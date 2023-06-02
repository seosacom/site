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

use Seleda\Cdek\Component\Cart\CartInterface;

abstract class AbstractCarrierCdek
{
    protected static $date_departure;

    protected $configuration;
    protected $tariffs = array();

    public $part_deliv;
    public $calculation;
    public $cart;
    public $customer;

    public function __construct(CartInterface $cart, $customer)
    {
        $this->configuration = ConfigurationCdek::get();

        $this->part_deliv = (bool)$this->configuration->part_deliv;

        if (!self::$date_departure) {
            self::$date_departure = $this->getDateDeparture();
        }

        $this->cart = $cart;
        $this->customer = $customer;
        $this->tariffs = TariffCdek::getTariffsByType($this->type, true);
    }

    public function getPrice()
    {
        if (!$this->calculation) {
            return false;
        }

        if ($this->isFree()) {
            return 0;
        }

        $price = $this->calculation['delivery_sum'];

        if ($this->configuration->total_correction != 0) {
            if ($this->configuration->type_correction == 1) {
                $price = $price / 100 * Tools::convertPrice($this->configuration->total_correction) + abs($price);
            } elseif ($this->configuration->type_correction == 2) {
                $price += Tools::convertPrice($this->configuration->total_correction);
            }
        }

        if ($this->configuration->impact_percent_of_cart != 0) {
            $price += $this->cart->order_total * $this->configuration->impact_percent_of_cart / 100;
        }

        if ($price < 0) {
            $price = 0;
        }

        if ($vat = $this->configuration->vat) {
            $price = $price / 100 * $vat + $price;
        }

        return Tools::ps_round($price, 0);
    }

