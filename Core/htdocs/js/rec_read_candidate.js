

function addNewCRMNote()
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed -  You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}
	$('#btn_new_crm_note').hide();
	$('#new_crm_note_type_of_contact').val('');
	$('#new_crm_note_subject').val('');
	$('#input_new_crm_note_date').val('');
	$('#input_new_crm_note_next_action_date').val('');
	$('#new_crm_note_agreed_action').val('');
	$('#new_crm_note_other_notes').val('');
	$('#new_crm_note_id').val('');
	$('#new_crm_note_date').focus();
	$('#div_new_crm_note').show();
}
function editCRMNote(note_id)
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed - You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	$('#btn_new_crm_note').hide();
	$('#div_new_crm_note').hide();
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=getCRMNoteDetail&crm_note_id=' + note_id, null, null, get_crm_note_detail_callback);
}
function get_crm_note_detail_callback(client)
{
	if(client.responseText != '')
	{
		$('#div_new_crm_note').show();
		var note_details = JSON.parse(client.responseText, true);
		$('#new_crm_note_type_of_contact').val(note_details.type_of_contact);
		$('#new_crm_note_subject').val(note_details.subject);
		$('#input_new_crm_note_date').val(note_details.crm_date);
		$('#input_new_crm_note_next_action_date').val(note_details.next_action_date);
		$('#new_crm_note_agreed_action').val(note_details.agreed_action);
		$('#new_crm_note_other_notes').val(note_details.other_notes);
		$('#new_crm_note_id').val(note_details.id);
		$('#new_crm_note_application_id').val(note_details.application_id);
		$('#new_crm_note_actioned').val(note_details.actioned);
		$('#new_crm_note_date').focus();
	}
}
function saveNewCRMNote()
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed -  You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	if($('#input_new_crm_note_date').val().trim() == '')
	{
		alert('Please enter date');
		$('#input_new_crm_note_date').focus();
		return false;
	}
	if($('#new_crm_note_subject').val().trim() == '')
	{
		alert('Please enter subject');
		$('#new_crm_note_subject').focus();
		return false;
	}
	if($('#input_new_crm_note_next_action_date').val() != '')
	{
		var crm_action_date = stringToDate($('#input_new_crm_note_date').val());
		var crm_next_action_date = stringToDate($('#input_new_crm_note_next_action_date').val());
		crm_action_date.setHours(0,0,0,0);
		crm_next_action_date.setHours(0,0,0,0);
		if(crm_next_action_date < crm_action_date && !confirm('CRM next action date is before the CRM action date, do you wish to continue?'))
		{
			return false;
		}
	}

	var parameters = '&candidate_id=' + encodeURIComponent(window.phpCandidateId) +
			'&type_of_contact=' + encodeURIComponent($('#new_crm_note_type_of_contact').val()) +
			'&subject=' + encodeURIComponent($('#new_crm_note_subject').val()) +
			'&crm_date=' + encodeURIComponent($('#input_new_crm_note_date').val()) +
			'&next_action_date=' + encodeURIComponent($('#input_new_crm_note_next_action_date').val()) +
			'&agreed_action=' + encodeURIComponent($('#new_crm_note_agreed_action').val()) +
			'&other_notes=' + encodeURIComponent($('#new_crm_note_other_notes').val()) +
			'&application_id_for_crm_note=' + encodeURIComponent($('#new_crm_note_application_id').val()) +
			'&actioned=' + encodeURIComponent($('#new_crm_note_actioned').val()) +
			'&crm_note_id=' + encodeURIComponent($('#new_crm_note_id').val())
		;
	$(".loading-gif").toggle();
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=saveNewCRMNote'	+ parameters, null, null, save_new_crm_note_callback);
}
function save_new_crm_note_callback(client)
{
	$(".loading-gif").toggle();
	if(client.responseText)
	{
		alert('CRM Note successfully added/updated.');
		window.location.href = 'do.php?_action=rec_read_candidate&id=' + window.phpCandidateId + '&selected_tab=tab3'
	}
	else
	{
		alert(client.responseText);
	}
}
function validateEmail(email)
{
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function sendEmail()
{
	/*if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed -  You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}*/

	tinymce.triggerSave();
	if($('#senderEmail').val().trim() == '')
	{
		alert('Your email is required for this operation, please provide your email address.');
		$('#senderEmail').focus();
		return false;
	}
	if(!validateEmail($('#senderEmail').val().trim()))
	{
		alert('Please provide a valid email address');
		$('#senderEmail').focus();
		return false;
	}
	if($('#candidateEmail').val().trim() == '')
	{
		alert('Candidate email is required for this operation, please provide candidate email address.');
		$('#candidateEmail').focus();
		return false;
	}
	if(!validateEmail($('#candidateEmail').val().trim()))
	{
		alert('Please provide a valid email address');
		$('#candidateEmail').focus();
		return false;
	}
	if($('#emailSubject').val().trim() == '')
	{
		alert('Please provide a suitable subject for the email');
		$('#emailSubject').focus();
		return false;
	}
	if($('#email_contents').val().trim() == '')
	{
		alert('You cannot send blank email.');
		$('#email_contents').focus();
		return false;
	}

	var parameters = '&candidate_id=' + encodeURIComponent(window.phpCandidateId) +
			'&candidate_email=' + encodeURIComponent($('#candidateEmail').val()) +
			'&sender_email=' + encodeURIComponent($('#senderEmail').val()) +
			'&subject=' + encodeURIComponent($('#emailSubject').val()) +
			'&email_contents=' + encodeURIComponent($('#email_contents').val())
		;


	$(".loading-gif").toggle();
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=sendEmail'	+ parameters, null, null, send_email_callback);
}
function send_email_callback(request)
{
	$(".loading-gif").toggle();

	if(request.status == 200)
	{
		alert('Email successfully sent and saved.');
		window.location.href = 'do.php?_action=rec_read_candidate&id=' + window.phpCandidateId + '&selected_tab=tab4';
	}
}

function loadEmailTemplate()
{
	var application_id_for_email = $('#application_id_for_email');
	var email_template_type = $('#email_template_type');
	if(application_id_for_email.val() == '')
	{
		alert('Please select application from applications list');
		application_id_for_email.focus();
		return false;
	}
	if(email_template_type.val() == '')
	{
		alert('Please select template from templates list');
		email_template_type.focus();
		return false;
	}
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=loadAndPrepareEmailTemplate&candidate_id=' + window.phpCandidateId + '&application_id_for_email=' + application_id_for_email.val() + '&template_type=' + email_template_type.val(), null, null, loadAndPrepareEmailTemplateCallback);
}

function loadAndPrepareEmailTemplateCallback(client)
{
	tinymce.get('email_contents').getBody().innerHTML = client.responseText;
}

function invite_template_type_onchange(ele)
{
	var application_id = $('#cv_sent_application_id').val();
	if(application_id == '')
	{
		alert('Please select an application from Applications in CV Sent list');
		$('#cv_sent_application_id').focus();
		return false;
	}
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=loadAndPrepareEmailTemplate&candidate_id=' + window.phpCandidateId + '&application_id_for_email=' + application_id + '&template_type=' + ele.value);
	if(client != null && client.responseText != '')
		tinymce.get('invite_description').getBody().innerHTML = client.responseText;
}

function createNewInvite()
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed -  You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	tinymce.triggerSave();
	var myForm = document.forms["frmAddInvite"];
	if(!validateForm(myForm))
		return false;

	var client = ajaxPostForm(myForm, createNewInviteCallback);
}

