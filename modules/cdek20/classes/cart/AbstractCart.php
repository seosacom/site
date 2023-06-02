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

use Seleda\Cdek\Component\Cart\Cart as ComponentCart;
use Seleda\Cdek\Component\Cart\Package\AllIsOnePackageBuilder;
use Seleda\Cdek\Component\Cart\Package\ByNameBuilder;
use Seleda\Cdek\Component\Cart\Package\OnePackageBuilder;
use Seleda\Cdek\Component\Cart\Package\AllOneBoxBuilder;
use Seleda\Cdek\Component\Cart\Package\OnePackageAllOneBoxBuilder;
use Seleda\Cdek\Component\Cart\Package\PackagesBuilderInterface;
use ConfigurationCdek;

abstract class AbstractCart extends ComponentCart
{
    protected $lang;
    protected $id_address_delivery;
    
    public function getIdAddressDelivery()
    {
        return (int)$this->id_address_delivery;
    }
    
    public function getLang()
    {
        return $this->lang;
    }
    
    final public function setPackageBuilder(PackagesBuilderInterface $packages_builder = null)
    {
        if (!is_null($packages_builder)) {
            $packages_builder = $packages_builder;
        } elseif (ConfigurationCdek::get('one_package')) {
            $packages_builder = new OnePackageBuilder($this);
        } elseif (ConfigurationCdek::get('all_is_one_package')) {
            $packages_builder = new AllIsOnePackageBuilder($this);
        } elseif (ConfigurationCdek::get('all_one_box')) {
            $packages_builder = new AllOneBoxBuilder($this);
        } elseif (ConfigurationCdek::get('one_package') && ConfigurationCdek::get('all_one_box')) {
            $packages_builder = new OnePackageAllOneBoxBuilder($this);
        } elseif (ConfigurationCdek::get('all_is_one_package') && ConfigurationCdek::get('all_one_box')) {
            $packages_builder = new AllIsOnePackageBuilder($this);
        } else {
            $packages_builder = new ByNameBuilder($this);
        }

        $this->package_builder = $packages_builder;
    }
}