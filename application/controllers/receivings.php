<?php
require_once ("secure_area.php");
class Receivings extends Secure_area
{
	function __construct()
	{
		parent::__construct('receivings');
		$this->load->library('receiving_lib');
	}

	function index()
	{
		$this->_reload(array(), false);
	}

	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->get('term'),100));
		echo json_encode($suggestions);
	}

	function supplier_search()
	{
		$suggestions = $this->Supplier->get_suppliers_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function select_supplier()
	{
		$data = array();
		$supplier_id = $this->input->post("supplier");
		
		if ($this->Supplier->exists($supplier_id))
		{
			$this->receiving_lib->set_supplier($supplier_id);
		}
		else
		{
			$data['error']=lang('receivings_unable_to_add_supplier');
		}
		$this->_reload($data);
	}

	function location_search()
	{
		$suggestions = $this->Location->get_locations_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function select_location()
	{
		$data = array();
		$location_id = $this->input->post("location");
		
		if ($this->Location->exists($location_id))
		{
			$this->receiving_lib->set_location($location_id);
		}
		else
		{
			$data['error']=lang('receivings_unable_to_add_location');
		}
		$this->_reload($data);
	}

	function delete_location()
	{
		$this->receiving_lib->delete_location();
		$this->_reload();
	}


	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->receiving_lib->set_mode($mode);
		$this->_reload();
	}

	function add()
	{
		$data=array();
		$mode = $this->receiving_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		$quantity = $mode=="receive" ? 1:-1;

		if($this->receiving_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$this->receiving_lib->return_entire_receiving($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->receiving_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->receiving_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif(!$this->receiving_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=lang('receivings_unable_to_add_item');
		}
		$this->_reload($data);
	}

	function edit_item($item_id)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'numeric');
		$this->form_validation->set_rules('discount', 'lang:items_discount', 'numeric');

    	$description = $this->input->post("description");
    	$serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");

		if ($this->form_validation->run() != FALSE)
		{
			$this->receiving_lib->edit_item($item_id,$description,$serialnumber,$quantity,$discount,$price);
		}
		else
		{
			$data['error']=lang('receivings_error_editing_item');
		}

		$this->_reload($data);
	}

	function delete_item($item_number)
	{
		$this->receiving_lib->delete_item($item_number);
		$this->_reload();
	}

	function delete_supplier()
	{
		$this->receiving_lib->delete_supplier();
		$this->_reload();
	}

	function complete()
	{
		$data['cart']=$this->receiving_lib->get_cart();
		if (empty($data['cart']))
		{
			redirect('receivings');
		}
		
		$data['total']=$this->receiving_lib->get_total();
		$data['receipt_title']=lang('receivings_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format());
		$supplier_id=$this->receiving_lib->get_supplier();
		$location_id=$this->receiving_lib->get_location();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->input->post('comment');
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = $this->input->post('payment_type');
		$data['payment_type']=$this->input->post('payment_type');
		$data['mode']=$this->receiving_lib->get_mode();

		if ($this->input->post('amount_tendered'))
		{
			$data['amount_tendered'] = $this->input->post('amount_tendered');
			$data['amount_change'] = to_currency($data['amount_tendered'] - round($data['total'], 2));
		}
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($supplier_id!=-1)
		{	
			$suppl_info=$this->Supplier->get_info($supplier_id);		
			$data['supplier']=$suppl_info->company_name;
			if ($suppl_info->first_name || $suppl_info->last_name)
			{
				$data['supplier'] .= ' ('.$suppl_info->first_name.' '.$suppl_info->last_name.')';
			}
			
		}

		//SAVE receiving to database
		$data['receiving_id']='RECV '.$this->Receiving->save($data['cart'], $supplier_id,$employee_id,$comment,$payment_type,false,$data['mode'],$location_id);
		
		if ($data['receiving_id'] == 'RECV -1')
		{
			$data['error_message'] = lang('receivings_transaction_failed');
		}

		$this->load->view("receivings/receipt",$data);
		$this->receiving_lib->clear_all();
		$this->removeDuplicateSessionCookieHeaders();
	}

	function receipt($receiving_id)
	{
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		$this->receiving_lib->copy_entire_receiving($receiving_id);
		$data['cart']=$this->receiving_lib->get_cart();
		$data['total']=$this->receiving_lib->get_total();
		$data['receipt_title']=lang('receivings_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($receiving_info['receiving_time']));
		$supplier_id=$this->receiving_lib->get_supplier();
		$emp_info=$this->Employee->get_info($receiving_info['employee_id']);
		$data['payment_type']=$receiving_info['payment_type'];

		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($supplier_id!=-1)
		{
			$supplier_info=$this->Supplier->get_info($supplier_id);
						
			$data['supplier']=$supplier_info->company_name;
			if ($supplier_info->first_name || $supplier_info->last_name)
			{
				$data['supplier'] .= ' ('.$supplier_info->first_name.' '.$supplier_info->last_name.')';
			}
		}
		$data['receiving_id']='RECV '.$receiving_id;
		$this->load->view("receivings/receipt",$data);
		$this->receiving_lib->clear_all();
		$this->removeDuplicateSessionCookieHeaders();

	}
	
	function edit($receiving_id)
	{
		$data = array();

		$data['suppliers'] = array('' => 'No Supplier');
		foreach ($this->Supplier->get_all()->result() as $supplier)
		{
			$data['suppliers'][$supplier->person_id] = $supplier->company_name.' ('.$supplier->first_name . ' '. $supplier->last_name.')';
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['receiving_info'] = $this->Receiving->get_info($receiving_id)->row_array();
		$this->load->view('receivings/edit', $data);
	}
	
	function delete($receiving_id)
	{
		$data = array();
		
		if ($this->Receiving->delete($receiving_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('receivings/delete', $data);
		
	}
	
	function undelete($receiving_id)
	{
		$data = array();
		
		if ($this->Receiving->undelete($receiving_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('receivings/undelete', $data);
		
	}
	
	function save($receiving_id)
	{
		$receiving_data = array(
			'receiving_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'supplier_id' => $this->input->post('supplier_id') ? $this->input->post('supplier_id') : null,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment')
		);
		
		if ($this->Receiving->update($receiving_data, $receiving_id))
		{
			echo json_encode(array('success'=>true,'message'=>lang('receivings_successfully_updated')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('receivings_unsuccessfully_updated')));
		}
	}

	function _reload($data=array(), $is_ajax = true)
	{
		$person_info = $this->Employee->get_logged_in_employee_info();
		$data['cart']=$this->receiving_lib->get_cart();
		$data['modes']=array('receive'=>lang('receivings_receiving'),'return'=>lang('receivings_return'));
		
		if ($this->Location->count_all() > 1)
		{
			$data['modes']['transfer']= lang('receivings_transfer');
		}
		$data['mode']=$this->receiving_lib->get_mode();
		$data['total']=$this->receiving_lib->get_total();
		$data['items_in_cart'] = $this->receiving_lib->get_items_in_cart();
		$data['items_module_allowed'] = $this->Employee->has_module_permission('items', $person_info->person_id);
		$data['payment_options']=array(
			lang('sales_cash') => lang('sales_cash'),
			lang('sales_check') => lang('sales_check'),
			lang('sales_debit') => lang('sales_debit'),
			lang('sales_credit') => lang('sales_credit')
		);
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}
		
		$supplier_id=$this->receiving_lib->get_supplier();
		if($supplier_id!=-1)
		{
			$info=$this->Supplier->get_info($supplier_id);
			$data['supplier']=$info->company_name;
			if ($info->first_name || $info->last_name)
			{
				$data['supplier'] .= ' ('.$info->first_name.' '.$info->last_name.')';
			}
			
			$data['supplier_id']=$supplier_id;
		}

		$location_id=$this->receiving_lib->get_location();
		if($location_id!=-1)
		{
			$info=$this->Location->get_info($location_id);
			$data['location']=$info->name;
			$data['location_id']=$location_id;
		}
		
		if ($is_ajax)
		{
			$this->load->view("receivings/receiving",$data);
		}
		else
		{
			$this->load->view("receivings/receiving_initial",$data);
		}
		
		$this->removeDuplicateSessionCookieHeaders();
	}

    function cancel_receiving()
    {
    	$this->receiving_lib->clear_all();
    	$this->_reload();
    }

}
?>