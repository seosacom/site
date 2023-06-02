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

namespace Seleda\Cdek\Component\Order;

use \TranslateCdek as Trans;

class Order
{
    // redo it
    const _NOT_FORMED_ = 1;
    const _NOT_SENT_ = 2;
    const _SENT_ = 3;
    const _CREATED_ = 4;
    const _DELIVERS_ = 5;
    const _COMPLETED_ = 6;
    const _INVALID_ = 7;
    const _DELETED_ = 8;
    //

    public $statuses = array(
        'ACCEPTED' => array(
            'name' => 'Принят',
            'comment' => 'Заказ создан в информационной системе СДЭК, но требуются дополнительные валидации'
        ),
        'CREATED' => array(
            'name' => 'Создан',
            'comment' => 'Заказ создан в информационной системе СДЭК и прошел необходимые валидации'
        ),
        'RECEIVED_AT_SENDER_WAREHOUSE' => array(
            'name' => 'Принят на склад отправителя',
            'comment' => 'Оформлен приход на склад СДЭК в городе-отправителе.'
        ),
        'READY_FOR_SHIPMENT_IN_SENDER_CITY' => array(
            'name' => 'Выдан на отправку в г. отправителе',
            'comment' => 'Оформлен расход со склада СДЭК в городе-отправителе. Груз подготовлен к отправке (консолидирован с другими посылками)'
        ),
        'RETURNED_TO_SENDER_CITY_WAREHOUSE' => array(
            'name' => 'Возвращен на склад отправителя',
            'comment' => 'Повторно оформлен приход в городе-отправителе (не удалось передать перевозчику по какой-либо причине). Примечание: этот статус не означает возврат груза отправителю.'
        ),
        'TAKEN_BY_TRANSPORTER_FROM_SENDER_CITY' => array(
            'name' => 'Сдан перевозчику в г. отправителе',
            'comment' => 'Зарегистрирована отправка в городе-отправителе. Консолидированный груз передан на доставку (в аэропорт/загружен машину)'
        ),
        'SENT_TO_TRANSIT_CITY' => array(
            'name' => 'Отправлен в г. транзит',
            'comment' => 'Зарегистрирована отправка в город-транзит. Проставлены дата и время отправления у перевозчика'
        ),
        'ACCEPTED_IN_TRANSIT_CITY' => array(
            'name' => 'Встречен в г. транзите',
            'comment' => 'Зарегистрирована встреча в городе-транзите'
        ),
        'ACCEPTED_AT_TRANSIT_WAREHOUSE' => array(
            'name' => 'Принят на склад транзита',
            'comment' => 'Оформлен приход в городе-транзите'
        ),
        'RETURNED_TO_TRANSIT_WAREHOUSE' => array(
            'name' => 'Возвращен на склад транзита',
            'comment' => 'Повторно оформлен приход в городе-транзите (груз возвращен на склад). Примечание: этот статус не означает возврат груза отправителю.'
        ),
        'READY_FOR_SHIPMENT_IN_TRANSIT_CITY' => array(
            'name' => 'Выдан на отправку в г. транзите',
            'comment' => 'Оформлен расход в городе-транзите'
        ),
        'TAKEN_BY_TRANSPORTER_FROM_TRANSIT_CITY' => array(
            'name' => 'Сдан перевозчику в г. транзите',
            'comment' => 'Зарегистрирована отправка у перевозчика в городе-транзите'
        ),
        'SENT_TO_RECIPIENT_CITY' => array(
            'name' => 'Отправлен в г. получатель',
            'comment' => 'Зарегистрирована отправка в город-получатель, груз в пути.'
        ),
        'ARRIVED_AT_RECIPIENT_CITY' => array(
            'name' => 'Встречен в г. получателе',
            'comment' => 'Зарегистрирована встреча груза в городе-получателе'
        ),
        'ACCEPTED_AT_RECIPIENT_CITY_WAREHOUSE' => array(
            'name' => 'Принят на склад доставки',
            'comment' => 'Оформлен приход на склад города-получателя, ожидает доставки до двери'
        ),
        'ACCEPTED_AT_PICK_UP_POINT' => array(
            'name' => 'Принят на склад до востребования',
            'comment' => 'Оформлен приход на склад города-получателя. Доставка до склада, посылка ожидает забора клиентом - покупателем ИМ'
        ),
        'TAKEN_BY_COURIER' => array(
            'name' => 'Выдан на доставку',
            'comment' => 'Добавлен в курьерскую карту, выдан курьеру на доставку'
        ),
        'RETURNED_TO_RECIPIENT_CITY_WAREHOUSE' => array(
            'name' => 'Возвращен на склад доставки',
            'comment' => 'Оформлен повторный приход на склад в городе-получателе. Доставка не удалась по какой-либо причине, ожидается очередная попытка доставки.'
        ),
        'DELIVERED' => array(
            'name' => 'Вручен',
            'comment' => 'Успешно доставлен и вручен адресату (конечный статус).'
        ),
        'NOT_DELIVERED' => array(
            'name' => 'Не вручен',
            'comment' => 'Покупатель отказался от покупки, возврат в ИМ (конечный статус).'
        ),
        'INVALID' => array(
            'name' => 'Некорректный заказ',
            'comment' => 'Заказ содержит некорректные данные'
        )
    );

