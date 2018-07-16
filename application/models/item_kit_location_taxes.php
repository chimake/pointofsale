<?php
class Item_kit_location_taxes extends CI_Model
{
	/*
	Gets tax info for a particular item
	*/
	function get_info($item_kit_id, $location_id = false)
	{
		if(!$location_id)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('location_item_kits_taxes');
		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->where('location_id',$location_id);
		$this->db->order_by('cumulative');
		$this->db->order_by('id');
		//return an array of taxes for an item
		return $this->db->get()->result_array();
	}
	
	/*
	Inserts or updates an item's taxes
	*/
	function save(&$location_item_kits_taxes_data, $item_kit_id, $location_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$current_taxes = $this->get_info($item_kit_id, $location_id);

		//Delete and add
		if (count($current_taxes) != count($location_item_kits_taxes_data))
		{
			$this->delete($item_kit_id, $location_id);
		
			foreach ($location_item_kits_taxes_data as $row)
			{
				$row['item_kit_id'] = $item_kit_id;
				$row['location_id'] = $location_id;
				$this->db->insert('location_item_kits_taxes',$row);		
			}
		}
		else //Update
		{
			for($k=0;$k<count($current_taxes);$k++)
			{
				$current_tax = $current_taxes[$k];
				$new_tax = $location_item_kits_taxes_data[$k];
				
				$this->db->where('id', $current_tax['id']);
				$this->db->update('location_item_kits_taxes', $new_tax);
			}
			
		}
		$this->db->trans_complete();
		return true;
	}
	
	/*
	Deletes taxes given an item
	*/
	function delete($item_kit_id, $location_id)
	{
		return $this->db->delete('location_item_kits_taxes', array('item_kit_id' => $item_kit_id,'location_id' => $location_id)); 
	}
}
?>
