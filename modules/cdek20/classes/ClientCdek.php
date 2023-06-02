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
use Seleda\Cdek\Component\Calculator\IClient as ICalculatorClient;
use Seleda\Cdek\Component\City\IClient as ICityClient;
abstract class ClientCdek implements ICalculatorClient, ICityClient
{
    protected $lang;
    protected $test = true;
    protected $result;
    protected $error;
    protected $authLogin = 'EMscd6r9JnFiQ3bLoyjJY6eM78JrJceI'; // test
    protected $authPassword = 'PjLZkKBHEiLK3YsjtNrt3TGNG0ahs3kG'; // test
    protected $logger;

    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
        $this->lang = LangCdek::getInstance(Context::getContext()->language)->getLang();
        $account = ConfigurationCdek::get('account');
        $secure_password = ConfigurationCdek::get('secure_password');
        if ($account && $secure_password && $account != $this->authLogin) {
            $this->test = false;
            $this->authLogin = $account;
            $this->authPassword = $secure_password;
        }
        $this->logger = new LoggerCdek();
    }

    public function getToken()
    {
        if ((!$auth = json_decode(Configuration::get('CDEK_API_TOKEN'), true)) || (is_array($auth) && !key_exists('expires_in', $auth)) ||
            time() - $auth['timestamp'] > $auth['expires_in'] - 30) { // 30 секунд - погрешность
            $data = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->authLogin,
                'client_secret' => $this->authPassword,
            ];
            $data_string = http_build_query($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.' . ($this->test ? 'edu.' : '') . 'cdek.ru/v2/oauth/token?parameters');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

            $result = curl_exec($ch);

            curl_close($ch);
            if ($result && !isset($result['error'])) {
                $auth = json_decode($result, true);
                $auth['timestamp'] = time();
                Configuration::updateValue('CDEK_API_TOKEN', json_encode($auth));
            }
        }

        return isset($auth['access_token']) ? $auth['access_token'] : false;
    }

    protected function logRequest($name, $request, $response)
    {
        Db::getInstance()->insert();
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getError()
    {
        return $this->error;
    }
}
