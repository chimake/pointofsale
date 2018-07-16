<?php
require_once("report.php");
class Summary_profit_and_loss extends Report
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
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
		$total = 0;
		
		$data = array();
		
		$sales_total = 0;
		$this->db->select('sale_id, sum(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->group_by('sale_id');
			
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
		}

		$this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('date(sale_time) BETWEEN "'. $this->params['start_date']. '" and "'. $this->params['end_date'].'"');
		
		//We only want sales, we don't want negative transactions
		$this->db->where('payment_amount > 0');
		
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		$this->db->order_by('sale_id, payment_type');
		$this->db->where('location_id', $location_id);
		
		$sales_payments = $this->db->get()->result_array();
		
		$payments_by_sale = array();
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = array();
		
		foreach($payments_by_sale as $sale_id => $payment_rows)
		{
			if(isset($sales_totals[$sale_id])){
				$total_sale_balance = $sales_totals[$sale_id];
			}
			
			foreach($payment_rows as $row)
			{
				$payment_amount = $row['payment_amount'] <= $total_sale_balance ? $row['payment_amount'] : $total_sale_balance;
				
				if (!isset($payment_data[$row['payment_type']]))
				{
					$payment_data[$row['payment_type']] = array('payment_type' => $row['payment_type'], 'payment_amount' => 0 );
				}
				
				if ($total_sale_balance != 0)
				{
					$payment_data[$row['payment_type']]['payment_amount'] += $payment_amount;
				}
				
				$total_sale_balance-=$payment_amount;
			}
		}		
		
		foreach($payment_data as $payment_data)
		{
			$sales_total+=$payment_data['payment_amount'];
			$total+=$payment_data['payment_amount'];
		}
		
		$data['sales_total'] = $sales_total;
		
		
		$this->db->select('category, sum(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		$this->db->where('total < 0');
		$suppliers_row = $this->db->get()->row_array();
		$total+=$suppliers_row['total'];
		$data['returns_total'] = $suppliers_row['total'];
		
		
		$this->db->select('category, sum(total) as total', false);
		$this->db->from('receivings_items_temp');
		
		$this->db->where($this->db->dbprefix('receivings_items_temp').'.deleted', 0);
		$receivings_row = $this->db->get()->row_array();
		$total+=$receivings_row['total'];
		$data['receivings_total'] = $receivings_row['total'];
		
		
		$this->db->select('SUM(item_unit_price * ( discount_percent /100 )) as discount');
		$this->db->from('sales_items_temp');
		$this->db->where('discount_percent > 0');
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		
		$discount_row = $this->db->get()->row_array();
		$data['discount_total'] = $discount_row['discount'];
		$total-=$discount_row['discount'];
		
		$this->db->select('sum(tax) as tax', false);
		$this->db->from('sales_items_temp');
		$this->db->where('deleted', 0);
		$tax_row = $this->db->get()->row_array();
		$data['taxes_total'] = $tax_row['tax'];
		
		$total-=$tax_row['tax'];
		$data['total'] = $total;
		
		
		$this->db->select('sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		$this->db->where('deleted', 0);
		$profit_row = $this->db->get()->row_array();
		
		$data['profit'] = $profit_row['profit'];
		return $data;
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>