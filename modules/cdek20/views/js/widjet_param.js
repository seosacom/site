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

    var iWidjet = new ISDEKWidjet ({
        defaultCity: widget_settings.defaultCity,
        cityFrom: widget_settings.cityFrom,
        lang: widget_settings.lang,
        link: widget_settings.link,
        region: widget_settings.region,
        path: widget_settings.path,
        templatepath: widget_settings.templatepath,
        servicepath: widget_settings.servicepath,
        widgetpath: widget_settings.widgetpath,
        showWarns: widget_settings.showWarns,
        showErrors: widget_settings.showErrors,
        showLogs: widget_settings.showLogs,
        hidedelt: widget_settings.hidedelt,
        onReady: onReady,
        onChoose: onChoose
    });

    function onReady() {

    }
    
    function onChoose(wat) {
        if ($(window.parent.document).find('[name="pvz_warehouse"]').length) { // for settings
            $(window.parent.document).find('[name="pvz_warehouse"]').val(wat.id + '|' + wat.PVZ.Address);
            return false;
        }
        $.ajax({
            data: {ajax: true, action: 'select_pvz', pvz: wat.PVZ.code},
            method: 'post',
            dataType: 'json',
            success: function (r) {
                if (r.result) {
                    var city_name = widget.widget.DATA.city.getFullName(wat.city);
                    widget.widget.template.controller.setInfoBlock(city_name, wat.PVZ.Address);
                }
            }
        });
    }

    function CdekWidget(widget, cdek) {
        this.widget = widget;
        this.delivery_option = false;
        this.iframe = false;
        this._init();
    }

    CdekWidget.prototype._init = function () {
        // var self = this;
        // this.iframe = $(window.parent.document).find('iframe#cdek_widget');
        // this.delivery_option = $(window.parent.document).find('[name^="delivery_option"]');
        // this.delivery_option.on('change', function () {
        //
        //     if (cdek.id_cdek_carrier == $(this).val().split(/,/)[0]) {
        //         var timerId = setInterval(function () {
        //             var document_height = $(document).height();
        //             if (document_height) {
        //                 clearInterval(timerId);
        //                 self.iframe.height($(document).height());
        //                 self.widget.template.ymaps.placeMarks();
        //             }
        //         }, 500);
        //     }
        // });
    }

    window.widget = new CdekWidget(iWidjet, cdek);
});
