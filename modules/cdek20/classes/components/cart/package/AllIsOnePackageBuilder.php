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


use Seleda\Cdek\Cart\ProductFromCart;

class AllIsOnePackageBuilder extends PackageBuilder
{
    // 3. Включена опция «Все товары на одной позиции». Остальные опции выключены (all_is_one_package)
    // 6. Включены опции  «Все товары на одной позиции» и опция «Все товары в одной коробке» (all_is_one_package, all_one_box)
    public function build()
    {
        $cart_global_product = [
            'id_product' => 0,
            'id_product_attribute' => 0,
            'cart_quantity' => 1,
            'name' => 'Сборный товар',
            'attributes' => '',
            'reference' => 'None',
            'id_category_default' => 0,
            'price_wt' => 0,
            'price' => 0,
            'width' => 0, // Возьмется дефолтный, т.к id_category_default = 0
            'height' => 0, // Возьмется дефолтный
            'depth' => 0, // Возьмется дефолтный
            'weight' => 0
        ];

        foreach ($this->cart->getProducts() as $key => $product) {
            $cart_global_product['price_wt'] += $product->getPayment()->getValue() * $product->getAmount();
            $cart_global_product['price'] += $product->getcost() * $product->getAmount();
            // В объекте $product уже учтены настройки меры веса. При создании нового глобального продукта вес еще раз пересчитается
            $cart_global_product['weight'] += $product->getWeight() * $product->getAmount() / $product->getShopWeightUnit();
        }

        $product = new ProductFromCart($cart_global_product);

        $packages = [];

        $package = new Package(1);
        $package_decorator = new PackageDecorator($package);
        $package_decorator = $product->decor($package_decorator);

        $packages[] = $package_decorator->getPackage();

        return $packages;
    }
}