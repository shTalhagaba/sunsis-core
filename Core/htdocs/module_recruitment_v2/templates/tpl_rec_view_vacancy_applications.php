<?php /* @var $view ViewVacancyApplications */ ?>
<?php /* @var $vacancy RecVacancy */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $vacancy->vacancy_title . ' - ' . $status_desc; ?></title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script><script src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>
<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
var calPop = new CalendarPopup();
calPop.showNavigationDropdowns();
	<?php } else { ?>
var calPop = new CalendarPopup("calPop1");
calPop.showNavigationDropdowns();
document.write(getCalendarStyles());
	<?php } ?>

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}
var phpVacancyID = '<?php echo $vacancy->id; ?>';
</script>
	<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinymce.init({
			mode: "specific_textareas",
			selector : "#email_contents, #email_contents_dialogMultiUpdateFromNotScreenedToRejected, #email_contents_dialogMultiUpdateFromScreenedToRejected",
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

	<style type="text/css">
		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}
		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}
		.ui-tabs-nav li.ui-tabs-close-button {
			float: right;
			margin-top: 3px;
		}
		.selectedMenuButton {
			border: 2px solid #0000ff;
		}
	</style>
</head>

<body onload="$('.loading-gif').hide();">
<div class="banner">
	<div class="Title"><?php echo $vacancy->vacancy_title . ' - ' . $status_desc; ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.replace('do.php?_action=rec_view_vacancies');">Close</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" value="rec_view_vacancy_applications" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="status" value="<?php echo $status; ?>" />
		<table>
			<tr>
				<td>Candidate firstname(s): </td>
				<td><?php echo $view->getFilterHTML('filter_firstnames'); ?></td>
			</tr>
			<tr>
				<td>Candidate surname: </td>
				<td><?php echo $view->getFilterHTML('filter_surname'); ?></td>
			</tr>
			<tr>
				<td>Records per page: </td>
				<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
			</tr>
			<tr>
				<td>Sort by:</td>
				<td><?php echo $view->getFilterHTML('order_by'); ?></td>
			</tr>
		</table>
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
	</form>
</div>

<?php include "rec_include_vacancy_navigator.php"; ?>

<?php if($top_message != ''){ echo '<table style="width: 100%; margin:10px;background-color:red; border:1px solid black;padding:1px; border-radius: 15px;"><tr valign="top"><td bgcolor="red" colspan="2" align="center" style="font-size: 100%;">' . $top_message . '</td></tr></table>'; }?>

<div>
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>

<table>
	<tr valign="top">
		<?php if($_SESSION['user']->isAdmin()){?>
		<td valign="top">
			<fieldset>
				<legend>Details</legend>
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
						<td valign="top" class="fieldLabel">Employer/Store:</td>
						<td class="fieldValue"><a href="do.php?_action=rec_read_employer&id=<?php echo $vacancy->employer_id; ?>"><?php echo ($vacancy->getEmployerName($link)); ?></a></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Employer/Store Location:</td>
						<td class="fieldValue"><?php echo ($vacancy->getLocation($link)); ?></td>
					</tr>
					<?php if($_SESSION['user']->isAdmin()){?>
					<tr>
						<td colspan="2">
							<div class="chart-panel-body " id="graphApplicationsByStatus"></div>
						</td>
					</tr>
					<?php } ?>
				</table>
			</fieldset>
		</td>
		<?php } ?>
		<td valign="top">
			<div align="center">
				<?php echo $view->render($link, $status); ?>
			</div>
		</td>
	</tr>
</table>

<script type="text/javascript">
	function displayCandidateDetail(application_id)
	{
		window.location.href = 'do.php?_action=rec_view_edit_vacancy_application&id=' + application_id;
	}

