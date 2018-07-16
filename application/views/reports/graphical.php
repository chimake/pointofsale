<?php
$this->load->view("partial/header");
?>
<div id="content-header">
	<h1 > <i class="fa fa-bar-chart"> </i><?php echo lang('reports_reports'); ?> - <?php echo $title ?></h1>
</div>
<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div style="text-align: center;">
<script src="<?php echo base_url();?>js/jquery.flot.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="<?php echo base_url();?>js/jquery.flot.pie.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="<?php echo base_url();?>js/jquery.flot.time.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>

</div>

<div class="row">
		<div id="report_summary"  class="repors-summarys">
		<ul class="stat-boxes">
<?php foreach($summary_data as $name=>$value) { ?>
	<li class="popover-visits">
	<?php echo "<div class='left peity_bar_good'><h5>".lang('reports_'.$name). '</h5></div>' ?>
	<?php echo "<div class='right'><strong>".to_currency($value). '</strong></div>' ?>
	 
	</li>
	
<?php }?>
	</ul>
</div>

</div>

<div id="chart_wrapper">
	<div id="chart"></div>
</div>
<script type="text/javascript">
$.getScript('<?php echo $graph_file; ?>');
</script>

<?php
$this->load->view("partial/footer"); 
?>