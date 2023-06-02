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
* @copyright  2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}
{block name="label"}
    {if $input.type != 'payment_methods'}
        {$smarty.block.parent}
    {/if}
{/block}

{block name="input"}
    {$smarty.block.parent}
    {if $input.type == 'payment_methods'}
        {assign var='value_text' value=$fields_value[$input.name]}
        {if count($value_text)}
            {foreach from=$value_text item=item}
                <div class="payment_method">
                    <input type="hidden" name="id_custom_payment_method[]"
                           value="{$item['id_custom_payment_method']|intval}">
                    <div class="row {if $ps_version < 1.6}v15{/if}">
                        <div class="col-md-2 col-sm-2 col-lg-2 col_1 text-center ">
                            <div class="form-group">
                                <h3></h3>
                            </div>
                            {if file_exists($absolute_path|cat:$item['logo'])}
                                <img class="payment_method-img imgm img-thumbnail" src="{$path_img|escape:'quotes':'UTF-8'}{$item['logo']|escape:'quotes':'UTF-8'}">
                            {/if}
                        </div>
                        <div class="col-md-8 col-sm-8 col-lg-8 col_2">
                            <div class="form-group">
                                <h3 style="text-transform: none;">{$item['name']|escape:'html':'UTF-8'}</h3>
                            </div>
                            <div class="custompaymentmethod-option form-group">{$item['description'] nofilter}</div>

                            <div class="row form-group">

                                <div class="col-lg-4">
                                    <label>
                                        <b>{l s='Commission' mod='custompaymentmethod'}</b>:
                                    </label>
                                    <div>
                                        {if $item['type_commission'] == CustomPayment::TYPE_COMMISSION_PERCENT}{$item['commission_percent']|escape:'quotes':'UTF-8'}%
                                        {elseif $item['type_commission'] == CustomPayment::TYPE_COMMISSION_AMOUNT}{displayPrice price=$item['commission_amount'] currency=$item['currency_commission']}
                                        {/if}
                                    </div>
                                    <div class="line-45"></div>
                                </div>

                                <div class="col-lg-4">
                                    <label>
                                        <b>{l s='Discount' mod='custompaymentmethod'}</b>:
                                    </label>
                                    <div>
                                        {if $item['type_discount'] == CustomPayment::TYPE_COMMISSION_PERCENT}{$item['discount_percent']|escape:'quotes':'UTF-8'}%
                                        {elseif $item['type_discount'] == CustomPayment::TYPE_COMMISSION_AMOUNT}{displayPrice price=$item['discount_amount'] currency=$item['currency_discount']}
                                        {/if}
                                    </div>
                                    <div class="line-45"></div>
                                </div>

                                <div class="col-lg-4">
                                    <label>
                                        <b>{l s='Order state' mod='custompaymentmethod'}</b>:
                                    </label>
                                    <div>
                                        {if $item['order_state']}
                                            {$item['order_state']|escape:'quotes':'UTF-8'}
                                        {else}
                                            {l s='No selected' mod='custompaymentmethod'}
                                        {/if}
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 btn_action col_3">
                            {if $item['active']}
                                <a class="btn btn-success" href="{$url_module|escape:'quotes':'UTF-8'}&unset_custom_payment_method={$item['id_custom_payment_method']|escape:'quotes':'UTF-8'}">
                                    <i class="icon-check"></i>
                                </a>
                            {else}
                                <a class="btn btn-danger" href="{$url_module|escape:'quotes':'UTF-8'}&set_custom_payment_method={$item['id_custom_payment_method']|escape:'quotes':'UTF-8'}">
                                    <i class="icon-close"></i>
                                </a>
                            {/if}
                            <a class="btn btn-default"
                               href="{$url_module|escape:'quotes':'UTF-8'}&edit_custom_payment_method={$item['id_custom_payment_method']|escape:'quotes':'UTF-8'}"
                               title="{l s='Edit' mod='custompaymentmethod'}">
                                {if $ps_version < 1.6}
                                    <img src="../img/admin/edit.gif">
                                {else}
                                    <i class="icon-pencil"></i>
                                {/if}
                            </a>
                            {if $all_shops}
                            <a class="btn btn-default"
                               href="{$url_module|escape:'quotes':'UTF-8'}&delete_custom_payment_method={$item['id_custom_payment_method']|escape:'quotes':'UTF-8'}"
                               title="{l s='Delete' mod='custompaymentmethod'}">
                                {if $ps_version < 1.6}
                                    <img src="../img/admin/delete.gif">
                                {else}
                                    <i class="icon-trash"></i>
                                {/if}
                            </a>
                            {/if}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            {/foreach}
        {/if}
    {/if}
{/block}

{block name="after"}
    <script>
        $(function () {
            if ($('.payment_method').length) {
                $('.payment_method').parent().sortable({
                    items: '> .payment_method',
                    stop: function () {
                        $.ajax({
                            url: document.location.href.replace(document.location.hash, ''),
                            type: 'POST',
                            data: 'ajax=true&action=savePaymentMethodPosition&' + $('.payment_method').find(':input').serialize()
                        });
                    }
                });
            }
        });
    </script>
{/block}