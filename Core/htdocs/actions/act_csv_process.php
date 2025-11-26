<?php
foreach (glob("./lib/funding/*.php") as $filename)
{
	include_once $filename;
}
class csv_process implements IAction
{
	private $monthsArray;
	private $amount1 = 0;
	private $amount2 = 0;

	public function execute(PDO $link)
	{
		$_SESSION['bc']->add($link, "do.php?_action=csv_process", "CSV Process");

		$report1 = "";
		$report2 = "";

		//if(isset($_REQUEST["filename"]))
		if(isset($_REQUEST["file"]) AND isset($_REQUEST['contract']) AND isset($_REQUEST['submission']))
		{
			$contract = $_REQUEST['contract'];
			$submission = $_REQUEST['submission'];

			$this->prepareMonthsArray();
			//$file_handle = fopen("upload/" . $_REQUEST["filename"], "r");
			$file_handle = fopen("lib/upload/" . $_REQUEST["file"], "r");

			$this->createTempTable($link, $this->monthsArray);

			//$data = new FundingPredictionPeriod($link, "18,19,20,16,14", 13, "", "", "", "W07");
			$data = new FundingPredictionPeriod($link, $contract, 13, "", "", "", $submission);
			$data = $data->get_learnerdata();

			$dataTable1InsertQuery = $this->generateInsertSQLQueryForTable1($data);
			DAO::execute($link, $dataTable1InsertQuery);

			$dataTable2InsertQuery = $this->generateInsertSQLQueryForTable2($file_handle, $this->monthsArray);
			DAO::execute($link, $dataTable2InsertQuery);

			fclose($file_handle);
			//unlink("upload/" . $_REQUEST["filename"]);

			$report1 = $this->renderSimilarRecords($link);
			$report2 = $this->renderDifferentRecords($link);

			$data = array();
			$labels = array();

			$data[0] = $this->amount1;
			$data[1] = $this->amount2;

			$labels[0] = "Sunesis Total Amount";
			$labels[1] = "PFR Total Amount";
		}
		//else
		require_once('tpl_csv_process.php');


	}//end function getInstance()


	private function prepareMonthsArray()
	{
		$this->monthsArray = array();

		//months array to pick up the columns from input CSV file
		$this->monthsArray[] = "August";
		$this->monthsArray[] = "September";
		$this->monthsArray[] = "October";
		$this->monthsArray[] = "November";
		$this->monthsArray[] = "December";
		$this->monthsArray[] = "January";
		$this->monthsArray[] = "February";
		$this->monthsArray[] = "March";
		$this->monthsArray[] = "April";
		$this->monthsArray[] = "May";
		$this->monthsArray[] = "June";
		$this->monthsArray[] = "July";

	}// end function prepareMonthsArray()


	private function generateInsertSQLQueryForTable1($data)
	{
		$dataTable1InsertQuery = "INSERT INTO dataTable1 VALUES ";
		for($i = 0; $i < count($data); $i++)
		{
			$dataTable1InsertQuery .= "(";
			$dataTable1InsertQuery .= "'" . $data[$i]['L03'] . "', ";
			$dataTable1InsertQuery .= "'" . trim($data[$i]['qualification_title']) . "', ";
			$dataTable1InsertQuery .= floatval($data[$i]['area_cost']) . ", ";
			$dataTable1InsertQuery .= floatval($data[$i]['disadvantage_uplift']) . ", ";
			$dataTable1InsertQuery .= "'" . $data[$i]['learner_start_date'] . "', ";

			$ii = 1;
			foreach($this->monthsArray as $month)
			{
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_OPP']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_ach']) . ", ";
				//$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_ach']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_bal']) . ", ";

				if($month == "July")
				{
					$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_total']) . "";

				}
				else
				{
					$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_total']) . " ,";
				}

				$ii++;
			}
			if($i == count($data) - 1)
				$dataTable1InsertQuery .= ")";
			else
				$dataTable1InsertQuery .= "),";

		}
		$dataTable1InsertQuery .= ";";
