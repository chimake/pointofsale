<?php
require_once ("secure_area.php");
class Home extends Secure_area 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$data['total_items']=$this->Item->count_all();
		$data['total_item_kits']=$this->Item_kit->count_all();
		$data['total_suppliers']=$this->Supplier->count_all();
		$data['total_customers']=$this->Customer->count_all();
		$data['total_employees']=$this->Employee->count_all();
		$data['total_locations']=$this->Location->count_all();
		$data['total_giftcards']=$this->Giftcard->count_all();
		$data['total_sales']=$this->Sale->count_all();
		$this->load->view("home",$data);
	}

	function logout()
	{
		$this->Employee->logout();
	}
}
?>