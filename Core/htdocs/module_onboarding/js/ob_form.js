$(function(){

	$('#LLDD').change(function() {
		if($(this).val() == 'Y')
			$('#divLLDDCat').show();
		else
			$('#divLLDDCat').hide();
	});

	if($('#LLDD').val() == 'Y')
	{
		$('#divLLDDCat').show();
	}

	$('.clsICheck').each(function(){
		var self = $(this),
			label = self.next(),
			label_text = label.text();

		label.remove();
		self.iCheck({
			checkboxClass: 'icheckbox_line-orange',
			insert: '<div class="icheck_line-icon"></div>' + label_text
		});
	});

	$('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});

	/*$('input[class=radioICheck]').on('ifChecked', function(){
		$('#span_contract_end_date').hide();
		$('#span_avg_weekly_hours').hide();
		if(this.value == "FT")
			$('#span_contract_end_date').show();
		if(this.value == "ZH")
			$('#span_avg_weekly_hours').show();
	});*/

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

	var form = $("#frmOnBoarding").show();

	form.steps({
		headerTag:"h3",
		bodyTag:"step",
		transitionEffect:"slideLeft",
		stepsOrientation:"vertical",
		// startIndex: 6,
		onStepChanging:function (event, currentIndex, newIndex) {
			// Always allow previous action even if the current form is not valid!
			$('.loader').show();
			if (currentIndex > newIndex) {//back
				$('.loader').hide();
				return true;
			}
			if(currentIndex == 0) {
				var v = 0;
				$("input[name='disclaimer[]']").each( function () {
					if( this.checked && (this.value >= 4 || this.value == 2) )
					{
						v++;
					}
				});
				if(v != 4)
				{
					custom_alert_OK_only('Please tick the consent options to continue.', 'Alert');
					$('.loader').hide();
					return false;
				}
			}
			if(currentIndex == 1) {

				selected_lldd = [];
				if($('#LLDD').val() == 'Y')
				{
					var v = 0;
					$("input[name='llddcat[]']").each( function () {
						if(this.checked)
						{
							v++;
							selected_lldd.push(this.value);
						}
					});
					if(v == 0)
					{
						custom_alert_OK_only('Please select at least one option from applicable LLDD categories.', 'Alert');
						$('.loader').hide();
						return false;
					}
					if( $.inArray($('input[name="primary_lldd"]:checked').val(), selected_lldd) < 0)
					{
						custom_alert_OK_only('Please select Primary LLDD from your chosen LLDD categories.', 'Alert');
						$('.loader').hide();
						return false;
					}
				}
				if($('input[name="HHS[]"]:checked').length == 0)
				{
					custom_alert_OK_only('Please select your household situation', 'Alert');
					$('.loader').hide();
					return false;
				}
			}
			if(currentIndex == 2) {
				if(!$('input[name=pa_agree]').is(':checked'))
				{
					custom_alert_OK_only('Please tick the consent option to continue.', 'Alert');
					$('.loader').hide();
					return false;
				}
			}
			if(currentIndex == 4) {
				if(!$('input[name=ea_consent]').is(':checked'))
				{
					custom_alert_OK_only('Please tick the consent option to continue.', 'Alert');
					$('.loader').hide();
					return false;
				}
			}
			if(currentIndex == 5) {
				if(!$('input[name=aa_consent]').is(':checked'))
				{
					custom_alert_OK_only('Please tick the consent option to continue.', 'Alert');
					$('.loader').hide();
					return false;
				}
			}
			if(newIndex == 4) {

				copyInfoForILP();

			}
			var validForm = form.valid();
			if(validForm)
			{
				if (currentIndex < newIndex)
				{//forward
					form[0].elements['is_finished'].value = 'N';
					saveForm();
				}
				return true;
			}
			else
			{
				$('.loader').hide();
				return false;
			}
		},
		onStepChanged:function (event, currentIndex, priorIndex) {
			$('.loader').hide();
			//window.scrollTo(0, 0);
			return true;
		},
		onFinishing:function (event, currentIndex) {

			if($("#learner_ob_signature").val() == '')
				return alert('Your signature is required to complete this form, please sign the form.');

			return form.valid();
		},
		onFinished:function (event, currentIndex) {
			form[0].elements['is_finished'].value = 'Y';
			form.submit();
		}
	}).validate({
		errorPlacement: function (error, element)
		{
			element.after(error);
		},
		rules: {
			learner_title: { required:true },
			firstnames: { required:true },
			surname: { required:true },
			dob: { required:true, dateUK:true },
			ethnicity: { required:true },
			gender: { required:true },
			home_address_line_1: { required:true },
			home_address_line_3: { required:true },
			home_postcode: { postcodeUK:true, required:true },
			ni: { required:true, niUK:true },
			home_email: { required:true, emailCheck:true },
			home_telephone: { phoneUK: true },
			previous_training_details: {
				required:function(element){
					return $("input[type=checkbox][name=previous_training]").is(':checked');
				}
			},
			new_skills_details: { required: true },
			LLDD: { required: true },
			gcse_english_date_completed: { required: true },
			gcse_maths_date_completed: { required: true },
			high_level: { required: true },
			gcse_english_grade_predicted: {
				required: function(element){
					return $("#gcse_english_grade_actual").val() == ''
				}
			},
			gcse_english_grade_actual: {
				required: function(element){
					return $("#gcse_english_grade_predicted").val() == ''
				}
			},
			gcse_maths_grade_predicted: {
				required: function(element){
					return $("#gcse_maths_grade_actual").val() == ''
				}
			},
			gcse_maths_grade_actual: {
				required: function(element){
					return $("#gcse_maths_grade_predicted").val() == ''
				}
			}

		}
	});

	jQuery.validator.addMethod("postcodeUK", function(value, element) {
		return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value) || value == 'EC1V 9EU';
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

	jQuery.validator.addMethod("filesize",function (value, element, param) {
			return this.optional(element) || (element.files[0].size <= param)
		}, 'Maximum file size is 1MB'
	);

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

function saveForm()
{
	$.ajax({
		method: "POST",
		url:'do.php?_action=save_onboarding',
		data: $('#frmOnBoarding').serialize()
	})
		.done(function( msg ) {
			//$('.loader').hide();
			//console.log(msg);
		})
		.fail(function (jqXHR, textStatus) {
			alert('Error: ' + jqXHR.status + '\r\n' + 'Message: ' + jqXHR.statusText);
		});


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
				$("#img_learner_ob_signature").attr('src',$('.sigboxselected').children('img')[0].src);
				$("#learner_ob_signature").val($('.sigboxselected').children('img')[0].src);
				$(this).dialog('close');
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});
});

var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
var sizes = Array(15,40,15,20,20,20,15,30);

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
		if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode === 32 || charCode === 39 || charCode === 45 || charCode === 8 || charCode === 46)
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
	if(window.phpLearnerSignature == '')
	{
		$('#signature_text').val('');
		$( "#panel_signature" ).data('panel', 'learner').dialog( "open");
		return;
	}
	$('#img_learner_ob_signature').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
	$('#learner_ob_signature').val(window.phpLearnerSignature);

	console.log(window.phpLearnerSignature);
	console.log($('#learner_ob_signature').val());
}

