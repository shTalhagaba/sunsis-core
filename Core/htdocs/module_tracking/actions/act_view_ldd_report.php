<?php

class view_ldd_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewLddReport', 'ViewLddReport');
        if (is_null($view)) 
        {
            $view = $_SESSION['ViewLddReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->add($link, "do.php?_action=view_ldd_report", "LDD Report");

        if ($subaction == 'export_csv') {
            // $view->exportToCSV($link);
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_ldd_report.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT DISTINCT 
    tr.id AS training_id,    
	tr.`firstnames`, 
	tr.`surname`, 
	(SELECT student_frameworks.`title` FROM student_frameworks WHERE tr_id = tr.`id`) AS apprenticeship_title,
	(SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.`id` = tr.`assessor`) AS assessor,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
	CASE tr_operations.`learner_status`
		WHEN 'A' THEN 'Achieved'
		WHEN 'OBIL' THEN 'Ops BIL'
		WHEN 'FBIL' THEN 'Formal BIL'
		WHEN 'OP' THEN 'On Programme'
		WHEN 'PA' THEN 'PEED - Assessment'
		WHEN 'PC' THEN 'PEED - Coordinator'
		WHEN 'PLM' THEN 'PEED - Learning Mentor'
		WHEN 'GR' THEN 'Gateway Ready'
		WHEN 'F' THEN 'Fail'
		WHEN 'PNDL' THEN 'Pending Leaver'
		ELSE learner_status
	END AS learner_status,
	CASE tr_operations.`ldd`
		WHEN '1' THEN 'Emotional/Behaviour difficulties'
		WHEN '2' THEN 'Multiple difficulties'
		WHEN '4' THEN 'Vision impairment'
		WHEN '5' THEN 'Hearing impairment'
		WHEN '6' THEN 'Disability affecting mobility'
		WHEN '9' THEN 'Mental health difficulty'
		WHEN 'MLD' THEN 'Moderate Learning Difficulty'
		WHEN 'SLD' THEN 'Severe Learning Difficulty'
		WHEN 'DXA' THEN 'Dyslexia'
		WHEN 'DLA' THEN 'Dyscalculia'
		WHEN 'ASD' THEN 'Autism Spectrum Disorder'
		WHEN '15' THEN 'Asperger\'s syndrome'
		WHEN '16' THEN 'Temporary disability after illness'
		WHEN '17' THEN 'Speech, language, and communication needs'
		WHEN '93' THEN 'Other physical disability'
		WHEN '95' THEN 'Other medical condition'
		WHEN '97' THEN 'Other disability'
		WHEN '98' THEN 'Prefer not to say'
		ELSE ldd
	END AS ldd,
	tr_operations.`additional_support`,
	CASE tr_operations.`support_conversation`
        WHEN '1' THEN 'Engaged and Support Required'
        WHEN '2' THEN 'Engaged and No Support Required'
        WHEN '3' THEN 'Did Not Engage'
        ELSE ''
    END AS support_converstaion,
	tr_operations.`epa_reasonable_adjustment`,
	IF (tr_operations.`als_plan` = 1, 'Yes', '') AS als_pan,
	IF (tr_operations.`diagnosis_evidence_required` = 1, 'Yes', '') AS diagnosis_evidence_required,
    EXTRACTVALUE(tr_operations.`lras_comments`, '/Notes/Note[last()]/Comments') AS lras_summary
FROM 
	tr 
	INNER JOIN tr_operations ON tr.`id` = tr_operations.`tr_id`
	INNER JOIN courses_tr ON tr.`id` = courses_tr.`tr_id`
	LEFT JOIN (
		SELECT 
			DISTINCT sunesis_username, DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date, induction_programme.`programme_id`
		FROM 
			inductees 
			INNER JOIN induction ON induction.`inductee_id` = inductees.id 
			INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
		) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE
	tr_operations.`ldd` IS NOT NULL
;
        ");

        $view = new VoltView('ViewLddReport', $sql->__toString());

        $options = array(
            0=>array('SHOW_ALL', 'Show all (Last 8 years only)', null, 'WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 8 YEAR)'),
            1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
            2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
            3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
            4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
            5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
            6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
            7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
        $f = new VoltCheckboxViewFilter('filter_record_status', $options, array('1'));
        $f->setDescriptionFormat("Show: %s");
        $view->addFilter($f);

        $format = "WHERE tr.start_date >= '%s'";
        $f = new VoltDateViewFilter('from_start_date', $format, '');
        $f->setDescriptionFormat("From start date: %s");
        $view->addFilter($f);
        $format = "WHERE tr.start_date <= '%s'";
        $f = new VoltDateViewFilter('to_start_date', $format, '');
        $f->setDescriptionFormat("To start date: %s");
        $view->addFilter($f);

        $format = "HAVING STR_TO_DATE(induction_date, '%d/%m/%Y') >= '%s'";
        $f = new VoltDateViewFilter('from_induction_date', $format, '');
        $f->setDescriptionFormat("From induction date: %s");
        $view->addFilter($f);
        $format = "HAVING STR_TO_DATE(induction_date, '%d/%m/%Y') <= '%s'";
        $f = new VoltDateViewFilter('to_induction_date', $format, '');
        $f->setDescriptionFormat("To induction date: %s");
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
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['training_id'], "small");
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
				
				// Write value rows
				do
				{
					$line = '';
					foreach($row as $field=>$value)
					{
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
