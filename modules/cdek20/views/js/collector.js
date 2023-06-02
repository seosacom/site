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

function Collector()
{
    this.grids;
    this.productAreaGrid;
    this.prototype = $('.package-area').data('prototype');
}

function checkCollector()
{
    var package_success = true;
    $(".package-body").each(function(index) {
        if ($(this)[0].childElementCount == 0) {
            jAlert(package_empty, error);
            package_success = false;
        }
    });

    if ($('.product-area')[0].childElementCount > 0) {
        jAlert(all_products_packages, error);
        package_success = false;
    }

    if (package_success) {
        var submit_collector = $('.submit-collector');
        submit_collector.attr('onclick', '');
        submit_collector.trigger( "click" );
    }
}

Collector.prototype.init = function()
{
    var self = this;
    this.grids = GridStack.initAll({
        column: 1,
        minRow: 5,
        cellHeight: 87,
        disableResize: true,
        acceptWidgets: function (el) {
            return true;
        }
    });
    this.grids.forEach(function (grid, i) {
        if (grid.el.classList.contains('package-body')) {
            packages[i].items.forEach(function (item, k) {
                grid.opts.minRow = 3;
                grid.addWidget({
                    content: '<img src="/img/p/' + item.id +'/' + item.id +'-small_default.jpg" alt="" class="imgm img-thumbnail h-100"> ' +
                    ' <span class="item-weight" data-weight="' + item.weight + '">[' + item.id +'] '+ item.name + '</span>' +
                        '<input class="package-item-input" type="hidden" name="package['+(i + 1)+'][items]['+k+']" value=\''+JSON.stringify(item)+'\' />'
                });
            });
            grid.on('added', self.changeGrid).on('removed', self.changeGrid);
        } else {
            self.productAreaGrid = grid;
        }
    });
}

Collector.prototype.removePackage = function(package)
{
    var self = this;
    this.grids.forEach(function (grid, i) {
        if ($(grid.el).closest('.package').data('number') == package.data('number')) {
            grid.getGridItems().forEach(function (item) {
                self.productAreaGrid.addWidget(item);
            });
        }
    });
    package.remove();
    this.reindexPackages();
};

Collector.prototype.addPackage = function(count = 1)
{
    var self = this;
    var package = $(this.prototype);
    $('.package-area').prepend(package);
    this.reindexPackages();
    var packageElements = document.getElementsByClassName('package-body');
    var grid = GridStack.addGrid(packageElements[0], {
        column: 1,
        minRow: 3,
        cellHeight: 87,
        disableResize: true,
        acceptWidgets: function (el) {
            return true;
        }
    });
    grid.on('added', self.changeGrid).on('removed', self.changeGrid);
    this.grids.unshift(grid);
};

Collector.prototype.changeGrid = function(event, items)
{
    var package = $(event.currentTarget).closest('.package');
    var weight = 0;
    setTimeout(function (e, a) {
        package.find('.item-weight').each(function () {
            weight += $(this).data('weight');
        });
        package.find('.package-weight').val(weight);
        package.find('.package-weight-input').val(weight);
        window.collector.reindexItems(package);
    }, 150);
};

Collector.prototype.reindexPackages = function()
{
    var self = this;
    $('.package').each(function (i, el) {
        el.dataset.number = i + 1;
        $(this).find('.data-number').html(i + 1);
        var  dimensionInputs = $(el).find('.dimension-input');
        dimensionInputs.each(function () {
            $(this).attr('name', $(this).attr('name').replace(/\[\d+]/g, '['+(i + 1)+']'))
        });
        var packageWeightInput = $(el).find('.package-weight-input');
        packageWeightInput.attr('name', packageWeightInput.attr('name').replace(/\d+/g, i + 1));
        self.reindexItems($(el));
    });
};

Collector.prototype.reindexItems = function(package)
{
    package.find('.package-item-input').each(function (i, el) {
        var packageNumber = package.attr('data-number');
        el.name = el.name.replace(/[^\d]+(\d+)[^\d]+(\d+)]/g, () => {
            return `package[${packageNumber}][items][${i}]`
        });
    });
};

$(document).ready(function($) {

    $(document).on('click', '.remove-package', function (e) {
        var package = $(this).closest('.package');
        collector.removePackage(package);
    });

    $(document).on('click', '.add-package', function (e) {
        collector.addPackage();
    });

    var collector = new Collector();
    collector.init();
    window.collector = collector;
});