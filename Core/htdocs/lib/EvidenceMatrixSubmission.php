<?php
class EvidenceMatrixSubmission extends Entity
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
	project_submissions
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
                $assessment_plan_log = new EvidenceMatrixSubmission();
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
        $table = 'project_submissions';

        $pobject = EvidenceMatrixSubmission::loadFromDatabase($link, $this->id);
        DAO::saveObjectToTable($link, $table, $this);
        if($this->id!="" and gettype($pobject)=="object")
        {
            Audit::add($link, $pobject, $this, "project_submissions", "set_date,due_date,submission_date,marked_date,sent_iqa_date,iqa_status,acc_rej_date,learner_feedback_date,feedback_received_date,completion_date");
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
        return DAO::getSingleValue($link, "select count(*) from project_submissions where project_id = '$this->id'");
    }

    public static function allowedToEdit()
    {		
        if($_SESSION['user']->isAdmin() or in_array($_SESSION['user']->username, Array("sellison1", "hcoatesa","kpattisona","mthompson16","kmalcolm16","davmiller","bblackett1","aspence1","cthomas1","dmartindale","caddison1","lbaggot1","lbroom12")))
            return true;
        else
            return false;
    }

    public $id = NULL;
    public $project_id = NULL;
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
    public $matrix = NULL;
    public $iqa_recheck_date = NULL;

    public $iqa_person = NULL;
    public $summative_date = NULL;
    public $rag = NULL;
    public $iqa_feedback = NULL;
    public $actioned = NULL;
    public $summative_date_actioned = NULL;
    public $lm_comments = NULL;
    public $iqa = NULL;
    public $recommendations_comments = NULL;
    public $recommendations_type = NULL;
    public $feedback_summary = NULL;
    public $iqa_rework_awaiting_marking = NULL;
    public $word_count = NULL;
    public $page_count = NULL;
    public $reduced_projects = NULL;
    public $summative_status = NULL;
    public $extension_date = NULL;
    public $extension_reason = NULL;
    public $iqa_rag = null;

    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>