function updateLRSRecord()
{
	$("#find_by_uln_dialog1").hide();
	$("#update_learner_in_lrs").fadeToggle();
	document.getElementById('update_learner_in_lrs').innerHTML = '';
	// ULN validation
	var uln = jQuery.trim($('input[name="l45"]').val());
	if (uln && !isValidUln(uln)) {
		alert("The ULN '" + uln + "' is invalid. Please correct the ULN to proceed.");
		$('input[name="l45"]').focus();
		return false;
	}

	var $dialog = $('#update_learner_dialog');
	var contents = "";

	var client = ajaxRequest('do.php?_action=ajax_get_learner_details&uln='+ encodeURIComponent($('input[name="old_l45"]').val()) + '&id=' + encodeURIComponent($('input[name="id"]').val()));
	if(client != null)
	{
		if(client.responseText != "")
		{
			if(client.responseText != 'No ID provided')
			{
				var data = client.responseText;
				data = JSON.parse(data) + '';
				var learnerDetails = data.split(',');
				var gender_desc = '';
				var verification_type_desc = '';
				var ability_to_share_desc = '';
				switch (learnerDetails[5])
				{
					case 'M':
						gender_desc = 'Male';
						break;
					case 'F':
						gender_desc = 'Female';
						break;
					case 'U':
						gender_desc = 'Unknown';
						break;
					case 'W':
						gender_desc = 'Witheld';
						break;
				}

				switch (learnerDetails[6])
				{
					case '1':
						verification_type_desc = 'Not Provided';
						break;
					case '2':
						verification_type_desc = 'Relationship with school';
						break;
					case '3':
						verification_type_desc = 'Passport';
						break;
					case '4':
						verification_type_desc = 'Driving license';
						break;
					case '5':
						verification_type_desc = 'ID Card or other form of national identification';
						break;
					case '6':
						verification_type_desc = 'National Insurance Card';
						break;
					case '7':
						verification_type_desc = 'Certification of Entitlement to Funding';
						break;
					case '8':
						verification_type_desc = 'Bank/Credit/Debit Card';
						break;
					case '999':
						verification_type_desc = 'Other';
						break;
				}

				switch (learnerDetails[7])
				{
					case '0':
						ability_to_share_desc = 'FPN not seen';
						break;
					case '1':
						ability_to_share_desc = 'FPN Seen and able to share data';
						break;
					case '2':
						ability_to_share_desc = 'FPN Seen and unable to share data';
						break;
				}

				contents += '<div><h3>Learner Details</h3>';
				contents += '<table width="450" class="resultset" cellspacing="0" cellpadding="4">';
				contents += '<caption><strong>Following information will be used to search and update the LRS records for the learner.<br>Please note that if a Learner with identically matching details already exists in the LRS, then LRS record will not be updated.</strong></caption>';
				contents += '<tr><th>Field</th><th>Value</th></tr>';
				contents += '<tr><td>ULN</td><td>' + $('input[name="l45"]').val(); + '</td></tr>';
				contents += '<tr><td>First Name</td><td>' + $('input[name="firstnames"]').val(); + '</td></tr>';
				contents += '<tr><td>Surname</td><td>' + $('input[name="surname"]').val(); + '</td></tr>';
				contents += '<tr><td>Date of Birth</td><td>' + $('input[name="dob"]').val(); + '</td></tr>';
				contents += '<tr><td>Gender</td><td>' + $("#gender option:selected").text(); + '</td></tr>';
				contents += '<tr><td>Address Line 1</td><td>' + $('input[name="home_address_line_1"]').val(); + '</td></tr>';
				contents += '<tr><td>Address Line 2</td><td>' + $('input[name="home_address_line_2"]').val(); + '</td></tr>';
				contents += '<tr><td>Address Line 3</td><td>' + $('input[name="home_address_line_3"]').val(); + '</td></tr>';
				contents += '<tr><td>Address Line 4</td><td>' + $('input[name="home_address_line_4"]').val(); + '</td></tr>';
				contents += '<tr><td>Postcode</td><td>' + $('input[name="home_postcode"]').val(); + '</td></tr>';
				contents += '<tr><td>Verification Type</td><td>' + $("#verification_type option:selected").text(); + '</td></tr>';
				contents += '<tr><td>Other Verification Description</td><td>' + $('input[name="verification_type_other"]').val(); + '</td></tr>';
				contents += '<tr><td>Ability to Share</td><td>' + $("#ability_to_share option:selected").text(); + '</td></tr>';
				contents += '<tr><td colspan="2" align="right"><input type="button" onclick="executeUpdateLRSRecordService();" value="UPDATE"></td></tr>';
				contents += '</table></div>';

			}
		}
	}
	document.getElementById('update_learner_in_lrs').innerHTML += contents;
	$('#update_learner_in_lrs').show();
}

