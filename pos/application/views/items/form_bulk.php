<div id="content-header" class="hidden-print bulk-pop">
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo lang("items_edit_multiple_items"); ?> </h5>
                    <span style="float:right; padding:4px 7px 0 0;"><button type="button" class="close" data-dismiss="modal">×</button></span>
				</div>
				<div class="widget-content nopadding">
					<div class='modal-body modal-body-popup'>
						<?php echo form_open('items/bulk_update/',array('id'=>'bulk_item_form','class'=>'form-horizontal')); ?>
							<div class="control-group control-group-popup">	
							<?php echo form_label(lang('items_category').':', 'category',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_input(array(
									'name'=>'category',
									'id'=>'category')
								);?>
								</div>
							</div>

							<div class="control-group control-group-popup">	
							<?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'control-label')); ?>
								<div class="controls bulks control-group-popup">
								<?php echo form_dropdown('supplier_id', $suppliers, '','class="form-control"');?>
								</div>
							</div>

							<div class="form-group">
							<?php echo form_label(lang('items_promo_start_date').':', 'start_date',array('class'=>'control-label text-info wide')); ?>
							<div class="controls controls-popup">
					   
			
						    <div class="input-group date datepicker" data-date="" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
		  					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<?php echo form_input(array(
						        'name'=>'start_date',
						        'id'=>'start_date',
								'class'=>'form-control',
						        'value' => '')
						    );?> </div>

						    </div>
						</div>


							<div class="form-group">
							<?php echo form_label(lang('items_promo_end_date').':', 'end_date',array('class'=>'control-label text-info wide')); ?>
							<div class="controls controls-popup">
					   
			
						    <div class="input-group date datepicker" data-date="" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
		  					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<?php echo form_input(array(
						        'name'=>'end_date',
						        'id'=>'end_date',
								'class'=>'form-control',
						        'value'=>'')
						    );?> </div>

						    </div>
						</div>


							<div class="control-group control-group-popup">	
							<?php echo form_label(lang('items_override_default_tax').':', 'override_default_tax',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_dropdown('override_default_tax', $override_default_tax_choices, '', 'id="override_default_tax"');?>
								</div>
							</div>
							
							
							<div id="tax_container" class="tax-container hidden">	
							
								<div class="control-group control-group-popup">	
								<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'control-label')); ?>
									<div class="controls controls-popup">
									<?php echo form_input(array(
										'name'=>'tax_names[]',
										'id'=>'tax_name_1',
										'size'=>'8',
										'placeholder' =>lang('common_tax_name'),
									));?>

									<?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_1',
										'size'=>'3',
										'placeholder' =>lang('items_tax_percent'),
									));?>
									%
										<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
									</div>
								</div>

								<div class="control-group control-group-popup">	
								<?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'control-label')); ?>
									<div class="controls controls-popup">
									<?php echo form_input(array(
										'name'=>'tax_names[]',
										'id'=>'tax_name_2',
										'size'=>'8',
										'placeholder' =>lang('common_tax_name'),
									));?>

									<?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_2',
										'size'=>'3',
										'placeholder' =>lang('items_tax_percent'),
									));?>
									%
									<?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative'] ? true : false); ?>
									<span class="cumulative_label">
									<?php echo lang('common_cumulative'); ?>
								    </span>
									</div>
								</div>
								
								<div class="control-group control-group-popup">	
								<?php echo form_label(lang('items_tax_3').':', 'tax_percent_3',array('class'=>'control-label')); ?>
									<div class="controls controls-popup">
									<?php echo form_input(array(
										'name'=>'tax_names[]',
										'id'=>'tax_name_3',
										'placeholder' =>lang('common_tax_name'),
										'size'=>'8',
									));?>

									<?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_3',
										'size'=>'3',
										'placeholder' =>lang('items_tax_percent'),
									));?>
									%
										<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
									</div>
								</div>
								
								<div class="control-group control-group-popup">	
								<?php echo form_label(lang('items_tax_4').':', 'tax_percent_4',array('class'=>'control-label')); ?>
									<div class="controls controls-popup">
									<?php echo form_input(array(
										'name'=>'tax_names[]',
										'id'=>'tax_name_4',
										'size'=>'8',
										'placeholder' =>lang('common_tax_name'),
									));?>

									<?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_4',
										'size'=>'3',
										'placeholder' =>lang('items_tax_percent'),
									));?>
									%
										<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
									</div>
								</div>
								<div class="control-group control-group-popup">	
								<?php echo form_label(lang('items_tax_5').':', 'tax_percent_5',array('class'=>'control-label')); ?>
									<div class="controls controls-popup">
									<?php echo form_input(array(
										'name'=>'tax_names[]',
										'id'=>'tax_name_5',
										'size'=>'8',
										'placeholder' =>lang('common_tax_name'),
									));?>

									<?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_5',
										'size'=>'3',
										'placeholder' =>lang('items_tax_percent'),
									));?>
									%
										<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
									</div>
								</div>
								
							</div>
							<div class="control-group control-group-popup">
							<?php echo form_label(lang('common_prices_include_tax').':', 'tax_included',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_dropdown('tax_included', $tax_included_choices);?>
								</div>
							</div>

							<div class="control-group control-group-popup">
							<?php echo form_label(lang('items_is_service').':', 'is_service',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_dropdown('is_service', $is_service_choices);?>
								</div>
							</div>
							
							<div class="control-group control-group-popup">	
							<?php echo form_label(lang('items_reorder_level').':', 'reorder_level',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_input(array(
									'name'=>'reorder_level',
									'id'=>'reorder_level')
								);?>
								</div>
							</div>

							<div class="control-group control-group-popup">	
							<?php echo form_label(lang('items_location').':', 'location',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_input(array(
									'name'=>'location',
									'id'=>'location')
								);?>
								</div>
							</div>

							<div class="control-group control-group-popup">
							<?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'control-label')); ?>
								<div class="controls controls-popup">
								<?php echo form_dropdown('allow_alt_description', $allow_alt_desciption_choices);?>
								</div>
							</div>

						<div class="control-group control-group-popup">
						<?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'control-label')); ?>
							<div class="controls controls-popup">
								<?php echo form_dropdown('is_serialized', $serialization_choices);?>
							</div>
						</div>
						<div class='modal-footer'>
							<div class="form-controls">
								<?php
								echo form_submit(array(
								'name'=>'submit',
								'id'=>'submit',
								'value'=>lang('common_submit'),
								'class'=>'btn btn-primary')
								); ?>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
    setTimeout(function(){$(":input:visible:first","#bulk_item_form").focus();},100);
	
	$('#bulk_item_form .datepicker').datepicker({
		format: <?php echo json_encode(get_js_date_format()); ?>
	});

	$( "#category" ).autocomplete({
		source: "<?php echo site_url('items/suggest_category');?>",
		delay: 10,
		autoFocus: false,
		minLength: 0
	});
	
	$("#override_default_tax").change(function()
	{
		if ($(this).val() == '1')
		{
			$("#tax_container").removeClass('hidden');
		}
		else
		{
			$("#tax_container").addClass('hidden');
		}
	});

	var submitting = false;
	
	$('#bulk_item_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;			
			if(confirm(<?php echo json_encode(lang('items_confirm_bulk_edit')); ?>))
			{
				//Get the selected ids and create hidden fields to send with ajax submit.
				var selected_item_ids=get_selected_values();
				for(k=0;k<selected_item_ids.length;k++)
				{
					$(form).append("<input type='hidden' name='item_ids[]' value='"+selected_item_ids[k]+"' />");
				}
				
				$("#bulk_item_form").mask(<?php echo json_encode(lang('common_wait')); ?>);
				submitting = true;
				$(form).ajaxSubmit({
				success:function(response)
				{
					post_bulk_form_submit(response);
					$("#bulk_item_form").unmask();
					submitting = false;
				},
				dataType:'json'
				});
			}

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			"tax_percents[]":
			{
				number:true
			},
			reorder_level:
			{
				number:true
			}
   		},
		messages: 
		{
			"tax_percents[]":
			{
				number:<?php echo json_encode(lang('items_tax_percent_number')); ?>
			},
			reorder_level:
			{
				number:<?php echo json_encode(lang('items_reorder_level_number')); ?>
			}
		}
	});
});


</script>