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

use Seleda\Cdek\Cart\CartFromCart;

class Cdek20WidgetModuleFrontController extends ModuleFrontController
{
    private $lang;
    private $type;
    private $pvz;
    private $customer;

    public function init()
    {
        parent::init();

        $this->lang = LangCdek::getInstance($this->context->language->id)->getLang();
        $this->type = pSQL(Tools::getValue('type'));
        if (!$this->type) {
            $id_carrier = trim(Tools::getValue('delivery_option'), ',');
            $this->type = TariffCdek::getTypeByIdCarrier($id_carrier);
        }
        if (!in_array($this->type, ['pickup', 'postamat'])) {
            exit('Bad params');
        }

        $cdek_cart = new CartFromCart($this->context->cart);

        $weight_sum = $cdek_cart->getTotalWeight() / 1000;
        $this->pvz = PvzCdek::getPVZForWidget($this->type, $this->lang, $weight_sum);

        if (!isset($this->pvz['PVZ'])) {
            exit($this->module->l('The list of pvzs is not loaded'));
        }

        if (!Tools::getIsset('id_order')) {
            $this->customer = new CustomerCdek($this->context->cart ? $this->context->cart->id_address_delivery : null);
        }

        if (Tools::getIsset('postal_code')) { // настройки
            $postcode = Tools::getValue('postal_code');
            if ($postcode != '--POSTCODE--') {
                $address = new Address();
                $address->postcode = $postcode;
                $address->id_country = Country::getByIso(ConfigurationCdek::get('country_warehouse'));
                $city = new CityCdek($address);
                $this->customer->{'city_' . $this->type} = $city->getCode();
                $this->customer->{$this->type} = current($this->pvz['PVZ'][$this->customer->{'city_' . $this->type}])['code'];
                return;
            }
        }
        if (!Validate::isLoadedObject($this->customer) && Tools::getIsset('customer')) {
            $this->customer = new CustomerCdek(Tools::getValue('customer'));
        }
        if (!Validate::isLoadedObject($this->customer) && Tools::getIsset('id_order')) {
            $order = new Order(Tools::getValue('id_order'));
            $this->customer = new CustomerCdek($order->id_address_delivery);
        }
        if (!$this->customer->{$this->type} && $this->pvz) {
            $this->customer->{$this->type} = current($this->pvz['PVZ'][$this->customer->{'city_' . $this->type}])['code'];
            $this->customer->save();
        }
    }

    // for prestashop 8 @deprecated 1.7.7 jQuery is always included, this method should no longer be used
    public static function getJqueryPath($version = null, $folder = null, $minifier = true)
    {
        @trigger_error(
            'Media::getJqueryPath() is deprecated since version 1.7.7.0, jquery is always included',
            E_USER_DEPRECATED
        );
        $addNoConflict = false;
        if ($version === null) {
            $version = _PS_JQUERY_VERSION_;
        } //set default version
        elseif (preg_match('/^([0-9\.]+)$/Ui', $version)) {
            $addNoConflict = true;
        } else {
            return false;
        }

        if ($folder === null) {
            $folder = _PS_JS_DIR_ . 'jquery/';
        } //set default folder
        //check if file exist
        $file = $folder . 'jquery-' . $version . ($minifier ? '.min.js' : '.js');

        // remove PS_BASE_URI on _PS_ROOT_DIR_ for the following
        $urlData = parse_url($file);
        $fileUri = _PS_ROOT_DIR_ . Tools::str_replace_once(__PS_BASE_URI__, DIRECTORY_SEPARATOR, $urlData['path']);
        $fileUriHostMode = _PS_CORE_DIR_ . Tools::str_replace_once(__PS_BASE_URI__, DIRECTORY_SEPARATOR, $urlData['path']);
        // check if js files exists, if not try to load query from ajax.googleapis.com

        $return = [];

        if (@filemtime($fileUri) || (defined('_PS_HOST_MODE_') && @filemtime($fileUriHostMode))) {
            $return[] = Media::getJSPath($file);
        } else {
            $return[] = Media::getJSPath(Tools::getCurrentUrlProtocolPrefix() . 'ajax.googleapis.com/ajax/libs/jquery/' . $version . '/jquery' . ($minifier ? '.min.js' : '.js'));
        }

        if ($addNoConflict) {
            $return[] = Media::getJSPath(Context::getContext()->shop->getBaseURL(true, false) . _PS_JS_DIR_ . 'jquery/jquery.noConflict.php?version=' . $version);
        }

        // added jQuery migrate for compatibility with new version of jQuery
        // will be removed when using latest version of jQuery
        $return[] = Media::getJSPath(_PS_JS_DIR_ . 'jquery/jquery-migrate-1.2.1.min.js');

        return $return;
    }

