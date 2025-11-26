<?php
class edit_pool_organisation implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        $_SESSION['bc']->add($link, "do.php?_action=edit_pool_organisation&id=" . $id, "Add/Edit Pool Organisation");

        if($id == '')
        {
            $pool = new EmployerPool();
            $pool->organisation_type = Organisation::TYPE_EMPLOYER;

            $mainLocation = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool_locations");
            foreach($records AS $key => $value)
                $mainLocation->$value = null;
        }
        else
        {
            $pool = EmployerPool::loadFromDatabase($link, $id);
            $mainLocation = DAO::getObject($link, "SELECT * FROM pool_locations WHERE pool_id = '{$pool->id}' AND is_legal_address = '1'");
            if(is_null($mainLocation))
            {
                $mainLocation = new stdClass();
                $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool_locations");
                foreach($records AS $key => $value)
                    $mainLocation->$value = null;
            }
        }


        require_once('tpl_edit_pool_organisation.php');
    }
}