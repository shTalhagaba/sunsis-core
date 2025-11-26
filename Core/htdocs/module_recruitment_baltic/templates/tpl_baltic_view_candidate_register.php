<?php
// date drop down populations
$day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
$month = array(array('','month'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
$year = array(array('','yyyy'));

$this_year = date("Y")+2;
$early_year = $this_year-60;

for($a = $this_year; $a >= $early_year; $a--) {
	$year[] = array($a,$a);
}
// - ie issue with onchange on array[] - removing it so verfiy this
$day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, true));
$day_options = preg_replace("/id=\"(.*)\"/", "", $day_options);
$mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, true));
$mon_options = preg_replace("/id=\"(.*)\"/", "", $mon_options);
$year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, true));
$year_options = preg_replace("/id=\"(.*)\"/", "", $year_options);

// none compulsory date fields
$qual_day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, false));
$qual_day_options = preg_replace("/id=\"(.*)\"/", "", $qual_day_options);
$qual_mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, false));
$qual_mon_options = preg_replace("/id=\"(.*)\"/", "", $qual_mon_options);
$qual_year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, false));
$qual_year_options = preg_replace("/id=\"(.*)\"/", "", $qual_year_options);

// GCSE / A / AS level options
$qual_level_one = array(array('GCSE','GCSE'),array('A', 'A Level'),array('AS','AS Level'));
// - ie issue with onchange on array[] - removing it so verfiy this
$qual_level_one_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_one, '', true, false, true));
$qual_level_one_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_one_options);

// NVQ / BTEC level options
$qual_level_two = array(array('NVQ','NVQ'),array('BTEC','BTEC'));
// - ie issue with onchange on array[] - removing it so verify this
$qual_level_two_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_two, '', true, false, true));
$qual_level_two_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_two_options);

// Grades for GCSE / A / AS level options
$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'));
// - ie issue with onchange on array[] - removing it so verify this
//$qualification_grade_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('grade[]', $qual_grades, '', true, false, true));
//$qualification_grade_options = preg_replace("/id=\"(.*)\"/", "",$qualification_grade_options);
// mandatory grades
$mandatory_grade_options = preg_replace("/id=\"(.*)\"/", "class=\"compulsory\"", HTML::select('grade[]', $qual_grades, '', true, true, true));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php if(DB_NAME=="am_baltic") { ?>
	<!-- for Google -->
	<meta name="description" content="Apply for apprenticeship opportunities in Baltic.  "/>
	<meta name="keywords" content="Baltic, baltic training services, apprenticeships, vacancies, , "/>
	<meta name="author" content="Perspective Limited" />
	<meta name="copyright" content="Perspective Limited" />
	<meta name="application-name" content="Sunesis" />

	<!-- for Facebook -->
	<meta property="og:title" content="Baltic Apprenticeships" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="http://baltictraining.com/wp-content/uploads/2014/06/logo1.png" />
	<meta property="og:url" content="https://baltic.sunesis.uk.net/do.php?_action=view_candidate_register"/>
	<meta property="og:description" content="Apply for apprenticeship opportunities."/>

	<!-- for Twitter -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="Baltic Apprenticeships" />
	<meta name="twitter:description" content="Apply for apprenticeship opportunities." />
	<meta name="twitter:image" content="http://baltictraining.com/wp-content/uploads/2014/06/logo1.png" />
	<?php } ?>
	<title>Perspective - Sunesis</title>
	<!-- link rel="stylesheet" href="/common.css" type="text/css" / -->
	<link rel="stylesheet" href="/css/core.css" type="text/css"/>
	<?php if(DB_NAME!='am_baltic' && DB_NAME!='am_baltic_demo')
{
	echo '<link rel="stylesheet" href="/css/open.css" type="text/css"/>';
}
else
{
	echo '<link rel="stylesheet" href="/css/open_baltic.css" type="text/css"/>';
}
	?>
	<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
	<?php
	// #176 - allow for client specific styling
	$css_filename = SystemConfig::getEntityValue($link, 'styling');
	if ( $css_filename != '' ) {
		echo '<link rel="stylesheet" href="/css/client/'.$css_filename.'" type="text/css"/>';
	}
	?>
	<script type="text/javascript" language="javascript">
		function source_onchange(ele)
		{
			var isVacancySelected = false;
		<?php if ( isset($_REQUEST['vac_id']) ) { ?>
			isVacancySelected = true;
			<?php } ?>

			if(ele.value == 17) // if value is Other then show the textfield to get the value
			{
				document.getElementById('lbl_source_other').style.visibility = 'visible';
				document.getElementById('txt_source_other').style.visibility = 'visible';
				document.getElementById('lbl_source_other').className = "compulsory";
				document.getElementById('txt_source_other').className = "compulsory";
			}
			else
			{
				document.getElementById('lbl_source_other').style.visibility = 'hidden';
				document.getElementById('txt_source_other').style.visibility = 'hidden';
				document.getElementById('lbl_source_other').value = '';
				document.getElementById('txt_source_other').value = '';
				document.getElementById('lbl_source_other').className = "optional";
				document.getElementById('txt_source_other').className = "optional";
			}
		}

		function last_education_onchange(event)
		{
//		alert(event.value);
//		if(event.value == 1)
//			document.getElementsByName('grade').className = 'optional';
//		else
//			document.getElementsByName('grade').className = 'compulsory';

			if(event.value == 1)
			{
				var fav_count = document.getElementsByName('grade[]');
				for (var i = 0; i <= 1; i++)
				{
					fav_count[i].className = 'optional';
				}
			}
			else
			{
				var fav_count = document.getElementsByName('grade[]');
				for (var i = 0; i <= 1; i++)
				{
					fav_count[i].className = 'compulsory';

				}
			}
		}

		function ShowRemainingCharacters()
		{
			var val = document.getElementById('extra_support_for_app').value;
			var cs = val.length;
			document.getElementById('rem_ch').innerHTML = cs + ' / ' + 950;
		}
		function charLimit(limitField, limitNum) {
			if (limitField.value.length > limitNum) {
				limitField.value = limitField.value.substring(0, limitNum);
			}
		}

	</script>
