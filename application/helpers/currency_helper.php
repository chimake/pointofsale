<?php
function to_currency($number, $decimals = 2)
{
	$CI =& get_instance();
	$currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : '$';
	if($number >= 0)
	{
		$ret = $currency_symbol.number_format($number, $decimals, '.', ',');
    }
    else
    {
    	$ret = '&#8209;'.$currency_symbol.number_format(abs($number), $decimals, '.', ',');
    }

	return preg_replace('/(?<=\d{2})0+$/', '', $ret);
}

function round_to_nearest_05($amount)
{
	return round($amount * 2, 1) / 2;
}

function to_currency_no_money($number, $decimals = 2)
{
	$ret = number_format($number, $decimals, '.', '');
	return preg_replace('/(?<=\d{2})0+$/', '', $ret);
}

function to_quantity($val)
{
	if ($val !== NULL)
	{
		return $val == (int)$val ? (int)$val : rtrim($val, '0');		
	}
	
	return lang('common_not_set');
}
?>