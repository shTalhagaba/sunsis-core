<?php
class view_tr_data_mismatch_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewTrDataMismatchReport', 'ViewTrDataMismatchReport');
        if (is_null($view)) {
            $view = $_SESSION['ViewTrDataMismatchReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_tr_data_mismatch_report", "Holding Contract Report");

        if ($subaction == 'export_csv') {
            $view->exportToCSV($link);
            exit;
        }

        require_once('tpl_view_tr_data_mismatch_report.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT
        tr.id AS training_id,
        tr.`firstnames`,
        tr.`surname`,
        tr.`l03` AS learner_reference,
        CASE tr.`dm_reason`
          WHEN 1 THEN 'Dlock_01: no matching UKPRN found'
          WHEN 2 THEN 'Dlock_02: no matching ULN number'
          WHEN 3 THEN 'Dlock_3: no matching standard code found'
          WHEN 4 THEN 'Dlock_4: no matching framework code'
          WHEN 6 THEN 'Dlock_06: no matching pathway'
          WHEN 7 THEN 'Dlock_07: no matching negotiated price'
          WHEN 8 THEN 'Dlock_08: multiple matching records found'
          WHEN 91 THEN 'Dlock_09: no matching start date'
          WHEN 92 THEN 'Dlock_09: after a change of employer'
          WHEN 10 THEN 'Dlock_10: employer has stopped the record'
          WHEN 11 THEN 'Dlock_11: the employer is not a levy payer'
          WHEN 12 THEN 'Dlock_12: employer has paused the commitment'
          ELSE ''
        END AS reason,
        EXTRACTVALUE(tr.`dm_additional_info_comments`, '/Notes/Note[last()]/Comment') AS additional_info_comments,
        CASE
          tr.`dm_assigned_to`
          WHEN '1'
          THEN 'Aneela'
          WHEN '2'
          THEN 'Admin'
	  WHEN 3 THEN 'Tiegan'
          ELSE ''
        END AS assigned_to,
        EXTRACTVALUE(tr.`dm_contact_comment`, '/Notes/Note[last()]/Comment') AS contact_comment,
        DATE_FORMAT(tr.`dm_date_added`, '%d/%m/%Y') AS date_added,
        DATE_FORMAT(
          tr.`dm_date_removed`,
          '%d/%m/%Y'
        ) AS date_removed,
        DATE_FORMAT(
          tr.`dm_funding_month`,
          '%M %Y'
        ) AS funding_month
      FROM
        tr
      WHERE tr.`dm_additional_info_comments` IS NOT NULL
        OR tr.`dm_assigned_to` IS NOT NULL
        OR tr.`dm_contact_comment` IS NOT NULL
        OR tr.`dm_date_added` IS NOT NULL
        OR tr.`dm_date_removed` IS NOT NULL
        OR tr.`dm_reason` IS NOT NULL;
        ");

        $view = new VoltView('ViewTrDataMismatchReport', $sql->__toString());

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
      $f = new VoltDropDownViewFilter('filter_date_removed', $options, 0, false);
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
