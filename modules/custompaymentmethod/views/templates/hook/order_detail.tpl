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

    <table class="table table-bordered order-products_template">
        <tfoot>

        {if $commission != 0}
            <tr class="item">
                <td colspan="1">
                    <strong>{l s='Сommission for this payment method' mod='custompaymentmethod'}</strong>
                </td>
                <td colspan="4">
                    <span class="price">{$format_commission|escape:'quotes':'UTF-8'}</span>
                </td>
            </tr>
        {/if}

        {if $discount != 0}
            <tr class="item">
                <td colspan="1">
                    <strong>{l s='Discount for this payment method' mod='custompaymentmethod'}</strong>
                </td>
                <td colspan="4">
                    <span class="price-shipping">{$format_discount|escape:'quotes':'UTF-8'}</span>
                </td>
            </tr>
        {/if}

        </tfoot>
    </table>

   <script>
       $("#order-detail-content tfoot tr").last().before(
           $(".order-products_template tr")
       );
   </script>
{/if}

{if $result}
    <section class="description_custompayment box">
        <h3>{l s='Details payment method :' mod='custompaymentmethod'}</h3>
        <table class="table table-striped table-bordered table-labeled hidden-xs-down">
            <col style="width:20%">
            <col style="width:80%">
            <thead class="thead-default">
            </thead>
            <tbody>
            {if $result['details']}
                <tr class="text-xs-left">
                    <td colspan="1">{l s='Details:' mod='custompaymentmethod'}</td>
                    <td>{$result['details']|cleanHtml nofilter}</td>
                </tr>
            {/if}

            {if $result['description']}
                <tr class="text-xs-left">
                    <td colspan="1">{l s='Description:' mod='custompaymentmethod'}</td>
                    <td>{$result['description']|cleanHtml nofilter}</td>
                </tr>
            {/if}
            {if $result['description_short']}
                <tr class="text-xs-left">
                    <td colspan="1">{l s='Description short:' mod='custompaymentmethod'}</td>
                    <td>{$result['description_short']|cleanHtml nofilter}</td>
                </tr>
            {/if}

            </tbody>
        </table>
    </section>
{/if}