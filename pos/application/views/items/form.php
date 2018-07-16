<?php $this->load->view("partial/header"); ?>

<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-pencil"></i>  <?php  if(!$item_info->item_id) { echo lang($controller_name.'_new'); } else { echo lang($controller_name.'_update'); }    ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="item_navigation clearfix">
	<?php
	if (isset($prev_item_id) && $prev_item_id)
	{
		echo '<div class="previous_item">';
			echo anchor('items/view/'.$prev_item_id, '&laquo; '.lang('items_prev_item'));
		echo '</div>';
	}
	?>

	<?php
	if (isset($next_item_id) && $next_item_id)
	{
		echo '<div class="next_item">';
			echo anchor('items/view/'.$next_item_id,lang('items_next_item').' &raquo;');
		echo '</div>';
	}
	?>
</div>

<?php echo form_open_multipart('items/save/'.$item_info->item_id,array('id'=>'item_form','class'=>'form-horizontal')); ?>
	<div class="row" id="form">
		<div class="col-md-12">
			<?php echo lang('common_fields_required_message'); ?>
			
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo lang("items_basic_information"); ?></h5>
				</div>
				<div class="widget-content nopadding">
					<div class="row">
					<div class="span7 ">
					<div class="form-group">
						<?php echo form_label(lang('items_item_number').':', 'item_number',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'item_number',
								'id'=>'item_number',
								'class'=>'form-control form-inps',
								'value'=>$item_info->item_number)
							);?>
						</div>
					</div>

					<div class="form-group">
						<?php echo form_label(lang('items_product_id').':', 'product_id',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'product_id',
								'id'=>'product_id',
								'class'=>'form-control form-inps',
								'value'=>$item_info->product_id)
							);?>
						</div>
					</div>

 					<div class="form-group">
					<?php echo form_label(lang('items_name').':', 'name',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'name',
							'id'=>'name',
							'class'=>'form-control form-inps',
							'value'=>$item_info->name)
						);?>
						</div>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('items_category').':', 'category',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'category',
							'id'=>'category',
								'class'=>'form-control form-inps',
							'value'=>$item_info->category)
						);?>
						</div>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_dropdown('supplier_id', $suppliers, $selected_supplier,'class="span3"');?>
						</div>
					</div>
					
					<div class="form-group reorder-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
					<?php echo form_label(lang('items_reorder_level').':', 'reorder_level',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'reorder_level',
							'id'=>'reorder_level',
								'class'=>'form-control form-inps',
							'value'=>$item_info->reorder_level ? to_quantity($item_info->reorder_level) :'')
						);?>
						</div>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('items_description').':', 'description',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_textarea(array(
							'name'=>'description',
							'id'=>'description',
							'value'=>$item_info->description,
								'class'=>'form-control  form-textarea',
							'rows'=>'5',
							'cols'=>'17')
						);?>
						</div>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('common_prices_include_tax').':', 'prices_include_tax',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_checkbox(array(
							'name'=>'tax_included',
							'id'=>'tax_included',
								'class'=>'form-control delete-checkbox',
							'value'=>1,
							'checked'=>($item_info->tax_included || (!$item_info->item_id && $this->config->item('prices_include_tax'))) ? 1 : 0)
						);?>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('items_is_service').':', 'is_service',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_checkbox(array(
							'name'=>'is_service',
							'id'=>'is_service',
								'class'=>'form-control delete-checkbox',
							'value'=>1,
							'checked'=>($item_info->is_service) ? 1 : 0)
						);?>
						</div>
					</div>
					<div class="form-group">
					<?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_checkbox(array(
							'name'=>'allow_alt_description',
							'id'=>'allow_alt_description',
							'class'=>'delete-checkbox',
							'value'=>1,
							'checked'=>($item_info->allow_alt_description)? 1  :0)
						);?>
						</div>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_checkbox(array(
							'name'=>'is_serialized',
							'id'=>'is_serialized',
								'class'=>'form-control delete-checkbox',
							'value'=>1,
							'checked'=>($item_info->is_serialized)? 1 : 0)
						);?>
						</div>
					</div>
					
				</div>
				<div class="form-group">
                <div class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</div>
				<div class="col-sm-9 col-md-9 col-lg-10">
							<div id="avatar">
				      		<?php echo $item_info->image_id ? img(array('src' => site_url('app_files/view/'.$item_info->image_id),'class'=>'img-polaroid img-polaroid-s')) : img(array('src' => base_url().'/img/avatar.png','class'=>'','id'=>'image_empty')); ?>
							</div>
                            <div class="image-upload">
							 <?php echo form_upload(array(
                                'name'=>'image_id',
                                'id'=>'image_id',
                                'value'=>$item_info->image_id)
                              );?>   
                          </div>  
                </div> 
				</div>
				<?php if($item_info->image_id) {  ?>
				<div class="form-group">
				<?php echo form_label(lang('items_del_image').':', 'del_image',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_checkbox(array(
						'name'=>'del_image',
						'id'=>'del_image',
						'class'=>'form-control delete-checkbox',
						'value'=>1
					));?>
					</div>
				</div>
				<?php } ?>

			</div>	


			<div class="widget-title widget-title1 pricing-widget">
				<span class="icon">
					<i class="fa fa-align-justify"></i>									
				</span>
				<h5><?php echo lang("items_pricing_and_inventory"); ?></h5>
			</div>
					<?php if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="") { ?>
						<div class="form-group">
							<?php echo form_label(lang('items_cost_price').' ('.lang('items_without_tax').')'.':', 'cost_price',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo form_input(array(
										'name'=>'cost_price',
										'size'=>'8',
										'id'=>'cost_price',
										'class'=>'form-control form-inps',
										'value'=>$item_info->cost_price ? to_currency_no_money($item_info->cost_price,10) : '')
									);?>
								</div>
						</div>
					<?php 
					}
					else
					{
						echo form_hidden('cost_price', $item_info->cost_price);
					}
					?>

				<div class="form-group">
				<?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					<?php echo form_input(array(
						'name'=>'unit_price',
						'size'=>'8',
						'id'=>'unit_price',
								'class'=>'form-control form-inps',
						'value'=>$item_info->unit_price ? to_currency_no_money($item_info->unit_price, 10) : '')
					);?>
					</div>
				</div>

				<?php foreach($tiers as $tier) { ?>	
					<div class="form-group">
						<?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'item_tier['.$tier->id.']',
							'size'=>'8',
								'class'=>'form-control form-inps margin10',
							'value'=> $tier_prices[$tier->id] !== FALSE ? ($tier_prices[$tier->id]->unit_price != NULL ? to_currency_no_money($tier_prices[$tier->id]->unit_price, 10) : $tier_prices[$tier->id]->percent_off): '')
						);?>
	
						<?php echo form_dropdown('tier_type['.$tier->id.']', $tier_type_options, $tier_prices[$tier->id] !== FALSE && $tier_prices[$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price');?>
						</div>
					</div>

				<?php } ?>

				<div class="form-group">
				<?php echo form_label(lang('items_promo_price').':', 'promo_price',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
				    <div class="col-sm-9 col-md-9 col-lg-10">
				    <?php echo form_input(array(
				        'name'=>'promo_price',
				        'size'=>'8',
								'class'=>'form-control',
				        'id'=>'promo_price',
						'class'=>'form-inps',
				        'value'=> $item_info->promo_price ? to_currency_no_money($item_info->promo_price,10) : '')
				    );?>
				    </div>
				</div>

					<div class="form-group offset1">
					<?php echo form_label(lang('items_promo_start_date').':', 'start_date',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label text-info wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					   
			
				    <div class="input-group date datepicker" data-date="<?php echo $item_info->start_date ? date(get_date_format(), strtotime($item_info->start_date)) : ''; ?>" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
  					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<?php echo form_input(array(
				        'name'=>'start_date',
				        'id'=>'start_date',
								'class'=>'form-control form-inps',
				        'value'=>$item_info->start_date ? date(get_date_format(), strtotime($item_info->start_date)) : '')
				    );?> </div>

				    </div>
				</div>


					<div class="form-group offset1">
					<?php echo form_label(lang('items_promo_end_date').':', 'end_date',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label text-info wide')); ?>
					<div class="col-sm-9 col-md-9 col-lg-10">
					   
			
				    <div class="input-group date datepicker" data-date="<?php echo $item_info->end_date ? date(get_date_format(), strtotime($item_info->end_date)) : ''; ?>" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
  					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<?php echo form_input(array(
				        'name'=>'end_date',
				        'id'=>'end_date',
								'class'=>'form-control form-inps',
				        'value'=>$item_info->end_date ? date(get_date_format(), strtotime($item_info->end_date)) : '')
				    );?> </div>

				    </div>
				</div>
				
				<div class="form-group override-taxes-container">
					<?php echo form_label(lang('items_override_default_tax').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
					
					<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_checkbox(array(
							'name'=>'override_default_tax',
							'class' => 'override_default_tax_checkbox form-control delete-checkbox',
							'value'=>1,
							'checked'=>(boolean)$item_info->override_default_tax));
						?>
					</div>
				</div>
				<div class="tax-container <?php if (!$item_info->override_default_tax){echo 'hidden';} ?>">	
					<div class="form-group">
					<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'tax_names[]',
							'id'=>'tax_name_1 noreset',
							'size'=>'8',
							'class'=>'form-control margin10 form-inps',
							'placeholder' => lang('common_tax_name'),
							'value'=> isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name')))
						);?>
						</div>
                        <label class="col-sm-3 col-md-3 col-lg-2 control-label wide" for="tax_percent_1">&nbsp;</label>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'tax_percents[]',
							'id'=>'tax_percent_name_1',
							'size'=>'3',
							'class'=>'form-control form-inps-tax',
							'placeholder' => lang('items_tax_percent'),
							'value'=> isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : '')
						);?>
						<div class="tax-percent-icon">%</div>
						<div class="clear"></div>
						<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
						</div>
					</div>

					<div class="form-group">
					<?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'tax_names[]',
							'id'=>'tax_name_2',
							'size'=>'8',
							'class'=>'form-control form-inps margin10',
							'placeholder' => lang('common_tax_name'),
							'value'=> isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : ($this->Location->get_info_for_key('default_tax_2_name') ? $this->Location->get_info_for_key('default_tax_2_name') : $this->config->item('default_tax_2_name')))
						);?>
						</div>
                        <label class="col-sm-3 col-md-3 col-lg-2 control-label text-info wide">&nbsp;</label>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'tax_percents[]',
							'id'=>'tax_percent_name_2',
							'size'=>'3',
							'class'=>'form-control form-inps-tax',
							'placeholder' => lang('items_tax_percent'),
							'value'=> isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : '')
						);?>
						<div class="tax-percent-icon">%</div>
						<div class="clear"></div>
						<?php echo form_checkbox('tax_cumulatives[]', '1', (isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative']) ? (boolean)$item_tax_info[1]['cumulative'] : (boolean)$this->config->item('default_tax_2_cumulative'), 'class="cumulative_checkbox"'); ?>
					    <span class="cumulative_label">
						<?php echo lang('common_cumulative'); ?>
					    </span>
						</div>
					</div>
                     
					<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3"  style="visibility: <?php echo isset($item_tax_info[2]['name']) ? 'hidden' : 'visible';?>">
						<a href="javascript: void(0);" class="show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
					</div>
					<div class="more_taxes_container" style="display: <?php echo isset($item_tax_info[2]['name']) ? 'block' : 'none';?>">
						<div class="form-group">
						<?php echo form_label(lang('items_tax_3').':', 'tax_percent_3',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'tax_names[]',
								'id'=>'tax_name_3 noreset',
								'size'=>'8',
								'class'=>'form-control form-inps margin10',
								'placeholder' => lang('common_tax_name'),
								'value'=> isset($item_tax_info[2]['name']) ? $item_tax_info[2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name')))
							);?>
							</div>
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'id'=>'tax_percent_name_3',
								'size'=>'3',
								'class'=>'form-control form-inps-tax margin10',
								'placeholder' => lang('items_tax_percent'),
								'value'=> isset($item_tax_info[2]['percent']) ? $item_tax_info[2]['percent'] : '')
							);?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
							</div>
						</div>

						<div class="form-group">
						<?php echo form_label(lang('items_tax_4').':', 'tax_percent_4',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'tax_names[]',
								'id'=>'tax_name_4 noreset',
								'size'=>'8',
								'class'=>'form-control  form-inps margin10',
								'placeholder' => lang('common_tax_name'),
								'value'=> isset($item_tax_info[3]['name']) ? $item_tax_info[3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name')))
							);?>
							</div>
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'id'=>'tax_percent_name_4',
								'size'=>'3',
								'class'=>'form-control form-inps-tax', 
								'placeholder' => lang('items_tax_percent'),
								'value'=> isset($item_tax_info[3]['percent']) ? $item_tax_info[3]['percent'] : '')
							);?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
							</div>
						</div>
						
						<div class="form-group">
						<?php echo form_label(lang('items_tax_5').':', 'tax_percent_5',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'tax_names[]',
								'id'=>'tax_name_5 noreset',
								'size'=>'8',
								'class'=>'form-control  form-inps margin10',
								'placeholder' => lang('common_tax_name'),
								'value'=> isset($item_tax_info[4]['name']) ? $item_tax_info[4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name')))
							);?>
							</div>
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'id'=>'tax_percent_name_5',
								'size'=>'3',
								'class'=>'form-control form-inps-tax margin10',
								'placeholder' => lang('items_tax_percent'),
								'value'=> isset($item_tax_info[4]['percent']) ? $item_tax_info[4]['percent'] : '')
							);?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
							</div>
						</div>
					</div> <!--End more Taxes Container-->
                    <div class="clear"></div>
				</div>
				<?php foreach($locations as $location) { ?>
					<div class="widget-title widget-title1">
						<span class="icon">
							<i class="fa fa-align-justify"></i>									
						</span>
						<h5><?php echo $location->name; ?></h5>
					</div>

					<div class="form-group quantity-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
					<?php echo form_label(lang('items_quantity').':', '', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'locations['.$location->location_id.'][quantity]',
							'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->quantity !== NULL ? to_quantity($location_items[$location->location_id]->quantity): '',
								'class'=>'form-control form-inps',
						));?>
						</div>
					</div>		
					
					<?php if ($this->Location->count_all() > 1) {?>
						<div class="form-group reorder-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
						<?php echo form_label(lang('items_reorder_level').':', '', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'locations['.$location->location_id.'][reorder_level]',
								'value'=> $location_items[$location->location_id]->item_id !== '' &&  $location_items[$location->location_id]->reorder_level !== NULL ? to_quantity($location_items[$location->location_id]->reorder_level): '',
									'class'=>'form-control form-inps',
							));?>
							</div>
						</div>
					<?php } ?>
					<div class="form-group">
					<?php echo form_label(lang('items_location_at_store').':', '', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
						<?php echo form_input(array(
							'name'=>'locations['.$location->location_id.'][location]',
							'class'=>'form-control form-inps',
							'value'=> $location_items[$location->location_id]->item_id !== '' ? $location_items[$location->location_id]->location: ''
						));?>
						</div>
					</div>
					
					<?php if ($this->Location->count_all() > 1) {?>
							<div class="form-group override-prices-container">
							<?php echo form_label(lang('items_override_prices').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'locations['.$location->location_id.'][override_prices]',
									'class' => 'override_prices_checkbox form-control delete-checkbox',
									'value'=>1,
									'checked'=>(boolean)isset($location_items[$location->location_id]) && is_object($location_items[$location->location_id]) && $location_items[$location->location_id]->is_overwritten));
								?>
							</div>
						</div>
						<div class="item-location-price-container <?php if ($location_items[$location->location_id] === FALSE || !$location_items[$location->location_id]->is_overwritten){echo 'hidden';} ?>">	
							<?php if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="") { ?>
									<div class="form-group">
										<?php echo form_label(lang('items_cost_price').' ('.lang('items_without_tax').'):', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
											<div class="col-sm-9 col-md-9 col-lg-10">
												<?php echo form_input(array(
													'name'=>'locations['.$location->location_id.'][cost_price]',
													'size'=>'8',
													'class'=>'form-control form-inps',
													'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->cost_price ? to_currency_no_money($location_items[$location->location_id]->cost_price, 10): ''
												)
												);?>
										</div>
									</div>
								<?php 
								}
								else
								{
									echo form_hidden('locations['.$location->location_id.'][cost_price]', $location_items[$location->location_id]->item_id !== '' ? $location_items[$location->location_id]->cost_price: '');
								}
								?>

							<div class="form-group">
							<?php echo form_label(lang('items_unit_price').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][unit_price]',
									'size'=>'8',
								'class'=>'form-control form-inps',
									'value'=>$location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->unit_price ? to_currency_no_money($location_items[$location->location_id]->unit_price, 10) : ''
									)
								);?>
								</div>
							</div>

							<?php foreach($tiers as $tier) { ?>	
								<div class="form-group">
									<?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
									<div class='col-sm-9 col-md-9 col-lg-10'>
									<?php echo form_input(array(
										'name'=>'locations['.$location->location_id.'][item_tier]['.$tier->id.']',
										'size'=>'8',
								'class'=>'form-control margin10 form-inps', 
										'value'=> $location_tier_prices[$location->location_id][$tier->id] !== FALSE ? ($location_tier_prices[$location->location_id][$tier->id]->unit_price != NULL ? to_currency_no_money($location_tier_prices[$location->location_id][$tier->id]->unit_price, 10) : $location_tier_prices[$location->location_id][$tier->id]->percent_off): '')
									);?>

									<?php echo form_dropdown('locations['.$location->location_id.'][tier_type]['.$tier->id.']', $tier_type_options, $location_tier_prices[$location->location_id][$tier->id] !== FALSE && $location_tier_prices[$location->location_id][$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price');?>
									</div>
								</div>

							<?php } ?>

							<div class="form-group">
							<?php echo form_label(lang('items_promo_price').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							    <div class="col-sm-9 col-md-9 col-lg-10">
							    <?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][promo_price]',
							        'size'=>'8',
								'class'=>'form-control form-inps',
									'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->promo_price ? to_currency_no_money($location_items[$location->location_id]->promo_price, 10): ''
								)
							    );?>
							    </div>
							</div>

								<div class="form-group offset1">
								<?php echo form_label(lang('items_promo_start_date').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label text-info wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
						
								<div class="input-group date datepicker" data-date="<?php echo $location_items[$location->location_id]->item_id !== '' &&  $location_items[$location->location_id]->start_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->start_date)): ''; ?>" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
		  							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>

								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][start_date]',
							        'size'=>'8',
								'class'=>'form-control form-inps',
									 'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->start_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->start_date)): ''
									)
								);?>       
							    </div>
							</div>
							</div>
								<div class="form-group offset1">
								<?php echo form_label(lang('items_promo_end_date').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label text-info wide')); ?>
							    <div class="col-sm-9 col-md-9 col-lg-10">
							    	<div class="input-group date datepicker" data-date="<?php echo $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->end_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->end_date)): ''; ?>" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
		  								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>

										    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][end_date]',
										        'size'=>'8',
								'class'=>'form-control form-inps',
												 'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->end_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->end_date)): ''
										    	));
											?> 
								    </div>
								</div>
							</div>
						</div>
						<div class="form-group override-taxes-container">
							<?php echo form_label(lang('items_override_default_tax').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>

							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'locations['.$location->location_id.'][override_default_tax]',
									'class' => 'override_default_tax_checkbox form-control delete-checkbox',
									'value'=>1,
									'checked'=> $location_items[$location->location_id]->item_id !== '' ? (boolean)$location_items[$location->location_id]->override_default_tax: FALSE
									));
								?>
							</div>
						</div>

						<div class="tax-container <?php if ($location_items[$location->location_id] === FALSE || !$location_items[$location->location_id]->override_default_tax){echo 'hidden';} ?>">	
							<div class="form-group">
							<?php echo form_label(lang('items_tax_1').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_names][]',
									'size'=>'8',
									'class'=>'form-control form-inps margin10',
									'placeholder' => lang('common_tax_name'),
									'value' => isset($location_taxes[$location->location_id][0]['name']) ? $location_taxes[$location->location_id][0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name'))
								));?>
								</div>
                                <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_percents][]',
									'size'=>'3',
									'class'=>'form-control form-inps-tax margin10',
									'placeholder' => lang('items_tax_percent'),
									'value' => isset($location_taxes[$location->location_id][0]['percent']) ? $location_taxes[$location->location_id][0]['percent'] : ''
								));?>
								<div class="tax-percent-icon">%</div>
								<div class="clear"></div>
								<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
								</div>
							</div>
						<div class="form-group">
						<?php echo form_label(lang('items_tax_2').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'locations['.$location->location_id.'][tax_names][]',
								'size'=>'8',
								'class'=>'form-control form-inps margin10',
								'placeholder' => lang('common_tax_name'),
								'value' => isset($location_taxes[$location->location_id][1]['name']) ? $location_taxes[$location->location_id][1]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name'))
								)
							);?>
							</div>
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
							<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_input(array(
								'name'=>'locations['.$location->location_id.'][tax_percents][]',
								'size'=>'3',
								'class'=>'form-control form-inps-tax',
								'placeholder' => lang('items_tax_percent'),
								'value' => isset($location_taxes[$location->location_id][1]['percent']) ? $location_taxes[$location->location_id][1]['percent'] : ''
								)
							);?>
							<div class="tax-percent-icon">%</div>
							<div class="clear"></div>
							<?php echo form_checkbox('locations['.$location->location_id.'][tax_cumulatives][]', '1', isset($location_taxes[$location->location_id][1]['cumulative']) ? (boolean)$location_taxes[$location->location_id][1]['cumulative'] : ($this->Location->get_info_for_key('default_tax_2_cumulative') ? (boolean)$this->Location->get_info_for_key('default_tax_2_cumulative') : (boolean)$this->config->item('default_tax_2_cumulative')), 'class="cumulative_checkbox"'); ?>
						    <span class="cumulative_label">
							<?php echo lang('common_cumulative'); ?>
						    </span>
							</div> <!-- end col-sm-9...-->
						</div><!--End form-group-->
						
						<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3" style="visibility: <?php echo isset($location_taxes[$location->location_id][2]['name']) ? 'hidden' : 'visible';?>">
							<a href="javascript: void(0);" class="show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
						</div>
						
						<div class="more_taxes_container"  style="display: <?php echo isset($location_taxes[$location->location_id][2]['name']) ? 'block' : 'none';?>">
							<div class="form-group">
							<?php echo form_label(lang('items_tax_3').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_names][]',
									'size'=>'8',
									'class'=>'form-control form-inps margin10',
									'placeholder' => lang('common_tax_name'),
									'value' => isset($location_taxes[$location->location_id][2]['name']) ? $location_taxes[$location->location_id][2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name'))
								));?>
								</div>
                                <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_percents][]',
									'size'=>'3',
									'class'=>'form-control form-inps-tax',
									'placeholder' => lang('items_tax_percent'),
									'value' => isset($location_taxes[$location->location_id][2]['percent']) ? $location_taxes[$location->location_id][2]['percent'] : ''
								));?>
								<div class="tax-percent-icon">%</div>
								<div class="clear"></div>
								<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
								</div>
							</div>
							
							
							<div class="form-group">
							<?php echo form_label(lang('items_tax_4').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_names][]',
									'size'=>'8',
									'class'=>'form-control form-inps margin10',
									'placeholder' => lang('common_tax_name'),
									'value' => isset($location_taxes[$location->location_id][3]['name']) ? $location_taxes[$location->location_id][3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name'))
								));?>
								</div>
                                <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_percents][]',
									'size'=>'3',
									'class'=>'form-control form-inps-tax',
									'placeholder' => lang('items_tax_percent'),
									'value' => isset($location_taxes[$location->location_id][3]['percent']) ? $location_taxes[$location->location_id][3]['percent'] : ''
								));?>
								<div class="tax-percent-icon">%</div>
								<div class="clear"></div>
								<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
								</div>
							</div>
							
							
							
							<div class="form-group">
							<?php echo form_label(lang('items_tax_5').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_names][]',
									'size'=>'8',
									'class'=>'form-control form-inps margin10',
									'placeholder' => lang('common_tax_name'),
									'value' => isset($location_taxes[$location->location_id][4]['name']) ? $location_taxes[$location->location_id][4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name'))
								));?>
								</div>
                                <label class="col-sm-3 col-md-3 col-lg-2 control-label wide">&nbsp;</label>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][tax_percents][]',
									'size'=>'3',
									'class'=>'form-control form-inps-tax',
									'placeholder' => lang('items_tax_percent'),
									'value' => isset($location_taxes[$location->location_id][4]['percent']) ? $location_taxes[$location->location_id][4]['percent'] : ''
								));?>
								<div class="tax-percent-icon">%</div>
								<div class="clear"></div>
								<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
								</div>
							</div>
						</div><!-- End more taxes container-->
                        <div class="clear"></div>
					</div> <!-- End tax-container-->
				<?php } /*End if for multi locations*/ ?>
			<?php } /*End foreach for locations*/ ?>	
			
				<?php echo form_hidden('redirect', $redirect); ?>
				<?php echo form_hidden('sale_or_receiving', $sale_or_receiving); ?>
				
					<div class="form-actions">
				<?php
				echo form_submit(array(
					'name'=>'submitf',
					'id'=>'submitf',
					'value'=>lang('common_submit'),
					'class'=>'submit_button btn btn-primary')
				);
				?>
				</div>
			<?php echo form_close(); ?>
			
			<div class="item_navigation">
				<?php
				if (isset($prev_item_id) && $prev_item_id)
				{
					echo '<div class="previous_item">';
						echo anchor('items/view/'.$prev_item_id, '&laquo; '.lang('items_prev_item'));
					echo '</div>';
				}
				?>

				<?php
				if (isset($next_item_id) && $next_item_id)
				{
					echo '<div class="next_item">';
						echo anchor('items/view/'.$next_item_id,lang('items_next_item').' &raquo;');
					echo '</div>';
				}
				?>
			</div>
			
			</div>
		</div>
	</div>
