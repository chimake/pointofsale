<?php
function get_css_files()
{
	return array(
		array('path' =>'css/bootstrap.min.css', 'media' => 'all'),
		array('path' =>'css/jquery.gritter.css', 'media' => 'all'),
		array('path' =>'css/jquery-ui.css', 'media' => 'all'),
		array('path' =>'css/unicorn.css', 'media' => 'all'),
		array('path' =>'css/custom.css', 'media' => 'all'),
		array('path' =>'css/datepicker.css', 'media' => 'all'),
		array('path' =>'css/bootstrap-select.css', 'media' => 'all'),
		array('path' =>'css/select2.css', 'media' => 'all'),
		array('path' =>'css/font-awesome.min.css', 'media' => 'all'),
		array('path' =>'css/jquery.loadmask.css', 'media' => 'all'),
		array('path' =>'css/token-input-facebook.css', 'media' => 'all'),
	);	
}

function get_js_files()
{
	if(!defined("ENVIRONMENT") or ENVIRONMENT == 'development')
	{
		return array(
			array('path' =>'js/jquery.min.js'),
			array('path' =>'js/jquery.clicktoggle.js'),
			array('path' =>'js/jquery-ui.custom.min.js'),
			array('path' =>'js/bootstrap.min.js'),
			array('path' =>'js/jquery.gritter.min.js'),
			array('path' =>'js/jquery.peity.min.js'), // Do we use this?
			array('path' =>'js/unicorn.js'),
			array('path' =>'js/jquery.dataTables.min.js'),
			array('path' =>'js/bootstrap-datatables.js'),
			array('path' =>'js/bootstrap-datepicker.js'),
			array('path' =>'js/bootstrap-select.min.js'),  // Do we use this?
			array('path' =>'js/select2.min.js'),
			array('path' =>'js/jquery.interface.js'),  // Do we use this?
			array('path' =>'js/jquery.jpanelmenu.min.js'),  // Do we use this?
			array('path' =>'js/imagePreview.js'),
			array('path' =>'js/jquery.tablesorter.min.js'),
			array('path' =>'js/jquery.validate.js'),
			array('path' =>'js/common.js'),
			array('path' =>'js/jquery.form.js'),
			array('path' =>'js/manage_tables.js'),
			array('path' =>'js/jquery.tokeninput.js'),
			array('path' =>'js/jquery.jpanelmenu.min.js'),
			array('path' =>'js/jquery.loadmask.min.js'),
			array('path' =>'js/jquery.imagerollover.js'),
		);
	}

	return array(
		array('path' =>'js/all.js'),
	);



}
?>