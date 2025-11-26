<?php
class qar_esfa_data implements IAction
{

	public $current_contract_year = null;

	public function execute(PDO $link)
	{
		$age_band_filter = isset($_REQUEST['age_band'])?$_REQUEST['age_band']:'All age_band';
		$qar_type_filter = isset($_REQUEST['qar_type'])?$_REQUEST['qar_type']:'Overall';
		$level_filter = isset($_REQUEST['level'])?$_REQUEST['level']:'All level';
		$best_case_filter = isset($_REQUEST['best_case'])?$_REQUEST['best_case']:'Actual';
		$panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
		$tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'summary';
		if(isset($_REQUEST['client']))
			$_SESSION['user']->department = $_REQUEST['client'];

		if($_SESSION['user']->department=='vauxhall')
			$database="esfa_qars2";
		else
			$database="esfa_qars";


		if($age_band_filter=='19 ')
			$age_band_filter =  '19+';
		if($age_band_filter=='24 ')
			$age_band_filter =  '24+';


		$this->case_scenario = $best_case_filter;
		$this->level = $level_filter;

		$table = array();
		$table2 = array();
		$table3 = array();

		$years_expected = DAO::getSingleColumn($link, "SELECT distinct Expected_End_Year expected FROM $database WHERE Expected_End_Year IS NOT NULL");
		$years_actual = DAO::getSingleColumn($link, "SELECT distinct Actual_End_Year FROM $database WHERE Actual_End_Year IS NOT NULL");
		$years = array_merge($years_expected, $years_actual);
		$year = array_unique($years, SORT_STRING);
		sort($year);

		$start_index = sizeof($year)-5;
		if($start_index<0)
			$start_index =0;

		if($panel == 'getOverallSummary')
		{
			$data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
				$data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]) . "</th>";

			$data .= "</tr><tr><th colspan=2></th>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
				$data.= "<th>Cohort</th><th>QAR</th>";

