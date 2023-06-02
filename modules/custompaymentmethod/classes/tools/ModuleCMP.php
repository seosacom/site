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
class ModuleCMP extends PaymentModule
{
    public $hooks = [];
    public $classes = [];
    public $config = [];
    public $tabs = [];
    public $documentation = true;
    public $documentation_type = null;
    const DOCUMENTATION_TYPE_TAB = 'tab';
    const DOCUMENTATION_TYPE_SIMPLE = 'simple';

    public function __construct()
    {
        $this->name = ToolsModuleCMP::getModNameForPath(__FILE__);
        $this->documentation_type = self::DOCUMENTATION_TYPE_SIMPLE;
        $this->bootstrap = true;
        parent::__construct();
    }

    public function registerHooks()
    {
        foreach ($this->hooks as $hook) {
            $this->registerHook($hook);
        }

        return true;
    }

    public function installClasses()
    {
        foreach ($this->classes as $class) {
            HelperDbCMP::loadClass($class)->installDb();
        }

        return true;
    }

    public function uninstallClasses()
    {
        foreach ($this->classes as $class) {
            HelperDbCMP::loadClass($class)->uninstallDb();
        }

        return true;
    }

    public function installConfig()
    {
        foreach ($this->config as $name => $value) {
            ConfCMP::setConf($name, $value);
        }

        return true;
    }

    public function uninstallConfig()
    {
        foreach (array_keys($this->config) as $name) {
            ConfCMP::deleteConf($name);
        }

        return true;
    }

    public function installTabs()
    {
        foreach ($this->tabs as $tab) {
            ToolsModuleCMP::createTab($this->name, $tab['tab'], $tab['parent'], $tab['name']);
        }

        return true;
    }

    public function uninstallTabs()
    {
        foreach ($this->tabs as $tab) {
            ToolsModuleCMP::deleteTab($tab['tab']);
        }

        return true;
    }

    public function install()
    {
        return parent::install()
            && $this->registerHooks()
            && $this->installClasses()
            && $this->installConfig()
            && $this->installTabs();
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallClasses()
            && $this->uninstallConfig()
            && $this->uninstallTabs();
    }

    public function getDocumentation()
    {
        DocumentationCMP::assignDocumentation();
        $return_back_link = '#';
        if (count($this->tabs)) {
            $return_back_link = $this->context->link->getAdminLink($this->tabs[1]['tab']);
        }
        $this->context->smarty->assign('mod_dir', _MODULE_DIR_.$this->name . '/');
        $this->context->smarty->assign('return_back_link', $return_back_link);

        return ToolsModuleCMP::fetchTemplate('admin/documentation.tpl');
    }

    public function getContent()
    {
        if (!$this->documentation) {
            return $this->getContentTab();
        } else {
            $this->context->controller->addCSS($this->getPathUri() . 'views/css/admin/admin-theme.css');
            if ($this->documentation_type == self::DOCUMENTATION_TYPE_SIMPLE) {
                return $this->getDocumentation();
            }
            ToolsModuleCMP::registerSmartyFunctions();
            $this->context->smarty->assign(
                [
                    'content_tab' => $this->getContentTab(),
                    'documentation' => $this->getDocumentation(),
                ]
            );

            return ToolsModuleCMP::fetchTemplate('admin/content.tpl');
        }
    }

    public function getContentTab()
    {
    }
}
