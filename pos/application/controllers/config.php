<?php
require_once ("secure_area.php");
class Config extends Secure_area 
{
	function __construct()
	{
		parent::__construct('config');
	}
	
	function index()
	{	
		$data['controller_name']=strtolower(get_class());
		$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit'),
				lang('sales_store_account') => lang('sales_store_account')

		);
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}
		
		$data['tiers'] = $this->Tier->get_all();
		
		$this->load->view("config", $data);
	}
		
	function save()
	{
		if(!empty($_FILES["company_logo"]) && $_FILES["company_logo"]["error"] == UPLOAD_ERR_OK && !is_on_demo_host())
		{
			$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
			$extension = strtolower(pathinfo($_FILES["company_logo"]["name"], PATHINFO_EXTENSION));
			
			if (in_array($extension, $allowed_extensions))
			{
				$config['image_library'] = 'gd2';
				$config['source_image']	= $_FILES["company_logo"]["tmp_name"];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width']	 = 170;
				$config['height']	= 60;
				$this->load->library('image_lib', $config); 
				$this->image_lib->resize();
				$company_logo = $this->Appfile->save($_FILES["company_logo"]["name"], file_get_contents($_FILES["company_logo"]["tmp_name"]), $this->config->item('company_logo'));
			}
		}
		elseif($this->input->post('delete_logo'))
		{
			$this->Appfile->delete($this->config->item('company_logo'));
		}
		
		
		$this->load->helper('directory');
		$valid_languages = directory_map(APPPATH.'language/', 1);
		$batch_save_data=array(
		'company'=>$this->input->post('company'),
		'sale_prefix'=>$this->input->post('sale_prefix') ? $this->input->post('sale_prefix') : 'POS',
		'website'=>$this->input->post('website'),
		'prices_include_tax' => $this->input->post('prices_include_tax') ? 1 : 0,
		'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
		'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
		'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
		'default_tax_2_name'=>$this->input->post('default_tax_2_name'),
		'default_tax_2_cumulative' => $this->input->post('default_tax_2_cumulative') ? 1 : 0,
		'default_tax_3_rate'=>$this->input->post('default_tax_3_rate'),	
		'default_tax_3_name'=>$this->input->post('default_tax_3_name'),
		'default_tax_4_rate'=>$this->input->post('default_tax_4_rate'),	
		'default_tax_4_name'=>$this->input->post('default_tax_4_name'),
		'default_tax_5_rate'=>$this->input->post('default_tax_5_rate'),	
		'default_tax_5_name'=>$this->input->post('default_tax_5_name'),
		'currency_symbol'=>$this->input->post('currency_symbol'),
		'language'=>in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english',
		'date_format'=>$this->input->post('date_format'),
		'time_format'=>$this->input->post('time_format'),
		'print_after_sale'=>$this->input->post('print_after_sale') ? 1 : 0,
		'round_cash_on_sales'=>$this->input->post('round_cash_on_sales') ? 1 : 0,
		'automatically_email_receipt'=>$this->input->post('automatically_email_receipt') ? 1 : 0,
		'barcode_price_include_tax'=>$this->input->post('barcode_price_include_tax') ? 1 : 0,
		'hide_signature'=>$this->input->post('hide_signature') ? 1 : 0,
		'disable_confirmation_sale'=>$this->input->post('disable_confirmation_sale') ? 1 : 0,
		'track_cash' => $this->input->post('track_cash') ? 1 : 0,
		'number_of_items_per_page'=>$this->input->post('number_of_items_per_page'),
		'additional_payment_types' => $this->input->post('additional_payment_types'),
		'hide_suspended_sales_in_reports' => $this->input->post('hide_suspended_sales_in_reports') ? 1 : 0,
		'hide_store_account_payments_in_reports' => $this->input->post('hide_store_account_payments_in_reports') ? 1 : 0,
		'change_sale_date_when_suspending' => $this->input->post('change_sale_date_when_suspending') ? 1 : 0,
		'change_sale_date_when_completing_suspended_sale' => $this->input->post('change_sale_date_when_completing_suspended_sale') ? 1 : 0,
		'show_receipt_after_suspending_sale' => $this->input->post('show_receipt_after_suspending_sale') ? 1 : 0,
		'customers_store_accounts' => $this->input->post('customers_store_accounts') ? 1 : 0,
		'calculate_average_cost_price_from_receivings' => $this->input->post('calculate_average_cost_price_from_receivings') ? 1 : 0,
		'averaging_method' => $this->input->post('averaging_method'),
		'hide_dashboard_statistics' => $this->input->post('hide_dashboard_statistics'),
		'default_payment_type'=> $this->input->post('default_payment_type'),
		'return_policy'=>$this->input->post('return_policy'),
		);

		if (isset($company_logo))
		{
			$batch_save_data['company_logo'] = $company_logo;
		}
		elseif($this->input->post('delete_logo'))
		{
			$batch_save_data['company_logo'] = 0;
		}
		
		if (is_on_demo_host())
		{
			$batch_save_data['language'] = 'english';
			$batch_save_data['currency_symbol'] = '$';
			$batch_save_data['company_logo'] = 0;
			$batch_save_data['company'] = 'www.PhpSoftwares.com, Inc';
		}
		
		if($this->Appconfig->batch_save($batch_save_data) && $this->save_tiers($this->input->post('tiers_to_edit'), $this->input->post('tiers_to_add'), $this->input->post('tiers_to_delete')))
		{
			echo json_encode(array('success'=>true,'message'=>lang('config_saved_successfully')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('config_saved_unsuccessfully')));
		}
	}
	
	function save_tiers($tiers_to_edit, $tiers_to_add, $tiers_to_delete)
	{
		if ($tiers_to_edit)
		{
			foreach($tiers_to_edit as $tier_id => $name)
			{
				if ($name)
				{
					$tier_data = array('name' => $name);
					$this->Tier->save($tier_data, $tier_id);
				}
			}
		}
		
		if ($tiers_to_add)
		{
			foreach($tiers_to_add as $name)
			{
				if ($name)
				{
					$tier_data = array('name' => $name);
					$this->Tier->save($tier_data);
				}
			}
		}
		
		if ($tiers_to_delete)
		{
			foreach($tiers_to_delete as $tier_id)
			{
				$this->Tier->delete($tier_id);
			}
		}
		return TRUE;
	}
	
	function backup()
	{
		$this->load->view("backup_overview");
	}
	
	function do_backup()
	{
		set_time_limit(0);
		$this->load->dbutil();
		$prefs = array(
			'format'      => 'txt',             // gzip, zip, txt
			'add_drop'    => FALSE,              // Whether to add DROP TABLE statements to backup file
			'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
			'newline'     => "\n"               // Newline character used in backup file
    	);
		$backup =&$this->dbutil->backup($prefs);
		$backup = 'SET FOREIGN_KEY_CHECKS = 0;'."\n".$backup."\n".'SET FOREIGN_KEY_CHECKS = 1;';
		force_download('php_point_of_sale.sql', $backup);
	}
	
	function do_mysqldump_backup()
	{
		set_time_limit(0);
		
		$mysqldump_paths = array();
		
	    // 1st: use mysqldump location from `which` command.
	    $mysqldump = `which mysqldump`;
		
	    if (is_executable($mysqldump))
		{
			array_unshift($mysqldump_paths, $mysqldump);
		}
		else
		{
		    // 2nd: try to detect the path using `which` for `mysql` command.
		    $mysqldump = dirname(`which mysql`) . "/mysqldump";
		    if (is_executable($mysqldump))
			{
				array_unshift($mysqldump_paths, $mysqldump);			
			}
		}
		
		// 3rd: Default paths
		$mysqldump_paths[] = 'C:\Program Files\PHP Point of Sale Stack\mysql\bin\mysqldump.exe';  //Windows
		$mysqldump_paths[] = 'C:\PHPPOS\mysql\bin\mysqldump.exe';  //Windows
		$mysqldump_paths[] = '/Applications/phppos/mysql/bin/mysqldump';  //Mac
		$mysqldump_paths[] = '/opt/phppos/mysql/bin/mysqldump';  //Linux
		$mysqldump_paths[] = '/usr/bin/mysqldump';  //Linux
		$mysqldump_paths[] = '/usr/local/mysql/bin/mysqldump'; //Mac
		$mysqldump_paths[] = '/usr/local/bin/mysqldump'; //Linux
		$mysqldump_paths[] = '/usr/mysql/bin/mysqldump'; //Linux


		$database = escapeshellarg($this->db->database);
		$db_hostname = escapeshellarg($this->db->hostname);
		$db_username= escapeshellarg($this->db->username);
		$db_password = escapeshellarg($this->db->password);
	
		$success = FALSE;
		foreach($mysqldump_paths as $mysqldump)
		{
			
			if (is_executable($mysqldump))
			{
				$backup_command = "\"$mysqldump\" --host=$db_hostname --user=$db_username --password=$db_password $database";

				// set appropriate headers for download ...  
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="php_point_of_sale.sql"');
				header('Content-Transfer-Encoding: binary');
				header('Connection: Keep-Alive');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				
				$status = false; 
				passthru($backup_command, $status);
				$success = $status == 0;
				break;
			}
		}
		
		if (!$success)
		{
			header('Content-Description: Error message');
			header('Content-Type: text/plain');
			header('Content-Disposition: inline');
			header('Content-Transfer-Encoding: base64');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			die(lang('config_mysqldump_failed'));	
		}
	}
	
	function optimize()
	{
		$this->load->dbutil();
		$this->dbutil->optimize_database();
		echo json_encode(array('success'=>true,'message'=>lang('config_database_optimize_successfully')));
	}
}
?>