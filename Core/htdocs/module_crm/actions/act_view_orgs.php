<?php
class view_orgs implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $view = VoltView::getViewFromSession('ViewOrgs', 'ViewOrgs'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewOrgs'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_orgs", "View Organisations");

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        include_once('tpl_view_orgs.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT * FROM 
    (SELECT
        'pool' AS org_type,
        pool.id AS system_id,
        pool_locations.`id` AS location_id,
        pool.legal_name,
        pool.`company_number`,
        pool_locations.`address_line_1`,
        pool_locations.`address_line_2`,
        pool_locations.`address_line_3`,
        pool_locations.`address_line_4`,
        pool_locations.postcode
    FROM
        pool
        LEFT JOIN pool_locations
          ON (pool.`id` = pool_locations.`pool_id` AND pool_locations.is_legal_address = '1')
    WHERE pool.employer_id IS NULL
    UNION ALL
    SELECT
        'employer' AS org_type,
        organisations.id AS system_id,
        locations.`id` AS location_id,
        organisations.legal_name,
        organisations.`company_number`,
        locations.`address_line_1`,
        locations.`address_line_2`,
        locations.`address_line_3`,
        locations.`address_line_4`,
        locations.postcode
    FROM
        organisations
    LEFT JOIN locations
          ON (organisations.`id` = locations.`organisations_id` AND locations.is_legal_address = '1')
	WHERE organisations.`organisation_type` = 2
    ) AS orgs
	");

        $view = new VoltView('ViewOrgs', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_legal_name', "WHERE orgs.legal_name LIKE '%%%s%%'", null);
        $f->setDescriptionFormat("Legal name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_postcode', "WHERE orgs.postcode LIKE '%s%%'", null);
        $f->setDescriptionFormat("Postcode starts with: %s");
        $view->addFilter($f);

        $options = <<<SQL
        SELECT * FROM (
SELECT DISTINCT
  address_line_3 AS id,
  address_line_3 AS description,
  NULL,
  CONCAT(
    "WHERE orgs.address_line_3=",
    CHAR(39),
    address_line_3,
    CHAR(39)
  )
FROM
  pool_locations
  INNER JOIN pool
    ON pool.id = pool_locations.pool_id
WHERE pool_locations.address_line_3 != ""
UNION ALL
SELECT DISTINCT
  address_line_3 AS id,
  address_line_3 AS description,
  NULL,
  CONCAT(
    "WHERE orgs.address_line_3=",
    CHAR(39),
    address_line_3,
    CHAR(39)
  )
FROM
  locations
  INNER JOIN organisations
    ON organisations.id = locations.`organisations_id`
WHERE locations.address_line_3 != ""
) AS locs
ORDER BY locs.description
SQL;
        $f = new VoltDropDownViewFilter('filter_address_line_3', $options, null, true);
        $f->setDescriptionFormat("Address line 3: %s");
        $view->addFilter($f);

        $options = <<<SQL
        SELECT * FROM (
SELECT DISTINCT
  address_line_4 AS id,
  address_line_4 AS description,
  NULL,
  CONCAT(
    "WHERE orgs.address_line_4=",
    CHAR(39),
    address_line_4,
    CHAR(39)
  )
FROM
  pool_locations
  INNER JOIN pool
    ON pool.id = pool_locations.pool_id
WHERE pool_locations.address_line_4 != ""
UNION ALL
SELECT DISTINCT
  address_line_4 AS id,
  address_line_4 AS description,
  NULL,
  CONCAT(
    "WHERE orgs.address_line_4=",
    CHAR(39),
    address_line_4,
    CHAR(39)
  )
FROM
  locations
  INNER JOIN organisations
    ON organisations.id = locations.`organisations_id`
WHERE locations.address_line_4 != ""
) AS locs
ORDER BY locs.description
SQL;
        $f = new VoltDropDownViewFilter('filter_address_line_4', $options, null, true);
        $f->setDescriptionFormat("Address line 4: %s");
        $view->addFilter($f);

	$options = array(
          0=>array(0, 'Show all', null, null),
          1=>array(1, 'Employers Only', null, 'HAVING org_type = "employer"'),
          2=>array(2, 'Pool Only', null, 'HAVING org_type = "pool"'));
        $f = new VoltDropDownViewFilter('filter_org_type', $options, 0, false);
        $f->setDescriptionFormat("Organisation Type: %s");
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
            0=>array(1, 'Legal Name', null, 'ORDER BY orgs.legal_name'));
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
            echo '<th>Legal Name</th><th>Org Type</th><th>System ID</th><th>Company Number</th><th>Address Line 1</th><th>Address Line 2</th><th>Locality</th><th>Town/City</th><th>Postcode</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo $row['org_type'] == 'employer' ? 
                    HTML::viewrow_opening_tag('do.php?_action=read_employer_v3&id='.$row['system_id']) : 
                    HTML::viewrow_opening_tag('do.php?_action=read_pool_organisation&id='.$row['system_id']);
                echo '<td>' . $row['legal_name'] . '</td>';
                echo '<td>';
                echo $row['org_type'] == 'employer' ? '<span class="text-green">Employer</span>' : '<span class="text-info">Pool</span>';
                echo '</td>';
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