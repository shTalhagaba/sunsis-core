<?php
class ajax_sr_apps_by_age_band_level implements IAction
{
	public function execute(PDO $link)
	{
//		echo 'HERE';
//		exit;
		echo $this->generateSuccessRates($link);
	}

	private function generateSuccessRates(PDO $link)
	{
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");

		// Overall and Timely Success Rates by Age band and Level
		$html = "<h3>Success Rates by Age Band and Level</h3> <br><table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";

		$age_band = array('16-18','19-24','25+','');

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

			$html .= "</tr><tr><th rowspan=3>Apprenticeship</th>";
			$html .= "<th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "3");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "3");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "3");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "3");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3")*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3")*100)) . "%</td>";
			}

			$html .= "<tr><th rowspan=3>Advanced Apprenticeship</th>";
			$html .= "<th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "2");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "2");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "<tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "2");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "2");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2")*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2")*100)) . "%</td>";

			}

			$html .= "<tr><th rowspan=3>Higher Apprenticeship</th>";
			$html .= "<th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "20");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "20");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "<tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "20");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "20");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "20")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "20")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "20")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "20")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "20")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "20")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "20")*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "20")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "20")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "20")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "20")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "20")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "20")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "20")*100)) . "%</td>";

			}


			if($ab=='')
				$html .= "<tr><th rowspan=3>Total for all ages</th>";
			else
				$html .= "<tr><th rowspan=3>Total for $ab</th>";
			$html .= "<th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "<tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "");
				$html .= "<td><a href=\"javascript:expor('" . $detail . "');\">" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
			}
		}

		$html .= "</tr></table>";

		return $html;
	}


}