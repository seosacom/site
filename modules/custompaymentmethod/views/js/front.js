/**
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
 */

$(document).ready(function () {
    startPosition();

    $('[name=payment-option]').each(function(index) {

        div_parent = $(this).closest('.payment-option').parent();
        div_parent.addClass('payment-option-wrap');
        div_price = div_parent.next('.additional-information').find('#cart-subtotal-commission');
        div_label = div_parent.find('label');
        div_desc = div_parent.next('.additional-information').find('section').find('span');

        total_clear = div_price.data('total-clear');
        cart_total_from = div_price.data('cart-total-from');
        cart_total_to = div_price.data('cart-total-to');
        cart_total_from_display = div_price.data('cart-total-from-display');
        cart_total_to_display = div_price.data('cart-total-to-display');

        var_visible = true;

        if (total_clear == undefined || total_clear == 0) {
            var_visible = false;
        }
        if (cart_total_from == undefined || cart_total_from == 0) {
            var_visible = false;
        }
        if (cart_total_to == undefined || cart_total_to == 0) {
            var_visible = false;
        }

        if (var_visible) {
            if (cart_total_from < total_clear && total_clear < cart_total_to) {
            } else {
                div_parent.addClass('disabled');
                if (visible_method_available) {
                    div_label.append('<span>('+payment_method_available+' '+cart_total_from_display+' '+to+' '+cart_total_to_display+')</span>');
                }
            }
        }

    });


    $(document).on('change', '[name=payment-option]', function () {
        $('#js-checkout-summary').find('#cart-subtotal-commission').remove();
        if(typeof real_total_wt !== "undefined") {
            $('.cart-summary-totals').find('.value').not('.sub').html(real_total_wt);
        }
        if(typeof real_total !== "undefined") {
            $('.cart-summary-totals').find('.cart-total .value').html(real_total);
        }
        $('.cart-summary-totals').find('.value.sub').html(real_tax);
        var commission_line = $('[payment-method="' + $(this).data('moduleName') + '"]');
        if (commission_line.length) {
            var new_line = commission_line.clone();
            new_line.show().insertBefore($('#js-checkout-summary').find('.separator'));
            $('.cart-summary-totals').find('.value').not('.sub').html(new_line.data('total_wt'));
            $('.cart-summary-totals').find('.cart-total .value').html(new_line.data('total'));
            $('.cart-summary-totals').find('.value.sub').html(new_line.data('tax'));
        }

    });

    if (typeof cpm_width != 'undefined' && typeof cpm_height != 'undefined') {

        $('body#checkout section.checkout-step .payment-options .custom-radio').css({
            // 'margin-top': '10px'
        });

        $('body#checkout section.checkout-step .payment-options [data-module-name^=custompaymentmethod]').parent().parent().find('label img').css({
            'maxWidth': cpm_width > 0 ? cpm_width + 'px' : '46px',
            'maxHeight': cpm_height > 0 ? cpm_height + 'px' : '36px'
        });

        $('body#checkout section.checkout-step .payment-options [data-module-name^=custompaymentmethod]').parent().parent().addClass('payment-option-custompaymentmethod');

        $('body#checkout section.checkout-step .payment-options label span').css({
            // 'margin-top': '10px',
            // 'float': 'right'
        });

    }

    $(document).on('submit', 'form[name="order_message_seosa"]', function () {
        return acceptOMS();
    });

    $(".order-confirmation-table tr").last().before(
        $(".order-items_template tr")
    );


    $("#order-products tfoot tr").last().before(
        $(".order-products_template tr")
    );

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

    function startPosition() {
        hideLine();
        $('[name=payment-option]').each(function () {
            if ($(this).prop('checked')) {
                $(this).trigger('change');
            }
        });
    }

    function hideLine() {
        $('#cart-subtotal-commission').hide();
        $('#cart-subtotal-discount-payment').hide();
    }

    function showLine(display_commission, display_discount) {
        hideLine();
        if (display_commission) {
            $('#cart-subtotal-commission').show();
        }
        if (display_discount) {
            $('#cart-subtotal-discount-payment').show();
        }
    }
});
