<?php
require_once("report.php");
class Store_account_statements extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array();	
	}
	
	public function getData()
	{
		$return = array();
		
		$customer_ids_for_report = array();
		$customer_id = $this->params['customer_id'];
		
		if ($customer_id == -1)
		{
			$this->db->select('person_id');
			$this->db->from('customers');
			$this->db->where('balance !=', 0);
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
			$result = $this->db->get()->result_array();
			
			foreach($result as $row)
			{
				$customer_ids_for_report[] = $row['person_id'];
			}
		}
		else
		{
			$this->db->select('person_id');
			$this->db->from('customers');
			$this->db->where('balance !=', 0);
			$this->db->where('person_id', $customer_id);
			$result = $this->db->get()->row_array();
			
			if (!empty($result))
			{
				$customer_ids_for_report[] = $result['person_id'];
			}
		}
		
		$one_month_ago = date('Y-m-d', strtotime('-1 month'));
		
		foreach($customer_ids_for_report as $customer_id)
		{
			$this->db->from('store_accounts');
			$this->db->where('customer_id', $customer_id);
			$this->db->where('date >', $one_month_ago);
			$this->db->where('deleted',0);
			$this->db->order_by('date');
			
			$result = $this->db->get()->result_array();
			
			for ($k=0;$k<count($result);$k++)
			{
				$item_names = array();
				$sale_id = $result[$k]['sale_id'];
				
				$this->db->select('name');
				$this->db->from('items');
				$this->db->join('sales_items', 'sales_items.item_id = items.item_id');
				$this->db->where('sale_id', $sale_id);
				
				foreach($this->db->get()->result_array() as $row)
				{
					$item_names[] = $row['name'];
				}
				
				$result[$k]['items'] = implode(', ', $item_names);
			}
			$return[]= array('customer_info' => $this->Customer->get_info($customer_id),'store_account_transactions' => $result);
		}
		
		return $return;
	}
	
	public function getTotalRows()
	{
		$customer_id = $this->params['customer_id'];
		
		if ($customer_id == -1)
		{
			$this->db->select('person_id');
			$this->db->from('customers');
			$this->db->where('balance !=', 0);
		}
		else
		{
			$this->db->select('person_id');
			$this->db->from('customers');
			$this->db->where('balance !=', 0);
			$this->db->where('person_id', $customer_id);
		}
		
		return $this->db->count_all_results();
	}
	
	
	public function getSummaryData()
	{
		return array();
	}
}
?>