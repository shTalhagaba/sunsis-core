<?php
class ViewTrackerDetails extends View
{
    public $viewKey = null;

    public static function getInstance($tracker_id = '')
    {
        $key = 'view_'.__CLASS__.$tracker_id;

        $tracker_id_fix = $tracker_id == 18 ? " LIMIT 1" : "";
        if(!isset($_SESSION[$key]))
        {

            $sql = new SQLStatement("
			SELECT DISTINCT
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = tr.assessor) AS assessor,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = tr.coordinator) AS coordinator,
  courses.title AS programme,
  ((DATE_FORMAT(tr.created, '%Y') - DATE_FORMAT(tr.dob, '%Y')) - (DATE_FORMAT(tr.created, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) AS age,
  tr.firstnames,
  tr.surname,
  tr_operations.preferred_name,
  employers.legal_name AS company,
  #(SELECT description FROM lookup_delivery_locations WHERE id = inductees.location_area) AS delivery_location,
  (SELECT DISTINCT lookup_delivery_locations.`description` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  INNER JOIN lookup_delivery_locations ON lookup_delivery_locations.`id` = inductees.`location_area`
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id`
  $tracker_id_fix ) AS delivery_location,
  tr.`home_mobile` AS learner_mobile_number,
  tr.`home_email` AS learner_email,
  DATE_FORMAT(tr_operations.`hour_48_call`,'%d/%m/%Y') AS 48_hour_call,
  #DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 21 DAY),'%d/%m/%Y') AS week_3_call,
  (IF(tr_operations.week_3_call IS NULL, DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 21 DAY),'%d/%m/%Y'), DATE_FORMAT(tr_operations.week_3_call, '%d/%m/%Y'))) AS week_3_call,
  DATE_FORMAT(tr_operations.`moc_on_demand_1`,'%d/%m/%Y') AS moc_on_demand_1,
  DATE_FORMAT(tr_operations.`moc_on_demand_2`,'%d/%m/%Y') AS moc_on_demand_2,
  CASE extractvalue(lar_details, '/Notes/Note[last()]/Type')
    WHEN 'N' THEN 'No'
    WHEN 'Y' THEN 'LAR'
    WHEN 'O' THEN 'Ops LAR'
    WHEN 'S' THEN 'Sales LAR'
    WHEN '' THEN ''
  END AS lar,
  #DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  (SELECT DISTINCT DATE_FORMAT(`induction_date`, '%d/%m/%Y') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS induction_date,
  CASE extractvalue(bil_details, '/Notes/Note[last()]/Type')
    WHEN 'N' THEN 'No'
    WHEN 'Y' THEN 'Yes'
    WHEN '' THEN ''
  END AS `break_in_learning`,
  CASE extractvalue(leaver_details, '/Notes/Note[last()]/Type')
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
  END AS `leaver`,
  additional_support,
   CASE tr_operations.`crc_alert`
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
  END AS `crc_alert`,
  CASE tr_operations.`added_to_lms`
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
  END AS `added_to_lms`,
  tr.id AS tr_id,
  tr.username,
  tr.employer_id,
  tr.gender,
  /*
  CASE inductee_type
  	  WHEN 'NA' THEN 'NB - New Apprentice'
  	  WHEN 'WFD' THEN 'NB - WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'ANEW' THEN 'ACCM - New'
  	  WHEN 'AWFD' THEN 'ACCM - WFD'
  END AS learner_type,
  */
  (SELECT DISTINCT 
  CASE inductee_type
  	  WHEN 'NA' THEN 'NB - New Apprentice'
  	  WHEN 'WFD' THEN 'NB - WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'SSU' THEN 'New Apprentice Client Sourced'
  	  WHEN '3AAA' THEN '3AAA Transfer'
  END
  FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id`
  $tracker_id_fix ) AS learner_type,
  (SELECT DISTINCT 
  CASE induction.webcam
  	  WHEN 'NR' THEN 'Not Required'
  	  WHEN 'S' THEN 'Sent'
  END
  FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id`
  $tracker_id_fix ) AS webcam,
  (SELECT DISTINCT 
  CASE induction.fs_exempt
  	  WHEN 'Y' THEN 'Yes'
  	  WHEN 'N' THEN 'No'
  END
  FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id`
  $tracker_id_fix ) AS fs_exempt,
  '' AS tracker_units
FROM
  op_trackers
  INNER JOIN op_tracker_frameworks ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  INNER JOIN op_tracker_units ON op_trackers.id = op_tracker_units.`tracker_id`
  LEFT JOIN student_frameworks ON op_tracker_frameworks.`framework_id` = student_frameworks.`id`
  LEFT JOIN tr ON student_frameworks.`tr_id` = tr.id
  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
  LEFT JOIN courses ON courses.id = courses_tr.course_id
  LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
  LEFT JOIN tr_operations ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN inductees ON tr.`username` = inductees.`sunesis_username`
  #LEFT JOIN induction ON inductees.`id` = induction.`inductee_id`
  #LEFT JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id`
			");

            $sql->setClause("WHERE tr.id IS NOT NULL");

            $view = $_SESSION[$key] = new ViewTrackerDetails();
            $view->setSQL($sql->__toString());

            $options = array(
                0=>array('SHOW_ALL', 'Show all', null, 'WHERE status_code in (1,2,3,4,5,6,7)'),
                1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new CheckboxViewFilter('filter_record_status', $options, array('1'));
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT organisations.id, legal_name, null, CONCAT('WHERE tr.employer_id=',organisations.id) FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE organisation_type LIKE '%2%' ORDER BY legal_name";
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ', users.surname), null, CONCAT('WHERE tr.assessor=',users.id) FROM users INNER JOIN tr ON users.id = tr.assessor WHERE users.type LIKE '%3%' ORDER BY users.firstnames";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.`firstnames`, ' ', users.`surname`), NULL,  CONCAT('WHERE tr.coordinator=',users.id) FROM users INNER JOIN tr ON users.`id` = tr.`coordinator` ORDER BY users.`firstnames`;";
            $f = new DropDownViewFilter('filter_coordinator', $options, null, true);
            $f->setDescriptionFormat("Coordinator: %s");
            $view->addFilter($f);

            $options = "SELECT id, description, NULL, CONCAT('WHERE inductees.location_area=',id) FROM lookup_delivery_locations ORDER BY description";
            $f = new DropDownViewFilter('filter_dl', $options, null, true);
            $f->setDescriptionFormat("Delivery Location: %s");
            $view->addFilter($f);

            $format = "WHERE tr.created >= '%s'";
            $f = new DateViewFilter('filter_from_tr_creation_date', $format, '');
            $f->setDescriptionFormat("From tr creation date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.created <= '%s'";
            $f = new DateViewFilter('filter_to_tr_creation_date', $format, '');
            $f->setDescriptionFormat("To tr creation date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(induction_date, '%d/%m/%Y') >= '%s'";
            $f = new DateViewFilter('filter_from_induction_date', $format, '');
            $f->setDescriptionFormat("From induction date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(induction_date, '%d/%m/%Y') <= '%s'";
            $f = new DateViewFilter('filter_to_induction_date', $format, '');
            $f->setDescriptionFormat("To induction date: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`hour_48_call` >= '%s'";
            $f = new DateViewFilter('filter_from_48_hour_call', $format, '');
            $f->setDescriptionFormat("From 48 hour call date: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`hour_48_call` <= '%s'";
            $f = new DateViewFilter('filter_to_48_hour_call', $format, '');
            $f->setDescriptionFormat("To 48 hour call date: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`moc_on_demand_1` >= '%s'";
            $f = new DateViewFilter('filter_from_moc_demand_1', $format, '');
            $f->setDescriptionFormat("MOC on demand 1: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`moc_on_demand_1` <= '%s'";
            $f = new DateViewFilter('filter_to_moc_demand_1', $format, '');
            $f->setDescriptionFormat("MOC on demand 1: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`moc_on_demand_2` >= '%s'";
            $f = new DateViewFilter('filter_from_moc_demand_2', $format, '');
            $f->setDescriptionFormat("MOC on demand 2: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`moc_on_demand_2` <= '%s'";
            $f = new DateViewFilter('filter_to_moc_demand_2', $format, '');
            $f->setDescriptionFormat("MOC on demand 2: %s");
            $view->addFilter($f);

            $options = "SELECT id, title, null, CONCAT('WHERE op_trackers.id=', id) FROM op_trackers ORDER BY title";
            $f = new DropDownViewFilter('filter_tracker', $options, '', false);
            $f->setDescriptionFormat("Tracker: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'No', null, 'WHERE tr_operations.crc_alert = "N"')
            ,1 => array('1', 'Yes', null, 'WHERE tr_operations.crc_alert = "Y"')
            );
            $f = new DropDownViewFilter('filter_crc_alert', $options, null, true);
            $f->setDescriptionFormat("CRC Alert: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'No', null, 'HAVING break_in_learning = "No"')
            ,1 => array('1', 'Yes', null, 'HAVING break_in_learning = "Yes"')
            );
            $f = new DropDownViewFilter('filter_break_in_learning', $options, null, true);
            $f->setDescriptionFormat("BIL: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'No', null, 'HAVING lar = "No"')
            ,1 => array('1', 'Yes', null, 'HAVING lar = "Yes"')
            ,2 => array('2', 'Ops LAR', null, 'HAVING lar = "Ops LAR"')
            ,3 => array('3', 'Sales LAR', null, 'HAVING lar = "Sales LAR"')
            );
            $f = new DropDownViewFilter('filter_lar', $options, null, true);
            $f->setDescriptionFormat("LAR: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'No', null, 'WHERE tr_operations.added_to_lms = "N"')
            ,1 => array('1', 'Yes', null, 'WHERE tr_operations.added_to_lms = "Y"')
            );
            $f = new DropDownViewFilter('filter_added_to_lms', $options, null, true);
            $f->setDescriptionFormat("Added to LMS: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'New Apprentice', null, 'WHERE inductees.inductee_type = "NA"')
            ,1 => array('1', 'WFD', null, 'WHERE inductees.inductee_type = "WFD"')
            ,2 => array('2', 'Progression', null, 'WHERE inductees.inductee_type = "P"')
            ,3 => array('3', 'New Apprentice Client Sourced', null, 'WHERE inductees.inductee_type = "SSU"')
            ,4 => array('4', '3AAA Transfer', null, 'WHERE inductees.inductee_type = "3AAA"')
            );
            $f = new DropDownViewFilter('filter_learner_type', $options, null, true);
            $f->setDescriptionFormat("Learner Type: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'No', null, 'HAVING leaver = "No"')
            ,1 => array('1', 'Yes', null, 'HAVING leaver = "Yes"')
            );
            $f = new DropDownViewFilter('filter_leaver', $options, null, true);
            $f->setDescriptionFormat("Leaver: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'No', null, 'WHERE (tr.operations_status = "N" OR tr.operations_status IS NULL)')
            ,1 => array('1', 'Yes', null, 'WHERE tr.operations_status = "Y"')
            );
            $f = new DropDownViewFilter('filter_op_status', $options, 0, true);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Programme Title', null, 'ORDER BY op_trackers.title'),
                1=>array(2, 'Programme Creation Date', null, 'ORDER BY op_trackers.created'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $view->viewKey = $key;
        }

        return $_SESSION[$key];
    }

    public function render(PDO $link, $columns)
    {
        $tracker_id = $this->getFilterValue('filter_tracker');
        $units = DAO::getResultset($link, "SELECT *  FROM op_tracker_units WHERE tracker_id = '{$tracker_id}'", DAO::FETCH_ASSOC);

        $c_sub = 'W' . DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
        $c_year = date('Y')-1;

        $st = DAO::query($link, $this->getSQL());
        if($st)
        {
            $t = InductionHelper::getListSessionEntryCodes();
            echo $this->getViewNavigator();
            echo '<div align="center"><table id="tblLearners" class="table table-bordered">';
            echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th>';

            foreach($columns AS $column)
            {
                echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
            }
            foreach($units AS $u)
            {
                //$u_title = DAO::getSingleValue($link, "SELECT extractvalue(evidences, \"//unit[@op_title='{$u['unit_ref']}']/@title\") AS title FROM framework_qualifications WHERE framework_id = '{$u['framework_id']}' AND REPLACE(id, '/', '') = '{$u['qualification_id']}';");
                //echo trim($u_title) != ''?'<th bgcolor="#add8e6">' . $u_title . '</th>':'<th bgcolor="#add8e6">' . $u['unit_ref'] . '</th>';
                echo '<th bgcolor="#add8e6">' . $u['unit_ref'] . '</th>';
            }

            $sch_options = InductionHelper::getListSchOptions();
            echo '</thead><tbody>';
            while($row = $st->fetch())
            {
                $tr_id = $row['tr_id'];

                $restart = DAO::getSingleValue($link, "SELECT
  extractvalue (
    ilr,
    '/Learner/LearningDelivery[LearnAimRef=\"ZPROG001\"]/LearningDeliveryFAM[LearnDelFAMType=\"RES\"]/LearnDelFAMCode'
  ) AS restart
FROM
  ilr
  INNER JOIN contracts
    ON ilr.`contract_id` = contracts.id
WHERE
  ilr.tr_id = '$tr_id'
  AND contract_year = '$c_year'
  AND submission = '$c_sub'
HAVING restart = '1'
;");
                $class = $restart == '1' ? 'bg-warning' : '';
                //echo HTML::viewrow_opening_tag('do.php?_action=view_edit_op_learner&tr_id=' . $row['tr_id']);
                echo '<tr class="'.$class.'">';
                echo '<td><span class="btn btn-md btn-primary" onclick="window.location.href=\'do.php?_action=view_edit_op_learner&tr_id='.$row['tr_id'].'&tracker_id='.$tracker_id.'\'"><i class="fa fa-folder-open"></i> Open</span> </td>';

                if($row['gender']=='M')
                    echo '<td><img src="/images/boy-blonde-hair.gif" border="0" /></td>';
                elseif($row['gender']=='F')
                    echo '<td><img src="/images/girl-black-hair.gif" border="0" /></td>';
                else
                    echo '<td><img src="/images/blue-person.gif" /></td>';

                foreach($columns as $column)
                {
                    echo '<td>' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
                }

                foreach($units AS $u)
                {
                    $u_ref = $u['unit_ref'];

                    if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
                    {
                        $sql = <<<SQL
SELECT
	IF(session_attendance.`attendance_date` IS NOT NULL, session_attendance.`attendance_date`, sessions.`start_date`) AS attendance_date,
	session_attendance.`attendance_code`
FROM
	sessions
	INNER JOIN session_entries ON sessions.id = session_entries.`entry_session_id`
	LEFT JOIN session_attendance ON session_entries.`entry_id` = session_attendance.`session_entry_id`
WHERE
	FIND_IN_SET('$tracker_id', sessions.`tracker_id`)
	AND session_entries.`entry_tr_id` = '$tr_id'
	AND session_entries.`entry_exam_name` = '$u_ref'
	AND sessions.`event_type` != 'SUP'
ORDER BY
	#sessions.`created` DESC
	sessions.start_date DESC
LIMIT 1
;
SQL;
                    }
                    else
                    {
                        $sql = <<<SQL
SELECT
	#IF(session_attendance.`attendance_date` IS NOT NULL, MIN(session_attendance.`attendance_date`), sessions.`start_date`) AS attendance_date,
	#session_attendance.`attendance_code`
	sessions.start_date AS attendance_date
FROM
	sessions
	INNER JOIN session_entries ON sessions.id = session_entries.`entry_session_id`
	LEFT JOIN session_attendance ON session_entries.`entry_id` = session_attendance.`session_entry_id`
WHERE
	#sessions.`tracker_id` = '$tracker_id'
	#AND sessions.`unit_ref` = '$u_ref'
	FIND_IN_SET('$tracker_id', sessions.`tracker_id`)
	AND
	(FIND_IN_SET('$u_ref', sessions.`unit_ref`)
	OR
	'$u_ref' = sessions.`unit_ref`)
	AND session_entries.`entry_tr_id` = '$tr_id' #ORDER BY attendance_date DESC
	AND sessions.`event_type` != 'SUP'
	ORDER BY sessions.`start_date` DESC LIMIT 1
;
SQL;
                    }
                    $data = DAO::getObject($link, $sql);
                    if(isset($data->attendance_date))
                    {
                        echo '<td>' . Date::toShort($data->attendance_date);
                    }
                    else
                    {
                        echo '<td bgcolor="yellow">';
                    }
                    $sch_details = DAO::getObject($link, "SELECT code, comments FROM op_tracker_unit_sch WHERE tr_id = '{$row['tr_id']}' AND unit_ref = '{$u_ref}' ORDER BY id DESC LIMIT 1");
                    $sch_code = isset($sch_details->code)?$sch_details->code:'';
                    $sch_comments = '';//isset($sch_details->comments)?json_encode($sch_details->comments):'';
                    if($sch_code == "P")
                    {
                        echo isset($sch_options[$sch_code])? '<br>'.$sch_options[$sch_code] . '':'<br>';
                    }
                    else
                    {
                        echo isset($sch_options[$sch_code])? '<br>'.$sch_options[$sch_code] . '<br>':'<br>';
                    }
		    
		    $pft = false;
		            $pnft = '';
                    // 1. for tests only
                    if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
                    {
                        // 2. if current status is pass
                        $current_status_sql = <<<SQL
SELECT
  entry_op_tracker_status
FROM
  session_entries 
WHERE entry_tr_id = '{$row['tr_id']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
                        $current_status = DAO::getSingleValue($link, $current_status_sql);
                        if($current_status ==  "P")
                        {
                            // 3. any failed row
                            $entry_rows = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['tr_id']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
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
                    echo $pft ? ' <span class="text-success text-bold">[RFT]</span><br>' : '';
                    echo $pnft != '' ? ' <span class="text-success text-bold">[' . $pnft.']</span><br>' : '';

                    $_chk = DAO::getSingleValue($link, "SELECT extractvalue(evidences, \"//unit[@op_title='".addslashes((string)$u_ref)."' and @track='true']/@title\") AS chk FROM framework_qualifications INNER JOIN student_frameworks ON (framework_qualifications.framework_id = student_frameworks.id AND student_frameworks.tr_id = '" . $row['tr_id'] . "') INNER JOIN op_tracker_frameworks ON student_frameworks.id = op_tracker_frameworks.framework_id WHERE tracker_id = '" . $tracker_id . "' HAVING chk != '';");
                    if($_chk != '')
                        echo '<span class="btn btn-xs btn-primary" onclick="setSchCode(\'' . $row['tr_id'] . '\', \'' . $u_ref . '\', \'' . $sch_code . '\', \'' . addslashes((string)$row['firstnames']) . ' '. addslashes((string)$row['surname']) . '\', \'' . $sch_comments . '\');">+</span>';
                    echo '</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table></div>';
            echo $this->getViewNavigator();
        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}