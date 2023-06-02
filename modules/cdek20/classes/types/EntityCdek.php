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
class EntityCdek extends AbstractTypeCdek
{
    protected $uuid;
    protected $is_return;
    protected $type;
    protected $cdek_number;
    protected $number;
    protected $tariff_code;
    protected $comment;
    protected $developer_key;
    protected $shipment_point;
    protected $delivery_point;
    protected $date_invoice;
    public $shipper_name;
    protected $shipper_address;
    protected $delivery_recipient_cost;
    protected $delivery_recipient_cost_adv = [];
    protected $sender;
    protected $seller;
    protected $recipient;
    protected $from_location;
    protected $to_location;
    protected $services = [];
    protected $packages = [];
    protected $delivery_detail;
    protected $statuses = [];
    protected $date_regenerate = null;
    protected $date_refresh = null;
    protected $type_create = 0;

    public function getTypeCreate()
    {
        return $this->type_create;
    }

    public function setTypeCreate($val)
    {
        $this->type_create = $val;
        return  $this->type_create;
    }

    public function getDateRefresh()
    {
        return $this->date_refresh;
    }

    public function setDateRefresh($val)
    {
        $this->date_refresh = $val;
        return  $this->date_refresh;
    }

    public function getDateRegenerate()
    {
       return $this->date_regenerate;
    }

