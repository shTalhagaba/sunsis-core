<?php
class ajax_sr_apps_by_age_level_ssa implements IAction
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

		// Overall and Timely Success Rates by Age band and Level and Government Office Region
		$html = "<h3>Success Rates by Age Band, Level, Sector Subject Area</h3>";
		$age_band = array('16-18','19-24','25+','');
		$ssas = DAO::getSingleColumn($link, "select distinct concat(ssa1,'<br>',ssa2) from tbl_success_rates where programme_type='Apprenticeship' " . $this->username_where_clause . " order by ssa1, ssa2");
		foreach($ssas as $ssa)
		{
			$sfcs = DAO::getSingleColumn($link, "select distinct ssa2 from tbl_success_rates where concat(ssa1,'<br>',ssa2) = '$ssa' and programme_type='Apprenticeship'" . $this->username_where_clause);
			foreach($sfcs as $sfc)
			{
				$html .= "<br><br><h4>$ssa</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$ssa</th><th colspan=4>Overall</th><th colspan=4>Timely (In-Year)</th></tr>";
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
						list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					$html .= "</tr><tr><th>Leavers</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					$html .= "</tr><tr><th>Success Rate</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)==0)
							$html .= "<td>" . "</td>";
						else
							if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)*100)>=53)
								$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)*100)) . "%</td>";
							else
								$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)*100)) . "%</td>";

					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						if(SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)==0)
							$html .= "<td>" . "</td>";
						else
							if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)*100)>=53)
								$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)*100)) . "%</td>";
							else
								$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "", $ssa, $sfc)*100)) . "%</td>";
					}

					$html .= "<tr><th rowspan=4>Advanced Apprenticeship</th></tr>";
					$html .= "<tr><th>Achievers</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					$html .= "<tr><th>Leavers</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					$html .= "</tr><tr><th>Success Rate</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)==0)
							$html .= "<td>" . "</td>";
						else
							if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)*100)>=53)
								$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)*100)) . "%</td>";
							else
								$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)*100)) . "%</td>";

					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						if(SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)==0)
							$html .= "<td>" . "</td>";
						else
							if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)*100)>=53)
								$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)*100)) . "%</td>";
							else
								$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "", $ssa, $sfc)*100)) . "%</td>";

					}

					if($ab=='')
						$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
					else
						$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
					$html .= "<tr><th>Achievers</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					$html .= "<tr><th>Leavers</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						list($n, $detail) = SuccessRates::getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
						$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
					}
					$html .= "</tr><tr><th>Success Rate</th>";
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)==0)
							$html .= "<td>" . "</td>";
						else
							if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)>=53)
								$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";
							else
								$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";
					}
					for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
					{
						if(SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)==0)
							$html .= "<td>" . "</td>";
						else
							if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)>=53)
								$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";
							else
								$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/SuccessRates::getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";
					}
				}
				$html .= "</tr></table>";
			}
			$html .= "</tr></table>";
		}
		$html .= "</tr></table>";

		echo $html;

	}
}