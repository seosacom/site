/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

$(function () {
    highlightJSON();

    $('.slider-range-weight').slider({
        range: true,
        min: 0,
        create: function(event, ui) {
            var container = $(event.target).closest('.slider-container');
            if (container.data('weight_unit') == 1) {
                $(event.target).slider('option', 'max', 5000);
                $(event.target).slider('option', 'step', 50);
            } else {
                $(event.target).slider('option', 'max', 30);
                $(event.target).slider('option', 'step', 0.5);
            }
            var value = container.find('input').val();
            var matches = value.match(/^(\d+)[-]+(\d+)$/);

            if (matches !== null && ('1' in matches) && ('2' in matches)) {
                $(event.target).slider('values', [matches[1]/container.data('weight_unit'), matches[2]/container.data('weight_unit')]);
                container.find('.label-value').html(matches[1] + '-' + matches[2]/container.data('weight_unit'));
            }
        },
        slide: function(event, ui) {
            var container = $(event.target).closest('.slider-container');
            container.find('input').val( (ui.values[0] * container.data('weight_unit')) + '-' + (ui.values[1] * container.data('weight_unit')));
            container.parent().parent().find('.label-value').html(ui.values[0] + '-' + ui.values[1]);
        }
    });

    $( "#free_shipping_courier_on" ).change(function() {
        $(this).closest('.row').find('.carriers-form-more').show();
    });

    $( "#free_shipping_courier_off" ).change(function() {
        $(this).closest('.row').find('.carriers-form-more').hide();
    });

    $( "#free_shipping_pickup_on" ).change(function() {
        $(this).closest('.row').find('.carriers-form-more').show();
    });

    $( "#free_shipping_pickup_off" ).change(function() {
        $(this).closest('.row').find('.carriers-form-more').hide();
    });

    $( "#free_shipping_postamat_on" ).change(function() {
        $(this).closest('.row').find('.carriers-form-more').show();
    });

    $( "#free_shipping_postamat_off" ).change(function() {
        $(this).closest('.row').find('.carriers-form-more').hide();
    });

    $( "#one_package_on" ).change(function() {
        $('#all_is_one_package_on').prop('checked', false);
        $('#all_is_one_package_off').prop('checked', true);
    });

    $( "#all_is_one_package_on" ).change(function() {
        $('#one_package_on').prop('checked', false);
        $('#one_package_off').prop('checked', true);
    });

});

function highlightJSON() {
    $('code').each(function(i, block) {
        $(this).data('code', $(this).html());

        hljs.highlightBlock(block);
    });
}