<?php
class ViewSessionsRegisters extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_' . __CLASS__;

        if (!isset($_SESSION[$key])) {

            $sql = new SQLStatement("
SELECT DISTINCT
	sessions.start_date AS lesson_date,
	DATE_FORMAT(sessions.end_date, '%D %b %Y') AS lesson_end_date,
	sessions.start_time AS lesson_start_time,
	sessions.id AS session_id,
	DATE_FORMAT(sessions.start_date, '%a') as `dayofweek`,
	DATE_FORMAT(sessions.start_date, '%D %b %Y') AS `date`,
	sessions.start_time,
	sessions.end_time,
	IF( sessions.start_date < CURRENT_DATE OR (sessions.start_date = CURRENT_DATE AND sessions.end_time <= CURRENT_TIME), -1,
		IF(sessions.start_date = CURRENT_DATE AND (sessions.start_time <= CURRENT_TIME AND sessions.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	sessions.unit_ref,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.`personnel`) AS trainer,
	sessions.`location`,
	sessions.`test_location`,
	CASE sessions.`event_type`
	  WHEN 'CRS' THEN 'Course'
	  WHEN 'DEV' THEN 'Development'
	  WHEN 'EX' THEN 'Exam'
	  WHEN 'MRK' THEN 'Marking'
	  WHEN 'OBS' THEN 'Observations'
	  WHEN 'PRP' THEN 'Preparations'
	  WHEN 'ST' THEN 'Staff training'
	  WHEN 'SUP' THEN 'Support'
	  WHEN 'TM' THEN 'Trainer meeting'
	  WHEN 'WRK' THEN 'Workshop'
	  WHEN 'O' THEN 'Other'
	  ELSE ''
  END AS event_type,
	(SELECT COUNT(*) FROM session_entries WHERE session_entries.`entry_session_id` = sessions.`id`) AS `total`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '1') AS `attendances`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '2') AS `lates`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '3') AS `absences`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '4') AS `attendance_not_required`,
    (SELECT COUNT(*) FROM session_cancellations WHERE session_cancellations.`session_id` = sessions.`id`) AS `cancelled`,
    sessions.max_learners AS max_allowed_learners,
    sessions.best_case 
