<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-pencil"></i>  <?php  if(!$location_info->location_id) { echo lang('locations_new'); } else { echo lang('locations_update'); }    ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

<?php if (isset($needs_auth) && $needs_auth) {?>
	<?php echo form_open('locations/check_auth',array('id'=>'location_form_auth','class'=>'form-horizontal')); ?>
	
	<h3 style="margin-left: 80px;"><a href="http:/optimumlinkup.com.ng" target="_blank"><?php echo lang('visit optimumlinkup.com.ng'); ?> &raquo;</a></h3>
	<div class="form-group">
		<?php echo form_label(lang('locations_purchase_email').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
		<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control form-inps',
				'name'=>'purchase_email',
				'id'=>'purchase_email')
			);?>
		</div>	
	</div>
	<div class="form-actions">
		<?php
		echo form_submit(array(
			'name'=>'submitf',
			'id'=>'submitf',
			'value'=>lang('common_submit'),
			'class'=>'submit_button btn btn-primary')
		);
		?>
		</div>
		
        <?php if (validation_errors()) {?>
        <div class="alert alert-danger">
            <strong><?php echo lang('common_error'); ?></strong>
            <?php echo validation_errors(); ?>
        </div>
        <?php } ?>	
</form>
<?php } else {?>

	<?php echo form_open_multipart('locations/save/'.$location_info->location_id,array('id'=>'location_form','class'=>'form-horizontal','autocomplete'=> 'off')); ?>
		<div class="row" id="form">
			<div class="col-md-12">
				<?php echo lang('common_fields_required_message'); ?>
				
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-align-justify"></i>									
						</span>
						<h5><?php echo lang("locations_basic_information"); ?></h5>
					</div>
					<div class="widget-content nopadding">

						<div class="form-group">
							<?php echo form_label(lang('locations_name').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'name',
									'id'=>'name',
									'value'=>$location_info->name)
								);?>
							</div>
						</div>


						<div class="form-group">
							<?php echo form_label(lang('locations_address').':', 'address',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								
								<?php echo form_textarea(array(
									'name'=>'address',
									'id'=>'address',
									'class'=>'form-textarea',
									'rows'=>'4',
									'cols'=>'30',
									'value'=>$location_info->address));?>								
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label(lang('locations_phone').':', 'phone',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'phone',
									'id'=>'phone',
									'value'=>$location_info->phone)
								);?>
							</div>
						</div>
					
						<div class="form-group">
							<?php echo form_label(lang('locations_fax').':', 'fax',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'fax',
									'id'=>'fax',
									'value'=>$location_info->fax)
								);?>
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label(lang('locations_email').':', 'email',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'email',
									'id'=>'email',
									'value'=>$location_info->email)
								);?>
							</div>
						</div>

						<div class="form-group">
						<?php echo form_label("<a href='http://go.mercurypay.com/go/PHP_POS/' target='_blank'>".$this->lang->line('config_enable_credit_card_processing').'</a>:', 'enable_credit_card_processing',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_checkbox(array(
								'name'=>'enable_credit_card_processing',
								'id'=>'enable_credit_card_processing',
								'value'=>'1',
								'checked'=>$location_info->enable_credit_card_processing));?>
							</div>
						</div>

						<div id="merchant_information">
							<div class="form-group">	
							<?php echo form_label($this->lang->line('config_merchant_id').':', 'merchant_id',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'merchant_id',
									'id'=>'merchant_id',
									'value'=>$location_info->merchant_id));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label($this->lang->line('config_merchant_password').':', 'merchant_password',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_password(array(
									'name'=>'merchant_password',
									'id'=>'merchant_password',
									'class'=>'form-control form-inps',
									'value'=>$location_info->merchant_password));?>
								<span id="merchant_password_note"><?php echo lang('sales_mercury_password_note'); ?></span>
								</div>
								
							</div>
						</div>
	
						<div class="form-group">	
						<?php echo form_label(lang('receive_stock_alert').':', 'receive_stock_alert',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_checkbox(array(
								'name'=>'receive_stock_alert',
								'id'=>'receive_stock_alert',
								'value'=>'1',
								'checked'=>$location_info->receive_stock_alert));?>
							</div>
						</div>

						<div class="form-group">	
						<?php echo form_label(lang('stock_alert_email').':', 'stock_alert_email',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps',
								'name'=>'stock_alert_email',
								'id'=>'stock_alert_email',
								'value'=>$location_info->stock_alert_email));?>
							</div>
						</div>
	
						<div class="form-group">	
							<?php echo form_label(lang('config_default_tax_rate_1').':', 'default_tax_1_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-4 col-md-4 col-lg-5">
								<?php echo form_input(array(
								'class'=>'form-control form-inps',
								'placeholder' => lang('common_tax_name'),
								'name'=>'default_tax_1_name',
								'id'=>'default_tax_1_name',
								'size'=>'10',
								'value'=>$location_info->default_tax_1_name ? $location_info->default_tax_1_name : lang('items_sales_tax_1')));?>
							</div>

							<div class="col-sm-4 col-md-4 col-lg-5">
								<?php echo form_input(array(
								'class'=>'form-control form-inps-tax',
								'placeholder' => lang('items_tax_percent'),
								'name'=>'default_tax_1_rate',
								'id'=>'default_tax_1_rate',
								'size'=>'4',
								'value'=>$location_info->default_tax_1_rate));?>
								<div class="tax-percent-icon">%</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_default_tax_rate_2').':', 'default_tax_1_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-4 col-md-4 col-lg-5">
								<?php echo form_input(array(
								'class'=>'form-control form-inps',
								'placeholder' => lang('common_tax_name'),
								'name'=>'default_tax_2_name',
								'id'=>'default_tax_2_name',
								'size'=>'10',
								'value'=>$location_info->default_tax_2_name ? $location_info->default_tax_2_name : lang('items_sales_tax_2')));?>
							</div>

							<div class="col-sm-4 col-md-4 col-lg-5">
								<?php echo form_input(array(
								'class'=>'form-control form-inps-tax'	,
								'placeholder' => lang('items_tax_percent'),
								'name'=>'default_tax_2_rate',
								'id'=>'default_tax_2_rate',
								'size'=>'4',
								'value'=>$location_info->default_tax_2_rate));?>
								<div class="tax-percent-icon">%</div>
								<div class="clear"></div>
								<?php echo form_checkbox('default_tax_2_cumulative', '1', $location_info->default_tax_2_cumulative ? true : false, 'class="cumulative_checkbox"');  ?>
								<span class="cumulative_label">
									<?php echo lang('common_cumulative'); ?>
								</span>
							</div>
						</div>
					
						<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3" style="display: <?php echo $location_info->default_tax_3_rate ? 'none' : 'block';?>">
							<a href="javascript: void(0);" class="show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
						</div>
					
						<div class="more_taxes_container" style="display: <?php echo $location_info->default_tax_3_rate ? 'block' : 'none';?>">
							
							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_3').':', 'default_tax_3_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'placeholder' => lang('common_tax_name'),
									'name'=>'default_tax_3_name',
									'id'=>'default_tax_3_name',
									'size'=>'10',
									'value'=>$location_info->default_tax_3_name ? $location_info->default_tax_3_name : lang('items_sales_tax_3')));?>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'placeholder' => lang('items_tax_percent'),
									'name'=>'default_tax_3_rate',
									'id'=>'default_tax_3_rate',
									'size'=>'4',
									'value'=>$location_info->default_tax_3_rate));?>%
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_4').':', 'default_tax_4_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'placeholder' => lang('common_tax_name'),
									'name'=>'default_tax_4_name',
									'id'=>'default_tax_4_name',
									'size'=>'10',
									'value'=>$location_info->default_tax_4_name ? $location_info->default_tax_4_name : lang('items_sales_tax_4')));?>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'default_tax_4_rate',
									'placeholder' => lang('items_tax_percent'),
									'id'=>'default_tax_4_rate',
									'size'=>'4',
									'value'=>$location_info->default_tax_4_rate));?>%
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_5').':', 'default_tax_5_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'placeholder' => lang('common_tax_name'),
									'name'=>'default_tax_5_name',
									'id'=>'default_tax_5_name',
									'size'=>'10',
									'value'=>$location_info->default_tax_5_name ? $location_info->default_tax_5_name : lang('items_sales_tax_5')));?>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'default_tax_5_rate',
									'placeholder' => lang('items_tax_percent'),
									'id'=>'default_tax_5_rate',
									'size'=>'4',
									'value'=>$location_info->default_tax_5_rate));?>%
								</div>
							</div>
							
							
						</div><!--End more Taxes Container-->					
						<div class="form-group">	
						<?php echo form_label(lang('config_timezone').':', 'timezone',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_dropdown('timezone', $all_timezones, $location_info->timezone);
								?>
							</div>
						</div>

						<div class="form-group">	
						<?php echo form_label(lang('config_mailchimp_api_key').':', 'mailchimp_api_key',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps',
								'name'=>'mailchimp_api_key',
								'id'=>'mailchimp_api_key',
								'value'=>$location_info->mailchimp_api_key));?>
							</div>
						</div>

						<?php echo form_hidden('redirect', $redirect); ?>

						<div class="form-actions">
					<?php
					if ($purchase_email)
					{
						echo form_hidden('purchase_email', $purchase_email);
					}
					
					echo form_submit(array(
						'name'=>'submitf',
						'id'=>'submitf',
						'value'=>lang('common_submit'),
						'class'=>'submit_button btn btn-primary')
					);
					?>
					</div>
				<?php echo form_close(); ?>
			<?php }?>
			</div>
		</div>
	</div>
