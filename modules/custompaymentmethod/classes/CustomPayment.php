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

class CustomPayment extends ObjectModel
{
    const TYPE_COMMISSION_NONE = 0;
    const TYPE_COMMISSION_PERCENT = 1;
    const TYPE_COMMISSION_AMOUNT = 2;
    
    const TYPE_COMMISSION_AMOUNT_PLUS = 0;
    const TYPE_COMMISSION_AMOUNT_MINUS = 1;
    
    const APPLY_COMMISSION_PRODUCTS = 0;
    const APPLY_COMMISSION_TOTAL = 1;
    
    public $name;
    public $logo;
    public $details;
    public $description;
    public $description_short;
    public $confirmation_page = 1;
    public $confirmation_page_add = 1;
    public $show_method_available = 1;
    public $visible_method_available = 1;
    public $add_history = 1;
    public $active = 1;
    public $id_order_state;
    
    public $commission_percent;
    public $currency_commission;
    public $commission_amount;
    public $type_commission;
    public $apply_commission;
    
    public $discount_percent;
    public $currency_discount;
    public $discount_amount;
    public $type_discount;
    public $apply_discount;
    
    public $available_groups;
    public $commission_use_tax_on_products = 1;
    public $discount_use_tax_on_products = 1;
    public $available_carriers;
    public $available_currencies;
    public $available_countries;
    public $view_message_field = 0;
    public $required_message_field = 0;
    public $name_message_field;
    public $error_message_field;
    public $is_send_mail = 1;
    public $cart_total_from = 0;
    public $cart_total_to = 0;
    public $select_currency;
    public $commission_tax = 0;
    public $commission_switch = 0;
    public $discount_tax = 0;
    public $position = 0;
    
    public $id_cms;
    
    public static $definition
        = array(
            'table'     => 'custom_payment_method',
            'multilang' => true,
            'multishop' => true,
            'primary'   => 'id_custom_payment_method',
            'fields'    => array(
                'name'              => array(
                    'type'     => self::TYPE_STRING,
                    'lang'     => true,
                    'validate' => 'isGenericName',
                    'size'     => 256,
                    'required' => true,
                ),
                'logo'              => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 256),
                'details'           => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
                'description'       => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
                'description_short' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
                'confirmation_page' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'confirmation_page_add' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'show_method_available' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'visible_method_available' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'add_history' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'active'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'shop' => true),
                
