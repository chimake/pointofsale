<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
	var table_columns = ["","location_id","name",'','phone','email',''];
	enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
    enable_cleanup(<?php echo json_encode(lang("items_confirm_cleanup"));?>);
	
   
});

</script>
<div id="content-header" class="hidden-print">
	<h1 ><i class="icon fa fa-home"></i> <?php echo lang('module_'.$controller_name); ?></h1>
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
						<?php echo 
						anchor("$controller_name/view/-1/",
						'<i title="'.lang($controller_name.'_new').'" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang($controller_name.'_new').'</span>',
						array('class'=>'btn btn-medium btn-primary tip-bottom', 
							'title'=>lang($controller_name.'_new'),
							'id' => 'new_location_btn'));
						?>

					<?php echo 
						anchor("$controller_name/delete",
						'<i title="'.lang('common_delete').'" class="fa fa-trash-o tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang('common_delete').'</span>',
						array('id'=>'delete', 
							'class'=>'btn btn-danger tip-bottom disabled','title'=>lang("common_delete"))); 
					?>
			</div>
		 </div>
		</div>
	</div>
	<div class="row ">
		<?php
		if (!is_on_demo_host()) { ?>
			<div class="col-md-12" style="text-align: right;">
				<strong><a href="http://phpsoftwares.com/buy_additional.php" target="_blank"><?php echo lang('locations_adding_location_requires_addtional_license'); ?></a></strong>
			</div>
		 <?php } ?>
		<?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off')); ?>
			<input type="text" name ='search' id='search' value=" <?php echo $search; ?> " placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
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

<?php if (!is_on_demo_host()) { ?>
	<script type="text/javascript">
	$('#new_location_btn').click(function()
	{
		if (!confirm(<?php echo json_encode(lang('locations_confirm_purchase')); ?>))
		{
			window.location='http://phpsoftwares.com/buy_additional.php';
			return false;
		}
	})
	</script>	
<?php } ?>
			
<?php $this->load->view("partial/footer"); ?>