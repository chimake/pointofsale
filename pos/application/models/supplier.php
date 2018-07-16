<?php
class Supplier extends Person
{	
	/*
	Determines if a given person_id is a customer
	*/
	function exists($person_id)
	{
		$this->db->from('suppliers');	
		$this->db->join('people', 'people.person_id = suppliers.person_id');
		$this->db->where('suppliers.person_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	/*
	Returns all the suppliers
	*/
	function get_all($limit=10000, $offset=0,$col='company_name',$order='asc')
	{
		$people=$this->db->dbprefix('people');
		$suppliers=$this->db->dbprefix('suppliers');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$suppliers." ON 										                       
						".$people.".person_id = ".$suppliers.".person_id
						WHERE deleted =0 ORDER BY ".$col." ".$order." 
						LIMIT  ".$offset.",".$limit);		
						
		return $data;	
	}
	
	function account_number_exists($account_number)
	{
		$this->db->from('suppliers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	function count_all()
	{
		$this->db->from('suppliers');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular supplier
	*/
	function get_info($supplier_id)
	{
		$this->db->from('suppliers');	
		$this->db->join('people', 'people.person_id = suppliers.person_id');
		$this->db->where('suppliers.person_id',$supplier_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $supplier_id is NOT an supplier
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from supplier table
			$fields = $this->db->list_fields('suppliers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple suppliers
	*/
	function get_multiple_info($suppliers_ids)
	{
		$this->db->from('suppliers');
		$this->db->join('people', 'people.person_id = suppliers.person_id');		
		$this->db->where_in('suppliers.person_id',$suppliers_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a suppliers
	*/
	function save(&$person_data, &$supplier_data,$supplier_id=false)
	{
		$success=false;
		
		if(parent::save($person_data,$supplier_id))
		{
			if (!$supplier_id or !$this->exists($supplier_id))
			{
				$supplier_data['person_id'] = $person_data['person_id'];
				$success = $this->db->insert('suppliers',$supplier_data);				
			}
			else
			{
				$this->db->where('person_id', $supplier_id);
				$success = $this->db->update('suppliers',$supplier_data);
			}
			
		}
		
		return $success;
	}
	
	/*
	Deletes one supplier
	*/
	function delete($supplier_id)
	{
		$supplier_info = $this->Supplier->get_info($supplier_id);
	
		if ($supplier_info->image_id !== NULL)
		{
			$this->Person->update_image(NULL,$supplier_id);
			$this->Appfile->delete($supplier_info->image_id);			
		}			
		
		$this->db->where('person_id', $supplier_id);
		return $this->db->update('suppliers', array('deleted' => 1));
	}
	
	/*
	Deletes a list of suppliers
	*/
	function delete_list($supplier_ids)
	{
		foreach($supplier_ids as $supplier_id)
		{
			$supplier_info = $this->Supplier->get_info($supplier_id);
		
			if ($supplier_info->image_id !== NULL)
			{
				$this->Person->update_image(NULL,$supplier_id);
				$this->Appfile->delete($supplier_info->image_id);			
			}			
		}
		
		$this->db->where_in('person_id',$supplier_ids);
		return $this->db->update('suppliers', array('deleted' => 1));
 	}
 	
 	/*
	Get search suggestions to find suppliers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("company_name",$search);
		$this->db->limit($limit);	
		$by_company_name = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_company_name->result() as $row)
		{
			$temp_suggestions[] = $row->company_name;
		}
		
		sort($temp_suggestions);
		
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}

		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		$this->db->limit($limit);	
		$by_name = $this->db->get();
				
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->last_name.', '.$row->first_name;
		}
		
		sort($temp_suggestions);
		
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
				
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("email",$search);
		$this->db->limit($limit);
		$by_email = $this->db->get();

		$temp_suggestions = array();
		foreach($by_email->result() as $row)
		{
			$temp_suggestions[] = $row->email;
		}
		
		sort($temp_suggestions);
		
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}

		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("phone_number",$search);
		$this->db->limit($limit);		
		$by_phone = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_phone->result() as $row)
		{
			$temp_suggestions[] = $row->phone_number;
		}
		
		sort($temp_suggestions);
		
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("account_number",$search);
		$this->db->limit($limit);
		$by_account_number = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_account_number->result() as $row)
		{
			$temp_suggestions[] = $row->account_number;
		}
		
		sort($temp_suggestions);
		
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	/*
	Get search suggestions to find suppliers
	*/
	function get_suppliers_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("company_name",$search);
		$this->db->limit($limit);
		$by_company_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_company_name->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->company_name;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		

		$this->db->from('suppliers');
		$this->db->join('people','suppliers.person_id=people.person_id');	
		
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");			
	
		$this->db->limit($limit);	
		$by_name = $this->db->get();
		
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->last_name.', '.$row->first_name;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
				
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
	/*
	Perform a search on suppliers
	*/
	function search($search, $limit=20,$offset=0,$column='last_name',$orderby='asc')
	{
			$this->db->from('suppliers');
	 		$this->db->join('people','suppliers.person_id=people.person_id');
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column, $orderby);
	 		$this->db->limit($limit);
	 		$this->db->offset($offset);
	 		return $this->db->get();	
	}
	
	function search_count_all($search, $limit=10000)
	{
			$this->db->from('suppliers');
	 		$this->db->join('people','suppliers.person_id=people.person_id');
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
	 		$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();		 		
	}
	
	function find_supplier_id($search)
	{
		if ($search)
		{
			$this->db->select("suppliers.person_id");
			$this->db->from('suppliers');
			$this->db->join('people','suppliers.person_id=people.person_id');
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."%' or
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by("last_name", "asc");
			$query = $this->db->get();
		
			if ($query->num_rows() > 0)
			{
				return $query->row()->person_id;
			}
		}
		
		return null;
	}
}
?>
