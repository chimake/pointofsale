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
					<h5><?php echo form_label(lang('reports_date_range'), 'report_date_range_label', array('class'=>'required')); ?>
					</h5>
				</div>
				<div class="widget-content nopadding"><?php
					if(isset($error))
					{
						echo "<div class='error_message'>".$error."</div>";
					}
					?>
					<form action="" class="form-horizontal form-horizontal-mobiles">



						<div class="form-group">

							<?php echo form_label($specific_input_name, 'specific_input_name_label', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>

							<div class="col-sm-9 col-md-9 col-lg-10" id='controls'>
								<?php echo form_dropdown('specific_input_data',$specific_input_data, '', 'id="specific_input_data"  class="input-medium"'); ?>
							</div>

							<div>
								<div class="form-group">


								</div>
								<div class="form-group">
									<?php echo form_label(lang('reports_export_to_excel').' :', 'reports_export_to_excel', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?> 
									<div class="col-sm-9 col-md-9 col-lg-10">
										<input type="radio" name="export_excel" id="export_excel_yes" value='1' /> <?php echo lang('common_yes'); ?>
										&nbsp;&nbsp;
										<input type="radio" name="export_excel" id="export_excel_no" value='0' checked='checked' /> <?php echo lang('common_no'); ?>
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
							</div>

							<?php $this->load->view("partial/footer"); ?>

							<script type="text/javascript" language="javascript">
								$(document).ready(function()
								{
									$("#specific_input_data").select2();

									$("#generate_report").click(function()
									{
										var export_excel = 0;
										if ($("#export_excel_yes").prop('checked'))
										{
											export_excel = 1;
										}
										window.location = window.location+'/'+ $('#specific_input_data').val() + '/'+ export_excel;

									});	
								});
							</script>