<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1 ><i class="fa fa-pencil"></i> <?php  if(!$person_info->person_id) { echo lang('employees_new'); } else { echo lang('employees_update'); }    ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<div class="row" id="form">
		<div class="col-md-12">
			<?php echo lang('common_fields_required_message'); ?>
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo lang("employees_basic_information"); ?></h5>
				</div>
				<div class="widget-content">
					<?php 	$current_employee_editing_self = $this->Employee->get_logged_in_employee_info()->person_id == $person_info->person_id;
							echo form_open('employees/save/'.$person_info->person_id,array('id'=>'employee_form','class'=>'form-horizontal'));
					?>

					<?php $this->load->view("people/form_basic_info"); ?>

					<legend class="page-header text-info"> &nbsp; &nbsp; <?php echo lang("employees_login_info"); ?></legend>
					<div class="form-group">	
					<?php echo form_label(lang('employees_username').':', 'username',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'username',
							'id'=>'username',
							'class'=>'form-control',
							'value'=>$person_info->username));?>
						</div>
					</div>

					<div class="form-group">	
					<div class="form-group">	
					<?php echo form_label(lang('employees_password').':', 'password',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_password(array(
							'name'=>'password',
							'id'=>'password',
							'class'=>'form-control',
						));?>
						</div>
					</div>

					<div class="form-group">	
					<?php echo form_label(lang('employees_repeat_password').':', 'repeat_password',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_password(array(
							'name'=>'repeat_password',
							'id'=>'repeat_password',
							'class'=>'form-control',
						));?>
						</div>
					</div>
					
					<div class="form-group">	
					<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-sm-3 col-md-3 col-lg-2 col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_dropdown('language', array(
							'english'  => 'English',
							'indonesia'    => 'Indonesia',
							'spanish'   => 'Spanish', 
							'french'    => 'French',
							'italian'    => 'Italian'),
							$person_info->language ? $person_info->language : $this->Appconfig->get_raw_language_value());
							?>
						</div>
					</div>
					
					<?php if (count($locations) == 1) { ?>
						<?php
							echo form_hidden('locations[]', current(array_keys($locations)));
						?>
					<?php }else { ?>
						<div class="form-group">	
						<?php echo form_label(lang('employees_locations').':', 'location',array('class'=>'col-sm-3 col-md-3 col-lg-2 col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<ul id="locations_list" class="list-inline">
							<?php
								foreach($locations as $location_id => $location) 
								{
									$checkbox_options = array(
									'name' => 'locations[]',
									'value' => $location_id,
									'checked' => $location['has_access'],
									);
									
									if (!$location['can_assign_access'])
									{
										$checkbox_options['disabled'] = 'disabled';
									}
								
									echo '<li>'.form_checkbox($checkbox_options). ' '.$location['name'].'</li>';
								}
							?>
							</ul>
							</div>
						</div>
					<?php } ?>
					<div class="form-group">
					<legend class="page-header text-info"> &nbsp; &nbsp; <?php echo lang("employees_permission_info"); ?></legend>
					<p><?php echo lang("employees_permission_desc"); ?></p>
					<ul id="permission_list" class="list-unstyled">
					<?php
					foreach($all_modules->result() as $module)
					{
						$checkbox_options = array(
						'name' => 'permissions[]',
						'value' => $module->module_id,
						'checked' => $this->Employee->has_module_permission($module->module_id,$person_info->person_id),
						'class' => 'module_checkboxes '
						);
						
						if ($logged_in_employee_id != 1)
						{
							if(($current_employee_editing_self && $checkbox_options['checked']) || !$this->Employee->has_module_permission($module->module_id,$logged_in_employee_id))
							{
								$checkbox_options['disabled'] = 'disabled';
								echo form_hidden('permissions[]', $module->module_id);
							}
						}
					?>
					<li>	
					<?php echo form_checkbox($checkbox_options); ?>
					<span class="text-success"><?php echo $this->lang->line('module_'.$module->module_id);?>:</span>
					<span class="text-warning"><?php echo $this->lang->line('module_'.$module->module_id.'_desc');?></span>
						<ul>
						<?php
						foreach($this->Module_action->get_module_actions($module->module_id)->result() as $module_action)
						{
							$checkbox_options = array(
							'name' => 'permissions_actions[]',
							'value' => $module_action->module_id."|".$module_action->action_id,
							'checked' => $this->Employee->has_module_action_permission($module->module_id, $module_action->action_id, $person_info->person_id)
							);
		
							if ($logged_in_employee_id != 1)
							{
								if(($current_employee_editing_self && $checkbox_options['checked']) || (!$this->Employee->has_module_action_permission($module->module_id,$module_action->action_id,$logged_in_employee_id)))
								{
									$checkbox_options['disabled'] = 'disabled';
									echo form_hidden('permissions_actions[]', $module_action->module_id."|".$module_action->action_id);
								}
							}
							?>
							<li>
							<?php echo form_checkbox($checkbox_options); ?>
							<span class="text-info"><?php echo $this->lang->line($module_action->action_name_key);?></span>
							</li>
						<?php
						}
						?>
						</ul>
					</li>
					<?php
					}
					?>
					</ul>
					
					</div>

					<?php echo form_hidden('redirect_code', $redirect_code); ?>

					<div class="form-actions">
					<?php
							echo form_submit(array(
								'name'=>'submitf',
								'id'=>'submitf',
								'value'=>lang('common_submit'),
								'class'=>'btn btn-primary float_right')
							);

					?>
					</div>
					<?php 
					echo form_close();
					?>

<script type='text/javascript'>
$('#image_id').imagePreview({ selector : '#avatar' }); // Custom preview container

//validation and submit handling
$(document).ready(function()
{
    setTimeout(function(){$(":input:visible:first","#employee_form").focus();},100);
	$(".module_checkboxes").change(function()
	{
		if ($(this).prop('checked'))
		{
			$(this).parent().find('input[type=checkbox]').not(':disabled').prop('checked', true);
		}
		else
		{
			$(this).parent().find('input[type=checkbox]').not(':disabled').prop('checked', false);			
		}
	});

	$('#employee_form').validate({
		submitHandler:function(form)
		{
			$.post('<?php echo site_url("employees/check_duplicate");?>', {term: $('#first_name').val()+' '+$('#last_name').val()},function(data) {
			<?php if(!$person_info->person_id) { ?>
			if(data.duplicate)
				{
	
					if(confirm(<?php echo json_encode(lang('employees_duplicate_exists'));?>))
					{
						doEmployeeSubmit(form);
					}
					else 
					{
						return false;
					}
				}
			<?php } else ?>
				{
					doEmployeeSubmit(form);
				}} , "json")
				.error(function() { 
				});
		},
		errorClass: "text-danger",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
		},
		rules: 
		{
			first_name: "required",
			last_name: "required",
			username:
			{
				<?php if(!$person_info->person_id) { ?>
				remote: 
			    { 
					url: "<?php echo site_url('employees/exmployee_exists');?>", 
					type: "post"
			    }, 
				<?php } ?>
				required:true,
				minlength: 5
			},

			password:
			{
				<?php
				if($person_info->person_id == "")
				{
				?>
				required:true,
				<?php
				}
				?>
				minlength: 8
			},	
			repeat_password:
			{
 				equalTo: "#password"
			},
    		email: {
				"required": true,
				"email": true
			},
			"locations[]": "required"
   		},
		messages: 
		{
     		first_name: <?php echo json_encode(lang('common_first_name_required')); ?>,
     		last_name: <?php echo json_encode(lang('common_last_name_required')); ?>,
     		username:
     		{
				<?php if(!$person_info->person_id) { ?>
	     			remote: <?php echo json_encode(lang('employees_username_exists')); ?>,
				<?php } ?>
     			required: <?php echo json_encode(lang('employees_username_required')); ?>,
     			minlength: <?php echo json_encode(lang('employees_username_minlength')); ?>
     		},
			password:
			{
				<?php
				if($person_info->person_id == "")
				{
				?>
				required:<?php echo json_encode(lang('employees_password_required')); ?>,
				<?php
				}
				?>
				minlength: <?php echo json_encode(lang('employees_password_minlength')); ?>
			},
			repeat_password:
			{
				equalTo: <?php echo json_encode(lang('employees_password_must_match')); ?>
     		},
     		email: <?php echo json_encode(lang('common_email_invalid_format')); ?>,
			"locations[]": <?php echo json_encode(lang('employees_one_location_required')); ?>
		}
	});
});

var submitting = false;

function doEmployeeSubmit(form)
{
	$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
	if (submitting) return;
	submitting = true;

	$(form).ajaxSubmit({
	success:function(response)
		{
			$("#form").unmask();
			submitting = false;
			if(response.redirect_code==1 && response.success)
			{
				if (response.success)
				{
					gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.person_id,response.message,'gritter-item-success',false,false);				
				}
				else
				{
					gritter(<?php echo json_encode(lang('common_error')); ?>,response.message,'gritter-item-error',false,false);
					
				}
			}
			else if(response.redirect_code==2 && response.success)
			{
				window.location.href = '<?php echo site_url('employees'); ?>'
			}
		},
	<?php if(!$person_info->person_id) { ?>
	resetForm: true,
	<?php } ?>
	dataType:'json'
	});
}
</script>
