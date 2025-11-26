$(function(){
	$('input[name=qualification_id]').keyup(qualification_id_keyup);

	requestQualsFromPreviousEpisode();
});

/**
 * jQuery event handler
 */
function qualification_id_keyup(e)
{
	filter_div_left();
}

/**
 * Hide Qualifications that don't match the surname filter
 */
function filter_div_left()
{
	var qualifications = $('#divLeft > div');
	var qualification_id = $('input[name=qualification_id]').val().toLowerCase();

	for(var i = 0, len = qualifications.length; i < len; i++)
	{
		if(qualifications[i].valueQualificationId.toLowerCase().indexOf(qualification_id) == 0)
		{
			qualifications.eq(i).show();
		}
		else
		{
			qualifications.eq(i).hide();
		}
	}
}

function provider_location_onchange(location, event)
{
	var f = location.form;

	var provider_courses = document.getElementById('provider_course');

	if(location.value != '')
	{
		location.disabled = true;
		provider_courses.disabled = true;
		ajaxPopulateSelect(provider_courses, 'do.php?_action=manage_enrolment&ajax_request=true&subaction=load_courses&provider_location=' + location.value);
		provider_courses.disabled = false;
		location.disabled =false;
	}
	else
	{
		emptySelectElement(provider_courses);
	}
}

function provider_course_onchange(element)
{
	var provider = $('select[name=provider_course]');

	var headingMiddle = document.getElementById('headingMiddle');
	var divMiddle = document.getElementById('divMiddle');

	if(divMiddle.hasChildNodes())
	{
		if(window.confirm("You can only select qualifications from one course, changing the course will clear your selection. Do you want to continue?"))
		{
			while(divMiddle.hasChildNodes()){
				divMiddle.removeChild(divMiddle.firstChild);
			}

			headingMiddle.innerHTML = "Qualifications to add (0)";
		}
		else
			return;
	}

	var course_groups = document.getElementById('course_groups');

	if(element.value != '')
	{
		element.disabled = true;
		course_groups.disabled = true;
		ajaxPopulateSelect(course_groups, 'do.php?_action=manage_enrolment&ajax_request=true&subaction=load_course_groups&course_id=' + element.value);
		course_groups.disabled = false;
		element.disabled =false;
	}
	else
	{
		emptySelectElement(course_groups);
	}

	requestQualifications();
}

function requestQualsFromPreviousEpisode()
{
	var divRight = document.getElementById('divRight');
	var divMiddle = document.getElementById('divMiddle');

	// Clear left-hand list
	while(divRight.hasChildNodes()){
		divRight.removeChild(divRight.firstChild);
	}

	// Display progress animation
	divRight.style.backgroundImage='url(/images/wait30.gif)';

	// Set up asynchronous AJAX request for qualifications
	ajaxRequest("do.php?_action=manage_enrolment&participant_id="+window.phpParticipantId
		+ "&ajax_request=true&subaction=get_open_quals_from_previous_episode", null, null, requestQualsFromPreviousEpisodeCallback);
}

function requestQualsFromPreviousEpisodeCallback(client, error)
{
	var divRight = document.getElementById('divRight');
	var divMiddle = document.getElementById('divMiddle');

	if(!error)
	{
		// Remove current children
		while(divRight.hasChildNodes()){
			divRight.removeChild(divRight.firstChild);
		}

		var dom = client.responseXML;
		var doc = dom.documentElement;
		var div = null;

		var divQualification = null;

		var qualifications = doc.getElementsByTagName("qualification");
		for(var i = 0, len = qualifications.length; i < len; i++)
		{
			divQualification = createQualificationDiv(qualifications[i]);
			divRight.appendChild(divQualification);
		}
	}

	divRight.style.backgroundImage='';
}

function requestQualifications()
{
	var divLeft = document.getElementById('divLeft');
	var divMiddle = document.getElementById('divMiddle');
	var provider_course = document.getElementById('provider_course');

	var course_id = provider_course.value;

	// Clear left-hand list
	while(divLeft.hasChildNodes()){
		divLeft.removeChild(divLeft.firstChild);
	}

	// Display progress animation
	divLeft.style.backgroundImage='url(/images/wait30.gif)';

	// Set up asynchronous AJAX request for qualifications
	ajaxRequest("do.php?_action=manage_enrolment&participant_id="+window.phpParticipantId
		+ "&ajax_request=true&subaction=get_qualifications&course_id=" + escape(course_id), null, null, requestQualificationsCallback);

}

