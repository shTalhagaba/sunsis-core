<?php
class ajax_sr_classroom implements IAction
{
	private  $username_where_clause = NULL;
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

		// Calculate Table for overall cohort table
		$table4 = array();
		$sql = "SELECT * FROM tbl_success_rates where programme_type='Classroom' " . $this->username_where_clause . " order by expected,actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table4[$row['expected']][$row['actual']]))
					$table4[$row['expected']][$row['actual']]++;
				else
					$table4[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year4 = array();
			foreach($table4 as $key => $expected)
			{
				$year4[] = $key;
			}
			foreach($table4 as $key => $expected)
			{
				foreach($year4 as $y)
				{
					if(!isset($table4[$key][$y]))
						$table4[$key][$y] = 0;
				}
			}
		}

		// Calculate Table for overall achievers
		$table5 = array();
		$sql = "SELECT * FROM tbl_success_rates where programme_type='Classroom' and p_prog_status=1" . $this->username_where_clause . " order by expected,actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table5[$row['expected']][$row['actual']]))
					$table5[$row['expected']][$row['actual']]++;
				else
					$table5[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year5 = array();
			foreach($table5 as $key => $expected)
			{
				$year5[] = $key;
			}
			foreach($table5 as $key => $expected)
			{
				foreach($year5 as $y)
				{
					if(!isset($table5[$key][$y]))
						$table5[$key][$y] = 0;
				}
			}
		}


		// Calculate Table for Timely achievers
		$table6 = array();
		$sql = "SELECT * FROM tbl_success_rates where programme_type='Classroom' and p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90 " . $this->username_where_clause . " order by expected,actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table6[$row['expected']][$row['actual']]))
					$table6[$row['expected']][$row['actual']]++;
				else
					$table6[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year6 = array();
			foreach($table6 as $key => $expected)
			{
				$year6[] = $key;
			}
			foreach($table6 as $key => $expected)
			{
				foreach($year6 as $y)
				{
					if(!isset($table6[$key][$y]))
						$table6[$key][$y] = 0;
				}
			}
		}


		$timely_cohort = array();
		$overall_cohort = array();
		$timely_achievers = array();
		$overall_achievers = array();

		$cols = sizeof($year4);



		$html = "<h3>Cohort Identification Table</h3> <br> <table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach($year4 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th>";
		$html .= "<tr><th rowspan=$cols>Expected</th>";
		foreach($table4 as $key => $expected)
		{
			$html .= "<th>" . Date::getFiscal($key) . "</th>";
			$timely = 0;
			$overall = 0;
			foreach($year4 as $y)
			{
				$timely += $table4[$key][$y];
				$html.= "<td>" . $table4[$key][$y] . "</td>";
			}
			if(isset($table4[$key][NULL]))
			{
				$n='';
				$html .= "<td>" . $table4[$key][NULL] . "</td>";

				$timely += $table4[$key][NULL];
			}
			else
				$html .= "<td>0</td>";

			$timely_cohort[$key] = $timely;
			$html .= "<td>" . $timely . "</td>";

			// Calculation of Overall Cohort
			$overall = 0;
			foreach($table4 as $key2 => $expected2)
			{
				foreach($year4 as $y2)
				{
					if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
						$overall += $table4[$key2][$y2];
				}
			}

			$overall_cohort[$key] = $overall;
			$html .= "<td>" . $overall . "</td> </tr>";
		}


		$html .= "</tr></table>";

		$html .= "<br><br>";


// Overall Achievers
		$cols = sizeof($year5);
		$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
		foreach($year5 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
		foreach($table5 as $key => $expected)
		{
			$html .= "<th>" . Date::getFiscal($key)	. "</th>";
			$timely = 0;
			foreach($year5 as $y)
			{
				$timely += $table5[$key][$y];
				$html.= "<td>" . $table5[$key][$y] . "</td>";
			}

			// Calculation of Overall Cohort
			$overall = 0;
			foreach($table5 as $key2 => $expected2)
			{
				foreach($year5 as $y2)
				{
					if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
						$overall += $table5[$key2][$y2];
				}
			}

			$overall_achievers[$key] = $overall;
			$html .= "<td>" . $overall . "</td></tr>";
		}
		$html .= "</tr></table>";


		$html .= "<br><br>";

// Timely Achievers
		$cols = sizeof($year6);
		$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
		foreach($year6 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
		foreach($table6 as $key => $expected)
		{
			$html .= "<th>" . Date::getFiscal($key)	. "</th>";
			$timely = 0;
			foreach($year6 as $y)
			{
				$timely += $table6[$key][$y];
				$html.= "<td>" . $table6[$key][$y] . "</td>";
			}

			$timely_achievers[$key] = $timely;
			$html .= "<td>" . $timely . "</td></tr>";
		}
		$html .= "</tr></table>";

		$html .= "<br><br>";

// Over all Success Rate
		$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach($year4 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
// overall achievers
		$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
		foreach($year4 as $y)
		{
			if(isset($overall_achievers[$y]))
				$html.= "<td>" . $overall_achievers[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Starts</th>";
// overall leavers
		foreach($year4 as $y)
		{
			if(isset($overall_cohort[$y]))
				$html.= "<td>" . $overall_cohort[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach($year4 as $y)
		{
			if(isset($overall_achievers[$y]) && $overall_cohort[$y]>0)
				if(($overall_achievers[$y]/$overall_cohort[$y]*100)>=53)
					$html.= "<td style='background-color: green'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
				else
					$html.= "<td style='background-color: red'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
			else
				$html.= "<td style='background-color: red'>0</td>";
		}
		$html .= "</tr></table>";

		$html .= "<br><br>";

// Over all Success Rate
		$html .= "<h3>Timely Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach($year4 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
// overall achievers
		$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
		foreach($year4 as $y)
		{
			if(isset($timely_achievers[$y]))
				$html.= "<td>" . $timely_achievers[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Starts</th>";
// overall leavers
		foreach($year4 as $y)
		{
			if(isset($timely_cohort[$y]))
				$html.= "<td>" . $timely_cohort[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach($year4 as $y)
		{
			if(isset($timely_achievers[$y]) && $timely_cohort[$y]>0)
				if(($timely_achievers[$y]/$timely_cohort[$y]*100)>=53)
					$html.= "<td style='background-color: green'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
				else
					$html.= "<td style='background-color: red'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
			else
				$html.= "<td style='background-color: red'>0</td>";
		}
		$html .= "</tr></table>";

		$html .= "<br><br>";

		$html .= "</tr></table>";
		echo $html;

	}
}