// jQuery initialisation
$(function(){
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
	var usertype = phpPeople;
	var value = window.prompt("Enter new Job Role for " + usertype);
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
			alert("Incorrect format for Postcode");
			document.getElementById("home_postcode").focus();
			return false;
		}
	}

	var postcode_value = document.getElementById("work_postcode").value;
	if(postcode_value.toLowerCase() == 'zz99 9zz')
		return true;
	if( !postcode_value.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) )
	{
		alert("Incorrect format for Postcode");
		document.getElementById("work_postcode").focus();
		return false;
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
	var myForm = document.forms[0];
	var home_postcode = myForm.elements['home_postcode'].value;
	if(home_postcode=='')
	{
		alert("Please enter postcode");
	}
	else
	{
		var client = ajaxRequest('do.php?_action=ajax_get_borough&postcode='	+ encodeURIComponent(home_postcode));
		myForm.elements['home_address_line_3'].value = client.responseText;
	}
}


function isPasswordValid(pwd_element)
{
	var pwd_value = jQuery.trim(pwd_element.value);
	if(pwd_value.length == 0)
	{
		return true;
	}
	if(pwd_value.length > 0 && pwd_value.length < 8)
	{
		alert("Password must be between 8 and 50 characters long");
		pwd_element.value = '';
		pwd_element.focus();
		return false;
	}
	// Validate password on server
	var illegalWords = "";
	var client = ajaxRequest("do.php?_action=ajax_check_password_strength"
		+ "&pwd=" + encodeURIComponent(pwd_value)
		+ "&extra_words=" + encodeURIComponent(illegalWords));
	if(client != null)
	{
		var res = eval("(" + client.responseText + ")");
		if(res['code'] == 0)
		{
			alert("Password unsuitable because " + res['message']);
			pwd_element.value = '';
			pwd_element.focus();
			return false;
		}
	}
	return true;
}

