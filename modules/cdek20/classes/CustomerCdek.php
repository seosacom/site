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
class CustomerCdek extends ObjectModel implements Seleda\Cdek\Component\Calculator\ICustomer
{
    public $force_id = true;

    public $city_courier;

    public $city_pickup;

    public $city_postamat;

    public $courier; // stab

    public $pickup;

    public $postamat;

    public static $definition = array(
        'table' => 'cdek_customer',
        'primary' => 'id_customer',
        'fields' => array(
            'city_courier' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'city_pickup' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'city_postamat' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'pickup' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'postamat' => array('type' => self::TYPE_STRING, 'validate' => 'isString')
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);

        if (!$this->id && Context::getContext()->cart && Context::getContext()->cart->id_address_delivery) {
            $this->id = Context::getContext()->cart->id_address_delivery;
        }
    }

    public function save($null_values = false, $auto_date = true)
    {
        if ($res = Db::getInstance()->getRow('SELECT `id_customer`, `city_pickup`, `city_postamat` FROM `'._DB_PREFIX_.'cdek_customer` WHERE `id_customer` = '.(int)$this->id)) {
            if ($this->city_pickup != $res['city_pickup']) {
                $this->pickup = false;
            }
            if ($this->city_postamat != $res['city_postamat']) {
                $this->postamat = false;
            }
            $this->update();
        } else {
            // на Preste 1.6 падает ajax при регистрации нового клиента одностраничник
//            if (!$this->id) {
//                throw new Exception('Bad id!!!');
//            }
            $this->add();
        }
    }

    public function getCityCode($type)
    {
        return $this->{'city_'.$type};
    }
}
