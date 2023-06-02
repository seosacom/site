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
use Seleda\Cdek\Component\Calculator\Calculator;

class CalculatorCdek extends Calculator
{
    public function __construct($date_departure, $type_contract, $from_location_code, $to_location_code, $cart)
    {
        $this->date_departure = $date_departure;
        $this->type_contract = $type_contract;
        $this->from_location_code = $from_location_code;
        $this->to_location_code = $to_location_code;
        $this->cart = $cart;
        $this->table = _DB_PREFIX_ . 'cdek_calculator';
        $this->lang = LangCdek::getInstance(Context::getContext()->language->id)->getLang();
    }
}
