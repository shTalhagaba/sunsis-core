<?php
define('METRES_IN_A_MILE', 1609.344);

class rec_view_vacancy  extends ActionController
{
	public function indexAction(PDO $link)
	{
		//pre($_REQUEST);
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'tab2';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'searchMatchingCandidates')
		{
			echo $this->searchMatchingCandidates($link);
			exit;
		}
		if($subaction == 'getCandidateDetail')
		{
			echo $this->getDetailCandidateInformation($link);
			exit;
		}
		if($subaction == 'createApplication')
		{
			echo $this->createApplication($link);
			exit;
		}
		if($subaction == 'graphApplicationsByStatus')
		{
			echo $this->graphApplicationsByStatus($link);
			exit;
		}


		if ( $id != '' )
		{
			$vacancy = RecVacancy::loadFromDatabase($link, $_REQUEST['id']);
			if ( !isset($vacancy) || !is_object($vacancy) )
				throw new Exception('Vacancy not found');

			$vacancy->radius = isset($_REQUEST['radius'])?$_REQUEST['radius']:'5';
			$vacancy->radius_metres = $vacancy->radius * METRES_IN_A_MILE;
		}
		else
		{
			throw new Exception('Missing querystring argument "id"');
		}

		$_SESSION['bc']->add($link, "do.php?_action=rec_view_vacancy&id=".$vacancy->id."&selected_tab=".$selected_tab, "View/Manage Vacancy Applications");

