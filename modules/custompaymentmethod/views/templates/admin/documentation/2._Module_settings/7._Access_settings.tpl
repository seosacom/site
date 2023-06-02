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

<h2 class="text-center">{l s='Access settings' mod='custompaymentmethod'}</h2>
<hr>
{l s='Set' mod='custompaymentmethod'}
<strong>{l s='"Group access".' mod='custompaymentmethod'}</strong> {l s='The payment method will be available to selected groups.' mod='custompaymentmethod'}
{get_image_lang_payment path = '12.jpg'}
<div class="alert alert-warning">
    {l s='Important: Payment methods is available only of distinguished group.' mod='custompaymentmethod'}
</div>
{l s='Set' mod='custompaymentmethod'}
<strong>{l s='"Carrier access".' mod='custompaymentmethod'}</strong> {l s='Select checkbox on the necessary methods. Client will be available to use the custom payments, if he chooses one of your selected carrier methods.' mod='custompaymentmethod'}
{get_image_lang_payment path = '13.jpg'}
{l s='Set' mod='custompaymentmethod'}
<strong>{l s='"View message field".' mod='custompaymentmethod'}</strong> {l s='If this field is enabled, the client will be able to write a comment when ordering products.' mod='custompaymentmethod'}
{l s='Set' mod='custompaymentmethod'}
<strong>{l s='"Active".' mod='custompaymentmethod'}</strong> {l s='Select' mod='custompaymentmethod'}
<strong>{l s='"On".' mod='custompaymentmethod'}</strong>
<div class="alert alert-warning">
    {l s='Important: If the' mod='custompaymentmethod'}
    <strong>{l s='"Active"' mod='custompaymentmethod'}</strong> {l s='is disabled, the module will not work.' mod='custompaymentmethod'}
</div>
{l s='In the end click' mod='custompaymentmethod'} <strong>{l s='"Save".' mod='custompaymentmethod'}</strong>
{l s='Your payment method appear in the module settings. You can it edit, disable, delete.' mod='custompaymentmethod'}
{get_image_lang_payment path = '14.jpg'}



