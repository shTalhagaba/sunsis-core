<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sunesis - Vacancies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>


	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>
	<script type="text/javascript" src="/scripts/read_vacancy.js?n=28"></script>
	<style type="text/css">
		#progress {
			position: absolute;
			height: 200px;
			width: 400px;
			margin: -100px 0 0 -200px;
			top: 50%;
			left: 50%;
		}
	</style>
	<script language="JavaScript">

		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}

		function vacancy_display() {
			if ( window.name == 'vacancy_screen' ) {
				showHideBlock('banner');
			}
		}

	</script>
</head>

<body onload="vacancy_display();">
<div class="banner">
	<div class="Title"><?php if($vo->active == 1){ echo 'Active '; }else{ echo 'Inactive '; }?>Vacancy</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=read_employer&id=<?php echo $vo->employer_id; ?>';"> Close </button>
		<?php if($_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<button onclick="window.location.href='do.php?_action=edit_vacancy&employer_id=<?php echo $vo->employer_id; ?>&id=<?php echo $vo->id; ?>';"> Edit </button>
		<button onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=delete_vacancy&emp_id=<?php echo $vo->employer_id; ?>&id=<?php echo rawurlencode($vo->id); ?>';">Delete </button>
		<?php } ?>
		<?php if(DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo") { ?>
		<button	onclick="window.open('do.php?vacancy_id=<?php echo $id; ?>&_action=baltic_pdf_from_vacancy');">Advertisement Form</button>
		<button id="audit_log_opener">Audit Log</button>
		<?php if((DB_NAME=="am_demo" || DB_NAME=="ams" || DB_NAME=="am_baltic" || DB_NAME=="am_ray_recruit"  || SystemConfig::getEntityValue($link, 'module_logic_melon')) && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER){?><button onclick="upload_vacancy_to_logic_melon(<?php echo $vo->id; ?>, '', '');">Upload to Logic Melon</button><?php } ?>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo") { ?>
		<button onclick="window.open('do.php?_action=read_vacancy&export=pdf&id=<?php echo $id; ?>', '_blank')" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php }else { ?>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php } ?>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<div id="div_filters" style="display:none">
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" value="read_vacancy" />
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
	</form>

</div>
<div class="loading-gif" id="progress" style="display:none" >
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>
<div id="logicMelonPanel" style="display:none">


</div>

<h3> Vacancy Details</h3>
<?php if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
{
	?>
<table border="0" cellspacing="4" cellpadding="4"  style="margin-left:10px">
<tr>
	<td class="fieldLabel">Vacancy URL to publish: </td>
	<td class="fieldValue" ><?php echo 'https://' . substr(DB_NAME, 3) . '.sunesis.uk.net/do.php?_action=vacancy_detail&id='.$vo->id; ?></td>
</tr>
<tr>
	<td class="fieldLabel">Creation Date: </td>
	<td class="fieldValue" ><?php echo date ( 'D, d M Y H:i:s T', strtotime ( $vo->created ) ); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Date Expected to Fill: </td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->date_expected_to_fill) ; ?></td>
</tr>
<tr>
	<td class="fieldLabel">Vacancy Code:</td>
	<td class="fieldValue">
		<?php echo htmlspecialchars((string)$vo->code); ?>
		<input type="hidden" name="code" value="<?php echo htmlspecialchars((string)$vo->code); ?>" />
	</td>
</tr>
<tr>
	<td class="fieldLabel">Employer:</td>
	<td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $vo->employer_id); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Delivery Location:</td>
	<?php if(isset($vo->location) && $vo->location != '') { ?>
	<td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(full_name, ' (', postcode, ')') FROM locations WHERE id = " . $vo->location); ?></td>
	<?php } else { ?>
	<td class="fieldValue">Location not provided</td>
	<?php } ?>
</tr>
<tr>
	<td class="fieldLabel">Client Contact Name:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->client_contact_name); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Client Contact Email:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->client_contact_email); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Client Contact Number:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->client_contact_number); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Region:</td>
	<?php if(isset($vo->region) && $vo->region != '') { ?>
	<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_regions WHERE id = " . $vo->region)); ?></td>
	<?php } else { ?>
	<td class="fieldValue"></td>
	<?php } ?>
</tr>
<tr>
	<td class="fieldLabel">Job Title:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->job_title); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Sector:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_type WHERE id = " . $vo->type)); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Apprenticeship Type:</td>
	<?php if(isset($vo->apprenticeship_type) && $vo->apprenticeship_type != '') { ?>
	<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_app_type WHERE id = " . $vo->apprenticeship_type)); ?></td>
	<?php }else{?>
	<td class="fieldValue"></td>
	<?php } ?>
</tr>
<tr>
	<td class="fieldLabel">Job Type:</td>
	<td class="fieldValue"><?php echo  isset($vo->job_type)?htmlspecialchars((string)$vacancy_job_type_dropdown[$vo->job_type]):$vo->job_type; ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Job Hours:</td>
	<td class="fieldValue"><?php echo  isset($vo->job_hours)?htmlspecialchars((string)$vacancy_job_hours_dropdown[$vo->job_hours]):$vo->job_hours; ?></td>
</tr>
<tr>
	<td class="fieldLabel"> No. of Vacancies:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->no_of_vacancies); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Weekly Wage:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->salary); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> No. hrs/wk:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->hrs_per_week); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Working Week:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->shift_pattern); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Proposed Interview Date:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->interview_date); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Induction Date:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->induction_date); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Closing Date:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->expiry_date); ?></td>
