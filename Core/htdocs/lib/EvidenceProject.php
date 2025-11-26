<?php
class EvidenceProject extends Entity
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
	tr_projects
WHERE
	id='$key';
HEREDOC;


        $st = $link->query($query);

        $evidence_project = null;
        if($st)
        {
            $evidence_project = null;
            $row = $st->fetch();
            if($row)
            {
                $evidence_project = new EvidenceProject();
                $evidence_project->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find exam result for the training record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $evidence_project;
    }

    public function save(PDO $link)
    {
        return DAO::saveObjectToTable($link, 'tr_projects', $this);
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

    public $id = NULL;
    public $tr_id = NULL;
    public $due_date = NULL;
    public $actual_date = NULL;
    public $assessor = NULL;
    public $project_id = NULL;
    public $project = NULL;
    public $traffic = NULL;
    public $paperwork = NULL;
    public $comments = NULL;
    public $marked_date = NULL;
    public $marked_date2 = NULL;
    public $marked_date3 = NULL;
    public $signed_off_date = NULL;
    public $iqa_person = NULL;
    public $summative_date = NULL;
    public $rag_summative = NULL;
    public $iqa_feedback = NULL;
    public $sample_type = NULL;
    public $actioned = NULL;
    public $summative_date_actioned= NULL;
    public $lm_comments = NULL;
    public $completion_risk = NULL;
    public $justification = NULL;
    public $manager_sign_off = NULL;

    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>