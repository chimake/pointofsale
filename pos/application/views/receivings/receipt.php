<?php $this->load->view("partial/header"); ?>
<?php
if (isset($error_message))
{
	echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
	exit;
}
?>
<div id="receipt_wrapper">
	
	<div id="receipt_header">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<div id="company_address"><?php echo nl2br($this->Location->get_info_for_key('address')); ?></div>
		<?php if($this->config->item('company_logo')) {?>
		<div id="company_logo"><?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?></div>
		<?php } ?>
		<div id="company_phone"><?php echo $this->Location->get_info_for_key('phone'); ?></div>
		<div id="sale_receipt"><?php echo $receipt_title; ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
		<div class="pull-right"><button class="btn btn-primary text-white hidden-print" id="new_receiving_button_1" onclick="window.location='<?php echo site_url('receivings'); ?>'" > <?php echo lang('receivings_new_receiving'); ?> </button></div>
	</div>
	<div id="receipt_general_info">
		<?php if(isset($supplier))
		{
		?>
			<div id="customer"><?php echo lang('suppliers_supplier').": ".$supplier; ?></div>
		<?php
		}
		?>
		<div id="sale_id"><?php echo lang('receivings_id').": ".$receiving_id; ?></div>
		<div id="employee"><?php echo lang('employees_employee').": ".$employee; ?></div>
	</div>

	<table id="receipt_items">
	<tr>
	<th style="width:50%;text-align:center;"><?php echo lang('items_item'); ?></th>
	<th style="width:17%;text-align:center;"><?php echo lang('common_price'); ?></th>
	<th style="width:16%;text-align:center;"><?php echo lang('sales_quantity'); ?></th>
	<th style="width:16%;text-align:center;"><?php echo lang('sales_discount'); ?></th>
	<th style="width:17%;text-align:right;"><?php echo lang('sales_total'); ?></th>
	</tr>
	<?php
	foreach(array_reverse($cart, true) as $line=>$item)
	{
	?>
		<tr>
		<td style="text-align:center;"><?php echo character_limiter(H($item['name']),25); ?></td>
		<td style="text-align:center;"><?php echo to_currency($item['price']); ?></td>
		<td style='text-align:center;'><?php echo $item['quantity']; ?></td>
		<td style='text-align:center;'><?php echo $item['discount']; ?></td>
		<td style='text-align:right;'><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		</tr>
	    <tr>

	    <td colspan="2" align="center"><?php echo H($item['description']); ?></td>
		<td colspan="2" ><?php echo H($item['serialnumber']); ?></td>
		<td colspan="1"><?php echo '---'; ?></td>
	    </tr>
	<?php
	}
	?>	
	<tr>
	<td colspan="3" style='text-align:right;'><?php echo lang('sales_total'); ?></td>
	<td colspan="2" style='text-align:right'><?php echo to_currency($total); ?></td>
	</tr>

	<tr>
	<td colspan="3" style='text-align:right;'><?php echo lang('sales_payment'); ?></td>
	<td colspan="2" style='text-align:right'><?php echo $payment_type; ?></td>
	</tr>

	<?php if(isset($amount_change))
	{
	?>
		<tr>
		<td colspan="3" style='text-align:right;'><?php echo lang('sales_amount_tendered'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo to_currency($amount_tendered); ?></td>
		</tr>

		<tr>
		<td colspan="3" style='text-align:right;'><?php echo lang('sales_change_due'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo $amount_change; ?></td>
		</tr>
	<?php
	}
	?>
	</table>

	<div id="sale_return_policy">
	<?php echo nl2br($this->config->item('return_policy')); ?>
	</div>
	<div id='barcode'>
	<?php echo "<img src='".site_url('barcode')."?barcode=$receiving_id&text=$receiving_id' />"; ?>
	</div>
	<button class="btn btn-primary text-white hidden-print" id="print_button" onClick="window.print()" > <?php echo lang('sales_print'); ?> </button>
	<br />
	<button class="btn btn-primary text-white hidden-print" id="new_receiving_button_2" onclick="window.location='<?php echo site_url('receivings'); ?>'" > <?php echo lang('receivings_new_receiving'); ?> </button>
	
</div>
<?php $this->load->view("partial/footer"); ?>

<?php if ($this->config->item('print_after_sale'))
{
?>
<script type="text/javascript">
$(window).load(function()
{
	window.print();
});
</script>
<?php
}
?>