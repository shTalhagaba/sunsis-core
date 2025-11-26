<?php
class save_form_non_app_enrolment implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidNonAppEnrolmentUrl($link, $id, $key))
            {
                http_redirect("do.php?_action=error_page");
                exit;
            }
        }
        else
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        if($tr->is_finished == 'Y')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }

        $checkboxes = [
            'SEI', 'PEI', 'SEM', 'ehc_plan', 'care_leaver', 'work_curr_emp'
        ];

        foreach($checkboxes AS $chk_name)
        {
            $tr->$chk_name = 0;
        }

        foreach($_REQUEST AS $key => $value)
        {
            $tr->$key = $value;
        }

        $ob_learner = $tr->getObLearnerRecord($link);
        $ob_learner_fields = [
            'learner_title',
            'firstnames',
            'surname',
            'gender',
            'dob',
            'home_address_line_1',
            'home_address_line_2',
            'home_address_line_3',
            'home_address_line_4',
            'borough',
            'home_postcode',
            'home_email',
            'home_telephone',
            'home_mobile',
            'work_email',
            'uln',
            'ni',
            'ethnicity',
        ];
        foreach($ob_learner_fields AS $learner_field)
        {
            $ob_learner->$learner_field = isset($_POST[$learner_field]) ? $_POST[$learner_field] : null;
        }

        if(isset($_POST['EligibilityList']) && in_array(2, $tr->EligibilityList))
            $tr->currently_enrolled_in_other =  $tr->currently_enrolled_in_other;
        else
            $tr->currently_enrolled_in_other = '';

        DAO::transaction_start($link);
        try
        {
            if($tr->is_finished == "N")
            {// partial save
                $ob_learner->save($link);
                $this->saveInformation($link, $tr);
                $this->saveEmergencyContacts($link, $tr, $_POST);
                $this->saveCareLeaverInformation($link, $tr, $_POST, $_FILES);
                $this->saveCriminalConvictionInformation($link, $tr, $_POST);
                $this->savePriorAttainment($link, $tr);
                if(isset($tr->EmploymentStatus) && $tr->EmploymentStatus == 10)
                {
                    $this->saveShiftPattern($link, $tr, $_POST);
                };
		        if(DB_NAME == "am_ela")
                {
                    $this->saveExtraFields($link, $tr, $_POST);
			        $this->saveAls($link, $tr, $_POST);
                }
                elseif(DB_NAME != 'am_crackerjack')
                {
                    $this->saveAls($link, $tr, $_POST);
                }
            }
            else
            {// complete save
                // start uploading files
                $target_directory = 'OnboardingModule/learners/' . $ob_learner->id . DIRECTORY_SEPARATOR . $tr->id . DIRECTORY_SEPARATOR .'/onboarding';
                $valid_extensions = array('csv', 'doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg', 'txt');

                if(isset($_FILES['ehc_evidence_file']['size']) &&
                    ($this->checkFileExtension($valid_extensions, $_FILES['ehc_evidence_file']['name'])) &&
                    $_FILES['ehc_evidence_file']['size'] <= 1048000)
                {
                    Repository::processFileUploads('ehc_evidence_file', $target_directory, $valid_extensions);
                    $tr->ehc_evidence_file = $_FILES['ehc_evidence_file']['name'];
                }
                if(isset($_FILES['care_leaver_evidence_file']['size']) &&
                    ($this->checkFileExtension($valid_extensions, $_FILES['care_leaver_evidence_file']['name'])) &&
                    $_FILES['care_leaver_evidence_file']['size'] <= 1048000)
                {
                    Repository::processFileUploads('care_leaver_evidence_file', $target_directory, $valid_extensions);
                    $tr->care_leaver_evidence_file = $_FILES['care_leaver_evidence_file']['name'];
                }
                if(isset($_FILES['in_care_evidence_file']['size']) &&
                    ($this->checkFileExtension($valid_extensions, $_FILES['in_care_evidence_file']['name'])) &&
                    $_FILES['in_care_evidence_file']['size'] <= 1048000)
                {
                    Repository::processFileUploads('in_care_evidence_file', $target_directory, $valid_extensions);
                    $tr->in_care_evidence_file = $_FILES['in_care_evidence_file']['name'];
                }
                if(isset($_FILES['evidence_pp']['size']) &&
                    ($this->checkFileExtension($valid_extensions, $_FILES['evidence_pp']['name'])) &&
                    $_FILES['evidence_pp']['size'] <= 1048000)
                {
                    Repository::processFileUploads('evidence_pp', $target_directory, $valid_extensions);
                    $tr->evidence_pp_file = $_FILES['evidence_pp']['name'];
                }
                if(isset($_FILES['evidence_ilr']['size']) &&
                    ($this->checkFileExtension($valid_extensions, $_FILES['evidence_ilr']['name'])) &&
                    $_FILES['evidence_ilr']['size'] <= 1048000)
                {
                    Repository::processFileUploads('evidence_ilr', $target_directory, $valid_extensions);
                    $tr->evidence_ilr_file = $_FILES['evidence_ilr']['name'];
                }
                if(isset($_FILES['evidence_previous_uk_study_visa']['size']) &&
                    ($this->checkFileExtension($valid_extensions, $_FILES['evidence_previous_uk_study_visa']['name'])) &&
                    $_FILES['evidence_previous_uk_study_visa']['size'] <= 1048000)
                {
                    Repository::processFileUploads('evidence_previous_uk_study_visa', $target_directory, $valid_extensions);
                    $tr->evidence_previous_uk_study_visa_file = $_FILES['evidence_previous_uk_study_visa']['name'];
                }

                $ob_learner->save($link);
                $learner_sign = isset($_REQUEST['learner_sign'])?$_REQUEST['learner_sign']:'';
                if($learner_sign == '')
                    throw new Exception('Missing learner signature');

                $learner_sign = explode('&', $_REQUEST['learner_sign']);
                unset($learner_sign[0]);
                $tr->learner_sign = implode('&', $learner_sign);

                $tr->learner_sign_date = date('Y-m-d');

                $this->saveInformation($link, $tr);
                $this->saveEmergencyContacts($link, $tr, $_POST);
                $this->saveCareLeaverInformation($link, $tr, $_POST, $_FILES);
                $this->saveCriminalConvictionInformation($link, $tr, $_POST);
                $this->savePriorAttainment($link, $tr);
		        if(isset($tr->EmploymentStatus) && $tr->EmploymentStatus == 10)
                {
                    $this->saveShiftPattern($link, $tr, $_POST);
                }
                if(DB_NAME == "am_ela")
                {
                    $this->saveExtraFields($link, $tr, $_POST);
			        $this->saveAls($link, $tr, $_POST);
                }
                elseif(DB_NAME != 'am_crackerjack')
                {
                    $this->saveAls($link, $tr, $_POST);
                }

                $log = new OnboardingLogger();
                $log->subject = 'FORM COMPLETED BY LEARNER';
                $log->note = "Learner has completed and finished the form.";
                $log->tr_id = $tr->id;
                $log->by_whom = $tr->id;
                $log->save($link);
                unset($log);

                $employer = Employer::loadFromDatabase($link, $tr->employer_id);
                $location = $employer->getMainLocation($link);

                $primary_contact_email = $location->contact_email;
                $primary_contact_name = $location->contact_name;

                if($primary_contact_email == '')
                {
                    $primary_contact_email_sql = <<<SQL
SELECT
  organisation_contacts.`contact_email`, organisation_contacts.`contact_name`
FROM
  organisation_contacts
WHERE organisation_contacts.`org_id` = '{$employer->id}'
  AND organisation_contacts.`job_role` = 99
  AND organisation_contacts.`contact_email` IS NOT NULL
ORDER BY organisation_contacts.`contact_id` DESC
LIMIT 1;
SQL;
                    $primary_contact = DAO::getObject($link, $primary_contact_email_sql);
                    if(isset($primary_contact->contact_email))
                        $primary_contact_email = $primary_contact->contact_email;
                    if(isset($primary_contact->contact_name))
                        $primary_contact_name = $primary_contact->contact_name;
                }

                if(!SOURCE_LOCAL)
                {
                    //$this->sendEmailToEmployer($link, $ob_learner, $tr, $primary_contact_email, $primary_contact_name);
                    //$this->sendEmailToLearnerPT($link, $ob_learner, $tr);
                    //$this->sendEmailToTrainer($link, $ob_learner, $tr);
                }

            }
            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new Exception($e->getMessage());
            exit;
        }

        if($tr->is_finished == 'N')
        {
            echo json_encode($ob_learner);
            return;
        }

        $tr->generateSignatureImages($link);

        //http_redirect('do.php?_action=onboarding&id='.$ob_learner->id.'&key='.md5($ob_learner->id.'_sunesis_completed'));
        OnboardingHelper::generateCompletionPage($link, $tr->id);
    }

    private function sendEmailToEmployer(PDO $link, OnboardingLearner $ob_learner, TrainingRecord $tr, $primary_contact_email, $primary_contact_name)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'APP_AGREEMENT_EMAIL_TO_EMPLOYER' ");
        if($email_content == '')
            return;

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, 'APP_AGREEMENT_EMAIL_TO_EMPLOYER', $tr);

        Emailer::notification_email($primary_contact_email,
            'no-reply@perspective-uk.com',
            '',
            'Your new Apprentice at ' . $tr->getProviderLegalName($link),
            '',
            $ready_template
        );
    }

    private function sendEmailToLearnerPT(PDO $link, OnboardingLearner $ob_learner, TrainingRecord $tr)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'FIRST_DAY_IN_LEARNING' ");
        if($email_content == '')
            return;

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, 'FIRST_DAY_IN_LEARNING', $tr);

        Emailer::notification_email($ob_learner->home_email,
            'no-reply@perspective-uk.com',
            '',
            'Personality Test Email',
            '',
            $ready_template
        );

    }

    private function sendEmailToTrainer(PDO $link, OnboardingLearner $ob_learner, TrainingRecord $tr)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMAIL_TO_TRAINER_FOLLOWING_ONBOARDING' ");
        if($email_content == '')
            return;

        $trainers = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
        $trainer_id = isset($trainers[0]) ? $trainers[0] : '';
        if($trainer_id == '')
            return;

        $trainer_record = DAO::getObject($link, "SELECT firstnames, surname, work_email FROM users WHERE users.id = '{$trainer_id}'");

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, 'EMAIL_TO_TRAINER_FOLLOWING_ONBOARDING', $tr);

        Emailer::notification_email($trainer_record->work_email,
            'no-reply@perspective-uk.com',
            '',
            'Completed On-Boarding documents',
            '',
            $ready_template
        );
    }

    private function saveInformation(PDO $link, TrainingRecord $tr)
    {

        $save = $tr->save($link);

        //echo json_encode($save);

    }

    public function saveCareLeaverInformation(PDO $link, TrainingRecord $tr, $input, $files)
    {
        $care_leaver_details = $tr->getCareLeaverDetails($link);
        $care_leaver_details->tr_id = $tr->id;
        $care_leaver_details->in_care_of_local_authority = isset($input['in_care_of_local_authority']) ? $input['in_care_of_local_authority'] : 0;
        $care_leaver_details->eligible_for_bursary_payment = isset($input['eligible_for_bursary_payment']) ? $input['eligible_for_bursary_payment'] : 0;
        $care_leaver_details->give_consent_to_inform_employer = isset($input['give_consent_to_inform_employer']) ? $input['give_consent_to_inform_employer'] : 0;
        $care_leaver_details->in_care_evidence = isset($input['in_care_evidence']) ? $input['in_care_evidence'] : '';
        $care_leaver_details->care_leaver_bank_name = isset($input['care_leaver_bank_name']) ? $input['care_leaver_bank_name'] : '';
        $care_leaver_details->care_leaver_account_name = isset($input['care_leaver_account_name']) ? $input['care_leaver_account_name'] : '';
        $care_leaver_details->care_leaver_sort_code = isset($input['care_leaver_sort_code']) ? $input['care_leaver_sort_code'] : '';
        $care_leaver_details->care_leaver_account_number = isset($input['care_leaver_account_number']) ? $input['care_leaver_account_number'] : '';
        $care_leaver_details->child_type = isset($input['child_type']) ? $input['child_type'] : '';

        if(isset($files['in_care_evidence_file']['size']) && $files['in_care_evidence_file']['size'] <= 1048000)
        {
            $target_directory = "OnboardingModule/learners/{$tr->ob_learner_id}/{$tr->id}/onboarding";
            $valid_extensions = array('csv', 'doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg', 'txt');

            $r = Repository::processFileUploads('in_care_evidence_file', $target_directory, $valid_extensions);
            if(isset($r[0]))
                $care_leaver_details->in_care_evidence_file = $files['in_care_evidence_file']['name'];
        }

        DAO::saveObjectToTable($link, 'ob_learner_care_leaver_details', $care_leaver_details);
    }

    public function saveEmergencyContacts(PDO $link, TrainingRecord $tr, $input)
    {
        DAO::execute($link, "DELETE FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'");
        for($i = 1; $i <= 2; $i++)
        {
            $entry = new stdClass();
            $entry->em_con_seq = $i;
            $entry->tr_id = $tr->id;
            $entry->em_con_title = isset($input['em_con_title'.$i]) ? $input['em_con_title'.$i] : null;
            $entry->em_con_name = isset($input['em_con_name'.$i]) ? $input['em_con_name'.$i] : null;
            $entry->em_con_rel = isset($input['em_con_rel'.$i]) ? $input['em_con_rel'.$i] : null;
            $entry->em_con_tel = isset($input['em_con_tel'.$i]) ? $input['em_con_tel'.$i] : null;
            $entry->em_con_mob = isset($input['em_con_mob'.$i]) ? $input['em_con_mob'.$i] : null;
            $entry->em_con_email = isset($input['em_con_email'.$i]) ? $input['em_con_email'.$i] : null;
            DAO::saveObjectToTable($link, 'ob_learner_emergency_contacts', $entry);
        }
    }

    public function saveCriminalConvictionInformation(PDO $link, TrainingRecord $tr, $input)
    {
        $criminal_conviction_details = $tr->getCriminalConvictionDetails($link);
        $criminal_conviction_details->have_criminal_conviction = isset($input['have_criminal_conviction']) ? $input['have_criminal_conviction'] : 'N';
        $criminal_conviction_details->is_it_motoring_conviction = isset($input['is_it_motoring_conviction']) ? $input['is_it_motoring_conviction'] : 'N';
        $criminal_conviction_details->have_criminal_conviction = $criminal_conviction_details->have_criminal_conviction == '' ? 'N' : $criminal_conviction_details->have_criminal_conviction;
        $criminal_conviction_details->is_it_motoring_conviction = $criminal_conviction_details->is_it_motoring_conviction == '' ? 'N' : $criminal_conviction_details->is_it_motoring_conviction;

        $details = [];
        for($i = 1; $i <= 8; $i++)
        {
            $detail = [
                "co_date_of_conviction{$i}" => isset($input["co_date_of_conviction{$i}"]) ? $input["co_date_of_conviction{$i}"] : '',
                "co_nature_of_offence{$i}" => isset($input["co_nature_of_offence{$i}"]) ? $input["co_nature_of_offence{$i}"] : '',
                "co_sentence{$i}" => isset($input["co_sentence{$i}"]) ? $input["co_sentence{$i}"] : '',
            ];
            $details[] = (object)$detail;
        }
        $criminal_conviction_details->details = json_encode($details);
        $criminal_conviction_details->working_with_agencies = isset($input['working_with_agencies']) ? $input['working_with_agencies'] : 0;
        $criminal_conviction_details->working_with_agencies = $criminal_conviction_details->working_with_agencies == '' ? 'N' : $criminal_conviction_details->working_with_agencies;

        $criminal_conviction_details->details_of_agencies = isset($input['details_of_agencies']) ? $input['details_of_agencies'] : null;

        DAO::saveObjectToTable($link, 'ob_learner_criminal_convictions', $criminal_conviction_details);
    }

    public function checkFileExtension($valid_extensions, $filename)
    {
        if(count($valid_extensions) > 0)
        {
            array_walk($valid_extensions, function(&$item, $key){$item = strtolower($item);}); // convert all valid extensions to lower-case
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $valid_extensions))
            {
                return false;
            }

            return true;
        }
    }

    private function savePriorAttainment(PDO $link, TrainingRecord $tr)
    {
        //save Prior Attainment
        if(DB_NAME == "am_ela")
        {
            DAO::execute($link, "DELETE FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type IN ('g', 'h')");
        }
        else
        {
            DAO::execute($link, "DELETE FROM ob_learners_pa WHERE tr_id = '{$tr->id}'");
        }
        $english = new stdClass();
        $english->tr_id = $tr->id;
        $english->level = isset($_POST['gcse_english_level'])?$_POST['gcse_english_level']:'';
        $english->subject = isset($_POST['gcse_english_subject'])?$_POST['gcse_english_subject']:'';
        $english->p_grade = isset($_POST['gcse_english_grade_predicted'])?$_POST['gcse_english_grade_predicted']:'';
        $english->a_grade = isset($_POST['gcse_english_grade_actual'])?$_POST['gcse_english_grade_actual']:'';
        $english->date_completed = isset($_POST['gcse_english_date_completed'])?$_POST['gcse_english_date_completed']:'';
	$english->evi_available = isset($_POST['gcse_english_evi_avail'])?$_POST['gcse_english_evi_avail']:'';
        $english->q_type = 'g';
        if($english->p_grade != '' || $english->a_grade != '')
            DAO::saveObjectToTable($link, 'ob_learners_pa', $english);
        unset($english);
        $maths = new stdClass();
        $maths->tr_id = $tr->id;
        $maths->level = isset($_POST['gcse_maths_level'])?$_POST['gcse_maths_level']:'';
        $maths->subject = isset($_POST['gcse_maths_subject'])?$_POST['gcse_maths_subject']:'';
        $maths->p_grade = isset($_POST['gcse_maths_grade_predicted'])?$_POST['gcse_maths_grade_predicted']:'';
        $maths->a_grade = isset($_POST['gcse_maths_grade_actual'])?$_POST['gcse_maths_grade_actual']:'';
        $maths->date_completed = isset($_POST['gcse_maths_date_completed'])?$_POST['gcse_maths_date_completed']:'';
	$maths->evi_available = isset($_POST['gcse_maths_evi_avail'])?$_POST['gcse_maths_evi_avail']:'';
        $maths->q_type = 'g';
        if($maths->p_grade != '' || $maths->a_grade != '')
            DAO::saveObjectToTable($link, 'ob_learners_pa', $maths);
        unset($maths);
        $ict = new stdClass();
        $ict->tr_id = $tr->id;
        $ict->level = isset($_POST['gcse_ict_level'])?$_POST['gcse_ict_level']:'';
        $ict->subject = isset($_POST['gcse_ict_subject'])?$_POST['gcse_ict_subject']:'';
        $ict->p_grade = isset($_POST['gcse_ict_grade_predicted'])?$_POST['gcse_ict_grade_predicted']:'';
        $ict->a_grade = isset($_POST['gcse_ict_grade_actual'])?$_POST['gcse_ict_grade_actual']:'';
        $ict->date_completed = isset($_POST['gcse_ict_date_completed'])?$_POST['gcse_ict_date_completed']:'';
	$ict->evi_available = isset($_POST['gcse_ict_evi_avail'])?$_POST['gcse_ict_evi_avail']:'';
        $ict->q_type = 'g';
        if($ict->p_grade != '' || $ict->a_grade != '')
            DAO::saveObjectToTable($link, 'ob_learners_pa', $ict);
        unset($ict);
        for($i = 1; $i <= 15; $i++)
        {
            $objPA = new stdClass();
            $objPA->tr_id = $tr->id;
            $objPA->level = isset($_POST['level'.$i])?$_POST['level'.$i]:'';
            $objPA->subject = isset($_POST['subject'.$i])?substr($_POST['subject'.$i], 0, 79):'';
            $objPA->p_grade= isset($_POST['predicted_grade'.$i])?$_POST['predicted_grade'.$i]:'';
            $objPA->a_grade = isset($_POST['actual_grade'.$i])?$_POST['actual_grade'.$i]:'';
            $objPA->date_completed = isset($_POST['date_completed'.$i])?$_POST['date_completed'.$i]:'';
	    $objPA->evi_available = isset($_POST['evi_available'.$i])?$_POST['evi_available'.$i]:'';
            $objPA->q_type = isset($_POST['q_type'.$i]) ? substr($_POST['q_type'.$i], 0, 3):'';
            if(trim($objPA->level) != '' && trim($objPA->subject) != '')
                DAO::saveObjectToTable($link, 'ob_learners_pa', $objPA);
            unset($objPA);
        }
        $high_level = new stdClass();
        $high_level->tr_id = $tr->id;
        $high_level->level = isset($_POST['high_level'])?$_POST['high_level']:'';
        $high_level->subject = isset($_POST['high_subject'])?$_POST['high_subject']:'h';
        $high_level->q_type = 'h';
        DAO::saveObjectToTable($link, 'ob_learners_pa', $high_level);
    }

    private function saveShiftPattern(PDO $link, TrainingRecord $tr, $data)
    {
        $shifts = new stdClass();
        $shifts->tr_id = $tr->id;

        $week_days = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ];

        foreach($week_days AS $key => $value)
        {
            $start_time = "{$key}_start";
            $end_time = "{$key}_end";
            $shifts->$start_time = isset($_POST[$start_time]) ? $_POST[$start_time] : '';
            $shifts->$end_time = isset($_POST[$end_time]) ? $_POST[$end_time] : '';
	    $shifts->$start_time = $shifts->$start_time == 'NA' ? null : $shifts->$start_time;
	    $shifts->$end_time = $shifts->$end_time == 'NA' ? null : $shifts->$end_time;
        }
        $shifts->shift_pattern_comments = isset($_POST['shift_pattern_comments']) ? $_POST['shift_pattern_comments'] : '';

        DAO::saveObjectToTable($link, "ob_learner_shift_pattern", $shifts);
    }

    private function saveExtraFields(PDO $link, TrainingRecord $tr, $data)
    {
        $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
        if(!isset($extra_info->tr_id))
        {
            $extra_info = new stdClass();
            $ob_learner_extra_details_fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_extra_details");
            foreach($ob_learner_extra_details_fields AS $extra_info_key => $extra_info_value)
                $extra_info->$extra_info_value = null;
        }

        foreach($extra_info AS $key => $value)
        {
            $extra_info->$key = isset($data[$key]) ? $data[$key] : null;
        }
        $extra_info->tr_id = $tr->id;     
        DAO::saveObjectToTable($link, "ob_learner_extra_details", $extra_info);
    }

    public function saveAls(PDO $link, TrainingRecord $tr, $data)
    {
        $save = new stdClass();
        $save->tr_id = $tr->id;
        $save->form_data = [
            'tr_id' => $tr->id,
        ];
        $als_ids = DAO::getSingleColumn($link, "SELECT id FROM lookup_questions_als");
        if(count($als_ids) == 0)
        {
            return;
        }

        foreach($als_ids AS $als_id)
        {
            $als_answer = "answer".$als_id;
            $als_answer_t2 = "t2_answer".$als_id;
            $als_comments = "comments".$als_id;
	        $als_comments_t2 = "t2_comments".$als_id;

            if( isset($data['als_' . $als_answer]) )
            {
                $save->form_data[$als_answer] = $data['als_' . $als_answer];
            }
            if( isset($data['als_' . $als_answer_t2]) )
            {
                $save->form_data[$als_answer_t2] = $data['als_' . $als_answer_t2];
            }
            if( isset($data['als_' . $als_comments]) )
            {
                $save->form_data[$als_comments] = preg_replace('/[^\x00-\x7F]/', '', $data['als_' . $als_comments]);	
            }
	        if( isset($data['als_' . $als_comments_t2]) )
            {
                $save->form_data[$als_comments_t2] = preg_replace('/[^\x00-\x7F]/', '', $data['als_' . $als_comments_t2]);
            }
            if( isset($data['als_funding_year']) )
            {
                $save->form_data['funding_year'] = $data['als_funding_year'];
            }	
        }

        $save->form_data = json_encode($save->form_data);

        if($tr->learner_sign != '')
        {
            $save->learner_sign = $tr->learner_sign;
        }
        if($tr->learner_sign_date != '')
        {
            $save->learner_sign_date = $tr->learner_sign_date = date('Y-m-d');
        }

        DAO::saveObjectToTable($link, "ob_learner_additional_support", $save);
    }
}