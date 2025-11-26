<?php
class AssessorReviewForm extends Entity
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
	assessor_review_forms
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new AssessorReviewForm();
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
        /*if(!isset($this->active))
            $this->active=0;

        if(!isset($this->funding_type))
            $this->funding_type=0;

        if(!isset($this->funded))
            $this->funded=0;
        */

        return DAO::saveObjectToTable($link, 'assessor_review_forms', $this);
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
    public $learner_dob = NULL;
    public $learner_assessor = NULL;
    public $learner_ni = NULL;
    public $learner_employer = NULL;
    public $learner_manager = NULL;
    public $learner_programme = NULL;
    public $learner_qualification = NULL;
    public $start_date = NULL;
    public $registration_number = NULL;
    public $planned_end_date = NULL;
    public $review_date = NULL;

    public $plagiarism = NULL;
    public $employer_previous_comments = NULL;
    public $significant_achievement = NULL;
    public $equality_diversity = NULL;
    public $safeguarding = NULL;
    public $prevent = NULL;
    public $health_wellbeing = NULL;
    public $concerns = NULL;
    public $commitment = NULL;
    public $additional_support = NULL;
    public $progress = NULL;
    public $discussion = NULL;
    public $err = NULL;

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
    public $learner_comment = NULL;
    public $employer_progress_review = NULL;
    public $attendance_poor = NULL;
    public $attendance_satisfactory = NULL;
    public $attendance_good = NULL;
    public $attendance_excellent = NULL;
    public $punctuality_poor = NULL;
    public $punctuality_satisfactory = NULL;
    public $punctuality_good = NULL;
    public $punctuality_excellent = NULL;
    public $attitude_poor = NULL;
    public $attitude_satisfactory = NULL;
    public $attitude_good = NULL;
    public $attitude_excellent = NULL;
    public $communication_poor = NULL;
    public $communication_satisfactory = NULL;
    public $communication_good = NULL;
    public $communication_excellent = NULL;
    public $enthusiasm_poor = NULL;
    public $enthusiasm_satisfactory = NULL;
    public $enthusiasm_good = NULL;
    public $enthusiasm_excellent = NULL;
    public $commitment_poor = NULL;
    public $commitment_satisfactory = NULL;
    public $commitment_good = NULL;
    public $commitment_excellent = NULL;
    public $behaviours = NULL;
    public $ability = NULL;
    public $skills_knowledge = NULL;
    public $achievements = NULL;
    public $signature_learner_font = NULL;
    public $signature_learner_name = NULL;
    public $signature_learner_date = NULL;
    public $signature_assessor_font = NULL;
    public $signature_assessor_name = NULL;
    public $signature_assessor_date = NULL;
    public $signature_employer_font = NULL;
    public $signature_employer_name = NULL;
    public $signature_employer_date = NULL;
    public $attendance = NULL;
    public $punctuality = NULL;
    public $attitude = NULL;
    public $communication = NULL;
    public $enthusiasm = NULL;
    public $commitment2 = NULL;

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