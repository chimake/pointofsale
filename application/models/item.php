<?php
class Item extends CI_Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*
	Returns all the items
	*/
	function get_all($limit=10000, $offset=0,$col='item_id',$order='desc')
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->select('items.*,
		location_items.quantity as quantity, 
		location_items.cost_price as location_cost_price,
		location_items.unit_price as location_unit_price');
		
		$this->db->from('items');
		$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
		$this->db->where('items.deleted',0);
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	function get_all_by_category($category, $offset=0, $limit = 14)
	{
		$items_table = $this->db->dbprefix('items');
		$item_kits_table = $this->db->dbprefix('item_kits');
		
		$result = $this->db->query("(SELECT item_id, name, image_id FROM $items_table 
		WHERE deleted = 0 and category = ".$this->db->escape($category). " ORDER BY name) UNION ALL (SELECT CONCAT('KIT ',item_kit_id), name, 'no_image' as image_id FROM $item_kits_table 
		WHERE deleted = 0 and category = ".$this->db->escape($category). " ORDER BY name) ORDER BY name LIMIT $offset, $limit");
		return $result;
	}
	
	function count_all_by_category($category)
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->where('category',$category);
		$items_count = $this->db->count_all_results();

		$this->db->from('item_kits');
		$this->db->where('deleted',0);
		$this->db->where('category',$category);
		$item_kits_count = $this->db->count_all_results();
		
		return $items_count + $item_kits_count;

	}
	
	function get_all_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");
		return $this->db->get();
	}
	
	function get_next_id($item_id)
	{
		$items_table = $this->db->dbprefix('items');
		$result = $this->db->query("SELECT item_id FROM $items_table WHERE item_id = (select min(item_id) from $items_table where deleted = 0 and item_id > ".$this->db->escape($item_id).")");
		
		if($result->num_rows() > 0)
		{
			$row = $result->result();
			return $row[0]->item_id;
		}
		
		return FALSE;
	}
	
	function get_prev_id($item_id)
	{
		$items_table = $this->db->dbprefix('items');
		$result = $this->db->query("SELECT item_id FROM $items_table WHERE item_id = (select max(item_id) from $items_table where deleted = 0 and item_id <".$this->db->escape($item_id).")");
		
		if($result->num_rows() > 0)
		{
			$row = $result->result();
			return $row[0]->item_id;
		}
		
		return FALSE;
	}
	
	function get_tier_price_row($tier_id,$item_id)
	{
		$this->db->from('items_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_id ',$item_id);
		return $this->db->get()->row();
	}
		
	function delete_tier_price($tier_id, $item_id)
	{
		
		$this->db->where('tier_id', $tier_id);
		$this->db->where('item_id', $item_id);
		$this->db->delete('items_tier_prices');
	}
	
	function tier_exists($tier_id, $item_id)
	{
		$this->db->from('items_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
		
	}
	
	function save_item_tiers($tier_data,$item_id)
	{
		if($this->tier_exists($tier_data['tier_id'],$item_id))
		{
			$this->db->where('tier_id', $tier_data['tier_id']);
			$this->db->where('item_id', $item_id);

			return $this->db->update('items_tier_prices',$tier_data);
			
		}

		return $this->db->insert('items_tier_prices',$tier_data);	
	}


	function account_number_exists($item_number)
	{
		$this->db->from('items');	
		$this->db->where('item_number',$item_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function count_all()
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}

	/*
	Get an item id given an item number or product_id
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->where('item_number',$item_number);
		$this->db->or_where('product_id', $item_number); 

		$query = $this->db->get();

		if($query->num_rows() >= 1)
		{
			return $query->row()->item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->where_in('item_id',$item_ids);
		$this->db->order_by("item_id", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data,$item_id=false)
	{
		if (!$item_id or !$this->exists($item_id))
		{
			if($this->db->insert('items',$item_data))
			{
				$item_data['item_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('item_id', $item_id);
		return $this->db->update('items',$item_data);
	}

	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids,$select_inventory=0)
	{
		if(!$select_inventory){
		$this->db->where_in('item_id',$item_ids);
		}
		return $this->db->update('items',$item_data);
	}

	
	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$item_info = $this->Item->get_info($item_id);
	
		if ($item_info->image_id !== NULL)
		{
			$this->Item->update_image(NULL,$item_id);
			$this->Appfile->delete($item_info->image_id);			
		}			
		
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids,$select_inventory)
	{
		foreach($item_ids as $item_id)
		{
			$item_info = $this->Item->get_info($item_id);
		
			if ($item_info->image_id !== NULL)
			{
				$this->Item->update_image(NULL,$item_id);
				$this->Appfile->delete($item_info->image_id);			
			}			
		}
		
		if(!$select_inventory){
		$this->db->where_in('item_id',$item_ids);
		}
		return $this->db->update('items', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
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
		
		
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->like('category', $search);
		$this->db->limit($limit);
		$by_category = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_category->result() as $row)
		{
			$temp_suggestions[] = $row->category;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		

		$this->db->from('items');
		$this->db->like('item_number', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_item_number = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_item_number->result() as $row)
		{
			$temp_suggestions[] = $row->item_number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('items');
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
		
		$this->db->from('items');
		$this->db->where('item_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_item_id = $this->db->get();
		$temp_suggestions = array();
		foreach($by_item_id->result() as $row)
		{
			$temp_suggestions[] = $row->item_id;
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

	function check_duplicate($term)
	{
		$this->db->from('items');
		$this->db->where('deleted',0);		
		$query = $this->db->where("name = ".$this->db->escape($term));
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
		
		
	}
	
	function get_item_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('name', $search);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			if ($row->category)
			{
				$temp_suggestions[$row->item_id] =  $row->name . ' ('.$row->category.')';
			}
			else
			{
				$temp_suggestions[$row->item_id] = $row->name;
			}
			
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('item_number', $search);
		$this->db->limit($limit);
		$by_item_number = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_item_number->result() as $row)
		{
			$temp_suggestions[$row->item_id] = $row->item_number;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
				
		$this->db->from('items');
		$this->db->like('product_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_product_id = $this->db->get();

		$temp_suggestions = array();
		
		foreach($by_product_id->result() as $row)
		{
			$temp_suggestions[$row->item_id] = $row->product_id;
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

	function get_category_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->limit(25);
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=array('label' => $row->category);
		}

		return $suggestions;
	}

	/*
	Preform a search on items
	*/
	
	function search($search, $category = false, $limit=20,$offset=0,$column='name',$orderby='asc')
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
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
				
			
			$this->db->select('items.*,
			location_items.quantity as quantity, 
			location_items.cost_price as location_cost_price,
			location_items.unit_price as location_unit_price');
			$this->db->from('items');
			$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
			$this->db->where("((".
			$sql_search_name_criteria. ") or 
			item_number LIKE '%".$this->db->escape_like_str($search)."%' or ".
			"product_id LIKE '%".$this->db->escape_like_str($search)."%' or ".
			$this->db->dbprefix('items').".item_id LIKE '%".$this->db->escape_like_str($search)."%' or 
			category LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
			
			if ($category)
			{
				$this->db->where('items.category', $category);
			}
				
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}

	function search_count_all($search, $category = FALSE, $limit=10000)
	{
			$this->db->from('items');
			$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
			item_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			category LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
			
			if ($category)
			{
				$this->db->where('items.category', $category);
			}
			
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}

	
	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get();
	}
	
	function cleanup()
	{
		$item_data = array('item_number' => null, 'product_id' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('items',$item_data);
	}
	
	function update_image($file_id,$item_id)
	{
		$this->db->set('image_id',$file_id);
	    $this->db->where('item_id',$item_id);
	    
		return $this->db->update('items');
	}
	
	function create_or_update_store_account_item()
	{
		$item_id = FALSE;
		
		$this->db->from('items');
		$this->db->where('name', lang('sales_store_account_payment'));
		$this->db->where('deleted', 0);

		$result=$this->db->get();				
		if ($result->num_rows() > 0)
		{
			$query_result = $result->result();
			$item_id = $query_result[0]->item_id;
		}
		
		$item_data = array(
			'name'			=>	lang('sales_store_account_payment'),
			'description'	=>	'',
			'item_number'	=> NULL,
			'category'		=>	lang('sales_store_account_payment'),
			'cost_price'	=>	0,
			'unit_price'	=>	0,
			'tax_included' => 0,
			'reorder_level'	=>	NULL,
			'allow_alt_description'=> 0,
			'is_serialized'=> 0,
			'is_service'=> 1,
			'override_default_tax' => 1
		);
		
		$this->save($item_data, $item_id);
			
		if ($item_id)
		{
			return $item_id;
		}
		else
		{
			return $item_data['item_id'];
		}
	}
	
	function get_store_account_item_id()
	{
		$this->db->from('items');
		$this->db->where('name', lang('sales_store_account_payment'));
		$this->db->where('deleted', 0);

		$result=$this->db->get();				
		if ($result->num_rows() > 0)
		{
			$query_result = $result->result();
			return $query_result[0]->item_id;
		}
		
		return FALSE;
	}
}
?>
