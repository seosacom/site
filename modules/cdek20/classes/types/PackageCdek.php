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
class PackageCdek extends AbstractTypeCdek
{
    protected $number;
    protected $weight;
    protected $weight_volume;
    protected $weight_calc;
    protected $length;
    protected $width;
    protected $height;
    protected $comment;
    protected $items = [];

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($val)
    {
        $this->number = $val;
        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($val)
    {
        $this->weight = $val;
        return $this;
    }

    public function setLength($val)
    {
        $this->length = $val;
        return $this;
    }

    public function setWidth($val)
    {
        $this->width = $val;
        return $this;
    }

    public function setHeight($val)
    {
        $this->height = $val;
        return $this;
    }

    public function setItems($val)
    {
        $this->items = $val;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }
}