function verifyLearnerULN1()
{
	$("#find_by_uln_dialog1").fadeToggle();
	$("#update_learner_in_lrs").hide();

}

$(function() {
	$( "#update_learner_dialog" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: true,
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
			750,
		height:
			750,
		buttons:[
			{
				id: 'button-ok',
				text: 'OK',
				click: function() {
				}
			},
			{
				id: 'button-cancel',
				text: 'Cancel',
				click: function() {$(this).dialog('close');$("#button-ok").button("enable");}
			}
		]
	});
	$( "#update_learner_dialog_opener" ).click(function() {
		$( "#update_learner_dialog" ).dialog( "open" );
	});
	$( "#update_learner_dialog_closer" ).click(function() {
		$( "#update_learner_dialog" ).dialog( "close" );
	});
});

function executeUpdateLRSRecordService()
{
	$("#progress").show();

	var ULN = $('input[name="l45"]').val();
	var firstnames = $('input[name="firstnames"]').val();
	var surname = $('input[name="surname"]').val();
	var postcode = $('input[name="home_postcode"]').val();
	var dob = $('input[name="dob"]').val();
	var gender = $('select[name="gender"]').val();
	var verification_type = $('select[name="verification_type"]').val();
	var ability_to_share = $('select[name="ability_to_share"]').val();
	var home_address_line_1 = $('input[name="home_address_line_1"]').val();
	var home_address_line_2 = $('input[name="home_address_line_2"]').val();
	var home_address_line_3 = $('input[name="home_address_line_3"]').val();
	var home_address_line_4 = $('input[name="home_address_line_4"]').val();
	var verification_type_other = $('input[name="verification_type_other"]').val();
	var sunesis_id = $('input[name="id"]').val();

	var postData = '&sunesis_id=' + encodeURIComponent(sunesis_id) +'&uln=' + encodeURIComponent(ULN) +'&firstnames=' + encodeURIComponent(firstnames) + '&surname=' + encodeURIComponent(surname) + '&postcode=' + encodeURIComponent(postcode)
		+ '&dob=' + encodeURIComponent(dob) + '&gender=' + encodeURIComponent(gender) + '&verification_type=' + encodeURIComponent(verification_type) + '&ability_to_share=' + encodeURIComponent(ability_to_share)
		+ '&home_address_line_1=' + encodeURIComponent(home_address_line_1) + '&home_address_line_2=' + encodeURIComponent(home_address_line_2) + '&home_address_line_3=' + encodeURIComponent(home_address_line_3)
		+ '&home_address_line_4=' + encodeURIComponent(home_address_line_4) + '&verification_type_other=' + encodeURIComponent(verification_type_other);

	var request = ajaxBuildRequestObject();
	if(request == null)
	{
		alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
	}

	var url = expandURI('do.php?_action=lrs_update_learner' + postData);
	request.open("GET", url, true); // (method, uri, synchronous)
	request.onreadystatechange = function(e)
	{
		if(request.readyState == 4)
		{
			if(request.status == 200)
			{
				if(request.responseText.length < 100)
				{
					alert(request.responseText);
					document.getElementById("update_learner_in_lrs").innerHTML = '';
					$("#update_learner_in_lrs").hide();
				}
				else
				{
					$( "#update_learner_dialog" ).dialog( "close" );
					$( "#update_learner_dialog" ).dialog( "open" );
					$( "#update_learner_dialog" ).html(request.responseText);
					$("#button-ok").button("disable");
				}
			}
			else
			{
				ajaxErrorHandler(request);
			}
			// Switch off spinning wheel
			$("#progress").hide();
		}
	}

	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null); // post data
}

