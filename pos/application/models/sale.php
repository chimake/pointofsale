<?php
class Sale extends CI_Model
{
	public function get_info($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}
	
	function get_cash_sales_total_for_shift($shift_start, $shift_end)
    {
		$sales_totals = $this->get_sales_totaled_by_id($shift_start, $shift_end);
        $employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
        
		$this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount', false);
        $this->db->from('sales_payments');
        $this->db->join('sales','sales_payments.sale_id=sales.sale_id');
		$this->db->where('sales_payments.payment_date >=', $shift_start);
		$this->db->where('sales_payments.payment_date <=', $shift_end);
		$this->db->where('employee_id', $employee_id);
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		
		$payments_by_sale = array();
		$sales_payments = $this->db->get()->result_array();
		
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
				
		$payment_data = $this->Sale->get_payment_data($payments_by_sale,$sales_totals);
		
		if (isset($payment_data[lang('sales_cash')]))
		{
			return $payment_data[lang('sales_cash')]['payment_amount'];
		}
		
		return 0.00;
    }
	
	function get_payment_data($payments_by_sale,$sales_totals)
	{
		$payment_data = array();
				
		foreach($payments_by_sale as $sale_id => $payment_rows)
		{
			if (isset($sales_totals[$sale_id]))
			{
				$total_sale_balance = $sales_totals[$sale_id];
				usort($payment_rows, array('Sale', '_sort_payments_for_sale'));
			
				foreach($payment_rows as $row)
				{
					$payment_amount = $row['payment_amount'] <= $total_sale_balance ? $row['payment_amount'] : $total_sale_balance;
				
					if (!isset($payment_data[$row['payment_type']]))
					{
						$payment_data[$row['payment_type']] = array('payment_type' => $row['payment_type'], 'payment_amount' => 0 );
					}
				
					if ($total_sale_balance != 0)
					{
						$payment_data[$row['payment_type']]['payment_amount'] += $payment_amount;
					}
				
					$total_sale_balance-=$payment_amount;
				}
			}
		}
		
		return $payment_data;
	}
	
	static function _sort_payments_for_sale($a,$b)
	{
		if ($a['payment_amount'] == $b['payment_amount']);
		{
			return 0;
		}
		
		if ($a['payment_amount']< $b['payment_amount'])
		{
			return -1;
		}
		
		return 1;
	}
	
	function get_sales_totaled_by_id($shift_start, $shift_end)
	{
        $employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$this->db->select('sales.sale_id', false);
        $this->db->from('sales');
        $this->db->join('sales_payments','sales_payments.sale_id=sales.sale_id');
		$this->db->where('sales_payments.payment_date >=', $shift_start);
		$this->db->where('sales_payments.payment_date <=', $shift_end);
		$this->db->where('employee_id', $employee_id);
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		
		$sale_ids = array();
		$result = $this->db->get()->result();
		foreach($result as $row)
		{
			$sale_ids[] = $row->sale_id;
		}
		
		$sales_totals = array();
		
		if (count($sale_ids) > 0)
		{
			$where = 'WHERE '.$this->db->dbprefix('sales').'.sale_id IN('.implode(',',$sale_ids).')';
			$this->_create_sales_items_temp_table_query($where);
			$this->db->select('sale_id, SUM(total) as total', false);
			$this->db->from('sales_items_temp');
			$this->db->group_by('sale_id');
			
			foreach($this->db->get()->result_array() as $sale_total_row)
			{
				$sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
			}
		}
		
		return $sales_totals;
	}

	/**
	 * added for cash register
	 * insert a log for track_cash_log
	 * @param array $data
	 */
	
	function update_register_log($data) {
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employee_id',$this->session->userdata('person_id'));
		return $this->db->update('register_log', $data) ? true : false;		
	}
	function insert_register($data) {
		return $this->db->insert('register_log', $data) ? $this->db->insert_id() : false;		
	}
	
	function is_register_log_open()
	{
		$this->db->from('register_log');
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employee_id',$this->session->userdata('person_id'));
		$query = $this->db->get();
		if($query->num_rows())
		return true	;
		else
		return false;
	
	 }

