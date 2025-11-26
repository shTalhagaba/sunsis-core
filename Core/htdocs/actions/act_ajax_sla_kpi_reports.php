<?php
set_time_limit(0);
ini_set("memory_limit","900000000000000000000000");

class ajax_sla_kpi_reports implements IAction
{
	public function execute(PDO $link)
	{
		error_reporting(E_ALL^E_NOTICE);
		//exit('in ajax_sla_kpi_reports');
		include_once('act_sla_kpi_reports.php');
		$obj_sla_kpi_reports = new sla_kpi_reports();


		$response = array();

		//save filter data
		if(isset($_REQUEST['save_filters']) && $_REQUEST['save_filters'] == 'save_filters')
		{
			//exit('in');
			//echo '<pre>';
			//print_r($_REQUEST);exit;
			//echo '<pre>';
			//print_r($_SESSION);exit;

			$user_id = $_SESSION['user']->id;
			$report_type = $_REQUEST['report_type'];

			$obj_sla_kpi_reports->save_filters($link, $user_id, $report_type, $_REQUEST);
		}

		//generate report data for graphs
		if(isset($_REQUEST['generate_report']) && $_REQUEST['generate_report'] == 'generate_report')
		{

			$report_type = $_REQUEST['report_type'];
			//exit("report_type = ".$report_type);

			$response['line_chart_details'] = array();
			$response['line_chart_details']['x_axis_categories'] = array();
			$response['line_chart_details']['series_data'] = array();


			$response['bar_chart_details'] = array();
			$response['bar_chart_details']['x_axis_categories'] = array();
			$response['bar_chart_details']['series_data'] = array();

			$response['pie_chart_details'] = array();
			$response['pie_chart_details']['series_data'] = array();


			$response['speedo_chart_details'] = array();
			$response['speedo_chart_details']['series_data'] = array();



			//////////////////////     Report sla_kpi_rep_achievers //////////////////////////

			if($report_type == "sla_kpi_rep_achievers")
			{
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value learners<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value learners<br>Click to return back";


				$chart_title = "Achievers";
				$x_axis_title = "Qualifications";
				$y_axis_title = "No. of Learners";
				//$x_axis_categories = array('X1','X2','X3','X4','X5');
				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " achievers";


				$from_date1 = $_REQUEST['from_date'];
				$to_date1 = $_REQUEST['to_date'];


				$where=" WHERE sq.achievement_date !='' ";
				if($from_date1 != '' && $to_date1 != '')
				{
					$from_date1 = str_replace('/', '-', $from_date1);
					$from_date = date("Y-m-d",strtotime($from_date1));

					$to_date1 = str_replace('/', '-', $to_date1);
					$to_date = date("Y-m-d",strtotime($to_date1));

					if($where != ''){ $and=" AND ";}else{$and = "";}
					$where .= $and." achievement_date >= '".$from_date."' AND achievement_date <= '".$to_date."' ";
				}

				//filter employers
				if($_REQUEST['employer'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$employer_id = $_REQUEST['employer'];
					$where .= $and." tr.employer_id IN (".$employer_id.") ";
				}
				//filter contracts
				if($_REQUEST['contract'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$contract_id = $_REQUEST['contract'];
					$where .= $and." tr.contract_id IN (".$contract_id.") ";
				}
				//filter training providers
				if($_REQUEST['training_provider'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$training_provider_id = $_REQUEST['training_provider'];
					$where .= $and." tr.provider_id IN (".$training_provider_id.") ";
				}
				//filter assessors
				if($_REQUEST['assessor'] != '')
				{
					if($where != ''){ $and=" AND ";}else{$and = "";}
					$assessor_id = $_REQUEST['assessor'];
					$where .= $and."  tr.assessor = '".$assessor_id."' ";
				}
				/*if($where != '')
            {
                $where = "WHERE ".$where;
            }*/


				$drill_down_by = $_REQUEST['drill_down_by'];

				$chart_subtitle = "From : ".$from_date1." To : ".$to_date1;

				$query="SELECT sq.internaltitle, count( tr.id ) AS learner_count
                    FROM student_qualifications sq
                    INNER JOIN tr ON tr.id = sq.tr_id
                     ".$where."
                    GROUP BY sq.internaltitle ORDER BY sq.internaltitle";

				//echo 'query = '.$query;exit;
				$result = $link->query($query);
//            echo 'result = <pre>';
//            print_r($result);exit;
				$response_result="no_data";
				while($row = $result->fetch())
				{
					$response_result="success";
					//echo 'result = <pre>';
					//print_r($row);
					$qualification_title = $row['internaltitle'];
					$learner_count = (int)$row['learner_count'];
					array_push($x_axis_categories,$qualification_title);
					array_push($chart_data,$learner_count);

					$learner_id_arr = array();

					if($where == ""){$where_new=" where sq.internaltitle='".$qualification_title."'";}else{$where_new = $where." and sq.internaltitle='".$qualification_title."'";}

					if($drill_down_by == 'employer')
					{
						$sql ="SELECT o.legal_name AS employer_name, count( tr.id ) AS learner_count
                            FROM organisations o
                            INNER JOIN tr ON o.id = tr.employer_id
                            INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                             ".$where_new."
                            GROUP BY o.id ORDER BY employer_name";
					}
					elseif($drill_down_by == 'training_provider')
					{
						$sql ="SELECT o.legal_name AS training_provider_name, count( tr.id ) AS learner_count
                            FROM organisations o
                            INNER JOIN tr ON o.id = tr.provider_id
                            INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                             ".$where_new."
                            GROUP BY o.id ORDER BY training_provider_name";
					}
					elseif($drill_down_by == 'contract')
					{
						$sql ="SELECT c.title AS contractor_name, count( tr.id ) AS learner_count
                            FROM contracts c
                            INNER JOIN tr ON c.id = tr.contract_id
                            INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                             ".$where_new."
                            GROUP BY c.id ORDER BY contractor_name";//o.legal_name
					}
					elseif($drill_down_by == 'assessor')
					{
						$sql ="SELECT concat( u.firstnames, u.surname ) AS assessor_name, count( tr.id ) AS learner_count
                            FROM users u
                            INNER JOIN tr ON u.id = tr.assessor
                            INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                             ".$where_new."
                            GROUP BY tr.assessor ORDER BY assessor_name";//assessor_name
					}

					$x_cat_val = $qualification_title;
					//echo "x_cat_val = ".$x_cat_val;exit;

					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


					$drilldown_categories = array();
					$drilldown_data = array();

					//echo '<br>sql = '.$sql.'<br>';
					$st = $link->query($sql);

					if($drill_down_by == "employer")
					{
						$drilldown_name = "Drilldown by Employers";
						$drilldown_x_axis_title = "Employers";

						while($row = $st->fetch())
						{
							$rows_exist="true";
							$qualification_title = $row['internaltitle'];
							$employer_name = $row['employer_name'];
							$learner_count = (int)$row['learner_count'];

							//echo 'employer_name = '.$employer_name." count = ".$learner_count."<br>";
							if($employer_name != "" && $learner_count != 0)
							{
								array_push($drilldown_categories,$employer_name);
								array_push($drilldown_data,$learner_count);
							}
						}

					}
					else if($drill_down_by == "training_provider")
					{
						$drilldown_name = "Drilldown by Training Providers";
						$drilldown_x_axis_title = "Training Providers";

						while($row = $st->fetch())
						{
							$rows_exist="true";
							$qualification_title = $row['internaltitle'];
							$training_provider_name = $row['training_provider_name'];
							$learner_count = (int)$row['learner_count'];

							//echo 'training_provider_name = '.$training_provider_name." count = ".$learner_count."<br>";
							if($training_provider_name != "" && $learner_count != 0)
							{
								array_push($drilldown_categories,$training_provider_name);
								array_push($drilldown_data,$learner_count);
							}

						}

					}
					else if($drill_down_by == "contract")
					{
						$drilldown_name = "Drilldown by Contractors";
						$drilldown_x_axis_title = "Contractors";

						while($row = $st->fetch())
						{
							$rows_exist="true";
							$qualification_title = $row['internaltitle'];
							$contractor_name = $row['contractor_name'];
							$learner_count = (int)$row['learner_count'];

							//echo 'contractor_name = '.$contractor_name." count = ".$learner_count."<br>";
							if($contractor_name != "" && $learner_count != 0)
							{
								array_push($drilldown_categories,$contractor_name);
								array_push($drilldown_data,$learner_count);
							}
						}
					}
					else if($drill_down_by == "assessor")
					{
						$drilldown_name = "Drilldown by Assessors";
						$drilldown_x_axis_title = "Assessors";

						while($row = $st->fetch())
						{
							$rows_exist="true";
							$qualification_title = $row['internaltitle'];
							$assessor_name = $row['assessor_name'];
							$learner_count = (int)$row['learner_count'];

							//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
							if($assessor_name != "" && $learner_count != 0)
							{
								array_push($drilldown_categories,$assessor_name);
								array_push($drilldown_data,$learner_count);
							}
						}
					}



					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

					$pie_drilldown_data = array();

					for($i=0;$i<count($drilldown_categories);$i++)
					{
						$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
					}
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;


					//echo '<pre>';
					//print_r($response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']);
				}
				//exit;
				//echo 'from_date = '.$from_date;exit;
				$final_total_achievers = (int)array_sum($chart_data);
				//echo "final_total_achievers = ".$final_total_achievers;exit;
				$speedo_chart_series_data = $final_total_achievers;

				$response['data_table']="";
				$data_table='<table class="CSSTableGenerator">
                        <tr>
                          <td><b>Total Qualifications</b></td>
                          <td><b>Total Achievers</b></td>
                        </tr>

                        <tr style="font-size: 20px;font-weight: bold;">
                          <td>'.count($x_axis_categories).'</td>
                          <td>'.$final_total_achievers.'</td>
                        </tr>
                        </table>';

				$response['data_table'] = $data_table;


			}

			//////////////////////     Report sla_kpi_rep_last_visit //////////////////////////

			else if($report_type == "sla_kpi_rep_last_visit")
			{
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value days";


				$idarray = array();
				//exit('sla_kpi_rep_last_visit');
				$chart_title = "Learner's last visit details";
				$x_axis_title = "Learners";
				$y_axis_title = "Days since last review";

				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " days";


				$from_date1 = $_REQUEST['from_date'];
				if($from_date1 != '')
				{
					$from_date1 = str_replace('/', '-', $from_date1);
					$from_date = date("Y-m-d",strtotime($from_date1));
					$idarray['from_date'] = $from_date1;
				}

				$to_date1 = $_REQUEST['to_date'];
				if($to_date1 != '')
				{
					$to_date1 = str_replace('/', '-', $to_date1);
					$to_date = date("Y-m-d",strtotime($to_date1));
					$idarray['to_date'] = $to_date1;
				}


				$assessor = $_REQUEST['assessor'];
				$idarray['assessor'] = $assessor;

				$contract = $_REQUEST['contract'];
				$idarray['contract'] = $contract;

				$employer = $_REQUEST['employer'];
				$idarray['employer'] = $employer;

				$training_provider = $_REQUEST['training_provider'];
				$idarray['training_provider'] = $training_provider;

				$chart_subtitle = "From : ".$from_date1." To : ".$to_date1;


				$details_arr = array();
				$details_arr = $obj_sla_kpi_reports->get_learner_last_visit_details($link, $idarray);
				//echo 'details_arr = <pre>';
				//print_r($details_arr);exit;
				if($details_arr[0] != 'false')
				{
					$response_result="success";

					foreach($details_arr as $details)
					{
						$learner_name = $details['learner_name'];
						$last_review_date = $details['last_review_date'];
						$cnv_last_review_date = date('Y-m-d',strtotime($last_review_date));
						if($from_date1 == "")
						{
							$from_date = $details['today'];
						}
						$diff = abs(strtotime($from_date) - strtotime($cnv_last_review_date));

						/* $years = floor($diff / (365*60*60*24));
                    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));*/
						$days = floor($diff/(60*60*24));
						//echo '<br>from_date = '.$from_date.' cnv_last_review_date = '.$cnv_last_review_date.' days = '.$days.'<br>';
						array_push($x_axis_categories,$learner_name);
						array_push($chart_data,$days);
					}
					//exit;
				}
				else
				{
					$response_result="no_data";
				}
			}




			//////////////////////     Report sla_kpi_rep_new_starts //////////////////////////

			elseif($report_type == "sla_kpi_rep_new_starts")//lib/kpi_reports/newstarts_period4.php
			{
				//exit('sla_kpi_rep_new_starts');
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value new learners joined<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value new learners joined<br>Click to return back";


				$chart_title = "New Starts";
				$x_axis_title = "Time Period";
				$y_axis_title = "Number of learners joined";

				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " new learners joined";



				$from_date1 = $_REQUEST['from_date'];
				$from_date1 = str_replace('/', '-', $from_date1);
				$from_date = date("Y-m-d",strtotime($from_date1));

				$to_date1 = $_REQUEST['to_date'];
				$to_date1 = str_replace('/', '-', $to_date1);
				$to_date = date("Y-m-d",strtotime($to_date1));

				$employer_id = $_REQUEST['employer'];
				$contract_id = $_REQUEST['contract'];
				$training_provider_id = $_REQUEST['training_provider'];
				$assessor_id = $_REQUEST['assessor'];

				$idarray = array();

				$idarray['from_date']=$from_date1;
				$idarray['to_date']=$to_date1;
				$idarray['employer_id']=$employer_id;
				$idarray['contract_id']=$contract_id;
				$idarray['training_provider_id']=$training_provider_id;
				$idarray['assessor_id']=$assessor_id;

				$drill_down_by = $_REQUEST['drill_down_by'];


				$chart_subtitle = "From : ".$from_date1." To : ".$to_date1;

				$details_arr = array();
				$details_arr = $obj_sla_kpi_reports->get_new_learners($link,$idarray);
				//echo 'details_arr = <pre>';
				//print_r($details_arr);exit;

				if($details_arr[0] != 'false')
				{
					$response_result="success";

					foreach($details_arr as $details)
					{
						$year = $details['year'];
						$learner_count = $details['learner_count'];

						array_push($x_axis_categories,$year);
						array_push($chart_data,$learner_count);

						$x_cat_val = $year;
						//echo "x_cat_val = ".$x_cat_val;exit;

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$drilldown_categories = array();
						$drilldown_data = array();

						$dtl_arr = array();
						$idarr = array();
						$idarr['from_date'] = $from_date1;
						$idarr['to_date'] = $to_date1;
						$idarr['year'] = $year;
						$idarr['employer_id']=$idarray['employer_id'];
						$idarr['contract_id']=$idarray['contract_id'];
						$idarr['training_provider_id']=$idarray['training_provider_id'];
						$idarr['assessor_id']=$idarray['assessor_id'];

						if($drill_down_by == "quarter")
						{
							$idarr['group_by'] = "quarter";

							$drilldown_name = "Drilldown by Quarter";
							$drilldown_x_axis_title = "Quarters";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$quarter_name = $dtl['quarter_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'quarter_name = '.$quarter_name." count = ".$learner_count."<br>";
									if($quarter_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$quarter_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "month")
						{
							$idarr['group_by'] = "month";

							$drilldown_name = "Drilldown by Month";
							$drilldown_x_axis_title = "Months";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$month_name = $dtl['month_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'month_name = '.$month_name." count = ".$learner_count."<br>";
									if($month_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$month_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "week")
						{
							$idarr['group_by'] = "week";

							$drilldown_name = "Drilldown by Week";
							$drilldown_x_axis_title = "Weeks";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$week = $dtl['week'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'week = '.$week." count = ".$learner_count."<br>";
									if($week != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$week);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "employer")
						{
							$drilldown_name = "Drilldown by Employer";
							$drilldown_x_axis_title = "Employers";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down_by_employer($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$employer_name = $dtl['employer_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'employer_name = '.$employer_name." count = ".$learner_count."<br>";
									if($employer_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$employer_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "training_provider")
						{
							$drilldown_name = "Drilldown by Training Providers";
							$drilldown_x_axis_title = "Training Providers";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down_by_training_provider($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$training_provider_name = $dtl['training_provider_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'training_provider_name = '.$training_provider_name." count = ".$learner_count."<br>";
									if($training_provider_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$training_provider_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "contract")
						{
							$drilldown_name = "Drilldown by Contract";
							$drilldown_x_axis_title = "Contracts";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down_by_contract($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$contract_name = $dtl['contract_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'contract_name = '.$contract_name." count = ".$learner_count."<br>";
									if($contract_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$contract_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "assessor")
						{
							$drilldown_name = "Drilldown by Assessors";
							$drilldown_x_axis_title = "Assessors";

							$dtl_arr = $obj_sla_kpi_reports->get_new_learners_drill_down_by_assessor($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$assessor_name = $dtl['assessor_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($assessor_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$assessor_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						/*echo 'drilldown_categories = <pre>';
                    print_r($drilldown_categories);
                    echo 'drilldown_data = <pre>';
                    print_r($drilldown_data);*/

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

						$pie_drilldown_data = array();

						for($i=0;$i<count($drilldown_categories);$i++)
						{
							$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
						}
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
					}

					$final_total_new_learners = (int)array_sum($chart_data);
					//echo "final_total_new_learners = ".$final_total_new_learners;exit;
					$speedo_chart_series_data = $final_total_new_learners;

					$response['data_table']="";
					$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Total new starts</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>'.$final_total_new_learners.'</td>
                            </tr>
                            </table>';

					$response['data_table'] = $data_table;
					//exit;
				}
				else
				{
					$response_result="no_data";
				}
			}



			//////////////////////     Report sla_kpi_rep_completions //////////////////////////

			elseif($report_type == "sla_kpi_rep_completions")//lib/kpi_reports/framework_achievers.php
			{
				//exit('sla_kpi_rep_completions');
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value completions<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value completions<br>Click to return back";


				$chart_title = "Completions";
				$x_axis_title = "Time Period";
				$y_axis_title = "Number of Completions";

				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " completions";



				$from_date1 = $_REQUEST['from_date'];
				$from_date1 = str_replace('/', '-', $from_date1);
				$from_date = date("Y-m-d",strtotime($from_date1));

				$to_date1 = $_REQUEST['to_date'];
				$to_date1 = str_replace('/', '-', $to_date1);
				$to_date = date("Y-m-d",strtotime($to_date1));

				$employer_id = $_REQUEST['employer'];
				$contract_id = $_REQUEST['contract'];
				$training_provider_id = $_REQUEST['training_provider'];
				$assessor_id = $_REQUEST['assessor'];

				$idarray = array();

				$idarray['from_date']=$from_date1;
				$idarray['to_date']=$to_date1;
				$idarray['employer_id']=$employer_id;
				$idarray['contract_id']=$contract_id;
				$idarray['training_provider_id']=$training_provider_id;
				$idarray['assessor_id']=$assessor_id;

				$drill_down_by = $_REQUEST['drill_down_by'];


				$chart_subtitle = "From : ".$from_date1." To : ".$to_date1;

				$details_arr = array();
				$details_arr = $obj_sla_kpi_reports->get_completions($link,$idarray);
				//echo 'details_arr = <pre>';
				//print_r($details_arr);exit;

				if($details_arr[0] != 'false')
				{
					$response_result="success";

					foreach($details_arr as $details)
					{
						$year = $details['year'];
						$learner_count = $details['learner_count'];

						array_push($x_axis_categories,$year);
						array_push($chart_data,$learner_count);

						$x_cat_val = $year;
						//echo "x_cat_val = ".$x_cat_val;exit;

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$drilldown_categories = array();
						$drilldown_data = array();

						$dtl_arr = array();
						$idarr = array();
						$idarr['from_date'] = $from_date1;
						$idarr['to_date'] = $to_date1;
						$idarr['year'] = $year;
						$idarr['employer_id']=$idarray['employer_id'];
						$idarr['contract_id']=$idarray['contract_id'];
						$idarr['training_provider_id']=$idarray['training_provider_id'];
						$idarr['assessor_id']=$idarray['assessor_id'];

						if($drill_down_by == "quarter")
						{
							$idarr['group_by'] = "quarter";

							$drilldown_name = "Drilldown by Quarter";
							$drilldown_x_axis_title = "Quarters";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$quarter_name = $dtl['quarter_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'quarter_name = '.$quarter_name." count = ".$learner_count."<br>";
									if($quarter_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$quarter_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "month")
						{
							$idarr['group_by'] = "month";

							$drilldown_name = "Drilldown by Month";
							$drilldown_x_axis_title = "Months";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$month_name = $dtl['month_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'month_name = '.$month_name." count = ".$learner_count."<br>";
									if($month_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$month_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "week")
						{
							$idarr['group_by'] = "week";

							$drilldown_name = "Drilldown by Week";
							$drilldown_x_axis_title = "Weeks";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$week = $dtl['week'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'week = '.$week." count = ".$learner_count."<br>";
									if($week != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$week);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "employer")
						{
							$drilldown_name = "Drilldown by Employer";
							$drilldown_x_axis_title = "Employers";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_employer($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$employer_name = $dtl['employer_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'employer_name = '.$employer_name." count = ".$learner_count."<br>";
									if($employer_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$employer_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "training_provider")
						{
							$drilldown_name = "Drilldown by Training Providers";
							$drilldown_x_axis_title = "Training Providers";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_training_provider($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$training_provider_name = $dtl['training_provider_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'training_provider_name = '.$training_provider_name." count = ".$learner_count."<br>";
									if($training_provider_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$training_provider_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "contract")
						{
							$drilldown_name = "Drilldown by Contract";
							$drilldown_x_axis_title = "Contracts";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_contract($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$contract_name = $dtl['contract_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'contract_name = '.$contract_name." count = ".$learner_count."<br>";
									if($contract_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$contract_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "assessor")
						{
							$drilldown_name = "Drilldown by Assessors";
							$drilldown_x_axis_title = "Assessors";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_assessor($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$assessor_name = $dtl['assessor_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($assessor_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$assessor_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						/*echo 'drilldown_categories = <pre>';
                    print_r($drilldown_categories);
                    echo 'drilldown_data = <pre>';
                    print_r($drilldown_data);*/

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

						$pie_drilldown_data = array();

						for($i=0;$i<count($drilldown_categories);$i++)
						{
							$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
						}
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
					}

					$final_total_completions = (int)array_sum($chart_data);
					//echo "final_total_completions = ".$final_total_completions;exit;
					$speedo_chart_series_data = $final_total_completions;

					$response['data_table']="";
					$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Total completions</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>'.$final_total_completions.'</td>
                            </tr>
                            </table>';

					$response['data_table'] = $data_table;
					//exit;
				}
				else
				{
					$response_result="no_data";
				}
			}



			//////////////////////     Report sla_kpi_rep_early_leavers //////////////////////////

			elseif($report_type == "sla_kpi_rep_early_leavers")//lib/kpi_reports/early_leavers.php
			{
				//exit('sla_kpi_rep_early_leavers');
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value early leavers<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value early leavers<br>Click to return back";


				$chart_title = "Early Leavers";
				$x_axis_title = "Time Period";
				$y_axis_title = "Number of Early Leavers";

				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " early leavers";



				$from_date1 = $_REQUEST['from_date'];
				$from_date1 = str_replace('/', '-', $from_date1);
				$from_date = date("Y-m-d",strtotime($from_date1));

				$to_date1 = $_REQUEST['to_date'];
				$to_date1 = str_replace('/', '-', $to_date1);
				$to_date = date("Y-m-d",strtotime($to_date1));

				$employer_id = $_REQUEST['employer'];
				$contract_id = $_REQUEST['contract'];
				$training_provider_id = $_REQUEST['training_provider'];
				$assessor_id = $_REQUEST['assessor'];

				$idarray = array();

				$idarray['from_date']=$from_date1;
				$idarray['to_date']=$to_date1;
				$idarray['employer_id']=$employer_id;
				$idarray['contract_id']=$contract_id;
				$idarray['training_provider_id']=$training_provider_id;
				$idarray['assessor_id']=$assessor_id;

				$drill_down_by = $_REQUEST['drill_down_by'];


				$chart_subtitle = "From : ".$from_date1." To : ".$to_date1;

				$details_arr = array();
				$idarray['mode']="early_leavers";
				$details_arr = $obj_sla_kpi_reports->get_completions($link,$idarray);
				//echo 'details_arr = <pre>';
				//print_r($details_arr);exit;

				if($details_arr[0] != 'false')
				{
					$response_result="success";

					foreach($details_arr as $details)
					{
						$year = $details['year'];
						$learner_count = $details['learner_count'];

						array_push($x_axis_categories,$year);
						array_push($chart_data,$learner_count);

						$x_cat_val = $year;
						//echo "x_cat_val = ".$x_cat_val;exit;

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$drilldown_categories = array();
						$drilldown_data = array();

						$dtl_arr = array();
						$idarr = array();
						$idarr['from_date'] = $from_date1;
						$idarr['to_date'] = $to_date1;
						$idarr['year'] = $year;
						$idarr['employer_id']=$idarray['employer_id'];
						$idarr['contract_id']=$idarray['contract_id'];
						$idarr['training_provider_id']=$idarray['training_provider_id'];
						$idarr['assessor_id']=$idarray['assessor_id'];
						$idarr['mode']="early_leavers";

						if($drill_down_by == "quarter")
						{
							$idarr['group_by'] = "quarter";

							$drilldown_name = "Drilldown by Quarter";
							$drilldown_x_axis_title = "Quarters";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$quarter_name = $dtl['quarter_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'quarter_name = '.$quarter_name." count = ".$learner_count."<br>";
									if($quarter_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$quarter_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "month")
						{
							$idarr['group_by'] = "month";

							$drilldown_name = "Drilldown by Month";
							$drilldown_x_axis_title = "Months";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$month_name = $dtl['month_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'month_name = '.$month_name." count = ".$learner_count."<br>";
									if($month_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$month_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "week")
						{
							$idarr['group_by'] = "week";

							$drilldown_name = "Drilldown by Week";
							$drilldown_x_axis_title = "Weeks";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$week = $dtl['week'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'week = '.$week." count = ".$learner_count."<br>";
									if($week != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$week);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "employer")
						{
							$drilldown_name = "Drilldown by Employer";
							$drilldown_x_axis_title = "Employers";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_employer($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$employer_name = $dtl['employer_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'employer_name = '.$employer_name." count = ".$learner_count."<br>";
									if($employer_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$employer_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "training_provider")
						{
							$drilldown_name = "Drilldown by Training Providers";
							$drilldown_x_axis_title = "Training Providers";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_training_provider($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$training_provider_name = $dtl['training_provider_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'training_provider_name = '.$training_provider_name." count = ".$learner_count."<br>";
									if($training_provider_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$training_provider_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "contract")
						{
							$drilldown_name = "Drilldown by Contract";
							$drilldown_x_axis_title = "Contracts";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_contract($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$contract_name = $dtl['contract_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'contract_name = '.$contract_name." count = ".$learner_count."<br>";
									if($contract_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$contract_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "assessor")
						{
							$drilldown_name = "Drilldown by Assessors";
							$drilldown_x_axis_title = "Assessors";

							$dtl_arr = $obj_sla_kpi_reports->get_completions_drill_down_by_assessor($link, $idarr);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$assessor_name = $dtl['assessor_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($assessor_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$assessor_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						/*echo 'drilldown_categories = <pre>';
                    print_r($drilldown_categories);
                    echo 'drilldown_data = <pre>';
                    print_r($drilldown_data);*/

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

						$pie_drilldown_data = array();

						for($i=0;$i<count($drilldown_categories);$i++)
						{
							$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
						}
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
					}

					$final_total_early_leavers = (int)array_sum($chart_data);
					//echo "final_total_early_leavers = ".$final_total_early_leavers;exit;
					$speedo_chart_series_data = $final_total_early_leavers;

					$response['data_table']="";
					$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Total no. of early leavers</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>'.$final_total_early_leavers.'</td>
                            </tr>
                            </table>';

					$response['data_table'] = $data_table;
					//exit;
				}
				else
				{
					$response_result="no_data";
				}
			}



			//////////////////////     Report sla_kpi_rep_retention //////////////////////////

			elseif($report_type == "sla_kpi_rep_retention")
			{
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value %<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value %<br>Click to return back";


				$chart_title = "Retention rates";
				$x_axis_title = "Contract year";
				$y_axis_title = "Percentage (%)";
				//$x_axis_categories = array('X1','X2','X3','X4','X5');
				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " learners";

				///get filters
				$idarray['employer_id'] = $employer_id = $_REQUEST['employer'];
				$idarray['contract_id'] = $contract_id = $_REQUEST['contract'];
				$idarray['training_provider_id'] = $training_provider_id = $_REQUEST['training_provider'];
				$idarray['assessor_id'] = $assessor_id = $_REQUEST['assessor'];
				$idarray['gender'] = $gender = $_REQUEST['gender'];
				$idarray['course'] = $course = $_REQUEST['course'];
				$idarray['framework'] = $framework = $_REQUEST['framework'];
				$idarray['valid'] = $valid = $_REQUEST['valid'];
				$idarray['active'] = $active = $_REQUEST['active'];
				$idarray['submission'] = $submission = $_REQUEST['submission'];
				$idarray['contract_year'] = $contract_year = $_REQUEST['contract_year'];

				///get drilldown filter value
				$drill_down_by = $_REQUEST['drill_down_by'];


				$chart_subtitle = "For Contract year: ".$contract_year;

				$details_arr = array();

				$details_arr = $obj_sla_kpi_reports->get_ilrs($link,$idarray);
				//echo 'details_arr = <br>';
				//pre($details_arr);exit;

				if($details_arr[0] != 'false')
				{
					self::createTempTableForRetentionReport($link);
					$response_result="success";

					foreach($details_arr as $row)
					{
						//$assessor_id = $row['assessor_id'];
						//$assessor_name = $row['assessor_name'];
						$provider_id = $row['provider_id'];
						$training_provider_name = $row['training_provider_name'];
						//$employer_id = $row['employer_id'];
						//$employer_name = $row['employer_name'];
						$contract_id = $row['contract_id'];
						$contract_name = $row['contract_name'];
						//$framework_id = $row['framework_id'];
						$framework_title = ($row['framework_title']);
						$course_id = $row['course_id'];
						$course_title = ($row['course_title']);
						//$ethnicity_id = $row['ethnicity_id'];
						$ethnicity_description = ($row['ethnicity_description']);
						$cntrct_year = $row['contract_year'];

						//echo "<br>".$row['contract_year'];
						if($row['contract_year']<2012)
						{
							$ilr = Ilr2011::loadFromXML($row['ilr']);
							//pre($ilr);
							if($ilr->learnerinformation->L08!="Y")
							{
								if(($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0"))
								{
									$l03 = $row['L03'];
									$a09 = $ilr->programmeaim->A09;
									$tr_id = $row['tr_id'];
									$gender = $ilr->learnerinformation->L13;
									$ssa = '';
									$ethnicity = $ilr->learnerinformation->L12;
									//$surname = $ilr->learnerinformation->L09;
									//$firstnames = $ilr->learnerinformation->L10;
									$a27 = Date::toMySQL($ilr->programmeaim->A27);
									$a31 = $ilr->programmeaim->A31;
									if($a31=='' || $a31=='dd/mm/yyyy' || $a31=='')
										$a31 = "NULL";
									else
										$a31 = "'" . Date::toMySQL($a31) . "'";
									$fcode = $ilr->programmeaim->A26;
									$prog_type = $ilr->programmeaim->A15;
									$comp_status = $ilr->programmeaim->A34;
									$assessor = '';
									$employer = '';

									//DAO::execute($link, "insert into sla_kpi_retention_new values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type','$assessor_id', '$assessor_name', '$provider_id', '$training_provider_name', '$employer_id', '$employer_name', '$contract_id', '$contract_name', '$framework_id','$framework_title', '$course_id', '$course_title', '$ethnicity_id', '$ethnicity_description', '$cntrct_year')");
									DAO::execute($link, "insert into sla_kpi_retention_new values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type', '$provider_id', '$training_provider_name', '$contract_id', '$contract_name', '$framework_title', '$course_id', '$course_title', '$ethnicity_description', '$cntrct_year')");
								}

								for($a = 0; $a<=$ilr->subaims; $a++)
								{
									$l03 = $row['L03'];
									$a09 = $ilr->aims[$a]->A09;
									$tr_id = $row['tr_id'];
									$gender = $ilr->learnerinformation->L13;
									$ssa = '';
									$ethnicity = $ilr->learnerinformation->L12;
									$surname = $ilr->learnerinformation->L09;
									$firstnames = $ilr->learnerinformation->L10;
									$a27 = Date::toMySQL($ilr->aims[$a]->A27);
									$a31 = $ilr->aims[$a]->A31;
									if($a31=='' || $a31=='dd/mm/yyyy' || $a31=='')
										$a31 = "NULL";
									else
										$a31 = "'" . Date::toMySQL($a31) . "'";
									$fcode = $ilr->aims[$a]->A26;
									$prog_type = $ilr->aims[$a]->A15;
									$comp_status = $ilr->aims[$a]->A34;
									$assessor = '';
									$employer = '';

									//DAO::execute($link, "insert into sla_kpi_retention_new values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type','$assessor_id', '$assessor_name', '$provider_id', '$training_provider_name', '$employer_id', '$employer_name', '$contract_id', '$contract_name', '$framework_id','$framework_title', '$course_id', '$course_title', '$ethnicity_id', '$ethnicity_description', '$cntrct_year')");
									DAO::execute($link, "insert into sla_kpi_retention_new values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type', '$provider_id', '$training_provider_name', '$contract_id', '$contract_name', '$framework_title', '$course_id', '$course_title', '$ethnicity_description', '$cntrct_year')");
								}
							}
						}
						else
						{
							$ilr = Ilr2012::loadFromXML($row['ilr']);
							foreach($ilr->LearningDelivery as $delivery)
							{
								$l03 = $row['L03'];
								$a09 = $delivery->LearnAimRef;
								$tr_id = $row['tr_id'];
								$gender = $ilr->Sex;
								$ssa = '';
								$ethnicity = $ilr->Ethnicity;
								$surname = addslashes((string)$ilr->FamilyName);
								$firstnames = addslashes((string)$ilr->GivenNames);
								$a27 = Date::toMySQL($delivery->LearnStartDate);
								$a31 = $delivery->LearnActEndDate;
								if($a31=='' || $a31=='dd/mm/yyyy' || $a31=='')
									$a31 = "NULL";
								else
									$a31 = "'" . Date::toMySQL($a31) . "'";
								$fcode = ($delivery->FworkCode=='undefined')?'':$delivery->FworkCode;
								$prog_type = $delivery->ProgType;
								$comp_status = $delivery->CompStatus;
								$assessor = '';
								$employer = '';

								//DAO::execute($link, "insert into sla_kpi_retention_new values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type','$assessor_id', '$assessor_name', '$provider_id', '$training_provider_name', '$employer_id', '$employer_name', '$contract_id', '$contract_name', '$framework_id','$framework_title', '$course_id', '$course_title', '$ethnicity_id', '$ethnicity_description', '$cntrct_year')");
								DAO::execute($link, "insert into sla_kpi_retention_new values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type', '$provider_id', '$training_provider_name', '$contract_id', '$contract_name', '$framework_title', '$course_id', '$course_title', '$ethnicity_description', '$cntrct_year')");
							}
						}
					}

					DAO::execute($link, "UPDATE sla_kpi_retention_new INNER JOIN lad201213.`all_annual_values` ON lad201213.`all_annual_values`.`LEARNING_AIM_REF` = a09 INNER JOIN lad201213.`ssa_tier1_codes` ON lad201213.`all_annual_values`.`SSA_TIER1_CODE` = lad201213.`ssa_tier1_codes`.`SSA_TIER1_CODE` SET ssa=SSA_TIER1_DESC;");
					DAO::execute($link, "UPDATE sla_kpi_retention_new SET ssa=a09 where ssa = '' or ssa is null;");
					DAO::execute($link, "UPDATE sla_kpi_retention_new INNER JOIN tr on tr.id = sla_kpi_retention_new.tr_id inner join users on users.username = tr.assessor set sla_kpi_retention_new.assessor = concat(users.firstnames,' ',users.surname)");
					DAO::execute($link, "UPDATE sla_kpi_retention_new INNER JOIN tr on tr.id = sla_kpi_retention_new.tr_id inner join organisations on organisations.id = tr.employer_id set sla_kpi_retention_new.employer = organisations.legal_name");


					$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, contract_year FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY contract_year;",$options=DAO::FETCH_BOTH);

					$p = $p[0];
					//pre($p);


					$contract_year = $p['contract_year'];
					$idarray['contract_year'] = $contract_year;

					$percentage = (float)sprintf("%.2f",100 - ($p['withdrawn']/$p['learners']*100));


					if($drill_down_by == "assessor")
					{
						$drilldown_name = "Drilldown by Assessors";
						$drilldown_x_axis_title = "Assessors";
						$drilldown_col_key = "assessor";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, assessor FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY assessor;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "contract")
					{
						$drilldown_name = "Drilldown by Contractors";
						$drilldown_x_axis_title = "Contractors";
						$drilldown_col_key = "contract_name";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, contract_name FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY contract_id;",$options=DAO::FETCH_BOTH);
					}

					elseif($drill_down_by == "employer")
					{
						$drilldown_name = "Drilldown by Employers";
						$drilldown_x_axis_title = "Employers";
						$drilldown_col_key = "employer";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, employer FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY employer;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "training_provider")
					{
						$drilldown_name = "Drilldown by Training Providers";
						$drilldown_x_axis_title = "Training Providers";
						$drilldown_col_key = "training_provider_name";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, training_provider_name FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY provider_id;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "gender")
					{
						$drilldown_name = "Drilldown by Gender";
						$drilldown_x_axis_title = "Gender";
						$drilldown_col_key = "gender";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,gender FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY gender;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "course")
					{
						$drilldown_name = "Drilldown by Course";
						$drilldown_x_axis_title = "Course";
						$drilldown_col_key = "course_title";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, course_title FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY course_id;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "ethnicity")
					{
						$drilldown_name = "Drilldown by Ethnicity";
						$drilldown_x_axis_title = "Ethnicity";
						$drilldown_col_key = "ethnicity_description";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, ethnicity_description FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY ethnicity;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "area_of_learning")
					{
						$drilldown_name = "Drilldown by Area of Learning";
						$drilldown_x_axis_title = "Area of Learning";
						$drilldown_col_key = "ssa";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, ssa  FROM sla_kpi_retention_new WHERE a09 != 'ZPROG001' AND prog_type IN (2,3,20,21) GROUP BY ssa;",$options=DAO::FETCH_BOTH);
					}

					else if($drill_down_by == "frameworks")
					{
						$drilldown_name = "Drilldown by Frameworks";
						$drilldown_x_axis_title = "Frameworks";
						$drilldown_col_key = "framework_title";

						$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,(SELECT CONCAT(FRAMEWORK_CODE,' - ',FRAMEWORK_DESC) FROM lad201213.frameworks AS f WHERE f.FRAMEWORK_CODE = fcode AND f.FRAMEWORK_TYPE_CODE = prog_type LIMIT 0,1) AS framework_title FROM sla_kpi_retention_new WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY fcode;",$options=DAO::FETCH_BOTH);
					}

					//echo 'drill = ';pre($p);

					$drilldown_categories = array();
					$drilldown_data = array();
					$final_total_learners = 0;
					$final_total_withdrawn = 0;
					$final_total_non_starters = 0;

					foreach($p as $framework)
					{//pr($framework);
						if($framework['learners']>0)
						{
							$x_val_title = $framework[$drilldown_col_key];
							$total_learners = $framework['learners'];
							$total_withdrawn = $framework['withdrawn'];
							$total_non_starters = $framework['non'];
							$x_val = (float)sprintf("%.2f",100 - ($framework['withdrawn']/$framework['learners']*100));

							$x_val_title = $x_val_title."<br><br>Learners: ".$total_learners." Withdrawn: ".$total_withdrawn." Non-starters: ".$total_non_starters;
							array_push($drilldown_categories,$x_val_title);
							array_push($drilldown_data,$x_val);

							$final_total_learners = $final_total_learners+$total_learners;
							$final_total_withdrawn = $final_total_withdrawn+$total_withdrawn;
							$final_total_non_starters = $final_total_non_starters+$total_non_starters;
						}
					}
					//pr($drilldown_categories);pre($drilldown_data);

					$x_cat_val = $contract_year;

					$x_cat_val = $x_cat_val."<br>Learners: ".$final_total_learners." Withdrawn: ".$final_total_withdrawn." Non-starters: ".$final_total_non_starters;
					array_push($x_axis_categories,$x_cat_val);
					array_push($chart_data,$percentage);


					//echo "x_cat_val = ".$x_cat_val;exit;

					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();



					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

					$pie_drilldown_data = array();

					for($i=0;$i<count($drilldown_categories);$i++)
					{
						$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
					}
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;


					//echo "final_total_learners = ".$final_total_learners;exit;
					$speedo_chart_title = "Total Learners";
					$speedo_chart_series_data = $final_total_learners;

					$response['data_table']="";
					$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Contract year</b></td>
                              <td><b>Total Learners</b></td>
                              <td><b>Withdrawn</b></td>
                              <td><b>Non-starters</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>'.$contract_year.'</td>
                              <td>'.$final_total_learners.'</td>
                              <td>'.$final_total_withdrawn.'</td>
                              <td>'.$final_total_non_starters.'</td>
                            </tr>
                            </table>';

					$response['data_table'] = $data_table;
				}
				else
				{
					$response_result="no_data";
				}
			}





			//////////////////////     Report sla_kpi_rep_overall_success  AND  Report sla_kpi_rep_timely_success    //////////////////////////

			elseif($report_type == "sla_kpi_rep_overall_success" || $report_type == "sla_kpi_rep_timely_success")
			{
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value %<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value %<br>Click to return back";

				if($report_type == 'sla_kpi_rep_overall_success')
				{
					$chart_title = "Overall Success rates";
				}
				else if($report_type == 'sla_kpi_rep_timely_success')
				{
					$chart_title = "Timely Success rates";
				}

				$x_axis_title = "Fiscal year";
				$y_axis_title = "Percentage (%)";
				//$x_axis_categories = array('X1','X2','X3','X4','X5');
				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " learners";

				///get filters
				$idarray['employer_id'] = $employer_id = $_REQUEST['employer'];
				$idarray['contract_id'] = $contract_id = $_REQUEST['contract'];
				$idarray['training_provider_id'] = $training_provider_id = $_REQUEST['training_provider'];


				//$chart_subtitle = "Classified by Fiscal Years";


				// Loop through all the contracts starting with the most recent
				$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
				//	$link->query("truncate success_rates");
				self::createTempTableForOverallAndTimelyReport($link);

				$values = '';
				$counter = 0;
				$data = array();

				for($year = $current_contract_year; $year>= ($current_contract_year-4); $year--)
				{
					$idarray['contract_year'] = $year;

					$details_arr = array();

					$details_arr = $obj_sla_kpi_reports->get_ilrs_for_overall_and_timely_report($link,$idarray);
					//echo 'details_arr = <br>';
					//pre($details_arr);exit;

					if($details_arr[0] != 'false')
					{
						$response_result="success";

						foreach($details_arr as $row)
						{
							if($row['contract_year']<2012)
							{
								$ilr = Ilr2011::loadFromXML($row['ilr']);
								$tr_id = $row['tr_id'];
								$submission = $row['submission'];
								$l03 = $row['L03'];
								$contract_id = $row['contract_id'];
								$p_prog_status = -1;

								if($ilr->learnerinformation->L08!="Y")
								{
									if(($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0"))
									{
										$programme_type = "Apprenticeship";
										$start_date = Date::toMySQL($ilr->programmeaim->A27);
										$end_date = Date::toMySQL($ilr->programmeaim->A28);

										// Age Band Calculation
										if($ilr->learnerinformation->L11!='00/00/0000' && $ilr->learnerinformation->L11!='00000000')
										{
											$dob = $ilr->learnerinformation->L11;
											$dob = Date::toMySQL($dob);
											$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
										}
										else
										{
											$age = '';
										}
										if($age<=18)
											$age_band = "16-18";
										elseif($age<=24)
											$age_band = "19-24";
										elseif($age>=25)
											$age_band = "25+";
										else
											$age_band = "Unknown";

										if($ilr->programmeaim->A31!='00000000' && $ilr->programmeaim->A31!='00/00/0000' && $ilr->programmeaim->A31!='')
											$actual_date = Date::toMySQL($ilr->programmeaim->A31);
										else
											$actual_date = "0000-00-00";

										if($ilr->programmeaim->A40!='00000000' && $ilr->programmeaim->A40!='00/00/0000' && $ilr->programmeaim->A40!='')
											$achievement_date = Date::toMySQL($ilr->programmeaim->A40);
										else
											$achievement_date = "0000-00-00";

										$level = $ilr->programmeaim->A15;


										// Calculation for p_prog_status for apprenticeship only
										if($ilr->programmeaim->A15=='2' || $ilr->programmeaim->A15=='3' || $ilr->programmeaim->A15=='10')
										{
											$p_prog_status = 7;
											if($actual_date=='0000-00-00')
												$p_prog_status = 0;
											if($achievement_date!='' && $achievement_date!='0000-00-00')
												$p_prog_status = 1;
											if($actual_date!='0000-00-00' && ($ilr->programmeaim->A35==4 || $ilr->programmeaim->A35==5) && $achievement_date!='0000-00-00')
												$p_prog_status = 3;
											if($ilr->aims[0]->A40!='00000000' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
												$p_prog_status = 4;
											if($ilr->aims[0]->A40!='00000000' && $actual_date=='0000-00-00')
												$p_prog_status = 5;
											if($ilr->aims[0]->A40=='00000000' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
												$p_prog_status = 6;
											if($ilr->programmeaim->A34==3)
												$p_prog_status = 13;
											if($ilr->programmeaim->A34==4 || $ilr->programmeaim->A34==5)
												$p_prog_status = 8;
											if($ilr->programmeaim->A50==2)
												$p_prog_status = 9;
											if($ilr->programmeaim->A50==7)
												$p_prog_status = 10;
											if($ilr->programmeaim->A34==6)
												$p_prog_status = 11;
											if(($ilr->programmeaim->A40!='00000000' || $ilr->programmeaim->A40!='')&& $ilr->programmeaim->A34==6)
												$p_prog_status = 12;

										}

										$a23 = $ilr->programmeaim->A23;

										$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
										if($local_authority=='')
										{
											$postcode = str_replace(" ","",$a23);
											$page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
											$local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
											$local_authority = str_replace("<strong>District</strong>","",$local_authority);
											$local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
											$local_authority = @str_replace("City Council","",$local_authority);
											$local_authority = @str_replace("District","",$local_authority);
											$local_authority = @str_replace("Council","",$local_authority);
											$local_authority = @str_replace("Borough","",$local_authority);
											if($local_authority=="")
												$local_authority="Not Found";
											$local_authority = str_replace("'","\'",$local_authority);
											DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
										}
										$local_authority = str_replace("'","\'",$local_authority);

										$a26 = $ilr->programmeaim->A26;
										$a09 = $ilr->aims[0]->A09;

										$ukprn = $ilr->aims[0]->A22;
										if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
										{
											$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
										}
										else
										{
											$provider = '';
										}


										$ethnicity = $ilr->learnerinformation->L12;

										$d = array();
										$d['l03'] = $l03;
										$d['tr_id'] = $tr_id;
										$d['programme_type'] = $programme_type;
										$d['start_date'] = $start_date;
										$d['planned_end_date'] = $end_date;
										$d['actual_end_date'] = $actual_date;
										$d['achievement_date'] = $achievement_date;
										$d['expected'] = 0;
										$d['actual'] = 0;
										$d['hybrid'] = 0;
										$d['p_prog_status'] = $p_prog_status;
										$d['contract_id'] = $contract_id;
										$d['submission'] = $submission;
										$d['level'] = $level;
										$d['age_band'] = $age_band;
										$d['a09'] = $a09;
										$d['local_authority'] = $local_authority;
										$d['region'] = $a23;
										$d['postcode'] = $a23;
										$d['sfc'] = $a26;
										$d['ssa1'] = '';
										$d['ssa2'] = '';
										//$d['glh'] = $glh;
										$d['employer'] = '';
										$d['assessor'] = '';
										$d['provider'] = $provider;
										$d['contractor'] = '';
										$d['ethnicity']	= $ethnicity;
										$data[] = $d;

										//$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, $p_prog_status, $contract_id, '$submission', '$level','$age_band','$a09','$local_authority','$a23','$a23','$a26','$ssa1','$ssa2','$employer','$assessor','$provider','$contractor','$ethnicity'),";
									}
									else
									{

										for($a = 0; $a<=$ilr->subaims; $a++)
										{
											// Calclation of A_TTGAIN

											if( ($ilr->aims[$a]->A10=='45' || $ilr->aims[$a]->A10=='46' || $ilr->aims[$a]->A10=='60') && ($ilr->aims[$a]->A15!='2' && $ilr->aims[$a]->A15!='3' && $ilr->aims[$a]->A15!='10') && ($ilr->aims[$a]->A46a!='83' && $ilr->aims[$a]->A46b!='83'))
											{

												// Age Band Calculation
												if(($ilr->aims[$a]->A18=='24' || $ilr->aims[$a]->A18=='23' || $ilr->aims[$a]->A18=='22') && $ilr->aims[$a]->A46a!='125')
													$programme_type = "Workplace";
												elseif($ilr->aims[$a]->A18=='1' || $ilr->aims[$a]->A46a=='125')
													$programme_type = "Classroom";
												else
													$programme_type = "Unknown";
												$start_date = Date::toMySQL($ilr->aims[$a]->A27);
												$end_date = Date::toMySQL($ilr->aims[$a]->A28);

												if($ilr->learnerinformation->L11!='00/00/0000')
												{
													$dob = $ilr->learnerinformation->L11;
													$dob = Date::toMySQL($dob);
													$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
												}
												else
												{
													$age = '';
												}
												if($age<=18)
													$age_band = "16-18";
												elseif($age<=24)
													$age_band = "19-24";
												elseif($age>=25)
													$age_band = "25+";
												else
													$age = "Unknown";

												if($ilr->aims[$a]->A31!='00000000' && $ilr->aims[$a]->A31!='00/00/0000' && $ilr->aims[$a]->A31!='')
													$actual_date = Date::toMySQL($ilr->aims[$a]->A31);
												else
													$actual_date = "0000-00-00";

												if($ilr->aims[$a]->A40!='00000000' && $ilr->aims[$a]->A40!='00/00/0000' && $ilr->aims[$a]->A40!='')
													$achievement_date = Date::toMySQL($ilr->aims[$a]->A40);
												else
													$achievement_date = "0000-00-00";

												$level = $ilr->aims[$a]->A15;
												$a09 = $ilr->aims[$a]->A09;

												// Calculation for p_prog_status for apprenticeship only
												$p_prog_status = 7;
												if($actual_date=='0000-00-00')
													$p_prog_status =0;
												if($achievement_date!='0000-00-00')
													$p_prog_status = 1;
												if($actual_date!='0000-00-00' && ($ilr->aims[$a]->A35==4 || $ilr->aims[$a]->A35==5) && $achievement_date=='0000-00-00')
													$p_prog_status = 3;
												if($actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
													$p_prog_status = 6;
												if($ilr->aims[$a]->A34==3)
													$p_prog_status = 13;
												if($ilr->aims[$a]->A34==4 || $ilr->aims[$a]->A34==5)
													$p_prog_status = 8;
												if($ilr->aims[$a]->A50==2)
													$p_prog_status = 9;
												if($ilr->aims[$a]->A50==7)
													$p_prog_status = 10;
												if($ilr->aims[$a]->A34==6)
													$p_prog_status = 11;

												$a23 = trim($ilr->aims[0]->A23);

												if(strlen($a23)>8)
													pre("Postcode " . $a23 . " is not correct");

												$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
												if($local_authority=='')
												{
													$postcode = str_replace(" ","",$a23);
													$page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
													$local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
													$local_authority = str_replace("<strong>District</strong>","",$local_authority);
													$local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
													$local_authority = @str_replace("City Council","",$local_authority);
													$local_authority = @str_replace("District","",$local_authority);
													$local_authority = @str_replace("Council","",$local_authority);
													$local_authority = @str_replace("Borough","",$local_authority);
													if($local_authority=='')
														$local_authority="Not Found";
													$local_authority = str_replace("'","\'",$local_authority);
													DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
												}
												$local_authority = str_replace("'","\'",$local_authority);

												$a09 = $ilr->aims[0]->A09;
												$a26 = $ilr->aims[0]->A26;


												$ukprn = $ilr->aims[$a]->A22;
												if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
												{
													$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
												}
												else
												{
													$provider = '';
												}

												$provider = addslashes((string)$provider);
												$ethnicity = $ilr->learnerinformation->L12;

												$d = array();
												$d['l03'] = $l03;
												$d['tr_id'] = $tr_id;
												$d['programme_type'] = $programme_type;
												$d['start_date'] = $start_date;
												$d['planned_end_date'] = $end_date;
												$d['actual_end_date'] = $actual_date;
												$d['achievement_date'] = $achievement_date;
												$d['expected'] = 0;
												$d['actual'] = 0;
												$d['hybrid'] = 0;
												$d['p_prog_status'] = $p_prog_status;
												$d['contract_id'] = $contract_id;
												$d['submission'] = $submission;
												$d['level'] = $level;
												$d['age_band'] = $age_band;
												$d['a09'] = $a09;
												$d['local_authority'] = $local_authority;
												$d['region'] = $a23;
												$d['postcode'] = $a23;
												$d['sfc'] = $a26;
												$d['ssa1'] = '';
												$d['ssa2'] = '';
												//$d['glh'] = $glh;
												$d['employer'] = '';
												$d['assessor'] = '';
												$d['provider'] = $provider;
												$d['contractor'] = '';
												$d['ethnicity']	= $ethnicity;
												$data[] = $d;


											}
										}
									}

									$counter++;
								}
							}
							else
							{
								$ilr = Ilr2012::loadFromXML($row['ilr']);
								$tr_id = $row['tr_id'];
								$submission = $row['submission'];
								$l03 = $row['L03'];
								$contract_id = $row['contract_id'];
								$p_prog_status = -1;

								foreach($ilr->LearningDelivery as $delivery)
								{
									if($delivery->AimType==1 && $delivery->ProgType!='99')
									{
										$programme_type = "Apprenticeship";
										$a26 = "".$delivery->FworkCode;
										$start_date = Date::toMySQL("".$delivery->LearnStartDate);
										$end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
										if(("".$ilr->DateOfBirth)!='00/00/0000' && ("".$ilr->DateOfBirth)!='00000000')
										{
											$dob = "".$ilr->DateOfBirth;
											$dob = Date::toMySQL($dob);
											$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
										}
										else
										{
											$age = '';
										}
										// Age Band Calculation
										if($age<=18)
											$age_band = "16-18";
										elseif($age<=24)
											$age_band = "19-24";
										elseif($age>=25)
											$age_band = "25+";
										else
											$age_band = "Unknown";

										if($delivery->LearnActEndDate!='00000000' && $delivery->LearnActEndDate!='00/00/0000' && $delivery->LearnActEndDate!='')
											$actual_date = Date::toMySQL($delivery->LearnActEndDate);
										else
											$actual_date = "0000-00-00";

										if($delivery->AchDate!='00000000' && $delivery->AchDate!='00/00/0000' && $delivery->AchDate!='')
											$achievement_date = Date::toMySQL($delivery->AchDate);
										else
											$achievement_date = "0000-00-00";

										$level = "".$delivery->ProgType;

										// Calculation for p_prog_status for apprenticeship only
										if($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10')
										{
											$p_prog_status = 7;
											if($actual_date=='0000-00-00')
												$p_prog_status = 0;
											if($achievement_date!='' && $achievement_date!='0000-00-00')
												$p_prog_status = 1;
											if($actual_date!='0000-00-00' && ($delivery->Outcome=='4' || $delivery->Outcome=='5') && $achievement_date!='0000-00-00')
												$p_prog_status = 3;
											if($achievement_date && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
												$p_prog_status = 4;
											if($achievement_date && $actual_date=='0000-00-00')
												$p_prog_status = 5;
											if($achievement_date && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
												$p_prog_status = 6;
											if($delivery->CompStatus=='3')
												$p_prog_status = 13;
											if($delivery->CompStatus==4 || $delivery->CompStatus==5)
												$p_prog_status = 8;
											if($delivery->WithdrawReason==2)
												$p_prog_status = 9;
											if($delivery->WithdrawReason==7)
												$p_prog_status = 10;
											if($delivery->CompStatus==6)
												$p_prog_status = 11;
											if( ($delivery->AchDate!='00000000' || $delivery->AchDate!='') && $delivery->CompStatus==6)
												$p_prog_status = 12;
										}
										$a23 = "" . $delivery->DelLocPostCode;
										$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
										if($local_authority=='')
										{
											$postcode = str_replace(" ","",$a23);
											$page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
											$local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
											$local_authority = str_replace("<strong>District</strong>","",$local_authority);
											$local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
											$local_authority = @str_replace("City Council","",$local_authority);
											$local_authority = @str_replace("District","",$local_authority);
											$local_authority = @str_replace("Council","",$local_authority);
											$local_authority = @str_replace("Borough","",$local_authority);
											if($local_authority=="")
												$local_authority="Not Found";
											$local_authority = str_replace("'","\'",$local_authority);
											DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
										}
										$local_authority = str_replace("'","\'",$local_authority);

										$a09 = '';
										foreach($ilr->LearningDelivery as $d)
										{
											if($d->AimType==1 || $d->AimType==4)
											{
												$a09 = "".$d->LearnAimRef;
												$ukprn = "".$d->PartnerUKPRN;
											}

										}
										//if($a09!='')
										//{
										//		$ssa1 = DAO::getSingleValue($link, "SELECT CONCAT(lad200910.SSA_TIER1_CODES.SSA_TIER1_CODE,' ',lad200910.SSA_TIER1_CODES.SSA_TIER1_DESC) FROM lad200910.SSA_TIER1_CODES INNER JOIN lad200910.ALL_ANNUAL_VALUES ON lad200910.ALL_ANNUAL_VALUES.SSA_TIER1_CODE = lad200910.SSA_TIER1_CODES.SSA_TIER1_CODE WHERE ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09';");
										//			$ssa2 = DAO::getSingleValue($link, "SELECT CONCAT(lad200910.SSA_TIER2_CODES.SSA_TIER2_CODE,' ',lad200910.SSA_TIER2_CODES.SSA_TIER2_DESC) FROM lad200910.SSA_TIER2_CODES INNER JOIN lad200910.ALL_ANNUAL_VALUES ON lad200910.ALL_ANNUAL_VALUES.SSA_TIER2_CODE = lad200910.SSA_TIER2_CODES.SSA_TIER2_CODE WHERE lad200910.ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09'");
										//		}

										if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
										{
											$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
										}
										else
										{
											$provider = '';
										}

										$provider = addslashes((string)$provider);
										$ethnicity = "".$ilr->Ethnicity;
										$d = array();
										$d['l03'] = $l03;
										$d['tr_id'] = $tr_id;
										$d['programme_type'] = $programme_type;
										$d['start_date'] = $start_date;
										$d['planned_end_date'] = $end_date;
										$d['actual_end_date'] = $actual_date;
										$d['achievement_date'] = $achievement_date;
										$d['expected'] = 0;
										$d['actual'] = 0;
										$d['hybrid'] = 0;
										$d['p_prog_status'] = $p_prog_status;
										$d['contract_id'] = $contract_id;
										$d['submission'] = $submission;
										$d['level'] = $level;
										$d['age_band'] = $age_band;
										$d['a09'] = $a09;
										$d['local_authority'] = $local_authority;
										$d['region'] = $a23;
										$d['postcode'] = $a23;
										$d['sfc'] = $a26;
										$d['ssa1'] = '';
										$d['ssa2'] = '';
										//$d['glh'] = $glh;
										$d['employer'] = '';
										$d['assessor'] = '';
										$d['provider'] = $provider;
										$d['contractor'] = '';
										$d['ethnicity']	= $ethnicity;
										$data[] = $d;
									}
									else
									{
										if($delivery->AimType==4 && $delivery->FundModel!='99')
										{
											$ldm = '';
											foreach($delivery->LearningDeliveryFAM as $ldf)
											{
												if($ldf->LearnDelFAMType=='LDM')
													if($ldf->LearnDelFAMCode=='125')
														$ldm = 'Classroom';
											}

											if($ldm=='Classroom')
												$programme_type = "Classroom";
											elseif($delivery->MainDelMeth=='24' || $delivery->MainDelMeth=='23' || $delivery->MainDelMeth=='22')
												$programme_type = "Workplace";
											else
												$programme_type = "Unknown";

											$start_date = Date::toMySQL($delivery->LearnStartDate);
											$end_date = Date::toMySQL($delivery->LearnPlanEndDate);

											if($ilr->DateOfBirth!='00/00/0000')
											{
												$dob = "".$ilr->DateOfBirth;
												$dob = Date::toMySQL($dob);
												$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
											}
											else
											{
												$age = '';
											}
											if($age<=18)
												$age_band = "16-18";
											elseif($age<=24)
												$age_band = "19-24";
											elseif($age>=25)
												$age_band = "25+";
											else
												$age = "Unknown";

											if($delivery->LearnActEndDate!='00000000' && $delivery->LearnActEndDate!='00/00/0000' && $delivery->LearnActEndDate!='')
												$actual_date = Date::toMySQL($delivery->LearnActEndDate);
											else
												$actual_date = "0000-00-00";

											if($delivery->AchDate!='00000000' && $delivery->AchDate!='00/00/0000' && $delivery->AchDate!='')
												$achievement_date = Date::toMySQL($delivery->AchDate);
											else
												$achievement_date = "0000-00-00";

											$level = "".$delivery->ProgType;
											$a09 = "".$delivery->LearnAimRef;
											// Calculation for p_prog_status for apprenticeship only
											$p_prog_status = 7;
											if($actual_date=='0000-00-00')
												$p_prog_status =0;
											if($achievement_date!='0000-00-00')
												$p_prog_status = 1;
											if($actual_date!='0000-00-00' && ($delivery->Outcome==4 || $delivery->Outcome==5) && $achievement_date=='0000-00-00')
												$p_prog_status = 3;
											if($actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
												$p_prog_status = 6;
											if($delivery->CompStatus==3)
												$p_prog_status = 13;
											if($delivery->CompStatus==4 || $delivery->CompStatus==5)
												$p_prog_status = 8;
											if($delivery->WithdrawReason==2)
												$p_prog_status = 9;
											if($delivery->WithdrawReason==7)
												$p_prog_status = 10;
											if($delivery->CompStatus==6)
												$p_prog_status = 11;

											$a23 = trim($delivery->DelLocPostCode);
											$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
											if($local_authority=='')
											{
												$postcode = str_replace(" ","",$a23);
												$page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
												$local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
												$local_authority = str_replace("<strong>District</strong>","",$local_authority);
												$local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
												$local_authority = @str_replace("City Council","",$local_authority);
												$local_authority = @str_replace("District","",$local_authority);
												$local_authority = @str_replace("Council","",$local_authority);
												$local_authority = @str_replace("Borough","",$local_authority);
												if($local_authority=='')
													$local_authority="Not Found";
												$local_authority = str_replace("'","\'",$local_authority);
												DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
											}
											$local_authority = str_replace("'","\'",$local_authority);

											$ukprn = "".$delivery->PartnerUKPRN;
											if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
											{
												$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
											}
											else
											{
												$provider = '';
											}

											$provider = addslashes((string)$provider);
											$ethnicity = $ilr->Ethnicity;

											$d = array();
											$d['l03'] = $l03;
											$d['tr_id'] = $tr_id;
											$d['programme_type'] = $programme_type;
											$d['start_date'] = $start_date;
											$d['planned_end_date'] = $end_date;
											$d['actual_end_date'] = $actual_date;
											$d['achievement_date'] = $achievement_date;
											$d['expected'] = 0;
											$d['actual'] = 0;
											$d['hybrid'] = 0;
											$d['p_prog_status'] = $p_prog_status;
											$d['contract_id'] = $contract_id;
											$d['submission'] = $submission;
											$d['level'] = $level;
											$d['age_band'] = $age_band;
											$d['a09'] = $a09;
											$d['local_authority'] = $local_authority;
											$d['region'] = $a23;
											$d['postcode'] = $a23;
											$d['sfc'] = '';
											$d['ssa1'] = '';
											$d['ssa2'] = '';
											//$d['glh'] = $glh;
											$d['employer'] = '';
											$d['assessor'] = '';
											$d['provider'] = $provider;
											$d['contractor'] = '';
											$d['ethnicity']	= $ethnicity;
											$d['aim_type'] = '';
											$data[] = $d;

										}
									}
								}
								$counter++;
							}
						}
					}

				}

				//pr($data);

				DAO::multipleRowInsert($link, "success_rates", $data);

				// Remaining fields
				DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier1_codes on ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE set ssa1 = CONCAT(lad201213.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201213.ssa_tier1_codes.SSA_TIER1_DESC)");
				DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier2_codes on ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE set ssa2 = CONCAT(lad201213.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201213.ssa_tier2_codes.SSA_TIER2_DESC)");
				DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.employer_id set employer = organisations.legal_name");
				DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name where provider='' or provider is NULL");
				if(DB_NAME=='am_lead')
				{
					DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name");
				}

				//changed from contract_holders to contracts
				//DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id INNER JOIN organisations on organisations.id = contracts.contract_holder set contractor = organisations.legal_name");
				DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id set contractor = contracts.title");


				DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN users on users.username = tr.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
				DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN group_members on group_members.tr_id = tr.id INNER JOIN groups on group_members.groups_id = groups.id INNER JOIN users on users.username = groups.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname) where success_rates.assessor is NULL or success_rates.assessor=''");

				DAO::execute($link, "DELETE FROM success_rates WHERE (p_prog_status = 13 or p_prog_status=6 or p_prog_status=-1 or p_prog_status=8)  AND DATE_ADD(start_date, INTERVAL 42 DAY)>actual_end_date and programme_type!='Classroom';");
				DAO::execute($link, "DELETE FROM success_rates WHERE p_prog_status = 8 OR p_prog_status=12;");

				//pre($link->errorInfo());
				DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.actual_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2');");
				DAO::execute($link, "update success_rates set ethnicity = (select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) UNION select Ethnicity_Desc from lis201011.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) limit 0,1);");
				DAO::execute($link, "update success_rates INNER JOIN lad201213.frameworks on frameworks.FRAMEWORK_CODE = success_rates.sfc set sfc = frameworks.FRAMEWORK_DESC");
				DAO::execute($link, "update success_rates set sfc = LEFT(sfc,POSITION('-' IN sfc)-1)");
				DAO::execute($link, "update success_rates LEFT JOIN lad201213.learning_aim on learning_aim.LEARNING_AIM_REF = success_rates.a09 LEFT JOIN lad201213.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
				DAO::execute($link, "update success_rates set ssa1 = sfc where ssa1='X Not Applicable'");
				DAO::execute($link, "update success_rates set ssa1 = replace(ssa1,\"'\",\"\")");

				DAO::execute($link, "UPDATE success_rates LEFT JOIN central.lookup_la_gor ON success_rates.local_authority = central.lookup_la_gor.local_authority SET success_rates.region = central.lookup_la_gor.government_region;");


				DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'QCF Units' and programme_type = 'Classroom'");
				DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'Employability Award' and programme_type = 'Classroom'");



				$filter_conditions = "";

				$filter_assessor_val = $_REQUEST['assessor'];
				//echo "<br>filter_assessor_val = ".$filter_assessor_val."<br>";//exit('done');
				if($filter_assessor_val != "")
				{
					$filter_conditions .= " AND assessor = '".$filter_assessor_val."'";
				}

				$filter_age_band_val = $_REQUEST['age_band'];
				//echo "<br>filter_age_band_val = ".$filter_age_band_val."<br>";//exit('done');
				if($filter_age_band_val != "")
				{
					$filter_conditions .= " AND age_band = '".$filter_age_band_val."'";
				}

				$filter_programme_type_val = $_REQUEST['programme_type'];
				//echo "<br>filter_programme_type_val = ".$filter_programme_type_val."<br>";//exit('done');
				if($filter_programme_type_val != "")
				{
					$filter_conditions .= " AND programme_type = '".$filter_programme_type_val."'";
				}

				$filter_ssa_val = $_REQUEST['ssa'];
				//echo "<br>filter_ssa_val = ".$filter_ssa_val."<br>";//exit('done');
				if($filter_ssa_val != "")
				{
					$filter_conditions .= " AND ssa2 = '".$filter_ssa_val."'";
				}

				$filter_ethnicity_val = $_REQUEST['ethnicity'];
				//echo "<br>filter_ethnicity_val = ".$filter_ethnicity_val."<br>";//exit('done');
				if($filter_ethnicity_val != "")
				{
					$filter_conditions .= " AND ethnicity = '".$filter_ethnicity_val."'";
				}

				///get drilldown filter value
				$drill_down_by = $_REQUEST['drill_down_by'];

				//echo "<br>drill_down_by = ".$drill_down_by."<br>";//exit('done');

				$group_by="";

				if($drill_down_by == "assessor")
				{
					$drilldown_name = "Drilldown by Assessors";
					$drilldown_x_axis_title = $drilldown_title = "Assessors";
					$drilldown_col_key = "assessor";

					$group_by = "assessor";
				}

				else if($drill_down_by == "contract")
				{
					$drilldown_name = "Drilldown by Contracts";
					$drilldown_x_axis_title = $drilldown_title = "Contracts";
					$drilldown_col_key = "contractor";

					$group_by = "contract_id";
				}

				elseif($drill_down_by == "employer")
				{
					$drilldown_name = "Drilldown by Employers";
					$drilldown_x_axis_title = $drilldown_title = "Employers";
					$drilldown_col_key = "employer";

					$group_by = "employer";
				}

				else if($drill_down_by == "training_provider")
				{
					$drilldown_name = "Drilldown by Training Providers";
					$drilldown_x_axis_title = $drilldown_title = "Training Providers";
					$drilldown_col_key = "provider";

					$group_by = "provider";
				}

				else if($drill_down_by == "age_band")
				{
					$drilldown_name = "Drilldown by Age band";
					$drilldown_x_axis_title = $drilldown_title = "Age band";
					$drilldown_col_key = "age_band";

					$group_by = "age_band";
				}

				else if($drill_down_by == "programme_type")
				{
					$drilldown_name = "Drilldown by Programme type";
					$drilldown_x_axis_title = $drilldown_title = "Programme Type";
					$drilldown_col_key = "programme_type";

					$group_by = "programme_type";
				}

				else if($drill_down_by == "ssa")
				{
					$drilldown_name = "Drilldown by Sector Subject Area";
					$drilldown_x_axis_title = $drilldown_title = "Drilldown by Sector Subject Area";
					//$drilldown_col_key = "CONCAT( ssa1, '<br>', ssa2 ) AS ssa";
					$drilldown_col_key = "ssa2";

					$group_by = "ssa2";
				}

				else if($drill_down_by == "ethnicity")
				{
					$drilldown_name = "Drilldown by Ethnicity";
					$drilldown_x_axis_title = $drilldown_title = "Ethnicity";
					$drilldown_col_key = "ethnicity";

					$group_by = "ethnicity";
				}

				else if($drill_down_by == "region")
				{
					$drilldown_name = "Drilldown by Government Office Region";
					$drilldown_x_axis_title = $drilldown_title = "Government Office Region";
					$drilldown_col_key = "region";

					$group_by = "region";
				}

				if($group_by != "")
				{
					$group_by = " GROUP BY ".$group_by;
				}
				$drilldown_column = "";
				if($drilldown_col_key != '')
				{
					$drilldown_column = $drilldown_col_key.", ";
				}

				//for($year = $current_contract_year; $year>= ($current_contract_year-4); $year--)
				$years_expected = DAO::getSingleColumn($link, "SELECT distinct expected FROM success_rates WHERE expected IS NOT NULL");
				$years_actual = DAO::getSingleColumn($link, "SELECT distinct actual FROM success_rates WHERE actual IS NOT NULL");
				$years = array_merge($years_expected, $years_actual);
				$years = array_unique($years, SORT_STRING);
				sort($years);

				$report_type = $_REQUEST['report'];


				$arr_all_vals_drilldown_col = array();
				$achievers_arr = array();
				$leavers_arr = array();

				foreach($years as $year)
				{
					///The main difference between overall success and timely success depends on the below condition
					if($report_type == "overall_success")
					{
						$where_cond = " WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) ";
					}
					else if($report_type == "timely_success")
					{
						$where_cond = " WHERE expected = $year AND DATEDIFF(actual_end_date, planned_end_date)<=90 ";
					}



					///fetch achievers
					$achievers_query = "SELECT ".$drilldown_column." count(tr_id) as achievers FROM success_rates ".$where_cond." AND p_prog_status = 1 ".$filter_conditions." ".$group_by;
					//echo 'achievers_query = <br>';
					//pr($achievers_query);
					$st = $link->query($achievers_query);

					if(! $st) throw new DatabaseException($link, $achievers_query);
					while($row = $st->fetch())
					{
						if($drill_down_by == "none")
						{
							$achievers_arr[$year] = $row['achievers'];
						}
						else
						{
							$achievers_arr[$year][$row[$drilldown_col_key]] = $row['achievers'];
						}

						array_push($arr_all_vals_drilldown_col, $row[$drilldown_col_key]);
					}



					///fetch leavers
					$leavers_query = "SELECT ".$drilldown_column." count(tr_id) as leavers FROM success_rates ".$where_cond." ".$filter_conditions." ".$group_by;
					//echo 'leavers_query = <br>';
					//pr($leavers_query);
					$st = $link->query($leavers_query);

					if(! $st) throw new DatabaseException($link, $leavers_query);
					while($row = $st->fetch())
					{
						if($drill_down_by == "none")
						{
							$leavers_arr[$year] = $row['leavers'];
						}
						else
						{
							$leavers_arr[$year][$row[$drilldown_col_key]] = $row['leavers'];
						}

						array_push($arr_all_vals_drilldown_col, $row[$drilldown_col_key]);
					}
				}

				//echo 'achievers_arr =<br>';pr($achievers_arr);
				//echo 'leavers_arr =<br>';pr($leavers_arr);exit;

				if($drill_down_by != "none")
				{
					$arr_all_vals_drilldown_col = array_unique($arr_all_vals_drilldown_col, SORT_STRING);
					sort($arr_all_vals_drilldown_col);
					//echo 'arr_all_vals_drilldown_col =<br>';pr($arr_all_vals_drilldown_col);
				}

				//echo 'drill = ';pre($p);

				$x_axis_categories = array();
				$final_total_achievers = 0;
				$final_total_leavers = 0;


				$cntr=0;
				foreach($years as $year)
				{
					//echo "<br>year = ".$year;
					$x_cat_val = Date::getFiscal($year);
					//echo "x_cat_val = ".$x_cat_val;exit;


					$total_achievers_for_year = 0;
					$total_leavers_for_year = 0;

					$drilldown_categories = array();
					$drilldown_data = array();

					foreach($arr_all_vals_drilldown_col as $drilldown_col_val)
					{
						$cnt_achievers = 0;

						if(isset($achievers_arr[$year]) && isset($achievers_arr[$year][$drilldown_col_val]))
						{
							$cnt_achievers = $achievers_arr[$year][$drilldown_col_val];
						}

						$cnt_leavers = 0;

						if(isset($leavers_arr[$year]) && isset($leavers_arr[$year][$drilldown_col_val]))
						{
							$cnt_leavers = $leavers_arr[$year][$drilldown_col_val];
						}

						if($cnt_leavers != 0)//so that we do not get divide by zero error
						{
							$success_rate_val = ($cnt_achievers/$cnt_leavers)*100;
							$success_rate = (float)sprintf("%.2f",$success_rate_val);

							/*if($success_rate_val >= 53)
                            {
                                $success_rate = '<font style="background-color: green">'.$success_rate.'</font>';
                            }
                            else
                            {
                                $success_rate = '<font style="background-color: red">'.$success_rate.'</font>';
                            }*/

							$total_achievers_for_year += $cnt_achievers;
							$total_leavers_for_year += $cnt_leavers;

							$drilldown_col_val = $drilldown_col_val." <br>(Achievers: ".$cnt_achievers." Leavers: ".$cnt_leavers.")";

							array_push($drilldown_categories, $drilldown_col_val);
							array_push($drilldown_data, $success_rate);
						}
					}


					//if($total_achievers_for_year > 0 && $total_leavers_for_year > 0)
					if($total_leavers_for_year > 0)
					{
						$percentage = ($total_achievers_for_year/$total_leavers_for_year)*100;
						$percentage = (float)sprintf("%.2f",$percentage);

						$final_total_achievers += $total_achievers_for_year;
						$final_total_leavers += $total_leavers_for_year;
						$x_cat_val = $x_cat_val." <br>(Achievers: ".$total_achievers_for_year." Leavers: ".$total_leavers_for_year.")";

						array_push($x_axis_categories, $x_cat_val);
						array_push($chart_data, $percentage);
					}

					/*echo 'drilldown_categories = <pre>';
                    print_r($drilldown_categories);
                    echo 'drilldown_data = <pre>';
                    print_r($drilldown_data);*/

					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

					$pie_drilldown_data = array();

					for($i=0;$i<count($drilldown_categories);$i++)
					{
						$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
					}
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
				}

				if(count($x_axis_categories) > 0)
				{
					//echo "final_total_learners = ".$final_total_learners;exit;
					$speedo_chart_title = "Total Achievers";
					$speedo_chart_series_data = $final_total_achievers;

					$response['data_table']="";
					$data_table='<table class="CSSTableGenerator">
                                <tr>
                                  <td><b>Total Achievers</b></td>
                                  <td><b>Total Leavers</b></td>
                                </tr>

                                <tr style="font-size: 20px;font-weight: bold;">
                                  <td>'.$final_total_achievers.'</td>
                                  <td>'.$final_total_leavers.'</td>
                                </tr>
                                </table>';

					$response['data_table'] = $data_table;
				}
				else
				{
					$response_result="no_data";
				}
			}




			//////////////////////     Report sla_kpi_rep_learners //////////////////////////

			elseif($report_type == "sla_kpi_rep_learners")//lib/ViewDoubleGraph.php
			{
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value learners<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value learners<br>Click to return back";


				$chart_title = "Learners";
				$x_axis_title = "Time Period";
				$y_axis_title = "No. of Learners";
				//$x_axis_categories = array('X1','X2','X3','X4','X5');
				$x_axis_categories = array();

				$chart_data = array();

				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " learners";


				$idarray['from_date'] = $from_date1 = $_REQUEST['from_date'];
				$idarray['to_date'] = $to_date1 = $_REQUEST['to_date'];

				$idarray['closure_start_date'] = $closure_start_date = $_REQUEST['closure_start_date'];
				$idarray['closure_end_date'] = $closure_end_date = $_REQUEST['closure_end_date'];

				$idarray['target_start_date'] = $target_start_date = $_REQUEST['target_start_date'];
				$idarray['target_end_date'] = $target_end_date = $_REQUEST['target_end_date'];

				$idarray['work_experience_start_date'] = $work_experience_start_date = $_REQUEST['work_experience_start_date'];
				$idarray['work_experience_end_date'] = $work_experience_end_date = $_REQUEST['work_experience_end_date'];

				$idarray['employer_id'] = $employer_id = $_REQUEST['employer'];
				$idarray['contract_id'] = $contract_id = $_REQUEST['contract'];
				$idarray['training_provider_id'] = $training_provider_id = $_REQUEST['training_provider'];
				$idarray['assessor_id'] = $assessor_id = $_REQUEST['assessor'];

				$idarray['progress'] = $progress = $_REQUEST['progress'];
				$idarray['gender'] = $gender = $_REQUEST['gender'];
				$idarray['programme'] = $programme = $_REQUEST['programme'];
				$idarray['record_status'] = $record_status = $_REQUEST['record_status'];
				$idarray['course'] = $course = $_REQUEST['course'];
				$idarray['framework'] = $framework = $_REQUEST['framework'];
				$idarray['group'] = $group = $_REQUEST['group'];

				$drill_down_by = $_REQUEST['drill_down_by'];


				$chart_subtitle = "From : ".$from_date1." To : ".$to_date1;

				$details_arr = array();

				$details_arr = $obj_sla_kpi_reports->get_learners($link,$idarray);
				//echo 'details_arr = <pre>';
				//print_r($details_arr);exit;

				if($details_arr[0] != 'false')
				{
					$response_result="success";

					foreach($details_arr as $details)
					{
						$year = $details['year'];
						$idarray['year'] = $year;
						$learner_count = $details['learner_count'];

						array_push($x_axis_categories,$year);
						array_push($chart_data,$learner_count);

						$x_cat_val = $year;
						//echo "x_cat_val = ".$x_cat_val;exit;

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$drilldown_categories = array();
						$drilldown_data = array();

						$dtl_arr = array();
						//$idarr = array();
//                    $idarr['from_date'] = $from_date1;
//                    $idarr['to_date'] = $to_date1;
//                    $idarr['year'] = $year;
//                    $idarr['employer_id']=$idarray['employer_id'];
//                    $idarr['contract_id']=$idarray['contract_id'];
//                    $idarr['training_provider_id']=$idarray['training_provider_id'];
//                    $idarr['assessor_id']=$idarray['assessor_id'];


						if($drill_down_by == "quarter")
						{
							//$idarr['group_by'] = "quarter";
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Quarter";
							$drilldown_x_axis_title = "Quarters";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$quarter_name = $dtl['quarter_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'quarter_name = '.$quarter_name." count = ".$learner_count."<br>";
									if($quarter_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$quarter_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "month")
						{
							//$idarr['group_by'] = "month";
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Month";
							$drilldown_x_axis_title = "Months";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$month_name = $dtl['month_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'month_name = '.$month_name." count = ".$learner_count."<br>";
									if($month_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$month_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "week")
						{
							//$idarr['group_by'] = "week";
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Week";
							$drilldown_x_axis_title = "Weeks";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$week = $dtl['week'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'week = '.$week." count = ".$learner_count."<br>";
									if($week != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$week);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "employer")
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Employer";
							$drilldown_x_axis_title = "Employers";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$employer_name = $dtl['employer_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'employer_name = '.$employer_name." count = ".$learner_count."<br>";
									if($employer_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$employer_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "training_provider")
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Training Providers";
							$drilldown_x_axis_title = "Training Providers";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$training_provider_name = $dtl['training_provider_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'training_provider_name = '.$training_provider_name." count = ".$learner_count."<br>";
									if($training_provider_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$training_provider_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "contract")
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Contract";
							$drilldown_x_axis_title = "Contracts";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$contract_name = $dtl['contract_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'contract_name = '.$contract_name." count = ".$learner_count."<br>";
									if($contract_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$contract_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						elseif($drill_down_by == "assessor")
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Assessors";
							$drilldown_x_axis_title = "Assessors";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$assessor_name = $dtl['assessor_name'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($assessor_name != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$assessor_name);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'age_range')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Age range";
							$drilldown_x_axis_title = "Age range";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$age = $dtl['age'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($age != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$age);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'course')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Course";
							$drilldown_x_axis_title = "Courses";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$course_title = $dtl['course_title'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($course_title != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$course_title);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'disability')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Disability";
							$drilldown_x_axis_title = "Disability";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$disability = $dtl['disability'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($disability != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$disability);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'ethnicity')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Ethnicity";
							$drilldown_x_axis_title = "Ethnicity";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$ethnicity = $dtl['ethnicity'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($ethnicity != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$ethnicity);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'gender')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Gender";
							$drilldown_x_axis_title = "Gender";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$gender = $dtl['gender'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($gender != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$gender);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'tutor')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Group tutor";
							$drilldown_x_axis_title = "Group tutors";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$tutor = $dtl['tutor'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($tutor != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$tutor);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'learning_difficulty')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Learning difficulty";
							$drilldown_x_axis_title = "Learning difficulty";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$learning_difficulty = $dtl['learning_difficulty'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($learning_difficulty != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$learning_difficulty);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'progress')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Progress";
							$drilldown_x_axis_title = "Progress";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$progress = $dtl['progress'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($progress != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$progress);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'mainarea')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Qualification Subject Sector Area";
							$drilldown_x_axis_title = "Qualification Subject Sector Areas";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$mainarea = $dtl['mainarea'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($mainarea != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$mainarea);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'subarea')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Qualification Subject Sector Subarea";
							$drilldown_x_axis_title = "Qualification Subject Sector Subareas";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$internaltitle = $dtl['internaltitle'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($internaltitle != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$internaltitle);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'record_status')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Record status";
							$drilldown_x_axis_title = "Record status";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$record_status = $dtl['record_status'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($record_status != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$record_status);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'verifier')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by IQA";
							$drilldown_x_axis_title = "IQA";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$verifier = $dtl['verifier'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($verifier != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$verifier);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'work_experience_coordinator')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Work Experience Coordinator";
							$drilldown_x_axis_title = "Work Experience Coordinator";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$wbcoordinator = $dtl['wbcoordinator'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($wbcoordinator != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$wbcoordinator);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'actual_work_experience')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Work Experience Days";
							$drilldown_x_axis_title = "Work Experience Days";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$actual_work_experience = $dtl['actual_work_experience'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($actual_work_experience != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$actual_work_experience);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						else if($drill_down_by == 'work_experience_band_10')
						{
							$idarray['drill_down_by'] = $drill_down_by;

							$drilldown_name = "Drilldown by Work Experience Visits 10 Days Band";
							$drilldown_x_axis_title = "Work Experience Visits 10 Days Band";

							$dtl_arr = $obj_sla_kpi_reports->get_learners($link, $idarray);
							//echo 'dtl_arr = <pre>';
							//print_r($dtl_arr);

							if($dtl_arr[0] != "false")
							{
								foreach($dtl_arr as $dtl)
								{

									$band0to10 = $dtl['band0to10'];
									$learner_count = (int)$dtl['learner_count'];
									//echo 'assessor_name = '.$assessor_name." count = ".$learner_count."<br>";
									if($band0to10 != "" && $learner_count != 0)
									{
										array_push($drilldown_categories,$band0to10);
										array_push($drilldown_data,$learner_count);
									}
								}
							}
						}
						/*echo 'drilldown_categories = <pre>';
                    print_r($drilldown_categories);
                    echo 'drilldown_data = <pre>';
                    print_r($drilldown_data);*/

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

						$pie_drilldown_data = array();

						for($i=0;$i<count($drilldown_categories);$i++)
						{
							$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
						}
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
					}

					$final_total_learners = (int)array_sum($chart_data);
					//echo "final_total_learners = ".$final_total_learners;exit;
					$speedo_chart_series_data = $final_total_learners;

					$response['data_table']="";
					$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Total Learners</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>'.$final_total_learners.'</td>
                            </tr>
                            </table>';

					$response['data_table'] = $data_table;
					//exit;
				}
				else
				{
					$response_result="no_data";
				}
			}




			//////////////////////     Report sla_kpi_rep_progression //////////////////////////

			elseif($report_type == "sla_kpi_rep_progression" || $report_type == "sla_kpi_rep_progression_l2tol3")
			{
				$response['bar_chart_details']['bar_chart_drillup_tooltip_format']="<b>ttl_title : </b>ttl_value learners<br>Click to view classifications";
				$response['bar_chart_details']['bar_chart_drilldown_tooltip_format']="<b>ttl_title : </b>ttl_value learners<br>Click to return back";

				if($report_type == "sla_kpi_rep_progression_l2tol3")
				{
					$chart_title = "Onward Progression from L2 to L3";
					$x_axis_title = "Transition Level";
				}
				else
				{
					$chart_title = "Onward Progression";
					$x_axis_title = "Transition Levels";
				}

				$y_axis_title = "No. of Learners";


				$response['line_chart_details']['tooltip_suffix'] = "";
				$response['bar_chart_details']['tooltip_suffix'] = " learners";

				///get filters
				$idarray['employer_id'] = $employer_id = $_REQUEST['employer'];
				$idarray['contract_id'] = $contract_id = $_REQUEST['contract'];
				$idarray['training_provider_id'] = $training_provider_id = $_REQUEST['training_provider'];
				$idarray['assessor_id'] = $assessor_id = $_REQUEST['assessor'];
				$idarray['gender'] = $gender = $_REQUEST['gender'];
				$idarray['course'] = $course = $_REQUEST['course'];
				$idarray['framework'] = $framework = $_REQUEST['framework'];
				$idarray['ethnicity_id'] = $ethnicity_id = $_REQUEST['ethnicity'];
				$idarray['submission'] = $submission = $_REQUEST['submission'];
				$idarray['contract_year'] = $contract_year = $_REQUEST['contract_year'];

				$idarray['group_by'] = "ilr.L03";
				$idarray['order_by'] = "contract_year desc, submission DESC";

				///get drilldown filter value
				$drill_down_by = $_REQUEST['drill_down_by'];


				//$chart_subtitle = "For Contract year: ".$contract_year;

				$learners_dtls_arr = array();

				$l2_lrnr_arr = array();
				$l3_lrnr_arr = array();
				$l4_lrnr_arr = array();
				$l5_lrnr_arr = array();
				$l6_lrnr_arr = array();
				$l7_lrnr_arr = array();


				$details_arr = array();

				$details_arr = $obj_sla_kpi_reports->get_ilrs($link,$idarray);
				//echo 'details_arr = <br>';
				//pre($details_arr);exit;
				if($details_arr[0] != 'false')
				{
					$response_result="success";

					foreach($details_arr as $row)
					{
						$l03 = $row['L03'];
						$learners_dtls_arr[$l03] = array();

						//insert learner details into the array
						$learners_dtls_arr[$l03] = $row;

						$qry = "SELECT ExtractValue( ilr, '/Learner/LearningDelivery/AimType' ) AS aim_type_values, ExtractValue( ilr, '/Learner/LearningDelivery/ProgType' ) AS prog_type_values FROM ilr WHERE L03 = '".$l03."'";

						$res = $link->query($qry) or die('Error in qry '.pre($qry));

						//echo '<br><br><u><b>L03 = '.$l03.'</b></u><br>';
						while($rw = $res->fetch())
						{
							$aim_type_values = $rw['aim_type_values'];
							$prog_type_values = $rw['prog_type_values'];

							$aim_arr = explode(" ",$aim_type_values);
							$prog_type_arr = explode(" ",$prog_type_values);
							//check if aim_type 1 exists
							if(in_array('1', $aim_arr))
							{
								$key_val = array_search('1', $aim_arr);
								//get prog_type value where aim_type = 1
								$prog_type_val = $prog_type_arr[$key_val];

								//check if the L03 value already exists for that level arr
								if($prog_type_val == '3' && (! in_array($l03, $l2_lrnr_arr)))
								{
									array_push($l2_lrnr_arr, $l03);
								}
								if($prog_type_val == '2' && (! in_array($l03, $l3_lrnr_arr)))
								{
									array_push($l3_lrnr_arr, $l03);
								}
								if($prog_type_val == '20' && (! in_array($l03, $l4_lrnr_arr)))
								{
									array_push($l4_lrnr_arr, $l03);
								}
								if($prog_type_val == '21' && (! in_array($l03, $l5_lrnr_arr)))
								{
									array_push($l5_lrnr_arr, $l03);
								}
								if($prog_type_val == '22' && (! in_array($l03, $l6_lrnr_arr)))
								{
									array_push($l6_lrnr_arr, $l03);
								}
								if($prog_type_val == '23' && (! in_array($l03, $l7_lrnr_arr)))
								{
									array_push($l7_lrnr_arr, $l03);
								}
							}

							//echo '<br>aim_type_values = '.$aim_type_values.' prog_type_values = '.$prog_type_values;
						}
					}

					/*echo '<br>L2 array = <br>';
                pr($l2_lrnr_arr);

                echo '<br>L3 array = <br>';
                pr($l3_lrnr_arr);

                echo '<br>L4 array = <br>';
                pr($l4_lrnr_arr);

                echo '<br>L5 array = <br>';
                pr($l5_lrnr_arr);

                echo '<br>L6 array = <br>';
                pr($l6_lrnr_arr);

                echo '<br>L7 array = <br>';
                pr($l7_lrnr_arr);*/

					//pre($learners_dtls_arr);
					//if the learner exists in L2 and also in L3 then it means that it has progressed from L2 to L3, and similarly for other levels also
					$l2_to_l3 = array();
					foreach($l3_lrnr_arr as $l03)
					{
						if(in_array($l03, $l2_lrnr_arr))
						{
							array_push($l2_to_l3, $l03);
						}
					}

					$l3_to_l4 = array();
					foreach($l4_lrnr_arr as $l03)
					{
						if(in_array($l03, $l3_lrnr_arr))
						{
							array_push($l3_to_l4, $l03);
						}
					}

					$l4_to_l5 = array();
					foreach($l5_lrnr_arr as $l03)
					{
						if(in_array($l03, $l4_lrnr_arr))
						{
							array_push($l4_to_l5, $l03);
						}
					}

					$l5_to_l6 = array();
					foreach($l6_lrnr_arr as $l03)
					{
						if(in_array($l03, $l5_lrnr_arr))
						{
							array_push($l5_to_l6, $l03);
						}
					}

					$l6_to_l7 = array();
					foreach($l7_lrnr_arr as $l03)
					{
						if(in_array($l03, $l6_lrnr_arr))
						{
							array_push($l6_to_l7, $l03);
						}
					}

					/*echo '<br>L2 to L3 array = <br>';
                pr($l2_to_l3);

                echo '<br>L3 to L4 array = <br>';
                pr($l3_to_l4);

                echo '<br>L4 to L5 array = <br>';
                pr($l4_to_l5);

                echo '<br>L5 to L6 array = <br>';
                pr($l5_to_l6);

                echo '<br>L6 to L7 array = <br>';
                pr($l6_to_l7);*/


					if($drill_down_by == "assessor")
					{
						$drilldown_name = "Drilldown by Assessors";
						$drilldown_x_axis_title = "Assessor Name";
						$drilldown_col_key = "assessor_name";

						//$group_by = "assessor";
					}

					else if($drill_down_by == "contract")
					{
						$drilldown_name = "Drilldown by Contracts";
						$drilldown_x_axis_title = "Contract Name";
						$drilldown_col_key = "contract_name";

						//$group_by = "contract_id";
					}

					elseif($drill_down_by == "employer")
					{
						$drilldown_name = "Drilldown by Employers";
						$drilldown_x_axis_title = "Employer Name";
						$drilldown_col_key = "employer_name";

						//$group_by = "employer";
					}

					else if($drill_down_by == "training_provider")
					{
						$drilldown_name = "Drilldown by Training Providers";
						$drilldown_x_axis_title = "Training Provider Name";
						$drilldown_col_key = "training_provider_name";

						//$group_by = "provider";
					}

					else if($drill_down_by == "ethnicity")
					{
						$drilldown_name = "Drilldown by Ethnicity";
						$drilldown_x_axis_title = "Ethnicity";
						$drilldown_col_key = "ethnicity_description";

						//$group_by = "ethnicity";
					}

					else if($drill_down_by == "gender")
					{
						$drilldown_name = "Drilldown by Gender";
						$drilldown_x_axis_title = "Gender";
						$drilldown_col_key = "gender";

						//$group_by = "ethnicity";
					}

					else if($drill_down_by == "course")
					{
						$drilldown_name = "Drilldown by Course";
						$drilldown_x_axis_title = "Course";
						$drilldown_col_key = "course_title";

						//$group_by = "ethnicity";
					}

					else if($drill_down_by == "frameworks")
					{
						$drilldown_name = "Drilldown by Frameworks";
						$drilldown_x_axis_title = "Framework";
						$drilldown_col_key = "framework_title";

						//$group_by = "ethnicity";
					}

					else if($drill_down_by == "submission")
					{
						$drilldown_name = "Drilldown by Submission";
						$drilldown_x_axis_title = "Submission";
						$drilldown_col_key = "submission";

						//$group_by = "ethnicity";
					}

					else if($drill_down_by == "contract_year")
					{
						$drilldown_name = "Drilldown by Contract Year";
						$drilldown_x_axis_title = "Contract Year";
						$drilldown_col_key = "contract_year";

						//$group_by = "ethnicity";
					}



					if($report_type == "sla_kpi_rep_progression_l2tol3")
					{
						$x_axis_categories = array("L2 to L3");
						$chart_data = array(count($l2_to_l3));
					}
					else
					{
						$x_axis_categories = array("L2 to L3", "L3 to L4", "L4 to L5", "L5 to L6", "L6 to L7 or above");
						$chart_data = array(count($l2_to_l3), count($l3_to_l4), count($l4_to_l5), count($l5_to_l6), count($l6_to_l7));
					}


					$level_arr = array();
					foreach($x_axis_categories as $x_cat_val)
					{


						if($x_cat_val == "L2 to L3")
							$level_arr = $l2_to_l3;
						else if($x_cat_val == "L3 to L4")
							$level_arr = $l3_to_l4;
						else if($x_cat_val == "L4 to L5")
							$level_arr = $l4_to_l5;
						else if($x_cat_val == "L5 to L6")
							$level_arr = $l5_to_l6;
						else if($x_cat_val == "L6 to L7 or above")
							$level_arr = $l6_to_l7;

						$drill_down_cats = array();
						foreach($level_arr as $val_l03)
						{
							$drilldown_col_value = $learners_dtls_arr[$val_l03][$drilldown_col_key];
							//push the fetched values into an array
							array_push($drill_down_cats, $drilldown_col_value);
						}
						$drilldown_categories = array();
						$drilldown_data = array();

						if(count($level_arr) > 0)
						{
							///Now count the distinct values and their occucrence(count)
							$temp_arr = array_count_values($drill_down_cats);
							foreach($temp_arr as $drill_cat => $drill_val)
							{
								array_push($drilldown_categories, $drill_cat);
								array_push($drilldown_data, $drill_val);
							}
						}

						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();



						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
						$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
						//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;

						$pie_drilldown_data = array();

						for($i=0;$i<count($drilldown_categories);$i++)
						{
							$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
						}
						$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
					}

					$speedo_chart_title = "Total Learners";
					$speedo_chart_series_data = (int)array_sum($chart_data);

					$response['data_table']="";

					if($report_type == "sla_kpi_rep_progression_l2tol3")
					{
						$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Transition Level</b></td>
                              <td><b>L2 to L3</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>Learners</td>
                              <td>'.count($l2_to_l3).'</td>
                            </tr>
                            </table>';
					}
					else
					{
						$data_table='<table class="CSSTableGenerator">
                            <tr>
                              <td><b>Transition Levels</b></td>
                              <td><b>L2 to L3</b></td>
                              <td><b>L3 to L4</b></td>
                              <td><b>L4 to L5</b></td>
                              <td><b>L5 to L6</b></td>
                              <td><b>L6 to L7 or above</b></td>
                            </tr>

                            <tr style="font-size: 20px;font-weight: bold;">
                              <td>Learners</td>
                              <td>'.count($l2_to_l3).'</td>
                              <td>'.count($l3_to_l4).'</td>
                              <td>'.count($l4_to_l5).'</td>
                              <td>'.count($l5_to_l6).'</td>
                              <td>'.count($l6_to_l7).'</td>
                            </tr>
                            </table>';
					}

					$response['data_table'] = $data_table;
				}
				else
				{
					$response_result="no_data";
				}
			}



			////////////////////////// temporary dummy data

			else
			{
				$chart_title = "Temp title";
				$chart_subtitle = "Temp sub title";
				$x_axis_title = "Temp x-title";
				$y_axis_title = "Temp y-title";
				$x_axis_categories = array('TX1','TX2','TX3','TX4','TX5');
				$chart_data = array(1,3,5,7,9);
				$response['line_chart_details']['tooltip_suffix'] = " temp tooltip suffix";

				$drilldown_name = "Temp drilldown name";
				$drilldown_x_axis_title = "Temp drilldown x-axis title";
				$drilldown_categories = array('TD1','TD2','TD3','TD4','TD5');
				$drilldown_data = array(15,30,45,60,75);

				foreach($x_axis_categories as $x_cat_val)
				{
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['bar_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;



					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['name'] = $drilldown_name;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['x_axis_title'] = $drilldown_x_axis_title;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = $drilldown_categories;
					$response['line_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $drilldown_data;


					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down'] = array();
					//$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['categories'] = array();
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = array();

					$pie_drilldown_data = array();

					for($i=0;$i<count($drilldown_categories);$i++)
					{
						$pie_drilldown_data[$drilldown_categories[$i]] = $drilldown_data[$i];
					}
					$response['pie_chart_details']['drilldown_details'][$x_cat_val]['drill_down']['data'] = $pie_drilldown_data;
				}

				$response['data_table']="";
				$data_table='<table class="CSSTableGenerator">
                        <tr>
                          <td><b>Learners per group</b></td>
                          <td><b>Total OPP</b></td>
                          <td><b>Funded Learners</b></td>
                        </tr>

                        <tr style="font-size: 20px;font-weight: bold;">
                          <td>14</td>
                          <td>&pound;61,888</td>
                          <td>320</td>
                        </tr>

                        <tr>
                          <td>Training avg : 12 (0.00%)</td>
                          <td>% of total : 100%(320)</td>
                          <td>% of total : 100% (&pound;61,888)</td>
                        </tr>

                        </table>';

				$response['data_table'] = $data_table;
				$speedo_chart_series_data = 50;

				$response_result="success";
			}

			/*echo 'x_axis_categories = <pre>';
        print_r($x_axis_categories);exit;*/

			//line chart details
			$response['line_chart_details']['title'] = $chart_title;
			$response['line_chart_details']['subtitle'] = $chart_subtitle;
			$response['line_chart_details']['x_axis_title'] = $x_axis_title;
			$response['line_chart_details']['x_axis_categories'] = $x_axis_categories;
			$response['line_chart_details']['y_axis_title'] = $y_axis_title;
			$response['line_chart_details']['series_title'] = $chart_title;
			$response['line_chart_details']['series_data'] = $chart_data;//for both line chart and bar the y-axis values must be converted to either integer or float otherwise data won't be displayed on graph


			//bar chart details
			$response['bar_chart_details']['title'] = $chart_title;
			$response['bar_chart_details']['subtitle'] = $chart_subtitle;
			$response['bar_chart_details']['x_axis_title'] = $x_axis_title;
			$response['bar_chart_details']['x_axis_categories'] = $x_axis_categories;
			$response['bar_chart_details']['y_axis_title'] = $y_axis_title;
			$response['bar_chart_details']['series_title'] = $chart_title;
			$response['bar_chart_details']['series_data'] = $chart_data;


			//sppedo chart details
			if($speedo_chart_title != "")//if speedo chart title is defined
			{
				$response['speedo_chart_details']['title'] = $speedo_chart_title;
			}
			else
			{
				$response['speedo_chart_details']['title'] = "Total ".$chart_title;
			}
			$response['speedo_chart_details']['subtitle'] = $chart_subtitle;
			$response['speedo_chart_details']['series_data'] = $speedo_chart_series_data;

			if($speedo_chart_series_data <= 100)
			{
				$speedo_chart_max_value = 100;
			}
			else if($speedo_chart_series_data >100 && $speedo_chart_series_data <=500)
			{
				$speedo_chart_max_value=500;
			}
			else if($speedo_chart_series_data >500 && $speedo_chart_series_data <=1000)
			{
				$speedo_chart_max_value=1000;
			}
			else if($speedo_chart_series_data >1000)
			{
				$speedo_chart_max_value=10000;
			}
			$response['speedo_chart_details']['max_value'] = (int)$speedo_chart_max_value;



			//pie chart details
			$pie_data = array();

			for($i=0;$i<count($x_axis_categories);$i++)
			{
				$pie_data[$x_axis_categories[$i]] = $chart_data[$i];
			}

			$response['pie_chart_details']['title'] = $chart_title;
			$response['pie_chart_details']['subtitle'] = $chart_subtitle;
			$response['pie_chart_details']['series_data'] = $pie_data;


			$response['result'] = $response_result;
			//echo 'response = <pre>';
			//print_r($response);exit;
			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}


	public function createTempTableForRetentionReport(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS `sla_kpi_retention_new` ;");
		$sql = "
                CREATE TEMPORARY TABLE `sla_kpi_retention_new` (
                  `l03` varchar(12) DEFAULT NULL,
                  `a09` varchar(8) DEFAULT NULL,
                  `tr_id` int(11) DEFAULT NULL,
                  `gender` varchar(1) DEFAULT NULL,
                  `ssa` varchar(100) DEFAULT NULL,
                  `ethnicity` varchar(10) DEFAULT NULL,

                  `a27` date DEFAULT NULL,
                  `a31` date DEFAULT NULL,
                  `comp_status` varchar(2) DEFAULT NULL,
                  `fcode` varchar(3) DEFAULT NULL,
                  `assessor` varchar(50) DEFAULT NULL,
                  `employer` varchar(100) DEFAULT NULL,
                  `prog_type` varchar(2) DEFAULT NULL,

                  provider_id int(11) DEFAULT NULL,
                  training_provider_name varchar(500) DEFAULT NULL,

                  contract_id int(11) DEFAULT NULL,
                  contract_name varchar(500) DEFAULT NULL,

                  framework_title varchar(2000) DEFAULT NULL,
                  course_id int(11) DEFAULT NULL,
                  course_title varchar(2000) DEFAULT NULL,

                  ethnicity_description varchar(2000) DEFAULT NULL,
                  contract_year int(11) DEFAULT NULL
                ) ENGINE 'MEMORY'
                ";
		DAO::execute($link, $sql);
	}


	public static function createTempTableForOverallAndTimelyReport(PDO $link)
	{
		$sql = "
                CREATE TEMPORARY TABLE `success_rates` (
                  `l03` varchar(12) DEFAULT NULL,
                  `tr_id` int(11) DEFAULT NULL,
                  `programme_type` varchar(15) DEFAULT NULL,
                  `start_date` date DEFAULT NULL,
                  `planned_end_date` date DEFAULT NULL,
                  `actual_end_date` date DEFAULT NULL,
                  `achievement_date` date DEFAULT NULL,
                  `expected` int(11) DEFAULT NULL,
                  `actual` int(11) DEFAULT NULL,
                  `hybrid` int(11) DEFAULT NULL,
                  `p_prog_status` int(11) DEFAULT NULL,
                  `contract_id` int(11) DEFAULT NULL,
                  `submission` varchar(3) DEFAULT NULL,
                  `level` varchar(20) DEFAULT NULL,
                  `age_band` varchar(20) DEFAULT NULL,
                  `a09` varchar(8) DEFAULT NULL,
                  `local_authority` varchar(50) DEFAULT NULL,
                  `region` varchar(50) DEFAULT NULL,
                  `postcode` varchar(10) DEFAULT NULL,
                  `sfc` varchar(100) DEFAULT NULL,
                  `ssa1` varchar(100) DEFAULT NULL,
                  `ssa2` varchar(100) DEFAULT NULL,
                  `employer` varchar(100) DEFAULT NULL,
                  `assessor` varchar(100) DEFAULT NULL,
                  `provider` varchar(100) DEFAULT NULL,
                  `contractor` varchar(100) DEFAULT NULL,
                  `ethnicity` varchar(255) DEFAULT NULL,
                  `aim_type` varchar(50) DEFAULT NULL,
                  KEY `prog` (`programme_type`,`expected`,`actual`),
                  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
                ) ENGINE 'MEMORY'
                ";
		DAO::execute($link, $sql);
	}
}
?>