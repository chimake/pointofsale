<?php
function get_item_kits_barcode_data($item_kits_ids)
{
	$CI =& get_instance();	
	$result = array();

	$item_kit_ids = explode('~', $item_kits_ids);
	foreach ($item_kit_ids as $item_kit_id)
	{
		$item_kit_info = $CI->Item_kit->get_info($item_kit_id);
		$item_kit_location_info = $CI->Item_kit_location->get_info($item_kit_id);
		
		$item_kit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
		
		if($CI->config->item('barcode_price_include_tax'))
		{
			if($item_kit_info->tax_included)
			{
				$result[] = array('name' =>to_currency($item_kit_price).' '.$item_kit_info->name, 'id'=> 'KIT ' .number_pad($item_kit_id, 11));
			}
			else
			{				
				$result[] = array('name' =>to_currency(get_price_for_item_kit_including_taxes($item_kit_id,$item_kit_price)).': '.$item_kit_info->name, 'id'=> 'KIT ' .number_pad($item_kit_id, 11));
	  	 	}
	  }
	  else
	  {
		if ($item_kit_info->tax_included)
		{
		    $result[] = array('name' =>to_currency(get_price_for_item_kit_excluding_taxes($item_kit_id, $item_kit_price)).': '.$item_kit_info->name, 'id'=> 'KIT ' .number_pad($item_kit_id, 11));
		}
		else
		{
	    	$result[] = array('name' =>to_currency($item_kit_price).': '.$item_kit_info->name, 'id'=> 'KIT ' .number_pad($item_kit_id, 11));
	  	}
	  }
	}
	
	return $result;
}

function get_price_for_item_kit_excluding_taxes($item_kit_id, $item_kit_price_including_tax, $sale_id = FALSE)
{
	$return = FALSE;
	$CI =& get_instance();

	if ($sale_id !== FALSE)
	{
		$tax_info = $CI->Sale->get_sale_item_kits_taxes($sale_id,$item_kit_id);
	}	
	else
	{
		$tax_info = $CI->Item_kit_taxes_finder->get_info($item_kit_id);
	}
	
	if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1)
	{
		$return = $item_kit_price_including_tax/(1+($tax_info[0]['percent'] /100) + ($tax_info[1]['percent'] /100) + (($tax_info[0]['percent'] /100) * (($tax_info[1]['percent'] /100))));
	}
	else //0 or more taxes NOT cumulative
	{
		$total_tax_percent = 0;
		
		foreach($tax_info as $tax)
		{
			$total_tax_percent+=$tax['percent'];
		}
		
		$return = $item_kit_price_including_tax/(1+($total_tax_percent /100));
	}
	
	if ($return !== FALSE)
	{
		return to_currency_no_money($return, 10);
	}
	
	return FALSE;	
}

function get_price_for_item_kit_including_taxes($item_kit_id, $item_kit_price_excluding_tax, $sale_id = FALSE)
{
	$return = FALSE;
	$CI =& get_instance();
	
	if ($sale_id !== FALSE)
	{
		$tax_info = $CI->Sale->get_sale_item_kits_taxes($sale_id,$item_kit_id);
	}	
	else
	{
		$tax_info = $CI->Item_kit_taxes_finder->get_info($item_kit_id);
	}
	
	if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1)
	{
		$first_tax = ($item_kit_price_excluding_tax*($tax_info[0]['percent']/100));
		$second_tax = ($item_kit_price_excluding_tax + $first_tax) *($tax_info[1]['percent']/100);
		$return = $item_kit_price_excluding_tax + $first_tax + $second_tax;
	}	
	else //0 or more taxes NOT cumulative
	{
		$total_tax_percent = 0;
		
		foreach($tax_info as $tax)
		{
			$total_tax_percent+=$tax['percent'];
		}
		
		$return = $item_kit_price_excluding_tax*(1+($total_tax_percent /100));
	}
	
	if ($return !== FALSE)
	{
		return to_currency_no_money($return, 10);
	}
	
	return FALSE;
}
?>