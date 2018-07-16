<?php
class Item_kit_location extends CI_Model
{

	function exists($item_kit_id,$location=false)
	{
		if(!$location)
		{
			$location= $this->Employee->get_logged_in_employee_current_location_id();
		}
		$this->db->from('location_item_kits');
		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->where('location_id',$location);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	
	function save($item_location_data,$item_kit_id=-1,$location_id=false)
	{
		if(!$location_id)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		if (!$this->exists($item_kit_id,$location_id))
		{
			return $this->db->insert('location_item_kits',$item_location_data);
		}

		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->where('location_id',$location_id);
		return $this->db->update('location_item_kits',$item_location_data);
		
	}
	
	function get_info($item_kit_id,$location=false)
	{
		if(!$location)
		{
			$location= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('location_item_kits');
		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->where('location_id',$location);
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			$row = $query->row();
			
			//Store a boolean indicating if the price has been overwritten
			$row->is_overwritten = ($row->cost_price !== NULL ||
			$row->unit_price !== NULL ||
			$this->is_tier_overwritten($item_kit_id, $location));
			return $row;
		
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item_kit_location
			$item_kit_location_obj=new stdClass();

			//Get all the fields from item_kit_locations table
			$fields = $this->db->list_fields('location_item_kits');

			foreach ($fields as $field)
			{
				$item_kit_location_obj->$field='';
			}
			
			$item_kit_location_obj->is_overwritten = FALSE;

			return $item_kit_location_obj;
		}

		return FALSE;
	}
	
	function get_tier_price_row($tier_id,$item_kit_id, $location_id)
	{
		$this->db->from('location_item_kits_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_kit_id ',$item_kit_id);
		$this->db->where('location_id ',$location_id);
		return $this->db->get()->row();
	}
		
	function delete_tier_price($tier_id, $item_kit_id, $location_id)
	{
		$this->db->where('tier_id', $tier_id);
		$this->db->where('item_kit_id', $item_kit_id);
		$this->db->where('location_id', $location_id);
		$this->db->delete('location_item_kits_tier_prices');
	}
	
	function tier_exists($tier_id, $item_kit_id, $location_id)
	{
		$this->db->from('location_item_kits_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->where('location_id',$location_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
		
	}
	
	function save_item_tiers($tier_data,$item_kit_id, $location_id)
	{	
		if($this->tier_exists($tier_data['tier_id'],$item_kit_id,$location_id))
		{
			$this->db->where('tier_id', $tier_data['tier_id']);
			$this->db->where('item_kit_id', $item_kit_id);
			$this->db->where('location_id', $location_id);

			return $this->db->update('location_item_kits_tier_prices',$tier_data);
			
		}

		return $this->db->insert('location_item_kits_tier_prices',$tier_data);	
	}

	function is_tier_overwritten($item_kit_id, $location_id)
	{
		$this->db->from('location_item_kits_tier_prices');
		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->where('location_id',$location_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
	}
}
?>
