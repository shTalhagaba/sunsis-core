<?php
class view_employers implements IAction
{
    public function execute(PDO $link)
    {

	if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo" || DB_NAME == "am_demo")
	{
		//http_redirect('do.php?_action=view_all_organisations');
	}
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_employers", "View Employers");

	
        if(in_array(DB_NAME, ["am_duplex"]))
        {
            $view = ViewEmployersV2::getInstance($link);
            $view->refresh($link, $_REQUEST);
            require_once('tpl_view_employers_duplex.php');
        }
        else
        {
            $view = ViewGroupEmployers::getInstance($link);
            $view->refresh($link, $_REQUEST);
            require_once('tpl_view_employers.php');
        }

    }
}
?>