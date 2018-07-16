<?php $this->load->view("partial/header"); ?>
<div id="content-header">
	<h1><i class="fa fa-beaker"></i>  <?php echo lang('reports_report_input'); ?></h1> 
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
				<h5><?php echo form_label(lang('reports_date_range'), 'report_date_range_label', array('class'=>'required')); ?>
				</h5>
			</div>
			<div class="widget-content nopadding">
				<?php
				if(isset($error))
				{
					echo "<div class='error_message'>".$error."</div>";
				}
				?>
				<form  class="form-horizontal form-horizontal-mobiles">
					<div class="form-group">
						<?php echo form_label('Fixed Range :', 'range',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label   ')); ?>

						<div class="col-sm-9 col-md-9 col-lg-10">
							<input type="radio" name="report_type" id="simple_radio" value='simple' checked='checked'/>
							&nbsp;
							<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, '', 'id="report_date_range_simple" class="input-large"'); ?>
						</div>
					</div>

					<div id='report_date_range_complex'>
						<div class="form-group">
							<?php echo form_label(lang('reports_custom_range').' :', 'range',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>

							<div class="col-sm-9 col-md-9 col-lg-10">

								<input type="radio" name="report_type" id="complex_radio" value='complex' />
								&nbsp;
								<?php echo form_dropdown('start_month',$months, $selected_month, 'id="start_month" class="input-medium"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('start_day',$days, $selected_day, 'id="start_day" class="input-small"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('start_year',$years, $selected_year, 'id="start_year" class="input-medium"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('start_hour',$hours, 0, 'id="start_hour" class="input-small-mobiles"'); ?>
								:
								<?php echo form_dropdown('start_minute',$minutes, '0', 'id="start_minute" class="input-small-mobiles"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<span class="forms_to">-</span>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('end_month',$months, $selected_month, 'id="end_month" class="input-medium"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('end_day',$days, $selected_day, 'id="end_day" class="input-small"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('end_year',$years, $selected_year, 'id="end_year" class="input-medium"'); ?>
								<div class="mobile_break">&nbsp;</div>
								<?php echo form_dropdown('end_hour',$hours, 23, 'id="end_hour" class="input-small"'); ?>
								:
								<?php echo form_dropdown('end_minute',$minutes, '59', 'id="end_minute" class="input-small"'); ?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<?php echo form_label(lang('reports_sale_type').' :', 'reports_sale_type_label', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?> 
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_dropdown('sale_type',array('all' => lang('reports_all'), 'sales' => lang('reports_sales'), 'returns' => lang('reports_returns')), 'all', 'id="sale_type" class="input-medium"'); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo form_label(lang('reports_export_to_excel').' :', 'reports_export_to_excel', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?> 
						<div class="col-sm-9 col-md-9 col-lg-10">
							<input type="radio" name="export_excel" id="export_excel_yes" value='1' /> <?php echo lang('common_yes'); ?>  &nbsp;&nbsp;
							<input type="radio" name="export_excel" id="export_excel_no" value='0' checked='checked' /> <?php echo lang('common_no'); ?>
						</div>
					</div>

					<div class="form-actions">
						<?php
						echo form_button(array(
							'name'=>'generate_report',
							'id'=>'generate_report',
							'content'=>lang('common_submit'),
							'class'=>'btn btn-primary submit_button btn-large')
						);
						?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

</div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
	$(document).ready(function()
	{
		$("#generate_report").click(function()
		{
			var sale_type = $("#sale_type").val();
			var export_excel = 0;
			if ($("#export_excel_yes").prop('checked'))
			{
				export_excel = 1;
			}

			if ($("#simple_radio").prop('checked'))
			{
				window.location = window.location+'/'+$("#report_date_range_simple option:selected").val() + '/'+sale_type+'/'+export_excel;
			}
			else
			{
				var start_date = $("#start_year").val()+'-'+$("#start_month").val()+'-'+$('#start_day').val()+' '+$('#start_hour').val()+':'+$('#start_minute').val()+':00';
				var end_date = $("#end_year").val()+'-'+$("#end_month").val()+'-'+$('#end_day').val()+' '+$('#end_hour').val()+':'+$('#end_minute').val()+':00';

				window.location = window.location+'/'+start_date + '/'+ end_date + '/'+sale_type+'/'+ export_excel;
			}
		});

		$("#start_month, #start_day, #start_year, #end_month, #end_day, #end_year").change(function()
		{
			$("#complex_radio").prop('checked', true);
		});

		$("#report_date_range_simple").change(function()
		{
			$("#simple_radio").prop('checked', true);
		});

	});
</script>