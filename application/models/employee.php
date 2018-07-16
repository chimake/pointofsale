<?php
class Employee extends Person
{
	/*
	Determines if a given person_id is an employee
	*/
	function exists($person_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
		
	function employee_username_exists($username)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.username',$username);
		$query = $this->db->get();
		
		
		if($query->num_rows()==1)
		{
			return $query->row()->username;
		}
	}	
	
	/*
	Returns all the employees
	*/
	function get_all($limit=10000, $offset=0,$col='last_name',$order='asc')
	{	
		$employees=$this->db->dbprefix('employees');
		$people=$this->db->dbprefix('people');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$employees." ON 										                       
						".$people.".person_id = ".$employees.".person_id
						WHERE deleted =0 ORDER BY ".$col." ". $order." 
						LIMIT  ".$offset.",".$limit);		
						
		return $data;
	}
	
	function count_all()
	{
		$this->db->from('employees');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular employee
	*/
	function get_info($employee_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id',$employee_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from employee table
			$fields = $this->db->list_fields('employees');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple employees
	*/
	function get_multiple_info($employee_ids)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');		
		$this->db->where_in('employees.person_id',$employee_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates an employee
	*/
	function save(&$person_data, &$employee_data,&$permission_data, &$permission_action_data, &$location_data, $employee_id=false)
	{
		$success=false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
			
		if(parent::save($person_data,$employee_id))
		{
			if (!$employee_id or !$this->exists($employee_id))
			{
				$employee_data['person_id'] = $employee_id = $person_data['person_id'];
				$success = $this->db->insert('employees',$employee_data);
			}
			else
			{
				$this->db->where('person_id', $employee_id);
				$success = $this->db->update('employees',$employee_data);		
			}
			
			//We have either inserted or updated a new employee, now lets set permissions. 
			if($success)
			{
				//First lets clear out any permissions the employee currently has.
				$success=$this->db->delete('permissions', array('person_id' => $employee_id));
				
				//Now insert the new permissions
				if($success)
				{
					foreach($permission_data as $allowed_module)
					{
						$success = $this->db->insert('permissions',
						array(
						'module_id'=>$allowed_module,
						'person_id'=>$employee_id));
					}
				}
				
				//First lets clear out any permissions actions the employee currently has.
				$success=$this->db->delete('permissions_actions', array('person_id' => $employee_id));
				
				//Now insert the new permissions actions
				if($success)
				{
					foreach($permission_action_data as $permission_action)
					{
						list($module, $action) = explode('|', $permission_action);
						$success = $this->db->insert('permissions_actions',
						array(
						'module_id'=>$module,
						'action_id'=>$action,
						'person_id'=>$employee_id));
					}
				}
				
				$success=$this->db->delete('employees_locations', array('employee_id' => $employee_id));
				
				//Now insert the new employee locations
				if($success)
				{
					if ($location_data !== FALSE)
					{
						foreach($location_data as $location_id)
						{
							$success = $this->db->insert('employees_locations',
							array(
							'employee_id'=>$employee_id,
							'location_id'=>$location_id
							));
						}
				
					}
				}
				
			}
			
		}
		
		$this->db->trans_complete();		
		return $success;
	}
	
	/*
	Deletes one employee
	*/
	function delete($employee_id)
	{
		$success=false;
		
		//Don't let employee delete their self
		if($employee_id==$this->get_logged_in_employee_info()->person_id)
			return false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$employee_info = $this->Employee->get_info($employee_id);
	
		if ($employee_info->image_id !== NULL)
		{
			$this->Person->update_image(NULL,$employee_id);
			$this->Appfile->delete($employee_info->image_id);			
		}			
		
		//Delete permissions
		if($this->db->delete('permissions', array('person_id' => $employee_id)) && $this->db->delete('permissions_actions', array('person_id' => $employee_id)))
		{	
			$this->db->where('person_id', $employee_id);
			$success = $this->db->update('employees', array('deleted' => 1));
		}
		$this->db->trans_complete();		
		return $success;
	}
	
	/*
	Deletes a list of employees
	*/
	function delete_list($employee_ids)
	{
		$success=false;
		
		//Don't let employee delete their self
		if(in_array($this->get_logged_in_employee_info()->person_id,$employee_ids))
			return false;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		foreach($employee_ids as $employee_id)
		{
			$employee_info = $this->Employee->get_info($employee_id);
		
			if ($employee_info->image_id !== NULL)
			{
				$this->Person->update_image(NULL,$employee_id);
				$this->Appfile->delete($employee_info->image_id);			
			}			
		}
		
		$this->db->where_in('person_id',$employee_ids);
		//Delete permissions
		if ($this->db->delete('permissions'))
		{
			//delete from employee table
			$this->db->where_in('person_id',$employee_ids);
			$success = $this->db->update('employees', array('deleted' => 1));
		}
		$this->db->trans_complete();		
		return $success;
 	}
	
		
	function check_duplicate($term)
	{
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$query = $this->db->where("CONCAT(first_name,' ',last_name) = ".$this->db->escape($term));
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
		
		
	}
	/*
	Get search suggestions to find employees
	*/
	function get_search_suggestions($search,$limit=5)
	{
		$suggestions = array();
		
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		
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
		
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
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
		
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("username",$search);
		$this->db->limit($limit);
		$by_username = $this->db->get();
		foreach($by_username->result() as $row)
		{
			$suggestions[]=array('label'=> $row->username);		
		}


		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("phone_number",$search);
		$this->db->limit($limit);
		$by_phone = $this->db->get();
		$temp_suggestions = array();
		foreach($by_phone->result() as $row)
		{
			$temp_suggestions[]=$row->phone_number;		
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
	Preform a search on employees
	*/
	function search($search, $limit=20,$offset=0,$column='last_name',$orderby='asc')
	{
			$this->db->from('employees');
			$this->db->join('people','employees.person_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			username LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}
	
	function search_count_all($search, $limit=10000)
	{
			$this->db->from('employees');
			$this->db->join('people','employees.person_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			username LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}
	
	/*
	Attempts to login employee and set session. Returns boolean based on outcome.
	*/
	function login($username, $password)
	{
		$query = $this->db->get_where('employees', array('username' => $username,'password'=>md5($password), 'deleted'=>0), 1);
		if ($query->num_rows() ==1)
		{
			$row=$query->row();
			$this->session->set_userdata('person_id', $row->person_id);
			return true;
		}
		return false;
	}
	
	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	
	/*
	Determins if a employee is logged in
	*/
	function is_logged_in()
	{
		return $this->session->userdata('person_id')!=false;
	}
	
	/*
	Gets information about the currently logged in employee.
	*/
	function get_logged_in_employee_info()
	{
		if($this->is_logged_in())
		{
			return $this->get_info($this->session->userdata('person_id'));
		}
		
		return false;
	}
	
	/*
	Gets the current employee's location. If they have more than 1, then a user can change during session
	*/
	function get_logged_in_employee_current_location_id()
	{
		if($this->is_logged_in())
		{
			//If we have a location in the session
			if ($this->session->userdata('employee_current_location_id')!==FALSE)
			{
				return $this->session->userdata('employee_current_location_id');
			}
			
			//Return the first location user is authenticated for
			return current($this->get_authenticated_location_ids($this->session->userdata('person_id')));
		}
		
		return FALSE;
	}
	
	function get_current_location_info()
	{
		return $this->Location->get_info($this->get_logged_in_employee_current_location_id());
	}
		
	function set_employee_current_location_id($location_id)
	{
		if ($this->is_location_authenticated($location_id))
		{
			$this->session->set_userdata('employee_current_location_id', $location_id);
		}
	}
	
	/*
	Determins whether the employee specified employee has access the specific module.
	*/
	function has_module_permission($module_id,$person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		$query = $this->db->get_where('permissions', array('person_id' => $person_id,'module_id'=>$module_id), 1);
		return $query->num_rows() == 1;
	}
	
	function has_module_action_permission($module_id, $action_id, $person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		$query = $this->db->get_where('permissions_actions', array('person_id' => $person_id,'module_id'=>$module_id,'action_id'=>$action_id), 1);
		return $query->num_rows() == 1;
	}
	
	function get_employee_by_username_or_email($username_or_email)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('username',$username_or_email);
		$this->db->or_where('email',$username_or_email);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		
		return false;
	}
	
	function update_employee_password($employee_id, $password)
	{
		$employee_data = array('password' => $password);
		$this->db->where('person_id', $employee_id);
		$success = $this->db->update('employees',$employee_data);
		
		return $success;
	}
		
	function cleanup()
	{
		$employee_data = array('username' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('employees',$employee_data);
	}
		
	function get_employee_id($username)
	{
		$query = $this->db->get_where('employees', array('username' => $username, 'deleted'=>0), 1);
		if ($query->num_rows() ==1)
		{
			$row=$query->row();
			return $row->person_id;
		}
		return false;
	}
	
	function get_authenticated_location_ids($employee_id)
	{
		$this->db->select('employees_locations.location_id');
		$this->db->from('employees_locations');
		$this->db->join('locations', 'locations.location_id = employees_locations.location_id');
		$this->db->where('employee_id', $employee_id);
		$this->db->where('deleted', 0);
		$this->db->order_by('location_id', 'asc');
		
		$location_ids = array();
		
		foreach($this->db->get()->result_array() as $location)
		{
			$location_ids[] = $location['location_id'];
		}
		
		return $location_ids;
	}
	
	function is_location_authenticated($location_id)
	{
		if ($employee = $this->get_logged_in_employee_info())
		{
			$this->db->select('location_id');
			$this->db->from('employees_locations');
			$this->db->where('employee_id', $employee->person_id);
			$this->db->where('location_id', $location_id);
			$result = $this->db->get();

			return $result->num_rows() == 1;
		}
		
		return FALSE;
	}
}
?>
