<?php $this->load->view("partial/header"); ?>
<?php
$is_integrated_credit_sale = is_sale_integrated_cc_processing();
if (isset($error_message))
{
	echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
	exit;
}
?>
<div id="receipt_wrapper">
	<div id="receipt_header">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<?php if($this->config->item('company_logo')) {?>
		<div id="company_logo"><?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?></div>
		<?php } ?>
		<div id="company_address"><?php echo nl2br($this->Location->get_info_for_key('address')); ?></div>
		<div id="company_phone"><?php echo $this->Location->get_info_for_key('phone'); ?></div>
		<?php if($this->config->item('website')) { ?>
			<div id="website"><?php echo $this->config->item('website'); ?></div>
		<?php } ?>
		<div id="sale_receipt"><?php echo $receipt_title; ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
		<div class="pull-right"><button class="btn btn-primary text-white hidden-print" id="new_sale_button_1" onclick="window.location='<?php echo site_url('sales'); ?>'" > <?php echo lang('sales_new_sale'); ?> </button></div>
	</div>
	<div id="receipt_general_info">
		<?php if(isset($customer))
		{
		?>
			<div id="customer"><?php echo lang('customers_customer').": ".$customer; ?></div>
			<?php if(!empty($customer_address_1)){ ?><div><?php echo lang('common_address'); ?> : <?php echo $customer_address_1. ' '.$customer_address_2; ?></div><?php } ?>
			<?php if (!empty($customer_city)) { echo $customer_city.' '.$customer_state.', '.$customer_zip;} ?>
			<?php if (!empty($customer_country)) { echo '<div>'.$customer_country.'</div>';} ?>			
			<?php if(!empty($customer_phone)){ ?><div><?php echo lang('common_phone_number'); ?> : <?php echo $customer_phone; ?></div><?php } ?>
			<?php if(!empty($customer_email)){ ?><div><?php echo lang('common_email'); ?> : <?php echo $customer_email; ?></div><?php } ?>
		<?php
		}
		?>
		<div id="sale_id"><?php echo lang('sales_id').": ".$sale_id; ?></div>
		<div id="employee"><?php echo lang('employees_employee').": ".$employee; ?></div>
		<?php 
		if($this->Location->get_info_for_key('enable_credit_card_processing'))
		{
			echo '<div id="mercahnt_id">'.lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id').'</div>';
		}
		?>
		
	</div>
	<table id="receipt_items">
	<tr>
	<th style="width:<?php echo $discount_exists ? "33%" : "49%"; ?>;text-align:center;"><?php echo lang('items_item'); ?></th>
	<th style="width:20%;text-align:center;"><?php echo lang('common_price'); ?></th>
	<th style="width:15%;text-align:center;"><?php echo lang('sales_quantity'); ?></th>
	<?php if($discount_exists) 
    {
	?>
	<th style="width:16%;text-align:center;"><?php echo lang('sales_discount'); ?></th>
	<?php
	}
	?>
	<th style="width:16%;text-align:right;"><?php echo lang('sales_total'); ?></th>
	</tr>
	<?php
	foreach(array_reverse($cart, true) as $line=>$item)
	{
	?>
		<tr>
		<td style="text-align:center;"><?php echo $item['name']; ?></td>
		<td style="text-align:center;"><?php echo to_currency($item['price']); ?></td>
		<td style='text-align:center;'><?php echo to_quantity($item['quantity']); ?></td>
		<?php if($discount_exists) 
		{
		?>
		<td style='text-align:center;'><?php echo $item['discount']; ?></td>
		<?php
		}
		?>
		<td style='text-align:right;'><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		</tr>

	    <tr>
	    <td colspan="2" align="center"><?php echo $item['description']; ?></td>
		<td colspan="2" ><?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?></td>
		
		<?php if($discount_exists) {?>
		<td colspan="1"><?php echo '&nbsp;'; ?></td>
		<?php } ?>
	    </tr>

	<?php
	}
	?>
	<tr>
	<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;border-top:2px solid #000000;'><?php echo lang('sales_sub_total'); ?></td>
	<td colspan="1" style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($subtotal); ?></td>
	</tr>

	<?php foreach($taxes as $name=>$value) { ?>
		<tr>
			<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo $name; ?>:</td>
			<td colspan="1" style='text-align:right;'><?php echo to_currency($value); ?></td>
		</tr>
	<?php }; ?>

	<tr>
	<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo lang('sales_total'); ?></td>
	<td colspan="1" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales') && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($total)) : to_currency($total); ?></td>
	</tr>

    <tr><td colspan="<?php echo $discount_exists ? '5' : '4'; ?>">&nbsp;</td></tr>

	<?php
		foreach($payments as $payment_id=>$payment)
	{ ?>
		<tr>
		<td colspan="<?php echo $discount_exists ? '3' : '2'; ?>" style="text-align:right;"><?php echo (isset($show_payment_times) && $show_payment_times) ?  date(get_date_format().' '.get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?></td>
		
		<?php if ($is_integrated_credit_sale || sale_has_partial_credit_card_payment()) { ?>
			<td colspan="1" style="text-align:right;"><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?>: <?php echo $payment['card_issuer']. ' '.$payment['truncated_card']; ?></td>
		<?php } else { ?>
			<td colspan="1" style="text-align:right;"><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> </td>											
		<?php } ?>
		<td colspan="1" style="text-align:right"><?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ?  to_currency(round_to_nearest_05($payment['payment_amount'])) : to_currency($payment['payment_amount']); ?>  </td>
		</tr>
	<?php
	}
	?>	
    <tr><td colspan="<?php echo $discount_exists ? '5' : '4'; ?>">&nbsp;</td></tr>

	<?php foreach($payments as $payment) {?>
		<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
	<tr>
		<td colspan="<?php echo $discount_exists ? '3' : '2'; ?>" style="text-align:right;"><?php echo lang('sales_giftcard_balance'); ?></td>
		<td colspan="1" style="text-align:right;"><?php echo $payment['payment_type'];?> </td>
		<td colspan="1" style="text-align:right"><?php echo to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type'])))); ?></td>
	</tr>
		<?php }?>
	<?php }?>
	
	<?php if ($amount_change >= 0) {?>
	<tr>
		<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo lang('sales_change_due'); ?></td>
		<td colspan="1" style='text-align:right'>
		<?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change)) : to_currency($amount_change); ?> </td>
	</tr>
	<?php
	}
	else
	{
	?>
	<tr>
		<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo lang('sales_amount_due'); ?></td>
		<td colspan="1" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change * -1)) : to_currency($amount_change * -1); ?></td>
	</tr>	
	<?php
	} 
	?>
	<?php if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE) {?>
	<tr>
		<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo lang('sales_customer_account_balance'); ?></td>
		<td colspan="1" style='text-align:right'>
		<?php echo to_currency($customer_balance_for_sale); ?> </td>
	</tr>
	<?php
	}
	?>
	
	<?php
	if ($ref_no)
	{
	?>
	<tr>
		<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo lang('sales_ref_no'); ?></td>
		<td colspan="1" style='text-align:right'><?php echo $ref_no; ?></td>
	</tr>	
	<?php
	}
	if (isset($auth_code) && $auth_code)
	{
	?>
	<tr>
		<td colspan="<?php echo $discount_exists ? '4' : '3'; ?>" style='text-align:right;'><?php echo lang('sales_auth_code'); ?></td>
		<td colspan="1" style='text-align:right'><?php echo $auth_code; ?></td>
	</tr>	
	<?php
	}
	?>
	
	<tr>
		<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>" align="right">
		<?php if($show_comment_on_receipt==1)
			{
				echo $comment ;
			}
		?>
		</td>
	</tr>
	</table>

	<div id="sale_return_policy">
	<?php echo nl2br($this->config->item('return_policy')); ?>
   <br />   

	</div>
	<div id='barcode'>
	<?php echo "<img src='".site_url('barcode')."?barcode=$sale_id&text=$sale_id' />"; ?>
	</div>
	<?php if(!$this->config->item('hide_signature')) { ?>
	
	<div id="signature">
	
	<?php foreach($payments as $payment) {?>
		<?php if (strpos($payment['payment_type'], lang('sales_credit'))!== FALSE) {?>
			<?php echo lang('sales_signature'); ?> --------------------------------- <br />	
			<?php 
			echo lang('sales_card_statement');
			break;
			?>
	
		<?php }?>
	<?php }?>
	
	</div>
	<?php } ?>
	
	<?php 
	 if (!$store_account_payment && $this->Employee->has_module_action_permission('sales', 'edit_sale', $this->Employee->get_logged_in_employee_info()->person_id)){

		$pieces = explode(' ',$sale_id);

	echo form_open("sales/change_sale/".$pieces[1],array('id'=>'sales_change_form')); ?>
	<button class="btn btn-primary text-white hidden-print" id="edit_sale" onclick="submit()" > <?php echo lang('sales_edit'); ?> </button>

	<?php }	?>
	</form>
	
