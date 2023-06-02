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
class ConfigurationCdek
{
    public static $instance;
    // general
    public $type_contract = 1;
    public $contract_currency = 1;
    public $sender_company = '';
    public $seller_name = '';
    public $sender_name; // manager
    public $sender_phone;
    public $shipper_address = '';
    public $vat = 0;
    public $account;
    public $secure_password;
    public $part_deliv;
    public $one_package;
    public $all_is_one_package;
    public $all_one_box;
    public $map_api_key;
    // location
    public $country_warehouse = 'RU';
    public $postal_code = '101000';
    public $city_warehouse = 'Москва';
    public $address_warehouse = 'Адрес склада';
    public $pvz_warehouse = 'MSK368|Каширское шоссе, 70, кор.3';
    // carriers
    public $free_shipping_courier = false;
    public $free_shipping_pickup = false;
    public $free_shipping_postamat = false;
    public $free_price_courier;
    public $free_price_pickup;
    public $free_price_postamat;
    public $free_weight_courier;
    public $free_weight_pickup;
    public $free_weight_postamat;
    // metrics
    public $weight_unit = 1;
    public $volume_unit = 1;
    public $default_weight = 1;
    public $default_length = 1;
    public $default_width = 1;
    public $default_height = 1;
    private $default_categories = array();
    private $statuses = array('create' => array(), 'delete' => array(), 'cod_ship' => array(), 'cod' => array());
    // calculator
    public $delay = 0;
    public $departure_time = '12:00';
    public $courier_start_time = '09:00';
    public $end_time_for_courier = '22:00';
    public $waiting_date_courier = 0;
    public $total_correction = 0;
    public $type_correction = 1;
    public $product_price_reduction = 0; // for insurance
    public $impact_percent_of_cart = 0; // impact of the basket value
    public $write_log = 0;
    public $weight_allowance;

    public $country_for_upload = array(); // fix for field city upload

