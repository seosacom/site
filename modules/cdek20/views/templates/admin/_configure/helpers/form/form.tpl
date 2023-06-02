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

{extends file="helpers/form/form.tpl"}

{block name="fieldset"}
    {capture name='fieldset_name'}{counter name='fieldset_name'}{/capture}
    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        {$field.title}
                    </div>
                {/block}
            {elseif $key == 'description' && $field}
                <div class="alert alert-info">{$field}</div>
            {elseif $key == 'warning' && $field}
                <div class="alert alert-warning">{$field}</div>
            {elseif $key == 'success' && $field}
                <div class="alert alert-success">{$field}</div>
            {elseif $key == 'error' && $field}
                <div class="alert alert-danger">{$field}</div>
            {elseif $key == 'input'}
                <div class="form-wrapper">
                    {foreach $field as $input}
                        {block name="input_row"}
                            {if $input.type != 'hidden_block'}
                            <div class="form-group{if isset($input.form_group_class)} {$input.form_group_class}{/if}{if $input.type == 'hidden'} hide{/if}{if isset($input.name)} {$input.name}{/if}"{if isset($input.name) && $input.name == 'id_state'} id="contains_states"{if !$contains_states} style="display:none;"{/if}{/if}{if isset($tabs) && isset($input.tab)} data-tab-id="{$input.tab}"{/if}>
                                {if $input.type == 'hidden'}
                                    <input type="hidden" name="{$input.name}" id="{$input.name}" value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                {else}
                                    {block name="label"}
                                        {if isset($input.label)}
                                            <label class="control-label col-lg-3{if isset($input.required) && $input.required && $input.type != 'radio'} required{/if}">
                                                {if isset($input.hint)}
                                                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{if is_array($input.hint)}
													{foreach $input.hint as $hint}
														{if is_array($hint)}
															{$hint.text|escape:'quotes'}
														{else}
															{$hint|escape:'quotes'}
														{/if}
													{/foreach}
												{else}
													{$input.hint|escape:'quotes'}
												{/if}">
                                                    {/if}
                                                    {$input.label}
                                                    {if isset($input.hint)}
                                                </span>
                                                {/if}
                                            </label>
                                        {/if}

                                    {/block}

                                    {block name="field"}
                                        <div class="col-lg-{if isset($input.col)}{$input.col|intval}{elseif $input.type == 'hr'}12{else}9{/if}{if !isset($input.label) && $input.type != 'hr'} col-lg-offset-3{/if}" {$input.type} {if $input.type == 'switch_one_package'}style="display: flex; align-items: center;"{/if}>
                                            {block name="input"}

                                                {if $input.type == 'text' || $input.type == 'tags'}
                                                {if isset($input.lang) AND $input.lang}
                                                {if $languages|count > 1}
                                                    <div class="form-group">
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
                                                        <input type="text"
                                                               name="{$input.name}"
                                                               id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                               value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                               class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                                {if isset($input.size)} size="{$input.size}"{/if}
                                                                {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                {if isset($input.required) && $input.required } required="required" {/if}
                                                                {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                        />
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

                                                {elseif $input.type == 'text_dimensions'}


                                                        {foreach $field as $input}

                                                        {if $input.name == 'default_width'}
                                                        {if isset($input.lang) AND $input.lang}
                                                        {if $languages|count > 1}
                                                            <div class="form-group">
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
                                                                <input type="text"
                                                                       name="{$input.name}"
                                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                                />
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
                                                        {if $input.name == 'default_height'}
                                                        {if isset($input.lang) AND $input.lang}
                                                        {if $languages|count > 1}
                                                            <div class="form-group">
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
                                                                <input type="text"
                                                                       name="{$input.name}"
                                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                                />
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
                                                        {if $input.name == 'default_length'}
                                                        {if isset($input.lang) AND $input.lang}
                                                        {if $languages|count > 1}
                                                            <div class="form-group">
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
                                                                <input type="text"
                                                                       name="{$input.name}"
                                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                                />
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

                                                        {/foreach}



                                                        {foreach $field as $input}

                                                            {if $input.name == 'default_width'}
                                                                <p class="help-block float-left">
                                                                    {if is_array($input.desc_dimensions)}
                                                                        {foreach $input.desc_dimensions as $p}
                                                                            {if is_array($p)}
                                                                                <span id="{$p.id}">{$p.text}</span><br />
                                                                            {else}
                                                                                {$p}<br />
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.desc_dimensions}
                                                                    {/if}
                                                                </p>

                                                            {/if}

                                                        {/foreach}


                                                {elseif $input.type == 'textbutton'}
                                                    {assign var='value_text' value=$fields_value[$input.name]}
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            {if isset($input.maxchar)}
                                                            <div class="input-group">
                                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon">
                                                                    <span class="text-count-down">{$input.maxchar|intval}</span>
                                                                </span>
                                                                {/if}
                                                                <input type="text"
                                                                       name="{$input.name}"
                                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                                />
                                                                {if isset($input.suffix)}{$input.suffix}{/if}
                                                                {if isset($input.maxchar) && $input.maxchar}
                                                            </div>
                                                            {/if}
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <button type="button" class="btn btn-default{if isset($input.button.attributes['class'])} {$input.button.attributes['class']}{/if}{if isset($input.button.class)} {$input.button.class}{/if}"
                                                            {foreach from=$input.button.attributes key=name item=value}
                                                                {if $name|lower != 'class'}
                                                                    {$name|escape:'html':'UTF-8'}="{$value|escape:'html':'UTF-8'}"
                                                                {/if}
                                                            {/foreach} >
                                                            {$input.button.label}
                                                            </button>
                                                        </div>
                                                    </div>
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <script type="text/javascript">
                                                        $(document).ready(function() {
                                                            countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                                        });
                                                    </script>
                                                {/if}
                                                {elseif $input.type == 'swap'}
                                                    <div class="form-group">
                                                        <div class="col-lg-9">
                                                            <div class="form-control-static row">
                                                                <div class="col-xs-6">
                                                                    <select {if isset($input.size)}size="{$input.size|escape:'html':'UTF-8'}"{/if}{if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if} class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}" id="availableSwap" name="{$input.name|escape:'html':'UTF-8'}_available[]" multiple="multiple">
                                                                        {foreach $input.options.query AS $option}
                                                                            {if is_object($option)}
                                                                                {if !in_array($option->$input.options.id, $fields_value[$input.name])}
                                                                                    <option value="{$option->$input.options.id}">{$option->$input.options.name}</option>
                                                                                {/if}
                                                                            {elseif $option == "-"}
                                                                                <option value="">-</option>
                                                                            {else}
                                                                                {if !in_array($option[$input.options.id], $fields_value[$input.name])}
                                                                                    <option value="{$option[$input.options.id]}">{$option[$input.options.name]}</option>
                                                                                {/if}
                                                                            {/if}
                                                                        {/foreach}
                                                                    </select>
                                                                    <a href="#" id="addSwap" class="btn btn-default btn-block">{l s='Add' mod='cdek20'} <i class="icon-arrow-right"></i></a>
                                                                </div>
                                                                <div class="col-xs-6">
                                                                    <select {if isset($input.size)}size="{$input.size|escape:'html':'UTF-8'}"{/if}{if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if} class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}" id="selectedSwap" name="{$input.name|escape:'html':'UTF-8'}_selected[]" multiple="multiple">
                                                                        {foreach $input.options.query AS $option}
                                                                            {if is_object($option)}
                                                                                {if in_array($option->$input.options.id, $fields_value[$input.name])}
                                                                                    <option value="{$option->$input.options.id}">{$option->$input.options.name}</option>
                                                                                {/if}
                                                                            {elseif $option == "-"}
                                                                                <option value="">-</option>
                                                                            {else}
                                                                                {if in_array($option[$input.options.id], $fields_value[$input.name])}
                                                                                    <option value="{$option[$input.options.id]}">{$option[$input.options.name]}</option>
                                                                                {/if}
                                                                            {/if}
                                                                        {/foreach}
                                                                    </select>
                                                                    <a href="#" id="removeSwap" class="btn btn-default btn-block"><i class="icon-arrow-left"></i> {l s='Remove' mod='cdek20'}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {elseif $input.type == 'select'}
                                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                                    {$input.empty_message}
                                                    {$input.required = false}
                                                    {$input.desc = null}
                                                {else}
                                                    <select name="{$input.name|escape:'html':'UTF-8'}"
                                                            class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} fixed-width-xl"
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
                                                {elseif $input.type == 'select_currency'}

                                                {foreach $field as $input}

                                                {if $input.name == 'contract_currency'}
                                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                                    {$input.empty_message}
                                                    {$input.required = false}
                                                    {$input.desc = null}
                                                {else}
                                                    <select name="{$input.name|escape:'html':'UTF-8'}"
                                                            class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} fixed-width-xl"
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

                                                {if $input.name == 'vat'}
                                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                                    {$input.empty_message}
                                                    {$input.required = false}
                                                    {$input.desc = null}
                                                {else}
                                                    <select name="{$input.name|escape:'html':'UTF-8'}"
                                                            class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} "
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

                                                {/foreach}


                                                        {foreach $field as $input}

                                                            {if $input.name == 'contract_currency'}
                                                                <p class="help-block float-left">
                                                                    {if is_array($input.desc_currency)}
                                                                        {foreach $input.desc_currency as $p}
                                                                            {if is_array($p)}
                                                                                <span id="{$p.id}">{$p.text}</span><br />
                                                                            {else}
                                                                                {$p}<br />
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.desc_currency}
                                                                    {/if}
                                                                </p>

                                                            {/if}

                                                        {/foreach}

                                                {elseif $input.type == 'select_unit'}

                                                {foreach $field as $input}

                                                {if $input.name == 'weight_unit'}
                                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                                    {$input.empty_message}
                                                    {$input.required = false}
                                                    {$input.desc = null}
                                                {else}
                                                    <select name="{$input.name|escape:'html':'UTF-8'}"
                                                            class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
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

                                                {if $input.name == 'volume_unit'}
                                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                                    {$input.empty_message}
                                                    {$input.required = false}
                                                    {$input.desc = null}
                                                {else}
                                                    <select name="{$input.name|escape:'html':'UTF-8'}"
                                                            class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} "
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

                                                {/foreach}

                                                {foreach $field as $input}

                                                {if $input.name == 'weight_unit'}
                                                    <p class="help-block float-left">
                                                        {if is_array($input.desc_unit)}
                                                            {foreach $input.desc_unit as $p}
                                                                {if is_array($p)}
                                                                    <span id="{$p.id}">{$p.text}</span><br />
                                                                {else}
                                                                    {$p}<br />
                                                                {/if}
                                                            {/foreach}
                                                        {else}
                                                            {$input.desc_unit}
                                                        {/if}
                                                    </p>

                                                {/if}

                                                {/foreach}


                                                {elseif $input.type == 'radio'}
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
                                                {elseif $input.type == 'switch'}
                                                    <span class="switch prestashop-switch fixed-width-lg">
                                                        {foreach $input.values as $value}
                                                            <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                            {strip}
                                                                <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                                                    {if $value.value == 1}
                                                                        {l s='Yes' mod='cdek20'}
                                                                    {else}
                                                                        {l s='No' mod='cdek20'}
                                                                    {/if}
                                                                </label>
                                                            {/strip}
                                                        {/foreach}
                                                        <a class="slide-button btn"></a>
                                                    </span>

                                                {elseif $input.type == 'switch_part_deliv'}

                                                    <div class="float-left">

                                                        {foreach $field as $input}

                                                            {if $input.name == 'part_deliv'}
                                                                <span class="switch prestashop-switch fixed-width-md float-left margin-right-lg">
                                                                    {foreach $input.values as $value}
                                                                        <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                        {strip}
                                                                            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                                                                {if $value.value == 1}
                                                                                    {l s='Yes' mod='cdek20'}
                                                                                {else}
                                                                                    {l s='No' mod='cdek20'}
                                                                                {/if}
                                                                            </label>
                                                                        {/strip}
                                                                    {/foreach}
                                                                    <a class="slide-button btn"></a>
                                                                </span>
                                                            {/if}

                                                        {/foreach}

                                                    </div>

                                                    <div class="float-left" style="max-width: calc(100% - 134px);">
                                                        {foreach $field as $input}

                                                            {if $input.name == 'part_deliv'}
                                                                <p class="help-block">
                                                                    {if is_array($input.desc_part_deliv)}
                                                                        {foreach $input.desc_part_deliv as $p}
                                                                            {if is_array($p)}
                                                                                <span id="{$p.id}">{$p.text}</span><br />
                                                                            {else}
                                                                                {$p}<br />
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.desc_part_deliv}
                                                                    {/if}
                                                                </p>

                                                            {/if}

                                                        {/foreach}
                                                    </div>

                                                {elseif $input.type == 'switch_one_package'}

                                                    <div class="float-left">

                                                        {foreach $field as $input}

                                                            {if $input.name == 'one_package'}
                                                                <span class="switch prestashop-switch fixed-width-md float-left margin-right-lg">
                                                                    {foreach $input.values as $value}
                                                                        <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                        {strip}
                                                                            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                                                                {if $value.value == 1}
                                                                                    {l s='Yes' mod='cdek20'}
                                                                                {else}
                                                                                    {l s='No' mod='cdek20'}
                                                                                {/if}
                                                                            </label>
                                                                        {/strip}
                                                                    {/foreach}
                                                                    <a class="slide-button btn"></a>
                                                                </span>
                                                            {/if}

                                                        {/foreach}

                                                    </div>

                                                    <div class="float-left" style="max-width: calc(100% - 134px);">
                                                        {foreach $field as $input}

                                                            {if $input.name == 'one_package'}
                                                                <p class="help-block">
                                                                    {if is_array($input.desc_one_package)}
                                                                        {foreach $input.desc_one_package as $p}
                                                                            {if is_array($p)}
                                                                                <span id="{$p.id}">{$p.text}</span><br />
                                                                            {else}
                                                                                {$p}<br />
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.desc_one_package}
                                                                    {/if}
                                                                </p>

                                                            {/if}

                                                        {/foreach}
                                                    </div>

                                                {elseif $input.type == 'switch_all_is_one_package'}
                                                    <div class="float-left">
                                                        {foreach $field as $input}
                                                            {if $input.name == 'all_is_one_package'}
                                                                <span class="switch prestashop-switch fixed-width-md float-left margin-right-lg">
                                                                    {foreach $input.values as $value}
                                                                        <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                        {strip}
                                                                            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                                                                {if $value.value == 1}
                                                                                    {l s='Yes' mod='cdek20'}
                                                                                {else}
                                                                                    {l s='No' mod='cdek20'}
                                                                                {/if}
                                                                            </label>
                                                                        {/strip}
                                                                    {/foreach}
                                                                    <a class="slide-button btn"></a>
                                                                </span>
                                                            {/if}

                                                        {/foreach}

                                                    </div>
                                                    <div class="float-left" style="max-width: calc(100% - 134px);">
                                                        {foreach $field as $input}

                                                            {if $input.name == 'all_is_one_package'}
                                                                <p class="help-block">
                                                                    {if is_array($input.desc_all_is_one_package)}
                                                                        {foreach $input.desc_all_is_one_package as $p}
                                                                            {if is_array($p)}
                                                                                <span id="{$p.id}">{$p.text}</span><br />
                                                                            {else}
                                                                                {$p}<br />
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.desc_all_is_one_package}
                                                                    {/if}
                                                                </p>

                                                            {/if}

                                                        {/foreach}
                                                    </div>
                                                {elseif $input.type == 'switch_all_one_box'}
                                                    <div class="float-left">
                                                        {foreach $field as $input}
                                                            {if $input.name == 'all_one_box'}
                                                                <span class="switch prestashop-switch fixed-width-md float-left margin-right-lg">
                                                                    {foreach $input.values as $value}
                                                                        <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                        {strip}
                                                                        <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                                                                {if $value.value == 1}
                                                                                    {l s='Yes' mod='cdek20'}
                                                                                {else}
                                                                                    {l s='No' mod='cdek20'}
                                                                                {/if}
                                                                            </label>
                                                                    {/strip}
                                                                    {/foreach}
                                                                    <a class="slide-button btn"></a>
                                                                </span>
                                                            {/if}

                                                        {/foreach}

                                                    </div>
                                                    <div class="float-left" style="max-width: calc(100% - 134px);">
                                                        {foreach $field as $input}
                                                            {if $input.name == 'all_one_box'}
                                                                <p class="help-block">
                                                                    {if is_array($input.desc_all_one_box)}
                                                                        {foreach $input.desc_all_one_box as $p}
                                                                            {if is_array($p)}
                                                                                <span id="{$p.id}">{$p.text}</span><br />
                                                                            {else}
                                                                                {$p}<br />
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.desc_all_one_box}
                                                                    {/if}
                                                                </p>

                                                            {/if}

                                                        {/foreach}
                                                    </div>

                                                {elseif $input.type == 'textarea'}
                                                {if isset($input.maxchar) && $input.maxchar}<div class="input-group">{/if}
                                                    {assign var=use_textarea_autosize value=true}
                                                    {if isset($input.lang) AND $input.lang}
                                                    {foreach $languages as $language}
                                                    {if $languages|count > 1}
                                                        <div class="form-group translatable-field lang-{$language.id_lang}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                                                        <div class="col-lg-9">
                                                    {/if}
                                                        {if isset($input.maxchar) && $input.maxchar}
                                                            <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
                                                                <span class="text-count-down">{$input.maxchar|intval}</span>
                                                            </span>
                                                        {/if}
                                                        <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name}_{$language.id_lang}" id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_{$language.id_lang}" class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte{else}textarea-autosize{/if}{if isset($input.class)} {$input.class}{/if}"{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}>{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}</textarea>
                                                    {if $languages|count > 1}
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                                {$language.iso_code}
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                {foreach from=$languages item=language}
                                                                    <li>
                                                                        <a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a>
                                                                    </li>
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
                                                    {else}
                                                    {if isset($input.maxchar) && $input.maxchar}
                                                        <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
                                                            <span class="text-count-down">{$input.maxchar|intval}</span>
                                                        </span>
                                                    {/if}
                                                        <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name}" id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}" {if isset($input.cols)}cols="{$input.cols}"{/if} {if isset($input.rows)}rows="{$input.rows}"{/if} class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte{else}textarea-autosize{/if}{if isset($input.class)} {$input.class}{/if}"{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}>{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
                                                    {if isset($input.maxchar) && $input.maxchar}
                                                        <script type="text/javascript">
                                                            $(document).ready(function(){
                                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                                            });
                                                        </script>
                                                    {/if}
                                                    {/if}
                                                    {if isset($input.maxchar) && $input.maxchar}</div>{/if}
                                                {elseif $input.type == 'checkbox'}
                                                {if isset($input.expand)}
                                                    <a class="btn btn-default show_checkbox{if strtolower($input.expand.default) == 'hide'} hidden{/if}" href="#">
                                                        <i class="icon-{$input.expand.show.icon}"></i>
                                                        {$input.expand.show.text}
                                                        {if isset($input.expand.print_total) && $input.expand.print_total > 0}
                                                            <span class="badge">{$input.expand.print_total}</span>
                                                        {/if}
                                                    </a>
                                                    <a class="btn btn-default hide_checkbox{if strtolower($input.expand.default) == 'show'} hidden{/if}" href="#">
                                                        <i class="icon-{$input.expand.hide.icon}"></i>
                                                        {$input.expand.hide.text}
                                                        {if isset($input.expand.print_total) && $input.expand.print_total > 0}
                                                            <span class="badge">{$input.expand.print_total}</span>
                                                        {/if}
                                                    </a>
                                                {/if}
                                                {foreach $input.values.query as $value}
                                                    {assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]}
                                                    <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">
                                                        {strip}
                                                            <label for="{$id_checkbox}">
                                                                <input type="checkbox" name="{$id_checkbox}" id="{$id_checkbox}" class="{if isset($input.class)}{$input.class}{/if}"{if isset($value.val)} value="{$value.val|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
                                                                {$value[$input.values.name]}
                                                            </label>
                                                        {/strip}
                                                    </div>
                                                {/foreach}
                                                {elseif $input.type == 'change-password'}
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <button type="button" id="{$input.name}-btn-change" class="btn btn-default">
                                                                <i class="icon-lock"></i>
                                                                {l s='Change password...' mod='cdek20'}
                                                            </button>
                                                            <div id="{$input.name}-change-container" class="form-password-change well hide">
                                                                <div class="form-group">
                                                                    <label for="old_passwd" class="control-label col-lg-2 required">
                                                                        {l s='Current password' mod='cdek20'}
                                                                    </label>
                                                                    <div class="col-lg-10">
                                                                        <div class="input-group fixed-width-lg">
                                                                            <span class="input-group-addon">
                                                                                <i class="icon-unlock"></i>
                                                                            </span>
                                                                            <input type="password" id="old_passwd" name="old_passwd" class="form-control" value="" required="required" autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div class="form-group">
                                                                    <label for="{$input.name}" class="required control-label col-lg-2">
                                                                        <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="{l s='Password should be at least 8 characters long.' mod='cdek20'}">
                                                                            {l s='New password' mod='cdek20'}
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-lg-9">
                                                                        <div class="input-group fixed-width-lg">
                                                                            <span class="input-group-addon">
                                                                                <i class="icon-key"></i>
                                                                            </span>
                                                                            <input type="password" id="{$input.name}" name="{$input.name}" class="{if isset($input.class)}{$input.class}{/if}" value="" required="required" autocomplete="off"/>
                                                                        </div>
                                                                        <span id="{$input.name}-output"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="{$input.name}2" class="required control-label col-lg-2">
                                                                        {l s='Confirm password' mod='cdek20'}
                                                                    </label>
                                                                    <div class="col-lg-4">
                                                                        <div class="input-group fixed-width-lg">
                                                                            <span class="input-group-addon">
                                                                                <i class="icon-key"></i>
                                                                            </span>
                                                                            <input type="password" id="{$input.name}2" name="{$input.name}2" class="{if isset($input.class)}{$input.class}{/if}" value="" autocomplete="off"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-lg-10 col-lg-offset-2">
                                                                        <input type="text" class="form-control fixed-width-md pull-left" id="{$input.name}-generate-field" disabled="disabled">
                                                                        <button type="button" id="{$input.name}-generate-btn" class="btn btn-default">
                                                                            <i class="icon-random"></i>
                                                                            {l s='Generate password' mod='cdek20'}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-lg-10 col-lg-offset-2">
                                                                        <p class="checkbox">
                                                                            <label for="{$input.name}-checkbox-mail">
                                                                                <input name="passwd_send_email" id="{$input.name}-checkbox-mail" type="checkbox" checked="checked">
                                                                                {l s='Send me this new password by Email' mod='cdek20'}
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <button type="button" id="{$input.name}-cancel-btn" class="btn btn-default">
                                                                            <i class="icon-remove"></i>
                                                                            {l s='Cancel' mod='cdek20'}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        $(function(){
                                                            var $oldPwd = $('#old_passwd');
                                                            var $passwordField = $('#{$input.name}');
                                                            var $output = $('#{$input.name}-output');
                                                            var $generateBtn = $('#{$input.name}-generate-btn');
                                                            var $generateField = $('#{$input.name}-generate-field');
                                                            var $cancelBtn = $('#{$input.name}-cancel-btn');

                                                            var feedback = [
                                                                { badge: 'text-danger', text: '{l s="Invalid" mod='cdek20' js=true}' },
                                                                { badge: 'text-warning', text: '{l s="Okay" mod='cdek20' js=true}' },
                                                                { badge: 'text-success', text: '{l s="Good" mod='cdek20' js=true}' },
                                                                { badge: 'text-success', text: '{l s="Fabulous" mod='cdek20' js=true}' }
                                                            ];
                                                            $.passy.requirements.length.min = 8;
                                                            $.passy.requirements.characters = 'DIGIT';
                                                            $passwordField.passy(function(strength, valid) {
                                                                $output.text(feedback[strength].text);
                                                                $output.removeClass('text-danger').removeClass('text-warning').removeClass('text-success');
                                                                $output.addClass(feedback[strength].badge);
                                                                if (valid){
                                                                    $output.show();
                                                                }
                                                                else {
                                                                    $output.hide();
                                                                }
                                                            });
                                                            var $container = $('#{$input.name}-change-container');
                                                            var $changeBtn = $('#{$input.name}-btn-change');
                                                            var $confirmPwd = $('#{$input.name}2');

                                                            $changeBtn.on('click',function(){
                                                                $container.removeClass('hide');
                                                                $changeBtn.addClass('hide');
                                                            });
                                                            $generateBtn.click(function() {
                                                                $generateField.passy( 'generate', 8 );
                                                                var generatedPassword = $generateField.val();
                                                                $passwordField.val(generatedPassword);
                                                                $confirmPwd.val(generatedPassword);
                                                            });
                                                            $cancelBtn.on('click',function() {
                                                                $container.find("input").val("");
                                                                $container.addClass('hide');
                                                                $changeBtn.removeClass('hide');
                                                            });

                                                            $.validator.addMethod('password_same', function(value, element) {
                                                                return $passwordField.val() == $confirmPwd.val();
                                                            }, '{l s="Invalid password confirmation" mod='cdek20' js=true}');

                                                            $('#employee_form').validate({
                                                                rules: {
                                                                    "email": {
                                                                        email: true
                                                                    },
                                                                    "{$input.name}" : {
                                                                        minlength: 8
                                                                    },
                                                                    "{$input.name}2": {
                                                                        password_same: true
                                                                    },
                                                                    "old_passwd" : {},
                                                                },
                                                                // override jquery validate plugin defaults for bootstrap 3
                                                                highlight: function(element) {
                                                                    $(element).closest('.form-group').addClass('has-error');
                                                                },
                                                                unhighlight: function(element) {
                                                                    $(element).closest('.form-group').removeClass('has-error');
                                                                },
                                                                errorElement: 'span',
                                                                errorClass: 'help-block',
                                                                errorPlacement: function(error, element) {
                                                                    if(element.parent('.input-group').length) {
                                                                        error.insertAfter(element.parent());
                                                                    } else {
                                                                        error.insertAfter(element);
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                {elseif $input.type == 'password'}
                                                    <div class="input-group fixed-width-lg">
                                                        <span class="input-group-addon">
                                                            <i class="icon-key"></i>
                                                        </span>
                                                        <input type="password"
                                                               id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                               name="{$input.name}"
                                                               class="{if isset($input.class)}{$input.class}{/if}"
                                                               value=""
                                                               {if isset($input.autocomplete) && !$input.autocomplete}autocomplete="off"{/if}
                                                                {if isset($input.required) && $input.required } required="required" {/if} />
                                                    </div>

                                                {elseif $input.type == 'birthday'}
                                                    <div class="form-group">
                                                        {foreach $input.options as $key => $select}
                                                            <div class="col-lg-2">
                                                                <select name="{$key}" class="fixed-width-lg{if isset($input.class)} {$input.class}{/if}">
                                                                    <option value="">-</option>
                                                                    {if $key == 'months'}

                                                                        {foreach $select as $k => $v}
                                                                            <option value="{$k}" {if $k == $fields_value[$key]}selected="selected"{/if}>{l s=$v}</option>
                                                                        {/foreach}
                                                                    {else}
                                                                        {foreach $select as $v}
                                                                            <option value="{$v}" {if $v == $fields_value[$key]}selected="selected"{/if}>{$v}</option>
                                                                        {/foreach}
                                                                    {/if}
                                                                </select>
                                                            </div>
                                                        {/foreach}
                                                    </div>
                                                {elseif $input.type == 'group'}
                                                    {assign var=groups value=$input.values}
                                                    {include file='helpers/form/form_group.tpl'}
                                                {elseif $input.type == 'shop'}
                                                    {$input.html}
                                                {elseif $input.type == 'categories'}
                                                    {$categories_tree}
                                                {elseif $input.type == 'file'}
                                                    {$input.file}
                                                {elseif $input.type == 'categories_select'}
                                                    {$input.category_tree}
                                                {elseif $input.type == 'asso_shop' && isset($asso_shop) && $asso_shop}
                                                    {$asso_shop}
                                                {elseif $input.type == 'color'}
                                                    <div class="form-group">
                                                        <div class="col-lg-2">
                                                            <div class="row">
                                                                <div class="input-group">
                                                                    <input type="color"
                                                                           data-hex="true"
                                                                            {if isset($input.class)} class="{$input.class}"
                                                                            {else} class="color mColorPickerInput"{/if}
                                                                           name="{$input.name}"
                                                                           value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {elseif $input.type == 'date'}
                                                    <div class="row">
                                                        <div class="input-group col-lg-4">
                                                            <input
                                                                    id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                                    type="text"
                                                                    data-hex="true"
                                                                    {if isset($input.class)} class="{$input.class}"
                                                                    {else}class="datepicker"{/if}
                                                                    name="{$input.name}"
                                                                    value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar-empty"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {elseif $input.type == 'datetime'}
                                                    <div class="row">
                                                        <div class="input-group col-lg-4">
                                                            <input
                                                                    id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                                    type="text"
                                                                    data-hex="true"
                                                                    {if isset($input.class)} class="{$input.class}"
                                                                    {else} class="datetimepicker"{/if}
                                                                    name="{$input.name}"
                                                                    value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar-empty"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {elseif $input.type == 'free'}
                                                    {$fields_value[$input.name]}
                                                {elseif $input.type == 'html'}
                                                    {if isset($input.html_content)}
                                                        {$input.html_content}
                                                    {else}
                                                        {$input.name}
                                                    {/if}
                                                {elseif $input.type == 'hr'}
                                                    <hr />
                                                {elseif $input.type == 'pvz_test'}
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input id="test_pvz_value" type="text" />
                                                </div>
                                                <div class="col-lg-2">
                                                    <select id="test_pvz_field_name">
                                                        <option value="city_code">{l s='City code'}</option>
                                                        <option value="postal_code">{l s='Postal code'}</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" id="test_pvz_start" class="btn btn-default">
                                                     <i class="fa fa-refresh fa-spin" style="display:none;"></i>
                                                    {l s='Start' mod='cdek20'}
                                                    </button>
                                                </div>
                                            </div>
                                                {elseif $input.type == 'button_city_upload'}
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            <button type="button" class="btn btn-default{if isset($input.attributes['class'])} {$input.attributes['class']|escape:'html':'UTF-8'}{/if}"
                                                            {foreach from=$input.attributes key=name item=value}
                                                                {if $name|lower != 'class'}
                                                                    {$name|escape:'html':'UTF-8'}="{$value|escape:'html':'UTF-8'}"
                                                                {/if}
                                                            {/foreach} ><i class="fa fa-refresh fa-spin" style="display:none;"></i>
                                                            {$input.text|escape:'html':'UTF-8'}
                                                            </button>
                                                            <div id="upload_progress_block"><span id="upload_progress"></span></div>
                                                        </div>
                                                    </div>
                                                {elseif $input.type == 'tariffs'}
                                                    <div class="alert alert-info">{l s='Use tariffs only for your contract' mod='cdek20'}.</div>
                                                {foreach from=$input.tariff_all item='tariffs' key='type'}
                                                    <section class="tariff-panel">
                                                        <header class="tariff-panel-header">
                                                            <span class="tariff-mode">{if $type == 'courier'}{l s='Courier' mod='cdek20'}{elseif $type == 'pickup'}{l s='Pickup' mod='cdek20'}{else}{l s='Postamat' mod='cdek20'}{/if}</span>
                                                            <label class="badge badge-primary badge-pill float-right">
                                                                {$tariffs|count}
                                                            </label>
                                                            <div class="mode_description"></div>
                                                        </header>

                                                        <section class="tariff-list">
                                                            <ul class="list-unstyled sortable">
                                                                {foreach from=$tariffs item='tariff'}
                                                                    <li id="{$tariff->id|intval}" class="tariff-position-31 tariff-item draggable">
                                                                        <div class="btn-toolbar text-center tariff-column-position dragHandle" id="td_tariff_135">
                                                                            <div class="btn-group_tariff">
                                                                                <span class="index-position">{$tariff->position|intval}</span>
                                                                            </div>

                                                                            <div class="btn-group-vertical tariff-buttons-update">
                                                                                <button class="btn btn-outline-primary btn-sm" data-hook-id="49" data-tariff-id="{$tariff->id|intval}" data-way="0">
                                                                                    <i class="material-icons">expand_less</i>
                                                                                </button>

                                                                                <button class="btn btn-outline-primary btn-sm" data-hook-id="49" data-tariff-id="{$tariff->id|intval}" data-way="1">
                                                                                    <i class="material-icons">expand_more</i>
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                        <div class="tariff-column-actions">
                                                                            <div class="btn-group">
                                                                                {if $tariff->active}
                                                                                    <a class="btn btn-success btn-sm" href="#" data-id="{$tariff->id|intval}">
                                                                                        <i class="material-icons">check</i>
                                                                                    </a>
                                                                                {else}
                                                                                    <a class="btn btn-danger btn-sm" href="#" data-id="{$tariff->id|intval}">
                                                                                        <i class="material-icons">close</i>
                                                                                    </a>
                                                                                {/if}
                                                                            </div>
                                                                        </div>

                                                                        <div class="tariff-column-infos">
                                                                            <span>{$tariff->tariff|escape:'html':'UTF-8'}</span>
                                                                            <span class="tariff-name">
                                                                                {if $input.cdek_lang == 'rus'}{$tariff->name_rus|escape:'html':'UTF-8'}{else}{$tariff->name_eng|escape:'html':'UTF-8'}{/if}
                                                                                <small class="text-muted">&nbsp;-&nbsp;{$tariff->getTextMode($input.cdek_lang)|escape:'html':'UTF-8'}</small>
                                                                            </span>
                                                                            <div class="tariff-description">{if $input.cdek_lang == 'rus'}От {$tariff->range_min|escape:'html':'UTF-8'}кг. до {$tariff->range_max|escape:'html':'UTF-8'}кг.{else}From {$tariff->range_min|escape:'html':'UTF-8'}kg. to {$tariff->range_max|escape:'html':'UTF-8'}kg.{/if}</div>
                                                                        </div>


                                                                    </li>
                                                                {/foreach}
                                                            </ul>
                                                        </section>
                                                    </section>
                                                {/foreach}
                                                {elseif $input.type == 'carriers'}

                                                {foreach from=$input.carriers item='carrier' key='type'}
                                                    <div >
                                                        <section class="tariff-panel">

                                                            <div class="form-wrapper">
                                                                <div class="row">

                                                                    <div class="col-lg-1">
                                                                        <header class="tariff-panel-header">
                                                                            <span class="tariff-mode">{if $type == 'courier'}{l s='Courier' mod='cdek20'}{elseif $type == 'pickup'}{l s='Pickup' mod='cdek20'}{else}{l s='Postamat' mod='cdek20'}{/if}</span>
                                                                            <div class="mode_description"></div>
                                                                        </header>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label col-lg-7">
                                                                                {if isset($input.hint)}
                                                                                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="">
                                                                                    {/if}
                                                                                    {l s='Free shipping' mod='cdek20'}
                                                                                    {if isset($input.hint)}
                                                                                </span>
                                                                                {/if}
                                                                            </label>
                                                                            <div class="col-lg-5">
                                                                                <span class="switch prestashop-switch">
                                                                                    {foreach [1,0] as $value}
                                                                                        <input type="radio" name="{'free_shipping_'|cat:$type}"{if $value == 1} id="{'free_shipping_'|cat:$type}_on"{else} id="{'free_shipping_'|cat:$type}_off"{/if} value="{$value|escape:'html':'UTF-8'}"{if $fields_value['free_shipping_'|cat:$type] == $value} checked="checked"{/if}{if (isset($input.disabled) && $input.disabled) or (isset($value.disabled) && $value.disabled)} disabled="disabled"{/if}/>
                                                                                        {strip}
                                                                                            <label {if $value == 1} for="{'free_shipping_'|cat:$type}_on"{else} for="{'free_shipping_'|cat:$type}_off"{/if}>
                                                                                                {if $value == 1}
                                                                                                    {l s='Enabled' d='Admin.Global'}
                                                                                                {else}
                                                                                                    {l s='Disabled' d='Admin.Global'}
                                                                                                {/if}
                                                                                            </label>
                                                                                        {/strip}
                                                                                    {/foreach}
                                                                                    <a class="slide-button btn"></a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                            class="col-lg-7 carriers-form-more"
                                                                            {foreach [1] as $value}{if $fields_value['free_shipping_'|cat:$type] != $value} style="display: none"{/if}{/foreach}>

                                                                        <label class="control-label float-left margin-right">
                                                                            {l s='From the price' mod='cdek20'} ({Context::getContext()->currency->iso_code})
                                                                        </label>

                                                                        <div class=" float-left margin-right-lg">
                                                                            <input type="text" value="{$cdek_configuration->get('free_price_'|cat:$type) / 100}" name="free_price_{$type}" class="fixed-width-sm"/>
                                                                        </div>

                                                                        <label class="control-label float-left margin-right-lg">
                                                                            {l s='At a weight of' mod='cdek20'}:
                                                                        </label>

                                                                        <div class="float-left margin-right">
                                                                            <div class="slider-container fixed-width-sm" data-weight_unit="{$cdek_configuration->get('weight_unit')}">
                                                                                <div class="slider-range-weight"></div>
                                                                                <div><input type="hidden" value="{$cdek_configuration->get('free_weight_'|cat:$type)}" name="free_weight_{$type}" class="fixed-width-xl"/></div>
                                                                            </div>
                                                                        </div>

                                                                        <label class="control-label float-left margin-right">
                                                                            <span class="label-value"></span> {if $cdek_configuration->get('weight_unit') == 1}{l s='g.' mod='cdek20'}{else}{l s='kg.' mod='cdek20'}{/if}
                                                                        </label>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </section>
                                                    </div>
                                                {/foreach}

                                                {elseif $input.type == 'cdek_categories'}

                                                    <div>
                                                        <div class="row default_weight">

                                                            <label class="col-lg-3">

                                                            </label>

                                                            <label class="control-label col-lg-9" style="text-align: left">
                                                                {$input.label}
                                                            </label>

                                                        </div>
                                                    </div>

                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th ><span class="title_box">{l s='ID' mod='cdek20'}</span></th>
                                                            <th >
                                                                <span class="title_box">
                                                                    {l s='Category name' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th style="text-align: center">
                                                                <span class="title_box">
                                                                    {l s='Default weight' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th style="text-align: center">
                                                                <span class="title_box">
                                                                    {l s='Default width' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th style="text-align: center">
                                                                <span class="title_box">
                                                                    {l s='Default height' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th style="text-align: center">
                                                                <span class="title_box">
                                                                    {l s='Default depth' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        {foreach $input.categories as $category}
                                                            <tr>
                                                                <td>{$category['id_category']|intval}</td>
                                                                <td>
                                                                    <label">{$category['name']|escape:'html':'UTF-8'}</label>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <input
                                                                            type="text"
                                                                            name="default_categories_weight[{$category.id_category|intval}]"
                                                                            class="categoryBox fixed-width-sm"
                                                                            style="display: inline-block"
                                                                            value="{if isset($fields_value[$input.name][$category.id_category])}{$fields_value[$input.name][$category.id_category]['weight']|escape:'html':'UTF-8'}{/if}" />
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <input
                                                                            type="text"
                                                                            name="default_categories_width[{$category.id_category|intval}]"
                                                                            class="categoryBox fixed-width-sm"
                                                                            style="display: inline-block"
                                                                            value="{if isset($fields_value[$input.name][$category.id_category])}{$fields_value[$input.name][$category.id_category]['width']|escape:'html':'UTF-8'}{/if}" />
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <input
                                                                            type="text"
                                                                            name="default_categories_height[{$category.id_category|intval}]"
                                                                            class="categoryBox fixed-width-sm"
                                                                            style="display: inline-block"
                                                                            value="{if isset($fields_value[$input.name][$category.id_category])}{$fields_value[$input.name][$category.id_category]['height']|escape:'html':'UTF-8'}{/if}" />
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <input
                                                                            type="text"
                                                                            name="default_categories_length[{$category.id_category|intval}]"
                                                                            class="categoryBox fixed-width-sm"
                                                                            style="display: inline-block"
                                                                            value="{if isset($fields_value[$input.name][$category.id_category])}{$fields_value[$input.name][$category.id_category]['length']|escape:'html':'UTF-8'}{/if}" />
                                                                </td>
                                                            </tr>
                                                        {/foreach}
                                                        </tbody>
                                                    </table>

                                                {elseif $input.type == 'statuses'}

                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><span class="title_box">{l s='ID' mod='cdek20'}</span></th>
                                                            <th>
                                                                <span class="title_box">
                                                                    {l s='Status name' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th >
                                                                <span class="title_box">
                                                                    {l s='Create an order' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th >
                                                                <span class="title_box">
                                                                    {l s='Delete an order' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th >
                                                                <span class="title_box">
                                                                    {l s='C.O.D shipping' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                            <th >
                                                                <span class="title_box">
                                                                    {l s='C.O.D products' mod='cdek20'}
                                                                </span>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        {foreach $input.statuses as $id_checkbox => $status}
                                                            <tr>
                                                                <td>{$status['id_order_state']|intval}</td>
                                                                <td>
                                                                    <label for="{$id_checkbox|intval}">{$status['name']|escape:'html':'UTF-8'}</label>
                                                                </td>
                                                                <td>
                                                                    <div class="md-checkbox">
                                                                        <label>
                                                                            <input type="checkbox" name="statusesCreateBox[]"
                                                                                   class="statusesCreateBox"
                                                                                   id="{$id_checkbox|cat:'_create'}"
                                                                                   value="{$status['id_order_state']|intval}"
                                                                                   {if in_array($status['id_order_state'], $fields_value['statuses']['create'])}checked="checked"{/if}
                                                                                    {if in_array($status['id_order_state'], $fields_value['statuses']['delete'])}disabled{/if} />
                                                                            <i class="md-checkbox-control"></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="md-checkbox">
                                                                        <label>
                                                                            <input type="checkbox" name="statusesDeleteBox[]"
                                                                                   class="statusesDeleteBox"
                                                                                   id="{$id_checkbox|cat:'_delete'}"
                                                                                   value="{$status['id_order_state']|intval}"
                                                                                   {if in_array($status['id_order_state'], $fields_value['statuses']['delete'])}checked="checked"{/if}
                                                                                    {if in_array($status['id_order_state'], $fields_value['statuses']['create'])}disabled{/if} />
                                                                            <i class="md-checkbox-control"></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="md-checkbox">
                                                                        <label>
                                                                            <input type="checkbox" name="statusesCodShipBox[]"
                                                                                   class="statusesCodShipBox"
                                                                                   id="{$id_checkbox|cat:'_cod_ship'}"
                                                                                   value="{$status['id_order_state']|intval}"
                                                                                   {if in_array($status['id_order_state'], $fields_value['statuses']['cod_ship'])}checked="checked"{/if} />
                                                                            <i class="md-checkbox-control"></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="md-checkbox">
                                                                        <label>
                                                                            <input type="checkbox" name="statusesCodBox[]"
                                                                                   class="statusesCodBox"
                                                                                   id="{$id_checkbox|cat:'_cod'}"
                                                                                   value="{$status['id_order_state']|intval}"
                                                                                   {if in_array($status['id_order_state'], $fields_value['statuses']['cod'])}checked="checked"{/if} />
                                                                            <i class="md-checkbox-control"></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        {/foreach}
                                                        </tbody>
                                                    </table>

                                                {literal}
                                                    <script>
                                                        $('.statusesCreateBox, .statusesDeleteBox').on ('change', function () {
                                                            var selector;
                                                            if ($(this).hasClass('statusesCreateBox')) {
                                                                selector = '.statusesDeleteBox[value=' + $(this).val() + ']';
                                                            } else if ($(this).hasClass('statusesDeleteBox')) {
                                                                selector = '.statusesCreateBox[value=' + $(this).val() + ']';
                                                            }
                                                            var elem = $(selector);
                                                            if ($(this).prop('checked')) {
                                                                elem.attr('disabled', 'disabled');
                                                            } else {
                                                                elem.removeAttr('disabled');
                                                            }
                                                        });
                                                        $('input#departure_time, input#courier_start_time, input#end_time_for_courier').timepicker({
                                                            {/literal}
                                                            currentText: '{l s='Now' mod='cdek20' js=true}',
                                                            closeText: '{l s='Done' mod='cdek20' js=true}',
                                                            timeOnlyTitle: '{l s='Choose Time' mod='cdek20' js=true}',
                                                            timeText: '{l s='Time' mod='cdek20' js=true}',
                                                            hourText: '{l s='Hour' mod='cdek20' js=true}',
                                                            minuteText: '{l s='Minute' mod='cdek20' js=true}'
                                                            {literal}
                                                        });
                                                    </script>
                                                {/literal}
                                                {elseif $input.type == 'log'}
                                                {if $input.name == 'write_log'}

                                                    <div class="form-group clearfix">
                                                        <div class="float-left margin-right-lg">
                                                            <button type="button" class="btn btn-danger clear_log">
                                                                {l s='Clear log' mod='cdek20'}
                                                            </button>
                                                        </div>
                                                        <label class="control-label float-left margin-right">{l s='Method' mod='cdek20'}</label>
                                                        <div class="float-left margin-right-lg">
                                                            <select name="search[method]" class="">
                                                                <option value="">-</option>
                                                                {foreach from=$methods item=method}
                                                                    <option value="{$method.method|escape:'html':'UTF-8'}">{$method.method|escape:'html':'UTF-8'}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>

                                                        <label class="control-label float-left margin-right">{l s='Date range' mod='cdek20'}</label>
                                                        <div class="float-left">
                                                            <input type="text" class="form-control datepicker fixed-width-sm" placeholder="{l s='Begin' mod='cdek20'}" readonly name="search[date_begin]">
                                                        </div>
                                                        <div class="float-left margin-right-lg">
                                                            <input type="text" class="form-control datepicker fixed-width-sm" placeholder="{l s='End' mod='cdek20'}" readonly name="search[date_end]">
                                                        </div>

                                                        <div class="float-left">
                                                            <button type="button" class="btn btn-default applyFilterLogger">
                                                                {l s='Apply' mod='cdek20'}
                                                            </button>
                                                        </div>

                                                        <div class="float-left margin-right-lg">
                                                            <button type="button"  class="btn btn-success clearcache">
                                                                {l s='Clear cache' mod='cdek20'}
                                                            </button>
                                                        </div>

                                                        <label class="control-label float-left margin-right">{l s='Logs' mod='cdek20'}</label>
                                                        <div class="float-left margin-right-md">
                                                            <span class="switch prestashop-switch fixed-width-lg">
                                                                {foreach $input.values as $value}
                                                                    <input type="radio" name="{$input.name|escape:'html':'UTF-8'}"{if $value.value == 1} id="{$input.name|escape:'html':'UTF-8'}_on"{else} id="{$input.name|escape:'html':'UTF-8'}_off"{/if} value="{$value.value|intval}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                    {strip}
                                                                        <label {if $value.value == 1} for="{$input.name|escape:'html':'UTF-8'}_on"{else} for="{$input.name|escape:'html':'UTF-8'}_off"{/if}>
                                                                            {if $value.value == 1}
                                                                                {l s='Enabled' mod='cdek20'}
                                                                            {else}
                                                                                {l s='Disabled' mod='cdek20'}
                                                                            {/if}
                                                                        </label>
                                                                    {/strip}
                                                                {/foreach}
                                                                <a class="slide-button btn"></a>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group clearfix">
                                                        <table class="table table-bordered cdek_logger">
                                                            <thead>
                                                            <tr>
                                                                <td>{l s='ID' mod='cdek20'}</td>
                                                                <td>{l s='Method' mod='cdek20'}</td>
                                                                <td>{l s='Message' mod='cdek20'}</td>
                                                                <td>{l s='Request' mod='cdek20'}</td>
                                                                <td>{l s='Response' mod='cdek20'}</td>
                                                                <td>{l s='Date' mod='cdek20'}</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {if is_array($log_items) && count($log_items)}
                                                                {foreach from=$log_items item=row}
                                                                    {include file="./table_log_row.tpl"}
                                                                {/foreach}
                                                            {/if}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="form-group clearfix">
                                                        <button type="button" class="btn btn-success show_more">
                                                            {l s='Show more' mod='cdek20'}
                                                        </button>
                                                    </div>
                                                    <script>
                                                        var pages = [];
                                                        var total_pages = {$pages|intval};
                                                        $(document).ready(function () {
                                                            for (var i = 2; i <= total_pages; i++) {
                                                                pages.push(i);
                                                            }
                                                            checkPagination();
                                                            var clipboard = new Clipboard('[data-copy-field]', {
                                                                text: function (trigger) {
                                                                    alert('Copied!');
                                                                    return $(trigger).parent().find('code').text().trim();
                                                                }
                                                            });
                                                            $('.clear_log').live('click', function() {
                                                                $.alerts.okButton = '{l s='Yes' mod='cdek20'}';
                                                                $.alerts.cancelButton = '{l s='No' mod='cdek20'}';
                                                                jConfirm('{l s='Clear log' mod='cdek20'}?', '{l s='Clear log' mod='cdek20'}', function(confirm){
                                                                    if (confirm === true) {
                                                                        $.ajax({
                                                                            url: document.location.href.replace('#' + document.location.hash, ''),
                                                                            type: 'POST',
                                                                            data: {
                                                                                ajax: true,
                                                                                action: 'clear_cdek_log'
                                                                            },
                                                                            success: function() {
                                                                                var table = $('.cdek_logger tbody');
                                                                                table.html('');
                                                                                pages = [];
                                                                                checkPagination();
                                                                            }
                                                                        });
                                                                    }
                                                                    else {
                                                                        // document.location = cancel_link;
                                                                    }
                                                                });
                                                            });
                                                            {literal}
                                                            $('.applyFilterLogger').live('click', function() {
                                                                var data = {};
                                                                $('[name^="search["]').each(function() {
                                                                    data[$(this).attr('name')] = $(this).val();
                                                                });
                                                                paginationSet(1, data);
                                                            });
                                                            $('.clearcache').live('click', function() {
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    data: {
                                                                        ajax: true,
                                                                        action: 'clear_calculator_cache'
                                                                    }
                                                                });
                                                            });
                                                            $('.show_more').live('click', function () {
                                                                var page = pages.shift();
                                                                var method = $('[name="search[method]"]').val();
                                                                var date_begin = $('[name="search[date_begin]"]').val();
                                                                var date_end = $('[name="search[date_end]"]').val();
                                                                var search = {};
                                                                if (method) {
                                                                    search.method = method;
                                                                }
                                                                if (date_begin) {
                                                                    search.date_begin = date_begin + ' 00:00:00';
                                                                }
                                                                if (date_end) {
                                                                    search.date_end = date_end + '23:59:59';
                                                                }
                                                                paginationSet(page, {search: search});
                                                            });
                                                            {/literal}
                                                            function checkPagination() {
                                                                if (!pages.length) {
                                                                    $('.show_more').hide();
                                                                }
                                                            }
                                                            function highlightJSON() {
                                                                $('code').each(function(i, block) {
                                                                    $(this).data('code', $(this).html());
                                                                    hljs.highlightBlock(block);
                                                                });
                                                            }
                                                            var pagination_ajax = null;
                                                            function paginationSet(page, filter) {
                                                                var table = $('.cdek_logger tbody');
                                                                var data = {
                                                                    ajax: true,
                                                                    action: 'get_cdek_log',
                                                                    page: page
                                                                };
                                                                if (typeof filter != 'undefined') {
                                                                    $.extend(data, filter);
                                                                }
                                                                if (pagination_ajax != null) {
                                                                    pagination_ajax.abort();
                                                                    pagination_ajax = null;
                                                                }
                                                                pagination_ajax = $.ajax({
                                                                    url: document.location.href.replace('#' + document.location.hash, ''),
                                                                    type: 'POST',
                                                                    dataType: 'json',
                                                                    data: data,
                                                                    success: function (json) {
                                                                        if (page == 1) {
                                                                            pages = [];
                                                                            for (var i = 2; i <= json.pages; i++) {
                                                                                pages.push(i);
                                                                            }
                                                                        }
                                                                        checkPagination();
                                                                        if (page == 1) {
                                                                            table.html(json.html);
                                                                        } else {
                                                                            table.append(json.html);
                                                                        }
                                                                        highlightJSON();
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    </script>
                                                {/if}
                                                {else}
                                                    {$smarty.block.parent}
                                                {/if}
                                            {/block}{* end block input *}
                                            {block name="description"}
                                                {if isset($input.desc) && !empty($input.desc)}
                                                    <p class="help-block float-left">
                                                        {if is_array($input.desc)}
                                                            {foreach $input.desc as $p}
                                                                {if is_array($p)}
                                                                    <span id="{$p.id}">{$p.text}</span><br />
                                                                {else}
                                                                    {$p}<br />
                                                                {/if}
                                                            {/foreach}
                                                        {else}
                                                            {$input.desc}
                                                        {/if}
                                                    </p>
                                                {/if}
                                            {/block}
                                        </div>
                                    {/block}{* end block field *}
                                {/if}
                            </div>
                            {/if}
                        {/block}
                    {/foreach}
                    {hook h='displayAdminForm' fieldset=$f}
                    {if isset($name_controller)}
                        {capture name=hookName assign=hookName}display{$name_controller|ucfirst}Form{/capture}
                        {hook h=$hookName fieldset=$f}
                    {elseif isset($smarty.get.controller)}
                        {capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|htmlentities}Form{/capture}
                        {hook h=$hookName fieldset=$f}
                    {/if}
                </div><!-- /.form-wrapper -->
            {elseif $key == 'desc'}
                <div class="alert alert-info col-lg-offset-3">
                    {if is_array($field)}
                        {foreach $field as $k => $p}
                            {if is_array($p)}
                                <span{if isset($p.id)} id="{$p.id}"{/if}>{$p.text}</span><br />
                            {else}
                                {$p}
                                {if isset($field[$k+1])}<br />{/if}
                            {/if}
                        {/foreach}
                    {else}
                        {$field}
                    {/if}
                </div>
            {/if}
            {block name="other_input"}{/block}
        {/foreach}
        {block name="footer"}
            {capture name='form_submit_btn'}{counter name='form_submit_btn'}{/capture}
            {if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
                <div class="panel-footer">
                    {if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-default pull-right{/if}">
                            <i class="{if isset($fieldset['form']['submit']['icon'])}{$fieldset['form']['submit']['icon']}{else}process-icon-save{/if}"></i> {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='cdek20'}
                        </a>
                    {/if}
                    {if isset($fieldset['form']['reset'])}
                        <button
                                type="reset"
                                id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']}{else}{$table}_form_reset_btn{/if}"
                                class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']}{else}btn btn-default{/if}"
                        >
                            {if isset($fieldset['form']['reset']['icon'])}<i class="{$fieldset['form']['reset']['icon']}"></i> {/if} {$fieldset['form']['reset']['title']}
                        </button>
                    {/if}
                    {if isset($fieldset['form']['buttons'])}
                        {foreach from=$fieldset['form']['buttons'] item=btn key=k}
                            {if isset($btn.href) && trim($btn.href) != ''}
                                <a href="{$btn.href}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</a>
                            {else}
                                <button type="{if isset($btn['type'])}{$btn['type']}{else}button{/if}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" name="{if isset($btn['name'])}{$btn['name']}{else}submitOptions{$table}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</button>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
            {/if}
        {/block}
    </div>
{/block}