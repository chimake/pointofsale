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

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-content" style="padding:10px;">
					
					
					<?php $counter = 0;?>
					<?php foreach($report_data as $data) {?>
						<h3><?php echo $data['customer_info']->first_name.' '.$data['customer_info']->last_name . ' '.($data['customer_info']->account_number ? $data['customer_info']->account_number : '') ;?></h3>
						<?php if($data['customer_info']->address_1) { ?>
								<span><?php echo $data['customer_info']->address_1 . ' '.$data['customer_info']->address_2; ?></span>
								<span><?php echo $data['customer_info']->city . ', '.$data['customer_info']->state . ' '.$data['customer_info']->zip; ?></span>
						<?php } ?>
						
						<div class="row">
							<div class="col-md-12 center" style="text-align: center;">					
								<ul class="stat-boxes">
									<li class="popover-visits">
										<div class="left peity_bar_good"><h5><?php echo lang('customers_balance'); ?></h5></div>
										<div class="right">
											<strong><?php echo to_currency($data['customer_info']->balance); ?></strong>
										</div>
									</li>				
								</ul>
							</div>	
						</div>
						
						<table class="table table-bordered table-striped table-hover data-table tablesorter" id="sortable_table">
							<thead>
								<tr>
									<th><?php echo lang('reports_date');?></th>
									<th><?php echo lang('reports_debit');?></th>
									<th><?php echo lang('reports_credit');?></th>
									<th><?php echo lang('reports_balance');?></th>
									<th><?php echo lang('reports_items');?></th>
									<th><?php echo lang('sales_comment');?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($data['store_account_transactions'] as $transaction) {?>
								<tr>
									<td><?php echo date(get_date_format(), strtotime($transaction['date']));?></td>
									<td><?php echo $transaction['transaction_amount'] > 0 ? to_currency($transaction['transaction_amount']) : to_currency(0); ?></td>
									<td><?php echo $transaction['transaction_amount'] < 0 ? to_currency($transaction['transaction_amount'] * -1) : to_currency(0); ?></td>
									<td><?php echo to_currency($transaction['balance']);?></td>
									<td><?php echo $transaction['items'];?></td>
									<td><?php echo $transaction['comment'];?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					
						<?php if ($counter != count($report_data) - 1) {?>
								<div class="page-break" style="page-break-before: always;"></div>
						<?php } ?>
					<?php $counter++;?>
					<?php } ?>
					
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