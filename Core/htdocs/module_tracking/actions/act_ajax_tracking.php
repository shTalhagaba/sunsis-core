<?php
class ajax_tracking implements IAction
{
    public function execute( PDO $link )
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction != '' && $subaction == 'saveEpaOwnerInOp')
        {
            $this->saveEpaOwnerInOp($link);
            exit;
        }
        if($subaction != '' && $subaction == 'load_employer_locations')
        {
            $this->load_employer_locations($link);
            exit;
        }
        if($subaction != '' && $subaction == 'load_employer_contacts')
        {
            $this->load_employer_contacts($link);
            exit;
        }
        if($subaction != '' && $subaction == 'checkNewInducteeDuplicates')
        {
            $this->checkNewInducteeDuplicates($link);
            exit;
        }
        if($subaction != '' && $subaction == 'checkExistingRecordsBeforeCreation')
        {
            $this->checkExistingRecordsBeforeCreation($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getInductionNotes')
        {
            $this->getInductionNotes($link);
            exit;
        }
        if($subaction != '' && $subaction == 'createLinkedSunesisAccount')
        {
            $this->createLinkedSunesisAccount($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getOperationsNotes')
        {
            $this->getOperationsNotes($link);
            exit;
        }
	if($subaction != '' && $subaction == 'showProjectCheckinNotes')
        {
            $this->showProjectCheckinNotes($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getInducteeNotes')
        {
            $this->getInducteeNotes($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getInductionProgrammeNotes')
        {
            $this->getInductionProgrammeNotes($link);
            exit;
        }
        if($subaction != '' && $subaction == 'createNewSunesisAccount')
        {
            echo $this->createNewSunesisAccount($link);
            exit;
        }
        if($subaction != '' && $subaction == 'isValidForCompletedStatus')
        {
            echo $this->isValidForCompletedStatus($link);
            exit;
        }
        if($subaction != '' && $subaction == 'isL4Programme')
        {
            echo $this->isL4Programme($link);
            exit;
        }
        if($subaction != '' && $subaction == 'importLearnersFromSalesforce')
        {
            echo $this->importLearnersFromSalesforceV2($link);
            exit;
        }
        if($subaction != '' && $subaction == 'saveTabInSession')
        {
            $this->saveTabInSession($link);
            exit;
        }
        if($subaction != '' && $subaction == 'saveOpSessionTabInSession')
        {
            $this->saveOpSessionTabInSession($link);
            exit;
        }
        if($subaction != '' && $subaction == 'createSFLearnersInSunesis')
        {
            $this->createSFLearnersInSunesis($link);
            exit;
        }
        if($subaction != '' && $subaction == 'saveCRMContactsForInductee')
        {
            $this->saveCRMContactsForInductee($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getFrameworkTrackingQualifications')
        {
            $this->getFrameworkTrackingQualifications($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getTrackerUnits')
        {
            $this->getTrackerUnits($link);
            exit;
        }
        if($subaction != '' && $subaction == 'addLearnerToSession')
        {
            $this->addLearnerToSession($link);
            exit;
        }
        if($subaction != '' && $subaction == 'removeLearnerFromSession')
        {
            $this->removeLearnerFromSession($link);
            exit;
        }
        if($subaction != '' && $subaction == 'saveOPSessionRegister')
        {
            $this->saveOPSessionRegister($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getTrackingUnits')
        {
            $this->getTrackingUnits($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getNotApplicableTrackers')
        {
            $this->getNotApplicableTrackers($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getApplicableTrackers')
        {
            $this->getApplicableTrackers($link);
            exit;
        }
        if($subaction != '' && $subaction == 'setSchCode')
        {
            $this->setSchCode($link);
            exit;
        }
        if($subaction != '' && $subaction == 'cancelSessionEntry')
        {
            $this->cancelSessionEntry($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getTrackerUnitSchedulingLog')
        {
            echo $this->getTrackerUnitSchedulingLog($link);
            exit;
        }
        if($subaction != '' && $subaction == 'get_tr_operations_notes')
        {
            echo $this->get_tr_operations_notes($link);
            exit;
        }
        if($subaction != '' && $subaction == 'saveMockEntry')
        {
            echo $this->saveMockEntry($link);
            exit;
        }
        if($subaction != '' && $subaction == 'add_op_add_details_type')
        {
            $this->add_op_add_details_type($link);
            exit;
        }
        if($subaction != '' && $subaction == 'load_op_details_types')
        {
            $this->load_op_details_types($link);
            exit;
        }
        if($subaction != '' && $subaction == 'load_op_epa_status')
        {
            $this->load_op_epa_status();
            exit;
        }
        if($subaction != '' && $subaction == 'get_op_epa_record')
        {
            echo $this->get_op_epa_record($link);
            exit;
        }
        if($subaction != '' && $subaction == 'pullBILInformationForOperations')
        {
            echo $this->pullBILInformationForOperations($link);
            exit;
        }
        if($subaction != '' && $subaction == 'loadAndPrepareEmailTemplate')
        {
            echo $this->loadAndPrepareEmailTemplate($link);
            exit;
        }
        if($subaction != '' && $subaction == 'send_email_to_learner')
        {
            echo $this->send_email_to_learner($link);
            exit;
        }
        if($subaction != '' && $subaction == 'getEmailContent')
        {
            echo $this->getEmailContent($link);
            exit;
        }
        if($subaction != '' && $subaction == 'upload_summernote_image')
        {
            $this->upload_summernote_image($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_learner_complaint')
        {
            $this->save_learner_complaint($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_complaint_response')
        {
            $this->save_complaint_response($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_gateway_prep')
        {
            $this->save_gateway_prep($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_gateway_ready')
        {
            $this->save_gateway_ready($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_epa_project')
        {
            $this->save_epa_project($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_interview')
        {
            $this->save_interview($link);
            exit;
        }
        if($subaction != '' && $subaction == 'removeMockEntry')
        {
            $this->removeMockEntry($link);
            exit;
        }
        if($subaction != '' && $subaction == 'check_unit_ref_applicable_for_learner')
        {
            echo $this->check_unit_ref_applicable_for_learner($link);
            exit;
        }
        if($subaction != '' && $subaction == 'showApProgressLookup')
        {
            echo $this->showApProgressLookup($link);
            exit;
        }
        if($subaction != '' && $subaction == 'removeTrackerUnitSchLog')
        {
            echo $this->removeTrackerUnitSchLog($link);
            exit;
        }
        if($subaction != '' && $subaction == 'removeLARUpdateEntry')
        {
            echo $this->removeLARUpdateEntry($link);
            exit;
        }
        if($subaction != '' && $subaction == 'get_lar_update_entry_details')
        {
            echo $this->get_lar_update_entry_details($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_lar_entry_update')
        {
            echo $this->save_lar_entry_update($link);
            exit;
        }
        if($subaction != '' && $subaction == 'delete_op_session')
        {
            echo $this->delete_op_session($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_tr_learner_profile_info')
        {
            $this->save_tr_learner_profile_info($link);
            exit;
        }
        if($subaction != '' && $subaction == 'save_tr_portfolio_enhancement')
        {
            $this->save_tr_portfolio_enhancement($link);
            exit;
        }
        if($subaction != '' && $subaction == 'showEpaEntryLog')
        {
            $this->showEpaEntryLog($link);
            exit;
        }
        if($subaction != '' && $subaction == 'deleteEpaEntry')
        {
            $this->deleteEpaEntry($link);
            exit;
        }
	if($subaction != '' && $subaction == 'saveMatrixTab')
        {
            $this->saveMatrixTab($link);
            exit;
        }
	if($subaction != '' && $subaction == 'getHoldingSectionComments')
        {
            $this->getHoldingSectionComments($link);
            exit;
        }
	if($subaction != '' && $subaction == 'getOperationNotes')
        {
            $this->getOperationNotes($link);
            exit;
        }
	if($subaction != '' && $subaction == 'reset_register')
        {
            $this->reset_register($link);
            exit;
        }
	if($subaction != '' && $subaction == 'save_programme_induction_capacity')
        {
            $this->save_programme_induction_capacity($link);
            exit;
        }
	if($subaction != '' && $subaction == 'fetch_last_lar_summary')
        {
            $this->fetch_last_lar_summary($link);
            exit;
        }
	if($subaction != '' && $subaction == 'update_tr_gold_star_employer')
        {
            $this->update_tr_gold_star_employer($link);
            exit;
        }
	if($subaction != '' && $subaction == 'update_tr_gold_star_learner')
        {
            $this->update_tr_gold_star_learner($link);
            exit;
        }
	if($subaction != '' && $subaction == 'delete_induction')
        {
            $this->delete_induction($link);
            exit;
        }
	if($subaction != '' && $subaction == 'add_option_to_lookup')
        {
            $this->add_option_to_lookup($link);
            exit;
        }
	if($subaction != '' && $subaction == 'delete_additional_tab_comments')
        {
            $this->delete_additional_tab_comments($link);
            exit;
        }

    }

    private function saveCRMContactsForInductee(PDO $link)
    {
        $inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
        if($inductee_id == '')
            throw new Exception('Missing querystring argument: inductee_id');

        $contact_ids = isset($_REQUEST['contact_ids'])?$_REQUEST['contact_ids']:'';

        DAO::execute($link, "UPDATE inductees SET inductees.emp_crm_contacts = '{$contact_ids}' WHERE inductees.id = '{$inductee_id}'");
    }

    private function createSFLearnersInSunesis(PDO $link)
    {
        $sf_ids = isset($_REQUEST['sf_ids'])?$_REQUEST['sf_ids']:'';

        if($sf_ids == '')
            throw new Exception('No learner selected');

	$employer_types = InductionHelper::getListInducteeEmployerType();
        $learner_types = InductionHelper::getListInducteeTypeV2();
        $certs_list = InductionHelper::getListCerts();

        $sf_ids = explode(',', $sf_ids);
        foreach($sf_ids AS &$Id)
            $Id = "'" . $Id . "'";

        $sf_ids = implode(',', $sf_ids);

        $sf_records = DAO::getResultset($link, "SELECT * FROM sf_contacts WHERE Id IN (" . $sf_ids . ")", DAO::FETCH_ASSOC);
        foreach($sf_records AS $row)
        {
            $exists = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM inductees WHERE sf_Id = '" . $row['Id'] . "'");
            if($exists > 0)
                continue;

            $inductee = new Inductee();
            $inductee->sf_Id = $row['Id'];
            $inductee->firstnames = $row['FirstName'];
            $inductee->surname = $row['LastName'];
	    /*
            if($row['Gender__c'] == 'Female')
                $inductee->gender = 'F';
            elseif($row['Gender__c'] == 'Male')
                $inductee->gender = 'M';
            else
	    */
            $inductee->gender = 'U';	    
            $inductee->dob = $row['Birthdate'];
            $inductee->ni = str_replace(' ', '', $row['National_Insurance__c']);
            $inductee->home_telephone = $row['Phone'];
            $inductee->home_mobile = $row['MobilePhone'];
            $inductee->work_email = $row['Email'];
            $inductee->next_of_kin = substr($row['Safeguarding_Next_of_Kin__c'], 0, 70);
            $inductee->next_of_kin_tel = $row['Safeguarding_Next_of_Kin_Telephone_No__c'];
            $inductee->next_of_kin_email = $row['Safeguarding_Next_of_Kin_Email__c'];
	    $inductee->employment_start_date = $row['EmploymentStartDate'];
            $inductee->salary = $row['AnnualSalary'];
            $inductee->paid_hours = $row['PaidWorkingHours'];
            $inductee->employer_type = array_search(trim($row['VacanySource']), $employer_types);
            //$inductee->inductee_type = array_search(trim($row['VacanyCategory']), $learner_types);

            $inductee->save($link);

	    $induction = new Induction();
            $induction->inductee_id = $inductee->id;
            $induction->induction_date = $row['InductionDate'];
            $induction->brm = $row['EngagementManager'];
            $induction->lead_gen = $row['BusinessConsultant'];
            $induction->resourcer = $row['CandidateRecruiter'];
            $induction->emp_recruiter = isset($row['EmployerRecruiter']) ? $row['EmployerRecruiter'] : '';
            $induction->arm = isset($row['AccountManager']) ? $row['AccountManager'] : '';
            $induction->app_opp_concern = isset($row['ApprovedOpportunityConcerns']) ? $row['ApprovedOpportunityConcerns'] : '';
            //$induction->math_cert = array_search(trim($row['FunctionalSkillsStatusMaths']), $certs_list);
            //$induction->eng_cert = array_search(trim($row['FunctionalSkillsStatusEnglish']), $certs_list);
	    $induction->maths_gcse_elig_met = array_search(trim($row['FunctionalSkillsStatusMaths']), $certs_list);
            $induction->wfd_assessment = array_search(trim($row['FunctionalSkillsStatusEnglish']), $certs_list);
            $induction->placement_id = isset($row['PlacementName']) ? $row['PlacementName'] : '';
            $induction->learner_concerns = isset($row['Red_Flag_Details__c']) ? $row['Red_Flag_Details__c'] : '';

            $induction->save($link);
        }

	echo isset($inductee) ? $inductee->id : 0;

    }

    private function saveTabInSession(PDO $link)
    {
        if(isset($_REQUEST['selected_tab']) && $_REQUEST['selected_tab'] != '')
            $_SESSION['ViewInductionSelectedTab'] = $_REQUEST['selected_tab'];
    }

    private function importLearnersFromSalesforce(PDO $link)
    {
        $FirstName = isset($_REQUEST['FirstName'])?$_REQUEST['FirstName']:'';
        $LastName = isset($_REQUEST['LastName'])?$_REQUEST['LastName']:'';
        $Birthdate = isset($_REQUEST['Birthdate'])?$_REQUEST['Birthdate']:'';
        $National_Insurance__c = isset($_REQUEST['National_Insurance__c'])?$_REQUEST['National_Insurance__c']:'';

        if($FirstName == '' && $LastName == '' && $Birthdate == '' && $National_Insurance__c == '')
            throw new Exception('No input given.');

        $Birthdate = $Birthdate != ''?Date::toMySQL($Birthdate):'';

        $sql = new SQLStatement("
SELECT 
       Id,
       FirstName, 
       LastName, 
       Gender__c, 
       Birthdate, 
       National_Insurance__c, 
       Phone, 
       MobilePhone, 
       Email, 
       Ethnicity__c,
       Safeguarding_Next_of_Kin_Email__c, 
       Safeguarding_Next_of_Kin_Telephone_No__c, 
       Safeguarding_Next_of_Kin__c  
FROM 
     Contact");
        if($FirstName != '')
            $sql->setClause("WHERE FirstName LIKE '%{$FirstName}%'");
        if($LastName != '')
            $sql->setClause("WHERE LastName LIKE '%{$LastName}%'");
        if($Birthdate != '')
            $sql->setClause("WHERE Birthdate = {$Birthdate}");
        if($National_Insurance__c != '')
            $sql->setClause("WHERE National_Insurance__c LIKE '%{$National_Insurance__c}%'");

        $html = '';
        ini_set("soap.wsdl_cache_enabled", "0");
        require_once ('lib/salesforce/SforceEnterpriseClient.php');
        $mySforceConnection = new SforceEnterpriseClient();
        $mySoapClient = $mySforceConnection->createConnection("lib/salesforce/enterprise.wsdl.xml");

        $sf_username = SystemConfig::getEntityValue($link, 'salesforce_username');
        $sf_password = SystemConfig::getEntityValue($link, 'salesforce_password');
        $sf_token = SystemConfig::getEntityValue($link, 'salesforce_token');
        if($sf_username == '' || $sf_password == '' || $sf_token == '')
            throw new Exception('Missing Salesforce authentication details');

        //$mylogin = $mySforceConnection->login("inaam@compact-soft.com", "Thegr88tsTBsbL1eJe8xFoPZDqdPGtfeDSz");
        $mylogin = $mySforceConnection->login($sf_username, $sf_password.$sf_token);

        $sfRecords = array();
        $importedRecords = DAO::getSingleColumn($link, "SELECT sf_Id FROM inductees");

	    $EmploymentStartDate = '';
        $InductionDate = '';
        $EngagementManager = '';
        $BusinessConsultant = '';
        $CandidateRecruiter = '';
        $VacanySource = '';
        $VacanyCategory = '';
        $PaidWorkingHours = '';
        $AnnualSalary = '';
        $LevyPayer = '';
        $FunctionalSkillsStatusEnglish = '';
        $FunctionalSkillsStatusMaths = '';

        $response = $mySforceConnection->query($sql->__toString());//throw new Exception(json_encode($response));
        $queryResult = new QueryResult($response);
        foreach($queryResult->records AS $record)
        {
            $html .= '<tr>';
            $html .= isset($record->Id) ? '<td>' . $record->Id . '</td>':'<td></td>';
            $html .= isset($record->FirstName) ? '<td>' . $record->FirstName . '</td>':'<td></td>';
            $html .= isset($record->LastName) ? '<td>' . $record->LastName . '</td>':'<td></td>';
            $html .= isset($record->Gender__c) ? '<td>' . $record->Gender__c . '</td>':'<td></td>';
            $html .= isset($record->Birthdate) ? '<td>' . Date::toShort($record->Birthdate) . '</td>':'<td></td>';
            $html .= isset($record->National_Insurance__c) ? '<td>' . $record->National_Insurance__c . '</td>':'<td></td>';
            $html .= isset($record->Phone) ? '<td>' . $record->Phone . '</td>':'<td></td>';
            $html .= isset($record->MobilePhone) ? '<td>' . $record->MobilePhone . '</td>':'<td></td>';
            $html .= isset($record->Email) ? '<td>' . $record->Email . '</td>':'<td></td>';
            $html .= isset($record->Ethnicity__c) ? '<td>' . $record->Ethnicity__c . '</td>':'<td></td>';
            $html .= isset($record->Safeguarding_Next_of_Kin__c) ? '<td>' . $record->Safeguarding_Next_of_Kin__c . '</td>':'<td></td>';
            $html .= isset($record->Safeguarding_Next_of_Kin_Telephone_No__c) ? '<td>' . $record->Safeguarding_Next_of_Kin_Telephone_No__c . '</td>':'<td></td>';
            $html .= isset($record->Safeguarding_Next_of_Kin_Email__c) ? '<td>' . $record->Safeguarding_Next_of_Kin_Email__c . '</td>':'<td></td>';

	        if(isset($record->Id) && $record->Id != '')
            {
                $placement_sql = "SELECT Id, Functional_Skills_Status_Maths__c, Functional_Skills_Status_English__c, ts2__Start_Date__c, ts2__Job__c, Induction_Date__c, Vac_Source__c, Vacancy_Category__c, Paid_Hours__c, Annual_Salary__c, Levy_Payer__c  FROM ts2__Placement__c WHERE ts2__Employee__c = '{$record->Id}'";
                $placement_response = $mySforceConnection->query($placement_sql);
                $placement_queryResult = new QueryResult($placement_response);

                $placement_queryLastResponse = $mySforceConnection->getLastResponse();
                $placement_queryLastResponse = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $placement_queryLastResponse);
                $placement_queryLastResponseXml = new SimpleXMLElement($placement_queryLastResponse);
		
                foreach($placement_queryResult->records AS $placement_record)
                {
                    if(isset($placement_record->ts2__Job__c))
                    {
                        $html .= '<td>';

                        $html .= isset($placement_record->ts2__Start_Date__c) ? '<span class="text-bold">Employment Start Date:</span> ' . Date::toShort($placement_record->ts2__Start_Date__c) . '<br>' : '';
			            $EmploymentStartDate = isset($placement_record->ts2__Start_Date__c) ? $placement_record->ts2__Start_Date__c : '';
                        $html .= isset($placement_record->Induction_Date__c) ? '<span class="text-bold">Induction Date:</span> ' . Date::toShort($placement_record->Induction_Date__c) . '<br>' : '';
			            $InductionDate = isset($placement_record->Induction_Date__c) ? $placement_record->Induction_Date__c : '';
			$FunctionalSkillsStatusMaths = isset($placement_record->Functional_Skills_Status_Maths__c) ? $placement_record->Functional_Skills_Status_Maths__c : '';
			            $FunctionalSkillsStatusEnglish = isset($placement_record->Functional_Skills_Status_English__c) ? $placement_record->Functional_Skills_Status_English__c : '';
                        if(isset($placement_record->Functional_Skills_Status_Maths__c))
                        {
                            $html .= '<span class="text-bold">FunctionalSkillsStatusMaths:</span> ' . $placement_record->Functional_Skills_Status_Maths__c . '<br>';
                            $FunctionalSkillsStatusMaths = $placement_record->Functional_Skills_Status_Maths__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFunctional_Skills_Status_Maths__c))
                        {
                            $html .= '<span class="text-bold">FunctionalSkillsStatusMaths:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFunctional_Skills_Status_Maths__c->__toString() . '<br>';
                            $FunctionalSkillsStatusMaths = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFunctional_Skills_Status_Maths__c->__toString();
                        }
			if(isset($placement_record->Functional_Skills_Status_English__c))
                        {
                            $html .= '<span class="text-bold">FunctionalSkillsStatusEnglish:</span> ' . $placement_record->Functional_Skills_Status_English__c . '<br>';
                            $FunctionalSkillsStatusEnglish = $placement_record->Functional_Skills_Status_English__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFunctional_Skills_Status_English__c))
                        {
                            $html .= '<span class="text-bold">FunctionalSkillsStatusEnglish:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFunctional_Skills_Status_English__c->__toString() . '<br>';
                            $FunctionalSkillsStatusEnglish = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFunctional_Skills_Status_English__c->__toString();
                        }
                        if(isset($placement_record->Vac_Source__c))
                        {
                            $html .= '<span class="text-bold">Vacancy Source:</span> ' . $placement_record->Vac_Source__c . '<br>';
                            $VacanySource = $placement_record->Vac_Source__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVac_Source__c))
                        {
                            $html .= '<span class="text-bold">Vacancy Source:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVac_Source__c->__toString() . '<br>';
                            $VacanySource = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVac_Source__c->__toString();
                        }
                        if(isset($placement_record->Vacancy_Category__c))
                        {
                            $html .= '<span class="text-bold">Vacancy Category:</span> ' . $placement_record->Vacancy_Category__c . '<br>';
                            $VacanyCategory = $placement_record->Vacancy_Category__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Category__c))
                        {
                            $html .= '<span class="text-bold">Vacancy Category:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Category__c->__toString() . '<br>';
                            $VacanyCategory = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Category__c->__toString();
                        }
                        if(isset($placement_record->Paid_Hours__c))
                        {
                            $html .= '<span class="text-bold">Vacancy Category:</span> ' . $placement_record->Paid_Hours__c . '<br>';
                            $PaidWorkingHours = $placement_record->Paid_Hours__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfPaid_Hours__c))
                        {
                            $html .= '<span class="text-bold">Paid Working Hours:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfPaid_Hours__c->__toString() . '<br>';
                            $PaidWorkingHours = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfPaid_Hours__c->__toString();
                        }
                        if(isset($placement_record->Annual_Salary__c))
                        {
                            $html .= '<span class="text-bold">Annual Salary:</span> ' . $placement_record->Annual_Salary__c . '<br>';
                            $AnnualSalary = $placement_record->Annual_Salary__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfAnnual_Salary__c))
                        {
                            $html .= '<span class="text-bold">Annual Salary:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfAnnual_Salary__c->__toString() . '<br>';
                            $AnnualSalary = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfAnnual_Salary__c->__toString();
                        }
                        if(isset($placement_record->Levy_Payer__c))
                        {
                            $html .= '<span class="text-bold">Levy Payer:</span> ' . $placement_record->Levy_Payer__c . '<br>';
                            $LevyPayer = $placement_record->Levy_Payer__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfLevy_Payer__c))
                        {
                            $html .= '<span class="text-bold">Levy Payer:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfLevy_Payer__c->__toString() . '<br>';
                            $LevyPayer = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfLevy_Payer__c->__toString();
                        }
    
                        $job_sql = "SELECT Id, Vacancy_Source__c, Vacancy_Category__c, Paid_Working_Hours__c, Annual_Salary__c, Levy_Payer__c, Business_Development_Manager__c, Business_Development_Coordinator__c, ts2__Recruiter__c, Employer_Recruiter__c FROM ts2__Job__c WHERE Id = '{$placement_record->ts2__Job__c}'";
                        $job_response = $mySforceConnection->query($job_sql);
                        $job_queryResult = new QueryResult($job_response);
                        foreach($job_queryResult->records AS $job_record)
                        {
                            if(isset($job_record->Id))
                            {
                                if(isset($job_record->Business_Development_Manager__c) && $job_record->Business_Development_Manager__c != '')
                                {
                                    $engagement_manager_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$job_record->Business_Development_Manager__c}'");
                                    $em_queryResult = new QueryResult($engagement_manager_response);
                                    foreach($em_queryResult AS $em_record)
                                    {
                                        $html .= '<span class="text-bold">Engagement Manager/ Specialist:</span> ';
                                        $html .= isset($em_record->FirstName) ? $em_record->FirstName . ' ' : '';
                                        $html .= isset($em_record->LastName) ? $em_record->LastName . '<br>' : '<br>';
					                    $EngagementManager = isset($em_record->FirstName) ? $em_record->FirstName . ' ' : '';
                                        $EngagementManager .= isset($em_record->LastName) ? $em_record->LastName . ' ' : '';
                                    }
                                }
                                if(isset($job_record->Business_Development_Coordinator__c) && $job_record->Business_Development_Coordinator__c != '')
                                {
                                    $bc_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$job_record->Business_Development_Coordinator__c}'");
                                    $bc_queryResult = new QueryResult($bc_response);
                                    foreach($bc_queryResult AS $bc_record)
                                    {
                                        $html .= '<span class="text-bold">Business Consultant:</span> ';
                                        $html .= isset($bc_record->FirstName) ? $bc_record->FirstName . ' ' : '';
                                        $html .= isset($bc_record->LastName) ? $bc_record->LastName . '<br>' : '<br>';
					                    $BusinessConsultant = isset($bc_record->FirstName) ? $bc_record->FirstName . ' ' : '';
                                        $BusinessConsultant .= isset($bc_record->LastName) ? $bc_record->LastName . ' ' : '';
                                    }
                                }
                                if(isset($job_record->ts2__Recruiter__c) && $job_record->ts2__Recruiter__c != '')
                                {
                                    $cr_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$job_record->ts2__Recruiter__c}'");
                                    $cr_queryResult = new QueryResult($cr_response);
                                    foreach($cr_queryResult AS $cr_record)
                                    {
                                        $html .= '<span class="text-bold">Candidate Recruiter:</span> ';
                                        $html .= isset($cr_record->FirstName) ? $cr_record->FirstName . ' ' : '';
                                        $html .= isset($cr_record->LastName) ? $cr_record->LastName . '<br>' : '<br>';
					                    $CandidateRecruiter = isset($cr_record->FirstName) ? $cr_record->FirstName . ' ' : '';
                                        $CandidateRecruiter .= isset($cr_record->LastName) ? $cr_record->LastName . ' ' : '';
                                    }
                                }
                                $html .= isset($job_record->Employer_Recruiter__c) ? '<span class="text-bold">Employer Recruiter:</span> ' . $job_record->Employer_Recruiter__c . '<br>' : '';
                            }
                        }
                        
                        $html .= '</td>';
    
                    }
                }
            }
            else
            {
                echo '<td></td>';
            }

            if(!in_array($record->Id, $importedRecords))
                $html .= '<td><input class="chkSelectedLearners" type="checkbox" name="selectedLearners[]" value="' . $record->Id . '" /></td>';
            else
                $html .= '<td><i class="fa fa-link" title="Already imported and created in Sunesis"></i> </td>';
            $html .= '</tr>';

            if(isset($record->Id) && $record->Id != '')
            {
                $l = new stdClass();
                $l->Id = $record->Id;
                $l->FirstName = isset($record->FirstName)?$record->FirstName:'';
                $l->LastName = isset($record->LastName)?$record->LastName:'';
                $l->Gender__c = isset($record->Gender__c)?$record->Gender__c:'';
                $l->Birthdate = isset($record->Birthdate)?$record->Birthdate:'';
                $l->National_Insurance__c = isset($record->National_Insurance__c)?$record->National_Insurance__c:'';
                $l->Phone = isset($record->Phone)?$record->Phone:'';
                $l->MobilePhone = isset($record->MobilePhone)?$record->MobilePhone:'';
                $l->Email = isset($record->Email)?$record->Email:'';
                $l->Ethnicity__c = isset($record->Ethnicity__c)?$record->Ethnicity__c:'';
                $l->Safeguarding_Next_of_Kin__c = isset($record->Safeguarding_Next_of_Kin__c)?$record->Safeguarding_Next_of_Kin__c:'';
                $l->Safeguarding_Next_of_Kin_Telephone_No__c = isset($record->Safeguarding_Next_of_Kin_Telephone_No__c)?$record->Safeguarding_Next_of_Kin_Telephone_No__c:'';
                $l->Safeguarding_Next_of_Kin_Email__c = isset($record->Safeguarding_Next_of_Kin_Email__c)?$record->Safeguarding_Next_of_Kin_Email__c:'';
		        $l->EmploymentStartDate = $EmploymentStartDate;
                $l->InductionDate = $InductionDate;
                $l->EngagementManager = $EngagementManager;
                $l->BusinessConsultant = $BusinessConsultant;
                $l->CandidateRecruiter = $CandidateRecruiter;
                $l->VacanySource = $VacanySource;
                $l->VacanyCategory = $VacanyCategory;
                $l->PaidWorkingHours = $PaidWorkingHours;
                $l->AnnualSalary = $AnnualSalary;
                $l->LevyPayer = $LevyPayer;
                $l->FunctionalSkillsStatusMaths = $FunctionalSkillsStatusMaths;
                $l->FunctionalSkillsStatusEnglish = $FunctionalSkillsStatusEnglish;
                $sfRecords[] = $l;
            }
        }

        $html .= '<tr><td colspan="13"><span class="btn btn-sm btn-primary pull-right" onclick="createLearnerInSunesis();"><i class="fa fa-plus"></i> Create in Sunesis</span></td></tr>';

        DAO::multipleRowInsert($link, 'sf_contacts', $sfRecords);
        return $html;
    }

    private function isL4Programme(PDO $link)
    {
        $programme_id = isset($_REQUEST['programme_id'])?$_REQUEST['programme_id']:'';
        if($programme_id == '')
            return '';

        $l4 = DAO::getSingleValue($link, "SELECT courses.l4 FROM courses WHERE courses.id = '{$programme_id}'");
        if($l4 == 'Y')
            return 'yes';

        return '';
    }

    private function isValidForCompletedStatus(PDO $link)
    {
        $inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
        if($inductee_id == '')
            throw new Exception('Missing querystring argument: inductee_id');

        $inductee = Inductee::loadFromDatabase($link, $inductee_id);
        $induction = $inductee->inductions[0]; /* @var $induction Induction */

        $validation = '';
        if(trim($inductee->ni) == '')
            $validation .= '- National insurance number is blank';
        if(is_null($inductee->employer_id))
            $validation .= '- Employer is not selected';
        if(is_null($induction->induction_date))
            $validation .= '- Induction date is blank';
        if($induction->moredle_account != 'Y')
            $validation .= '- Moredle account is not selected as \'Yes\'';
        if(is_null($induction->iag_literacy))
            $validation .= '- Literacy level is blank';
        if(is_null($induction->iag_numeracy))
            $validation .= '- Numeracy level is blank';
        if(is_null($induction->iag_ict))
            $validation .= '- ICT level is blank';
        if(is_null($induction->sla_received) || $induction->sla_received == 'N' || $induction->sla_received == 'R')
            $validation .= '- SLA received should be \'Yes New\' or \'Yes Old\'';
        if(is_null($induction->sla_start_date))
            $validation .= '- SLA start date is blank';
        if(is_null($induction->sla_end_date))
            $validation .= '- SLA end date is blank';
        if($induction->commit_statement != 'FC')
            $validation .= '- Commitment statement should be \'Fully Completed\'';
        if(is_null($induction->expected_end_date))
            $validation .= '- Expected end date is blank';
        if(is_null($induction->planned_end_date))
            $validation .= '- Planned end date is blank';

        return $validation;
    }

    private function getUniqueUsername(PDO $link, $firstnames, $surname)
    {
        $number_of_attempts = 0;
        $i = 1;
        do
        {
            $number_of_attempts++;
            if($number_of_attempts > 18)
            {
                $username = '';
                break;
            }
            $username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 20));
            $username = str_replace(' ', '', $username);
            $username = str_replace("'", '', $username);
            $username = str_replace('"', '', $username);
            $username = $username . $i;
            $i++;
        }while((int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE username = '$username'") > 0);
        if($username == '' || is_null($username))
            $username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 15)) . date('is');
        return strtolower($username);
    }

    private function createNewSunesisAccount(PDO $link)
    {
        $inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
        if($inductee_id == '')
            throw new Exception('Missing querystring argument: inductee_id');

        $inductee = Inductee::loadFromDatabase($link, $inductee_id);
        if(is_null($inductee))
            throw new Exception('Invalid Inductee learner id');

        $sunesis_learner = new User();
        $sunesis_learner->type = User::TYPE_LEARNER;
        $sunesis_learner->populate($inductee);
        $sunesis_learner->id = NULL; // populate method will put inductee id into user object
        $sunesis_learner->username = $this->getUniqueUsername($link, $sunesis_learner->firstnames, $sunesis_learner->surname);
        $emp_location = DAO::getObject($link, "SELECT * FROM locations WHERE locations.id = '{$inductee->employer_location_id}'"); /* @var $emp_location Location */
        $sunesis_learner->work_address_line_1 = $emp_location->address_line_1;
        $sunesis_learner->work_address_line_2 = $emp_location->address_line_2;
        $sunesis_learner->work_address_line_3 = $emp_location->address_line_3;
        $sunesis_learner->work_address_line_4 = $emp_location->address_line_4;
        $sunesis_learner->work_telephone = $emp_location->telephone;
        $sunesis_learner->work_postcode = $emp_location->postcode;
        $sunesis_learner->web_access = 0;
        $sunesis_learner->who_created = $_SESSION['user']->username;
        $sunesis_learner->learner_work_email = $inductee->work_email;
        do
        {
            $pwd = PasswordUtilities::generateDatePassword();
            $pwd = PasswordUtilities::randomCapitalisation($pwd, 1);
            $pwd = PasswordUtilities::replaceSpacesWithNumbers($pwd);
            $validationResults = PasswordUtilities::checkPasswordStrength($pwd, PasswordUtilities::getIllegalWords());
        } while($validationResults['code'] == 0);
        $sunesis_learner->password = $pwd;
        $sunesis_learner->pwd_sha1 = sha1($pwd);

        $induction = $inductee->inductions[0]; /* @var $induction Induction */

        $sunesis_learner->numeracy = DAO::getSingleValue($link, "SELECT id FROM lookup_pre_assessment WHERE new_id = '{$induction->iag_numeracy}'");
        $sunesis_learner->literacy = DAO::getSingleValue($link, "SELECT id FROM lookup_pre_assessment WHERE new_id = '{$induction->iag_literacy}'");
        $sunesis_learner->ict = DAO::getSingleValue($link, "SELECT id FROM lookup_pre_assessment WHERE new_id = '{$induction->iag_ict}'");
        $sunesis_learner->save($link);

        $inductee->sunesis_username = $sunesis_learner->username;
        $inductee->save($link);

        $training_provider_location_id = isset($_REQUEST['training_provider_location_id'])?$_REQUEST['training_provider_location_id']:'';
        $training_contract_id = isset($_REQUEST['training_contract_id'])?$_REQUEST['training_contract_id']:'';
        $course_id = $inductee->inductionProgramme->programme_id;
        $framework_id = DAO::getSingleValue($link, "SELECT framework_id FROM courses WHERE courses.id = '{$course_id}'");

        if($training_provider_location_id != '' && $training_contract_id != '' && $course_id != '' && $framework_id != '')
        {
            $url_string = '&username=' . $sunesis_learner->username;
            $url_string .= '&framework_id=' . $framework_id;
            $url_string .= '&course_id=' . $course_id;
            $url_string .= '&provider_location_id=' . $training_provider_location_id;
            $url_string .= '&contract_id=' . $training_contract_id;
            $url_string .= '&start_date=' . $induction->induction_date;
            $url_string .= '&end_date=' . $induction->planned_end_date;
            $url_string .= '&assessor=' . $induction->assigned_assessor;
            $url_string .= '&coordinator=' . $induction->assigned_coord;
	    $url_string .= '&inductee_id=' . $inductee_id;

            http_redirect('do.php?_action=save_start_training'.$url_string);
        }

        return 'success';
    }

    private function load_employer_locations(PDO $link)
    {
        header('Content-Type: text/xml');
        $employer_id = isset($_REQUEST['employer_id'])?$_REQUEST['employer_id']:'';
        if($employer_id == '')
        {
            throw new Exception("Missing querystring argument 'employer_id'");
        }

        $sql = <<<HEREDOC
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE locations.organisations_id = '$employer_id'
ORDER BY full_name
;
HEREDOC;


        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";

            // First entry is empty
            echo "<option value=\"\"></option>\r\n";

            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }

            echo '</select>';

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

    }

    private function load_employer_contacts(PDO $link)
    {
        header('Content-Type: text/xml');
        $employer_id = isset($_REQUEST['employer_id'])?$_REQUEST['employer_id']:'';
        if($employer_id == '')
        {
            throw new Exception("Missing querystring argument 'employer_id'");
        }

        $sql = <<<HEREDOC
SELECT
  contact_id, CONCAT(
  COALESCE(contact_name),
  ' (',
  COALESCE(`contact_department`, ''),
  ', ',
  COALESCE(`contact_email`, ''),
  ', ',
  COALESCE(`contact_telephone`, ''),
  ', ',
  COALESCE(`contact_mobile`, ''),
  ')'
), null
FROM
  organisation_contact
WHERE
  org_id = '$employer_id'
ORDER BY contact_name
;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";

            // First entry is empty
            echo "<option value=\"\"></option>\r\n";

            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }

            echo '</select>';

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

    }

    public function checkNewInducteeDuplicates(PDO $link)
    {
        $firstnames = isset($_REQUEST['firstnames'])?$_REQUEST['firstnames']:'';
        $surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';
        $dob = isset($_REQUEST['dob'])?$_REQUEST['dob']:'';
        $dob = Date::to($dob, Date::MYSQL);

        $c = substr($firstnames, 0, 1);

        $firstnames = $link->quote($this->prepareFieldValue($firstnames));
        $surname = $link->quote($this->prepareFieldValue($surname));
        $dob = $link->quote($this->prepareFieldValue($dob));

        $sql = new SQLStatement("SELECT inductees.id, inductees.firstnames, inductees.surname, inductees.dob,
			inductees.created,
			(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = inductees.created_by) AS created_by,
			LEVENSHTEIN_RATIO (firstnames, $firstnames) AS r1,
  			LEVENSHTEIN_RATIO (surname, $surname) AS r2,
  			LEVENSHTEIN(dob, $dob) AS r3
			 FROM inductees ");
        $sql->setClause("WHERE inductees.firstnames LIKE '$c%'");
        $sql->setClause("HAVING (r1+r2) > 120 AND r3 <= 2");
        $sql->setClause("ORDER BY (r1+r2) DESC");

        $existing_records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);

        if(count($existing_records) == 0)
            return;

        echo json_encode($existing_records);
    }

    public function createLinkedSunesisAccount(PDO $link)
    {
        $inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
        $training_provider_location_id = isset($_REQUEST['training_provider_location_id'])?$_REQUEST['training_provider_location_id']:'';
        $training_contract_id = isset($_REQUEST['training_contract_id'])?$_REQUEST['training_contract_id']:'';
        $selectedLearnerForConversion = isset($_REQUEST['selectedLearnerForConversion'])?$_REQUEST['selectedLearnerForConversion']:'';

        if($inductee_id == '')
            throw new Exception('Missing querystring argument: inductee_id');
        if($selectedLearnerForConversion == '')
            throw new Exception('Missing querystring argument: selectedLearnerForConversion');

        $inductee = Inductee::loadFromDatabase($link, $inductee_id);
        if(is_null($inductee))
            throw new Exception('Invalid Inductee learner id');

        $sunesis_learner = User::loadFromDatabaseById($link, $selectedLearnerForConversion);
        if(is_null($sunesis_learner))
            throw new Exception('Invalid existing learner id');

        $sunesis_learner->firstnames = $inductee->firstnames;
        $sunesis_learner->surname = $inductee->surname;
        $sunesis_learner->gender = $inductee->gender;
        $sunesis_learner->home_telephone = $inductee->home_telephone;
        $sunesis_learner->home_mobile = $inductee->home_mobile;
        $sunesis_learner->home_email = $inductee->home_email;
        $sunesis_learner->work_email = $inductee->work_email;
        $sunesis_learner->learner_work_email = $inductee->work_email;
        $sunesis_learner->employer_id = $inductee->employer_id;
        $sunesis_learner->employer_location_id = $inductee->employer_location_id;
        $sunesis_learner->save($link);

        $induction = $inductee->inductions[0]; /* @var $induction Induction */
        $inductee->sunesis_username = $sunesis_learner->username;
        $inductee->save($link);

        $course_id = $inductee->inductionProgramme->programme_id;
        $framework_id = DAO::getSingleValue($link, "SELECT framework_id FROM courses WHERE courses.id = '{$course_id}'");
        $url_string = '&username=' . $sunesis_learner->username;
        $url_string .= '&framework_id=' . $framework_id;
        $url_string .= '&course_id=' . $course_id;
        $url_string .= '&provider_location_id=' . $training_provider_location_id;
        $url_string .= '&contract_id=' . $training_contract_id;
        $url_string .= '&start_date=' . $induction->induction_date;
        $url_string .= '&end_date=' . $induction->planned_end_date;
        $url_string .= '&assessor=' . $induction->assigned_assessor;
        $url_string .= '&coordinator=' . $induction->assigned_coord;
	$url_string .= '&inductee_id=' . $inductee_id;

        http_redirect('do.php?_action=save_start_training'.$url_string);

        return 'success';
    }

    public function checkExistingRecordsBeforeCreation(PDO $link)
    {
        $firstnames = isset($_REQUEST['firstnames'])?$_REQUEST['firstnames']:'';
        $surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';
        $dob = isset($_REQUEST['dob'])?$_REQUEST['dob']:'';

        $c = substr($firstnames, 0, 1);

        $firstnames = $link->quote($this->prepareFieldValue($firstnames));
        $surname = $link->quote($this->prepareFieldValue($surname));
        $dob = $link->quote($this->prepareFieldValue($dob));

        $sql = new SQLStatement("SELECT users.id, users.firstnames, users.surname, DATE_FORMAT(users.dob, '%d/%m/%Y') AS date_of_birth,
			users.username,users.home_address_line_1, users.home_postcode,ni,gender,
			LEVENSHTEIN_RATIO (firstnames, $firstnames) AS r1,
  			LEVENSHTEIN_RATIO (surname, $surname) AS r2,
  			LEVENSHTEIN(dob, $dob) AS r3
			 FROM users ");
        $sql->setClause("WHERE users.firstnames LIKE '$c%'");
        $sql->setClause("WHERE users.type = '5'");
        $sql->setClause("HAVING (r1+r2) > 120 AND r3 <= 2");
        $sql->setClause("ORDER BY (r1+r2) DESC");

        $existing_records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);

        if(count($existing_records) == 0)
        {
            echo json_encode('no_matching_records');
            return;
        }

        echo json_encode($existing_records);
    }

    private function prepareFieldValue($field_value)
    {
        return trim(strtolower($field_value));
    }

    public function getInductionNotes(PDO $link)
    {
        $induction_id = isset($_REQUEST['induction_id'])?$_REQUEST['induction_id']:'';
        $note_type = isset($_REQUEST['note_type'])?$_REQUEST['note_type']:'';

        if($induction_id == '')
            throw new Exception('No induction id given');

        $html = '<table class="table callout">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT induction.{$note_type} FROM induction WHERE induction.id = '{$induction_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo '<small>' . $html . '</small>';
    }

    public function getOperationsNotes(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $note_type = isset($_REQUEST['note_type'])?$_REQUEST['note_type']:'';

        if($tr_id == '')
            throw new Exception('No tr id given');

        $html = '<table class="table callout">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT tr_operations.{$note_type} FROM tr_operations WHERE tr_id = '{$tr_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo '<small>' . $html . '</small>';
    }

    public function showProjectCheckinNotes(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

        if($tr_id == '')
            throw new Exception('No tr id given');

        $html = '<table class="table callout">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Check in Date</th><th>Check in Comments</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT tr_operations.project_checkin FROM tr_operations WHERE tr_id = '{$tr_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . $note->Date . '</td>';
                $html .= '<td>' . html_entity_decode($note->Comments) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo '<small>' . $html . '</small>';
    }

    public function getInducteeNotes(PDO $link)
    {
        $inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
        $note_type = isset($_REQUEST['note_type'])?$_REQUEST['note_type']:'';

        if($inductee_id == '')
            throw new Exception('No id given');

        $html = '<table class="table callout">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT inductees.{$note_type} FROM inductees WHERE inductees.id = '{$inductee_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo '<small>' . $html . '</small>';
    }

    public function getInductionProgrammeNotes(PDO $link)
    {
        $programme_id = isset($_REQUEST['programme_id'])?$_REQUEST['programme_id']:'';
        $note_type = isset($_REQUEST['note_type'])?$_REQUEST['note_type']:'';

        if($programme_id == '')
            throw new Exception('No induction programme id given');

        $html = '<table class="table callout">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT induction_programme.{$note_type} FROM induction_programme WHERE induction_programme.id = '{$programme_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= $note_type == 'programme_notes' ? '<td>' . html_entity_decode($note->Note) . '</td>' : '<td>' . html_entity_decode($note->Comment) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo '<small>' . $html . '</small>';
    }

    public function getFrameworkTrackingQualifications(PDO $link)
    {
        $framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
        if($framework_id == '')
            return;

        $records = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, internaltitle FROM framework_qualifications WHERE framework_id = '{$framework_id}' AND LOCATE('track=\"true\"', evidences) > 0", DAO::FETCH_ASSOC);
        if(count($records) > 0)
        {
            foreach($records AS $row)
            {
                echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
            }
        }
        else
        {
            echo '<option value="">No qualification with tracking unit(s) found</option>';
        }
    }

    public function getTrackerUnits(PDO $link)
    {
        $tracker_id = isset($_REQUEST['tracker_id']) ? $_REQUEST['tracker_id'] : '';

        if ($tracker_id == '')
            return;

        $sql = <<<HEREDOC
SELECT
	 unit_ref, unit_ref, (SELECT frameworks.title FROM frameworks WHERE frameworks.id = op_tracker_units.framework_id) AS framework
FROM
	 op_tracker_units
WHERE
	 tracker_id = '$tracker_id' ;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";

            // First entry is empty
            echo "<option value=\"\"></option>\r\n";
            $current_option_group = null;
            while($row = $st->fetch())
            {
                $option_group = $row[2];
                if( !is_null($option_group) && $option_group !== '' && $option_group != $current_option_group )
                {
                    // Close current option group if set
                    if(!is_null($current_option_group))
                    {
                        echo "</optgroup>\r\n";
                    }

                    // Begin new option group
                    echo '<optgroup label="', htmlspecialchars((string)$option_group), "\">\r\n";

                    // Record current option group for next iteration
                    $current_option_group = $option_group;
                }
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }

            echo '</select>';

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function getTrackingUnits(PDO $link)
    {
        $frameworks = isset($_REQUEST['frameworks']) ? $_REQUEST['frameworks'] : '';

        if ($frameworks == '')
            return;

        $units_ddl = array();
        $qualifications = DAO::getSingleColumn($link, "SELECT REPLACE(id, '/', '') AS id FROM framework_qualifications WHERE framework_id IN '{$frameworks}' AND LOCATE('track=\"true\"', evidences) > 0 ;", DAO::FETCH_ASSOC);
        foreach($qualifications AS $qualification_id)
        {
            $units = array();
            $sql = <<<HEREDOC
SELECT
	 framework_qualifications.id,
	 framework_qualifications.evidences
FROM
	 framework_qualifications
WHERE
	 framework_qualifications.framework_id = '$framework_id' AND REPLACE(framework_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

            $student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            foreach ($student_qualifications AS $qualification)
            {
                $evidence = XML::loadSimpleXML($qualification['evidences']);

                $units = $evidence->xpath('//unit');
                $q_units = array();
                foreach ($units AS $unit)
                {
                    $temp = array();
                    $temp = (array)$unit->attributes();
                    if(!isset($temp['op_title'])) continue;
                    $temp = $temp['@attributes'];
                    if(!isset($temp['op_title'])) continue;
                    $temp['op_title'] = str_replace('/','', $temp['op_title']);
                    if($temp['track'] == 'true')
                        $q_units[] = $temp;
                }
                $units_ddl[] = $q_units;
            }
        }
        /*
        $s =  "<?xml version=\"1.0\" ?>\r\n";
        $s .=  "<select>\r\n";
        */
        $s = "<option value=\"\"></option>\r\n";
        foreach($units_ddl AS $unit_entry)
        {
            for($i=0;$i<count($unit_entry);$i++)
                $s .= '<option value="' . htmlspecialchars((string)$unit_entry[$i]['op_title']) . '">' . htmlspecialchars((string)$unit_entry[$i]['title']) . "</option>\r\n";
        }

        //$s .= '</select>';
        echo $s;

    }

    private function addLearnerToSession(PDO $link)
    {
        $session_id = isset($_REQUEST['session_id'])?$_REQUEST['session_id']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

        if($session_id == '' || $tr_id == '')
            throw new Exception('Missing querystring arguments: session_id, tr_id');

        $session = OperationsSession::loadFromDatabase($link, $session_id);
        $session->addEntry($link, $tr_id);

        unset($session);
    }

    private function removeLearnerFromSession(PDO $link)
    {
        $session_id = isset($_REQUEST['session_id'])?$_REQUEST['session_id']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

        if($session_id == '' || $tr_id == '')
            throw new Exception('Missing querystring arguments: session_id, tr_id');

        $session = OperationsSession::loadFromDatabase($link, $session_id);
        $session->removeEntry($link, $tr_id);

        unset($session);
    }

    private function saveOpSessionTabInSession()
    {
        if(isset($_REQUEST['selected_tab']) && $_REQUEST['selected_tab'] != '')
            $_SESSION['ManageSessionSelectedTab'] = $_REQUEST['selected_tab'];
    }

    private function saveOPSessionRegister(PDO $link)
    {
        $codes = array(
            'AT' => '1',
            'LA' => '2',
            'VL' => '3',
            'AB' => '4'
        );
        $session_id = isset($_REQUEST['session_id'])?$_REQUEST['session_id']:'';
        if($session_id == '')
            throw new Exception('Missing querystring argument: session_id');

        $update_sql = "";

        foreach($_REQUEST AS $key => $value)
        {
            if(substr($key, 0, 16) == "AttendanceStatus")
            {
                $tr_id = str_replace('AttendanceStatus', '', $key);
                $entry_code = isset($codes[$value])?$codes[$value]:'';
                $comments = '';
                if(isset($_REQUEST['comments'.$tr_id]) && trim($_REQUEST['comments'.$tr_id]) != '')
                    $comments = $link->quote($this->saveOPSessionRegisterNotes($link, $session_id, $tr_id, 'comments', trim($_REQUEST['comments'.$tr_id])));
                $update_sql .= "UPDATE session_entries SET entry = '{$entry_code}', comments = {$comments} WHERE entry_session_id = '{$session_id}' AND tr_id = '{$tr_id}'; " . PHP_EOL;
            }
        }
        DAO::execute($link, $update_sql);
    }

    private static  function saveOPSessionRegisterNotes(PDO $link, $session_id, $tr_id, $note_type, $notes)
    {
        if(trim($notes) == '')
            return;
        $notes = str_replace("�", "&pound;", $notes);

        $notes = htmlspecialchars((string)$notes, 16);
        $xml = '';
        $xml = DAO::getSingleValue($link, "SELECT {$note_type} FROM session_entries WHERE entry_session_id = '{$session_id}' AND tr_id = '{$tr_id}'");
        if(is_null($xml) || $xml == '')
            $xml = '<Notes></Notes>';
        $xml = XML::loadSimpleXML($xml);
        $new_note = $xml->addChild('Note');
        $new_note->DateTime = date('Y-m-d H:i:s');
        $new_note->CreatedBy = $_SESSION['user']->id;
        $new_note->NoteType = $note_type;
        $new_note->Note = $notes;
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadXML($xml->saveXML());
        $dom->formatOutput = TRUE;
        $modified_xml = $dom->saveXml();
        $modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

        return $modified_xml;
    }

    public function getNotApplicableTrackers(PDO $link)
    {
        $result = array();
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if($key == '')
            return $result;

        /*		$key = explode('|', $key);
        $owner_reference = $key[0];
        $qualification_id = $key[1];
        $reference = $key[2];
        $framework_id = $key[3];

        $trackers = DAO::getSingleColumn($link, "SELECT DISTINCT tracker_id FROM op_tracker_units");

        foreach($trackers AS $t)
        {
            $exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM op_tracker_units WHERE tracker_id = '{$t}' AND unit_ref = '{$owner_reference}' AND qualification_id = '{$qualification_id}' AND framework_id = '{$framework_id}'");
            if($exists == 0)
                $result[] = $t;
        }*/
        $trackers = DAO::getSingleColumn($link, "SELECT DISTINCT id FROM op_trackers");
        $key = str_replace('&', '&amp;', $key);

        foreach($trackers AS $t)
        {
            $exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM op_tracker_units WHERE tracker_id = '{$t}' AND unit_ref = '{$key}' ");
            if($exists == 0)
                $result[] = $t;
        }
        echo json_encode($result);
    }

    public function getApplicableTrackers(PDO $link)
    {
        $result = array();
        $keys = isset($_REQUEST['key'])?json_decode($_REQUEST['key']):'';
        if(count($keys) == 0)
            return $result;

        foreach($keys AS $key)
        {
            $key = str_replace('&', '&amp;', $key);
            //$tracker_id = DAO::getSingleValue($link, "SELECT tracker_id FROM op_tracker_units WHERE unit_ref = '{$key}' ");
            $tracker_ids = DAO::getSingleColumn($link, "SELECT tracker_id FROM op_tracker_units WHERE unit_ref = '{$key}' ");
            foreach($tracker_ids AS $tracker_id)
            {
                if($tracker_id != '' && !in_array($tracker_id, $result))
                    $result[] = $tracker_id;
            }
        }

        echo json_encode($result);
    }

    public function setSchCode(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $unit_ref = isset($_REQUEST['unit_ref'])?$_REQUEST['unit_ref']:'';
        $sch_code = isset($_REQUEST['sch_code'])?$_REQUEST['sch_code']:'';
        $sch_comments = isset($_REQUEST['sch_comments'])?$_REQUEST['sch_comments']:'';

        if($tr_id == '' || $unit_ref == '' || $sch_code == '')
            throw new Exception('Missing mandatory information');

        $obj = new stdClass();
        $obj->tr_id = $tr_id;
        $obj->unit_ref = $unit_ref;
        $obj->code = $sch_code;
        $obj->comments = $sch_comments;
        $obj->created_by = $_SESSION['user']->id;//throw new Exception(json_encode($obj));
        $saved = DAO::saveObjectToTable($link, "op_tracker_unit_sch", $obj);

	if(DB_NAME == "am_baltic_demo" && $saved)
        {
            $this->updateFsProgress($link, $tr_id, $unit_ref, $sch_code);
        }
    }

    private function updateFsProgress(PDO $link, $tr_id, $unit_ref, $status)
    {
        $fs_progress_id = DAO::getSingleValue($link, "SELECT id FROM fs_progress WHERE tr_id = '{$tr_id}' ORDER BY id DESC LIMIT 1");
        if($fs_progress_id != '')
        {
            $fs_progress = FSProgress::loadFromDatabase($link, $fs_progress_id);
        }
        else
        {
            $fs_progress = new FSProgress();
	    $fs_progress->tr_id = $tr_id;	
        }

        if( trim($unit_ref) == trim('Functional Skills English') )
        {
            if($status == 'R')
            {
                $fs_progress->required = "2"; // English
                $fs_progress->fs_required = "1"; // No Evidence
                $fs_progress->english_course_status = "1"; // Required
                //$fs_progress->english_course_overall_status = "1"; // Required
            }
            if($status == 'NR')
            {
                $fs_progress->english_course_overall_status = "6"; // Not Required
            }
            if($status == 'B')
            {
                $fs_progress->english_course_overall_status = "2"; // Booked
            }
            if($status == 'I')
            {
                $fs_progress->english_course_overall_status = "7"; // Invited
            }
        }
        if( trim($unit_ref) == trim('Functional Skills English Reading Test') )
        {
            if($status == 'R')
            {
                $fs_progress->english_overall_status_reading = "1"; // Required
            }
            if($status == 'NR')
            {
                $fs_progress->english_overall_status_reading = "6"; // Not Required
            }
            if($status == 'B')
            {
                $fs_progress->english_overall_status_reading = "2"; // Booked
            }
            if($status == 'I')
            {
                $fs_progress->english_overall_status_reading = "7"; // Invited
            }
        }
        if( trim($unit_ref) == trim('Functional Skills English Writing Test') )
        {
            if($status == 'R')
            {
                $fs_progress->english_overall_status_writing = "1"; // Required
            }
            if($status == 'NR')
            {
                $fs_progress->english_overall_status_writing = "6"; // Not Required
            }
            if($status == 'B')
            {
                $fs_progress->english_overall_status_writing = "2"; // Booked
            }
            if($status == 'I')
            {
                $fs_progress->english_overall_status_writing = "7"; // Invited
            }
        }

        if( trim($unit_ref) == trim('SLC'))
        {
            if($status == 'R')
            {
                $fs_progress->scl_status = "1"; // Required
            }
            if($status == 'NR')
            {
                $fs_progress->scl_status = "4"; // Not Required
            }
            if($status == 'B')
            {
                $fs_progress->scl_status = "2"; // Booked
            }
            if($status == 'I')
            {
                $fs_progress->scl_status = "7"; // Invited
            }
        }
        
        if( trim($unit_ref) == trim('Functional Skills Mathematics'))
        {
            if($status == 'R')
            {
                $fs_progress->required = "1"; // Maths
                $fs_progress->fs_required = "1"; // No Evidence
                $fs_progress->maths_mock_status = "1"; // Required
                //$fs_progress->maths_overall_status = "1"; // Required
            }
            if($status == 'NR')
            {
                $fs_progress->maths_overall_status = "6"; // Not Required
            }
            if($status == 'B')
            {
                $fs_progress->maths_overall_status = "2"; // Booked
            }
            if($status == 'I')
            {
                $fs_progress->maths_overall_status = "7"; // Invited
            }
        }

        if($fs_progress_id != '')
		{
			$existing_record = FSProgress::loadFromDatabase($link, $fs_progress_id);
			$log_string = $existing_record->buildAuditLogString($link, $fs_progress);
			if($log_string != '')
			{
				$note = new Note();
				$note->subject = "FS progress updated from operations tracker";
				$note->note = $log_string;
			}
		}
		else
		{
			$note = new Note();
			$note->subject = "New FS progress record created from operations tracker";
		}

        DAO::transaction_start($link);
        try
        {
            $fs_progress->save($link);
            if(isset($note) && !is_null($note))
            {
                $note->is_audit_note = true;
                $note->parent_table = 'fs_progress';
                $note->parent_id = $fs_progress->id;
                $note->save($link);
            }
            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link, $ex);
			throw new WrappedException($ex);
        }

    }

    public function cancelSessionEntry(PDO $link)
    {
        $session_id = isset($_REQUEST['session_id'])?$_REQUEST['session_id']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $comments = isset($_REQUEST['comments'])?$_REQUEST['comments']:'';
	$category = isset($_REQUEST['category'])?$_REQUEST['category']:'';
	$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

        if($session_id == '' || $tr_id == '')
            throw new Exception('Missing mandatory information');

        $session = OperationsSession::loadFromDatabase($link, $session_id);
        $session->cancelEntry($link, $tr_id, $comments, $category, $type);

        unset($session);
    }

    public function getTrackerUnitSchedulingLog(PDO $link)
    {
        $html = '';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $unit_ref = isset($_REQUEST['unit_ref'])?$_REQUEST['unit_ref']:'';
        if($tr_id == '' || $unit_ref == '')
        {
            return $html;
        }

        //get cancellations from session_cancellations table
        $sql = new SQLStatement("
SELECT 
    session_cancellations.`cancellation_date`, 
    (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = session_cancellations.`cancelled_by`) AS cancelled_by,
    session_cancellations.`category`,
    session_cancellations.`comments`
FROM
    session_cancellations INNER JOIN op_tracker_unit_sch ON session_cancellations.`id` = op_tracker_unit_sch.`cancel_id`
                ");
        $sql->setClause("WHERE op_tracker_unit_sch.`tr_id` = '{$tr_id}'");
        $sql->setClause("WHERE op_tracker_unit_sch.`unit_ref` = '{$unit_ref}'");
        $session_cancellation = DAO::getObject($link, $sql->__toString());
        if(isset($session_cancellation->cancellation_date))
        {
            $_categories = InductionHelper::getListReschedulingCategory();
            $cat_desc = isset($_categories[$session_cancellation->category]) ? $_categories[$session_cancellation->category] : $session_cancellation->category;
            $html .= '<table class="small table table-bordered"><caption class="text-bold">Cancellation</caption>';
            $html .= '<thead><tr><th>Date</th><th>By</th><th>Category</th><th>Comments</th></tr></thead>';
            $html .= '<tr>';
            $html .= '<td>' . Date::toShort($session_cancellation->cancellation_date) . '</td><td>' . $session_cancellation->cancelled_by . '</td><td>' . $cat_desc . '</td><td>' . $session_cancellation->comments . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }                

        $html .= '<table class="small table table-bordered"><caption class="text-bold">Log</caption>';
        if($_SESSION['user']->isAdmin() && $_SESSION['user']->username == 'jcoates')
            $html .= '<thead><tr><th>DateTime</th><th>By</th><th>Code</th><th>Comments</th><th>&nbsp;</th></tr></thead>';
        else
            $html .= '<thead><tr><th>DateTime</th><th>By</th><th>Code</th><th>Comments</th></tr></thead>';
        $html .= '<tbody>';
        $sql = <<<SQL
SELECT op_tracker_unit_sch.id, `code`, (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.id = created_by) AS created_by, created, comments, register_id
 FROM op_tracker_unit_sch WHERE tr_id = '$tr_id' AND unit_ref = '$unit_ref' ORDER BY id
SQL;
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        if(count($result) > 0)
        {
            $sch_options = InductionHelper::getListSchOptions();
            foreach($result AS $row)
            {
                $html .= '<tr>';
                if($row['register_id'] == '')
                {
                    $html .= '<td>' . Date::to($row['created'], DAte::DATETIME) . '</td>';
                }
                else
                {
                    $html .= '<td><a href="do.php?_action=edit_session_register&id=' . $row['register_id'] . '"><i class="fa fa-external-link"></i> ' . Date::to($row['created'], DAte::DATETIME) . '</a></td>';
                }
                $html .= '<td>' . $row['created_by'] . '</td>';
                $html .= isset($sch_options[$row['code']]) ? '<td>' . $sch_options[$row['code']] . '</td>' : '<td>' . $row['code'] . '</td>';
                $html .= '<td class="small">' . $row['comments'] . '</td>';
                if($_SESSION['user']->isAdmin() && $_SESSION['user']->username == 'jcoates')
                {
                    $html .= '<td><span class="btn btn-xs btn-danger" onclick="removeTrackerUnitSchLog(\'' . $row['id'] . '\');"><i class="fa fa-trash"></i></span></td>';
                }
                $html .= '</tr>';
            }
        }
        else
        {
            $html = '';
        }
        $html .= '</tbody></table></p>';

        // get Mocks from session_entries table
        $sql = new SQLStatement("
SELECT 
    sessions.start_date AS session_start_date, CONCAT(sessions.start_time, ' ', sessions.end_time) AS session_time,
	session_entries.entry_mock_result 
FROM sessions 
    INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
                    ");
        $sql->setClause("WHERE session_entries.entry_tr_id = '{$tr_id}'");
        $sql->setClause("ORDER BY sessions.`start_date` DESC");
        $sql->setClause("LIMIT 1");

        if(substr($unit_ref, -5) === ' Test' || substr($unit_ref, -5) === ' test')
        {
            $sql->setClause("WHERE session_entries.`entry_exam_name` = '{$unit_ref}'");
        }
        else
        {
            $sql->setClause("WHERE FIND_IN_SET('{$unit_ref}', unit_ref)");
        }
        $session_details = DAO::getObject($link, $sql->__toString());
        if(isset($session_details->session_start_date))
        {
            $html .= '<table class="small table table-bordered"><caption class="text-bold">Result</caption>';
            $html .= '<thead><tr><th>Date</th><th>Time</th><th>Test Result</th></tr></thead>';
            $html .= '<tr>';
            $html .= '<td>' . Date::toShort($session_details->session_start_date) . '</td><td>' . $session_details->session_time . '</td><td>' . $session_details->entry_mock_result . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        return $html;
    }

    public function get_tr_operations_notes(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $note_type = isset($_REQUEST['note_type'])?$_REQUEST['note_type']:'';

        if($tr_id == '' || $note_type == '')
            return '';

        $owners = InductionHelper::getListOpOwners();
        if($note_type == 'last_learning_evidence')
        {
            $html = '<table class="table"><caption class="lead">Last Learning Evidence</caption>';
            $html .= '<tr><th>Creation Date Time</th><th>Created By</th><th>Evidence Type</th><th>Evidence Date</th><th>Evidence Notes</th></tr>';
            $notes = DAO::getSingleValue($link, "SELECT tr_operations.last_learning_evidence FROM tr_operations WHERE tr_id = '{$tr_id}'");
            if($notes == '')
            {
                $html .= '<tr><td colspan="5"><i>No record found.</i></td></tr>';
            }
            else
            {
                $types = InductionHelper::getListLastLearningEvidence();
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Evidence AS $note)
                {
                    $html .= '<tr>';
                    $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                    $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                    $html .= isset($types[$note->Type->__toString()])?'<td>' . $types[$note->Type->__toString()] . '</td>':'<td></td>';
                    $html .= '<td>' . Date::toShort($note->Date) . '</td>';
                    $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</table>';
        }
        elseif($note_type == 'lar_notes')
        {
            if(SOURCE_BLYTHE_VALLEY || DB_NAME == "am_baltic_demo" || DB_NAME == "am_baltic")
            {
                $html = '<table id="tblLarNotes" class="table table-bordered"><caption class="lead">LAR Details</caption>';
                $html .= '<thead><tr><th>Creation Date Time</th><th>Created By</th><th>Type</th><th>LAR Date</th><th>Last Action Date</th><th>Next Action Date</th><th>Sales Deadline Date</th><th>Reason</th><th>RAG</th><th>Owner</th><th>Notes</th><th>Actions</th></tr></thead>';
                $notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
                $ragDDL = InductionHelper::getListLARRAGRating();
                $reasonDDL = InductionHelper::getListLARReason();
                $html .= '<tbody>';
                if($notes == '')
                {
                    $html .= '<tr><td colspan="12"><i>No record found.</i></td></tr>';
                }
                else
                {
                    $types = InductionHelper::getListLAR();
                    $notes = XML::loadSimpleXML($notes);
                    $first = true;
                    $is_no = false;
                    $episode = 1;
                    $episode_row = 0;
                    foreach($notes->Note AS $note)
                    {
                        $episode_row++;
                        $episode_id = 'episode'.$episode.'_'.$episode_row;

                        if($episode_row == 1)
                            $html .= $note->Type->__toString() != 'N' ? '<tr id="'.$episode_id.'">' : '<tr id="'.$episode_id.'" class="text-blue">';
                        else
                            $html .= $note->Type->__toString() != 'N' ? '<tr id="'.$episode_id.'" style="display: none;">' : '<tr id="'.$episode_id.'" style="display: none;" class="text-blue">';
                        $html .= '<td data-order="'.$note->DateTime->__toString().'">';
                        $html .= Date::to($note->DateTime->__toString(), Date::DATETIME);
                        $html .= '</td>';
                        $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                        $html .= isset($types[$note->Type->__toString()])?'<td>' . $types[$note->Type->__toString()] . '</td>':'<td></td>';
                        $html .= '<td>' . Date::toShort($note->Date->__toString()) . '</td>';
                        $html .= '<td>' . Date::toShort($note->LastActionDate->__toString()) . '</td>';
                        $html .= '<td>' . Date::toShort($note->NextActionDate->__toString()) . '</td>';
                        $html .= '<td>' . Date::toShort($note->SalesDeadlineDate->__toString()) . '</td>';
                        if(isset($note->Reason))
                            $html .= isset($reasonDDL[$note->Reason->__toString()])?'<td>'.$reasonDDL[$note->Reason->__toString()].'</td>':'<td></td>';
                        else
                            $html .= '<td></td>';
                        $html .= isset($ragDDL[$note->RAG->__toString()])?'<td>'.$ragDDL[$note->RAG->__toString()].'</td>':'<td></td>';
                        $html .= isset($owners[$note->Owner->__toString()])?'<td>'.$owners[$note->Owner->__toString()].'</td>':'<td></td>';
                        $html .= '<td class="small">' . html_entity_decode($note->Note) . '</td>';
                        $html .= '<td>';
                        $html .= $first ? '<span class="btn btn-xs btn-info" onclick="$(\'tr[id^=episode'.$episode.']\').not(\'#episode'.$episode.'_1\').toggle();$(this).text(function(i, text){return text === \'Expand\' ? \'Hide\' : \'Expand\';});">Expand</span><span class="btn btn-xs btn-info" onclick="generateLARTemplate(\'' . $note->DateTime->__toString() . '\');"><i class="fa fa-download"></i> </span> ' : '';
                        if($is_no)
                        {
                            $html .= '<span class="btn btn-xs btn-info" onclick="$(\'tr[id^=episode'.$episode.']\').not(\'#episode'.$episode.'_1\').toggle();$(this).text(function(i, text){return text === \'Expand\' ? \'Hide\' : \'Expand\';});">Expand</span><span class="btn btn-xs btn-info" onclick="generateLARTemplate(\'' . $note->DateTime->__toString() . '\');"><i class="fa fa-download"></i> </span> ';
                            $is_no = false;
                        }
                        $html .= '<span title="Edit notes in this entry" class="btn btn-xs btn-default" onclick="editLARUpdateEntry(\'' . $tr_id . '\', \'' . $note->DateTime . '\');"><i class="fa fa-edit"></i> </span> ';
                        $html .= '<span title="Delete this entry" class="btn btn-xs btn-danger" onclick="removeLARUpdateEntry(\'' . $tr_id . '\', \'' . $note->DateTime . '\');"><i class="fa fa-trash"></i> </span> ';
                        $html .= '</td>';
                        $html .= '</tr>';
                        $first = false;
                        $is_no = $note->Type->__toString() == 'N' ? true : false;

                        if($is_no)
                        {
                            $episode_row = 0;
                            $episode++;
                        }
                    }
                }
                $html .= '</tbody></table>';
            }
            else
            {
                $html = '<table class="table"><caption class="lead">LAR Details</caption>';
                $html .= '<tr><th>Creation Date Time</th><th>Created By</th><th>Type</th><th>LAR Date</th><th>Last Action Date</th><th>Next Action Date</th><th>Sales Deadline Date</th><th>Reason</th><th>RAG</th><th>Notes</th></tr>';
                $notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
                $ragDDL = InductionHelper::getListLARRAGRating();
                $reasonDDL = InductionHelper::getListLARReason();
                if($notes == '')
                {
                    $html .= '<tr><td colspan="9"><i>No record found.</i></td></tr>';
                }
                else
                {
                    $types = InductionHelper::getListLAR();
                    $notes = XML::loadSimpleXML($notes);
                    foreach($notes->Note AS $note)
                    {
                        $html .= '<tr>';
                        $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                        $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                        $html .= isset($types[$note->Type->__toString()])?'<td>' . $types[$note->Type->__toString()] . '</td>':'<td></td>';
                        $html .= '<td>' . Date::toShort($note->Date->__toString()) . '</td>';
                        $html .= '<td>' . Date::toShort($note->LastActionDate->__toString()) . '</td>';
                        $html .= '<td>' . Date::toShort($note->NextActionDate->__toString()) . '</td>';
                        $html .= '<td>' . Date::toShort($note->SalesDeadlineDate->__toString()) . '</td>';
                        if(isset($note->Reason))
                            $html .= isset($reasonDDL[$note->Reason->__toString()])?'<td>'.$reasonDDL[$note->Reason->__toString()].'</td>':'<td></td>';
                        else
                            $html .= '<td></td>';
                        $html .= isset($ragDDL[$note->RAG->__toString()])?'<td>'.$ragDDL[$note->RAG->__toString()].'</td>':'<td></td>';
                        $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</table>';
            }
        }
        elseif($note_type == 'break_in_learning_notes')
        {
            $html = '<table class="table"><caption class="lead">BIL Details</caption>';
            $html .= '<tr><th>Creation Date Time</th><th>Created By</th><th>Type</th><th>Date</th><th>Reason</th><th>Retention</th><th>Owner</th><th>Predicted Return</th><th>Predicted Leaver</th><th>Next Action</th><th>Notes</th></tr>';
            $notes = DAO::getSingleValue($link, "SELECT tr_operations.bil_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
            if($notes == '')
            {
                $html .= '<tr><td colspan="11"><i>No record found.</i></td></tr>';
            }
            else
            {
                $types = InductionHelper::getListBIL();
                $bil_reasons = InductionHelper::getListLARReason();
		$bil_retentions = InductionHelper::getListBilRetentions();
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Note AS $note)
                {
                    $html .= '<tr>';
                    $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                    $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                    $html .= isset($types[$note->Type->__toString()])?'<td>' . $types[$note->Type->__toString()] . '</td>':'<td></td>';
                    $html .= '<td>' . Date::toShort($note->Date) . '</td>';
                    $html .= isset($bil_reasons[$note->Reason->__toString()])?'<td>'.$bil_reasons[$note->Reason->__toString()].'</td>':'<td></td>';
		    $html .= isset($bil_retentions[$note->Retention->__toString()])?'<td>'.$bil_retentions[$note->Retention->__toString()].'</td>':'<td></td>';
                    $html .= isset($owners[$note->Owner->__toString()])?'<td>'.$owners[$note->Owner->__toString()].'</td>':'<td></td>';
		    $html .= isset($note->PredictedReturn)?'<td>'.$note->PredictedReturn->__toString().'</td>':'<td></td>';
                    $html .= isset($note->PredictedLeaver)?'<td>'.$note->PredictedLeaver->__toString().'</td>':'<td></td>';
		    $html .= isset($note->NextAction)?'<td>'.$note->NextAction->__toString().'</td>':'<td></td>';	
                    $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</table>';
        }
        elseif($note_type == 'leaver_notes')
        {
            $html = '<table class="table"><caption class="lead">Leaver Details</caption>';
            $html .= '<tr><th>Creation Date Time</th><th>Created By</th><th>Type</th><th>Date</th><th>Reason</th><th>Cause</th><th>Owner</th><th>Notes</th></tr>';
            $notes = DAO::getSingleValue($link, "SELECT tr_operations.leaver_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
            if($notes == '')
            {
                $html .= '<tr><td colspan="8"><i>No record found.</i></td></tr>';
            }
            else
            {
                $types = InductionHelper::getListLAR();
                $leaver_causes = InductionHelper::getListLARCause();
                $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Note AS $note)
                {
                    $html .= '<tr>';
                    $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                    $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                    $html .= isset($types[$note->Type->__toString()])?'<td>' . $types[$note->Type->__toString()] . '</td>':'<td></td>';
                    $html .= '<td>' . Date::toShort($note->Date) . '</td>';
                    if(isset($note->Reason))
                    {
                        $html .= isset($_list_leaver_reasons[$note->Reason->__toString()])?'<td>' . $_list_leaver_reasons[$note->Reason->__toString()] . '</td>':'<td></td>';
                    }
                    else
                    {
                        $html .= '<td></td>';
                    }
                    if(isset($note->Cause))
                    {
                        $html .= isset($leaver_causes[$note->Cause->__toString()])?'<td>' . $leaver_causes[$note->Cause->__toString()] . '</td>':'<td></td>';
                    }
                    else
                    {
                        $html .= '<td></td>';
                    }
                    $html .= isset($owners[$note->Owner->__toString()])?'<td>'.$owners[$note->Owner->__toString()].'</td>':'<td></td>';
                    $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</table>';
        }
        elseif($note_type == 'peed_notes')
        {
            $html = '<table class="table table-bordered"><caption class="lead">PEED Details</caption>';
            $html .= '<thead><tr><th>Creation Date Time</th><th>Created By</th><th>Status</th><th>Date</th><th>Reason</th><th>Cause</th><th>Revisit Date</th><th>Owner</th><th>Comments</th></tr></thead>';
            $notes = DAO::getSingleValue($link, "SELECT tr_operations.peed_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
            $statusDdl = InductionHelper::getListPeedStatus();
            $reasonDdl = InductionHelper::getListLARReason();
            $causeDdl = InductionHelper::getListPeedCause();
            $ownerDdl = InductionHelper::getListOpOwners();
            $html .= '<tbody>';
            if($notes == '')
            {
                $html .= '<tr><td colspan="9"><i>No record found.</i></td></tr>';
            }
            else
            {
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Note AS $note)
                {
                    $html .= '<tr>';
                    $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                    $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                    $html .= isset($statusDdl[$note->Status->__toString()])?'<td>' . $statusDdl[$note->Status->__toString()] . '</td>':'<td></td>';
                    $html .= '<td>' . Date::toShort($note->Date) . '</td>';
                    $html .= isset($reasonDdl[$note->Reason->__toString()])?'<td>' . $reasonDdl[$note->Reason->__toString()] . '</td>':'<td></td>';
                    $html .= '<td>' . Date::toShort($note->Revisit) . '</td>';
                    $html .= isset($causeDdl[$note->Cause->__toString()])?'<td>' . $causeDdl[$note->Cause->__toString()] . '</td>':'<td></td>';
                    $html .= isset($ownerDdl[$note->Owner->__toString()])?'<td>'.$ownerDdl[$note->Owner->__toString()].'</td>':'<td></td>';
                    $html .= '<td>' . html_entity_decode($note->Comments) . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody></table>';
        }
	elseif($note_type == 'lras_notes')
        {
            $html = '<table class="table table-bordered"><caption class="lead">LRAS Details</caption>';
            $html .= '<thead><tr><th>Creation Date Time</th><th>Created By</th><th>Status</th><th>Summary</th><th>Reason</th><th>Category</th><th>Date</th><th>Recommended End Date</th><th>Pro/React</th><th>Support Provider</th><th>Action Plan</th><th>Resources/Aftercare</th></tr></thead>';
            $notes = DAO::getSingleValue($link, "SELECT tr_operations.lras_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
            $reasons = Safeguarding::getListTriggers($link);
            $categories = Safeguarding::getListCategories($link);
            $support_providers = Safeguarding::getListSupportProvider($link);

            $html .= '<tbody>';
            if($notes == '')
            {
                $html .= '<tr><td colspan="9"><i>No record found.</i></td></tr>';
            }
            else
            {
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Note AS $note)
                {
                    $html .= '<tr>';
		    $html .= '<td>' . Date::to($note->DateTime->__toString(), Date::DATETIME) . '</td>';
		    $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy->__toString()}'") . '</td>';		
                    $html .= '<td>' . $note->Status->__toString() . '</td>';
                    $html .= '<td>' . nl2br($note->Summary->__toString()) . '</td>';
                    $html .= '<td>';
                    if(isset($note->Reason) && $note->Reason->__toString() != '')
                    {
                        foreach( explode(',', $note->Reason->__toString()) AS $Reason )
                        {
                            $html .= isset($reasons[$Reason]) ? $reasons[$Reason] : $Reason;
                            $html .= ' | ';
                        }
                    }
                    $html .= '</td>';
                    $html .= isset($categories[$note->Category->__toString()]) ? '<td>' . $categories[$note->Category->__toString()] . '</td>' : '<td>' . $note->Category->__toString() . '</td>';
                    $html .= '<td>' . $note->LrasDate->__toString() . '</td>';
                    $html .= '<td>' . $note->RecommendedEndDate->__toString() . '</td>';
                    $html .= '<td>' . $note->ProReact->__toString() . '</td>';
                    $html .= '<td>';
                    if(isset($note->SupportProvider) && $note->SupportProvider->__toString() != '')
                    {
                        foreach( explode(',', $note->SupportProvider->__toString()) AS $SupportProvider )
                        {
                            $html .= isset($support_providers[$SupportProvider]) ? $support_providers[$SupportProvider] : $SupportProvider;
                            $html .= ' | ';
                        }
                    }
                    $html .= '</td>';
                    $html .= '<td>' . $note->ActionPlanAgreed->__toString() . '</td>';
                    $html .= '<td>' . $note->ResourcesProvided->__toString() . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody></table>';
        }
        else
        {
            $notes_options = array(
                'hour_48_call_notes' => '48 Hour Call Notes',
                'week_3_call_notes' => 'Week 3 Call Notes',
                'lar_notes' => 'LAR Notes',
                'break_in_learning_notes' => 'Break in Learning Notes',
                'leaver_notes' => 'Leaver Notes',
                'leaver_form_notes' => 'Leaver Form Notes',
                'learner_id_notes' => 'Learner ID Notes',
            );

            $note_title = isset($notes_options[$note_type]) ? $notes_options[$note_type] : '';
            $html = '<table class="table"><caption class="lead">' . $note_title . '</caption>';
            $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
            $notes = DAO::getSingleValue($link, "SELECT tr_operations.{$note_type} FROM tr_operations WHERE tr_id = '{$tr_id}'");
            if($notes == '')
            {
                $html .= '<tr><td colspan="3"><i>No record found.</i></td></tr>';
            }
            else
            {
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Note AS $note)
                {
                    $html .= '<tr>';
                    $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                    $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                    $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</table>';

        }

        return $html;
    }

    public function saveMockEntry(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $unit_ref = isset($_REQUEST['mock_unit_ref'])?$_REQUEST['mock_unit_ref']:'';
        $mock_code = isset($_REQUEST['mock_unit_ref_status'])?$_REQUEST['mock_unit_ref_status']:'';
        $mock_comments = isset($_REQUEST['mock_unit_comments'])?$_REQUEST['mock_unit_comments']:'';

        if($tr_id == '' || $unit_ref == '')
            return;

        /*$existing_mock_code = DAO::getSingleValue($link, "SELECT mock_code FROM op_tracker_unit_mock WHERE tr_id = '{$tr_id}' AND unit_ref = '{$unit_ref}' ORDER BY id DESC LIMIT 1");
        if($existing_mock_code == $mock_code)
            return;*/

        $obj = new stdClass();
        $obj->tr_id = $tr_id;
        $obj->unit_ref = $unit_ref;
        $obj->mock_code = $mock_code;
        $obj->created_by = $_SESSION['user']->id;
        $obj->comments = $mock_comments;

        DAO::saveObjectToTable($link, 'op_tracker_unit_mock', $obj);
    }

    public function add_op_add_details_type(PDO $link)
    {
        $value = isset($_REQUEST['value'])?$_REQUEST['value']:'';
        $lookup_value = new stdClass();
        $lookup_value->id = null;
        $lookup_value->description = $value;
        DAO::saveObjectToTable($link, 'lookup_op_add_details_types', $lookup_value);
        unset($lookup_value);
    }

    public function load_op_details_types(PDO $link)
    {
        header('Content-Type: text/xml');
        $sql = "SELECT id, description, null FROM lookup_op_add_details_types ORDER BY description; ";
        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";
            // First entry is empty
            echo "<option value=\"\"></option>\r\n";
            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }
            echo '</select>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function load_op_epa_status()
    {
        $op_task = isset($_REQUEST['op_task']) ? $_REQUEST['op_task']: '';

        $op_tasks_status = InductionHelper::getListOpTaskStatus($op_task);
        header('Content-Type: text/xml');
        echo "<?xml version=\"1.0\" ?>\r\n";
        echo "<select>\r\n";
        // First entry is empty
        echo "<option value=\"\"></option>\r\n";
        foreach($op_tasks_status AS $key => $value)
        {
            echo '<option value="' . htmlspecialchars((string)$key) . '">' . htmlspecialchars((string)$value) . "</option>\r\n";
        }
        echo '</select>';
    }

    public function get_op_epa_record(PDO $link)
    {
        $op_epa_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($op_epa_id == '')
            return;

        $sql = <<<SQL
SELECT id, tr_id, task, task_status, DATE_FORMAT(task_date, '%d/%m/%Y') AS task_date, 
DATE_FORMAT(task_actual_date, '%d/%m/%Y') AS task_actual_date, 
DATE_FORMAT(task_peed_forecast_date, '%d/%m/%Y') AS task_peed_forecast_date, task_comments, task_applicable, task_type, DATE_FORMAT(task_end_date, '%d/%m/%Y') AS task_end_date, LEFT(task_end_time, 5) AS task_end_time,
task_epa_risk, potential_achievement_month, task_lsl, task_peed_cause, task_assessment_method1, task_assessment_method2 FROM op_epa WHERE id = '{$op_epa_id}'
SQL;
        return json_encode(DAO::getObject($link, $sql));
    }

    private function pullBILInformationForOperations(PDO $link)
    {
        $bil_id = isset($_REQUEST['bil_id']) ? $_REQUEST['bil_id']:'';
        $con_id = isset($_REQUEST['con_id']) ? $_REQUEST['con_id']:'';

        if($bil_id == '' || $con_id == '')
            throw new Exception('Missing querystring arguments: bil_id, con_id');

        DAO::transaction_start($link);
        try
        {
            $bil_tr_operations = DAO::getObject($link, "SELECT * FROM tr_operations WHERE tr_id = '{$bil_id}'");
            if(isset($bil_tr_operations->tr_id))
            {
                $bil_tr_operations->tr_id = $con_id;
		$bil_tr_operations->leaver_details = null;
                DAO::saveObjectToTable($link, 'tr_operations', $bil_tr_operations);
                //DAO::execute($link, "DELETE FROM tr_operations WHERE tr_id = '{$bil_id}'");
            }
            DAO::execute($link, "UPDATE op_epa SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE op_tracker_unit_mock SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE op_tracker_unit_sch SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE session_entries SET entry_tr_id = '{$con_id}' WHERE entry_tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE session_cancellations SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE assessment_plan_log SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE tr_projects SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE submissions_iqa SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE assessor_review SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE additional_support SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE skills_scan SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
            DAO::execute($link, "UPDATE fs_progress SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
	    // for caseload entries 
            $latest_caseload_entry = DAO::getObject($link, "SELECT * FROM caseload_management WHERE tr_id = '{$bil_id}' ORDER BY id DESC LIMIT 1");
            if(isset($latest_caseload_entry->id))
            {
                if($latest_caseload_entry->bil == 1)
                {
                    // support ticket 15 of V2 
                    // if latest castload entry the bil is ticked then do the copy paste.
                    $caseloadEntries = DAO::getResultset($link, "SELECT * FROM caseload_management WHERE tr_id = '{$bil_id}' ORDER BY id", DAO::FETCH_ASSOC);
                    foreach($caseloadEntries AS $caseloadEntry)
                    {
                        $caseloadEntry['id'] = null;
                        $caseloadEntry['tr_id'] = $con_id;
                        DAO::saveObjectToTable($link, "caseload_management", $caseloadEntry);
                    }
                }
                else
                {
                    // do cut paste.
                    DAO::execute($link, "UPDATE caseload_management SET tr_id = '{$con_id}' WHERE tr_id = '{$bil_id}'");
                }
            }

            $latest_caseload_entry = DAO::getObject($link, "SELECT * FROM caseload_management WHERE tr_id = '{$con_id}' ORDER BY id DESC LIMIT 1");
            if( isset($latest_caseload_entry->id) )
            {
                $latest_caseload_entry->bil = 0;
                $latest_caseload_entry->closed_date = date('Y-m-d');
                DAO::saveObjectToTable($link, 'caseload_management', $latest_caseload_entry);
            }	
            DAO::execute($link, "INSERT INTO tr_files SELECT '{$con_id}', file_name, file_type, uploaded_by, uploaded_date, null FROM tr_files WHERE tr_files.tr_id = '{$bil_id}'");

            DAO::execute($link, "INSERT INTO op_bil_merged_records (bil_id, con_id) VALUES ('{$bil_id}', '{$con_id}') ");

            $bil_tr = TrainingRecord::loadFromDatabase($link, $bil_id);
            $con_tr = TrainingRecord::loadFromDatabase($link, $con_id);
            $con_tr->learner_work_email = is_null($con_tr->learner_work_email) ? $bil_tr->learner_work_email : $con_tr->learner_work_email;
            $con_tr->work_email = is_null($con_tr->work_email) ? $bil_tr->work_email : $con_tr->work_email;
            $con_tr->home_email = is_null($con_tr->home_email) ? $bil_tr->home_email : $con_tr->home_email;
	    $con_tr->coordinator = is_null($con_tr->coordinator) ? $bil_tr->coordinator : $con_tr->coordinator;
            if(is_null($con_tr->assessor) || $con_tr->assessor == 0)
                $con_tr->assessor = $bil_tr->assessor;
            $con_tr->save($link);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        return true;

    }

    private function loadAndPrepareEmailTemplate(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $template_id = isset($_REQUEST['template_id'])?$_REQUEST['template_id']:'';
        if($tr_id == '' || $template_id == '')
            throw new Exception('Cannot load email template, missing querystring: tr_id or template type');

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception('Training record not found');
        $template = DAO::getSingleValue($link, "SELECT template FROM lookup_learner_email_templates WHERE id = '{$template_id}'");
        $template = str_replace('$$LEARNER_NAME$$', $tr->firstnames . ' ' . $tr->surname, $template);
        $client_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
        $template = str_replace('$$CLIENT_URL$$', $client_url, $template);
        unset($tr);
        return $template;
    }

    private function send_email_to_learner(PDO $link)
    {
        $tracker_id = isset($_REQUEST['tracker_id']) ? $_REQUEST['tracker_id']: '';
        $to = isset($_REQUEST['frmEmailTo']) ? $_REQUEST['frmEmailTo']: '';
        if($to == '')
            throw new Exception('Email to cannot be null');
        $from = isset($_REQUEST['frmEmailFrom']) ? $_REQUEST['frmEmailFrom']: '';
        if($from == '')
            throw new Exception('Email from cannot be null');
        $subject = isset($_REQUEST['frmEmailSubject']) ? $_REQUEST['frmEmailSubject']: '';
        if($subject == '')
            throw new Exception('Subject cannot be null');

        $email_body = isset($_REQUEST['compose-textarea']) ? $_REQUEST['compose-textarea']: '';
        if($email_body == '')
            throw new Exception('Email body cannot be null');

        if(Emailer::notification_email($to, $from, $from, $subject, '', $email_body))
        {
            $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
            if($tr_id != '')
            {
                $save_email = new stdClass();
                $save_email->tr_id = $tr_id;
                $save_email->subject = $subject;
                $save_email->learner_email = $to;
                $save_email->email_body = $email_body;
                $save_email->by_whom = $_SESSION['user']->id;
                $save_email->template_type = isset($_POST['frmEmailTemplate'])?$_POST['frmEmailTemplate']:null;
                $save_email->email_to = $to;
                $save_email->email_from = $from;
                DAO::saveObjectToTable($link, "learners_emails", $save_email);
            }
        }

        //http_redirect('do.php?_action=view_edit_op_learner&tr_id='.$_REQUEST['tr_id'].'&tracker_id='.$_REQUEST['tracker_id']);
        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function getEmailContent(PDO $link)
    {
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id']: '';
        if($email_id == '')
            throw new Exception('Missing querystring argument: email id');

        $email = DAO::getObject($link, "SELECT * FROM learners_emails WHERE id = '{$email_id}'");

        return $email->email_body;
    }

    private function upload_summernote_image(PDO $link)
    {
        if(!file_exists(Repository::getRoot() . '/summernote'))
        {
            mkdir(Repository::getRoot() . '/summernote');
        }
        if ($_FILES['file']['name'])
        {
            if (!$_FILES['file']['error'])
            {
                $filename = $_FILES['file']['name'];
                $destination = Repository::getRoot() . '/summernote/' . $filename;
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo 'http://sunesis/do.php?_action=display_picture&d=summernote&f=' . $filename;
            }
            else
            {
                echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
            }
        }
    }

    private function save_learner_complaint(PDO $link)
    {
        $complaint = new Complaint();
        $complaint->populate($_POST);
        $complaint->save($link);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function save_complaint_response(PDO $link)
    {
        $complaint_id = isset($_POST['complaint_id'])?$_POST['complaint_id']:'';
        if($complaint_id == '')
            throw new Exception('Missing querystring argument: complaint_id');

        $response = new ComplaintResponse($complaint_id);
        $response->populate($_POST);
        $response->save($link);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function save_gateway_prep(PDO $link)
    {
        $id = isset($_POST['id'])?$_POST['id']:null;
        $tr_id = isset($_POST['tr_id'])?$_POST['tr_id']:'';
        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        $recordToSave = new stdClass();
        $recordToSave->id = $id;
        $recordToSave->tr_id = $tr_id;
        if(isset($_POST['emp_epa_pack_chklst']))
        {
            $recordToSave->emp_epa_pack_chklst = $_POST['emp_epa_pack_chklst'];
        }
        else
        {
            $recordToSave->emp_epa_pack_chklst = '';
        }

        DAO::saveObjectToTable($link, 'op_epa_extra', $recordToSave);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function save_gateway_ready(PDO $link)
    {
        $id = isset($_POST['id'])?$_POST['id']:null;
        $tr_id = isset($_POST['tr_id'])?$_POST['tr_id']:'';
        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        $recordToSave = new stdClass();
        $recordToSave->id = $id;
        $recordToSave->tr_id = $tr_id;
        $recordToSave->summative_portfolio = $_POST['summative_portfolio'];
        $recordToSave->summative_portfolio_date = $_POST['summative_portfolio_date'];
        $recordToSave->passed_to_ss = $_POST['passed_to_ss'];
        $recordToSave->passed_to_ss_date = $_POST['passed_to_ss_date'];
        $recordToSave->predicted_gateway_month = $_POST['predicted_gateway_month'];

        DAO::saveObjectToTable($link, 'op_epa_extra', $recordToSave);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function save_epa_project(PDO $link)
    {
        $id = isset($_POST['id'])?$_POST['id']:null;
        $tr_id = isset($_POST['tr_id'])?$_POST['tr_id']:'';
        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        $recordToSave = new stdClass();
        $recordToSave->id = $id;
        $recordToSave->tr_id = $tr_id;
        $recordToSave->predicted_epa_month = $_POST['predicted_epa_month'];
        $recordToSave->predicted_project_date = $_POST['predicted_project_date'];
        $recordToSave->actual_project_date = $_POST['actual_project_date'];
        $recordToSave->completed_project_date = $_POST['completed_project_date'];

        DAO::saveObjectToTable($link, 'op_epa_extra', $recordToSave);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function save_interview(PDO $link)
    {
        $id = isset($_POST['id'])?$_POST['id']:null;
        $tr_id = isset($_POST['tr_id'])?$_POST['tr_id']:'';
        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        $recordToSave = new stdClass();
        $recordToSave->id = $id;
        $recordToSave->tr_id = $tr_id;
        $recordToSave->predicted_interview_date = $_POST['predicted_interview_date'];
        $recordToSave->actual_interview_date = $_POST['actual_interview_date'];
        $recordToSave->completed_interview_date = $_POST['completed_interview_date'];
        $recordToSave->provisional_result = $_POST['provisional_result'];
        $recordToSave->confirmed_result = $_POST['confirmed_result'];

        DAO::saveObjectToTable($link, 'op_epa_extra', $recordToSave);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    private function removeMockEntry(PDO $link)
    {
        $id = isset($_REQUEST['entry_id'])?$_REQUEST['entry_id']:null;
        if($id != '')
        {
            DAO::execute($link, "DELETE FROM op_tracker_unit_mock WHERE id = '{$id}'");
        }
    }

    private function check_unit_ref_applicable_for_learner(PDO $link)
    {
        $entry_id = isset($_REQUEST['entry_id']) ? $_REQUEST['entry_id'] : '';
        $unit_ref = isset($_REQUEST['unit_ref']) ? $_REQUEST['unit_ref'] : '';

        $session_entry = DAO::getObject($link, "SELECT * FROM session_entries WHERE entry_id = '{$entry_id}'");
        $tracker_ids = DAO::getSingleValue($link, "SELECT tracker_id FROM sessions WHERE sessions.id = '{$session_entry->entry_session_id}'");
        $_chk = DAO::getSingleValue($link, "SELECT extractvalue(evidences, '//unit[@op_title=\"".addslashes((string)$unit_ref)."\" and @track=\"true\"]/@title') AS chk
			FROM framework_qualifications INNER JOIN student_frameworks ON (framework_qualifications.framework_id = student_frameworks.id AND student_frameworks.tr_id = '" . $session_entry->entry_tr_id . "')
			INNER JOIN op_tracker_frameworks ON student_frameworks.id = op_tracker_frameworks.framework_id WHERE tracker_id IN (" . $tracker_ids . ") HAVING chk != '';");

        if($_chk != '')
        {
            $session_entry->entry_exam_name = $unit_ref;
            DAO::saveObjectToTable($link, "session_entries", $session_entry);
        }
        return $_chk != '' ? 1 : 0;
    }

    private function showApProgressLookup(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return '';

        $sql = <<<SQL
SELECT CONCAT(tr.firstnames, ' ', UPPER(tr.surname)) AS learner_name,
	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date, courses_tr.course_id,
	''  AS current_training_month
    ,courses.title AS c_title
FROM
	tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
	 INNER JOIN courses ON courses_tr.course_id = courses.id
WHERE
	tr.id = '{$tr_id}'
SQL;


        $details = DAO::getObject($link, $sql);
        $details->current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $tr_id);
        if(!isset($details->start_date))
            return '';

        $ap_details = DAO::getResultset($link, "SELECT * FROM ap_percentage WHERE ap_percentage.course_id = '{$details->course_id}'", DAO::FETCH_ASSOC);
        $lookup_rows = '';
        foreach($ap_details AS $ap)
        {
            $lookup_rows .= '<tr><td align="center">' . $ap['min_month'] . ' - ' . $ap['max_month'] . '</td><td align="center">' . $ap['aps'] . '</td><td align="center">' . $ap['comp'] . '</td></tr>';
        }

        $lookup = <<<HTML
<table class="table table-bordered small">
	<tr><th class="bg-info" colspan="3">Course Title: $details->c_title</th> </tr>
	<tr><th class="bg-info" colspan="3">Learner: $details->learner_name (Start Date: $details->start_date, Week: $details->current_training_month)</th> </tr>
	<tr><th align="center">Training Week Between (inclusive)</th><th align="center">Reviews should have been completed</th><th align="center">Evidences</th></tr>
	$lookup_rows
</table>
HTML;

        return $lookup;
    }

    private function removeTrackerUnitSchLog(PDO $link)
    {
        $sch_id = isset($_REQUEST['sch_id']) ? $_REQUEST['sch_id'] : '';

        if($sch_id == '')
            return;

        DAO::execute($link, "DELETE FROM op_tracker_unit_sch WHERE op_tracker_unit_sch.id = '{$sch_id}'");

        return true;
    }

    private function removeLARUpdateEntry(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $timestamp = isset($_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : '';
        if($tr_id == '' || $timestamp == '')
            throw new Exception('Missing querystring argument: tr_id, timestamp');

        $notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
        $notes = XML::loadSimpleXML($notes);
        foreach($notes->Note AS $note)
        {
            if($note->DateTime == $timestamp)
            {
                $dom = dom_import_simplexml($note);
                $dom->parentNode->removeChild($dom);
            }
        }

        $tr_operations = DAO::getObject($link, "SELECT * FROM tr_operations WHERE tr_id = '{$tr_id}'");
        $modified_xml = $notes->saveXML();
        $modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);
        $tr_operations->lar_details = $modified_xml;
        DAO::saveObjectToTable($link, 'tr_operations', $tr_operations);
    }

    private function get_lar_update_entry_details(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $timestamp = isset($_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : '';
        if($tr_id == '' || $timestamp == '')
            throw new Exception('Missing querystring argument: tr_id, timestamp');

        $notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
        $notes = XML::loadSimpleXML($notes);

        $lar_types = InductionHelper::getListLAR();
        $rags = InductionHelper::getListLARRAGRating();

        foreach($notes->Note AS $note)
        {
            if($note->DateTime->__toString() == $timestamp)
            {
                $obj = new stdClass();
                $obj->modal_creation_date_time = Date::to($note->DateTime->__toString(), Date::DATETIME);
                $obj->timestamp = $note->DateTime->__toString();
                $obj->lar_notes = $note->Note->__toString();
                $obj->modal_created_by = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '" . $note->CreatedBy->__toString() . "'");
                $obj->modal_type = isset($lar_types[$note->Type->__toString()]) ? $lar_types[$note->Type->__toString()] : '';
                $obj->modal_lar_date = $note->Date->__toString();
                $obj->modal_last_action_date = $note->LastActionDate->__toString();
                $obj->modal_next_action_date = $note->NextActionDate->__toString();
                $obj->modal_sales_deadline_date = $note->SalesDeadlineDate->__toString();
                $obj->modal_rag = isset($rags[$note->RAG->__toString()]) ? $rags[$note->RAG->__toString()] : '';

                echo json_encode($obj);
                return;
            }
        }

    }

    private function save_lar_entry_update(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $timestamp = isset($_REQUEST['modal_timestamp']) ? $_REQUEST['modal_timestamp'] : '';
        $modal_lar_notes = isset($_REQUEST['modal_lar_notes']) ? $_REQUEST['modal_lar_notes'] : '';
        if($tr_id == '' || $timestamp == '')
            throw new Exception('Missing querystring argument: tr_id, timestamp');

        $notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
        $notes = XML::loadSimpleXML($notes);
        foreach($notes->Note AS $note)
        {
            if($note->DateTime->__toString() == $timestamp)
            {
                $note->Note = $modal_lar_notes;
            }
        }

        $tr_operations = DAO::getObject($link, "SELECT * FROM tr_operations WHERE tr_id = '{$tr_id}'");
        $modified_xml = $notes->saveXML();
        $modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);
        $tr_operations->lar_details = $modified_xml;
        DAO::saveObjectToTable($link, 'tr_operations', $tr_operations);
    }

    private function delete_op_session(PDO $link)
    {
        if(!SOURCE_BLYTHE_VALLEY && $_SESSION['user']->op_access != 'W')
            throw new UnauthorizedException();

        $session_id = isset($_REQUEST['session_id']) ? $_REQUEST['session_id'] : '';

        if($session_id == '')
            throw new Exception('Missing querystring: session_id');

        $session = OperationsSession::loadFromDatabase($link, $session_id);
        if(is_null($session))
            throw new Exception('Invalid session_id');

        if(!$session->isSafeToDelete($link))
            throw new Exception('This session has got entries so it cannot be deleted.');

        DAO::execute($link, "DELETE FROM sessions WHERE sessions.id = '{$session->id}'");

        return 'success';
    }

    private function save_tr_learner_profile_info(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->learner_profile = isset($_REQUEST['learner_profile']) ? $_REQUEST['learner_profile'] : '';
        $tr->progression_discussed = isset($_REQUEST['progression_discussed']) ? $_REQUEST['progression_discussed'] : '';
	$tr->portfolio_prediction = isset($_REQUEST['portfolio_prediction']) ? $_REQUEST['portfolio_prediction'] : '';

        $tr->save($link);

        http_redirect("do.php?_action=read_training_record&id={$tr->id}");
    }

    private function save_tr_portfolio_enhancement(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->portfolio_enhancement = isset($_REQUEST['portfolio_enhancement']) ? $_REQUEST['portfolio_enhancement'] : '';

        $tr->save($link);

        http_redirect("do.php?_action=read_training_record&id={$tr->id}");
    }

    private function showEpaEntryLog(PDO $link)
    {
        $epa_id = isset($_REQUEST['epa_id']) ? $_REQUEST['epa_id'] : '';
        if($epa_id == '')
            return;

        echo '<table class="table table-bordered">';
        echo '<tr><th>Date Time</th><th>By</th><th>Details</th></tr>';
        $result = DAO::getResultset($link, "SELECT * FROM op_epa_log WHERE op_epa_id = '{$epa_id}' ORDER BY created DESC", DAO::FETCH_ASSOC);
        if(count($result) == 0)
            echo '<tr><td colspan="3"><i>No records found.</i></td> </tr>';
        else
        {
            $tasks = InductionHelper::getListOpTask();
            $task_applicables = InductionHelper::getListYesNo();
            $task_statuses = InductionHelper::getListOpTaskStatus();
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
                echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . '</td>';
                echo '<td class="small">';
                $detail = json_decode($row['log']);
                echo '<span class="text-bold">Task: </span>' . $tasks[$detail->task] . '<br>';
                echo '<span class="text-bold">Task Applicable: </span>' . $task_applicables[$detail->task_applicable] . '<br>';
                echo '<span class="text-bold">Status: </span>' . $task_statuses[$detail->task_status] . '<br>';
                echo '<span class="text-bold">Date: </span>' . Date::toShort($detail->task_date) . '<br>';
                echo '<span class="text-bold">Actual Date: </span>' . Date::toShort($detail->task_actual_date) . '<br>';
                echo '<span class="text-bold">Potential Achievement Month: </span>' . $detail->potential_achievement_month . '<br>';
                echo isset($task_applicables[$detail->task_epa_risk]) ?
                    '<span class="text-bold">EPA Risk: </span>' . $task_applicables[$detail->task_epa_risk]:
                    '<span class="text-bold">EPA Risk: </span>' . $detail->task_epa_risk;
                echo '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }

    public function saveEpaOwnerInOp(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;
        $epa_owner = isset($_REQUEST['epa_owner']) ? $_REQUEST['epa_owner'] : '';
        if($epa_owner == '')
            DAO::execute($link, "UPDATE tr_operations SET tr_operations.epa_owner = NULL WHERE tr_operations.tr_id = '{$tr_id}'");
        else
            DAO::execute($link, "UPDATE tr_operations SET tr_operations.epa_owner = '{$epa_owner}' WHERE tr_operations.tr_id = '{$tr_id}'");
    }

    private function deleteEpaEntry(PDO $link)
    {
        if(!SOURCE_LOCAL && !in_array($_SESSION['user']->username, ["lmargach", "jcoates"]))
        {
            throw new Exception("You are not authorised to perform this action");
        }

        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $epa_entry_id = isset($_REQUEST['epa_entry_id']) ? $_REQUEST['epa_entry_id'] : '';
        if($tr_id == '' || $epa_entry_id == '')
            throw new Exception('Missing querystring argument: tr_id, epa_entry_id');


        $deleted_rows = DAO::execute($link, "DELETE FROM op_epa WHERE op_epa.id = '{$epa_entry_id}' AND op_epa.tr_id = '{$tr_id}'");
        echo "{$deleted_rows} entry has been removed.";
    }

    private function saveMatrixTab(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        $entry = new stdClass();
        $entry->tr_id = $tr_id;
        $entry->coordinator_comments = substr($_REQUEST['coordinator_comments'], 0, 799);
        $entry->reason_outside_matrix = $_REQUEST['reason_outside_matrix'];
        DAO::saveObjectToTable($link, 'tr_operations', $entry);
    }

    public function getHoldingSectionComments(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $field = isset($_REQUEST['field'])?$_REQUEST['field']:'';

        if($tr_id == '' || $field == '')
            return;

	$table = 'tr';
        $html = '<table class="resultset" cellpadding="4">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th>';
        $html .= in_array($_SESSION['user']->username, ["jcoates"]) ? '<th></th></tr>' : '</tr>';
        if($field == 'coe_notes')
        {
            $notes = DAO::getSingleValue($link, "SELECT tr_coe.{$field} FROM tr_coe WHERE tr_id = '{$tr_id}'");
		$table = 'tr_coe';
        }
        else
        {
            $notes = DAO::getSingleValue($link, "SELECT tr.{$field} FROM tr WHERE tr.id = '{$tr_id}'");
        }
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);	
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Comment) . '</td>';
		if(in_array($_SESSION['user']->username, ["jcoates"]))
                {
                    $html .= '<td><form name="frmDelXmlNotes_'.time().'" method="post" action="do.php?_action=ajax_tracking">';
                    $html .= '<input type="hidden" name="subaction" value="delete_additional_tab_comments" />';
                    $html .= '<input type="hidden" name="tr_id" value="'.$tr_id.'" />';
                    $html .= '<input type="hidden" name="table" value="'.$table.'" />';
                    $html .= '<input type="hidden" name="field" value="'.$field.'" />';
                    $html .= '<input type="hidden" name="timestamp" value="'.$note->DateTime.'" />';
                    $html .= '<button type="submit" style="color: red;">Del</button>';
                    $html .= '</form></td>';
                }
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo $html;
    }

    public function getOperationNotes(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $field = isset($_REQUEST['field'])?$_REQUEST['field']:'';

        if($tr_id == '' || $field == '')
            return;

        $html = '<table class="table table-bordered">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT tr_operations.{$field} FROM tr_operations WHERE tr_operations.tr_id = '{$tr_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Comments) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        echo $html;
    }

    public function reset_register(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            return;
        }

        $session = OperationsSession::loadFromDatabase($link, $id);
        if($session->id != '')
        {
            $session->status = 'NC';
            $session->save($link);
        }

        echo '1';
    }

    public function save_programme_induction_capacity(PDO $link)
    {
        $framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
        if($framework_id == '')
        {
            return;
        }

        foreach($_REQUEST AS $key => $value)
        {
            if(substr($key, 0, 3) == 'fn_')
            {
                $month = str_replace('fn_', '', $key);
                $objCapacity = DAO::getObject($link, "SELECT * FROM framework_induction_capacity WHERE framework_id = '{$framework_id}' AND month = '{$month}'");
                if(!isset($objCapacity->month))
                {
                    $objCapacity = new stdClass();
                    $objCapacity->framework_id = $framework_id;
                    $objCapacity->month = $month;
                    $objCapacity->capacity = $value;
                }
                else
                {
                    $objCapacity->capacity = $value;
                }
                DAO::saveObjectToTable($link, 'framework_induction_capacity', $objCapacity);
            }
        }

        http_redirect("do.php?_action=read_framework&id={$framework_id}");
    }	

    public function fetch_last_lar_summary(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
        {
            return;
        }

        $sql = <<<SQL
SELECT 
	tr_operations.lar_details 
FROM tr_operations WHERE tr_id = '{$tr_id}';

SQL;
        $notes = DAO::getObject($link, $sql);

        if (isset($notes->lar_details) && $notes->lar_details != '') 
        {
            $notes = XML::loadSimpleXML($notes->lar_details);
            $summary = [];

            foreach ($notes->Note as $note) 
            {
                $summary[] = trim($note->Summary->__toString());
            }
            for($i = count($summary) - 1; $i >= 0; $i--)
            {
                if(trim($summary[$i]) != '')
                {
                    echo $summary[$i];
                    break;
                }
            }
        }
    }		

    public function update_tr_gold_star_employer(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';

        if($value == 'true')
        {
            DAO::execute($link, "UPDATE tr SET tr.gold_employer = '1' WHERE tr.id = '{$tr_id}'");
        }
        if($value == 'false')
        {
            DAO::execute($link, "UPDATE tr SET tr.gold_employer = '0' WHERE tr.id = '{$tr_id}'");
        }

        echo 'This value has been updated successfully.';
    }

    public function update_tr_gold_star_learner(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';

        if($value == 'true')
        {
            DAO::execute($link, "UPDATE tr SET tr.gold_learner = '1' WHERE tr.id = '{$tr_id}'");
        }
        if($value == 'false')
        {
            DAO::execute($link, "UPDATE tr SET tr.gold_learner = '0' WHERE tr.id = '{$tr_id}'");
        }

        echo 'This value has been updated successfully.';
    }

    public function delete_induction(PDO $link)
    {
        if($_SESSION['user']->username != 'jcoates')
        {
            throw new Exception("Unauthorized action.", 401);
        }

        $inductee_id = isset($_POST['inductee_id']) ? $_POST['inductee_id']: '';
        if($inductee_id == '')
        {
            throw new Exception("Missing querystring argument: inductee_id");
        }

        $inductee = Inductee::loadFromDatabase($link, $inductee_id);
        if(!is_null($inductee->sunesis_username))
        {
            throw new Exception("This record has got training record so it cannot be deleted.");
        }

        $inductee->delete($link);

	http_redirect('do.php?_action=induction_home');
    }

    public function add_option_to_lookup(PDO $link)
    {
        $lookup_table_name = isset($_POST['lookup_table_name']) ? $_POST['lookup_table_name']: '';
        $lookup_table_option = isset($_POST['lookup_table_option']) ? $_POST['lookup_table_option']: '';
        if($lookup_table_name == '' || $lookup_table_option == '')
        {
            throw new Exception("Missing querystring arguments");
        }

        $new_option = new stdClass();
        $new_option->id = null;
        $new_option->description = $lookup_table_option;
        DAO::saveObjectToTable($link, $lookup_table_name, $new_option);
        echo 'success';
    }

	public function delete_additional_tab_comments(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $table = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
        $field = isset($_REQUEST['field']) ? $_REQUEST['field'] : '';
        $timestamp = isset($_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : '';

        if($tr_id == '' || $table == '' || $field == '' || $timestamp == '')
        {
            throw new Exception("Missing querystring arguments.");
        }

        if($field == 'coe_notes')
        {
            $notes = DAO::getSingleValue($link, "SELECT {$table}.{$field} FROM tr WHERE tr_id = '{$tr_id}'");
        }
        else
        {
            $notes = DAO::getSingleValue($link, "SELECT {$table}.{$field} FROM tr WHERE tr.id = '{$tr_id}'");
        }
        if($notes == '')
        {
            http_redirect('do.php?_action=read_training_record&id='.$tr_id.'&tabHci=1');
        }

        $changed = false;
        $notes = XML::loadSimpleXML($notes);
        foreach($notes->Note AS $note)
        {
            if($note->DateTime == $timestamp)
            {
                $dom = dom_import_simplexml($note);
                $dom->parentNode->removeChild($dom);

                $modified_xml = $notes->saveXml();
                $modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

                $changed = true;
                break;
            }
        }

        if($changed)
        {
            if($table == 'tr')
            {
                DAO::execute($link, "UPDATE tr SET tr.{$field} = '{$modified_xml}' WHERE tr.id = '{$tr_id}'");
            }
            else
            {
                DAO::execute($link, "UPDATE {$table} SET {$table}.{$field} = '{$modified_xml}' WHERE {$table}.tr_id = '{$tr_id}'");
            }
        }

        http_redirect('do.php?_action=read_training_record&id='.$tr_id.'&tabHci=1');
    }

    private function importLearnersFromSalesforceV2(PDO $link)
    {
        $FirstName = isset($_REQUEST['FirstName'])?$_REQUEST['FirstName']:'';
        $LastName = isset($_REQUEST['LastName'])?$_REQUEST['LastName']:'';
        $Birthdate = isset($_REQUEST['Birthdate'])?$_REQUEST['Birthdate']:'';
        $National_Insurance__c = isset($_REQUEST['National_Insurance__c'])?$_REQUEST['National_Insurance__c']:'';

        if($FirstName == '' && $LastName == '' && $Birthdate == '' && $National_Insurance__c == '')
            throw new Exception('No input given.');

        $Birthdate = $Birthdate != ''?Date::toMySQL($Birthdate):'';

        $sql = new SQLStatement("
SELECT 
       Id,
       FirstName, 
       LastName, 
       seven20__Gender__c, 
       Birthdate, 
       National_Insurance__c, 
       Phone, 
       MobilePhone, 
       Email  
FROM 
     Contact");
        if($FirstName != '')
            $sql->setClause("WHERE FirstName LIKE '%{$FirstName}%'");
        if($LastName != '')
            $sql->setClause("WHERE LastName LIKE '%{$LastName}%'");
        if($Birthdate != '')
            $sql->setClause("WHERE Birthdate = {$Birthdate}");
        if($National_Insurance__c != '')
            $sql->setClause("WHERE National_Insurance__c LIKE '%{$National_Insurance__c}%'");

        $html = '';
        ini_set("soap.wsdl_cache_enabled", "0");
        require_once ('lib/salesforce/SforceEnterpriseClient.php');
        $mySforceConnection = new SforceEnterpriseClient();
        $mySoapClient = $mySforceConnection->createConnection("lib/salesforce/enterprise.wsdl.xml");

        $sf_username = SystemConfig::getEntityValue($link, 'salesforce_username');
        $sf_password = SystemConfig::getEntityValue($link, 'salesforce_password');
        $sf_token = SystemConfig::getEntityValue($link, 'salesforce_token');
        if($sf_username == '' || $sf_password == '' || $sf_token == '')
            throw new Exception('Missing Salesforce authentication details');

        //$mylogin = $mySforceConnection->login("inaam@compact-soft.com", "Thegr88tsTBsbL1eJe8xFoPZDqdPGtfeDSz");
        $mylogin = $mySforceConnection->login($sf_username, $sf_password.$sf_token);

        $sfRecords = array();
        $importedRecords = DAO::getSingleColumn($link, "SELECT sf_Id FROM inductees");

	    $EmploymentStartDate = '';
        $InductionDate = '';
        $EngagementManager = '';
        $BusinessConsultant = '';
        $CandidateRecruiter = '';
        $EmployerRecruiter = '';
        $VacanySource = '';
        $VacanyCategory = '';
        $PaidWorkingHours = '';
        $AnnualSalary = '';
        $LevyPayer = '';
        $FunctionalSkillsStatusEnglish = '';
        $Red_Flag_Details__c = '';
        $FunctionalSkillsStatusMaths = '';
        $ApprovedOpportunityConcerns = '';
        $seven20__Job__c = '';
        $AccountManager = '';
        $PlacementName = '';

        $response = $mySforceConnection->query($sql->__toString());//throw new Exception(json_encode($response));
        $queryResult = new QueryResult($response);
        foreach($queryResult->records AS $record)
        {
            $html .= '<tr>';
            $html .= isset($record->Id) ? '<td>' . $record->Id . '</td>':'<td></td>';
            $html .= isset($record->FirstName) ? '<td>' . $record->FirstName . '</td>':'<td></td>';
            $html .= isset($record->LastName) ? '<td>' . $record->LastName . '</td>':'<td></td>';
            $html .= isset($record->seven20__Gender__c) ? '<td>' . $record->seven20__Gender__c . '</td>':'<td></td>';
            $html .= isset($record->Birthdate) ? '<td>' . Date::toShort($record->Birthdate) . '</td>':'<td></td>';
            $html .= isset($record->National_Insurance__c) ? '<td>' . $record->National_Insurance__c . '</td>':'<td></td>';
            $html .= isset($record->Phone) ? '<td>' . $record->Phone . '</td>':'<td></td>';
            $html .= isset($record->MobilePhone) ? '<td>' . $record->MobilePhone . '</td>':'<td></td>';
            $html .= isset($record->Email) ? '<td>' . $record->Email . '</td>':'<td></td>';

	        if(isset($record->Id) && $record->Id != '')
            {
                $placement_sql = "
                    SELECT 
                        Id, Name, FS_Exemption_Status_English__c, 
                        FS_Exemption_Status_Maths__c, 
                        seven20__Start_Date__c, 
                        seven20__Job__c, 
                        Induction_Date__c, 
                        Paid_Working_Hours__c, 
                        seven20__Salary__c, 
                        fLevyPayer__c,
                        Red_Flag_Details__c   
                    FROM 
                        seven20__Placement__c 
                    WHERE 
                        seven20__Placement__c.seven20__Candidate__c = '{$record->Id}'";
                $placement_response = $mySforceConnection->query($placement_sql);
                $placement_queryResult = new QueryResult($placement_response);

                $placement_queryLastResponse = $mySforceConnection->getLastResponse();
                $placement_queryLastResponse = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $placement_queryLastResponse);
                $placement_queryLastResponseXml = new SimpleXMLElement($placement_queryLastResponse);
                

                foreach($placement_queryResult->records AS $placement_record)
                {
                    if(isset($placement_record->Id) && $placement_record->Id != '')
                    {
                        $html .= '<td>';

			if(isset($placement_record->Name))
                        {
                            $html .= '<span class="text-bold">Placement Name:</span> ' . $placement_record->Name . '<br>';
                            $PlacementName = $placement_record->Name;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfName))
                        {
                            $html .= '<span class="text-bold">Placement Name:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfName->__toString() . '<br>';
                            $PlacementName = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfName->__toString();
                        }

                        if(isset($placement_record->seven20__Start_Date__c))
                        {
                            $html .= '<span class="text-bold">Employment Start Date:</span> ' . Date::toShort($placement_record->seven20__Start_Date__c) . '<br>';
                            $EmploymentStartDate = $placement_record->seven20__Start_Date__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Start_Date__c))
                        {
                            $html .= '<span class="text-bold">Employment Start Date:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Start_Date__c->__toString() . '<br>';
                            $EmploymentStartDate = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Start_Date__c->__toString();
                        }

                        if(isset($placement_record->Induction_Date__c))
                        {
                            $html .= '<span class="text-bold">Induction Date:</span> ' . Date::toShort($placement_record->Induction_Date__c) . '<br>';
                            $InductionDate = $placement_record->Induction_Date__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfInduction_Date__c))
                        {
                            $html .= '<span class="text-bold">Induction Date:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfInduction_Date__c->__toString() . '<br>';
                            $InductionDate = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfInduction_Date__c->__toString();
                        }

                        if(isset($placement_record->FS_Exemption_Status_Maths__c))
                        {
                            $html .= '<span class="text-bold">FS Maths :</span> ' . $placement_record->FS_Exemption_Status_Maths__c . '<br>';
                            $FunctionalSkillsStatusMaths = $placement_record->FS_Exemption_Status_Maths__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFS_Exemption_Status_Maths__c))
                        {
                            $html .= '<span class="text-bold">FS Maths:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFS_Exemption_Status_Maths__c->__toString() . '<br>';
                            $FunctionalSkillsStatusMaths = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFS_Exemption_Status_Maths__c->__toString();
                        }

                        if(isset($placement_record->FS_Exemption_Status_English__c))
                        {
                            $html .= '<span class="text-bold">FS English :</span> ' . $placement_record->FS_Exemption_Status_English__c . '<br>';
                            $FunctionalSkillsStatusEnglish = $placement_record->FS_Exemption_Status_English__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFS_Exemption_Status_English__c))
                        {
                            $html .= '<span class="text-bold">FS English:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFS_Exemption_Status_English__c->__toString() . '<br>';
                            $FunctionalSkillsStatusEnglish = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfFS_Exemption_Status_English__c->__toString();
                        }

			if(isset($placement_record->Red_Flag_Details__c))
                        {
                            $html .= '<span class="text-bold">Red_Flag_Details__c :</span> ' . $placement_record->Red_Flag_Details__c . '<br>';
                            $Red_Flag_Details__c = $placement_record->Red_Flag_Details__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfRed_Flag_Details__c))
                        {
                            $html .= '<span class="text-bold">Red_Flag_Details__c:</span> ' . $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfRed_Flag_Details__c->__toString() . '<br>';
                            $Red_Flag_Details__c = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfRed_Flag_Details__c->__toString();
                        }

                        if(isset($placement_record->seven20__Job__c))
                        {
                            $seven20__Job__c = $placement_record->seven20__Job__c;
                        }
                        elseif(isset($placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Job__c))
                        {
                            $seven20__Job__c = $placement_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Job__c->__toString();
                        }

                        if($seven20__Job__c != '' )
                        {
                            $job_sql = "
                            SELECT 
                                Id, 
                                Vacancy_Source__c, 
                                Vacancy_Category__c, 
                                Paid_Working_Hours__c, 
                                seven20__Salary__c, 
                                Levy_Payer__c, 
                                Engagement_Manager_Specialist__c, 
                                Business_Consultant__c, 
                                Candidate_Recruiter__c, 
                                Employer_Recruiter__c, 
                                OwnerId, 
                                Approved_Opportunity_Concerns__c 
                            FROM 
                                seven20__Job__c 
                            Where 
                                Id = '{$seven20__Job__c}'";
                            $job_response = $mySforceConnection->query($job_sql);
                            $job_queryResult = new QueryResult($job_response);

                            $job_queryLastResponse = $mySforceConnection->getLastResponse();
                            $job_queryLastResponse = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $job_queryLastResponse);
                            $job_queryLastResponseXml = new SimpleXMLElement($job_queryLastResponse);
                            
                            foreach($job_queryResult->records AS $job_record)
                            {
                                //
                                if(isset($job_record->Id))
                                {
                                    if(isset($job_record->Vacancy_Source__c))
                                    {
                                        $html .= '<span class="text-bold">Vacancy Source :</span> ' . $job_record->Vacancy_Source__c . '<br>';
                                        $VacanySource = $job_record->Vacancy_Source__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Source__c))
                                    {
                                        $html .= '<span class="text-bold">Vacancy Source:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Source__c->__toString() . '<br>';
                                        $VacanySource = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Source__c->__toString();
                                    }

                                    if(isset($job_record->Vacancy_Category__c))
                                    {
                                        $html .= '<span class="text-bold">Vacancy Category:</span> ' . $job_record->Vacancy_Category__c . '<br>';
                                        $VacanyCategory = $job_record->Vacancy_Category__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Category__c))
                                    {
                                        $html .= '<span class="text-bold">Vacancy Category:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Category__c->__toString() . '<br>';
                                        $VacanyCategory = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfVacancy_Category__c->__toString();
                                    }

                                    if(isset($job_record->seven20__Salary__c))
                                    {
                                        $html .= '<span class="text-bold">Annual Salary:</span> ' . $job_record->seven20__Salary__c . '<br>';
                                        $AnnualSalary = $job_record->seven20__Salary__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Salary__c))
                                    {
                                        $html .= '<span class="text-bold">Annual Salary:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Salary__c->__toString() . '<br>';
                                        $AnnualSalary = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfseven20__Salary__c->__toString();
                                    }

                                    if(isset($job_record->Paid_Working_Hours__c))
                                    {
                                        $html .= '<span class="text-bold">Paid working hours:</span> ' . $job_record->Paid_Working_Hours__c . '<br>';
                                        $PaidWorkingHours = $job_record->Paid_Working_Hours__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfPaid_Working_Hours__c))
                                    {
                                        $html .= '<span class="text-bold">Paid working hours:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfPaid_Working_Hours__c->__toString() . '<br>';
                                        $PaidWorkingHours = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfPaid_Working_Hours__c->__toString();
                                    }

                                    if(isset($job_record->Levy_Payer__c))
                                    {
                                        $html .= '<span class="text-bold">Levy Payer:</span> ' . $job_record->Levy_Payer__c . '<br>';
                                        $LevyPayer = $job_record->Levy_Payer__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfLevy_Payer__c))
                                    {
                                        $html .= '<span class="text-bold">Levy Payer:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfLevy_Payer__c->__toString() . '<br>';
                                        $LevyPayer = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfLevy_Payer__c->__toString();
                                    }

                                    if(isset($job_record->Approved_Opportunity_Concerns__c))
                                    {
                                        $html .= '<span class="text-bold">Approved opportunity concerns:</span> ' . $job_record->Approved_Opportunity_Concerns__c . '<br>';
                                        $ApprovedOpportunityConcerns = $job_record->Approved_Opportunity_Concerns__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfApproved_Opportunity_Concerns__c))
                                    {
                                        $html .= '<span class="text-bold">Approved opportunity concerns:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfApproved_Opportunity_Concerns__c->__toString() . '<br>';
                                        $ApprovedOpportunityConcerns = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfApproved_Opportunity_Concerns__c->__toString();
                                    }
                                    
                                    if(isset($job_record->Engagement_Manager_Specialist__c))
                                    {
                                        $Engagement_Manager_Specialist__c = $job_record->Engagement_Manager_Specialist__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfEngagement_Manager_Specialist__c))
                                    {
                                        $Engagement_Manager_Specialist__c = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfEngagement_Manager_Specialist__c->__toString();
                                    }
                                    if(isset($Engagement_Manager_Specialist__c) && $Engagement_Manager_Specialist__c != '')
                                    {
                                        $engagement_manager_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$Engagement_Manager_Specialist__c}'");
                                        $em_queryResult = new QueryResult($engagement_manager_response);
                                        foreach($em_queryResult AS $em_record)
                                        {
                                            $html .= '<span class="text-bold">Engagement Manager/ Specialist:</span> ';
                                            $html .= isset($em_record->FirstName) ? $em_record->FirstName . ' ' : '';
                                            $html .= isset($em_record->LastName) ? $em_record->LastName . '<br>' : '<br>';
                                            $EngagementManager = isset($em_record->FirstName) ? $em_record->FirstName . ' ' : '';
                                            $EngagementManager .= isset($em_record->LastName) ? $em_record->LastName . ' ' : '';
                                        }
                                    }

                                    if(isset($job_record->Business_Consultant__c))
                                    {
                                        $Business_Consultant__c = $job_record->Business_Consultant__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfBusiness_Consultant__c))
                                    {
                                        $Business_Consultant__c = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfBusiness_Consultant__c->__toString();
                                    }
                                    if(isset($Business_Consultant__c) && $Business_Consultant__c != '')
                                    {
                                        $bc_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$Business_Consultant__c}'");
                                        $bc_queryResult = new QueryResult($bc_response);
                                        foreach($bc_queryResult AS $bc_record)
                                        {
                                            $html .= '<span class="text-bold">Business Consultant:</span> ';
                                            $html .= isset($bc_record->FirstName) ? $bc_record->FirstName . ' ' : '';
                                            $html .= isset($bc_record->LastName) ? $bc_record->LastName . '<br>' : '<br>';
                                            $BusinessConsultant = isset($bc_record->FirstName) ? $bc_record->FirstName . ' ' : '';
                                            $BusinessConsultant .= isset($bc_record->LastName) ? $bc_record->LastName . ' ' : '';
                                        }
                                    }
                                    
                                    if(isset($job_record->Candidate_Recruiter__c))
                                    {
                                        $Candidate_Recruiter__c = $job_record->Candidate_Recruiter__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfCandidate_Recruiter__c))
                                    {
                                        $Candidate_Recruiter__c = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfCandidate_Recruiter__c->__toString();
                                    }
                                    if(isset($Candidate_Recruiter__c) && $Candidate_Recruiter__c != '')
                                    {
                                        $cr_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$Candidate_Recruiter__c}'");
                                        $cr_queryResult = new QueryResult($cr_response);
                                        foreach($cr_queryResult AS $cr_record)
                                        {
                                            $html .= '<span class="text-bold">Candidate Recruiter:</span> ';
                                            $html .= isset($cr_record->FirstName) ? $cr_record->FirstName . ' ' : '';
                                            $html .= isset($cr_record->LastName) ? $cr_record->LastName . '<br>' : '<br>';
                                            $CandidateRecruiter = isset($cr_record->FirstName) ? $cr_record->FirstName . ' ' : '';
                                            $CandidateRecruiter .= isset($cr_record->LastName) ? $cr_record->LastName . ' ' : '';
                                        }
                                    }

				                    if(isset($job_record->OwnerId))
                                    {
                                        $OwnerId = $job_record->OwnerId;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfOwnerId))
                                    {
                                        $OwnerId = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfOwnerId->__toString();
                                    }
                                    if(isset($OwnerId) && $OwnerId != '')
                                    {
                                        $am_response = $mySforceConnection->query("SELECT FirstName, LastName FROM User WHERE Id = '{$OwnerId}'");
                                        $am_queryResult = new QueryResult($am_response);
                                        foreach($am_queryResult AS $am_record)
                                        {
                                            $html .= '<span class="text-bold">Account Manager:</span> ';
                                            $html .= isset($am_record->FirstName) ? $am_record->FirstName . ' ' : '';
                                            $html .= isset($am_record->LastName) ? $am_record->LastName . '<br>' : '<br>';
                                            $AccountManager = isset($am_record->FirstName) ? $am_record->FirstName . ' ' : '';
                                            $AccountManager .= isset($am_record->LastName) ? $am_record->LastName . ' ' : '';
                                        }
                                    }

                                    if(isset($job_record->Employer_Recruiter__c))
                                    {
                                        $html .= '<span class="text-bold">Employer Recruiter:</span> ' . $job_record->Employer_Recruiter__c . '<br>';
                                        $EmployerRecruiter = $job_record->Employer_Recruiter__c;
                                    }
                                    elseif(isset($job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfEmployer_Recruiter__c))
                                    {
                                        $html .= '<span class="text-bold">Employer Recruiter:</span> ' . $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfEmployer_Recruiter__c->__toString() . '<br>';
                                        $EmployerRecruiter = $job_queryLastResponseXml->soapenvBody->queryResponse->result->records->sfEmployer_Recruiter__c->__toString();
                                    }
                                }
                            }// job foreach

                        }
			            

                        $html .= '</td>';
                    }
                }
		

            }
            else
            {
                echo '<td></td>';
            }

            if(!in_array($record->Id, $importedRecords))
                $html .= '<td><input class="chkSelectedLearners" type="checkbox" name="selectedLearners[]" value="' . $record->Id . '" /></td>';
            else
                $html .= '<td><i class="fa fa-link" title="Already imported and created in Sunesis"></i> </td>';
            $html .= '</tr>';

            if(isset($record->Id) && $record->Id != '')
            {
                $l = new stdClass();
                $l->Id = $record->Id;
                $l->FirstName = isset($record->FirstName)?$record->FirstName:'';
                $l->LastName = isset($record->LastName)?$record->LastName:'';
                $l->Gender__c = isset($record->seven20__Gender__c)?$record->seven20__Gender__c:'';
                $l->Birthdate = isset($record->Birthdate)?$record->Birthdate:'';
                $l->National_Insurance__c = isset($record->National_Insurance__c)?$record->National_Insurance__c:'';
                $l->Phone = isset($record->Phone)?$record->Phone:'';
                $l->MobilePhone = isset($record->MobilePhone)?$record->MobilePhone:'';
                $l->Email = isset($record->Email)?$record->Email:'';
		$l->EmploymentStartDate = $EmploymentStartDate;
                $l->InductionDate = $InductionDate;
                $l->EngagementManager = $EngagementManager;
                $l->BusinessConsultant = $BusinessConsultant;
                $l->CandidateRecruiter = $CandidateRecruiter;
                $l->VacanySource = $VacanySource;
                $l->VacanyCategory = $VacanyCategory;
                $l->PaidWorkingHours = $PaidWorkingHours;
                $l->AnnualSalary = $AnnualSalary;
                $l->LevyPayer = $LevyPayer;
                $l->FunctionalSkillsStatusMaths = $FunctionalSkillsStatusMaths;
                $l->FunctionalSkillsStatusEnglish = $FunctionalSkillsStatusEnglish;
                $l->Red_Flag_Details__c = substr($Red_Flag_Details__c, 0, 800);
                $l->EmployerRecruiter = $EmployerRecruiter;
                $l->ApprovedOpportunityConcerns = $ApprovedOpportunityConcerns;
                $l->AccountManager = $AccountManager;
                $l->PlacementName = $PlacementName;
                $sfRecords[] = $l;
            }
        }

        $html .= '<tr><td colspan="9"><span class="btn btn-sm btn-primary pull-right" onclick="createLearnerInSunesis();"><i class="fa fa-plus"></i> Create in Sunesis</span></td></tr>';

        DAO::multipleRowInsert($link, 'sf_contacts', $sfRecords);
        return $html;
    }

}