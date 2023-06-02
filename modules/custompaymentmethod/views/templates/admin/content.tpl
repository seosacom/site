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

<div class="doc_switch_btn">
    <label for="doc_switch_1"><input type="radio" id="doc_switch_1" name="doc_switch" value="1">
        <span>{'Documentation'|ld}</span>
    </label>
    <label for="doc_switch_0">
        <input checked type="radio" id="doc_switch_0" name="doc_switch" value="0"><span>{'Settings'|ld}</span>
    </label>
    <a class="float-right" id="seosa_manager_btn" href="#">{'Our modules'|ld}</a>
</div>
<div class="wrap_not_documentation custom_bootstrap">{$content_tab|no_escape}</div>
<div class="wrap_documentation">{$documentation|no_escape}</div>