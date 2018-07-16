<?php
class Login extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('encrypt');
	}
	
	function index()
	{
		$data = array();
		$data['username'] = is_on_demo_host() ? 'admin' : '';
		$data['password'] = is_on_demo_host() ? 'pointofsale' : '';
		if ($this->agent->browser() == 'Internet Explorer' && $this->agent->version() < 9)
		{
			$data['ie_browser_warning'] = TRUE;
		}
		else
		{
			$data['ie_browser_warning'] = FALSE;
		}
		if(APPLICATION_VERSION==$this->config->item('version'))
		{
			$data['application_mismatch']=false;
		}
		else
		{
			$data['application_mismatch']=lang('login_application_mismatch');
		}
		
		if($this->Employee->is_logged_in())
		{
			redirect('home');
		}
		else
		{
			$this->form_validation->set_rules('username', 'lang:login_username', 'callback_employee_location_check|callback_login_check');
    	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run() == FALSE)
			{
				//Only set the username when we have a non false value (not '' or FALSE)
				if ($this->input->post('username'))
				{					
					$data['username'] = $this->input->post('username');
				}
				include APPPATH.'config/database.php';
				
				//If we have a site configuration check to make sure the user has not cancelled
				if (isset($db['site']))
				{
					$site_db = $this->load->database('site', TRUE);
					
					if ($this->_is_subscription_cancelled($site_db))
					{
						if ($this->_is_subscription_cancelled_within_30_days($site_db))
						{
							$data['subscription_cancelled_within_30_days']  = TRUE;
							$this->load->view('login/login',$data);
						}
						else
						{
							$this->load->view('login/subscription_cancelled');
						}
					}
					else
					{
						$this->load->view('login/login', $data);
					}
				}
				else
				{
					$this->load->view('login/login',$data);
				}
			}
			else
			{
				redirect('home');
			}
		}
	}
	
	function _is_subscription_cancelled($site_db)
	{
		$phppos_client_name = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
		$site_db->select('subscr_status');	
		$site_db->from('subscriptions');	
		$site_db->where('username',$phppos_client_name);
		$site_db->where('subscr_status','cancelled');
		$query = $site_db->get();
		return ($query->num_rows() >= 1);
	}
	
	function _is_subscription_cancelled_within_30_days($site_db)
	{
		$phppos_client_name = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
		$thirty_days_ago = date('Y-m-d H:i:s', strtotime("now -30 days"));
		$site_db->select('subscr_status');	
		$site_db->from('subscriptions');	
		$site_db->where('username',$phppos_client_name);
		$site_db->where('subscr_status','cancelled');
		$site_db->where('cancel_date >', $thirty_days_ago);
		$query = $site_db->get();
		return ($query->num_rows() >= 1);
	}
	
	function login_check($username)
	{
		$password = $this->input->post("password");	
		
		if(!$this->Employee->login($username,$password))
		{
			$this->form_validation->set_message('login_check', lang('login_invalid_username_and_password'));
			return false;
		}
		return true;		
	}
	
	function employee_location_check($username)
	{		
		$employee_id = $this->Employee->get_employee_id($username);
		
		if ($employee_id)
		{
			$employee_location_count = count($this->Employee->get_authenticated_location_ids($employee_id));

			if ($employee_location_count < 1)
			{
				$this->form_validation->set_message('employee_location_check', lang('login_employee_is_not_assigned_to_any_locations'));
				return false;
			}
		}
		
		//Didn't find an employee, we can pass validation
		return true;
	}
	
	function switch_user()
	{
		if($this->input->post('password'))
		{
			if(!$this->Employee->login($this->input->post('username'),$this->input->post('password')))
			{
				echo json_encode(array('success'=>false,'message'=>lang('login_invalid_username_and_password')));
			}
			else
			{
				//Unset location in case the user doesn't have access to currently set location
				$this->session->unset_userdata('employee_current_location_id');
				echo json_encode(array('success'=>true));
			}
		}
		else
		{
			foreach($this->Employee->get_all()->result_array() as $row)
			{
				$employees[$row['username']] = $row['first_name'] .' '. $row['last_name'];
			}
			$data['employees']=$employees;

			$this->load->view('login/switch_user',$data);
			
		}
	}
	
	function reset_password()
	{
		$this->load->view('login/reset_password');
	}
	
	function do_reset_password_notify()
	{	
		if($this->input->post('username_or_email'))
		{
			$employee = $this->Employee->get_employee_by_username_or_email($this->input->post('username_or_email'));
			if ($employee)
			{
				$data = array();
				$data['employee'] = $employee;
			    $data['reset_key'] = base64url_encode($this->encrypt->encode($employee->person_id.'|'.(time() + (2 * 24 * 60 * 60))));
			
				$this->load->library('email');
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@phpsoftwares.com', $this->config->item('company'));
				$this->email->to($employee->email); 

				$this->email->subject(lang('login_reset_password'));
				$this->email->message($this->load->view("login/reset_password_email",$data, true));	
				$this->email->send();
			
				$data['success']=lang('login_password_reset_has_been_sent');
				$this->load->view('login/reset_password',$data);
			}
			else 
			{
				$data['error']=lang('login_username_or_email_does_not_exist');
				$this->load->view('login/reset_password',$data);
			}
		}
		else
		{
			$data['error']= lang('common_field_cannot_be_empty');
			$this->load->view('login/reset_password',$data);
		}
	}
	
	function reset_password_enter_password($key=false)
	{
		if ($key)
		{
			$data = array();
		    list($employee_id, $expire) = explode('|', $this->encrypt->decode(base64url_decode($key)));			
			if ($employee_id && $expire && $expire > time())
			{
				$employee = $this->Employee->get_info($employee_id);
				$data['username'] = $employee->username;
				$data['key'] = $key;
				$this->load->view('login/reset_password_enter_password', $data);			
			}
		}
	}
	
	function do_reset_password($key=false)
	{
		if ($key)
		{
	    	list($employee_id, $expire) = explode('|', $this->encrypt->decode(base64url_decode($key)));
			
			if ($employee_id && $expire && $expire > time())
			{
				$password = $this->input->post('password');
				$confirm_password = $this->input->post('confirm_password');
				
				if (($password == $confirm_password) && strlen($password) >=8)
				{
					if ($this->Employee->update_employee_password($employee_id, md5($password)))
					{
						$this->load->view('login/do_reset_password');	
					}
				}
				else
				{
					$data = array();
					$employee = $this->Employee->get_info($employee_id);
					$data['username'] = $employee->username;
					$data['key'] = $key;
					$data['error_message'] = lang('login_passwords_must_match_and_be_at_least_8_characters');
					$this->load->view('login/reset_password_enter_password', $data);
				}
			}
		}
	}
}
?>