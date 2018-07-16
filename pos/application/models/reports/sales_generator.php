<?php
require_once("report.php");
class Sales_generator extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
						'summary' => array(
													array('data'=>lang('reports_sale_id'), 'align'=> 'left'), 
													array('data'=>lang('reports_date'), 'align'=> 'left'), 
													array('data'=>lang('reports_items_purchased'), 'align'=> 'left'), 
													array('data'=>lang('reports_sold_by'), 'align'=> 'left'), 
													array('data'=>lang('reports_sold_to'), 'align'=> 'left'), 
													array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
													array('data'=>lang('reports_total'), 'align'=> 'right'), 
													array('data'=>lang('reports_tax'), 'align'=> 'right'), 
													array('data'=>lang('reports_profit'), 'align'=> 'right'), 
													array('data'=>lang('reports_payment_type'), 'align'=> 'right'), 
													array('data'=>lang('reports_comments'), 'align'=> 'right')
													),
						'details' => array(
													array('data'=>lang('reports_item_number'), 'align'=> 'left'), 
													array('data'=>lang('items_product_id'), 'align'=> 'left'), 
													array('data'=>lang('reports_name'), 'align'=> 'left'), 
													array('data'=>lang('reports_category'), 'align'=> 'left'), 
													array('data'=>lang('reports_serial_number'), 'align'=> 'left'), 
													array('data'=>lang('reports_description'), 'align'=> 'left'), 
													array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left'), 
													array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
													array('data'=>lang('reports_total'), 'align'=> 'right'), 
													array('data'=>lang('reports_tax'), 'align'=> 'right'), 
													array('data'=>lang('reports_profit'), 'align'=> 'right'),
													array('data'=>lang('reports_discount'), 'align'=> 'right')
													)
					);		
	}
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();
		
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sale_id, sale_time, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
			$this->db->from('sales_items_temp');
			$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');			
			$this->_searchSalesQueryParams();
			$this->db->where('sales_items_temp.deleted', 0);
			$this->db->group_by('sale_id');
			$this->db->order_by('sale_date');
			
			//If we are exporting NOT exporting to excel make sure to use offset and limit
			if (isset($this->params['export_excel']) && !$this->params['export_excel'])
			{
				$this->db->limit($this->report_limit);
				$this->db->offset($this->params['offset']);
			}
					
			foreach($this->db->get()->result_array() as $sale_summary_row)
			{
				$data['summary'][$sale_summary_row['sale_id']] = $sale_summary_row; 
			}
		
			$sale_ids = array();
		
			foreach($data['summary'] as $sale_row)
			{
				$sale_ids[] = $sale_row['sale_id'];
			}
			
			$this->db->select('sale_id, items.item_id, item_kits.item_kit_id, sale_time, sale_date, item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, quantity_purchased as items_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent', false);
			$this->db->from('sales_items_temp');
			$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
			$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');		
			$this->_searchSalesQueryParams();		
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('1', '2', FALSE);		
			}			
		
			foreach($this->db->get()->result_array() as $sale_item_row)
			{
				$data['details'][$sale_item_row['sale_id']][] = $sale_item_row;
			}
												
			return $data;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIds();
			$this->db->select('sale_id, sale_time, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
			$this->db->from('sales_items_temp');
			$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
			$this->db->where('sales_items_temp.deleted', 0);
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('sale_id', -1);
			}
			$this->db->group_by('sale_id');
			$this->db->order_by('sale_date');
			
			//If we are exporting NOT exporting to excel make sure to use offset and limit
			if (isset($this->params['export_excel']) && !$this->params['export_excel'])
			{
				$this->db->limit($this->report_limit);
				$this->db->offset($this->params['offset']);
			}			
			
			foreach($this->db->get()->result_array() as $sale_summary_row)
			{
				$data['summary'][$sale_summary_row['sale_id']] = $sale_summary_row; 
			}
		
			$sale_ids = array();
		
			foreach($data['summary'] as $sale_row)
			{
				$sale_ids[] = $sale_row['sale_id'];
			}
			
			$this->db->select('sale_id, items.item_id, item_kits.item_kit_id, sale_time, sale_date, item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent', false);
			$this->db->from('sales_items_temp');
			$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
			$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');		
		
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('1', '2', FALSE);		
			}
		
			foreach($this->db->get()->result_array() as $sale_item_row)
			{
				$data['details'][$sale_item_row['sale_id']][] = $sale_item_row;
			}
		
			return $data;
		}		
	}
	
	function getTotalRows()
	{		
		$sale_ids = $this->_getMatchingSaleIds();
		return count($sale_ids);
	}
	
	public function getSummaryData()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sales_items_temp.sale_id, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit,sum(quantity_purchased) as items_purchased', false);
			$this->db->from('sales_items_temp');
			$this->db->where('sales_items_temp.deleted', 0);
			$this->_searchSalesQueryParams();
			$this->db->group_by('sale_id');
			$result = $this->db->get()->result_array();
			
			$return = array('subtotal' => 0, 'total' => 0,'tax' => 0, 'profit' => 0);
			foreach($result as $row)
			{
				$return['subtotal']+=$row['subtotal'];
				$return['total']+=$row['total'];
				$return['tax']+=$row['tax'];
				$return['profit']+=$row['profit'];
			}
			return $return;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIds();
			$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
			$this->db->from('sales_items_temp');
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('sale_id', -1);
			}
			$result = $this->db->get()->row_array();
			return $result;
		}
	}
	
	private function _getMatchingSaleIds()
	{
		$this->db->select('sale_id, sum(quantity_purchased) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->_searchSalesQueryParams();
		$this->db->where('sales_items_temp.deleted', 0);
		$this->db->group_by('sale_id');
		$this->db->order_by('sale_date');		
		$sales_matches = $this->db->get()->result_array();
		$sale_ids = array();
		foreach($sales_matches as $sale_match)
		{
			$sale_ids[] = $sale_match['sale_id'];
		}
		
		return $sale_ids;
	}
	
	private function _searchSalesQueryParams()
	{
		$matchType = 'where';
		if ($this->params['matchType'] == 'matchType_Or') 
		{
			$matchType = 'or_where';			
		}
		
		if ($this->params['values'][0]['f'] != 0) 
		{
			foreach ($this->params['values'] as $w => $d) 
			{
				$ops = $this->params['ops'][$d['o']]; // Condition Operator
				if (count($d['i']) > 1) 
				{
					if ($d['o'] == 1) { $ops = $this->params['ops'][5]; }
					if ($d['o'] == 2) { $ops = $this->params['ops'][6]; }
				}

				if  ($d['f'] == 6 && $d['o'] == 10) 
				{ 
					// Sale Type
					$this->db->or_having('items_purchased > 0');
				} 
				elseif ($d['f'] == 6 && $d['o'] == 11) 
				{ 
					// Returns
					$this->db->or_having('items_purchased < 0');
				} 
				elseif ($d['f'] == 7) 
				{ 
					// Sale Amount
					if ($this->params['matchType'] == 'matchType_All')
					{
						$this->db->having('total '.str_replace("xx", join(", ", $d['i']), $ops));				
					}
					elseif($this->params['matchType'] == 'matchType_Or')
					{
						$this->db->or_having('total '.str_replace("xx", join(", ", $d['i']), $ops));				
					}
				}
				elseif($d['f'] == 11)
				{
					//Payment type
					foreach($d['i'] as $payment_type)
					{
						$this->db->or_like($this->params['tables'][$d['f']], $payment_type);
					}
				}
				else 
				{
					$this->db->{$matchType}($this->params['tables'][$d['f']].' '.str_replace("xx", join("', '", $d['i']), $ops));
				}
			}
		}
	}
}
?>