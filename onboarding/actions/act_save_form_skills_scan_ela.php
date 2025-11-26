<?php
class save_form_skills_scan_ela implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($key) != '')
        {
            $id = OnboardingHelper::getSkillsAnalysisIdFromKey($link, $key);
            if($id == '')
            {
                http_redirect("do.php?_action=error_page");
            }
        }
        else
        {
            http_redirect("do.php?_action=error_page");
        }

        $skills_analysis = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $tr = TrainingRecord::loadFromDatabase($link, $skills_analysis->tr_id);
        $ob_learner = OnboardingLearner::loadFromDatabase($link, $tr->ob_learner_id);

        if(isset($skills_analysis->signed_by_learner) && $skills_analysis->signed_by_learner == 1)
        {
            OnboardingHelper::generateAlreadyCompletedPage($link);
            exit;
        }

        $skills_analysis->is_completed_by_learner = isset($_POST['is_completed_by_learner']) ? $_POST['is_completed_by_learner'] : 'N';
        DAO::transaction_start($link);
        try
        {
            $this->savePriorAttainment($link, $tr);
            $this->saveEmploymentHistory($link, $tr);

	        $ksb_updated_entries = [];	

            foreach($skills_analysis->ksb AS &$row)
            {
                if($skills_analysis->lock_for_learner)
                {
                    $score = $row['score'];
                    $comments = $row['comments'];
                }
                else
                {
                    $score = isset($_POST["score_{$row['id']}"]) ? $_POST["score_{$row['id']}"] : $row['score'];
                    $comments = isset($_POST["comments_{$row['id']}"]) ? $_POST["comments_{$row['id']}"] : $row['comments'];
                }

                $ksb_updated_entries[] = [
                    'id' => $row['id'],
                    'unit_group' => $row['unit_group'],
                    'unit_title' => $row['unit_title'],
                    'evidence_title' => $row['evidence_title'],
                    'score' => 'old value: ' . $row['score'] . ' | new value: ' . $score,
                    'comments' => 'old value: ' . $row['comments'] . ' | new value: ' . $comments,
                ];
            
                // update the score and comments
                $row['score'] = $score;
                $row['comments'] = $comments;
            }

            if($skills_analysis->is_completed_by_learner == 'Y')
            {
                $skills_analysis->signed_by_learner = 1;
                $skills_analysis->learner_sign = $_POST['learner_sign'];
                $skills_analysis->learner_sign_date = date('Y-m-d');

                $ksb_log = (object)[
                    'tr_id' => $tr->id,
                    'updated_by' => '0000',
                    'updated_detail' => json_encode($ksb_updated_entries),
                    'skills_analysis_id' => $skills_analysis->id,
                ];
                DAO::saveObjectToTable($link, 'ob_learner_ksb_log', $ksb_log);
            }

            if($skills_analysis->lock_for_learner == 1 && $skills_analysis->is_completed_by_learner == 'Y')
            {
                // if skills analysis is locked for the learner then do not change anything for SA just save the learner sign and date
                $skills_analysis_id = $skills_analysis->id;
                $skills_analysis = null;
                $skills_analysis = SkillsAnalysis::loadFromDatabaseById($link, $skills_analysis_id);
                $skills_analysis->is_completed_by_learner = 'Y';
                $skills_analysis->signed_by_learner = 1;
                $skills_analysis->learner_sign = $_POST['learner_sign'];
                $skills_analysis->learner_sign_date = date('Y-m-d');
            }
            $skills_analysis->save($link);
            DAO::multipleRowInsert($link, 'ob_learner_ksb', $skills_analysis->ksb);

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        if($skills_analysis->is_completed_by_learner == 'N')
        {
            echo 'saved successfully';
            return;
        }

        if(!SOURCE_LOCAL)
        {
            $this->sendEmailToLearner($link, $tr);
            $this->sendEmailToTrainer($link, $tr);
        }

        $_POST = null;
        unset($_POST);

        http_redirect('do.php?_action=cs_completed&k='.md5('sunesis_cs_form_completed_for_'.$skills_analysis->id));
    }

    private function saveEmploymentHistory(PDO $link, TrainingRecord $tr)
    {
        DAO::execute($link, "DELETE FROM ob_learners_ea WHERE tr_id = '{$tr->id}'");
        for($i = 1; $i <= 8; $i++)
        {
            $objEA = new stdClass();
            $objEA->tr_id = $tr->id;
            $objEA->ea_date_from = isset($_POST['ea_date_from'.$i])?$_POST['ea_date_from'.$i]:'';
            $objEA->ea_date_to = isset($_POST['ea_date_to'.$i])?$_POST['ea_date_to'.$i]:'';
            $objEA->ea_employer = isset($_POST['ea_employer'.$i]) ? substr($_POST['ea_employer'.$i], 0, 149):'';
            $objEA->ea_role = isset($_POST['ea_role'.$i]) ? substr($_POST['ea_role'.$i], 0, 149):'';
            $objEA->ea_resp = isset($_POST['ea_resp'.$i]) ? substr($_POST['ea_resp'.$i], 0, 149):'';
            if(trim($objEA->ea_date_from) != '' && trim($objEA->ea_employer) != '' && Date::isDate($objEA->ea_date_from) && Date::isDate($objEA->ea_date_to))
                DAO::saveObjectToTable($link, 'ob_learners_ea', $objEA);
            unset($objEA);
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

        $tr->fs_eng_opt_in = isset($_POST['fs_eng_opt_in']) ? $_POST['fs_eng_opt_in'] : '';
        $tr->fs_eng_opt_out_reason = isset($_POST['fs_eng_opt_out_reason']) ? substr($_POST['fs_eng_opt_out_reason'], 0, 255) : '';
        $tr->fs_maths_opt_in = isset($_POST['fs_maths_opt_in']) ? $_POST['fs_maths_opt_in'] : '';
        $tr->fs_maths_opt_out_reason = isset($_POST['fs_maths_opt_out_reason']) ? substr($_POST['fs_maths_opt_out_reason'], 0, 255) : '';
        $tr->save($link);
    }

    public function sendEmailToTrainer(PDO $link, TrainingRecord $tr)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMAIL_TO_TRAINER_FOLLOWING_SKILLS_SCAN_SUBMITTED_BY_LEARNER' ");
        if($email_content == '')
            return;

        $trainers = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
        $trainer_id = isset($trainers[0]) ? $trainers[0] : '';
        if($trainer_id == '')
            return;

        $trainer_record = DAO::getObject($link, "SELECT firstnames, surname, work_email FROM users WHERE users.id = '{$trainer_id}'");

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, 'EMAIL_TO_TRAINER_FOLLOWING_SKILLS_SCAN_SUBMITTED_BY_LEARNER', $tr);

        Emailer::notification_email($trainer_record->work_email,
            'no-reply@perspective-uk.com',
            '',
            'Skills scan submitted',
            '',
            $ready_template
        );

    }

    public function sendEmailToLearner(PDO $link, TrainingRecord $tr)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMAIL_TO_LEARNER_FOLLOWING_SKILLS_SCAN_COMPLETED' ");
        if($email_content == '')
            return;

        $ob_learner = $tr->getObLearnerRecord($link);

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, 'EMAIL_TO_LEARNER_FOLLOWING_SKILLS_SCAN_COMPLETED', $tr);

        Emailer::notification_email($ob_learner->home_email,
            'no-reply@perspective-uk.com',
            '',
            'Skills scan complete',
            '',
            $ready_template
        );

    }

}
