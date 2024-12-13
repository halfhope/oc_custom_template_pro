// ajaxQueue
(function($) {
	var b = $({});
	$.ajaxQueue = function(c) {
		var d = c.complete;
		b.queue(function(b) {
			c.complete = function() {
				d && d.apply(this, arguments);
				b()
			};
			$.ajax(c)
		})
	}
})(jQuery);

// Custom templates
var getFoundMods = function(route) {
	$.ajaxQueue({
		url: ct_data.links.find_mods,
		type: 'post',
		dataType: 'json',
		data: {
			route: route
		},
		success: function(response) {
			if (response.title !== undefined) {
				var $ct_group = addCtGroup(route);
				$ct_group.find('a[data-toggle="popover"]').popover({
					title: response.title,
					content: response.content.join("<br>"),
					trigger: 'focus',
					html: true
				});
				console.log(response.content);
			} else {
				var $ct_group = addCtGroup(route);
				$ct_group.find('a[data-toggle="popover"]').remove();
			}
		},
		error: function(err) {
			alert(err.responseText);
		}
	});
}

var getDescription = function(val, overwrite) {
	var module_row = $(val).data('module-row');
	var sd = $(val).find('input[type=hidden][name*=value]').val();
	if (sd || overwrite) {
		$.ajaxQueue({
			url: ct_data.links.explain,
			type: 'post',
			dataType: 'json',
			data: (sd.length > 0) ? sd : {
				module_row: module_row
			},
			success: function(response) {
				$('#ct-row-' + module_row).find('td.description').html(response.description);
			},
			error: function(err) {
				alert(err.responseText);
			}
		});
	}
}
var editModuleLayout = function(module_row) {
	$('#mmct-modal').modal();
	$.ajax({
		url: ct_data.links.form,
		type: 'POST',
		dataType: 'html',
		data: {
			module_row: module_row,
			route: $('#ct-row-' + module_row).find('input[type=hidden][name*=route]').val(),
			custom_template: $('#ct-row-' + module_row).find('input[type=hidden][name*=value]').val()
		}
	}).success(function(response) {
		$('#fmct').html(response);
		$('#fmct .form-group.more_hidden').detach().appendTo('#fmct');
		$('#mmct-modal #type').on('change load', function(event) {
			switch (parseInt($(this).val())) {
				case 1:
					$('.sproducts').show();
					$('.scategories, .sinformations, .smanufacturers').hide();
					break;
				case 2:
					$('.scategories').show();
					$('.sproducts, .sinformations, .smanufacturers').hide();
					break;
				case 3:
					$('.smanufacturers').show();
					$('.sproducts, .scategories, .sinformations').hide();
					break;
				case 4:
					$('.sinformations').show();
					$('.sproducts, .scategories, .smanufacturers').hide();
					break;
			}
		});
		$('#fmct #type').trigger('change');
		$('#fmct input[name=\'products_search\']').autocomplete({
			source: function(request, response) {
				$.ajax({
					url: ct_data.links.product_autocomplete + '&term=' + encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json.results, function(item) {
							return {
								label: item['text'],
								value: item['id']
							}
						}));
					}
				});
			},
			select: function(item) {
				$('#fmct input[name=\'products_search\']').val('');
				$('#fmct #product_id' + item['value']).remove();
				$('#fmct #product_id').append('<div id="product_id' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_id[]" value="' + item['value'] + '" /></div>');
				$('#fmct #product_id #product_id' + item['value']).click()
			}
		});
		$('#fmct #product_id').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
		$('#fmct input[name=template]').on('keydown', function(event) {
			if (event.keyCode == 10 || event.keyCode == 13) {
				$('#mmct-modal button.save').trigger('click');
			}
		});
	});
}

var addCtGroup = function(route) {
	var $ct_group = $('#fmcc .ct-group[data-route="' + route + '"]');
	if ($ct_group.length) {
		return $ct_group;
	} else {
		html = '';
		html += '<div class="ct-group" data-route="' + route + '">';
		html += '	<div class="ct-group-header">';
		html += '		<h4><svg aria-hidden="true" class="octicon" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path d="M10.86 7c-.45-1.72-2-3-3.86-3-1.86 0-3.41 1.28-3.86 3H0v2h3.14c.45 1.72 2 3 3.86 3 1.86 0 3.41-1.28 3.86-3H14V7h-3.14zM7 10.2c-1.22 0-2.2-.98-2.2-2.2 0-1.22.98-2.2 2.2-2.2 1.22 0 2.2.98 2.2 2.2 0 1.22-.98 2.2-2.2 2.2z"></path></svg>';
		html += '		&nbsp;&nbsp;' + route;
		html += '		&nbsp;<a tabindex="0" data-toggle="popover" title="" data-trigger="focus" data-html="true" data-content="" class="label label-primary"><i class="fa fa-info"></i></a>';
		html += '		</h4>';
		html += '		<div class="pull-right">';
		html += '			<button data-toggle="tooltip" title="' + ct_data.lang.button_add + '" class="btn btn-success add" data-route="' + route + '"><i class="fa fa-plus"></i></button>';
		html += '		</div>';
		html += '	</div>';
		html += '	<table class="ct-group-content">';
		html += '		<tbody>';
		html += '		</tbody>';
		html += '	</table>';
		html += '</div>';
		$('#fmcc').append(html);

		$ct_group = $('#fmcc .ct-group[data-route="' + route + '"]');
		$ct_group.find('button.add[data-route]').on('click', buttonAddTrigger);

		getFoundMods(route);

		return $ct_group;
	}
}

