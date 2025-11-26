<?php
class arf_introduction implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $meeting_date = isset($_REQUEST['meeting_date']) ? $_REQUEST['meeting_date'] : '';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $output = isset($_REQUEST['output']) ? $_REQUEST['output'] : '';
        $convert = isset($_REQUEST['convert']) ? $_REQUEST['convert'] : '';

        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        if($convert==1)
        {
            DAO::execute($link, "update assessor_review set template_review = 3 where id = '$review_id'");
            http_redirect("do.php?_action=arf_introduction&source=1&review_id=". $review_id . "&tr_id=" . $tr_id);
        }
        elseif($convert==2)
        {
            DAO::execute($link, "update assessor_review set template_review = 4 where id = '$review_id'");
            http_redirect("do.php?_action=arf_introduction&source=1&review_id=". $review_id . "&tr_id=" . $tr_id);
        }

        $template_review = DAO::getSingleValue($link, "select template_review from assessor_review where id = '$review_id'");
        $framework_id = DAO::getSingleValue($link, "select framework_id from courses_tr where tr_id = '$tr_id'");
        $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = '$tr_id'");

        $english_exempt = DAO::getSingleValue($link, "select aptitude from student_qualifications where tr_id = '$tr_id' and internaltitle like '%English%' limit 0,1");
        $math_exempt = DAO::getSingleValue($link, "select aptitude from student_qualifications where tr_id = '$tr_id' and internaltitle like '%Math%' limit 0,1");
        $ict_exempt = DAO::getSingleValue($link, "select aptitude from student_qualifications where tr_id = '$tr_id' and internaltitle like '%ICT%' limit 0,1");

        $form_arf = ARFIntroduction::loadFromDatabase($link, $review_id);
        $previous_form = DAO::getObject($link,"SELECT * FROM arf_introduction WHERE review_id = (SELECT MAX(id) FROM assessor_review WHERE id < '$review_id' AND tr_id = '$tr_id');");
        if(isset($previous_form))
            $form_arf = $this->updateSkillsScan($form_arf, $previous_form);
        $training_record = TrainingRecord::loadFromDatabase($link,$tr_id);

        if(DAO::getSingleValue($link, "SELECT * FROM assessor_review LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1"))
            $previous_review = DAO::getObject($link, "SELECT * FROM assessor_review LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1");
        else
            $previous_review = $form_arf;

        $evidence_matrix = "";

        $snapshot_saved = DAO::getSingleValue($link, "select count(*) from arf_introduction_additional where review_id = '$review_id'");
        if($form_arf->signature_assessor_font=="" || $snapshot_saved==0)
        {
            $skills_scan = DAO::getResultset($link,"SELECT skills_scan.id,`lookup_skills_scan`.`description`,skills_scan.`grade` FROM skills_scan LEFT JOIN  `lookup_skills_scan` ON `lookup_skills_scan`.`id` = skills_scan.`plan_id` AND lookup_skills_scan.`framework_id` = '$framework_id' WHERE tr_id = '$tr_id';");
            if(sizeof($skills_scan)==0)
            {
                // get skills scan from previous tr
                $tr_id2 = DAO::getSingleValue($link, "SELECT id FROM tr WHERE l03 IN (SELECT l03 FROM tr WHERE id = '$tr_id') AND start_date < (SELECT start_date FROM tr WHERE id = '$tr_id') AND status_code = 6;");
                $skills_scan = DAO::getResultset($link,"SELECT skills_scan.id,`lookup_skills_scan`.`description`,skills_scan.`grade` FROM skills_scan LEFT JOIN  `lookup_skills_scan` ON `lookup_skills_scan`.`id` = skills_scan.`plan_id` AND lookup_skills_scan.`framework_id` = '$framework_id' WHERE tr_id = '$tr_id2';");
            }
            $ss_result = Array();
            foreach($skills_scan as $ss)
            {
                $ss_result[$ss[1]] = $ss[2];
            }
            $ss_result_json = json_encode($ss_result);

            // Get Assessment Plan Statuses
            $Assessment_Plan = Array();
            $sql= "SELECT
                    lookup_assessment_plan_log_mode.description AS plan
                    ,sub.due_date
                    ,sub.submission_date
                    ,sub.marked_date AS marked_date_1
                    ,sub.completion_date
                    ,(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.`id`) AS submission_number
                    ,tr.contract_id
                    ,assessment_plan_log.tr_id
                    ,sub.due_date < CURDATE() AS expired
                    ,sub.iqa_status
                    ,sub.sent_iqa_date
                    ,sub.assessor_signed_off
                    ,sub.set_date
                    ,sub.acc_rej_date
                    ,sub.comments
                    FROM
                        assessment_plan_log
                        LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                            sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                        LEFT JOIN tr ON tr.id = assessment_plan_log.tr_id
                        LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
                        LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.id = assessment_plan_log.mode AND student_frameworks.id = lookup_assessment_plan_log_mode.framework_id
                                WHERE assessment_plan_log.tr_id = '$tr_id'";
            $st = $link->query($sql);
            if($st)
            {
                while($row = $st->fetch())
                {
                    if($row['completion_date']!='')
                        $status = "Complete";
                    elseif($row['iqa_status']=='2')
                        $status = "Rework Required";
                    elseif($row['sent_iqa_date']!='' and $row['iqa_status']!='2')
                        $status = "IQA";
                    elseif($row['submission_date']!='')
                        $status = "Awaiting marking";
                    elseif($row['expired']=='1' and $row['submission_date']=='')
                        $status = "Overdue";
                    elseif($row['set_date']!='' and $row['expired']=='0' and $row['submission_number']=='1')
                        $status = "In progress";
                    else
                        $status = "Rework Required";
                    $Assessment_Plan[$row['plan']]=$status;
                }
            }
            $Assessment_Plan_json = json_encode($Assessment_Plan);

            $technical_knowledge = DAO::getResultset($link,"SELECT technical_knowledge.id,`lookup_technical_knowledge`.`description`,technical_knowledge.`grade` FROM technical_knowledge LEFT JOIN  `lookup_technical_knowledge` ON `lookup_technical_knowledge`.`id` = technical_knowledge.`plan_id` AND lookup_technical_knowledge.`framework_id` = '$framework_id' WHERE tr_id = '$tr_id';");
            if(sizeof($technical_knowledge)==0 && $tr_id!=30589)
            {
                // get skills scan from previous tr
                $tr_id2 = DAO::getSingleValue($link, "SELECT id FROM tr WHERE l03 IN (SELECT l03 FROM tr WHERE id = '$tr_id') AND start_date < (SELECT start_date FROM tr WHERE id = '$tr_id') AND status_code = 6;");
                $technical_knowledge = DAO::getResultset($link,"SELECT technical_knowledge.id,`lookup_technical_knowledge`.`description`,technical_knowledge.`grade` FROM technical_knowledge LEFT JOIN  `lookup_technical_knowledge` ON `lookup_technical_knowledge`.`id` = technical_knowledge.`plan_id` AND lookup_technical_knowledge.`framework_id` = '$framework_id' WHERE tr_id = '$tr_id2';");
            }

            $tk_result = Array();
            foreach($technical_knowledge as $ss)
            {
                $tk_result[$ss[1]] = $ss[2];
            }
            $tk_result_json = json_encode($tk_result);

            $attitudes_behaviours = DAO::getResultset($link,"SELECT attitudes_behaviours.id,`lookup_attitudes_behaviours`.`description`,attitudes_behaviours.`grade` FROM attitudes_behaviours LEFT JOIN  `lookup_attitudes_behaviours` ON `lookup_attitudes_behaviours`.`id` = attitudes_behaviours.`plan_id` AND lookup_attitudes_behaviours.`framework_id` = '$framework_id' WHERE tr_id = '$tr_id';");
            if(sizeof($attitudes_behaviours)==0)
            {
                // get skills scan from previous tr
                $tr_id2 = DAO::getSingleValue($link, "SELECT id FROM tr WHERE l03 IN (SELECT l03 FROM tr WHERE id = '$tr_id') AND start_date < (SELECT start_date FROM tr WHERE id = '$tr_id') AND status_code = 6;");
                $attitudes_behaviours = DAO::getResultset($link,"SELECT attitudes_behaviours.id,`lookup_attitudes_behaviours`.`description`,attitudes_behaviours.`grade` FROM attitudes_behaviours LEFT JOIN  `lookup_attitudes_behaviours` ON `lookup_attitudes_behaviours`.`id` = attitudes_behaviours.`plan_id` AND lookup_attitudes_behaviours.`framework_id` = '$framework_id' WHERE tr_id = '$tr_id2';");
            }
            $ab_result = Array();
            foreach($attitudes_behaviours as $ss)
            {
                $ab_result[$ss[1]] = $ss[2];
            }
            $ab_result_json = json_encode($ab_result);
            $events = $training_record->getTrackingUnitsDetail($link);
            $events_json = json_encode($events);

            $assessment_percentage = TrainingRecord::getAssessmentProgress($link, $tr_id);
            $technical_percentage = $this->getTechnicalProgress($link, $tr_id);
            $exam_percentage = $this->getExamProgress($link,$tr_id);
            $reflective_hours = DAO::getSingleValue($link, "SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = '$tr_id')");
            $reflective_hours = ($reflective_hours=="")?0:$reflective_hours;
            DAO::execute($link, "replace into arf_introduction_additional values('$review_id', '$ss_result_json', '$tk_result_json' ,'$ab_result_json', '$events_json' ,'$Assessment_Plan_json', '$assessment_percentage', '$technical_percentage', '$exam_percentage', '$reflective_hours');");
        }
        else
        {
            DAO::execute($link, "UPDATE arf_introduction_additional SET assessment_plan = REPLACE(REPLACE(assessment_plan, '\r', ''), '\n', '');");
            $ss_result = json_decode(DAO::getSingleValue($link, "select ss_result from arf_introduction_additional where review_id = '$review_id'"),true);
            $Assessment_Plan = json_decode(DAO::getSingleValue($link, "select assessment_plan from arf_introduction_additional where review_id = '$review_id'"),true);
            $tk_result = json_decode(DAO::getSingleValue($link, "select tk_result from arf_introduction_additional where review_id = '$review_id'"),true);
            $ab_result = json_decode(DAO::getSingleValue($link, "select ab_result from arf_introduction_additional where review_id = '$review_id'"),true);
            $events = json_decode(DAO::getSingleValue($link, "select events from arf_introduction_additional where review_id = '$review_id'"),true);
            $assessment_percentage = DAO::getSingleValue($link, "select ap_percentage from arf_introduction_additional where review_id = '$review_id'");
            $technical_percentage = DAO::getSingleValue($link, "select kt_percentage from arf_introduction_additional where review_id = '$review_id'");
            $exam_percentage = DAO::getSingleValue($link, "select ke_percentage from arf_introduction_additional where review_id = '$review_id'");
            $reflective_hours = DAO::getSingleValue($link, "select reflective_hours from arf_introduction_additional where review_id = '$review_id'");
        }

        if($assessment_percentage=='' and $technical_percentage=='' and $exam_percentage)
        {
            $assessment_percentage = TrainingRecord::getAssessmentProgress($link, $tr_id);
            $technical_percentage = $this->getTechnicalProgress($link, $tr_id);
            $exam_percentage = $this->getExamProgress($link,$tr_id);
        }


        $ss_statuses = array(array('N','No Knowledge'),array('S','Some Knowledge'),array('F','Fully Competent'));

        $attempts = array(
            array('1', 'Yes'),
            array('0', 'No')
        );

        $keytoverify = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
        if(!isset($_SESSION['user']))
            if($key!=$keytoverify)
                pre("Invalid URL");

        $review_programme_title = DAO::getSingleValue($link, "select review_programme_title from courses inner join courses_tr on courses_tr.course_id = courses.id and courses_tr.tr_id = '$tr_id'");

        $year1920 = new DateTime("2019-08-01");
        $lsd = new DateTime($training_record->start_date);
        if($lsd >= $year1920)
            $learner_is_1920 = true;
        else
            $learner_is_1920 = false;



        if(isset($training_record->assessor) && $training_record->assessor!=0)
            $assessor = User::loadFromDatabaseById($link,$training_record->assessor);
        else
        {
            $assessor_id = DAO::getSingleValue($link,"select assessor from groups inner join group_members on group_members.groups_id = groups.id where group_members.tr_id = '$tr_id'");
            if($assessor_id)
                $assessor = User::loadFromDatabaseById($link,$assessor_id);
            else
                $assessor = new User();
        }

        $employer = Organisation::loadFromDatabase($link,$training_record->employer_id);

        if($form_arf->signature_assessor_font=='')
            $assessor_signed = true;
        else
            $assessor_signed = false;


        if($source==2 and $form_arf->signature_learner_font!='')
        {
            pre("Review is complete");
        }

        if($source==3 and $form_arf->signature_employer_font!='')
            pre("Review is complete");

        // Auto populate date
        if($source==1)
        {
            if($form_arf->signature_assessor_date=='')
                $form_arf->signature_assessor_date = date('Y-m-d');
        }
        elseif($source==2)
        {
            if($form_arf->signature_learner_date=='')
                $form_arf->signature_learner_date = date('Y-m-d');
        }
        elseif($source==3)
        {
            if($form_arf->signature_employer_date=='')
                $form_arf->signature_employer_date = date('Y-m-d');
        }

        $learner = User::loadFromDatabase($link,$training_record->username);
        if(isset($training_record->crm_contact_id))
            $crm_contact = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
        else
            $crm_contact = new EmployerContacts();
        if($form_arf->learner_programme=='')
            $form_arf->learner_programme=$review_programme_title;
        if($form_arf->learner_name=='')
            $form_arf->learner_name=$training_record->firstnames . ' ' . $training_record->surname;
        if($form_arf->learner_assessor=='' and isset($_SESSION['user']))
            $form_arf->learner_assessor=$_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname ;
        if($form_arf->learner_employer=='')
            $form_arf->learner_employer=$employer->legal_name;
        if($form_arf->start_date=='')
            $form_arf->start_date=$training_record->start_date;
        if($form_arf->planned_end_date=='')
            $form_arf->planned_end_date=$training_record->target_date;
        if($form_arf->learner_manager=='')
            $form_arf->learner_manager=addslashes((string)$crm_contact->contact_name);

        if($output=='PDF')
        {
            // Save signature files
            $this->save_signatures($link,'',$tr_id,$review_id);
            $db = DB_NAME;
            $username=$training_record->username;
            $learner_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/learner_signature.png";
            $assessor_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/assessor_signature.png";
            $employer_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/employer_signature.png";

            $header = "";
            $learner_details = $this->getLearnerDetailsPDF($form_arf, $source);
            $smart_actions= $this->getSmartActions($form_arf);
            $next_contact = $this->getNextContact($form_arf);
            $signatures = $this->getSignatures($form_arf, $assessor_signature_url, $learner_signature_url, $employer_signature_url);
            $learnerEmployer = ReviewSkillsScans::getLearnerEmployerComments($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
            $skills_scan = "";
            $functional_skills = "";
            $part1 = "";
            $part2 = "";
            $part3 = "";
            $part4 = "";
            $part5 = "";

            if($template_review==1)
            {
                $header = $this->getHeader($link, "Assessor Review Form - Introduction Review");
                $meeting_date = $form_arf->review_date;

                if($meeting_date=="" or strtotime($meeting_date)>strtotime("2020-09-01"))
                    $part1 = $this->getIntroPart1V2($link, $form_arf, $review_id, $tr_id, $source);
                else
                    $part1 = $this->getIntroPart1($link, $form_arf, $review_id, $tr_id, $source);
            }
            elseif($template_review==2)
            {
                /*if(in_array($framework_id,Array(371)))
                {
                    require ("../htdocs/module_reviews/DigitalMarketingL4.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Level 4 Digital Marketing");
                    $evidence_matrix = DigitalMarketingL4::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= DigitalMarketingL4::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }*/
                if(in_array($framework_id,Array(371)))
                {
                    require ("../htdocs/module_reviews/DigitalMarketingL4.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing Level 4");
                    $part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                    $evidence_matrix = DigitalMarketingL4::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= DigitalMarketingL4::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkillsOld($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                }
                elseif(in_array($framework_id,Array(397,410)))
                {
                    require ("../htdocs/module_reviews/ICTNetworkTechnician.php");
                    $header = $this->getHeader($link, "Learner On Programme Review ICT Network Technician");
                    $evidence_matrix = ICTNetworkTechnician::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= ICTNetworkTechnician::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(398, 409)))
                {
                    require ("../htdocs/module_reviews/ICTSupportTechnician.php");
                    $header = $this->getHeader($link, "Learner On Programme Review ICT Support Technician");
                    $evidence_matrix = ICTSupportTechnician::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= ICTSupportTechnician::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(394)))
                {
                    require ("../htdocs/module_reviews/L4SoftwareDeveloperV2.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Level 4 Software Developer V2");
                    $evidence_matrix = L4SoftwareDeveloperV2::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= L4SoftwareDeveloperV2::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(407)))
                {
                    require ("../htdocs/module_reviews/L4SoftwareDeveloperV3.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Level 4 Software Developer V3");
                    $evidence_matrix = L4SoftwareDeveloperV3::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= L4SoftwareDeveloperV3::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(364)) and $tr_id != 30047)
                {
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    {
                        require ("../htdocs/module_reviews/ITInfrastructureTechnicianV6.php");
                        $header = $this->getHeader($link, "Learner On Programme Review IT Infrastructure Technician V6");
                        $evidence_matrix = ITInfrastructureTechnicianV6::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review,$course_id);
                        $evidence_matrix .= ITInfrastructureTechnicianV6::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                    else
                    {
                        require ("../htdocs/module_reviews/ITInfrastructureTechnicianV4.php");
                        $header = $this->getHeader($link, "Learner On Programme Review IT Infrastructure Technician V4");
                        //$part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                        $evidence_matrix = ITInfrastructureTechnicianV4::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $evidence_matrix .= ITInfrastructureTechnicianV4::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $functional_skills = $this->getFunctionalSkillsOld($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                    }
                }
                elseif(in_array($framework_id,Array(388)))
                {
                    require ("../htdocs/module_reviews/ITInfrastructureTechnicianV5.php");
                    $header = $this->getHeader($link, "Learner On Programme Review IT Infrastructure Technician V5");
                    $evidence_matrix = ITInfrastructureTechnicianV5::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= ITInfrastructureTechnicianV5::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(389)))
                {
                    require ("../htdocs/module_reviews/ITInfrastructureTechnicianV6.php");
                    $header = $this->getHeader($link, "Learner On Programme Review IT Infrastructure Technician V6");
                    $evidence_matrix = ITInfrastructureTechnicianV6::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review,$course_id);
                    $evidence_matrix .= ITInfrastructureTechnicianV6::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(383)))
                {
                    require ("../htdocs/module_reviews/L3LearningMentor.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Level 3 Learning Mentor");
                    $evidence_matrix = L3LearningMentor::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= L3LearningMentor::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(400)))
                {
                    require ("../htdocs/module_reviews/L4LearningMentor.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Level 4 Learning Mentor");
                    $evidence_matrix = L4LearningMentor::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= L4LearningMentor::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(378)))
                {
                    require ("../htdocs/module_reviews/DataTechnician.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Data Technician");
                    $evidence_matrix = DataTechnician::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(404)))
                {
                    require ("../htdocs/module_reviews/DataTechnicianV212.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Data Technician V2 12 Months");
                    $evidence_matrix = DataTechnicianV212::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(405)))
                {
                    require ("../htdocs/module_reviews/DataTechnicianV215.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Data Technician V2 15 Months");
                    $evidence_matrix = DataTechnicianV215::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(377)))
                {
                    require ("../htdocs/module_reviews/MarketingExecutive.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Marketing Executive");
                    $evidence_matrix = MarketingExecutive::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= MarketingExecutive::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(376)))
                {
                    require ("../htdocs/module_reviews/DataAnalystV2.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Data Analyst V2");
                    $evidence_matrix = DataAnalystV2::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= DataAnalystV2::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(395,408)))
                {
                    require ("../htdocs/module_reviews/DataAnalystV3.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Data Analyst V3");
                    $evidence_matrix = DataAnalystV3::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= DataAnalystV3::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(360)))
                {
                    if($learner_is_1920 && $tr_id != 30045 && $tr_id != 30578)
                    {
                        require ("../htdocs/module_reviews/ITInfrastructureTechnicianV4.php");
                        $header = $this->getHeader($link, "Learner On Programme Review IT Infrastructure Technician");
                        $part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                        //$skills_scan = $this->getSkillsScanITInfrastructureTechnician($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                        $evidence_matrix = ITInfrastructureTechnicianV4::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $evidence_matrix .= ITInfrastructureTechnicianV4::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    }
                    else
                    {
                        //include('./module_reviews/tpl_it_infrastructure_technician_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Infrastructure Technician V3");
                        $skills_scan = ReviewSkillsScans::getSkillsScanITInfrastructureTechnicianPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                }
                elseif(in_array($framework_id,Array(358)))
                {
                    if($learner_is_1920)
                        if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        {
                            require ("../htdocs/module_reviews/CyberSecurityRiskAnalyst.php");
                            $header = $this->getHeader($link, "Learner On Programme Review Cyber Security Risk Analyst");
                            $evidence_matrix = CybersecurityRiskAnalyst::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $evidence_matrix .= CybersecurityRiskAnalyst::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        }
                        else
                        {
                            require ("../htdocs/module_reviews/CyberSecurityRiskAnalyst.php");
                            $header = $this->getHeader($link, "Learner On Programme Review Cyber Security Risk Analyst");
                            $part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                            $evidence_matrix = CybersecurityRiskAnalyst::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $evidence_matrix .= CybersecurityRiskAnalyst::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                            $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        }
                }
                elseif(in_array($framework_id,Array(357)))
                {
                    if($learner_is_1920)
                    {
                        if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        {
                            require ("../htdocs/module_reviews/CyberSecurityRiskTechnologist2021.php");
                            $header = $this->getHeader($link, "Learner On Programme Review Cyber Security Technologist");
                            $evidence_matrix = CyberSecurityRiskTechnologist2021::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $evidence_matrix .= CyberSecurityRiskTechnologist2021::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        }
                        else
                        {
                            require ("../htdocs/module_reviews/CyberSecurityRiskTechnologist.php");
                            $header = $this->getHeader($link, "Learner On Programme Review Cyber Security Technologist");
                            $part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                            $evidence_matrix = CyberSecurityRiskTechnologist::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $evidence_matrix .= CyberSecurityRiskTechnologist::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                            $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        }
                    }
                    else
                    {
                        //include('./module_reviews/tpl_cyber_security_technologist_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Cyber Security Technologist");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                }
                elseif(in_array($framework_id,Array(336)))
                {
                    //include('./module_reviews/tpl_business_analyst.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Business Analyst");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(337)))
                {
                    //include('./module_reviews/tpl_data_analyst.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Data Analyst");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(359)))
                {
                    if($learner_is_1920)
                    {
                        require ("../htdocs/module_reviews/DigitalMarketingV3.php");
                        $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing V3");
                        $part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                        $evidence_matrix = DigitalMarketingV3::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $evidence_matrix .= DigitalMarketingV3::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                    else
                    {
                        //include('./module_reviews/tpl_digital_marketingv3_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing V3");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                }
                elseif(in_array($framework_id,Array(366)))
                {
                    require ("../htdocs/module_reviews/DigitalMarketingV4.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing V4");
                    $evidence_matrix = DigitalMarketingV4::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= DigitalMarketingV4::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(391)))
                {
                    require ("../htdocs/module_reviews/DigitalMarketingV5.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing V5");
                    $evidence_matrix = DigitalMarketingV5::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= DigitalMarketingV5::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(338)))
                {
                    //include('./module_reviews/tpl_digital_marketing.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(352)))
                {
                    //include('./module_reviews/tpl_digital_marketingv2.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Digital Marketing V2");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(344,345)) or $tr_id == 30047)
                {
                    //include('./module_reviews/tpl_it_infrastructure_technician_v2.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Infrastructure Technician V2");
                    $skills_scan = ReviewSkillsScans::getSkillsScanItInfrastructureTechnicianV2($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(331,343)))
                {
                    //include('./module_reviews/tpl_it_infrastructure_technician_m_prior.php');
                    $header = $this->getHeader($link, "Learner On Programme Review IT Infrastructure Technician Microsoft");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(305,362)))
                {
                    if($learner_is_1920 and $tr_id != 29527 and $tr_id!=29728 and $tr_id!=29524 and $tr_id!=30049)
                    {
                        if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        {
                            require ("../htdocs/module_reviews/ITProV2.php");
                            $header = $this->getHeader($link, "Learner On Programme Review IT Pro V2");
                            $evidence_matrix = ITProV2::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $evidence_matrix .= ITProV2::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        }
                        else
                        {
                            require ("../htdocs/module_reviews/ITProV2.php");
                            $header = $this->getHeader($link, "Learner On Programme Review IT Pro");
                            $part1 = $this->getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
                            $evidence_matrix = ITProV2::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $evidence_matrix .= ITProV2::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                            $learnerEmployer = ReviewSkillsScans::getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                            $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                        }
                    }
                    else
                    {
                        //include('./module_reviews/tpl_it_pro_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review IT Pro V2");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                }
                elseif(in_array($framework_id,Array(350)))
                {
                    //include('./module_reviews/tpl_it_prov1_prior.php');
                    $header = $this->getHeader($link, "Learner On Programme Review IT Pro V1");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(335)))
                {
                    //include('./module_reviews/tpl_tech_salesv1.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Technical Sales V1");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(351)))
                {
                    //include('./module_reviews/tpl_tech_salesv2.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Technical Sales V2");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(355)))
                {
                    //include('./module_reviews/tpl_unified_comm_technician.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Unified Communications Technician");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(361)))
                {
                    if($learner_is_1920)
                    {
                        //include('./module_reviews/tpl_unified_comm_technicianv2.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Unified Communications Technician V2");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                    else
                    {
                        //include('./module_reviews/tpl_unified_comm_technicianv2_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Unified Communications Technician V2");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                }
                elseif(in_array($framework_id,Array(365)))
                {
                    require ("../htdocs/module_reviews/UnifiedCommsTechnicianV3.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Unified Communications Technician V3");
                    $evidence_matrix = UnifiedCommsTechnicianV3::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= UnifiedCommsTechnicianV3::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(325,334)))
                {
                    //include('./module_reviews/tpl_software_tester.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Software Tester");
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(332,341)))
                {
                    //include('./module_reviews/tpl_network_engineer_prior.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Network Engineer");
                    $skills_scan = ReviewSkillsScans::getSkillsScanNetworkEngineerPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(354)))
                {
                    //include('./module_reviews/tpl_network_engineerv2_prior.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Network Engineer V2");
                    $skills_scan = ReviewSkillsScans::getSkillsScanNetworkEngineerV2Prior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                }
                elseif(in_array($framework_id,Array(333,342)))
                {
                    if($learner_is_1920 and !in_array($tr_id, Array(29585)))
                    {
                        require ("../htdocs/module_reviews/SoftwareDeveloper.php");
                        $header = $this->getHeader($link, "Learner On Programme Review Software Development");
                        $evidence_matrix = SoftwareDeveloper::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $evidence_matrix .= SoftwareDeveloper::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                    else
                    {
                        //include('./module_reviews/tpl_software_developer_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Software Development");
                        $skills_scan = ReviewSkillsScans::getSkillsScanSoftwareDeveloperPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                }
                elseif(in_array($framework_id,Array(394)))
                {
                    require ("../htdocs/module_reviews/SoftwareDeveloperV2.php");
                    $header = $this->getHeader($link, "Learner On Programme Review Software Development V2");
                    $evidence_matrix = SoftwareDeveloper::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $evidence_matrix .= SoftwareDeveloper::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(329)))
                {
                    if($learner_is_1920)
                    {
                        require ("../htdocs/module_reviews/SoftwareDevelopmentTechnician.php");
                        $header = $this->getHeader($link, "Learner On Programme Review Software Development Technician");
                        $evidence_matrix = SoftwareDevelopmentTechnician::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $evidence_matrix .= SoftwareDevelopmentTechnician::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                    }
                    else
                    {
                        //include('./module_reviews/tpl_software_development_technician_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Software Development Technician");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                }
                elseif(in_array($framework_id,Array(393)))
                {
                    //include('./module_reviews/tpl_software_development_technician.php');
                    $header = $this->getHeader($link, "Learner On Programme Review Software Development Technician V2");
                    $skills_scan = ReviewSkillsScans::getSkillsScanSoftwareDevelopmentTechnician($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events);
                    $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);
                }
                elseif(in_array($framework_id,Array(356)))
                {
                    if($learner_is_1920)
                    {
                        include('./module_reviews/UnifiedCommsTroubleshooter.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Unified Communications Troubleshooter");
                        $evidence_matrix = UnifiedCommsTroubleshooter::getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $evidence_matrix .= UnifiedCommsTroubleshooter::getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review);
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                    else
                    {
                        //include('./module_reviews/tpl_unified_comm_troubleshooter_prior.php');
                        $header = $this->getHeader($link, "Learner On Programme Review Unified Communications Troubleshooter");
                        $functional_skills = $this->getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts);

                    }
                }
                else
                {
                    /*$url_string = '&tr_id=' . $tr_id;
                    $url_string .= '&meeting_date=' . $meeting_date;
                    $url_string .= '&source=' . $source;
                    $url_string .= '&review_id=' . $review_id;
                    $url_string .= '&key=' . $key;
                    $url_string .= '&output=' . $output;

                    http_redirect('do.php?_action=assessor_review_formv2'.$url_string);*/
                    $header = "";
                }
            }
            elseif($template_review==3)
            {
                //include('tpl_arf_gateway2.php');
                $header = $this->getHeader($link, "Gateway Review - General");
            }
            elseif($template_review==4)
            {
                //include('tpl_arf_gateway.php');
                $header = $this->getHeader($link, "Gateway Review - Preparation for Interview");
            }
            else
                pre("Configuration Required");


            $html =  $header;
            $html .= $learner_details;
            if($part1=="")
                $part1 = $this->getOnProgPart1($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage);
            $html .= $part1;
            $html .= $skills_scan;
            $html .= $evidence_matrix;
            $html .= $functional_skills;
            $html .= $smart_actions;
            $html .= $next_contact;
            if($template_review!=1)
                $html .= $learnerEmployer;
            $html .= $signatures;
            //$html .= $this->getProgrammeProgressPDF($form_arf, $source, $link, $tr_id);

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $stylesheet = file_get_contents('./MPDF57/examples/baltic.css');
            $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $form_assessor1->review_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }

        if($template_review==1)
        {
            $meeting_date = $form_arf->review_date;
            if($meeting_date=="" or strtotime(Date::toMySQL($meeting_date))>strtotime("2020-09-01"))
                include('tpl_arf_introduction_v2.php');
            else
                include('tpl_arf_introduction.php');
        }
        elseif($template_review==2)
        {
            if(in_array($framework_id,Array(371)))
            {
                //include('./module_reviews/tpl_digital_marketing_l4.php');
                include('./module_reviews/tpl_digital_marketing_l4v2.php');
            }
            elseif(in_array($framework_id,Array(364)) and $tr_id != 30047)
            {
                if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    include('./module_reviews/tpl_it_infrastructure_technician_v4_2021.php');
                else
                    include('./module_reviews/tpl_it_infrastructure_technician_v4.php');
            }
            elseif(in_array($framework_id,Array(386)))
            {
                include('./module_reviews/tpl_network_engineer_v3.php');
            }
            elseif(in_array($framework_id,Array(388)))
            {
                include('./module_reviews/tpl_it_infrastructure_technician_v5.php');
            }
            elseif(in_array($framework_id,Array(389)))
            {
                include('./module_reviews/tpl_it_infrastructure_technician_v6.php');
            }
            elseif(in_array($framework_id,Array(383)))
            {
                include('./module_reviews/tpl_l3_learning_mentor.php');
            }
            elseif(in_array($framework_id,Array(400)))
            {
                include('./module_reviews/tpl_l4_learning_mentor.php');
            }
            elseif(in_array($framework_id,Array(397,410)))
            {
                include('./module_reviews/tpl_ict_network_technician.php');
            }
            elseif(in_array($framework_id,Array(398, 409)))
            {
                include('./module_reviews/tpl_ict_support_technician.php');
            }
            elseif(in_array($framework_id,Array(360)))
            {
                if($learner_is_1920 && $tr_id != 30045 && $tr_id != 30578)
                    include('./module_reviews/tpl_it_infrastructure_technician.php');
                else
                    include('./module_reviews/tpl_it_infrastructure_technician_prior.php');
            }
            elseif(in_array($framework_id,Array(358)))
            {
                if($learner_is_1920)
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        include('./module_reviews/tpl_cyber_security_risk_analyst_2021.php');
                    else
                        include('./module_reviews/tpl_cyber_security_risk_analyst.php');
                else
                    include('./module_reviews/tpl_cyber_security_risk_analyst_prior.php');
            }
            elseif(in_array($framework_id,Array(357)))
            {
                if($learner_is_1920)
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        include('./module_reviews/tpl_cyber_security_technologist_2021.php');
                    else
                        include('./module_reviews/tpl_cyber_security_technologist.php');
                else
                    include('./module_reviews/tpl_cyber_security_technologist_prior.php');
            }
            elseif(in_array($framework_id,Array(336)))
            {
                include('./module_reviews/tpl_business_analyst.php');
            }
            elseif(in_array($framework_id,Array(337)))
            {
                include('./module_reviews/tpl_data_analyst.php');
            }
            elseif(in_array($framework_id,Array(359)))
            {
                if($learner_is_1920)
                    include('./module_reviews/tpl_digital_marketingv3.php');
                else
                    include('./module_reviews/tpl_digital_marketingv3_prior.php');
            }
            elseif(in_array($framework_id,Array(366)))
            {
                if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    include('./module_reviews/tpl_digital_marketingv4_2021.php');
                else
                    include('./module_reviews/tpl_digital_marketingv4.php');
            }
            elseif(in_array($framework_id,Array(391)))
            {
                include('./module_reviews/tpl_digital_marketing_v5.php');
            }
            elseif(in_array($framework_id,Array(338)))
            {
                include('./module_reviews/tpl_digital_marketing.php');
            }
            elseif(in_array($framework_id,Array(352)))
            {
                include('./module_reviews/tpl_digital_marketingv2.php');
            }
            elseif(in_array($framework_id,Array(344,345)) or $tr_id == 30047)
            {
                include('./module_reviews/tpl_it_infrastructure_technician_v2.php');
            }
            elseif(in_array($framework_id,Array(331,343)))
            {
                include('./module_reviews/tpl_it_infrastructure_technician_m_prior.php');
            }
            elseif(in_array($framework_id,Array(305,362)))
            {
                if($learner_is_1920 and $tr_id != 29527 and $tr_id != 29673 and $tr_id!=29728 and $tr_id!=29524 and $tr_id!=30049)
                {
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        include('./module_reviews/tpl_it_pro_2021.php');
                    else
                        include('./module_reviews/tpl_it_pro.php');
                }
                else
                {
                    include('./module_reviews/tpl_it_pro_prior.php');
                }
            }
            elseif(in_array($framework_id,Array(350)))
            {
                include('./module_reviews/tpl_it_prov1_prior.php');
            }
            elseif(in_array($framework_id,Array(335)))
            {
                include('./module_reviews/tpl_tech_salesv1.php');
            }
            elseif(in_array($framework_id,Array(351)))
            {
                include('./module_reviews/tpl_tech_salesv2.php');
            }
            elseif(in_array($framework_id,Array(355)))
            {
                include('./module_reviews/tpl_unified_comm_technician.php');
            }
            elseif(in_array($framework_id,Array(361)))
            {
                if($learner_is_1920)
                    include('./module_reviews/tpl_unified_comm_technicianv2.php');
                else
                    include('./module_reviews/tpl_unified_comm_technicianv2_prior.php');
            }
            elseif(in_array($framework_id,Array(365)))
            {
                if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    include('./module_reviews/tpl_unified_comm_technicianv3_2021.php');
                else
                    include('./module_reviews/tpl_unified_comm_technicianv3.php');
            }
            elseif(in_array($framework_id,Array(325,334)))
            {
                include('./module_reviews/tpl_software_tester.php');
            }
            elseif(in_array($framework_id,Array(332,341)))
            {
                include('./module_reviews/tpl_network_engineer_prior.php');
            }
            elseif(in_array($framework_id,Array(354)))
            {
                include('./module_reviews/tpl_network_engineerv2_prior.php');
            }
            elseif(in_array($framework_id,Array(333,342)))
            {
                if($learner_is_1920 and !in_array($tr_id, Array(29585)))
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        include('./module_reviews/tpl_software_developer_2021.php');
                    else
                        include('./module_reviews/tpl_software_developer.php');
                else
                    include('./module_reviews/tpl_software_developer_prior.php');
            }
            elseif(in_array($framework_id,Array(394)))
            {
                include('./module_reviews/tpl_software_developer_v2.php');
            }
            elseif(in_array($framework_id,Array(407)))
            {
                include('./module_reviews/tpl_software_developer_v3.php');
            }
            elseif(in_array($framework_id,Array(329)))
            {
                if($learner_is_1920)
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        include('./module_reviews/tpl_software_development_technician_2021.php');
                    else
                        include('./module_reviews/tpl_software_development_technician.php');
                else
                    include('./module_reviews/tpl_software_development_technician_prior.php');
            }
            elseif(in_array($framework_id,Array(393)))
            {
                if($learner_is_1920)
                     include('./module_reviews/tpl_software_development_technician_v2.php');
            }
            elseif(in_array($framework_id,Array(356)))
            {
                if($learner_is_1920)
                    if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                        include('./module_reviews/tpl_unified_comm_troubleshooter_2021.php');
                    else
                        include('./module_reviews/tpl_unified_comm_troubleshooter.php');
                else
                    include('./module_reviews/tpl_unified_comm_troubleshooter_prior.php');
            }
            elseif(in_array($framework_id,Array(376)))
            {
                if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    include('./module_reviews/tpl_data_analystv2_2021.php');
                else
                    include('./module_reviews/tpl_data_analystv2.php');
            }
            elseif(in_array($framework_id,Array(395,408)))
            {
                include('./module_reviews/tpl_data_analystv3.php');
            }
            elseif(in_array($framework_id,Array(377)))
            {
                if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    include('./module_reviews/tpl_marketing_exec_2021.php');
                else
                    include('./module_reviews/tpl_marketing_exec.php');
            }
            elseif(in_array($framework_id,Array(378)))
            {
                if($form_arf->review_date=="" or strtotime($form_arf->review_date)>strtotime("2021-01-29"))
                    include('./module_reviews/tpl_data_technician_2021.php');
                else
                    include('./module_reviews/tpl_data_technician.php');
            }
            elseif(in_array($framework_id,Array(404)))
            {
                include('./module_reviews/tpl_data_technician_v212.php');
            }
            elseif(in_array($framework_id,Array(405)))
            {
                include('./module_reviews/tpl_data_technician_v215.php');
            }
            else
            {
                $url_string = '&tr_id=' . $tr_id;
                $url_string .= '&meeting_date=' . $meeting_date;
                $url_string .= '&source=' . $source;
                $url_string .= '&review_id=' . $review_id;
                $url_string .= '&key=' . $key;
                $url_string .= '&output=' . $output;

                http_redirect('do.php?_action=assessor_review_formv2'.$url_string);
            }
        }
        elseif($template_review==3)
        {
            include('tpl_arf_gateway2.php');
        }
        elseif($template_review==4)
        {
            include('tpl_arf_gateway.php');
        }
        else
            pre("Configuration Required");

    }

    private function updateSkillsScan($form_arf, $previous_form)
    {
        if(isset($previous_form))
        {
            for($a = 1; $a<=42; $a++)
            {
                $x = 'skills_scan_status'.$a;
                if($previous_form->{$x}=="F")
                    $form_arf->{$x} = "F";
            }
        }
        return $form_arf;
    }

    // Have to sort this out
    private function save_signatures($link, $form,$tr_id,$review_id)
    {
        $isNewReview = DAO::getSingleValue($link, "select count(*) from arf_introduction where review_id = '$review_id'");
        if($isNewReview == 1)
        {
            $form_arf = ARFIntroduction::loadFromDatabase($link, $review_id);
            $signature_learner_font = explode("&",$form_arf->signature_learner_font);
            $signature_assessor_font = explode("&",$form_arf->signature_assessor_font);
            $signature_employer_font = explode("&",$form_arf->signature_employer_font);
        }
        else
        {
            $form_learner = AssessorReviewFormLearner::loadFromDatabase($link,$review_id);
            $form_assessor1 = AssessorReviewFormAssessor1::loadFromDatabase($link,$review_id);
            $form_assessor2 = AssessorReviewFormAssessor2::loadFromDatabase($link,$review_id);
            $form_assessor3 = AssessorReviewFormAssessor3::loadFromDatabase($link,$review_id);
            $form_assessor4 = AssessorReviewFormAssessor4::loadFromDatabase($link,$review_id);
            $form_employer = AssessorReviewFormEmployer::loadFromDatabase($link,$review_id);
            $signature_learner_font = explode("&",$form_learner->signature_learner_font);
            $signature_assessor_font = explode("&",$form_assessor4->signature_assessor_font);
            $signature_employer_font = explode("&",$form_employer->signature_employer_font);
        }
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $username = $tr->username;
        $db=DB_NAME;
        if(!file_exists(DATA_ROOT."/uploads/$db"))
        {
            mkdir(DATA_ROOT."/uploads/$db");
        }
        if(!file_exists(DATA_ROOT."/uploads/$db/$username"))
        {
            mkdir(DATA_ROOT."/uploads/$db/$username");
        }
        if(!file_exists(DATA_ROOT."/uploads/$db/$username/signatures"))
        {
            mkdir(DATA_ROOT."/uploads/$db/$username/signatures");
        }
        if(!file_exists(DATA_ROOT."/uploads/$db/$username/signatures/$review_id"))
        {
            mkdir(DATA_ROOT."/uploads/$db/$username/signatures/$review_id");
        }

        $size = substr($signature_learner_font[3],strpos($signature_learner_font[3],"=")+1);
        $font = substr($signature_learner_font[2],strpos($signature_learner_font[2],"=")+1);
        $text = str_replace("%20"," ",substr($signature_learner_font[1],strpos($signature_learner_font[1],"=")+1));
        $im = imagecreatetruecolor(285,49);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 285, 49, $white);
        Imagettftext($im, $size, 0, 25, 35, $black, ("./fonts/".$font), $text);
        $target_directory = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/learner_signature.png";
        imagepng($im,$target_directory,0,NULL);

        $size = substr($signature_assessor_font[3],strpos($signature_assessor_font[3],"=")+1);
        $font = substr($signature_assessor_font[2],strpos($signature_assessor_font[2],"=")+1);
        $text = str_replace("%20"," ",substr($signature_assessor_font[1],strpos($signature_assessor_font[1],"=")+1));
        $im = imagecreatetruecolor(285,49);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 285, 49, $white);
        Imagettftext($im, $size, 0, 25, 35, $black, ("./fonts/".$font), $text);
        $target_directory = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/assessor_signature.png";
        imagepng($im,$target_directory,0,NULL);

        $size = substr($signature_employer_font[3],strpos($signature_employer_font[3],"=")+1);
        $font = substr($signature_employer_font[2],strpos($signature_employer_font[2],"=")+1);
        $text = str_replace("%20"," ",substr($signature_employer_font[1],strpos($signature_employer_font[1],"=")+1));
        $im = imagecreatetruecolor(285,49);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 285, 49, $white);
        Imagettftext($im, $size, 0, 25, 35, $black, ("./fonts/".$font), $text);
        $target_directory = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/employer_signature.png";
        imagepng($im,$target_directory,0,NULL);

    }

    public function isAssessmentComplete($Assessment_Plan, $plans_array)
    {
        $assessmentComplete = true;
        foreach($plans_array as $plan)
        {
            if(!isset($Assessment_Plan[$plan]))
            {
                $assessmentComplete = false;
                break;
            }
            elseif(isset($Assessment_Plan[$plan]) and $Assessment_Plan[$plan]!="Complete")
            {
                $assessmentComplete = false;
                break;
            }
        }
        $assessmentComplete = ($assessmentComplete)?"Complete":"";
        return $assessmentComplete;
    }

    public function getEventStatus($statuses, $event)
    {
        $res = "";
        foreach($statuses as $status)
        {
            if(isset($status['unit_ref']) and strtoupper($status['unit_ref'])==strtoupper($event))
            {
                $res = $status['code'];
                break;
            }
        }
        return $res;
    }

    public function getEventDate($statuses, $event)
    {
        $res = "";
        foreach($statuses as $status)
        {
            if(isset($status['unit_ref']) and strtoupper($status['unit_ref'])==strtoupper($event))
            {
                $res = Date::toShort($status['date']);
                break;
            }
        }
        return $res;
    }

    public function getTechnicalProgress($link, $tr_id)
    {
        $sqltp = <<<SQL
SELECT DISTINCT
  tr.id AS tr_id,
  op_trackers.`id` AS programme_id,
  frameworks.short_name,
  (SELECT COUNT(*) FROM op_course_percentage WHERE programme = frameworks.short_name) AS percentage_set,
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date
FROM
  tr
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN frameworks ON student_frameworks.`id` = frameworks.`id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE tr.id = $tr_id;
SQL;
        $st = DAO::query($link, $sqltp);
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $class = '';
                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test"');
                if($row['programme_id'] == '9' || $row['programme_id'] == '18')
                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test"');
                else
                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test"');
                $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                if(DB_NAME=='am_baltic_demo' || DB_NAME=='am_baltic')
                    $current_training_month = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(WEEK, '".Date::toMySQL($row['start_date'])."', CURDATE());");
                else
                    $current_training_month = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '".Date::toMySQL($row['start_date'])."', CURDATE());");

                if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
                {
                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                    $class = "bg-green";
                    if($current_training_month > $max_month_value && $course_percentage < 100)
                    {
                        $class = "bg-red";
                    }
                    else
                    {
                        $op_course_progress_lookup = DAO::getObject($link, "SELECT op_course_percentage.* FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                        if($course_percentage >= $op_course_progress_lookup->min_percentage)
                            $class = "bg-green";
                        else
                            $class = "bg-red";
                    }
                }
                if($course_percentage >= 100 || $current_training_month == 0)
                    $class = "bg-green";

                return $total_units != 0 ? $passed_units . '/' . $total_units . ' = ' . $course_percentage  . '%': 'N/A';
            }
        }
    }

    public function getExamProgress($link, $tr_id)
    {
        $sqltp = <<<SQL
SELECT DISTINCT
  tr.id AS tr_id,
  op_trackers.`id` AS programme_id,
  frameworks.short_name,
  (SELECT COUNT(*) FROM op_test_percentage WHERE programme = frameworks.short_name) AS test_percentage_set,
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date
FROM
  tr
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN frameworks ON student_frameworks.`id` = frameworks.`id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE tr.id = $tr_id;
SQL;
        $st = DAO::query($link, $sqltp);
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $class = '';
                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref LIKE "% Test"');
                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test"');
                $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                if(DB_NAME=='am_baltic_demo' || DB_NAME=='am_baltic')
                    $current_training_month = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(WEEK, '".Date::toMySQL($row['start_date'])."', CURDATE());");
                else
                    $current_training_month = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '".Date::toMySQL($row['start_date'])."', CURDATE());");

                if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
                {
                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                    $class = "bg-green";
                    if($current_training_month > $max_month_value && $test_percentage < 100)
                    {
                        $class = "bg-red";
                    }
                    else
                    {
                        $op_test_progress_lookup = DAO::getObject($link, "SELECT op_test_percentage.* FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                        if($test_percentage >= $op_test_progress_lookup->min_percentage)
                            $class = "bg-green";
                        else
                            $class = "bg-red";
                    }
                }
                if($test_percentage >= 100 || $current_training_month == 0)
                    $class = "bg-green";

                return $total_units != 0 ? $passed_units . '/' . $total_units . ' = ' . $test_percentage  . '%': 'N/A';
            }
        }
    }


    public function getLearnerDetails($form_arf, $source)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Details</th>
        </thead>
        <tbody>
        <tr>
            <td>Learner:</td>
            <td><input type=\"text\" name=\"learner_name\" value='" . $form_arf->learner_name . "' size=30/></td>
            <td>Reviewer/ Assessor:</td>
            <td><input type=\"text\" name=\"learner_assessor\" value='" . $form_arf->learner_assessor . "' size=30/></td>
        </tr>
        <tr>
            <td>Employer Name:</td>
            <td><input type=\"text\" name=\"learner_employer\" value='" . $form_arf->learner_employer . "' size=30/></td>
            <td>Line Manager/ Supervisor Name:</td>
            <td><input type=\"text\" name=\"learner_manager\" value='" . $form_arf->learner_manager . "' size=30/></td>
        </tr>
        <tr>
            <td>Programme:</td>
            <td><input type=\"text\" name=\"learner_programme\" value='" . $form_arf->learner_programme . "' size=30/></td>
            <td>Programme Start Date:</td>
            <td>" . HTML::datebox("start_date", $form_arf->start_date, true, false) . "</td>
        </tr>
        <tr>
            <td>Expected Completion Date:</td>
            <td>" . HTML::datebox("planned_end_date", $form_arf->planned_end_date, true, false) . "</td>
            <td>Actual Review Date:</td>
            <td>";
            if($source==1)
                $html .= HTML::datebox("review_date", $form_arf->review_date, true, false);
            else
                $html .= $form_arf->review_date;
         $html .=   "</td>
        </tr>
        </tbody>
    </table>
    <br>";
    return $html;
    }

    public function getProgrammeProgress($form_arf, $source, $link, $tr_id, $assessment_percentage, $technical_percentage, $exam_percentage)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Programme Progress</th>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Percentage Progress</td>
            </tr>
            <tr>
                <td colspan=4>
                    <table>
                        <tr>
                            <td style=\"text-align: center\">Assessment Plans</td>
                            <td style=\"text-align: center\">Knowledge - Training</td>
                            <td style=\"text-align: center\">Knowledge - Exams</td>
                        </tr>
                        <tr>
                            <td style=\"text-align: center\">";
                                $html .= $assessment_percentage . "%
                            </td>
                            <td style=\"text-align: center\"><i>" .
                                    $technical_percentage .
                            "</i></td>
                            <td style=\"text-align: center\"><i>"
                                 . $exam_percentage . "
                            </i></td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <br>";
        return $html;
    }

    public function getHeaderITInfrastructureTechnician($link)
    {
          $html = "<table style=\"width: 900px\">
            <tr>
                <td>
                    <table class=\"table1\">
                        <thead>
                        <th style=\"width: 800px\">&nbsp;&nbsp;&nbsp;Learner On Programme Review IT Infrastructure Technician</th>
                        </thead>
                    </table>
                </td>
                <td>";
                    if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                        $html .= '<img height = "100" width = "80" src="images/logos/' . SystemConfig::getEntityValue($link, "logo") . '">';
                    else
                        $html .= '<img height = "100" width = "80" src="images/sunesislogo.gif">';
                $html .= "</td>
            </tr>
        </table>
        <br>";
        return $html;
    }

    public function getLearnerDetailsPDF($form_arf, $source)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Details</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Learner:</td>
            <td>" . $form_arf->learner_name . "</td>
            <td>Reviewer/ Assessor:</td>
            <td>" . $form_arf->learner_assessor . "</td>
        </tr>
        <tr>
            <td>Employer Name:</td>
            <td>" . $form_arf->learner_employer . "</td>
            <td>Line Manager/ Supervisor Name:</td>
            <td>" . $form_arf->learner_manager . "</td>
        </tr>
        <tr>
            <td>Programme:</td>
            <td>" . $form_arf->learner_programme . "</td>
            <td>Programme Start Date:</td>
            <td>" . Date::toMedium($form_arf->start_date) . "</td>
        </tr>
        <tr>
            <td>Expected Completion Date:</td>
            <td>" . Date::toMedium($form_arf->planned_end_date) . "</td>
            <td>Actual Review Date:</td>
            <td>";
        if($source==1)
            $html .= Date::toMedium($form_arf->review_date);
        else
            $html .= Date::toMedium($form_arf->review_date);
        $html .=   "</td>
        </tr>
        </tbody>
    </table>
    <br>";
        return $html;
    }

    public function getProgrammeProgressPDF($form_arf, $source, $link, $tr_id, $assessment_percentage, $technical_percentage, $exam_percentage)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Programme Progress</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Percentage Progress</td>
            </tr>
            <tr>
                <td colspan=4>
                    <table>
                        <tr>
                            <td style=\"text-align: center\">Assessment Plans</td>
                            <td style=\"text-align: center\">Knowledge - Training</td>
                            <td style=\"text-align: center\">Knowledge - Exams</td>
                        </tr>
                        <tr>
                            <td style=\"text-align: center\">";
        $html .= $assessment_percentage . "%
                            </td>
                            <td style=\"text-align: center\"><i>" .
            $technical_percentage .
            "</i></td>
                            <td style=\"text-align: center\"><i>"
            . $exam_percentage . "
                            </i></td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <br>";
        return $html;
    }

    public function getHeader($link, $content)
    {
        $html = "<table style=\"width: 900px\">
            <tr>
                <td>
                    <table class=\"table1\">
                        <thead>
                        <tr>
                        <th style=\"width: 800px\">&nbsp;&nbsp;&nbsp;" . $content . "</th>
                        </tr>
                        </thead>
                    </table>
                </td>
                <td>";
        if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            $html .= '<img height = "100" width = "80" src="images/logos/' . SystemConfig::getEntityValue($link, "logo") . '">';
        else
            $html .= '<img height = "100" width = "80" src="images/sunesislogo.gif">';
        $html .= "</td>
            </tr>
        </table>
        <br>";
        return $html;
    }

    public function getIntroPart1($link, $form_arf, $review_id, $tr_id, $source)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Introduction</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Introduction to who you are, your role and the requirements of the apprenticeship</td>
            </tr>
            <tr>
                <td colspan=4><i>" . $form_arf->introduction .
                "</i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Skill Scan</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Review of skill scan completed at Recruitment stage and how it will link to progress in monthly reviews</td>
            </tr>
            <tr>
                <td colspan=4><i>" .
                    $form_arf->skill_scan .
                "</i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Review Overview</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Overview of reviews - timeframe, content and importance of manager attendance and return and employer reference discussion.<br><br>Employer reference process and contribution discussed.</td>
            </tr>
            <tr>
                <td colspan=4><i>
                     " . $form_arf->progress_review . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Technical Training</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Discuss technical training courses and links to coordinator</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->technical_training . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Assessment</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Discuss assessment activity, assessment matrix and the importance of each milestone</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->assessment . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Functional Skills</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Explain the role of the FS Team, check and remind of the need to send exemption certs if applicable and schedule FS activity if applicable</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->functional_skills . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;End Point Assessment</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Discuss briefly what the EPA is, how it works and how it links to the assessment matrix</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->end_point_assessment . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;SkilSure</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Provide SkilSure link and learner login details</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->skilsure . "
                </i></td>
            </tr>
            <tr>
                <td colspan=4>Screen share to demonstrate key features, key screens and how to: accept assessment plans, upload first submissions & rework and complete reflective account.</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->skilsure2 . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Setting Work</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Set the first assessment plan, providing any necessary advice and guidance</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->setting_work . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Concerns</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & Safety, Health & Wellbeing issues.</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->learner_concerns . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Are there any issues or anything you would like to disclose which could prevent you completing your 12 month apprenticeship?</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->apprenticeship_commitment . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Off the job training in the workplace</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Record here training and learning that has taken place at work, this must include a date and duration. This will include job shadowing, mentoring by a supervisor, project work (Learning Logs/Learning Diary can support these statements). Provide instruction to complete learning log on Skilsure following the session and set a SMART objective.</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->otj . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>";

        return $html;
    }

    public function getIntroPart1V2($link, $form_arf, $review_id, $tr_id, $source)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Concerns</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & Safety, Health & Wellbeing issues.</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->learner_concerns . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Are there any issues or anything you would like to disclose which could prevent you completing your 12 month apprenticeship?</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->apprenticeship_commitment . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Any Other Business</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->learner_comment . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>";

        return $html;
    }

    public function getSmartActions($form_arf)
    {
            $html = "<table class=\"table1\" style=\"width: 900px\">
                <thead>
                <tr>
                <th colspan=4>&nbsp;&nbsp;&nbsp;Actions Required for next contact</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan=4>(SMART - Exactly what you will do, how you will know it is complete, how is it realistic for you to achieve, when will you achieve it by?)</td>
                </tr>
                <tr>
                    <td style=\"text-align: center\">Specific</td>
                    <td style=\"text-align: center\">Measurable</td>
                    <td style=\"text-align: center\">Achievable & Realistic</td>
                    <td style=\"text-align: center\">Timebound</td>
                </tr>
                <tr>
                    <td colspan=4>
                        <table>
                            <tr>
                                <td><i>
                            " . $form_arf->specific . "
                                </i></td>
                            </tr>
                            <tr>
                                <td><i>
                                    " . $form_arf->measurable . "
                                </i></td>
                            </tr>
                            <tr>
                                <td><i>
                                    " . $form_arf->achievable . "
                                </i></td>
                            </tr>
                            <tr>
                                <td><i>
                                    " . $form_arf->timebound . "
                                </i></td>
                            </tr>
                            <tr>
                                <td><i>
                                    " . $form_arf->smart_line5 . "
                                </i></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>";
        return $html;
    }

    public function getNextContact($form_arf)
    {
         return "";
         $html = "<table class=\"table1\" style=\"width: 900px\">
            <tbody><tr>
            <td colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </td>
            <td style=\"text-align: center\">
                " . Date::toMedium($form_arf->next_contact) . "
            </td>
            <td>&nbsp;&nbsp;&nbsp;Hours: </td>
            <td style=\"text-align: center\">
                " . $form_arf->hours . "
            </td>
            <td>&nbsp;&nbsp;&nbsp;Minutes: </td>
            <td style=\"text-align: center\">
                " . $form_arf->minutes . "
            </td>
            </tr></tbody>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th>&nbsp;&nbsp;&nbsp;Adobe Link: </th>
            </tr>
            <th style=\"text-align: center\">
                " . $form_arf->adobe . "
            </th>
            </thead>
        </table>
        <br>";
        return $html;
    }


    public function getSignatures($form_arf, $assessor, $learner, $employer)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th>&nbsp;</th>
            <th>Signature</th>
            <th>Name</th>
            <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>Learner</td>";
            $html .= '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "' . $learner .  '" height="49" width="285"/></div></td>';
            $html .= "<td style=\"text-align: center\">" . $form_arf->signature_learner_name . "</td>
            <td style=\"text-align: center\">" . Date::toMedium($form_arf->signature_learner_date) . "</td>
            </tr>
            <tr>
            <td>Assessor</td>";
            $html .= '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "' . $assessor .  '" height="49" width="285"/></div></td>';
            $html .= "<td style=\"text-align: center\">" . $form_arf->signature_assessor_name . "</td>
            <td style=\"text-align: center\">" . Date::toMedium($form_arf->signature_assessor_date) . "</td>
            </tr>
            <tr>
            <td>Employer</td>";
            $html .= '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "' . $employer .  '" height="49" width="285"/></div></td>';
            $html .= "<td style=\"text-align: center\">" . $form_arf->signature_employer_name . "</td>
            <td style=\"text-align: center\">" . Date::toMedium($form_arf->signature_employer_date) . "</td>
            </tr>
            </tbody>
        </table>
        <br>";
        return $html;
    }

    public function getOnProgPart1Old($form_arf, $review_id, $tr_id, $link, $previous_review,$assessment_percentage, $technical_percentage, $exam_percentage)
    {

        $html = $this->getProgrammeProgressPDF($form_arf, 1, $link, $tr_id,$assessment_percentage, $technical_percentage, $exam_percentage);
        $html .= "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Review of previous SMART targets</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan=3>SMART</td><td style=\"text-align: center;\">Met (Y/N)</td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->specific)?$previous_review->specific:'';
        $html .= "</i></td>
        <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart1_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->measurable)?$previous_review->measurable:'';
        $html .= "</i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart2_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->achievable)?$previous_review->achievable:'';
        $html .= "</i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart3_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->timebound)?$previous_review->timebound:'';
        $html .= "</i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart4_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->smart_line5)?$previous_review->smart_line5:'';
        $html .= "
            </i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart5_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        </tbody>
    </table>
    <br>";
