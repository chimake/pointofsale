<?php $this->load->view("partial/header"); ?>
	<div id="content-header" class="hidden-print">
		<h1><i class="fa fa-cogs"></i> <?php echo lang('module_'.$controller_name); ?></h1>
	</div>

	<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	</div>
	<div class="clear"></div>

	<div class="pull-right">
		<div class="row">
			<div class="col-md-12 ">					
			</div>
		</div>
	</div>
				<ul class="list-inline pull-right">
					<li> <?php echo anchor('config/backup', lang('config_backup_database'), array('class' => 'btn btn-primary text-white pull-right dbBackup')); ?> </li>
					<li> <?php echo anchor('config/optimize',lang('config_optimize_database'), array('class' => 'btn btn-primary text-white pull-right dbOptimize')); ?> </li>
					<li><i id="spin" class="fa fa-spinner fa fa-spin fa fa-3x  hidden"></i> &nbsp;</li>
				</ul>

	<div class="">
		<div class="row">
			<?php echo lang('config_looking_for_location_settings').' '.anchor($this->Location->count_all() > 1 ? 'locations' : 'locations/view/1', lang('module_locations').' '.lang('config_module'));?>
			
			<div class="col-md-12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="fa fa-align-justify"></i>									
						</span>
						<h5><?php echo lang("config_info"); ?></h5>

					</div>
					<div class="widget-content nopadding">
						<?php echo form_open_multipart('config/save/',array('id'=>'config_form','class'=>'form-horizontal', 'autocomplete'=> 'off'));  ?>
							
							<div class="form-group">	
								<?php echo form_label(lang('config_company_logo').':', 'company_logo',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo form_upload(array(
										'name'=>'company_logo',
										'id'=>'company_logo',
										'value'=>$this->config->item('company_logo')));
									?>		
								</div>	
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_delete_logo').':', 'delete_logo',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo form_checkbox('delete_logo', '1');?>
								</div>	
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_company').':', 'company',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
									'name'=>'company',
									'id'=>'company',
									'value'=>$this->config->item('company')));?>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_prefix').':', 'sale_prefix',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
									'name'=>'sale_prefix',
									'id'=>'sale_prefix',
									'value'=>$this->config->item('sale_prefix')));?>
								</div>
							</div>
							
							<div class="form-group">	
							<?php echo form_label(lang('common_prices_include_tax').':', 'prices_include_tax',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'prices_include_tax',
									'id'=>'prices_include_tax',
									'value'=>'prices_include_tax',
									'checked'=>$this->config->item('prices_include_tax')));?>
								</div>
							</div>
							

							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_1').':', 'default_tax_1_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'default_tax_1_name',
									'placeholder' => lang('common_tax_name'),
									'id'=>'default_tax_1_name',
									'size'=>'10',
									'value'=>$this->config->item('default_tax_1_name')!==FALSE ? $this->config->item('default_tax_1_name') : lang('items_sales_tax_1')));?>
								</div>
								
								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps-tax',
									'placeholder' => lang('items_tax_percent'),
									'name'=>'default_tax_1_rate',
									'id'=>'default_tax_1_rate',
									'size'=>'4',
									'value'=>$this->config->item('default_tax_1_rate')));?>
									<div class="tax-percent-icon">%</div>
									<div class="clear"></div>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_2').':', 'default_tax_1_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'default_tax_2_name',
									'placeholder' => lang('common_tax_name'),
									'id'=>'default_tax_2_name',
									'size'=>'10',
									'value'=>$this->config->item('default_tax_2_name')!==FALSE ? $this->config->item('default_tax_2_name') : lang('items_sales_tax_2')));?>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-5">
									<?php echo form_input(array(
									'class'=>'form-control form-inps-tax',	
									'name'=>'default_tax_2_rate',
									'placeholder' => lang('items_tax_percent'),
									'id'=>'default_tax_2_rate',
									'size'=>'4',
									'value'=>$this->config->item('default_tax_2_rate')));?>
									<div class="tax-percent-icon">%</div>
									<div class="clear"></div>
									<?php echo form_checkbox('default_tax_2_cumulative', '1', $this->config->item('default_tax_2_cumulative') ? true : false, 'class="cumulative_checkbox"');  ?>
	    							<span class="cumulative_label">
										<?php echo lang('common_cumulative'); ?>
	    							</span>
								</div>
								
								<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3" style="display: <?php echo $this->config->item('default_tax_3_rate') ? 'none' : 'block';?>">
									<a href="javascript: void(0);" class="show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
								</div>
								
								<div class="more_taxes_container" style="display: <?php echo $this->config->item('default_tax_3_rate') ? 'block' : 'none';?>">
									<div class="form-group">	
										<?php echo form_label(lang('config_default_tax_rate_3').':', 'default_tax_3_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
										<div class="col-sm-4 col-md-4 col-lg-5">
											<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'default_tax_3_name',
											'placeholder' => lang('common_tax_name'),
											'id'=>'default_tax_3_name',
											'size'=>'10',
											'value'=>$this->config->item('default_tax_3_name')!==FALSE ? $this->config->item('default_tax_3_name') : lang('items_sales_tax_3')));?>
										</div>
								
										<div class="col-sm-4 col-md-4 col-lg-5">
											<?php echo form_input(array(
											'class'=>'form-control form-inps-tax',
											'placeholder' => lang('items_tax_percent'),
											'name'=>'default_tax_3_rate',
											'id'=>'default_tax_3_rate',
											'size'=>'4',
											'value'=>$this->config->item('default_tax_3_rate')));?>
											<div class="tax-percent-icon">%</div>
											<div class="clear"></div>
										</div>
									</div>
									
									
									<div class="form-group">	
										<?php echo form_label(lang('config_default_tax_rate_4').':', 'default_tax_4_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
										<div class="col-sm-4 col-md-4 col-lg-5">
											<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'placeholder' => lang('common_tax_name'),
											'name'=>'default_tax_4_name',
											'id'=>'default_tax_4_name',
											'size'=>'10',
											'value'=>$this->config->item('default_tax_4_name')!==FALSE ? $this->config->item('default_tax_4_name') : lang('items_sales_tax_4')));?>
										</div>
								
										<div class="col-sm-4 col-md-4 col-lg-5">
											<?php echo form_input(array(
											'class'=>'form-control form-inps-tax',
											'placeholder' => lang('items_tax_percent'),
											'name'=>'default_tax_4_rate',
											'id'=>'default_tax_4_rate',
											'size'=>'4',
											'value'=>$this->config->item('default_tax_4_rate')));?>
											<div class="tax-percent-icon">%</div>
											<div class="clear"></div>
										</div>
									</div>
									
									<div class="form-group">	
										<?php echo form_label(lang('config_default_tax_rate_5').':', 'default_tax_5_rate',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
										<div class="col-sm-4 col-md-4 col-lg-5">
											<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'placeholder' => lang('common_tax_name'),
											'name'=>'default_tax_5_name',
											'id'=>'default_tax_5_name',
											'size'=>'10',
											'value'=>$this->config->item('default_tax_5_name')!==FALSE ? $this->config->item('default_tax_5_name') : lang('items_sales_tax_5')));?>
										</div>
								
										<div class="col-sm-4 col-md-4 col-lg-5">
											<?php echo form_input(array(
											'class'=>'form-control form-inps-tax',
											'placeholder' => lang('items_tax_percent'),
											'name'=>'default_tax_5_rate',
											'id'=>'default_tax_5_rate',
											'size'=>'4',
											'value'=>$this->config->item('default_tax_5_rate')));?>
											<div class="tax-percent-icon">%</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('config_currency_symbol').':', 'currency_symbol',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
									'name'=>'currency_symbol',
									'id'=>'currency_symbol',
									'value'=>$this->config->item('currency_symbol')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_website').':', 'website',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'website',
									'id'=>'website',
									'value'=>$this->config->item('website')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('language', array(
									'english'  => 'English',
									'indonesia'    => 'Indonesia',
									'spanish'   => 'Spanish', 
									'french'    => 'French',
									'italian'    => 'Italian'),
									$this->Appconfig->get_raw_language_value());
									?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_date_format').':', 'date_format',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('date_format', array(
									'middle_endian'    => '12/30/2000',
									'little_endian'  => '30-12-2000',
									'big_endian'   => '2000-12-30'), $this->config->item('date_format'));
									?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_time_format').':', 'time_format',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('time_format', array(
									'12_hour'    => '1:00 PM',
									'24_hour'  => '13:00'
									), $this->config->item('time_format'));
									?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_print_after_sale').':', 'print_after_sale',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'print_after_sale',
									'id'=>'print_after_sale',
									'value'=>'print_after_sale',
									'checked'=>$this->config->item('print_after_sale')));?>
								</div>
							</div>

									
							<div class="form-group">	
							<?php echo form_label(lang('config_customers_store_accounts').':', 'customers_store_accounts',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'customers_store_accounts',
									'id'=>'customers_store_accounts',
									'value'=>'customers_store_accounts',
									'checked'=>$this->config->item('customers_store_accounts')));?>
								</div>
							</div>
									
							<div class="form-group">	
							<?php echo form_label(lang('config_round_cash_on_sales').':', 'round_cash_on_sales',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'round_cash_on_sales',
									'id'=>'round_cash_on_sales',
									'value'=>'round_cash_on_sales',
									'checked'=>$this->config->item('round_cash_on_sales')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_automatically_email_receipt').':', 'automatically_email_receipt',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'automatically_email_receipt',
									'id'=>'automatically_email_receipt',
									'value'=>'automatically_email_receipt',
									'checked'=>$this->config->item('automatically_email_receipt')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_barcode_price_include_tax').':', 'barcode_price_include_tax',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'barcode_price_include_tax',
									'id'=>'barcode_price_include_tax',
									'value'=>'barcode_price_include_tax',
									'checked'=>$this->config->item('barcode_price_include_tax')));?>
								</div>
							</div>


							<div class="form-group">	
							<?php echo form_label(lang('config_hide_signature').':', 'hide_signature',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'hide_signature',
									'id'=>'hide_signature',
									'value'=>'hide_signature',
									'checked'=>$this->config->item('hide_signature')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('disable_confirmation_sale').':', 'disable_confirmation_sale',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'disable_confirmation_sale',
									'id'=>'disable_confirmation_sale',
									'value'=>'disable_confirmation_sale',
									'checked'=>$this->config->item('disable_confirmation_sale')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_number_of_items_per_page').':', 'number_of_items_per_page',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('number_of_items_per_page', 
								 array(
									'20'=>'20',
									'50'=>'50',
									'100'=>'100',
									'200'=>'200',
									'500'=>'500'
									), $this->config->item('number_of_items_per_page') ? $this->config->item('number_of_items_per_page') : '20');
									?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_track_cash').':', 'track_cash',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'track_cash',
									'id'=>'track_cash',
									'value'=>'1',
									'checked'=>$this->config->item('track_cash')));?>
								</div>
							</div>


							<div class="form-group">	
							<?php echo form_label(lang('sales_hide_suspended_sales_in_reports').':', 'hide_suspended_sales_in_reports',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'hide_suspended_sales_in_reports',
									'id'=>'hide_suspended_sales_in_reports',
									'value'=>'1',
									'checked'=>$this->config->item('hide_suspended_sales_in_reports')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_hide_store_account_payments_in_reports').':', 'hide_store_account_payments_in_reports',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'hide_store_account_payments_in_reports',
									'id'=>'hide_store_account_payments_in_reports',
									'value'=>'1',
									'checked'=>$this->config->item('hide_store_account_payments_in_reports')));?>
								</div>
							</div>
							
							<div class="form-group">	
							<?php echo form_label(lang('config_change_sale_date_when_suspending').':', 'change_sale_date_when_suspending',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'change_sale_date_when_suspending',
									'id'=>'change_sale_date_when_suspending',
									'value'=>'1',
									'checked'=>$this->config->item('change_sale_date_when_suspending')));?>
								</div>
							</div>


							<div class="form-group">	
							<?php echo form_label(lang('config_change_sale_date_when_completing_suspended_sale').':', 'change_sale_date_when_completing_suspended_sale',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'change_sale_date_when_completing_suspended_sale',
									'id'=>'change_sale_date_when_completing_suspended_sale',
									'value'=>'1',
									'checked'=>$this->config->item('change_sale_date_when_completing_suspended_sale')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_show_receipt_after_suspending_sale').':', 'show_receipt_after_suspending_sale',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'show_receipt_after_suspending_sale',
									'id'=>'show_receipt_after_suspending_sale',
									'value'=>'1',
									'checked'=>$this->config->item('show_receipt_after_suspending_sale')));?>
								</div>
							</div>
														
							<div class="form-group">	
							<?php echo form_label(lang('config_automatically_calculate_average_cost_price_from_receivings').':', 'calculate_average_cost_price_from_receivings',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'calculate_average_cost_price_from_receivings',
									'id'=>'calculate_average_cost_price_from_receivings',
									'value'=>'1',
									'checked'=>$this->config->item('calculate_average_cost_price_from_receivings')));?>
								</div>
							</div>
														
							<div id="average_cost_price_from_receivings_methods">
								<div class="form-group">	
								<?php echo form_label($this->lang->line('config_averaging_method').':', 'averaging_method',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
									<div class="col-sm-9 col-md-9 col-lg-10">
										<?php echo form_dropdown('averaging_method', array('moving_average' => lang('config_moving_average'), 'historical_average' => lang('config_historical_average')), $this->config->item('averaging_method'),'class="span2"'); ?>
									</div>
								</div>
							</div>
							
							
							<div class="form-group">	
							<?php echo form_label(lang('config_hide_dashboard_statistics').':', 'hide_dashboard_statistics',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_checkbox(array(
									'name'=>'hide_dashboard_statistics',
									'id'=>'hide_dashboard_statistics',
									'value'=>'1',
									'checked'=>$this->config->item('hide_dashboard_statistics')));?>
								</div>
							</div>
							
							<div class="form-group">	
							<?php echo form_label(lang('common_return_policy').':', 'return_policy',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_textarea(array(
									'name'=>'return_policy',
									'id'=>'return_policy',
									'class'=>'form-textarea',
									'rows'=>'4',
									'cols'=>'30',
									'value'=>$this->config->item('return_policy')));?>
								</div>
							</div>
							
							<div class="form-group">	
							<?php echo form_label(lang('config_payment_types').':', 'additional_payment_types',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
									<?php echo lang('sales_cash'); ?>, 
									<?php echo lang('sales_check'); ?>, 
									<?php echo lang('sales_giftcard'); ?>, 
									<?php echo lang('sales_debit'); ?>, 
									<?php echo lang('sales_credit'); ?>,
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'additional_payment_types',
										'id'=>'additional_payment_types',
										'size'=> 40,
										'value'=>$this->config->item('additional_payment_types')));?>
								</div>
							</div>

							<div class="form-group">	
							<?php echo form_label(lang('config_default_payment_type').':', 'default_payment_type',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_dropdown('default_payment_type', $payment_options, $this->config->item('default_payment_type'),'class="span2"'); ?>
								</div>
							</div>
							
							
							<div class="form-group">	
							<?php echo form_label(lang('config_price_tiers').':', 'tiers',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
								<div class="col-sm-9 col-md-9 col-lg-10">
								<table id="price_tiers">
								<thead>
									<tr>
									<th><?php echo lang('items_tier_name'); ?></th>
									<th><?php echo lang('common_delete'); ?></th>
									</tr>
								</thead>
								
								<tbody>
								<?php foreach($tiers->result() as $tier) { ?>
									<tr><td><input type="text" name="tiers_to_edit[<?php echo $tier->id; ?>]" value="<?php echo H($tier->name); ?>" /></td><td>
									<?php if ($this->Employee->has_module_action_permission('items', 'delete', $this->Employee->get_logged_in_employee_info()->person_id) || $this->Employee->has_module_action_permission('item_kits', 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
									<a class="delete_tier" href="javascript:void(0);" data-tier-id='<?php echo $tier->id; ?>'><?php echo lang('common_delete'); ?></a>
									<?php }else { ?>
										&nbsp;
									<?php } ?>
									</td><tr>
								<?php } ?>
								</tbody>
								</table>
								
								<a href="javascript:void(0);" id="add_tier"><?php echo lang('config_add_tier'); ?></a>
								</div>
							</div>
							<div class="form-actions">
							<?php echo form_submit(array(
								'name'=>'submitf',
								'id'=>'submitf',
								'value'=>lang('common_submit'),
								'class'=>'submit_button btn btn-primary float_right')); ?>
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
	$(".delete_tier").click(function()
	{
		$("#config_form").append('<input type="hidden" name="tiers_to_delete[]" value="'+$(this).data('tier-id')+'" />');
		$(this).parent().parent().remove();
	});
	
	$("#add_tier").click(function()
	{
		$("#price_tiers tbody").append('<tr><td><input type="text" class="tiers_to_add" name="tiers_to_add[]" value="" /></td><td>&nbsp;</td></tr>');
	});
	
	$(".dbOptimize").click(function(event)
	{
		event.preventDefault();
		$('#spin').removeClass('hidden');
		
		$.getJSON($(this).attr('href'), function(response) 
		{
			$('#spin').addClass('hidden');
			alert(response.message);
		});
		
	});
	var submitting = false;
	$('#config_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).ajaxSubmit({
			success:function(response)
			{
				//Don't let the tiers be double submitted, so we change the name
				$(".tiers_to_add").attr('name', 'tiers_added[]');
				if(response.success)
				{
					gritter(<?php echo json_encode(lang('common_success')); ?>,response.message,'gritter-item-success',false,false);
				}
				else
				{
					gritter(<?php echo json_encode(lang('common_error')); ?>,response.message,'gritter-item-error',false,false);
				}
				submitting = false;
			},
			dataType:'json'
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
    		company: "required",
    		sale_prefix: "required",
			return_policy:
			{
				required: true
			}
   	},
		messages: 
		{
     		company: <?php echo json_encode(lang('config_company_required')); ?>,
     		sale_prefix: <?php echo json_encode(lang('config_sale_prefix_required')); ?>,
			return_policy:
			{
				required:<?php echo json_encode(lang('config_return_policy_required')); ?>
			},
	
		}
	});
	
});

$("#calculate_average_cost_price_from_receivings").change(check_calculate_average_cost_price_from_receivings).ready(check_calculate_average_cost_price_from_receivings);

function check_calculate_average_cost_price_from_receivings()
{
	if($("#calculate_average_cost_price_from_receivings").prop('checked'))
	{
		$("#average_cost_price_from_receivings_methods").show();
	}
	else
	{
		$("#average_cost_price_from_receivings_methods").hide();
	}
}

</script>
<?php $this->load->view("partial/footer"); ?>


