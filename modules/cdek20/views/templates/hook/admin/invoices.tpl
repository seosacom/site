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

{capture name=status_string}
    {l s='The invoice has not been formed or is being formed' mod='cdek20'}
{/capture}

{assign var="invoice_check" value=false}
{assign var="invoice_url" value=$smarty.capture.status_string}
{foreach from=$cdek_order->getRelatedEntities() item="related_entity"}
    {if $related_entity->getType() == 'waybill'}
        {assign var="invoice_url" value=$related_entity->getUrl()}
        {assign var="invoice_check" value=true}
    {/if}
{/foreach}

{if $invoice_check && $invoice_url}
    <a target="_blank" class="related-entities-load" href="{$cdek_order->getInvoiceLoadLink()|escape:'html':'UTF-8'}?action=load_invoice&id_order={$cdek_order->id_order|intval}&ajax=1" >{$invoice_url|escape:'html':'UTF-8'}</a>
{elseif $invoice_check}
    {l s='Formation in progress' mod='cdek20'}...
{else}
    {$invoice_url|escape:'html':'UTF-8'}
{/if}

{if $invoice_check && !$invoice_url}
    <script>
        var invoice_request_interval = setInterval(function () {
            $('[data-action="info"]').trigger('click');
        }, 3000);

    </script>
{/if}