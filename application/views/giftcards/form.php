<?php $this->load->view("partial/header"); ?>
	<div id="content-header" class="hidden-print">
		<h1> <i class="fa fa-pencil"></i> <?php  if(!$giftcard_info->giftcard_id) { echo lang('giftcards_new'); } else { echo lang('giftcards_update'); }    ?>	</h1>
	</div>

	<div id="breadcrumb" class="hidden-print">
			<?php echo create_breadcrumb(); ?>
	</div>
    <div class="clear"></div>
	<div class="container-fluid" id="form">
		<div class="row">
			<div class="col-md-12">
				<?php echo lang('common_fields_required_message'); ?>
				
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-align-justify"></i>									
						</span>
						<h5><?php echo lang("giftcards_basic_information"); ?></h5>
					</div>
					<div class="widget-content">
					<?php echo form_open('giftcards/save/'.$giftcard_info->giftcard_id,array('id'=>'giftcard_form','class'=>'form-horizontal')); ?>

						<div class="form-group">	
							<?php echo form_label(lang('giftcards_giftcard_number').':', 'name',array('class'=>'required wide col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
								'name'=>'giftcard_number',
								'size'=>'8',
								'id'=>'giftcard_number',
							'class'=>'form-control form-inps',
								'value'=>$giftcard_info->giftcard_number)
								);?>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('giftcards_card_value').':', 'name',array('class'=>'required wide col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
								'name'=>'value',
								'size'=>'8',
							'class'=>'form-control form-inps ',
								'id'=>'value',
								'value'=>$giftcard_info->value ? to_currency_no_money($giftcard_info->value, 10) : '')
								);?>
							</div>
						</div>
						
						<div class="form-group">	
							<?php echo form_label(lang('giftcards_customer_name').':', 'customer_id',array('class'=>'wide col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('customer_id', $customers, $giftcard_info->customer_id, 'id="customer_id" class="span5"');?>
							</div>
						</div>
						
						
						<?php echo form_hidden('redirect', $redirect); ?>
				
						<div class="form-actions">
							<?php echo form_submit(array(
							'name'=>'submit',
							'id'=>'submit',
							'value'=>lang('common_submit'),
							'class'=>'btn btn-primary')
							); ?>	
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
				<script type='text/javascript'>
				
				giftcard_swipe_field($('#giftcard_number'));
				
				//validation and submit handling
				$(document).ready(function()
				{
				    setTimeout(function(){$(":input:visible:first","#giftcard_form").focus();},100);
					var submitting = false;
					$('#giftcard_form').validate({
						submitHandler:function(form)
						{
							$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
							if (submitting) return;
							submitting = true;
							$(form).ajaxSubmit({
							success:function(response)
							{
								$("#form").unmask();
								submitting = false;
								gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.giftcard_id,response.message,'gritter-item-success',false,false);
								if(response.redirect==2 && response.success)
								{
										window.location.href = '<?php echo site_url('giftcards'); ?>'
								}
							},
							<?php if(!$giftcard_info->giftcard_id) { ?>
							resetForm:true,
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
							giftcard_number:
							{
								<?php if(!$giftcard_info->giftcard_id) { ?>
								remote: 
								    { 
									url: "<?php echo site_url('giftcards/giftcard_exists');?>", 
									type: "post"
					
								    }, 
								<?php } ?>
								required:true
				
							},
							value:
							{
								required:true,
								number:true
							}
				   		},
						messages:
						{
							giftcard_number:
							{
								<?php if(!$giftcard_info->giftcard_id) { ?>
								remote:<?php echo json_encode(lang('giftcards_exists')); ?>,
								<?php } ?>
								required:<?php echo json_encode(lang('giftcards_number_required')); ?>,

							},
							value:
							{
								required:<?php echo json_encode(lang('giftcards_value_required')); ?>,
								number:<?php echo json_encode(lang('giftcards_value')); ?>
							}
						}
					});
				});
				</script>
<?php $this->load->view("partial/footer"); ?>