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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__).'/classes/tools/config.php');

class CustomPaymentMethod extends PaymentModule
{
    private $html = '';
    
    private $post_fields = [];

    public $custom_payment;
    
    public function __construct()
    {
        $this->name = 'custompaymentmethod';
        $this->tab = 'payments_gateways';
        $this->version = '1.5.18';
        $this->author = 'SeoSa';
        $this->module_key = '4a370b63d364f31b2ae76a8f9e102ae2';
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $this->controllers = array('validation');
        
        parent::__construct();
        
        $this->displayName = $this->l('Custom payment module');
        $this->description = $this->l('Add payment methods');
        
        if (Tools::getValue('type')) {
            $type = (int)Tools::getValue('type');
            $custom_payment = new CustomPayment($type, $this->context->language->id);
            $display_name = 'displayName';
            if (Validate::isLoadedObject($custom_payment)) {
                $this->custom_payment = $custom_payment;
                $this->{$display_name} = $custom_payment->name;
            }
        }
        
        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module');
        }
    }
    
    public function install()
    {
        if (Tools::getValue('configure') != 'custompaymentmethod') {
            $this->installSQL();
        }

        if (!parent::install()
            ||!$this->registerHook('payment')
            ||!$this->registerHook('paymentOptions')
            ||!$this->registerHook('displayPaymentReturn')
            ||!$this->registerHook('displayAdminOrder')
            ||!$this->registerHook('displayOrderDetail')
            ||!$this->registerHook('displayCommissionForPDF')
            ||!$this->registerHook('cpmPaymentModules')
            ||!$this->registerHook('displayBackOfficeHeader')
            ||!$this->registerHook('displayHeader')
            ||!$this->registerHook('actionOrderStatusPostUpdate')
            ||!$this->registerHook('orderConfirmation')
            ||!Configuration::updateValue('PS_CPM_ICON_HEIGHT', 49)
            ||!Configuration::updateValue('PS_CPM_ICON_WIDTH', 86)
        ) {
            return false;
        }
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')
            && !$this->registerHook('displayOrderConfirmationCommission')
        ) {
            return false;
        }
        
        return true;
    }
    
    public function uninstall()
    {
        if (Tools::getValue('configure') != 'custompaymentmethod') {
            $this->uninstallSQL();
        }

        if (!parent::uninstall()) {
            return false;
        }
        
        return true;
    }
    
    public function installSQL()
    {
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'custom_payment_method` (
										`id_custom_payment_method` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
										`logo` VARCHAR( 256 ) NOT NULL ,
										`id_order_state` int(11) NOT NULL DEFAULT "0",
										`type_commission` int(11) NOT NULL DEFAULT "1",
										`confirmation_page` TINYINT NOT NULL DEFAULT  "1",
										`confirmation_page_add` TINYINT NOT NULL DEFAULT  "1",
										`show_method_available` TINYINT NOT NULL DEFAULT  "1",
										`visible_method_available` TINYINT NOT NULL DEFAULT  "1",
										`active` TINYINT NOT NULL DEFAULT  "1",
										`commission_amount` double(20,2) NOT NULL DEFAULT "0.00",
										`currency_commission` int(11) NOT NULL DEFAULT "0",
										`commission_percent` double(20,2) NOT NULL DEFAULT "0.00",
										`apply_commission` int(11) NOT NULL DEFAULT "1",
										`type_discount` int(11) NOT NULL DEFAULT "1",
										`discount_amount` double(20,2) NOT NULL DEFAULT "0.00",
										`currency_discount` int(11) NOT NULL DEFAULT "0",
										`discount_percent` double(20,2) NOT NULL DEFAULT "0.00",
										`apply_discount` int(11) NOT NULL DEFAULT "1",
										`available_groups` text NOT NULL,
										`available_carriers` text NOT NULL,
										`available_currencies` text NOT NULL,
										`available_countries` text NOT NULL,
										`view_message_field` TINYINT NOT NULL DEFAULT  "0",
										`required_message_field` TINYINT NOT NULL DEFAULT  "0",
										`is_send_mail` TINYINT NOT NULL DEFAULT  "1",
										`commission_use_tax_on_products` TINYINT NOT NULL DEFAULT  "1",
										`discount_use_tax_on_products` TINYINT NOT NULL DEFAULT  "1",
										`cart_total_from` double(20,2) NOT NULL DEFAULT  "0.00",
										`cart_total_to` double(20,2) NOT NULL DEFAULT  "0.00",
										`select_currency`  int(4) NOT NULL DEFAULT "1",
										`commission_tax` double(20,2) NOT NULL DEFAULT "0",
										`discount_tax` double(20,2) NOT NULL DEFAULT "0",
										`position` int(11) NOT NULL DEFAULT "0",
										`id_cms` int(11) NOT NULL  DEFAULT  "0",
										`commission_switch` int(11) NOT NULL  DEFAULT  "0",
										`add_history`  int(4) NOT NULL DEFAULT "0"
									) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
        );
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'custom_payment_method_lang` (
										`id_custom_payment_method` int(11) NOT NULL,
										`id_lang` int(11) NOT NULL,
										`name` text NOT NULL,
										`details` text NOT NULL,
										`description` text NOT NULL,
										`description_short` text NOT NULL,
										`name_message_field` VARCHAR(256) NOT NULL,
										`error_message_field` VARCHAR(256) NOT NULL,
										PRIMARY KEY (`id_custom_payment_method`, `id_lang`) 
									) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
        );
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'custom_payment_method_shop` (
										`id_custom_payment_method` int(11) NOT NULL,
										`id_shop` int(11) NOT NULL,
										`active` TINYINT NOT NULL DEFAULT  "1",
										PRIMARY KEY (`id_custom_payment_method`, `id_shop`)
									) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
        );
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'order_commission` (
			  `id_order` int(11) DEFAULT NULL,
			  `id_currency` int(11) NOT NULL DEFAULT "0",
			  `commission` double(20,2) NOT NULL DEFAULT "0.00",
			  `commission_tax_excl` double(20,2) NOT NULL DEFAULT "0.00",
			  `discount` double(20,2) NOT NULL DEFAULT "0.00",
			  `discount_tax_excl` double(20,2) NOT NULL DEFAULT "0.00"
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
        );
    }
    
    public function uninstallSQL()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'custom_payment_method`');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'custom_payment_method_lang`');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'custom_payment_method_shop`');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'order_commission`;');
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'module` WHERE `name` LIKE "custompaymentmethod_%"'
        );
    }

    public function postProcess()
    {
        if (Tools::isSubmit('saveConf')) {
            Configuration::updateValue('PS_CPM_DEFAULT_METHOD', Tools::getValue('PS_CPM_DEFAULT_METHOD'));
            Configuration::updateValue('PS_CPM_ICON_WIDTH', Tools::getValue('PS_CPM_ICON_WIDTH'));
            Configuration::updateValue('PS_CPM_ICON_HEIGHT', Tools::getValue('PS_CPM_ICON_HEIGHT'));
            Tools::redirectAdmin(
                'index.php?controller=AdminModules&token='
                .Tools::getValue('token').'&configure='
                .$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
            );
        }
        
        if (Tools::isSubmit('saveCustomPaymentMethod')) {
            if (Tools::getValue('edit_custom_payment_method')) {
                $custom_payment = new CustomPayment((int)Tools::getValue('edit_custom_payment_method'));
            } elseif (Tools::isSubmit('add_custom_payment_method')) {
                $custom_payment = new CustomPayment();
            }
            
            $name = [];
            $details = [];
            $description = [];
            $description_short = [];
            $name_message_field = [];
            $error_message_field = [];
            $languages = Language::getLanguages(false);
            


            foreach ($languages as $language) {
                $name[$language['id_lang']] = (Tools::getValue('name_'.$language['id_lang'])
                    ?
                    Tools::getValue('name_'.$language['id_lang'])
                    :
                    Tools::getValue('name_'.$this->context->language->id));
                $details[$language['id_lang']] = (Tools::getValue('details_'.$language['id_lang'])
                    ?
                    Tools::getValue('details_'.$language['id_lang'])
                    :
                    Tools::getValue('details_'.$this->context->language->id));
                $description[$language['id_lang']] = (Tools::getValue('description_'.$language['id_lang'])
                    ?
                    Tools::getValue('description_'.$language['id_lang'])
                    :
                    Tools::getValue('description_'.$this->context->language->id));
                $description_short[$language['id_lang']] = (Tools::getValue('description_short_'
                    .$language['id_lang'])
                    ?
                    Tools::getValue('description_short_'.$language['id_lang'])
                    :
                    Tools::getValue('description_short_'.$this->context->language->id));
                $name_message_field[$language['id_lang']] = (Tools::getValue('name_message_field_'
                    .$language['id_lang'])
                    ?
                    Tools::getValue('name_message_field_'.$language['id_lang'])
                    :
                    Tools::getValue('name_message_field_'.$this->context->language->id));
                $error_message_field[$language['id_lang']] = (Tools::getValue('error_message_field_'
                    .$language['id_lang'])
                    ?
                    Tools::getValue('error_message_field_'.$language['id_lang'])
                    :
                    Tools::getValue('error_message_field_'.$this->context->language->id));
            }

            $this->post_fields['name'] = $name;
            if (!Tools::getValue('name_'.$this->context->language->id)) {
                $this->_errors[] = $this->l('name empty');
            }
            if (preg_match("/[<>\/]/", Tools::getValue('name_'.$this->context->language->id))) {
                $this->_errors[] = $this->l('name error');
                $this->post_fields['name'] = '';
            }

            $this->post_fields['details'] = $details;
            $this->post_fields['description'] = $description;
            $this->post_fields['description_short'] = $description_short;
            $this->post_fields['confirmation_page'] = (int)Tools::getValue('confirmation_page');
            $this->post_fields['confirmation_page_add'] = (int)Tools::getValue('confirmation_page_add');
            $this->post_fields['show_method_available'] = (int)Tools::getValue('show_method_available');
            $this->post_fields['visible_method_available'] = (int)Tools::getValue('visible_method_available');
            $this->post_fields['add_history'] = (int)Tools::getValue('add_history');
            $this->post_fields['status'] = (int)Tools::getValue('status');
            $this->post_fields['view_message_field'] = (int)Tools::getValue('view_message_field');
            $this->post_fields['required_message_field'] = (int)Tools::getValue('required_message_field');
            $this->post_fields['name_message_field'] = $name_message_field;
            $this->post_fields['error_message_field'] = $error_message_field;
            $this->post_fields['id_order_state'] = (int)Tools::getValue('id_order_state');
            /**
             * Commission
             */
            $this->post_fields['commission_percent'] = (float)Tools::getValue('commission_percent');
            $this->post_fields['currency_commission'] = (int)Tools::getValue('currency_commission');
            $this->post_fields['type_commission'] = (int)Tools::getValue('type_commission');
            $this->post_fields['commission_amount'] = (float)Tools::getValue('commission_amount');
            $this->post_fields['apply_commission'] = (int)Tools::getValue('apply_commission');
            /**
             * Discount
             */
            $this->post_fields['discount_percent'] = (float)Tools::getValue('discount_percent');
            $this->post_fields['currency_discount'] = (int)Tools::getValue('currency_discount');
            $this->post_fields['type_discount'] = (int)Tools::getValue('type_discount');
            $this->post_fields['discount_amount'] = (float)Tools::getValue('discount_amount');
            $this->post_fields['apply_discount'] = (int)Tools::getValue('apply_discount');
            
            $this->post_fields['available_groups'] = Tools::getValue('groupBox');
            $this->post_fields['available_carriers'] = Tools::getValue('available_carriers');
            $this->post_fields['available_currencies'] = Tools::getValue('available_currencies');
            $this->post_fields['available_countries'] = Tools::getValue('available_countries');
            $this->post_fields['commission_use_tax_on_products'] = (int)Tools::getValue(
                'commission_use_tax_on_products'
            );
            $this->post_fields['discount_use_tax_on_products'] = (int)Tools::getValue(
                'discount_use_tax_on_products'
            );
            $this->post_fields['is_send_mail'] = (int)Tools::getValue('is_send_mail');
            $this->post_fields['cart_total_from'] = (float)Tools::getValue('cart_total_from');
            $this->post_fields['cart_total_to'] = (float)Tools::getValue('cart_total_to');
            $this->post_fields['select_currency'] = (float)Tools::getValue('select_currency');
            $this->post_fields['commission_tax'] = (float)Tools::getValue('commission_tax');
            $this->post_fields['commission_switch'] = (float)Tools::getValue('commission_switch');
            $this->post_fields['discount_tax'] = (float)Tools::getValue('discount_tax');
            $this->post_fields['id_cms'] = (int)Tools::getValue('id_cms');
            
            $custom_payment->name = $name;
            $custom_payment->details = $details;
            $custom_payment->description = $description;
            $custom_payment->description_short = $description_short;
            $custom_payment->confirmation_page = Tools::getValue('confirmation_page');
            $custom_payment->confirmation_page_add = Tools::getValue('confirmation_page_add');
            $custom_payment->show_method_available = Tools::getValue('show_method_available');
            $custom_payment->visible_method_available = Tools::getValue('visible_method_available');
            $custom_payment->add_history = Tools::getValue('add_history');
            $custom_payment->active = Tools::getValue('status');
            $custom_payment->id_order_state = (int)Tools::getValue('id_order_state');
            /**
             * Commission
             */
            $custom_payment->commission_percent = (float)Tools::getValue('commission_percent');
            $custom_payment->currency_commission = (int)Tools::getValue('currency_commission');
            $custom_payment->type_commission = (int)Tools::getValue('type_commission');
            $custom_payment->commission_amount = (float)Tools::getValue('commission_amount');
            $custom_payment->apply_commission = (int)Tools::getValue('apply_commission');
            /**
             * Discount
             */
            $custom_payment->discount_percent = (float)Tools::getValue('discount_percent');
            $custom_payment->currency_discount = (int)Tools::getValue('currency_discount');
            $custom_payment->type_discount = (int)Tools::getValue('type_discount');
            $custom_payment->discount_amount = (float)Tools::getValue('discount_amount');
            $custom_payment->apply_discount = (int)Tools::getValue('apply_discount');
            
            $custom_payment->available_groups = Tools::getValue('groupBox');
            $custom_payment->available_carriers = Tools::getValue('available_carriers');
            $custom_payment->available_currencies = Tools::getValue('available_currencies');
            $custom_payment->available_countries = Tools::getValue('available_countries');
            $custom_payment->view_message_field = (int)Tools::getValue('view_message_field');
            $custom_payment->required_message_field = (int)Tools::getValue('required_message_field');
            $custom_payment->name_message_field = $name_message_field;
            $custom_payment->error_message_field = $error_message_field;
            $custom_payment->commission_use_tax_on_products = (int)Tools::getValue('commission_use_tax_on_products');
            $custom_payment->discount_use_tax_on_products = (int)Tools::getValue('discount_use_tax_on_products');
            $custom_payment->is_send_mail = (int)Tools::getValue('is_send_mail');
            $custom_payment->cart_total_from = (float)Tools::getValue('cart_total_from');
            $custom_payment->cart_total_to = (float)Tools::getValue('cart_total_to');
            $custom_payment->select_currency = (int)Tools::getValue('select_currency');
            $custom_payment->commission_tax = (float)Tools::getValue('commission_tax');
            $custom_payment->commission_switch = (float)Tools::getValue('commission_switch');
            $custom_payment->discount_tax = (float)Tools::getValue('discount_tax');
            $custom_payment->id_cms = (int)Tools::getValue('id_cms');
            
            if (!count($this->_errors)) {
                $custom_payment->available_groups = (is_array($custom_payment->available_groups) ? implode(
                    ',',
                    $custom_payment->available_groups
                ) : '');
                $custom_payment->available_carriers = (is_array($custom_payment->available_carriers) ? implode(
                    ',',
                    $custom_payment->available_carriers
                ) : '');
                $custom_payment->available_currencies = (is_array($custom_payment->available_currencies) ? implode(
                    ',',
                    $custom_payment->available_currencies
                ) : '');
                $custom_payment->available_countries = (is_array($custom_payment->available_countries) ? implode(
                    ',',
                    $custom_payment->available_countries
                ) : '');
                $custom_payment->save();
                ImageManager::resize(
                    $_FILES['logo']['tmp_name'],
                    _PS_MODULE_DIR_.$this->name.'/logos/'.$custom_payment->id.'.png',
                    Configuration::get('PS_CPM_ICON_WIDTH'),
                    Configuration::get('PS_CPM_ICON_HEIGHT'),
                    'png',
                    true
                );
                $custom_payment->logo = $custom_payment->id.'.png';
                $custom_payment->setNextPosition();
                $custom_payment->save();

                if (Tools::isSubmit('add_custom_payment_method')) {
                    $method = new SeoSaMockPayment($custom_payment->id);
                    $method->_path = '/modules/custompaymentmethod_'.$custom_payment->id.'/';
                    $method->local_path = _PS_MODULE_DIR_.'custompaymentmethod_'.$custom_payment->id.'/';
                    $method->install();
                }

                Tools::redirectAdmin(
                    'index.php?controller=AdminModules&token='
                    .Tools::getValue('token').'&configure='
                    .$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
                );
            }
        }
        
        if (Tools::getValue('delete_logo')) {
            if (file_exists(_PS_MODULE_DIR_.'custompaymentmethod/logos/'.(int)Tools::getValue('delete_logo').'.png')) {
                unlink(_PS_MODULE_DIR_.'custompaymentmethod/logos/'.(int)Tools::getValue('delete_logo').'.png');
            }
            Tools::redirectAdmin(
                'index.php?controller=AdminModules&token='
                .Tools::getValue('token').'&configure='
                .$this->name.'&tab_module='.$this->tab
                .'&module_name='.$this->name.'&'
                .(Tools::isSubmit('edit_custom_payment_method')
                    ? 'edit_custom_payment_method='.Tools::getValue('edit_custom_payment_method')
                    : 'add_custom_payment_method')
            );
        }
        
        if (Tools::getValue('unset_custom_payment_method')) {
            $custom_payment = new CustomPayment(Tools::getValue('unset_custom_payment_method'));
            if (Validate::isLoadedObject($custom_payment)) {
                $custom_payment->active = 0;
                $custom_payment->save();
                call_user_func_array(
                    array('SeoSaMockPayment', 'unsetMethod'),
                    array($custom_payment, (int)Tools::getValue('edit_custom_payment_method'))
                );
                Tools::redirectAdmin(
                    'index.php?controller=AdminModules&token='
                    .Tools::getValue('token').'&configure='
                    .$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
                );
            }
        }
        if (Tools::getValue('set_custom_payment_method')) {
            $custom_payment = new CustomPayment(Tools::getValue('set_custom_payment_method'));
            if (Validate::isLoadedObject($custom_payment)) {
                $custom_payment->active = 1;
                $custom_payment->save();
                call_user_func_array(
                    array('SeoSaMockPayment', 'setMethod'),
                    array($custom_payment, (int)Tools::getValue('edit_custom_payment_method'))
                );
                Tools::redirectAdmin(
                    'index.php?controller=AdminModules&token='
                    .Tools::getValue('token')
                    .'&configure='.$this->name
                    .'&tab_module='.$this->tab.'&module_name='.$this->name
                );
            }
        }
        if (Tools::getValue('delete_custom_payment_method')) {
            $custom_payment = new CustomPayment(Tools::getValue(
                'delete_custom_payment_method'
            ), $this->context->language->id);
            if (Validate::isLoadedObject($custom_payment)) {
                $method = new SeoSaMockPayment($custom_payment->id);
                $method->_path = '/modules/custompaymentmethod_'.$custom_payment->id.'/';
                $method->local_path = _PS_MODULE_DIR_.'custompaymentmethod_'.$custom_payment->id.'/';
                $method->uninstall();
                $custom_payment->delete();
                Tools::redirectAdmin(
                    'index.php?controller=AdminModules&token='
                    .Tools::getValue('token')
                    .'&configure='.$this->name
                    .'&tab_module='.$this->tab.'&module_name='.$this->name
                );
            }
        }
    }
    
    public $languages;
    
    public function getLanguages()
    {
        if (!is_null($this->languages)) {
            return $this->languages;
        }
        $languages = Language::getLanguages(false);
        foreach ($languages as &$l) {
            $l['is_default'] = (Configuration::get('PS_DEFAULT_LANG') == $l['id_lang']);
        }
        $this->languages = $languages;
        
        return $languages;
    }
    
    public function getContentWrap()
    {
        $this->postProcess();

        $fields = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Payment methods'),
                    ),
                    'input'  => array(
                        array(
                            'label' => 'label',
                            'name'  => 'payment_methods',
                            'type'  => 'payment_methods',
                        ),
                    ),
                ),
            ),
        );
        $payment_methods = CustomPayment::getCustomPaymentMethods();
        $helper_payment_methods = new HelperForm();
        $helper_payment_methods->fields_value = array(
            'payment_methods' => $payment_methods,
        );

        $this->context->smarty->assign('all_shops', !Shop::isFeatureActive() || !Shop::getContextShopID());

        $helper_payment_methods->module = $this;
        $helper_payment_methods->show_toolbar = false;
        $helper_payment_methods->languages = $this->getLanguages();
        $helper_payment_methods->default_form_language = $this->context->language->id;
        $helper_payment_methods->allow_employee_form_lang = 0;
        $helper_payment_methods->override_folder = 'payment_methods/';
        $helper_payment_methods->submit_action = 'none';
        $helper_payment_methods->table = 'payment_methods';
        $helper_payment_methods->tpl_vars['path_img'] = $this->_path.'logos/';
        $helper_payment_methods->tpl_vars['absolute_path'] = _PS_MODULE_DIR_.'custompaymentmethod/logos/';
        $helper_payment_methods->tpl_vars['ps_version'] = _PS_VERSION_;
        $helper_payment_methods->tpl_vars['url_module'] = 'index.php?controller=AdminModules&token='
            .Tools::getValue('token')
            .'&configure='.$this->name
            .'&tab_module='.$this->tab
            .'&module_name='.$this->name;

        if (!Tools::isSubmit('add_custom_payment_method')) {
            $this->html .= '<a class="button btn btn-success btn-add-payment-methods" href="index.php?controller=AdminModules&token='
                .Tools::getValue('token').'&configure='.$this->name
                .'&tab_module='.$this->tab.'&module_name='.$this->name.'&add_custom_payment_method">'.$this->l(
                    'Add payment methods'
                ).'</a><br>';
        }
        $this->html.= '<link rel="stylesheet" href="'.$this->_path.'views/css/admin.css">';
        if (version_compare(_PS_VERSION_, '1.7.8.0', '>=')) {
            $this->html.= '<link rel="stylesheet" href="'.$this->_path.'views/css/admin178.css">';
        }
        $this->html.= $helper_payment_methods->generateForm($fields);
        $fields = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Customize module'),
                    ),
                    'input'  => array(
                        array(
                            'label'   => $this->l('Default payment module'),
                            'name'    => 'PS_CPM_DEFAULT_METHOD',
                            'type'    => 'select',
                            'options' => array(
                                'query' => CustomPayment::getCustomPaymentMethods(),
                                'id'    => 'id_custom_payment_method',
                                'name'  => 'name',
                            ),
                        ),
                        array(
                            'label' => $this->l('Width logo payment module'),
                            'name'  => 'PS_CPM_ICON_WIDTH',
                            'type'  => 'text',
                        ),
                        array(
                            'label' => $this->l('Height logo payment module'),
                            'name'  => 'PS_CPM_ICON_HEIGHT',
                            'type'  => 'text',
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                ),
            ),
        );
        $helper_customize = new HelperForm();
        $helper_customize->module = $this;
        $helper_customize->override_folder = 'customize_module/';
        $helper_customize->fields_value = array(
            'PS_CPM_DEFAULT_METHOD' => Configuration::get('PS_CPM_DEFAULT_METHOD'),
            'PS_CPM_ICON_WIDTH'     => Configuration::get('PS_CPM_ICON_WIDTH'),
            'PS_CPM_ICON_HEIGHT'    => Configuration::get('PS_CPM_ICON_HEIGHT'),
        );
        $helper_customize->token = Tools::getValue('token');
        $helper_customize->toolbar_btn = array(
            'save' => array(
                'title' => $this->l('Save'),
            ),
        );
        $helper_customize->submit_action = 'saveConf';
        $helper_customize->currentIndex = $_SERVER['REQUEST_URI'];
        $helper_customize->languages = $this->getLanguages();
        $helper_customize->default_form_language = $this->context->language->id;
        $helper_customize->allow_employee_form_lang = 0;
        $form_customize = $helper_customize->generateForm($fields);
        $this->html .= $form_customize;

        $this->context->controller->addJqueryUI('ui.sortable');
        if (Tools::getValue('edit_custom_payment_method') || Tools::isSubmit('add_custom_payment_method')) {
            $this->context->controller->addCSS($this->getPathUri().'views/css/ion.rangeSlider.css');
            $this->context->controller->addCSS($this->getPathUri().'views/css/ion.rangeSlider.skinFlat.css');
            $this->context->controller->addCSS($this->getPathUri().'views/css/normalize.css');
            $this->context->controller->addJS($this->getPathUri().'views/js/ion.rangeSlider.js');
            $this->context->controller->addJS($this->getPathUri().'views/js/admin.js');
            
            $id_custom_payment = (Tools::getValue('edit_custom_payment_method')
                ? Tools::getValue('edit_custom_payment_method')
                : null);
            
            $custom_payment = new CustomPayment($id_custom_payment);
            $custom_payment->available_groups = ($custom_payment->available_groups ? explode(
                ',',
                $custom_payment->available_groups
            ) : []);
            $custom_payment->available_carriers = ($custom_payment->available_carriers ? explode(
                ',',
                $custom_payment->available_carriers
            ) : []);
            $custom_payment->available_currencies = ($custom_payment->available_currencies ? explode(
                ',',
                $custom_payment->available_currencies
            ) : []);
            $custom_payment->available_countries = ($custom_payment->available_countries ? explode(
                ',',
                $custom_payment->available_countries
            ) : []);
            $unidentified = new Group(Configuration::get('PS_UNIDENTIFIED_GROUP'));
            $guest = new Group(Configuration::get('PS_GUEST_GROUP'));
            $default = new Group(Configuration::get('PS_CUSTOMER_GROUP'));
            $unidentified_group_information = sprintf(
                $this->l('%s - All people without a valid customer account.'),
                '<b>'.$unidentified->name[$this->context->language->id].'</b>'
            );
            $guest_group_information = sprintf(
                $this->l('%s - Customer who placed an order with the guest checkout.'),
                '<b>'.$guest->name[$this->context->language->id].'</b>'
            );
            $default_group_information = sprintf(
                $this->l('%s - All people who have created an account on this site.'),
                '<b>'.$default->name[$this->context->language->id].'</b>'
            );
            
            $cms_pages = CMS::listCms($this->context->language->id);
            $first_li = array(array('id_cms' => '0', 'meta_title' => '-'));
            if (is_array($cms_pages)) {
                $cms_pages = array_merge($first_li, $cms_pages);
            } else {
                $cms_pages = $first_li;
            }

            $fields = array(
                array(
                    'form' => array(
                        'tinymce' => true,
                        'legend'  => array(
                            'title' => $this->l('Edit payment method'),
                        ),
                        'input'   => array(
                            array(
                                'label'     => $this->l('Name'),
                                'name'      => 'name',
                                'lang'      => true,
                                'type'      => 'text',
                                'required'  => true,
                                'maxchar'   => 40,
                                'maxlength' => 40,
                            ),
                            array(
                                'label'      => $this->l('Logo'),
                                'name'       => 'logo',
                                'type'       => 'file',
                                'image'      => (file_exists(
                                    _PS_MODULE_DIR_.'custompaymentmethod/logos/'.$id_custom_payment.'.png'
                                ) ?
                                    '<img src="'._MODULE_DIR_.'custompaymentmethod/logos/'.$id_custom_payment.'.png">'
                                    : ''),
                                'delete_url' => $_SERVER['REQUEST_URI'].'&delete_logo='.$id_custom_payment,
                            ),
                            array(
                                'label'        => $this->l('Details'),
                                'name'         => 'details',
                                'type'         => 'textarea',
                                'lang'         => true,
                                'class'        => 'rte',
                                'autoload_rte' => true,
                            ),
                            array(
                                'label'     => $this->l('Description short'),
                                'name'      => 'description_short',
                                'type'         => 'textarea',
                                'lang'      => true,
                                'class'     => 'rte',
                                'autoload_rte' => true,
//                                'maxchar'   => 300,
//                                'maxlength' => 300,
                            ),
                            array(
                                'label'        => $this->l('Description'),
                                'name'         => 'description',
                                'type'         => 'textarea',
                                'lang'         => true,
                                'class'        => 'rte',
                                'autoload_rte' => true,
                            ),
                            array(
                                'label' => $this->l('Show, when cart total from'),
                                'name'  => 'cart_total_from',
                                'type'  => 'text',
                            ),
                            array(
                                'label' => $this->l('to'),
                                'name'  => 'cart_total_to',
                                'type'  => 'text',
                            ),
                            array(
                                'label' => $this->l('Select currency'),
                                'name'  => 'select_currency',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => Currency::getCurrencies(false, true),
                                    'id'    => 'id_currency',
                                    'name'  => 'name',
                                ),
                            ),


                            array(
                                'type'              => 'group',
                                'label'             => $this->l('Group access'),
                                'name'              => 'groupBox',
                                'values'            => Group::getGroups(Context::getContext()->language->id),
                                'info_introduction' => $this->l('You now have three default customer groups.'),
                                'unidentified'      => $unidentified_group_information,
                                'guest'             => $guest_group_information,
                                'customer'          => $default_group_information,
                                'hint'              => $this->l(
                                    'Mark all customer groups to whom you want to allow access to this category.'
                                ),
                            ),
                            array(
                                'type'   => 'carrier_group',
                                'label'  => $this->l('Carrier access'),
                                'name'   => 'available_carriers',
                                'values' => Carrier::getCarriers(
                                    Context::getContext()->language->id,
                                    false,
                                    false,
                                    false,
                                    null,
                                    Carrier::ALL_CARRIERS
                                ),
                            ),
                            array(
                                'type'   => 'currency_group',
                                'label'  => $this->l('Currency access'),
                                'name'   => 'available_currencies',
                                'values' => Currency::getCurrencies(),
                            ),
                            array(
                                'type'   => 'country_group',
                                'label'  => $this->l('Country access'),
                                'name'   => 'available_countries',
                                'values' => Country::getCountries($this->context->language->id, true),
                            ),
                            array(
                                'label'   => $this->l('View message field'),
                                'name'    => 'view_message_field',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'view_message_field_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'view_message_field_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => true,
                            ),
                            array(
                                'label'   => $this->l('Required message field'),
                                'name'    => 'required_message_field',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'required_message_field_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'required_message_field_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => true,
                            ),
                            array(
                                'label' => $this->l('Name message field'),
                                'name'  => 'name_message_field',
                                'lang'  => true,
                                'type'  => 'text',
                            ),

                            array(
                                'label' => $this->l('Error message field'),
                                'name'  => 'error_message_field',
                                'lang'  => true,
                                'type'  => 'text',
                                'hint'  => $this->l('Enter the message text that appears if the field is empty.'),
                            ),

                            array(
                                'label'   => $this->l('Content from cms'),
                                'name'    => 'id_cms',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => $cms_pages,
                                    'id'    => 'id_cms',
                                    'name'  => 'meta_title',
                                ),
                            ),
                            array(
                                'label'   => $this->l('Confirmation page'),
                                'name'    => 'confirmation_page',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'confirmation_page_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'confirmation_page_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),

                            array(
                                'label'   => $this->l('Send mail'),
                                'name'    => 'is_send_mail',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'is_send_mail_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'is_send_mail_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),
                            array(
                                'label'   => $this->l('Add Confirmation page'),
                                'name'    => 'confirmation_page_add',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'confirmation_page_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'confirmation_page_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),
                            array(
                                'label'   => $this->l('Show method if not available'),
                                'name'    => 'show_method_available',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'confirmation_page_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'confirmation_page_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),
                            array(
                                'label'   => $this->l('Visible warning'),
                                'name'    => 'visible_method_available',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'confirmation_page_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'confirmation_page_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),
                            array(
                                'label'   => $this->l('Add details to order history page'),
                                'name'    => 'add_history',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'add_history_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'add_history_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),

                            array(
                                'label'   => $this->l('Select order state'),
                                'name'    => 'id_order_state',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => array_merge(
                                        array(array('id_order_state' => 0, 'name' => $this->l('No selected'))),
                                        OrderState::getOrderStates($this->context->language->id)
                                    ),
                                    'id'    => 'id_order_state',
                                    'name'  => 'name',
                                ),
                            ),


                            array(
                                'label'   => $this->l('Active'),
                                'name'    => 'status',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'status_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'status_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),
                            array(
                                'label'   => $this->l('Apply commission'),
                                'name'    => 'apply_commission',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => array(
                                        array(
                                            'id'   => CustomPayment::APPLY_COMMISSION_TOTAL,
                                            'name' => $this->l('Total products + Total shipping'),
                                        ),
                                        array(
                                            'id'   => CustomPayment::APPLY_COMMISSION_PRODUCTS,
                                            'name' => $this->l('Total product'),
                                        ),
                                    ),
                                    'id'    => 'id',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label'   => $this->l('Commission use tax on products'),
                                'name'    => 'commission_use_tax_on_products',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'commission_use_tax_on_products_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'commission_use_tax_on_products_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),

                            array(
                                'label'   => $this->l('Type commission'),
                                'name'    => 'type_commission',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => array(
                                        array(
                                            'id'   => CustomPayment::TYPE_COMMISSION_NONE,
                                            'name' => $this->l('None'),
                                        ),
                                        array(
                                            'id'   => CustomPayment::TYPE_COMMISSION_PERCENT,
                                            'name' => $this->l('Percent'),
                                        ),
                                        array(
                                            'id'   => CustomPayment::TYPE_COMMISSION_AMOUNT,
                                            'name' => $this->l('Amount'),
                                        ),
                                    ),
                                    'id'    => 'id',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label'   => $this->l('Currency commission'),
                                'name'    => 'currency_commission',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => Currency::getCurrencies(false, true),
                                    'id'    => 'id_currency',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label' => $this->l('Value commission'),
                                'name'  => 'commission_percent',
                                'type'  => 'text',
                            ),
                            array(
                                'label' => $this->l('Value commission'),
                                'name'  => 'commission_amount',
                                'type'  => 'text',
                            ),
                            array(
                                'label' => $this->l('Commission tax'),
                                'name'  => 'commission_tax',
                                'type'  => 'text',
                            ),
                            array(
                                'label'   => $this->l('Show'),
                                'name'    => 'commission_switch',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => array(
                                        array(
                                            'value' => 1,
                                            'name' => $this->l('Percent'),
                                        ),
                                        array(
                                            'value' => 0,
                                            'name' => $this->l('Amount'),
                                        ),
                                    ),
                                    'id'    => 'id',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label'   => $this->l('Apply discount'),
                                'name'    => 'apply_discount',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => array(
                                        array(
                                            'id'   => CustomPayment::APPLY_COMMISSION_TOTAL,
                                            'name' => $this->l('Total products + Total shipping'),
                                        ),
                                        array(
                                            'id'   => CustomPayment::APPLY_COMMISSION_PRODUCTS,
                                            'name' => $this->l('Total product'),
                                        ),
                                    ),
                                    'id'    => 'id',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label'   => $this->l('Discount use tax on products'),
                                'name'    => 'discount_use_tax_on_products',
                                'type'    => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
                                'class'   => 't',
                                'values'  => array(
                                    array(
                                        'id'    => 'discount_use_tax_on_products_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id'    => 'discount_use_tax_on_products_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                                'is_bool' => false,
                            ),
                            array(
                                'label'   => $this->l('Type discount'),
                                'name'    => 'type_discount',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => array(
                                        array(
                                            'id'   => CustomPayment::TYPE_COMMISSION_NONE,
                                            'name' => $this->l('None'),
                                        ),
                                        array(
                                            'id'   => CustomPayment::TYPE_COMMISSION_PERCENT,
                                            'name' => $this->l('Percent'),
                                        ),
                                        array(
                                            'id'   => CustomPayment::TYPE_COMMISSION_AMOUNT,
                                            'name' => $this->l('Amount'),
                                        ),
                                    ),
                                    'id'    => 'id',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label'   => $this->l('Currency discount'),
                                'name'    => 'currency_discount',
                                'type'    => 'select',
                                'options' => array(
                                    'query' => Currency::getCurrencies(false, true),
                                    'id'    => 'id_currency',
                                    'name'  => 'name',
                                ),
                            ),
                            array(
                                'label' => $this->l('Value discount'),
                                'name'  => 'discount_percent',
                                'type'  => 'text',
                            ),
                            array(
                                'label' => $this->l('Value discount'),
                                'name'  => 'discount_amount',
                                'type'  => 'text',
                            ),
                            array(
                                'label' => $this->l('Discount tax'),
                                'name'  => 'discount_tax',
                                'type'  => 'text',
                            ),

                        ),
                        'submit'  => array(
                            'title' => $this->l('Save'),
                        ),
                    ),
                ),
            );

            $helper_add_payment = new HelperForm();
            $helper_add_payment->fields_value = array(
                'name'                           => (isset($this->post_fields['name']) ? $this->post_fields['name']
                    : $custom_payment->name),
                'details'                       => (isset($this->post_fields['details']) ? $this->post_fields['details']
                    : $custom_payment->details),
                'description'                    => (isset($this->post_fields['description'])
                    ? $this->post_fields['description'] : $custom_payment->description),
                'description_short'              => (isset($this->post_fields['description_short']) ?
                    $this->post_fields['description_short'] : $custom_payment->description_short),
                'confirmation_page'              => (isset($this->post_fields['confirmation_page'])
                    ? $this->post_fields['confirmation_page']
                    : $custom_payment->confirmation_page),
                'confirmation_page_add'              => (isset($this->post_fields['confirmation_page_add'])
                    ? $this->post_fields['confirmation_page_add']
                    : $custom_payment->confirmation_page_add),
                'show_method_available'              => (isset($this->post_fields['show_method_available'])
                    ? $this->post_fields['show_method_available']
                    : $custom_payment->show_method_available),
                'visible_method_available'              => (isset($this->post_fields['visible_method_available'])
                    ? $this->post_fields['visible_method_available']
                    : $custom_payment->visible_method_available),
                'add_history' => (isset($this->post_fields['add_history']) ? $this->post_fields['add_history']
                    : $custom_payment->add_history),
                'status'                         => (isset($this->post_fields['status']) ? $this->post_fields['status']
                    : $custom_payment->active),
                'view_message_field'             => (isset($this->post_fields['view_message_field']) ?
                    $this->post_fields['view_message_field'] : $custom_payment->view_message_field),
                'required_message_field'         => (isset($this->post_fields['required_message_field']) ?
                    $this->post_fields['required_message_field'] : $custom_payment->required_message_field),
                'name_message_field'             => (isset($this->post_fields['name_message_field']) ?
                    $this->post_fields['name_message_field'] : $custom_payment->name_message_field),
                'error_message_field'            => (isset($this->post_fields['error_message_field']) ?
                    $this->post_fields['error_message_field'] : $custom_payment->error_message_field),
                'id_order_state'                 => (isset($this->post_fields['id_order_state'])
                    ? $this->post_fields['id_order_state'] : $custom_payment->id_order_state),
                'type_commission'                => (isset($this->post_fields['type_commission'])
                    ? $this->post_fields['type_commission'] : $custom_payment->type_commission),
                'commission_percent'             => (isset($this->post_fields['commission_percent'])
                    ? $this->post_fields['commission_percent']
                    :
                    $custom_payment->commission_percent),
                'currency_commission'            => (isset($this->post_fields['currency_commission'])
                    ? $this->post_fields['currency_commission']
                    :
                    $custom_payment->currency_commission),
                'commission_amount'              => (isset($this->post_fields['commission_amount'])
                    ? $this->post_fields['commission_amount']
                    :
                    $custom_payment->commission_amount),
                'apply_commission'               => (isset($this->post_fields['apply_commission'])
                    ? $this->post_fields['apply_commission']
                    :
                    $custom_payment->apply_commission),
                'type_discount'                  => (isset($this->post_fields['type_discount'])
                    ? $this->post_fields['type_discount'] : $custom_payment->type_discount),
                'discount_percent'               => (isset($this->post_fields['discount_percent'])
                    ? $this->post_fields['discount_percent']
                    :
                    $custom_payment->discount_percent),
                'currency_discount'              => (isset($this->post_fields['currency_discount'])
                    ? $this->post_fields['currency_discount']
                    :
                    $custom_payment->currency_discount),
                'discount_amount'                => (isset($this->post_fields['discount_amount'])
                    ? $this->post_fields['discount_amount']
                    :
                    $custom_payment->discount_amount),
                'apply_discount'                 => (isset($this->post_fields['apply_discount'])
                    ? $this->post_fields['apply_discount']
                    :
                    $custom_payment->apply_discount),
                'available_groups'               => (isset($this->post_fields['available_groups'])
                    ? $this->post_fields['available_groups']
                    :
                    $custom_payment->available_groups),
                'available_carriers'             => (isset($this->post_fields['available_carriers'])
                    ? $this->post_fields['available_carriers']
                    :
                    $custom_payment->available_carriers),
                'available_currencies'           => (isset($this->post_fields['available_currencies'])
                    ? $this->post_fields['available_currencies']
                    :
                    $custom_payment->available_currencies),
                'available_countries'           => (isset($this->post_fields['available_countries'])
                    ? $this->post_fields['available_countries']
                    :
                    $custom_payment->available_countries),
                'commission_use_tax_on_products' => (isset($this->post_fields['commission_use_tax_on_products'])
                    ?
                    $this->post_fields['commission_use_tax_on_products']
                    :
                    $custom_payment->commission_use_tax_on_products),
                'discount_use_tax_on_products'   => (isset($this->post_fields['discount_use_tax_on_products'])
                    ?
                    $this->post_fields['discount_use_tax_on_products']
                    :
                    $custom_payment->discount_use_tax_on_products),
                'is_send_mail'                   => (isset($this->post_fields['is_send_mail'])
                    ? (int)$this->post_fields['is_send_mail']
                    : $custom_payment->is_send_mail),
                'cart_total_from'                => (isset($this->post_fields['cart_total_from'])
                    ? (int)$this->post_fields['cart_total_from']
                    : $custom_payment->cart_total_from),
                'select_currency'                => (isset($this->post_fields['select_currency'])
                    ? (int)$this->post_fields['select_currency']
                    : $custom_payment->select_currency),
                'cart_total_to'                  => (isset($this->post_fields['cart_total_to'])
                    ? (int)$this->post_fields['cart_total_to']
                    : $custom_payment->cart_total_to),
                'commission_tax'                 => (isset($this->post_fields['commission_tax'])
                    ? (float)$this->post_fields['commission_tax']
                    : $custom_payment->commission_tax),
                'commission_switch'              => (isset($this->post_fields['commission_switch'])
                    ? (float)$this->post_fields['commission_switch']
                    : $custom_payment->commission_switch),
                'discount_tax'                   => (isset($this->post_fields['discount_tax'])
                    ? (float)$this->post_fields['discount_tax']
                    : $custom_payment->discount_tax),
                'id_cms'                         => (isset($this->post_fields['id_cms'])
                    ? $this->post_fields['id_cms']
                    :
                    $custom_payment->id_cms),
            );

            foreach (Group::getGroups(Context::getContext()->language->id) as $group) {
                $helper_add_payment->fields_value['groupBox_'.$group['id_group']] = (is_array(
                    $helper_add_payment->fields_value['available_groups']
                )
                && in_array($group['id_group'], $helper_add_payment->fields_value['available_groups']) ? 1 : 0);
            }
            $helper_add_payment->table = 'add_payment_method';
            $helper_add_payment->languages = Language::getLanguages(false);
            $helper_add_payment->default_form_language = $this->context->language->id;
            $languages = Language::getLanguages(false);
            foreach ($languages as &$language) {
                $language['is_default'] = ($language['id_lang'] == $helper_add_payment->default_form_language);
            }
            $helper_add_payment->languages = $languages;
            $helper_add_payment->token = Tools::getValue('token');
            $helper_add_payment->module = $this;

            if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
                $helper_add_payment->override_folder = 'form_add_payment/';
            } else {
                $helper_add_payment->override_folder = 'form_add_payment15/';
            }

            $helper_add_payment->toolbar_btn = array(
                'save' => array(
                    'title' => $this->l('Save'),
                ),
            );
            $helper_add_payment->submit_action = 'saveCustomPaymentMethod';
            $helper_add_payment->currentIndex = $_SERVER['REQUEST_URI'];
            $form_add_payment = '<link rel="stylesheet" href="'.$this->_path.'views/css/admin.css" >';
            if (version_compare(_PS_VERSION_, '1.7.8.0', '>=')) {
                $form_add_payment = $form_add_payment.'<link rel="stylesheet" href="'.$this->_path.'views/css/admin178.css" >';
            }
            $form_add_payment = $form_add_payment.$helper_add_payment->generateForm($fields);
            if (Tools::isSubmit('add_custom_payment_method')
                || Tools::isSubmit('edit_custom_payment_method')) {
                $this->html = '<a class="button btn btn-default btn-return-back" 
                href="index.php?controller=AdminModules&token='.Tools::getValue('token')
                    .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='
                    .$this->name.'">'.$this->l('Return back').'</a>';
            }
            
            $this->html
                .= '<script>

				$(function () {
					$("[name=type_commission]").live("change", function () {
						var type_c = $("[name=type_commission]").val();
						if (type_c == 1)
						{
							$("[name=commission_percent]").closest(".js-switch").show();

							$("[name=commission_amount]").closest(".js-switch").hide();
							$("[name=commission_amount]").val(0);

							$("[name=currency_commission]").closest(".js-switch").hide();
						}
						else if (type_c == 2)
						{
							$("[name=commission_percent]").closest(".js-switch").hide();
							$("[name=commission_percent]").val(0);
							$("[name=commission_amount]").closest(".js-switch").show();
							$("[name=currency_commission]").closest(".js-switch").show();
						}
						else if (type_c == 0)
						{
							$("[name=commission_percent]").val(0);
							$("[name=currency_commission], [name=commission_percent], [name=commission_amount]")
							.closest(".js-switch").hide();
						}
					});
					$("[name=type_commission]").trigger("change");
				});
				
				$(function () {
					$("[name=type_discount]").live("change", function () {
						var type_c = $("[name=type_discount]").val();
						if (type_c == 1)
						{
							$("[name=discount_percent]").closest(".js-switch").show();
							$("[name=discount_amount]").closest(".js-switch").hide();
							$("[name=discount_amount]").val(0);
							$("[name=currency_discount]").closest(".js-switch").hide();
						}
						else if (type_c == 2)
						{
							$("[name=discount_percent]").closest(".js-switch").hide();
							$("[name=discount_percent]").val(0);
							$("[name=discount_amount]").closest(".js-switch").show();
							$("[name=currency_discount]").closest(".js-switch").show();
						}
						else if (type_c == 0)
						{
							$("[name=discount_percent]").val(0);
							$("[name=currency_discount], [name=discount_percent], [name=discount_amount]")
							.closest(".js-switch").hide();
						}
					});
					$("[name=type_discount]").trigger("change");
					
				});
				
			    $(function () {
					$("[name=show_method_available]").on("change", function () {
						if ($(this).val() == 1)
						{
						    $("[name=visible_method_available]").closest(".js-switch").show();
						}
						else {
					        $("[name=visible_method_available]").closest(".js-switch").hide();
						}
					});

				    if ($("[name=show_method_available]").prop("checked")) {
						 $("[name=visible_method_available]").closest(".js-switch").show();
					} else { 
					     $("[name=visible_method_available]").closest(".js-switch").hide();
					}
		        });

			</script>';
            
            if (count($this->_errors)) {
                $this->html
                    .= '<div class="alert alert-danger error">
					'.implode('<br>', $this->_errors).'
			</div>';
            }
            
            $this->html .= $form_add_payment;
        }
        
        return $this->html;
    }

    public function hookPayment()
    {
        if ($this->name == 'custompaymentmethod') {
            return '';
        }

        if (!$this->active) {
            return '';
        }

        if ($this->checkAvailability() !== true) {
            return '';
        }

        ToolsModuleCMP::registerSmartyFunctions();

        if ($id_pcm = Tools::getValue('id_cpm')) {
            return '<script>
				window.location = "'.
                $this->context->link->getModuleLink(
                    $this->name,
                    'validation',
                    array('type' => $id_pcm)
                )
                .'";
			</script>';
        }

        $this->context->smarty->assign(
            array(
                'logos_path' => $this->_path.'logos/',
            )
        );

        if (!file_exists(_PS_MODULE_DIR_.'custompaymentmethod/logos/'.$this->custom_payment->logo)) {
            $this->custom_payment->logo = 0;
        }

        $this->context->smarty->assign(
            array(
                'ps_version'        => _PS_VERSION_,
                'payment'           => $this->custom_payment,
                'commission'        => CustomPaymentMethod::getCommission(
                    $this->context->cart,
                    $this->custom_payment,
                    $this->context
                ),
                'commision_percent' => $this->custom_payment->commission_percent,
                'commission_switch' => $this->custom_payment->commission_switch,
                'discount'          => CustomPaymentMethod::getDiscount(
                    $this->context->cart,
                    $this->custom_payment,
                    $this->context
                ),
                'order_total'       => CustomPaymentMethod::getOrderTotal(
                    $this->context->cart,
                    $this->custom_payment,
                    $this->context
                ),
            )
        );

        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'custompaymentmethod/views/templates/hook/payment.tpl');
    }

    public function hookPaymentOptions()
    {
        ToolsModuleCMP::registerSmartyFunctions();
        if ($this->name == 'custompaymentmethod') {
            return '';
        }
        if (!class_exists('PrestaShop\PrestaShop\Core\Payment\PaymentOption')) {
            return '';
        }
        if (!$this->active) {
            return '';
        }
        if ($this->checkAvailability() !== true) {
            return '';
        }

        $id_custom_payment = explode('_', $this->name);

        if (is_numeric($id_custom_payment[1])) {
            $this->custom_payment = new CustomPayment($id_custom_payment[1]);
        } else {
            return '';
        }

        if (!file_exists(_PS_MODULE_DIR_.'custompaymentmethod/logos/'.$this->custom_payment->logo)) {
            $this->custom_payment->logo = 0;
        }

        $commission_summary = CustomPaymentMethod::calculateCommission(
            $this->context->cart,
            $this->custom_payment,
            $this->context
        );

        $commission = $commission_summary['commission'];
        $commission_wt = $commission_summary['commission_tax_excl'];
        $discount = $commission_summary['discount'];
        $discount_wt = $commission_summary['discount_tax_excl'];


        $order_total = CustomPaymentMethod::getOrderTotal(
            $this->context->cart,
            $this->custom_payment,
            $this->context
        );

        $real_total = $this->context->cart->getOrderTotal();
        $real_total_wt = $this->context->cart->getOrderTotal(false);

        Media::addJsDef(array(
            'real_total' => Tools::displayPrice($real_total),
            'real_total_wt' => Tools::displayPrice($real_total_wt),
            'real_tax' => Tools::displayPrice($real_total - $real_total_wt)
        ));

        $total = $real_total + $commission + $discount;
        $total_wt = $real_total_wt + $commission_wt + $discount_wt;

        $currency_to = new Currency(Context::getContext()->cart->id_currency);
        $currency_from = new Currency($this->custom_payment->select_currency);
        if ($currency_to != $currency_from) {
            $from = Tools::convertPriceFull($this->custom_payment->cart_total_from, $currency_from, $currency_to);
            $to = Tools::convertPriceFull($this->custom_payment->cart_total_to, $currency_from, $currency_to);
            $this->custom_payment->cart_total_from = $from;
            $this->custom_payment->cart_total_to = $to;
        }

        if (!$this->custom_payment->show_method_available) {
            if ($this->custom_payment->cart_total_from > $order_total
                && $this->custom_payment->cart_total_from != 0) {
                return "";
            }
            if ($this->custom_payment->cart_total_to <  $order_total
                && $this->custom_payment->cart_total_to != 0) {
                return "";
            }
        }

        $this->context->smarty->assign(
            array(
                'payment'            => $this->custom_payment,
                'commission'         => $commission,
                'discount'           => $discount,
                'order_total'        => $order_total,
                'format_commission'  => Tools::displayPrice($commission),
                'format_discount'    => Tools::displayPrice($discount),
                'format_order_total' => Tools::displayPrice($order_total),
                'lang' => $this->context->language->id,
                'name' => $this->name,
                'total' => Tools::displayPrice($total),
                'total_wt' => Tools::displayPrice($total_wt),
                'commission_tax' => Tools::displayPrice($total - $total_wt),
                'total_clear' => $total,
                'cart_total_from' => $this->custom_payment->cart_total_from,
                'cart_total_to' => $this->custom_payment->cart_total_to,
                'cart_total_from_display' => Tools::displayPrice($this->custom_payment->cart_total_from),
                'cart_total_to_display' => Tools::displayPrice($this->custom_payment->cart_total_to),
            )
        );

        $newOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $additional_information = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.'custompaymentmethod/views/templates/hook/payment_intro.tpl'
        );

        $newOption->setCallToActionText($this->custom_payment->name[$this->context->language->id])
            ->setModuleName($this->name)
            ->setAction($this->context->link->getModuleLink(
                'custompaymentmethod',
                'validation',
                array('type' => $this->custom_payment->id),
                true
            ))
            ->setAdditionalInformation($additional_information);

        if ($this->custom_payment->logo) {
            $newOption->setLogo($this->_path.'logos/'.$this->custom_payment->logo);
        }

        return array($newOption);
    }

    public function hookDisplayPaymentReturn($params)
    {
        return '';

        if (!$this->active) {
            return '';
        }

        $type = (int)Tools::getValue('type');
        $custom_payment = new CustomPayment($type, $this->context->language->id);
        if ($custom_payment->type_commission == CustomPayment::TYPE_COMMISSION_AMOUNT) {
            $custom_payment->commission_amount = Tools::convertPriceFull(
                $custom_payment->commission_amount,
                null,
                $this->context->currency
            );
        }

        if (version_compare(_PS_VERSION_, '1.6.0.0', '<')) {
            $idLang = $this->context->language->id;
            $idCms = $custom_payment->id_cms;
            if (null === $this->context->language->id) {
                $idLang = (int) Configuration::get('PS_LANG_DEFAULT');
            }
            $sql = '
			SELECT `content`
			FROM `' . _DB_PREFIX_ . 'cms_lang`
			WHERE `id_cms` = ' . (int) $idCms . ' AND `id_lang` = ' . (int) $idLang;
            $cms_content =  Db::getInstance()->getRow($sql);
        } else {
            $cms_content = $custom_payment->id_cms
                ? CMS::getCMSContent($custom_payment->id_cms, $this->context->language->id)
                : array('content' => null);
        }

        /**
         * @var Order $order
         */
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $order = $params['order'];
        } else {
            $order = $params['objOrder'];
        }

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

        $confirmation_page_add = false;
        if ($this->custom_payment->confirmation_page_add == 1) {
            $confirmation_page_add = true;
        }

        $show_method_available = false;
        $visible_method_available = false;
        $this->context->smarty->assign(
            array(
                'custom_payment' => $custom_payment,
                'status'         => 'ok',
                'id_order'       => $order->id,
                'total_to_pay'   => Tools::displayPrice($order->getOrdersTotalPaid()),
                'total'          => $order->getOrdersTotalPaid(),
                'reference'      => $this->context->controller->reference,
                'cms_content'    => $cms_content['content'],
                'details' => $this->custom_payment->details,
                'description' => $this->custom_payment->description,
                'ps_message_field'     => $message,
                'confirmation_page_add'     => $confirmation_page_add,
                'show_method_available'     => $show_method_available,
                'visible_method_available'     => $visible_method_available,
            )
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.'custompaymentmethod/views/templates/hook/payment_return.tpl'
        );
    }

    public function hookDisplayAdminOrder($params)
    {
        $id_order = $params['id_order'];
        $order = new Order($id_order);
        $commission = CustomPayment::getOrderCommission($id_order);
        $commission_total = isset($commission['commission']) ? $commission['commission'] : 0;
        $discount = CustomPayment::getOrderDiscount($id_order);
        $discount_total = isset($discount['discount']) ? $discount['discount'] : 0;
        $this->context->smarty->assign(
            array(
                'commission'        => (float)$commission_total,
                'format_commission' => Tools::displayPrice((float)$commission_total, new Currency($order->id_currency)),
                'discount'          => (float)$discount_total,
                'format_discount'   => Tools::displayPrice((float)$discount_total, new Currency($order->id_currency)),
                'order_obj'         => $order,
            )
        );

        return $this->display(__FILE__, 'admin_order.tpl');
    }

    public function hookDisplayOrderConfirmationCommission($params)
    {
        $order = new Order($params['order']['id']);
        if ($order instanceof Order && Validate::isLoadedObject($order)) {
            $commission = CustomPayment::getOrderCommission($order->id);
            $commission_total = $commission['commission'];
            $this->context->smarty->assign(
                array(
                    'commission'        => (float)$commission_total,
                    'format_commission' => Tools::displayPrice(
                        (float)$commission_total,
                        new Currency($order->id_currency)
                    ),
                    'order_obj'         => $order,
                    'controller'        => get_class($this->context->controller),
                )
            );

            return $this->display(__FILE__, 'payment_return_17.tpl');
        }
    }

    public function hookDisplayOrderDetail($params)
    {

        $order = $params['order'];
        if ($order instanceof Order && Validate::isLoadedObject($order)) {
            $commission = CustomPayment::getOrderCommission($order->id);
            if (empty($commission)) {
                return '';
            }
            $commission_total = $commission['commission'];
            $discount = CustomPayment::getOrderDiscount($order->id);
            $discount_total = $discount['discount'];

            if (!$commission_total && !$discount_total) {
                return '';
            }

            if (strpos($order->module, 'custompaymentmethod_') !== false) {
                $payment_name_exp = explode('_', $order->module);

                if (!isset($payment_name_exp[1])) {
                    $result = '';
                } else {
                    $result = Db::getInstance()->getRow('SELECT cl.`details`, cl.`description`, cl.`description_short`, c.`add_history` 
                FROM `' . _DB_PREFIX_ . 'custom_payment_method_lang` cl
                LEFT JOIN `' . _DB_PREFIX_ . 'custom_payment_method` c ON c.`id_custom_payment_method` = cl.`id_custom_payment_method`
                WHERE cl.`id_custom_payment_method` = ' . (int)$payment_name_exp[1] . '
                 AND cl.`id_lang` = ' . $order->id_lang);
                }
                if ($result['add_history'] == 0) {
                    $result = '';
                }
            }

            $this->context->smarty->assign(
                array(
                    'commission'        => (float)$commission_total,
                    'format_commission' => Tools::displayPrice(
                        (float)$commission_total,
                        new Currency($order->id_currency)
                    ),
                    'discount'          => $discount_total,
                    'format_discount'   => Tools::displayPrice($discount_total, new Currency($order->id_currency)),
                    'order_obj'         => $order,
                    'result' => $result
                )
            );

            if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                return $this->display(__FILE__, 'order_detail_17.tpl');
            } else {
                return $this->display(__FILE__, 'order_detail.tpl');
            }
        }
    }

    public function hookDisplayCommissionForPDF($params)
    {
        $id_order = Tools::getValue('id_order');

        if (!$id_order && isset($params['order']) && ($order = $params['order'])) {
            $id_order = $order->id;
        }

        if (!$id_order) {
            return '';
        }

        ToolsModuleCMP::registerSmartyFunctions();

        $sql = 'SELECT * FROM `'._DB_PREFIX_.'order_commission` WHERE `id_order` = '.(int)$id_order;
        if (!$order_commission = Db::getInstance()->getRow($sql)) {
            return '';
        }
        $commission = CustomPayment::getOrderCommission($id_order);
        $commission_wt = $commission['commission_tax_excl'];
        $discount = CustomPayment::getOrderDiscount($id_order);
        $discount_wt = $discount['discount_tax_excl'];
        $order_obj = new Order($id_order);

        if (isset($params['tab']) && $params['tab'] == 'total') {
            $this->context->smarty->assign(
                array(
                    'commission' => (float)$commission_wt,
                    'discount' => (float)$discount_wt,
                    'base_commission' => (float)$order_commission['commission_tax_excl']
                        + (float)$order_commission['discount_tax_excl'],
                    'order' => $order_obj
                )
            );

            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'custompaymentmethod/views/templates/hook/invoice_total_tab.tpl'
            );
        } elseif (isset($params['tab']) && $params['tab'] == 'tax') {
            $total_tax = (float)($order_commission['commission'] + (float)$order_commission['discount']
                - $order_commission['commission_tax_excl'] - (float)$order_commission['discount_tax_excl']);
            if ($total_tax == 0) {
                return '';
            }
            $base_commission = (float)$order_commission['commission_tax_excl']
                + (float)$order_commission['discount_tax_excl'];
            $tax_rate = $total_tax * 100 / $base_commission;
            $this->context->smarty->assign(
                array(
                    'base_commission' => $base_commission,
                    'total_tax' => $total_tax,
                    'tax_rate' => Tools::round_helper($tax_rate, PS_ROUND_UP),
                    'order' => $order_obj
                )
            );
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'custompaymentmethod/views/templates/hook/invoice_tax_tab.tpl'
            );
        }
    }

    public function hookCpmPaymentModules($params)
    {
        $payment_modules = &$params['payment_modules'];
        $payments = CustomPayment::getCustomPaymentMethods(true);
        foreach ($payment_modules as $key => $item) {
            if ($item->id == $this->id) {
                unset($payment_modules[$key]);
            }
        }
        $obj_payment = $this;
        foreach ($payments as $payment) {
            $obj_payment->name = $obj_payment->name.'_'.$payment['id_custom_payment_method'];
            $obj_payment->displayName = $payment['name'];
            $payment_modules[] = $obj_payment;
        }
    }

    public function hookDisplayHeader()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->context->controller->addCSS($this->getPathUri().'views/css/front17.css');
            $this->context->controller->addJS($this->getPathUri().'views/js/front.js');
        } else {
            $this->context->controller->addCSS($this->getPathUri().'views/css/front.css');
        }

