<?php
class ajax_lrs extends ActionController
{
    private $orgPassword;
    private $UKPRN;
    private $userName;
    private $certificate;
    private $wsdl_learner_service;
    private $wsdl_learner_service_r9;
    private $vendorID;
    private $language;


    public function indexAction(PDO $link)
    {
        require_once WEBROOT . '/lib/LRS/LearnerService/autoload.php';
        require_once WEBROOT . '/lib/LRS/LearnerServiceR9/autoload.php';
        $this->orgPassword = SystemConfig::getEntityValue($link, "lrsOrgPassword"); // "P8rsp8ctiveP3rsp";
        $this->userName = SystemConfig::getEntityValue($link, "lrsUsername"); //"TEST38";
        $this->UKPRN = SystemConfig::getEntityValue($link, "lrsOrgUkprn"); //"TEST0038";
        $this->certificate = WEBROOT . '/lib/LRS/ElaLrs.pem';
        $this->vendorID = '01';
        $this->language = 'ENG';
    }

    private function prepareGender($gender)
    {
        $lrs_value = null;
        switch($gender)
        {
            case 'Male':
            case 'M':
            case '1':
                $lrs_value = 1;
                break;
            case 'Female':
            case 'F':
            case '2':
                $lrs_value = 2;
                break;
            case 'Witheld':
            case 'W':
            case '9':
                $lrs_value = 9;
                break;
            case 'Unknown':
            case 'U':
            case '0':
                $lrs_value = 0;
                break;
            default:
                break;
        }
        return $lrs_value;
    }

    private function prepareDate($date)
    {
        if(trim($date) == '' || is_null($date))
        {
            return;
        }

        $date = new Date($date);
        return $date->format('Y-m-d');
    }

    public function getLearnerLearningEventsAction(PDO $link)
    {
        $this->indexAction($link);

        $this->wsdl_learner_service_r9 = WEBROOT . '/lib/LRS/LearnerServiceR9.wsdl';
        
        $response_ls = $this->learnerByDemographicsAction($link, false);

        if(isset($response_ls['SOAP_faultcode']))
        {
            echo json_encode($response_ls);
            return;
        }

        if(isset($response_ls['status']) && $response_ls['status'] == 'WSRC0004')
        {
            $learner = $response_ls['learner'][0];
            
            $invokingOrganisationR10 = new InvokingOrganisationR10();
            $invokingOrganisationR10->setUkprn($this->UKPRN);
            $invokingOrganisationR10->setUsername($_SESSION['user']->username);
            $invokingOrganisationR10->setPassword($this->orgPassword);
    
            $getLearnerLearningEvents = new GetLearnerLearningEvents(
                $invokingOrganisationR10,
                'SER',
                $this->vendorID,
                $this->language,
                $learner['ULN'],
                $learner['GivenName'],
                $learner['FamilyName'],
                $learner['DateOfBirth'],
                $learner['Gender'],
                'FULL'
            );

            $service = new LearnerServiceR9Proxy([
                'local_cert' => $this->certificate,
                'trace' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ], $this->wsdl_learner_service_r9);

            $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : null;

            try{
                $events = [];
                $response_s9 = $service->GetLearnerLearningEvents($getLearnerLearningEvents);
                $response_s9 = $response_s9->GetLearnerLearningEventsResult;
                $learningEvents = $response_s9->getLearnerRecord();
                if(count($learningEvents) == 0)
                {
                    $result = [
                        'status' => $response_s9->getResponseCode(),
                        'lrs_code_description' => 'No records found in PLR',
                    ];
                    echo json_encode($result);
                    return;
                }
                foreach($learningEvents AS $learningEvent)
                {
                    $events[] = [
                        'AchievementAwardDate' => $learningEvent->getAchievementAwardDate(),
                        'AchievementProviderName' => $learningEvent->getAchievementProviderName(),
                        'AchievementProviderUkprn' => $learningEvent->getAchievementProviderUkprn(),
                        'AwardingOrganisationName' => $learningEvent->getAwardingOrganisationName(),
                        'AwardingOrganisationUkprn' => $learningEvent->getAwardingOrganisationUkprn(),
                        'CollectionType' => $learningEvent->getCollectionType(),
                        'Credits' => $learningEvent->getCredits(),
                        'DateLoaded' => $learningEvent->getDateLoaded(),
                        'Grade' => $learningEvent->getGrade(),
                        'ID' => $learningEvent->getID(),
                        'LanguageForAssessment' => $learningEvent->getLanguageForAssessment(),
                        'Level' => $learningEvent->getLevel(),
                        'Restriction' => $learningEvent->getRestriction(),
                        'Source' => $learningEvent->getSource(),
                        'Status' => $learningEvent->getStatus(),
                        'Subject' => $learningEvent->getSubject(),
                        'SubjectCode' => $learningEvent->getSubjectCode(),
                        'UnderDataChallenge' => $learningEvent->getUnderDataChallenge(),
                        'ReturnNumber' => $learningEvent->getReturnNumber(),
                        'QualificationType' => $learningEvent->getQualificationType(),
                        'ParticipationStartDate' => $learningEvent->getParticipationStartDate(),
                        'ParticipationEndDate' => $learningEvent->getParticipationEndDate(),
                        
                        // for save hook method
                        'tr_id' => $tr_id,
                    ];
                }

                if(!is_null($tr_id) && count($events) > 0)
                    $this->saveLearnerLearningEvents($link, $tr_id, $events);

                $response_ls['events'] = $events;
                echo json_encode($response_ls);
                return;
            }
            catch (SoapFault $e) {
                $fault = [
                    'SOAP_faultcode' => $e->faultcode,
                    'SOAP_faultstring' => $e->faultstring,
                    'LRS_ErrorCode' => '',
                    'LRS_Description' => '',
                    'LRS_FurtherDetails' => '',
                ];
                if(isset($e->detail->MIAPAPIException))
                {
                    $miapException = $e->detail->MIAPAPIException;
                    $fault['LRS_ErrorCode'] = $miapException->ErrorCode;
                    $fault['LRS_Description'] = $miapException->Description;
                    $fault['LRS_FurtherDetails'] = $miapException->FurtherDetails;
                }
                echo json_encode($fault);
                return;
            }
        }

        echo json_encode($response_ls);
        return;
    }

