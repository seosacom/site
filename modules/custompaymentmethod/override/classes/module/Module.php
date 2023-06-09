<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author     PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2015 PrestaShop SA
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
abstract class Module extends ModuleCore
{
    public static function getInstanceByName($module_name)
    {
        if (isset(self::$_INSTANCE[$module_name])) {
            return self::$_INSTANCE[$module_name];
        }
        if (strpos($module_name, 'custompaymentmethod_') !== false) {
            $payment_name_exp = explode('_', $module_name);
            if (!isset($payment_name_exp[1])) {
                return false;
            }
            if (!class_exists('SeoSaMockPayment', false)) {
                if (file_exists(_PS_MODULE_DIR_ . 'custompaymentmethod/classes/SeoSaMockPayment.php')) {
                    include_once _PS_MODULE_DIR_ . 'custompaymentmethod/classes/SeoSaMockPayment.php';
                } else {
                    return false;
                }
            }
            $method = new SeoSaMockPayment($payment_name_exp[1]);
            if ($method->active) {
                self::$_INSTANCE[$module_name] = $method;
                return $method;
            }
            return false;
        }
        return parent::getInstanceByName($module_name);
    }
}
