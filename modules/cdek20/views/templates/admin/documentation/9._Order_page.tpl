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

<h2 class="text-center">{l s='Order page' mod='cdek20'}</h2>
<hr>

{l s='In the admin panel on the order page there is a block from the CDEK delivery method' mod='cdek20'}</br>
{get_image_lang_cdek path = '10.jpg'}</br>
</br>
{l s='This block displays information about the order and buttons:' mod='cdek20'}
<strong>>{l s=' "Regenerate", "Send", "Delete", "Create invoice" "Call courier", "Cancel courier","Edit boxes", and Comment' mod='cdek20'}</strong></br>
</br>
<strong>{l s='"Regenerate" button' mod='cdek20'}</strong></br>
{l s='You can edit the order before creating an order in CDEK (or after canceling)' mod='cdek20'}</br>
{l s='After editing, click "Regenerate" to update the information in the CDEK block.' mod='cdek20'}</br>
</br>
<strong>{l s='Field "Comment"' mod='cdek20'}</strong></br>
{l s='In the CDEK block there is a field "comment to the order"' mod='cdek20'}</br>
{l s='You can add a shipping note.' mod='cdek20'}</br>
{l s='To do this, fill in the "Comment" field and click the "Regenerate" button' mod='cdek20'}</br>
</br>
<strong>{l s='Send Button' mod='cdek20'}</strong></br>
{l s='There are three shipping options:' mod='cdek20'}</br>
<strong>{l s='"Send (paid)"' mod='cdek20'}</strong></br>
<strong>{l s='"Send (paid for goods only)"' mod='cdek20'}</strong></br>
<strong>{l s='"Send (not paid)"' mod='cdek20'}</strong></br>
</br>
<strong>{l s='"Send (paid)" button' mod='cdek20'}</strong></br>
{l s='You can create an order on the CDEK side by clicking "Send (paid)"' mod='cdek20'}</br>
{l s='In this case, an order will be created in CDEK and the amount payable will be 0 rubles.' mod='cdek20'}</br>
{l s='Use if a customer places an order in your store with online prepayment. Then, upon receipt, the client no longer has to pay anything.' mod='cdek20'}</br>
</br>
<strong>{l s='"Send (paid for products)" button' mod='cdek20'}</strong></br>
{l s='You can create an order on the CDEK side by clicking "Send (paid only for goods)"' mod='cdek20'}</br>
{l s='In this case, an order will be created in CDEK and the invoice will indicate the amount payable only for delivery.' mod='cdek20'}</br>
{l s='Use if the client places an order in a store with online prepayment and pays for the goods. Payment for the delivery itself occurs upon receipt.' mod='cdek20'}</br>
</br>
<strong>{l s='"Send (not paid)" button' mod='cdek20'}</strong></br>
{l s='You can create an order on the CDEK side by clicking "Send (paid)"' mod='cdek20'}</br>
{l s='In this case, an order will be created in CDEK and the invoice will indicate the full amount payable.' mod='cdek20'}</br>
{l s='Use if a customer places an order in your store with cash on delivery' mod='cdek20'}</br>
</br>
{l s='You can also send an order to SDEK by switching the status.' mod='cdek20'}</br>
{l s='The order will go to SDEK in accordance with the status settings' mod='cdek20'}</br>
</br>
<strong>{l s='"Delete" button' mod='cdek20'}</strong></br>
{l s='You can delete an order on the CDEK side by clicking "Delete" ' mod='cdek20'}</br>
{l s='or by switching to a status that is configured to delete' mod='cdek20'}</br>
</br>
<strong>{l s='"Create invoice" button' mod='cdek20'}</strong></br>
{l s='After the order is created in CDEK, the "Create invoice" button will appear' mod='cdek20'}</br>
{l s='When you click, a link will appear to download the invoice from CDEK' mod='cdek20'}</br>
</br>
<strong>{l s='Button "Call a courier"' mod='cdek20'}</strong></br>
{l s='If the order is placed with the tariff "from the door", then you need to call the courier. ' mod='cdek20'}</br>
{l s='Sending an application to call a courier becomes available only after creating an order in CDEK' mod='cdek20'}</br>
</br>
<strong>{l s='Button "Cancel courier"' mod='cdek20'}</strong></br>
{l s='When deleting an order from CDEK, it is necessary to delete the application for calling a courier' mod='cdek20'}</br>
</br>
<strong>{l s='Option "Edit boxes"' mod='cdek20'}</strong></br>
{l s='You can separately change the distribution of goods into boxes for each order before sending them to CDEK.' mod='cdek20'}</br>
{l s='You can change the size of the boxes.' mod='cdek20'}</br>
{l s='To do this, click "Edit boxes".' mod='cdek20'}</br>
{l s='A window will appear with the goods distribution settings.' mod='cdek20'}</br>
{l s='Boxes are displayed on the left side.' mod='cdek20'}</br>
{l s='You can drag and drop products between boxes, you can create and delete boxes as needed.' mod='cdek20'}</br>
{l s='The right side displays a column for the temporary location of products.' mod='cdek20'}</br>
{l s='For example, when you have a lot of products and a lot of boxes, you can drag the products to the right block for convenience and then distribute them among the boxes.' mod='cdek20'}</br>
{l s='After settings Save changes.' mod='cdek20'}</br>
</br>
{l s='Important: Saving is not possible if you left an empty box.' mod='cdek20'}</br>
{l s='You need to add a product for it or remove the box.' mod='cdek20'}</br>
{l s='You can not leave products in the right column. ' mod='cdek20'}</br>
{l s='It is necessary to distribute all the goods into boxes.' mod='cdek20'}</br>
</br>
{l s='Important: If you click "Regenerate" after manually sorting into boxes, then all sorting into boxes will be reset to default settings.' mod='cdek20'}</br>
</br>
{l s='Important: If the option "All products in one box" is enabled on the "General" tab, distribution by box will not be available' mod='cdek20'}</br>
{get_image_lang_cdek path = '11.jpg'}</br>