$html.="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Personal Development Progress</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Review Employer Comments</b> <br>Discuss employer comments from previous review</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->introduction . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Significant Achievement over past 4 weeks</b><br>Learner to identify a personal achievement - for example a piece of work, team contribution, Apprentice of the month or learner of the week nomination.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->skill_scan . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Equality and Diversity</b><br>Review learner understanding of Equality and Diversity, QCF Appeals procedure and bullying and harassment. Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->progress_review . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Safeguarding including E-Safety</b><br>Explore learner understanding of Safeguarding. Discuss with learner whether they feel safe at work. Discuss with learners their understanding of e-safety, privacy setting, the negative aspects of social media. Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->technical_training . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Prevent, Radicalisation and Extremism</b><br>Explore with learners their understanding of Prevent, Radicalisation and Extremism and British values. Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->assessment . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Health and Wellbeing</b><br>Raise awareness of anxiety & Depression. Discuss with the learner topics such as diet and exercise, factors that affect their health, i.e drugs, alcohol and smoking.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->functional_skills . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Learner Concerns</b><br>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & Safety, Health & Wellbeing issues.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->end_point_assessment . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Apprenticeship Commitment</b><br>Are there any issues or anything you would like to disclose which could prevent you completing your 12 month apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->skilsure . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Additional Support Requirements</b><br>Is there any additional support you would like from Baltic Training or your Line Manager</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->skilsure2 . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Learner Progress at Placement / Employment</b><br>Discuss both positive and development areas.  Comment on attendance, time keeping, attitude and ability including new skills developed.  Identify new skills and experience that have been learnt and applied at work.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->setting_work . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Off the job training in the workplace</b><br>Record here training and learning that has taken place at work.  This will include job shadowing, mentoring by a supervisor, project work. (Learning Logs/Learning Diary can support these statements).</td>
    </tr>
    <tr>
        <td style=\"text-align: center\">Previous:</td>
        <td style=\"text-align: center\">Current:</td>
        <td style=\"text-align: center\">Other:</td>
    </tr>
    <tr>
        <td style=\"text-align: center;\">" . DAO::getSingleValue($link, "SELECT SUM(hours) FROM assessor_review WHERE tr_id = '$tr_id'") . "</td>
        <td style=\"text-align: center\">" . $form_arf->current_hours . "</td>
        <td style=\"text-align: center;\">" . DAO::getSingleValue($link, "SELECT (SELECT COALESCE(SUM(HOUR(TIMEDIFF(time_to, time_from))),0)  FROM additional_support WHERE tr_id = '$tr_id')+
(SELECT
                      COALESCE(SUM(HOUR(TIMEDIFF(start_time,end_time))),0)
                    FROM
                      session_attendance
                      INNER JOIN session_entries
                        ON session_attendance.`session_entry_id` = session_entries.`entry_id`
                      INNER JOIN sessions
                        ON session_entries.`entry_session_id` = sessions.`id`
                    WHERE sessions.`event_type` = 'SUP' AND session_attendance.`attendance_code` = 1
                    AND session_entries.`entry_tr_id` = '$tr_id');
") . "</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->learner_concerns . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";
    return $html;

    }
    public function getOnProgPart1($form_arf, $review_id, $tr_id, $link, $previous_review, $assessment_percentage, $technical_percentage, $exam_percentage)
    {
        $html = $this->getProgrammeProgressPDF($form_arf, 1, $link, $tr_id, $assessment_percentage, $technical_percentage, $exam_percentage);

        $html .= "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Review of previous SMART targets</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan=3>SMART</td><td style=\"text-align: center;\">Met (Y/N)</td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->specific)?$previous_review->specific:'';
        $html .= "</i></td>
        <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart1_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->measurable)?$previous_review->measurable:'';
        $html .= "</i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart2_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->achievable)?$previous_review->achievable:'';
        $html .= "</i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart3_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->timebound)?$previous_review->timebound:'';
        $html .= "</i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart4_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        <tr>
            <td colspan=3><i>";
        $html .= isset($previous_review->smart_line5)?$previous_review->smart_line5:'';
        $html .= "
            </i></td>
            <td style=\"text-align: center;\">";
        $checked = ($form_arf->smart5_achieved=="on")?" checked ":"";
        $html .= "<input type = \"checkbox\" checked=\"$checked\" ></td>
        </tr>
        </tbody>
    </table>
    <br>";

    $html .= "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Personal Development Progress</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Review Employer Comments</b></td>
    </tr>
    <tr>
        <td colspan=2>Did the employer attend the review session?</td>";
        $issues = Array(Array('1','Yes'),Array('2','No'));
    $html.= "<td colspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("manager_attendance", $issues, $form_arf->manager_attendance, true, true) . "</td>
    </tr>
    <tr>
        <td colspan=4><i>
        " . $form_arf->introduction . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Workplace progress and significant achievement over past 12 weeks</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->skill_scan . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Apprenticeship Commitment / Future Planning / Goal Setting / EPA Readiness Check</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->progress_review . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";


        $html .= "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Personal Development Topics</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>E-safety & Digital Resilience</b><br>Discuss progress and topics that have been set and learnt</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->technical_training . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Health & Wellbeing / Prevent / British Values / Citizenship</b><br>Discuss progress and topics that have been set and learnt</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->assessment . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";

        $html .= "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Concerns</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Welfare Check-In</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->end_point_assessment . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Additional Support Requirements</b><br>Is there any additional support you would like from Baltic Training or your Line Manager</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->skilsure2 . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";