    public function postProcess()
    {
        if (Tools::getValue('ajax')) {
            $action = Tools::toCamelCase(Tools::getValue('action'));
            $this->{$action}();

            exit;
        }
        $javascripts = array_merge(self::getJqueryPath(), [
            $this->module->getPathUri() . 'views/js/widjet/widjet.js',
            $this->module->getPathUri() . 'views/js/widjet_param.js',
        ]);

        $default_city = $this->customer->{'city_' . $this->type};

        Media::addJsDef([
            'widget_templates' => $this->getTemplates(),
            'widget_lang' => $this->getWidgetLang(),
            'PVZ' => $this->pvz,
            'selected_pvz' => $this->customer->{$this->type},
            'cdek' => [
                'id_cdek_carrier' => AbstractCarrierCdek::getCarrierIdByType($this->type),
                'current_carrier' => AbstractCarrierCdek::getCarrierIdByType($this->type),
            ],
            'id_order' => (int) Tools::getValue('id_order'),
            'widget_settings' => [
                'map_api_key' => ConfigurationCdek::get('map_api_key'),
                'defaultCity' => $default_city,
                'lang' => $this->lang,
                'link' => 'forpvz',
                'region' => false,
                'path' => $this->module->getPathUri() . 'views/',
                'servicepath' => $this->context->link->getModuleLink($this->module->name, 'service'),
                'widgetpath' => $this->context->link->getModuleLink($this->module->name, 'widget'),
                'showWarns' => _PS_MODE_DEV_,
                'showErrors' => _PS_MODE_DEV_,
                'showLogs' => _PS_MODE_DEV_,
                'hidedelt' => true,
            ],
        ]);

        $this->context->smarty->assign([
            'js_def' => Media::getJsDef(),
            'javascripts' => $javascripts,
            'pickup_point' => $this->lang == 'rus' ? 'Пункт выдачи' : 'Pick-up point',
            'city_name' => isset($this->pvz['CITYFULL']) ? $this->pvz['CITYFULL'][$default_city] : '',
            'pvz_address' => isset($this->pvz['PVZ']) ? $this->pvz['PVZ'][$default_city][$this->customer->{$this->type}]['Address'] : '',
        ]);
        echo $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/front/widget/widget.tpl');

        exit;
    }

    private function selectPvz()
    {
        $type = Tools::getValue('type');
        $city = Tools::getValue('city');
        $pvz = Tools::getValue('pvz');
        $this->customer->{'city_' . $type} = $city;
        $this->customer->{$type} = $pvz;
        $this->customer->save();

        exit(json_encode(['result' => true]));
    }

    private function cityChange()
    {
        $id_carrier = trim(Tools::getValue('delivery_option'), ',');
        $city = Tools::getValue('city');

        if (Validate::isLoadedObject($this->context->cart)) {
            $cart = $this->context->cart;
        } elseif (Tools::getIsset('id_order')) {
            $order = new Order(Tools::getValue('id_order'));
            $id_carrier = $order->id_carrier;
            $cart = new Cart($order->id_cart);
        }

        $cdek_cart = new CartFromCart($cart);

        $carrier_cdek = $this->module->getCdekCarrier($cdek_cart, $id_carrier);
        $carrier_cdek->customer->{'city_' . $this->type} = $city;
        $carrier_cdek->customer->{$this->type} = current($this->pvz['PVZ'][$carrier_cdek->customer->{'city_' . $this->type}])['code'];
        $price = $carrier_cdek->calculate(false)->getPrice();

        if ($price !== false) { // может быть 0
            $carrier_cdek->customer->save();
            exit(json_encode([
                'hasError' => false,
                'widget' => $this->module->getWidgetIframe(
                    [
                    'delivery_option' => $id_carrier,
                    'iframe_url' => $this->context->link->getModuleLink(
                        $this->module->name,
                        'widget',
                        [
                            'type' => $this->type,
                            'customer' => $carrier_cdek->customer->id,
                            'city' => $carrier_cdek->customer->{'city_' . $this->type},
                            'postal_code' => '--POSTCODE--',
                        ]
                    ),
                    'height' => '400px',
                    'width' => '100%',
                    ]
                ),
            ]));
        }

        exit(json_encode([
            'hasError' => true,
        ]));
    }

