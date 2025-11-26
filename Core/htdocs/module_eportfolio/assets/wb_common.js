if(!window.jQuery && window.console){
	window.console.log("Common.js requires jQuery. Please load jquery.min.js before common.js.");
}

var nowMs = new Date().getTime();
var lastKeepAlivePingMs = nowMs;
var keepAliveDelayMs = 5 * 60 * 1000; // 5 minutes
$(window.document).keydown(function(event) {
	nowMs = new Date().getTime();
	if(nowMs - lastKeepAlivePingMs > keepAliveDelayMs) {
		if (window.console) {
			window.console.log("Calling session keep alive");
		}
		var client = ajaxRequest("/keep-alive.php");
		if (client && client.responseText === "0") {
			alert("Inactivity timeout exceeded. You have been logged out of Sunesis.");
		}
		lastKeepAlivePingMs = Date.now();
	}
});

function ajaxRequest(uri, postData, contentType, callback)
{
	var req = ajaxBuildRequestObject();

	if(postData)
	{
		req.open('POST', expandURI(uri), callback != null);
		req.setRequestHeader("Content-Type", contentType != null?contentType:"application/x-www-form-urlencoded; charset=UTF-8");
	}
	else
	{
		req.open('GET', expandURI(uri), callback != null);
	}

	req.setRequestHeader("Accept", "application/ajax"); // marker for server code (does not work with Opera)
	req.setRequestHeader("X-Requested-With", "XMLHttpRequest"); // marker for server code (does not work with IE)

	if(callback)
	{
		req.onreadystatechange = function(e){
			if(req.readyState == 4){
				if(ajaxErrorHandler(req)){
					callback(req, true);
				} else {
					callback(req, false);
				}
			}
		};
		req.send(postData);
	}
	else
	{
		// Post data and return the xhr object if
		// there are no errors
		req.send(postData);
		return ajaxErrorHandler(req) ? null : req;
	}
}

function expandURI(uri)
{
	var url = '';

	if(uri.match(/^http/))
	{
		// do nothing
		url = uri;
	}
	else if(uri.charAt(0) == '/')
	{
		// Add prefix
		url = location.protocol + "//" + location.host + uri;
	}
	else
	{
		// Add prefix and path
		var i = location.pathname.lastIndexOf('/');
		var path = '';
		if(i > 0)
		{
			path = location.pathname.substring(0, i);
		}
		else
		{
			path = location.pathname;
		}

		url = location.protocol + "//" + location.host + path + "/" + uri;
	}

	return url.replace("/do.php/","/");
}

function ajaxBuildRequestObject()
{
	var req = null;

	// branch for native XMLHttpRequest object (Opera, Mozilla, IE 7+)
	if(window.XMLHttpRequest)
	{
		try
		{
			req = new XMLHttpRequest();
		}
		catch(e)
		{
			req = null;
		}
	}
	else
	{
		// branch for IE/Windows ActiveX version
		if(window.ActiveXObject)
		{
			try
			{
				req = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e)
			{
				try
				{
					req = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e)
				{
					req = null;
				}
			}
		}
	}

	return req;
}

function ajaxIsError(req)
{
	return req.status != 200
		|| req.getResponseHeader("X-Perspective") == 'Application error'
		|| req.responseText.indexOf('<php:error xmlns:php="http://php.net">') > -1
		|| req.responseText.indexOf('<br />') === 0;
}

