<?php $this->load->view("partial/header"); ?>
<div id="content-header">
	<h1 > <i class="fa fa-bar-chart"> </i><?php echo lang('reports_reports'); ?> - <?php echo lang('reports_profit_and_loss') ?>	</h1>
</div>
<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="row">
<div class="col-md-12 center" style="text-align: center;">


<ul class="stat-boxes">
	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_sales'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['sales_total']); ?></strong>
		</div>
	</li>
	
	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_returns'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['returns_total']); ?></strong>
		</div>
	</li>
	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_receivings'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['receivings_total']); ?></strong>
		</div>
	</li>
	
	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_discounts'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['discount_total']); ?></strong>
		</div>
	</li>

	
	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_taxes'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['taxes_total']); ?></strong>
		</div>
	</li>

	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_total'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['total']); ?></strong>
		</div>
	</li>

	<li class="popover-visits">
		<div class="left peity_bar_good">
			<h5><?php echo lang('reports_profit'); ?></h5>
		</div>
		<div class="right">
			<strong><?php echo to_currency($details_data['profit']); ?></strong>
		</div>
	</li>
</div>
<br />
</div>
</div>

<?php $this->load->view("partial/footer"); ?>