<?php

class view_caseload_management_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewCaseloadManagementReport', 'ViewCaseloadManagementReport');
        if (is_null($view)) {
            $view = $_SESSION['ViewCaseloadManagementReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->add($link, "do.php?_action=view_caseload_management_report", "Caseload Management Report");

        if ($subaction == 'export_csv') {
            //$view->exportToCSV($link);
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_caseload_management_report.php');
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
        (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS assessor,
        caseload_management.status,        
        DATE_FORMAT(caseload_management.initial_date_raised, '%d/%m/%Y') AS initial_date_raised,        
        DATE_FORMAT(caseload_management.pm_revisit_date_agreed, '%d/%m/%Y') AS pm_revisit_date_agreed,        
        DATE_FORMAT(caseload_management.peed_agreed_recommended_date, '%d/%m/%Y') AS peed_agreed_recommended_date,        
        caseload_management.root_cause,        
	caseload_management.risk_summary,
        caseload_management.action_plan,
        IF(caseload_management.bil = 1, 'Yes', '') AS BIL,
        IF(caseload_management.reinstated = 1, 'Yes', '') AS reinstated,
        DATE_FORMAT(caseload_management.closed_date, '%d/%m/%Y') AS closed_date,        
        caseload_management.destination,
        DATE_FORMAT(caseload_management.leaver_decision_made, '%d/%m/%Y') AS leaver_decision_made,        
        caseload_management.leaver_reason,
        caseload_management.positive_outcome,
        IF(caseload_management.potential_return = 1, 'Yes', '') AS potential_return,
        IF(caseload_management.previous_leaver = 1, 'Yes', '') AS previous_leaver_reinstatement,
        (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = caseload_management.created_by) AS created_by,
        (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = caseload_management.last_updated_by) AS last_updated_by,
        DATE_FORMAT(caseload_management.created_at, '%d/%m/%Y %H:%i:%s') AS created_at,
        caseload_management.leaver_note,
        DATE_FORMAT(caseload_management.added_to_bil_date, '%d/%m/%Y') AS added_to_bil_date,
        IF(caseload_management.sales_lar = 1, 'Yes', '') AS talent_pool,
        DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS learning_start_date,
        DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS learning_end_date,
        IF(caseload_management.audited = 1, 'Yes', '') AS audited,
        IF(caseload_management.right_candidate = 1, 'Yes', '') AS right_candidate,
        IF(caseload_management.right_employer = 1, 'Yes', '') AS right_employer,
        IF(caseload_management.right_support = 1, 'Yes', '') AS right_support,
        CASE caseload_management.sbi_recommed
            WHEN 'P' THEN 'Positive'
            WHEN 'D' THEN 'Developmental'
            WHEN 'P,D' THEN 'Positive; Developmental'
            ELSE ''
        END AS sbi_recommeded,
        caseload_management.auditor_notes AS auditor_notes,
        caseload_management.actions_required,
        DATE_FORMAT(caseload_management.bh_consultation_start, '%d/%m/%Y') AS bh_consultation_start,
        DATE_FORMAT(caseload_management.bh_revisit, '%d/%m/%Y') AS bh_revisit,
        DATE_FORMAT(caseload_management.bh_consultation_closed, '%d/%m/%Y') AS bh_consultation_closed,
        IF(caseload_management.bh_shortlist = 1, 'Yes', '') AS bh_shortlist,
        DATE_FORMAT(caseload_management.sales_lars_expiry, '%d/%m/%Y') AS sales_lars_expiry,
        (SELECT CONCAT(managers.firstnames, ' ', managers.surname) FROM users AS managers WHERE managers.username IN (SELECT assessors.supervisor FROM users AS assessors WHERE assessors.id = tr.assessor) ) AS manager,
        DATE_FORMAT(caseload_management.added_to_leaver_date, '%d/%m/%Y') AS added_to_leaver_date,
        CASE caseload_management.risk_origin
            WHEN 1 THEN 'In-session: Onboarding'
            WHEN 2 THEN 'In-session: Training'
            WHEN 3 THEN 'In-session: Review'
            WHEN 4 THEN 'In-session: Support'
            WHEN 5 THEN 'Raised to ARM'
            WHEN 6 THEN 'Raised to Coach'
            WHEN 7 THEN 'Raised to Recruitment'
            WHEN 8 THEN 'Raised to Coordinators'
            WHEN 9 THEN 'Survey'
            WHEN 10 THEN 'Other'
        END AS risk_origin,
        DATE_FORMAT(tr.`dob`, '%d/%m/%Y') AS dob,
        ((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age

      FROM
        tr
        INNER JOIN 

	(SELECT m1.* FROM caseload_management m1 LEFT JOIN caseload_management m2 ON (m1.tr_id = m2.tr_id AND m1.id < m2.id) WHERE m2.id IS NULL) AS caseload_management
	
	 ON tr.id = caseload_management.tr_id

        ;
        ");

        $view = new VoltView('ViewCaseloadManagementReport', $sql->__toString());

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
            1=>array(1, 'Only BILs', null, 'WHERE caseload_management.bil = "1" AND (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ("Leaver", "Direct Leaver - No intervention"))'));
        $f = new VoltDropDownViewFilter('filter_bil', $options, 0, false);
        $f->setDescriptionFormat("BIL: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Only Previous Leaver - Reinstatement', null, 'WHERE caseload_management.previous_leaver = "1"'));
        $f = new VoltDropDownViewFilter('filter_previous_leaver', $options, 0, false);
        $f->setDescriptionFormat("Previous Leaver - Reinstatement: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Low Risk', null, 'WHERE caseload_management.status IN ("Low Risk")'),
            2=>array(2, 'Medium Risk', null, 'WHERE caseload_management.status IN ("Medium Risk")'),
            3=>array(3, 'High Risk', null, 'WHERE caseload_management.status IN ("High Risk")'),
	    4=>array(4, 'High Risk (excl. leavers)', null, 'WHERE caseload_management.status IN ("High Risk") AND (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ("Leaver", "Direct Leaver - No intervention"))'),
            5=>array(5, 'High Risk (no closed date)', null, 'WHERE caseload_management.status IN ("High Risk") AND (caseload_management.`closed_date` IS NULL)'),
        );
        $f = new VoltDropDownViewFilter('filter_status', $options, 0, false);
        $f->setDescriptionFormat("Status: %s");
        $view->addFilter($f);

	$options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Yes', null, 'WHERE caseload_management.status IS NOT NULL AND caseload_management.closed_date IS NULL AND (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ("Leaver", "Direct Leaver - No intervention")) '),
        );
        $f = new VoltDropDownViewFilter('filter_caseload_risk', $options, 0, false);
        $f->setDescriptionFormat("Caseload Risk: %s");
        $view->addFilter($f);

	$options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(
                1, 
                'Only Sales LAR', 
                null, 
                'WHERE caseload_management.sales_lar = "1" AND 
                caseload_management.`closed_date` IS NULL AND 
                caseload_management.`tr_id` IN (SELECT id FROM tr WHERE tr.`status_code` IN (1, 6)) AND 
                (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ("Leaver", "Direct Leaver - No intervention"))'
            ));
        $f = new VoltDropDownViewFilter('filter_sales_lar', $options, 0, false);
        $f->setDescriptionFormat("Talent Pool: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Leaver OR Direct Leaver - No Intervention', null, 'WHERE caseload_management.destination IN ("Leaver", "Direct Leaver - No intervention")'),
            2=>array(2, 'Continuing - No concern', null, 'WHERE caseload_management.destination = "Continuing - No concern"'),
            3=>array(3, 'Continuing - Monitoring', null, 'WHERE caseload_management.destination = "Continuing - Monitoring"'),
            4=>array(4, 'Continuing - LRAS', null, 'WHERE caseload_management.destination = "Continuing - LRAS"'),
            5=>array(5, 'EPA Ready', null, 'WHERE caseload_management.destination = "EPA Ready"'),
            6=>array(6, 'Leaver', null, 'WHERE caseload_management.destination = "Leaver"'),	
            7=>array(7, 'Direct Leaver - No intervention', null, 'WHERE caseload_management.destination = "Direct Leaver - No intervention"'),
        );
        $f = new VoltDropDownViewFilter('filter_destination', $options, 0, false);
        $f->setDescriptionFormat("Destination: %s");
        $view->addFilter($f);

	$format = "WHERE tr.closure_date >= '%s'";
        $f = new VoltDateViewFilter('from_learning_end_date', $format, '');
        $f->setDescriptionFormat("From learning end date: %s");
        $view->addFilter($f);
        $format = "WHERE tr.closure_date <= '%s'";
        $f = new VoltDateViewFilter('to_learning_end_date', $format, '');
        $f->setDescriptionFormat("To learning end date: %s");
        $view->addFilter($f);

	$format = "WHERE caseload_management.closed_date >= '%s'";
        $f = new VoltDateViewFilter('from_closed_date', $format, '');
        $f->setDescriptionFormat("From closed date: %s");
        $view->addFilter($f);
        $format = "WHERE caseload_management.closed_date <= '%s'";
        $f = new VoltDateViewFilter('to_closed_date', $format, '');
        $f->setDescriptionFormat("To closed date: %s");
        $view->addFilter($f);

        $format = "WHERE caseload_management.added_to_bil_date >= '%s'";
        $f = new VoltDateViewFilter('from_added_to_bil_date', $format, '');
        $f->setDescriptionFormat("From added to bil date: %s");
        $view->addFilter($f);
        $format = "WHERE caseload_management.added_to_bil_date <= '%s'";
        $f = new VoltDateViewFilter('to_added_to_bil_date', $format, '');
        $f->setDescriptionFormat("To added to bil date: %s");
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

	    $list_root_cause = InductionHelper::getListLeaverMotive();	
            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['training_id'] . '&tabClm=1', "small");
                foreach ($columns as $column) {
                    if($column == "root_cause")
                    {
                        echo isset($list_root_cause[$row['root_cause']]) ? '<td>' . $list_root_cause[$row['root_cause']] . '</td>' : '<td></td>';
                    }
                    else
                    {
                        echo isset($row[$column]) ? '<td>' . $row[$column] . '</td>' : '<td></td>';
                    }
                }
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    public function exportToCSV(PDO $link, VoltView $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $view->getViewName() . '.csv"');
			
			// Internet Explorer requires two extra headers when downloading files over HTTPS
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			
			if($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				// Write header row
				$line = '';
				foreach($row as $field=>$value)
				{
					if(strlen($line) > 0)
					{
						$line .= ',';
					}
					$line .= '"' . str_replace('"', '""', $field) . '"';
				}
				echo $line . "\r\n";
				
                $list_root_cause = InductionHelper::getListLeaverMotive();
				// Write value rows
				do
				{
					$line = '';
					foreach($row as $field=>$value)
					{
                        if($field == "root_cause")
                        {
                            $value = isset($list_root_cause[$row['root_cause']]) ? $list_root_cause[$row['root_cause']] : '';
                        }

						if(strlen($line) > 0)
						{
							$line .= ',';
						}
						$line .= '"' . str_replace('"', '""', $value) . '"';
					}
					echo $line . "\r\n";
				} while($row = $st->fetch(PDO::FETCH_ASSOC));
				
			}
			
		}
		else
		{
			throw new DatabaseException($link, $statement->__toString());
		}		
	}
}
