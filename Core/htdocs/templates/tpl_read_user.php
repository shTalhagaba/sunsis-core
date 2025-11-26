<?php /* @var $vo User*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>User</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		<?php if(SystemConfig::getEntityValue($link, 'miap.soap.enabled')) { ?>
		$(function() {
			$( "#lrs_log_dialog" ).dialog({
				autoOpen: false,
				show: {
					effect: "blind",
					duration: 1000
				},
				hide: {
					effect: "explode",
					duration: 1000
				},
				width:
					700,
				height:
					700
			});

			$( "#lrs_log_opener" ).click(function() {
				$( "#lrs_log_dialog" ).dialog( "open" );
			});
		});
			<?php } ?>
		function deleteRecord()
		{
			if(window.confirm("Delete this user?"))
			{
				window.location.replace('do.php?_action=delete_user&username=<?php echo $vo->username; ?>&people_type=<?php echo $vo->type; ?>');
			}
		}

		function deleteImage()
		{
			if(!window.confirm("Delete this user's profile photo?")){
				return;
			}
			var client = ajaxRequest("do.php?_action=ajax_delete_image&username=<?php echo rawurlencode($vo->username) ?>");
			if(client){
				window.location.reload();
			}
		}

		/*
		function graph()
		{

			document.getElementById("pic").src = "do.php?_action=display_image&username=" + <?php echo "'" . rawurlencode($vo->username) . "'" ?>;
	
	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_is_photo_exists&username=' + <?php echo "'" . rawurlencode($vo->username) . "'" ?>), false);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null);

	if(request.status == 200) {
		
		var photo = request.responseText;
		if ( photo == "Y" ) {
			document.getElementById("removeimage").innerHTML = '<a href="#" onclick="javascript:deleteImage();" >remove this photo</a>';
		}	
	}
}
*/

		function course_onchange()
		{
			var myForm = document.forms[1];
			var buttons = myForm.elements['course'];
			var course_id = buttons[buttons.selectedIndex].value;

			var postData = 'id=' + course_id;

			var client = ajaxRequest('do.php?_action=ajax_get_org_name', postData);
			if(client != null)
			{
				document.getElementById('provider_name').innerHTML = client.responseText;
			}

            var f = document.forms['form2'];
            var locations = f.elements['location'];
            var url = 'do.php?_action=ajax_load_location_dropdown&org_name=' + client.responseText;
            ajaxPopulateSelect(locations, url);
		}

		function start_date_onchange()
		{
			var dob = '<?php echo $vo->dob; ?>';
			var startDate = document.forms[1].elements['start_date'].value;
			if(!window.stringToDate(startDate)){
				return;
			}

			var postData = 'd1=' + encodeURIComponent(dob) + '&d2=' + encodeURIComponent(startDate);
			var client = ajaxRequest('do.php?_action=ajax_get_date_difference', postData);
			if(client != null){
				document.getElementById('age_at_enrolment').innerHTML = client.responseText;
			}
		}

		function editLessons(event)
		{
			var myForm = document.forms[0];
			var buttons = myForm.elements['tr'];

			id = buttons[buttons.selectedIndex].value;

			if(id == '')
			{
				alert("Please select a Training Record");
				return false;
			}
			else
			{
				window.location.href=('do.php?_action=read_training_record&id=' + id);
			}
		}

		function validateContract()
		{
			var myForm = document.forms[1];
			var select = myForm.elements['contract'];
			var contractId = select[select.selectedIndex].value;
			var startDate = myForm.elements['start_date'].value;
			var targetDate = myForm.elements['end_date'].value;

			var postData = 'contract_id=' + contractId
				+ '&startDate=' + startDate
				+ '&targetDate=' + targetDate;


			var request = ajaxRequest('do.php?_action=verify_contract', postData);
			//alert(request.request.responseText.match('/^Successful/'));return;
			return request.responseText;
		}


		function editEnrol()
		{
			var myForm = document.forms[1];

			var select = myForm.elements['course'];
			var course_id = select[select.selectedIndex].value;
			select = myForm.elements['contract'];
			var contract_id = select[select.selectedIndex].value;
			var start_date = myForm.elements['start_date'].value;
			var end_date = myForm.elements['end_date'].value;
			select = myForm.elements['assessor'];
			var assessor = select[select.selectedIndex].value;
			select = myForm.elements['tutor'];
			var tutor = select[select.selectedIndex].value;
			var verifier_select = myForm.elements['verifier'];
			var verifier = verifier_select[verifier_select.selectedIndex].value;
            var select = myForm.elements['location'];
            var provider_id = select[select.selectedIndex].value;

			if(course_id == '' || contract_id == '' || start_date == '' || start_date == 'dd/mm/yyyy' || end_date == '' || end_date == 'dd/mm/yyyy')
			{
				alert("Please complete all fields");
				return false;
			}
			else
			{
				if(validateContract() == 'Unsuccessful')
				{
					alert("Invalid contract selected. Either change start date or select different contract.");
					return false;
				}

				<?php if($bil_learner) {?>
				var copy_compliance_records = myForm.elements["chk_copy_compliance"].checked;
				var request_path = 'do.php?_action=save_start_training'
					+ '&username=' + <?php echo '"' . rawurlencode($vo->username) . '"';?>
					+ '&course_id=' + course_id
					+ '&contract_id=' + contract_id
					+ '&start_date=' + encodeURIComponent(start_date)
					+ '&end_date=' + encodeURIComponent(end_date)
					+ '&provider_location_id=' + encodeURIComponent(provider_id)
					+ '&bil_learner=true'
					+ '&copy_compliance=' + copy_compliance_records
					+ '&previous_training_record_id=' + <?php echo $previous_training_record_id; ?>
					+ '&previous_course_id=' + <?php echo $previous_course_id; ?>
				;
				<?php } else {?>
				var request_path = 'do.php?_action=save_start_training&username='+<?php echo '"' . rawurlencode($vo->username) . '"';?> +'&course_id='+course_id
					+'&contract_id='+contract_id+'&start_date='+encodeURIComponent(start_date)+'&end_date='+encodeURIComponent(end_date)+'&provider_location_id='+encodeURIComponent(provider_id);
				<?php } ?>
				if( assessor != '' ) {
					request_path += '&assessor=1'+'&assessor='+assessor;
				}
				if(tutor != '')
				{
					request_path += '&tutor='+tutor;
				}
				if(verifier != '')
				{
					request_path += '&verifier='+verifier;
				}
				window.location.href=(request_path);
			}
		}

		function PDF()
		{
			f = document.forms[2];
//	f.xml.value = toXML();
			f.submit();
		}


	</script>