    public function registerSingleLearnerAction(PDO $link)
    {
        $this->indexAction($link);

        $this->wsdl_learner_service = WEBROOT . '/lib/LRS/LearnerService.wsdl';
        
        $response_ls = $this->learnerByDemographicsAction($link, false);

        if(isset($response_ls['SOAP_faultcode']))
        {
            echo json_encode($response_ls);
            return;
        }

        if(isset($response_ls['status']) && $response_ls['status'] == 'WSRC0001')
        {
            if(isset($_POST['DateOfAddressCapture']) && Date::isDate($_POST['DateOfAddressCapture']))
            {
                $_POST['DateOfAddressCapture'] = Date::toMySQL($_POST['DateOfAddressCapture']);
            }
            if(isset($_POST['DateOfBirth']) && Date::isDate($_POST['DateOfBirth']))
            {
                $_POST['DateOfBirth'] = Date::toMySQL($_POST['DateOfBirth']);
            }

            $learnerToRegister = new LearnerToRegister($_POST);
            
            $registerSingleLearnerRequest = new RegisterSingleLearnerRqst();
            $registerSingleLearnerRequest->setLearner($learnerToRegister);
            $registerSingleLearnerRequest->setOrgPassword($this->orgPassword);
            $registerSingleLearnerRequest->setUKPRN($this->UKPRN);
            $registerSingleLearnerRequest->setUserName($this->userName);

            
            $service = new LearnerServiceProxy([
                'local_cert' => $this->certificate,
                'trace' => true,
            ], $this->wsdl_learner_service);
            
            try{
                $response = $service->registerSingleLearner($registerSingleLearnerRequest); /** @var response RegisterSingleLearnerResp */
                
                $result = [
                    'status' => $response->getResponseCode(),
                    'lrs_code_description' => $this->processResponse($response->getResponseCode()),
                    'uln' => $response->getULN(),
                ];

                echo json_encode($result);
                return;
            }
            catch (SoapFault $e) {
                $fault = [
                    'SOAP_faultcode' => $e->faultcode,
                    'SOAP_faultstring' => $e->faultstring,
                    'LRS_ErrorCode' => '',
                    'LRS_Description' => '',
                    'LRS_FurtherDetails' => '',
                ];
                if(isset($e->detail->MIAPAPIException))
                {
                    $miapException = $e->detail->MIAPAPIException;
                    $fault['LRS_ErrorCode'] = $miapException->ErrorCode;
                    $fault['LRS_Description'] = $miapException->Description;
                    $fault['LRS_FurtherDetails'] = $miapException->FurtherDetails;
                }
                echo json_encode($fault);
                return;
            }  


        }

        echo json_encode($response_ls);
        return;
    }

