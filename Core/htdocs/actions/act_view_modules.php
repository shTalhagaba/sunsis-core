<?php
class view_modules implements IAction
{
    public function execute(PDO $link)
    {
	    $_SESSION['bc']->index=0;
	    $_SESSION['bc']->add($link, "do.php?_action=view_modules", "View Modules");

	    if(SystemConfig::getEntityValue($link, "attendance_module_v2"))
	    {
		    $view = ViewAttendanceModules::getInstance();
		    $view->refresh($link, $_REQUEST);
		    require_once('tpl_view_attendance_modules.php');
	    }
	    else
	    {
		    $view = ViewModules::getInstance();
		    $view->refresh($link, $_REQUEST);
		    require_once('tpl_view_modules.php');
	    }
    }
}
?>