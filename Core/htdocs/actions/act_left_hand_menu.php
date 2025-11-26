<?php

class left_hand_menu implements IAction
{
    public function execute(PDO $link)
    {
        if (DB_NAME == 'am_duplex')
        {
            require_once('tpl_left_hand_menu_duplex.php');
        }
	elseif (DB_NAME == 'am_presentation')
        {
            require_once('tpl_left_hand_menu_presentation.php');
        }
        else
        {
            require_once('tpl_left_hand_menu.php');
        }
    }
}

?>