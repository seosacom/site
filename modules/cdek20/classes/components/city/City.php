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

namespace Seleda\Cdek\Component\City;

use \LangCdek;

class City
{
    protected $postal_code;

    protected $country_code;

    protected $table;

    protected $code;

    protected $city;

    public function getCode()
    {
        if ($this->code) {
            return $this->code;
        }
        if (!$this->postal_code && !$this->city) {
            return null;
        }
//        $city_code = $this->getCodeBase();
//        if ($city_code == false) {
//            $this->loadCityApi(1);
//            $city_code = $this->getCodeBase();
//        }
        $city_code = false;
        if (!$city_code && $this->city) {
            $city_code = $this->getCodeByCity();
        }
        $this->code = (int)$city_code;

        return $city_code;
    }

    private function getCodeBase()
    {
        $postal_code = $this->getPostalCode();
        if (!$postal_code) {
            return false;
        }
        return self::getCodeByPostalCodeStatic($postal_code, $this->getCountryCode());
    }

    private function getCodeByCity()
    {
        return Db::getInstance()->getValue('SELECT `code` FROM `'.$this->table.'_lang`
         WHERE `city` = "'.pSQL(trim($this->city)).'"');
    }

    public static function loadCities($page = 0, $size = 1, $country_code = null)
    {
        $obj = new City();
        $obj->setCountryCode($country_code);
        return $obj->loadCityApi($size, $page);
    }

    protected function loadCityApi($size = 1000, $page = 0)
    {
        $params = array();
        $params['country_codes'] = $this->country_code;
        $params['postal_code'] = $this->postal_code;
        $params['size'] = (int)$size;
        $params['page'] = (int)$page;

        $res = array();
        $client = Client::getInstance();
        foreach (LangCdek::getLanguages() as $key => $language) {
            $cdek_lang = LangCdek::getInstance($language['id_lang']);

            if (array_key_exists($cdek_lang->getLang(), $res)) {
                continue;
            }

            $params['lang'] = $cdek_lang->getLang();
            if ($client->getCity($params)) {
                $result = $client->getResult();
                if ($result == '[]') {
                    return false;
                }
                $res[$cdek_lang->getLang()] = $result;
            } else {
                return false;
            }
        }

        $i = 0;
        foreach ($res as $lang => $item) {
            $item = json_decode($item);
            if (is_array($item)) {
                foreach ($item as $city) {
                    if (!self::saveCity($city, $lang, $i > 0)) {
                        return false;
                    }
                }
            }
            $i++;
        }

        return true;
    }

    private static function saveCity($city, $lang, $only_lang = false)
    {
        if (is_array($city)) {
            $city = (object)$city;
        } elseif (is_string($city)) {
            $city = json_decode($city);
        }

        $city = array(
            'code'      => $city->code,
            'city'    => property_exists($city, 'city') ? $city->city : '',
            'region'  => property_exists($city, 'region') ? $city->region : '',
            'country' => property_exists($city, 'country') ? $city->country : '',
            'longitude' => property_exists($city, 'longitude') ? $city->longitude : '',
            'latitude' => property_exists($city, 'latitude') ? $city->latitude : '',
            'country_code' => $city->country_code,
            'postal_codes' => property_exists($city, 'postal_codes') ? $city->postal_codes : array(),
            'payment_limit' => property_exists($city, 'payment_limit') ? $city->payment_limit : -1,
            'fias_guid' => property_exists($city, 'fias_guid') ? $city->fias_guid : '',
            'region_code' => property_exists($city, 'region_code') ? $city->region_code : '',
            'sub_region' => property_exists($city, 'sub_region') ? $city->sub_region : '',
            'kladr_code' => property_exists($city, 'kladr_code') ? $city->kladr_code : '',
            'kladr_region_code' => property_exists($city, 'kladr_region_code') ? $city->kladr_region_code : '',
            'fias_region_guid' => property_exists($city, 'fias_region_guid') ? $city->fias_region_guid : '',
            'time_zone' => property_exists($city, 'time_zone') ? $city->time_zone : ''
        );

        $return = true;

        $sql = array();
        if (!$only_lang) {
            $sql[] = 'INSERT INTO `' . _DB_PREFIX_ . 'cdek_city` (
                    `code`, 
                    `postal_codes`, 
                    `country_code`, 
                    `fias_guid`, 
                    `kladr_code`, 
                    `region_code`, 
                    `kladr_region_code`, 
                    `fias_region_guid`, 
                    `longitude`, 
                    `latitude`, 
                    `time_zone`, 
                    `payment_limit`) 
                    VALUES (
                    ' . (int)$city['code'] . ', 
                    "' . pSQL(implode(',', $city['postal_codes'])) . '", 
                    "' . pSQL($city['country_code']) . '",
                    "' . pSQL($city['fias_guid']) . '", 
                    "' . pSQL($city['kladr_code']) . '", 
                    "' . pSQL($city['region_code']) . '", 
                    "' . pSQL($city['kladr_region_code']) . '", 
                    "' . pSQL($city['fias_region_guid']) . '", 
                    "' . pSQL($city['longitude']) . '", 
                    "' . pSQL($city['latitude']) . '", 
                    "' . pSQL($city['time_zone']) . '", 
                    ' . pSQL($city['payment_limit']) . ') 
                    ON DUPLICATE KEY UPDATE postal_codes = "' . pSQL(isset($city['postal_codes']) ? implode(',', $city['postal_codes']) : null) . '"';
        }
        $sql[] = 'INSERT INTO `'._DB_PREFIX_.'cdek_city_lang` (
                    `code`, 
                    `lang`, 
                    `country`, 
                    `region`, 
                    `sub_region`, 
                    `city`) 
                    VALUES (
                    '.(int)$city['code'].', 
                    "'.pSQL($lang).'", 
                    "'.pSQL($city['country']).'",
                    "'.pSQL($city['region']).'", 
                    "'.pSQL($city['sub_region']).'", 
                    "'.pSQL($city['city']).'") 
                    ON DUPLICATE KEY UPDATE 
                    country = "'.pSQL($city['country']).'",
                    region = "'.pSQL($city['region']).'",
                    sub_region = "'.pSQL($city['sub_region']).'",
                    city = "'.pSQL($city['city']).'"';

        foreach ($sql as $item_sql) {
            $return &= Db::getInstance()->execute($item_sql);
        }

        return $return;
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

    public function getCity($lang = 'rus')
    {
        return self::getCityByCodeStatic($this->getCode(), $lang);
    }

    public static function getCityByCodeStatic($code, $lang = 'rus')
    {
        return Db::getInstance()->getValue('SELECT `city` FROM `'._DB_PREFIX_.'cdek_city_lang` WHERE `code` = '.(int)$code.' AND `lang` = "'.Db::escape($lang).'"');
    }

    public static function getCodeByPostalCodeStatic($postal_code, $country_code)
    {
        return Db::getInstance()->getValue('SELECT `code` FROM `'._DB_PREFIX_.'cdek_city` WHERE `postal_codes` LIKE "%'.pSQL($postal_code).'%" 
         AND `country_code` = "'.pSQL($country_code).'"');
    }

    public function getCountryCode()
    {
        return $this->country_code;
    }

    public function setCountryCode($val)
    {
        $this->country_code = $val;
        return $this;
    }
}