</head>


<body>
<div class="banner">
	<div class="Title">
		<?php echo $page_title?>
	</div>
	<div class="ButtonBar">
		<button onclick="if(window.name == 'viewUser'){window.close();} window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($_SESSION['user']->isAdmin() || (!$_SESSION['user']->isEmployerAdmin && $_SESSION['user']->type!=2 && $_SESSION['user']->type!=3 && $_SESSION['user']->type!=13 && $_SESSION['user']->type!=14 && $_SESSION['user']->type!=12 && $_SESSION['user']->type!=19 && $_SESSION['user']->type!=20 && $_SESSION['user']->type!=5)){ ?>
		<button	onclick="window.location.replace('do.php?username=<?php echo $vo->username; ?>&people=<?php echo $people; ?>&organisations_id=<?php echo $o_vo->id; ?>&_action=edit_user');">Edit</button>
		<?php } ?>
		<?php
		if($_SESSION['user']->isAdmin() && !$vo->isAdmin() ) {
			?>
			<button onclick="deleteRecord();">Delete</button>
			<?php
		}
		?>

		<?php if($vo->type == User::TYPE_LEARNER){?>
		<?php if(DB_NAME!='am_fareham') { ?>
			<button	onclick="window.open('do.php?username=<?php echo $vo->username; ?>&_action=pdf_from_learner');">Basic ILR</button>
			<?php } elseif($xml!='') { ?>
			<button onclick="PDF();">Basic ILR</button>
			<?php } ?>

		<?php if(count($trs) == 1) {?>
			<button onclick="window.location.href=('do.php?_action=read_training_record&id=' + <?php echo $trs[0][0]; ?>)">Training Record</button>
			<?php }else{ ?>
			<button onclick="showHideBlock('div_addLesson');">Training Records</button>
			<?php } ?>
		<!--		<?php /*if($_SESSION['user']->type==8 && (DB_NAME=='am_lcurve')){ */?>
			<button onclick="showHideBlock('div_addEnrol');">Enrol</button>
		--><?php /*} */?>

		<?php if($_SESSION['user']->isAdmin() && ($_SESSION['user']->type!=8 && (DB_NAME!='am_lcurve')) && (DB_NAME=='am_superdrug' || DB_NAME=='am_demo'|| DB_NAME=='ams'|| DB_NAME=='am_raytheon' || DB_NAME == 'am_lead' || DB_NAME == 'am_pathway' || DB_NAME == 'am_set' || DB_NAME == 'am_lcurve')){ ?>
			<?php echo !$bil_learner ? '<button onclick="showHideBlock(\'div_addEnrol\');">Enrol</button>':'<button onclick="showHideBlock(\'div_addEnrol\');">Enrol/Re-Enrol</button>';?>
			<?php }
		elseif(($_SESSION['user']->isAdmin() && DB_NAME!="am_siemens_demo" && DB_NAME!="am_siemens") OR ($_SESSION['user']->type==1 && (DB_NAME=='am_lcurve')) OR ($_SESSION['user']->type==8 && (DB_NAME=='am_lcurve'))){?>
			<?php echo !$bil_learner ? '<button onclick="showHideBlock(\'div_addEnrol\');">Enrol</button>':'<button onclick="showHideBlock(\'div_addEnrol\');">Enrol/Re-Enrol</button>';?>
			<?php }
		elseif($_SESSION['user']->isAdmin() && (DB_NAME=="am_siemens_demo" || DB_NAME=="am_siemens")) {?>
			<button onclick="window.location.href='do.php?_action=manage_enrolment&participant_id=<?php echo $vo->id; ?>';">Enrol</button>
			<?php }
		elseif(DB_NAME=="am_hybrid" && $_SESSION['user']->type == User::TYPE_ADMIN && $_SESSION['user']->org->organisation_type == Organisation::TYPE_TRAINING_PROVIDER) {?>
			<button onclick="showHideBlock('div_addEnrol');">Enrol</button>
			<?php }
		if((SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) && $vo->id != '' && SystemConfig::getEntityValue($link, 'miap.soap.enabled') && $vo->type==User::TYPE_LEARNER) {?>
			<!--<button id="lrs_log_opener">LRS Access Log</button>-->
			<?php }
		if(SystemConfig::getEntityValue($link, 'module_recruitment_v2') && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_ASSESSOR) && $vo->type == User::TYPE_LEARNER) {?>
			<button id="btn_show_lrs_achievement">LRS Achievement Results</button>
			<?php }
	} ?>	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
		<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px; border-radius: 15px;">
			<tr>
				<td align="right">Select Training Record</td>
				<td><?php echo HTML::select('tr', $trs, null, true); ?></td>
				<td>
					<div style="margin:20px 0px 20px 10px">
						<span class="button" onclick="editLessons();"> Training Record </span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php if($vo->type == User::TYPE_LEARNER){?>
<form name="form2" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<div id="div_addEnrol" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
		<table border="0" cellspacing="4" style="margin:10px;background-color:silver; border:1px solid black;padding:3px; border-radius: 15px;">
			<?php if($bil_learner){?>
			<tr><td><img src="/images/info-icon.png" alt="Break In Learning Learner" style="float: right; padding-right: 5px" height="20" width="20" /></td><td>Learner is on break in learning so the following options are pre-selected</td></tr>
			<?php } ?>
			<tr>
				<td align="right">Course</td>
				<td align=left><?php echo HTML::select('course', $courses, $previous_course_id, true); ?></td>
			</tr>
			<?php if(isset($previous_provider_name) && $previous_provider_name != ''){?>
			<tr>
				<td align="right">Provider</td>
				<td align=left id="provider_name"><?php echo $previous_provider_name; ?></td>
			</tr>
			<?php } ?>
            <tr>
                <td align="right">Location</td>
                <td align=left><?php echo HTML::select('location', $locations, '', false); ?></td>
            </tr>
			<tr>
				<td align="right">Assessor</td>
				<td align=left><?php echo HTML::select('assessor', $assessors, $previous_assessor_id, true); ?></td>
			</tr>
			<tr>
				<td align="right">FS Tutor</td>
				<td align=left><?php echo HTML::select('tutor', $tutors, $previous_tutor_id, true); ?></td>
			</tr>
			<tr>
				<td align="right">Verifier</td>
				<td align=left><?php echo HTML::select('verifier', $verifiers, $previous_verifier_id, true); ?></td>
			</tr>
			<tr>
				<td align="right">Contract</td>
				<td align=left><?php echo HTML::select('contract', $contracts, $previous_contract_id, true); ?></td>
			</tr>
			<tr>
				<td align="right">Start Date</td>
				<td align=left><?php echo HTML::datebox('start_date', null) ?></td>
			</tr>
			<tr>
				<td align="right">Planned End Date</td>
				<td align=left><?php echo HTML::datebox('end_date', null) ?></td>
			</tr>
			<tr>
				<td align="right">Age at enrolment</td>
				<td align=left id="age_at_enrolment"></td>
			</tr>
			<?php if($bil_learner){?>
			<tr>
				<td align="right">Copy Compliance Records</td>
				<td align=left><input type="checkbox" name="chk_copy_compliance" id="chk_copy_compliance" value="" /></td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<div style="margin:20px 0px 20px 10px">
						<span class="button" onclick="editEnrol();"> Enrol </span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>
	<?php } ?>


<table border="0" cellspacing="4" cellpadding="4">
	<col width="190" /><col width="380" />
	<tr>
		<?php
		// re 15/09/2011 - replaced the view timestamp with leter generation
		$edit_rights = !$_SESSION['user']->isEmployerAdmin && $_SESSION['user']->type!=2 && $_SESSION['user']->type!=3 && $_SESSION['user']->type!=13 && $_SESSION['user']->type!=14 && $_SESSION['user']->type!=12;
		if (( DB_NAME == 'am_superdrug' || DB_NAME == 'sunesis' ) && $_SESSION['user']->type != User::TYPE_LEARNER)
		{
			if (sizeof($document_outputs) > 0 )
			{
				echo '<td>';
				echo '<img id="pic" height="160" alt="Photograph" border="2" src="'.$photopath.'"/>';
				echo '<br/>';
				if($photopath != "/images/no_photo.png" && $edit_rights)
				{
					echo '<span id="removeimage"><a href="" onclick="deleteImage();return false;" >remove this photo</a></span>';
				}
				else
				{
					echo '<span id="removeimage"></span>';
				}
				echo '</td>';
				echo '<td style="vertical-align:middle; text-align: right;" >';
				echo '<div style="font-size: 1.1em; height: 100px; width: 380px; text-align:left; background: url(\'images/grey-box-background.png\') no-repeat top center #fff; padding: 10px 5px 0px 40px;" >';
				echo 'Choose a letter for this learner';
				echo '<form name="doc_gen" method="POST" >';
				echo '<input type="hidden" name="_action" value="learner_doc" />';
				echo '<input type="hidden" name="username" value="'.htmlspecialchars((string)$vo->username).'" />';
				asort($document_outputs);
				echo '<select name="docname" id="docname" >';
				echo '<option value="">Please select a template...</option>';
				foreach ( $document_outputs as $id => $docname ) {
					echo '<option value="'.$docname.'">'.$docname.'</option>';
				}
				echo '</select>';
				echo '<input type="submit" name="go" value="Generate &raquo;" ';
				echo ' onclick="javascript:if(document.getElementById(\'docname\').value == \'\' ) return false;" ';
				echo ' onmouseover="javascript:this.style.cursor=\'pointer\';"/>';
				echo '</form>';
				echo '</div>';
				echo '</td>';
			}
		}
		else
		{
			echo '<td colspan="2" >';
			echo '<img id="pic" height="160" alt="Photograph" border="2" src="'.$photopath.'"/>';
			echo '<br/>';
			if($photopath != "/images/no_photo.png" && $edit_rights)
			{
				echo '<span id="removeimage"><a href="" onclick="deleteImage();return false;" >remove this photo</a></span>';
			}
			else
			{
				echo '<span id="removeimage"></span>';
			}
			echo '</td>';
		}
		?>
	</tr>
</table>

<h3>Personal Details</h3>
<table border="0" cellspacing="4" cellpadding="4"  style="margin-left:10px">
	<col width="190" /><col width="380" />
	<tr>
		<td class="fieldLabel">Firstnames</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->firstnames); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Surname</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->surname); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Job role</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->job_role); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">User Type</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = " . $vo->type)); ?></td>
	</tr>

	<?php
	if ( SystemConfig::getEntityValue($link, 'module_recruitment') ) {
		echo '<tr><td class="fieldLabel">Sales region:</td><td class="fieldValue">'.htmlspecialchars((string) $vo->department).'</td></tr>';
	}
	?>

	<tr>
		<td class="fieldLabel">Gender</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$gender_description); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Ethnicity</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$ethnicity_description); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Date of birth</td>
		<td class="fieldValue"><?php
			echo htmlspecialchars(Date::toMedium($vo->dob));
			if ($vo->dob) {
				echo '<span style="margin-left:30px;color:gray">(' . Date::dateDiff(date("Y-m-d"),$vo->dob) . ')</span>';
			}
			?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Nationality</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$nationality_description); ?></td>
	</tr>
	<?php if(DB_NAME=="am_edudo") { ?>
	<tr>
		<td class="fieldLabel">Entered OnTo DIGIAPP</td>
		<td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($vo->enteredOnToDigiApp)); ?></td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME!="am_reed_demo" && DB_NAME!="am_reed" && $vo->type==User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel">Referral Source</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->referral_source); ?></td>
	</tr>
	<?php } ?>
	<?php if((DB_NAME=="am_reed_demo" || DB_NAME=="am_reed")  && $vo->type==User::TYPE_LEARNER){ ?>
	<tr>
		<td class="fieldLabel">Referral Source</td>
		<?php if(isset($vo->referral_source) && $vo->referral_source != '' && is_numeric($vo->referral_source) ) { ?>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_referral_source WHERE id = " . $vo->referral_source)); ?></td>
		<?php } else { ?>
		<td class="fieldValue"></td>
		<?php } ?>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Referral Source Other - Description:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->ref_source_other_desc); ?></td>
	</tr>
	<?php } ?>
	<?php if((DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")  && $vo->type==User::TYPE_LEARNER){ ?>
	<tr>
		<td class="fieldLabel_optional">Referral Date:</td>
		<td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($vo->referral_date)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Goal 1:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$job_goal_1); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Goal 2:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$job_goal_2); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Goal 3:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$job_goal_3); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Readiness:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->job_readiness); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Office:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$learner_office); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Signposting Organisation:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->signposting_org); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<?php if(DB_NAME=="am_baltic") {?>
		<td class="fieldLabel">Induction Date</td>
		<?php } else { ?>
		<td class="fieldLabel">Initial Appointment Date</td>
		<?php } ?>
		<td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($vo->initial_appointment_date)); ?></td>
	</tr>
	<?php if((DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo") && $vo->type == User::TYPE_LEARNER) {?>
	<tr>
		<td class="fieldLabel">Business Code</td>
		<?php if(isset($vo->employer_business_code) && $vo->employer_business_code != '' && is_numeric($vo->employer_business_code) ) { ?>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT title FROM brands WHERE id = " . $vo->employer_business_code)); ?></td>
		<?php } else { ?>
		<td class="fieldValue"></td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php
	if(DB_NAME=="am_baltic" && $vo->type == User::TYPE_LEARNER)
	{
		$data_from_e_rec = DAO::getResultset($link, "SELECT lookup_source.`description`, candidate.`source_other`, candidate.`jobatar` FROM candidate INNER JOIN lookup_source ON candidate.source = lookup_source.`id` WHERE candidate.`username` = '$vo->username'", DAO::FETCH_ASSOC);
		if(isset($data_from_e_rec) && count($data_from_e_rec) > 0)
		{
			echo '<tr>';
			echo '<td class="fieldLabel">Source (from e-recruitment):</td>';
			echo '<td class="fieldValue">' . htmlspecialchars((string)$data_from_e_rec[0]['description']) . '</td>';
			echo '</tr>';
			if(strtolower($data_from_e_rec[0]['description']) == 'other')
			{
				echo '<tr>';
				echo '<td class="fieldLabel">Source Other (from e-recruitment):</td>';
				echo '<td class="fieldValue">' . htmlspecialchars((string)$data_from_e_rec[0]['source_other']) . '</td>';
				echo '</tr>';
			}
			if(!is_null($data_from_e_rec[0]['jobatar']))
			{
				echo '<tr>';
				echo '<td class="fieldLabel">Candidate Jobatar Completed(from e-recruitment):</td>';
				echo $data_from_e_rec[0]['jobatar'] == 1?'<td class="fieldValue">Yes</td>':'No';
				echo '</tr>';

			}
		}
	}
	?>
	<?php if((DB_NAME=="ams" || DB_NAME=="am_platinum" || DB_NAME=="am_pathway" || DB_NAME=="am_crackerjack") && $vo->type==User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel">Previous School:</td>
		<td class="fieldValue">
			<?php echo $vo->prev_school != ''? DAO::getSingleValue($link, "SELECT school_name FROM central.lookup_schools WHERE id = " . $vo->prev_school) : ''; ?>
		</td>
	</tr>
	<?php } ?>
	<?php if((DB_NAME=="ams" || DB_NAME=="am_platinum") && $vo->type == User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel">Initially engaged by:</td>
		<td class="fieldValue">
			<?php echo $vo->initially_engaged_by != ''? DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = " . $vo->initially_engaged_by) : ''; ?>
		</td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME=="am_lcurve" && $vo->type==User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel">Learner Find Agent:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learner_find_agent); ?></td>
	</tr>
	<?php } ?>
</table>

<?php if($vo->type==User::TYPE_LEARNER){?>
<h4>Identifiers</h4>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="190" /><col width="380" />
	<tr>
		<td class="fieldLabel">Enrolment No.</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->enrollment_no); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Unique Learner Number (ULN)</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->l45); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">National Insurance (NI)</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->ni); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">ILR Learner Ref No. (L03)</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$tr_l03); ?></td>
	</tr>
	<?php if(DB_NAME=='am_reed' || DB_NAME=='am_reed' || DB_NAME=='ams') {?>
	<tr>
		<td class="fieldLabel_optional">Registered By:</td>
		<td class="fieldValue">
			<?php
			if(isset($vo->who_created) && $vo->who_created != '')
				echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames,' ',surname) FROM users WHERE username  = '" . $vo->who_created . "'");
			else
				echo '';
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if(SOURCE_LOCAL || DB_NAME == "am_gigroup"){?>
	<tr>
		<td class="fieldLabel">Payroll Number</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->payroll_number); ?></td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME=='am_platinum') {?>
	<tr>
		<td class="fieldLabel">UCI Number:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->uci_number); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Candidate Number:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->candidate_number); ?></td>
	</tr>
	<?php } ?>
