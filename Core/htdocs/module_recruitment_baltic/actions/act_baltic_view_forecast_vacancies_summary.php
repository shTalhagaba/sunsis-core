<?php

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class baltic_view_forecast_vacancies_summary implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=baltic_view_forecast_vacancies_summary&forecast_fill_year=2015", "View Vacancies Forecast Summary");

		$region_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_vacancy_regions ORDER BY description;");
		$brm_dropdown = DAO::getResultset($link, "SELECT username, CONCAT(firstnames, ' ', surname) FROM users WHERE type = 23 ORDER BY firstnames;");
		$sector_dropdown = DAO::getResultset($link, "SELECT id, description, null FROM lookup_vacancy_type WHERE id != 14 ORDER BY description asc;");
		$employers_dropdown = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = 2 ORDER BY legal_name asc;");
		$apprenticeship_type_dorpdown = DAO::getResultset($link, "SELECT id, description, null FROM lookup_vacancy_app_type ORDER BY description");
		$status_dorpdown = DAO::getResultset($link, "SELECT id, description, null FROM lookup_vacancy_status ORDER BY description");
		$forecast_fill_month_dropdown = array(array(1, 'January'), array(2, 'February'), array(3, 'March'), array(4, 'April'), array(5, 'May'), array(6, 'June'), array(7, 'July'), array(8, 'August'), array(9, 'September'), array(10, 'October'), array(11, 'November'), array(12, 'December'));
		$forecast_fill_year_dropdown = array(array(2014, '2014'), array(2015, '2015'), array(2016, '2016'));
		$active_vacancy_dropdown = array(
			0 => array(0, 'Show all', null, null),
			1 => array(1, 'Yes', null, null),
			2 => array(2, 'No', null, null)
		);

		$region = isset($_REQUEST['region']) ? $_REQUEST['region'] : false;
		$postcode = isset($_REQUEST['postcode']) ? $_REQUEST['postcode'] : false;
		$brm = isset($_REQUEST['brm']) ? $_REQUEST['brm'] : false;
		$employer = isset($_REQUEST['employer']) ? $_REQUEST['employer'] : false;
		$sector = isset($_REQUEST['sector']) ? $_REQUEST['sector'] : false;
		$apprenticeship_type = isset($_REQUEST['apprenticeship_type']) ? $_REQUEST['apprenticeship_type'] : false;
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : false;
		$forecast_fill_month = isset($_REQUEST['forecast_fill_month']) ? $_REQUEST['forecast_fill_month'] : false;
		$active_vacancy = isset($_REQUEST['active_vacancy']) ? $_REQUEST['active_vacancy'] : false;
		$forecast_fill_year = isset($_REQUEST['forecast_fill_year']) ? $_REQUEST['forecast_fill_year'] : '2016';

		$export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';

		$where_clause = " WHERE (1 = 1) AND vacancies.type != 14 AND vacancies.`employer_id` IN (SELECT id FROM organisations WHERE organisation_type = 2) ";

		if ($region)
			$where_clause .= " AND (vacancies.region = " . $region . ") ";
		if ($postcode)
			$where_clause .= " AND (vacancies.postcode = " . $postcode . ") ";
		if ($brm)
			$where_clause .= " AND (vacancies.brm = '" . $brm . "') ";
		if ($employer)
			$where_clause .= " AND (vacancies.employer_id = " . $employer . ") ";
		if ($sector)
			$where_clause .= " AND (vacancies.type = " . $sector . ") ";
		if ($apprenticeship_type)
			$where_clause .= " AND (vacancies.apprenticeship_type = " . $apprenticeship_type . ") ";
		if ($status)
			$where_clause .= " AND (vacancies.status = " . $status . ") ";
		if ($forecast_fill_month)
			$where_clause .= " AND (MONTHNAME(vacancies.date_expected_to_fill) = " . $forecast_fill_month . ") ";
		if ($forecast_fill_year)
			$where_clause .= " AND (YEAR(vacancies.date_expected_to_fill) = " . $forecast_fill_year . ") ";
		if ($active_vacancy) {
			switch ($active_vacancy) {
				case 0:
					$where_clause .= " AND (1=1) ";
					break;
				case 1:
					$where_clause .= " AND (vacancies.active = " . $active_vacancy . ") ";
					break;
				case 2:
					$where_clause .= " AND (vacancies.active = 0) ";
					break;
					defaul:
					$where_clause .= " AND (1=1) ";
					break;
			}
		}

		if (!isset($_REQUEST['forecast_fill_month']))
			$_REQUEST['forecast_fill_month'] = 0;
		$report1 = $this->generateStats($link, true, false, false, false, false, $where_clause, $_REQUEST);
		$report2 = $this->generateStats($link, false, true, false, false, false, $where_clause, $_REQUEST);
		$report3 = $this->generateStats($link, false, false, true, false, false, $where_clause, $_REQUEST);
		$report4 = $this->generateStats($link, false, false, false, true, false, $where_clause, $_REQUEST);
		$report5 = $this->generateStats($link, false, false, false, false, true, $where_clause, $_REQUEST);

		if (isset($export) && $export == 'export') {
			define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

			// Put the html into a temporary file
			$tmpfile = DATA_ROOT . '/uploads/' . DB_NAME . '/' . time() . '.html';

			$report1 = preg_replace('/<\/?a[^>]*>/', '', $report1);
			file_put_contents($tmpfile, $report1);
			// Read the contents of the file into PHPSpreadSheet Reader class
			$reader = new Html;
			$excel_report = $reader->load($tmpfile);
			$excel_report->getActiveSheet()->setTitle('Group By Region');

			$report2 = preg_replace('/<\/?a[^>]*>/', '', $report2);
			file_put_contents($tmpfile, $report2);
			$by_location = $reader->load($tmpfile);
			$by_location->getActiveSheet()->setTitle('Group By Location');
			foreach ($by_location->getAllSheets() as $sheet) {
				$excel_report->addExternalSheet($sheet);
			}

			$report3 = preg_replace('/<\/?a[^>]*>/', '', $report3);
			file_put_contents($tmpfile, $report3);
			$by_brm = $reader->load($tmpfile);
			$by_brm->getActiveSheet()->setTitle('Group By BRM');
			foreach ($by_brm->getAllSheets() as $sheet) {
				$excel_report->addExternalSheet($sheet);
			}

			$report4 = preg_replace('/<\/?a[^>]*>/', '', $report4);
			file_put_contents($tmpfile, $report4);
			$by_employer = $reader->load($tmpfile);
			$by_employer->getActiveSheet()->setTitle('Group By Employer');
			foreach ($by_employer->getAllSheets() as $sheet) {
				$excel_report->addExternalSheet($sheet);
			}

			$report5 = preg_replace('/<\/?a[^>]*>/', '', $report5);
			file_put_contents($tmpfile, $report5);
			$by_sector = $reader->load($tmpfile);
			$by_sector->getActiveSheet()->setTitle('Group By Sector');
			foreach ($by_sector->getAllSheets() as $sheet) {
				$excel_report->addExternalSheet($sheet);
			}


			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
			header('Cache-Control: max-age=0');
			header('Pragma: public');

			// Pass to writer and output as needed
			$objWriter = new Xlsx($excel_report);
			//$objWriter->save('excelfile.xlsx');
			$objWriter->save('php://output');

			// Delete temporary file
			unlink($tmpfile);
		}

		require_once('tpl_baltic_view_forecast_vacancies_summary.php');
	}

	private function generateStats(PDO $link, $region, $postcode, $brm, $employer, $sector, $where, $filters = array())
	{
		$select_clause = "";
		$group_clasue = "";
		$order_clause = "";
		$html_column_title = "";
		$html_column_value = "";
		$hyperlink = "";
		if ($region) {
			$select_clause = " (SELECT description FROM lookup_vacancy_regions WHERE id = vacancies.region) AS region, vacancies.region AS vacancies_region_id, ";
			$group_clasue = " GROUP BY vacancies.region ";
			$html_column_title = "Region";
			$html_column_value = "region";
			$order_clause = " ORDER BY region ";
			$hyperlink = "do.php?_action=baltic_view_forecast_vacancies&ViewForecastVacancies_filter_region=";
		} elseif ($postcode) {
			$select_clause = " vacancies.postcode, ";
			$group_clasue = " GROUP BY vacancies.postcode ";
			$html_column_title = "Postcode";
			$html_column_value = "postcode";
			$order_clause = " ORDER BY postcode ";
		} elseif ($brm) {
			$select_clause = " (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE username = vacancies.brm) AS brm, vacancies.brm AS brm_username, ";
			$group_clasue = " GROUP BY vacancies.brm ";
			$html_column_title = "BRM";
			$html_column_value = "brm";
			$order_clause = " ORDER BY brm ";
		} elseif ($employer) {
			$select_clause = " (SELECT legal_name FROM organisations WHERE id = vacancies.`employer_id`) AS employer, ";
			$group_clasue = " GROUP BY vacancies.employer_id ";
			$html_column_title = "Employer";
			$html_column_value = "employer";
			$order_clause = " ORDER BY employer ";
		} elseif ($sector) {
			$select_clause = " (SELECT description FROM lookup_vacancy_type WHERE id = vacancies.type) AS sector, vacancies.type AS vacancies_sector, ";
			$group_clasue = " GROUP BY vacancies.type ";
			$html_column_title = "Sector";
			$html_column_value = "sector";
			$order_clause = " ORDER BY sector ";
		}

		$sql = <<<HEREDOC
	SELECT
	$select_clause
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Live'), 1, 0))) AS live_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Live'), 1, 0)))*10)/100) AS live_vacancies_pg,
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Candidate Selection'), 1, 0))) AS candidate_selection_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Candidate Selection'), 1, 0)))*30)/100) AS candidate_selection_vacancies_pg,
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Client Interview'), 1, 0))) AS client_interview_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Client Interview'), 1, 0)))*70)/100) AS client_interview_vacancies_pg,
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Offer Pending'), 1, 0))) AS offer_pending_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Offer Pending'), 1, 0)))*80)/100) AS offer_pending_vacancies_pg,
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Induction Pending'), 1, 0))) AS induction_pending_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Induction Pending'), 1, 0)))*90)/100) AS induction_pending_vacancies_pg,
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Filled'), 1, 0))) AS filled_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Filled'), 1, 0)))*100)/100) AS filled_vacancies_pg,
	(SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Withdrawn'), 1, 0))) AS withdrawn_vacancies,
	CEIL(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Withdrawn'), 1, 0)))*100)/100) AS withdrawn_vacancies_pg,
	(ROUND(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Live'), 1, 0)))*10)/100))
	+ (ROUND(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Candidate Selection'), 1, 0)))*30)/100))
	+ (ROUND(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Client Interview'), 1, 0)))*70)/100))
	+ (ROUND(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Offer Pending'), 1, 0)))*80)/100))
	+ (ROUND(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Induction Pending'), 1, 0)))*90)/100))
	+ (ROUND(((SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Filled'), 1, 0)))*100)/100)) AS potential_starts


FROM
	vacancies

$where

$group_clasue

$order_clause
;
HEREDOC;
		//echo $sql;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = <<<HEREDOC
<div align="center">
<table class="resultset" border="0" cellspacing="0" cellpadding="6">
	<tr>
	    <th class="bottomRow" style="font-size:80%; color:#555555">$html_column_title</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Live (10%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Candidate Selection (30%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Client Interview (70%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Offer Pending (80%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Induction Pending (90%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Filled (100%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Withdrawn (0%)</th>
	    <th class="bottomRow" style="font-size:80%; color:#555555">Potential Starts</th>
    </tr>


HEREDOC;

		$html .= "<tbody>";
		$total_potential_starts = 0;
		foreach ($result as $row) {
			if ($employer) { //<input id="ViewCandidates_filter_applied_directly" type="hidden" value="1" name="ViewCandidates_filter_applied_directly">
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=6&ViewVacancies_filter_isactive=1">' . $row['live_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=13&ViewVacancies_filter_isactive=1">' . $row['candidate_selection_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=10&ViewVacancies_filter_isactive=1">' . $row['client_interview_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=11&ViewVacancies_filter_isactive=1">' . $row['offer_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=12&ViewVacancies_filter_isactive=1">' . $row['induction_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=4&ViewVacancies_filter_isactive=1">' . $row['filled_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_employername=' . $row[$html_column_value] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year']  . '&ViewVacancies_filter_rec_stage=14&ViewVacancies_filter_isactive=1">' . $row['withdrawn_vacancies'] . '</a></td>' .
					'<td align="left">' . $row['potential_starts'] . '</td></tr>';
			} elseif ($region) {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=6&ViewVacancies_filter_isactive=1">' . $row['live_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=13&ViewVacancies_filter_isactive=1">' . $row['candidate_selection_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=10&ViewVacancies_filter_isactive=1">' . $row['client_interview_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=11&ViewVacancies_filter_isactive=1">' . $row['offer_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=12&ViewVacancies_filter_isactive=1">' . $row['induction_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=4&ViewVacancies_filter_isactive=1">' . $row['filled_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_region=' . $row['vacancies_region_id'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=14&ViewVacancies_filter_isactive=1">' . $row['withdrawn_vacancies'] . '</a></td>' .
					'<td align="left">' . $row['potential_starts'] . '</td></tr>';
			} elseif ($brm) {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=6&ViewVacancies_filter_isactive=1">' . $row['live_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=13&ViewVacancies_filter_isactive=1">' . $row['candidate_selection_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=10&ViewVacancies_filter_isactive=1">' . $row['client_interview_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=11&ViewVacancies_filter_isactive=1">' . $row['offer_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=12&ViewVacancies_filter_isactive=1">' . $row['induction_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=4&ViewVacancies_filter_isactive=1">' . $row['filled_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_brm=' . $row['brm_username'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=14&ViewVacancies_filter_isactive=1">' . $row['withdrawn_vacancies'] . '</a></td>' .
					'<td align="left">' . $row['potential_starts'] . '</td></tr>';
			} elseif ($sector) {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=6&ViewVacancies_filter_isactive=1">' . $row['live_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=13&ViewVacancies_filter_isactive=1">' . $row['candidate_selection_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=10&ViewVacancies_filter_isactive=1">' . $row['client_interview_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=11&ViewVacancies_filter_isactive=1">' . $row['offer_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=12&ViewVacancies_filter_isactive=1">' . $row['induction_pending_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=4&ViewVacancies_filter_isactive=1">' . $row['filled_vacancies'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_vacancies&_reset=1&ViewVacancies_filter_sectortype=' . $row['vacancies_sector'] . '&ViewVacancies_filter_month_expected_to_fill=' . $filters['forecast_fill_month'] . '&ViewVacancies_filter_year_expected_to_fill=' . $filters['forecast_fill_year'] . '&ViewVacancies_filter_rec_stage=14&ViewVacancies_filter_isactive=1">' . $row['withdrawn_vacancies'] . '</a></td>' .
					'<td align="left">' . $row['potential_starts'] . '</td></tr>';
			} else {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left">' . $row['live_vacancies'] . '</td>' .
					'<td align="left">' . $row['candidate_selection_vacancies'] . '</td>' .
					'<td align="left">' . $row['client_interview_vacancies'] . '</td>' .
					'<td align="left">' . $row['offer_pending_vacancies'] . '</td>' .
					'<td align="left">' . $row['induction_pending_vacancies'] . '</td>' .
					'<td align="left">' . $row['filled_vacancies'] . '</td>' .
					'<td align="left">' . $row['withdrawn_vacancies'] . '</td>' .
					'<td align="left">' . $row['potential_starts'] . '</td></tr>';
				//$total_potential_starts += $row['potential_starts'];
			}
		}

		//$html .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>' . $total_potential_starts . '</td>';
		$html .= "</tbody></table></div>";

		return $html;
	}

	private function exportToExcel() {}
}
