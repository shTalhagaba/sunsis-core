<?php

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class baltic_view_candidate_availability_report implements IAction
{
	private $age = "";
	public function execute(PDO $link)
	{
		set_time_limit(0);
		ini_set('memory_limit', '2048M');

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=baltic_view_candidate_availability_report", "View Candidates Availability Report");

		$region_dropdown = DAO::getResultset($link, "SELECT description, description FROM lookup_vacancy_regions ORDER BY description;");
		$status_dorpdown = DAO::getResultset($link, "SELECT id, description, null FROM lookup_candidate_status ORDER BY description");
		$age_group_options = array(
			0 => array(0, 'Show all', null, null),
			1 => array(1, 'Less than 16', null, ' '),
			2 => array(2, '16 - 18', null, ' '),
			3 => array(3, '19 - 23', null, ' '),
			4 => array(4, '24+', null, ' '),
			5 => array(5, 'Unknown', null, ' '),
			6 => array(6, 'Out of Range', null, ' ')
		);
		$age_group_options = array(
			0 => array(0, 'Show all', null, null),
			1 => array(1, 'Less than 16', null, '  '),
			2 => array(2, '18 or less', null, '  '),
			3 => array(3, '16 - 18', null, ' '),
			4 => array(4, '19 or more', null, ' '),
			5 => array(5, '19 - 23', null, ' '),
			6 => array(6, '24+', null, ' '),
			7 => array(7, 'Unknown', null, ' '),
			8 => array(8, 'Out of Range', null, ' ')
		);

		$ethnicity_dropdown = DAO::getResultSet($link, "SELECT Ethnicity AS id, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) AS description, null FROM lis201314.ilr_ethnicity");

		$region = isset($_REQUEST['region']) ? $_REQUEST['region'] : false;
		$postcode = isset($_REQUEST['postcode']) ? $_REQUEST['postcode'] : false;
		$age_group = isset($_REQUEST['age_group']) ? $_REQUEST['age_group'] : false;
		$ethnicity = isset($_REQUEST['ethnicity']) ? $_REQUEST['ethnicity'] : false;

		$export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';

		$where_clause = " WHERE (1 = 1) AND (candidate.username = '' OR candidate.username IS NULL) ";


		if ($region)
			$where_clause .= " AND (candidate.region = '" . $region . "')  ";
		if ($postcode)
			$where_clause .= " AND (candidate.postcode = '" . $postcode . "')  ";
		if ($age_group) {
			$this->age = $age_group;
			switch ($age_group) {
				case 0:
					$where_clause .= " AND candidate.dob IS NOT NULL AND candidate.dob != ''  ";
					break;
				case 1:
					$where_clause .= " AND (timestampdiff(YEAR,candidate.dob,CURDATE()) BETWEEN 5 AND 15) AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
				case 2:
					$where_clause .= " AND (timestampdiff(YEAR,candidate.dob,CURDATE()) BETWEEN 5 AND 18) AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
				case 3:
					$where_clause .= " AND (timestampdiff(YEAR,candidate.dob,CURDATE()) BETWEEN 16 AND 18) AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
				case 4:
					$where_clause .= " AND (timestampdiff(YEAR,candidate.dob,CURDATE()) >= 19) AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
				case 5:
					$where_clause .= " AND (timestampdiff(YEAR,candidate.dob,CURDATE()) > 18 AND timestampdiff(YEAR,candidate.dob,CURDATE()) <= 23) AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
				case 6:
					$where_clause .= " AND (timestampdiff(YEAR,candidate.dob,CURDATE()) >= 24) AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
				case 7:
					$where_clause .= " AND (candidate.dob IS NULL OR candidate.dob = '0000-00-00' OR candidate.dob = '' ) ";
					break;
				case 8:
					$where_clause .= " AND ((timestampdiff(YEAR,candidate.dob,CURDATE()) <=0 ) OR (timestampdiff(YEAR,candidate.dob,CURDATE()) >= 100))";
					break;
				default:
					$where_clause .= " AND candidate.dob IS NOT NULL AND candidate.dob != '' ";
					break;
			}
		}
		if ($ethnicity)
			$where_clause .= " AND (candidate.ethnicity= '" . $ethnicity . "')  ";

		if (!isset($_REQUEST['age_group']))
			$_REQUEST['age_group'] = 0;
		if (!isset($_REQUEST['region']))
			$_REQUEST['region'] = '';
		if (!isset($_REQUEST['ethnicity']))
			$_REQUEST['ethnicity'] = '';
		$report1 = $this->generateStats($link, true, false, false, false, $where_clause, $_REQUEST);
		$report2 = $this->generateStats($link, false, true, false, false, $where_clause, $_REQUEST);
		$report3 = $this->generateStats($link, false, false, true, false, $where_clause, $_REQUEST);
		$report4 = $this->generateStats($link, false, false, false, true, $where_clause, $_REQUEST);

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
			$by_age_group = $reader->load($tmpfile);
			$by_age_group->getActiveSheet()->setTitle('Group By Age Group');
			foreach ($by_age_group->getAllSheets() as $sheet) {
				$excel_report->addExternalSheet($sheet);
			}

			$report4 = preg_replace('/<\/?a[^>]*>/', '', $report4);
			file_put_contents($tmpfile, $report4);
			$by_ethnicity = $reader->load($tmpfile);
			$by_ethnicity->getActiveSheet()->setTitle('Group By Ethnicity');
			foreach ($by_ethnicity->getAllSheets() as $sheet) {
				$excel_report->addExternalSheet($sheet);
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
			header('Cache-Control: max-age=0');
			header('Pragma: public');

			$objWriter = new Xlsx($excel_report);
			$objWriter->save('php://output');

			// Delete temporary file
			unlink($tmpfile);
		}

		require_once('tpl_baltic_view_candidate_availability_report.php');
	}

	private function generateStats(PDO $link, $region, $postcode, $age_group, $ethnicity, $where, $filters)
	{
		$select_clause = "";
		$group_clasue = "";
		$order_clause = "";
		$html_column_title = "";
		$html_column_value = "";
		$hyperlink = "";
		if ($region) {
			$select_clause = " (SELECT description FROM lookup_vacancy_regions WHERE description = candidate.region) AS region, ";
			//$where .= " AND candidate.region IS NOT NULL AND candidate.region != '' ";
			$group_clasue = " GROUP BY candidate.region ";
			$html_column_title = "Region";
			$html_column_value = "region";
			$order_clause = " ORDER BY region ";
			$hyperlink = "do.php?_action=baltic_view_candidates&ViewCandidates_filter_region=";
		} elseif ($postcode) {
			$select_clause = " candidate.postcode, ";
			$where .= " AND candidate.postcode IS NOT NULL AND candidate.postcode != '' ";
			$group_clasue = " GROUP BY candidate.postcode ";
			$html_column_title = "Postcode";
			$html_column_value = "postcode";
			$order_clause = " ORDER BY postcode ";
		} elseif ($age_group) {
			$select_clause = " timestampdiff(YEAR,candidate.dob,CURDATE()) AS age_in_years, ";
			if (!strpos($where, 'candidate.dob IS NULL'))
				$where .= " AND candidate.dob IS NOT NULL AND candidate.dob != '' AND candidate.dob != '0000-00-00' ";
			$group_clasue = " GROUP BY timestampdiff(YEAR,candidate.dob,CURDATE()) ";
			$html_column_title = "Age Group";
			$html_column_value = "age_in_years";
			$order_clause = " ORDER BY age_in_years ";
		} elseif ($ethnicity) {
			$select_clause = " candidate.ethnicity, ";
			$where .= " AND candidate.ethnicity IS NOT NULL AND candidate.ethnicity != '' ";
			$group_clasue = " GROUP BY candidate.ethnicity ";
			$html_column_title = "Ethnicity";
			$html_column_value = "ethnicity";
			$order_clause = " ORDER BY ethnicity ";
			$hyperlink = "do.php?_action=baltic_view_candidates&ViewCandidates_filter_ethnicity=";
		}

		$sql = <<<HEREDOC
	SELECT
	$select_clause
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = '19+'), 1, 0))) AS `19_plus`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = '25+'), 1, 0))) AS `25_plus`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'DNU'), 1, 0))) AS `DNU`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'DUPLICATE'), 1, 0))) AS `DUPLICATE`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'FAILED INTERVIE'), 1, 0))) AS `FAILED_INTERVIE`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'FOJ'), 1, 0))) AS `FOJ`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'GOLD CANDIDATE'), 1, 0))) AS `GOLD_CANDIDATE`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'HOLD'), 1, 0))) AS `HOLD`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'INELIGIBLE'), 1, 0))) AS `INELIGIBLE`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'LEAVER2014'), 1, 0))) AS `LEAVER2014`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'LEAVER2015'), 1, 0))) AS `LEAVER2015`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'LEAVER2016'), 1, 0))) AS `LEAVER2016`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'LIVE'), 1, 0))) AS `LIVE`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'PASSED INTERVIEW'), 1, 0))) AS `PASSED_INTERVIEW`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'PLACED'), 1, 0))) AS `PLACED`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'PLACED - DNU'), 1, 0))) AS `PLACED_DNU`,
	(SUM(IF(candidate.`status_code` = (SELECT id FROM lookup_candidate_status WHERE description = 'QUERY'), 1, 0))) AS `QUERY`,
	(SUM(IF(candidate.`status_code` IS NULL, 1, 0))) AS `BLANK_STATUS`,
	(SUM(IF(candidate.`status_code` NOT BETWEEN 1 AND 17 AND candidate.`status_code` IS NOT NULL, 1, 0))) AS `OTHER_STATUS`


