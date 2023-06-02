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
class AdminCdekPackageController extends ModuleAdminController
{
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
            Tools::redirectAdmin(
                Context::getContext()->link->getBaseLink() . array_pop($admin_dir_exp) . '/' . Context::getContext(
                )->link->getAdminLink('AdminCdekPackage')
            );
        }

        $this->bootstrap = true;
        parent::init();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addCSS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/collector.css');
        $this->addCSS(
            __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/node_modules/gridstack/dist/gridstack.min.css'
        );
        $this->addJS(
            __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/node_modules/gridstack/dist/gridstack-h5.js'
        );
        $this->addJS(__PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/collector.js');
    }

    public function postProcess()
    {
        $success_empty = false;
        if (!Tools::getValue('submitCollector')) {
            return parent::postProcess();
        } else {
            $success_empty = true;
        }
        $packages = Tools::getValue('package');
        $success_items = true;
        foreach ($packages as $number => &$package) {
            if (!isset($package['items'])) {
                $this->errors[] = $this->l('The package cannot be empty');
                $success_items = false;
                continue;
            }
            foreach ($package['items'] as &$item) {
                $item = new ItemCdek($item);
            }
            $package = new PackageCdek($package);
            $package->setNumber($number);
        }
        $id_order = Tools::getValue('id_order');
        $order_cdek = new OrderCdek(new Order($id_order));

        $order_cdek->getEntity()->setPackages(array_values($packages));

        if ($success_items or $success_empty) {
            $order_cdek->save();
            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $redirect_url = $this->context->link->getAdminLink('AdminOrders', true, ['id_order' => $id_order]);
            } else {
                $redirect_url = ${'GLOBALS'}['kernel']->getContainer()->get('router')->generate(
                    'admin_orders_view',
                    ['orderId' => $id_order]
                );
            }
            Tools::redirectAdmin($redirect_url);
        }
    }

    public function initContent()
    {
        $id_order = Tools::getValue('id_order');
        $order_cdek = new OrderCdek(new Order($id_order));
        $packages_source = $order_cdek->getEntity()->getPackages();

        $packages = [];
        foreach ($packages_source as $package) {
            $package = $package->propertiesToArray();
            $items = [];
            foreach ($package['items'] as $item) {
                $amount = $item['amount'];
                for ($i = 0; $i < $amount; $i++) {
                    $item['amount'] = 1;
                    $items[] = $item;
                }
            }
            $package['items'] = $items;
            $packages[] = $package;
        }

        Media::addJsDef(
            [
                'packages' => $packages,
            ]
        );
        $this->context->smarty->assign(
            [
                'packages' => $packages,
                'weight_unit' => ConfigurationCdek::get('weight_unit'),
                'volume_unit' => ConfigurationCdek::get('volume_unit'),
            ]
        );
        $this->setTemplate('collector.tpl');

        return parent::initContent();
    }

    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return parent::l($string, __CLASS__);
    }
}
