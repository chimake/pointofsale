<?php

class Item_kit_taxes extends CI_Model
{
	/*
	Gets tax info for a particular item kit
	*/
	function get_info($item_kit_id)
	{
		$this->db->from('item_kits_taxes');
		$this->db->where('item_kit_id',$item_kit_id);
		$this->db->order_by('cumulative');
		$this->db->order_by('id');
		//return an array of taxes for an item
		return $this->db->get()->result_array();
	}

	/*
	Inserts or updates an item kit's taxes
	*/
	function save(&$item_kits_taxes_data, $item_kit_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$current_taxes = $this->get_info($item_kit_id);

		//Delete and add
		if (count($current_taxes) != count($item_kits_taxes_data))
		{
			$this->delete($item_kit_id);
		
			foreach ($item_kits_taxes_data as $row)
			{
				$row['item_kit_id'] = $item_kit_id;
				$this->db->insert('item_kits_taxes',$row);		
			}
		}
		else //Update
		{
			for($k=0;$k<count($current_taxes);$k++)
			{
				$current_tax = $current_taxes[$k];
				$new_tax = $item_kits_taxes_data[$k];
				
				$this->db->where('id', $current_tax['id']);
				$this->db->update('item_kits_taxes', $new_tax);
			}
			
		}
		$this->db->trans_complete();
		return true;	
	}
	
	function save_multiple(&$item_kits_taxes_data, $item_kit_ids)
	{
		foreach($item_kit_ids as $item_kit_id)
		{
			$this->save($item_kits_taxes_data, $item_kit_id);
		}
	}

	/*
	Deletes taxes given an item
	*/
	function delete($item_kit_id)
	{
		return $this->db->delete('item_kits_taxes', array('item_kit_id' => $item_kit_id)); 
	}
}
?>