<button class="btn btn-primary text-white hidden-print" id="print_button" onclick="print_receipt()" > <?php echo lang('sales_print'); ?> </button>
<br />
<button class="btn btn-primary text-white hidden-print" id="new_sale_button_2" onclick="window.location='<?php echo site_url('sales'); ?>'" > <?php echo lang('sales_new_sale'); ?> </button>
	
</div>
<?php $this->load->view("partial/footer"); ?>

<?php if ($this->config->item('print_after_sale'))
{
?>
<script type="text/javascript">
$(window).bind("load", function() {
	window.print();
});
</script>
<?php }  ?>

<script type="text/javascript">
function print_receipt()
 {
 	window.print();
 }
</script>

<?php if($is_integrated_credit_sale && $is_sale) { ?>
<script type="text/javascript">
gritter(<?php echo json_encode(lang('common_success')); ?>, <?php echo json_encode(lang('sales_credit_card_processing_success'))?>, 'gritter-item-success',false,false);
</script>
<?php } ?>

<!-- This is used for mobile apps to print receipt-->
<script type="text/print" id="print_output"><?php echo $this->config->item('company'); ?>

<?php echo $this->Location->get_info_for_key('address'); ?>

<?php echo $this->Location->get_info_for_key('phone'); ?>

<?php if($this->config->item('website')) { ?>
	<?php echo $this->config->item('website'); ?>
<?php } ?>

