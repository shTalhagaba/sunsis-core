<?php /* @var $candidate RecCandidate*/ ?>
<?php /* @var $application RecCandidateApplication*/ ?>
<?php /* @var $vacancy RecVacancy*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>View/Edit Vacancy Application</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

	<!-- CSS for TabView -->
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

	<!-- Dependency source files -->
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

	<!-- Page-specific script -->
	<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

	<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="css/progress-wizard.min.css" rel="stylesheet">

	<script type="text/javascript">

		var phpApplicationID = '<?php echo $application->id; ?>';
		var phpVacancyIsFull = '<?php echo $vacancy_is_full; ?>';

		function treeInit() {
			myTabs = new YAHOO.widget.TabView("demo");
			myTabs = new YAHOO.widget.TabView("post_progression_inner_tabs");
		}

		YAHOO.util.Event.onDOMReady(treeInit);


	</script>
	<style type="text/css">
		.disabledbutton {
			pointer-events: none;
			opacity: 0.4;
		}

		.disabled{
			pointer-events:none;
			opacity:0.4;
		}

		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}

		.notification-counter {
			position: absolute;
			top: -2px;
			left: 8px;
			background-color: rgba(212, 19, 13, 1);
			color: #fff;
			border-radius: 3px;
			padding: 1px 3px;
			font: 10px Verdana;
		}
		.subdued {
			font-size: 200%;
		}
		.progress-indicator.stacked {
			width: 100%;
		}
		.progress-indicator.stacked > li {
			height: 150px;
		}
		.progress-indicator.stacked > li .bubble {
			padding: 0.1em;
		}
		.progress-indicator.stacked > li:first-of-type .bubble {
			padding: 0.5em;
		}
		.progress-indicator.stacked > li:last-of-type .bubble {
			padding: 0em;
		}
			/* LIST #2 */
		#list2 { width:80%; }
		#list2 ul { font-family:Verdana, Arial, serif; font-size:20px;   }
		#list2 ul li { }
		#list2 ul li p { padding:8px; font-style:normal; font-family:Arial; font-size:13px;  border-left: 1px solid #999; }
		#list2 ul li p em { display:block; }

		textarea:focus {
			background: red;
			outline: none !important;
			border:2px solid #ffc0cb;
			box-shadow: 0 0 10px #719ECE;
		}

		select:focus {
			background: red;
			outline: none !important;
			border:2px solid #ffc0cb;
			box-shadow: 0 0 10px #719ECE;
		}
	</style>
	<script type="text/javascript">

		var phpApplicationInterviewScore = '<?php echo $application_interview_score; ?>';
		$(function(){
			$('select[name^=q_s_]').change(function(e){
				var score = 0;
				$('select[name^=q_s_]').each(function(){
					score += parseInt(this.value);
				});
				$('#lblTotalScreeningScore').html(score);
			});
			$('#lblTotalScreeningScore').html(window.phpApplicationInterviewScore);
		});
		function saveScreening()
		{
			var rag_rating = $('#rag_rating').val();
			if(rag_rating == '')
			{
				if(!confirm('Are you sure you want to continue without selecting RAG rating?'))
					return false;
			}
			var q = {};
			var screening = [];
			$('#frmScreening textarea[id^=q_a_]').each(function(){
				var q_number = this.id.split('_');
				q_number = q_number[2];
				q = {"question_id": this.id, "answer": this.value, "application_id": window.phpApplicationID};
				screening.push(q);
			});
			//return console.log(screening);
			var parameters = '&screening=' + JSON.stringify(screening) +
					'&screening_rag=' + rag_rating +
					'&screening_comments=' + encodeURIComponent($('#screening_comments').val()) +
					'&yes_no_auto_email=' + encodeURIComponent($('#yes_no_auto_email').val()) +
					'&application_id=' + window.phpApplicationID
				;

			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=saveScreening'+parameters, null, null, saveApplicationScreeningCallback);
		}
		function saveApplicationScreeningCallback(client)
		{
			$('.loading-gif').hide();
			if(client.responseText)
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=0'
			}
			else
			{
				alert(client.responseText);
			}
		}
		function telephone_interview_outcome_onchange(outcome)
		{
			$('#tr_interview_stage_candidate_email_successful').hide();
			$('#tr_interview_stage_candidate_email_unsuccessful').hide();
			$('#tr_interview_stage_candidate_email_notcontactable').hide();

			if(outcome.value == 'successful')
			{
				$('#tr_interview_stage_candidate_email_successful').show();
			}
			if(outcome.value == 'unsuccessful')
			{
				$('#tr_interview_stage_candidate_email_unsuccessful').show();
			}
			if(outcome.value == 'notcontactable')
			{
				$('#tr_interview_stage_candidate_email_notcontactable').show();
			}
		}
		function updateCandidateStatusToTelephoneInterviewed()
		{
			tinymce.triggerSave();

			var q = {};
			var telephone_interview = [];
			$('#frmTelephoneInterview textarea[id^=q_a_]').each(function(){
				var q_number = this.id.split('_');
				q_number = q_number[2];
				q = {"question_id": this.id, "answer": this.value.replace('�', '&pound;'), "score": $('#q_s_'+q_number).val(), "application_id": window.phpApplicationID};
				telephone_interview.push(q);
			});
			//return console.log(telephone_interview);
			var telephone_interview_outcome_email = '';
			if($('#telephone_interview_outcome').val() == 'successful')
				telephone_interview_outcome_email = $('#interview_stage_candidate_email_successful').val().trim();
			else if($('#telephone_interview_outcome').val() == 'unsuccessful')
				telephone_interview_outcome_email = $('#interview_stage_candidate_email_unsuccessful').val().trim();
			else if($('#telephone_interview_outcome').val() == 'notcontactable')
				telephone_interview_outcome_email = $('#interview_stage_candidate_email_notcontactable').val().trim();
			var parameters = '&telephone_interview=' + encodeURIComponent(JSON.stringify(telephone_interview)) +
					'&telephone_interview_score=' + parseInt($('#lblTotalScreeningScore').html()) +
					'&telephone_interview_outcome=' + encodeURIComponent($('#telephone_interview_outcome').val()) +
					'&telephone_interview_comments=' + encodeURIComponent($('#frmTelephoneInterview #comments').val()) +
					'&telephone_interview_outcome_email=' + encodeURIComponent(telephone_interview_outcome_email) +
					'&application_id=' + window.phpApplicationID
				;

			$('.loading-gif').show();
			var frmTelephoneInterview = document.forms["frmTelephoneInterview"];
			frmTelephoneInterview.elements["telephone_interview"].value = JSON.stringify(telephone_interview);
			frmTelephoneInterview.elements["telephone_interview_score"].value = parseInt($('#lblTotalScreeningScore').html());
			frmTelephoneInterview.elements["telephone_interview_outcome"].value = ($('#telephone_interview_outcome').val());
			frmTelephoneInterview.elements["telephone_interview_comments"].value = ($('#frmTelephoneInterview #comments').val());
			frmTelephoneInterview.elements["telephone_interview_outcome_email"].value = (telephone_interview_outcome_email);
			frmTelephoneInterview.elements["application_id"].value = window.phpApplicationID;

			//var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=saveTelephoneInterview'+parameters, null, null, saveApplicationTelephoneInterviewCallback);
			var client = ajaxPostForm(frmTelephoneInterview, saveApplicationTelephoneInterviewCallback);
		}
		function saveApplicationTelephoneInterviewCallback(client)
		{
			$('.loading-gif').hide();
			if(client.status == '200')
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=2'
			}
			else
			{
				alert(client.responseText);
			}
		}
		function validateEmail(email)
		{
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}
		function updateCandidateStatusToCVSent(application_id)
		{
			var sendEmail = '0';
			var emailParameters = '';
			if($('#chkSendEmail').attr('checked'))
			{
				tinymce.triggerSave();
				sendEmail = '1';
				var receiverName = $('#receiverName');
				var receiverEmail = $('#receiverEmail');
				var senderEmail = $('#senderEmail');
				var emailSubject = $('#emailSubject');
				var email_contents = $('#email_contents');
				if(receiverName.val().trim() == '')
				{
					alert('Please provide receiver\'s name');
					receiverName.focus();
					return false;
				}
				if(receiverEmail.val().trim() == '')
				{
					alert('Please provide receiver\'s email address');
					receiverEmail.focus();
					return false;
				}
				else if(!validateEmail(receiverEmail.val().trim()))
				{
					alert('Please provide a valid email address');
					receiverEmail.focus();
					return false;
				}
				if(senderEmail.val().trim() == '')
				{
					alert('Please provide your email address');
					senderEmail.focus();
					return false;
				}
				else if(!validateEmail(senderEmail.val().trim()))
				{
					alert('Please provide a valid email address');
					senderEmail.focus();
					return false;
				}
				if(emailSubject.val().trim() == '')
				{
					alert('Please provide a suitable subject for the email');
					emailSubject.focus();
					return false;
				}
				if(email_contents.val().trim() == '')
				{
					alert('You cannot send blank email');
					email_contents.focus();
					return false;
				}
				emailParameters = '&receiverName=' + receiverName.val().trim() +
					'&receiverEmail=' + receiverEmail.val().trim() +
					'&senderEmail=' + senderEmail.val().trim() +
					'&emailSubject=' + emailSubject.val().trim() +
					'&email_contents=' + encodeURIComponent(email_contents.val().trim())
				;
			}
			var parameters = '&screening_comments=' + encodeURIComponent($('#frmCVSent #screening_comments').val()) +
					'&application_id=' + window.phpApplicationID +
					'&send_email=' + sendEmail +
					emailParameters
				;
			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=updateCandidateStatusToCVSent'+parameters, null, null, updateCandidateStatusToCVSentCallback);
		}
		function updateCandidateStatusToCVSentCallback(client)
		{
			$('.loading-gif').hide();
			if(client.responseText)
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=3';
			}
			else
			{
				alert(client.responseText);
			}
		}
		function updateCandidateInterviewStatus(application_id)
		{
			var interview_outcome = $('#interview_outcome').val();

			if(!confirm('Are you sure?'))
			{
				return false;
			}

			var parameters = '&interview_outcome=' + interview_outcome +
					'&screening_comments=' + encodeURIComponent($('#frmInterviewStage #screening_comments').val()) +
					'&application_id=' + window.phpApplicationID
				;
			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=updateCandidateInterviewStatus'+parameters, null, null, updateCandidateInterviewStatusCallback);
		}
		function updateCandidateInterviewStatusCallback(client)
		{
			$('.loading-gif').hide();
			if(client.responseText)
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=3';
			}
			else
			{
				alert(client.responseText);
			}
		}
		/*function assessor_onchange(assessor)
		{
			tinymce.get('assessor_email_contents').getBody().innerHTML = '';

			if(assessor.value == '')
				return;

			if($("#assessor option:selected").text().indexOf('---NO EMAIL ADDRESS---') >= 0)
			{
				alert('No email address exists in the system for this assessor, email template will not be loaded');
				return;
			}

			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=getAssessorEmailText&application_id='+encodeURIComponent(window.phpApplicationID)+'&assessor_id='+encodeURIComponent(assessor.value));
			if(client.status == '200')
			{
				tinymce.get('assessor_email_contents').getBody().innerHTML = client.responseText;
			}
			else
			{
				alert(client.responseText);
			}
		}
		function convertToSunesisLearner(application_id)
		{
			if(!confirm('Are you sure?'))
			{
				return false;
			}
			if(window.phpVacancyIsFull == '1')
			{
				alert('This vacancy is full, candidate cannot be converted into Sunesis Learner');
				return false;
			}

			tinymce.triggerSave();
			var parameters = '&assessor=' + $('#frmConvertLearner #assessor').val() +
					'&screening_comments=' + encodeURIComponent($('#frmConvertLearner #screening_comments').val()) +
					'&conversion_email_cc=' + encodeURIComponent($('#conversion_email_cc').val()) + 
					'&assessor_email_contents=' + $('#assessor_email_contents').val() +
					'&application_id=' + window.phpApplicationID
				;

			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=convertToSunesisLearner'+parameters, null, null, convertCandidateToSunesisLearnerCallback);
		}*/
		function convertCandidateToSunesisLearnerCallback(client)
		{
			$('.loading-gif').hide();
			if(client.status == '200')
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=5'
			}
			else
			{
				alert(client.responseText);
			}
		}
		function showDialogSendEmail()
		{
			if($('#chkSendEmail').attr('checked'))
				$('#tblCVSent').show();
			else
				$('#tblCVSent').hide();
		}

		function decideScreeningLater()
		{
			var parameters = '&application_id=' + window.phpApplicationID +
				'&screening_rag=' + encodeURIComponent($('#frmScreening #rag_rating').val()) +
				'&screening_comments=' + encodeURIComponent($('#frmScreening #screening_comments').val());

			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=decideScreeningLater'+parameters, null, null, decideScreeningLaterCallback);
		}
		function decideScreeningLaterCallback(request)
		{
			$('.loading-gif').hide();
			if(request.status == 200)
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=0'
			}
			else
			{
				alert(request.responseText);
			}
		}
		function rejectApplication()
		{
			if(!confirm('Are you sure?'))
				return;

			var parameters = '&application_id=' + window.phpApplicationID +
				'&yes_no_auto_email=' + encodeURIComponent($('#yes_no_auto_email').val()) +
				'&screening_rag=' + encodeURIComponent($('#frmScreening #rag_rating').val()) +
				'&screening_comments=' + encodeURIComponent($('#frmScreening #screening_comments').val());

			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=rejectApplication'+parameters, null, null, rejectApplicationCallback);
		}
		function rejectApplicationCallback(request)
		{
			$('.loading-gif').hide();
			if(request.status == 200)
			{
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=0'
			}
			else
			{
				alert(request.responseText);
			}
		}
	</script>
