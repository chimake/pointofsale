<?php
class Tier extends CI_Model
{
	/*
	Determines if a given tier_id is a tier
	*/
	function exists($tier_id)
	{
		$this->db->from('price_tiers');	
		$this->db->where('id',$tier_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function get_all()
	{
		$this->db->from('price_tiers');
		$this->db->order_by('id');
		return $this->db->get();
	}

	function count_all()
	{
		$this->db->from('price_tiers');
		return $this->db->count_all_results();
	}
	
	/*
	Inserts or updates a tier
	*/
	function save(&$tier_data,$tier_id=false)
	{
		if (!$tier_id or !$this->exists($tier_id))
		{
			if($this->db->insert('price_tiers',$tier_data))
			{
				$tier_data['id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('id', $tier_id);
		return $this->db->update('price_tiers',$tier_data);
	}
	
	function delete($tier_id)
	{
		//Make sure customers don't belong to tier anymore
		$this->db->where('tier_id', $tier_id);
		$this->db->update('customers', array('tier_id' => NULL));
		
		$this->db->where('id', $tier_id);
		return $this->db->delete('price_tiers'); 
	}

}
?>