</table>

<h3>Diagnostics</h3>
<table border="0" cellspacing="4" cellpadding="4"  style="margin-left:10px">
	<col width="190" /><col width="380" />
	<tr>
		<td class="fieldLabel">Numeracy</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$numeracy); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Literacy</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$literacy); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">ICT</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$ict); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Mechanical aptitude test</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->bennett_test); ?></td>
	</tr>
	<?php if(DB_NAME=="am_platinum") { ?>
	<tr>
		<td class="fieldLabel">GCSE English Grade</td>
		<td class="fieldValue"><?php if(isset($vo->gcse_eng) && $vo->gcse_eng != '') echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM central.lookup_gcse_grades WHERE id = " . $vo->gcse_eng)); echo ''; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">GCSE Maths Grade</td>
		<td class="fieldValue"><?php if(isset($vo->gcse_maths) && $vo->gcse_maths != '') echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM central.lookup_gcse_grades WHERE id = " . $vo->gcse_maths)); echo ''; ?></td>
	</tr>
	<?php } ?>
</table>
	<?php if(DB_NAME=="am_lead" || DB_NAME=="ams"){?>
	<h3>Learner Defined Fields</h3>
	<table border="0" cellspacing="4" cellpadding="4"  style="margin-left:10px">
		<col width="190" /><col width="380" />
		<tr>
			<td class="fieldLabel">Learner Defined Field 1 (LD1)</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->ld1); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Learner Defined Field 2 (LD2)</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->ld2); ?></td>
		</tr>
	</table>
		<?php } ?>
	<?php if(SystemConfig::getEntityValue($link, 'miap.soap.enabled') && $vo->type==User::TYPE_LEARNER) { ?>
	<h3>LRS Specific Fields</h3>
	<table border="0" cellspacing="4" cellpadding="4"  style="margin-left:10px">
		<col width="190" /><col width="380" />
		<tr>
			<td class="fieldLabel">Ability to share</td>
			<td class="fieldValue"><?php echo $vo->ability_to_share != '' ? htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_ability_to_share WHERE id = " . $vo->ability_to_share)): ''; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Verification type</td>
			<td class="fieldValue"><?php echo $vo->verification_type != '' ? htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_verification_type WHERE code = " . $vo->verification_type)): ''; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Verification type other</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string) $vo->verification_type_other); ?></td>
		</tr>
	</table>
		<?php } ?>
	<?php } ?>
