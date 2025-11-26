<?php
class TestViewPurchase extends View
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
,sales_date AS purchase_date
,(select description from car_makes where id = make) AS car_make
,(select description from car_models where id = model) AS car_model
,reg_mark AS registration_mark
,c_name AS customer_name
,c_address AS customer_address
,c_phone AS customer_phone
,concat('&pound;',price) AS purchase_price
,colour as colour
,car_keys as car_keys
,service_history as service_history
,mot as mot_expiry_date
,mileage
,road_tax as road_tax_expiry_date
,owners as previous_owners
,engine_size
,IF(transmission='1', 'Manual', 'Automatic') as transmission
,concat('&pound;',sva_fee) as sva_fee
,concat('&pound;',registration_fee) as registration_fee
,concat('&pound;',speedo_meter_change) as speedo_meter_change
,concat('&pound;',immobiliser) as immobiliser
,concat('&pound;',custom_duty) as custom_duty
,concat('&pound;',vat) as vat
,concat('&pound;',repair) as mechanical_work
,concat('&pound;',transport) as transportation
,concat('&pound;',valet) as valeting
,concat('&pound;',(sva_fee+registration_fee+speedo_meter_change+immobiliser+custom_duty+vat+price+repair+transport+valet)) as total_cost
FROM test_purchase
$where
HEREDOC;

            $view = $_SESSION[$key] = new TestViewPurchase();
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

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Available stock', null, 'WHERE id not in (select pid from test_sales)'),
                2=>array(2, '2. Sold stock', null, 'WHERE id in (select pid from test_sales)'));
            $f = new DropDownViewFilter('filter_stock', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
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

            mb_convert_encoding('£','UTF-8');
            htmlentities('£');
            while($row = $st->fetch())
            {

                echo HTML::viewrow_opening_tag('/do.php?_action=test_edit_purchase&id=' . $row['id']);
                echo '<td><img src="/images/blue-building.png" width="25" height="30" border="0" /></td>';

                foreach($columns as $column)
                {

                    if($column=='service_history')
                        if($row['service_history']==1)
                            echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
                        else
                            echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
                    else
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
