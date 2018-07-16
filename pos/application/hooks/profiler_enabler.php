<?php
class ProfilerEnabler
{
	function EnableProfiler()
	{
		$CI = &get_instance();
		$CI->output->enable_profiler( config_item('enable_profiling') );
	}
}
?>