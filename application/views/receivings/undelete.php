<?php $this->load->view("partial/header"); ?>

<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-pencil"></i>  <?php echo lang('receivings_register') ?> </span>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<?php 
				if ($success)
				{
				?>
					<h1 class="text-success"> <i class="fa fa-check"></i> <?php echo lang('receivings_undelete_successful'); ?></h1>
				<?php	
				}
				else
				{
				?>
					<h1><?php echo lang('receivings_undelete_unsuccessful'); ?></h1>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<br />	<br />	<br />
<?php $this->load->view("partial/footer"); ?>

