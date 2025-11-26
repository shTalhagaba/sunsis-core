<?php /* @var $candidate RecCandidate */ ?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<title>Candidate</title>
</head>

<body>
<div class="banner">
	<div class="Title"><?php echo $candidate->firstnames . ' ' . $candidate->surname; ?></div>
	<div class="ButtonBar">
		<button onclick="if(window.name == 'viewUser'){window.close();} window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">Close</button>
	</div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<?php
$cv_file_link = 'No CV uploaded';
if (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".doc")) {
	$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_' . $candidate->id . '.doc" target="_blank">Applicants CV 1</a> (doc)';
} elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".docx")) {
	$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_1_' . $candidate->id . '.docx" target="_blank">Applicants CV 1</a> (docx)';
}
elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".pdf")) {
	$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_' . $candidate->id . '.pdf" target="_blank">Applicants CV 1</a> (pdf)';
}
?>

<table style="width: 80%;" cellpadding="6">
	<tr style="background-color: #eee;"><td colspan="4" align="center"><strong>Candidate Information</strong></td></tr>
	<tr>
		<td class="fieldLabel">Name:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->firstnames . ' ' . $candidate->surname); ?></td>
		<!--<td class="fieldLabel">Gender:</td>
		<td class="fieldValue"><?php /*echo htmlspecialchars((string)$candidate->getGenderDesc()); */?></td>-->
		<td class="fieldLabel">Mobile:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->mobile);?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Date of Birth:</td>
		<td class="fieldValue"><?php echo Date::toShort($candidate->dob); echo ' (' . Date::dateDiff(date("Y-m-d"), $candidate->dob) . ')'; ?></td>
		<td class="fieldLabel">NI Number:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->national_insurance); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Telephone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->telephone); ?></td>
		<td class="fieldLabel">Email:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->email); ?></td>
	</tr>
	<tr>
		<!--<td class="fieldLabel">Ethnicity:</td>
		<td class="fieldValue"><?php /*echo htmlspecialchars(DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201314.ilr_ethnicity WHERE Ethnicity='{$candidate->ethnicity}';")); */?></td>-->
	</tr>
	<tr>
		<td class="fieldLabel">CV: </td>
		<td class="fieldValue"><?php echo $cv_file_link; ?></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Address: </td>
		<td class="fieldValue">
			<?php
			echo $candidate->address1 . '<br>';
			echo $candidate->address2 . '<br>';
			echo $candidate->borough . '<br>';
			echo $candidate->county . '<br>';
			echo $candidate->postcode . '<br>';
			?>
		</td>
		<td></td>
		<td></td>
	</tr>
	<tr style="background-color: #eee;" align="center"><td colspan="4"><strong>Application Details</strong></td></tr>
	<tr>
		<td colspan="4">
			<table style="width: 100%;" border="0" class="resultset" cellspacing="0" cellpadding="4">
				<thead><tr><th>Vacancy Ref</th><th>Vacancy Title</th><th>Application<br>Status</th><th>Screening<br>RAG</th><th>Telephone Interview<br>Score</th><th>Telephone Interview<br>Comments</th></tr></thead>
				<tbody>
				<?php
				$sql = "SELECT candidate_applications.id FROM candidate_applications INNER JOIN vacancies ON candidate_applications.vacancy_id = vacancies.id WHERE candidate_id = '" . $candidate->id . "' AND vacancies.`employer_id` = '" . $_SESSION['user']->employer_id . "';";
				$applications = DAO::getSingleColumn($link, $sql);
				if ( sizeof($applications) > 0 )
				{
					foreach ( $applications AS $id )
					{
						$objApp = RecCandidateApplication::loadFromDatabaseByID($link, $id);
						echo '<td align="center">'.$objApp->vacancy->vacancy_reference.'</td><td align="center">'.$objApp->vacancy->vacancy_title.'</td><td align="center">'.$objApp->getCandidateApplicationCurrentStatusDesc($link).'</td><td align="center">'.$objApp->screening_rag.'</td>';
						echo '<td align="center"><table cellpadding="6">';
						echo '<tr><td><span style="color:gray">score:</span></td><td>' . $objApp->telephone_interview_score . '</td></tr>';
						echo '<tr><td><span style="color:gray">total marks:</span></td><td>35</td></tr>';
						echo '<tr><td><span style="color:gray">pass marks:</span></td><td>20</td></tr>';
						echo '</td></table>';
						echo '<td align="center">' . DAO::getSingleValue($link, "SELECT comments FROM candidate_application_status WHERE application_id = '" . $objApp->id . "' AND status = '" . RecCandidateApplication::TELEPHONE_INTERVIEWED . "' ORDER BY id DESC LIMIT 1") . '</td>';
						echo '</tr>';
					}
				}
				else
					echo '<tr><td colspan="6">No records entered</td> </tr>';
				?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr style="background-color: #eee;" align="center"><td colspan="4"><strong>Study History</strong></td></tr>
	<!--<tr><td class="fieldLabel">Highest education completed:</td><td class="fieldValue"><?php /*echo DAO::getSingleValue($link, "SELECT DISTINCT CONCAT(PriorAttain, ' - ', PriorAttainDesc) FROM lis201415.ilr_priorattain WHERE PriorAttain = '{$candidate->last_education}'"); */?></td></tr>-->
	<tr>
		<td colspan="4">
			<table style="width: 100%;" border="0" class="resultset" cellspacing="0" cellpadding="4">
				<thead><tr><th>Level</th><th>Subject</th><th>Grade</th><th>Date</th><th>Institution</th></tr></thead>
				<tbody>
				<?php
				if(count($candidate->qualifications) > 0)
				{
					foreach ( $candidate->qualifications AS $edu_pos => $edu_row )
					{
						echo '<tr>';
						if($edu_row['qualification_level'] != 'GCSE')
							echo '<td align="center">'.DAO::getSingleValue($link, "SELECT distinct PriorAttainDesc AS id FROM lis201415.ilr_priorattain WHERE PriorAttain = '" . $edu_row['qualification_level'] . "'").'</td>';
						else
							echo '<td align="center">GCSE</td>';
						echo '<td align="center">'.$edu_row['qualification_subject'].'</td>';
						echo '<td align="center">'.DAO::getSingleValue($link, "SELECT description FROM lookup_gcse_grades WHERE id = '" . $edu_row['qualification_grade'] . "'").'</td>';
						echo '<td align="center">'.Date::to($edu_row['qualification_date'], 'd/m/Y').'</td>';
						echo '<td align="center">'.$edu_row['institution'].'</td>';
						echo '</tr>';
					}
				}
				else
					echo '<tr><td colspan="5">No records entered</td> </tr>';
				?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr style="background-color: #eee;" align="center"><td colspan="4"><strong>Employment History</strong></td></tr>
	<tr>
		<td colspan="4">
			<table style="width: 100%;" border="0" class="resultset" cellspacing="0" cellpadding="4">
				<thead><tr><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></tr></thead>
				<tbody>
				<?php
				if ( sizeof($candidate->employments) > 0 )
				{
					foreach ( $candidate->employments AS $edu_pos => $edu_row )
					{
						echo '<td align="center">'.$edu_row['company_name'].'</td><td align="center">'.$edu_row['job_title'].'</td><td align="center">'.Date::toShort($edu_row['start_date']).'</td><td align="center">'.Date::toShort($edu_row['end_date']).'</td><td align="center">'.nl2br($edu_row['skills']).'</td>';
						echo '</tr>';
					}
				}
				else
					echo '<tr><td colspan="5">No records entered</td> </tr>';
				?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr style="background-color: #eee;" align="center"><td colspan="4"><strong>Availability to work</strong></td></tr>
	<tr>
		<td colspan="4">
			<table style="width: 100%;" border="0" class="resultset" cellspacing="0" cellpadding="4">
				<thead><tr><th align="center">Day</th><th align="center">Monday</th><th align="center">Tuesday</th><th align="center">Wednesday</th><th align="center">Thursday</th><th align="center">Friday</th><th align="center">Saturday</th><th align="center">Sunday</th></tr></thead>
				<tr>
					<th>Start Time</th>
					<td align="center"><?php echo $shift_pattern->mon_start_time; ?></td>
					<td align="center"><?php echo $shift_pattern->tue_start_time; ?></td>
					<td align="center"><?php echo $shift_pattern->wed_start_time; ?></td>
					<td align="center"><?php echo $shift_pattern->thu_start_time; ?></td>
					<td align="center"><?php echo $shift_pattern->fri_start_time; ?></td>
					<td align="center"><?php echo $shift_pattern->sat_start_time; ?></td>
					<td align="center"><?php echo $shift_pattern->sun_start_time; ?></td>
				</tr>
				<tr>
					<th>End Time</th>
					<td align="center"><?php echo $shift_pattern->mon_end_time; ?></td>
					<td align="center"><?php echo $shift_pattern->tue_end_time; ?></td>
					<td align="center"><?php echo $shift_pattern->wed_end_time; ?></td>
					<td align="center"><?php echo $shift_pattern->thu_end_time; ?></td>
					<td align="center"><?php echo $shift_pattern->fri_end_time; ?></td>
					<td align="center"><?php echo $shift_pattern->sat_end_time; ?></td>
					<td align="center"><?php echo $shift_pattern->sun_end_time; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</body>
</html>