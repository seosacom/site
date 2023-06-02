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

        <div class="col-lg-4 text-right">
            {foreach $field as $input}
                {block name="label"}
                    {if $input.name == 'confirmation_page'}
                        {if isset($input.label)}
                            <label class="control-label" data-toggle="tooltip" data-html="true" title="">
                                {$input.label}
                            </label>
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

        <div class="col-lg-8">
            {foreach $field as $input}
                {block name="input"}
                    {if $input.type == 'radio' && $input.name == 'confirmation_page'}
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
                    {elseif $input.type == 'switch' && $input.name == 'confirmation_page'}
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

    </div>

    <div class="form-group">

        <div class="col-lg-4 text-right">
            {foreach $field as $input}
                {block name="label"}
                    {if $input.name == 'view_message_field'}
                        {if isset($input.label)}
                            <label class="control-label" data-toggle="tooltip" data-html="true" title="">
                                {$input.label}
                            </label>
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

        <div class="col-lg-8">
            {foreach $field as $input}
                {block name="input"}
                    {if $input.type == 'radio' && $input.name == 'view_message_field'}
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
                    {elseif $input.type == 'switch' && $input.name == 'view_message_field'}
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
                    {if $input.name == 'required_message_field'}
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
                    {if $input.type == 'radio' && $input.name == 'required_message_field'}
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
                    {elseif $input.type == 'switch' && $input.name == 'required_message_field'}
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
        </div>

    </div>

    <div class="form-group">

        <div class="col-lg-4 text-right">
            {foreach $field as $input}
                {block name="label"}
                    {if $input.name == 'name_message_field'}
                        {if isset($input.label)}
                            <label class="control-label" data-toggle="tooltip" data-html="true" title="">
                                {$input.label}
                            </label>
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

        <div class="col-lg-8">
            {foreach $field as $input}
                {block name="input"}
                    {if $input.name == 'name_message_field'}
                        {if isset($input.lang) AND $input.lang}
                        {if $languages|count > 1}
                            <div class="row">
                                {/if}
                                {foreach $languages as $language}
                                    {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                    {if $languages|count > 1}
                                        <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                        <div class="col-lg-9">
                                    {/if}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                    <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
                                            <span class="text-count-down">{$input.maxchar|intval}</span>
                                        </span>
                                    {/if}
                                    {if isset($input.prefix)}
                                        <span class="input-group-addon">
                                            {$input.prefix}
                                        </span>
                                    {/if}
                                    <input type="text"
                                           id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                           name="{$input.name}_{$language.id_lang}"
                                           class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                           value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                           onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                            {if isset($input.size)} size="{$input.size}"{/if}
                                            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                            {if isset($input.required) && $input.required} required="required" {/if}
                                            {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                    {if isset($input.suffix)}
                                        <span class="input-group-addon">
                                            {$input.suffix}
                                        </span>
                                    {/if}
                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                    </div>
                                {/if}
                                    {if $languages|count > 1}
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                {$language.iso_code}
                                                <i class="icon-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                {foreach from=$languages item=language}
                                                    <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                        </div>
                                    {/if}
                                {/foreach}
                                {if isset($input.maxchar) && $input.maxchar}
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            {foreach from=$languages item=language}
                                            countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                            {/foreach}
                                        });
                                    </script>
                                {/if}
                                {if $languages|count > 1}
                            </div>
                        {/if}
                        {else}
                        {if $input.type == 'tags'}
                        {literal}
                            <script type="text/javascript">
                                $().ready(function () {
                                    var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                    $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                    $({/literal}'#{$table}{literal}_form').submit( function() {
                                        $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                    });
                                });
                            </script>
                        {/literal}
                        {/if}
                            {assign var='value_text' value=$fields_value[$input.name]}
                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                            <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                {/if}
                                {if isset($input.maxchar) && $input.maxchar}
                                    <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                {/if}
                                {if isset($input.prefix)}
                                    <span class="input-group-addon">
                                        {$input.prefix}
                                    </span>
                                {/if}

                                <!-- input block -->
                                <div class="float-left">
                                    <input type="text"
                                           name="{$input.name}"
                                           id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                           value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                           class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                            {if isset($input.size)} size="{$input.size}"{/if}
                                            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                            {if isset($input.required) && $input.required } required="required" {/if}
                                            {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                    />
                                </div>
                                {if isset($input.suffix)}
                                    <span class="input-group-addon">
                                        {$input.suffix}
                                    </span>
                                {/if}

                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                            </div>
                        {/if}
                        {if isset($input.maxchar) && $input.maxchar}
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                });
                            </script>
                        {/if}
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

    </div>

    <div class="form-group">

        <div class="col-lg-4 text-right">
            {foreach $field as $input}
                {block name="label"}
                    {if $input.name == 'error_message_field'}
                        {if isset($input.label)}
                            <label class="control-label" data-toggle="tooltip" data-html="true" title="">
                                {$input.label}
                            </label>
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

        <div class="col-lg-8">
            {foreach $field as $input}
                {block name="input"}
                    {if $input.name == 'error_message_field'}
                        {if isset($input.lang) AND $input.lang}
                        {if $languages|count > 1}
                            <div class="row">
                                {/if}
                                {foreach $languages as $language}
                                    {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                    {if $languages|count > 1}
                                        <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                        <div class="col-lg-9">
                                    {/if}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                    <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
                                            <span class="text-count-down">{$input.maxchar|intval}</span>
                                        </span>
                                    {/if}
                                    {if isset($input.prefix)}
                                        <span class="input-group-addon">
                                            {$input.prefix}
                                        </span>
                                    {/if}
                                    <input type="text"
                                           id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                           name="{$input.name}_{$language.id_lang}"
                                           class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                           value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                           onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                            {if isset($input.size)} size="{$input.size}"{/if}
                                            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                            {if isset($input.required) && $input.required} required="required" {/if}
                                            {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                    {if isset($input.suffix)}
                                        <span class="input-group-addon">
                                            {$input.suffix}
                                        </span>
                                    {/if}
                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                    </div>
                                {/if}
                                    {if $languages|count > 1}
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                {$language.iso_code}
                                                <i class="icon-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                {foreach from=$languages item=language}
                                                    <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                        </div>
                                    {/if}
                                {/foreach}
                                {if isset($input.maxchar) && $input.maxchar}
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            {foreach from=$languages item=language}
                                            countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                            {/foreach}
                                        });
                                    </script>
                                {/if}
                                {if $languages|count > 1}
                            </div>
                        {/if}
                        {else}
                        {if $input.type == 'tags'}
                        {literal}
                            <script type="text/javascript">
                                $().ready(function () {
                                    var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                    $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                    $({/literal}'#{$table}{literal}_form').submit( function() {
                                        $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                    });
                                });
                            </script>
                        {/literal}
                        {/if}
                            {assign var='value_text' value=$fields_value[$input.name]}
                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                            <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                {/if}
                                {if isset($input.maxchar) && $input.maxchar}
                                    <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                {/if}
                                {if isset($input.prefix)}
                                    <span class="input-group-addon">
                                        {$input.prefix}
                                    </span>
                                {/if}

                                <!-- input block -->
                                <div class="float-left">
                                    <input type="text"
                                           name="{$input.name}"
                                           id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                           value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                           class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                            {if isset($input.size)} size="{$input.size}"{/if}
                                            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                            {if isset($input.required) && $input.required } required="required" {/if}
                                            {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                    />
                                </div>
                                {if isset($input.suffix)}
                                    <span class="input-group-addon">
                                        {$input.suffix}
                                    </span>
                                {/if}

                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                            </div>
                        {/if}
                        {if isset($input.maxchar) && $input.maxchar}
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                });
                            </script>
                        {/if}
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

    </div>

    <div class="form-group">

        <div class="col-lg-4 text-right">
            {foreach $field as $input}
                {block name="label"}
                    {if $input.name == 'id_cms'}
                        {if isset($input.label)}
                            <label class="control-label" data-toggle="tooltip" data-html="true" title="">
                                {$input.label}
                            </label>
                        {/if}
                    {/if}
                {/block}
            {/foreach}
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-9">
                    {foreach $field as $input}
                        {block name="input"}
                            {if $input.name == 'id_cms'}
                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                    {$input.empty_message}
                                    {$input.required = false}
                                    {$input.desc = null}
                                {else}
                                    <select name="{$input.name|escape:'html':'UTF-8'}"
                                            class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} w-100"
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
                                {/if}
                            {/if}
                        {/block}
                    {/foreach}
                </div>
            </div>
        </div>

    </div>

</div>
