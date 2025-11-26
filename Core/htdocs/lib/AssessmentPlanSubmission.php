<?php
class AssessmentPlanSubmission extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes((string)$id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	assessment_plan_log_submissions
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $assessment_plan_log = null;
        if($st)
        {
            $assessment_plan_log = null;
            $row = $st->fetch();
            if($row)
            {
                $assessment_plan_log = new AssessmentPlanSubmission();
                $assessment_plan_log->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find exam result for the training record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $assessment_plan_log;
    }

    public function save(PDO $link)
    {
        $table = 'assessment_plan_log_submissions';

        $pobject = AssessmentPlanSubmission::loadFromDatabase($link, $this->id);
        DAO::saveObjectToTable($link, $table, $this);
        if($this->id!="" and gettype($pobject)=="object")
        {
            Audit::add($link, $pobject, $this, "assessment_plan_log_submissions", "set_date,due_date,submission_date,marked_date,sent_iqa_date,iqa_status,acc_rej_date,learner_feedback_date,feedback_received_date,completion_date");
        }
        return true;
    }

    public function delete(PDO $link)
    {

    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public function getSubmissionCount(PDO $link)
    {
        return DAO::getSingleValue($link, "select count(*) from assessment_plan_log_submissions where assessment_plan_id = '$this->id'");
    }

    public $id = NULL;
    public $assessment_plan_id = NULL;
    public $set_date = NULL;
    public $due_date = NULL;
    public $submission_date = NULL;
    public $marked_date = NULL;
    public $status = NULL;
    public $sent_iqa_date = NULL;
    public $iqa_status = NULL;
    public $acc_rej_date = NULL;
    public $learner_feedback_date = NULL;
    public $feedback_received_date = NULL;
    public $completion_date = NULL;
    public $comments = NULL;
    public $assessor = NULL;
    public $user = NULL;
    public $assessor_signed_off = NULL;
    public $mode = NULL;
    public $iqa_reason = NULL;
    public $assessor_reason = NULL;
    public $system = NULL;
    public $portfolio_enhancement = NULL;
    public $attempt = NULL;
    public $iqa_type = NULL;
    public $strengths = NULL;
    public $actions = NULL;
    public $development = NULL;
    public $iqa = NULL;

    public $iqa_person = NULL;
    public $summative_date = NULL;
    public $rag = NULL;
    public $iqa_feedback = NULL;
    public $actioned = NULL;
    public $summative_date_actioned = NULL;
    public $lm_comments = NULL;


    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>