function ajaxErrorHandler(req, textStatus, errorThrown)
{
	// Check for errors
	if(!ajaxIsError(req)){
		return false;
	}

	if(req.getResponseHeader("X-Perspective") == 'Application error' && req.responseText.indexOf('<'+'?xml') > -1)
	{
		// FRAMEWORK GENERATED ERROR
		//var doc = req.responseXML.documentElement;
		var $xml = $(req.responseXML);

		var file = $xml.find('error file').text();
		var line = $xml.find('error line').text();
		var message = $xml.find('error message').text();
		var extra_info = $xml.find('error extra_info').text();

		if(file)
		{
			// Private info for developers
			if (window.console) {
				window.console.log(file + '(' + line + '): ' + message);
				if (extra_info) {
					window.console.log(extra_info);
				}
			}

			// Public message
			alert(message);
		}
		else
		{
			// Not a framework error -- just display it
			alert(req.responseText);
		}
	}
	else
	{
		// Look for PHP generated errors. For this code to work, the following
		// entries need to be made in the Apache VirtualHost configuration
		// php_value error_prepend_string '<php:error
		// xmlns:php="http://php.net">'
		// php_value error_append_string '</php:error>'
		var text = req.responseText;
		var openingTag = '<php:error xmlns:php="http://php.net">';
		var start = text.indexOf(openingTag);
		if(start > -1)
		{
			// extract error
			var end = text.indexOf('</php:error>');
			text = text.substring(start + openingTag.length, end);

			// remove HTML tags
			text = text.replace(/<[^>]+>/g, '');
		}
		else
		{
			// Check for the standard <br /> opening tag for PHP errors
			if(text.indexOf('<br />') == 0)
			{
				// remove HTML tags
				text = text.replace(/<[^>]+>/g, '');
			}
		}

		// Display whatever is left
		if(req.status == 200)
		{
			alert(text);
		}
		else
		{
			alert(req.status + ' ' + req.statusText + ': ' + text);
		}
	}

	return true;
}

toastr.options = {
	"closeButton": true,
	"progressBar": true,
	"preventDuplicates": true,
	"positionClass": "toast-bottom-right",
	"onclick": null,
	"showDuration": "400",
	"hideDuration": "1000",
	"timeOut": "7000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
};

$('.datepicker').datepicker({
	dateFormat: 'dd/mm/yy',
	yearRange: 'c-50:c+50',
	changeMonth: false,
	changeYear: true,
	constrainInput: true,
	buttonImage: "/images/calendar-icon.gif",
	buttonImageOnly: true,
	buttonText: "Show calendar",
	showOn: "both",
	showAnim: "fadeIn"
});

function onlyAlphabets(e, t)
{
	try {
		if (window.event) {
			var charCode = window.event.keyCode;
		}
		else if (e) {
			var charCode = e.which;
		}
		else { return true; }
		if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
			return true;
		else
			return false;
	}
	catch (err) {
		alert(err.Description);
	}
}

function body_onbeforeunload()
{
	return "Please make sure you have saved your workbook, do you want to continue?";
}

function bookmarkPage()
{
	var wizard = $("#wizard");
	var currentIndex = wizard.steps("getCurrentIndex");
	var currentStep = wizard.find(".steps li").eq(currentIndex);
	if(currentStep.hasClass("bookmark"))
		currentStep.removeClass("bookmark");
	else
		currentStep.addClass("bookmark");

	$.ajax({
		type:'GET',
		url:'do.php?_action=wb_ajax&subaction=bookmark&page='+currentIndex+'&wb_id='+window.phpWorkbookID
	});
}

function showAssessorFeedback(section)
{
	$.ajax({
		type:'POST',
		url:'do.php?_action=wb_ajax&subaction=assessor_feedback&section='+section+'&wb_id='+window.phpWorkbookID,
		success: function(data, textStatus, xhr) {
			$("<div></div>").html('<span class="small">' + data + '</span>').dialog({
				title: "Assessor Feedback",
				resizable: false,
				modal: true,
				width: 'auto',
				maxWidth: 550,
				height: 'auto',
				maxHeight: 500,
				buttons: {
					'Close': function() {$(this).dialog('close');}
				}
			}).css("background", "#FFF");
		}
	});
}

