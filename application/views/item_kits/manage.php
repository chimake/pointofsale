<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
	var table_columns = ["","item_kit_number","name",'description','unit_price','',''];
	enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
	enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
    
    $('#generate_barcodes').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
    		return false;
    	}

    	$(this).attr('href','<?php echo site_url("item_kits/generate_barcodes");?>/'+selected.join('~'));
    });

    $('#generate_barcode_labels').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
    		return false;
    	}

    	$(this).attr('href','<?php echo site_url("item_kits/generate_barcode_labels");?>/'+selected.join('~'));
    });
});

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(
		{
			sortList: [[1,0]],
			headers:
			{
				0: { sorter: false},
				5: { sorter: false}
			}

		});
	}
}
</script>


<div id="content-header" class="hidden-print">
	<h1> <i class="icon fa fa-inbox"></i> <?php echo lang('module_'.$controller_name); ?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<?php if($pagination) {  ?>

<div class="pagination hidden-print alternate text-center" id="pagination_top" >
	<?php echo $pagination;?>
</div>
<?php }  ?>
<div class="pull-right">
		<div class="row">
			<div class="col-md-12 ">					
				<div class="btn-group  ">
				
					<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
						<?php echo 
							anchor("$controller_name/view/-1/",
							'<i class="fa fa-pencil fa fa-2x hidden-lg tip-bottom"></i> <span class="visible-lg">'.lang($controller_name.'_new').'</span>',
							array('class'=>'btn btn-primary tip-bottom', 
								'title'=>lang($controller_name.'_new')));
						?>
					<?php } ?>

					<?php echo 
						anchor("$controller_name/generate_barcode_labels",
						'<i class="fa fa-barcode hidden-lg tip-bottom fa fa-2x"></i> <span class="visible-lg">'.lang("common_barcode_labels").'</span>',
						array('id'=>'generate_barcode_labels', 
							'class' => 'btn hidden-xs btn-primary disabled tip-bottom',
							'target' =>'_blank',
							'title'=>lang('common_barcode_labels'))); 
					?>
					<?php echo 
						anchor("$controller_name/generate_barcodes",
						'<i class="fa fa-barcode fa fa-2x hidden-lg tip-bottom"></i> <span class="visible-lg">'.lang("common_barcode_sheet").'</span>',
						array('id'=>'generate_barcodes',
						 	'class' => 'btn hidden-xs btn-primary disabled tip-bottom',
							'target' =>'_blank',
							'title'=>lang('common_barcode_sheet'))); 
					?>
					<?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
						
							<?php echo 
								anchor("$controller_name/delete",
								'<i class="fa fa-trash-o fa fa-2x hidden-lg tip-bottom"></i> <span class="visible-lg">'.lang("common_delete").'</span>',
								array('id'=>'delete', 
									'class'=>'btn btn-danger tip-bottom disabled','title'=>lang("common_delete"))); 
							?>
					<?php } ?>
					<?php echo anchor("$controller_name/excel_export",
					'<i class="fa fa-download fa fa-2x hidden-lg tip-bottom"></i> <span class="visible-lg">'.lang("common_excel_export").'</span>',
						array('class'=>'btn hidden-xs btn-primary import tip-bottom','title'=>lang('common_excel_export')));
					?>
				</div>
			</div>
		</div>
	</div>
	
		<div class="row ">
			<?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off')); ?>
				<input type="text" name ='search' id='search'  value="<?php echo $search;  ?>" placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
			</form>
		</div>
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
						<div class="widget-content nopadding  table_holder table-responsive"  >
							<div id="item_table">
								<div id="table_holder">
									<?php echo $manage_table; ?>			
								</div>
							</div>		
						</div>
						<?php if($pagination) {  ?>

						<div class="pagination hidden-print alternate text-center" id="pagination_bottom" >
							<?php echo $pagination;?>
						</div>
						<?php } ?>
					</div>
					</div>
				</div>
				</div>
				
<?php $this->load->view("partial/footer"); ?>
