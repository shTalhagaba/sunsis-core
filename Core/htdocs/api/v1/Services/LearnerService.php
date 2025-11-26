<?php
namespace Services;

use Contract;
use Course;
use DAO;
use Date;
use DOMDocument;
use Exception;
use Framework;
use Location;
use Organisation;
use PasswordUtilities;
use PDO;
use TrainingRecord;
use User;
use XML;

class LearnerService
{
    public function createAndEnrolLearner(PDO $link, array $data)
    {
        $link->beginTransaction();
        try 
        {
            $data['ProviderID'] = !empty($data['ProviderID']) ? $data['ProviderID'] : DAO::getSingleValue($link, "SELECT organisations_id FROM locations WHERE id = '{$data['ProviderLocationID']}'");
            $data['EmployerID'] = !empty($data['EmployerID']) ? $data['EmployerID'] : DAO::getSingleValue($link, "SELECT organisations_id FROM locations WHERE id = '{$data['EmployerLocationID']}'");
    
            $learner = $this->createLearner($link, $data);
            $tr = $this->enrolLearner($link, $learner, $data);

            $link->commit();
        }
        catch(Exception $ex)
        {
            $link->rollback();
            throw new Exception($ex);
        }

        return [
            'SunesisLearnerID' => $learner->id, 
            'SunesisTrainingID' => $tr->id,
            'CourseID' => $data['CourseID'],
            'ProviderID' => $data['ProviderID'],
            'ProviderLocationID' => $data['ProviderLocationID'],
            'EmployerID' => $data['EmployerID'],
            'EmployerLocationID' => $data['EmployerLocationID'],
            'AssessorID' => isset($data['AssessorID']) ? $data['AssessorID'] : null,
            'TutorID' => isset($data['TutorID']) ? $data['TutorID'] : null,
            'VerifierID' => isset($data['VerifierID']) ? $data['VerifierID'] : null,
        ];
    }

    private function createLearner(PDO $link, array $data)
    {
        $learner = new User();
        $learnerFields = [
            'firstnames' => 'GivenNames',
            'surname' => 'FamilyName',
            'gender' => 'Gender',
            'dob' => 'DateOfBirth',
            'home_address_line_1' => 'HomeAddressLine1',
            'home_address_line_2' => 'HomeAddressLine2',
            'home_address_line_3' => 'HomeAddressLine3',
            'home_address_line_4' => 'HomeAddressLine4',
            'home_postcode' => 'HomePostcode',
            'home_mobile' => 'HomeMobile',
            'home_email' => 'HomeEmail',
            'home_telephone' => 'HomeTelephone',
            'work_address_line_1' => 'WorkAddressLine1',
            'work_address_line_2' => 'WorkAddressLine2',
            'work_address_line_3' => 'WorkAddressLine3',
            'work_address_line_4' => 'WorkAddressLine4',
            'work_postcode' => 'WorkPostcode',
            'work_mobile' => 'WorkMobile',
            'work_email' => 'WorkEmail',
            'work_telephone' => 'WorkTelephone',
            'ethnicity' => 'Ethnicity',
            'ni' => 'NationalInsurance',
            'uln' => 'ULN',
        ];
        foreach ($learnerFields as $sunesisField => $apiField) 
        {
            $learner->$sunesisField = isset($data[$apiField]) ? trim($data[$apiField] ?? '') : null;
        }

        $learner->username = $this->generateUsername($link, $learner->firstnames, $learner->surname);
        $learner->type = User::TYPE_LEARNER;
        $learner->web_access = 0;
        $learner->password = PasswordUtilities::generateDatePassword();
        $learner->pwd_sha1 = sha1($learner->password);
        $learner->employer_id = $data['EmployerID'];
        $learner->employer_location_id = $data['EmployerLocationID'];
        $learner->ni = strtoupper(str_replace(' ', '', $learner->ni ?? ''));
        $learner->home_postcode = strtoupper($learner->home_postcode ?? '');
        $learner->work_postcode = strtoupper($learner->work_postcode ?? '');

        $learner->save($link);

        return $learner;
    }

