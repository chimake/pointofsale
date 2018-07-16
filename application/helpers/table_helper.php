<?php
/*
Gets the html table to manage people.
*/
function get_people_manage_table($people,$controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter table table-bordered  table-hover" id="sortable_table">';	
	$controller_name=strtolower(get_class($CI));

	if($controller_name=='customers' &&	$CI->config->item('customers_store_accounts'))
	{
		$headers = array('<input type="checkbox" id="select_all" />', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		lang('customers_balance'),
		'&nbsp',
		'&nbsp');
	
	}
	else 
	{	
		$headers = array('<input type="checkbox" id="select_all" />', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		'&nbsp');
	}
	$table.='<thead><tr>';
	
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_people_manage_table_data_rows($people,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the people.
*/
function get_people_manage_table_data_rows($people,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($people->result() as $person)
	{
		$table_data_rows.=get_person_data_row($person,$controller);
	}
	
	if($people->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='7'><span class='col-md-12 text-center text-warning' >".lang('common_no_persons_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_person_data_row($person,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$avatar_url=$person->image_id ?  site_url('app_files/view/'.$person->image_id) : false;
	$start_of_time =  date('Y-m-d', 0);
	$today = date('Y-m-d').' 23:59:59';	
	$link = site_url('reports/specific_'.($controller_name == 'customers' ? 'customer' : 'employee').'/'.$start_of_time.'/'.$today.'/'.$person->person_id.'/all/0');
	$table_data_row='<tr>';	

	$table_data_row.="<td><input type='checkbox' id='person_$person->person_id' value='".$person->person_id."'/></td>";
	$table_data_row.='<td>'.$person->person_id.'</td>';
	$table_data_row.='<td ><a href="'.$link.'" class="underline">'.H($person->last_name).'</a></td>';
	$table_data_row.='<td><a href="'.$link.'" class="underline">'.H($person->first_name).'</a></td>';
	$table_data_row.='<td>'.mailto(H($person->email),H($person->email), array('class' => 'underline')).'</td>';
	$table_data_row.='<td>'.H($person->phone_number).'</td>';	
	if($controller_name=='customers' && $CI->config->item('customers_store_accounts'))
	{	
		$table_data_row.='<td width="15%">'.to_currency($person->balance).'</td>';		
		$table_data_row.='<td width="5%">'.anchor($controller_name."/pay_now/$person->person_id",lang('customers_pay'),array('title'=>lang('customers_pay'))).'</td>';
	}
	$table_data_row.='<td>'.anchor($controller_name."/view/$person->person_id/2", lang('common_edit'),array('class'=>'update-person','title'=>lang($controller_name.'_update'))).'</td>';
	
	if ($avatar_url)
	{
		$table_data_row.="<td width='55px' align='center'><a href='$avatar_url' class='rollover'><img id='avatar' src='".$avatar_url."' class='img-polaroid' width='45' /></a></td>";
	}
	$table_data_row.='</tr>';
	
	return $table_data_row;
}

/*
Gets the html table to manage suppliers.
*/
function get_supplier_manage_table($suppliers,$controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter table table-bordered table-striped table-hover" id="sortable_table">';	
	$headers = array('<input type="checkbox" id="select_all" />',
	lang('suppliers_company_name'),
	lang('common_last_name'),
	lang('common_first_name'),
	lang('common_email'),
	lang('common_phone_number'),
	'&nbsp',
	'&nbsp');
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	
	$table.='</tr></thead><tbody>';
	$table.=get_supplier_manage_table_data_rows($suppliers,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the supplier.
*/
function get_supplier_manage_table_data_rows($suppliers,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($suppliers->result() as $supplier)
	{
		$table_data_rows.=get_supplier_data_row($supplier,$controller);
	}
	
	if($suppliers->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='8'><span class='col-md-12 text-center text-warning' >".lang('common_no_persons_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_supplier_data_row($supplier,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$avatar_url=$supplier->image_id ?  site_url('app_files/view/'.$supplier->image_id) : false;

	$table_data_row='<tr>';
	$table_data_row.="<td ><input type='checkbox' id='person_$supplier->person_id' value='".$supplier->person_id."'/></td>";
	$table_data_row.='<td >'.H($supplier->company_name).'</td>';
	$table_data_row.='<td >'.H($supplier->last_name).'</td>';
	$table_data_row.='<td >'.H($supplier->first_name).'</td>';
	$table_data_row.='<td >'.mailto(H($supplier->email),H($supplier->email)).'</td>';
	$table_data_row.='<td >'.H($supplier->phone_number).'</td>';		
	$table_data_row.='<td class="rightmost">'.anchor($controller_name."/view/$supplier->person_id/2", lang('common_edit')).'</td>';				
	if ($avatar_url)
	{
		$table_data_row.="<td width='55px' align='center'><a href='$avatar_url' class='rollover'><img id='avatar' src='".$avatar_url."' class='img-polaroid' width='45' /></a></td>";
	}
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage items.
*/
function get_items_manage_table($items,$controller)
{
	$CI =& get_instance();
	$has_cost_price_permission = $CI->Employee->has_module_action_permission('items','see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
	$table='<table class="table tablesorter table-bordered table-striped table-hover" id="sortable_table">';	


	if ($has_cost_price_permission)
	{
		$headers = array('<input type="checkbox" id="select_all" />', 
		$CI->lang->line('items_item_id'),
		$CI->lang->line('items_item_number'),
		$CI->lang->line('items_name'),
		$CI->lang->line('items_category'),
		$CI->lang->line('items_cost_price'),
		$CI->lang->line('items_unit_price'),
		$CI->lang->line('items_quantity'),
		$CI->lang->line('items_inventory'),
		'&nbsp;',
		'&nbsp;'
		);
	}
	else 
	{
		$headers = array('<input type="checkbox" id="select_all" />', 
		$CI->lang->line('items_item_id'),
		$CI->lang->line('items_item_number'),
		$CI->lang->line('items_name'),
		$CI->lang->line('items_category'),
		$CI->lang->line('items_unit_price'),
		$CI->lang->line('items_quantity'),
		$CI->lang->line('items_inventory'),
		'&nbsp;',
		'&nbsp;'
		);
		
	}
		
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_items_manage_table_data_rows($items,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the items.
*/
function get_items_manage_table_data_rows($items,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($items->result() as $item)
	{
		$table_data_rows.=get_item_data_row($item,$controller);
	}
	
	if($items->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('items_no_items_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_item_data_row($item,$controller)
{
	$CI =& get_instance();
	static $has_cost_price_permission;
		
	if (!$has_cost_price_permission)
	{
		$has_cost_price_permission = $CI->Employee->has_module_action_permission('items','see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
	}
	
	$controller_name=strtolower(get_class($CI));

	$avatar_url=$item->image_id ?  site_url('app_files/view/'.$item->image_id) : false;

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='item_$item->item_id' value='".$item->item_id."'/></td>";
	$table_data_row.='<td width="10%">'.$item->item_id.'</td>';
	$table_data_row.='<td width="15%">'.H($item->item_number).'</td>';
	$table_data_row.='<td width="15%">'.H($item->name).'</td>';
	$table_data_row.='<td width="11%">'.H($item->category).'</td>';
	if($has_cost_price_permission)
	{
		$table_data_row.='<td width="11%" align="right">'.to_currency($item->location_cost_price ? $item->location_cost_price: $item->cost_price, 10).'</td>';
	}
	$table_data_row.='<td width="11%" align="right">'.to_currency($item->location_unit_price ? $item->location_unit_price : $item->unit_price, 10).'</td>';
	$table_data_row.='<td width="11%">'.to_quantity($item->quantity).'</td>';
	
	if (!$item->is_service)
	{
		$table_data_row.='<td width="12%">'.anchor($controller_name."/inventory/$item->item_id/", lang('common_inv'),array('class'=>'','title'=>lang($controller_name.'_count'))).'</td>';//inventory details	
	
	}
	else
	{
		$table_data_row.='<td width="12%">&nbsp;</td>';
		
	}
	
	$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/view/$item->item_id/2	", lang('common_edit'),array('class'=>'','title'=>lang($controller_name.'_update'))).'</td>';		
	
	if ($avatar_url)
	{	
		$table_data_row.="<td width='55px' align='center'><a href='$avatar_url' class='rollover'><img id='avatar' src='".$avatar_url."' class='img-polaroid' width='45' /></a></td>";
	}
	
	$table_data_row.='</tr>';
	return $table_data_row;
}


/*
Gets the html table to manage items.
*/
function get_locations_manage_table($locations,$controller)
{
	$CI =& get_instance();
	$table='<table class="table tablesorter table-bordered table-striped table-hover" id="sortable_table">';	

		$headers = array('<input type="checkbox" id="select_all" />', 
		$CI->lang->line('locations_location_id'),
		$CI->lang->line('locations_name'),
		$CI->lang->line('locations_address'),
		$CI->lang->line('locations_phone'),
		$CI->lang->line('locations_email'),
		'&nbsp;'
		);
		

		
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_locations_manage_table_data_rows($locations,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the items.
*/
function get_locations_manage_table_data_rows($locations,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($locations->result() as $location)
	{
		$table_data_rows.=get_location_data_row($location,$controller);
	}
	
	if($locations->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('locations_no_locations_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_location_data_row($location,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='location_$location->location_id' value='".$location->location_id."'/></td>";
	$table_data_row.='<td width="10%">'.$location->location_id.'</td>';
	$table_data_row.='<td width="15%">'.H($location->name).'</td>';
	$table_data_row.='<td width="15%">'.H($location->address).'</td>';
	$table_data_row.='<td width="11%">'.H($location->phone).'</td>';
	$table_data_row.='<td width="11%">'.H($location->email).'</td>';
	$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/view/$location->location_id/2	", lang('common_edit'),array('class'=>'','title'=>lang($controller_name.'_update'))).'</td>';		
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage giftcards.
*/
function get_giftcards_manage_table( $giftcards, $controller )
{
	$CI =& get_instance();
	
	$table='<table class="tablesorter table table-bordered table-striped table-hover" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" />', 
	lang('giftcards_giftcard_number'),
	lang('giftcards_card_value'),
	lang('giftcards_customer_name'),
	'&nbsp', 
	);
	
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_giftcards_manage_table_data_rows( $giftcards, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the giftcard.
*/
function get_giftcards_manage_table_data_rows( $giftcards, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($giftcards->result() as $giftcard)
	{
		$table_data_rows.=get_giftcard_data_row( $giftcard, $controller );
	}
	
	if($giftcards->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('giftcards_no_giftcards_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_giftcard_data_row($giftcard,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$link = site_url('reports/detailed_'.$controller_name.'/'.$giftcard->customer_id.'/0');
	$cust_info = $CI->Customer->get_info($giftcard->customer_id);
	
	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='giftcard_$giftcard->giftcard_id' value='".$giftcard->giftcard_id."'/></td>";
	$table_data_row.='<td width="20%">'.H($giftcard->giftcard_number).'</td>';
	$table_data_row.='<td width="25%">'.to_currency(H($giftcard->value), 10).'</td>';
	$table_data_row.='<td width="20%"><a class="underline" href="'.$link.'">'.H($cust_info->first_name). ' '.H($cust_info->last_name).'</a></td>';
	$table_data_row.='<td width="5%" class="rightmost">'.anchor($controller_name."/view/$giftcard->giftcard_id/2	", lang('common_edit'),array('class'=>'','title'=>lang($controller_name.'_update'))).'</td>';		
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage item kits.
*/
function get_item_kits_manage_table( $item_kits, $controller )
{
	$CI =& get_instance();
	
	$table='<table class="tablesorter table table-bordered table-striped table-hover" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" />', 
	lang('items_item_number'),
	lang('item_kits_name'),
	lang('item_kits_description'),
	lang('items_cost_price'),
	lang('items_unit_price'),
	'&nbsp', 
	);
	
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_item_kits_manage_table_data_rows( $item_kits, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the item kits.
*/
function get_item_kits_manage_table_data_rows( $item_kits, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($item_kits->result() as $item_kit)
	{
		$table_data_rows.=get_item_kit_data_row( $item_kit, $controller );
	}
	
	if($item_kits->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('item_kits_no_item_kits_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_item_kit_data_row($item_kit,$controller)
{

	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	
	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='item_kit_$item_kit->item_kit_id' value='".$item_kit->item_kit_id."'/></td>";
	$table_data_row.='<td width="15%">'.H($item_kit->item_kit_number).'</td>';
	$table_data_row.='<td width="15%">'.H($item_kit->name).'</td>';
	$table_data_row.='<td width="20%">'.H($item_kit->description).'</td>';
	$table_data_row.='<td width="20%" align="right">'.(!is_null($item_kit->cost_price) ? to_currency(($item_kit->location_cost_price ? $item_kit->location_cost_price : $item_kit->cost_price), 10) : '').'</td>';
	$table_data_row.='<td width="20%" align="right">'.(!is_null($item_kit->unit_price) ? to_currency(($item_kit->location_unit_price ? $item_kit->location_unit_price : $item_kit->unit_price), 10) : '').'</td>';
	$table_data_row.='<td width="5%" class="rightmost">'.anchor($controller_name."/view/$item_kit->item_kit_id/2	", lang('common_edit'),array('class'=>'','title'=>lang($controller_name.'_update'))).'</td>';
	$table_data_row.='</tr>';
	return $table_data_row;
}

?>