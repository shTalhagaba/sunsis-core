<?php
class SlaKpiGenerateReportsRetention extends View
{
    public static function get_includes()
    {
        include_once('act_sla_kpi_reports.php');
        require_once('./lib/KPI_classes.php');
    }

    public static function createTempTable(PDO $link)
    {
        $sql = "DROP TABLE IF EXISTS `sla_kpi_retention`;
                CREATE TEMPORARY TABLE `sla_kpi_retention` (
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

    public static function getInstance(PDO $link)
	{

        //filter progress
        /*$progress = $_REQUEST['progress'];
        if($progress != '')
        {
            $progress_default_value = $progress;
        }
        elseif(isset($_REQUEST[__CLASS__.'_progress']))
        {
            $progress_default_value = $_REQUEST[__CLASS__.'_progress'];
        }
        else
        {
            $progress_default_value = 0;
        }*/




        //filter programme
        /*$programme = $_REQUEST['programme'];
        if($programme != '')
        {
            $programme_default_value = $programme;
        }
        elseif(isset($_REQUEST[__CLASS__.'_programme']))
        {
            $programme_default_value = $_REQUEST[__CLASS__.'_programme'];
        }
        else
        {
            $programme_default_value = "";
        }*/


        //filter record_status
        /*$record_status = $_REQUEST['record_status'];
        if($record_status != '')
        {
            $record_status_default_value = $record_status;
        }
        elseif(isset($_REQUEST[__CLASS__.'_record_status']))
        {
            $record_status_default_value = $_REQUEST[__CLASS__.'_record_status'];
        }
        else
        {
            $record_status_default_value = 0;
        }*/



        //filter group
        /*$group = $_REQUEST['group'];
        if($group != '')
        {
            $group_default_value = $group;
        }
        elseif(isset($_REQUEST[__CLASS__.'_group']))
        {
            $group_default_value = $_REQUEST[__CLASS__.'_group'];
        }
        else
        {
            $group_default_value = "";
        }*/



        //filter from date
        /*$from_date = $_REQUEST['from_date'];
        if($from_date != '')
        {
            $start_date_default_value = $from_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_start_date']))
        {
            $start_date_default_value = $_REQUEST[__CLASS__.'_start_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $start_date_default_value = "";
        }

        //filter to date
        $to_date = $_REQUEST['to_date'];
        if($to_date != '')
        {
            $end_date_default_value = $to_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_end_date']))
        {
            $end_date_default_value = $_REQUEST[__CLASS__.'_end_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $end_date_default_value = "";
        }


//filter from date
        $from_date = $_REQUEST['from_date'];
        if($from_date != '')
        {
            $start_date_default_value = $from_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_start_date']))
        {
            $start_date_default_value = $_REQUEST[__CLASS__.'_start_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $start_date_default_value = "";
        }

        //filter to date
        $to_date = $_REQUEST['to_date'];
        if($to_date != '')
        {
            $end_date_default_value = $to_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_end_date']))
        {
            $end_date_default_value = $_REQUEST[__CLASS__.'_end_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $end_date_default_value = "";
        }

        //filter target_start_date
        $target_start_date = $_REQUEST['target_start_date'];
        if($target_start_date != '')
        {
            $target_start_date_default_value = $target_start_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_target_start_date']))
        {
            $target_start_date_default_value = $_REQUEST[__CLASS__.'_target_start_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $target_start_date_default_value = "";
        }

        //filter $target_end_date
        $target_end_date = $_REQUEST['target_end_date'];
        if($target_end_date != '')
        {
            $target_end_date_default_value = $target_end_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_target_end_date']))
        {
            $target_end_date_default_value = $_REQUEST[__CLASS__.'_target_end_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $target_end_date_default_value = "";
        }

        //filter closure_start_date
        $closure_start_date = $_REQUEST['closure_start_date'];
        if($closure_start_date != '')
        {
            $closure_start_date_default_value = $closure_start_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_closure_start_date']))
        {
            $closure_start_date_default_value = $_REQUEST[__CLASS__.'_closure_start_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $closure_start_date_default_value = "";
        }

        //filter closure_end_date
        $closure_end_date = $_REQUEST['closure_end_date'];
        if($closure_end_date != '')
        {
            $closure_end_date_default_value = $closure_end_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_closure_end_date']))
        {
            $closure_end_date_default_value = $_REQUEST[__CLASS__.'_closure_end_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $closure_end_date_default_value = "";
        }

        //filter work_experience_start_date
        $work_experience_start_date = $_REQUEST['work_experience_start_date'];
        if($work_experience_start_date != '')
        {
            $work_experience_start_date_default_value = $work_experience_start_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_work_experience_start_date']))
        {
            $work_experience_start_date_default_value = $_REQUEST[__CLASS__.'_work_experience_start_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $work_experience_start_date_default_value = "";
        }

        //filter to date
        $work_experience_end_date = $_REQUEST['work_experience_end_date'];
        if($work_experience_end_date != '')
        {
            $work_experience_end_date_default_value = $work_experience_end_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_work_experience_end_date']))
        {
            $work_experience_end_date_default_value = $_REQUEST[__CLASS__.'_work_experience_end_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $work_experience_end_date_default_value = "";
        }*/

        error_reporting(E_ALL^E_NOTICE);
        $key = 'view_'.__CLASS__;
        //echo 'key = '.$key;exit;
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode'] != '')
        {
            unset($_SESSION[$key]);
        }//unset($_SESSION[$key]);


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

        //filter contract
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


            $sql = "SELECT ilr.*, c.*,
                    tr.assessor as assessor_id, concat(assessorsng.firstnames,' ', assessorsng.surname) as assessor_name,
                    tr.provider_id, providers.legal_name as training_provider_name,
                    tr.employer_id, employers.legal_name as employer_name,
                    ilr.contract_id, c.title as contract_name,
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
            $view = $_SESSION[$key] = new SlaKpiGenerateReportsRetention();
            $view->setSQL($sql);


			// Add view filters
			/*$options = array(
                //0=>array(5,5,null,null),
                0=>array(10,10,null,null),
				1=>array(20,20,null,null),
				2=>array(50,50,null,null),
				3=>array(100,100,null,null),
				4=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 10, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);*/

            // Sort by Qualification title
		   	//$options = array(
//				0=>array(1, 'internaltitle (asc)', null, 'ORDER BY sq.internaltitle'),
//				1=>array(2, 'internaltitle (desc)', null, 'ORDER BY sq.internaltitle DESC'));
//			$f = new DropDownViewFilter('order_by', $options, 1, false);
//			$f->setDescriptionFormat("Sort by: %s");
//			$view->addFilter($f);

            //start_date filter
            /*$format = "WHERE tr.start_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, $start_date_default_value);
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

            //end_date filter
			$format = "WHERE tr.start_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, $end_date_default_value);
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);


            // Target date filter
			$format = "WHERE tr.target_date >= '%s'";
			$f = new DateViewFilter('target_start_date', $format, $target_start_date_default_value);
			$f->setDescriptionFormat("From target date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week

			$format = "WHERE target_date <= '%s'";
			$f = new DateViewFilter('target_end_date', $format, $target_end_date_default_value);
			$f->setDescriptionFormat("To target date: %s");
			$view->addFilter($f);


			// Closure date filter
			$format = "WHERE tr.closure_date >= '%s'";
			$f = new DateViewFilter('closure_start_date', $format, $closure_start_date_default_value);
			$f->setDescriptionFormat("From closure date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week

			$format = "WHERE tr.closure_date <= '%s'";
			$f = new DateViewFilter('closure_end_date', $format, $closure_end_date_default_value);
			$f->setDescriptionFormat("To closure date: %s");
			$view->addFilter($f);*/

			// Work Experience Dates Filter
			//$format = "WHERE workplace_visits.end_date >= '%s'";
//			$f = new DateViewFilter('work_experience_start_date', $format, $work_experience_start_date_default_value);
//			$f->setDescriptionFormat("From work experience date: %s");
//			$view->addFilter($f);
//
//
//			$format = "WHERE workplace_visits.end_date <= '%s'";
//			$f = new DateViewFilter('work_experience_end_date', $format, $work_experience_end_date_default_value);
//			$f->setDescriptionFormat("To work experience date: %s");
//			$view->addFilter($f);




            //Filter group
			/*$options = 'SELECT groups.id, CONCAT(courses.title, "::" , groups.title), null, CONCAT("WHERE group_members.groups_id=",groups.id) FROM groups INNER JOIN courses on courses.id = groups.courses_id order by CONCAT(courses.title, "::" , groups.title)';
			$f = new DropDownViewFilter('filter_group', $options, $group_default_value, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);*/

            // record status Filter
			/*$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
				2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
				3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
				4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
				5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
				6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'));
			$f = new DropDownViewFilter('filter_record_status', $options, $record_status_default_value, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);*/


            //filter progress
            /*$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'On track', null, 'HAVING progress="On Track"'),
				2=>array(2, 'Behind', null, 'HAVING progress="Behind"'));
			$f = new DropDownViewFilter('filter_progress', $options, $progress_default_value, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);*/

            //filter programme
            /*$options = 'SELECT code, description, null, CONCAT("WHERE courses.programme_type=",char(39),code,char(39)) FROM lookup_programme_type';
			$f = new DropDownViewFilter('filter_programme', $options, $programme_default_value, true);
			$f->setDescriptionFormat("Programme: %s");
			$view->addFilter($f);*/





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


            ////Filter valid
            $options = array(
                0=>array('all', 'All', null, ' where ilr.is_valid = "1" OR ilr.is_valid != "1"'),
                1=>array('valid', 'Valid', null, ' where ilr.is_valid = "1"'),
                2=>array('invalid', 'Invalid', null, ' where ilr.is_valid != "1"')
            );
            $f = new DropDownViewFilter('filter_valid', $options, $valid_default_value, false);
            $f->setDescriptionFormat("Validity: %s");
            $view->addFilter($f);


            ////Filter active
            $options = array(
                0=>array('all', 'All', null, ' where ilr.is_active = "1" OR ilr.is_active != "1"'),
                1=>array('active', 'Active', null, ' where ilr.is_active = "1"'),
                2=>array('inactive', 'Not Active', null, ' where ilr.is_active != "1"')
            );
            $f = new DropDownViewFilter('filter_active', $options, $active_default_value, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);


            //// Filter submission
			$options = "SELECT description, description, null, CONCAT('WHERE ilr.submission=',quote(description)) FROM lookup_er_submissions";
			$f = new DropDownViewFilter('filter_submission', $options, $submission_default_value, true);
			$f->setDescriptionFormat("Submission: %s");
			$view->addFilter($f);

            ///// Filter Contracts year
			$options = "SELECT DISTINCT(contract_year), contract_year, null, CONCAT('WHERE c.contract_year=',contract_year) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, $contract_year_default_value, false);
			$f->setDescriptionFormat("Contract year: %s");
			$view->addFilter($f);


            //drill_down filter
            $options = array(
                /*
                1=>array('quarter', 'Quarter', null, null),
                2=>array('month', 'Month', null, null),
                3=>array('week', 'Week', null, null),
                8=>array('age_range', 'Age Range', null, null),
                10=>array('disability', 'Disability', null, null),
                13=>array('tutor', 'Group tutor', null, null),
                14=>array('learning_difficulty', 'Learning difficulty', null, null),
                15=>array('progress', 'Progress', null, null),
                16=>array('mainarea', 'Qualification Subject Sector Area', null, null),
                17=>array('subarea', 'Qualification Subject Sector Subarea', null, null),
                18=>array('record_status', 'Record status', null, null),
                19=>array('verifier', 'Verifier', null, null),
                20=>array('work_experience_coordinator', 'Work Experience Coordinator', null, null),
                21=>array('actual_work_experience', 'Work Experience Days', null, null),
                22=>array('work_experience_band_10', 'Work Experience Visits 10 Days Band', null, null)
                */
                0=>array('none', 'None', null, null),
                1=>array('assessor', 'Asssessor', null, null),
                2=>array('contract', 'Contract', null, null),
				3=>array('employer', 'Employer', null, null),
                4=>array('training_provider', 'Training Provider', null, null),
                5=>array('gender', 'Gender', null, null),
                6=>array('course', 'Course', null, null),
                7=>array('ethnicity', 'Ethnicity', null, null),
                8=>array('area_of_learning', 'Area of Learning', null, null),
                9=>array('frameworks', 'Frameworks', null, null),
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

        $submission = $this->getFilterValue('filter_submission');

        if($submission != "")
        {
            $submission_arr = array($submission);
            $new_query_with_submission = $this->getSQL();
        }
        else
        {
            $submission_details = $obj_sla_kpi_reports->get_submissions($link, $mode="all", $idarray=array());

            $submission_arr = array();
            foreach($submission_details as $submission_dtls)
            {
                array_push($submission_arr, $submission_dtls['description']);
            }
        }

        //pre($submission_arr);

        foreach($submission_arr as $submission_value)
        {
            $old_query = $this->getSQL();
            $new_query_with_submission = $old_query." AND (ilr.submission='".$submission_value."')";
            //pr($new_query_with_submission);
    		$st = $link->query($new_query_with_submission);

            if($st)
            {
                self::createTempTable($link);

                while($row = $st->fetch())
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
                    $framework_title = mysql_real_escape_string($row['framework_title']);
                    $course_id = $row['course_id'];
                    $course_title = mysql_real_escape_string($row['course_title']);
                    //$ethnicity_id = $row['ethnicity_id'];
                    $ethnicity_description = mysql_real_escape_string($row['ethnicity_description']);
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

                                //DAO::execute($link, "insert into sla_kpi_retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type','$assessor_id', '$assessor_name', '$provider_id', '$training_provider_name', '$employer_id', '$employer_name', '$contract_id', '$contract_name', '$framework_id','$framework_title', '$course_id', '$course_title', '$ethnicity_id', '$ethnicity_description', '$cntrct_year')");
                                DAO::execute($link, "insert into sla_kpi_retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type', '$provider_id', '$training_provider_name', '$contract_id', '$contract_name', '$framework_title', '$course_id', '$course_title', '$ethnicity_description', '$cntrct_year')");
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

                                //DAO::execute($link, "insert into sla_kpi_retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type','$assessor_id', '$assessor_name', '$provider_id', '$training_provider_name', '$employer_id', '$employer_name', '$contract_id', '$contract_name', '$framework_id','$framework_title', '$course_id', '$course_title', '$ethnicity_id', '$ethnicity_description', '$cntrct_year')");
                                DAO::execute($link, "insert into sla_kpi_retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type', '$provider_id', '$training_provider_name', '$contract_id', '$contract_name', '$framework_title', '$course_id', '$course_title', '$ethnicity_description', '$cntrct_year')");
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

                            //DAO::execute($link, "insert into sla_kpi_retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type','$assessor_id', '$assessor_name', '$provider_id', '$training_provider_name', '$employer_id', '$employer_name', '$contract_id', '$contract_name', '$framework_id','$framework_title', '$course_id', '$course_title', '$ethnicity_id', '$ethnicity_description', '$cntrct_year')");
                            DAO::execute($link, "insert into sla_kpi_retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$a27',$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type', '$provider_id', '$training_provider_name', '$contract_id', '$contract_name', '$framework_title', '$course_id', '$course_title', '$ethnicity_description', '$cntrct_year')");
                        }
                    }
                }

                DAO::execute($link, "UPDATE sla_kpi_retention INNER JOIN lad201213.`all_annual_values` ON lad201213.`all_annual_values`.`LEARNING_AIM_REF` = a09 INNER JOIN lad201213.`ssa_tier1_codes` ON lad201213.`all_annual_values`.`SSA_TIER1_CODE` = lad201213.`ssa_tier1_codes`.`SSA_TIER1_CODE` SET ssa=SSA_TIER1_DESC;");
                DAO::execute($link, "UPDATE sla_kpi_retention SET ssa=a09 where ssa = '' or ssa is null;");
                DAO::execute($link, "UPDATE sla_kpi_retention INNER JOIN tr on tr.id = sla_kpi_retention.tr_id inner join users on users.id = tr.assessor set sla_kpi_retention.assessor = concat(users.firstnames,' ',users.surname)");
                DAO::execute($link, "UPDATE sla_kpi_retention INNER JOIN tr on tr.id = sla_kpi_retention.tr_id inner join organisations on organisations.id = tr.employer_id set sla_kpi_retention.employer = organisations.legal_name");

                //DAO::execute($link, "drop table retention2");
                //DAO::execute($link, "create table retention2 select * from sla_kpi_retention");



                /*
                $assessor_id = $this->getFilterValue('filter_assessor');
                echo "<br>assessor_id = ".$assessor_id."<br>";//exit('done');
                $contract_id = $this->getFilterValue('filter_contract');
                echo "<br>contract_id = ".$contract_id."<br>";//exit('done');
                $employer_id = $this->getFilterValue('filter_employer');
                echo "<br>employer_id = ".$employer_id."<br>";//exit('done');
                $training_provider_id = $this->getFilterValue('filter_training_provider');
                echo "<br>training_provider_id = ".$training_provider_id."<br>";//exit('done');
                $gender = $this->getFilterValue('filter_gender');
                echo "<br>gender = ".$gender."<br>";//exit('done');
                $course_id = $this->getFilterValue('filter_course');
                echo "<br>course_id = ".$course_id."<br>";//exit('done');
                $framework_id = $this->getFilterValue('filter_framework');
                echo "<br>framework_id = ".$framework_id."<br>";//exit('done');
                $valid = $this->getFilterValue('filter_valid');
                echo "<br>valid = ".$valid."<br>";//exit('done');
                $active = $this->getFilterValue('filter_active');
                echo "<br>active = ".$active."<br>";//exit('done');
                $submission = $this->getFilterValue('filter_submission');
                echo "<br>submission = ".$submission."<br>";//exit('done');
                */
                $contract_year = $this->getFilterValue('filter_contract_year');
                //echo "<br>contract_year = ".$contract_year."<br>";//exit('done');

                $drill_down_by = $this->getFilterValue('filter_drilldown');
                //echo "<br>drill_down_by = ".$drill_down_by."<br>";//exit('done');

                if($drill_down_by == "none")
                {
                    $drilldown_name="Drilldown by Contract year";
                    $drilldown_title = "Contract year";
                    $drilldown_col_key = "contract_year";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, contract_year FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY contract_year;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "assessor")
                {
                    $drilldown_name = "Drilldown by Assessors";
                    $drilldown_title = "Assessors";
                    $drilldown_col_key = "assessor";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, assessor FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY assessor;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "contract")
                {
                    $drilldown_name = "Drilldown by Contractors";
                    $drilldown_title = "Contractors";
                    $drilldown_col_key = "contract_name";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, contract_name FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY contract_id;",$options=DAO::FETCH_BOTH);
                }

                elseif($drill_down_by == "employer")
                {
                    $drilldown_name = "Drilldown by Employers";
                    $drilldown_title = "Employers";
                    $drilldown_col_key = "employer";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, employer FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY employer;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "training_provider")
                {
                    $drilldown_name = "Drilldown by Training Providers";
                    $drilldown_title = "Training Providers";
                    $drilldown_col_key = "training_provider_name";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, training_provider_name FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY provider_id;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "gender")
                {
                    $drilldown_name = "Drilldown by Gender";
                    $drilldown_title = "Gender";
                    $drilldown_col_key = "gender";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,gender FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY gender;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "course")
                {
                    $drilldown_name = "Drilldown by Course";
                    $drilldown_title = "Course";
                    $drilldown_col_key = "course_title";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, course_title FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY course_id;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "ethnicity")
                {
                    $drilldown_name = "Drilldown by Ethnicity";
                    $drilldown_title = "Ethnicity";
                    $drilldown_col_key = "ethnicity_description";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, ethnicity_description FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY ethnicity;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "area_of_learning")
                {
                    $drilldown_name = "Drilldown by Area of Learning";
                    $drilldown_title = "Area of Learning";
                    $drilldown_col_key = "ssa";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, ssa  FROM sla_kpi_retention WHERE a09 != 'ZPROG001' AND prog_type IN (2,3,20,21) GROUP BY ssa;",$options=DAO::FETCH_BOTH);
                }

                else if($drill_down_by == "frameworks")
                {
                    $drilldown_name = "Drilldown by Frameworks";
                    $drilldown_title = "Frameworks";
                    $drilldown_col_key = "framework_title";

                    $p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,(SELECT CONCAT(FRAMEWORK_CODE,' - ',FRAMEWORK_DESC) FROM lad201213.frameworks AS f WHERE f.FRAMEWORK_CODE = fcode AND f.FRAMEWORK_TYPE_CODE = prog_type LIMIT 0,1) AS framework_title FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY fcode;",$options=DAO::FETCH_BOTH);

                    //$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,(SELECT CONCAT(FRAMEWORK_CODE,' - ',FRAMEWORK_DESC) FROM lad201213.frameworks AS f WHERE f.FRAMEWORK_CODE = fcode AND f.FRAMEWORK_TYPE_CODE = prog_type LIMIT 0,1) AS title, framework_title FROM sla_kpi_retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY framework_id;",$options=DAO::FETCH_BOTH);
                }



                //pr($p);


                foreach($p as $framework)
                {//pre($framework);
                    if($framework['learners']>0)
                        $apps_by_aol[] = Array($drilldown_title => $framework[$drilldown_col_key], "Learners" => $framework['learners'], "Withdrawn" => $framework['withdrawn'], "Non-starter" => $framework['non'], "Percentage" => sprintf("%.2f",100 - ($framework['withdrawn']/$framework['learners']*100)) . "%");
                }

                //pr($apps_by_aol);

                echo '<div align="center" style="margin-top:50px;">';
                echo '<h2>Submission : '.$submission_value.'</h2>';
                echo '</div>';

                if(isset($apps_by_aol))
                {
                    $report_apps_by_aol = new DataMatrix(array_keys($apps_by_aol[0]), $apps_by_aol, false);
                    //pr($report_apps_by_aol);
                    $report_apps_by_aol->addTotalColumns(array('Learners', 'Withdrawn', 'Non-starter'));

                    echo '<div align="center">';
                    echo '<h4>Contract year : '.$contract_year.'</h4>';
    				echo '<h3>'.$drilldown_name.'</h3>';

                    echo $report_apps_by_aol->to('HTML');
                    echo '</div>';
                    unset($apps_by_aol);
                }
                else
                {
                    echo $error_msg = "<h1 style='text-align: center;'>Sorry, no data found !</h1>";
                }
            }
            else
            {
                throw new DatabaseException($link, $this->getSQL());
            }

        }
    }
}
?>