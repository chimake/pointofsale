<?php
class Location extends CI_Model
{
	/*
	Determines if a given location_id is an location
	*/
	function exists($location_id)
	{
		$this->db->from('locations');
		$this->db->where('location_id',$location_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*
	Returns all the locations
	*/
	function get_all($limit=10000, $offset=0,$col='location_id',$order='asc')
	{
		$this->db->from('locations');
		$this->db->where('deleted',0);
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	function count_all()
	{
		$this->db->from('locations');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular location
	*/
	function get_info($location_id)
	{
		$this->db->from('locations');
		$this->db->where('location_id',$location_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $location_id is NOT a location
			$location_obj=new stdClass();

			//Get all the fields from locations table
			$fields = $this->db->list_fields('locations');

			foreach ($fields as $field)
			{
				$location_obj->$field='';
			}

			return $location_obj;
		}
	}
	
	function get_info_for_key($key)
	{
		static $location_info;
		
		if (!$location_info)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
			$location_info = $this->get_info($location_id);
		}
		
		return $location_info->{$key};
	}

	/*
	Inserts or updates a location
	*/
	function save(&$location_data,$location_id=false)
	{
		if (!$location_id or !$this->exists($location_id))
		{
			if($this->db->insert('locations',$location_data))
			{
				$location_data['location_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('location_id', $location_id);
		return $this->db->update('locations',$location_data);
	}


	function search_count_all($search, $limit=10000)
	{
			$this->db->from('locations');
			$this->db->where("name LIKE '%".$this->db->escape_like_str($search)."%' and deleted=0");
			$this->db->order_by("name", "asc");
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}


 	/*
	Get search suggestions to find locations
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('locations');
		$this->db->like('name', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		
		
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		
		$this->db->from('locations');
		$this->db->where('deleted',0);
		$this->db->like('address', $search);
		$this->db->limit($limit);
		
		$by_address = $this->db->get();
		$temp_suggestions = array();
		foreach($by_address->result() as $row)
		{
			$temp_suggestions[] = $row->address;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		

		$this->db->from('locations');
		$this->db->like('location_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_location_id = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_location_id->result() as $row)
		{
			$temp_suggestions[] = $row->location_id;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('locations');
		$this->db->like('phone', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_phone = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_phone->result() as $row)
		{
			$temp_suggestions[] = $row->phone;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('locations');
		$this->db->like('email', $search);
		$this->db->where('deleted',0);
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

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}


	/*
	Preform a search on locations
	*/
	
	function search($search, $limit=20,$offset=0,$column='name',$orderby='asc')
	{
			$search_terms_array=explode(" ", $this->db->escape_like_str($search));
	
			//to keep track of which search term of the array we're looking at now	
			$search_name_criteria_counter=0;
			$sql_search_name_criteria = '';
			//loop through array of search terms
			foreach ($search_terms_array as $x)
			{
				$sql_search_name_criteria.=
				($search_name_criteria_counter > 0 ? " AND " : "").
				"name LIKE '%".$this->db->escape_like_str($x)."%'";
				
				$search_name_criteria_counter++;
			}
	
			$this->db->from('locations');
			$this->db->where("((".
			$sql_search_name_criteria. ") or 
			address LIKE '%".$this->db->escape_like_str($search)."%' or 
			location_id LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();	
	}


	function get_locations_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('locations');
		$this->db->where('deleted', 0);
		$this->db->like("name",$search);
		$this->db->limit($limit);	
		$by_company_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_company_name->result() as $row)
		{
			$temp_suggestions[$row->location_id] = $row->name;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}

		$this->db->from('locations');
		
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
		address LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");			
		
		
		$this->db->limit($limit);	
		$by_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[$row->location_id] = $row->address;
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
	Deletes one location
	*/
	function delete($location_id)
	{
		$current_location_id= $this->Employee->get_logged_in_employee_current_location_id();

		//Don't let current logged in location be deleted
		if($current_location_id == $location_id || !$location_id)
			return false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where('location_id', $location_id);
		$this->db->delete('employees_locations');

		$this->db->where('location_id', $location_id);
		$this->db->delete('location_items');

		$this->db->where('location_id', $location_id);
		$this->db->delete('location_items_taxes');

		$this->db->where('location_id', $location_id);
		$this->db->delete('location_items_tier_prices');

		$this->db->where('location_id', $location_id);
		$this->db->delete('location_item_kits');

		$this->db->where('location_id', $location_id);
		$this->db->delete('location_item_kits_taxes');

		$this->db->where('location_id', $location_id);
		$this->db->delete('location_item_kits_tier_prices');
		
		$this->db->where('location_id', $location_id);
		$this->db->update('locations', array('deleted' => 1));
		
		return $this->db->trans_complete();		
	}
	
	function delete_list($location_ids)
	{	
		$location_id= $this->Employee->get_logged_in_employee_current_location_id();

		//Don't let current logged in location be deleted
		if(in_array($location_id,$location_ids) || empty($location_ids))
			return false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('employees_locations');

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('location_items');

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('location_items_taxes');

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('location_items_tier_prices');

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('location_item_kits');

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('location_item_kits_taxes');

		$this->db->where_in('location_id',$location_ids);
		$this->db->delete('location_item_kits_tier_prices');
		
		$this->db->where_in('location_id',$location_ids);
		$this->db->update('locations', array('deleted' => 1));
		
		return $this->db->trans_complete();		
 	}
}
?>