    private function __construct()
    {
        $res = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'cdek_configuration`');
        if ($res) {
            $this->contract_currency = $res['contract_currency'];
            $this->sender_company = $res['sender_company'];
            $this->seller_name = $res['seller_name'];
            $this->sender_name = $res['sender_name']; // manager
            $this->sender_phone = $res['sender_phone'];
            $this->shipper_address = $res['shipper_address'];
            $this->vat = $res['vat'];
            $this->part_deliv = $res['part_deliv'];
            $this->one_package = $res['one_package'];
            $this->weight_allowance = $res['weight_allowance'];
            $this->all_is_one_package = $res['all_is_one_package'];
            $this->all_one_box = $res['all_one_box'];
            $this->account = $res['account'];
            $this->secure_password = $res['secure_password'];
            $this->map_api_key = $res['map_api_key'];

            $this->country_warehouse = $res['country_warehouse'];
            $this->postal_code = $res['postal_code'];
            $this->city_warehouse = $res['city_warehouse'];
            $this->address_warehouse = $res['address_warehouse'];
            $this->pvz_warehouse = $res['pvz_warehouse'];

            $this->free_shipping_courier = $res['free_shipping_courier'];
            $this->free_shipping_pickup = $res['free_shipping_pickup'];
            $this->free_shipping_postamat = $res['free_shipping_postamat'];
            $this->free_price_courier = $res['free_price_courier'];
            $this->free_price_pickup = $res['free_price_pickup'];
            $this->free_price_postamat = $res['free_price_postamat'];
            $this->free_weight_courier = $res['free_weight_courier'];
            $this->free_weight_pickup = $res['free_weight_pickup'];
            $this->free_weight_postamat = $res['free_weight_postamat'];

            $this->weight_unit = $res['weight_unit'];
            $this->volume_unit = $res['volume_unit'];
            $this->default_weight = $res['default_weight'];
            $this->default_length = $res['default_length'];
            $this->default_width = $res['default_width'];
            $this->default_height = $res['default_height'];

            $this->delay = $res['delay'];
            $this->departure_time = $res['departure_time'];
            $this->courier_start_time = $res['courier_start_time'];
            $this->end_time_for_courier = $res['end_time_for_courier'];
            $this->waiting_date_courier = $res['waiting_date_courier'];
            $this->total_correction = $res['total_correction'];
            $this->type_correction = $res['type_correction'];
            $this->product_price_reduction = $res['product_price_reduction'];
            $this->impact_percent_of_cart = $res['impact_percent_of_cart'];
            $this->write_log = (int)$res['write_log'];
        }
    }

    public function save()
    {
        $res = true;
        Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_configuration`');
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ($key == 'default_categories') {
                if (count($value)) {
                    Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_category`');
                    foreach ($value as $id_category => $category) {
                        $category['id_category'] = $id_category;
                        Db::getInstance()->insert('cdek_category', $category);
                    }
                }
                unset($vars[$key]);
                continue;
            } elseif ($key == 'statuses') {
                if (count($value)) {
                    $res = array();
                    foreach (OrderState::getOrderStates(Context::getContext()->language->id) as $k => $state) {
                        $res[$k] = array();
                        $res[$k]['id_status'] = $state['id_order_state'];
                        $res[$k]['create'] = 0;
                        $res[$k]['delete'] = 0;
                        $res[$k]['cod_ship'] = 0;
                        $res[$k]['cod'] = 0;
                        if (in_array($state['id_order_state'], $value['create'])) {
                            $res[$k]['create'] = 1;
                        }
                        if (in_array($state['id_order_state'], $value['delete'])) {
                            $res[$k]['delete'] = 1;
                        }
                        if (in_array($state['id_order_state'], $value['cod_ship'])) {
                            $res[$k]['cod_ship'] = 1;
                        }
                        if (in_array($state['id_order_state'], $value['cod'])) {
                            $res[$k]['cod'] = 1;
                        }
                    }
                    Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_status`');
                    foreach ($res as $status) {
                        Db::getInstance()->insert('cdek_status', $status);
                    }
                }
                unset($vars[$key]);
                continue;
            } elseif ($key == 'country_for_upload') { // fix load cities
                unset($vars[$key]);
                continue;
            }
        }
        return $res && Db::getInstance()->insert('cdek_configuration', $vars);
    }

    public static function get($param = false)
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        if ($param == 'statuses') {
            if (self::$instance->statuses['create'] || self::$instance->statuses['delete'] || self::$instance->statuses['cod_ship'] || self::$instance->statuses['cod']) {
                return self::$instance->statuses;
            }
            $statuses = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'cdek_status`');
            $res = array('create' => array(), 'delete' => array(), 'cod_ship' => array(), 'cod' => array());
            foreach ($statuses as $status) {
                if ($status['create']) {
                    $res['create'][] = $status['id_status'];
                }
                if ($status['delete']) {
                    $res['delete'][] = $status['id_status'];
                }
                if ($status['cod_ship']) {
                    $res['cod_ship'][] = $status['id_status'];
                }
                if ($status['cod']) {
                    $res['cod'][] = $status['id_status'];
                }
            }
            self::$instance->setStatuses($res);
        } elseif ($param == 'default_categories') {
            if (self::$instance->default_categories) {
                return self::$instance->default_categories;
            }
            $categories = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'cdek_category`');
            $res = array();
            foreach ($categories as $category) {
                $res[$category['id_category']] = array(
                    'weight' => $category['weight'],
                    'length' => $category['length'],
                    'width' => $category['width'],
                    'height' => $category['height']
                );
            }
            self::$instance->setDefaultCategories($res);
        }

        if ($param) {
            return self::$instance->{$param};
        }

        return self::$instance;
    }

    public function getStatuses()
    {
        return $this->statuses;
    }

    public function setStatuses($val)
    {
        $this->statuses = $val;
        return $this;
    }

    public function getDefaultCategories()
    {
        return $this->default_categories;
    }

    public function setDefaultCategories($val)
    {
        $this->default_categories = $val;
        return $this;
    }
}
