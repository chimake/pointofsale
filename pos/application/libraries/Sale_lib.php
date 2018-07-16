<?php
class Sale_lib
{
	var $CI;
	
	//This is used when we need to change the sale state and restore it before changing it (The case of showing a receipt in the middle of a sale)
	var $sale_state;
  	function __construct()
	{
		$this->CI =& get_instance();
		$this->sale_state = array();
	}

	function get_cart()
	{
		if($this->CI->session->userdata('cart') === false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cart');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart',$cart_data);
	}

	//Alain Multiple Payments
	function get_payments()
	{
		if($this->CI->session->userdata('payments') === false)
			$this->set_payments(array());

		return $this->CI->session->userdata('payments');
	}

	//Alain Multiple Payments
	function set_payments($payments_data)
	{
		$this->CI->session->set_userdata('payments',$payments_data);
	}
	
	function change_credit_card_payments_to_partial()
	{
		$payments=$this->get_payments();
		
		foreach($payments as $payment_id=>$payment)
		{
			//If we have a credit payment, change it to partial credit card so we can process again
			if ($payment['payment_type'] == lang('sales_credit'))
			{
				$payments[$payment_id] =  array(
					'payment_type'=>lang('sales_partial_credit'),
					'payment_amount'=>$payment['payment_amount'],
					'payment_date' => $payment['payment_date'] !== FALSE ? $payment['payment_date'] : date('Y-m-d H:i:s'),
					'truncated_card' => $payment['truncated_card'],
					'card_issuer' => $payment['card_issuer'],
				);
			}
		}
		
		$this->set_payments($payments);
	}
	
	function get_change_sale_date() 
	{
		return $this->CI->session->userdata('change_sale_date') ? $this->CI->session->userdata('change_sale_date') : '';
	}
	function clear_change_sale_date() 	
	{
		$this->CI->session->unset_userdata('change_sale_date');
		
	}
	function clear_change_sale_date_enable() 	
	{
		$this->CI->session->unset_userdata('change_sale_date_enable');
	}
	function set_change_sale_date_enable($change_sale_date_enable)
	{
		$this->CI->session->set_userdata('change_sale_date_enable',$change_sale_date_enable);
	}
	
	function get_change_sale_date_enable() 
	{
		return $this->CI->session->userdata('change_sale_date_enable') ? $this->CI->session->userdata('change_sale_date_enable') : '';
	}
	
	function set_change_sale_date($change_sale_date)
	{
		$this->CI->session->set_userdata('change_sale_date',$change_sale_date);
	}
	
	function get_comment() 
	{
		return $this->CI->session->userdata('comment') ? $this->CI->session->userdata('comment') : '';
	}

	function get_comment_on_receipt() 
	{
		return $this->CI->session->userdata('show_comment_on_receipt') ? $this->CI->session->userdata('show_comment_on_receipt') : '';
	}

	function set_comment($comment) 
	{
		$this->CI->session->set_userdata('comment', $comment);
	}
		
	function get_selected_tier_id() 
	{
		return $this->CI->session->userdata('selected_tier_id') ? $this->CI->session->userdata('selected_tier_id') : FALSE;
	}

	function get_previous_tier_id() 
	{
		return $this->CI->session->userdata('previous_tier_id') ? $this->CI->session->userdata('previous_tier_id') : FALSE;
	}

	function set_selected_tier_id($tier_id) 
	{
		$this->CI->session->set_userdata('previous_tier_id', $this->get_selected_tier_id());
		$this->CI->session->set_userdata('selected_tier_id', $tier_id);
		$this->change_price();
	}
	
	function clear_selected_tier_id()	
	{
		$this->CI->session->unset_userdata('previous_tier_id');
		$this->CI->session->unset_userdata('selected_tier_id');
	}
	
	
	function set_comment_on_receipt($comment_on_receipt) 
	{
		$this->CI->session->set_userdata('show_comment_on_receipt', $comment_on_receipt);
	}

	function clear_comment() 	
	{
		$this->CI->session->unset_userdata('comment');
		
	}
	
	function clear_show_comment_on_receipt() 	
	{
		$this->CI->session->unset_userdata('show_comment_on_receipt');
		
	}
	
