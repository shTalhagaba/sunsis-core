<?php
class sla_kpi_reports implements IAction
{
	public function execute(PDO $link)
	{
		error_reporting(E_ALL^E_NOTICE);
		include('tpl_sla_kpi_reports.php');
	}

	public function get_student_qualification_details(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query="select * from student_qualifications GROUP BY id, internaltitle ORDER BY internaltitle";
		}
		elseif($mode=="get_distinct_titles")
		{
			$query="select DISTINCT (internaltitle) from student_qualifications ORDER BY internaltitle";
		}
		elseif($mode=="get_distinct_titles_from_achievement_date_range")
		{
			$from_date = $idarray[0];
			$to_date = $idarray[1];
			$query="select DISTINCT (internaltitle) from student_qualifications where achievement_date >='".$from_date."' and achievement_date <='".$to_date."' ORDER BY internaltitle";
		}
		elseif($mode=="get_distinct_learners_from_qualification_title")
		{
			$qualification_title = $idarray[0];
			$query="select DISTINCT(tr_id) from student_qualifications where internaltitle='".$qualification_title."'";
		}
		elseif($mode=="get_distinct_learners_from_achievement_date_range_and_qualification_title")
		{
			$from_date = $idarray[0];
			$to_date = $idarray[1];
			$qualification_title = $idarray[2];
			$query="select DISTINCT(tr_id) from student_qualifications where achievement_date >='".$from_date."' and achievement_date <='".$to_date."' and internaltitle='".$qualification_title."'";
		}

		$result = $link->query($query);
		$learner_id_arr = array();
		while($row = $result->fetch())
		{
			$result_exist="true";
			$learner_id = $row['tr_id'];
			if($learner_id != '')
			{
				array_push($learner_id_arr,$learner_id);
			}
			array_push($details_arr, $row);
		}

		if(count($learner_id_arr) > 0)
		{
			$learner_id_str = implode(",",$learner_id_arr);
			$details_arr['learner_id_str'] = $learner_id_str;
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}

	public function get_learner_details(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query="select * from tr";
		}
		elseif($mode=="from_learner_id_str")
		{
			$learner_id_str = $idarray[0];
			$query="select *, assessor as assessor_uname from tr where id IN (".$learner_id_str.")";
		}

