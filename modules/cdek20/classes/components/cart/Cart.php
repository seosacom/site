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

namespace Seleda\Cdek\Component\Cart;

use Seleda\Cdek\Component\Calculator\ICartCalculator;
use Seleda\Cdek\Component\Cart\Package\PackagesBuilderInterface;


abstract class Cart implements CartInterface, ICartCalculator
{
    /**
     * @var array Package
     */
    private $packages = array();
    /**
     * @var int for calculator cache
     */
    protected $id_calculator_cache;

    /**
     * @var array Product
     */
    protected $products = array();

    /**
     * @var int https://api-docs.cdek.ru/63345519.html#id-%D0%9A%D0%B0%D0%BB%D1%8C%D0%BA%D1%83%D0%BB%D1%8F%D1%82%D0%BE%D1%80.%D0%A0%D0%B0%D1%81%D1%87%D0%B5%D1%82%D0%BF%D0%BE%D0%B4%D0%BE%D1%81%D1%82%D1%83%D0%BF%D0%BD%D1%8B%D0%BC%D1%82%D0%B0%D1%80%D0%B8%D1%84%D0%B0%D0%BC-calc_currency2
     */
    protected $currency;

    protected $package_builder;

    final protected function create()
    {
        $this->setIdCalculatorCache();
        $this->setProducts();
        $this->setCurrency();
        $this->setPackageBuilder();
    }

    abstract function setIdCalculatorCache();
    abstract function setProducts();
    abstract function setCurrency();

    final public function getProducts()
    {
        return $this->products;
    }

    final public function createPackages()
    {
        if (is_null($this->package_builder)) {
            throw new \Exception('Error: Add a package builder($cart->setPackageBuilder($builder)');
        }
        $this->packages = $this->package_builder->build();
        return $this;
    }

    final public function getPackagesForCalculator()
    {
        $packages = array();
        foreach ($this->packages as &$package) {
            $packages[] = array(
                'weight' => $package->getWeight()
            );
        }
        return $packages;
    }

    final public function getPackagesForOrder()
    {
        $packages = array();
        foreach ($this->packages as &$package) {
            $packages[] = array(
                'number' => $package->getNumber(),
                'weight' => $package->getWeight(),
                'items' => $package->getItems()
            );
        }
        return $packages;
    }

    final public function getTotalWeight()
    {
        $total_weight = 0;
        foreach ($this->products as $product) {
            $total_weight += $product->getWeight() * $product->getAmount();
        }

        return $total_weight;
    }

    final public function getIdCalculatorCache()
    {
        return $this->id_calculator_cache;
    }

    final public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * https://chilihelp.ru/portfolio/vychislenie-gabaritov-posylki-iz-neskolkikh-tovarov/
     */
    final public function getTotalDimensions($cell)
    {
        $volume = 0;
        foreach ($this->getProducts() as $product) {
            $volume += $product->getLength() * $product->getWidth() * $product->getHeight() * $product->getAmount();
        }
        // увеличить объем на 10%
        $volume *= 1.1;

        $ratio = array(
            'length' => 1,
            'width' => $cell['width'] / $cell['depth'],
            'height' => $cell['height'] / $cell['depth']
        );

        $length = pow($volume / ($ratio['width'] * $ratio['height']), 1/3);

        $dimensions = array(
            'length' => round($length),
            'width' => round($length * $ratio['width']),
            'height' => round($length * $ratio['height'])
        );

        return $dimensions;
    }
}