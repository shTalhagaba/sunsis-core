<?php
class test_save_sales implements IAction
{

    public function execute(PDO $link)
    {

        $vo = new TestSales();
        $vo->populate($_POST);
        $vo->pid = $_POST['registration'];

//		if($vo->contract_year>2008 && DB_NAME!='am_sunesis' && DB_NAME!='ams')
//			throw new Exception("Funding module for 2009/10 is under development");

        DAO::transaction_start($link);
        try
        {

            $vo->save($link);

            /*	$acl->resource_id = $vo->id;
                $acl->appendIdentities('read', $acl->readACLFormField($_POST, 'acl_read'));
                $acl->removeIdentities('read', $acl->readACLFormField($_POST, 'acl_read_not'));
                $acl->appendIdentities('write', $acl->readACLFormField($_POST, 'acl_write'));
                $acl->removeIdentities('write', $acl->readACLFormField($_POST, 'acl_write_not'));
                $acl->save($link); */

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
            http_redirect('do.php?_action=test_view_sales');
        }
    }
}
?>