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

if (!class_exists('CustomPaymentMethod')) {
    require_once _PS_MODULE_DIR_.'custompaymentmethod/custompaymentmethod.php';
}
if (!class_exists('CustomPayment')) {
    require_once _PS_MODULE_DIR_.'custompaymentmethod/classes/CustomPayment.php';
}
if (!class_exists('PriceFormatter')) {
    require_once _PS_ROOT_DIR_.'/src/Adapter/Product/PriceFormatter.php';
}

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

class SeoSaCartPresenter extends \PrestaShop\PrestaShop\Adapter\Cart\CartPresenter
{
    private $module;
    private $context;
    private $priceFormatter;
    
    public function __construct()
    {
        parent::__construct();
        $this->module = Module::getInstanceByName('custompaymentmethod');
        $this->context = Context::getContext();
        $this->priceFormatter = new PriceFormatter();
    }
    
    public function present($cart, $shouldSeparateGifts = false)
    {
        $order_total = $cart->getOrderTotal(true, Cart::BOTH);
        $isFree = 0 == (float)$order_total;
        $payment_options_finder = new PaymentOptionsFinder();
        $payment_options = $payment_options_finder->present($isFree);
        if (!isset($payment_options[$this->module->name])) {
            return parent::present($cart, $shouldSeparateGifts);
        }
        $custom_payments = CustomPayment::getCustomPaymentMethodsCollection($this->context->language->id, true);
        $js_def = [];
        foreach ($payment_options[$this->module->name] as $payment_option) {
            foreach ($custom_payments as $payment) {
                if ($payment_option['call_to_action_text'] == $payment->name) {
                    $commission_value = CustomPaymentMethod::getCommission(
                        $cart,
                        $payment
                    );
                    $discount_value = CustomPaymentMethod::getDiscount(
                        $cart,
                        $payment
                    );
                    $js_def[$payment_option['id']] = array(
                        'commission'            => $this->priceFormatter->format($commission_value),
                        'display_commission'    => ($commission_value != 0) ? true : false,
                        'discount'              => $this->priceFormatter->format($discount_value),
                        'display_discount'      => ($discount_value != 0) ? true : false,
                        'total_incl_commission' => $this->priceFormatter->format(
                            CustomPaymentMethod::getOrderTotal(
                                $cart,
                                $payment
                            )
                        ),
                    );
                }
            }
            $js_def['total_excl_commission'] = $this->priceFormatter->format($order_total);
        }
        Media::addJsDef(array('custom_payments_options' => $js_def));
        $present = parent::present($cart, $shouldSeparateGifts);
        $present['subtotals']['commission'] = array(
            'type'   => 'commission',
            'label'  => $this->module->l('Commission'),
            'amount' => null,
            'value'  => null,
        );
        $present['subtotals']['discount-payment'] = array(
            'type'   => 'discount-payment',
            'label'  => $this->module->l('Payment discount'),
            'amount' => null,
            'value'  => null,
        );
        
        return $present;
    }
}
