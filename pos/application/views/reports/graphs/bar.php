<?php
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Pragma: public");

$bar_data = array();
$k = 0;
$ticks = array();
foreach($data as $label=>$value)
{
    $bar_data[] = array($k, (float)$value);
	$ticks[] = array($k, $label);
	$k++;
}
?>
$.plot($("#chart"), [<?php echo json_encode($bar_data);?> ], 
{
	series: 
	{
		color: '#cccccc',
		bars: { show: true, barWidth: 0.6 }
	},
	xaxis: {
	    ticks: <?php echo json_encode($ticks);?>
	  }
});