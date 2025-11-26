<?php
class create_ilr implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_POST['subaction']) ? $_POST['subaction'] : '';
        if($subaction == 'start_process')
        {
            $this->start_process($link);
            echo 'success';
            exit;
        }

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        if($tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        $_SESSION['bc']->add($link, "do.php?_action=create_ilr&tr_id={$tr->id}", "Create ILR");

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $tr->ob_learner_id);

        $assessor_user_type = User::TYPE_ASSESSOR;

	    $census_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN census_start_date AND census_end_date LIMIT 1");
        $contracts_ddl = DAO::getResultset($link, "SELECT id, title, contracts.`contract_year` FROM contracts WHERE contract_year = {$census_contract_year}  ORDER BY contract_year DESC, title");
        //$assessors_ddl = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), null FROM users WHERE users.type = '{$assessor_user_type}' AND users.web_access = '1' ORDER BY users.firstnames");
	    $assessors_ddl = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), (SELECT DISTINCT description FROM `lookup_user_types` WHERE id = users.type) AS _type FROM users WHERE users.employer_id = '{$tr->provider_id}' AND users.type NOT IN (5) ORDER BY _type DESC, firstnames");
        $courses_ddl = DAO::getResultset($link, "SELECT courses.id, courses.title, null FROM courses WHERE courses.framework_id = '{$tr->framework_id}' ORDER BY courses.title");

        $sunesis_tr = null;
        if($tr->sunesis_tr_id != '')
        {
            $sunesis_tr = DAO::getObject($link, "SELECT * FROM tr WHERE tr.id = '{$tr->sunesis_tr_id}'");
            if(!isset($sunesis_tr->id))
                $sunesis_tr = null;
        }

        include_once('tpl_create_ilr.php');
    }

    public function start_process(PDO $link)
    {
        $tr_id = isset($_POST['tr_id'])?$_POST['tr_id']:'';
        $course_id = isset($_POST['course_id'])?$_POST['course_id']:'';
        $contract_id = isset($_POST['contract_id'])?$_POST['contract_id']:'';
        $assessor_id = isset($_POST['assessor_id'])?$_POST['assessor_id']:'';
        if($tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        $ob_learner = $tr->getObLearnerRecord($link);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $course = Course::loadFromDatabase($link, $course_id);
        $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
	$employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);

        $sql = new SQLStatement("SELECT id FROM users WHERE users.type = '" . User::TYPE_LEARNER . "'");
        $sql->setClause("WHERE users.firstnames = '" . trim(addslashes($ob_learner->firstnames)) . "'");
        $sql->setClause("WHERE users.surname = '" . trim(addslashes($ob_learner->surname)) . "'");
        $sql->setClause("WHERE users.dob = '" . $ob_learner->dob . "'");
        $exists = DAO::getSingleValue($link, $sql->__toString());
        if($exists != '')
        {
            $sunesis_learner = User::loadFromDatabaseById($link, $exists);
        }
        else
        {
            $sunesis_learner = new User();
            $sunesis_learner->type = User::TYPE_LEARNER;
        }
        $learner_fields = [
            'firstnames', 'surname', 'gender', 'dob', 'home_address_line_1', 'home_address_line_2', 'home_address_line_3', 'home_address_line_4',
            'home_postcode', 'home_mobile', 'home_email', 'home_telephone', 'work_email', 'ethnicity', 'ni', 'uln',
        ];
        foreach($learner_fields AS $field)
        {
            $sunesis_learner->$field = $ob_learner->$field;
        }
	$sunesis_learner->ni = str_replace(' ', '', $sunesis_learner->ni);
        
        if($exists == '')
        {
            $sunesis_learner->username = $this->getUniqueUsername($link, $sunesis_learner->firstnames, $sunesis_learner->surname);
        }

	$sunesis_learner->employer_id = $employer_location->organisations_id;
        $sunesis_learner->employer_location_id = $employer_location->id;
	$sunesis_learner->work_address_line_1 = $employer_location->address_line_1;
        $sunesis_learner->work_address_line_2 = $employer_location->address_line_2;
        $sunesis_learner->work_address_line_3 = $employer_location->address_line_3;
        $sunesis_learner->work_address_line_4 = $employer_location->address_line_4;
        $sunesis_learner->work_postcode = $employer_location->postcode;
	$sunesis_learner->job_role = substr($tr->job_title, 0, 100);
        $sunesis_learner->rui = $tr->RUI;
        $sunesis_learner->pmc = $tr->PMC;
        if($tr->LLDD == 'Y')
        {
            $sunesis_learner->l14 = 1;
        }
        elseif($tr->LLDD == 'N')
        {
            $sunesis_learner->l14 = 2;
        }
        elseif($tr->LLDD == 'P')
        {
            $sunesis_learner->l14 = 3;
        }
        $sunesis_learner->lldd_cat = $tr->llddcat;
        $sunesis_learner->primary_lldd = $tr->primary_lldd;
        $sunesis_learner->literacy = $tr->literacy;
        if($tr->literacy != '' || $tr->literacy_other != '')
        {
            $sunesis_learner->literacy_diagnostic = 1;
        }
        $sunesis_learner->numeracy = $tr->numeracy;
        if($tr->numeracy != '' || $tr->numeracy_other != '')
        {
            $sunesis_learner->numeracy_diagnostic = 1;
        }
        $sunesis_learner->high_level = DAO::getSingleValue($link, "SELECT ilr_code FROM central.`lookup_prior_attainment` WHERE `code` IN ( SELECT `level` FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h'  );");
        $sunesis_learner->literacy_other = $tr->literacy_other;
        $sunesis_learner->numeracy_other = $tr->numeracy_other;
        if($tr->line_manager_id != '')
        {
            $line_manager = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$tr->line_manager_id}'");
            if(isset($line_manager->contact_id))
            {
                $sunesis_learner->line_manager = $line_manager->contact_name;    
                $sunesis_learner->line_manager_tel = $line_manager->contact_telephone;    
                $sunesis_learner->line_manager_email = $line_manager->contact_email;    
            }
        }
	$ob_learner_emergency_contact = DAO::getObject($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}' LIMIT 1");
        if(isset($ob_learner_emergency_contact->tr_id))
        {
            $sunesis_learner->nok_title = isset($ob_learner_emergency_contact->em_con_title) ? $ob_learner_emergency_contact->em_con_title : null;
            $sunesis_learner->nok_tel = isset($ob_learner_emergency_contact->em_con_tel) ? substr($ob_learner_emergency_contact->em_con_tel, 0, 20) : null;
            $sunesis_learner->nok_rel = isset($ob_learner_emergency_contact->em_con_rel) ? $ob_learner_emergency_contact->em_con_rel : null;
            $sunesis_learner->nok_name = isset($ob_learner_emergency_contact->em_con_name) ? $ob_learner_emergency_contact->em_con_name : null;
            $sunesis_learner->nok_mob = isset($ob_learner_emergency_contact->em_con_mob) ? substr($ob_learner_emergency_contact->em_con_mob, 0, 20) : null;
        }

        $sunesis_tr = new SunesisTrainingRecord();
        $sunesis_tr->populate($sunesis_learner, true);
        $sunesis_tr->id = NULL;
        $sunesis_tr->contract_id = $contract_id;
        $sunesis_tr->start_date = $tr->practical_period_start_date;
        $sunesis_tr->target_date = $tr->practical_period_end_date;
        $sunesis_tr->start_date_inc_epa = $tr->apprenticeship_start_date;
        $sunesis_tr->end_date_inc_epa = $tr->apprenticeship_end_date_inc_epa;
        $sunesis_tr->status_code = 1; // Continuing
        $sunesis_tr->employer_id = $tr->employer_id; 
        $sunesis_tr->employer_location_id = $tr->employer_location_id; 
        $sunesis_tr->provider_id = $tr->provider_id;
        $sunesis_tr->provider_location_id = $tr->provider_location_id;
        $sunesis_tr->provider_address_line_1 = $provider_location->address_line_1;
        $sunesis_tr->provider_address_line_2 = $provider_location->address_line_2;
        $sunesis_tr->provider_address_line_3 = $provider_location->address_line_3;
        $sunesis_tr->provider_address_line_4 = $provider_location->address_line_4;
        $sunesis_tr->provider_postcode = $provider_location->postcode;
        $sunesis_tr->provider_telephone = $provider_location->telephone;
        $sunesis_tr->ethnicity = $sunesis_learner->ethnicity;
        $sunesis_tr->work_experience = 0;
        $sunesis_tr->assessor = $assessor_id;
	$sunesis_tr->crm_contact_id = $tr->line_manager_id;
        $sunesis_tr->l36 = 0;
        if($tr->contracted_hours_per_week >= 30)
        {
            $sunesis_tr->otj_hours = $tr->off_the_job_hours_based_on_duration;
        }
        else
        {
            $sunesis_tr->otj_hours = $tr->part_time_otj_hours;
        }
	$sunesis_tr->work_address_line_1 = $employer_location->address_line_1;
        $sunesis_tr->work_address_line_2 = $employer_location->address_line_2;
        $sunesis_tr->work_address_line_3 = $employer_location->address_line_3;
        $sunesis_tr->work_address_line_4 = $employer_location->address_line_4;
        $sunesis_tr->work_postcode = $employer_location->postcode;
	$sunesis_tr->epa_organisation = $tr->epa_organisation;
        $sunesis_tr->prior_attainment_level = $sunesis_learner->high_level;
        $sunesis_tr->current_postcode = $sunesis_learner->home_postcode;
        $sunesis_tr->planned_epa_date = $tr->planned_epa_date;

        $sunesis_tr->home_email = $ob_learner->home_email;
        $sunesis_tr->home_telephone = $ob_learner->home_telephone;
        $sunesis_tr->home_mobile = $ob_learner->home_mobile;

	if(DB_NAME == "am_ela")
        {
            $sunesis_tr->sales_lead = $ob_learner->caseload_org_id;
        }

        $l03 = DAO::getSingleValue($link, "SELECT l03 FROM tr WHERE username = '{$sunesis_learner->username}' LIMIT 0,1");
        if($l03 == '')
        {
            $l03 = (int)DAO::getSingleValue($link, "SELECT MAX(l03) FROM tr WHERE l03 + 0 <> 0 AND LENGTH(RTRIM(l03)) = 12");
            $l03 += 1;
            $sunesis_tr->l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
            $l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
        }
        else
        {
            $sunesis_tr->l03 = $l03;
        }

        $link->beginTransaction();
        try
        {
            $sunesis_learner->save($link);
            
            $sunesis_tr->save($link);

            $courses_tr = (object)[
                'course_id' => $course->id,
                'tr_id' => $sunesis_tr->id,
                'qualification_id' => 0,
                'framework_id' => $framework->id,
            ];
            DAO::saveObjectToTable($link, 'courses_tr', $courses_tr);

            $student_framework = (object)[
                'title' => $framework->title,
                'id' => $framework->id,
                'tr_id' => $sunesis_tr->id,
                'sector' => $framework->framework_code,
                'comments' => $framework->comments,
                'duration_in_months' => $framework->duration_in_months,
            ];
            DAO::saveObjectToTable($link, 'student_frameworks', $student_framework);

            //$sql = "SELECT * FROM framework_qualifications WHERE auto_id IN (SELECT framework_qual_auto_id FROM ob_learner_quals WHERE tr_id = '{$tr->id}')";
	    $sql = "SELECT
            ob_learner_quals.`qual_start_date`,
            ob_learner_quals.`qual_end_date`,
            ob_learner_quals.`qual_exempt`,
            ob_learner_quals.`qual_sequence`,
            framework_qualifications.*
          FROM
            framework_qualifications
            INNER JOIN ob_learner_quals
            INNER JOIN ob_tr
              ON framework_qualifications.`id` = ob_learner_quals.`qual_id`
              AND framework_qualifications.`title` = ob_learner_quals.`qual_title`
              AND framework_qualifications.`framework_id` = ob_tr.`framework_id`
              AND ob_learner_quals.`tr_id` = ob_tr.`id`
          WHERE ob_tr.id = '{$tr->id}'
          ORDER BY framework_qualifications.`sequence`;
          ";	
            $framework_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
            foreach($framework_qualifications AS $framework_qualification)
            {
                $student_qualification = new StudentQualification();
                $student_qualification->populate($framework_qualification);
                $student_qualification->tr_id = $sunesis_tr->id;
                $student_qualification->framework_id = $framework->id;
                $student_qualification->auto_id = null;

		        $student_qualification->start_date = $framework_qualification['qual_start_date'];
                $student_qualification->end_date = $framework_qualification['qual_end_date'];
		        $student_qualification->qual_exempt = $framework_qualification['qual_exempt'];
                $student_qualification->aptitude = $framework_qualification['qual_exempt'];
                $student_qualification->qual_sequence = $framework_qualification['qual_sequence'];

                $student_qualification->save($link);
            }

            $contract = DAO::getObject($link, "SELECT * FROM contracts WHERE id = '{$contract_id}'");
            $submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() AND contract_year = '{$contract->contract_year}' AND contract_type = '$contract->funding_body' ORDER BY last_submission_date LIMIT 1;");
            if($submission == "")
                $submission = "W13";

            $ilr = XML::loadSimpleXML("<Learner></Learner>");
            if($sunesis_tr->l03 != '')
                $ilr->addChild("LearnRefNumber", trim($sunesis_tr->l03));
            if($ob_learner->uln != '')
                $ilr->addChild("ULN", trim($ob_learner->uln));
            if($sunesis_tr->surname != '')
                $ilr->addChild("FamilyName", trim($sunesis_tr->surname));
            if($sunesis_tr->firstnames != '')
                $ilr->addChild("GivenNames", trim($sunesis_tr->firstnames));
            if($sunesis_tr->dob != '')
                $ilr->addChild("DateOfBirth", trim($sunesis_tr->dob));
            if($sunesis_tr->ethnicity != '')
                $ilr->addChild("Ethnicity", trim($sunesis_tr->ethnicity));
            if($sunesis_tr->gender != '')
                $ilr->addChild("Sex", trim($sunesis_tr->gender));
            if($tr->LLDD == 'Y')
            {
                $ilr->addChild('LLDDHealthProb', '1');
                if($tr->primary_lldd != '')
                {
                    $LLDDandHealthProblem = $ilr->addChild('LLDDandHealthProblem');
                    $LLDDandHealthProblem->LLDDCat = $tr->primary_lldd;
                    $LLDDandHealthProblem->PrimaryLLDD = '1';
                }
                $ob_learner_lldd_cat = $tr->llddcat != '' ? explode(',', $tr->llddcat) : [];
                foreach($ob_learner_lldd_cat AS $cat)
                {
                    if($cat == $tr->primary_lldd)
                        continue;
                    $LLDDandHealthProblem = $ilr->addChild('LLDDandHealthProblem');
                    $LLDDandHealthProblem->LLDDCat = $cat;
                }
            }
            elseif($tr->LLDD == 'N')
            {
                $ilr->addChild('LLDDHealthProb', '2');
            }
            elseif($tr->LLDD == 'P')
            {
                $ilr->addChild('LLDDHealthProb', '9');
            }
            if($sunesis_tr->ni != '')
            {
                $ilr->addChild("NINumber", trim($sunesis_tr->ni));
            }
            
            if(trim($sunesis_tr->otj_hours) != '' && intval($sunesis_tr->otj_hours) > 0 )
            {
                $ilr->addChild("OTJHours", trim($sunesis_tr->otj_hours));
            }

            if($sunesis_tr->home_postcode != '')
            {
                $learnerContact = $ilr->addChild("LearnerContact");
                $learnerContact->addChild("LocType", "2");
                $learnerContact->addChild("ContType", "1");
                $learnerContact->addChild("PostCode", trim($sunesis_tr->home_postcode));
            }
            if($sunesis_tr->home_address_line_1 != '' || $sunesis_tr->home_address_line_2 != '' || $sunesis_tr->home_address_line_3 != '' || $sunesis_tr->home_address_line_4 != '')
            {
                $learnerContact = $ilr->addChild("LearnerContact");
                $learnerContact->addChild("LocType", "1");
                $learnerContact->addChild("ContType", "2");
                $postAdd = $learnerContact->addChild("PostAdd");
                if($sunesis_tr->home_address_line_1 != '')
                    $postAdd->addChild("AddLine1", trim($sunesis_tr->home_address_line_1));
                if($sunesis_tr->home_address_line_2 != '')
                    $postAdd->addChild("AddLine2", trim($sunesis_tr->home_address_line_2));
                if($sunesis_tr->home_address_line_3 != '')
                    $postAdd->addChild("AddLine3", trim($sunesis_tr->home_address_line_3));
                if($sunesis_tr->home_address_line_4 != '')
                    $postAdd->addChild("AddLine4", trim($sunesis_tr->home_address_line_4));
            }
            if($sunesis_tr->home_postcode != '')
            {
                $learnerContact = $ilr->addChild("LearnerContact");
                $learnerContact->addChild("LocType", "2");
                $learnerContact->addChild("ContType", "2");
                $learnerContact->addChild("PostCode", trim($sunesis_tr->home_postcode));
            }
            if($sunesis_tr->home_telephone != '')
            {
                $learnerContact = $ilr->addChild("LearnerContact");
                $learnerContact->addChild("LocType", "3");
                $learnerContact->addChild("ContType", "2");
                $learnerContact->addChild("TelNumber", trim($sunesis_tr->home_telephone));
            }
            if($sunesis_tr->home_email != '')
            {
                $learnerContact = $ilr->addChild("LearnerContact");
                $learnerContact->addChild("LocType", "4");
                $learnerContact->addChild("ContType", "2");
                $learnerContact->addChild("Email", trim($sunesis_tr->home_email));
            }
            $trRuis = $tr->RUI != '' ? explode(',', $tr->RUI) : [];
            $trPmcs = $tr->PMC != '' ? explode(',', $tr->PMC) : [];
            foreach($trRuis AS $v)
            {
                $ContactPreference = $ilr->addChild('ContactPreference');
                $ContactPreference->ContPrefType = 'RUI';
                $ContactPreference->ContPrefCode = $v;
            }
            foreach($trPmcs AS $v)
            {
                $ContactPreference = $ilr->addChild('ContactPreference');
                $ContactPreference->ContPrefType = 'PMC';
                $ContactPreference->ContPrefCode = $v;
            }
            $prior_attain = DAO::getSingleValue($link, "SELECT `level` FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h' LIMIT 1");
            if($prior_attain != '')
            {
                $PriorAttain = $ilr->addChild("PriorAttain");
                $PriorAttain->PriorLevel = $this->mapObPriorAttainToIlr(trim($prior_attain));
                $PriorAttain->DateLevelApp = '';
            }

	    if(in_array($course->programme_type, ["7"]))
            {
                $eng_actual_grade = DAO::getSingleValue($link, "SELECT a_grade FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND `level` = '101' AND q_type = 'g' LIMIT 1");    
                if($eng_actual_grade != '')
                    $ilr->addChild("EngGrade", trim($eng_actual_grade));
    
                $math_actual_grade = DAO::getSingleValue($link, "SELECT a_grade FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND `level` = '102' AND q_type = 'g' LIMIT 1");    
                if($math_actual_grade != '')
                    $ilr->addChild("MathGrade", trim($math_actual_grade));
            }
                
            $prior_enrolment_emp_date = new Date($sunesis_tr->start_date);
            $prior_enrolment_emp_date->subtractDays(1);
            $LearnerEmploymentStatus = $ilr->addChild('LearnerEmploymentStatus');
            $LearnerEmploymentStatus->EmpStat = $tr->EmploymentStatus;
            $LearnerEmploymentStatus->DateEmpStatApp = $prior_enrolment_emp_date->formatMySQL();
	    $LearnerEmploymentStatus->EmpId = DAO::getSingleValue($link, "SELECT edrs FROM organisations WHERE id = '{$tr->employer_id}'");
            if($tr->EmploymentStatus == '10') // 10 = In paid employment
            {
                $SEI = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $SEI->addChild('ESMType', 'SEI');
                $SEI->addChild('ESMCode', $tr->SEI);

                $SEM = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $SEM->addChild('ESMType', 'SEM');
                $SEM->addChild('ESMCode', $tr->SEM);

                $LOE = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $LOE->addChild('ESMType', 'LOE');
                $LOE->addChild('ESMCode', $tr->LOE);

                $EII = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $EII->addChild('ESMType', 'EII');
                $EII->addChild('ESMCode', $tr->EII);
            }
            //11 = Not in paid employment, looking for work and available to start work
            //12 = Not in paid employment, not looking for work and/or not available to start work
            if($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12')
            {
                $LOU = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $LOU->addChild('ESMType', 'LOU');
                $LOU->addChild('ESMCode', $tr->LOU);

                $BSI = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $BSI->addChild('ESMType', 'BSI');
                $BSI->addChild('ESMCode', $tr->BSI);

                $PEI = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $PEI->addChild('ESMType', 'PEI');
                $PEI->addChild('ESMCode', $tr->PEI);
            }

            $this->createLearningDelivery($link, $sunesis_tr, $course, $framework, $contract, $ilr, $tr);

            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = FALSE;
            @$dom->loadXML($ilr->saveXML());
            $dom->formatOutput = TRUE;
            $modified_ilr = $dom->saveXml();
            $modified_ilr = str_replace('<?xml version="1.0"?>', '', $modified_ilr);

            $ilr_entry = (object)[
                'L03' => $sunesis_tr->l03,
                'ilr' => $modified_ilr,
                'submission' => $submission,
                'contract_type' => $contract->contract_type != '' ? $contract->contract_type : 0,
                'tr_id' => $sunesis_tr->id,
                'is_active' => 1,
                'contract_id' => $contract->id,
            ];

            DAO::saveObjectToTable($link, "ilr", $ilr_entry);

	    // create reviews 
            if( $framework->first_review != '' && $framework->review_frequency != '' )
            {
                $reviews_to_create = [];
                $assessor_username = DAO::getSingleValue($link, "SELECT username FROM users WHERE users.id = '{$assessor_id}'");
                $first_review_days = intval($framework->first_review)*7;
                $subsequent_review_days = intval($framework->review_frequency)*7;    
                $_review_dates = OnboardingHelper::getReviewsDates($tr->practical_period_start_date, $tr->practical_period_end_date, $first_review_days, $subsequent_review_days);
                foreach ($_review_dates as $_review_number => $_review_date) 
                {
                    $reviews_to_create[] = [
                        'id' => null,
                        'tr_id' => $sunesis_tr->id,
                        'due_date' => Date::toMySQL($_review_date),
                        'assessor' => $assessor_username,
                    ];
                }
                if( count($reviews_to_create) > 0 )
                {
                    DAO::multipleRowInsert($link, 'assessor_review', $reviews_to_create);
                }
            }

            $ob_learner->sunesis_learner_id = $sunesis_learner->id;
            $ob_learner->save($link);

            $tr->sunesis_tr_id = $sunesis_tr->id;
            $tr->status_code = TrainingRecord::STATUS_CONVERTED;
            $tr->save($link);

            $link->commit();
        }
        catch(Exception $ex)
        {
            $link->rollback();
            throw new WrappedException($ex);
        }

    }

    private function getUniqueUsername(PDO $link, $firstnames, $surname)
	{
		$number_of_attempts = 0;
		$i = 1;
		do
		{
			$number_of_attempts++;
			if($number_of_attempts > 29)
				return null;
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

    private function createLearningDelivery(PDO $link, $tr, $course, $framework, $contract, &$ilr, $ob_tr)
    {
        $AimSeqNumber = 1;
        $ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '$tr->provider_id' AND ukprn NOT IN (SELECT ukprn FROM organisations WHERE organisation_type = '1')");
        if(in_array($course->programme_type, [2, 7, 37]))
        {
            $learningDelivery = $ilr->addChild("LearningDelivery");

            $learningDelivery->addChild("LearnAimRef", "ZPROG001");
            $learningDelivery->addChild("AimType", "1");
            $learningDelivery->addChild("AimSeqNumber", $AimSeqNumber);
            $learningDelivery->addChild("LearnStartDate", $tr->start_date);
            $learningDelivery->addChild("LearnPlanEndDate", $tr->target_date);
            if($course->programme_type == '2')
                $learningDelivery->addChild("FundModel", "36");
            elseif($course->programme_type == '37')
                $learningDelivery->addChild("FundModel", "37");
            elseif ($framework->fund_model != '')
                $learningDelivery->addChild("FundModel", $framework->fund_model);
            if(trim($framework->PwayCode) != '')    
                $learningDelivery->addChild("PwayCode", $framework->PwayCode);
            if(trim($framework->StandardCode) != '')
                $learningDelivery->addChild("StdCode", $framework->StandardCode);

	        $learningDelivery->addChild("ProgType", $framework->framework_type);
            $learningDelivery->addChild("EPAOrgID", $ob_tr->epa_organisation);
	        if($ob_tr->contracted_hours_per_week >= 30)
            {
                $learningDelivery->addChild("PHours", $ob_tr->off_the_job_hours_based_on_duration);
            }
            else
            {
                $learningDelivery->addChild("PHours", $ob_tr->part_time_otj_hours);
            }	
            
            if($ukprn != '')
                $learningDelivery->addChild("PartnerUKPRN", $ukprn);
            if($tr->work_postcode != '')
                $learningDelivery->addChild("DelLocPostCode", $tr->work_postcode);
            $learningDelivery->addChild("PropFundRemain", "100");
            $learningDelivery->addChild("CompStatus", "1");
            $SOF = in_array(DB_NAME, ["am_ela"]) ? '105': $this->getValueFromTemplate($contract->template, str_replace("/" , "", "ZPROG001"), "SOF");
            $FFI = $this->getValueFromTemplate($contract->template, str_replace("/" , "", "ZPROG001"), "FFI");
            if($SOF != '')
            {
                $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
                $LearningDeliveryFAM->addChild("LearnDelFAMType", "SOF");
                $LearningDeliveryFAM->addChild("LearnDelFAMCode", $SOF);
            }
            if($FFI != '')
            {
                $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
                $LearningDeliveryFAM->addChild("LearnDelFAMType", "FFI");
                $LearningDeliveryFAM->addChild("LearnDelFAMCode", $FFI);
            }

            $trHhs = $ob_tr->hhs != '' ? explode(',', $ob_tr->hhs) : [];
            foreach($trHhs AS $hhs)
            {
                $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
                $LearningDeliveryFAM->addChild("LearnDelFAMType", "HHS");
                $LearningDeliveryFAM->addChild("LearnDelFAMCode", $hhs);
            }

	        if(in_array(DB_NAME, ["am_ela"]))
            {
                $LearningDeliveryFAM = $learningDelivery->addChild('LearningDeliveryFAM');    
                $LearningDeliveryFAM->addChild('LearnDelFAMType', 'ACT');
                $LearningDeliveryFAM->addChild('LearnDelFAMCode', '1');
                $LearningDeliveryFAM->addChild('LearnDelFAMDateFrom', $tr->start_date);
            }

            $tnp1_prices = (is_null($ob_tr->tnp1) || $ob_tr->tnp1 == '0') ? [] : json_decode($ob_tr->tnp1);
            $tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $tnp1_prices);
            $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));

            if($tnp1_total > 0)
            {
                $TrailblazerApprenticeshipFinancialRecord = $learningDelivery->addChild("TrailblazerApprenticeshipFinancialRecord");
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinType', 'TNP');
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinCode', '1');
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinDate', $tr->start_date);
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinAmount', ceil($tnp1_total));
            }
            if($ob_tr->epa_price != '')
            {
                $TrailblazerApprenticeshipFinancialRecord = $learningDelivery->addChild("TrailblazerApprenticeshipFinancialRecord");
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinType', 'TNP');
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinCode', '2');
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinDate', $tr->start_date);
                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinAmount', ceil($ob_tr->epa_price));
            }

        }

        $result = DAO::getResultset($link, "SELECT DISTINCT student_qualifications.id, student_qualifications.start_date, student_qualifications.end_date, student_qualifications.qualification_type, student_qualifications.qual_exempt FROM student_qualifications INNER JOIN framework_qualifications ON student_qualifications.framework_id = framework_qualifications.framework_id WHERE tr_id = '{$tr->id}' ORDER BY qual_sequence", DAO::FETCH_ASSOC);
        foreach($result AS $row_sub)
        {
	    $aim_reference = str_replace("/" , "", $row_sub['id']);

            $AimSeqNumber++;

            $learningDelivery = $ilr->addChild("LearningDelivery");

	    if( in_array( substr($aim_reference, -2, 2), ["GW", "EP"] ) || $row_sub['qual_exempt'] == '1' )
            {
                $learningDelivery->addChild("Exclude", 1);    
            }

            $learningDelivery->addChild("LearnAimRef", str_replace("/" , "", $row_sub['id']));
            if($course->programme_type == '2')
                $learningDelivery->addChild("AimType", "3");
            elseif($course->programme_type == '7')
                $learningDelivery->addChild("AimType", "5");
            else
                $learningDelivery->addChild("AimType", "4");
            $learningDelivery->addChild("AimSeqNumber", $AimSeqNumber);
            $learningDelivery->addChild("LearnStartDate", $row_sub['start_date']);
            $learningDelivery->addChild("LearnPlanEndDate", $row_sub['end_date']);
            if($course->programme_type == '1' || $course->programme_type == '2')
                $learningDelivery->addChild("FundModel", "36");
            elseif($course->programme_type=='3')
                $learningDelivery->addChild("FundModel", "21");
            elseif($course->programme_type=='4')
                $learningDelivery->addChild("FundModel", "22");
            elseif($course->programme_type=='5')
                $learningDelivery->addChild("FundModel", "70");
            elseif($course->programme_type=='6')
                $learningDelivery->addChild("FundModel", "10");
            elseif($course->programme_type=='7')
                $learningDelivery->addChild("FundModel", "25");
            elseif ($framework->fund_model != '')
                $learningDelivery->addChild("FundModel", $framework->fund_model);
            if($course->programme_type!='6')
                $learningDelivery->addChild("ProgType", $framework->framework_type);
            if($course->programme_type!='1' && $course->programme_type!='6')
                $learningDelivery->addChild("FworkCode", $framework->framework_code);
            if(trim($framework->PwayCode) != '')    
                $learningDelivery->addChild("PwayCode", $framework->PwayCode);
            if(trim($framework->StandardCode) != '')
                $learningDelivery->addChild("StdCode", $framework->StandardCode);
            $learningDelivery->addChild("PartnerUKPRN", $ukprn);    
            if($tr->work_postcode != '')
                $learningDelivery->addChild("DelLocPostCode", $tr->work_postcode);
            $learningDelivery->addChild("PropFundRemain", "100");
            $learningDelivery->addChild("CompStatus", "1");
            $SOF = in_array(DB_NAME, ["am_ela"]) ? '105': $this->getValueFromTemplate($contract->template, str_replace("/" , "", "ZPROG001"), "SOF");
            $FFI = $this->getValueFromTemplate($contract->template, str_replace("/" , "", "ZPROG001"), "FFI");
            if($SOF != '')
            {
                $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
                $LearningDeliveryFAM->addChild("LearnDelFAMType", "SOF");
                $LearningDeliveryFAM->addChild("LearnDelFAMCode", $SOF);
            }
            if($FFI != '')
            {
                $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
                $LearningDeliveryFAM->addChild("LearnDelFAMType", "FFI");
                $LearningDeliveryFAM->addChild("LearnDelFAMCode", $FFI);
            }

	    if($row_sub['qualification_type'] == 'FS' && in_array(DB_NAME, ["am_ela"]))
            {
                $LearningDeliveryFAM = $learningDelivery->addChild('LearningDeliveryFAM');    
                $LearningDeliveryFAM->addChild('LearnDelFAMType', 'ACT');
                $LearningDeliveryFAM->addChild('LearnDelFAMCode', '1');
                $LearningDeliveryFAM->addChild('LearnDelFAMDateFrom', $tr->start_date);
            }

        }
    }

    public function getValueFromTemplate($ilr, $LearningAimRef, $Field)
    {
        if($ilr != '')
        {
            $ilr = XML::loadSimpleXML($ilr);
            foreach($ilr->LearningDelivery as $delivery)
            {
                if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
                    foreach($delivery->LearningDeliveryFAM as $ldf)
                        if($ldf->LearnDelFAMType==$Field)
                            return $ldf->LearnDelFAMCode;

            }
        }
    }

    public function mapObPriorAttainToIlr($ob_pa)
    {
        $mapping = [
            1 => 2,
            2 => 4,
            3 => 6,
            7 => 1,
            9 => 1,
            10 => 7,
            11 => 8,
            12 => 9,
            13 => 10,
            99 => 99,
        ];

        return isset($mapping[$ob_pa]) ? $mapping[$ob_pa] : $ob_pa;
    }

}
