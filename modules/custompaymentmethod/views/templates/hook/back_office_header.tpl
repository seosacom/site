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
{if isset($script_url)}
    <script type="text/javascript" src="{$script_url|escape:'html':'UTF-8'}"></script>
{/if}
{literal}
<script>
    if (typeof $.fn.live == 'undefined')
        $.fn.live = $.fn.on;

    var ajaxGetCommission = null;
    $('#payment_module_name').live('change', function () {
        if (ajaxGetCommission != null)
            ajaxGetCommission.abort();
        var ajaxGetCommission = $.ajax({
            url: document.location.href.replace(/#.*$/, ''),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'changePaymentMethod',
                id_customer: id_customer,
                ajax: 1,
                module_name: $(this).val(),
                id_cart: id_cart
            },
            success: function (r) {
                $('.order_commission').remove();
                $('.order_discount').remove();

                if (r.commission)
                    $('#cart_summary').after('<div class="panel order_commission">' + r.commission + '</div>');

                if (r.discount)
                    $('#cart_summary').after('<div class="panel order_discount">' + r.discount + '</div>');

                $('#total_without_taxes').text(r.total_without_tax);
                $('#total_with_taxes').text(r.total);

                $('#payment_module_name').html(r.view);
            }
        });
    });
    $('#customer_part').on('click', 'button.setup-customer', function (e) {
        e.preventDefault();
        $('#payment_module_name').trigger('change');
    });
</script>
<style>
    .order_comission {
        font-size: 19px;
    }

    fieldset .order_comission {
        float: left;
        margin-left: 119px;
        color: #FF0000;
        margin-bottom: 20px;
    }
</style>
{/literal}