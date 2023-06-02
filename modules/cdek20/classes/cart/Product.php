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

use Seleda\Cdek\Component\Cart\Product as ComponentProduct;
use \ConfigurationCdek;
use \Combination;
use \Db;
use \Tools;


abstract class Product extends ComponentProduct
{
    protected $id_product;
    protected $id_product_attribute;
    protected $id_category;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
        $this->createSub();
    }

    final public function createSub()
    {
        $this->setIdProduct();
        $this->setIdProductAttribute();
        $this->setIdCategory();

        $this->create();
    }

    public function setShopWeightUnit()
    {
        ($this->shop_weight_unit = (int)ConfigurationCdek::get('weight_unit')) ||
        ($this->shop_weight_unit = 1); // 1 - gr or 1000 - kg // TODO lb 453,59237 g
    }

    public function setShopVolumeUnit()
    {
        ($this->shop_volume_unit = (float)ConfigurationCdek::get('volume_unit')) ||
        ($this->shop_volume_unit = 1); //100 - meter or 1 - sm or 0.1 - mm
    }
    
    abstract protected function setIdProduct();
    abstract protected function setIdProductAttribute();
    abstract protected function setIdCategory();

    final protected function calculateProductWeight($product)
    {
        $weight = 0;

        $impact = 0;
        if (Combination::isFeatureActive()) {
            $impact = (float)Db::getInstance()->getValue('SELECT `weight`
                        FROM `'._DB_PREFIX_.'product_attribute` 
                        WHERE `id_product_attribute` = '.(int)$product->id_product_attribute);
        }

        $default_categories = ConfigurationCdek::get('default_categories');

        if (empty($product->weight) && $impact != 0) {
            $weight += $impact * $this->shop_weight_unit;
        } elseif ((float)$product->weight != 0) {
            $weight += $product->weight * $this->shop_weight_unit;
        } elseif ((float)$product->weight && $product->weight != $impact) {
            $weight += $product->weight * $this->shop_weight_unit;
        } elseif (isset($default_categories[$product->id_category]) && $default_categories[$product->id_category]['weight']) {
            $weight += $default_categories[$product->id_category]['weight'] * $this->shop_weight_unit;
        } elseif (($length = $this->calculateProductDimension($product, 'length', true)) &&
            ($width = $this->calculateProductDimension($product, 'width', true)) &&
            ($height = $this->calculateProductDimension($product, 'height', true))) {
            $volume = $width * $height * $length / 5; // объемный вес
            $weight += round($volume);
        } else {
            $default_categories = ConfigurationCdek::get('default_categories');
            if (isset($default_categories[$product->id_category]) && (int)$default_categories[$product->id_category]['weight'] > 0) {
                $weight += $default_categories[$product->id_category]['weight'] * $this->shop_weight_unit;
            }
        }

        if ($weight == 0) {
            $weight = ConfigurationCdek::get('default_weight') * $this->shop_weight_unit;
        }

        return (int) Tools::ps_round($weight, 0);
    }

    final protected function calculateProductDimension($product, $dimension)
    {
        $res = 0;

        $default_categories = ConfigurationCdek::get('default_categories');

        if ((float)$product->{$dimension}) {
            $res = (int)($product->{$dimension} * $this->shop_volume_unit);
        } elseif (isset($default_categories[$product->id_category]) && $default_categories[$product->id_category][$dimension]) {
            $res = (int)$default_categories[$product->id_category][$dimension] * $this->shop_volume_unit;
        } else {
            $res = (int)ConfigurationCdek::get('default_'.$dimension) * $this->shop_volume_unit;
        }

        return (int)$res;
    }

    // TODO
    public function setAmountForce($val)
    {
        $this->amount = (int)$val;
    }
}