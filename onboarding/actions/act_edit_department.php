<?php
class edit_department implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_department&id=" . $id, "Add/ Edit Department");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
        }

        if($id == '')
        {
            // New record
            $department = new Department();
            $department->active = 1;
            $department->organisation_type = Organisation::TYPE_DEPARTMENT;
            $mainLocation = new Location();
            $mainLocation->is_legal_address = 1;
        }
        else
        {
            $department = Department::loadFromDatabase($link, $id);
            $mainLocation = $department->getMainLocation($link);
        }

        $providers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = " . Organisation::TYPE_TRAINING_PROVIDER . " ORDER BY legal_name");

        // Presentation
        include('tpl_edit_department.php');
    }
}
?>