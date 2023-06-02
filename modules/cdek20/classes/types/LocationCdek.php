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
class LocationCdek extends AbstractTypeCdek
{
    protected $code;
    protected $fias_guid;
    protected $postal_code;
    protected $longitude;
    protected $latitude;
    protected $country_code;
    protected $region;
    protected $region_code;
    protected $sub_region;
    protected $city;
    protected $kladr_code;
    protected $address;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($val)
    {
        $this->code = $val;
        return $this;
    }

    public function getPostalCode()
    {
        return $this->postal_code;
    }

    public function setPostalCode($val)
    {
        $this->postal_code = $val;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($val)
    {
        $this->city = $val;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($val)
    {
        $this->address = $val;
        return $this;
    }

    public function setRegionCode($val)
    {
        $this->region_code = $val;
        return $this;
    }

    public function getRegionCode()
    {
        return $this->region_code;
    }

    public function setFiasGuid($val)
    {
        $this->fias_guid = $val;
        return $this;
    }

    public function getFiasGuid()
    {
        return $this->fias_guid;
    }
}
