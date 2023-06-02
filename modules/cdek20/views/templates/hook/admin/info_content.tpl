{**
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
 *}

<div data-id-order="{$cdek_order->id_order|intval}">

    <div class="row">

        <div class="col-lg-6">

            <div class="text-danger">
                <span class="cdekTabContent_label">{l s='State' mod='cdek20'}:</span> {$cdek_order->getCurrentStatusString()|escape:'html':'UTF-8'}<br />
            </div>

            <div>
                <span class="cdekTabContent_label">{l s='Date order create: ' mod='cdek20'}</span>
                {if count($cdek_order->getRequests())}
                    {assign var="last_request" value=$cdek_order->getLastRequest()}
                    {assign var="date_upd" value=false}
                    {if is_object($last_request)}
                        {assign var="date_upd" value=$last_request->getDateTime()}
                    {/if}
                    {if $date_upd}
                        <span class="form-group">
                            {$date_upd|escape:'html':'UTF-8'}
                        </span>
                        {assign var="create_test" value=1}
                    {else}
                        {assign var="create_test" value=0}
                        {l s='order not created' mod='cdek20'}
                    {/if}
                {else}
                    {assign var="create_test" value=0}
                    {l s='order not created ' mod='cdek20'}
                {/if}
            </div>

            {assign var="type_create" value=$cdek_order->getEntity()->getTypeCreate()}
            {if $create_test == 1}
                <div><span class="cdekTabContent_label">{l s='Тип созданного заказа:  ' mod='cdek20'}:</span>
                    {if $type_create == 0}{l s='Отправлен как оплачено ' mod='cdek20'}{/if}
                    {if $type_create == 1}{l s='Отправлен как оплачено только за товары ' mod='cdek20'}{/if}
                    {if $type_create == 2}{l s='Отправлен как не оплачено ' mod='cdek20'}{/if}
                </div>
            {/if}

            {assign var="last_date_regenerate" value=$cdek_order->getEntity()->getDateRegenerate()}
            <div> <span class="cdekTabContent_label">{l s='Date regenerate: ' mod='cdek20'}</span>
                {if $last_date_regenerate}
                    <span>{$last_date_regenerate}</span>
                {/if}
            </div>
            {assign var="date_refresh" value=$cdek_order->getEntity()->getDateRefresh()}
            <div> <span class="cdekTabContent_label">{l s='Date refresh: ' mod='cdek20'}</span>
                {if $date_refresh}
                    <span>{$date_refresh}</span>
                {else}
                    {l s='No refresh' mod='cdek20'}
                {/if}
            </div>

            <div>
            <div class="mb-1">
                <span class="cdekTabContent_label">{l s='Note' mod='cdek20'}: </span>
            </div>
            <input type="text" id="order_note" class="form-control" style="display: inline-block;" placeholder="{$cdek_order->getEntity()->getComment()|escape:'html':'UTF-8'}">
            </div>
        </div>

        <div class="col-lg-6">
            {if in_array($cdek_order->getStatus(), array(OrderCdek::_NOT_SENT_, OrderCdek::_INVALID_, OrderCdek::_DELETED_))}
                <div class="form-group clearfix">
                    <a class="btn btn-default btn-primary btn-sm" data-action="regenerate" href="#" onclick="cdek_order.click(this); return false">
                        <i class="icon-plus"></i>
                        {l s='Regenerate' mod='cdek20'}
                    </a>
                </div>

                {if $cdek_order->getStatus() == OrderCdek::_NOT_SENT_}


                    <div class="info-block">
                        <div class="form-group">
                            <h3 class="mb-0 d-print-none">
                                {l s='Send - CDEK' mod='cdek20'}
                            </h3>
                        </div>

                        <div class="">
                            <a class="btn btn-default btn-primary btn-sm mb-1" style="text-transform: none;" data-action="create_with_full_payment" href="#" onclick="cdek_order.click(this); return false">
                                {l s='Send - Paid' mod='cdek20'}
                            </a>

                            <a class="btn btn-default btn-primary btn-sm mb-1" style="text-transform: none;" data-action="create_with_payment_without_delivery" href="#" onclick="cdek_order.click(this); return false">
                                {l s='Send - paid for goods only' mod='cdek20'}
                            </a>

                            <a class="btn btn-default btn-primary btn-sm mb-1" style="text-transform: none;" data-action="create_without_payment" href="#" onclick="cdek_order.click(this); return false">
                                {l s='Send - unpaid' mod='cdek20'}
                            </a>

                            <span class="btn btn-default btn-sm js-send-cdek-toggle-title mb-1">
                                {l s='Description' mod='cdek20'}
                            </span>
                        </div>

                        <div class="mt-2 js-send-cdek-block d-none">
                            <div class="form-group">
                                <span class="cdekTabContent_label">{l s='Send - Paid' mod='cdek20'}</span>: {l s='Send - Paid desc' mod='cdek20'}<br />
                            </div>
                            <div class="form-group">
                                <span class="cdekTabContent_label">{l s='Send - paid for goods only' mod='cdek20'}</span>: {l s='Send - paid for goods only desc' mod='cdek20'}<br />
                            </div>
                            <div class="">
                                <span class="cdekTabContent_label">{l s='Send - unpaid' mod='cdek20'}</span>: {l s='Send - unpaid desc' mod='cdek20'}<br />
                            </div>
                        </div>

                    </div>

                {/if}

                {if $cdek_order->getStatus() == OrderCdek::_CREATED_}
                    {if $door == 1}
                        <a class="btn btn-default btn-primary form-group btn-sm" data-action="сall_courier" href="#" onclick="cdek_order.click(this); return false">
                            <i class="icon-send"></i>
                            {l s='Call a courier' mod='cdek20'}
                        </a>
                        <a class="btn btn-default btn-primary form-group btn-sm" data-action="cancel_call" href="#" onclick="cdek_order.click(this); return false">
                            <i class="icon-send"></i>
                            {l s='Cancel call' mod='cdek20'}
                        </a>
                    {/if}
                {/if}



            {elseif in_array($cdek_order->getStatus(), array(OrderCdek::_SENT_, OrderCdek::_CREATED_, OrderCdek::_DELIVERS_, OrderCdek::_SENT_))}
                {if $cdek_order->getStatus() == OrderCdek::_CREATED_  ||  $cdek_order->getStatus() == OrderCdek::_SENT_}
                    <a class="btn btn-default btn-primary form-group btn-sm" data-action="create_invoice" href="#" onclick="cdek_order.click(this); return false">
                        <i class="icon-remove"></i>
                        {l s='Сreate an invoice' mod='cdek20'}
                    </a>
                    <a class="btn btn-danger form-group btn-sm" data-action="remove" href="#" onclick="cdek_order.click(this); return false">
                        <i class="icon-remove"></i>
                        {l s='Remove' mod='cdek20'}
                    </a>
                {/if}

                <a class="btn btn-default btn-primary form-group btn-sm" data-action="info" href="#" onclick="cdek_order.click(this); return false">
                    <i class="icon-refresh"></i>
                    {l s='Refresh' mod='cdek20'}
                </a>
                {if $cdek_order->getStatus() == OrderCdek::_CREATED_}
                    {if $door == 1}
                        <a class="btn btn-default btn-primary form-group btn-sm" data-action="сall_courier" href="#" onclick="cdek_order.click(this); return false">
                            <i class="icon-send"></i>
                            {l s='Call a courier' mod='cdek20'}
                        </a>
                        <a class="btn btn-default btn-primary form-group btn-sm" data-action="cancel_call" href="#" onclick="cdek_order.click(this); return false">
                            <i class="icon-send"></i>
                            {l s='Cancel call' mod='cdek20'}
                        </a>
                    {/if}
                {/if}

            {elseif $cdek_order->getStatus() == OrderCdek::_NOT_FORMED_}
                &nbsp;
                <a class="btn btn-default btn-sm" data-action="generate" href="#" onclick="cdek_order.click(this); return false">
                    <i class="icon-plus"></i>
                    {l s='Generate' mod='cdek20'}
                </a>
            {/if}
        </div>
    </div>

