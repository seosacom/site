## Custom payment method

## About

You create a simple and convenient method of payment with any details. For each payment method: you can configure additional percentage / amount commision, select a different order status after order.
Quantity of payment methods is unlimited.

## Contributing

SeoSa modules are Proprietary extensions to the PrestaShop e-commerce solution.

1. Download module on server
2. Install
If you have did not work the module.
3. Find file controllers/admin/AdminOrdersController.php
	Add row after line:
	foreach (PaymentModule::getInstalledPaymentModules() as $p_module)
    			$payment_modules[] = Module::getInstanceById((int)$p_module['id_module']);
    }
  This:
    			
  Hook::exec('cpmPaymentModules', array('payment_modules' => &$payment_modules));

Please make these steps for each language in  your shop:

- Add commission on mail:

1. Open file: mails/en/order_conf.html
2. After the desired block:

                <tr class="conf_body">
                    ...
                </tr>
Add this:

                <tr class="conf_body">
					<td bgcolor="#f8f8f8" colspan="4" style="border:1px solid #D6D4D4;color:#B3351D;padding:7px 0">
						<table class="table" style="width:100%;border-collapse:collapse">
							<tr>
								<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
								<td align="right" style="color:#B3351D;padding:0">
									<font size="2" face="Open-sans, sans-serif" color="#B3351D">
										<strong>{commission_title}</strong>
									</font>
								</td>
								<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
							</tr>
						</table>
					</td>
					<td bgcolor="#f8f8f8" align="right" colspan="4" style="border:1px solid #D6D4D4;color:#B3351D;padding:7px 0">
						<table class="table" style="width:100%;border-collapse:collapse">
							<tr>
								<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
								<td align="right" style="color:#B3351D;padding:0">
									<font size="2" face="Open-sans, sans-serif" color="#B3351D">
										{commission}
									</font>
								</td>
								<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

3. Save


1. Open file: mails/en/order_conf.txt
2. After line:
{total_products}

Add this:

{commission_title}

{commission}

PAYMENT DISCOUNT

{discount}

3. Save


1. Open file: modules/mailalerts/mails/en/new_order.html (For Prestashop 1.7 This file may be located inside your themplate. Start your search there.)
2. After line:
<tr class="conf_body">

Add this:

<td bgcolor="#f8f8f8" colspan="4" style="border:1px solid #D6D4D4;color:#B3351D;padding:7px 0">
							<table class="table" style="width:100%;border-collapse:collapse">
								<tr>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
									<td align="right" style="color:#B3351D;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#B3351D">
											<strong>Payment costs</strong>
										</font>
									</td>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td bgcolor="#f8f8f8" align="right" colspan="4" style="border:1px solid #D6D4D4;color:#B3351D;padding:7px 0">
							<table class="table" style="width:100%;border-collapse:collapse">
								<tr>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
									<td align="right" style="color:#b3351d;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#B3351D">
											{commission}
										</font>
									</td>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr class="conf_body">
						<td bgcolor="#f8f8f8" colspan="4" style="border:1px solid #D6D4D4;color:#B3351D;padding:7px 0">
							<table class="table" style="width:100%;border-collapse:collapse">
								<tr>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
									<td align="right" style="color:#B3351D;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#B3351D">
											<strong>Payment discount</strong>
										</font>
									</td>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td bgcolor="#f8f8f8" align="right" colspan="4" style="border:1px solid #D6D4D4;color:#B3351D;padding:7px 0">
							<table class="table" style="width:100%;border-collapse:collapse">
								<tr>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
									<td align="right" style="color:#B3351D;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#B3351D">
											{discount}
										</font>
									</td>
									<td width="10" style="color:#B3351D;padding:0">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr class="conf_body">

3. Save

1. Open file: modules/mailalerts/mails/en/new_order.txt (For Prestashop 1.7 This file may be located inside your themplate. Start your search there.)
2. After line:
{total_products}

