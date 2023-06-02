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

use Seleda\Cdek\Cart\Money;
use \Tools;

class ProductFromCart extends Product
{
    protected function setIdProduct()
    {
        $this->id_product = (int)$this->product['id_product'];
    }
    protected function setIdProductAttribute()
    {
        $this->id_product_attribute = (int)$this->product['id_product_attribute'];
    }
    protected function setAmount()
    {
        $this->amount = (int)$this->product['cart_quantity'];
    }
    protected function setName()
    {
        $this->name = $this->product['name'].(isset($this->product['attributes']) && $this->product['attributes'] ? ' '.$this->product['attributes'] : '');
    }
    protected function setWareKey()
    {
        $this->ware_key = $this->product['reference'] ? $this->product['reference'] : 'None';
    }
    protected function setIdCategory()
    {
        $this->id_category = (int)$this->product['id_category_default'];
    }
    protected function setPayment()
    {
        $payment = new Money();
        $payment->setValue(Tools::ps_round($this->product['price_wt'], 2));
        $this->payment = $payment;
    }
    protected function setCost()
    {
        $this->cost = Tools::ps_round($this->product['price'], 2);
    }
    protected function setWidth()
    {
        $this->width = $this->product['width'];
        $this->width = self::calculateProductDimension($this, 'width');
    }
    protected function setHeight()
    {
        $this->height = $this->product['height'];
        $this->height = self::calculateProductDimension($this, 'height');
    }
    protected function setLength()
    {
        $this->length = $this->product['depth'];
        $this->length = self::calculateProductDimension($this, 'length');
    }
    protected function setWeight()
    {
        $this->weight = $this->product['weight'];
        $this->weight = self::calculateProductWeight($this);
    }
}