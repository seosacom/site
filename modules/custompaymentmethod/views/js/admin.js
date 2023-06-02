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

$(function () {
    $('[name="commission_tax"], [name="discount_tax"]').ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        step: 0.01,
        postfix: "%"
    });

    $('[name="commission_tax"], [name="discount_tax"]').each(function () {
        var field = $('<input type="text" class="fixed-width-sm margin-right-lg float-left">');
        field.attr('name', $(this).attr('name') + '_field');
        $(this).after(field);
        $('[name="' + $(this).attr('name') + '_field' + '"]').live('keyup', function () {
            var value = parseFloat($(this).val());
            value = (isNaN(value) ? 0 : value);
            var name = $(this).attr('name').replace('_field', '');
            var slider = $('[name="' + name + '"]').data("ionRangeSlider");
            slider.update({
                from: value
            })
        });
    });

    $('[name="commission_tax"], [name="discount_tax"]').live('change', function () {
        var value = $(this).val();
        var name = $(this).attr('name');
        $('[name="' + name + '_field"]').val(value);
    }).trigger('change');

    $('[name="confirmation_page"]').live('change', function () {
        if($('#confirmation_page_on').prop('checked')){

        } else {
            $("#view_message_field_off").prop("checked", true);
            $("#required_message_field_off").prop("checked", true);
        }
    });

    $('[name="required_message_field"]').live('change', function () {
        if($('#required_message_field_on').prop('checked')){
            $("#confirmation_page_on").prop("checked", true);
            $("#view_message_field_on").prop("checked", true);
        } else {

        }
    });

    $('[name="view_message_field"]').live('change', function () {
        if($('#view_message_field_on').prop('checked')){
            $("#confirmation_page_on").prop("checked", true);
        } else {
            $("#required_message_field_off").prop("checked", true);
        }
    });

});