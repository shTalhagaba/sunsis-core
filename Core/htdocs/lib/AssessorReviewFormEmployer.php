<?php
class AssessorReviewFormEmployer extends Entity
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
	assessor_review_forms_employer
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewFormEmployer();
            $row = $st->fetch();
            if($row)
            {
                $form->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
        }

        return $form;
    }

    public function save(PDO $link)
    {
        DAO::saveObjectToTable($link, 'assessor_review_forms_employer', $this);
        return DAO::saveObjectToTable($link, 'assessor_review_forms_employer_audit', $this);
    }

    public function delete(PDO $link)
    {
        // Placeholder
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }


    public $review_id = null;
    public $tr_id = NULL;
    public $signature_employer_font = NULL;
    public $signature_employer_name = NULL;
    public $signature_employer_date = NULL;
    public $employer_progress_review = NULL;
    public $attendance = NULL;
    public $punctuality = NULL;
    public $attitude = NULL;
    public $communication = NULL;
    public $enthusiasm = NULL;
    public $commitment2 = NULL;
    public $behaviours = NULL;
    public $increase_in_confidence = NULL;
    public $good_attendance = NULL;
    public $creativity = NULL;

    public $ability = NULL;
    public $skills_knowledge = NULL;
    public $achievements = NULL;
    public $autosave = NULL;

    public $attended_regularly = NULL;
    public $unauthorised_absences = NULL;
    public $days_absence = NULL;
    public $sick = NULL;
    public $sick_days = NULL;
    public $time_keeping = NULL;
    public $employer_comments = NULL;
    public $other_comments = NULL;

}
?>