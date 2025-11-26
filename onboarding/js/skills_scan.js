function saveForm()
{
	$.ajax({
		type:'POST',
		url:'do.php',
		data: $('#frmSkillsScan').serialize(),
		async: false,
		success: function(data, textStatus, xhr) {

		},
		error: function(data, textStatus, xhr){
			var myxml = data.responseText,
				xmlDoc = $.parseXML( myxml ),
				$xml = $( xmlDoc );
			$(data.responseXML).find('error').each(function(){
				console.log('Error: ' + $(this).find('message').text());
				console.log('Error: ' + $(this).find('line').text());
				console.log('Error: ' + $(this).find('file').text());
			});
		}
	});
}

$(function(){

	$("select#fs_eng_opt_in").on("change", function (){
		$("tr#tr_eng_opt_out_reason").hide();
		if(this.value === 'No')
		{
			$("tr#tr_eng_opt_out_reason").show();
		}
	});
	$("select#fs_maths_opt_in").on("change", function (){
		$("tr#tr_maths_opt_out_reason").hide();
		if(this.value === 'No')
		{
			$("tr#tr_maths_opt_out_reason").show();
		}
	});

	$('.clsICheck').each(function(){
		var self = $(this),
			label = self.next(),
			label_text = label.text();

		label.remove();
		self.iCheck({
			checkboxClass: 'icheckbox_line-blue',
			insert: '<div class="icheck_line-icon"></div>' + label_text
		});
	});

	$('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});

	if(window.phpScrolLogic == 1)
	{
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
	}

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

	var frmSkillsScan = $("#frmSkillsScan").show();
	frmSkillsScan.steps({
		headerTag:"h3",
		bodyTag:"step",
		transitionEffect:"slideLeft",
		stepsOrientation:"vertical",
		// startIndex: 4,
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
					if( this.checked )
					{
						v++;
					}
				});
				if(v != 3)
				{
					custom_alert_OK_only('Please tick the consent options to continue.', 'Alert');
					$('.loader').hide();
					return false;
				}
			}
			if(currentIndex == 4 && phpClient != "am_ela") {
				var allAnswered = true;
				$("select[name^=score_]").each(function(){
					if(this.value == '')
					{
						$(this).closest('td').addClass('bg-red');
						$(this).closest('div.box').addClass('box-danger');
						alert('Please answer all questions');
						return allAnswered = false;
					}
				});
				$('.loader').hide();
				if(!allAnswered)
					return false;
			}
			if(currentIndex == 3 && phpClient == "am_ela") {
				var allAnswered = true;
				$("select[name^=score_]").each(function(){
					if(this.value == '')
					{
						$(this).closest('div.box-body').addClass('bg-red');
						$(this).closest('div.box').addClass('box-danger');
						alert('Please answer all questions');
						return allAnswered = false;
					}
				});
				$('.loader').hide();
				if(!allAnswered)
					return false;
			}
			var validForm = frmSkillsScan.valid();
			console.log(validForm);
			if(validForm)
			{
				if (currentIndex < newIndex)
				{
					//forward
					frmSkillsScan[0].elements['is_completed_by_learner'].value = 'N';
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
			if($("#learner_sign").val() == '')
				return alert('Your signature is required to complete this form, please sign the form.');

			return frmSkillsScan.valid();
		},
		onFinished:function (event, currentIndex) {
			frmSkillsScan[0].elements['is_completed_by_learner'].value = 'Y';
			frmSkillsScan.submit();
		}
	}).validate({
			errorPlacement: function (error, element)
			{
				element.after(error);
			},
			rules: {
				gcse_english_date_completed: {
					dateUK:true
				},
				gcse_maths_date_completed: {
					dateUK:true
				},
				gcse_ict_date_completed: {
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
				high_level: {
					required:true
				},
				fs_eng_opt_in: {
					required: function(element){
						return $("#fs_eng_opt_in").val() == '' && window.phpAgeAtStart >= 19;
					}
				},
				fs_maths_opt_in: {
					required: function(element){
						return $("#fs_maths_opt_in").val() == '' && window.phpAgeAtStart >= 19;
					}
				},
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
				/*,
				gcse_ict_grade_predicted: {
					required: function(element){
						return $("#gcse_ict_grade_actual").val() == ''
					}
				},
				gcse_ict_grade_actual: {
					required: function(element){
						return $("#gcse_ict_grade_predicted").val() == ''
					}
				}*/
			}
		});

	jQuery.validator.addMethod("dateUK",function(value, element) {
			return value == ''?true:value.match(/^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/);
		}, "Please enter a date in the format dd/mm/yyyy."
	);

	

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

// function getSignature()
// {
// 	$( "#panel_signature" ).dialog( "open");
// }

function getSignature() {
	if(window.phpLearnerSignature != '')
	{
		$('#img_learner_sign').attr('src', 'do.php?_action=generate_image&' + window.phpLearnerSignature);
		$('#learner_sign').val(window.phpLearnerSignature);
	}
	else
	{
		$( "#panel_signature" ).dialog( "open");
	}
	return;
}

function SignatureSelected(sig)
{
	$('.sigboxselected').attr('class','sigbox');
	sig.className = "sigboxselected";
}



