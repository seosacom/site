<?php
/**
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
 * @author    SeoSA <885588@bk.ru>
 * @copyright 2012-2023 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if ($cpm = Module::getInstanceByName('custompaymentmethod')) {
    $output = $output ? $output : null; // validation
    if (is_array($output)) {
        foreach ($output as $item_pm => $value_pm) {
            if ($value_pm['name'] == 'custompaymentmethod') {
                unset($output[$item_pm]);
                break;
            }
        }
    }
    require_once dirname(__FILE__) . '/../' . $cpm->name . '/classes/tools/config.php';
    $cpm_payments = CustomPayment::getCustomPaymentMethodsCollection(Context::getContext()->language->id, true);
    $output_cpm = [];
    foreach ($cpm_payments as $key => $cpm_payment) {
        $output_cpm[] = [
            'id' => $cpm->id,
            'title' => '',
            'description' => '',
            'name' => $cpm_payment->name,
            'author' => '',
            'version' => '',
            'html' => '',
            'url_image' => _MODULE_DIR_ . '/' . $cpm->name . '/logos/' . $cpm_payment->logo,
            'additional' => [],
            'url_payment' => Context::getContext()->link->getModuleLink(
                Module::getInstanceByName('custompaymentmethod')->name,
                'payment',
                [
                    'pm' => $cpm->name,
                    'id_cpm' => $cpm_payment->id,
                ]
            ),
            'modules_external_image' => [],
            'force_display' => 0,
            'title_opc' => $cpm_payment->name,
            'description_opc' => $cpm_payment->description,
        ];
    }
    $output = array_merge($output, $output_cpm);
}
