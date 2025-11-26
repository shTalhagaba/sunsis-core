<?php
class SunesisTrainingRecord extends Entity
{
    public function save(PDO $link)
    {
        $this->created = date('Y-m-d H:i:s');

        return DAO::saveObjectToTable($link, 'tr', $this);
    }

    public $id = NULL;
    public $username = NULL;
    public $programme = NULL;
    public $cohort = NULL;
    public $start_date = NULL;
    public $target_date = NULL;
    public $closure_date = NULL;
    public $marked_date = NULL;
    public $status_code = NULL;
    public $school_id = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $gender = NULL;
    public $ethnicity = NULL;
    public $dob = NULL;
    public $uln = NULL;
    public $upi = NULL;
    public $upn = NULL;
    public $ni = NULL;
    public $numeracy = NULL;
    public $literacy = NULL;
    public $home_address_line_1 = NULL;
    public $home_address_line_2 = NULL;
    public $home_address_line_3 = NULL;
    public $home_address_line_4 = NULL;
    public $home_postcode = NULL;
    public $home_email = NULL;
    public $home_telephone = NULL;
    public $home_mobile = NULL;
    public $learning_difficulties = NULL;
    public $disability = NULL;
    public $learning_difficulty = NULL;
    public $current_postcode = NULL;
    public $country_of_domicile = NULL;
    public $prior_attainment_level = NULL;
    public $contract_id = NULL;
    public $l03 = NULL;
    public $l28a = NULL;
    public $l28b = NULL;
    public $l34a = NULL;
    public $l34b = NULL;
    public $l34c = NULL;
    public $l34d = NULL;
    public $l36 = NULL;
    public $l37 = NULL;
    public $l39 = NULL;
    public $l40a = NULL;
    public $l40b = NULL;
    public $l41a = NULL;
    public $l41b = NULL;
    public $l45 = NULL;
    public $l47 = NULL;
    public $employer_id = NULL;
    public $legal_name = NULL;
    public $full_name = NULL;
    public $employer_location_id = NULL;
    public $work_address_line_1 = NULL;
    public $work_address_line_2 = NULL;
    public $work_address_line_3 = NULL;
    public $work_address_line_4 = NULL;
    public $work_postcode = NULL;
    public $work_email = NULL;
    public $work_telephone = NULL;
    public $work_mobile = NULL;
    public $provider_id = NULL;
    public $provider_full_name = NULL;
    public $provider_location_id = NULL;
    public $provider_address_line_1 = NULL;
    public $provider_address_line_2 = NULL;
    public $provider_address_line_3 = NULL;
    public $provider_address_line_4 = NULL;
    public $provider_postcode = NULL;
    public $provider_email = NULL;
    public $provider_telephone = NULL;
    public $scheduled_lessons = null;
    public $registered_lessons = null;
    public $attendances = null;
    public $lates = null;
    public $very_lates = null;
    public $authorised_absences = null;
    public $unexplained_absences = null;
    public $unauthorised_absences = null;
    public $dismissals_uniform = null;
    public $dismissals_discipline = null;
    public $units_total = null;
    public $units_not_started = null;
    public $units_behind = null;
    public $units_on_track = null;
    public $units_under_assessment = null;
    public $units_completed = null;
    public $uploadedfile = NULL;
    public $work_experience = NULL;
    public $assessor = NULL;
    public $tutor = NULL;
    public $verifier = NULL;
    public $wbcoordinator = NULL;
    public $reason_for_leaving = NULL;
    public $reasons_for_leaving = NULL;
    public $ilr_status = NULL;
    public $l42a = NULL;
    public $l42b = NULL;
    public $archive_box = NULL;
    public $destruction_date = NULL;
    public $reason_unfunded = NULL;
    public $revised_planned = NULL;
    public $portfolio_in_date = NULL;
    public $portfolio_iv_date = NULL;
    public $ace_sign_date = NULL;
    public $tdf1 = NULL; //for lead
    public $tdf2 = NULL; //for lead
    public $achievement_date = NULL; //for lead
    public $learner_access_key = NULL;
    public $ecordia_id = NULL;
    public $college_id  = NULL; // for siemens
    public $at_risk  = NULL; // for liga uk
    public $crm_contact_id = NULL;
    public $learner_work_email = NULL;
    public $ob_alert = NULL; // for Siemens
    public $created = NULL;
    public $cs_review1 = NULL;
    public $cs_review2 = NULL;
    public $cs_review3 = NULL;
    public $epa_organisation = NULL;
    public $epa_assessor_name = NULL;
    public $epa_prop_date1 = NULL;
    public $epa_prop_result1 = NULL;
    public $epa_prop_date2 = NULL;
    public $epa_prop_result2 = NULL;
    public $epa_prop_date3 = NULL;
    public $epa_prop_result3 = NULL;


    public $operations_status = NULL;

    public $college_start_date = NULL;
    public $college_end_date = NULL;

    public $coordinator = NULL;

    public $ad_lldd = NULL;
    public $ad_arrangement_req = NULL;
    public $ad_arrangement_agr = NULL;
    public $ad_evidence = NULL;

    public $coach = NULL;
    public $tg_id = NULL;

    public $otj_hours = NULL;
    public $last_contact = NULL;
    public $learner_profile = NULL;
    public $progression_discussed = NULL;
    public $outcome = NULL;
    public $progression_status = NULL;
    public $reason_not_progressing = NULL;
    public $notified_arm = NULL;
    public $app_title = NULL;
    public $progression_comments = NULL;
    public $progression_last_date = NULL;
    public $start_date_inc_epa = NULL;
    public $end_date_inc_epa = NULL;
    public $progression_rating = NULL;
    public $portfolio_prediction = NULL;
    public $actual_progression = NULL;

    public $arm_prog_status = NULL;
    public $arm_reason_not_prog = NULL;
    public $arm_closed_date = NULL;
    public $arm_revisit_progression = NULL;
    public $arm_prog_rating = NULL;
    public $arm_comments = NULL;
    public $employer_mentor = NULL;
    public $planned_induction_date = NULL;
    public $actual_induction_date = NULL;
    public $planned_epa_date = NULL;

    public $sales_lead = NULL;

    protected $audit_fields = array('surname'=>'Learner surname');
}
?>