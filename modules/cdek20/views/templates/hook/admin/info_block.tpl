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

<div id="{if $smarty.const._PS_VERSION_ < '1.7.7.0'}cdek_info_block{else}cdekTabContent{/if}" data-id-order="{$cdek_order->id_order|intval}" class="tab-pane {if $smarty.const._PS_VERSION_ >= '1.7.7.0'}show active{/if}">
    {*<div id="cdek_loader">*}
        {*<div class="spinner">*}
            {*<div class="rect1"></div>*}
            {*<div class="rect2"></div>*}
            {*<div class="rect3"></div>*}
            {*<div class="rect4"></div>*}
            {*<div class="rect5"></div>*}
            {*<div class="rect6"></div>*}
            {*<div class="rect7"></div>*}
            {*<div class="rect8"></div>*}
        {*</div>*}
    {*</div>*}
    <div id="cdek_info_content" class="form-group">
        {include './info_content.tpl'}
    </div>
</div>
