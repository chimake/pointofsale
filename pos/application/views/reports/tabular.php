<?php
if($export_excel == 1)
{
	$rows = array();
	$row = array();
	foreach ($headers as $header) 
	{
		$row[] = strip_tags($header['data']);
	}
	
	$rows[] = $row;
	
	foreach($data as $datarow)
	{
		$row = array();
		foreach($datarow as $cell)
		{
			$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));
		}
		$rows[] = $row;
	}
	
	$content = array_to_csv($rows);
	
	force_download(strip_tags($title) . '.csv', $content);
	exit;
}
?>
<?php $this->load->view("partial/header"); ?>
<div id="content-header">
	<h1 > <i class="fa fa-bar-chart"> </i><?php echo lang('reports_reports'); ?> - <?php echo $title ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<?php if(isset($pagination) && $pagination) {  ?>
		<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
			<?php echo $pagination;?>
		</div>
	<?php }  ?>

	<div class="row">
		<div class="col-md-12 center" style="text-align: center;">					
			<ul class="stat-boxes">
				<?php foreach($summary_data as $name=>$value) { ?>
				<li class="popover-visits">
					<div class="left peity_bar_good"><h5><?php echo lang('reports_'.$name); ?></h5></div>
					<div class="right">
						<strong><?php echo to_currency($value); ?></strong>
						
					</div>
				</li>
				<?php }?>
				
			</ul>
		</div>	
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<h5><?php echo $subtitle ?></h5>
				</div>
				<div class="widget-content nopadding">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover data-table tablesorter" id="sortable_table">
						<thead>
							<tr>
								<?php foreach ($headers as $header) { ?>
								<th align="<?php echo $header['align'];?>"><?php echo $header['data']; ?></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $row) { ?>
							<tr>
								<?php foreach ($row as $cell) { ?>
								<td align="<?php echo $cell['align'];?>"><?php echo $cell['data']; ?></td>
								<?php } ?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if(isset($pagination) && $pagination) {  ?>
		<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
			<?php echo $pagination;?>
		</div>
	<?php }  ?>
	

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(); 
	}
}
$(document).ready(function()
{
	init_table_sorting();
});
</script>