    private function getTemplates()
    {
        $files = scandir($D = _PS_MODULE_DIR_ . $this->module->name . '/views/templates/front/widget_tpl');
        unset($files[0]);
        unset($files[1]);

        $arTPL = [];
        foreach ($files as $filesname) {
            $file_tmp = explode('.', $filesname);
            $content = file_get_contents($D . '/' . $filesname);
            $arTPL[strtolower($file_tmp[0])] = str_replace(['\r', '\n', '\t', '\n', '\r', '\t'],'', $content);
        }

        return $arTPL;
    }

    public function getWidgetLang($lang = false)
    {
        $tanslate = [
            'rus' => [
                'YOURCITY' => 'Ваш город',
                'COURIER' => 'Курьер',
                'PICKUP' => 'Самовывоз',
                'TERM' => 'Срок',
                'PRICE' => 'Стоимость',
                'DAY' => 'дн.',
                'RUB' => 'руб.',
                'NODELIV' => 'Нет доставки',
                'CITYSEARCH' => 'Поиск города',
                'ALL' => 'Все',
                'PVZ' => 'Пункты выдачи',
                'MOSCOW' => 'Москва',
                'RUSSIA' => 'Россия',
                'COUNTING' => 'Идет расчет',

                'NO_AVAIL' => 'Нет доступных способов доставки',
                'CHOOSE_TYPE_AVAIL' => 'Выберите способ доставки',
                'CHOOSE_OTHER_CITY' => 'Выберите другой населенный пункт',

                'L_ADDRESS' => 'Адрес пункта выдачи заказов',
                'L_TIME' => 'Время работы',
                'L_WAY' => 'Как к нам проехать',
                'L_CHOOSE' => 'Выбрать',

                'H_LIST' => 'Список пунктов выдачи заказов',
                'H_PROFILE' => 'Способ доставки',
                'H_CASH' => 'Расчет картой',
                'H_DRESS' => 'С примеркой',
                'H_SUPPORT' => 'Служба поддержки',
                'H_QUESTIONS' => 'Если у вас есть вопросы, можете<br> задать их нашим специалистам',
            ],
            'eng' => [
                'YOURCITY' => 'Your city',
                'COURIER' => 'Courier',
                'PICKUP' => 'Pickup',
                'TERM' => 'Term',
                'PRICE' => 'Price',
                'DAY' => 'days',
                'RUB' => ' RUB',
                'NODELIV' => 'Not delivery',
                'CITYSEARCH' => 'Search for a city',
                'ALL' => 'All',
                'PVZ' => 'Points of self-delivery',
                'MOSCOW' => 'Moscow',
                'RUSSIA' => 'Russia',
                'COUNTING' => 'Calculation',

                'NO_AVAIL' => 'No shipping methods available',
                'CHOOSE_TYPE_AVAIL' => 'Choose a shipping method',
                'CHOOSE_OTHER_CITY' => 'Choose another location',

                'L_ADDRESS' => 'Adress of self-delivery',
                'L_TIME' => 'Working hours',
                'L_WAY' => 'How to get to us',
                'L_CHOOSE' => 'Choose',

                'H_LIST' => 'List of self-delivery',
                'H_PROFILE' => 'Shipping method',
                'H_CASH' => 'Payment by card',
                'H_DRESS' => 'Dressing room',
                'H_SUPPORT' => 'Support',
                'H_QUESTIONS' => 'If you have any questions,<br> you can ask them to our specialists',
            ],
        ];

        if ($this->lang) {
            return $tanslate[$this->lang];
        }

        return $tanslate['eng'];
    }
}