</div>
		

<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
    setTimeout(function(){$(":input:visible:first","#item_form").focus();},100);
    $('#image_id').imagePreview({ selector : '#avatar' }); // Custom preview container
	
	$('.datepicker').datepicker({
		format: <?php echo json_encode(get_js_date_format()); ?>
	});
   	
	$(".override_default_tax_checkbox, .override_prices_checkbox").change(function()
	{
		$(this).parent().parent().next().toggleClass('hidden')
	});
	
	$("#is_service").change(function()
	{
		if ($(this).prop('checked'))
		{
			$(".quantity-input").addClass('hidden');			
			$(".reorder-input").addClass('hidden');			
		}
		else
		{
			$(".quantity-input").removeClass('hidden');
			$(".reorder-input").removeClass('hidden');
		}
	});

	$( "#category" ).autocomplete({
		source: "<?php echo site_url('items/suggest_category');?>",
		delay: 10,
		autoFocus: false,
		minLength: 0
	});

	$('#item_form').validate({
		submitHandler:function(form)
		{
			$.post('<?php echo site_url("items/check_duplicate");?>', {term: $('#name').val()},function(data) {
			<?php if(!$item_info->item_id) {  ?>
			if(data.duplicate)
				{
					
					if(confirm(<?php echo json_encode(lang('items_duplicate_exists'));?>))
					{
						doItemSubmit(form);
					}
					else 
					{
						return false;
					}
				}
			<?php }  else ?>
			 {
				doItemSubmit(form);
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
		<?php if(!$item_info->item_id) {  ?>
			item_number:
			{
				remote: 
				    { 
					url: "<?php echo site_url('items/item_number_exists');?>", 
					type: "post"
					
				    } 
			},
		<?php } ?>
		
		<?php foreach($tiers as $tier) { ?>
			"<?php echo 'item_tier['.$tier->id.']'; ?>":
			{
				number: true
			},
		<?php } ?>
		
		<?php foreach($locations as $location) { ?>
			"<?php echo 'locations['.$location->location_id.'][quantity]'; ?>":
			{
				number: true
			},
			"<?php echo 'locations['.$location->location_id.'][reorder_level]'; ?>":
			{
				number: true
			},
			"<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>":
			{
				number: true
			},
			"<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>":
			{
				number: true
			},			
			"<?php echo 'locations['.$location->location_id.'][promo_price]'; ?>":
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
			cost_price:
			{
				required:true,
				number:true
			},

			unit_price:
			{
				required:true,
				number:true
			},
			promo_price:
			{
				number: true
			},
			reorder_level:
			{
				number:true
			},
   		},
		messages:
		{			
			<?php if(!$item_info->item_id) {  ?>
			item_number:
			{
				remote: <?php echo json_encode(lang('items_item_number_exists')); ?>
				   
			},
			<?php } ?>
			
			<?php foreach($tiers as $tier) { ?>
				"<?php echo 'item_tier['.$tier->id.']'; ?>":
				{
					number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
				},
			<?php } ?>
			
			<?php foreach($locations as $location) { ?>
				"<?php echo 'locations['.$location->location_id.'][quantity]'; ?>":
				{
					number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
				},
				"<?php echo 'locations['.$location->location_id.'][reorder_level]'; ?>":
				{
					number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
				},
				"<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>":
				{
					number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
				},
				"<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>":
				{
					number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
				},			
				"<?php echo 'locations['.$location->location_id.'][promo_price]'; ?>":
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
			cost_price:
			{
				required:<?php echo json_encode(lang('items_cost_price_required')); ?>,
				number:<?php echo json_encode(lang('items_cost_price_number')); ?>
			},
			unit_price:
			{
				required:<?php echo json_encode(lang('items_unit_price_required')); ?>,
				number:<?php echo json_encode(lang('items_unit_price_number')); ?>
			},
			promo_price:
			{
				number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
			}
		}
	});
});

var submitting = false;

function doItemSubmit(form)
{
	if (submitting) return;
	submitting = true;
	$("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
	$(form).ajaxSubmit({
	success:function(response)
	{
		$("#form").unmask();
		submitting = false;
		gritter(<?php echo json_encode(lang('common_success')); ?>+' #' + response.item_id,response.message,response.success ? 'gritter-item-success' : 'gritter-item-error',false,false);

		if(response.redirect==1 && response.success)
		{ 
			if (response.sale_or_receiving == 'sale')
			{
				$.post('<?php echo site_url("sales/add");?>', {item: response.item_id}, function()
				{
					window.location.href = '<?php echo site_url('sales'); ?>'
				});
			}
			else
			{
				$.post('<?php echo site_url("receivings/add");?>', {item: response.item_id}, function()
				{
					window.location.href = '<?php echo site_url('receivings'); ?>'
				});
			}
		}
		else if(response.redirect==2 && response.success)
		{
			window.location.href = '<?php echo site_url('items'); ?>'
		}

		
		<?php if(!$item_info->item_id) { ?>
		//If we have a new item, make sure we hide the tax containers to "reset"
		$(".tax-container").addClass('hidden');
		$(".item-location-price-container").addClass('hidden');
		
		//Make the quantity inputs show up again in case they were hidden
		$(".quantity-input").removeClass('hidden');
		$(".reorder-input").removeClass('hidden');
		
		<?php } ?>
	},
	<?php if(!$item_info->item_id) { ?>
	resetForm: true,
	<?php } ?>
	dataType:'json'
	});
}

</script>
<?php $this->load->view('partial/footer'); ?>