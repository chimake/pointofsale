<?php $this->load->view("partial/header"); ?>

<div id="content-header">
	<h1><i class="fa fa-beaker"></i>  <?php echo lang('reports_reports'); ?> - <?php echo $title ?></h1> 
</div>


<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	
</div>
<div class="clear"></div>
<style type="text/css">
	.ui-autocomplete-loading {
        background: white url('images/spinner_small.gif') 0% 5% no-repeat;
    }
	.item_table { padding-left: 40px; font: 12px Arial;}
	span.required { color: #FF0000; }

	/* Add / Remove Images */
	a.AddCondition {
		background-image: url(data:image/gif;base64,R0lGODlhEAAQAPcAAAAAAP///5i+lTyGNUeNQEiOQVKVTJi+lN7p3d3o3ESMO0WMO0iPP1SVTLjRtUKNNkOPOEmOP3DBY16bVWCdV4HMdXm9boXNeYbJfI7Sg4nIf47MhJTTisXowFCZQXrGa2OgV37Hb4rPfYbJeozLgZXUipfVi5bUi5PNiJ3YkqDZlaDZlqXbm6/fprfgr73ktqTGnqPFnc7pydzx2GWrVXG+X2qsW3zDa2eiWpbTia/fpbPdqbXfrL7ittfu0tTrz8XawODy3OPs4eLr4FeeRWSgVGaiV3etaHWsZ5nRi5jMirTdqrLbqLbdrLjdr8bawebu5HC4WW61WGirU2+1WXnBZGuqWGyqWn7BaX25an25a3+5bYrCearUncvmw8jcwsncw2+1WHK5W3O6XHS3XHe8YH2+Z3i0ZIm+eI/CfZfMhZLFgIm5eJjLhpjMh7XbqLXRrLfTrnS3W3y6ZH28Zn6yaoK1boK0bo2+e4i3dpfHhaTOlKbQlqbPlqvUnLTaprPZpbTZpbfaqrvcr+bu44a2cZS/gZW/gurx54y7dpO/fpK+fsLatsHZtdXlzerw5/n5+f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAJEALAAAAAAQABAAAAjPACEJHEiwYMFHjA4VusMmDiGDkBwt2uPlhwwnW46AKego0aAgL1is0NGBi5EnAxEpEjQjBYoNJ0pkcKEFhxCBjfT4UMFBQ4AAF26E4EEEhkBDTVqQwGDhp40yY5KsoSDQTg8TI35qDRAFSxcGAuvsEFFh688oZvwoEJhHSY4PEmicjSJGDZ4GAuFMWVKlRpmzUsj8gXBAIBQkWZhAjUIljBxAVwogGPilyJk3bujMaRPIygAHBYGA8JCGTx80DwiANjgkxoQICwwISACxdsGAADs=);	
		display: block;
		float: left;
		text-indent: -9999px;
		width: 16px;
		height: 16px;
	}
	a.DelCondition {
		background-image: url(data:image/gif;base64,R0lGODlhEAAQAPcAAAAAAP///+FvcON4efDi3ePAtfDh3LpSNL5WOb9cP71bP8FmS9mjk9mklMRQNL9TOcBZPsNkS8ZqU/zHusFNM8BVPfaCaMhqVfaEbPiMdu6KdfCMd/GOeveTfviUf/qah/qjkfi2qN6mm/3b1PLj4MxSPPNzXfN5Y8tlVMxoV/iGcPCFcPmSfvqTf/eRfvCRf/qdi/qrnfq6rt6nnfzUzfnTzOnFv/Li3+5mUs1gUc5iU/J3Y/upnPWsofivpPWvpfS0qvm5r/nLxOrFv9BPPtNwZPSOge6MgferofarouvFwevGwv3c2OlZTeZYTOlbT+ZZTupcUNtWS/BkVuxfVNddUepmXNZgVO5qXuNrYeNuY+JwZtVtY+l7cO+GfvGdlvjDvuZWTOZaUuljW+BlXOR4cfCDe+l/eOJ7ddx3cu6EfeqDfe2TjvGclvWmofOno+etqeivrPTk4+ZWUOddWdtoZNtraNxuaeh6dd54c+6Sj/SinvWjn/Ktqt1rauJ4eOJ+fOF+fPSgnu2ysu2zs/LLy/bm5vn5+f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAIgALAAAAAAQABAAAAjLAA8JHEiwYEFDhAL5sZMnjhyDhwr9YQOmhhAgW7gsKVhIQB8mMkDA4DGhi44hAw0NeDPiwwsOHlhkCKElBQmBg9bQgNFBA4afGE74IDJDIKAfMTZQWcp0h5EzFwTeCdJiRYCrWJtg+VJBYB0kKixgzWqlDQKBaY64MNGkrdsnZtBEEAhHSpIpONw2cSKGjwMGAuUUydKDSpQmUMLM2XMlAYGBSnKQceNlDB01gqocKFDQBooSePToKUNBAWeDN0RIgPBgQQMDEGMXDAgAOw==);		display: block;
		float: left;
		text-indent: -9999px;
		width: 16px;
		height: 16px;
	}
	span.actionCondition {
		float: left;
		font-weight: bold;
		margin-right: 5px;
	}
	
	table.conditions {
		width: 900px;
		border: 1px solid #DDDDDD;
	}
	
	table.conditions tr.duplicate td {
		padding: 10px 0px;
	}

	table.conditions tr.duplicate td.field {
		padding-left: 5px;
	}

	table.conditions tr.duplicate td.field select {
		width: 200px;
	}
	
	table.conditions tr.duplicate td.value textarea {
		height: 20px;
		resize: none;
		overflow-y: hidden;
		padding-left: 16px;
		-webkit-transition:height .1s ease-in-out;
		-moz-transition:height .1s ease-in-out;
		-o-transition:height .1s ease-in-out;
		-ms-transition:height .1s ease-in-out;
		transition:height .1s ease-in-out;	
	}
	
</style>

<script type="text/javascript">
(function($) 
{
  	$.fn.tokenize = function(options)
	{
		var settings = $.extend({}, {prePopulate: false}, options);
    	return this.each(function() 
		{
      		$(this).tokenInput('<?php echo site_url("reports/sales_generator"); ?>?act=autocomplete',
			{
				theme: "facebook",
				queryParam: "term",
				extraParam: "w",
				hintText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_hintText"));?>,
				noResultsText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_noResultsText"));?>,
				searchingText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_searchingText"));?>,
				preventDuplicates: true,
				prePopulate: settings.prePopulate
			});
    	});
 	}
})(jQuery);

$(document).on('change', "#matchType", function(){
	if ($(this).val() == 'matchType_All')
	{
		$("#matched_items_only").prop('disabled', false);
		$(".actions span.actionCondition").html(<?php echo json_encode(lang("reports_sales_generator_matchType_All_TEXT"));?>);
	}
	else 
	{
		$("#matched_items_only").prop('checked', false);
		$("#matched_items_only").prop('disabled', true);
		$(".actions span.actionCondition").html(<?php echo json_encode(lang("reports_sales_generator_matchType_Or_TEXT"));?>);
	}
});


$(document).on('click', "a.AddCondition", function(e){
	var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":""});
	$('.conditions tr.duplicate:last').clone().insertAfter($('.conditions tr.duplicate:last'));
	$("input", $('.conditions tr.duplicate:last')).parent().html("").append(sInput).children("input").tokenize();
	$("option", $('.conditions tr.duplicate:last select')).removeAttr("disabled").removeAttr("selected").first().prop("selected", true);
	
	$('.conditions tr.duplicate:last').trigger('change');
	e.preventDefault();
})

$(document).on('click', "a.DelCondition", function(e){
	if ($(this).parent().parent().parent().children().length > 1)
		$(this).parent().parent().remove();
	
	e.preventDefault();
})

$(document).on('change', ".selectField", function(){
	var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":""});
	var field = $(this);
	// Remove Value Field
	field.parent().parent().children("td.value").html("");
	if ($(this).val() == 0) 
	{
		field.parent().parent().children("td.condition").children(".selectCondition").prop("disabled", true);	
		field.parent().parent().children("td.value").append(sInput.prop("disabled", true));		
	} 
	else 
	{
		field.parent().parent().children("td.condition").children(".selectCondition").removeAttr("disabled");	
		if ($(this).val() == 2 || $(this).val() == 7 || $(this).val() == 10 || $(this).val() == 12) 
		{
			field.parent().parent().children("td.value").append(sInput);		
		} 
		else 
		{
			if ($(this).val() == 6) 
			{
				field.parent().parent().children("td.value").append($("<input />").attr({"type": "hidden", "name": "value[]", "value":""}));		
			} 
			else 
			{
				field.parent().parent().children("td.value").append(sInput.attr("w", $("option:selected", field).attr('rel'))).children("input").tokenize();		
			}
		}
		disableConditions(field, true);
	}
});

$(function() {
	<?php
		if (isset($prepopulate) and count($prepopulate) > 0) {
			echo "var prepopulate = ".json_encode($prepopulate).";";
		}
	?>
	var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":""});
	$(".selectField").each(function(i) {
		if ($(this).val() == 0) {
			$(this).parent().parent().children("td.condition").children(".selectCondition").prop("disabled", true);
			$(this).parent().parent().children("td.value").html("").append(sInput.prop("disabled", true));	
		} else {
			if ($(this).val() != 2 && $(this).val() != 6 && $(this).val() != 7 && $(this).val() != 10 && $(this).val() != 12) {
				$(this).parent().parent().children("td.value").children("input").attr("w", $("option:selected", $(this)).attr('rel')).tokenize({prePopulate: prepopulate.field[i][$(this).val()] });	
			}
			if ($(this).val() == 6) {
				$(this).parent().parent().children("td.value").html("").append($("<input />").attr({"type": "hidden", "name": "value[]", "value":""}));	
			}
			disableConditions($(this), false);
		}
	});
	
	$("#start_month, #start_day, #start_year, #end_month, #end_day, #end_year").change(function()
	{
		$("#complex_radio").prop('checked', true);
	});

	$("#report_date_range_simple").change(function()
	{
		$("#simple_radio").prop('checked', true);
	});
});

function disableConditions(elm, q) {
	var allowed1 = ['1', '2'];
	var allowed2 = ['7', '8', '9'];
	var allowed3 = ['10', '11'];
	var allowed4 = ['1', '2', '7', '8', '9'];
	var allowed5 = ['1'];
	var disabled = elm.parent().parent().children("td.condition").children(".selectCondition");
	
	if (q == true)
		$("option", disabled).removeAttr("selected");
	
	$("option", disabled).prop("disabled", true);
	$("option", disabled).each(function() {
		if (elm.val() == 11 && $.inArray($(this).attr("value"), allowed5) != -1) {
			$(this).removeAttr("disabled");
		}else if (elm.val() == 10 && $.inArray($(this).attr("value"), allowed4) != -1) {
			$(this).removeAttr("disabled");
		} else if (elm.val() == 6 && $.inArray($(this).attr("value"), allowed3) != -1) {
			$(this).removeAttr("disabled");
		} else if (elm.val() == 7 && $.inArray($(this).attr("value"), allowed2) != -1) {
			$(this).removeAttr("disabled");
		} else if (elm.val() != 6 && elm.val() != 7 && elm.val() != 10 && elm.val() != 11 && $.inArray($(this).attr("value"), allowed1) != -1) {
			$(this).removeAttr("disabled");
		} 
	});
	
	if (q == true)
		$("option:not(:disabled)", disabled).first().prop("selected", true);
}

</script>
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-th"></i>
					</span>
					<h5 ><?php echo lang('reports_date_range'); ?></h5>
				</div>
				<div class="widget-content nopadding">

<form name="salesReportGenerator" action="<?php echo site_url("reports/sales_generator"); ?>" method="get" class="form-horizontal form-horizontal-mobiles">
<div class="table-responsive">
<table id="contents" class="table">

	<tr>
		<td class="item_table">
			<div class="control-group">	
			<?php echo form_label(lang('reports_date_range'), 'report_date_range_label', array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
			<div id='report_date_range_simple' class="controls">
				<input type="radio" name="report_type" id="simple_radio" value='simple'<?php if ($report_type != 'complex') { echo " checked='checked'"; }?>/>
				<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, $sreport_date_range_simple, 'id="report_date_range_simple"'); ?>
			</div>
			
			<div id='report_date_range_complex'  class="controls">
				<input type="radio" name="report_type" id="complex_radio" value='complex'<?php if ($report_type == 'complex') { echo " checked='checked'"; }?>/>
				<?php echo form_dropdown('start_month',$months, $start_month, 'id="start_month"'); ?>
				<div class="mobile_break">&nbsp;</div>
				<?php echo form_dropdown('start_day',$days, $start_day, 'id="start_day"'); ?>
				<div class="mobile_break">&nbsp;</div>
				<?php echo form_dropdown('start_year',$years, $start_year, 'id="start_year"'); ?>
				<div class="mobile_break">&nbsp;</div>
				<span class="forms_to">-</span>
				<div class="mobile_break">&nbsp;</div>
				<?php echo form_dropdown('end_month',$months, $end_month, 'id="end_month"'); ?>
				<div class="mobile_break">&nbsp;</div>
				<?php echo form_dropdown('end_day',$days, $end_day, 'id="end_day"'); ?>
				<div class="mobile_break">&nbsp;</div>
				<?php echo form_dropdown('end_year',$years, $end_year, 'id="end_year"'); ?>
			</div>
			</div>
		</td>
	</tr>
	<tr>
		<td class="item_table">&nbsp;</td>
	</tr>
	<tr>
		<td class="item_table">
			<?php echo form_label(lang('reports_sales_generator_matchType'), 'matchType', array('class'=>'required')); ?><br />		
			<select name="matchType" id="matchType">
				<option value="matchType_All"<?php if ($matchType != 'matchType_All') { echo " selected='selected'"; }?>><?php echo lang('reports_sales_generator_matchType_All')?></option>
				<option value="matchType_Or"<?php if ($matchType == 'matchType_Or') { echo " selected='selected'"; }?>><?php echo lang('reports_sales_generator_matchType_Or')?></option>
			</select>
			<br />
			<em>
				<?php echo lang('reports_sales_generator_matchType_Help')?>
			</em>
		</td>
	</tr>
		<tr>
		<td class="item_table">&nbsp;</td>
	</tr>

	<tr>
		<td class="item_table">
			<?php echo form_label(lang('reports_sales_generator_show_only_matched_items'), 'matched_items_only'); ?>
			<?php
				$matched_items_checkbox =	array(
			    'name'        => 'matched_items_only',
			    'id'          => 'matched_items_only',
			    'value'       => '1',
			    'checked'     => $matched_items_only,
		    	);
				
				if ($matchType == 'matchType_Or')
				{
					$matched_items_checkbox['disabled'] = 'disabled';
				}
			?>
			&nbsp;&nbsp;<?php echo form_checkbox($matched_items_checkbox); 
			?>
		</td>
	</tr>
	
	<tr>
		<td class="item_table">
			<?php echo form_label(lang('reports_export_to_excel'), 'reports_export_to_excel'); ?> 
			
			&nbsp;&nbsp;<?php echo form_checkbox(array(
			    'name'        => 'export_excel',
			    'id'          => 'export_excel',
			    'value'       => '1'
		    	)); ?>
			
		</td>
		
	</tr>

	<tr>
		<td class="item_table">&nbsp;</td>
	</tr>
	<tr>
		<td class="item_table">
		<div class="table-responsive">
			<table class="table conditions">
				<?php
					if (isset($field) and $field[0] > 0) {
						foreach ($field as $k => $v) {
				?>
				<tr class="duplicate">
					<td class="field">
						<select name="field[]" class="selectField ">
							<option value="0"><?php echo lang("reports_sales_generator_selectField_0") ?></option>						
							<option value="1" rel="customers"<?php if($field[$k] == 1) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_1") ?></option>
							<option value="2" rel="itemsSN"<?php if($field[$k] == 2) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_2") ?></option>
							<option value="3" rel="employees"<?php if($field[$k] == 3) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_3") ?></option>
							<option value="4" rel="itemsCategory"<?php if($field[$k] == 4) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_4") ?></option>
							<option value="5" rel="suppliers"<?php if($field[$k] == 5) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_5") ?></option>
							<option value="6" rel="saleType"<?php if($field[$k] == 6) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_6") ?></option>
							<option value="7" rel="saleAmount"<?php if($field[$k] == 7) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_7") ?></option>
							<option value="8" rel="itemsKitName"<?php if($field[$k] == 8) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_8") ?></option>
							<option value="9" rel="itemsName"<?php if($field[$k] == 9) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_9") ?></option>
							<option value="10" rel="saleID"<?php if($field[$k] == 10) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_10") ?></option>
							<option value="11" rel="paymentType"<?php if($field[$k] == 11) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_11") ?></option>
							<option value="12" rel="saleItemDescription"<?php if($field[$k] == 12) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_12") ?></option>
						</select>
					</td>
					<td class="condition">
						<select name="condition[]" class="selectCondition ">
							<option value="1"<?php if($condition[$k] == 1) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_1")?></option>
							<option value="2"<?php if($condition[$k] == 2) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_2")?></option>
							<option value="7"<?php if($condition[$k] == 7) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_7")?></option>
							<option value="8"<?php if($condition[$k] == 8) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_8")?></option>
							<option value="9"<?php if($condition[$k] == 9) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_9")?></option>
							<option value="10"<?php if($condition[$k] == 10) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_10")?></option>
							<option value="11"<?php if($condition[$k] == 11) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_11")?></option>
						</select>
					</td>
					<td class="value">
						<input type="text" name="value[]" w="" value="<?php echo $value[$k]; ?>"/>
					</td>
					<td class="actions">
						<span class="actionCondition">
						<?php 
							if ($matchType == 'matchType_Or') {
								echo lang("reports_sales_generator_matchType_Or_TEXT");
							} else {
								echo lang("reports_sales_generator_matchType_All_TEXT");					
							}
						?>
						</span>
						<a class="AddCondition" href="#" title="<?php echo lang("reports_sales_generator_addCondition")?>"><?php echo lang("reports_sales_generator_addCondition")?></a>
						<a class="DelCondition" href="#" title="<?php echo lang("reports_sales_generator_delCondition")?>"><?php echo lang("reports_sales_generator_delCondition")?></a>
					</td>
				</tr>				
				<?php
						}
					} else {
				?>
				<tr class="duplicate">
					<td class="field">
						<select name="field[]" class="selectField span7">
							<option value="0"><?php echo lang("reports_sales_generator_selectField_0") ?></option>						
							<option value="1" rel="customers"><?php echo lang("reports_sales_generator_selectField_1") ?></option>
							<option value="2" rel="itemsSN"><?php echo lang("reports_sales_generator_selectField_2") ?></option>
							<option value="3" rel="employees"><?php echo lang("reports_sales_generator_selectField_3") ?></option>
							<option value="4" rel="itemsCategory"><?php echo lang("reports_sales_generator_selectField_4") ?></option>
							<option value="5" rel="suppliers"><?php echo lang("reports_sales_generator_selectField_5") ?></option>
							<option value="6" rel="saleType"><?php echo lang("reports_sales_generator_selectField_6") ?></option>
							<option value="7" rel="saleAmount"><?php echo lang("reports_sales_generator_selectField_7") ?></option>
							<option value="8" rel="itemsKitName"><?php echo lang("reports_sales_generator_selectField_8") ?></option>
							<option value="9" rel="itemsName"><?php echo lang("reports_sales_generator_selectField_9") ?></option>
							<option value="10" rel="saleID"><?php echo lang("reports_sales_generator_selectField_10") ?></option>
							<option value="11" rel="paymentType"><?php echo lang("reports_sales_generator_selectField_11") ?></option>
							<option value="12" rel="saleItemDescription"><?php echo lang("reports_sales_generator_selectField_12") ?></option>
						</select>
					</td>
					<td class="condition">
						<select name="condition[]" class="selectCondition ">
							<option value="1"><?php echo lang("reports_sales_generator_selectCondition_1")?></option>
							<option value="2"><?php echo lang("reports_sales_generator_selectCondition_2")?></option>
							<option value="7"><?php echo lang("reports_sales_generator_selectCondition_7")?></option>
							<option value="8"><?php echo lang("reports_sales_generator_selectCondition_8")?></option>
							<option value="9"><?php echo lang("reports_sales_generator_selectCondition_9")?></option>
							<option value="10"><?php echo lang("reports_sales_generator_selectCondition_10")?></option>
							<option value="11"><?php echo lang("reports_sales_generator_selectCondition_11")?></option>
						</select>
					</td>
					<td class="value">
						<input type="text" name="value[]" w="" value=""/>
					</td>
					<td class="actions">
						<span class="actionCondition">
						<?php 
							if ($matchType == 'matchType_Or') {
								echo lang("reports_sales_generator_matchType_Or_TEXT");
							} else {
								echo lang("reports_sales_generator_matchType_All_TEXT");					
							}
						?>
						</span>
						<a class="AddCondition" href="#" title="<?php echo lang("reports_sales_generator_addCondition")?>"><?php echo lang("reports_sales_generator_addCondition")?></a>
						<a class="DelCondition" href="#" title="<?php echo lang("reports_sales_generator_delCondition")?>"><?php echo lang("reports_sales_generator_delCondition")?></a>
					</td>
				</tr>
			
				<?php
					}
				?>
			</table>
			</div>
		</td>
	</tr>	
	<tr>
		<td class="item_table" style="padding-top: 15px;">
			<button name="generate_report" type="submit" value="1" id="generate_report" class="submit_button btn btn-primary btn-large"><?php echo lang('common_submit')?></button>
		</td>
	</tr>		
</table>
</div>
</form>

<br />
<?php 
	if (isset($results)) echo $results;
?>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>