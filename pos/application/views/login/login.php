<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $this->config->item('company').' -- '.lang('common_powered_by').' www,optimumlinkup.com.ng' ?></title>
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
            if ($("#username").val() == '')
            {
                $("#username").focus();                   
            }
            else
            {
                $("#password").focus();
            }
		});
    </script>
</head>
<body>
	<?php if ($ie_browser_warning) { ?>
	    <div id="container">
			<div class="alert alert-danger">
				<?php 
				echo lang('login_unsupported_browser');
				?>
			</div>
		</div>
		
	<?php
	 die();
	} ?>
	
	
    <div id="container">
        <div id="logo">
            <?php echo img(
                array(
                    'src' => $this->Appconfig->get_logo_image(),
                    )); ?>
                </div>
                <?php if (is_on_demo_host()) { ?>
                        
                        <div class="alert alert-success text-center msg-demo">

                            <h3><?php echo lang('login_press_login_to_continue'); ?></h3>
                            </div>
                            <?php } ?>
                <div id="loginbox">            
                    <?php echo form_open('login', array('class' => 'form login-form', 'id'=>'loginform', 'autocomplete'=> 'off')) ?>
                    <p><?php echo lang('login_welcome_message'); ?></p>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <?php echo form_input(array(
                        	'name'=>'username', 
							'id'=>'username', 
                            'value'=> '',
                            'class'=> 'form-control',
                            'placeholder'=> lang('login_username'),
                            'size'=>'20')); ?>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <?php echo form_password(array(
                                'name'=>'password', 
								'id' => 'password',
                                'value'=>'',
                                'class'=>'form-control',
                                'placeholder'=> lang('login_password'),
                                'size'=>'20')); ?>

                            </div>
                            <hr />
                            <div class="form-actions">
                                <div class="pull-left">
                                    <a href="#" class="flip-link to-recover"><?php echo anchor('login/reset_password', lang('login_reset_password').'?'); ?></a><br />


                                    <?php echo date("Y")?> <?php echo lang('login_version'); ?> <span class="label label-info"><?php echo APPLICATION_VERSION; ?>

                                </div>
                                <div class="pull-right"><input type="submit" class="btn btn-success" value="<?php echo lang('login_login'); ?>" /></div>
                            </div>
                        </form>

                    </div>
                    
                     <?php if (isset($subscription_cancelled_within_30_days) && $subscription_cancelled_within_30_days === true) { ?>
                            <div class="alert alert-danger"><?php echo lang('login_subscription_cancelled_within_30_days'); ?></div>
                        <?php } ?>
                     

                    <?php if ($application_mismatch) {?>
                    <div class="alert alert-danger">
                        <strong><?php echo json_encode(lang('common_error')); ?></strong> <?php echo $application_mismatch; ?>
                    </div>
                    
                    <?php } ?>
                    <?php if (validation_errors() || $ie_browser_warning) {?>
                    <div class="alert alert-danger">
                        <strong><?php echo lang('common_error'); ?></strong>
                        <?php echo validation_errors(); ?>
                    </div>
                    <?php } ?>

                        

                </div>

            </body>
            </html>