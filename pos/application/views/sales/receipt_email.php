<?php
$is_integrated_credit_sale = is_sale_integrated_cc_processing();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $receipt_title; ?></title>
</head>
<body>
<div id="receipt_wrapper">
	<div id="receipt_header">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<div id="company_address"><?php echo nl2br($this->Location->get_info_for_key('address')); ?></div>
		<div id="company_phone"><?php echo $this->Location->get_info_for_key('phone'); ?></div>
		<div id="sale_receipt"><?php echo $receipt_title; ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
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
			echo '<div id="merchant_id">'.lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id').'</div>';
		}
		?>
		
	</div>
	<?php
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$discount_exists=false;
		if($item['discount']>0)
		{
			$discount_exists=true;
		}
	}
	?>
	<table id="receipt_items">
	<tr>
	<th style="width:33%;text-align:center;"><?php echo lang('items_item'); ?></th>
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
		<td style="text-align:center;"><span class='long_name'><?php echo $item['name']; ?></span></td>
		<td style="text-align:center;"><?php echo to_currency($item['price']); ?></td>
		<td style='text-align:center;'><?php echo $item['quantity']; ?></td>
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
		<td colspan="2"><?php echo '&nbsp;'; ?></td>
	    </tr>

	<?php
	}
	?>
	<tr>
	<td colspan="4" style='text-align:right;border-top:2px solid #000000;'><?php echo lang('sales_sub_total'); ?></td>
	<td colspan="2" style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($subtotal); ?></td>
	</tr>

	<?php foreach($taxes as $name=>$value) { ?>
		<tr>
			<td colspan="4" style='text-align:right;'><?php echo $name; ?>:</td>
			<td colspan="2" style='text-align:right;'><?php echo to_currency($value); ?></td>
		</tr>
	<?php }; ?>

	<tr>
	<td colspan="4" style='text-align:right;'><?php echo lang('sales_total'); ?></td>
	<td colspan="2" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales') && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($total)) : to_currency($total); ?></td>
	</tr>

    <tr><td colspan="6">&nbsp;</td></tr>

    <?php
		foreach($payments as $payment_id=>$payment)
		{ 
		?>
			<tr>
			<td colspan="2" style="text-align:right;"><?php echo (isset($show_payment_times) && $show_payment_times) ?  date(get_date_format().' '.get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?></td>
			
			<?php if ($is_integrated_credit_sale || sale_has_partial_credit_card_payment()) { ?>
				<td style="text-align:right;"><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> </td>											 
				<td style="text-align:right;"><?php echo $payment['card_issuer']. ' '.$payment['truncated_card']; ?></td>											 
				
			<?php } else { ?>
				<td colspan="2" style="text-align:right;"><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> </td>											 
			<?php
			}
			?>
			<td colspan="2" style="text-align:right"><b><?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ?  to_currency(round_to_nearest_05($payment['payment_amount'])) : to_currency($payment['payment_amount']); ?> </b> </td>
		    </tr>
		<?php
		}
		?>

    <tr><td colspan="6">&nbsp;</td></tr>
	<?php foreach($payments as $payment) {?>
		<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
	<tr>
		<td colspan="2" style="text-align:right;"><?php echo lang('sales_giftcard_balance'); ?></td>
		<td colspan="2" style="text-align:right;"><?php echo $payment['payment_type'];?> </td>
		<td colspan="2" style="text-align:right"><?php echo to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type'])))); ?></td>
	</tr>
		<?php }?>
	<?php }?>
	
	<?php if ($amount_change >= 0) {?>
	<tr>
		<td colspan="4" style='text-align:right;'><?php echo lang('sales_change_due'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change)) : to_currency($amount_change); ?></td>
	</tr>
	<?php
	}
	else
	{
	?>
	<tr>
		<td colspan="4" style='text-align:right;'><?php echo lang('sales_amount_due'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo to_currency($amount_change * -1); ?></td>
	</tr>	
	<?php
	} 
	?>
	
	<?php if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE) {?>
	<tr>
		<td colspan="4" style='text-align:right;'><?php echo lang('sales_customer_account_balance'); ?></td>
		<td colspan="2" style='text-align:right'>
		<?php echo to_currency($customer_balance_for_sale); ?> </td>
	</tr>
	<?php
	}
	?>
	
	<?php
	if (isset($ref_no) && $ref_no)
	{
	?>
	<tr>
		<td colspan="4" style='text-align:right;'><?php echo lang('sales_ref_no'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo $ref_no; ?></td>
	</tr>	
	<?php
	}
	?>
	
	<?php
	if (isset($auth_code) && $auth_code)
	{
	?>
	<tr>
		<td colspan="4" style='text-align:right;'><?php echo lang('sales_auth_code'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo $auth_code; ?></td>
	</tr>	
	<?php
	}
	?>
	
	</table>
	<div style="margin: 20px auto;text-align: left;">
	    <?php if(isset($show_comment_on_receipt) && $show_comment_on_receipt == 1){?>		
			<?php echo lang('common_comments').": ". $comment;?>
	    <?php } ?>
	</div>

	<div id="sale_return_policy">
	<?php echo nl2br($this->config->item('return_policy')); ?>
	</div>
</div>
</body>
</html>