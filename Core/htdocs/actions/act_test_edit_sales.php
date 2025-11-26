<?php
class test_edit_sales implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=test_edit_sales&id=" . $id, "Sales");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
        }

        if($id == '')
        {
            // New record
            $vo = new TestSales();
            $p = new TestPurchase();
            $registrations = DAO::getResultset($link, "select id, reg_mark from test_purchase where id not in (select pid from test_sales)");
        }
        else
        {
            $vo = TestSales::loadFromDatabase($link, $id);
            $p = TestPurchase::loadFromDatabase($link, $vo->pid);
            $registrations = DAO::getResultset($link, "select id, reg_mark from test_purchase where id not in (select pid from test_sales) UNION select id, reg_mark from test_purchase where id = '$vo->pid'");
        }


        include('tpl_test_edit_sales.php');
    }
}
?>