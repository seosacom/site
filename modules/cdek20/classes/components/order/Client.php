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

namespace Seleda\Cdek\Component\Order;

class Client extends \ClientCdek
{
    protected static $instance;

    public function createOrder($entity)
    {
        if (!$this->getToken()) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.'.($this->test ? 'edu.' : '').'cdek.ru/v2/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $entity);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->addMessage('Create order', 'Code '.$code, $entity, $result);

        $result = json_decode($result, true);
        if ($result && in_array($code, array(200, 202))) {
            $this->result = $result;
            return true;
        } else {
            $this->setErrorForOrder($result);
            return false;
        }
    }

    public function getOrderInfo($uuid)
    {
        if (!$this->getToken()) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.'.($this->test ? 'edu.' : '').'cdek.ru/v2/orders/'.$uuid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->addMessage('Info order', 'Code '.$code, $uuid, $result);

        $result = json_decode($result, true);

        if ($result && in_array($code, array(200, 202))) {
            $this->result = $result;
            return true;
        } else {
            $this->setErrorForOrder($result);
            return false;
        }
    }

    public function deleteOrder($uuid)
    {
        if (!$this->getToken()) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.'.($this->test ? 'edu.' : '').'cdek.ru/v2/orders/'.$uuid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->addMessage('Delete order', 'Code '.$code, $uuid, $result);

        $result = json_decode($result, true);
        if ($result && in_array($code, array(200, 202))) {
            $this->result = $result;
            return true;
        } else {
            $this->setErrorForOrder($result);
            return false;
        }
    }

    public function createInvoice($params)
    {
        if (!$this->getToken()) {
            return false;
        }
        if (is_array($params)) {
            $params = json_encode($params);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.'.($this->test ? 'edu.' : '').'cdek.ru/v2/print/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->addMessage('Create invoice', 'Code '.$code, $params, $result);

        $result = json_decode($result, true);
        if ($result && in_array($code, array(200, 202))) {
            $this->result = $result;
            return true;
        } else {
            $this->setErrorForOrder($result);
            return false;
        }
    }

    public function getInvoice($uuid)
    {
        if (!$this->getToken()) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.'.($this->test ? 'edu.' : '').'cdek.ru/v2/print/orders/'.$uuid.'.pdf');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($result && in_array($code, array(200, 202))) {
            $this->result = $result;
            return true;
        } else {
            $this->logger->addMessage('Get invoice', 'Code '.$code, $uuid, $result);
            return false;
        }
    }

    private function setErrorForOrder($result)
    {
        $this->error = array();
        if (!$result) {
            $this->error['code'] = 'unknown';
            $this->error['message'] = 'The server did not respond';
        } elseif (isset($result['error'])) {
            $this->error['code'] = 'unknown';
            $this->error['message'] = $result['error'];
        } elseif (isset($result['requests'])) {
            $request = $result['requests'][count($result['requests']) - 1];
            $this->error['code'] = $request['errors'][0]['code'];
            $this->error['message'] = $request['errors'][0]['message'];
        } else {
            $this->error = 'Unknown error';
        }
    }
}