FROM
	sessions
		");
            $sql->setClause("WHERE sessions.personnel NOT IN (27964)");
	    /*	
            if (array_key_exists($_SESSION['user']->id, InductionHelper::getListOpTrainers($link))) {
                if (!in_array($_SESSION['user']->username, ['aspence1', 'dtroke12', 'opennington', 'nrichardson1', 'dkorsos1', 'elliepearson', 'jridley16', "abalmer1", "crthompson", "ewaterworth", "gemmaholz", "jthompson1", "mborrow1"]))
                    $sql->setClause("WHERE sessions.personnel = '{$_SESSION['user']->id}' ");
            } elseif ($_SESSION['user']->type == User::TYPE_ASSESSOR) {
                if (!in_array($_SESSION['user']->username, ["kristianhudson", "bmoss123", "lbyers12", "abalmer1", "crthompson", "ewaterworth", "gemmaholz", "jthompson1", "mborrow1"])) {
                    $sql->setClause("FROM sessions LEFT JOIN session_entries ON sessions.id = session_entries.`entry_session_id` LEFT JOIN tr ON session_entries.`entry_tr_id` = tr.`id`");
                    $sql->setClause("WHERE tr.assessor = '{$_SESSION['user']->id}'");
                }
            }*/

            $view = $_SESSION[$key] = new ViewSessionsRegisters();
            $view->setSQL($sql->__toString());

            $f = new TextboxViewFilter('filter_session_id', "WHERE sessions.id = '%s%%'", null);
            $f->setDescriptionFormat("ID: %s");
            $view->addFilter($f);

            $d = new DateTime("now");
            $f = new DateRangeViewFilter("filter_date", "sessions.start_date", $d->format("d/m/Y"), $d->format("d/m/Y"));
            $f->setDescriptionFormat("Date: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'Course', null, 'WHERE sessions.`event_type` = "CRS"'), 1 => array('1', 'Development', null, 'WHERE sessions.`event_type` = "DEV"'), 2 => array('2', 'Exam', null, 'WHERE sessions.`event_type` = "EX"'), 3 => array('3', 'Marking', null, 'WHERE sessions.`event_type` = "MRK"'), 4 => array('4', 'Observation', null, 'WHERE sessions.`event_type` = "OBS"'), 5 => array('5', 'Preparations', null, 'WHERE sessions.`event_type` = "PRP"'), 6 => array('6', 'Staff Training', null, 'WHERE sessions.`event_type` = "ST"'), 7 => array('7', 'Support', null, 'WHERE sessions.`event_type` = "SUP"'), 8 => array('8', 'Trainer meeting', null, 'WHERE sessions.`event_type` = "TM"'), 9 => array('9', 'Workshop', null, 'WHERE sessions.`event_type` = "WRK"'), 10 => array('10', 'Other', null, 'WHERE sessions.`event_type` = "O"')
            );
            $f = new DropDownViewFilter('filter_event_type', $options, null, true);
            $f->setDescriptionFormat("Event Type: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT unit_ref, unit_ref, null, CONCAT('WHERE sessions.unit_ref=', CHAR(39), unit_ref, CHAR(39)) FROM sessions ORDER BY unit_ref";
            $f = new DropDownViewFilter('filter_unit_ref', $options, null, true);
            $f->setDescriptionFormat("Unit Ref.: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT test_location, test_location, null, CONCAT('WHERE sessions.test_location=', CHAR(39), test_location, CHAR(39)) FROM sessions WHERE test_location IS NOT NULL AND test_location != '' ORDER BY test_location";
            $f = new DropDownViewFilter('filter_test_location', $options, null, true);
            $f->setDescriptionFormat("Test Location: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ', users.surname), null, CONCAT('WHERE sessions.personnel=', CHAR(39), users.id, CHAR(39)) FROM users INNER JOIN lookup_op_trainers ON users.id = user_id ORDER BY users.firstnames";
            $options = "SELECT DISTINCT sessions.`personnel`, (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = sessions.`personnel`) AS `name`, NULL, CONCAT('WHERE sessions.personnel=', CHAR(39), sessions.personnel, CHAR(39)) FROM sessions WHERE sessions.`personnel` IS NOT NULL HAVING `name` IS NOT NULL ORDER BY `name`;";
            $f = array_key_exists($_SESSION['user']->id, InductionHelper::getListOpTrainers($link)) ? new DropDownViewFilter('filter_trainer', $options, $_SESSION['user']->id, true) : new DropDownViewFilter('filter_trainer', $options, null, true);
            $f->setDescriptionFormat("Trainer: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'Not Completed', null, 'WHERE sessions.`status` = "NC"'), 1 => array('1', 'Completed', null, 'WHERE sessions.`status` = "C"'), 2 => array('2', 'Signed-off', null, 'WHERE sessions.`status` = "S"'), 3 => array('3', 'Not Accepted', null, 'WHERE sessions.`status` = "NA"'), 4 => array('4', 'Resubmitted', null, 'WHERE sessions.`status` = "R"')
            );
            $f = new DropDownViewFilter('filter_reg_status', $options, null, true);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = array(
                array(30, 30, null, null),
                array(50, 50, null, null),
                array(100, 100, null, null),
                array(200, 200, null, null),
                array(0, 'No limit', null, null)
            );
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                array(1, 'Day, start time, unit_ref', null, 'ORDER BY sessions.start_date, sessions.start_time, sessions.unit_ref'),
                array(2, 'Day, unit_ref, start time', null, 'ORDER BY sessions.start_date, sessions.unit_ref, sessions.start_time'),
                array(3, 'Unit Ref, day, start time', null, 'ORDER BY sessions.unit_ref, sessions.start_date, sessions.start_time')
            );
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }

    public function render(PDO $link)
    {//if($_SESSION['user']->username == 'abalmer1') pre($this->getSQL());
        $st = DAO::query($link, $this->getSQL());
        if ($st) {
            echo $this->getViewNavigator();
            echo '<div align="center" ><table class="table table-bordered" id="tblSessionsRegisters" class="table table-striped text-center" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            echo '<th colspan="2">Date</th><th>Unit Ref / Event Type</th><th>Trainer</th><th>Max. Allowed Learners</th><th>Best Case</th><th>Learners Added</th><th>Cancellations</th><th>Location</th><th>Test Location</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                $total = $row['attendances'] + $row['lates'] + $row['absences'];

                // Colour coding
                if (($total > 0) || ($row['attendance_not_required'] > 0)) {
                    $className = "registerCompleted";
                } else {
                    switch ($row['pastpresentfuture']) {
                        case -1:
                            $className = "past";
                            break;

                        case 0:
                            $className = "present";
                            break;

                        case 1:
                            $className = "future";
                            break;

                        default:
                            throw new Exception("Incorrect value for calculated field 'pastpresentfuture'");
                            break;
                    }
                }
                if ($row['total'] == 0)
                    echo '<tr title="No learners have been added to this session.">';
                else
                    echo HTML::viewrow_opening_tag('do.php?_action=edit_session_register&id=' . $row['session_id'], $className);
                echo '<td align="left" title="#' . $row['session_id'] . '">' . HTML::cell($row['dayofweek']) . '</td>';
                echo "<td align=\"left\">{$row['start_time']}&nbsp;&#8209;&nbsp;{$row['end_time']}<br/><div class=\"AttendancePercentage\" style=\"font-size:80%;text-align:center;opacity:0.7\">{$row['date']} - {$row['lesson_end_date']}</div></td>";
                echo '<td align="left">' . HTML::cell($row['unit_ref']) . '<br><div class="AttendancePercentage" style="font-size:80%;text-align:left;opacity:0.7">' . $row['event_type'] . '</div></td>';
                echo '<td align="left">' . HTML::cell($row['trainer']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['max_allowed_learners']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['best_case']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['total']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['cancelled']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['location']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['test_location']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $this->getViewNavigator();
        } else {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

    public function exportToCSV(PDO $link, $columns, $extra = '', $key = '', $where = '')
    {
        $statement = $this->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if ($st) {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="' . $this->getViewName() . '.csv"');
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            echo "Session ID,Session Start Date,Session Start Time,Session End Date,Session End Time,Unit References,Trainer,Max. Allowed Learners,Best Case,Learners Added,Cancellations,Location,Test Location";
            echo "\r\n";

            if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                do {
                    echo $row['session_id'] . ",";
                    echo Date::toShort($row['lesson_date']) . ",";
                    echo $row['lesson_start_time'] . ",";
                    echo Date::toShort($row['lesson_end_date']) . ",";
                    echo $row['end_time'] . ",";
                    echo HTML::csvSafe($row['unit_ref']) . ",";
                    echo $row['trainer'] . ",";
                    echo $row['max_allowed_learners'] . ",";
                    echo $row['best_case'] . ",";
                    echo $row['total'] . ",";
                    echo $row['cancelled'] . ",";
                    echo $row['location'] . ",";
                    echo $row['test_location'];

                    echo "\r\n";
                } while ($row = $st->fetch(PDO::FETCH_ASSOC));
            }
        } else {
            throw new DatabaseException($link, $statement->__toString());
        }
    }
}