FROM
	candidate

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
	<thead><tr>
	    <th class="topRow" style="font-size:80%; color:#555555">$html_column_title</th>
	    <th class="topRow" style="font-size:80%; color:#555555">19+</th>
	    <th class="topRow" style="font-size:80%; color:#555555">25+</th>
	    <th class="topRow" style="font-size:80%; color:#555555">DNU</th>
	    <th class="topRow" style="font-size:80%; color:#555555">DUPLICATE</th>
	    <th class="topRow" style="font-size:80%; color:#555555">FAILED INTERVIEW</th>
	    <th class="topRow" style="font-size:80%; color:#555555">FOJ</th>
	    <th class="topRow" style="font-size:80%; color:#555555">GOLD CANDIDATE</th>
	    <th class="topRow" style="font-size:80%; color:#555555">HOLD</th>
	    <th class="topRow" style="font-size:80%; color:#555555">INELIGIBLE</th>
	    <th class="topRow" style="font-size:80%; color:#555555">LEAVER2014</th>
	    <th class="topRow" style="font-size:80%; color:#555555">LEAVER2015</th>
	    <th class="topRow" style="font-size:80%; color:#555555">LEAVER2016</th>
	    <th class="topRow" style="font-size:80%; color:#555555">LIVE</th>
	    <th class="topRow" style="font-size:80%; color:#555555">PASSED INTERVIEW</th>
	    <th class="topRow" style="font-size:80%; color:#555555">PLACED</th>
	    <th class="topRow" style="font-size:80%; color:#555555">PLACED - DNU</th>
	    <th class="topRow" style="font-size:80%; color:#555555">QUERY</th>
	    <th class="topRow" style="font-size:80%; color:#555555">BLANK STATUS</th>
	    <th class="topRow" style="font-size:80%; color:#555555">OTHER STATUS</th>
	    <th class="topRow" style="font-size:80%; color:#555555">Total</th>
   </tr></thead>
   <tbody>

