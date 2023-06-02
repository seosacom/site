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
use \Seleda\Cdek\Component\Calculator\ILang as CalculatorLang;
class LangCdek implements CalculatorLang
{
    private static $instance = array();

    private $lang;

    public function __construct($lang)
    {
        if (is_numeric($lang)) {
            $iso_code = Db::getInstance()->getValue('SELECT `iso_code` 
                FROM `' . _DB_PREFIX_ . 'lang` WHERE `id_lang` = ' . (int)$lang);
        } elseif ($lang instanceof Language) {
            $iso_code = $lang->iso_code;
        } elseif (is_string($lang)) {
            $iso_code = $lang;
        } else {
            throw new Exception('Bad param');
        }

        switch ($iso_code) {
            case 'ru':
                $this->lang = 'rus';
                break;
            case 'zh':
            case 'tw':
                $this->lang = 'zho';
                break;
            default:
                $this->lang = 'eng';
        }
    }

    public static function getInstance($lang)
    {
        if (is_null($lang)) {
            $lang = 0;
        }
        $id_lang = is_numeric($lang) ? $lang : $lang->id;
        if (!isset(self::$instance[$id_lang])) {
            self::$instance[$id_lang] = new self($lang);
        }

        return self::$instance[$id_lang];
    }

    public function getLang()
    {
        return $this->lang;
    }

    public static function getLanguages()
    {
        return Language::getLanguages();
    }
}
