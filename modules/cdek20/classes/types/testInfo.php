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
return '{
    "entity": {
        "uuid": "72753031-ebf7-4f15-8d23-dc6951808d69",
        "type": 1,
        "is_return": false,
        "number": "1580203165",
        "cdek_number": "1105661402",
        "tariff_code": 137,
        "sender": {
            "name": "Петров Петр",
            "phones": []
        },
        "recipient": {
            "name": "Иванов Иван",
            "phones": [
                {
                    "number": "+79134637228"
                }
            ]
        },
        "from_location": {
            "code": "44",
            "postal_code": "",
            "country_code": "RU",
            "fias_guid": "0c5b2444-70a0-4932-980c-b4dc0d3f02b5",
            "kladr_code": "7700000000000",
            "country": "Россия",
            "region": "Москва",
            "region_code": "81",
            "sub_region": "Москва",
            "city": "Москва",
            "address": "пр. Ленинградский, д.4",
            "longitude": 37.6204,
            "latitude": 55.754
        },
        "to_location": {
            "code": "270",
            "postal_code": "",
            "country_code": "RU",
            "fias_guid": "8dea00e3-9aab-4d8e-887c-ef2aaa546456",
            "kladr_code": "5400000100000",
            "country": "Россия",
            "region": "Новосибирская",
            "region_code": "23",
            "sub_region": "Новосибирск",
            "city": "Новосибирск",
            "address": "ул. Блюхера, 32",
            "longitude": 82.9204,
            "latitude": 55.0302
        },
        "packages": [
            {
                "number": "bar-001",
                "weight": 4000,
                "length": 10,
                "width": 10,
                "height": 10,
                "comment": "приложена опись",
                "items": [
                    {
                        "ware_key": "00055",
                        "payment": {
                            "value": 3000.0,
                            "vat_sum": 0.0
                        },
                        "cost": 300.0,
                        "weight": 700,
                        "amount": 2,
                        "name": "Товар",
                        "url": "www.item.ru",
                        "weight_gross": 700
                    }
                ]
            }
        ],
        "services": [
            {
                "code": "DELIV_WEEKEND"
            }
        ],
        "delivery_recipient_cost": {
            "value": 500
        },
        "delivery_recipient_cost_adv": [
            {
                "threshold": 200,
                "sum": 3000
            }
        ],
        "recipient_currency": "RUB",
        "items_cost_currency": "RUB",
        "comment": "Новый заказ",
        "shop_seller_name": "ТЕСТИРОВАНИЕ ИНТЕГРАЦИИ, ООО",
        "statuses": [
            {
                "code": "ACCEPTED",
                "name": "Принят",
                "date_time": "2020-01-28T16:19:26+0700",
                "city": "Офис СДЭК"
            },
            {
                "code": "CREATED",
                "name": "Создан",
                "date_time": "2020-01-28T16:19:28+0700",
                "city": "Москва"
            }
        ],
        "errors": [],
        "seller": {
            "name": "ТЕСТИРОВАНИЕ ИНТЕГРАЦИИ, ООО"
        }
    },
    "requests": [
        {
            "request_uuid": "72753031-f1c4-49ae-b5c7-ad2110ec46df",
            "type": "CREATE",
            "state": "SUCCESSFUL",
            "date_time": "2020-01-28T16:19:26+0700",
            "errors": [],
            "warnings": []
        }
    ]
}';