    public function learnerByULNAction(PDO $link)
    {
        $this->indexAction($link);

        $this->wsdl_learner_service = WEBROOT . '/lib/LRS/LearnerService.wsdl';

        $learnerByULNReqst = new LearnerByULNRqst([
            'OrgPassword' => $this->orgPassword,
            'UKPRN' => $this->UKPRN,
            'userName' => $this->userName,
            'FindType' => isset($_REQUEST['FindType']) ? $_REQUEST['FindType'] : null,
            'ULN' => isset($_REQUEST['ULN']) ? $_REQUEST['ULN'] : null,
            'FamilyName' => isset($_REQUEST['FamilyName']) ? $_REQUEST['FamilyName'] : null,
            'GivenName' => isset($_REQUEST['GivenName']) ? $_REQUEST['GivenName'] : null,
        ]);

        $service = new LearnerServiceProxy([
            'local_cert' => $this->certificate,
            'trace' => true,
        ], $this->wsdl_learner_service);

        $result = [
            'status' => '',
            'lrs_code_description' => '',
            'learners' => '',
            'learners_count' => 0,
        ];

        try{
            $matching_learners = [];
            $findLearnerResp = $service->learnerByULN($learnerByULNReqst); /* @var $findLearnerResp FindLearnerResp */
            if(is_array($findLearnerResp->getLearner()))
            {
                foreach($findLearnerResp->getLearner() AS $learner)
                {
                    $matching_learners[] = [
                        'CreatedDate' => $learner->getCreatedDate(),
                        'LastUpdatedDate' => $learner->getLastUpdatedDate(),
                        'ULN' => $learner->getULN(),
                        'MasterSubstituted' => $learner->getMasterSubstituted(),
                        'Title' => $learner->getTitle(),
                        'GivenName' => $learner->getGivenName(),
                        'MiddleOtherName' => $learner->getMiddleOtherName(),
                        'FamilyName' => $learner->getFamilyName(),
                        'PreferredGivenName' => $learner->getPreferredGivenName(),
                        'PreviousFamilyName' => $learner->getPreviousFamilyName(),
                        'FamilyNameAtAge16' => $learner->getFamilyNameAtAge16(),
                        'SchoolAtAge16' => $learner->getSchoolAtAge16(),
                        'LastKnownAddressLine1' => $learner->getLastKnownAddressLine1(),
                        'LastKnownAddressTown' => $learner->getLastKnownAddressLine2(),
                        'LastKnownAddressLine1' => $learner->getLastKnownAddressTown(),
                        'LastKnownPostCode' => $learner->getLastKnownPostCode(),
                        'DateOfAddressCapture' => $learner->getDateOfAddressCapture(),
                        'PlaceOfBirth' => $learner->getPlaceOfBirth(),
                        'Gender' => $learner->getGender(),
                        'DateOfBirth' => $learner->getDateOfBirth(),
                        'Nationality' => $learner->getNationality(),
                        'ScottishCandidateNumber' => $learner->getScottishCandidateNumber(),
                        'VerificationType' => $learner->getVerificationType(),
                        'OtherVerificationDescription' => $learner->getOtherVerificationDescription(),
                        'TierLevel' => $learner->getTierLevel(),
                        'AbilityToShare' => $learner->getAbilityToShare(),
                        'LearnerStatus' => $learner->getLearnerStatus(),
                        'LinkedULNs' => $learner->getLinkedULNs(),
                        'Notes' => $learner->getNotes(),
                        'VersionNumber' => $learner->getVersionNumber(),
                    ];
                }
            }

            $result = [
                'status' => $findLearnerResp->getResponseCode(),
                'lrs_code_description' => $this->processResponse($findLearnerResp->getResponseCode()),
                'learner' => $matching_learners,
                'learners_count' => count($matching_learners),
            ];
        }
        catch (SoapFault $e) {
            $fault = [
                'SOAP_faultcode' => $e->faultcode,
                'SOAP_faultstring' => $e->faultstring,
                'LRS_ErrorCode' => '',
                'LRS_Description' => '',
                'LRS_FurtherDetails' => '',
            ];
            if(isset($e->detail->MIAPAPIException))
            {
                $miapException = $e->detail->MIAPAPIException;
                $fault['LRS_ErrorCode'] = $miapException->ErrorCode;
                $fault['LRS_Description'] = $miapException->Description;
                $fault['LRS_FurtherDetails'] = $miapException->FurtherDetails;
            }

            echo json_encode($fault);
            return;
        }

        echo json_encode($result);
    }

