<?php
class AssessorReviewFormAssessor3 extends Entity
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
	assessor_review_forms_assessor3
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewFormAssessor3();
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
        DAO::saveObjectToTable($link, 'assessor_review_forms_assessor3', $this);
        return DAO::saveObjectToTable($link, 'assessor_review_forms_assessor3_audit', $this);
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
    public $health_wellbeing = NULL;
    public $concerns = NULL;
    public $commitment = NULL;

    public $tech_progress = NULL;
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
    public $autosave = NULL;

    public $functional_skills = NULL;
    public $english_to_be = NULL;
    public $math_to_be = NULL;
    public $ict_to_be = NULL;
    public $english_completed = NULL;
    public $math_completed = NULL;
    public $ict_completed = NULL;
    public $internal_training = NULL;
    public $hours_to_be_added = NULL;
    public $err_completed = NULL;
    public $plts_embedded = NULL;
    public $plan_for_next_assessment = NULL;
    public $date_time_next_visit = NULL;
    public $aln = NULL;
    public $asn = NULL;
    public $alsn = NULL;
    public $other = NULL;
    public $support_since_last_review = NULL;

    public $created_by = NULL;


}
?>