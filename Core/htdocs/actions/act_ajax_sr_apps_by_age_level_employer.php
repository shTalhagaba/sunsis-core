<?php
class ajax_sr_apps_by_age_level_employer implements IAction
{
	private $username_where_clause = NULL;
	public function execute(PDO $link)
	{
//		echo 'HERE';
//		exit;
		$this->username_where_clause = " AND tbl_success_rates.username = '" . $_SESSION['user']->username . "' ";
		echo $this->generateSuccessRates($link);
	}

	private function generateSuccessRates(PDO $link)
	{
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");

		// Overall and Timely Success Rates by Age band and Level and Employer
		$html = "<h3>Success Rates by Age Band, Level and Employer</h3>";
		$age_band = array('16-18','19-24','25+','');
		$employers = DAO::getSingleColumn($link, "select distinct employer from tbl_success_rates where employer is not null " . $this->username_where_clause . " and programme_type='Apprenticeship' ORDER BY employer");
		$employers[] = "All employers";
		foreach($employers as $employer)
		{
			$html .= "<br><br><h4>$employer</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$employer</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
			foreach($age_band as $ab)
			{
				if($ab=='')
					$html .= "<tr><th colspan=2>Age Band All ages</th>";
				else
					$html .= "<tr><th colspan=2>Age Band $ab</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					$html .= "<th>" . Date::getFiscal($year) . "</th>";

				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					$html .= "<th>" . Date::getFiscal($year) . "</th>";

				$html .= "</tr><tr><th rowspan=4>Apprenticeship</th></tr>";
				$html .= "<tr><th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))*100)) . "%</td>";

				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
				}

				$html .= "<tr><th rowspan=4>Advanced Apprenticeship</th></tr>";
				$html .= "<tr><th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "<tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))*100)) . "%</td>";

				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", '', '', '', addslashes((string)$employer))*100)) . "%</td>";

				}

				if($ab=='')
					$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
				else
					$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
				$html .= "<tr><th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "<tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";
				}
			}
			$html .= "</tr></table>";
		}
		$html .= "</tr></table>";

		// Missing Employers
		$sql = "select * from tbl_success_rates left join contracts on contracts.id = tbl_success_rates.contract_id where employer is null or employer = '' " . $this->username_where_clause;
		$st = $link->query($sql);
		if($st)
		{
			$html .= "<br><br><br>Example of learners are not linked to the employers";
			$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";
			$ch = 0;
			while($row = $st->fetch())
			{
				$ch++;
				if($ch>10)
					break;
				$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] . "</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
			}
			$html .= "</table>";
		}


		echo $html;

	}
}