</div>

{if isset($error)}
<div class="alert-danger">
    {$error|escape:'html':'UTF-8'}
</div>
{/if}

<span class="cdekTabContent_label">{l s='Order identifier in the CDEK IS' mod='cdek20'}</span>: {$cdek_order->getEntity()->getUuid()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Sign of a return order' mod='cdek20'}</span>: {$cdek_order->getEntity()->getIsReturn()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Order type' mod='cdek20'}</span>: {$cdek_order->getEntity()->getType()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='CDEK order number' mod='cdek20'}</span>: {$cdek_order->getEntity()->getCdekNumber()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Order number in the Client IS (if it is not sent, the order number will be assigned in the CDEK IS – uuid)' mod='cdek20'}</span>: {$cdek_order->getEntity()->getNumber()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Tariff code' mod='cdek20'}</span>: {$cdek_order->getEntity()->getTariffString()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Comment for the order' mod='cdek20'}</span>: {$cdek_order->getEntity()->getComment()|escape:'html':'UTF-8'}<br />
{if $cdek_order->getEntity()->getShipmentPoint()|escape:'html':'UTF-8'}
    <span class="cdekTabContent_label">{l s='Code of the CDEK pickup point to which the client will deliver the shipment' mod='cdek20'}</span>: {$cdek_order->getEntity()->getShipmentPointString()|escape:'html':'UTF-8'}<br />
{/if}
{if $cdek_order->getEntity()->getDeliveryPoint()}
    <span class="cdekTabContent_label">{l s='Code of the CDEK pickup point to which the parcel will be delivered' mod='cdek20'}</span>: {$cdek_order->getEntity()->getDeliveryPointString()|escape:'html':'UTF-8'}<br />
{/if}
<span class="cdekTabContent_label">{l s='Date of invoice' mod='cdek20'}</span>: {$cdek_order->getEntity()->getDateInvoice()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Consignor' mod='cdek20'}</span>: {$cdek_order->getEntity()->getShipperName()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Consignor`s address' mod='cdek20'}</span>: {$cdek_order->getEntity()->getShipperAddress()|escape:'html':'UTF-8'}<br />
{*{l s='Extra delivery charge collected by the e-shop from the receiver' mod='cdek20'}: {$cdek_order->getEntity()->getDeliveryRecipientCostString()}<br />*}
{*{l s='Extra delivery charge collected by the e-shop from the receiver, depending on the order amount' mod='cdek20'}: {$cdek_order->getEntity()->getDeliveryRecipientCostAdvString()}<br />*}
{*{l s='Sender' mod='cdek20'}: {$cdek_order->getEntity()->getSenderString()}<br />*}
{*{l s='Реквизиты реального продавца' mod='cdek20'}: {$cdek_order->getEntity()->getSellerString()}<br />*}
<span class="cdekTabContent_label">{l s='Receiver' mod='cdek20'}</span>: {$cdek_order->getEntity()->getRecipientString()|escape:'html':'UTF-8'}<br />
{if !$cdek_order->getEntity()->getShipmentPoint()}
    <span class="cdekTabContent_label">{l s='Sender`s address' mod='cdek20'}</span>: {$cdek_order->getEntity()->getFromLocationString()|escape:'html':'UTF-8'}<br />
{/if}
{if !$cdek_order->getEntity()->getDeliveryPoint()}
    <span class="cdekTabContent_label">{l s='Receiver`s address' mod='cdek20'}</span>: {$cdek_order->getEntity()->getToLocationString()|escape:'html':'UTF-8'}<br />
{/if}
<span class="cdekTabContent_label">{l s='Additional services' mod='cdek20'}:</span> {$cdek_order->getEntity()->getServicesString()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='List of details for cargo packages' mod='cdek20'}:</span> {$cdek_order->getEntity()->getPackagesString()|escape:'html':'UTF-8'}
{if in_array($cdek_order->getStatus(), array(OrderCdek::_NOT_SENT_, OrderCdek::_INVALID_, OrderCdek::_DELETED_))}
    {if $cdek_order->getStatus() == OrderCdek::_NOT_SENT_}
        <a class="btn btn-primary btn-sm" id="package_button" href="{Context::getContext()->link->getAdminLink('AdminCdekPackage', true, array(), array('id_order' => $cdek_order->id_order))}">{l s='Edit package' mod='cdek20'}</a><br />
    {/if}
{/if}
<span class="cdekTabContent_label">{l s='Order receipt' mod='cdek20'}:</span> {include file="./invoices.tpl" cdek_order=$cdek_order}<br />
<span class="cdekTabContent_label">{l s='Information about delivery' mod='cdek20'}:</span> {$cdek_order->getDeliveryDetailString()|escape:'html':'UTF-8'}<br />
<span class="cdekTabContent_label">{l s='Call courier' mod='cdek20'}:</span> {if $call == 'ACCEPTED' && empty($error_message)}{l s='Courier called' mod='cdek20'}{else}{l s='No call' mod='cdek20'}{/if}<br />
{if !empty($error_message)}
{foreach $error_message as $error}
        <span>{$error['message']|escape:'html':'UTF-8'}</span><br/>
    {/foreach}
{/if}


