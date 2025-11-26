<?php
class ajax_helper implements IAction
{
	public function execute( PDO $link )
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction != '' && $subaction == 'generateOtjSheetPdf')
		{
			$this->generateOtjSheetPdf($link);
			exit;
		}

		if($subaction != '' && $subaction == 'generateEvidenceOfEmploymentPdf')
		{
			$this->generateEvidenceOfEmploymentPdf($link);
			exit;
		}

		if($subaction != '' && $subaction == 'generateFdilPdf')
		{
			$this->generateFdilPdf($link);
			exit;
		}

		if($subaction != '' && $subaction == 'generateAlsPdf')
		{
			$this->generateAlsPdf($link);
			exit;
		}

        	if($subaction != '' && $subaction == 'addRemoveFlagOnEpaOrg')
		{
			$this->addRemoveFlagOnEpaOrg($link);
			exit;
		}

		if($subaction != '' && $subaction == 'update_archive_status')
		{
			$this->update_archive_status($link);
			exit;
		}

		if($subaction != '' && $subaction == 'saveObLearnerEligibility')
		{
			$this->saveObLearnerEligibility($link);
			exit;
		}

		if($subaction != '' && $subaction == 'getStandardInfo')
		{
			$this->getStandardInfo($link);
			exit;
		}

		if($subaction != '' && $subaction == 'getStandardEpaAndPrice')
		{
			$this->getStandardEpaAndPrice($link);
			exit;
		}
		
		if($subaction != '' && $subaction == 'getOblearnerKsbLog')
		{
			$this->getOblearnerKsbLog($link);
			exit;
		}

		if($subaction != '' && $subaction == 'getApprenticeshipDetails')
		{
			$this->getApprenticeshipDetails($link);
			exit;
		}

		if($subaction != '' && $subaction == 'updateEmployerAgreementStatus')
		{
			$this->updateEmployerAgreementStatus($link);
			exit;
		}

		if($subaction != '' && $subaction == 'populate_other_info')
		{
			$this->populate_other_info($link);
			exit;
		}

		if($subaction != '' && $subaction == 'validate_edrs_number')
		{
			echo $this->validate_edrs_number($link);
			exit;
		}

        if($subaction != '' && $subaction == 'remove_tr_qualification')
		{
			echo $this->remove_tr_qualification($link);
			exit;
		}

        if($subaction != '' && $subaction == 'add_tr_qualification')
		{
			echo $this->add_tr_qualification($link);
			exit;
		}

        if($subaction != '' && $subaction == 'upload_learner_file')
		{
			echo $this->upload_learner_file($link);
			exit;
		}

        	if($subaction != '' && $subaction == 'update_tr_from_sa')
		{
			$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
            		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
			echo (DB_NAME == "am_ela" && $tr->practical_period_start_date > '2023-06-31') ? $this->update_tr_from_sa_v2($link) : $this->update_tr_from_sa($link);
			exit;
		}

		if($subaction != '' && $subaction == 'update_tr_duration_from_sa')
		{
			$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
            		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
	            	echo (DB_NAME == "am_ela" && $tr->practical_period_start_date > '2023-06-31') ? $this->update_tr_duration_from_sa_v2($link) : $this->update_tr_duration_from_sa($link);
			exit;
		}

        	if($subaction != '' && $subaction == 'update_tr_price_from_sa')
		{
			$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
			echo (DB_NAME == "am_ela" && $tr->practical_period_start_date > '2023-06-31') ? $this->update_tr_price_from_sa_v2($link) : $this->update_tr_price_from_sa($link);
			exit;
		}

        	if($subaction != '' && $subaction == 'update_training_qualification_details')
		{
			echo $this->update_training_qualification_details($link);
			exit;
		}

        	if($subaction != '' && $subaction == 'saveTabInSession')
		{
			echo $this->saveTabInSession($link);
			exit;
		}

		if($subaction != '' && $subaction == 'calculate_end_date_from_duration')
		{
			echo $this->calculate_end_date_from_duration($link);
			exit;
		}

		if($subaction != '' && $subaction == 'generateDocumentPdf')
		{
			echo $this->generateDocumentPdf($link);
			exit;
		}

		if($subaction != '' && $subaction == 'generatePlrPdf')
		{
			$this->generatePlrPdf($link);
			exit;
		}

		if($subaction != '' && $subaction == 'delete_fdil_entry')
		{
			$this->delete_fdil_entry($link);
			exit;
		}

		if($subaction != '' && $subaction == 'delete_initial_contract')
		{
			$this->delete_initial_contract($link);
			exit;
		}

		if($subaction != '' && $subaction == 'calculateOtj')
		{
			$this->calculateOtj($link);
			exit;
		}

		if($subaction != '' && $subaction == 'generateEmpAgrWithTr')
		{
			$this->generateEmpAgrWithTr($link);
		}

		if($subaction != '' && $subaction == 'reset_otj_planner_grid')
		{
			$this->reset_otj_planner_grid($link);
		}

		if($subaction != '' && $subaction == 'overwriteOtj')
		{
			$this->overwriteOtj($link);
		}
		
        if($subaction != '' && $subaction == 'fetch_otj_hours')
		{
			$this->fetchOtjHours($link);
		}

        if($subaction != '' && $subaction == 'get_lookup_otj_durations')
		{
			$this->get_lookup_otj_durations($link);
		}

	}

	public function populate_other_info(PDO $link)
	{
		$entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
		$entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
		if($entity_id == '')
			throw new Exception('Missing querystring argument: entity_id');

		if($entity_type == 'pool')
			$sql = "SELECT pool.*, pool_locations.telephone FROM pool INNER JOIN pool_locations ON pool.id = pool_locations.`pool_id` WHERE pool.id = '{$entity_id}'";

		$pool = DAO::getObject($link, $sql);

		echo json_encode($pool);
	}

	public function updateEmployerAgreementStatus(PDO $link)
    {
//        $agreement_id = isset($_REQUEST['agreement_id']) ? $_REQUEST['agreement_id'] : '';
  //      $agreement = EmployerAgreement::loadFromDatabase($link, $agreement_id);
    //    $agreement->status = EmployerAgreement::TYPE_SENT;
      //  $agreement->save($link);
        echo true;
    }

    public function getStandardInfo(PDO $link)
    {
        $info = [];
        $framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
        if($framework_id == '')
        {
            echo json_encode((object)$info);
            return;
        }
        $framework = Framework::loadFromDatabase($link, $framework_id);
        $info['level'] = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}'");
        $info['apprenticeship_title'] = $framework->getStandardCodeDesc($link);
        $info['funding_band_max'] = $framework->getFundingBandMax($link);
        $info['recommended_duration'] = $framework->getRecommendedDuration($link);

        echo '<table class="table table-bordered small">';
        echo '<tr><th>Standard</th><td>' . $framework->title . '</td></tr>';
        echo '<tr><th>Apprenticeship title</th><td>' . $framework->getStandardCodeDesc($link) . '</td></tr>';
        echo '<tr><th>Level</th><td>' . DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}'") . '</td></tr>';
        echo '<tr><th>Funding band maximum</th><td>&pound;' . $framework->getFundingBandMax($link) . '</td></tr>';
        echo '<tr><th>Recommended duration</th><td>' . $framework->getRecommendedDuration($link) . '</td></tr>';
        echo '</table>';
    }

    public function getApprenticeshipDetails(PDO $link)
    {
        $result = new stdClass();
        $ob_learner_id = $_REQUEST['ob_learner_id'];
        $ppsd = $_REQUEST['ppsd'];
        $dpp = $_REQUEST['dpp'];

        $pped = new Date($ppsd);
        $pped->addMonths($dpp);

        $pediepa = new Date($ppsd);
        $pediepa->addMonths($dpp+3);



        $result->ppsd = $ppsd;
        $result->dpp = $dpp;
        $result->pped = $pped->formatShort();
        $result->pediepa = $pediepa->formatShort();

        echo json_encode($result);
    }

    public function getOblearnerKsbLog(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $logs = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb_log WHERE tr_id = '{$tr_id}' ORDER BY id DESC", DAO::FETCH_ASSOC);
        if(count($logs) == 0)
        {
            echo 'No logs entry found.';
            return;
        }

        echo '<table class="table table-bordered small">';
        echo '<tr><th>By</th><th>DateTime</th><th>Duration</th><th>Detail</th></tr>';
        foreach($logs AS $log)
        {
            echo '<tr>';
            echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$log['updated_by']}'") . '</td>';
            echo '<td>' . Date::to($log['created'], Date::DATETIME) . '</td>';
            echo '<td>' . $log['overwrite_max_duration_fa'] . '</td>';
            echo '<td>';
            $log_detail = json_decode($log['updated_detail']);
            echo '<table class="table table-bordered small">';
            echo '<tr><th>Question</th><th>Score</th><th>Comments</th></tr>';
            foreach($log_detail AS $log_entry)
            {
                echo '<tr>';
                echo '<td>' . DAO::getSingleValue($link, "SELECT evidence_title FROM ob_learner_ksb WHERE id = '{$log_entry->id}'") . '</td>';
                echo '<td>' . $log_entry->score . '</td>';
                echo '<td>' . $log_entry->comments . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function getStandardEpaAndPrice(PDO $link)
    {
        $framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
        if($framework_id == '')
            return;

        $framework = Framework::loadFromDatabase($link, $framework_id);
        $result = new stdClass();
        $result->epa_org = $framework->epa_org_id;
        $result->epa_price = $framework->epa_price;
        $result->duration_in_months = $framework->duration_in_months;
        $result->epa_duration = $framework->epa_duration;

        echo json_encode($result);
    }

    public function saveObLearnerEligibility(PDO $link)
    {
        $ob_id = isset($_REQUEST['ob_id']) ? $_REQUEST['ob_id'] : '';
        $ob_eligibility = isset($_REQUEST['ob_eligibility']) ? $_REQUEST['ob_eligibility'] : '';
        if($ob_id == '')
            return;

       DAO::execute($link, "UPDATE ob_learners SET is_eligible = '{$ob_eligibility}' WHERE id = '{$ob_id}'");

    }

    public function addRemoveFlagOnEpaOrg(PDO $link)
    {
        $EPA_ORG_ID = isset($_REQUEST['EPA_ORG_ID']) ? $_REQUEST['EPA_ORG_ID'] : '';
        if($EPA_ORG_ID == '')
            return;

        $exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM client_epa_orgs WHERE EPA_ORG_ID = '{$EPA_ORG_ID}'");
        if($exists > 0)
        {
            DAO::execute($link, "DELETE FROM client_epa_orgs WHERE EPA_ORG_ID = '{$EPA_ORG_ID}'");
            echo 'Flag is unset for this EPA organisation.';
        }
        else
        {
            $epa_org = DAO::getObject($link, "SELECT * FROM central.epa_organisations WHERE EPA_ORG_ID = '{$EPA_ORG_ID}'");
            DAO::saveObjectToTable($link, 'client_epa_orgs', $epa_org);
            echo 'Flag is set for this EPA organisation.';
        }
    }

    public function update_archive_status(PDO $link)
    {
        $ob_learner_id = isset($_REQUEST['frm_archive_learner_ob_learner_id']) ? $_REQUEST['frm_archive_learner_ob_learner_id'] : '';
        if($ob_learner_id == '')
            throw new Exception("Missing querystring argument: frm_archive_learner_ob_learner_id");

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);

        $message = "";
        if($ob_learner->archive == 'N')
        {
            $ob_learner->archive = 'Y';
            $message = "Learner is archived.";
        }
        elseif($ob_learner->archive == 'Y')
        {
            $ob_learner->archive = 'N';
            $message = "Learner is unarchived.";
        }

        $ob_learner->save($link);

        echo $message;

    }

    public function validate_edrs_number(PDO $link)
    {
        $A44 = isset($_REQUEST['edrs']) ? $_REQUEST['edrs'] : '';
        if($A44 == '')
            return 1;

        $flag1 = true;
        for($a = 0; $a <= 8; $a++)
            if(!(Helpers::isDigit(substr($A44, $a, 1))))
                $flag1 = false;

        $flag2 = true;
        if(strlen($A44) > 9)
            for($a=9; $a <= 29; $a++)
                if((substr($A44, $a, 1) != ' ') && (substr($A44, $a, 1) != ''))
                    $flag2 = false;

        if($flag1 && $flag2)
        {
            $res = 11-((9*(int)substr($A44,0,1)+8*(int)substr($A44,1,1)+7*(int)substr($A44,2,1)+6*(int)substr($A44,3,1)+5*(int)substr($A44,4,1)+4*(int)substr($A44,5,1) + 3*(int)substr($A44,6,1) + 2*(int)substr($A44,7,1)) % 11);
            if($res==11)
                $AD03='0';
            else
                if($res==10)
                    $AD03='X';
                else
                    $AD03=$res;
        }
        else
            $AD03 = 'T';

        if($AD03=='T')
        {
            return 0;
        }

        return 1;
    }

    public function remove_tr_qualification(PDO $link)
    {
        $row_id = isset($_REQUEST['row_id']) ? $_REQUEST['row_id'] : '';

        if($row_id == '')
            return;

        DAO::execute($link, "DELETE FROM ob_learner_quals WHERE id = '{$row_id}'");
        
        return 1;
    }
    
    public function add_tr_qualification(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $qualification = isset($_REQUEST['qualification']) ? $_REQUEST['qualification'] : '';

        if($tr_id == '' || $qualification == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $framework_qualification = DAO::getObject($link, "SELECT * FROM framework_qualifications WHERE auto_id = '{$qualification}' AND framework_id = '{$tr->framework_id}'");
        if(isset($framework_qualification->id))
        {
            if(DB_NAME == "am_ela")
            {
                $_sd = new Date($tr->practical_period_start_date);
                $_ed = new Date($tr->practical_period_end_date);
                if($framework_qualification->offset_months != '' && (int)$framework_qualification->offset_months > 0)
                {
                    $_sd->addMonths($framework_qualification->offset_months);
                }
                if($framework_qualification->duration_in_months != '' && (int)$framework_qualification->duration_in_months > 0)
                {
                    $_ed = new Date($_sd->formatMySQL());
                    $_ed->addMonths($framework_qualification->duration_in_months);
                }

                $ob_learner_qual = new stdClass();
                $ob_learner_qual->tr_id = $tr->id;
                $ob_learner_qual->qual_type = $framework_qualification->qualification_type;
                $ob_learner_qual->qual_id = str_replace('/', '', $framework_qualification->id);
                $ob_learner_qual->qual_title = $framework_qualification->title;
                $ob_learner_qual->qual_start_date = $_sd->formatMySQL(); //$tr->practical_period_start_date;
                $ob_learner_qual->qual_end_date = $_ed->formatMySQL(); //$tr->practical_period_end_date;
		$ob_learner_qual->qual_sequence = $framework_qualification->sequence;
		$ob_learner_qual->qual_offset_months = $framework_qualification->offset_months;
                $ob_learner_qual->framework_qual_auto_id = $framework_qualification->auto_id;    
            }
            else
            {
                $ob_learner_qual = new stdClass();
                $ob_learner_qual->tr_id = $tr->id;
                $ob_learner_qual->qual_type = $framework_qualification->qualification_type;
                $ob_learner_qual->qual_id = str_replace('/', '', $framework_qualification->id);
                $ob_learner_qual->qual_title = $framework_qualification->title;
                $ob_learner_qual->qual_start_date = $tr->practical_period_start_date;
                $ob_learner_qual->qual_end_date = $tr->practical_period_end_date;
                $ob_learner_qual->framework_qual_auto_id = $framework_qualification->auto_id;   
            }

            DAO::saveObjectToTable($link, 'ob_learner_quals', $ob_learner_qual);
        }
        
        return 1;
    }

    public function upload_learner_file(PDO $link)
    {
        $learner_id = isset($_REQUEST['learner_id']) ? $_REQUEST['learner_id'] : '';
		if($learner_id == '')
        {
			throw new Exception("Missing querystring argument, learner_id");
		}

		$target_directory = "/OnBoarding/ob_learners/" . $learner_id;

        Repository::processFileUploads('uploaded_learner_file', $target_directory, Helpers::getValidExtensions());

		http_redirect($_SESSION['bc']->getCurrent());
    }

    public function update_tr_from_sa(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $sa_id = isset($_REQUEST['sa_id']) ? $_REQUEST['sa_id'] : '';

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $sa_id);
        if(is_null($sa) || $sa->tr_id != $tr_id)
        {
            throw new Exception('Invalid details');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        $epa_duration = DAO::getSingleValue($link, "SELECT epa_duration FROM frameworks WHERE id = '{$tr->framework_id}'");

	$tr->off_the_job_hours_based_on_duration = $sa->off_the_job_hours_based_on_duration;
        $tr->price_reduction_percentage = $sa->price_reduction_percentage;

        //update the prices
        $tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
        foreach($tnp1_prices AS &$price)
        {
            if($price->reduce == 1)
            {
                //$price->cost = ceil( $price->cost * ($sa->percentage_fa / 100) );
		//$price->cost = $price->cost - ceil( $price->cost * ($sa->price_reduction_percentage / 100) );
		$price->cost = ceil( $price->cost * ( (100 - $sa->price_reduction_percentage) / 100 ) );
            }
        }
        $tr->tnp1 = json_encode($tnp1_prices);

	$tr_practical_period_end_date_before_change = $tr->practical_period_end_date;
        
        $tr->duration_practical_period = $sa->duration_fa;
        $practical_period_end_date = new Date($tr->practical_period_start_date);
        $practical_period_end_date->addMonths($tr->duration_practical_period);
        $tr->practical_period_end_date = $practical_period_end_date->formatMySQL();
        
        if($epa_duration != '')
        {
            $tr->apprenticeship_duration_inc_epa = intval($tr->duration_practical_period) + intval($epa_duration);
            $practical_period_end_date->addMonths($epa_duration);
            $tr->apprenticeship_end_date_inc_epa = $practical_period_end_date->formatMySQL();
        }

	$tr->total_contracted_hours_full_apprenticeship = (floatval($tr->total_contracted_hours_per_year)/12)*floatval($tr->duration_practical_period);
        $tr->total_contracted_hours_full_apprenticeship = ceil($tr->total_contracted_hours_full_apprenticeship);

        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Training record edited";
            $note->note = $log_string;
        }
        
        //if($tr->duration_practical_period != $sa->duration_fa)
        {
            DAO::transaction_start($link);
            try
            {

                $tr->save($link);
		
		if(DB_NAME != "am_ela")
		{    
                	DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}'");
		}

		if(DB_NAME == "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}' AND qual_end_date = '{$tr_practical_period_end_date_before_change}'");
                }

                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_duration = update_from_counter_duration + 1 WHERE id = '{$sa->id}'");
                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_price = update_from_counter_price + 1 WHERE id = '{$sa->id}'");

                if(isset($note) && !is_null($note))
                {
                    $note->is_audit_note = true;
                    $note->parent_table = 'ob_tr';
                    $note->parent_id = $tr->id;
                    $note->created = date('Y-m-d H:i:s');
                    $note->save($link);
                }
    
                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }
        }

        return 'Prices and dates are updated successfully.';
    }

    public function update_tr_price_from_sa(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $sa_id = isset($_REQUEST['sa_id']) ? $_REQUEST['sa_id'] : '';

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $sa_id);
        if(is_null($sa) || $sa->tr_id != $tr_id)
        {
            throw new Exception('Invalid details');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        
        //update the prices
        $tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
        foreach($tnp1_prices AS &$price)
        {
            if($price->reduce == 1)
            {
                //$price->cost = ceil( $price->cost * ($sa->percentage_fa / 100) );
                $price->cost = ceil( $price->cost * ( (100 - $sa->price_reduction_percentage) / 100 ) );
            }
        }
        $tr->tnp1 = json_encode($tnp1_prices);
        
        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Training record edited";
            $note->note = $log_string;
        }
        
        //if($tr->duration_practical_period != $sa->duration_fa)
        {
            DAO::transaction_start($link);
            try
            {
                $tr->save($link);
    
                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_price = update_from_counter_price + 1 WHERE id = '{$sa->id}'");

                if(isset($note) && !is_null($note))
                {
                    $note->is_audit_note = true;
                    $note->parent_table = 'ob_tr';
                    $note->parent_id = $tr->id;
                    $note->created = date('Y-m-d H:i:s');
                    $note->save($link);
                }
    
                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }
        }

        return 'Prices are updated successfully.';
    }

    public function update_tr_duration_from_sa(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $sa_id = isset($_REQUEST['sa_id']) ? $_REQUEST['sa_id'] : '';

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $sa_id);
        if(is_null($sa) || $sa->tr_id != $tr_id)
        {
            throw new Exception('Invalid details');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        $epa_duration = DAO::getSingleValue($link, "SELECT epa_duration FROM frameworks WHERE id = '{$tr->framework_id}'");

	$tr_practical_period_end_date_before_change = $tr->practical_period_end_date;

        $tr->off_the_job_hours_based_on_duration = $sa->off_the_job_hours_based_on_duration;
        $tr->duration_practical_period = $sa->duration_fa;
        $practical_period_end_date = new Date($tr->practical_period_start_date);
        $practical_period_end_date->addMonths($tr->duration_practical_period);
        $tr->practical_period_end_date = $practical_period_end_date->formatMySQL();
        
        if($epa_duration != '')
        {
            $tr->apprenticeship_duration_inc_epa = intval($tr->duration_practical_period) + intval($epa_duration);
            $practical_period_end_date->addMonths($epa_duration);
            $tr->apprenticeship_end_date_inc_epa = $practical_period_end_date->formatMySQL();
        }

        $tr->total_contracted_hours_full_apprenticeship = (floatval($tr->total_contracted_hours_per_year)/12)*floatval($tr->duration_practical_period);
        $tr->total_contracted_hours_full_apprenticeship = ceil($tr->total_contracted_hours_full_apprenticeship);

        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Training record edited";
            $note->note = $log_string;
        }
        
        //if($tr->duration_practical_period != $sa->duration_fa)
        {
            DAO::transaction_start($link);
            try
            {

                $tr->save($link);
    
                if(DB_NAME != "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}'");
                }

		if(DB_NAME == "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}' AND qual_end_date = '{$tr_practical_period_end_date_before_change}'");
                }

                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_duration = update_from_counter_duration + 1 WHERE id = '{$sa->id}'");

                if(isset($note) && !is_null($note))
                {
                    $note->is_audit_note = true;
                    $note->parent_table = 'ob_tr';
                    $note->parent_id = $tr->id;
                    $note->created = date('Y-m-d H:i:s');
                    $note->save($link);
                }
    
                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }
        }

        return 'Duration and dates are updated successfully.';
    }

    public function update_tr_from_sa_v2(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $sa_id = isset($_REQUEST['sa_id']) ? $_REQUEST['sa_id'] : '';

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $sa_id);
        if(is_null($sa) || $sa->tr_id != $tr_id)
        {
            throw new Exception('Invalid details');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        $epa_duration = DAO::getSingleValue($link, "SELECT epa_duration FROM frameworks WHERE id = '{$tr->framework_id}'");

        $tr->tnp1 = $sa->tnp1_fa;

        $tr_practical_period_end_date_before_change = $tr->practical_period_end_date;

        $tr->off_the_job_hours_based_on_duration = $sa->off_the_job_hours_based_on_duration;
        
        $tr->duration_practical_period = $sa->duration_fa;

        $practical_period_end_date = new Date($tr->practical_period_start_date);

        $practical_period_end_date->addMonths($tr->duration_practical_period);
	    $practical_period_end_date->addDays(1);

        $tr->practical_period_end_date = $practical_period_end_date->formatMySQL();
        
        if($epa_duration != '')
        {
            $tr->apprenticeship_duration_inc_epa = intval($tr->duration_practical_period) + intval($epa_duration);

            $practical_period_end_date->addMonths($epa_duration);

            $tr->apprenticeship_end_date_inc_epa = $practical_period_end_date->formatMySQL();
        }

        $total_weeks_on_programme = SkillsAnalysis::calculateTotalWeeksOnProgramme($link, $tr->practical_period_start_date, $tr->practical_period_end_date);

        $annual_leave_for_total_weeks_on_programme = SkillsAnalysis::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);

        $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;

        $tr->total_contracted_hours_full_apprenticeship = round( $tr->contracted_hours_per_week * $actual_weeks_on_programme );

        $tr->part_time_otj_hours = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : SkillsAnalysis::calculateOtjPartTime($tr->total_contracted_hours_full_apprenticeship);

        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Training record edited by pressing the button 'Update Duration and Duration' from skills scan tab.";
            $note->note = $log_string;
        }
        
        //if($tr->duration_practical_period != $sa->duration_fa)
        {
            DAO::transaction_start($link);
            try
            {

                $tr->save($link);
    
                if(DB_NAME != "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}'");
                }

                if(DB_NAME == "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}' AND qual_end_date = '{$tr_practical_period_end_date_before_change}'");
                }

                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_duration = update_from_counter_duration + 1 WHERE id = '{$sa->id}'");
                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_price = update_from_counter_price + 1 WHERE id = '{$sa->id}'");

                if(isset($note) && !is_null($note))
                {
                    $note->is_audit_note = true;
                    $note->parent_table = 'ob_tr';
                    $note->parent_id = $tr->id;
                    $note->created = date('Y-m-d H:i:s');
                    $note->save($link);
                }
    
                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }
        }

        return 'Prices and dates are updated successfully.';
    }

    public function update_tr_price_from_sa_v2(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $sa_id = isset($_REQUEST['sa_id']) ? $_REQUEST['sa_id'] : '';

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $sa_id);
        if(is_null($sa) || $sa->tr_id != $tr_id)
        {
            throw new Exception('Invalid details');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        
        //update the prices
        $tr->tnp1 = $sa->tnp1_fa;

        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Training record edited by pressing the button 'Update Price Only' from skills scan tab.";
            $note->note = $log_string;
        }
        
        //if($tr->duration_practical_period != $sa->duration_fa)
        {
            DAO::transaction_start($link);
            try
            {
                $tr->save($link);
    
                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_price = update_from_counter_price + 1 WHERE id = '{$sa->id}'");

                if(isset($note) && !is_null($note))
                {
                    $note->is_audit_note = true;
                    $note->parent_table = 'ob_tr';
                    $note->parent_id = $tr->id;
                    $note->created = date('Y-m-d H:i:s');
                    $note->save($link);
                }
    
                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }
        }

        return 'Prices are updated successfully.';
    }

    public function update_tr_duration_from_sa_v2(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $sa_id = isset($_REQUEST['sa_id']) ? $_REQUEST['sa_id'] : '';

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $sa_id);
        if(is_null($sa) || $sa->tr_id != $tr_id)
        {
            throw new Exception('Invalid details');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        $epa_duration = DAO::getSingleValue($link, "SELECT epa_duration FROM frameworks WHERE id = '{$tr->framework_id}'");

        $tr_practical_period_end_date_before_change = $tr->practical_period_end_date;

        $tr->off_the_job_hours_based_on_duration = $sa->off_the_job_hours_based_on_duration;

        $tr->duration_practical_period = $sa->duration_fa;

        $practical_period_end_date = new Date($tr->practical_period_start_date);

        $practical_period_end_date->addMonths($tr->duration_practical_period);
	$practical_period_end_date->addDays(1);

        $tr->practical_period_end_date = $practical_period_end_date->formatMySQL();
        
        if($epa_duration != '')
        {
            $tr->apprenticeship_duration_inc_epa = intval($tr->duration_practical_period) + intval($epa_duration);

            $practical_period_end_date->addMonths($epa_duration);

            $tr->apprenticeship_end_date_inc_epa = $practical_period_end_date->formatMySQL();
        }

        $total_weeks_on_programme = SkillsAnalysis::calculateTotalWeeksOnProgramme($link, $tr->practical_period_start_date, $tr->practical_period_end_date);

        $annual_leave_for_total_weeks_on_programme = SkillsAnalysis::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);

        $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;

        $tr->total_contracted_hours_full_apprenticeship = round( $tr->contracted_hours_per_week * $actual_weeks_on_programme );

        $tr->part_time_otj_hours = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : SkillsAnalysis::calculateOtjPartTime($tr->total_contracted_hours_full_apprenticeship);

        $existing_tr_record = TrainingRecord::loadFromDatabase($link, $tr->id);
        $log_string = $existing_tr_record->buildAuditLogString($link, $tr);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Training record edited by pressing the button 'Update Duration Only' from skills scan tab.";
            $note->note = $log_string;
        }
        
        //if($tr->duration_practical_period != $sa->duration_fa)
        {
            DAO::transaction_start($link);
            try
            {

                $tr->save($link);
    
                if(DB_NAME != "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}'");
                }

                if(DB_NAME == "am_ela")
                {
                    DAO::execute($link, "UPDATE ob_learner_quals SET qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}' AND qual_end_date = '{$tr_practical_period_end_date_before_change}'");
                }

                DAO::execute($link, "UPDATE ob_learner_skills_analysis SET update_from_counter_duration = update_from_counter_duration + 1 WHERE id = '{$sa->id}'");

                if(isset($note) && !is_null($note))
                {
                    $note->is_audit_note = true;
                    $note->parent_table = 'ob_tr';
                    $note->parent_id = $tr->id;
                    $note->created = date('Y-m-d H:i:s');
                    $note->save($link);
                }
    
                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }
        }

        return 'Duration and dates are updated successfully.';
    }

    public function generateOtjSheetPdf(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);    
        $otj_file = $tr->getDirectoryPath() . '/OTJT Sheet.pdf';
        if(is_file($otj_file))
        {
            unlink($otj_file);
        }

        OtjSheet::exportToPdf($link, $tr_id);

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="OTJT Sheet.pdf"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($otj_file);
        exit;
    }

    public function generateEvidenceOfEmploymentPdf(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);    
        $file = $tr->getDirectoryPath() . '/Evidence of Employment.pdf';
        if(is_file($file))
        {
            unlink($file);
        }

        PdfHelper::evidenceOfEmploymentPdf($link, $tr);

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="Evidence of Employment.pdf"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($file);
        exit;
    }

    public function generateFdilPdf(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);    
        $file = $tr->getDirectoryPath() . '/FDIL.pdf';
        if(is_file($file))
        {
            unlink($file);
        }

        PdfHelper::fdilPdf($link, $tr);

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="FDIL.pdf"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($file);
        exit;
    }

    public function generateAlsPdf(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);    
        $file = $tr->getDirectoryPath() . '/AdditionalLearningSupport.pdf';
        if(is_file($file))
        {
            unlink($file);
        }

        PdfHelper::alsPdf($link, $tr);

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="AdditionalLearningSupport.pdf"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($file);
        exit;
    }
    
    public function update_training_qualification_details(PDO $link)
    {
        if( !isset($_POST['tr_id'])  || !isset($_POST['ob_learner_qual_id']))
        {
            throw new Exception("Missing mandatory information");
        }

        $qual = DAO::getObject($link, "SELECT * FROM ob_learner_quals WHERE tr_id = '{$_POST['tr_id']}' AND id = '{$_POST['ob_learner_qual_id']}'");
        if(!isset($qual->id))
        {
            throw new Exception("Invalid arguments");
        }

        $qual->qual_exempt = $_POST['qual_exempt'];
        $qual->qual_start_date = $_POST['qual_start_date'];
        $qual->qual_end_date = $_POST['qual_end_date'];
        $qual->qual_offset_months = isset($_POST['qual_offset_months']) ? $_POST['qual_offset_months'] : '';
        $qual->qual_weighting = isset($_POST['qual_weighting']) ? $_POST['qual_weighting'] : '';
        $qual->qual_on_of = isset($_POST['qual_on_of']) ? $_POST['qual_on_of'] : '';
        $qual->qual_standard_link = isset($_POST['qual_standard_link']) ? substr($_POST['qual_standard_link'], 0, 499) : '';

        $qual->qual_dh = isset($_POST['qual_dh']) ? $_POST['qual_dh'] : '';
        $qual->qual_delivery_postcode = isset($_POST['qual_delivery_postcode']) ? $_POST['qual_delivery_postcode'] : '';

        DAO::saveObjectToTable($link, "ob_learner_quals", $qual);

        http_redirect('do.php?_action=read_training&id=' . $_POST['tr_id']);
    }

    private function saveTabInSession(PDO $link)
    {
        if(isset($_REQUEST['selected_tab']) && $_REQUEST['selected_tab'] != '')
            $_SESSION['training_read_screen_tab'] = $_REQUEST['selected_tab'];
    }

    public function calculate_end_date_from_duration()
    {
        $practical_period_start_date = isset($_REQUEST['practical_period_start_date']) ? $_REQUEST['practical_period_start_date'] : '';
        $practical_duration = isset($_REQUEST['practical_duration']) ? $_REQUEST['practical_duration'] : '';
        $app_duration = isset($_REQUEST['app_duration']) ? $_REQUEST['app_duration'] : '';

        $result = [
            'practical_period_end_date' => '',
            'apprenticeship_end_date_inc_epa' => '',
        ];
        if($practical_period_start_date == '' || $practical_duration == '')
        {
            return;
        }

        $obj_practical_period_start_date = new Date($practical_period_start_date);
        $obj_practical_period_start_date->addMonths($practical_duration);
        if($obj_practical_period_start_date->getWeekday() == 6)
        {
            $obj_practical_period_start_date->subtractDays(1);
        }
        if($obj_practical_period_start_date->getWeekday() == 7)
        {
            $obj_practical_period_start_date->addDays(1);
        }

        $obj_app_start_date_inc_epa = new Date($practical_period_start_date);
        if($app_duration == '')
        {
            $obj_app_start_date_inc_epa->addMonths((int)$practical_duration + 1);
        }
        else
        {
            $obj_app_start_date_inc_epa->addMonths($app_duration);
        }
        if($obj_app_start_date_inc_epa->getWeekday() == 6)
        {
            $obj_app_start_date_inc_epa->subtractDays(1);
        }
        if($obj_app_start_date_inc_epa->getWeekday() == 7)
        {
            $obj_app_start_date_inc_epa->addDays(1);
        }

        
        $result['practical_period_end_date'] = $obj_practical_period_start_date->formatShort();
        $result['apprenticeship_end_date_inc_epa'] = $obj_app_start_date_inc_epa->formatShort();

        return json_encode($result);
    }

    public function generateDocumentPdf(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $document = isset($_REQUEST['document']) ? $_REQUEST['document'] : '';

        if($tr_id != '' && $document != '')
        {
            DAO::execute($link, "UPDATE ob_tr SET ob_tr.generate_pdfs = '{$document}' WHERE ob_tr.id = '{$tr_id}'");
        }
    }

    public function generatePlrPdf(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($tr_id == '')
        {
            return;
        }

        PdfHelper::generatePlrPdf($link, $tr_id);
    }

    public function delete_fdil_entry(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        DAO::execute($link, "DELETE FROM ob_learner_fdil WHERE id = '{$id}'");

        echo 'FDIL entry is deleted successfully.';
    }

    public function calculateOtj(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id']: '';
        $duration_in_months = isset($_REQUEST['duration_in_months']) ? $_REQUEST['duration_in_months']: '';
        $percentage_fa = isset($_REQUEST['percentage_fa']) ? $_REQUEST['percentage_fa']: 0;
        if($tr_id == '')
        {
            echo 0;
        }
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $contracted_hours_per_week = DAO::getSingleValue($link, "SELECT contracted_hours_per_week FROM ob_tr WHERE id = '{$tr_id}'");

        if($contracted_hours_per_week < 30)
        {
            $result = SkillsAnalysis::calculateOtjForPartTimers($link, $tr_id, $duration_in_months, $percentage_fa);
        }
        else
        {
            $result = SkillsAnalysis::calculateOtjForFullTimers($link, $tr_id, $duration_in_months, $percentage_fa);
        }

        echo json_encode($result);
    }

    public function delete_initial_contract(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        DAO::execute($link, "DELETE FROM employer_agreement_schedules WHERE id = '{$id}'");

        echo 'Entry is deleted successfully.';
    }

    function generateEmpAgrWithTr(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $agreement_id = DAO::getSingleValue($link, "SELECT employer_agreements.id FROM employer_agreements INNER JOIN ob_tr ON employer_agreements.employer_id = ob_tr.employer_id WHERE ob_tr.id = '{$tr_id}' ORDER BY employer_agreements.id DESC LIMIT 1");

        if($agreement_id == '')
        {
            http_redirect('do.php?_action=read_training&id='.$tr_id);
        }

        $agreement = EmployerAgreement::loadFromDatabase($link, $agreement_id);

	if($agreement->provider_sign == '' || $agreement->employer_sign == '')
        {
            http_redirect('do.php?_action=read_training&id='.$tr_id);
        }

        include_once('./lib/OnboardingDocuments/ElaEA.php');

        ElaEA::generatePdf($link, $agreement, $tr_id);

        //http_redirect('do.php?_action=read_training&id='.$tr_id);
    }

    public function reset_otj_planner_grid(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring argument: tr_id");
        }

        $sql = <<<SQL