<?php
// #171 - relmes - display user metadata
$meta_data_count = 0;
if ( ( isset($vo->user_metadata) ) && ( sizeof($vo->user_metadata) > 0) && ( $_SESSION['user']->isAdmin() ) ) {
	foreach( $vo->user_metadata as $meta_group => $meta_array ) {
		echo '<h3>'.$meta_group.'</h3>';
		echo '<table border="0" cellspacing="4" cellpadding="4">';
		echo '<col width="170" /><col width="400" />';
		foreach ( $meta_array as $title => $type ) {
			if ( $title != 'multiple_addresses' ) {
				echo '<tr><td>'.$title.'</td>';
				echo '<td>'.$type.'</td>';
				echo '</tr>';
			}
		}
		echo '</table>';
	}
}
?>

<h3>Record Status and Web Access</h3>
<p class="sectionDescription"></p>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="190" /><col width="380" />
	<tr>
		<td class="fieldLabel">Record status</td>
		<td class="fieldValue"><?php echo $vo->record_status?'Active':'Archived'; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Web access</td>
		<td class="fieldValue"><?php echo $vo->web_access?'Enabled':'Disabled'; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Web username</td>
		<td class="fieldValue" style="font-family:monospace"><?php echo htmlspecialchars((string)$vo->username); ?></td>
	</tr>