//        $this->context->controller->addCSS($this->getPathUri().'views/css/fontawesome.css');
//        $this->context->controller->addCSS($this->getPathUri().'views/css/front.css');

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            Media::addJsDef(
                array(
                    'cpm_width'  => Configuration::get('PS_CPM_ICON_WIDTH'),
                    'cpm_height' => Configuration::get('PS_CPM_ICON_HEIGHT')
                )
            );
        }
    }
    
    public function hookDisplayBackOfficeHeader()
    {
        $id_cart = (int)Tools::getValue('id_cart');
        $cart = new Cart($id_cart);
        
        if (Tools::isSubmit('ajax')) {
            if (Tools::getValue('action') == 'changePaymentMethod') {
                $customer = new Customer(Tools::getValue('id_customer'));
                $modules = Module::getAuthorizedModules($customer->id_default_group);
                $authorized_modules = [];
                
                if (!Validate::isLoadedObject($customer) ||!is_array($modules)) {
                    die(json_encode(array('result' => false)));
                }
                
                foreach ($modules as $module) {
                    if ($module['name'] != 'custompaymentmethod') {
                        $authorized_modules[] = (int)$module['id_module'];
                    }
                }
                
                $payment_modules = [];
                
                foreach (PaymentModule::getInstalledPaymentModules() as $p_module) {
                    if (in_array((int)$p_module['id_module'], $authorized_modules)) {
                        $payment_modules[] = Module::getInstanceById((int)$p_module['id_module']);
                    }
                }

                foreach ($payment_modules as $key => $payment) {
                    if ($payment_modules['name'] == 'custompaymentmethod') {
                        unset($payment_modules[$key]);
                        break;
                    }
                }

                $module_name = Tools::getValue('module_name');

                $this->context->smarty->assign(
                    array(
                        'payment_modules' => $payment_modules,
                        'module_name' => $module_name
                    )
                );

                $module_name = explode('_', $module_name);
                $custom_payment = new CustomPayment((int)$module_name[1], $this->context->language->id);
                if (Validate::isLoadedObject($custom_payment)) {
                    $result_commission = $this->calculateCommission(
                        $cart,
                        $custom_payment,
                        null
                    );

                    $total = $cart->getOrderTotal(true);
                    $total_without_tax = $cart->getOrderTotal(false);
                    $commission = $result_commission['commission'];
                    $commission_without_tax = $result_commission['commission_tax_excl'];

                    $result_discount = $this->calculateCommission(
                        $cart,
                        $custom_payment,
                        null
                    );
                    $discount = $result_discount['discount'];
                    $discount_without_tax = $result_discount['discount_tax_excl'];

                    $total += $commission + $discount;
                    $total_without_tax += $commission_without_tax;
                    $total_without_tax += $discount_without_tax;
                } else {
                    $total = $cart->getOrderTotal(true, Cart::BOTH);
                    $total_without_tax = $cart->getOrderTotal(false, Cart::BOTH);
                    $commission_without_tax = 0;
                    $commission = 0;
                    $discount_without_tax = 0;
                    $discount = 0;
                }

                die(
                    json_encode(
                        array(
                            'result' => true,
                            'view'   => $this->context->smarty->fetch(
                                _PS_MODULE_DIR_.'custompaymentmethod/views/templates/admin/_select_payment.tpl'
                            ),
                            'total'             => Tools::displayPrice($total),
                            'total_without_tax' => Tools::displayPrice($total_without_tax),
                            'commission'         => $this->l('Commission(excl tax)').': '.Tools::displayPrice(
                                $commission_without_tax
                            ).' '.$this->l('Commission(incl tax)').': '.Tools::displayPrice($commission),
                            'discount'          => $this->l('Discount(excl tax)').': '.Tools::displayPrice(
                                $discount_without_tax
                            ).' '.$this->l('Discount(incl tax)').': '.Tools::displayPrice($discount),
                        )
                    )
                );
            }
            
            if (Tools::getValue('action') == 'savePaymentMethodPosition') {
                $id_custom_payment_method = Tools::getValue('id_custom_payment_method');
                if (is_array($id_custom_payment_method) && count($id_custom_payment_method)) {
                    foreach ($id_custom_payment_method as $key => $value) {
                        Db::getInstance()->update(
                            'custom_payment_method',
                            array(
                                'position' => pSQL($key),
                            ),
                            ' id_custom_payment_method = '.(int)$value
                        );
                    }
                }
            }
        }
        
        if (Tools::getValue('method')) {
            if (Tools::getValue('method') == 'get_payment_methods') {
                $payments = CustomPayment::getCustomPaymentMethodsCollection($this->context->language->id, true);
                $json_payments = [];
                foreach ($payments as $payment) {
                    $json_payments[] = array(
                        'id'    => 'custompaymentmethod_'.$payment->id,
                        'value' => $payment->name,
                    );
                }
                die(json_encode($json_payments));
            }
        }
        if (Tools::isSubmit('submitAddOrder') && ($id_cart = Tools::getValue('id_cart'))
            && ($module_name = Tools::getValue('payment_module_name'))
            && ($id_order_state = Tools::getValue('id_order_state'))
            && Validate::isModuleName($module_name)
            && strpos($module_name, 'custompaymentmethod_') !== false
        ) {
            $payment_module = Module::getInstanceByName($module_name);
            $custom_payment = $payment_module->custom_payment;
            Context::getContext()->currency = new Currency((int)$cart->id_currency);
            Context::getContext()->customer = new Customer((int)$cart->id_customer);
            $employee = new Employee((int)Context::getContext()->cookie->id_employee);
            $total = $cart->getOrderTotal(true, Cart::BOTH);

            $calculate = self::calculateCommission($cart, $custom_payment);
            $commission = $calculate['commission'];
            $discount = $calculate['discount'];
            $commission_wt = $calculate['commission_tax_excl'];
            $discount_wt = $calculate['discount_tax_excl'];

            $payment_module->validateOrder(
                (int)$cart->id,
                (int)$id_order_state,
                $calculate['total'],
                $custom_payment->name,
                $this->l('Manual order -- Employee:').' '.
                Tools::substr($employee->firstname, 0, 1).'. '.$employee->lastname,
                [],
                null,
                false,
                $cart->secure_key
            );
            $current_order = 'currentOrder';
            CustomPayment::addOrderData(
                $payment_module->{$current_order},
                Context::getContext()->currency->id,
                $calculate
            );
            $order = new Order($payment_module->{$current_order});
//            $total_paid = $order->total_paid + $commission + $discount;
//            $order->total_paid = ($total_paid < 0 ? 0 : $total_paid);
//            $total_paid_tax_excl = $order->total_paid_tax_excl + $commission_wt + $discount_wt;
//            $order->total_paid_tax_excl = ($total_paid_tax_excl < 0 ? 0 : $total_paid_tax_excl);
//            $total_paid_tax_incl = $order->total_paid_tax_incl + $commission + $discount;
//            $order->total_paid_tax_incl = ($total_paid_tax_incl < 0 ? 0 : $total_paid_tax_incl);
            $order->save();
            if ($payment_module->{$current_order}) {
                Tools::redirectAdmin(
                    $this->context->link->getAdminLink(
                        'AdminOrders'
                    ).'&id_order='.$payment_module->{$current_order}
                    .'&vieworder'
                );
            }
        }
        
        if ($this->context->controller->controller_name == 'AdminOrders') {
            if (Tools::getIsset('addorder')) {
                $modules = CustomPayment::getCustomPaymentMethods();
                if (count($modules)) {
                    $carriers = [];
                    foreach ($modules as $module) {
                        $carriers['custompaymentmethod_'.$module['id_custom_payment_method']]
                            = $module['available_carriers'];
                    }
                    Media::addJsDef($carriers);
                }
                $this->context->smarty->assign('script_url', $this->_path.'views/js/admin_order.js');
            }

            return $this->display(__FILE__, 'back_office_header.tpl');
        }
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        /**
         * @var $status OrderState
         */
        $status = $params['newOrderStatus'];
        $id_order = (int)$params['id_order'];

        if (!Validate::isLoadedObject($this->custom_payment)) {
            return;
        }

        if (Db::getInstance()->getValue('SELECT `id_order` FROM `'._DB_PREFIX_.'order_commission` 
                                                       WHERE `id_order` = '.(int)$id_order)) {
            return;
        }

        $order = new Order($id_order);

        if ($order->module != $this->name) {
            return;
        }

        if ($this->context->cart == null) {
            $this->context->cart = Cart::getCartByOrderId($id_order);
        }
        $calculations = self::getOrderCalculations($this->context->cart, $this->custom_payment);

        $commission = Tools::ps_round($calculations['commission']);
        $discount = Tools::ps_round($calculations['discount']);

        if (($commission + $discount) == 0) {
            return;
        }

        CustomPayment::addOrderPaymentData(
            $id_order,
            array(
                'commission' => $commission,
                'discount' => $discount,
                'commission_tax_excl' => $calculations['commission_tax_excl'],
                'discount_tax_excl' => $calculations['discount_tax_excl']
            )
        );

        if (Validate::isLoadedObject($order) && $order->invoice_number) {
            Db::getInstance()->execute(
                'UPDATE `'._DB_PREFIX_.'order_invoice` SET `total_paid_tax_excl` = '.
                (float)$order->total_paid_tax_excl.', `total_paid_tax_incl` = '.
                (float)$order->total_paid_tax_incl.' WHERE id_order_invoice = '.(int)$order->invoice_number
            );
        }
    }
    
    private static function calculateCommission(
        Cart $cart,
        CustomPayment $payment,
        Context $context = null,
        $return = null
    ) {
        if (null === $context) {
            $context = Context::getContext();
        }
        
        $commission = 0;
        $commission_tax_excl = 0;
        $discount = 0;
        $discount_tax_excl = 0;
        
        $total = null;
        switch ((int)$payment->type_commission) {
            case CustomPayment::TYPE_COMMISSION_AMOUNT:
                $currency_commission = new Currency($payment->currency_commission);
                $commission_tax_excl = Tools::convertPriceFull(
                    $payment->commission_amount,
                    $currency_commission,
                    $context->currency
                );
                break;
            case CustomPayment::TYPE_COMMISSION_PERCENT:
                if ((int)$payment->apply_commission === CustomPayment::APPLY_COMMISSION_PRODUCTS) {
                    $commission_base = $cart->getOrderTotal(
                        $payment->commission_use_tax_on_products,
                        Cart::ONLY_PRODUCTS
                    );
                } elseif ((int)$payment->apply_commission === CustomPayment::APPLY_COMMISSION_TOTAL) {
                    $commission_base = $cart->getOrderTotal(
                        $payment->commission_use_tax_on_products,
                        Cart::ONLY_PRODUCTS
                    );
                    $shipping_base = $cart->getOrderTotal(
                        $payment->commission_use_tax_on_products,
                        Cart::ONLY_SHIPPING
                    );
                    $commission_base += $shipping_base;
                }
                
                $commission_tax_excl = ($commission_base / 100 * $payment->commission_percent);
                break;
        }

        $commission = $commission_tax_excl * (1 + $payment->commission_tax / 100);
        
        switch ((int)$payment->type_discount) {
            case CustomPayment::TYPE_COMMISSION_AMOUNT:
                $currency_discount = new Currency($payment->currency_discount);
                $discount_tax_excl = Tools::convertPriceFull(
                    $payment->discount_amount,
                    $currency_discount,
                    $context->currency
                );
                break;
            case CustomPayment::TYPE_COMMISSION_PERCENT:
                if ((int)$payment->apply_discount === CustomPayment::APPLY_COMMISSION_PRODUCTS) {
                    $discount_base = $cart->getOrderTotal(
                        $payment->discount_use_tax_on_products,
                        Cart::ONLY_PRODUCTS
                    );
                } elseif ((int)$payment->apply_discount === CustomPayment::APPLY_COMMISSION_TOTAL) {
                    $discount_base = $cart->getOrderTotal(
                        $payment->discount_use_tax_on_products,
                        Cart::ONLY_PRODUCTS
                    );
                    $shipping = $cart->getOrderTotal(
                        $payment->discount_use_tax_on_products,
                        Cart::ONLY_SHIPPING
                    );
                    $discount_base += $shipping;
                }
                
                $discount_tax_excl = ($discount_base / 100 * $payment->discount_percent);
                break;
        }

        $discount = $discount_tax_excl * (1 + $payment->discount_tax / 100);
        
        if ($return === 'commission') {
            return $commission;
        }
        if ($return === 'discount') {
            return -$discount;
        }
        
        $total = $cart->getOrderTotal(true, Cart::BOTH);
        $total += $commission;
        $total += -$discount;
        
        if ($return === 'total') {
            return $total;
        }
        
        return array(
            'total'      => $total,
            'commission' => $commission,
            'discount'   => -$discount,
            'commission_tax_excl' => $commission_tax_excl,
            'discount_tax_excl' => -$discount_tax_excl
        );
    }
    
    public static function getOrderCalculations(Cart $cart, CustomPayment $payment, Context $context = null)
    {
        return self::calculateCommission($cart, $payment, $context);
    }
    
    public static function getOrderTotal(Cart $cart, CustomPayment $payment, Context $context = null)
    {
        return self::calculateCommission($cart, $payment, $context, 'total');
    }
    
    
    public static function getCommission(Cart $cart, CustomPayment $payment, Context $context = null)
    {
        return self::calculateCommission($cart, $payment, $context, 'commission');
    }
    
    public static function getDiscount(Cart $cart, CustomPayment $payment, Context $context = null)
    {
        return self::calculateCommission($cart, $payment, $context, 'discount');
    }
    
    public function getMailTemplatePath()
    {
        return _PS_MODULE_DIR_.'custompaymentmethod/mails/';
    }
    
    public function sendMail($template, $email_to, $theme, $template_vars = [])
    {
        $this->checkAndFixEmailTemplateForLang($this->context->language, $template);
        Mail::Send(
            $this->context->language->id,
            $template,
            $theme,
            $template_vars,
            $email_to,
            null,
            Configuration::get('PS_SHOP_EMAIL'),
            Configuration::get('PS_SHOP_NAME'),
            null,
            null,
            $this->getMailTemplatePath()
        );
    }
    
    public function validateOrder(
        $id_cart,
        $id_order_state,
        $amount_paid,
        $payment_method = 'Unknown',
        $message = null,
        $extra_vars = [],
        $currency_special = null,
        $dont_touch_amount = false,
        $secure_key = false,
        Shop $shop = null,
        $order_reference = null
    ) {
        $calculations = self::getOrderCalculations($this->context->cart, $this->custom_payment);

        $commission = $calculations['commission'];
        $discount = $calculations['discount'];

        if ($commission != 0) {
            $extra_vars['{commission_title}'] = $this->l('Payment commission');
        } else {
            $extra_vars['{commission_title}'] = $this->l('Payment discount');
        }

        $extra_vars['{commission}'] = Tools::displayPrice(
            Tools::ps_round($commission + $discount, _PS_PRICE_DISPLAY_PRECISION_),
            $this->context->currency
        );

        $return = parent::validateOrder(
            $id_cart,
            $id_order_state,
            $amount_paid,
            $payment_method,
            $message,
            $extra_vars,
            $currency_special,
            $dont_touch_amount,
            $secure_key,
            $shop
        );
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $id_order = Db::getInstance()->getValue('SELECT `id_order` 
                                                                         FROM `'._DB_PREFIX_.'orders` 
                                                                         WHERE `id_cart` = '.(int)$id_cart);
        } else {
            $id_order = $this->getIdByCartId($id_cart);
        }
            $total = round($calculations['total'], 2);

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'order_payment` op
            LEFT JOIN  `'._DB_PREFIX_.'orders` o  ON o.id_order = ' .(int)$id_order . '
            SET op.`amount` = '. $total .' WHERE op.`order_reference` = o.`reference`'
        );

        if (($commission + $discount) != 0) {
            CustomPayment::addOrderData(
                self::getIdByCartId($id_cart),
                $this->context->currency->id,
                array(
                    'commission' => $commission,
                    'discount' => $discount,
                    'commission_tax_excl' => $calculations['commission_tax_excl'],
                    'discount_tax_excl' => $calculations['discount_tax_excl']
                )
            );
        }
        return $return;
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
    
    public function fixEmailTemplateForLang($lang, $template_filename)
    {

        if (!file_exists($template_path = $this->getMailTemplatePath().$lang->iso_code)) {
            mkdir($template_path = $this->getMailTemplatePath().$lang->iso_code);
        }
        $default_template_path = $this->getMailTemplatePath().'en/';
        $template_path = $this->getMailTemplatePath().$lang->iso_code.'/'.$template_filename;
        if (file_exists($default_template_path.$template_filename)) {
            call_user_func_array(
                'copy',
                array(
                    $default_template_path.$template_filename,
                    $template_path,
                )
            );
        }
    }
    
    public function checkAndFixEmailTemplateForLang($lang, $template)
    {
        $template_path = $this->getMailTemplatePath().$lang->iso_code.'/'.$template;
        if (!file_exists($template_path.'.txt')) {
            $this->fixEmailTemplateForLang($lang, $template.'.txt');
        }
        if (!file_exists($template_path.'.html')) {
            $this->fixEmailTemplateForLang($lang, $template.'.html');
        }
    }
    
    public function getContent()
    {
        ToolsModuleCMP::registerSmartyFunctions();
        $this->context->controller->addJS(
            'https://seosaps.com/ru/module/seosamanager/manager?ajax=1&action=script&iso_code='
            .Context::getContext()->language->iso_code
        );
        $this->context->smarty->assign(
            array(
                'content_tab'   => $this->getContentWrap(),
                'documentation' => $this->getDocumentation(),
            )
        );
        
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/content.tpl');
    }
    
    public function assignDocumentation()
    {
        ToolsModuleCMP::registerSmartyFunctions();
        
        $this->context->controller->addCSS($this->_path .'views/css/documentation.css');
        
        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $this->context->controller->addCSS(($this->_path).'views/css/documentation.css', 'all');
            $this->context->controller->addCSS(($this->_path).'views/css/admin-theme.css', 'all');
        }
        
        $documentation_folder = $this->getLocalPath().'views/templates/admin/documentation';
        $documentation_pages = self::globRecursive($documentation_folder.'/**.tpl');
        natsort($documentation_pages);
        
        $tree = [];
        if (is_array($documentation_pages) && count($documentation_pages)) {
            foreach ($documentation_pages as &$documentation_page) {
                $name = str_replace(array($documentation_folder.'/', '.tpl'), '', $documentation_page);
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
        
        $this->context->smarty->assign('tree', $tree);
        $this->context->smarty->assign('documentation_pages', $documentation_pages);
        $this->context->smarty->assign('documentation_folder', $documentation_folder);
    }
    
    public function getDocumentation()
    {
        $this->assignDocumentation();
        
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->name.'/views/templates/admin/documentation.tpl'
        );
    }
    
    /**
     * @param string $pattern
     * @param int    $flags
     *
     * @return array
     */
    public static function globRecursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        if (!$files) {
            $files = [];
        }
        
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::globRecursive($dir.'/'.basename($pattern), $flags));
        }
        
        return $files;
    }
    
    public static function noEscape($value)
    {
        return $value;
    }
    
    public function getImageLangPayment($smarty)
    {
        if (_PS_VERSION_ < 1.5) {
            $cookie = &$GLOBALS['cookie'];
        } else {
            $cookie = $this->context->cookie;
            $cookie->id_lang = $this->context->language->id;
        }
        
        $path = $smarty['path'];
        $module_path = $this->name.'/views/img/';
        $current_language = new Language($cookie->id_lang);
        $module_lang_path = $module_path.$current_language->iso_code.'/';
        $module_lang_default_path = $module_path.'en/';
        $path_image = false;
        if (file_exists(_PS_MODULE_DIR_.$module_lang_path.$path)) {
            $path_image = _MODULE_DIR_.$module_lang_path.$path;
        } elseif (file_exists(_PS_MODULE_DIR_.$module_lang_default_path.$path)) {
            $path_image = _MODULE_DIR_.$module_lang_default_path.$path;
        }
        
        if ($path_image) {
            return '<img class="thumbnail" src="'.$path_image.'">';
        } else {
            return '[can not load image "'.$path.'"]';
        }
    }

    public function addCheckboxCarrierRestrictionsForModule(array $shops = [])
    {
        if (!$shops) {
            $shops = Shop::getShops(true, null, true);
        }

        $carriers = Carrier::getCarriers(
            (int) Context::getContext()->language->id,
            false,
            false,
            false,
            null,
            Carrier::ALL_CARRIERS
        );
        $carrier_ids = [];
        foreach ($carriers as $carrier) {
            $carrier_ids[] = $carrier['id_reference'];
        }

        foreach ($shops as $s) {
            foreach ($carrier_ids as $id_carrier) {
                if (!Db::getInstance()->execute(
                    'INSERT INTO `' . _DB_PREFIX_ . 'module_carrier` (`id_module`, `id_shop`, `id_reference`)
				     VALUES (' . (int) $this->id . ', "' . (int) $s . '", ' . (int) $id_carrier . ')'
                )) {
                    return false;
                }
            }
        }
        return true;
    }
}
