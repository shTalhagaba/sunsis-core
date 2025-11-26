<?php
class ajax_sr_apprenticeships implements IAction
{
	private $username_where_clause = NULL;
	public function execute(PDO $link)
	{
		$export = isset($_REQUEST['export'])?$_REQUEST['export']:'';
		//echo 'HERE';
		//exit;
		$this->username_where_clause = " AND tbl_success_rates.username = '" . $_SESSION['user']->username . "' ";
		if($export == 'pdf')
		{
			include("./MPDF57/mpdf.php");
			$mpdf=new mPDF('c');
			$mpdf->SetDisplayMode('fullpage');

			// LOAD a stylesheet
			$stylesheet = file_get_contents('./common.css');
			$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
			$mpdf->WriteHTML($this->generateSuccessRates($link));
			$mpdf->Output('SuccessRates.pdf', 'D');
			exit;
		}
		echo $this->generateSuccessRates($link);
	}

	private function generateSuccessRates(PDO $link)
	{
		$table = array();
		$table2 = array();
		$table3 = array();
		$years_expected = DAO::getSingleColumn($link, "SELECT DISTINCT expected FROM tbl_success_rates WHERE expected IS NOT NULL" . $this->username_where_clause . " ORDER BY expected");
		$years_actual = DAO::getSingleColumn($link, "SELECT DISTINCT actual FROM tbl_success_rates WHERE actual IS NOT NULL" . $this->username_where_clause . " ORDER BY expected");
		$years = array_merge($years_expected, $years_actual);
		$years = array_unique($years, SORT_STRING);
		sort($years);
		foreach($years as $y)
		{
			$table[$y][NULL] = 0;
			$table2[$y][NULL] = 0;
			$table3[$y][NULL] = 0;
		}

		// Calculate Table for overall cohort table
		$sql = "SELECT * FROM tbl_success_rates WHERE programme_type='Apprenticeship'"  . $this->username_where_clause . " ORDER BY expected, actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table[$row['expected']][$row['actual']]))
					$table[$row['expected']][$row['actual']]++;
				else
					$table[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year = array();
			foreach($table as $key => $expected)
			{
				$year[] = $key;
			}

			foreach($table as $key => $expected)
			{
				foreach($year as $y)
				{
					if(!isset($table[$key][$y]))
						$table[$key][$y] = 0;
				}
			}
		}

		// Calculate Table for overall achievers
		$sql = "SELECT * FROM tbl_success_rates WHERE programme_type='Apprenticeship' AND p_prog_status=1"  . $this->username_where_clause . "ORDER BY expected, actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table2[$row['expected']][$row['actual']]))
					$table2[$row['expected']][$row['actual']]++;
				else
					$table2[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year2 = array();
			foreach($table2 as $key => $expected)
			{
				$year2[] = $key;
			}
			foreach($table2 as $key => $expected)
			{
				foreach($year2 as $y)
				{
					if(!isset($table2[$key][$y]))
						$table2[$key][$y] = 0;
				}
			}
		}

		// Calculate Table for Timely achievers
		$sql = "SELECT * FROM tbl_success_rates WHERE programme_type='Apprenticeship' AND p_prog_status=1 AND DATEDIFF(actual_end_date, planned_end_date) <= 90" . $this->username_where_clause . " ORDER BY expected, actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table3[$row['expected']][$row['actual']]))
					$table3[$row['expected']][$row['actual']]++;
				else
					$table3[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year3 = array();
			foreach($table3 as $key => $expected)
			{
				$year3[] = $key;
			}
			foreach($table3 as $key => $expected)
			{
				foreach($year3 as $y)
				{
					if(!isset($table3[$key][$y]))
						$table3[$key][$y] = 0;
				}
			}
		}

		$timely_cohort = array();
		$timely_in_year = array();
		$overall_cohort = array();
		$timely_achievers = array();
		$overall_achievers = array();

		$cols = sizeof($year) + 1;

		$html = "<div>";
		if(!isset($_REQUEST['export']))
			$html .= "<a href='{$_SERVER['REQUEST_URI']}&amp;export=pdf'><img title='Export to .CSV file' src='/images/pdf_export.gif' style='vertical-align:text-bottom' alt='Export to PDF' /></a>";
		$html .= "<h3>Cohort Identification Table</h3> <br> <table class='resultset'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach ($year as $y) {
			$html .= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th></tr>";

		$html .= "<tr><th rowspan=$cols>Expected</th>";
		foreach ($table as $key => $expected) {
			$html .= "<tr><th>" . Date::getFiscal($key) . "</th>";
			$timely = 0;
			$timely_leavers = 0;
			$overall = 0;
			foreach ($year as $y) {
				$timely += $table[$key][$y];
				$timely_leavers += $table[$key][$y];
				$html .= "<td>" . $table[$key][$y] . "</td>";
			}
			if (isset($table[$key][NULL])) {
				$n = '';
				$html .= "<td>" . $table[$key][NULL] . "</td>";
				$timely += $table[$key][NULL];
			} else
				$html .= "<td>0</td>";

			$timely_cohort[$key] = $timely;
			$timely_in_year[$key] = $timely_leavers;
			$html .= "<td>" . $timely . "</td>";

			// Calculation of Overall Cohort
			$overall = 0;
			foreach ($table as $key2 => $expected2) {
				foreach ($year as $y2) {
					if (($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
						$overall += $table[$key2][$y2];
				}
			}

			$overall_cohort[$key] = $overall;
			$html .= "<td>" . $overall . "</td> </tr>";
		}


		$html .= "</table>";

		$html .= "<br><br>";


		// Overall Achievers
		$cols = sizeof($year2);
		$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
		foreach ($year2 as $y) {
			$html .= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$firstTime = true;
		$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
		foreach ($table2 as $key => $expected) {
			if(!$firstTime)
				$html .= "<tr>";
			$html .= "<th>" . Date::getFiscal($key) . "</th>";
			$timely = 0;
			foreach ($year2 as $y) {
				$timely += $table2[$key][$y];
				$html .= "<td>" . $table2[$key][$y] . "</td>";
			}

			// Calculation of Overall Cohort
			$overall = 0;
			foreach ($table2 as $key2 => $expected2) {
				foreach ($year2 as $y2) {
					if (($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
						$overall += $table2[$key2][$y2];
				}
			}

			$firstTime = false;
			$overall_achievers[$key] = $overall;
			$html .= "<td>" . $overall . "</td></tr>";
		}
		$html .= "</table>";


		$html .= "<br><br>";

		// Timely Achievers
		$cols = sizeof($year3);
		$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
		foreach ($year3 as $y) {
			$html .= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$firstTime = true;
		$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
		foreach ($table3 as $key => $expected) {
			if(!$firstTime)
				$html .= '<tr>';
			$html .= "<th>" . Date::getFiscal($key) . "</th>";
			$timely = 0;
			foreach ($year3 as $y) {
				$timely += $table3[$key][$y];
				$html .= "<td>" . $table3[$key][$y] . "</td>";
			}

			$firstTime = false;
			$timely_achievers[$key] = $timely;
			$html .= "<td>" . $timely . "</td></tr>";
		}
		$html .= "</table>";

		$html .= "<br><br>";

		// Over all Success Rate
		$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach ($year as $y) {
			$html .= "<th>" . Date::getFiscal($y) . "</th>";
		}
		// overall achievers
		$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
		foreach ($year as $y) {
			if (isset($overall_achievers[$y]))
				$html .= "<td>" . $overall_achievers[$y] . "</td>";
			else
				$html .= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Leavers</th>";
		// overall leavers
		foreach ($year as $y) {
			if (isset($overall_cohort[$y]))
				$html .= "<td>" . $overall_cohort[$y] . "</td>";
			else
				$html .= "<td>0</td>";
		}
		// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach ($year as $y) {
			if (isset($overall_achievers[$y]) && $overall_cohort[$y] > 0)
				if (($overall_achievers[$y] / $overall_cohort[$y] * 100) >= 53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f", $overall_achievers[$y] / $overall_cohort[$y] * 100) . "%</td>";
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f", $overall_achievers[$y] / $overall_cohort[$y] * 100) . "%</td>";
			else
				$html .= "<td style='background-color: red'>0</td>";
		}
		$html .= "</tr></table>";

		$html .= "<br><br>";

		// Timely all Success Rate
		$html .= "<h3>Timely Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach ($year as $y) {
			$html .= "<th>" . Date::getFiscal($y) . "</th>";
		}
		// overall achievers
		$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
		foreach ($year as $y) {
			if (isset($timely_achievers[$y]))
				$html .= "<td>" . $timely_achievers[$y] . "</td>";
			else
				$html .= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Leavers</th>";
		// overall leavers
		foreach ($year as $y) {
			if (isset($timely_cohort[$y]))
				$html .= "<td>" . $timely_cohort[$y] . "</td>";
			else
				$html .= "<td>0</td>";
		}
		// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach ($year as $y) {
			if (isset($timely_achievers[$y]) && $timely_cohort[$y] > 0)
				if (($timely_achievers[$y] / $timely_cohort[$y] * 100) >= 53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f", $timely_achievers[$y] / $timely_cohort[$y] * 100) . "%</td>";
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f", $timely_achievers[$y] / $timely_cohort[$y] * 100) . "%</td>";
			else
				$html .= "<td style='background-color: red'>0</td>";
		}
		$html .= "</tr></table>";
		$html .= "<br><br>";

		// Timely Success Rate in year
		$html .= "<h3>Timely Success Rates (in-year)</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach ($year as $y) {
			$html .= "<th>" . Date::getFiscal($y) . "</th>";
		}
		// overall achievers
		$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
		foreach ($year as $y) {
			if (isset($timely_achievers[$y]))
				$html .= "<td>" . $timely_achievers[$y] . "</td>";
			else
				$html .= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Leavers</th>";
		// overall leavers
		foreach ($year as $y) {
			if (isset($timely_in_year[$y]))
				$html .= "<td>" . $timely_in_year[$y] . "</td>";
			else
				$html .= "<td>0</td>";
		}
		// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach ($year as $y) {
			if (isset($timely_achievers[$y]) && $timely_in_year[$y] > 0)
				if (($timely_achievers[$y] / $timely_in_year[$y] * 100) >= 53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f", $timely_achievers[$y] / $timely_in_year[$y] * 100) . "%</td>";
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f", $timely_achievers[$y] / $timely_in_year[$y] * 100) . "%</td>";
			else
				$html .= "<td style='background-color: red'>0</td>";
		}
		$html .= "</tr></table>";
		$html .= "<br><br></div>";

		return $html;
	}
}