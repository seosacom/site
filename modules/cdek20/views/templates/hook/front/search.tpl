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

{if $cdek_city_name}
    {*<p>{l s='CDEK defined your city as' mod='cdek20'}:</p>*}
    {if $carrier_count}
        <form action="" method="post">
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary" name="sort_cdek_carriers" value="{if $sort_cdek_carriers == 'delay'}price{else}delay{/if}">
                        {if $sort_cdek_carriers == 'delay'}
                            {l s='Show cheap ones' mod='cdek20'}
                        {else}
                            {l s='Show fast deliveries' mod='cdek20'}
                        {/if}
                    </button>
                </div>
            </div>
        </form>
        {if version_compare($prestashop_version, '1.7', '<')}
            <script>
                $('[name="sort_cdek_carriers"]').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest('form');
                    form.attr('name', '');
                    form.attr('action', form.attr('action') + '?step=2&sort_cdek_carriers=' + form.find('[name="sort_cdek_carriers"]').val());
                    form.find('input').remove();
                    form.submit();
                });
            </script>
        {/if}
    {/if}
{else}
    <p class="carrier_title">{l s='CDEK did not identify your city' mod='cdek20'}</p>
    <form method="post" class="carrier_form">
        <div class="form-group row">
            <div class="col-md-12">
                <label class="float-left form-control-label text-right mr-1">
                    {l s='Enter' mod='cdek20'}:
                </label>
                <div class="float-left fixed-width-xxl mr-1">
                    <input class="form-control" type="text" id="city_search" value="{$cdek_city_name|escape:'html':'UTF-8'}" name="search_city">
                    <input type="hidden" id="city_code" name="cdek_city_code">
                </div>
                <div class="float-left form-control-comment warning">
                    {l s='Write the region' mod='cdek20'}
                </div>
            </div>
        </div>
    </form>
{/if}