Add this:
PAYMENT COMMISSION

{commission}

PAYMENT DISCOUNT

{discount}

3. Save


1. Open file: modules/mailalerts/mailalerts.php
2. After line:

 '{message}' => $message
);

Add this:

// add commission

        if (Module::isEnabled('custompaymentmethod'))
        {
            /**
             * @var $module custompaymentmethod
             **/
            require_once(_PS_MODULE_DIR_. 'custompaymentmethod/classes/CustomPayment.php');
            $row_commission = CustomPayment::getOrderCommission($order->id);
            $row_discount = CustomPayment::getOrderDiscount($order->id);

   $commission = 0;
   if (array_key_exists('commission', $row_commission))
      $commission = $row_commission['commission'];
   elseif (isset($this->context->cookie->{'cpm_commission_'.$order->id}))
      $commission = $this->context->cookie->{'cpm_commission_'.$order->id};
   $discount = 0;
   if (array_key_exists('discount', $row_discount))
      $discount = $row_discount['discount'];
   elseif (isset($this->context->cookie->{'cpm_discount_'.$order->id}))
      $discount = $this->context->cookie->{'cpm_discount_'.$order->id};
   $id_currency = null;
   if (array_key_exists('id_currency', $row_commission))
      $id_currency = $row_commission['id_currency'];
   else
      $id_currency = $order->id_currency;

   $template_vars['{commission}'] = Tools::displayPrice($commission, (int)$id_currency);
   $template_vars['{discount}'] = Tools::displayPrice($discount, (int)$id_currency);
   $template_vars['{total_paid}'] = Tools::displayPrice($order->total_paid + $commission + $discount, (int)$id_currency);
}

// end commission

3. Save


1. Open file: modules/custompaymentmethod/controllers/front/validation.php
2. After line:
 $mail_vars['{total_paid}'] = Tools::displayPrice($total, $this->context->currency, false);

Add this:

 $mail_vars['{total_paid_m}'] = Tools::displayPrice($total, $this->context->currency, false);

3. Save


Please make these steps for each language in  your shop:

- Add commission on mail:

For Prestashop 1.7.5
----------------------------------------------------------
			 
1. Open file:	/modules/ps_emailalerts/ps_emailalerts.php
2. After line:
               '{message}' => $message
);

Add this:
// add commission

        if (Module::isEnabled('custompaymentmethod'))
        {
            /**
             * @var $module custompaymentmethod
             */
            require_once(_PS_MODULE_DIR_. 'custompaymentmethod/classes/CustomPayment.php');
            $row_commission = CustomPayment::getOrderCommission($order->id);
            $row_discount = CustomPayment::getOrderDiscount($order->id);

            $commission = 0;
            if (array_key_exists('commission', $row_commission))
      $commission = $row_commission['commission'];
   elseif (isset($this->context->cookie->{'cpm_commission_'.$order->id}))
      $commission = $this->context->cookie->{'cpm_commission_'.$order->id};
   $discount = 0;
   if (array_key_exists('discount', $row_discount))
      $discount = $row_discount['discount'];
   elseif (isset($this->context->cookie->{'cpm_discount_'.$order->id}))
      $discount = $this->context->cookie->{'cpm_discount_'.$order->id};
   $id_currency = null;
   if (array_key_exists('id_currency', $row_commission))
      $id_currency = $row_commission['id_currency'];
   else
      $id_currency = $order->id_currency;

   $template_vars['{commission}'] = Tools::displayPrice($commission, (int)$id_currency);
   $template_vars['{discount}'] = Tools::displayPrice($discount, (int)$id_currency);
   $template_vars['{total_paid}'] = Tools::displayPrice($order->total_paid + $commission + $discount, (int)$id_currency);
}

// end commission

3. Save

