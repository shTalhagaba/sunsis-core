<?php
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
// mandatory grades
$mandatory_grade_options = preg_replace("/id=\"(.*)\"/", "class=\"compulsory\"", HTML::select('grade[]', $qual_grades, '', true, true, true));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Companies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/common.js" type="text/javascript"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="mobile/jquery.mobile-1.4.2.min.css">
	<script src="mobile/jquery-1.10.2.min.js"></script>
	<script src="mobile/jquery.mobile-1.4.2.min.js"></script>
	<script src="/js/jquery.min.js" type="text/javascript"></script>


	<title>Perspective - Sunesis</title>

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


	</script>
	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
	<script type="text/javascript">
		var GB_ROOT_DIR = "/assets/js/greybox/";
	</script>
	<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
	<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
</head>
<!--<body onload="body_onload()" id="registration">-->
<body id="registration">
<?php
$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
$filename = ($filename=='')?'perspective.png':$filename;
?>

<div data-role="page" id="pageone">
	<div data-role="header">
		<h1>Baltic Apprenticeship Application Form</h1>
	</div>

	<div data-role="main" class="ui-content">
		<p>Here you can apply for apprenticeships.</p>
		<a href="#pagetwo" class="ui-btn ui-icon-arrow-r ui-btn-icon-left" data-transition="slide">Proceed &raquo;</a>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pagetwo">
	<div data-role="header">
		<h1>Personal Information</h1>
	</div>

	<div data-role="main" class="ui-content">
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
				<td><label for="file"><?php echo $attachCVLabel; ?></label></td>
				<td><input class="<?php echo $cvLabelClass; ?>" type="file" name="uploadedfile" id="uploadedfile"   /></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory" valign="top"> Interests:</td>
				<td class="fieldValue"><div style="height: 150px; overflow-y: scroll; overflow-x: scroll;" ><?php echo HTML::checkboxGrid('cand_interests', $cand_interests, $cand_selected_interests); ?></div></td>
			</tr>
			<?php
			$source_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_source ORDER BY description;");
			$name_of_source_other_control = "source_other";
			$value_of_source_other_control = $candidate->source_other;
			$other_source_question = "How did you find out about Baltic Training?:";
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
			<tr>
				<td>
					<a href="#pageone" class="ui-btn ui-icon-arrow-l ui-btn-icon-left" data-transition="slide">&laquo; Previous</a><br>
				</td>
				<td>
					<a href="#pagethree" class="ui-btn ui-icon-arrow-r ui-btn-icon-left" data-transition="slide">Next &raquo;</a>
				</td>
			</tr>
		</table>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pagethree">
	<div data-role="header">
		<h1>Contact Details</h1>
	</div>

	<div data-role="main" class="ui-content">
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
				<td><?php echo HTML::select('region', DAO::getResultset($link, "select description, description, null from lookup_vacancy_regions order by description;"), '', true, true); ?></td>
			</tr>
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
				<td class="optional"><?php echo HTML::checkbox('job_by_email', 1, $candidate->job_by_email, true, false); ?></td>
			</tr>
			<tr>
				<td>
					<a href="#pagetwo" class="ui-btn ui-icon-arrow-l ui-btn-icon-left" data-transition="slide">&laquo; Previous</a><br>
				</td>
				<td>
					<a href="#pagefour" class="ui-btn ui-icon-arrow-r ui-btn-icon-left" data-transition="slide">Next &raquo;</a>
				</td>
			</tr>
		</table>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pagefour">
	<div data-role="header">
		<h1>Employment Status</h1>
	</div>

	<div data-role="main" class="ui-content">
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
				<td><input type="text" name="hours_per_week" id="hours_per_week" size="10" maxlength="5" value="<?php echo $candidate->hours_per_week; ?>" value="0" /></td>
			</tr>
			<tr>
				<td width="240" class="">If not employed, when was the last time that you worked:</td>
				<td>
					<?php $last_time_worked = array(array('0','Please select one'),array('6','Less than 6 months'),array('11','6-11 months'),array('23','12-23 months'),array('35','24-35 months'),array('36','Over 36 months'));
					echo HTML::select('last_time_worked', $last_time_worked, $candidate->time_last_worked, false, false, false);
					?>
				</td>
			</tr>
			<tr>
				<td>
					<a href="#pagethree" class="ui-btn ui-icon-arrow-l ui-btn-icon-left" data-transition="slide">&laquo; Previous</a><br>
				</td>
				<td>
					<a href="#pagefive" class="ui-btn ui-icon-arrow-r ui-btn-icon-left" data-transition="slide">Next &raquo;</a>
				</td>
			</tr>
		</table>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pagefive">
	<div data-role="header">
		<h1>Study Needs</h1>
	</div>

	<div data-role="main" class="ui-content">
		<p>
			You do not need to provide this information at this point if you would prefer not to.
		</p>
		<h4>Disability</h4>
		<?php
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
		$difficulty = DAO::getResultset($link, "SELECT LLDDCode AS Difficulty_Code, CONCAT(LLDDCode,' ',LLDDCode_Desc),NULL FROM lis201314.ilr_llddcode WHERE LLDDType = 'LD' ORDER BY LLDDCode LIMIT 0,8;");
		if($candidate->id != '')
		{
			$candidate_difficulties = DAO::getSingleColumn($link, "SELECT difficulty_code FROM candidate_difficulty WHERE candidate_id = " . $candidate->id);
			echo HTML::checkboxGrid('difficulty', $difficulty, $candidate_difficulties, 2, true);
		}
		else
			echo HTML::checkboxGrid('difficulty', $difficulty, '', 2, true);
		?>
		<table>
			<tr>
				<td>
					<a href="#pagefour" class="ui-btn ui-icon-arrow-l ui-btn-icon-left" data-transition="slide">&laquo; Previous</a><br>
				</td>
				<td>
					<a href="#pagesix" class="ui-btn ui-icon-arrow-r ui-btn-icon-left" data-transition="slide">Next &raquo;</a>
				</td>
			</tr>
		</table>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pagesix">
	<div data-role="header">
		<h1>Study History</h1>
	</div>

	<div data-role="main" class="ui-content">
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
		echo newQualificationSecondTable($link, $candidate->qualificationsOther);
		?>

		<a href="#nvq_quals" onclick="javascript:newqual('qual_two')">add another qualification</a>

		<table>
			<tr>
				<td>
					<a href="#pagefive" class="ui-btn ui-icon-arrow-l ui-btn-icon-left" data-transition="slide">&laquo; Previous</a><br>
				</td>
				<td>
					<a href="#pageseven" class="ui-btn ui-icon-arrow-r ui-btn-icon-left" data-transition="slide">Next &raquo;</a>
				</td>
			</tr>
		</table>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pageseven">
	<div data-role="header">
		<h1>Privacy Policy</h1>
	</div>

	<div data-role="main" class="ui-content">
		<p>
			In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.
		</p>
		<table>
			<tr>
				<td>
					<?php include_once('templates/tpl_terms_and_conditions.php'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<a href="#pagesix" class="ui-btn ui-icon-arrow-l ui-btn-icon-left" data-transition="slide">&laquo; Previous</a><br>
				</td>
			</tr>
		</table>

		<button onclick="javascript:return save();" class="button" >Register</button>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

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