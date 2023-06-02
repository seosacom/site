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

<h2 class="text-center">{l s='"Statuses" tab' mod='cdek20'}</h2>
<hr>

{l s='Column' mod='cdek20'}
<strong>{l s='"Create order"' mod='cdek20'}</strong><br>
{l s='The order in the CDEK service itself will be generated after setting a certain status in Prestashop orders.' mod='cdek20'}<br>
{l s='Tick the statuses under which the order will be generated in CDEK' mod='cdek20'}<br>
{l s='You can choose several statuses.' mod='cdek20'}<br>
<br>
{l s='Column' mod='cdek20'}
<strong>{l s='"Delete order"' mod='cdek20'}</strong><br>
{l s='The order in the CDEK service itself will be canceled after setting a certain status in Prestashop orders.' mod='cdek20'}<br>
{l s='Tick the statuses in which the order in CDEK will be canceled' mod='cdek20'}<br>
{l s='You can select multiple statuses.' mod='cdek20'}<br>
<br>
{l s='Columns' mod='cdek20'}
<strong>{l s=' "Payment for delivery upon receipt"' mod='cdek20'}</strong>
{l s='and' mod='cdek20'}
<strong>{l s='"Payment for products upon receipt".' mod='cdek20'}</strong><br>
{l s='Configure the statuses in which the user pays for the order at checkout and prepayment online.' mod='cdek20'}<br>
{l s='If the client will pay for the order in full upon receipt, then tick the columns' mod='cdek20'}
<strong>{l s=' "Payment for delivery upon receipt"' mod='cdek20'}</strong>
{l s='and' mod='cdek20'}
<strong>{l s='"Payment for products upon receipt".' mod='cdek20'}</strong><br>
{l s='If the client will pay only for delivery, then check the box' mod='cdek20'}
<strong>{l s=' "Payment for delivery upon receipt"' mod='cdek20'}</strong><br>
{l s='If the client will pay only for the products, then check the boxes for the column' mod='cdek20'}
<strong>{l s='"Payment for products upon receipt".' mod='cdek20'}</strong><br>
{l s='If the client has fully paid for the order online, then there is no need to check the boxes for' mod='cdek20'}
<strong>{l s=' "Payment for delivery upon receipt"' mod='cdek20'}</strong>
{l s='and' mod='cdek20'}
<strong>{l s='"Payment for products upon receipt".' mod='cdek20'}</strong><br>
{l s='In this case, zero cost will be transmitted in the CDEK invoice, since the customer has already paid.' mod='cdek20'}<br>
{get_image_lang_cdek path = '8.jpg'}<br>
