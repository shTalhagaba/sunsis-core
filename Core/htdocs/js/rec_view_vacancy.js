

function screenApplication(application_id, candidate_id, vacancy_id)
{
	if(!$('[name="screen_'+application_id+'"]:checked').val())
		return;
	var postData = '_action=rec_save_application'
			+ '&id=' + application_id
			+ '&candidate_id=' + candidate_id
			+ '&vacancy_id=' + vacancy_id
			+ '&application_comments=' + 'Edited from summary screen'
			+ '&application_screening=' + $('[name="screen_'+application_id+'"]:checked').val()
			+ '&application_status=1'
		;
	var request = ajaxRequest('do.php?_action=rec_save_application', postData);
	if(request && request.status == 200)
	{
		alert("Record Saved.");
		window.location.reload();
	}
	else
	{
		alert(request.responseText);
	}
//		console.log(postData);
}

function processApplication(application_id, candidate_id, vacancy_id)
{
	var interview_outcome = "";
	var application_status = $('#ddl_'+application_id).val();
	if(application_status == '')
		return;
	if(application_status == 3 || application_status == 4)
	{
		if(application_status == 3)
			interview_outcome = 1;
		else if(application_status == 4)
			interview_outcome = 0;
		application_status = 2;
	}
	var postData = '_action=rec_save_application'
			+ '&id=' + application_id
			+ '&candidate_id=' + candidate_id
			+ '&vacancy_id=' + vacancy_id
			+ '&application_comments=' + 'Edited from summary screen'
			+ '&application_status=' + application_status
			+ '&interview_outcome=' + interview_outcome
		;
	var request = ajaxRequest('do.php?_action=rec_save_application', postData);
	if(request && request.status == 200)
	{
		alert("Record Saved.");
		window.location.reload();
	}
	else
	{
		alert(request.responseText);
	}
//		console.log(postData);
}

function convertApplication(application_id, candidate_id, vacancy_id)
{
	var interview_outcome = "";
	var application_status = $('#ddl_'+application_id).val();
	if(application_status == 5)
	{
		application_status = 3;
	}
	var postData = '_action=rec_save_application'
			+ '&id=' + application_id
			+ '&candidate_id=' + candidate_id
			+ '&vacancy_id=' + vacancy_id
			+ '&application_comments=' + 'Edited from summary screen'
			+ '&application_status=' + application_status
		;
	var request = ajaxRequest('do.php?_action=rec_save_application', postData);
	if(request && request.status == 200)
	{
		alert("Record Saved.");
		window.location.reload();
	}
	else
	{
		alert(request.responseText);
	}
}

function createProgression(application_id, candidate_id, vacancy_id)
{
	var interview_outcome = "";
	var application_status = $('#ddl_'+application_id).val();

	var postData = '_action=rec_save_application'
			+ '&id=' + application_id
			+ '&candidate_id=' + candidate_id
			+ '&vacancy_id=' + vacancy_id
			+ '&application_comments=' + 'Edited from summary screen'
			+ '&application_status=' + application_status
		;
	var request = ajaxRequest('do.php?_action=rec_save_application', postData);
	if(request && request.status == 200)
	{
		alert("Record Saved.");
		window.location.reload();
	}
	else
	{
		alert(request.responseText);
	}
}

function displayDetail(tr, screening)
{
	var detail_tr = 'detail_'+tr;
	var candid = tr.split('_');
	var table_row = document.getElementById(detail_tr);
	var current_status = table_row.style.display;
	$("tr[id^=detail]").each(function()
	{
		$(this).css('display','none');
	});

	// IE sillyness - check for table-row conformance IE7 +
	if( $.browser.msie && $.browser.version < 7 )
	{
		if ( current_status != 'block' )
		{
			table_row.style.display = 'block';
			if ( window.phpVacancyID != '' )
			{
				var request = ajaxRequest('do.php?_action=ajax_display_candidate_screening','candid='+candid[1]+'&tabid='+candid[0]+'&vacid='+window.phpVacancyID);
				if ( request.responseText.match('/^Successfully/') )
					alert('There has been a problem finding candidate screening');
				else
					$("tr[id="+detail_tr+"] td:first").html(request.responseText);
			}
		}
	}
	else
	{
		if ( current_status != 'table-row' )
		{
			table_row.style.display = 'table-row';
			if ( window.phpVacancyID != '' )
			{
				var request = ajaxRequest('do.php?_action=rec_view_vacancy&subaction=fetchCandidateDetails','candid='+candid[1]+'&selected_tab='+candid[0]+'&vacid='+window.phpVacancyID+'&screening='+screening);
				if ( request.responseText.match('/^Successfully/') )
					alert('There has been a problem finding candidate screening');
				else
					$("tr[id="+detail_tr+"] td:first").html(request.responseText);
			}
		}
	}
}

