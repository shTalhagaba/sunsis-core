<?php
class view_tr_reinstatement implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewTrResinstatementReport', 'ViewTrResinstatementReport');
        if (is_null($view)) {
            $view = $_SESSION['ViewTrResinstatementReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_tr_reinstatement", "Reinstatement Report");

        if ($subaction == 'export_csv') {
            $view->exportToCSV($link);
            exit;
        }

        require_once('tpl_view_tr_reinstatement.php');
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
        (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/OrigLearnStartDate\") AS OrigLearnStartDate FROM ilr WHERE ilr.tr_id = tr.id ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1) AS original_start_date,
        DATE_FORMAT(tr.last_day_of_active_learning, '%d/%m/%Y') AS last_day_of_active_learning,
        DATE_FORMAT(tr.first_day_of_active_learning, '%d/%m/%Y') AS first_day_of_active_learning,
        DATE_FORMAT(tr.new_planned_end_date, '%d/%m/%Y') AS new_planned_end_date,
        (IF(tr.training_plan_sent = 1, 'Yes', 'No')) AS training_plan_sent,
        DATE_FORMAT(tr.training_plan_sent_date, '%d/%m/%Y') AS training_plan_sent_date,
        (IF(tr.training_plan_signed = 1, 'Yes', 'No')) AS training_plan_signed,
        DATE_FORMAT(tr.training_plan_signed_date, '%d/%m/%Y') AS training_plan_signed_date,
        EXTRACTVALUE(tr.`reinstatement_notes`, '/Notes/Note[last()]/Comment') AS reinstatement_notes,
        DATE_FORMAT(tr.reinstatement_nda, '%d/%m/%Y') AS reinstatement_nda,
        tr.reinstatement_owner,
        tr.reinstatement_type,
        DATE_FORMAT(tr.reinstatement_date_raised, '%d/%m/%Y') AS reinstatement_date_raised,
        DATE_FORMAT(tr.reinstatement_date_closed, '%d/%m/%Y') AS reinstatement_date_closed
      FROM
        tr
      WHERE TRUE AND 
        (tr.`last_day_of_active_learning` IS NOT NULL
        OR tr.`first_day_of_active_learning` IS NOT NULL
        OR tr.`new_planned_end_date` IS NOT NULL
        OR tr.`reinstatement_notes` IS NOT NULL)
        ;
        ");

        $view = new VoltView('ViewTrResinstatementReport', $sql->__toString());

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
            0=>array(0, 'Training Plan Signed - Checked', null, 'WHERE tr.training_plan_signed = "1"'),
            1=>array(1, 'Training Plan Signed - Not Checked', null, 'WHERE tr.training_plan_signed = "0"'));
        $f = new VoltDropDownViewFilter('filter_training_plan_signed', $options, 1, true);
        $f->setDescriptionFormat("Training Plan Signed: %s");
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
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['training_id'] . '&tabRein=1', "small");
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
