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

{block name="fieldset"}
    {capture name='fieldset_name'}{counter name='fieldset_name'}{/capture}

    {foreach $fieldset.form as $key => $field}
        {if $key == 'legend'}
            {block name="legend"}
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

            <div class="">

                <div class="form-group">

                    <div class="col-lg-12">

                        {foreach $field as $input}
                            {block name="label"}
                                {if $input.name == 'status'}
                                    {if isset($input.label)}
                                        <h2 class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="">
                                            {$input.label}
                                        </h2>
                                    {/if}
                                {/if}
                            {/block}
                        {/foreach}

                        {foreach $field as $input}
                            {block name="input"}
                                {if $input.type == 'radio' && $input.name == 'status'}
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
                                {elseif $input.type == 'switch' && $input.name == 'status'}
                                    <div class="float-left margin-top">
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

            </div>

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

    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        1. {l s='Description' mod='custompaymentmethod'}
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

                {include file='../form/blocks/block1.tpl'}

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
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='custompaymentmethod'}
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

    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        2. {l s='Cart total and Access' mod='custompaymentmethod'}
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

                {include file='../form/blocks/block2.tpl'}

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
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='custompaymentmethod'}
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

    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        3. {l s='Confirmation Page' mod='custompaymentmethod'}
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

                {include file='../form/blocks/block3.tpl'}

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
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='custompaymentmethod'}
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

    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        4. {l s='Options' mod='custompaymentmethod'}
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

                {include file='../form/blocks/block4.tpl'}

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
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='custompaymentmethod'}
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

    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        5. {l s='Commission' mod='custompaymentmethod'}
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

                {include file='../form/blocks/block5.tpl'}

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
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='custompaymentmethod'}
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

    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        6. {l s='Discount' mod='custompaymentmethod'}
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

                {include file='../form/blocks/block6.tpl'}

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
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='custompaymentmethod'}
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
