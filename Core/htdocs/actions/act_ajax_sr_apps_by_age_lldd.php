<?php
class ajax_sr_apps_by_age_lldd implements IAction
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
		$html = "<h3>Success Rates by Age Band and LLDD</h3> <br><table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
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

			$html .= "</tr><tr><th rowspan=4>LDD - Yes</th></tr>";
			$html .= "<tr><th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";
			}

			$html .= "<tr><th rowspan=4>LDD - No</th></tr>";
			$html .= "<tr><th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			$html .= "<tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";

			}

			$html .= "<tr><th rowspan=4>LDD - Unknown</th></tr>";
			$html .= "<tr><th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			$html .= "<tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
				$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
			}
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";

			}
		}

		$html .= "</tr></table>";

		echo $html;

	}
}