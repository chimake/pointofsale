<?php
//Some reports need time information others do not. So this allows us to reuse this function. The $time parameter should be passed from the corresponding
//date_input_excel_whatever_specific_blabla that calls the private function: _get_common_report_data, that in turn, calls this helper function.
function get_simple_date_ranges($time=false)
{
		$CI =& get_instance();
		$CI->load->language('reports');

		if(!$time)
		{
			$today =  date('Y-m-d');
			$yesterday = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("Y")));
			$six_days_ago = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-6,date("Y")));
			$start_of_this_month = date('Y-m-d', mktime(0,0,0,date("m"),1,date("Y")));
			$end_of_this_month = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
			$start_of_last_month = date('Y-m-d', mktime(0,0,0,date("m")-1,1,date("Y")));
			$end_of_last_month = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime((date('m') - 1).'/01/'.date('Y').' 00:00:00'))));
			$start_of_this_year =  date('Y-m-d', mktime(0,0,0,1,1,date("Y")));
			$end_of_this_year =  date('Y-m-d', mktime(0,0,0,12,31,date("Y")));
			$start_of_last_year =  date('Y-m-d', mktime(0,0,0,1,1,date("Y")-1));
			$end_of_last_year =  date('Y-m-d', mktime(0,0,0,12,31,date("Y")-1));
			$start_of_time =  date('Y-m-d', 0);

			return array(
				$today. '/' . $today 								=> lang('reports_today'),
				$yesterday. '/' . $yesterday						=> lang('reports_yesterday'),
				$six_days_ago. '/' . $today 						=> lang('reports_last_7'),
				$start_of_this_month . '/' . $end_of_this_month		=> lang('reports_this_month'),
				$start_of_last_month . '/' . $end_of_last_month		=> lang('reports_last_month'),
				$start_of_this_year . '/' . $end_of_this_year	 	=> lang('reports_this_year'),
				$start_of_last_year . '/' . $end_of_last_year		=> lang('reports_last_year'),
				$start_of_time . '/' . 	$today						=> lang('reports_all_time'),
			);
		}
		else
		{
			$today =  date('Y-m-d').' 00:00:00';
			$end_of_today=date('Y-m-d').' 23:59:59';
			$yesterday = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("Y"))).' 00:00:00';
			$end_of_yesterday=date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("Y"))).' 23:59:59';
			$six_days_ago = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-6,date("Y"))).' 00:00:00';
			$start_of_this_month = date('Y-m-d', mktime(0,0,0,date("m"),1,date("Y"))).' 00:00:00';
			$end_of_this_month = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))).' 23:59:59';
			$start_of_last_month = date('Y-m-d', mktime(0,0,0,date("m")-1,1,date("Y"))).' 00:00:00';
			$end_of_last_month = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime((date('m') - 1).'/01/'.date('Y').' 00:00:00')))).' 23:59:59';
			$start_of_this_year =  date('Y-m-d', mktime(0,0,0,1,1,date("Y"))).' 00:00:00';
			$end_of_this_year =  date('Y-m-d', mktime(0,0,0,12,31,date("Y"))).' 23:59:59';
			$start_of_last_year =  date('Y-m-d', mktime(0,0,0,1,1,date("Y")-1)).' 00:00:00';
			$end_of_last_year =  date('Y-m-d', mktime(0,0,0,12,31,date("Y")-1)).' 23:59:59';
			$start_of_time =  date('Y-m-d', 0);

			return array(
				$today. '/' . $end_of_today 								=> lang('reports_today'),
				$yesterday. '/' . $end_of_yesterday						=> lang('reports_yesterday'),
				$six_days_ago. '/' . $end_of_today  						=> lang('reports_last_7'),
				$start_of_this_month . '/' . $end_of_this_month		=> lang('reports_this_month'),
				$start_of_last_month . '/' . $end_of_last_month		=> lang('reports_last_month'),
				$start_of_this_year . '/' . $end_of_this_year	 	=> lang('reports_this_year'),
				$start_of_last_year . '/' . $end_of_last_year		=> lang('reports_last_year'),
				$start_of_time . '/' . 	$end_of_today						=> lang('reports_all_time'),
			);
		}
}

function get_months()
{
	$months = array();
	for($k=1;$k<=12;$k++)
	{
		$cur_month = mktime(0, 0, 0, $k, 1, 2000);
		$months[date("m", $cur_month)] = get_month_translation(date("m", $cur_month));
	}

	return $months;
}

function get_month_translation($month_numeric)
{
	return lang('reports_month_'.$month_numeric);
}

function get_days()
{
	$days = array();

	for($k=1;$k<=31;$k++)
	{
		$cur_day = mktime(0, 0, 0, 1, $k, 2000);
		$days[date('d',$cur_day)] = date('j',$cur_day);
	}

	return $days;
}

function get_years()
{
	$years = array();
	for($k=0;$k<10;$k++)
	{
		$years[date("Y")-$k] = date("Y")-$k;
	}

	return $years;
}

function get_hours($time_format)
    {
       $hours = array();
	   if($time_format == '24_hour')
	   {
       for($k=0;$k<24;$k++)
		{
          $hours[$k] = $k;
		}
	   }
	   else 
	   {
		for($k=0;$k<24;$k++)
		{
		
          $hours[$k]  = date('h a', mktime($k));
		
		}
		
		
	   }
       return $hours;
    }


    function get_minutes()
    {
       $hours = array();
       for($k=0;$k<60;$k++)
       {
          $minutes[$k] = $k;
       }
       return $minutes;
    }


function get_random_colors($how_many)
{
	$colors = array();

	for($k=0;$k<$how_many;$k++)
	{
		$colors[] = '#'.random_color();
	}

	return $colors;
}

function random_color()
{
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}

function get_template_colors()
{
	return array('#1c2b33', '#0e6638', '#bfa51b', '#d9561b', '#b2182d', '#ff0000', '#0000ff');
}

function arr_to_csv_line($arr) {
	$line = array();
	foreach ($arr as $v) {
		$line[] = is_array($v) ? arr_to_csv_line($v) : '"' . str_replace('"', '""', $v) . '"';
	}
	return implode(",", $line);
}
function array_to_csv($arr) {
	$lines = array();
	foreach ($arr as $v) {
		$lines[] = arr_to_csv_line($v);
	}
	return implode("\n", $lines);
}