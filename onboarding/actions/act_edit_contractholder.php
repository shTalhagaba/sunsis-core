<?php
class edit_contractholder implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_contractholder&id=" . $id, "Add/ Edit Contract Holder");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
        }

        if($id == '')
        {
            // New record
            $ch = new ContractHolder();
            $ch->active = 1;
            $ch->organisation_type = Organisation::TYPE_CONTRACT_HOLDER;
            $mainLocation = new Location();
            $mainLocation->is_legal_address = 1;
        }
        else
        {
            $ch = ContractHolder::loadFromDatabase($link, $id);
            $mainLocation = $ch->getMainLocation($link);
        }

        $L46_dropdown = "SELECT DISTINCT UKPRN, LEFT(CONCAT(Name,' ',UKPRN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
        $L46_dropdown = DAO::getResultset($link,$L46_dropdown);

        $L01_dropdown = "SELECT DISTINCT CAPN, LEFT(CONCAT(Name,' ',CAPN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
        $L01_dropdown = DAO::getResultset($link,$L01_dropdown);

        // Presentation
        include('tpl_edit_contractholder.php');
    }
}
?>