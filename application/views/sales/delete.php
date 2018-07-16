<?php $this->load->view("partial/header"); ?>
	<div id="content-header" class="hidden-print">
		<h1 ><i class="fa fa-pencil"></i> <?php echo lang('sales_register') ?></h1>
	</div>

	<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	</div>
	<div class="clear"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo lang("sales_delete_successful"); ?></h5>
				</div>
				<div class="widget-content nopadding">
					<br />
					<br />
					
<?php 
if ($success)
{
?>
	<h1 class="text-warning text-center"><?php echo lang('sales_delete_successful'); ?></h1>
<?php	
}
else
{
?>
	<h1 class="text-error"><?php echo lang('sales_delete_unsuccessful'); ?></h1>
<?php
}
?>
<br />
<br />
	</div>
</div>

<?php $this->load->view("partial/footer"); ?>