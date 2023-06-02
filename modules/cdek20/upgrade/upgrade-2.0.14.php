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

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * This function updates your module from previous versions to the version 1.1,
 * usefull when you modify your database, or register a new hook ...
 * Don't forget to create one file per version.
 */
function upgrade_module_2_0_14($module)
{
    $res = true;
    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'cdek_configuration`');
    if (is_array($list_fields)) {
        foreach ($list_fields as $k => &$field) {
            $field = $field['Field'];
        }
        if (!in_array('id_cart', $list_fields) && $res) {
            $res = Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'cdek_calculator` ADD `id_cart` INT(11) NOT NULL FIRST;');
        }
        $sql = array();
        $sql[] = 'TRUNCATE TABLE `'._DB_PREFIX_.'cdek_calculator`';
        $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'cdek_calculator` DROP PRIMARY KEY;';
        $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'cdek_calculator` ADD PRIMARY KEY (`id_cart`);';
        $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'cdek_calculator` ADD UNIQUE `id_cart_unique` (`id_cart`);';
        foreach ($sql as $query) {
            $res &= Db::getInstance()->execute($query);
        }
    }
    return $res;
}
