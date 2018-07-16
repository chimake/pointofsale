<?php $this->load->view("partial/header"); ?>


<div id="content-header" class="hidden-print edit-sale-header">
	<h1 > <i class="fa fa-pencil"></i>  <?php echo lang('receivings_register')." - ".lang('receivings_edit_receiving'); ?> <span class="text-warning"> RECV <?php echo $receiving_info['receiving_id']; ?> </span>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	
	<?php echo form_open("receivings/save/".$receiving_info['receiving_id'],array('id'=>'receivings_edit_form','class'=>'form-horizontal')); ?>
	
		<div class="row">
			<div class="col-md-12 editss">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-align-justify"></i>									
						</span>
						<h5><?php echo lang("items_basic_information"); ?></h5>
					</div>
					<div class="widget-content widget-contentz nopadding">
	
					<div class="form-group">
					<?php echo form_label(lang('receivings_receipt').':', 'receipt',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
							<?php echo anchor('receivings/receipt/'.$receiving_info['receiving_id'], 'RECV '.$receiving_info['receiving_id'], array('target' => '_blank'));?>
						</div>
					</div>
	
					<div class="form-group">
					<?php echo form_label(lang('sales_date').':', 'date',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
							<?php echo form_input(array('name'=>'date','value'=>date(get_date_format(), strtotime($receiving_info['receiving_time'])), 'id'=>'date'));?>
						</div>
					</div>
	
					<div class="form-group">
					<?php echo form_label(lang('receivings_supplier').':', 'supplier',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
							<?php echo form_dropdown('supplier_id', $suppliers, $receiving_info['supplier_id'], 'id="supplier_id"');?>
						</div>
					</div>
	
					<div class="form-group">
					<?php echo form_label(lang('sales_employee').':', 'employee',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10 sale-s">
							<?php echo form_dropdown('employee_id', $employees, $receiving_info['employee_id'], 'id="employee_id"');?>
						</div>
					</div>
	
					<div class="form-group">
					<?php echo form_label(lang('sales_comment').':', 'comment',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_textarea(array('name'=>'comment','value'=>$receiving_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
						</div>
					</div>
	
					<div class="form-actions form-actionszs">
					<?php
					echo form_submit(array(
						'name'=>'submit',
						'id'=>'submit',
						'value'=>lang('common_submit'),
						'class'=>'btn btn-primary float_left submitzz',
						'style'=>'margin-right:15px')
					);
					?>
				</form>
				<?php if ($receiving_info['deleted'])
				{
				?>
				<?php echo form_open("receivings/undelete/".$receiving_info['receiving_id'],array('id'=>'receivings_undelete_form')); ?>
					<?php
					echo form_submit(array(
						'name'=>'submit',
						'id'=>'submit',
						'value'=>lang('receivings_undelete_entire_sale'),
						'class'=>'btn btn-primary submitzz')
					);
					?>
				</form>
                	<div class="clear">&nbsp;</div>
					
				<?php
				}
				else
				{
				?>
				<?php echo form_open("receivings/delete/".$receiving_info['receiving_id'],array('id'=>'receivings_delete_form')); ?>
					<?php
					echo form_submit(array(
						'name'=>'submit',
						'id'=>'submit',
						'value'=>lang('receivings_delete_entire_receiving'),
						'class'=>'btn btn-danger delete_button delete_btnz') 
					);
					?>
				</form>
                <div class="clear">&nbsp;</div>
					</div>
				<?php
				}
				?>

			</div>
		</div>
	</div>
</div>	
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{	
	$('#date').datepicker({format: '<?php echo get_js_date_format(); ?>'});
	$("#receivings_delete_form").submit(function()
	{
		if (!confirm(<?php echo json_encode(lang("sales_delete_confirmation")); ?>))
		{
			return false;
		}
	});
	
	$("#receivings_undelete_form").submit(function()
	{
		if (!confirm(<?php echo json_encode(lang("receivings_undelete_confirmation")); ?>))
		{
			return false;
		}
	});
	var submitting = false;
	$('#receivings_edit_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			
			$(form).ajaxSubmit({
			success:function(response)
			{
				submitting = false;
				if(response.success)
				{
					gritter(<?php echo json_encode(lang('common_success')); ?>,response.message,'gritter-item-success',false,false);
				}
				else
				{
					gritter(<?php echo json_encode(lang('common_error')); ?>,response.message,'gritter-item-error',false,false);
					
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