</head>
<body onload="body_onload()" id="registration">
<?php
$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
$filename = ($filename=='')?'perspective.png':$filename;
?>
<div id="recruitment">
<div id="customerlogo">
	<!--  img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis - <?php echo DB_NAME; ?> candidate registration" / -->
</div>
<?php
// #116 relmes
if ( ( !isset($_REQUEST['msg']) ) ) {
	?>
<div id="divWarnings"></div>
<div id="divMessages">
	<ul id="status">
		<li id="status_1" class="active">Personal Information</li>
		<li id="status_2" >Contact Details</li>
		<li id="status_3" >Employment Status</li>
		<li id="status_4" >Study Needs</li>
		<li id="status_5" >Study History</li>
		<li id="status_6" >Confirmation</li>
	</ul>
</div>
<div id="main">
<form name="recruitmentForm" action="/do.php?_action=save_candidate" method="post">
<input type="hidden" name="screen_width" />
<input type="hidden" name="screen_height" />
<input type="hidden" name="color_depth" />
<input type="hidden" name="flash" />
<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />
<input type="hidden" name="id" value="<?php echo (isset($_REQUEST['candidate_id'])?htmlspecialchars((string)$_REQUEST['candidate_id']):''); ?>" />
<input type="hidden" name="vacancies[]" value="<?php echo $vacancies; ?>" />
<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
<input type="hidden" name="candidate_created_by" value="candidate" />
<input type="hidden" name="applied_directly" value="1" />
	<?php // this margin is affecting the display at low resolutions ?>
	<?php
	if ( isset($_REQUEST['vac_id']) ) {
		$candidate_vacancy = Vacancy::loadFromDatabase($link, $_REQUEST['vac_id']);
		?>
	<h3>You are applying for this vacancy</h3>
	<input type="hidden" name="enrolled" value="<?php echo $candidate_vacancy->id; ?>" />
	<input type="hidden" name="applications[]" value="<?php echo $candidate_vacancy->id; ?>" />
	<h3>Job Title:</h3>
	<span><?php echo $candidate_vacancy->job_title; ?></span>
	<h3>Job Description:</h3>
	<div style="width: 700px; font-size: 0.8em;"><span><?php echo str_replace('�', '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#149;', $candidate_vacancy->description); ?></span></div>
		<?php
	}
	?>
<div id="wizard">
<div id="items">
<div id="registration_1" class="formentry" >
	<h1>Personal Information</h1>
	<p>
		Please complete as much of the information in the following form as you can.  Anything marked with an asterisk (*) we need in order to match you to the vacancies.
	</p>
	<p>
		If you do not have all the information required please give us a call on 01325 731050 to discuss how we can help you.
	</p>
	<table>
		<col width="150">
		<col width="350">
		<tr>
			<td width="250" class="" style="text-align: left;">First Name(s): <span style="color: red">&nbsp; * </span></td>
			<td><input class="compulsory" type="text" name="firstnames" value="<?php echo $candidate->firstnames; ?>"  size="40" maxlength="100"/></td>
		</tr>
		<tr>
			<td class="" style="text-align: left;">Family Name: <span style="color: red">&nbsp; * </span></td>
			<td><input class="compulsory" type="text" name="surname" value="<?php echo $candidate->surname; ?>"  size="40" maxlength="100"/></td>
		</tr>
		<tr>
			<td class="" style="text-align: left;">Gender: <span style="color: red">&nbsp; * </span></td>
			<td style="text-align: left;">
				<?php
				$gender = "SELECT id, description, null FROM lookup_gender;";
				$gender = DAO::getResultset($link, $gender);
				array_unshift($gender,array('','Please select one',''));
				echo HTML::select('gender', $gender, $candidate->gender, false, true);
				?>
			</td>
		</tr>
		<tr>
			<td class="">Ethnicity: <span style="color: red">&nbsp; * </span></td>
			<td>
				<?php
				$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), null from lis201213.ilr_ethnicity order by Ethnicity;");
				array_unshift($L12_dropdown,array('','Please select one',''));
				echo HTML::select('ethnicity', $L12_dropdown, $candidate->ethnicity, false, true);
				?>
			</td>
		</tr>
		<tr>
			<td class="">Date of Birth: <span style="color: red">&nbsp; * </span></td>
			<td>
				<?php
				$dob = array();
				if($candidate->dob != '')
				{
					$dob = explode('-', $candidate->dob);
					$dob[2] = ltrim($dob[2], '0');
					$dob[1] = ltrim($dob[1], '0');
				}
				else
				{
					$dob[0] = '';
					$dob[1] = '';
					$dob[2] = '';
				}

				echo HTML::select('dob_day', $day, $dob[2], false, true);
				echo HTML::select('dob_month', $month, $dob[1], false, true);
				echo HTML::select('dob_year', $year, $dob[0], false, true);
				?>
			</td>
		</tr>
		<tr>
			<td class="">National Insurance: <br/><small>format: LL######L (no spaces)</small></td>
			<td><input type="text" name="national_insurance" id="national_insurance" value="<?php echo $candidate->national_insurance; ?>" size="10" maxlength="10"/></td>
		</tr>
		<?php
		if(isset($candidateCVLink) AND $candidateCVLink != '&nbsp;')
		{
			echo "<tr>";
			echo "<td>Previous CV</td>";
			echo "<td>" . $candidateCVLink . "</td>";
			echo "</tr>";
			$attachCVLabel = "Attach New CV:";
			$cvLabelClass = "";
		}
		else
		{
			$attachCVLabel = "Attach CV:";
			$cvLabelClass = "optional";
		}
		?>

		<tr>
			<!--<td><label for="file"><?php /*echo $attachCVLabel; */?></label><span style="color: red">&nbsp; * </span></td>-->
			<td><label for="file"><?php echo $attachCVLabel; ?></label></td>
			<td><input class="<?php echo $cvLabelClass; ?>" type="file" name="uploadedfile" id="uploadedfile"   /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top"> Interests:</td>
			<td class="fieldValue"><div style="height: 150px; overflow-y: scroll; overflow-x: scroll;" ><?php echo HTML::checkboxGrid('cand_interests', $cand_interests, $cand_selected_interests); ?></div></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Is there anything that you feel you would require support with if placed in to an apprenticeship?:</td>
			<td>
				<textarea rows="5" cols="70" onKeyDown="charLimit(this.form.extra_support_for_app,950);" onkeyup="ShowRemainingCharacters();" name="extra_support_for_app" id="extra_support_for_app"><?php echo htmlspecialchars((string)$candidate->extra_support_for_app); ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><span style="font-size: 12px;" id="rem_ch">Maximum characters: 950</span></td>
		</tr>
		<?php
		$source_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_source ORDER BY description;");
		$name_of_source_other_control = "source_other";
		$value_of_source_other_control = $candidate->source_other;
		$other_source_question = (DB_NAME=="am_baltic") ? "How did you find out about Baltic Training?:" : "How did you find out about us?:";
		if ( isset($_REQUEST['vac_id']) )
		{
			$name_of_source_other_control = "source_vacancy";
			$value_of_source_other_control = $candidate->source_vacancy;
			$other_source_question = "How did you find out about this vacancy?:";
		}
		?>
		<td width="250" class="" style="text-align: left;"><?php echo $other_source_question ?> <span style="color: red">&nbsp; * </span></td>
		<td><?php echo HTML::select('source',$source_dropdown, $candidate->source, true, true); ?></td>
		<tr>
			<td width="250" class="" style="text-align: left;"><label id="lbl_source_other" style="visibility: hidden;">Please Specify: *</label></td>
			<td><input id="txt_source_other" type="text" name="<?php echo $name_of_source_other_control; ?>" style="visibility: hidden;" value="<?php echo $value_of_source_other_control; ?>"  size="40" maxlength="100" /></td>
		</tr>
		<!--<tr>
			<td><label for="file">Upload Your Photo:</label><span style="color: red">&nbsp; * </span></td>
			<td><input class="optional" type="file" name="uploadedphoto" id="uploadedphoto"  /></td>
		</tr>-->
	</table>
	<div class="navigation" >
		&nbsp;<button type="button" class="next button" onclick="uploadFile();" id="proceed_2" >Proceed &raquo;</button>
	</div>
