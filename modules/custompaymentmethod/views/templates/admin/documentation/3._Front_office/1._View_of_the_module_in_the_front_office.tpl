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

<h2 class="text-center ">{l s='View of the module in the front office' mod='custompaymentmethod'}</h2>
<hr>
{l s='When ordering, in the column' mod='custompaymentmethod'}
<strong>{l s='"Payment methods"' mod='custompaymentmethod'}</strong>{l s=', appear your payment method.' mod='custompaymentmethod'}
{get_image_lang_payment path = '15.jpg'}
<div class="alert alert-warning">
    {l s='Important: If the module "Active" is disabled, in list will not be your method of payment.' mod='custompaymentmethod'}
</div>
{l s='There will be more information available to the client.' mod='custompaymentmethod'}
{get_image_lang_payment path = '16.jpg'}