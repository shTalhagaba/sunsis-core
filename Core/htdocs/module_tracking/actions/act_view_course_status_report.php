<?php
class view_course_status_report implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->add($link, "do.php?_action=view_course_status_report", "Course Status Report");
        $view = VoltView::getViewFromSession('view_course_status_report', 'view_course_status_report'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['view_course_status_report'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] = 'export')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        include 'tpl_view_course_status_report.php';
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT DISTINCT
  tr.id AS training_id,
  op_trackers.`title` AS programme,
  (SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS employer,
  tr.l03,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.`dob`, '%d/%m/%Y') AS learner_dob,
  sch_table.unit_ref AS course,
  '' AS event_type,
  '' AS trainer,
  '' AS session_start_date,
  '' AS session_end_date,
  '' AS session_start_time,
  '' AS session_end_time,
  '' AS duration_hours,
  '' AS duration_minutes,
  '' AS mock_1,
  '' AS mock_2,
  '' AS mock_3,
  '' AS rft,
  tr_operations.`additional_support`,
  CASE
    sch_table.code
    WHEN 'I' THEN 'Invited'
    WHEN 'B' THEN 'Booked'
    WHEN 'R' THEN 'Required'
    WHEN 'U' THEN 'Uploaded'
    WHEN 'P' THEN 'Pass'
    WHEN 'MC' THEN 'Merit / Credit'
    WHEN 'D' THEN 'Distinction'
    WHEN 'NR' THEN 'Not Required'
    WHEN 'RP' THEN 'Result Pending'
  END AS `code`,
  (SELECT CONCAT(users.`firstnames`, ' ' , users.`surname`) FROM users WHERE users.id = sch_table.created_by) AS created_by,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  DATE_FORMAT(sch_table.`created`,'%d/%m/%Y %H:%i:%s') AS created,
  tr.home_email AS personal_email,
  tr.learner_work_email AS work_email,
  (SELECT contact_email FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS employer_email,
  induction_fields.induction_date,
  sch_table.comments,
  CASE 
    tr_operations.walled_garden
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
    ELSE ''
  END AS registered_on_walled_garden,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor
  
FROM
  (SELECT m1.*
FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2
 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id)
WHERE m2.id IS NULL) AS sch_table
  LEFT JOIN tr ON sch_table.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN tr_operations ON tr.id = tr_operations.tr_id
  LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
  LEFT JOIN  (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = \"N\" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = \"\")
  AND tr.id IS NOT NULL AND op_trackers.id IS NOT NULL
;
		");

        $view = new VoltView('view_course_status_report', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show All', null, null),
            1=>array(1, 'Invited', null, 'WHERE sch_table.code="I"'),
            2=>array(2, 'Booked', null, 'WHERE sch_table.code="B"'),
            3=>array(3, 'Required', null, 'WHERE sch_table.code="R"'),
            4=>array(4, 'Uploaded', null, 'WHERE sch_table.code="U"'),
            5=>array(5, 'Pass', null, 'WHERE sch_table.code="P"'),
            6=>array(6, 'Merit / Credit', null, 'WHERE sch_table.code="MC"'),
            7=>array(7, 'Distinction', null, 'WHERE sch_table.code="D"'),
            8=>array(8, 'Not Required', null, 'WHERE sch_table.code="NR"'),
            9=>array(9, 'Result Pending', null, 'WHERE sch_table.code="RP"')
        );
        $f = new VoltDropDownViewFilter('filter_sch_code', $options, 0, false);
        $f->setDescriptionFormat("Sch Code: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show All', null, null),
            1=>array(1, 'Continuing', null, 'WHERE tr.status_code="1"'),
            2=>array(2, 'Completed', null, 'WHERE tr.status_code="2"'),
            3=>array(3, 'Withdrawn', null, 'WHERE tr.status_code="3"'),
            4=>array(4, 'Temp. Withdrawn', null, 'WHERE tr.status_code="6"'),
        );
        $f = new VoltDropDownViewFilter('filter_tr_status', $options, 1, false);
        $f->setDescriptionFormat("Training Status: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT unit_ref, unit_ref, null, CONCAT('WHERE sch_table.unit_ref=',char(39),unit_ref,char(39)) FROM op_tracker_unit_sch ORDER BY unit_ref";
        $f = new VoltDropDownViewFilter('filter_unit_ref', $options, null, true);
        $f->setDescriptionFormat("Unit Ref: %s");
        $view->addFilter($f);

        $format = "WHERE sch_table.created >= '%s'";
        $f = new VoltDateViewFilter('filter_from_sch_date_created', $format, '');
        $f->setDescriptionFormat("From: %s");
        $view->addFilter($f);

        $format = "WHERE sch_table.created <= '%s'";
        $f = new VoltDateViewFilter('filter_to_sch_date_created', $format, '');
        $f->setDescriptionFormat("To: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show All', null, null),
            1=>array(1, 'Only Test', null, 'WHERE sch_table.unit_ref LIKE "% Test"'),
            2=>array(2, 'Without Test', null, 'WHERE sch_table.unit_ref NOT LIKE "% Test"')
        );
        $f = new VoltDropDownViewFilter('filter_test_units', $options, 0, false);
        $f->setDescriptionFormat("Test Unit: %s");
        $view->addFilter($f);

	$options = "SELECT id, title, NULL, CONCAT('WHERE op_trackers.id=',CHAR(39),id,CHAR(39)) FROM op_trackers ORDER BY title";
        $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
        $f->setDescriptionFormat("Tracker: %s");
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

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblLearners" class="table table-striped table-bordered text-center" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            foreach($columns AS $column)
            {
                echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo '<tr>';
                foreach($columns AS $column)
                {
                    $sql = new SQLStatement("
SELECT 
    sessions.start_date AS session_start_date, sessions.end_date AS session_end_date, sessions.event_type, sessions.personnel, 
    sessions.start_time AS session_start_time, sessions.end_time AS session_end_time,
	TIMESTAMPDIFF(HOUR, CONCAT(sessions.`start_date`, ' ', sessions.`start_time`), CONCAT(sessions.`end_date`, ' ', sessions.`end_time`)) AS duration_hours,
    LEFT(SUBSTRING_INDEX(TIMESTAMPDIFF(MINUTE, CONCAT(sessions.`start_date`, ' ', sessions.`start_time`), CONCAT(sessions.`end_date`, ' ', sessions.`end_time`))/60, '.',-1)*60, 2) AS duration_minutes,
    session_entries.entry_mock_1 AS mock_1, session_entries.entry_mock_2 AS mock_2, session_entries.entry_mock_3 AS mock_3
FROM sessions 
    INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
                    ");
                    $sql->setClause("WHERE session_entries.entry_tr_id = '{$row['training_id']}'");
                    $sql->setClause("ORDER BY sessions.`start_date` DESC");
                    $sql->setClause("LIMIT 1");

                    if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                    {
                        $sql->setClause("WHERE session_entries.`entry_exam_name` = '{$row['course']}'");
                    }
                    else
                    {
                        $sql->setClause("WHERE FIND_IN_SET('{$row['course']}', unit_ref)");
                    }
                    $session_details = DAO::getObject($link, $sql);
                    if(!isset($session_details->session_start_date))
                    {
                        $session_details = new stdClass();
                        $session_details->event_type = null;
                        $session_details->personnel = null;
                        $session_details->session_start_date = null;
                        $session_details->session_end_date = null;
                        $session_details->session_start_time = null;
                        $session_details->session_end_time = null;
                        $session_details->duration_hours = null;
                        $session_details->duration_minutes = null;
                        $session_details->mock_1 = null;
                        $session_details->mock_2 = null;
                        $session_details->mock_3 = null;
                    }

                    if($column == 'comments' || $column == 'additional_support')
                        echo '<td class="small">' . HTML::nl2p($row[$column]) . '</td>';
                    elseif($column == 'session_start_date')
                    {
                        echo '<td>' . Date::toShort($session_details->session_start_date) . '</td>';
                    }
                    elseif($column == 'session_end_date')
                    {
                        echo '<td>' . Date::toShort($session_details->session_end_date) . '</td>';
                    }
                    elseif($column == 'event_type')
                    {
                        $event_types = InductionHelper::getListEventTypes();
                        echo isset($event_types[$session_details->event_type]) ? '<td>' . $event_types[$session_details->event_type] . '</td>' : '<td></td>';
                    }
                    elseif($column == 'trainer')
                    {
                        echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$session_details->personnel}'") . '</td>';
                    }
                    elseif(in_array($column, ["session_start_time", "session_end_time", "duration_hours", "duration_minutes", "mock_1", "mock_2", "mock_3"]))
                    {
                        echo '<td>' . $session_details->$column . '</td>';
                    }
                    elseif($column == 'rft')
                    {
                        $pft = false;
                        $pnft = '';
                        $u_ref = $row['course'];
                        // 1. for tests only
                        if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
                        {
                            // 2. if current status is pass
                            $current_status_sql = <<<SQL
SELECT
  entry_op_tracker_status
FROM
  session_entries 
WHERE entry_tr_id = '{$row['training_id']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
                            $current_status = DAO::getSingleValue($link, $current_status_sql);
                            if($current_status ==  "P")
                            {
                                // 3. any failed row
                                $entry_rows = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['training_id']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
                                if($entry_rows == 0)
                                {
                                    $pft = true;
                                }
                                else
                                {
                                    $pnft = intval($entry_rows)+1;
                                }
                            }
                        }
                        echo '<td>';
                        echo $pft ? 'RFT' : '';
                        echo $pnft;
                        echo '</td>';
                    }
                    else
                        echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }

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

    public function exportToCSV(PDO $link, VoltView $view)
    {
	set_time_limit(0);

        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=CourseStatusReport.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            foreach($columns AS $column)
            {
                echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
            }

            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                foreach($columns AS $column)
                {
                    $sql = new SQLStatement("
SELECT 
    sessions.start_date AS session_start_date, sessions.end_date AS session_end_date, sessions.event_type, sessions.personnel, 
    sessions.start_time AS session_start_time, sessions.end_time AS session_end_time,
	TIMESTAMPDIFF(HOUR, CONCAT(sessions.`start_date`, ' ', sessions.`start_time`), CONCAT(sessions.`end_date`, ' ', sessions.`end_time`)) AS duration_hours,
    LEFT(SUBSTRING_INDEX(TIMESTAMPDIFF(MINUTE, CONCAT(sessions.`start_date`, ' ', sessions.`start_time`), CONCAT(sessions.`end_date`, ' ', sessions.`end_time`))/60, '.',-1)*60, 2) AS duration_minutes,
    session_entries.entry_mock_1 AS mock_1, session_entries.entry_mock_2 AS mock_2, session_entries.entry_mock_3 AS mock_3 
FROM sessions 
    INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
                    ");
                    $sql->setClause("WHERE session_entries.entry_tr_id = '{$row['training_id']}'");
                    $sql->setClause("ORDER BY sessions.`start_date` DESC");
                    $sql->setClause("LIMIT 1");

                    if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                    {
                        $sql->setClause("WHERE session_entries.`entry_exam_name` = '{$row['course']}'");
                    }
                    else
                    {
                        $sql->setClause("WHERE FIND_IN_SET('{$row['course']}', unit_ref)");
                    }
                    $session_details = DAO::getObject($link, $sql);
                    if(!isset($session_details->session_start_date))
                    {
                        $session_details = new stdClass();
                        $session_details->event_type = null;
                        $session_details->personnel = null;
                        $session_details->session_start_date = null;
                        $session_details->session_end_date = null;
                        $session_details->session_start_time = null;
                        $session_details->session_end_time = null;
                        $session_details->duration_hours = null;
                        $session_details->duration_minutes = null;
                        $session_details->mock_1 = null;
                        $session_details->mock_2 = null;
                        $session_details->mock_3 = null;
                    }

                    if($column == 'comments' || $column == 'additional_support')
                        echo HTML::csvSafe($row[$column]) . ',';
                    elseif($column == 'session_start_date')
                    {
                        echo Date::toShort($session_details->session_start_date) . ',';
                    }
                    elseif($column == 'session_end_date')
                    {
                        echo Date::toShort($session_details->session_end_date) . ',';
                    }
                    elseif($column == 'event_type')
                    {
                        $event_types = InductionHelper::getListEventTypes();
                        echo isset($event_types[$session_details->event_type]) ? HTML::csvSafe($event_types[$session_details->event_type]) . ',' : ',';
                    }
                    elseif($column == 'trainer')
                    {
                        echo HTML::csvSafe(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$session_details->personnel}'")) . ',';
                    }
                    elseif(in_array($column, ["session_start_time", "session_end_time", "duration_hours", "duration_minutes", "mock_1", "mock_2", "mock_3"]))
                    {
                        echo $session_details->$column . ',';
                    }
                    elseif($column == 'rft')
                    {
                        $pft = false;
			$pnft = '';
                        $u_ref = $row['course'];
                        // 1. for tests only
                        if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
                        {
                            // 2. if current status is pass
                            $current_status_sql = <<<SQL
SELECT
  entry_op_tracker_status
FROM
  session_entries 
WHERE entry_tr_id = '{$row['training_id']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
                            $current_status = DAO::getSingleValue($link, $current_status_sql);
                            if($current_status ==  "P")
                            {
                                // 3. any failed row
                                $entry_rows = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['training_id']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
                                if($entry_rows == 0)
                                {
                                    $pft = true;
                                }
				else
                                {
                                    $pnft = intval($entry_rows)+1;
                                }
                            }
                        }
                        echo $pft ? 'RFT' : '';
                        echo $pnft;
                        echo ',';
                    }
                    else
                        echo ((isset($row[$column]))?(($row[$column]=='') ? '' : HTML::csvSafe($row[$column])):'') . ',';
                }

                echo "\r\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

}