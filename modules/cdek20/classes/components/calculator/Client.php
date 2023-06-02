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

namespace Seleda\Cdek\Component\Calculator;

use \ClientCdek;

class Client extends ClientCdek
{
    protected static $instance;

    public function calculateList($data)
    {
        if (!$this->getToken()) {
            return false;
        }
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.'.($this->test ? 'edu.' : '').'cdek.ru/v2/calculator/tarifflist');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch); //  может быть false
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->logger->addMessage('Calculate list', 'Code '.$code, $data, $result);

        curl_close($ch);

        if ($result && $code < 300) {
            $this->result = $result;
            return true;
        } elseif ($result) {
            $this->error = $result;
            return false;
        } else {
            $this->error = json_encode(array('errors' => array(array('code' => '0', 'message' => 'No response from the server'))));
        }
        return false;
    }
}
