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
class ItemCdek extends AbstractTypeCdek
{
    protected $id;
    protected $name;
    protected $ware_key;
    protected $payment;
    protected $cost;
    protected $weight;
    protected $weight_gross;
    protected $amount;
    protected $delivery_amount;
    protected $name_i18n;
    protected $brand;
    protected $country_code;
    protected $material;
    protected $wifi_gsm;
    protected $url;

    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }

    public function setId($val)
    {
        $this->id = $val;
        return $this;
    }

    public function setWareKey($val)
    {
        $this->ware_key = $val;
        return $this;
    }

    public function setPayment(MoneyCdek $val)
    {
        $this->payment = $val;
        return $this;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setCost($val)
    {
        $percentage_reduction = ConfigurationCdek::get('product_price_reduction');
        // Переведено в тип String иначе при использованнии json_encode при записи в базу округление слетит
        $this->cost = (string) Tools::ps_round($val * (1 - $percentage_reduction / 100), 2);
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setWeight($val)
    {
        $this->weight = $val;
        return $this;
    }

    public function setWeightGross($val)
    {
        $this->weight_gross = $val;
        return $this;
    }

    public function setAmount($val)
    {
        $this->amount = $val;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
