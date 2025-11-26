
// jQuery initialisation

$(function(){
/*
	// Select the first checkbox in the group list,
	// if it is the only checkbox in the list
	if($('#groupSelector input:checkbox').length == 1)
	{
		$('#groupSelector input:checkbox').attr('checked', true);
	}
*/
	$('input[name=surname]').keyup(surname_keyup);

	$('#dialogAddNewAttendee').dialog({
		modal: true,
		width: 800,
		height: 600,
		closeOnEscape: true,
		autoOpen: false,
		resizable: false,
		draggable: false,
		buttons: {
			'Add': function() {

				var myForm = document.forms["frm_new_attendee"];
				var postcode_value = myForm.elements["attendee_postcode"].value;
				var ni_value = myForm.elements["attendee_ni"].value;
				if(myForm.elements["attendee_id"].value == '')
				{
					if(validateForm(myForm) == false)
					{
						return false;
					}
					if( !postcode_value.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) )
					{
						alert("Incorrect format for Postcode");
						myForm.elements["attendee_postcode"].focus();
						return false;
					}
					if(ni_value != '')
					{
						if( !ni_value.match( /^[A-Za-z]{2}[0-9]{2}[0-9]{2}[0-9]{2}[A-Za-z]{1}$/ ) )
						{
							alert("Incorrect format of National Insurance Number 'LL######L'");
							myForm.elements["attendee_ni"].focus();
							return false;
						}
					}
				}
				var url = 'do.php?_action=ajax_add_new_lesson_attendee&'
					+ 'attendee_id=' + encodeURIComponent(myForm.elements['attendee_id'].value) + '&'
					+ 'attendee_firstnames=' + encodeURIComponent(myForm.elements['attendee_firstnames'].value) + '&'
					+ 'attendee_surname=' + encodeURIComponent(myForm.elements['attendee_surname'].value) + '&'
					+ 'attendee_dob=' + encodeURIComponent(myForm.elements['attendee_dob'].value) + '&'
					+ 'attendee_ni=' + encodeURIComponent(myForm.elements['attendee_ni'].value) + '&'
					+ 'attendee_postcode=' + encodeURIComponent(myForm.elements['attendee_postcode'].value) + '&'
					+ 'lesson_id=' + encodeURIComponent(myForm.elements['lesson_id'].value);
				//console.log(url);return;
				var req = ajaxRequest(url);
				alert(req.responseText);
				window.location.reload();
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});

});

function tt()
{
	var req = ajaxRequest('do.php?_action=ajax_add_new_lesson_attendee&lesson_id=1355');
	console.log(req.responseText);
	return;

}

/**
 * jQuery event handler
 */
function surname_keyup(e)
{
	filter_div_left();
}

/**
 * Hide learners that don't match the surname filter
 */
function filter_div_left()
{
	var learners = $('#divLeft > div');
	var surname = $('input[name=surname]').val().toLowerCase();

	for(var i = 0, len = learners.length; i < len; i++)
	{
		if(learners[i].valueSurname.toLowerCase().indexOf(surname) == 0)
		{
			learners.eq(i).show();
		}
		else
		{
			learners.eq(i).hide();
		}
	}
}

function requestLearners()
{
	var divLeft = document.getElementById('divLeft');
	var divRight = document.getElementById('divRight');
	var filter_provider = document.getElementById('filter_provider');

	var provider_id = filter_provider.value;

	// Clear left-hand list
	while(divLeft.hasChildNodes()){
		divLeft.removeChild(divLeft.firstChild);
	}

	// Display progress animation
	divLeft.style.backgroundImage='url(/images/wait30.gif)';

	// Set up asynchronous AJAX request for learners
	ajaxRequest("do.php?_action=add_new_lesson_learner&lesson_id="+window.lessonId
				+ "&ajax_action=get_learners&provider_id=" + escape(provider_id), null, null, requestLearnersCallback);

}

function requestLearnersCallback(client, error)
{
	var divLeft = document.getElementById('divLeft');
	var divRight = document.getElementById('divRight');
	var filter_provider = document.getElementById('filter_provider');
	var surname = $('input[name=surname]').val().toLowerCase();

	var provider_id = filter_provider.value;

	if(!error)
	{
		// Remove current children
		while(divLeft.hasChildNodes()){
			divLeft.removeChild(divLeft.firstChild);
		}

		var dom = client.responseXML;
		var doc = dom.documentElement;
		var div = null;

		var divLearner = null;

		var learners = doc.getElementsByTagName("learner");
		for(var i = 0, len = learners.length; i < len; i++)
		{
			divLearner = createLearnerDiv(learners[i]);
			if(surname && divLearner.valueSurname.toLowerCase().indexOf(surname) != 0)
			{
				divLearner.style.display = "none";
			}

			divLeft.appendChild(divLearner);
		}
	}

	divLeft.style.backgroundImage='';
}



function showHideExistingLearners()
{
	var divRight = document.getElementById('divRight');

	// Determine if we are adding or hiding enrolments
	if($('div#divRight div.enrolledLearner').length)
	{
		// Hiding
		$('div#divRight div.enrolledLearner').remove();
	}
	else
	{
		// Set up asynchronous AJAX request for learners
		ajaxRequest("do.php?_action=add_new_lesson_learner"
			+ "&ajax_action=get_enrolled_learners"
			+ "&lesson_id=" + window.lessonId
			+ "&attendance_module_id=" + window.attendanceModuleId, null, null, showHideExistingLearnersCallback);
	}
}


function showHideExistingLearnersCallback(client, error)
{
	var divRight = document.getElementById('divRight');

	if(!error)
	{
		var dom = client.responseXML;
		var doc = dom.documentElement;

		var learners = doc.getElementsByTagName("learner");
		for(var i = 0, len = learners.length; i < len; i++)
		{
			addLearner(createLearnerDiv(learners[i]));
		}
	}
}

function filter_provider_onchange(element)
{
	var provider = $('select[name=filter_provider]');

	$('#divLeft').empty();

	requestLearners();
}

function filter_sort_onchange(element)
{
	sortLearners();
}

/**
 * Creates a <DIV> from a <learner> XML tag (DOM element)
 */
function createLearnerDiv(domElement)
{
	var div = document.createElement("DIV");
	div.valueLearnerId = domElement.getAttribute("id");
	div.valueSurname = domElement.getAttribute("surname");
	div.valueFirstnames = domElement.getAttribute("firstnames");
	div.valueDob = domElement.getAttribute("dob");
	div.valueUln = domElement.getAttribute("uln");
	div.valueProvider = domElement.getAttribute("legal_name").substr(0, 42);
	div.valueStartDate = domElement.getAttribute("start_date").substr(0, 42);
	div.valueTargetDate = domElement.getAttribute("target_date").substr(0, 42);

	var html = '<b>' + div.valueSurname + ", " + div.valueFirstnames + '</b>'
		+ '<br/><div class="learnerDetails">' + div.valueProvider
		+ '<br/>dob=' + div.valueDob + '<br/>';
	if(div.valueUln)
	{
		html += 'uln=' + div.valueUln;
	}
	if(div.valueStartDate)
	{
		html += '<br/>(' + div.valueStartDate + ' to ' + div.valueTargetDate + ')';
	}

	html += '</div>';

	div.innerHTML = html;

	if(domElement.getAttribute("enrolled") == 1)
	{
		div.className = "enrolledLearner";
		div.onclick = function(){$('#dialogLearnerAlreadyEnrolled').dialog('open');};
		div.title = "This learner is already enroled";
	}
	else
	{
		div.className ="learner";
		div.onclick = function(){addLearner(this);};
		div.title = "Click to add to list of register learners";
	}

	return div;
}

/**
 * Sorts learners in the right-hand list. The strategy for sorting
 * is in the compareLearnerDivs() function.
 */
function sortLearners()
{
	var divRight = document.getElementById("divRight");

	var tempNodes = new Array(divRight.childNodes.length);
	for(var i = 0, len1 = divRight.childNodes.length; i < len1; i++)
	{
		tempNodes[i] = divRight.childNodes[i];
	}

	tempNodes.sort(compareLearnerDivs);


	// Remove current children
	while(divRight.hasChildNodes()){
		divRight.removeChild(divRight.firstChild);
	}

	// Append children
	for(var i = 0, len2 = tempNodes.length; i < len2; i++)
	{
		divRight.appendChild(tempNodes[i]);
		if(tempNodes[i].className != "enrolledLearner")
		{
			tempNodes[i].onclick=function(){removeLearner(this);};
		}
	}

}

/**
 * Adds a learner to the right-hand list
 */
function addLearner(node)
{
	var divLeft = document.getElementById('divLeft');
	var divRight = document.getElementById('divRight');
	var headingRight = document.getElementById('headingRight');

	var rightDivs = divRight.getElementsByTagName("DIV");

	// Don't add the learner if they are already in the list
	for(var i = 0, len = rightDivs.length; i < len; i++)
	{
		if(rightDivs[i].valueLearnerId == node.valueLearnerId)
		{
			return;
		}
	}

	var div = node.cloneNode(true);
	if(node.className != "enrolledLearner")
	{
		div.onclick=function(){removeLearner(this);};
		div.title = "Click to remove from list of register learners";
	}
	div.valueLearnerId = node.valueLearnerId;
	div.valueSurname = node.valueSurname;
	div.valueFirstnames = node.valueFirstnames;
	div.valueDob = node.valueDob;
	div.valueUln = node.valueUln;
	div.valueProvider = node.valueProvider;

	// Flash course div
	$(node).add(div).css("background-color", "orange");
	window.setTimeout(function(){$(node).add(div).css("background-color", "");}, 100);

	if(divRight.hasChildNodes())
	{
		var max = divRight.childNodes.length;
		for(var i = 0; i < max; i++)
		{
			if(i + 1 < max)
			{
				if(compareLearnerDivs(div, divRight.childNodes[i]) < 0)
				{
					divRight.insertBefore(div, divRight.childNodes[i]);
					break;
				}
			}
			else
			{
				// Final node
				if(compareLearnerDivs(div, divRight.childNodes[i]) < 0)
				{
					divRight.insertBefore(div, divRight.childNodes[i]);
					break;
				}
				else
				{
					divRight.appendChild(div);
					break;
				}
			}
		}
	}
	else
	{
		divRight.appendChild(div);
	}

	headingRight.innerHTML = "Learners to add (" + $('#divRight > div.learner').length + ")";
	if($('#divRight > div.learner').length)
	{
		window.onbeforeunload = body_onbeforeunload;
	}
}

/**
 * Compares two learner <DIV> elements
 */
function compareLearnerDivs(a, b)
{
	var filter_sort = document.getElementById("filter_sort");
	var strA = null;
	var strB = null;

	if(filter_sort.value == 0)
	{
		strA = a.valueSurname + a.valueFirstnames + a.valueProvider;
		strB = b.valueSurname + b.valueFirstnames + b.valueProvider;
	}
	else
	{
		strA = a.valueProvider + a.valueSurname + a.valueFirstnames;
		strB = b.valueProvider + b.valueSurname + b.valueFirstnames;
	}

	if(strA > strB){
		return 1;
	} else if(strA < strB) {
		return -1;
	} else {
		return 0;
	}
}

/**
 * Removes a single learner from the right-hand list
 */
function removeLearner(node)
{
	var divLeft = document.getElementById('divLeft');
	var divRight = document.getElementById('divRight');
	var headingRight = document.getElementById('headingRight');

	divRight.removeChild(node);

	headingRight.innerHTML = "Learners to add (" + divRight.childNodes.length + ")";

	if(!divRight.hasChildNodes()){
		window.onbeforeunload = null;
	}
}

/**
 * Removes all learners from the right-hand list
 */
function removeAllLearners()
{
	var divRight = document.getElementById('divRight');
	var headingRight = document.getElementById('headingRight');

	if(divRight.hasChildNodes() && window.confirm("Clear enrolment list?"))
	{
		while(divRight.hasChildNodes()){
			divRight.removeChild(divRight.firstChild);
		}

		headingRight.innerHTML = "Learners to add (0)";
	}

	// Clear beforeUnload handler
	window.onbeforeunload = null;
}


function addLearners()
{
	var divRight = document.getElementById('divRight');

	if($('#divRight > div.learner').length == 0)
	{
		alert("No new learners have been selected for enrolment.");
		return;
	}
	else
	{
		if(!window.confirm("Add learners below?"))
		{
			return;
		}
	}


	var postData = "";

	// Build POST payload of learners to add
	for(var i = 0, len1 = divRight.childNodes.length; i < len1; i++)
	{
		postData += "learner_id[]=" + divRight.childNodes[i].valueLearnerId;
		if(i + 1 < len1)
		{
			postData += "&";
		}
	}

	// Progress animation
	divRight.style.backgroundImage='url(/images/wait30.gif)';

	// Call server
	ajaxRequest("do.php?_action=add_new_lesson_learner&ajax_action=enrol_learners&attendance_module_id=" + window.attendanceModuleId + "&lesson_id=" + window.lessonId,
		postData, null, addLearnersCallback);

}

function addLearnersCallback(requestObject, error)
{
	var divRight = document.getElementById('divRight');
	var headingRight = document.getElementById('headingRight');

	divRight.style.backgroundImage = "";

	if(!error && requestObject && parseInt(requestObject.responseText) == 1)
	{
		// Clear right-hand list
		while(divRight.hasChildNodes()){
			divRight.removeChild(divRight.firstChild);
		}
		headingRight.innerHTML = "Learners to add (0)";

		// Reload left list
		requestLearners();

		// Clear beforeUnload handler
		window.onbeforeunload = null;

		window.location.reload();
	}
	else
	{
		// Failure
		alert("Failed to add learners");
	}


}



function body_onload()
{
	requestLearners();
}

function body_onbeforeunload()
{
	return "The learners in the right-hand list have not been added.";
}
