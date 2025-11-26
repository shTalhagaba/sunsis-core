<?php
class ajax_sr_wpl implements IAction
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
		// Calculate Table for overall cohort table
		$table7 = array();
		$sql = "SELECT * FROM tbl_success_rates where programme_type='Workplace'" . $this->username_where_clause . " order by expected,actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table7[$row['expected']][$row['actual']]))
					$table7[$row['expected']][$row['actual']]++;
				else
					$table7[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year7 = array();
			foreach($table7 as $key => $expected)
			{
				$year7[] = $key;
			}
			foreach($table7 as $key => $expected)
			{
				foreach($year7 as $y)
				{
					if(!isset($table7[$key][$y]))
						$table7[$key][$y] = 0;
				}
			}
		}

		// Calculate Table for overall achievers
		$table8 = array();
		$sql = "SELECT * FROM tbl_success_rates where programme_type='Workplace' and p_prog_status=1" . $this->username_where_clause . " order by expected,actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table8[$row['expected']][$row['actual']]))
					$table8[$row['expected']][$row['actual']]++;
				else
					$table8[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year8 = array();
			foreach($table8 as $key => $expected)
			{
				$year8[] = $key;
			}
			foreach($table8 as $key => $expected)
			{
				foreach($year8 as $y)
				{
					if(!isset($table8[$key][$y]))
						$table8[$key][$y] = 0;
				}
			}
		}


		// Calculate Table for Timely achievers
		$table9 = array();
		$sql = "SELECT * FROM tbl_success_rates where programme_type='Workplace' and p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90" . $this->username_where_clause . " order by expected,actual";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if(isset($table9[$row['expected']][$row['actual']]))
					$table9[$row['expected']][$row['actual']]++;
				else
					$table9[$row['expected']][$row['actual']] = 1;
			}

			// Creating the table by adding blank cells
			$year9 = array();
			foreach($table9 as $key => $expected)
			{
				$year9[] = $key;
			}
			foreach($table9 as $key => $expected)
			{
				foreach($year9 as $y)
				{
					if(!isset($table9[$key][$y]))
						$table9[$key][$y] = 0;
				}
			}
		}

		$timely_cohort = array();
		$overall_cohort = array();
		$timely_achievers = array();
		$overall_achievers = array();

		$cols = sizeof($year7);

		$html = "<h3>Cohort Identification Table</h3> <br> <table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach($year7 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th>";
		$html .= "<tr><th rowspan=$cols>Expected</th>";
		foreach($table7 as $key => $expected)
		{
			$html .= "<th>" . Date::getFiscal($key) . "</th>";
			$timely = 0;
			$overall = 0;
			foreach($year7 as $y)
			{
				$timely += $table7[$key][$y];
				$html.= "<td>" . $table7[$key][$y] . "</td>";
			}
			if(isset($table7[$key][NULL]))
			{
				$n='';
				$html .= "<td>" . $table7[$key][NULL] . "</td>";

				$timely += $table7[$key][NULL];
			}
			else
				$html .= "<td>0</td>";

			$timely_cohort[$key] = $timely;
			$html .= "<td>" . $timely . "</td>";

			// Calculation of Overall Cohort
			$overall = 0;
			foreach($table7 as $key2 => $expected2)
			{
				foreach($year7 as $y2)
				{
					if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
						$overall += $table7[$key2][$y2];
				}
			}

			$overall_cohort[$key] = $overall;
			$html .= "<td>" . $overall . "</td> </tr>";
		}


		$html .= "</tr></table>";

		$html .= "<br><br>";


// Overall Achievers
		$cols = sizeof($year8);
		$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
		foreach($year8 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
		foreach($table8 as $key => $expected)
		{
			$html .= "<th>" . Date::getFiscal($key)	. "</th>";
			$timely = 0;
			foreach($year8 as $y)
			{
				$timely += $table8[$key][$y];
				$html.= "<td>" . $table8[$key][$y] . "</td>";
			}

			// Calculation of Overall Cohort
			$overall = 0;
			foreach($table8 as $key2 => $expected2)
			{
				foreach($year8 as $y2)
				{
					if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
						$overall += $table8[$key2][$y2];
				}
			}

			$overall_achievers[$key] = $overall;
			$html .= "<td>" . $overall . "</td></tr>";
		}
		$html .= "</tr></table>";


		$html .= "<br><br>";

// Timely Achievers
		$cols = sizeof($year9);
		$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
		foreach($year9 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
		$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
		foreach($table9 as $key => $expected)
		{
			$html .= "<th>" . Date::getFiscal($key)	. "</th>";
			$timely = 0;
			foreach($year9 as $y)
			{
				$timely += $table9[$key][$y];
				$html.= "<td>" . $table9[$key][$y] . "</td>";
			}

			$timely_achievers[$key] = $timely;
			$html .= "<td>" . $timely . "</td></tr>";
		}
		$html .= "</tr></table>";

		$html .= "<br><br>";

// Over all Success Rate
		$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
		foreach($year7 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
// overall achievers
		$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
		foreach($year7 as $y)
		{
			if(isset($overall_achievers[$y]))
				$html.= "<td>" . $overall_achievers[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Starts</th>";
// overall leavers
		foreach($year7 as $y)
		{
			if(isset($overall_cohort[$y]))
				$html.= "<td>" . $overall_cohort[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach($year7 as $y)
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
		foreach($year7 as $y)
		{
			$html.= "<th>" . Date::getFiscal($y) . "</th>";
		}
// overall achievers
		$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
		foreach($year7 as $y)
		{
			if(isset($timely_achievers[$y]))
				$html.= "<td>" . $timely_achievers[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
		$html .= "</tr><tr><th>Starts</th>";
// overall leavers
		foreach($year7 as $y)
		{
			if(isset($timely_cohort[$y]))
				$html.= "<td>" . $timely_cohort[$y] . "</td>";
			else
				$html.= "<td>0</td>";
		}
// %
		$html .= "</tr><tr><th>Success Rate</th>";
		foreach($year7 as $y)
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