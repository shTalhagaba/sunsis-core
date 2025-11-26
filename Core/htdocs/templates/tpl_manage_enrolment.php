<?php /* @var $participant User */ ?>
<?php /* @var $contract Contract */ ?>
<?php /* @var $contractType ContractType */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Manage Enrolment</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script type="text/javascript">
		var phpParticipantId = '<?php echo $participant->id; ?>';
		var phpParticipantUsername = '<?php echo $participant->username; ?>';
		var phpIsParticipant = '<?php echo $participant->is_participant; ?>';
	</script>
	<script src="/js/manage_enrolment.js?n=<?php echo time(); ?>"></script>

	<script language="JavaScript">
		function save()
		{
			var start_date = $('#input_start_date');
			var planned_end_date = $('#input_planned_end_date');

			if(start_date.val().trim() == '')
			{
				alert('Please enter the training start date');
				start_date.focus();
				return ;
			}
			if(planned_end_date.val().trim() == '')
			{
				alert('Please enter the training planned end date');
				planned_end_date.focus();
				return ;
			}

			start_date = stringToDate(start_date.val());
			start_date.setHours(0,0,0,0);
			planned_end_date = stringToDate(planned_end_date.val());
			planned_end_date.setHours(0,0,0,0);

			if(planned_end_date < start_date)
			{
				alert('Training planned end date ' + formatDateGB(planned_end_date) + ' cannot be before the training start date ' + formatDateGB(start_date));
				return;
			}

			var selected_contract = $('#selected_contract').val();

			var postData = '&subaction=get_contract_year' +
					'&contract_id=' + selected_contract
				;

			var client = ajaxRequest('do.php?_action=manage_enrolment&ajax_request=true'+postData, null, null);
			var contract_year = client.responseText;
			if(contract_year == null || contract_year == '')
			{
				alert('Invalid contract: Operation aborted');
				return;
			}

			/*if(contract_year == '2016' && start_date < new Date('2016-08-01'))
			{
				alert('Training start date falls in 2015-16 contract year, please select 2015-16 contract');
				return;
			}
			if(contract_year == '2015' && start_date > new Date('2016-07-31'))
			{
				alert('Training start date falls in 2016-17 contract year, please select 2016-17 contract');
				return;
			}*/

			var divMiddle =document.getElementById('divMiddle');
			var html = '<p>You have selected the following qualifications/aims to add into the ILR.</p>';
			html += '<p>Please check those aims for which you want to populate Partner UKPRN from your selected college.</p>';
			if(divMiddle.hasChildNodes())
			{
				html += '<table style="border-collapse: collapse;" cellpadding="6">';
				html += '<tr style="border-bottom: 1pt solid black;"><th></th><th>ID</th><th align="left">Title</th></tr>';
				var max = divMiddle.childNodes.length;
				for(var i = 0; i < max; i++)
				{
					html += '<tr style="border-bottom: 1pt solid black;">';
					var txtValueQualificationId = divMiddle.childNodes[i].valueQualificationId.split('_');

					html += '<td><input type="checkbox" name="chk[]" value="' + txtValueQualificationId[0] + '" /></td>';
					html += '<td>' + txtValueQualificationId[0] + '</td>' + '<td>'+ txtValueQualificationId[1] + '</td>';

					html += '</tr>';
				}
				html += '</table>';
			}
			$('#dialogIDHelp').html(html);
			$('#dialogIDHelp').dialog('open');

			//addQualifications();
		}


	</script>
	<style type="text/css">
		div.MiddleMenu
		{
			margin-top: 108px;
		}

		#divLeft, #divMiddle, #divRight
		{
			width:500px;
			height:400px;
			border-width:1px;
			border-color:#668FEB;
			border-style:solid;
			margin-right: 10px;
			overflow:scroll;
			background-position: center;
			background-repeat: no-repeat;
		}

		#filter_school
		{
			width: 275px;
		}

		select.filter
		{
			width: 260px;
		}

		td.columnHeading
		{
			font-weight:bold;
		}

		div.qualification
		{
			height: 60px;
			padding: 2px;
			cursor: pointer;
			border-bottom: #DDDDDD 1px solid;
		}

		div.enrolledQualification
		{
			height: 60px;
			padding: 2px;
			background-color: orange;
			cursor: default;
			border-bottom: #ffd07a 1px solid;
		}

		div.qualification:hover
		{
			background-color: #FDF1E2;
		}

		div.qualificationDetails
		{
			margin-left:5px;
			font-size: 100%;
			color: #333333;
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
	</style>


</head>
<body>
<div class="banner">
	<div class="Title">Manage Enrolment</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){?><button onclick="save();">Enrol</button><?php }?>
		<?php if($participant->is_participant){?>
		<button onclick="window.location.replace('do.php?_action=view_edit_participant&id=<?php echo $participant->id; ?>&selected_tab=tab6')">Cancel</button>
		<?php } else {?>
		<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPrevious();?>');">Cancel</button>
		<?php } ?>


	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<div>
	<fieldset>
		<legend>Participant Information</legend>
		<table border="0" cellspacing="2" style="margin-left:10px" cellpadding="6">
			<tr>
				<td class="fieldLabel">Name:</td>
				<td class="fieldValue"><?php echo $participant->firstnames . ' ' . $participant->surname; ?></td>
			</tr>
			<?php if($participant->is_participant) { ?>
			<tr>
				<td class="fieldLabel">Contract:</td>
				<td class="fieldValue"><?php echo $contract->title; ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Contract Type:</td>
				<td class="fieldValue"><?php echo $contractType->title; ?></td>
			</tr>
			<?php } ?>
		</table>
	</fieldset>