</script>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script type="text/javascript">
	var chart1;
	$.ajax({
		url:'do.php?_action=rec_view_vacancy&subaction=graphApplicationsByStatus&id=<?php echo $vacancy->id; ?>',
		type:"GET",
		async:true,
		beforeSend:function (data) {
			$("#graphApplicationsByStatus").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
		},
		success:function (response) {
			drawGraphApplicationsByStatus(JSON.parse(response));
		}
	});
	function drawGraphApplicationsByStatus(data){
		var options = {
			chart: {
				renderTo: 'graphApplicationsByStatus',
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 15,
					beta: 8,
					depth: 50,
					viewDistance: 25
				},
				height: 350
			},
			title: {
				text: 'Applications By Status',
				x: -20 //center
			},
			subtitle: {
				text: '',
				x: -20
			},
			xAxis: {
				categories: []
			},
			yAxis: {
				title: {
					text: 'Applications'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				formatter: function() {
					return '<b>'+ this.series.name +'</b><br/>'+
						this.x +': '+ this.y;
				}
			},
			plotOptions: {
				column: {
					dataLabels: {
						enabled: true
					}
				}
			},

			series: [{
				type: 'column',
				name: 'Applications By Status'
			}]
		}
		options.xAxis.categories = data[0]['data'];
		options.series[0] = data[1];
		if (chart1!==undefined) chart1.destroy();
		chart1 = new Highcharts.Chart(options);
	}
	var phpStatus = '<?php echo $status; ?>';
	$(function(){
		if(window.phpStatus == '0')
			$('#btnNotScreened').attr("class","selectedMenuButton");
		else if(window.phpStatus == '1')
			$('#btnScreened').attr("class","selectedMenuButton");
		else if(window.phpStatus == '2')
			$('#btnTelephonicInterviewed').attr("class","selectedMenuButton");
		else if(window.phpStatus == '3')
			$('#btnCVSent').attr("class","selectedMenuButton");
		else if(window.phpStatus == '4')
			$('#btnInterviewSuccessful').attr("class","selectedMenuButton");
		else if(window.phpStatus == '5')
			$('#btnInterviewUnsuccessful').attr("class","selectedMenuButton");
		else if(window.phpStatus == '6')
			$('#btnSunesisLearner').attr("class","selectedMenuButton");
		else if(window.phpStatus == '98')
			$('#btnWithdrawn').attr("class","selectedMenuButton");
		else if(window.phpStatus == '99')
			$('#btnRejected').attr("class","selectedMenuButton");

		$('#dialogMultiUpdate').dialog({
			modal: true,
			width: 750,
			height: 850,
			closeOnEscape: true,
			autoOpen: false,
			resizable: true,
			draggable: true,
			buttons: {
				'Update to CV Sent': function() {
					var app_ids = new Array();
					$("input[id^='checkbox_']").each(function (i, el) {
						if(el.checked)
							app_ids.push(el.value);
					});

					if(app_ids.length == 0)
					{
						alert('No application selected');
						$('#divForm').hide();
						return;
					}

					$('#divForm').hide();
					$('#divProgress').show();

					$("#selected_application_ids").val(app_ids.join(","));
					var sendEmail = 0;
					if($('#chkSendEmail').attr('checked'))
						sendEmail = 1;

					tinymce.triggerSave();
					var parameters = '&subaction=update_application_status_to_cv_sent' +
							'&comments=' + $('#multi_select_screening_comments').val() +
							'&send_email=' + sendEmail +
							'&email_contents=' + $('#email_contents').val() +
							'&selected_application_ids=' + app_ids.join(",")
						;

					var client = ajaxRequest('do.php?_action=rec_view_vacancy_applications'+parameters, null, null, dialogMultiUpdateCallback);
				},
				'Close': function() {$(this).dialog('close');}
			}
		});

		$('#dialogMultiUpdateFromNotScreenedToRejected').dialog({
			modal: true,
			width: 500,
			height: 500,
			closeOnEscape: true,
			autoOpen: false,
			resizable: true,
			draggable: true,
			buttons: {
				'Reject': function() {
					var app_ids = new Array();
					$("input[id^='checkboxDialogMultiUpdateFromNotScreenedToRejected_']").each(function (i, el) {
						if(el.checked)
							app_ids.push(el.value);
					});
					if(app_ids.length == 0)
					{
						alert('No application selected');
						$('#divForm').hide();
						return;
					}
					$('#divFormMultiUpdateFromNotScreenedToRejected').hide();
					$('#divProgressDialogMultiUpdateFromNotScreenedToRejected').show();
					$("#selected_application_ids").val(app_ids.join(","));
					tinymce.triggerSave();
					var parameters = '&subaction=update_application_status_to_rejected' +
							'&comments=' + $('#multi_select_screening_comments_dialogMultiUpdateFromNotScreenedToRejected').val() +
							'&selected_application_ids=' + app_ids.join(",")
						;
					var client = ajaxRequest('do.php?_action=rec_view_vacancy_applications'+parameters, null, null, dialogMultiUpdateFromNotScreenedToRejectedCallback);
				},
				'Close': function() {$(this).dialog('close');}
			}
		});

		$('#dialogMultiUpdateFromScreenedToRejected').dialog({
			modal: true,
			width: 500,
			height: 500,
			closeOnEscape: true,
			autoOpen: false,
			resizable: true,
			draggable: true,
			buttons: {
				'Reject': function() {
					var app_ids = new Array();
					$("input[id^='checkboxDialogMultiUpdateFromScreenedToRejected_']").each(function (i, el) {
						if(el.checked)
							app_ids.push(el.value);
					});

					if(app_ids.length == 0)
					{
						alert('No application selected');
						$('#divForm').hide();
						return;
					}

					$('#divFormMultiUpdateFromScreenedToRejected').hide();
					$('#divProgressDialogMultiUpdateFromScreenedToRejected').show();

					$("#selected_application_ids").val(app_ids.join(","));

					tinymce.triggerSave();
					var parameters = '&subaction=update_application_status_to_rejected' +
							'&comments=' + $('#multi_select_screening_comments_dialogMultiUpdateFromScreenedToRejected').val() +
							'&selected_application_ids=' + app_ids.join(",")
						;

					var client = ajaxRequest('do.php?_action=rec_view_vacancy_applications'+parameters, null, null, dialogMultiUpdateFromScreenedToRejectedCallback);
				},
				'Close': function() {$(this).dialog('close');}
			}
		});

	});

	function dialogMultiUpdateCallback(client)
	{
		if(client != null)
		{
			$('#divProgress').hide();
			$('#divForm').show();
			$('#dialogMultiUpdate').dialog('close');
			window.location.reload();
		}
		else
		{
			$('#dialogMultiUpdate').html(client.responseText);
		}
	}

	function dialogMultiUpdateFromNotScreenedToRejectedCallback(client)
	{
		if(client != null)
		{
			$('#divProgressDialogMultiUpdateFromNotScreenedToRejected').hide();
			$('#divFormMultiUpdateFromNotScreenedToRejected').show();
			$('#dialogMultiUpdateFromNotScreenedToRejected').dialog('close');
			window.location.reload();
		}
		else
		{
			$('#dialogMultiUpdateFromNotScreenedToRejected').html(client.responseText);
		}
	}

	function dialogMultiUpdateFromScreenedToRejectedCallback(client)
	{
		if(client != null)
		{
			$('#divProgressDialogMultiUpdateFromScreenedToRejected').hide();
			$('#divFormMultiUpdateFromScreenedToRejected').show();
			$('#dialogMultiUpdateFromScreenedToRejected').dialog('close');
			window.location.reload();
		}
		else
		{
			$('#dialogMultiUpdateFromScreenedToRejected').html(client.responseText);
		}
	}

	function openDialogMultiUpdate()
	{
		$('.loading-gif').show();

		var app_ids = new Array();
		$("input[id^='checkbox_']").each(function (i, el) {
			if(el.checked)
				app_ids.push(el.value);
		});

		if(app_ids.length == 0)
		{
			alert('No application selected');
			$('.loading-gif').hide();
			return;
		}

		var client = ajaxRequest('do.php?_action=rec_view_vacancy_applications&subaction=getCVSentEmailText&app_ids='+app_ids.join(','));
		if(client.responseText != '')
		{
			tinymce.get('email_contents').getBody().innerHTML = client.responseText;
		}

		var $dialog = $('#dialogMultiUpdate');

		$dialog.dialog("open");

		$('.loading-gif').hide();

	}

	function openDialogMultiUpdateFromNotScreenedToRejected()
	{
		$('.loading-gif').show();

		var app_ids = new Array();
		$("input[id^='checkboxDialogMultiUpdateFromNotScreenedToRejected_']").each(function (i, el) {
			if(el.checked)
				app_ids.push(el.value);
		});

		if(app_ids.length == 0)
		{
			alert('No application selected');
			$('.loading-gif').hide();
			return;
		}

		var $dialog = $('#dialogMultiUpdateFromNotScreenedToRejected');

		$dialog.dialog("open");

		$('.loading-gif').hide();

	}

	function openDialogMultiUpdateFromScreenedToRejected()
	{
		$('.loading-gif').show();

		var app_ids = new Array();
		$("input[id^='checkboxDialogMultiUpdateFromScreenedToRejected_']").each(function (i, el) {
			if(el.checked)
				app_ids.push(el.value);
		});

		if(app_ids.length == 0)
		{
			alert('No application selected');
			$('.loading-gif').hide();
			return;
		}

		var $dialog = $('#dialogMultiUpdateFromScreenedToRejected');

		$dialog.dialog("open");

		$('.loading-gif').hide();

	}

	function rejectApplicationAfterTelephoneInterview(application_id)
	{
		if(!confirm('Are you sure?'))
			return;

		var parameters = '&application_id=' + application_id +
			'&after_interview=1' +
			'&screening_comments=' + $('#frmScreening #screening_comments').val();

		$('.loading-gif').show();

		var client = ajaxRequest('do.php?_action=rec_view_edit_vacancy_application&subaction=rejectApplication'+parameters, null, null, rejectApplicationAfterTelephoneInterviewCallback);
	}

	function rejectApplicationAfterTelephoneInterviewCallback(request)
	{
		$('.loading-gif').hide();
		if(request.status == 200)
		{
			window.location.reload();
		}
		else
		{
			alert(request.responseText);
		}
	}

	function checkbox_onclick(ele)
	{
		var tr_id = ele.id.split('_');
		if(ele.checked)
			$('#tr_app_'+tr_id[1]).css('background-color', '#D3D3D3');
		else
		{
			$('#global').attr('checked', false);
			$('#tr_app_'+tr_id[1]).css('background-color', '');
		}
	}

	function checkboxDialogMultiUpdateFromNotScreenedToRejected_onclick(ele)
	{
		var tr_id = ele.id.split('_');
		if(ele.checked)
			$('#tr_app_'+tr_id[1]).css('background-color', '#D3D3D3');
		else
		{
			$('#global').attr('checked', false);
			$('#tr_app_'+tr_id[1]).css('background-color', '');
		}
	}

	function checkboxDialogMultiUpdateFromScreenedToRejected_onclick(ele)
	{
		var tr_id = ele.id.split('_');
		if(ele.checked)
			$('#tr_app_'+tr_id[1]).css('background-color', '#D3D3D3');
		else
		{
			$('#global').attr('checked', false);
			$('#tr_app_'+tr_id[1]).css('background-color', '');
		}
	}

	function checkAll(t)
	{
		$("input[id^='checkbox_']").each(function (i, el) {
			if(t.checked == true)
			{
				$('#'+el.id).attr('checked', true);
				$('#tr_app_'+el.id.split('_')[1]).css('background-color', '#D3D3D3');
			}
			else
			{
				$('#'+el.id).attr('checked', false);
				$('#tr_app_'+el.id.split('_')[1]).css('background-color', '');
			}
		});
	}

	function checkAllDialogMultiUpdateFromNotScreenedToRejected(t)
	{
		$("input[id^='checkboxDialogMultiUpdateFromNotScreenedToRejected_']").each(function (i, el) {
			if(t.checked == true)
			{
				$('#'+el.id).attr('checked', true);
				$('#tr_app_'+el.id.split('_')[1]).css('background-color', '#D3D3D3');
			}
			else
			{
				$('#'+el.id).attr('checked', false);
				$('#tr_app_'+el.id.split('_')[1]).css('background-color', '');
			}
		});
	}

	function checkAllDialogMultiUpdateFromScreenedToRejected(t)
	{
		$("input[id^='checkboxDialogMultiUpdateFromScreenedToRejected_']").each(function (i, el) {
			if(t.checked == true)
			{
				$('#'+el.id).attr('checked', true);
				$('#tr_app_'+el.id.split('_')[1]).css('background-color', '#D3D3D3');
			}
			else
			{
				$('#'+el.id).attr('checked', false);
				$('#tr_app_'+el.id.split('_')[1]).css('background-color', '');
			}
		});
	}

	function moveBackApplication(application_id, current_status, new_status)
	{
		if(!confirm('Are you sure you want to move this application from "'+current_status+'" to "'+new_status+'"'))
			return false;

		$('.loading-gif').show();
		var parameters = '&application_id=' + application_id;

		var client = ajaxRequest('do.php?_action=rec_view_vacancy_applications&subaction=moveBackApplication'+parameters, null, null, moveBackApplicationCallback);
	}

	function moveBackApplicationCallback(client)
	{
		$('.loading-gif').hide();
		if(client.status == 200)
		{
			window.location.reload();
		}
		else
		{
			alert(request.responseText);
		}
	}
