<?php
class ARFIntroduction extends Entity
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
arf_introduction
WHERE
	review_id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new ARFIntroduction();
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
        DAO::saveObjectToTable($link, 'arf_introduction', $this);
        return DAO::saveObjectToTable($link, 'arf_introduction_audit', $this);
    }

    public function delete(PDO $link)
    {
        // Placeholder
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public static function isIntroductionReview($link, $review_id)
    {
        return DAO::getSingleValue($link, "select count(*) from assessor_review where id = '$review_id' and template_review = 1");
    }

    public $review_id = null;
    public $tr_id = NULL;
    public $learner_name = NULL;
    public $learner_assessor = NULL;
    public $learner_employer = NULL;
    public $learner_manager = NULL;
    public $learner_programme = NULL;
    public $start_date = NULL;
    public $planned_end_date = NULL;
    public $review_date = NULL;
    public $introduction = NULL;
    public $skill_scan = NULL;
    public $progress_review = NULL;
    public $technical_training = NULL;
    public $assessment = NULL;
    public $functional_skills = NULL;
    public $end_point_assessment = NULL;
    public $skilsure = NULL;
    public $skilsure2 = NULL;
    public $setting_work = NULL;
    public $learner_concerns = NULL;
    public $apprenticeship_commitment = NULL;

    public $skills_scan_status1 = NULL;
    public $skills_scan_status2 = NULL;
    public $skills_scan_status3 = NULL;
    public $skills_scan_status4 = NULL;
    public $skills_scan_status5 = NULL;
    public $skills_scan_status6 = NULL;
    public $skills_scan_status7 = NULL;
    public $skills_scan_status8 = NULL;
    public $skills_scan_status9 = NULL;
    public $skills_scan_status10 = NULL;
    public $skills_scan_status11 = NULL;
    public $skills_scan_status12 = NULL;
    public $skills_scan_status13 = NULL;
    public $skills_scan_status14 = NULL;
    public $skills_scan_status15 = NULL;
    public $skills_scan_status16 = NULL;
    public $skills_scan_status17 = NULL;
    public $skills_scan_status18 = NULL;
    public $skills_scan_status19 = NULL;
    public $skills_scan_status20 = NULL;
    public $skills_scan_status21 = NULL;
    public $skills_scan_status22 = NULL;
    public $skills_scan_status23 = NULL;
    public $skills_scan_status24 = NULL;
    public $skills_scan_status25 = NULL;
    public $skills_scan_status26 = NULL;
    public $skills_scan_status27 = NULL;
    public $skills_scan_status28 = NULL;
    public $skills_scan_status29 = NULL;
    public $skills_scan_status30 = NULL;
    public $skills_scan_status31 = NULL;
    public $skills_scan_status32 = NULL;
    public $skills_scan_status33 = NULL;
    public $skills_scan_status34 = NULL;
    public $skills_scan_status35 = NULL;
    public $skills_scan_status36 = NULL;
    public $skills_scan_status37 = NULL;
    public $skills_scan_status38 = NULL;
    public $skills_scan_status39 = NULL;
    public $skills_scan_status40 = NULL;
    public $skills_scan_status41 = NULL;
    public $skills_scan_status42 = NULL;
    public $skills_scan_status43 = NULL;
    public $skills_scan_status44 = NULL;
    public $skills_scan_status45 = NULL;
    public $skills_scan_status46 = NULL;
    public $skills_scan_status47 = NULL;
    public $skills_scan_status48 = NULL;
    public $skills_scan_status49 = NULL;
    public $skills_scan_status50 = NULL;

    public $functional_skills_progress = null;
    public $functional_skills_progress2 = null;

    public $workplace_competence = NULL;
    public $knowledge_modules = NULL;
    public $learner_comment = NULL;
    public $employer_progress_review = NULL;
    public $attendance = NULL;
    public $punctuality = NULL;
    public $attitude = NULL;
    public $communication = NULL;
    public $enthusiasm = NULL;
    public $commitment = NULL;
    public $emp_logical_creative = NULL;
    public $emp_independently = NULL;
    public $emp_problem_solving = NULL;
    public $emp_initiative = NULL;
    public $emp_organised = NULL;
    public $emp_internal_external = NULL;
    public $emp_communicate_effectively = NULL;
    public $emp_maintain_productive = NULL;
    public $current_hours = NULL;
    public $smart_line5 = NULL;
    public $smart1_achieved = NULL;
    public $smart2_achieved = NULL;
    public $smart3_achieved = NULL;
    public $smart4_achieved = NULL;
    public $smart5_achieved = NULL;
    public $otj = NULL;
    public $specific = NULL;
    public $measurable = NULL;
    public $achievable = NULL;
    public $timebound = NULL;
    public $next_contact = NULL;
    public $hours = NULL;
    public $minutes = NULL;
    public $next_support = NULL;
    public $support_hours = NULL;
    public $support_minutes = NULL;
    public $adobe = NULL;
    public $performance_issues = NULL;
    public $example1 = NULL;
    public $example2 = NULL;
    public $example3 = NULL;
    public $example4 = NULL;
    public $example5 = NULL;
    public $example6 = NULL;
    public $example7 = NULL;
    public $example8 = NULL;
    public $example9 = NULL;
    public $example10 = NULL;

    public $signature_learner_font = NULL;
    public $signature_learner_name = NULL;
    public $signature_learner_date = NULL;
    public $signature_assessor_font = NULL;
    public $signature_assessor_name = NULL;
    public $signature_assessor_date = NULL;
    public $signature_employer_font = NULL;
    public $signature_employer_name = NULL;
    public $signature_employer_date = NULL;
    public $autosave = NULL;
    public $created_by = NULL;
    public $manager_attendance = NULL;
    public $ontrack_behind = NULL;
    public $hours_currently = NULL;
    public $learner_comment2 = NULL;

}
?>