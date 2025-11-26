<?php
class view_tr_compliance_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $view = VoltView::getViewFromSession('ViewLearnersComplianceReport', 'ViewLearnersComplianceReport'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewLearnersComplianceReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if($subaction == 'export')
        {
            $this->export($link, $view);
            exit;
        }

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_tr_compliance_report", "View Learners Compliance Report");

        include('tpl_view_tr_compliance_report.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT
    tr.id AS tr_id,
    tr.firstnames,
    tr.surname,
    student_frameworks.title AS framework,
    compliance_checklist.id AS event_id,
    compliance_checklist.c_event,
    tr_compliance.*
FROM 
    tr LEFT JOIN student_frameworks ON tr.`id` = student_frameworks.tr_id
    LEFT JOIN compliance_checklist ON student_frameworks.`id` = compliance_checklist.`framework_id`
    LEFT JOIN tr_compliance ON (tr_compliance.compliance_item_id = compliance_checklist.`id` AND tr_compliance.tr_id = tr.`id`)

;
		");

        $view = new VoltView('ViewLearnersComplianceReport', $sql->__toString());

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
            2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
            3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
            4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
            5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
            6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
            7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
        $f = new VoltDropDownViewFilter('filter_record_status', $options, 1, false);
        $f->setDescriptionFormat("Show: %s");
        $view->addFilter($f);


        $options = array(
            0 => array('1', 'With Compliance Records', null, ' WHERE tr_compliance.compliance_item_id is NOT NULL '),
            1 => array('2', 'Without Compliance Records', null, ' WHERE tr_compliance.compliance_item_id is NULL ')
        );
        $f = new VoltDropDownViewFilter('filter_compliance', $options, 1, true);
        $f->setDescriptionFormat("With/Without Compliance Records: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
        $f->setDescriptionFormat("L03: %s");
        $view->addFilter($f);

        if($_SESSION['user']->type == User::TYPE_MANAGER)
            $options = "SELECT DISTINCT frameworks.id, title, null, CONCAT('WHERE student_frameworks.id=',frameworks.id) FROM frameworks where frameworks.parent_org = '{$_SESSION['user']->employer_id}' AND frameworks.active = 1 ORDER BY frameworks.title";
        else
            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM frameworks ORDER BY frameworks.title";
        $f = new VoltDropDownViewFilter('filter_framework', $options, null, true);
        $f->setDescriptionFormat("Framework: %s");
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
        $f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Learner Name, Event Name', null, 'ORDER BY tr.firstnames, compliance_checklist.sorting'));
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //pr($view->getSQLStatement()->__toString());

        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblActivities" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Firstnames</th><th>Surname</th><th>Framework</th><th>Event</th><th>Sub&nbsp;Events</th><th>Date Submitted</th><th>Actual Date</th><th>Status</th><th>Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo '<tr>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td class="small">' . HTML::cell($row['framework']) . '</td>';
                echo '<td>' . HTML::cell($row['c_event']) . '</td>';
                if($row['sub_events'] == '')
                {
                    echo '<td></td>';
                }
                else
                {
                    $sub_events = explode(",", $row['sub_events']);
                    $sub_events_html = '';
                    foreach($sub_events AS $s)
                    {
                        if($s == '') continue;
                        $sub_events_html .= DAO::getSingleValue($link, "SELECT EXTRACTVALUE(sub_events, 'SubEvents/Event[@id={$s}]/@title') FROM compliance_checklist WHERE id = '{$row['event_id']}';") . '<br>';
                    }
                    echo '<td>' . $sub_events_html . '</td>';
                }
                echo '<td>' . Date::toShort($row['submitted_date']) . '</td>';
                echo '<td>' . Date::toShort($row['actual_date']) . '</td>';
                if($row['status1'] == 'CP')
                    echo '<td>Checked and processed</td>';
                elseif($row['status1'] == 'Q')
                    echo '<td>Query</td>';
                elseif($row['status1'] == 'RA')
                    echo '<td>Received and awaiting processing</td>';
                else
                    echo '<td></td>';
                echo '<td>' . HTML::cell($row['comments']) . '</td>';
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

    private function export(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');

        $st = $link->query($statement->__toString());
        if($st)
        {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo "Firstnames,Surname,Framework,Event,Sub Events,Date Submitted,Actual Date,Status,Comments";
            echo "\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::csvSafe($row['firstnames']) . ",";
                echo HTML::csvSafe($row['surname']) . ",";
                echo HTML::csvSafe($row['framework']) . ",";
                echo HTML::csvSafe($row['c_event']) . ",";
                if($row['sub_events'] == '')
                {
                    echo ',';
                }
                else
                {
                    $sub_events = explode(",", $row['sub_events']);
                    $sub_events_html = '';
                    foreach($sub_events AS $s)
                    {
                        if($s == '') continue;
                        $_temp = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(sub_events, 'SubEvents/Event[@id={$s}]/@title') FROM compliance_checklist WHERE id = '{$row['event_id']}';");
                        $_temp = $_temp != '' ? HTML::csvSafe($_temp) . '; ' : '';
                        $sub_events_html .= $_temp;
                    }
                    echo $sub_events_html . ',';
                }
                echo Date::toShort($row['submitted_date']) . ",";
                echo Date::toShort($row['actual_date']) . ",";
                if($row['status1'] == 'CP')
                    echo 'Checked and processed,';
                elseif($row['status1'] == 'Q')
                    echo 'Query,';
                elseif($row['status1'] == 'RA')
                    echo 'Received and awaiting processing,';
                else
                    echo ',';
                echo HTML::csvSafe($row['comments']) . ",";
                echo "\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}
?>