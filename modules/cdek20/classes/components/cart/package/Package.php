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

namespace Seleda\Cdek\Component\Cart\Package;


use Seleda\Cdek\Component\Cart\Product;

class Package
{
    /**
     * @var string
     */
    protected $number;
    /**
     * @var integer Вес в граммах
     */
    protected $weight = 0;
    /**
     * @var integer Длина в сантиметрах
     */
    protected $length = 0;
    /**
     * @var integer Ширина в сантиметрах
     */
    protected $width = 0;
    /**
     * @var integer Высота в сантиметрах
     */
    protected $height = 0;
    /**
     * @var array Product
     */
    protected $products = [];

    public function __construct($number)
    {
        $this->number = (string)$number;
    }

    public function addWeight($val)
    {
        $this->weight += $val;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getItems()
    {
        $items = [];
        foreach ($this->products as $product) {
            $items[] = [
                'name' => $product->getName(),
                'ware_key' => $product->getWareKey(),
                'payment' => [
                    'value' => $product->getPayment()->getValue()
                ],
                'cost' => $product->getCost(),
                'amount' => $product->getAmount(),
                'weight' => $product->getWeight(),
                'width' => $product->getWidth(),
                'height' => $product->getHeight(),
                'length' => $product->getLength()
            ];
        }
        return $items;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }
}