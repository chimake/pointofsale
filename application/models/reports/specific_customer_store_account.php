<?php
require_once("report.php");
class Specific_customer_store_account extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_id'), 'align'=>'left'),
		array('data'=>lang('reports_time'), 'align'=> 'left'),
		array('data'=>lang('reports_sale_id'), 'align'=> 'left'),
		array('data'=>lang('reports_debit'), 'align'=> 'left'),
		array('data'=>lang('reports_credit'), 'align'=> 'left'),
		array('data'=>lang('reports_balance'), 'align'=> 'left'),
		array('data'=>lang('reports_comment'), 'align'=> 'left'));
		
	}
	
	public function getData()
	{
		$this->db->from('store_accounts');
		$this->db->where('customer_id',$this->params['customer_id']);
		$this->db->where('date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'" and deleted=0');
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
		$this->db->from('store_accounts');
		$this->db->where('customer_id',$this->params['customer_id']);
		$this->db->where('date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'" and deleted=0');
		return $this->db->count_all_results();
	}
	
	
	public function getSummaryData()
	{

		$summary_data=array(lang('reports_balance_to_pay')=>$this->Customer->get_info($this->params['customer_id'])->balance);
		return $summary_data;
	}
}
?>