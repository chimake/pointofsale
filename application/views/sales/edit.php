<?php $this->load->view("partial/header"); ?>
	<div id="content-header" class="hidden-print edit-sale-header">
		<h1><i class="fa fa-pencil"></i> <?php echo lang('sales_register')." <small> -  ".lang('sales_edit_sale'); ?>  <?php echo $this->config->item('sale_prefix').' '.$sale_info['sale_id']; ?> </small></h1>
	</div>

	<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	</div>
	<div class="clear"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo lang("sales_edit_sale"); ?></h5>
				</div>
				<div class="widget-content nopadding">

	<?php echo form_open("sales/save/".$sale_info['sale_id'],array('id'=>'sales_edit_form','class'=>'form-horizontal')); ?>


	<div class="form-group">	
		<?php echo form_label(lang('sales_receipt').':', 'sales_receipt',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
			<?php echo anchor('sales/receipt/'.$sale_info['sale_id'], $this->config->item('sale_prefix').' '.$sale_info['sale_id'], array('target' => '_blank'));?>
		</div>
	</div>

	<div class="form-group">	
		<?php echo form_label(lang('sales_date').':', 'date',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array('name'=>'date','value'=>date(get_date_format(), strtotime($sale_info['sale_time'])), 'id'=>'date'));?>
		</div>
	</div>

	<div class="form-group">	
		<?php echo form_label(lang('sales_customer').':', 'customer',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
			<?php echo form_dropdown('customer_id', $customers, $sale_info['customer_id'], 'id="customer_id" class="span3"');?>
			&nbsp;
			<?php if ($sale_info['customer_id']) { ?>
				<?php echo anchor('sales/email_receipt/'.$sale_info['sale_id'], lang('sales_email_receipt'), array('id' => 'email_receipt'));?>
			<?php }?>
		</div>
	</div>
	

	<div class="form-group">	
		<?php echo form_label(lang('sales_employee').':', 'employee',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
			<?php echo form_dropdown('employee_id', $employees, $sale_info['employee_id'], 'id="employee_id" class="span3"');?>
		</div>
	</div>
	


	<div class="form-group">	
		<?php echo form_label(lang('sales_comments_receipt').':', 'sales_comments_receipt',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
						<?php echo form_checkbox(array(
											'name'=>'show_comment_on_receipt',
											'id'=>'show_comment_on_receipt',
											'value'=>'1',
											'checked'=>(boolean)$sale_info['show_comment_on_receipt'])
										);
			?>
		</div>
	</div>
	
	<div class="form-group">	
		<?php echo form_label(lang('sales_comment').':', 'comment',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_textarea(array('name'=>'comment','value'=>$sale_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
		</div>
	</div>

	
		<div class="form-actions">
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>lang('common_submit'),
		'class'=>' btn btn-primary')
	);
	?>
	</div>

	</form>

	<?php if ($sale_info['deleted'])
	{
	?>
	<?php echo form_open("sales/undelete/".$sale_info['sale_id'],array('id'=>'sales_undelete_form','class'=>'form-horizontal')); ?>
	
	<div class="form-group">	
		<?php echo form_label(lang('sales_deleted_by').':&nbsp;', 'deleted_by',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
		
		<div class="controls" style="padding-top:7px;">
			<?php echo anchor('employees/view/'.$sale_info['deleted_by'], $this->Employee->get_info($sale_info['deleted_by'])->first_name.' '.$this->Employee->get_info($sale_info['deleted_by'])->last_name, array('target' => '_blank'));?>
		</div>
	</div>
	
		<div class="form-actions">
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>lang('sales_undelete_entire_sale'),
		'class'=>' btn btn-primary')
	);
	?>
	</div>
	</form>
	<?php
	}
	else
	{
	?>
	<?php 
	 if ($this->Employee->has_module_action_permission('sales', 'edit_sale', $this->Employee->get_logged_in_employee_info()->person_id)){

	echo form_open("sales/change_sale/".$sale_info['sale_id'],array('id'=>'sales_change_form','class'=>'form-horizontal')); ?>

		<div class="form-actions">
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>lang('sales_change_sale'),
		'class'=>' btn btn-primary')
	); 
}
	?>
	</div>
	</form>
	
	
	<?php
	 	if ($this->Employee->has_module_action_permission('sales', 'delete_sale', $this->Employee->get_logged_in_employee_info()->person_id))
		{ 
	 			echo form_open("sales/delete/".$sale_info['sale_id'],array('id'=>'sales_delete_form','class'=>'form-horizontal')); ?>

		<div class="form-actions">
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>lang('sales_delete_entire_sale'),
		'class'=>' btn btn-primary')
	);
	?>
	</div>

	</form>
	<?php
	} }
	?>
</div>
	</div>
</div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{	
	$("#email_receipt").click(function()
	{
		$.get($(this).attr('href'), function()
		{
			gritter(<?php echo json_encode(lang('common_success')); ?>,'<?php echo lang('sales_receipt_sent'); ?>','gritter-item-success',false,false);
			
		});
		
		return false;
	});
	$('#date').datepicker({format: '<?php echo get_js_date_format(); ?>'});
	$("#sales_delete_form").submit(function()
	{
		if (!confirm(<?php echo json_encode(lang("sales_delete_confirmation")); ?>))
		{
			return false;
		}
	});
	
	$("#sales_undelete_form").submit(function()
	{
		if (!confirm(<?php echo json_encode(lang("sales_undelete_confirmation")); ?>))
		{
			return false;
		}
	});
	
	$('#sales_edit_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				if(response.success)
				{
					submitting = false;
					gritter(<?php echo json_encode(lang('common_success')); ?> ,response.message,'gritter-item-success',false,false);
				
				}
				else
				{
					submitting = false;
					gritter(<?php echo json_encode(lang('common_error')); ?> ,response.message,'gritter-item-error',false,false);
					
				}
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
   		},
		messages: 
		{
		}
	});
});
</script>