<?php
class App_files extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function view($file_id)
	{ 
		$file = $this->Appfile->get($file_id);
		header("Content-type: ".get_mime_by_extension($file->file_name));
		echo $file->file_data;
	}
}
?>