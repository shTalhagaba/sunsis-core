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
		//startIndex: 5,
		onStepChanging:function (event, currentIndex, newIndex) {
			// Allways allow previous action even if the current form is not valid!
			if (currentIndex > newIndex) {//back
				//console.log('back');
				return true;
			}
			if(currentIndex == 0) {
				var v = 0;
				$("input[name='disclaimer[]']").each( function () {
					if(this.checked)
						v++;
				});
				if(v != 3)
				{
					custom_alert_OK_only('Please tick the disclaimer options to continue.', 'Alert');
					return false;
				}
			}
			if(currentIndex == 1) {
				if($('#LLDD').val() == 'Y')
				{
					var v = 0;
					$("input[name='llddcat[]']").each( function () {
						if(this.checked)
							v++;
					});
					if(v == 0)
					{
						custom_alert_OK_only('Please select at least one option from applicable LLDD categories.', 'Alert');
						return false;
					}
				}
			}
			if(currentIndex == 4) {
				if($("input[name='chkESF']").not(':checked').length){
					custom_alert_OK_only('Please tick the box to confirm you agree to the ESF Declaration.', 'Alert');
					return false;
				}
			}
			if(currentIndex == 5) {
				var selected = $("input[type='radio'][name='EmploymentStatus']:checked");
				if(selected.length == 0){
					custom_alert_OK_only('Please select your employment status', 'Alert');
					return false;
				}
			}
			if(newIndex == 6) {

				copyInfoForILP();

			}
			if(currentIndex == 6) {

				if($("input[name='ilp_signature']").not(':checked').length){
					custom_alert_OK_only('Please tick the box to confirm you agree to the Individual Learner Plan.', 'Alert');
					$("input[name='ilp_signature']").focus();
					return false;
				}

			}
			if(currentIndex == 7) {

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
					form[0].elements['is_finished'].value = 'N';
					saveForm();
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
				learner_title: {
					required:true
				},
				firstnames: {
					required:true
				},
				surname: {
					required:true
				},
				dob: {
					required:true,
					dateUK:true
				},
				gcse_english_date_completed: {
					dateUK:true
				},
				gcse_maths_date_completed: {
					dateUK:true
				},
				date_completed1: {
					dateUK:true
				},
				date_completed2: {
					dateUK:true
				},
				date_completed3: {
					dateUK:true
				},
				date_completed4: {
					dateUK:true
				},
				date_completed5: {
					dateUK:true
				},
				date_completed6: {
					dateUK:true
				},
				date_completed7: {
					dateUK:true
				},
				date_completed8: {
					dateUK:true
				},
				date_completed9: {
					dateUK:true
				},
				date_completed10: {
					dateUK:true
				},
				date_completed11: {
					dateUK:true
				},
				date_completed12: {
					dateUK:true
				},
				date_completed13: {
					dateUK:true
				},
				date_completed14: {
					dateUK:true
				},
				date_completed15: {
					dateUK:true
				},
				ethnicity: {
					required:true
				},
				gender: {
					required:true
				},
				LLDD: {
					required:true
				},
				home_address_line_1: {
					required:true
				},
				home_address_line_3: {
					required:true
				},
				home_postcode: {
					postcodeUK:true,
					required:true
				},
				skills_trade_occ: {
					maxlength:449
				},
				ni: {
					required:true,
					niUK:true
				},
				home_email: {
					required:true,
					emailCheck:true
				},
				home_telephone: {
					phoneUK: true
				},
				empStatusEmployer: {
					required: function(element){
						return $("input[type='radio'][name='EmploymentStatus']:checked").val() == '10'
					}
				},
				LOE: {
					required: function(element){
						return $("#LOE").val() == '' && $("input[type='radio'][name='EmploymentStatus']:checked").val() == '10'
					}
				},
				EII: {
					required: function(element){
						return $("#EII").val() == '' && $("input[type='radio'][name='EmploymentStatus']:checked").val() == '10'
					}
				},
				LOU: {
					required: function(element){
						return $("#LOU").val() == '' && ($("input[type='radio'][name='EmploymentStatus']:checked").val() == '11' || $("input[type='radio'][name='EmploymentStatus']:checked").val() == '12')
					}
				},
				high_level: {
					required:true
				},
				care_or_ehc: {
					filesize: 1024000
				},
				file1: {
					filesize: 1024000
				},
				file2: {
					filesize: 1024000
				},
				file3: {
					filesize: 1024000
				},
				file4: {
					filesize: 1024000
				},
				file5: {
					filesize: 1024000
				},
				file6: {
					filesize: 1024000
				},
				evidence_pp: {
					filesize: 1024000
				},
				evidence_ilr: {
					filesize: 1024000
				},
				evidence_previous_uk_study_visa: {
					filesize: 1024000
				}
				/*,gcse_english_grade_predicted: {
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
				}*/
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

	$('input[name=dob]').datepicker("option", "yearRange", "-50:-10");
	$('input[name=dob]').datepicker("option", "defaultDate", "-18y");

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

	showEmploymentStatusFieldsIfAlreadySaved();

});

$("input[name=EmploymentStatus]").on('ifChecked', function(event){
	if(this.value == 10)
	{
		$('#tbl_emp_status_10').show();
		$('#tbl_emp_status_11_12').hide();
	}
	else if(this.value == 11 || this.value == 12)
	{
		$('#tbl_emp_status_10').hide();
		$('#tbl_emp_status_11_12').show();
	}
	else
	{
		$('#tbl_emp_status_10').hide();
		$('#tbl_emp_status_11_12').hide();
	}
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

$("#personal_info :input").change(function() {
	if(this.id == 'home_address_line_1' || this.id == 'home_address_line_2' || this.id == 'home_address_line_3' || this.id == 'home_address_line_4')
		$('#ilp_address').html($('#home_address_line_1').val() + ' ' + $('#home_address_line_2').val() + ' ' + $('#home_address_line_3').val() + ' ' + $('#home_address_line_4').val());
	else
		$('#ilp_'+this.id).html(this.value);
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
	for(var i = 1; i <= 15; i++)
		$('#ilp_pa'+i).html('<td>'+$("#level"+i+" option:selected").text()+' - '+$("#subject"+i).val()+'</td><td>'+$("#input_date_completed"+i).val()+'</td><td>'+$("#actual_grade"+i+" option:selected").text()+'</td><td>&nbsp;</td>');
}

function showEmploymentStatusFieldsIfAlreadySaved()
{
	var EmpStatus = $("input[name=EmploymentStatus]:checked").val();

	if(EmpStatus == '10')
	{
		$('#tbl_emp_status_10').show();
		$('#tbl_emp_status_11_12').hide();
	}
	else if(EmpStatus == '11' || EmpStatus == '12')
	{
		$('#tbl_emp_status_10').hide();
		$('#tbl_emp_status_11_12').show();
	}
	else
	{
		$('#tbl_emp_status_10').hide();
		$('#tbl_emp_status_11_12').hide();
	}
}