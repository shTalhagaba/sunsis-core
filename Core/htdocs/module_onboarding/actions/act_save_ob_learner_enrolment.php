<?php
class save_ob_learner_enrolment implements IAction
{
    public function execute(PDO $link)
    {
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$_REQUEST['ob_learner_id']}'");
        $sunesis_learner = User::loadFromDatabaseById($link, $_REQUEST['sunesis_learner_id']);
        $objCourse = Course::loadFromDatabase($link, $_REQUEST['course_id']);

        if(is_null($ob_learner) || is_null($sunesis_learner) || is_null($objCourse))
            throw new Exception('Invalid input ids');

        $aims_keys = [
            'main_aim', 'tech_cert', 'l2_found_competence', 'fs_maths', 'fs_eng', 'fs_ict', 'ERR', 'PLTS'
        ];

        DAO::transaction_start($link);
        try
        {
            $l03 = DAO::getSingleValue($link, "SELECT l03 FROM tr WHERE username = '{$sunesis_learner->username}' LIMIT 0,1");
            // create training record
            $tr = new TrainingRecord();
            $tr->populate($sunesis_learner, true);
            $tr->contract_id = $_REQUEST['contract_id'];
            $tr->start_date = isset($_REQUEST['practical_start_date']) ? $_REQUEST['practical_start_date'] : '';
            $tr->target_date = isset($_REQUEST['practical_end_date']) ? $_REQUEST['practical_end_date'] : '';
            $tr->end_date_inc_epa = $_REQUEST['end_date'];
            $tr->status_code = 1;
            $provider = DAO::getObject($link, "SELECT locations.* FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id` WHERE organisations.`organisation_type` = 3 LIMIT 1");
            $tr->provider_id = $provider->organisations_id;
            $tr->provider_location_id = $provider->id;
            $tr->provider_address_line_1 = $provider->address_line_1;
            $tr->provider_address_line_2 = $provider->address_line_2;
            $tr->provider_address_line_3 = $provider->address_line_3;
            $tr->provider_address_line_4 = $provider->address_line_4;
            $tr->provider_postcode = $provider->postcode;
            $tr->provider_telephone = $provider->telephone;
            if($sunesis_learner->ethnicity != 'NOBT')
                $tr->ethnicity = $sunesis_learner->ethnicity;
            $tr->work_experience = 0;
            $tr->crm_contact_id = $_REQUEST['crm_contact_id'];
            $tr->coach = $_REQUEST['coach'];
            $tr->l36 = 0;
            $tr->id = NULL;
            if($l03 == '')
            {
                $l03 = (int)DAO::getSingleValue($link, "SELECT MAX(l03) FROM tr WHERE l03 + 0 <> 0 AND LENGTH(RTRIM(l03)) = 12");
                $l03 += 1;
                $tr->l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
            }
            else
            {
                $tr->l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
            }
            $weeks_on_programme = $_REQUEST['weeks_on_programme'];
            $statutory_annual_leave = $_REQUEST['statutory_annual_leave'];
            $emp_q7 = $_REQUEST['emp_q7']; // normal weekly hours
            //$tr->otj_hours = ceil((floatval($weeks_on_programme)-floatval($statutory_annual_leave))*floatval($emp_q7)*0.2);
            $tr->otj_hours = $_REQUEST['planned_otj_hours'];
	    $tr->epa_organisation = isset($_REQUEST['epa_organisation']) ? $_REQUEST['epa_organisation'] : '';
            $tr->save($link);
            // attach course to training record
            $courses_tr = new stdClass();
            $courses_tr->course_id = $objCourse->id;
            $courses_tr->tr_id = $tr->id;
            $courses_tr->framework_id = $objCourse->framework_id;
            $courses_tr->qualification_id = 0;
            //pre($courses_tr);
            DAO::saveObjectToTable($link, 'courses_tr', $courses_tr);
            // attach group to training record if applicable
            if(isset($_REQUEST['cohort_id']) && $_REQUEST['cohort_id'] != '')
            {
                $group_members = new stdClass();
                $group_members->groups_id = $_REQUEST['cohort_id'];
                $group_members->tr_id = $tr->id;
                $group_members->member = '0';
                DAO::saveObjectToTable($link, 'group_members', $group_members);
            }
            // attach framework to training record
            $query = "INSERT INTO student_frameworks SELECT title, id, '{$tr->id}', framework_code, comments, duration_in_months FROM frameworks WHERE id = '{$objCourse->framework_id}'";
            DAO::execute($link, $query);

            /*
            foreach($aims_keys AS $_key)
            {
                if($_REQUEST[$_key.'_id'] != '')
                    $this->attachQualification($link, $tr, $objCourse->framework_id, $_REQUEST[$_key.'_id'], $_REQUEST[$_key.'_sd'], $_REQUEST[$_key.'_ped'], $_REQUEST[$_key.'_glh']);
            }
            */
            foreach($_REQUEST['selected_quals'] AS $q_id)
            {
                $this->attachQualification($link, $tr, $objCourse->framework_id, $q_id, $_REQUEST['sd_'.$q_id], $_REQUEST['ped_'.$q_id], $_REQUEST['glh_'.$q_id]);
            }

            // update milestones
            $this->createMilestones($link, $tr->id);

            // create ILR and attach to training record
            $objContract = Contract::loadFromDatabase($link, $tr->contract_id);
            $objFramework = Framework::loadFromDatabase($link, $objCourse->framework_id);
            $ilrTemplate = '';
            if(!is_null($objContract->template) && $objContract->template != '')
                $ilrTemplate = XML::loadSimpleXML($objContract->template);

            $sql = <<<SQL
SELECT
submission
FROM
central.lookup_submission_dates
WHERE last_submission_date >= CURDATE()
AND contract_year = '$objContract->contract_year'
AND contract_type = '$objContract->funding_body'
ORDER BY
last_submission_date
LIMIT 1;
SQL;

            $submission = DAO::getSingleValue($link, $sql);
            //$submission = 'W01';

            $ilr = new ILRStruct2015($submission, $tr->contract_id, $tr->id, $tr->l03);
            $ilr->populateFromLearner($sunesis_learner);
            $learnerEmpStatus1 = new LearnerEmploymentStatusStruct();
            $learnerEmpStatus1->EmpStat = 10;
            if(!is_null($sunesis_learner->l37) && $sunesis_learner->l37 != '')
            {
                $learnerEmpStatus1->EmpStat = $sunesis_learner->l37;
                if(!is_null($sunesis_learner->lou) && $sunesis_learner->lou != '')
                    $learnerEmpStatus1->LOU = $sunesis_learner->lou;
            }
            $learnerEmpStatus1->EmpId = '999999999';
            $start_date2 = new Date($tr->start_date);
            $start_date2->subtractDays(1);
            $learnerEmpStatus1->DateEmpStatApp = $start_date2->formatMySQL();
            $ilr->addLearnerEmploymentStatus($learnerEmpStatus1);

            $learnerEmpStatus2 = new LearnerEmploymentStatusStruct();
            $learnerEmpStatus2->EmpStat = 10;
            if(!is_null($sunesis_learner->l47) && $sunesis_learner->l47 != '')
                $learnerEmpStatus2->EmpStat = $sunesis_learner->l47;
            $learnerEmpStatus2->EmpId = '999999999';
            $learnerEmpStatus2->DateEmpStatApp = $tr->start_date;
            $ilr->addLearnerEmploymentStatus($learnerEmpStatus2);

            // add the ZPROG001 delivery
            $zprog_delivery = new LearningDeliveryStruct('ZPROG001');
            $zprog_delivery->AimSeqNumber = 1;
            $zprog_delivery->AimType = 1;
            $zprog_delivery->LearnStartDate = $tr->start_date;
            $zprog_delivery->LearnPlanEndDate = $tr->target_date;
            $zprog_delivery->FundModel = '36';
            $zprog_delivery->ProgType = $objFramework->framework_type;
            $zprog_delivery->FworkCode = $objFramework->framework_code;
            //$zprog_delivery->PwayCode = $this->getValueFromTemplate($ilrTemplate,"ZPROG001","PwayCode");
            //TODO: $zprog_delivery->StdCode = '';
            $zprog_delivery->DelLocPostCode = $sunesis_learner->work_postcode;
            $zprog_delivery->CompStatus = 1;
            $zprog_delivery->SOF = '105';
            $ilr->addLearningDelivery($zprog_delivery);

            $student_qualifications = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, start_date, end_date FROM student_qualifications WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
            $counter = 1;
            foreach($student_qualifications AS $std_qual)
            {
                $counter++;
                $delivery = new LearningDeliveryStruct($std_qual['id']);
                $delivery->AimSeqNumber = $counter;
                $delivery->AimType = 3;
                $delivery->LearnStartDate = $std_qual['start_date'];
                $delivery->LearnPlanEndDate = $std_qual['end_date'];
                $delivery->FundModel = '36';
                $delivery->ProgType = $objFramework->framework_type;
                $delivery->FworkCode = $objFramework->framework_code;
                $delivery->DelLocPostCode = $sunesis_learner->work_postcode;
                $delivery->CompStatus = 1;
                $delivery->SOF = '105';
                if($tr->college_id != '' && !is_null($tr->college_id))
                    $delivery->PartnerUKPRN = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$tr->college_id}'");
                $ilr->addLearningDelivery($delivery);
            }
            $xml = $ilr->getILRXML();
            $xml = str_replace('<?xml version="1.0"?>', '', $xml);
            $xml = str_replace("'", "&apos;", $xml);
            $tempILR = new stdClass();
            $tempILR->L03 = $tr->l03;
            $tempILR->A09 = 0;
            $tempILR->ilr = $xml;
            $tempILR->submission = $submission;
            $tempILR->contract_type = !is_null($objContract->contract_type)?$objContract->contract_type:'ER';
            $tempILR->tr_id = $tr->id;
            $tempILR->is_complete = 0;
            $tempILR->is_valid = 0;
            $tempILR->is_approved = 0;
            $tempILR->is_active = 1;
            $tempILR->contract_id = $objContract->id;
            DAO::saveObjectToTable($link, 'ilr', $tempILR);
            unset($tempILR);