function updatePossibleMatchLearner(data)
{
	var firstnames = $('input[name="firstnames"]').val();
	var surname = $('input[name="surname"]').val();
	var postcode = $('input[name="home_postcode"]').val();
	var dob = $('input[name="dob"]').val();
	var gender = $('select[name="gender"]').val();
	var verification_type = $('select[name="verification_type"]').val();
	var ability_to_share = $('select[name="ability_to_share"]').val();
	var home_address_line_1 = $('input[name="home_address_line_1"]').val();
	var home_address_line_2 = $('input[name="home_address_line_2"]').val();
	var home_address_line_3 = $('input[name="home_address_line_3"]').val();
	var home_address_line_4 = $('input[name="home_address_line_4"]').val();
	var verification_type_other = $('input[name="verification_type_other"]').val();
	var sunesis_id = $('input[name="id"]').val();

	var postData = '&sunesis_id=' + encodeURIComponent(sunesis_id) +'&firstnames=' + encodeURIComponent(firstnames) + '&surname=' + encodeURIComponent(surname) + '&postcode=' + encodeURIComponent(postcode)
		+ '&dob=' + encodeURIComponent(dob) + '&gender=' + encodeURIComponent(gender) + '&verification_type=' + encodeURIComponent(verification_type) + '&ability_to_share=' + encodeURIComponent(ability_to_share)
		+ '&home_address_line_1=' + encodeURIComponent(home_address_line_1) + '&home_address_line_2=' + encodeURIComponent(home_address_line_2) + '&home_address_line_3=' + encodeURIComponent(home_address_line_3)
		+ '&home_address_line_4=' + encodeURIComponent(home_address_line_4)+ '&verification_type_other='  + encodeURIComponent(verification_type_other);
	data = data+postData;
	//alert(data+postData);return;

	var client = ajaxRequest('do.php?_action=lrs_update_learner', data);

	if(client != null)
	{
		alert(client.responseText);
		$('#update_learner_dialog').dialog('close');
		window.location.reload();
	}
}

function findByULN(id)
{
	// Switch on the spinning wheel
	$("#progress").show();
	var find_by_uln_val = $('input:radio[name="find_by_uln"]:checked').val();
	if(find_by_uln_val == undefined)
	{
		alert('Please select the find type');
		$("#progress").hide();
		return;
	}
	var ULN = $('input[name="l45"]').val();
	if(ULN == '')
	{
		alert("ULN Not Provided");
		$("#progress").hide();
		return;
	}
	var GivenName = $('input[name="firstnames"]').val();
	if(GivenName == '')
	{
		alert("Firstname(s) Not Provided");
		$("#progress").hide();
		return;
	}
	var FamilyName = $('input[name="surname"]').val();
	if(FamilyName == '')
	{
		alert("Surname Not Provided");
		$("#progress").hide();
		return;
	}
	var request = ajaxBuildRequestObject();
	if(request == null)
	{
		alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
	}
	var postData = "&uln=" + ULN + "&GivenName=" + encodeURIComponent(GivenName) + "&FamilyName=" + encodeURIComponent(FamilyName) + "&FindType=" + encodeURIComponent(find_by_uln_val) + "&learner_sunesis_id=" + encodeURIComponent(id);
	var url = expandURI('do.php?_action=miap_connectivity' + postData);
	request.open("GET", url, true); // (method, uri, synchronous)
	request.onreadystatechange = function(e)
	{
		if(request.readyState == 4)
		{
			if(request.status == 200)
			{
				if(request.responseText.search('Mandatory information missing') == -1)
				{
					$( "#find_by_uln_dialog" ).dialog( "close" );
					$( "#find_by_uln_dialog" ).dialog( "option", "width", '750' );
					$( "#find_by_uln_dialog" ).dialog( "option", "height", '750' );
					$( "#find_by_uln_dialog" ).dialog( "option", "buttons", {
						"OK": function() { $(this).dialog('close').dialog('remove'); window.location.reload(); }
					} );
					$( "#find_by_uln_dialog" ).dialog( "open" );
					$("#find_by_uln_dialog").html(request.responseText);
				}
				else
				{
					$( "#find_by_uln_dialog" ).dialog( "close" );
					window.location.reload();
				}
			}
			else
			{
				ajaxErrorHandler(request);
			}
			// Switch off spinning wheel
			$("#progress").hide();
		}
	}

	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null); // post data
}

