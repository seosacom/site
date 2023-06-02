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
class OrderState extends OrderStateCore
{
    /*
    * module: custompaymentmethod
    * date: 2023-04-17 10:17:10
    * version: 1.5.18
    */
    public static function getOrderStates($id_lang, $filterDeleted = true)
    {
        $deletedStates = $filterDeleted ? ' WHERE deleted = 0' : '';
        $cache_id = 'OrderState::getOrderStates_' . (int) $id_lang;
        $cache_id .= $filterDeleted ? '_filterDeleted' : '';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT *
            FROM `' . _DB_PREFIX_ . 'order_state` os
            LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = ' . (int) $id_lang . ')'
            . $deletedStates);
        Cache::store($cache_id, $result);
        foreach ($result as $key => $value) {
            $result[$key]['name'] = $value['id_order_state'] . ' ' . $value['name'];
        }
        return $result;
    }
}
