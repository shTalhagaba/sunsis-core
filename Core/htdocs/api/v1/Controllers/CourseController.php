<?php
namespace Controllers;

use Course;
use DAO;
use Helpers\Database;
use Helpers\Response;
use HttpRequest;

class CourseController
{
    private $link = null;

    public function __construct() 
    {
        $this->link = Database::getInstance()->getConnection();
    }

    public function index(HttpRequest $request)
    {
        $result = DAO::getResultset($this->link, "SELECT id, title FROM courses WHERE active = 1 ORDER BY title", DAO::FETCH_ASSOC);
        
        Response::success($result, 'Courses retrieved successfully');    
    }

    public function show(HttpRequest $request, $id)
    {
        $coruse = Course::loadFromDatabase($this->link, $id);
        $data = [];
        if (!isset($coruse->id)) 
        {
            Response::error('coruse not found', 404);
        } 
        else 
        {
            $data[] = $coruse;
            Response::success($data, 'coruse retrieved successfully');
        }
    }
}
