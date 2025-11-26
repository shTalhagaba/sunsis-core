<?php
class save_training_record_v2 implements IAction
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
            'commercial_fee',
            'commercial_fee_emp_cont',
            'all_amount',
            'all_before',
        ];
        foreach($tr_fields AS $tr_field)
        {
            $tr->$tr_field = isset($_POST[$tr_field]) ? $_POST[$tr_field] : null;
        }
        if(DB_NAME == "am_crackerjack")
        {
            $tr->term_time = isset($_POST['term_time']) ? $_POST['term_time'] : null;
        }
	    if(in_array($tr->LLDD, ["N", "P"]))
        {
            $tr->llddcat = '';
            $tr->primary_lldd = '';
        }

	// because users can change the dates in the training record, so let's take the differece in months between two dates
        $psd = Date::toMySQL($tr->practical_period_start_date);
        $ped = Date::toMySQL($tr->practical_period_end_date);
        $duration_practical_period = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '{$psd}', '{$ped}');");

        $tr->total_contracted_hours_per_year = ceil( floatval($tr->contracted_hours_per_week) * floatval($tr->weeks_to_be_worked_per_year) );

        $total_weeks_on_programme = SkillsAnalysis::calculateTotalWeeksOnProgramme($link, $tr->practical_period_start_date, $tr->practical_period_end_date);

        $annual_leave_for_total_weeks_on_programme = SkillsAnalysis::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);

        $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;

        $tr->total_contracted_hours_full_apprenticeship = round( $tr->contracted_hours_per_week * $actual_weeks_on_programme );

        $tr->part_time_otj_hours = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : SkillsAnalysis::calculateOtjPartTime($tr->total_contracted_hours_full_apprenticeship);
	    $tr->part_time_otj_hours = SkillsAnalysis::checkForMimimumOtjHours($tr->part_time_otj_hours);

        $tr->off_the_job_hours_based_on_duration = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : SkillsAnalysis::calculateOtjFullTime($actual_weeks_on_programme);
	    $tr->off_the_job_hours_based_on_duration = SkillsAnalysis::checkForMimimumOtjHours($tr->off_the_job_hours_based_on_duration);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $tr->minimum_percentage_otj_training = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : SkillsAnalysis::calculateOtjPartTime($tr->total_contracted_hours_full_apprenticeship); // old field for full timers - now this value is stored in off_the_job_hours_based_on_duration
        $tr->minimum_duration_part_time = $tr->duration_practical_period; // legacy field
        $tr->part_time_total_contracted_hours_full_apprenticeship = $tr->total_contracted_hours_full_apprenticeship; // legacy field

        // if standard is changed and user wants to refresh the prices from standard.
        if(isset($_POST['refresh_prices']) && $_POST['refresh_prices'] == 1)
        {
            $standard = Framework::loadFromDatabase($link, $_POST['framework_id']);
            $tr->tnp1 = $standard->tnp1;
            $tr->epa_price = $standard->epa_price;
            $tr->additional_prices = $standard->additional_prices;
        }
        else
        {
            $tnp1 = [];
            if(isset($_POST['total_tnp']) && intval($_POST['total_tnp']) > 0)
            {
                for($i = 1; $i <= $_POST['total_tnp']; $i++)
                {
                    if(trim($_POST['price_description_'.$i]) != '')
                    {
                        $tnp1[] = [
                            'description' => trim($_POST['price_description_'.$i]),
                            'cost' => $_POST['price_cost_'.$i],
                            'reduce' => isset($_POST['price_include_'.$i]) ? 1 : 0 ,
                        ];
                    }
                }
            }
            
            $additional_prices = [];
            if(isset($_POST['total_additional_prices']) && intval($_POST['total_additional_prices']) > 0)
            {
                for($i = 1; $i <= $_POST['total_additional_prices']; $i++)
                {
                    if(trim($_POST['additional_prices_description_'.$i]) != '')
                    {
                        $additional_prices[] = [
                            'description' => trim($_POST['additional_prices_description_'.$i]),
                            'cost' => $_POST['additional_prices_cost_'.$i],
                        ];
                    }
                }
            }
    
            $tr->tnp1 = json_encode($tnp1);
            $tr->additional_prices = json_encode($additional_prices);
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

	    $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
        if(!isset($extra_info->tr_id))
        {
            $extra_info = new stdClass();
        }
        $ob_learner_extra_details_fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_extra_details");
        foreach($ob_learner_extra_details_fields AS $extra_info_key => $extra_info_field_name)
        {
            $extra_info->$extra_info_field_name = isset($_POST[$extra_info_field_name]) ? $_POST[$extra_info_field_name] : null;
        }
        $extra_info->tr_id = $tr->id;            

        DAO::transaction_start($link);
        try
        {
            // update learner fields
            $ob_learner->save($link);

            // update training fields
            $tr->save($link);

	        // update extra info 
            DAO::saveObjectToTable($link, "ob_learner_extra_details", $extra_info);

            // if framework has been updated
            if($existing_tr_record->framework_id != $tr->framework_id)		
            {
                DAO::execute($link, "DELETE FROM ob_learner_quals WHERE tr_id = '{$tr->id}'");
                $framework_qualifications = DAO::getResultset($link, "SELECT id, title, qualification_type, auto_id FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}';", DAO::FETCH_ASSOC);
                $ob_learner_quals = [];
                foreach($framework_qualifications AS $qual)
                {
                    $ob_learner_quals[] = [
                        'tr_id' => $tr->id,
                        'qual_id' => $qual['id'],
                        'qual_start_date' => $tr->practical_period_start_date,
                        'qual_end_date' => $tr->practical_period_end_date,
                        'qual_type' => $qual['qualification_type'],
                        'qual_title' => $qual['title'],
                        'framework_qual_auto_id' => $qual['auto_id'],
                    ];
                }
                DAO::multipleRowInsert($link, 'ob_learner_quals', $ob_learner_quals);

                $skills_analysis = new SkillsAnalysis($tr->id);
                $skills_analysis->training_price_minus_epa = floatval($tr->total_training_cost) - floatval($tr->epa_price);
                $skills_analysis->total_training_price = floatval($tr->total_training_cost);
                $_hours = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(evidences, '//evidence/@delhours') FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND main_aim = 1;");
                $_hours = $_hours != '' ? explode(" ", $_hours) : [];
                $skills_analysis->delivery_plan_hours_ba = array_sum($_hours);
                $skills_analysis->save($link);

                DAO::execute($link, "DELETE FROM ob_learner_ksb WHERE tr_id = '{$tr->id}'");
                $ksb_entries = Helpers::getKsbElementsAsArray($link, $tr, $skills_analysis);
                DAO::multipleRowInsert($link, 'ob_learner_ksb', $ksb_entries);
            }

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