	function get_email_receipt() 
	{
		return $this->CI->session->userdata('email_receipt');
	}

	function set_email_receipt($email_receipt) 
	{
		$this->CI->session->set_userdata('email_receipt', $email_receipt);
	}

	function clear_email_receipt() 	
	{
		$this->CI->session->unset_userdata('email_receipt');
	}
	
	function get_save_credit_card_info() 
	{
		return $this->CI->session->userdata('save_credit_card_info');
	}

	function set_save_credit_card_info($save_credit_card_info) 
	{
		$this->CI->session->set_userdata('save_credit_card_info', $save_credit_card_info);
	}

	function clear_save_credit_card_info() 	
	{
		$this->CI->session->unset_userdata('save_credit_card_info');
	}
	
	function get_use_saved_cc_info() 
	{
		return $this->CI->session->userdata('use_saved_cc_info');
	}

	function set_use_saved_cc_info($use_saved_cc_info) 
	{
		$this->CI->session->set_userdata('use_saved_cc_info', $use_saved_cc_info);
	}

	function clear_use_saved_cc_info() 	
	{
		$this->CI->session->unset_userdata('use_saved_cc_info');
	}
	
	function get_partial_transactions()
	{
		return $this->CI->session->userdata('partial_transactions');
	}
	
	function set_partial_transactions($partial_transactions)
	{
		$this->CI->session->set_userdata('partial_transactions', $partial_transactions);
	}
	
	function add_partial_transaction($partial_transaction)
	{
		$partial_transactions = $this->CI->session->userdata('partial_transactions');
		$partial_transactions[] = $partial_transaction;
		$this->CI->session->set_userdata('partial_transactions', $partial_transactions);
	}
	
	function delete_partial_transactions()
	{
		$this->CI->session->unset_userdata('partial_transactions');
	}
	

	function add_payment($payment_type,$payment_amount,$payment_date = false, $truncated_card = '', $card_issuer = '')
	{
			$payments=$this->get_payments();
			$payment = array(
				'payment_type'=>$payment_type,
				'payment_amount'=>$payment_amount,
				'payment_date' => $payment_date !== FALSE ? $payment_date : date('Y-m-d H:i:s'),
				'truncated_card' => $truncated_card,
				'card_issuer' => $card_issuer,
			);
			
			$payments[]=$payment;
			$this->set_payments($payments);
			return true;
	}
	
	function edit_payment($payment_id, $payment_type, $payment_amount,$payment_date = false, $truncated_card = '', $card_issuer = '')
	{
		$payments=$this->get_payments();
		$payment = array(
			'payment_type'=>$payment_type,
			'payment_amount'=>$payment_amount,
			'payment_date' => $payment_date !== FALSE ? $payment_date : date('Y-m-d H:i:s'),
			'truncated_card' => $truncated_card,
			'card_issuer' => $card_issuer,
		);
		
		$payments[$payment_id]=$payment;
		$this->set_payments($payments);
		return true;
	}
	
	public function get_payment_ids($payment_type)
	{
		$payment_ids = array();
		
		$payments=$this->get_payments();
		
		for($k=0;$k<count($payments);$k++)
		{
			if ($payments[$k]['payment_type'] == $payment_type)
			{
				$payment_ids[] = $k;
			}
		}
		
		return $payment_ids;
	}
	
	public function get_payment_amount($payment_type)
	{
		$payment_amount = 0;
		if (($payment_ids = $this->get_payment_ids($payment_type)) !== FALSE)
		{
			$payments=$this->get_payments();
			
			foreach($payment_ids as $payment_id)
			{
				$payment_amount += $payments[$payment_id]['payment_amount'];
			}
		}
		
		return $payment_amount;
	}
	
	//Alain Multiple Payments
	function delete_payment($payment_ids)
	{
		$payments=$this->get_payments();
		if (is_array($payment_ids))
		{
			foreach($payment_ids as $payment_id)
			{
				unset($payments[$payment_id]);
			}
		}
		else
		{
			unset($payments[$payment_ids]);			
		}
		$this->set_payments(array_values($payments));
	}
	
