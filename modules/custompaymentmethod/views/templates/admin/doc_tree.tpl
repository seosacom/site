{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    SeoSA <885588@bk.ru>
*  @copyright 2012-2023 SeoSA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if (is_array($tree) && count($tree))}
    {foreach from=$tree key=name item=tree_item}
        {assign var='res' value=preg_match('/^(\d+)\._(.*)$/', $name, $matches)}
        {assign var='format_name' value="{$matches[1]}. {$matches[2]|ld}"}
        <li>
            <a {if !is_array($tree_item)}data-tab="{$tree_item|escape:'quotes':'UTF-8'}"
               href="#"{/if}>{$format_name|escape:'quotes':'UTF-8'}</a>
            {if (is_array($tree_item) && count($tree_item))}
                <ul>
                    {assign var='tree2' value=$tree_item}
                    {if (is_array($tree2) && count($tree2))}
                        {foreach from=$tree2 key=name item=tree_item2}
                            {assign var='res' value=preg_match('/^(\d+)\._(.*)$/', $name, $matches)}
                            {assign var='format_name' value="{$matches[1]}. {$matches[2]|ld}"}
                            <li>
                                <a {if !is_array($tree_item2)}data-tab="{$tree_item2|escape:'quotes':'UTF-8'}"
                                   href="#"{/if}>{$format_name|escape:'quotes':'UTF-8'}</a>
                                {if (is_array($tree_item2) && count($tree_item2))}
                                    <ul>
                                        {assign var='tree3' value="{$tree_item2}"}
                                    </ul>
                                {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            {/if}
        </li>
    {/foreach}
{/if}