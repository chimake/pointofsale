	<?php if(isset($pagination) && $pagination) {  ?>
		<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
			<?php echo $pagination;?>
		</div>
	<?php }  ?>

	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-th"></i>
					</span>
					<h5 ><?php echo lang('reports_reports'); ?> - <?php echo $title ?></h5>
					<span title="<?php echo $subtitle ?>" class="label label-info tip-left"><?php echo $subtitle ?></span>
				</div>
				<div class="widget-content nopadding">
					<?php echo $subtitle ?>
<div class="table-responsive">
<table id="contents" class="table table-hover table-bordered table-striped">
	<tr>
		<td id="item_table">
			<div id="table_holder">
				<table class="tablesorter table report" id="sortable_table">
					<thead>
						<tr>
							<th><a href="#" class="expand_all" style="font-weight: bold; ">+</a></th>
							<?php foreach ($headers['summary'] as $header) { ?>
							<th align="<?php echo $header['align']; ?>"><?php echo $header['data']; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($summary_data as $key=>$row) { ?>
						<tr>
							<td><a href="#" class="expand" style="font-weight: bold;">+</a></td>
							<?php foreach ($row as $cell) { ?>
							<td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
							<?php } ?>
						</tr>
						<tr>
							<td colspan="<?php echo count($headers['summary']) + 1; ?>" class="innertable">
								<table class="innertable table table-bordered" >
									<thead>
										<tr>
											<?php foreach ($headers['details'] as $header) { ?>
											<th align="<?php echo $header['align']; ?>"><?php echo $header['data']; ?></th>
											<?php } ?>
										</tr>
									</thead>
								
									<tbody>
										<?php foreach ($details_data[$key] as $row2) { ?>
										
											<tr>
												<?php foreach ($row2 as $cell) { ?>
												<td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
												<?php } ?>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			</div>
			</div>

			<div id="report_summary" class="tablesorter pull-right report report-sumary" style="margin-right: 10px;">
			<?php foreach($overall_summary_data as $name=>$value) { ?>
				<div class="summary_row"><?php echo "<strong>".lang('reports_'.$name). '</strong>: '.to_currency($value); ?></div>
			<?php }?>
			</div>
		</td>
	</tr>
</table>
</div>
	<?php if(isset($pagination) && $pagination) {  ?>
		<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
			<?php echo $pagination;?>
		</div>
	<?php }  ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$(".tablesorter a.expand").click(function(event)
	{
		$(event.target).parent().parent().next().find('td.innertable').toggle();
		
		if ($(event.target).text() == '+')
		{
			$(event.target).text('-');
		}
		else
		{
			$(event.target).text('+');
		}
		return false;
	});
	
	$(".tablesorter a.expand_all").click(function(event)
	{
		$('td.innertable').toggle();
		
		if ($(event.target).text() == '+')
		{
			$(event.target).text('-');
			$(".tablesorter a.expand").text('-');
		}
		else
		{
			$(event.target).text('+');
			$(".tablesorter a.expand").text('+');
		}
		return false;
	});
	
});
</script>