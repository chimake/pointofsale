<?php
function get_all_mailchimps_lists()
{
	$CI =& get_instance();
	$CI->load->library('mcapi', array('apikey' => $CI->Location->get_info_for_key('mailchimp_api_key')));
	$lists = $CI->mcapi->lists();
	return $lists['data'];
}

function email_subscribed_to_list($email, $list_id)
{
	$CI =& get_instance();
	$CI->load->library('mcapi', array('apikey' => $CI->Location->get_info_for_key('mailchimp_api_key')));
	
	foreach(get_mailchimp_lists($email) as $list)
	{
		if ($list['id'] == $list_id)
		{
			return true;
		}
	}
	return false;
}

function get_mailchimp_lists($email)
{
	static $lists = array();
	$CI =& get_instance();
	$CI->load->library('mcapi', array('apikey' => $CI->Location->get_info_for_key('mailchimp_api_key')));
		
	if (!$lists)
	{
		$lists = $CI->mcapi->lists();
	}
	$list_ids_subscribed = $CI->mcapi->listsForEmail($email) ? $CI->mcapi->listsForEmail($email) : array();
	$lists_subscribed = array();
	foreach($lists['data'] as $list)
	{
		if (in_array($list['id'], $list_ids_subscribed))
		{
			$lists_subscribed[] = $list;
		}
	}
	
	return $lists_subscribed;
}

function get_mailchimp_lists_string($email)
{
	$lists = array();
	foreach(get_mailchimp_lists($email) as $list)
	{
		$lists[] = $list['name'];
	}
	
	return implode(', ', $lists);
}
?>