			$data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Apprenticeships</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Apprenticeship";
				if($age_band_filter!='All age_band')
					$filters['age_band'] = $age_band_filter;
				$QAR = $this->getQAR($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
				if($QAR['OverallLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "</tr><tr><td rowspan=2 style='background: #add8e6'>Education & Training</td><td style='background: #ffb6c1; width:150px'>16-18</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "16-18";
				$QAR = $this->getQAR($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
				if($QAR['OverallLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "<tr><td style='background: #ffb6c1; width:150px'>19+</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "19+";
				$QAR = $this->getQAR($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
				if($QAR['OverallLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "</tr></tbody></table><br>";

			echo $data;
			exit;
		}

		if($panel == 'getRetentionSummary')
		{
			$data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
				$data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]) . "</th>";

			$data .= "</tr><tr><th colspan=2></th>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
				$data.= "<th>Cohort</th><th>Ret:</th>";

			$data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Apprenticeships</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Apprenticeship";
				if($age_band_filter!='All age_band')
					$filters['age_band'] = $age_band_filter;
				$QAR = $this->getRetention($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
				if($QAR['OverallLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "</tr><tr><td rowspan=2 style='background: #add8e6'>Education & Training</td><td style='background: #ffb6c1; width:150px'>16-18</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "16-18";
				$QAR = $this->getRetention($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
				if($QAR['OverallLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "<tr><td style='background: #ffb6c1; width:150px'>19+</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "19+";
				$QAR = $this->getRetention($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
				if($QAR['OverallLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "</tr></tbody></table><br>";

			echo $data;
			exit;
		}

		if($panel == 'getTimelySummary')
		{
			$data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
				$data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]) . "</th>";

			$data .= "</tr><tr><th colspan=2></th>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
				$data.= "<th>Cohort</th><th>QAR</th>";

			$data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Apprenticeships</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Apprenticeship";
				if($age_band_filter!='All age_band')
					$filters['age_band'] = $age_band_filter;
				$QAR = $this->getQAR($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
				if($QAR['TimelyLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "</tr><tr><td rowspan=2 style='background: #add8e6'>Education & Training</td><td style='background: #ffb6c1; width:150px'>16-18</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "16-18";
				$QAR = $this->getQAR($link,$filters);

				$data.= "<td><a href=javascript:expor('"  . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
				if($QAR['TimelyLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "<tr><td style='background: #ffb6c1; width:150px'>19+</td>";

			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters = Array();
				$filters['year'] = $year[$i];
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "19+";
				$QAR = $this->getQAR($link,$filters);

				$data.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
				if($QAR['TimelyLeaver'][0][0]>0)
					$data.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
				else
					$data.= "<td>0%</td>";
			}

			$data .= "</tr></tbody></table><br>";

			echo $data;
			exit;
		}

		if($panel == 'getLineChartOverallTrend')
		{
			$overall_trend = array();
			$overall_trend[0]['Years'] = $year;
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Apprenticeship";
				if($age_band_filter!='All age_band')
					$filters['age_band'] = $age_band_filter;
				$QAR = $this->getQAR($link,$filters);
				if($QAR['OverallLeaver'][0][0]>0)
					$overall_trend[1][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
				else
					$overall_trend[1][] = 0;
			}
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "16-18";
				$QAR = $this->getQAR($link,$filters);
				if($QAR['OverallLeaver'][0][0]>0)
					$overall_trend[2][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
				else
					$overall_trend[2][] = 0;
			}
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "19+";
				$QAR = $this->getQAR($link,$filters);
				if($QAR['OverallLeaver'][0][0]>0)
					$overall_trend[3][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
				else
					$overall_trend[3][] = 0;
			}
			echo(json_encode($overall_trend,JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'getLineChartOverallTrend')
		{
			$overall_trend = array();
			$overall_trend[0]['Years'] = $year;
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Apprenticeship";
				if($age_band_filter!='All age_band')
					$filters['age_band'] = $age_band_filter;
				$QAR = $this->getQAR($link,$filters);
				if($QAR['OverallLeaver'][0][0]>0)
					$overall_trend[1][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
				else
					$overall_trend[1][] = 0;
			}
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "16-18";
				$QAR = $this->getQAR($link,$filters);
				if($QAR['OverallLeaver'][0][0]>0)
					$overall_trend[2][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
				else
					$overall_trend[2][] = 0;
			}
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "19+";
				$QAR = $this->getQAR($link,$filters);
				if($QAR['OverallLeaver'][0][0]>0)
					$overall_trend[3][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
				else
					$overall_trend[3][] = 0;
			}
			echo(json_encode($overall_trend,JSON_NUMERIC_CHECK));
			exit;
		}


		if($panel == 'getLineChartTimelyTrend')
		{
			$timely_trend = array();
			$timely_trend[0]['Years'] = $year;
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Apprenticeship";
				if($age_band_filter!='All age_band')
					$filters['age_band'] = $age_band_filter;
				$QAR = $this->getQAR($link,$filters);
				if($QAR['TimelyLeaver'][0][0]>0)
					$timely_trend[1][] = (sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100));
				else
					$timely_trend[1][] = 0;
			}
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "16-18";
				$QAR = $this->getQAR($link,$filters);
				if($QAR['TimelyLeaver'][0][0]>0)
					$timely_trend[2][] = (sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100));
				else
					$timely_trend[2][] = 0;
			}
			foreach($year AS $y)
			{
				$filters = Array();
				$filters['year'] = $y;
				$filters['programme_type'] = "Education";
				$filters['age_band'] = "19+";
				$QAR = $this->getQAR($link,$filters);
				if($QAR['TimelyLeaver'][0][0]>0)
					$timely_trend[3][] = (sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100));
				else
					$timely_trend[3][] = 0;
			}
			echo(json_encode($timely_trend,JSON_NUMERIC_CHECK));
			exit;
		}
		if($panel == 'LearnerByGenderTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"gender",$filters,$qar_type_filter);
			echo $data;
			exit;
		}
		if($panel == 'LearnerByAgeBandTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"age_band",$filters,$qar_type_filter);
			echo $data;
			exit;
		}
		if($panel == 'LearnerByLLDDTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"lldd",$filters,$qar_type_filter);
			echo $data;
			exit;
		}
		if($panel == 'LearnerByEthnicityTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"ethnicity",$filters,$qar_type_filter);
			echo $data;
			exit;
		}
		if($panel == 'LearnerBySSATable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"ssa1",$filters,$qar_type_filter);
			echo $data;
			exit;
		}

		if($panel == 'LearnerBySSAChartApp')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"ssa1",$filters,$qar_type_filter,"Apprenticeship");
			echo $data;
			exit;
		}

		if($panel == 'LearnerBySSAChartEducation')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"ssa1",$filters,$qar_type_filter,"Education");
			echo $data;
			exit;
		}

		if($panel == 'LearnerByFrameworkTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"sfc",$filters,$qar_type_filter);
			echo $data;
			exit;
		}

		if($panel == 'LearnerByFrameworkChartApp')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"sfc",$filters,$qar_type_filter,"Apprenticeship");
			echo $data;
			exit;
		}

		if($panel == 'LearnerByAssessorTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"assessor",$filters,$qar_type_filter);
			echo $data;
			exit;
		}

		if($panel == 'LearnerByAssessorApp')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"assessor",$filters,$qar_type_filter,"Apprenticeship");
			echo $data;
			exit;
		}

		if($panel == 'LearnerByAssessorEducation')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"assessor",$filters,$qar_type_filter,"Education");
			echo $data;
			exit;
		}

		if($panel == 'LearnerByLevelTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getTable($link,$year,"level",$filters,$qar_type_filter);
			echo $data;
			exit;
		}

		if($panel == 'LearnerByLevelApp')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"level",$filters,$qar_type_filter,"Apprenticeship");
			echo $data;
			exit;
		}

		if($panel == 'LearnerByLevelEducation')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getChart($link,$year,"level",$filters,$qar_type_filter,"Education");
			echo $data;
			exit;
		}

		if($panel == 'RetentionByLevelTable')
		{
			$filters = Array();
			if($age_band_filter!='All age_band')
				$filters['age_band'] = $age_band_filter;
			$data = $this->getRetentionTable($link,$year,"level",$filters,$qar_type_filter);
			echo $data;
			exit;
		}

		if($panel == 'leavers')
		{
			$query = "SELECT Hybrid_End_Year AS `year` , COUNT(Hybrid_End_Year) AS leavers FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Hybrid_End_Year IS NOT NULL AND Hybrid_End_Year != 0 GROUP BY Hybrid_End_Year";
			$leavers = DAO::getResultset($link, $query);
			$result = Array();
			$n= Array();
			$c= Array();
			$cohort=Array();
			foreach($leavers as $leaver)
			{
				$n[] = $leaver[1];
				$c[] = $leaver[0];
				$year = $leaver[0];
				$coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM $database WHERE Hybrid_End_Year = '$year' AND Provision_Type = 'Apps'");
				$cohort[] = $coh;
			}
			$series = Array();
			$series['name'] = "Leavers";
			$series['data'] = $c;
			array_push($result,$series);
			$series['name'] = "Leavers";
			$series['data'] = $cohort;
			array_push($result,$series);
			$series['name'] = "Withdrawn";
			$series['data'] = $n;
			array_push($result,$series);
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'leaversbytrend')
		{
			$query = "SELECT Hybrid_End_Year AS `year` , COUNT(Hybrid_End_Year) AS leavers FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Hybrid_End_Year IS NOT NULL AND Hybrid_End_Year != 0 GROUP BY Hybrid_End_Year";
			$leavers = DAO::getResultset($link, $query);
			$result = Array();
			$trend= Array();
			$c= Array();
			foreach($leavers as $leaver)
			{
				$c[] = $leaver[0];
				$year = $leaver[0];
				$coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM $database WHERE Hybrid_End_Year = '$year' AND Provision_Type = 'Apps'");
				if($leaver[1]==0)
					$leaver[1]=1;
				$trend[] = round(($leaver[1]/$coh*100),2);
			}
			$series = Array();
			$series['name'] = "Leavers";
			$series['data'] = $c;
			array_push($result,$series);
			$series['name'] = "Percentage of withdrawn learners against leavers (Hybrid end-year)";
			$series['data'] = $trend;
			array_push($result,$series);
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'leaversbytrendactual')
		{
			$query = "SELECT Actual_End_Year AS `year` , COUNT(Actual_End_Year) AS leavers FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Actual_End_Year IS NOT NULL AND Actual_End_Year != 0 GROUP BY Actual_End_Year";
			$leavers = DAO::getResultset($link, $query);
			$result = Array();
			$trend= Array();
			$c= Array();
			foreach($leavers as $leaver)
			{
				$c[] = $leaver[0];
				$year = $leaver[0];
				$coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM $database WHERE Actual_End_Year = '$year'  AND Provision_Type = 'Apps'");
				if($leaver[1]==0)
					$leaver[1]=1;
				$trend[] = round(($leaver[1]/$coh*100),2);
			}
			$series = Array();
			$series['name'] = "Leavers";
			$series['data'] = $c;
			array_push($result,$series);
			$series['name'] = "Percentage of withdrawn learners against leavers (year learning ended)";
			$series['data'] = $trend;
			array_push($result,$series);
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'leaversbytrendonprogramme')
		{
			$query = "SELECT Actual_End_Year AS `year` , COUNT(Actual_End_Year) AS leavers FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Actual_End_Year IS NOT NULL AND Actual_End_Year != 0 GROUP BY Actual_End_Year";
			$leavers = DAO::getResultset($link, $query);
			$result = Array();
			$trend= Array();
			$c= Array();
			foreach($leavers as $leaver)
			{
				$c[] = $leaver[0];
				$year = $leaver[0];
				$year1 = $year+1;
				$start_date = $year."-08-01";
				$end_date = $year1."-07-31";
				$coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM $database WHERE Learning_Start_Date >= '$start_date' and (Learning_Actual_End_Date is null or Learning_Actual_End_Date<= '$end_date') AND Provision_Type = 'Apps'");
				if($leaver[1]==0)
					$leaver[1]=1;
				if($coh==0)
					$trend[] = round(($leaver[1]/1*100),2);
				else
					$trend[] = round(($leaver[1]/$coh*100),2);
			}
			$series = Array();
			$series['name'] = "Leavers";
			$series['data'] = $c;
			array_push($result,$series);
			$series['name'] = "Percentage of withdrawn learners against learners on programme (year learning ended)";
			$series['data'] = $trend;
			array_push($result,$series);
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'leaversbyreason')
		{
			$query = "SELECT Hybrid_End_Year, COUNT(0) AS data_error, COUNT(1) AS genuine FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps'  GROUP BY Hybrid_End_Year";
			$leavers = DAO::getResultset($link, $query);
			$result = Array();
			$year= Array();
			$error= Array();
			$genuine= Array();
			foreach($leavers as $leaver)
			{
				if($leaver[0]=='')
					continue;
				$year[] = $leaver[0];
				$error[] = $leaver[1];
				$genuine[] = $leaver[2];
			}
			$series = Array();
			$series['name'] = "Leavers";
			$series['data'] = $year;
			array_push($result,$series);
			$series['name'] = "Data Error";
			$series['data'] = $error;
			array_push($result,$series);
			$series['name'] = "Withdrawn";
			$series['data'] = $genuine;
			array_push($result,$series);
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'leaversbyimpact')
		{
			$query = "SELECT DISTINCT Hybrid_End_Year AS Impact_Year FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Hybrid_End_Year IS NOT NULL GROUP BY Hybrid_End_Year ;";
			$leavers = DAO::getResultset($link, $query);
			$result = Array();
			$year= Array();
			foreach($leavers as $leaver)
			{
				$year[] = $leaver[0];
			}
			$series = Array();
			$series['name'] = "Impact Year";
			$series['data'] = $year;
			array_push($result,$series);
			unset($series);
			$actual_query = "SELECT DISTINCT Actual_End_Year AS Impact_Year FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Actual_End_Year IS NOT NULL ORDER BY Actual_End_Year;";
			$actual_years = DAO::getResultset($link, $actual_query);
			foreach($actual_years as $actual_year)
			{
				$ay = $actual_year[0];
				$series['name'] = $ay;
				foreach($leavers as $leaver)
				{
					$hy = $leaver[0];
					$series['data'][] = DAO::getSingleValue($link, "select count(*) from $database where Achievement_Rate_Status not in (0,1) and Actual_End_Year='$ay' and Hybrid_End_Year='$hy'");
				}
				array_push($result,$series);
				unset($series);
			}
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		if($panel == 'leaversbyactual')
		{
			$query = "SELECT DISTINCT Actual_End_Year AS Impact_Year FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Actual_End_Year IS NOT NULL and Actual_End_Year <> '0' GROUP BY Actual_End_Year;";
			$actual_leavers = DAO::getResultset($link, $query);
			$result = Array();
			$year= Array();
			foreach($actual_leavers as $leaver)
			{
				$year[] = $leaver[0];
			}
			$series = Array();
			$series['name'] = "Actual Year";
			$series['data'] = $year;
			array_push($result,$series);
			unset($series);
			$hybrid_query = "SELECT DISTINCT Hybrid_End_Year AS Impact_Year FROM $database WHERE Achievement_Rate_Status NOT IN (0,1) AND Provision_Type = 'Apps' AND Hybrid_End_Year IS NOT NULL ORDER BY Hybrid_End_Year;";
			$hybrid_years = DAO::getResultset($link, $hybrid_query);
			foreach($hybrid_years as $hybrid_year)
			{
				$hy = $hybrid_year[0];
				$series['name'] = $hy;
				foreach($actual_leavers as $actual_leaver)
				{
					$ay = $actual_leaver[0];
					$series['data'][] = DAO::getSingleValue($link, "select count(*) from $database where Achievement_Rate_Status not in (0,1) and Actual_End_Year='$ay' and Hybrid_End_Year='$hy'");
				}
				array_push($result,$series);
				unset($series);
			}
			echo(json_encode($result, JSON_NUMERIC_CHECK));
			exit;
		}

		include_once('tpl_qar_esfa_data.php');

	}

	public function getTable($link, $year, $by, $filters, $qar_type_filter)
	{
		if($_SESSION['user']->department=='vauxhall')
			$database="esfa_qars2";
		else
			$database="esfa_qars";

		if($by=='lldd')
			$by="Learning_Difficulties";
		elseif($by=='gender')
			$by="Sex";
		elseif($by=='age_band')
			$by="Age_Group";
		elseif($by=='ssa1')
			$by="Sector_Subject_Area_Tier_1";
		elseif($by=='sfc')
			$by="Framework_Name";
		$data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><td colspan=2>Apprenticeships<span style="margin-left:10px; background: #90ee90">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>';
		$start_index = sizeof($year)-5;
		if($start_index<0)
			$start_index =0;
		for($i = $start_index; $i<=sizeof($year)-1; $i++)
			$data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]);

