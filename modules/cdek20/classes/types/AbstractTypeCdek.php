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
class AbstractTypeCdek
{
    protected $context;

    public function __construct($vars = false)
    {
        if ($vars) {
            if ($this->isJson($vars)) {
                $vars = json_decode($vars);
            }

            foreach ($vars as $key => $var) {
                if (is_array($var) && count($var) == 0) {
                    continue;
                }
                $class_name = $this->getClassName($key);
                if (property_exists($this, $key) && is_array($this->{$key}) && $class_name && is_array($var)) {
                    foreach ($var as $item) {
                        $this->{$key}[] = new $class_name($item);
                    }
                } elseif ($class_name && property_exists($this, $key)) {
                    $this->{$key} = new $class_name($var);
                } elseif (property_exists($this, $key)) {
                    $this->{$key} = $var;
                }
            }
        }

        if ($this instanceof EntityCdek) {
            $this->context = Context::getContext();
        }
    }

    public function propertiesToArray()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => &$var) {
            if (!$var) {
                continue;
            }
            if (is_array($var)) {
                foreach ($var as &$item) {
                    $item = $item->propertiesToArray();
                }
            } elseif ($var instanceof Context) {
                unset($vars[$key]);
            } elseif (is_object($var)) {
                $var = $var->propertiesToArray();
            }
        }

        return $vars;
    }

    public static function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }

    public function getClassName($name)
    {
        if (get_class($this) == 'SellerCdek') { // The Seller object has all fields - strings
            return false;
        }
        if (class_exists(Tools::toCamelCase($name, true) . 'Cdek')) {
            $exceptions = ['city'];
            if (!in_array($name, $exceptions)) {
                return Tools::toCamelCase($name, true) . 'Cdek';
            }
        }
        $array = [
            'delivery_recipient_cost' => 'money',
            'delivery_recipient_cost_adv' => 'threshold',
            'sender' => 'contact',
            'from_location' => 'location',
            'to_location' => 'location',
            'statuses' => 'status',
            'recipient' => 'contact',
            'payment' => 'money',
            'phones' => 'phone',
            'services' => 'service',
            'packages' => 'package',
            'items' => 'item',
            'requests' => 'request',
            'errors' => 'error',
            'warnings' => 'warning',
        ];
        if (array_key_exists($name, $array)) {
            return ucfirst($array[$name]) . 'Cdek';
        }
        return false;
    }
}
