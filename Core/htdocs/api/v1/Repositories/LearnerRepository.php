<?php
namespace Repositories;

use DAO;
use User;
use View;

class LearnerRepository 
{
    public function getAll($link, $searchString = [])
    {
        $sql = "SELECT * FROM users WHERE users.user_type = " . User::TYPE_LEARNER;

        $view = new View();
		$view->setSQL($sql);
        $view->refresh($link, $searchString);

        
    }
}