	function get_current_register_log()
	{
		$this->db->from('register_log');
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employee_id',$this->session->userdata('person_id'));
		$query = $this->db->get();
		if($query->num_rows())
		return $query->row();
		else
		return false;
	
	 }
	function exists($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	function update($sale_data, $sale_id)
	{
		$this->db->where('sale_id', $sale_id);
		$success = $this->db->update('sales',$sale_data);
		
		return $success;
	}
	
	function save ($items,$customer_id,$employee_id,$comment,$show_comment_on_receipt,$payments,$sale_id=false, $suspended = 0, $cc_ref_no = '', $auth_code = '', $change_sale_date=false,$balance=0, $store_account_payment = 0)
	{
		if(count($items)==0)
			return -1;

		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			$payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).'<br />';
		}
		
		$sales_data = array(
			'customer_id'=> $this->Customer->exists($customer_id) ? $customer_id : null,
			'employee_id'=>$employee_id,
			'payment_type'=>$payment_types,
			'comment'=>$comment,
			'show_comment_on_receipt'=> $show_comment_on_receipt ?  $show_comment_on_receipt : 0,
			'suspended'=>$suspended,
			'deleted' => 0,
			'cc_ref_no' => $cc_ref_no,
			'auth_code' => $auth_code,
			'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
			'store_account_payment' => $store_account_payment,
		);
			
		if($sale_id)
		{
			$old_date=$this->get_info($sale_id)->row_array();
			$sales_data['sale_time']=$old_date['sale_time'];
			
			if($change_sale_date) 
			{
				$sale_time = strtotime($change_sale_date);
				if($sale_time !== FALSE)
				{
					$sales_data['sale_time']=date('Y-m-d H:i:s', strtotime($change_sale_date));
				}
			}
			
		}
		else
		{
			$sales_data['sale_time'] = date('Y-m-d H:i:s');
		}
	
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$store_account_payment_amount = 0;
		
		if ($store_account_payment)
		{
			$store_account_payment_amount = $this->sale_lib->get_total();
		}
		
	     //Update customer store account balance
		if($this->Customer->exists($customer_id) && $balance)
		{
			$this->db->set('balance','balance+'.$balance,false);
			$this->db->where('person_id', $customer_id);
			$this->db->update('customers'); 
		 }

	     //Update customer store account if payment made
		if($this->Customer->exists($customer_id) && $store_account_payment_amount)
		{
			$this->db->set('balance','balance-'.$store_account_payment_amount,false);
			$this->db->where('person_id', $customer_id);
			$this->db->update('customers'); 
		 }
		 
		if ($sale_id)
		{
			//Delete previoulsy sale so we can overwrite data
			$this->delete($sale_id, true);
			
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sales', $sales_data);
		}
		else
		{
			$this->db->insert('sales',$sales_data);
			$sale_id = $this->db->insert_id();
		}
		
		
		 //insert store account transaction 
		if($this->Customer->exists($customer_id) && $balance)
		{
		 	$store_account_transaction = array(
		        'customer_id'=>$customer_id,
		        'sale_id'=>$sale_id,
				'comment'=>$comment,
		       	'transaction_amount'=>$balance,
				'balance'=>$this->Customer->get_info($customer_id)->balance
			);

			$this->db->insert('store_accounts',$store_account_transaction);
		 }
		 		 

		 //insert store account payment transaction 
		if($this->Customer->exists($customer_id) && $store_account_payment)
		{
		 	$store_account_transaction = array(
		        'customer_id'=>$customer_id,
		        'sale_id'=>$sale_id,
				'comment'=>$comment,
		       	'transaction_amount'=> -$store_account_payment_amount,
				'balance'=>$this->Customer->get_info($customer_id)->balance
			);

			$this->db->insert('store_accounts',$store_account_transaction);
		 }
		 
		$total_giftcard_payments = 0;

		foreach($payments as $payment_id=>$payment)
		{
			if ( substr( $payment['payment_type'], 0, strlen( lang('sales_giftcard') ) ) == lang('sales_giftcard') )
			{
				/* We have a gift card and we have to deduct the used value from the total value of the card. */
				$splitpayment = explode( ':', $payment['payment_type'] );
				$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $splitpayment[1] );
	
				$this->Giftcard->update_giftcard_value( $splitpayment[1], $cur_giftcard_value - $payment['payment_amount'] );
				$total_giftcard_payments+=$payment['payment_amount'];
			}

