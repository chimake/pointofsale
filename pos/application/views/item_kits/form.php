<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1> <i class="fa fa-upload"></i> <?php  if(!$item_kit_info->item_kit_id) { echo lang($controller_name.'_new'); } else { echo lang($controller_name.'_update'); }    ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

	<div class="row" id="form">
		<div class="col-md-12">
			<?php echo lang('common_fields_required_message'); ?>
			
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="fa fa-align-justify"></i>									
				</span>
				<h5><?php echo lang("item_kits_info"); ?></h5>
			</div>
			<div class="widget-content nopadding">
				<?php echo form_open('item_kits/save/'.$item_kit_info->item_kit_id,array('id'=>'item_kit_form','class'=>'form-horizontal')); ?>
				<span class="help-block" style="margin-left: 35px"><?php echo lang('item_kits_desc'); ?></span>
			<div class="form-group">
			<?php echo form_label(lang('item_kits_add_item').':', 'item',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?>
				<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps',
						'name'=>'item',
						'id'=>'item'
					));?>
				</div>
			</div>

	<div class="container-fluid">
		<div class="row">
			<div class="span6 offset3">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-th"></i>
						</span>
						<h5><?php echo lang('item_kits_items_added');?></h5>
					</div>
					<div class="widget-content nopadding">
						<table id="item_kit_items" class="table table-bordered table-striped table-hover text-success text-center">
							<tr>
								<th><?php echo lang('common_delete');?></th>
								<th><?php echo lang('item_kits_item');?></th>
								<th><?php echo lang('item_kits_quantity');?></th>
							</tr>
	
							<?php foreach ($this->Item_kit_items->get_info($item_kit_info->item_kit_id) as $item_kit_item) {?>
								<tr>
									<?php
									$item_info = $this->Item->get_info($item_kit_item->item_id);
									?>
									<td><a  href="#" onclick='return deleteItemKitRow(this);'><i class=' fa fa-trash-o fa-2x text-error'</i></a></td>
									<td><?php echo $item_info->name; ?></td>
									<td><input class='quantity' onchange="calculateSuggestedPrices();" id='item_kit_item_<?php echo $item_kit_item->item_id ?>' type='text' size='3' name=item_kit_item[<?php echo $item_kit_item->item_id ?>] value='<?php echo to_quantity($item_kit_item->quantity); ?>'/></td>
								</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
		<?php echo form_label(lang('items_item_number').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control form-inps',
				'name'=>'item_kit_number',
				'id'=>'item_kit_number',
				'value'=>$item_kit_info->item_kit_number)
			);?>
			</div>
		</div>
		<?php echo form_hidden('redirect', $redirect); ?>
				
		<div class="form-group">
			<?php echo form_label(lang('items_product_id').':', 'product_id',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_input(array(
					'name'=>'product_id',
					'id'=>'product_id',
					'class'=>'form-control form-inps',
					'value'=>$item_kit_info->product_id)
				);?>
			</div>
		</div>
		

		<div class="form-group">
		<?php echo form_label(lang('item_kits_name').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control form-inps',
				'name'=>'name',
				'id'=>'name',
				'value'=>$item_kit_info->name)
			);?>
			</div>
		</div>

		<div class="form-group">
		<?php echo form_label(lang('items_category').':', 'category',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control form-inps',
				'name'=>'category',
				'id'=>'category',
				'value'=>$item_kit_info->category)
			);?>
			</div>
		</div>
		
		<div class="form-group">
		<?php echo form_label(lang('item_kits_description').':', 'description',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_textarea(array(
				'name'=>'description',
				'id'=>'description',
				'class'=>'form-textarea',
				'value'=>$item_kit_info->description,
				'rows'=>'5',
				'cols'=>'17')
			);?>
			</div>
		</div>
		
		
		<div class="form-group">
		<?php echo form_label(lang('common_prices_include_tax').':', 'prices_include_tax',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_checkbox(array(
				'name'=>'tax_included',
				'id'=>'tax_included',
				'class'=>'tax-checkboxes',
				'value'=>1,
				'checked'=>($item_kit_info->tax_included || (!$item_kit_info->item_kit_id && $this->config->item('prices_include_tax'))) ? 1 : 0)
			);?>
		</div>
		</div>


		<div class="form-group">
		<?php echo form_label(lang('items_cost_price').' ('.lang('items_without_tax').'):', 'cost_price',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control form-inps',
				'name'=>'cost_price',
				'id'=>'cost_price',
				'value'=>$item_kit_info->cost_price ? to_currency_no_money($item_kit_info->cost_price) : '')
			);?>
			</div>
		</div>

		<div class="form-group">
		<?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control form-inps',
				'name'=>'unit_price',
				'id'=>'unit_price',
				'value'=>$item_kit_info->unit_price ? to_currency_no_money($item_kit_info->unit_price,10) : '')
			);?>
			</div>
		</div>
		
		<?php foreach($tiers as $tier) { ?>	
			<div class="form-group">
				<?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
				<div class='col-sm-9 col-md-9 col-lg-10'>
				<?php echo form_input(array(
					'class'=>'form-control form-inps margin10',
					'name'=>'item_kit_tier['.$tier->id.']',
					'size'=>'8',
					'value'=> $tier_prices[$tier->id] !== FALSE ? ($tier_prices[$tier->id]->unit_price != NULL ? to_currency_no_money($tier_prices[$tier->id]->unit_price, 10) : $tier_prices[$tier->id]->percent_off): '')
				);?>

				<?php echo form_dropdown('tier_type['.$tier->id.']', $tier_type_options, $tier_prices[$tier->id] !== FALSE && $tier_prices[$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price');?>
				</div>
			</div>

		<?php } ?>
		
		<div class="form-group override-taxes-container">
			<?php echo form_label(lang('items_override_default_tax').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
			
			<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_checkbox(array(
					'name'=>'override_default_tax',
					'class' => 'override_default_tax_checkbox tax-checkboxes',
					'value'=>1,
					'checked'=>(boolean)$item_kit_info->override_default_tax));
				?>
			</div>
		</div>
		<div class="tax-container <?php if (!$item_kit_info->override_default_tax){echo 'hidden';} ?>">	
			<div class="form-group">
			<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
				<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_input(array(
					'class'=>'form-control form-inps margin10',
					'name'=>'tax_names[]',
					'placeholder' => lang('common_tax_name'),
					'id'=>'tax_name_1 noreset',
					'size'=>'8',
					'value'=> isset($item_kit_tax_info[0]['name']) ? $item_kit_tax_info[0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name')))
				);?>
				</div>
				<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
				<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_input(array(
					'class'=>'form-control form-inps-tax',
					'name'=>'tax_percents[]',
					'placeholder' => lang('items_tax_percent'),
					'id'=>'tax_percent_name_1',
					'size'=>'3',
					'value'=> isset($item_kit_tax_info[0]['percent']) ? $item_kit_tax_info[0]['percent'] : '')
				);?>
				<div class="tax-percent-icon">%</div>
				<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
				</div>
			</div>

			<div class="form-group">
			<?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
				<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_input(array(
					'class'=>'form-control form-inps margin10',
					'name'=>'tax_names[]',
					'placeholder' => lang('common_tax_name'),
					'id'=>'tax_name_2',
					'size'=>'8',
					'value'=> isset($item_kit_tax_info[1]['name']) ? $item_kit_tax_info[1]['name'] : ($this->Location->get_info_for_key('default_tax_2_name') ? $this->Location->get_info_for_key('default_tax_2_name') : $this->config->item('default_tax_2_name')))
				);?>
				</div>
				<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
				<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_input(array(
					'class'=>'form-control form-inps-tax',
					'name'=>'tax_percents[]',
					'placeholder' => lang('items_tax_percent'),
					'id'=>'tax_percent_name_2',
					'size'=>'3',
					'value'=> isset($item_kit_tax_info[1]['percent']) ? $item_kit_tax_info[1]['percent'] : '')
				);?>
				<div class="tax-percent-icon">%</div>
				<div class="clear"></div>
				<?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_kit_tax_info[1]['cumulative']) && $item_kit_tax_info[1]['cumulative'] ? (boolean)$item_kit_tax_info[1]['cumulative'] : (boolean)$this->config->item('default_tax_2_cumulative'), 'class="cumulative_checkbox"'); ?>
			    <span class="cumulative_label">
				<?php echo lang('common_cumulative'); ?>
			    </span>
				</div>
			</div>
		
		
			<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3" style="visibility: <?php echo isset($item_kit_tax_info[2]['name']) ? 'hidden' : 'visible';?>">
				<a href="javascript: void(0);" class="show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
			</div>
			
		
			<div class="more_taxes_container" style="display: <?php echo isset($item_kit_tax_info[2]['name']) ? 'block' : 'none';?>">
				<div class="form-group">
				<?php echo form_label(lang('items_tax_3').':', 'tax_percent_3',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps margin10',
						'name'=>'tax_names[]',
						'placeholder' => lang('common_tax_name'),
						'id'=>'tax_name_3 noreset',
						'size'=>'8',
						'value'=> isset($item_kit_tax_info[2]['name']) ? $item_kit_tax_info[2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name')))
					);?>
					</div>
					<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps-tax',
						'name'=>'tax_percents[]',
						'placeholder' => lang('items_tax_percent'),
						'id'=>'tax_percent_name_3',
						'size'=>'3',
						'value'=> isset($item_kit_tax_info[2]['percent']) ? $item_kit_tax_info[2]['percent'] : '')
					);?>
					<div class="tax-percent-icon">%</div>
					<div class="clear"></div>
					<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
					</div>
				</div>
				
				<div class="form-group">
				<?php echo form_label(lang('items_tax_4').':', 'tax_percent_4',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps margin10',
						'name'=>'tax_names[]',
						'placeholder' => lang('common_tax_name'),
						'id'=>'tax_name_4 noreset',
						'size'=>'8',
						'value'=> isset($item_kit_tax_info[3]['name']) ? $item_kit_tax_info[3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name')))
					);?>
					</div>
					<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps-tax',
						'name'=>'tax_percents[]',
						'placeholder' => lang('items_tax_percent'),
						'id'=>'tax_percent_name_4',
						'size'=>'3',
						'value'=> isset($item_kit_tax_info[3]['percent']) ? $item_kit_tax_info[3]['percent'] : '')
					);?>
					<div class="tax-percent-icon">%</div>
					<div class="clear"></div>
					<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
					</div>
				</div>

				<div class="form-group">
				<?php echo form_label(lang('items_tax_5').':', 'tax_percent_5',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps margin10',
						'name'=>'tax_names[]',
						'placeholder' => lang('common_tax_name'),
						'id'=>'tax_name_5 noreset',
						'size'=>'8',
						'value'=> isset($item_kit_tax_info[4]['name']) ? $item_kit_tax_info[4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name')))
					);?>
					</div>
					<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'class'=>'form-control form-inps-tax',
						'name'=>'tax_percents[]',
						'placeholder' => lang('items_tax_percent'),
						'id'=>'tax_percent_name_5',
						'size'=>'3',
						'value'=> isset($item_kit_tax_info[4]['percent']) ? $item_kit_tax_info[4]['percent'] : '')
					);?>
					<div class="tax-percent-icon">%</div>
					<div class="clear"></div>
					<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
					</div>
				</div>				
			</div>		
			<div class="clear"></div>
		</div>		

		<?php if ($this->Location->count_all() > 1) {?>
		
			<?php foreach($locations as $location) { ?>
				<div class="widget-title widget-title1">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo $location->name; ?></h5>
				</div>
			
					<div class="form-group override-prices-container">
						<?php echo form_label(lang('items_override_prices').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_checkbox(array(
								'name'=>'locations['.$location->location_id.'][override_prices]',
								'class' => 'override_prices_checkbox tax-checkboxes',
								'value'=>1,
								'checked'=>(boolean)isset($location_item_kits[$location->location_id]) && is_object($location_item_kits[$location->location_id]) && $location_item_kits[$location->location_id]->is_overwritten));
							?>
						</div>
					</div>

					<div class="item-kit-location-price-container <?php if ($location_item_kits[$location->location_id] === FALSE || !$location_item_kits[$location->location_id]->is_overwritten){echo 'hidden';} ?>">	
						<div class="form-group">
						<?php echo form_label(lang('items_cost_price').' ('.lang('items_without_tax').'):', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'locations['.$location->location_id.'][cost_price]',
									'size'=>'8',
									'value'=> $location_item_kits[$location->location_id]->item_kit_id !== '' && $location_item_kits[$location->location_id]->cost_price ? to_currency_no_money($location_item_kits[$location->location_id]->cost_price, 10): ''
								)
								);?>
						</div>
					</div>

						<div class="form-group">
						<?php echo form_label(lang('items_unit_price').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps',
								'name'=>'locations['.$location->location_id.'][unit_price]',
								'size'=>'8',
								'value'=>$location_item_kits[$location->location_id]->item_kit_id !== '' &&  $location_item_kits[$location->location_id]->unit_price ? to_currency_no_money($location_item_kits[$location->location_id]->unit_price, 10): ''
								)
							);?>
							</div>
						</div>

						<?php foreach($tiers as $tier) { ?>	
							<div class="form-group">
								<?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps margin10',
									'name'=>'locations['.$location->location_id.'][item_tier]['.$tier->id.']',
									'size'=>'8',
									'value'=> $location_tier_prices[$location->location_id][$tier->id] !== FALSE ? ($location_tier_prices[$location->location_id][$tier->id]->unit_price != NULL ? to_currency_no_money($location_tier_prices[$location->location_id][$tier->id]->unit_price ,10) : $location_tier_prices[$location->location_id][$tier->id]->percent_off): '')
								);?>

								<?php echo form_dropdown('locations['.$location->location_id.'][tier_type]['.$tier->id.']', $tier_type_options, $location_tier_prices[$location->location_id][$tier->id] !== FALSE && $location_tier_prices[$location->location_id][$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price');?>
								</div>
							</div>

						<?php } ?>
					</div>
					<div class="form-group override-taxes-container">
						<?php echo form_label(lang('items_override_default_tax').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>

						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_checkbox(array(
								'name'=>'locations['.$location->location_id.'][override_default_tax]',
								'class' => 'override_default_tax_checkbox tax-checkboxes',
								'value'=>1,
								'checked'=> $location_item_kits[$location->location_id]->item_kit_id !== '' ? (boolean)$location_item_kits[$location->location_id]->override_default_tax: FALSE
								));
							?>
						</div>
					</div>

					<div class="tax-container <?php if ($location_item_kits[$location->location_id] === FALSE || !$location_item_kits[$location->location_id]->override_default_tax){echo 'hidden';} ?>">	
						<div class="form-group">
						<?php echo form_label(lang('items_tax_1').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps margin10',
								'name'=>'locations['.$location->location_id.'][tax_names][]',
								'placeholder' => lang('common_tax_name'),
								'size'=>'8',
								'value' => isset($location_taxes[$location->location_id][0]['name']) ? $location_taxes[$location->location_id][0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name'))
							));?>
							</div>
							<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps-tax margin10',
								'name'=>'locations['.$location->location_id.'][tax_percents][]',
								'placeholder' => lang('items_tax_percent'),
								'size'=>'3',
								'value' => isset($location_taxes[$location->location_id][0]['percent']) ? $location_taxes[$location->location_id][0]['percent'] : ''
							));?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
							</div>
						</div>
					<div class="form-group">
					<?php echo form_label(lang('items_tax_2').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'class'=>'form-control form-inps margin10',
							'name'=>'locations['.$location->location_id.'][tax_names][]',
							'placeholder' => lang('common_tax_name'),
							'size'=>'8',
							'value' => isset($location_taxes[$location->location_id][1]['name']) ? $location_taxes[$location->location_id][1]['name'] : ($this->Location->get_info_for_key('default_tax_2_name') ? $this->Location->get_info_for_key('default_tax_2_name') : $this->config->item('default_tax_2_name'))
							)
						);?>
						</div>
						<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'class'=>'form-control form-inps-tax',
							'name'=>'locations['.$location->location_id.'][tax_percents][]',
							'placeholder' => lang('items_tax_percent'),
							'size'=>'3',
							'value' => isset($location_taxes[$location->location_id][1]['percent']) ? $location_taxes[$location->location_id][1]['percent'] : ''
							)
						);?>
						<div class="tax-percent-icon">%</div>
						<div class="clear"></div>
						<?php echo form_checkbox('locations['.$location->location_id.'][tax_cumulatives][]', '1', isset($location_taxes[$location->location_id][1]['cumulative']) ? (boolean)$location_taxes[$location->location_id][1]['cumulative'] :(boolean)$this->config->item('default_tax_2_cumulative'), 'class="cumulative_checkbox"'); ?>
					    <span class="cumulative_label">
						<?php echo lang('common_cumulative'); ?>
					    </span>
						</div>
					</div>
					
					<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3"  style="visibility: <?php echo isset($item_tax_info[2]['name']) ? 'hidden' : 'visible';?>">
						<a href="javascript: void(0);" class="show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
					</div>
					
					<div class="more_taxes_container" style="display: <?php echo isset($location_taxes[$location->location_id][2]['name']) ? 'block' : 'none';?>">
						<div class="form-group">
						<?php echo form_label(lang('items_tax_3').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps margin10',
								'name'=>'locations['.$location->location_id.'][tax_names][]',
								'placeholder' => lang('common_tax_name'),
								'size'=>'8',
								'value' => isset($location_taxes[$location->location_id][2]['name']) ? $location_taxes[$location->location_id][2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name'))
							));?>
							</div>
							<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps-tax',
								'name'=>'locations['.$location->location_id.'][tax_percents][]',
								'placeholder' => lang('items_tax_percent'),
								'size'=>'3',
								'value' => isset($location_taxes[$location->location_id][2]['percent']) ? $location_taxes[$location->location_id][2]['percent'] : ''
							));?>
							<div class="tax-percent-icon">%</div>
						<div class="clear"></div>
							<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
							</div>
						</div>

						<div class="form-group">
						<?php echo form_label(lang('items_tax_4').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps margin10',
								'name'=>'locations['.$location->location_id.'][tax_names][]',
								'placeholder' => lang('common_tax_name'),
								'size'=>'8',
								'value' => isset($location_taxes[$location->location_id][3]['name']) ? $location_taxes[$location->location_id][3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name'))
							));?>
							</div>
							<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps-tax',
								'name'=>'locations['.$location->location_id.'][tax_percents][]',
								'placeholder' => lang('items_tax_percent'),
								'size'=>'3',
								'value' => isset($location_taxes[$location->location_id][3]['percent']) ? $location_taxes[$location->location_id][3]['percent'] : ''
							));?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
							</div>
						</div>


						<div class="form-group">
						<?php echo form_label(lang('items_tax_5').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps margin10',
								'name'=>'locations['.$location->location_id.'][tax_names][]',
								'placeholder' => lang('common_tax_name'),
								'size'=>'8',
								'value' => isset($location_taxes[$location->location_id][4]['name']) ? $location_taxes[$location->location_id][4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name'))
							));?>
							</div>
							<label class="col-sm-3 col-md-3 col-lg-2 control-label  wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'class'=>'form-control form-inps-tax margin10',
								'name'=>'locations['.$location->location_id.'][tax_percents][]',
								'placeholder' => lang('items_tax_percent'),
								'size'=>'3',
								'value' => isset($location_taxes[$location->location_id][4]['percent']) ? $location_taxes[$location->location_id][4]['percent'] : ''
							));?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
							</div>
						</div>
					</div>
					
					
					
					
				</div>	
				<?php } /*End foreach locations*/ ?>
		<?php } /*End if for multi locations*/?>
		
	<div class="form-actions">
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>lang('common_submit'),
		'class'=>'submit_button btn btn-primary')
	);
	?>
	</div>
	<?php echo form_close(); ?>
	<script type='text/javascript'>

	$( "#item" ).autocomplete({
		source: '<?php echo site_url("items/item_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 0,
		select: function( event, ui ) 
		{	
			$( "#item" ).val("");
			if ($("#item_kit_item_"+ui.item.value).length ==1)
			{
				$("#item_kit_item_"+ui.item.value).val(parseFloat($("#item_kit_item_"+ui.item.value).val()) + 1);
			}
			else
			{
				$("#item_kit_items").append("<tr class='item_kit_item_row'><td><a  href='#' onclick='return deleteItemKitRow(this);'><i class='fa fa-trash-o fa-2x text-error'></i></a></td><td>"+ui.item.label+"</td><td><input class='quantity' onchange='calculateSuggestedPrices();' id='item_kit_item_"+ui.item.value+"' type='text' size='3' name=item_kit_item["+ui.item.value+"] value='1'/></td></tr>");
			}
		
			calculateSuggestedPrices();
		
			return false;
		}
	});

	//validation and submit handling
	$(document).ready(function()
	{
	    setTimeout(function(){$(":input:visible:first","#item_kit_form").focus();},100);
		$( "#category" ).autocomplete({
			source: "<?php echo site_url('items/suggest_category');?>",
			delay: 10,
			autoFocus: false,
			minLength: 0
		});
		
		$(".override_default_tax_checkbox, .override_prices_checkbox").change(function()
		{
			$(this).parent().parent().next().toggleClass('hidden')
		});	

		$('#item_kit_form').validate({
			submitHandler:function(form)
			{
				$.post('<?php echo site_url("item_kits/check_duplicate");?>', {term: $('#name').val()},function(data) {
				<?php if(!$item_kit_info->item_kit_id) { ?>
				if(data.duplicate)
					{
						if(confirm(<?php echo json_encode(lang('items_duplicate_exists'));?>))
						{
							doItemKitSubmit(form);
						}
						else 
						{
							return false;
						}
					}
				<?php } else ?>		
					{
						doItemKitSubmit(form);
					}} , "json")
					.error(function() {
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
					<?php foreach($tiers as $tier) { ?>
						"<?php echo 'item_kit_tier['.$tier->id.']'; ?>":
						{
							number: true
						},
					<?php } ?>
			
					<?php foreach($locations as $location) { ?>
						"<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>":
						{
							number: true
						},
						"<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>":
						{
							number: true
						},			
						<?php foreach($tiers as $tier) { ?>
							"<?php echo 'locations['.$location->location_id.'][item_tier]['.$tier->id.']'; ?>":
							{
								number: true
							},
						<?php } ?>				
					<?php } ?>					
					name:"required",
					category:"required",
					unit_price: "number",
					cost_price: "number"
				},
				messages:
				{
					<?php foreach($tiers as $tier) { ?>
						"<?php echo 'item_kit_tier['.$tier->id.']'; ?>":
						{
							number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
						},
					<?php } ?>
			
					<?php foreach($locations as $location) { ?>
						"<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>":
						{
							number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
						},
						"<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>":
						{
							number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
						},			
						<?php foreach($tiers as $tier) { ?>
							"<?php echo 'locations['.$location->location_id.'][item_tier]['.$tier->id.']'; ?>":
							{
								number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
							},
						<?php } ?>				
					<?php } ?>
					name:<?php echo json_encode(lang('items_name_required')); ?>,
					category:<?php echo json_encode(lang('items_category_required')); ?>,
					unit_price: <?php echo json_encode(lang('items_unit_price_number')); ?>,
					cost_price: <?php echo json_encode(lang('items_cost_price_number')); ?>
				}
		});
	});

	function deleteItemKitRow(link)
	{
		$(link).parent().parent().remove();
		calculateSuggestedPrices();
		return false;
	}

	function calculateSuggestedPrices()
	{
		var items = [];
		$("#item_kit_items").find('input').each(function(index, element)
		{
			var quantity = parseFloat($(element).val());
			var item_id = $(element).attr('id').substring($(element).attr('id').lastIndexOf('_') + 1);
		
			items.push({
				item_id: item_id,
				quantity: quantity
			});
		});
		calculateSuggestedPrices.totalCostOfItems = 0;
		calculateSuggestedPrices.totalPriceOfItems = 0;
		getPrices(items, 0);
	}

	function getPrices(items, index)
	{
		if (index > items.length -1)
		{
			$("#unit_price").val(calculateSuggestedPrices.totalPriceOfItems);
			$("#cost_price").val(calculateSuggestedPrices.totalCostOfItems);
		}
		else
		{
			$.get('<?php echo site_url("items/get_info");?>'+'/'+items[index]['item_id'], {}, function(item_info)
			{
				calculateSuggestedPrices.totalPriceOfItems+=items[index]['quantity'] * parseFloat(item_info.unit_price);
				calculateSuggestedPrices.totalCostOfItems+=items[index]['quantity'] * parseFloat(item_info.cost_price);
				getPrices(items, index+1);
			}, 'json');
		}
	}
	
	var submitting = false;
	function doItemKitSubmit(form)
	{	
		$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
		if (submitting) return;
		submitting = true;
		$(form).ajaxSubmit({
		success:function(response)
	    {
			$('#form').unmask();
			submitting = false;
			gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.item_kit_id,response.message,response.success ? 'gritter-item-success' : 'gritter-item-error',false,false);
			
			<?php if(!$item_kit_info->item_kit_id) { ?>
			//If we have a new item, make sure we hide the tax containers to "reset"
			$(".tax-container").addClass('hidden');
			$(".item-kit-location-price-container").addClass('hidden');
			$('.item_kit_item_row').remove();
			<?php } ?>

			if(response.redirect==2 && response.success)
			{
				window.location.href = '<?php echo site_url('item_kits'); ?>'
			}
		},
		<?php if(!$item_kit_info->item_kit_id) { ?>
		resetForm: true,
		<?php } ?>
		dataType:'json'
		});
	}	
	</script>
	<?php $this->load->view("partial/footer"); ?>