</script>

<div id="dialogMultiUpdate" title="Update Status to CV Sent" style="width: 750px; height: 500px;" >
	<div id="divForm">
		<p style="font-size:smaller;">This action will update the status of all your selected applications. </p>
		<p style="font-size:smaller;">If you select email checkbox, system will send one standard email containing the information of all selected applications to employer contact if an email address is available. </p>
		<p style="font-size:smaller;">Comments will be stored against each of the selected applications and will not be sent in the email. </p>
		<p class="fieldValue"><input type="checkbox" id="chkSendEmail" />Send email to employer contact</p>
		<p><span id="ttt"></span> </p>
		<p class="fieldLabel_compulsory">
			Your Comments: <span style="font-size:smaller; color:gray;font-style:italic">notes to save for each selected application</span><br>
			<textarea rows="6" cols="100" id="multi_select_screening_comments"></textarea>
		</p>
		<p class="fieldLabel_compulsory">
			Store Email: <span style="font-size:smaller; color:gray;font-style:italic">email which will be sent to the store manager</span><br>
			<textarea id="email_contents" name="email_contents" style="font-family:sans-serif; font-size:10pt" cols="100" rows="30" ></textarea>
		</p>
	</div>
	<div id="divProgress" style="display: none; width: 50%;margin: 0 auto; ">
		<img id="globe1" src="/images/progress-animations/loading51.gif" style="vertical-align:text-bottom;" />
		<p>Updating ...</p>
	</div>
