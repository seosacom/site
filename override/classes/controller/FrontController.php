<?php
/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class FrontController extends FrontControllerCore
{
    /*
    * module: custompaymentmethod
    * date: 2023-04-17 10:17:11
    * version: 1.5.18
    */
    public function __construct()
    {
        parent::__construct();
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')
            && $this->php_self == 'order'
        ) {
            if (Module::isEnabled('custompaymentmethod')) {
                require_once _PS_MODULE_DIR_ . 'custompaymentmethod/classes/SeoSaCartPresenter.php';
                $this->cart_presenter = new SeoSaCartPresenter();
            }
        }
    }
}
