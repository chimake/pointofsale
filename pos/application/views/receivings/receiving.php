<div id="content-header" class="hidden-print receiving-header">
	<h1> <i class="fa fa-cloud-download"></i>
		<?php echo lang('receivings_register'); ?> <span id="ajax-loader"><?php echo img(array('src' => base_url().'/img/ajax-loader.gif')); ?></span>
	</h1>    
</div>
		<div class="row">
			<!--Left small box-->
			<div class="sale_register_leftbox col-md-9 receiving_register_leftbox no-padd">
				<div class="row">
					<div class="col-md-8 no-padd">
						<div class="input-append">
							<?php echo form_open("receivings/add",array('id'=>'add_item_form', 'autocomplete'=> 'off')); ?>
								<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'30','placeholder'=>'Enter item name or scan barcode'));?>
									<?php echo anchor("items/view/-1/1/receiving",
									"<div class='small_button'><span>".lang('sales_new_item')."</span></div>",
									array('class'=>'btn btn-primary none width_full','title'=>lang('sales_new_item')));?>
							</form>
						</div>
					</div>
					<div class="col-md-4 no-padd">
						<?php echo form_open("receivings/change_mode",array('id'=>'mode_form', 'autocomplete'=> 'off')); ?>
						<label>
							<?php echo lang('receivings_mode') ?>
							<?php echo form_dropdown('mode',$modes,$mode, "id='mode' class='input-small'"); ?>
						</label>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="table-responsive">
						<table id="register" class="table table-bordered ">
						<thead>
							<tr>
								<th id="reg_item_del"></th>
								<th id="reg_item_name"><?php echo lang('receivings_item_name'); ?></th>
								<th id="reg_item_price"><?php echo lang('receivings_cost'); ?></th>
								<th id="reg_item_qty"><?php echo lang('receivings_quantity'); ?></th>
								<th id="reg_item_discount"><?php echo lang('receivings_discount'); ?></th>
								<th id="reg_item_total"><?php echo lang('receivings_total'); ?></th>
							</tr>
						</thead>
						<tbody id="cart_contents">
							<?php
							if(count($cart)==0)
							{
							?>
							<tr class="cart_content_area">
								<td colspan='6' style="height:60px;border:none;">
									<div  class='text-center text-warning' ><h3><?php echo lang('sales_no_items_in_cart'); ?></h3></div>
								</td>
							</tr>
							<?php
								}
								else
								{
									foreach(array_reverse($cart, true) as $line=>$item)
									{
										$cur_item_info = $this->Item->get_info($item['item_id']);?>		
										 
												
											<tr id="reg_item_top">
												<td id="reg_item_del"><?php //echo anchor("receivings/delete_item/$line",lang('common_delete'), array('class' => 'delete_item'));?>
												<?php echo anchor("receivings/delete_item/$line",'<i class="fa fa-trash-o fa fa-2x text-error"></i>', array('class' => 'delete_item'));?>
												</td>
												<td id="reg_item_name"><?php echo H($item['name']); ?></td>
											<?php if ($items_module_allowed){ ?>
												
												<td id="reg_item_price">
													<?php
														echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));															   
															echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 10),'class'=>'input-small', 'id' => 'price_'.$line));?>
														</form>
												</td>
											<?php }else{ ?>
												<td id="reg_item_price">
													<?php echo $item['price']; ?>
													<?php
													echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_hidden('price',$item['price']); ?>
													</form>
												</td>
												
											<?php }	?>
												<td id="reg_item_qty">
												<?php
													echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'class'=>'input-small', 'id' => 'quantity_'.$line));?>
													</form>
												</td>
												<td id="reg_item_discount"><?php
													echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_input(array('name'=>'discount','value'=>$item['discount'],'class'=>'input-small', 'id' => 'discount_'.$line));?>
													</form>	
												</td>
												<td id="reg_item_total"><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
											</tr>
											<tr id="reg_item_bottom" >
												<td id="reg_item_descrip_label"><?php echo lang('sales_description_abbrv').':';?></td>
												<td id="reg_item_descrip" colspan="5">
											<?php 
												echo H($item['description']);
												 
													echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_hidden('description',$item['description']);
											?>		</form>	
												</td>
										 
									</tr>
				
									<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
		<!-- Right small box  -->
		<div class="col-md-3 sale_register_rightbox">
		<br />
		<ul class="list-group">
			<li class="list-group-item nopadding">
				<!-- Cancel button -->
				<div class="sale_form_main"   <?php if(count($cart) > 0){ echo "style='visibility: visible;'";}?>>
					<?php	if(count($cart) > 0){ ?>
						<?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form', 'autocomplete'=> 'off')); ?>
							<div class='btn btn-danger width_full' id='cancel_sale_button'>
								<span>
									<?php echo lang('receivings_cancel_receiving');  ?>
								</span>
							</div>
						</form>
					<?php } ?>
				</div>
			
		<li class="list-group-item nopadding">
		<?php if($mode=="transfer") { ?>
			<!-- Location info starts here-->
					<div class="widget-box">
						<div class="widget-title">
							<span class="icon">
								<i class="fa fa-th-list"></i>
							</span>
							<h5><?php if(isset($location)) { echo "Location is added"; } else {  echo lang('receivings_select_location'); } ?></h5>
						</div>
						<div class="widget-content">
							<div id="customer_info_shell">
								<?php
								if(isset($location))
								{
										echo '<div id="customer_name">'.character_limiter($location, 25).'</div>';

											echo anchor("locations/view/$location_id/1", lang('common_edit'),  array('class'=>'btn btn-primary none','title'=>lang('suppliers_update')));
											echo '&nbsp;'.anchor("receivings/delete_location", lang('sales_detach'),array('id' => 'delete_location','class'=>'btn btn-warning'));

								}
								else
								{ ?>
									<?php echo form_open("receivings/select_location",array('id'=>'select_location_form', 'autocomplete'=> 'off')); ?>
									<?php echo form_input(array('name'=>'location','id'=>'location','size'=>'30','value'=>lang('receivings_start_typing_location_name')));?>
										</form>
										<div id="add_customer_info">
											<div id="common_or">
												<?php echo lang('common_or') ."&nbsp;"; 
												echo anchor("locations/view/-1",
												"<div class='small_button' style='margin:0 auto;'><span>".lang('receivings_new_location')."</span></div>",
												array('class'=>'btn btn-primary none','title'=>lang('receivings_new_location')));
												?>
											</div>
											
										</div>

								<?php } ?>
							</div>
						</div>
					</div>
					<!--Location info ends here-->
					<div id='sale_details'>
						<table id="sales_items_total" class="table">
							<tr class="success">
								<td class="left"><h4><?php echo lang('sales_items_in_cart'); ?>:</h4></td>
								<td class="right"><h4><?php echo $items_in_cart * -1; ?></h4></td>
							</tr>
						</table>
					</div>

					
		<?php } else { ?>
		<!-- Supplier info starts here-->
				<div class="widget-box no_left_right_border">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-th-list"></i>
						</span>
						<h5><?php if(isset($supplier)) { echo "Supplier is added"; } else {  echo lang('receivings_select_supplier'); } ?></h5>
					</div>
					<div class="widget-content">
						<div id="customer_info_shell">
							<?php
							if(isset($supplier))
							{
									echo '<div id="customer_name">'.character_limiter(H($supplier), 25).'</div>';
									
										echo anchor("suppliers/view/$supplier_id/", lang('common_edit'),  array('class'=>'btn btn-primary none','title'=>lang('suppliers_update')));
										echo '&nbsp;'.anchor("receivings/delete_supplier", lang('sales_detach'),array('id' => 'delete_supplier','class'=>'btn btn-warning'));
								
							}
							else
							{ ?>
								<?php echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form', 'autocomplete'=> 'off')); ?>
								<?php echo form_input(array('name'=>'supplier','id'=>'supplier','size'=>'30','value'=>lang('receivings_start_typing_supplier_name')));?>
									</form>
									<div id="add_customer_info">
										<div id="common_or">
											<?php echo lang('common_or') ."&nbsp;";
											echo anchor("suppliers/view/-1/1",
											"<div class='small_button' style='margin:0 auto;'><span>".lang('receivings_new_supplier')."</span></div>",
											array('id' => 'new-supplier', 'class'=>'btn btn-primary none','title'=>lang('receivings_new_location')));
											?>
											
										</div>
										
									</div>

							<?php } ?>
						</div>
					</div>
				</div>
				<!--Supplier info ends here-->	
				<div id='sale_details'>
					<table id="sales_items_total" class="table">
						<tr class="success">
							<td class="left"><h4><?php echo lang('sales_total'); ?>:</h4></td>
							<td class="right"><h4><?php echo to_currency($total); ?></h4></td>
						</tr>
					</table>
				</div>
				
			<?php } ?>

				<?php
				// Only show this part if there are Items already in the Table.
				if(count($cart) > 0){ ?>

					<div id="finish_sale">
						<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
						<?php if($mode!="transfer") { ?>

						<div id="make_payment" >
							<table id="make_payment_table" class="table">
								<tr id="mpt_top" class="success ">
									<td>
										<?php echo lang('sales_payment').':   ';?>
	
										<?php echo form_dropdown('payment_type',$payment_options, $this->config->item('default_payment_type'),'class="input-medium"');?>
									</td>
								</tr>
								<tr id="mpt_bottom" class="info">
									<td id="tender" colspan="2">
										<?php echo form_input(array('name'=>'amount_tendered','value'=>'','size'=>'10','class'=>'width_full_always','placeholder'=> lang('common_enter_amount_tendered'))); ?>
									</td>
								</tr>
							</table>
						</div>
						<?php } ?>
						<div style="padding: 0 10px 0 10px;">
							<label id="comment_label" for="comment"><?php echo lang('common_comments'); ?>:</label>
							<?php echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'value'=>'','rows'=>'4'));?>
							
							<?php if ($mode!= 'transfer' || ($mode=='transfer' && isset($location_id))) { ?>
								<?php echo "<div class='btn btn-primary btn-block btn-large'  style=\"margin: 10px 0 10px 0;\" id='finish_sale_button' >".lang('receivings_complete_receiving')."</div>"; ?>
							<?php } ?>
						</div>
					</div>
				</form>
				<?php } ?>
			</div>
			</li>
		</ul>
		</div><!-- END OVERALL-->		
		</div>
		</div>
		
