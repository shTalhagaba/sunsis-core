<?php /* @var $vo RecVacancy */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>View Vacancy</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>

	<script language="JavaScript">

		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
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
	</style>

	<script type="text/javascript">
		/*function selectAllOptions(ele, grid_id)
		{
			var grid = document.getElementById(grid_id);
			var grid_inputs = grid.getElementsByTagName('INPUT');
			for(var i = 0; i < grid_inputs.length; i++)
			{
				if(ele.checked)
					grid_inputs[i].checked = true;
				else
					grid_inputs[i].checked = false;
			}
		}
		function saveSectorQuestions(vacancy_id)
		{
			var grid_sector_questions = document.getElementById('grid_sector_questions');
			var selected_options = new Array();
			if(grid_sector_questions)
			{
				var grid_sector_questions_inputs = grid_sector_questions.getElementsByTagName('INPUT');
				for(var i = 0; i < grid_sector_questions_inputs.length; i++)
				{
					if(grid_sector_questions_inputs[i].checked)
						selected_options.push(grid_sector_questions_inputs[i].value);
				}
			}
			if(selected_options.length == 0)
				return;
			var client = ajaxRequest('do.php?_action=rec_read_vacancy&subaction=saveSectorQuestions&vacancy_id='+vacancy_id+'&question_ids='+JSON.stringify(selected_options));
			if(client.status == '200')
			{
				alert('Questions updated');
			}
			else
			{
				alert(client.responseText);
			}
		}*/
	</script>
</head>

<body onload="$('.loading-gif').hide();">
<div class="banner">
	<div class="Title">View Vacancy</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"> Close </button>
		<button onclick="window.location.href='do.php?_action=rec_edit_vacancy&employer_id=<?php echo $vo->employer_id; ?>&id=<?php echo $vo->id; ?>';"> Edit </button>
		<?php if(SOURCE_LOCAL || DB_NAME == "am_demo") { ?>
		<button onclick="window.location.href='do.php?_action=vacancy_advert'"> Create Advert </button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.location.href='do.php?_action=rec_read_vacancy&id=<?php echo $vo->id; ?>&export=pdf'" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div style = 'left : 50%;top : 50%;position : fixed;z-index : 101;width : 32px;height : 32px;margin-left : -16px;margin-top : -16px;'>
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>

<br>
<table cellpadding="6" cellspacing="6">
	<tr>
		<td class="fieldLabel">Vacancy Reference:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vacancy_reference); ?></td>
		<?php if($vo->uploaded_to_nas == '1'){?>
		<td title="This vacancy is uploaded to NAS via Sunesis." valign="top" rowspan="3"><img src="/images/logos/app_logo.jpg" height="50" alt="Uploaded to NAS"></td>
		<?php } ?>
	</tr>
</table>
<table>
	<tr valign="top">
		<td valign="top">
			<fieldset>
				<legend>Dates</legend>
				<table cellpadding="6">
					<tr>
						<td class="fieldLabel">Possible Start Date:</td><td class="fieldValue"><?php echo Date::toShort($vo->possible_start_date); ?></td>
						<td class="fieldLabel">Interview From Date:</td><td class="fieldValue"><?php echo Date::toShort($vo->interview_from_date); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Closing Date:</td><td class="fieldValue"><?php echo Date::toShort($vo->closing_date); ?></td>
						<td class="fieldLabel">Expected Duration:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->expected_duration); ?></td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td>
			<fieldset>
				<legend>NAS - National Apprenticeship Service</legend>
				<?php if(SystemConfig::getEntityValue($link, 'nas.soap.enabled')){?>
				<table cellspacing="0" cellpadding="6">
					<tr>
						<td class="fieldLabel">Uploaded:</td>
						<td class="fieldValue"><?php echo $vo->uploaded_to_nas == '1'?'Yes':'No'; ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">NAS Vacancy GUID:</td>
						<td class="fieldValue"><?php echo $vo->vacancy_guid == ''?'<span class="button" onclick="uploadVacancyToNAS('.$vo->id.');"> &nbsp; Upload &nbsp; </span>':$vo->vacancy_guid; ?></td>
					</tr>
				</table>
				<?php } else {?>
					<p>Not enabled</p>
				<?php } ?>
			</fieldset>
		</td>
<!--		
		<td>
			<fieldset>
				<legend>NAS - National Apprenticeship Service</legend>
				<table cellspacing="0" cellpadding="6">
					<tr>
						<td class="fieldLabel">Uploaded:</td>
						<td class="fieldValue"><?php /*echo $vo->uploaded_to_nas == '1'?'Yes':'No'; */?></td>
					</tr>
					<?php /*if($vo->uploaded_to_nas == '0' && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)){*/?>
					<tr>
						<td colspan="2"><span class="button" onclick="uploadVacancyToNAS('<?php /*echo $vo->id; */?>');">Upload</span></td>
					</tr>
					<?php /*} */?>
				</table>
			</fieldset>
		</td>
