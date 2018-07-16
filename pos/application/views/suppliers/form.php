<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1 ><i class="fa fa-pencil"></i> <?php if(!$person_info->person_id) { echo lang('suppliers_new'); } else { echo lang('suppliers_update');  }    ?>	</h1>
</div>


<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="container-fluid" id="form">
	<?php echo lang('common_fields_required_message'); ?>
	
	<div class="row">
	<div class="col-md-12">
	<div class="widget-box">
	<div class="widget-title">
	<span class="icon">
	<i class="fa fa-align-justify"></i>									
</span>
<h5><?php echo lang("suppliers_basic_information"); ?></h5>


</div>
<div class="widget-content nopadding">

<?php
echo form_open('suppliers/save/'.$person_info->person_id,array('id'=>'supplier_form','class'=>'form-horizontal'));
?>
<div class="row">
<div class="form-group  cmp-lbl">
<?php echo form_label(lang('suppliers_company_name').':', 'company_name', array('class'=>'required col-sm-3 col-md-3 col-lg-2 control-label')); ?>
<div class="col-sm-9 col-md-9 col-lg-10 cmp-inps">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'company_name',
		'id'=>'company_name_input',
		'value'=>$person_info->company_name)
	);?>
	</div>
</div>
</div>
<?php $this->load->view("people/form_basic_info"); ?>
<div class="form-group">
<?php echo form_label(lang('suppliers_account_number').':', 'account_number', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'account_number',
		'id'=>'account_number',
		'value'=>$person_info->account_number)
	);?>
	</div>
</div>

<?php echo form_hidden('redirect', $redirect); ?>

<div class="form-actions">

	<?php
echo form_submit(array(
	'name'=>'submitf',
	'id'=>'submitf',
	'value'=>lang('common_submit'),
	'class'=>'btn btn-primary submit_button btn-large')
	);
	?>
	
	</div>
<?php 
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
    setTimeout(function(){$(":input:visible:first","#supplier_form").focus();},100);
	var submitting = false;
	$('#image_id').imagePreview({ selector : '#avatar' }); // Custom preview container
	
	$('#supplier_form').validate({
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
				gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.person_id,response.message,'gritter-item-success',false,false);	
				if(response.redirect==1 && response.success)
				{ 
					$.post('<?php echo site_url("receivings/select_supplier");?>', {supplier: response.person_id}, function()
					{
						window.location.href = '<?php echo site_url('receivings'); ?>'
					});					
				}
				if(response.redirect==2 && response.success)
				{ 
					window.location.href = '<?php echo site_url('suppliers'); ?>'
				}

			},
			
			<?php if(!$person_info->person_id) { ?>
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
			<?php if(!$person_info->person_id) { ?>
			account_number:
			{
				remote: 
				    { 
					url: "<?php echo site_url('suppliers/account_number_exists');?>", 
					type: "post"
					
				    } 
			},
			<?php } ?>
			company_name: "required",
			first_name: "required",
			last_name: "required",
    		email: "email"
   		},
		messages: 
		{
			<?php if(!$person_info->person_id) { ?>
			account_number:
			{
				remote: <?php echo json_encode(lang('common_account_number_exists')); ?>
			},
			<?php } ?>
     		company_name: <?php echo json_encode(lang('suppliers_company_name_required')); ?>,
     		last_name: <?php echo json_encode(lang('common_last_name_required')); ?>,
     		email: <?php echo json_encode(lang('common_email_invalid_format')); ?>
		}
	});
});
</script>
<?php $this->load->view('partial/footer')?>