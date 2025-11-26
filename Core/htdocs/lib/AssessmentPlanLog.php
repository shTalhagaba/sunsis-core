<?php
class AssessmentPlanLog extends Entity
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
	assessment_plan_log
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
                $assessment_plan_log = new AssessmentPlanLog();
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

        return DAO::saveObjectToTable($link, 'assessment_plan_log', $this);
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

    public static function isReduction(PDO $link, $assessor_id, $framework_id, $mode)
    {
        $attempts = DAO::getSingleColumn($link, "SELECT attempt FROM assessment_plan_log_submissions
                    LEFT JOIN assessment_plan_log ON assessment_plan_log.`id` = assessment_plan_log_submissions.`assessment_plan_id`
                    LEFT JOIN tr ON assessment_plan_log.`tr_id` = tr.id
                    LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = tr.`id`
                    WHERE assessment_plan_log_submissions.assessor = $assessor_id
                    AND student_frameworks.`id` = $framework_id AND assessment_plan_log.`mode` = $mode
                    AND completion_date IS NOT NULL
                    ORDER BY completion_date DESC LIMIT 5;");
        if(array_sum($attempts)==5)
            return "Yes";
        else
            return "No";
    }


    public $id = NULL;
    public $tr_id = NULL;
    public $due_date = NULL;
    public $actual_date = NULL;
    public $assessor = NULL;
    public $mode = NULL;
    public $traffic = NULL;
    public $paperwork = NULL;
    public $comments = NULL;
	public $marked_date = NULL;
    public $marked_date2 = NULL;
    public $marked_date3 = NULL;
    public $signed_off_date = NULL;

    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>