</div>
<div>
	<fieldset>
		<legend>Please provide the following information</legend>
		<table border="0" cellspacing="8" style="margin-left:10px">
			<col width="190"/>
			<col width="380"/>
			<?php if(!$participant->is_participant) {?>
			<tr>
				<td class="fieldLabel_compulsory" valign="top">Contract:</td>
				<td>
					<?php
					$sql = <<<SQL
SELECT contracts.id, contracts.`title`, contract_year FROM contracts WHERE contracts.`contract_year` > (YEAR(CURDATE()) - 2) AND contracts.`active` = 1 ORDER BY  title;
SQL;
					echo HTML::select('selected_contract', DAO::getResultset($link, $sql), '', true, true); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td class="fieldLabel_compulsory">Start Date:</td>
				<td><?php echo HTML::datebox('start_date', '', true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory" valign="top">Planned End Date:</td>
				<td><?php echo HTML::datebox('planned_end_date', '', true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory" valign="top">Training Provider:</td>
				<td>
					<?php
					if($participant->is_participant)
						$sql = <<<SQL
SELECT
providers_locations.id,
CONCAT(COALESCE(providers.`legal_name`), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),' ,',COALESCE(`postcode`,''), ')') AS detail,
NULL
FROM organisations AS providers
INNER JOIN locations AS providers_locations
ON providers.id = providers_locations.`organisations_id`
WHERE providers.`organisation_type` = 3 AND providers.id = '$provider'
ORDER BY providers.legal_name
;
SQL;
					else
						$sql = <<<SQL
SELECT
providers_locations.id,
CONCAT(COALESCE(providers.`legal_name`), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),' ,',COALESCE(`postcode`,''), ')') AS detail,
NULL
FROM organisations AS providers
INNER JOIN locations AS providers_locations
ON providers.id = providers_locations.`organisations_id`
WHERE providers.`organisation_type` = 3
ORDER BY providers.legal_name
;
SQL;

					echo HTML::select('provider_location', DAO::getResultset($link, $sql), '', true, true);
					?>
				</td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory" valign="top">Course:</td>
				<td><?php echo HTML::select('provider_course', array(), '', true, true); ?></td>
			</tr>
			<?php if(!$participant->is_participant) {?>
			<tr>
				<td class="fieldLabel_optional" valign="top">Course Group:</td>
				<td><?php echo HTML::select('course_groups', array(), '', true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Assessor:</td>
				<td>
					<?php
					$sql = <<<SQL
SELECT users.id, CONCAT(firstnames, ' ', surname), CONCAT(legal_name, ' - ', SUBSTRING(lookup_org_type.`org_type`, 1, CHAR_LENGTH(lookup_org_type.`org_type`)-1))
FROM users INNER JOIN organisations ON users.`employer_id` = organisations.id INNER JOIN lookup_org_type ON organisations.`organisation_type` = lookup_org_type.`id`
WHERE users.type = 3 AND users.`web_access` = 1
ORDER BY organisation_type DESC, legal_name, firstnames;
SQL;
					echo HTML::select('selected_assessor', DAO::getResultset($link, $sql), '', true); ?>
				</td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">FS Tutor:</td>
				<td>
					<?php
					$sql = <<<SQL
SELECT users.id, CONCAT(firstnames, ' ', surname), CONCAT(legal_name, ' - ', SUBSTRING(lookup_org_type.`org_type`, 1, CHAR_LENGTH(lookup_org_type.`org_type`)-1))
FROM users INNER JOIN organisations ON users.`employer_id` = organisations.id INNER JOIN lookup_org_type ON organisations.`organisation_type` = lookup_org_type.`id`
WHERE users.type = 2 AND users.`web_access` = 1
ORDER BY organisation_type DESC, legal_name, firstnames;
SQL;
					echo HTML::select('selected_tutor', DAO::getResultset($link, $sql), '', true); ?>
				</td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">College:</td>
				<td><?php echo HTML::select('selected_college', $colleges_ddl, '', true); ?></td>
			</tr>
			<?php } ?>
		</table>
	</fieldset>
</div>


<div>
	<form name="frmEnrolment" action="<?php /*echo $_SERVER['PHP_SELF']; */?>" method="post">
		<input type="hidden" name="aims_to_populate_partner_ukprn" id="aims_to_populate_partner_ukprn" value="" />
		<fieldset>
			<legend>Qualifications Selection</legend>
			<table width="580" style="margin-left:10px;" >
				<col width="120"/>
				<tr>
					<td id="headingLeft" class="columnHeading" colspan="2"><span id="lbl_course"></span></td>
					<td id="headingMiddle" class="columnHeading" colspan="2">Qualifications to add (0)</td>
					<td id="headingRight" class="columnHeading" colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2"><span id="COURSE_TITLE"></span></td>
					<td>Sort by:</td>
					<td><?php echo HTML::select("filter_sort", $sort_dropdown, 0, false); ?></td>
					<td></td>
				</tr>

				<tr>
					<td></td>
					<td>Filter By Qualification ID: <input type="text" size="9" name="qualification_id"/></td>
					<td colspan="2">
						<?php if(!$participant->is_participant){?>&nbsp;<input type="checkbox" name="chk_add_to_ilr" id="chk_add_to_ilr" checked="checked" /> Add to ILR<?php } ?>
						&nbsp;<?php echo HTML::button("Clear Selection", "removeAllQualifications();"); ?>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><b>All Qualifications of this course</b></td>
					<td colspan="2" align="center"><b>Selected Qualifications</b></td>
					<td colspan="2" align="center"><b>Open Qualifications from Previous Episode</b></td>
				</tr>
				<tr>
					<td colspan="2"><div id="divLeft" ></div></td>
					<td colspan="2"><div id="divMiddle" ></div></td>
					<td colspan="2"><div id="divRight" ></div></td>
				</tr>

			</table>
		</fieldset>
	</form>
</div>

<div id="dialogIDHelp" title="ILR aims selection for Partner UKPRN field" style="font-size: smaller;">
	<p>You have selected the following qualifications/aims to add into the ILR.</p>
	<p>Please check those aims for which you want to populate Partner UKPRN from your selected college.</p>
</div>

</body>
</html>