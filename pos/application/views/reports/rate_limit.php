<?php
$this->load->view("partial/header");
?>

<div id="content-header">
	<h1>	<i class="icon fa fa-bar-chart-o"></i>
		<?php echo lang('reports_reports'); ?> <?php echo lang('common_error');?>
	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>


<div class="row">
	<p><?php echo lang('reports_rate_limit_exceeded');?></p>
</div>
<?php
$this->load->view("partial/footer"); 
?>