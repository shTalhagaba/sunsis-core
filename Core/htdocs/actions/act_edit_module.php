<?php
class edit_module implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_module&id=" . $id, "Add/ Edit Module");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        $acl = ACL::loadFromDatabase($link, 'contract', $id); /* @var $acl ACL */


        if($id == '')
        {
            // New record
            $vo = new Module();
        }
        else
        {
            $vo = Module::loadFromDatabase($link, $id);
        }

        // Dropdown arrays
        $providers = DAO::getResultSet($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type like '%3%';");

        include('tpl_edit_module.php');
    }
}
?>