</div>
<div id="registration_2" class="formentry" >
	<h1>Contact Details</h1>
	<table>
		<tr>
			<td width="250" class="">House name:</td>
			<td><input type="text" name="address1"  size="40" value="<?php echo $candidate->address1; ?>" maxlength="100"/></td>
		</tr>
		<tr>
			<td width="240" class="">Street and number: <span style="color: red">&nbsp; * </span></td>
			<td><input class="compulsory" type="text" name="address2" value="<?php echo $candidate->address2; ?>"  size="40" maxlength="100"/></td>
		</tr>
		<tr>
			<td class="">
				Town: <span style="color: red">&nbsp; * </span>
			</td>
			<td>
				<input class="compulsory" type="text" name="borough" size="40" value="<?php echo $candidate->borough; ?>" maxlength="100"/>
			</td>
		</tr>
		<tr>
			<td class="">County: <span style="color: red">&nbsp; * </span></td>
			<td>
				<?php echo HTML::select('county', $counties, $candidate->county, true, true); ?>
			</td>
		</tr>
		<tr>
			<td width="240" class="">Postcode: <span style="color: red">&nbsp; * </span></td>
			<td><input class="compulsory" type="text" name="postcode" id="postcode" size="8" value="<?php echo $candidate->postcode; ?>" maxlength="100"/></td>
		</tr>
		<tr>
			<td width="240" class="">Region: <span style="color: red">&nbsp; * </span></td>
			<td><?php echo HTML::select('region', DAO::getResultset($link, "select description, description, null from lookup_vacancy_regions order by description;"), $candidate->region, true, true); ?></td>
		</tr>
		<?php
		// -------------------------------
		// this was a RTTG requirement - we have removed
		// as not required for existing recruitment staff.
		// -------------------------------
		// <tr>
		// 	<td width="240" class="">Region: <span style="color: red">&nbsp; * </span></td>
		// 	<td>
		// <?php
		// 	// $region_dropdown = array(array('North West','North West',''), array('North East','North East',''), array('Midlands','Midlands',''), array('East Midlands','East Midlands',''), array('West Midlands','West Midlands',''), array('London North','London North',''), array('London South','London South',''), array('Peterborough','Peterborough',''), array('Yorkshire','Yorkshire',''));
		// 	$region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
		// 	$region_dropdown = DAO::getResultset($link, $region_dropdown);
		// 	echo HTML::select('region', $region_dropdown, '', false, false);
		//
		// 	</td>
		// </tr>
		?>

		<tr>
			<td width="240" class="">
				Telephone: <span style="color: red">&nbsp; * </span>
				<br/>
				<small>Please enter only numbers and spaces</small>
			</td>
			<td>
				<input class="compulsory" type="text" name="telephone" id="telephone" value="<?php echo $candidate->telephone; ?>" size="15" maxlength="20"/>
			</td>
		</tr>
		<tr>
			<td width="240" class="">Mobile:</td>
			<td><input type="text" name="mobile" id="mobile" size="15" value="<?php echo $candidate->mobile; ?>" maxlength="20"/></td>
		</tr>
		<tr>
			<td width="240" class="">Fax:</td>
			<td><input type="text" name="fax" id="fax" value="<?php echo $candidate->fax; ?>" size="15" maxlength="20"/></td>
		</tr>
		<tr>
			<td width="240" class="">Email: <span style="color: red">&nbsp; * </span></td>
			<td><input class="compulsory"  type="text" name="email" id="email" value="<?php echo $candidate->email; ?>" size="20" maxlength="100"/></td>
		</tr>
		<tr>
			<td width="240" class="">Jobs by email: </td>
			<!--					<td><input type="checkbox" name="job_by_email" id="job_by_email" checked="--><?php //$candidate->job_by_email; ?><!--"  /></td>-->
			<td class="optional"><?php echo HTML::checkbox('job_by_email', 1, $candidate->job_by_email, true, false); ?></td>
		</tr>
	</table>
	<div class="navigation" >
		<button type="button" class="previous button" id="bproceed_1" >&laquo; Back</button>
		<button type="button" class="next right button" id="proceed_3" >Proceed &raquo;</button>
	</div>
