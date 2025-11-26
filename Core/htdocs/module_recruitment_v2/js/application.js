
$(document).ready(function() {

	function adjustIframeHeight() {
		var $body   = $('body'),
			$iframe = $body.data('iframe.fv');
		if ($iframe) {
			// Adjust the height of iframe
			$iframe.height($body.height());
		}
	}
});

$(function () {
	jQuery.validator.addClassRules('compulsory', {
		required: true
	});
	jQuery.validator.addClassRules('datepicker', {
		dateUK:true
	});

	var form = $("#recruitmentForm").show();
	form.steps({
		headerTag:"h3",
		bodyTag:"step",
		transitionEffect:"slideLeft",
		stepsOrientation:"horizontal",
		//startIndex: 4,
		onStepChanging:function (event, currentIndex, newIndex) {
			// Allways allow previous action even if the current form is not valid!
			if (currentIndex > newIndex) {
				return true;
			}
			// Needed in some cases if the user went back (clean up)
			if (currentIndex < newIndex) {
				// To remove error styles
				form.find(".body:eq(" + newIndex + ") label.error").remove();
				form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
			}
			form.validate().settings.ignore = ":disabled,:hidden";
			return form.valid();
		},
		onStepChanged:function (event, currentIndex, priorIndex) {
			// Used to skip the "Warning" step if the user is old enough and wants to the previous step.
			/*if (currentIndex === 2 && priorIndex === 3) {
			 form.steps("previous");
			 }*/
		},
		onFinishing:function (event, currentIndex) {
			form.validate().settings.ignore = ":disabled";
			return form.valid();
		},
		onFinished:function (event, currentIndex) {

			var myForm = document.forms['recruitmentForm'];
			myForm.enctype = "multipart/form-data";
			myForm.submit();
		}
	}).validate({
			errorPlacement:function errorPlacement(error, element) {
				element.after(error);
			},
			rules:{
				firstnames:{
					required:true
				},
				surname:{
					required:true
				},
				gender:{
					required:true
				},
				ethnicity:{
					required:true
				},
				dob:{
					required:true,
					dateUK:true
				},
				national_insurance:{
					required:true,
					niUK:true
				},
				address1:{
					required:true
				},
				county:{
					required:true
				},
				postcode:{
					required:true,
					postcodeUK:true
				},
				region:{
					required:true
				},
				telephone:{
					required:true
				},
				mobile:{
					required:true
				},
				email:{
					required:true,
					emailCheck:true
				},
				gcse_english_grade:{
					required:true
				},
				gcse_maths_grade:{
					required:true
				},
				guardian_email:{
					/*required:function(element){
						var age = getAge($("#input_dob").val());
						return age < 18;
					},*/
					required:true,
					emailCheck:true
				},
				guardian_contact:{
					required:true
				},
				supplementary_question_1_answer:{
					maxlength: 500
				},
				supplementary_question_2_answer:{
					maxlength: 500
				},
				q_a_8:{
					maxlength: 500
				},
				q_a_9:{
					maxlength: 500
				},
				q_a_10:{
					maxlength: 500
				},
				q_a_11:{
					maxlength: 500
				},
				q_a_12:{
					maxlength: 500
				},
				q_a_13:{
					maxlength: 500
				},
				q_a_14:{
					maxlength: 500
				}
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

});

function employment_status_onchange(id, event) {
	if( id.value >= 3 )
	{
		document.getElementById('time_last_worked').disabled = '';
		document.getElementById('hours_per_week').disabled = 'disabled';
	}
	else
	{
		document.getElementById('time_last_worked').disabled = 'disabled';
		document.getElementById('hours_per_week').disabled = '';
	}
}

function searchVacancies()
{
	var myForm = document.forms["frmSearchVacancies"];
	myForm.submit();
}

function getAge(dateString)
{
	var pieces = dateString.split('/');
	dateString = pieces[2]+'-'+pieces[1]+'-'+pieces[0];
	var birthday = new Date(dateString);

	var ageDifMs = Date.now() - birthday.getTime();
	var ageDate = new Date(ageDifMs); // miliseconds from epoch
	return Math.abs(ageDate.getFullYear() - 1970);
}
