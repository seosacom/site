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

if (typeof ddd == 'undefined') {
    ddd = console.log;
}

function Cdek() {
    this.option_selector;
    this.row_selector;
    this.dictionary = {};
}

Cdek.prototype._loadTrans = function () {
    var self = this;
    $.ajax({
        url: cdek_settings.trans_url,
        dataType: 'json',
        success: function (r) {
            self.dictionary = r;
        }
    });
}

Cdek.prototype.trans = function (string) {
    if (typeof this.dictionary[string] != 'undefined') {
        return this.dictionary[string];
    }
    return string;
}

Cdek.prototype.closeWidget = function (widget) {
    widget.slideUp();
}

Cdek.prototype.closeAllWidgets = function () {
    var self = this;
    $(this.row_selector).next('.cdek_widget').each(function () {
        self.closeWidget($(this));
    });
}

Cdek.prototype.openWidget = function () {
    this.closeAllWidgets();

    var checked_option = $(this.option_selector + ':checked');

    if (checked_option.length == 0) {
        return false;
    }
    var row = checked_option.closest(this.row_selector);

    if (row.next('.cdek_widget').length) {
        row.next('.cdek_widget').slideDown();
        return false;
    }

    var template = '';
    var container = $('script#cdek_widget_temp_' + checked_option.val().split(/,/)[0]);

    if (container.length) {
        template = $(container.text());
    }

    row.after(template).next('.cdek_widget').slideDown();
}

Cdek.prototype.openWidgetAdmin = function () {
    this.closeAllWidgets();

    var checked_option = $(this.option_selector);

    var template = '';
    var container = $('script#cdek_widget_temp_' + checked_option.val().split(/,/)[0]);

    if (container.length) {
        template = $(container.text());
        $.fancybox(template);
    }
}

Cdek.prototype.openWidgetSetting = function (option) {
    this.closeAllWidgets();

    option = $.extend({city: 44, postcode: '101000'}, option);

    var template = '';
    var container = $('script#cdek_widget_temp_0');

    if (container.length) {
        var text = container.text();
        if (option.postcode) {
            text = text.replace('--POSTCODE--', option.postcode)
        } else if (option.city) {
            text = text.replace('--CITY--', option.postcode)
        }

        if (option.type) {
            text = text.replace('--TYPE--', option.type)
        }
        template = $(text);
        $.fancybox(template);
    }
}

Cdek.prototype.init = function() {
    this._loadTrans();
    this.option_selector = $('[name^="delivery_option"]').length ? '[name^="delivery_option"]' : '[name="shipping_carrier"]';
    this.row_selector = $('.delivery-option').length ? '.delivery-option' : '';

    if (!this.row_selector) {
        this.row_selector = $('.delivery_option').length ? '.delivery_option' : ''; //1.6
    }

    if ($(this.option_selector).length > 1) {
        $(this.option_selector).on('change', {self: this}, function (e) {
            e.data.self.openWidget();
        });
    } else {
        // admin order
        if ($(this.option_selector).length == 0) {
            //1.7.7
            this.option_selector = '[name="update_order_shipping[new_carrier_id]"]';
        }
        $(this.option_selector).on('change click', {self: this}, function (e) {
            var select$ = $(this);

            if (e.type == 'click') { // opening widget for one shipping after close
                if (select$.find('option').length == 1) {
                    select$.trigger('change').blur();
                }
                return false;
            }

            e.data.self.openWidgetAdmin();
        });
    }

    $('.edit_shipping_link, .js-update-shipping-btn').on('click', {self: this}, function (e) {
        setTimeout(function () {
            if ($('[name="shipping_carrier"]').length > 0) {
                var id_carrier = $('[name="shipping_carrier"]').val().split(/,/)[0];
            } else if ($('[name="update_order_shipping[new_carrier_id]"]').length > 0) { // 1.7.7
                var id_carrier = $('[name="update_order_shipping[new_carrier_id]"]').val();
            }
            if (cdek_params.id_cdek_carrier != id_carrier) {
                return false;
            }
            e.data.self.openWidgetAdmin();
        }, 500);
    });
}

/** этот скрипт загружается так же в панели администратора(hookDisplayBackOfficeHeader)
 *  раньше чем Jquery
 */
var cdek_init_interval = setInterval(function () {
    if (typeof $ != 'undefined') {
        clearInterval(cdek_init_interval);
        $(document).ready(function () {
            window.cdek = new Cdek();
            window.cdek.init();
            window.cdek.openWidget();
        });
    }
}, 1000);