</head>
<body onload="$('.loading-gif').hide();" class="yui-skin-sam">
<div class="banner">
	<div class="Title">View/Edit Vacancy Application</div>
	<div class="ButtonBar">
		<button onclick="if(confirm('You have clicked on Cancel button, this will take you back to the previous screen and your information will not be saved. Are you sure you want to continue?'))window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>')">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div style = 'left : 50%;top : 50%;position : fixed;z-index : 101;width : 32px;height : 32px;margin-left : -16px;margin-top : -16px;'>
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>

<p>
<?php echo $this->renderStatus($link, $application); ?>
</p>

<div id="demo" class="yui-navset">
	<ul class="yui-nav">
		<li <?php echo $tab1; ?>><a href="#tab1"><em>Details</em></a></li>
		<li <?php echo $tab2; ?>><a href="#tab2"><em>History</em></a></li>
		<?php if($_SESSION['user']->isAdmin()){?><li <?php echo $tab3; ?>><a href="#tab3"><em>Application</em></a></li><?php } ?>
		<li <?php echo $tab4; ?>><a href="#tab4"><em>Telephone Interview</em></a></li>
		<?php if($_SESSION['user']->isAdmin()) { ?><li <?php echo $tab5; ?>><a href="#tab5"><em>LRS</em></a></li><?php } ?>
	</ul>

	<div class="yui-content" style='background: white;border-width:1px;border-style:solid;border-color:#00A4E4;'>

		<div id="tab1">
			<table>
				<tr valign="top">
					<td valign="top">
						<fieldset>
							<legend>Vacancy Details</legend>
							<table cellpadding="6" cellspacing="1">
								<col width="100" />
								<col width="300" />
								<tr>
									<td class="fieldLabel">Vacancy Reference:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->vacancy_reference); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Vacancy Title:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->vacancy_title); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel" valign="top">Number of Positions:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->no_of_positions); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Vacancy Location:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy_location); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Framework:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = '{$vacancy->app_framework}';")); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Wage (�):</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->wage); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Wage Type:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->wage_type); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Wage Text:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->wage_text); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Working Week:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->working_week); ?></td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="chart-panel-body " id="graphApplicationsByStatus"></div>
									</td>
								</tr>
							</table>
						</fieldset>
						<fieldset>
							<legend>Candidate Details</legend>
							<table cellpadding="6" cellspacing="1">
								<tr>
									<td class="fieldLabel">Firstname(s):</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->firstnames); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Surname:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->surname); ?></td>
								</tr>

								<tr>
									<td class="fieldLabel">Date of birth:</td>
									<td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($candidate->dob)); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">National Insurance:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->national_insurance); ?></td>
								</tr>
								<!--<tr>
									<td class="fieldLabel">Gender:</td>
									<td class="fieldValue"><?php /*echo htmlspecialchars(DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$candidate->gender}';")); */?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Ethnicity:</td>
									<td class="fieldValue"><?php /*echo htmlspecialchars(DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201314.ilr_ethnicity WHERE Ethnicity='{$candidate->ethnicity}';")); */?></td>
								</tr>-->
								<tr>
									<td class="fieldLabel" valign="top">Candidate CV:</td>
									<td class="fieldValue"><?php if ($cv_file_link != '') echo  $cv_file_link; else echo 'CV Not Provided'; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel" valign="top">Address:</td>
									<td class="fieldValue">
										<?php
										echo $candidate->address1 . '<br>';
										echo $candidate->address2 . '<br>';
										echo $candidate->borough . '<br>';
										echo $candidate->county . '<br>';
										echo $candidate->postcode . '<br>';
										?>
									</td>
								</tr>
								<tr>
									<td class="fieldLabel">Telephone:</td>
									<td class="fieldValue"><?php echo $candidate->telephone; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Mobile:</td>
									<td class="fieldValue"><?php echo $candidate->mobile; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Email:</td>
									<td class="fieldValue"><?php echo $candidate->email; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">English GCSE Grade:</td>
									<td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT qualification_grade FROM candidate_qualification WHERE candidate_id = '{$candidate->id}' AND qualification_level = 'GCSE' AND qualification_subject = 'English Language' ORDER BY id LIMIT 1"); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Maths GCSE Grade:</td>
									<td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT qualification_grade FROM candidate_qualification WHERE candidate_id = '{$candidate->id}' AND qualification_level = 'GCSE' AND qualification_subject = 'Maths' ORDER BY id LIMIT 1"); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Parent/Guardian Email:</td>
									<td class="fieldValue"><?php echo $candidate->guardian_email; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Parent/Guardian Contact:</td>
									<td class="fieldValue"><?php echo $candidate->guardian_contact; ?></td>
								</tr>
							</table>
						</fieldset>
						<fieldset>
							<legend>Employment History</legend>
							<?php echo $this->render_candidate_employment($link, $candidate); ?>
						</fieldset>
						<fieldset>
							<legend>Availability to work</legend>
							<table class="resultset" cellspacing="0" cellpadding="6" style="font-size: smaller;">
								<thead><tr><th>Day</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr></thead>
								<tr>
									<th>Start Time</th>
									<td><?php echo $shift_pattern->mon_start_time; ?></td>
									<td><?php echo $shift_pattern->tue_start_time; ?></td>
									<td><?php echo $shift_pattern->wed_start_time; ?></td>
									<td><?php echo $shift_pattern->thu_start_time; ?></td>
									<td><?php echo $shift_pattern->fri_start_time; ?></td>
									<td><?php echo $shift_pattern->sat_start_time; ?></td>
									<td><?php echo $shift_pattern->sun_start_time; ?></td>
								</tr>
								<tr>
									<th>End Time</th>
									<td><?php echo $shift_pattern->mon_end_time; ?></td>
									<td><?php echo $shift_pattern->tue_end_time; ?></td>
									<td><?php echo $shift_pattern->wed_end_time; ?></td>
									<td><?php echo $shift_pattern->thu_end_time; ?></td>
									<td><?php echo $shift_pattern->fri_end_time; ?></td>
									<td><?php echo $shift_pattern->sat_end_time; ?></td>
									<td><?php echo $shift_pattern->sun_end_time; ?></td>
								</tr>
							</table>
						</fieldset>
						<?php if($_SESSION['user']->isAdmin()){?>
						<fieldset>
							<legend>Other vacancies applied</legend>
							<?php echo $this->renderCandidateApplications($link, $application); ?>
						</fieldset>
						<?php } ?>
					</td>
					<?php if($application->current_status == RecCandidateApplication::CREATED) {?>
					<td valign="top">
						<form id="frmScreening" method="get" action="">
						<fieldset>
							<legend>Supplementary Questions</legend>
							<table cellpadding="2" cellspacing="1">
								<tr><td><strong><i><?php echo $application->vacancy->getSupplementaryQuestion1Description($link); ?></i></strong></td></tr>
								<tr><td style="color: blue;"><?php echo $application->supplementary_question_1_answer; ?></td></tr>
								<tr><td><strong><i><?php echo $application->vacancy->getSupplementaryQuestion2Description($link); ?></i></strong></td></tr>
								<tr><td style="color: blue;"><?php echo $application->supplementary_question_2_answer; ?></td></tr>
							</table>
						</fieldset>
						<fieldset>
							<legend>Application Questions</legend>
							<?php
								if(isset($application_questions) && count($application_questions) > 0)
								{
									echo '<table cellpadding="2" cellspacing="1">';
									foreach($application_questions AS $q)
									{
										echo '<tr><td><strong><i>' . $q['description'] . '</i></strong></td></tr>';
										$answer_detail = DAO::getSingleValue($link, "SELECT answer FROM candidate_application_screening WHERE application_id = '" . $application->id . "' AND question_id = '" . $q['question_id'] . "'");
										if($answer_detail == '')
											echo '<tr><td><textarea rows="5" cols="70" id="q_a_'.$q['question_id'].'"></textarea></td></tr>';
										else
											echo '<tr><td style="color: blue;">' . $answer_detail . '</td></tr>';
									}
									echo '</table>';
								}
								if(count($application_questions) == 0 && count($application_questions) == 0)
								{
									echo '<p>There are no screening questions for this vacancy</p>';
								}
							?>
						</fieldset>

							<br>
							<table>
								<tr>
									<td class="fieldLabel">RAG Rating:</td>
									<td>
										<?php
										$g_selected = ' ';
										$a_selected = ' ';
										$r_selected = ' ';
										if($application->screening_rag == 'G')
											$g_selected = ' selected="selected" ';
										elseif($application->screening_rag == 'A')
											$a_selected = ' selected="selected" ';
										elseif($application->screening_rag == 'R')
											$r_selected = ' selected="selected" ';
										?>
										<select id="rag_rating">
											<option value=""></option>
											<option value="G" style="background-color: #E0EAD0;" <?php echo $g_selected; ?>>Screen as Green</option>
											<option value="A" style="background-color: #FFE6D7;" <?php echo $a_selected; ?>>Screen as Amber</option>
											<option value="R" style="background-color: #FFBFBF;" <?php echo $r_selected; ?>>Screen as Red</option>
										</select>
									</td>
									<td></td>
								</tr>
								<tr><td valign="top" class="fieldLabel">Your Comments:</td><td><textarea rows="10" cols="70" id="screening_comments"></textarea></td><td></td></tr>
								<tr><td class="fieldLabel">Send Auto-Email:</td><td><?php echo HTML::select('yes_no_auto_email', $yes_no_options, '1'); ?></td><td></td></tr>
								<tr><td colspan="3"><br></td></tr>
								<tr>
									<td align="left"><span class="button" onclick="decideScreeningLater();">Decide Later</span></td>
									<td align="center"><span class="button" onclick="saveScreening();">&nbsp;&nbsp;&nbsp;Accept&nbsp;&nbsp;&nbsp;</span></td>
									<td align="right"><span class="button" onclick="rejectApplication();">&nbsp;&nbsp;&nbsp;Reject&nbsp;&nbsp;&nbsp;</span></td>
								</tr>
								<tr><td colspan="3"><br></td></tr>
								<tr>
									<td>Decide Later</td>
									<td colspan="2"><span style="font-size:smaller; color:gray;font-style:italic">This action will save the comments and keep the application as 'Not Screened'. Saved Comments can be seen within History tab</span></td>
								</tr>
								<tr>
									<td>Accept</td>
									<td colspan="2"><span style="font-size:smaller; color:gray;font-style:italic">This action will save the comments and move the application to 'Screened'</span></td>
								</tr>
								<tr>
									<td>Reject</td>
									<td colspan="2"><span style="font-size:smaller; color:gray;font-style:italic">This action will save the comments and move the application to 'Rejected'</span></td>
								</tr>
							</table>
						</form>
					</td>
					<?php } elseif($application->current_status == RecCandidateApplication::SCREENED){?>
					<td valign="top">
						<form id="frmTelephoneInterview" name="frmTelephoneInterview" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<input type="hidden" name="_action" value="rec_view_edit_vacancy_application" />
						<input type="hidden" name="subaction" value="saveTelephoneInterview" />
						<input type="hidden" name="telephone_interview" value="" />
						<input type="hidden" name="telephone_interview_score" value="" />
						<input type="hidden" name="telephone_interview_outcome" value="" />
						<input type="hidden" name="telephone_interview_comments" value="" />
						<input type="hidden" name="telephone_interview_outcome_email" value="" />
						<input type="hidden" name="application_id" value="" />
						<fieldset>
							<legend>Telephone Interview</legend>
							<table class="resultset" cellpadding="4" width="70%">
								<tr><th colspan="3" align="center">Intro</th></tr>
								<tr><td colspan="3" align="left" style="color: blue;">Identify candidate. Hi, my name is <strong><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></strong>. And I am calling from <?php echo $this->client_name; ?> about your apprenticeship application. Are you in a suitable place to talk for 10 minutes? Can I confirm your postcode (to check I am talking to the right person)? To help with your application I just want to have a chat find out if you are right for us and help you to see if we are right for you</td></tr>
								<tr><th colspan="3" align="center">Application check /Eligibility</th></tr>
								<?php
								$interview_questions = DAO::getResultset($link, "SELECT * FROM rec_interview_questions WHERE id BETWEEN 1 AND 11 ORDER BY id ", DAO::FETCH_ASSOC);
								foreach($interview_questions AS $question)
								{
									echo '<tr>';
									echo '<td width="50%">';
									$question['description'] = str_replace('$$STORE_NAME$$', $vacancy->getEmployerName($link), $question['description']);
									$question['description'] = str_replace('$$STORE_LOCATION$$', $vacancy->getLocation($link), $question['description']);
									echo '<p style="font-weight: bold;">' . $question['description'] . '</p>';
									echo '<p><span style="color:gray;font-style:italic;font-size:smaller;">' . $question['notes'] . '</span></p>';
									echo '</td>';
									echo '<td colspan="2" width="50%">';
									echo '<textarea rows="5" cols="70" id="q_a_'.$question['id'].'">';
									echo DAO::getSingleValue($link, "SELECT answer FROM candidate_application_interview_screening WHERE application_id = '" . $application->id . "' AND question_id = '" . $question['id'] . "'");
									echo '</textarea></td>';
									echo '</tr>';
								}
								?>
								<tr><td colspan="3" align="left" style="color: blue;">If any of the answers mean they are ineligible, thank them for their time, explain why we can�t take them further and wish them the best of luck with their future.</td></tr>
								<tr><th colspan="3" align="center">Interview</th></tr>
								<?php
								$interview_questions = DAO::getResultset($link, "SELECT * FROM rec_interview_questions WHERE id BETWEEN 12 AND 16 ORDER BY id ", DAO::FETCH_ASSOC);
								foreach($interview_questions AS $question)
								{
									echo '<tr>';
									echo '<td width="50%">';
									echo '<p style="font-weight: bold;">' . $question['description'] . '</p>';
									echo '<p><span style="color:gray;font-style:italic;font-size:smaller;">' . $question['notes'] . '</span></p>';
									echo '</td>';
									echo '<td width="45%">';
									echo '<textarea rows="5" cols="70" id="q_a_'.$question['id'].'">';
									echo DAO::getSingleValue($link, "SELECT answer FROM candidate_application_interview_screening WHERE application_id = '" . $application->id . "' AND question_id = '" . $question['id'] . "'");
									echo '</textarea></td>';
									echo '<td width="5%">' . HTML::select('q_s_'.$question['id'], $score_ddl, DAO::getSingleValue($link, "SELECT score FROM candidate_application_interview_screening WHERE application_id = '" . $application->id . "' AND question_id = '" . $question['id'] . "'")) . '</td>';
									echo '</tr>';
								}
								?>
								<tr>
									<td class="fieldLabel">1=Poor; 2=Unsatisfactory; 3=Good; 4=excellent; 5=Outstanding</td>
									<td align="right">Total Score</td><td><span style="font-size: 500%;"><label id="lblTotalScreeningScore">0</label></span></td>
								</tr>
								<tr>
									<td colspan="3" align="left" style="color: blue;">
										If candidate has scored 16 or less thank them for their time, feedback on why you are not taking them forward with something to work on for next time and wish them luck with the job hunt and future.
										<p>If scored 16-25 go through apprenticeship details below;</p>
									</td>
								</tr>
								<tr><th colspan="3" align="center">Apprenticeship details</th></tr>
								<?php
								$interview_questions = DAO::getResultset($link, "SELECT * FROM rec_interview_questions WHERE id BETWEEN 19 AND 25 ORDER BY id ", DAO::FETCH_ASSOC);
								foreach($interview_questions AS $question)
								{
									echo '<tr>';
									echo '<td width="50%">';
									echo '<p style="font-weight: bold;">' . $question['description'] . '</p>';
									echo '<p><span style="color:gray;font-style:italic">' . $question['notes'] . '</span></p>';
									echo '</td>';
									echo '<td width="50%" colspan="2">';
									echo '<textarea rows="5" cols="70" id="q_a_'.$question['id'].'">';
									echo DAO::getSingleValue($link, "SELECT answer FROM candidate_application_interview_screening WHERE application_id = '" . $application->id . "' AND question_id = '" . $question['id'] . "'");
									echo '</textarea></td>';
									echo '</tr>';
								}
								?>
								<tr>
									<td colspan="3" align="left" style="color: blue;">
										<p>Thank them for their time.</p>
										<p>Inform them they will be contacted by email by the end of the week to confirm if they have been successful at this stage and have been put forward for a face-to �face interview at the store.</p>
										<p>If you are invited for interview, is there anything that we would need to take into account to ensure your interview is a fair reflection of your ability? Any learning disability or anything that would require support or reasonable adjustment?</p>
									</td>
								</tr>
								<tr><td class="fieldLabel">Outcome:</td><td colspan="2"><?php echo HTML::select('telephone_interview_outcome', $telephone_interview_outcome, '', false, true);?></td></tr>
								<tr>
									<td class="fieldLabel">Your Comments:<br><span style="font-size:smaller; color:gray;font-style:italic">enter comments as these will be visible to the Store Managers<br>previously stored comments can be seen under <u>History</u> tab</span></td>
									<td colspan="2"><textarea rows="10" cols="70" id="comments"></textarea></td>
								</tr>
								<tr id="tr_interview_stage_candidate_email_successful" style="display: none;">
									<td class="fieldLabel">Email: <span style="font-size:smaller; color:gray;font-style:italic">email for the candidate</span></td>
									<td colspan="2">
										<textarea id="interview_stage_candidate_email_successful" name="interview_stage_candidate_email_successful" style="font-family:sans-serif; font-size:10pt" cols="100" rows="30" >
											<p>Dear <?php echo $application->candidate->firstnames . ' ' . $application->candidate->surname; ?>,</p>
											<p>Congratulations! You have been successfully shortlisted to attend an interview with the Store Manager for the role of <span style="text-decoration: underline;"><?php echo $application->vacancy->vacancy_title; ?></span>&nbsp;in <u><?php echo $application->vacancy->getLocation($link); ?></u>.</p>
											<p>Interviews for this position are planned to be held at the store in the next few weeks.</p>
											<p>The Store Manager will contact you next week by phone to confirm the date and time of your interview. They will only attempt to contact you for a limited number of times so please answer your phone at the 1<sup>st</sup> opportunity.</p>
											<p>Please bring your original school/college certificates for Maths and English, your passport or birth certificate and your National Insurance number letter. You will also need a photocopy of each document to leave with the Manager. <strong>Please note, if you fail to bring these documents and copies with you, the interview may not take place</strong>. Copies of the above are a legal requirement.</p>
											<p>If you have recently sat your exams and do not have your certificates yet, then you may be required to sit a short assessment for Maths and English as part of your interview to establish your level of ability.</p>
											<p>Please let the Store Manager know prior to your interview if you have any health issues or learning difficulties that need to be taken into account to ensure your interview is a fair reflection of your ability.</p>
											<p>If you have not heard anything within 14 days of receiving this email, contact the store directly on the number below.</p>
											<p>Telephone number: <u><?php echo $application->vacancy->getLocationTelephone($link); ?></u></p>
											<p>Good Luck!</p>
											<p>Apprenticeship Recruitment Team</p>
											<p><img title="<?php echo $this->client_name; ?>" src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $this->client_name; ?>" style="width: 100px;" /></p>
										</textarea>
									</td>
								</tr>
								<tr id="tr_interview_stage_candidate_email_unsuccessful" style="display: none;">
									<td class="fieldLabel">Email: <span style="font-size:smaller; color:gray;font-style:italic">email for the candidate</span></td>
									<td colspan="2">
										<textarea id="interview_stage_candidate_email_unsuccessful" name="interview_stage_candidate_email_unsuccessful" style="font-family:sans-serif; font-size:10pt" cols="100" rows="30" >
											<p>Dear <?php echo $application->candidate->firstnames . ' ' . $application->candidate->surname; ?>,</p>
											<p>Thank you for taking part in the recent video interview for the role of <span style="text-decoration: underline;"><?php echo $application->vacancy->vacancy_title; ?></span> in <u><?php echo $application->vacancy->getLocation($link); ?></u>.</p>
											<p>We have carefully reviewed your interview responses and while you provided us with some good answers, on this occasion you have been unsuccessful and we are unable to take you forward on to the next stage.</p>
											<p>We wish you the best of luck in your search for an Apprenticeship.</p>
											<p>Kind regards,</p>
											<p>Apprenticeship Recruitment Team</p>
											<p><img title="<?php echo $this->client_name; ?>" src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $this->client_name; ?>" style="width: 100px;" /></p>
										</textarea>
									</td>
								</tr>
								<tr id="tr_interview_stage_candidate_email_notcontactable" style="display: none;">
									<td class="fieldLabel">Email: <span style="font-size:smaller; color:gray;font-style:italic">email for the candidate</span></td>
									<td colspan="2">
										<textarea id="interview_stage_candidate_email_notcontactable" name="interview_stage_candidate_email_notcontactable" style="font-family:sans-serif; font-size:10pt" cols="100" rows="30" >
											<p>Dear <?php echo $application->candidate->firstnames . ' ' . $application->candidate->surname; ?>,</p>
											<p>Thank you for your application for the role of <span style="text-decoration: underline;"><?php echo $application->vacancy->vacancy_title; ?></span> in <u><?php echo $application->vacancy->getLocation($link); ?></u>.</p>
											<p>We have not received your video interview within the allocated timescale. Due to being unable to complete this stage we will not be taking your application further at this time.</p>
											<p>If you have experienced any technical difficulties with your video interview and still wish to be considered for this role, please email <a href="mailto:apprenticeships@uk.aswatson.com">apprenticeships@uk.aswatson.com</a> for further assistance, please include which vacancy you applied for.</p>
											<p>We wish you the best of luck with your search for an Apprenticeship.</p>
											<p>Kind regards,</p>
											<p>Apprenticeship Recruitment Team</p>
											<p><img title="<?php echo $this->client_name; ?>" src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $this->client_name; ?>" style="width: 100px;" /></p>
										</textarea>
									</td>
								</tr>
								<tr><td class="fieldLabel">Send Auto-Email:</td><td colspan="2"><?php echo HTML::select('yes_no_auto_email', $yes_no_options, '1'); ?></td></tr>
							</table>
							<p><span class="button" onclick="updateCandidateStatusToTelephoneInterviewed('<?php echo $application->id; ?>');" >Update to 'Telephone Interviewed'</span></p>
						</fieldset>
						</form>
					</td>
					<?php }elseif($application->current_status == RecCandidateApplication::TELEPHONE_INTERVIEWED && $application->telephone_interview_outcome == 'successful'){?>
					<td valign="top">
						<form id="frmCVSent" method="get" action="">
							<fieldset>
								<legend>Update Status to CV Sent</legend>
								<p><input type="checkbox" id="chkSendEmail" onclick="showDialogSendEmail();" />Send email to employer contacts</p>
								<table id="tblCVSent" style="display: none;">
									<tr><td class="fieldLabel">Receiver Name:</td><td><input type="text" name="receiverName" id="receiverName" value="" size="30" /></td></tr>
									<tr><td class="fieldLabel">Receiver Email:</td><td><input type="text" name="receiverEmail" id="receiverEmail" value="" size="30" /></td></tr>
									<tr><td></td><td><span style="color:gray;font-style:italic">enter semicolon separated email addresses if more than one</span></td></tr>
									<tr><td class="fieldLabel">Your Name:</td><td class="fieldValue"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td></tr>
									<tr><td class="fieldLabel">Your Email:</td><td><input type="text" name="senderEmail" id="senderEmail" value="<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>" size="30" /></td></tr>
									<tr><td class="fieldLabel">Subject:</td><td><input type="text" name="emailSubject" id="emailSubject" value="" size="30" /></td></tr>
									<tr><td class="fieldLabel" valign="top">Message:</td>
										<td>
											<textarea id="email_contents" name="email_contents" style="font-family:sans-serif; font-size:10pt" cols="100" rows="30" >
												<p>Store Management Team,</p>
												<p>Great news! We have shortlisted applications for your Apprentice vacancy in store, ready for you to interview. You can now view these on your iPad. For instructions on how to do this please see User guide on the intranet under Documents/Apprenticeships/Information.</p>
												<p><strong>Stage 1 - Invite to interview</strong></p>
												<p>All applicants have already taken part in a video interview, are eligible and are the strongest candidates. Contact <strong><u>ALL</u></strong> candidates by phone and invite to interview offering them a suitable time slot.</p>
												<p><strong>PLEASE CONTACT <u>ALL</u> THE CANDIDATES WITHIN THE NEXT 72HRS</strong></p>
												<p><strong>If you haven't already done so please remove the window poster and store in a safe place.</strong></p>
												<p><strong>Stage 2 - Interview</strong></p>
												<p>Please complete the full interview process as per the flowchart on page 1 of the Apprentice Interview script found on the intranet under Documents/Apprenticeships/Interview documents.</p>
												<p><strong>If there are comments in your candidates' notes on their application on the iPad stating "please complete initial assessments" then print these from the intranet and ensure the candidates complete these as part of their interview. </strong>You can find these under documents/apprenticeships/interview documents.</p>
												<p><strong>THEY MUST NOT GET HELP WITH THE QUESTIONS OR USE A CALCULATOR.</strong></p>
												<p><strong>Stage 3 - After Interview</strong></p>
												<p><strong>Update all candidates' status on the iPad and contact all of them, informing of next steps or giving feedback to unsuccessful candidates.</strong></p>
												<p><strong>For successful candidates(s) from stage 2 </strong>- Arrange a suitable time/date with the candidates for the Area Manager to <strong><u>meet</u></strong> them and conduct the final interview.</p>
												<p><strong>Once the final stage with your Area Manager is complete, update the candidate status on the iPad and contact <?php echo $this->contact_name; ?> on <?php echo $this->contact_telephone; ?> <u>BEFORE</u> agreeing a start date.</strong></p>
												<p>If you have any further questions at any point, please call <?php echo $this->contact_name; ?> on <?php echo $this->contact_telephone; ?>.</p>
												<p>Apprenticeship Recruitment Team</p>
												<p><img title="<?php echo $this->client_name; ?>" src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $this->client_name; ?>" style="width: 100px;" /></p>
											</textarea>
										</td>
									</tr>
									<tr><td colspan="2"><hr></td> </tr>
								</table>
								<p>
									Your Comments:<br>
									<textarea rows="10" cols="70" id="screening_comments"></textarea>
								</p>
								<p><span class="button" onclick="updateCandidateStatusToCVSent('<?php echo $application->id; ?>');" >Update to 'CV Sent'</span></p>
							</fieldset>
						</form>
					</td>
					<?php } elseif($application->current_status == RecCandidateApplication::CV_SENT){?>
						<td valign="top">
							<form id="frmInterviewStage" method="get" action="">
							<fieldset>
								<legend>Interview Stage</legend>
								<table cellpadding="6">
									<tr>
										<td class="fieldLabel_compulsory" valign="top">Your Comments:</td>
										<td><textarea rows="10" cols="70" id="screening_comments"><?php echo $this->getApplicationDecideLaterComments($link, $application); ?></textarea></td>
									</tr>
									<tr>
										<td class="fieldLabel_compulsory">Interview Outcome:</td>
										<td>
											<?php //pre($application->ftof_interview_level1);
												if(!is_null($application->ftof_interview_level1) && $application->ftof_interview_level1 != 'awaiting')
												{
													$yes_no_options = array(
														array('decidelater', 'Decide Later', ''),
														array('successfullevel2', 'Successful Area Manager Interview', ''),
														array('unsuccessful', 'Unsuccessful Interview', '')
													);
													echo HTML::select('interview_outcome', $yes_no_options, 'successfullevel2', false, true);
												}
												else
												{
													$yes_no_options = array(
														array('decidelater', 'Decide Later', ''),
														array('didnotattend', 'Did Not Attend', ''),
														array('withdraw', 'Withdraw', ''),
														array('successfullevel1', 'Successful Store Manager Interview', ''),
														array('successfullevel2', 'Successful Area Manager Interview', ''),
														array('unsuccessful', 'Unsuccessful Interview', '')
													);
													echo HTML::select('interview_outcome', $yes_no_options, 'successfullevel1', false, true);
												}
											?>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><p><span class="button" onclick="updateCandidateInterviewStatus('<?php echo $application->id; ?>');" > &nbsp;Save &nbsp;</span></p></td>
									</tr>
									<tr><td colspan="2" style="font-weight: bold;">Please contact ALL your candidates by phone to provide feedback and contact <u><?php echo $this->contact_name; ?></u> on <u><?php echo $this->contact_telephone; ?></u> to update on your interviews.</td></tr>
								</table>
							</fieldset>
							</form>
						</td>
					<?php } elseif($application->current_status == RecCandidateApplication::INTERVIEW_SUCCESSFUL){?>
						<td valign="top">
							<form id="frmConvertLearner" method="get" action="">
							<fieldset>
								<legend>Convert Candidate to Sunesis Learner</legend>
								<table cellpadding="6">
									<tr>
										<td class="fieldLabel_optional" valign="top">Assessor:</td>
										<td><?php echo HTML::select('assessor', $assessorsDDL, '', true, false); ?></td>
									</tr>
									<tr>
										<td></td>
										<td><span style="color:gray;font-style:italic; font-size: smaller;">System will only send an auto email to the selected assessor, if there exists a work email address for an assessor.</span></td>
									</tr>
									<tr>
										<td class="fieldLabel_optional" valign="top">CC:</td>
										<td>
											<input type="text" name="conversion_email_cc" id="conversion_email_cc" size="60" />
											<span style="color:gray;font-style:italic; font-size: smaller;">comma separated</span>
										</td>
									</tr>
									<tr>
										<td class="fieldLabel_compulsory" valign="top">Your Comments:<br><span style="font-size:smaller; color:gray;font-style:italic">comments will be stored against this application</span></td>
										<td><textarea rows="10" cols="70" id="screening_comments"></textarea></td>
									</tr>
									<tr>
										<td class="fieldLabel_compulsory" valign="top">Assessor Email:<br><span style="font-size:smaller; color:gray;font-style:italic">email which will be sent to the selected assessor</span></td>
										<td><textarea id="assessor_email_contents" name="assessor_email_contents" style="font-family:sans-serif; font-size:10pt" cols="100" rows="30" >Select assessor to load an email template</textarea></td>
									</tr>
									<tr>
										<td></td>
										<td><p><span class="button" onclick="convertToSunesisLearner('<?php echo $application->id; ?>');" > &nbsp;Convert &nbsp;</span></p></td>
									</tr>
								</table>
							</fieldset>
							</form>
						</td>
					<?php } elseif($application->current_status == RecCandidateApplication::INTERVIEW_UNSUCCESSFUL) {?>
					<td valign="top">
						<fieldset>
							<legend>Candidate unsuccessful</legend>
							<p>This candidate application was unsuccessful.</p>
						</fieldset>
					</td>
					<?php } elseif($application->current_status == RecCandidateApplication::SUNESIS_LEARNER) {?>
					<td valign="top">
						<fieldset>
							<legend>Candidate is converted into Sunesis Learner</legend>
							<p>Candidate application was successful, and the candidate has been converted into Sunesis Learner.</p><p>Please use the Learners menu to find the leanrer record.</p>
						</fieldset>
					</td>
					<?php } ?>
				</tr>
			</table>
		</div>

		<div id="tab2">
			<div align="left" id="list2">
				<ul>
					<?php echo $this->renderApplicationHistory($link, $application); ?>
				</ul>
			</div>
		</div>

		<?php if($_SESSION['user']->isAdmin()){ ?>
		<div id="tab3">
			<?php echo $this->renderApplicationScreeningHistory($link, $application); ?>
		</div>
		<?php } ?>

		<div id="tab4">
			<?php echo $this->renderApplicationTelephoneInterviewHistory($link, $application); ?>
		</div>

		<?php if($_SESSION['user']->isAdmin()) { ?>
		<div id="tab5">
			<?php echo $this->renderCandidateLRSHistory($link, $application); ?>
		</div>
		<?php } ?>		

	</div>

