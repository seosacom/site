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
    if (typeof cdek_request != 'undefined') {
        console.log('cdek_request', cdek_request);
    }

    if (typeof cdek_info_courier != 'undefined') {
        console.log('courier', cdek_info_courier);
    }
    if (typeof cdek_info_pickup != 'undefined') {
        console.log('pickup', cdek_info_pickup);
    }

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {

            var val = $(mutation.addedNodes).val();
            if (typeof val != 'undefined') {
                var id_carrier = val.split(/,/)[0];
                if (cdek_params.id_cdek_carrier != id_carrier) {
                    return false;
                }
                if (typeof cdek.iframe[id_carrier] != 'undefined') {
                    $.fancybox(cdek.iframe[id_carrier]);
                }
            }
        });
    });

    var target = document.getElementById('delivery_option');
    if (target) {
        observer.observe(
            target,
            {
                childList: true
            }
        );
    }
// info block
    function CdekOrder(info_block) {
        this.info_block = info_block;
    }
    CdekOrder.prototype._onLoader = function () {
        $('#cdek_loader').show();
    }
    CdekOrder.prototype._offLoader = function () {
        $('#cdek_loader').hide();
    }
    CdekOrder.prototype.refresh = function () {
        var info_button = this.info_block.find('[data-action="info"]');

        if (info_button.length) {
            this.click(info_button);
        }
    }
    CdekOrder.prototype.click = function(button) {
        if ($(button).length && $(button).data('action') != 'info' && !confirm(window.cdek.trans('Are you sure you want to perform an action?'))) {
            return false;
        }
        var self = this;
        self._onLoader();
        var data = {};
        data.id_order = self.info_block.data('idOrder');
        data.action = $(button).data('action');
        data.note = $('#order_note').val();
        data.ajax = 1;
        $.ajax({
            url: cdek_params.request_url,
            dataType: 'html',
            data: data,
            success: function (html) {
                self.info_block.html(html);
                if (data.action == 'create' || data.action == 'remove') {
                    self.refresh();
                }
                self.info_block.find('#package_button').attr('href', cdek_params.package_url);
            },
            complete: function () {
                self._offLoader();
            }
        });
    }

    var info_block = false;
    if ($('#cdek_info_block').length > 0) {
        info_block = $('#cdek_info_block');
    } else if ($('#cdekTabContent').length > 0) { // >=1.7.7.0
        info_block = $('#cdekTabContent');
    } else {
        console.log('info_block is not defined');
        return false;
    }
    window.cdek_order = new CdekOrder(info_block);

    window.cdek_order.refresh();

    $("body").on('click', '.js-send-cdek-toggle-title', function () {
        $('.js-send-cdek-block').toggleClass('d-none');
    });

// end info block
});

