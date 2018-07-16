	<?php echo form_open('login/switch_user/',array('id'=>'login_form','class'=>'form-horizontal')); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3> <?php echo lang('login_switch_user'); ?></h3>
		</div>
		<div class="modal-body ">

			<div class="row">
				<div class="col-md-12">
				<div class="form-group">
				<?php echo form_label(lang('employees_employee').':', 'employee',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
				<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_dropdown('username', $employees, $this->Employee->get_logged_in_employee_info()->username);?>
				</div>
			</div>


			<div class="form-group">
				<?php echo form_label(lang('login_password').':', 'supplier',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
				<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_password(array(
						'name'=>'password', 
						'value'=>'',
						'size'=>'20')); ?>
					</div>
				</div>
			</div>

					
				</div>
			</div>
	
			<div class="modal-footer">
				<div class="form-acions">
					<?php
					echo form_submit(array(
						'name'=>'submit',
						'id'=>'submit',
						'value'=>lang('common_submit'),
						'class'=>'submit_button btn btn-primary btn-block')
					);
					?>
					<i id="spin" class="fa fa-spinner fa fa-spin fa fa-2x hidden"></i>
					<span id="error_message" class="text-danger">&nbsp;</span>
				</div>
				
			</div>

		
			
		</div>
	</div>
	
	<?php echo form_close(); ?>

	<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
	var submitting = false;

	$('#login_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$('#spin').removeClass('hidden');
			$(form).ajaxSubmit({
				success:function(response)
				{
					$('#spin').addClass('hidden');
					submitting = false;
					if(!response.success)
					{
						$('#error_message').html(response.message);
					}
					else
					{
						$('#myModal').modal('hide');
						window.location.href = '<?php echo site_url('sales'); ?>';
					}

					

				},
				dataType:'json'
			});
		},
		errorClass: "text-danger display-block",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('error');
			$(element).parents('.form-group').addClass('success');
		},rules:
		{

			password:"required",

		},
		messages:
		{
			password:
			{
				required: <?php echo json_encode(lang('login_invalid_username_and_password')); ?>
			}

		}
	});
});


</script>