    public function isFree()
    {
        if ($this->configuration->{'free_shipping_'.$this->type}) {
            if ($this->configuration->{'free_weight_'.$this->type} == false) {
                $this->configuration->{'free_weight_'.$this->type} = '0-0';
            }
            if ($this->configuration->{'free_price_'.$this->type} == 0 && $this->configuration->{'free_weight_'.$this->type} == '0-0') {
                return true;
            } elseif ($this->configuration->{'free_price_'.$this->type} && Tools::convertPrice($this->cart->order_total) >= $this->configuration->{'free_price_'.$this->type}/100) {
                return true;
            } elseif ($this->configuration->{'free_weight_'.$this->type} && $this->configuration->{'free_weight_'.$this->type} != '0-0') {
                $range = ToolsCdek::rangeToArray($this->configuration->{'free_weight_'.$this->type});
                $total_weight = $this->cart->getTotalWeight();
                if ($total_weight >= $range[0] && $total_weight <= $range[1]) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getDelay()
    {
        if (!$this->calculation) {
            return false;
        }

        list($min, $max) = array(
            $this->calculation['period_min'] + $this->configuration->delay,
            $this->calculation['period_max'] + $this->configuration->delay
        );

        if ($min == $max) {
            $str = $max;
        } else {
            $str = $min.'-'.$max;
        }

        $lang = $this->cart->getLang();

        if ($lang == 'rus') {
            $str = 'Срок доставки '.$str;
            $n = abs($max) % 100;

            $n1 = $n % 10;

            if (($n1 > 4 && $n1 < 20) || $n1 == 0 || in_array($max, array(11, 12))) {
                return $str.' дней';
            } elseif ($n1 > 1 && $n1 < 5) {
                return $str.' дня';
            } elseif ($n1 == 1) {
                return $str.' день';
            }
        }

        return 'Delivery time '.$str.' '.($max > 1 ? 'days' : 'day');
    }

    public function calculate()
    {
        if (count($this->cart->getProducts()) == 0) {
            $this->calculation = false;
            return $this;
        }
        if ($this->calculation) {
            return $this;
        }
        $address = new Address();
        $address->postcode = ConfigurationCdek::get('postal_code');
        $address->id_country = Country::getByIso($this->configuration->country_warehouse);

        $city_from = new CityCdek($address);
        $exp = explode('|', ConfigurationCdek::get('pvz_warehouse'));
        $city_from->setCode(Db::getInstance()->getValue('SELECT `CityCode` FROM `'._DB_PREFIX_.'cdek_pvz` WHERE `code` = "'.$exp[0].'"'));
        
        $calculator = new CalculatorCdek(
            self::$date_departure,
            $this->configuration->type_contract,
            $city_from->getCode(),
            $this->customer->{'city_'.$this->type},
            $this->cart
        );

        $calculation = $calculator->calculate();

        if ($calculation) {
            $response = json_decode($calculation['response'], true);
            if (array_key_exists('tariff_codes', $response)) {
                foreach ($response['tariff_codes'] as $value) {
                    // один раз сдек не передал tariff_code
                    if (isset($value['tariff_code']) && array_key_exists($value['tariff_code'], $this->tariffs)) {
                        $this->calculation[] = $value;
                    }
                }
            }
        }

        if ($this->calculation) {
            usort($this->calculation, function ($a, $b) {
                if ($a['delivery_sum'] < $b['delivery_sum']) {
                    return -1;
                }
                return 1;
            });

            if (Context::getContext()->cookie->sort_cdek_carriers == 'delay') {
                usort($this->calculation, function ($a, $b) {
                    if ($a['period_min'] < $b['period_min'] || $a['period_max'] < $b['period_max']) {
                        return -1;
                    }
                    return 1;
                });
            }

            $this->calculation = $this->calculation[0];
            // Добавлена возможность добавлять к стоимости доставки по весу места
            if ($this->configuration->weight_allowance) {
                $summ = 0;
                if ($this->configuration->all_one_box == 0) {
                    foreach ($this->cart->products as $product) {
                        $weight = $product['weight'];
                        $weight2 = $product['width'] * $product['height'] * $product['depth'] / 5;
                        $big_weight = max($weight, $weight2);
                        if ($big_weight >= 200000) {
                            $summ = $summ + $big_weight / 1000 * 25 * $product['cart_quantity'];
                        } elseif ($big_weight > 75000 && $big_weight < 200000) {
                            $summ = $summ + $big_weight / 1000 * 18 * $product['cart_quantity'];
                        }
                    }
                    $this->calculation['delivery_sum'] = $this->calculation['delivery_sum'] + $summ;
                }

                if ($this->configuration->all_one_box == 1) {
                    $big_weight = 0;
                    foreach ($this->cart->products as $product) {
                        $weight = $product['weight'];
                        $weight2 = $product['width'] * $product['height'] * $product['depth'] / 5;
                        $big_weight = $big_weight + max($weight, $weight2);
                    }
                    if ($big_weight >= 200000) {
                        $summ = $big_weight / 1000 * 25 * $product['cart_quantity'];
                    } elseif ($big_weight > 75000 && $big_weight < 200000) {
                        $summ = $big_weight / 1000 * 18 * $product['cart_quantity'];
                    }
                    $this->calculation['delivery_sum'] = $this->calculation['delivery_sum'] + $summ;
                }
            }
        }
        return $this;
    }

    public function getDateDeparture()
    {
        $current_date = date('Y-m-d');
        $date = date_create_from_format('Y-m-d', $current_date);
        $day = date_format($date, 'd');
        return date(
            'Y-m-d\T'.$this->configuration->departure_time.':sO',
            mktime(0, 0, 0, date("m"), $day + $this->configuration->delay, date("Y"))
        );
    }

    public static function getCarrierIdByType($type)
    {
        $reference = Db::getInstance()->getValue('SELECT `carrier_reference` FROM `'._DB_PREFIX_.'cdek_carrier_type` 
            WHERE `type` = "'.pSQL($type).'" ORDER BY `carrier_reference` DESC');
        $carrier = Carrier::getCarrierByReference($reference);
        return $carrier->id;
    }
}
