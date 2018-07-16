<?php
require_once("report.php");
class Summary_taxes extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_tax_percent'), 'align'=>'left'),array('data'=>lang('reports_subtotal'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'left'),array('data'=>lang('reports_total'), 'align'=>'left'));
	}
	
	public function getData()
	{
		$this->db->select('sale_id, item_id, item_kit_id, line');
		$this->db->from('sales_items_temp');
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		
		$taxes_data = array();
		foreach($this->db->get()->result_array() as $row)
		{
			if ($row['item_id'])
			{
				$this->getTaxesForItems($row['sale_id'], $row['item_id'], $row['line'], $taxes_data);
			}
			else
			{
				$this->getTaxesForItemKits($row['sale_id'], $row['item_kit_id'], $row['line'], $taxes_data);			
			}
		}
		
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$taxes_data = array_slice($taxes_data, $this->params['offset'], $this->report_limit);
		}
		
		
		return $taxes_data;
	}
	
	function getTotalRows()
	{
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select('COUNT(DISTINCT(CONCAT('.$this->db->dbprefix('sales_items_taxes').'.name,'.$this->db->dbprefix('sales_items_taxes').'.percent))) as tax_count', false);
		$this->db->from('sales_items_taxes');
		$this->db->join('sales', 'sales.sale_id=sales_items_taxes.sale_id');
		$this->db->where('sale_time BETWEEN "'. $this->params['start_date']. '" and "'. $this->params['end_date'].'" and location_id = "'.$logged_in_location_id.'"');
		
		if ($this->config->item('hide_store_account_payments_in_reports'))
		{
			$this->db->where('store_account_payment',0);
		}
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('payment_amount > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('payment_amount < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		
		$ret = $this->db->get()->row_array();
		return $ret['tax_count'];
	}
	
	function getTaxesForItems($sale_id, $item_id, $line, &$taxes_data)
	{
		$query = $this->db->query("SELECT name, percent, cumulative, item_unit_price, item_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('sales_items_taxes').' 
		JOIN '.$this->db->dbprefix('sales_items'). ' USING(sale_id, item_id, line) WHERE '.
		$this->db->dbprefix('sales_items_taxes').'.sale_id = '.$sale_id.' and '.
		$this->db->dbprefix('sales_items_taxes').'.item_id = '.$item_id.' and '.
		$this->db->dbprefix('sales_items_taxes').'.line = '.$line. ' ORDER BY cumulative');
		
		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = to_currency_no_money($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = to_currency_no_money($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['name'].$row['percent']]))
			{
				$taxes_data[$row['name'].$row['percent']] = array('name' => $row['name'].' ('.$row['percent'] . '%)', 'tax' => 0, 'subtotal' => 0, 'total' => 0);
			}
						
			$taxes_data[$row['name'].$row['percent']]['subtotal'] += to_currency_no_money($subtotal);
			$taxes_data[$row['name'].$row['percent']]['tax'] += to_currency_no_money($tax);
			$taxes_data[$row['name'].$row['percent']]['total'] += to_currency_no_money($subtotal+ $tax);
			
		}
		
	}
	
	function getTaxesForItemKits($sale_id, $item_kit_id, $line, &$taxes_data)
	{
		$query = $this->db->query("SELECT name, percent, cumulative, item_kit_unit_price, item_kit_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('sales_item_kits_taxes').' 
		JOIN '.$this->db->dbprefix('sales_item_kits'). ' USING(sale_id, item_kit_id, line) WHERE '.
		$this->db->dbprefix('sales_item_kits_taxes').'.sale_id = '.$sale_id.' and '.
		$this->db->dbprefix('sales_item_kits_taxes').'.item_kit_id = '.$item_kit_id.' and '.
		$this->db->dbprefix('sales_item_kits_taxes').'.line = '.$line. ' ORDER BY cumulative');

		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = to_currency_no_money($row['item_kit_unit_price']*$row['quantity_purchased']-$row['item_kit_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = to_currency_no_money($row['item_kit_unit_price']*$row['quantity_purchased']-$row['item_kit_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['name'].$row['percent']]))
			{
				$taxes_data[$row['name'].$row['percent']] = array('name' => $row['name'].' ('.$row['percent'] . '%)', 'tax' => 0);
			}
			
			
			$taxes_data[$row['name'].$row['percent']]['tax'] += to_currency_no_money($tax);
		}
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>