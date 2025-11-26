<?php
class AssessorReviewFormAssessor4 extends Entity
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
	assessor_review_forms_assessor4
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewFormAssessor4();
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
        DAO::saveObjectToTable($link, 'assessor_review_forms_assessor4', $this);
        return DAO::saveObjectToTable($link, 'assessor_review_forms_assessor4_audit', $this);
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

    public $additional_support = NULL;
    public $progress = NULL;
    public $discussion = NULL;
    public $err = NULL;

    public $english_exempt = NULL;
    public $math_exempt = NULL;
    public $ict_exempt = NULL;

    public $use_functional = NULL;
    public $english_l1 = NULL;
    public $english_l2 = NULL;
    public $math_l1 = NULL;
    public $math_l2 = NULL;
    public $ict_l1 = NULL;
    public $ict_l2 = NULL;
    public $plts = NULL;
    public $functional_progress = NULL;
    public $specific = NULL;
    public $measurable = NULL;
    public $achievable = NULL;
    public $timebound = NULL;
    public $next_contact = NULL;

    public $signature_assessor_font = NULL;
    public $signature_assessor_name = NULL;
    public $signature_assessor_date = NULL;

    // Fields after 1st May
    public $knowledge_module_1 = NULL;
    public $knowledge_module_2 = NULL;
    public $knowledge_module_3 = NULL;
    public $knowledge_module_4 = NULL;
    public $knowledge_module_5 = NULL;
    public $knowledge_module_6 = NULL;
    public $knowledge_module_7 = NULL;
    public $knowledge_module_8 = NULL;
    public $knowledge_module_9 = NULL;
    public $knowledge_module_10 = NULL;
    public $knowledge_module_11 = NULL;
    public $knowledge_module_12 = NULL;
    public $knowledge_status_1 = NULL;
    public $knowledge_status_2 = NULL;
    public $knowledge_status_3 = NULL;
    public $knowledge_status_4 = NULL;
    public $knowledge_status_5 = NULL;
    public $knowledge_status_6 = NULL;
    public $knowledge_status_7 = NULL;
    public $knowledge_status_8 = NULL;
    public $knowledge_status_9 = NULL;
    public $knowledge_status_10 = NULL;
    public $knowledge_status_11 = NULL;
    public $knowledge_status_12 = NULL;
    public $knowledge_module = NULL;
    public $workplace_competence_1 = NULL;
    public $workplace_competence_2 = NULL;
    public $workplace_competence_3 = NULL;
    public $workplace_competence_4 = NULL;
    public $workplace_competence_5 = NULL;
    public $workplace_competence_6 = NULL;
    public $workplace_competence_7 = NULL;
    public $workplace_competence_8 = NULL;
    public $workplace_competence_9 = NULL;
    public $workplace_competence_10 = NULL;
    public $workplace_competence_11 = NULL;
    public $workplace_competence_12 = NULL;
    public $workplace_status_1 = NULL;
    public $workplace_status_2 = NULL;
    public $workplace_status_3 = NULL;
    public $workplace_status_4 = NULL;
    public $workplace_status_5 = NULL;
    public $workplace_status_6 = NULL;
    public $workplace_status_7 = NULL;
    public $workplace_status_8 = NULL;
    public $workplace_status_9 = NULL;
    public $workplace_status_10 = NULL;
    public $workplace_status_11 = NULL;
    public $workplace_status_12 = NULL;
    public $workplace_competence = NULL;
    public $present = NULL;
    public $autosave = NULL;


    public $results_support = NULL;
    public $learner_welfare = NULL;
    public $welfare_none = NULL;
    public $welfare_wf = NULL;
    public $welfare_sg = NULL;
    public $iag = NULL;
    public $health_safety = NULL;
    public $safeguarding = NULL;
    public $equality = NULL;
    public $on_track = NULL;
    public $portfolio = NULL;
    public $why_portfolio_behind = NULL;
    public $issue = NULL;
    public $date_reported = NULL;
    public $case_number = NULL;
    public $assessor_feedback = NULL;

    public $created_by = NULL;


}
?>