function createNewInviteCallback(client)
{
	$(".loading-gif").toggle();
	if(client.responseText)
	{
		alert('Invite successfully added/updated.');
		window.location.href = 'do.php?_action=rec_read_candidate&id=' + window.phpCandidateId + '&selected_tab=tab5'
	}
	else
	{
		alert(client.responseText);
	}
}

function addNewInvite()
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed - You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	$('#btnNewInvite').hide();
	$('#frmAddInvite').find("input[type=text], textarea").val("");
	$("#frmAddInvite input[name=title]").focus();
	$('#spanCreateInvite').html('Create');
	$('#div_new_invite').show();
}

function editInvite(note_id)
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed - You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	$('#btnNewInvite').hide();
	$('#div_new_invite').hide();
	$('#spanCreateInvite').html('Update');
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=getInviteDetail&event_id=' + note_id, null, null, getInviteDetailCallback);
}

function getInviteDetailCallback(client)
{
	if(client.responseText != '')
	{
		$('#div_new_invite').show();
		var note_details = JSON.parse(client.responseText, true);
		$("#frmAddInvite input[name=title]").val(note_details.title);
		$("#frmAddInvite input[name=datefrom]").val(note_details.datefrom);
		$("#frmAddInvite input[name=datefromtime]").val(note_details.datefromtime);
		$("#frmAddInvite input[name=datetotime]").val(note_details.datetotime);
		$("#frmAddInvite input[name=dateto]").val(note_details.dateto);
		$("#frmAddInvite input[name=allday]").val(note_details.allday);
		$("#frmAddInvite input[name=location]").val(note_details.location);
		$("#frmAddInvite input[name=event_id]").val(note_details.event_id);
		$("#frmAddInvite input[name=mode]").val('update');
		$("#frmAddInvite input[name=sequence_number]").val(note_details.sequence_number);
		$("#frmAddInvite input[name=event_uid]").val(note_details.event_uid);
		tinymce.get('invite_description').getBody().innerHTML = note_details.description;
		$("#frmAddInvite input[name=title]").focus();
	}
}

