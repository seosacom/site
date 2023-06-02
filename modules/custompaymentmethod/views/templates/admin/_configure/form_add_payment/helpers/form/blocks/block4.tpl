{*
*  2007-2022 PrestaShop
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
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="form-wrapper">

    <div class="form-group">

        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'is_send_mail'}
                    {if isset($input.label)}
                        <label class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}

        {foreach $field as $input}
            {block name="input"}
                {if $input.type == 'radio' && $input.name == 'is_send_mail'}
                    {foreach $input.values as $value}
                        <div class="radio {if isset($input.class)}{$input.class}{/if}">
                            {strip}
                                <label>
                                    <input type="radio"	name="{$input.name}" id="{$value.id}" value="{$value.value|escape:'html':'UTF-8'}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                    {$value.label}
                                </label>
                            {/strip}
                        </div>
                        {if isset($value.p) && $value.p}<p class="help-block">{$value.p}</p>{/if}
                    {/foreach}
                {elseif $input.type == 'switch' && $input.name == 'is_send_mail'}
                    <div class="float-left margin-right-lg">
                                    <span class="switch prestashop-switch fixed-width-lg">
										{foreach $input.values as $value}
                                            <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
										{strip}
                                            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
											{if $value.value == 1}
                                                {l s='Yes' mod='custompaymentmethod'}
                                            {else}
                                                {l s='No' mod='custompaymentmethod'}
                                            {/if}
										</label>
                                        {/strip}
                                        {/foreach}
                                        <a class="slide-button btn"></a>
									</span>
                    </div>
                {/if}
            {/block}
        {/foreach}

        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'confirmation_page_add'}
                    {if isset($input.label)}
                        <label class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}
        {foreach $field as $input}
            {block name="input"}
                {if $input.type == 'radio' && $input.name == 'confirmation_page_add'}
                    {foreach $input.values as $value}
                        <div class="radio {if isset($input.class)}{$input.class}{/if}">
                            {strip}
                                <label>
                                    <input type="radio"	name="{$input.name}" id="{$value.id}" value="{$value.value|escape:'html':'UTF-8'}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                    {$value.label}
                                </label>
                            {/strip}
                        </div>
                        {if isset($value.p) && $value.p}<p class="help-block">{$value.p}</p>{/if}
                    {/foreach}
                {elseif $input.type == 'switch' && $input.name == 'confirmation_page_add'}
                    <div class="float-left margin-right-lg">
                        <span class="switch prestashop-switch fixed-width-lg">
                            {foreach $input.values as $value}
                                <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                {strip}
                                    <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                        {if $value.value == 1}
                                            {l s='Yes' mod='custompaymentmethod'}
                                        {else}
                                            {l s='No' mod='custompaymentmethod'}
                                        {/if}
                                    </label>
                                {/strip}
                            {/foreach}
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                {/if}
            {/block}
        {/foreach}

    </div>

    <div class="form-group">
        <!-- history add -->
        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'add_history'}
                    {if isset($input.label)}
                        <label class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}
        {foreach $field as $input}
            {block name="input"}
                {if $input.type == 'radio' && $input.name == 'add_history'}
                    {foreach $input.values as $value}
                        <div class="radio {if isset($input.class)}{$input.class}{/if}">
                            {strip}
                                <label>
                                    <input type="radio"	name="{$input.name}" id="{$value.id}" value="{$value.value|escape:'html':'UTF-8'}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                    {$value.label}
                                </label>
                            {/strip}
                        </div>
                        {if isset($value.p) && $value.p}<p class="help-block">{$value.p}</p>{/if}
                    {/foreach}
                {elseif $input.type == 'switch' && $input.name == 'add_history'}
                    <div class="float-left">
                        <span class="switch prestashop-switch fixed-width-lg">
                            {foreach $input.values as $value}
                                <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                {strip}
                                <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                        {if $value.value == 1}
                                            {l s='Yes' mod='custompaymentmethod'}
                                        {else}
                                            {l s='No' mod='custompaymentmethod'}
                                        {/if}
                                    </label>
                            {/strip}
                            {/foreach}
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                {/if}
            {/block}
        {/foreach}
        <!-- End add history -->
    </div>

    <div class="form-group">

        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'id_order_state'}
                    {if isset($input.label)}
                        <label class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}

        {foreach $field as $input}
            {block name="input"}
                {if $input.name == 'id_order_state'}
                    {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                        {$input.empty_message}
                        {$input.required = false}
                        {$input.desc = null}
                    {else}
                        <div class="float-left margin-right-lg">
                            <select name="{$input.name|escape:'html':'UTF-8'}"
                                    class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} fixed-width-400"
                                    id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                    {if isset($input.multiple) && $input.multiple} multiple="multiple"{/if}
                                    {if isset($input.size)} size="{$input.size|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}>
                                {if isset($input.options.default)}
                                    <option value="{$input.options.default.value|escape:'html':'UTF-8'}">{$input.options.default.label|escape:'html':'UTF-8'}</option>
                                {/if}
                                {if isset($input.options.optiongroup)}
                                    {foreach $input.options.optiongroup.query AS $optiongroup}
                                        <optgroup label="{$optiongroup[$input.options.optiongroup.label]}">
                                            {foreach $optiongroup[$input.options.options.query] as $option}
                                                <option value="{$option[$input.options.options.id]}"
                                                        {if isset($input.multiple)}
                                                            {foreach $fields_value[$input.name] as $field_value}
                                                                {if $field_value == $option[$input.options.options.id]}selected="selected"{/if}
                                                            {/foreach}
                                                        {else}
                                                            {if $fields_value[$input.name] == $option[$input.options.options.id]}selected="selected"{/if}
                                                        {/if}
                                                >{$option[$input.options.options.name]}</option>
                                            {/foreach}
                                        </optgroup>
                                    {/foreach}
                                {else}
                                    {foreach $input.options.query AS $option}
                                        {if is_object($option)}
                                            <option value="{$option->$input.options.id}"
                                                    {if isset($input.multiple)}
                                                        {foreach $fields_value[$input.name] as $field_value}
                                                            {if $field_value == $option->$input.options.id}
                                                                selected="selected"
                                                            {/if}
                                                        {/foreach}
                                                    {else}
                                                        {if $fields_value[$input.name] == $option->$input.options.id}
                                                            selected="selected"
                                                        {/if}
                                                    {/if}
                                            >{$option->$input.options.name}</option>
                                        {elseif $option == "-"}
                                            <option value="">-</option>
                                        {else}
                                            <option value="{$option[$input.options.id]}"
                                                    {if isset($input.multiple)}
                                                        {foreach $fields_value[$input.name] as $field_value}
                                                            {if $field_value == $option[$input.options.id]}
                                                                selected="selected"
                                                            {/if}
                                                        {/foreach}
                                                    {else}
                                                        {if $fields_value[$input.name] == $option[$input.options.id]}
                                                            selected="selected"
                                                        {/if}
                                                    {/if}
                                            >{$option[$input.options.name]}</option>

                                        {/if}
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                    {/if}
                {/if}
            {/block}
        {/foreach}

    </div>

</div>
