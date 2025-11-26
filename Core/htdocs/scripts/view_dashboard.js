$(function() {
	$(".panel").dialog({
		show: {
			effect: "blind",
			duration: 1000
		},
		hide: {
			effect: "explode",
			duration: 1000
		},
		resizable: false,
		width:
			300,
		height:
			300,
		open: function() {
			var panel_html_id = '#' + this.id;

			var client = ajaxRequest('do.php?_action=ajax_get_panel_position&panel='+ encodeURIComponent(this.id) + '&default_positioning=true');
			if(client != null)
			{
				if(client.responseText != "")
				{
					setTimeout(loadajax, 1500);
					var position = client.responseText.split(',');
					var x = parseInt(position[0]);
					var y = parseInt(position[1]);
					jQuery(panel_html_id).dialog('option', 'position', [x,y]);
				}
			}
		}
	});
	$('.panel').bind('dialogclose', function(event) {
		var postData = 'panel=' + this.id
			+ '&show_hide=0';
		var request = ajaxRequest('do.php?_action=ajax_show_hide_dashboard_panels', postData);

		if(request)
		{
			//alert("Changes Saved.");
			var dashboard_panels = document.getElementById('dashboard_panels');
			ajaxPopulateSelect(dashboard_panels, 'do.php?_action=ajax_load_dashboard_panels');
		}
//		reshufflePanels(this.id);
	});
});



function dashboard_panels_onchange()
{
	var panel = $("#dashboard_panels option:selected").val();
	var panel_html_id = '#' + panel;
	var postData = 'panel='+panel
		+ '&show_hide=1';
	var request = ajaxRequest('do.php?_action=ajax_show_hide_dashboard_panels', postData);
	if(request)
	{
		$( panel_html_id ).dialog( "open" );
		var dashboard_panels = document.getElementById('dashboard_panels');
		ajaxPopulateSelect(dashboard_panels, 'do.php?_action=ajax_load_dashboard_panels');
		//window.location.reload();
	}
}

function resetToDefaultPosition(panel)
{
	if(panel == '')
	{
		var client = ajaxRequest('do.php?_action=ajax_get_panel_position&panel=&default_positioning=true');
		if(client != null)
		{
			if(client.responseText != "")
			{
				var obj = JSON.parse(client.responseText);
				for(var i = 0; i < obj.panels.length; i++)
				{
					var panel_name = '#' + obj.panels[i].name;
					var position = obj.panels[i].position.split(',');
					var x = parseInt(position[0]);
					var y = parseInt(position[1]);
					jQuery(panel_name).dialog('option', 'position', [x,y]);
				}
			}
		}
	}
	else
	{
		var panel_html_id = '#' + panel;
		var client = ajaxRequest('do.php?_action=ajax_get_panel_position&panel='+ encodeURIComponent(panel) + '&default_positioning=true');
		if(client != null)
		{
			if(client.responseText != "")
			{
				var position = client.responseText.split(',');
				var x = parseInt(position[0]);
				var y = parseInt(position[1]);
				jQuery(panel_html_id).dialog('option', 'position', [x,y]);
			}
		}
	}
}

function coordinates(element)
{
	element = $(element);
	var position = element.offset();
	var top = position.top;
	var left = position.left;
	alert(left + ',' + top);
}


function loadajax(){

	resetToDefaultPosition('');
}

/*
function reshufflePanels(panel_closed)
{
	var startFrom
	var client = ajaxRequest('do.php?_action=ajax_get_panel_position&panel=&default_positioning=true');
	if(client != null)
	{
		if(client.responseText != "")
		{
			alert(client.responseText);
			var obj = JSON.parse(client.responseText);
			for(var i = 0; i < obj.panels.length; i++)
			{
				alert('i= ' + i + ', length = ' + obj.panels.length);
				var panel_name = '#' + obj.panels[i].name;alert(panel_name);
				var position = obj.panels[i].position.split(',');
				var x = parseInt(position[0]);alert(x);
				var y = parseInt(position[1]);alert(y);
				jQuery(panel_name).dialog('option', 'position', [x,y]);
			}
		}
	}
}*/
