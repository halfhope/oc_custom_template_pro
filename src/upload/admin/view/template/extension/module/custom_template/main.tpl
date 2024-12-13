<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success add" data-route=""><i class="fa fa-plus"></i></button>
				<button type="submit" form="fmcc" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="mmct-modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn close" data-dismiss="modal" aria-label="<?php echo $text_form_close ?>" data-route=""><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><?php echo $text_form_title ?></h4>
			</div>
			<div class="modal-body">
			<form action="" method="post" enctype="multipart/form-data" id="fmct" class="form-horizontal">
				<center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></center> 
			</form>
			</div>
			<div class="modal-footer stripped-footer sticky-footer">
				<button type="button" class="btn btn-sm btn-secondary clear"><?php echo $text_form_clear ?></button>
				<button type="button" class="btn btn-sm btn-primary save"><?php echo $text_form_save ?></button>
			</div>
		</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="mmcr-modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn close" data-dismiss="modal" aria-label="<?php echo $text_form_close ?>" data-route=""><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><?php echo $text_form_layout_title ?></h4>
			</div>
			<div class="modal-body">
			<form action="" method="post" enctype="multipart/form-data" id="fmcr" class="form-horizontal">
			
				<div class="row form-group">
					<label for="route" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_form_template_help; ?>"><?php echo $entry_form_template; ?></span></label>
					<div class="col-sm-10">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $text_template_dropdown; ?> <span class="caret"></span></button>
								<ul class="dropdown-menu" id="known-routes">
									<li><a href="" data-route="common/home"><?php echo $route_preset_1 ?></a></li>
									<li><a href="" data-route="product/category"><?php echo $route_preset_2 ?></a></li>
									<li><a href="" data-route="product/special"><?php echo $route_preset_3 ?></a></li>
									<li><a href="" data-route="product/search"><?php echo $route_preset_4 ?></a></li>
									<li><a href="" data-route="product/product"><?php echo $route_preset_5 ?></a></li>
									<li><a href="" data-route="product/manufacturer"><?php echo $route_preset_6 ?></a></li>
									<li><a href="" data-route="product/manufacturer_info"><?php echo $route_preset_7 ?></a></li>
									<li><a href="" data-route="information/information"><?php echo $route_preset_8 ?></a></li>
								</ul>
							</div>
							<input type="text" class="form-control" name="route" id="route" placeholder="<?php echo $text_template_placeholder; ?>">
						</div>
					</div>
				</div>

			</form>
			</div>
			<div class="modal-footer stripped-footer">
				<button type="button" class="btn btn-secondary cancel"><?php echo $button_cancel ?></button>
				<button type="button" class="btn btn-primary add"><?php echo $button_add ?></button>
			</div>
		</div>
		</div>
	</div>

	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3><h3 class="panel-title pull-right">v<?php echo $version ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="fmcc" class="form-horizontal">
					<?php $module_row = 1; ?>
					<?php foreach ($custom_template as $group_id => $group_data): ?>
						<div class="ct-group" data-route="<?php echo $group_id ?>">
							<div class="ct-group-header">
								<h4><svg aria-hidden="true" class="octicon" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path d="M10.86 7c-.45-1.72-2-3-3.86-3-1.86 0-3.41 1.28-3.86 3H0v2h3.14c.45 1.72 2 3 3.86 3 1.86 0 3.41-1.28 3.86-3H14V7h-3.14zM7 10.2c-1.22 0-2.2-.98-2.2-2.2 0-1.22.98-2.2 2.2-2.2 1.22 0 2.2.98 2.2 2.2 0 1.22-.98 2.2-2.2 2.2z"></path></svg>
								&nbsp;&nbsp;<?php echo $group_id ?>
								<?php if (!empty($group_data['mods'])): ?>
								&nbsp;<a tabindex="0" data-toggle="popover" title="<?php echo $group_data['mods']['title']; ?>" data-trigger="focus" data-html="true" data-content="<?php echo implode('<br>', $group_data['mods']['content']); ?>" class="label label-primary"><i class="fa fa-info"></i></a>
								<?php endif ?>
								</h4>
								<div class="pull-right">
									<button data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success btn-sm add" data-route="<?php echo $group_id ?>"><i class="fa fa-plus"></i></button>
								</div>
							</div>
							<table class="ct-group-content">
								<tbody>
								<?php $sort_order = 1 ?>
								<?php foreach ($group_data['items'] as $item_id => $item_data): ?>
									<tr class="ct-group-item" id="ct-row-<?php echo $module_row ?>" data-module-row="<?php echo $module_row ?>">
										<td width="auto" class="description">
										<span class="btn-sm sort-handle"><i class="fa fa-arrows-v"></i></span>
										<?php echo $item_data['description'] ?>
										</td>
										<td width="80">
											<button data-toggle="tooltip" title="" class="btn btn-primary btn-sm edit" data-original-title="<?php echo $text_edit ?>" data-module-row="<?php echo $module_row ?>"><i class="fa fa-pencil"></i></button>
											<button data-toggle="tooltip" title="" class="btn btn-danger btn-sm remove" data-original-title="<?php echo $button_remove ?>"><i class="fa fa-trash"></i></button>
											<input type="hidden" name="custom_template[<?php echo $module_row ?>][value]" value="<?php echo $item_data['value'] ?>">
											<input type="hidden" name="custom_template[<?php echo $module_row ?>][route]" value="<?php echo $group_id ?>">
											<input type="hidden" name="custom_template[<?php echo $module_row ?>][sort_order]" value="<?php echo $sort_order ?>">
										</td>
									</tr>
								<?php $sort_order++; ?>
								<?php $module_row++; ?>
								<?php endforeach ?>
								</tbody>
							</table>
						</div>
					<?php endforeach ?>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	var ct_data = <?php echo $ct_data ?>;
	var module_row = <?php echo $module_row ?>;
</script>
<?php echo $footer; ?>