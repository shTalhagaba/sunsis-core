// jQuery initialisation
$(function(){


	$("#"+phpParticipantStatus).stop().css("background-color", "#FFFF9C")
		.animate({ backgroundColor: "#87cefa"}, 2000);

	if(phpRecordID == '')
		$("#demo").addClass("disabledbutton");

	$("#participant_status > option").each(function() {
		if(this.value < window.phpParticipantStatusID)
			$(this).attr('disabled', 'disabled');
	});

	$('input[name=input_mobile]').keyup(function(e){
		if(e.keyCode == 13) addContact(this);
	});

	// Dynamic ULN validation
	$('input[name="l45"]').keyup(function(e){
		$('input[name="l45"]').css('color', isValidUln($('input[name="l45"]').val()) ? 'green':'red');
	});
	$('input[name="l45"]').bind('paste', function(e){
		setTimeout(function() {
			$('input[name="l45"]').css('color', isValidUln($('input[name="l45"]').val()) ? 'green':'red');
		}, 100);
	});
	$('input[name="l45"]').css('color', isValidUln($('input[name="l45"]').val()) ? 'green':'red');

	$('input[name="firstnames"],input[name="surname"],input[name="dob"]').change(function(e){
		findSimilarRecords();
	});
	findSimilarRecords();

	$('#dialogDuplicate').dialog({
		modal: true,
		width: 550,
		closeOnEscape: true,
		autoOpen: false,
		resizable: true,
		draggable: true,
		buttons: {
			'View full record': function() {
				//$(this).dialog('close');
				window.open('do.php?_action=read_user&username='+$(this).data('username'));
			},
			'Close': function() {$(this).dialog('close');}
		}
	});

	if(phpParticipantContract == '')
	{
		var tabView = new YAHOO.widget.TabView('demo');
		tabView.get('tabs')[2].set('disabled', true);
		tabView.get('tabs')[3].set('disabled', true);
		tabView.get('tabs')[4].set('disabled', true);
		tabView.get('tabs')[5].set('disabled', true);
		tabView.get('tabs')[6].set('disabled', true);
		$('#btnSaveAll').hide();
	}
	if(phpTrainingRecordExists == 0)
	{
		var tabView = new YAHOO.widget.TabView('demo');
		tabView.get('tabs')[6].set('disabled', true);
	}
	else if(window.phpTrainingRecordExists == '1')
	{
		$('#grid_hhs').attr('class', 'disabled');
		$('#grid_lsr').attr('class', 'disabled');
		$('#fme').attr('class', 'disabled');
		$('#notificationIconForDisabledHHS').show();
		$('#notificationIconForDisabledLSR').show();
		$('#notificationIconForDisabledFME').show();
	}

	if($('#l14').val() == '1')
	{
		$('#primary_lldd').attr('class', 'compulsory validate[required]');
		$('#grid_lldd_cat').attr('class', 'compulsory validate[required]');
	}
	else
	{
		$('#primary_lldd').attr('class', 'disabled');
		$('#grid_lldd_cat').attr('class', 'disabled');
	}

	if(window.phpIDSeen == '1')
	{
		$('#lbl_id_seen_type').attr('class', 'fieldLabel_compulsory');
		$('#lbl_id_seen_type').show();
		$('#id_seen_type').attr('class', 'compulsory validate[required]');
		$('#id_seen_type').show();
		if($('#id_seen_type').val() == '12' || $('#id_seen_type').val() == '13')
		{
			$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_compulsory');
			$('#lbl_id_seen_other_desc').show();
			$('#id_seen_other_desc').attr('class', 'compulsory validate[required]');
			$('#id_seen_other_desc').show();
		}
		else if($('#id_seen_type').val() == '1')
		{
			$('#lbl_id_seen_passport_number').attr('class', 'fieldLabel_compulsory');
			$('#lbl_id_seen_passport_number').show();
			$('#id_seen_passport_number').attr('class', 'compulsory validate[required]');
			$('#id_seen_passport_number').show();
		}
		else
		{
			$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_optional');
			$('#lbl_id_seen_other_desc').hide();
			$('#id_seen_other_desc').attr('class', 'optional');
			$('#id_seen_other_desc').hide();
			$('#lbl_id_seen_passport_number').attr('class', 'fieldLabel_optional');
			$('#lbl_id_seen_passport_number').hide();
			$('#id_seen_passport_number').attr('class', 'optional');
			$('#id_seen_passport_number').hide();
		}
	}
});

/**
 * Opens the duplicate dialog window
 * @param $divDuplicate
 */
function viewDuplicateRecord($divDuplicate)
{
	var $dialog = $('#dialogDuplicate');
	$dialog.data('id', $divDuplicate.data('id'));
	$dialog.data('username', $divDuplicate.data('username'));
	$('td#firstnames', $dialog).text($divDuplicate.data('firstnames'));
	$('td#surname', $dialog).text($divDuplicate.data('surname'));
	$('td#dob', $dialog).text($divDuplicate.data('dob'));
	$('td#gender', $dialog).text($divDuplicate.data('gender'));
	$('td#employer', $dialog).text($divDuplicate.data('employer'));
	$('td#uln', $dialog).text($divDuplicate.data('uln'));
	$('td#ni', $dialog).text($divDuplicate.data('ni'));
	$('td#id', $dialog).text($divDuplicate.data('id'));
	$('td#l03', $dialog).text($divDuplicate.data('l03'));
	$('td#tr_count', $dialog).text($divDuplicate.data('tr_count') + ' records');
	$dialog.dialog("open");
}

function numbersonly(myfield, e, dec)
{
	var key;
	var keychar;

	if (window.event)
		key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;
	keychar = String.fromCharCode(key);

	// control keys
	if ((key==null) || (key==0) || (key==8) ||
		(key==9) || (key==13) || (key==27) )
		return true;

	// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
		return true;

	// decimal point jump
	else if (dec && (keychar == "."))
	{
		myfield.form.elements[dec].focus();
		return false;
	}
	else
		return false;
}