    public function learnerByDemographicsAction(PDO $link, $json = true)
    {
        $this->indexAction($link);

        $this->wsdl_learner_service = WEBROOT . '/lib/LRS/LearnerService.wsdl';

        $learnerByDemographicsRqst = new LearnerByDemographicsRqst([
            'OrgPassword' => $this->orgPassword,
            'UKPRN' => $this->UKPRN,
            'userName' => $this->userName,
            'FindType' => isset($_REQUEST['FindType']) ? $_REQUEST['FindType'] : 'FUL',
            'FamilyName' => isset($_REQUEST['FamilyName']) ? $_REQUEST['FamilyName'] : null,
            'GivenName' => isset($_REQUEST['GivenName']) ? $_REQUEST['GivenName'] : null,
            'DateOfBirth' => isset($_REQUEST['DateOfBirth']) ? $this->prepareDate($_REQUEST['DateOfBirth']) : null,
            'Gender' => isset($_REQUEST['Gender']) ? $this->prepareGender($_REQUEST['Gender']) : null,
            'LastKnownPostCode' => isset($_REQUEST['LastKnownPostCode']) ? $_REQUEST['LastKnownPostCode'] : null,
            'PreviousFamilyName' => isset($_REQUEST['PreviousFamilyName']) ? $_REQUEST['PreviousFamilyName'] : null,
            'SchoolAtAge16' => isset($_REQUEST['SchoolAtAge16']) ? $_REQUEST['SchoolAtAge16'] : null,
            'EmailAddress' => isset($_REQUEST['EmailAddress']) ? $_REQUEST['EmailAddress'] : null,
        ]);

        $service = new LearnerServiceProxy([
            'local_cert' => $this->certificate,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ], $this->wsdl_learner_service);

        $result = [
            'status' => '',
            'lrs_code_description' => '',
            'learners' => '',
            'learners_count' => 0,
        ];
        try{
            $matching_learners = [];
            $findLearnerResp = $service->learnerByDemographics($learnerByDemographicsRqst); /* @var $findLearnerResp FindLearnerResp */
            if(is_array($findLearnerResp->getLearner()))
            {
                foreach($findLearnerResp->getLearner() AS $learner)
                {
                    $matching_learners[] = [
                        'CreatedDate' => $learner->getCreatedDate(),
                        'LastUpdatedDate' => $learner->getLastUpdatedDate(),
                        'ULN' => $learner->getULN(),
                        'MasterSubstituted' => $learner->getMasterSubstituted(),
                        'Title' => $learner->getTitle(),
                        'GivenName' => $learner->getGivenName(),
                        'MiddleOtherName' => $learner->getMiddleOtherName(),
                        'FamilyName' => $learner->getFamilyName(),
                        'PreferredGivenName' => $learner->getPreferredGivenName(),
                        'PreviousFamilyName' => $learner->getPreviousFamilyName(),
                        'FamilyNameAtAge16' => $learner->getFamilyNameAtAge16(),
                        'SchoolAtAge16' => $learner->getSchoolAtAge16(),
                        'LastKnownAddressLine1' => $learner->getLastKnownAddressLine1(),
                        'LastKnownAddressTown' => $learner->getLastKnownAddressLine2(),
                        'LastKnownAddressLine1' => $learner->getLastKnownAddressTown(),
                        'LastKnownPostCode' => $learner->getLastKnownPostCode(),
                        'DateOfAddressCapture' => $learner->getDateOfAddressCapture(),
                        'PlaceOfBirth' => $learner->getPlaceOfBirth(),
                        'Gender' => $learner->getGender(),
                        'DateOfBirth' => $learner->getDateOfBirth(),
                        'Nationality' => $learner->getNationality(),
                        'ScottishCandidateNumber' => $learner->getScottishCandidateNumber(),
                        'VerificationType' => $learner->getVerificationType(),
                        'OtherVerificationDescription' => $learner->getOtherVerificationDescription(),
                        'TierLevel' => $learner->getTierLevel(),
                        'AbilityToShare' => $learner->getAbilityToShare(),
                        'LearnerStatus' => $learner->getLearnerStatus(),
                        'LinkedULNs' => $learner->getLinkedULNs(),
                        'Notes' => $learner->getNotes(),
                        'VersionNumber' => $learner->getVersionNumber(),
                    ];
                }
            }

            $result = [
                'status' => $findLearnerResp->getResponseCode(),
                'lrs_code_description' => $this->processResponse($findLearnerResp->getResponseCode()),
                'learner' => $matching_learners,
                'learners_count' => count($matching_learners),
            ];
        }
        catch (SoapFault $e) {
            $fault = [
                'SOAP_faultcode' => $e->faultcode,
                'SOAP_faultstring' => $e->faultstring,
                'LRS_ErrorCode' => '',
                'LRS_Description' => '',
                'LRS_FurtherDetails' => '',
            ];
            if(isset($e->detail->MIAPAPIException))
            {
                $miapException = $e->detail->MIAPAPIException;
                $fault['LRS_ErrorCode'] = $miapException->ErrorCode;
                $fault['LRS_Description'] = $miapException->Description;
                $fault['LRS_FurtherDetails'] = $miapException->FurtherDetails;
            }

            if(!$json)
            {
                return $fault;
            }
            echo json_encode($fault);
            return;
        }

        if(!$json)
        {
            return $result;
        }
        echo json_encode($result);
    }

