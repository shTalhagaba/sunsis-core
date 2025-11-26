<?php
class save_org_crm_note implements IAction
{
    public function execute(PDO $link)
    {
        $crm_note = new OrganisationCrmNote();
        $crm_note->populate($_POST);

        $existing_record = OrganisationCrmNote::loadFromDatabase($link, $_POST['id']);

        DAO::transaction_start($link);
        try
        {
            $crm_note->save($link);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