//echo $dataTable1InsertQuery; exit(0);
		return $dataTable1InsertQuery;
	}// end function generateInsertSQLQueryForTable1()

	private function generateInsertSQLQueryForTable2($file_handle, $monthsArray)
	{
		$indexArray = array(); // this array stores the indexes for each filed to be read from the input file
	//	$outputArray = array(); // the tw-dimensional output array which stores the data
		$firstLine = true; // to take care of first line to read the headers
		$found = false; // boolean variable which is set to true when the headers are found inside the file
	//	$i = 0; // counter for the output array

		$dataTable2InsertQuery = "INSERT INTO dataTable2 VALUES ";

		while (!feof($file_handle) )
		{

			//start reading the file line by line
			$line_of_text = fgetcsv($file_handle);

			if(!$found AND is_array($line_of_text) AND !in_array('Learner reference number', $line_of_text))
			{// continue skipping until found the headers
				continue;
			}
			$found = true;

			if($firstLine) // get the indexes for each field
			{
				for($z = 0; $z < count($line_of_text); $z++)
				{
					if($line_of_text[$z] == 'Learner reference number')
						$indexArray['learning_ref_number'] = $z;
					elseif($line_of_text[$z] == 'Learning aim reference')
						$indexArray['learning_aim_ref'] = $z;
					elseif($line_of_text[$z] == 'Area uplift')
						$indexArray['area_uplift'] = $z;
					elseif($line_of_text[$z] == 'Disadvantage uplift')
						$indexArray['disadvantage_uplift'] = $z;
					elseif($line_of_text[$z] == 'Learning start date')
						$indexArray['learner_start_date'] = $z;
					foreach($monthsArray as $month)
					{
						if($line_of_text[$z] == $month . ' On Programme Earned Cash')
							$indexArray[$month . '_prog_earned_cash'] = $z;
						elseif($line_of_text[$z] == $month . ' Aim Achievement')
							$indexArray[$month . '_aim_achievement'] = $z;
						//elseif($line_of_text[$z] == $month . ' Total Achievement Earned Cash')
							//$indexArray[$month . '_total_earned_cash'] = $z;
						elseif($line_of_text[$z] == $month . ' Balancing Payment Earned Cash')
							$indexArray[$month . '_bal_earned_cash'] = $z;
						//elseif($line_of_text[$z] == $month . ' ALS Earned Cash')
							//$indexArray[$month . '_als_earned_cash'] = $z;
					}
				}



				// Error Checking to verify that all the required fields are there in the input file
				if(count(array_keys($indexArray)) != 41)
				{
					$missingFields = array();
					if(!array_key_exists('learning_ref_number', $indexArray))
						$missingFields[] = 'Learning Reference Number';
					if(!array_key_exists('learning_aim_ref', $indexArray))
						$missingFields[] = 'Learning Aim Reference';
					if(!array_key_exists('area_uplift', $indexArray))
						$missingFields[] = 'Area Uplift';
					if(!array_key_exists('disadvantage_uplift', $indexArray))
						$missingFields[] = 'Disadvantage Uplift';
					if(!array_key_exists('learner_start_date', $indexArray))
						$missingFields[] = 'Learning Start Date';
					foreach($monthsArray as $month)
					{
						if(!array_key_exists($month . '_prog_earned_cash', $indexArray))
							$missingFields[] = $month . ' On Programme Earned Case';
						if(!array_key_exists($month . '_aim_achievement', $indexArray))
							$missingFields[] = $month . ' Aim Achievement';
						//if(!array_key_exists($month . '_total_earned_cash', $indexArray))
							//$missingFields[] = $month . ' Total Achievement Earned Cash';
						if(!array_key_exists($month . '_bal_earned_cash', $indexArray))
							$missingFields[] = $month . ' Balancing Payment Earned Case';
						//if(!array_key_exists($month . '_als_earned_cash', $indexArray))
							//$missingFields[] = $month . ' ALS Earned Case';

					}

					echo "<br>Error: The input file misses following required fields<br>";
					foreach($missingFields as $missingField)
						echo "'{$missingField}'<br>";
					exit(0);
				}
				$firstLine = false;
			}

			try
			{
				$dataTable2InsertQuery .= "(";
				//start filling the output array with data from the input file
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['learning_ref_number']] . "', ";
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['learning_aim_ref']] . "', ";
				$dataTable2InsertQuery .= floatval($line_of_text[$indexArray['area_uplift']]) . ", ";
				$dataTable2InsertQuery .= floatval($line_of_text[$indexArray['disadvantage_uplift']]) . ", ";
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['learner_start_date']] . "', ";
				foreach($monthsArray as $month)
				{
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_prog_earned_cash']] )) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_aim_achievement']])) . ", ";
					//$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_total_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_bal_earned_cash']])) . ", ";
					//$outputArray[$i][$month . '_als_earned_cash'] = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_als_earned_cash']]);

					$value1 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_prog_earned_cash']]);
					$value2 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_aim_achievement']]);
					$value3 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_bal_earned_cash']]);
					if($month == "July")
					{
						$dataTable2InsertQuery .= floatval($value1) + floatval($value2) + floatval($value3);
					}
					else
					{
						$dataTable2InsertQuery .= floatval($value1) + floatval($value2) + floatval($value3) . ", ";

					}
				}
			}
			catch(Exception $e)
			{
				echo "Error: " . $e->getMessage();
				//echo "<br> The input line: " . var_dump($line_of_text);
				exit(0);
			}

			$dataTable2InsertQuery .= "),";

		//	$i++;

		}
		$dataTable2InsertQuery = substr($dataTable2InsertQuery, 0, -1);
		$dataTable2InsertQuery .= ";";
