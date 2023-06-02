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
$(document).ready(function () {
    $( "#city_search" ).autocomplete(
        cdek_city_search_url,
        {
            minChars: 3,
            max: 10,
            selectFirst: false,
            scroll: false,
            dataType: "json",
            formatItem: function(data, i, max, value, term) {
                return value;
            },
            parse: function(data) {
                // var mytab = [];
                // for (var i = 0; i < data.length; i++)
                //     mytab[mytab.length] = { data: data[i], value: data[i].cname + ' > ' + data[i].pname };
                return data;
            },
            extraParams: {
                ajax: 1
            }
        }
    ).result(function(e, data, formatted) {
        var input = $(e.target);
        var form = input.closest('form');
        form.find('#city_code').val(data);
        setTimeout(function () {
            input.val(formatted);
        }, 10);
        form.attr('name', '');
        form.attr('action', form.attr('action') + '?step=2&cdek_city_code=' + form.find('[name="cdek_city_code"]').val());
        form.find('input').remove();
        form.submit();
    });
});