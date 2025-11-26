<?php
class summary implements IAction
{
	public function execute(PDO $link)
	{
		$months = array(array('',''),array(1,'January'),array(2,'February'),array(3,'March'),array(4,'April'),array(5,'May'),array(6,'June'),array(7,'July'),array(8,'August'),array(9,'September'),array(10,'October'),array(11,'November'),array(12,'December'));
		$region_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_vacancy_regions ORDER BY description;");
		$brm_dropdown = DAO::getResultset($link, "SELECT username, CONCAT(firstnames, ' ', surname) FROM users WHERE type = 23 ORDER BY firstnames;");
		$sector_dropdown = DAO::getResultset($link, "SELECT id, description, null FROM lookup_vacancy_type ORDER BY description asc;");
		$employers_dropdown = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = 2 ORDER BY legal_name asc;");

		$month = isset($_REQUEST['month'])?$_REQUEST['month']:'';
		$region = isset($_REQUEST['region'])?$_REQUEST['region']:'';
		$brm = isset($_REQUEST['brm'])?$_REQUEST['brm']:'';
		$sector = isset($_REQUEST['sector'])?$_REQUEST['sector']:'';
		$employer = isset($_REQUEST['employer'])?$_REQUEST['employer']:'';

		if($month == '')
			$month = date('n');

		$month_name_1 = $this->getMonthName($month);
		$month_name_2 = $this->getMonthName($month+1);
		$month_name_3 = $this->getMonthName($month+2);

		$result = $this->getResult($link, $month, $region, $brm, $sector, $employer);
		$result1 = $this->getResult($link, $month + 1, $region, $brm, $sector, $employer);
		$result2 = $this->getResult($link, $month + 2, $region, $brm, $sector, $employer);


		$open = $result[$month]['open'];
		$selection = $result[$month]['selection'];
		$ci = $result[$month]['ci'];
		$op = $result[$month]['op'];
		$ip = $result[$month]['ip'];
		$f = $result[$month]['f'];
		$ps = $result[$month]['ps'];

		$ps1 = $result1[$month+1]['ps'];
		$ps2 = $result2[$month+2]['ps'];

		$column_title = '';
		$column_value = '';
		if($region!= '')
		{
			$column_title = "Region";
			$column_value = DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_regions WHERE id = " . $region);

		}
		elseif($brm!= '')
		{
			$column_title = "Business Resource Manger";
			$column_value = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE username = '" . $brm . "'");

		}
		elseif($sector!= '')
		{
			$column_title = "Sector";
			$column_value = DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_type WHERE  id = '" . $sector . "'");

		}
		elseif($employer!= '')
		{
			$column_title = "Employer";
			$column_value = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '" . $employer . "'");

		}
		else
		{
			$column_title = "Month";
			$column_value = $month_name_1;

		}

		$table = <<<HEREDOC
		<table class="resultset" border="0" cellspacing="0" cellpadding="6">
		<tr>
			<thead>
				<th>$column_title</th><th>Open (10%)</th><th>Selection (30%)</th><th>Client Interview (70%)</th><th>Offer Pending (80%)</th><th>Induction Pending (90%)</th><th>Filled (100%)</th><th>Potential Starts ($month_name_1)</th><th>Potential Starts ($month_name_2)</th><th>Potential Starts ($month_name_3)</th>
			</thead>
		</tr>
		<tbody>
			<tr>
				<td>$column_value</td><td>$open</td><td>$selection</td><td>$ci</td><td>$op</td><td>$ip</td><td>$f</td><td>$ps</td><td>$ps1</td><td>$ps2</td>
			</tr>
		</tbody>
		</table>
HEREDOC;

		$k = array();
		$k[] = Array("" => "", "Open" => $open,"Selection" => $selection,"ClientInterview" => $ci,"OfferPending" => $op,"InductionPending" => $ip,"Filled" => $f);

		require_once('tpl_summary.php');
	}

	private function getResult(PDO $link, $month, $region, $brm, $sector, $employer)
	{
		if($region != '')
		{
			$sql = "SELECT (SELECT description FROM lookup_vacancy_regions WHERE id = $region) AS region, MONTH(date_expected_to_fill ),
					vacancies.* FROM vacancies WHERE date_expected_to_fill IS NOT NULL AND MONTH(date_expected_to_fill) = " . $month .
					" AND region = " . $region;
			//pre($sql);
		}
		elseif($brm != '')
		{
			$sql = "SELECT (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE  username = '" . $brm . "') AS brm, MONTH(date_expected_to_fill ),
					vacancies.* FROM vacancies WHERE date_expected_to_fill IS NOT NULL AND MONTH(date_expected_to_fill) = " . $month .
				" AND brm = '" . $brm . "'";
//			pre($sql);
		}
		elseif($sector != '')
		{
			$sql = "SELECT (SELECT description FROM lookup_vacancy_type WHERE id = '" . $sector . "') AS sector, MONTH(date_expected_to_fill ),
					vacancies.* FROM vacancies WHERE date_expected_to_fill IS NOT NULL AND MONTH(date_expected_to_fill) = " . $month .
				" AND type = '" . $sector . "'";
//			pre($sql);
		}
		elseif($employer != '')
		{
			$sql = "SELECT (SELECT legal_name FROM organisations WHERE id = '" . $employer . "') AS employer, MONTH(date_expected_to_fill ),
					vacancies.* FROM vacancies WHERE date_expected_to_fill IS NOT NULL AND MONTH(date_expected_to_fill) = " . $month .
				" AND employer_id = '" . $employer . "'";
//			pre($sql);
		}
		else
		{
			$sql = "SELECT MONTH(date_expected_to_fill ), vacancies.* FROM vacancies WHERE date_expected_to_fill IS NOT NULL AND MONTH(date_expected_to_fill) = " . $month;
		}

		$r = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$open = 0;
		$selection = 0;
		$ci = 0;
		$op = 0;
		$ip = 0;
		$f = 0;
		$ps = 0;

		foreach($r as $row)
		{

			if($row['status'] == 1)
			{
				$open++;
			}
			elseif($row['status'] == 13)
			{
				$selection++;
			}
			elseif($row['status'] == 10 )
			{
				$ci++;
			}
			elseif($row['status'] == 11 )
			{
				$op++;
			}
			elseif($row['status'] == 12 )
			{
				$ip++;
			}
			elseif($row['status'] == 4 )
			{
				$f++;
			}
		}

		$ps = ($open*10/100)+($selection*30/100)+($ci*70/100)+($op*80/100)+($ip*90/100)+($f*100/100);
		$result = array();
		$result[$month]['open'] = $open;
		$result[$month]['selection'] = $selection;
		$result[$month]['ci'] = $ci;
		$result[$month]['op'] = $op;
		$result[$month]['ip'] = $ip;
		$result[$month]['f'] = $f;
		$result[$month]['ps'] = $ps;

		return $result;
	}

	private function getMonthName($month)
	{
		switch($month)
		{
			case 1:
				$month_name = 'January';
				break;
			case 2:
				$month_name = 'February';
				break;
			case 3:
				$month_name = 'March';
				break;
			case 4:
				$month_name = 'April';
				break;
			case 5:
				$month_name = 'May';
				break;
			case 6:
				$month_name = 'June';
				break;
			case 7:
				$month_name = 'July';
				break;
			case 8:
				$month_name = 'August';
				break;
			case 9:
				$month_name = 'September';
				break;
			case 10:
				$month_name = 'October';
				break;
			case 11:
				$month_name = 'November';
				break;
			case 12:
				$month_name = 'December';
				break;
		}
		return $month_name;
	}
}