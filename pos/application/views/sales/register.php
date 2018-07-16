<div id="content-header" class="hidden-print sales_header_container">
	<h1 class="headigs"> <i class="icon fa fa-shopping-cart"></i>
		<?php echo lang('sales_register'); ?> <span id="ajax-loader"><?php echo img(array('src' => base_url().'/img/ajax-loader.gif')); ?></span>
		<?php if($this->sale_lib->get_change_sale_id()) { ?>
		<small>
			<?php echo lang('sales_editing_sale'); ?> <b> <?php echo $this->config->item('sale_prefix').' '.$this->sale_lib->get_change_sale_id(); ?> </b> 
		</small>
		<?php } ?>
	</h1>
	
    
    
</div>

<div class="clear"></div>
	<!--Left small box-->
	<div class="row">
		<div class="sale_register_leftbox col-md-9">
			<div class="row forms-area">
				<?php if ($mode != 'store_account_payment') { ?>
						<div class="col-md-8 no-padd">
							<div class="input-append">
								<?php echo form_open("sales/add",array('id'=>'add_item_form','class'=>'form-inline', 'autocomplete'=> 'off')); ?>
								<?php echo form_input(array('name'=>'item','id'=>'item','class'=>'input-xlarge', 'accesskey' => 'i', 'placeholder' => lang('sales_start_typing_item_name')));?>
								<?php echo anchor("items/view/-1/1/sale",
									lang('sales_new_item'),
									array('class'=>'btn btn-primary none new_item_btn','title'=>lang('sales_new_item')));?>
								<?php echo anchor("sales/suspended/",
									"<div class='small_button'>Suspended Sales</div>",
									array('class'=>'btn btn-primary none suspended_sales_btn','title'=>"Suspended Sales"));
									?>
								</form>
							</div>
						</div>					
					<?php } ?>
							
				<div class="col-md-4 no-padd">
					<?php echo form_open("sales/change_mode",array('id'=>'mode_form', 'autocomplete'=> 'off')); ?>
						<label ><?php echo lang('sales_mode') ?>
							<?php echo form_dropdown('mode',$modes,$mode,'id="mode" class=""'); ?>
						</label>
					</form>
					<?php if ($mode == 'store_account_payment' && isset($customer_balance)) {?>
						<h3><?php echo lang('customers_balance').': '.$customer_balance; ?></h3>
					<?php } ?>
					
			</div>
	
			</div>
		
		<div class="row">
			
			<?php if ($mode != 'store_account_payment') 
			{ ?>
			<div class="table-responsive">
				<table id="register" class="table table-bordered">

					<thead>
						<tr>
							<th ></th>
							<th class="item_name_heading" ><?php echo lang('sales_item_name'); ?></th>
							<th class="sales_item sales_items_number"><?php echo lang('sales_item_number'); ?></th>
							<th class="sales_stock"><?php echo lang('sales_stock'); ?></th>
							<th class="sales_price"><?php echo lang('sales_price'); ?></th>
							<th class="sales_quality"><?php echo lang('sales_quantity'); ?></th>
							<th class="sales_discount"><?php echo lang('sales_discount'); ?></th>
							<th ><?php echo lang('sales_total'); ?></th>
						</tr>
					</thead>
					<tbody id="cart_contents" class="sa">
						<?php if(count($cart)==0)	{ ?>
						<tr class="cart_content_area">
							<td colspan='8'>
								<div class='text-center text-warning' > <h3><?php echo lang('sales_no_items_in_cart'); ?></h3></div>
							</td>
						</tr>
						<?php 
						}
						else
						{
							foreach(array_reverse($cart, true) as $line=>$item)	
							{
								$cur_item_location_info = isset($item['item_id']) ? $this->Item_location->get_info($item['item_id']) : $this->Item_kit_location->get_info($item['item_kit_id']);
								?>
								<tr id="reg_item_top" bgcolor="#eeeeee" >
									<td><?php echo anchor("sales/delete_item/$line",'<i class="fa fa-trash-o fa fa-2x text-error"></i>', array('class' => 'delete_item'));?></td>
									<td class="text text-success"><a href="<?php echo isset($item['item_id']) ? site_url('items/view_modal/'.$item['item_id']) : site_url('item_kits/view_modal/'.$item['item_kit_id']) ; ?>" data-toggle="modal" data-target="#myModal" ><?php echo H($item['name']); ?></a></td>
									<td class="text text-info sales_item" id="reg_item_number"><?php echo isset($item['item_id']) ? H($item['item_number']) : H($item['item_kit_number']); ?></td>
									<td class="text text-warning sales_stock" id="reg_item_stock" ><?php echo property_exists($cur_item_location_info, 'quantity') ? to_quantity($cur_item_location_info->quantity) : ''; ?></td>

									<?php if ($this->Employee->has_module_action_permission('sales', 'edit_sale_price', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
									<td>
										<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	 
										 
										
										echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 10),'class'=>'input-small', 'id' => 'price_'.$line));?>
										 
										</form>
									 
									</td>
									<?php }else{ 
									?><td>
									<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	 
											
											echo $item['price']; 
											echo form_hidden('price',$item['price']); ?>
										
										
										</form>
									
									</td>
									<?php }	?>

									<td>
										<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	
											
												if(isset($item['is_serialized']) && $item['is_serialized']==1){
													echo to_quantity($item['quantity']);
													echo form_hidden('quantity',to_quantity($item['quantity']));
												}else{
													echo form_input(array('name'=>'quantity','value'=>to_quantity($item['quantity']),'class'=>'input-small', 'id' => 'quantity_'.$line));
												}?>
											 
										</form>
									</td>

									<?php if ($this->Employee->has_module_action_permission('sales', 'give_discount', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
									<td>
										<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
											echo form_input(array('name'=>'discount','value'=>$item['discount'],'class'=>'input-small', 'id' => 'discount_'.$line));?>
										  </form>
									</td>
									<?php }else{ ?>
									<td>
										<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	
											 	
											echo $item['discount']; 
											echo form_hidden('discount',$item['discount']);
								 ?>
										</form>
									</td>
									 
									<?php }	?>


									<td class="text text-main"><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
								</tr>

								<tr id="reg_item_bottom">
									<td ><?php echo lang('sales_description_abbrv').':';?></td>
									<td  colspan="4" class="edit_discription">
										<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	 
											 
										
										if(isset($item['allow_alt_description']) && $item['allow_alt_description']==1){
											echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20', 'id' => 'description_'.$line, 'class' =>'description'));
										}else{
											if ($item['description']!=''){
												echo $item['description'];
												echo form_hidden('description',$item['description']);
											}else{
												echo 'None';
												echo form_hidden('description','');
											}
										}?>
										</form>
									</td>
									<td >
										
										<?php if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard')){
											echo lang('sales_serial').':';
										}?>
									</td>
									<td colspan="2">
										<?php
										echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	 
											 
											
										    if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard'))	{
												echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'], 'class' => 'serial_item','size'=>'20', 'id' => 'serialnumber_'.$line));
											}else{
												echo form_hidden('serialnumber', '');
											}?>
										</form>
									</td>
								</tr>

								
							<?php
						}
					}?>
				</tbody>
			</table>
			</div>
		<?php } 
		else 
		{ /*Store Account Mode*/ ?>
			<table id="register"  class="tablesorter table table-bordered ">

				<thead>
					<tr>
						<th ><?php echo lang('sales_item_name'); ?></th>
						<th ><?php echo lang('sales_payment_amount'); ?></th>
					</tr>
				</thead>
				<tbody id="cart_contents">
					<?php
					foreach(array_reverse($cart, true) as $line=>$item)	
					{
						$cur_item_location_info = isset($item['item_id']) ? $this->Item_location->get_info($item['item_id']) : $this->Item_kit_location->get_info($item['item_kit_id']);
						?>
						 							
						
						
					 

						<tr id="reg_item_top" bgcolor="#eeeeee" >
							<td class="text text-success"><a href="<?php echo site_url('items/view_modal/'.$item['item_id']) ; ?>" data-toggle="modal" data-target="#myModal" ><?php echo H($item['name']); ?></a></td>
							<td>
								<?php
								echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off')); 	

									echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 10),'class'=>'input-small', 'id' => 'price_'.$line));
									
									echo form_hidden('quantity',to_quantity($item['quantity']));
									echo form_hidden('description','');
									echo form_hidden('serialnumber', '');
								?>
							
								</form>		
							</td>
						</tr>
						
						
				 
				<?php } /*Foreach*/?>
			</tbody>
		</table>
			
		<?php } ?>
		<ul class="list-inline pull-left">
			<?php if ($this->config->item('track_cash')) { ?>
			<li>
				<?php echo anchor(site_url('sales/closeregister?continue=home'), lang('sales_close_register'),array('class'=>'btn btn-primary')); ?>
			</li>
			<?php } ?>
			<li>
				
			<?php if ($mode != 'store_account_payment') { ?>
				<?php if ($this->Employee->has_module_action_permission('giftcards', 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
					<?php echo 
					anchor("sales/new_giftcard",
						lang('sales_new_giftcard'),
						array('class'=>'btn btn-primary ', 
							'title'=>lang('sales_new_giftcard')));
							?>
						</li>
				<?php } ?>
				
				<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_sales_generator', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<li>
						<?php echo 
						anchor("reports/sales_generator",
						lang('sales_search_reports'),
						array('class'=>'btn btn-primary ', 
							'title'=>lang('sales_search_reports')));
						?> 
						</li>
					<?php } ?>
			<?php } ?>
				 
			</ul>				
				<?php 
				if ($this->Employee->has_module_action_permission('sales', 'give_discount', $this->Employee->get_logged_in_employee_info()->person_id) && $mode != 'store_account_payment'){ ?>
					<ul class="list-inline pull-right" id="global_discount">
						<li>
							<?php 
							echo form_open("sales/discount_all", array('id' => 'discount_all_form', 'autocomplete'=> 'off'));
							echo '<label id="discount_all_percent_label" for="discount_all_percent">';
							echo lang('sales_global_sale_discount').': ';
							echo '</label>';
							echo '&nbsp;&nbsp;';
							echo form_input(array('name'=>'discount_all_percent','value'=> '','size'=>'3', 'id' => 'discount_all_percent'));
							echo '%&nbsp;&nbsp;';
							echo form_submit('submit_discount_form',lang('common_submit'),'class="btn btn-primary"');
							?>
							</form>
		
						</li>
					</ul>
				<?php } ?>							
						</div>
						
						
						<?php
						if (isset($customer))
						{
						?>
						<div class="row hidden-xs">
							<h1><?php echo lang('sales_recent_sales').' '.H($customer);?></h1>	
							<table id="recent_sales" class="table">
								<tr>
									<th align="center"><?php echo lang('items_date');?></th>
									<th align="center"><?php echo lang('sales_amount');?></th>
									<th align="center"><?php echo lang('reports_items_purchased');?></th>
									<th align="center"><?php echo lang('sales_receipt');?></th>
								</tr>
								
								<?php foreach($recent_sales as $sale) {?>
									<tr>
										<td align="center"><?php echo date(get_date_format(), strtotime($sale['sale_date']));?></td>
										<td align="center"><?php echo to_currency($sale['total']);?></td>
										<td align="center"><?php echo to_quantity($sale['items_purchased']);?></td>
										<td align="center"><?php echo anchor('sales/receipt/'.$sale['sale_id'], lang('sales_receipt'), array('target' =>'_blank')); ?></td>
									</tr>
								<?php } ?>
							</table>
						</div>
						<?php	
						}
						?>	

					</div>
					<!-- Right small box  -->
				<div class="col-md-3 sale_register_rightbox">
					<ul class="list-group">
						<li class="list-group-item nopadding">
							<!-- Cancel and suspend buttons -->
							<div <?php if(count($cart) > 0){ echo "class='sale_form_main'";}?>>
								<?php if(count($cart) > 0){ ?>
								<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form', 'autocomplete'=> 'off')); ?>
								<?php if ($mode != 'store_account_payment') { ?>
									<input type="button" class="btn btn-warning warning-buttons" id="suspend_sale_button" value="<?php echo lang('sales_suspend_sale');?>" />
								<?php } ?>
								<input type="button" class="btn btn-danger button_dangers" id="cancel_sale_button" value="<?php echo lang('sales_cancel_sale');?>" />
							</form>
							<?php } ?>
						</div>
					</li>
					<li class="list-group-item item_tier">
						<!-- Customer info starts here-->
						<h5 class="customer-basic-information"><?php if(isset($customer)) { echo lang('customers_basic_information'); } else {  echo lang('sales_select_customer'); } ?></h5>
						<div class="row nomargin">
						<div class="clearfix" id="customer_info_shell">
							<?php if(isset($customer)) { 
								$full_width_col = "full_width_imporant";
								if ($avatar != '' )
								{
								 $full_width_col = "";
								?>
									
									<div id="customer-avatar">
										<img src='<?php echo $avatar; ?>' alt="Customer" class=' img-polaroid ' width="100px;" />
									</div>
							<?php	
								}
							?>
								<div id="customer-info" class=" <?php echo $full_width_col?>">
								<div class="clear">
									<ul class="list-unstyled">
										<li><strong><?php echo character_limiter(H($customer), 25); ?></strong></li>
										<?php if(!empty($customer_email)) {
											echo "<li>";
											echo form_checkbox(array(
												'name'        => 'email_receipt',
												'id'          => 'email_receipt',
												'value'       => '1',
												'class'       => 'email_receipt_checkbox',
												'checked'     => (boolean)$email_receipt,
												)).'&nbsp;'.lang('sales_email_receipt').': <i>'.character_limiter(H($customer_email), 25).'</i>';
											echo "</li>";
										} ?>
									</ul>
								</div>
						<?php										
							echo anchor("customers/view/$customer_id/1", lang('common_edit'),  array('id' => 'edit_customer','class'=>'none btn-sm btn-primary ','title'=>lang('customers_update'))).'';
							echo ''.anchor("sales/delete_customer", lang('sales_detach'),array('id' => 'delete_customer','class'=>'btn-sm btn-warning'));
							
							?>
							</div>
							
							
							
					<?php
						}
						else
							{ ?>
						<?php echo form_open("sales/select_customer",array('id'=>'select_customer_form', 'autocomplete'=> 'off')); ?>
						<?php echo form_input(array('name'=>'customer','id'=>'customer','size'=>'30','value'=>lang('sales_start_typing_customer_name'), 'placeholder'=>lang('sales_start_typing_customer_name'),  'accesskey' => 'c'));?>
					</form>
					<div id="add_customer_info">
							<div id="common_or" class="common_or">
								<?php echo lang('common_or'); ?>
								<?php 
								echo anchor("customers/view/-1/1",
									"<div class='small_button'> <span>".lang('sales_new_customer')."</span> </div>", array('class'=>'btn btn-primary none','title'=>lang('sales_new_customer'), 'id' => 'new-customer'));
								?>
							</div>
					</div>

						<?php }
						
						if (count($tiers) > 1) 
						{
							echo "<div class=\"tiers_main clear\">";
							echo '<h3 class="items_tiers">'.lang('items_tiers'),'</h3>';
							// margin: 12px 0px 0px 12px;
							if ($this->Employee->has_module_action_permission('sales', 'edit_sale_price', $this->Employee->get_logged_in_employee_info()->person_id))
							{								
								echo form_dropdown('tier_id', $tiers, $selected_tier_id, 'id="tier_id"');
							}
							else
							{
								echo "<div class='item_tier_no_edit text-info'>: ".H($tiers[$selected_tier_id])."</div>";
							}
							echo "<div class=\"clear\"></div>";
							echo "</div>";
							 
						}
						?>
					</div>
				</div>
				</li>
				<li class="list-group-item spacing">
				</li>
				<li class="list-group-item nopadding">

					<div id='sale_details'>
						<table id="sales_items" class="table">
							<tr class="warning">
								<td class="left"><?php echo lang('sales_items_in_cart'); ?>:</td>
								<td class="right"><?php echo $items_in_cart; ?></td>
							</tr>
							<?php foreach($payments as $payment) {?>
							<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
							<tr class="error">
								<td class="left"><?php echo $payment['payment_type']. ' '.lang('sales_balance') ?>:</td>
								<td class="right"><?php echo to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type']))) - $payment['payment_amount']);?></td>
							</tr>
							<?php }?>
							<?php }?>
							<tr class="info">
								<td class="left"><?php echo lang('sales_sub_total'); ?>:</td>
								<td class="right"><?php echo to_currency($subtotal); ?></td>
							</tr>
							<?php foreach($taxes as $name=>$value) { ?>
							<tr class="color1">
								<td class="left"><?php echo $name; ?>:</td>
								<td class="right"><?php echo to_currency($value); ?></td>
							</tr>
							<?php }; ?>
							<tr class="success">
								<td ><h3 class="sales_totals"><?php echo lang('sales_total'); ?>:</h3></td>
								<td ><h3 class="currency_totals"><?php echo to_currency($total); ?></h3></td>
							</tr>
						</table>
					</div>
				</li>
				<li class="list-group-item spacing">
				</li>

				<li class="list-group-item nopadding">
					<?php
					// Only show this part if there are Items already in the sale.
					if(count($cart) > 0){ ?>

					<div id="Payment_Types">

						<?php
							// Only show this part if there is at least one payment entered.
						if(count($payments) > 0)
						{
						?>
							<table id="register" class="table">
								<thead>
									<tr>
										<th id="pt_delete"></th>
										<th id="pt_type"><?php echo lang('sales_type'); ?></th>
										<th id="pt_amount"><?php echo lang('sales_amount'); ?></th>


									</tr>
								</thead>
								<tbody id="payment_contents">
									<?php
									foreach($payments as $payment_id=>$payment)
									{
										?>
										<tr class="warning">
											<td id="pt_delete"><?php echo anchor("sales/delete_payment/$payment_id",'['.lang('common_delete').']', array('class' => 'delete_payment'));?></td>
											<td id="pt_type"><?php echo $payment['payment_type']; ?> </td>
											<td id="pt_amount"><?php echo  to_currency($payment['payment_amount']); ?>  </td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
							<?php } ?>

							<table id="amount_due" class="table">
								<tr class="<?php if($payments_cover_total) { echo 'success'; } else { echo 'error'; }?>">
									<td>
										<h4 class="sales_amount_due"><?php echo lang('sales_amount_due'); ?>:</h4>
									</td>
									<td>
										<h3 class="amount_dues"><?php echo to_currency($amount_due); ?></h3>
									</td>
								</tr>
							</table>

							<div id="make_payment">
								<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form', 'autocomplete'=> 'off')); ?>
								<table id="make_payment_table" class="table">
									<tr id="mpt_top">
										<td id="add_payment_text">
											<?php echo lang('sales_add_payment'); ?>:
										</td>
										<td>
											<?php echo form_dropdown('payment_type',$payment_options,$this->config->item('default_payment_type'), 'id="payment_types" class="input-medium"');?>
										</td>
									</tr>
									<tr id="mpt_bottom" >
										<td id="tender" colspan="2">
											<div class="input-append">
												<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>to_currency_no_money($amount_due),'class'=>'input-medium input_mediums', 'accesskey' => 'p'));	?>
												<input type="button" class="btn btn-primary" id="add_payment_button" value="<?php echo lang('sales_add_payment'); ?>" />
											</div>

										</td>
									</tr>
									 
								</table>

							</form>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<?php
					echo '<label id="comment_label" for="comment">';
					echo lang('common_comments');
					echo ':</label><br />';
					echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'value'=>$comment,'rows'=>'4',  'accesskey' => 'o'));
					echo '<br />';
					echo '<label id="show_comment_on_receipt_label" for="show_comment_on_receipt" class="checkbox">';
					echo lang('sales_comments_receipt');
					echo form_checkbox(array(
						'name'=>'show_comment_on_receipt',
						'id'=>'show_comment_on_receipt',
						'value'=>'1',
						'checked'=>(boolean)$show_comment_on_receipt)
					);
					echo '</label>  ';


					// Only show this part if there is at least one payment entered.
					if((count($payments) > 0 && !is_sale_integrated_cc_processing())){?>
					<div id="finish_sale">
						<?php echo form_open("sales/complete",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
						<?php							 
						if ($payments_cover_total)
						{
							echo "<input type='button' class='btn btn-success btn-large btn-block' id='finish_sale_button' value='".lang('sales_complete_sale')."' />";
						}
						?>
					</div>
				</form>
				<?php }elseif(count($payments) > 0)	{?>
				<div id="finish_sale">
					<?php echo form_open("sales/start_cc_processing",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
					<?php							 
					if ($payments_cover_total || (is_sale_integrated_cc_processing()))
					{
						echo "<input type='button' class='btn btn-success btn-large btn-block' id='finish_sale_button' value='".lang('sales_process_credit_card')."' />";

						if (is_sale_integrated_cc_processing())
						{
							if (isset($customer) && $customer_cc_token && $customer_cc_preview)
							{
								echo '<label id="sales_use_saved_cc_label" for="use_saved_cc_info" class="checkbox">';
								echo lang('sales_use_saved_cc_info'). ' '.$customer_cc_preview;
								echo form_checkbox(array(
									'name'=>'use_saved_cc_info',
									'id'=>'use_saved_cc_info',
									'value'=>'1',
									'checked'=>(boolean)$use_saved_cc_info)
								);
								echo '</label>  ';
							}
							elseif(isset($customer))
							{
								echo '<label id="sales_save_credit_card_label" for="save_credit_card_info" class="checkbox">';
								echo lang('sales_save_credit_card_info');
								echo form_checkbox(array(
									'name'=>'save_credit_card_info',
									'id'=>'save_credit_card_info',
									'value'=>'1',
									'checked'=>(boolean)$save_credit_card_info)
								);
								echo '</label>  ';
							}
						}
					}
					?>
				</div>
			</form>
			<?php }
			?>
			<?php
			if($this->sale_lib->get_change_sale_id()) {
				echo '<br />';
				echo '<label id="comment_label" for="change_sale_date_enable" class="checkbox">';
				echo lang('sales_change_date');
				echo form_checkbox(array(
					'name'=>'change_sale_date_enable',
					'id'=>'change_sale_date_enable',
					'value'=>'1',
					'checked'=>(boolean)$change_sale_date_enable)
				);
				echo ':</label>  ';

				?>
				<div class="field_row clearfix" id="change_sale_input">
					<div class='form_field'>
					
					<div id="change_sale_date_picker" class="input-group date datepicker" data-date="date(get_date_format())" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>

					<?php echo form_input(array(
						'name'=>'change_sale_date',
						'id' => 'change_sale_date',
						'size'=>'8',
						 'value'=> date(get_date_format())
						)
					);?>       
					</div>
					</div>
				</div>
				<?php 
			}
		} ?>
			</li>
		</ul>

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

		$('#mode_form, #select_customer_form, #add_payment_form, .line_item_form, #discount_all_form').ajaxForm({target: "#register_container", beforeSubmit: salesBeforeSubmit});
		$('#add_item_form').ajaxForm({target: "#register_container", beforeSubmit: salesBeforeSubmit, success: itemScannedSuccess});
		$("#cart_contents input").change(function()
		{
			$(this.form).ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
		});

		$( "#item" ).autocomplete({
			source: '<?php echo site_url("sales/item_search"); ?>',
			delay: 10,
			autoFocus: false,
			minLength: 1,
			select: function(event, ui)
			{
				event.preventDefault();
				$( "#item" ).val(ui.item.value);
				$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit, success: itemScannedSuccess});
			}
		});

		$('#item,#customer').click(function()
		{
			$(this).attr('value','');
		});

		$( "#customer" ).autocomplete({
			source: '<?php echo site_url("sales/customer_search"); ?>',
			delay: 10,
			autoFocus: false,
			minLength: 1,
			select: function(event, ui)
			{
				$("#customer").val(ui.item.value);
				$('#select_customer_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
			}
		});

		$('#customer').blur(function()
		{
			$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_customer_name')); ?>);
		});
		
		$('#item').blur(function()
		{
			$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_item_name')); ?>);
		});
		
		//Datepicker change
		$('#change_sale_date_picker').datepicker().on('changeDate', function(ev) {
			$.post('<?php echo site_url("sales/set_change_sale_date");?>', {change_sale_date: $('#change_sale_date').val()});			
		});
		
		//Input change
		$("#change_sale_date").change(function(){
			$.post('<?php echo site_url("sales/set_change_sale_date");?>', {change_sale_date: $('#change_sale_date').val()});			
		});

		$('#change_sale_date_enable').change(function() 
		{
			$.post('<?php echo site_url("sales/set_change_sale_date_enable");?>', {change_sale_date_enable: $('#change_sale_date_enable').is(':checked') ? '1' : '0'});
		});

		$('#comment').change(function() 
		{
			$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()});
		});
						
		$('#show_comment_on_receipt').change(function() 
		{
			$.post('<?php echo site_url("sales/set_comment_on_receipt");?>', {show_comment_on_receipt:$('#show_comment_on_receipt').is(':checked') ? '1' : '0'});
		});

		$('#email_receipt').change(function() 
		{	
			$.post('<?php echo site_url("sales/set_email_receipt");?>', {email_receipt: $('#email_receipt').is(':checked') ? '1' : '0'});
		});

		$('#save_credit_card_info').change(function() 
		{
			$.post('<?php echo site_url("sales/set_save_credit_card_info");?>', {save_credit_card_info:$('#save_credit_card_info').is(':checked') ? '1' : '0'});
		});

		$('#change_sale_date_enable').is(':checked') ? $("#change_sale_input").show() : $("#change_sale_input").hide(); 

		$('#change_sale_date_enable').click(function() {
			if( $(this).is(':checked')) {
				$("#change_sale_input").show();
			} else {
				$("#change_sale_input").hide();
			}
		});

		$('#use_saved_cc_info').change(function() 
		{
			$.post('<?php echo site_url("sales/set_use_saved_cc_info");?>', {use_saved_cc_info:$('#use_saved_cc_info').is(':checked') ? '1' : '0'});
		});

		$("#finish_sale_button").click(function()
		{
			//Prevent double submission of form
			$("#finish_sale_button").hide();
			$("#register_container").mask(<?php echo json_encode(lang('common_wait')); ?>);
			
			<?php if(!$payments_cover_total) { ?>
				
				if (!confirm(<?php echo json_encode(lang('sales_payment_not_cover_total_confirmation')); ?>))
				{
					//Bring back submit and unmask if fail to confirm
					$("#finish_sale_button").show();
					$("#register_container").unmask();
					
					return;
				}
			<?php } ?>
			
			<?php if (!$this->config->item('disable_confirmation_sale')) { ?>
				if (confirm(<?php echo json_encode(lang("sales_confirm_finish_sale")); ?>))
				{
					<?php } ?>
															
					if ($("#comment").val())
					{
						$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()}, function()
						{
							$('#finish_sale_form').submit();						
						});						
					}
					else
					{
						$('#finish_sale_form').submit();						
					}
					
					<?php if (!$this->config->item('disable_confirmation_sale')) { ?>
					}
					else
					{
						//Bring back submit and unmask if fail to confirm
						$("#finish_sale_button").show();
						$("#register_container").unmask();
					}
					<?php } ?>
				});

		$("#suspend_sale_button").click(function()
		{
			if (confirm(<?php echo json_encode(lang("sales_confirm_suspend_sale")); ?>))
			{
				<?php if ($this->config->item('show_receipt_after_suspending_sale')) { ?>
					window.location = '<?php echo site_url("sales/suspend"); ?>';
				<?php }else { ?>
					$("#register_container").load('<?php echo site_url("sales/suspend"); ?>');
				<?php } ?>
			}
		});

		$("#cancel_sale_button").click(function()
		{
			if (confirm(<?php echo json_encode(lang("sales_confirm_cancel_sale")); ?>))
			{
				$('#cancel_sale_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
			}
		});

		$("#add_payment_button").click(function()
		{
			$('#add_payment_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
		});

		$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard);
		$('#mode').change(function()
		{
			if ($(this).val() == "store_account_payment") { // Hiding the category grid
				$('#show_hide_grid_wrapper, #category_item_selection_wrapper').fadeOut();
			}else { // otherwise, show the categories grid
				$('#show_hide_grid_wrapper, #show_grid').fadeIn();
				$('#hide_grid').fadeOut();
			}
			$('#mode_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
		});

		$('.delete_item, .delete_payment, #delete_customer').click(function(event)
		{
			event.preventDefault();
			$("#register_container").load($(this).attr('href'));	
		});

		$("#tier_id").change(function()
		{
			$.post('<?php echo site_url("sales/set_tier_id");?>', {tier_id: $(this).val()}, function()
			{
				$("#register_container").load('<?php echo site_url("sales/reload"); ?>');
			});
		});

		$("input[type=text]").not(".description").click(function() {
			$(this).select();
		});
		
		//alert(screen.width);
		if(screen.width <= 768) //set the colspan on page load
		{ 
			jQuery('td.edit_discription').attr('colspan', '2');
		}
		
		 $(window).resize(function() {
			var wi = $(window).width();
	 
			if (wi <= 768){
				jQuery('td.edit_discription').attr('colspan', '2');
			}
			else {
				jQuery('td.edit_discription').attr('colspan', '4');
			}
		});     
			
		$("#new-customer").click(function()
		{
			$("body").mask(<?php echo json_encode(lang('common_wait')); ?>);			
		});
	});
 
function checkPaymentTypeGiftcard()
{
	if ($("#payment_types").val() == <?php echo json_encode(lang('sales_giftcard')); ?>)
	{
		$("#amount_tendered").val('');
		$("#amount_tendered").focus();
		giftcard_swipe_field($("#amount_tendered"));
	}
}

function salesBeforeSubmit(formData, jqForm, options)
{
	if (submitting)
	{
		return false;
	}
	submitting = true;
	$("#ajax-loader").show();
	$("#add_payment_button").hide();
	$("#finish_sale_button").hide();
}

function itemScannedSuccess(responseText, statusText, xhr, $form)
{
	setTimeout(function(){$('#item').focus();}, 10);
}


</script>