function save()
{
	// Lock the save button
	var btnSave = document.getElementById('btnSave');
	btnSave.disabled = true;

	var myForm = document.forms[0];
	var newuser = myForm.elements['newuser'].value;

	if( !validateForm(myForm) ) {
		btnSave.disabled = false;
		return false;
	}

	// First and second name validation
	var fn = myForm.elements['firstnames'];
	var sn = myForm.elements['surname'];
	var re = /^[a-zA-Z\x27\x2D ]+$/;
	if (re.test(fn.value) == false)	{
		alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
		fn.focus();
		btnSave.disabled = false;
		return false;
	}
	if (re.test(sn.value) == false) {
		alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
		sn.focus();
		btnSave.disabled = false;
		return false;
	}

	// ULN validation
	var uln = jQuery.trim(myForm.elements['l45'].value);
	if (uln && !isValidUln(uln)) {
		alert("The ULN '" + uln + "' is invalid. Please correct or remove the ULN before saving.");
		myForm.elements['l45'].focus();
		btnSave.disabled = false;
		return false;
	}

	// ULN duplicate detection
	if (uln) {
		var id = myForm.elements['id'] ? myForm.elements['id'].value : '';
		var employerId = myForm.elements['employer_id'] ? myForm.elements['employer_id'].value : '';
		var client = ajaxRequest('do.php?_action=edit_user&subaction=findExistingUln'
			+ '&id=' + encodeURIComponent(id)
			+ '&employer_id=' + encodeURIComponent(employerId)
			+ '&uln=' + encodeURIComponent(uln));
		if (client) {
			var records = jQuery.parseJSON(client.responseText);
			if (records.length) {
				alert("Another learner exists with ULN '" + uln + "' ("
					+ records[0]['firstnames'] + " " + records[0]['surname']
					+ ") . The ULN is a unique identifier. Please correct or remove the ULN before saving.");
				myForm.elements['l45'].focus();
				btnSave.disabled = false;
				return false;
			}
		} else {
			btnSave.disabled = false;
			return false;
		}
	}

	var ni = jQuery.trim(myForm.elements['ni'].value);
	// NI duplicate detection
	if (ni) {
		var id = myForm.elements['id'] ? myForm.elements['id'].value : '';
		var employerId = myForm.elements['employer_id'] ? myForm.elements['employer_id'].value : '';
		var client = ajaxRequest('do.php?_action=edit_user&subaction=findExistingNI'
			+ '&id=' + encodeURIComponent(id)
			+ '&employer_id=' + encodeURIComponent(employerId)
			+ '&ni=' + encodeURIComponent(ni));
		if (client) {
			var records = jQuery.parseJSON(client.responseText);
			if (records.length) {
				alert("Another learner exists with NI '" + ni + "' ("
					+ records[0]['firstnames'] + " " + records[0]['surname']
					+ "). The NI is a unique identifier. Please correct or remove the NI before saving.");
				myForm.elements['ni'].focus();
				btnSave.disabled = false;
				return false;
			}
		} else {
			btnSave.disabled = false;
			return false;
		}
	}



	if (newuser == 1) {
		// Username validation
		var username = document.forms[0].elements['username'];
		if (username.value == '') {
			alert("Please enter a username");
			btnSave.disabled = false;
			username.focus();
			return false;
		}
		if(jQuery.trim(username.value).length > 0 && jQuery.trim(username.value).length < 8)
		{
			alert("Username must be between 8 and 45 characters long");
			btnSave.disabled = false;
			username.value = '';
			username.focus();
			return false;
		}
		re = /^[a-z][a-z0-9_]+$/;
		username.value = username.value.toLowerCase();
		if(re.test(username.value) == false)
		{
			alert("The username may only contain letters, numbers and underscores, and may not begin with a number");
			btnSave.disabled = false;
			username.focus();
			return false;
		}
		if (!usernameUnique(username.value)) {
			alert("Username " + username.value + " has already been taken by an existing user or group. Please try a different username.");
			btnSave.disabled = false;
			username.focus();
			return false;
		}
	}

/*	var age = calcAge($('input[name="dob"]').val());
	if( age < 11 || age > 110 )
	{
		alert('Age must be between 11 and 110 years');
		btnSave.disabled = false;
		$('input[name="dob"]').focus();
		return false;
	}
*/
	// Password validation
	var pwd_element = myForm.elements['password'];
	var pwd_value = jQuery.trim(pwd_element.value);
	if(pwd_value.length > 0)
	{
		if(!isPasswordValid(pwd_element))
		{
			btnSave.disabled = false;
			return false;
		}
	}

	if(!postcodesValidation())
	{
		btnSave.disabled = false;
		return false;
	}
/*
	// first name and surname validation
	var firstnames = $('input[name="firstnames"]').val();
	var firstnamesOK = searchStringInArray(firstnames);
	if(firstnamesOK != -1)
	{
		alert('First name contains ' + firstnamesOK + ' which is not allowed');
		btnSave.disabled = false;
		$('input[name="firstnames"]').focus();
		return false;
	}

	var surname = $('input[name="surname"]').val();
	var surnameOK = searchStringInArray(surname);
	if(surnameOK != -1)
	{
		alert('Surname contains ' + surnameOK + ' which is not allowed');
		btnSave.disabled = false;
		$('input[name="surname"]').focus();
		return false;
	}
*/
	myForm.submit();
}

function searchStringInArray (str)
{
	str = str.toLowerCase();
	var invalidNameParts = [" known ", " knownas ", " known as ", " aka ", " ka ", " k as ", " kwn as ", " a k a ", " k a ", " kn as ", " was ", " used ", " previously ", " prev ", " pre ", " then ", " formerley ",
		" formerly ", " preferred ", " nee ", " vel ", " change ", " legal ", " birth ", " contact ", " pronounce ", " pronounced ", " or ", " duplicate ", " do not ", "unknown", "not known", "notknown", "do not use", "duplicate"];
	for(var i = 0; i < invalidNameParts.length; i++)
	{
		if (str.search(invalidNameParts[i]) != -1)
			return '"' + invalidNameParts[i] + '"';
	}
	return -1;
}

function username_onfocus(username)
{
	var firstnames = username.form.elements['firstnames'].value.toLowerCase();
	var surname = username.form.elements['surname'].value.toLowerCase();

	if(username.value == '')
	{
		var tmp = firstnames.substring(0,1) + surname.replace(/[^a-zA-Z]/, '');
		tmp = tmp.replace("'", "");
		username.value = tmp.substring(0,21);
	}
	if(username.value.length < 8)
	{
		var i = 1;
		do
		{
			username.value += i++;
		}while(username.value.length < 8);
	}
}