            $ob_learner->weeks_on_programme = $_REQUEST['weeks_on_programme'];
            $ob_learner->statutory_annual_leave = $_REQUEST['statutory_annual_leave'];
            $ob_learner->emp_q7 = $_REQUEST['emp_q7'];
            $ob_learner->is_enrolled = 'Y';
            $ob_learner->linked_tr_id = $tr->id;
            $ob_learner->practical_start_date = isset($_REQUEST['practical_start_date']) ? $_REQUEST['practical_start_date'] : '';
            $ob_learner->practical_end_date = isset($_REQUEST['practical_end_date']) ? $_REQUEST['practical_end_date'] : '';
            $ilp_weeks_on_programme = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(WEEK, '{$tr->start_date}', '{$tr->target_date}')");
            $ob_learner->planned_otj_hours = $tr->otj_hours;
            DAO::saveObjectToTable($link, "ob_learners", $ob_learner);

            $log = new OnboardingLogger();
            $log->subject = 'ENROLMENT';
            $log->note = "Learner record is enrolled. Main Details of training record:\n";
            $log->note .= "L03: {$tr->l03}\n";
            $c = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '{$tr->contract_id}'");
            $log->note .= "Contract: {$c}\n";
            $log->ob_learner_id = $ob_learner->id;
            $log->by_whom = $_SESSION['user']->id;
            $log->save($link);
            unset($log);

