<?php
require_once ("secure_area.php");
class Sales extends Secure_area
{
	function __construct()
	{
		parent::__construct('sales');
		$this->load->library('sale_lib');
	}

	function index()
	{
		if ($this->config->item('track_cash')) {
			if ($this->input->post('opening_amount') != '') {
				$now = date('Y-m-d H:i:s');
				
				$cash_register = new stdClass();
				
				$cash_register->employee_id = $this->session->userdata('person_id');
				$cash_register->shift_start = $now;
				$cash_register->open_amount = $this->input->post('opening_amount');
				$cash_register->close_amount = 0;
				$cash_register->cash_sales_amount = 0;
				$this->Sale->insert_register($cash_register);
				
				redirect(site_url('sales'));
			} else if ($this->Sale->is_register_log_open()) {
				$this->_reload(array(), false);
			} else {
				$this->load->view('sales/opening_amount');
			}
		} else {
			$this->_reload(array(), false);
		}
	}
	
	function closeregister() 
	{
		if (!$this->Sale->is_register_log_open()) 
		{
			redirect(site_url('home'));
			return;
		}
		$cash_register = $this->Sale->get_current_register_log();
		$continueUrl = $this->input->get('continue');
		if ($this->input->post('closing_amount') != '') {
			$now = date('Y-m-d H:i:s');
			$cash_register->shift_end = $now;
			$cash_register->close_amount = $this->input->post('closing_amount');
			$cash_register->cash_sales_amount = $this->Sale->get_cash_sales_total_for_shift($cash_register->shift_start, $cash_register->shift_end);			
			unset($cash_register->register_log_id);
			$this->Sale->update_register_log($cash_register);
			if ($continueUrl == 'logout') {
				redirect(site_url('home/logout'));
			} else {
				redirect(site_url('home'));
			}
		} else {
			$this->load->view('sales/closing_amount', array(
				'continue'=>$continueUrl ? "?continue=$continueUrl" : '',
				'closeout'=>to_currency($cash_register->open_amount + $this->Sale->get_cash_sales_total_for_shift($cash_register->shift_start, date("Y-m-d H:i:s")))
			));
		}
	}
	
	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->get('term'),100));
		echo json_encode($suggestions);
	}

	function customer_search()
	{
		$suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function select_customer()
	{
		$data = array();
		$customer_id = $this->input->post("customer");
			
		if ($this->Customer->account_number_exists($customer_id))
		{
			$customer_id = $this->Customer->customer_id_from_account_number($customer_id);
		}
		
		if ($this->Customer->exists($customer_id))
		{
			$customer_info=$this->Customer->get_info($customer_id);
		
			if ($customer_info->tier_id)
			{
				$this->sale_lib->set_selected_tier_id($customer_info->tier_id);
			}
			
			$this->sale_lib->set_customer($customer_id);
			if($this->config->item('automatically_email_receipt'))
			{
				$this->sale_lib->set_email_receipt(1);
			}
		}
		else
		{
			$data['error']=lang('sales_unable_to_add_customer');
		}
		$this->_reload($data);
	}

	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->sale_lib->set_mode($mode);
		
		if ($mode == 'store_account_payment')
		{
			$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
			$this->sale_lib->empty_cart();
			$this->sale_lib->add_item($store_account_payment_item_id,1);
		}
		
		$this->_reload();
	}
	
	function set_comment() 
	{
 	  $this->sale_lib->set_comment($this->input->post('comment'));
	}
	
	function set_change_sale_date() 
	{
 	  $this->sale_lib->set_change_sale_date($this->input->post('change_sale_date'));
	}
	
	function set_change_sale_date_enable() 
	{
 	  $this->sale_lib->set_change_sale_date_enable($this->input->post('change_sale_date_enable'));
	  if (!$this->sale_lib->get_change_sale_date())
	  {
	 	  $this->sale_lib->set_change_sale_date(date(get_date_format()));
	  }
	}
	
	function set_comment_on_receipt() 
	{
 	  $this->sale_lib->set_comment_on_receipt($this->input->post('show_comment_on_receipt'));
	}
	
	function set_email_receipt()
	{
 	  $this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	function set_save_credit_card_info() 
	{
 	  $this->sale_lib->set_save_credit_card_info($this->input->post('save_credit_card_info'));
	}
	
	function set_use_saved_cc_info()
	{
 	  $this->sale_lib->set_use_saved_cc_info($this->input->post('use_saved_cc_info'));
	}
	
	function set_tier_id() 
	{
 	  $this->sale_lib->set_selected_tier_id($this->input->post('tier_id'));
	}

	//Alain Multiple Payments
	function add_payment()
	{		
		$data=array();
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			if ( $this->input->post('payment_type') == lang('sales_giftcard') )
				$data['error']=lang('sales_must_enter_numeric_giftcard');
			else
				$data['error']=lang('sales_must_enter_numeric');
				
 			$this->_reload($data);
 			return;
		}
		
		if (($this->input->post('payment_type') == lang('sales_store_account') && $this->sale_lib->get_customer() == -1) ||
			($this->sale_lib->get_mode() == 'store_account_payment' && $this->sale_lib->get_customer() == -1)
			) 
		{
				$data['error']=lang('sales_customer_required_store_account');
				$this->_reload($data);
				return;
		}
				
		$payment_type=$this->input->post('payment_type');
		$payment_total = $this->input->post('payment_total');		


		if ( $payment_type == lang('sales_giftcard') )
		{
			if(!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($this->input->post('amount_tendered'))))
			{
				$data['error']=lang('sales_giftcard_does_not_exist');
				$this->_reload($data);
				return;
			}
			
			$payment_type=$this->input->post('payment_type').':'.$this->input->post('amount_tendered');
			$current_payments_with_giftcard = $this->sale_lib->get_payment_amount($payment_type);
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			if ( $cur_giftcard_value <= 0 && $this->sale_lib->get_total() > 0)
			{
				$data['error']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				$this->_reload($data);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				$data['warning']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			$payment_amount=min( $this->sale_lib->get_amount_due(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
		}
		else
		{
			$payment_amount=$this->input->post('amount_tendered');
		}
		
		if( !$this->sale_lib->add_payment( $payment_type, $payment_amount, $payment_total ) )
		{
			$data['error']=lang('sales_unable_to_add_payment');
		}
		
		$this->_reload($data);
	}

	//Alain Multiple Payments
	function delete_payment($payment_id)
	{
		$this->sale_lib->delete_payment($payment_id);
		$this->_reload();
	}

	function add()
	{		
		$data=array();
		$mode = $this->sale_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		$quantity = $mode=="sale" ? 1:-1;

		if($this->sale_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$this->sale_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->sale_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->sale_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity);

			//As surely a Kit item , do out of stock check
			$item_kit_id = $this->sale_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);

			if($this->sale_lib->out_of_stock_kit($item_kit_id))
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
		}
		else if(!$this->Item->get_info($item_id_or_number_or_item_kit_or_receipt)->description=="" && $this->Giftcard->get_giftcard_id($this->Item->get_info($item_id_or_number_or_item_kit_or_receipt)->description,true))
		{
			$data['error']=lang('sales_unable_to_add_item');
		}
		elseif(!$this->sale_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=lang('sales_unable_to_add_item');
		}
		
		if($this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt))
		{
			$data['warning'] = lang('sales_quantity_less_than_zero');
		}
		$this->_reload($data);
	}

	function edit_item($line)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'numeric');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");


		if ($this->form_validation->run() != FALSE)
		{
			$this->sale_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price);
		}
		else
		{
			$data['error']=lang('sales_error_editing_item');
		}
		
		if($this->sale_lib->is_kit_or_item($line) == 'item')
		{
			if($this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line)))
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
		}
		elseif($this->sale_lib->is_kit_or_item($line) == 'kit')
		{
		    if($this->sale_lib->out_of_stock_kit($this->sale_lib->get_kit_id($line)))
		    {
			    $data['warning'] = lang('sales_quantity_less_than_zero');
		    }
		}

		$this->_reload($data);
	}

	function delete_item($item_number)
	{
		$this->sale_lib->delete_item($item_number);
		$this->_reload();
	}

	function delete_customer()
	{
		$this->sale_lib->delete_customer();
   	  	$this->sale_lib->set_selected_tier_id(0);
		$this->_reload();
	}
	
	function start_cc_processing()
	{
		$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
		$cc_amount = to_currency_no_money($this->sale_lib->get_payment_amount(lang('sales_credit')));
		$tax_amount = to_currency_no_money(($this->sale_lib->get_total() - $this->sale_lib->get_subtotal()) * ($cc_amount / $this->sale_lib->get_total()));
		$customer_id = $this->sale_lib->get_customer();
		$customer_name = '';
		if ($customer_id != -1)
		{
			$customer_info=$this->Customer->get_info($customer_id);
			$customer_name = $customer_info->first_name.' '.$customer_info->last_name;
		}
		
		if(!$this->sale_lib->get_use_saved_cc_info())
		{
			$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);

			$parameters = array(
				'request' => array(
					'MerchantID' => $this->Location->get_info_for_key('merchant_id'),
					'Password' => $this->Location->get_info_for_key('merchant_password'),
					'TranType' => $cc_amount > 0 ? 'Sale' : 'Return',
					'TotalAmount' => abs($cc_amount),
					'PartialAuth' => 'On',
					'Frequency' => 'OneTime',
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
					'Invoice' => $invoice_number,
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'TaxAmount' => abs($tax_amount),
					'CardHolderName' => preg_replace("/[^A-Za-z ]/", '', $customer_name),
					'ForceManualTablet' => 'Off',
					'ProcessCompleteUrl' => site_url('sales/finish_cc_processing'),
					'ReturnUrl' => site_url('sales/cancel_cc_processing'),
				)
			);

			if (isset($customer_info) && $customer_info->zip)
			{
				$parameters['request']['AVSZip'] = $customer_info->zip;
			}

			$client = new SoapClient($service_url,array('trace' => TRUE));
			$result = $client->InitializePayment($parameters);
			$response_code = $result->InitializePaymentResult->ResponseCode;

			if ($response_code == 0)
			{
				$payment_id = $result->InitializePaymentResult->PaymentID;
				$hosted_checkout_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/CheckoutPOS.aspx' : 'https://hc.mercurypay.com/CheckoutPOS.aspx';
				$this->load->view('sales/hosted_checkout', array('payment_id' => $payment_id, 'hosted_checkout_url' =>$hosted_checkout_url ));
			}
			else
			{
				$this->_reload(array('error' => lang('sales_credit_card_processing_is_down')), false);
			}			
		}
		elseif($customer_info->cc_token) //We have saved credit card information, process it
		{
			$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL': 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
			$client = new SoapClient($service_url,array('trace' => TRUE));
			$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);
			
			$parameters = array(
				'request' => array(
					'Token' => $customer_info->cc_token,
					'MerchantID' => $this->Location->get_info_for_key('merchant_id'),
					'PurchaseAmount' => $cc_amount,
					'PartialAuth' => FALSE,
					'Frequency' => 'OneTime',
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
					'Invoice' => $invoice_number,
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'TaxAmount' => $tax_amount,
					'CardHolderName' => preg_replace("/[^A-Za-z ]/", '', $customer_name),
				),
				'password' => $this->Location->get_info_for_key('merchant_password'),
			);

			if (isset($customer_info) && $customer_info->zip)
			{
				$parameters['request']['Zip'] = $customer_info->zip;
			}
			$result = $client->CreditSaleToken($parameters);
			
			$status = $result->CreditSaleTokenResult->Status;

			
			if ($status == 'Approved')	
			{
				$token =  $result->CreditSaleTokenResult->Token;
				$ref_no =  $result->CreditSaleTokenResult->RefNo;
				$auth_code = $result->CreditSaleTokenResult->AuthCode;
				$masked_account = $customer_info->cc_preview;
				$card_issuer = $customer_info->card_issuer;
				$acq_ref_data = $result->CreditSaleTokenResult->AcqRefData;
				$process_data =  $result->CreditSaleTokenResult->ProcessData;
				
				$person_info = array('person_id' => $this->sale_lib->get_customer());
				$customer_info = array('cc_token' => $token);
				$this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
				$this->session->set_flashdata('ref_no', $ref_no);
				$this->session->set_flashdata('auth_code', $auth_code);
				$this->session->set_flashdata('masked_account', $masked_account);
				$this->session->set_flashdata('card_issuer', $card_issuer);
				
				//If the sale payments cover the total, redirect to complete (receipt)
				if ($this->_payments_cover_total())
				{
					redirect(site_url('sales/complete'));
				}
				else //Change payment type to Partial Credit Card and show sales interface
				{
					$credit_card_amount = to_currency_no_money($this->sale_lib->get_payment_amount(lang('sales_credit')));

					$partial_transaction = array(
						'AuthCode' => $auth_code,
						'Frequency' => 'OneTime',
						'Memo' => 'PHP POS '.APPLICATION_VERSION,
						'Invoice' => $invoice_number,
						'MerchantID' => $this->Location->get_info_for_key('merchant_id'),
						'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
						'PurchaseAmount' => $credit_card_amount,
						'RefNo' => $ref_no,
						'Token' => $token,
						'AcqRefData' =>$acq_ref_data,
						'ProcessData' => $process_data,
					);
										
					$this->sale_lib->delete_payment($this->sale_lib->get_payment_ids(lang('sales_credit')));
					$this->sale_lib->add_payment(lang('sales_partial_credit'), $credit_card_amount, FALSE, $masked_account, $card_issuer);
					$this->sale_lib->add_partial_transaction($partial_transaction);
					$this->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);			
				}
			}
			else
			{
				//If we have failed, remove cc token and cc preview
				$person_info = array('person_id' => $this->sale_lib->get_customer());
				$customer_info = array('cc_token' => NULL, 'cc_preview' => NULL, 'card_issuer' => NULL);
				$this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
				
				//Clear cc token for using saved cc info
				$this->sale_lib->clear_use_saved_cc_info();
				$this->_reload(array('error' => lang('sales_charging_card_failed_please_try_again')), false);
			}

		}
		
	}
	
	function finish_cc_processing()
	{
		$return_code = $this->input->get("ReturnCode");
		
		$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
		$parameters = array(
			'request' => array(
				'MerchantID' => $this->Location->get_info_for_key('merchant_id'),
				'PaymentID' => $this->input->get('PaymentID'),
				'Password' => $this->Location->get_info_for_key('merchant_password'),
			)
		);

		$client = new SoapClient($service_url,array('trace' => TRUE));
		$result = $client->VerifyPayment($parameters);
		$response_code = $result->VerifyPaymentResult->ResponseCode;
		$status = $result->VerifyPaymentResult->Status;
		$total_amount = $result->VerifyPaymentResult->Amount;
		$auth_amount = $result->VerifyPaymentResult->AuthAmount;
		
		$auth_code = $result->VerifyPaymentResult->AuthCode;
		$acq_ref_data = $result->VerifyPaymentResult->AcqRefData;
		$ref_no =  $result->VerifyPaymentResult->RefNo;
		$token =  $result->VerifyPaymentResult->Token;
		$masked_account = $result->VerifyPaymentResult->MaskedAccount;
		$process_data =  $result->VerifyPaymentResult->ProcessData;
		$card_issuer = $result->VerifyPaymentResult->CardType;
		
		if ($response_code == 0 && $status == 'Approved')
		{
			$result = $client->AcknowledgePayment($parameters);
			$response_code = $result->AcknowledgePaymentResult;
			
			if ($response_code == 0 && $auth_amount == $total_amount)
			{
				$this->session->set_flashdata('ref_no', $ref_no);
				$this->session->set_flashdata('auth_code', $auth_code);
				$this->session->set_flashdata('masked_account', $masked_account);
				$this->session->set_flashdata('card_issuer', $card_issuer);
				
				$info=$this->Customer->get_info($this->sale_lib->get_customer());
				
				//We want to save/update card:
				//1. User decides to save credit card info
				//2. We already have a saved credit and need to update
				if (($this->sale_lib->get_save_credit_card_info() or ($info->cc_token && $info->cc_preview)) && $this->sale_lib->get_customer() != -1)
				{
					$person_info = array('person_id' => $this->sale_lib->get_customer());
					$customer_info = array('cc_token' => $token, 'cc_preview' => $masked_account, 'card_issuer' => $card_issuer);
					$this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
				}
								
				//If the sale payments cover the total, redirect to complete (receipt)
				if ($this->_payments_cover_total())
				{
					redirect(site_url('sales/complete'));
				}
				else //Change payment type to Partial Credit Card and show sales interface
				{
					$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);
					
					$credit_card_amount = to_currency_no_money($this->sale_lib->get_payment_amount(lang('sales_credit')));
					
					$partial_transaction = array(
						'AuthCode' => $auth_code,
						'Frequency' => 'OneTime',
						'Memo' => 'PHP POS '.APPLICATION_VERSION,
						'Invoice' => $invoice_number,
						'MerchantID' => $this->Location->get_info_for_key('merchant_id'),
						'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
						'PurchaseAmount' => $credit_card_amount,
						'RefNo' => $ref_no,
						'Token' => $token,
						'AcqRefData' =>$acq_ref_data,
						'ProcessData' => $process_data,
					);
															
					$this->sale_lib->delete_payment($this->sale_lib->get_payment_ids(lang('sales_credit')));
					$this->sale_lib->add_payment(lang('sales_partial_credit'), $credit_card_amount, FALSE, $masked_account, $card_issuer);
					$this->sale_lib->add_partial_transaction($partial_transaction);
					$this->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);			
				}
			}
			elseif($response_code == 0 && $auth_amount < $total_amount)
			{
				$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);
				
				$partial_transaction = array(
					'AuthCode' => $auth_code,
					'Frequency' => 'OneTime',
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'Invoice' => $invoice_number,
					'MerchantID' => $this->Location->get_info_for_key('merchant_id'),
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
					'PurchaseAmount' => $auth_amount,
					'RefNo' => $ref_no,
					'Token' => $token,
					'AcqRefData' =>$acq_ref_data,
					'ProcessData' => $process_data,
				);
				
				$this->sale_lib->delete_payment($this->sale_lib->get_payment_ids(lang('sales_credit')));
				$this->sale_lib->add_payment(lang('sales_partial_credit'), $auth_amount, FALSE, $masked_account, $card_issuer);
				$this->sale_lib->add_partial_transaction($partial_transaction);
				$this->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);
			}
			else
			{
				$this->_reload(array('error' => lang('sales_acknowledge_payment_failed_please_contact_support')), false);
			}
		}
		else
		{
			$client->AcknowledgePayment($parameters);
			$this->_reload(array('error' => $result->VerifyPaymentResult->StatusMessage.': '.$result->VerifyPaymentResult->DisplayMessage), false);
		}		
	}
	
	function cancel_cc_processing()
	{
		$this->sale_lib->delete_payment($this->sale_lib->get_payment_ids(lang('sales_credit')));
		$this->_reload(array('error' => lang('sales_cc_processing_cancelled')), false);
	}
	
	function complete()
	{
		$data['is_sale'] = TRUE;
		$data['cart']=$this->sale_lib->get_cart();
		
		if (empty($data['cart']))
		{
			redirect('sales');
		}
			
		if (!$this->_payments_cover_total())
		{
			$this->_reload(array('error' => lang('sales_cannot_complete_sale_as_payments_do_not_cover_total')), false);
			return;
		}
		
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();		
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=lang('sales_receipt');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$data['comment'] = $this->sale_lib->get_comment();
		$data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
		$emp_info=$this->Employee->get_info($employee_id);
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['amount_change']=$this->sale_lib->get_amount_due() * -1;
		$data['balance']=$this->sale_lib->get_payment_amount(lang('sales_store_account'));
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		$data['ref_no'] = $this->session->flashdata('ref_no') ? $this->session->flashdata('ref_no') : '';
		$data['auth_code'] = $this->session->flashdata('auth_code') ? $this->session->flashdata('auth_code') : '';
		$data['discount_exists'] = $this->_does_discount_exists($data['cart']);
		$masked_account = $this->session->flashdata('masked_account') ? $this->session->flashdata('masked_account') : '';
		$card_issuer = $this->session->flashdata('card_issuer') ? $this->session->flashdata('card_issuer') : '';
				
		if ($masked_account && $card_issuer)
		{
			$cc_payment_id = current($this->sale_lib->get_payment_ids(lang('sales_credit')));
			$cc_payment = $data['payments'][$cc_payment_id];
			$this->sale_lib->edit_payment($cc_payment_id, $cc_payment['payment_type'], $cc_payment['payment_amount'],$cc_payment['payment_date'], $masked_account, $card_issuer);
			
			//Make sure our payments has the latest change to masked_account
			$data['payments'] = $this->sale_lib->get_payments();
		}
		
		$data['change_sale_date'] =$this->sale_lib->get_change_sale_date_enable() ?  $this->sale_lib->get_change_sale_date() : false;
		
		$old_date = $this->sale_lib->get_change_sale_id()  ? $this->Sale->get_info($this->sale_lib->get_change_sale_id())->row_array() : false;
		$old_date=  $old_date ? date(get_date_format().' '.get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format().' '.get_time_format());
		$data['transaction_time']= $this->sale_lib->get_change_sale_date_enable() ?  date(get_date_format().' '.get_time_format(), strtotime($this->sale_lib->get_change_sale_date())) : $old_date;
	
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
			$data['customer_address_1'] = $cust_info->address_1;
			$data['customer_address_2'] = $cust_info->address_2;
			$data['customer_city'] = $cust_info->city;
			$data['customer_state'] = $cust_info->state;
			$data['customer_zip'] = $cust_info->zip;
			$data['customer_country'] = $cust_info->country;
			$data['customer_phone'] = $cust_info->phone_number;
			$data['customer_email'] = $cust_info->email;			
		}
		
		$suspended_change_sale_id=$this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id() ;
		
		//If we have a previous sale make sure we get the ref_no unless we already have it set
		if ($suspended_change_sale_id && !$data['ref_no'])
		{
			$sale_info = $this->Sale->get_info($suspended_change_sale_id)->row_array();
			$data['ref_no'] = $sale_info['cc_ref_no'];
		}

		//If we have a previous sale make sure we get the auth_code unless we already have it set
		if ($suspended_change_sale_id && !$data['auth_code'])
		{
			$sale_info = $this->Sale->get_info($suspended_change_sale_id)->row_array();
			$data['auth_code'] = $sale_info['auth_code'];
		}
		
		//If we have a suspended sale, update the date for the sale
		if ($this->sale_lib->get_suspended_sale_id() && $this->config->item('change_sale_date_when_completing_suspended_sale'))
		{
			$data['change_sale_date'] = date('Y-m-d H:i:s');
		}
		
		$data['store_account_payment'] = $this->sale_lib->get_mode() == 'store_account_payment' ? 1 : 0;
		
		//SAVE sale to database
		$sale_id_raw = $this->Sale->save($data['cart'], $customer_id,$employee_id,$data['comment'],$data['show_comment_on_receipt'],$data['payments'], $suspended_change_sale_id, 0,$data['ref_no'],$data['auth_code'], $data['change_sale_date'], $data['balance'], $data['store_account_payment']); 
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id_raw;
		
		if($customer_id != -1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			
			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}
		
		//If we don't have any taxes, run a check for items so we don't show the price including tax on receipt
		if (empty($data['taxes']))
		{
			foreach(array_keys($data['cart']) as $key)
			{
				if (isset($data['cart'][$key]['item_id']))
				{
					$item_info = $this->Item->get_info($data['cart'][$key]['item_id']);
					if($item_info->tax_included)
					{
						$price_to_use = get_price_for_item_excluding_taxes($data['cart'][$key]['item_id'], $data['cart'][$key]['price']);
						$data['cart'][$key]['price'] = $price_to_use;
					}					
				}
				elseif (isset($data['cart'][$key]['item_kit_id']))
				{
					$item_info = $this->Item_kit->get_info($data['cart'][$key]['item_kit_id']);
					if($item_info->tax_included)
					{
						$price_to_use = get_price_for_item_kit_excluding_taxes($data['cart'][$key]['item_kit_id'], $data['cart'][$key]['price']);
						$data['cart'][$key]['price'] = $price_to_use;
					}					
				}
				
			}
			
		}
		
		if ($data['sale_id'] == $this->config->item('sale_prefix').' -1')
		{
			$data['error_message'] = '';
			if (is_sale_integrated_cc_processing())
			{
				$data['error_message'].=lang('sales_credit_card_transaction_completed_successfully').'. ';
			}
			$data['error_message'] .= lang('sales_transaction_failed');
		}
		else
		{			
			if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email))
			{
				$this->load->library('email');
				$config['mailtype'] = 'html';				
				$this->email->initialize($config);
				$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@phpsoftwares.com', $this->config->item('company'));
				$this->email->to($cust_info->email); 

				$this->email->subject(lang('sales_receipt'));
				$this->email->message($this->load->view("sales/receipt_email",$data, true));	
				$this->email->send();
			}
		}
		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();
		$this->removeDuplicateSessionCookieHeaders();
	}
	
	function email_receipt($sale_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();
		
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->copy_entire_sale($sale_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['subtotal']=$this->sale_lib->get_subtotal($sale_id);
		$data['taxes']=$this->sale_lib->get_taxes($sale_id);
		$data['total']=$this->sale_lib->get_total($sale_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due_round($sale_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		$data['ref_no'] = $sale_info['cc_ref_no'];
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
			$data['customer_address_1'] = $cust_info->address_1;
			$data['customer_address_2'] = $cust_info->address_2;
			$data['customer_city'] = $cust_info->city;
			$data['customer_state'] = $cust_info->state;
			$data['customer_zip'] = $cust_info->zip;
			$data['customer_country'] = $cust_info->country;
			$data['customer_phone'] = $cust_info->phone_number;
			$data['customer_email'] = $cust_info->email;
			
			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}
				
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		$data['store_account_payment'] = FALSE;
		
		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}
		
		if (!empty($cust_info->email))
		{
			$this->load->library('email');
			$config['mailtype'] = 'html';				
			$this->email->initialize($config);
			$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@phpsoftwares.com', $this->config->item('company'));
			$this->email->to($cust_info->email); 

			$this->email->subject(lang('sales_receipt'));
			$this->email->message($this->load->view("sales/receipt_email",$data, true));	
			$this->email->send();
		}

		$this->sale_lib->clear_all();
		
		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
		
		$this->removeDuplicateSessionCookieHeaders();
	}
	
	function receipt($sale_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();
		
		$data['is_sale'] = FALSE;
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['show_payment_times'] = TRUE;
		$data['subtotal']=$this->sale_lib->get_subtotal($sale_id);
		$data['taxes']=$this->sale_lib->get_taxes($sale_id);
		$data['total']=$this->sale_lib->get_total($sale_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['comment'] = $this->Sale->get_comment($sale_id);
		$data['show_comment_on_receipt'] = $this->Sale->get_comment_on_receipt($sale_id);
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due($sale_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		$data['ref_no'] = $sale_info['cc_ref_no'];
		$data['auth_code'] = $sale_info['auth_code'];
		$data['discount_exists'] = $this->_does_discount_exists($data['cart']);
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
			$data['customer_address_1'] = $cust_info->address_1;
			$data['customer_address_2'] = $cust_info->address_2;
			$data['customer_city'] = $cust_info->city;
			$data['customer_state'] = $cust_info->state;
			$data['customer_zip'] = $cust_info->zip;
			$data['customer_country'] = $cust_info->country;
			$data['customer_phone'] = $cust_info->phone_number;
			$data['customer_email'] = $cust_info->email;
			
			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}		
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		$data['store_account_payment'] = FALSE;
		
		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}
		
		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();
		
		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
		
		$this->removeDuplicateSessionCookieHeaders();

	}
	
	function _does_discount_exists($cart)
	{
		foreach($cart as $line=>$item)
		{
			if($item['discount']>0)
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	function edit($sale_id)
	{
		$data = array();

		$data['customers'] = array('' => 'No Customer');
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$data['customers'][$customer->person_id] = $customer->first_name . ' '. $customer->last_name;
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['sale_info'] = $this->Sale->get_info($sale_id)->row_array();
				
		
		$this->load->view('sales/edit', $data);
	}
	
	function delete($sale_id)
	{
		$this->check_action_permission('delete_sale');
		$data = array();
		
		if ($this->Sale->delete($sale_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('sales/delete', $data);
		
	}
	
	function undelete($sale_id)
	{
		$data = array();
		
		if ($this->Sale->undelete($sale_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('sales/undelete', $data);
		
	}
	
	function save($sale_id)
	{
		$sale_data = array(
			'sale_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'customer_id' => $this->input->post('customer_id') ? $this->input->post('customer_id') : null,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'show_comment_on_receipt' => $this->input->post('show_comment_on_receipt') ? 1 : 0
		);
		
		if ($this->Sale->update($sale_data, $sale_id))
		{
			echo json_encode(array('success'=>true,'message'=>lang('sales_successfully_updated')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('sales_unsuccessfully_updated')));
		}
	}
	
	function _payments_cover_total()
	{
		$total_payments = 0;

		foreach($this->sale_lib->get_payments() as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}

		/* Changed the conditional to account for floating point rounding */
		if ( ( $this->sale_lib->get_mode() == 'sale' || $this->sale_lib->get_mode() == 'store_account_payment' ) && ( ( to_currency_no_money( $this->sale_lib->get_total() ) - $total_payments ) > 1e-6 ) )
		{
			return false;
		}
		
		return true;
	}
	function reload()
	{
		$this->_reload();
	}
	
	function _reload($data=array(), $is_ajax = true)
	{		
		$person_info = $this->Employee->get_logged_in_employee_info();
		$modes = array('sale'=>lang('sales_sale'),'return'=>lang('sales_return'));
		
		if($this->config->item('customers_store_accounts')) 
		{
			$modes['store_account_payment'] = lang('sales_store_account_payment');
		}
		
		$data['cart']=$this->sale_lib->get_cart();
		$data['modes']= $modes;
		$data['mode']=$this->sale_lib->get_mode();
		$data['items_in_cart'] = $this->sale_lib->get_items_in_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_module_permission('items', $person_info->person_id);
		$data['comment'] = $this->sale_lib->get_comment();
		$data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
		$data['email_receipt'] = $this->sale_lib->get_email_receipt();
		$data['payments_total']=$this->sale_lib->get_payments_totals_excluding_store_account();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		$data['payments']=$this->sale_lib->get_payments();
		$data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
		$data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
		$data['selected_tier_id'] = $this->sale_lib->get_selected_tier_id();
		$tiers = array();

		$tiers[0] = lang('items_none');
		foreach($this->Tier->get_all()->result() as $tier)
		{
			$tiers[$tier->id]=$tier->name;
		}
		
		$data['tiers'] = $tiers;
		
		if ($this->Location->get_info_for_key('enable_credit_card_processing'))
		{
			$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_credit') => lang('sales_credit'),
				lang('sales_giftcard') => lang('sales_giftcard'));
				
				if($this->config->item('customers_store_accounts')) 
				{
					$data['payment_options']=array_merge($data['payment_options'],	array(lang('sales_store_account') => lang('sales_store_account')		
					));
				}
		}
		else
		{
			$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit')
				);
				
				if($this->config->item('customers_store_accounts') && $this->sale_lib->get_mode() != 'store_account_payment') 
				{
					$data['payment_options']=array_merge($data['payment_options'],	array(lang('sales_store_account') => lang('sales_store_account')		
					));
				}
		}
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}

		$customer_id=$this->sale_lib->get_customer();
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name.($info->company_name==''  ? '' :' ('.$info->company_name.')');
			$data['customer_email']=$info->email;
			$data['customer_balance'] = to_currency($info->balance);
			$data['customer_id']=$customer_id;
			$data['customer_cc_token'] = $info->cc_token;
			$data['customer_cc_preview'] = $info->cc_preview;
			$data['save_credit_card_info'] = $this->sale_lib->get_save_credit_card_info();
			$data['use_saved_cc_info'] = $this->sale_lib->get_use_saved_cc_info();
			$data['avatar']=$info->image_id ?  site_url('app_files/view/'.$info->image_id) : ""; //can be changed to  base_url()."/img/avatar.png" if it is required
			$data['recent_sales'] = $this->Sale->get_recent_sales_for_customer($customer_id);
		}
		$data['payments_cover_total'] = $this->_payments_cover_total();
		if ($is_ajax)
		{
			$this->load->view("sales/register",$data);
		}
		else
		{
			$this->load->view("sales/register_initial",$data);
		}
		
		$this->removeDuplicateSessionCookieHeaders();
	}

    function cancel_sale()
    {
		if (!$this->_void_partial_transactions())
		{
			$this->_reload(array('error' => lang('sales_attempted_to_reverse_partial_transactions_failed_please_contact_support')), true);
		}
		
    	$this->sale_lib->clear_all();
    	$this->_reload();

    }

	function _void_partial_transactions()
	{
		$void_success = true;
		
		if ($partial_transactions = $this->sale_lib->get_partial_transactions())
		{
			$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL': 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
			
			foreach($partial_transactions as $partial_transaction)
			{
				$parameters = array(
					'request' => $partial_transaction,
					'password' => $this->Location->get_info_for_key('merchant_password'),
				);
				
				$client = new SoapClient($service_url,array('trace' => TRUE));
				$result = $client->CreditReversalToken($parameters);
				
				$status = $result->CreditReversalTokenResult->Status;
				if ($status != 'Approved')
				{
					unset($parameters['AcqRefData']);
					unset($parameters['ProcessData']);
					$result = $client->CreditVoidSaleToken($parameters);
					$status = $result->CreditVoidSaleTokenResult->Status;
					
					if ($status != 'Approved')
					{
						$void_success = false;
					}
				}
			}
		}
		
		return $void_success;
	}
	
	function suspend()
	{
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format());
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->sale_lib->get_comment();
		$comment = $this->sale_lib->get_comment();
		$show_comment_on_receipt = $this->sale_lib->get_comment_on_receipt();
		$emp_info=$this->Employee->get_info($employee_id);
		//Alain Multiple payments
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=$this->sale_lib->get_amount_due() * -1;
		$data['balance']=$this->sale_lib->get_payment_amount(lang('sales_store_account'));
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
		}

		$total_payments = 0;

		foreach($data['payments'] as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}
		
		$sale_id = $this->sale_lib->get_suspended_sale_id();
		//SAVE sale to database
		$sale_id = $this->Sale->save($data['cart'], $customer_id,$employee_id,$comment,$show_comment_on_receipt,$data['payments'], $sale_id, 1,'','',$this->config->item('change_sale_date_when_suspending') ? date('Y-m-d H:i:s') : FALSE, $data['balance']);
		
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		if ($data['sale_id'] == $this->config->item('sale_prefix').' -1')
		{
			$data['error_message'] = lang('sales_transaction_failed');
		}
		$this->sale_lib->clear_all();
		
		if ($this->config->item('show_receipt_after_suspending_sale'))
		{
			redirect('sales/receipt/'.$sale_id);
		}
		else
		{
			$this->_reload(array('success' => lang('sales_successfully_suspended_sale')));
		}
	}
	
	
	function new_giftcard()
	{
		if (!$this->Employee->has_module_action_permission('giftcards', 'add_update', $this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
		
		$data = array();
		$data['item_id']=$this->Item->get_item_id(lang('sales_giftcard'));
		$this->load->view("sales/giftcard_form",$data);
	}
	
	function suspended()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale->get_all_suspended();
		$this->load->view('sales/suspended', $data);
	}
	
	function change_sale($sale_id)
	{
		$this->check_action_permission('edit_sale');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_change_sale_id($sale_id);
		
		if ($this->Location->get_info_for_key('enable_credit_card_processing'))
		{
			$this->sale_lib->change_credit_card_payments_to_partial();				
		}
    	$this->_reload(array(), false);
	}
		
	function unsuspend()
	{
		$sale_id = $this->input->post('suspended_sale_id');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_suspended_sale_id($sale_id);
    	$this->_reload(array(), false);
	}
	
	function delete_suspended_sale()
	{
		$this->check_action_permission('delete_suspended_sale');
		$suspended_sale_id = $this->input->post('suspended_sale_id');
		if ($suspended_sale_id)
		{
			$this->sale_lib->delete_suspended_sale_id();
			$this->Sale->delete($suspended_sale_id);
		}
    	redirect('sales/suspended');
	}
	
	function discount_all()
	{
		$discount_all_percent = (int)$this->input->post('discount_all_percent');
		$this->sale_lib->discount_all($discount_all_percent);
		$this->_reload();
	}
	
	function categories($offset = 0)
	{
		$categories = array();
		
		$item_categories = array();
		$item_categories_items_result = $this->Item->get_all_categories()->result();
		
		foreach($item_categories_items_result as $category)
		{
			if ($category->category != lang('sales_giftcard') && $category->category != lang('sales_store_account_payment'))
			{
				$item_categories[] = $category->category;				
			}
		}
		
		$item_kit_categories = array();
		$item_kit_categories_items_result = $this->Item_kit->get_all_categories()->result();
		
		foreach($item_kit_categories_items_result as $category)
		{
			$item_kit_categories[] = $category->category;
		}
		
		$categories = array_unique(array_merge($item_categories, $item_kit_categories));
		sort($categories);
		
		$categories_count = count($categories);		
		$config['base_url'] = site_url('sales/categories');
		$config['total_rows'] = $categories_count;
		$config['per_page'] = 15; 
		$this->pagination->initialize($config);

		$categories = array_slice($categories, $offset, $config['per_page']);

		$data = array();
		$data['categories'] = $categories;
		$data['pagination'] = $this->pagination->create_links();
		
		echo json_encode($data);	
	}
	
	function items($offset = 0)
	{
		$category = $this->input->post('category');
		
		$items = array();
		$items_result = $this->Item->get_all_by_category($category, $offset)->result();
		
		//print_r($items_result);
		foreach($items_result as $item)
		{
			$img_src = "";
			//echo $item->image_id ;
			if ($item->image_id != 'no_image' && trim($item->image_id) != '') {
				$img_src = site_url('app_files/view/'.$item->image_id);
			}
			
			$items[] = array(
				'id' => $item->item_id,
				'name' => character_limiter($item->name, 58),				
				'image_src' => 	$img_src			
			);	
		}
		$items_count = $this->Item->count_all_by_category($category);
		
		$config['base_url'] = site_url('sales/items');
		$config['total_rows'] = $items_count;
		$config['per_page'] = 14; 
		$this->pagination->initialize($config);
		
		//print_r($items);
		$data = array();
		$data['items'] = $items;
		$data['pagination'] = $this->pagination->create_links();
		
		echo json_encode($data);	
	}
}
?>