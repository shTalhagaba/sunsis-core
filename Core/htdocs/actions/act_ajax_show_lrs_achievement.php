<?php
class ajax_show_lrs_achievement implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
		if($username == '')
		{
			echo 'Missing querystring argument: username';
			exit;
		}

		$return_html = '';
		$return_html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="10">';
		$return_html .= '<thead><tr>';
		$return_html .= '<th>ULN</th><th>Achievement Award Date</th><th>Achievement Provider Name</th><th>Achievement Provider UKPRN</th>';
		$return_html .= '<th>Awarding Organisation Name</th><th>Credits</th><th>Date Loaded</th><th>Grade</th>';
		$return_html .= '<th>Language for Assessment</th><th>Level</th><th>Qualification Type</th><th>Source</th>';
		$return_html .= '<th>Status</th><th>Subject</th><th>Subject Code</th>';
		$return_html .= '</tr></thead><tbody>';
		$records = DAO::getResultset($link, "SELECT * FROM lrs_learner_learning_events INNER JOIN candidate ON lrs_learner_learning_events.candidate_id = candidate.id WHERE candidate.username = '{$username}' ", DAO::FETCH_ASSOC);
		if(count($records) == 0)
		{
			$return_html .= '<tr><td colspan="14">No records found.</td></tr>';
		}
		else
		{
			foreach($records AS $row)
			{
				$return_html .= '<tr>';
				$return_html .= '<td>' . $row['l45'] . '</td>';
				$return_html .= '<td>' . Date::toShort($row['AchievementAwardDate']) . '</td>';
				$return_html .= '<td>' . $row['AchievementProviderName'] . '</td>';
				$return_html .= '<td>' . $row['AchievementProviderUkprn'] . '</td>';
				$return_html .= '<td>' . $row['AwardingOrganisationName'] . '</td>';
				$return_html .= '<td>' . $row['Credits'] . '</td>';
				$return_html .= '<td>' . Date::toShort($row['DateLoaded']) . '</td>';
				$return_html .= '<td>' . $row['Grade'] . '</td>';
				$return_html .= '<td>' . $row['LanguageForAssessment'] . '</td>';
				$return_html .= '<td>' . $row['Level'] . '</td>';
				$return_html .= '<td>' . $row['QualificationType'] . '</td>';
				$return_html .= '<td>' . $row['Source'] . '</td>';
				$return_html .= '<td>' . $row['Status'] . '</td>';
				$return_html .= '<td>' . $row['Subject'] . '</td>';
				$return_html .= '<td>' . $row['SubjectCode'] . '</td>';
				$return_html .= '</tr>';
			}
		}
		$return_html .= '</tbody></table>';

		echo $return_html;
		exit;
	}
}