function populateAddress(url, form, elementPrefix)
{
	if (elementPrefix == null) {
		elementPrefix = '';
	}

	var xml = ajaxRequest(url);
	var $xml = $(xml.responseXML);

	/*	$('[name="' + elementPrefix + 'saon_start_number' + '"]', form).val($xml.find('saon_start_number').text());
	 $('[name="' + elementPrefix + 'saon_start_suffix' + '"]', form).val($xml.find('saon_start_suffix').text());
	 $('[name="' + elementPrefix + 'saon_end_number' + '"]', form).val($xml.find('saon_end_number').text());
	 $('[name="' + elementPrefix + 'saon_end_suffix' + '"]', form).val($xml.find('saon_end_suffix').text());
	 $('[name="' + elementPrefix + 'saon_description' + '"]', form).val($xml.find('saon_description').text());
	 $('[name="' + elementPrefix + 'paon_start_number' + '"]', form).val($xml.find('saon_start_number').text());
	 $('[name="' + elementPrefix + 'paon_start_suffix' + '"]', form).val($xml.find('saon_start_suffix').text());
	 $('[name="' + elementPrefix + 'paon_end_number' + '"]', form).val($xml.find('saon_end_number').text());
	 $('[name="' + elementPrefix + 'paon_end_suffix' + '"]', form).val($xml.find('saon_end_suffix').text());
	 $('[name="' + elementPrefix + 'paon_description' + '"]', form).val($xml.find('paon_description').text());
	 $('[name="' + elementPrefix + 'street_description' + '"]', form).val($xml.find('street_description').text());
	 $('[name="' + elementPrefix + 'locality' + '"]', form).val($xml.find('locality').text());
	 $('[name="' + elementPrefix + 'town' + '"]', form).val($xml.find('town').text());
	 $('[name="' + elementPrefix + 'county' + '"]', form).val($xml.find('county').text());*/

	$('[name="' + elementPrefix + 'address_line_1' + '"]', form).val($xml.find('address_line_1').text());
	$('[name="' + elementPrefix + 'address_line_2' + '"]', form).val($xml.find('address_line_2').text());
	$('[name="' + elementPrefix + 'address_line_3' + '"]', form).val($xml.find('address_line_3').text());
	$('[name="' + elementPrefix + 'address_line_4' + '"]', form).val($xml.find('address_line_4').text());
	$('[name="' + elementPrefix + 'postcode' + '"]', form).val($xml.find('postcode').text());

	$('[name="' + elementPrefix + 'telephone' + '"]', form).val($xml.find('telephone').text());
	$('[name="' + elementPrefix + 'mobile' + '"]', form).val($xml.find('mobile').text());
	$('[name="' + elementPrefix + 'fax' + '"]', form).val($xml.find('fax').text());
	$('[name="' + elementPrefix + 'email' + '"]', form).val($xml.find('email').text());

	var envelope = document.getElementById(elementPrefix + '_envelope');
	if (envelope) {
		envelope.update(form, elementPrefix);
	}
}


/**
 * Helper to populateAddress()
 */
function extractAddressField(address, fieldName)
{
	var elements = address.getElementsByTagName(fieldName);
	if(elements.length > 0)
	{
		if(elements[0].firstChild)
		{
			return elements[0].firstChild.nodeValue;
		}
	}

	return '';
}


/**
 * When the organisation name changes, reload the locations box
 */
function employer_id_onchange(org_id, event)
{
	var f = org_id.form;
	var loc = f.elements['employer_location_id'];


	if(org_id.value != '')
	{
		org_id.disabled = true;
		loc.disabled = true;
		ajaxPopulateSelect(loc, 'do.php?_action=ajax_load_location_dropdown&org_id=' + org_id.value);
		loc.disabled = false;
		org_id.disabled =false;
	}
	else
	{
		emptySelectElement(loc);
	}
}



function employer_location_id_onchange(loc)
{
	if(loc.value != '')
	{
		populateAddress('do.php?_action=ajax_load_organisation_address&loc_id=' + loc.value, document.forms[0], 'work_');
	}
}


function checkUsernameAvailability()
{
	var username = document.forms[0].elements['username'];

	if(username.value == '')
	{
		return;
	}

	var client = ajaxRequest('do.php?_action=ajax_is_identifier_unique&identifier='
		+ encodeURIComponent(username.value));

	if(client != null)
	{
		if(client.responseText == 1)
		{
			alert("Username available");
		}
		else
		{
			alert("Username already taken (by a user or group)");
		}
	}
}