</div>
<div id="registration_3" class="formentry" >
	<h1>Employment Status</h1>
	<p>
		It is useful for us to know your current employment status as it affects how much support you may be entitled to receive. You do not need to provide this information at this point if you would prefer not to.
	</p>
	<table>
		<tr>
			<td width="250" class="">What is your employment status:</td>
			<td>
				<?php
				$employment_status = DAO::getResultset($link, "select id, status_description, null from lookup_candidate_employment_status order by id;");
				array_unshift($employment_status ,array('0','Please select one',''));
				echo HTML::select('employment_status', $employment_status, $candidate->employment_status, false, true);
				?>
			</td>
		</tr>
		<tr>
			<td width="240" class="">If employed, how many hours per week:</td>
			<td>
				<input type="text" name="hours_per_week" id="hours_per_week" size="2" maxlength="2" value="<?php echo $candidate->hours_per_week; ?>" />
				<span style="font-size: 10px;">Enter only digits</span>
			</td>
		</tr>
		<tr>
			<td width="240" class="">If not employed, when was the last time that you worked:</td>
			<td>
				<?php $last_time_worked = array(array('0','Please select one'),array('6','Less than 6 months'),array('11','6-11 months'),array('23','12-23 months'),array('35','24-35 months'),array('36','Over 36 months'));
				echo HTML::select('last_time_worked', $last_time_worked, $candidate->time_last_worked, false, false, false);
				?>
			</td>
		</tr>
	</table>
	<div class="navigation" >
		<button type="button" class="previous button" id="bproceed_2" >&laquo; Back</button>
		<button type="button" class="next right button" id="proceed_4" >Proceed &raquo;</button>
	</div>