</tr>
<tr>

	<td class="fieldLabel"> Active Vacancy:</td>
	<?php
	if($vo->active == 1)
		$vo->active = 'Yes';
	elseif($vo->active == 0)
		$vo->active = 'No';
	else
		$vo->active = '';
	?>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->active); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Vacancy Status:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_status WHERE id = " . $vo->status)); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Source:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->source); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Business Resource Manager:</td>
	<td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname, ' (', username, ')') FROM users WHERE username = '" . $vo->brm . "'"); ?></td>
</tr>
<tr>
	<td class="fieldLabel" valign="top"> Further Progression:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT GROUP_CONCAT(description) FROM lookup_vacancy_app_type WHERE id IN (SELECT vacancy_app_id FROM vacancies_extra_progress WHERE vacancy_id = " . $vo->id . ")")); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Age Required:</td>
	<?php
	if($vo->age == 1)
		$vo->age = 'Yes';
	elseif($vo->age == 0)
		$vo->age = 0;
	else
		$vo->age = '';
	?>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->age); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> At Risk:</td>
	<?php
	if($vo->at_risk == 1)
		$vo->at_risk = 'Yes';
	elseif($vo->at_risk == 0)
		$vo->at_risk = 0;
	else
		$vo->at_risk = '';
	?>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->at_risk); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Due Diligence:</td>
	<?php
	if($vo->dd == 1)
		$vo->dd = 'Yes';
	elseif($vo->dd == 0)
		$vo->dd = 0;
	else
		$vo->dd = '';
	?>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->dd); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Induction Confirmed:</td>
	<?php
	if($vo->induction_confirmed == 1)
		$vo->induction_confirmed = 'Yes';
	elseif($vo->induction_confirmed == 0)
		$vo->induction_confirmed = 0;
	else
		$vo->induction_confirmed = '';
	?>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->induction_confirmed); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Inductor:</td>
	<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->inductor); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Job Description:</td>
	<td class="fieldValue"><?php echo ($vo->description); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Skills Required:</td>
	<td class="fieldValue"><?php echo ($vo->skills_req); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Training To Be Provided:</td>
	<td class="fieldValue"><?php echo ($vo->training_provided); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Qualifications Required:</td>
	<td class="fieldValue"><?php echo ($vo->required_quals); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Personal Qualities:</td>
	<td class="fieldValue"><?php echo ($vo->person_spec); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Future Prospects:</td>
	<td class="fieldValue"><?php echo ($vo->future_prospects); ?></td>
</tr>
<tr>
	<td class="fieldLabel">Important Other Information:</td>
	<td class="fieldValue"><?php echo ($vo->misc); ?></td>
</tr>
<tr>
	<td class="fieldLabel"> Additional Comments with dates/action plan:</td>
	<td class="fieldValue"><?php echo ($vo->comments); ?></td>
</tr>
</table>
	<?php }else{ ?>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="25" /><col />
	<tr>
		<td class="fieldLabel">Job Title:</td>
		<td class="fieldValue" ><?php echo htmlspecialchars((string)$vo->job_title); ?></td>
		<td class="fieldLabel">Vacancy Code:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->code); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Award to be completed:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$type_of_vacancy); ?></td>
		<td class="fieldLabel"> No. of Vacancies:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->no_of_vacancies); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel"> Proposed Interview Date:</td>
		<td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($vo->interview_date)); ?></td>
		<td class="fieldLabel"> Salary Information:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->salary); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel"> Location:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->postcode); ?></td>
		<td class="fieldLabel"> Active Vacancy:</td>
		<?php
		$vacancy_active = '';
		if($vo->active == 1)
			$vacancy_active = 'Active';
		else
			$vacancy_active = 'Inactive';
		?>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy_active); ?></td>
	</tr>
</table>
<table border="0" cellspacing="4" cellpadding="4">
	<tr>
		<td class="fieldlabel"> Expected Weekly Working Routine:</td>
		<td  colspan="3" width="500"   class="fieldValue"><?php echo htmlspecialchars((string)$vo->shift_pattern); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Job Description:</td>
		<td  colspan="3" width="500"   class="fieldValue"><?php echo htmlspecialchars((string)$vo->description); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Person Specification:</td>
		<td  colspan="3" width="500"    class="fieldValue"><?php echo htmlspecialchars((string)$vo->person_spec); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Qualifications Required:</td>
		<td  colspan="3" width="500"   class="fieldValue"><?php echo htmlspecialchars((string)$vo->required_quals); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Important Other Information:</td>
		<td  colspan="3" width="500"   class="fieldValue"><?php echo htmlspecialchars((string)$vo->misc); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel"> Possibility to complete a level 3<br> advanced apprenticeship:</td>
		<td class="fieldValue"><?php if($vo->to_level_3 == 1) echo htmlspecialchars('Yes'); else echo htmlspecialchars('No'); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel"> Other (please state):</td>
		<td  colspan="3" width="500"   class="fieldValue"><?php echo htmlspecialchars((string)$vo->prospects); ?></td>
	</tr>
</table>
	<?php } ?>

<div id="audit_log" title="Vacancy Audit Log" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" >
	<?php
	echo Note::renderNotes($link, 'vacancy', $vo->id);
	?>
</div>
<div id="dialog_logic_melon" title="Upload Vacancy to Logic Melon" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" >
</div>
</body>
</html>
