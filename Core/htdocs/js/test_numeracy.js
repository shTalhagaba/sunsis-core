var visited_steps = [0];

$(function () {
	var form = $("#frmInitialAssessment").show();
	form.steps({
		headerTag:"h3",
		bodyTag:"fieldset",
		transitionEffect:"slideLeft",
		stepsOrientation:"vertical",
		//startIndex: 2,
		onStepChanging:function (event, currentIndex, newIndex)
		{
			if(jQuery.inArray(newIndex, visited_steps) === -1)
			{
				visited_steps.push(newIndex);
				var currValue = $( "#progressbar" ).data("value");
				currValue = parseInt(currValue) ? parseInt(currValue) : 0;
				if(currValue <= 100)
				{
					$( "#progressbar" ).progressbar({
						value: currValue+33
					}).data("value",currValue+33);
					$("#progressLabel").html((currValue+33)+"%");
				}
			}
			var data = [];
			if(currentIndex < newIndex)
			{
			}

			// start the timer if section 10
			if(newIndex == 9) {
			}
			// Allways allow previous action even if the current form is not valid!
			if (currentIndex > newIndex) {
				return true;
			}
			form.validate().settings.ignore = ":disabled,:hidden";
			return form.valid();
		},
		onFinishing:function (event, currentIndex) {
			form.validate().settings.ignore = ":disabled";
			return form.valid();
		},

		onFinished:function (event, currentIndex) {
			var currValue = $( "#progressbar" ).data("value");
			currValue = parseInt(currValue) ? parseInt(currValue) : 0;
			if(currValue <= 100)
			{
				$( "#progressbar" ).progressbar({
					value: currValue+34
				}).data("value",currValue+34);
				$("#progressLabel").html((currValue+34)+"%");
			}
			alert('Your test is finished. ');
			window.location.href = 'do.php?_action=home_page';
		}
	}).validate({
			errorPlacement:function errorPlacement(error, element) {
				element.after(error);
			}
		});
});

$(function () {


});

function finishAndSubmitAssessment()
{
	var data = [];
	for(var i = 1; i <= 40; i++)
	{
		var ans = extractAnswers('question_'+i);
		ans = ans.replace('£', '');
		data.push(['question_'+i, ans]);
	}
	if(data.length > 0)
	{
		var postData = 'do.php?_action=save_participant_assessment' +
			'&id=' + phpId +
			'&participant_id=' + window.phpParticipantId +
			'&mode=' + window.phpAssessmentMode +
			'&date_taken=' + getTodayDate() +
			'&type=2' +
			'&data=' + JSON.stringify(data);

		var client = ajaxRequest(postData);
		if(client != null)
		{
			console.log(client.responseText);
		}
	}
	if(window.phpAssessmentMode == '1')
		window.location.href= 'do.php?_action=view_edit_participant&id=' + phpParticipantId;
	else if(window.phpAssessmentMode == '2')
		document.write(getHTMLOnFinish());
}

function getHTMLOnFinish()
{
	var html = '<table width="100%" border="0" style="height:100%">' +
		'<tr><td valign="middle" align="center"><div class="box"><h4>Successful Submission</h4><div class="message">Your assessment has been submitted successfully</td></tr></table>';

	return html;
}

function getTodayDate()
{
	var d = new Date();
	var day = d.getDate();
	var month = d.getMonth() + 1;
	var year = d.getFullYear();
	if (day < 10) {
		day = "0" + day;
	}
	if (month < 10) {
		month = "0" + month;
	}

	return year + "-" + month + "-" + day;
}

function extractAnswers(table_id)
{
	var t = '';
	if(table_id == 'question_23')
		t = $('#question_23_blank').val();
	else if(table_id == 'question_24')
		t = $('#question_24_blank').val();
	else if(table_id == 'question_25')
		t = $('#question_25_blank').val();
	else if(table_id == 'question_26')
	{
		if ($('input:radio[name="question_26_blank"]').is(':checked'))
			t = $('input:radio[name="question_26_blank"]:checked').val();
	}
	else if(table_id == 'question_30' || table_id == 'question_31')
	{
		if($('#'+table_id).find('tr').children('td.selected').html() === undefined)
			t = '';
		else
			t = $('#'+table_id).find('tr').children('td.selected').html();
	}
	else
		t = $('#'+table_id).find('tr').children('td.selected').text();

	return t;
}

function circle(ele)
{
	var table_id = $(ele).closest('table').attr('id');
	var blankOfQuestion = $('#'+table_id+'_blank');

	$('td[id^="' + table_id + '_choice_"]').attr('class', '');
	var selectedChoiceText = $(ele).html();
	$(ele).toggleClass('selected');
	$(blankOfQuestion).html(selectedChoiceText);

	$(ele).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
	$(blankOfQuestion).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
}

$(function() {
	$( "#progressbar" ).progressbar({
		value: 0
	})
		.data("value","0");
});