function cancelInvite(note_id)
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed - You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	$('#btnNewInvite').hide();
	$('#div_new_invite').hide();
	$('#spanCreateInvite').html('Cancel Event');
	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=getInviteDetail&event_id=' + note_id);
	if(client.responseText != '')
	{
		$('#div_new_invite').show();
		var note_details = JSON.parse(client.responseText, true);
		$("#frmAddInvite input[name=title]").val(note_details.title);
		$("#frmAddInvite input[name=datefrom]").val(note_details.datefrom);
		$("#frmAddInvite input[name=datefromtime]").val(note_details.datefromtime);
		$("#frmAddInvite input[name=datetotime]").val(note_details.datetotime);
		$("#frmAddInvite input[name=dateto]").val(note_details.dateto);
		$("#frmAddInvite input[name=allday]").val(note_details.allday);
		$("#frmAddInvite input[name=location]").val(note_details.location);
		$("#frmAddInvite input[name=event_id]").val(note_details.event_id);
		$("#frmAddInvite input[name=mode]").val('cancel');
		$("#frmAddInvite input[name=sequence_number]").val(note_details.sequence_number);
		$("#frmAddInvite input[name=event_uid]").val(note_details.event_uid);
		tinymce.get('invite_description').getBody().innerHTML = note_details.description;
		$("#frmAddInvite input[name=title]").focus();
	}
	else
	{
		alert(client.responseTexts);
	}
}

function saveEmailAsCRMNote(email_id)
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed - You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=getEmailDetail&email_id=' + email_id);
	if(client.responseText != '')
	{
		var email_details = JSON.parse(client.responseText, true);
		var html = '<p>This action will create a new CRM note from this email. This can then be tracked from the calendar view on e-Recruitment home screen.</p>';
		html += '<table border="0" cellspacing="8" style="margin-left:10px"><col width="250"/><col width="380"/>';
		html += '<tr><td class="fieldLabel_compulsory">Next Action Date:</td><td><input class="datepicker compulsory" type="text" id="next_action_date" name="next_action_date" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>';
		html += '</table>';

		$("<div></div>").html(html).dialog({
			title: "Save Email as CRM note",
			resizable: false,
			modal: true,
			width: 400,
			height: 250,
			buttons: {
				"OK": function()
				{
					$(".loading-gif").toggle();
					var parameters = '&candidate_id=' + encodeURIComponent(window.phpCandidateId) +
							'&type_of_contact=2' +
							'&subject=' + encodeURIComponent(email_details.subject) +
							'&crm_date=' + encodeURIComponent(email_details.created) +
							'&next_action_date=' + encodeURIComponent($('#next_action_date').val()) +
							'&agreed_action=' + encodeURIComponent(email_details.email_body)

						;
					var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=saveNewCRMNote'	+ parameters, null, null, save_new_crm_note_callback);
				},
				'Cancel': function() {$(this).dialog('close');}
			}
		}).css("font-size", "smaller");
	}
	else
	{
		alert(client.responseTexts);
	}
}

function cv_sent_application_id_onchange(application_id)
{
	if(application_id.value == '')
	{
		$('#frmAddInvite #location').val('');
		return;
	}

	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=getLocationFromID&application_id=' + application_id.value, null, null, getLocationFromApplicationIDCallback);
}
function getLocationFromApplicationIDCallback(client)
{
	if(client != null)
	{
		console.log(client.responseText);
		$('#frmAddInvite #location').val(client.responseText);
	}
}

function addCandidateInviteToEmployerContact(invite_id)
{
	if(window.phpViewOnly == '1')
	{
		return custom_alert_OK_only('Operation not allowed - You don\'t have sufficient privileges or the candidate has been converted into Sunesis learner.');
	}

	var client = ajaxRequest('do.php?_action=rec_read_candidate&subaction=saveCandidateInviteToEmployerContact&invite_id=' + invite_id+'&selected_contact=' + $('#contacts'+invite_id).val());
	if(client != null)
	{
		alert('Candidate invite has been added into the employer contact.');
		window.location.href = 'do.php?_action=rec_read_candidate&id=' + window.phpCandidateId + '&selected_tab=tab5'
	}
	else
	{
		alert(client.responseText);
	}
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