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
*  @author    SeoSA <885588@bk.ru>
*  @copyright 2012-2023 SeoSA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="input"}
    {$smarty.block.parent}
    {if $input.type == 'carrier_group'}
        {assign var='field_value' value=$fields_value[$input.name]}
        {if is_array($input.values) && count($input.values)}
            <ul class="carrier_group">
                {foreach from=$input.values item=value}
                    <li><input {if is_array($field_value) && in_array($value.id_reference, $field_value)}checked{/if}
                               type="checkbox" name="{$input.name|escape:'quotes':'UTF-8'}[]"
                               value="{$value.id_reference|intval}"> {$value.name|escape:'quotes':'UTF-8'}</li>
                {/foreach}
            </ul>
        {/if}
    {elseif $input.type == 'currency_group'}
        {assign var='field_value' value=$fields_value[$input.name]}
        {if is_array($input.values) && count($input.values)}
            <ul class="carrier_group">
                {foreach from=$input.values item=value}
                    <li><input {if is_array($field_value) && in_array($value.id_currency, $field_value)}checked{/if}
                               type="checkbox" name="{$input.name|escape:'quotes':'UTF-8'}[]"
                               value="{$value.id_currency|intval}"> {$value.name|escape:'quotes':'UTF-8'}</li>
                {/foreach}
            </ul>
        {/if}
    {elseif $input.type == 'country_group'}
        {assign var='field_value' value=$fields_value[$input.name]}
        {if is_array($input.values) && count($input.values)}
            <ul class="carrier_group">
                {foreach from=$input.values item=value}
                    <li><input {if is_array($field_value) && in_array($value.id_country, $field_value)}checked{/if}
                               type="checkbox" name="{$input.name|escape:'quotes':'UTF-8'}[]"
                               value="{$value.id_country|intval}"> {$value.name|escape:'quotes':'UTF-8'}</li>
                {/foreach}
            </ul>
        {/if}
    {/if}
{/block}

{block name="input_row"}
    {if $input.type == 'title'}
        <h2 class="break_header text-center">{$input.label|escape:'quotes':'UTF-8'}</h2>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{block name="input_row"}
    {if $input.type == 'group_start'}
<div class="{$input.class|escape:'quotes':'UTF-8'}">
    {else}
    {$smarty.block.parent}
    {/if}
    {/block}

    {block name="input_row"}
    {if $input.type == 'group_end'}
</div>
    {else}
    {$smarty.block.parent}
    {/if}
{/block}
