<?php
namespace Controllers;

use DAO;
use Helpers\Database;
use Helpers\Response;
use HttpRequest;
use Services\LearnerService;
use User;
use Validator;

class LearnerController
{
    private $link = null;

    public function __construct() 
    {
        $this->link = Database::getInstance()->getConnection();
    }

    public function index(HttpRequest $request)
    {
        $learners = DAO::getResultset($this->link, "SELECT id, firstnames, home_postcode FROM users WHERE type = " . User::TYPE_LEARNER . " LIMIT 5", DAO::FETCH_ASSOC);
        if(count($learners) == 0)
        {
            Response::error('No learners found', 404);
        }
        else
        {
            Response::success($learners, 'Learners retrieved successfully');
        }
    }

    public function show(HttpRequest $request, $id)
    {
        $learner = User::loadFromDatabaseById($this->link, $id);
        $data = [];
        if (!isset($learner->id)) 
        {
            Response::error('Learner not found', 404);
        } 
        else 
        {
            $data[] = $learner;
            Response::success($data, 'Learner retrieved successfully');
        }
    }

    public function store(HttpRequest $request)
    {
        $requestData = $request->getBodyParams();
        $validationRules = [
            #'ProviderID' => 'required,number',
            'ProviderLocationID' => 'required,number',
            #'EmployerID' => 'required,number',
            'EmployerLocationID' => 'required,number',
            #'ProgrammeID' => 'required,number',
            'CourseID' => 'required,number',
            'ContractID' => 'required,number',
            'GivenNames' => 'required',
            'FamilyName' => 'required',
            'AssessorID' => 'number',
            'TutorID' => 'number',
            'VerifierID' => 'number',
            'TrainingStartDate' => 'required',
            'TrainingPlannedEndDate' => 'required',
        ];

        $validator = new Validator($requestData);
        if(!$validator->validate($validationRules))
        {
            Response::error($validator->errors(), 400);
        }

        $learnerService = new LearnerService();        
        $result = $learnerService->createAndEnrolLearner($this->link, $requestData);
        $SunesisLearnerID = $result['SunesisLearnerID'];
        $SunesisTrainingID = $result['SunesisTrainingID'];
        if(!empty($SunesisLearnerID) && !empty($SunesisTrainingID))
        {
            Response::success($result, 'Learner is created and enrolled successfully');
        }
        else
        {
            Response::error('Something went wrong', 500);
        }
    }
}
