<?php
class TestSales extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes((string)$id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	test_sales
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $org = null;
        if($st)
        {
            $org = null;
            $row = $st->fetch();
            if($row)
            {
                $org = new TestSales();
                $org->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
        }

        return $org;
    }

    public function save(PDO $link)
    {
        return DAO::saveObjectToTable($link, 'test_sales', $this);
    }

    public function delete(PDO $link)
    {
        // Placeholder
    }

    public $id = null;
    public $sales_date = NULL;
    public $make = NULL;
    public $model = NULL;
    public $reg_mark = NULL;
    public $c_name = NULL;
    public $c_address = NULL;
    public $c_phone = NULL;
    public $price = NULL;
    public $colour = NULL;
    public $car_keys = NULL;
    public $service_history = NULL;
    public $mot = NULL;
    public $road_tax = NULL;
    public $owners = NULL;
    public $pid = NULL;
    public $deposit = NULL;
    public $invoice = NULL;
}
?>