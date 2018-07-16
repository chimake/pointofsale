<?php
class Appfile extends CI_Model 
{		
	function get($file_id)
	{
		$query = $this->db->get_where('app_files', array('file_id' => $file_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		
		return "";
		
	}
	
	function save($file_name,$file_data, $file_id = false)
	{
		$file_data=array(
		'file_name'=>$file_name,
		'file_data'=>$file_data
		);
				
		if (!$file_id)
		{
			if ($this->db->insert('app_files',$file_data))
			{
				return $this->db->insert_id();
			}
			
			return false;
		}
		
		$this->db->where('file_id', $file_id);
		if ($this->db->update('app_files',$file_data))
		{
			return $file_id;
		}
		
		return false;
	}
		
	function delete($file_id)
	{
		return $this->db->delete('app_files', array('file_id' => $file_id)); 
	}
}

?>