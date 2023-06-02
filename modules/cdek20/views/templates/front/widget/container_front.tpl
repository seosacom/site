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

{foreach from=$cdek_widgets item='cdek_widget'}
	<script type="text/html" id="cdek_widget_temp_{$cdek_widget['delivery_option']|no_escape}">
		<div class="row cdek_widget form-group" style="display:none;">

			<div class="CDEK-widget__preloader">
				<div class="CDEK-widget__preloader-truck">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 440.34302 315.71001">
						<g>
							<path class="path1"
								  d="M416.43,188.455q-1.014-1.314-1.95-2.542c-7.762-10.228-14.037-21.74-19.573-31.897-5.428-9.959-10.555-19.366-16.153-25.871-12.489-14.513-24.24-21.567-35.925-21.567H285.128c-0.055.001-5.567,0.068-12.201,0.068h-9.409a14.72864,14.72864,0,0,0-14.262,11.104l-0.078.305V245.456l0.014,0.262a4.86644,4.86644,0,0,1-1.289,3.472c-1.587,1.734-4.634,2.65-8.812,2.65H14.345C6.435,251.839,0,257.893,0,265.334v46.388c0,7.441,6.435,13.495,14.345,13.495h49.36a57.8909,57.8909,0,0,0,115.335,0h82.61a57.89089,57.89089,0,0,0,115.335,0H414.53a25.8416,25.8416,0,0,0,25.813-25.811v-44.29C440.344,219.47,425.953,200.805,416.43,188.455ZM340.907,320.132a21.5865,21.5865,0,1,1-21.59-21.584A21.61074,21.61074,0,0,1,340.907,320.132ZM390.551,207.76c-0.451.745-1.739,1.066-3.695,0.941l-99.197-.005V127.782h42.886c11.539,0,19.716,5.023,28.224,17.337,5.658,8.19,20.639,33.977,21.403,35.293,0.532,1.027,1.079,2.071,1.631,3.125C386.125,191.798,392.658,204.279,390.551,207.76ZM121.372,298.548a21.58351,21.58351,0,1,1-21.583,21.584A21.6116,21.6116,0,0,1,121.372,298.548Z"
								  transform="translate(0 -62.31697)"></path>
							<path class="path2"
								  d="M30.234,231.317h68a12.51354,12.51354,0,0,0,12.5-12.5v-50a12.51354,12.51354,0,0,0-12.5-12.5h-68a12.51354,12.51354,0,0,0-12.5,12.5v50A12.51418,12.51418,0,0,0,30.234,231.317Z"
								  transform="translate(0 -62.31697)"></path>
							<path class="path3"
								  d="M143.234,231.317h68a12.51354,12.51354,0,0,0,12.5-12.5v-50a12.51354,12.51354,0,0,0-12.5-12.5h-68a12.51354,12.51354,0,0,0-12.5,12.5v50A12.51418,12.51418,0,0,0,143.234,231.317Z"
								  transform="translate(0 -62.31697)"></path>
							<path class="path4"
								  d="M30.234,137.317h68a12.51354,12.51354,0,0,0,12.5-12.5v-50a12.51355,12.51355,0,0,0-12.5-12.5h-68a12.51355,12.51355,0,0,0-12.5,12.5v50A12.51418,12.51418,0,0,0,30.234,137.317Z"
								  transform="translate(0 -62.31697)"></path>
							<path class="path5"
								  d="M143.234,137.317h68a12.51354,12.51354,0,0,0,12.5-12.5v-50a12.51354,12.51354,0,0,0-12.5-12.5h-68a12.51354,12.51354,0,0,0-12.5,12.5v50A12.51418,12.51418,0,0,0,143.234,137.317Z"
								  transform="translate(0 -62.31697)"></path>
						</g>
					</svg>
					<div class="CDEK-widget__preloader-truck__grass"></div>
					<div class="CDEK-widget__preloader-truck__road"></div>
				</div>
			</div>

			{include file="./iframe_front.tpl" cdek_widget=$cdek_widget id_order=$id_order}
		</div>
	</script>
{/foreach}
