<?php
function character_limiter($str, $n = 500, $end_char = '&#8230;')
{
	if (strlen($str) < $n)
	{
		return $str;
	}

	return substr($str,0, $n).$end_char;
}

function replace_newline($string) 
{
	return (string)str_replace(array("\r", "\r\n", "\n"), '', $string);
}

function number_pad($number,$n) 
{
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}

function get_date_format()
{
	$CI =& get_instance();
	switch($CI->config->item('date_format'))
	{
		case "middle_endian":
			return "m/d/Y";
		case "little_endian":
			return "d-m-Y";
		case "big_endian":
			return "Y-m-d";
		default:
			return "m/d/Y";
	}
}

function get_flot_date_format()
{
	$CI =& get_instance();
	switch($CI->config->item('date_format'))
	{
		case "middle_endian":
			return "%m/%d/%y";
		case "little_endian":
			return "%d-%m-%y";
		case "big_endian":
			return "%y-%m-%d";
		default:
			return "%m/%d/%y";
	}
}

function get_js_date_format()
{
	$CI =& get_instance();
	switch($CI->config->item('date_format'))
	{
		case "middle_endian":
			return "mm/dd/yyyy";
		case "little_endian":
			return "dd-mm-yyyy";
		case "big_endian":
			return "yyyy-mm-dd";
		default:
			return "mm/dd/yyyy";
	}
}

function get_js_start_of_time_date()
{
	$CI =& get_instance();
	switch($CI->config->item('date_format'))
	{
		case "middle_endian":
			return "01/01/1970";
		case "little_endian":
			return "01-01-1970";
		case "big_endian":
			return "1970-01-01";
		default:
			return "01/01/1970";
	}	
}

function get_time_format()
{
	$CI =& get_instance();
	switch($CI->config->item('time_format'))
	{
		case "12_hour":
			return "h:i a";
		case "24_hour":
			return "H:i";
		default:
			return "h:i a";
	}
}

function H($input)
{
	return htmlentities($input, ENT_QUOTES, 'UTF-8', false);
}
?>