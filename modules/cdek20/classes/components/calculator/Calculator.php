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

namespace Seleda\Cdek\Component\Calculator;

use Seleda\Cdek\Component\Cart\Package\CalculatorPackageBuiler;

abstract class Calculator
{
    protected $date_departure;
    protected $type_contract;
    protected $from_location_code;
    protected $to_location_code;
    protected $cart;
    protected $lang;

    protected $table;

    public function calculate()
    {
        $res = $this->getCache();

        if (!$res) {
            $this->loadCalculationApi();
            $res = $this->getCache();
        }

        return $res;
    }

    public function getCache()
    {
        $res = Db::getInstance()->getRow('SELECT * FROM `'.$this->table.'` 
        WHERE `id_cart` = '.(int)$this->cart->getIdCalculatorCache());

        if (!$res ||
            $res['date'] != $this->date_departure ||
            $res['weight'] != $this->cart->getTotalWeight() ||
            $res['city_from'] != $this->from_location_code ||
            $res['city_to'] != $this->to_location_code ||
            $res['currency'] != $this->cart->getCurrency() ||
            $res['lang'] != $this->lang
        ) {
            return false;
        }

        return $res;
    }

    public function loadCalculationApi()
    {
        $params = array();

        $params['date'] = $this->date_departure;
        $params['type'] = $this->type_contract;
        $params['currency'] = $this->cart->getCurrency();
        $params['lang'] = $this->lang;
        $params['from_location'] = array(
            'code' => $this->from_location_code
        );
        $params['to_location'] = array(
            'code' => $this->to_location_code
        );

        $params['packages'] = $this->cart->createPackages()->getPackagesForCalculator();

        $client = Client::getInstance();
        if ($client->calculateList($params)) {
            $result = $client->getResult();
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cdek_calculator` (
                    `id_cart`,
                    `weight`,  
                    `city_from`, 
                    `city_to`,
                    `currency`,
                    `lang`,
                    `response`,
                    `date`) 
                    VALUES (
                    '.(int)$this->cart->getIdCalculatorCache().',
                    '.(int)$this->cart->getTotalWeight().', 
                    "'.(int)$this->from_location_code.'", 
                    "'.(int)$this->to_location_code.'",
                    "'.(int)$this->cart->getCurrency().'",
                    "'.Db::escape($this->lang).'",
                    "'.Db::escape($result).'",
                    "'.Db::escape($this->date_departure).'") 
                    ON DUPLICATE KEY UPDATE 
                    `weight` = '.(int)$this->cart->getTotalWeight().',
                    `city_from` = "'.(int)$this->from_location_code.'",
                    `city_to` = "'.(int)$this->to_location_code.'",
                    `currency` = "'.(int)$this->cart->getCurrency().'",
                    `lang` = "'.Db::escape($this->lang).'",
                    `response` = "'.Db::escape($result).'",
                    `date` = "'.Db::escape($this->date_departure).'"');
            return true;
        }

        return false;
    }
}
