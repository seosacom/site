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
class OrderCdek extends Seleda\Cdek\Component\Order\Order
{
    private $order;
    public $id_order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->id_order = $order->id;

        $data = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'cdek_order` WHERE `id_order` = '.(int)$order->id);
        if (!$data) {
            $data = array();
            $data['entity'] = null;
            $data['requests'] = null;
            $data['related_entities'] = null;
            $data['order_note'] = null;
        }

        $this->entity = new EntityCdek($data['entity']);

        $requests = json_decode($data['requests']);
        if (is_array($requests)) {
            foreach ($requests as $request) {
                $this->requests[] = new RequestCdek($request);
            }
        }

        $related_entities = json_decode($data['related_entities']);
        if (is_array($related_entities)) {
            foreach ($related_entities as $related_entity) {
                $this->related_entities[] = new RelatedEntityCdek($related_entity);
            }
        }
    }

    public function save()
    {
        if (is_array($this->entity)) {
            $this->entity = (object)$this->entity;
        }

        $entity = $this->entity->propertiesToArray();

        //Sender address and sender shipment point can't be filled both
        if ($entity['shipment_point']) {
            unset($entity['from_location']);
        } else {
            unset($entity['shipment_point']);
        }
        //Recipient address and recipient delivery point can't be filled both
        if ($entity['delivery_point']) {
            unset($entity['to_location']);
        } else {
            unset($entity['delivery_point']);
        }
     //   unset($entity['seller']);

        $requests = array();
        foreach ($this->requests as $request) {
            $request = new RequestCdek((object)$request);
            $requests[] = $request->propertiesToArray();
        }

        $related_entities = array();
        foreach ($this->related_entities as $related_entity) {
            $related_entities[] = $related_entity->propertiesToArray();
        }

        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'cdek_order` (
                    `id_order`, 
                    `entity`, 
                    `requests`, 
                    `related_entities`) 
                    VALUES (
                    ' . (int)$this->id_order . ', 
                    "' . pSQL(json_encode($entity, JSON_UNESCAPED_UNICODE)) . '", 
                    "' . pSQL(json_encode($requests)) . '",
                    "' . pSQL(json_encode($related_entities)) . '") 
                    ON DUPLICATE KEY UPDATE `entity` = "' . pSQL(json_encode($entity, JSON_UNESCAPED_UNICODE)) . '",
                    `requests` = "' . pSQL(json_encode($requests)) . '",
                    `related_entities` = "' . pSQL(json_encode($related_entities)) . '"';

        Db::getInstance()->execute($sql);
    }

    public function generateOrderNumber($prod = true)
    {
        if ($number = $this->entity->getNumber()) {
            if (strpos($number, '_') !== false) {
                $number = preg_replace_callback(
                    '/([^_]+)_(\d+)/',
                    function ($match) {
                        return $match[1].'_'.($match[2] + 1);
                    },
                    $number
                );
            } else {
                $number = $number.'_2';
            }
        } else {
            $number = $prod ? $this->id_order : md5(time());
        }

        $this->entity->setNumber($number);
    }

    public function getInvoiceLoadLink()
    {
        return Context::getContext()->link->getModuleLink('cdek20', 'order', array('action' => 'load_invoice', 'id_order' => $this->order->id, 'ajax' => 1));
    }
}