		$data .= '<tr><td colspan=2>Education & Training<span style="margin-left:10px; background: #ffb6c1">&nbsp;&nbsp;&nbsp;&nbsp;</span></td></th>';
		$data .= "</tr><tr><th colspan=2></th>";
		for($i = $start_index; $i<=sizeof($year)-1; $i++)
			$data.= "<th>Cohort</th><th>QAR</th>";
		if($by=='sfc')
		{
			$outer_query = "select distinct ssa1 from $database where ssa1 is not null and Provision_Type = 'Apps' order by ssa1";
			$ssa1_values = DAO::getSingleColumn($link, $outer_query);
			foreach($ssa1_values as $ssa1_value)
			{
				$cohort = Array();
				$rate = Array();
				$query = "select distinct " . $by . " from $database where " . $by . " is not null and Provision_Type = 'Apps' and ssa1 = '$ssa1_value' order by " . $by;
				$by_values = DAO::getSingleColumn($link, $query);
				$value_found = false;
				foreach($by_values as $value)
				{
					$display_level = $value;
					$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
					$value_found = false;
					for($i = $start_index; $i<=sizeof($year)-1; $i++)
					{
						$filters['year'] = $year[$i];
						$filters['programme_type'] = "Apprenticeship";
						$filters[$by] = $value;
						$QAR = $this->getQAR($link,$filters);
						if(!isset($rate[$i]))
							$rate[$i] = array();

						if($qar_type_filter=="Overall")
						{
							$row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
							if(isset($cohort[$i]))
								$cohort[$i]+= $QAR['OverallLeaver'][0][0];
							else
								$cohort[$i] = $QAR['OverallLeaver'][0][0];
							if($QAR['OverallLeaver'][0][0]>0)
							{    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
								$value_found = true;
							}
							else
								$row.= "<td>0%</td>";

							//SSA1 Rate
							if($QAR['OverallLeaver'][0][0]>0)
							{
								$rate[$i][]=sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100);
							}
						}
						else
						{
							$row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
							if($QAR['TimelyLeaver'][0][0]>0)
							{    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
								$value_found = true;
							}
							else
								$row.= "<td>0%</td>";

							if($QAR['TimelyLeaver'][0][0]>0)
							{
								$rate[$i][]=sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100);
							}


						}
					}
					if($value_found)
						$data.=$row;
				}

