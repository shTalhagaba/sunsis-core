<?php
class edit_systemowner implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_systemowner&id=" . $id, "Add/ Edit System Owner");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
        }

        if($id == '')
        {
            // New record
            $so = new SystemOwner();
            $so->active = 1;
            $so->organisation_type = Organisation::TYPE_CLIENT;
            $mainLocation = new Location();
            $mainLocation->is_legal_address = 1;
        }
        else
        {
            $so = SystemOwner::loadFromDatabase($link);
            $mainLocation = $so->getMainLocation($link);
        }

        $L46_dropdown = "SELECT DISTINCT UKPRN, LEFT(CONCAT(Name,' ',UKPRN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
        $L46_dropdown = DAO::getResultset($link,$L46_dropdown);

        $L01_dropdown = "SELECT DISTINCT CAPN, LEFT(CONCAT(Name,' ',CAPN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
        $L01_dropdown = DAO::getResultset($link,$L01_dropdown);

        // Presentation
        include('tpl_edit_systemowner.php');
    }
}
?>