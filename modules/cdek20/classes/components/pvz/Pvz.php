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

namespace Seleda\Cdek\Component\Pvz;

use \LangCdek;

class Pvz
{
    public function __construct($type, $city, $lang)
    {
        $this->type = $type;
        $this->city = $city;
        $this->lang = $lang;
    }

    public static function getMaxPostamat($city_code)
    {
        return Db::getInstance()->getRow('SELECT `depth`, `width`, `height` FROM `'.Db::getPrefix().'cdek_pvz`
            WHERE `CityCode` = '.(int)$city_code.' AND `type` = "POSTAMAT" ORDER BY `width`, `height`, `depth` DESC');
    }

    public static function getDimensions($code)
    {
        return Db::getInstance()->getRow('SELECT `depth`, `width`, `height` FROM `'.Db::getPrefix().'cdek_pvz`
            WHERE `code` = '.(int)$code.' ORDER BY `width`, `height`, `depth` DESC');
    }

    public static function loadPoints()
    {
        $params = array();
        $params['type'] = 'ALL';

        $res = array();
        $client = Client::getInstance();
        foreach (LangCdek::getLanguages() as $key => $language) {
            $cdek_lang = LangCdek::getInstance($language['id_lang']);

            if (array_key_exists($cdek_lang->getLang(), $res)) {
                continue;
            }

            $params['lang'] = $cdek_lang->getLang();

            if ($client->getPvz($params)) {
                $result = $client->getResult();
                $res[$cdek_lang->getLang()] = $result;
            } else {
                return false;
            }
        }

        if ($res) {
            Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_pvz`');
            Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'cdek_pvz_lang`');
        }

        $i = 0;
        foreach ($res as $lang => $item) {
            $item = json_decode($item);
            if (is_array($item)) {
                foreach ($item as $pvz) {
                    $pvz = self::adapter15($pvz);
                    if (!self::savePvz($pvz, $lang, $i > 0)) {
                        return false;
                    }
                }
            }
            $i++;
        }

        return true;
    }

    public static function adapter15($item)
    {
        $phone = '';
        if (isset($item->phones)) {
            foreach ($item->phones as $key2 => $one_phone) {
                $phone .= ($key2 ? ', ' : '') . $one_phone->number;
            }
        }
        if (isset($item->office_image_list)) {
            $picture = array();
            foreach ($item->office_image_list as $one_picture) {
                $picture[] = 'https:' . substr($one_picture->url, strpos($one_picture->url, '/'));
            }
            $picture = json_encode($picture);
        } else {
            $picture = '';
        }

        list($width, $height, $depth) = array(500, 500, 500);
        if (isset($item->dimensions)) {
            $max_volume = 0;
            foreach ($item->dimensions as $dimension) {
                $volume = $dimension->width * $dimension->height * $dimension->depth;
                if ($volume > $max_volume) {
                    $max_volume = $volume;
                    $width = $dimension->width;
                    $height = $dimension->height;
                    $depth = $dimension->depth;
                }
            }
        }

        $result = array(
            'code' => $item->code,
            'Phone' => $phone,
            'cX' => $item->location->longitude,
            'cY' => $item->location->latitude,
            'Dressing' => isset($item->is_dressing_room) ? $item->is_dressing_room : 0,
            'Cash' => $item->have_cashless,
            'CityCode' => $item->location->city_code,
            'Picture' => $picture,
            'weight_min' => isset($item->weight_min) ? $item->weight_min : 0,
            'weight_max' => isset($item->weight_max) ? $item->weight_max : 0,
            'type' => $item->type,
            'Name' => isset($item->name) ? $item->name : '',
            'WorkTime' => isset($item->work_time) ? $item->work_time : '',
            'Address' => $item->location->address,
            'Note' => isset($item->note) ? $item->note : '',
            'Station' => isset($item->nearest_station) ? $item->nearest_station : '',
            'Site' => isset($item->site) ? $item->site : '',
            'Metro' => isset($item->nearest_metro_station) ? $item->nearest_metro_station : '',
            'AddressComment' => isset($item->address_comment) ? $item->address_comment : '',
            'width' => $width,
            'height' => $height,
            'depth' => $depth
        );

        return $result;
    }

    public static function savePvz($pvz, $lang, $only_lang = false)
    {
        if (!$only_lang) {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'cdek_pvz` (`code`, `Phone`, `cX`, `cY`, `Dressing`, `Cash`, `CityCode`, `Picture`, `weight_min`, `weight_max`, `width`, `height`, `depth`, `type`)
                    VALUES ("'
                . pSQL($pvz['code']) . '", 
                        "' . pSQL($pvz['Phone']) . '", 
                        "' . pSQL($pvz['cX']) . '", 
                        "' . pSQL($pvz['cY']) . '", '
                . (int)$pvz['Dressing'] . ', '
                . (int)$pvz['Cash'] . ', '
                . (int)$pvz['CityCode'] . ',
                    \'' . pSQL($pvz['Picture']) . '\', '
                . (float)$pvz['weight_min'] . ', '
                . (float)$pvz['weight_max'] . ', '
                . (float)$pvz['width'] . ', '
                . (float)$pvz['height'] . ', '
                . (float)$pvz['depth'] . ',
                    "' . pSQL($pvz['type']) . '")';
            if (!Db::getInstance()->execute($sql)) {
                return false;
            }
        }

        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'cdek_pvz_lang` (`code`, `lang`, `Name`, `WorkTime`, `Address`, `Note`, `Station`, `Site`, `Metro`, `AddressComment`)
            VALUES ("'
                .pSQL($pvz['code']).'", 
                "'.pSQL($lang).'", 
                "'.pSQL($pvz['Name']).'", 
                "'.pSQL($pvz['WorkTime']).'", 
                "'.pSQL($pvz['Address']).'", 
                "'.pSQL($pvz['Note']).'", 
                "'.pSQL($pvz['Station']).'", 
                "'.pSQL($pvz['Site']).'", 
                "'.pSQL($pvz['Metro']).'", 
                "'.pSQL($pvz['AddressComment']).'")';
        return Db::getInstance()->execute($sql);
    }

    public static function getReserveCity($city_code, $type)
    {
        $radius = 120; // мили. 200 км

        $city = Db::getInstance()->getRow('SELECT * FROM `'.Db::getPrefix().'cdek_city` WHERE `code` = '.(int)$city_code);
        $pvz_in_radius = Db::getInstance()->executeS('SELECT * FROM `'.Db::getPrefix().'cdek_pvz` 
        WHERE `type` = "'.Db::_escape(strtoupper($type)).'" AND `cX` BETWEEN '.(float)((float)$city['longitude'] - $radius/42.5).' AND '.(float)((float)$city['longitude'] + $radius/42.5).' AND
        `cY` BETWEEN '.(float)((float)$city['latitude'] - $radius/69.0).' AND '.(float)((float)$city['latitude'] + $radius/69.0));

        $min = $nearest_city_code = false;
        foreach ($pvz_in_radius as $pvz) {
            $square_hypotenuse = pow($city['longitude'] - $pvz['cX'], 2) + pow($city['latitude'] - $pvz['cY'], 2);
            if (!$min || $min > $square_hypotenuse) {
                $min = $square_hypotenuse;
                $nearest_city_code = $pvz['CityCode'];
            }
        }

        return $nearest_city_code ? $nearest_city_code : $city_code;
    }

    public static function getPvzAddress($pvz, $lang = 'rus')
    {
        return Db::getInstance()->getValue('SELECT `Address` FROM `'._DB_PREFIX_.'cdek_pvz_lang` WHERE `code` = "'.Db::escape($pvz).'" AND `lang` = "'.Db::escape($lang).'"');
    }
}