</div>
<div id="dialogMultiUpdateFromNotScreenedToRejected" title="Update Status to Reject" style="width: 500px; height: 500px;" >
	<div id="divFormMultiUpdateFromNotScreenedToRejected">
		<p style="font-size:smaller;">This action will update the status of all your selected applications to 'Rejected'. </p>
		<p style="font-size:smaller;">Candidates of all selected applications will receive 'Reject Before Telephone Interview' email. </p>
		<p style="font-size:smaller;">Comments will be stored against each of the selected applications and will not be sent in the email. </p>
		<p class="fieldLabel_compulsory">
			Your Comments: <span style="font-size:smaller; color:gray;font-style:italic">notes to save for each selected application</span><br>
			<textarea rows="6" cols="60" id="multi_select_screening_comments_dialogMultiUpdateFromNotScreenedToRejected"></textarea>
		</p>
	</div>
	<div id="divProgressDialogMultiUpdateFromNotScreenedToRejected" style="display: none; width: 50%;margin: 0 auto; ">
		<img id="globe1" src="/images/progress-animations/loading51.gif" style="vertical-align:text-bottom;" />
		<p>Updating ...</p>
	</div>
</div>
<div id="dialogMultiUpdateFromScreenedToRejected" title="Update Status to Reject" style="width: 500px; height: 500px;" >
	<div id="divFormMultiUpdateFromScreenedToRejected">
		<p style="font-size:smaller;">This action will update the status of all your selected applications to 'Rejected'. </p>
		<p style="font-size:smaller;">Candidates of all selected applications will receive 'Reject Before Telephone Interview' email. </p>
		<p style="font-size:smaller;">Comments will be stored against each of the selected applications and will not be sent in the email. </p>
		<p class="fieldLabel_compulsory">
			Your Comments: <span style="font-size:smaller; color:gray;font-style:italic">notes to save for each selected application</span><br>
			<textarea rows="6" cols="60" id="multi_select_screening_comments_dialogMultiUpdateFromScreenedToRejected"></textarea>
		</p>
	</div>
	<div id="divProgressDialogMultiUpdateFromScreenedToRejected" style="display: none; width: 50%;margin: 0 auto; ">
		<img id="globe1" src="/images/progress-animations/loading51.gif" style="vertical-align:text-bottom;" />
		<p>Updating ...</p>
	</div>
</div>
<?php if(!$_SESSION['user']->isAdmin()){
	$logo = 'SUNlogo.jpg';
	$c_name = 'Perspective';
	if(DB_NAME == "am_superdrug")
	{
		$logo = 'superdrug.bmp';
		$c_name = 'Superdrug';
	}
	?>
<div id="footer">
	<span style="float: left; text-align: left;" ><?php echo date('D, d M Y H:i:s T'); ?></span>
	<span style="float: right; text-align: right;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</span>
	<span style="float: right"><img src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $c_name; ?>" style="box-shadow:2px 3px 6px #ccc; border-radius: 6px;" />
</div>
<?php } ?>
</body>
</html>