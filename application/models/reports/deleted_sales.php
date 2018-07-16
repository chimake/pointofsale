	<?php
require_once("report.php");
class Deleted_sales extends Report
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
								array('data'=>lang('sales_deleted_by'), 'align'=> 'left'), 
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
								array('data'=>lang('reports_name'), 'align'=> 'left'), 
								array('data'=>lang('reports_category'), 'align'=> 'left'), 
								array('data'=>lang('reports_serial_number'), 'align'=> 'left'), 
								array('data'=>lang('reports_description'), 'align'=> 'left'), 
								array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left'), 
								array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
								array('data'=>lang('reports_total'), 'align'=> 'right'), 
								array('data'=>lang('reports_tax'), 'align'=> 'right'), 
								array('data'=>lang('reports_profit'), 'align'=> 'right'),
								array('data'=>lang('reports_discount'), 'align'=> 'left')
								)
					);		
	}
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();
		
		$this->db->select('sale_id, sale_time, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name,CONCAT(deleted_by.first_name," ",deleted_by.last_name) as deleted_by, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
		$this->db->from('sales_items_temp');
		$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
		$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
		$this->db->join('people as deleted_by', 'sales_items_temp.deleted_by = deleted_by.person_id', 'left');

		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 1);
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

		$this->db->select('sale_id, sale_time, sale_date,item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent', false);
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
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('sales_items_temp.deleted', 1);
		$ret = $this->db->get()->row_array();
		return $ret['sale_count'];

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
		$this->db->where('deleted', 1);
		return $this->db->get()->row_array();
	}
}
?>