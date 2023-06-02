{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

{if $status == 'ok'}
    <p>
        {if version_compare($smarty.const._PS_VERSION_, '1.7.0.0', '>=')}
            {l s='Your order № %s checkout.' sprintf=[$id_order]  mod='custompaymentmethod'}
        {else}
            {l s='Your order № %s checkout.' sprintf=$id_order  mod='custompaymentmethod'}
        {/if}
        <br/><br/><strong>{l s='Total order:' mod='custompaymentmethod'}</strong>
        <span class="price">
            {if version_compare($smarty.const._PS_VERSION_, '1.7.0.0', '<')}
                {Tools::displayPrice($total)|escape:'html':'UTF-8'}
            {else}
                {$total_to_pay|escape:'quotes':'UTF-8'}
            {/if}
        </span>
        <br/><br/><strong>{l s='Payment method' mod='custompaymentmethod'}:</strong>
        {$custom_payment->name nofilter}
        </strong>
        <br/><br/><strong>{l s='Order reference' mod='custompaymentmethod'}:</strong>
        {$reference|escape:'quotes':'UTF-8'}
    </p>

    {if $confirmation_page_add}
        <br/><strong>{l s='Requisites' mod='custompaymentmethod'}:</strong>
        {$details|escape:'quotes':'UTF-8' nofilter}

        <br/><strong>{l s='Description' mod='custompaymentmethod'}:</strong>
        {$description|escape:'quotes':'UTF-8' nofilter}

        <br/><strong>{l s='Message' mod='custompaymentmethod'}:</strong>
        <p>{$ps_message_field|escape:'quotes':'UTF-8' nofilter}</p><br/>
    {/if}

    {if $cms_content}
        <div>
            {$cms_content|escape:'quotes':'UTF-8' nofilter}
        </div>
    {/if}
{else}
    <p class="warning">
        {l s='Issue has been identified in your order. If you think this is an error, please inform' mod='custompaymentmethod'}
        <a href="{$link->getPageLink('contact', true)|escape:'quotes':'UTF-8'}">{l s='us' mod='custompaymentmethod'}</a>.
    </p>
{/if}
