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

<tr>
    <td>{$row.id_cdek_logger|escape:'quotes':'UTF-8'}</td>
    <td>{$row.method|escape:'quotes':'UTF-8'}</td>
    <td>{$row.message|escape:'quotes':'UTF-8'}</td>
    <td>
        <label class="data_api" for="show_request_{$row.id_cdek_logger|escape:'quotes':'UTF-8'}">
                                            <span class="btn btn-default">
                                                <i class="icon-list"></i>
                                            </span>
            <input type="checkbox" id="show_request_{$row.id_cdek_logger|escape:'quotes':'UTF-8'}">
            <section class="request_code">
                <code class="json">
                    <span>
                        {$row.request|escape:'htmlall':'UTF-8'}
                    </span>
                </code>
                <button data-copy-field class="btn btn-default" type="button">
                    <i class="icon-copy"></i>
                    {l s='Copy' mod='cdek20'}
                </button>
            </section>
        </label>
    </td>
    <td>
        <label class="data_api" for="show_response_{$row.id_cdek_logger|escape:'quotes':'UTF-8'}">
                                            <span type="button" class="btn btn-default">
                                                <i class="icon-list"></i>
                                            </span>
            <input type="checkbox" id="show_response_{$row.id_cdek_logger|escape:'quotes':'UTF-8'}">
            <section class="request_code">
                <code class="json">
                    {$row.response|escape:'html':'UTF-8'}
                </code>
                <button data-copy-field class="btn btn-default float-right" type="button">
                    <i class="icon-copy"></i>
                    {l s='Copy' mod='cdek20'}
                </button>
            </section>
        </label>
    </td>
    <td>{date('H:i:s d-m-Y', strtotime($row.date_add))|escape:'quotes':'UTF-8'}</td>
</tr>