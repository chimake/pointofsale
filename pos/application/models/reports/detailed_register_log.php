<?php
require_once("report.php");
class Detailed_register_log extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
			array('data'=>lang('common_delete'), 'align'=> 'left'), 
			array('data'=>lang('reports_employee'), 'align'=> 'left'), 
			array('data'=>lang('reports_shift_start'), 'align'=>'left'),
			array('data'=>lang('reports_shift_end'), 'align'=>'left'),
			array('data'=>lang('reports_open_amount'), 'align'=>'left'),
			array('data'=>lang('reports_close_amount'), 'align'=>'left'),
			array('data'=>lang('reports_cash_sales'), 'align'=>'left'),
			array('data'=>lang('reports_difference'), 'align'=>'left')
		);		
	}
	
	public function getData()
	{
		$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		
		$between = 'between "' . $this->params['start_date'] . ' 00:00:00" and "' . $this->params['end_date'] . ' 23:59:59"';
		$this->db->select("people.first_name, people.last_name, register_log.*, (register_log.close_amount - register_log.open_amount - register_log.cash_sales_amount) as difference");
		$this->db->from('register_log as register_log');
		$this->db->join('people as people', 'register_log.employee_id=people.person_id');
		$this->db->join('employees_locations as employees_locations', 'employees_locations.employee_id=people.person_id');
		$this->db->where('register_log.shift_start ' . $between);
		$this->db->where('register_log.deleted ', 0);
		$this->db->where('employees_locations.location_id', $location_id);
		
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}
		
		return $this->db->get()->result_array();
	}
	
	public function getTotalRows()
	{
		$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		
		$between = 'between "' . $this->params['start_date'] . ' 00:00:00" and "' . $this->params['end_date'] . ' 23:59:59"';
		$this->db->select("people.first_name, people.last_name, register_log.*, (register_log.close_amount - register_log.open_amount - register_log.cash_sales_amount) as difference");
		$this->db->from('register_log as register_log');
		$this->db->join('people as people', 'register_log.employee_id=people.person_id');
		$this->db->join('employees_locations as employees_locations', 'employees_locations.employee_id=people.person_id');
		$this->db->where('register_log.shift_start ' . $between);
		$this->db->where('register_log.deleted ', 0);
		$this->db->where('employees_locations.location_id', $location_id);
		
		return $this->db->count_all_results();

	}
	
	
	public function getSummaryData() 
	{
		$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		
		$between = 'between "' . $this->params['start_date'] . ' 00:00:00" and "' . $this->params['end_date'] . ' 23:59:59"';
		$this->db->select("people.first_name, people.last_name, register_log.*, (register_log.close_amount - register_log.open_amount - register_log.cash_sales_amount) as difference");
		$this->db->from('register_log as register_log');
		$this->db->join('people as people', 'register_log.employee_id=people.person_id');
		$this->db->join('employees_locations as employees_locations', 'employees_locations.employee_id=people.person_id');
		$this->db->where('register_log.shift_start ' . $between);
		$this->db->where('register_log.deleted ', 0);
		$this->db->where('employees_locations.location_id', $location_id);
		
		$data = $this->db->get()->result_array();
		
		$overallSummaryData = array(
			'total_cash_sales'=>0,
			'total_shortages'=>0,
			'total_overages'=>0,
			'total_difference'=>0
		);
		
		foreach($data as $row)
		{
			$overallSummaryData['total_cash_sales'] += $row['cash_sales_amount'];
			if ($row['difference'] > 0) {
				$overallSummaryData['total_overages'] += $row['difference'];
			} else {
				$overallSummaryData['total_shortages'] += $row['difference'];
			}
		
			$overallSummaryData['total_difference'] += $row['difference'];
		}
		
		return $overallSummaryData;
		
	}
		
	public function delete_register_log($register_log_id)
	{	
		$this->db->where('register_log_id', $register_log_id);
		return $this->db->update('register_log', array('deleted' => 1));
	}
}
?>