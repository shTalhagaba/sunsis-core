<?php
class AssessorReviewFormAssessor2 extends Entity
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
	assessor_review_forms_assessor2
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewFormAssessor2();
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
        DAO::saveObjectToTable($link, 'assessor_review_forms_assessor2', $this);
        return DAO::saveObjectToTable($link, 'assessor_review_forms_assessor2_audit', $this);
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
    public $equality_diversity = NULL;
    public $safeguarding = NULL;
    public $prevent = NULL;
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
    public $progress_target = NULL;
    public $main_progress = NULL;
    public $autosave = NULL;

    public $smart_target_1 = NULL;
    public $smart_target_2 = NULL;
    public $smart_target_3 = NULL;
    public $smart_target_4 = NULL;
    public $smart_target_5 = NULL;
    public $smart_target_6 = NULL;
    public $smart_target_7 = NULL;
    public $smart_target_date_1 = NULL;
    public $smart_target_date_2 = NULL;
    public $smart_target_date_3 = NULL;
    public $smart_target_date_4 = NULL;
    public $smart_target_date_5 = NULL;
    public $smart_target_date_6 = NULL;
    public $smart_target_date_7 = NULL;
    public $progression_with_qualification = NULL;
    public $main_aim_percentage = NULL;
    public $sub_aim_percentage = NULL;
    public $combined_aim_percentage = NULL;

    public $created_by = NULL;


}
?>