<?php
require_once("report.php");
class Summary_employees extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_employee'), 'align'=>'left'), array('data'=>lang('reports_subtotal'), 'align'=>'right'), array('data'=>lang('reports_total'), 'align'=>'right'), array('data'=>lang('reports_tax'), 'align'=>'right'), array('data'=>lang('reports_profit'), 'align'=>'right'));
	}
	
	public function getData()
	{
		$this->db->select('CONCAT(first_name, " ",last_name) as employee, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		$this->db->join('employees', 'employees.person_id = sales_items_temp.employee_id');
		$this->db->join('people', 'employees.person_id = people.person_id');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		$this->db->group_by('employee_id');
		$this->db->order_by('last_name');

		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}

		return $this->db->get()->result_array();		
	}
	
	function getTotalRows()
	{
		$this->db->select('COUNT(DISTINCT(person_id)) as employee_count');
		$this->db->from('sales_items_temp');		
		$this->db->join('employees', 'employees.person_id = sales_items_temp.employee_id');

		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		
		$ret = $this->db->get()->row_array();
		return $ret['employee_count'];
	}
	
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		$this->db->join('employees', 'employees.person_id = sales_items_temp.employee_id');
		$this->db->join('people', 'employees.person_id = people.person_id');
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