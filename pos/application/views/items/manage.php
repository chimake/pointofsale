<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
	var table_columns = ["","item_id","item_number",'name','category','cost_price','unit_price','quantity','',''];
	enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir);?>);
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
    enable_cleanup(<?php echo json_encode(lang("items_confirm_cleanup"));?>);
	
    $('#generate_barcodes').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
    		return false;
    	}

    	$(this).attr('href','<?php echo site_url("items/generate_barcodes");?>/'+selected.join('~'));
    });

	$('#generate_barcode_labels').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
    		return false;
    	}

    	$(this).attr('href','<?php echo site_url("items/generate_barcode_labels");?>/'+selected.join('~'));
    });
});

function post_bulk_form_submit(response)
{
	window.location.reload();
}

function select_inv()
{	
	if (confirm(<?php echo json_encode(lang('items_select_all_message')); ?>))
	{
		$('#select_inventory').val(1);
		$('#selectall').css('display','none');
		$('#selectnone').css('display','block');
		$.post('<?php echo site_url("items/select_inventory");?>', {select_inventory: $('#select_inventory').val()});
	}
		
}
function select_inv_none()
{
	$('#select_inventory').val(0);
	$('#selectnone').css('display','none');
	$('#selectall').css('display','block');
	$.post('<?php echo site_url("items/clear_select_inventory");?>', {select_inventory: $('#select_inventory').val()});	
}
select_inv_none();
</script>
<div id="content-header" class="hidden-print">
	<h1 ><i class="icon fa fa-table"></i> <?php echo lang('module_'.$controller_name); ?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

<?php if($pagination) {  ?>
	<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
		<?php echo $pagination;?>
	</div>
<?php }  ?>
<div class="pull-right">
		<div class="row">
				<div class="col-md-12 center" style="text-align: center;">					
				<div class="btn-group  ">
				<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
					
						<?php echo 
						anchor("$controller_name/view/-1/",
						'<i class="fa fa-pencil   hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang($controller_name.'_new').'"></i> <span class="visible-lg">'.lang($controller_name.'_new').'</span>',
						array('class'=>'btn btn-medium btn-primary', 
							'title'=>lang($controller_name.'_new')));
						?>

					<?php echo
						anchor("$controller_name/bulk_edit/",
						'<i class="fa fa-edit   hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('items_bulk_edit').'"></i><span class="visible-lg">'.lang("items_bulk_edit").'</span>',
						array('id'=>'bulk_edit','data-toggle'=>'modal','data-target'=>'#myModal',
							'class' => 'btn hidden-xs btn-medium btn-primary disabled',
							'title'=>lang('items_edit_multiple_items'))); 
					?>
										
				<?php } ?>

				<?php echo 
					anchor("$controller_name/generate_barcode_labels",
					'<i class="fa fa-barcode   hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_barcode_labels').'"></i><span class="visible-lg">'.lang("common_barcode_labels").'</span>',
					array('id'=>'generate_barcode_labels', 
						'class' => 'btn hidden-xs btn-medium btn-primary hidden-xs   disabled',
						'target' =>'_blank',
						'title'=>lang('common_barcode_labels'))); 
				?>
				<?php echo 
					anchor("$controller_name/generate_barcodes",
					'<i class="fa fa-barcode hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_barcode_sheet').'"></i><span class="visible-lg">'.lang("common_barcode_sheet").'</span>',
					array('id'=>'generate_barcodes', 
						'class' => 'btn hidden-xs btn-medium btn-primary  hidden-xs   disabled',
						'target' =>'_blank',
						'title'=>lang('common_barcode_sheet'))); 
				?>
				<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				

					<?php echo anchor("$controller_name/excel_import/",
					'<i class="fa fa-upload   hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_excel_import').'"></i><span class="visible-lg">'.lang("common_excel_import").'</span>',
						array('class'=>'btn hidden-xs btn-medium btn-primary ',
							'title'=>lang('common_excel_import')));
					?>
					<?php echo anchor("$controller_name/excel_export/",
					'<i class="fa fa-download   hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_excel_export').'"></i><span class="visible-lg">'.lang("common_excel_export").'</span>',
						array('class'=>'btn hidden-xs btn-medium btn-primary',
							'title'=>lang('common_excel_export')));
					?>
				<?php }?>
				<?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				

					<?php echo 
						anchor("$controller_name/delete",
						'<i class="fa fa-trash-o hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_delete').'"></i><span class="visible-lg">'.lang("common_delete").'</span>',
						array('id'=>'delete', 
							'class'=>'btn btn-danger disabled','title'=>lang("common_delete"))); 
					?>
					<?php echo 
						anchor("$controller_name/cleanup",
						'<i class="fa fa-undo hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('items_cleanup_old_items').'"></i><span class="visible-lg">'.lang("items_cleanup_old_items").'</span>',
						array('id'=>'cleanup', 
							'class'=>'btn btn-warning','title'=>lang("items_cleanup_old_items"))); 
					?>
				<?php } ?>
			</div>
		 </div>
		</div>
	</div>
	<div class="row ">
		<?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off')); ?>
			<input type="text" name ='search' id='search' value="<?php echo $search; ?>"  placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
			<?php echo form_dropdown('category', $categories, $category, 'id="category"'); ?>
			<?php echo form_submit('submitf', lang('common_search'),'class="btn btn-primary btn-sm"'); ?>
		</form>
	</div>
	<?php if($total_rows > $per_page) { ?>
		<div id="selectall" class="selectall" onclick="select_inv()" style="text-align: center;display:none;cursor:pointer">
			<?php echo lang('items_all').' <b>'.$per_page.'</b> '.lang('items_select_inventory').' <b style="text-decoration:underline">'.$total_rows.'</b> '.lang('items_select_inventory_total'); ?></div>
			<div id="selectnone" class="selectnone" onclick="select_inv_none()" style="text-align: center;display:none; cursor:pointer">
			<?php echo '<b>'.$total_rows.'</b> '.lang('items_selected_inventory_total').' '.lang('items_select_inventory_none'); ?>
		</div>
		<?php 
		}
		echo form_input(array(
		'name'=>'select_inventory',
		'id'=>'select_inventory',
		'style'=>'display:none',
		)); 
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-th"></i>
						</span>
						<h5 ><?php echo lang('common_list_of').' '.lang('module_'.$controller_name); ?></h5>
						<span title="<?php echo $total_rows; ?> total <?php echo $controller_name?>" class="label label-info tip-left"><?php echo $total_rows; ?></span>
						<a href="<?php echo site_url($controller_name.'/clear_state'); ?>" class="clear-state pull-right"><?php echo lang('common_clear_search'); ?></a>
					</div>
					<div class="widget-content nopadding table_holder table-responsive" >
						<?php echo $manage_table; ?>			
 					</div>		
 					<?php if($pagination) {  ?>

					<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_bottom" >
						<?php echo $pagination;?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("partial/footer"); ?>