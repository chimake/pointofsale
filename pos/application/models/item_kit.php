<?php
class Item_kit extends CI_Model
{
	/*
	Determines if a given item_id is an item kit
	*/
	function exists($item_kit_id)
	{
		$this->db->from('item_kits');
		$this->db->where('item_kit_id',$item_kit_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*
	Returns all the item kits
	*/
	function get_all($limit=10000, $offset=0,$col='name',$ord='asc')
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select('item_kits.*,
		location_item_kits.unit_price as location_unit_price,
		location_item_kits.cost_price as location_cost_price');		
		$this->db->from('item_kits');
		$this->db->join('location_item_kits', 'location_item_kits.item_kit_id = item_kits.item_kit_id and location_id = '.$current_location, 'left');
		$this->db->where('deleted',0);
		$this->db->order_by($col, $ord);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	function count_all()
	{
		$this->db->from('item_kits');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
		
	function get_all_categories()
	{
		$this->db->select('category');
		$this->db->from('item_kits');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");
		return $this->db->get();
	}
	
	/*
	Gets information about a particular item kit
	*/
	function get_info($item_kit_id)
	{
		$this->db->from('item_kits');
		$this->db->where('item_kit_id',$item_kit_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('item_kits');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}
	
	
		
	function check_duplicate($term)
	{
		$this->db->from('item_kits');
		$this->db->where('deleted',0);		
		$query = $this->db->where("name = ".$this->db->escape($term));
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
		
	}
	/*
	Get an item_kit_id given an item kit number
	*/
	function get_item_kit_id($item_kit_number)
	{
		$this->db->from('item_kits');
		$this->db->where('item_kit_number',$item_kit_number);
		$this->db->or_where('product_id', $item_kit_number); 
		$query = $this->db->get();

		if($query->num_rows() >= 1)
		{
			return $query->row()->item_kit_id;
		}

		return false;
	}

	/*
	Gets information about multiple item kits
	*/
	function get_multiple_info($item_kit_ids)
	{
		$this->db->from('item_kits');
		$this->db->where_in('item_kit_id',$item_kit_ids);
		$this->db->order_by("name", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates an item kit
	*/
	function save(&$item_kit_data,$item_kit_id=false)
	{
		if (!$item_kit_id or !$this->exists($item_kit_id))
		{
			if($this->db->insert('item_kits',$item_kit_data))
			{
				$item_kit_data['item_kit_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('item_kit_id', $item_kit_id);
		return $this->db->update('item_kits',$item_kit_data);
	}

	/*
	Deletes one item kit
	*/
	function delete($item_kit_id)
	{
		$this->db->where('item_kit_id', $item_kit_id);
		return $this->db->update('item_kits', array('deleted' => 1));
	}

	/*
	Deletes a list of item kits
	*/
	function delete_list($item_kit_ids)
	{
		$this->db->where_in('item_kit_id',$item_kit_ids);
		return $this->db->update('item_kits', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find kits
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('item_kits');
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
		
		$this->db->from('item_kits');
		$this->db->like('item_kit_number', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_item_kit_number = $this->db->get();
		$temp_suggestions = array();
		foreach($by_item_kit_number->result() as $row)
		{
			$temp_suggestions[] = $row->item_kit_number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}

		$this->db->from('item_kits');
		$this->db->like('product_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_product_id = $this->db->get();
		$temp_suggestions = array();
		foreach($by_product_id->result() as $row)
		{
			$temp_suggestions[] = $row->product_id;
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
	
	function get_item_kit_search_suggestions($search, $limit=25)
	{
		$suggestions = array();

		$this->db->from('item_kits');
		$this->db->like('name', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			if ($row->category)
			{
				$temp_suggestions['KIT '.$row->item_kit_id] =  $row->name . ' ('.$row->category.')';
			}
			else
			{
				$temp_suggestions['KIT '.$row->item_kit_id] = $row->name;
			}
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		$this->db->from('item_kits');
		$this->db->like('item_kit_number', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_item_kit_number = $this->db->get();

		$temp_suggestions = array();
		
		foreach($by_item_kit_number->result() as $row)
		{
			$temp_suggestions['KIT '.$row->item_kit_id] = $row->item_kit_number;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}

		$this->db->from('item_kits');
		$this->db->like('product_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_product_id = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_product_id->result() as $row)
		{
			$temp_suggestions['KIT '.$row->item_kit_id] = $row->product_id;
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
	Preform a search on items kits
	*/
	function search($search, $limit=16,$offset=0,$column='name',$orderby='asc')
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();

		$this->db->select('item_kits.*,
		location_item_kits.unit_price as location_unit_price,
		location_item_kits.cost_price as location_cost_price');		
		$this->db->from('item_kits');
		$this->db->join('location_item_kits', 'location_item_kits.item_kit_id = item_kits.item_kit_id and location_id = '.$current_location, 'left');
		
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search).
		"%' or item_kit_number LIKE '%".$this->db->escape_like_str($search)."%'".
		"or product_id LIKE '%".$this->db->escape_like_str($search)."%' or
		description LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");	
		
		$this->db->order_by($column, $orderby);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();	
	}
	
	function search_count_all($search, $limit=10000)
	{
		$this->db->from('item_kits');
		
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search).
		"%' or item_kit_number LIKE '%".$this->db->escape_like_str($search)."%' or
		description LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");	
		
		$result=$this->db->get();				
		return $result->num_rows();	
	}
	
	function get_tier_price_row($tier_id,$item_kit_id)
	{
		$this->db->from('item_kits_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_kit_id ',$item_kit_id);
		return $this->db->get()->row();
	}
		
	function delete_tier_price($tier_id, $item_kit_id)
	{
		
		$this->db->where('tier_id', $tier_id);
		$this->db->where('item_kit_id', $item_kit_id);
		$this->db->delete('item_kits_tier_prices');
	}
	
	function tier_exists($tier_id, $item_kit_id)
	{
		$this->db->from('item_kits_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_kit_id',$item_kit_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
		
	}
	
	function save_item_tiers($tier_data,$item_kit_id)
	{
		if($this->tier_exists($tier_data['tier_id'],$item_kit_id))
		{
			$this->db->where('tier_id', $tier_data['tier_id']);
			$this->db->where('item_kit_id', $item_kit_id);

			return $this->db->update('item_kits_tier_prices',$tier_data);
			
		}

		return $this->db->insert('item_kits_tier_prices',$tier_data);	
	}
}
?>