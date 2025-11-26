<?php
class SlaKpiGenerateReportsLearners extends View
{
    public static function get_includes()
    {
        include_once('act_sla_kpi_reports.php');
    }
    public static function getInstance(PDO $link)
	{
        error_reporting(E_ALL^E_NOTICE);
        $key = 'view_'.__CLASS__;
        //echo 'key = '.$key;exit;
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode'] != '')
        {
            unset($_SESSION[$key]);
        }


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

        //filter progress
        $progress = $_REQUEST['progress'];
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


        //filter programme
        $programme = $_REQUEST['programme'];
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
        }


        //filter record_status
        $record_status = $_REQUEST['record_status'];
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

        //filter group
        $group = $_REQUEST['group'];
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
        //echo 'innnnnnnn';
        //echo "filter_drilldown_default_value = ".$filter_drilldown_default_value;

            $group_by ="";
            if($filter_drilldown_default_value == "quarter")
            {
                $group_by = " GROUP BY year, year_and_quarter ORDER BY year, year_and_quarter ";
            }
            else if($filter_drilldown_default_value == "month")
            {
                $group_by = " GROUP BY year, year_and_month ORDER BY year, month ";
            }
            else if($filter_drilldown_default_value == "week")
            {
                $group_by = " GROUP BY year, week ORDER BY year, week ";
            }
            else if($filter_drilldown_default_value == 'employer')
            {
                $group_by = " GROUP BY year, tr.employer_id ORDER BY year, employer_name";
            }
            else if($filter_drilldown_default_value == 'training_provider')
            {
                $group_by = " GROUP BY year, tr.provider_id ORDER BY year, training_provider_name";
            }
            else if($filter_drilldown_default_value == 'contract')
            {
                $group_by = " GROUP BY year, tr.contract_id ORDER BY year, contract_name";
            }
            else if($filter_drilldown_default_value == 'assessor')
            {
                $group_by = " GROUP BY year, tr.assessor ORDER BY year, assessor_name";
            }
            else if($filter_drilldown_default_value == 'age_range')
            {
                $group_by = " GROUP BY year, age ORDER BY year, age";
            }
            else if($filter_drilldown_default_value == 'course')
            {
                $group_by = " GROUP BY year, courses.id ORDER BY year, course_title";
            }
            else if($filter_drilldown_default_value == 'disability')
            {
                $group_by = " GROUP BY year, disability ORDER BY year, disability";
            }
            else if($filter_drilldown_default_value == 'ethnicity')
            {
                $group_by = " GROUP BY year, ethnicity ORDER BY year, ethnicity";
            }
            else if($filter_drilldown_default_value == 'gender')
            {
                $group_by = " GROUP BY year, gender ORDER BY year, gender";
            }
            else if($filter_drilldown_default_value == 'tutor')
            {
                $group_by = " GROUP BY year, tutor ORDER BY year, tutor";
            }
            else if($filter_drilldown_default_value == 'learning_difficulty')
            {
                $group_by = " GROUP BY year, learning_difficulty ORDER BY year, learning_difficulty";
            }
            else if($filter_drilldown_default_value == 'progress')
            {
                $group_by = " GROUP BY year, progress ORDER BY year, progress";
            }
            else if($filter_drilldown_default_value == 'mainarea')
            {
                $group_by = " GROUP BY year, mainarea ORDER BY year, mainarea";
            }
            else if($filter_drilldown_default_value == 'subarea')
            {
                $group_by = " GROUP BY year, internaltitle ORDER BY year, internaltitle";
            }
            else if($filter_drilldown_default_value == 'record_status')
            {
                $group_by = " GROUP BY year, record_status ORDER BY year, record_status";
            }
            else if($filter_drilldown_default_value == 'verifier')
            {
                $group_by = " GROUP BY year, verifiers.id ORDER BY year, verifier";
            }
            else if($filter_drilldown_default_value == 'work_experience_coordinator')
            {
                $group_by = " GROUP BY year, wbcoordinators.id ORDER BY year, wbcoordinator";
            }
            else if($filter_drilldown_default_value == 'actual_work_experience')
            {
                $group_by = " GROUP BY year, actual_work_experience ORDER BY year, actual_work_experience";
            }
            else if($filter_drilldown_default_value == 'work_experience_band_10')
            {
                $group_by = " GROUP BY year, band0to10 ORDER BY year, band0to10";
            }
            else
            {
                $group_by = " GROUP BY year ORDER BY year";
            }

