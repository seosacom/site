<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
abstract class ToolsCdek
{
    public static function isCdek($object)
    {
        if ($object instanceof Carrier) {
            $carrier = $object;
        } elseif ($object instanceof Cart || $object instanceof Order) {
            $carrier = new Carrier($object->id_carrier);
        } else {
            throw new Exception('Bad param');
        }
        if (!Validate::isLoadedObject($carrier)) {
            return false;
        }

        return (bool) Db::getInstance()->getValue('SELECT `id_reference` FROM `' . _DB_PREFIX_ . 'carrier` WHERE `external_module_name` = "cdek20" AND `id_reference` = ' . (int) $carrier->id_reference);
    }

    public static function normalizationPhone($phone, $iso)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($phone, $iso);
            return '+' . $swissNumberProto->getCountryCode() . $swissNumberProto->getNationalNumber();
        } catch (\libphonenumber\NumberParseException $e) {
            return $phone;
        }
    }

    public static function rangeToArray($string)
    {
        preg_match('/^(\d+)[-]+(\d+)$/', $string, $matches);
        if (isset($matches[1]) && $matches[2]) {
            return [$matches[1], $matches[2]];
        }

        return [0, 0]; // Or throw Exception
    }

    public static function getFromRequest($param)
    {
        try {
            if (version_compare(_PS_VERSION_, '1.7.4.0', '>=')) {
                $request = \PrestaShop\PrestaShop\Adapter\SymfonyContainer::getInstance()
                    ->get('request_stack')
                    ->getCurrentRequest();
            } else {
                global $kernel;

                $request = $kernel->getContainer()->get('request');
            }

            return $request->get($param);
        } catch (Throwable $t) {
            return Tools::getValue($param);
        } catch (Exception $e) {
            return Tools::getValue($param);
        }
    }
}