</table>

<?php if($vo->type == User::TYPE_LEARNER && (DB_NAME=="ams" || DB_NAME=="am_baltic")) {?>
<h3>Employer Age Grant</h3>
<table>
	<tr>
		<td class="fieldLabel">Learner is part of Age Grant allowance:</td>
		<td class="fieldValue"><?php echo $vo->age_grant == 1? 'Yes': 'No'; ?></td>
	</tr>
</table>
	<?php } ?>

<h3>Employer</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="190" /><col width="380" />
	<tr>
		<td class="fieldLabel">Current employer</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$o_vo->legal_name); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Location</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$loc->address_line_3 . ' ' . $loc->postcode); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Contact name:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$loc->contact_name); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Contact mobile phone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$loc->contact_mobile); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Contact telephone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$loc->contact_telephone); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Contact email:</td>
		<?php echo '<td class="fieldValue"> <a href="mailto:' . htmlspecialchars((string)$loc->contact_email) . '">' . htmlspecialchars((string)$loc->contact_email) . '</a></td>'; ?>
	</tr>
</table>

<?php
// user address management now handled
// in a separate function for requirement #179 {0000000044}
echo $vo->displayUserAddresses($link);

?>

<?php if(DB_NAME=='am_fareham') {?>
<form  name="pdf" id="pdf" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<input type="hidden" name="_action" value="pdf_from_ilr2012" />
	<input type="hidden" name="xml" value="<?php echo $xml;?>" />
</form>
	<?php }?>

