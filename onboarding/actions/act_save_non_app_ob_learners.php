<?php
class save_non_app_ob_learners implements IAction
{
	public function execute(PDO $link)
	{
        $ob_learner = new OnboardingLearner();
        $ob_learner->populate($_POST);

        if($_POST['employment_status'] == 'No')
        {
            $ob_learner->employer_id = Organisation::notEmployerId($link);
            $ob_learner->employer_location_id = Organisation::notEmployerLocationId($link);
        }
        $ob_learner->employer_location_id = empty($ob_learner->employer_location_id) ? 
            DAO::getSingleValue($link, "SELECT id FROM locations WHERE organisations_id = '{$ob_learner->employer_id}' AND is_legal_address = 1 LIMIT 1;") : 
            $ob_learner->employer_location_id;
        $ob_learner->funding_stream = $_POST['funding_stream'];

	    DAO::transaction_start($link);
        try
        {
            $ob_learner->save($link);

            // enrolment
            $provider_location = Location::loadFromDatabase($link, $_POST['training_provider_location_id']);

            $tr = new TrainingRecord();
            $tr->populate($_POST);

            $tr->ob_learner_id = $ob_learner->id;
            $tr->employer_id = $ob_learner->employer_id;
            $tr->employer_location_id = $ob_learner->employer_location_id;
            $tr->provider_id = $provider_location->organisations_id;
            $tr->provider_location_id = $provider_location->id;

            if(isset($_POST['subcontractor_location_id']) && $_POST['subcontractor_location_id'] != '')
            {
                $subcontractor_location = Location::loadFromDatabase($link, $_POST['subcontractor_location_id']);
                $tr->subcontractor_id = $subcontractor_location->organisations_id;
                $tr->subcontractor_location_id = $subcontractor_location->id;
            }

            $tr->status_code = TrainingRecord::STATUS_IN_PROGRESS;
            $tr->framework_id = $_POST['framework_id'];
            $tr->practical_period_start_date = $_POST['practical_period_start_date'];
            $tr->practical_period_end_date = $_POST['practical_period_end_date'];
            $tr->glh = $_POST['glh'];

            $tr->save($link);

            // copy qualifications
            $framework_qualifications = DAO::getResultset($link, "SELECT id, title, qualification_type, auto_id, sequence, offset_months, duration_in_months FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}';", DAO::FETCH_ASSOC);
            $ob_learner_quals = [];
            foreach($framework_qualifications AS $qual)
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
                    'qual_start_date' => $_sd->formatMySQL(),
                    'qual_end_date' => $_ed->formatMySQL(),
                    'qual_type' => $qual['qualification_type'],
                    'qual_title' => $qual['title'],
                    'framework_qual_auto_id' => $qual['auto_id'],
                    'qual_sequence' => $qual['sequence'],
                    'qual_offset_months' => $qual['offset_months'],
                ];                
            }
            DAO::multipleRowInsert($link, 'ob_learner_quals', $ob_learner_quals);

            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        http_redirect("do.php?_action=view_ob_learner&id={$ob_learner->id}");
	}
}