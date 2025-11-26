


function displayprevious()
{

	var current_status = $("#tbl_candidate_info").css('display');
	if ( current_status == 'none' ) {
		$("#tbl_candidate_info").css('display', 'block');
	}
	else {
		$("#tbl_candidate_info").css('display', 'none');
	}
}

function apply()
{
	var myForm = document.forms["frmApply"];
	var vacancy_id = myForm.elements["vacancy_id"].value;

	myForm.submit();
}

function forgotID()
{
	var $dialog = $('#dialog_unique_id');

	$dialog.html("<p>Please enter your email: </p><p><input type='text' id='candidate_email' name='candidate_email' size='50' /></p>");

	$dialog.dialog("open");
}

function searchVacancies()
{
	var myForm = document.forms["frmSearchVacancies"];
	myForm.submit();
}

function displaydetail(tr)
{
	var detail_tr = 'detail_'+tr;
	var table_row = document.getElementById(detail_tr);

	var current_status = table_row.style.display;

	$("tr[id^=detail]").each(function()
	{
		$(this).css('display','none');
	});

	if ( current_status != 'table-row' )
	{
		table_row.style.display = 'table-row';
	}
}

function fn(text)
{
	var $dialog = $('#dialog_unique_id');
	$dialog.dialog("close");

	var $dialog1 = $('#dialog_unique_id_sent');
	$dialog1.html(text);
	$dialog1.dialog("open");
}

$(function() {
	$( "#dialog_unique_id" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: true,
		width:
			450,
		height:
			200,
		buttons: {
			'OK': function() {
				var client = ajaxRequest('do.php?_action=search_vacancies&subaction=forgot_id&candidate_email=' + encodeURIComponent($('#candidate_email').val()));
				if(client){
					fn(client.responseText);
				}
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});

	$( "#dialog_unique_id_sent" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: true,
		width:
			350,
		height:
			200,
		buttons: {
			'OK': function() {
				$(this).dialog('close');
			}
		}
	});

	$( "#dialog_registration" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: true
	});
});

function redirectToApplicationPage()
{
	window.location.href = "do.php?_action=application";
}

function displayprevious()
{

	var current_status = $("#previous_registration").css('display');
	if ( current_status == 'none' )
	{
		$("#previous_registration").css('display', 'block');
	}
	else
	{
		$("#previous_registration").css('display', 'none');
	}
}

function applyForVacancy(vacancy_id)
{
	var form_number = 1 + Math.floor(Math.random() * 50000);

	var html =  '<form method="post" name="frmCandidateApply'+form_number+'" method="post" action="do.php?_action=application">' +
				'<input type="hidden" name="vacancy_id" value=' + vacancy_id + ' />' +
				'<input type="hidden" name="new_candidate" value="1"' +
				'<div>' +
				'<p>If you have registered with us previously, provide the following details and click on \'Returning Candidate\'.</p>' +
				'<p>Alternatively, you can leave these fields blank and click \'New Candidate\'.</p>' +
				'<div>' +
				'<table>'+
				'<tr><td class="fieldLabel_compulsory">First Name:</td><td><input class="compulsory" type="text" name="firstname" id="firstname" size="30" maxlength="100" /> </td></tr>' +
				'<tr><td class="fieldLabel_compulsory">Surname:</td><td><input class="compulsory" type="text" name="surname" id="surname" size="30" maxlength="100" /> </td></tr>' +
				'<tr><td class="fieldLabel_compulsory">Date of Birth:</td><td><input class="datepicker compulsory" type="text" id="dob" name="dob" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>' +
				'<tr><td class="fieldLabel_compulsory">Postcode:</td><td><input class="compulsory" type="text" name="postcode" id="postcode" size="10" maxlength="10" /> </td></tr>' +
				'</table>' +
				'</div>' +
				'</div></form>';

	$("<div></div>").html(html).dialog({
		title: 'Your Details',
		resizable: false,
		modal: true,
		width: 600,
		height: 380,
		buttons: {
			"Returning Candidate": function()
			{
				var myForm = document.forms["frmCandidateApply"+form_number];
				var firstnames = myForm.elements['firstnames'];
				var surname = myForm.elements['surname'];
				var dob = myForm.elements['dob'];
				var postcode = myForm.elements['postcode'];
				myForm.elements["new_candidate"].value = '0';
				if(!validateForm(myForm))
				{
					return false;
				}
				if(dob.value && dob.value != 'dd/mm/yyyy' && window.stringToDate && !window.stringToDate(dob.value))
				{
					alert("Invalid calendar-date or invalid date-format. Please use the format dd/mm/yyyy.");
					return false;
				}
				if(!valid_postcode(postcode.value))
				{
					alert('Please enter a valid UK postcode.');
					return false;
				}
				$( this ).dialog( "close" );
				document.forms["frmCandidateApply"+form_number].submit();
			},
			"New Candidate": function()
			{
				var myForm = document.forms["frmCandidateApply"+form_number];
				var firstnames = myForm.elements['firstnames'];
				var surname = myForm.elements['surname'];
				var dob = myForm.elements['dob'];
				var postcode = myForm.elements['postcode'];
				if(dob.value && dob.value != 'dd/mm/yyyy' && window.stringToDate && !window.stringToDate(dob.value))
				{
					alert("Invalid calendar-date or invalid date-format of Date of Birth. Please use the format dd/mm/yyyy.");
					return false;
				}
				$( this ).dialog( "close" );

				document.forms["frmCandidateApply"+form_number].submit();
			}
		}
	});
}

function valid_postcode(postcode)
{
	postcode = postcode.replace(/\s/g, "");
	var regex = /^[A-Z]{1,2}[0-9]{1,2}[A-Z]{0,1} ?[0-9][A-Z]{2}$/i;;
	return regex.test(postcode);
}