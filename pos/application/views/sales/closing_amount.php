<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print salezz-head">
	<h1> <i class="fa fa-upload"></i>  Closing amount	</h1>
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
				<h5><?php echo lang('sales_closing_amount_desc'); ?></h5>
			</div>
			<div class="widget-content nopadding">
				<ul class="text-error" id="error_message_box"></ul><?php
				echo form_open('sales/closeregister' . $continue, array('id'=>'closing_amount_form','class'=>'form-horizontal'));
				?>

				<h3 class="text-left text-success text-center"><?php echo sprintf(lang('sales_closing_amount_approx'), $closeout); ?></h3>
				<br />
				<div class="control-group controll-croups1">
					<?php echo form_label(lang('sales_closing_amount').':', 'closing_amount',array('class'=>'control-label')); ?>
					<?php echo form_input(array(
						'name'=>'closing_amount',
						'id'=>'closing_amount',
						'value'=>'')
						);?>
					</div>
					<div class="form-actions form-actions1">
						<?php
						echo form_submit(array(
							'name'=>'submit',
							'id'=>'submit',
							'value'=>lang('common_submit'),
							'class'=>'btn btn-primary ')
						);
						?>
					</div>
					<div style="clear:both;"></div>
				</div></div>
				<?php
				echo form_close();
				?>
			</div>
			<?php $this->load->view('partial/footer.php'); ?>
			<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$("#closing_amount").focus();
	
	var submitting = false;

	$('#closing_amount_form').validate({
		rules:
		{
			closing_amount: {
				required: true,
				number: true
			}
		},
		messages:
		{
			closing_amount: {
				required: <?php echo json_encode(lang('sales_amount_required')); ?>,
				number: <?php echo json_encode(lang('sales_amount_number')); ?>
			}
		}
	});
});
</script>