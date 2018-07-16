function checkbox_click(event)
{
	event.stopPropagation();
	do_email(enable_email.url);
	if($(event.target).prop('checked'))
	{
		$(event.target).parent().parent().find("td").addClass('selected').css("backgroundColor","");		
	}
	else
	{
		$(event.target).parent().parent().find("td").removeClass('selected');		
	}
	
	determine_checkbox_status();
}

function enable_search(suggest_url,confirm_search_message)
{
	//Keep track of enable_email has been called
	if(!enable_search.enabled)
		enable_search.enabled=true;

	$('#search').click(function()
    {
    	$(this).attr('value','');
    });


	$( "#search" ).autocomplete({
 		source: suggest_url,
		delay: 10,
 		autoFocus: false,
 		minLength: 0,
 		select: function( event, ui ) 
 		{
			$( "#search" ).val(ui.item.label);
 			do_search(true);
 		}
	});

	$('#search_form').submit(function(event)
	{
		event.preventDefault();

		if(get_selected_values().length >0)
		{
			if(!confirm(confirm_search_message))
				return;
		}
		do_search(true);
	});
}
enable_search.enabled=false;

function do_search(show_feedback,on_complete)
{	
	//If search is not enabled, don't do anything
	if(!enable_search.enabled)
		return;
		
	if(show_feedback)
		$('#spinner').show();
	
		$('#search_form').ajaxSubmit({
			success:function(response)
			{
				if(typeof on_complete=='function')
					on_complete();
				$('#sortable_table tbody').html(response.manage_table);
				$('.pagination').html(response.pagination);		
				$('#spinner').hide();
				update_sortable_table();	
				enable_row_selection();		
				$('#sortable_table tbody :checkbox').click(checkbox_click);
				$("#select_all").attr('checked',false);
			},
			dataType: 'json'
		});
		
}

function enable_email(email_url)
{
	//Keep track of enable_email has been called
	if(!enable_email.enabled)
		enable_email.enabled=true;

	//store url in function cache
	if(!enable_email.url)
	{
		enable_email.url=email_url;
	}
	
	$('#select_all, #sortable_table tbody :checkbox').click(checkbox_click);
}
enable_email.enabled=false;
enable_email.url=false;

function do_email(url)
{
	//If email is not enabled, don't do anything
	if(!enable_email.enabled)
		return;

	$.post(url, { 'ids[]': get_selected_values() },function(response)
	{
		$('#email').attr('href',response);
	});

}

function enable_checkboxes()
{
	$('#select_all, #sortable_table tbody :checkbox').click(checkbox_click);
}

function enable_delete(confirm_message,none_selected_message)
{
	//Keep track of enable_delete has been called
	if(!enable_delete.enabled)
		enable_delete.enabled=true;
	
	$('#delete').click(function(event)
	{
		event.preventDefault();
		if($("#sortable_table tbody :checkbox:checked").length >0)
		{
			if(confirm(confirm_message))
			{
				do_delete($("#delete").attr('href'));
			}
		}
	});
}
enable_delete.enabled=false;

function do_delete(url)
{
	//If delete is not enabled, don't do anything
	if(!enable_delete.enabled)
		return;
	
	var row_ids = get_selected_values();
	var selected_rows = get_selected_rows();
	$.post(url, { 'ids[]': row_ids },function(response)
	{
		//delete was successful, remove checkbox rows
		if(response.success)
		{
			gritter(COMMON_SUCCESS,response.message,'gritter-item-success',false,false);
			
			$(selected_rows).each(function(index, dom)
			{
				$(this).find("td").addClass({backgroundColor:"#FF0000"},1200,"linear")
				.end().animate({opacity:0},1200,"linear",function()
				{
					$(this).remove();
					//Re-init sortable table as we removed a row
					update_sortable_table();
					
				});
			});	
		}
		else
		{
			gritter(COMMON_ERROR,response.message,'gritter-item-error',false,false);
		}
		

	},"json");
}

