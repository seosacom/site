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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cdek PVZ</title>
    {if isset($js_def) && is_array($js_def) && $js_def|@count}
        <script type="text/javascript">
            {foreach from=$js_def key=k item=def}
                var {$k|no_escape} = {$def|json_encode|no_escape nofilter};
            {/foreach}
        </script>
    {/if}
    {foreach from=$javascripts key=id item=javascript}
        <script type="text/javascript" src="{$javascript|no_escape}"></script>
    {/foreach}
    <style>

        body {
            direction: ltr;
            font-family: Noto Sans,sans-serif;
            font-size: 1rem;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: #232323;
            line-height: 1.25em;
        }
        
        #cart_details {
            background: #e9f9e2;
            padding-right: 15px;
            padding-left: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>
</head>
<body style="padding:0; margin:0;">
<div id="cart_details">
    <span>{$pickup_point|escape:'html':'UTF-8'}</span><br />
    <span id="info_city">{$city_name|escape:'html':'UTF-8'}</span><br />
    <span id="info_address">{$pvz_address|escape:'html':'UTF-8'}</span>
</div>
<div id="forpvz" style="width:100%;height:496px;"></div>
<script>
    $(document).ready(function () {
        var document_height = $(document.body).height();
        if (document_height)
            $(window.parent.document).find('iframe#cdek_widget_' + cdek.id_cdek_carrier).height(document_height);
    });
</script>
</body>
</html>