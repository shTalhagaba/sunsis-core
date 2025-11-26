<?php
class save_crm_contract implements IAction
{
    public function execute(PDO $link)
    {
        $groups = isset($_REQUEST['groups'])?$_REQUEST['groups']:'';
        $crm_id = isset($_REQUEST['crm_id'])?$_REQUEST['crm_id']:'';
        $tobedeleted = isset($_REQUEST['tobedeleted'])?$_REQUEST['tobedeleted']:'';

        $tobedeleted = explode(",",$tobedeleted);
        foreach($tobedeleted as $del)
        {
            $contract_id = $del;
            if($contract_id!='')
                DAO::execute($link, "delete from crm_subjects_contracts where crm_subject_id = $crm_id");
        }
        $groups = explode(",",$groups);
        foreach($groups as $group)
        {
            $contract_id = $group;
            if($contract_id!='')
                DAO::execute($link, "insert into crm_subjects_contracts values($crm_id,$contract_id);");
        }
        http_redirect('do.php?_action=edit_crm_subjects');
    }
}
?>