HEREDOC;

		$html .= "<tbody>";
		$col1 = 0;
		$col2 = 0;
		$col3 = 0;
		$col4 = 0;
		$col5 = 0;
		$col6 = 0;
		$col7 = 0;
		$col8 = 0;
		$col9 = 0;
		$col10 = 0;
		$col11 = 0;
		$col12 = 0;
		$col13 = 0;
		$col14 = 0;
		$col15 = 0;
		$col16 = 0;
		$col17 = 0;
		$col18 = 0;
		$col19 = 0;
		$col20 = 0;
		foreach ($result as $row) {
			$row_total = intval($row['19_plus']) + intval($row['25_plus']) + intval($row['DNU']) + intval($row['DUPLICATE']) + intval($row['FAILED_INTERVIE']) + intval($row['FOJ']) + intval($row['GOLD_CANDIDATE']) + intval($row['HOLD']) + intval($row['INELIGIBLE']) + intval($row['LEAVER2014']) + intval($row['LEAVER2015']) + intval($row['LEAVER2016']) + intval($row['LIVE']) + intval($row['PASSED_INTERVIEW']) + intval($row['PLACED']) + intval($row['PLACED_DNU']) + intval($row['QUERY']) + intval($row['BLANK_STATUS']) + intval($row['OTHER_STATUS']);
			$col1 += $row['19_plus'];
			$col2 += $row['25_plus'];
			$col3 += $row['DNU'];
			$col4 += $row['DUPLICATE'];
			$col5 += $row['FAILED_INTERVIE'];
			$col6 += $row['FOJ'];
			$col7 += $row['GOLD_CANDIDATE'];
			$col8 += $row['HOLD'];
			$col9 += $row['INELIGIBLE'];
			$col10 += $row['LEAVER2014'];
			$col11 += $row['LEAVER2015'];
			$col12 += $row['LEAVER2016'];
			$col13 += $row['LIVE'];
			$col14 += $row['PASSED_INTERVIEW'];
			$col15 += $row['PLACED'];
			$col16 += $row['PLACED_DNU'];
			$col17 += $row['QUERY'];
			$col18 += $row['BLANK_STATUS'];
			$col19 += $row['OTHER_STATUS'];
			$col20 += $row_total;
			if ($region) {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=1&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=1&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['19_plus'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=2&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=2&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['25_plus'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=3&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=3&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['DNU'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=4&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=4&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['DUPLICATE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=5&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=5&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['FAILED_INTERVIE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=6&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=6&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['FOJ'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=7&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=7&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['GOLD_CANDIDATE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=8&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=8&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['HOLD'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=9&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=9&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['INELIGIBLE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=10&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=10&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2014'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=11&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=11&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2015'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=12&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=12&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2016'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=13&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=13&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LIVE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=14&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=14&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PASSED_INTERVIEW'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=15&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=15&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PLACED'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=16&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=16&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PLACED_DNU'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=17&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=17&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['QUERY'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_appliedfor=NULL&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_appliedfor=NULL&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['BLANK_STATUS'] . '</a></td>' .
					//					'<td align="left">' . $row['BLANK_STATUS'] . '</td>' .
					'<td align="left">' . $row['OTHER_STATUS'] . '</td>' .
					'<td align="left">' . $row_total . '</a></td></tr>';
			} elseif ($postcode) {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=1&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['19_plus'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=2&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['25_plus'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=3&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['DNU'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=4&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['DUPLICATE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=5&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['FAILED_INTERVIE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=6&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['FOJ'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=7&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['GOLD_CANDIDATE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=8&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['HOLD'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=9&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['INELIGIBLE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=10&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2014'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=11&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2015'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=12&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2016'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=13&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LIVE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=14&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PASSED_INTERVIEW'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=15&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PLACED'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=16&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PLACED_DNU'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=17&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['QUERY'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_postcodes=' . $row[$html_column_value] . '&ViewCandidates_filter_distance=0' . '&ViewCandidates_filter_appliedfor=NULL&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['BLANK_STATUS'] . '</a></td>' .
					//					'<td align="left">' . $row['BLANK_STATUS'] . '</td>' .
					'<td align="left">' . $row['OTHER_STATUS'] . '</td>' .
					'<td align="left">' . $row_total . '</a></td></tr>';
			} elseif ($ethnicity) {
				$e = DAO::getSingleValue($link, "SELECT LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc), 60) FROM lis201314.ilr_ethnicity WHERE Ethnicity = $row[$html_column_value]");
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$e) . '</td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=1&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['19_plus'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=2&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['25_plus'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=3&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['DNU'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=4&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['DUPLICATE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=5&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['FAILED_INTERVIE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=6&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['FOJ'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=7&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['GOLD_CANDIDATE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=8&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['HOLD'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=9&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['INELIGIBLE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=10&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2014'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=11&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2015'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=12&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LEAVER2016'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=13&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['LIVE'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=14&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PASSED_INTERVIEW'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=15&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PLACED'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=16&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['PLACED_DNU'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=17&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['QUERY'] . '</a></td>' .
					'<td align="left"><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_ethnicity=' . urlencode($row[$html_column_value]) . '&ViewCandidates_filter_region=' . urlencode($filters['region']) . '&ViewCandidates_filter_appliedfor=NULL&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $row['BLANK_STATUS'] . '</a></td>' .
					//					'<td align="left">' . $row['BLANK_STATUS'] . '</td>' .
					'<td align="left">' . $row['OTHER_STATUS'] . '</td>' .
					'<td align="left">' . $row_total . '</a></td></tr>';
			} else {
				$html .= '<tr><td align="left">' . htmlspecialchars((string)$row[$html_column_value]) . '</td>' .
					'<td align="left">' . $row['19_plus'] . '</td>' .
					'<td align="left">' . $row['25_plus'] . '</td>' .
					'<td align="left">' . $row['DNU'] . '</td>' .
					'<td align="left">' . $row['DUPLICATE'] . '</td>' .
					'<td align="left">' . $row['FAILED_INTERVIE'] . '</td>' .
					'<td align="left">' . $row['FOJ'] . '</td>' .
					'<td align="left">' . $row['GOLD_CANDIDATE'] . '</td>' .
					'<td align="left">' . $row['HOLD'] . '</td>' .
					'<td align="left">' . $row['INELIGIBLE'] . '</td>' .
					'<td align="left">' . $row['LEAVER2014'] . '</td>' .
					'<td align="left">' . $row['LEAVER2015'] . '</td>' .
					'<td align="left">' . $row['LEAVER2016'] . '</td>' .
					'<td align="left">' . $row['LIVE'] . '</td>' .
					'<td align="left">' . $row['PASSED_INTERVIEW'] . '</td>' .
					'<td align="left">' . $row['PLACED'] . '</td>' .
					'<td align="left">' . $row['PLACED_DNU'] . '</td>' .
					'<td align="left">' . $row['QUERY'] . '</td>' .
					'<td align="left">' . $row['BLANK_STATUS'] . '</td>' .
					'<td align="left">' . $row['OTHER_STATUS'] . '</td>' .
					'<td align="left">' . $row_total . '</td></tr>';
			}
		}
		if ($age_group) {
			$html .= '<tr><td>Total</td><td>' . $col1 . '</td><td>' . $col2 . '</td><td>' . $col3 . '</td><td>' . $col4 . '</td><td>' . $col5 . '</td><td>' . $col6 . '</td><td>' . $col7 . '</td><td>' . $col8 . '</td><td>' . $col9 . '</td><td>' . $col10 . '</td>' .
				'<td>' . $col11 . '</td><td>' . $col12 . '</td><td>' . $col13 . '</td><td>' . $col14 . '</td><td>' . $col15 . '</td><td>' . $col16 . '</td><td>' . $col17 . '</td><td>' . $col18 . '</td><td>' . $col19 . '</td>';
			$html .= '<td><a href="do.php?_action=baltic_view_candidates&_reset=1&ViewCandidates_filter_region=' . $filters['region'] . '&ViewCandidates_filter_ethnicity=' . $filters['ethnicity'] . '&ViewCandidates_filter_age_custom=' . $filters['age_group'] . '">' . $col20 . '</td></tr>';
		} else {
			$html .= '<tr><td>Total</td><td>' . $col1 . '</td><td>' . $col2 . '</td><td>' . $col3 . '</td><td>' . $col4 . '</td><td>' . $col5 . '</td><td>' . $col6 . '</td><td>' . $col7 . '</td><td>' . $col8 . '</td><td>' . $col9 . '</td><td>' . $col10 . '</td>' .
				'<td>' . $col11 . '</td><td>' . $col12 . '</td><td>' . $col13 . '</td><td>' . $col14 . '</td><td>' . $col15 . '</td><td>' . $col16 . '</td><td>' . $col17 . '</td><td>' . $col18 . '</td><td>' . $col19 . '</td><td>' . $col20 . '</td></tr>';
		}
		$html .= "</tbody></table></div>";

		return $html;
	}
}