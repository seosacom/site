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

use Seleda\Cdek\Component\Pvz\Client;

class AdminCdekSettingController extends ModuleAdminController
{
    private $cdek_lang;
    private $config;

    public function checkHttp()
    {
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }

        if (array_key_exists('X-Forwarded-Proto', $headers)) {
            $_SERVER['HTTP_X_FORWARDED_PROTO'] = $headers['X-Forwarded-Proto'];
        } elseif (array_key_exists('X-Request-Scheme', $headers)) {
            $_SERVER['HTTP_X_REQUEST_SCHEME'] = $headers['X-Request-Scheme'];
        }

        if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http') ||
            (isset($_SERVER['HTTP_X_REQUEST_SCHEME']) && $_SERVER['HTTP_X_REQUEST_SCHEME'] == 'http')) {
            return true;
        }
        return false;
    }

    public function init()
    {
        if (Configuration::get('PS_SSL_ENABLED') && $this->checkHttp()) {
            $admin_dir_exp = explode(DIRECTORY_SEPARATOR, _PS_ADMIN_DIR_);
            Tools::redirectAdmin(Context::getContext()->link->getBaseLink() . array_pop($admin_dir_exp) . '/' . Context::getContext()->link->getAdminLink('AdminCdekSetting'));
        }

        $this->bootstrap = true;
        parent::init();
        $this->cdek_lang = LangCdek::getInstance($this->context->language)->getLang();
        $this->config = ConfigurationCdek::get();
        $this->config->setDefaultCategories(ConfigurationCdek::get('default_categories'));
        $this->config->setStatuses(ConfigurationCdek::get('statuses'));
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addjqueryPlugin('sortable');

        $this->addCSS('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
        $this->addCSS('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css');
        $this->addCSS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/settings.css');
        $this->addCSS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/back.css');

        if (version_compare(_PS_VERSION_, '1.7.8', '>')) {
            $this->addCSS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/back178.css');
        }

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $this->addCSS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/back16.css');
        }

        $this->addJS('https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js');
        $this->addJS('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js');
        $this->addJS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/cdek.js');
        $this->addJS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/back.js');
        $this->addJS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/clipboard.js');
        Media::addJsDef(['cdek_settings' => $this->module->getCdekSettings()]);
    }

    public function renderGenericForm($fields_form, $fields_value, $tpl_vars = [])
    {
        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = [];
        $helper->id = (int) Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->tpl_vars = array_merge([
            'fields_value' => $fields_value,
            'languages' => $this->getLanguages(),
            'id_language' => $this->context->language->id,
            'methods' => LoggerCdek::getMethods(),
            'log_items' => LoggerCdek::getAll(1),
            'pages' => ceil(LoggerCdek::getAll(1, true) / LoggerCdek::LIMIT),
        ], $tpl_vars);

        return $helper->generateForm([$fields_form]);
    }

    protected function getFieldsFormGeneral($type_contract)
    {
        $fields = ['input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Account'),
                    'name' => 'account',
                    'desc' => $this->l('This is not a login from a personal account'),
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Secure password'),
                    'name' => 'secure_password',
                    'class' => 'fixed-width-xxl separator',
                    'desc' => $this->l('separator'),
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Yandex map API Key'),
                    'name' => 'map_api_key',
                    'class' => 'fixed-width-xxl separator',
                    'desc' => $this->l('separator'),
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Type contract'),
                    'name' => 'type_contract',
                    'class' => 'fixed-width-xxl',
                    'options' => ['query' => [
                        ['id' => 1, 'name' => $this->l('Online store')],
                    ],
                        'id' => 'id',
                        'name' => 'name',
                    ],
                    'required' => false,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Seller name'),
                    'name' => 'seller_name',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                    'hint' => $this->l('Only for orders "online store", if the order is international'),
                    'desc' => $this->l('Seller name desc'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Sender'),
                    'name' => 'sender_company',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => false,
                    'hint' => $this->l('Sender hint'),
                    'desc' => $this->l('Sender desc'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Manager'),
                    'name' => 'sender_name',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => false,
                    'hint' => $this->l('Manager name'),
                    'desc' => $this->l('Manager desc'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Sender phone'),
                    'name' => 'sender_phone',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                    'hint' => $this->l('Sender phone hint'),
                    'desc' => $this->l('Sender phone desc'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Address of the shipper'),
                    'name' => 'shipper_address',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => false,
                    'hint' => $this->l('Address of the shipper hint'),
                    'desc' => $this->l('Address of the shipper desc'),
                ],
                [
                    'type' => 'select_currency',
                    'label' => $this->l('Сurrency of the contract'),
                    'class' => 'fixed-width-xxl float-left margin-right-lg',
                    'hint' => $this->l('The currency that the online store uses to work with CDEK'),
                    'name' => 'contract_currency',
                    'options' => ['query' => array_merge(
                        [['id_currency' => 0, 'name' => '--']],
                        Currency::getCurrencies()
                    ),
                        'id' => 'id_currency',
                        'name' => 'name',
                    ],
                    'desc_currency' => $this->l('Сurrency / VAT'),
                ],
                [
                    'type' => 'hidden_block',
                    'label' => $this->l('VAT'),
                    'class' => 'fixed-width-sm float-left margin-right-lg',
                    'hint' => $this->l('VAT under the contract with the Cdek'),
                    'name' => 'vat',
                    'options' => ['query' => array_merge(
                        [['val' => 0, 'name' => '0%']],
                        [['val' => 20, 'name' => '20%']]
                    ),
                        'id' => 'val',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'switch_part_deliv',
                    'label' => $this->l('Partial delivery'),
                    'hint' => $this->l('Partial delivery hint'),
                    'desc_part_deliv' => $this->l('Partial delivery desc'),
                    'name' => 'part_deliv',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ],
                [
                    'type' => 'switch_one_package',
                    'label' => $this->l('One product - one package'),
                    'hint' => $this->l('One product - one package hint'),
                    'desc_one_package' => $this->l('One product - one package desc'),
                    'name' => 'one_package',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'one_package_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ],
                        [
                            'id' => 'one_package_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ],
                    ],
                ],
                [
                    'type' => 'switch_all_is_one_package',
                    'label' => $this->l('All products - one package'),
                    'hint' => $this->l('All products - one package hint'),
                    'desc_all_is_one_package' => $this->l('All products - one package desc'),
                    'name' => 'all_is_one_package',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'all_is_one_package_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ],
                        [
                            'id' => 'all_is_one_package_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ],
                    ],
                ],
                [
                    'type' => 'switch_all_one_box',
                    'label' => $this->l('All products - one box'),
                    'hint' => $this->l('All products - one box hint'),
                    'desc_all_one_box' => $this->l('All products - one box desc'),
                    'name' => 'all_one_box',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'all_one_box_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ],
                        [
                            'id' => 'all_one_box_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ],
                    ],
                ],
            ],
        ];
        $fields['submit'] = [
            'title' => $this->l('Save'),
            'class' => 'btn btn-primary btn-lg pull-right',
            'name' => 'saveGeneralsForm',
        ];
        return $fields;
    }

    protected function getFieldsFormLocation()
    {
        $fields = [
            'input' => [
                [
                    'type' => 'select',
                    'label' => $this->l('Country warehouse'),
                    'name' => 'country_warehouse',
                    'hint' => $this->l('country_warehouse hint'),
                    'desc' => $this->l('country_warehouse desc'),
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'options' => ['query' => array_merge(
                        [['iso_code' => null, 'name' => '--']],
                        Country::getCountries($this->context->language->id, true)
                    ),
                        'id' => 'iso_code',
                        'name' => 'name',
                    ],
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Postcode warehouse'),
                    'hint' => $this->l('Postcode warehouse hint'),
                    'desc' => $this->l('Postcode warehouse desc'),
                    'name' => 'postal_code',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('City warehouse'),
                    'hint' => $this->l('City warehouse hint'),
                    'desc' => $this->l('City warehouse desc'),
                    'name' => 'city_warehouse',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Address warehouse'),
                    'hint' => $this->l('Address warehouse hint'),
                    'desc' => $this->l('Address warehouse desc'),
                    'name' => 'address_warehouse',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('PVZ warehouse'),
                    'hint' => $this->l('PVZ warehouse hint'),
                    'desc' => $this->l('PVZ warehouse desc'),
                    'name' => 'pvz_warehouse',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'required' => true,
                ],
                [
                    'type' => 'html',
                    'label' => Configuration::get('pvz_date_upd'),
                    'hint' => $this->l('pvz_date_upd hint'),
                    'desc' => $this->l('Last date of updating the list of pickup points'),
                    'name' => '',
                    'html_content' => '<button type="button" class="btn btn-default fixed-width-xxl float-left mr-1" id="load_pvz_button">
                                       <i class="fa fa-refresh fa-spin" style="display:none;"></i>' . $this->l('Update PVZ list') . '</button>',
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Country for upload'),
                    'hint' => $this->l('Country for upload hint'),
                    'desc' => $this->l('Country for upload desc'),
                    'name' => 'country_for_upload',
                    'class' => 'fixed-width-xxl float-left mr-1',
                    'options' => ['query' => array_merge(
                        [['iso_code' => 0, 'name' => '--']],
                        Country::getCountries($this->context->language->id, true)
                    ),
                        'id' => 'iso_code',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'button_city_upload',
                    'attributes' => [
                        'id' => 'load_city_button',
                    ],
                    'name' => '',
                    'text' => $this->l('Download the city database'),
                ],
                [
                    'type' => 'hr',
                ],
                [
                    'type' => 'pvz_test',
                    'label' => $this->l('Test PVZ'),
                    'hint' => $this->l('Enter the zip code or city code'),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary btn-lg pull-right',
                'name' => 'saveLocationForm',
            ],
        ];

        return $fields;
    }

    protected function getFieldsFormTariffs()
    {
        $tariffs = [];
        foreach (['courier', 'pickup', 'postamat'] as $type) {
            $tariffs[$type] = TariffCdek::getTariffsByType($type);
        }

        $fields = [
            'input' => [
                [
                    'type' => 'tariffs',
                    'label' => '',
                    'name' => '',
                    'cdek_lang' => $this->cdek_lang,
                    'tariff_all' => $tariffs,
                    'col' => 12,
                ],
            ],
        ];

        return $fields;
    }

    protected function getFieldsFormCarriers()
    {
        $carriers = [];
        $carrier_references = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cdek_carrier_type`');
        foreach ($carrier_references as $reference) {
            $carriers[$reference['type']] = Carrier::getCarrierByReference($reference['carrier_reference']);
        }

        $fields = [
            'input' => [
                [
                    'type' => 'carriers',
                    'label' => '',
                    'name' => 'carriers',
                    'carriers' => $carriers,
                    'col' => 12,
                    'options' => ['query' => array_merge(
                        [['iso_code' => 0, 'name' => '--']],
                        Country::getCountries($this->context->language->id, true)
                    ),
                        'id' => 'iso_code',
                        'name' => 'name',
                    ],
                ],
            ],
        ];
        $fields['submit'] = [
            'title' => $this->l('Save'),
            'class' => 'btn btn-primary btn-lg pull-right',
            'name' => 'saveCarriersForm',
        ];

        return $fields;
    }

    public function getFieldsFormMetrics()
    {
        $fields = [
            'input' => [
                [
                    'type' => 'select_unit',
                    'label' => $this->l('Unit of weight and length'),
                    'name' => 'weight_unit',
                    'class' => 'fixed-width-lg float-left margin-right-lg',
                    'desc_unit' => $this->l('Unit of weight / Unit of length'),
                    'options' => ['query' => [
                        ['code' => '1', 'name' => $this->l('Gram')],
                        ['code' => '1000', 'name' => $this->l('Kilogram')],
                    ],
                        'id' => 'code',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'hidden_block',
                    'label' => $this->l('Unit of length'),
                    'name' => 'volume_unit',
                    'class' => 'fixed-width-lg float-left margin-right-lg',
                    'options' => ['query' => [
                        ['code' => '0.1', 'name' => $this->l('Millimeter')],
                        ['code' => '1', 'name' => $this->l('Centimeter')],
                        ['code' => '100', 'name' => $this->l('Metre')],
                    ],
                        'id' => 'code',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('The default weight'),
                    'name' => 'default_weight',
                    'desc' => $this->l('The default weight desc'),
                    'required' => true,
                    'class' => 'fixed-width-md float-left mr-1',
                    'hint' => $this->l('If you can\'t determine the weight of the product'),
                ],
                [
                    'type' => 'text_dimensions',
                    'label' => $this->l('The default dimensions'),
                    'name' => 'default_width',
                    'class' => 'fixed-width-md float-left margin-right-lg',
                    'desc_dimensions' => $this->l('width / height / depth'),
                ],
                [
                    'type' => 'hidden_block',
                    'label' => $this->l('The default height'),
                    'name' => 'default_height',
                    'class' => 'fixed-width-md float-left margin-right-lg',
                    'hint' => $this->l('If you can\'t determine the height of the product'),
                ],
                [
                    'type' => 'hidden_block',
                    'label' => $this->l('The default depth'),
                    'name' => 'default_length',
                    'class' => 'fixed-width-md float-left  margin-right-lg',
                    'hint' => $this->l('If you can\'t determine the depth of the product'),
                ],
                [
                    'type' => 'cdek_categories',
                    'label' => $this->l('Categories default weight'),
                    'name' => 'default_categories',
                    'hint' => $this->l('If you can\'t determine the weight of the product'),
                    'categories' => Category::getCategories(false, true, false),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary btn-lg pull-right',
                'name' => 'saveMetricsForm',
            ],
        ];
        return $fields;
    }

    public function getFieldsFormCalculator()
    {
        $fields = [
            'input' => [
                [
                    'type' => 'select',
                    'label' => $this->l('Departure delay'),
                    'name' => 'delay',
                    'hint' => $this->l('Departure delay in days'),
                    'options' => ['query' => [
                        ['code' => '0', 'name' => '0'],
                        ['code' => '1', 'name' => '1'],
                        ['code' => '2', 'name' => '2'],
                        ['code' => '3', 'name' => '3'],
                        ['code' => '4', 'name' => '4'],
                        ['code' => '5', 'name' => '5'],
                        ['code' => '6', 'name' => '6'],
                        ['code' => '7', 'name' => '7'],
                        ['code' => '8', 'name' => '8'],
                        ['code' => '9', 'name' => '9'],
                        ['code' => '10', 'name' => '10'],
                    ],
                        'id' => 'code',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Estimated departure time'),
                    'name' => 'departure_time',
                    'desc' => $this->l('separator'),
                    'required' => true,
                    'hint' => $this->l('Estimated time to call a courier or deliver a parcel to the warehouse'),
                    'class' => 'fixed-width-xl separator',
                    'disabled' => false,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Waiting date for the courier'),
                    'name' => 'waiting_date_courier',
                    'required' => false,
                    'hint' => $this->l("The courier's waiting date cannot be more than 1 year older than the current one.
                                                         An order created on the current date after 15:00 sender time can be executed the next day"),
                    'class' => 'fixed-width-xl separator',
                    'disabled' => false,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Courier start time'),
                    'name' => 'courier_start_time',
                    'required' => false,
                    'hint' => $this->l('Courier start time. Not earlier than 9:00 local time'),
                    'class' => 'fixed-width-xl separator',
                    'disabled' => false,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('End time for courier'),
                    'name' => 'end_time_for_courier',
                    'desc' => $this->l('separator'),
                    'required' => false,
                    'hint' => $this->l('End time for courier. No later than 22:00 local time'),
                    'class' => 'fixed-width-xl separator',
                    'disabled' => false,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('The correction value of the delivery'),
                    'name' => 'total_correction',
                    'desc' => $this->l('Example: -10 or 10'),
                    'hint' => $this->l('If the shipping cost stably differs by a certain percentage'),
                    'disabled' => false,
                    'class' => 'fixed-width-xl float-left mr-1',
                    'placeholder' => '0.00',
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Adjustment value type'),
                    'hint' => $this->l('Adjustment value type 2'),
                    'class' => 'separator',
                    'name' => 'type_correction',
                    'desc' => $this->l('separator'),
                    'options' => ['query' => [
                        ['code' => '1', 'name' => $this->l('percent')],
                        ['code' => '2', 'name' => $this->l('value')],
                    ],
                        'id' => 'code',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Reduce the price of insurance'),
                    'name' => 'product_price_reduction',
                    'desc' => $this->l('Acceptable values are 0 - 100'),
                    'hint' => $this->l('Reduce the price of the product by a percentage. Insurance is calculated from the cost of the product.'),
                    'disabled' => false,
                    'class' => 'fixed-width-xl float-left mr-1',
                    'placeholder' => '0.00',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Impact on the percentage of the basket'),
                    'name' => 'impact_percent_of_cart',
                    'desc' => $this->l('Example: -10 or 10'),
                    'hint' => $this->l('Decrease or increase in the cost of delivery by a percentage of the cost of the basket.'),
                    'disabled' => false,
                    'class' => 'fixed-width-xl float-left mr-1',
                    'placeholder' => '0.00',
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Weight allowance'),
                    'hint' => $this->l('Weight from 75 kg - 200 kg + 18 rubles \/ kg, more than 200 kg + 25 rubles \/ kg'),
                    'desc' => $this->l('Weight from 75 kg - 200 kg + 18 rubles\/kg, more than 200 kg + 25 rubles\/kg'),
                    'name' => 'weight_allowance',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'weight_allowance_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ],
                        [
                            'id' => 'weight_allowance_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary btn-lg pull-right',
                'name' => 'saveCalculatorForm',
            ],
        ];

        return $fields;
    }

    public function getFieldsFormStatuses()
    {
        $fields = [
            'input' => [
                [
                    'type' => 'statuses',
                    'label' => '',
                    'name' => 'statuses',
                    'col' => 12,
                    'statuses' => OrderState::getOrderStates($this->context->language->id),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary btn-lg pull-right',
                'name' => 'saveStatusesForm',
            ],
        ];

        return $fields;
    }

    public function getFieldsFormLog()
    {
        $fields = [
            'input' => [
                [
                    'type' => 'log',
                    'label' => '',
                    'name' => 'write_log',
                    'col' => 12,
                    'values' => [
                        [
                            'value' => 1,
                        ],
                        [
                            'value' => 0,
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary btn-lg pull-right',
                'name' => 'saveLogForm',
            ],
        ];

        return $fields;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('saveGeneralsForm')) {
            if ($account = Tools::getValue('account')) {
                $this->config->account = $account;
            } else {
                $this->errors[] = $this->l('Account required field');
            }

            if ($secure_password = Tools::getValue('secure_password')) {
                $this->config->secure_password = $secure_password;
            } else {
                $this->errors[] = $this->l('Secure password required field');
            }

            $this->config->sender_company = Tools::getValue('sender_company');
            $this->config->seller_name = Tools::getValue('seller_name');
            $this->config->sender_name = Tools::getValue('sender_name'); // manager
            $this->config->sender_phone = Tools::getValue('sender_phone');
            $this->config->shipper_address = Tools::getValue('shipper_address');

            if ($type_contract = Tools::getValue('type_contract')) {
                $this->config->type_contract = $type_contract;
            }

            $contract_currency = Tools::getValue('contract_currency');
            $this->config->contract_currency = $contract_currency;

            $vat = Tools::getValue('vat');
            $this->config->vat = $vat;

            $part_deliv = Tools::getValue('part_deliv');
            $this->config->part_deliv = $part_deliv;

            $one_package = Tools::getValue('one_package');
            $this->config->one_package = $one_package;

            $all_is_one_package = Tools::getValue('all_is_one_package');
            $this->config->all_is_one_package = $all_is_one_package;

            $all_one_box = Tools::getValue('all_one_box');
            $this->config->all_one_box = $all_one_box;

            $map_api_key = Tools::getValue('map_api_key');
            $this->config->map_api_key = $map_api_key;
            // Delete token API
            Configuration::deleteByName('CDEK_API_TOKEN');

            $this->config->save();
        } elseif (Tools::isSubmit('saveLocationForm')) {
            $this->config->country_warehouse = Tools::getValue('country_warehouse') ?: $this->errors[] = $this->l('Country warehouse required field');
            $this->config->postal_code = Tools::getValue('postal_code') ?: $this->errors[] = $this->l('Postcode warehouse required field');
            $this->config->city_warehouse = Tools::getValue('city_warehouse') ?: $this->errors[] = $this->l(' City warehouse required field');
            $this->config->address_warehouse = Tools::getValue('address_warehouse') ?: $this->errors[] = $this->l('Address warehouse required field');
            $this->config->pvz_warehouse = Tools::getValue('pvz_warehouse') ?: $this->errors[] = $this->l('PVZ warehouse required field');
            if (count($this->errors) == 0) {
                $address = new Address();
                $address->postcode = $this->config->postal_code;
                $address->id_country = Country::getByIso($this->config->country_warehouse);
                $city_from = new CityCdek($address);
                if (!$city_from->getCode()) {
                    $this->errors[] = $this->l('Your postal code for selected country is not in the database');
                }
                // TODO remove cache
                $this->config->save();
            }
        } elseif (Tools::isSubmit('saveCarriersForm')) {
            foreach (Db::getInstance()->executeS('SELECT `type` FROM `' . _DB_PREFIX_ . 'cdek_carrier_type`') as $type) {
                $this->config->{'free_shipping_' . $type['type']} = (bool) Tools::getValue('free_shipping_' . $type['type']);
                $this->config->{'free_price_' . $type['type']} = Tools::getValue('free_price_' . $type['type']) * 100;
                $this->config->{'free_weight_' . $type['type']} = Tools::getValue('free_weight_' . $type['type']);
            }
            $this->config->save();
        } elseif (Tools::isSubmit('saveMetricsForm')) {
            $default_categories = [];
            $weight = Tools::getValue('default_categories_weight');
            $width = Tools::getValue('default_categories_width');
            $height = Tools::getValue('default_categories_height');
            $length = Tools::getValue('default_categories_length');
            foreach ($weight as $id_category => $value) {
                if ($value && !Validate::isFloat($value)) {
                    $this->errors[] = $this->l('Default weight not valid');
                }
                if ($width[$id_category] && !Validate::isFloat($width[$id_category])) {
                    $this->errors[] = $this->l('Default width not valid');
                }
                if ($length[$id_category] && !Validate::isFloat($length[$id_category])) {
                    $this->errors[] = $this->l('Default length not valid');
                }
                if ($height[$id_category] && !Validate::isFloat($height[$id_category])) {
                    $this->errors[] = $this->l('Default height not valid');
                }
                $default_categories[$id_category] = [
                    'weight' => $value,
                    'width' => $width[$id_category],
                    'height' => $height[$id_category],
                    'length' => $length[$id_category],
                ];
            }

            $this->config->setDefaultCategories($default_categories);

            $this->config->weight_unit = Tools::getValue('weight_unit');

            $this->config->volume_unit = Tools::getValue('volume_unit');

            $this->config->default_weight = Tools::getValue('default_weight');

            $this->config->default_width = Tools::getValue('default_width');

            $this->config->default_length = Tools::getValue('default_length');

            $this->config->default_height = Tools::getValue('default_height');

            if ($this->config->default_weight && !Validate::isFloat($this->config->default_weight)) {
                $this->errors[] = $this->l('Default weight not valid');
            }
            if ($this->config->default_width && !Validate::isFloat($this->config->default_width)) {
                $this->errors[] = $this->l('Default width not valid');
            }
            if ($this->config->default_length && !Validate::isFloat($this->config->default_length)) {
                $this->errors[] = $this->l('Default length not valid');
            }
            if ($this->config->default_height && !Validate::isFloat($this->config->default_height)) {
                $this->errors[] = $this->l('Default height not valid');
            }

            if (!$this->config->default_weight) {
                $this->errors[] = $this->l('Default weight not valid');
            }

            if (!count($this->errors)) {
                $this->config->save();
            }
        } elseif (Tools::isSubmit('saveCalculatorForm')) {
            $this->config->delay = Tools::getValue('delay');
            $this->config->departure_time = Tools::getValue('departure_time');
            $this->config->courier_start_time = Tools::getValue('courier_start_time');
            $this->config->end_time_for_courier = Tools::getValue('end_time_for_courier');
            $this->config->waiting_date_courier = Tools::getValue('waiting_date_courier');
            $this->config->total_correction = Tools::getValue('total_correction');
            $this->config->product_price_reduction = Tools::getValue('product_price_reduction');
            $this->config->impact_percent_of_cart = Tools::getValue('impact_percent_of_cart');
            $this->config->type_correction = Tools::getValue('type_correction');
            $this->config->type_correction = Tools::getValue('type_correction');
            $this->config->weight_allowance = Tools::getValue('weight_allowance');

            if (!preg_match('/^\d{2}:\d{2}$/u', $this->config->departure_time)) {
                $this->errors[] = $this->l('Departure time not valid');
            } else {
                $this->config->save();
            }
            if (!preg_match('/^\d{2}:\d{2}$/u', $this->config->courier_start_time)) {
                $this->errors[] = $this->l('Courier start time');
            } else {
                $this->config->save();
            }
            if (!preg_match('/^\d{2}:\d{2}$/u', $this->config->end_time_for_courier)) {
                $this->errors[] = $this->l('End time for courier not valid');
            } else {
                $this->config->save();
            }
        } elseif (Tools::isSubmit('saveStatusesForm')) {
            $statuses = ['create' => [], 'delete' => [], 'cod_ship' => [], 'cod' => []];
            foreach (OrderState::getOrderStates($this->context->language->id) as $state) {
                if (Tools::getIsset('statusesCreateBox') && in_array($state['id_order_state'], Tools::getValue('statusesCreateBox'))) {
                    $statuses['create'][] = $state['id_order_state'];
                }
                if (Tools::getIsset('statusesDeleteBox') && in_array($state['id_order_state'], Tools::getValue('statusesDeleteBox'))) {
                    $statuses['delete'][] = $state['id_order_state'];
                }
                if (Tools::getIsset('statusesCodShipBox') && in_array($state['id_order_state'], Tools::getValue('statusesCodShipBox'))) {
                    $statuses['cod_ship'][] = $state['id_order_state'];
                }
                if (Tools::getIsset('statusesCodBox') && in_array($state['id_order_state'], Tools::getValue('statusesCodBox'))) {
                    $statuses['cod'][] = $state['id_order_state'];
                }
            }
            $this->config->setStatuses($statuses);
            $this->config->save();
        } elseif (Tools::isSubmit('saveLogForm')) {
            $this->config->write_log = Tools::getValue('write_log');
            $this->config->save();
        }

        return parent::postProcess();
    }

    public function initContent()
    {
        $fieds_value = get_object_vars($this->config);
        $fieds_value['statuses'] = $this->config->getStatuses();
        $fieds_value['default_categories'] = $this->config->getDefaultCategories();

        $this->context->smarty->assign('cdek_configuration', ConfigurationCdek::$instance);

        $this->context->smarty->assign([
            'documentation_links' => $this->module->getDocumentationLinks(),
            'cdek_lang' => LangCdek::getInstance($this->context->language->id)->getLang(),
            'general_form' => $this->renderGenericForm(['form' => $this->getFieldsFormGeneral($this->config->type_contract)], $fieds_value),
            'location_form' => $this->renderGenericForm(['form' => $this->getFieldsFormLocation()], $fieds_value),
            'tariffs_form' => $this->renderGenericForm(['form' => $this->getFieldsFormTariffs()], $fieds_value),
            'carriers_form' => $this->renderGenericForm(['form' => $this->getFieldsFormCarriers()], $fieds_value),
            'metrics_form' => $this->renderGenericForm(['form' => $this->getFieldsFormMetrics()], $fieds_value),
            'calculator_form' => $this->renderGenericForm(['form' => $this->getFieldsFormCalculator()], $fieds_value),
            'statuses_form' => $this->renderGenericForm(['form' => $this->getFieldsFormStatuses()], $fieds_value),
            'log_form' => $this->renderGenericForm(['form' => $this->getFieldsFormLog()], $fieds_value),
            'widgets' => $this->module->getWidgetIframes(),
        ]);
        $this->setTemplate('setting.tpl');
        return parent::initContent();
    }

    public function ajaxProcessTariffPosition()
    {
        $positions = Tools::getValue('positions');
        if (!is_array($positions)) {
            return false;
        }
        foreach ($positions as $key => $id_tariff) {
            $tariff = new TariffCdek($id_tariff);
            if (Validate::isLoadedObject($tariff)) {
                $tariff->position = $key + 1;
                $tariff->update();
            }
        }
        // clear cache
        $type = $tariff->getType();
        Db::getInstance()->delete('cdek_calculator', '`type` = "' . $type . '"');
    }

    public function ajaxProcessTariffActive()
    {
        $id = Tools::getValue('id');
        $tariff = new TariffCdek($id);
        if (!Validate::isLoadedObject($tariff)) {
            exit('error');
        }
        $tariff->active = !$tariff->active;
        $tariff->update();
        exit($tariff->active ? 'check' : 'close');
    }

    public function ajaxProcessCitiesLoad()
    {
        $page = Tools::getValue('page');
        $size = Tools::getValue('size');
        $country_code = Tools::getValue('country_code');

        try {
            if (CityCdek::loadCities($page, $size, $country_code)) {
                exit(json_encode([
                    'hasError' => false,
                    'message' => $this->l('The database is loaded'),
                    'page' => $page + 1,
                ]));
            } elseif ($page == 0) {
                exit(json_encode([
                    'hasError' => false,
                    'message' => $this->l('The database not loaded'),
                    'page' => false,
                ]));
            } else {
                exit(json_encode([
                    'hasError' => false,
                    'message' => $this->l('The database is loaded'),
                    'page' => false,
                ]));
            }
        } catch (Exception $e) {
            exit(json_encode([
                'hasError' => true,
                'message' => $e->getMessage(),
            ]));
        }
    }

    public function ajaxProcessChangeOrder()
    {
        $field = Tools::getValue('field');
        $value = Tools::getValue('value');
        $id_order = Tools::getValue('id_order');
        $id_cart = Order::getCartIdStatic($id_order);
        $cdek_order = CdekOrder::getByIdCart($id_cart);
        $cdek_order->setBidField($field, $value);
        if ($cdek_order->save()) {
            exit(json_encode([
                'hasError' => false,
            ]));
        }
        exit(json_encode([
            'hasError' => true,
        ]));
    }

    public function ajaxProcessUpdatePvzList()
    {
        Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_pvz`');

        PvzCdek::loadPoints();
        Configuration::updateValue('pvz_date_upd', date('Y-m-d H:i:s'));
        exit(Configuration::get('pvz_date_upd'));
    }

    public function ajaxProcessGetCdekLog()
    {
        $page = Tools::getValue('page');
        $search = Tools::getValue('search');
        $rows = LoggerCdek::getAll($page, false, $search);

        $html = '';
        foreach ($rows as $row) {
            $this->context->smarty->assign([
                'row' => $row,
            ]);
            $html .= Context::getContext()->smarty->fetch(
                _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/_configure/helpers/form/table_log_row.tpl'
            );
        }

        $json = [
            'html' => $html,
        ];

        if ($page == 1) {
            $total = LoggerCdek::getAll($page, true, $search);
            $json['pages'] = ceil($total / LoggerCdek::LIMIT);
        }

        exit(json_encode($json));
    }

    public function ajaxProcessClearCdekLog()
    {
        Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_logger`');
        exit('ok');
    }

    public function ajaxProcessClearCalculatorCache()
    {
        Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_calculator`');
        exit('ok');
    }

    public function ajaxProcessTestPvz()
    {
        $field_name = Tools::getValue('field_name');
        $value = Tools::getValue('value');
        $params = [
            'type' => 'PVZ',
            $field_name => $value,
        ];
        $client = Client::getInstance();
        if ($client->getPvz($params)) {
            exit(json_encode($client->getResult()));
        } else {
            exit(json_encode($client->getError()));
        }
    }

    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return parent::l($string, __CLASS__);
    }
}
