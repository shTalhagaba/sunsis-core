<?php
class rec_view_applications implements IAction
{
	public function execute(PDO $link)
	{
		$view = VoltView::getViewFromSession('primaryView', 'rec_view_applications'); /* @var $view View */
		if(is_null($view))
		{
			// Create new view object
			$view = $_SESSION['primaryView'] = $this->buildView($link);
		}

		$view->refresh($_REQUEST, $link);

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=rec_view_applications" , "View Applications");

		require_once('tpl_rec_view_applications.php');
	}

	private function buildView(PDO $link)
	{

		if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo")
		{
			$sql = <<<HEREDOC
SELECT
	vacancies.`code`,
	vacancies.`job_title`,
	DATE_FORMAT(vacancies.`live_date`, '%d/%m/%Y') AS live_date,
	DATE_FORMAT(vacancies.`expiry_date`, '%d/%m/%Y') AS expiry_date,
	CONCAT(tr.`firstnames`, ' ', tr.`surname`) AS candidate_name,
	TIMESTAMPDIFF(YEAR,tr.dob,CURDATE()) AS age_in_years,
	CONCAT(tr.`home_address_line_1`, ', ', tr.`home_address_line_2`, ' ', tr.`home_address_line_3`, ' ', tr.`home_address_line_4`) AS candidate_address,
	UCASE(tr.`home_postcode`) AS candidate_postcode,
	candidate_applications.`application_status`,
	candidate_applications.`application_screening`,
	candidate_applications.`interview_outcome`,
	candidate_applications.id AS application_id,
	candidate_applications.candidate_id,
	candidate_applications.vacancy_id
FROM
	candidate_applications
	INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id`
	INNER JOIN tr ON candidate_applications.`candidate_id` = tr.`id`
;
HEREDOC;
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	vacancies.`code`,
	vacancies.`job_title`,
	DATE_FORMAT(vacancies.`live_date`, '%d/%m/%Y') AS live_date,
	DATE_FORMAT(vacancies.`expiry_date`, '%d/%m/%Y') AS expiry_date,
	CONCAT(candidate.`firstnames`, ' ', candidate.`surname`) AS candidate_name,
	TIMESTAMPDIFF(YEAR,candidate.dob,CURDATE()) AS age_in_years,
	CONCAT(candidate.`address1`, ', ', candidate.`address2`, ' ', candidate.`borough`, ' ', candidate.`county`) AS candidate_address,
	UCASE(candidate.`postcode`) AS candidate_postcode,
	candidate_applications.`application_status`,
	candidate_applications.`application_screening`,
	candidate_applications.`interview_outcome`,
	candidate_applications.id AS application_id,
	candidate_applications.candidate_id,
	candidate_applications.vacancy_id
FROM
	candidate_applications
	INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id`
	INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.`id`
;
HEREDOC;
		}

		$view = new VoltView('rec_view_applications', $sql);

		// Date filters
		$format = "WHERE vacancies.live_date >= '%s'";
		$f = new VoltDateViewFilter('filter_from_live_date', $format, '');
		$f->setDescriptionFormat("From Live Date: %s");
		$view->addFilter($f);

		$format = "WHERE vacancies.live_date <= '%s'";
		$f = new VoltDateViewFilter('filter_to_live_date', $format, '');
		$f->setDescriptionFormat("To Live Date: %s");
		$view->addFilter($f);

		$format = "WHERE vacancies.expiry_date >= '%s'";
		$f = new VoltDateViewFilter('filter_from_expiry_date', $format, '');
		$f->setDescriptionFormat("From Expiry Date: %s");
		$view->addFilter($f);

		$format = "WHERE vacancies.expiry_date <= '%s'";
		$f = new VoltDateViewFilter('filter_to_expiry_date', $format, '');
		$f->setDescriptionFormat("To Expiry Date: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(1, 'Show All', null, null),
			1=>array(2, 'Active', null, 'WHERE vacancies.status = 1'),
			2=>array(3, 'Inactive', null, 'WHERE vacancies.status = 0')
		);
		$f = new VoltDropDownViewFilter('filter_status', $options, 2, false);
		$f->setDescriptionFormat("Vacancy Status: %s");
		$view->addFilter($f);

		// Job Title Filter
		$options = DAO::getResultset($link, "SELECT id, job_title, null, CONCAT('WHERE vacancies.id=',id) FROM vacancies ORDER BY job_title");
		$f = new VoltDropDownViewFilter('filter_job_id', $options, null);
		$f->setDescriptionFormat("Vacancy: %s");
		$view->addFilter($f);

		// Job Title Filter
		$f = new VoltTextboxViewFilter('filter_job_title', "WHERE LOWER(vacancies.job_title) LIKE LOWER('%%%s%%')", null);
		$f->setDescriptionFormat("Job Title: %s");
		$view->addFilter($f);

		// Job Title Filter
		$f = new VoltTextboxViewFilter('filter_code', "WHERE LOWER(vacancies.code) LIKE LOWER('%%%s%%')", null);
		$f->setDescriptionFormat("Code: %s");
		$view->addFilter($f);

		// Primary Sector Type Filter
		$options = "SELECT DISTINCT id, description, NULL, CONCAT('WHERE vacancies.primary_sector = ',CHAR(39),id,CHAR(39)) FROM lookup_sector_types";
		$f = new VoltDropDownViewFilter('filter_primary_sector', $options, null, true);
		$f->setDescriptionFormat("Primary Sector: %s");
		$view->addFilter($f);

		// been screened ( more than two comments  two are added during registration )
		$options = array(
			0=>array(1, 'Requires Screening', null, 'WHERE candidate_applications.application_status IS NULL'),
			1=>array(2, 'Has Been Screened', null, 'WHERE candidate_applications.application_status IS NOT NULL')
		);
		$f = new VoltDropDownViewFilter('filter_screened', $options, null, true);
		$f->setDescriptionFormat("Candidate Screened: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(1, 'Is a green candidate', null, 'WHERE candidate_applications.application_screening = "G"'),
			1=>array(2, 'Is an amber candidate', null, 'WHERE candidate_applications.application_screening = "A"'),
			2=>array(3, 'Is a red candidate', null, 'WHERE candidate_applications.application_screening = "R"'),
		);
		$f = new VoltDropDownViewFilter('filter_screening', $options, null, true);
		$f->setDescriptionFormat("Applications Screening: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(1, 'Not Screened', null, 'WHERE candidate_applications.application_status IS NULL'),
			1=>array(2, 'Screened', null, 'WHERE candidate_applications.application_status = 1'),
			2=>array(3, 'Approved', null, 'WHERE candidate_applications.application_status = 2 AND candidate_applications.interview_outcome IS NULL'),
			3=>array(4, 'Approved And Successful Interview', null, 'WHERE candidate_applications.application_status = 2 AND candidate_applications.interview_outcome = 1'),
			4=>array(5, 'Approved And Unsuccessful Interview', null, 'WHERE candidate_applications.application_status = 2 AND candidate_applications.interview_outcome = 0'),
			5=>array(6, 'Removed from Vacancy', null, 'WHERE candidate_applications.application_status = 99'),
		);
		$f = new VoltDropDownViewFilter('filter_application_status', $options);
		$f->setDescriptionFormat("Applications Status: %s");
		$view->addFilter($f);

		// Candidate Name Filter
		$f = new VoltTextboxViewFilter('filter_firstnames', "WHERE LOWER(firstnames) LIKE LOWER('%%%s%%')", null);
		$f->setDescriptionFormat("Firstname contains: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_surname', "WHERE LOWER(surname) LIKE LOWER('%%%s%%')", null);
		$f->setDescriptionFormat("Surname contains: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$is_learner = 0;
		if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo")
			$is_learner = 1;

		$sql = $view->getSQLStatement()->__toString();

		$st = $link->query($sql);
		if(!$st)
		{
			throw new DatabaseException($link, $sql);
		}

		echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead>';
		echo '<tr><th class="topRow">&nbsp;</th><th class="topRow" colspan="4">Vacancy Information</th><th  class="topRow" colspan="4">Candidate Information</th><th class="topRow" colspan="4">Application Information</th></tr>';
		echo '<tr>';
		echo '<th class="bottomRow">&nbsp;</th><th class="bottomRow">Code</th><th class="bottomRow">Title</th><th class="bottomRow">Live Date</th><th class="bottomRow">Expiry Date</th>';
		echo '<th class="bottomRow">Name</th><th class="bottomRow">Age</th><th class="bottomRow">Address</th><th class="bottomRow">Postcode</th>';
		echo '<th class="bottomRow">Status</th><th class="bottomRow">Screening</th><th class="bottomRow">Interview<br>Outcome</th><th class="bottomRow">Actions</th>';
		echo '</tr></thead>';
		echo '<tbody>';
		$bgcolor = "white";
		while($row = $st->fetch())
		{
			if($row['application_screening'] == 'R')
				$bgcolor = "#FFBFBF";
			elseif($row['application_screening'] == 'A')
				$bgcolor = "#FFE6D7";
			elseif($row['application_screening'] == 'G')
				$bgcolor = '#E0EAD0';

			$bgcolor = 'white';
			echo '<tr bgcolor="' . $bgcolor . '">';
			echo "<td align='center' style='background-image: url('/images/application_icon.jpg');border-right-style: solid;'><a title='Redirect to Application Page' href='do.php?_action=rec_edit_application&id=".$row['application_id']."&candidate_id=".$row['candidate_id']."&vacancy_id=".$row['vacancy_id']."&is_learner=".$is_learner."'><img src=\"/images/application_icon.jpg\" border=\"0\" alt=\"\" /></a></td>";
			echo '<td align="center">' . ((isset($row['code']))?(($row['code']=='')?'&nbsp':'<a href="do.php?_action=rec_read_vacancy&id='.$row['vacancy_id'].'">'.$row['code']):'&nbsp') . '</a></td>';
			echo '<td align="center">' . ((isset($row['job_title']))?(($row['job_title']=='')?'&nbsp':$row['job_title']):'&nbsp') . '</td>';
			echo '<td align="center">' . ((isset($row['live_date']))?(($row['live_date']=='')?'&nbsp':$row['live_date']):'&nbsp') . '</td>';
			echo '<td align="center">' . ((isset($row['expiry_date']))?(($row['expiry_date']=='')?'&nbsp':$row['expiry_date']):'&nbsp') . '</td>';
			if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo")
				echo '<td align="center">' . ((isset($row['candidate_name']))?(($row['candidate_name']=='')?'&nbsp':'<a href="do.php?_action=read_training_record&id='.$row['candidate_id'].'">'.$row['candidate_name']):'&nbsp') . '</a></td>';
			else
				echo '<td align="center">' . ((isset($row['candidate_name']))?(($row['candidate_name']=='')?'&nbsp':'<a href="do.php?_action=rec_read_candidate&id='.$row['candidate_id'].'">'.$row['candidate_name']):'&nbsp') . '</a></td>';
			echo '<td align="center">' . ((isset($row['age_in_years']))?(($row['age_in_years']=='')?'&nbsp':$row['age_in_years']):'&nbsp') . '</td>';
			echo '<td align="center">' . ((isset($row['candidate_address']))?(($row['candidate_address']=='')?'&nbsp':$row['candidate_address']):'&nbsp') . '</td>';
			echo '<td align="center">' . ((isset($row['candidate_postcode']))?(($row['candidate_postcode']=='')?'&nbsp':$row['candidate_postcode']):'&nbsp') . '</td>';
			$application_status = "";
			switch($row['application_status'])
			{
				case 1:
					$application_status =  'SCREENED';
					break;
				case 99:
					$application_status =  'REMOVED FROM VACANCY';
					break;
				case 3:
					$application_status =  'CONVERTED TO LEARNER';
					break;
				case 2:
					if(!is_null($row['interview_outcome']) && $row['interview_outcome'] == '1' )
						$application_status =  'SUCCESSFUL INTERVIEW';
					elseif(!is_null($row['interview_outcome']) && $row['interview_outcome'] == '0' )
						$application_status =  'UNSUCCESSFUL INTERVIEW';
					else
						$application_status =  'APPROVED';
					break;
				default:
					$application_status =  'AWAITING SCREENING';
					break;
			}
			echo '<td align="center">' . ((isset($row['application_status']))?(($row['application_status']=='')?'&nbsp':$application_status):'&nbsp') . '</td>';
			switch($row['application_screening'])
			{
				case 'G':
					$application_screening = '<img title="Highly Suitable" height="25" src="/images/green_button.png" border="0" />';
					break;
				case 'A':
					$application_screening = '<img title="Suitable" height="25" src="/images/amber_button.png" border="0" />';
					break;
				case 'R':
					$application_screening = '<img title="Not Suitable" height="25" src="/images/red_button.png" border="0" />';
					break;
				default:
					$application_screening = 'Not Screened';
					break;
			}
			echo '<td align="center">' . ((isset($row['application_screening']))?(($row['application_screening']=='')?'&nbsp':$application_screening):'&nbsp') . '</td>';
			switch($row['interview_outcome'])
			{
				case 1:
					$interview_outcome = '<img title="Successful" height="25" src="/images/smile-face.png" border="0" />';
					break;
				case 0:
					$interview_outcome = '<img title="UnSuccessful" height="25" src="/images/sad-face.png" border="0" />';
					break;
				default:
					$interview_outcome = "N/A";
					break;
			}
			if($row['application_status'] == '2')
				echo '<td align="center">' . ((isset($row['interview_outcome']))?(($row['interview_outcome']=='')?'&nbsp':$interview_outcome):'&nbsp') . '</td>';
			else
				echo '<td></td>';

			if(is_null($row['application_status']))
			{
				echo '<td align="center"><table cellpadding="6"><tr>';
				echo '<td align="center" class="greenl" width="32" height="40"><input type="radio" value="G" name="screen_' . $row['application_id'] . '" title="Satisfactory" onclick="entry_onclick(this);" /></td>';
				echo '<td align="center" class="yellowl" width="32" height="40"><input type="radio" value="A" name="screen_' . $row['application_id'] . '" title="Average" onclick="entry_onclick(this);"/></td>';
				echo '<td align="center" class="redl" width="32" height="40"><input type="radio" value="R" name="screen_' . $row['application_id'] . '" title="Dis-satisfactory" onclick="entry_onclick(this);"/></td>';
				echo '<td><span id="save_' . $row['application_id'] . '" onclick="screenApplication(' . $row['application_id'] . ', ' . $row['candidate_id'] . ', ' . $row['vacancy_id'] . ');" class="button">Save</span></td>';
				echo '</tr></table></td>';
			}
			elseif($row['application_status'] == RecCandidateApplication::SCREENED )
			{
				$lookup_application_status = array(array(2, "Approve Application"), array(99, "Remove From Vacancy"));
				echo '<td align="center">' . HTML::select('ddl_' . $row['application_id'], $lookup_application_status, '', true) . '<span class="button" onclick="processApplication(' . $row['application_id'] . ', ' . $row['candidate_id'] . ', ' . $row['vacancy_id'] . ');">Save</span></td>';
			}
			elseif($row['application_status'] == RecCandidateApplication::APPROVED && is_null($row['interview_outcome']))
			{
				$lookup_application_status = array(array(3, "Successful Interview"), array(4, "UnSuccessful Interview"), array(99, "Remove From Vacancy"));
				echo '<td align="center">' . HTML::select('ddl_' . $row['application_id'], $lookup_application_status, '', true) . '<span class="button" onclick="processApplication(' . $row['application_id'] . ', ' . $row['candidate_id'] . ', ' . $row['vacancy_id'] . ');">Save</span></td>';
			}
			elseif($row['application_status'] == RecCandidateApplication::APPROVED && $row['interview_outcome'] == 1)
			{
				$lookup_application_status = array(array(5, "Convert to Sunesis Learner"), array(99, "Remove From Vacancy"));
				echo '<td align="center">' . HTML::select('ddl_' . $row['application_id'], $lookup_application_status, '', true) . '<span class="button" onclick="convertApplication(' . $row['application_id'] . ', ' . $row['candidate_id'] . ', ' . $row['vacancy_id'] . ');">Save</span></td>';
			}
			elseif($row['application_status'] == RecCandidateApplication::APPROVED && $row['interview_outcome'] == 0)
			{
				$lookup_application_status = array(array(99, "Remove From Vacancy"));
				echo '<td align="center">' . HTML::select('ddl_' . $row['application_id'], $lookup_application_status, '', true) . '<span class="button" onclick="processApplication(' . $row['application_id'] . ', ' . $row['candidate_id'] . ', ' . $row['vacancy_id'] . ');">Save</span></td>';
			}
			else
				echo '<td align="center"></td>';

			echo '</tr>';
		}
		echo '</tbody></table></div>';
	}
}
?>