1. Open file: modules/custompaymentmethod/controllers/front/validation.php
2. After line:
              $mail_vars['{total_paid}'] = Tools::displayPrice($total, $this->context->currency, false);
   Add this:
              $mail_vars['{total_paid_m}'] = Tools::displayPrice($total, $this->context->currency, false);
3. Save
        
		
1. Open file:	/themes/classic/mails/it/order_conf.html
(if the theme does not have mail folder, then open  mail folder in main folder of site)
2. Before code :	
	<tr class="conf_body">
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;">
                      <span size="2" face="Open-sans, sans-serif" color="#555454" style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> 
                          <strong>Spedizione (IVA incl.)</strong>
                      </span>
																			   
Add this:	
	  <!-- add commission -->
              <tr class="conf_body">
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> <strong>Payment costs</strong> </span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> {commission} </span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr class="conf_body">
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> <strong>Payment discount</strong> </span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> {discount} </span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <!--end commission -->
			  
3. Save

1. Open file:			 \themes\classic\mails\it\order_conf.txt
(if the theme does not have mail folder, then open  mail folder in main folder of site)			 
2. After line:
			      {total_wrapping}			  
Add this:			  
			      PAYMENT COMMISSION

            {commission}

            PAYMENT DISCOUNT

            {discount}  
3. Save			  
			 
			  
1. Open file:	 \themes\classic\modules\ps_emailalerts\mails\it\new_order.html
(if the theme does not have mail folder, then open  mail folder in main folder of site)	  
2. After line:			  
			   <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> {total_wrapping} </span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
              </tr>			  
Add this:			  
			   <!-- add commission-->
              <tr class="conf_body">
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> <strong>Payment costs</strong></span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> {commission} </span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr class="conf_body">
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;"> <strong>Payment discount</strong></span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
                <td bgcolor="#f8f8f8" colspan="4" style="border: 1px solid #D6D4D4; color: #333; padding: 7px 0;">
                  <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                      <td align="right" style="color: #333; padding: 0;"><span size="2" face="Open-sans, sans-serif"
                                                                               color="#555454"
                                                                               style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;">{discount}</span>
                      </td>
                      <td width="10" style="color: #333; padding: 0;">&nbsp;</td>
                    </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
         <!--end commission-->
3. Save		
			  
1. Open file: \themes\classic\modules\ps_emailalerts\mails\it\new_order.txt	
(if the theme does not have mail folder, then open  mail folder in main folder of site)	  
2. After line:			  
			       {total_wrapping}			  
Add this:			  
             PAYMENT COMMISSION

             {commission}

             PAYMENT DISCOUNT

             {discount}
3. Save					 
			 
----------------------------------------------------------

If you have installed the module "One Page Checkout PrestaShop"
1. Find file /modules/onepagecheckoutps/onepagecheckoutps.php
2.  Add row before line:
    if ($set_id_customer_opc) {
        if ($this->isModuleActive('taxcloud')) {
        
    This:
    include _PS_MODULE_DIR_.'custompaymentmethod/fixonepagecheckout.php';


- For Prestashop version 1.7

1. Open file: themes/your_theme/templates/checkout/_partials/order-confirmation-table.tpl
2. After line: {if $subtotals.tax.label !== null}
                   <tr class="sub">
                      <td>{$subtotals.tax.label}</td>
                      <td>{$subtotals.tax.value}</td>
                   </tr>
               {/if}
Add this:
{hook h='displayOrderConfirmationCommission' order=$order.details}

- Commission in PDF file

1. Open file: /pdf/invoice.total-tab.tpl
2. Before line: <tr class="bold big">
Add this: 
{if strpos($order->module, 'custompaymentmethod_') !== false}
	{hook h="displayCommissionForPDF" tab='total' order=$order}
	{$footer.total_paid_tax_incl = $order->total_paid_tax_incl}
	{/if}
3. Open file: /pdf/invoice.tax-tab.tpl
4. Before line: {if !$has_line}
Add this: {hook h="displayCommissionForPDF" tab='tax' order=$order}