				// Total SSA1 Row
				if($value_found)
				{
					$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ee90ee;'>".$ssa1_value." (Group Total)</td>";
					for($i = $start_index; $i<=sizeof($year)-1; $i++)
					{
						$row.= isset($cohort[$i]) ? "<td>" . $cohort[$i] . "</td>" : "<td> - </td>";
						$n = (count($rate[$i])==0)?1:count($rate[$i]);
						$r = array_sum($rate[$i])/$n;
						$row.= "<td>" . sprintf("%.2f",$r) . "</td>";
					}
					$data.=$row;
				}

			}
		}
		else
		{
			if($by=='lldd')
				$by="Learning_Difficulties";
			elseif($by=='gender')
				$by="Sex";
			elseif($by=='age_band')
				$by="Age_Group";
			elseif($by=='ssa1')
				$by="Sector_Subject_Area_Tier_1";
			elseif($by=='assessor')
				$by="Delivery_Region";
			elseif($by=='level')
				$by="Programme_Type";
			$query = "select distinct " . $by . " from $database where " . $by . " is not null order by " . $by;
			$by_values = DAO::getSingleColumn($link, $query);
			foreach($by_values as $value)
			{
				if($value=='2')
					$display_level = "2 - Advanced Apprenticeship";
				elseif($value=='3')
					$display_level = "3 - Intermediate Apprenticeship";
				elseif($value=='20')
					$display_level = "20 - Higher Apprenticeship";
				elseif($value=='25')
					$display_level = "25 - Standard";
				else
					$display_level = $value;

				$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
				$value_found = false;
				for($i = $start_index; $i<=sizeof($year)-1; $i++)
				{
					$filters['year'] = $year[$i];
					$filters['programme_type'] = "Apprenticeship";
					$filters[$by] = $value;
					$QAR = $this->getQAR($link,$filters);

					if($qar_type_filter=="Overall")
					{
						$row.= "<td><a href=javascript:expor('"  . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
						if($QAR['OverallLeaver'][0][0]>0)
						{    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
					else
					{
						$row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" .  $QAR['TimelyLeaver'][0][0] . "</td>";
						if($QAR['TimelyLeaver'][0][0]>0)
						{    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
				}
				if($value_found)
					$data.=$row;
			}
		}
		if($by!='programme' and $by!='sfc')
			foreach($by_values as $value)
			{
				$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ffb6c1'>".$value."</td>";
				$value_found = false;
				for($i = $start_index; $i<=sizeof($year)-1; $i++)
				{
					$filters['year'] = $year[$i];
					$filters[$by] = $value;
					$filters['programme_type'] = "Education";
					$QAR = $this->getQAR($link,$filters);

					if($qar_type_filter=="Overall")
					{
						$row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
						if($QAR['OverallLeaver'][0][0]>0)
						{
							$row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
					else
					{
						$row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
						if($QAR['TimelyLeaver'][0][0]>0)
						{
							$row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
				}
				if($value_found)
					$data.=$row;
			}
		$data .= "</tr></tbody></table><br>";
		return $data;
	}

	public function getRetentionTable($link, $year, $by, $filters, $qar_type_filter)
	{
		if($_SESSION['user']->department=='vauxhall')
			$database="esfa_qars2";
		else
			$database="esfa_qars";

		$data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><td colspan=2>Apprenticeships<span style="margin-left:10px; background: #90ee90">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>';
		$start_index = sizeof($year)-5;
		if($start_index<0)
			$start_index =0;
		for($i = $start_index; $i<=sizeof($year)-1; $i++)
			$data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]);

		$data .= '<tr><td colspan=2>Education & Training<span style="margin-left:10px; background: #ffb6c1">&nbsp;&nbsp;&nbsp;&nbsp;</span></td></th>';
		$data .= "</tr><tr><th colspan=2></th>";
		for($i = $start_index; $i<=sizeof($year)-1; $i++)
			$data.= "<th>Cohort</th><th>Ret:</th>";
		if($by=='sfc')
		{
			$outer_query = "select distinct ssa1 from $database where ssa1 is not null and Provision_Type = 'Apps' order by ssa1";
			$ssa1_values = DAO::getSingleColumn($link, $outer_query);
			foreach($ssa1_values as $ssa1_value)
			{
				$cohort = Array();
				$rate = Array();
				$query = "select distinct " . $by . " from $database where " . $by . " is not null and Provision_Type = 'Apps' and ssa1 = '$ssa1_value' order by " . $by;
				$by_values = DAO::getSingleColumn($link, $query);
				$value_found = false;
				foreach($by_values as $value)
				{
					$display_level = $value;
					$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
					$value_found = false;
					for($i = $start_index; $i<=sizeof($year)-1; $i++)
					{
						$filters['year'] = $year[$i];
						$filters['programme_type'] = "Apprenticeship";
						$filters[$by] = $value;
						$QAR = $this->getQAR($link,$filters);
						if(!isset($rate[$i]))
							$rate[$i] = array();

						if($qar_type_filter=="Overall")
						{
							$row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
							if(isset($cohort[$i]))
								$cohort[$i]+= $QAR['OverallLeaver'][0][0];
							else
								$cohort[$i] = $QAR['OverallLeaver'][0][0];
							if($QAR['OverallLeaver'][0][0]>0)
							{    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
								$value_found = true;
							}
							else
								$row.= "<td>0%</td>";

							//SSA1 Rate
							if($QAR['OverallLeaver'][0][0]>0)
							{
								$rate[$i][]=sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100);
							}
						}
						else
						{
							$row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
							if($QAR['TimelyLeaver'][0][0]>0)
							{    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
								$value_found = true;
							}
							else
								$row.= "<td>0%</td>";

							if($QAR['TimelyLeaver'][0][0]>0)
							{
								$rate[$i][]=sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100);
							}


						}
					}
					if($value_found)
						$data.=$row;
				}

				// Total SSA1 Row
				if($value_found)
				{
					$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ee90ee;'>".$ssa1_value." (Group Total)</td>";
					for($i = $start_index; $i<=sizeof($year)-1; $i++)
					{
						$row.= isset($cohort[$i]) ? "<td>" . $cohort[$i] . "</td>" : "<td> - </td>";
						$n = (count($rate[$i])==0)?1:count($rate[$i]);
						$r = array_sum($rate[$i])/$n;
						$row.= "<td>" . sprintf("%.2f",$r) . "</td>";
					}
					$data.=$row;
				}

			}
		}
		else
		{
			if($by=='level')
				$by = "Programme_Type";
			$query = "select distinct " . $by . " from $database where " . $by . " is not null order by " . $by;
			$by_values = DAO::getSingleColumn($link, $query);
			foreach($by_values as $value)
			{
				if($value=='2')
					$display_level = "2 - Advanced Apprenticeship";
				elseif($value=='3')
					$display_level = "3 - Intermediate Apprenticeship";
				elseif($value=='20')
					$display_level = "20 - Higher Apprenticeship";
				elseif($value=='25')
					$display_level = "25 - Standard";
				else
					$display_level = $value;

				$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
				$value_found = false;
				for($i = $start_index; $i<=sizeof($year)-1; $i++)
				{
					$filters['year'] = $year[$i];
					$filters['programme_type'] = "Apprenticeship";
					$filters[$by] = $value;
					$QAR = $this->getRetention($link,$filters);

					if($qar_type_filter=="Overall")
					{
						$row.= "<td><a href=javascript:expor('"  . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
						if($QAR['OverallLeaver'][0][0]>0)
						{    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
					else
					{
						$row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" .  $QAR['TimelyLeaver'][0][0] . "</td>";
						if($QAR['TimelyLeaver'][0][0]>0)
						{    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
				}
				if($value_found)
					$data.=$row;
			}
		}
		if($by!='programme' and $by!='sfc')
			foreach($by_values as $value)
			{
				$row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ffb6c1'>".$value."</td>";
				$value_found = false;
				for($i = $start_index; $i<=sizeof($year)-1; $i++)
				{
					$filters['year'] = $year[$i];
					$filters[$by] = $value;
					$filters['programme_type'] = "Education";
					$QAR = $this->getQAR($link,$filters);

					if($qar_type_filter=="Overall")
					{
						$row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
						if($QAR['OverallLeaver'][0][0]>0)
						{
							$row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
					else
					{
						$row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
						if($QAR['TimelyLeaver'][0][0]>0)
						{
							$row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
							$value_found = true;
						}
						else
							$row.= "<td>0%</td>";
					}
				}
				if($value_found)
					$data.=$row;
			}
		$data .= "</tr></tbody></table><br>";
		return $data;
	}

	public function getChart($link, $year, $by, $filters, $qar_type_filter,$app)
	{
		if($_SESSION['user']->department=='vauxhall')
			$database="esfa_qars2";
		else
			$database="esfa_qars";

		if($by=='ssa1')
			$by = "Sector_Subject_Area_Tier_1";
		elseif($by=='sfc')
			$by = "Framework_Name";
		elseif($by=='assessor')
			$by = "Delivery_Region";
		elseif($by=='level')
			$by = "Programme_Type";
		$result = array();
		$category = Array();
		$data = Array();
		$start_index = sizeof($year)-5;
		if($start_index<0)
			$start_index =0;
		for($i = $start_index; $i<=sizeof($year)-1; $i++)
			$category['categories'][]= Date::getFiscal($year[$i]);

		if($app=='Apprenticeship')
			$query = "select distinct " . $by . " from $database where Provision_Type = 'Apps' and " . $by . " is not null order by " . $by;
		else
			$query = "select distinct " . $by . " from $database where Provision_Type = 'Apps' and " . $by . " is not null order by " . $by;

		$by_values = DAO::getSingleColumn($link, $query);
		foreach($by_values as $value)
		{
			$series = Array();
			$value_found = false;
			$series['name'] = $value;
			for($i = $start_index; $i<=sizeof($year)-1; $i++)
			{
				$filters['year'] = $year[$i];
				$filters['programme_type'] = $app;
				$filters[$by] = $value;
				$QAR = $this->getQAR($link,$filters);

				if($qar_type_filter=="Overall")
				{
					if($QAR['OverallLeaver'][0][0]>0)
					{
						$series['data'][]= sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100);
						$value_found = true;
					}
					else
						$series['data'][]= 0;
				}
				else
				{
					if($QAR['TimelyLeaver'][0][0]>0)
					{
						$series['data'][]= sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100);
						$value_found = true;
					}
					else
						$series['data'][]= 0;
				}
			}
			array_push($result,$series);
		}
		echo(json_encode($result, JSON_NUMERIC_CHECK));
	}

	public function getQAR($link, $filters = Array())
	{
		if($_SESSION['user']->department=='vauxhall')
			$database="esfa_qars2";
		else
			$database="esfa_qars";

		DAO::execute($link, "SET SESSION group_concat_max_len = 1000000000;");
		$where = '';
		foreach($filters as $key => $value)
		{
			$value = addslashes((string)$value);
			if($key=='year')
				$year = $value;
			elseif($key=='ssa')
				$where .= " and concat(ssa1,'<br>',ssa2)='$value'";
			elseif($key=='programme_type' && $value=='Apprenticeship')
				$where .= " and Provision_Type = 'Apps'";
			elseif($key=='programme_type' && $value=='Education')
				$where .= " and Provision_Type != 'Apps'";
			elseif($key=='age_band' && $value=='19+')
				$where .= " and (Age_Group = '19-23' OR Age_Group = '24+')";
			elseif($key=='age_band' && $value=='19-23')
				$where .= " and (Age_Group = '19-23')";
			elseif($key=='age_band' && $value=='16-18')
				$where .= " and (Age_Group = '16-18')";
			elseif($key=='age_band' && $value=='24+')
				$where .= " and (Age_Group = '24+')";
			elseif($key=='level' && $value=='3')
				$where .= " and Programme_Type=='Intermediate Level Apprenticeship'";
			elseif($key=='level' && $value=='2')
				$where .= " and Programme_Type=='Advanced Level Apprenticeship'";
			elseif($key=='level' && $value=='20')
				$where .= " and Programme_Type=='Higher Level Apprenticeship'";
			elseif($key=='level' && $value=='25')
				$where .= " and Programme_Type=='Standard'";
			elseif($key=='gender' && $value=='M')
				$where .= " and Sex=='Male'";
			elseif($key=='gender' && $value=='F')
				$where .= " and Sex=='Female'";
			else
				$where .= " and " . $key . " = '$value'";
		}

		if($this->level!='' and $this->level!='All level')
		{
			if($this->level=='3')
				$where .= " and Programme_Type='Intermediate Level Apprenticeship'";
			elseif($this->level=='2')
				$where .= " and Programme_Type='Advanced Level Apprenticeship'";
			elseif($this->level=='20')
				$where .= " and Programme_Type='Higher Level Apprenticeship'";
			elseif($this->level=='25')
				$where .= " and Programme_Type='Standard'";
		}

		$result = Array();
		if($this->case_scenario=="Actual")
		{
			$result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE Hybrid_End_Year = $year  $where;");
			$result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE Hybrid_End_Year = $year AND Achievement_Rate_Status = 1 $where;");
			$result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE Expected_End_Year = $year AND Timely_Achiever = 1 $where;");
			$result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE Expected_End_Year = $year and Timely_Leaver = 1  $where;");
		}
		else
		{
			$tablebcs = $database."_bcs";
			DAO::execute($link, "CREATE TEMPORARY TABLE IF NOT EXISTS $tablebcs select * from $database");
			DAO::execute($link, "update $tablebcs set Learning_Actual_End_Date = Learning_Planned_End_Date, Actual_End_Year = Expected_End_Year, Hybrid_End_Year = Expected_End_Year, Achievement_Rate_Status = 1 where Achievement_Rate_Status = 0");

			$result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $tablebcs WHERE Hybrid_End_Year = $year  $where;");
			$result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $tablebcs WHERE Hybrid_End_Year = $year AND Achievement_Rate_Status = 1 $where;");
			$result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $tablebcs WHERE Expected_End_Year = $year AND Timely_Achiever = 1 $where;");
			$result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $tablebcs WHERE Expected_End_Year = $year and Timely_Leaver=1 $where;");
		}

		return $result;
	}

	public function getRetention($link, $filters = Array())
	{
		if($_SESSION['user']->department=='vauxhall')
			$database="esfa_qars2";
		else
			$database="esfa_qars";

		DAO::execute($link, "SET SESSION group_concat_max_len = 10000000;");
		$where = '';
		foreach($filters as $key => $value)
		{
			$value = addslashes((string)$value);
			if($key=='year')
				$year = $value;
			elseif($key=='ssa')
				$where .= " and concat(ssa1,'<br>',ssa2)='$value'";
			elseif($key=='programme_type' && $value=='Apprenticeship')
				$where .= " and Provision_Type = 'Apps'";
			elseif($key=='programme_type' && $value=='Education')
				$where .= " and Provision_Type = 'Education'";
			elseif($key=='age_band' && $value=='19+')
				$where .= " and (Age_Group = '19-23' OR Age_Group = '24+')";
			elseif($key=='age_band' && $value=='19-23')
				$where .= " and (Age_Group = '19-23')";
			elseif($key=='age_band' && $value=='16-18')
				$where .= " and (Age_Group = '16-18')";
			elseif($key=='age_band' && $value=='24+')
				$where .= " and (Age_Group = '24+')";
			else
				$where .= " and " . $key . " = '$value'";
		}

		if($this->level!='' and $this->level!='All level')
		{
			if($this->level=='3')
				$where .= " and Programme_Type='Intermediate Level Apprenticeship'";
			elseif($this->level=='2')
				$where .= " and Programme_Type='Advanced Level Apprenticeship'";
			elseif($this->level=='20')
				$where .= " and Programme_Type='Higher Level Apprenticeship'";
			elseif($this->level=='25')
				$where .= " and Programme_Type='Standard'";
		}

		$result = Array();
		if(true)
		{
			$end_year = ($year+1) . "-07-31";
			$result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE (Hybrid_End_Year = $year OR (Learning_Actual_End_Date IS NULL AND Learning_Start_Date<='$end_year'))  $where;");
			$result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE (Hybrid_End_Year = $year OR (Learning_Actual_End_Date IS NULL AND Learning_Start_Date<='$end_year')) AND Achievement_Rate_Status in (0,1)  $where;");
			$result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE Expected_End_Year = $year AND Achievement_Rate_Status = 1 and DATEDIFF(Learning_Actual_End_Date, Learning_Planned_End_Date)<=90  $where;");
			$result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $database WHERE Expected_End_Year = $year and Learning_Actual_End_Date is not null $where;");
		}
		else
		{
			$result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $table WHERE Hybrid_End_Year = $year $where;");
			$result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $table WHERE Hybrid_End_Year = $year AND p_prog_status = 1 $where;");
			$result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $table WHERE Expected_End_Year = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
			$result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(Learner_Reference,Learning_Start_Date) FROM $table WHERE Expected_End_Year = $year and actual_end_date is not null $where;");
		}

		return $result;
	}


	public $case_scenario = NULL;
	public $level = NULL;
}