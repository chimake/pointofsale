<?php
function is_on_demo_host()
{
	return $_SERVER['HTTP_HOST'] == 'demo.phpsoftwares.com' || $_SERVER['HTTP_HOST'] == 'demo.phpsoftwaresstaging.com';
}
?>