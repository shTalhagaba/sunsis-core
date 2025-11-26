<?php
class save_crm_contacts implements IAction
{
    public function execute(PDO $link)
    {
        if($_POST['org_type'] == 'pool')
        {
            $vo = new EmployerPoolContacts();
    
        }
        elseif($_POST['org_type'] == 'employer')
        {
            $vo = new OrganisationContact();
        }
        else
        {
            throw new Exception("Missing organisation type");
        }

        $vo->populate($_POST);
        if($_POST['org_type'] == 'pool')
        {
            $vo->pool_id = $_POST['org_id'];
        }
        if($_POST['org_type'] == 'employer')
        {
            $vo->org_id = $_POST['org_id'];
        }

        $vo->save($link);

        if(IS_AJAX)
        {
            return 1;
        }

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
