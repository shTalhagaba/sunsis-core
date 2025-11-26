<?php
class ViewCourseLearners extends View
{
    public static function getInstance($link, $course_id, $extra = '')
    {
        $key = 'view_'.__CLASS__.$course_id.$extra;

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<SQL
SELECT
	courses.id AS course_id,
	tr.id AS tr_id,
	contracts.id AS contract_id,
	tr.surname, tr.firstnames, tr.gender, organisations.legal_name AS employer_name, tr.status_code, tr.username,
	users.enrollment_no, tr.l03, tr.uln, contracts.title AS contract_title, locations.full_name, locations.postcode AS lpc,
	(SELECT title FROM training_groups WHERE id = tr.tg_id) AS tg_title,

	(SELECT SUM(student_qualifications.units) FROM student_qualifications WHERE student_qualifications.tr_id = tr.id AND student_qualifications.framework_id!=0) AS units,
	(SELECT SUM(student_qualifications.units) - SUM(student_qualifications.unitsBehind) - SUM(student_qualifications.unitsOnTrack) - SUM(student_qualifications.unitsCompleted) FROM student_qualifications WHERE student_qualifications.tr_id=tr.id AND student_qualifications.framework_id!=0) AS unitsNotStarted,
	(SELECT SUM(student_qualifications.unitsBehind) FROM student_qualifications WHERE student_qualifications.tr_id = tr.id AND student_qualifications.framework_id!=0) AS unitsBehind,
	(SELECT SUM(student_qualifications.unitsOnTrack) FROM student_qualifications WHERE student_qualifications.tr_id = tr.id AND student_qualifications.framework_id!=0) AS unitsOnTrack,
	(SELECT SUM(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)) FROM student_qualifications WHERE student_qualifications.tr_id = tr.id AND student_qualifications.framework_id!=0) AS unitsUnderAssessment,
	(SELECT SUM(student_qualifications.unitsCompleted) FROM student_qualifications WHERE student_qualifications.tr_id = tr.id AND student_qualifications.framework_id!=0) AS unitsCompleted,

	tr.`scheduled_lessons`,
	tr.`registered_lessons`,
	tr.`attendances`,
	tr.`lates`,
	tr.`very_lates`,
	tr.`authorised_absences`,
	tr.`unexplained_absences`,
	tr.`unauthorised_absences`,
	tr.`dismissals_uniform`,
	tr.`dismissals_discipline`,
	(tr.attendances+
	tr.lates+
	tr.very_lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) AS `total`,

	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%math%' AND LOWER(unit_title) LIKE '%level 1%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS maths_l1,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%math%' AND LOWER(unit_title) LIKE '%level 2%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS maths_l2,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l1_read,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l1_write,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l1_speak,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l2_read,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l2_write,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l2_speak,
	
	#IF(
	#(SELECT MAX(DATE) FROM tr_tracking WHERE tr_id = tr.`id`) > (SELECT MAX(actual_date) FROM additional_support WHERE tr_id = tr.id),
	#(SELECT MAX(DATE) FROM tr_tracking WHERE tr_id = tr.`id`),
	#(SELECT MAX(actual_date) FROM additional_support WHERE tr_id = tr.id)
	#) AS last_contact_date
	(SELECT MAX(tr_tracking.`date`) FROM tr_tracking WHERE tr_id = tr.`id`) AS max_tracking_date,
	(SELECT MAX(actual_date) FROM additional_support WHERE tr_id = tr.id) AS max_support_date

FROM
	tr
	LEFT JOIN organisations	ON (tr.employer_id = organisations.id AND organisations.organisation_type = 2)
	LEFT JOIN users ON (users.username = tr.username AND users.type = 5)
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN group_members ON tr.`id` = group_members.`tr_id`
	LEFT JOIN groups ON group_members.`groups_id` = groups.id
ORDER BY
	tr.surname
;
SQL;

            $sql = new SQLStatement($sql);

