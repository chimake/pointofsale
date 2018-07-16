<?php $this->load->view("partial/header"); ?>
	<div id="content-header" class="hidden-print">
		<h1> <i class="fa fa-upload"></i> <?php echo lang('customers_import_customers_from_excel'); ?>	</h1>
	</div>

	<div id="breadcrumb" class="hidden-print">
			<?php echo create_breadcrumb(); ?>
	</div>
	<div class="clear"></div>
		<div class="row" id="form">
			<div class="col-md-12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-align-justify"></i>									
						</span>
						<h5><?php echo lang('customers_mass_import_from_excel'); ?></h5>
					</div>
					<div class="widget-content">
						<ul class="text-error" id="error_message_box"></ul>
						<?php echo form_open_multipart('customers/do_excel_import/',array('id'=>'item_form','class'=>'form-horizontal')); ?>
						<h2><?php echo lang('common_step_1'); ?>: </h2>
						<p><?php echo lang('customers_step_1_desc'); ?></p>
					
						<a class="btn btn-info btn-sm " href="<?php echo site_url('customers/excel'); ?>"><?php echo lang('customers_new_customers_import'); ?></a>
						<?php echo lang('common_or');?>
						<a class="btn btn-info btn-sm " href="<?php echo site_url('customers/excel_export'); ?>"><?php echo lang('customers_update_customers_import'); ?></a>
						
						<h2><?php echo lang('common_step_2'); ?>: </h2>
						<p><?php echo lang('customers_step_1_desc'); ?></p>
							<div class="control-group">
								<?php echo form_label(lang('common_file_path').':', 'name',array('class'=>'wide control-label')); ?>
								<div class="controls">
								<?php echo form_upload(array(
									'name'=>'file_path',
									'id'=>'file_path',
									'value'=>'')
									);?>
								</div>
							</div>
							<div class="form-actions">
								<?php echo form_submit(array(
									'name'=>'submitf',
									'id'=>'submitf',
									'value'=>lang('common_submit'),
									'class'=>'btn btn-primary')
									); ?>
							</div>
						</form>
					</div>
				</div>	
			</div>
		
			<script type='text/javascript'>
				//validation and submit handling
				$(document).ready(function()
				{	
					var submitting = false;
					$('#item_form').validate({
						submitHandler:function(form)
						{
							if (submitting) return;
							submitting = true;
							$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
							$(form).ajaxSubmit({
								success:function(response)
								{
									$("#form").unmask();
									if(!response.success)
									{ 
										gritter(<?php echo json_encode(lang('common_error')); ?>,response.message,'gritter-item-error',false,false);
									}
									else
									{
										gritter(<?php echo json_encode(lang('common_success')); ?>,response.message,'gritter-item-success',false,false);
									}
									submitting = false;
								},
								dataType:'json',
								resetForm: true
							});
						},
						errorLabelContainer: "#error_message_box",
				 		wrapper: "li",
						highlight:function(element, errorClass, validClass) {
							$(element).parents('.control-group').addClass('error');
						},
						unhighlight: function(element, errorClass, validClass) {
							$(element).parents('.control-group').removeClass('error');
							$(element).parents('.control-group').addClass('success');
						},
						rules: 
						{
							file_path:"required"
				   		},
						messages: 
						{
				   			file_path:<?php echo json_encode(lang('customers_full_path_to_excel_required')); ?>
						}
					});
				});
			</script>
<?php $this->load->view("partial/footer"); ?>