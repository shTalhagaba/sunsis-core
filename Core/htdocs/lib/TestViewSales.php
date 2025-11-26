<?php
class TestViewSales extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__;
        if(!isset($_SESSION[$key]))
        {
            $where = "";

            $sql = <<<HEREDOC
SELECT
id
,sales_date AS sales_date
,(SELECT description FROM car_makes INNER JOIN test_purchase ON test_purchase.make = car_makes.id WHERE test_purchase.id=test_sales.pid) AS car_make
,(SELECT description FROM car_models INNER JOIN test_purchase ON test_purchase.model = car_models.id WHERE test_purchase.id=test_sales.pid) AS car_model
,(SELECT reg_mark FROM test_purchase WHERE test_purchase.id=test_sales.pid) AS registration_mark
,c_name AS customer_name
,c_address AS customer_address
,c_phone AS customer_phone
,pid
,concat('&pound;',price) as sales_price
,concat('&pound;',deposit) as deposit
,concat('&pound;',(price - deposit)) as to_pay
FROM test_sales
$where
HEREDOC;

            $view = $_SESSION[$key] = new TestViewSales();
            $view->setSQL($sql);

            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            // Start Date Filter
            $format = "WHERE sales_date >= '%s'";
            $f = new DateViewFilter('start_date', $format, '');
            $f->setDescriptionFormat("From Sales date: %s");
            $view->addFilter($f);

            $format = "WHERE sales_date <= '%s'";
            $f = new DateViewFilter('end_date', $format, '');
            $f->setDescriptionFormat("To Sales date: %s");
            $view->addFilter($f);

        }
        return $_SESSION[$key];
    }

    public function render(PDO $link, $columns)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="4">';
            echo '<thead><tr><th>&nbsp;</th>';

            foreach($columns as $column)
            {
                //echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
                echo '<th>' . ucwords(str_replace("_"," ",$column)) . '</th>';
            }

            echo '<tbody>';
            while($row = $st->fetch())
            {

                echo HTML::viewrow_opening_tag('/do.php?_action=test_edit_sales&id=' . $row['id'] . '&pid=' . $row['pid']);
                echo '<td><img src="/images/blue-building.png" width="25" height="30" border="0" /></td>';

                foreach($columns as $column)
                {
                    if($column=='name' || $column=='full_address')
                        echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    else
                        echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table></div align="center">';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }


}
?>
