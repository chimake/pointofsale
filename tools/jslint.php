#!/usr/bin/php
<?php
require_once('../application/helpers/assets_helper.php');

foreach(get_js_files() as $js_file)
{
	echo ('node jslint-reporter/wrapper.js ../'.$js_file['path']."\n******************************************************************\n");
	system ('node jslint-reporter/wrapper.js ../'.$js_file['path']);
}
?>