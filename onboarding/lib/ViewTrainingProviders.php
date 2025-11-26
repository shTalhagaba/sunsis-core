<?php
class ViewTrainingProviders extends View
{
    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $organisation_type = Organisation::TYPE_TRAINING_PROVIDER;
            $sql = <<<HEREDOC
SELECT
    organisations.id,
    organisations.legal_name,
    organisations.ukprn,
    locations.address_line_1, 
    locations.address_line_2, 
    locations.address_line_3, 
    locations.address_line_4, 
    locations.postcode,
    locations.telephone
FROM
    organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
WHERE organisations.organisation_type = '{$organisation_type}';
HEREDOC;
            $view = $_SESSION[$key] = new ViewTrainingProviders();
            $view->setSQL($sql);

            // Add view filters
            $f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Legal Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Postcode: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_ukprn', "WHERE organisations.ukprn LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("UKPRN: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Company name (asc)', null, 'ORDER BY legal_name'),
                1=>array(2, 'Company name (desc)', null, 'ORDER BY legal_name DESC'),
                2=>array(3, 'Location (asc), Provider name (asc)', null, 'ORDER BY address_line_3, address_line_2, legal_name'),
                3=>array(4, 'Location (desc), Provider name (desc)', null, 'ORDER BY address_line_3 DESC, address_line_2 DESC, legal_name DESC'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div class="table-responsive"><table id="tblTrainingProviders" class="table table-bordered">';
            echo <<<HTML
<thead>
    <tr>
        <th>&nbsp;</th>
        <th>Legal Name</th>
        <th>Ukprn</th>
        <th>Address Line 1</th>
        <th>Address Line 2</th>
        <th>Address Line 3</th>
        <th>Address Line 4</th>
        <th>Postcode</th>
        <th>Telephone</th>
    </tr>
</thead>
HTML;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('/do.php?_action=read_trainingprovider&id=' . $row['id']);
                echo '<td><span class="fa fa-bank"></span> </td>';
                echo '<td>' . HTML::cell($row['legal_name']) . '</td>';
                echo '<td>' . HTML::cell($row['ukprn']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_1']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_2']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_3']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_4']) . '</td>';
                echo '<td>' . HTML::cell($row['postcode']) . '</td>';
                echo '<td>' . HTML::cell($row['telephone']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
?>