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

namespace Seleda\Cdek\Cart;

use \Order as PsOrder;
use \LangCdek;
use \CurrencyCdek;
use \Currency;

class CartFromOrder extends AbstractCart
{
    private $ps_order;

    public function __construct(PsOrder $ps_order)
    {
        $this->ps_order = $ps_order;
        $this->id_address_delivery = $ps_order->id_address_delivery;
        $this->lang = LangCdek::getInstance($ps_order->id_lang)->getLang();;
        $this->create();
    }

    public function setIdCalculatorCache()
    {
        $this->id_calculator_cache = $this->ps_order->id_cart;
    }

    public function setProducts()
    {
        foreach ($this->ps_order->getOrderDetailList() as $product) {
            $product = new ProductFromOrder($product);
            $this->products[] = $product;
        }
    }

    public function setCurrency()
    {
        $this->currency = CurrencyCdek::getCurrency(Currency::getIsoCodeById($this->ps_order->id_currency));
    }
}