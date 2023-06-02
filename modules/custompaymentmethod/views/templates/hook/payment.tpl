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


<div class="row">
    <div class="col-xs-12">
        <p class="payment_module">
            <a class="cpm_link cheque"
               style='{if $ps_version < 1.6}padding-left: 100px;{/if} {if $payment->logo}background: url("{$logos_path|escape:'quotes':'UTF-8'}{$payment->logo|escape:'quotes':'UTF-8'}") 86px 49px no-repeat #fbfbfb; background-position: 7px center;{/if}'
               href="{$link->getModuleLink('custompaymentmethod', 'validation', ['type'=>$payment->id], true)|escape:'quotes':'UTF-8'}"
               title="{$payment->name|escape:'quotes':'UTF-8'}">
                {$payment->name|escape:'quotes':'UTF-8'}
                {if $commission != 0}
                    <span class="commission">
						{if $commission_switch == 0}
                            {l s='Commission total' mod='custompaymentmethod'}: {roundPrice price=$commission}
                        {else}
                            {l s='Commission' mod='custompaymentmethod'}: {$commision_percent|escape:'html':'UTF-8'}%
                        {/if}
					</span>
                {/if}
                {if $discount != 0}
                    <span class="commission">{l s='Discount total' mod='custompaymentmethod'}
                        : {roundPrice price=$discount}</span>
                {/if}
                {if $commission != 0 || $discount != 0}
                    <span class="commission">{l s='Order total' mod='custompaymentmethod'}
                        : {roundPrice price=$order_total}</span>
                {/if}
                {if $payment->description_short}<span>
                  ({$payment->description_short|escape:'quotes':'UTF-8'|strip_tags}
                    )</span>{/if}
            </a>
        </p>
    </div>
</div>
