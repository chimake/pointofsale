<?php $this->load->view("partial/header"); ?>
 <div class="clear"></div>
 
<div id="sale-grid-big-wrapper" class="clearfix">
<div class="clearfix" id="category_item_selection_wrapper">
	<div class="">
		<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar">
		</div>        
		<div id="category_item_selection" class="row">
		</div>
		<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar">
		</div>
	</div>
    
</div>

<div style= "display: <?php echo $mode == 'store_account_payment' ? 'none': 'block' ;?>" id="show_hide_grid_wrapper">
	<img src="<?php echo base_url()?>img/show_hide.png" alt="show or hide item grid" />
	<a href="javascript: void(0);" class="btn btn-primary" id="show_grid"><?php echo lang('sales_show_grid');?></a>
	<a href="javascript: void(0);" class="btn btn-primary" id="hide_grid"><?php echo lang('sales_hide_grid');?></a>	
</div>

</div>

<div id="register_container" class="sales clearfix">
	<?php $this->load->view("sales/register"); ?>
</div>




<script type="text/javascript">
$(document).ready(function()
{
	$("#show_grid").click(function()
	{
		$("#category_item_selection_wrapper").slideDown();
		$("#show_grid").hide();
		$("#hide_grid").show();
	});

	$("#hide_grid,#hide_grid_top").click(function()
	{
		$("#category_item_selection_wrapper").slideUp();
		$("#show_grid").show();
		$("#hide_grid").hide();
	});
	
 	var current_category = null;

	function load_categories()
	{
		$.get('<?php echo site_url("sales/categories");?>', function(json)
		{
			processCategoriesResult(json);
		}, 'json');	
	}

	$(document).on('click', ".pagination.categories a", function(event)
	{
		$("#category_item_selection_wrapper").mask(<?php echo json_encode(lang('common_wait')); ?>);
		event.preventDefault();
		var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);
	
		$.get('<?php echo site_url("sales/categories");?>/'+offset, function(json)
		{
			processCategoriesResult(json);

		}, "json");
	});

	$(document).on('click', ".pagination.items a", function(event)
	{
		$("#category_item_selection_wrapper").mask(<?php echo json_encode(lang('common_wait')); ?>);
		event.preventDefault();
		var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);
	
		$.post('<?php echo site_url("sales/items");?>/'+offset, {category: current_category}, function(json)
		{
			processItemsResult(json);
		}, "json");
	});

	$('#category_item_selection_wrapper').on('click','.category_item.category', function(event)
	{
		$("#category_item_selection_wrapper").mask(<?php echo json_encode(lang('common_wait')); ?>);
		
		event.preventDefault();
		current_category = $(this).text();
		$.post('<?php echo site_url("sales/items");?>', {category: current_category}, function(json)
		{
			processItemsResult(json);
		}, "json");
	});

	$('#category_item_selection_wrapper').on('click','.category_item.item', function(event)
	{		
		$("#category_item_selection_wrapper").mask(<?php echo json_encode(lang('common_wait')); ?>);
		event.preventDefault();
		$( "#item" ).val($(this).data('id'));
		$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit, success: function()
		{
			<?php
			echo "gritter(".json_encode(lang('common_success')).",".json_encode(lang('items_successful_adding')).",'gritter-item-success',false,false);";
			?>
			$("#category_item_selection_wrapper").unmask();			
		}});
	});

	$("#category_item_selection_wrapper").on('click', '#back_to_categories', function(event)
	{
		$("#category_item_selection_wrapper").mask(<?php echo json_encode(lang('common_wait')); ?>);
		
		event.preventDefault();
		load_categories();
	});

	function processCategoriesResult(json)
	{	
		$("#category_item_selection_wrapper .pagination").removeClass('items').addClass('categories');
		$("#category_item_selection_wrapper .pagination").html(json.pagination);
	
		$("#category_item_selection").html('');
	
		for(var k=0;k<json.categories.length;k++)
		{
			//var category_item = $("<div/>").attr('class', 'category_item category col-md-2').append('<p>'+json.categories[k]+'</p>');
			 var category_item = $("<div/>").attr('class', 'category_item category col-md-2 col-sm-3 col-xs-6').append('<p>'+json.categories[k]+'</p>');
			$("#category_item_selection").append(category_item);
		}
		
		$("#category_item_selection_wrapper").unmask();
	}

	function processItemsResult(json)
	{
		$("#category_item_selection_wrapper .pagination").removeClass('categories').addClass('items');
		$("#category_item_selection_wrapper .pagination").html(json.pagination);

		$("#category_item_selection").html('');
	
		var back_to_categories_button = $("<div/>").attr('id', 'back_to_categories').attr('class', 'category_item back-to-categories col-md-2 col-sm-3 col-xs-6 ').append('<p>&laquo; '+<?php echo json_encode(lang('sales_back_to_categories')); ?>+'</p>');
		//var back_to_categories_button = $("<div/>").attr('id', 'back_to_categories').attr('class', 'category_item item category_list').append('<p>&laquo; '+<?php echo json_encode(lang('sales_back_to_categories')); ?>+'</p>');
		$("#category_item_selection").append(back_to_categories_button);

		for(var k=0;k<json.items.length;k++)
		{
			var image_src = json.items[k].image_src;
			var prod_image = "";
			var item_parent_class = "";
			if (image_src != '' ) {
				var item_parent_class = "item_parent_class";
				var prod_image = '<div class="prod_image"><img style="width:167px; height:80px;" src="'+image_src+'" alt="" /></div>';
			}
			
			var item = $("<div/>").attr('class', 'category_item item col-md-2 col-sm-3 col-xs-6  '+item_parent_class).attr('data-id', json.items[k].id).append(prod_image+'<p>'+json.items[k].name+'</p>');
			$("#category_item_selection").append(item);
			var d_id = json.items[k].id;
			//alert( $("#"+d_id).html());
			//if (image_src != '' )
			 //$("div[data-id='" + d_id + "']").attr('style', 'background:rgba(255, 255, 255, 0.5) url('+image_src+') ;background-size:167px 80px;background-repeat:no-repeat;');
			
		}
		
		$("#category_item_selection_wrapper").unmask();
	
	}
	load_categories();
});
var last_focused_id = null;
setTimeout(function(){$('#item').focus();}, 10);
</script>


<?php $this->load->view("partial/footer"); ?>