function showSectionHistory(section)
{
	$.ajax({
		type:'POST',
		url:'do.php?_action=wb_ajax&subaction=section_history&section='+section+'&wb_id='+window.phpWorkbookID,
		success: function(data, textStatus, xhr) {
			$("<div></div>").html('<span class="small">' + data + '</span>').dialog({
				title: "Section Change History",
				resizable: false,
				modal: true,
				width: 'auto',
				maxWidth: 550,
				height: 'auto',
				maxHeight: 500,
				buttons: {
					'Close': function() {$(this).dialog('close');}
				}
			}).css("background", "#FFF");
		},
		error: function(data, textStatus, xhr){
		}
	});
}


$(function() {

	// load bookmarks
	var wb_bookmarks = window.phpBookmarks;
	if(wb_bookmarks != '')
	{
		var wizard = $("#wizard");
		wb_bookmarks = wb_bookmarks.split(',');
		for(var i = 0; i < wb_bookmarks.length; i++)
		{
			var currentStep = wizard.find(".steps li").eq(wb_bookmarks[i]);
			currentStep.addClass("bookmark");
		}
	}

	// load steps with questions
	var wb_steps_with_questions = window.phpStepsWithQuestions;
	if(wb_steps_with_questions != '')
	{
		var wizard = $("#wizard");
		wb_steps_with_questions = wb_steps_with_questions.split(',');
		for(var i = 0; i < wb_steps_with_questions.length; i++)
		{
			var currentStep = wizard.find(".steps li").eq(wb_steps_with_questions[i]);
			currentStep.addClass("step-with-question");
		}
	}

	//Disable cut copy paste
	$('body').bind('cut copy paste', function (e) {
		e.preventDefault();
	});

	//Disable mouse right click
	$("body").on("contextmenu",function(e){
		return false;
	});
});