</div>
<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		mode: "specific_textareas",
		selector : "#email_contents,#interview_stage_candidate_email_successful,#interview_stage_candidate_email_unsuccessful,#assessor_email_contents,#interview_stage_candidate_email_notcontactable",
		theme: "modern",
		oninit : "setPlainText",
		menubar : false,
		plugins : "paste"
	});

	function setPlainText() {
		var ed = tinyMCE.get('elm1');

		ed.pasteAsPlainText = true;

		//adding handlers crossbrowser
		if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
			ed.onKeyDown.add(function (ed, e) {
				if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
					ed.pasteAsPlainText = true;
			});
		} else {
			ed.onPaste.addToTop(function (ed, e) {
				ed.pasteAsPlainText = true;
			});
		}
	}
</script>
<?php if(!$_SESSION['user']->isAdmin()){?>
<div id="footer">
	<span style="float: left; text-align: left;" ><?php echo date('D, d M Y H:i:s T'); ?></span>
	<span style="float: right; text-align: right;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</span>
	<span style="float: right"><img src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $this->client_name; ?>" style="box-shadow:2px 3px 6px #ccc; border-radius: 6px;" />
</div>
<?php } ?>

<script type="text/javascript">
	$(function(){
		$(document).keydown(function (event) {
			if (event.which === 8 && !$(event.target).is("input, textarea")) {
				event.preventDefault();
			}
		});
	});

	var phpCandidateULN = '<?php echo $candidate->l45; ?>';
	$('#btnGetLearnerLearningEvents').click(function(){
		if(window.phpCandidateULN == '' && $('#l45').val() == '')
		{
			alert('ULN is required in order to download the achievement results.\r\n Please download the ULN by clicking \'Download ULN\' button.');
			return false;
		}
		var l45 = $('#l45').val();
		if(l45 == '')
			l45 = window.phpCandidateULN;

		//7663891863
		var candidate_id = '<?php echo $application->candidate->id; ?>';

		$("#btnGetLearnerLearningEvents").attr('class', '');
		$("#btnGetLearnerLearningEvents").html('<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;" />');

		var firstnames = "<?php echo htmlspecialchars((string)$candidate->firstnames); ?>";
		var surname = "<?php echo htmlspecialchars((string)$candidate->surname); ?>";
		var postcode = '<?php echo $candidate->postcode; ?>';
		var dob = '<?php echo $candidate->dob; ?>';
		var gender = '<?php echo $candidate->gender; ?>';

		var postData = '&subaction=getLearnerLearningEvents' +
				'&candidate_id=' + (candidate_id) +
				'&l45=' + (l45)
			;

		var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application'+postData, null, null, getLearnerLearningEventsCallback);

	});

	function getLearnerLearningEventsCallback(req)
	{
		console.log(req.responseText);
		$("#btnGetLearnerLearningEvents").html('Get Achievement From LRS');
		$("#btnGetLearnerLearningEvents").attr('class', 'button');

		window.location.reload();
	}

	$('#btnDownloadULN').click(function(){

		var candidate_id = '<?php echo $application->candidate->id; ?>';

		$("#btnDownloadULN").attr('class', '');
		$("#btnDownloadULN").html('<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;" />');

		var firstnames = "<?php echo htmlspecialchars((string)$candidate->firstnames); ?>";
		var surname = "<?php echo htmlspecialchars((string)$candidate->surname); ?>";
		var postcode = '<?php echo $candidate->postcode; ?>';
		var dob = '<?php echo $candidate->dob; ?>';
		var gender = '<?php echo $candidate->gender; ?>';

		var postData = '&subaction=searchByDemographics' +
				'&find_type=FUL' +
				'&firstnames=' + (firstnames) +
				'&surname=' + (surname) +
				'&home_postcode=' + (postcode) +
				'&dob=' + (dob) +
				'&gender=' + (gender)
			;

		var client = ajaxRequest('do.php?_action=ajax_download_uln_from_lrs'+postData, null, null, loadCommands_callback1);


	});

	function loadCommands_callback1(req)
	{
		$("#btnDownloadULN").html('Download ULN From LRS');
		$("#btnDownloadULN").attr('class', 'button');
		if(req.responseText.search('WSRC0004') != -1)
		{
			$("input[name='l45']").val(req.responseText.replace('(WSRC0004)', ''));
			saveCandidateULN();
		}
		else if(req.responseText.search('WSRC0001') != -1 || req.responseText.search('WSRC0002') != -1 || req.responseText.search('WSRC0003') != -1)
		{
			if(req.responseText.search('WSRC0001') != -1) // no record found
			{
				alert('No record found in LRS.');
			}
			else
			{
				$("<div></div>").html(req.responseText).dialog({
					id: "dlg_lrs_result",
					title: "LRS webservice Result",
					resizable: false,
					modal: true,
					width: 750,
					height: 500,

					buttons: {
						'Close': function() {$(this).dialog('close');}
					}
				});
			}
		}
		else
		{
			$("<div></div>").html(req.responseText).dialog({
				id: "dlg_lrs_result",
				title: "LRS webservice Result",
				resizable: false,
				modal: true,
				width: 750,
				height: 500,

				buttons: {
					'Close': function() {$(this).dialog('close');}
				}
			});
		}
	}

	function saveCandidateULN()
	{
		var l45 = $("input[name='l45']").val();
		if(l45 == '')
			return;
		var candidate_id = '<?php echo $candidate->id; ?>';
		var postData = '&subaction=saveCandidateULN' +
				'&candidate_id=' + candidate_id +
				'&l45=' + l45
			;
		var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application'+postData);
	}

	function assessor_onchange(assessor)
	{
		tinymce.get('assessor_email_contents').getBody().innerHTML = '';

		if(assessor.value == '')
			return;

		if($("#assessor option:selected").text().indexOf('---NO EMAIL ADDRESS---') >= 0)
		{
			alert('No email address exists in the system for this assessor, email template will not be loaded');
			return;
		}

		$.ajax({
			type:'GET',
			url:'do.php?_action=rec_view_edit_vacancy_application&subaction=getAssessorEmailText',
			data: { application_id: window.phpApplicationID, assessor_id: assessor.value },
			async: false,
			beforeSend: function(){
				$(".loading-gif").show();
			},
			success: function(result) {
				tinymce.get('assessor_email_contents').getBody().innerHTML = result;
				$(".loading-gif").hide();
			},
			error: function(error){
				console.log(error);
				$(".loading-gif").hide();
			}
		});
	}

	function convertToSunesisLearner(application_id)
	{
		if(!confirm('Are you sure?'))
		{
			return false;
		}
		if(window.phpVacancyIsFull == '1')
		{
			alert('This vacancy is full, candidate cannot be converted into Sunesis Learner');
			return false;
		}

		tinymce.triggerSave();

		$.ajax({
			type:'POST',
			url:'do.php?_action=rec_view_edit_vacancy_application&subaction=convertToSunesisLearner',
			data: {
				assessor: $('#frmConvertLearner #assessor').val(),
				screening_comments: $('#frmConvertLearner #screening_comments').val(),
				conversion_email_cc: $('#conversion_email_cc').val(),
				assessor_email_contents: $('#assessor_email_contents').val(),
				application_id: window.phpApplicationID
			},
			async: false,
			beforeSend: function(){
				$(".loading-gif").show();
			},
			success: function(result) {
				$(".loading-gif").hide();
				window.location.href = 'do.php?_action=rec_view_vacancy_applications&id=<?php echo $application->vacancy_id; ?>' + '&status=5'
			},
			error: function(error){
				console.log(error);
				$(".loading-gif").hide();
			}
		});
	}
</script>
</body>
</html>
