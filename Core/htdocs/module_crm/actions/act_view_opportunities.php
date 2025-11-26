<?php
class view_opportunities implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewOpportunities', 'ViewOpportunities'); /* @var $view VoltView */
        if (is_null($view)) {
            $view = $_SESSION['ViewOpportunities'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_opportunities", "View Opportunities");

        if ($subaction == 'export_csv') {
            $this->export_csv($link, $view);
            exit;
        }

        include_once('tpl_view_opportunities.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT
        crm_opportunities.*,
        CASE crm_opportunities.`company_type`
            WHEN 'pool' THEN (SELECT legal_name FROM pool WHERE pool.id = crm_opportunities.`company_id`)
            WHEN 'employer' THEN (SELECT legal_name FROM organisations WHERE organisations.id = crm_opportunities.`company_id`)
            ELSE ''
        END AS company,
        CASE crm_opportunities.`company_type`
            WHEN 'pool' THEN (SELECT CONCAT(COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' ') FROM pool_contact WHERE contact_id = crm_opportunities.`main_contact_id`)
            WHEN 'pool' THEN (SELECT CONCAT(COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' ') FROM organisation_contact WHERE contact_id = crm_opportunities.`main_contact_id`)
            ELSE ''
        END AS company_contact,
        CASE crm_opportunities.`company_type`
            WHEN 'pool' THEN (SELECT company_rating FROM pool WHERE id = crm_opportunities.`company_id`)
            WHEN 'employer' THEN (SELECT company_rating FROM organisations WHERE id = crm_opportunities.`company_id`)
            ELSE ''
        END AS company_rating
    FROM
        crm_opportunities
		");

        $view = new VoltView('ViewOpportunities', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_id', "WHERE crm_opportunities.id = '%s%%'", null);
        $f->setDescriptionFormat("ID: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_title', "WHERE crm_opportunities.opportunity_title LIKE '%%%s%%'", null);
        $f->setDescriptionFormat("Title: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames,' ',users.surname), null, CONCAT('WHERE crm_opportunities.created_by=',users.id) FROM users INNER JOIN crm_opportunities ON users.id = crm_opportunities.created_by ORDER BY users.firstnames";
        $f = $_SESSION['user']->isAdmin() ?
            new VoltDropDownViewFilter('filter_owner', $options, '', true) :
            new VoltDropDownViewFilter('filter_owner', $options, $_SESSION['user']->id, true);
        $f->setDescriptionFormat("Status: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_company', "HAVING company LIKE '%%%s%%'", null);
        $f->setDescriptionFormat("Company: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('1', 'Open', null, 'WHERE crm_opportunities.status = "1"'),
            1 => array('2', 'In Progress', null, 'WHERE crm_opportunities.status = "2"'),
            2 => array('3', 'Qualified', null, 'WHERE crm_opportunities.status = "3"'),
            3 => array('4', 'Unqualified', null, 'WHERE crm_opportunities.status = "4"')
        );
        $f = new VoltDropDownViewFilter('filter_status', $options, null, true);
        $f->setDescriptionFormat("Status: %s");
        $view->addFilter($f);

        $options = [
            0 => [1, 'Opportunity ID (descending)', null, 'ORDER BY crm_opportunities.id DESC'],
            1 => [2, 'Opportunity ID (ascending)', null, 'ORDER BY crm_opportunities.id ASC'],
            2 => [3, 'Opportunity Title', null, 'ORDER BY crm_opportunities.opportunity_title ASC'],
        ];
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);

        $options = array(
            0 => array(20, 20, null, null),
            1 => array(50, 50, null, null),
            2 => array(100, 100, null, null),
            3 => array(200, 200, null, null),
            4 => array(300, 300, null, null),
            5 => array(400, 400, null, null),
            6 => array(500, 500, null, null),
            7 => array(0, 'No limit', null, null)
        );
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if ($st) {
            $columns = array();
            for ($i = 0; $i < $st->columnCount(); $i++) {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblOpportunities" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            //			foreach($columns AS $column)
            //			{
            //				if($column == 'p_addr')
            //					echo '<th class="bottomRow">Permanent Address</th>';
            //				elseif($column == 'p_addr_city')
            //					echo '<th class="bottomRow">City</th>';
            //				elseif($column == 'p_addr_postcode')
            //					echo '<th class="bottomRow">Postcode</th>';
            //				else
            //					echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
            //			}
            echo '<th>Opportunity ID/Ref.</th><th>Title</th><th>Opportunity Status</th><th>Company</th><th>Contact Person</th><th>Estimated Closed Date</th><th style="width: 20%;">Description</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                echo HTML::viewrow_opening_tag('do.php?_action=read_opportunity&id=' . $row['id']);
                //				foreach($columns AS $column)
                //				{
                //					if($column == 'status')
                //						echo '<td><span class="label label-info">' . Opportunity::getListOpportunityStatus($row['status']) . '</span></td>';
                //					else
                //						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                //				}
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['opportunity_title'] . '</td>';
                if ($row['status'] == 2)
                    echo '<td><span class="label label-primary">' . Opportunity::getListOpportunityStatus($row['status']) . '</span></td>';
                elseif ($row['status'] == 3)
                    echo '<td><span class="label label-success">' . Opportunity::getListOpportunityStatus($row['status']) . '</span></td>';
                else
                    echo '<td><span class="label label-info">' . Opportunity::getListOpportunityStatus($row['status']) . '</span></td>';
                echo '<td><span class="text-bold text-blue">' . $row['company'] . '</span></td>';
                echo '<td>';
                echo $row['company_contact'];
                echo '</td>';
                echo '<td>' . Date::toShort($row['est_closed_date']) . '</td>';
                echo '<td class="small">' . $row['description'] . '</td> ';
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_csv(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if ($st) {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=Opportunities.csv');
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo "Opportunity ID/Ref.,Title,Opportunity Status,Company,Contact Person,Estimated Closed Date,";
            echo "Estimated Revenue,Converted,Description";
            echo "\n";
            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                echo $row['id'] . ",";
                echo HTML::csvSafe($row['opportunity_title'] ?? '') . ",";
                echo HTML::csvSafe(Opportunity::getListOpportunityStatus($row['status']) ?? '') . ",";
                echo HTML::csvSafe($row['company'] ?? '') . ",";
                echo HTML::csvSafe(
                    ($row['contact_title'] ?? '') . " " .
                        ($row['first_name'] ?? '') . " " .
                        ($row['surname'] ?? '')
                ) . ",";
                echo HTML::csvSafe($row['phone'] ?? '') . ",";
                echo HTML::csvSafe($row['mobile'] ?? '') . ",";
                echo HTML::csvSafe($row['email'] ?? '') . ",";
                echo HTML::csvSafe(
                    ($row['p_addr'] ?? '') . "; " .
                        ($row['p_addr_city'] ?? '') . "; " .
                        ($row['p_addr_region'] ?? '')
                ) . ",";
                echo HTML::csvSafe($row['p_addr_postcode'] ?? '');
                echo ($row['est_closed_date'] ?? '') . ",";
                echo HTML::csvSafe($row['est_revenue']) . ",";
                echo $row['converted'] == 1 ? "Yes" . "," : "No" . ",";
                echo HTML::csvSafe($row['description'] ?? '');
                echo "\n";
            }
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}
