<?php
define('METRES_IN_A_MILE', 1609.344);

class rec_view_vacancies implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=rec_view_vacancies", "View Vacancies");

		$view = RecViewVacancies::getInstance($link);
		$view->refresh($link, $_REQUEST);

		if($subaction == 'exportToCSV')
		{
			$this->exportView($link, $view);
			exit;
		}

		$vacancy_postcode = isset($_REQUEST['vacancy_postcode'])?$_REQUEST['vacancy_postcode']:'';

		require_once('tpl_rec_view_vacancies.php');
	}

	private function exportView(PDO $link, RecViewVacancies $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=ViewVacancies.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			$line = '';
			$line .= 'Employer,Reference,Title,Postcode,Total Positions,Created,Screened,Telephone Interview,CV Sent,Interview Successful,Interview Unsuccessful,Withdrawn,Rejected,Sunesis Learner,Filled %';
			echo $line . "\r\n";
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$line = '';
				$line .= $this->csvSafe($row['legal_name']) . ', ';
				$line .= $this->csvSafe($row['vacancy_reference']) . ', ';
				$line .= $this->csvSafe($row['vacancy_title']) . ', ';
				$line .= $this->csvSafe($row['postcode']) . ', ';
				$line .= $this->csvSafe($row['no_of_positions']) . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::CREATED . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::SCREENED . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::TELEPHONE_INTERVIEWED . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::CV_SENT . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_SUCCESSFUL . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_UNSUCCESSFUL . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::WITHDRAWN . "'");
				$line .= $status . ', ';
				$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::REJECTED . "'");
				$line .= $status . ', ';
				$sunesis_learners = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::SUNESIS_LEARNER . "'");
				$line .= $sunesis_learners . ', ';
				$percentage_filled = round(($sunesis_learners / $row['no_of_positions'])*100);
				$line .= $percentage_filled . ', ';
				echo $line . "\r\n";
			}
		}

		exit;
	}

	private function csvSafe($value)
	{
		$value = str_replace(',', ';', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		//$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}
?>
