#!/usr/bin/php
<?php
require_once('../application/helpers/assets_helper.php');

$js_files = array();

foreach(get_js_files() as $js_file)
{
	$js_files[] = '../'.$js_file['path'];
}

exec ('cat '.implode(' ',$js_files).' | java -jar yuicompressor.jar --type js -o ../js/all.js');
?>