            // send welcome email to the learner
            //$this->sendWelcomeEmailToLearner($link, $tr);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect("do.php?_action=read_ob_learner&id={$ob_learner->id}");
    }

    private function attachQualification(PDO $link, TrainingRecord $tr, $framework_id, $qualification, $start_date, $planned_end_date, $glh)
    {
        if($qualification == '')
            return;

        $frameworkQualification = FrameworkQualification::loadFromDatabaseWithoutQualTitle($link, $qualification, $framework_id);
        if(is_null($frameworkQualification))
            return;

        $studentQualification = new StudentQualification();
        $studentQualification->populate($frameworkQualification);
        $studentQualification->tr_id = $tr->id;
        $studentQualification->start_date = $start_date;
        $studentQualification->end_date = $planned_end_date;
        $studentQualification->glh = $glh;
        $studentQualification->save($link);
        unset($frameworkQualification);
        unset($studentQualification);
    }

    private function createMilestones(PDO $link, $tr_id)
    {
        $sql = "SELECT evidences, framework_id, id, internaltitle, timestampdiff(MONTH, start_date, end_date) AS months FROM student_qualifications WHERE tr_id = '{$tr_id}'";
        $st = $link->query($sql);
        $unit = 0;
        while($row = $st->fetch())
        {
            $xml = mb_convert_encoding($row['evidences'],'UTF-8');
            $pageDom = XML::loadXmlDom(mb_convert_encoding($xml,'UTF-8'));
            $evidences = $pageDom->getElementsByTagName('unit');
            foreach($evidences as $evidence)
            {
                $unit_id = $evidence->getAttribute('owner_reference');
                $framework_id = $row['framework_id'];
                $qualification_id = $row['id'];
                $internaltitle = $row['internaltitle'];

                $m = Array();
                for($a = 1; $a<=$row['months']; $a++)
                {
                    if($a==$row['months'])
                        $m[] = 100;
                    else
                        $m[] = sprintf("%.1f", 100 / $row['months'] * $a);
                }
                for($a = $row['months']+1; $a<=36; $a++)
                {
                    $m[] = 100;
                }
                $internaltitle = addslashes((string)$internaltitle);
                DAO::execute($link, "insert into student_milestones values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
            }
        }
    }

}