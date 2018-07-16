<?php
class Secure_area extends CI_Controller 
{
	var $module_id;
	
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		parent::__construct();
		$this->module_id = $module_id;	
		$this->load->model('Employee');
		$this->load->model('Location');
		if(!$this->Employee->is_logged_in())
		{
			redirect('login');
		}
		
		if(!$this->Employee->has_module_permission($this->module_id,$this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
		
		//load up global data
		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$data['allowed_modules']=$this->Module->get_allowed_modules($logged_in_employee_info->person_id);
		$data['user_info']=$logged_in_employee_info;
		
		$locations_list=$this->Location->get_all();
		$authenticated_locations = $this->Employee->get_authenticated_location_ids($logged_in_employee_info->person_id);
		$locations = array();
		foreach($locations_list->result() as $row)
		{
			if(in_array($row->location_id, $authenticated_locations))
			{
				$locations[$row->location_id] =$row->name;
			}
		}
		
		$data['authenticated_locations'] = $locations;
		$this->load->vars($data);
	}
	
	function check_action_permission($action_id)
	{
		if (!$this->Employee->has_module_action_permission($this->module_id, $action_id, $this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
	}
	
 //See (modified from) http://ha17.com/1745-bigip-f5-header-max-size-collides-with-codeigniters-bizarre-session-class/
function removeDuplicateSessionCookieHeaders ()
{
	//php < 5.3 doesn't have header remove so this function will fatal error otherwise
	if (function_exists('header_remove'))
	{
		 $CI = &get_instance();

		// clean up all the cookies that are set...
		$headers             = headers_list();
		$cookies_to_output   = array ();
		$header_session_cookie = '';
		$session_cookie_name = $CI->config->item('sess_cookie_name');
	 
		foreach ($headers as $header)
		{
			list ($header_type, $data) = explode (':', $header, 2);
			$header_type = trim ($header_type);
			$data        = trim ($data);

			if (strtolower ($header_type) == 'set-cookie')
			{
				header_remove ('Set-Cookie'); 
			
				$cookie_value = current(explode (';', $data));
				list ($key, $val) = explode ('=', $cookie_value);
				$key = trim ($key);

				if ($key == $session_cookie_name)
				{
				   // OVERWRITE IT (yes! do it!)
				   $header_session_cookie = $data;
				   continue;
				} 
					else 
					{
				   // Not a session related cookie, add it as normal. Might be a CSRF or some other cookie we are setting
				   $cookies_to_output[] = array ('header_type' => $header_type, 'data' => $data);
				}
			}
		}

		if ( ! empty ($header_session_cookie))
		{
			$cookies_to_output[] = array ('header_type' => 'Set-Cookie', 'data' => $header_session_cookie);
		}

		foreach ($cookies_to_output as $cookie)
		{
			header ("{$cookie['header_type']}: {$cookie['data']}", false);
		}
	}
 }
}
?>