	function get_price_for_item($item_id, $tier_id = FALSE)
	{
		if ($tier_id === FALSE)
		{
			$tier_id = $this->get_selected_tier_id();
		}
		
		$item_info = $this->CI->Item->get_info($item_id);
		$item_location_info = $this->CI->Item_location->get_info($item_id);
		
		$item_tier_row = $this->CI->Item->get_tier_price_row($tier_id, $item_id);
		$item_location_tier_row = $this->CI->Item_location->get_tier_price_row($tier_id, $item_id, $this->CI->Employee->get_logged_in_employee_current_location_id());
		
		if (!empty($item_location_tier_row) && $item_location_tier_row->unit_price)
		{
			return to_currency_no_money($item_location_tier_row->unit_price, 10);
		}
		elseif (!empty($item_location_tier_row) && $item_location_tier_row->percent_off)
		{
			$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
			return to_currency_no_money($item_unit_price *(1-($item_location_tier_row->percent_off/100)), 10);
		}
		elseif (!empty($item_tier_row) && $item_tier_row->unit_price)
		{
			return to_currency_no_money($item_tier_row->unit_price, 10);
		}
		elseif (!empty($item_tier_row) && $item_tier_row->percent_off)
		{
			$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
			return to_currency_no_money($item_unit_price *(1-($item_tier_row->percent_off/100)), 10);
		}
		else
		{
			$today =  strtotime(date('Y-m-d'));
			$is_item_location_promo = ($item_location_info->start_date !== NULL && $item_location_info->end_date !== NULL) && (strtotime($item_location_info->start_date) <= $today && strtotime($item_location_info->end_date) >= $today);
			$is_item_promo = ($item_info->start_date !== NULL && $item_info->end_date !== NULL) && (strtotime($item_info->start_date) <= $today && strtotime($item_info->end_date) >= $today);
			
			if ($is_item_location_promo)
			{
				return to_currency_no_money($item_location_info->promo_price, 10);
			}
			elseif ($is_item_promo)
			{
				return to_currency_no_money($item_info->promo_price, 10);
			}
			else
			{
				$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
				return to_currency_no_money($item_unit_price, 10);
			}
		}			
			
	}
	
	function get_price_for_item_kit($item_kit_id, $tier_id = FALSE)
	{
		if ($tier_id === FALSE)
		{
			$tier_id = $this->get_selected_tier_id();
		}
		
		$item_kit_info = $this->CI->Item_kit->get_info($item_kit_id);
		$item_kit_location_info = $this->CI->Item_kit_location->get_info($item_kit_id);
		
		$item_kit_tier_row = $this->CI->Item_kit->get_tier_price_row($tier_id, $item_kit_id);
		$item_kit_location_tier_row = $this->CI->Item_kit_location->get_tier_price_row($tier_id, $item_kit_id, $this->CI->Employee->get_logged_in_employee_current_location_id());
		
		if (!empty($item_kit_location_tier_row) && $item_kit_location_tier_row->unit_price)
		{
			return to_currency_no_money($item_kit_location_tier_row->unit_price, 10);
		}
		elseif (!empty($item_kit_location_tier_row) && $item_kit_location_tier_row->percent_off)
		{
			$item_kit_unit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
			return to_currency_no_money($item_kit_unit_price *(1-($item_kit_location_tier_row->percent_off/100)), 10);
		}
		elseif (!empty($item_kit_tier_row) && $item_kit_tier_row->unit_price)
		{
			return to_currency_no_money($item_kit_tier_row->unit_price, 10);
		}
		elseif (!empty($item_kit_tier_row) && $item_kit_tier_row->percent_off)
		{
			$item_kit_unit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
			return to_currency_no_money($item_kit_unit_price *(1-($item_kit_tier_row->percent_off/100)), 10);
		}
		else
		{
			$item_kit_unit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
			return to_currency_no_money($item_kit_unit_price, 10);
		}		
	}	
	
	function empty_payments()
	{
		$this->CI->session->unset_userdata('payments');
	}

