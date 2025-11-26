<?php
class save_ks_assessment implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $check_mandatory_fields = $this->validateMandatoryFields($link, $_POST);
        if(!$check_mandatory_fields)
        {
            throw new Exception("Your action is invalid, cannot continue.");
        }

        $id = isset($_POST['learner_id']) ? $_POST['learner_id'] : '';
        $url_key = isset($_POST['key']) ? $_POST['key'] : '';
        $forwarding = isset($_POST['forwarding']) ? $_POST['forwarding'] : '';

        if($id == '' || !is_numeric($id))
        {
            throw new Exception("Missing or invalid querystring argument: id");
        }
        //referrer validation
        if(!isset($_SERVER['HTTP_REFERER']))
        {
            throw new Exception("Invalid access.");
        }
        else
        {
            $qs = $_SERVER['QUERY_STRING'];
            $qs = explode('&', $qs);
            $url = SOURCE_LOCAL ? 'http://' : 'https://';
            $url .= SOURCE_LOCAL ? 'sunesis/' : DB_NAME;

            $url .= "do.php?_action=ks_assessment&id={$id}&forwarding={$forwarding}&key={$url_key}";
            if($_SERVER['HTTP_REFERER'] != $url && !in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
            {
                throw new Exception("Invalid access.");
            }
        }

        if(!OnboardingHelper::isValidKey($link, $id, $url_key))
        {
            http_redirect('do.php?_action=form_error');
        }

        // check if key is form completed key
        if(OnboardingHelper::isFormCompletedKey($link, $id, $forwarding, $url_key))
        {
            http_redirect('do.php?_action=form_already_completed');
        }

        // if valid key then check if the form is already completed
        $already_completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ks_assessment WHERE ob_learner_id = '{$id}' AND is_finished = 'Y'");
        if($already_completed)
        {
            http_redirect('do.php?_action=form_already_completed');
        }

        $questions_k = $this->getQuestions($link, $_POST['assessment_type'], 'k');
        $questions_s = $this->getQuestions($link, $_POST['assessment_type'], 's');
        $questions_p = $this->getQuestions($link, $_POST['assessment_type'], 'p');

        $assessment = new stdClass();
        $assessment->ob_learner_id = $_POST['learner_id'];
        $assessment->assessment_type = $_POST['assessment_type'];
        $assessment->k_qs = [];
        $assessment->s_qs = [];
        $assessment->pd_qs = [];
        $assessment->p_qs = [];
        $assessment->your_role = isset($_POST['your_role']) ? $_POST['your_role'] : '';
        $assessment->job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
        $assessment->is_finished = isset($_POST['is_finished']) ? $_POST['is_finished'] : '';

        $k_qs = [];
        foreach($questions_k AS $q_id)
        {
            $k_qs[$q_id] = $_POST[$q_id];
        }
        $assessment->k_qs = json_encode($k_qs);

        $s_qs = [];
        foreach($questions_s AS $q_id)
        {
            $s_qs[$q_id] = $_POST[$q_id];
        }
        $assessment->s_qs = json_encode($s_qs);

        $pd_qs = [];
        foreach($_POST AS $key => $value)
        {
            if(substr($key, 0, 2) != 'pd')
                continue;

            $pd_qs[$key] = $value;
        }
        $assessment->pd_qs = json_encode($pd_qs);

        $p_qs = [];
        foreach($questions_p AS $q_id)
        {
            $p_qs[$q_id] = $_POST[$q_id];
        }
        $assessment->p_qs = json_encode($p_qs);

        DAO::transaction_start($link);
        try{
            DAO::saveObjectToTable($link, 'ks_assessment', $assessment);

            $log = new OnboardingLogger();
            $log->subject = 'K & S COMPLETED BY LEARNER';
            $log->note = "Learner has completed and finished the knowledge & skills assessment form.";
            $log->ob_learner_id = $assessment->ob_learner_id;
            $log->by_whom = $assessment->ob_learner_id;
            $log->save($link);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new Exception($e->getMessage());
        }

        if(SystemConfig::getEntityValue($link, 'ob_send_notifications'))
        {
            $this->sendEmailToCoach($link, $assessment->ob_learner_id);
        }

        $_POST = null;
        unset($_POST);

        http_redirect('do.php?_action=form_completed');

    }

    public function sendEmailToCoach(PDO $link, $ob_learner_id)
    {
        if($ob_learner_id == '')
            return;

        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");
        if(!isset($ob_learner->id))
            return;

        if($ob_learner->coach == '')
            return;

        $to_email = SystemConfig::getEntityValue($link, "onboarding_email");
        if($to_email == '')
            return;

        $message = <<<MESSAGE
<p>Hi</p>

<p>Re: {$ob_learner->firstnames} {$ob_learner->surname}</p>

<p>The above learner has completed the Knowledge & Skills Assessment. Please login to Sunesis and check.</p>

<p>Many thanks</p>

<p><img height="50" src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." /></p>

MESSAGE;

        if(!SOURCE_LOCAL)
        {
            Emailer::notification_email($to_email, 'no-reply@perspective-uk.com', '', 'Learner K&S Assessment Notification Email ', '', $message);
        }

    }

    public function getQuestions(PDO $link, $assessment_type, $question_type)
    {
        $sql = <<<SQL
SELECT CONCAT('q',assessment_type, question_id)
FROM lookup_ks_questions
WHERE assessment_type = '{$assessment_type}' AND question_type = '{$question_type}'
ORDER BY question_id
SQL;
        $questions = DAO::getSingleColumn($link, $sql);
        return $questions;
    }

    private function validateKey(PDO $link, $learner_id, $key)
    {
        return true;
    }

    private function validateMandatoryFields(PDO $link, $data)
    {
        if(!is_array($data))
            return false;

        $fields = ["learner_id", "key", "assessment_type"];
        foreach($fields AS $f)
        {
            if(!isset($data[$f]))
                return false;
        }
        foreach($fields AS $f)
        {
            if(trim($data[$f]) == '')
                return false;
        }

        if(!is_numeric($data["learner_id"]))
            return false;

        return true;
    }
}