            $sql = "
SELECT DISTINCT
				year( tr.start_date ) AS year, count( tr.id ) AS learner_count,
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
                    weekofyear( tr.start_date ) AS week,
				tr.id AS tr_id,
                users.gender AS gender,
                CONCAT(lisl12.Ethnicity_Code, ' ', lisl12.Ethnicity_Desc) AS ethnicity,
                CONCAT(lisl15.Disability_Code, ' ', lisl15.Disability_Desc) AS disability,
				IF(tr.target_date < CURDATE(),IF(tr.l36 >= 100,'On Track', 'Behind'),IF(`subquery`.result IS NULL
                                                                                    OR tr.l36 >= `subquery`.result, 'On Track', 'Behind')) AS progress,

                IF((DATEDIFF(tr.start_date, tr.dob)/365)>=16
                   AND (DATEDIFF(tr.start_date, tr.dob)/365)<19, '16-18', IF((DATEDIFF(tr.start_date, tr.dob)/365)>=19
                                                                             AND (DATEDIFF(tr.start_date, tr.dob)/365)<=25, '19-24', IF((DATEDIFF(tr.start_date, tr.dob)/365)>25, '25+', 'Unknown'))) AS age,
                CONCAT(lisl16.Difficulty_Code, ' ', lisl16.Difficulty_Desc) AS learning_difficulty,
                courses.title AS course_title,
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
                IF(tr.target_date < CURDATE(),100,`subquery`.result) AS target,
				employers.legal_name as employer_name,
				providers.legal_name as training_provider_name,
				contracts.title AS contract_name,
				concat( users.firstnames, users.surname ) AS assessor_name

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
( SELECT qualifications.mainarea,
         qualifications.internaltitle,
         qualifications.level,
         tr_id
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
           CASE timestampdiff(MONTH, student_qualifications.start_date, CURDATE()) WHEN -1 THEN 0 WHEN -2 THEN 0 WHEN -3 THEN 0 WHEN -4 THEN 0 WHEN -5 THEN 0 WHEN -6 THEN 0 WHEN -7 THEN 0 WHEN -8 THEN 0 WHEN -9 THEN 0 WHEN -10 THEN 0 WHEN 0 THEN 0 WHEN 1 THEN AVG(student_milestones.month_1) WHEN 2 THEN AVG(student_milestones.month_2) WHEN 3 THEN AVG(student_milestones.month_3) WHEN 4 THEN AVG(student_milestones.month_4) WHEN 5 THEN AVG(student_milestones.month_5) WHEN 6 THEN AVG(student_milestones.month_6) WHEN 7 THEN AVG(student_milestones.month_7) WHEN 8 THEN AVG(student_milestones.month_8) WHEN 9 THEN AVG(student_milestones.month_9) WHEN 10 THEN AVG(student_milestones.month_10) WHEN 11 THEN AVG(student_milestones.month_11) WHEN 12 THEN AVG(student_milestones.month_12) WHEN 13 THEN AVG(student_milestones.month_13) WHEN 14 THEN AVG(student_milestones.month_14) WHEN 15 THEN AVG(student_milestones.month_15) WHEN 16 THEN AVG(student_milestones.month_16) WHEN 17 THEN AVG(student_milestones.month_17) WHEN 18 THEN AVG(student_milestones.month_18) WHEN 19 THEN AVG(student_milestones.month_19) WHEN 20 THEN AVG(student_milestones.month_20) WHEN 21 THEN AVG(student_milestones.month_21) WHEN 22 THEN AVG(student_milestones.month_22) WHEN 23 THEN AVG(student_milestones.month_23) WHEN 24 THEN AVG(student_milestones.month_24) WHEN 25 THEN AVG(student_milestones.month_25) WHEN 26 THEN AVG(student_milestones.month_26) WHEN 27 THEN AVG(student_milestones.month_27) WHEN 28 THEN AVG(student_milestones.month_28) WHEN 29 THEN AVG(student_milestones.month_29) WHEN 30 THEN AVG(student_milestones.month_30) WHEN 31 THEN AVG(student_milestones.month_31) WHEN 32 THEN AVG(student_milestones.month_32) WHEN 33 THEN AVG(student_milestones.month_33) WHEN 34 THEN AVG(student_milestones.month_34) WHEN 35 THEN AVG(student_milestones.month_35) WHEN 36 THEN AVG(student_milestones.month_36) ELSE 100 END AS target
    FROM student_milestones
    LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.`qualification_id`
    AND student_milestones.tr_id = student_qualifications.`tr_id`
    AND student_qualifications.aptitude != 1
    GROUP BY student_milestones.`tr_id`,
             student_milestones.`qualification_id`) AS `sub` ON tr.id = `sub`.tr_id
 GROUP BY tr.`id` ) AS `subquery` ON `subquery`.tr_id = tr.id
 ".$group_by;

            //echo "sql = ".$sql;
            //pre($sql);
            $view = $_SESSION[$key] = new SlaKpiGenerateReportsLearners();
            $view->setSQL($sql);


			// Add view filters
			$options = array(
                //0=>array(5,5,null,null),
                0=>array(10,10,null,null),
				1=>array(20,20,null,null),
				2=>array(50,50,null,null),
				3=>array(100,100,null,null),
				4=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 10, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

            // Sort by Qualification title
		   	//$options = array(
//				0=>array(1, 'internaltitle (asc)', null, 'ORDER BY sq.internaltitle'),
//				1=>array(2, 'internaltitle (desc)', null, 'ORDER BY sq.internaltitle DESC'));
//			$f = new DropDownViewFilter('order_by', $options, 1, false);
//			$f->setDescriptionFormat("Sort by: %s");
//			$view->addFilter($f);

            //start_date filter
            $format = "WHERE tr.start_date >= '%s'";
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
			$view->addFilter($f);

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

            //Filter group
			$options = 'SELECT groups.id, CONCAT(courses.title, "::" , groups.title), null, CONCAT("WHERE group_members.groups_id=",groups.id) FROM groups INNER JOIN courses on courses.id = groups.courses_id order by CONCAT(courses.title, "::" , groups.title)';
			$f = new DropDownViewFilter('filter_group', $options, $group_default_value, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);

            // record status Filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
				2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
				3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
				4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
				5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
				6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'));
			$f = new DropDownViewFilter('filter_record_status', $options, $record_status_default_value, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);


            //filter progress
            $options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'On track', null, 'HAVING progress="On Track"'),
				2=>array(2, 'Behind', null, 'HAVING progress="Behind"'));
			$f = new DropDownViewFilter('filter_progress', $options, $progress_default_value, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);

            //filter gender
            $options = 'SELECT DISTINCT gender, gender, null, CONCAT("WHERE tr.gender=",char(39),gender,char(39)) FROM tr';
			$f = new DropDownViewFilter('filter_gender', $options, $gender_default_value, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

            //filter programme
            $options = 'SELECT code, description, null, CONCAT("WHERE courses.programme_type=",char(39),code,char(39)) FROM lookup_programme_type';
			$f = new DropDownViewFilter('filter_programme', $options, $programme_default_value, true);
			$f->setDescriptionFormat("Programme: %s");
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

            //Assessor filter
            $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by CONCAT(firstnames,' ',surname)";

			$f = new DropDownViewFilter('filter_assessor', $options, $assessor_default_value, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

            //Contractor filter
			$options = "SELECT id, title, null, CONCAT('WHERE tr.contract_id=',id) FROM contracts where active =  1 order by title";
			$f = new DropDownViewFilter('filter_contract', $options, $contract_default_value, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);


            //drill_down filter
            $options = array(
				0=>array('none', 'None', null, null),
                1=>array('quarter', 'Quarter', null, null),
                2=>array('month', 'Month', null, null),
                3=>array('week', 'Week', null, null),
				4=>array('employer', 'Employer', null, null),
				5=>array('contract', 'Contract', null, null),
                6=>array('training_provider', 'Training Provider', null, null),
                7=>array('assessor', 'Asssessor', null, null),
                8=>array('age_range', 'Age Range', null, null),
                9=>array('course', 'Course', null, null),
                10=>array('disability', 'Disability', null, null),
                11=>array('ethnicity', 'Ethnicity', null, null),
                12=>array('gender', 'Gender', null, null),
                13=>array('tutor', 'Group tutor', null, null),
                14=>array('learning_difficulty', 'Learning difficulty', null, null),
                15=>array('progress', 'Progress', null, null),
                16=>array('mainarea', 'Qualification Subject Sector Area', null, null),
                17=>array('subarea', 'Qualification Subject Sector Subarea', null, null),
                18=>array('record_status', 'Record status', null, null),
                19=>array('verifier', 'Verifier', null, null),
                //8=>array('monthly_work_experience', 'Work Experience by Month', null, null),
                20=>array('work_experience_coordinator', 'Work Experience Coordinator', null, null),
                21=>array('actual_work_experience', 'Work Experience Days', null, null),
                22=>array('work_experience_band_10', 'Work Experience Visits 10 Days Band', null, null)
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
        $stu_quali_dtls_arr = array();

        //echo '<pre>';
        //print_r($this->getFilterValue('filter_drilldown'));exit;
        $from_date1 = $this->getFilterValue('start_date');

        //echo "from_date1 = ".$from_date1;exit;
        if($from_date1 != '')
        {
            $from_date1 = str_replace('/', '-', $from_date1);
            $from_date = date("Y-m-d",strtotime("$from_date1"));
        }
        else
        {
            $from_date = "";
        }

        $to_date1 = $this->getFilterValue('end_date');

        if($to_date1 != '')
        {
            $to_date1 = str_replace('/', '-', $to_date1);
            $to_date = date("Y-m-d",strtotime("$to_date1"));
        }
        else
        {
            $to_date = "";
        }

        //$employer_id = $this->getFilterValue('filter_employer');
//        $contract_id = $this->getFilterValue('filter_contract');
//        $training_provider_id = $this->getFilterValue('filter_training_provider');
//        $assessor_username = $this->getFilterValue('filter_assessor');

        //echo 'from_date1 = '.$from_date1.' to_date1 = '.$to_date1.'<br>';
        //echo 'from_date = '.$from_date.' to_date = '.$to_date.'<br>';



        $drill_down_by = $this->getFilterValue('filter_drilldown');
        //echo "<br>drill_down_by = ".$drill_down_by."<br>";
        if($drill_down_by == "none")
        {
            $drill_down_by="";
        }

        //echo 'sql = '.$this->getSQL();//exit;
		$st = $link->query($this->getSQL());

        //echo 'st = <pre>';
        //print_r($st);exit;

        $error_msg = "<h1 style='text-align: center;'>Sorry, no data found !</h1>";
        $rows_exist="false";

		if($st)
		{
            $table_head = $this->getViewNavigator();
			$table_head .= '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6" style="width:1230px;">';

			$table_body = '<tbody>';

            if($drill_down_by == "quarter")
            {
                $drilldown_name = "Drilldown by Quarter";
                $drilldown_title = "Quarter";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $quarter = $row['quarter_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$quarter.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            elseif($drill_down_by == "month")
            {
                $drilldown_name = "Drilldown by Month";
                $drilldown_title = "Month";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $month = $row['month_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$month.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            elseif($drill_down_by == "week")
            {
                $drilldown_name = "Drilldown by Week";
                $drilldown_title = "Week";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $week = $row['week'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$week.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            elseif($drill_down_by == "employer")
            {
                $drilldown_name = "Drilldown by Employers";
                $drilldown_title = "Employers";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $employer_name = $row['employer_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$employer_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            else if($drill_down_by == "training_provider")
            {
                $drilldown_name = "Drilldown by Training Providers";
                $drilldown_title = "Training Providers";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $training_provider_name = $row['training_provider_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$training_provider_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            else if($drill_down_by == "contract")
            {
                $drilldown_name = "Drilldown by Contractors";
                $drilldown_title = "Contractors";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $contractor_name = $row['contract_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$contractor_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "assessor")
            {
                $drilldown_name = "Drilldown by Assessors";
                $drilldown_title = "Assessors";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $assessor_name = $row['assessor_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$assessor_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }


            else if($drill_down_by == "age_range")
            {
                $drilldown_name = "Drilldown by Age range";
                $drilldown_title = "Age range";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $age = $row['age'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$age.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "course")
            {
                $drilldown_name = "Drilldown by Course";
                $drilldown_title = "Course";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $course_title = $row['course_title'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$course_title.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "disability")
            {
                $drilldown_name = "Drilldown by Disability";
                $drilldown_title = "Disability";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $disability = $row['disability'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$disability.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "ethnicity")
            {
                $drilldown_name = "Drilldown by Ethnicity";
                $drilldown_title = "Ethnicity";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $ethnicity = $row['ethnicity'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$ethnicity.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "gender")
            {
                $drilldown_name = "Drilldown by Gender";
                $drilldown_title = "Gender";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $gender = $row['gender'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$gender.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "tutor")
            {
                $drilldown_name = "Drilldown by tutor";
                $drilldown_title = "Group tutor";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $tutor = $row['tutor'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$tutor.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "learning_difficulty")
            {
                $drilldown_name = "Drilldown by Learning difficulty";
                $drilldown_title = "Learning difficulty";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $learning_difficulty = $row['learning_difficulty'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$learning_difficulty.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "progress")
            {
                $drilldown_name = "Drilldown by Progress";
                $drilldown_title = "Progress";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $progress = $row['progress'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$progress.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "mainarea")
            {
                $drilldown_name = "Drilldown by Qualification Subject Sector Area";
                $drilldown_title = "Qualification Subject Sector Area";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $mainarea = $row['mainarea'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$mainarea.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "subarea")
            {
                $drilldown_name = "Drilldown by Qualification Subject Sector Subarea";
                $drilldown_title = "Qualification Subject Sector Subarea";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $subarea = $row['internaltitle'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$subarea.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "record_status")
            {
                $drilldown_name = "Drilldown by Record status";
                $drilldown_title = "Record status";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $record_status = $row['record_status'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$record_status.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "verifier")
            {
                $drilldown_name = "Drilldown by Verifier";
                $drilldown_title = "Verifier";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $verifier = $row['verifier'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$verifier.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "work_experience_coordinator")
            {
                $drilldown_name = "Drilldown by Work Experience Coordinator";
                $drilldown_title = "Work Experience Coordinator";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $wbcoordinator = $row['wbcoordinator'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$wbcoordinator.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "actual_work_experience")
            {
                $drilldown_name = "Drilldown by Work Experience Days";
                $drilldown_title = "Work Experience Days";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $actual_work_experience = $row['actual_work_experience'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$actual_work_experience.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "work_experience_band_10")
            {
                $drilldown_name = "Drilldown by Work Experience Visits 10 Days Band";
                $drilldown_title = "Work Experience Visits 10 Days Band";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $band0to10 = $row['band0to10'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$band0to10.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else
            {
                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }


            if($drill_down_by == "")
            {
                $table_head .= '<thead>
                <tr>
                    <th>Year</th>
                    <th>No. of Learners</th>
                </tr>';
            }
            else
            {
                $table_head .= '<thead>
                <tr>
                    <th>Year</th>
                    <th>'.$drilldown_title.'</th>
                    <th>No. of Learners</th>
                </tr>';
            }

            if($rows_exist == "true")
            {
                //echo $data_table;
                echo $table_head;
                echo $table_body;
    			echo '</tbody></table></div>';
    			echo $this->getViewNavigator();
            }
            else
            {
                echo $error_msg;
            }
        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
?>