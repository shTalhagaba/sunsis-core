<?php
class view_pool_organisations implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $view = VoltView::getViewFromSession('ViewPoolOrganisations', 'ViewPoolOrganisations'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewPoolOrganisations'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_pool_organisations", "View Pool Organisations");

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        include_once('tpl_view_pool_organisations.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT
  pool.id AS system_id,
  pool_locations.`id` AS pool_location_id,
  pool.*,
  pool_locations.*
FROM
  pool
  LEFT JOIN pool_locations
    ON (pool.`id` = pool_locations.`pool_id` AND pool_locations.is_legal_address = '1')
;
		");

        $view = new VoltView('ViewPoolOrganisations', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_legal_name', "WHERE pool.legal_name LIKE '%%%s%%'", null);
        $f->setDescriptionFormat("Legal name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_postcode', "WHERE pool_locations.postcode LIKE '%s%%'", null);
        $f->setDescriptionFormat("Postcode starts with: %s");
        $view->addFilter($f);

        $options = 'SELECT DISTINCT address_line_3, address_line_3, null, CONCAT("WHERE pool_locations.address_line_3=",CHAR(39),address_line_3,CHAR(39)) FROM pool_locations INNER JOIN pool ON pool.id = pool_locations.pool_id WHERE pool_locations.address_line_3 != "" ORDER BY pool_locations.address_line_3';
        $f = new VoltDropDownViewFilter('filter_address_line_3', $options, null, true);
        $f->setDescriptionFormat("Address line 3: %s");
        $view->addFilter($f);

        $options = 'SELECT DISTINCT address_line_4, address_line_4, null, CONCAT("WHERE pool_locations.address_line_4=",CHAR(39),address_line_4,CHAR(39)) FROM pool_locations INNER JOIN pool ON pool.id = pool_locations.pool_id WHERE pool_locations.address_line_4 != "" ORDER BY pool_locations.address_line_4';
        $f = new VoltDropDownViewFilter('filter_address_line_4', $options, null, true);
        $f->setDescriptionFormat("Address line 4: %s");
        $view->addFilter($f);

        $format = "WHERE pool.created_at >= '%s'";
        $f = new VoltDateViewFilter('from_created_date', $format, '');
        $f->setDescriptionFormat("From created/imported date: %s");
        $view->addFilter($f);
        $format = "WHERE pool.created_at <= '%s'";
        $f = new VoltDateViewFilter('to_created_date', $format, '');
        $f->setDescriptionFormat("To created/imported date: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(20,20,null,null),
            1=>array(50,50,null,null),
            2=>array(100,100,null,null),
            3=>array(200,200,null,null),
            4=>array(300,300,null,null),
            5=>array(400,400,null,null),
            6=>array(500,500,null,null),
            7=>array(0, 'No limit', null, null));
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Legal Name', null, 'ORDER BY legal_name'));
        $f = new VoltDropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);


        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {

        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblPoolOrganisations" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Legal Name</th><th>System ID</th><th>Company Number</th><th>Address Line 1</th><th>Address Line 2</th><th>Locality</th><th>Town/City</th><th>Postcode</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_pool_organisation&id='.$row['system_id']);
                echo '<td>' . $row['legal_name'] . '</td>';
                echo '<td>' . $row['system_id'] . '</td>';
                echo '<td>' . $row['company_number'] . '</td>';
                echo '<td>' . $row['address_line_1'] . '</td>';
                echo '<td>' . $row['address_line_2'] . '</td>';
                echo '<td>' . $row['address_line_3'] . '</td>';
                echo '<td>' . $row['address_line_4'] . '</td>';
                echo '<td><i class="fa fa-map-marker"></i> ' . $row['postcode'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_csv(PDO $link, VoltView $view)
    {
        $view->exportToCSV($link);
        exit;
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=PoolOrganisations.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo "Legal Name,System ID,Company Number,Address Line 1,Address Line 2,Locality,Town/City,Postcode";
            echo "\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::csvSafe($row['legal_name']) . ",";
                echo HTML::csvSafe($row['system_id']) . ",";
                echo HTML::csvSafe($row['company_number']) . ",";
                echo HTML::csvSafe($row['address_line_1']) . ",";
                echo HTML::csvSafe($row['address_line_2']) . ",";
                echo HTML::csvSafe($row['address_line_3']) . ",";
                echo HTML::csvSafe($row['address_line_4']) . ",";
                echo HTML::csvSafe($row['postcode']);
                echo "\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}