<?php

function create_breadcrumb()
{
	$ci = &get_instance();
	$return = '';
	$dashboard_link = '<a href="'.site_url().'"><i class="fa fa-home"></i> '.lang('common_dashboard').'</a>';

	$return.=$dashboard_link;

	if ($ci->uri->segment(1) == 'customers')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$customers_home_link =create_current_page_url(lang('module_customers'));
		}
		else
		{
			$customers_home_link = '<a href="'.site_url('customers').'">'.lang('module_customers').'</a>';
		}
		
		$return.=$customers_home_link;
		
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('customers_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('customers_update'));
			}
		}
		elseif($ci->uri->segment(2) == 'excel_import')
		{
			$return.=create_current_page_url(lang('customers_import_customers_from_excel'));
		}
  	}
	elseif($ci->uri->segment(1) == 'items')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$items_home_link =create_current_page_url(lang('module_items'));
		}
		else
		{
			$items_home_link = '<a href="'.site_url('items').'">'.lang('module_items').'</a>';
		}
				
		$return.=$items_home_link;
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('items_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('items_update'));
			}
		}
		elseif($ci->uri->segment(2) == 'excel_import')
		{
			$return.=create_current_page_url(lang('common_excel_import'));
		}
	}
	elseif($ci->uri->segment(1) == 'item_kits')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$item_kits_home_link =create_current_page_url(lang('module_item_kits'));
		}
		else
		{
			$item_kits_home_link = '<a href="'.site_url('item_kits').'">'.lang('module_item_kits').'</a>';
		}
				
		$return.=$item_kits_home_link;
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('item_kits_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('item_kits_update'));
			}
		}
	}
	elseif($ci->uri->segment(1) == 'suppliers')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$suppliers_home_link =create_current_page_url(lang('module_suppliers'));
		}
		else
		{
			$suppliers_home_link = '<a href="'.site_url('suppliers').'">'.lang('module_suppliers').'</a>';
		}
				
		$return.=$suppliers_home_link;
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('suppliers_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('suppliers_update'));
			}
		}
		elseif($ci->uri->segment(2) == 'excel_import')
		{
			$return.=create_current_page_url(lang('suppliers_import_suppliers_from_excel'));
		}
	}
	elseif($ci->uri->segment(1) == 'reports')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$reports_home_link =create_current_page_url(lang('module_reports'));
		}
		else
		{
			$reports_home_link = '<a href="'.site_url('reports').'">'.lang('module_reports').'</a>';
		}
		
		$return.=$reports_home_link;
		
		if($ci->uri->segment(2) == 'graphical_summary_categories' || $ci->uri->segment(2) == 'summary_categories')
		{
			$return.=create_report_breadcrumb(lang('reports_categories_summary_report'));
		}
		elseif($ci->uri->segment(2) == 'sales_generator')
		{
			$return.=create_current_page_url(lang('reports_sales_generator'));
		}		
		elseif($ci->uri->segment(2) == 'graphical_summary_customers' || $ci->uri->segment(2) == 'summary_customers')
		{
			$return.=create_report_breadcrumb(lang('reports_customers_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'specific_customer')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_customers_report'));
		}		
		elseif($ci->uri->segment(2) == 'deleted_sales')
		{
			$return.=create_report_breadcrumb(lang('reports_deleted_sales_report'));				
		}		
		if($ci->uri->segment(2) == 'graphical_summary_discounts' || $ci->uri->segment(2) == 'summary_discounts')
		{
			$return.=create_report_breadcrumb(lang('reports_discounts_summary_report'));
		}
		elseif($ci->uri->segment(2) == 'graphical_summary_employees' || $ci->uri->segment(2) == 'summary_employees')
		{
			$return.=create_report_breadcrumb(lang('reports_employees_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'specific_employee')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_employees_report'));
		}		
		elseif($ci->uri->segment(2) == 'summary_giftcards')
		{
			$return.=create_report_breadcrumb(lang('reports_giftcard_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'detailed_giftcards')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_giftcards_report'));
		}		
		elseif($ci->uri->segment(2) == 'inventory_low')
		{
			$return.=create_report_breadcrumb(lang('reports_low_inventory_report'));	
		}		
		elseif($ci->uri->segment(2) == 'inventory_summary')
		{
			$return.=create_report_breadcrumb(lang('reports_inventory_summary'));		
		}		
		elseif($ci->uri->segment(2) == 'graphical_summary_item_kits' || $ci->uri->segment(2) == 'summary_item_kits')
		{
			$return.=create_report_breadcrumb(lang('reports_item_kits_summary_report'));	
		}		
		elseif($ci->uri->segment(2) == 'graphical_summary_items' || $ci->uri->segment(2) == 'summary_items')
		{
			$return.=create_report_breadcrumb(lang('reports_items_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'graphical_summary_payments' || $ci->uri->segment(2) == 'summary_payments')
		{
			$return.=create_report_breadcrumb(lang('reports_payments_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'summary_profit_and_loss')
		{
			$return.=create_report_breadcrumb(lang('reports_profit_and_loss'));
		}		
		elseif($ci->uri->segment(2) == 'detailed_profit_and_loss')
		{
			$return.=create_report_breadcrumb(lang('reports_profit_and_loss'));				
		}		
		elseif($ci->uri->segment(2) == 'detailed_receivings')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_receivings_report'));						
		}
		elseif($ci->uri->segment(2) == 'detailed_register_log')
		{
			$return.=create_report_breadcrumb(lang('reports_register_log_title'));						
		}		
		elseif($ci->uri->segment(2) == 'graphical_summary_sales' || $ci->uri->segment(2) == 'summary_sales')
		{
			$return.=create_report_breadcrumb(lang('reports_sales_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'detailed_sales')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_sales_report'));
		}		
		elseif($ci->uri->segment(2) == 'store_account_statements')
		{			
			$return.=create_report_breadcrumb(lang('reports_store_account_statements'));
		}		
		elseif($ci->uri->segment(2) == 'summary_store_accounts')
		{
			$return.=create_report_breadcrumb(lang('reports_store_account_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'specific_customer_store_account')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_store_accounts_report'));
		}
		elseif($ci->uri->segment(2) == 'graphical_summary_suppliers' || $ci->uri->segment(2) == 'summary_suppliers')
		{
			$return.=create_report_breadcrumb(lang('reports_suppliers_summary_report'));
		}		
		elseif($ci->uri->segment(2) == 'specific_supplier')
		{
			$return.=create_report_breadcrumb(lang('reports_detailed_suppliers_report'));	
		}		
		elseif($ci->uri->segment(2) == 'graphical_summary_taxes' || $ci->uri->segment(2) == 'summary_taxes')
		{
			$return.=create_report_breadcrumb(lang('reports_taxes_summary_report'));					
		}		
	}
	elseif ($ci->uri->segment(1) == 'employees')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$employees_home_link =create_current_page_url(lang('module_employees'));
		}
		else
		{
			$employees_home_link = '<a href="'.site_url('employees').'">'.lang('module_employees').'</a>';
		}
		
		$return.=$employees_home_link;
		
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('employees_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('employees_update'));
			}
		}
  	}
	elseif ($ci->uri->segment(1) == 'giftcards')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$giftcards_home_link =create_current_page_url(lang('module_giftcards'));
		}
		else
		{
			$giftcards_home_link = '<a href="'.site_url('giftcards').'">'.lang('module_giftcards').'</a>';
		}
		
		$return.=$giftcards_home_link;
		
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('giftcards_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('giftcards_update'));
			}
		}
  	}
	elseif($ci->uri->segment(1) == 'config')
	{

		if ($ci->uri->segment(2) == false) //Main page
		{
			$config_home_link =create_current_page_url(lang('module_config'));
		}
		else
		{
			$config_home_link = '<a href="'.site_url('config').'">'.lang('module_config').'</a>';
		}
		
		$return.=$config_home_link;
		
		
		if($ci->uri->segment(2) == 'backup')
		{
  			$return.=create_current_page_url(lang('config_backup_overview'));
		}
	}
	elseif ($ci->uri->segment(1) == 'locations')
	{
		if ($ci->uri->segment(2) == false) //Main page
		{
			$locations_home_link =create_current_page_url(lang('module_locations'));
		}
		else
		{
			$locations_home_link = '<a href="'.site_url('locations').'">'.lang('module_locations').'</a>';
		}
		
		$return.=$locations_home_link;
		
		
		if($ci->uri->segment(2) == 'view')
		{
			if ($ci->uri->segment(3) == -1)
			{
  				$return.=create_current_page_url(lang('locations_new'));
			}
			else
			{
  				$return.=create_current_page_url(lang('locations_update'));
			}
		}
  }
	elseif ($ci->uri->segment(1) == 'sales')
	{
		$sales_home_link = '<a href="'.site_url('sales').'">'.lang('module_sales').'</a>';
		$return.=$sales_home_link;
		if($ci->uri->segment(2) == 'suspended')
		{
			$return.=create_current_page_url(lang('sales_suspended_sales'));
		}
	}
	
  	return $return;
}

function create_current_page_url($link_text)
{
	return '<a  class="current" href="'.current_url().'">'.$link_text.'</a>';
}

function create_report_breadcrumb($report_name)
{
	$ci = &get_instance();

	$return = '';
	if ($ci->uri->segment(3) === FALSE) // Input page
	{
		$return.=create_current_page_url(lang('reports_report_input').': '.$report_name);
	}
	else
	{
		$return.= '<a href="'.site_url('reports/'.$ci->uri->segment(2)).'">'.lang('reports_report_input').': '.$report_name.'</a>';	
		$return.= create_current_page_url($report_name);
	}
	
	return $return;
}

?>