			$sales_payments_data = array
			(
				'sale_id'=>$sale_id,
				'payment_type'=>$payment['payment_type'],
				'payment_amount'=>$payment['payment_amount'],
				'payment_date' => $payment['payment_date'],
				'truncated_card' => $payment['truncated_card'],
				'card_issuer' => $payment['card_issuer'],
			);
			$this->db->insert('sales_payments',$sales_payments_data);
		}
	
		$has_added_giftcard_value_to_cost_price = $total_giftcard_payments > 0 ? false : true;
		$store_account_item_id = $this->Item->get_store_account_item_id();
		
		foreach($items as $line=>$item)
		{
			if (isset($item['item_id']))
			{
				$cur_item_info = $this->Item->get_info($item['item_id']);
				$cur_item_location_info = $this->Item_location->get_info($item['item_id']);
				
				if ($item['item_id'] != $store_account_item_id)
				{
					$cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;
				}
				else // Set cost price = price so we have no profit
				{
					$cost_price = $item['price'];
				}
				
				//Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
				if (!$has_added_giftcard_value_to_cost_price)
				{
					$cost_price+= $total_giftcard_payments;
					$has_added_giftcard_value_to_cost_price = true;
				}
				
				$reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;
				
				if ($cur_item_info->tax_included)
				{
					$item['price'] = get_price_for_item_excluding_taxes($item['item_id'], $item['price']);
				}
				
				$sales_items_data = array
				(
					'sale_id'=>$sale_id,
					'item_id'=>$item['item_id'],
					'line'=>$item['line'],
					'description'=>$item['description'],
					'serialnumber'=>$item['serialnumber'],
					'quantity_purchased'=>$item['quantity'],
					'discount_percent'=>$item['discount'],
					'item_cost_price' =>  $cost_price,
					'item_unit_price'=>$item['price']
				);

				$this->db->insert('sales_items',$sales_items_data);

				//create giftcard from sales 
				if($item['name']==lang('sales_giftcard') && !$this->Giftcard->get_giftcard_id($item['description'])) 
				{ 
					$giftcard_data = array(
						'giftcard_number'=>$item['description'],
						'value'=>$item['price'],
						'customer_id'=>$this->Customer->exists($customer_id) ? $customer_id : null,
					);
					
					$this->Giftcard->save($giftcard_data);
				}
				
				$stock_recorder_check=false;
				$out_of_stock_check=false;
				$email=false;
				$message = '';
				
				//checks if the quantity is greater than reorder level
				if(!$cur_item_info->is_service && $cur_item_location_info->quantity > $reorder_level)
				{
					$stock_recorder_check=true;
				}
				
				//checks if the quantity is greater than 0
				if(!$cur_item_info->is_service && $cur_item_location_info->quantity > 0)
				{
					$out_of_stock_check=true;
				}
				
				//Update stock quantity IF not a service 
				if (!$cur_item_info->is_service)
				{
					$cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;
					
					$item_data = array('quantity'=>$cur_item_location_info->quantity - $item['quantity']);
					$this->Item_location->save($item_data,$item['item_id']);
				}
				
				//Re-init $cur_item_location_info after updating quantity
				$cur_item_location_info = $this->Item_location->get_info($item['item_id']);
				
				//checks if the quantity is out of stock
				if($out_of_stock_check && $cur_item_location_info->quantity <= 0)
				{
					$message= $cur_item_info->name.' '.lang('sales_is_out_stock').' '.to_quantity($cur_item_location_info->quantity);
					$email=true;
					
				}	
				//checks if the quantity hits reorder level 
				else if($stock_recorder_check && ($cur_item_location_info->quantity <= $reorder_level))
				{
					$message= $cur_item_info->name.' '.lang('sales_hits_reorder_level').' '.to_quantity($cur_item_location_info->quantity);
					$email=true;
				}
				
				//send email 
				if($this->Location->get_info_for_key('receive_stock_alert') && $email)
				{			
					$this->load->library('email');
					$config = array();
					$config['mailtype'] = 'text';				
					$this->email->initialize($config);
					$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@phpsoftwares.com', $this->config->item('company'));
					$this->email->to($this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email')); 

					$this->email->subject(lang('sales_stock_alert_item_name').$this->Item->get_info($item['item_id'])->name);
					$this->email->message($message);	
					$this->email->send();
				}
				
				if (!$cur_item_info->is_service)
				{
					$qty_buy = -$item['quantity'];
					$sale_remarks =$this->config->item('sale_prefix').' '.$sale_id;
					$inv_data = array
					(
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$item['item_id'],
						'trans_user'=>$employee_id,
						'trans_comment'=>$sale_remarks,
						'trans_inventory'=>$qty_buy,
						'location_id' => $this->Employee->get_logged_in_employee_current_location_id() 
					);
					$this->Inventory->insert($inv_data);
				}
			}
			else
			{
				$cur_item_kit_info = $this->Item_kit->get_info($item['item_kit_id']);
				$cur_item_kit_location_info = $this->Item_kit_location->get_info($item['item_kit_id']);
				
				$cost_price = ($cur_item_kit_location_info && $cur_item_kit_location_info->cost_price) ? $cur_item_kit_location_info->cost_price : $cur_item_kit_info->cost_price;
				
				//Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
				if (!$has_added_giftcard_value_to_cost_price)
				{
					$cost_price+= $total_giftcard_payments;
					$has_added_giftcard_value_to_cost_price = true;
				}
				
				if ($cur_item_kit_info->tax_included)
				{
					$item['price'] = get_price_for_item_kit_excluding_taxes($item['item_kit_id'], $item['price']);
				}
				
				$sales_item_kits_data = array
				(
					'sale_id'=>$sale_id,
					'item_kit_id'=>$item['item_kit_id'],
					'line'=>$item['line'],
					'description'=>$item['description'],
					'quantity_purchased'=>$item['quantity'],
					'discount_percent'=>$item['discount'],
					'item_kit_cost_price' => $cost_price === NULL ? 0.00 : $cost_price,
					'item_kit_unit_price'=>$item['price']
				);

				$this->db->insert('sales_item_kits',$sales_item_kits_data);
				
				foreach($this->Item_kit_items->get_info($item['item_kit_id']) as $item_kit_item)
				{
					$cur_item_info = $this->Item->get_info($item_kit_item->item_id);
					$cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);
					
					$reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level !== NULL) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;
					
					$stock_recorder_check=false;
					$out_of_stock_check=false;
					$email=false;
					$message = '';


					//checks if the quantity is greater than reorder level
					if(!$cur_item_info->is_service && $cur_item_location_info->quantity > $reorder_level)
					{
						$stock_recorder_check=true;
					}

					//checks if the quantity is greater than 0
					if(!$cur_item_info->is_service && $cur_item_location_info->quantity > 0)
					{
						$out_of_stock_check=true;
					}

					//Update stock quantity IF not a service item and the quantity for item is NOT NULL
					if (!$cur_item_info->is_service)
					{
						$cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;
	
						$item_data = array('quantity'=>$cur_item_location_info->quantity - ($item['quantity'] * $item_kit_item->quantity));
						$this->Item_location->save($item_data,$item_kit_item->item_id);
					}
					
					//Re-init $cur_item_location_info after updating quantity
					$cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);
				
					//checks if the quantity is out of stock
					if($out_of_stock_check && !$cur_item_info->is_service && $cur_item_location_info->quantity <= 0)
					{
						$message= $cur_item_info->name.' '.lang('sales_is_out_stock').' '.to_quantity($cur_item_location_info->quantity);
						$email=true;

					}	
					//checks if the quantity hits reorder level 
					else if($stock_recorder_check && ($cur_item_location_info->quantity <= $reorder_level))
					{
						$message= $cur_item_info->name.' '.lang('sales_hits_reorder_level').' '.to_quantity($cur_item_location_info->quantity);
						$email=true;
					}

					//send email 
					if($this->Location->get_info_for_key('receive_stock_alert') && $email)
					{			
						$this->load->library('email');
						$config = array();
						$config['mailtype'] = 'text';				
						$this->email->initialize($config);
						$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@phpsoftwares.com', $this->config->item('company'));
						$this->email->to($this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email')); 

						$this->email->subject(lang('sales_stock_alert_item_name').$cur_item_info->name);
						$this->email->message($message);	
						$this->email->send();
					}

					if (!$cur_item_info->is_service)
					{
						$qty_buy = -$item['quantity'] * $item_kit_item->quantity;
						$sale_remarks =$this->config->item('sale_prefix').' '.$sale_id;
						$inv_data = array
						(
							'trans_date'=>date('Y-m-d H:i:s'),
							'trans_items'=>$item_kit_item->item_id,
							'trans_user'=>$employee_id,
							'trans_comment'=>$sale_remarks,
							'trans_inventory'=>$qty_buy,
							'location_id' => $this->Employee->get_logged_in_employee_current_location_id()
						);
						$this->Inventory->insert($inv_data);					
					}
				}
			}
			
			$customer = $this->Customer->get_info($customer_id);
 			if ($customer_id == -1 or $customer->taxable)
 			{
				if (isset($item['item_id']))
				{
					foreach($this->Item_taxes_finder->get_info($item['item_id']) as $row)
					{
						$this->db->insert('sales_items_taxes', array(
							'sale_id' 	=>$sale_id,
							'item_id' 	=>$item['item_id'],
							'line'      =>$item['line'],
							'name'		=>$row['name'],
							'percent' 	=>$row['percent'],
							'cumulative'=>$row['cumulative']
						));
					}
				}
				else
				{
					foreach($this->Item_kit_taxes_finder->get_info($item['item_kit_id']) as $row)
					{
						$this->db->insert('sales_item_kits_taxes', array(
							'sale_id' 		=>$sale_id,
							'item_kit_id'	=>$item['item_kit_id'],
							'line'      	=>$item['line'],
							'name'			=>$row['name'],
							'percent' 		=>$row['percent'],
							'cumulative'	=>$row['cumulative']
						));
					}					
				}
			}
		}
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return -1;
		}
		
		return $sale_id;
	}
	
	function update_store_account($sale_id,$undelete=0)
	{
		//update if Store account payment exists
		$this->db->from('sales_payments');
		$this->db->where('payment_type',lang('sales_store_account'));
		$this->db->where('sale_id',$sale_id);
		$to_be_paid_result = $this->db->get();
		
		$customer_id=$this->get_customer($sale_id)->person_id;
		
		
		if($to_be_paid_result->num_rows >=1)
		{
			foreach($to_be_paid_result->result() as $to_be_paid)
			{
				if($to_be_paid->payment_amount > 0) 
				{
					//update customer balance
					if($undelete==0)
					{
						$this->db->set('balance','balance-'.$to_be_paid->payment_amount,false);
					}
					else
					{
						$this->db->set('balance','balance+'.$to_be_paid->payment_amount,false);
					}
					$this->db->where('person_id', $customer_id);
					$this->db->update('customers'); 
				
					//delete transaction in store accounts
					$this->db->where('sale_id', $sale_id);
					if($undelete==0)
					{
						$this->db->update('store_accounts', array('deleted' => 1));
					}
					else
					{
						$this->db->update('store_accounts', array('deleted' => 0));
					}
				}
			}
		}
	}
	
	function update_giftcard_balance($sale_id,$undelete=0)
	{
		//if gift card payment exists add the amount to giftcard balance
			$this->db->from('sales_payments');
			$this->db->like('payment_type',lang('sales_giftcard'));
			$this->db->where('sale_id',$sale_id);
			$sales_payment = $this->db->get();
			
			if($sales_payment->num_rows >=1)
			{
				foreach($sales_payment->result() as $row)
				{
					$giftcard_number=str_ireplace(lang('sales_giftcard').':','',$row->payment_type);
					$value=$row->payment_amount;
					if($undelete==0)
					{
						$this->db->set('value','value+'.$value,false);			
					}
					else
					{
						$this->db->set('value','value-'.$value,false);
					}
					$this->db->where('giftcard_number', $giftcard_number);
					$this->db->update('giftcards'); 
				}
			}
	
	}
	
	function delete($sale_id, $all_data = false)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		
		$this->db->select('sales.location_id, item_id, quantity_purchased');
		$this->db->from('sales_items');
		$this->db->join('sales', 'sales.sale_id = sales_items.sale_id');
		$this->db->where('sales_items.sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			$sale_location_id = $sale_item_row['location_id'];
			$cur_item_info = $this->Item->get_info($sale_item_row['item_id']);	
			$cur_item_location_info = $this->Item_location->get_info($sale_item_row['item_id'], $sale_location_id);
			
			$cur_item_quantity = $this->Item_location->get_location_quantity($sale_item_row['item_id'], $sale_location_id);
			
			if (!$cur_item_info->is_service)
			{
				//Update stock quantity
				$item_data = array(
					'quantity'=>$cur_item_quantity + $sale_item_row['quantity_purchased'],
					'location_id'=>$sale_location_id,
					'item_id'=>$sale_item_row['item_id']
					);

					$this->Item_location->save($item_data,$sale_item_row['item_id'], $sale_location_id);
			
					$sale_remarks =$this->config->item('sale_prefix').' '.$sale_id;
					$inv_data = array
					(
						'location_id' => $sale_location_id,
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$sale_item_row['item_id'],
						'trans_user'=>$employee_id,
						'trans_comment'=>$sale_remarks,
						'trans_inventory'=>$sale_item_row['quantity_purchased']
					);
					$this->Inventory->insert($inv_data);
			}
		}
		
		$this->db->select('sales.location_id, item_kit_id, quantity_purchased');
		$this->db->from('sales_item_kits');
		$this->db->join('sales', 'sales.sale_id = sales_item_kits.sale_id');
		$this->db->where('sales_item_kits.sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item)
			{
				$sale_location_id = $sale_item_kit_row['location_id'];
				$cur_item_info = $this->Item->get_info($item_kit_item->item_id);
				$cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id, $sale_location_id);

				if (!$cur_item_info->is_service)
				{
					$cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;
					
					$item_data = array('quantity'=>$cur_item_location_info->quantity + ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));

					$this->Item_location->save($item_data,$item_kit_item->item_id, $sale_location_id);

					$sale_remarks =$this->config->item('sale_prefix').' '.$sale_id;
					$inv_data = array
					(
						'location_id' => $sale_location_id,
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$item_kit_item->item_id,
						'trans_user'=>$employee_id,
						'trans_comment'=>$sale_remarks,
						'trans_inventory'=>$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity
					);
					$this->Inventory->insert($inv_data);
				}				
			}
		}
		
		
		$this->update_store_account($sale_id);
		$this->update_giftcard_balance($sale_id);
		
		
		if ($all_data)
		{
			//Run these queries as a transaction, we want to make sure we do all or nothing
			$this->db->trans_start();
			$this->db->delete('sales_payments', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_items_taxes', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_items', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_item_kits_taxes', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_item_kits', array('sale_id' => $sale_id)); 
			$this->db->trans_complete();			
		}

		$this->db->where('sale_id', $sale_id);
		return $this->db->update('sales', array('deleted' => 1,'deleted_by'=>$employee_id));
	}
	
	function undelete($sale_id)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
	
		$this->db->select('sales.location_id, item_id, quantity_purchased');
		$this->db->from('sales_items');
		$this->db->join('sales', 'sales.sale_id = sales_items.sale_id');
		$this->db->where('sales_items.sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			$sale_location_id = $sale_item_row['location_id'];
			$cur_item_info = $this->Item->get_info($sale_item_row['item_id']);	
			$cur_item_location_info = $this->Item_location->get_info($sale_item_row['item_id'], $sale_location_id);

			if (!$cur_item_info->is_service && $cur_item_location_info->quantity !== NULL)
			{
				//Update stock quantity
				$item_data = array(
					'quantity'=>$cur_item_location_info->quantity - $sale_item_row['quantity_purchased'],
					'location_id'=>$this->Employee->get_logged_in_employee_current_location_id(),
					'item_id'=>$sale_item_row['item_id']
				);

				$this->Item_location->save($item_data,$sale_item_row['item_id']);
		
				$sale_remarks =$this->config->item('sale_prefix').' '.$sale_id;
				$inv_data = array
				(
					'location_id' => $sale_location_id,
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$sale_item_row['item_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>-$sale_item_row['quantity_purchased']
					);
				$this->Inventory->insert($inv_data);
			}
		}
		
		$this->update_store_account($sale_id,1);
		$this->update_giftcard_balance($sale_id,1);

		
		$this->db->select('sales.location_id, item_kit_id, quantity_purchased');
		$this->db->from('sales_item_kits');
		$this->db->join('sales', 'sales.sale_id = sales_item_kits.sale_id');
		$this->db->where('sales_item_kits.sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item)
			{
				$sale_location_id = $sale_item_kit_row['location_id'];
				$cur_item_info = $this->Item->get_info($item_kit_item->item_id);
				$cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id, $sale_location_id);
				if (!$cur_item_info->is_service && $cur_item_location_info->quantity !== NULL)
				{
					$item_data = array('quantity'=>$cur_item_location_info->quantity - ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));

					$this->Item_location->save($item_data,$item_kit_item->item_id, $sale_location_id);
					
					$sale_remarks =$this->config->item('sale_prefix').' '.$sale_id;
					$inv_data = array
					(
						'location_id' => $sale_location_id,
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$item_kit_item->item_id,
						'trans_user'=>$employee_id,
						'trans_comment'=>$sale_remarks,
						'trans_inventory'=>-$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity
					);
					$this->Inventory->insert($inv_data);					
				}
			}
		}	
		$this->db->where('sale_id', $sale_id);
		return $this->db->update('sales', array('deleted' => 0, 'deleted_by' => NULL));
	}

	function get_sale_items($sale_id)
	{
		$this->db->from('sales_items');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function get_sale_item_kits($sale_id)
	{
		$this->db->from('sales_item_kits');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}
	
	function get_sale_items_taxes($sale_id, $item_id = FALSE)
	{
		$item_where = '';
		
		if ($item_id)
		{
			$item_where = 'and item_id = '.$item_id;
		}

		$query = $this->db->query('SELECT name, percent, cumulative, item_unit_price as price, quantity_purchased as quantity, discount_percent as discount '.
		'FROM '. $this->db->dbprefix('sales_items_taxes'). ' JOIN '.
		$this->db->dbprefix('sales_items'). ' USING (sale_id, item_id, line) '.
		'WHERE '.$this->db->dbprefix('sales_items_taxes').".sale_id = $sale_id".' '.$item_where.' '.
		'ORDER BY line,item_id,cumulative,name,percent');
		return $query->result_array();
	}
	
	function get_sale_item_kits_taxes($sale_id, $item_kit_id = FALSE)
	{
		$item_kit_where = '';
		
		if ($item_kit_id)
		{
			$item_kit_where = 'and item_kit_id = '.$item_kit_id;
		}
		
		$query = $this->db->query('SELECT name, percent, cumulative, item_kit_unit_price as price, quantity_purchased as quantity, discount_percent as discount '.
		'FROM '. $this->db->dbprefix('sales_item_kits_taxes'). ' JOIN '.
		$this->db->dbprefix('sales_item_kits'). ' USING (sale_id, item_kit_id, line) '.
		'WHERE '.$this->db->dbprefix('sales_item_kits_taxes').".sale_id = $sale_id".' '.$item_kit_where.' '.
		'ORDER BY line,item_kit_id,cumulative,name,percent');
		return $query->result_array();	
	}

	function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function get_customer($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}
	
	function get_comment($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get()->row()->comment;
	}
	
	function get_comment_on_receipt($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get()->row()->show_comment_on_receipt;
	}
	

	//We create a temp table that allows us to do easy report/sales queries
	public function create_sales_items_temp_table($params)
	{
		if (!$this->session->userdata('sales_report_running'))
		{
			$this->session->set_userdata('sales_report_running', TRUE);
		
			$location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
			$where = '';
		
			if (isset($params['start_date']) && isset($params['end_date']))
			{
				$where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.' and '.$this->db->dbprefix('sales').'.location_id='.$this->db->escape($location_id). (($this->config->item('hide_store_account_payments_in_reports') ) ? ' and '.$this->db->dbprefix('sales').'.store_account_payment=0' : '');
			
				if ($this->config->item('hide_suspended_sales_in_reports'))
				{
					$where .=' and suspended = 0';
				}
			}
			elseif ($this->config->item('hide_suspended_sales_in_reports'))
			{
				$where .='WHERE suspended = 0'.' and '.$this->db->dbprefix('sales').'.location_id='.$this->db->escape($location_id).(($this->config->item('hide_store_account_payments_in_reports') ) ? ' and '.$this->db->dbprefix('sales').'.store_account_payment=0' : '');
			}
			
			if ($where == '')
			{
				$where = 'WHERE '.$this->db->dbprefix('sales').'.location_id='.$this->db->escape($location_id).(($this->config->item('hide_store_account_payments_in_reports') ) ? ' and '.$this->db->dbprefix('sales').'.store_account_payment=0' : '');
			}
			
		
			$this->_create_sales_items_temp_table_query($where);
			$this->session->set_userdata('sales_report_running', FALSE);
			
			return TRUE;
		}
		
		return FALSE;
		
	}
	
	function _create_sales_items_temp_table_query($where)
	{
		set_time_limit(0);

		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('sales_items_temp')."
		(SELECT ".$this->db->dbprefix('sales').".deleted as deleted,".$this->db->dbprefix('sales').".deleted_by as deleted_by, sale_time, date(sale_time) as sale_date, ".$this->db->dbprefix('sales_items').".sale_id, comment,payment_type, customer_id, employee_id, 
		".$this->db->dbprefix('items').".item_id, NULL as item_kit_id, supplier_id, quantity_purchased, item_cost_price, item_unit_price, category, 
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('sales_items').".line as line, serialnumber, ".$this->db->dbprefix('sales_items').".description as description,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)+(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) 
		+(((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as total,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) 
		+(((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as tax,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('sales_items')."
		INNER JOIN ".$this->db->dbprefix('sales')." ON  ".$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales').'.sale_id'."
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('items').'.supplier_id='.$this->db->dbprefix('suppliers').'.person_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('sales_items_taxes')." ON  "
		.$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales_items_taxes').'.sale_id'." and "
		.$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('sales_items_taxes').'.item_id'." and "
		.$this->db->dbprefix('sales_items').'.line='.$this->db->dbprefix('sales_items_taxes').'.line'. "
		$where
		GROUP BY sale_id, item_id, line) 
		UNION ALL
		(SELECT ".$this->db->dbprefix('sales').".deleted as deleted,".$this->db->dbprefix('sales').".deleted_by as deleted_by, sale_time, date(sale_time) as sale_date, ".$this->db->dbprefix('sales_item_kits').".sale_id, comment,payment_type, customer_id, employee_id, 
		NULL as item_id, ".$this->db->dbprefix('item_kits').".item_kit_id, '' as supplier_id, quantity_purchased, item_kit_cost_price, item_kit_unit_price, category, 
		discount_percent, (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('sales_item_kits').".line as line, '' as serialnumber, ".$this->db->dbprefix('sales_item_kits').".description as description,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)+(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) 
		+(((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as total,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) 
		+(((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as tax,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100) - (item_kit_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('sales_item_kits')."
		INNER JOIN ".$this->db->dbprefix('sales')." ON  ".$this->db->dbprefix('sales_item_kits').'.sale_id='.$this->db->dbprefix('sales').'.sale_id'."
		INNER JOIN ".$this->db->dbprefix('item_kits')." ON  ".$this->db->dbprefix('sales_item_kits').'.item_kit_id='.$this->db->dbprefix('item_kits').'.item_kit_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('sales_item_kits_taxes')." ON  "
		.$this->db->dbprefix('sales_item_kits').'.sale_id='.$this->db->dbprefix('sales_item_kits_taxes').'.sale_id'." and "
		.$this->db->dbprefix('sales_item_kits').'.item_kit_id='.$this->db->dbprefix('sales_item_kits_taxes').'.item_kit_id'." and "
		.$this->db->dbprefix('sales_item_kits').'.line='.$this->db->dbprefix('sales_item_kits_taxes').'.line'. "
		$where
		GROUP BY sale_id, item_kit_id, line) ORDER BY sale_id, line");
	}
	
	
	public function get_giftcard_value( $giftcardNumber )
	{
		if ( !$this->Giftcard->exists( $this->Giftcard->get_giftcard_id($giftcardNumber)))
			return 0;
		
		$this->db->from('giftcards');
		$this->db->where('giftcard_number',$giftcardNumber);
		return $this->db->get()->row()->value;
	}
	
	function get_all_suspended()
	{
		$this->db->from('sales');
		$this->db->join('customers', 'sales.customer_id = customers.person_id', 'left');
		$this->db->join('people', 'customers.person_id = people.person_id', 'left');
		$this->db->where('sales.deleted', 0);
		$this->db->where('suspended', 1);
		$this->db->order_by('sale_id');
		$sales = $this->db->get()->result_array();

		for($k=0;$k<count($sales);$k++)
		{
			$item_names = array();
			$this->db->select('name');
			$this->db->from('items');
			$this->db->join('sales_items', 'sales_items.item_id = items.item_id');
			$this->db->where('sale_id', $sales[$k]['sale_id']);
		
			foreach($this->db->get()->result_array() as $row)
			{
				$item_names[] = $row['name'];
			}
			
			$this->db->select('name');
			$this->db->from('item_kits');
			$this->db->join('sales_item_kits', 'sales_item_kits.item_kit_id = item_kits.item_kit_id');
			$this->db->where('sale_id', $sales[$k]['sale_id']);
		
			foreach($this->db->get()->result_array() as $row)
			{
				$item_names[] = $row['name'];
			}
			
			
			$sales[$k]['items'] = implode(', ', $item_names);
		}
		
		return $sales;
		
	}
	
	function count_all()
	{
		$this->db->from('sales');
		$this->db->where('deleted',0);
		
		if ($this->config->item('hide_store_account_payments_in_reports'))
		{
			$this->db->where('store_account_payment',0);
		}
		
		return $this->db->count_all_results();
	}
	
	function get_recent_sales_for_customer($customer_id)
	{
		$return = array();
		
		if (!$this->create_sales_items_temp_table(array('start_date' =>date('Y-m-d', strtotime('-60 days')), 'end_date' => date('Y-m-d').' 23:59:59')))
		{
			return array();
		}
		
		$this->db->select('sale_id, sale_date, sum(quantity_purchased) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		$this->db->order_by('sale_date');
		$this->db->limit(10);
		
		foreach($this->db->get()->result_array() as $row)
		{
			$return[] = $row;
		}

		return $return;
	}
}
?>