<?php echo $receipt_title; ?>

<?php echo $transaction_time; ?>

<?php if(isset($customer))
{
?>
<?php echo lang('customers_customer').": ".$customer; ?>

<?php if(!empty($customer_address_1)){ ?><?php echo lang('common_address'); ?>: <?php echo $customer_address_1. ' '.$customer_address_2; ?>
	
<?php } ?>
<?php if (!empty($customer_city)) { echo $customer_city.' '.$customer_state.', '.$customer_zip; ?>

<?php } ?>
<?php if (!empty($customer_country)) { echo $customer_country; ?>
	
<?php } ?>
<?php if(!empty($customer_phone)){ ?><?php echo lang('common_phone_number'); ?> : <?php echo $customer_phone; ?>
	
<?php } ?>
<?php if(!empty($customer_email)){ ?><?php echo lang('common_email'); ?> : <?php echo $customer_email; ?><?php } ?>

<?php
}
?>
<?php echo lang('sales_id').": ".$sale_id; ?>

<?php echo lang('employees_employee').": ".$employee; ?>

<?php 
if($this->Location->get_info_for_key('enable_credit_card_processing'))
{
	echo lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id');
}
?>

<?php echo lang('items_item'); ?>            <?php echo lang('common_price'); ?> <?php echo lang('sales_quantity'); ?><?php if($discount_exists){echo ' '.lang('sales_discount');}?> <?php echo lang('sales_total'); ?>