<script type="text/javascript">
	<?php
	if(isset($error))
	{
		echo "gritter(".json_encode(lang('common_error')).",".json_encode($error).",'gritter-item-error',false,false);";

	}

	if (isset($warning))
	{
		echo "gritter(".json_encode(lang('common_warning')).",".json_encode($warning).",'gritter-item-warning',false,false);";

	}

	if (isset($success))
	{
		echo "gritter(".json_encode(lang('common_success')).",".json_encode($success).",'gritter-item-success',false,false);";

	}
	?>
</script>

<script type="text/javascript" language="javascript">
var submitting = false;
$(document).ready(function()
{		
	//Here just in case the loader doesn't go away for some reason
	$("#ajax-loader").hide();
	
	if (last_focused_id && last_focused_id != 'item' && $('#'+last_focused_id).is('input[type=text]'))
	{
		$('#'+last_focused_id).focus();
		$('#'+last_focused_id).select();
	}

	$(document).focusin(function(event) 
	{
		last_focused_id = $(event.target).attr('id');
	});
	
	$('#mode_form, #select_supplier_form,#select_location_form,.line_item_form').ajaxForm({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
	$('#add_item_form').ajaxForm({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: itemScannedSuccess});
	
	$( "#item" ).autocomplete({
		source: '<?php echo site_url("receivings/item_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 1,
		select: function(event, ui)
		{
 			event.preventDefault();
 			$( "#item" ).val(ui.item.value);
			$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: itemScannedSuccess});
		},
		change: function(event, ui)
		{
			if ($(this).attr('value') != '' && $(this).attr('value') != <?php echo json_encode(lang('sales_start_typing_item_name')); ?>)
			{
				$("#add_item_form").ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
			}
	
    		$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_item_name')); ?>);
		}
	});
	
	$("#cart_contents input").change(function()
	{
		$(this.form).ajaxSubmit({target: "#register_container",beforeSubmit: receivingsBeforeSubmit});
	});
	
	$('#item,#supplier,#location').click(function()
    {
    	$(this).attr('value','');
    });

	$('#mode').change(function()
	{
		$('#mode_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
	});

	$( "#supplier" ).autocomplete({
		source: '<?php echo site_url("receivings/supplier_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 1,
		select: function(event, ui)
		{			
			$( "#supplier" ).val(ui.item.value);
			$('#select_supplier_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: itemScannedSuccess});		
		}
	});

	$( "#location" ).autocomplete({
		source: '<?php echo site_url("receivings/location_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 1,
		select: function(event, ui)
		{
			$( "#location" ).val(ui.item.value);
			$('#select_location_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});			
		}
	});


    $('#supplier').blur(function()
    {
    	$(this).attr('value',<?php echo json_encode(lang('receivings_start_typing_supplier_name')); ?>);
    });

    $('#location').blur(function()
    {
    	$(this).attr('value',<?php echo json_encode(lang('receivings_start_typing_location_name')); ?>);
    });


    $("#finish_sale_form").submit(function()
	{
		<?php if($mode=="transfer" and !isset($location)) { ?>
			alert(<?php echo json_encode(lang("receivings_location_required")); ?>);
			$('#location').focus();
			return;
			<?php } ?>
			
			if (confirm(<?php echo json_encode(lang("receivings_confirm_finish_receiving")); ?>))
    		{
				//Prevent double submission of form
				$("#finish_sale_button").hide();
				return true;
    		}
			else {
				return false;
			}
	});
    $("#finish_sale_button").click(function()
    {
		$('#finish_sale_form').submit();
	});

    $("#cancel_sale_button").click(function()
    {
    	if (confirm(<?php echo json_encode(lang("receivings_confirm_cancel_receiving")); ?>))
    	{
			$('#cancel_sale_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
    	}
    });

	$('.delete_item, #delete_supplier, #delete_location').click(function(event)
	{
		event.preventDefault();
		$("#register_container").load($(this).attr('href'));	
	});

	$("input[type=text]").click(function() {
		$(this).select();
	});
	
	$("#new-supplier").click(function()
	{
		$("body").mask(<?php echo json_encode(lang('common_wait')); ?>);			
	});
});

function receivingsBeforeSubmit(formData, jqForm, options)
{
	if (submitting)
	{
		return false;
	}
	submitting = true;
	
	$("#ajax-loader").show();
	$("#finish_sale_button").hide();
}

function itemScannedSuccess(responseText, statusText, xhr, $form)
{
	setTimeout(function(){$('#item').focus();}, 10);
}

</script>