</div>
<div id="registration_4" class="formentry" >
	<h1>Study Needs</h1>
	<p>
		You do not need to provide this information at this point if you would prefer not to.
	</p>
	<h4>Disability</h4>
	<?php
	// $disability = DAO::getResultset($link, "SELECT Disability_Code, CONCAT(Disability_Code, ' ', Disability_Desc), null from lis201011.ilr_l15_disability order by Disability_Code limit 0,10;");
	//$disability = DAO::getResultset($link, "SELECT Disability_Code, IF(LOCATE('(', Disability_Desc) > 0, CONCAT(Disability_Code,' ',LEFT(Disability_Desc,LOCATE('(', Disability_Desc)-2)), CONCAT(Disability_Code,' ',Disability_Desc)) AS Disability_Desc, IF(LOCATE('(', Disability_Desc) > 0, SUBSTRING(Disability_Desc,LOCATE('(', Disability_Desc)), '') AS Disability_Additional FROM lis201011.ilr_l15_disability order by Disability_Code limit 0,10;");
	$disability = DAO::getResultset($link, "SELECT LLDDCode AS Disability_Code, IF(LOCATE('(', LLDDCode_Desc) > 0, CONCAT(LLDDCode,' ',LEFT(LLDDCode_Desc,LOCATE('(', LLDDCode_Desc)-2)), CONCAT(LLDDCode,' ',LLDDCode_Desc)) AS Disability_Desc, IF(LOCATE('(', LLDDCode_Desc) > 0, SUBSTRING(LLDDCode_Desc,LOCATE('(', LLDDCode_Desc)), '') AS Disability_Additional FROM lis201314.ilr_llddcode WHERE LLDDType = 'DS' ORDER BY LLDDCode LIMIT 0,10");
	if($candidate_id!='')
	{
		$candidate_disabilities = DAO::getSingleColumn($link, "SELECT disability_code FROM candidate_disability WHERE candidate_id = " . $candidate->id);
		echo HTML::checkboxGrid('disability', $disability, $candidate_disabilities, 2, true);
	}
	else
		echo HTML::checkboxGrid('disability', $disability, '', 2, true);
	?>
	<h4>Learning Difficulty:</h4>
	<?php
	//$difficulty = DAO::getResultset($link, "SELECT Difficulty_Code, CONCAT(Difficulty_Code,' ',Difficulty_Desc),null from lis201011.ilr_l16_difficulty order by Difficulty_Code limit 0,8;");
	$difficulty = DAO::getResultset($link, "SELECT LLDDCode AS Difficulty_Code, CONCAT(LLDDCode,' ',LLDDCode_Desc),NULL FROM lis201314.ilr_llddcode WHERE LLDDType = 'LD' ORDER BY LLDDCode LIMIT 0,8;");
	if($candidate->id != '')
	{
		$candidate_difficulties = DAO::getSingleColumn($link, "SELECT difficulty_code FROM candidate_difficulty WHERE candidate_id = " . $candidate->id);
		echo HTML::checkboxGrid('difficulty', $difficulty, $candidate_difficulties, 2, true);
	}
	else
		echo HTML::checkboxGrid('difficulty', $difficulty, '', 2, true);
	?>
	<div class="navigation" >
		<button type="button" class="previous button" id="bproceed_3" >&laquo; Back</button>
		<button type="button" class="next right button" id="proceed_5" >Proceed &raquo;</button>
	</div>
</div>
<div id="registration_5" class="formentry" >
	<h1>Study History</h1>
	<p>
		You do not need to provide this information at this point if you would prefer not to.
	</p>
	<table>
		<tr>
			<td width="250" class="">Highest education completed:</td>
			<td>
				<?php
				$last_education = DAO::getResultset($link, "SELECT id, description, null FROM lookup_candidate_qualification order by id;");
				array_unshift($last_education ,array('0','Please select one',''));
				echo HTML::select('last_education', $last_education, $candidate->last_education, false, false);
				?>
			</td>
		</tr>
	</table>
	<?php
//	echo getCandidateQualificationInfo($candidate->qualifications, "first");
	echo newQualificationFirstTable($link, $candidate->qualifications, $candidate->last_education);
	?>
	<a href="#study_quals" onclick="javascript:newqual('qual_one')" >add another qualification</a>
	<a name="nvq_quals"></a>
	<p>
		Have you completed an NVQ or BTEC Qualification before?
		<?php

		if($candidate->previous_qualification == "1")
		{
			$yes = "checked";
			$no = "";
		}
		else
		{
			$yes = "";
			$no = "checked";
		}
		?>
		<input type="radio" onclick="if(window.previous_qualification_onclick){window.previous_qualification_onclick(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" onchange="if(window.previous_qualification_onchange){window.previous_qualification_onchange(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" value="1" name="previous_qualification" <?php echo $yes; ?> />
		Yes
		<input type="radio" onclick="if(window.previous_qualification_onclick){window.previous_qualification_onclick(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" onchange="if(window.previous_qualification_onchange){window.previous_qualification_onchange(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" value="0" name="previous_qualification" <?php echo $no; ?> />
		No
	</p>
	<?php
	//echo getCandidateQualificationInfo($candidate->qualifications, "second");
	echo newQualificationSecondTable($link, $candidate->qualificationsOther);
	?>

	<a href="#nvq_quals" onclick="javascript:newqual('qual_two')">add another qualification</a>
	<div class="navigation" >
		<button type="button" class="previous button" id="bproceed_4" >&laquo; Back</button>
		<button type="button" class="next right button" id="proceed_6" >Proceed &raquo;</button>
	</div>
</div>