            $sql->setClause("WHERE courses_tr.course_id = '{$course_id}'");

            $view = $_SESSION[$key] = new ViewCourseLearners();
            $view->setSQL($sql->__toString());

            $f = new TextboxViewFilter('filter_learner_l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("L03: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_learner_firstnames', "WHERE tr.firstnames LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Firstnames: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_learner_surname', "WHERE tr.surname LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $options = array(
                0=>array('SHOW_ALL', 'Show all', null, 'WHERE status_code in (1,2,3,4,5,6,7)'),
                1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new DropDownViewFilter('filter_tr_record_status', $options, 1);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array('0', 'Learners without cohort', null, 'WHERE group_members.`groups_id` IS NULL'),
                1=>array('1', 'Learners without training group ', null, 'WHERE tr.tg_id IS NULL'));
            $f = new DropDownViewFilter('filter_without_cohort_or_tg', $options, null, true);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT
  training_groups.id,
  training_groups.title,
  groups.`title`,
  CONCAT(
    'WHERE tr.tg_id=',
    CHAR(39),
    training_groups.id,
    CHAR(39)
  )

FROM
  training_groups
  INNER JOIN groups
    ON training_groups.`group_id` = groups.`id`
WHERE groups.`courses_id` = '$course_id'
ORDER BY groups.title, training_groups.title
;
SQL;
            $f = new DropDownViewFilter('filter_tg', $options, '');
            $f->setDescriptionFormat("Training Group: %s");
            $view->addFilter($f);

            $options = "SELECT groups.id, groups.title, null, CONCAT('WHERE groups.id=', groups.id) FROM groups WHERE courses_id = '{$course_id}' ORDER BY title";
            $f = new DropDownViewFilter('filter_group', $options, '');
            $f->setDescriptionFormat("Group: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL,
	CONCAT('WHERE tr.coach=', users.id)
FROM
	users
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')
ORDER BY CONCAT(firstnames, ' ', surname)
;
SQL;
            $f = new DropDownViewFilter('filter_coach', $options, null, true);
            $f->setDescriptionFormat("Coach: %s");
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
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $view->setPreference('showFSPassStats', '0');
            $view->setPreference('showAttendanceStats', '0');
            $view->setPreference('showProgressStats', '0');

        }

        return $_SESSION[$key];
    }

