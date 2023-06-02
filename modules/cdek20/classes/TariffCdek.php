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
class TariffCdek extends ObjectModel
{
    const DOOR_DOOR = 1;
    const DOOR_WAREHOUSE = 2;
    const WAREHOUSE_DOOR = 3;
    const WAREHOUSE_WAREHOUSE = 4;
    const WAREHOUSE_POSTAMAT = 6;
    const DOOR_POSTAMAT = 7;

    public $tariff;
    public $mode;
    public $range_min;
    public $range_max;
    public $name_rus;
    public $name_eng;
    public $active;
    public $position;

    public static $definition = array(
        'table' => 'cdek_tariff',
        'primary' => 'id_cdek_tariff',
        'fields' => array(
            'tariff' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'copy_post' => false),
            'mode' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'copy_post' => false),
            'range_min' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true, 'copy_post' => false),
            'range_max' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true, 'copy_post' => false),
            'name_rus' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'name_eng' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'copy_post' => false)
        ),
    );

    public function getTextMode($lang)
    {
        switch ($this->mode) {
            case TariffCdek::WAREHOUSE_WAREHOUSE:
                return  $lang == 'rus' ? 'Склад-склад' : 'Warehouse-warehouse';
            case TariffCdek::WAREHOUSE_DOOR:
                return  $lang == 'rus' ? 'Склад-дверь' : 'Warehouse-door';
            case TariffCdek::DOOR_DOOR:
                return  $lang == 'rus' ? 'Дверь-дверь' : 'Door-door';
            case TariffCdek::DOOR_WAREHOUSE:
                return  $lang == 'rus' ? 'Дверь-склад' : 'Door-warehouse';
            case TariffCdek::DOOR_POSTAMAT:
                return  $lang == 'rus' ? 'Дверь-постамат' : 'Door-postamat';
            case TariffCdek::WAREHOUSE_POSTAMAT:
                return  $lang == 'rus' ? 'Склад-постамат' : 'Warehouse-postamat';
            default:
                return '';
        }
    }

    public static function getModeByTariff($tatiff)
    {
        return Db::getInstance()->getValue(
            'SELECT `mode` FROM `'._DB_PREFIX_.'cdek_tariff` WHERE `tariff` = '.(int)$tatiff
        );
    }

    public static function getTypeByIdCarrier($id_carrier)
    {
        return Db::getInstance()->getValue('SELECT ct.`type` FROM `'._DB_PREFIX_.'cdek_carrier_type` ct
            LEFT JOIN `'._DB_PREFIX_.'carrier` c ON (ct.`carrier_reference` = c.`id_reference` AND c.`external_module_name` = "cdek20")
            WHERE c.`id_carrier` = '.(int)$id_carrier);
    }

    public static function getTypeByMode($mode)
    {
        if (in_array($mode, array(self::DOOR_DOOR, self::WAREHOUSE_DOOR))) {
            return 'courier';
        } elseif (in_array($mode, array(self::WAREHOUSE_WAREHOUSE, self::DOOR_WAREHOUSE))) {
            return 'pickup';
        } else {
            return 'postamat';
        }
    }

    public static function getTariffsByType($type, $active = false)
    {
        if ($type == 'pickup') {
            $types = array(TariffCdek::DOOR_WAREHOUSE, TariffCdek::WAREHOUSE_WAREHOUSE);
        } elseif ($type == 'courier') {
            $types = array(TariffCdek::DOOR_DOOR, TariffCdek::WAREHOUSE_DOOR);
        } elseif ($type == 'postamat') {
            $types = array(TariffCdek::WAREHOUSE_POSTAMAT, TariffCdek::DOOR_POSTAMAT);
        }

        $tariffs = array();
        $res = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'cdek_tariff` WHERE `mode` IN('.pSQL(implode(',', $types)).')'.($active ? ' AND `active` = 1' : '').' ORDER BY `position` ASC');
        foreach ($res as $val) {
            $tariffs[$val['tariff']] = new TariffCdek($val['id_cdek_tariff']);
        }
        return $tariffs;
    }

    public function getType()
    {
        if (in_array($this->mode, array(TariffCdek::DOOR_WAREHOUSE, TariffCdek::WAREHOUSE_WAREHOUSE))) {
            return 'pickup';
        } elseif (in_array($this->mode, array(TariffCdek::DOOR_DOOR, TariffCdek::WAREHOUSE_DOOR))) {
            return 'courier';
        } elseif (in_array($this->mode, array(TariffCdek::WAREHOUSE_POSTAMAT, TariffCdek::DOOR_POSTAMAT))) {
            return 'postamat';
        }
        return false;
    }

    public static function getTariffsStatic()
    {
        return array(
            array('id' => 136,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Посылка склад-склад',
                    'en' => 'Making a warehouse-warehouse'
                )
            ),
            array('id' => 137,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Посылка склад-дверь',
                    'en' => 'Making a warehouse-door'
                )
            ),
            array('id' => 138,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Посылка дверь-склад',
                    'en' => 'Making a door-warehouse'
                )
            ),
            array('id' => 139,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Посылка дверь-дверь',
                    'en' => 'Package door-to-door'
                )
            ),
            array('id' => 231,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичная посылка дверь-дверь',
                    'en' => 'Economy door-to-door package'
                )
            ),
            array('id' => 232,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичная посылка дверь-склад',
                    'en' => 'Economy package door-warehouse'
                )
            ),
            array('id' => 233,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичная посылка склад-дверь',
                    'en' => 'Economy package warehouse-door'
                )
            ),
            array('id' => 234,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичная посылка склад-склад',
                    'en' => 'Economic parcel warehouse-warehouse'
                )
            ),
            array('id' => 291,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'CDEK Express склад-склад',
                    'en' => 'CDEK Express warehouse-warehouse'
                )
            ),
            array('id' => 293,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'CDEK Express дверь-дверь',
                    'en' => 'CDEK Express door-to-door'
                )
            ),
            array('id' => 294,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'CDEK Express склад-дверь',
                    'en' => 'CDEK Express warehouse-door'
                )
            ),
            array('id' => 295,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'CDEK Express дверь-склад',
                    'en' => 'CDEK Express door-warehouse'
                )
            ),
            array('id' => 243,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Китайский экспресс склад-склад',
                    'en' => 'China Express warehouse-warehouse'
                )
            ),
            array('id' => 245,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Китайский экспресс дверь-дверь',
                    'en' => 'China Express door-door'
                )
            ),
            array('id' => 246,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Китайский экспресс склад-дверь',
                    'en' => 'China Express warehouse-door'
                )
            ),
            array('id' => 247,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Китайский экспресс дверь-склад',
                    'en' => 'China Express door-warehouse'
                )
            ),
            array('id' => 3,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18',
                    'en' => 'Super Express 18'
                )
            ),
            array('id' => 57,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 9 дверь-дверь',
                    'en' => 'Super Express to 9 door-door'
                )
            ),
            array('id' => 58,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 10 дверь-дверь',
                    'en' => 'Super Express to 10 door-door'
                )
            ),
            array('id' => 59,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12 дверь-дверь',
                    'en' => 'Super Express to 12 door-door'
                )
            ),
            array('id' => 60,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14 дверь-дверь',
                    'en' => 'Super Express to 14 door-door'
                )
            ),
            array('id' => 66,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 15.000000
                ),
                'name' => array(
                    'ru' => 'Доставка за 4 часа внутри города пешие',
                    'en' => 'Delivery within 4 hours within the city on foot'
                )
            ),
            array('id' => 67,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 15.000000
                ),
                'name' => array(
                    'ru' => 'Доставка за 4 часа МСК-МО МО-МСК пешие',
                    'en' => 'Delivery in 4 hours MSK-MO MO-MSK on foot'
                )
            ),
            array('id' => 68,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 15.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Доставка за 4 часа внутри города авто',
                    'en' => 'Delivery within 4 hours within the city auto'
                )
            ),
            array('id' => 69,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 15.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Доставка за 4 часа МСК-МО МО-МСК авто',
                    'en' => 'Delivery in 4 hours MSK-MO MO-MSK auto'
                )
            ),
            array('id' => 61,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16 дверь-дверь',
                    'en' => 'Super Express to 16 door-door'
                )
            ),
            array('id' => 777,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12 дверь-склад',
                    'en' => 'Super Express to 12 door-warehouse'
                )
            ),
            array('id' => 786,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14 дверь-склад',
                    'en' => 'Super Express to 14 door-warehouse'
                )
            ),
            array('id' => 795,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16 дверь-склад',
                    'en' => 'Super Express to 16 door-warehouse'
                )
            ),
            array('id' => 804,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18 дверь-склад',
                    'en' => 'Super Express to 18 door-warehouse'
                )
            ),
            array('id' => 778,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12 склад-дверь',
                    'en' => 'Super Express to 12 warehouse-door'
                )
            ),
            array('id' => 787,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14 склад-дверь',
                    'en' => 'Super Express to 14 warehouse-door'
                )
            ),
            array('id' => 796,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16 склад-дверь',
                    'en' => 'Super Express to 16 warehouse-door'
                )
            ),
            array('id' => 805,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18 склад-дверь',
                    'en' => 'Super Express to 18 warehouse-door'
                )
            ),
            array('id' => 779,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12 склад-склад',
                    'en' => 'Super Express to 12 warehouse-warehouse'
                )
            ),
            array('id' => 788,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14 склад-склад',
                    'en' => 'Super Express to 14 warehouse-warehouse'
                )
            ),
            array('id' => 797,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16 склад-склад',
                    'en' => 'Super Express to 16 warehouse-warehouse'
                )
            ),
            array('id' => 806,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18 склад-склад',
                    'en' => 'Super Express to 18 warehouse-warehouse'
                )
            ),
            array('id' => 184,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Международный экономичный экспресс дверь-дверь',
                    'en' => 'International Economy Express door-door'
                )
            ),
            array('id' => 185,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Международный экономичный экспресс склад-склад',
                    'en' => 'International Economy Express warehouse-warehouse'
                )
            ),
            array('id' => 186,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Международный экономичный экспресс склад-дверь',
                    'en' => 'International Economy Express warehouse-door'
                )
            ),
            array('id' => 187,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Международный экономичный экспресс дверь-склад',
                    'en' => 'International Economy Express door-warehouse'
                )
            ),
            array('id' => 58,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 5.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 10',
                    'en' => 'Super Express 10'
                )
            ),
            array('id' => 59,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 5.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12',
                    'en' => 'Super Express 12'
                )
            ),
            array('id' => 60,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 5.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14',
                    'en' => 'Super Express 14'
                )
            ),
            array('id' => 61,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16',
                    'en' => 'Super Express 16'
                )
            ),
            array('id' => 62,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный экспресс склад-склад',
                    'en' => 'Bulk Express warehouse-warehouse'
                )
            ),
            array('id' => 63,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный супер-экспресс склад-склад',
                    'en' => 'Super-rapid Bulk storage warehouse'
                )
            ),
            array('id' => 8,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Международный экспресс грузы дверь-дверь',
                    'en' => 'International express freight (door-door)'
                )
            ),
            array('id' => 7,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 5.000000
                ),
                'name' => array(
                    'ru' => 'Международный экспресс документы дверь-дверь',
                    'en' => 'International express documents (door-door)'
                )
            ),
            array('id' => 361,
                'mode' => TariffCdek::DOOR_POSTAMAT,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс лайт дверь-постамат',
                    'en' => 'Express door postamat light'
                )
            ),
            array('id' => 363,
                'mode' => TariffCdek::WAREHOUSE_POSTAMAT,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичная посылка склад-постамат',
                    'en' => 'Economy Package warehouse postamat'
                )
            ),
            array('id' => 366,
                'mode' => TariffCdek::DOOR_POSTAMAT,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Посылка дверь-постамат',
                    'en' => 'Making a door-to-postamat'
                )
            ),
            array('id' => 368,
                'mode' => TariffCdek::WAREHOUSE_POSTAMAT,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Посылка склад-постамат',
                    'en' => 'Making a warehouse-to-postamat'
                )
            ),
            array('id' => 118,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичный экспресс дверь-дверь',
                    'en' => 'Parcel depot door-to-postamat'
                )
            ),
            array('id' => 119,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичный экспресс склад-дверь',
                    'en' => 'Parcel depot warehouse-to-postamat'
                )
            ),
            array('id' => 120,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экономичный экспресс дверь-склад',
                    'en' => 'Parcel depot door-to-postamat'
                )
            ),
            array('id' => 121,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный экспресс дверь-дверь',
                    'en' => 'Trunk express door-door'
                )
            ),
            array('id' => 122,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный экспресс склад-дверь',
                    'en' => 'Trunk express warehouse-door'
                )
            ),
            array('id' => 123,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный экспресс дверь-склад',
                    'en' => 'Trunk express door-warehouse'
                )
            ),
            array('id' => 124,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный супер-экспресс дверь-дверь',
                    'en' => 'Trunk super express door-to-door'
                )
            ),
            array('id' => 125,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный супер-экспресс склад-дверь',
                    'en' => 'Parcel depot door-to-postamat'
                )
            ),
            array('id' => 126,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Магистральный супер-экспресс дверь-склад',
                    'en' => 'Parcel depot door-to-postamat'
                )
            ),
            array('id' => 533,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 0.300000
                ),
                'name' => array(
                    'ru' => 'СДЭК документы дверь-дверь',
                    'en' => 'SDEK documents door-to-door'
                )
            ),
            array('id' => 534,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 0.300000
                ),
                'name' => array(
                    'ru' => 'СДЭК документы дверь-склад',
                    'en' => 'SDEK documents door-warehouse'
                )
            ),
            array('id' => 535,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 0.300000
                ),
                'name' => array(
                    'ru' => 'СДЭК документы склад-дверь',
                    'en' => 'SDEK documents warehouse-door'
                )
            ),
            array('id' => 536,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 0.300000
                ),
                'name' => array(
                    'ru' => 'СДЭК документы склад-склад',
                    'en' => 'SDEK documents warehouse-warehouse'
                )
            ),
            array('id' => 480,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс дверь-дверь',
                    'en' => 'Express door-to-door'
                )
            ),
            array('id' => 481,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс дверь-склад',
                    'en' => 'Express door-warehouse'
                )
            ),
            array('id' => 482,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс склад-дверь',
                    'en' => 'Parcel depot door-to-postamat'
                )
            ),
            array('id' => 483,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 100500.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс склад-склад',
                    'en' => 'Express warehouse-door'
                )
            ),
            array('id' => 485,
                'mode' => TariffCdek::DOOR_POSTAMAT,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс дверь-постамат',
                    'en' => 'Express door-postamat'
                )
            ),
            array('id' => 751,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 20000.000000
                ),
                'name' => array(
                    'ru' => 'Сборный груз склад-склад',
                    'en' => 'Consolidated cargo warehouse-warehouse'
                )
            ),
            array('id' => 676,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 10.00 дверь-дверь',
                    'en' => 'Super Express until 10.00 door-door'
                )
            ),
            array('id' => 677,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 10.00 дверь-склад',
                    'en' => 'Super Express until 10.00 door-warehouse'
                )
            ),
            array('id' => 678,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 10.00 склад-дверь',
                    'en' => 'Super Express until 10.00 warehouse-door'
                )
            ),
            array('id' => 679,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 10.00 склад-склад',
                    'en' => 'Super Express until 10.00 warehouse-warehouse'
                )
            ),
            array('id' => 686,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12.00 дверь-дверь',
                    'en' => 'Super Express until 12.00 door-door'
                )
            ),
            array('id' => 687,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12.00 дверь-склад',
                    'en' => 'Super Express until 12.00 door-warehouse'
                )
            ),
            array('id' => 688,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12.00 склад-дверь',
                    'en' => 'Super Express until 12.00 warehouse-door'
                )
            ),
            array('id' => 689,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 12.00 склад-склад',
                    'en' => 'Super Express until 12.00 warehouse-warehouse'
                )
            ),
            array('id' => 696,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14.00 дверь-дверь',
                    'en' => 'Super Express until 14.00 door-door'
                )
            ),
            array('id' => 697,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14.00 дверь-склад',
                    'en' => 'Super Express until 14.00 door-warehouse'
                )
            ),
            array('id' => 698,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14.00 склад-дверь',
                    'en' => 'Super Express until 14.00 warehouse-door'
                )
            ),
            array('id' => 699,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 14.00 склад-склад',
                    'en' => 'Super Express until 14.00 warehouse-warehouse'
                )
            ),
            array('id' => 706,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16.00 дверь-дверь',
                    'en' => 'Super Express until 16.00 door-door'
                )
            ),
            array('id' => 707,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16.00 дверь-склад',
                    'en' => 'Super Express until 16.00 door-warehouse'
                )
            ),
            array('id' => 708,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16.00 склад-дверь',
                    'en' => 'Super Express until 16.00 warehouse-door'
                )
            ),
            array('id' => 709,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 16.00 склад-склад',
                    'en' => 'Super Express until 16.00 warehouse-warehouse'
                )
            ),
            array('id' => 716,
                'mode' => TariffCdek::DOOR_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18.00 дверь-дверь',
                    'en' => 'Super Express until 18.00 door-door'
                )
            ),
            array('id' => 717,
                'mode' => TariffCdek::DOOR_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18.00 дверь-склад',
                    'en' => 'Super Express until 18.00 door-warehouse'
                )
            ),
            array('id' => 718,
                'mode' => TariffCdek::WAREHOUSE_DOOR,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18.00 склад-дверь',
                    'en' => 'Super Express until 18.00 warehouse-door'
                )
            ),
            array('id' => 719,
                'mode' => TariffCdek::WAREHOUSE_WAREHOUSE,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 30.000000
                ),
                'name' => array(
                    'ru' => 'Супер-экспресс до 18.00 склад-склад',
                    'en' => 'Super Express until 18.00 warehouse-warehouse'
                )
            ),
            array('id' => 486,
                'mode' => TariffCdek::WAREHOUSE_POSTAMAT,
                'range' => array(
                    'min' => 0.000000,
                    'max' => 50.000000
                ),
                'name' => array(
                    'ru' => 'Экспресс склад-постамат',
                    'en' => 'Express warehouse postamat'
                )
            )
        );
    }
}
