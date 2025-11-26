<?php
class save_allocation implements IAction
{

    public function execute(PDO $link)
    {
        $vo = new Allocation();
        $vo->populate($_POST);

        DAO::transaction_start($link);
        try
        {
            $vo->save($link);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $vo->id;
        }
        else
        {
            http_redirect('do.php?_action=view_allocations');
        }
    }
}
?>