<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo lang('items_generate_barcodes'); ?></title>
</head>
<body style="margin: 0;">
<table width='50%' align='center' cellpadding='20'>
<tr>
<?php 
for($k=0;$k<count($items);$k++)
{
	$item = $items[$k];
	$barcode = $item['id'];
	$text = $item['name'];

	$page_break_after = ($k == count($items) -1) ? 'auto' : 'always';
	echo "<div style='width: 2in;height: .8in;word-wrap: break-word;overflow: hidden;margin:0 auto;text-align:center;font-size: 10pt;line-height: 1em;page-break-after: $page_break_after;padding: 10px;'>".$this->config->item('company')."<br /><img src='".site_url('barcode').'?barcode='.rawurlencode($barcode).'&text='.rawurlencode($barcode)."&scale=$scale' /><br />".$text."</div>";
}
?>
</tr>


</table>
</body>
</html>
