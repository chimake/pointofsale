<?php
function get_items_barcode_data($item_ids)
{
	$CI =& get_instance();	
	$result = array();

	$item_ids = explode('~', $item_ids);
	foreach ($item_ids as $item_id)
	{
		$item_info = $CI->Item->get_info($item_id);
		$item_location_info = $CI->Item_location->get_info($item_id);
		
		$item_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
		
		if($CI->config->item('barcode_price_include_tax'))
		{
			if($item_info->tax_included)
			{
				$result[] = array('name' =>to_currency($item_price).': '.$item_info->name, 'id'=> number_pad($item_id, 11));
			}
			else
			{				
				$result[] = array('name' =>to_currency(get_price_for_item_including_taxes($item_id,$item_price)).': '.$item_info->name, 'id'=> number_pad($item_id, 11));
	  	 	}
	  }
	  else
	  {
		if ($item_info->tax_included)
		{
		    $result[] = array('name' =>to_currency(get_price_for_item_excluding_taxes($item_id, $item_price)).': '.$item_info->name, 'id'=> number_pad($item_id, 11));
		}
		else
		{
	    	$result[] = array('name' =>to_currency($item_price).': '.$item_info->name, 'id'=> number_pad($item_id, 11));
	  	}
	  }
	}
	return $result;
}

function get_price_for_item_excluding_taxes($item_id, $item_price_including_tax, $sale_id = FALSE)
{
	$return = FALSE;
	$CI =& get_instance();
	
	if ($sale_id !== FALSE)
	{
		$tax_info = $CI->Sale->get_sale_items_taxes($sale_id, $item_id);
	}	
	else
	{
		$tax_info = $CI->Item_taxes_finder->get_info($item_id);
	}
	
	if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1)
	{
		$return = $item_price_including_tax/(1+($tax_info[0]['percent'] /100) + ($tax_info[1]['percent'] /100) + (($tax_info[0]['percent'] /100) * (($tax_info[1]['percent'] /100))));
	}
	else //0 or more taxes NOT cumulative
	{
		$total_tax_percent = 0;
		
		foreach($tax_info as $tax)
		{
			$total_tax_percent+=$tax['percent'];
		}
		
		$return = $item_price_including_tax/(1+($total_tax_percent /100));
	}
	
	if ($return !== FALSE)
	{
		return to_currency_no_money($return, 10);
	}
	
	return FALSE;
}

function get_price_for_item_including_taxes($item_id, $item_price_excluding_tax, $sale_id = FALSE)
{
	$return = FALSE;
	$CI =& get_instance();
	if ($sale_id !== FALSE)
	{
		$tax_info = $CI->Sale->get_sale_items_taxes($sale_id,$item_id);
	}	
	else
	{
		$tax_info = $CI->Item_taxes_finder->get_info($item_id);
	}
	
	if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1)
	{
		$first_tax = ($item_price_excluding_tax*($tax_info[0]['percent']/100));
		$second_tax = ($item_price_excluding_tax + $first_tax) *($tax_info[1]['percent']/100);
		$return = $item_price_excluding_tax + $first_tax + $second_tax;
	}	
	else //0 or more taxes NOT cumulative
	{
		$total_tax_percent = 0;
		
		foreach($tax_info as $tax)
		{
			$total_tax_percent+=$tax['percent'];
		}
		
		$return = $item_price_excluding_tax*(1+($total_tax_percent /100));
	}

	
	if ($return !== FALSE)
	{
		return to_currency_no_money($return, 10);
	}
	
	return FALSE;
}
?>