function enable_select_all()
{
	//Keep track of enable_select_all has been called
	if(!enable_select_all.enabled)
		enable_select_all.enabled=true;

	$('#select_all').click(function()
	{
		if($(this).prop('checked'))
		{	
			$('#selectall').show('medium');
			$("#sortable_table tbody :checkbox").each(function()
			{
				$(this).prop('checked',true);
				$(this).parent().parent().find("td").addClass('selected').css("backgroundColor","");

			});
		}
		else
		{
			$('#selectall').hide('medium');
			$('#selectnone').hide('medium');
			$("#sortable_table tbody :checkbox").each(function()
			{
				$(this).prop('checked',false);
				$(this).parent().parent().find("td").removeClass('selected');				
			});    	
		}
	 });	
}
enable_select_all.enabled=false;

function enable_row_selection(rows)
{
	//Keep track of enable_row_selection has been called
	if(!enable_row_selection.enabled)
		enable_row_selection.enabled=true;
	
	if(typeof rows =="undefined")
		rows=$("#sortable_table tbody tr");
	
	rows.hover(
		function row_over()
		{
			$(this).css("cursor","pointer");
		},
		
		function row_out()
		{
			if(!$(this).find("td").hasClass("selected"))
			{
				$(this).find("td").removeClass('over');
			}
		}
	);
	
	rows.click(function row_click(event)
	{
		var checkbox = $(this).find(":checkbox");
		checkbox.prop('checked',!checkbox.prop('checked'));
		do_email(enable_email.url);
		
		if(checkbox.prop('checked'))
		{
			$(this).find("td").addClass('selected').css("backgroundColor","");
		}
		else
		{
			$(this).find("td").removeClass('selected').css("backgroundColor","");
		}
		
		determine_checkbox_status();
	});
}
enable_row_selection.enabled=false;

function update_sortable_table()
{
	//let tablesorter know we changed <tbody> and then triger a resort
	$("#sortable_table").trigger("update");

	if(typeof $("#sortable_table")[0].config!="undefined")
	{
		var sorting = $("#sortable_table")[0].config.sortList; 		
		$("#sortable_table").trigger("sorton",[sorting]);
	}
}

function update_row(row_id,url)
{
	$.post(url, { 'row_id': row_id },function(response)
	{
		//Replace previous row
		var row_to_update = $("#sortable_table tbody tr :checkbox[value="+row_id+"]").parent().parent();
		row_to_update.replaceWith(response);	
		reinit_row(row_id);
		highlight_row(row_id);
	});
}

function reinit_row(checkbox_id)
{
	var new_checkbox = $("#sortable_table tbody tr :checkbox[value="+checkbox_id+"]");
	var new_row = new_checkbox.parent().parent();
	enable_row_selection(new_row);
	//Re-init some stuff as we replaced row
	update_sortable_table();
	//re-enable e-mail
	new_checkbox.click(checkbox_click);	
}

function highlight_row(checkbox_id)
{
	var new_checkbox = $("#sortable_table tbody tr :checkbox[value="+checkbox_id+"]");
	var new_row = new_checkbox.parent().parent();

	new_row.find("td").animate({backgroundColor:"#e1ffdd"},"slow","linear")
		.animate({backgroundColor:"#e1ffdd"},5000)
		.animate({backgroundColor:"#e9e9e9"},"slow","linear");
}

function get_selected_values()
{
	var selected_values = new Array();
	$("#sortable_table tbody :checkbox:checked").each(function()
	{
		selected_values.push($(this).val());
	});
	return selected_values;
}

function get_selected_rows() 
{ 
	var selected_rows = new Array(); 
	$("#sortable_table tbody :checkbox:checked").each(function() 
	{ 
		selected_rows.push($(this).parent().parent()); 
	}); 
	return selected_rows; 
}

function get_visible_checkbox_ids()
{
	var row_ids = new Array();
	$("#sortable_table tbody :checkbox").each(function()
	{
		row_ids.push($(this).val());
	});
	return row_ids;
}

