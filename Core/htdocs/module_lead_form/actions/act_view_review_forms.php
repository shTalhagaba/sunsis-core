<?php
class view_review_forms implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $_SESSION['bc']->add($link, "do.php?_action=view_review_forms&tr_id={$tr->id}", "View Learner Contacts");

        $employer_contacts_ddl = DAO::getResultset($link, "SELECT contact_id, CONCAT(contact_name, ' - ', contact_email), null FROM organisation_contact WHERE org_id = '{$tr->employer_id}' ORDER BY contact_name");

        include_once ('tpl_view_review_forms.php');
    }
}