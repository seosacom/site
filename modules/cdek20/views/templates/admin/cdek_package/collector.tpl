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

<form id="collector_form" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data" novalidate>
    <div class="panel bootstrap clearfix">
        <div class="panel-heading"><i class="icon-info-sign"></i>
            {l s='Collector' mod='cdek20'}
        </div>

        {if $packages[0]['items'][0]['id']}
            <div class="mb-1">
                {l s='Collector description' mod='cdek20'}
            </div>
        {/if}

        <div class="row tab-content" style="display: flex">
            <div class="package-area col-lg-6" data-prototype='{include file="./package.tpl" package=false}'>
                {foreach from=$packages item="package" key="key"}
                    {include file="./package.tpl" package=$package}
                {/foreach}
            </div>

            {if $packages[0]['items'][0]['id']}
                <div class="col-lg-6">
                    <div class="info-block" style="position: sticky; top: 100px;">
                        <h3 class="m-0 mb-2 d-print-none">
                            {l s='Product list' mod='cdek20'}
                        </h3>
                        <div class="product-area-header form-group">
                            <a class="btn btn-primary add-package">{l s='Add package' mod='cdek20'}</a>
                        </div>
                        <div class="">
                            <div class="grid-container center">
                                <div class="product-area grid-stack"></div>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

        </div>

        <div class="panel-footer">
            <button type="submit" value="1" name="submitCollector" onclick="checkCollector();return false;" class="btn btn-default pull-right submit-collector">
                <i class="process-icon-save"></i> {l s='Save' mod='cdek20'}
            </button>
            <a class="btn btn-default" onclick="javascript:window.history.back();">
                <i class="process-icon-cancel"></i> {l s='Cancel' mod='cdek20'}
            </a>
        </div>
    </div>
</form>

<script type="text/javascript">
    var error = "{l s='Error' mod='cdek20'}";
    var package_empty = "{l s='The package cannot be empty' mod='cdek20'}";
    var all_products_packages = "{l s='Add all products to packages' mod='cdek20'}";
</script>