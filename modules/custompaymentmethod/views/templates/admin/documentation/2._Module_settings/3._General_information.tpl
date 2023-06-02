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

<h2 class="text-center">{l s='General information' mod='custompaymentmethod'}</h2>
<hr>
{l s='Choose language. Enter  name (required).' mod='custompaymentmethod'}
{get_image_lang_payment path = '4.jpg'}
<div class="alert alert-warning">
    {l s='Important: Without name payment settings are not saved.' mod='custompaymentmethod'}
</div>
{l s='Add logo. For this click' mod='custompaymentmethod'}
<strong>{l s='"Add file"' mod='custompaymentmethod'}</strong> {l s='and select the desired file.' mod='custompaymentmethod'}
{get_image_lang_payment path = '5.jpg'}
<div class="alert alert-warning">
    {l s='Important: Size of logo shown on home page module settings.' mod='custompaymentmethod'}
</div>
{l s='Choose language. Enter' mod='custompaymentmethod'} <strong>{l s='"Details"' mod='custompaymentmethod'}</strong> ,
<strong>{l s='"Description short".' mod='custompaymentmethod'}</strong>
<strong>{l s='"Description"' mod='custompaymentmethod'}</strong>
{get_image_lang_payment path = '6.jpg'}
