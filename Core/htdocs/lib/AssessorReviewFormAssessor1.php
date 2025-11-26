<?php
class AssessorReviewFormAssessor1 extends Entity
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
	assessor_review_forms_assessor1
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewFormAssessor1();
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
        $this->created_by = isset($_SESSION['user']->username) ? $_SESSION['user']->username : '';
        DAO::saveObjectToTable($link, 'assessor_review_forms_assessor1', $this);
        return DAO::saveObjectToTable($link, 'assessor_review_forms_assessor1_audit', $this);
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
    public $learner_name = NULL;
    public $learner_qualification = NULL;
    public $learner_assessor = NULL;
    public $learner_employer = NULL;
    public $learner_iqa = NULL;
    public $learner_funder = NULL;
    public $review_date = NULL;
    public $planned_date = NULL;
    public $learner_framework = NULL;
    public $time_in = NULL;
    public $time_out = NULL;
    public $plagiarism = NULL;
    public $type_of_contact = NULL;
    public $rags = NULL;
    public $objectives = NULL;
    public $autosave = NULL;
    public $learner_dob = NULL;
    public $learner_ni = NULL;
    public $learner_manager = NULL;
    public $learner_programme = NULL;
    public $start_date = NULL;
    public $registration_number = NULL;
    public $planned_end_date = NULL;

    public $employer_previous_comments = NULL;
    public $significant_achievement = NULL;
    public $created_by = NULL;

}
?>