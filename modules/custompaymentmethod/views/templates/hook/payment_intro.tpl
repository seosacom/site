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

<script>
    var payment_method_available = '{l s='available from' mod='custompaymentmethod'}';
    var to = '{l s='to' mod='custompaymentmethod'}';
    var visible_method_available = {$payment->visible_method_available};
</script>

<section>
    {if $commission != 0}
        <br>
        <span class="commission">{l s='Commission total' mod='custompaymentmethod'}
            : <span class="font-weight-bold">{$format_commission|escape:'html':'UTF-8'}</span></span>
    {/if}
    {if $discount != 0}
        <br>
        <span class="commission">{l s='Discount total' mod='custompaymentmethod'}
            : <span class="font-weight-bold">{$format_discount|escape:'html':'UTF-8'}</span></span>
    {/if}
    {if $commission != 0 || $discount != 0}
        <br>
        <span class="commission">{l s='Order total' mod='custompaymentmethod'}
            : <span class="font-weight-bold">{$format_order_total|escape:'html':'UTF-8'}</span></span>
    {/if}
    <br>
    {if $payment->description_short[$lang]}<span>{$payment->description_short[$lang]|escape:'html':'UTF-8' nofilter}</span>{/if}
</section>
<div payment-method="{$name}" data-total-clear="{$total_clear}" data-cart-total-from="{$cart_total_from}" data-cart-total-to="{$cart_total_to}" data-cart-total-from-display="{$cart_total_from_display}" data-cart-total-to-display="{$cart_total_to_display}" data-total="{$total}" data-total_wt="{$total_wt}" data-tax="{$commission_tax}" id="cart-subtotal-commission" class="card-block" style="display:none;">
    <div class="cart-summary-line cart-summary-subtotals">
        <span class="label">{if $commission != 0}{l s='Commission total' mod='custompaymentmethod'}{elseif $discount != 0}{l s='Discount total' mod='custompaymentmethod'}{/if}</span>
        <span class="value">{if $commission != 0}{$format_commission|escape:'html':'UTF-8'}{elseif $discount != 0}{$format_discount|escape:'html':'UTF-8'}{/if}</span>
    </div>
</div>