<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Person</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<!--	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>-->
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/password.js" type="text/javascript"></script>
	<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>
	<script language="JavaScript" src="js/lrs.js?n=8115"></script>

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
		var phpPeople = <?php echo '"' . $people . '"'; ?>;
		var phpOrgId = <?php echo '"' . $org_id . '"'; ?>;

		function course_id_onchange()
		{
			if(document.getElementById("course_id").value!='')
				document.getElementById("plannedEndDate").style.display = "block";
			else
				document.getElementById("plannedEndDate").style.display = "none";
		}

		function limitText(limitField, limitCount, limitNum)
		{
			if (limitField.value.length > limitNum)
			{
				limitField.value = limitField.value.substring(0, limitNum);
			}
			else
			{
				limitCount.value = limitNum - limitField.value.length;
			}
		}

		function verification_type_onchange()
		{
			var verification_type_value = document.getElementById('verification_type').value;
			if(verification_type_value == 999)
			{
				alert('Please enter \'Verification Type Other\' information.');
				document.getElementById('verification_type_other').className = "compulsory";
				document.getElementById('verification_type_other').focus();
			}
			else
			{
				document.getElementById('verification_type_other').value = "";
				document.getElementById('verification_type_other').className = "optional";
			}
		}

		function verification_type_other_onkeypress()
		{
			var verification_type_value = document.getElementById('verification_type').value;
			if(verification_type_value != 999)
			{
				alert('This field must be left blank if \'Verification Type\' is not selected as \'Other\'');
				document.getElementById('verification_type_other').value = '';
			}
		}

	</script>
	<script type="text/javascript" src="/scripts/edit_user.js?n=7787"></script>

	<style type="text/css">
		div.SimilarRecord {
			margin-left: 1px;
			margin-top: 3px;
			height: 1.3em;
			width: 150px;
			overflow: hidden;
			color: #0B79BE;
			cursor: pointer;
			font-size: 10pt;
			padding-right: 10px;

			-webkit-user-select: none;
			-moz-user-select: none;
		}

	</style>

</head>
<body>
<?php
if (! preg_match ( '/MSIE [1-6]/', $_SERVER ['HTTP_USER_AGENT'] ) && ($_SESSION ['screen_width'] >= 1024)) { ?>
<div class="RightMenu">
	<div class="RightMenuTitle">Sections</div>
	<div class="RightMenuItem">- <a href=""
	                                onclick="document.getElementById('sectionPersonalDetails').scrollIntoView(true);return false">Personal details</a></div>
	<div class="RightMenuItem">- <a href=""
	                                onclick="document.getElementById('sectionDiagnosticAssessments').scrollIntoView(true);return false">Diagnostic assessments</a></div>
	<div class="RightMenuItem">- <a href=""
	                                onclick="document.getElementById('sectionIlrSpecific').scrollIntoView(true);return false">ILR specific</a></div>
	<div class="RightMenuItem">- <a href=""
	                                onclick="document.getElementById('sectionApplicationAccess').scrollIntoView(true);return false">Application access</a></div>
	<div class="RightMenuItem">- <a href=""
	                                onclick="document.getElementById('sectionWorkContactDetails').scrollIntoView(true);return false">Work contact details</a></div>
	<div class="RightMenuItem">- <a href=""
	                                onclick="document.getElementById('sectionHomeContactDetails').scrollIntoView(true);return false">Home contact details</a></div>
	<!--
	<div class="RightMenuTitle">Actions</div>
	<div class="RightMenuItem">- <a href="<?php echo $_SESSION['bc']->getPrevious();?>">Cancel</a></div>
	<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER): ?>
	<div class="RightMenuItem">- <a href="" onclick="save();return false;">Save</a></div>
	<?php endif; ?>
-->
	<div id="SimilarRecords" style="margin-top:20px;display:none">
		<div class="RightMenuTitle">Duplicate Records?</div>
	</div>
</div>

	<?php } ?>

