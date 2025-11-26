<?php
class save_department implements IAction
{
    public function execute(PDO $link)
    {

        $org = new Department();
        $org->populate($_POST);

        // active is 1 by default so fix it
        $org->active = isset($_POST['active']) ? 1 : 0;

        $loc = new Location();
        $loc->populate($_POST);
        $loc->id = $_POST['main_location_id'];

        DAO::transaction_start($link);
        try
        {
            $org->save($link);

            $loc->organisations_id = $org->id;
            $loc->is_legal_address = 1;
            $loc->save($link);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect('do.php?_action=read_department&id=' . $org->id);
    }
}
?>