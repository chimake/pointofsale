<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3><?php echo lang("items_basic_information"); ?></h3>
		</div>
		<div class="modal-body nopadding">			
			<table class="table table-bordered table-hover table-striped">
				
					<tr> <td><h4><?php echo lang('items_name'); ?></h4></td> <td> <h4><?php echo lang('item_kits_quantity');?></h4></td></tr>
					 
					<?php foreach ($this->Item_kit_items->get_info($item_kit_info->item_kit_id) as $item_kit_item) {?>
						<tr>
							<?php
							$item_info = $this->Item->get_info($item_kit_item->item_id);
							?>
							<td><?php echo $item_info->name; ?></td>
							<td> <?php echo number_format($item_kit_item->quantity) ?></td>
						</tr>
					<?php } ?>
				<tr> <td><?php echo lang('items_item_number'); ?></td> <td> <?php echo (isset($item_kit_info->item_kit_number) && $item_kit_info->item_kit_number != '') ? $item_kit_info->item_kit_number : lang('items_none'); ?></td></tr>

				<tr> <td><?php echo lang('items_product_id'); ?></td> <td> <?php echo (isset($item_kit_info->product_id) && $item_kit_info->product_id != '') ? $item_kit_info->product_id : lang('items_none'); ?></td></tr>
								
				<tr> <td><?php echo lang('item_kits_name'); ?></td> <td> <?php echo (isset($item_kit_info->name) && $item_kit_info->name != '') ? $item_kit_info->name : lang('items_none'); ?></td></tr>
				
				<tr> <td><?php echo lang('items_category'); ?></td> <td> <?php echo (isset($item_kit_info->category) && $item_kit_info->category != '') ? $item_kit_info->category : lang('items_none'); ?></td></tr>
				
				<tr> <td><?php echo lang('items_cost_price'); ?></td> <td> <?php echo (isset($item_kit_info->cost_price) && $item_kit_info->cost_price != '') ? to_currency($item_kit_info->cost_price, 10) : lang('items_none'); ?></td></tr>
				
				<tr> <td><?php echo lang('items_unit_price'); ?></td> <td> <?php echo (isset($item_kit_info->unit_price) && $item_kit_info->unit_price != '') ? to_currency($item_kit_info->unit_price, 10) : lang('items_none'); ?></td></tr>
			 	
				<tr> <td><?php echo lang('item_kits_description'); ?></td> <td> <?php echo (isset($item_kit_info->description) && $item_kit_info->description != '') ? $item_kit_info->description : lang('items_none'); ?></td></tr>
			 	 
		 	</table>
		</div>
	</div>
</div>



