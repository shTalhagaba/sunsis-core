<?php
class read_training_record implements IAction
{
    public function execute(PDO $link)
    {
	    $pageload_starttime = microtime(true);
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000");

        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $repo = isset($_GET['repo']) ? $_GET['repo'] : '';
        $appointment_tab = isset($_GET['appointment_tab']) ? $_GET['appointment_tab'] : '';
        $otj_tab = isset($_GET['otj_tab']) ? $_GET['otj_tab'] : '';
        $glh_tab = isset($_GET['otj_tab']) ? $_GET['otj_tab'] : '';
	    $tabHci = isset($_GET['tabHci']) ? $_GET['tabHci'] : '';
        $tabRein = isset($_GET['tabRein']) ? $_GET['tabRein'] : '';
	    $tabChoc = isset($_GET['tabChoc']) ? $_GET['tabChoc'] : '';
        $tabCoe = isset($_GET['tabCoe']) ? $_GET['tabCoe'] : '';
        $tabClm = isset($_GET['tabClm']) ? $_GET['tabClm'] : '';
	    $tabSg = isset($_GET['tabSg']) ? $_GET['tabSg'] : '';
	    $tabOnefile = isset($_GET['tabOnefile']) ? $_GET['tabOnefile'] : '';
        $internal_validation_tab = isset($_GET['internal_validation_tab']) ? $_GET['internal_validation_tab'] : '';
        $exams_tab = isset($_GET['exams_tab']) ? $_GET['exams_tab'] : '';
        $als_tab = isset($_GET['als_tab']) ? $_GET['als_tab'] : '';
        $webinars_tab = isset($_GET['webinars_tab']) ? $_GET['webinars_tab'] : '';
        $assessment_plan_log_tab = isset($_GET['assessment_plan_log_tab']) ? $_GET['assessment_plan_log_tab'] : '';
        $claims_tab = isset($_GET['claims_tab']) ? $_GET['claims_tab'] : '';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        $section = isset($_REQUEST['section']) ? basename(trim($_REQUEST['section'])) : '';

        if(!$id){
            throw new Exception("Record id is missing.");
        }

        $pot_vo = TrainingRecord::loadFromDatabase($link, $id); /* @var $pot_vo TrainingRecord */
        if(!$pot_vo){
            throw new Exception("Cannot find a training record with id #".$id);
        }

        if(isset($_REQUEST['export']) && $_REQUEST['export'] == 'learner_progression_pdf')
        {
            if($_REQUEST['tr_id'] == '' || $_REQUEST['framework_id'] == '' || $_REQUEST['achieved'] == '')
                throw new Exception('Compulsory information missing.');
            $this->printLearnerProgressionPDF($link, $_REQUEST['tr_id'], $_REQUEST['framework_id'], $_REQUEST['achieved'], $pot_vo);
        }
        if (preg_match('/[^A-Za-z0-9 \\-_]/', $section)) {
            throw new Exception("Invalid character in section title '".$section."'");
        }
        if(SystemConfig::getEntityValue($link, 'module_scottish_funding'))
            $scottish_funding_tab = isset($_GET['scottish_funding_tab']) ? $_GET['scottish_funding_tab'] : '';
        else
            $scottish_funding_tab = '';

        if(DB_NAME=='am_demo')
            $milestones = DAO::getSingleValue($link,"SELECT milestones FROM frameworks INNER JOIN courses on courses.framework_id = frameworks.id INNER JOIN courses_tr on courses_tr.course_id = courses.id WHERE courses_tr.tr_id='$id'");

        $hours_attended = 0;

        if($id == '' || !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        if($repo==1)
        {
            $progress = '';
            $repo =  ' class="selected" ';
            $appointment_tab = '';
            $internal_validation_tab = '';
            $als_tab = '';
        }
        elseif($appointment_tab==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = ' class="selected" ';
            $internal_validation_tab = '';
            $als_tab = '';
        }
        elseif($exams_tab==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = ' class="selected" ';
            $internal_validation_tab = '';
            $als_tab = '';
        }
        elseif($als_tab==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $als_tab = ' class="selected" ';
            $internal_validation_tab = '';
        }
        elseif($claims_tab==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab  = '';
            $exams_tab = '';
            $claims_tab = ' class="selected" ';
            $internal_validation_tab = '';
            $als_tab = '';
        }
        elseif($internal_validation_tab==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = ' class="selected" ';
            $claims_tab = '';
            $als_tab = '';
        }
        elseif($otj_tab==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = ' class="selected" ';
            $claims_tab = '';
            $als_tab = '';
        }
	elseif($tabHci==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
            $tabHci = ' class="selected" ';
            $als_tab = '';
        }
	elseif($tabRein==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
	    $tabHci = '';	
            $tabRein = ' class="selected" ';
            $als_tab = '';
        }
	elseif($tabChoc==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
            $tabHci = '';
            $tabRein = '';
            $tabChoc = ' class="selected" ';
            $als_tab = '';
        }
	elseif($tabCoe==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
            $tabHci = '';
            $tabRein = '';
            $tabChoc = '';
            $tabCoe = ' class="selected" ';
            $als_tab = '';
        }
	elseif($tabClm==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
            $tabHci = '';
            $tabRein = '';
            $tabChoc = '';
            $tabCoe = '';
            $tabClm = ' class="selected" ';
            $als_tab = '';
        }
	elseif($tabSg==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
            $tabHci = '';
            $tabRein = '';
            $tabSg = ' class="selected" ';
            $als_tab = '';
        }
	elseif($tabOnefile==1)
        {
            $progress = '';
            $repo =  '';
            $appointment_tab = '';
            $exams_tab = '';
            $internal_validation_tab = '';
            $otj_tab = '';
            $claims_tab = '';
            $tabHci = '';
            $tabRein = '';
            $tabSg = '';
            $tabOnefile = ' class="selected" ';
            $als_tab = '';
        }
        else
        {
            $progress = ' class="selected" ';
            $repo = '';
            $appointment_tab = '';
            $internal_validation_tab = '';
            $als_tab = '';
        }

        $year1920 = new DateTime("2019-08-01");
        $lsd = new DateTime($pot_vo->start_date);
        if($lsd >= $year1920)
            $learner_is_1920 = true;
        else
            $learner_is_1920 = false;


        if(isset($pot_vo->crm_contact_id))
            $line_manager = EmployerContacts::loadFromDatabase($link,$pot_vo->crm_contact_id);
        else
            $line_manager = new EmployerContacts();

        if(DB_NAME=='am_sd_demo' && !isset($pot_vo->crm_contact_id))
        {
            $line_manager = EmployerContacts::loadFromDatabase($link,99);
        }

        $diagnostic_assessment = $this->renderLearnerDiagnostics($link, $pot_vo->username);

        // Populate sections dropdown
        if($_SESSION['user']->type == 5)
            $section_options = $this->buildSectionDropDownOptions($_SESSION['user']->username);
        else
            $section_options = $this->buildSectionDropDownOptions($pot_vo->username);
        switch($subaction)
        {
            case 'createsection':
                if($_SESSION['user']->type == 5)
                {
                    $this->createSection($section, $_SESSION['user']->username, $pot_vo->id);
                }
                else
                {
                    $this->createSection($section, $pot_vo->username, $pot_vo->id);
                }
                return;
                break;

            case 'deletesection':
                if($_SESSION['user']->type == 5)
                {
                    $this->deleteSection($section, $_SESSION['user']->username);
                }
                else
                {
                    $this->deleteSection($section, $pot_vo->username);
                }
                return;
                break;

            default:
                break;
        }

        $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = $id");
        $course = Course::loadFromDatabase($link, $course_id);
        $provider_id = $pot_vo->provider_id;
        $provider = Organisation::loadFromDatabase($link, $pot_vo->provider_id);
        if(!$provider){
            $provider = new Organisation(); // blank organisation
        }
        $employer = Organisation::loadFromDatabase($link, $pot_vo->employer_id);
        if(!$employer){
            $employer = new Organisation(); // blank organisation
        }

        $contract = DAO::getSingleValue($link, "select contract_id from tr where id = $id");
        $contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = $contract");

        if($contract_year<2012)
        {
            $planned_hours = "";
            $a15 = '';
            $a31 = '';
            $a40 = '';
            $a35 = '';
            $a34 = '';
            $aims = Array();
            $ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $id order by contract_id desc, submission desc limit 1");
            if($ilr)
            {
                $ilrxml = XML::loadSimpleXML($ilr);
                foreach ($ilrxml->programmeaim as $item)
                {
                    $a15 = $item->A15;
                    $a31 = $item->A31;
                    $a40 = $item->A40;
                    $a35 = DAO::getSingleValue($link, "SELECT CONCAT(Learning_Outcome_Code, '-', Learning_Outcome_Desc) from lis201011.ilr_a35_learning_outcomes where Learning_Outcome_Code='$item->A35';");
                    $a34 = DAO::getSingleValue($link, "SELECT concat(code,'-',description) FROM lookup_pot_status where code='$item->A34'");
                }
                foreach ($ilrxml->main as $item)
                {
                    $aims[] = "'" . substr($item->A09,0,3) .'/'. substr($item->A09,3,4) .'/'. substr($item->A09,7,1) . "'";
                }
                foreach ($ilrxml->subaim as $item)
                {
                    $aims[] = "'" . substr($item->A09,0,3) .'/'. substr($item->A09,3,4) .'/'. substr($item->A09,7,1) . "'";
                }
            }
        }
        else
        {
            $a15 = '';
            $a31 = '';
            $a40 = '';
            $a35 = '';
            $a34 = '';
            $aims = Array();
            $ilr = DAO::getSingleValue($link, "select ilr from ilr inner join contracts on contracts.id = ilr.contract_id where tr_id = $id order by contract_year desc, submission desc limit 1");
            if($ilr)
            {
                $ilrxml = XML::loadSimpleXML($ilr);
                $planned_hours = $ilrxml->PlanLearnHours;
                foreach($ilrxml->LearningDelivery as $delivery)
                {
                    if($delivery->AimType=='1' || $delivery->AimType=='4')
                    {
                        $a15 = $delivery->ProgType;
                        $a31 = $delivery->LearnActEndDate;
                        $a40 = $delivery->AchDate;
                        $outcome = $delivery->Outcome;
                        $compstatus = $delivery->CompStatus;
                        $a35 = DAO::getSingleValue($link, "SELECT CONCAT(Learning_Outcome_Code, '-', Learning_Outcome_Desc) from lis201011.ilr_a35_learning_outcomes where Learning_Outcome_Code='$outcome';");
                        $a34 = DAO::getSingleValue($link, "SELECT concat(code,'-',description) FROM lookup_pot_status where code='$compstatus'");
                        break;
                    }
                }
                foreach($ilrxml->LearningDelivery as $delivery)
                {
                    $aims[] = "'" . $delivery->LearnAimRef . "'";
                }
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_training_record&id=" . $id . "&contract=" . $contract, "View Training Record");

        $que = "select max(meeting_date) from assessor_review where tr_id='$id' order by tr_id";
        $review_date = trim((string) DAO::getSingleValue($link, $que));

        $numberOfDestinationRecords = DAO::getSingleValue($link, "SELECT COUNT(*) FROM destinations WHERE tr_id = " . $id);

        if($review_date){
            $que = "select comments from assessor_review where tr_id='$id' and meeting_date = '$review_date' order by tr_id;";
            $last_review_status = trim((string) DAO::getSingleValue($link, $que));
        } else {
            $last_review_status = null;
        }

        $workplace = SystemConfig::getEntityValue($link, 'workplace');

        $que = "SELECT courses.framework_id FROM courses INNER JOIN courses_tr ON courses_tr.course_id = courses.id WHERE tr_id='$id'";
        $framework_id = trim((string) DAO::getSingleValue($link, $que));

        $is_scottish_funded_learner = 0;
        if(SystemConfig::getEntityValue($link, 'module_scottish_funding'))
            $is_scottish_funded_learner = DAO::getSingleValue($link,"SELECT COUNT(*) FROM frameworks WHERE id = '$framework_id' AND funding_stream = '2'");


        $course_start_date = $pot_vo->start_date;
        $course_end_date = $pot_vo->target_date;

        $que = "select DATEDIFF(target_date,start_date) from tr where id='$id'";
        $no_of_days_in_course = trim((string) DAO::getSingleValue($link, $que));

        $que = "select DATEDIFF(NOW(), start_date) from tr where id='$id'";
        $days_passed_since_course_started = trim((string) DAO::getSingleValue($link, $que));

        $que = "select courses.title from courses LEFT JOIN courses_tr on courses_tr.course_id = courses.id where tr_id='$id'";
        $course_title = trim((string) DAO::getSingleValue($link, $que));

        $fsd = new Date($course_start_date);
        $fed = new Date($course_end_date);


        $coursestamp = $fed->getDate() - $fsd->getDate();
        $currentstamp = time() - $fsd->getDate();

        $days_between_course_start_date_and_end_date = (($coursestamp/60)/60)/24;
        $days_between_course_start_date_and_today = (($currentstamp/60)/60)/24;

        //$months_in_course = round($days_between_course_start_date_and_end_date / 30,0);
        $que = "SELECT TIMESTAMPDIFF(MONTH, start_date, DATE_ADD(target_date, INTERVAL 1 DAY)) FROM tr WHERE id = $id;";
        $months_in_course = trim((string) DAO::getSingleValue($link, $que));

        if($months_in_course==0)
            $months_in_course = 1;

        $months_passed_float = (round($days_between_course_start_date_and_today / 30,2));

        if($months_passed_float>$months_in_course)
            $months_passed_float = $months_in_course;

        $months_passed = floor($days_between_course_start_date_and_today / 30);

        $months_passed = ($months_passed<0)?0:$months_passed;

        if($months_passed>$months_in_course)
            $months_passed = $months_in_course;

        if($days_between_course_start_date_and_end_date>0)
            $percentcoursepassed = $days_between_course_start_date_and_today / $days_between_course_start_date_and_end_date * 100;

        $que = "select sum(IF(aptitude=1,100,IF(unitsUnderAssessment>100,100,unitsUnderAssessment))/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and aptitude!=1";
        $achieved = trim((string) DAO::getSingleValue($link, $que));
    	if(DB_NAME == "am_ela")
        {
            $achieved = $pot_vo->l36;
        }


        $isSafeToDelete = $pot_vo->isSafeToDelete($link);

        $acl = ACL::loadFromDatabase($link, 'trainingrecord', $id); /* @var $acl ACL */

        $que = "SELECT description from dropdown0708 where code='L12' and value = '$pot_vo->ethnicity'";
	$que = "SELECT CONCAT(Ethnicity, ' ', Ethnicity_Desc) FROM lis201415.ilr_ethnicity WHERE Ethnicity = '{$pot_vo->ethnicity}'";
        $ethnicity = trim((string) DAO::getSingleValue($link, $que));

        $co = Contract::loadFromDatabase($link,$contract);
        if(!$co){
            throw new Exception("Cannot find this training record's associated contract. Training record id='"
                .$pot_vo->id."', contract id='".$contract."'.");
        }

        if(DB_NAME=="am_demo" || DB_NAME=="am_reed" || DB_NAME=="am_reed_demo") 
        {
            $contract1314 = "";
            $contract1415 = "";
            $submissions = DAO::getResultset($link, "SELECT concat(submission,'*',contract_id,'*',tr_id, '*', contracts.contract_year, '*', L03, '*', contracts.funding_body), concat(contracts.title, ' ', submission) FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id = '$id' AND (CONCAT(submission,contract_year) IN (SELECT CONCAT(submission,contract_year) FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() and contract_type='$co->funding_body' ORDER BY last_submission_date));");
            $contract1314 = DAO::getSingleValue($link, "SELECT contracts.id FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id AND contract_year = '2013' where tr_id = '$id' AND (CONCAT(submission,contract_year) IN (SELECT CONCAT(submission,contract_year) FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() and contract_type='$co->funding_body' ORDER BY last_submission_date));");
            $contract1415 = DAO::getSingleValue($link, "SELECT contracts.id FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id AND contract_year = '2014' where tr_id = '$id' AND (CONCAT(submission,contract_year) IN (SELECT CONCAT(submission,contract_year) FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() and contract_type='$co->funding_body' ORDER BY last_submission_date));");
        }

        if(empty($submissions))
        {
            $submissions = DAO::getResultset($link, "SELECT concat(submission,'*',contract_id,'*',tr_id, '*', contracts.contract_year, '*', L03, '*', contracts.funding_body), concat(contracts.title, ' ', submission) FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id = '$id' order by contract_year DESC, submission DESC limit 0,1;");
        }

        $aims = implode(",",$aims);

        $user_employer_id = $_SESSION['user']->employer_id;

        if($aims=="'ZESF0001'" || $aims=="")
        {
            if($_SESSION['user']->isAdmin())
                $courses = DAO::getResultset($link, "SELECT distinct concat(courses.id,'*','$id'), concat(legal_name,'::',title) from courses LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.active = 1 order by legal_name, title");
            elseif(DB_NAME=='am_reed' || DB_NAME=='am_reed_demo')
                $courses = DAO::getResultset($link, "SELECT distinct concat(courses.id,'*','$id'), concat(legal_name,'::',title) from courses LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.active = 1 and courses.organisations_id = '$user_employer_id' order by legal_name, title");
            else
                $courses = DAO::getResultset($link, "SELECT distinct concat(courses.id,'*','$id'), concat(title,'::',legal_name) from courses LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.active = 1 and courses.organisations_id = '$user_employer_id'");
        }
        else
        {
            if(DB_NAME=="am_raytheon" || DB_NAME=="am_superdrug" || DB_NAME=="am_crackerjack" || DB_NAME=="am_city_skills")
                $courses = DAO::getResultset($link, "SELECT distinct concat(courses.id,'*','$id'), Concat(title,'::',legal_name) from courses LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id LEFT JOIN organisations on organisations.id = courses.organisations_id WHERE courses.active = 1");
            else
                $courses = DAO::getResultset($link, "SELECT distinct concat(courses.id,'*','$id'), Concat(title,'::',legal_name) from courses LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id LEFT JOIN organisations on organisations.id = courses.organisations_id WHERE replace(course_qualifications_dates.qualification_id,'/','') IN ($aims) and courses.active = 1 and courses.organisations_id = '$user_employer_id'");
        }


        $qualifications = DAO::getResultset($link, "select concat(id,'*',framework_id,'*',tr_id,'*',internaltitle), concat(id, ' ',internaltitle) from student_qualifications where tr_id=$id");

    	$qualificationdatabase = DAO::getResultset($link, "SELECT CONCAT(id,'*',internaltitle), concat(id, ' ',internaltitle), LEFT(title, 1) FROM qualifications ORDER BY title");

        $groups = DAO::getResultset($link, "select id, concat(id, ' ',title) from groups");

        $provider_legal_name = '';

        $provider_location = '';

        $que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";
        $framework_percentage = trim((string) DAO::getSingleValue($link, $que));

        $que = "SELECT title FROM frameworks INNER JOIN courses_tr ON courses_tr.`framework_id` = frameworks.id AND courses_tr.`tr_id` = '$id';";
        $framework_title = trim((string) DAO::getSingleValue($link, $que));

        if(DB_NAME=='am_demo')
        {
            $que = "SELECT milestone_payment FROM frameworks INNER JOIN courses_tr ON courses_tr.`framework_id` = frameworks.id AND courses_tr.`tr_id` = '$id';";
            $otj = trim((string) DAO::getSingleValue($link, $que));
        }

        $que = "SELECT framework_code FROM frameworks INNER JOIN courses_tr ON courses_tr.`framework_id` = frameworks.id AND courses_tr.`tr_id` = '$id';";
        $framework_code = trim((string) DAO::getSingleValue($link, $que));

        $age_at_start = DAO::getSingleValue($link, "SELECT ((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) FROM tr where id = '$id'");

        $showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson

        // Calculate target against every training record
        $tr_id = $id;
        $study_start_month = Date::to($pot_vo->start_date, 'n');
        $study_start_year = Date::to($pot_vo->start_date, 'Y');
        $current_year = (int)date("Y");

        if($pot_vo->status_code==1)
            $current_month = (int)date("m");
        else
            $current_month = substr($pot_vo->closure_date,5,2);

        $current_month_since_study_start_date = ($current_year - $study_start_year) * 12;

        if($current_month > $study_start_month)
            $current_month_since_study_start_date += ($current_month - $study_start_month + 1);
        else
            $current_month_since_study_start_date += ($current_month - $study_start_month + 1);

        if($framework_title==NULL || $framework_title=='')
            $current_month_since_study_start_date = NULL;

        $month = "month_" . ($current_month_since_study_start_date-1);

        if($pot_vo->status_code!='1')
        {
            $target = "100";
        }
        else
        {
            $target = 50;
        }

        $end_date = Date::toShort($pot_vo->target_date);

        $view = ViewFrameworksTrainingRecord::getInstance($link, $id, $framework_id);
        $view->refresh($link, $_REQUEST);

        //$view2 = ViewQualificationsTrainingRecord::getInstance($link, $id);
        //$view2->refresh($link, $_REQUEST);

        $que = "select description from lookup_pot_status where code='$pot_vo->status_code'";
        $record_status = trim((string) DAO::getSingleValue($link, $que));


        // If it is a grouped learner fetch tutor, assessor, verifier and wb coordinator information from group otherwise training record
        if($pot_vo->isGrouped($link))
        {
            if($pot_vo->assessor=='')
                $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.assessor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$id;");
            else
                $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = '$pot_vo->assessor'");

            $group_tutor = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.tutor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$id;");
            $verifier = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.verifier = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$id;");
        }
        else
        {
            $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = '$pot_vo->assessor'");
            $group_tutor = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = '$pot_vo->tutor'");
            $verifier = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = '$pot_vo->verifier'");
        }

        // Create Address presentation helper
        $home_bs7666 = new Address();
        $home_bs7666->set($pot_vo, 'home_');

        $work_bs7666 = new Address();
        $work_bs7666->set($pot_vo, 'work_');

        $provider_bs7666 = new Address();
        $provider_bs7666->set($pot_vo, 'provider_');

        $page_record = 'Training Record';

        $stu_vo = $pot_vo;


        if($workplace)
        {

            $que = "select count(*) from workplace_visits where tr_id='$id' and start_date is not null order by tr_id";
            $planned_work_experience = trim((string) DAO::getSingleValue($link, $que));

            $work_experience_milestones = array(0,0,2,3,5,7,8,10,13,17,20,23,27,30,32,33,35,37,38,40,42,43,45,47,48,50);

            $que = "select timestampdiff(MONTH, tr.start_date, CURDATE()) from tr where id = '$id'";
            $work_experience_month = DAO::getSingleValue($link, $que);

            if($work_experience_month<0)
                $work_experience_month = 0;
            elseif($work_experience_month>24)
                $work_experience_month=24;

            $target_work_experience = $work_experience_milestones[$work_experience_month];


            $que = "select count(*) from workplace_visits where tr_id='$id' and end_date is not null order by tr_id";
            $workplace_visits = trim((string) DAO::getSingleValue($link, $que));
            $workplace_visits = ($workplace_visits==null)?0:$workplace_visits;

            $dealersView = ViewTrainingRecordDealers::getInstance($tr_id);
        }

        $viewcrm = ViewLearnerCrmNotes::getInstance($link, $id);
        $viewcrm->refresh($link, $_REQUEST);

        $anotes = ViewRegisterNotes::getInstance($link, $id);
        $anotes->refresh($link, $_REQUEST);

        $events = ViewEvents::getInstance($link, $id);
        $events->refresh($link, $_REQUEST);

        $total_events = DAO::getSingleValue($link, "select count(*) from events_template");
        if($total_events==0)
            $total_events = 1;
        $events_achieved = DAO::getSingleValue($link, "select count(*) from student_events where tr_id = '$id'");


        $dropdown_iv = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where users.type=4";
        $iv_select = DAO::getResultset($link, $dropdown_iv, DAO::FETCH_NUM, "read training record iv dropdown");

        $auto_ids = DAO::getSingleValue($link, "select GROUP_CONCAT(student_qualifications.auto_id) from student_qualifications where tr_id = '$tr_id'");

        if(DB_NAME=='am_lead')
        {
            if($_SESSION['user']->type==8)
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where (users.type=3  OR users.type=2) and users.employer_id = {$_SESSION['user']->employer_id} ORDER BY CONCAT(users.firstnames, ' ' , users.surname);";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
            else
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where users.type=3 or users.type=2 ORDER BY CONCAT(users.firstnames, ' ' , users.surname)";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
        }
        elseif(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
        {
            if($_SESSION['user']->type==8 && !$_SESSION['user']->isAdmin())
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where web_access= 1 and type!=5 and users.employer_id = {$_SESSION['user']->employer_id} ORDER BY CONCAT(users.firstnames, ' ' , users.surname);";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
            else
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where web_access=1 and type!=5 ORDER BY CONCAT(users.firstnames, ' ' , users.surname)";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
        }
        elseif(DB_NAME=='am_city_skills')
        {
            if($_SESSION['user']->type==8 && !$_SESSION['user']->isAdmin())
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where (users.type=3) and users.employer_id = {$_SESSION['user']->employer_id} ORDER BY CONCAT(users.firstnames, ' ' , users.surname);";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
            else
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where users.type=3 ORDER BY CONCAT(users.firstnames, ' ' , users.surname)";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
        }
	elseif(DB_NAME=='am_ela')
        {
            if($_SESSION['user']->type==8 && !$_SESSION['user']->isAdmin())
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where (users.type=3) and users.employer_id = {$_SESSION['user']->employer_id} ORDER BY CONCAT(users.firstnames, ' ' , users.surname);";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
            else
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where users.type=3 ORDER BY CONCAT(users.firstnames, ' ' , users.surname)";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
        }
        else
        {
            if($_SESSION['user']->type==8 && !$_SESSION['user']->isAdmin())
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where web_access= 1 and (users.type=3) and users.employer_id = {$_SESSION['user']->employer_id} ORDER BY CONCAT(users.firstnames, ' ' , users.surname);";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
            else
            {
                $dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where web_access=1 and users.type=3 ORDER BY CONCAT(users.firstnames, ' ' , users.surname)";
                $assessor_select = DAO::getResultset($link, $dropdown_assessor);
            }
        }


        $dropdown_qualification = "SELECT id, CONCAT(internaltitle), null FROM student_qualifications where tr_id = '$tr_id'";
        $qualification_select = DAO::getResultset($link, $dropdown_qualification);

        $contract_title = $co->title;

        $framework = Framework::loadFromDatabase($link, $framework_id);

        $first_weeks = $framework->first_review ?? '';
        $meeting_date = Array();
        $subsequent_weeks = $framework->review_frequency ?? '';
        if($subsequent_weeks=='' or $subsequent_weeks==0)
            $subsequent_weeks = 4;
        if($first_weeks=='' or $first_weeks==0)
            $first_weeks = 4;

        $show_file_repository = true;
        if($section != '')
            $html2 = $show_file_repository ? $this->getFileDownloads($pot_vo, $section):"";
        else
            $html2 = $show_file_repository ? $this->getFileDownloads($pot_vo):"";

            // Learner photo
        $user = User::loadFromDatabase($link, $pot_vo->username);
        $photopath = $user ? $user->getPhotoPath():'';
        if($photopath){
            $photopath = "do.php?_action=display_image&username=".rawurlencode($pot_vo->username);
        } else {
            $photopath = "/images/no_photo.png";
        }

		if($pot_vo->ob_alert == '1' && SystemConfig::getEntityValue($link, "module_onboarding"))
		{
			$pot_vo->synchOnboardingChanges($link);
		}

		$_type = '';
	    if(DB_NAME == "am_superdrug" || DB_NAME == "am_sd_demo")
	    {
		    $retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr_id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		    if($retail_qual > 0)
		    {
			    $_type = 'retail';
		    }
			else
			{
				$cs_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr_id}' AND REPLACE(id, '/', '') = '" . Workbook::CS_QAN . "'");
				if($cs_qual > 0)
					$_type = 'cs';
			}
	    }

		$op_trackers_nav = "";
        if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
        {
	        $sql = <<<SQL
SELECT
  DISTINCT op_trackers.id AS tracker_id, tr_id, op_trackers.`title`
FROM
  student_frameworks
  INNER JOIN op_tracker_frameworks
    ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers
    ON op_tracker_frameworks.`tracker_id` = op_trackers.id
WHERE tr_id = '$tr_id'
SQL;
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			foreach($result AS $row)
			{
				$op_trackers_nav .= '<a href="do.php?_action=view_edit_op_learner&tr_id=' . $row['tr_id'] . '&tracker_id=' . $row['tracker_id'] . '">' . $row['title'] . '</a><br>';
			}
        }

		if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
	    {
		    TrainingRecord::updateProgressStatistics($link, $pot_vo->id);
	    }

        if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
        {
            $induction_fields_sql = <<<SQL
SELECT DISTINCT 
    inductees.paid_hours,
    inductees.salary,
    CASE induction.comp_issue
        WHEN 'N' THEN 'No'
        WHEN 'Y' THEN 'Yes'
        ELSE ''
    END AS red_flag_learner,
    induction.arm,
    induction.brm,
    CASE induction.fs_exempt
        WHEN 'Y' THEN 'Yes'
        WHEN 'N' THEN 'No'
        ELSE ''
    END AS fs_exempt,
    CASE inductees.ldd
        WHEN 'MLD' THEN 'Moderate Learning Difficulty'
        WHEN 'SLD' THEN 'Severe Learning Difficulty'
        WHEN 'DXA' THEN 'Dyslexia'
        WHEN 'DLA' THEN 'Dyscalculia'
        WHEN 'ASD' THEN 'Autism Spectrum Disorder'
        WHEN 'OSLD' THEN 'Other Specific Learning Difficulty'
        WHEN 'OTH' THEN 'Other (Additional Data Required)'
        WHEN 'PNS' THEN 'Prefer Not To Say'
        WHEN 'NP' THEN 'Not provided'
        WHEN 'N' THEN 'None'    
    END AS ldd,
    inductees.ldd_comments,
    induction.math_cert,
    induction.eng_cert,
    CASE inductees.arm_chance_to_progress
        WHEN '1' THEN 'Potential to Progress'
        WHEN '2' THEN 'Will never progress'
        WHEN '3' THEN 'Level 4 N/A'
        ELSE ''
    END AS arm_chance_to_progress,
    induction.iag_numeracy,
    induction.iag_literacy,
    CASE induction.math_cert
        WHEN '1' THEN 'Received'
        WHEN '2' THEN 'Not Received'
        WHEN '3' THEN 'Before FS Process'
        WHEN '4' THEN 'Quals confirmed on PLR'
        WHEN '5' THEN 'Certificate Requested'
        WHEN '6' THEN 'Certificates Re-print requested'
        WHEN '7' THEN 'No Qual - SMT Approved'
    END AS math_cert,
    CASE induction.eng_cert
        WHEN '1' THEN 'Received'
        WHEN '2' THEN 'Not Received'
        WHEN '3' THEN 'Before FS Process'
        WHEN '4' THEN 'Quals confirmed on PLR'
        WHEN '5' THEN 'Certificate Requested'
        WHEN '6' THEN 'Certificates Re-print requested'
        WHEN '7' THEN 'No Qual - SMT Approved'
    END AS eng_cert,
    CASE induction.wfd_assessment
        WHEN 'Y' THEN 'Yes'
        WHEN 'N' THEN 'No'
    END AS wfd_assessment,
    CASE induction.maths_gcse_elig_met
        WHEN 'Y' THEN 'Yes'
        WHEN 'N' THEN 'No'
    END AS maths_gcse_elig_met,
    induction.eng_gcse_grade,
    induction.maths_gcse_grade,
    induction.sci_gcse_grade,
    induction.it_gcse_grade,
    inductees.id AS inductee_id,
    induction.id AS induction_id,
    induction.app_opp_concern,
    induction.das_account_contact,
    induction.das_account_telephone,
    induction.das_account_email,
    inductees.general_comments,
    inductees.preferred_name,
    induction.comp_issue_notes,
    induction_programme.data_pathway,
    induction_programme.it_pathway,
    induction.placement_id
FROM 
   inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id 
WHERE
   inductees.sunesis_username = '{$pot_vo->username}' AND induction_programme.programme_id = '{$course->id}'
SQL;
            $induction_fields = DAO::getObject($link, $induction_fields_sql);

            $tr_coe = DAO::getObject($link, "SELECT * FROM tr_coe WHERE tr_id = '{$pot_vo->id}'");
            if(!isset($tr_coe->tr_id))
            {
                $tr_coe = new stdClass();
                $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM tr_coe");
                foreach($records AS $key => $value)
                    $tr_coe->$value = null;
                $tr_coe->tr_id = $pot_vo->id;
            }
            $das_months_list = [
                ["Jan", "Jan"],
                ["Feb", "Feb"],
                ["Mar", "Mar"],
                ["Apr", "Apr"],
                ["May", "May"],
                ["Jun", "Jun"],
                ["Jul", "Jul"],
                ["Aug", "Aug"],
                ["Sep", "Sep"],
                ["Oct", "Oct"],
                ["Nov", "Nov"],
                ["Dec", "Dec"],
            ];
        }

	if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
	{
		//$pot_vo->updateOperationsStatus($link);
	}

	    if(in_array(DB_NAME, ["am_ela", "am_demo", "am_crackerjack"]))
        {
            $ob_tr = DAO::getObject($link, "SELECT * FROM ob_tr WHERE ob_tr.sunesis_tr_id = '{$pot_vo->id}'");
        }

	if(SystemConfig::getEntityValue($link, 'onefile.integration'))
		{
			$onefile_classrooms_list = [];
			$onefile_classrooms_list_from_db = DAO::getResultset($link, "SELECT `value` FROM onefile WHERE `key` LIKE 'classrooms_%'", DAO::FETCH_ASSOC);
            foreach($onefile_classrooms_list_from_db AS $onefile_classrooms_list_from_db_record)
            {
                $_classrooms = json_decode($onefile_classrooms_list_from_db_record['value']);
                usort($_classrooms, function($a, $b) {return strcmp($a->Name, $b->Name);});
                foreach($_classrooms AS $_cls)
                {
                    $onefile_classrooms_list[] = [$_cls->ID, $_cls->Name];
                }
            }

            $emails_list = [];
            if($pot_vo->work_email != '')
                $emails_list[] = [$pot_vo->work_email, $pot_vo->work_email];
            if($pot_vo->home_email != '')
                $emails_list[] = [$pot_vo->home_email, $pot_vo->home_email];

            
		}

	$blueFlag = '';
        $yellowFlag = '';
        $greyFlag = '';
        $redFlag = '';
        if(DB_NAME == "am_baltic")
        {
            if(isset($induction_fields->app_opp_concern) && $induction_fields->app_opp_concern != '')
            {
                $blueFlag = '<img src="images/icons-flags/flag-blue.png" />';
            }
            elseif(isset($induction_fields->comp_issue_notes) && $induction_fields->comp_issue_notes != '')
            {
                $blueFlag = '<img src="images/icons-flags/flag-blue.png" />';
            }
            $trOperationsAdditionalSupport = DAO::getSingleValue($link, "SELECT additional_support FROM tr_operations WHERE tr_id = '{$pot_vo->id}'");
            if($pot_vo->ad_lldd != '' || $trOperationsAdditionalSupport != '' || ( isset($induction_fields->ldd_comments) &&  $induction_fields->ldd_comments != ''))
            {
                $yellowFlag = '<img src="images/icons-flags/flag-yellow.png" />';
            }
            $trOperationsGeneralComments = DAO::getSingleValue($link, "SELECT general_comments FROM tr_operations WHERE tr_id = '{$pot_vo->id}'");
            if( ( isset($induction_fields->general_comments) && $induction_fields->general_comments != '') || $trOperationsGeneralComments != '' || (isset($induction_fields->preferred_name) && $induction_fields->preferred_name != ''))
            {
                $greyFlag = '<img src="images/icons-flags/flag-grey.png" />';
            }
            $epaReady = DAO::getSingleValue($link, "SELECT task_status FROM op_epa WHERE op_epa.tr_id = '{$pot_vo->id}' AND op_epa.task = '1' ORDER BY id DESC LIMIT 1");
            $caseloadCount = DAO::getSingleValue($link, "SELECT COUNT(*) FROM caseload_management WHERE caseload_management.tr_id = '{$pot_vo->id}' AND caseload_management.closed_date IS NULL");
            if($epaReady == '1' && $caseloadCount > 0)
            {
                $redFlag = '<img src="images/icons-flags/flag-red.png" />';
            }
        }

        // Presentation
        include('tpl_read_training_record.php');
    }


    private function format_size($size)
    {
        $sizes = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        if($size == 0)
        {
            return('n/a');
        }
        else
        {
            $i = 0;
            $s = $size;
            while($size > 1024){
                $size = $size/1024;
                $i++;
            }
            return sprintf("%.1f" . $sizes[$i], $size);
            //return sprintf("%.1f",($size/pow(1024, ($i = floor(log($size, 1024))))) . $sizes[$i]);
        }
    }


    private function renderTrainingRecords(PDO $link, TrainingRecord $stu_vo)
    {

        $training_records_sql = <<<HEREDOC
SELECT
	tr.id, tr.programme, tr.cohort, tr.start_date, tr.target_date as target_date,
	tr.closure_date, courses.title as course_title, organisations.legal_name,
	tr.status_code,courses.id as course_id,
	courses_tr.qualification_id as qualification_id,

	tr.scheduled_lessons,
	tr.registered_lessons,
	tr.attendances,
	tr.lates,
	tr.very_lates,
	tr.authorised_absences,
	tr.unexplained_absences,
	tr.unauthorised_absences,
	tr.dismissals_uniform,
	tr.dismissals_discipline,
	(tr.attendances+
	tr.lates+
	tr.very_lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`
FROM
	courses_tr
	INNER JOIN tr ON tr.id = courses_tr.tr_id
	INNER JOIN courses ON courses.id = courses_tr.course_id
	INNER JOIN organisations ON organisations.id=tr.provider_id
	LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
WHERE
	courses_tr.tr_id={$stu_vo->id};
HEREDOC;


        $st = $link->query($training_records_sql);
        if(true)
        {
            echo '<table id="trainingRecordsTable" class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">';
            echo <<<HEREDOC
<tr>
<th>&nbsp;</th>
<th>Provider</th>
<th>Course</th>
<th>QAN</th>
<th>Start</th>
<th>Completed</th>
HEREDOC;
            AttendanceHelper::echoHeaderCells();
            echo '</tr>';

            $total_units = 0;
            $total_not_started = 0;
            $total_behind = 0;
            $total_on_track = 0;
            $total_under_assessment = 0;
            $total_completed = 0;

            while($row = $st->fetch())
            {

                $icon_opacity = $row['status_code'] <= 3 ? 'opacity:1.0':'opacity:0.3';
                $text_style = $row['status_code'] <= 3 ? '':'text-decoration:line-through;color:silver';
                $image = '/images/folder-'
                    .($stu_vo->gender == 1?'blue':'red')
                    .($row['status_code'] == 2?'-happy':'')
                    .($row['status_code'] == 3?'-sad':'')
                    .'.png';

                echo "<td align=\"left\"><img style=\"$text_style;$icon_opacity\" src=\"$image\" title=\"#{$row['id']}\" /></td>";

                $que = "select legal_name from organisations where id=(select organisations_id from courses where id={$row['course_id']})";
                $legal_name = DAO::getSingleValue($link, $que);

                echo "<td style=\"$text_style\">" . HTML::cell($legal_name) . '</td>';
                echo "<td style=\"font-size:80%;$text_style\">" . HTML::cell($row['course_title']) . '</td>';
                echo "<td style=\"font-size:80%;$text_style\">" . HTML::cell(($row['qualification_id']=='0')?'':$row['qualification_id']) . '</td>';
                echo "<td style=\"$text_style\">" . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
                echo "<td style=\"$text_style\">" . HTML::cell(Date::toShort($row['closure_date'])) . '</td>';


                AttendanceHelper::echoDataCells($row);

                echo '</tr>';
            }

            // Now add summary row
            echo '<tr><td colspan="6" align="right" style="font-weight:bold;background-color:#EEEEEE;">Summary for all courses: </td>';
            AttendanceHelper::echoDataCells($stu_vo);
            echo '</table>';
        }
        else
        {
            echo '<p class="sectionDescription">This learner has not yet been enroled on a course. To enrol this learner onto a course, press the "enrol" button at the top of this page or return to this page to enrol at a later time.</em></p>';
        }

    }

    private function renderAttendanceModuleAttendance(PDO $link, TrainingRecord $stu_vo)
    {

        $training_records_sql = <<<HEREDOC
SELECT tr.id AS tr_id,
	tr.`l03` AS learner_ref_number,
	attendance_modules.`qualification_title`,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS target_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS closure_date,
	attendance_modules.module_title,
	(SELECT organisations.legal_name FROM organisations WHERE organisations.id = attendance_modules.provider_id) AS legal_name,
	tr.`status_code`,
	attendance_modules.id AS module_id,
	attendance_modules.qualification_id,
	tr.`outcome`,
	COUNT(DISTINCT lessons.id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,NULL)) AS 'total',
	COUNT(IF(entry=1,1,NULL)) AS 'attendances',
	(COUNT(IF(entry=1,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'attendances_percentage',
	COUNT(IF(entry=2,1,NULL)) AS 'lates',
	(COUNT(IF(entry=2,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'lates_percentage',
	COUNT(IF(entry=9,1,NULL)) AS 'very_lates',
	(COUNT(IF(entry=9,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'very_lates_percentage',
	COUNT(IF(entry=3,1,NULL)) AS 'authorised_absences',
	(COUNT(IF(entry=3,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'authorised_percentage',
	COUNT(IF(entry=4,1,NULL)) AS 'unexplained_absences',
	(COUNT(IF(entry=4,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'unexplained_percentage',
	COUNT(IF(entry=5,1,NULL)) AS 'unauthorised_absences',
	(COUNT(IF(entry=5,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'unauthorised_percentage',
	COUNT(IF(entry=6,1,NULL)) AS 'dismissals_uniform',
	(COUNT(IF(entry=6,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'dismissals_uniform_percentage',
	COUNT(IF(entry=7,1,NULL)) AS 'dismissals_discipline',
	(COUNT(IF(entry=7,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'dismissals_discipline_percentage',
	COUNT(IF(entry=8,1,NULL)) AS 'not_applicables',
	(COUNT(IF(entry=8,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'not_applicable_percentage',
	SUM(DISTINCT attendance_modules.hours) AS planned_hours,
	tr.id AS tr_id,
	'' AS actual_hours
FROM
	group_members INNER JOIN lessons INNER JOIN tr INNER JOIN attendance_module_groups INNER JOIN attendance_modules
	ON group_members.groups_id = lessons.groups_id
	AND tr.id = group_members.tr_id
	AND group_members.groups_id = attendance_module_groups.id
	AND attendance_module_groups.`module_id` = attendance_modules.id
	LEFT JOIN register_entries ON lessons.id = register_entries.`lessons_id` AND tr.id = register_entries.`pot_id`
WHERE tr.id = {$stu_vo->id}
GROUP BY tr_id, attendance_modules.id
HEREDOC;


        $st = $link->query($training_records_sql);
        if(true)
        {
            echo '<table id="trainingRecordsTable" class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">';
            echo <<<HEREDOC
<tr>
<th>&nbsp;</th>
<th>Provider</th>
<th>Attendance Module</th>
<th>QAN</th>
<th>Start</th>
<th>Completed</th>
HEREDOC;
            AttendanceHelper::echoHeaderCells();
            echo '</tr>';

            while($row = $st->fetch())
            {
                $icon_opacity = $row['status_code'] <= 3 ? 'opacity:1.0':'opacity:0.3';
                $text_style = $row['status_code'] <= 3 ? '':'text-decoration:line-through;color:silver';
                $image = '/images/folder-'
                    .($stu_vo->gender == 1?'blue':'red')
                    .($row['status_code'] == 2?'-happy':'')
                    .($row['status_code'] == 3?'-sad':'')
                    .'.png';

                echo "<td align=\"left\"><img style=\"$text_style;$icon_opacity\" src=\"$image\" title=\"#{$row['tr_id']}\" /></td>";

                echo "<td style=\"$text_style\">" . HTML::cell($row['legal_name']) . '</td>';
                echo "<td style=\"font-size:80%;$text_style\">" . HTML::cell($row['module_title']) . '</td>';
                echo "<td style=\"font-size:80%;$text_style\">" . HTML::cell(($row['qualification_id']=='0')?'':$row['qualification_id']) . '</td>';
                echo "<td style=\"$text_style\">" . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
                echo "<td style=\"$text_style\">" . HTML::cell(Date::toShort($row['closure_date'])) . '</td>';


                AttendanceHelper::echoDataCells($row);
                echo '</tr>';
            }

            // Now add summary row
            echo '<tr><td colspan="6" align="right" style="font-weight:bold;background-color:#EEEEEE;">Summary for all modules: </td>';
            AttendanceHelper::echoDataCells($stu_vo);


            echo '</table>';
        }
        else
        {
            echo '<p class="sectionDescription">This learner has not yet been made part of any attendance module.</em></p>';
        }

    }

    private function getFileDownloads(TrainingRecord $pot_vo, $section = null)
    {
        $learner_dir = Repository::getRoot().'/'.trim((string) $pot_vo->username);
        if(!is_null($section))
            $learner_dir = Repository::getRoot().'/'.trim((string) $pot_vo->username).'/'.$section;
        $files = Repository::readDirectory($learner_dir);
        if(count($files) == 0){
            return "";
        }

        $html = "";

        $html .= <<<HEREDOC
<div class="Directory">
<table cellspacing="0" style="table-layout:fixed; width:570">
<col width="310"/><col width="70"/><col width="170"/>
<tr>
	<th>Filename</th><th>Size</th><th>Upload Date</th><th>&nbsp;</th>
</tr>
HEREDOC;

        /* @var $f RepositoryFile */
        foreach($files as $f)
        {
            if($f->isDir()){
                continue;
            }
            $html .= "<tr>\r\n";
            $html .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($f->getSize()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';
            if($_SESSION['user']->isAdmin() || (DB_NAME == "am_superdrug" && $_SESSION['user']->type == User::TYPE_ASSESSOR) || (DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ['abielok', 'sblackburn1']) ) )
            {
                $html .= '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
            }
            else
            {
                $html .= '<td align="right" width="20">&nbsp;</td>';
            }
            $html .= "\r\n</tr>\r\n";
        }

        $html .= "</table>\r\n";
        $html .= "</div>\r\n";

        return $html;
    }

    private function getOnboardingFiles(PDO $link, TrainingRecord $pot_vo)
    {
        if(!in_array(DB_NAME, ["am_crackerjack", "am_demo", "am_ela"]))
            return;

        $ob_ids = DAO::getObject($link, "SELECT ob_tr.id, ob_tr.ob_learner_id FROM ob_tr WHERE ob_tr.sunesis_tr_id = '{$pot_vo->id}'");
        if(!isset($ob_ids->id))
            return;

        $html = "";

        $trs1 = '';
	if(is_file(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/OTJT Sheet.pdf"))
        {
            $_otj_file = new RepositoryFile(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/OTJT Sheet.pdf");
            $trs1 .= '<tr>';
            $trs1 .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$_otj_file->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$_otj_file->getName()).'</td>';
            $trs1 .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($_otj_file->getSize()).'</td>';
            $trs1 .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $_otj_file->getModifiedTime()).'</td>';
            $trs1 .= '</tr>';
        }
        foreach (["schedule1", "skills_analysis", "onboarding"] as $section) 
        {
            $html .= '<span class="fieldLabel">' . ucwords(str_replace("_", " ", $section)) . ':</span>';
            $s_files = Repository::readDirectory(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/{$section}");
            foreach ($s_files as $s_file) {
                if ($s_file->getExtension() != 'pdf')
                    continue;

                $trs1 .= '<tr>';
                $trs1 .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$s_file->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$s_file->getName()).'</td>';
                $trs1 .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($s_file->getSize()).'</td>';
                $trs1 .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $s_file->getModifiedTime()).'</td>';
                $trs1 .= '</tr>';
            }       
        }
        echo <<<HTML
<p><br></p><h4>Onboarding Module Files</h4>
<div class="Directory">
    <table cellspacing="0" style="table-layout:fixed; width:570">
        <col width="310"/><col width="70"/><col width="170"/>
        <tr>
            <th>Filename</th><th>Size</th><th>Upload Date</th>
        </tr>
        $trs1
    </table>    
</div>
HTML;

        
    }

    private function getFileDownloadsForClaims(PDO $link, TrainingRecord $pot_vo, $section = null)
    {
        $learner_dir = Repository::getRoot().'/'.trim((string) $pot_vo->username);
        if(!is_null($section))
            $learner_dir = Repository::getRoot().'/'.trim((string) $pot_vo->username).'/'.$section;
        $files = Repository::readDirectory($learner_dir);
        if(count($files) == 0){
            return "";
        }

        $html = "";

        $html .= <<<HEREDOC
<div class="Directory">
<table cellspacing="0" style="table-layout:fixed; width:700">
<col width="310"/><col width="70"/><col width="170"/><col width="100"/><col width="100"/>
<tr>
	<th>Filename</th><th>Size</th><th>Upload Date</th><th>Claim Status</th><th>Claim Point</th><th>&nbsp;</th>
</tr>
HEREDOC;

        /* @var $f RepositoryFile */
        foreach($files as $f)
        {
            if($f->isDir()){
                continue;
            }
            $html .= "<tr>\r\n";
            $html .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($f->getSize()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';

            $file_claim_status = DAO::getSingleValue($link, "SELECT status FROM claim_files INNER JOIN tr_files ON claim_files.tr_files_id = tr_files.id INNER JOIN claims ON claim_files.claim_id = claims.id WHERE claims.tr_id = {$pot_vo->id} AND tr_files.file_name = '" . $f->getName() . "'");
            $file_claim_point = DAO::getSingleValue($link, "SELECT lookup_claim_points_by_contract_type.description FROM claim_files INNER JOIN tr_files ON claim_files.tr_files_id = tr_files.id INNER JOIN claims ON claim_files.claim_id = claims.id LEFT JOIN lookup_claim_points_by_contract_type ON claims.claim_point = lookup_claim_points_by_contract_type.id WHERE claims.tr_id = {$pot_vo->id} AND tr_files.file_name = '" . $f->getName() . "'");


            if($file_claim_status == 'Rejected')
                $html .= "<td align='center' title='Rejected' > <img width='25' height='25'  src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
            elseif($file_claim_status == 'Accepted')
                $html .= "<td align='center' title='Accepted' > <img  width='25' height='25'  src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
            elseif($file_claim_status == 'Invalid_L03')
                $html .= "<td align='center' title='Learner Reference Number (L03) is not valid' > <img width='25' height='25'  src=\"/images/notice_icon_red.gif\" border=\"0\" alt=\"\" /></td>";
            elseif($file_claim_status == 'Withdrawn')
                $html .= "<td align='center' title='Withdrawn' > <img width='25' height='25'  src=\"/images/withdrawn.jpg\" border=\"0\" alt=\"\" /></td>";
            elseif($file_claim_status == 'Unclaimable')
                $html .= "<td align='center' title='Unclaimable' > <img src=\"/images/unclaimable.png\" border=\"0\" alt=\"\" /></td>";
            else
                "<td align='center' ></td>";

            if($file_claim_point != '')
                $html .= "<td align='center' > " . $file_claim_point . "</td>";
            else
                "<td align='center' ></td>";

            if($_SESSION['user']->isAdmin())
            {
                $html .= '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
            }
            else
            {
                $html .= '<td align="right" width="20">&nbsp;</td>';
            }
            $html .= "\r\n</tr>\r\n";
        }

        $html .= "</table>\r\n";
        $html .= "</div>\r\n";

        return $html;
    }




    private function renderRepositorySpaceRemaining()
    {
        $max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
	if(Repository::getRemainingSpace() < $max_file_upload){
            $max_file_upload = Repository::getRemainingSpace();
        }
	$max_file_upload = 6291456;
        echo Repository::formatFileSize(Repository::getUsedSpace())." used of "
            .Repository::formatFileSize(Repository::getTotalSpace())." (max file size "
            .Repository::formatFileSize($max_file_upload).")";
    }

    private function getMaximumFileSizeToUploadForTrainingRecord()
    {
        $max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
        if(Repository::getRemainingSpace() < $max_file_upload){
            $max_file_upload = Repository::getRemainingSpace();
        }
	$max_file_upload = 6291456;
        return $max_file_upload;
    }

    private function buildSectionDropDownOptions($username = null)
    {
        $sections = array(array("")); // default section
        if(!is_null($username))
        {
            $learner_dir = Repository::getRoot().'/'.trim((string) $username);
            $files = Repository::readDirectory($learner_dir);
            foreach ($files as $f) {
                if ($f->isDir()) {
                    $sections[] = array($f->getName()); // additional section
                }
            }
        }

//		var_dump($sections);
        return $sections;
    }

    private function isSectionEmpty($section,$username)
    {
        $upload_root = Repository::getRoot();
        if($section)
        {
            $path = $upload_root.'/'.$username.'/'.basename($section);
        }
        else
        {
            $path = $upload_root.'/'.$username;
        }
//var_dump($path);
        $files = Repository::readDirectory($path);
        return count($files) == 0;
    }

    private function createSection($section, $username = null, $tr_id = null)
    {
        if (!$section) {
            return;
        }
        $upload_root = Repository::getRoot();
        if(!is_null($username))
        {
            $path = $upload_root.'/'.trim($username).'/'.basename($section);
        }
        else
        {
            $path = $upload_root.'/section_'.basename($section);
        }

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function deleteSection($section, $username = null)
    {
        if (!$section) {
            return;
        }

        $upload_root = Repository::getRoot();
        if(!is_null($username))
        {
            $path = $upload_root.'/'.trim($username).'/'.basename($section);
        }
        else
        {
            return;
        }
        if (!is_dir($path)) {
            return;
        }

        $files = Repository::readDirectory($path);
        if (count($files) > 0) {
            return;
        }

        rmdir($path);
    }

    private function generateFunding(PDO $link, $contracts, $period, $course, $assessor, $employer, $submission, $tutor, $tr_id)
    {
		if(DB_NAME == "am_sd_demo") return '';
        if(SystemConfig::getEntityValue($link, 'module_scottish_funding'))
        {
            $framework_id = DAO::getSingleValue($link, "select framework_id from courses_tr where tr_id = '$tr_id'");
            $is_scottish_funded_learner = DAO::getSingleValue($link,"SELECT COUNT(*) FROM frameworks WHERE id = " . $framework_id . " AND funding_stream = 2");
            if($is_scottish_funded_learner)
                return '<p><br>Learner is Scottish Funded, please see "Scottish Funding" tab for funding details</p>';
        }
        // dependencies
        require_once('./lib/funding/FundingCore.php');
        require_once('./lib/funding/PeriodLookup.php');
        require_once('./lib/funding/LearnerFunding.php');
        require_once('./lib/funding/FundingPeriod.php');
        require_once('./lib/funding/FundingPrediction.php');
        require_once('./lib/funding/FundingPredictionPeriod.php');

        if(!isset($_REQUEST['output']))
        {
            $_REQUEST['output'] = 'HTML';
        }
        $predictions = new FundingPredictionPeriod($link, $contracts, 25, $course, $assessor, $employer, $submission, $tutor, $tr_id);
        $data = $predictions->get_learnerdata();

        //$dataHTML = '<h3>Funding for W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</h3>' . $predictions->to($_REQUEST['output']);

        $monthsArray = array();

		//months array to pick up the columns from input CSV file
		$monthsArray[] = "August";
		$monthsArray[] = "September";
		$monthsArray[] = "October";
		$monthsArray[] = "November";
		$monthsArray[] = "December";
		$monthsArray[] = "January";
		$monthsArray[] = "February";
		$monthsArray[] = "March";
		$monthsArray[] = "April";
		$monthsArray[] = "May";
		$monthsArray[] = "June";
		$monthsArray[] = "July";


		$dataHTML = "";
		$dataHTML .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		$dataHTML .= '<thead><tr><th>Aim</th><th>Start Date</th><th>Planned End Date</th><th>Act End Date</th><th>Dis Uplift</th><th>Area Cost</th>';

		foreach($monthsArray as $month)
		{
			$dataHTML .= '<th>' . $month . '</th>';
		}

		$dataHTML .= "</tr></thead>";
		$dataHTML .= "<tbody>";

		for($i = 0; $i < count($data); $i++)
		{
			$ii = 1;
			$dataHTML .= "<tr>";
			$dataHTML .= '<td>' . $data[$i]['qualification_title'] . "</td>";
			$dataHTML .= '<td>' . $data[$i]['learner_start_date'] . "</td>";
			$dataHTML .= '<td>' . $data[$i]['learner_target_end_date'] . "</td>";
			$dataHTML .= '<td>' . $data[$i]['learner_end_date'] . "</td>";
			$dataHTML .= '<td>' . $data[$i]['disadvantage_uplift'] . "</td>";
			$dataHTML .= '<td>' . $data[$i]['area_cost'] . "</td>";

			foreach($monthsArray as $month)
			{
				$dataHTML .= '<td>';
				$dataHTML .= '<table cellpadding="6">';
				$dataHTML .= '<tr><th>Funding Field</th><th>Amount (&pound;)</th></tr>';
				$dataHTML .= '<tr><td>OPP</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_OPP'])) . '</td></tr>';
				$dataHTML .= '<tr><td>Ach</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_ach'])) . '</td></tr>';
				$dataHTML .= '<tr><td>BAL</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_bal'])) . '</td></tr>';
				$dataHTML .= '<tr><td>Total</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_total'])) . '</td></tr>';
				$dataHTML .= '<tr><td>E&M OPP</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_EM_OPP'])) . '</td></tr>';
				$dataHTML .= '<tr><td>E&M Bal</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_EM_Bal'])) . '</td></tr>';
				$dataHTML .= '<tr><td>1618 Prov Inc</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_1618_Pro_Inc'])) . '</td></tr>';
				$dataHTML .= '<tr><td>1618 Emp Inc</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_1618_Emp_Inc'])) . '</td></tr>';
				$dataHTML .= '<tr><td>1618 FU OPP</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_1618_FW_Uplift_OPP'])) . '</td></tr>';
				$dataHTML .= '<tr><td>1618 FU Bal</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_1618_FW_Uplift_Bal'])) . '</td></tr>';
				$dataHTML .= '<tr><td>1618 FU Comp</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_1618_FW_Uplift_Comp'])) . '</td></tr>';
				$dataHTML .= '<tr><td>FM36 Disadv.</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_FM36_Disadv'])) . '</td></tr>';
				$dataHTML .= '<tr><td>ALS</td><td>' . sprintf('%0.2f',floatval($data[$i]['P' . $ii . '_ALS'])) . '</td></tr>';
				$dataHTML .= '</table>';
				$dataHTML .= '</td>';

				$ii++;
			}
			$dataHTML .= "</tr>";
		}

		$dataHTML .= "</tbody>";
		$dataHTML .= "</table>";

        return $dataHTML;
    }


    private function getLearnerAppointments(PDO $link, $tr_id)
    {
        return ViewLearnerAppointments::getInstance($link, $tr_id);
    }

    private function getLearnerReviews(PDO $link, $tr_id)
    {
        return ViewLearnerReviews::getInstance($link, $tr_id);
    }

    private function getLearnerInternalValidation(PDO $link, $tr_id)
    {
        return ViewLearnerInternalValidation::getInstance($link, $tr_id);
    }

    private function getLearnerClaims(PDO $link, $tr_id)
    {
        return ViewLearnerClaims::getInstance($link, $tr_id);
    }

    private function getScottishPayments(PDO $link, $tr_id)
    {
        return ViewScottishPayments::getInstance($link, $tr_id);
    }

    private function getLearnerExamResults(PDO $link, $tr_id)
    {
        return ViewLearnerExamResults::getInstance($link, $tr_id);
    }

    private function getALS(PDO $link, $tr_id)
    {
        return ViewALS::getInstance($link, $tr_id);
    }

    private function getLearnerFSProgress(PDO $link, $tr_id, $last)
    {
        return ViewLearnerFSProgress::getInstance($link, $tr_id, $last);
    }

    private function getLearnerEmployerContact(PDO $link, $tr_id)
    {
        return ViewLearnerEmployerContact::getInstance($link, $tr_id);
    }

    private function getLearnerAdditionalSupport(PDO $link, $tr_id)
    {
        return ViewLearnerAdditionalSupport::getInstance($link, $tr_id);
    }

    private function getEmployerReference(PDO $link, $tr_id)
    {
        return ViewEmployerReference::getInstance($link, $tr_id);
    }

    private function getOTJ(PDO $link, $tr_id)
    {
        return ViewOTJ::getInstance($link, $tr_id);
    }

    private function getGLH(PDO $link, $tr_id)
    {
        return ViewGLH::getInstance($link, $tr_id);
    }

    private function getAssessmentPlanLog(PDO $link, $tr_id)
    {
        return ViewAssessmentPlanLog::getInstance($link, $tr_id);
    }

    private function getAssessmentPlanLog2(PDO $link, $tr_id)
    {
        return ViewAssessmentPlanLog2::getInstance($link, $tr_id);
    }

    private function getEvidenceMatrix(PDO $link, $tr_id)
    {
        return ViewEvidenceMatrix::getInstance($link, $tr_id);
    }

    private function getExamResults(PDO $link, $tr_id, TrainingRecord $training_record)
    {
        $framework_id = DAO::getSingleValue($link, "SELECT courses_tr.`framework_id` FROM courses_tr WHERE courses_tr.`tr_id` = '$tr_id';");
        if($framework_id!='')
        {
            $framework = Framework::loadFromDatabase($link, $framework_id);
            $qualifications_ddl = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, CONCAT(REPLACE(id, '/', ''), ' - ', internaltitle) FROM student_qualifications WHERE tr_id = '$tr_id'");

            $outputHTML = "";
            $outputHTML .= '<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">';
            $outputHTML .= '<tr>';
            $outputHTML .= '<td class="fieldLabel">Framework No.</td><td class="fieldValue">' . $framework->framework_code . '</td>';
            $outputHTML .= '</tr>';
            $outputHTML .= '<tr>';
            $outputHTML .= '<td class="fieldLabel">Framework Title</td><td class="fieldValue">' . $framework->title . '</td>';
            $outputHTML .= '</tr>';
            $outputHTML .= '<tr>';
            $outputHTML .= '<td class="fieldLabel">Training Start Date</td><td class="fieldValue">' . Date::toLong($training_record->start_date) . '</td>';
            $outputHTML .= '</tr>';
            $outputHTML .= '<tr>';
            $outputHTML .= '<td class="fieldLabel">Training Planned End Date</td><td class="fieldValue">' . Date::toLong($training_record->target_date) . '</td>';
            $outputHTML .= '</tr>';
            $outputHTML .= '<tr>';
            $outputHTML .= '<td class="fieldLabel">Training Actual End Date</td><td class="fieldValue">' . Date::toLong($training_record->closure_date) . '</td>';
            $outputHTML .= '</tr>';
            $outputHTML .= '</table><p><br /></p>';
            return $outputHTML;
        }
    }


    private function getUnits(PDO $link, $qualification_id, $tr_id)
    {
        $qualification_id = str_replace('/', '', $qualification_id);

        $sql = <<<HEREDOC
SELECT
	 student_qualifications.id,
	 student_qualifications.evidences
FROM
	 student_qualifications
WHERE
	 student_qualifications.tr_id = '$tr_id' AND REPLACE(student_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

        $student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        $units_ddl = array();
        foreach ($student_qualifications AS $qualification)
        {
            $evidence = XML::loadSimpleXML($qualification['evidences']);

            $units = $evidence->xpath('//unit');
            $q_units = array();
            foreach ($units AS $unit)
            {
                $temp = array();
                $temp = (array)$unit->attributes();
                $temp = $temp['@attributes'];
                $temp['reference'] = str_replace('/','', $temp['reference']);
                if($temp['chosen'] == 'true')
//					$q_units[$temp['reference']] = $temp['reference'] . ' - ' . $temp['title'];
                    $q_units[] = $temp;
            }
            $units_ddl[] = $q_units;
        }
        $final_ddl = array();
        foreach($units_ddl AS $unit_entry)
        {
            for($i=0;$i<count($unit_entry);$i++)
                $final_ddl[] = array($unit_entry[$i]['reference'], $unit_entry[$i]['title']);
        }
        return $final_ddl;
    }

    private function printLearnerProgressionPDF(PDO $link, $tr_id, $framework_id, $achieved, TrainingRecord $training_record)
    {
        $framework_title = DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = " . $framework_id);
        $training_start_date = Date::to($training_record->start_date, Date::SHORT);
        $training_planned_end_date = Date::to($training_record->target_date, Date::SHORT);

        $induction_booklet = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_events WHERE tr_id = " . $training_record->id . " AND event_id = (SELECT id FROM events_template WHERE TRIM(title) = 'Induction Booklet' AND provider_id = " . $training_record->provider_id . " ); ");
        $edims_booklet = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_events WHERE tr_id = " . $training_record->id . " AND event_id = (SELECT id FROM events_template WHERE TRIM(title) = 'EDIMs Booklet' AND provider_id = " . $training_record->provider_id . " ); ");
        $h_and_s_booklet = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_events WHERE tr_id = " . $training_record->id . " AND event_id = (SELECT id FROM events_template WHERE TRIM(title) = 'H&S Booklet' AND provider_id = " . $training_record->provider_id . " ); ");

        $induction_booklet = $induction_booklet == 1?'Yes':'No';
        $edims_booklet = $edims_booklet == 1?'Yes':'No';
        $h_and_s_booklet = $h_and_s_booklet == 1?'Yes':'No';

        $achieved = ($achieved=='')?0:sprintf("%.1f",$achieved);

        $progress_bar_color = "green";
        if($achieved == '0.0' || $achieved == '0')
            $progress_bar_color = "white";

        $achieved_progress_bar = <<<HEREDOC
			<table><tr><td bgcolor="#f5f9ee" class="fieldLabel">% Achieved: </td><td class="fieldValue">$achieved %</td></tr></table>
			<div style="width: 50%; border: 1px solid black; border-radius: 5px; position: relative; padding: 3px;">
				<div style="height: 20px; border-radius: 15px; width: $achieved %; background-color: $progress_bar_color;"></div>
			</div>
HEREDOC;

        include_once("./MPDF57/mpdf.php");

        $mpdf=new mPDF('','Legal-L','10');

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();


        $client_logo = SystemConfig::getEntityValue($link, "logo");
        $client_logo = $client_logo ? $client_logo : 'perspective.png';
        $client_logo_path = "./images/logos/" . $client_logo;

        $html = <<<HTML
<div style="float: right; width: 30%;"><img src="$client_logo_path" alt=""></div>
<div>
	<table border="1" style="width: 100%;" cellspacing="0" cellpadding="5">
		<col width="10%"/>
		<tr><td bgcolor="#f5f9ee" class="fieldLabel">Learner Name:</td><td class="fieldValue" colspan="3">$training_record->firstnames $training_record->surname</td></tr>
		<tr><td bgcolor="#f5f9ee" class="fieldLabel">Framework Name:</td><td class="fieldValue" colspan="3" style="font-size: 10px;">$framework_title</td></tr>
		<tr><td bgcolor="#f5f9ee" class="fieldLabel">Start Date:</td><td class="fieldValue">$training_start_date</td><td bgcolor="#f5f9ee" class="fieldLabel" width="20%">Planned End Date:</td><td class="fieldValue">$training_planned_end_date</td></tr>
	</table>

	<table border="1" width="100%" cellspacing="0" cellpadding="6">
		<tr><th bgcolor="#f5f9ee">Induction Booklet</th><th bgcolor="#f5f9ee">EDIMs Booklet</th><th bgcolor="#f5f9ee">H&S Booklet</th></tr>
		<tbody><tr><td class="fieldValue" align="center">$induction_booklet</td><td class="fieldValue" align="center">$edims_booklet</td><td class="fieldValue" align="center">$h_and_s_booklet</td></tr></tbody>
	</table>
</div>

<div>
	<h4>Framework / Qualification Progress</h4>
	$achieved_progress_bar
</div>
<br>
<div>
	<table border="1" width="100%" cellspacing="0" cellpadding="5">

HTML;

        $s_quals = array();

        $qualifications = DAO::getResultset($link, "SELECT * FROM student_qualifications WHERE tr_id = " . $tr_id, DAO::FETCH_ASSOC);
        foreach ($qualifications AS $qualification)
        {
            $stdClass = new stdClass();

            $stdClass->qualification_id = $qualification['id'];
            $stdClass->qualification_title = $qualification['internaltitle'];

            $i = 1;
            $evidence = XML::loadSimpleXML($qualification['evidences']);
            $units = $evidence->xpath('//unit[@chosen=\'true\']');

            $s_qual_units = array();

            foreach ($units AS $unit)
            {
                //if($i > 25)
                //	break;
                $temp = (array)$unit->attributes();
                $temp = $temp['@attributes'];
                $temp['reference'] = str_replace('/','', $temp['reference']);
                if($temp['chosen'] == 'true')
                {
                    $s_qual_units[$temp['owner_reference']] = round($temp['percentage']);
                }
                $i++;
            }

            $stdClass->progress = $s_qual_units;
            $s_quals[] = $stdClass;
        }

        foreach($s_quals AS $s_qual)
        {
            $total_number_of_chose_units_in_this_qual = count($s_qual->progress);
            $quotient = floor($total_number_of_chose_units_in_this_qual / 25);
            $excess = $total_number_of_chose_units_in_this_qual % 25;

            $offset = 0;
            $offsett = 0;

            $i = 1;
            $ii = 1;

            while($quotient > 0)
            {
                //echo $quotient . '= quotient<br>';
                $html .= '<tr><th style="width: 5%; " bgcolor="#f5f9ee">Qual Ref.</th><th style="width: 25%; " bgcolor="#f5f9ee">Qualification Title</th>';
                foreach($s_qual->progress AS $key => $value)
                {
                    //echo $i . '=i<br>';
                    //echo $offset . '=offset<br>';
                    if($i > $offset + 25)
                    {
                        $offset = $i;
                        break;
                    }

                    if($i <= $offset)
                    {
                        $i++;
                        continue;
                    }

                    $html .= '<th bgcolor="#f5f9ee" style="width: 4%; ">' . $key . '</th>';
                    $i++;
                }
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td class="fieldValue">' . $s_qual->qualification_id . '</td>';
                $html .= '<td class="fieldValue">' . $s_qual->qualification_title . '</td>';
                foreach($s_qual->progress AS $key => $value)
                {
                    if($ii > $offsett + 25)
                    {
                        $offsett = $ii;
                        break;
                    }

                    if($ii <= $offsett)
                    {
                        $ii++;
                        continue;
                    }
                    if($value == '100.00')
                        $html .= '<td class="fieldValue" bgcolor="lightgreen">' . $value . '%</td>';
                    else
                        $html .= '<td class="fieldValue" >' . $value . '%</td>';
                    $ii++;
                }
                $html .= '</tr>';
                $quotient--;
            }

            if($excess > 0)
            {
                $html .= '<tr><th style="width: 5%; " bgcolor="#f5f9ee">Qual Ref.</th><th style="width: 25%; " bgcolor="#f5f9ee">Qualification Title</th>';

                $again_i = 0;
                $again_ii = 0;
                foreach($s_qual->progress AS $key => $value)
                {
                    $again_i++;
                    if($again_i < $offset)
                        continue;
                    $html .= '<th bgcolor="#f5f9ee" style="width: 5%; ">' . $key . '</th>';
                }

                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td class="fieldValue">' . $s_qual->qualification_id . '</td>';
                $html .= '<td class="fieldValue">' . $s_qual->qualification_title . '</td>';

                foreach($s_qual->progress AS $key => $value)
                {
                    $again_ii++;
                    if($again_ii < $offset)
                        continue;
                    if($value == '100.00')
                        $html .= '<td class="fieldValue" bgcolor="lightgreen">' . $value . '%</td>';
                    else
                        $html .= '<td class="fieldValue" >' . $value . '%</td>';
                }
                $html .= '</tr>';
            }


        }




        $html .= "</table>";
        $html .= "</div>";

        $print_off_date = '{DATE j/m/Y H:i}';
        $print_off_date = date('d/m/Y H:i');
        $sunesis_stamp = md5('ghost'.date('d/m/Y H:i'));
        $footer = <<<FOOTER
			<table><tr><td style="font-size:70%;">$print_off_date</td></tr><tr><td style="font-size:70%;">$sunesis_stamp</td></tr></table>
FOOTER;

        $html .= $footer;
        echo $html;
        //exit;
        $file_name = 'Progress Report - ' . $training_record->firstnames . ' ' . $training_record->surname . '.pdf';

        $html = ob_get_contents();
        ob_end_clean();

        $mpdf->WriteHTML($html);


        $content = $mpdf->Output('', 'S');

        $content = chunk_split(base64_encode($content));
        $mailto = 'inaam.cs@gmail.com'; //Mailto here
        $from_name = 'Sunesis'; //Name of sender mail
        $from_mail = 'inaam.azmat@perspective-uk.com'; //Mailfrom here
        $subject = 'Progress Report - ' . $training_record->firstnames . ' ' . $training_record->surname;
        $message = 'Please find an attached progress report';
        $filename = $file_name; //Your Filename with local date and time

        //Headers of PDF and e-mail
        $boundary = "XYZ-" . date("dmYis") . "-ZYX";

        $header = "--$boundary\r\n";
        $header .= "Content-Transfer-Encoding: 8bits\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n\r\n"; // or utf-8
        $header .= "$message\r\n";
        $header .= "--$boundary\r\n";
        $header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
        $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n";
        $header .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $header .= "$content\r\n";
        $header .= "--$boundary--\r\n";

        $header2 = "MIME-Version: 1.0\r\n";
        $header2 .= "From: ".$from_name." \r\n";
        $header2 .= "Return-Path: $from_mail\r\n";
        $header2 .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $header2 .= "$boundary\r\n";

        //mail($mailto,$subject,$header,$header2, "-r".$from_mail);

        $mpdf->Output($filename ,'I');

        exit;
    }

    private function renderLearnerDiagnostics(PDO $link, $tr_username)
    {
        $html = "";
        $user_record = User::loadFromDatabase($link, $tr_username);

        if(DB_NAME=="am_city_skills" and !$user_record)
        {
            $tr_id = DAO::getSingleValue($link, "select id from tr where username = '$tr_username'");
            $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
            $user_record = new User();
            $user_record->populate($tr);
            $user_record->save($link, true);
        }

        if($user_record->numeracy>0)
            $numeracy = DAO::getSingleValue($link, 'SELECT description FROM lookup_pre_assessment WHERE id = ' . $user_record->numeracy);
        else
            $numeracy = '';
        $numeracy_test = $user_record->numeracy_diagnostic == 1?'Yes':'No';

        if($user_record->literacy>0)
            $literacy = DAO::getSingleValue($link, 'SELECT description FROM lookup_pre_assessment WHERE id = ' . $user_record->literacy);
        else
            $literacy = '';
        $literacy_test = $user_record->literacy_diagnostic == 1?'Yes':'No';

        if($user_record->esol>0)
            $esol = DAO::getSingleValue($link, 'SELECT description FROM lookup_pre_assessment WHERE id = ' . $user_record->esol);
        else
            $esol = '';
        $esol_test = $user_record->esol_diagnostic == 1?'Yes':'No';

        $diagnostic_assessment = $user_record->bennett_test;

        $html = <<<HTML
<h3>Diagnostics</h3><span style="color:gray;margin-left:10px">This information is fetched from user level</span>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190"
	<tr>
		<td class="fieldLabel">Diagnostic Assessment:</td>
		<td class="fieldValue">$diagnostic_assessment</td>
	</tr>
	<tr>
		<td class="fieldLabel">Numeracy Test:</td>
		<td class="fieldValue">$numeracy</td>
		<td class="fieldLabel">Diagnostic Assessment?</td>
		<td class="fieldValue">$numeracy_test</td>
	</tr>
	<tr>
		<td class="fieldLabel">Literacy Test:</td>
		<td class="fieldValue">$literacy</td>
		<td class="fieldLabel">Diagnostic Assessment?</td>
		<td class="fieldValue">$literacy_test</td>
	</tr>
	<tr>
		<td class="fieldLabel">ESOL Test:</td>
		<td class="fieldValue">$esol</td>
		<td class="fieldLabel">Diagnostic Assessment?</td>
		<td class="fieldValue">$esol_test</td>
	</tr>
</table>
HTML;

        return $html;
    }

	public function renderOperationsSessionRegistersNotes(PDO $link, $tr_id)
	{
		$sql = <<<SQL
SELECT DISTINCT
  sessions.`title`,
  sessions.`unit_ref`,
  CONCAT(sessions.`start_date`, ' ', sessions.`start_time`) AS `start`,
  CONCAT(sessions.`end_date`, ' ', sessions.`end_time`) AS `end`,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.`personnel`) AS trainer,
  session_entries.`entry_comments`
FROM
  session_attendance
  LEFT JOIN session_entries
    ON session_attendance.`session_entry_id` = session_entries.`entry_id`
  LEFT JOIN sessions
    ON session_entries.`entry_session_id` = sessions.`id`
WHERE session_entries.`entry_tr_id` = '$tr_id' ;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<table class="resultset" cellpadding="6" cellspacing="0">';
		echo '<tr><th>Event Title</th><th>Unit Reference</th><th>Start</th><th>End</th><th>Trainer</th><th>Comments</th></tr>';
		foreach($records AS $row)
			echo '<tr><td>' . $row['title'] . '</td><td>' . $row['unit_ref'] . '</td><td>' . Date::to($row['start'], Date::DATETIME) . '</td><td>' . Date::to($row['end'], Date::DATETIME) . '</td><td>' . $row['trainer'] . '</td><td>' . $row['entry_comments'] . '</td></tr>';
		echo '</table>';

	}

    public function renderAssessorAutoEmails(PDO $link, $tr_id)
    {
        $sql = <<<SQL
SELECT * FROM forms_audit
INNER JOIN assessor_review ON assessor_review.`id` = forms_audit.`form_id` AND assessor_review.`tr_id` = '$tr_id'
WHERE description IN ("Review Form 24HR Emailed to Learner","Review Form 48HR Emailed to Learner","Review Form 72HR Emailed to Learner","Review Form 72HR Emailed to Employer",
"Review Form 120HR Emailed to Employer","Review Form 168HR Emailed to Employer","Review Form Emailed to Learner","Review Form Emailed to Employer","Review Form 72HR Bsuiness Letter","Welcome Review Form Emailed to Learner","Welcome Review Form Emailed to Employer");
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<table class="resultset" cellpadding="6" cellspacing="0">';
        echo '<tr><th>Type</th><th>Review Date</th><th>Description</th><th>Date/ time</th><th>User</th><th>Contents</th></tr>';
        foreach($records AS $row)
        {
            $user = ($row['user']!='')?$row['user']:"Auto";
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = {$row['form_id']}"));
            echo '<tr><td>Assessor Review</td><td>' . Date::toShort($actual_date) . '</td><td>' . $row['description'] . '</td><td>' . Date::to($row['date'], Date::DATETIME) . '</td><td>' . $user . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&tr_id=$tr_id&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
        }


        echo '</table><br><br>';

        $sql = <<<SQL
SELECT * FROM forms_audit
INNER JOIN assessment_plan_log_submissions ON assessment_plan_log_submissions.id = forms_audit.`form_id`
INNER JOIN assessment_plan_log ON assessment_plan_log.`id` = assessment_plan_log_submissions.`assessment_plan_id` AND assessment_plan_log.`tr_id` = '$tr_id'
WHERE description IN ("Assessment Plan Prompt 1 sent","Assessment Plan Prompt 2 sent","Assessment Plan Chaser 1 sent","Assessment Plan Chaser 2 sent");
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<h3>Assessment Plans</h3>';
        echo '<table class="resultset" cellpadding="6" cellspacing="0">';
        echo '<tr><th>Type</th><th>Set Date</th><th>Description</th><th>Date/ time</th><th>User</th><th>Contents</th></tr>';
        foreach($records AS $row)
        {
            $user = ($row['user']!='')?$row['user']:"Auto";
            echo '<tr><td>Assessment Plan</td><td>' . Date::toShort($row['set_date']) . '</td><td>' . $row['description'] . '</td><td>' . Date::to($row['date'], Date::DATETIME) . '</td><td>' . $user . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&tr_id=$tr_id&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
        }
        echo '</table>';


        $sql = <<<SQL
SELECT * FROM forms_audit
INNER JOIN project_submissions ON project_submissions.id = forms_audit.form_id
INNER JOIN tr_projects ON tr_projects.id = project_submissions.project_id AND tr_projects.`tr_id` = '$tr_id'
WHERE description IN ("Project Prompt 1 sent","Project Prompt 2 sent","Project Chaser 1 sent","Project Chaser 2 sent");
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<h3>Projects</h3>';
        echo '<table class="resultset" cellpadding="6" cellspacing="0">';
        echo '<tr><th>Type</th><th>Set Date</th><th>Description</th><th>Date/ time</th><th>User</th><th>Contents</th></tr>';
        foreach($records AS $row)
        {
            $user = ($row['user']!='')?$row['user']:"Auto";
            echo '<tr><td>Assessment Plan</td><td>' . Date::toShort($row['set_date']) . '</td><td>' . $row['description'] . '</td><td>' . Date::to($row['date'], Date::DATETIME) . '</td><td>' . $user . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&tr_id=$tr_id&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
        }
        echo '</table>';


    }


	public function renderWorkbooks(PDO $link, $tr_id)
	{
		$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr_id}' AND REPLACE(id, '/', '') = '60313432'");
		$cs_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr_id}' AND REPLACE(id, '/', '') = 'Z0001875'");

		if($retail_qual == 0 && $cs_qual == 0)
		{
			echo 'Learner is not studying workbooks qualification';
			return;
		}

		if($retail_qual > 0)
		{
			$workbooks = array(
				'WBHSAndSecurity' => 'H&S and Security'
				,'WBCustomer' => 'Customer'
				,'WBCommunication' => 'Communication'
				,'WBTechnical' => 'Technical'
				,'WBPersonalTeamPerformance' => 'Personal Team Performance'
				,'WBRetailProductAndService' => 'Product and Service'
				,'WBStock' => 'Stock'
				,'WBFinancial' => 'Financial'
				,'WBEnvironment' => 'Environment'
				,'WBBusinessAndBrandReputation' => 'Business and Brand Reputation'
				,'WBLegalAndGovernance' => 'Legal and Governance'
				,'WBMarketing' => 'Marketing'
				,'WBSalesPromotionMarchandising' => 'Sales and Promotion & Merchandising'
			);
		}
		else
		{
			$workbooks = array(
				'WBDevelopingSelf' => '02 Developing Self'
			,'WBCustomerExperience' => '03 Customer Experience'
			,'WBKnowingYourCustomers' => '04 Knowing Your Customers'
			,'WBRoleResponsibility' => '05 Role, Responsibility and Personal Organisation'
			,'WBTeamWorking' => '06 Team Working'
			,'WBCommunication' => '07 Communication'
			,'WBSystemsAndResources' => '08 Systems and Resources'
			,'WBUnderstandingTheOrganisation' => '09 Understanding the Organisation'
			,'WBProductAndService' => '10 Product and Service'
			,'WBMeetingRegulationsAndLegislation' => '11 Meeting Regulations and Legislation'
			);
		}

		echo '<table class="resultset" cellpadding="6" cellspacing="0">';
		echo '<tr><th>Workbook</th><th>Log</th></tr>';
		foreach($workbooks AS $key => $value)
		{
			echo '<tr>';
			$learner_workbook = DAO::getObject($link, "SELECT id, tr_id, wb_status, extractvalue(wb_content, '/Workbook/@title') AS t FROM workbooks WHERE tr_id = '{$tr_id}' AND wb_title = '{$key}'");
			if(!isset($learner_workbook->id))
			{
				echo '<td><p><b>Title: &nbsp; </b>' . $value . '</p><p><b>Current Status: &nbsp; </b>' . Workbook::getWBStatusTitle(0) . '</p></td><td>No records found</td>';
			}
			else
			{
				echo '<td><p><b>Title: &nbsp; </b>' . $value . '</p><p><b>Current Status: &nbsp; </b>' . Workbook::getWBStatusTitle($learner_workbook->wb_status) . '</p><p><span class="button" onclick="window.location.href=\'do.php?_action=wb_'.$learner_workbook->t.'&id='.$learner_workbook->id.'&tr_id='.$learner_workbook->tr_id.'\'">View Learner Workbook</button></p></td>';
				$_id = $learner_workbook->id;
				$sql = <<<SQL
SELECT
  workbooks_log.created, workbooks_log.by_whom, workbooks_log.user_type, workbooks_log.wb_status AS log_status
FROM
  workbooks_log
WHERE
    workbooks_log.wb_id = '$_id'
ORDER BY created
;

SQL;
				$logs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
				echo '<td>';
				if(count($logs) == 0)
					echo 'No records found';
				else
				{
					echo '<table cellpadding="6">';
					foreach($logs AS $l)
					{
						echo '<tr>';
						echo '<td>' . Date::to($l['created'], Date::DATETIME) . '</td><td>' . Workbook::getWBStatusTitle($l['log_status']) . '</td>';
						echo '</tr>';
					}
					echo '</table>';
				}
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';

	}

	public function renderCSReviewsTab(PDO $link, $tr_id, TrainingRecord $tr)
	{
		$html = '';
		$html .= '<h4>Dates</h4>';
		$html .= '<form name="frmCSReviewDates" action="' . $_SERVER['PHP_SELF'] . '" method="post">';
		$html .= '<input type="hidden" name="_action" value="save_cs_review_dates" />';
		$html .= '<input type="hidden" name="id" value="' . $tr->id . '" />';
		$html .= '<input type="hidden" name="username" value="' . $tr->username . '" />';
		$html .= '<table style="margin-left:10px" cellspacing="6" cellpadding="6">';
		$html .= '<tr>';
		$html .= '<td class="fieldLabel_compulsory">Review 1 Date:</td>';
		$html .= '<td>' . HTML::datebox('cs_review1', $tr->cs_review1, true) . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="fieldLabel_compulsory">Review 2 Date:</td>';
		$html .= '<td>' . HTML::datebox('cs_review2', $tr->cs_review2, true) . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="fieldLabel_compulsory">Review 3 Date:</td>';
		$html .= '<td>' . HTML::datebox('cs_review3', $tr->cs_review3, true) . '</td>';
		$html .= '</tr>';
		if($_SESSION['user']->type != User::TYPE_LEARNER)
		{
			$html .= '<tr>';
			$html .= '<td colspan="2" align="right"><span class="button" onclick="document.forms[\'frmCSReviewDates\'].submit();"> &nbsp; Save &nbsp; </span> </td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		$html .= '</form>';

		$html .= '<h4>&nbsp;</h4>';
		$signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE id = '{$_SESSION['user']->id}'");
		if($_SESSION['user']->isAdmin())
		{
			$html .= '<p><span class="button" onclick="window.location.replace(\'do.php?_action=cs_review&tr_id='.$tr_id.'\');"> &nbsp;&nbsp;&nbsp; Review &raquo; &nbsp;&nbsp;&nbsp; </span> </p>';
		}
		else
		{
			if($signature == '' || is_null($signature))
				$html .= '<p><span class="button" onclick="return alert(\'Please first create your signature\');"> &nbsp;&nbsp;&nbsp; Review &raquo; &nbsp;&nbsp;&nbsp; </span> </p>';
			else
				$html .= '<p><span class="button" onclick="window.location.replace(\'do.php?_action=cs_review&tr_id='.$tr_id.'\');"> &nbsp;&nbsp;&nbsp; Review &raquo; &nbsp;&nbsp;&nbsp; </span> </p>';
		}

		return $html;
	}

	public function renderEPATab(PDO $link, $tr_id, TrainingRecord $tr)
	{
		$framework_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$tr_id}'");
		$framework = Framework::loadFromDatabase($link, $framework_id);

        if(!$framework)
            return '<i>Please edit the learner\'s framework and select EPA organisation.</i>';

		$epa_organisation = DAO::getObject($link, "SELECT * FROM central.epa_organisations WHERE EPA_ORG_ID = '{$framework->epa_org_id}'");
		if(!isset($epa_organisation->EPA_ORG_ID))
			return '<i>Please edit the learner\'s framework and select EPA organisation.</i>';
		$epa_org_assessor = DAO::getObject($link, "SELECT * FROM epa_org_assessors WHERE id = '{$framework->epa_org_assessor_id}'");

		$tr_epa = DAO::getObject($link, "SELECT * FROM tr_epa WHERE tr_id = '{$tr->id}'");
		if(!isset($tr_epa->tr_id))
		{
			$tr_epa = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM tr_epa");
			foreach($records AS $key => $value)
				$tr_epa->$value = null;
			$tr_epa->tr_id = $tr_id;
		}
		$epa_result_ddl = array(array('1', 'Pass'), array('2', 'Fail'), array('3', 'Not Ready'), array('4', 'Referred'));

		$epa_assessor_details = '';
		if(isset($epa_org_assessor->title))
		{
			$epa_assessor_details = <<<HTML
			$epa_org_assessor->title $epa_org_assessor->firstnames $epa_org_assessor->surname <br>
			$epa_org_assessor->address1 <br>$epa_org_assessor->address2 <br>$epa_org_assessor->address3 <br>$epa_org_assessor->address4 <br>
			$epa_org_assessor->postcode <br>
			<a href="mailto:$epa_org_assessor->email">$epa_org_assessor->email</a> <br>
			$epa_org_assessor->telephone
HTML;

		}

		$return_html = <<<HTML
<fieldset>
	<legend>EPA Organisation and EPA Assessor</legend>
	<table border="0" cellspacing="2" cellpadding="6" style="margin-left:5px">
		<tr>
			<td class="fieldLabel" valign="top">EPA Organisation:</td><td class="fieldValue">$epa_organisation->EP_Assessment_Organisations</td>
			<td class="fieldLabel" valign="top">EPA Organisation ID:</td><td class="fieldValue">$epa_organisation->EPA_ORG_ID</td>
		</tr>
		<tr>
			<td class="fieldLabel" valign="top">Address Details:</td>
			<td class="fieldValue" valign="top">$epa_organisation->Contact_address1 <br>$epa_organisation->Contact_address2 <br>$epa_organisation->Contact_address3 <br>$epa_organisation->Contact_address4 <br>$epa_organisation->Postcode</td>
			<td class="fieldLabel" valign="top">Contact Details:</td>
			<td class="fieldValue" valign="top">$epa_organisation->Contact_Name <br>$epa_organisation->Contact_number <br><a href="mailto:$epa_organisation->Contact_email">$epa_organisation->Contact_email</a></td>
		</tr>
		<tr>
			<td class="fieldLabel" valign="top">EP Assessor Details:</td>
			<td class="fieldValue" valign="top">
				$epa_assessor_details
			</td>
			<td class="fieldLabel" valign="top">Delivery Areas:</td>
			<td class="fieldValue" valign="top">
				$epa_organisation->Delivery_Area_1 <br>
				$epa_organisation->Delivery_Area_2 <br>
				$epa_organisation->Delivery_Area_3 <br>
				$epa_organisation->Delivery_Area_4 <br>
			</td>
		</tr>
	</table>
</fieldset><br>
HTML;

		$epa_rows = '';
		for($i = 1; $i <= 3; $i++)
		{
			$_d = 'epa_prop_date'.$i;
			$_r = 'epa_res'.$i;
			$_c = 'epa_comments'.$i;
			$epa_rows .= '<tr>';
			$epa_rows .= '<th valign="top">Set '.$i.'</th>';
			$epa_rows .= '<td valign="top">' . HTML:: datebox($_d, $tr_epa->$_d) . '</td>';
			$epa_rows .= '<td valign="top">' . HTML:: select($_r, $epa_result_ddl, $tr_epa->$_r, true) . '</td>';
			$epa_rows .= '<td valign="top"><textarea rows="5" cols="50" name="'.$_c.'">'.$tr_epa->$_c.'</textarea> </td>';
			$epa_rows .= '</tr>';
			$epa_rows .= '<tr><td colspan="4"><hr></td> </tr>';
		}

		$return_html .= <<<HTML
<form name="frmEPATab31" action="do.php?_action=save_epa" method="post">
	<input type="hidden" name="tr_id" value="$tr_id">
	<fieldset>
		<legend>EPA Information</legend>
		<table border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
			<tr>
				<th>EPA</th><th>Date</th><th>Result</th><th>Comments</th>
			</tr>
			$epa_rows
			<tr>
				<td colspan="4" align="center"><span class="button" onclick="submitEPAForm();">Save EPA Information</span></td>
			</tr>
		</table>
	</fieldset>
</form>
<br>
HTML;


		$student_quals = DAO::getResultset($link, "SELECT auto_id, id, internaltitle, awarding_body, awarding_body_reg, certificate_applied, certificate_received, certificate_sent FROM student_qualifications WHERE tr_id = '{$tr_id}'", DAO::FETCH_ASSOC);
		$rows = '';
		foreach($student_quals AS $qual)
		{
			$rows .= '<tr>';
			$rows .= '<td>' . $qual['id'] . '</td>';
			$rows .= '<td>' . $qual['internaltitle'] . '</td>';
			$rows .= '<td><input type="text" name="awarding_body'.$qual['auto_id'].'" id="awarding_body'.$qual['auto_id'].'" value="' . $qual['awarding_body'] . '" size="50" /></td>';
			$rows .= '<td><input type="text" name="awarding_body_reg'.$qual['auto_id'].'" id="awarding_body_reg'.$qual['auto_id'].'" value="' . $qual['awarding_body_reg'] . '" size="15" /></td>';
			$rows .= '<td>' . HTML:: datebox('certificate_applied'.$qual['auto_id'], $qual['certificate_applied']) . '</td>';
			$rows .= '<td>' . HTML:: datebox('certificate_received'.$qual['auto_id'], $qual['certificate_received']) . '</td>';
			$rows .= '<td>' . HTML:: datebox('certificate_sent'.$qual['auto_id'], $qual['certificate_sent']) . '</td>';
			$rows .= '</tr>';
			$rows .= '<tr><td colspan="6"><hr></td> </tr>';
		}

		$return_html .= <<<HTML
<form name="frmEPATab31Certification" action="do.php?_action=save_epa_certification" method="post">
	<input type="hidden" name="tr_id" value="$tr_id">
	<fieldset>
		<legend>EPA Certification</legend>
		<table border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Awarding Body</th>
				<th>Awarding Body Reg/Ref.</th>
				<th><abbr title="Certificate">Cert.</abbr> Applied</th>
				<th><abbr title="Certificate">Cert.</abbr> Received</th>
				<th><abbr title="Certificate">Cert.</abbr> Sent to Learner</th>
			</tr>
		</thead>
		<tbody>
			$rows
			<tr>
				<td colspan="7" align="center"><span class="button" onclick="submitEPAFormCertification();">Save EPA Certification</span></td>
			</tr>
		</tbody>

		</table>
	</fieldset>
</form>
<br>
HTML;

		return $return_html;
	}

	public function renderRetailReviewsTab(PDO $link, $tr_id)
	{
		$btn_retailer_add_new_review_status = '<span class="button" style="pointer-events: none; opacity: 0.7;" title="Please complete the existing review in order to complete the new one." >Create New</span>';
		$number_of_retailer_reviews = DAO::getObject($link, "SELECT SUM(IF(TRUE, 1, 1)) AS total, SUM(IF(retailer_reviews.`assessor_signature` IS NOT NULL AND retailer_reviews.`learner_signature` IS NOT NULL, 1, 0)) AS completed FROM retailer_reviews WHERE tr_id = '{$tr_id}'");
		if(($number_of_retailer_reviews->total == 0 || $number_of_retailer_reviews->total == $number_of_retailer_reviews->completed) && $number_of_retailer_reviews->total <= 6)
			$btn_retailer_add_new_review_status = '<span class="button" onclick="window.location.href=\'do.php?_action=view_edit_retailer_review&id=&tr_id=' . $tr_id . '\'" >Create New</span>';

		$return_html = '<p>' . $btn_retailer_add_new_review_status .'</p>';
		$return_html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="10">';
		$return_html .= '<tr><th>&nbsp;</th><th>Completed</th><th>Last Modified Date</th></tr>';
		$records = DAO::getResultset($link, "SELECT * FROM retailer_reviews WHERE tr_id = '{$tr_id}' ORDER BY id", DAO::FETCH_ASSOC);
		if(count($records) == 0)
		{
			$return_html .= '<tr><td colspan="3">No records found.</td></tr>';
		}
		else
		{
			$i = 0;
			foreach($records AS $row)
			{
				if($_SESSION['user']->type != User::TYPE_LEARNER)
					$return_html .= HTML::viewrow_opening_tag('do.php?_action=view_edit_retailer_review&id='.$row['id'].'&tr_id=' . $row['tr_id']);
				else
					$return_html .= '<tr>';
				$return_html .= '<td>Review ' . ++$i . '</td>';
				if(!is_null($row['learner_signature']) && !is_null($row['assessor_signature']))
					$return_html .= '<td>Yes</td>';
				else
					$return_html .= '<td>No</td>';
				$return_html .= '<td>' . Date::to($row['modified'], Date::DATETIME) . '</td>';
				$return_html .= '</tr>';
			}
		}
		$return_html .= '</table>';

		$return_html .= '<h3>Retail Self-Assessment</h3><table class="resultset" border="0" cellspacing="0" cellpadding="10">';
		$return_html .= '<tr><span class="button" onclick="window.location.href=\'do.php?_action=view_edit_retailer_self_assessment&id=&tr_id=' . $tr_id . '\'" >View Retail Self Assessment</span></tr>';
		$return_html .= '</table>';

		return $return_html;
	}

	public function renderLRSAchievementRecordsTab(PDO $link, $tr_id)
	{

		$return_html = '';
		$return_html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="10">';
		$return_html .= '<thead><tr>';
		$return_html .= '<th></th><th>Achievement Award Date</th><th>Achievement Provider Name</th><th>Achievement Provider UKPRN</th>';
		$return_html .= '<th>Awarding Organisation Name</th><th>Credits</th><th>Date Loaded</th><th>Grade</th>';
		$return_html .= '<th>Language for Assessment</th><th>Level</th><th>Qualification Type</th><th>Source</th>';
		$return_html .= '<th>Status</th><th>Subject</th><th>Subject Code</th>';
		$return_html .= '</tr></thead>';
		$records = [];
        // If client is using Onboarding module.
        if(SystemConfig::getEntityValue($link, "onboarding"))
        {
            $sql = "
            SELECT
                lrs_learner_learning_events.*
            FROM
                lrs_learner_learning_events INNER JOIN ob_tr ON (lrs_learner_learning_events.`tr_id` = ob_tr.`id` AND lrs_learner_learning_events.sunesis_core = 0)
            WHERE 
                ob_tr.sunesis_tr_id = '{$tr_id}'
            ";
            $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
            // If not Onboarding then check if downloaded in Core Sunesis.
            if(count($records) == 0)
            {
                $sql = "
                SELECT
                    lrs_learner_learning_events.*
                FROM
                    lrs_learner_learning_events INNER JOIN tr ON (lrs_learner_learning_events.`tr_id` = tr.`id` AND lrs_learner_learning_events.sunesis_core = 1)
                WHERE 
                    lrs_learner_learning_events.`tr_id` = '{$tr_id}'
                ";
                $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
            }
        }
        else
        {
            // If not Onboarding then check if downloaded in Core Sunesis.
            $sql = "
                SELECT
                    lrs_learner_learning_events.*
                FROM
                    lrs_learner_learning_events INNER JOIN tr ON (lrs_learner_learning_events.`tr_id` = tr.`id` AND lrs_learner_learning_events.sunesis_core = 1)
                WHERE 
                    lrs_learner_learning_events.`tr_id` = '{$tr_id}'
                ";
                $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        }
		if(count($records) == 0)
		{
			$return_html .= '<tr><td colspan="15">No records found.</td></tr>';
		}
		else
		{
			foreach($records AS $row)
			{
				$return_html .= '<tr>';
				$return_html .= '<td><img src="images/rosette.gif" alt=""></td>';
				$return_html .= '<td>' . Date::toShort($row['AchievementAwardDate']) . '</td>';
				$return_html .= '<td>' . $row['AchievementProviderName'] . '</td>';
				$return_html .= '<td>' . $row['AchievementProviderUkprn'] . '</td>';
				$return_html .= '<td>' . $row['AwardingOrganisationName'] . '</td>';
				$return_html .= '<td>' . $row['Credits'] . '</td>';
				$return_html .= '<td>' . Date::toShort($row['DateLoaded']) . '</td>';
				$return_html .= '<td>' . $row['Grade'] . '</td>';
				$return_html .= '<td>' . $row['LanguageForAssessment'] . '</td>';
				$return_html .= '<td>' . $row['Level'] . '</td>';
				$return_html .= '<td>' . $row['QualificationType'] . '</td>';
				$return_html .= '<td>' . $row['Source'] . '</td>';
				$return_html .= '<td>' . $row['Status'] . '</td>';
				$return_html .= '<td>' . $row['Subject'] . '</td>';
				$return_html .= '<td>' . $row['SubjectCode'] . '</td>';
				$return_html .= '</tr>';
			}
		}
		$return_html .= '</table>';

		return $return_html;
	}


    public function renderSkillsScanTab(PDO $link, $tr_id)
    {
        $framework_id = DAO::getSingleValue($link, "SELECT framework_id FROM courses_tr WHERE tr_id = '$tr_id'");
        if($framework_id==378 or $framework_id==404 or $framework_id==405)
        {
            $count=DAO::getSingleValue($link, "select count(*) from skills_scan where tr_id = '$tr_id'");
            if($count==0)
                DAO::execute($link,"INSERT INTO skills_scan SELECT NULL,id,'$tr_id','', '', '', '' FROM `lookup_skills_scan` WHERE framework_id = '$framework_id';");

            $st = DAO::query($link, "SELECT skills_scan.id,`lookup_skills_scan`.`description`,`lookup_skills_scan`.`description2`,skills_scan.`grade`, lookup_skills_scan.category
                                    FROM skills_scan LEFT JOIN  `lookup_skills_scan` ON `lookup_skills_scan`.`id` = skills_scan.`plan_id`
                                    AND lookup_skills_scan.`framework_id` = '$framework_id'
                                    WHERE tr_id = '$tr_id';");
            $ss_rows = '';
            $category = "";
            while($row = $st->fetch())
            {
                if($category!=$row['category'])
                {
                    if($category!="")
                        $ss_rows .= "</table></fieldset><br>";
                    $ss_rows .= "<fieldset><legend>" . $row['category'] . "</legend>";
                    $ss_rows .= '<table border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">';
                    $ss_rows .= "<tr><th>Criteria</th><th>Grade</th></tr>";
                    $category=$row['category'];
                }
                $ss_rows .= '<tr>';
                $ss_rows .= '<td valign="top">'.$row['description'].'</td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss|' . $row['id'] . '" value="' . $row['grade'] .  '" size=5/></td>';
                $ss_rows .= '</tr>';
            }
            $ss_rows .= "</table></fieldset><br>";

            $return_html = <<<HTML
<form name="frmSkillsScan" action="do.php?_action=save_skills_scan" method="post">
	<input type="hidden" name="tr_id" value="$tr_id">
	<input type="hidden" name="framework_id" value="$framework_id">
			$ss_rows
	<br>
    <table><tr>
        <td colspan="4" align="center"><span class="button" onclick="submitSSForm();">Save</span></td>
    </tr></table>
</form>
<br>
HTML;



        }
        else
        {
            $count=DAO::getSingleValue($link, "select count(*) from skills_scan where tr_id = '$tr_id'");
            if($count==0)
                DAO::execute($link,"INSERT INTO skills_scan SELECT NULL,id,'$tr_id','','','','' FROM `lookup_skills_scan` WHERE framework_id = '$framework_id';");

            $count=DAO::getSingleValue($link, "select count(*) from technical_knowledge where tr_id = '$tr_id'");
            if($count==0)
                DAO::execute($link,"INSERT INTO technical_knowledge SELECT NULL,id,'$tr_id','','','','' FROM `lookup_technical_knowledge` WHERE framework_id = '$framework_id';");

            $count=DAO::getSingleValue($link, "select count(*) from attitudes_behaviours where tr_id = '$tr_id'");
            if($count==0)
                DAO::execute($link,"INSERT INTO attitudes_behaviours SELECT NULL,id,'$tr_id','','','','' FROM `lookup_attitudes_behaviours` WHERE framework_id = '$framework_id';");

            $st = DAO::query($link, "SELECT skills_scan.id,`lookup_skills_scan`.`description`,`lookup_skills_scan`.`description2`,skills_scan.`grade`,skills_scan.`grade2`,skills_scan.`grade3`,skills_scan.`grade4` FROM skills_scan LEFT JOIN  `lookup_skills_scan` ON `lookup_skills_scan`.`id` = skills_scan.`plan_id`
AND lookup_skills_scan.`framework_id` = '$framework_id'
WHERE tr_id = '$tr_id';");
            $ss_rows = '';
            while($row = $st->fetch())
            {
                $ss_rows .= '<tr>';
                $ss_rows .= '<td valign="top">'.$row['description'].'</td>';
                $ss_rows .= '<td valign="top">'.$row['description2'].'</td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss|' . $row['id'] . '" value="' . $row['grade'] .  '" size=5/></td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss2|' . $row['id'] . '" value="' . $row['grade2'] .  '" size=5/></td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss3|' . $row['id'] . '" value="' . $row['grade3'] .  '" size=5/></td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss4|' . $row['id'] . '" value="' . $row['grade4'] .  '" size=5/></td>';
                $ss_rows .= '</tr>';
            }


            $st2 = DAO::query($link, "SELECT technical_knowledge.id,`lookup_technical_knowledge`.`description`,`lookup_technical_knowledge`.`description2`,technical_knowledge.`grade`,technical_knowledge.`grade2`,technical_knowledge.`grade3`,technical_knowledge.`grade4` FROM technical_knowledge LEFT JOIN  `lookup_technical_knowledge` ON `lookup_technical_knowledge`.`id` = technical_knowledge.`plan_id`
AND lookup_technical_knowledge.`framework_id` = '$framework_id'
WHERE tr_id = '$tr_id';");
            $tk_rows = '';
            while($row2 = $st2->fetch())
            {
                $tk_rows .= '<tr>';
                $tk_rows .= '<td valign="top">'.$row2['description'].'</td>';
                $tk_rows .= '<td valign="top">'.$row2['description2'].'</td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk|' . $row2['id'] . '" value="' . $row2['grade'] .  '" size=5/></td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk2|' . $row2['id'] . '" value="' . $row2['grade2'] .  '" size=5/></td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk3|' . $row2['id'] . '" value="' . $row2['grade3'] .  '" size=5/></td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk4|' . $row2['id'] . '" value="' . $row2['grade4'] .  '" size=5/></td>';
                $tk_rows .= '</tr>';
            }

            $st3 = DAO::query($link, "SELECT attitudes_behaviours.id,`lookup_attitudes_behaviours`.`description`,attitudes_behaviours.`grade`,attitudes_behaviours.`grade2`,attitudes_behaviours.`grade3`,attitudes_behaviours.`grade4` FROM attitudes_behaviours LEFT JOIN  `lookup_attitudes_behaviours` ON `lookup_attitudes_behaviours`.`id` = attitudes_behaviours.`plan_id`
AND lookup_attitudes_behaviours.`framework_id` = '$framework_id'
WHERE tr_id = '$tr_id';");
            $ab_rows = '';
            while($row3 = $st3->fetch())
            {
                $ab_rows .= '<tr>';
                $ab_rows .= '<td valign="top">'.$row3['description'].'</td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab|' . $row3['id'] . '" value="' . $row3['grade'] .  '" size=5/></td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab2|' . $row3['id'] . '" value="' . $row3['grade2'] .  '" size=5/></td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab3|' . $row3['id'] . '" value="' . $row3['grade3'] .  '" size=5/></td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab4|' . $row3['id'] . '" value="' . $row3['grade4'] .  '" size=5/></td>';
                $ab_rows .= '</tr>';
            }



            $return_html = <<<HTML
<form name="frmSkillsScan" action="do.php?_action=save_skills_scan" method="post">
	<input type="hidden" name="tr_id" value="$tr_id">
	<input type="hidden" name="framework_id" value="$framework_id">
	<fieldset>
		<legend>Competencies</legend>
		<table border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
			<tr>
				<th>Competency</th><th>Description</th><th>Starting Point Grade</th><th>Month 6 Review</th><th>Month 12 Review</th><th>Month 18 Review</th>
			</tr>
			$ss_rows
		</table>
	</fieldset>
	<br>
	<fieldset>
		<legend>Technical Knowledge</legend>
		<table border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
			<tr>
				<th>Technical Knowledge</th><th>Description</th><th>Starting Point Grade</th><th>Month 6 Review</th><th>Month 12 Review</th><th>Month 18 Review</th>
			</tr>
			$tk_rows
		</table>
	</fieldset>
	<br>
	<fieldset>
		<legend>Attitudes & Behaviours</legend>
		<table border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
			<tr>
				<th>Attitudes & Behaviours</th><th>Starting Point Grade</th><th>Month 6 Review</th><th>Month 12 Review</th><th>Month 18 Review</th>
			</tr>
			$ab_rows
			<tr>
				<td colspan="4" align="center"><span class="button" onclick="submitSSForm();">Save</span></td>
			</tr>
		</table>
	</fieldset>
</form>
<br>
HTML;

        }


        return $return_html;
    }

	public function renderEmployerTabExtra(PDO $link, $tr_id)
	{
		$sql = <<<SQL
SELECT 	ilr
FROM ilr WHERE tr_id IN (SELECT id FROM tr WHERE username = (SELECT username FROM tr WHERE id = '$tr_id'));
SQL;
		$current_employer_edrs = DAO::getSingleValue($link, "SELECT edrs FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = '{$tr_id}'");
		$results = DAO::getSingleColumn($link, $sql);
        $exclude = Array();
        $data = [];
        $rows = '';
        foreach($results as $result)
        {
            $ilr = XML::loadSimpleXML($result);
            foreach($ilr->LearnerEmploymentStatus AS $LearnerEmploymentStatus)
            {
                if(isset($LearnerEmploymentStatus->EmpId) && $LearnerEmploymentStatus->EmpId->__toString() != $current_employer_edrs)
                {
                    if(!in_array($LearnerEmploymentStatus->EmpId->__toString(), $exclude))
                    {
                        $data[$LearnerEmploymentStatus->DateEmpStatApp->__toString()] = $LearnerEmploymentStatus->EmpId->__toString();
                        $exclude[] = $LearnerEmploymentStatus->EmpId->__toString();
                    }
                }
            }
        }
        ksort($data);
		foreach($data AS $key => $value)
		{
			$org_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE edrs = '{$value}'");
			$rows .= "<tr><td>" . $value . "</td><td>" . $org_name . "</td><td>" . Date::toShort($key) . "</td></tr>";
		}

		$table = '';
		if($rows != '')
		{
			$table .= "<h4>Previous Employers</h4>";
			$table .= "<table class=\"table resultset\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\"> ";
			$table .= "<thead><tr><th>EDRS</th><th>Employer Name</th><th>Date Emp. Status Applies</th></tr></thead>";
			$table .= '<tbody>' . $rows . '</tbody>';
			$table .= "</table>";
		}
		return $table;
	}

	public function renderManagerComments(PDO $link, $tr_id)
	{
		if(in_array($_SESSION['user']->username, ['abielok', 'shaley12', 'jcoates', 'fkhan1234','lroddamcarty', 'jrearsv', 'jakbird', 'atodd123', 'mijones12', 'codiefoster', 'arockett16', 'jparkin18', 'marbrown', 'olboukadida', 'creay123', 'nellwood1', 'hgibson1', 'lcolquhoun', 'ecann123', 'nrichardson1', 'rachaelgreen']))
			echo '<span class="button" onclick="window.location.href=\'/do.php?_action=edit_tr_manager_comment&id=&tr_id='.$tr_id.'\';">New Manager Comments Entry</span>';
		echo '<p></p>';
		
		$sql = "SELECT * FROM manager_comments WHERE tr_id = '{$tr_id}' AND comment_type != 'FS' ORDER BY updated_at DESC";
		$st = $link->query($sql);
		if($st)
		{
			echo '<div align="left"><table style="width: 80%;" class="resultset" border="0" cellspacing="0" cellpadding="6">';

			echo '<thead><tr>';
			echo '<th  class="topRow">Created</th><th>Last Updated</th><th>RAG</th><th>Comment Type</th><th style="width: 40%">Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			$comment_types = [
				'ER' => 'Employer reference comment',
				'LP' => 'Learner progress comment',
				'FS' => 'Functional Skills'
			];
			$rags = [
				'R' => 'Red',
				'A' => 'Amber',
				'G' => 'Green'
			];
			if($st->rowCount() > 0)
			{
				while($row = $st->fetch())
				{	
					if(!in_array($_SESSION['user']->username, ['atodd123', 'abielok', 'shaley12', 'jcoates', 'fkhan1234','lroddamcarty', 'jrearsv', 'mijones12', 'codiefoster', 'arockett16', 'jparkin18', 'marbrown', 'olboukadida', 'creay123', 'nellwood1', 'hgibson1', 'lcolquhoun', 'ecann123', 'nrichardson1', 'rachaelgreen']))
						echo '<tr>';
					else
						echo HTML::viewrow_opening_tag('do.php?_action=edit_tr_manager_comment&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);

					echo '<td align="left">';
					echo 'Timestamp: ' . Date::to($row['created_at'], Date::DATETIME) . '<br>';
					echo 'By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
					echo '</td>';
					echo '<td align="left">';
					echo 'Timestamp: ' . Date::to($row['updated_at'], Date::DATETIME) . '<br>';
					echo 'By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['last_updated_by']}'");
					echo '</td>';
					$rag = isset($rags[$row['rag']]) ? $rags[$row['rag']] : $row['rag'];
					echo '<td align="left">' . HTML::cell($rag) . '</td>';
					$ct = isset($comment_types[$row['comment_type']]) ? $comment_types[$row['comment_type']] : $row['comment_type'];
					echo '<td align="left">' . HTML::cell($ct) . '</td>';
					echo '<td>' . HTML::cell($row['comment']) . '</td>';
					echo '</tr>';
				}	
			}
			else
			{
				echo '<tr><td colspan="4"><i>No records found.</i></td></tr>';  	
			}
			
			echo '</tbody></table></div>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	public function renderFsManagerComments(PDO $link, $tr_id)
	{
		echo '<p></p>';
		
		$sql = "SELECT * FROM manager_comments WHERE tr_id = '{$tr_id}' AND comment_type = 'FS' ORDER BY updated_at DESC";
		$st = $link->query($sql);
		if($st)
		{
            if($st->rowCount() == 0)
            {
                return;
            }
            
			echo '<div align="left"><table style="width: 80%;" class="resultset" border="0" cellspacing="0" cellpadding="6">';

			echo '<thead><tr>';
			echo '<th  class="topRow">Created</th><th>Last Updated</th><th>RAG</th><th>Comment Type</th><th style="width: 40%">Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			$comment_types = [
				'ER' => 'Employer reference comment',
				'LP' => 'Learner progress comment',
				'FS' => 'Functional Skills'
			];
			$rags = [
				'R' => 'Red',
				'A' => 'Amber',
				'G' => 'Green'
			];
			if($st->rowCount() > 0)
			{
				while($row = $st->fetch())
				{
					if(!in_array($_SESSION['user']->username, ['lroddamcarty', 'jrearsv', 'mijones12', 'codiefoster', 'arockett16', 'jparkin18', 'marbrown', 'olboukadida', 'creay123', 'nellwood1', 'hgibson1', 'lcolquhoun', 'ecann123', 'nrichardson1', 'rachaelgreen']))
						echo '<tr>';
					else
						echo HTML::viewrow_opening_tag('do.php?_action=edit_tr_manager_comment&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);

					echo '<td align="left">';
					echo 'Timestamp: ' . Date::to($row['created_at'], Date::DATETIME) . '<br>';
					echo 'By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
					echo '</td>';
					echo '<td align="left">';
					echo 'Timestamp: ' . Date::to($row['updated_at'], Date::DATETIME) . '<br>';
					echo 'By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['last_updated_by']}'");
					echo '</td>';
					$rag = isset($rags[$row['rag']]) ? $rags[$row['rag']] : $row['rag'];
					echo '<td align="left">' . HTML::cell($rag) . '</td>';
					$ct = isset($comment_types[$row['comment_type']]) ? $comment_types[$row['comment_type']] : $row['comment_type'];
					echo '<td align="left">' . HTML::cell($ct) . '</td>';
					echo '<td>' . HTML::cell($row['comment']) . '</td>';
					echo '</tr>';
				}	
			}
			else
			{
				echo '<tr><td colspan="4"><i>No records found.</i></td></tr>';  	
			}
			
			echo '</tbody></table></div>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	public function renderLearnerChocs(PDO $link, $tr_id)
    {
        $sql = "SELECT * FROM chocs WHERE tr_id = '{$tr_id}' ORDER BY updated_at DESC";
        $st = $link->query($sql);
        if($st)
        {
            echo '<div align="left"><table style="width: 50%;" class="resultset" border="0" cellspacing="0" cellpadding="6">';

            echo '<thead><tr>';
            echo '<th  class="topRow">Type</th><th>Initiated By</th><th>Status</th><th>Assigned To</th><th>Created At</th><th>Last Updated At</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            if($st->rowCount() > 0)
            {
                while($row = $st->fetch())
                {
                    echo HTML::viewrow_opening_tag('do.php?_action=read_choc&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);

                    echo '<td align="left">' . HTML::cell($row['choc_type']) . '</td>';
                    echo '<td align="left">' . HTML::cell(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'")) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['choc_status']) . '</td>';
                    echo '<td align="left">' . HTML::cell(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['assigned_to']}'")) . '</td>';
                    echo '<td align="left">' . Date::to($row['created_at'], Date::DATETIME) . '</td>';
                    echo '<td align="left">' . Date::to($row['updated_at'], Date::DATETIME) . '</td>';
                    echo '</tr>';
                }
            }
            else
            {
                echo '<tr><td colspan="4"><i>No records found.</i></td></tr>';
            }

            echo '</tbody></table></div>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

	private function convertToHoursMins($time, $format = '%02d:%02d')
	{
		if ($time < 1)
		{
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}

	public function renderComplianceTab(PDO $link, TrainingRecord $tr)
	{
		$programme_type = DAO::getSingleValue($link, "SELECT courses.programme_type FROM courses LEFT JOIN courses_tr ON courses_tr.course_id = courses.id WHERE courses_tr.tr_id = '{$tr->id}'");
		$ids = '';
		if ( isset($programme_type) && $programme_type != '' )
		{
			$ids = DAO::getSingleColumn($link, "SELECT id FROM events_template WHERE programme_type = ( IF('{$programme_type}' = 5, 2, IF('{$programme_type}' > 2, 1, '{$programme_type}')) )");
			$ids = implode(",", $ids);
		}
		echo '<form name="compliance" autocomplete="off">';

		if(!in_array($_SESSION['user']->type, [User::TYPE_ORGANISATION_VIEWER, User::TYPE_LEARNER, User::TYPE_REVIEWER]))
		{
			echo '<span id="compliancesavebutton" class="button" onclick="saveCompliance(\'' . $ids . '\');">&nbsp;Save&nbsp;</span><span><img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>';
		}

		$sql = <<<HEREDOC
SELECT DISTINCT
	events_template.*,
	student_events.*,
	DATE_FORMAT(event_date,"%d-%m-%Y") AS event_date
FROM
	courses_tr
	LEFT JOIN tr on tr.id = courses_tr.tr_id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN events_template ON events_template.provider_id = tr.provider_id AND (events_template.course_id = courses_tr.course_id OR events_template.course_id = 0  OR events_template.`course_id` IS NULL)
	AND ( IF(courses.programme_type = 5, 2 = events_template.programme_type, IF(courses.programme_type > 2, 1 = events_template.programme_type, courses.programme_type = events_template.programme_type )) )
	LEFT JOIN student_events ON student_events.event_id = events_template.id AND student_events.tr_id = '{$tr->id}'
WHERE
	courses_tr.tr_id = '{$tr->id}'
;
HEREDOC;


		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Event Title</th><th>Status</th><th>Actual Date</th><th>Comments</th></tr></thead>';
			echo '<tbody>';

			$due_date = new Date($tr->start_date);

			$subsequent_weeks = DAO::getSingleValue($link, "SELECT frequency FROM courses INNER JOIN courses_tr ON courses.id = courses_tr.course_id WHERE courses_tr.tr_id = '{$tr->id}'");
			if($subsequent_weeks == '' || $subsequent_weeks == 0)
				$subsequent_weeks = 4;

			while($row = $st->fetch())
			{
				$due_date->addDays($subsequent_weeks * 7);
				$did = $row['id'];
				echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
				echo '<td align="left" name="ids" title="' . $did . '">' . HTML::cell($row['title']) . "</td>";
				if($row['event_id']=='')
					$checked = '';
				else
					$checked = 'checked';
				echo "<td align=center><input type='checkbox' id ='compliancestatus" . $did . "' name ='status" . $did . "' " . $checked . "/>&nbsp;</td>";
				echo "<td>" . HTML::datebox("compliancedate".$did, $row['event_date'], true) . "</td>";

				if($row['comments']=='')
                {
                    if(DB_NAME=='am_baltic')
                        echo "<td style='vetical-align: middle'><table><tr><td><span title='" . $row['comments'] . "' class='button' id=" . $did . " onclick='showComplianceComments(this);'>+/-</span></td><td><textarea  onKeyPress=\"return numbersonly(this, event, 1)\" rows=3 cols=30 style='display: none;' id='compliancecomments" . $did . "'>" . $row['comments'] . "</textarea></td></tr></table></td>";
                    else
                         echo "<td style='vetical-align: middle'><table><tr><td><span title='" . $row['comments'] . "' class='button' id=" . $did . " onclick='showComplianceComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='compliancecomments" . $did . "'>" . $row['comments'] . "</textarea></td></tr></table></td>";
                }
                else
				{
                    if(DB_NAME=='am_baltic')
                        echo "<td style='vetical-align: middle'><table><tr><td><span title='" . $row['comments'] . "' class='button' id=" . $did . " onclick='showComplianceComments(this);'>+/-</span></td><td><textarea  onKeyPress=\"return numbersonly(this, event, 1)\" rows=3 cols=30 style='display: block;' id='compliancecomments" . $did . "'>" . $row['comments'] . "</textarea></td></tr></table></td>";
                    else
                        echo "<td style='vetical-align: middle'><table><tr><td><span title='" . $row['comments'] . "' class='button' id=" . $did . " onclick='showComplianceComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: block;' id='compliancecomments" . $did . "'>" . $row['comments'] . "</textarea></td></tr></table></td>";
                }	

                $audit = isset($row['audit'])?$row['audit']:"";    
                echo '<td><input type = hidden name = "input_audit' . $did . '" id = "input_audit' . $did . '" value = "' . $audit . '"></td>';

				echo '</tr>';
			}
			echo '</table>';
		}

		echo '</form>';
	}

	public function renderComplianceTabV2(PDO $link, TrainingRecord $tr)
	{
		$listStatus1 = [
			'CP' => 'Checked and processed',
			'Q' => 'Query',
			'RA' => 'Received and awaiting processing',
		];
		if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
		{
			echo '<p><span class="button" onclick="window.location.href=\'do.php?_action=edit_tr_compliance&tr_id=' . $tr->id . '\'" >Edit Compliance</span></p>';
		}

		$framework_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$tr->id}'");

		$sql = <<<SQL
SELECT
  compliance_checklist.`id`,
  compliance_checklist.`c_event`,
  compliance_checklist.`sub_events` AS sub_events_xml,
  tr_compliance.*
FROM
  compliance_checklist
  LEFT JOIN tr_compliance
    ON (
      compliance_checklist.`id` = tr_compliance.`compliance_item_id`
      AND tr_compliance.`tr_id` = '{$tr->id}'
    )
WHERE
  compliance_checklist.framework_id = '{$framework_id}'
ORDER BY
    compliance_checklist.sorting;
SQL;

		$compliance_list = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr><th>Compliance Item</th><th>Date Submitted</th><th>Evidence Uploaded/Posted</th><th>Actual Date</th><th>Status</th><th>Comments</th></tr></thead>';
		echo '<tbody>';
		foreach($compliance_list AS $item)
		{
			echo $item['compliant'] == '1' ? '<tr style="background-color: #e0ffff;">' : '<tr>';
			echo '<td>' . $item['c_event'] . '</td>';
			echo '<td>' . Date::toShort($item['submitted_date']) . '</td>';
			echo '<td>';
			$SubEvents = XML::loadSimpleXML($item['sub_events_xml']);
			echo '<table class="resultset" cellpadding="6">';
			$checked_sub_events = explode(',', $item['sub_events']);
			foreach($SubEvents->Event AS $Event)
			{
				echo '<tr>';
				$temp = array();
				$temp = (array)$Event->attributes();
				$temp = $temp['@attributes'];
				echo '<td>' . $temp['title'] . '</td>';
				echo in_array($temp['id'], $checked_sub_events) ?
					'<td class="text-center"><input type="checkbox" checked disabled /></td>' :
					'<td class="text-center"><input type="checkbox" disabled /></td>';
				echo '</tr>';
			}
			echo '</table>';
			echo '</td>';
			echo '<td>' . Date::toShort($item['actual_date']) . '</td>';
			echo isset($listStatus1[$item['status1']]) ? '<td>' . $listStatus1[$item['status1']] . '</td>' : '<td></td>';
			echo '<td>' . nl2br((string) $item['comments'] ?: '') . '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}

	public function renderCaseloadManagement(PDO $link, $tr_id)
    {
        echo '<p></p>';

        $sql = "SELECT * FROM caseload_management WHERE tr_id = '{$tr_id}' ORDER BY created_at DESC";
        $st = $link->query($sql);
        if($st)
        {
            echo '<div align="left"><table style="width: 80%;" class="resultset" border="0" cellspacing="0" cellpadding="6">';

	    echo $st->rowCount() > 1 ? '<caption><strong>' . $st->rowCount() . ' records (order by creation date - descending)</strong></caption>' : '';
            echo '<thead><tr class="topRow">';
            echo '<th></th>';
            echo '<th>Action Plan</th>';
            echo '<th>Auditor Notes</th>';
            echo '<th>Other Details</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            $root_cause_list = InductionHelper::getListLARCause();

            if($st->rowCount() > 0)
            {
                while($row = $st->fetch())
                {
                    echo HTML::viewrow_opening_tag('do.php?_action=edit_baltic_caseload_management&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
                    echo '<td valign="top">';
                    echo 'Created By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . '<hr>';
                    echo 'Created At: ' . Date::to($row['created_at'], Date::DATETIME) . '<hr>';
                    echo 'Last Updated By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['last_updated_by']}'") . '<hr>';
                    echo 'Last Updated At: ' . Date::to($row['updated_at'], Date::DATETIME) . '<hr>';
                    echo '<span style="font-size: medium">Status: ' . $row['status'] . '</span><hr>';
                    echo '</td>';
                    echo '<td valign="top" style="width: 30%">' . nl2br((string) $row['action_plan'] ?: '') . '</td>';
                    echo '<td valign="top" style="width: 25%">' . nl2br((string) $row['auditor_notes'] ?: '') . '</td>';
                    echo '<td valign="top" style="width: 25%">';
                    echo 'Initial Date Raised: ' . Date::toShort($row['initial_date_raised']) . '<hr>';
                    echo 'PM Revisit Date Agreed: ' . Date::toShort($row['pm_revisit_date_agreed']) . '<hr>';
                    echo 'PEED Agreed Recommended Date: ' . Date::toShort($row['peed_agreed_recommended_date']) . '<hr>';
                    echo isset($root_cause_list[$row['root_cause']]) ? 'Root Cause: ' . $root_cause_list[$row['root_cause']] . '<hr>' : 'Root Cause: <hr>';
                    echo 'Risk Summary: ' . nl2br((string) $row['risk_summary']) . '<hr>';
                    echo $row['bil'] == 1 ? 'BIL: Checked<br>' : 'BIL: Not Checked<hr>';
                    echo $row['reinstated'] == 1 ? 'Reinstated: Checked<br>' : 'Reinstated: Not Checked<hr>';
                    echo 'Closed Date: ' . Date::toShort($row['closed_date']) . '<hr>';
                    echo 'Destination: ' . $row['destination'] . '<hr>';
                    echo 'Leaver Decision Made: ' . Date::toShort($row['leaver_decision_made']) . '<hr>';
                    echo 'Leaver Reason: ' . $row['leaver_reason'] . '<hr>';
                    echo 'Positive Outcome: ' . $row['positive_outcome'] . '<hr>';
                    echo $row['potential_return'] == 1 ? 'Potential Return: Checked<br>' : 'Potential Return: Not Checked<hr>';
                    echo $row['previous_leaver'] == 1 ? 'Previous Leaver - Reinstatement: Checked<br>' : 'Previous Leaver - Reinstatement: Not Checked<hr>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            else
            {
                echo '<tr><td colspan="4"><i>No records found.</i></td></tr>';
            }

            echo '</tbody></table></div>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public $global_units = array();
}
?>