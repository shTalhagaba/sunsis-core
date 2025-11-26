/**
 * Created with JetBrains PhpStorm.
 * User: ianss
 * Date: 17/10/14
 * Time: 12:13
 * To change this template use File | Settings | File Templates.
 */
$(function() {
	$( "#audit_log" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		hide: {
			effect: "explode",
			duration: 1000
		},
		width:
			700,
		height:
			700
	});

	$( "#audit_log_opener" ).click(function() {
		$( "#audit_log" ).dialog( "open" );
	});

	$( "#audit_log_closer" ).click(function() {
		$( "#audit_log" ).dialog( "close" );
	});
});
$(function() {
	$( "#dialog_logic_melon" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		hide: {
			effect: "explode",
			duration: 1000
		},
		width:
			950,
		height:
			950
	});

	$( "#dialog_logic_melon_opener" ).click(function() {
		$( "#dialog_logic_melon" ).dialog( "open" );
	});

	$( "#dialog_logic_melon_closer" ).click(function() {
		$( "#dialog_logic_melon" ).dialog( "close" );
	});
});

$(document).ajaxStart(function() {
	$('#progress').show(); // show the gif image when ajax starts
}).ajaxStop(function() {
		$('#progress').hide(); // hide the gif image when ajax completes
	});


function upload_vacancy_to_logic_melon(id, advert_id, on_duplicate)
{
	// Switch on the spinning wheel
	$("#progress").show();

	if(id == '')
	{
		alert("Missing Vacancy ID.");
		return false;
	}

	var request = ajaxBuildRequestObject();
	if(request == null)
	{
		alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
	}

	// Place request to server
	if(advert_id != '' && on_duplicate != '')
		var url = expandURI('do.php?_action=logic_melon&vac_id=' + encodeURIComponent(id) + '&advert_id=' + encodeURIComponent(advert_id) + '&on_duplicate=' + encodeURIComponent(on_duplicate));
	else
		var url = expandURI('do.php?_action=logic_melon&vac_id=' + encodeURIComponent(id));
	request.open("GET", url, true); // (method, uri, synchronous)
	request.onreadystatechange = function(e){
		if(request.readyState == 4){
			if(request.status == 200)
			{
				if(request.responseText.search('Operation successfully completed on Logic Melon') != -1)
				{
					$("#dialog_logic_melon" ).dialog( "open" );
					$("#dialog_logic_melon").html(request.responseText);
					$("#logicMelonPanel").hide();
				}
				else
				{
					document.getElementById('logicMelonPanel').innerHTML = request.responseText;
					$("#logicMelonPanel").show();
				}
			}
			else
			{
				ajaxErrorHandler(request);
			}
			// Switch off globes
			$("#progress").hide();
		}
	}

	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null); // post data

}


function removeDialog()
{
	$( "#dialog_logic_melon" ).dialog( "close" );
}