<?php
class view_chocs implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewChocs', 'ViewChocs');
        //if (is_null($view)) 
        {
            $view = $_SESSION['ViewChocs'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_chocs", "View Choc Entries");

        if ($subaction == 'export_csv') {
            $view->exportToCSV($link);
            exit;
        }

        require_once('tpl_view_chocs.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT
        tr.id AS training_id,
        tr.`firstnames`,
        tr.`surname`,
        tr.`l03` AS learner_reference,
        (SELECT legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer,
        (SELECT title FROM student_frameworks WHERE student_frameworks.tr_id = tr.id) AS programme,
        #(SELECT extractvalue(ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/OrigLearnStartDate\") AS OrigLearnStartDate FROM ilr WHERE ilr.tr_id = tr.id ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1) AS original_start_date,
        chocs.id AS choc_id,
        chocs.choc_type,
        choc_status,
        (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = chocs.assigned_to) AS assigned_to,
        DATE_FORMAT(chocs.created_at, '%d/%m/%Y %H:%i:%s') AS created_at,
        DATE_FORMAT(chocs.updated_at, '%d/%m/%Y %H:%i:%s') AS last_updated_at
      FROM
        chocs INNER JOIN tr ON chocs.tr_id = tr.id
        ;
        ");

        $view = new VoltView('ViewChocs', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
        $f->setDescriptionFormat("L03: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $options = array(
            0 => array(1, 'Firstnames', null, 'ORDER BY tr.firstnames ASC'),
            1 => array(2, 'Surname', null, 'ORDER BY tr.surname ASC')
        );
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'NEW & CREATED BY LEARNER', null, 'WHERE chocs.choc_status IN ("NEW", "CREATED BY LEARNER")'),
            2=>array(2, 'NEW', null, 'WHERE chocs.choc_status = "NEW"'),
            3=>array(3, 'CREATED BY LEARNER', null, 'WHERE chocs.choc_status = "CREATED BY LEARNER"'),
            4=>array(4, 'IN PROGRESS', null, 'WHERE chocs.choc_status = "IN PROGRESS"'),
            5=>array(5, 'ACCEPTED', null, 'WHERE chocs.choc_status = "ACCEPTED"'),
            6=>array(6, 'REFERRED', null, 'WHERE chocs.choc_status = "REFERRED"'),
            7=>array(7, 'REFERRED TO LEARNER', null, 'WHERE chocs.choc_status = "REFERRED TO LEARNER"'),
            8=>array(8, 'COMPLETED', null, 'WHERE chocs.choc_status = "COMPLETED"'));
        $f = new VoltDropDownViewFilter('filter_choc_status', $options, 0, false);
        $f->setDescriptionFormat("Status: %s");
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
        $f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //if(SOURCE_HOME) pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if ($st) {
            $columns = [];
            for ($i = 0; $i < $st->columnCount(); $i++) {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            foreach ($columns as $column) {
                echo '<th>' . $column . '</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';

            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                // echo '<tr>';
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&tabChoc=1&id=' . $row['training_id']);
                foreach ($columns as $column) {
                    echo isset($row[$column]) ? '<td>' . $row[$column] . '</td>' : '<td></td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}
