<?php
class view_change_of_employer_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewChangeOfEmployerReport', 'ViewChangeOfEmployerReport');
        if (is_null($view)) {
            $view = $_SESSION['ViewChangeOfEmployerReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_change_of_employer_report", "Change of Employer Report");

        if ($subaction == 'export_csv') {
            $view->exportToCSV($link);
            exit;
        }

        require_once('tpl_view_change_of_employer_report.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT
        tr.id AS training_id,
        tr.`firstnames`,
        tr.`surname`,
        tr.`l03` AS learner_reference,
	IF( 
            tr_coe.current_employer IS NOT NULL,
            tr_coe.current_employer,
            (SELECT legal_name FROM organisations WHERE organisations.id = tr.employer_id)    
        ) AS employer,
        #(SELECT legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer,
        (SELECT title FROM student_frameworks WHERE student_frameworks.tr_id = tr.id) AS programme,
        coe_new_employer_name AS new_employer_name,
        DATE_FORMAT(coe_last_day, '%d/%m/%Y') AS last_day,
        DATE_FORMAT(coe_start_date, '%d/%m/%Y') AS start_date,
        coe_das_month,
        IF(coe_rfs = 1, 'Yes', '') AS rfs,
        IF(coe_fa = 1, 'Yes', '') AS fa,
        IF(coe_hs = 1, 'Yes', '') AS h_and_s,
        IF(coe_ilp = 1, 'Yes', '') AS ilp,
        IF(coe_tp_sent = 1, 'Yes', '') AS training_plan_sent,
        DATE_FORMAT(coe_tp_sent_date, '%d/%m/%Y') AS training_plan_sent_date,
        IF(coe_tp_signed = 1, 'Yes', '') AS training_plan_signed,
        DATE_FORMAT(coe_tp_signed_date, '%d/%m/%Y') AS training_plan_signed_date,
        EXTRACTVALUE(coe_notes, '/Notes/Note[last()]/Comment') AS coe_notes,
        tr_coe.coe_das_stopped,
        tr_coe.coe_added_new_das,
        tr_coe.coe_new_das,
        DATE_FORMAT(coe_nda, '%d/%m/%Y') AS coe_nda,
        IF(tr_coe.coe_process_complete = 1, 'Yes', IF(tr_coe.coe_process_complete = 0, 'No', '') ) AS coe_process_complete,
        tr_coe.coe_owner,
        tr_coe.coe_status,
        DATE_FORMAT(tr_coe.coe_date_raised, '%d/%m/%Y') AS coe_date_raised,
        DATE_FORMAT(tr_coe.coe_date_closed, '%d/%m/%Y') AS coe_date_closed
      FROM
        tr INNER JOIN tr_coe ON tr.id = tr_coe.tr_id
      WHERE tr_coe.coe_new_employer_name IS NOT NULL
        ;
        ");

        $view = new VoltView('ViewChangeOfEmployerReport', $sql->__toString());

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
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Process complete - Yes', null, 'WHERE tr_coe.coe_process_complete="1"'),
            2=>array(2, 'Process complete - No', null, 'WHERE tr_coe.coe_process_complete="0"'));
        $f = new VoltDropDownViewFilter('filter_process_complete', $options, 0, false);
        $f->setDescriptionFormat("Process Complete: %s");
        $view->addFilter($f);

        $options = array(
            0 => array(1, 'Firstnames', null, 'ORDER BY tr.firstnames ASC'),
            1 => array(2, 'Surname', null, 'ORDER BY tr.surname ASC')
        );
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
                echo '<tr>';
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