function requestQualificationsCallback(client, error)
{
	var divLeft = document.getElementById('divLeft');
	var divMiddle = document.getElementById('divMiddle');
	var provider_course = document.getElementById('provider_course');
	var qualification_id = $('input[name=qualification_id]').val().toLowerCase();

	var course_id = provider_course.value;

	if(!error)
	{
		// Remove current children
		while(divLeft.hasChildNodes()){
			divLeft.removeChild(divLeft.firstChild);
		}

		var dom = client.responseXML;
		var doc = dom.documentElement;
		var div = null;

		var divQualification = null;

		var qualifications = doc.getElementsByTagName("qualification");
		for(var i = 0, len = qualifications.length; i < len; i++)
		{
			divQualification = createQualificationDiv(qualifications[i]);
			if(qualification_id && divQualification.valueQualificationId.toLowerCase().indexOf(qualification_id) != 0)
			{
				divQualification.style.display = "none";
			}

			divLeft.appendChild(divQualification);
		}
	}

	divLeft.style.backgroundImage='';
}

/**
 * Creates a <DIV> from a <Qualification> XML tag (DOM element)
 */
function createQualificationDiv(domElement)
{
	var div = document.createElement("DIV");
	div.valueQualificationId = domElement.getAttribute("qualification_id");
	div.valueQualificationTitle = domElement.getAttribute("internaltitle");

	var html = '<b>' + div.valueQualificationId + ", " + div.valueQualificationTitle + '</b>';

	html += '</div>';

	div.innerHTML = html;

	div.className ="qualification";
	div.onclick = function(){addQualification(this);};
	div.title = "Click to add to list of qualifications for the learner training record";

	return div;
}

/**
 * Adds a qualification to the right-hand list
 */
function addQualification(node)
{
	var divLeft = document.getElementById('divLeft');
	var divMiddle = document.getElementById('divMiddle');
	var headingMiddle = document.getElementById('headingMiddle');

	var rightDivs = divMiddle.getElementsByTagName("DIV");

	// Don't add the qualification if they are already in the list
	for(var i = 0, len = rightDivs.length; i < len; i++)
	{
		if(rightDivs[i].valueQualificationId == node.valueQualificationId)
		{
			return;
		}
	}

	var div = node.cloneNode(true);

	if(node.className != "addedQualification")
	{
		div.onclick=function(){removeQualification(this);};
		div.title = "Click to remove from list of selected qualifications";
	}

	div.valueQualificationId = node.valueQualificationId;
	div.valueQualificationTitle = node.valueQualificationTitle;

	// Flash course div
	$(node).add(div).css("background-color", "orange");
	window.setTimeout(function(){$(node).add(div).css("background-color", "");}, 100);

	if(divMiddle.hasChildNodes())
	{
		var max = divMiddle.childNodes.length;
		for(var i = 0; i < max; i++)
		{
			if(i + 1 < max)
			{
				if(compareQualificationDivs(div, divMiddle.childNodes[i]) < 0)
				{
					divMiddle.insertBefore(div, divMiddle.childNodes[i]);
					break;
				}
			}
			else
			{
				// Final node
				if(compareQualificationDivs(div, divMiddle.childNodes[i]) < 0)
				{
					divMiddle.insertBefore(div, divMiddle.childNodes[i]);
					break;
				}
				else
				{
					divMiddle.appendChild(div);
					break;
				}
			}
		}
	}
	else
	{
		divMiddle.appendChild(div);
	}

	headingMiddle.innerHTML = "Qualifications to add (" + $('#divMiddle > div.qualification').length + ")";
	if($('#divMiddle > div.qualification').length)
	{
		window.onbeforeunload = body_onbeforeunload;
	}
}

/**
 * Compares two Qualification <DIV> elements
 */
function compareQualificationDivs(a, b)
{
	var filter_sort = document.getElementById("filter_sort");
	var strA = null;
	var strB = null;

	if(filter_sort.value == 0)
	{
		strA = a.valueQualificationId + a.valueQualificationTitle;
		strB = b.valueQualificationId + b.valueQualificationTitle;
	}
	else
	{
		strA = a.valueQualificationTitle + a.valueQualificationId;
		strB = b.valueQualificationTitle + b.valueQualificationId;
	}

	if(strA > strB){
		return 1;
	} else if(strA < strB) {
		return -1;
	} else {
		return 0;
	}
}

function body_onbeforeunload()
{
	return "The learner/participant is not enrolled, do you want to continue?";
}

/**
 * Removes a single Qualification from the right-hand list
 */