<div id="registration_6" class="formentry">
	<h1>Privacy Policy</h1>
	<p>
		In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.
	</p>
	<table>
		<tr>
			<td>
				<?php include_once('templates/tpl_terms_and_conditions.php'); ?>
			</td>
		</tr>
	</table>
	<div class="navigation" >
		<button type="button" class="previous button" id="bproceed_5" >&laquo; Back</button>
		<button onclick="javascript:return save();" class="button" >Register</button>
	</div>
</div>
</div>
</div>
</form>
</div>
<script type="text/javascript">
	//<![CDATA[
	/*
    * relmes: extra validation for candidate registration requirements
    */
	var ele = document.getElementById("national_insurance");
	ele.validate = function() {
		/*
		* only concerned with ni if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[A-Za-z]{2}[0-9]{2}[0-9]{2}[0-9]{2}[A-Za-z]{1}$/ ) ) {
				alert("Incorrect format of National Insurance Number 'LL######L'");
				this.focus();
				return false;
			}
		}
		return true;
	}

	var email_valid = document.getElementById("email");
	email_valid.validate = function() {
		/*
		* only concerned with email if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/ ) ) {
				alert("Incorrect format for Email Address");
				this.focus();
				return false;
			}
		}
		return true;
	}

	var mobile_valid = document.getElementById("mobile");
	mobile_valid.validate = function() {
		/*
		* only concerned with email if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[0-9\s]{1,20}$/ ) ) {
				alert("Incorrect format for Mobile Number");
				this.focus();
				return false;
			}
		}
		return true;
	}

	var fax_valid = document.getElementById("fax");
	fax_valid.validate = function() {
		/*
		* only concerned with email if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[0-9\s]{1,20}$/ ) ) {
				alert("Incorrect format for Mobile Number");
				this.focus();
				return false;
			}
		}
		return true;
	}

	var telephone_valid = document.getElementById("telephone");
	telephone_valid.validate = function() {
		/*
		* only concerned with email if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[0-9\s]{1,20}$/ ) ) {
				alert("Incorrect format for Telephone Number");
				this.focus();
				return false;
			}
		}
		return true;
	}

	var hours_per_week_valid = document.getElementById("hours_per_week");
	hours_per_week_valid.validate = function() {
		if ( this.value != ""  && this.value != "0.00" ) {
			if( !this.value.match( /^\d+$/ ) ) {
				alert("Incorrect format for number of hours per week");
				this.focus();
				return false;
			}
		}
		return true;
	}

	var postcode_valid = document.getElementById("postcode");
	postcode_valid.validate = function() {
		<?php // match from http://www.cabinetoffice.gov.uk/media/291370/bs7666-v2-0-xsd-PostCodeType.htm ?>
		if( !this.value.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) ) {
			alert("Incorrect format for Postcode");
			this.focus();
			return false;
		}
		return true;
	}

	var cv_upload = document.getElementById("uploadedfile");
	cv_upload.validate = function() {
		/*
				 * only concerned with CV if its being uploaded.
				 */
		if ( this.value != "" ) {
			extArray = new Array(".doc", ".pdf", ".docx");
			ext = this.value.slice(this.value.indexOf(".")).toLowerCase();
			for (var i = 0; i < extArray.length; i++) {
				if (extArray[i] == ext) {
					return true;
				}
			}
			alert("We only accept .doc/.docx or .pdf files for your CV");
			this.focus();
			return false;
		}
	}
	//]]>
