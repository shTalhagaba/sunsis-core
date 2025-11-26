$(document).ready(function() {
  // set the default layout up
  $(".formentry").hide();
  $("#registration_1").show();
  $(".next").click(function() {
	var form_display = "#"+$(this).attr('id');
  	var status_display = "#"+$(this).attr('id');  
  	var id_for_display = $(this).attr('id');
  	form_display = form_display.replace(/proceed/,"registration");
  	status_display = status_display.replace(/proceed/,"status");

	// get the name of the div we are in
  	id_for_display = id_for_display.replace(/proceed_/, "");
  	id_for_display--;
  	id_for_display = "#registration_"+id_for_display;

	// do the input validation on a tab by tab level
	var ignore_list = Array();	
	
	// get all the the items in the recruitment form
	$('form[name=recruitmentForm] input, textarea').each( function() {
		ignore_list.push($(this).attr('name'));
	});

	// if we have a div id we are only checking part of the form
	$(id_for_display+' :input').each( function() {
		var keep_item = $.inArray($(this).attr('name'), ignore_list);
		if ( keep_item != -1 ) {
			ignore_list.splice(keep_item, 1);
		}
	});

  	if ( validateForm( document.forms['recruitmentForm'], ignore_list ) ) { 
  	    $(".formentry").hide();	  
  		$("#status li").removeClass('active');
    	$(form_display).slideToggle(500);
    	$(status_display).toggleClass('active');
  	}
  });
  
  $(".previous").click(function() {
    $(".formentry").hide();
  	var form_display = "#"+$(this).attr('id');
  	var status_display = "#"+$(this).attr('id');
  	form_display = form_display.replace(/bproceed/,"registration");
  	status_display = status_display.replace(/bproceed/,"status");
  	$("#status li").removeClass('active');
    $(form_display).slideToggle(500);
    $(status_display).toggleClass('active');
  });
});


function save()
{
	if(document.getElementById('agree_terms').checked == false)
	{
		alert("please agree to the terms and conditions.");
		return false;
	}
	var myForm = document.forms['recruitmentForm'];

	if( !validateForm(myForm) ) {
		return false;
	}

	myForm.enctype="multipart/form-data";

	myForm.submit();
}

function body_onload()
{
	if(window.self != window.top) {
		window.top.location.href = window.location.href;
	}
	var myForm = document.forms['recruitmentForm'];
	var warnings = document.getElementById('divWarnings');
	var isFirefox = window.navigator.userAgent.indexOf('Firefox') > -1;
	
	myForm.elements['screen_width'].value = window.screen.width;
	myForm.elements['screen_height'].value = window.screen.height;
	myForm.elements['color_depth'].value = window.screen.colorDepth;
	myForm.elements['flash'].value = getFlashVersion();
}


/**
 * Requires the Adobe Flash Detection script
 */
function getFlashVersion() {
	versionStr = GetSwfVer();
	
	if (versionStr == -1 )
	{
		versionStr = '';
	} 
	else if (versionStr != 0) 
	{
		if(isIE && isWin && !isOpera) 
		{
			// Given "WIN 2,0,0,11"
			tokens = versionStr.split(" ");
			versionStr = tokens[1].replace(/,/g,'.');
		}
	}
	
	return versionStr;
}

function borough_onchange(id, event)
{
	var url = '/do.php?_action=ajax_get_county&id=' + id.value;
	var c = ajaxRequest(url);
	if( c != null ) {
		document.getElementById("county").value = c.responseText
    }
}

function employment_status_onchange(id, event) {
	if( id.value >= 3 ) {
		document.getElementById('last_time_worked').disabled = '';
		document.getElementById('hours_per_week').disabled = 'disabled';	
	}
	else {
		document.getElementById('last_time_worked').disabled = 'disabled';
		document.getElementById('hours_per_week').disabled = '';		
	}
}

/* relmes: function for the dynamic addition of qualifications 
 *   expects a table id to which the row is to be appended.
 */
function newqual(tableId) {
	if ( "" == tableId ) {
        return false;
	}
   	var $tr = $("#"+tableId+' tr:last').clone(true);
   	// clear any values already in the last row
   	$tr.find('input').val('');
   	$tr.find('select').each(function () {
   		$(this).find('option:first').attr('selected','selected');
	});
   	// insert into the table.
   	$tr.insertAfter($("#"+tableId+' tr:last'));
    return true;
}

/*
 * relmes: function for the dynamic addition of qualification form entry elements
 */
function newQualInput ( input_type, input_size, input_length, input_name, input_value ) {
    var qualInput = document.createElement("input");
    qualInput.type= input_type;
    qualInput.name = input_name;
    qualInput.size = input_size;
    qualInput.maxlength = input_length;
    if( input_value ) {
      qualInput.value = input_value;
    }
    return qualInput;   
}

