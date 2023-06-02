{**
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
 *}

<a class="back_doc button btn btn-default" href="{Context::getContext()->link->getAdminLink('AdminCdekSetting', true)|escape:'quotes':'UTF-8'}">{l s='Back' mod='cdek20'}</a>

<div class="form-group tab_manager clearfix">
    <div class="col-lg-3">
        <div class="panel">
            <div class="panel-body">
                <ul class="tab_links nav nav-pills nav-stacked">
                    {$tree|no_escape}
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="panel">
            <div class="panel-body tab_contents">
                {foreach item='documentation_page' from=$documentation_pages}
                    <div data-tab-content="{str_replace(array($documentation_folder|cat:'/', '.tpl'), '', $documentation_page)|no_escape}">
                        {include file=$documentation_page}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

<script>
    $.fn.tabManager = function () {
        function TabManager(elem)
        {
            var self = this;
            self.element = $(elem);

            self.element.find('[data-tab]').on('click', function (e) {
                e.preventDefault();
                self.element.find('[data-tab-content]').hide();
                self.element.find('[data-tab-content="'+$(this).data('tab')+'"]').show();
            });

            self.element.find('[book-link]').on('click', function (e) {
                e.preventDefault();
                if (!self.element.find('[data-tab-content="'+$(this).attr('book-link')+'"]').length)
                    return false;
                self.element.find('[data-tab-content]').hide();
                self.element.find('[data-tab-content="'+$(this).attr('book-link')+'"]').show();
            });

            self.element.find('[data-tab]').eq(0).trigger('click');
        }

        $.each(this, function (index, elem) {
            if (!$(elem).data('tab-manager'))
                $(elem).data('tab-manager', new TabManager(elem));
        });
    };

    $('.tab_manager').tabManager();

    $('[name="doc_switch"]').live('change', function () {
        if (parseInt($(this).val()))
        {
            $('.wrap_not_documentation').hide();
            $('.wrap_documentation').show();
        }
        else
        {
            $('.wrap_not_documentation').show();
            $('.wrap_documentation').hide();
        }
    });
</script>