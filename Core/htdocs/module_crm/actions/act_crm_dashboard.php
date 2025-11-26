<?php
class crm_dashboard implements IAction
{
	public function execute(PDO $link)
	{
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=crm_dashboard", "CRM Dashboard");

        include_once('tpl_crm_dashboard.php');
    }
}