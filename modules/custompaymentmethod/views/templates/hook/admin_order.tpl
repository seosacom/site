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
{if $commission != 0 || $discount != 0}
    <div class="panel commission"{if $commission != 0} data-val="{$format_commission|escape:'quotes':'UTF-8'}"{elseif $discount != 0} data-val="{$format_discount|escape:'quotes':'UTF-8'}"{/if}>
        {if $commission != 0}
            {l s='Commission' mod='custompaymentmethod'}
            {$format_commission|escape:'quotes':'UTF-8'}
            <br>
        {/if}
        {if $discount != 0}
            {l s='Discount' mod='custompaymentmethod'}
            {$format_discount|escape:'quotes':'UTF-8'}
        {/if}
    </div>
{/if}
<style>
    .commission
    {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
        border: solid 1px #e6e6e6;
        background-color: #fff;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: rgba(0,0,0,0.1) 0 2px 0,#fff 0 0 0 3px inset;
        box-shadow: rgba(0,0,0,0.1) 0 2px 0,#fff 0 0 0 3px inset;
        font-size: 20px;
        font-weight: bolder;
        color: #FF0000;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var commissionJs = $('.commission').attr('data-val');
        if(typeof commissionJs !== 'undefined') {
            $('#total_order').before('' +
                '<tr id="total_commission">\n' +
                '<td class="text-right"><span>{l s='Commission' mod='custompaymentmethod'}</span></td>\n' +
                '<td class="amount text-right nowrap">\n' +
                '<span id="val_commission"></span>\n' +
                '</td>\n' +
                '<td class="partial_refund_fields current-edit" style="display:none;"></td>\n' +
                '</tr>');

            $('#val_commission').text(commissionJs);
        }
    })
</script>
