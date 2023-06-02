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

use Seleda\Cdek\Cart\Money;
use Seleda\Cdek\Component\Cart\Package\PackageDecoratorInterface;

abstract class Product implements PackageDecoratorInterface
{
    protected $component; // for decorator
    
    /**
     * @var string (255)Наименование товара (может также содержать описание товара: размер, цвет)
     */
    protected $name;
    /**
     * @var string (50)Идентификатор/артикул товара
     */
    protected $ware_key;
    /**
     * @var Money Оплата за товар при получении (за единицу товара в валюте страны получателя, значение >=0) — наложенный платеж, в случае предоплаты значение = 0
     */
    protected $payment;
    /**
     * @var float Объявленная стоимость товара (за единицу товара в валюте взаиморасчетов, значение >=0). С данного значения рассчитывается страховка
     */
    protected $cost;
    /**
     * @var integer Вес (за единицу товара, в граммах)
     */
    protected $weight;
    /**
     * @var integer Длина в сантиметрах
     */
    protected $length;
    /**
     * @var integer Ширина в сантиметрах
     */
    protected $width;
    /**
     * @var integer Высота в сантиметрах
     */
    protected $height;
    /**
     * @var integer Количество единиц товара (в штуках) Количество одного товара в заказе может быть от 1 до 999
     */
    protected $amount;
    /**
     * @var integer
     */
    protected $shop_weight_unit;
    /**
     * @var float
     */
    protected $shop_volume_unit;
    
    public function decor(PackageDecoratorInterface $package)
    {
        $this->component = $package;
        return $this;
    }


    final public function create()
    {
        $this->setShopWeightUnit();
        $this->setShopVolumeUnit();
        $this->setAmount();
        $this->setName();
        $this->setWareKey();
        $this->setPayment();
        $this->setCost();
        $this->setWidth();
        $this->setHeight();
        $this->setLength();
        $this->setWeight();
    }

    abstract protected function setShopWeightUnit();
    abstract protected function setShopVolumeUnit();
    abstract protected function setAmount();
    abstract protected function setName();
    abstract protected function setWareKey();
    abstract protected function setPayment();
    abstract protected function setCost();
    abstract protected function setWidth();
    abstract protected function setHeight();
    abstract protected function setLength();
    abstract protected function setWeight();

    final public function getName()
    {
        return $this->name;
    }

    final public function getWareKey()
    {
        return $this->ware_key;
    }

    final public function getPayment()
    {
        return $this->payment;
    }
    
    final public function getCost()
    {
        return $this->cost;
    }

    final public function getAmount()
    {
        return $this->amount;
    }

    final public function getWeight()
    {
        return $this->weight;
    }

    final public function getWidth()
    {
        return $this->width;
    }

    final public function getHeight()
    {
        return $this->height;
    }

    final public function getLength()
    {
        return $this->length;
    }

    final public function getShopWeightUnit()
    {
        return $this->shop_weight_unit;
    }

    final public function getShopVolumeUnit()
    {
        return $this->shop_volume_unit;
    }
    
    final public function getPackage()
    {
        $package =  $this->component->getPackage();
        $package->addWeight($this->getWeight() * $this->getAmount());
        $package->addProduct($this);
        return $package;
    }
}