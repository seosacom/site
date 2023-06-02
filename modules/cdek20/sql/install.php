<?php
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_tariff` (
    `id_cdek_tariff` INT(11) NOT NULL AUTO_INCREMENT,
    `tariff` INT(11) NOT NULL,
    `mode` INT(11) NOT NULL,
    `range_min` DECIMAL(20,6) NOT NULL,
    `range_max` DECIMAL(20,6) NOT NULL,
    `name_rus` VARCHAR(255) NOT NULL,
    `name_eng` VARCHAR(255) NOT NULL,
    `active` INT(11) NOT NULL,
    `position` INT(11) NOT NULL,
    PRIMARY KEY  (`id_cdek_tariff`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_configuration` (
    `type_contract` INT(11) NOT NULL,
    `contract_currency` VARCHAR(3) NOT NULL,
    `sender_company` VARCHAR(255) NOT NULL,
    `sender_name` VARCHAR(255) NOT NULL,
    `seller_name` VARCHAR(255) NOT NULL,
    `sender_phone` VARCHAR(255) NOT NULL,
    `shipper_address` VARCHAR(255) NOT NULL,
    `vat` DECIMAL(20,6) NOT NULL,
    `account` VARCHAR(255) NOT NULL,
    `secure_password` VARCHAR(255) NOT NULL,
    `country_warehouse` VARCHAR(2) NOT NULL,
    `postal_code` VARCHAR(255) NOT NULL,
    `city_warehouse` VARCHAR(255) NOT NULL,
    `address_warehouse` VARCHAR(255) NOT NULL,
    `pvz_warehouse` VARCHAR(255) NOT NULL,
    `free_shipping_courier` INT(11) NOT NULL,
    `free_shipping_pickup` INT(11) NOT NULL,
    `free_shipping_postamat` INT(11) NOT NULL,
    `free_price_courier` INT(11) NOT NULL,
    `free_price_pickup` INT(11) NOT NULL,
    `free_price_postamat` INT(11) NOT NULL,
    `free_weight_courier` VARCHAR(255) NOT NULL,
    `free_weight_pickup` VARCHAR(255) NOT NULL,
    `free_weight_postamat` VARCHAR(255) NOT NULL,
    `weight_unit` DECIMAL(20,6) NOT NULL,
    `volume_unit` DECIMAL(20,6) NOT NULL,
    `default_weight` DECIMAL(20,6) NOT NULL,
    `default_length` DECIMAL(20,6) NOT NULL,
    `default_width` DECIMAL(20,6) NOT NULL,
    `default_height` DECIMAL(20,6) NOT NULL,
    `delay` INT(11) NOT NULL,
    `departure_time` VARCHAR(255) NOT NULL,
    `courier_start_time`  VARCHAR(255) NOT NULL,
    `end_time_for_courier`  VARCHAR(255) NOT NULL,
    `waiting_date_courier`   VARCHAR(255) NOT NULL,
    `total_correction` DECIMAL(20,6) NOT NULL,
    `type_correction` INT(1) NOT NULL DEFAULT 1,
    `product_price_reduction` DECIMAL(20,6) NOT NULL,
    `impact_percent_of_cart` DECIMAL(20,6) NOT NULL,
    `write_log` INT(11) NOT NULL,
    `part_deliv` INT(11) NOT NULL,
    `one_package` INT(2) NOT NULL,
    `all_is_one_package` INT(2) NOT NULL,
    `all_one_box` INT(2) NOT NULL,
    `map_api_key` VARCHAR(255) NOT NULL,
    `weight_allowance` VARCHAR(2) NOT NULL,
    PRIMARY KEY  (`type_contract`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_customer` (
    `id_customer` INT(11) NOT NULL,
    `city_courier` INT(11) NOT NULL,
    `city_pickup` INT(11) NOT NULL,
    `city_postamat` INT(11) NOT NULL,
    `pickup` VARCHAR(255) NOT NULL,
    `postamat` VARCHAR(255) NOT NULL,
    PRIMARY KEY  (`id_customer`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_category` (
    `id_category` INT(11) NOT NULL,
    `weight` INT(11) NOT NULL,
    `length` INT(11) NOT NULL,
    `width` INT(11) NOT NULL,
    `height` INT(11) NOT NULL,
    PRIMARY KEY  (`id_category`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_status` (
    `id_status` INT(11) NOT NULL,
    `create` int(1) NOT NULL,
    `delete` int(1) NOT NULL,
    `cod_ship` int(1) NOT NULL,
    `cod` int(1) NOT NULL,
    PRIMARY KEY  (`id_status`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_order` (
    `id_order` INT(11) NOT NULL,
    `entity` LONGTEXT NOT NULL,
    `requests` LONGTEXT NOT NULL,
    `related_entities` TEXT NOT NULL,
    `call_courier` TEXT NOT NULL,
    PRIMARY KEY  (`id_order`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_calculator` (
    `id_cart` INT(11),
    `weight` INT(11),
    `city_from` INT(11) NOT NULL,
    `city_to` INT(32) NOT NULL,
    `currency` INT(2) NOT NULL,
    `lang` VARCHAR(8) NOT NULL,
    `response` text NOT NULL,
    `date` varchar(32) NOT NULL,
    PRIMARY KEY  (`id_cart`),
    UNIQUE `id_cart_unique` (`id_cart`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_carrier_type` (
    `carrier_reference` int(11) NOT NULL,
    `type` varchar(32) NOT NULL,
    PRIMARY KEY  (`carrier_reference`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cdek_city` (
            `code` INT(11),
            `postal_codes` TEXT NOT NULL,
            `country_code` VARCHAR(3),
            `fias_guid` VARCHAR(255) NULL,
            `kladr_code` VARCHAR(30) NULL,
            `region_code` VARCHAR(30) NULL,
            `kladr_region_code` VARCHAR(30) NULL,
            `fias_region_guid` VARCHAR(255) NULL,
            `longitude`  VARCHAR(30) NULL,
            `latitude`  VARCHAR(30) NULL,
            `time_zone` VARCHAR(30) NULL,
            `payment_limit` DECIMAL(20,6) NULL,
            PRIMARY KEY  (`code`, `country_code`)
        )ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cdek_city_lang` (
            `code` INT(11),
            `lang` VARCHAR(3) NOT NULL,
            `country` VARCHAR(255),
            `region` VARCHAR(255) NULL,
            `sub_region` VARCHAR(255) NULL,
            `city` VARCHAR(255) NULL,
            PRIMARY KEY  (`code`, `lang`)
        )ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_pvz` (
    `code` VARCHAR(32) NOT NULL,
    `Phone` TEXT NOT NULL,
    `cX` VARCHAR(32) NOT NULL,
    `cY` VARCHAR(32) NOT NULL,
    `Dressing` INT(11) NOT NULL,
    `Cash` INT(11) NOT NULL,
    `CityCode` INT(11) NOT NULL,
    `Picture` TEXT NOT NULL,
    `weight_min` DECIMAL(10, 3) NOT NULL,
    `weight_max` DECIMAL(10, 3) NOT NULL,
    `width` DECIMAL(10, 3) NOT NULL,
    `height` DECIMAL(10, 3) NOT NULL,
    `depth` DECIMAL(10, 3) NOT NULL,
    `type` VARCHAR(32),
    PRIMARY KEY  (`code`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_pvz_lang` (
    `code` VARCHAR(32) NOT NULL,
    `lang` VARCHAR(8),
    `Name` TEXT NOT NULL,
    `WorkTime` TEXT NOT NULL,
    `Address` TEXT NOT NULL,
    `Note` TEXT NOT NULL,
    `Station` TEXT NOT NULL,
    `Site` TEXT NOT NULL,
    `Metro` TEXT NOT NULL,
    `AddressComment` TEXT NOT NULL,
    PRIMARY KEY  (`code`, `lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cdek_logger` (
    `id_cdek_logger` INT(10) NOT NULL AUTO_INCREMENT,
    `method` VARCHAR(32),
    `message` TEXT NOT NULL,
    `request` TEXT NOT NULL,
    `response` TEXT NOT NULL,
    `date_add` DATETIME NOT NULL,
    PRIMARY KEY  (`id_cdek_logger`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
