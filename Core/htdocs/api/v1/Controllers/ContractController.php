<?php
namespace Controllers;

use Contract;
use DAO;
use Helpers\Database;
use Helpers\Response;
use HttpRequest;
use SQLStatement;
use Validator;

class ContractController
{
    private $link = null;

    public function __construct() 
    {
        $this->link = Database::getInstance()->getConnection();
    }

    public function index(HttpRequest $request)
    {
        $requestData = $request->getQueryParams();
        $validationRules = [
            'ContractYear' => 'number',
        ];

        $validator = new Validator($requestData);
        if(!$validator->validate($validationRules))
        {
            Response::error($validator->errors(), 400);
        }

        $sql = new SQLStatement("SELECT id, title FROM contracts ORDER BY contract_year DESC, title");
        $sql->setClause("WHERE active = 1");
        if(!empty($requestData['ContractYear']))
        {
            $sql->setClause("WHERE contract_year = '{$requestData['ContractYear']}'");
        }

        $result = DAO::getResultset($this->link, $sql->__toString(), DAO::FETCH_ASSOC);
        
        Response::success($result, 'Contracts retrieved successfully');    
    }

    public function show(HttpRequest $request, $id)
    {
        $contract = Contract::loadFromDatabase($this->link, $id);
        $data = [];
        if (!isset($contract->id)) 
        {
            Response::error('contract not found', 404);
        } 
        else 
        {
            $data[] = $contract;
            Response::success($data, 'contract retrieved successfully');
        }
    }
}
