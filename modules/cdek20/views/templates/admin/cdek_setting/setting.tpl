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
{$documentation_links|no_escape}
<div class="panel bootstrap" id="fieldset_cdek_order">
	<div class="panel-heading"><i class="icon-info-sign"></i>
		{l s='Settings' mod='cdek20'}
	</div>
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#GeneralsForm" role="tab" data-toggle="tab">{l s='General' mod='cdek20'}</a></li>
		<li><a href="#LocationForm" role="tab" data-toggle="tab">{l s='Location' mod='cdek20'}</a></li>
		<li><a href="#TariffsForm" role="tab" data-toggle="tab">{l s='Tariffs' mod='cdek20'}</a></li>
		<li><a href="#CarriersForm" role="tab" data-toggle="tab">{l s='Carriers' mod='cdek20'}</a></li>
		<li><a href="#MetricsForm" role="tab" data-toggle="tab">{l s='Metrics' mod='cdek20'}</a></li>
		<li><a href="#CalculatorForm" role="tab" data-toggle="tab">{l s='Calculator' mod='cdek20'}</a></li>
		<li><a href="#StatusesForm" role="tab" data-toggle="tab">{l s='Statuses' mod='cdek20'}</a></li>
		<li><a href="#LogForm" role="tab" data-toggle="tab">{l s='Logging' mod='cdek20'}</a></li>
		<li><a href="{$link_on_tab_module|escape:'quotes':'UTF-8'}">{l s='Documentation' mod='cdek20'}</a></li>
		<li><a href="#" id="seosa_manager_btn">{l s='Our modules' mod='cdek20'}</a></li>
	</ul>
	<div class="tab-content form-horizontal">
		<div role="tabpanel" class="tab-pane active" id="GeneralsForm">
			{$general_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="LocationForm">
			{$location_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="TariffsForm">
			{$tariffs_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="CarriersForm">
			{$carriers_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="MetricsForm">
			{$metrics_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="CalculatorForm">
			{$calculator_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="StatusesForm">
			{$statuses_form|no_escape}
		</div>
		<div role="tabpanel" class="tab-pane" id="LogForm">
			{$log_form|no_escape}
		</div>
	</div>
</div>
<script>
	{assign var=tabs value=['CarriersForm', 'LocationForm', 'MetricsForm', 'CalculatorForm', 'StatusesForm', 'LogForm']}
	{foreach $tabs as $tab}
		{if Tools::getValue('save'|cat:$tab)}
			$('[href="#{$tab|no_escape}"]').trigger('click');
		{/if}
	{/foreach}
</script>

<script type="text/javascript">
    var copy_title = "{l s='Test PVZ' mod='cdek20'}";
    var copy_ok = "{l s='Copy' mod='cdek20'}";
    var copy_cancel = "{l s='Cancel' mod='cdek20'}";
</script>

{literal}
<script>
	$(document).ready(function () {

		$('#load_city_button').on('click', function () {
			$('#upload_progress').text('');
			if ($('[name="country_for_upload"]').val() == 0) {
				return false;
			}
			var button = $(this);
			button.attr('disabled', 'disabled');
			button.find('.fa-spin').show();
			requestLoadCity(0);
		});

		function requestLoadCity(page) {
			var size = 1000;
			$.ajax({
				data: {
					ajax: 1,
					action: 'cities_load',
					page: page,
					size: size,
					country_code: $('[name="country_for_upload"]').val()
				},
				dataType: 'json',
				success: function (r) {
					if (r.page) {
						$('#upload_progress').text((page + 1) * size);
						requestLoadCity(r.page);
					} else {
						$.fancybox(r.message);
						$('#load_city_button').removeAttr('disabled');
						$('#load_city_button').find('.fa-spin').hide();
						$('#upload_progress').text('');
					}
				},
				error:function (jqXHR, textStatus, errorThrown) {
					$.fancybox(errorThrown);
					$('#load_city_button').removeAttr('disabled');
					$('#load_city_button').find('.fa-spin').hide();
					$('#upload_progress').text('');
				}
			});
		}

		$('#load_pvz_button').on('click', function (e) {
			e.preventDefault();
			var self = $(this);
			self.attr('disabled', 'disabled');
			self.find('.fa-spin').show();
			$.ajax({
				type: "POST",
				headers: {"cache-control": "no-cache"},
				data: { ajax: 1, action: 'update_pvz_list'},
				success: function (r) {
					self.closest('.form-group').find('.control-label').text(r);
					self.removeAttr('disabled');
					self.find('.fa-spin').hide();
				}
			});
		});

        function copytext(el) {
            var $tmp = $("<textarea>");
            $("body").append($tmp);
            $tmp.val(el).select();
            document.execCommand("copy");
            $tmp.remove();
        }

        $('#test_pvz_start').on('click', function (e) {
			e.preventDefault();
			var self = $(this);
			self.find('.fa-spin').show();
			self.attr('disabled', 'disabled');
			var data = {ajax: 1, action: 'test_pvz'}
			data.field_name = $('#test_pvz_field_name').val();
			data.value = $('#test_pvz_value').val();
			$.ajax({
				type: "POST",
				headers: {"cache-control": "no-cache"},
				dataType: 'json',
				data: data,
				success: function (r) {
                    $.alerts.okButton = copy_ok;
                    $.alerts.cancelButton = copy_cancel;
                    jConfirm(r, copy_title, function(confirm){
                        if (confirm == true) {
                            copytext(r);
                        }
                    });
					self.removeAttr('disabled');
					self.find('.fa-spin').hide();
				}
			});
		});

		$(".tariff-column-actions .btn").on("click", function (e) {
			e.preventDefault();
			var self = $(this);
			var id = $(this).data('id');
			$.ajax({
				type: "POST",
				headers: {"cache-control": "no-cache"},
				data: {id: id, ajax: 1, action: 'tariff_active'},
				success: function (r) {
					self.find('i').text(r);

                    if (self.hasClass('btn-success')) {
                        self.removeClass('btn-success');
                        self.addClass('btn-danger');
                    } else {
                        self.addClass('btn-success');
                        self.removeClass('btn-danger');
                    }
				}
			});
		});

		$(".tariff-buttons-update .btn").on("click", function () {
			var e = $(this), t = e.closest(".tariff-item"), n = void 0;
			return n = e.data("way") ? t.next(".tariff-item") : t.prev(".tariff-item"), 0 !== n.length && (e.data("way") ? t.insertAfter(n) : t.insertBefore(n), updatePositions({
				hookId: e.data("hook-id"),
				tariffId: e.data("tariff-id"),
				way: e.data("way"),
				positions: []
			}, e.closest("ul")), !1)
		});

		function updatePositions(o, e) {
			var positions = [];
			$.each(e.children(), function (e, t) {
				positions.push($(t).attr("id"))
			}), $.ajax({
				type: "POST",
				headers: {"cache-control": "no-cache"},
				data: {positions: positions, ajax: 1, action: 'tariff_position'},
				success: function () {
					var o = 0;
					$.each(e.children(), function (e, t) {
						console.log($(t).find(".index-position")), $(t).find(".index-position").html(++o)
					}), window.showSuccessMessage(window.update_success_msg);
				}
			})
		}

		function i(o, e) {
			var t = [], n = !0, i = !1, l = void 0;
			try {
				for (var s, a = o[Symbol.iterator](); !(n = (s = a.next()).done) && (t.push(s.value), !e || t.length !== e); n = !0) ;
			} catch (o) {
				i = !0, l = o
			} finally {
				try {
					!n && a.return && a.return()
				} finally {
					if (i) throw l
				}
			}
			return t
		}

		$(".sortable").sortable({
			forcePlaceholderSize: true
		}).bind(
				'sortupdate', function (e, t) {
					var n = t.item.attr("id").split("_"), l = i(n, 2), a = l[0], r = l[1], c = {
						hookId: a,
						moduleId: r,
						way: $(this).data("previous-index") < t.item.index() ? 1 : 0,
						positions: []
					};
					updatePositions(c, $(e.target))
				});

		$('#pvz_warehouse').on('click', function () {
			var error = '';
			var postal_code = $('[name="postal_code"]').val();
			var country_warehouse = $('[name="country_warehouse"]').val();
			if (!postal_code) {
				error += cdek.trans('Fill out postal code field');
			}
			if (!postal_code && !country_warehouse) {
				error += ', ';
			}
			if (!country_warehouse) {
				error += cdek.trans('Choose the country');
			}
			if (error) {
				alert(error);
				return false;
			}
			// $.fancybox($(document.getElementById('cdek_widget_pickup').innerHTML.replace('--POSTCODE--', postal_code)));
			window.cdek.openWidgetSetting({postcode: postal_code, type: 'pickup'});
		});
	});
</script>
{/literal}