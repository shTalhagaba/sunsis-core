<?php
class view_op_sessions_attendance implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $view = VoltView::getViewFromSession('ViewOpSessionsAttendance', 'ViewOpSessionsAttendance'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewOpSessionsAttendance'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_op_sessions_attendance", "View Op. Sessions Attendance");

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        require_once('tpl_view_op_sessions_attendance.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT DISTINCT
  sessions.`id` AS event_id,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.`personnel`) AS trainer,
  sessions.`event_type`,
  sessions.`start_date`,
  sessions.`end_date`,
  sessions.`start_time`,
  sessions.`end_time`,
  sessions.`max_learners`,
  sessions.`unit_ref`,
  sessions.`num_entries` AS max_learners_allowed,
  sessions.`attendances`,
  sessions.`lates`,
  sessions.`very_lates`,
  sessions.`absences`,
  sessions.`comments`,
  (SELECT CONCAT(firstnames, ' ', surname) FROM tr WHERE tr.id = sessions.`learner_of_week`) AS learner_of_week,
  sessions.`location`,
  sessions.`test_location`,
  sessions.`status`,
  sessions.`best_case`,
  session_entries.`entry_tr_id`,
  session_entries.`entry_id`,
  CONCAT(tr.firstnames, ' ', tr.surname) as learner_name,
  session_entries.`entry_skilsure_check` AS sa_checked,
  session_entries.`entry_op_tracker_status`,
  session_entries.`entry_mock_1`,
  session_entries.`entry_mock_2`,
  session_entries.`entry_mock_3`,
  session_entries.`entry_mock_pass_fail`,
  session_entries.`entry_learner_trainer`,
  session_entries.`entry_comments`
  
FROM
  `sessions`
  LEFT JOIN session_entries ON sessions.`id` = session_entries.`entry_session_id`
  LEFT JOIN session_attendance ON session_entries.`entry_id` = session_attendance.`session_entry_id`
  LEFT JOIN tr ON session_entries.entry_tr_id = tr.id
WHERE
true
#   sessions.id = 9722  and 
#   session_entries.`entry_tr_id` IN (SELECT id FROM tr)
;
		");

        $view = new VoltView('ViewOpSessionsAttendance', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_session_id', "WHERE sessions.id = '%s%%'", null);
        $f->setDescriptionFormat("ID: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'Course', null, 'WHERE sessions.event_type = "CRS"')
            ,1 => array('1', 'Development', null, 'WHERE sessions.event_type = "DEV"')
            ,2 => array('2', 'Exam', null, 'WHERE sessions.event_type = "EX"')
            ,3 => array('3', 'Marking', null, 'WHERE sessions.event_type = "MRK"')
            ,4 => array('4', 'Observations', null, 'WHERE sessions.event_type = "OBS"')
            ,5 => array('5', 'Preparations', null, 'WHERE sessions.event_type = "PRP"')
            ,6 => array('6', 'Staff training', null, 'WHERE sessions.event_type = "ST"')
            ,7 => array('7', 'Support', null, 'WHERE sessions.event_type = "SUP"')
            ,8 => array('8', 'Trainer meeting', null, 'WHERE sessions.event_type = "TM"')
            ,9 => array('9', 'Workshop', null, 'WHERE sessions.event_type = "WRK"')
            ,10 => array('10', 'Other', null, 'WHERE sessions.event_type = "OTH"')
        );
        $f = new VoltDropDownViewFilter('filter_event_type', $options, null, true);
        $f->setDescriptionFormat("Event Type: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'Not Completed', null, 'WHERE sessions.status = "NC"')
            ,1 => array('1', 'Completed', null, 'WHERE sessions.status = "C"')
            ,2 => array('2', 'Signed-off', null, 'WHERE sessions.status = "S"')
            ,3 => array('3', 'Not Accepted', null, 'WHERE sessions.status = "NA"')
            ,4 => array('4', 'Resubmitted', null, 'WHERE sessions.status = "R"')
        );
        $f = new VoltDropDownViewFilter('filter_event_status', $options, null, true);
        $f->setDescriptionFormat("Event Status: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'Newcastle', null, 'WHERE sessions.test_location = "Newcastle"')
            ,1 => array('1', 'Darlington', null, 'WHERE sessions.test_location = "Darlington"')
            ,2 => array('2', 'Birmingham', null, 'WHERE sessions.test_location = "Birmingham"')
            ,3 => array('3', 'Coventry', null, 'WHERE sessions.test_location = "Coventry"')
            ,4 => array('4', 'Luton', null, 'WHERE sessions.test_location = "Luton"')
            ,5 => array('5', 'Nottingham', null, 'WHERE sessions.test_location = "Nottingham"')
            ,6 => array('6', 'Preston', null, 'WHERE sessions.test_location = "Preston"')
            ,7 => array('7', 'Northampton', null, 'WHERE sessions.test_location = "Northampton"')
            ,8 => array('8', 'Manchester', null, 'WHERE sessions.test_location = "Manchester"')
            ,9 => array('9', 'Leeds', null, 'WHERE sessions.test_location = "Leeds"')
            ,10 => array('10', 'Sheffield', null, 'WHERE sessions.test_location = "Sheffield"')
        );
        $f = new VoltDropDownViewFilter('filter_test_location', $options, null, true);
        $f->setDescriptionFormat("Test Location: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT unit_ref, unit_ref, NULL, CONCAT('WHERE FIND_IN_SET(\'', unit_ref, '\', sessions.unit_ref)') FROM op_tracker_units ORDER BY unit_ref";
        $f = new VoltDropDownViewFilter('filter_unit_ref', $options, null, true);
        $f->setDescriptionFormat("Unit Reference: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT users.id, CONCAT(firstnames, ' ', surname), null, CONCAT('WHERE sessions.personnel=',users.id) FROM users INNER JOIN sessions ON users.id = sessions.personnel ORDER BY firstnames";
        $f = new VoltDropDownViewFilter('filter_trainer', $options, null, true);
        $f->setDescriptionFormat("Trainer: %s");
        $view->addFilter($f);

        $format = "WHERE sessions.start_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_start_date', $format, date('Y-m').'-01');
        $f->setDescriptionFormat("From start date: %s");
        $view->addFilter($f);

        $format = "WHERE sessions.start_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_start_date', $format, '');
        $f->setDescriptionFormat("To start date: %s");
        $view->addFilter($f);

        $format = "WHERE sessions.end_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_end_date', $format, '');
        $f->setDescriptionFormat("From end date: %s");
        $view->addFilter($f);

        $format = "WHERE sessions.end_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_end_date', $format, date('Y-m').'-28');
        $f->setDescriptionFormat("To end date: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Sessions Start Date (asc)', null, 'ORDER BY sessions.start_date ASC'),
            1=>array(2, 'Sessions Start Date (desc)', null, 'ORDER BY sessions.start_date DESC'));
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
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
            echo '<div align="center" ><table id="tblLeads" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Event ID</th><th>Status</th><th>Trainer</th><th>Type</th><th>Start</th><th>End</th>';
            echo '<th>Max. Learners Allowed</th><th>Best Case</th><th>Unit Refs</th><th>Location</th><th>Test Location</th><th style="width: 20%;">Comments</th>';
            echo '<th>Learner Name</th><th>Smart Assessor Checked</th>';
            echo '<th>Monday</th>';
            echo '<th>Tuesday</th>';
            echo '<th>Wednesday</th>';
            echo '<th>Thursday</th>';
            echo '<th>Friday</th>';
            echo '<th>Status</th>';
            echo '<th>Mock 1</th>';
            echo '<th>Mock 2</th>';
            echo '<th>Mock 3</th>';
            echo '<th>Mock Pass/Fail</th>';
            echo '<th style="width: 20%;">Learner Comments</th><th>Learner of Week</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            $op_tracker_status = [
                'U' => 'Uploaded',
                'R' => 'Did not attend',
                'RP' => 'Result pending',
                'P' => 'Pass',
                'F' => 'Fail',
                'D' => 'Did not attend',
                'RP' => 'Result pending',
            ];

            $status = InductionHelper::getListSessionRegisterStatus();
            $types = InductionHelper::getListEventTypes();
            
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=edit_session_register&id='.$row['event_id']);
                echo '<td>'.$row['event_id'].'</td>';
                echo isset($status[$row['status']]) ? '<td><label class="label label-info">'.$status[$row['status']].'</label></td>' : '<td>' . $row['status'] . '</td>';
                echo '<td>' . $row['trainer'] . '</td>';
                echo isset($types[$row['event_type']]) ? '<td>' . $types[$row['event_type']] . '</td>' : '<td>' . $row['event_type'] . '</td>';
                echo '<td>' . Date::toShort($row['start_date']) . ' ' . $row['start_time'] . '</td>';
                echo '<td>' . Date::toShort($row['end_date']) . ' ' . $row['end_time'] . '</td>';
                echo '<td>'.$row['max_learners_allowed'].'</td>';
                echo '<td>'.$row['best_case'].'</td>';
                echo '<td>'.$row['unit_ref'].'</td>';
                echo '<td>'.$row['location'].'</td>';
                echo '<td>'.$row['test_location'].'</td>';
                echo '<td class="small">'.$row['comments'].'</td>';
                echo '<td>'.$row['learner_name'].'</td>';
                echo '<td>'.$row['sa_checked'].'</td>';
                
                $session_attendance_data = DAO::getLookupTable($link, "SELECT attendance_day, CASE attendance_code WHEN '1' THEN 'Attended' WHEN '2' THEN 'Late' WHEN '3' THEN 'Absent' WHEN '4' THEN 'N/A' ELSE '' END AS attendance_code FROM session_attendance WHERE session_entry_id = '{$row['entry_id']}';");
                echo isset($session_attendance_data['Monday']) ? '<td>' . $session_attendance_data['Monday'] . '</td>' : '<td></td>';
                echo isset($session_attendance_data['Tuesday']) ? '<td>' . $session_attendance_data['Tuesday'] . '</td>' : '<td></td>';
                echo isset($session_attendance_data['Wednesday']) ? '<td>' . $session_attendance_data['Wednesday'] . '</td>' : '<td></td>';
                echo isset($session_attendance_data['Thursday']) ? '<td>' . $session_attendance_data['Thursday'] . '</td>' : '<td></td>';
                echo isset($session_attendance_data['Friday']) ? '<td>' . $session_attendance_data['Friday'] . '</td>' : '<td></td>';

                echo isset($op_tracker_status[$row['entry_op_tracker_status']]) ? '<td>'.$op_tracker_status[$row['entry_op_tracker_status']].'</td>' : '<td></td>';
                echo '<td>' . $row['entry_mock_1'] . '</td>';
                echo '<td>' . $row['entry_mock_2'] . '</td>';
                echo '<td>' . $row['entry_mock_3'] . '</td>';
                echo '<td>' . $row['entry_mock_pass_fail'] . '</td>';
                echo '<td class="small">'.$row['entry_comments'].'</td>';
                echo '<td>'.$row['learner_of_week'].'</td>';

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

    private function export_csv(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=SessionsAttendance.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo 'Event ID,Status,Trainer,Type,Start,End,Max. Learners Allowed,Best Case,Unit Refs,Location,Test Location,Comments,Learner Name,Smart Assessor Checked,';
            echo 'Monday,';
            echo 'Tuesday,';
            echo 'Wednesday,';
            echo 'Thursday,';
            echo 'Friday,';
            echo 'Status,';
            echo 'Learner Comments,Learner of Week';
            echo "\n";

            $op_tracker_status = [
                'U' => 'Uploaded',
                'R' => 'Did not attend',
                'RP' => 'Result pending',
                'P' => 'Pass',
                'F' => 'Fail',
                'D' => 'Did not attend',
                'RP' => 'Result pending',
            ];

            $status = InductionHelper::getListSessionRegisterStatus();
            $types = InductionHelper::getListEventTypes();

            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo $row['event_id'] . ',';
                echo isset($status[$row['status']]) ? $status[$row['status']] . ',' : $row['status'] . ',';
                echo HTML::csvSafe($row['trainer']) . ',';
                echo isset($types[$row['event_type']]) ? $types[$row['event_type']] . ',' : $row['event_type'] . ',';
                echo Date::toShort($row['start_date']) . ' ' . $row['start_time'] . ',';
                echo Date::toShort($row['end_date']) . ' ' . $row['end_time'] . ',';
                echo $row['max_learners_allowed'] . ',';
                echo HTML::csvSafe($row['best_case']) . ',';
                echo HTML::csvSafe($row['unit_ref']) . ',';
                echo HTML::csvSafe($row['location']) . ',';
                echo HTML::csvSafe($row['test_location']) . ',';
                echo HTML::csvSafe($row['comments']) . ',';
                echo HTML::csvSafe($row['learner_name']) . ',';
                echo $row['sa_checked'] . ',';
                
                $session_attendance_data = DAO::getLookupTable($link, "SELECT attendance_day, CASE attendance_code WHEN '1' THEN 'Attended' WHEN '2' THEN 'Late' WHEN '3' THEN 'Absent' WHEN '4' THEN 'N/A' ELSE '' END AS attendance_code FROM session_attendance WHERE session_entry_id = '{$row['entry_id']}';");
                echo isset($session_attendance_data['Monday']) ? $session_attendance_data['Monday'] . ',' : ',';
                echo isset($session_attendance_data['Tuesday']) ? $session_attendance_data['Tuesday'] . ',' : ',';
                echo isset($session_attendance_data['Wednesday']) ? $session_attendance_data['Wednesday'] . ',' : ',';
                echo isset($session_attendance_data['Thursday']) ? $session_attendance_data['Thursday'] . ',' : ',';
                echo isset($session_attendance_data['Friday']) ? $session_attendance_data['Friday'] . ',' : ',';

                echo isset($op_tracker_status[$row['entry_op_tracker_status']]) ? $op_tracker_status[$row['entry_op_tracker_status']] : ',';
                echo HTML::csvSafe($row['entry_comments']) . ',';
                echo HTML::csvSafe($row['learner_of_week']) . ',';
                echo "\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}