		$vacancy_location = DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),', ', COALESCE(`postcode`,''), ')') AS location FROM locations WHERE id = '$vacancy->location_id'");

		$top_message = '';
		if($vacancy->is_active == 0)
			$top_message = 'THE STATUS OF THIS VACANCY IS NOT ACTIVE';
		$d1 = new Date($vacancy->closing_date);
		$today = new Date(date('Y-m-d'));
		if($d1->before($today))
			$top_message .= 'THE VACANCY IS CLOSED - CLOSING DATE IS PASSED';
		$number_of_sunesis_learnersIn_this_application = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE vacancy_id = '" . $vacancy->id . "' AND current_status = '" . RecCandidateApplication::SUNESIS_LEARNER . "'");
		if($number_of_sunesis_learnersIn_this_application >= (int)$vacancy->no_of_positions)
			$top_message .= 'THE VACANCY IS FULL - TOTAL POSITIONS = ' . $vacancy->no_of_positions . ' AND SUCCESSFUL APPLICATIONS = ' . $number_of_sunesis_learnersIn_this_application;

		require_once('tpl_rec_view_vacancy.php');

	}

	private function graphApplicationsByStatus(PDO $link)
	{
		$vacancy_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  IF(
    current_status = 0,
    'Not Screened',
    IF(
      current_status = 1,
      'Screened',
      IF(
      	current_status = 2,
      	'Telephone Interviewed',
      	IF(
          current_status = 3,
          'CV Sent',
          	IF(
          	  current_status = 4,
              'Interview Successful',
            IF(
              current_status = 5,
              'Interview Unsuccessful',
               IF(
                 current_status = 6,
                 'Sunesis Learner',
                 IF(current_status = 99, 'Closed', '')
               )
            )
          )
        )
      )
    )
  ) AS current_status
FROM candidate_applications
WHERE vacancy_id = '$vacancy_id'
GROUP BY candidate_applications.`current_status`
;

SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$category = array();
		$category['name'] = 'Applications Status';
		$series1 = array();
		$series1['name'] = 'Applications';

		foreach ($result AS $rs) {
			$category['data'][] = $rs['current_status'];
			$series1['data'][] = $rs['cnt'];
		}
		$result = array();
		array_push($result, $category);
		array_push($result, $series1);
		return (json_encode($result, JSON_NUMERIC_CHECK));
	}

	private function searchMatchingCandidates(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($id == '')
			throw new Exception('Missing querstring argument --- vacancy_id');
		$vacancy = RecVacancy::loadFromDatabase($link, $id);
		if(is_null($vacancy))
			throw new Exception('Vacancy with id: ' . $id .' not found');

		$firstname = isset($_REQUEST['frmSearchCandidatesFirstName'])?$_REQUEST['frmSearchCandidatesFirstName']:'';
		$surname = isset($_REQUEST['frmSearchCandidatesSurname'])?$_REQUEST['frmSearchCandidatesSurname']:'';
		$age = isset($_REQUEST['frmSearchCandidatesAge'])?$_REQUEST['frmSearchCandidatesAge']:'';
		$radius = isset($_REQUEST['frmSearchCandidatesRadius'])?$_REQUEST['frmSearchCandidatesRadius']:'';

		if($firstname != '')
		{
			$firstname = " AND candidate.firstnames LIKE '" . addslashes((string)$firstname) . "%'";
		}
		if($surname != '')
		{
			$surname = " AND candidate.surname LIKE '" . addslashes((string)$surname) . "%'";
		}
		if($age != '')
		{
			$age = " AND (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d'))) = " . $age . " ";
		}
		$radius_metres = $radius * 1609.344;
		$sql = <<<HEREDOC
	SELECT
		candidate.id, candidate.gender, candidate.firstnames, candidate.surname, candidate.address1, candidate.address2, candidate.borough, candidate.postcode,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
		candidate.username,
		SQRT(POWER(ABS($vacancy->easting - candidate.easting), 2) + POWER(ABS($vacancy->northing - candidate.northing), 2)) AS distance
	FROM
		candidate
	WHERE
	    candidate.username is NULL AND
		candidate.easting >= ($vacancy->easting - $radius_metres) AND candidate.easting <= ($vacancy->easting + $radius_metres) AND
		candidate.northing >= ($vacancy->northing - $radius_metres) AND candidate.northing <= ($vacancy->northing + $radius_metres) AND
		candidate.id NOT IN ( SELECT candidate_applications.candidate_id FROM candidate_applications WHERE candidate_applications.vacancy_id = $vacancy->id )
		$firstname
		$surname
		$age
	HAVING
		distance <= $radius_metres
	ORDER BY
		distance,
        candidate.surname;
HEREDOC;
		//return $sql;
		$records  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(count($records) == 0)
			return 'No records found based on your search criteria';

		$html = "";
		$html .= '<table id="tbl_candidates" style="" class="resultset" border="0" cellspacing="0" cellpadding="6">';
		$html .= '<caption>Total Records found: ' . count($records) . '</caption>';
		$html .= '<thead><tr>';
		$html .= '<th  class="topRow"></th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Action</th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		foreach($records AS $record)
		{
			$html .= '<tr>';
			if($record['gender'] == 'M')
				$html .= '<td><img src="/images/boy-blonde-hair.gif" border="0" /></td>';
			elseif($record['gender'] == 'F')
				$html .= '<td><img src="/images/girl-black-hair.gif" border="0" /></td>';
			else
				$html .= '<td><img src="/images/blue-person.gif" border="0" /></td>';

			$html .= '<td><a target="_blank" href="do.php?_action=rec_read_candidate&id=' . $record['id'] . '">' . strtoupper($record['surname']) . ', ' . $record['firstnames'] . '</a></td>';
			$html .= '<td align="center">' . $record['age'] . '</td>';
			$html .= '<td>' . $record['address1'] . ' ' . $record['address2']  . ', ' . $record['borough'] . '</td>';
			$html .= '<td align="center">' . $record['postcode'] . '</td>';
			$html .= '<td align="center">'.sprintf("%.2f",($record['distance'] / METRES_IN_A_MILE)).'</td>';
			$html .= '<td align="center"><span class="button" onclick="displayCandidateDetail(\'1_'.$record['id'].'\'); return false;">+/-</span> </td>';
			$html .= '</tr>';

			$html .= '<tr id="tr_detail_' . $record['id'] . '" style="display: none;"><td id="td_detail_' . $record['id'] . '" colspan="7"></td></tr>';
		}
		$html .= '</tbody></table>';
		unset($vacancy);
		return $html;
	}

	private function getDetailCandidateInformation(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		if($candidate_id == '')
			return '';

		$html = '';
		$candidate = RecCandidate::loadFromDatabase($link, $candidate_id);
		$cv_file_link = '';
		if ( file_exists(Repository::getRoot()."/recruitment/cv_1_".$candidate->id.".doc") )
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$candidate->id.'.doc">Candidate CV</a> (doc)';
		if ( file_exists(Repository::getRoot()."/recruitment/cv_1_".$candidate->id.".docx") )
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$candidate->id.'.docx">Candidate CV</a> (docx)';
		if ( file_exists(Repository::getRoot()."/recruitment/cv_1_".$candidate->id.".pdf") )
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$candidate->id.'.pdf">Candidate CV</a> (pdf)';
		$dob = Date::toShort($candidate->dob);
		$ethnicity = DAO::getSingleValue($link, "SELECT description FROM lookup_country_list WHERE code='{$candidate->ethnicity}';");
		$study_history = $this->render_candidate_qualifications($link, $candidate);
		$employment_history = $this->render_candidate_employment($link, $candidate);
		$other_applications = $this->render_other_applications($link, $candidate);
		$html .= <<<HTML

<table style="width: 100%; background-color: #e0ffff;" cellpadding="6">
	<col width="150" />
	<col width="350" />
	<col width="150" />
	<col width="350" />
	<tr style="background-color: #eee;"><td colspan="4"><strong>Candidate Information</strong></td><td colspan="3">$cv_file_link</td></tr>
	<tr>
		<td class="fieldLabel">Date of Birth:</td><td class="fieldValue">$dob</td>
		<td class="fieldLabel">NI Number:</td><td class="fieldValue">$candidate->national_insurance</td>
	</tr>
	<tr>
		<td class="fieldLabel">Telephone:</td><td class="fieldValue">$candidate->telephone</td>
		<td class="fieldLabel">Email:</td><td class="fieldValue">$candidate->email</td>
	</tr>
	<tr>
		<td class="fieldLabel">Mobile:</td><td class="fieldValue">$candidate->mobile</td>
		<td class="fieldLabel">Ethnicity:</td><td class="fieldValue">$ethnicity</td>
	</tr>
	<tr style="background-color: #eee;"><td colspan="4"><strong>Study History</strong></td></tr>
	<tr><td colspan="4">$study_history</td></tr>
	<tr style="background-color: #eee;"><td colspan="4"><strong>Employment History</strong></td></tr>
	<tr><td colspan="4">$employment_history</td></tr>
	<tr style="background-color: #eee;"><td colspan="4"><strong>Other Applications</strong></td></tr>
	<tr><td colspan="4">$other_applications</td></tr>
	<tr style="background-color: #eee;"><td colspan="4"><strong>Enter Comments</strong></td></tr>
	<tr><td colspan="4"><textarea rows="5" cols="100%" id="comments_{$candidate_id}"></textarea></td></tr>
	<tr><td colspan="4" align="right"><span class="button" onclick="createApplication('$candidate_id');">Create Application</span></td></tr>
</table>
HTML;
		if($other_applications != '')
			$html = str_replace('LBL_OTHER_APPLICATIONS', $other_applications, $html);
		unset($candidate);
		return $html;
	}

	private function render_other_applications(PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		$ids = DAO::getSingleColumn($link, "SELECT id FROM candidate_applications WHERE candidate_id = '{$candidate->id}'");

		$return_html .= '<table style="width: 100%;" border="0" class="resultset" cellspacing="0" cellpadding="4">';
		$return_html .= '<thead><th>Vacancy Title</th><th>Vacancy Employer</th><th>Application Status</th></thead>';
		if(count($ids) > 0)
		{
			foreach ($ids AS $id)
			{
				$application = RecCandidateApplication::loadFromDatabaseByID($link, $id);
				$return_html .= '<tr>';
				$return_html .= '<td align="center">'. $application->getVacancyTitle($link) .'</td>';
				$return_html .= '<td align="center">'. $application->getVacancyEmployerName($link) .'</td>';
				$return_html .= '<td align="center">'. $application->getCandidateApplicationCurrentStatusDesc($link) .'</td>';
				$return_html .= '</tr>';
			}
		}
		else
			$return_html .= '<tr><td colspan="3">No other applications found</td> </tr>';
		$return_html .= '</table>';
		return $return_html;
	}

	private function render_candidate_qualifications (PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		$return_html .= '<table style="width: 100%;" border="0" class="resultset" cellspacing="0" cellpadding="4">';
		$return_html .= '<thead><th>Level</th><th>Subject</th><th>Grade</th><th>Date</th><th>Institution</th></thead>';
		if(count($candidate->qualifications) > 0)
		{
			foreach ( $candidate->qualifications AS $edu_pos => $edu_row )
			{
				$return_html .= '<tr>';
				if($edu_row['qualification_level'] != 'GCSE')
					$return_html .= '<td align="center">'.DAO::getSingleValue($link, "SELECT distinct PriorAttainDesc AS id FROM lis201415.ilr_priorattain WHERE PriorAttain = '" . $edu_row['qualification_level'] . "'").'</td>';
				else
					$return_html .= '<td align="center">GCSE</td>';
				$return_html .= '<td align="center">'.$edu_row['qualification_subject'].'</td>';
				$return_html .= '<td align="center">'.DAO::getSingleValue($link, "SELECT description FROM lookup_gcse_grades WHERE id = '" . $edu_row['qualification_grade'] . "'").'</td>';
				$return_html .= '<td align="center">'.Date::to($edu_row['qualification_date'], 'd/m/Y').'</td>';
				$return_html .= '<td align="center">'.$edu_row['institution'].'</td>';
				$return_html .= '</tr>';
			}
		}
		else
			$return_html .= '<tr><td colspan="5">No records entered</td> </tr>';
		$return_html .= '</table>';
		return $return_html;
	}

	private function render_candidate_employment (PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		$return_html .= '<table id="tbl_employment" class="resultset" border="0" cellspacing="0" cellpadding="4" style="width: 100%;">';
		$return_html .= '<thead><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></thead>';
		if ( sizeof($candidate->employments) > 0 )
		{
			foreach ( $candidate->employments AS $edu_pos => $edu_row )
			{
				$return_html .= '<tr>';
				$return_html .= '<td>'.$edu_row['company_name'].'</td><td>'.$edu_row['job_title'].'</td><td>'.Date::toShort($edu_row['start_date']).'</td><td>'.Date::toShort($edu_row['end_date']).'</td><td>'.nl2br($edu_row['skills']).'</td>';
				$return_html .= '</tr>';
			}
		}
		else
			$return_html .= '<tr><td colspan="5">No records entered</td> </tr>';
		$return_html .= '</table>';
		return $return_html;
	}

	private function createApplication(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$vacancy_id = isset($_REQUEST['vacancy_id'])?$_REQUEST['vacancy_id']:'';
		if($candidate_id == '' || $vacancy_id == '')
			throw new Exception('Missing querystring arguments: candidate_id, vacancy_id');

		$application = new RecCandidateApplication();
		$application->candidate_id = $candidate_id;
		$application->vacancy_id = $vacancy_id;
		$application->current_status = RecCandidateApplication::CREATED;
		$application->created_by = $_SESSION['user']->id;
		$application->save($link);

		$objApplicationStatus = new stdClass();
		$objApplicationStatus->application_id = $application->id;
		$objApplicationStatus->status = $application->current_status;
		$objApplicationStatus->comments = $_REQUEST['comments'];
		$objApplicationStatus->created_by = $application->created_by;
		DAO::saveObjectToTable($link, 'candidate_application_status', $objApplicationStatus);

		$objCandidate = RecCandidate::loadFromDatabase($link, $candidate_id);
		$objVacancy = RecVacancy::loadFromDatabase($link, $vacancy_id);
		if(!is_null($objCandidate) && !is_null($objVacancy))
		{
			$objCandidate->saveCandidateNotes($link, 'Candidate application is created. [Vacancy Ref: "' . $objVacancy->vacancy_reference . '", Vacancy Title: "' . $objVacancy->vacancy_title . '"]');
		}
		unset($application);
		unset($objApplicationStatus);
		unset($objCandidate);
	}
}
?>