/*
    <tr>
        <td colspan=4><b>Apprenticeship Commitment</b><br>Are there any issues or anything you would like to disclose which could prevent you completing your 12 month apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->skilsure . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Learner Progress at Placement / Employment</b><br>Discuss both positive and development areas.  Comment on attendance, time keeping, attitude and ability including new skills developed.  Identify new skills and experience that have been learnt and applied at work.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->setting_work . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Off the job training in the workplace</b><br>Record here training and learning that has taken place at work.  This will include job shadowing, mentoring by a supervisor, project work. (Learning Logs/Learning Diary can support these statements).</td>
    </tr>
    <tr>
        <td style=\"text-align: center\">Previous:</td>
        <td style=\"text-align: center\">Current:</td>
        <td style=\"text-align: center\">Other:</td>
    </tr>
    <tr>
        <td style=\"text-align: center;\">";
        $hours =  DAO::getSingleValue($link, "SELECT SUM(hours) FROM assessor_review WHERE tr_id = '$tr_id'");
        $html .= $hours . "</td>
        <td style=\"text-align: center\">" . $form_arf->current_hours . "</td>
        <td style=\"text-align: center;\">";
        $reflective = DAO::getSingleValue($link, "SELECT (SELECT COALESCE(SUM(HOUR(TIMEDIFF(time_to, time_from))),0)  FROM additional_support WHERE tr_id = '$tr_id')+
        (SELECT
                      COALESCE(SUM(HOUR(TIMEDIFF(start_time,end_time))),0)
                    FROM
                      session_attendance
                      INNER JOIN session_entries
                        ON session_attendance.`session_entry_id` = session_entries.`entry_id`
                      INNER JOIN sessions
                        ON session_entries.`entry_session_id` = sessions.`id`
                    WHERE sessions.`event_type` = 'SUP' AND session_attendance.`attendance_code` = 1
                    AND session_entries.`entry_tr_id` = '$tr_id');
");
    $html .= $reflective ."</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->learner_concerns . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>"; */

        return $html;
    }

    public function getSkillsScanITInfrastructureTechnician($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=5>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th>
        </tr>
        </thead>
        <tbody>
        <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
        <tr>
        <td rowspan=3>Communication</td>
        <td>Work both independently and as part of a team and follow your organisations standards</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false) .
            "</td>";
        $communication = isset($Assessment_Plan['Communication'])?$Assessment_Plan['Communication']:"";
        $html .= "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
        </tr>
        <tr><td>Able to communicate both in writing and orally at all levels</td></tr>
        <tr><td>Use a range of tools and demonstrate strong interpersonal skills and cultural awareness when dealing with colleagues, customers and clients during all tasks.</td>
        </tr>

        <tr>
            <td>IT Security</td>
            <td>Securely operate across all platforms and areas of responsibilities in line with organisations guidance and legislation</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['IT Security'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false) . "</td>";
            $it_security = isset($Assessment_Plan['IT Security'])?$Assessment_Plan['IT Security']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
        </tr>
        <tr>
            <td>Remote Infrastructure</td>
            <td>Operate a range of mobile devices and securely add them to a network in accordance with organisations policies and procedures</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Remote Infrastructure'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false) . "</td>";
            $remote_infrastructure = isset($Assessment_Plan['Remote Infrastructure'])?$Assessment_Plan['Remote Infrastructure']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
        </tr>
        <tr>
            <td>Data</td>
            <td>Record, analyse and communicate data at the appropriate level using the organisation's standard tools and processes, to all stakeholders within the responsibility of the position</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false) . "</td>";
            $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
        </tr>
        <tr>
            <td rowspan=2>Problem Solving</td>
            <td>Apply structured techniques to common and non-routine problems, testing methodologies</td>
            <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
            <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false) . "</td>";
            $problem_solving = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $html .= "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
        </tr>
        <tr><td>Troubleshoot and analyse problems by selecting the digital appropriate tools and techniques in line with organisation guidance and to obtain the relevant logistical support as required</td></tr>
        </tr>
        <tr>
            <td>Workflow Management</td>
            <td>Work flexibly and have the ability to work under pressure to progress allocated tasks in accordance with the organisation's reporting and quality systems</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Workflow Management'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false) . "</td>";
            $workflow_management = isset($Assessment_Plan['Workflow Management'])?$Assessment_Plan['Workflow Management']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
        </tr>
        <tr>
            <td>Health and Safety</td>
            <td>Interpret and follow IT legislation to securely and professional work productively</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Health and Safety'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false) . "</td>";
            $health_safety = isset($Assessment_Plan['Health and Safety'])?$Assessment_Plan['Health and Safety']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
        </tr>
        <tr>
            <td rowspan=2>Performance</td>
            <td>Optimise the performance of hardware, software and Network Systems and services in line with business requirements</td>
            <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Performance'] . "</td>
            <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false) . "</td>";
            $performance = isset($Assessment_Plan['Performance'])?$Assessment_Plan['Performance']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
        </tr>
        <tr><td>Explain the correct processes associated with WEEE (the Waste Electrical and Electronic Equipment Directive)</td>";
        $weee = isset($Assessment_Plan['WEEE'])?$Assessment_Plan['WEEE']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $weee . "</td>
        </tr>
        </tbody>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
            <tr>
                <td>A range of cabling and connectivity, the various types of antennas and wireless systems and IT test equipment (Networking MTA)</td>
                <td rowspan=5 style='text-align:center; vertical-align:middle'>" . $tk_result['9628-06 Networking and Architecture Test'] . "</td>
                <td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false) . "</td>
                <td rowspan=5 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'9628-06 Networking and Architecture Test') . "<br>" . $this->getEventDate($events,'9628-06 Networking and Architecture Test') . "</td>
            </tr>
            <tr><td>Maintenance  processes and applying them in working practices (Networking MTA)</td></tr>
            <tr><td>Applying the basic elements and architecture of computer systems (Networking MTA)</td>
            <tr><td>Where to apply the relevant numerical skills e.g. Binary (Networking MTA)</td>
            <tr><td>Networking skills necessary to maintain a secure network (Networking MTA)</td>
            </tr>
            <tr>
                <td>Similarities, differences and benefits of the current Operating Systems available (Mobility & Devices MTA)</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['Mobility and Devices MTA Test'] . "</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false) . "</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'Mobility and Devices MTA Test') . "<br>" . $this->getEventDate($events,'Mobility and Devices MTA Test') . "</td>
            </tr>
            <tr><td>How to operate remotely and how to deploy and securely integrate mobile devices (Mobility & Devices MTA)</td></tr>
            </tr>
            <tr>
                <td>Cloud and Cloud Service (Cloud MTA)</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>";
            if(isset($tk_result['Cloud Fundamentals MTA Test']))
                $html.= $tk_result['Cloud Fundamentals MTA Test'];
            else
                $html.="&nbsp;";
            $html.="</td><td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false) . "</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'Cloud Fundamentals MTA Test') . "<br>" . $this->getEventDate($events,'Cloud Fundamentals MTA Test') . "</td>
            </tr>
            <tr><td>Importance of disaster recovery and how a disaster recovery plan works and their role within it (Cloud MTA)</td></tr>
            </tr>
            <tr>
                <td>Similarities and differences between a range of coding and logic (Coding & Logic)</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['9268-09 Coding and Logic Test'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'9628-09 Coding and Logic Test') . "<br>" . $this->getEventDate($events,'9628-09 Coding and Logic Test') . "</td>
            </tr>
            <tr>
                <td>Business processes (Business Processes)</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['9628-10 ITIL Foundation Test'] . "</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false) . "</td>
                <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'9628-10 ITIL Foundation Test Business Processes Test') . "<br>" . $this->getEventDate($events,'9628-10 ITIL Foundation Test Business Processes Test') . "</td>
            </tr>
            <tr><td>Business IT skills relevant to the organization (Business Processes)</td></tr>
            </tr>
            </tbody>
        </table>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
            <tr>
                <td>Logical and creative thinking skills</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Data","Problem Solving","Workflow Management")))
                $html .="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .="<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .="</tr>
            <tr>
                <td>Analytical and problem solving skills</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Data","Problem Solving")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .="</tr>
            <tr>
                <td>Ability to work independently and to take responsibility</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .= "</tr>
            <tr>
                <td>Can use own initiative</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .="</tr>
            <tr>
                <td>A thorough and organised approach</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("IT Security","Problem Solving","Workflow Management","Performance","WEEE")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .="</tr>
            <tr>
                <td>Ability to work with a range of internal and external people</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Problem Solving","Health and Safety","Performance","WEEE","Communication")))
                $html .="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .="<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .="</tr>
            <tr>
                <td>Ability to communicate effectively in a variety of situations</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Communication")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .="</tr>
            <tr>
                <td>Maintain productive, professional and secure working environment</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false) . "</td>";
                if($this->isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
                $html .="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>;</td>";
            $html .= "</tr>
            </tbody>
        </table>
        <br>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
            <tr>
                <td>Network Fundamentals MTA</td>
                <td>" . $this->getEventStatus($events, "Network Fundamentals MTA") . "<br>" . $this->getEventDate($events,'Network Fundamentals MTA') . "</td>
                <td>Network Fundamentals MTA Test</td>
                <td>" . $this->getEventStatus($events, "Network Fundamentals MTA Test") . "<br>" . $this->getEventDate($events,'Network Fundamentals MTA Test') . "</td>
                <td>Mobility and Devices MTA</td>
                <td>" . $this->getEventStatus($events, "Mobility and Devices MTA") . "<br>" . $this->getEventDate($events,'Mobility and Devices MTA') . "</td>
            </tr>
            <tr>
                <td>Mobility and Devices MTA Test</td>
                <td>" . $this->getEventStatus($events, "Mobility and Devices MTA Test") . "<br>" . $this->getEventDate($events,'Mobility and Devices MTA Test') . "</td>
                <td>Cloud Fundamentals MTA</td>
                <td>" . $this->getEventStatus($events, "Cloud Fundamentals MTA") . "<br>" . $this->getEventDate($events,'Cloud Fundamentals MTA') . "</td>
                <td>Cloud Fundamentals MTA Test</td>
                <td>" . $this->getEventStatus($events, "Cloud Fundamentals MTA Test") . "<br>" . $this->getEventDate($events,'Cloud Fundamentals MTA Test') . "</td>
            </tr>
            <tr>
                <td>Business Processes</td>
                <td>" . $this->getEventStatus($events, "Business Processes") . "<br>" . $this->getEventDate($events,'Business Processes') . "</td>
                <td>City & Guilds 9628-10 Level 3 Award in Business Processes Test</td>
                <td>" . $this->getEventStatus($events, "City & Guilds 9628-10 Level 3 Award in Business Processes Test") . "<br>" . $this->getEventDate($events,'City & Guilds 9628-10 Level 3 Award in Business Processes Test') . "</td>
                <td>Coding and Logic</td>
                <td>" . $this->getEventStatus($events, "Coding and Logic") . "<br>" . $this->getEventDate($events,'Coding and Logic') . "</td>
            </tr>
            <tr>
                <td>9628-09 Coding and logic test</td>
                <td>" . $this->getEventStatus($events, "9628-09 Coding and logic test") . "<br>" . $this->getEventDate($events,'9628-09 Coding and logic test') . "</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            </tbody>
        </table>
        <br>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->workplace_competence . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this?</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->knowledge_modules . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>";

        return $html;

    }



    public function getFunctionalSkills($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts)
    {
        $html ="<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=3>&nbsp;&nbsp;&nbsp;Functional Skills Development: In the workplace - In everyday use - In training</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan=3>Functional Skills exemptions:</td></tr>
            <tr><td>Maths";
            $checked = ($math_exempt==1)?"checked=''":"";
            $html .="<input type = \"checkbox\" $checked></td><td>English";
            $checked = ($english_exempt==1)?"checked=''":"";
            $html .="<input type = \"checkbox\" $checked></td><td>ICT";
            $checked = ($ict_exempt==1)?"checked=''":"";
            $html .="<input type = \"checkbox\" $checked></td></tr>
            <tr>
                <td colspan=6><i>
                    " . $form_arf->functional_skills_progress . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Off the job training in the workplace</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan=6>Record training and learning that has taken place</td></tr>
            <tr>
                <td colspan=6><i>
                    " . $form_arf->functional_skills_progress2 . "
                </i></td>
            </tr>
            <tr>
                <td>Hours Currently</td><td><input type=\"text\" name=\"hours_currently\" value=" . $form_arf->hours_currently . " size=10/></td>
                <td>On-track/ Behind</td><td>" . HTML::selectChosen('ontrack_behind', $attempts, $form_arf->ontrack_behind, true) . "</td>
            </tr>
            </tbody>
        </table>
        <br>";
        return $html;

    }


    public function getFunctionalSkillsOld($form_arf, $math_exempt, $english_exempt, $ict_exempt, $link, $tr_id, $attempts)
    {
        $html ="<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=3>&nbsp;&nbsp;&nbsp;Functional Skills Development: In the workplace - In everyday use - In training</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan=3>Functional Skills exemptions:</td></tr>
            <tr><td>Maths";
        $checked = ($math_exempt==1)?"checked=''":"";
        $html .="<input type = \"checkbox\" $checked></td><td>English";
        $checked = ($english_exempt==1)?"checked=''":"";
        $html .="<input type = \"checkbox\" $checked></td><td>ICT";
        $checked = ($ict_exempt==1)?"checked=''":"";
            $html .="<input type = \"checkbox\" $checked></td></tr>
            <tr>
                <td colspan=6><i>
                    " . $form_arf->functional_skills_progress . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>";
        $html.="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress summary: Functional Skills – (complete only if working towards qualification)</th></tr>
    </thead><tbody>
    <tr><td colspan=6>Indicate units completed with a %</td></tr>
    <tr>
        <td>English L2</td><td>Maths L1</td><td>Maths L2</td><td>ICT L1</td><td>ICT L2</td><td>PLTS</td>
    </tr>
    <tr>
        <td>" . DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%English%\" AND internaltitle LIKE \"%Level 2%\" and tr_id = '$tr_id';") . "</td>
        <td>" . DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%Math%\" AND internaltitle LIKE \"%Level 1%\" and tr_id = '$tr_id';") . "</td>
        <td>" . DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%Math%\" AND internaltitle LIKE \"%Level 2%\" and tr_id = '$tr_id';") . "</td>
        <td>" . DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%ICT%\" AND internaltitle LIKE \"%Level 1%\" and tr_id = '$tr_id';") . "</td>
        <td>" . DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%ICT%\" AND internaltitle LIKE \"%Level 2%\" and tr_id = '$tr_id';") . "</td>
        <td>" . DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%PLTS%\" and tr_id = '$tr_id';") . "</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->functional_skills_progress2 . "
        </i></td>
    </tr></tbody>
</table>
<br>";
    return $html;

    }

    public function getProjectStatus($projects, $find)
    {
        foreach($projects as $project)
            if($project[0]==$find)
                return $project[1];
    }

}

?>