function removeQualification(node)
{
	var divLeft = document.getElementById('divLeft');
	var divMiddle = document.getElementById('divMiddle');
	var headingMiddle = document.getElementById('headingMiddle');

	divMiddle.removeChild(node);

	headingMiddle.innerHTML = "Qualifications to add (" + divMiddle.childNodes.length + ")";

	if(!divMiddle.hasChildNodes()){
		window.onbeforeunload = null;
	}
}

/**
 * Removes all qualifications from the right-hand list
 */
function removeAllQualifications()
{
	var divMiddle = document.getElementById('divMiddle');
	var headingMiddle = document.getElementById('headingMiddle');

	if(divMiddle.hasChildNodes() && window.confirm("Clear selection list?"))
	{
		while(divMiddle.hasChildNodes()){
			divMiddle.removeChild(divMiddle.firstChild);
		}

		headingMiddle.innerHTML = "Qualifications to add (0)";
	}

	// Clear beforeUnload handler
	window.onbeforeunload = null;
}

function addQualifications()
{
	if($('#input_start_date').val() == '')
	{
		alert('Please enter the start date of the training record.');
		$('#input_start_date').focus();
		return;
	}
	if($('#input_planned_end_date').val() == '')
	{
		alert('Please enter the planned end date of the training record.');
		$('#input_planned_end_date').focus();
		return;
	}


	var divMiddle = document.getElementById('divMiddle');

	if($('#divMiddle > div.qualification').length == 0)
	{
		alert("No qualifications have been selected for enrolment.");
		return;
	}
	else
	{
		if(!window.confirm("Enrol learner with the selected qualifications below?"))
		{
			return;
		}
	}


	var postData = "";

	// Build POST payload of qualifications to add
	for(var i = 0, len1 = divMiddle.childNodes.length; i < len1; i++)
	{
		postData += "qualification_id[]=" + divMiddle.childNodes[i].valueQualificationId;
		if(i + 1 < len1)
		{
			postData += "&";
		}
	}

	// Progress animation
	divMiddle.style.backgroundImage='url(/images/wait30.gif)';


	// Call server
	ajaxRequest("do.php?_action=manage_enrolment&ajax_request=true" +
		"&subaction=enrol_learner" +
		"&participant_id=" + window.phpParticipantId +
		"&add_to_ilr=" + $('input[name=chk_add_to_ilr]').attr('checked') +
		"&start_date=" + $('#input_start_date').val() +
		"&planned_end_date=" + $('#input_planned_end_date').val() +
		"&provider_location=" + $('#provider_location').val() +
		"&course_id=" + $('#provider_course').val() +
		"&selected_contract=" + $('#selected_contract').val() +
		"&selected_assessor=" + $('#selected_assessor').val() +
		"&selected_college=" + $('#selected_college').val() +
		"&selected_tutor=" + $('#selected_tutor').val() +
		"&selected_group=" + $('#course_groups').val() +
		"&aims_to_populate_partner_ukprn=" + $('#aims_to_populate_partner_ukprn').val(),
		postData, null, addQualificationsCallback);

}

function addQualificationsCallback(requestObject, error)
{
	var divMiddle = document.getElementById('divMiddle');
	var headingMiddle = document.getElementById('headingMiddle');

	divMiddle.style.backgroundImage = "";

	if(!error && requestObject && parseInt(requestObject.responseText) == 1)
	{
		// Clear right-hand list
		while(divMiddle.hasChildNodes()){
			divMiddle.removeChild(divMiddle.firstChild);
		}
		headingMiddle.innerHTML = "Qualifications to add (0)";

		// Reload left list
		requestQualifications();

		// Clear beforeUnload handler
		window.onbeforeunload = null;

		if(window.phpIsParticipant == '1')
			window.location.href='do.php?_action=view_edit_participant&id=' + window.phpParticipantId + '&selected_tab=tab6';
		else
			window.location.href='do.php?_action=read_user&id=' + window.phpParticipantId + '&username=' + window.phpParticipantUsername;
	}
	else
	{
		// Failure
		alert("Failed to enrol participant");
	}



}

$(function(){
	$('#dialogIDHelp').dialog({
		modal: true,
		width: 600,
		height: 500,
		closeOnEscape: true,
		autoOpen: false,
		resizable: true,
		draggable: true,
		buttons: {
			'Continue': function() {
				var _list = '';
				var $boxes = $('input[name="chk[]"]:checked');
				for(var i = 0; i < $boxes.length; i++)
				{
					_list += $boxes[i].value + ',';
				}
				if(_list != '')
					_list = _list.slice(0,-1);
				$('#aims_to_populate_partner_ukprn').val(_list);
				addQualifications();
				$(this).dialog('close');
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}
	});
});