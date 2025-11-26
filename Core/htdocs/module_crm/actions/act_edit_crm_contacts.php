<?php
class edit_crm_contacts implements IAction
{
    public function execute(PDO $link)
    {
        $contact_id = isset($_REQUEST['contact_id']) ? $_REQUEST['contact_id'] : '';
        $org_id = isset($_REQUEST['org_id']) ? $_REQUEST['org_id'] : '';
        $org_type = isset($_REQUEST['org_type']) ? $_REQUEST['org_type'] : '';

        if($org_id == '' || $org_type == '')
        {
            throw new Exception("Missing querystring argument: org_id, org_type");
        }

        if($org_type == 'pool')
        {
            $contact = $contact_id == '' ? new EmployerPoolContacts() : EmployerPoolContacts::loadFromDatabase($link, $contact_id);
            $organisation = EmployerPool::loadFromDatabase($link, $org_id);
        }
        if($org_type == 'employer')
        {
            $contact = $contact_id == '' ? new OrganisationContact() : OrganisationContact::loadFromDatabase($link, $contact_id);
            $organisation = Organisation::loadFromDatabase($link, $org_id);
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_crm_contacts&contact_id={$contact->contact_id}&org_id={$org_id}&org_type={$org_type}", "Add/Edit Contact Person");

        include_once ('tpl_edit_crm_contacts.php');
    }
}