		$result = $link->query($query);
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}



	public function get_new_learners(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$where="";
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')//start date is between these dates
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));

			$where .= "  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}

		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		$query="SELECT count(tr.id) AS learner_count, year(tr.start_date) AS year FROM tr ".$where." GROUP BY year ORDER BY year";
		$result = $link->query($query);
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_new_learners_drill_down(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$where="";
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));

			$where .= "  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";
		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.start_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}

		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		$group_by ="";
		if($idarray['group_by'] != "" && $idarray['group_by'] == "quarter")
		{
			$group_by = " GROUP BY year_and_quarter ORDER BY year_and_quarter ";
		}
		else if($idarray['group_by'] != "" && $idarray['group_by'] == "month")
		{
			$group_by = " GROUP BY year_and_month ORDER BY month ";
		}
		else if($idarray['group_by'] != "" && $idarray['group_by'] == "week")
		{
			$group_by = " GROUP BY week ORDER BY week ";
		}

		$query="SELECT
                count( tr.id ) AS learner_count,
                tr.start_date,
                year( tr.start_date ) AS year,
                month( tr.start_date ) AS month,
                monthname( tr.start_date ) AS month_name,
                concat( monthname( tr.start_date ) , '-', year( tr.start_date ) ) AS monthname_year,
                concat( year( tr.start_date ) , '-', month( tr.start_date ) ) AS year_and_month,
                concat( year( tr.start_date ) , '-', quarter( tr.start_date ) ) AS year_and_quarter,
                quarter( tr.start_date ) AS quarter,
                CASE quarter( tr.start_date )
                WHEN 1
                THEN 'Jan-Mar'
                WHEN 2
                THEN 'Apr-Jun'
                WHEN 3
                THEN 'Jul-Sep'
                WHEN 4
                THEN 'Oct-Dec'
                END AS quarter_name,
                weekofyear( tr.start_date ) AS week
                FROM tr ".$where." ".$group_by;

		$result = $link->query($query);
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_new_learners_drill_down_by_employer(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$where="";
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= "  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.start_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		$query="SELECT count( tr.id ) AS learner_count,
                tr.employer_id,
                o.legal_name AS employer_name
                FROM tr
                LEFT JOIN organisations o ON tr.employer_id = o.id
                ".$where."
                GROUP BY tr.employer_id
                ORDER BY employer_name";

		$result = $link->query($query);
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_new_learners_drill_down_by_contract(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$where="";
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= "  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.start_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		$query="SELECT count( tr.id ) AS learner_count,
                tr.contract_id,
                c.title AS contract_name
                FROM tr
                LEFT JOIN contracts c ON tr.contract_id = c.id
                ".$where."
                GROUP BY tr.contract_id
                ORDER BY contract_name";

		//echo 'get_new_learners_drill_down_by_employer query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_new_learners_drill_down_by_training_provider(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		$where="";
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= "  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.start_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count( tr.id ) AS learner_count,
                tr.provider_id,
                o.legal_name AS training_provider_name
                FROM tr
                LEFT JOIN organisations o ON tr.provider_id = o.id
                ".$where."
                GROUP BY tr.provider_id
                ORDER BY training_provider_name";

		//echo 'get_new_learners_drill_down_by_employer query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_new_learners_drill_down_by_assessor(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		$where="";
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= "  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.start_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count( tr.id ) AS learner_count,
                tr.assessor,
                concat( u.firstnames, u.surname ) AS assessor_name
                FROM tr
                LEFT JOIN users u ON tr.assessor = u.id
                ".$where."
                GROUP BY tr.assessor
                ORDER BY assessor_name";

		//echo 'get_new_learners_drill_down_by_assessor query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_completions(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;
		if(isset($idarray['mode']) && $idarray['mode'] == 'early_leavers')
		{
			$where = " LEFT JOIN contracts AS c ON (c.id = tr.contract_id)
                        WHERE (tr.status_code = 3 or tr.status_code=4)
                        AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0
                        AND tr.closure_date >= c.start_date ";
		}
		else
		{
			$where = "WHERE tr.status_code=2 ";
		}
		if($idarray['from_date'] != '' && $idarray['to_date'] != '')//closure date is between these dates
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));

			$where .= $and."  tr.closure_date >= '".$from_date."' AND tr.closure_date <= '".$to_date."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}

		/*if($where != '')
				{
					$where = "WHERE ".$where;
				}*/

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count(tr.id) AS learner_count, year(tr.closure_date) AS year FROM tr ".$where." GROUP BY year ORDER BY year";
		//echo 'get_new_learners query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_completions_drill_down(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		if(isset($idarray['mode']) && $idarray['mode'] == 'early_leavers')
		{
			$where = " LEFT JOIN contracts AS c ON (c.id = tr.contract_id)
                        WHERE (tr.status_code = 3 or tr.status_code=4)
                        AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0
                        AND tr.closure_date >= c.start_date ";
		}
		else
		{
			$where = "WHERE tr.status_code=2 ";
		}

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));

			$where .= $and."  tr.closure_date >= '".$from_date."' AND tr.closure_date <= '".$to_date."' ";
		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.closure_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}

		/*if($where != '')
				{
					$where = "WHERE ".$where;
				}*/

		$group_by ="";
		if($idarray['group_by'] != "" && $idarray['group_by'] == "quarter")
		{
			$group_by = " GROUP BY year_and_quarter ORDER BY year_and_quarter ";
		}
		else if($idarray['group_by'] != "" && $idarray['group_by'] == "month")
		{
			$group_by = " GROUP BY year_and_month ORDER BY month ";
		}
		else if($idarray['group_by'] != "" && $idarray['group_by'] == "week")
		{
			$group_by = " GROUP BY week ORDER BY week ";
		}

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT
                count( tr.id ) AS learner_count,
                tr.closure_date,
                year( tr.closure_date ) AS year,
                month( tr.closure_date ) AS month,
                monthname( tr.closure_date ) AS month_name,
                concat( monthname( tr.closure_date ) , '-', year( tr.closure_date ) ) AS monthname_year,
                concat( year( tr.closure_date ) , '-', month( tr.closure_date ) ) AS year_and_month,
                concat( year( tr.closure_date ) , '-', quarter( tr.closure_date ) ) AS year_and_quarter,
                quarter( tr.closure_date ) AS quarter,
                CASE quarter( tr.closure_date )
                WHEN 1
                THEN 'Jan-Mar'
                WHEN 2
                THEN 'Apr-Jun'
                WHEN 3
                THEN 'Jul-Sep'
                WHEN 4
                THEN 'Oct-Dec'
                END AS quarter_name,
                weekofyear( tr.closure_date ) AS week
                FROM tr ".$where." ".$group_by;

		//echo 'get_completions_drill_down query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_completions_drill_down_by_employer(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		if(isset($idarray['mode']) && $idarray['mode'] == 'early_leavers')
		{
			$where = " LEFT JOIN contracts AS c ON (c.id = tr.contract_id)
                        WHERE (tr.status_code = 3 or tr.status_code=4)
                        AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0
                        AND tr.closure_date >= c.start_date ";
		}
		else
		{
			$where = "WHERE tr.status_code=2 ";
		}

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= $and."  tr.closure_date >= '".$from_date."' AND tr.closure_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.closure_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		/*if($where != '')
				{
					$where = "WHERE ".$where;
				}*/

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count( tr.id ) AS learner_count,
                tr.employer_id,
                o.legal_name AS employer_name
                FROM tr
                LEFT JOIN organisations o ON tr.employer_id = o.id
                ".$where."
                GROUP BY tr.employer_id
                ORDER BY employer_name";

		//echo 'get_completions_drill_down_by_employer query = '.$query;//exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_completions_drill_down_by_contract(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		if(isset($idarray['mode']) && $idarray['mode'] == 'early_leavers')
		{
			$where = "  WHERE (tr.status_code = 3 or tr.status_code=4)
                        AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0
                        AND tr.closure_date >= c.start_date ";
		}
		else
		{
			$where = "WHERE tr.status_code=2 ";
		}

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= $and."  tr.closure_date >= '".$from_date."' AND tr.closure_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.closure_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		/*if($where != '')
				{
					$where = "WHERE ".$where;
				}*/

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count( tr.id ) AS learner_count,
                tr.contract_id,
                c.title AS contract_name
                FROM tr
                LEFT JOIN contracts c ON tr.contract_id = c.id
                ".$where."
                GROUP BY tr.contract_id
                ORDER BY contract_name";

		//echo 'get_completions_drill_down_by_employer query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_completions_drill_down_by_training_provider(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		if(isset($idarray['mode']) && $idarray['mode'] == 'early_leavers')
		{
			$where = " LEFT JOIN contracts AS c ON (c.id = tr.contract_id)
                        WHERE (tr.status_code = 3 or tr.status_code=4)
                        AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0
                        AND tr.closure_date >= c.start_date ";
		}
		else
		{
			$where = "WHERE tr.status_code=2 ";
		}

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= $and."  tr.closure_date >= '".$from_date."' AND tr.closure_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.closure_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		/* if($where != '')
				{
					$where = "WHERE ".$where;
				}*/

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count( tr.id ) AS learner_count,
                tr.provider_id,
                o.legal_name AS training_provider_name
                FROM tr
                LEFT JOIN organisations o ON tr.provider_id = o.id
                ".$where."
                GROUP BY tr.provider_id
                ORDER BY training_provider_name";

		//echo 'get_completions_drill_down_by_employer query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_completions_drill_down_by_assessor(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		if(isset($idarray['mode']) && $idarray['mode'] == 'early_leavers')
		{
			$where = " LEFT JOIN contracts AS c ON (c.id = tr.contract_id)
                        WHERE (tr.status_code = 3 or tr.status_code=4)
                        AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0
                        AND tr.closure_date >= c.start_date ";
		}
		else
		{
			$where = "WHERE tr.status_code=2 ";
		}

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));


			$where .= $and."  tr.closure_date >= '".$from_date."' AND tr.closure_date <= '".$to_date."' ";

		}

		if($idarray['year'] != '')
		{
			$where .= "  and year(tr.closure_date)='".$idarray['year']."' ";
		}
		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}
		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}
		/* if($where != '')
				{
					$where = "WHERE ".$where;
				}*/

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT count( tr.id ) AS learner_count,
                tr.assessor,
                concat( u.firstnames, u.surname ) AS assessor_name
                FROM tr
                LEFT JOIN users u ON tr.assessor = u.id
                ".$where."
                GROUP BY tr.assessor
                ORDER BY assessor_name";

		//echo 'get_completions_drill_down_by_assessor query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}



	public function get_learner_last_visit_details(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		$where="WHERE status_code = 1 and assessment_date_subquery.assessment_date != '' ";

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')//last review date is between these dates
		{
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));

			$where .= " and assessment_date_subquery.assessment_date >= '".$from_date."' and  assessment_date_subquery.assessment_date <= '".$to_date."' ";
		}

		if($idarray['assessor'] != '')
		{
			$assessor = $idarray['assessor'];
			$where .=" and (groups.assessor='".$assessor."' or tr.assessor='".$assessor."') ";
		}

		if($idarray['contract'] != '')
		{
			$contract = $idarray['contract'];
			$where .=" and contracts.id='".$contract."' ";
		}

		if($idarray['employer'] != '')
		{
			$employer = $idarray['employer'];
			$where .=" and tr.employer_id='".$employer."' ";
		}

		if($idarray['training_provider'] != '')
		{
			$training_provider = $idarray['training_provider'];
			$where .=" and tr.provider_id='".$training_provider."' ";
		}

		//echo "<br>where condition = ".$where.'<br>';exit;

		$query="SELECT DISTINCT
	DATE_FORMAT(tr.start_date, '%d-%m-%Y') as start_date,
	DATE_FORMAT(target_date, '%d-%m-%Y') as planned_end_date,
	frameworks.first_review as frequency,
	frameworks.review_frequency as subsequent,
	assessor_review.comments as assessment_status,
	meeting_dates.all_dates,
	tr.id AS tr_id,
	courses.title as course_title,
	tr.contract_id,
	tr.l03,
	CONCAT(tr.surname, ' ', tr.firstnames) AS learner_name,
    concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator,
	DATE_FORMAT(assessment_date_subquery.assessment_date, '%d-%m-%Y')  as last_review_date,
	employers.legal_name AS employer,
    providers.legal_name AS training_provider,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	groups.title as groups,
	NULL as next_review_date,
	NULL as missed_reviews,
	contracts.title as contract,
    CURDATE() as today

