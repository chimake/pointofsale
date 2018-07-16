<?php $this->load->view("partial/header"); ?>

<div id="content-header">
	<h1>	<i class="icon fa fa-bar-chart-o"></i>
		<?php echo lang('reports_reports'); ?>
	</h1>
</div>


<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

<div class="row report-listing">
	<div class="col-md-6  ">
		<div class="panel">
			<div class="panel-body">
				<div class="list-group parent-list">
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_categories', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="categories"><i class="fa fa-th"></i>	<?php echo lang('reports_categories'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_sales_generator', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="custom-report">
							<i class="fa fa-search"></i>	<?php echo lang('reports_sales_generator'); ?>
						</a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_customers', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="customers"><i class="fa fa-group"></i>	<?php echo lang('reports_customers'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_deleted_sales', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>	
						<a href="#" class="list-group-item" id="deleted-sales"><i class="fa fa-trash-o"></i>	<?php echo lang('reports_deleted_sales'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_discounts', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="discounts"><i class="fa fa-magic"></i>	<?php echo lang('reports_discounts'); ?></a>
					<?php } ?>

					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_employees', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="employees"><i class="fa fa-user"></i>	<?php echo lang('reports_employees'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_giftcards', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="giftcards"><i class="fa fa-credit-card"></i>	<?php echo lang('reports_giftcards'); ?></a>
					<?php } ?>

					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_inventory_reports', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>					
						<a href="#" class="list-group-item" id="inventory"><i class="fa fa-table"></i>	<?php echo lang('reports_inventory_reports'); ?></a>
					<?php } ?>

					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_item_kits', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>					
						<a href="#" class="list-group-item" id="item-kits"><i class="fa fa-inbox"></i>	<?php echo lang('module_item_kits'); ?></a>
					<?php } ?>


					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_items', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>					
						<a href="#" class="list-group-item" id="items"><i class="fa fa-table"></i>	<?php echo lang('reports_items'); ?></a>
					<?php } ?>

					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_payments', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>					
						<a href="#" class="list-group-item" id="payments"><i class="fa fa-money"></i>	<?php echo lang('reports_payments'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_profit_and_loss', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="profit-and-loss"><i class="fa fa-shopping-cart"></i>	<?php echo lang('reports_profit_and_loss'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_receivings', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="receivings"><i class="fa fa-cloud-download"></i>	<?php echo lang('reports_receivings'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_register_log', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<?php if ($this->config->item('track_cash')) { ?>
							<a href="#" class="list-group-item" id="register-log"><i class="fa fa-search"></i>	<?php echo lang('reports_register_log_title'); ?></a>
						<?php } ?>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_sales', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="sales"><i class="fa fa-shopping-cart"></i>	<?php echo lang('reports_sales'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_store_account', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<?php if($this->config->item('customers_store_accounts')) { ?>
							<a href="#" class="list-group-item" id="store-accounts"><i class="fa fa-credit-card"></i> <?php echo lang('reports_store_account'); ?></a>
						<?php } ?>
					<?php } ?>

					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_suppliers', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="suppliers"><i class="fa fa-download"></i>	<?php echo lang('reports_suppliers'); ?></a>
					<?php } ?>
					
					<?php
					if ($this->Employee->has_module_action_permission('reports', 'view_taxes', $this->Employee->get_logged_in_employee_info()->person_id))
					{
					?>
						<a href="#" class="list-group-item" id="taxes"><i class="fa fa-book"></i>	<?php echo lang('reports_taxes'); ?></a>
					<?php } ?>
				</div>
			</div>
		</div> <!-- /panel -->
	</div>
	<div class="col-md-6" id="report_selection">
		<div class="panel">
			<div class="panel-body child-list">
			<h3 class="page-header text-info">&laquo; <?php echo lang('reports_make_a_selection')?></h3>
				<div class="list-group custom-report hidden">
					<a href="<?php echo site_url('reports/sales_generator');?>" class="list-group-item ">
						<i class="fa fa-search report-icon"></i>  <?php echo lang('reports_sales_search'); ?>
					</a>
				</div>
				<div class="list-group customers hidden">
					<a class="list-group-item" href="<?php echo site_url('reports/graphical_summary_customers');?>" ><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/summary_customers');?>" ><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/specific_customer');?>" ><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group employees hidden">
					<a class="list-group-item" href="<?php echo site_url('reports/graphical_summary_employees');?>" ><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/summary_employees');?>" ><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/specific_employee');?>" ><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group sales hidden">
					<a class="list-group-item" href="<?php echo site_url('reports/graphical_summary_sales');?>" ><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/summary_sales');?>" ><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales');?>" ><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group deleted-sales hidden">
					<a href="<?php echo site_url('reports/deleted_sales');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group register-log hidden">
					<a href="<?php echo site_url('reports/detailed_register_log');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group categories hidden">
					<a href="<?php echo site_url('reports/graphical_summary_categories');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_categories');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
				</div>
				<div class="list-group discounts hidden">
					<a href="<?php echo site_url('reports/graphical_summary_discounts');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_discounts');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
				</div>
				<div class="list-group items hidden">
					<a href="<?php echo site_url('reports/graphical_summary_items');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_items');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
				</div>
				<div class="list-group item-kits hidden">
					<a href="<?php echo site_url('reports/graphical_summary_item_kits');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_item_kits');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
				</div>
				<div class="list-group payments hidden">
					<a href="<?php echo site_url('reports/graphical_summary_payments');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_payments');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
				</div>
				<div class="list-group suppliers hidden">
					<a href="<?php echo site_url('reports/graphical_summary_suppliers');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_suppliers');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
					<a href="<?php echo site_url('reports/specific_supplier');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group taxes hidden">
					<a href="<?php echo site_url('reports/graphical_summary_taxes');?>" class="list-group-item"><i class="fa fa-bar-chart-o"></i> <?php echo lang('reports_graphical_reports'); ?></a>
					<a href="<?php echo site_url('reports/summary_taxes');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
				</div>
				<div class="list-group receivings hidden">
					<a href="<?php echo site_url('reports/detailed_receivings');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group inventory hidden">
					<a href="<?php echo site_url('reports/inventory_low');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_low_inventory'); ?></a>
					<a href="<?php echo site_url('reports/inventory_summary');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_inventory_summary'); ?></a>
				</div>
				<div class="list-group giftcards hidden">
					<a href="<?php echo site_url('reports/summary_giftcards');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>			
					<a href="<?php echo site_url('reports/detailed_giftcards');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group store-accounts hidden">
					<a href="<?php echo site_url('reports/store_account_statements');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_store_account_statements'); ?></a>
					<a href="<?php echo site_url('reports/summary_store_accounts');?>" class="list-group-item"><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
					<a href="<?php echo site_url('reports/specific_customer_store_account');?>" class="list-group-item"><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
				<div class="list-group profit-and-loss hidden">
					<a class="list-group-item" href="<?php echo site_url('reports/summary_profit_and_loss');?>" ><i class="fa fa-building-o"></i> <?php echo lang('reports_summary_reports'); ?></a>
					<a class="list-group-item" href="<?php echo site_url('reports/detailed_profit_and_loss');?>" ><i class="fa fa-calendar"></i> <?php echo lang('reports_detailed_reports'); ?></a>
				</div>
			</div>
		</div> <!-- /panel -->
	</div>
</div>
</div>
<script type="text/javascript">
 $('.parent-list a').click(function(e){
 	e.preventDefault();
 	$('.parent-list a').removeClass('active');
 	$(this).addClass('active');
 	var currentClass='.child-list .'+ $(this).attr("id");
 	$('.child-list .page-header').html($(this).html());
 	$('.child-list .list-group').addClass('hidden');
 	$(currentClass).removeClass('hidden');
	
	$('html, body').animate({
	    scrollTop: $("#report_selection").offset().top
	 }, 500);
 });
 </script>


<?php $this->load->view("partial/footer"); ?>