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
class Cdek20TransModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();

        exit(json_encode([
            'Fill out postal code field' => $this->l('Fill out postal code field'),
            'Choose the country' => $this->l('Choose the country'),
            'This delivery method is not available for this city' => $this->l('This delivery method is not available for this city'),
            'Are you sure you want to perform an action?' => $this->l('Are you sure you want to perform an action?'),
        ]));
    }

    protected function l($string, $specific = 'trans', $class = null, $addslashes = false, $htmlentities = true)
    {
        if (isset($this->module) && is_a($this->module, 'Module')) {
            return $this->module->l($string, $specific);
        } else {
            return $string;
        }
    }
}
