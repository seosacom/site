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
use Seleda\Cdek\Component\City\IDb as CityDb;
use Seleda\Cdek\Component\Calculator\IDb as CalculatorDb;
use Seleda\Cdek\Component\Pvz\IDb as PvzDb;
class DbCdek implements CityDb, CalculatorDb, PvzDb
{
    public static function getInstance()
    {
        return Db::getInstance();
    }

    public static function getPrefix()
    {
        return _DB_PREFIX_;
    }

    public function getValue($query)
    {
        return Db::getInstance()->getValue($query);
    }

    public function getRow($query)
    {
        return Db::getInstance()->getRow($query);
    }

    public function execute($query)
    {
        return Db::getInstance()->execute($query);
    }

    public function executeS($query)
    {
        return Db::getInstance()->executeS($query);
    }

    public static function escape($string, $html_ok = false, $bq_sql = false)
    {
        if (_PS_MAGIC_QUOTES_GPC_) {
            $string = stripslashes($string);
        }

        if (!is_numeric($string)) {
            $string = self::_escape($string);

            if (!$html_ok) {
                $string = strip_tags(self::nl2br($string));
            }

            if ($bq_sql === true) {
                $string = str_replace('`', '\`', $string);
            }
        }

        return $string;
    }

    public static function _escape($string)
    {
        $search = array('\\', "\0", "\n", "\r", "\x1a", "'", '"');
        $replace = array('\\\\', '\\0', '\\n', '\\r', "\Z", "\'", '\"');

        return str_replace($search, $replace, $string);
    }

    public static function nl2br($string)
    {
        return str_replace(array("\r\n", "\r", "\n", "\n", PHP_EOL), '<br />', $string);
    }
}
