<?php
namespace Controllers;

use DAO;
use Helpers\Database;
use Helpers\Response;
use HttpRequest;
use SQLStatement;
use Validator;

class UserController
{
    private $link = null;

    public function __construct() 
    {
        $this->link = Database::getInstance()->getConnection();
    }

    public function index(HttpRequest $request, $id = null)
    {
        $requestData = $request->getQueryParams();
        $validationRules = [
            'UserType' => 'required,number',
        ];

        $validator = new Validator($requestData);
        if(!$validator->validate($validationRules))
        {
            Response::error($validator->errors(), 400);
        }
        $userType = $requestData['UserType'];

        $sql = new SQLStatement("SELECT id, firstnames, surname FROM users ORDER BY firstnames");
        $sql->setClause("WHERE active = 1");
        $sql->setClause("WHERE web_access = 1");
        $sql->setClause("WHERE type = '{$userType}'");

        if(!is_null($id))
        {
            $sql->setClause("WHERE id = '{$id}'");
        }

        $result = DAO::getResultset($this->link, $sql->__toString(), DAO::FETCH_ASSOC);
        
        Response::success($result, 'Users retrieved successfully');    
    }

    public function show(HttpRequest $request, $id)
    {
        return $this->index($request, $id);
    }
}
