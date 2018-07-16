<?php $this->load->view("partial/header");
	$controller_name="items";
 ?>
<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-list"> </i> List of Suspended sales	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
								<i class="fa fa-th"></i>
							</span>
						<h5><?php echo lang('sales_suspended_search')?></h5>
					</div>
					<div class="widget-content">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover data-table" id="dTable">
				<thead>	<tr>
					<th><?php echo lang('sales_suspended_sale_id'); ?></th>
					<th><?php echo lang('sales_date'); ?></th>
					<th><?php echo lang('sales_customer'); ?></th>
					<th><?php echo lang('reports_items'); ?></th>
					<th><?php echo lang('sales_comments'); ?></th>
					<th><?php echo lang('sales_unsuspend'); ?></th>
					<th><?php echo lang('sales_receipt'); ?></th>
					<th><?php echo lang('sales_email_receipt'); ?></th>
					<?php if ($this->Employee->has_module_action_permission('sales', 'delete_suspended_sale', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
					<th><?php echo lang('common_delete'); ?></th>
					<?php } ?>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($suspended_sales as $suspended_sale)
				{
				?>
					<tr>
						<td><?php echo $suspended_sale['sale_id'];?></td>
						<td><?php echo date(get_date_format(). ' @ '.get_time_format(),strtotime($suspended_sale['sale_time']));?></td>
						<td>
							<?php
							if (isset($suspended_sale['customer_id']))
							{
								$customer = $this->Customer->get_info($suspended_sale['customer_id']);
								echo $customer->first_name. ' '. $customer->last_name;
							}
							else
							{
							?>
								&nbsp;
							<?php
							}
							?>
						</td>
						<td><?php echo $suspended_sale['items'];?></td>
						<td><?php echo $suspended_sale['comment'];?></td>
						<td >
							<?php 
							echo form_open('sales/unsuspend');
							echo form_hidden('suspended_sale_id', $suspended_sale['sale_id']);
							?>
							<input type="submit" name="submit" value="<?php echo lang('sales_unsuspend'); ?>" id="submit_unsuspend" class="btn btn-primary">
							</form>
						</td>
						<td>
							<?php 
							echo form_open('sales/receipt/'.$suspended_sale['sale_id'], array('method'=>'get', 'class' => 'form_receipt_suspended_sale'));
							?>
							<input type="submit" name="submit" value="<?php echo lang('sales_recp'); ?>" id="submit_receipt" class="btn btn-primary">
							</form>
						</td>
						<td>
						<?php
						if ($suspended_sale['email']) 
						{
							echo form_open('sales/email_receipt/'.$suspended_sale['sale_id'], array('method'=>'get', 'class' => 'form_email_receipt_suspended_sale'));
							?>
								<input type="submit" name="submit" value="<?php echo lang('common_email'); ?>" id="submit_receipt" class="btn btn-primary">
							</form>
						<?php } ?>
						
						</td>
						<td>
							<?php 
							if ($this->Employee->has_module_action_permission('sales', 'delete_suspended_sale', $this->Employee->get_logged_in_employee_info()->person_id)){
						 	echo form_open('sales/delete_suspended_sale', array('class' => 'form_delete_suspended_sale'));
							echo form_hidden('suspended_sale_id', $suspended_sale['sale_id']);
							?>
							<input type="submit" name="submit" value="<?php echo lang('common_delete'); ?>" id="submit_delete" class="btn btn-danger">
							</form>
							<?php } ?>
						</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			</div>
</div></div></div>


<script type="text/javascript">
$(".form_delete_suspended_sale").submit(function()
{
	if (!confirm(<?php echo json_encode(lang("sales_delete_confirmation")); ?>))
	{
		return false;
	}
});

$(".form_email_receipt_suspended_sale").ajaxForm({success: function()
{
	alert("<?php echo lang('sales_receipt_sent'); ?>");
}});	
</script>