DELETE FROM
	otj_tr_template_sections,
	otj_tr_template_subsections,
	otj_tr_template_activities
USING 
	otj_tr_template_sections 
	LEFT OUTER JOIN	otj_tr_template_subsections ON otj_tr_template_sections.section_id = otj_tr_template_subsections.section_id
	LEFT OUTER JOIN otj_tr_template_activities ON otj_tr_template_subsections.`subsection_id` = otj_tr_template_activities.`subsection_id`
WHERE
	otj_tr_template_sections.tr_id = '$tr_id'        
SQL;

        DAO::execute($link, $sql);
        
        echo 'Grid is reset successfully.';
    }

    public function overwriteOtj(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
        {
            return;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $beforeOtj = $tr->otj_overwritten;
        $tr->otj_overwritten = $_REQUEST['otj_overwritten'] ? $_REQUEST['otj_overwritten'] : null;
        $tr->save($link);

        $note = new Note();
        $note->parent_table = "tr";
        $note->parent_id = $tr->id;
        $note->subject = "OTJ Overwritten";
        $note->note = "OTJ hours changed from {$beforeOtj} to {$tr->otj_overwritten}";
        $note->created = date('Y-m-d H:i:s');
        $note->save($link);

        http_redirect('do.php?_action=read_training&id=' . $tr->id);
    }

    public function fetchOtjHours(PDO $link)
    {
        $standard_ref_no = isset($_REQUEST['standard_ref_no']) ? $_REQUEST['standard_ref_no'] : '';
        if($standard_ref_no == '')
        {
            return;
        }

        $otjHours = DAO::getSingleValue($link, "SELECT otj_hours FROM central.lookup_app_otj_requirements WHERE standard_code = '{$standard_ref_no}'");
        
        $response = [
            'success' => true,
            'otj_hours' => $otjHours
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    public function get_lookup_otj_durations(PDO $link)
    {
        $framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
        if($framework_id == '')
        {
            return;
        }
        $framework = Framework::loadFromDatabase($link, $framework_id);

        $sql = "SELECT 
            lookup_otj_durations.* 
            FROM central.lookup_otj_durations INNER JOIN central.lookup_app_otj_requirements ON lookup_otj_durations.otj_hours = lookup_app_otj_requirements.otj_hours 
            WHERE lookup_app_otj_requirements.standard_code = '{$framework->standard_ref_no}'";
            
        $result = DAO::getObject($link, $sql);
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}