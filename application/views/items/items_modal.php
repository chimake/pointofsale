<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3><?php echo lang("items_basic_information"); ?></h3>
		</div>
		<div class="modal-body nopadding">
			<?php echo $item_info->image_id ? img(array('src' => site_url('app_files/view/'.$item_info->image_id),'class'=>' img-polaroid')) : img(array('src' => base_url().'/img/avatar.png','class'=>' img-polaroid','id'=>'image_empty')); ?>
			<table class="table table-bordered table-hover table-striped" width="1200px">
				<tr> <td><?php echo lang('items_item_number'); ?></td> <td> <?php echo $item_info->item_number; ?></td></tr>
				<tr> <td><?php echo lang('items_product_id'); ?></td> <td> <?php echo $item_info->product_id; ?></td></tr>
				<tr> <td><h4><?php echo lang('items_name'); ?></h4></td> <td> <h4><?php echo $item_info->name; ?></h4></td></tr>
				<tr> <td><?php echo lang('items_category'); ?></td> <td> <?php echo $item_info->category; ?></td></tr>
				<tr> <td><?php echo lang('items_supplier'); ?></td> 
					<td> <?php if (isset($supplier) && $supplier != '' ){
							echo $supplier;
						}else {
						   echo lang('items_none');  
						}
						?></td>
				</tr>
				<?php if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="")	{ ?>
				<tr> <td><?php echo lang('items_cost_price'); ?></td> <td> <?php echo to_currency($item_info->cost_price, 10); ?></td></tr>
				<?php } ?>
				<tr> <td><?php echo lang('items_unit_price'); ?></td> <td> <?php echo to_currency($item_info->unit_price, 10); ?></td></tr>
				<tr> <td><?php echo lang('items_promo_price'); ?></td> <td> <?php echo to_currency($item_info->promo_price, 10); ?></td></tr>
				<tr> <td><?php echo lang('items_quantity'); ?></td> <td> <?php echo to_quantity($item_location_info->quantity); ?></td></tr>
				<tr> <td><?php echo lang('items_reorder_level'); ?></td> <td> <?php echo to_quantity($reorder_level); ?></td></tr>
				<tr> <td><?php echo lang('items_location'); ?></td> <td> <?php echo $item_location_info->location; ?></td></tr>
				<tr> <td><?php echo lang('items_description'); ?></td> <td> <?php echo $item_info->description; ?></td></tr>
				<tr> <td><?php echo lang('items_allow_alt_desciption'); ?></td> <td> <?php echo $item_info->allow_alt_description ? lang('common_yes') : lang('common_no'); ?></td></tr>
				<tr> <td><?php echo lang('items_is_serialized'); ?></td> <td> <?php echo $item_info->is_serialized ? lang('common_yes') : lang('common_no'); ?></td></tr>
			</table>
		</div>
	</div>
</div>