    public function processResponse($response)
    {
        if($response == "WSRC0001")
        {
            return "No Match";
        }
        if($response == "WSRC0003")
        {
            return "Possible Matches";
        }
        if($response == "WSRC0004")
        {
            return "Exact Match";
        }
        if($response == "WSRC0022")
        {
            return "Linked learner";
        }
        if($response == "WSEC0206")
        {
            return "(Learner has not opted to share data) - no Learning Events returned";
        }
        if($response == "WSEC0208")
        {
            return "Learner could not be verified";
        }
        if($response == "WSEC0136")
        {
            return "Supplied ULN is invalid";
        }
        if($response == "WSRC0005")
        {
            return "Learner successfully registered";
        }
        if($response == "WSRC0021")
        {
            return "Learner could not be registered";
        }
        if($response == "WSEC0001")
        {
            return "The request was badly formed (XSD validation error) - this may include missing mandatory parameters, or invalid data types, including invalid or prohibited values in certain fields.";
        }
    }

    // hook method to save the learning events to save time
    private function saveLearnerLearningEvents(PDO $link, $tr_id, $events)
    {
        DAO::execute($link, "DELETE FROM lrs_learner_learning_events WHERE tr_id = '{$tr_id}'");
        DAO::multipleRowInsert($link, "lrs_learner_learning_events", $events);
    }
}