//echo $dataTable2InsertQuery;exit(0);
		return $dataTable2InsertQuery;

	}// end function generateInsertSQLQueryForTable2()


	private function uploadFile($_FILES)
	{
		if(isset($_FILES["file"]))
		{
			if ($_FILES["file"]["error"] > 0) // if errors while uploading
			{
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				$mimes = array('application/vnd.ms-excel','text/csv');

				//accept the CSV files only

				if(in_array($_FILES['file']['type'],$mimes))
				{
					$pathParts = pathinfo($_FILES["file"]["name"]);
					$newName = $pathParts['filename'] . '_'.time().'.'.$pathParts['extension'];

					//upload the file
					move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $newName);

					//var_dump($newName);
					$file_handle = fopen("upload/".$newName, "r");

				}
				else
				{
					die("Please provide the CSV file only");
				}
			}
		}
		return $file_handle;
	}// end function uploadFile()

	public function renderDifferentRecords(PDO $link)
	{

		 $sql = <<<HEREDOC
SELECT t1.*, t2.* FROM datatable1 t1 INNER JOIN datatable2 t2
ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2
AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
AND t1.learner_start_date_t1 = t2.learner_start_date_t2
AND
(
t1.area_uplift_t1 != t2.area_uplift_t2
OR t1.disadvantage_uplift_t1 != t2.disadvantage_uplift_t2
HEREDOC;
		foreach($this->monthsArray as $month)
		{
			$sql .= " OR FLOOR(t1.{$month}_prog_earned_cash_t1) != FLOOR(t2.{$month}_prog_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_aim_achievement_t1) != FLOOR(t2.{$month}_aim_achievement_t2) ";
			//$sql .= " OR t1.{$month}_total_earned_cash_t1 != t2.{$month}_total_earned_cash_t2 ";
			$sql .= " OR FLOOR(t1.{$month}_bal_earned_cash_t1) != FLOOR(t2.{$month}_bal_earned_cash_t2) ";
			$sql .= " OR FLOOR(ROUND(t1.{$month}_total_t1, 1)) != FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
		}
		$sql.= <<<HEREDOC
)

HEREDOC;

		//echo $sql;exit(0);
		$st = $link->query($sql);
		$this->discrepancies = $st->rowCount();
		$report = "";
		if($st)
		{
			$report = '<div><table class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th>&nbsp;</th><th>Learning Ref</th><th>Learning Aim</th><th>Area Uplift</th><th>Disadvantage Uplift</th>';
			foreach($this->monthsArray as $month)
			{
				/*
				$report .= '<th>' . $month . '_prog_earned_cash</th>';
				$report .= '<th>' . $month . '_aim_achievement</th>';
				//echo '<th>' . $month . '_total_earned_cash</th>';
				$report .= '<th>' . $month . '_bal_earned_cash</th>';
				$report .= '<th>' . $month . '_total</th>';
				*/
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Achievement</th>';
				//echo '<th>' . $month . '_total_earned_cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
			}
			$report .= '</tr></thead>';

			$report .= '<tbody>';
			$x=0;
			while($row = $st->fetch())
			{
				$x++;

				$color = ($x%2 == 0)? '#E6E6E6': '#FFFFFF';

				$report .= '<tr bgcolor="' . $color . '"><td align="left">Sunesis</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t1']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t1']) . "</td>";
				if($row['area_uplift_t1'] != $row['area_uplift_t2'])
					$report .= '<td align="left" bgcolor="#80FF00">' . HTML::cell($row['area_uplift_t1']) . "</td>";
				else
					$report .= '<td align="left">' . HTML::cell($row['area_uplift_t1']) . "</td>";
				if($row['disadvantage_uplift_t1'] != $row['disadvantage_uplift_t2'])
					$report .= '<td align="left" bgcolor="#80FF00">' . HTML::cell($row['disadvantage_uplift_t1']) . "</td>";
				else
					$report .= '<td align="left">' . HTML::cell($row['disadvantage_uplift_t1']) . "</td>";
				foreach($this->monthsArray as $month)
				{
					if($row[$month . '_prog_earned_cash_t1'] != $row[$month . '_prog_earned_cash_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . "</td>";
					if($row[$month . '_aim_achievement_t1'] != $row[$month . '_aim_achievement_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_aim_achievement_t1']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_aim_achievement_t1']) . "</td>";
					//if($row[$month . '_total_earned_cash_t1'] != $row[$month . '_total_earned_cash_t2'])
					//echo '<td align="left" bgcolor="#80FF00">' . HTML::cell($row[$month . '_total_earned_cash_t1']) . "</td>";
					//else
					//echo '<td align="left">' . HTML::cell($row[$month . '_total_earned_cash_t1']) . "</td>";
					if($row[$month . '_bal_earned_cash_t1'] != $row[$month . '_bal_earned_cash_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . "</td>";
					if($row[$month . '_total_t1'] != $row[$month . '_total_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_total_t1']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_total_t1']) . "</td>";
					$this->amount1 = $this->amount1 + floatval($row[$month . '_total_t1']);
				}//end foreach

				$report .= "</tr>";
				$report .= '<tr bgcolor="' . $color . '"><td align="left">CSV</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t2']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t2']) . "</td>";
				if($row['area_uplift_t1'] != $row['area_uplift_t2'])
					$report .= '<td align="left" bgcolor="#80FF00">' . HTML::cell($row['area_uplift_t2']) . "</td>";
				else
					$report .= '<td align="left">' . HTML::cell($row['area_uplift_t2']) . "</td>";
				if($row['disadvantage_uplift_t1'] != $row['disadvantage_uplift_t2'])
					$report .= '<td align="left" bgcolor="#80FF00">' . HTML::cell($row['disadvantage_uplift_t2']) . "</td>";
				else
					$report .= '<td align="left">' . HTML::cell($row['disadvantage_uplift_t2']) . "</td>";
				foreach($this->monthsArray as $month)
				{
					if($row[$month . '_prog_earned_cash_t1'] != $row[$month . '_prog_earned_cash_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . "</td>";
					if($row[$month . '_aim_achievement_t1'] != $row[$month . '_aim_achievement_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_aim_achievement_t2']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_aim_achievement_t2']) . "</td>";
					//if($row[$month . '_total_earned_cash_t1'] != $row[$month . '_total_earned_cash_t2'])
					//echo '<td align="left" bgcolor="#80FF00">' . HTML::cell($row[$month . '_total_earned_cash_t2']) . "</td>";
					//else
					//echo '<td align="left">' . HTML::cell($row[$month . '_total_earned_cash_t2']) . "</td>";
					if($row[$month . '_bal_earned_cash_t1'] != $row[$month . '_bal_earned_cash_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . "</td>";
					if($row[$month . '_total_t1'] != $row[$month . '_total_t2'])
						$report .= '<td align="left" bgcolor="#80FF00" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_total_t2']) . "</td>";
					else
						$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_total_t2']) . "</td>";
					$this->amount2 = $this->amount2 + floatval($row[$month . '_total_t2']);
				}//end foreach

				$report .= "</tr>";
			}//end while
			$report .= '</tbody></table></div align="center">';

		}
		return $report;
	}// end function renderDifferentRecords()




	public function renderSimilarRecords(PDO $link)
	{

		$sql = <<<HEREDOC
SELECT t1.*, t2.* FROM datatable1 t1 INNER JOIN datatable2 t2
ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2
AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
AND t1.learner_start_date_t1 = t2.learner_start_date_t2
AND
(
t1.area_uplift_t1 = t2.area_uplift_t2
AND t1.disadvantage_uplift_t1 = t2.disadvantage_uplift_t2
HEREDOC;
		foreach($this->monthsArray as $month)
		{
			$sql .= " AND FLOOR(t1.{$month}_prog_earned_cash_t1) = FLOOR(t2.{$month}_prog_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_aim_achievement_t1) = FLOOR(t2.{$month}_aim_achievement_t2) ";
			//$sql .= " OR t1.{$month}_total_earned_cash_t1 != t2.{$month}_total_earned_cash_t2 ";
			$sql .= " AND FLOOR(t1.{$month}_bal_earned_cash_t1) = FLOOR(t2.{$month}_bal_earned_cash_t2) ";
			$sql .= " AND FLOOR(ROUND(t1.{$month}_total_t1, 1)) = FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
		}
		$sql.= <<<HEREDOC
)

HEREDOC;


		//echo $sql;exit(0);
		$st = $link->query($sql);
		$this->similarRecords = $st->rowCount();
		$report = "";
		if($st)
		{
			$report = '<div><table class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th >&nbsp;</th><th>Learning Ref</th><th>Learning Aim</th><th>Area Uplift</th><th>Disadvantage Uplift</th>';
			foreach($this->monthsArray as $month)
			{
				/*
				$report .= '<th>' . $month . '_prog_earned_cash</th>';
				$report .= '<th>' . $month . '_aim_achievement</th>';
				//echo '<th>' . $month . '_total_earned_cash</th>';
				$report .= '<th>' . $month . '_bal_earned_cash</th>';
				$report .= '<th>' . $month . '_total</th>';
				*/
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Achievement</th>';
				//echo '<th>' . $month . '_total_earned_cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
			}
			$report .= '</tr></thead>';

			$report .= '<tbody>';
			$x=0;
			while($row = $st->fetch())
			{
				$x++;

				$color = ($x%2 == 0)? '#E6E6E6': '#FFFFFF';

				$report .= '<tr bgcolor="' . $color . '"><td align="left" class="headcol">Sunesis</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t1']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t1']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['area_uplift_t1']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['disadvantage_uplift_t1']) . "</td>";
				foreach($this->monthsArray as $month)
				{
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . "</td>";
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_aim_achievement_t1']) . "</td>";
					//if($row[$month . '_total_earned_cash_t1'] != $row[$month . '_total_earned_cash_t2'])
					//echo '<td align="left" bgcolor="#80FF00">' . HTML::cell($row[$month . '_total_earned_cash_t1']) . "</td>";
					//else
					//echo '<td align="left">' . HTML::cell($row[$month . '_total_earned_cash_t1']) . "</td>";
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . "</td>";
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_total_t1']) . "</td>";
					$this->amount1 = $this->amount1 + floatval($row[$month . '_total_t1']);
				}//end foreach

				$report .= "</tr>";
				$report .= '<tr bgcolor="' . $color . '"><td align="left" class="headcol">CSV</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t2']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t2']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['area_uplift_t2']) . "</td>";
				$report .= '<td align="left">' . HTML::cell($row['disadvantage_uplift_t2']) . "</td>";
				foreach($this->monthsArray as $month)
				{
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . "</td>";
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_aim_achievement_t2']) . "</td>";
					//if($row[$month . '_total_earned_cash_t1'] != $row[$month . '_total_earned_cash_t2'])
					//echo '<td align="left" bgcolor="#80FF00">' . HTML::cell($row[$month . '_total_earned_cash_t2']) . "</td>";
					//else
					//echo '<td align="left">' . HTML::cell($row[$month . '_total_earned_cash_t2']) . "</td>";
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . "</td>";
					$report .= '<td align="left" title="Learning Ref: ' .  $row["learning_ref_number_t1"] . '">' . HTML::cell($row[$month . '_total_t2']) . "</td>";
					$this->amount2 = $this->amount2 + floatval($row[$month . '_total_t2']);
				}//end foreach

				$report .= "</tr>";
			}//end while
			$report .= '</tbody></table></div align="center">';

		}
		return $report;
	}// end function renderSimilarRecords()

	private function createTempTable(PDO $link, $monthsArray)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `dataTable1` (
  `learning_ref_number_t1` varchar(12) DEFAULT NULL,
  `learning_aim_ref_t1` varchar(12) DEFAULT NULL,
  `area_uplift_t1` FLOAT(8,4),
  `disadvantage_uplift_t1` FLOAT(8,4),
  `learner_start_date_t1` varchar(20),
HEREDOC;
		foreach($monthsArray as $month)
		{
			$sql .= "`{$month}_prog_earned_cash_t1` FLOAT(8,4), ";
			$sql .= "`{$month}_aim_achievement_t1` FLOAT(8,4) , ";
			//$sql .= "`{$month}_total_earned_cash_t1` FLOAT(8,4) , ";
			$sql .= "`{$month}_bal_earned_cash_t1` FLOAT(8,4) , ";
			if($month != "July")
				$sql .= "`{$month}_total_t1` FLOAT(8,4) , ";
			else
				$sql .= "`{$month}_total_t1` FLOAT(8,4)  ";
		}

		$sql.= <<<HEREDOC
) ENGINE 'MEMORY'
HEREDOC;

		DAO::execute($link, $sql);
//echo $sql; exit(0);
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `dataTable2` (
  `learning_ref_number_t2` varchar(25) DEFAULT NULL,
  `learning_aim_ref_t2` varchar(25) DEFAULT NULL,
  `area_uplift_t2` FLOAT(8,4),
  `disadvantage_uplift_t2` FLOAT(8,4),
  `learner_start_date_t2` varchar(20),
HEREDOC;
		foreach($monthsArray as $month)
		{
			$sql .= "`{$month}_prog_earned_cash_t2` FLOAT(8,4) , ";
			$sql .= "`{$month}_aim_achievement_t2` FLOAT(8,4) , ";
			//$sql .= "`{$month}_total_earned_cash_t2` FLOAT(8,4) , ";
			$sql .= "`{$month}_bal_earned_cash_t2` FLOAT(8,4) , ";
			if($month != "July")
				$sql .= "`{$month}_total_t2` FLOAT(8,4) , ";
			else
				$sql .= "`{$month}_total_t2` FLOAT(8,4)  ";
		}
		$sql.= <<<HEREDOC
) ENGINE 'MEMORY'
HEREDOC;
//echo $sql;exit(0);
		DAO::execute($link, $sql);

	}// end function createTempTable()

}// end class
?>