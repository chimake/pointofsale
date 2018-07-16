<?php $this->load->view("partial/header"); ?>
	<div id="content-header" class="hidden-print salezz-head">
		<h1> <i class="fa fa-pencil"></i> <?php echo lang('giftcards_new');   ?>	</h1>
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
							<h5><?php echo lang("giftcards_basic_information"); ?></h5>
						</div>
						<div class="widget-content nopadding">
						
<?php echo form_open('items/save/'.$item_id,array('id'=>'giftcard_form','class'=>'form-horizontal')); ?>

<div class="control-group">
<?php echo form_label(lang('giftcards_giftcard_number').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'name'=>'description',
		'size'=>'8',
		'id'=>'description',
		'class'=>'form-control',
		)
	);?>
	</div>
    <div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>
<div class="control-group">

<?php echo form_label(lang('giftcards_card_value').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'name'=>'unit_price',
		'size'=>'8',
		'class'=>'form-control',
		'id'=>'unit_price')
	);?>
	</div>
</div>
<?php echo form_hidden('redirect', 1); ?>
<?php echo form_hidden('sale_or_receiving', 'sale'); ?>
<?php echo form_hidden('is_service', 1); ?>
<?php echo form_hidden('sale', 1); ?>
<?php echo form_hidden('item_number', lang('sales_giftcard')); ?>
<?php echo form_hidden('name', lang('sales_giftcard')); ?>
<?php echo form_hidden('category', lang('sales_giftcard')); ?>
<?php echo form_hidden('quantity', ''); ?>
<?php echo form_hidden('allow_alt_description', '1'); ?>
<?php echo form_hidden('is_serialized', '1'); ?>
<?php echo form_hidden('override_default_tax', '1'); ?>

<div class="clear"></div>
<div class="form-actions form-actionzsz">
<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>lang('common_submit'),
	'class'=>'btn btn-primary')
);
?>
</div>
<div class="clear"></div>
<?php
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	giftcard_swipe_field($('#description'));
	
    setTimeout(function(){$(":input:visible:first","#giftcard_form").focus();},100);
	var submitting = false;
	$('#giftcard_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).ajaxSubmit({
			success:function(response)
			{
				$('#spin').addClass('hidden');
				submitting = false;
				gritter(<?php echo json_encode(lang('common_success')); ?>,response.message,'gritter-item-success',false,false);
				if(response.redirect==1)
				{ 
					if (response.sale_or_receiving == 'sale')
					{
						$.post('<?php echo site_url("sales/add");?>', {item: response.item_id}, function()
						{
							window.location.href = '<?php echo site_url('sales'); ?>'
						});
					}
				}
			},
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
			description:
			{
				required:true,
				remote: 
				    { 
					url: "<?php echo site_url('giftcards/giftcard_exists');?>", 
					type: "post"
					
				    } 
			},
			unit_price:
			{
				required:true,
				number:true
			}
   		},
		messages:
		{
			description:
			{
				required:<?php echo json_encode(lang('giftcards_number_required')); ?>,
				remote:<?php echo json_encode(lang('giftcards_exists')); ?>
			},
			unit_price:
			{
				required:<?php echo json_encode(lang('giftcards_value_required')); ?>,
				number:<?php echo json_encode(lang('giftcards_value')); ?>
			}
		}
	});
});
</script>