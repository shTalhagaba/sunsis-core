<?php
class save_enrol_ob_learner implements IAction
{
    public function execute(PDO $link)
    {
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';
        if($ob_learner_id == '')
        {
            throw new Exception("Missing required data: ob_learner_id");
        }

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);

        $tr = new TrainingRecord();
        $tr->populate($_POST);

        if($tr->postJuly25Start())
        {
            $tr->apprenticeship_start_date = $tr->practical_period_start_date;
            $tr->apprenticeship_end_date_inc_epa = $tr->practical_period_end_date;
        }

        // get provider id from its location id
        $provider_location = Location::loadFromDatabase($link, $_POST['training_provider_location_id']);
        $tr->provider_id = $provider_location->organisations_id;
        $tr->provider_location_id = $provider_location->id;

        // get subcontractor id from its location id
        if(isset($_POST['subcontractor_location_id']) && $_POST['subcontractor_location_id'] != '')
        {
            $subcontractor_location = Location::loadFromDatabase($link, $_POST['subcontractor_location_id']);
            $tr->subcontractor_id = $subcontractor_location->organisations_id;
            $tr->subcontractor_location_id = $subcontractor_location->id;
        }

        $tr->home_address_line_1 = $ob_learner->home_address_line_1;
        $tr->home_address_line_2 = $ob_learner->home_address_line_2;
        $tr->home_address_line_3 = $ob_learner->home_address_line_3;
        $tr->home_address_line_4 = $ob_learner->home_address_line_4;
        $tr->home_postcode = $ob_learner->home_postcode;
        $tr->home_email = $ob_learner->home_email;
        $tr->home_telephone = $ob_learner->home_telephone;
        $tr->home_mobile = $ob_learner->home_mobile;
        $tr->work_email = $ob_learner->work_email;

        $tr->status_code = TrainingRecord::STATUS_IN_PROGRESS;

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

        $tr->tnp1 = $framework->tnp1;
        $tr->epa_price = $framework->epa_price;
        $tr->additional_prices = $framework->additional_prices;
	    $tr->recommended_duration = $framework->getRecommendedDuration($link);

        if($tr->postJuly25Start())
        {
            $tr->otj_duration_pw_hours = $tr->otjPW();
        }

        DAO::transaction_start($link);
        try
        {
            // create training record
            $tr->save($link);

            // copy qualifications
            if(DB_NAME == "am_ela" || DB_NAME == "am_demo")
                $framework_qualifications = DAO::getResultset($link, "SELECT id, title, qualification_type, auto_id, sequence, offset_months, duration_in_months FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}';", DAO::FETCH_ASSOC);
            else	
                $framework_qualifications = DAO::getResultset($link, "SELECT id, title, qualification_type, auto_id FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}';", DAO::FETCH_ASSOC);
                
            $ob_learner_quals = [];
            foreach($framework_qualifications AS $qual)
            {
                if(DB_NAME == "am_ela" || DB_NAME == "am_demo")
                {
		            $_sd = new Date($tr->practical_period_start_date);
                    $_ed = new Date($tr->practical_period_end_date);
                    if($qual['offset_months'] != '' && (int)$qual['offset_months'] > 0)
                    {
                        $_sd->addMonths($qual['offset_months']);
                    }
		            if($qual['duration_in_months'] != '' && (int)$qual['duration_in_months'] > 0)
                    {
                        $_ed = new Date($_sd->formatMySQL());
                        $_ed->addMonths($qual['duration_in_months']);
                    }
			
                    $ob_learner_quals[] = [
                        'tr_id' => $tr->id,
                        'qual_id' => $qual['id'],
                        // 'qual_start_date' => $tr->practical_period_start_date,
                        // 'qual_end_date' => $tr->practical_period_end_date,
                        'qual_start_date' => $_sd->formatMySQL(),
                        'qual_end_date' => $_ed->formatMySQL(),
                        'qual_type' => $qual['qualification_type'],
                        'qual_title' => $qual['title'],
                        'framework_qual_auto_id' => $qual['auto_id'],
                        'qual_sequence' => $qual['sequence'],
                        'qual_offset_months' => $qual['offset_months'],
                    ];   
                }
                else
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
            }
            DAO::multipleRowInsert($link, 'ob_learner_quals', $ob_learner_quals);

            // create entry in skills analysis
            $skills_analysis = new SkillsAnalysis($tr->id);
            $skills_analysis->duration_ba = $tr->duration_practical_period == '' ? $framework->getRecommendedDuration($link) : $tr->duration_practical_period;
            $skills_analysis->tnp1 = $framework->tnp1;
	        $skills_analysis->tnp1_fa = $framework->tnp1;
            $skills_analysis->epa_price = $framework->epa_price;
            $skills_analysis->epa_price_fa = $framework->epa_price;
            $skills_analysis->additional_prices = $framework->additional_prices;
            $skills_analysis->rpl_percentages = $framework->getRplPercentages();

	        $skills_analysis->minimum_duration_part_time = $tr->minimum_duration_part_time; // legacy field
            $skills_analysis->part_time_total_contracted_hours_full_apprenticeship = $tr->part_time_total_contracted_hours_full_apprenticeship; // legacy field
            $skills_analysis->part_time_otj_hours = $tr->part_time_otj_hours;

            $skills_analysis->otj_pw_ba = $tr->otj_duration_pw_hours;
            $skills_analysis->off_the_job_hours_based_on_duration = $tr->off_the_job_hours_based_on_duration;
            
            $skills_analysis->save($link);

            // populate ksb table
            $ksb_entries = Helpers::getKsbElementsAsArray($link, $tr, $skills_analysis);
            DAO::multipleRowInsert($link, 'ob_learner_ksb', $ksb_entries);
            

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect("do.php?_action=read_training&id={$tr->id}");
    }
}
?>