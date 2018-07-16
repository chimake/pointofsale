<?php $this->load->view("partial/header");
	$controller_name="items";
 ?>
<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-bar-chart"> </i><?php echo lang("items_inventory_tracking"); ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="container-fluid">
	<div class="row">
		<div class="row">
			<div class="span6 offset3">
				<div class="widget-box">
					<div class="widget-title"><span class="icon"><i class="fa fa-file"></i></span><h5><?php echo lang("items_basic_information"); ?></h5></div>
						<div class="widget-content nopadding">
							<table class="table table-bordered table-hover">
								<tr><td><b><?php echo lang('items_item_number'); ?></b></td><td class="text-success"><?php echo $item_info->item_number; ?></td></tr>
								<tr class="text-success"><td><b><h4><?php echo lang('items_name'); ?></h4></b></td><td ><h4><?php echo $item_info->name; ?></h4></td></tr>
								<tr><td><b><?php echo lang('items_category'); ?></b></td><td><?php echo $item_info->category; ?></td></tr>
								<tr><td><b><?php echo lang('items_current_quantity'); ?></b></td><td><?php echo to_quantity($item_location_info->quantity) ?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="widget-box">
					<div class="widget-title">
						<h5>Search <?php echo lang("items_inventory_tracking"); ?></h5>
					</div>
					<div class="widget-content nopadding">
						<table class="table table-bordered table-striped table-hover data-table">
							<thead><tr align="center" style="font-weight:bold"><td width="15%"><?php echo lang("items_inventory_tracking"); ?></td><td width="25%"><?php echo lang("employees_employee"); ?></td><td width="15%"><?php echo lang("items_in_out_qty"); ?></td><td width="45%"><?php echo lang("items_remarks"); ?></td></tr></thead>
							<tbody>
								<?php foreach($this->Inventory->get_inventory_data_for_item($item_info->item_id)->result_array() as $row) { ?>
									<tr  align="center">
										<td><?php echo $row['trans_date'];?></td>
										<td>
											<?php
												$person_id = $row['trans_user'];
												$employee = $this->Employee->get_info($person_id);
												echo $employee->first_name." ".$employee->last_name;
											?>
										</td>
										<td align="right"><?php echo to_quantity($row['trans_inventory']);?></td>
										<td><?php echo $row['trans_comment'];?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('partial/footer'); ?>
					
				