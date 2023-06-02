<?php
/**
* 2005-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* 1This source file is subject to the Academic Free License (AFL 3.0)
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

include dirname(__FILE__) . '/config.php';

class Cdek20 extends CarrierModule
{
    public $id_carrier;
    public $cart_cdek;
    public $cdek_carriers = [];

    public function __construct()
    {
        $this->name = 'cdek20';
        $this->tab = 'shipping_logistics';
        $this->version = '2.1.0';
        $this->author = 'SeoSA';
        $this->need_instance = 0;
        $this->module_key = 'f3e8bd8c4ba01dd0003a618b8dc92d9b';
        $this->bootstrap = true;

        parent::__construct();

        ToolsModuleSK::registerSmartyFunctions();
        $this->displayName = $this->l('Cdek module');
        $this->description = $this->l('Delivery option form the Cdek');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall my module?');
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        ToolsModuleSK::registerSmartyFunctions();
        return $this->getDocumentation();
    }

    public function assignDocumentation()
    {
        $this->context->controller->addCSS($this->getLocalPath() . 'views/css/documentation.css');
        $documentation_folder = $this->getLocalPath() . 'views/templates/admin/documentation';
        $documentation_pages = self::globRecursive($documentation_folder . '/**.tpl');
        natsort($documentation_pages);

        $tree = [];
        if (is_array($documentation_pages) && count($documentation_pages)) {
            foreach ($documentation_pages as &$documentation_page) {
                $name = str_replace([$documentation_folder . '/', '.tpl'], '', $documentation_page);
                $path = explode('/', $name);

                $tmp_tree = &$tree;
                foreach ($path as $key => $item) {
                    $part = $item;
                    if ($key == (count($path) - 1)) {
                        $tmp_tree[$part] = $name;
                    } else {
                        if (!isset($tmp_tree[$part])) {
                            $tmp_tree[$part] = [];
                        }
                    }
                    $tmp_tree = &$tmp_tree[$part];
                }
            }
        }

        $this->context->smarty->assign('tree', $this->buildTree($tree));
        $this->context->smarty->assign('documentation_pages', $documentation_pages);
        $this->context->smarty->assign('documentation_folder', $documentation_folder);
    }

    public function getDocumentation()
    {
        $this->assignDocumentation();
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/documentation.tpl');
    }

    public static function globRecursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        if (!$files) {
            $files = [];
        }

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $files = array_merge($files, self::globRecursive($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }

    public function buildTree($tree)
    {
        $tree_html = '';
        if (is_array($tree) && count($tree)) {
            foreach ($tree as $name => $tree_item) {
                preg_match('/^(\d+)\._(.*)$/', $name, $matches);
                $format_name = $matches[1] . '. ' . TransModSK::getInstance()->ld($matches[2]);

                $tree_html .= '<li>';
                $tree_html .= '<a ' . (!is_array($tree_item) ? 'data-tab="' . $tree_item . '" href="#"' : '') . '>' . $format_name . '</a>';
                if (is_array($tree_item) && count($tree_item)) {
                    $tree_html .= '<ul>';
                    $tree_html .= $this->buildTree($tree_item);
                    $tree_html .= '</ul>';
                }
                $tree_html .= '</li>';
            }
        }
        return $tree_html;
    }

    public function getDocumentationLinks()
    {
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/front.css');
        $this->context->smarty->assign('link_on_tab_module', $this->getAdminLink());
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/documentation_links.tpl');
    }

    public function getAdminLink()
    {
        return $this->context->link->getAdminLink('AdminModules', true)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function getOrderShippingCost($cart, $shipping_cost)
    {
        return false;
    }

    public function getOrderShippingCostExternal($cart)
    {
        if ($this->checkNeedCalculate($cart) && (bool) $cart->id_address_delivery) {

            if (is_null($this->cart_cdek)) {
                if ($id_order = Db::getInstance()->getValue('SELECT `id_order` FROM `'._DB_PREFIX_.'orders` WHERE `id_cart` = '.(int)$cart->id)) {
                    $this->cart_cdek = new Seleda\Cdek\Cart\CartFromOrder((new Order($id_order)));
                } else {
                    $this->cart_cdek = new Seleda\Cdek\Cart\CartFromCart($cart);
                }
            }

            if (count($this->cart_cdek->getProducts()) == 0) {
                return false;
            }
            
            static $price = [];
            
            if (!array_key_exists($this->id_carrier, $price)) {
                $carrier_cdek = $this->getCdekCarrier($this->cart_cdek, $this->id_carrier);
                $price[$this->id_carrier] = $carrier_cdek->calculate()->getPrice();
            }
            return $price[$this->id_carrier];
        }
        return false;
    }

    public function checkNeedCalculate($cart = null)
    {
        if (is_null($cart)) {
            $cart = $this->context->cart;
        }
        return (bool) $cart && $cart->id_address_delivery;
        if ($this->context->controller->php_self == 'pagenotfound') {
            return false;
        }
        if (($cart instanceof Cart) && !$cart->id_address_delivery) {
            return false;
        }
        if (($cart instanceof Cart) && $cart->orderExists()) {
            return true;
        }
        return ($this->context->customer && $this->context->customer->logged == true) ||
            ($this->context->controller->controller_type == 'admin' && $this->context->controller->controller_name == 'AdminOrders') ||
            ($this->context->controller->controller_type == 'admin' && $this->context->controller->controller_name == 'AdminCarts');
    }

    public function getCdekCarrier($cdek_cart, $id_carrier)
    {
        if (array_key_exists($id_carrier, $this->cdek_carriers)) {
            return $this->cdek_carriers[$id_carrier];
        }

        $type = TariffCdek::getTypeByIdCarrier($id_carrier);

        if (!$type) {
            $this->cdek_carriers[$id_carrier] = false;
            return false;
        }

        static $city_to = false;

        if (!Validate::isLoadedObject($this->context->cart)) {
            $id_address_delivery = Db::getInstance()->getValue('SELECT `id_address_delivery` FROM `'._DB_PREFIX_.'cart` WHERE `id_cart` = '.(int)$cdek_cart->getIdCalculatorCache());
        } else {
            $id_address_delivery = $this->context->cart->id_address_delivery;
        }

        if (!$city_to) {
            $address = new Address($id_address_delivery);
            $city_to = new CityCdek($address);
        }

        static $cdek_customer = false;
        if (!$cdek_customer) {
            $cdek_customer = new CustomerCdek($id_address_delivery);
        }

        if (!$cdek_customer->{'city_' . $type}) {
            $cdek_customer->{'city_' . $type} = $city_to->getCode();

            if (!Validate::isLoadedObject($cdek_customer)) {
                $cdek_customer->id = $id_address_delivery;
            }
            $cdek_customer->save();
        }

        if ($type != 'courier' && !$cdek_customer->{$type} && $cdek_customer->{'city_' . $type}) {
            $cdek_customer->{$type} = PvzCdek::getPvzDefault($type, LangCdek::getInstance((int) $this->context->language->id)->getLang(), $cdek_customer->{'city_' . $type}, $cdek_cart->getTotalWeight());
            $cdek_customer->save();
        }

        $class = ucfirst($type) . 'Cdek';
        $this->cdek_carriers[$id_carrier] = new $class($cdek_cart, $cdek_customer);
        return $this->cdek_carriers[$id_carrier];
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (version_compare(_PS_VERSION_, '1.7.7', '>=')) {
            $request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
            if (preg_match('#\/sell\/orders\/(\d+)\/view#', $request->getPathInfo(), $matches) && isset($matches[1])) {
                ${'_GET'}['id_order'] = $matches[1];
            }
        }
        return $this->hookHeader();
    }

    public function hookDisplayAdminOrderTabShip($params)
    {
        $order = $params['order'];
        if (!ToolsCdek::isCdek($order)) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.7.7.0', '<')) {
            return '<li>
            <a href="#cdek_info_block">
              <i class="icon-truck "></i>
              Cdek <span class="badge">1</span>
            </a>
          </li>';
        }

        return '<li class="nav-item"><a class="nav-link active show" id="historyTab" data-toggle="tab" href="#cdekTabContent" role="tab" aria-controls="cdekTabContent" aria-expanded="true" aria-selected="false">
        <i class="material-icons">local_shipping</i> Cdek</a></li>';
    }

    public function hookDisplayAdminOrderTabLink($params)
    {
        $order = new Order($params['id_order']);
        return $this->hookDisplayAdminOrderTabShip(['order' => $order]);
    }

    public function hookDisplayAdminOrderContentShip($params)
    {
        $order = $params['order'];
        if (!ToolsCdek::isCdek($order)) {
            return false;
        }

//        $this->hookActionValidateOrder(array('order' => $order, 'cart' => new Cart($order->id_cart)));
//        $type_contract = ConfigCdek::getConf('type_contract');
//
//        $type = $type_contract == 2 ? 'Contract' : 'Online';
//
//        $class = $type.'OrderCdek';
        $cdek_order = new OrderCdek($order);
        $door = 0;
        $tarrif_door = [138, 139, 293, 1, 3, 18, 57, 58, 59, 60, 61, 8, 7, 118, 121, 124, 480, 295, 247, 12, 17, 120, 123, 126, 481, 361, 366, 485];
        $order_tarrif = $cdek_order->getEntity()->getTariffCode();

        if (in_array($order_tarrif, $tarrif_door)) {
        $door = 1;
        }
        $error_message = [];
        $call = db::getInstance()->getValue('
        SELECT `call_courier` FROM ' . _DB_PREFIX_ . 'cdek_order
        WHERE id_order = ' . $order->id
        );
        if ($call) {
            $data_call = json_decode($call, true);
            // ---------------------------------------------------
            $this->logger = new LoggerCdek();
            $uid = $data_call['entity']['uuid'];

            if (!empty($uid) && !empty(ClientCdek::getToken())) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.cdek.ru/v2/intakes/' . $uid);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . ClientCdek::getToken(), 'Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $this->logger->addMessage('Info call', 'Code ' . $code, $uid, $result);

            $data_info = json_decode($result, true);
            $call = $data_info['requests'][0]['state'];
            $error_message = $data_info['requests'][0]['errors'];
            }
            $call = 'ACCEPTED';
        } else {
            $call = 'no';
        }
        $note = $cdek_order->getEntity()->getComment();
        $this->context->smarty->assign([
            'cdek_order' => $cdek_order,
            'door' => $door,
            'call' => $call,
            'error_message' => $error_message,
            'note' => $note,
        ]);

        return $this->display(__FILE__, 'admin/info_block.tpl');
    }

    public function hookDisplayAdminOrderTabContent($params)
    {
        $order = new Order($params['id_order']);
        return $this->hookDisplayAdminOrderContentShip(['order' => $order]);
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (($this->context->controller->controller_name == 'AdminOrders') || Tools::getIsset('addorder')) {
            $order = new Order((int) Tools::getValue('id_order'));
            if (!Validate::isLoadedObject($order)) {
                return;
            }
            $id_carrier = Tools::getValue('shipping_carrier');
            if (!$id_carrier && Validate::isLoadedObject($order)) {
                $id_carrier = $order->id_carrier;
            }

            $def = [
                'cdek_params' => [
                    'id_cdek_carrier' => $id_carrier,
                    'request_url' => $this->context->link->getModuleLink($this->name, 'order'),
                    'package_url' => Context::getContext()->link->getAdminLink('AdminCdekPackage', true, [], ['id_order' => $order->id])
                ]
            ];

//            $sql = 'SELECT * FROM `'._DB_PREFIX_.'cdek_carrier_type` t1 LEFT JOIN (SELECT MAX(`carrier_reference`) AS carrier_reference, `type` FROM `'._DB_PREFIX_.'cdek_carrier_type` GROUP BY `type`) t2 ON (t1.`carrier_reference` = t2.`carrier_reference`) WHERE t2.`type` IN ("pickup", "postamat")';
//            $res = Db::getInstance()->executeS($sql);
//            foreach ($res as $reference) {
//                $carrier = Carrier::getCarrierByReference($reference['carrier_reference']);
//                $def['cdek']['iframe'][$carrier->id] = $this->hookHeader();
//            }

            Media::addJsDef($def);
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addJS($this->_path . 'views/js/cdek.js');
            $this->context->controller->addJS($this->_path . 'views/js/order.js');
        } elseif (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    public function hookActionDispatcherBefore()
    {
        if (!Validate::isLoadedObject($this->context->cart) && $this->context->cookie->id_cart) {
            $this->context->cart = new Cart((int) $this->context->cookie->id_cart);
        }
        if (($city_code = Tools::getValue('cdek_city_code')) && Validate::isLoadedObject($this->context->cart)) {
            $cdek_customer = new CustomerCdek($this->context->cart->id_address_delivery);
            $cdek_customer->city_courier = (int) $city_code;
            $cdek_customer->city_pickup = (int) $city_code;
            $cdek_customer->city_postamat = (int) $city_code;
            $cdek_customer->save();
        }
        if (Tools::getValue('sort_cdek_carriers') == 'delay') {
            $this->context->cookie->sort_cdek_carriers = 'delay';
        } elseif (Tools::getValue('sort_cdek_carriers') == 'price') {
            $this->context->cookie->sort_cdek_carriers = 'price';
        }
    }

    public function hookActionDispatcher()
    {
        $this->hookActionDispatcherBefore();
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $page = $_GET['controller'];
        Media::addJsDef([
            'cdek_settings' => $this->getCdekSettings(),
            'cdek_city_search_url' => $this->context->link->getModuleLink($this->name, 'search', ['type' => 'city'])
        ]);
        if ($page == 'order' || $page == 'cart' || $page == 'orderopc') {
            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $this->context->controller->addJS($this->_path . '/views/js/front16.js');
            } else {
                $this->context->controller->addJS($this->_path . '/views/js/front.js');
            }
            $this->context->controller->addCSS($this->_path . '/views/css/front.css');
            $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/cdek.js');
        }

        return $this->getWidgetIframes();
    }

    public function hookUpdateCarrier($params)
    {

    }

    public function hookActionObjectAddAfter($param)
    {
        $this->hookActionObjectUpdateAfter($param);
    }

    public function hookActionObjectUpdateAfter($param)
    {
        $obj = $param['object'];
        if (!($obj instanceof Cart) && !($obj instanceof Order)) {
            return false;
        }

        // change language front
        if (!$this->context->currency) {
            $this->context->currency = new Currency($obj->id_currency);
        }

        if ((int) $obj->id_address_delivery != 0) {
            if ($obj instanceof Cart && (!Validate::isLoadedObject($this->context->cart)
                    || (int) $this->context->cart->id_address_delivery == 0)) {
                $this->context->cart = $obj;
            }
            $address = new Address($obj->id_address_delivery);
            $city_to = new CityCdek($address);
            $cdek_customer = new CustomerCdek($obj->id_address_delivery);
            if ($city_to->getCode() && $city_to->getCode() != $cdek_customer->city_courier) {
                $cdek_customer->city_courier = $city_to->getCode();
                $cdek_customer->city_pickup = $city_to->getCode();
                $cdek_customer->city_postamat = $city_to->getCode();
                $cdek_customer->save();
            }
        }

        if ($obj instanceof Order) {
            $carrier = new Carrier($obj->id_carrier);

            if (!Validate::isLoadedObject($carrier) || $carrier->external_module_name != $this->name) {
                return false;
            }
            $cdek_cart = new \Seleda\Cdek\Cart\CartFromOrder($obj);
            $carrier_cdek = $this->getCdekCarrier($cdek_cart, $carrier->id);
            $price = $carrier_cdek->calculate()->getPrice();
//            if ($obj->total_shipping != $price) {
//                $obj->total_shipping_tax_excl = Tools::ps_round((float)$price, 2);
//                $obj->total_shipping_tax_incl = $obj->total_shipping_tax_excl;
//                $obj->total_shipping = $obj->total_shipping_tax_incl;
//
//                if (null !== $carrier && Validate::isLoadedObject($carrier)) {
//                    $obj->carrier_tax_rate = $carrier->getTaxesRate(
//                        new Address((int)$cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')})
//                    );
//                }
//                $obj->save();
//            }
            Db::getInstance()->update('order_carrier', [
                'weight' => $carrier_cdek->cart->getTotalWeight() / ConfigurationCdek::get('weight_unit'),
                'shipping_cost_tax_excl' => $obj->total_shipping_tax_excl,
                'shipping_cost_tax_incl' => $obj->total_shipping_tax_incl,
            ], 'id_order = ' . (int) $obj->id);
        }
    }

    public function hookActionValidateOrder($params)
    {
        $order = $params['order'];

        if (Tools::getIsset('shipping_carrier')) {
            $carrier = new Carrier((int) Tools::getValue('shipping_carrier'));
        } else {
            $carrier = new Carrier($order->id_carrier);
        }

        if ($carrier->external_module_name != $this->name) {
            return false;
        }

        $cdek_cart = new \Seleda\Cdek\Cart\CartFromOrder($order);
        $carrier_cdek = $this->getCdekCarrier($cdek_cart, $carrier->id);
        $carrier_cdek->calculate();

        // update weight
        Db::getInstance()->update('order_carrier', ['weight' => $carrier_cdek->cart->getTotalWeight()/ConfigurationCdek::get('weight_unit')], 'id_order = ' . (int) $order->id);

        $cdek_order = new OrderCdek($order);
        $cdek_order->id_order = $order->id;

        $tariff = $carrier_cdek->calculation['tariff_code'];

        $shipment_point = '';
        if (in_array(TariffCdek::getModeByTariff($tariff), [TariffCdek::WAREHOUSE_WAREHOUSE, TariffCdek::WAREHOUSE_DOOR, TariffCdek::WAREHOUSE_POSTAMAT])) {
            $shipment_point = ConfigurationCdek::get('pvz_warehouse');
            preg_match('/^[^\d]+\d+/', $shipment_point, $match);
            if (count($match)) {
                $shipment_point = $match[0];
            }
        }

        $address_recipient = new Address($order->id_address_delivery);
        $country_iso = Db::getInstance()->getValue('SELECT `iso_code` FROM `' . _DB_PREFIX_ . 'country` WHERE `id_country` = ' . (int) $address_recipient->id_country);

        $recipient = new ContactCdek();
        $recipient->setName($address_recipient->firstname . ' ' . $address_recipient->lastname);
        $recipient->setCompany($address_recipient->company);

        $phones = [];
        $phone = new stdClass;
        if ($address_recipient->phone) {
            $phone->number = ToolsCdek::normalizationPhone($address_recipient->phone, $country_iso);
            $phones[] = new PhoneCdek($phone);
        }
        if ($address_recipient->phone_mobile) {
            $phone->number = ToolsCdek::normalizationPhone($address_recipient->phone_mobile, $country_iso);
            $phones[] = new PhoneCdek($phone);
        }
        if (count($phones) == 0) {
            $phone->number = '+70000000000';
            $phones[] = new PhoneCdek($phone);
        }
        $recipient->setPhones($phones);

        $type = ConfigurationCdek::get('type_contract');

        $sender = new SenderCdek();
        $phone = new PhoneCdek();
        $phone->setNumber(ToolsCdek::normalizationPhone(ConfigurationCdek::get('sender_phone'), $country_iso));
        $sender->setCompany(ConfigurationCdek::get('sender_company'))
            ->setName(ConfigurationCdek::get('sender_name')) // manager
            ->setPhones([$phone]);

        $seller = new SellerCdek();
        $seller->setName(ConfigurationCdek::get('seller_name'));
        $seller->setAddress(ConfigurationCdek::get('shipper_address'));

        $cdek_order->getEntity()
            ->setType($type ? $type : 1)
            ->setTariffCode($tariff)
            ->setDeveloperKey('')
            ->setDeliveryPoint($carrier_cdek->customer->{$carrier_cdek->type})
            ->setShipmentPoint($shipment_point)
            ->setDateInvoice($order->invoice_date)
            ->setSender($sender)
            ->setShipperAddress(ConfigurationCdek::get('shipper_address'))
            ->setRecipient($recipient)
            ->setSeller($seller);
        $cdek_order->getEntity()->shipper_name = ConfigurationCdek::get('sender_company');

        if ($carrier_cdek->part_deliv) {
            $part_deliv = new ServiceCdek();
            $part_deliv->setCode('PART_DELIV');
            $cdek_order->getEntity()->addService($part_deliv);
        }

        $address = new Address();
        $address->postcode = ConfigurationCdek::get('postal_code');
        $address->id_country = Country::getByIso(ConfigurationCdek::get('country_warehouse'));
        $city = new CityCdek($address);

        if (!$shipment_point) {
            $from_location = new LocationCdek();
            $from_location
                ->setCode($city->getCode())
                ->setPostalCode(ConfigurationCdek::get('postal_code'))
                ->setCity(ConfigurationCdek::get('city_warehouse'))
                ->setAddress(ConfigurationCdek::get('address_warehouse'));
            $cdek_order->getEntity()->setFromLocation($from_location);
        }

        $to_location = new LocationCdek();
        if ($carrier_cdek->type == 'courier') {
            $to_location
                ->setCode($carrier_cdek->customer->city_courier)
                ->setPostalCode($address_recipient->postcode)
                ->setCity($address_recipient->city)
                ->setAddress($address_recipient->address1);
            $cdek_order->getEntity()
                ->setToLocation($to_location);
        } else {
            $to_location->setCode($carrier_cdek->customer->{'city_'.$carrier_cdek->type})
                ->setAddress(
                    PvzCdek::getPvzAddress(
                        $carrier_cdek->customer->{$carrier_cdek->type},
                        LangCdek::getInstance($this->context->language->id)->getLang()
                    )
                );
            $cdek_order->getEntity()->setToLocation($to_location);
        }

        $delivery_recipient_cost = new MoneyCdek();
        $delivery_recipient_cost->setValue($order->total_shipping);
        $cdek_order->getEntity()->setDeliveryRecipientCost($delivery_recipient_cost);

        Context::getContext()->order = $params['order'];

        // for postamat one package
        if ($carrier_cdek->type == 'postamat') {
            if (ConfigurationCdek::get('all_is_one_package')) {
                $carrier_cdek->cart->setPackageBuilder(new \Seleda\Cdek\Component\Cart\Package\AllIsOnePackageBuilder($carrier_cdek->cart));
            } elseif (ConfigurationCdek::get('one_package')) {
                $carrier_cdek->cart->setPackageBuilder(new \Seleda\Cdek\Component\Cart\Package\OnePackageBuilder($carrier_cdek->cart));
            } else {
                $carrier_cdek->cart->setPackageBuilder(new \Seleda\Cdek\Component\Cart\Package\AllOneBoxBuilder($carrier_cdek->cart));
            }
        }

        $packages = $carrier_cdek->cart->createPackages()->getPackagesForOrder();

        foreach ($packages as &$package) {
            $package = new PackageCdek($package);
        }

        $cdek_order->getEntity()->setPackages($packages);
        $cdek_order->generateOrderNumber(ConfigurationCdek::get('account'));
        $cdek_order->save();
    }


    /**
     *  OrderState newOrderStatus
     * int id_order
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        $new_order_status = $params['newOrderStatus'];
        $statuses = ConfigurationCdek::get('statuses');
        // create, delete, cod_ship, cod
        if (in_array($new_order_status->id, $statuses['create'])) {
            $client = Seleda\Cdek\Component\Order\Client::getInstance();
            $entity = Db::getInstance()->getValue('SELECT `entity` FROM `'._DB_PREFIX_.'cdek_order` WHERE `id_order` = '.(int) $params['id_order']);
            $entity = json_decode($entity, true);

            // Если заказ не доставляется до ПВЗ, то не надо передавать пустые delivery_point и/или shipment_point
            // т.к. по ним в первую очередь определяется город
            if (isset($entity['delivery_point']) && !$entity['delivery_point']) {
                unset($entity['delivery_point']);
            }
            if (isset($entity['shipment_point']) && !$entity['shipment_point']) {
                unset($entity['shipment_point']);
            }
            $order = new Order((int)$params['id_order']);
            // Если заказ оплачен, убираем сумму наложенного платежа
            // new REQ-0011 2021.04.01
            if (!in_array($new_order_status->id, $statuses['cod'])) {
                foreach ($entity['packages'] as &$package) {
                    foreach ($package['items'] as &$item) {
                        $item['payment']['value'] = 0;
                    }
                }
            } else {
                //TODO
            }
            //
            // new REQ-0011 2021.04.01 наложенный платеж
            if (!in_array($new_order_status->id, $statuses['cod_ship'])) {
                $entity['delivery_recipient_cost']['value'] = 0;
            } else {
                $entity['delivery_recipient_cost']['value'] = $order->total_shipping;
            }
            //
            $entity = json_encode($entity, JSON_UNESCAPED_UNICODE);
            if ($client->createOrder($entity)) {
                $result = $client->getResult();

                $cdek_order = new OrderCdek($order);
                $cdek_order->getEntity()->setUuid($result['entity']['uuid']);
                $cdek_order->setRequests($result['requests']);
                $cdek_order->save();
            }
        }
        if (in_array($new_order_status->id, $statuses['delete'])) {
            $order = new Order((int) $params['id_order']);
            $cdek_order = new OrderCdek($order);
            $client = Seleda\Cdek\Component\Order\Client::getInstance();
            if ($client->deleteOrder($cdek_order->getEntity()->getUuid())) {
                $result = $client->getResult();
                $cdek_order->getEntity()->setCdekNumber(null);
                $cdek_order->setRequests($result['requests']);
                $cdek_order->save();
            }
        }
    }

    public function hookDisplayBeforeCarrier()
    {
        $cdek_customer = new CustomerCdek($this->context->cart->id_address_delivery);

        $city = CityCdek::getCityByCodeStatic($cdek_customer->city_courier);

        $this->context->smarty->assign([
            'sort_cdek_carriers' => $this->context->cookie->sort_cdek_carriers,
            'cdek_city_name' => $city,
            'carrier_count' => count($this->cdek_carriers),
            'prestashop_version' => _PS_VERSION_,
        ]);

        return $this->display(__FILE__, 'front/search.tpl');
    }

    public function setDeliveryDelay($carrier)
    {
        if ($this->context->controller->controller_type == 'admin') {
            return false;
        }

        $cache_key = 'setDeliveryDelay_' . $carrier->id;

        if (Cache::isStored($cache_key)) {
            $delay = Cache::retrieve($cache_key);
            if ($delay && is_array($carrier->delay)) {
                $carrier->delay[Context::getContext()->language->id] = $delay;
            } elseif ($delay) {
                $carrier->delay = $delay;
            }
            return true;
        }

        if ($id_order = Tools::getValue('id_order')) {
            $cdek_cart = new \Seleda\Cdek\Cart\CartFromOrder(new Order((int)$id_order));
        } else {
            $cdek_cart = new \Seleda\Cdek\Cart\CartFromCart($this->context->cart);
        }

        if (!$cdek_cart->getIdAddressdelivery()) {
            return;
        }

        $cdek_carrier = $this->getCdekCarrier($cdek_cart, $carrier->id);

        $delay = $cdek_carrier->calculate()->getDelay();
        Cache::store($cache_key, $delay);
        if ($delay && is_array($carrier->delay)) {
            $carrier->delay[Context::getContext()->language->id] = $delay;
        } elseif ($delay) {
            $carrier->delay = $delay;
        }
    }

    public function setDeliveryNameSuffix($carrier)
    {
        if (!$this->checkNeedCalculate($this->context->cart)) {
            return false;
        }
        $cache_key = 'setDeliveryNameSuffix_' . $carrier->id;

        if (Cache::isStored($cache_key)) {
            $carrier->name .= '. ' . Cache::retrieve($cache_key);
            return true;
        }

        if ($id_order = Tools::getValue('id_order')) {
            $cdek_cart = new \Seleda\Cdek\Cart\CartFromOrder(new Order((int)$id_order));
        } else {
            $cdek_cart = new \Seleda\Cdek\Cart\CartFromCart($this->context->cart);
        }

        if (!$cdek_cart->getIdAddressDelivery()) {
            return;
        }

        $cdek_carrier = $this->getCdekCarrier($cdek_cart, $carrier->id);

        if (!$cdek_carrier) {
            return;
        }

        $city = Db::getInstance()->getValue('SELECT `city` FROM `' . _DB_PREFIX_ . 'cdek_city_lang` 
            WHERE `code` = ' . (int) $cdek_carrier->customer->{'city_' . $cdek_carrier->type} . ' AND `lang` = "' . pSQL($cdek_carrier->cart->getLang()) . '"');

        $carrier->name .= '. ' . $city;
        Cache::store($cache_key, $city);
    }

    public function getCdekSettings()
    {
        return ['trans_url'=>$this->context->link->getModuleLink($this->name, 'trans')];
    }

    public function getWidgetIframes()
    {
        $types = ['pickup', 'postamat'];
        $city = '--CITY--';
        $postal_code = '--POSTCODE--';

        if (Tools::getIsset('id_order')) {
            $order = new Order((int) Tools::getValue('id_order'));
            $cdek_customer = new CustomerCdek($order->id_address_delivery);
        } else {
            $cdek_customer = new CustomerCdek($this->context->cart ? $this->context->cart->id_address_delivery : null);
        }

        $cdek_widgets = [];
        $cdek_widgets[] = [
            'delivery_option' => 0,
            'iframe_url' => $this->context->link->getModuleLink($this->name, 'widget', ['type' => '--TYPE--', 'city'=> $city, 'postal_code' => $postal_code]),
            'height' => '400px'
        ];
        foreach ($types as $type) {
            $cdek_widgets[] = [
                'delivery_option' => AbstractCarrierCdek::getCarrierIdByType($type),
                'iframe_url' => $this->context->link->getModuleLink($this->name, 'widget', ['type' => $type, 'customer' => $cdek_customer->id, 'city'=> $cdek_customer->{'city_'.$type}, 'postal_code' => $postal_code]),
                'height' => '400px',
                'width' => '100%',
            ];
        }

        $this->context->smarty->assign([
            'cdek_widgets' => $cdek_widgets,
            'id_order' => (int) Tools::getValue('id_order'),
        ]);

        $page = $_GET['controller'];

        if ($page == 'order' || $page == 'cart' || $page == 'orderopc') {
            return $this->context->smarty->fetch($this->getLocalPath() . 'views/templates/front/widget/container_front.tpl');
        } elseif ($this->context->controller->controller_type == 'admin' || $page == 'AdminCdekSetting') {
            return $this->context->smarty->fetch($this->getLocalPath() . 'views/templates/front/widget/container.tpl');
        }
    }

    public function getWidgetIframe($cdek_widget)
    {
        $this->context->smarty->assign(['cdek_widget' => $cdek_widget]);
        return $this->context->smarty->fetch($this->getLocalPath() . 'views/templates/front/widget/iframe.tpl');
    }

    public function install()
    {
        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        try {
            Db::getInstance()->delete('authorization_role', '`slug` LIKE "ROLE_MOD_TAB_ADMINCDEKSETTING%"');
        } catch (Exception $e) {
            // pass
        }

        $class_name = 'AdminCdekSetting';

        if (!Db::getInstance()->getValue('SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `class_name` = "' . $class_name . '"') && !$this->createTab(
            $this->name,
            $class_name,
            [
                'ru' => 'CDEK способ доставки',
                'en' => 'CDEK delivery option',
            ],
            'AdminParentShipping'
        )) {
            return false;
        }

        $class_name = 'AdminCdekPackage';

        if (!Db::getInstance()->getValue('SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `class_name` = "' . $class_name . '"')
            && !$this->createTab($this->name, $class_name, false)
           ) {
            return false;
        }
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $access = new Access();
            $access->updateLgcAccess((int)_PS_ADMIN_PROFILE_, Db::getInstance()->getValue('SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `class_name` ="' . pSQL($class_name) . '"'), 'view', 1);
        }
        require_once $this->getLocalPath().'sql/install.php';

        foreach (TariffCdek::getTariffsStatic() as $data) {
            if (!$this->createTariff($data)) {
                return false;
            }
        }

        foreach (array('courier', 'pickup', 'postamat') as $type) {
            $carrier = $this->addCarrier($type);
            $this->addZones($carrier);
            $this->addGroups($carrier);
            $this->addRanges($carrier);
        }

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            (version_compare(_PS_VERSION_, '1.7', '<') ? $this->registerHook('actionDispatcher') : $this->registerHook('actionDispatcherBefore')) &&
            $this->registerHook('displayBeforeCarrier') &&
            $this->registerHook('actionAdminControllerSetMedia') &&
            $this->registerHook('displayAdminOrderTabShip') &&
            $this->registerHook('displayAdminOrderTabLink') &&
            $this->registerHook('displayAdminOrderContentShip') &&
            $this->registerHook('displayAdminOrderTabContent') &&
            $this->registerHook('actionObjectUpdateAfter') &&
            $this->registerHook('actionObjectAddAfter') &&
            $this->registerHook('actionOrderStatusPostUpdate') &&
            $this->registerHook('actionValidateOrder');
    }

    public function addNewTariffs()
    {
        Db::getInstance()->execute('TRUNCATE TABLE `ps_cdek_tariff`');
        foreach (TariffCdek::getTariffsStatic() as $data) {
            if (!$this->createTariff($data)) {
                return false;
            }
        }
    }

    public function createTariff($data)
    {
        $pickup = [TariffCdek::DOOR_WAREHOUSE, TariffCdek::WAREHOUSE_WAREHOUSE];
        $courier = [TariffCdek::DOOR_DOOR, TariffCdek::WAREHOUSE_DOOR];
        $postamat = [TariffCdek::WAREHOUSE_POSTAMAT, TariffCdek::DOOR_POSTAMAT];
        if (in_array($data['mode'], $pickup)) {
            $type = $pickup;
        } elseif (in_array($data['mode'], $courier)) {
            $type = $courier;
        } elseif (in_array($data['mode'], $postamat)) {
            $type = $postamat;
        }
        $type = implode(',', $type);
        $position = Db::getInstance()->getValue('SELECT MAX(position) as max FROM `' . _DB_PREFIX_ . 'cdek_tariff` WHERE `mode` IN(' . pSQL($type) . ')') + 1;
        $tariff = new TariffCdek();
        $tariff->tariff = $data['id'];
        $tariff->mode = $data['mode'];
        $tariff->range_min = $data['range']['min'];
        $tariff->range_max = $data['range']['max'];
        $tariff->name_rus = $data['name']['ru'];
        $tariff->name_eng = $data['name']['en'];
        $tariff->active = 1;
        $tariff->position = $position;
        return $tariff->add();
    }

    public function createTab($module_name, $class_name, $name, $parent = null)
    {
        if (!is_array($name)) {
            $name = ['en' => $name];
        } elseif (is_array($name) && !count($name)) {
            $name = ['en' => $class_name];
        } elseif (is_array($name) && count($name) && !isset($name['en'])) {
            $name['en'] = current($name);
        }

        $tab = new Tab();
        $tab->class_name = $class_name;
        $tab->module = $module_name;
        $tab->id_parent = (!is_null($parent) ? Tab::getIdFromClassName($parent) : 0);
        $tab->active = true;
        foreach (Language::getLanguages(true) as $l) {
            $tab->name[$l['id_lang']] = (isset($name[$l['iso_code']]) ? $name[$l['iso_code']] : $name['en']);
        }
        return $tab->save();
    }

    protected function addCarrier($type)
    {
        $carrier = new Carrier();

        $lang_cdek = LangCdek::getInstance($this->context->language);
        if ($lang_cdek->getLang() == 'rus') {
            if ($type == 'courier') {
                $name = 'CДЭК(курьер)';
            } elseif ($type == 'postamat') {
                $name = 'СДЭК(постамат)';
            } else {
                $name = 'СДЭК(самовывоз)';
            }
        } else {
            $name = 'CDEK(' . $type . ')';
        }

        $carrier->name = $name;
        $carrier->active = 1;
        $carrier->shipping_external = 1;
        $carrier->external_module_name = $this->name;

        $delay = [
            'rus' => 'Супер быстрая доставка',
            'eng' => 'Super fast delivery',
            'zho' => '超快速交货',
        ];
        foreach (Language::getLanguages() as $lang) {
            $key_lang = LangCdek::getInstance((int) $lang['id_lang'])->getLang();
            $carrier->delay[$lang['id_lang']] = $delay[$key_lang];
        }

        if ($carrier->add() == true) {
            Db::getInstance()->insert('cdek_carrier_type', ['carrier_reference' => $carrier->id, 'type' => $type]);
            @copy(dirname(__FILE__) . '/views/img/carrier_image.jpg', _PS_SHIP_IMG_DIR_ . '/' . (int) $carrier->id . '.jpg');
            return $carrier;
        }

        return false;
    }

    protected function addGroups($carrier)
    {
        $groups_ids = [];
        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group) {
            $groups_ids[] = $group['id_group'];
        }

        $carrier->setGroups($groups_ids);
    }

    protected function addRanges($carrier)
    {
        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '0';
        $range_weight->add();
    }

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone) {
            $carrier->addZone($zone['id_zone']);
        }
    }

    public function uninstall()
    {
        $carrier_references = Db::getInstance()->executeS('SELECT `carrier_reference` FROM `' . _DB_PREFIX_ . 'cdek_carrier_type`');
        foreach ($carrier_references as $reference) {
            $carrier = Carrier::getCarrierByReference($reference['carrier_reference']);

            if (Validate::isLoadedObject($carrier)) {
                if (Configuration::get('PS_CARRIER_DEFAULT') == $carrier->id) {
                    $sql = 'SELECT `id_carrier` FROM `' . _DB_PREFIX_ . 'carrier`
                        WHERE `deleted` = 0 AND `external_module_name` <> "' . pSQL($this->name) . '"
                        ORDER BY `position` ASC';
                    $new_default = Db::getInstance()->getValue($sql);
                    Configuration::updateValue('PS_CARRIER_DEFAULT', $new_default);
                }
                $carrier->deleted = true;
                if (!$carrier->save()) {
                    return false;
                }
            }
        }
        require_once $this->getLocalPath() . 'sql/uninstall.php';

        return $this->deleteTab('AdminCdekSetting') && parent::uninstall();
    }

    public function deleteTab($class_name)
    {
        $tab = Tab::getInstanceFromClassName($class_name);
        if (Validate::isLoadedObject($tab)) {
            return $tab->delete();
        }
        return true;
    }
}