/*function newaddress()
 {
 var thisForm = document.getElementById('useredit');

 var nextCurrentValue = 1;

 if( document.forms[0].elements['newaddresscount'].value != '' ) {
 nextCurrentValue = document.forms[0].elements['newaddresscount'].value;
 }

 var newaddressdata = document.createElement('div');
 var newaddress = '<h3>New Address</h3>';

 newaddress += '<table border="0" cellspacing="4" cellpadding="4">';
 newaddress += '	<col width="140" /><col />';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional" valign="top">Address Title</td>';
 newaddress += '		<td><input class="optional" type="text" name="address_title[]" value="" size="30" maxlength="80"/></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional" valign="top">Set as primary address?</td>';
 newaddress += '		<td><input type="checkbox" name="address_primary[]" value="" /></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional" valign="top">Delete this address?</td>';
 newaddress += '		<td><input type="checkbox" name="address_delete[]" value="" /></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional" valign="top">Address</td>';
 newaddress += '		<td class="fieldValue">';
 newaddress += '<fieldset class="bs7666">';
 newaddress += '<legend>Location within building or estate: Flat, room, floor or sub-building</legend>';
 newaddress += '<table border="0" class="bs7666">';
 newaddress += '<!--  SAON -->';
 newaddress += '<tbody><tr>';
 newaddress += '	<td align="left"><span class="fieldLabel">start</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="4" size="3" value="" name="address_saon_start_number[]" class="optional"></td>';
 newaddress += '	<td align="left"><span class="fieldLabel">suffix</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="1" size="1" value="" name="address_saon_start_suffix[]" class="optional"></td>';
 newaddress += '		<td align="left" valign="middle">&nbsp;<br>&nbsp;-&nbsp;</td>';
 newaddress += '		<td align="left"><span class="fieldLabel">end</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="4" size="3" value="" name="address_saon_end_number[]" class="optional"></td>';
 newaddress += '		<td align="left"><span class="fieldLabel">suffix</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="1" size="1" value="" name="address_saon_end_suffix[]" class="optional"></td>';
 newaddress += '		<td align="left"><span class="fieldLabel">Flat, room, floor or sub-building name</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'home_'+nextCurrentValue+'\')" maxlength="90" size="30" value="" name="address_saon_description[]" class="optional"></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '			<td colspan="6"><span onclick="showHideBlock(\'address_'+nextCurrentValue+'saon_example1\');showHideBlock(\'address_'+nextCurrentValue+'saon_example2\');" style="cursor: pointer;" class="cellText">Show/hide examples</span></td>';
 newaddress += '		</tr>';
 newaddress += '		<tr style="display: none;" id="address_'+nextCurrentValue+'saon_example1">';
 newaddress += '			<td align="left" class="examples">613</td>';
 newaddress += '			<td align="left" class="examples">b</td>';
 newaddress += '		<td align="center">-</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">Sixth Floor</td>';
 newaddress += '	</tr>';
 newaddress += '	<tr style="display: none;" id="address_'+nextCurrentValue+'saon_example2">';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="center">-</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">Engineering Faculty</td>';
 newaddress += '	</tr>';
 newaddress += '</tbody></table>';
 newaddress += '</fieldset>';
 newaddress += '<div class="bs7666">';
 newaddress += '	<span class="fieldLabel">Building or estate name e.g. Civic House</span><br>';
 newaddress += '	<input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="100" size="40" value="" name="address_paon_description[]" class="optional">';
 newaddress += '</div>';
 newaddress += '<fieldset style="margin-top: 10px;" class="bs7666">';
 newaddress += '<legend>Building number and street name</legend>';
 newaddress += '<table border="0" class="bs7666">';
 newaddress += '	<!--  PAON -->';
 newaddress += '	<tbody><tr>';
 newaddress += '		<td align="left"><span class="fieldLabel">start</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="4" size="3" value="" name="address_paon_start_number[]" class="optional"></td>';
 newaddress += '		<td align="left"><span class="fieldLabel">suffix</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="1" size="1" value="" name="address_paon_start_suffix[]" class="optional"></td>';
 newaddress += '		<td align="left" valign="middle">&nbsp;<br>&nbsp;-&nbsp;</td>';
 newaddress += '		<td align="left"><span class="fieldLabel">end</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="4" size="3" value="" name="address_paon_end_number[]" class="optional"></td>';
 newaddress += '		<td align="left"><span class="fieldLabel">suffix</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="1" size="1" value="" name="address_paon_end_suffix[]" class="optional"></td>';
 newaddress += '		<td align="left"><span class="fieldLabel">street name</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="90" size="30" value="" name="address_street_description[]" class="optional"></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td colspan="6"><span onclick="showHideBlock(\'address_'+nextCurrentValue+'paon_example1\');showHideBlock(\'address_'+nextCurrentValue+'paon_example2\');" style="cursor: pointer;" class="cellText">Show/hide examples</span></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr style="display: none;" id="address_'+nextCurrentValue+'paon_example1">';
 newaddress += '		<td align="left" class="examples">221</td>';
 newaddress += '		<td align="left" class="examples">b</td>';
 newaddress += '		<td align="center">-</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">Baker Street</td>';
 newaddress += '	</tr>';
 newaddress += '	<tr style="display: none;" id="address_'+nextCurrentValue+'paon_example2">';
 newaddress += '		<td align="left" class="examples">7</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="center">-</td>';
 newaddress += '		<td align="left" class="examples">9</td>';
 newaddress += '		<td align="left" class="examples">&nbsp;</td>';
 newaddress += '		<td align="left" class="examples">High Street</td>';
 newaddress += '	</tr>';
 newaddress += '</tbody></table>';
 newaddress += '</fieldset>';
 newaddress += '<table cellspacing="0" cellpadding="0">';
 newaddress += '<tbody><tr>';
 newaddress += '	<td valign="top">';
 newaddress += '<div class="bs7666">';
 newaddress += '	<table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td align="left"><span class="fieldLabel">Locality</span></td><td align="right"><span class="fieldLabel">e.g. Covent Garden</span></td></tr></tbody></table>';
 newaddress += '	<input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="35" size="30" value="" name="address_locality[]" class="optional">';
 newaddress += '</div>';
 newaddress += '<div class="bs7666">';
 newaddress += '	<table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td align="left"><span class="fieldLabel">Town</span></td><td align="right"><span class="fieldLabel">e.g. London</span></td></tr></tbody></table>';
 newaddress += '	<input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="30" size="30" value="" name="address_town[]" class="optional">';
 newaddress += '</div>';
 newaddress += '<div class="bs7666">';
 newaddress += '	<table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td align="left"><span class="fieldLabel">County</span></td><td align="right"><span class="fieldLabel">e.g. Middlesex</span></td></tr></tbody></table>';
 newaddress += '	<input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="30" size="30" value="" name="address_county[]" class="optional">';
 newaddress += '</div>';
 newaddress += '<div class="bs7666">';
 newaddress += '		<span class="fieldLabel">Postcode</span><br><input type="text" onkeyup="document.getElementById(\'address_'+nextCurrentValue+'_envelope\').update(this.form, \'address_'+nextCurrentValue+'\')" maxlength="10" size="10" value="" name="address_postcode[]" class="optional">';
 newaddress += '	</div>';
 newaddress += '		</td>';
 newaddress += '		<td>';
 newaddress += '		<div class="envelope" id="address_'+nextCurrentValue+'_envelope">';
 newaddress += '		</div>';
 newaddress += '	</td>';
 newaddress += '</tr>';
 newaddress += '</tbody></table>';
 newaddress += '<script language="JavaScript">';
 newaddress += 'var envelope = document.getElementById(\'address_'+nextCurrentValue+'_envelope\');';
 newaddress += 'envelope.update = function(form, prefix) {';
 newaddress += '	this.innerHTML = bs7666_to_lines(form, prefix).join(\'\<br/\>\');';
 newaddress += '}';
 newaddress += '<\/script>';
 newaddress += '</td>';

 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional">Telephone</td>';
 newaddress += '		<td><input class="optional" type="text" name="address_telephone[]" value="" size="20" maxlength="20"/></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional">Mobile</td>';
 newaddress += '		<td><input class="optional" type="text" name="address_mobile[]" value="" size="20" maxlength="20"/></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional">Fax</td>';
 newaddress += '		<td><input class="optional" type="text" name="address_fax[]" value="" size="20" maxlength="20"/></td>';
 newaddress += '	</tr>';
 newaddress += '	<tr>';
 newaddress += '		<td class="fieldLabel_optional">Email</td>';
 newaddress += '		<td><input class="optional" type="text" name="address_email[]" value="" size="30" maxlength="80"/></td>';
 newaddress += '	</tr>';
 newaddress += '</table>';


 newaddressdata.innerHTML = newaddress;
 thisForm.appendChild(newaddressdata);

 if( document.forms[0].elements['newaddresscount'].value != '' ) {
 document.forms[0].elements['newaddresscount'].value = parseInt(nextCurrentValue)+1;
 }

 return true;

 }*/

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