-->	
	</tr>
	<tr valign="top">
		<td valign="top">
			<fieldset>
				<legend>Information</legend>
				<table cellpadding="6">
					<col width="150" />
					<col width="300" />
					<tr>
						<td class="fieldLabel" valign="top">Active:</td>
						<td class="fieldValue"><?php echo $vo->is_active == '1' ? 'Yes' : 'No'; ?></td>
					</tr>
					<tr>
						<td class="fieldLabel" valign="top">Archived:</td>
						<td class="fieldValue"><?php echo $vo->is_archived == '1' ? 'Yes' : 'No'; ?></td>
					</tr>
					<tr>
						<td class="fieldLabel" valign="top">Number of Positions:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->no_of_positions); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Vacancy Title:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vacancy_title); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Provider:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$vo->provider_id}';")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Employer:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$vo->employer_id}';")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Vacancy Location:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy_location); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Framework:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = '{$vo->app_framework}';")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wage (�):</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->wage); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wage Type:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->wage_type); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wage Text:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->wage_text); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Working Week:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->working_week); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Contact Person:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->contact_person); ?></td>
					</tr>
				</table>
			</fieldset>
			<fieldset>
				<legend>Additional NAS Information</legend>
				<table cellpadding="6">
					<tr>
						<td class="fieldLabel">Location Type:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->location_type); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Supplementary Question1:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_vacancies_supp_questions WHERE id = '{$vo->suppl_q_1}'")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Supplementary Question2:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_vacancies_supp_questions WHERE id = '{$vo->suppl_q_2}'")); ?></td>
					</tr>
				</table>
			</fieldset>
			<!--<fieldset>
				<legend>Screening Questions</legend>
				<table cellpadding="6">
					<col width="50" />
					<col width="400" />
					<tr>
						<td class="fieldLabel_compulsory">Sector:</td>
						<td class="fieldValue"><?php /*echo htmlspecialchars(DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$vo->sector}'")); */?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory" valign="top">Sector Questions: &nbsp; <input type="checkbox" id="sector_questions_select_all" onclick="selectAllOptions(this, 'grid_sector_questions');" /></td>
						<td><?php /*echo HTML::checkboxGrid('sector_questions', $sector_questions_ddl, $selected_sector_questions, 1); */?></td>
					</tr>
					<tr><td colspan="2"><span class="button" onclick="saveSectorQuestions('<?php /*echo $vo->id; */?>');"> &nbsp; Save &nbsp; </span> </td> </tr>
				</table>
			</fieldset>-->
		</td>
		<td rowspan="4" valign="top">
			<fieldset>
				<legend>Details</legend>
				<table cellpadding="6">
					<col width="150" />
					<col width="500" />
					<tr>
						<td valign="top" class="fieldLabel">Short Description:</td>
						<td class="fieldValue"><?php echo ($vo->short_description); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Full Description:</td>
						<td class="fieldValue"><?php echo ($vo->full_description); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Person Qualities:</td>
						<td class="fieldValue"><?php echo ($vo->personal_qualities); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Qualifications Required:</td>
						<td class="fieldValue"><?php echo ($vo->qualifications_required); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Skills Required:</td>
						<td class="fieldValue"><?php echo ($vo->skills_required); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Other Information:</td>
						<td class="fieldValue"><?php echo ($vo->other_info); ?></td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>

<script>
	function uploadVacancyToNAS(vacancy_id)
	{
		$('.loading-gif').show();

		var parameters = '&vacancy_id=' + vacancy_id;

		var request = ajaxRequest('do.php?_action=rec_read_vacancy&subaction=uploadVacancyToNAS'+parameters, null, null, uploadVacancyToNASCallback);
	}

	function uploadVacancyToNASCallback(request)
	{
		$('.loading-gif').hide();
		var html = '';
		var response = request.responseText;
		//return console.log(response);
		if(request.status == '200')
		{
			if(response != 'success')
			{
				var xmlDoc = $.parseXML(response);
				var $xml = $(xmlDoc);
				var $error = $xml.find("error");
				var report = '';
				$error.each(function(){
					report += '<tr><td>' + $(this).find('ErrorCode').text() + '</td><td>' + $(this).find('ErrorDescription').text() + '</td></tr>';
				});
				if(report != '')
					html = '<table class="resultset" cellpadding="6"><caption><strong>Upload Failed</strong></caption><tr><th>Error Code</th><th>Error Description</th></tr>' + report + '</table>';
				else
					html = '<p><strong>Upload Failed</strong></p>' + response;
			}
			else
			{
				html = '<div style="position: absolute;top: 50%; left: 50%;margin-top: -50px;margin-left: -50px;width: 150px;height: 100px;"><strong>Upload Successful</strong><br><img width="100" height="100" src="/images/validate.png" alt="Successful" /></div>';
			}
		}
		else
		{
			html = response;
		}
		$("<div></div>").html(html).dialog({
			title: "NAS Vacancy Upload Result",
			resizable: false,
			modal: true,
			width: 500,
			height: 500,
			buttons: {
				"OK": function()
				{
					//$(this).dialog('close');
					window.location.reload();
				}
			}
		});
	}

</script>

</body>
</html>
