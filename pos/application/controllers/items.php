<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Items extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('items');
	}
	
	//change text to check line endings
	//new line endings

	function index($offset=0)
	{	
		$params = $this->session->userdata('item_search_data') ? $this->session->userdata('item_search_data') : array('offset' => 0, 'order_col' => 'item_id', 'order_dir' => 'asc', 'search' => FALSE, 'category' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('items/index/'.$params['offset']);
		}

		$this->check_action_permission('search');
		$config['base_url'] = site_url('items/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		$data['category'] = $params['category'] ? $params['category'] : "";
		
		if ($data['search'] || $data['category'])
		{

			$config['total_rows'] = $this->Item->search_count_all($data['search'], $data['category']);
			$table_data = $this->Item->search($data['search'],$data['category'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Item->count_all();
			$table_data = $this->Item->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}

		$data['total_rows'] = $config['total_rows'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		
		
		
		$data['manage_table']=get_items_manage_table($table_data,$this);
		$data['categories'][''] = '--'.lang('items_select_category_or_all').'--';
		foreach($this->Item->get_all_categories()->result() as $category)
		{
			$category = $category->category;
			$data['categories'][$category] = $category;
		}
		
		$this->load->view('items/manage',$data);
	}
	

	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$category = $this->input->post('category');
		
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'category' => $category);
		$this->session->set_userdata("item_search_data",$item_search_data);
		if ($search || $category)
		{
			$config['total_rows'] = $this->Item->search_count_all($search, $category);
			$table_data = $this->Item->search($search,$category, $per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Item->count_all();
			$table_data = $this->Item->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('items/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_items_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
	}

	
	function find_item_info()
	{
		$item_number=$this->input->post('scan_item_number');
		echo json_encode($this->Item->find_item_info($item_number));
	}
		
	function item_number_exists()
	{
		if($this->Item->account_number_exists($this->input->post('item_number')))
		echo 'false';
		else
		echo 'true';
		
	}
	
	function check_duplicate()
	{
		echo json_encode(array('duplicate'=>$this->Item->check_duplicate($this->input->post('term'))));
	}
		
	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$category = $this->input->post('category');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';
		
		$item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search,  'category' => $category);
		$this->session->set_userdata("item_search_data",$item_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Item->search($search, $category, $per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('items/search');
		$config['total_rows'] = $this->Item->search_count_all($search, $category);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_items_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest_category()
	{
		$suggestions = $this->Item->get_category_suggestions($this->input->get('term'));
		echo json_encode($suggestions);
	}

	function get_row()
	{
		$item_id = $this->input->post('row_id');
		$data_row=get_item_data_row($this->Item->get_info($item_id),$this);
		echo $data_row;
	}

	function get_info($item_id=-1)
	{
		echo json_encode($this->Item->get_info($item_id));
	}

	function view($item_id=-1,$redirect=0, $sale_or_receiving = 'sale')
	{
		$this->check_action_permission('add_update');
      $this->load->helper('report');
		$data = array();
		$data['controller_name']=strtolower(get_class());

		$data['item_info']=$this->Item->get_info($item_id);
		$data['item_tax_info']=$this->Item_taxes->get_info($item_id);
		$data['tiers']=$this->Tier->get_all()->result();
		$data['locations'] = array();
		$data['location_tier_prices'] = array();
		
		if ($item_id != -1)
		{
			$data['next_item_id'] = $this->Item->get_next_id($item_id);
			$data['prev_item_id'] = $this->Item->get_prev_id($item_id);;
		}
			
		foreach($this->Location->get_all()->result() as $location)
		{
			if($this->Employee->is_location_authenticated($location->location_id))
			{				
				$data['locations'][] = $location;
				$data['location_items'][$location->location_id] = $this->Item_location->get_info($item_id,$location->location_id);
				$data['location_taxes'][$location->location_id] = $this->Item_location_taxes->get_info($item_id, $location->location_id);
								
				foreach($data['tiers'] as $tier)
				{					
					$tier_prices = $this->Item_location->get_tier_price_row($tier->id,$data['item_info']->item_id, $location->location_id);
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
				
		$suppliers = array('' => lang('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
		}
		$data['redirect']=$redirect;
		$data['sale_or_receiving']=$sale_or_receiving;
		
		$data['tier_prices'] = array();
		$data['tier_type_options'] = array('unit_price' => lang('items_fixed_price'), 'percent_off' => lang('items_percent_off'));
		foreach($data['tiers'] as $tier)
		{
			$tier_prices = $this->Item->get_tier_price_row($tier->id,$data['item_info']->item_id);
			
			if (!empty($tier_prices))
			{
				$data['tier_prices'][$tier->id] = $tier_prices;
			}
			else
			{
				$data['tier_prices'][$tier->id] = FALSE;			
			}
		}

		$data['suppliers']=$suppliers;
		$data['selected_supplier'] = $this->Item->get_info($item_id)->supplier_id;
		$this->load->view("items/form",$data);
	}

	function view_modal($item_id)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$data['item_location_info']=$this->Item_location->get_info($item_id);
		$data['item_tax_info']=$this->Item_taxes_finder->get_info($item_id);
		$data['reorder_level'] = ($data['item_location_info'] && $data['item_location_info']->reorder_level) ? $data['item_location_info']->reorder_level : $data['item_info']->reorder_level;
		
		$suppliers = array('' => lang('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
		}

		if ($supplier_id = $this->Item->get_info($item_id)->supplier_id)
		{
			$supplier = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier->company_name . ' ('.$supplier->first_name.' '.$supplier->last_name.')';
		}
		
		$this->load->view("items/items_modal",$data);
	}

	function inventory($item_id=-1)
	{
		$this->check_action_permission('add_update');
		$data['item_info']=$this->Item->get_info($item_id);
		$data['item_location_info']=$this->Item_location->get_info($item_id);
		$this->load->view("items/inventory",$data);
	}

	function generate_barcodes($item_ids)
	{
		$data['items'] = get_items_barcode_data($item_ids);
		$data['scale'] = 2;
		$this->load->view("barcode_sheet", $data);
	}

	function generate_barcode_labels($item_ids)
	{
		$data['items'] = get_items_barcode_data($item_ids);
		$data['scale'] = 1;
		$this->load->view("barcode_labels", $data);
	}

	function bulk_edit()
	{
		$this->check_action_permission('add_update');		
		$this->load->helper('report');
        $data = array();
		
		$suppliers = array('' => lang('items_do_nothing'), '-1' => lang('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name']. ' ('.$row['first_name'] .' '. $row['last_name'].')';
		}
		$data['suppliers'] = $suppliers;
		
		$data['override_default_tax_choices'] = array(
			''=>lang('items_do_nothing'), 
			'0' => lang('common_no'), 
			'1' => lang('common_yes'));
			
		$data['allow_alt_desciption_choices'] = array(
			''=>lang('items_do_nothing'),
			1 =>lang('items_change_all_to_allow_alt_desc'),
			0 =>lang('items_change_all_to_not_allow_allow_desc'));
	 
       
		$data['serialization_choices'] = array(
			''=>lang('items_do_nothing'),
			1 =>lang('items_change_all_to_serialized'),
			0 =>lang('items_change_all_to_unserialized'));

		$data['tax_included_choices'] = array(
				''=>lang('items_do_nothing'),
				'0' => lang('common_no'), 
				'1' => lang('common_yes'));
			
		$data['is_service_choices'] = array(
			''=>lang('items_do_nothing'),
			'0' => lang('common_no'), 
			'1' => lang('common_yes'));
			
			
		$this->load->view("items/form_bulk", $data);
	}

	function save($item_id=-1)
	{
		$this->check_action_permission('add_update');		
		$item_data = array(
		'name'=>$this->input->post('name'),
		'description'=>$this->input->post('description'),
		'tax_included'=>$this->input->post('tax_included') ? $this->input->post('tax_included') : 0,
		'category'=>$this->input->post('category'),
		'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
		'item_number'=>$this->input->post('item_number')=='' ? null:$this->input->post('item_number'),
		'product_id'=>$this->input->post('product_id')=='' ? null:$this->input->post('product_id'),
		'cost_price'=>$this->input->post('cost_price'),
		'unit_price'=>$this->input->post('unit_price'),
		'promo_price'=>$this->input->post('promo_price') ? $this->input->post('promo_price') : NULL,
		'start_date'=>$this->input->post('start_date') ? date('Y-m-d', strtotime($this->input->post('start_date'))) : NULL,
		'end_date'=>$this->input->post('end_date') ?date('Y-m-d', strtotime($this->input->post('end_date'))) : NULL,
		'reorder_level'=>$this->input->post('reorder_level')!='' ? $this->input->post('reorder_level') : NULL,
		'is_service'=>$this->input->post('is_service') ? $this->input->post('is_service') : 0 ,
		'allow_alt_description'=>$this->input->post('allow_alt_description') ? $this->input->post('allow_alt_description') : 0 ,
		'is_serialized'=>$this->input->post('is_serialized') ? $this->input->post('is_serialized') : 0,
		'override_default_tax'=> $this->input->post('override_default_tax') ? $this->input->post('override_default_tax') : 0,
		);
		
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);

		$redirect=$this->input->post('redirect');
		$sale_or_receiving=$this->input->post('sale_or_receiving');

		if($this->Item->save($item_data,$item_id))
		{
			$tier_type = $this->input->post('tier_type');
			
			if ($this->input->post('item_tier'))
			{
				foreach($this->input->post('item_tier') as $tier_id => $price_or_percent)
				{
					if ($price_or_percent)
					{				
						$tier_data=array('tier_id'=>$tier_id);
						$tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;

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
					
						$this->Item->save_item_tiers($tier_data,$item_id);
					}
					else
					{
						$this->Item->delete_tier_price($tier_id, $item_id);
					}
				
				}
			}
			//New item
			if($item_id==-1)
			{				
				echo json_encode(array('success'=>true,'message'=>lang('items_successful_adding').' '.
				$item_data['name'],'item_id'=>$item_data['item_id'],'redirect' => $redirect, 'sale_or_receiving'=>$sale_or_receiving));
				$item_id = $item_data['item_id'];
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>lang('items_successful_updating').' '.
				$item_data['name'],'item_id'=>$item_id,'redirect' => $redirect, 'sale_or_receiving'=>$sale_or_receiving));
			}
			
			if ($this->input->post('locations'))
			{
				foreach($this->input->post('locations') as $location_id => $item_location_data)
				{		        
					$override_prices = isset($item_location_data['override_prices']) && $item_location_data['override_prices'];
				
					$item_location_before_save = $this->Item_location->get_info($item_id,$location_id);
					$data = array(
						'location_id' => $location_id,
						'item_id' => $item_id,
						'location' => $item_location_data['location'],
						'cost_price' => $override_prices && $item_location_data['cost_price'] != '' ? $item_location_data['cost_price'] : NULL,
						'unit_price' => $override_prices && $item_location_data['unit_price'] != '' ? $item_location_data['unit_price'] : NULL,
						'promo_price' => $override_prices && $item_location_data['promo_price'] != '' ? $item_location_data['promo_price'] : NULL,
						'start_date' => $override_prices && $item_location_data['promo_price']!='' && $item_location_data['start_date'] != '' ? date('Y-m-d', strtotime($item_location_data['start_date'])) : NULL,
						'end_date' => $override_prices && $item_location_data['promo_price'] != '' && $item_location_data['end_date'] != '' ? date('Y-m-d', strtotime($item_location_data['end_date'])) : NULL,
						'quantity' => $item_location_data['quantity'] != '' && !$this->input->post('is_service')  ? $item_location_data['quantity'] : NULL,
						'reorder_level' => isset($item_location_data['reorder_level']) && $item_location_data['reorder_level'] != '' && $item_location_data['reorder_level']!=$this->input->post('reorder_level') ? $item_location_data['reorder_level'] : NULL,
						'override_default_tax'=> isset($item_location_data['override_default_tax'] ) && $item_location_data['override_default_tax'] != '' ? $item_location_data['override_default_tax'] : 0,
					);
					$this->Item_location->save($data, $item_id,$location_id);
					

					if (isset($item_location_data['item_tier']))
					{
						$tier_type = $item_location_data['tier_type'];

						foreach($item_location_data['item_tier'] as $tier_id => $price_or_percent)
						{
							//If we are overriding prices and we have a price/percent, add..otherwise delete
							if ($override_prices && $price_or_percent)
							{				
								$tier_data=array('tier_id'=>$tier_id);
								$tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;
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

								$this->Item_location->save_item_tiers($tier_data,$item_id, $location_id);
							}
							else
							{
								$this->Item_location->delete_tier_price($tier_id, $item_id, $location_id);
							}

						}
					}
									
				
					if (isset($item_location_data['tax_names']))
					{
						$location_items_taxes_data = array();
						$tax_names = $item_location_data['tax_names'];
						$tax_percents = $item_location_data['tax_percents'];
						$tax_cumulatives = $item_location_data['tax_cumulatives'];
						for($k=0;$k<count($tax_percents);$k++)
						{
							if (is_numeric($tax_percents[$k]))
							{
								$location_items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
							}
						}
						$this->Item_location_taxes->save($location_items_taxes_data, $item_id, $location_id);
					}
					
					if ($item_location_data['quantity'] != '' && !$this->input->post('is_service'))
					{
						$inv_data = array
							(
							'trans_date'=>date('Y-m-d H:i:s'),
							'trans_items'=>$item_id,
							'trans_user'=>$employee_id,
							'trans_comment'=>lang('items_manually_editing_of_quantity'),
							'trans_inventory'=>$item_location_data['quantity'] - $item_location_before_save->quantity,
							'location_id' => $location_id,
						);
						$this->Inventory->insert($inv_data);
					}
				}
			}
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$tax_cumulatives = $this->input->post('tax_cumulatives');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
				}
			}
			$this->Item_taxes->save($items_taxes_data, $item_id);
			
			
			//Delete Image
			if($this->input->post('del_image') && $item_id != -1)
			{
			    if($cur_item_info->image_id != null)
			    {
					$this->Item->update_image(NULL,$item_id);
					$this->Appfile->delete($cur_item_info->image_id);
			    }
			}

			//Save Image File
			if(!empty($_FILES["image_id"]) && $_FILES["image_id"]["error"] == UPLOAD_ERR_OK)
			{			    
			    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
				$extension = strtolower(pathinfo($_FILES["image_id"]["name"], PATHINFO_EXTENSION));

			    if (in_array($extension, $allowed_extensions))
			    {
				    $config['image_library'] = 'gd2';
				    $config['source_image']	= $_FILES["image_id"]["tmp_name"];
				    $config['create_thumb'] = FALSE;
				    $config['maintain_ratio'] = TRUE;
				    $config['width']	 = 400;
				    $config['height']	= 300;
				    $this->load->library('image_lib', $config); 
				    $this->image_lib->resize();
				    $image_file_id = $this->Appfile->save($_FILES["image_id"]["name"], file_get_contents($_FILES["image_id"]["tmp_name"]));
			    }

			    $this->Item->update_image($image_file_id,$item_id);
			}
		}
		else //failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_error_adding_updating').' '.
			$item_data['name'],'item_id'=>-1));
		}

	}

	function save_inventory($item_id=-1)
	{
		$this->check_action_permission('add_update');		
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		$cur_item_location_info = $this->Item_location->get_info($item_id);
		
		$inv_data = array
		(
			'trans_date'=>date('Y-m-d H:i:s'),
			'trans_items'=>$item_id,
			'trans_user'=>$employee_id,
			'trans_comment'=>$this->input->post('trans_comment'),
			'trans_inventory'=>$this->input->post('newquantity'),
			'location_id'=>$this->Employee->get_logged_in_employee_current_location_id()
		);
		$this->Inventory->insert($inv_data);

		//Update stock quantity
		$item_data = array(
			'quantity'=> $cur_item_location_info->quantity + $this->input->post('newquantity'),
			'location_id'=>$this->Employee->get_logged_in_employee_current_location_id(),
			'item_id'=>$item_id
		);
		if($this->Item_location->save($item_data,$item_id))
		{
			echo json_encode(array('success'=>true,'message'=>lang('items_successful_updating').' '.
			$cur_item_info->name,'item_id'=>$item_id));
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_error_adding_updating').' '.
			$cur_item_info->name,'item_id'=>-1));
		}

	}

	function clear_state()
	{
		$this->session->unset_userdata('item_search_data');
		redirect('items');
	}

	function bulk_update()
	{
		$this->check_action_permission('add_update');
		$items_to_update=$this->input->post('item_ids');
		$select_inventory=$this->get_select_inventory();

		//clears the total inventory selection
		$this->clear_select_inventory();

		$item_data = array();

		foreach($_POST as $key=>$value)
		{
			if ($key == 'submit')
			{
				continue;
			}

			//This field is nullable, so treat it differently
			if ($key == 'supplier_id')
			{
				if ($value!='')
				{
					$item_data["$key"]=$value == '-1' ? null : $value;
				}
			}
			elseif($value != '' && ($key == 'start_date' || $key == 'end_date'))
			{
				$item_data["$key"]=date('Y-m-d', strtotime($value));
			}
			elseif($value!='' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents', 'tax_cumulatives', 'select_inventory'))))
			{
				$item_data["$key"]=$value;
			}
		}

		//Item data could be empty if tax information is being updated
		if(empty($item_data) || $this->Item->update_multiple($item_data,$items_to_update,$select_inventory))
		{
			//Only update tax data of we are override taxes
			if (isset($item_data['override_default_tax']) && $item_data['override_default_tax'])
			{
				$items_taxes_data = array();
				$tax_names = $this->input->post('tax_names');
				$tax_percents = $this->input->post('tax_percents');
				$tax_cumulatives = $this->input->post('tax_cumulatives');

				for($k=0;$k<count($tax_percents);$k++)
				{
					if (is_numeric($tax_percents[$k]))
					{
						$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
					}
				}

				if (!empty($items_taxes_data))
				{
					$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update,$select_inventory);
				}
			}
			echo json_encode(array('success'=>true,'message'=>lang('items_successful_bulk_edit')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_error_updating_multiple')));
		}
	}

	function delete()
	{
		$this->check_action_permission('delete');		
		$items_to_delete=$this->input->post('ids');
		$select_inventory=$this->get_select_inventory();
		$total_rows= $select_inventory ? $this->Item->count_all() : count($items_to_delete);
		//clears the total inventory selection
		$this->clear_select_inventory();
		if($this->Item->delete_list($items_to_delete,$select_inventory))
		{
			
			echo json_encode(array('success'=>true,'message'=>lang('items_successful_deleted').' '.
			$total_rows.' '.lang('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_cannot_be_deleted')));
		}
	}

	function _excel_get_header_row()
	{
		$header_row = array();
	
		$header_row[] = lang('items_item_number');
		$header_row[] = lang('items_product_id');
		$header_row[] = lang('items_name');
		$header_row[] = lang('items_category');
		$header_row[] = lang('items_supplier_id');
		$header_row[] = lang('items_cost_price');
		$header_row[] = lang('items_unit_price');
	
		foreach($this->Tier->get_all()->result() as $tier)
		{
			$header_row[] =$tier->name;
		}
	
		$header_row[] = lang('items_price_includes_tax');
		$header_row[] = lang('items_is_service');
		$header_row[] = lang('items_quantity');
		$header_row[] = lang('items_reorder_level');
		$header_row[] = lang('items_description');
		$header_row[] = lang('items_allow_alt_desciption');
		$header_row[] = lang('items_is_serialized');
			
		return $header_row;
	}
	
	function excel()
	{
		$this->load->helper('report');
		$header_row = $this->_excel_get_header_row();
		
		$content = array_to_csv(array($header_row));
		force_download('items_import.csv', $content);
	}

	function excel_export() {
		$data = $this->Item->get_all($this->Item->count_all())->result_object();
		$this->load->helper('report');
		
		$header_row = $this->_excel_get_header_row();
		$header_row[] = lang('items_item_id');
		$rows[] = $header_row;
		foreach ($data as $r) 
		{
			$row = array();
			$row[] = $r->item_number;
			$row[] = $r->product_id;
			$row[] = $r->name;
			$row[] = $r->category;
			$row[] = $r->supplier_id;
			$row[] = $r->cost_price;
			$row[] = $r->unit_price;
			
			foreach($this->Tier->get_all()->result() as $tier)
			{
				$tier_id = $tier->id;
				$tier_row = $this->Item->get_tier_price_row($tier_id, $r->item_id);
				
				$value = '';
				
				
				if (is_object($tier_row) && property_exists($tier_row, 'tier_id'))
				{
					$value = $tier_row->unit_price !== NULL ? $tier_row->unit_price : $tier_row->percent_off.'%';
				}

				$row[] = $value;
			}
			
			
			$row[] = $r->tax_included ? 'y' : '';
			$row[] = $r->is_service ? 'y' : '';
			$row[] = $r->quantity;
			$row[] = $r->reorder_level;
			$row[] = $r->description;
			$row[] = $r->allow_alt_description ? 'y' : '';
			$row[] = $r->is_serialized ? 'y' : '';
			$row[] = $r->item_id;
					
			$rows[] = $row;
		}

		$content = array_to_csv($rows);
		force_download('items_export.csv', $content);
		exit;
	}

	function excel_import()
	{
		$this->check_action_permission('add_update');
		$this->load->view("items/excel_import", null);
	}

	
	function do_excel_import()
	{
		set_time_limit(0);
		$this->check_action_permission('add_update');
		$this->db->trans_start();
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = lang('items_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);
				
				$price_tiers_count = $this->Tier->count_all();
				
				while (($data = fgetcsv($handle)) !== FALSE)
				{
					$item_data = array(
					'name'			=>	$data[2],
					'description'	=>	$data[11+$price_tiers_count],
					'category'		=>	$data[3],
					'cost_price'	=>	$data[5]!=null ? $data[5] : 0,
					'unit_price'	=>	$data[6]!=null ? $data[6] : 0,
					'tax_included' => $data[7+$price_tiers_count] != '' and $data[7+$price_tiers_count] != '0' and strtolower($data[7+$price_tiers_count]) != 'n' ? '1' : '0',
					'is_service' => $data[8+$price_tiers_count] != '' and $data[8+$price_tiers_count] != '0' and strtolower($data[8+$price_tiers_count]) != 'n' ? '1' : '0',
					'reorder_level'	=>	$data[10+$price_tiers_count]!=null ? $data[10+$price_tiers_count] : NULL,
					'supplier_id'	=>  $this->Supplier->exists($data[4]) ? $data[4] : $this->Supplier->find_supplier_id($data[4]),
					'allow_alt_description'=> $data[12+$price_tiers_count] != '' and $data[12+$price_tiers_count] != '0' and strtolower($data[12+$price_tiers_count]) != 'n' ? '1' : '0',
					'is_serialized'=> $data[13+$price_tiers_count] != '' and $data[13+$price_tiers_count] != '0' and strtolower($data[13+$price_tiers_count]) != 'n' ? '1' : '0',
					);
					$item_number = $data[0];
					$product_id = $data[1];
					$item_id = isset($data[14+$price_tiers_count]) ? $data[14+$price_tiers_count] : FALSE;
					

					if ($item_number != "")
					{
						$item_data['item_number'] = $item_number;
					}

					if ($product_id != "")
					{
						$item_data['product_id'] = $product_id;
					}

					if($this->Item->save($item_data, $item_id))
					{	
						$item_unit_price_col_index = 6;
						$counter = 0;
						//Save price tiers
						foreach($this->Tier->get_all()->result() as $tier)
						{
							$tier_id = $tier->id;
							
							$tier_data=array('tier_id'=>$tier_id);
							$tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] :  $item_id;
							$tier_value = $data[$item_unit_price_col_index+($counter + 1)];
							
							if ($tier_value)
							{
								if (strpos($tier_value, '%') === FALSE)
								{
									$tier_data['unit_price'] = $tier_value;
									$tier_data['percent_off'] = NULL;
								}
								else
								{
									$tier_data['percent_off'] = (int)$tier_value;
									$tier_data['unit_price'] = NULL;
								}
					
								$this->Item->save_item_tiers($tier_data,isset($item_data['item_id']) ? $item_data['item_id'] :  $item_id);
							}
							else
							{
								$this->Item->delete_tier_price($tier_id, isset($item_data['item_id'])? $item_data['item_id'] :  $item_id);	
							}
							
							$counter++;
						}
						
						$item_location_before_save = $this->Item_location->get_info($item_id,$this->Employee->get_logged_in_employee_current_location_id());
						
						$quantity_data=array(
							'quantity'=>$data[9+$price_tiers_count]!=null ? $data[9+$price_tiers_count] : NULL,
							'location_id'=>$this->Employee->get_logged_in_employee_current_location_id(),
						);

						$this->Item_location->save($quantity_data,isset($item_data['item_id']) ? $item_data['item_id'] :  $item_id);
						
						$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
						$emp_info=$this->Employee->get_info($employee_id);
						$comment =lang('items_csv_import');
						
						if (!$item_data['is_service'])
						{
							$inv_data = array
							(
								'trans_date'=>date('Y-m-d H:i:s'),
								'trans_items'=>isset($item_data['item_id']) ? $item_data['item_id'] :  $item_id,
								'trans_user'=>$employee_id,
								'trans_comment'=>$comment,
								'trans_inventory'=>$data[9+$price_tiers_count] - $item_location_before_save->quantity,
								'location_id'=>$this->Employee->get_logged_in_employee_current_location_id()
							);
							$this->Inventory->insert($inv_data);
						}
					}
					else//insert or update item failure
					{
						echo json_encode( array('success'=>false,'message'=>lang('items_duplicate_item_ids')));
						return;
					}
				}
			}
			else
			{
				echo json_encode( array('success'=>false,'message'=>lang('common_upload_file_not_supported_format')));
				return;
			}
		}

		$this->db->trans_complete();
		echo json_encode(array('success'=>true,'message'=>lang('items_import_successful')));
	}
	
	function cleanup()
	{
		$this->Item->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('items_cleanup_sucessful')));
	}
	
	
	function select_inventory() 
	{
		$this->session->set_userdata('select_inventory', 1);
	}
	
	function get_select_inventory() 
	{
		return $this->session->userdata('select_inventory') ? $this->session->userdata('select_inventory') : 0;
	}

	function clear_select_inventory() 	
	{
		$this->session->unset_userdata('select_inventory');
		
	}

}
?>