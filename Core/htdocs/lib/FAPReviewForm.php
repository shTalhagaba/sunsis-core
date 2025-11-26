<?php
class FAPReviewForm extends Entity
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
	fap_review_forms
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new FAPReviewForm();
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

        return DAO::saveObjectToTable($link, 'fap_review_forms', $this);
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
    public $learner_programme = NULL;
    public $learner_assessor = NULL;
    public $review_date = NULL;
    public $feedback_summary_notes = NULL;
    public $general_feedback = NULL;
    public $significant_achievement = NULL;
    public $equality_diversity = NULL;
    public $commitment = NULL;
    public $prevent = NULL;
    public $english_exempt = NULL;

    public $main_name_unit1 = NULL;
    public $main_name_unit2 = NULL;
    public $main_name_unit3 = NULL;
    public $main_name_unit4 = NULL;
    public $main_name_unit5 = NULL;
    public $main_name_unit6 = NULL;
    public $main_name_unit7 = NULL;
    public $main_name_unit8 = NULL;
    public $main_name_unit9 = NULL;
    public $main_name_unit10 = NULL;
    public $main_name_unit11 = NULL;
    public $main_name_unit12 = NULL;
    public $main_perc_unit1 = NULL;
    public $main_perc_unit2 = NULL;
    public $main_perc_unit3 = NULL;
    public $main_perc_unit4 = NULL;
    public $main_perc_unit5 = NULL;
    public $main_perc_unit6 = NULL;
    public $main_perc_unit7 = NULL;
    public $main_perc_unit8 = NULL;
    public $main_perc_unit9 = NULL;
    public $main_perc_unit10 = NULL;
    public $main_perc_unit11 = NULL;
    public $main_perc_unit12 = NULL;
    public $workshop1 = NULL;
    public $workshop2 = NULL;
    public $workshop3 = NULL;
    public $main_feedback = NULL;
    public $tech_feedback = NULL;
    public $tech_name_unit1 = NULL;
    public $tech_name_unit2 = NULL;
    public $tech_name_unit3 = NULL;
    public $tech_name_unit4 = NULL;
    public $tech_name_unit5 = NULL;
    public $tech_name_unit6 = NULL;
    public $tech_name_unit7 = NULL;
    public $tech_name_unit8 = NULL;
    public $tech_name_unit9 = NULL;
    public $tech_name_unit10 = NULL;
    public $tech_name_unit11 = NULL;
    public $tech_name_unit12 = NULL;
    public $tech_perc_unit1 = NULL;
    public $tech_perc_unit2 = NULL;
    public $tech_perc_unit3 = NULL;
    public $tech_perc_unit4 = NULL;
    public $tech_perc_unit5 = NULL;
    public $tech_perc_unit6 = NULL;
    public $tech_perc_unit7 = NULL;
    public $tech_perc_unit8 = NULL;
    public $tech_perc_unit9 = NULL;
    public $tech_perc_unit10 = NULL;
    public $tech_perc_unit11 = NULL;
    public $tech_perc_unit12 = NULL;
    public $err = NULL;
    public $math_exempt = NULL;
    public $ict_exempt = NULL;
    public $functional_feedback = NULL;
    public $learner_log = NULL;
    public $next_contact = NULL;
    public $signature_assessor_font = NULL;
    public $signature_assessor_name = NULL;
    public $signature_assessor_date = NULL;
    public $next_objectives = NULL;

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

}
?>