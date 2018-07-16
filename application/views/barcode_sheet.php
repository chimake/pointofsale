<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo lang('items_generate_barcodes'); ?></title>
</head>
<body>
<table width='50%' align='center' cellpadding='20'>
<tr>
<?php 
$count = 0;
foreach($items as $item)
{
	$barcode = $item['id'];
	$text = $item['name'];
	
	if ($count % 2 ==0 and $count!=0)
	{
		echo '</tr><tr>';
	}
	echo "<td align='center'>".$this->config->item('company')."<br /><img src='".site_url('barcode').'?barcode='.rawurlencode($barcode).'&text='.rawurlencode($barcode)."&scale=$scale' /><br />".character_limiter($text, 45)."</td>";
	$count++;
}
?>
</tr>
</table>
</body>
</html>
