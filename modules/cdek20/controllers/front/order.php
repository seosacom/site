<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
use Seleda\Cdek\Component\Order\Client;

class Cdek20OrderModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();

        if (Tools::getValue('ajax')) {
            $action = Tools::toCamelCase(Tools::getValue('action'));
            if ($action) {
                $this->{$action}(${'_POST'} + ${'_GET'});
            } else {
                throw new Exception('No action for this request!');
            }
        }
    }

    private function сallCourier($params)
    {
        $config = ConfigurationCdek::get();
        $order = new Order((int) $params['id_order']);
        $today = date('Y-m-d');
        $dates = date('Y-m-d', strtotime($today . '+' . $config->waiting_date_courier . 'days'));
        $cdek_order = new OrderCdek($order);
        $this->logger = new LoggerCdek();

        $intake_date = $dates;
        $intake_time_to = $config->courier_start_time;
        $intake_time_from = $config->end_time_for_courier;
        $name = $config->sender_name;
        $number = $config->sender_phone;
        if (date('H:i') > '14:00' && $config->waiting_date_courier == 0) {
            $intake_date = date('Y-m-d', strtotime($today . '+' . 1 . 'days'));
        }

        $data = [
            'cdek_number' => $cdek_order->getEntity()->getCdekNumber(),
            'order_uuid' => $cdek_order->getEntity()->getUuid(),
            'intake_date' => $intake_date,
            'intake_time_from' => $intake_time_to,
            'intake_time_to' => $intake_time_from,
            'sender' => [
                'name' => $name,
                'phones' => [
                    'number' => $number,
                ],
            ],
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.cdek.ru/v2/intakes');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . ClientCdek::getToken(), 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->addMessage('Call Courier', 'Code ' . $code, json_encode($data, true), $result);
        $dat = json_decode($result, true);
        if ($dat['requests'][0]['state'] == 'ACCEPTED') {
            Db::getInstance()->update('cdek_order', ['call_courier' => $result], 'id_order = ' . (int) $params['id_order']);
        }

        $cdek = Module::getInstanceByName('cdek20');
        $html = $cdek->hookDisplayAdminOrderContentShip(['order' => $order]);
        exit($html);
    }

    private function cancelCall($params, $remove = false)
    {
        $order = new Order((int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $this->logger = new LoggerCdek();
        $datas = db::getInstance()->getValue('select `call_courier` from `' . _DB_PREFIX_ . 'cdek_order` where id_order = ' . (int) $params['id_order']);
        $dat = json_decode($datas, true);
        if(!empty($dat)) {
            $uid = $dat['entity']['uuid'];
        }

        if (!empty($uid) && !empty(ClientCdek::getToken())) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.cdek.ru/v2/intakes/' . $uid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . ClientCdek::getToken(), 'Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            $result = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $this->logger->addMessage('Cancel Courier', 'Code ' . $code, $uid, $result);
            $dat = json_decode($result, true);
            if ($dat) {
                if ($dat['requests'][0]['state'] == 'ACCEPTED' || $remove == true) {
                    Db::getInstance()->update('cdek_order', ['call_courier' => ''], 'id_order = ' . (int) $params['id_order']);
                }
            }
        }
        if ($remove) {
            return true;
        }
        $cdek = Module::getInstanceByName('cdek20');
        $html = $cdek->hookDisplayAdminOrderContentShip(['order' => $order]);
        exit($html);
    }

    private function generate($params)
    {
        $order = new Order((int) $params['id_order']);
        $cart = new Cart($order->id_cart);
        $cdek = Module::getInstanceByName('cdek20');
        $cdek->hookActionValidateOrder(['order' => $order, 'cart' => $cart]);
        $html = $cdek->hookDisplayAdminOrderContentShip(['order' => $order]);

        exit($html);
    }

    private function regenerate($params)
    {
        $order = new Order((int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $number = $cdek_order->getEntity()->getNumber();
        Db::getInstance()->delete('cdek_order', '`id_order` = ' . (int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $cdek_order->getEntity()->setNumber($number);
        $cdek_order->getEntity()->setComment($params['note']);
        $cdek_order->getEntity()->setDateRegenerate(date('Y-m-d H:i:s'));
        $cdek_order->save();
        $this->generate($params);
    }

    private function createWithFullPayment($params)
    {
        $this->create($params, 0);
    }

    private function createWithPaymentWithoutDelivery($params)
    {
        $this->create($params, 1);
    }

    private function createWithoutPayment($params)
    {
        $this->create($params, 2);
    }

    private function create($params, $cod = 0)
    {
        $client = Client::getInstance();
        $entity = Db::getInstance()->getValue('SELECT `entity` FROM `' . _DB_PREFIX_ . 'cdek_order` WHERE `id_order` = ' . (int) $params['id_order']);
        $entity = json_decode($entity, true);

        // Если заказ не доставляется до ПВЗ, то не надо передавать пустые delivery_point и/или shipment_point
        // т.к. по ним в первую очередь определяется город
        if (isset($entity['delivery_point']) && !$entity['delivery_point']) {
            unset($entity['delivery_point']);
        }
        if (isset($entity['shipment_point']) && !$entity['shipment_point']) {
            unset($entity['shipment_point']);
        }

        // new REQ-00111 Обновленная
        if (in_array($cod, [0, 1])) {
            foreach ($entity['packages'] as &$package) {
                foreach ($package['items'] as &$item) {
                    $item['payment']['value'] = 0;
                }
            }
        }

        // new REQ-00111
        if (in_array($cod, [0])) {
            $entity['delivery_recipient_cost']['value'] = 0;
        }

        $entity = json_encode($entity, JSON_UNESCAPED_UNICODE);
        if ($client->createOrder($entity)) {
            $order = new Order($params['id_order']);
            $result = $client->getResult();
            $cdek_order = new OrderCdek($order);
            $cdek_order->getEntity()->setUuid($result['entity']['uuid']);
            $cdek_order->getEntity()->setTypeCreate($cod);
            $cdek_order->setRequests($result['requests']);
            $cdek_order->save();
        } else {
            $error = $client->getError();
            $this->context->smarty->assign('error', (isset($this->errors[0]) ? $this->errors[0]['message'] . ', ' : '') . $error['message']);
        }
        $cdek = Module::getInstanceByName('cdek20');
        $html = $cdek->hookDisplayAdminOrderContentShip(['order' => $order]);
        exit($html);
    }

    private function info($params)
    {
        $order = new Order((int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $uuid = $cdek_order->getEntity()->getUuid();
        $client = Client::getInstance();
        if ($client->getOrderInfo($uuid)) {
            $result = $client->getResult();
            $entity_arr = $cdek_order->getEntity()->propertiesToArray();
            $cdek_order->setEntity(array_merge($entity_arr, $result['entity']));
            $cdek_order->setRequests($result['requests']);
            $cdek_order->getEntity()->setDateRefresh(date('Y-m-d H:i:s'));
            if (!isset($result['related_entities'])) {
                $result['related_entities'] = null;
            }
            $cdek_order->setRelatedEntities($result['related_entities']);
            $cdek_order->save();
            $order->shipping_number = $cdek_order->getEntity()->getCdekNumber();
            $order->save();
            Db::getInstance()->update('order_carrier', ['tracking_number' => $cdek_order->getEntity()->getCdekNumber()], 'id_order = ' . (int) $order->id);
        } else {
            $error = $client->getError();
            if ($_GET['action'] != 'remove') {
                $this->context->smarty->assign('error', (isset($this->errors[0]) ? $this->errors[0]['message'] . ', ' : '') . $error['message']);
            }
            if ($error['code'] == 'v2_entity_not_found') {
                $cdek_order->getEntity()->setCdekNumber(null);
                $request = new RequestCdek();
                $request->setType('DELETE')->setState('ACCEPTED');
                $cdek_order->setRequests([$request]);
                $cdek_order->save();
            }
        }
        $cdek = Module::getInstanceByName('cdek20');
        $html = $cdek->hookDisplayAdminOrderContentShip(['order' => $order]);
        exit($html);
    }

    private function remove($params)
    {
        $order = new Order((int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $this->cancelCall($params, true);
        $client = Client::getInstance();
        if ($client->deleteOrder($cdek_order->getEntity()->getUuid())) {
            $order->shipping_number = null;
            $order->save();
            $result = $client->getResult();
            $cdek_order->getEntity()->setCdekNumber(null);
            $cdek_order->setRequests($result['requests']);
            $cdek_order->setRelatedEntities(null);
            $cdek_order->save();
        }
        $this->info($params);
    }

    private function createInvoice($params)
    {
        $order = new Order((int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $uuid = $cdek_order->getEntity()->getUuid();
        $request_params = [
            'orders' => [
                'order_uuid' => $uuid,
            ],
        ];
        $client = Client::getInstance();
        if ($client->createInvoice($request_params)) {
            $result = $client->getResult();
            $cdek_order->getEntity()->setCdekNumber(null);
            $cdek_order->setRequests($result['requests']);
            $cdek_order->save();
        }
        $this->info($params);
    }

    private function loadInvoice($params)
    {
        $order = new Order((int) $params['id_order']);
        $cdek_order = new OrderCdek($order);
        $uuid_invoice = $cdek_order->getUuidInvoice();
        $client = Client::getInstance();
        if ($client->getInvoice($uuid_invoice)) {
            $result = $client->getResult();
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename=' . $uuid_invoice . '.pdf');
            exit($result);
        }
        exit('Обновите вкладку Сдэк или сформируйте накладную заново(срок действия ссылки 1 час)');
    }
}
