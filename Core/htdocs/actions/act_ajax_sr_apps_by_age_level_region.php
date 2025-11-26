<?php
class ajax_sr_apps_by_age_level_region implements IAction
{
	private $username_where_clause = NULL;
	public function execute(PDO $link)
	{
		//echo 'HERE';
		//exit;
		$this->username_where_clause = " AND tbl_success_rates.username = '" . $_SESSION['user']->username . "' ";
		echo $this->generateSuccessRates($link);
	}

	private function generateSuccessRates(PDO $link)
	{
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");

		// Overall and Timely Success Rates by Age band and Level and Government Office Region
		$html = "<h3>Success Rates by Age Band, Level and Government Office Region</h3>";
		$age_band = array('16-18','19-24','25+','');
		$regions = DAO::getSingleColumn($link, "SELECT DISTINCT region FROM tbl_success_rates WHERE region IS NOT NULL " . $this->username_where_clause . " AND programme_type='Apprenticeship' ORDER BY region");
		$regions[] = "All regions";
		foreach($regions as $region)
		{
			$html .= "<br><br><h4>$region</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$region</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
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
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "3", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "3", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "3", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "3", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", $region)==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", $region)*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", $region)*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", $region)*100)) . "%</td>";

				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", $region)==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", $region)*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", $region)*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "3", $region)*100)) . "%</td>";
				}

				$html .= "<tr><th rowspan=3>Advanced Apprenticeship</th>";
				$html .= "<th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "2", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "2", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "<tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "2", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "2", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", $region)==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", $region)*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", $region)*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", $region)*100)) . "%</td>";

				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", $region)==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", $region)*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", $region)*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "2", $region)*100)) . "%</td>";

				}

				if($ab=='')
					$html .= "<tr><th rowspan=3>Total for all ages</th>";
				else
					$html .= "<tr><th rowspan=3>Total for $ab</th>";
				$html .= "<th>Achievers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "<tr><th>Leavers</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					list($n, $detail) = SuccessRates::getTimelyLeaverExport($link, $year, "Apprenticeship", $ab, "", $region);
					$html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
				}
				$html .= "</tr><tr><th>Success Rate</th>";
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getOverallAchievers($link, $year, "Apprenticeship", $ab, "", $region)/SuccessRates::getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";
				}
				for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				{
					if(SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", $region)==0)
						$html .= "<td>" . "</td>";
					else
						if((SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)>=53)
							$html .= "<td style='background-color: green'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";
						else
							$html .= "<td style='background-color: red'>" . sprintf("%.2f",(SuccessRates::getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", $region)/SuccessRates::getTimelyLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";
				}
			}
			$html .= "</tr></table>";
		}


		// Invalid postcode
		$sql = "select * from tbl_success_rates left join contracts on contracts.id = tbl_success_rates.contract_id where region is null " . $this->username_where_clause;
		$st = $link->query($sql);
		if($st)
		{
			$html .= "<br><br><br>Following learners are not linked to the region possibly due to invalid or missing postcode";
			$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";

			$local_authority_form = '';
			if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)  {
				$local_authority = DAO::getResultset($link,"SELECT DISTINCT(CONCAT(TRIM(central.lookup_postcode_la.local_authority), ' - ', central.lookup_la_gor.government_region)) AS la FROM central.lookup_postcode_la, central.lookup_la_gor WHERE central.lookup_postcode_la.local_authority = central.lookup_la_gor.local_authority ORDER BY central.lookup_postcode_la.local_authority");
			}
			$ch = 0;
			while($row = $st->fetch())
			{
				$ch++;
				if($ch>10)
					break;
				if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)  {
					$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "&nbsp;|&nbsp;<a href='http://local.direct.gov.uk/LDGRedirect/LocationSearch.do?searchtype=1&LGSL=&LGIL=&Style=&formsub=t&requestType=locator&mode=1.1&text=".$row['postcode']."' target='_blank' >Direct Gov</a>&nbsp;|&nbsp;<a href='http://maps.google.co.uk/maps?f=q&hl=en&q=".urlencode($row['postcode'])."' target='_blank'>Google</a></td><td>";
					$html .= $row['local_authority'] ."</br>".$local_authority_form = HTML::select('la'.$row['l03'], $local_authority, '', true, true, true);
					$html .= "&nbsp;<a href='#' onclick=\"save_la('#la".$row['l03']."', '".$row['postcode']."');\" >update &raquo;</a></td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
				}
				else {
					$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] ."</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
				}
			}
			$html .= "</table>";
		}

		return $html;
	}
}