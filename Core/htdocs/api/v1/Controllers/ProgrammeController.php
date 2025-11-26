<?php
namespace Controllers;

use DAO;
use Framework;
use Helpers\Database;
use Helpers\Response;
use HttpRequest;

class ProgrammeController
{
    private $link = null;

    public function __construct() 
    {
        $this->link = Database::getInstance()->getConnection();
    }

    public function index(HttpRequest $request)
    {
        $result = DAO::getResultset($this->link, "SELECT id, title FROM frameworks WHERE active = 1 ORDER BY title", DAO::FETCH_ASSOC);
        
        Response::success($result, 'Programmes retrieved successfully');    
    }

    public function show(HttpRequest $request, $id)
    {
        $programme = Framework::loadFromDatabase($this->link, $id);
        $data = [];
        if (!isset($programme->id)) 
        {
            Response::error('Programme not found', 404);
        } 
        else 
        {
            $data[] = $programme;
            Response::success($data, 'Programme retrieved successfully');
        }
    }
}