    private function enrolLearner(PDO $link, User $learner, $data)
    {
        $course = Course::loadFromDatabase($link, $data['CourseID']);
        $programme = Framework::loadFromDatabase($link, $course->framework_id);
        $employer = Organisation::loadFromDatabase($link, $data['EmployerID']);
        $employerLocation = Location::loadFromDatabase($link, $data['EmployerLocationID']);
        $provider = Organisation::loadFromDatabase($link, $data['ProviderID']);
        $providerLocation = Location::loadFromDatabase($link, $data['ProviderLocationID']);
        $contract = Contract::loadFromDatabase($link, $data['ContractID']);
        $assessor = (isset($data['AssessorID']) && !empty($data['AssessorID'])) ?
            User::loadFromDatabaseById($link, $data['AssessorID']) : null;
        $tutor = (isset($data['TutorID']) && !empty($data['TutorID'])) ?
            User::loadFromDatabaseById($link, $data['TutorID']) : null;
        $verifier = (isset($data['VerifierID']) && !empty($data['VerifierID'])) ?
            User::loadFromDatabaseById($link, $data['VerifierID']) : null;

        $tr = new TrainingRecord();
        $tr->populate($learner, true);
        $tr->id = NULL;
        $tr->contract_id = $contract->id;
        $tr->start_date = $data["TrainingStartDate"];
        $tr->target_date = $data["TrainingPlannedEndDate"];
        $tr->start_date_inc_epa = $data["TrainingStartDate"];
        $tr->status_code = 1; // Continuing
        $tr->employer_id = $learner->employer_id;
        $tr->employer_location_id = $learner->employer_location_id;
        $tr->work_address_line_1 = $employerLocation->address_line_1;
        $tr->work_address_line_2 = $employerLocation->address_line_2;
        $tr->work_address_line_3 = $employerLocation->address_line_3;
        $tr->work_address_line_4 = $employerLocation->address_line_4;
        $tr->work_postcode = $employerLocation->postcode;
        $tr->provider_id = $provider->id;
        $tr->provider_location_id = $providerLocation->id;
        $tr->provider_address_line_1 = $providerLocation->address_line_1;
        $tr->provider_address_line_2 = $providerLocation->address_line_2;
        $tr->provider_address_line_3 = $providerLocation->address_line_3;
        $tr->provider_address_line_4 = $providerLocation->address_line_4;
        $tr->provider_postcode = $providerLocation->postcode;
        $tr->provider_telephone = $providerLocation->telephone;
        $tr->ethnicity = $learner->ethnicity;
        $tr->work_experience = 0;
        $tr->assessor = !is_null($assessor)? $assessor->id : null;
        // $tr->assessor = $assessor->id;
        $tr->tutor = !is_null($tutor) ? $tutor->id : null;
        $tr->verifier = !is_null($verifier) ? $verifier->id : null;
        $tr->l36 = 0;
        $tr->current_postcode = $learner->home_postcode;
        if($programme->epa_duration != '')
        {
            $_epaDate = new Date($tr->start_date);
            $_epaDate->addMonths($programme->duration_in_months);
            $_epaDate->addMonths($programme->epa_duration);
            $tr->planned_epa_date = $_epaDate->formatMySQL();
        }
        $l03 = DAO::getSingleValue($link, "SELECT l03 FROM tr WHERE username = '{$learner->username}' LIMIT 0,1");
        if ($l03 == '') 
        {
            $l03 = (int)DAO::getSingleValue($link, "SELECT MAX(l03) FROM tr WHERE l03 + 0 <> 0 AND LENGTH(RTRIM(l03)) = 12");
            $l03 += 1;
            $tr->l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
            $l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
        } 
        else 
        {
            $tr->l03 = $l03;
        }

        $tr->save($link);

        $coursesTr = (object)[
            'course_id' => $course->id,
            'tr_id' => $tr->id,
            'qualification_id' => 0,
            'framework_id' => $programme->id,
        ];
        DAO::saveObjectToTable($link, 'courses_tr', $coursesTr);

        $studentFramework = (object)[
            'title' => $programme->title,
            'id' => $programme->id,
            'tr_id' => $tr->id,
            'sector' => $programme->framework_code,
            'comments' => $programme->comments,
            'duration_in_months' => $programme->duration_in_months,
        ];
        DAO::saveObjectToTable($link, 'student_frameworks', $studentFramework);

        $frameQuals = DAO::getResultset($link, "SELECT * FROM framework_qualifications WHERE framework_id = '{$programme->id}' ORDER BY sequence", DAO::FETCH_ASSOC);
        foreach($frameQuals AS $fQualification)
        {
            $startDate = new Date($tr->start_date);
            $plannedEndDate = new Date($tr->target_date);
            if(isset($fQualification['offset_months']) && $fQualification['offset_months'] != '')
            {
                $startDate->addMonths($fQualification['offset_months']);

                if(isset($fQualification['duration_in_months']) && $fQualification['duration_in_months'] != '')
                {
                    $plannedEndDate = new Date($startDate->formatMySQL());
                    $plannedEndDate->addMonths($fQualification['duration_in_months']);
                }
            }
            $studentQualification = (object)[
                'tr_id' => $tr->id,
                'auto_id' => null,
                'qual_sequence' => $fQualification['sequence'],
                'id' => $fQualification['id'],
                'lsc_learning_aim' => $fQualification['lsc_learning_aim'],
                'awarding_body' => $fQualification['awarding_body'],
                'title' => $fQualification['title'],
                'description' => $fQualification['description'],
                'assessment_method' => $fQualification['assessment_method'],
                'structure' => $fQualification['structure'],
                'level' => $fQualification['level'],
                'qualification_type' => $fQualification['qualification_type'],
                'accreditation_start_date' => $fQualification['accreditation_start_date'],
                'operational_centre_start_date' => $fQualification['operational_centre_start_date'],
                'accreditation_end_date' => $fQualification['accreditation_end_date'],
                'certification_end_date' => $fQualification['certification_end_date'],
                'dfes_approval_start_date' => $fQualification['dfes_approval_start_date'],
                'dfes_approval_end_date' => $fQualification['dfes_approval_end_date'],
                'framework_id' => $fQualification['framework_id'],
                'evidences' => $fQualification['evidences'],
                'units' => $fQualification['units'],
                'internaltitle' => $fQualification['internaltitle'],
                'proportion' => $fQualification['proportion'],
                'units_required' => $fQualification['units_required'],
                'start_date' => $startDate->formatMySQL(),
                'end_date' => $plannedEndDate->formatMySQL(),
                'username' => $tr->username,
                'trading_name' => 0,
            ];
            DAO::saveObjectToTable($link, 'student_qualifications', $studentQualification);
        }

        $submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() AND contract_year = '{$contract->contract_year}' AND contract_type = '$contract->funding_body' ORDER BY last_submission_date LIMIT 1;");
        if ($submission == "")
            $submission = "W13";

        $ilr = XML::loadSimpleXML("<Learner></Learner>");
        if ($tr->l03 != '')
            $ilr->addChild("LearnRefNumber", trim($tr->l03 ?? ''));
        if ($learner->uln != '')
            $ilr->addChild("ULN", trim($learner->uln ?? ''));
        if ($tr->surname != '')
            $ilr->addChild("FamilyName", trim($tr->surname ?? ''));
        if ($tr->firstnames != '')
            $ilr->addChild("GivenNames", trim($tr->firstnames ?? ''));
        if ($tr->dob != '')
            $ilr->addChild("DateOfBirth", trim($tr->dob ?? ''));
        if ($tr->ethnicity != '')
            $ilr->addChild("Ethnicity", trim($tr->ethnicity ?? ''));
        if ($tr->gender != '')
            $ilr->addChild("Sex", trim($tr->gender ?? ''));
        if ($tr->ni != '') 
        {
            $ilr->addChild("NINumber", trim($tr->ni ?? ''));
        }
        if (trim($tr->otj_hours ?? '') != '' && intval($tr->otj_hours ?? '') > 0) 
        {
            $ilr->addChild("OTJHours", trim($tr->otj_hours ?? ''));
        }

        if ($tr->home_postcode != '') 
        {
            $learnerContact = $ilr->addChild("LearnerContact");
            $learnerContact->addChild("LocType", "2");
            $learnerContact->addChild("ContType", "1");
            $learnerContact->addChild("PostCode", trim($tr->home_postcode ?? ''));
        }
        if ($tr->home_address_line_1 != '' || $tr->home_address_line_2 != '' || $tr->home_address_line_3 != '' || $tr->home_address_line_4 != '') 
        {
            $learnerContact = $ilr->addChild("LearnerContact");
            $learnerContact->addChild("LocType", "1");
            $learnerContact->addChild("ContType", "2");
            $postAdd = $learnerContact->addChild("PostAdd");
            if ($tr->home_address_line_1 != '')
                $postAdd->addChild("AddLine1", trim($tr->home_address_line_1 ?? ''));
            if ($tr->home_address_line_2 != '')
                $postAdd->addChild("AddLine2", trim($tr->home_address_line_2 ?? ''));
            if ($tr->home_address_line_3 != '')
                $postAdd->addChild("AddLine3", trim($tr->home_address_line_3 ?? ''));
            if ($tr->home_address_line_4 != '')
                $postAdd->addChild("AddLine4", trim($tr->home_address_line_4 ?? ''));
        }
        if ($tr->home_postcode != '') 
        {
            $learnerContact = $ilr->addChild("LearnerContact");
            $learnerContact->addChild("LocType", "2");
            $learnerContact->addChild("ContType", "2");
            $learnerContact->addChild("PostCode", trim($tr->home_postcode ?? ''));
        }
        if ($tr->home_telephone != '') 
        {
            $learnerContact = $ilr->addChild("LearnerContact");
            $learnerContact->addChild("LocType", "3");
            $learnerContact->addChild("ContType", "2");
            $learnerContact->addChild("PostCode", trim($tr->home_telephone ?? ''));
        }
        if ($tr->home_email != '') 
        {
            $learnerContact = $ilr->addChild("LearnerContact");
            $learnerContact->addChild("LocType", "4");
            $learnerContact->addChild("ContType", "2");
            $learnerContact->addChild("PostCode", trim($tr->home_email ?? ''));
        }

        $LearnerEmploymentStatus = $ilr->addChild('LearnerEmploymentStatus');
        $LearnerEmploymentStatus->EmpStat = 10;
        $LearnerEmploymentStatus->DateEmpStatApp = $tr->start_date;
        $LearnerEmploymentStatus->EmpId = DAO::getSingleValue($link, "SELECT edrs FROM organisations WHERE id = '{$tr->employer_id}'");

        $this->addDeliveries($link, $tr, $course, $programme, $contract, $ilr, $tr);

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadXML($ilr->saveXML());
        $dom->formatOutput = TRUE;
        $modified_ilr = $dom->saveXml();
        $modified_ilr = str_replace('<?xml version="1.0"?>', '', $modified_ilr);

        $ilr_entry = (object)[
            'L03' => $tr->l03,
            'ilr' => $modified_ilr,
            'submission' => $submission,
            'contract_type' => $contract->contract_type != '' ? $contract->contract_type : 0,
            'tr_id' => $tr->id,
            'is_active' => 1,
            'contract_id' => $contract->id,
        ];

        DAO::saveObjectToTable($link, "ilr", $ilr_entry);

        return $tr;
    }