FROM
	tr
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN frameworks ON frameworks.id = courses.framework_id
	LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id
	LEFT JOIN users AS assessors ON groups.assessor = assessors.id
	LEFT JOIN users AS verifiers ON groups.verifier = verifiers.id
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND assessor_review.id = (SELECT MAX(id) FROM assessor_review WHERE tr_id = tr.id AND `assessor_review`.`meeting_date` IS NOT NULL)
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS verifiersng ON verifiersng.id = tr.verifier
	LEFT JOIN users as acs on acs.id = tr.programme
	LEFT OUTER JOIN (
		SELECT
			assessor_review.tr_id,
			MAX(meeting_date) AS `assessment_date`
		FROM
			assessor_review
		GROUP BY
			assessor_review.tr_id
	) AS `assessment_date_subquery`
		ON `assessment_date_subquery`.tr_id = tr.id

    LEFT OUTER JOIN (
        SELECT
            tr_id,
            GROUP_CONCAT(meeting_date) as all_dates
        FROM assessor_review
            group by assessor_review.tr_id
    ) AS `meeting_dates` on `meeting_dates`.tr_id = tr.id
 ".$where."
ORDER BY tr.surname";
		//pre($query);exit;
		//echo 'get_learner_last_visit_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_assessors(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query="SELECT id, CONCAT( firstnames, ' ', surname ) AS assessor_name FROM users WHERE TYPE =3 ORDER BY assessor_name";
		}

		//echo 'get_assessors query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_contracts(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query="SELECT id, title FROM contracts WHERE active =1 ORDER BY title";
		}
		else if($mode=="all_distinct_contract_years")
		{
			$query="SELECT DISTINCT(contract_year) FROM contracts ORDER BY contract_year DESC";
		}

		//echo 'get_contracts query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_employers(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query="SELECT tr.employer_id as id, o.legal_name AS employer_name
                    FROM tr
                    INNER JOIN organisations o ON tr.employer_id = o.id
                    GROUP BY tr.employer_id
                    ORDER BY employer_name";
		}

		//echo 'get_contracts query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_training_providers(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query="SELECT tr.provider_id as id, o.legal_name AS training_provider_name
                    FROM tr
                    INNER JOIN organisations o ON tr.provider_id = o.id
                    GROUP BY tr.provider_id
                    ORDER BY training_provider_name";
		}

		//echo 'get_contracts query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}




	public function save_filters($link, $user_id, $report_type, $_REQUEST=array())
	{
		$details_arr = array();
		$result_exist="false";

		$arr_filter_data = array();
		$arr_filter_data['graph_type'] = $_REQUEST['graph_type'];
		$arr_filter_data['from_date'] = $_REQUEST['from_date'];
		$arr_filter_data['to_date'] = $_REQUEST['to_date'];
		$arr_filter_data['drill_down_by'] = $_REQUEST['drill_down_by'];
		$arr_filter_data['assessor'] = $_REQUEST['assessor'];
		$arr_filter_data['contract'] = $_REQUEST['contract'];
		$arr_filter_data['employer'] = $_REQUEST['employer'];
		$arr_filter_data['training_provider'] = $_REQUEST['training_provider'];
		$arr_filter_data['progress'] = $_REQUEST['progress'];
		$arr_filter_data['gender'] = $_REQUEST['gender'];
		$arr_filter_data['programme'] = $_REQUEST['programme'];
		$arr_filter_data['record_status'] = $_REQUEST['record_status'];
		$arr_filter_data['course'] = $_REQUEST['course'];
		$arr_filter_data['framework'] = $_REQUEST['framework'];
		$arr_filter_data['group'] = $_REQUEST['group'];
		$arr_filter_data['target_start_date'] = $_REQUEST['target_start_date'];
		$arr_filter_data['target_end_date'] = $_REQUEST['target_end_date'];
		$arr_filter_data['closure_start_date'] = $_REQUEST['closure_start_date'];
		$arr_filter_data['closure_end_date'] = $_REQUEST['closure_end_date'];
		$arr_filter_data['work_experience_start_date'] = $_REQUEST['work_experience_start_date'];
		$arr_filter_data['work_experience_end_date'] = $_REQUEST['work_experience_end_date'];
		$arr_filter_data['progress'] = $_REQUEST['progress'];

		$arr_filter_data['active'] = $_REQUEST['active'];
		$arr_filter_data['valid'] = $_REQUEST['valid'];
		$arr_filter_data['contract_year'] = $_REQUEST['contract_year'];
		$arr_filter_data['submission'] = $_REQUEST['submission'];

		$arr_filter_data['age_band'] = $_REQUEST['age_band'];
		$arr_filter_data['programme_type'] = $_REQUEST['programme_type'];
		$arr_filter_data['ssa'] = $_REQUEST['ssa'];
		$arr_filter_data['ethnicity'] = $_REQUEST['ethnicity'];

		$filter_string = json_encode($arr_filter_data);

		$query = "select id from sla_kpi_reports_saved_filters where user_id='".$user_id."' and report_type='".$report_type."'";
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		$row = $result->fetch();
		if($row['id'] != '')//if filter data exists then update else insert
		{
			$query1="update sla_kpi_reports_saved_filters set filter_string='".$filter_string."', updated_datetime=NOW() where user_id='".$user_id."' and report_type='".$report_type."'";
		}
		else
		{
			$query1="insert into sla_kpi_reports_saved_filters (user_id, report_type, filter_string, updated_datetime) values ('".$user_id."', '".$report_type."', '".$filter_string."',NOW())";
		}
		$link->query($query1);
	}


	public function get_filter_details(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="from_user_id_and_report_type")
		{
			$query = "select * from sla_kpi_reports_saved_filters where user_id='".$idarray[0]."' and report_type='".$idarray[1]."'";
		}
		else if($mode=="from_user_id")
		{
			$query = "select * from sla_kpi_reports_saved_filters where user_id='".$idarray[0]."'";
		}
		else if($mode=="from_filter_id")
		{
			$query = "select * from sla_kpi_reports_saved_filters where id='".$idarray[0]."'";
		}
		else if($mode=="from_user_id_with_limit_latest_saved")
		{
			$query = "select * from sla_kpi_reports_saved_filters where user_id='".$idarray[0]."' ORDER BY updated_datetime DESC LIMIT ".$idarray['limit'];
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_genders(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all_distinct")
		{
			$query = "select distinct(gender) from tr";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_programme(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query = "SELECT code, description FROM lookup_programme_type";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}

	public function get_courses(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all_distinct")
		{
			$query = "SELECT DISTINCT id, title FROM courses order by title";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_frameworks(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all_distinct")
		{
			$query = "SELECT DISTINCT id, title FROM student_frameworks order by title";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}

	public function get_groups(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all_distinct")
		{
			$query = "SELECT groups.id, CONCAT(courses.title, '::' , groups.title) as title FROM groups INNER JOIN courses on courses.id = groups.courses_id order by title";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_learners(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;
		$having="";
		$where="";

		if($idarray['from_date'] != '' && $idarray['to_date'] != '')//start date is between these dates
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$from_date1 = str_replace('/', '-', $idarray['from_date']);
			$from_date = date("Y-m-d",strtotime($from_date1));

			$to_date1 = str_replace('/', '-', $idarray['to_date']);
			$to_date = date("Y-m-d",strtotime($to_date1));

			$where .= $and."  tr.start_date >= '".$from_date."' AND tr.start_date <= '".$to_date."' ";
		}

		if($idarray['target_start_date'] != '' && $idarray['target_end_date'] != '')//target_date is between these dates
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$target_start_date = str_replace('/', '-', $idarray['target_start_date']);
			$target_start_date = date("Y-m-d",strtotime($target_start_date));

			$target_end_date = str_replace('/', '-', $idarray['target_end_date']);
			$target_end_date = date("Y-m-d",strtotime($target_end_date));

			$where .= $and."  tr.target_date >= '".$target_start_date."' AND tr.target_date <= '".$target_end_date."' ";
		}

		if($idarray['closure_start_date'] != '' && $idarray['closure_end_date'] != '')//closure date is between these dates
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$closure_start_date = str_replace('/', '-', $idarray['closure_start_date']);
			$closure_start_date = date("Y-m-d",strtotime($closure_start_date));

			$closure_end_date = str_replace('/', '-', $idarray['closure_end_date']);
			$closure_end_date = date("Y-m-d",strtotime($closure_end_date));

			$where .= $and."  tr.closure_date >= '".$closure_start_date."' AND tr.closure_date <= '".$closure_end_date."' ";
		}

		if($idarray['work_experience_start_date'] != '' && $idarray['work_experience_end_date'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$work_experience_start_date = str_replace('/', '-', $idarray['work_experience_start_date']);
			$work_experience_start_date = date("Y-m-d",strtotime($work_experience_start_date));

			$work_experience_end_date = str_replace('/', '-', $idarray['work_experience_end_date']);
			$work_experience_end_date = date("Y-m-d",strtotime($work_experience_end_date));

			$where .= $and."  workplace_visits.end_date >= '".$work_experience_start_date."' AND workplace_visits.end_date <= '".$work_experience_end_date."' ";
		}



		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." tr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}

		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}



		//filter gender
		if($idarray['gender'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$gender = $idarray['gender'];
			$where .= $and."  tr.gender = '".$gender."' ";
		}

		//filter programme
		if($idarray['programme'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$programme = $idarray['programme'];
			$where .= $and."  courses.programme_type = '".$programme."' ";
		}

		//filter record_status
		if($idarray['record_status'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$record_status = $idarray['record_status'];
			$where .= $and."  tr.status_code = '".$record_status."' ";
		}

		//filter record_status
		if($idarray['course'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$course = $idarray['course'];
			$where .= $and."  courses.id = '".$course."' ";
		}

		//filter framework
		if($idarray['framework'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$framework = $idarray['framework'];
			$where .= $and."  student_frameworks.id = '".$framework."' ";
		}

		//filter group
		if($idarray['group'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$group = $idarray['group'];
			$where .= $and."  group_members.groups_id = '".$group."' ";
		}


		//filter year
		if($idarray['year'] != '')
		{
			if($having != ''){ $and=" AND ";}else{$and = "";}
			$year = $idarray['year'];
			$having .= $and." year = '".$year."' ";
		}

		//filter progress

		if($idarray['progress'] != '')
		{
			if($having != ''){ $and=" AND ";}else{$and = "";}
			$progress = $idarray['progress'];
			if($progress == 1)
			{
				$having = " progress='On Track'";
			}
			elseif($progress == 2)
			{
				$having = " progress='Behind'";
			}
		}


		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		if($having != '')
		{
			$having = "HAVING ".$having;
		}


		//echo "<br>where condition = ".$where.'<br>';exit;

		$drill_down_by = $idarray['drill_down_by'];
		$group_by ="";
		$order_by ="";
		if($drill_down_by == "quarter")
		{
			$group_by = " GROUP BY year, year_and_quarter";
			$order_by = " ORDER BY year, year_and_quarter";
		}
		else if($drill_down_by == "month")
		{
			$group_by = " GROUP BY year, year_and_month";
			$order_by = " ORDER BY year, month";
		}
		else if($drill_down_by == "week")
		{
			$group_by = " GROUP BY year, week";
			$order_by = " ORDER BY year, week";
		}
		else if($drill_down_by == 'employer')
		{
			$group_by = " GROUP BY year, tr.employer_id";
			$order_by = " ORDER BY year, employer_name";
		}
		else if($drill_down_by == 'training_provider')
		{
			$group_by = " GROUP BY year, tr.provider_id";
			$order_by = " ORDER BY year, training_provider_name";
		}
		else if($drill_down_by == 'contract')
		{
			$group_by = " GROUP BY year, tr.contract_id";
			$order_by = " ORDER BY year, contract_name";
		}
		else if($drill_down_by == 'assessor')
		{
			$group_by = " GROUP BY year, tr.assessor";
			$order_by = " ORDER BY year, assessor_name";
		}
		else if($drill_down_by == 'age_range')
		{
			$group_by = " GROUP BY year, age";
			$order_by = " ORDER BY year, age";
		}
		else if($drill_down_by == 'course')
		{
			$group_by = " GROUP BY year, courses.id";
			$order_by = " ORDER BY year, course_title";
		}
		else if($drill_down_by == 'disability')
		{
			$group_by = " GROUP BY year, disability";
			$order_by = " ORDER BY year, disability";
		}
		else if($drill_down_by == 'ethnicity')
		{
			$group_by = " GROUP BY year, ethnicity";
			$order_by = " ORDER BY year, ethnicity";
		}
		else if($drill_down_by == 'gender')
		{
			$group_by = " GROUP BY year, gender";
			$order_by = " ORDER BY year, gender";
		}
		else if($drill_down_by == 'tutor')
		{
			$group_by = " GROUP BY year, tutor";
			$order_by = " ORDER BY year, tutor";
		}
		else if($drill_down_by == 'learning_difficulty')
		{
			$group_by = " GROUP BY year, learning_difficulty";
			$order_by = " ORDER BY year, learning_difficulty";
		}
		else if($drill_down_by == 'progress')
		{
			$group_by = " GROUP BY year, progress";
			$order_by = " ORDER BY year, progress";
		}
		else if($drill_down_by == 'mainarea')
		{
			$group_by = " GROUP BY year, mainarea";
			$order_by = " ORDER BY year, mainarea";
		}
		else if($drill_down_by == 'subarea')
		{
			$group_by = " GROUP BY year, internaltitle";
			$order_by = " ORDER BY year, internaltitle";
		}
		else if($drill_down_by == 'record_status')
		{
			$group_by = " GROUP BY year, record_status";
			$order_by = " ORDER BY year, record_status";
		}
		else if($drill_down_by == 'verifier')
		{
			$group_by = " GROUP BY year, verifiers.id";
			$order_by = " ORDER BY year, verifier";
		}
		else if($drill_down_by == 'work_experience_coordinator')
		{
			$group_by = " GROUP BY year, wbcoordinators.id";
			$order_by = " ORDER BY year, wbcoordinator";
		}
		/*else if($drill_down_by == 'actual_work_experience')
		{
			$group_by = " GROUP BY year, actual_work_experience";
			$order_by = " ORDER BY year, actual_work_experience ";
		}*/
		/*else if($drill_down_by == 'work_experience_band_10')
		{
			$group_by = " GROUP BY year, band0to10 ";
			$order_by = " ORDER BY year, band0to10";
		}*/
		else
		{
			$group_by = " GROUP BY year";
			$order_by = " ORDER BY year";
		}

		$query = "
SELECT DISTINCT
				year( tr.start_date ) AS year, count( tr.id ) AS learner_count,month( tr.start_date ) AS month,monthname( tr.start_date ) AS month_name,
                    concat( monthname( tr.start_date ) , '-', year( tr.start_date ) ) AS monthname_year,concat( year( tr.start_date ) , '-', month( tr.start_date ) ) AS year_and_month,
                    concat( year( tr.start_date ) , '-', quarter( tr.start_date ) ) AS year_and_quarter,quarter( tr.start_date ) AS quarter,
                    CASE quarter( tr.start_date )
                    WHEN 1
                    THEN 'Jan-Mar'
                    WHEN 2
                    THEN 'Apr-Jun'
                    WHEN 3
                    THEN 'Jul-Sep'
                    WHEN 4
                    THEN 'Oct-Dec'
                    END AS quarter_name,
                    weekofyear( tr.start_date ) AS week,
				tr.id AS tr_id,users.gender AS gender,CONCAT(lisl12.Ethnicity_Code, ' ', lisl12.Ethnicity_Desc) AS ethnicity,CONCAT(lisl15.Disability_Code, ' ', lisl15.Disability_Desc) AS disability,
				IF(tr.target_date < '2013-07-05',IF(tr.l36 >= 100,'On Track', 'Behind'),IF(`subquery`.result IS NULL
                                                                                    OR tr.l36 >= `subquery`.result, 'On Track', 'Behind')) AS progress,

                IF((DATEDIFF(tr.start_date, tr.dob)/365)>=16
                   AND (DATEDIFF(tr.start_date, tr.dob)/365)<19, '16-18', IF((DATEDIFF(tr.start_date, tr.dob)/365)>=19
                                                                             AND (DATEDIFF(tr.start_date, tr.dob)/365)<=25, '19-24', IF((DATEDIFF(tr.start_date, tr.dob)/365)>25, '25+', 'Unknown'))) AS age,
                CONCAT(lisl16.Difficulty_Code, ' ', lisl16.Difficulty_Desc) AS learning_difficulty,courses.title AS course_title,
                IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
                CONCAT(verifiers.firstnames, ' ', verifiers.surname) AS verifier,
                IF(CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, CONCAT(tutorsng.firstnames,' ',tutorsng.surname), '') AS tutor,
                providers.legal_name AS provider,
                actual_work_experience_subquery.wactual AS actual_work_experience,
                target_work_experience_subquery.wplanned AS visits,
                CONCAT(wbcoordinators.firstnames, ' ', wbcoordinators.surname) AS wbcoordinator,
                IF(actual_work_experience_subquery.wactual >= 0
                   AND actual_work_experience_subquery.wactual <= 10, '0-10', IF(actual_work_experience_subquery.wactual >= 11
                                                                                 AND actual_work_experience_subquery.wactual <= 20, '11-20',IF(actual_work_experience_subquery.wactual >= 21
                                                                                                                                               AND actual_work_experience_subquery.wactual <= 30, '21-30',IF(actual_work_experience_subquery.wactual >= 31
AND actual_work_experience_subquery.wactual <= 40, '31-40',IF(actual_work_experience_subquery.wactual >= 41
                                                              AND actual_work_experience_subquery.wactual <= 50, '41-50',NULL))))) AS band0to10,
                qualifications_subquery.mainarea,
                qualifications_subquery.internaltitle,
                qualifications_subquery.level,
                users.job_role AS job_role,
                lookup_pot_status.description AS record_status,
                CONCAT(acoordinators.firstnames,' ',acoordinators.surname) AS apprentice_coordinator,
                IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
                IF(tr.target_date < '2013-07-05',100,`subquery`.result) AS target,
                employers.legal_name as employer_name,providers.legal_name as training_provider_name,contracts.title AS contract_name,concat( users.firstnames, users.surname ) AS assessor_name

FROM tr
LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
LEFT JOIN users ON users.username = tr.username
LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
LEFT JOIN group_members ON group_members.tr_id = tr.id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
LEFT JOIN groups ON group_members.groups_id = groups.id
LEFT JOIN users AS assessors ON groups.assessor = assessors.id
#LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id
AND CONCAT(assessor_review.id,assessor_review.meeting_date) =
(SELECT MAX(CONCAT(id,meeting_date))
 FROM assessor_review
 WHERE tr_id = tr.id
   AND meeting_date IS NOT NULL
   AND meeting_date!='0000-00-00')
LEFT JOIN contracts ON contracts.id = tr.contract_id
LEFT JOIN lookup_contract_locations ON lookup_contract_locations.id = contracts.contract_location
LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor
LEFT JOIN users AS acoordinators ON acoordinators.id = tr.programme
LEFT JOIN users AS verifiers ON verifiers.id = groups.verifier
LEFT JOIN users AS wbcoordinators ON wbcoordinators.id = groups.wbcoordinator
LEFT JOIN locations ON locations.id = tr.employer_location_id
LEFT JOIN brands ON brands.id = employers.manufacturer
LEFT JOIN lis201112.ilr_l12_ethnicity AS lisl12 ON lisl12.Ethnicity_Code = tr.ethnicity
LEFT JOIN lis201112.ilr_l15_disability AS lisl15 ON lisl15.Disability_Code = tr.disability
LEFT JOIN lis201112.ilr_l16_difficulty AS lisl16 ON lisl16.Difficulty_Code = tr.learning_difficulty
LEFT JOIN lookup_pot_status ON lookup_pot_status.code = tr.status_code
LEFT OUTER JOIN
( SELECT qualifications.mainarea,qualifications.internaltitle,qualifications.level,tr_id
 FROM qualifications
 LEFT JOIN framework_qualifications AS mainaim ON mainaim.id = qualifications.id
 AND mainaim.internaltitle = qualifications.internaltitle
 AND main_aim = 1
 LEFT JOIN student_qualifications ON student_qualifications.id = mainaim.id
 AND student_qualifications.framework_id = mainaim.framework_id ) AS `qualifications_subquery` ON `qualifications_subquery`.tr_id = tr.id
LEFT OUTER JOIN
( SELECT tr_id,
         meeting_date,
         GROUP_CONCAT(meeting_date) AS all_dates
 FROM assessor_review
 GROUP BY assessor_review.tr_id HAVING meeting_date!='0000-00-00' ) AS `meeting_dates` ON `meeting_dates`.tr_id = tr.id
LEFT OUTER JOIN
( SELECT workplace_visits.tr_id,
         COUNT(*) AS `wplanned`
 FROM workplace_visits
 WHERE start_date IS NOT NULL
 GROUP BY workplace_visits.tr_id ) AS `target_work_experience_subquery` ON `target_work_experience_subquery`.tr_id = tr.id
LEFT OUTER JOIN
( SELECT workplace_visits.tr_id,
         COUNT(*) AS `wactual`
 FROM workplace_visits
 WHERE end_date IS NOT NULL
 GROUP BY workplace_visits.tr_id ) AS `actual_work_experience_subquery` ON `actual_work_experience_subquery`.tr_id = tr.id
LEFT OUTER JOIN
( SELECT tr.id AS tr_id,
         SUM(`sub`.target * proportion /
               (SELECT SUM(proportion)
                FROM student_qualifications
                WHERE tr_id = tr.id
                  AND aptitude != 1)) AS RESULT
 FROM tr
 LEFT OUTER JOIN
   (SELECT student_milestones.tr_id,
           student_qualifications.proportion,
           CASE timestampdiff(MONTH, student_qualifications.start_date, '2013-07-05') WHEN -1 THEN 0 WHEN -2 THEN 0 WHEN -3 THEN 0 WHEN -4 THEN 0 WHEN -5 THEN 0 WHEN -6 THEN 0 WHEN -7 THEN 0 WHEN -8 THEN 0 WHEN -9 THEN 0 WHEN -10 THEN 0 WHEN 0 THEN 0 WHEN 1 THEN AVG(student_milestones.month_1) WHEN 2 THEN AVG(student_milestones.month_2) WHEN 3 THEN AVG(student_milestones.month_3) WHEN 4 THEN AVG(student_milestones.month_4) WHEN 5 THEN AVG(student_milestones.month_5) WHEN 6 THEN AVG(student_milestones.month_6) WHEN 7 THEN AVG(student_milestones.month_7) WHEN 8 THEN AVG(student_milestones.month_8) WHEN 9 THEN AVG(student_milestones.month_9) WHEN 10 THEN AVG(student_milestones.month_10) WHEN 11 THEN AVG(student_milestones.month_11) WHEN 12 THEN AVG(student_milestones.month_12) WHEN 13 THEN AVG(student_milestones.month_13) WHEN 14 THEN AVG(student_milestones.month_14) WHEN 15 THEN AVG(student_milestones.month_15) WHEN 16 THEN AVG(student_milestones.month_16) WHEN 17 THEN AVG(student_milestones.month_17) WHEN 18 THEN AVG(student_milestones.month_18) WHEN 19 THEN AVG(student_milestones.month_19) WHEN 20 THEN AVG(student_milestones.month_20) WHEN 21 THEN AVG(student_milestones.month_21) WHEN 22 THEN AVG(student_milestones.month_22) WHEN 23 THEN AVG(student_milestones.month_23) WHEN 24 THEN AVG(student_milestones.month_24) WHEN 25 THEN AVG(student_milestones.month_25) WHEN 26 THEN AVG(student_milestones.month_26) WHEN 27 THEN AVG(student_milestones.month_27) WHEN 28 THEN AVG(student_milestones.month_28) WHEN 29 THEN AVG(student_milestones.month_29) WHEN 30 THEN AVG(student_milestones.month_30) WHEN 31 THEN AVG(student_milestones.month_31) WHEN 32 THEN AVG(student_milestones.month_32) WHEN 33 THEN AVG(student_milestones.month_33) WHEN 34 THEN AVG(student_milestones.month_34) WHEN 35 THEN AVG(student_milestones.month_35) WHEN 36 THEN AVG(student_milestones.month_36) ELSE 100 END AS target
    FROM student_milestones
    LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.`qualification_id`
    AND student_milestones.tr_id = student_qualifications.`tr_id`
    AND student_qualifications.aptitude != 1
    GROUP BY student_milestones.`tr_id`,
             student_milestones.`qualification_id`) AS `sub` ON tr.id = `sub`.tr_id
 GROUP BY tr.`id` ) AS `subquery` ON `subquery`.tr_id = tr.id
 ".$where." ".$group_by." ".$having." ".$order_by;


		//throw new Exception($query);
		$result = $link->query($query) or die('Error in query '.pre($query));
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}



	public function get_ilrs(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		$where="";

		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." ilr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}

		//filter assessors
		if($idarray['assessor_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$assessor_id = $idarray['assessor_id'];
			$where .= $and."  tr.assessor = '".$assessor_id."' ";
		}



		//filter gender
		if($idarray['gender'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$gender = $idarray['gender'];
			$where .= $and."  tr.gender = '".$gender."' ";
		}

		//filter record_status
		if($idarray['course'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$course = $idarray['course'];
			$where .= $and."  courses.id = '".$course."' ";
		}

		//filter framework
		if($idarray['framework'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$framework = $idarray['framework'];
			$where .= $and."  student_frameworks.id = '".$framework."' ";
		}

		//filter valid
		if($idarray['valid'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$valid = $idarray['valid'];

			if($valid == "all")
				$where .= $and." (ilr.is_valid = '1' OR ilr.is_valid != '1') ";

			else if($valid == "valid")
				$where .= $and." ilr.is_valid = '1' ";

			else if($valid == "invalid")
				$where .= $and." ilr.is_valid != '1' ";
		}

		//filter active
		if($idarray['active'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$active = $idarray['active'];

			if($active == "all")
				$where .= $and." (ilr.is_active = '1' OR ilr.is_active != '1') ";

			else if($active == "active")
				$where .= $and." ilr.is_active = '1' ";

			else if($active == "inactive")
				$where .= $and." ilr.is_active != '1' ";
		}


		//filter submission
		if($idarray['submission'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$submission = $idarray['submission'];
			$where .= $and."  ilr.submission = '".$submission."' ";
		}

		//filter contract_year
		if($idarray['contract_year'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_year = $idarray['contract_year'];
			$where .= $and."  c.contract_year = '".$contract_year."' ";
		}

		//filter ethnicity_id
		if($idarray['ethnicity_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$ethnicity_id = $idarray['ethnicity_id'];
			$where .= $and."  tr.ethnicity = '".$ethnicity_id."' ";
		}

		//// where
		if($where != '')
		{
			$where = "WHERE ".$where;
		}

		///group by
		$group_by="";

		if(isset($idarray['group_by']) && $idarray['group_by'] != '')
		{
			$group_by = " GROUP BY ".$idarray['group_by'];
		}


		///order by
		$order_by="";

		if(isset($idarray['order_by']) && $idarray['order_by'] != '')
		{
			$order_by = " ORDER BY ".$idarray['order_by'];
		}

		$query = "SELECT ilr.*, c.*,
                tr.firstnames, tr.surname, concat(tr.firstnames,' ',tr.surname) AS learner_name, tr.gender, tr.uln as unique_learner_number, tr.dob, tr.home_email, users.username,
                tr.assessor as assessor_id, concat(assessorsng.firstnames,' ', assessorsng.surname) as assessor_name,
                tr.provider_id, providers.legal_name as training_provider_name,
                tr.employer_id, employers.legal_name as employer_name,
                ilr.contract_id, c.title as contract_name, c.contract_year,
                student_frameworks.id as framework_id, student_frameworks.title as framework_title,
                courses.id as course_id, courses.title as course_title,
                tr.ethnicity as ethnicity_id, lisl12.Ethnicity_Desc as ethnicity_description

                FROM ilr
                LEFT JOIN contracts c ON c.id = ilr.contract_id
                LEFT JOIN tr ON tr.id = ilr.tr_id
                LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
                LEFT JOIN organisations AS providers ON providers.id = tr.provider_id
                LEFT JOIN users ON users.username = tr.username
                LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
                LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
                LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
                LEFT JOIN courses ON courses.id = courses_tr.course_id
                LEFT JOIN lis201112.ilr_l12_ethnicity AS lisl12 ON lisl12.Ethnicity_Code = tr.ethnicity ".$where." ".$group_by." ".$order_by;//." LIMIT 5";

		//echo 'get_learners query = '.$query;//exit;
		//pre($query);
		$result = $link->query($query) or die('Error in get_ilrs '.pre($query));
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			//$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}



	public function get_ilrs_for_overall_and_timely_report(PDO $link, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		//echo 'idarray = <pre>';
		//print_r($idarray);exit;

		$where="";

		//filter employers
		if($idarray['employer_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$employer_id = $idarray['employer_id'];
			$where .= $and." tr.employer_id IN (".$employer_id.") ";
		}
		//filter contracts
		if($idarray['contract_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_id = $idarray['contract_id'];
			$where .= $and." ilr.contract_id IN (".$contract_id.") ";
		}
		//filter training providers
		if($idarray['training_provider_id'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$training_provider_id = $idarray['training_provider_id'];
			$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
		}

		/*//filter assessors
				if($idarray['assessor_id'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$assessor_id = $idarray['assessor_id'];
					$where .= $and."  tr.assessor = '".$assessor_id."' ";
				}



				//filter gender
				if($idarray['gender'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$gender = $idarray['gender'];
					$where .= $and."  tr.gender = '".$gender."' ";
				}

				//filter record_status
				if($idarray['course'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$course = $idarray['course'];
					$where .= $and."  courses.id = '".$course."' ";
				}

				//filter framework
				if($idarray['framework'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$framework = $idarray['framework'];
					$where .= $and."  student_frameworks.id = '".$framework."' ";
				}

				//filter valid
				if($idarray['valid'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$valid = $idarray['valid'];

					if($valid == "all")
						$where .= $and." (ilr.is_valid = '1' OR ilr.is_valid != '1') ";

					else if($valid == "valid")
						$where .= $and." ilr.is_valid = '1' ";

					else if($valid == "invalid")
						$where .= $and." ilr.is_valid != '1' ";
				}

				//filter active
				if($idarray['active'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$active = $idarray['active'];

					if($active == "all")
						$where .= $and." (ilr.is_active = '1' OR ilr.is_active != '1') ";

					else if($active == "active")
						$where .= $and." ilr.is_active = '1' ";

					else if($active == "inactive")
						$where .= $and." ilr.is_active != '1' ";
				}


				//filter submission
				if($idarray['submission'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$submission = $idarray['submission'];
					$where .= $and."  ilr.submission = '".$submission."' ";
				}*/

		//filter contract_year
		if($idarray['contract_year'] != '')
		{
			if($where != ''){ $and=" AND ";}else{$and = "";}
			$contract_year = $idarray['contract_year'];
			$where .= $and."  contract_year = '".$contract_year."' ";
		}


		/*if($where != '')
				{
					$where = "WHERE ".$where;
				}*/
		$where = "WHERE ilr.is_active =1
                    AND contracts.funding_body =2
                    AND submission = (
                    	SELECT MAX( submission )
                    	FROM ilr
                    	INNER JOIN contracts ON contracts.id = ilr.contract_id
                    	WHERE contract_year =$contract_year
                    )
                    AND funding_type =1 AND ".$where;


		$query = "SELECT ilr.*, contracts.* FROM ilr
                	INNER JOIN contracts ON contracts.id = ilr.contract_id
                	LEFT JOIN tr ON tr.id = ilr.tr_id
                	LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
                	LEFT JOIN organisations AS providers ON providers.id = tr.provider_id
                	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor ".$where;//." LIMIT 5";

		//echo 'get_learners query = '.$query;//exit;
		//pre($query);
		$result = $link->query($query) or die('Error in get_ilrs_for_overall_and_timely_report '.pre($query));
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";
			//$row['learner_count'] = (int)$row['learner_count'];
			array_push($details_arr, $row);
		}

		//echo 'details_arr = <pre>';
		//print_r($details_arr);exit;


		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_submissions(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query = "SELECT * FROM lookup_er_submissions";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_ssa_tier2(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query = "SELECT *, CONCAT(SSA_TIER2_CODE,' ',SSA_TIER2_DESC) AS code_and_title FROM lad201213.ssa_tier2_codes order by SSA_TIER2_CODE asc";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_ethnicities(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query = "SELECT Ethnicity_Desc AS description
                        FROM lis201112.ilr_l12_ethnicity
                        UNION SELECT Ethnicity_Desc
                        FROM lis201011.ilr_l12_ethnicity
                        ORDER BY description ASC";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}


	public function get_ethnicities_201112(PDO $link, $mode, $idarray=array())
	{
		$details_arr = array();
		$result_exist="false";

		$idstr = implode(",",$idarray);

		if($mode=="all")
		{
			$query = "SELECT *, Ethnicity_Code as ethnicity_id, Ethnicity_Desc AS description FROM lis201112.ilr_l12_ethnicity ORDER BY description ASC";
		}

		//echo 'get_filter_details query = '.$query;exit;
		$result = $link->query($query);
		//echo 'result = <pre>';
		//print_r($result);exit;
		while($row = $result->fetch())
		{
			$result_exist="true";

			array_push($details_arr, $row);
		}

		if($result_exist == "false")
		{
			$details_arr[0]="false";
		}
		return $details_arr;
	}

}
?>