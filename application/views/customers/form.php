<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-pencil"></i>  <?php  if(!$person_info->person_id) { echo lang($controller_name.'_new'); } else { echo lang($controller_name.'_update'); }    ?>	</h1>
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
				<h5><?php echo lang("customers_basic_information"); ?></h5>
			</div>
			<div class="widget-content ">
				<?php echo form_open_multipart('customers/save/'.$person_info->person_id,array('id'=>'customer_form','class'=>'form-horizontal')); 	?>
				<?php $this->load->view("people/form_basic_info"); ?>

				<div class="form-group">	
					<?php echo form_label(lang('config_company').':', 'company_name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'company_name',
							'id'=>'customer_company_name',
							'class'=>'company_names',
							'value'=>$person_info->company_name)
							);?>
						</div>
					</div>

					<div class="form-group">	
						<?php echo form_label(lang('customers_account_number').':', 'account_number',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'account_number',
								'id'=>'account_number',
								'class'=>'company_names',
								'value'=>$person_info->account_number)
								);?>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('customers_taxable').':', 'taxable',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox('taxable', '1', $person_info->taxable == '' ? TRUE : (boolean)$person_info->taxable,'id="noreset"');?>
							</div>
						</div>

						<?php if (!empty($tiers)) { ?>
						<div class="form-group">	
							<?php echo form_label(lang('customers_tier_type').':', 'tier_type',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('tier_id', $tiers, $person_info->tier_id);?>
							</div>
						</div>
						<?php } ?>

						<?php if($person_info->cc_token && $person_info->cc_preview) { ?>
						<div class="control-group">	
							<?php echo form_label(lang('customers_delete_cc_info').':', 'delete_cc_info',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox('delete_cc_info', '1');?>
							</div>
						</div>
						<?php } ?>

						<?php echo form_hidden('redirect_code', $redirect_code); ?>

						<div class="form-actions">
							<?php
							echo form_submit(array(
								'name'=>'submitf',
								'id'=>'submitf',
								'value'=>lang('common_submit'),
								'class'=>' btn btn-primary')
							);
							?>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>

	<script type='text/javascript'>
					$('#image_id').imagePreview({ selector : '#avatar' }); // Custom preview container
						//validation and submit handling
						$(document).ready(function()
						{
							setTimeout(function(){$(":input:visible:first","#customer_form").focus();},100);
							var submitting = false;
							$('#customer_form').validate({
								submitHandler:function(form)
								{
									$.post('<?php echo site_url("customers/check_duplicate");?>', {term: $('#first_name').val()+' '+$('#last_name').val()},function(data) {
										<?php if(!$person_info->person_id) { ?>
											if(data.duplicate)
											{

												if(confirm(<?php echo json_encode(lang('customers_duplicate_exists'));?>))
												{
													doCustomerSubmit(form);
												}
												else 
												{
													return false;
												}
											}
											<?php } else ?>
											{
												doCustomerSubmit(form);
											}} , "json")
									.error(function() { 
									});
									
								},
								rules: 
								{
									<?php if(!$person_info->person_id) { ?>
										account_number:
										{
											remote: 
											{ 
												url: "<?php echo site_url('customers/account_number_exists');?>", 
												type: "post"

											} 
										},
										<?php } ?>
										first_name: "required",
										last_name: "required",
										email: "email"
									},
									errorClass: "text-danger",
									errorElement: "span",
										highlight:function(element, errorClass, validClass) {
											$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
										},
										unhighlight: function(element, errorClass, validClass) {
											$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
										},
									messages: 
									{
										<?php if(!$person_info->person_id) { ?>
											account_number:
											{
												remote: <?php echo json_encode(lang('common_account_number_exists')); ?>
											},
											<?php } ?>
											first_name: <?php echo json_encode(lang('common_first_name_required')); ?>,
											last_name: <?php echo json_encode(lang('common_last_name_required')); ?>,
											email: <?php echo json_encode(lang('common_email_invalid_format')); ?>
										}
									});
});

var submitting = false;

function doCustomerSubmit(form)
{
	$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
	if (submitting) return;
	submitting = true;

	$(form).ajaxSubmit({
		success:function(response)
		{
			$("#form").unmask();
			submitting = false;
			gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.person_id,response.message,response.success ? 'gritter-item-success' : 'gritter-item-error',false,false);
			if(response.redirect_code==1 && response.success)
			{ 
				$.post('<?php echo site_url("sales/select_customer");?>', {customer: response.person_id}, function()
				{
					window.location.href = '<?php echo site_url('sales'); ?>'
				});
			}
			else if(response.redirect_code==2 && response.success)
			{
				window.location.href = '<?php echo site_url('customers'); ?>'
			}
		},
		<?php if(!$person_info->person_id) { ?>
			resetForm: true,
			<?php } ?>
			dataType:'json'
		});
}
</script>
<?php $this->load->view("partial/footer"); ?>
