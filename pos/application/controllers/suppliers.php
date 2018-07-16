<?php
require_once ("person_controller.php");
class Suppliers extends Person_controller
{
	function __construct()
	{
		parent::__construct('suppliers');
	}
	
	
	function index($offset=0)
	{
		$params = $this->session->userdata('supplier_search_data') ? $this->session->userdata('supplier_search_data') : array('offset' => 0, 'order_col' => 'company_name', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('suppliers/index/'.$params['offset']);
		}
		$this->check_action_permission('search');
		$config['base_url'] = site_url('suppliers/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Supplier->search_count_all($data['search']);
			$table_data = $this->Supplier->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Supplier->count_all();
			$table_data = $this->Supplier->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		$data['total_rows'] = $config['total_rows'];
		
		$data['manage_table']=get_supplier_manage_table($table_data,$this);
		$this->load->view('people/manage',$data);
	}
	
	
	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$supplier_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("supplier_search_data",$supplier_search_data);
		if ($search)
		{
			$config['total_rows'] = $this->Supplier->search_count_all($search);
			$table_data = $this->Supplier->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Supplier->count_all();
			$table_data = $this->Supplier->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('suppliers/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_supplier_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
	}
	
	function _excel_get_header_row()
	{
		return array(lang('suppliers_company_name'),lang('common_first_name'),lang('common_last_name'),lang('common_email'),lang('common_phone_number'),lang('common_address_1'),lang('common_address_2'),lang('common_city'),	lang('common_state'),lang('common_zip'),lang('common_country'),lang('common_comments'),lang('suppliers_account_number'));
	}
	
	function clear_state()
	{
		$this->session->unset_userdata('supplier_search_data');
		redirect('suppliers');
	}

	function excel()
	{
		$this->load->helper('report');
		$header_row = $this->_excel_get_header_row();
		
		$content = array_to_csv(array($header_row));
		force_download('import_suppliers.csv', $content);
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
			$msg = lang('suppliers_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);
				while (($data = fgetcsv($handle)) !== FALSE) 
				{
					$person_data = array(
					'first_name'=>$data[1],
					'last_name'=>$data[2],
					'email'=>$data[3],
					'phone_number'=>$data[4],
					'address_1'=>$data[5],
					'address_2'=>$data[6],
					'city'=>$data[7],
					'state'=>$data[8],
					'zip'=>$data[9],
					'country'=>$data[10],
					'comments'=>$data[11]
					);
					
					$supplier_data=array(
					'account_number'=>$data[12]=='' ? null:$data[12],
					'company_name' => $data[0],
					);
					
					if(!$this->Supplier->save($person_data,$supplier_data, isset($data[13]) ? $data[13] : FALSE))
					{	
						echo json_encode( array('success'=>false,'message'=>lang('suppliers_duplicate_account_id')));
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
		echo json_encode(array('success'=>true,'message'=>lang('suppliers_import_successfull')));
	}
	
	
	function excel_import()
	{
		$this->check_action_permission('add_update');
		$this->load->view("suppliers/excel_import", null);
	}
	
	/* added for excel expert */
	function excel_export() {
		$data = $this->Supplier->get_all($this->Supplier->count_all())->result_object();
		$this->load->helper('report');
		$rows = array();
		$header_row = $this->_excel_get_header_row();
		$header_row[] = lang('suppliers_id');
		$rows[] = $header_row;
		
		foreach ($data as $r) {
			$row = array(
				$r->company_name,
				$r->first_name,
				$r->last_name,
				$r->email,
				$r->phone_number,
				$r->address_1,
				$r->address_2,
				$r->city,
				$r->state,
				$r->zip,
				$r->country,
				$r->comments,
				$r->account_number,
				$r->person_id
			);
			$rows[] = $row;
		}
		
		$content = array_to_csv($rows);

		force_download('suppliers_export' . '.csv', $content);
		exit;
	}
	/*
	Returns supplier table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$supplier_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("supplier_search_data",$supplier_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Supplier->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('suppliers/search');
		$config['total_rows'] = $this->Supplier->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_supplier_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
		
	}
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Supplier->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the supplier edit form
	*/
	function view($supplier_id=-1, $redirect = 0)
	{
		$this->check_action_permission('add_update');	
		$data = array();	
		$data['controller_name']=strtolower(get_class());
		$data['person_info']=$this->Supplier->get_info($supplier_id);
		$data['redirect']=$redirect;
		$this->load->view("suppliers/form",$data);
	}
	
	/*
	Inserts/updates a supplier
	*/
	function save($supplier_id=-1)
	{
		$this->check_action_permission('add_update');		
		$person_data = array(
		'first_name'=>$this->input->post('first_name'),
		'last_name'=>$this->input->post('last_name'),
		'email'=>$this->input->post('email'),
		'phone_number'=>$this->input->post('phone_number'),
		'address_1'=>$this->input->post('address_1'),
		'address_2'=>$this->input->post('address_2'),
		'city'=>$this->input->post('city'),
		'state'=>$this->input->post('state'),
		'zip'=>$this->input->post('zip'),
		'country'=>$this->input->post('country'),
		'comments'=>$this->input->post('comments')
		);
		$supplier_data=array(
		'company_name'=>$this->input->post('company_name'),
		'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
		);
		
		$redirect = $this->input->post('redirect');
		
		if($this->Supplier->save($person_data,$supplier_data,$supplier_id))
		{
			if ($this->Location->get_info_for_key('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
			}
			
			//New supplier
			if($supplier_id==-1)
			{
				echo json_encode(array('success'=>true, 'redirect'=> $redirect, 'message'=>lang('suppliers_successful_adding').' '.
				$supplier_data['company_name'],'person_id'=>$supplier_data['person_id']));
			}
			else //previous supplier
			{
				echo json_encode(array('success'=>true,'redirect'=> $redirect, 'message'=>lang('suppliers_successful_updating').' '.
				$supplier_data['company_name'],'person_id'=>$supplier_id));
			}
			
			//Delete Image
			if($this->input->post('del_image') && $supplier_id != -1)
			{
				$supplier_info = $this->Supplier->get_info($supplier_id);				
			    if($supplier_info->image_id != null)
			    {
					$this->Person->update_image(NULL,$supplier_id);
					$this->Appfile->delete($supplier_info->image_id);
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

				if($supplier_id==-1)
				{
	    			$this->Person->update_image($image_file_id,$supplier_data['person_id']);
				}
				else
				{
					$this->Person->update_image($image_file_id,$supplier_id);
    			
				}
			}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('suppliers_error_adding_updating').' '.
			$supplier_data['company_name'],'person_id'=>-1));
		}
	}
		
	function account_number_exists()
	{
		if($this->Supplier->account_number_exists($this->input->post('account_number')))
		echo 'false';
		else
		echo 'true';
		
	}
	/*
	This deletes suppliers from the suppliers table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$suppliers_to_delete=$this->input->post('ids');
		
		if($this->Supplier->delete_list($suppliers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('suppliers_successful_deleted').' '.
			count($suppliers_to_delete).' '.lang('suppliers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('suppliers_cannot_be_deleted')));
		}
	}
	
	/*
	Gets one row for a supplier manage table. This is called using AJAX to update one row.
	*/
	function get_row()
	{
		$person_id = $this->input->post('row_id');
		$data_row=get_supplier_data_row($this->Supplier->get_info($person_id),$this);
		echo $data_row;
	}
}
?>