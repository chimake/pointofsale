<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->config->item('company').' -- '.lang('common_powered_by').'www.optimumlinkup.com.ng' ?></title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<base href="<?php echo base_url();?>" />
		<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon"/>
		
		<?php foreach(get_css_files() as $css_file) { ?>
			<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url().$css_file['path'].'?'.APPLICATION_VERSION;?>" media="<?php echo $css_file['media'];?>" />
		<?php } ?>
		<script type="text/javascript">
			var SITE_URL= "<?php echo site_url(); ?>";
		</script>
		
		<?php foreach(get_js_files() as $js_file) { ?>
			<script src="<?php echo base_url().$js_file['path'].'?'.APPLICATION_VERSION;?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<?php } ?>	
		
		<script type="text/javascript">
			COMMON_SUCCESS = <?php echo json_encode(lang('common_success')); ?>;
			COMMON_ERROR = <?php echo json_encode(lang('common_error')); ?>;
			$.ajaxSetup ({
				cache: false,
				headers: { "cache-control": "no-cache" }
			});
			
			$(document).ready(function()
			{
				//Ajax submit current location
				$("#employee_current_location_id").change(function()
				{
					$("#form_set_employee_current_location_id").ajaxSubmit(function()
					{
						window.location.reload(true);
					});
				});	
			});
		</script>
		
	</head>
	<body data-color="grey" class="flat">
		<div class="modal fade hidden-print" id="myModal"></div>
		<div id="wrapper" <?php echo $this->uri->segment(1)=='sales' || $this->uri->segment(1)=='receivings'  ? 'class="minibar"' : ''; ?> >
		<div id="header" class="hidden-print">
			<h1><a href="<?php echo site_url(); ?>"><?php echo img(
				array(
					'src' => base_url().'img/header_logo.png',
					'class'=>'hidden-print header-log',
					'id'=>'header-logo',

				)); ?></a></h1>		
				<a id="menu-trigger" href="#"><i class="fa fa-bars fa fa-2x"></i></a>	
		<div class="clear"></div>
		</div>
		
		
		
		
		<div id="user-nav" class="hidden-print hidden-xs">
			<ul class="btn-group ">
				<li class="btn  hidden-xs"><a title="" href="<?php echo site_url('login/switch_user')?>" data-toggle="modal" data-target="#myModal" ><i class="icon fa fa-user fa-2x"></i> <span class="text">	<?php echo lang('common_welcome')." <b> $user_info->first_name $user_info->last_name! </b>"; ?></span></a></li>
				<li class="btn  hidden-xs disabled" >
					<a title="" href="" onClick="return false;"><i class="icon fa fa-clock-o fa-2x"></i> <span class="text">
					<?php echo date(get_time_format()); ?>
					<?php echo date(get_date_format()) ?>
					</span></a>
				</li>
				<?php if ($this->Employee->has_module_permission('config', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
					<li class="btn "><?php echo anchor("config",'<i class="icon fa fa-cog"></i><span class="text">'.lang("common_settings").'</span>'); ?></li>
				<?php } ?>
        <li class="btn  ">
					<?php
					if ($this->config->item('track_cash') && $this->Sale->is_register_log_open()) {
						echo anchor("sales/closeregister?continue=logout",'<i class="fa fa-power-off"></i><span class="text">'.lang("common_logout").'</span>');
					} else {
						echo anchor("home/logout",'<i class="fa fa-power-off"></i><span class="text">'.lang("common_logout").'</span>');
					}
					?>
				</li>
			</ul>
		</div>
		<?php 
		$sidebar_class = "";
		$sales_content_class = "";
		if ($this->router->fetch_class() == "sales" || $this->router->fetch_class() == "receivings") {
			$sidebar_class = "sales_minibar";
			$sales_content_class = "sales_content_minibar";
		}?>
		<div id="sidebar" class="hidden-print minibar <?php echo $sidebar_class?>">
			
			<ul>
            	<?php if (count($authenticated_locations) > 1) { ?>
				<li id="location-top" class="location-drops">
		        <?php echo form_open('employees/set_employee_current_location_id', array('id' => 'form_set_employee_current_location_id')) ?>
		      		<?php echo form_dropdown('employee_current_location_id', $authenticated_locations,$this->Employee->get_logged_in_employee_current_location_id(),'id="employee_current_location_id"'); ?>
		        <?php echo form_close(); ?>
		</li>
		<?php } ?>
				<li  <?php echo $this->uri->segment(1)=='home'  ? 'class="active"' : ''; ?>><a href="<?php echo site_url(); ?>"><i class="icon fa fa-dashboard"></i><span class="hidden-minibar">Dashboard</span></a></li>
				<?php foreach($allowed_modules->result() as $module) { ?>
					<li <?php echo $module->module_id==$this->uri->segment(1)  ? 'class="active"' : ''; ?>><a href="<?php echo site_url("$module->module_id");?>"><i class="fa fa-<?php echo $module->icon; ?>"></i><span class="hidden-minibar"><?php echo lang("module_".$module->module_id) ?></span></a></li>
				<?php } ?>
                <li>
                	<?php
					if ($this->config->item('track_cash') && $this->Sale->is_register_log_open()) {
						echo anchor("sales/closeregister?continue=logout",'<i class="fa fa-power-off"></i><span class="hidden-minibar">'.lang("common_logout").'</span>');
					} else {
						echo anchor("home/logout",'<i class="fa fa-power-off"></i><span class="hidden-minibar">'.lang("common_logout").'</span>');
					}
					?>

                </li>
			</ul>
		</div>
        
       
        
		<div id="content"  class="clearfix <?php echo $sales_content_class?>" >
		
