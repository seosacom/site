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
use Seleda\Cdek\Component\Pvz\Pvz;
class PvzCdek extends Pvz
{
    public static function getPVZForWidget($type, $lang, $weight)
    {
        if ($type == 'pickup') {
            $type = 'PVZ';
        }
        $type = strtoupper($type);
        $res = [];

        $sql = 'SELECT p.*, pl.*, cl.`country`, cl.`region`, cl.`city` FROM `' . _DB_PREFIX_ . 'cdek_pvz` p
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_pvz_lang` pl ON (p.`code` = pl.`code` AND pl.`lang` = "' . pSQL($lang) . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_city_lang` cl ON (p.`CityCode` = cl.`code` AND pl.`lang` = cl.`lang`)
        WHERE 1 AND cl.`city` IS NOT NULL' . ($type == 'ALL' ? '' : ' AND p.`type` = "' . pSQL($type) . '"') . ($weight ? (' AND (`weight_max` = 0 OR `weight_max` > ' . $weight . ')') : '');

        $pvz = Db::getInstance()->executeS($sql);

        if ($pvz) {
            foreach ($pvz as $row) {
                if ($row['Picture']) {
                    $row['Picture'] = json_decode($row['Picture'], true);
                }
                $res['PVZ'][$row['CityCode']][$row['code']] = $row;
                $res['CITY'][$row['CityCode']] = $row['city'];
                $res['CITYFULL'][$row['CityCode']] = $row['country'] . ', ' . $row['region'] . ', ' . $row['city'];
                $res['REGIONS'][$row['CityCode']] = $row['country'] . ', ' . $row['region'];
            }
        }

        return $res;
    }

    public static function getPvzDefault($type, $lang, $city_code, $weight = false)
    {
        if ($type == 'pickup') {
            $type = 'PVZ';
        }
        $type = strtoupper($type);

        $sql = 'SELECT p.`code` FROM `' . _DB_PREFIX_ . 'cdek_pvz` p
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_pvz_lang` pl ON (p.`code` = pl.`code` AND pl.`lang` = "' . pSQL($lang) . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_city_lang` cl ON (p.`CityCode` = cl.`code` AND pl.`lang` = cl.`lang`)
        WHERE p.`CityCode` = ' . (int)$city_code . ' AND cl.`city` IS NOT NULL' . ($type == 'ALL' ? '' : ' AND p.`type` = "' . pSQL($type) . '"') . ($weight ? (' AND (`weight_max` = 0 OR `weight_max` > ' . $weight . ')') : '');

        return Db::getInstance()->getValue($sql);
    }
}
