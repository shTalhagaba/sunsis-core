<?php
class AssessmentPlanLog2 extends Entity
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
                $assessment_plan_log = new AssessmentPlanLog2();
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