<?php if(DB_NAME=="am_baltic" && $vo->type == User::TYPE_LEARNER && $candidate_id != '') { ?>
<h3>CRM Notes From e-Recruitment</h3>
	<?php $view_candidate_crm->render($link, $candidate_id);
} ?>

<?php if((SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) && SystemConfig::getEntityValue($link, 'miap.soap.enabled') && $vo->type==User::TYPE_LEARNER) { ?>
<div id="lrs_log_dialog" title="LRS Access Log" style="
        height: 500px;
	    width: 270px;
	    text-align: left;
	    margin-top: 20px;
	    margin-left: 5px;
	    vertical-align: middle;
	    display: table-cell;
        overflow-y: scroll; overflow-x: scroll; display:none;"  >
	<p><?php //echo Note::getLRSAccessLog($link, $vo->id); ?></p>
</div>
	<?php } ?>

<script type="text/javascript">

	$( "#btn_show_lrs_achievement" ).click(function() {
		$.ajax({
			type:'GET',
			url:'do.php?_action=ajax_show_lrs_achievement&username='+encodeURIComponent('<?php echo $vo->username; ?>'),
			success: function(response) {

				$("<div></div>").html(response).dialog({
					title: " LRS Achievement Results ",
					autoOpen: "false",
        			maxWidth:"600",
        			maxHeight: "500",
        			width: "600",
        			height: "500",
        			modal: "true",
					closeOnEscape: true,
					buttons: {
						'OK': function() {
							$(this).dialog('close');
						}
					}
				});
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	});
</script>
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>