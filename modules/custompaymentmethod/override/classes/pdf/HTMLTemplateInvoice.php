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
class HTMLTemplateInvoice extends HTMLTemplateInvoiceCore
{
    public function getContent()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $invoiceAddressPatternRules = json_decode(Configuration::get('PS_INVCE_INVOICE_ADDR_RULES'), true);
            $deliveryAddressPatternRules = json_decode(Configuration::get('PS_INVCE_DELIVERY_ADDR_RULES'), true);
            $invoice_address = new Address((int) $this->order->id_address_invoice);
            $country = new Country((int) $invoice_address->id_country);
            $formatted_invoice_address = AddressFormat::generateAddress($invoice_address, $invoiceAddressPatternRules, '<br />', ' ');
            $delivery_address = null;
            $formatted_delivery_address = '';
            if (isset($this->order->id_address_delivery) && $this->order->id_address_delivery) {
                $delivery_address = new Address((int) $this->order->id_address_delivery);
                $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, $deliveryAddressPatternRules, '<br />', ' ');
            }
            $customer = new Customer((int) $this->order->id_customer);
            $carrier = new Carrier((int) $this->order->id_carrier);
            $order_details = $this->order_invoice->getProducts();
            $has_discount = false;
            foreach ($order_details as $id => &$order_detail) {
                if ($order_detail['reduction_amount_tax_excl'] > 0) {
                    $has_discount = true;
                    $order_detail['unit_price_tax_excl_before_specific_price'] = $order_detail['unit_price_tax_excl_including_ecotax'] + $order_detail['reduction_amount_tax_excl'];
                } elseif ($order_detail['reduction_percent'] > 0) {
                    $has_discount = true;
                    if ($order_detail['reduction_percent'] == 100) {
                        $order_detail['unit_price_tax_excl_before_specific_price'] = 0;
                    } else {
                        $order_detail['unit_price_tax_excl_before_specific_price'] = (100 * $order_detail['unit_price_tax_excl_including_ecotax']) / (100 - $order_detail['reduction_percent']);
                    }
                }
                $taxes = OrderDetail::getTaxListStatic($id);
                $tax_temp = [];
                foreach ($taxes as $tax) {
                    $obj = new Tax($tax['id_tax']);
                    $translator = Context::getContext()->getTranslator();
                    $tax_temp[] = $translator->trans(
                        '%taxrate%%space%%',
                        [
                            '%taxrate%' => ($obj->rate + 0),
                            '%space%' => '&nbsp;',
                        ],
                        'Shop.Pdf'
                    );
                }
                $order_detail['order_detail_tax'] = $taxes;
                $order_detail['order_detail_tax_label'] = implode(', ', $tax_temp);
            }
            unset(
                $tax_temp,
                $order_detail
            );
            if (Configuration::get('PS_PDF_IMG_INVOICE')) {
                foreach ($order_details as &$order_detail) {
                    if ($order_detail['image'] != null) {
                        $name = 'product_mini_' . (int) $order_detail['product_id'] . (isset($order_detail['product_attribute_id']) ? '_' . (int) $order_detail['product_attribute_id'] : '') . '.jpg';
                        $path = _PS_PROD_IMG_DIR_ . $order_detail['image']->getExistingImgPath() . '.jpg';
                        $order_detail['image_tag'] = preg_replace(
                            '/\.*' . preg_quote(__PS_BASE_URI__, '/') . '/',
                            _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR,
                            ImageManager::thumbnail($path, $name, 45, 'jpg', false),
                            1
                        );
                        if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                            $order_detail['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                        } else {
                            $order_detail['image_size'] = false;
                        }
                    }
                }
                unset($order_detail);
            }
            $cart_rules = $this->order->getCartRules($this->order_invoice->id);
            $free_shipping = false;
            foreach ($cart_rules as $key => $cart_rule) {
                if ($cart_rule['free_shipping']) {
                    $free_shipping = true;
                    $cart_rules[$key]['value_tax_excl'] -= $this->order_invoice->total_shipping_tax_excl;
                    $cart_rules[$key]['value'] -= $this->order_invoice->total_shipping_tax_incl;
                    if ($cart_rules[$key]['value'] == 0) {
                        unset($cart_rules[$key]);
                    }
                }
            }
            $product_taxes = 0;
            foreach ($this->order_invoice->getProductTaxesBreakdown($this->order) as $details) {
                $product_taxes += $details['total_amount'];
            }
            $product_discounts_tax_excl = $this->order_invoice->total_discount_tax_excl;
            $product_discounts_tax_incl = $this->order_invoice->total_discount_tax_incl;
            if ($free_shipping) {
                $product_discounts_tax_excl -= $this->order_invoice->total_shipping_tax_excl;
                $product_discounts_tax_incl -= $this->order_invoice->total_shipping_tax_incl;
            }
            $products_after_discounts_tax_excl = $this->order_invoice->total_products - $product_discounts_tax_excl;
            $products_after_discounts_tax_incl = $this->order_invoice->total_products_wt - $product_discounts_tax_incl;
            $shipping_tax_excl = $free_shipping ? 0 : $this->order_invoice->total_shipping_tax_excl;
            $shipping_tax_incl = $free_shipping ? 0 : $this->order_invoice->total_shipping_tax_incl;
            $shipping_taxes = $shipping_tax_incl - $shipping_tax_excl;
            $wrapping_taxes = $this->order_invoice->total_wrapping_tax_incl - $this->order_invoice->total_wrapping_tax_excl;
            $total_taxes = $this->order_invoice->total_paid_tax_incl - $this->order_invoice->total_paid_tax_excl;
            $footer = [
                'products_before_discounts_tax_excl' => $this->order_invoice->total_products,
                'product_discounts_tax_excl' => $product_discounts_tax_excl,
                'products_after_discounts_tax_excl' => $products_after_discounts_tax_excl,
                'products_before_discounts_tax_incl' => $this->order_invoice->total_products_wt,
                'product_discounts_tax_incl' => $product_discounts_tax_incl,
                'products_after_discounts_tax_incl' => $products_after_discounts_tax_incl,
                'product_taxes' => $product_taxes,
                'shipping_tax_excl' => $shipping_tax_excl,
                'shipping_taxes' => $shipping_taxes,
                'shipping_tax_incl' => $shipping_tax_incl,
                'wrapping_tax_excl' => $this->order_invoice->total_wrapping_tax_excl,
                'wrapping_taxes' => $wrapping_taxes,
                'wrapping_tax_incl' => $this->order_invoice->total_wrapping_tax_incl,
                'ecotax_taxes' => $total_taxes - $product_taxes - $wrapping_taxes - $shipping_taxes,
                'total_taxes' => $total_taxes,
                'total_paid_tax_excl' => $this->order_invoice->total_paid_tax_excl,
                'total_paid_tax_incl' => $this->order_invoice->total_paid_tax_incl,
            ];
            foreach ($footer as $key => $value) {
                $footer[$key] = Tools::ps_round($value, Context::getContext()->getComputingPrecision(), $this->order->round_mode);
            }
            /**
             * Need the $round_mode for the tests.
             */
            $round_type = null;
            switch ($this->order->round_type) {
                case Order::ROUND_TOTAL:
                    $round_type = 'total';
                    break;
                case Order::ROUND_LINE:
                    $round_type = 'line';
                    break;
                case Order::ROUND_ITEM:
                    $round_type = 'item';
                    break;
                default:
                    $round_type = 'line';
                    break;
            }
            $display_product_images = Configuration::get('PS_PDF_IMG_INVOICE');
            $tax_excluded_display = Group::getPriceDisplayMethod($customer->id_default_group);
            $layout = $this->computeLayout(['has_discount' => $has_discount]);
            $legal_free_text = Hook::exec('displayInvoiceLegalFreeText', ['order' => $this->order]);
            if (!$legal_free_text) {
                $legal_free_text = Configuration::get('PS_INVOICE_LEGAL_FREE_TEXT', (int) Context::getContext()->language->id, null, (int) $this->order->id_shop);
            }
            $id_order = $this->order->id;
            $order = new Order($id_order);
            $commission = $this->getOrderCommission($id_order);
            $commission_total = isset($commission['commission']) ? $commission['commission'] : 0;
            $discount = $this->getOrderDiscount($id_order);
            $discount_total = isset($discount['discount']) ? $discount['discount'] : 0;
            $data = [
                'order' => $this->order,
                'order_invoice' => $this->order_invoice,
                'order_details' => $order_details,
                'carrier' => $carrier,
                'cart_rules' => $cart_rules,
                'delivery_address' => $formatted_delivery_address,
                'invoice_address' => $formatted_invoice_address,
                'addresses' => ['invoice' => $invoice_address, 'delivery' => $delivery_address],
                'tax_excluded_display' => $tax_excluded_display,
                'display_product_images' => $display_product_images,
                'layout' => $layout,
                'tax_tab' => $this->getTaxTabContent(),
                'customer' => $customer,
                'footer' => $footer,
                'ps_price_compute_precision' => Context::getContext()->getComputingPrecision(),
                'round_type' => $round_type,
                'legal_free_text' => $legal_free_text,
                'commission' => (float) $commission_total,
                'format_commission' => $commission_total,
                'discount' => (float) $discount_total,
                'format_discount' => $discount_total,
            ];
            if (Tools::getValue('debug')) {
                exit(json_encode($data));
            }
            $this->smarty->assign($data);
            $tpls = [
                'style_tab' => $this->smarty->fetch($this->getTemplate('invoice.style-tab')),
                'addresses_tab' => $this->smarty->fetch($this->getTemplate('invoice.addresses-tab')),
                'summary_tab' => $this->smarty->fetch($this->getTemplate('invoice.summary-tab')),
                'product_tab' => $this->smarty->fetch($this->getTemplate('invoice.product-tab')),
                'tax_tab' => $this->getTaxTabContent(),
                'payment_tab' => $this->smarty->fetch($this->getTemplate('invoice.payment-tab')),
                'note_tab' => $this->smarty->fetch($this->getTemplate('invoice.note-tab')),
                'total_tab' => $this->smarty->fetch(_PS_MODULE_DIR_ . 'custompaymentmethod/views/templates/pdf/invoice.total-tab.tpl'),
                'shipping_tab' => $this->smarty->fetch($this->getTemplate('invoice.shipping-tab')),
            ];
            $this->smarty->assign($tpls);
            return $this->smarty->fetch($this->getTemplateByCountry($country->iso_code));
        } else {
            $invoiceAddressPatternRules = json_decode(Configuration::get('PS_INVCE_INVOICE_ADDR_RULES'), true);
            $deliveryAddressPatternRules = json_decode(Configuration::get('PS_INVCE_DELIVERY_ADDR_RULES'), true);

            $invoice_address = new Address((int) $this->order->id_address_invoice);
            $country = new Country((int) $invoice_address->id_country);
            $formatted_invoice_address = AddressFormat::generateAddress($invoice_address, $invoiceAddressPatternRules, '<br />', ' ');

            $delivery_address = null;
            $formatted_delivery_address = '';
            if (isset($this->order->id_address_delivery) && $this->order->id_address_delivery) {
                $delivery_address = new Address((int) $this->order->id_address_delivery);
                $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, $deliveryAddressPatternRules, '<br />', ' ');
            }

            $customer = new Customer((int) $this->order->id_customer);
            $carrier = new Carrier((int) $this->order->id_carrier);

            $order_details = $this->order_invoice->getProducts();

            $has_discount = false;
            foreach ($order_details as $id => &$order_detail) {
                // Find out if column 'price before discount' is required
                if ($order_detail['reduction_amount_tax_excl'] > 0) {
                    $has_discount = true;
                    $order_detail['unit_price_tax_excl_before_specific_price'] = $order_detail['unit_price_tax_excl_including_ecotax'] + $order_detail['reduction_amount_tax_excl'];
                } elseif ($order_detail['reduction_percent'] > 0) {
                    $has_discount = true;
                    $order_detail['unit_price_tax_excl_before_specific_price'] = (100 * $order_detail['unit_price_tax_excl_including_ecotax']) / (100 - $order_detail['reduction_percent']);
                }

                // Set tax_code
                $taxes = OrderDetail::getTaxListStatic($id);
                $tax_temp = [];
                foreach ($taxes as $tax) {
                    $obj = new Tax($tax['id_tax']);
                    $tax_temp[] = sprintf($this->l('%1$s%2$s%%'), $obj->rate + 0, '&nbsp;');
                }

                $order_detail['order_detail_tax'] = $taxes;
                $order_detail['order_detail_tax_label'] = implode(', ', $tax_temp);
            }
            unset($tax_temp);
            unset($order_detail);

            if (Configuration::get('PS_PDF_IMG_INVOICE')) {
                foreach ($order_details as &$order_detail) {
                    if ($order_detail['image'] != null) {
                        $name = 'product_mini_' . (int) $order_detail['product_id'] . (isset($order_detail['product_attribute_id']) ? '_' . (int) $order_detail['product_attribute_id'] : '') . '.jpg';
                        $path = _PS_PROD_IMG_DIR_ . $order_detail['image']->getExistingImgPath() . '.jpg';
                        $order_detail['image_tag'] = preg_replace(
                            '/\.*' . preg_quote(__PS_BASE_URI__, '/') . '/',
                            _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR,
                            ImageManager::thumbnail($path, $name, 45, 'jpg', false),
                            1
                        );

                        if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                            $order_detail['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                        } else {
                            $order_detail['image_size'] = false;
                        }
                    }
                }
                unset($order_detail);
            }
            $cart_rules = $this->order->getCartRules($this->order_invoice->id);
            $free_shipping = false;
            foreach ($cart_rules as $key => $cart_rule) {
                if ($cart_rule['free_shipping']) {
                    $free_shipping = true;
                    $cart_rules[$key]['value_tax_excl'] -= $this->order_invoice->total_shipping_tax_excl;
                    $cart_rules[$key]['value'] -= $this->order_invoice->total_shipping_tax_incl;
                    if ($cart_rules[$key]['value'] == 0) {
                        unset($cart_rules[$key]);
                    }
                }
            }
            $product_taxes = 0;
            foreach ($this->order_invoice->getProductTaxesBreakdown($this->order) as $details) {
                $product_taxes += $details['total_amount'];
            }
            $product_discounts_tax_excl = $this->order_invoice->total_discount_tax_excl;
            $product_discounts_tax_incl = $this->order_invoice->total_discount_tax_incl;
            if ($free_shipping) {
                $product_discounts_tax_excl -= $this->order_invoice->total_shipping_tax_excl;
                $product_discounts_tax_incl -= $this->order_invoice->total_shipping_tax_incl;
            }
            $products_after_discounts_tax_excl = $this->order_invoice->total_products - $product_discounts_tax_excl;
            $products_after_discounts_tax_incl = $this->order_invoice->total_products_wt - $product_discounts_tax_incl;
            $shipping_tax_excl = $free_shipping ? 0 : $this->order_invoice->total_shipping_tax_excl;
            $shipping_tax_incl = $free_shipping ? 0 : $this->order_invoice->total_shipping_tax_incl;
            $shipping_taxes = $shipping_tax_incl - $shipping_tax_excl;
            $wrapping_taxes = $this->order_invoice->total_wrapping_tax_incl - $this->order_invoice->total_wrapping_tax_excl;
            $total_taxes = $this->order_invoice->total_paid_tax_incl - $this->order_invoice->total_paid_tax_excl;
            $footer = [
                'products_before_discounts_tax_excl' => $this->order_invoice->total_products,
                'product_discounts_tax_excl' => $product_discounts_tax_excl,
                'products_after_discounts_tax_excl' => $products_after_discounts_tax_excl,
                'products_before_discounts_tax_incl' => $this->order_invoice->total_products_wt,
                'product_discounts_tax_incl' => $product_discounts_tax_incl,
                'products_after_discounts_tax_incl' => $products_after_discounts_tax_incl,
                'product_taxes' => $product_taxes,
                'shipping_tax_excl' => $shipping_tax_excl,
                'shipping_taxes' => $shipping_taxes,
                'shipping_tax_incl' => $shipping_tax_incl,
                'wrapping_tax_excl' => $this->order_invoice->total_wrapping_tax_excl,
                'wrapping_taxes' => $wrapping_taxes,
                'wrapping_tax_incl' => $this->order_invoice->total_wrapping_tax_incl,
                'ecotax_taxes' => $total_taxes - $product_taxes - $wrapping_taxes - $shipping_taxes,
                'total_taxes' => $total_taxes,
                'total_paid_tax_excl' => $this->order_invoice->total_paid_tax_excl,
                'total_paid_tax_incl' => $this->order_invoice->total_paid_tax_incl,
            ];

            foreach ($footer as $key => $value) {
                $footer[$key] = Tools::ps_round($value, _PS_PRICE_COMPUTE_PRECISION_, $this->order->round_mode);
            }

            /**
             * Need the $round_mode for the tests.
             */
            $round_type = null;
            switch ($this->order->round_type) {
                case Order::ROUND_TOTAL:
                    $round_type = 'total';
                    break;
                case Order::ROUND_LINE:
                    $round_type = 'line';
                    break;
                case Order::ROUND_ITEM:
                    $round_type = 'item';
                    break;
                default:
                    $round_type = 'line';
                    break;
            }

            $display_product_images = Configuration::get('PS_PDF_IMG_INVOICE');
            $tax_excluded_display = Group::getPriceDisplayMethod($customer->id_default_group);

            $layout = $this->computeLayout(['has_discount' => $has_discount]);

            $legal_free_text = Hook::exec('displayInvoiceLegalFreeText', ['order' => $this->order]);
            if (!$legal_free_text) {
                $legal_free_text = Configuration::get('PS_INVOICE_LEGAL_FREE_TEXT', (int) Context::getContext()->language->id, null, (int) $this->order->id_shop);
            }

            $id_order = $this->order->id;
            $order = new Order($id_order);
            $commission = $this->getOrderCommission($id_order);
            $commission_total = isset($commission['commission']) ? $commission['commission'] : 0;
            $discount = $this->getOrderDiscount($id_order);
            $discount_total = isset($discount['discount']) ? $discount['discount'] : 0;
            $data = [
                'order' => $this->order,
                'order_invoice' => $this->order_invoice,
                'order_details' => $order_details,
                'carrier' => $carrier,
                'cart_rules' => $cart_rules,
                'delivery_address' => $formatted_delivery_address,
                'invoice_address' => $formatted_invoice_address,
                'addresses' => ['invoice' => $invoice_address, 'delivery' => $delivery_address],
                'tax_excluded_display' => $tax_excluded_display,
                'display_product_images' => $display_product_images,
                'layout' => $layout,
                'tax_tab' => $this->getTaxTabContent(),
                'customer' => $customer,
                'footer' => $footer,
                'ps_price_compute_precision' => _PS_PRICE_COMPUTE_PRECISION_,
                'round_type' => $round_type,
                'legal_free_text' => $legal_free_text,
                'commission' => (float) $commission_total,
                'format_commission' => $commission_total,
                'discount' => (float) $discount_total,
                'format_discount' => $discount_total,
            ];

            if (Tools::getValue('debug')) {
                exit(json_encode($data));
            }

            $this->smarty->assign($data);

            $tpls = [
                'style_tab' => $this->smarty->fetch($this->getTemplate('invoice.style-tab')),
                'addresses_tab' => $this->smarty->fetch($this->getTemplate('invoice.addresses-tab')),
                'summary_tab' => $this->smarty->fetch($this->getTemplate('invoice.summary-tab')),
                'product_tab' => $this->smarty->fetch($this->getTemplate('invoice.product-tab')),
                'tax_tab' => $this->getTaxTabContent(),
                'payment_tab' => $this->smarty->fetch($this->getTemplate('invoice.payment-tab')),
                'note_tab' => $this->smarty->fetch($this->getTemplate('invoice.note-tab')),
                'total_tab' => $this->smarty->fetch(_PS_MODULE_DIR_ . 'custompaymentmethod/views/templates/pdf/invoice.total-tab-16.tpl'),
                'shipping_tab' => $this->smarty->fetch($this->getTemplate('invoice.shipping-tab')),
            ];
            $this->smarty->assign($tpls);

            return $this->smarty->fetch($this->getTemplateByCountry($country->iso_code));
        }
    }

    public static function getOrderCommission($id_order)
    {
        $res = Db::getInstance()->getRow(
            'SELECT commission, commission_tax_excl, id_currency 
             FROM ' . _DB_PREFIX_ . 'order_commission WHERE id_order = ' . (int) $id_order
        );
        return $res ? $res : [];
    }

    public static function getOrderDiscount($id_order)
    {
        $res = Db::getInstance()->getRow(
            'SELECT discount, discount_tax_excl, id_currency 
             FROM ' . _DB_PREFIX_ . 'order_commission WHERE id_order = ' . (int) $id_order
        );
        return $res ? $res : [];
    }
}
