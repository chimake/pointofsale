<?php
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Pragma: public");
$line_data = array();
$options = array('lines' => array('show' => true), 'xaxis' => array('mode' => 'time', 'timeformat' => "<?php echo get_flot_date_format(); ?>"));


foreach($data as $label=>$value)
{
    $line_data[] = array((float)$label*1000, (float)$value);
}

?>
$.plot($("#chart"), [<?php echo json_encode($line_data)?>], {lines: {show: true}, xaxis:{mode: 'time', timeformat:'<?php echo get_flot_date_format(); ?>'}});