	//Alain Multiple Payments
	function get_payments_totals_excluding_store_account()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
		    if($payments['payment_type'] != lang('sales_store_account'))
			{
		    	$subtotal+=$payments['payment_amount'];
			}	
		}
		return to_currency_no_money($subtotal);
	}

	function get_payments_totals()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
			$subtotal+=$payments['payment_amount'];
		}

		return to_currency_no_money($subtotal);
	}

	//Alain Multiple Payments
	function get_amount_due($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_totals();
		$sales_total=$this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_amount_due_round($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_totals();
		$sales_total= $this->CI->config->item('round_cash_on_sales') ?  round_to_nearest_05($this->get_total($sale_id)) : $this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_customer()
	{
		if(!$this->CI->session->userdata('customer'))
			$this->set_customer(-1);

		return $this->CI->session->userdata('customer');
	}

	function set_customer($customer_id)
	{
		$this->CI->session->set_userdata('customer',$customer_id);
		$this->change_price();
	}

	function get_mode()
	{
		if(!$this->CI->session->userdata('sale_mode'))
			$this->set_mode('sale');

		return $this->CI->session->userdata('sale_mode');
	}

	function set_mode($mode)
	{
		$this->CI->session->set_userdata('sale_mode',$mode);
	}
	
	/*
	* This function is called when a customer added or tier changed
	* It scans item and item kits to see if there price is at a default value
	* If a price is at a default value, it is changed to match the tier
	*/
	function change_price()
	{
		$items = $this->get_cart();
		foreach ($items as $item )
		{
			if (isset($item['item_id']))
			{
				$line=$item['line'];
				$price=$item['price'];
				$item_id=$item['item_id'];
				$item_info = $this->CI->Item->get_info($item_id);
				$item_location_info = $this->CI->Item_location->get_info($item_id);
				$previous_price = FALSE;
			
				if ($previous_tier_id = $this->get_previous_tier_id())
				{
					$previous_price = $this->get_price_for_item($item_id, $previous_tier_id);
				}
				$previous_price = to_currency_no_money($previous_price, 10);
				$price = to_currency_no_money($price, 10);
				
				if($price==$item_info->unit_price || $price == $item_location_info->unit_price || $price == $previous_price )
				{	
					$items[$line]['price']= $this->get_price_for_item($item_id);		
				}
			}
			elseif(isset($item['item_kit_id']))
			{
				$line=$item['line'];
				$price=$item['price'];
				$item_kit_id=$item['item_kit_id'];
				$item_kit_info = $this->CI->Item_kit->get_info($item_kit_id);
				$item_kit_location_info = $this->CI->Item_kit_location->get_info($item_kit_id);
				$previous_price = FALSE;
			
				if ($previous_tier_id = $this->get_previous_tier_id())
				{
					$previous_price = $this->get_price_for_item_kit($item_kit_id, $previous_tier_id);
				}
				
				$previous_price = to_currency_no_money($previous_price, 10);
				$price = to_currency_no_money($price, 10);
						
				if($price==$item_kit_info->unit_price || $price == $item_kit_location_info->unit_price || $price == $previous_price )
				{
					$items[$line]['price']= $this->get_price_for_item_kit($item_kit_id);		
				}
			}
		}
		$this->set_cart($items);
	}
	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null, $force_add = FALSE)
	{
		$store_account_item_id = $this->CI->Item->get_store_account_item_id();
		
		//Do NOT allow item to get added unless in store_account_payment mode
		if (!$force_add && $this->get_mode() !=='store_account_payment' && $store_account_item_id == $item_id)
		{
			return FALSE;
		}
		
		//make sure item exists
		if(!$this->CI->Item->exists(is_numeric($item_id) ? (int)$item_id : -1))	
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		else
		{
			$item_id = (int)$item_id;
		}
		
		$item_info = $this->CI->Item->get_info($item_id);
		
		//Alain Serialization and Description

		//Get all items in the cart so far...
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
            //Also, we have stored the key in the element itself so we can compare.

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}

			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
				
				if($item_info->description==$items[$updatekey]['description'] && $item_info->name==lang('sales_giftcard'))
				{
					return false;
				}
			}
		}

		$insertkey=$maxkey+1;

        $today =  strtotime(date('Y-m-d'));
        $price_to_use= $this->get_price_for_item($item_id);		

		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$item_info->name,
			'item_number'=>$item_info->item_number,
			'description'=>$description!=null ? $description: $item_info->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$item_info->allow_alt_description,
			'is_serialized'=>$item_info->is_serialized,
			'quantity'=>$quantity,
            'discount'=>$discount,
			'price'=>$price!=null ? $price:$price_to_use
			)
		);
		
		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($item_info->is_serialized ==0) )
		{
			$items[$updatekey]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}

		$this->set_cart($items);
		return true;

	}
	
	function add_item_kit($external_item_kit_id_or_item_number,$quantity=1,$discount=0,$price=null,$description=null)
	{
		if (strpos($external_item_kit_id_or_item_number, 'KIT') !== FALSE)
		{
			//KIT #
			$pieces = explode(' ',$external_item_kit_id_or_item_number);
			$item_kit_id = (int)$pieces[1];	
		}
		else
		{
			$item_kit_id = $this->CI->Item_kit->get_item_kit_id($external_item_kit_id_or_item_number);
		}
		
		
		//make sure item exists
		if(!$this->CI->Item_kit->exists($item_kit_id))	
		{
			return false;
		}

		$item_kit_info = $this->CI->Item_kit->get_info($item_kit_id);
		
		if ( $item_kit_info->unit_price == null)
		{
			foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
			{
				for($k=0;$k<$item_kit_item->quantity;$k++)
				{
					$this->add_item($item_kit_item->item_id, $quantity);
				}
			}
			
			return true;
		}
		else
		{
			$items = $this->get_cart();

	        //We need to loop through all items in the cart.
	        //If the item is already there, get it's key($updatekey).
	        //We also need to get the next key that we are going to use in case we need to add the
	        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

	        $maxkey=0;                       //Highest key so far
	        $itemalreadyinsale=FALSE;        //We did not find the item yet.
			$insertkey=0;                    //Key to use for new entry.
			$updatekey=0;                    //Key to use to update(quantity)

			foreach ($items as $item)
			{
	            //We primed the loop so maxkey is 0 the first time.
	            //Also, we have stored the key in the element itself so we can compare.

				if($maxkey <= $item['line'])
				{
					$maxkey = $item['line'];
				}

				if(isset($item['item_kit_id']) && $item['item_kit_id']==$item_kit_id)
				{
					$itemalreadyinsale=TRUE;
					$updatekey=$item['line'];
				}
			}

			$insertkey=$maxkey+1;
			
			$price_to_use=$this->get_price_for_item_kit($item_kit_id);

			//array/cart records are identified by $insertkey and item_id is just another field.
			$item = array(($insertkey)=>
			array(
				'item_kit_id'=>$item_kit_id,
				'line'=>$insertkey,
				'item_kit_number'=>$item_kit_info->item_kit_number,
				'name'=>$item_kit_info->name,
				'description'=>$description!=null ? $description: $item_kit_info->description,
				'quantity'=>$quantity,
	            'discount'=>$discount,
				'price'=>$price!=null ? $price: $price_to_use
				)
			);

			//Item already exists and is not serialized, add to quantity
			if($itemalreadyinsale)
			{
				$items[$updatekey]['quantity']+=$quantity;
			}
			else
			{
				//add to existing array
				$items+=$item;
			}

			$this->set_cart($items);
			return true;
		}
	}
	
	function discount_all($percent_discount)
	{
		$items = $this->get_cart();
				
		foreach(array_keys($items) as $key)
		{
			$items[$key]['discount'] = $percent_discount;
		}
		$this->set_cart($items);
		return true;
	}
	
	function out_of_stock($item_id)
	{
		//make sure item exists
		if(!$this->CI->Item->exists($item_id))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		
		$item_location_quantity = $this->CI->Item_location->get_location_quantity($item_id);
		$quanity_added = $this->get_quantity_already_added($item_id);
		
		//If $item_location_quantity is NULL we don't track quantity
		if ($item_location_quantity !== NULL && $item_location_quantity - $quanity_added < 0)
		{
			return true;
		}
		
		return false;
	}
	
	function out_of_stock_kit($kit_id)
	{
	    //Make sure Item kit exist
	    if(!$this->CI->Item_kit->exists($kit_id)) return FALSE;

	    //Get All Items for Kit
	    $kit_items = $this->CI->Item_kit_items->get_info($kit_id);

	    //Check each item
	    foreach ($kit_items as $item)
	    {
			$item_location_quantity = $this->CI->Item_location->get_location_quantity($item->item_id);
			$item_already_added = $this->get_quantity_already_added($item->item_id);

			if ($item_location_quantity - $item_already_added < 0)
			{
		    	return true;
			}	
	    }
	    return false;
	}

	function get_quantity_already_added($item_id)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}
		
		//Check Item Kist for this item
		$all_kits = $this->CI->Item_kit_items->get_kits_have_item($item_id);

		foreach($all_kits as $kits)
		{
		    $kit_quantity = $this->get_kit_quantity_already_added($kits['item_kit_id']);
		    if($kit_quantity > 0)
		    {
				$quanity_already_added += ($kit_quantity * $kits['quantity']);
		    }
		}
		return $quanity_already_added;
	}
	
	function get_kit_quantity_already_added($kit_id)
	{
	    $items = $this->get_cart();
	    $quanity_already_added = 0;
	    foreach ($items as $item)
	    {
		    if(isset($item['item_kit_id']) && $item['item_kit_id']==$kit_id)
		    {
				$quanity_already_added+=$item['quantity'];
		    }
	    }

	    return $quanity_already_added;
	}

	function get_item_id($line_to_get)
	{
		$items = $this->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return isset($item['item_id']) ? $item['item_id'] : -1;
			}
		}
		
		return -1;
	}

	function get_kit_id($line_to_get)
	{
	    $items = $this->get_cart();

	    foreach ($items as $line=>$item)
	    {
		if($line==$line_to_get)
		{
		    return isset($item['item_kit_id']) ? $item['item_kit_id'] : -1;
		}
	    }
	    return -1;
	}

	function is_kit_or_item($line_to_get)
	{
	    $items = $this->get_cart();
	    foreach ($items as $line=>$item)
	    {
		if($line==$line_to_get)
		{
		    if(isset($item['item_id']))
		    {
			return 'item';
		    }
		    elseif ($item['item_kit_id'])
		    {
			return 'kit';
		    }
		}
	    }
	    return -1;
	}

	function edit_item($line,$description = FALSE,$serialnumber = FALSE,$quantity = FALSE,$discount = FALSE,$price = FALSE)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			if ($description !== FALSE ) {
				$items[$line]['description'] = $description;
			}
			if ($serialnumber !== FALSE ) {
				$items[$line]['serialnumber'] = $serialnumber;
			}
			if ($quantity !== FALSE ) {
				$items[$line]['quantity'] = $quantity;
			}
			if ($discount !== FALSE ) {
				$items[$line]['discount'] = $discount;
			}
			if ($price !== FALSE ) {
				$items[$line]['price'] = $price;
			}
			 
			$this->set_cart($items);
		}

		return false;
	}

	function is_valid_receipt($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		if(count($pieces)==2 && strtolower($pieces[0]) == strtolower($this->CI->config->item('sale_prefix')))
		{
			return $this->CI->Sale->exists($pieces[1]);
		}

		return false;
	}
	
	function is_valid_item_kit($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $this->CI->Item_kit->exists($pieces[1]);
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id) !== FALSE;
		}
	}

	function get_valid_item_kit_id($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $pieces[1];
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id);
		}
	}

	function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		$sale_id = $pieces[1];

		$this->empty_cart();
		$this->delete_customer();

		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$this->add_item_kit('KIT '.$row->item_kit_id,-$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
	}
	
	function copy_entire_sale($sale_id, $is_receipt = false)
	{
		$this->empty_cart();
		$this->delete_customer();

		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$item_info = $this->CI->Item->get_info($row->item_id);
			$price_to_use = $row->item_unit_price;
			$sale_taxes = $this->get_taxes($sale_id);
			
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_info->tax_included && empty($sale_taxes) && !$is_receipt)
			{
				$price_to_use = get_price_for_item_including_taxes($row->item_id, $row->item_unit_price);
			}
			elseif($item_info->tax_included)
			{
				$price_to_use = get_price_for_item_including_taxes($row->item_id, $row->item_unit_price,$sale_id);				
			}
			
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description,$row->serialnumber, TRUE);
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$item_kit_info = $this->CI->Item_kit->get_info($row->item_kit_id);
			$price_to_use = $row->item_kit_unit_price;
			$sale_taxes = $this->get_taxes($sale_id);
						
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_kit_info->tax_included && empty($sale_taxes) && !$is_receipt)
			{
				$price_to_use = get_price_for_item_kit_including_taxes($row->item_kit_id, $row->item_kit_unit_price);
			}
			elseif ($item_kit_info->tax_included)
			{
				$price_to_use = get_price_for_item_kit_including_taxes($row->item_kit_id, $row->item_kit_unit_price,$sale_id);
			}
						
			$this->add_item_kit('KIT '.$row->item_kit_id,$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description);
		}
		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount, $row->payment_date, $row->truncated_card, $row->card_issuer);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
		$this->set_comment($this->CI->Sale->get_comment($sale_id));
		$this->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt($sale_id));
	}

	function get_suspended_sale_id()
	{
		return $this->CI->session->userdata('suspended_sale_id');
	}
	
	function set_suspended_sale_id($suspended_sale_id)
	{
		$this->CI->session->set_userdata('suspended_sale_id',$suspended_sale_id);
	}
	
	function delete_suspended_sale_id()
	{
		$this->CI->session->unset_userdata('suspended_sale_id');
	}
	
	function get_change_sale_id()
	{
		return $this->CI->session->userdata('change_sale_id');
	}
	
	function set_change_sale_id($change_sale_id)
	{
		$this->CI->session->set_userdata('change_sale_id',$change_sale_id);
	}
	
	function delete_change_sale_id()
	{
		$this->CI->session->unset_userdata('change_sale_id');
	}
	function delete_item($line)
	{
		$items=$this->get_cart();
		$item_id=$this->get_item_id($line);
		if($this->CI->Giftcard->get_giftcard_id($this->CI->Item->get_info($item_id)->description))
		{
			$this->CI->Giftcard->delete_completely($this->CI->Item->get_info($item_id)->description);
		}
		unset($items[$line]);
		$this->set_cart($items);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	function delete_customer()
	{
		$this->CI->session->unset_userdata('customer');
		$this->change_price();
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('sale_mode');
	}

	function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->clear_comment();
		$this->clear_show_comment_on_receipt();
		$this->clear_change_sale_date();
		$this->clear_change_sale_date_enable();
		$this->clear_email_receipt();
		$this->empty_payments();
		$this->delete_customer();
		$this->delete_suspended_sale_id();
		$this->delete_change_sale_id();
		$this->delete_partial_transactions();
		$this->clear_save_credit_card_info();
		$this->clear_use_saved_cc_info();
		$this->clear_selected_tier_id();
	}
	
	function save_current_sale_state()
	{
		$this->sale_state = array(
			'mode' => $this->get_mode(),
			'cart' => $this->get_cart(),
			'comment' => $this->get_comment(),
			'show_comment_on_receipt' => $this->get_comment_on_receipt(),
			'change_sale_date' => $this->get_change_sale_date(),
			'change_sale_date_enable' => $this->get_change_sale_date_enable(),
			'email_receipt' => $this->get_email_receipt(),
			'payments' => $this->get_payments(),
			'customer' => $this->get_customer(),
			'suspended_sale_id' => $this->get_suspended_sale_id(),
			'change_sale_id' => $this->get_change_sale_id(),
			'partial_transactions' => $this->get_partial_transactions(),
			'save_credit_card_info' => $this->get_save_credit_card_info(),
			'use_saved_cc_info' => $this->get_use_saved_cc_info(),
			'selected_tier_id' => $this->get_selected_tier_id(),
			
		);
	}
	
	function restore_current_sale_state()
	{
		if (isset($this->sale_state))
		{
			$this->set_mode($this->sale_state['mode']);
			$this->set_cart($this->sale_state['cart']);
			$this->set_comment($this->sale_state['comment']);
			$this->set_comment_on_receipt($this->sale_state['show_comment_on_receipt']);
			$this->set_change_sale_date($this->sale_state['change_sale_date']);
			$this->set_change_sale_date_enable($this->sale_state['change_sale_date_enable']);
			$this->set_email_receipt($this->sale_state['email_receipt']);
			$this->set_payments($this->sale_state['payments']);
			$this->set_customer($this->sale_state['customer']);
			$this->set_suspended_sale_id($this->sale_state['suspended_sale_id']);
			$this->set_change_sale_id($this->sale_state['change_sale_id']);
			$this->set_partial_transactions($this->sale_state['partial_transactions']);
			$this->set_save_credit_card_info($this->sale_state['save_credit_card_info']);
			$this->set_use_saved_cc_info($this->sale_state['use_saved_cc_info']);
			$this->set_selected_tier_id($this->sale_state['selected_tier_id']);
		}
	}

	function get_taxes($sale_id = false)
	{
		$taxes = array();
		
		if ($sale_id)
		{
			$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
			foreach($taxes_from_sale as $key=>$tax_item)
			{
				$name = $tax_item['percent'].'% ' . $tax_item['name'];
			
				if ($tax_item['cumulative'])
				{
					$prev_tax = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($taxes_from_sale[$key-1]['percent'])/100);
					$tax_amount=(($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100) + $prev_tax)*(($tax_item['percent'])/100);					
				}
				else
				{
					$tax_amount=($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
				}

				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
		}
		else
		{
			$customer_id = $this->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);

			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id!=-1)
			{
			   return array();
			}

			foreach($this->get_cart() as $line=>$item)
			{
				$price_to_use = $this->_get_price_for_item_in_cart($item);		
				
				$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes_finder->get_info($item['item_id']) : $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
				foreach($tax_info as $key=>$tax)
				{
					$name = $tax['percent'].'% ' . $tax['name'];
				
					if ($tax['cumulative'])
					{
						$prev_tax = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
						$tax_amount=(($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
					}
					else
					{
						$tax_amount=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					}

					if (!isset($taxes[$name]))
					{
						$taxes[$name] = 0;
					}
					$taxes[$name] += $tax_amount;
				}
			}
		}		
		return $taxes;
	}
	
	function get_items_in_cart()
	{
		$items_in_cart = 0;
		foreach($this->get_cart() as $item)
		{
		    $items_in_cart+=$item['quantity'];
		}
		
		return $items_in_cart;
	}
	
	function get_subtotal($sale_id = FALSE)
	{
		$subtotal = 0;
		foreach($this->get_cart() as $item)
		{
			$price_to_use = $this->_get_price_for_item_in_cart($item, $sale_id);
		    $subtotal+=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
		}
		
		return to_currency_no_money($subtotal);
	}
	
	function _get_price_for_item_in_cart($item, $sale_id = FALSE)
	{
		$price_to_use = $item['price'];
		
		if (isset($item['item_id']))
		{
			$item_info = $this->CI->Item->get_info($item['item_id']);
			if($item_info->tax_included)
			{
				$price_to_use = get_price_for_item_excluding_taxes($item['item_id'], $item['price'], $sale_id);
			}
		}
		elseif (isset($item['item_kit_id']))
		{
			$item_kit_info = $this->CI->Item_kit->get_info($item['item_kit_id']);
			if($item_kit_info->tax_included)
			{
				$price_to_use = get_price_for_item_kit_excluding_taxes($item['item_kit_id'], $item['price'], $sale_id);
			}
		}
		
		return $price_to_use;
	}

	function get_total($sale_id = false)
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
			$price_to_use = $this->_get_price_for_item_in_cart($item, $sale_id);
		    $total+=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
		}

		foreach($this->get_taxes($sale_id) as $tax)
		{
			$total+=$tax;
		}
		
		$total = $this->CI->config->item('round_cash_on_sales') && $this->is_sale_cash_payment() ?  round_to_nearest_05($total) : $total;
		return to_currency_no_money($total);
	}
	
	function is_sale_cash_payment()
	{
		foreach($this->get_payments() as $payment)
		{
			if($payment['payment_type'] ==  lang('sales_cash'))
			{
				return true;
			}
		}
		
		return false;
	}
}
?>