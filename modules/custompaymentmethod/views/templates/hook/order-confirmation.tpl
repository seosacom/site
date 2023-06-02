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

<div class="content">

    <div class="form-group">
        <div class="custompaymentmethod-option">
            <div class="row">
                <div class="col-lg-4 form-group">
                    <label><b>{l s='You select payment method' mod='custompaymentmethod'}:</b></label>
                    <div>{$name|cleanHtml nofilter}</div>
                    <div class="line-45"></div>
                </div>
                <div class="col-lg-4 form-group">
                    <label><b>{l s='Total order' mod='custompaymentmethod'}:</b></label>
                    <div class="price">{$total_paid|escape:'quotes':'UTF-8'}</div>
                    <div class="line-45"></div>
                </div>
                <div class="col-lg-4 form-group">
                    {if $logo}
                        <img src="{$this_path|escape:'quotes':'UTF-8'}logos/{$logo|escape:'quotes':'UTF-8'}" />
                    {/if}
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="custompaymentmethod-option">
            <label>
                <b>{l s='Requisites:' mod='custompaymentmethod'}</b>
            </label>

            {$details|cleanHtml nofilter}

        </div>
    </div>

    <div class="form-group">
        <div class="custompaymentmethod-option">
            <label>
                <b>{l s='Description:' mod='custompaymentmethod'}</b>
            </label>

            {$description|cleanHtml nofilter}

        </div>
    </div>

    {if $ps_message_field}
        <div class="form-group">
            <div class="custompaymentmethod-option">
                <label>
                    <b>{l s='Message:' mod='custompaymentmethod'}</b>
                </label>
                <p>
                    {$ps_message_field|escape:'html':'UTF-8'}
                </p>
            </div>
        </div>
    {/if}

    <div id="order-items" class="order-items_template">
        <table>
            <tbody>
            {if $commission != 0 || $discount != 0}
                {if $commission != 0}
                    <tr>
                        <td>{l s='Сommission for this payment method' mod='custompaymentmethod'}:</td>
                        <td>{$format_commission|escape:'quotes':'UTF-8'}</td>
                    </tr>
                {/if}
                {if $discount != 0}
                    <tr>
                        <td>{l s='Discount for this payment method' mod='custompaymentmethod'}:</td>
                        <td>{$format_discount|escape:'quotes':'UTF-8'}</td>
                    </tr>
                {/if}
            {/if}
            </tbody>
        </table>
    </div>

</div>