</script>
	<?php
}
else if ( isset($_REQUEST['msg']) ) {
	// check if the enrolled flag is set to allow
	// a registrant to apply for a particular course
	$vacancy_enroll = '';
	if ( $_REQUEST['msg'] == 1 ) {
		if ( isset($_REQUEST['enrolled']) && is_int($_REQUEST['enrolled']) ) {
			$vacancy_enroll = $_REQUEST['enrolled'];
			$candidate_vacancy = Vacancy::loadFromDatabase($link, $vacancy_enroll);
			$candidate_vacancy->update($link);
		}
		?>

	<div id="divMessages">
		Your registration has been successful.
	</div>
	<div id="main" style="text-align: center;" >
		<!--		Thank you for taking the time to complete our registration form. [--><?php //echo $vacancy_enroll; ?><!--]-->
		<!--		We will be in contact with you when an appropriate course becomes available. Return to the <a href="--><?php //echo $_SERVER['PHP_SELF'].'?_action=login'; ?><!--">login page</a>-->
		<!--		<br/>-->
		<!--		<br/>-->
		<!--		If you experience any difficulties with registration please contact us via the help desk at: <a href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>-->
		<!--		<br/>-->
		<!--		Or help desk hotline: 0121 5069667-->
		<?php if(DB_NAME=="am_baltic") {?>
		Your CV has been sent to the Apprenticeship Recruitment Team. We will be in touch with you shortly.
		<br/><br/>
		If you would like further information please contact us:
		<br/>
		Baltic Training Services Ltd.
		<br/>
		Tel: 01325 731 050
		<br/>
		Fax: 01325 317 156
		<br/>
		Email: <a href="mailto:yourfuture@baltictraining.com">yourfuture@baltictraining.com</a>
		<?php }else{ ?>
			Your CV has been sent to the Apprenticeship Recruitment Team. We will be in touch with you shortly.
			<br/><br/>
			If you would like further information please contact us:
			<br/>
			Sunesis Demo.
			<br/>
			Tel: 00000 000 000
			<br/>
			Fax: 0000 000 000
			<br/>
			Email: <a href="mailto:mail@email.com">mail@email.com</a>
		<?php } ?>
	</div>
		<?php
	}
	elseif( $_REQUEST['msg'] == 2 ) {
		?>
	<div id="divMessages">
		We already have your details.
	</div>
	<div id="main" style="text-align: center;" >
		If you would like to speak to anyone regarding this, please use the details below.  Alternatively you can begin the <a href="<?php echo $_SERVER['PHP_SELF'].'?_action=view_candidate_register'; ?>">registration process </a> again or return to the <a href="<?php echo $_SERVER['PHP_SELF'].'?_action=login'; ?>">login page</a>
		<br/>
		<br/>
		If you experience any difficulties with registration please contact us via the help desk at: <a href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>
		<br/>
		Or help desk hotline: 0121 5069667
	</div>
		<?php
	}
	elseif( $_REQUEST['msg'] == 3 ) {
		?>
	<div id="divMessages">
		<h1>We are sorry, we have been unable to save your details at this time!</h1>
	</div>
	<div id="main" style="text-align: center;" >
		If you would like to speak to anyone regarding this, please use the details below.</a>
		<br/>
		Please contact us at: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?></a>
		<br/>
		Or help desk hotline: <?php echo SystemConfig::getEntityValue($link, 'recruitment_contact'); ?>
	</div>
		<?php
	}
}
?>
</div>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/sunesis-registration.js" type="text/javascript"></script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
<?php
function getMandatoryQualificationGradeList($grade = false, $mandatory, $last_education = false)
{
	if($mandatory AND $last_education > 1)
		$compulsory = "true";
	else
		$compulsory = "";

//var_dump($compulsory); exit;

	if(!$grade)
		$grade = '';
	$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'),array('NA','N/A'));
//	return $mandatory_grade_options = preg_replace("/id=\"(.*)\"/", "class=\"$class\"", HTML::select('grade[]', $qual_grades, $grade, true, true, true));
	return HTML::select('grade[]', $qual_grades, $grade, true, $compulsory, true);
}

function getQualificationLevelList(PDO $link, $level = false)
{
	if(!$level)
		$level = '';
	// GCSE / A / AS level options
	$qual_level_one = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_qualification WHERE description IN ('GCSE' ,'A Level', 'AS Level') ");
	// - ie issue with onchange on array[] - removing it so verfiy this
	$qual_level_one_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_one, $level, true, false, true));
	return $qual_level_one_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_one_options);
}

function getQualLevel2(PDO $link, $level = false)
{
	if(!$level)
		$grade = '';
	// NVQ / BTEC level options
	$qual_level_two = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_qualification WHERE description IN ('BTEC' ,'NVQ') ");
	// - ie issue with onchange on array[] - removing it so verify this
	$qual_level_two_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level_1[]', $qual_level_two, $level, true, false, true));
	return $qual_level_two_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_two_options);

}
function newQualificationFirstTable(PDO $link, $candidateQualifications, $last_education = false)
{
	$table = '<table class="resultset" id="qual_one" >';
	$table .= '  	<thead>';
	$table .= '		<tr>';
	$table .= '	  		<th>GCSE/A/AS Level</th>';
	$table .= '	  		<th>Subject</th>';
	$table .= '	  		<th>Grade</th>';
	$table .= '		</tr>';
	$table .= '  	</thead>';
	$table .= '  	<tbody>';
	if(sizeof($candidateQualifications) > 0)
	{
		$counter = 0;
		foreach($candidateQualifications AS $qualification)
		{
			if(($qualification['level'] == 'GCSE' AND $qualification['subject'] == "English") OR ($qualification['level'] == 'GCSE' AND $qualification['subject'] == "Maths"))
			{
				$table .= '<tr>';
				$table .= '<td>' . $qualification['level'] . '<input type="hidden" name="level[]" value="' . $qualification['level'] . '" /></td>';
				$table .= '<td>'. $qualification['subject'] . '<input type="hidden" name="subject[]"  value="' . $qualification['subject'] . '" /></td>';
				$table .= '<td>' . getMandatoryQualificationGradeList($qualification['grade'], true, $last_education) . '<span style="color: red">&nbsp; * </span></td>';
				$table .= '</tr>';
			}
			else
			{
				$table .= '<tr>';
				$table .= '<td>' . getQualificationLevelList($link, $qualification['level']) . '</td>';
				$table .= '<td><input type="text" name="subject[]"  size="40" maxlength="100" value="'. $qualification['subject'] . '"/></td>';
				$table .= '<td>' . getMandatoryQualificationGradeList($qualification['grade'], false, $last_education) . '</td>';
				$table .= '</tr>';

			}
		}
		if(sizeof($candidateQualifications) == 2)
		{
			$table .= '<tr>';
			$table .= '<td>' . getQualificationLevelList($link) . '</td>';
			$table .= '<td><input type="text" name="subject[]"  size="40" maxlength="100" /></td>';
			$table .= '<td>' . getMandatoryQualificationGradeList('', false, $last_education) . '</td>';
			$table .= '</tr>';
		}
	}
	else
	{
		$table .= '<tr>';
		$table .= '<td>GCSE<input type="hidden" name="level[]" value="2" /></td>';
		$table .= '<td>English<input type="hidden" name="subject[]"  value="English" /></td>';
		$table .= '<td>' . getMandatoryQualificationGradeList('', true, $last_education) . '<span style="color: red">&nbsp; * </span></td>';
		$table .= '</tr>';
		$table .= '<tr>';
		$table .= '<td>GCSE<input type="hidden" name="level[]" value="2" /></td>';
		$table .= '<td>Maths<input type="hidden" name="subject[]"  value="Maths" /></td>';
		$table .= '<td>' . getMandatoryQualificationGradeList('', true, $last_education) . '<span style="color: red">&nbsp; * </span></td>';
		$table .= '</tr>';
		$table .= '<tr>';
		$table .= '<td>' . getQualificationLevelList($link) . '</td>';
		$table .= '<td><input type="text" name="subject[]"  size="40" maxlength="100" /></td>';
		$table .= '<td>' . getMandatoryQualificationGradeList('', false, $last_education) . '</td>';
		$table .= '</tr>';

	}
	$table .= '</tbody></table>';
	return $table;
}

