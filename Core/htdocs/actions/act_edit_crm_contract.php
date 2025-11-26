<?php
class edit_crm_contract implements IAction
{
    public function execute(PDO $link)
    {
        $crm_id = isset($_REQUEST['id']) ? $_REQUEST['id']:'';

        $_SESSION['bc']->add($link, "do.php?_action=edit_crm_contract", "CRM / Contract Link");

        $crm = DAO::getSingleValue($link, "select description from lookup_crm_subject where id = '$crm_id'");
        $view = GetCRMContracts::getInstance($link, $crm_id);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_edit_crm_contract.php');
    }
}
?>
