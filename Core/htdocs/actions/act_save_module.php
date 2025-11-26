<?php
class save_module implements IAction
{

    public function execute(PDO $link)
    {
        $vo = new Module();
        $vo->populate($_POST);


//		if($vo->contract_year>2008 && DB_NAME!='am_sunesis' && DB_NAME!='ams')
//			throw new Exception("Funding module for 2009/10 is under development");

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
            http_redirect('do.php?_action=view_modules');
        }
    }
}
?>