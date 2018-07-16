<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1><i class="icon fa fa-dashboard"></i> <?php echo lang('common_dashboard'); ?></h1>
</div>
<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
	
</div>
<div class="clear"></div>
<div class="text-center">					
	<h3><strong style="font-size: 15px; color: #0000CC;">WELCOME TO POS MANAGEMENT SYSTEM</strong></h3>
	<ul class="quick-actions">
		<?php foreach($allowed_modules->result() as $module) { ?>
		<li <?php echo $module->module_id==$this->uri->segment(1)  ? 'class="active"' : ''; ?>  > 
			<a class="right" href="<?php echo site_url("$module->module_id");?>">	<i class="text-info fa fa-<?php echo $module->icon; ?> left fa "></i> <?php echo lang("module_".$module->module_id) ?></a>
		</li>
		<?php } ?>
	</ul>

	<?php if (!$this->config->item('hide_dashboard_statistics')) { ?>
	
		<div class="row">
			<div class="widget-box">
				<div class="widget-title"><span class="icon"><i class="fa fa-signal"></i></span><h5> Statistics</h5></div>
				<div class="widget-content">
					<div class="row">
						<div class="col-md-4">
							<ul class="site-stats">
								<li><a href="<?php echo site_url('items'); ?>"><h3><i class="fa fa-shopping-cart"></i>  <?php echo lang('common_total')." ".lang('module_items'); ?> : <strong><?php echo $total_items; ?></strong></h3></a> </li>
								<li><a href="<?php echo site_url('item_kits'); ?>"><h3> <i class="fa fa-inbox"></i>  <?php echo lang('common_total')." ".lang('module_item_kits'); ?>  :  <strong><?php echo $total_item_kits; ?></strong></h3></a></li>
							</ul>
						</div>
						<div class="col-md-4">
							<ul class="site-stats">
								<li>  <a href="<?php echo site_url('customers'); ?>"><h3> <i class="fa fa-group"></i>  <?php echo lang('common_total')." ".lang('module_customers'); ?>  : <strong><?php echo $total_customers; ?></strong></h3></a></li>
								<li> <a href="<?php echo site_url('employees'); ?>"><h3> <i class="fa fa-user"></i>  <?php echo lang('common_total')." ".lang('module_employees'); ?>  :  <strong><?php echo $total_employees; ?></strong></h3></a></li>
							</ul>
						</div>
						<div class="col-md-4">
							<ul class="site-stats">
								<li> <a href="<?php echo site_url('sales'); ?>"><h3> <i class="fa fa-download"></i>  <?php echo lang('common_total')." ".lang('module_sales'); ?>  : <strong><?php echo $total_sales; ?></strong> </h3></a></li>
								<li>  <a href="<?php echo site_url('giftcards'); ?>"><h3> <i class="fa fa-credit-card"></i>  <?php echo lang('common_total')." ".lang('module_giftcards'); ?>  : <strong><?php echo $total_giftcards; ?></strong></h3></a></li>
							</ul>
						</div>
					</div>							
				</div>

			</div>		
		</div>
	<?php } ?>
	</div>
<?php $this->load->view("partial/footer"); ?>