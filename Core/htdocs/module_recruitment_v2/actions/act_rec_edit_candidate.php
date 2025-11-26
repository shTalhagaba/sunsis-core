<?php
class rec_edit_candidate implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		if($subaction == 'checkForDuplicates')
		{
			echo $this->checkForDuplicates($link);
			exit;
		}

		if(isset($_REQUEST['reset']) && $_REQUEST['reset'] == '1')
			$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=rec_edit_candidate&id=" . $id, "Add/Edit Candidate");

		if($id == '')
		{
			$candidate = new RecCandidate();
			$page_title = "New Candidate";
			$js_cancel = "window.location.replace('" . $_SESSION['bc']->getPrevious() . "');";
		}
		else
		{
			$candidate = RecCandidate::loadFromDatabase($link, $id);
			if (is_null($candidate))
			{
				throw new Exception("No candidate with id '$id'");
			}
		}
		$gender_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_gender ORDER BY description;");
		$ethnicity_ddl = DAO::getResultset($link,"SELECT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), NULL FROM lis201415.ilr_ethnicity ORDER BY Ethnicity;");
		$yes_no_ddl = array(
			array('1', 'Yes', ''),
			array('2', 'No', ''));

		$LLDDHealthProb_dropdown = DAO::getResultset($link,"SELECT DISTINCT LLDDInd, CONCAT(LLDDInd, ' ', LLDDInd_Desc), NULL FROM lis201213.ilr_llddind ORDER BY LLDDInd;", DAO::FETCH_NUM);
		$LLDDCat_dropdown = DAO::getResultset($link, "SELECT code, CONCAT(code, ' ', description), NULL FROM central.lookup_lldd_cat WHERE CODE NOT IN (1,2,3,98,99) ORDER BY code");

		$shift_pattern = $candidate->getShiftPattern($link);
		if($shift_pattern == '')
		{
			$shift_pattern = new stdClass();
			$shift_pattern->mon_start_time = '';
			$shift_pattern->tue_start_time = '';
			$shift_pattern->wed_start_time = '';
			$shift_pattern->thu_start_time = '';
			$shift_pattern->fri_start_time = '';
			$shift_pattern->sat_start_time = '';
			$shift_pattern->sun_start_time = '';
			$shift_pattern->mon_end_time = '';
			$shift_pattern->tue_end_time = '';
			$shift_pattern->wed_end_time = '';
			$shift_pattern->thu_end_time = '';
			$shift_pattern->fri_end_time = '';
			$shift_pattern->sat_end_time = '';
			$shift_pattern->sun_end_time = '';
		}

		require_once('tpl_rec_edit_candidate.php');

	}

	private function checkForDuplicates(PDO $link)
	{
		$firstnames = isset($_REQUEST['firstnames'])?$link->quote($_REQUEST['firstnames']):'';
		$surname = isset($_REQUEST['surname'])?$link->quote($_REQUEST['surname']):'';
		$dob = isset($_REQUEST['dob'])?$link->quote(Date::toMySQL($_REQUEST['dob'])):'';

		$sql = "SELECT COUNT(*) FROM candidate WHERE firstnames = {$firstnames} AND surname = {$surname} AND dob = {$dob} ";

		$count = (int)DAO::getSingleValue($link, $sql);

		return $count;
	}

	private function render_qualifications_tab(PDO $link, $candidate_id = '')
	{
		$Grade_dropdown = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
		//$qual_levels = DAO::getResultset($link, "SELECT DISTINCT PriorAttain, CONCAT(PriorAttain, ' - ', PriorAttainDesc), null FROM lis201415.ilr_priorattain WHERE PriorAttain NOT IN (4,5) ORDER BY PriorAttain;");
		$qual_levels = DAO::getResultset($link, "SELECT DISTINCT id, description, null FROM lookup_candidate_qualification ORDER BY id;");
		if($candidate_id == '')
		{
			echo '<table>';
			echo '<tr>';
			echo '<td width="250" class="">Highest education completed:</td>';
			echo '<td>';
			$last_education = DAO::getResultset($link, "SELECT DISTINCT PriorAttain, CONCAT(PriorAttain, ' - ', PriorAttainDesc), null FROM lis201415.ilr_priorattain WHERE PriorAttain NOT IN (4,5) ORDER BY PriorAttain;");
			array_unshift($last_education ,array('0','Please select one',''));
			echo HTML::select('last_education', $last_education, '', false, false);
			echo '</td>';
			echo '</tr>';
			echo '</table><p></p>';
			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="2">';
			echo '<thead><th>&nbsp;</th><th>Level</th><th>Subject</th><th>Grade</th><th>Date Completed</th><th>School/Institution</th></thead>';
			echo '<tbody>';
			echo '<tr>';
			echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
			echo '<td>GCSE</td>';
			echo '<td>English</td>';
			echo '<td>';
			echo HTML::select('gcse_english_grade', $Grade_dropdown, '', true, true) . ' *';
			echo '</td>';
			echo '<td>' . HTML::datebox('gcse_english_date_completed', '') . '</td>';
			echo '<td><input type="text" name="gcse_english_school_name" id="gcse_english_school_name" value="" /></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<tr>';
			echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
			echo '<td>GCSE</td>';
			echo '<td>Maths</td>';
			echo '<td>';
			echo HTML::select('gcse_maths_grade', $Grade_dropdown, '', true, true) . ' *';
			echo '</td>';
			echo '<td>' . HTML::datebox('gcse_maths_date_completed', '') . '</td>';
			echo '<td><input type="text" name="gcse_maths_school_name" id="gcse_maths_school_name" value="" /></td>';
			echo '</tr>';
			for($i = 1; $i <= 5; $i++)
			{
				echo '<tr>';
				echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
				echo '<td>' . HTML::select('level_'.$i, $qual_levels, '',true) . '</td>';
				echo '<td><input type="text" name="subject_'.$i.'" id="subject_'.$i.'" /></td>';
				echo '<td>' . HTML::select('grade_'.$i, $Grade_dropdown, '', true, false) . '</td>';
				echo '<td>' . HTML::datebox('date_completed_'.$i, '') . '</td>';
				echo '<td><input type="text" name="school_name_'.$i.'" id="school_name_'.$i.'" value="" /></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
		else
		{
			$gcse_english_details = DAO::getResultset($link, "SELECT * FROM candidate_qualification WHERE candidate_id = " . $candidate_id . " AND qualification_level = 'GCSE' AND qualification_subject = 'English Language' LIMIT 1", DAO::FETCH_ASSOC);
			$gcse_maths_details = DAO::getResultset($link, "SELECT * FROM candidate_qualification WHERE candidate_id = " . $candidate_id . " AND qualification_level = 'GCSE' AND qualification_subject = 'Maths' LIMIT 1", DAO::FETCH_ASSOC);

			if(empty($gcse_english_details))
			{
				$gcse_english_details[0]['qualification_grade'] = '';
				$gcse_english_details[0]['qualification_date'] = '';
				$gcse_english_details[0]['institution'] = '';
			}
			if(empty($gcse_maths_details))
			{
				$gcse_maths_details[0]['qualification_grade'] = '';
				$gcse_maths_details[0]['qualification_date'] = '';
				$gcse_maths_details[0]['institution'] = '';
			}
			/*echo '<table>';
			echo '<tr>';
			echo '<td width="250" class="">Highest education completed:</td>';
			echo '<td>';
			$last_education = DAO::getResultset($link, "SELECT DISTINCT PriorAttain, CONCAT(PriorAttain, ' - ', PriorAttainDesc), null FROM lis201415.ilr_priorattain WHERE PriorAttain NOT IN (4,5) ORDER BY PriorAttain;");
			array_unshift($last_education ,array('0','Please select one',''));
			echo HTML::select('last_education', $last_education, DAO::getSingleValue($link, "SELECT last_education FROM candidate WHERE id = " . $candidate_id), false, false);
			echo '</td>';
			echo '</tr>';
			echo '</table><p></p>';*/
			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><th>&nbsp;</th><th>Level</th><th>Subject</th><th>Grade</th><th>Date Completed</th><th>School/Institution</th></thead>';
			echo '<tbody>';
			echo '<tr>';
			echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
			echo '<td>GCSE</td>';
			echo '<td>English Language</td>';
			echo '<td>';
			echo HTML::select('gcse_english_grade', $Grade_dropdown, $gcse_english_details[0]['qualification_grade'], true, false) . ' *';
			echo '</td>';
			echo '<td>' . HTML::datebox('gcse_english_date_completed', $gcse_english_details[0]['qualification_date']) . '</td>';
			echo '<td><input type="text" name="gcse_english_school_name" id="gcse_english_school_name" value="' . $gcse_english_details[0]['institution'] . '" /></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<tr>';
			echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
			echo '<td>GCSE</td>';
			echo '<td>Maths</td>';
			echo '<td>';
			echo HTML::select('gcse_maths_grade', $Grade_dropdown, $gcse_maths_details[0]['qualification_grade'], true, false) . ' *';
			echo '</td>';
			echo '<td>' . HTML::datebox('gcse_maths_date_completed', $gcse_maths_details[0]['qualification_date']) . '</td>';
			echo '<td><input type="text" name="gcse_maths_school_name" id="gcse_maths_school_name" value="' . $gcse_maths_details[0]['institution'] . '" /></td>';
			echo '</tr>';
			$candidate_qualifications = DAO::getResultset($link, "SELECT * FROM candidate_qualification WHERE candidate_id = " . $candidate_id . " AND qualification_level != 'GCSE' ORDER BY id", DAO::FETCH_ASSOC);
			//$qual_levels = DAO::getResultset($link, "SELECT DISTINCT PriorAttain, CONCAT(PriorAttain, ' - ', PriorAttainDesc), null FROM lis201415.ilr_priorattain WHERE PriorAttain NOT IN (4,5) ORDER BY PriorAttain;");
			$qual_levels = DAO::getResultset($link, "SELECT DISTINCT id, description, null FROM lookup_candidate_qualification ORDER BY id;");
			$i = 0;
			foreach($candidate_qualifications AS $qual)
			{
				$i++;
				if($i > 5)
					break;
				echo '<tr>';
				echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
				echo '<td>' . HTML::select('level_'.$i, $qual_levels, $qual['qualification_level'],true) . '</td>';
				echo '<td><input type="text" name="subject_'.$i.'" id="subject_'.$i.'" value="'.$qual['qualification_subject'].'" /></td>';
				echo '<td>' . HTML::select('grade_'.$i, $Grade_dropdown, $qual['qualification_grade'], true, false) . '</td>';
				echo '<td>' . HTML::datebox('date_completed_'.$i, $qual['qualification_date']) . '</td>';
				echo '<td><input type="text" name="school_name_'.$i.'" id="school_name_'.$i.'" value="' . $qual['institution'] . '" /></td>';
				echo '</tr>';
			}
			$i++;
			if($i < 5)
			{
				do
				{
					echo '<tr>';
					echo '<td><img src="/images/achieved.jpg" border="0" /></td>';
					echo '<td>' . HTML::select('level_'.$i, $qual_levels, '',true) . '</td>';
					echo '<td><input type="text" name="subject_'.$i.'" id="subject_'.$i.'" /></td>';
					echo '<td>' . HTML::select('grade_'.$i, $Grade_dropdown, '', true, false) . '</td>';
					echo '<td>' . HTML::datebox('date_completed_'.$i, '') . '</td>';
					echo '<td><input type="text" name="school_name_'.$i.'" id="school_name_'.$i.'" value="" /></td>';
					echo '</tr>';
					$i++;
				}
				while($i <= 5);
			}
			echo '</tbody>';
			echo '</table>';
		}
	}

	private function render_employments_tab(PDO $link, $candidate_id = '')
	{
		if($candidate_id == '')
		{
			echo '<table id="tbl_employment" class="resultset"  cellpadding="6" cellspacing="0">';
			echo '<thead><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></thead>';
			echo '<tbody>';
			for($i = 1; $i <= 5; $i++)
			{
				echo '<tr>';
				echo '<td valign="top"><input type="text" name="company_name_' . $i . '" id="company_name_' . $i . '" /></td>';
				echo '<td valign="top"><input type="text" name="job_title_' . $i . '" id="job_title_{$i}" /></td>';
				echo '<td valign="top">' . HTML::datebox('start_date_' . $i, '', false) . '</td>';
				echo '<td valign="top">' . HTML::datebox('end_date_' . $i, '', false) . '</td>';
				echo '<td valign="top"><textarea name="skills_' . $i . '" id="skills_' . $i . '"></textarea></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
		else
		{
			$candidate_employments = DAO::getResultset($link, "SELECT * FROM candidate_history WHERE candidate_id = " . $candidate_id, DAO::FETCH_ASSOC);
			echo '<table id="tbl_employment" class="resultset" cellpadding="6" cellspacing="0">';
			echo '<thead><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></thead>';
			echo '<tbody>';
			$i = 0;
			foreach($candidate_employments AS $emp)
			{
				$i++;
				if($i > 5)
					break;
				echo '<tr>';
				echo '<td valign="top"><input type="text" name="company_name_' . $i . '" id="company_name_' . $i . '" value = "'.$emp['company_name'].'" /></td>';
				echo '<td valign="top"><input type="text" name="job_title_' . $i . '" id="job_title_'.$i.'" value = "'.$emp['job_title'].'" /></td>';
				echo '<td valign="top">' . HTML::datebox('start_date_' . $i, $emp['start_date'], false) . '</td>';
				echo '<td valign="top">' . HTML::datebox('end_date_' . $i, $emp['end_date'], false) . '</td>';
				echo '<td valign="top"><textarea name="skills_' . $i . '" id="skills_' . $i . '">' . $emp['skills'] . '</textarea></td>';
				echo '</tr>';
			}
			$i++;
			if($i < 5)
			{
				do
				{
					echo '<tr>';
					echo '<td valign="top"><input type="text" name="company_name_' . $i . '" id="company_name_' . $i . '" /></td>';
					echo '<td valign="top"><input type="text" name="job_title_' . $i . '" id="job_title_{$i}" /></td>';
					echo '<td valign="top">' . HTML::datebox('start_date_' . $i, '', false) . '</td>';
					echo '<td valign="top">' . HTML::datebox('end_date_' . $i, '', false) . '</td>';
					echo '<td valign="top"><textarea name="skills_' . $i . '" id="skills_' . $i . '"></textarea></td>';
					echo '</tr>';
					$i++;
				}
				while($i <= 5);
			}
			echo '</tbody>';
			echo '</table>';

		}
	}
}
?>