function findSimilarRecords()
{
	var $divSimilarRecords = $('div#SimilarRecords');
	if($divSimilarRecords.length == 0) {
		return;
	}

	// Hide the section while we work on it
	$divSimilarRecords.hide();

	var firstnames = $('input[name="firstnames"]').val();
	var surname = $('input[name="surname"]').val();
	var uln = $('input[name="l45"]').val();
	var dob = $('input[name="dob"]').val();
	var username = $('input[name="username"]').val();
	var id = $('input[name="id"]').val();
	var employerId = $('select[name="employer_id"]').val();

	// Don't proceed without at least the first and second name
	if(!firstnames || !surname) {
		return;
	}

	var url = 'do.php?_action=edit_user&subaction=findSimilarRecords'
		+ "&firstnames=" + encodeURIComponent(firstnames)
		+ "&surname=" + encodeURIComponent(surname)
		+ "&dob=" + encodeURIComponent(dob)
		+ "&id=" + encodeURIComponent(id)
		+ "&employer_id=" + encodeURIComponent(employerId);
	var client = ajaxRequest(url);
	var html = null;
	if (client) {
		var records = jQuery.parseJSON(client.responseText);
		if (records.length) {
			$('div.SimilarRecord', $divSimilarRecords).remove();
			var $node = null;
			for (var i = 0; i < records.length; i++) {
				html = '<div class="SimilarRecord">- <span style="color:orange">'
					+ htmlspecialchars(records[i].firstnames) + ' ' + htmlspecialchars(records[i].surname)
					+ '</span></div>';
				$node = $(html);
				$node.data('id', records[i].id);
				$node.data('username', records[i].username);
				$node.data('firstnames', records[i].firstnames);
				$node.data('surname', records[i].surname);
				$node.data('dob', records[i].dob);
				$node.data('uln', records[i].l45);
				$node.data('ni', records[i].ni);
				$node.data('gender', records[i].gender);
				$node.data('employer', records[i].employer);
				$node.data('l03', records[i].l03);
				$node.data('tr_count', records[i].tr_count);
				$node.click(function(e){
					viewDuplicateRecord($(this));
				});
				$divSimilarRecords.append($node);
			}
			$divSimilarRecords.show();
		}
	}
}