    public function setDateRegenerate($val)
    {
        $this->date_regenerate = $val;
        return  $this->date_regenerate;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($val)
    {
        $this->uuid = $val;
        return $this;
    }

    public function getIsReturn()
    {
        return $this->is_return;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($val)
    {
        $this->type = $val;
        return $this;
    }

    public function setCdekNumber($val)
    {
        $this->cdek_number = $val;
        return $this;
    }

    public function getCdekNumber()
    {
        return $this->cdek_number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($val)
    {
        $this->number = $val;
        return $this;
    }

    public function getTariffCode()
    {
        return $this->tariff_code;
    }

    public function setTariffCode($val)
    {
        $this->tariff_code = $val;
        return $this;
    }

    public function getTariffString($code = true)
    {
        $string = $code ? $this->tariff_code . '|' : '';
        $string .= Db::getInstance()->getValue('SELECT `name_rus` FROM `' . _DB_PREFIX_ . 'cdek_tariff` WHERE `tariff` = ' . (int) $this->tariff_code);
        return $string;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($val)
    {
        $this->comment = $val;
        return $this;
    }

    public function getDeveloperKey()
    {
        return $this->developer_key;
    }

    public function setDeveloperKey($val)
    {
        $this->developer_key = $val;
        return $this;
    }

    public function getShipmentPoint()
    {
        return $this->shipment_point;
    }

    public function getShipmentPointString()
    {
        $sql = 'SELECT CONCAT(p.`code`, "|", cl.`city`, ", ", pl.`Address`) FROM `' . _DB_PREFIX_ . 'cdek_pvz` p
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_pvz_lang` pl ON (p.`code` = pl.`code` AND pl.`lang` = "' . LangCdek::getInstance($this->context->language->id)->getLang() . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_city_lang` cl ON (p.`CityCode` = cl.`code` AND cl.`lang` = "' . LangCdek::getInstance($this->context->language->id)->getLang() . '")
        WHERE p.`code` = "' . pSQL($this->shipment_point) . '"';

        return Db::getInstance()->getValue($sql);
    }

    public function setShipmentPoint($val)
    {
        $this->shipment_point = $val;
        return $this;
    }

    public function getDeliveryPoint()
    {
        return $this->delivery_point;
    }

    public function getDeliveryPointString()
    {
        $sql = 'SELECT CONCAT(p.`code`, "|", cl.`city`, ", ", pl.`Address`) FROM `' . _DB_PREFIX_ . 'cdek_pvz` p
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_pvz_lang` pl ON (p.`code` = pl.`code` AND pl.`lang` = "' . LangCdek::getInstance($this->context->language->id)->getLang() . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'cdek_city_lang` cl ON (p.`CityCode` = cl.`code` AND cl.`lang` = "' . LangCdek::getInstance($this->context->language->id)->getLang() . '")
        WHERE p.`code` = "' . pSQL($this->delivery_point) . '"';

        return Db::getInstance()->getValue($sql);
    }

    public function setDeliveryPoint($val)
    {
        $this->delivery_point = $val;
        return $this;
    }

    public function getDateInvoice()
    {
        return $this->date_invoice;
    }

    public function setDateInvoice($val)
    {
        $val = substr($val, 0, 10);
        if ($val != '0000-00-00') {
            $this->date_invoice = $val;
        }
        return $this;
    }

    public function getShipperName()
    {
        return $this->shipper_name;
    }

    public function setShipperName($val)
    {
        $this->shipper_name = $val;
        return $this;
    }

    public function getShipperAddress()
    {
        return $this->shipper_address;
    }

    public function setShipperAddress($val)
    {
        $this->shipper_address = $val;
        return $this;
    }

    public function getDeliveryRecipientCost()
    {
        return $this->delivery_recipient_cost;
    }

    public function setDeliveryRecipientCost(MoneyCdek $val)
    {
        $this->delivery_recipient_cost = $val;
        return $this;
    }

    public function getDeliveryRecipientCostString()
    {
        if ($this->delivery_recipient_cost instanceof MoneyCdek) {
            return $this->delivery_recipient_cost->getValue();
        }
        return '';
    }

    public function getDeliveryRecipientCostAdv()
    {
        return $this->delivery_recipient_cost_adv;
    }

    public function getDeliveryRecipientCostAdvString()
    {
        $string = '';
        foreach ($this->delivery_recipient_cost_adv as $key => $item) {
            $string .= ($key ? '|' : '') . $item->getSum();
        }
        return $string;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender(SenderCdek  $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function getSenderString()
    {
        if ($this->sender instanceof ContactCdek) {
            return $this->sender->getName();
        }
        return '';
    }

    public function setSeller(SellerCdek  $seller)
    {
        $this->seller = $seller;
        return $this;
    }

    public function getSeller()
    {
        return $this->seller;
    }

    public function getSellerString()
    {
        if ($this->seller instanceof SellerCdek) {
            return $this->seller->getName();
        }
        return '';
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setRecipient(ContactCdek $val)
    {
        $this->recipient = $val;
        return $this;
    }

    public function getRecipientString()
    {
        if ($this->recipient instanceof ContactCdek) {
            return $this->recipient->toString();
        }
        return '';
    }

    public function getFromLocation()
    {
        return $this->from_location;
    }

    public function setFromLocation(LocationCdek $val = null)
    {
        $this->from_location = $val;
        return $this;
    }

    public function getFromLocationString()
    {
        if ($this->from_location instanceof LocationCdek) {
            return $this->from_location->getPostalCode().', ' . $this->from_location->getCity() . ', ' . $this->from_location->getAddress();
        }
        return '';
    }

    public function getToLocation()
    {
        return $this->to_location;
    }

    public function setToLocation(LocationCdek $val)
    {
        $this->to_location = $val;
        return $this;
    }

    public function getToLocationString()
    {
        if ($this->to_location instanceof LocationCdek) {
            return $this->to_location->getPostalCode() . ', ' . $this->to_location->getCity() . ', ' . $this->to_location->getAddress();
        }
        return '';
    }

    public function getServices()
    {
        return $this->services;
    }

    public function addService(ServiceCdek $service)
    {
        $this->services[] = $service;
    }

    public function getServicesString()
    {
        $string = '';
        foreach ($this->services as $key => $service) {
            $string .= ($key ? '|' : '') . $service->getCode() . ' - ' . $service->getSum();
        }
        return $string;
    }

    public function getPackages()
    {
        return $this->packages;
    }

    public function setPackages($val)
    {
        $this->packages = $val;
        return $this;
    }

    public function getPackagesString()
    {
        $string = '';
        $items = 0;
        $weight = 0;
        foreach ($this->packages as $key => $package) {
            $items++;
            $weight += $package->getWeight();
        }

        $string .= 'Мест: ' . $items . ', вес: ' . $weight . ' гр.';
        return $string;
    }

    public function getDeliveryDetail()
    {
        return $this->delivery_detail;
    }

    public function setStatuses($val)
    {
        if (!is_array($val)) {
            $val = [];
        }

        $this->statuses = $val;
        return $this;
    }

    public function getStatuses()
    {
        return $this->statuses;
    }

    public function removeStatuses()
    {
        $this->statuses = array();
        return $this;
    }

    public function getCurrentStatus()
    {
        if ($this->statuses) {
            usort($this->statuses, function ($a, $b) {
                if (($a->getCode() == 'CREATED' || $a->getCode() == 'INVALID') && $b->getCode() == 'ACCEPTED') {
                    return 1;
                }
                return -1;
            });
            $last_status_key = count($this->statuses) - 1;
            if (isset($this->statuses[$last_status_key])) {
                return $this->statuses[$last_status_key];
            }
        }

        return new StatusCdek();
    }
}