var addCtGroupItem = function($ct_group) {
	var $container = $ct_group.find('.ct-group-content>tbody');
	var route = $ct_group.data('route');
	html = '';
	html += '<tr class="ct-group-item" id="ct-row-' + module_row + '" data-module-row="' + module_row + '">';
	html += '	<td width="auto" class="description">';
	html += '		<span class="label label-warning">' + ct_data.lang.text_output_notset + '</span>';
	html += '	</td>';
	html += '	<td width="10">';
	html += '		<button data-toggle="tooltip" title="" class="btn btn-primary edit" data-original-title="' + ct_data.lang.text_edit + '" data-module-row="' + module_row + '"><i class="fa fa-pencil"></i></button>';
	html += '		<input type="hidden" name="custom_template[' + module_row + '][value]" value="">';
	html += '		<input type="hidden" name="custom_template[' + module_row + '][route]" value="' + route + '">';
	html += '	</td>';
	html += '	<td width="10">';
	html += '		<button data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="' + ct_data.lang.button_remove + '"><i class="fa fa-trash"></i></button>';
	html += '	</td>';
	html += '</tr>';
	$container.append(html);

	var $ct_group_item = $container.find('.ct-group-item').last();

	$ct_group_item.find('button.remove').on('click', buttonRemoveTrigger);
	$ct_group_item.find('button.edit').on('click', buttonEditTrigger);
	$ct_group_item.on('dblclick', dblClickTrigger);

	module_row++;

	return $ct_group_item;
}

var modalRoute = function() {
	$('#mmcr-modal').modal('show');
}

var buttonAddTrigger = function(event) {
	event.preventDefault();
	var route = $(this).data('route');
	if (route == '') {
		modalRoute();
	} else {
		var $ct_group = addCtGroup(route);
		var $ct_group_item = addCtGroupItem($ct_group);
		editModuleLayout($ct_group_item.data('module-row'));
	}
}

var buttonRemoveTrigger = function(event) {
	event.preventDefault();
	$(this).parents('.ct-group-item').remove();
}

var buttonEditTrigger = function(event) {
	event.preventDefault();
	var module_row = $(this).data('module-row');
	editModuleLayout(module_row);
}
var dblClickTrigger = function(event) {
	event.preventDefault();
	var module_row = $(this).data('module-row');
	editModuleLayout(module_row);
}

$(document).ready(function() {
	// init popovers
	$('.ct-group a[data-toggle=popover]').popover();

	// modal scrolling fix
	$('body').on('hidden.bs.modal', function() {
		if ($('.modal.in').length > 0) {
			$('body').addClass('modal-open');
		}
	});

	// edit item on dbl-click
	$('.ct-group .ct-group-item').on('dblclick', dblClickTrigger);

	//button triggers
	$('button.add[data-route]').on('click', buttonAddTrigger);
	$('button.remove').on('click', buttonRemoveTrigger);
	$('button.edit').on('click', buttonEditTrigger);

	// modal template
	$('#mmct-modal').on('hidden.bs.modal', function(e) {
		$('#fmct').html('<center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></center>');
	});

	$('#mmct-modal button.save').on('click', function(event) {
		var $input_template = $('#fmct input[name=template]');
		if ($input_template.val() == '') {
			$input_template.parents('.form-group').addClass('has-error');
			$input_template.focus();
			return;
		} else {
			$input_template.parents('.form-group').removeClass('has-error');
		}

		var $module_row_node = $('#fmct input[name=module_row]');
		var module_row = $module_row_node.val();
		$('#fmct input[name=module_row]').remove();
		$.each($('#fmct .auto_disabled'), function(index, val) {
			if ($(val).val() == 0) {
				$(val).parent('.input-group').find('select,input').remove();
			}
		});
		var serialized = $('#fmct').serialize();
		$('#ct-row-' + module_row).append($module_row_node);
		$('#mmct-modal').modal('hide');
		$('#ct-row-' + module_row).find('input[type=hidden][name*=value]').val(serialized);
		getDescription($('#ct-row-' + module_row), true);
	});
	$('#mmct-modal button.clear').on('click', function(event) {
		var module_row = $('#fmct>input[name=module_row]').val();
		$('#ct-row-' + module_row).find('input[type=hidden][name*=value]').val('');
		$('#mmct-modal').modal('hide');
		getDescription($('#ct-row-' + module_row), true);
	});

	// modal route
	$('#mmcr-modal #known-routes a').on('click', function(event) {
		event.preventDefault();
		var route = $(this).data('route');
		$('#mmcr-modal #route').val(route);
	});
	$('#mmcr-modal .modal-footer button.add').on('click', function(event) {
		event.preventDefault();
		var $input_route = $('#mmcr-modal #route');
		var route = $input_route.val();
		if (route == '') {
			$input_route.parents('.form-group').addClass('has-error');
			$input_route.focus();
			return;
		} else {
			$input_route.parents('.form-group').removeClass('has-error');
			var $ct_group = addCtGroup(route);
			var $ct_group_item = addCtGroupItem($ct_group);
			editModuleLayout($ct_group_item.data('module-row'));
		}

		$('#mmcr-modal').modal('hide');
	});
	$('#mmcr-modal .modal-footer .cancel').on('click', function(event) {
		event.preventDefault();
		$('#mmcr-modal').modal('hide');
		$('#mmcr-modal #route').val('');
	});
	$('#mmcr-modal #fmcr input[name=route]').on('keydown', function(event) {
		if (event.keyCode == 10 || event.keyCode == 13) {
			event.preventDefault();
			$('#mmcr-modal button.add').trigger('click');
		}
	});
	$('#mmcr-modal').on('hidden.bs.modal', function() {
		var $input_route = $('#mmcr-modal #route');
		$input_route.parents('.form-group').removeClass('has-error');
		$input_route.val('');
	});

});