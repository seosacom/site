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

class PostamatCdek extends AbstractCarrierCdek
{
    public $type = 'postamat';

    public function __construct($cart, $customer)
    {
        parent::__construct($cart, $customer);

        $this->part_deliv = false;
    }

    public function calculate()
    {
        parent::calculate();

        if ($this->calculation && !$this->checkDimensions()) {
            $this->calculation = false;
        }

        if (!$this->calculation) {
            $this->customer->{'city_'.$this->type} = PvzCdek::getReserveCity($this->customer->{'city_'.$this->type}, 'POSTAMAT');
            parent::calculate();
            if ($this->calculation && $this->checkDimensions()) {
                $this->customer->save();
            } else {
                $this->calculation = false;
            }
        }

        return $this;
    }

    public function checkDimensions()
    {
        if ($this->type != 'postamat') {
            return true;
        }

        $pvz_max = $pvz = PvzCdek::getMaxPostamat($this->customer->{'city_'.$this->type});

        if (!$pvz_max) {
            return false;
        }
        rsort($pvz_max);

        foreach ($this->cart->getProducts() as $product) {
            $p_demensions = array(
                $product->getLength(),
                $product->getWidth(),
                $product->getHeight(),
            );
            rsort($p_demensions);
            foreach ($p_demensions as $key => $val) {
                if ($val > $pvz_max[$key]) {
                    return false;
                }
            }
        }

        $total_dimension = $this->cart->getTotalDimensions($pvz);
        rsort($total_dimension);

        foreach ($total_dimension as $key => $val) {
            if ($val > $pvz_max[$key]) {
                return false;
            }
        }
        return true;
    }
}
