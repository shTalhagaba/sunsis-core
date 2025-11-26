<?php
class manage_onefile_record extends ActionController
{
	public function indexAction(PDO $link)
	{
        $authorised = $_SESSION['user']->type != User::TYPE_LEARNER;
		if (!$authorised) 
        {
			throw new UnauthorizedException();
		}

        include_once('tpl_manage_onefile_record.php');
    }
}