function newQualificationSecondTable(PDO $link, $candidateQualifications)
{
	$table = '<table class="resultset" id="qual_two" >';
	$table .= '  	<thead>';
	$table .= '		<tr>';
	$table .= '	  		<th>Level</th>';
	$table .= '	  		<th>Course</th>';
	$table .= '	  		<th>Date Completed</th>';
	$table .= '		</tr>';
	$table .= '  	</thead>';
	$table .= '  	<tbody>';
	if(sizeof($candidateQualifications) > 0)
	{
		foreach($candidateQualifications AS $qualification)
		{
			$table .= '<td>'. getQualLevel2($link, $qualification['level']) . '</td>';
			$table .= '<td><input type="text" name="subject_1[]"  size="40" maxlength="100" value="'. $qualification['subject'] . '"/></td>';
			if($qualification['date'] != NULL)
			{
				$date = explode('-', $qualification['date']);
				$date[0] = ltrim($date[0], '0');
				$date[1] = ltrim($date[1], '0');
				$date[2] = ltrim($date[2], '0');

				$table .= '<td><input type="hidden" name="grade_1[]" value="~" />';
				$table .= preg_replace("/\r\n/","", getDateDropDown($date[2], "day"));
				$table .= preg_replace("/\r\n/","", getDateDropDown($date[1], "month"));
				$table .= preg_replace("/\r\n/","", getDateDropDown($date[0], "year"));
				$table .= '</td>';
			}
			else
			{
				$table .= '<td><input type="hidden" name="grade_1[]" value="~" />';
				$table .= preg_replace("/\r\n/","", getDateDropDown('', "day"));
				$table .= preg_replace("/\r\n/","", getDateDropDown('', "month"));
				$table .= preg_replace("/\r\n/","", getDateDropDown('', "year"));
				$table .= '</td>';
			}
			$table .= '</tr>';
		}
	}
	else
	{
		$table .= '<td>'. getQualLevel2($link) . '<input type="hidden" name="level_1[]" /></td>';
		$table .= '<td><input type="text" name="subject_1[]"  size="40" maxlength="100" /></td>';

		$table .= '<td><input type="hidden" name="grade_1[]" value="~" />';
		$table .= preg_replace("/\r\n/","", getDateDropDown('', "day"));
		$table .= preg_replace("/\r\n/","", getDateDropDown('', "month"));
		$table .= preg_replace("/\r\n/","", getDateDropDown('', "year"));
		$table .= '</td>';

		$table .= '</tr>';
	}
	$table .= '</tbody></table>';
	return $table;
}

function getDateDropDown($input = false, $output)
{
	// date drop down populations
	$day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
	$month = array(array('','mon'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
	$year = array(array('','yyyy'));

	$this_year = date("Y")+2;
	$early_year = $this_year-60;

	for($a = $this_year; $a >= $early_year; $a--) {
		$year[] = array($a,$a);
	}
	// - ie issue with onchange on array[] - removing it so verfiy this
	$day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, true));
	$day_options = preg_replace("/id=\"(.*)\"/", "", $day_options);
	$mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, true));
	$mon_options = preg_replace("/id=\"(.*)\"/", "", $mon_options);
	$year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, true));
	$year_options = preg_replace("/id=\"(.*)\"/", "", $year_options);

	// none compulsory date fields
	$qual_day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, $input, false, false));
	$qual_day_options = preg_replace("/id=\"(.*)\"/", "", $qual_day_options);
	$qual_mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, $input, false, false));
	$qual_mon_options = preg_replace("/id=\"(.*)\"/", "", $qual_mon_options);
	$qual_year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, $input, false, false));
	$qual_year_options = preg_replace("/id=\"(.*)\"/", "", $qual_year_options);

	switch($output)
	{
		case 'day':
			return $qual_day_options;
		case 'month':
			return $qual_mon_options;
		case 'year':
			return $qual_year_options;
	}
	return false;
}
?>