---------------------------------------
<?php
foreach(array_reverse($cart, true) as $line=>$item)
{
?>
<?php echo character_limiter($item['name'], 14,'...'); ?><?php echo strlen($item['name']) < 14 ? str_repeat(' ', 14 - strlen($item['name'])) : ''; ?> <?php echo str_replace('&#8209;', '-', to_currency($item['price'])); ?> <?php echo to_quantity($item['quantity']); ?><?php if($discount_exists){echo ' '.$item['discount'];}?> <?php echo str_replace('&#8209;', '-', to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)); ?>

  <?php echo $item['description']; ?>  <?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?>
	

<?php
}
?>

<?php echo lang('sales_sub_total'); ?>: <?php echo str_replace('&#8209;', '-', to_currency($subtotal)); ?>


<?php foreach($taxes as $name=>$value) { ?>
<?php echo $name; ?>: <?php echo str_replace('&#8209;', '-', to_currency($value)); ?>

<?php }; ?>

<?php echo lang('sales_total'); ?>: <?php echo $this->config->item('round_cash_on_sales') && $is_sale_cash_payment ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($total))) : str_replace('&#8209;', '-', to_currency($total)); ?>

<?php
	foreach($payments as $payment_id=>$payment)
{ ?>

<?php echo (isset($show_payment_times) && $show_payment_times) ?  date(get_date_format().' '.get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?>  <?php if ($is_integrated_credit_sale || sale_has_partial_credit_card_payment()) { ?><?php $splitpayment=explode(':',$payment['payment_type']);echo $splitpayment[0]; ?>: <?php echo $payment['card_issuer']. ' '.$payment['truncated_card']; ?> <?php } else { ?><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> <?php } ?><?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($payment['payment_amount']))) : str_replace('&#8209;', '-', to_currency($payment['payment_amount'])); ?>
<?php
}
?>	

<?php foreach($payments as $payment) {?>
<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?><?php echo lang('sales_giftcard_balance'); ?>  <?php echo $payment['payment_type'];?>: <?php echo str_replace('&#8209;', '-', to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type']))))); ?>
	<?php }?>
<?php }?>

<?php if ($amount_change >= 0) {?>
<?php echo lang('sales_change_due'); ?>: <?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($amount_change))) : str_replace('&#8209;', '-', to_currency($amount_change)); ?>
<?php
}
else
{
?>
<?php echo lang('sales_amount_due'); ?>: <?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($amount_change * -1))) : str_replace('&#8209;', '-', to_currency($amount_change * -1)); ?>
<?php
} 
?>
<?php if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE) {?>
	
<?php echo lang('sales_customer_account_balance'); ?>: <?php echo to_currency($customer_balance_for_sale); ?>
<?php
}
?>
<?php
if ($ref_no)
{
?>

<?php echo lang('sales_ref_no'); ?>: <?php echo $ref_no; ?>
<?php
}
if (isset($auth_code) && $auth_code)
{
?>

<?php echo lang('sales_auth_code'); ?>: <?php echo $auth_code; ?>
<?php
}
?>
<?php if($show_comment_on_receipt==1){echo $comment;} ?>

<?php $this->config->item('return_policy'); ?>

<?php if(!$this->config->item('hide_signature')) { ?>
<?php foreach($payments as $payment) {?>
	<?php if (strpos($payment['payment_type'], lang('sales_credit'))!== FALSE) {?>
		
	<?php echo lang('sales_signature'); ?>: 
---------------------------------------
<?php 
echo lang('sales_card_statement');
break;
?><?php }?><?php }?><?php } ?></script>