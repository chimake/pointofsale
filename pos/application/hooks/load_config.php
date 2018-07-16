<?php
//Loads configuration from database into global CI config
function load_config()
{
	$CI =& get_instance();
	foreach($CI->Appconfig->get_all()->result() as $app_config)
	{
		$CI->config->set_item($app_config->key,$app_config->value);
	}
	
	if($CI->Employee->is_logged_in() and $CI->Employee->get_logged_in_employee_info()->language)
	{
		$CI->lang->switch_to($CI->Employee->get_logged_in_employee_info()->language);
	}
	else if ($CI->config->item('language'))
	{
		$CI->lang->switch_to($CI->config->item('language'));
	}
	
	if ($CI->Location->get_info_for_key('timezone'))
	{
		date_default_timezone_set($CI->Location->get_info_for_key('timezone'));
	}
	else
	{
		date_default_timezone_set('America/New_York');
	}
}
?>