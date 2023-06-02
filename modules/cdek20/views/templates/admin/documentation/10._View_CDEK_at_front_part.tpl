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

<h2 class="text-center">{l s='Displaying at front part' mod='cdek20'}</h2>
<hr>

{l s='If the site enabled index field for the client is address, then CDEK will display delivery and pickup points for this index (if delivery is possible)' mod='cdek20'}<br>
{get_image_lang_cdek path = '12.jpg'}
<br>
{l s='If there is no index field, the message "delivery for CDEK is not defined" is displayed.' mod='cdek20'}
{l s='The client can enter the locality, then the delivery from CDEK will appear' mod='cdek20'}<br>
{get_image_lang_cdek path = '13.jpg'}
<br>
<strong>{l s='Option "Show cheap shipping" "Show fast shipping"' mod='cdek20'}</strong><br>
{l s='If you select "Show cheap shipping", the cheapest shipping will be displayed' mod='cdek20'}<br>
{l s='If you select "Show fast delivery", the fast delivery will be displayed' mod='cdek20'}<br>
<br>
{l s='There are 3 types of delivery: Courier, Pickup, Postomat. ' mod='cdek20'}<br>
{l s='Each of these deliveries have their own rates. the first available tariff is displayed.' mod='cdek20'}<br>
{l s='When using the Show cheapest / fastest shipping option, the appropriate tariff will be selected.' mod='cdek20'}<br>
{get_image_lang_cdek path = '14.jpg'}
