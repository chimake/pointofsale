<?php
class Item_location_taxes extends CI_Model
{
	/*
	Gets tax info for a particular item
	*/
	function get_info($item_id, $location_id = false)
	{
		if(!$location_id)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('location_items_taxes');
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location_id);
		$this->db->order_by('cumulative');
		$this->db->order_by('id');
		//return an array of taxes for an item
		return $this->db->get()->result_array();
	}
	
	/*
	Inserts or updates an item's taxes
	*/
	function save(&$location_items_taxes_data, $item_id, $location_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$current_taxes = $this->get_info($item_id, $location_id);

		//Delete and add
		if (count($current_taxes) != count($location_items_taxes_data))
		{
			$this->delete($item_id, $location_id);
		
			foreach ($location_items_taxes_data as $row)
			{
				$row['item_id'] = $item_id;
				$row['location_id'] = $location_id;
				$this->db->insert('location_items_taxes',$row);		
			}
		}
		else //Update
		{
			for($k=0;$k<count($current_taxes);$k++)
			{
				$current_tax = $current_taxes[$k];
				$new_tax = $location_items_taxes_data[$k];
				
				$this->db->where('id', $current_tax['id']);
				$this->db->update('location_items_taxes', $new_tax);
			}
			
		}
		$this->db->trans_complete();
		return true;
	}
	
	/*
	Deletes taxes given an item
	*/
	function delete($item_id, $location_id)
	{
		return $this->db->delete('location_items_taxes', array('item_id' => $item_id,'location_id' => $location_id)); 
	}
}
?>
