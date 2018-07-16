<?php
require_once("report.php");
class Specific_supplier extends Report
{
	function __construct()
	{		
		parent::__construct();
	}
	
	public function getDataColumns()
	{


		return array('summary' => array(array('data'=>lang('reports_sale_id'), 'align'=>'left'), array('data'=>lang('reports_date'), 'align'=>'left'), array('data'=>lang('reports_items_purchased'), 'align'=>'left'), array('data'=>lang('reports_sold_to'), 'align'=>'left'), array('data'=>lang('reports_subtotal'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'left'), array('data'=>lang('reports_profit'), 'align'=>'left'), array('data'=>lang('reports_payment_type'), 'align'=>'left'), array('data'=>lang('reports_comments'), 'align'=>'left')),
							'details' => array(array('data'=>lang('reports_item_number'), 'align'=>'left'), array('data'=>lang('items_product_id'), 'align'=>'left'),array('data'=>lang('reports_name'), 'align'=>'left'), array('data'=>lang('reports_category'), 'align'=>'left'), array('data'=>lang('reports_serial_number'), 'align'=>'left'), array('data'=>lang('reports_description'), 'align'=>'left'), array('data'=>lang('reports_quantity_purchased'), 'align'=>'left'), array('data'=>lang('reports_subtotal'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'left'), array('data'=>lang('reports_profit'), 'align'=>'left'),array('data'=>lang('reports_discount'), 'align'=>'left'))
		);
	}
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();


		$this->db->select('sale_id, sale_time, sale_date, sum(quantity_purchased) as items_purchased,  CONCAT(first_name," ",last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
		$this->db->from('sales_items_temp');
		$this->db->join('people', 'sales_items_temp.customer_id = people.person_id', 'left');
		$this->db->where('supplier_id = '.$this->params['supplier_id']);

		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}

		$this->db->where('deleted', 0);
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
		$this->db->select('sale_id, sale_time, sale_date, item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent', false);
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
	
	public function getTotalRows()
	{		
		$this->db->select("COUNT(DISTINCT(sale_id)) as sale_count");
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
		$this->db->where('items.supplier_id = '.$this->params['supplier_id']);
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where('sales_items_temp.deleted', 0);
		$ret = $this->db->get()->row_array();
		return $ret['sale_count'];
	}
	
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		$this->db->where('sale_date BETWEEN "'. $this->params['start_date']. '" and "'. $this->params['end_date'].'" and supplier_id='.$this->params['supplier_id']);
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		
		return $this->db->get()->row_array();
		

	}
}
?>