function determine_checkbox_status()
{
	if ($("#sortable_table tbody :checkbox:checked").length > 0)
	{
		$("#email").removeClass("disabled");
		$("#delete").removeClass("disabled");
		$("#generate_barcodes").removeClass("disabled");
		$("#generate_barcode_labels").removeClass("disabled");
		$("#bulk_edit").removeClass("disabled");
	}
	else
	{
		$("#email").addClass("disabled");
		$("#delete").addClass("disabled");
		$("#generate_barcodes").addClass("disabled");
		$("#generate_barcode_labels").addClass("disabled");
		$("#bulk_edit").addClass("disabled");
	}
}

function enable_cleanup(confirm_message)
{
	if(!enable_cleanup.enabled)
		enable_cleanup.enabled=true;
	
	$('#cleanup').click(function(event)
	{
		do_cleanup(event, confirm_message);
	});
}	
enable_cleanup.enabled=false;

function do_cleanup(event, confirm_message)
{
	event.preventDefault();
	
	if(!enable_cleanup.enabled)
		return;

	if (confirm(confirm_message))
	{
		$.post($('#cleanup').attr('href'), {},function(response)
		{
			gritter(COMMON_SUCCESS,response.message,'gritter-item-success',false,false);
		}, 'json');	
	}
}


function enable_sorting(sort_url,table_columns, per_page, order_col, order_dir)
{
	if(!enable_sorting.enabled)
	{
		enable_sorting.enabled=true;
	}
	var offset=0;
	if($("#pagination_top").find('strong').text() > 0 )
	{
		offset = ($("#pagination_top").find('strong').text() - 1) * per_page;
	}

	//Set default headers based on order_col and order_dir	
	var sort_index = table_columns.indexOf(order_col);
	if (order_dir == 'asc')
	{
		$('#sortable_table tr th').removeClass('header headerSortUp').removeClass('header headerSortDown');
		$('#sortable_table tr th').eq(sort_index).addClass('header headerSortUp');
	}
	else
	{
		$('#sortable_table tr th').removeClass('header headerSortUp').removeClass('header headerSortDown');
		$('#sortable_table tr th').eq(sort_index).addClass('header headerSortDown');	
	}
	
	$('#sortable_table tr th').click(function()
	{
		if (table_columns[$(this).parent().children().index($(this))])
		{
			$('#sortable_table tbody').html('<img src="img/spinner.gif"  width="16" height="16" />');

			if ($(this).hasClass('headerSortUp'))
			{	
				do_sorting(sort_url, 0, table_columns[$(this).parent().children().index($(this))], "desc");
				$('#sortable_table tr th').removeClass('header headerSortUp').removeClass('header headerSortDown');
				$(this).removeClass('header headerSortUp').addClass('header headerSortDown');
			}
			else
			{				
				do_sorting(sort_url, 0, table_columns[$(this).parent().children().index($(this))], "asc");
				$('#sortable_table tr th').removeClass('header headerSortUp').removeClass('header headerSortDown');
				$(this).removeClass('header headerSortUp').addClass('header headerSortUp');
			}
		}
	});	
	
	$(document).on('click', ".pagination a", function(event)
	{
		event.preventDefault();
		var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);
		var table_column_index = $('#sortable_table tr th.header').parent().children().index($('#sortable_table tr th.header'));
		var sort_dir = $('#sortable_table tr th.headerSortDown').length == 1 ? 'desc' : 'asc';
		do_sorting($(this).attr('href'), offset, table_column_index >=0 ? table_columns[table_column_index] : 0, sort_dir);
	});
	
}
enable_sorting.enabled=false;

function do_sorting(sort_url, offset, order_col, order_dir)
{
	//alert(sort_url);
	var params = { "search": $("#search").val(), "offset" : offset , "order_col" : order_col, "order_dir" : order_dir};
	
	if ($("#category").length == 1)
	{
		params['category'] = $("#category").val();
	}
	$.post(sort_url,params, function(response)
	{
		$('#sortable_table tbody').html(response.manage_table);
		$('.pagination').html(response.pagination);
		//re-init elements in new table, as table tbody children were replaced
		update_sortable_table();	
		enable_row_selection();		
		$('#sortable_table tbody :checkbox').click(checkbox_click);
		$("#select_all").prop('checked',false);
	}, "json");
}