    public function render(PDO $link, $course_id, $extra = '')
    {
        $table_id = "tblCourseLearners";
        $show_nav = true;
        if(is_array($extra) && isset($extra['subview']))
        {
            if($extra['subview'] == 'training_group_view')
            {
                $table_id = "tblTGLearners";
                $show_nav = false;
            }
            if($extra['subview'] == 'group_view')
            {
                $table_id = "tblCohortLearners";
                $show_nav = false;
            }
        }
        $st = DAO::query($link, $this->getSQL());
        if($st)
        {
            if($show_nav)
                echo '<div class="well well-sm" style="padding: 1px;">' . $this->getViewNavigator() . '</div>';
            else
                echo '<div class="well well-sm text-center text-bold" style="padding: 1px;">' . $st->rowCount() . ' records</div>';
            echo '<div class="table-responsive">';
            echo '<table id="'.$table_id.'" class="table table-bordered">';
            echo '<thead>';
            echo '<tr class="bg-green">';
            echo '<th colspan="9" class="text-center">Learners Details</th>';
            if($this->getPreference('showFSPassStats') == '1')
            {
                echo '<th colspan="8" class="text-center">FS first time pass stats</th>';
            }
            if($this->getPreference('showAttendanceStats') == '1')
            {
                echo '<th colspan="8" class="text-center">Attendance</th>';
            }
            if($this->getPreference('showProgressStats') == '1')
            {
                echo '<th colspan="5" class="text-center">Progress</th>';
            }
            echo '</tr>';
            echo '<tr class="bottomRow"><th>&nbsp;</th><th>&nbsp;</th>';
            echo '<th>Learner</th><th>Identifiers</th><th title="last date of contact">LDoC</th><th>Employer</th><th>Contract</th>';
            echo '<th>Cohort</th><th>Training Group</th>';
            if($this->getPreference('showFSPassStats') == '1')
            {
                echo '<th>Maths L1</th>';
                echo '<th>Maths L2</th>';
                echo '<th>Eng. R L1</th>';
                echo '<th>Eng. W L1</th>';
                echo '<th>Eng. S L1</th>';
                echo '<th>Eng. R L2</th>';
                echo '<th>Eng. W L2</th>';
                echo '<th>Eng. S L2</th>';
            }
            if($this->getPreference('showAttendanceStats') == '1')
            {
                AttendanceHelper::echoHeaderCells();
            }
            if($this->getPreference('showProgressStats') == '1')
            {
                echo '<th class="ProgressStatistic" style="font-size:6pt;color:gray">Total units</th>';
                echo '<th class="ProgressStatistic" style="font-size:6pt;color:gray">Not started</th>';
                echo '<th class="ProgressStatistic" style="font-size:6pt;color:gray">Behind</th>';
                echo '<th class="ProgressStatistic" style="font-size:6pt;color:gray">On track</th>';
                echo '<th class="ProgressStatistic" style="font-size:6pt;color:gray">Completed</th>';
            }
            echo '</thead><tbody>';
            while($row = $st->fetch())
            {
                echo '<tr>';
                echo '<td>';
                echo '<span class="btn btn-xs btn-info" title="Navigate to the training record screen of this learner" onclick="window.location.href=\'do.php?_action=read_training_record&id=' . $row['tr_id'] . '\'"><i class="fa fa-folder"></i></span> &nbsp; ';
                echo '<span class="btn btn-xs btn-primary" title="View/Edit compliance checklist" onclick="window.location.href=\'do.php?_action=edit_tr_compliance&tr_id=' . $row['tr_id'] . '\'"><i class="fa fa-list"></i></span> &nbsp; ';
                echo '</td>';
                echo '<td title=#'.$row['tr_id'] . '>';
                $folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
                $textStyle = '';
                switch($row['status_code'])
                {
                    case 1:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 2:
                        echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 3:
                    case 6:
                        echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 4:
                        echo "<img src=\"/images/transfer.png\" border=\"0\" alt=\"\" />";
                        break;
                    case 5:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
                        $textStyle = 'text-decoration:line-through;color:gray';
                        break;

                    default:
                        echo '?';
                        break;
                }
                echo '</td>';
                echo "<td align=\"left\" style=\"$textStyle;font-size:100%;\">"
                    . HTML::cell($row['surname'])
                    . '<div style="margin-left:5px;color:gray;font-style:italic;">'
                    . HTML::cell($row['firstnames']) . '</div>';
                echo '</td>';
                echo '<td>';
                echo $row['enrollment_no'] != '' ? "<span class='text-bold'>Enrollment No:</span>&nbsp;{$row['enrollment_no']}<br>" : "";
                echo "<span class='text-bold'>L03:</span>&nbsp;{$row['l03']}<br>";
                echo $row['uln'] != '' ? "<span class='text-bold'>ULN:</span>&nbsp;{$row['uln']}" : "";
                echo '</td>';
                if($row['max_tracking_date'] != '' && $row['max_support_date'] != '')
                {
                    $max_tracking_date = new Date($row['max_tracking_date']);
                    $max_support_date = new Date($row['max_support_date']);
                    if($max_tracking_date->after($max_support_date))
                    {
                        echo '<td class="' . self::getLdocColor($link, $max_tracking_date, $row['tr_id']) . '">';
                        echo $max_tracking_date->formatShort();
                        echo '</td>';
                    }
                    else
                    {
                        echo '<td class="' . self::getLdocColor($link, $max_support_date, $row['tr_id']) . '">';
                        echo $max_support_date->formatShort();
                        echo '</td>';
                    }
                }
                elseif($row['max_tracking_date'] == '')
                {
                    echo '<td class="' . self::getLdocColor($link, $row['max_support_date'], $row['tr_id']) . '">' . Date::toShort($row['max_support_date']) . '</td>';
                }
                elseif($row['max_support_date'] == '')
                {
                    echo '<td class="' . self::getLdocColor($link, $row['max_tracking_date'], $row['tr_id']) . '">' . Date::toShort($row['max_tracking_date']) . '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                echo '<td>' . HTML::cell($row['employer_name']) . '<br> &nbsp; <span class="small"><i class="fa fa-map-marker"></i> ' . HTML::cell($row['full_name'] . ', ' . $row['lpc']) . '</span>' . '</td>';
                echo '<td>' . HTML::cell($row['contract_title']) . '</td>';
                $groups = DAO::getSingleColumn($link, "SELECT groups.`title` FROM groups INNER JOIN group_members ON groups.`id` = group_members.`groups_id` WHERE groups.`courses_id` = '{$row['course_id']}' AND group_members.`tr_id` = '{$row['tr_id']}' ORDER BY groups.title;");
                echo '<td>';
                foreach($groups AS $g)
                    echo "{$g}<br>";
                echo '</td>';
                echo '<td>' . HTML::cell($row['tg_title']) . '</td>';
                if($this->getPreference('showFSPassStats'))
                {
                    echo $row['maths_l1'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['maths_l2'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['eng_l1_read'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['eng_l1_write'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['eng_l1_speak'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['eng_l2_read'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['eng_l2_write'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                    echo $row['eng_l2_speak'] > 0 ? '<td align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td></td>';
                }

                if($this->getPreference('showAttendanceStats'))
                {
                    AttendanceHelper::echoDataCells($row);
                }
                if($this->getPreference('showProgressStats'))
                {
                    $fields = array('units', 'unitsNotStarted', 'unitsBehind', 'unitsOnTrack', 'unitsCompleted');

                    foreach($fields as $field)
                    {
                        if($row[$field] == 0)
                        {
                            if($field == 'units')
                            {
                                echo '<td style="border-left-style:solid">&nbsp;</td>';
                            }
                            else
                            {
                                echo '<td>&nbsp;</td>';
                            }
                        }
                        else
                        {
                            switch($field)
                            {
                                case 'unitsNotStarted':
                                    echo '<td class="TrafficLightAmber" align="center">'.HTML::cell($row[$field]).'</td>';
                                    break;

                                case 'unitsBehind':
                                    echo '<td class="TrafficLightRed" align="center">'.HTML::cell($row[$field]).'</td>';
                                    break;

                                case 'unitsOnTrack':
                                    //case 'unitsUnderAssessment':
                                case 'unitsCompleted':
                                    echo '<td class="TrafficLightGreen" align="center">'.HTML::cell($row[$field]).'</td>';
                                    break;

                                default:
                                    echo '<td align="center">'.HTML::cell($row[$field]).'</td>';
                                    break;
                            }
                        }
                    }
                }
                echo '</tr>';
            }

            echo '</tbody></table></div>';
            if($show_nav)
                echo '<div class="well well-sm" style="padding: 1px;">' . $this->getViewNavigator() . '</div>';
            else
                echo '<div class="well well-sm text-center text-bold" style="padding: 1px;">' . $st->rowCount() . ' records</div>';
        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

    public static function getLdocColor(PDO $link, $review_date, $tr_id)
    {
        $review = DAO::getObject($link, "SELECT * FROM review_forms WHERE date_of_activity = '{$review_date}' AND tr_id = '{$tr_id}'");
        if(isset($review->id))
        {
            if($review->coach_sign == '')
                return 'bg-red';
            if($review->coach_sign != '' && $review->learner_sign == '')
                return 'bg-orange';
            if($review->coach_sign != '' && $review->learner_sign != '' && $review->emp_sign == '')
                return 'bg-green';
        }
    }
}
?>