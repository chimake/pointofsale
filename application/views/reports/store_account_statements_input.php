<?php $this->load->view("partial/header"); ?>
<div id="content-header">
	<h1><i class="fa fa-beaker"></i>  <?php echo lang('reports_report_input'); ?></h1> 
</div>


<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	
</div>
<div class="clear"></div>
<div class="container-fluid">
	<div class="row">
	<div class="col-md-12">
	<div class="widget-box">
	<div class="widget-title">
	<span class="icon">
	<i class="fa fa-align-justify"></i>									
</span>
</div>
<div class="widget-content nopadding">
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>
	<form action="" class="form-horizontal form-horizontal-mobiles">
		
				
	<div class="form-group">
		<?php echo form_label(lang('customers_customer').' :', 'customer_input', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label   ')); ?> 
		<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_dropdown('customer_input',$customers	, '', 'id="customer_input" class="input-medium"'); ?>
		</div>
	</div>
	
	<div class="form-actions">
	<?php
echo form_button(array(
	'name'=>'generate_report',
	'id'=>'generate_report',
	'content'=>lang('common_submit'),
	'class'=>'btn btn-primary submit_button')
);
?>
</div>

</div>	</div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#generate_report").click(function()
	{
		window.location = window.location+'/'+$("#customer_input").val();
	});
});
</script>