<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER){?>
		<button id="btnSave" onclick="save();return false;">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
		<?php if((SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL) && SystemConfig::getEntityValue($link, 'miap.soap.enabled') && $vo->type == User::TYPE_LEARNER) { ?>
		<!--<button type="button" id="showHideLRSPanel">LRS Panel (Show/Hide)</button>-->
		<?php } ?>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3 class="introduction">Introduction</h3>
<p class="introduction">The unique identifier for a user is the <b>username</b>, and
	once assigned it cannot be changed.</p>

<form method="post" id="useredit" action="do.php?_action=save_user" enctype="multipart/form-data">
<input type="hidden" name="newuser" value="<?php echo $vo->username==''?'1':'0'; ?>" />
<input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
<input type="hidden" name="_action" value="save_user" />
<input type="hidden" id="organisation_type" value="<?php echo $org->organisation_type;?>" />
<input type="hidden" name="type" value="<?php echo $people_type?>" />
<!-- Maximum size of profile photograph -->
<input type="hidden" name="MAX_FILE_SIZE" value="102400" />

<div id="lrsPanel" style="display:none">
	<h3 class="introduction">LRS Panel</h3>
	<p class="introduction">This panel is used to interact with LRS web portal.</p>
	<?php if($vo->id != '') {?>
	<span class="button" onclick="verifyLearnerULN1();">Verify Learner ULN</span>
	<span class="button" onclick="updateLRSRecord();">Update Learner Record in LRS</span>
	<!--<span class="button" onclick="showHideFindByDemographicsPanel();">Find Learner by Demographics</span>-->
	<?php } else { ?>
	<span class="button" onclick="showHideFindByDemographicsPanel();">Find Learner by Demographics</span>
	<?php } ?>
	<div id="lrsPanelSearchByDemographics" style="display: none;">
		<input type="hidden" name="subaction" value="searchByDemographics" />
		<h4>Mandatory Fields</h4>
		<table border="0" cellspacing="4" style="margin-left:10px">
			<col width="140" />
			<tr>
				<td class="fieldLabel_compulsory">First Names:</td>
				<td><input class="compulsory" type="text" name="lrs_firstnames" value="<?php echo htmlspecialchars((string)$vo->firstnames); ?>" size="40" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Surname:</td>
				<td><input class="compulsory" type="text" name="lrs_surname" value="<?php echo htmlspecialchars((string)$vo->surname); ?>" size="40" /></td>
			</tr>
			<tr>
				<?php
				switch($vo->gender)
				{
					case 'M':
						$lrs_gender = '1';
						break;
					case 'F':
						$lrs_gender = '2';
						break;
					case 'U':
						$lrs_gender = '0';
						break;
					case 'W':
						$lrs_gender = '9';
						break;
					default:
						$lrs_gender = '';
						break;
				}
				?>
				<td class="fieldLabel_compulsory">Gender:</td>
				<td><?php echo HTML::select('lrs_gender', DAO::getResultset($link, "SELECT IF(id='F', 2, IF(id='M',1,IF(id='U', 0, IF(id='W', 9,'')))) AS id, description FROM lookup_gender;"), $lrs_gender, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Date of Birth:</td>
				<td><?php echo HTML::datebox('lrs_dob', $vo->dob, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Postcode:</td>
				<td><input id="lrs_home_postcode" class="compulsory" type="text" name="lrs_home_postcode" value="<?php echo $vo->home_postcode ?>" size="20" maxlength="20" /></td>
			</tr>
		</table>
		<h4>Optional Fields</h4>
		<table border="0" cellspacing="4" style="margin-left:10px">
			<col width="140" />
			<tr>
				<td class="fieldLabel_optional">Previous Family Name:</td>
				<td><input class="optional" type="text" name="lrs_prev_family_name" value="" size="40" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">School At Age 16:</td>
				<td><input class="optional" type="text" name="lrs_school_at_age_16" value="" size="40" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Place of Birth:</td>
				<td><input class="optional" type="text" name="lrs_place_of_birth" value="" size="40" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Email Address:</td>
				<td><?php echo HTML::emailbox('lrs_home_email', ''); ?></td>
			</tr>
		</table>
		<input type="button" onclick="findByDemographics1();" value="Search" />
	</div>
	<div id="find_by_uln_dialog1" style="display: none;">
		<input type="hidden" name="subaction" value="searchByULN" />
		<table class="resultset" cellspacing="0" cellpadding="4">
			<tr><th>Find Type</th><th>Description</th></tr>
			<tr><td><input name="find_by_uln" type="radio" value="FUL" >Full</td><td><b>'Full'</b> option will bring all the information if match exists.</td></tr>
			<tr><td><input name="find_by_uln" type="radio" value="CHK" >Status</td><td><b>'Status'</b> option will simply check if a match or possible match exists.</td></tr>
			<tr><td colspan="2" align="right"><input type="button" onclick="findByULN(<?php echo $vo->id; ?>)" value="Search" /></td></tr>
		</table>
	</div>
	<div id="update_learner_in_lrs" style="display: none;">
	</div>
</div>
<div class="loading-gif" id="progress" style="display:none" >
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>
<h3 id="sectionPersonalDetails">Personal Details</h3>
<p class="sectionDescription">First and second names may only contain the
	letters a-z, spaces, hyphens and apostrophes.</p>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190" />
	<tr>
		<td class="fieldLabel_compulsory">Firstname(s):</td>
		<td>
			<input class="compulsory" type="text" name="firstnames" value="<?php echo htmlspecialchars((string)$vo->firstnames); ?>" size="40" maxlength="100"/>
			<span>(<a href="ListOfInvalidNames.pdf" target="_blank">List of Invalid Names</a>)</span>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Surname:</td>
		<td>
			<input class="compulsory" type="text" name="surname" value="<?php echo htmlspecialchars((string)$vo->surname); ?>" size="40" maxlength="100"/>
			<span>(<a href="ListOfInvalidNames.pdf" target="_blank">List of Invalid Names</a>)</span>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Role </td>
		<td>
			<!-- <?php //echo HTML::select('job_role', $job_role, $vo->job_role, true, false, true); ?>
			<span class="button" onclick="newJobRole();">New</span> -->
			<input type="text" name="job_role" id="job_role" value="<?php echo $vo->job_role; ?>" maxlength="70">
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Gender:</td>
		<td><?php echo HTML::select('gender', $gender, $vo->gender, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Ethnicity:</td>
		<td><?php echo HTML::select('ethnicity', $L12_dropdown, ($vo->ethnicity ? $vo->ethnicity:99), false, true); ?></td>
	</tr>

	<?php if(DB_NAME=='am_crackerjack' && $vo->type == User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel_compulsory">Date of Birth:</td>
		<td><?php echo HTML::datebox("dob", $vo->dob, true); ?></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td class="fieldLabel_optional">Date of Birth:</td>
		<td><?php echo HTML::datebox("dob", $vo->dob, false); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel_optional">Nationality:</td>
		<td><?php echo HTML::select('nationality', $country_list, $vo->nationality, true, false); ?> </td></tr>
	</tr>
	<?php if((DB_NAME == "am_reed_demo" || DB_NAME == "am_reed") && $vo->type == User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel_optional">Referral Source: </td>
		<td>
			<?php
			if(isset($vo->referral_source) && $vo->referral_source != '' && $activeReferralSource != 1)
				echo $vo->referral_source;
			else
				echo HTML::select('referral_source', $referral_sources, $vo->referral_source, true, false, true);
			?>
		</td>
	</tr>
	<?php } else { ?>
	<?php if($vo->type == User::TYPE_LEARNER){?>
		<tr>
			<td class="fieldLabel_optional">Referral Source: </td>
			<td><?php echo HTML::select('referral_source', $referral_sources, $vo->referral_source, true, false, true); ?>
				<span class="button" onclick="newReferralSource();">New</span>
				<span class="button" onclick="delReferralSource();">Delete</span>
			</td>
		</tr>
		<?php } ?>
	<?php } ?>
	<?php if((DB_NAME=="am_reed_demo" || DB_NAME == "am_reed") && $vo->type == User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel_optional">Referral Source Other - Description:</td>
		<td><textarea class="optional" name="ref_source_other_desc" id="ref_source_other_desc" cols="40" rows="3" ><?php echo htmlspecialchars((string)$vo->ref_source_other_desc); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Referral Date:</td>
		<td><?php echo HTML::datebox("referral_date", $vo->referral_date, false); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<?php if(DB_NAME=="am_baltic") {?>
		<td class="fieldLabel_optional">Induction Date</td>
		<?php } else { ?>
		<td class="fieldLabel_optional">Initial Appointment Date</td>
		<?php } ?>
		<td><?php echo HTML::datebox("initial_appointment_date", $vo->initial_appointment_date, false); ?></td>
	</tr>
    <tr>
        <?php if(DB_NAME=="am_baltic") {?>
        <td class="fieldLabel_optional">Capacity</td>
        <td><input class="optional" type="text" name="capacity" value="<?php echo htmlspecialchars((string)$vo->capacity); ?>" size="3" maxlength="3"/></td>
        <?php } ?>
    </tr>
	<?php if((DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo") && $vo->type == User::TYPE_LEARNER && $vo->id != '') {?>
	<tr>
		<td class="fieldLabel_optional">Business Code</td>
		<td><?php echo HTML::select('employer_business_code', $employer_business_codes, $vo->employer_business_code, true, false); ?></td>
	</tr>
	<?php } ?>
	<?php if((DB_NAME == "am_reed_demo" || DB_NAME == "am_reed") && $vo->type == User::TYPE_LEARNER){?>
	<tr>
		<td class="fieldLabel_optional">Job Goal 1 </td>
		<td><?php echo HTML::select('job_goal_1', $job_goals, $vo->job_goal_1, true, false, true); ?>
			<?php if($_SESSION['user']->isAdmin()) { ?>
				<!--<span class="button" onclick="newJobGoal();">New Job Goal</span>-->
				<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Goal 2 </td>
		<td><?php echo HTML::select('job_goal_2', $job_goals, $vo->job_goal_2, true, false, true); ?>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Goal 3 </td>
		<td><?php echo HTML::select('job_goal_3', $job_goals, $vo->job_goal_3, true, false, true); ?>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job Readiness</td>
		<td><?php echo HTML::select('job_readiness', $job_readiness, $vo->job_readiness, true, false, true); ?>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Office</td>
		<td><?php echo HTML::select('learner_office', $offices, $vo->learner_office, true, false, true); ?>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Signposting Organisation</td>
		<td><input class="optional" type="text" name="signposting_org" value="<?php echo htmlspecialchars((string)$vo->signposting_org); ?>" size="40" maxlength="199"/></td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME=="am_lcurve") {?>
	<tr>
		<td class="fieldLabel_optional">Learner Find Agent:</td>
		<td><input class="optional" type="text" name="learner_find_agent" onKeyDown="limitText(this.form.learner_find_agent,this.form.countdown,5);"
		           onKeyUp="limitText(this.form.learner_find_agent,this.form.countdown,5);" value="<?php echo htmlspecialchars((string)$vo->learner_find_agent); ?>" size="5"/><span style="color: #9e9e9e; font-size: 0.9em; font-style: italic;">(5 characters max.)</span></td>
	</tr>
	<?php }?>
	<?php if(DB_NAME=="am_edudo") { ?>
	<tr>
		<td class="fieldLabel_optional">Entered OnTo DIGIAPP</td>
		<td><?php echo HTML::datebox("enteredOnToDigiApp", $vo->enteredOnToDigiApp, false); ?></td>
	</tr>
	<?php } ?>
	<?php if((DB_NAME=="ams" || DB_NAME=="am_platinum" || DB_NAME=="am_pathway" || DB_NAME=="am_crackerjack") && $vo->type == User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel_optional">Previous School</td>
		<td>
			<?php
			echo HTML::select('prev_school', DAO::getResultset($link, "SELECT id, school_name, school_type FROM central.lookup_schools ORDER BY school_type, school_name "), $vo->prev_school, true);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if((DB_NAME=="ams" || DB_NAME=="am_platinum") && $vo->type == User::TYPE_LEARNER) { ?>
	<tr>
		<td class="fieldLabel_optional">Initially engaged by:</td>
		<td>
			<?php
			echo HTML::select('initially_engaged_by', DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), lookup_user_types.`description` FROM users INNER JOIN lookup_user_types ON users.type = lookup_user_types.id WHERE users.type != 5 AND users.web_access = 1 ORDER BY lookup_user_types.description, users.`firstnames`; "), $vo->initially_engaged_by, true);
			?>
		</td>
	</tr>
	<?php } ?>

	<tr style = "display: none">
		<td class="fieldLabel_compulsory">User type </td>
		<td><?php echo HTML::select('type', $people_types, $vo->type, false, true, false); ?></td>
	</tr>

	<?php
	if ( SystemConfig::getEntityValue($link, 'module_recruitment') && $people == 'Salesman' ) {
		// make non compulsory re 30/06/2011
		echo '<tr><td class="fieldLabel">Sales Region:</td>';
		echo '<td>'.HTML::select('department', $region_dropdown, $vo->department, true, false).'</td></tr>';
	}
	?>
</table>

<h4>Identifiers</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190"/><col/>
	<tr>
		<td class="fieldLabel_optional">Enrolment No:</td>
		<td><input class="optional" type="text" name="enrollment_no" value="<?php echo htmlspecialchars((string)$vo->enrollment_no); ?>" size="10" maxlength="100"/></td>
	</tr>
	<?php if(DB_NAME=='am_pera') { ?>
	<tr>
		<td class="fieldLabel_compulsory">National Insurance (NI):</td>
		<td><input class="compulsory" type="text" name="ni" value="<?php echo htmlspecialchars((string)$vo->ni); ?>" size="10" maxlength="9"/></td>
	</tr>
	<?php }else{ ?>
	<tr>
		<td class="fieldLabel_optional">National Insurance (NI):</td>
		<td><input class="optional" type="text" name="ni" value="<?php echo htmlspecialchars((string)$vo->ni); ?>" size="10" maxlength="9"/></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel_optional"><abbr title="LRS supplied, 10 digits, checksum validated">Unique Learner Number</abbr> (ULN):</td>
		<td>
			<input class="optional" type="text" id="L45" name="l45" value="<?php echo htmlspecialchars((string)$vo->l45); ?>" size="10" maxlength="10"/>
			<input type="hidden" id="old_L45" name="old_l45" value="<?php echo htmlspecialchars((string)$vo->l45); ?>" size="10" maxlength="10"/>
			<?php if($vo->id != '') {?>
			<!--<span class="button" onclick="verifyLearnerULN();">Verify Learner ULN from LRS</span>
			<span class="button" onclick="updateLRSRecord();">Update Learner Record in LRS</span>-->
			<?php } ?>
		</td>
	</tr>
	<?php if((SOURCE_LOCAL || DB_NAME=="am_gigroup") && $vo->type == User::TYPE_LEARNER) {?>
	<tr>
		<td class="fieldLabel_optional">Payroll Number:</td>
		<td><input class="optional" type="text" name="payroll_number" value="<?php echo htmlspecialchars((string)$vo->payroll_number); ?>" size="15" maxlength="15"/></td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME=="am_platinum" && $vo->type == User::TYPE_LEARNER) {?>
	<tr>
		<td class="fieldLabel_optional">UCI Number:</td>
		<td><input class="optional" type="text" name="uci_number" value="<?php echo htmlspecialchars((string)$vo->uci_number); ?>" size="10" maxlength="10"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Candidate Number:</td>
		<td><input class="optional" type="text" name="candidate_number" value="<?php echo htmlspecialchars((string)$vo->candidate_number); ?>" size="10" maxlength="10"/></td>
	</tr>
	<?php } ?>
</table>

<?php if($vo->type == User::TYPE_LEARNER && (SOURCE_LOCAL || DB_NAME == "am_crackerjack")){?>
<h4>Next of Kin</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190"/><col/>
	<tr>
		<td class="fieldLabel_optional">Title </td>
		<td>
			<?php
			$nok_titles = array(array('Mr', 'Mr.'), array('Mrs', 'Mrs.'), array('Ms', 'Ms.'), array('Miss', 'Miss'), array('Dr', 'Dr.'));
			echo HTML::select('nok_title', $nok_titles, $vo->nok_title, true, false, true);
			?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Name:</td>
		<td><input class="optional" type="text" name="nok_name" value="<?php echo htmlspecialchars((string)$vo->nok_name); ?>" size="30" maxlength="100"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Relationship:</td>
		<td><input class="optional" type="text" name="nok_rel" value="<?php echo htmlspecialchars((string)$vo->nok_rel); ?>" size="30" maxlength="100"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Telephone:</td>
		<td><input class="optional" type="text" name="nok_tel" value="<?php echo htmlspecialchars((string)$vo->nok_tel); ?>" size="30" maxlength="20"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Mobile:</td>
		<td><input class="optional" type="text" name="nok_mob" value="<?php echo htmlspecialchars((string)$vo->nok_mob); ?>" size="30" maxlength="20"/></td>
	</tr>
</table>
<?php } ?>

<?php if((SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL) && $vo->type == User::TYPE_LEARNER && SystemConfig::getEntityValue($link, 'miap.soap.enabled')) {?>
<h4>LRS Specific Fields</h4>
<p class="sectionDescription">These fields are required in order to register/update the learner's record to LRS web portal</p>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="190" /><col width="800" />
	<tr>
		<td class="fieldLabel_optional">Ability to share</td>
		<td>
			<?php
			echo HTML::select('ability_to_share', DAO::getResultset($link, "SELECT id, description FROM lookup_ability_to_share;"), $vo->ability_to_share, true);
			?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Verification Type</td>
		<td class="optional">
			<?php
			echo HTML::select('verification_type', DAO::getResultset($link, "SELECT code, description FROM lookup_verification_type;"), $vo->verification_type, true);
			?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Verification Type Other</td>
		<td><input class="optional" type="text" size="50" id="verification_type_other" onkeyup="verification_type_other_onkeypress();" name="verification_type_other" value="<?php echo htmlspecialchars((string)$vo->verification_type_other); ?>" /><span style="margin-left:10px;color:gray">(Only required if verification type is selected as 'Other')</span></td>
	</tr>
</table>
	<?php } ?>
<h4>Upload new picture</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190" />
	<tr>
		<td class="fieldLabel_optional">Picture:</td>
		<td><input class="optional" type="file" name="uploadedfile" /><span style="margin-left:10px;color:gray">(100KB max)</span></td>
	</tr>

	<?php if(DB_NAME=='am_reed' || DB_NAME=='am_reed_demo' || DB_NAME=='ams') {?>
	<tr>
		<td class="fieldLabel_optional">Registered By:</td>
		<td><?php echo HTML::select('who_created', $registered_by, $vo->who_created, true, false, true); ?></td>
	</tr>
	<?php } ?>
</table>


<?php if($vo->type == User::TYPE_ASSESSOR || $vo->type == User::TYPE_GLOBAL_MANAGER || $vo->type == User::TYPE_TUTOR || $vo->type == User::TYPE_BRAND_MANAGER) { ?>
<h3 id="sectionRoleSpecific">Role specific</h3>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190"/>
	<?php
    if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') { ?>
    <tr>
        <td class="fieldLabel_optional">Manager:</td>
        <td><?php echo HTML::select('supervisor', $manager_dropdown, $vo->supervisor, true, false, true); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional">IQA:</td>
        <td><?php echo HTML::select('iqa', $iqa_dropdown, $vo->iqa, true, false, true); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional">Reduced Sample:</td>
        <td><?php echo HTML::select('reduced_sample', $reduced_sample_dropdown, $vo->reduced_sample, true, false, true); ?></td>
    </tr>
    <?php } elseif($people=='Assessor') { ?>
	<tr>
		<td class="fieldLabel_optional">Supervisor:</td>
		<td><?php echo HTML::select('supervisor', $supervisor_dropdown, $vo->supervisor, true, false, true); ?></td>
	</tr>
	<?php } ?>

	<?php if($_SESSION['user']->isAdmin() && $vo->type== User::TYPE_GLOBAL_MANAGER) { ?>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Lead Assessors:</td>
		<td class="fieldValue"><?php  echo HTML::checkboxGrid('supervisor', $supervisor_checkboxes, $vo->supervisor, 3, true); ?></td>
	</tr>
	<?php } ?>

	<?php if($vo->type==User::TYPE_TUTOR)
{
	echo "<tr>";
	echo '<td class="fieldLabel_optional">IFL Membership No.</td>';
	echo '<td><input class="optional" type="text" name="ifl" value="'. htmlspecialchars((string)$vo->ifl) . '" size="10" maxlength="50"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="fieldLabel_optional">CRB Check: </td>';
	if($vo->crb==1)
		echo '<td><input class="optional" type="checkbox" name="crb" checked value="0" /></td>';
	else
		echo '<td><input class="optional" type="checkbox" name="crb" value="1" /></td>';
	echo '</tr>';
}
	?>
	<?php if($vo->type==User::TYPE_BRAND_MANAGER)
{
	$brands = DAO::getResultset($link,"SELECT id, title,null from brands order by title;");
	echo "<tr>";
	echo '<td class="fieldLabel_optional">Brand</td><td>';
	echo HTML::select('department', $brands, $vo->department, true, false, true);
	echo '</td></tr>';
}
	?>
</table>
	<?php } ?>

<?php if($vo->type == User::TYPE_LEARNER) {?>
<h3 id="sectionDiagnosticAssessments">Diagnostic Assessments</h3>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190"
	<tr>
		<td class="fieldLabel_optional">Diagnostic Assessment:</td>
		<td><input class="optional" type="text" name="bennett_test" value="<?php echo htmlspecialchars((string)$vo->bennett_test); ?>" size="3" maxlength="5" onKeyPress="return numbersonly(this, event);"/>
			<span style="color:gray;margin-left:10px">(numeric)</span></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Numeracy Test:</td>
		<td><?php echo HTML::select('numeracy', $pre_assessment_dropdown, $vo->numeracy, true, false, true); ?>
		<td class="fieldLabel_optional">Diagnostic Assessment?</td>
		<?php $checked = ($vo->numeracy_diagnostic==1)?"checked":"" ;?>
		<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "numeracy_diagnostic" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Literacy Test:</td>
		<td><?php echo HTML::select('literacy', $pre_assessment_dropdown, $vo->literacy, true, false, true); ?>

		<td class="fieldLabel_optional">Diagnostic Assessment?</td>
		<?php $checked = ($vo->literacy_diagnostic==1)?"checked":"" ;?>
		<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "literacy_diagnostic" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">ICT Test:</td>
		<td><?php echo HTML::select('ict', $pre_assessment_dropdown, $vo->ict, true, false, true); ?>
		<td class="fieldLabel_optional">ICT Assessment?</td>
		<?php $checked = ($vo->ict_diagnostic==1)?"checked":"" ;?>
		<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "ict_diagnostic" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">ESOL Test:</td>
		<td><?php echo HTML::select('esol', $pre_assessment_dropdown, $vo->esol, true, false, true); ?>
		<td class="fieldLabel_optional">Diagnostic Assessment?</td>
		<?php $checked = ($vo->esol_diagnostic==1)?"checked":"" ;?>
		<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "esol_diagnostic" /></td>
	</tr>
	<?php if(DB_NAME=="am_platinum") { ?>
	<tr>
		<td class="optional">GCSE English</td>
		<td><?php echo HTML::select('gcse_eng', DAO::getResultset($link, "SELECT id, description, null FROM central.lookup_gcse_grades ORDER BY id;"), $vo->gcse_eng, true, false, true); ?></td>
	</tr>
	<tr>
		<td class="optional">GCSE Maths</td>
		<td><?php echo HTML::select('gcse_maths', DAO::getResultset($link, "SELECT id, description, null FROM central.lookup_gcse_grades ORDER BY id;"), $vo->gcse_maths, true, false, true); ?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>

<?php $this->renderUserMetaData($link, $vo); ?>

<?php if(DB_NAME=="am_lead" || DB_NAME=="ams" || DB_NAME=="am_lema"){ ?>
<h3>User Defined Fields</h3>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190"
	<tr>
		<td class="fieldLabel_optional">Learner Defined Field 1 (LD1):</td>
		<td><input class="optional" type="text" name="ld1" value="<?php echo htmlspecialchars((string)$vo->ld1); ?>" size="30" maxlength="30" /><span style="color:gray;margin-left:10px">(max characters 30)</span></td></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Learner Defined Field 2 (LD2):</td>
		<td><input class="optional" type="text" name="ld2" value="<?php echo htmlspecialchars((string)$vo->ld2); ?>" size="30" maxlength="30" /><span style="color:gray;margin-left:10px">(max characters 30)</span></td>
	</tr>
</table>
	<?php } ?>

<?php if($vo->type == User::TYPE_LEARNER) {?>
<h3 id="sectionIlrSpecific">ILR Fields</h3>
<h4>Prior attainment</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_optional">Prior attainment level (<a style="font-size:small;" href="PriorLrngGuide201516.pdf" target="_blank">Prior Attainment Learning Guide</a>)<br>
			<?php echo HTML::select('l35', $PriorAttain_dropdown, $vo->l35, true, false); ?>
		</td>
	</tr>
</table>

<h4>Learning difficulties and disabilities</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_optional">Learning difficulties and/or disabilities and/or health problems <br>
			<?php echo HTML::select('l14', $LLDDHealthProb_dropdown, $vo->l14, true, false); ?></td>
	</tr>
	<!--<tr>
		<td class="fieldLabel_optional">Disability <br>
			<?php /*echo HTML::select('l15', $LLDDDS_dropdown, $vo->l15, true, false); */?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Learning difficulty <br>
			<?php /*echo HTML::select('l16', $LLDDLD_dropdown, $vo->l16, true, false); */?></td>-->
</table>

<h4>Learner support</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<td class="fieldLabel_optional">Learner support reason <br>
		<?php echo "<input class='optional' type='text' value='" . $vo->l34a . "' style='margin-right:10px;' id='L34a' name='l34a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>"; ?>
		<?php echo "<input class='optional' type='text' value='" . $vo->l34b . "' style='margin-right:10px;' id='L34b' name='l34b' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>"; ?>
		<?php echo "<input class='optional' type='text' value='" . $vo->l34c . "' style='margin-right:10px;' id='L34c' name='l34c' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>"; ?>
		<?php echo "<input class='optional' type='text' value='" . $vo->l34d . "' style='margin-right:10px;' id='L34d' name='l34d' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>"; ?>
	</td>
</table>

<h4>Employment status</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_optional">Prior to enrolment Learning Employment Status <br>
			<?php echo HTML::select('l37', $employment_status, $vo->l37, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Current employment status <br>
			<?php echo HTML::select('l47', $employment_status, $vo->l47, true, false); ?></td>
	</tr>
	<!--<tr>
		<td class="fieldLabel_optional">Date Employment Status Changed <br>
			<?php //echo HTML::datebox('l48', $vo->l48); ?>
	</tr>
-->
</table>

<h4>Monitoring data</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_optional">National learner monitoring (L40a)<br>
			<?php echo HTML::select('l40a', $L40_dropdown, $vo->l40a, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">National learner monitoring (L40b)<br>
			<?php echo HTML::select('l40b', $L40_dropdown, $vo->l40b, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Learner Provider Specified Monitoring (L42a)<br>
			<?php echo "<input class='optional' type='text' value='" . $vo->l42a . "' style='' id='L42a' name='l42a' maxlength=20 size=45></td>"; ?>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Learner Provider Specified Monitoring (L42b)<br>
			<?php echo "<input class='optional' type='text' value='" . $vo->l42b . "' style='' id='L42b' name='l42b' maxlength=20 size=45></td>"; ?>
	</tr>
</table>

<h4>Destination</h4>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<!--    <tr>
	<td class="fieldLabel_optional">L28 Eligibility for Enhanced Funding <br>
	<?php //echo HTML::select('l28a', $L28_dropdown, $vo->l28a, true, false); ?></td>
	<td class="fieldLabel_optional">L28 Eligibility for Enhanced Funding <br>
	<?php //echo HTML::select('l28b', $L28_dropdown, $vo->l28b, true, false); ?></td>
	</tr>
-->
	<tr>
		<td class="fieldLabel_optional">Destination <br>
			<?php
                if(strtotime('now') < strtotime("2015-08-01"))
                    echo HTML::select('l39', $L39_dropdown, $vo->l39, true, false);
                else
                    echo HTML::select('l39', $L39_dropdown, $vo->l39, true, false, false);
            ?></td>
	</tr>
</table>
<?php } ?>

<?php
if(DB_NAME=='am_reed_demo' || DB_NAME=='am_reed'  || DB_NAME=='ams' || DB_NAME=='am_demo' || DB_NAME=='am_template' || DB_NAME=='am_peraesf')
{
	echo '<h3 id="sectionESF">ESF</h3>';
	echo '<table><tr>';
	echo '<td>Create ESF ILR</td>';
	// Check if this learner already has a tr
	$username = $vo->username;
	$trs = DAO::getSingleValue($link, "select count(*) from tr where username = '$username'");
	if($trs)
		echo '<td class="optional"><input disabled type="checkbox" name = "is_esf" onclick=showHideBlock(document.getElementById("ESF1")) /></td></tr></table>';
	else
		echo '<td class="optional"><input type="checkbox" name = "is_esf" onclick=showHideBlock(document.getElementById("ESF1")) /></td></tr></table>';

	echo '<div style="display: none" id="ESF1">';
	//echo '<table><tr><td width=100>Course </td>';
	//$courses = DAO::getResultset($link, "select courses.id, title FROM courses");
	//echo '<td>' . HTML::select('course_id', $courses, '', true, false) . '</td></tr>';
	echo '<tr><td width=100 class="fieldLabel_compulsory">Contract </td>';
	$contracts = DAO::getResultset($link, "select contracts.id, title, CONCAT('Contract Year ',contract_year) FROM contracts where contract_year >= 2014 and active = 1 order by contract_year desc, title");
	echo '<td>' . HTML::select('contract_id', $contracts, '', true, true) . '</td></tr>';
	echo '<tr><td width=100 class="fieldLabel_compulsory">Start Date:</td>';
	echo '<td>' . HTML::datebox("start_date", "", true) . '</td></tr>';
	echo '</table></div>';

	echo '<div id ="plannedEndDate" style="display: none;">';
	echo '<table><tr><td width=100 class="fieldLabel_compulsory">Planned End Date:</td>';
	echo '<td>' . HTML::datebox("end_date", "", true) . '</td></tr></table>';
	echo '</div>';

}
?>

<?php if( $vo->id != '' && SystemConfig::getEntityValue($link, 'onefile.integration')) { ?>
<h3 id="sectionApplicationAccess">Onefile User</h3>
<p class="sectionDescription">Please select Onefile user from the list.<br>If the user exists in Onefile system then click on 'Refresh' otherwise click on "Create in Onefile" to create this user in Onefile.</p>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
<col width="190" />
	<tr>
		<td class="fieldLabel_optional">Select Onefile Organisation:</td>
		<td><?php echo HTML::selectChosen('onefile_organisation_id', Onefile::getOnefileOrganisationsDdl($link), $vo->onefile_organisation_id); ?></td>
	</tr>
	<tr>
        <td class="fieldLabel_optional">Select Onefile User:</td>
        <td>
			<?php 
			$onefile_users_list = [];
			if($vo->type == User::TYPE_ASSESSOR || $vo->type == User::TYPE_TUTOR || $vo->type == User::TYPE_VERIFIER)
			{
				$onefile_users_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'users_{$vo->type}'");
				if($onefile_users_list_from_db != '')
				{
					$onefile_users_list_from_db = json_decode($onefile_users_list_from_db);
					foreach($onefile_users_list_from_db AS $_onefile_user)
					{
						$onefile_users_list[] = [$_onefile_user->ID, $_onefile_user->LastName . ', ' . $_onefile_user->FirstName];
					}
				}
			}
						
			echo HTML::select('onefile_user_id', $onefile_users_list, $vo->onefile_user_id, true, false, true); 
			?>
			<button type="button" class="button" id="btnOnefileRefresh" onclick="refresh_onefile_users();">Refresh List</button></td>
		</td>
    </tr>
	<tr>
		<td></td>
		<td><button type="button" class="button" id="btnOnefileCreateUser" onclick="create_onefile_user();">Create in Onefile</button></td></td>
	</tr>
</table>
<?php } ?>

<h3 id="sectionApplicationAccess">Application Access</h3>
<p class="sectionDescription">On account creation or re-activation, Sunesis will generate
	a random passphrase and email it to the user. For added security the email will <i>not</i>
	include the username. The user should be informed of their username by separate communication, preferably
	by a channel other than email.</p>
<p class="sectionDescription">If the user does not have a working email
	address, you may alternatively specify the password yourself so that you may inform
	the user of it. In this case you should also advise the user to change their password, since
	someone other than themselves has knowledge of it (you).</p>
<?php if($_SESSION['user']->work_email == '' && $vo->web_access==0){echo '<p class="sectionDescription"><b>You do not have a work email address on your user record. You will be unable to activate this user.</b></p>';} ?>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="150" />
	<tr>
		<td class="fieldLabel_compulsory">Account:</td>
		<td><?php echo HTML::radioButtonGrid('web_access', $web_account, $vo->web_access, 2); ?></td>
	</tr>
	<?php if($vo->username == ''){ ?>
	<!-- The username may only be set during record creation -->
	<tr>
		<td width="140" class="fieldLabel_compulsory">Username:</td>
		<td><input class="compulsory" type="text" name="username"
		           value="<?php echo htmlspecialchars((string)$vo->username); ?>" onfocus="username_onfocus(this);" size="30"
		           maxlength="30" style="font-family:monospace"
		           onblur="this.value=this.value.toLowerCase()" />
			<span class="button" onclick="checkUsernameAvailability();">Check availability</span></td>
	</tr>
	<?php } else { ?>
	<!-- The username may not be edited -->
	<tr>
		<td width="140" class="fieldLabel_compulsory">Username:</td>
		<td style="font-family:monospace"><?php echo htmlspecialchars((string)$vo->username); ?>
			<input type="hidden" name="username" value="<?php echo htmlspecialchars((string)$vo->username); ?>" /></td>
	</tr>
	<?php } ?>
	<tr>
		<td valign="top">Pass phrase:</td>
		<td><input type="text" name="password" maxlength="50" size="30" style="font-family:monospace" />
			<span class="button" onclick="document.forms[0].elements['password'].value=dicewarePassword(4, 8, 50);">Generate</span><br/>
			<span style="color:gray">(8 - 50 characters, spaces allowed)</span></td>
	</tr>
	<?php if($vo->type != User::TYPE_LEARNER) {?>
        <tr>
            <td class="fieldLabel_compulsory">Active:</td>
            <td><?php echo HTML::radioButtonGrid('active', $active_status, $vo->active, 2); ?></td>
        </tr>
    	<?php } ?>
	<?php if((DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") && $vo->type != User::TYPE_LEARNER){?>
	<tr>
		<td class="fieldLabel_compulsory">Induction access:</td>
		<td><?php echo HTML::radioButtonGrid('induction_access', $induction_access, $vo->induction_access, 3); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Induction menus:</td>
		<td>
			<?php
				echo HTML::checkboxGrid('induction_menus', $induction_menus, explode(',', $vo->induction_menus));
			?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Operations access:</td>
		<td><?php echo HTML::radioButtonGrid('op_access', $induction_access, $vo->op_access, 3); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Operations menus:</td>
		<td>
			<?php
			echo HTML::checkboxGrid('op_menus', $op_menus, explode(',', $vo->op_menus));
			?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">FS Progress tab & report:</td>
		<td><?php echo HTML::radioButtonGrid('fs_progress_tab', [['1', 'Yes', ''], ['0', 'No', '']], $vo->fs_progress_tab, 3); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">FS Progress access:</td>
		<td><?php echo HTML::radioButtonGrid('fs_progress_access', [['R', 'Read', ''], ['W', 'Write', '']], $vo->fs_progress_access, 3); ?></td>
	</tr>
	<?php } ?>
</table>
<?php if($vo->type == User::TYPE_LEARNER && (DB_NAME=="ams" || DB_NAME=="am_baltic")) {?>
<h3>Employer Age Grant</h3>
<table>
	<tr>
		<td class="fieldLabel_optional">Learner is part of Age Grant allowance:</td>
		<td class="optional"><?php echo HTML::checkbox('age_grant', 1, $vo->age_grant, true, false); ?></td>
	</tr>
</table>
	<?php } ?>
<?php
switch($org->organisation_type)
{
	case '1':
		$o= 'System Owner';
		break;
	case '2';
		$o = 'Employer';
		break;
	case '3';
		$o = 'Training Provider';
		break;
	case '4';
		$o = 'Contract Holder';
		break;
	case '6';
		$o = 'School';
		break;
	case '7';
		$o = 'Dealer';
		break;
	case '8';
		$o = 'Awarding Body';
		break;
	default:
		$o = 'Work';
		break;
}

echo '<h3 id="sectionWorkContactDetails">' . $o . ' Contact Details</h3>';
?>


<p class="sectionDescription">It is <b>vital</b> that every user on the system has
	a valid work email address.</p>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" /><col />
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Current <?php echo $o; ?></td>
		<td><?php echo HTML::select('employer_id', $organisations, $vo->employer_id, true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Location</td>
		<td><?php echo HTML::select('employer_location_id', $locations, $vo->employer_location_id, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Postal Address</td>
		<td><?php echo $work_address->formatEdit(true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Telephone</td>
		<?php // re : 20/09/2011 : ensure on setup we don't pull across any centrally held contact information ?>
		<td><input class="optional" type="text" name="work_telephone" value="<?php echo htmlspecialchars((string)$vo->work_telephone);  ?>" size="20" maxlength="20"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Mobile</td>
		<?php // re : 20/09/2011 : ensure on setup we don't pull across any centrally held contact information ?>
		<td><input class="optional" type="text" name="work_mobile" value="<?php echo htmlspecialchars((string)$vo->work_mobile);  ?>" size="20" maxlength="20"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Fax</td>
		<?php // re : 20/09/2011 : ensure on setup we don't pull across any centrally held contact information ?>
		<td><input class="optional" type="text" name="work_fax" value="<?php echo htmlspecialchars((string)$vo->work_fax); ?>" size="20" maxlength="20"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Email</td>
		<?php // re : 20/09/2011 : ensure on setup we don't pull across any centrally held contact information ?>
		<td><input class="optional" type="text" name="work_email" value="<?php echo htmlspecialchars((string)$vo->work_email); ?>" size="50" maxlength="80"/></td>
		<!--<td>
			<?php /*echo HTML::emailbox('work_email', htmlspecialchars((string)$vo->work_email)); */?>
		</td>-->
	</tr>
</table>

<?php
// need to add in the
// #179 {0000000044}
$address_title = 'Home Contact Details';
if ( isset($vo->primary_address_title) ) {
	$address_title = $vo->primary_address_title.' (Primary Address)';
}
echo '<h3 id="sectionHomeContactDetails">'.$address_title.'</h3>';
?>

<table border="0" cellspacing="4" cellpadding="4">
	<col width="140" /><col />
	<tr>
		<td class="fieldLabel_optional" valign="top">Address</td>
		<td><?php if(DB_NAME=="am_reed_demo" || DB_NAME=="ams" || DB_NAME=="am_reed") echo $home_address->formatEdit(false,true); else echo $home_address->formatEdit(false,false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Telephone</td>
		<td><input class="optional" type="text" name="home_telephone" value="<?php echo htmlspecialchars((string)$vo->home_telephone); ?>" size="20" maxlength="20"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Mobile</td>
		<td><input class="optional" type="text" name="home_mobile" value="<?php echo htmlspecialchars((string)$vo->home_mobile); ?>" size="20" maxlength="20"/></td>
	</tr>
	<?php if(DB_NAME == "am_platinum") {?>
	<tr>
		<td class="fieldLabel_optional">Emergency Telephone</td>
		<td><input class="optional" type="text" name="tel_emergency" value="<?php echo htmlspecialchars((string)$vo->tel_emergency); ?>" size="20" maxlength="20"/></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel_optional">Fax</td>
		<td><input class="optional" type="text" name="home_fax" value="<?php echo htmlspecialchars((string)$vo->home_fax); ?>" size="20" maxlength="20"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Email</td>
		<td><input class="optional" type="text" name="home_email" value="<?php echo htmlspecialchars((string)$vo->home_email); ?>" size="30" maxlength="80"/></td>
	</tr>
    <?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") {?>
    <tr>
        <td class="fieldLabel_optional">Work Email</td>
        <td><input class="optional" type="text" name="learner_work_email" value="<?php echo htmlspecialchars((string)$vo->learner_work_email); ?>" size="30" maxlength="80"/></td>
    </tr>
    <?php } ?>

</table>

<?php


// #179 {0000000044} - store the number of additional addresses we have for the user
$learner_meta_addresses = 0;
// #179 - display all the other addresses stored against the user
if ( ( isset($vo->user_metadata) ) && ( isset($vo->user_metadata['multiple_addresses']) ) ) {
	$user_address_details = '';
	$user_addresses = $vo->user_metadata['multiple_addresses'];
	foreach ( $user_addresses as $address_label => $address_value ) {
		// reformat the address for display.
		$format_address = new Address($address_value, '');

		$format_address->set_prefix('address_');
		// change the form elements to array elements
		$format_address->set_suffix('[]');

		$user_address_details .= '<h3>'.htmlspecialchars((string)$address_value['address_title']).'</h3>';
		$user_address_details .= '<table border="0" cellspacing="4" cellpadding="4">';
		$user_address_details .= '	<col width="140" /><col />';
		// address title
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional" valign="top">Address Title</td>';
		$user_address_details .= '		<td><input class="optional" type="text" name="address_title[]" value="'.htmlspecialchars((string)$address_value['address_title']).'" size="30" maxlength="80"/></td>';
		$user_address_details .= '	</tr>';
		// set as primary address
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional" valign="top">Set as primary address?</td>';
		$user_address_details .= '		<td><input type="checkbox" name="address_primary[]" value="" /></td>';
		$user_address_details .= '	</tr>';
		// delete this address
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional" valign="top">Delete this address?</td>';
		$user_address_details .= '		<td><input type="checkbox" name="address_delete[]" value="" /></td>';
		$user_address_details .= '	</tr>';
		//
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional" valign="top">Address</td>';
		$user_address_details .= '		<td class="fieldValue">'.$format_address->formatEdit().'</td>';
		$user_address_details .= '	</tr>';
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional">Telephone</td>';
		$user_address_details .= '		<td><input class="optional" type="text" name="address_telephone[]" value="'.htmlspecialchars((string)$address_value['telephone']).'" size="20" maxlength="20"/></td>';
		$user_address_details .= '	</tr>';
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional">Mobile</td>';
		$user_address_details .= '		<td><input class="optional" type="text" name="address_mobile[]" value="'.htmlspecialchars((string)$address_value['mobile']).'" size="20" maxlength="20"/></td>';
		$user_address_details .= '	</tr>';
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional">Fax</td>';
		$user_address_details .= '		<td><input class="optional" type="text" name="address_fax[]" value="'.htmlspecialchars((string)$address_value['fax']).'" size="20" maxlength="20"/></td>';
		$user_address_details .= '	</tr>';
		$user_address_details .= '	<tr>';
		$user_address_details .= '		<td class="fieldLabel_optional">Email</td>';
		$user_address_details .= '		<td><input class="optional" type="text" name="address_email[]" value="'.htmlspecialchars((string)$address_value['email']).'" size="30" maxlength="80"/></td>';
		$user_address_details .= '	</tr>';
		$user_address_details .= '</table>';

		$learner_meta_addresses++;
	}
	echo $user_address_details;

}

if (DB_NAME=='am_lcpa' || DB_NAME=='am_lcpa_test') {
	echo '<a href="#" onclick="newaddress()" >add new address</a>';
	echo '<input type="hidden" name="newaddresscount" id="newaddresscount" value="1" />';
}
?>
<!--
<h3>Privilege Escalation</h3>
<p class="sectionDescription">This feature is used to allow a user to administrate document access,
and adopt the privileges, of users other than themselves. This is usually used at an
organisational and location level to grant a user local administrative privileges over staff and colleagues, but is
flexible enough to allow for many tiered levels of administrative privilege.</p>
<p class="sectionDescription">Note that wildcard identities (e.g. <code>*/acme</code> or <code>*/londonoffice/acme</code>) are automatically expanded to include all
employees below them. Wildcard identities are the recommended way to refer to whole organisations and locations
because they will avoid the necessity of updating this field when more staff are employed.</p>

<h4>Access Control List Administration</h4>
<p class="sectionDescription">In addition to controlling their own access to documents, this user can grant or deny
document access to the following additional identities.</p>
<div style="margin-left: 10px">
<?php //$acl->renderList($link, 'acl_filters', $vo->acl_filters, ACL::EVERYONE|ACL::GROUPS|ACL::EMPLOYEES|ACL::EMPLOYEE_WILDCARDS); ?>
</div>

<h4>Adopted Privileges</h4>
<p class="sectionDescription">In addition to their own privileges, this user will adopt the access privileges of the following
additional identities.</p>
<div style="margin-left: 10px">
<?php //$acl->renderList($link, 'acl_adopted_identities', $vo->acl_adopted_identities, ACL::EVERYONE|ACL::GROUPS|ACL::EMPLOYEES|ACL::EMPLOYEE_WILDCARDS); ?>
</div>
-->

</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<div id="dialogDuplicate" title="Possible duplicate">
	<p>The record you are editing is a possible duplicate of the record
		below. The match is made on forename, surname and date of birth (if provided).
		In order to facilitate duplicate detection, no account is taken of
		ULN and National Insurance number in the search for matching records.</p>
	<table style="margin-left:10px">
		<col width="160"/><col/>
		<tr>
			<td style="font-weight:bold">Firstnames</td>
			<td id="firstnames"></td>
		</tr>
		<tr>
			<td style="font-weight:bold">Surname</td>
			<td id="surname"></td>
		</tr>
		<tr>
			<td style="font-weight:bold">Date of birth</td>
			<td id="dob"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Gender</td>
			<td id="gender" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Employer</td>
			<td id="employer" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Training records</td>
			<td id="tr_count" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Learner ref (L03)</td>
			<td id="l03" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">ULN</td>
			<td id="uln" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">National insurance</td>
			<td id="ni" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Sunesis username</td>
			<td id="username" style="color:gray"></td>
		</tr>
	</table>
</div>
<?php if(SystemConfig::getEntityValue($link, 'miap.soap.enabled')) { ?>
<div id="find_by_uln_dialog" title="Verify Learner ULN"
     style="
        height: 500px;
	    width: 750px;
	    text-align: left;
	    margin-top: 20px;
	    margin-left: 5px;
	    vertical-align: middle;
	    display: table-cell;
        overflow-y: scroll; overflow-x: scroll; display:none;" >
</div>
<div id="update_learner_dialog" title="Update Learner in LRS Portal"
     style="
        height: 500px;
	    width: 270px;
	    text-align: left;
	    margin-top: 20px;
	    margin-left: 5px;
	    vertical-align: middle;
	    display: table-cell;
        overflow-y: scroll; overflow-x: scroll; display:none;" >
	<p id="contentholder"></p>
</div>
<div id="find_by_demographics_dialog" title="Learner Search Results"
     style="
        height: 500px;
	    width: 270px;
	    text-align: left;
	    margin-top: 20px;
	    margin-left: 5px;
	    vertical-align: middle;
	    display: table-cell;
        overflow-y: scroll; overflow-x: scroll; display:none;" >
	<p id="contentholder"></p>
</div>
	<?php } ?>

	<script language="javascript">

		<?php if($vo->type != ''){ ?>
		function refresh_onefile_users()
		{
			var onefile_organisation_id = $("#onefile_organisation_id").val();
			var url = 'do.php?_action=ajax_onefile&subaction=getOnefileUsers'
			+ "&sunesis_user_type=" + encodeURIComponent('<?php echo $vo->type; ?>')
			+ "&organisation_id=" + encodeURIComponent(onefile_organisation_id);

			$("button#btnOnefileRefresh").attr('disabled', true);
        	$("button#btnOnefileRefresh").html('Please wait ...');

			function onefileRefreshCallback()
			{
				var onefile_user_id_select = document.getElementById('onefile_user_id');
				onefile_user_id_select.disabled = true;
				ajaxPopulateSelect(onefile_user_id_select, 'do.php?_action=ajax_load_account_manager&subaction=load_onefile_users&sunesis_user_type=<?php echo $vo->type; ?>');
				onefile_user_id_select.disabled = false;

				$("button#btnOnefileRefresh").attr('disabled', false);
				$("button#btnOnefileRefresh").html('Refresh List');
			}

			var client = ajaxRequest(url, null, null, onefileRefreshCallback);

		}

		function create_onefile_user()
		{
			if(!confirm("This action will create a new user in Onefile system. Are you sure you want to continue?"))
			{
				return false;
			}

			var onefile_organisation_id = $("#onefile_organisation_id").val();
			var url = 'do.php?_action=ajax_onefile&subaction=createUserInOnefile'
			+ "&user_id=" + encodeURIComponent('<?php echo $vo->id; ?>')
			+ "&organisation_id=" + encodeURIComponent(onefile_organisation_id);

			$("button#btnOnefileCreateUser").attr('disabled', true);
        	$("button#btnOnefileCreateUser").html('Please wait');

			function onefileCreateUserCallback(client)
			{
				if (client) 
				{
					if(client.responseText == 200)
					{
						refresh_onefile_users();
					}
					else if(client.responseText == 400)
					{
						alert("Error: 400 Bad Request");
					}
					else if(client.responseText == 401)
					{
						alert("Error: 401 Unauthorized");
					}
					else if(client.responseText == 403)
					{
						alert("Error: 403 Forbidden");
					}
					else if(client.responseText == 500)
					{
						alert("Error: 500 Internal Server Error");
					}
					else
					{
						alert(client.responseText);
					}
				}

				$("button#btnOnefileCreateUser").attr('disabled', false);
        		$("button#btnOnefileCreateUser").html('Create in Onefile');
			}

			var client = ajaxRequest(url, null, null, onefileCreateUserCallback);
			
		}
		<?php } ?>

	</script>
</body>
</html>