</div>
</div>
		

<script type='text/javascript'>
	var submitting = false;
		//validation and submit handling
		$(document).ready(function()
		{
			if ($("#location_form_auth").length == 1)
			{
			    setTimeout(function(){$(":input:visible:first","#location_form_auth").focus();},100);
			}
			else
			{
			    setTimeout(function(){$(":input:visible:first","#location_form").focus();},100);				
			}
			var submitting = false;
			$('#location_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
					$(form).ajaxSubmit({
					success:function(response)
					{
						$("#form").unmask();
						submitting = false;

						gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.location_id,response.message,response.success ? 'gritter-item-success' : 'gritter-item-error',false,false);

						
						if(response.redirect==2 && response.success)
						{
							window.location.href = '<?php echo site_url('locations'); ?>'
						}
										
					},
					<?php if(!$location_info->location_id) { ?>
					resetForm: true,
					<?php } ?>
					dataType:'json'
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
					name:
					{
						required:true,
					},
					phone:
					{
						required:true
					},
					address:
					{
						required:true
					},
					timezone:
					{
						required: true
					}
		   		},
				messages:
				{
					name:
					{
						required:<?php echo json_encode(lang('locatoins_name_required')); ?>,

					},
					phone:
					{
						required:<?php echo json_encode(lang('locations_phone_required')); ?>,
						number:<?php echo json_encode(lang('locations_phone_valid')); ?>
					},
					address:
					{
						required:<?php echo json_encode(lang('config_address_required')); ?>
					},
					timezone:
					{
						required:<?php echo json_encode(lang('config_timezone_required_field')); ?>
					}
				}
			});
			
			$("#enable_credit_card_processing").change(check_enable_credit_card_processing).ready(check_enable_credit_card_processing);

			function check_enable_credit_card_processing()
			{
				if($("#enable_credit_card_processing").prop('checked'))
				{
					$("#merchant_information").show();
				}
				else
				{
					$("#merchant_information").hide();
				}

			}
		});

</script>
<?php $this->load->view('partial/footer'); ?>