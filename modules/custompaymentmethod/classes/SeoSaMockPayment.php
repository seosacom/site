<?php
/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright  2012-2023 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

require_once _PS_MODULE_DIR_.'custompaymentmethod/custompaymentmethod.php';

class SeoSaMockPayment extends CustomPaymentMethod
{
    public $id;
    public $active;
    public $custom_payment;
    public $description;
    public $description_short;
    public $_path = null;
    public $local_path = null;

    public function __construct($id_custom_payment_method = null)
    {
        parent::__construct();

        $this->version = '1.0.0';
        $this->name = 'custompaymentmethod_'.$id_custom_payment_method;

        $sql = 'SELECT `id_module` FROM `'._DB_PREFIX_.'module` 
        WHERE `name` = "custompaymentmethod_'.pSQL($id_custom_payment_method).'"';
        if ($id = Db::getInstance()->getValue($sql)) {
            $this->id = $id;
        }


        $this->custom_payment = new CustomPayment($id_custom_payment_method, $this->context->language->id);

        $this->active = $this->custom_payment->active;

        $this->displayName = $this->custom_payment->name;
    }

    public function hookDisplayAdminOrder($params)
    {
        return;
    }

    public function hookDisplayBackOfficeHeader()
    {
        return;
    }
    
    public function isUsingNewTranslationSystem()
    {
        return false;
    }

    public function hookDisplayCommissionForPDF($params)
    {
        return;
    }

    public function hookDisplayOrderDetail($params)
    {
        return;
    }

    public static function setMethod($id = null)
    {
        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'module` SET `active` = 1 WHERE `name` = "'
            .pSQL('custompaymentmethod_'.(int)$id).'"'
        );
    }
    
    public static function unsetMethod($id = null)
    {
        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'module` SET `active` = 0  WHERE `name` = "'
            .pSQL('custompaymentmethod_'.(int)$id).'"'
        );
    }
    
    public static function delete($id = null)
    {
        $id_module = Db::getInstance()->getValue(
            'SELECT `id_module` FROM `'._DB_PREFIX_.'module` WHERE `name` = "'
            .pSQL('custompaymentmethod_'.(int)$id).'"'
        );

        if (!$id_module) {
            return;
        }

        $module = Module::getInstanceByName('custompaymentmethod_'.(int)$id);
        $module->uninstall();
    }

    public function checkAvailability()
    {
        $available_countries = ($this->custom_payment->available_countries ?
            explode(',', $this->custom_payment->available_countries) : []);

        if (!in_array($this->context->country->id, $available_countries)) {
            return 'country';
        }

        $total_price = $this->context->cart->getOrderTotal();

        $currency_to = new Currency($this->context->cart->id_currency);
        $currency_from = new Currency($this->custom_payment->select_currency);
        if ($currency_to != $currency_from) {
            $from = Tools::convertPriceFull($this->custom_payment->cart_total_from, $currency_from, $currency_to);
            $to = Tools::convertPriceFull($this->custom_payment->cart_total_to, $currency_from, $currency_to);
            $this->custom_payment->cart_total_from = $from;
            $this->custom_payment->cart_total_to = $to;
        }

        if (!$this->custom_payment->show_method_available) {
            if ($this->custom_payment->cart_total_from > 0 && $this->custom_payment->cart_total_from > $total_price) {
                return 'total_from';
            }
            if ($this->custom_payment->cart_total_to > 0 && $this->custom_payment->cart_total_to < $total_price) {
                return 'total_to';
            }
        }

        $available_currencies = ($this->custom_payment->available_currencies ?
            explode(',', $this->custom_payment->available_currencies) : []);

        if (!in_array($this->context->currency->id, $available_currencies)) {
            return 'currency';
        }

        $available_carriers = ($this->custom_payment->available_carriers ?
            explode(',', $this->custom_payment->available_carriers) : []);

        $carrier = new Carrier($this->context->cart->id_carrier);
        $nik = 0;
        if (!in_array($carrier->id_reference, $available_carriers)) {
            foreach ($this->context->cart->getProducts() as $item) {
                if ($item['is_virtual'] == 1) {
                    $nik = 1;
                }
            }
            if ($nik == 0) {
                return 'carrier';
            } else {
                return true;
            }
        }

        $available_groups = ($this->custom_payment->available_groups ?
            explode(',', $this->custom_payment->available_groups) : []);

        $customer_groups = Db::getInstance()->executeS('SELECT `id_group` 
        FROM `' . _DB_PREFIX_ . 'customer_group` 
        WHERE `id_customer` = ' . (int) ($this->context->customer->id));
        foreach ($customer_groups as $key => $group) {
            foreach ($available_groups as $group_modul) {
                if ($group['id_group'] == $group_modul) {
                    return true;
                }
            }
        }

        if (!in_array($this->context->customer->id_default_group, $available_groups)) {
            return 'customer_group';
        }

        return true;
    }

    public function l($string, $specific = false, $locale = null)
    {
        $default_module = Module::getInstanceByName('custompaymentmethod');
        return $default_module->l($string, $specific);
    }
    public function hookOrderConfirmation($params)
    {

        if (!file_exists(_PS_MODULE_DIR_.'custompaymentmethod/logos/'.$this->custom_payment->logo)) {
            $this->custom_payment->logo = 0;
        }

        $name = 'custompaymentmethod_'.$this->custom_payment->id;

        $id_message = Db::getInstance()->getValue(
            'SELECT `id_message`
                     FROM ' . _DB_PREFIX_ . 'message 
                     WHERE id_cart = ' . Tools::getValue('id_cart')
        );

        $message = Db::getInstance()->getValue(
            'SELECT `message`
                     FROM ' . _DB_PREFIX_ . 'message 
                     WHERE id_message = ' . ($id_message + 1)
        );

        if (!$message) {
            $message = Db::getInstance()->getValue(
                'SELECT `message`
                     FROM ' . _DB_PREFIX_ . 'message 
                     WHERE id_message = ' . ($id_message)
            );
        }

        $id_order = $params['order']->id;
        $order = new Order($id_order);
        $commission = CustomPayment::getOrderCommission($id_order);
        $commission_total = isset($commission['commission']) ? $commission['commission'] : 0;
        $discount = CustomPayment::getOrderDiscount($id_order);
        $discount_total = isset($discount['discount']) ? $discount['discount'] : 0;

        if ($this->custom_payment->confirmation_page_add == 1 && $params['order']->module == $name) {
            $this->context->smarty->assign(
                array(
                    'name' => $this->custom_payment->name,
                    'logo' => $this->custom_payment->logo,
                    'this_path' => $this->_path,
                    'details' => $this->custom_payment->details,
                    'description' => $this->custom_payment->description,
                    'description_short' => $this->custom_payment->description_short,
                    'ps_message_field'     => $message,
                    'total_paid' => Tools::displayPrice($params['order']->total_paid, (int)$params['order']->id_currency),
                    'commission'        => (float)$commission_total,
                    'format_commission' => Tools::displayPrice((float)$commission_total, new Currency($order->id_currency)),
                    'discount'          => (float)$discount_total,
                    'format_discount'   => Tools::displayPrice((float)$discount_total, new Currency($order->id_currency)),
                )
            );
            return $this->context->smarty->fetch($this->local_path . 'views/templates/hook/order-confirmation.tpl');
        }
        return "";
    }
}
