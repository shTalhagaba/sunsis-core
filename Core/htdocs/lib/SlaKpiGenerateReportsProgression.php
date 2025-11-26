<?php
class SlaKpiGenerateReportsProgression extends View
{
    public static function get_includes()
    {
        include_once('act_sla_kpi_reports.php');
        require_once('./lib/KPI_classes.php');
    }


    public static function getInstance(PDO $link)
	{

        /*
        //filter framework
        $framework = $_REQUEST['framework'];
        if($framework != '')
        {
            $framework_default_value = $framework;
        }
        elseif(isset($_REQUEST[__CLASS__.'_framework']))
        {
            $framework_default_value = $_REQUEST[__CLASS__.'_framework'];
        }
        else
        {
            $framework_default_value = "";
        }

        //filter valid
        $valid = $_REQUEST['valid'];
        if($valid != '')
        {
            $valid_default_value = $valid;
        }
        elseif(isset($_REQUEST[__CLASS__.'_valid']))
        {
            $valid_default_value = $_REQUEST[__CLASS__.'_valid'];
        }
        else
        {
            $valid_default_value = "all";
        }

        //filter active
        $active = $_REQUEST['active'];
        if($active != '')
        {
            $active_default_value = $active;
        }
        elseif(isset($_REQUEST[__CLASS__.'_active']))
        {
            $active_default_value = $_REQUEST[__CLASS__.'_active'];
        }
        else
        {
            $active_default_value = "all";
        }


        //filter contract_year
        $contract_year = $_REQUEST['contract_year'];
        if($contract_year != '')
        {
            $contract_year_default_value = $contract_year;
        }
        elseif(isset($_REQUEST[__CLASS__.'_contract_year']))
        {
            $contract_year_default_value = $_REQUEST[__CLASS__.'_contract_year'];
        }
        else
        {
            $contract_year_default_value = "";
        }

        //filter submission
        $submission = $_REQUEST['submission'];
        if($submission != '')
        {
            $submission_default_value = $submission;
        }
        elseif(isset($_REQUEST[__CLASS__.'_submission']))
        {
            $submission_default_value = $_REQUEST[__CLASS__.'_submission'];
        }
        else
        {
            //$submission_val = DAO::getSingleValue($link, "SELECT id FROM lookup_er_submissions WHERE description = (SELECT MAX(submission) FROM ilr)");
            $submission_val = DAO::getSingleValue($link, "SELECT MAX(submission) FROM ilr");
            //exit($submission_val);
            $submission_default_value = $submission_val;
        }

*/

        error_reporting(E_ALL^E_NOTICE);
        $key = 'view_'.__CLASS__;
        //echo 'key = '.$key;exit;
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode'] != '')
        {
            unset($_SESSION[$key]);
        }unset($_SESSION[$key]);


        //filter assessor
        $assessor = $_REQUEST['assessor'];
        if($assessor != '')
        {
            $assessor_default_value = $assessor;
        }
        elseif(isset($_REQUEST[__CLASS__.'_assessor']))
        {
            $assessor_default_value = $_REQUEST[__CLASS__.'_assessor'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $assessor_default_value = "";
        }

        //filter contract
        $contract = $_REQUEST['contract'];
        if($contract != '')
        {
            $contract_default_value = $contract;
        }
        elseif(isset($_REQUEST[__CLASS__.'_contract']))
        {
            $contract_default_value = $_REQUEST[__CLASS__.'_contract'];
        }
        else
        {
            $contract_default_value = "";
        }

        //filter employer
        $employer = $_REQUEST['employer'];
        if($employer != '')
        {
            $employer_default_value = $employer;
        }
        elseif(isset($_REQUEST[__CLASS__.'_employer']))
        {
            $employer_default_value = $_REQUEST[__CLASS__.'_employer'];
        }
        else
        {
            $employer_default_value = "";
        }

        //filter provider
        $training_provider = $_REQUEST['training_provider'];
        if($training_provider != '')
        {
            $training_provider_default_value = $training_provider;
        }
        elseif(isset($_REQUEST[__CLASS__.'_training_provider']))
        {
            $training_provider_default_value = $_REQUEST[__CLASS__.'_training_provider'];
        }
        else
        {
            $training_provider_default_value = "";
        }

        //filter ethnicity
        $ethnicity = $_REQUEST['ethnicity'];
        if($ethnicity != '')
        {
            $ethnicity_default_value = $ethnicity;
        }
        elseif(isset($_REQUEST[__CLASS__.'_ethnicity']))
        {
            $ethnicity_default_value = $_REQUEST[__CLASS__.'_ethnicity'];
        }
        else
        {
            $ethnicity_default_value = "";
        }

        //filter gender
        $gender = $_REQUEST['gender'];
        if($gender != '')
        {
            $gender_default_value = $gender;
        }
        elseif(isset($_REQUEST[__CLASS__.'_gender']))
        {
            $gender_default_value = $_REQUEST[__CLASS__.'_gender'];
        }
        else
        {
            $gender_default_value = "";
        }

        //filter course
        $course = $_REQUEST['course'];
        if($course != '')
        {
            $course_default_value = $course;
        }
        elseif(isset($_REQUEST[__CLASS__.'_course']))
        {
            $course_default_value = $_REQUEST[__CLASS__.'_course'];
        }
        else
        {
            $course_default_value = "";
        }


        //filter framework
        $framework = $_REQUEST['framework'];
        if($framework != '')
        {
            $framework_default_value = $framework;
        }
        elseif(isset($_REQUEST[__CLASS__.'_framework']))
        {
            $framework_default_value = $_REQUEST[__CLASS__.'_framework'];
        }
        else
        {
            $framework_default_value = "";
        }

        //filter submission
        $submission = $_REQUEST['submission'];
        if($submission != '')
        {
            $submission_default_value = $submission;
        }
        elseif(isset($_REQUEST[__CLASS__.'_submission']))
        {
            $submission_default_value = $_REQUEST[__CLASS__.'_submission'];
        }
        else
        {
            //$submission_val = DAO::getSingleValue($link, "SELECT id FROM lookup_er_submissions WHERE description = (SELECT MAX(submission) FROM ilr)");
            //$submission_val = DAO::getSingleValue($link, "SELECT MAX(submission) FROM ilr");
            //exit($submission_val);
            //$submission_default_value = $submission_val;
            $submission_default_value = "";
        }

        //filter contract_year
        $contract_year = $_REQUEST['contract_year'];
        if($contract_year != '')
        {
            $contract_year_default_value = $contract_year;
        }
        elseif(isset($_REQUEST[__CLASS__.'_contract_year']))
        {
            $contract_year_default_value = $_REQUEST[__CLASS__.'_contract_year'];
        }
        else
        {
            $contract_year_default_value = "";
        }


        //drill down
        $drill_down_by =  $_REQUEST['drill_down_by'];
        //echo "drill_down_by = ".$drill_down_by;
        if($drill_down_by != '')
        {
            $filter_drilldown_default_value = $drill_down_by;
        }
        elseif(isset($_REQUEST[__CLASS__.'_filter_drilldown']))
        {
            $filter_drilldown_default_value = $_REQUEST[__CLASS__.'_filter_drilldown'];
        }
        else
        {
            $filter_drilldown_default_value = "none";
        }


        if(isset($_REQUEST[__CLASS__.'_filter_drilldown']))
        {
            unset($_SESSION[$key]);
        }
        //echo 'session = <pre>';
        //print_r($_SESSION[$key]);exit;
        //echo $_SESSION[$key]->sql;exit;

        if(!isset($_SESSION[$key]))
		{
            // Create new view object
            $sql = "SELECT ilr.*,
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
                    LEFT JOIN lis201112.ilr_l12_ethnicity AS lisl12 ON lisl12.Ethnicity_Code = tr.ethnicity";




            //echo "sql = ".$sql;
            //pre($sql);
            $view = $_SESSION[$key] = new SlaKpiGenerateReportsProgression();
            $view->setSQL($sql);

            //Assessor filter
            $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by CONCAT(firstnames,' ',surname)";

			$f = new DropDownViewFilter('filter_assessor', $options, $assessor_default_value, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

            //Contractor filter
			$options = "SELECT id, title, null, CONCAT('WHERE ilr.contract_id=',id) FROM contracts where active =  1 order by title";
			$f = new DropDownViewFilter('filter_contract', $options, $contract_default_value, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

            // Employer Filter
			$options = "SELECT o.id, legal_name, null, CONCAT('WHERE tr.employer_id=',o.id) FROM organisations o inner join tr on o.id = tr.employer_id group by tr.employer_id order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, $employer_default_value, true);
			$f->setDescriptionFormat("Employer %s");
			$view->addFilter($f);

            // Training provider Filter
			$options = "SELECT o.id, legal_name, null, CONCAT('WHERE tr.provider_id=',o.id) FROM organisations o inner join tr on o.id = tr.provider_id group by tr.provider_id order by legal_name";
			$f = new DropDownViewFilter('filter_training_provider', $options, $training_provider_default_value, true);
			$f->setDescriptionFormat("Training Provider %s");
			$view->addFilter($f);

            // Filter Ethnicity
            /*$options = "SELECT Ethnicity_Desc, Ethnicity_Desc AS description, NULL , NULL
                        FROM lis201112.ilr_l12_ethnicity
                        UNION SELECT Ethnicity_Desc, Ethnicity_Desc, NULL , NULL
                        FROM lis201011.ilr_l12_ethnicity
                        ORDER BY description ASC";*/
            $options = "SELECT Ethnicity_Code, Ethnicity_Desc AS description, NULL , CONCAT('WHERE tr.ethnicity=',Ethnicity_Code)
                        FROM lis201112.ilr_l12_ethnicity
                        ORDER BY description ASC";
            $f = new DropDownViewFilter('filter_ethnicity', $options, $ethnicity_default_value, true);
			$f->setDescriptionFormat("Ethnicity %s");
            $view->addFilter($f);

            //filter gender
            $options = 'SELECT DISTINCT gender, gender, null, CONCAT("WHERE tr.gender=",char(39),gender,char(39)) FROM tr';
			$f = new DropDownViewFilter('filter_gender', $options, $gender_default_value, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

            //Filter course
            $options = 'SELECT DISTINCT id, title, null, CONCAT("WHERE courses.id=",id) FROM courses order by title';
			$f = new DropDownViewFilter('filter_course', $options, $course_default_value, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);


            //Filter framework
            $options = 'SELECT DISTINCT id, title, null, CONCAT("WHERE student_frameworks.id=",id) FROM student_frameworks order by title';
			$f = new DropDownViewFilter('filter_framework', $options, $framework_default_value, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);


            //// Filter submission
			$options = "SELECT description, description, null, CONCAT('WHERE ilr.submission=',quote(description)) FROM lookup_er_submissions";
			$f = new DropDownViewFilter('filter_submission', $options, $submission_default_value, true);
			$f->setDescriptionFormat("Submission: %s");
			$view->addFilter($f);


            ///// Filter Contracts year
			$options = "SELECT DISTINCT(contract_year), contract_year, null, CONCAT('WHERE c.contract_year=',contract_year) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, $contract_year_default_value, true);
			$f->setDescriptionFormat("Contract year: %s");
            $view->addFilter($f);




            //drill_down filter
            $options = array(
                /*
                5=>array('gender', 'Gender', null, null),
                6=>array('course', 'Course', null, null),
                8=>array('area_of_learning', 'Area of Learning', null, null),
                9=>array('frameworks', 'Frameworks', null, null),
                */
                0=>array('none', 'None', null, null),
                1=>array('assessor', 'Asssessor', null, null),
                2=>array('contract', 'Contract', null, null),
				3=>array('employer', 'Employer', null, null),
                4=>array('training_provider', 'Training Provider', null, null),
                5=>array('ethnicity', 'Ethnicity', null, null),
                6=>array('gender', 'Gender', null, null),
                7=>array('course', 'Course', null, null),
                8=>array('frameworks', 'Frameworks', null, null),
                9=>array('submission', 'Submission', null, null),
                10=>array('contract_year', 'Contract year', null, null),
            );

			$f = new DropDownViewFilter('filter_drilldown', $options, $filter_drilldown_default_value, false);
			$f->setDescriptionFormat("Drilldown by : %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}


   	public function render(PDO $link)
	{
        self::get_includes();

        $obj_sla_kpi_reports = new sla_kpi_reports();

        $learners_dtls_arr = array();

        $l2_lrnr_arr = array();
        $l3_lrnr_arr = array();
        $l4_lrnr_arr = array();
        $l5_lrnr_arr = array();
        $l6_lrnr_arr = array();
        $l7_lrnr_arr = array();


        $sql = $this->getSQL();
        $sql = $sql." GROUP BY ilr.L03 ORDER BY contract_year desc, submission DESC";
        //pre($sql);

		$st = $link->query($sql);

        //echo 'st = <pre>';
        //print_r($st);exit;
        $row_exists = "FALSE";

        if($st)
        {
            while($row = $st->fetch())
            {
                $row_exists = "TRUE";

                $l03 = $row['L03'];
                $learners_dtls_arr[$l03] = array();

                //insert learner details into the array
                $learners_dtls_arr[$l03] = $row;


                $qry = "SELECT * , ExtractValue( ilr, '/Learner/LearningDelivery/AimType' ) AS aim_type_values, ExtractValue( ilr, '/Learner/LearningDelivery/ProgType' ) AS prog_type_values FROM ilr WHERE L03 = '".$l03."'";

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
            //echo "row_exists = ".$row_exists;
            //pr($sql);
            //check if result data exists or no data is found
            if($row_exists == "TRUE")
            {
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

            $drill_down_by = $this->getFilterValue('filter_drilldown');
            //echo "<br>drill_down_by = ".$drill_down_by."<br>";//exit('done');


            /*if($drill_down_by == "none")
            {
                $drilldown_name="Drilldown by Contract year";
                $drilldown_title = "Contract year";
                $drilldown_col_key = "";

                //$group_by = "";
            }

            else */
            if($drill_down_by == "assessor")
            {
                $drilldown_name = "Drilldown by Assessors";
                $drilldown_title = "Assessor Name";
                $drilldown_col_key = "assessor_name";

                //$group_by = "assessor";
            }

            else if($drill_down_by == "contract")
            {
                $drilldown_name = "Drilldown by Contracts";
                $drilldown_title = "Contract Name";
                $drilldown_col_key = "contract_name";

                //$group_by = "contract_id";
            }

            elseif($drill_down_by == "employer")
            {
                $drilldown_name = "Drilldown by Employers";
                $drilldown_title = "Employer Name";
                $drilldown_col_key = "employer_name";

                //$group_by = "employer";
            }

            else if($drill_down_by == "training_provider")
            {
                $drilldown_name = "Drilldown by Training Providers";
                $drilldown_title = "Training Provider Name";
                $drilldown_col_key = "training_provider_name";

                //$group_by = "provider";
            }

            else if($drill_down_by == "ethnicity")
            {
                $drilldown_name = "Drilldown by Ethnicity";
                $drilldown_title = "Ethnicity";
                $drilldown_col_key = "ethnicity_description";

                //$group_by = "ethnicity";
            }

            else if($drill_down_by == "gender")
            {
                $drilldown_name = "Drilldown by Gender";
                $drilldown_title = "Gender";
                $drilldown_col_key = "gender";

                //$group_by = "ethnicity";
            }

            else if($drill_down_by == "course")
            {
                $drilldown_name = "Drilldown by Course";
                $drilldown_title = "Course";
                $drilldown_col_key = "course_title";

                //$group_by = "ethnicity";
            }

            else if($drill_down_by == "frameworks")
            {
                $drilldown_name = "Drilldown by Frameworks";
                $drilldown_title = "Framework";
                $drilldown_col_key = "framework_title";

                //$group_by = "ethnicity";
            }

            else if($drill_down_by == "submission")
            {
                $drilldown_name = "Drilldown by Submission";
                $drilldown_title = "Submission";
                $drilldown_col_key = "submission";

                //$group_by = "ethnicity";
            }

            else if($drill_down_by == "contract_year")
            {
                $drilldown_name = "Drilldown by Contract Year";
                $drilldown_title = "Contract Year";
                $drilldown_col_key = "contract_year";

                //$group_by = "ethnicity";
            }

            /*if($group_by != "")
            {
                $group_by = " GROUP BY ".$group_by;
            }
            $drilldown_column = "";
            if($drilldown_col_key != '')
            {
                $drilldown_column = $drilldown_col_key.", ";
            }*/


            /**********************************  For L2 to L3    **************************************/
            foreach($l2_to_l3 as $val_l03)
            {
                $learner_name = $learners_dtls_arr[$val_l03]['learner_name'];
                $gender = $learners_dtls_arr[$val_l03]['gender'];
                $unique_learner_number = $learners_dtls_arr[$val_l03]['unique_learner_number'];
                $dob = $learners_dtls_arr[$val_l03]['dob'];
                $username = $learners_dtls_arr[$val_l03]['username'];
                $home_email = $learners_dtls_arr[$val_l03]['home_email'];
                //$employer_name = $learners_dtls_arr[$val_l03]['employer_name'];
                //$contract_year = $learners_dtls_arr[$val_l03]['contract_year'];

                if($drill_down_by != "none")
                {
                    $drilldown_col_value = $learners_dtls_arr[$val_l03][$drilldown_col_key];

                    $table_l2_to_l3[] = Array($drilldown_title => $drilldown_col_value, "Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
                else
                {
                    $table_l2_to_l3[] = Array("Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
            }

            if(isset($table_l2_to_l3))
            {
                $report_l2_to_l3 = new DataMatrix(array_keys($table_l2_to_l3[0]), $table_l2_to_l3, false);
                //$report_l2_to_l3->addTotalColumns(array('Learners', 'Withdrawn', 'Non-starter'));
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L2 to L3</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo $report_l2_to_l3->to('HTML');
                echo '</div>';
            }
            else
            {
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L2 to L3</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo 'Sorry, no such learners found !';
                echo '</div>';
            }


            //if show only is set to l2tol3 then exit from here
            $show_only ="";
            if(isset($_REQUEST['show_only']) && $_REQUEST['show_only'] == "l2tol3")
            {
                $show_only = $_REQUEST['show_only'];
                //pre('here = '.$_REQUEST['show_only']);
                exit;
            }


            /**********************************  For L3 to L4    **************************************/
            foreach($l3_to_l4 as $val_l03)
            {
                $learner_name = $learners_dtls_arr[$val_l03]['learner_name'];
                $gender = $learners_dtls_arr[$val_l03]['gender'];
                $unique_learner_number = $learners_dtls_arr[$val_l03]['unique_learner_number'];
                $dob = $learners_dtls_arr[$val_l03]['dob'];
                $username = $learners_dtls_arr[$val_l03]['username'];
                $home_email = $learners_dtls_arr[$val_l03]['home_email'];
                //$employer_name = $learners_dtls_arr[$val_l03]['employer_name'];
                //$contract_year = $learners_dtls_arr[$val_l03]['contract_year'];

                if($drill_down_by != "none")
                {
                    $drilldown_col_value = $learners_dtls_arr[$val_l03][$drilldown_col_key];

                    $table_l3_to_l4[] = Array($drilldown_title => $drilldown_col_value, "Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
                else
                {
                    $table_l3_to_l4[] = Array("Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
            }

            if(isset($table_l3_to_l4))
            {
                $report_l3_to_l4 = new DataMatrix(array_keys($table_l3_to_l4[0]), $table_l3_to_l4, false);
                //$report_l3_to_l4->addTotalColumns(array('Learners', 'Withdrawn', 'Non-starter'));
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L3 to L4</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo $report_l3_to_l4->to('HTML');
                echo '</div>';
            }
            else
            {
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L3 to L4</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo 'Sorry, no such learners found !';
                echo '</div>';
            }


            /**********************************  For L4 to L5    **************************************/
            foreach($l4_to_l5 as $val_l03)
            {
                $learner_name = $learners_dtls_arr[$val_l03]['learner_name'];
                $gender = $learners_dtls_arr[$val_l03]['gender'];
                $unique_learner_number = $learners_dtls_arr[$val_l03]['unique_learner_number'];
                $dob = $learners_dtls_arr[$val_l03]['dob'];
                $username = $learners_dtls_arr[$val_l03]['username'];
                $home_email = $learners_dtls_arr[$val_l03]['home_email'];
                //$employer_name = $learners_dtls_arr[$val_l03]['employer_name'];
                //$contract_year = $learners_dtls_arr[$val_l03]['contract_year'];

                if($drill_down_by != "none")
                {
                    $drilldown_col_value = $learners_dtls_arr[$val_l03][$drilldown_col_key];

                    $table_l4_to_l5[] = Array($drilldown_title => $drilldown_col_value, "Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
                else
                {
                    $table_l4_to_l5[] = Array("Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
            }

            if(isset($table_l4_to_l5))
            {
                $report_l4_to_l5 = new DataMatrix(array_keys($table_l4_to_l5[0]), $table_l4_to_l5, false);
                //$report_l3_to_l4->addTotalColumns(array('Learners', 'Withdrawn', 'Non-starter'));
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L4 to L5</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo $report_l4_to_l5->to('HTML');
                echo '</div>';
            }
            else
            {
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L4 to L5</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo 'Sorry, no such learners found !';
                echo '</div>';
            }


            /**********************************  For L5 to L6    **************************************/
            foreach($l5_to_l6 as $val_l03)
            {
                $learner_name = $learners_dtls_arr[$val_l03]['learner_name'];
                $gender = $learners_dtls_arr[$val_l03]['gender'];
                $unique_learner_number = $learners_dtls_arr[$val_l03]['unique_learner_number'];
                $dob = $learners_dtls_arr[$val_l03]['dob'];
                $username = $learners_dtls_arr[$val_l03]['username'];
                $home_email = $learners_dtls_arr[$val_l03]['home_email'];
                //$employer_name = $learners_dtls_arr[$val_l03]['employer_name'];
                //$contract_year = $learners_dtls_arr[$val_l03]['contract_year'];

                if($drill_down_by != "none")
                {
                    $drilldown_col_value = $learners_dtls_arr[$val_l03][$drilldown_col_key];

                    $table_l5_to_l6[] = Array($drilldown_title => $drilldown_col_value, "Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
                else
                {
                    $table_l5_to_l6[] = Array("Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
            }

            if(isset($table_l5_to_l6))
            {
                $report_l5_to_l6 = new DataMatrix(array_keys($table_l5_to_l6[0]), $table_l5_to_l6, false);
                //$report_l3_to_l4->addTotalColumns(array('Learners', 'Withdrawn', 'Non-starter'));
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L5 to L6</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo $report_l5_to_l6->to('HTML');
                echo '</div>';
            }
            else
            {
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L5 to L6</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo 'Sorry, no such learners found !';
                echo '</div>';
            }


            /**********************************  For L6 to L7    **************************************/
            foreach($l6_to_l7 as $val_l03)
            {
                $learner_name = $learners_dtls_arr[$val_l03]['learner_name'];
                $gender = $learners_dtls_arr[$val_l03]['gender'];
                $unique_learner_number = $learners_dtls_arr[$val_l03]['unique_learner_number'];
                $dob = $learners_dtls_arr[$val_l03]['dob'];
                $username = $learners_dtls_arr[$val_l03]['username'];
                $home_email = $learners_dtls_arr[$val_l03]['home_email'];
                //$employer_name = $learners_dtls_arr[$val_l03]['employer_name'];
                //$contract_year = $learners_dtls_arr[$val_l03]['contract_year'];

                if($drill_down_by != "none")
                {
                    $drilldown_col_value = $learners_dtls_arr[$val_l03][$drilldown_col_key];

                    $table_l6_to_l7[] = Array($drilldown_title => $drilldown_col_value, "Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
                else
                {
                    $table_l6_to_l7[] = Array("Learner Name" => $learner_name, "Unique Learner Number" => $unique_learner_number, "Username" => $username, "DOB" => $dob, "Email-id" => $home_email);
                }
            }

            if(isset($table_l6_to_l7))
            {
                $report_l6_to_l7 = new DataMatrix(array_keys($table_l6_to_l7[0]), $table_l6_to_l7, false);
                //$report_l3_to_l4->addTotalColumns(array('Learners', 'Withdrawn', 'Non-starter'));
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L6 to L7 or above</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo $report_l6_to_l7->to('HTML');
                echo '</div>';
            }
            else
            {
                echo '<div align="center" style="margin-top:50px;">';
                echo '<h3>Learners progressed from level L6 to L7 or above</h3>';

                echo $drill_down_by != "none" ? ('<h4>'.$drilldown_name.'</h3>') : "";

                echo 'Sorry, no such learners found !';
                echo '</div>';
            }

            }
            else
            {
                echo $error_msg = "<h1 style='text-align: center;'>Sorry, no data found !</h1>";
            }
        }
    }
}
?>