<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1><i class="fa fa-upload"></i>Opening amount</h1>
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
					<h5><?php echo lang('sales_opening_amount_desc'); ?></h5>
				</div>
				<div class="widget-content nopadding ">
					<ul class="text-error" id="error_message_box"></ul>
					<?php echo form_open('sales', array('id'=>'opening_amount_form','class'=>'form-horizontal')); ?>
				
                <div class="control-group controll-croups1">
                <?php echo form_label(lang('sales_opening_amount').':', 'opening_amount',array('class'=>'control-label ss')); ?>
                    <?php echo form_input(array(
                        'name'=>'opening_amount',
                        'id'=>'opening_amount',
                        'value'=>'')
                    );?>
                    </div>

                    <div style="clear:both;"></div>
						  
                    <div class="form-actions form-actions">

                    <div class="form-actions form-actions1 ">

                    <?php echo form_submit(array(
							'name'=>'submit',
							'id'=>'submit',
							'value'=>lang('common_submit'),
							'class'=>'btn btn-primary ')
						);
						?>
                    </div>
                    <div style="clear:both;"></div>

                    
                </div>
                
				
				</div>
                </div>
				
<?php
echo form_close();
?>
</div>
<?php $this->load->view('partial/footer.php'); ?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$("#opening_amount").focus();
	
	var submitting = false;

	$('#opening_amount_form').validate({
		rules:
		{
			opening_amount: {
				required: true,
				number: true
			}
   		},
		errorClass: "text-danger",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
		},
		messages: {
	   		closing_amount: {
				required: <?php echo json_encode(lang('sales_amount_required')); ?>,
				number: <?php echo json_encode(lang('sales_amount_number')); ?>
			}
   		}
	});
});
</script>