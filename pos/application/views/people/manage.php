<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
	$(document).ready(function() 
	{ 
		<?php if ($controller_name == 'suppliers') { ?>
			var table_columns = ['','company_name','last_name','first_name','email','phone_number'];
			
		<?php } else { ?>
			var table_columns = ['','<?php echo $this->db->dbprefix('people'); ?>'+'.person_id','last_name','first_name','email','phone_number'];
		<?php } ?>
		enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
	    enable_select_all();
	    enable_checkboxes();
	    enable_row_selection();
	    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
	    enable_email('<?php echo site_url("$controller_name/mailto")?>');
	    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
		 enable_cleanup(<?php echo json_encode(lang($controller_name."_confirm_cleanup"));?>);
		 $('#new-person-btn, .update-person').click(function()
	 	 {
			$("body").mask(<?php echo json_encode(lang('common_wait')); ?>);
	 	 });
			
	}); 
</script>
<div id="content-header" class="hidden-print">
	<h1> <i class="icon fa fa-<?php echo $controller_name=="customers" ? "group" : "user"; ?>"></i>
		<?php echo lang('module_'.$controller_name); ?></h1>


	</div>


	<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>

	</div>
	<div class="clear"></div>
	<?php if($pagination) {  ?>
	<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top">
		<?php echo $pagination;?>
	</div>
	 <?php }  ?>

	<div class=" pull-right">
		<div class="row">
			<div class="col-md-12 center" style="text-align: center;">					
				<div class="btn-group  ">

					<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
					<?php echo anchor("$controller_name/view/-1/",
						'<i title="'.lang($controller_name.'_new').'" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang($controller_name.'_new').'</span>',
						array('id' => 'new-person-btn', 'class'=>'btn btn-primary', 'title'=>$this->lang->line($controller_name.'_new')));
				}	
				?>
				<a class="btn btn-primary disabled email email_inactive" title="<?php echo lang("common_email");?>" id="email" href="<?php echo current_url(). '#'; ?>" >
					<i title="<?php echo lang('common_email'); ?>" class="fa fa-envelope tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('common_email'); ?></span>
				</a>

				<?php if ($controller_name =='customers' || $controller_name == 'suppliers') {  
					?>
					<?php echo anchor("$controller_name/excel_import/",
						'<i title="'.lang('common_excel_import').'" class="fa fa-upload tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang('common_excel_import').'</span>',
						array('class'=>'btn hidden-xs btn-primary ','title'=>lang('common_excel_import')));
					} ?>


					<?php
					if ($controller_name == 'customers' || $controller_name == 'employees' || $controller_name == 'suppliers') {	
						echo anchor("$controller_name/excel_export",
							'<i title="'.lang('common_excel_export').'" class="fa fa-download tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang('common_excel_export').'</span>',
							array('class'=>'btn hidden-xs btn-primary  import','title'=>lang('common_excel_export')));

					}
					?>
					<?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
					<?php echo anchor("$controller_name/delete",
						'<i title="'.lang('common_delete').'" class="fa fa-trash-o tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang('common_delete').'</span>'
						,array('id'=>'delete', 'class'=>'btn btn-danger disabled delete_inactive ','title'=>$this->lang->line("common_delete"))); ?>
						<?php } ?>
						<?php if ($controller_name =='customers' or $controller_name =='employees') {?>
						<?php echo 
						anchor("$controller_name/cleanup",
							'<i title="'.lang($controller_name."_cleanup_old_customers").'" class="fa fa-undo tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang($controller_name."_cleanup_old_customers").'</span>',
							array('id'=>'cleanup', 
								'class'=>'btn btn-warning ','title'=> lang($controller_name."_cleanup_old_customers"))); 
								?>
								<?php } ?>

							</div>
						</div>
					</div>
				</div>
				<div class="row ">
					<?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off')); ?>
					<input type="text" name ='search' id='search' value="<?php echo $search;  ?>"   placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
				</form>
			</div>

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
			<?php $this->load->view("partial/footer"); ?>