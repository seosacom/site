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
 * @author    SeoSA <885588@bk.ru>
 * @copyright 2012-2023 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
require_once _PS_MODULE_DIR_ . 'custompaymentmethod/classes/CustomPayment.php';

class CustomPaymentMethodValidationModuleFrontController extends ModuleFrontController
{
    public $custom_payment;
    public $controller_name;
    public function postProcess()
    {
        $cart = $this->context->cart;
        if ($id_custom_payment = (int) Tools::getValue('type')) {
            $this->module = Module::getInstanceByName($this->module->name . '_' . $id_custom_payment);
            if (Validate::isLoadedObject($this->module)) {
                $this->custom_payment = $this->module->custom_payment;
            }
        }
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0
            || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'custompaymentmethod_' . $id_custom_payment) {
                $authorized = true;
                break;
            }
        }
        if (!$authorized) {
            exit($this->module->l('This payment method is not available.', 'validation'));
        }
        if (!Validate::isLoadedObject($this->custom_payment)) {
            $this->custom_payment = new CustomPayment(Configuration::get(
                'PS_CPM_DEFAULT_METHOD'
            ), $this->context->language->id);
            if (!Validate::isLoadedObject($this->custom_payment)) {
                exit($this->module->l('This payment module not exists.', 'validation'));
            }
        }
        if ($this->module->checkAvailability() !== true) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        $custom_payment = $this->custom_payment;
        $customer = new Customer($cart->id_customer);
        $currency = $this->context->currency;
        $total_cart = (float) $cart->getOrderTotal(Configuration::get('PS_TAX'), Cart::BOTH);
        $mail_vars = [
            '{custom_payment_name}' => (Tools::strlen($custom_payment->name) ? $custom_payment->name : 'custom payment method'),
            '{custom_payment_details}' => $custom_payment->details,
        ];
        if (Tools::getValue('confirm') || !$custom_payment->confirmation_page) {
            $id_order_state = Configuration::get('PS_OS_PREPARATION');
            if ($custom_payment->id_order_state) {
                $order_state = new OrderState($custom_payment->id_order_state);
                if (Validate::isLoadedObject($order_state)) {
                    $id_order_state = $order_state->id;
                }
            }
            $calculations = CustomPaymentMethod::getOrderCalculations(
                $this->context->cart,
                $this->custom_payment,
                $this->context
            );
            $commission = $calculations['commission'];
            $discount = $calculations['discount'];
            $total = $calculations['total'];
            $mail_vars['{total_paid}'] = Tools::displayPrice($total, $this->context->currency, false);
            $mail_vars['{total_paid_m}'] = Tools::displayPrice($total, $this->context->currency, false);
            $mail_vars['{commission}'] = Tools::displayPrice($commission, $this->context->currency, false);
            $mail_vars['{discount}'] = Tools::displayPrice($discount, $this->context->currency, false);
            $this->context->cookie->{'cpm_commission_' . $this->getNextOrderId()} = $commission;
            $this->context->cookie->{'cpm_discount_' . $this->getNextOrderId()} = $discount;
            $this->module->validateOrder(
                (int) $cart->id,
                $id_order_state,
                $total_cart,
                Tools::strlen($custom_payment->name) ? $custom_payment->name : 'custom payment method',
                Tools::getValue('message') ? Tools::getValue('message') : null,
                $mail_vars,
                (int) $currency->id,
                false,
                $customer->secure_key
            );
            $order = new Order($this->module->currentOrder);
            if (version_compare(_PS_VERSION_, '1.7.0.0', '>=') && Validate::isLoadedObject($order)) {
                $old_message = Message::getMessageByCartId((int) $cart->id);
                if ($old_message && $old_message['private']) {
                    $update_message = new Message((int) $old_message['id_message']);
                    $update_message->id_order = (int) $order->id;
                    $update_message->update();
                    $customer_thread = new CustomerThread();
                    $customer_thread->id_contact = 0;
                    $customer_thread->id_customer = (int) $order->id_customer;
                    $customer_thread->id_shop = (int) $this->context->shop->id;
                    $customer_thread->id_order = (int) $order->id;
                    $customer_thread->id_lang = (int) $this->context->language->id;
                    $customer_thread->email = $customer->email;
                    $customer_thread->status = 'open';
                    $customer_thread->token = Tools::passwdGen(12);
                    $customer_thread->add();
                    $customer_message = new CustomerMessage();
                    $customer_message->id_customer_thread = $customer_thread->id;
                    $customer_message->id_employee = 0;
                    $customer_message->message = $update_message->message;
                    $customer_message->private = 1;
                    if (!$customer_message->add()) {
                        $this->errors[] = $this->trans(
                            'An error occurred while saving message',
                            [],
                            'Admin.Payment.Notification'
                        );
                    }
                }
                if (!empty(Tools::getValue('message')) && $old_message['message'] != Tools::getValue('message')) {
                    $customer_thread = new CustomerThread();
                    $customer_thread->id_contact = 0;
                    $customer_thread->id_customer = (int) $order->id_customer;
                    $customer_thread->id_shop = (int) $this->context->shop->id;
                    $customer_thread->id_order = (int) $order->id;
                    $customer_thread->id_lang = (int) $this->context->language->id;
                    $customer_thread->email = $customer->email;
                    $customer_thread->status = 'open';
                    $customer_thread->token = Tools::passwdGen(12);
                    $customer_thread->add();
                    $update_message = new Message();
                    $update_message->id_order = (int) $order->id;
                    $update_message->message = Tools::getValue('message');
                    $update_message->add();
                    $customer_message = new CustomerMessage();
                    $customer_message->id_customer_thread = $customer_thread->id;
                    $customer_message->id_employee = 0;
                    $customer_message->message = $update_message->message;
                    $customer_message->private = 1;
                    $customer_message->add();
                }
            }

            $total = CustomPaymentMethod::getOrderTotal(
                $this->context->cart,
                $this->custom_payment,
                $this->context
            );

            if ($custom_payment->is_send_mail) {
                $this->module->sendMail(
                    'select_method',
                    $customer->email,
                    $this->module->l('Select payment', 'validation'),
                    [
                        '{payment_name}' => $custom_payment->name,
                        '{payment_details}' => $custom_payment->details,
                        '{payment_description}' => $custom_payment->description,
                        '{firstname}' => $customer->firstname,
                        '{lastname}' => $customer->lastname,
                        '{total}' => Tools::displayPrice($total, $this->context->currency, false),
                        '{reference}' => $order->reference,
                    ]
                );
            }
            Tools::redirect(
                'index.php?controller=order-confirmation&id_cart='
                . (int) $cart->id . '&id_module='
                . (int) $this->module->id . '&type='
                . $custom_payment->id . '&id_order='
                . $this->module->currentOrder . '&key=' . $customer->secure_key
            );
        }
    }

    public function initContent()
    {
        $this->display_column_left = false;
        parent::initContent();
        $type = (int) Tools::getValue('type');
        if (!Validate::isLoadedObject(new CustomPayment($type, $this->context->language->id))) {
            $type = Configuration::get('PS_CPM_DEFAULT_METHOD');
        }
        $total = CustomPaymentMethod::getOrderTotal(
            $this->context->cart,
            $this->custom_payment,
            $this->context
        );
        if (!file_exists(_PS_MODULE_DIR_ . 'custompaymentmethod/logos/' . $this->custom_payment->logo)) {
            $this->custom_payment->logo = 0;
        }
        $this->context->smarty->assign(
            [
                'total' => $total,
                'this_path' => $this->module->getPathUri(),
                'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/',
                'type' => $type,
                'custom_payment' => $this->custom_payment,
                'ps_version_cpm' => _PS_VERSION_,
                'name_message_field' => $this->custom_payment->name_message_field,
                'required_message_field' => $this->custom_payment->required_message_field,
                'error_message_field' => $this->custom_payment->error_message_field,
                'id_currency' => $this->context->currency->id,
            ]
        );
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            $this->module->name = 'custompaymentmethod'; // for path
            $this->setTemplate('validation.tpl');
        } else {
            ToolsModuleCMP::registerSmartyFunctions();
            $this->setTemplate('module:custompaymentmethod/views/templates/front/validation_17.tpl');
        }
    }

    public static function getNextOrderId()
    {
        $id_last_order = (int) Db::getInstance()->getValue('SELECT MAX(id_order) FROM ' . _DB_PREFIX_ . 'orders');
        return $id_last_order + 1;
    }
}