function previewInputInformation()
{
	var html = '';

	$('select[name^=status_]').each(function(){
		var section = this.id.replace('status_', '');
		html += '<p><span class="text-bold">Section: </span>' + section + '</p>';
		if(this.value == 'A')
			html += '<p><span class="text-bold">Status:  </span>ACCEPTED</p>';
		else
			html += '<p><span class="text-bold">Status:  </span>NOT ACCEPTED</p>';
		html += '<p><span class="text-bold">Comments: </span><br>' + $('textarea[name="comments_'+section+'"]').val() + '</p><hr>';
	});

	$('#divPreview').html(html);
	$('#dialogPreview').dialog('open').css("background", "#FFF");
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


function confirmation(question)
{
	$('<div></div>').html(question).dialog({
		title:'Confirmation',
		resizable: false,
		modal:true,
		buttons:{
			"Yes":function () {
				$(this).dialog("close");
			},
			"No":function () {
				$(this).dialog("close");
			},
			"Save And Come Back Later":function () {
				$(this).dialog("close");
			}
		}
	});
}

function getSignature(user)
{
	if(user == 'learner')
	{
		$('#img_user_signature').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
		$('#user_signature').val(window.phpLearnerSignature);
		return;
	}
	if(user == 'assessor')
	{
		$('#img_user_signature').attr('src', 'do.php?_action=generate_image&'+window.phpAssessorSignature);
		$('#user_signature').val(window.phpAssessorSignature);
		return;
	}
	if(user == 'iv')
	{
		$('#img_iv_signature').attr('src', 'do.php?_action=generate_image&'+window.phpIVSignature);
		$('#iv_signature').val(window.phpIVSignature);
		return;
	}
}

var allowed = [36,8216,8217,8220,8221,188,190,192,8,229,9,10,13,32,33,34,35,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,125,126,163];

$(function(){

	$(document.forms[0]).on('keypress', function (e) {
	 var keyCode = e.which;
	 //$('#inaam').text(keyCode + ' code');
	 if (jQuery.inArray(keyCode, allowed) !== -1) {
	    return true;
	 }
	 else {
		alert('This character is not allowed.');
       	return false;
 	}
});

	$(document.forms[0]).on('textInput', function (e) {
		var keyCode = e.originalEvent.data.charCodeAt(0);
		//$('#inaam').text(keyCode + ' code');
		if (jQuery.inArray(keyCode, allowed) !== -1) {
			return true;
		}
		else {
			alert('This character is not allowed.');
			return false;
		}
	});

	if($.datepicker)
	{
		$('input.datepicker').datepicker().blur(datepicker_blur);

		// Add validation code (for when the field is not blank)
		$('input.datepicker').each(function(){
				if(!this.validate){
					this.validate = function(){
						if(this.value != "" && window.stringToDate(this.value) == null){
							alert("Invalid date " + this.value + ". Format: dd/mm/yyyy");
							$(this).focus();
							return false;
						}
						return true;
					};
				}
			}
		);
	}

});

function datepicker_blur(e)
{
	if(this.value != "" && (window.stringToDate(this.value) == null) ){
		alert("Invalid date format or invalid calendar date. Format: dd/mm/yyyy");
		this.value = "";
		return;
	}

	// Call old CLM event handlers
	if(window[this.name+"_onblur"])
	{
		window[this.name+"_onblur"](this);
	}
}

function stringToDate(strDate)
{
	var pattern_w3c = /^(\d\d\d\d)[-\/](\d{1,2})[-\/](\d{1,2})(?:\s(\d\d):(\d\d):(\d\d))?$/;
	var pattern_gb = /^(\d{1,2})[-\/](\d{1,2})[-\/](\d\d\d\d)(?:\s(\d\d):(\d\d):(\d\d))?$/;
	var day, month, year, hours, minutes, seconds;

	var matches = null;
	if(matches = pattern_w3c.exec(strDate))
	{
		year = matches[1];
		month = matches[2];
		day = matches[3];
		hours = (matches.length >= 4 && matches[4] != null && matches[4] != '') ? matches[4]:'0';
		minutes = (matches.length >= 5 && matches[5] != null && matches[5] != '') ? matches[5]:'0';
		seconds = (matches.length >= 6 && matches[6] != null && matches[6] != '') ? matches[6]:'0';
	}
	else if (matches = pattern_gb.exec(strDate))
	{
		year = matches[3];
		month = matches[2];
		day = matches[1];
		hours = (matches.length >= 4 && matches[4] != null && matches[4] != '') ? matches[4]:'0';
		minutes = (matches.length >= 5 && matches[5] != null && matches[5] != '') ? matches[5]:'0';
		seconds = (matches.length >= 6 && matches[6] != null && matches[6] != '') ? matches[6]:'0';
	}
	else
	{
		return null;
	}


	// Remove any zero pre-fixes
	if(month.length == 2 && month.charAt(0) == '0')
	{
		month = month.charAt(1);
	}
	if(day.length == 2 && day.charAt(0) == '0')
	{
		day = day.charAt(1);
	}
	if(hours.length == 2 && hours.charAt(0) == '0')
	{
		hours = hours.charAt(1);
	}
	if(minutes.length == 2 && minutes.charAt(0) == '0')
	{
		minutes = minutes.charAt(1);
	}
	if(seconds.length == 2 && seconds.charAt(0) == '0')
	{
		seconds = seconds.charAt(1);
	}

	// Convert strings to numbers
	year = parseInt(year);
	month = parseInt(month);
	day = parseInt(day);
	hours = parseInt(hours);
	minutes = parseInt(minutes);
	seconds = parseInt(seconds);

	// Create date object and check that the user has entered a valid calendar date
	// (NB months in JavaScript run from 0-11)
	var d = new Date(year, month - 1, day, hours, minutes, seconds);
	if( (d.getFullYear() != year) || ((d.getMonth() + 1) != month) || (d.getDate() != day)
		|| (d.getHours() != hours) || (d.getMinutes() != minutes) || (d.getSeconds() != seconds) )
	{
		// Invalid calendar date
		return null;
	}
	return d;
}