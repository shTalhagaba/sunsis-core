function saveForm()
{
	$.ajax({
		type:'POST',
		url:'do.php?_action=save_onboarding',
		data: $('#frmOnBoarding').serialize(),
		async: false,
		beforeSend: function(){
			//$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
		},
		success: function(data, textStatus, xhr) {
			//console.log(data);
		},
		error: function(data, textStatus, xhr){
			var myxml = data.responseText,
				xmlDoc = $.parseXML( myxml ),
				$xml = $( xmlDoc );
			$(data.responseXML).find('error').each(function(){
				//alert('Error: \n' + $(this).find('message').text());
				console.log('Error: ' + $(this).find('message').text());
				console.log('Error: ' + $(this).find('line').text());
				console.log('Error: ' + $(this).find('file').text());
			});
		}
	});
}

$(function(){

	$("#loading").dialog({
		autoOpen: false,
		width: 'auto',
		height: 'auto',
		modal: true,
		closeOnEscape: false,
		resizable: false,
		draggable: false,
		buttons: {}
	});

	var form = $("#frmOnBoarding").show();
	form.steps({
		headerTag:"h3",
		bodyTag:"step",
		transitionEffect:"slideLeft",
		stepsOrientation:"vertical",
		//startIndex: 2,
		onStepChanging:function (event, currentIndex, newIndex) {
			// Allways allow previous action even if the current form is not valid!
			if (currentIndex > newIndex) {//back
				//console.log('back');
				return true;
			}
			if(currentIndex == 1) {

				if($("input[name='ilp_signature']").not(':checked').length){
					custom_alert_OK_only('Please tick the box to confirm you agree to the Individual Learner Plan.', 'Alert');
					$("input[name='ilp_signature']").focus();
					return false;
				}

			}
			if(currentIndex == 2) {

				if($("input[name='app_agreement']").not(':checked').length){
					custom_alert_OK_only('Please tick the box to confirm you agree to the Apprenticeship Agreement.', 'Alert');
					return false;
				}

			}
			var validForm = form.valid();
			if(validForm)
			{
				if (currentIndex < newIndex)
				{//forward
					//$('#frmOnBoarding input[name=is_finished]').val('N');
					//form[0].elements['is_finished'].value = 'N';
					//saveForm();
				}
				return true;
			}
			else
			{
				return false;
			}
		},
		onStepChanged:function (event, currentIndex, priorIndex) {
			//window.scrollTo(0, 0);
			return true;
		},
		onFinishing:function (event, currentIndex) {

			if($("#learner_signature").val() == '')
				return alert('Your signature is required to complete this form, please sign the form.');

			return form.valid();
		},
		onFinished:function (event, currentIndex) {
//			$('#contentForm').hide();
//			$('#completionPage').show();
			form[0].elements['is_finished'].value = 'Y';
			form.submit();
		}
	}).validate({
			errorPlacement: function (error, element)
			{
				element.after(error);
			},
			rules: {
				skills_trade_occ: {
					maxlength:449
				},
			}
		});

	jQuery.validator.addMethod("postcodeUK", function(value, element) {
		return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value);
	}, "Please specify a valid Postcode");

	jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
		return this.optional(element) || phone_number.length > 9 &&
			phone_number.match(/^(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}$/);
	}, 'Please specify a valid phone number');

	jQuery.validator.addMethod("niUK", function(value, element) {
		return this.optional(element) || /^\s*[a-zA-Z]{2}(?:\s*\d\s*){6}[a-zA-Z]?\s*$/i.test(value);
	}, "Please specify a valid National Insurance Number");

	jQuery.validator.addMethod("emailCheck", function(value, element) {
		return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(value);
	}, "Please specify a valid Email address");

	jQuery.validator.addMethod("dateUK",function(value, element) {
			return value == ''?true:value.match(/^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/);
		}, "Please enter a date in the format dd/mm/yyyy."
	);

	$('.datecontrol').datepicker({
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

	$(window).scroll(function() {
		if ($(".navbar").offset().top > 5) {
			$('.headerlogo').attr('src', window.phpHeaderLogo2); //change src
			$("#mainNav").fadeIn("slow", function(){
				$("#mainNav").css("opacity", "0.95");
			});
		} else {
			$('.headerlogo').attr('src', window.phpHeaderLogo1);
			$("#mainNav").fadeIn("slow", function(){
				$("#mainNav").css("opacity", "");
			});
		}
	});

	$('.clsICheck').each(function(){
		var self = $(this),
			label = self.next(),
			label_text = label.text();

		label.remove();
		self.iCheck({
			checkboxClass: 'icheckbox_line-blue',
			/*radioClass: 'iradio_line',*/
			insert: '<div class="icheck_line-icon"></div>' + label_text
		});

	});

	$('input[type=radio]').iCheck({
		radioClass: 'iradio_square-red'
	});

	$('a[href="#next"]').html($('a[href="#next"]').html()+' &nbsp; <i class="fa fa-forward"></i>');
	$('a[href="#previous"]').html('<i class="fa fa-backward"></i> &nbsp; ' + $('a[href="#previous"]').html());

	loadDefaultSignatures();

});

var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
var sizes = Array(30,40,15,30,30,30,25,30);

function refreshSignature()
{
	for(var i = 1; i <= 8; i++)
		$("#img"+i).attr('src', 'images/loading.gif');

	for(var i = 0; i <= 7; i++)
		$("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
}

function loadDefaultSignatures()
{
	for(var i = 1; i <= 8; i++)
		$("#img"+i).attr('src', 'images/loading.gif');

	for(var i = 0; i <= 7; i++)
		$("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
}

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

function getSignature()
{
	$( "#panel_signature" ).dialog( "open");
}

function SignatureSelected(sig)
{
	$('.sigboxselected').attr('class','sigbox');
	sig.className = "sigboxselected";
}

$(function() {
	$( "#panel_signature" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		width: "auto",
		height: 500,
		buttons: {
			'Add': function() {
				$("#img_learner_signature").attr('src',$('.sigboxselected').children('img')[0].src);
				$("#learner_signature").val($('.sigboxselected').children('img')[0].src);
				$(this).dialog('close');
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});
});

function custom_alert_OK_only(output_msg, title_msg)
{
	return alert(output_msg);
	if (!title_msg)
		title_msg = 'Alert';

	if (!output_msg)
		output_msg = 'No Message to Display.';

	$("<div></div>").html(output_msg).dialog({
		title: title_msg,
		resizable: false,
		modal: true,
		buttons: {"OK": function(){
			$( this ).dialog( "close" );
		}
		}
	});
}