function SignatureSelected(sig)
{
	$('.sigboxselected').attr('class','sigbox');
	sig.className = "sigboxselected";
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

function copyInfoForILP()
{
	$('#ilp_learner_title').html($('#learner_title').val());
	$('#ilp_firstnames').html($('#firstnames').val());
	$('#ilp_surname').html($('#surname').val());
	$('#ilp_input_dob').html($('#input_dob').val());
	$('#ilp_gender').html($('#gender').val());
	$('#ilp_address').html($('#home_address_line_1').val() + ' ' + $('#home_address_line_2').val() + ' ' + $('#home_address_line_3').val() + ' ' + $('#home_address_line_4').val());
	$('#ilp_home_postcode').html($('#home_postcode').val());
	$('#ilp_home_email').html($('#home_email').val());
	$('#ilp_home_telephone').html($('#home_telephone').val());
	$('#ilp_home_mobile').html($('#home_mobile').val());
	$('#ilp_em_con_title').html($('#em_con_title').val());
	$('#ilp_em_con_name').html($('#em_con_name').val());
	$('#ilp_em_con_rel').html($('#em_con_rel').val());
	$('#ilp_em_con_tel').html($('#em_con_tel').val());
	$('#ilp_em_con_mob').html($('#em_con_mob').val());

	$('#ilp_gcse_english').html('<td>GCSE - English Language</td><td>'+$("#input_gcse_english_date_completed").val()+'</td><td>'+$("#gcse_english_grade_actual option:selected").text()+'</td><td>&nbsp;</td>');
	$('#ilp_gcse_maths').html('<td>GCSE - Maths</td><td>'+$("#input_gcse_maths_date_completed").val()+'</td><td>'+$("#gcse_maths_grade_actual option:selected").text()+'</td><td>&nbsp;</td>');
	for(var i = 1; i <= 7; i++)
		$('#ilp_pa'+i).html('<td>'+$("#level"+i+" option:selected").text()+' - '+$("#subject"+i).val()+'</td><td>'+$("#input_date_completed"+i).val()+'</td><td>'+$("#actual_grade"+i+" option:selected").text()+'</td><td>&nbsp;</td>');
}