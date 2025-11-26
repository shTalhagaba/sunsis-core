<?php
class ajax_sr_apps_by_age_level_contractor_provider implements IAction
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

		// Overall and Timely Success Rates by Age band and Level and contractor
		$html = "<h3>Success Rates by Level, Provider & Contractor</h3>";
		$age_band = DAO::getSingleColumn($link, "select distinct provider from tbl_success_rates where provider is not null and provider!= '' and programme_type='Apprenticeship'" . $this->username_where_clause);
		$age_band[] = '';
		$contractors = DAO::getSingleColumn($link, "select distinct contractor from tbl_success_rates where contractor is not null and contractor!= '' and programme_type='Apprenticeship'" . $this->username_where_clause . " ORDER BY contractor ");
		$contractors[] = "All contractors";
		foreach($contractors as $contractor)
		{
			$html .= "<br><br><h4>$contractor</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$contractor</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
			foreach($age_band as $ab)
			{
				if($ab=='')
					$html .= "<tr><th colspan=2>All Providers</th>";
				else
					$html .= "<tr><th colspan=2>Provider $ab</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					$html .= "<th>" . Date::getFiscal($year) . "</th>";

				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					$html .= "<th>" . Date::getFiscal($year) . "</th>";

				$html .= "</tr><tr><th rowspan=4>Apprenticeship</th></tr>";
				$html .= "<tr><th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";

				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "3", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
				}

				$html .= "<tr><th rowspan=4>Advanced Apprenticeship</th></tr>";
				$html .= "<tr><th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "<tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";

				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "2", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";

				}

				if($ab=='')
					$html .= "<tr><th rowspan=4>Total for all providers</th></tr>";
				else
					$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
				$html .= "<tr><th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "<tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
				}
			}
			$html .= "</tr></table>";
		}
		$html .= "</tr></table>";
		echo $html;

	}
}