function newReferralSource()
{
	var optn = document.createElement("OPTION");
	var usertype = phpPeople;
	var value = window.prompt("Enter new referral source ");
	if(value!=null)
	{

		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + 1
				+ '&type=' + value
				+ '&usertype=' + usertype;

			request.open("POST", expandURI('do.php?_action=save_referral_source'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);

			if(request.status == 200)
			{
				//optn.value = + request.responseText;
				optn.value = value;
				optn.text = value;
				var type = document.getElementById('referral_source');
				type.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}

}

function delReferralSource()
{
	var select=document.getElementById('referral_source');
	var val = document.getElementById('referral_source').value;

	if(val=='')
	{
		alert("Please select referral source value from the dropdown.");
		return;
	}
	if(confirm("Are you sure?") == false) return;

	for (i=0;i<select.length;  i++)
	{
		if (select.options[i].value==val)
		{
			select.remove(i);

			// Save elements by AJAX
			var request = ajaxBuildRequestObject();
			if(request != null)
			{
				var postData = 'type=' + val;

				request.open("POST", expandURI('do.php?_action=delete_referral_source'), false); // (method, uri, synchronous)
				request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				request.setRequestHeader("x-ajax", "1"); // marker for server code
				request.send(postData);

				if(request.status == 200)
				{

				}
				else
				{
					alert(request.responseText);
				}
			}
			else
			{
				alert("Could not create XMLHttpRequest object");
			}

		}
	}

}

function newJobRole()
{
	var optn = document.createElement("OPTION");
	var usertype = 'Learner';
	var value = window.prompt("Enter new Job Role for participant");
	if(value!=null)
	{

		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + 1
				+ '&type=' + value
				+ '&usertype=' + usertype;

			request.open("POST", expandURI('do.php?_action=save_job_role'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);

			if(request.status == 200)
			{
				//optn.value = + request.responseText;
				optn.value = value;
				optn.text = value;
				var type = document.getElementById('job_role');
				type.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}
}

function newJobGoal()
{
	var optn = document.createElement("OPTION");
	var value = window.prompt("Enter new Job Goal ");
	if(value!=null)
	{
		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + 1
				+ '&type=' + value;
			request.open("POST", expandURI('do.php?_action=save_job_goal'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);
			if(request.status == 200)
			{
				//optn.value = + request.responseText;
				optn.value = value;
				optn.text = value;
				var type1 = document.getElementById('job_goal_1');
				type1.options.add(optn);
				var type2 = document.getElementById('job_goal_2');
				type2.options.add(optn);
				var type3 = document.getElementById('job_goal_3');
				type3.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}
	window.location.reload();
}

function usernameUnique(username)
{
	var client = ajaxRequest('do.php?_action=ajax_is_identifier_unique&identifier='	+ encodeURIComponent(username));
	return client && client.responseText == "1";
}

function postcodesValidation()
{
	var postcode = document.getElementById("home_postcode").value;
	if(postcode != '')
	{
		if( !postcode.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) )
		{
			custom_alert_OK_only("Incorrect format for Postcode");
			document.getElementById("home_postcode").focus();
			return false;
		}
	}

	/*
	 if (postcode_value != '' && isPrisonPostcode(postcode_value)) {
	 alert("Employer Postcode " + postcode_value + " is not allowed, it is associated with prisons or other offender institutions.");
	 document.getElementById("work_postcode").focus();
	 return false;
	 }

	 if (postcode != '' && isPrisonPostcode(postcode)) {
	 alert("User Postcode " + postcode + " is not allowed, it is associated with prisons or other offender institutions.");
	 document.getElementById("home_postcode").focus();
	 return false;
	 }
	 */
	return true;
}

function isPrisonPostcode(postcode)
{
	var client = ajaxRequest('do.php?_action=ajax_is_prison_postcode&postcode='	+ encodeURIComponent(postcode));
	return client && client.responseText == "1";
}


function calcAge(dateString)
{
	var pieces = dateString.split('/');
	dateString = pieces[2]+'-'+pieces[1]+'-'+pieces[0];
	var birthday = new Date(dateString);
	/*
	 var ageDifMs = Date.now() - birthday.getTime();
	 var ageDate = new Date(ageDifMs); // miliseconds from epoch
	 return Math.abs(ageDate.getFullYear() - 1970);
	 */
	var d2 = new Date('2013-08-31');
	return d2.getFullYear() - birthday.getFullYear();
}

function fillBorough()
{
	var home_postcode = $('#home_postcode').val();
	if(home_postcode=='')
	{
		custom_alert_OK_only("Please enter postcode", "Alert");
	}
	else
	{
		if(!postcodesValidation())
		{
			return false;
		}
		$("#btn_fill_borough").attr('class', '');
		$("#btn_fill_borough").html('<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;" />');
		var client = ajaxRequest('do.php?_action=ajax_get_borough&postcode='	+ encodeURIComponent(home_postcode), null, null, loadCommands_callback);
	}
}

function loadCommands_callback(req)
{
	$("#btn_fill_borough").html('Auto-Fill');
	$("#btn_fill_borough").attr('class', 'button');
	$("[name='home_address_line_3']").val(req.responseText);
}

/**
 *
 * @param string uln
 * @return boolean True if the value is a valid ULN, false if the value is empty or an invalid ULN
 */
function isValidUln(uln)
{
	uln = jQuery.trim(uln);
	var valid_pattern = /^[1-9]{1}[0-9]{9}$/;
	if (uln.match(valid_pattern)) {
		var remainder = ((10 * uln.charAt(0))
			+ (9 * uln.charAt(1))
			+ (8 * uln.charAt(2))
			+ (7 * uln.charAt(3))
			+ (6 * uln.charAt(4))
			+ (5 * uln.charAt(5))
			+ (4 * uln.charAt(6))
			+ (3 * uln.charAt(7))
			+ (2 * uln.charAt(8))) % 11;

		if (remainder == 0) {
			return false;
		}

		var check_digit = 10 - remainder;
		if (check_digit != uln.charAt(9)) {
			return false;
		}

		return true;
	}

	return false;
}

function formatDate(input_date, with_time)
{
	var d = new Date(input_date);
	var day = d.getDate();
	var month = d.getMonth() + 1;
	var year = d.getFullYear();
	if (day < 10) {
		day = "0" + day;
	}
	if (month < 10) {
		month = "0" + month;
	}

	if(with_time)
		return day + "/" + month + "/" + year + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
	else
		return day + "/" + month + "/" + year;
}

function checkDuplicates()
{
	var anyDuplicates = false;

	var myForm = document.forms["frmBasicDetails"];
	var postData = 'do.php?_action=view_edit_participant'
			+ '&firstnames=' + encodeURIComponent(myForm.elements["firstnames"].value)
			+ '&surname=' + encodeURIComponent(myForm.elements["surname"].value)
			+ '&dob=' + encodeURIComponent(myForm.elements["dob"].value)
			+ '&subaction=' + encodeURIComponent("checkDuplicates")
		;

	var req = ajaxRequest(postData);

	var html = '';
	html = '<table border="1" cellspacing="4" cellpadding="4" style="margin-left:10px">' +
		'<thead><tr><th>&nbsp;</th><th>Record ID</th><th>Creation Date</th><th>First Name(s)</th><th>Surname</th><th>DOB</th><th>Postcode</th><th>Created By</th></tr></thead>' +
		'<tbody>';


	var myObject = eval('(' + req.responseText + ')');
	for (var i in myObject)
	{
		anyDuplicates = true;
		html += '<tr>';
		html += '<td><span class="button" onclick="window.location.href=\'do.php?_action=view_edit_participant&id=' + myObject[i]['id'] + '\'">Load</span></td>' +
			'<td>' + myObject[i]["id"] + '</td>' +
			'<td>' + formatDate(myObject[i]["created"], true) + '</td>' +
			'<td>' + myObject[i]["firstnames"] + '</td>' +
			'<td>' + myObject[i]["surname"] + '</td>' +
			'<td>' + formatDate(myObject[i]["dob"], false) + '</td>' +
			'<td>' + myObject[i]["home_postcode"] + '</td>' +
			'<td>' + myObject[i]["created_by"] + '</td>'
		;
		html += '</tr>';
	}

	html += '</tbody>' +
		'</table>';
	if(anyDuplicates)
	{
		$('#tbl_duplicate_records').html(html);
		$('#messageBox').show();
	}
	return anyDuplicates;
}

function updateBasicDetails(participant_id)
{
	var myForm = document.forms["frmBasicDetails"];
	var myForm2 = document.forms["frmDetails"];

	if(myForm.elements["firstnames"].value == '' || myForm.elements["surname"].value == '' || myForm.elements["dob"].value == '' || myForm.elements["adviser"].value == '')
	{
		alert('Please enter all basic details.');
		return;
	}

	if(myForm2.elements["contract"].value != '')
	{
		var postData = 'do.php?_action=view_edit_participant'
				+ '&dob=' + encodeURIComponent(myForm.elements["dob"].value)
				+ '&contract_id=' + encodeURIComponent(myForm2.elements["contract"].value)
				+ '&subaction=' + encodeURIComponent("validateDOB")
			;

		var req = ajaxRequest(postData);
		var anyError = '';
		$(req.responseXML).find('Error').each(function(){
			anyError += $(this).text() + '\r\n';
		});

		if(anyError != '')
		{
			alert(anyError);
			return false;
		}
	}

	var postData = 'do.php?_action=view_edit_participant'
			+ '&firstnames=' + encodeURIComponent(myForm.elements["firstnames"].value)
			+ '&surname=' + encodeURIComponent(myForm.elements["surname"].value)
			+ '&dob=' + encodeURIComponent(myForm.elements["dob"].value)
			+ '&id=' + encodeURIComponent(participant_id)
			+ '&adviser=' + encodeURIComponent(myForm.elements["adviser"].value)
			+ '&subaction=' + encodeURIComponent("updateBasicDetails")
		;

	var req = ajaxRequest(postData);

	window.location.href = 'do.php?_action=view_edit_participant&id=' + req.responseText;

}

function saveBasicDetails()
{
	var myForm = document.forms["frmBasicDetails"];
	if(myForm.elements["firstnames"].value == '' || myForm.elements["surname"].value == '' || myForm.elements["dob"].value == '' || myForm.elements["adviser"].value == '')
	{
		alert('Please enter all basic details.');
		return;
	}

	var anyDuplicates = checkDuplicates();
	if(anyDuplicates)
	{
		return;
	}

	var postData = 'do.php?_action=view_edit_participant'
			+ '&firstnames=' + encodeURIComponent(myForm.elements["firstnames"].value)
			+ '&surname=' + encodeURIComponent(myForm.elements["surname"].value)
			+ '&dob=' + encodeURIComponent(myForm.elements["dob"].value)
			+ '&adviser=' + encodeURIComponent(myForm.elements["adviser"].value)
			+ '&subaction=' + encodeURIComponent("saveAndGenerateID")
		;

	var req = ajaxRequest(postData);

	$("#demo").addClass("yui-navset");

	window.location.href = 'do.php?_action=view_edit_participant&id=' + req.responseText;
}

function fetchParticipantAddress()
{
	$("input[name=input_address1]").val($("input[name=home_address_line_1]").val());
	$("input[name=input_address2]").val($("input[name=home_address_line_2]").val());
	$("input[name=input_address3]").val($("input[name=home_address_line_3]").val());
	$("input[name=input_address4]").val($("input[name=home_address_line_4]").val());
	$("input[name=input_postcode]").val($("input[name=home_postcode]").val());
}

function addContact()
{
	var inputRow = $('table#contacts tr#inputRow')[0];
	var spacerRow = $('table#contacts tr#spacerRow')[0];
	var templateRow = $('table#contacts tr#templateRow')[0];

	var guardian_name = jQuery.trim($("input[name=input_guardian_name]", inputRow).val());
	var address1 = jQuery.trim($("input[name=input_address1]", inputRow).val());
	var address2 = jQuery.trim($("input[name=input_address2]", inputRow).val());
	var address3 = jQuery.trim($("input[name=input_address3]", inputRow).val());
	var address4 = jQuery.trim($("input[name=input_address4]", inputRow).val());
	var postcode = jQuery.trim($("input[name=input_postcode]", inputRow).val());
	var telephone = jQuery.trim($("input[name=input_telephone]", inputRow).val());
	var mobile = jQuery.trim($("input[name=input_mobile]", inputRow).val());
	var email = jQuery.trim($("input[name=input_email]", inputRow).val());
	if(!(guardian_name && (telephone || mobile || email)))
	{
		alert('Please enter Guardian Name and one of the following fields: telephone, mobile, or email');
		return;
	}

	var relation = $("select[name=relation]", inputRow).val();
	relation =  $("select[name=relation] option[value=" + relation + "]").html();

	var clone = templateRow.cloneNode(true);
	clone.id = "";
	$(clone).addClass("dataRow");
	$(clone).insertBefore(spacerRow);
	$("td.guardian_name", clone).html($("input[name=input_guardian_name]", inputRow).val());
	$("td.relation", clone).html(relation);
	$("td.address1", clone).html($("input[name=input_address1]", inputRow).val());
	$("td.address2", clone).html($("input[name=input_address2]", inputRow).val());
	$("td.address3", clone).html($("input[name=input_address3]", inputRow).val());
	$("td.address4", clone).html($("input[name=input_address4]", inputRow).val());
	$("td.postcode", clone).html($("input[name=input_postcode]", inputRow).val());
	$("td.telephone", clone).html($("input[name=input_telephone]", inputRow).val());
	$("td.mobile", clone).html($("input[name=input_mobile]", inputRow).val());
	$("td.email", clone).html($("input[name=input_email]", inputRow).val());
	$("input[name=input_guardian_name]", inputRow).val("");
	$("select[name=relation]", inputRow)[0].selectedIndex = 0;
	$("input[name=input_address1]", inputRow).val("");
	$("input[name=input_address2]", inputRow).val("");
	$("input[name=input_address3]", inputRow).val("");
	$("input[name=input_address4]", inputRow).val("");
	$("input[name=input_postcode]", inputRow).val("");
	$("input[name=input_telephone]", inputRow).val("");
	$("input[name=input_mobile]", inputRow).val("");
	$("input[name=input_email]", inputRow).val("");
	$(clone).show();

	$("input[name=input_guardian_name]", inputRow).focus();
}

function editContact(element)
{
	var row = element.parentNode.parentNode;
	var inputRow = document.getElementById("inputRow");

	var guardian_name = jQuery.trim($("input[name=input_guardian_name]", inputRow).val());
	var address1 = jQuery.trim($("input[name=input_address1]", inputRow).val());
	var address2 = jQuery.trim($("input[name=input_address2]", inputRow).val());
	var address3 = jQuery.trim($("input[name=input_address3]", inputRow).val());
	var address4 = jQuery.trim($("input[name=input_address4]", inputRow).val());
	var postcode = jQuery.trim($("input[name=input_postcode]", inputRow).val());
	var telephone = jQuery.trim($("input[name=input_telephone]", inputRow).val());
	var mobile = jQuery.trim($("input[name=input_mobile]", inputRow).val());
	var email = jQuery.trim($("input[name=input_email]", inputRow).val());

	if(guardian_name && (telephone || mobile || email))
	{
		addContact();
	}

	var relation = $("td.relation", row).html();
	if(relation)
	{
		relation =  $("select[name=relation] option:contains(" + relation + ")").attr("value");
	}

	$("input[name=input_guardian_name]", inputRow).val($("td.guardian_name", row).html());
	$("select[name=relation]", inputRow).val(relation);
	$("input[name=input_address1]", inputRow).val($("td.address1", row).html());
	$("input[name=input_address2]", inputRow).val($("td.address2", row).html());
	$("input[name=input_address3]", inputRow).val($("td.address3", row).html());
	$("input[name=input_address4]", inputRow).val($("td.address4", row).html());
	$("input[name=input_postcode]", inputRow).val($("td.postcode", row).html());
	$("input[name=input_telephone]", inputRow).val($("td.telephone", row).html());
	$("input[name=input_mobile]", inputRow).val($("td.mobile", row).html());
	$("input[name=input_email]", inputRow).val($("td.email", row).html());
	$(row).remove();

	$("input[name=input_guardian_name]", inputRow).focus();
}

function deleteContact(element)
{
	/*
	 if(window.confirm("Delete contact?"))
	 {
	 var row = element.parentNode.parentNode;
	 $(row).remove();
	 $("input[name=input_guardian_name]", inputRow).focus();
	 }
	 */
	var question = "Delete Contact?";
	confirmation(question).then(function (answer) {
		var ansbool = (String(answer) == "true");
		if(ansbool){
			var row = element.parentNode.parentNode;
			$(row).remove();
			$("input[name=input_guardian_name]", inputRow).focus();
		} else {
			return ;
		}
	});
}

function getContacts()
{
	var relation = null;
	var contacts = new Array();
	var rows = $('table#contacts tr.dataRow');
	for(var i = 0; i < rows.length; i++)
	{
		relation = $("td.relation", rows.eq(i)).html();
		if(relation)
		{
			relation = $("select[name=relation] option:contains(" + relation +")").attr("value");
		}

		contacts[i] = new Object();
		contacts[i].id = i+1;
		contacts[i].guardian_name = $("td.guardian_name", rows.eq(i)).html();
		contacts[i].relation = relation;
		contacts[i].address1 = $("td.address1", rows.eq(i)).html();
		contacts[i].address2 = $("td.address2", rows.eq(i)).html();
		contacts[i].address3 = $("td.address3", rows.eq(i)).html();
		contacts[i].address4 = $("td.address4", rows.eq(i)).html();
		contacts[i].telephone = $("td.telephone", rows.eq(i)).html();
		contacts[i].postcode = $("td.postcode", rows.eq(i)).html();
		contacts[i].mobile = $("td.mobile", rows.eq(i)).html();
		contacts[i].email = $("td.email", rows.eq(i)).html();
	}

	return contacts;
}

function l14_onchange(ele)
{
	if(ele.value == '1')
	{
		$('#primary_lldd').attr('class', 'compulsory validate[required]');
		$('#grid_lldd_cat').attr('class', 'compulsory validate[required]');
	}
	else
	{
		$('#primary_lldd').attr('class', 'disabled');
		$('#grid_lldd_cat').attr('class', 'disabled');
	}
}

function saveTab1()
{
	var myForm = document.forms["frmDetails"];

	var contract = myForm.elements['contract'].value;
	var borough = myForm.elements['home_address_line_3'].value;
	var gender = myForm.elements['gender'].value;
	if(validateForm(myForm, new Array('contract')) == false)
	{
		return false;
	}
	if(contract != '')
	{
		if(!verifyContract(contract, borough, gender))
			return;

	}

	if(!postcodesValidation())
	{
		return false;
	}
	myForm.submit();
}

function saveTab2()
{
	var myForm = document.forms["frmParticipantContacts"];
	myForm.elements['contacts'].value = JSON.stringify(getContacts());
	myForm.submit();
}

function saveTab3()
{
	var myForm = document.forms["frmParticipantSelfDec"];
	var checkboxes = myForm.elements['evidenceradio'];
	var evidence_id = "";
	var xml = "<questions>";
	var anyUnchecked = false;
	for(var i = 0; i < checkboxes.length; i++)
	{
		if(checkboxes[i].checked)
		{
			evidence_id =  checkboxes[i].value;
			xml += '<question>' + evidence_id + '</question>';
		}
		else
		{
			anyUnchecked = true;
		}
	}
	xml += "</questions>";
	myForm.elements["questions_xml"].value = xml;

	if(anyUnchecked)
		return alert('Please select all checkboxes');

	myForm.submit();
}

function lldd_verification()
{
	if($('#l14').val() == '1')
	{
		if($('#primary_lldd').val() == '')
		{
			custom_alert_OK_only("Please select <b>Primary LLDD Category</b> within <b>ILR & Identifiers</b> tab", "Input Error");
			return false;
		}
	}
}

function id_seen_check()
{
	if($('#id_seen').val() == '1' && ($('#id_seen_type').val() == '12' || $('#id_seen_type').val() == '13') && $('#id_seen_other_desc').val().trim() == '')
	{
		custom_alert_OK_only('Please provide details of Identification Type within <b>ILR & Identifiers</b> tab', 'Input Error');
		return false;
	}
	else if($('#id_seen').val() == '1' && $('#id_seen_type').val() == '1'  && $('#id_seen_passport_number').val().trim() == '')
	{
		custom_alert_OK_only('Please enter passport number of within <b>ILR & Identifiers</b> tab', 'Input Error');
		return false;
	}
}

function saveTab4()
{
	var myForm = document.forms["frmParticipantILRInfo"];

	if(validateForm(myForm) == false)
	{
		return false;
	}
	if(lldd_verification() == false)
	{
		return false;
	}
	myForm.submit();
}

function saveTab7()
{
	/*var myForm = document.forms["frmESFEnrolment"];
	 if(validateForm(myForm) == false)
	 {
	 return false;
	 }
	 myForm.submit();*/
	window.location.href='do.php?_action=manage_enrolment&is_participant=true&participant_id=' + window.phpRecordID
}

function attachContract()
{
	var contract = $('select[name="contract"]');

	if(contract.val() == '')
	{
		custom_alert_OK_only('No contract selected', 'Input Error');
		return;
	}

	if(window.phpParticipantContract == contract.val())
	{
		return;
	}

	if(window.phpTrainingRecordExists == '1' && window.phpParticipantContract != contract.val())
	{
		custom_alert_OK_only('You cannot change the contract as Participant is enrolled with existing contract', 'Input Error');
		return;
	}
	var gender = $('select[name="gender"]').val();
	var borough = $('input[name="home_address_line_3"]').val();

	if(gender.trim() == '')// || gender == 'U' || gender == 'W')
	{
		custom_alert_OK_only('Please select the learner\'s gender', "Validation Error");
		//contract.val('');
		return;
	}
	if(borough.trim() == '')
	{
		custom_alert_OK_only('Please enter the learner\'s borough, alternatively enter the postcode and press Auto-Fill to fetch the county', "Validation Error");
		//contract.val('');
		return;
	}

	var postData = 'do.php?_action=view_edit_participant'
			+ '&participant_id=' + encodeURIComponent(phpRecordID)
			+ '&contract_id=' + encodeURIComponent(contract.val())
			+ '&gender=' + encodeURIComponent(gender)
			+ '&borough=' + encodeURIComponent(borough)
			+ '&existing_contract_id=' + encodeURIComponent(window.phpParticipantContract)
			+ '&subaction=' + encodeURIComponent("validateContract")
		;

	var req = ajaxRequest(postData);
	var anyError = '';
	$(req.responseXML).find('Error').each(function(){
		anyError += $(this).text() + '\r\n';
	});

	if(anyError != '')
	{
		custom_alert_OK_only(anyError, "Validation Error");
		return;
	}

	if(req.status == 200)
	{
		window.location.href = 'do.php?_action=view_edit_participant&id=' + phpRecordID;
	}
	else
	{
		alert(req.responseText);
	}

	saveTab1();

}

function verifyContract(contract, borough, gender)
{
	if(contract == '')
	{
		return true;
	}

	if(window.phpTrainingRecordExists == '1' && window.phpParticipantContract != contract)
	{
		custom_alert_OK_only('You cannot change the contract as Participant is enrolled with existing contract', 'Input Error');
		return;
	}

	if(gender.trim() == '')// || gender == 'U' || gender == 'W')
	{
		custom_alert_OK_only('Please select the learner\'s gender', "Input Error");
		return false;
	}
	if(borough.trim() == '')
	{
		custom_alert_OK_only('Please enter the learner\'s borough', "Input Error");
		return false;
	}

	var postData = 'do.php?_action=view_edit_participant'
			+ '&participant_id=' + encodeURIComponent(phpRecordID)
			+ '&contract_id=' + encodeURIComponent(contract)
			+ '&gender=' + encodeURIComponent(gender)
			+ '&borough=' + encodeURIComponent(borough)
			+ '&existing_contract_id=' + encodeURIComponent(phpParticipantContract)
			+ '&subaction=' + encodeURIComponent("validateContract")
		;

	var req = ajaxRequest(postData);
	var anyError = '';
	$(req.responseXML).find('Error').each(function(){
		anyError += $(this).text() + '\r\n';
	});

	if(anyError != '')
	{
		custom_alert_OK_only(anyError, "Validation Error");
		return false;
	}

	return true;
}

function createParticipantTriageAssessment()
{

	window.location.replace('do.php?_action=triage_assessment&participant_id=' + phpRecordID);
}

function saveAll()
{
	var question = "This action will save the information from the following tabs: <br>1. Details<br>2. Parent/Guardian<br>3. Self Declaration<br>4. ILR & Identifiers<br>Are you sure you want to continue?";
	confirmation(question).then(function (answer) {
		var ansbool = (String(answer) == "true");
		if(ansbool){
			var myForm1 = document.forms["frmDetails"];
			var contract = myForm1.elements['contract'].value;
			var borough = myForm1.elements['home_address_line_3'].value;
			var gender = myForm1.elements['gender'].value;
			if(contract != '')
			{
				if(!verifyContract(contract, borough, gender))
				{
					return;
				}
			}
			if(validateForm(myForm1) == false)
			{
				return;
			}
			if(!postcodesValidation())
			{
				return false;
			}
			var client1 = ajaxPostForm(myForm1);
			if(client1 != null)
			{
				if(client1.responseText == 'success') // tab1 records saved successfully
				{
					var myForm2 = document.forms["frmParticipantContacts"];
					myForm2.elements['contacts'].value = JSON.stringify(getContacts());
					var client2 = ajaxPostForm(myForm2);
					if(client2 != null)
					{
						if(client2.responseText == 'success') // tab2 records saved successfully
						{
							var myForm3 = document.forms["frmParticipantSelfDec"];
							var checkboxes = myForm3.elements['evidenceradio'];
							var evidence_id = "";
							var xml = "<questions>";
							var anyUnchecked = false;
							for(var i = 0; i < checkboxes.length; i++)
							{
								if(checkboxes[i].checked)
								{
									evidence_id =  checkboxes[i].value;
									xml += '<question>' + evidence_id + '</question>';
								}
								else
								{
									anyUnchecked = true;
								}
							}
							xml += "</questions>";
							myForm3.elements["questions_xml"].value = xml;

							if(anyUnchecked)
								return custom_alert_OK_only('Please select all checkboxes of self-declaration questions', "Input Error");
							var client3 = ajaxPostForm(myForm3);
							if(client3 != null)
							{
								if(client3.responseText == 'success') // tab3 records saved successfully
								{
									var myForm4 = document.forms["frmParticipantILRInfo"];
									if(lldd_verification() == false)
									{
										return;
									}
									if(id_seen_check() == false)
									{
										return;
									}
									if(validateForm(myForm4) == false)
									{
										return;
									}
									var client4 = ajaxPostForm(myForm4);
									if(client4 != null)
									{
										if(client4.responseText == 'success') // tab4 records saved successfully
										{
											window.location.reload();
										}
										else
											custom_alert_OK_only(client4.responseText);
									}
								}
								else
									custom_alert_OK_only(client3.responseText);
							}
						}
						else
							custom_alert_OK_only(client2.responseText);
					}
				}
				else
					custom_alert_OK_only(client1.responseText);
			}
		} else {
			return ;
		}
	});
}

function lsr_onclick(el)
{
	var grid = document.getElementById('grid_lsr');
	var inputs = grid.getElementsByTagName('INPUT');
	var totalTicked = 0;
	for(var i = 0; i < inputs.length; i++)
	{
		if(inputs[i].checked)
			totalTicked++;
		if(totalTicked > 4)
			break;
	}
	if(totalTicked > 4)
	{
//		alert('You can only select maximum of 4 items.');
		custom_alert_OK_only("You can only select maximum of 4 items", "Input Error");
		el.checked = false;
	}
}


function hhs_onclick(el)
{
	var grid = document.getElementById('grid_hhs');
	var inputs = grid.getElementsByTagName('INPUT');
	switch (el.value)
	{
		case '1':
			if(inputs[1].checked)
			{
				var question = "You can record up to two HHS records. You can record either codes HHS1 or HHS2 but not both of these. Code HHS3 may then apply in addition to code HHS1. Do you want to change?";
				confirmation(question).then(function (answer) {
					var ansbool = (String(answer) == "true");
					if(ansbool){
						inputs[1].checked = false;
					} else {
						inputs[0].checked = false;
					}
				});
			}
			inputs[3].checked = false;
			inputs[4].checked = false;
			break;
		case '2':
			if(inputs[0].checked || inputs[2].checked)
			{
				var question = "You can record up to two HHS records. You can record either codes HHS1 or HHS2 but not both of these. Do you want to change?";
				confirmation(question).then(function (answer) {
					var ansbool = (String(answer) == "true");
					if(ansbool){
						inputs[0].checked = false;
						inputs[2].checked = false;
					} else {
						inputs[1].checked = false;
					}
				});
			}
			inputs[3].checked = false;
			inputs[4].checked = false;
			break;
		case '3':
			if(inputs[1].checked)
			{
				var question = "Code HHS3 can only be used in addition to code HHS1. Do you want to change?";
				confirmation(question).then(function (answer) {
					var ansbool = (String(answer) == "true");
					if(ansbool){
						inputs[1].checked = false;
					} else {
						inputs[0].checked = false;
						inputs[2].checked = false;
					}
				});
			}
			inputs[3].checked = false;
			inputs[4].checked = false;
			break;
		case '99':
			inputs[0].checked = false;
			inputs[1].checked = false;
			inputs[2].checked = false;
			inputs[4].checked = false;
			break;
		case '98':
			inputs[0].checked = false;
			inputs[1].checked = false;
			inputs[2].checked = false;
			inputs[3].checked = false;
			break;
	}
}

function confirmation(question) {
	var defer = $.Deferred();
	$('<div></div>')
		.html(question)
		.dialog({
			autoOpen: true,
			modal: true,
			title: 'Confirmation',
			buttons: {
				"Yes": function () {
					defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
					$(this).dialog("close");
				},
				"No": function () {
					defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
					$(this).dialog("close");
				}
			},
			close: function () {
				//$(this).remove();
				$(this).dialog('destroy').remove()
			}
		});
	return defer.promise();
};

function confirm_on_cancel(){
	var question = "There might be some unsaved changes, are you sure to continue?";
	confirmation(question).then(function (answer) {
		var ansbool = (String(answer) == "true");
		if(ansbool){
			window.location.href = phpHref;
		} else {
			return ;
		}
	});
}

function custom_alert_OK_only(output_msg, title_msg)
{
	if (!title_msg)
		title_msg = 'Alert';

	if (!output_msg)
		output_msg = 'No Message to Display.';

	$("<div></div>").html(output_msg).dialog({
		title: title_msg,
		resizable: false,
		modal: true,
		buttons: {
			"OK": function()
			{
				$( this ).dialog( "close" );
			}
		}
	});
}


function downloadDocumentsPack()
{
	if($('#id_seen').val() == '' || $('#id_seen').val() == '0')
		return custom_alert_OK_only('<b>ID Seen</b> field within <b>ILR & Identifiers</b> tab should be selected and saved as "Yes" to create an Initial Assessment Plan', 'Input Error');
	else if($('#id_seen').val() == '1' && window.phpIDSeen != '1')
		return custom_alert_OK_only('<b>ID Seen</b> is selected as Yes, but not saved, please click on Save to update the participant record', 'Input Error');
	if(window.phpActionPlanExists == '0')
		return custom_alert_OK_only('To create an Initial Assessment Plan, followig items are required:<br><ul><li>Triage Assessment</li><li>Action Plan</li><li>Training Record</li></ul><br>Please create these items for this participant in order to download an Initial Assessment Plan', 'Input Error');
	window.location.href="do.php?_action=generate_document_pack&participant_id=" + window.phpRecordID;
}

function showInfo(field_desc)
{
	custom_alert_OK_only('Participant has got a training record so ' + field_desc + ' can now only be changed from ILR', "Information");
}

function id_seen_onchange(ele)
{
	if(ele.value == '1')
	{
		$('#lbl_id_seen_type').attr('class', 'fieldLabel_compulsory');
		$('#lbl_id_seen_type').show();
		$('#lbl_id_seen_type').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		$('#id_seen_type').attr('class', 'compulsory validate[required]');
		$('#id_seen_type').show();
		$('#id_seen_type').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		if($('#id_seen_type').val() == '12' || $('#id_seen_type').val() == '13')
		{
			$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_compulsory');
			$('#lbl_id_seen_other_desc').show();
			$('#lbl_id_seen_other_desc').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
			$('#id_seen_other_desc').attr('class', 'compulsory validate[required]');
			$('#id_seen_other_desc').show();
			$('#id_seen_other_desc').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		}
		else if($('#id_seen_type').val() == '1')
		{
			$('#lbl_id_seen_passport_number').attr('class', 'fieldLabel_compulsory');
			$('#lbl_id_seen_passport_number').show();
			$('#lbl_id_seen_passport_number').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
			$('#id_seen_passport_number').attr('class', 'compulsory validate[required]');
			$('#id_seen_passport_number').show();
			$('#id_seen_passport_number').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		}
		else
		{
			$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_optional');
			$('#lbl_id_seen_other_desc').hide();
			$('#id_seen_other_desc').attr('class', 'optional');
			$('#id_seen_other_desc').hide();
		}
	}
	else
	{
		$('#lbl_id_seen_type').attr('class', 'fieldLabel_optional');
		$('#lbl_id_seen_type').hide();
		$('#id_seen_type').attr('class', 'optional');
		$('#id_seen_type').hide();
		$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_optional');
		$('#lbl_id_seen_other_desc').hide();
		$('#id_seen_other_desc').attr('class', 'optional');
		$('#id_seen_other_desc').hide();
		$('#lbl_id_seen_passport_number').attr('class', 'optional');
		$('#lbl_id_seen_passport_number').hide();
		$('#id_seen_passport_number').attr('class', 'optional');
		$('#id_seen_passport_number').hide();
	}
}

function id_seen_type_onchange(ele)
{
	if(ele.value == '12' || ele.value == '13')
	{
		$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_compulsory');
		$('#lbl_id_seen_other_desc').show();
		$('#lbl_id_seen_other_desc').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		$('#id_seen_other_desc').attr('class', 'compulsory validate[required]');
		$('#id_seen_other_desc').show();
		$('#id_seen_other_desc').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		$('#lbl_id_seen_passport_number').attr('class', 'fieldLabel_optional');
		$('#lbl_id_seen_passport_number').hide();
		$('#id_seen_passport_number').attr('class', 'optional');
		$('#id_seen_passport_number').hide();
	}
	else if(ele.value == '1')
	{
		$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_optional');
		$('#lbl_id_seen_other_desc').hide();
		$('#id_seen_other_desc').attr('class', 'optional');
		$('#id_seen_other_desc').hide();
		$('#lbl_id_seen_passport_number').attr('class', 'fieldLabel_compulsory');
		$('#lbl_id_seen_passport_number').show();
		$('#lbl_id_seen_passport_number').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
		$('#id_seen_passport_number').attr('class', 'compulsory validate[required]');
		$('#id_seen_passport_number').show();
		$('#id_seen_passport_number').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
	}
	else
	{
		$('#lbl_id_seen_other_desc').attr('class', 'fieldLabel_optional');
		$('#lbl_id_seen_other_desc').hide();
		$('#id_seen_other_desc').attr('class', 'optional');
		$('#id_seen_other_desc').hide();
		$('#lbl_id_seen_passport_number').attr('class', 'fieldLabel_optional');
		$('#lbl_id_seen_passport_number').hide();
		$('#id_seen_passport_number').attr('class', 'optional');
		$('#id_seen_passport_number').hide();
	}
}

function downloadULNFromLRS()
{
	if(window.phpSite.search('demo') != -1)
	{
		custom_alert_OK_only('You cannot use this functionality on Demo site.', 'Information');
		return;
	}

	var firstnames = $('input[name="firstnames"]').val();
	var surname = $('input[name="surname"]').val();
	var postcode = $('input[name="home_postcode"]').val();
	var dob = $('input[name="dob"]').val();
	var gender = $('select[name="gender"]').val();

	if(firstnames == '')
	{
		custom_alert_OK_only('Please provide the first name(s)', "Alert");
		return;
	}
	if(surname == '')
	{
		custom_alert_OK_only('Please provide the surname', "Alert");
		return;
	}
	if(gender == '')
	{
		custom_alert_OK_only('Please select the gender', "Alert");
		return;
	}
	if(dob == '')
	{
		custom_alert_OK_only('Please provide the date of birth', "Alert");
		return;
	}
	if(postcode == '')
	{
		custom_alert_OK_only("Please enter postcode", "Alert");
		return;
	}
	else
	{
		if(!postcodesValidation())
		{
			return false;
		}
	}
	$("#btn_downloadULNFromLRS").attr('class', '');
	$("#btn_downloadULNFromLRS").html('<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;" />');

	var postData = '&subaction=searchByDemographics' +
			'&find_type=FUL' +
			'&firstnames=' + (firstnames) +
			'&surname=' + (surname) +
			'&home_postcode=' + (postcode) +
			'&dob=' + (dob) +
			'&gender=' + (gender)
		;

	var client = ajaxRequest('do.php?_action=ajax_download_uln_from_lrs'+postData, null, null, loadCommands_callback1);

}

function loadCommands_callback1(req)
{
	$("#btn_downloadULNFromLRS").html('Download from LRS');
	$("#btn_downloadULNFromLRS").attr('class', 'button');
	if(req.responseText.search('WSRC0004') != -1)
		$("input[name='l45']").val(req.responseText.replace('(WSRC0004)', ''));
	else if(req.responseText.search('WSRC0001') != -1 || req.responseText.search('WSRC0002') != -1 || req.responseText.search('WSRC0003') != -1)
	{
		console.log(req.responseText);
		$("<div></div>").html(req.responseText).dialog({
			id: "dlg_lrs_result",
			title: "LRS webservice Result",
			resizable: false,
			modal: true,
			width: 750,
			height: 500,

			buttons: {
				'Close': function() {$(this).dialog('close');}
			}
		});
	}
}

function copyULN(uln)
{
	$("input[name='l45']").val(uln);
}