function findByDemographics1()
{
	// Switch on the spinning wheel
	$("#progress").show();
	var firstnames = $('input[name="lrs_firstnames"]').val();
	var surname = $('input[name="lrs_surname"]').val();
	var postcode = $('input[name="lrs_home_postcode"]').val();
	var dob = $('input[name="lrs_dob"]').val();
	var gender = $('select[name="lrs_gender"]').val();
	var prev_family_name = $('input[name="lrs_prev_family_name"]').val();
	var school_at_age_16 = $('input[name="lrs_school_at_age_16"]').val();
	var home_email = $('input[name="lrs_home_email"]').val();
	var place_of_birth = $('input[name="lrs_place_of_birth"]').val();

	if(firstnames == '')
	{
		alert('Please provide the first name(s)');
		$('input[name="lrs_firstnames"]').focus();
		return;
	}
	if(surname == '')
	{
		alert('Please provide the surname');
		$('input[name="lrs_surname"]').focus();
		return;
	}
	if(gender == '')
	{
		alert('Please select the gender');
		$('select[name="lrs_gender"]').focus();
		return;
	}
	if(dob == '')
	{
		alert('Please provide the date of birth');
		$('input[name="lrs_dob"]').focus();
		return;
	}
	if(postcode == '')
	{
		alert('Please provide the postcode');
		$('input[name="lrs_home_postcode"]').focus();
		return;
	}

	var request = ajaxBuildRequestObject();
	if(request == null)
	{
		alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
	}

	var postData = '&subaction=searchByDemographics&find_type=FUL&firstnames=' + (firstnames) + '&surname=' + (surname) + '&home_postcode=' + (postcode)
		+ '&dob=' + (dob) + '&gender=' + (gender) + '&prev_family_name=' + (prev_family_name) + '&school_at_age_16=' + (school_at_age_16) + '&place_of_birth=' + (place_of_birth)
		+ '&home_email=' + (home_email);

	var url = expandURI('do.php?_action=lrs_search_by_demographics' + postData);
	request.open("GET", url, true); // (method, uri, synchronous)
	request.onreadystatechange = function(e)
	{
		if(request.readyState == 4)
		{
			if(request.status == 200)
			{
				var $dialog = $('#find_by_demographics_dialog');
				$dialog.dialog("open");
				$dialog.html(request.responseText);
			}
			else
			{
				ajaxErrorHandler(request);
			}
			// Switch off spinning wheel
			$("#progress").hide();
		}
	}

	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null); // post data
}

$(function() {
	$( "#find_by_demographics_dialog" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: true,
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
			750,
		height:
			750,
		buttons: {
/*
			'OK': function() {
			},
*/
			'Cancel': function() {$(this).dialog('close');}
		}
	});
	$( "#find_by_demographics_dialog_opener" ).click(function() {
		$( "#find_by_demographics_dialog" ).dialog( "open" );
	});
	$( "#find_by_demographics_dialog_closer" ).click(function() {
		$( "#find_by_demographics_dialog" ).dialog( "close" );
	});
});

$(function() {
	$( "#find_by_uln_dialog" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: true,
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
			750,
		height:
			750,
		buttons: {
			'OK': function() {
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});
	$( "#find_by_uln_dialog_opener" ).click(function() {
		$( "#find_by_uln_dialog" ).dialog( "open" );
	});
	$( "#find_by_uln_dialog_closer" ).click(function() {
		$( "#find_by_uln_dialog" ).dialog( "close" );
	});
});

function update_fields(url)
{
	var values = url.split('&');
	for(var i=0;i<values.length;i++)
	{
		var tmp = values[i].split('=');
		var fieldName = tmp[0];
		var fieldValue = (tmp[1]);
		$('input[name="' + fieldName + '"]').val(fieldValue);
		if(fieldName == 'gender' || fieldName == 'verification_type' || fieldName == 'ability_to_share')
			$('select[name="' + fieldName + '"]').val(fieldValue);
	}

	$( "#find_by_uln_dialog" ).dialog( "close" );
}

$(document).ready(function(){
	$("#showHideLRSPanel").click(function(){
		$("#lrsPanel").fadeToggle();
	});
});

function showHideFindByDemographicsPanel()
{
	$("#lrsPanelSearchByDemographics").fadeToggle();
}
