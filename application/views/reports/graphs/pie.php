<?php
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Pragma: public");
$pie_data = array();

$threshold = 0.04;
$colors = get_template_colors();

$total = 0;
foreach($data as $value)
{
	$total +=$value;	
}

$k=0;

foreach($data as $label=>$value)
{
	if ($value/$total > $threshold)
	{
		$pie_data[] = array('color' => isset($colors[$k]) ? $colors[$k] : $colors[rand(0, count($colors) -1) ] , 'data' => (float)$value, 'label' => (string)$label);
		$k++;
	}
	else
	{
		$pie_data[] = array('data' => (float)$value, 'label' => (string)$label);
	}
}
?>
$.plot($("#chart"), <?php echo json_encode($pie_data); ?>, 
{
        series: {
            pie: { 
                show: true,
                combine: {
                    color: '#999',
                    threshold: <?php echo $threshold; ?>
                }
            }
        },
        legend: {
            show: false
        }
});