                'commission_percent'   => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'currency_commission'  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'type_commission'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'commission_amount'    => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'apply_commission'     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                
                'discount_percent'   => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'currency_discount'  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'type_discount'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'discount_amount'    => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'apply_discount'     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                
                'id_order_state'                 => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
                'available_groups'               => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'available_carriers'             => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'available_currencies'           => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'available_countries'           => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'commission_use_tax_on_products' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'discount_use_tax_on_products'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'view_message_field'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'required_message_field'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'name_message_field'             => array('type'     => self::TYPE_STRING,
                                                          'lang'     => true,
                                                          'validate' => 'isString',
                ),
                'error_message_field'            => array('type'     => self::TYPE_STRING,
                                                          'lang'     => true,
                                                          'validate' => 'isString',
                ),
                'is_send_mail'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
                'cart_total_from'                => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'cart_total_to'                  => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'select_currency'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'commission_tax'                 => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'commission_switch'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'discount_tax'                   => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
                'position'                       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
                'id_cms'                         => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            ),
        );
    
    
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        if (isset(static::$definition['multishop']) && !Shop::isTableAssociated(static::$definition['table'])) {
            Shop::addTableAssociation(
                static::$definition['table'],
                array(
                    'type' => 'shop',
                )
            );
        }
        parent::__construct($id, $id_lang, $id_shop);
        
        if (!$this->id) {
            $_ = [];
            foreach (Group::getGroups($id_lang) as $group) {
                $_[] = $group['id_group'];
            }
            $this->available_groups = implode(',', $_);
            $_ = [];
            foreach (Carrier::getCarriers($id_lang) as $carrier) {
                $_[] = $carrier['id_reference'];
            }
            $this->available_carriers = implode(',', $_);
            $_ = [];
            foreach (Currency::getCurrencies() as $currency) {
                $_[] = $currency['id_currency'];
            }
            $this->available_currencies = implode(',', $_);
            $_ = [];
            foreach (Country::getCountries(Context::getContext()->language->id, true) as $country) {
                $_[] = $country['id_country'];
            }
            $this->available_countries = implode(',', $_);
        }
    }
    
    /**
     * @param null $active
     *
     * @return array
     * @deprecated
     */
    public static function getCustomPaymentMethods($active = null)
    {
        $context = Context::getContext();
        $id_shop = Shop::getContextShopID();
        if (!$id_shop) {
            $id_shop = (int)Configuration::get('PS_SHOP_DEFAULT');
        }
        
        $result = Db::getInstance()->executeS(
            'SELECT cpm.*, cmpl.*, osl.`name` as order_state, cmps.`active` FROM '._DB_PREFIX_
            .'custom_payment_method cpm LEFT JOIN '
            ._DB_PREFIX_.'custom_payment_method_lang cmpl
			 ON cmpl.`id_custom_payment_method` = cpm.`id_custom_payment_method`
			 AND cmpl.`id_lang` = '.(int)$context->language->id.'
			 LEFT JOIN '._DB_PREFIX_.'custom_payment_method_shop cmps
			 ON cmps.`id_custom_payment_method` = cpm.`id_custom_payment_method`
			 AND cmps.`id_shop` = '.(int)$id_shop.'
			 LEFT JOIN '._DB_PREFIX_.'order_state_lang osl ON osl.`id_order_state` = cpm.`id_order_state` 
			 AND osl.`id_lang` = '.(int)$context->language->id
            .(! is_null($active) ? ' WHERE cpm.`active` = 1 ' : '').' ORDER BY cpm.`position` ASC'
        );
        if (!is_array($result) || !count($result)) {
            return [];
        }
        
        foreach ($result as $key => &$item) {
            $item['available_groups'] = ($item['available_groups'] ? explode(
                ',',
                $item['available_groups']
            ) : []);
            
            $item['available_carriers'] = ($item['available_carriers'] ? explode(
                ',',
                $item['available_carriers']
            ) : []);

            foreach ($item['available_carriers'] as &$id_reference) {
                $id_carrier = Db::getInstance()->getValue('SELECT `id_carrier` FROM `' . _DB_PREFIX_ . 'carrier`
			        WHERE id_reference = ' . (int) $id_reference . ' AND deleted = 0 ORDER BY id_carrier DESC');
                if (!$id_carrier) {
                    continue;
                }
                $id_reference = $id_carrier;
            }

            $item['available_countries'] = ($item['available_countries'] ? explode(
                ',',
                $item['available_countries']
            ) : []);

            $item['available_currencies'] = ($item['available_currencies'] ? explode(
                ',',
                $item['available_currencies']
            ) : []);
            
            if (!defined('_PS_ADMIN_DIR_') && Context::getContext()->cookie->logged) {
                $choice = false;
                $customer_groups = Context::getContext()->customer->getGroups();
                foreach ($customer_groups as $customer_group) {
                    if (in_array($customer_group, $item['available_groups'])) {
                        $choice = true;
                    }
                }
                if (!$choice) {
                    unset($result[$key]);
                }
            }
        }
        
        return $result;
    }
    
    /**
     * @param int  $id_lang
     * @param null $active
     *
     * @return CustomPayment[]
     * @throws \PrestaShopException
     */
    public static function getCustomPaymentMethodsCollection($id_lang, $active = null, Customer $customer = null)
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('CustomPayment', $id_lang);
        } else {
            $collection = new Collection('CustomPayment', $id_lang);
        }
        
        if (null !== $active) {
            $collection->where('active', '=', (bool)$active);
        }
        $collection->orderBy('position', 'asc');
        $payments = $collection->getResults();
        
        $customer_obj = Context::getContext()->customer;
        if (!is_null($customer)) {
            $customer_obj = $customer;
        }
        
        /**
         * @var $payment CustomPayment
         */
        foreach ($payments as $key => $payment) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'custom_payment_method_shop` 
            WHERE `id_custom_payment_method` = '.(int)$payment->id.' 
            AND `id_shop` = '.(int)Context::getContext()->shop->id.'
            AND `active` = 1';

            if (!Db::getInstance()->getRow($sql)) {
                unset($payments[$key]);
                continue;
            }

            $payment->available_groups = ($payment->available_groups ? explode(
                ',',
                $payment->available_groups
            ) : []);

            if (Context::getContext()->cart) {
                $payment->available_carriers = ($payment->available_carriers ? explode(
                    ',',
                    $payment->available_carriers
                ) : []);
                $payment->available_currencies = ($payment->available_currencies ? explode(
                    ',',
                    $payment->available_currencies
                ) : []);
                $payment->available_countries = ($payment->available_countries ? explode(
                    ',',
                    $payment->available_countries
                ) : []);

                $carrier = new Carrier(Context::getContext()->cart->id_carrier);
                if (!is_null($carrier->id) &&!in_array($carrier->id, $payment->available_carriers)) {
                    unset($payments[$key]);
                    continue;
                }

                if (!in_array(Context::getContext()->cart->id_currency, $payment->available_currencies)) {
                    unset($payments[$key]);
                    continue;
                }

                if (!in_array(Context::getContext()->country->id, $payment->available_countries)) {
                    unset($payments[$key]);
                    continue;
                }

                $cart_total = (float)Context::getContext()->cart->getOrderTotal();

                $currency_to = new Currency(Context::getContext()->cart->id_currency);
                $currency_from = new Currency($payment->select_currency);
                if ($currency_to != $currency_from) {
                    $from = Tools::convertPriceFull($payment->cart_total_from, $currency_from, $currency_to);
                    $to = Tools::convertPriceFull($payment->cart_total_to, $currency_from, $currency_to);
                    $payment->cart_total_from = $from;
                    $payment->cart_total_to = $to;
                }

                if ((int)$payment->cart_total_from && $cart_total < $payment->cart_total_from) {
                    unset($payments[$key]);
                    continue;
                }

                if ((int)$payment->cart_total_to && $cart_total > $payment->cart_total_to) {
                    unset($payments[$key]);
                    continue;
                }
            }
            
            if (!defined('_PS_ADMIN_DIR_') && Context::getContext()->cookie->logged || !is_null($customer)) {
                $choice = false;
                $customer_groups = $customer_obj->getGroups();
                foreach ($customer_groups as $customer_group) {
                    if (in_array($customer_group, $payment->available_groups)) {
                        $choice = true;
                    }
                }
                if (!$choice) {
                    unset($payments[$key]);
                }
            }
        }

        return $payments;
    }
    
    public static function addOrderData($id_order, $id_currency, $calculations)
    {
        if (($calculations['commission'] + $calculations['discount']) == 0) {
            return;
        }

        $order = new Order((int)$id_order);

        $commission = $calculations['commission'];
        $discount = $calculations['discount'];
        $commission_wt = $calculations['commission_tax_excl'];
        $discount_wt = $calculations['discount_tax_excl'];

        $total_paid = $order->total_paid + $commission + $discount;
        $order->total_paid = ($total_paid < 0 ? 0 : $total_paid);
        $total_paid_tax_excl = $order->total_paid_tax_excl + $commission_wt + $discount_wt;
        $order->total_paid_tax_excl = ($total_paid_tax_excl < 0 ? 0 : $total_paid_tax_excl);
        $total_paid_tax_incl = $order->total_paid_tax_incl + $commission + $discount;
        $order->total_paid_tax_incl = ($total_paid_tax_incl < 0 ? 0 : $total_paid_tax_incl);
        $order->save();

        Db::getInstance()->insert(
            'order_commission',
            array(
                'id_order'    => (int)$id_order,
                'id_currency' => (int)$id_currency,
                'commission'  => pSQL($calculations['commission']),
                'commission_tax_excl'  => pSQL($calculations['commission_tax_excl']),
                'discount'    => pSQL($calculations['discount']),
                'discount_tax_excl'    => pSQL($calculations['discount_tax_excl']),
            )
        );
    }

    public static function addOrderPaymentData($id_order, $calculations)
    {
        if (($calculations['commission'] + $calculations['discount']) == 0) {
            return;
        }

        $commission = $calculations['commission'];
        $discount = $calculations['discount'];

        foreach (self::getByOrderId($id_order) as $order_payment) {
            $order_payment->amount += Tools::ps_round($commission + $discount);
            $order_payment->update();
            break;
        }
    }

    public static function getOrderCommission($id_order)
    {
        $res = Db::getInstance()->getRow(
            'SELECT commission, commission_tax_excl, id_currency 
             FROM '._DB_PREFIX_.'order_commission WHERE id_order = '.(int)$id_order
        );
        return $res ? $res : [];
    }
    
    public static function getOrderDiscount($id_order)
    {
        $res = Db::getInstance()->getRow(
            'SELECT discount, discount_tax_excl, id_currency 
             FROM '._DB_PREFIX_.'order_commission WHERE id_order = '.(int)$id_order
        );
        return $res ? $res : [];
    }
    
    public function setNextPosition()
    {
        if (!$this->id) {
            $last_position = (int)Db::getInstance()->getValue(
                'SELECT MAX(`position`) FROM '._DB_PREFIX_.'custom_payment_method'
            );
            $this->position = $last_position + 1;
        }
    }

    public function getIdByCartId($id_cart)
    {
        $sql = 'SELECT `id_order`
            FROM `' . _DB_PREFIX_ . 'orders`
            WHERE `id_cart` = ' . (int) $id_cart .
            Shop::addSqlRestriction();

        $result = Db::getInstance()->getValue($sql);

        return !empty($result) ? (int) $result : false;
    }

    public static function getByOrderId($id_order)
    {
        Tools::displayAsDeprecated();
        $order = new Order($id_order);

        return OrderPayment::getByOrderReference($order->reference);
    }
}
