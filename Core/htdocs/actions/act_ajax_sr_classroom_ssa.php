<?php
class ajax_sr_classroom_ssa implements IAction
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

		// Overall and Timely Success Rates by Age band and Level
		$html = "<h3>Success Rates by Sector Subject Area</h3> <br><table class='resultset' cellpadding='5'>";
		$ssas = DAO::getSingleColumn($link, "select distinct concat(ssa1,'<br>',ssa2) from tbl_success_rates where programme_type='Classroom'" . $this->username_where_clause);
		foreach($ssas as $ssa)
		{
			$html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
			$html .= "<tr><th colspan=2 style='text-align: left'>$ssa</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<th>" . Date::getFiscal($year) . "</th>";

			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<th>" . Date::getFiscal($year) . "</th>";

			$html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
			$html .= "<tr><th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<td>" . SuccessRates::getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa) . "</td>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<td>" . SuccessRates::getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa) . "</td>";
			$html .= "</tr><tr><th>Starts</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<td>" . SuccessRates::getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa) . "</td>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<td>" . SuccessRates::getTimelyLeaver($link, $year, "Classroom", "", "", "", $ssa) . "</td>";
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa)/SuccessRates::getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa)/SuccessRates::getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa)/SuccessRates::getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";

			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if(SuccessRates::getTimelyLeaver($link, $year, "Classroom", "", "", "", $ssa)==0)
					$html .= "<td>" . "</td>";
				else
					if((SuccessRates::getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa)/SuccessRates::getTimelyLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa)/SuccessRates::getTimelyLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa)/SuccessRates::getTimelyLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
			}
		}

		$html .= "</tr></table>";

		echo $html;

	}
}