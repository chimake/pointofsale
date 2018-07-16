	<div class="row">
	<div class="col-md-12">
					<div class="form-group">
			<?php echo form_label(lang('common_first_name').':', 'first_name',array('class'=>'required col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
				<?php echo form_input(array(
					'class'=>'form-control',
					'name'=>'first_name',
					'id'=>'first_name',
					'class'=>'form-inps',
					'value'=>$person_info->first_name)
				);?>
			</div>
		</div>

					<div class="form-group">
			<?php echo form_label(lang('common_last_name').':', 'last_name',array('class'=>'required col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control',
				'name'=>'last_name',
				'id'=>'last_name',
				'class'=>'form-inps',
				'value'=>$person_info->last_name)
			);?>
			</div>
		</div>

					<div class="form-group">
			<?php echo form_label(lang('common_email').':', 'email',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label '.($controller_name == 'employees' ? 'required' : 'not_required'))); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control',
				'name'=>'email',
				'id'=>'email',
				'class'=>'form-inps',
				'value'=>$person_info->email)
				);?>
			</div>
		</div>
					<div class="form-group">	
		<?php echo form_label(lang('common_phone_number').':', 'phone_number',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
			<?php echo form_input(array(
				'class'=>'form-control',
				'name'=>'phone_number',
				'id'=>'phone_number',
				'class'=>'form-inps',
				'value'=>$person_info->phone_number));?>
			</div>
		</div>
		<div class="form-group">	
		<?php echo form_label(lang('common_choose_avatar').':', 'phone_number',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
			<div class="col-sm-9 col-md-9 col-lg-10">
	      		<?php echo $person_info->image_id ? img(array('src' => site_url('app_files/view/'.$person_info->image_id),'class'=>'img-polaroid img-polaroid-s')) : img(array('src' => base_url().'/img/avatar.png','class'=>'img-polaroid','id'=>'image_empty')); ?>
			
			</div>
		</div>
	
	</div>
	<div class="form-group">
    <label class="col-sm-3 col-md-3 col-lg-2 control-label ">&nbsp;</label>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<ul class="list-unstyled text-center">
	<li >
	<div id="avatar">
	
	<div class="col-sm-9 col-md-9 col-lg-10">
	      
			
			</div>
	</div>
		<br /><br />
	</li>
		<li>
			 <?php echo form_upload(array(
			    'name'=>'image_id',
			    'id'=>'image_id',
			    'value'=>$person_info->image_id)
			  );?>     
				

		</li>
	
	</ul>
	
	</div>
	</div>
	
	
</div>
	<?php if($person_info->image_id) {  ?>

<div class="form-group">
<?php echo form_label(lang('items_del_image').':', 'del_image',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_checkbox(array(
		'name'=>'del_image',
		'id'=>'del_image',
		'class'=>'form-control delete-checkbox', 
		'value'=>1
	));?>
	</div>
</div>

<?php }  ?>



<div class="form-group">	
<?php echo form_label(lang('common_address_1').':', 'address_1',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'address_1',
		'id'=>'address_1',
		'value'=>$person_info->address_1));?>
	</div>
</div>

			<div class="form-group">	
<?php echo form_label(lang('common_address_2').':', 'address_2',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'address_2',
		'id'=>'address_2',
		'value'=>$person_info->address_2));?>
	</div>
</div>

			<div class="form-group">	
<?php echo form_label(lang('common_city').':', 'city',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'city',
		'id'=>'city',
		'value'=>$person_info->city));?>
	</div>
</div>

			<div class="form-group">	
<?php echo form_label(lang('common_state').':', 'state',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'state',
		'id'=>'state',
		'value'=>$person_info->state));?>
	</div>
</div>

			<div class="form-group">	
<?php echo form_label(lang('common_zip').':', 'zip',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'zip',
		'id'=>'zip',
		'value'=>$person_info->zip));?>
	</div>
</div>

			<div class="form-group">	
<?php echo form_label(lang('common_country').':', 'country',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_input(array(
		'class'=>'form-control form-inps',
		'name'=>'country',
		'id'=>'country',
		'value'=>$person_info->country));?>
	</div>
</div>

	<div class="form-group">	
<?php echo form_label(lang('common_comments').':', 'comments',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	<div class="col-sm-9 col-md-9 col-lg-10">
	<?php echo form_textarea(array(
		'name'=>'comments',
		'id'=>'comments',
		'value'=>$person_info->comments,
		'rows'=>'5',
		'cols'=>'17')		
	);?>
	</div>
</div>
<?php
if ($this->Location->get_info_for_key('mailchimp_api_key'))
{
?>
			<div class="form-group">
	<div class="column">	
		<?php echo form_label(lang('common_mailing_lists').':', 'mailchimp_mailing_lists',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
	</div>
	
    <div class="column">
		<ul style="list-style: none;">
	<?php 
	foreach(get_all_mailchimps_lists() as $list)
	{
		echo '<li>';
		echo form_checkbox(array('name'=> 'mailing_lists[]',
		'id' => $list['id'],
		'value' => $list['id'],
		'checked' => email_subscribed_to_list($person_info->email, $list['id']),
		'label'	=> $list['id']));
		echo ' '.form_label($list['name'], $list['id'], array('style' => 'float: none;'));
		echo '</li>';
	}
	?>
	</ul>
	</div>
	<div class="cleared"></div>
</div>
<?php
}
?> 