    private function addDeliveries(PDO $link, $tr, $course, $framework, $contract, &$ilr)
    {
        $AimSeqNumber = 1;
        if (in_array($course->programme_type, [2, 7])) 
        {
            $learningDelivery = $ilr->addChild("LearningDelivery");

            $this->addDelivery($link, $tr, $course, $framework, $contract, $learningDelivery, "ZPROG001", $AimSeqNumber);            
        }

        $result = DAO::getResultset($link, "SELECT DISTINCT student_qualifications.id, student_qualifications.start_date, student_qualifications.end_date, student_qualifications.qualification_type, student_qualifications.qual_exempt FROM student_qualifications INNER JOIN framework_qualifications ON student_qualifications.framework_id = framework_qualifications.framework_id WHERE tr_id = '{$tr->id}' ORDER BY qual_sequence", DAO::FETCH_ASSOC);
        foreach ($result as $row_sub) 
        {
            $aimReference = str_replace("/", "", $row_sub['id']);
            $learningDelivery = $ilr->addChild("LearningDelivery");

            $this->addDelivery($link, $tr, $course, $framework, $contract, $learningDelivery, $aimReference, ++$AimSeqNumber);            

        }
    }

    private function addDelivery(PDO $link, $tr, $course, $framework, $contract, &$learningDelivery, $learnAimRef, $AimSeqNumber)
    {
        $learningDelivery->addChild("LearnAimRef", str_replace("/", "", $learnAimRef));
        if($learnAimRef == "ZPROG001")
        {
            $learningDelivery->addChild("AimType", "1");
        }
        else
        {
            if ($course->programme_type == '2')
                $learningDelivery->addChild("AimType", "3");
            elseif ($course->programme_type == '7')
                $learningDelivery->addChild("AimType", "5");
            else
                $learningDelivery->addChild("AimType", "4");
        }
        $learningDelivery->addChild("AimSeqNumber", $AimSeqNumber);
        $learningDelivery->addChild("LearnStartDate", $tr->start_date);
        $learningDelivery->addChild("LearnPlanEndDate", $tr->target_date);
        if ($course->programme_type == '1' || $course->programme_type == '2')
            $learningDelivery->addChild("FundModel", "36");
        elseif ($course->programme_type == '3')
            $learningDelivery->addChild("FundModel", "21");
        elseif ($course->programme_type == '4')
            $learningDelivery->addChild("FundModel", "22");
        elseif ($course->programme_type == '5')
            $learningDelivery->addChild("FundModel", "70");
        elseif ($course->programme_type == '6')
            $learningDelivery->addChild("FundModel", "10");
        elseif ($course->programme_type == '7')
            $learningDelivery->addChild("FundModel", "25");
        if (trim($framework->PwayCode ?? '') != '')
            $learningDelivery->addChild("PwayCode", $framework->PwayCode);
        if (trim($framework->StandardCode ?? '') != '')
            $learningDelivery->addChild("StdCode", $framework->StandardCode);

        $learningDelivery->addChild("ProgType", $framework->framework_type);

        $ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '$tr->provider_id' AND ukprn NOT IN (SELECT ukprn FROM organisations WHERE organisation_type = '1')");
        if ($ukprn != '')
            $learningDelivery->addChild("PartnerUKPRN", $ukprn);
        if ($tr->work_postcode != '')
            $learningDelivery->addChild("DelLocPostCode", $tr->work_postcode);
        $learningDelivery->addChild("PropFundRemain", "100");
        $learningDelivery->addChild("CompStatus", "1");
        $SOF = $this->getValueFromTemplate($contract->template, $learnAimRef, "SOF");
        $FFI = $this->getValueFromTemplate($contract->template, $learnAimRef, "FFI");
        if ($SOF != '') 
        {
            $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
            $LearningDeliveryFAM->addChild("LearnDelFAMType", "SOF");
            $LearningDeliveryFAM->addChild("LearnDelFAMCode", $SOF);
        }
        if ($FFI != '') 
        {
            $LearningDeliveryFAM = $learningDelivery->addChild("LearningDeliveryFAM");
            $LearningDeliveryFAM->addChild("LearnDelFAMType", "FFI");
            $LearningDeliveryFAM->addChild("LearnDelFAMCode", $FFI);
        }
    }

    private function generateUsername(PDO $link, $firstnames, $surname)
    {
        $i = 1;
        do {
            $username = strtolower(substr(substr($firstnames, 0, 1) . $surname, 0, 20));
            $username = str_replace(' ', '', $username);
            $username = str_replace("'", '', $username);
            $username = str_replace('"', '', $username);
            $username = $username . $i;
            $i++;
        } while ((int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE username = '$username'") > 0);

        if ($username == '' || is_null($username))
        {
            $username = strtolower(substr(substr($firstnames, 0, 1) . $surname, 0, 15)) . date('is');
        }
        return strtolower($username);
    }

    public function getValueFromTemplate($ilr, $LearningAimRef, $Field)
    {
        if($ilr == '')
        {
            return '';
        }
        
        $ilr = XML::loadSimpleXML($ilr);
        foreach ($ilr->LearningDelivery as $delivery) 
        {
            if (("" . $delivery->LearnAimRef) == $LearningAimRef || ("" . $delivery->LearnAimRef) == '')
            {
                foreach ($delivery->LearningDeliveryFAM as $ldf)
                {
                    if ($ldf->LearnDelFAMType == $Field)
                    {
                        return $ldf->LearnDelFAMCode;
                    }
                }
            }
        }
    }
}