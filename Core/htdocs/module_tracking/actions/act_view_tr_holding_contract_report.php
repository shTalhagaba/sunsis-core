<?php
class view_tr_holding_contract_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewTrHoldingContractReport', 'ViewTrHoldingContractReport');
        if (is_null($view)) {
            $view = $_SESSION['ViewTrHoldingContractReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_tr_holding_contract_report", "Holding Contract Report");

        if ($subaction == 'export_csv') {
            $view->exportToCSV($link);
            exit;
        }

        require_once('tpl_view_tr_holding_contract_report.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT
        tr.id AS training_id,
        tr.`firstnames`,
        tr.`surname`,
        tr.`l03` AS learner_reference,
	(SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer,
	(SELECT student_frameworks.title FROM student_frameworks WHERE student_frameworks.tr_id = tr.id) AS programme,
        (SELECT
          CONCAT(firstnames, ' ', surname)
        FROM
          users
        WHERE users.id = tr.`hc_processed_by`) AS processed_by,
        CASE
          tr.`hc_reason`
          WHEN '1'
          THEN 'Application to be approved'
          WHEN '2'
          THEN 'Levy Application to be made'
          WHEN '3'
          THEN 'Application overlap'
          WHEN '4'
          THEN 'Other'
          WHEN '5'
          THEN 'Data Mismatch'
	  WHEN '6'
          THEN 'Reinstatement'
          ELSE ''
        END AS reason,
        EXTRACTVALUE(tr.`hc_additional_info_comments`, '/Notes/Note[last()]/Comment') AS additional_info_comments,
        CASE
          tr.`hc_assigned_to`
          WHEN '1'
          THEN 'Aneela'
          WHEN '2'
          THEN 'ARM'
	  WHEN 3 THEN 'Tiegan'
          ELSE ''
        END AS assigned_to,
        EXTRACTVALUE(tr.`hc_contact_comment`, '/Notes/Note[last()]/Comment') AS contact_comment,
        DATE_FORMAT(tr.`hc_date_added`, '%d/%m/%Y') AS date_added,
        DATE_FORMAT(
          tr.`hc_date_removed`,
          '%d/%m/%Y'
        ) AS date_removed,
        induction_fields.induction_date,
        tr.`hc_stage`,
        DATE_FORMAT(
          tr.`hc_funding_month`,
          '%M %Y'
        ) AS funding_month
      FROM
        tr
	INNER JOIN courses_tr ON tr.id = courses_tr.`tr_id`
        LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, 
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  inductees.id AS inductee_id
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
      WHERE true AND (tr.`hc_additional_info_comments` IS NOT NULL
        OR tr.`hc_assigned_to` IS NOT NULL
        OR tr.`hc_contact_comment` IS NOT NULL
        OR tr.`hc_date_added` IS NOT NULL
        OR tr.`hc_processed_by` IS NOT NULL
        OR tr.`hc_reason` IS NOT NULL)
        ");

        $view = new VoltView('ViewTrHoldingContractReport', $sql->__toString());

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
          0=>array('0', 'Show All', null, null),
          1=>array('1', 'Date Removed Blank', null, 'HAVING (date_removed = "" OR date_removed IS NULL) '),
          2=>array('2', 'Date Removed Not Blank', null, 'HAVING date_removed != ""' ));
      $f = new VoltDropDownViewFilter('filter_date_removed', $options, 2, false);
      $f->setDescriptionFormat("Date Removed: %s");
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
