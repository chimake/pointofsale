<?php
require_once("report.php");
class Summary_store_accounts extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_id'), 'align'=>'left'), array('data'=>lang('reports_customer'), 'align'=> 'left'), array('data'=>lang('customers_balance'), 'align'=> 'right'), array('data'=>'', 'align'=> 'right'));
	}
	
	public function getData()
	{
		$this->db->select('CONCAT(first_name, " ",last_name) as customer, balance, customers.person_id', false);
		$this->db->from('customers');
		$this->db->join('people', 'customers.person_id = people.person_id');
		$this->db->where('balance > 0');
		
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
		$this->db->select('CONCAT(first_name, " ",last_name) as customer, balance, customers.person_id', false);
		$this->db->from('customers');
		$this->db->join('people', 'customers.person_id = people.person_id');
		$this->db->where('balance > 0');
		
		return $this->db->count_all_results();
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>