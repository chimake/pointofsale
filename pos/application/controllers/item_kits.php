<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Item_kits extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('item_kits');
	}

	function index($offset=0)
	{
		$params = $this->session->userdata('item_kit_search_data') ? $this->session->userdata('item_kit_search_data') : array('offset' => 0, 'order_col' => 'name', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('item_kits/index/'.$params['offset']);
		}
		$this->check_action_permission('search');
		$config['base_url'] = site_url('item_kits/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Item_kit->search_count_all($data['search']);
			$table_data = $this->Item_kit->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Item_kit->count_all();
			$table_data = $this->Item_kit->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		$data['total_rows'] = $config['total_rows'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		$data['manage_table']=get_item_kits_manage_table($table_data,$this);
		$this->load->view('item_kits/manage',$data);
	}
	
	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$item_kit_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("item_kit_search_data",$item_kit_search_data);
		if ($search)
		{
			$config['total_rows'] = $this->Item_kit->search_count_all($search);
			$table_data = $this->Item_kit->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Item_kit->count_all();
			$table_data = $this->Item_kit->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('item_kits/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_Item_kits_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
	}
	

	/* added for excel expert */
	function excel_export() {
		$data = $this->Item_kit->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang('items_item_number'),lang('items_product_id'), lang('item_kits_name'),lang('items_category'),lang('items_supplier_id'),lang('items_cost_price'),lang('items_unit_price'),lang('items_tax_1_name'),lang('items_tax_1_percent'),lang('items_tax_2_name'),lang('items_tax_2_percent'),lang('items_tax_2_cummulative'),lang('item_kits_description'));
		
		$rows[] = $row;
		
		foreach ($data as $r) {
			$taxdata = $this->Item_kit_taxes_finder->get_info($r->item_kit_id);
			if (sizeof($taxdata) >= 2) {
				$r->taxn = $taxdata[0]['name'];
				$r->taxp = $taxdata[0]['percent'];
				$r->taxn1 = $taxdata[1]['name'];
				$r->taxp1 = $taxdata[1]['percent'];
				$r->cumulative = $taxdata[1]['cumulative'] ? 'y' : '';
			} else if (sizeof($taxdata) == 1) {
				$r->taxn = $taxdata[0]['name'];
				$r->taxp = $taxdata[0]['percent'];
				$r->taxn1 = '';
				$r->taxp1 = '';
				$r->cumulative = '';
			} else {
				$r->taxn = '';
				$r->taxp = '';
				$r->taxn1 = '';
				$r->taxp1 = '';
				$r->cumulative = '';
			}
			
			$row = array(
				$r->item_kit_number,
				$r->product_id,
				$r->name,
				$r->category,
				$r->cost_price,
				$r->unit_price,
				$r->taxn,
				$r->taxp,
				$r->taxn1,
				$r->taxp1,
				$r->cumulative,
				$r->description
			);
			
			$rows[] = $row;		
		}
		
		$content = array_to_csv($rows);
		force_download('itemkits_export' . '.csv', $content);
		exit;
	}

	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$item_kit_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("item_kit_search_data",$item_kit_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Item_kit->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('item_kits/search');
		$config['total_rows'] = $this->Item_kit->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_Item_kits_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Item_kit->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function get_row()
	{
		$item_kit_id = $this->input->post('row_id');
		$data_row=get_item_kit_data_row($this->Item_kit->get_info($item_kit_id),$this);
		echo $data_row;
	}
	
	function check_duplicate()
	{
		echo json_encode(array('duplicate'=>$this->Item_kit->check_duplicate($this->input->post('term'))));

	}
	
	function view($item_kit_id=-1,$redirect=0)
	{
		$this->check_action_permission('add_update');	
		$data['controller_name']=strtolower(get_class());
		$data['item_kit_info']=$this->Item_kit->get_info($item_kit_id);
		$data['item_kit_tax_info']=$this->Item_kit_taxes->get_info($item_kit_id);
		$data['tiers']=$this->Tier->get_all()->result();		
		
		$data['tier_prices'] = array();
		$data['tier_type_options'] = array('unit_price' => lang('items_fixed_price'), 'percent_off' => lang('items_percent_off'));
		$data['redirect']=$redirect;
		
		
		foreach($this->Location->get_all()->result() as $location)
		{
			if($this->Employee->is_location_authenticated($location->location_id))
			{				
				$data['locations'][] = $location;
				$data['location_item_kits'][$location->location_id] = $this->Item_kit_location->get_info($item_kit_id,$location->location_id);
				$data['location_taxes'][$location->location_id] = $this->Item_kit_location_taxes->get_info($item_kit_id, $location->location_id);
								
				foreach($data['tiers'] as $tier)
				{					
					$tier_prices = $this->Item_kit_location->get_tier_price_row($tier->id,$data['item_kit_info']->item_kit_id, $location->location_id);
					if (!empty($tier_prices))
					{
						$data['location_tier_prices'][$location->location_id][$tier->id] = $tier_prices;
					}
					else
					{
						$data['location_tier_prices'][$location->location_id][$tier->id] = FALSE;			
					}
				}
			}
			
		}
		
		foreach($data['tiers'] as $tier)
		{
			$tier_prices = $this->Item_kit->get_tier_price_row($tier->id,$data['item_kit_info']->item_kit_id);
			
			if (!empty($tier_prices))
			{
				$data['tier_prices'][$tier->id] = $tier_prices;
			}
			else
			{
				$data['tier_prices'][$tier->id] = FALSE;			
			}
		}
		$this->load->view("item_kits/form",$data);
	}
	
	
	// Function to show the modal window when clicked on kit name
	function view_modal($item_kit_id)
	{
		// Fetching Kit information using kit_id
		$data['item_kit_info']=$this->Item_kit->get_info($item_kit_id);
		
		$this->load->view("item_kits/items_modal",$data);
	}
	
	function save($item_kit_id=-1)
	{
		$this->check_action_permission('add_update');		
		$item_kit_data = array(
		'item_kit_number'=>$this->input->post('item_kit_number')=='' ? null:$this->input->post('item_kit_number'),
		'product_id'=>$this->input->post('product_id')=='' ? null:$this->input->post('product_id'),
		'name'=>$this->input->post('name'),
		'category'=>$this->input->post('category'),
		'tax_included'=>$this->input->post('tax_included') ? $this->input->post('tax_included') : 0,
		'unit_price'=>$this->input->post('unit_price')=='' ? null:$this->input->post('unit_price'),
		'cost_price'=>$this->input->post('cost_price')=='' ? null:$this->input->post('cost_price'),
		'description'=>$this->input->post('description'),
		'override_default_tax'=> $this->input->post('override_default_tax') ? $this->input->post('override_default_tax') : 0,
		);
		
		$redirect=$this->input->post('redirect');
		
		if($this->Item_kit->save($item_kit_data,$item_kit_id))
		{
			$tier_type = $this->input->post('tier_type');
			
			if ($this->input->post('item_kit_tier'))
			{
				foreach($this->input->post('item_kit_tier') as $tier_id => $price_or_percent)
				{
					if ($price_or_percent)
					{				
						$tier_data=array('tier_id'=>$tier_id);
						$tier_data['item_kit_id'] = isset($item_kit_data['item_kit_id']) ? $item_kit_data['item_kit_id'] : $item_kit_id;

						if ($tier_type[$tier_id] == 'unit_price')
						{
							$tier_data['unit_price'] = $price_or_percent;
							$tier_data['percent_off'] = NULL;
						}
						else
						{
							$tier_data['percent_off'] = (int)$price_or_percent;
							$tier_data['unit_price'] = NULL;
						}
					
						$this->Item_kit->save_item_tiers($tier_data,$item_kit_id);
					}
					else
					{
						$this->Item->delete_tier_price($tier_id, $item_kit_id);
					}
				}
			}

			//New item kit
			if($item_kit_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_adding').' '.
				$item_kit_data['name'],'item_kit_id'=>$item_kit_data['item_kit_id']));
				$item_kit_id = $item_kit_data['item_kit_id'];
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_updating').' '.
				$item_kit_data['name'],'item_kit_id'=>$item_kit_id,'redirect'=>$redirect));
			}
			
			
			if ($this->input->post('locations'))
			{
				foreach($this->input->post('locations') as $location_id => $item_kit_location_data)
				{		        
					$override_prices = isset($item_kit_location_data['override_prices']) && $item_kit_location_data['override_prices'];
				
					$data = array(
						'location_id' => $location_id,
						'item_kit_id' => $item_kit_id,
						'cost_price' => $override_prices && $item_kit_location_data['cost_price'] != '' ? $item_kit_location_data['cost_price'] : NULL,
						'unit_price' => $override_prices && $item_kit_location_data['unit_price'] != '' ? $item_kit_location_data['unit_price'] : NULL,
						'override_default_tax'=> isset($item_kit_location_data['override_default_tax'] ) && $item_kit_location_data['override_default_tax'] != '' ? $item_kit_location_data['override_default_tax'] : 0,
					);
					$this->Item_kit_location->save($data, $item_kit_id,$location_id);
					

					if (isset($item_kit_location_data['item_tier']))
					{
						$tier_type = $item_kit_location_data['tier_type'];

						foreach($item_kit_location_data['item_tier'] as $tier_id => $price_or_percent)
						{
							//If we are overriding prices and we have a price/percent, add..otherwise delete
							if ($override_prices && $price_or_percent)
							{				
								$tier_data=array('tier_id'=>$tier_id);
								$tier_data['item_kit_id'] = isset($item_data['item_kit_id']) ? $item_data['item_kit_id'] : $item_kit_id;
								$tier_data['location_id'] = $location_id;
							
								if ($tier_type[$tier_id] == 'unit_price')
								{
									$tier_data['unit_price'] = $price_or_percent;
									$tier_data['percent_off'] = NULL;
								}
								else
								{
									$tier_data['percent_off'] = (int)$price_or_percent;
									$tier_data['unit_price'] = NULL;
								}

								$this->Item_kit_location->save_item_tiers($tier_data,$item_kit_id, $location_id);
							}
							else
							{
								$this->Item_kit_location->delete_tier_price($tier_id, $item_kit_id, $location_id);
							}

						}
					}
									
					$location_items_taxes_data = array();
				
					$tax_names = $item_kit_location_data['tax_names'];
					$tax_percents = $item_kit_location_data['tax_percents'];
					$tax_cumulatives = $item_kit_location_data['tax_cumulatives'];
					for($k=0;$k<count($tax_percents);$k++)
					{
						if (is_numeric($tax_percents[$k]))
						{
							$location_items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
						}
					}
					$this->Item_kit_location_taxes->save($location_items_taxes_data, $item_kit_id, $location_id);
				}
			}
			
			if ($this->input->post('item_kit_item'))
			{
				$item_kit_items = array();
				foreach($this->input->post('item_kit_item') as $item_id => $quantity)
				{
					$item_kit_items[] = array(
						'item_id' => $item_id,
						'quantity' => $quantity
						);
				}
			
				$this->Item_kit_items->save($item_kit_items, $item_kit_id);
			}
			
			$item_kits_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$tax_cumulatives = $this->input->post('tax_cumulatives');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$item_kits_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
				}
			}
			$this->Item_kit_taxes->save($item_kits_taxes_data, $item_kit_id);
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('item_kits_error_adding_updating').' '.
			$item_kit_data['name'],'item_kit_id'=>-1));
		}

	}
	
	function delete()
	{
		$this->check_action_permission('delete');		
		$item_kits_to_delete=$this->input->post('ids');

		if($this->Item_kit->delete_list($item_kits_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_deleted').' '.
			count($item_kits_to_delete).' '.lang('item_kits_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('item_kits_cannot_be_deleted')));
		}
	}

	function clear_state()
	{
		$this->session->unset_userdata('item_kit_search_data');
		redirect('item_kits');
	}

	function generate_barcodes($item_kit_ids)
	{
		$data['items'] = get_item_kits_barcode_data($item_kit_ids);
		$data['scale'] = 2;
		$this->load->view("barcode_sheet", $data);
	}
	
	function generate_barcode_labels($item_kit_ids)
	{
		$data['items'] = get_item_kits_barcode_data($item_kit_ids);
		$data['scale'] = 1;
		$this->load->view("barcode_labels", $data);
	}
	
}
?>