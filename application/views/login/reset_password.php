<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $this->config->item('company').' -- '.lang('common_powered_by').' www.PhpSoftwares.com' ?></title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<base href="<?php echo base_url();?>" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/font-awesome.min.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/unicorn-login.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/unicorn-login-custom.css?<?php echo APPLICATION_VERSION; ?>" />

	<script src="<?php echo base_url();?>js/jquery.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/bootstrap.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>

	<script type="text/javascript">
		$(document).ready(function()
		{
				//If we have an empty username focus
				if ($("#login_form input:first").val() == '')
				{
					$("#login_form input:first").focus();					
				}
				else
				{
					$("#login_form input:last").focus();
				}
			});
	</script>
</head>
<body>
	<div id="container">
		<div id="logo">
			<?php echo img(
				array(
					'src' => $this->Appconfig->get_logo_image(),
					)); ?>
				</div>
				<?php if (is_on_demo_host()) { ?>
                        
						<div class="alert alert-success text-center">

                            <h2><?php echo lang('login_press_login_to_continue'); ?></h2>
                            </div>
                            <?php } ?>
                <div id="loginbox">            
					            <?php echo form_open('login/do_reset_password_notify',array('class'=>'form login-form')); ?>
<h2><?php echo lang('login_reset_password'); ?></h2>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <?php echo form_input(array(
                            'name'=>'username_or_email', 
                            'class'=>'form-control', 
                            'placeholder'=>lang('login_username'), 
                            'size'=>'20')); ?>
						</div>
							<hr />
							<div class="form-actions">
								<div class="pull-left">
									<?php echo anchor('login', lang('login_login')); ?><br />


									<?php echo date("Y")?> <?php echo lang('login_version'); ?> <span class="label label-info"><?php echo APPLICATION_VERSION; ?>

								</div>
								<div class="pull-right"><input type="submit" class="btn btn-success" value="<?php echo lang('login_reset_password'); ?>" /></div>
							</div>
						</form>

					</div>
						<?php if (isset($error)) {?>
                <div class="alert alert-danger">
                    <strong><?php echo lang('common_error'); ?></strong>
                    <?php echo $error; ?>
                </div>
            <?php } else if(isset($success)){ ?>
                <div class="alert alert-success">
                    <strong><?php echo lang('common_success'); ?></strong> 
                    <?php echo $success; ?>
                </div>
                <?php } ?>
            

				</div>

			</body>
			</html>