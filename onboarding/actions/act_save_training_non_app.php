<?php
class save_training_non_app implements IAction
{

    public function execute(PDO $link)
    {
        if(!isset($_POST['id']) || $_POST['id'] == '')
            throw new Exception("missing querystring argument: id");

        $tr = TrainingRecord::loadFromDatabase($link, $_POST['id']);
        if(is_null($tr))
            throw new Exception("invalid id");

        $ob_learner = $tr->getObLearnerRecord($link);
        $ob_learner_fields = [
            'learner_title',
            'firstnames',
            'surname',
            'gender',
            'dob',
            'home_address_line_1',
            'home_address_line_2',
            'home_address_line_3',
            'home_address_line_4',
            'borough',
            'home_postcode',
            'home_email',
            'home_telephone',
            'home_mobile',
            'work_email',
            'uln',
            'ni',
            'ethnicity',
            'bksb_username',
            'das_admin',
            'das_cohort_no',
        ];
        foreach($ob_learner_fields AS $learner_field)
        {
            $ob_learner->$learner_field = isset($_POST[$learner_field]) ? $_POST[$learner_field] : null;
        }

        $training_provider_location = Location::loadFromDatabase($link, $_POST['training_provider_location_id']);
        $tr->provider_id = $training_provider_location->organisations_id;
        $tr->provider_location_id = $training_provider_location->id;
        $tr->employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : null;
        $tr->employer_location_id = isset($_POST['employer_location_id']) ? $_POST['employer_location_id'] : null;
	    $tr->line_manager_id = isset($_POST['line_manager_id']) ? $_POST['line_manager_id'] : null;
        if($_POST['subcontractor_location_id'] != '')
        {
            $subcontractor_location = Location::loadFromDatabase($link, $_POST['subcontractor_location_id']);
            $tr->subcontractor_id = $subcontractor_location->organisations_id;
            $tr->subcontractor_location_id = $subcontractor_location->id;
        }

        $tr_fields = [
            'framework_id',
            'epa_organisation',
            'trainers',
            'contracted_hours_per_week',
            'weeks_to_be_worked_per_year',
            'practical_period_start_date',
            'practical_period_end_date',
            'duration_practical_period',
            'apprenticeship_start_date',
            'apprenticeship_end_date_inc_epa',
            'apprenticeship_duration_inc_epa',
            'planned_epa_date',
            'job_title',
            'status_code',
            'epa_price',
            'hhs',
            'LLDD',
            'llddcat',
            'primary_lldd',
            'EmploymentStatus',
            'work_curr_emp',
            'SEI',
            'empStatusEmployer',
            'LOE',
            'EII',
            'LOU',
            'BSI',
            'PEI',
            'levy_gifted',
            'type_of_funding',
            'glh',
            'earnings_below_llw',
            'BSI_other_details',
            'commercial_fee',
            'commercial_fee_emp_cont',
            'all_amount',
            'all_before',
        ];
        foreach($tr_fields AS $tr_field)
        {
            $tr->$tr_field = isset($_POST[$tr_field]) ? $_POST[$tr_field] : null;
        }
        if(in_array($tr->LLDD, ["N", "P"]))
        {
            $tr->llddcat = '';
            $tr->primary_lldd = '';
        }

        $existing_learner_record = OnboardingLearner::loadFromDatabase($link, $ob_learner->id);
        $log_string_learner = $existing_learner_record->buildAuditLogString($link, $ob_learner);
        if($log_string_learner != '')
        {
            $note_learner = new Note();
            $note_learner->subject = "Learner record edited";
            $note_learner->note = $log_string_learner;
        }

        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string_tr = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string_tr != '')
        {
            $note_tr = new Note();
            $note_tr->subject = "Training record edited";
            $note_tr->note = $log_string_tr;
        }

        DAO::transaction_start($link);
        try
        {
            // update learner fields
            $ob_learner->save($link);

            // update training fields
            $tr->save($link);

            // if dates were not provided at enrolment
            $qual_start_date = Date::toMySQL($tr->practical_period_start_date);
            $qual_end_date = Date::toMySQL($tr->practical_period_end_date);
            if($qual_start_date != '' && $qual_end_date != '')
                DAO::execute($link, "UPDATE ob_learner_quals SET qual_start_date = '{$qual_start_date}', qual_end_date = '{$qual_end_date}' WHERE tr_id = '{$tr->id}' AND qual_start_date IS NULL AND qual_end_date IS NULL");

            if(isset($note_learner) && !is_null($note_learner))
            {
                $note_learner->is_audit_note = true;
                $note_learner->parent_table = 'ob_learners';
                $note_learner->parent_id = $ob_learner->id;
                $note_learner->created = date('Y-m-d H:i:s');
                $note_learner->save($link);
            }

            if(isset($note_tr) && !is_null($note_tr))
            {
                $note_tr->is_audit_note = true;
                $note_tr->parent_table = 'ob_tr';
                $note_tr->parent_id = $tr->id;
                $note_tr->created = date('Y-m-d H:i:s');
                $note_tr->save($link);
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }


        http_redirect($_SESSION['bc']->getPrevious());
    }

}