<?php
class AssessorReviewFormLearner extends Entity
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
	assessor_review_forms_learner
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewFormLearner();
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
        DAO::saveObjectToTable($link, 'assessor_review_forms_learner', $this);
        return DAO::saveObjectToTable($link, 'assessor_review_forms_learner_audit', $this);
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
    public $learner_comment = NULL;
    public $feedback_on_spellings = NULL;
    public $equality_and_diversity = NULL;
    public $what_training = NULL;
    public $what_future = NULL;
    public $what_support = NULL;
    public $feel_safe = NULL;
    public $accident = NULL;
    public $changes = NULL;
    public $health = NULL;
    public $signature_learner_font = NULL;
    public $signature_learner_name = NULL;
    public $signature_learner_date = NULL;
    public $autosave = NULL;


    public $appeals = NULL;
    public $learner_comments = NULL;

}
?>