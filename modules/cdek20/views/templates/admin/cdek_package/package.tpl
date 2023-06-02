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

<div class="package info-block mb-2" data-number="{$package.number}">
    <div class="package-header clearfix ">
        <div class="mb-2 clearfix">
            <div class="package-info">
                <div class="">
                    <h3 class="m-0 mb-1 d-print-none">

                        {if $packages[0]['items'][0]['id']}
                            {l s='Package' mod='cdek20'} <span class="data-number">{$package.number}</span>
                        {else}
                            {l s='Package' mod='cdek20'}
                        {/if}

                        {if $weight_unit == 1000}
                            ({l s='Kilogram' mod='cdek20'} /
                        {else}
                            ({l s='Gram' mod='cdek20'} /
                        {/if}

                        {if $volume_unit == 0.100000}
                            {l s='Millimeter' mod='cdek20'})
                        {else}
                            {l s='Centimeter' mod='cdek20'})
                        {/if}
                    </h3>
                </div>

                {*<div class="input-group col-lg-4">*}
                    {*{l s='Weight'}: <span class="package-weight">{if $package}{$package.weight}{else}0{/if}</span>*}
                    {*<input type="hidden" class="package-weight-input" name="package[{if $package}{$package.number}{else}1{/if}][weight]" value="{if $package}{$package.weight}{else}0{/if}" />*}
                {*</div>*}


            </div>

        </div>

        <div class="form-group">

            <div class="float-left mr-1">
                <div>{l s='Weight' mod='cdek20'}</div>
                <input class="package-weight fixed-width-sm d-inline-block m-0" disabled type="text" value="{if $package}{$package.weight}{else}0{/if}">
                <input type="hidden" class="package-weight-input" name="package[{if $package}{$package.number}{else}1{/if}][weight]" value="{if $package}{$package.weight}{else}0{/if}" />
            </div>

            <div class="float-left mr-1">
                <div>{l s='Width' mod='cdek20'}</div>
                <input class="fixed-width-sm d-inline-block dimension-input m-0" type="text" name="package[{if $package}{$package.number}{else}1{/if}][length]" value="{if $package}{$package.length}{else}0{/if}">
            </div>
            <div class="float-left mr-1">
                <div>{l s='Height' mod='cdek20'}</div>
                <input class="fixed-width-sm d-inline-block dimension-input m-0" type="text" name="package[{if $package}{$package.number}{else}1{/if}][width]" value="{if $package}{$package.width}{else}0{/if}">
            </div>
            <div class="float-left mr-1">
                <div>{l s='Depth' mod='cdek20'}</div>
                <input class="fixed-width-sm d-inline-block dimension-input m-0" type="text" name="package[{if $package}{$package.number}{else}1{/if}][height]" value="{if $package}{$package.height}{else}0{/if}">
            </div>

            <div class="float-left mr-1">
                <div class="invisible">{l s='Remove package' mod='cdek20'}</div>
                <a class="remove-package btn btn-danger">{l s='Remove' mod='cdek20'}</a>
            </div>

        </div>
    </div>

    {if $packages[0]['items'][0]['id']}
        <div class="">
            <div class="grid-container center">
                <div class="package-body clearfix grid-stack">
                </div>
            </div>
        </div>
    {else}
        <div class="hidden">
            <div class="grid-container center">
                <div class="package-body clearfix grid-stack">
                </div>
            </div>
        </div>
        {if $packages[0]['items'][0]['name'] > 0}
            {l s='Order' mod='cdek20'} {$id_order}. {l s='Package not available. All products - one package.' mod='cdek20'}
        {else}
            {l s='Order' mod='cdek20'} {$id_order}. {l s='Package not available. All products - one box' mod='cdek20'}
        {/if}
    {/if}

</div>