    protected $entity;
    protected $requests = array();
    protected $related_entities = array();

    public function setEntity($entity)
    {
        if ($entity instanceof \EntityCdek) {
            $this->entity = $entity;
        } else {
            $this->entity = new \EntityCdek($entity);
        }
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setRequests($requests)
    {
        if (!is_array($requests)) {
            $requests = json_decode($requests, true);
        }
        if (is_array($requests)) {
            $res = array();
            foreach ($requests as $item) {
                if ($item instanceof \RequestCdek) {
                    $res[] = $item;
                } else {
                    $res[] = new \RequestCdek($item);
                }
            }
            $this->requests = $res;
        }
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function getRelatedEntities()
    {
        return $this->related_entities;
    }

    public function setRelatedEntities($related_entities)
    {
        if (!is_array($related_entities)) {
            $related_entities = json_decode($related_entities, true);
            if (!$related_entities) {
                $related_entities = array();
            }
            $this->related_entities = $related_entities;
        } elseif (is_array($related_entities)) {
            $res = array();
            foreach ($related_entities as $item) {
                if ($item instanceof \RelatedEntityCdek) {
                    $res[] = $item;
                } else {
                    $res[] = new \RelatedEntityCdek($item);
                }
            }
            $this->related_entities = $res;
        }
    }

    public function getLastRequest()
    {
        if (!$this->requests) {
            return new \RequestCdek();
        }
        return $this->requests[count($this->requests) - 1];
    }

    public function getStatus()
    {
        if ($this->getLastRequest()->getType() == 'DELETE' && $this->getLastRequest()->getState() == 'ACCEPTED') {
            return self::_DELETED_;
        }

        if (!$this->entity->getNumber()) {
            return self::_NOT_FORMED_;
        }

        if ($this->entity->getUuid() == null) {
            return self::_NOT_SENT_;
        }

        if ($this->entity->getCurrentStatus()->getCode() == 'ACCEPTED') {
            return self::_SENT_;
        }

        if ($this->entity->getCurrentStatus()->getCode() == 'INVALID') {
            return self::_INVALID_;
        }

        if ($this->entity->getCurrentStatus()->getCode() == 'CREATED') {
            return self::_CREATED_;
        }

        if (in_array($this->entity->getCurrentStatus()->getCode(), array('NOT_DELIVERED', 'DELIVERED'))) {
            return self::_COMPLETED_;
        }

        return self::_DELIVERS_;
    }

    public function getCurrentStatusString()
    {
        if ($this->getStatus() == self::_DELETED_) {
            return Trans::l('Deleted');
        }
        if ($this->getStatus() == self::_NOT_SENT_) {
            return Trans::l('Is not sent');
        }
        $error_message = false;
        if ($this->entity->getCurrentStatus()->getCode() == 'INVALID') {
            //TODO create method for errors
            foreach ($this->requests as $request) {
                if ($request->getState() == 'INVALID') {
                    $errors = $request->getErrors();
                    if (count($errors)) {
                        $error = $errors[0];
                        $error_message = $error->getMessage();
                    }
                }
            }
        }
        return $this->entity->getCurrentStatus()->getName().($error_message ? '('.$error_message.')' : '');
    }

    public function getDeliveryDetailString()
    {
        $delivery_detaul = $this->entity->getDeliveryDetail();
        if (!$delivery_detaul) {
            return '';
        }
        return ($delivery_detaul->getDate() ? $delivery_detaul->getDate().', ' : '')
            .($delivery_detaul->getRecipientName() ? $delivery_detaul->getRecipientName().', ' : '')
            .($delivery_detaul->getPaymentSum() ? $delivery_detaul->getPaymentSum().', ' : '')
            .($delivery_detaul->getDeliverySum() ? Trans::l('Cost of delivery').' '.\Tools::displayPrice($delivery_detaul->getDeliverySum(), (int)\ConfigurationCdek::get('contract_currency')).', ' : '')
            .($delivery_detaul->getTotalSum() ? Trans::l('Total shipping cost').' '.\Tools::displayPrice($delivery_detaul->getTotalSum(), (int)\ConfigurationCdek::get('contract_currency')).', ' : '');
    }

    public function getUuidInvoice()
    {
        $link = false;
        foreach ($this->related_entities as $related_entity) {
            if ($related_entity->getType() == 'waybill') {
                $link = $related_entity->getUuid();
            }
        }
        return $link;
    }
}
