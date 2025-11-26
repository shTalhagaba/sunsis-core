<?php
class test_edit_purchase implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=test_edit_purchase&id=" . $id, "Purchase");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
        }

        if($id == '')
        {
            // New record
            $vo = new TestPurchase();
            $models = DAO::getResultset($link, "SELECT id, description, null FROM car_models limit 0,0;", DAO::FETCH_NUM);
        }
        else
        {
            $vo = TestPurchase::loadFromDatabase($link, $id);
            $models = DAO::getResultset($link, "SELECT id, description, null FROM car_models where id = $vo->model", DAO::FETCH_NUM);
        }

        $makes = DAO::getResultset($link, "SELECT id, description, null FROM car_makes;", DAO::FETCH_NUM);
        $transmission = array(
            array('1', 'Manual'),
            array('2', 'Automatic'));
      include('tpl_test_edit_purchase.php');
    }
}
?>