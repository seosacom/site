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

{capture name=path}{l s='Checkout order' mod='custompaymentmethod'}{/capture}
{if $ps_version_cpm < 1.6}
    {include file="$tpl_dir./breadcrumb.tpl"}
{/if}

<section class="custompaymentmethod-step checkout-step">

    <h1 class="step-title h3">
        <span class="step-number">5</span>
        {l s='Checkout order' mod='custompaymentmethod'}
    </h1>

    {if $ps_version_cpm <= 1.6}
        {assign var='current_step' value='payment'}
        {include file="$tpl_dir./order-steps.tpl"}
    {/if}

    <div class="content">

        <form id="custompaymentmethod_form" class="custompayment-form"
              action="{$link->getModuleLink('custompaymentmethod', 'validation', ['type' => $type], true)|escape:'quotes':'UTF-8'}"
              method="post" name="order_message_seosa">
            <input type="hidden" name="confirm" value="1"/>

            <div class="form-group">
                <div class="custompaymentmethod-option"></div>
            </div>

            <div class="form-group">
                <div class="custompaymentmethod-option">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <label><b>{l s='You select payment method' mod='custompaymentmethod'}:</b></label>
                            <div>{$custom_payment->name|escape:'html':'UTF-8'}</div>
                            <div class="line-45"></div>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label><b>{l s='Total order' mod='custompaymentmethod'}:</b></label>
                            <div id="amount_{$id_currency|intval}" class="price">{displayPrice price=$total currency=$id_currency}</div>
                            <div class="line-45"></div>
                        </div>

                        <div class="col-lg-4 form-group">
                            {if $custom_payment->logo}
                                <img src="{$this_path|escape:'quotes':'UTF-8'}logos/{$custom_payment->logo|escape:'quotes':'UTF-8'}"
                                     alt="{$custom_payment->name|escape:'html':'UTF-8'}" />
                            {/if}
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custompaymentmethod-option">
                    <label>
                        <b>{l s='Details:' mod='custompaymentmethod'}</b>
                    </label>

                    {if $ps_version_cpm < 1.7}
                        {$custom_payment->details|escape:'quotes':'UTF-8' nofilter}
                    {else}
                        {$custom_payment->details|escape:html nofilter}
                    {/if}

                </div>
            </div>

            <div class="form-group">
                <div class="custompaymentmethod-option">
                    <label>
                        <b>{l s='Description:' mod='custompaymentmethod'}</b>
                    </label>

                    {if $ps_version_cpm < 1.7}
                        {$custom_payment->description|escape:'quotes':'UTF-8' nofilter}
                    {else}
                        {$custom_payment->description|escape:html nofilter}
                    {/if}

                </div>
            </div>

            {if $custom_payment->view_message_field}
                <div class="form-group">
                    <div class="custompaymentmethod-option">

                        <label>
                            <b>
                                {$name_message_field|escape:'html':'UTF-8'}:
                                {if $required_message_field}
                                    <span>({l s='required' mod='custompaymentmethod'})</span>
                                {/if}
                            </b>
                        </label>
                        <textarea class="d-block" name="message" id="oms"></textarea>

                    </div>
                </div>
            {/if}

            <p>
                {l s='Please, submit order, press button "Submit order".' mod='custompaymentmethod'}
            </p>
            <div class="cart_navigation clearfix form-group">

                <a href="{$link->getPageLink('order', true)|escape:'quotes':'UTF-8'}?step=3" class="label margin-right button-exclusive">
                    <i class="icon-chevron-left"></i>
                    {l s='Other payment methods' mod='custompaymentmethod'}
                </a>

                {if $ps_version_cpm < 1.7}
                    <button type="submit" name="submit" class="button btn btn-default standard-checkout button-medium" style="">
                        <span>
                            {l s='Submit order' mod='custompaymentmethod'}
                            <i class="icon-chevron-right right"></i>
                        </span>
                    </button>
                {else}
                    <button type="submit" name="submit" class="btn btn-primary float-right">
                        {l s='Submit order' mod='custompaymentmethod'}
                    </button>
                {/if}

            </div>
        </form>

    </div>


</section>

{if $required_message_field}
    <script>
        {if $error_message_field}
        var error_message_field = "{$error_message_field|escape:'quotes':'UTF-8'}";
        {else}
        var error_message_field = "{l s='Fill out the required field.' mod='custompaymentmethod'}";
        {/if}
        {if version_compare($ps_version_cpm, '1.7.0.0', '<')}
        $(document).ready(function () {
            $(document).on('submit', 'form[name="order_message_seosa"]', function () {
                return acceptOMS();
            });
        });

        function acceptOMS() {
            if (typeof error_message_field != 'undefined' && $('#oms').length && $('textarea#oms').val() == '') {
                if (!!$.prototype.fancybox)
                    $.fancybox.open([
                            {
                                type: 'inline',
                                autoScale: true,
                                minHeight: 30,
                                content: '<p class="fancybox-error">' + error_message_field + '</p>'
                            }],
                        {
                            padding: 0
                        });
                else
                    alert(error_message_field);
            }
            else
                return true;
            return false;
        }
        {/if}
    </script>
{/if}