<!DOCTYPE html>
<html>
<head>
	<title>Candidate Application Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="mobile/jquery.mobile-1.4.2.min.css">
	<script src="mobile/jquery-1.10.2.min.js"></script>
	<script src="mobile/jquery.mobile-1.4.2.min.js"></script>
</head>
<body>
<script>
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

	function onSuccess(data, status) {
		data = $.trim(data);
		$("#notification").text(data);
	}

	function onError(data, status) {

	}

	/*$(document).ready(function () {
		$("#submit").click(function () {

			var formData = $("#candidateForm").serialize();

			$.ajax({
				type:"POST",
				url:"do.php?_action=save_mobile_2",
				cache:false,
				data:formData,
				success:onSuccess,
				error:onError
			});

			return false;
		});
	});*/


</script>


<div data-role="page" id="pageone">
	<div data-role="header">
		<h1>Candidate Application Form</h1>
	</div>

	<div data-role="main" class="ui-content">
		<form method="post" id="candidateForm" enctype="multipart/form-data" action="do.php?_action=save_candidate" data-ajax="false">

			<div class="ui-field-contain">
				<label for="firstnames">First Name:</label>
				<input type="text" name="firstnames" id="firstnames" value="" placeholder="What's Your First Name?">
			</div>

			<div class="ui-field-contain">
				<label for="surname">Surname:</label>
				<input type="text" name="surname" id="surname" value="" placeholder="What's Your Surname?">
			</div>

			<div class="ui-field-contain">
				<label for="gender">Gender:</label>
					<?php
						$gender = "SELECT id, description, null FROM lookup_gender;";
						$gender = DAO::getResultset($link, $gender);
						array_unshift($gender,array('','Please select one',''));
						echo HTMLMobile::select('gender', $gender, '', false, true);
					?>
			</div>

			<div class="ui-field-contain">
				<label for="ethnicity">Ethnicity:</label>
					<?php
						$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), null from lis201213.ilr_ethnicity order by Ethnicity;");
						array_unshift($L12_dropdown,array('','Please select one',''));
						echo HTMLMobile::select('ethnicity', $L12_dropdown, $candidate->ethnicity, false, true);
					?>
			</div>

			<div class="ui-field-contain">
				<label for="dob">Date of Birth:</label>
				<input type="date" name="dob" id="dob" value="">
			</div>

			<div class="ui-field-contain">
				<label for="national_insurance">National Insurance:</label>
				<input type="text" name="national_insurance" id="national_insurance" value="" placeholder="What's Your National Insurance?">
			</div>

			<div class="ui-field-contain">
				<label for="attachment">Attach CV:</label>
				<input type="file" name="uploadedfile" id="uploadedfile" multiple data-role="none">
			</div>

			<div class="ui-field-contain">
				<fieldset data-role="controlgroup">
					<legend>Interests:</legend>
					<?php
					$cand_interests = DAO::getResultset($link, 'SELECT id, description FROM lookup_candidate_interests ORDER BY description', DAO::FETCH_ASSOC);
					foreach($cand_interests AS $interest)
					{
						echo '<input type="checkbox" name="cand_interests[]" id="interest_' . $interest['id'] . '" value="' . $interest['id'] . '">';
						echo '<label for="interest_' . $interest['id'] . '">' . $interest['description'] . '</label>';
					}
					?>
				</fieldset>


			</div>

			<div class="ui-field-contain">
				<label for="name">Is there anything that you feel you would require support with if placed in to an apprenticeship?</label>
				<textarea rows="5" cols="70" onKeyDown="charLimit(this.form.extra_support_for_app,950);" onkeyup="ShowRemainingCharacters();" name="extra_support_for_app" id="extra_support_for_app"><?php echo htmlspecialchars((string)$candidate->extra_support_for_app); ?></textarea>
				<label id="rem_ch">Maximum characters: 950</label>
			</div>

			<div class="ui-field-contain">
				<label for="source">Source:</label>
				<?php
					$source_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_source ORDER BY description;");
					echo HTMLMobile::select('source',$source_dropdown, '', true, true);
				?>
			</div>

			<div class="ui-field-contain">
				<label for="address1">House Name/Number:</label>
				<input type="text" name="address1" id="address1" value="" placeholder="What's Your House Number/Name?">
			</div>

			<div class="ui-field-contain">
				<label for="address2">Street Name/Number:</label>
				<input type="text" name="address2" id="address2" value="" placeholder="What's Your Street?">
			</div>

			<div class="ui-field-contain">
				<label for="address2">Town:</label>
				<input type="text" name="borough" id="borough" value="" placeholder="What's Your Street?">
			</div>

			<div class="ui-field-contain">
				<label for="ethnicity">County:</label>
				<?php
				$sql = "SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC;";
				$counties = DAO::getResultSet($link, $sql);
				echo HTMLMobile::select('county', $counties, '', false, true);
				?>
			</div>

			<div class="ui-field-contain">
				<label for="postcode">Town:</label>
				<input type="text" name="postcode" id="postcode" value="" placeholder="What's Your Post Code?">
			</div>

			<div class="ui-field-contain">
				<label for="region">Region:</label>
				<?php
				$regions = DAO::getResultset($link, "select description, description, null from lookup_vacancy_regions order by description;");
				echo HTMLMobile::select('region', $regions, '', false, true);
				?>
			</div>

			<div class="ui-field-contain">
				<label for="telephone">Telephone:</label>
				<input type="text" name="telephone" id="telephone" value="" placeholder="What's Your Telephone?">
			</div>

			<div class="ui-field-contain">
				<label for="mobile">Mobile:</label>
				<input type="text" name="mobile" id="mobile" value="" placeholder="What's Your Mobile?">
			</div>

			<div class="ui-field-contain">
				<label for="fax">Fax:</label>
				<input type="text" name="fax" id="fax" value="" placeholder="What's Your Fax?">
			</div>

			<div class="ui-field-contain">
				<label for="email">Email:</label>
				<input type="text" name="email" id="email" value="" placeholder="What's Your Email?">
			</div>

			<div class="ui-field-contain">
				<fieldset data-role="controlgroup">
					<legend>Jobs by email:</legend>
					<input type="checkbox" name="job_by_email" id="job_by_email" value="">
				</fieldset>
			</div>

			<div class="ui-field-contain">
				<label for="employment_status">Employment Status:</label>
				<?php
				$employment_status = DAO::getResultset($link, "select id, status_description, null from lookup_candidate_employment_status order by id;");
				array_unshift($employment_status ,array('0','Please select one',''));
				echo HTMLMobile::select('employment_status', $employment_status, '', false, true);
				?>
			</div>

			<div class="ui-field-contain">
				<label for="hours_per_week">If employed, how many hours per week::</label>
				<input type="text" name="hours_per_week" id="hours_per_week" value="">
			</div>

			<div class="ui-field-contain">
				<label for="last_time_worked">Employment Status:</label>
				<?php
				$last_time_worked = array(array('0','Please select one'),array('6','Less than 6 months'),array('11','6-11 months'),array('23','12-23 months'),array('35','24-35 months'),array('36','Over 36 months'));
				array_unshift($last_time_worked ,array('0','Please select one',''));
				echo HTMLMobile::select('last_time_worked', $last_time_worked, '', false, true);
				?>
			</div>

			<div class="ui-field-contain">
				<fieldset data-role="controlgroup">
					<legend>Disability:</legend>
					<?php
					$disabilities = DAO::getResultset($link, "SELECT LLDDCode AS Disability_Code, IF(LOCATE('(', LLDDCode_Desc) > 0, CONCAT(LLDDCode,' ',LEFT(LLDDCode_Desc,LOCATE('(', LLDDCode_Desc)-2)), CONCAT(LLDDCode,' ',LLDDCode_Desc)) AS Disability_Desc, IF(LOCATE('(', LLDDCode_Desc) > 0, SUBSTRING(LLDDCode_Desc,LOCATE('(', LLDDCode_Desc)), '') AS Disability_Additional FROM lis201314.ilr_llddcode WHERE LLDDType = 'DS' ORDER BY LLDDCode LIMIT 0,10", DAO::FETCH_ASSOC);
					foreach($disabilities AS $disability)
					{
						echo '<input type="checkbox" name="disability[]" id="disability_' . $disability['Disability_Code'] . '" value="' . $disability['Disability_Code'] . '">';
						echo '<label for="disability_' . $disability['Disability_Code'] . '">' . $disability['Disability_Desc'] . '</label>';
					}
					?>
				</fieldset>


			</div>

			<div class="ui-field-contain">
				<fieldset data-role="controlgroup">
					<legend>Learning Difficulty:</legend>
					<?php
					$difficulties = DAO::getResultset($link, "SELECT LLDDCode AS Difficulty_Code, CONCAT(LLDDCode,' ',LLDDCode_Desc) AS Difficulty_Desc,NULL FROM lis201314.ilr_llddcode WHERE LLDDType = 'LD' ORDER BY LLDDCode LIMIT 0,8;", DAO::FETCH_ASSOC);
					foreach($difficulties AS $difficulty)
					{
						echo '<input type="checkbox" name="difficulty[]" id="difficulty_' . $difficulty['Difficulty_Code'] . '" value="' . $difficulty['Difficulty_Code'] . '">';
						echo '<label for="difficulty_' . $difficulty['Difficulty_Code'] . '">' . $difficulty['Difficulty_Desc'] . '</label>';
					}
					?>
				</fieldset>


			</div>

			<div class="ui-field-contain">
				<label for="last_education">Highest education completed:</label>
				<?php
				$last_education = DAO::getResultset($link, "SELECT id, description, null FROM lookup_candidate_qualification order by id;");
				array_unshift($last_education ,array('0','Please select one',''));
				echo HTMLMobile::select('last_education', $last_education, '', false, true);
				?>
			</div>

			<div data-role="main" class="ui-content">
				<table border="1" data-role="table" class="ui-responsive" id="tbl_qualification">
					<thead>
					<tr>
						<th>GCSE/A/AS Level</th>
						<th>Subject</th>
						<th>Grade</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>GCSE</td>
						<td>English</td>
						<td>
							<?php
							$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'),array('NA','N/A'));
							echo HTMLMobile::select('grade_english', $qual_grades, '', false, true);
							?>
						</td>
					</tr>
					<tr>
						<td>GCSE</td>
						<td>Maths</td>
						<td>
							<?php
							$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'),array('NA','N/A'));
							echo HTMLMobile::select('grade_maths', $qual_grades, '', false, true);
							?>
						</td>
					</tr>
				</tbody>
				</table>
			</div>

			<h3 id="notification"></h3>
			<button id="submit" type="submit">Submit</button>
		</form>
	</div>
</div>


</body>
</html>