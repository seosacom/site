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
                {if $input.name == 'name'}
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
                {if $input.name == 'name'}
                    {if isset($input.lang) AND $input.lang}
                    {if $languages|count > 1}
                        <div class="float-left fixed-width-500">
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

    <div class="row">

        {foreach $field as $input}
            {block name="label"}
                {if $input.type == 'file'}
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
                {if $input.type == 'file'}
                    <div class="float-left fixed-width-600">
                        {$input.file}
                    </div>
                {/if}
            {/block}
        {/foreach}

    </div>

    <div class="form-group">

        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'details'}
                    {if isset($input.label)}
                        <label class="control-label margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}

        {foreach $field as $input}
            {block name="input"}
                {if $input.name == 'details'}
                    {if isset($input.maxchar) && $input.maxchar}<div class="input-group">{/if}
                    {assign var=use_textarea_autosize value=true}
                    {if isset($input.lang) AND $input.lang}
                    {foreach $languages as $language}
                    {if $languages|count > 1}
                        <div class="row translatable-field lang-{$language.id_lang}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
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
                {/if}
            {/block}
        {/foreach}

    </div>

    <div class="form-group">

        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'description_short'}
                    {if isset($input.label)}
                        <label class="control-label margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}

        {foreach $field as $input}
            {block name="input"}
                {if $input.name == 'description_short'}
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
{*                                <input type="text"*}
{*                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"*}
{*                                       name="{$input.name}_{$language.id_lang}"*}
{*                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"*}
{*                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"*}
{*                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"*}
{*                                        {if isset($input.size)} size="{$input.size}"{/if}*}
{*                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}*}
{*                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}*}
{*                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}*}
{*                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}*}
{*                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}*}
{*                                        {if isset($input.required) && $input.required} required="required" {/if}*}
{*                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />*}
                                <textarea
                                        id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                        name="{$input.name}_{$language.id_lang}"
                                        class="rte autoload_rte rte"
                                        aria-hidden="true"
                                        "
                                >
                                  {if isset($input.string_format) && $input.string_format}
                                      {$value_text|string_format:$input.string_format|escape:'html':'UTF-8' nofilter}
                                  {else}
                                      {$value_text|escape:'html':'UTF-8' nofilter}

                                      {/if}
                                </textarea>
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

    <div class="form-group">

        {foreach $field as $input}
            {block name="label"}
                {if $input.name == 'description'}
                    {if isset($input.label)}
                        <label class="control-label margin-right" data-toggle="tooltip" data-html="true" title="">
                            {$input.label}
                        </label>
                    {/if}
                {/if}
            {/block}
        {/foreach}

        {foreach $field as $input}
            {block name="input"}
                {if $input.name == 'description'}
                    {if isset($input.maxchar) && $input.maxchar}<div class="input-group">{/if}
                    {assign var=use_textarea_autosize value=true}
                    {if isset($input.lang) AND $input.lang}
                    {foreach $languages as $language}
                    {if $languages|count > 1}
                        <div class="row translatable-field lang-{$language.id_lang}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
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
                {/if}
            {/block}
        {/foreach}

    </div>

</div>