function updateUrlParameter(url, param, value)
{
	var regex = new RegExp("([?|&]" + param + "=)[^\&]+");console.log(value);
	return url.replace(regex, '$1' + value);
}

function setScreening(application_id, candidate_id, vacancy_id, application_status, application_screening, interview_outcome, next_tab)
{
	var myForm = document.getElementById('screen_'+candidate_id);
	myForm.application_id.value = application_id;
	myForm.candidate_id.value = candidate_id;
	myForm.vacancy_id.value = vacancy_id;
	myForm.application_status.value = application_status;
	myForm.application_screening.value = application_screening;
	myForm.interview_outcome.value = interview_outcome;
	myForm.next_tab.value = next_tab;
	/*myForm.submit();*/
	var client = ajaxPostForm(myForm);
	if(client != null)
	{
		alert('Record Saved/Updated Successfully');
		window.location.href = updateUrlParameter(window.location.href, 'selected_tab', next_tab);
	}
}

function create_progression(application_id, candidate_id, vacancy_id)
{
	window.location.replace('do.php?_action=create_progression_from_recruitment&application_id='+application_id);
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

function btnFrmSearchCandidates()
{
	var myForm = document.forms["frmSearchCandidates"];
	var parameters = '&id=' + window.phpVacancyID + '&subaction=searchMatchingCandidates';
	if(myForm.elements["frmSearchCandidatesFirstName"].value != '')
		parameters += '&frmSearchCandidatesFirstName='+myForm.elements["frmSearchCandidatesFirstName"].value;
	if(myForm.elements["frmSearchCandidatesSurname"].value != '')
		parameters += '&frmSearchCandidatesSurname='+myForm.elements["frmSearchCandidatesSurname"].value;
	if(myForm.elements["frmSearchCandidatesAge"].value != '')
		parameters += '&frmSearchCandidatesAge='+myForm.elements["frmSearchCandidatesAge"].value;
	if(myForm.elements["frmSearchCandidatesRadius"].value != '')
		parameters += '&frmSearchCandidatesRadius='+myForm.elements["frmSearchCandidatesRadius"].value;

	$("#tblSearchCandidatesResults").html('<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;" />');
	var client = ajaxRequest('do.php?_action=rec_view_vacancy'+parameters, null, null, SearchCandidates_callback);

}

function SearchCandidates_callback(client)
{
	$("#tblSearchCandidatesResults").html(client.responseText);
}

function displayCandidateDetail(candidate_id)
{
	var candidate_id = candidate_id.split('_'); // format: 1_ID
	candidate_id = candidate_id[1];

	$("tr[id^=tr_detail_]").each(function() {
		if(this.id != 'tr_detail_'+candidate_id)
			$(this).css('display','none');
	});
	$('#td_detail_'+candidate_id).html('<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;" />');
	var client = ajaxRequest('do.php?_action=rec_view_vacancy&subaction=getCandidateDetail&candidate_id='+candidate_id);
	$('#td_detail_'+candidate_id).html(client.responseText);
	$('#tr_detail_'+candidate_id).toggle();
}

function createApplication(candidate_id)
{
	var parameters = '&subaction=createApplication' +
		'&vacancy_id=' + window.phpVacancyID +
		'&candidate_id=' + candidate_id +
		'&comments=' + $('#comments_'+candidate_id).val()
	;
	var client = ajaxRequest('do.php?_action=rec_view_vacancy'+parameters, null, null, CreateApplication_callback);
}

function CreateApplication_callback()
{
	window.location.reload();
}
