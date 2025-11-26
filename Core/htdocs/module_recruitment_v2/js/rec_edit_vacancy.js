$(document).ready(function(){
	$('#total_positions,#max_submissions,#salary_from,#salary_to').bind("cut copy paste",function(e) {
		e.preventDefault();
	});

	tinymce.init({
		selector: "textarea",
		theme: "modern",
		oninit : "setPlainText",
		menubar : false,
		plugins : "paste"/*,
		force_p_newlines : false,
		force_br_newlines : true,
		convert_newlines_to_brs : false,
		remove_linebreaks : true*/
	});
});

function save()
{
	tinymce.triggerSave();
	var shortDescriptionText = $('textarea[name="short_description"]').val();
	var shortDescriptionTextWithoutHTML = $.trim(shortDescriptionText.replace(/(<([^>]+)>)/ig, "")).length;
	var shortDescriptionTextWithHTML = shortDescriptionText.length;

	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}

	var closing_date = stringToDate($('#input_closing_date').val());
	closing_date.setHours(0,0,0,0);
	var interview_from_date = stringToDate($('#input_interview_from_date').val());
	interview_from_date.setHours(0,0,0,0);
	var possible_start_date = stringToDate($('#input_possible_start_date').val());
	possible_start_date.setHours(0,0,0,0);
	var today_date = new Date();
	closing_date.setHours(0,0,0,0);

	if(window.phpVacancyID == '')
	{
		if(possible_start_date <= today_date)
		{
			alert('Possible start date ' + formatDateGB(possible_start_date) + ' should be after today\'s date');
			return;
		}
		if(interview_from_date <= today_date)
		{
			alert('Interview from date ' + formatDateGB(interview_from_date) + ' should be after today\'s date');
			return;
		}
		if(closing_date <= today_date)
		{
			alert('Closing date ' + formatDateGB(closing_date) + ' should be after today\'s date');
			return;
		}
	}

	if(shortDescriptionTextWithoutHTML > 255)
	{
		alert('Data too long for Short Description. The maximum limit is 255 characters.');
		$('textarea[name="short_description"]').focus();
		return false;
	}

//return console.log($('#short_description').val());

	myForm.submit();
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

	// To check if it goes beyond 100
	//if(parseInt(myfield.value+keychar)<0 || parseInt(myfield.value+keychar)>100)
	//	return false;

	// control keys
	if ((key==null) || (key==0) || (key==8) ||
		(key==9) || (key==13) || (key==27) )
		return true;

	// numbers
	else if ((("0123456789.").indexOf(keychar) > -1))
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

function setPlainText() {
	var ed = tinyMCE.get('elm1');

	ed.pasteAsPlainText = true;

	//adding handlers crossbrowser
	if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
		ed.onKeyDown.add(function (ed, e) {
			if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
				ed.pasteAsPlainText = true;
		});
	} else {
		ed.onPaste.addToTop(function (ed, e) {
			ed.pasteAsPlainText = true;
		});
	}
}

function provider_id_onchange(provider, event)
{
	return;
	var f = provider.form;

	var provider_locations = document.getElementById('provider_location');

	if(provider.value != '')
	{
		provider.disabled = true;
		provider_locations.disabled = true;
		ajaxPopulateSelect(provider_locations, 'do.php?_action=rec_edit_vacancy&subaction=load_organisation_locations&organisation=' + provider.value);
		provider_locations.disabled = false;
		provider.disabled =false;
	}
	else
	{
		emptySelectElement(provider_locations);
	}
}

function yes_no_apprenticeship_onchange(ele)
{
	if(ele.value == '1')
	{
		$('#provider_id').attr('class', 'compulsory validate[required]');
		$('#provider_location').attr('class', 'compulsory validate[required]');
	}
	else
	{
		$('#provider_id').attr('class', 'optional');
		$('#provider_location').attr('class', 'optional');
		$('#provider_id').val('');
		$('#provider_location').val('');
	}
}

function template_id_onchange(ele)
{
	var client = ajaxRequest('do.php?_action=rec_edit_vacancy&subaction=getVacancyTemplateFromID&vacancy_id=' + encodeURIComponent(ele.value), null, null, getVacancyTemplateFromIDCallback);
}

function getVacancyTemplateFromIDCallback(client)
{
	if(client.responseText != '')
	{
		var details = JSON.parse(client.responseText, true);
		$('#no_of_positions').val(details.no_of_positions);
		$('#vacancy_title').val(details.vacancy_title);
		$('#wage').val(details.wage);
		$('#wage_type').val(details.wage_type);
		$('#wage_text').val(details.wage_text);
		$('#working_week').val(details.working_week);
		$('#suppl_q_1').val(details.suppl_q_1);
		$('#suppl_q_2').val(details.suppl_q_2);
		$('#app_framework').val(details.app_framework);
		tinymce.get('short_description').getBody().innerHTML = details.short_description;
		tinymce.get('full_description').getBody().innerHTML = details.full_description;
		tinymce.get('personal_qualities').getBody().innerHTML = details.personal_qualities;
		tinymce.get('qualifications_required').getBody().innerHTML = details.qualifications_required;
		tinymce.get('skills_required').getBody().innerHTML = details.skills_required;
		tinymce.get('future_prospects').getBody().innerHTML = details.future_prospects;
		tinymce.get('other_info').getBody().innerHTML = details.other_info;
	}
}

function selectAllOptions(ele, grid_id)
{
	var grid = document.getElementById(grid_id);
	var grid_inputs = grid.getElementsByTagName('INPUT');
	for(var i = 0; i < grid_inputs.length; i++)
	{
		if(ele.checked)
			grid_inputs[i].checked = true;
		else
			grid_inputs[i].checked = false;
	}
}