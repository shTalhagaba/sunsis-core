<?php
class view_coach_dashboard_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_coach_dashboard_report", "Coach Dashboard Report");

		$view = VoltView::getViewFromSession('ViewCoachDashboardReport', 'ViewCoachDashboardReport'); /* @var $view VoltView */
		if(is_null($view))
		{
			$view = $_SESSION['ViewCoachDashboardReport'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		include_once('tpl_view_coach_dashboard_report.php');
	}

	public function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
    CONCAT(coaches.`firstnames`, ' ', coaches.`surname`) AS coach,
    employers.`legal_name` AS employer,
    tr.`l03`,
    tr.`uln`,
    CONCAT(tr.`firstnames`, ' ', tr.`surname`) AS learner_name,
    tr.status_code,
    DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
    DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
    '' AS progress_percentage,
    '' AS fs_english_level_1,
    '' AS fs_english_level_1_reg_number,
    '' AS fs_english_level_1_result,
    '' AS fs_english_level_2,
    '' AS fs_english_level_2_reg_number,
    '' AS fs_english_level_2_result,
    '' AS fs_maths_level_1,
    '' AS fs_maths_level_1_reg_number,
    '' AS fs_maths_level_1_result,
    '' AS fs_maths_level_2,
    '' AS fs_maths_level_2_reg_number,
    '' AS fs_maths_level_2_result,
    tr.otj_hours AS otj_total,
    '' AS otj_stats,
    '' AS ldoc,
    '' AS number_of_months_missed_contact_since_start_date,
    (SELECT DATE_FORMAT(meeting_date, '%d/%m/%Y') FROM assessor_review WHERE tr_id = tr.id ORDER BY meeting_date DESC LIMIT 1) AS last_review,
    (SELECT DATE_FORMAT(due_date, '%d/%m/%Y') FROM assessor_review WHERE tr_id = tr.id AND id > (SELECT id FROM assessor_review WHERE tr_id = tr.id AND meeting_date != '0000-00-00' ORDER BY meeting_date DESC LIMIT 1) ORDER BY id ASC LIMIT 1) AS next_review_due,
    IF(tr.status_code = 1, REPLACE(DATEDIFF(tr.`target_date`, CURDATE()), '-', '+'), '')  AS number_of_days_to_end_date,
    (SELECT MAX(tr_tracking.`date`) FROM tr_tracking WHERE tr_id = tr.`id`) AS max_tracking_date,
    (SELECT MAX(actual_date) FROM additional_support WHERE tr_id = tr.id) AS max_support_date,
    tr.id AS tr_id,
    courses_tr.course_id
FROM
    tr
    LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
    LEFT JOIN users AS coaches ON tr.`coach` = coaches.`id`
    LEFT JOIN organisations AS employers ON tr.`employer_id` = employers.`id`
;
		");
		$view = new VoltView('ViewCoachDashboardReport', $sql->__toString());

        $options = "SELECT DISTINCT id, CONCAT(users.firstnames, ' ', users.surname, ' - ', users.username), null, CONCAT('WHERE coaches.id=', id) FROM users WHERE users.id IN (SELECT DISTINCT coach FROM tr) ORDER BY users.firstnames";
        $f = new VoltDropDownViewFilter('filter_coach', $options, null, true);
        $f->setDescriptionFormat("Coach: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE courses_tr.course_id=', id) FROM courses WHERE courses.id IN (SELECT DISTINCT course_id FROM courses_tr) ORDER BY courses.title";
        $f = new VoltDropDownViewFilter('filter_course', $options, null, true);
        $f->setDescriptionFormat("Course: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT id, legal_name, null, CONCAT('WHERE employers.id=', id) FROM organisations WHERE id IN (SELECT DISTINCT employer_id FROM tr) ORDER BY legal_name";
        $f = new VoltDropDownViewFilter('filter_employer', $options, null, true);
        $f->setDescriptionFormat("Employer: %s");
        $view->addFilter($f);

        $options = [
            0 => [1, 'Continuing Learners', null, 'WHERE tr.status_code = "1"'],
            1 => [2, 'Completed Learners', null, 'WHERE tr.status_code = "2"'],
            2 => [3, 'Withdrawn Learners', null, 'WHERE tr.status_code = "3"'],
            3 => [4, 'Temporary Withdrawn Learners', null, 'WHERE tr.status_code = "6"'],
        ];
		$f = new VoltDropDownViewFilter('filter_status_code', $options, 1, true);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
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

        $options = array(
            0=>array(0, 'Coach Name, Learner Name', null, 'ORDER BY coach, learner_name'),
            1=>array(1, 'Learner Name, Start Date', null, 'ORDER BY learner_name, start_date'),
        );
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 0, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);

		return $view;
	}

    public function renderView(PDO $link, VoltView $view)
    {
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigator() . '<br>';
            echo '<div class="center"><table class="table table-bordered">';
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                if(!in_array($column['name'], $this->excludeColumns))
                    $columns[] = $column['name'];
            }
            echo '<thead class="bg-green-gradient"><tr>';
            foreach($columns AS $column)
            {
                if($column == 'otj_stats')
                {
                    echo '<th>OTJ hours attended</th><th>OTJ hours remaining</th><th>OTJ attended percentage</th><th>OTJ remaining percentage</th>';

                }
                else
                {
                    echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
                }
            }
            echo '</tr></thead>';

            echo '<tbody>';
            
            $courses_templates = [];

            while($row = $st->fetch())
            {
                $fs_registrations = [
                    'eng_l1' => ['title' => '', 'reg' => '', 'exempt' => false, 'result' => ''],
                    'math_l1' =>  ['title' => '', 'reg' => '', 'exempt' => false, 'result' => ''],
                    'eng_l2' => ['title' => '', 'reg' => '', 'exempt' => false, 'result' => ''],
                    'math_l2' =>  ['title' => '', 'reg' => '', 'exempt' => false, 'result' => ''],
                ];
                $student_qualifications_result = DAO::getResultset($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$row['tr_id']}' AND qualification_type = 'FS' ORDER BY auto_id DESC", DAO::FETCH_ASSOC);
                foreach($student_qualifications_result AS $student_qualification_row)
                {
                    $title = strtolower($student_qualification_row['title']);
                    if(strpos($title, 'english') && $student_qualification_row['level'] == 1 && $fs_registrations['eng_l1']['title'] == '')
                    {
                        $fs_registrations['eng_l1']['title'] = $student_qualification_row['title'];
                        $fs_registrations['eng_l1']['reg'] = $student_qualification_row['awarding_body_reg'];
                        if($student_qualification_row['aptitude'] == 1)
                            $fs_registrations['eng_l1']['exempt'] = true;
                        $l1_eng_read = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%read%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        $l1_eng_write = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%writ%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        $l1_eng_speak = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%speak%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        if(isset($l1_eng_read->id) && isset($l1_eng_write->id) && isset($l1_eng_speak->id))
                        {
                            if(strtolower($l1_eng_read->exam_result) == 'fail' || strtolower($l1_eng_write->exam_result) == 'fail' || strtolower($l1_eng_speak->exam_result) == 'fail')
                            {
                                $fs_registrations['eng_l1']['result'] = 'fail';
                            }
                            elseif(strtolower($l1_eng_read->exam_result) == 'pass' && strtolower($l1_eng_write->exam_result) == 'pass' && strtolower($l1_eng_speak->exam_result) == 'pass')
                            {
                                $fs_registrations['eng_l1']['result'] = 'pass';
                            }
                        }
                    }
                    if(strpos($title, 'english') && $student_qualification_row['level'] == 2 && $fs_registrations['eng_l2']['title'] == '')
                    {
                        $fs_registrations['eng_l2']['title'] = $student_qualification_row['title'];
                        $fs_registrations['eng_l2']['reg'] = $student_qualification_row['awarding_body_reg'];
                        if($student_qualification_row['aptitude'] == 1)
                            $fs_registrations['eng_l2']['exempt'] = true;
                        $l2_eng_read = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%read%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        $l2_eng_write = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%writ%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        $l2_eng_speak = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%speak%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        if(isset($l2_eng_read->id) && isset($l2_eng_write->id) && isset($l2_eng_speak->id))
                        {
                            if(strtolower($l2_eng_read->exam_result) == 'fail' || strtolower($l2_eng_write->exam_result) == 'fail' || strtolower($l2_eng_speak->exam_result) == 'fail')
                            {
                                $fs_registrations['eng_l2']['result'] = 'fail';
                            }
                            elseif(strtolower($l2_eng_read->exam_result) == 'pass' && strtolower($l2_eng_write->exam_result) == 'pass' && strtolower($l2_eng_speak->exam_result) == 'pass')
                            {
                                $fs_registrations['eng_l2']['result'] = 'pass';
                            }
                        }
    
                    }
                    if(strpos($title, 'math') && $student_qualification_row['level'] == 1 && $fs_registrations['math_l1']['title'] == '')
                    {
                        $fs_registrations['math_l1']['title'] = $student_qualification_row['title'];
                        $fs_registrations['math_l1']['reg'] = $student_qualification_row['awarding_body_reg'];
                        if($student_qualification_row['aptitude'] == 1)
                            $fs_registrations['math_l1']['exempt'] = true;
                        $l1_exam = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 1%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        if(isset($l1_exam->id))
                        {
                            if(strtolower($l1_exam->exam_result) == 'fail')
                            {
                                $fs_registrations['math_l1']['result'] = 'fail';
                            }
                            else
                            {
                                $fs_registrations['math_l1']['result'] = 'pass';
                            }
                        }
                    }
                    if(strpos($title, 'math') && $student_qualification_row['level'] == 2 && $fs_registrations['math_l2']['title'] == '')
                    {
                        $fs_registrations['math_l2']['title'] = $student_qualification_row['title'];
                        $fs_registrations['math_l2']['reg'] = $student_qualification_row['awarding_body_reg'];
                        if($student_qualification_row['aptitude'] == 1)
                            $fs_registrations['math_l2']['exempt'] = true;
                        $l2_exam = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 2%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                        if(isset($l2_exam->id))
                        {
                            if(strtolower($l2_exam->exam_result) == 'fail')
                            {
                                $fs_registrations['math_l2']['result'] = 'fail';
                            }
                            else
                            {
                                $fs_registrations['math_l2']['result'] = 'pass';
                            }
                        }    
                    }
                }

                echo HTML::viewrow_opening_tag("do.php?_action=read_training_record&id={$row['tr_id']}");
                foreach($columns as $column)
                {
                    if($column == 'status_code')
                    {
                        switch($row['status_code'])
                        {
                            case '1':
                                echo '<td>Continuing</td>';
                                break;
                            case '2':
                                echo '<td>Completed</td>';
                                break;
                            case '3':
                                echo '<td>Withdrawn</td>';
                                break;
                            case '6':
                                echo '<td>Temp. Withdrawn</td>';
                                break;
                            default:
                                echo '<td>Other</td>';
                                break;
                        }
                    }
                    elseif($column == 'otj_stats')
                    {
                        $minutes_planned = $row['otj_total'] * 60;
                        $minutes_attended = DAO::getSingleValue($link, "SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = '{$row['tr_id']}'");
                        $hours_attended = $this->convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
                        $minutes_remaining = $minutes_planned - $minutes_attended;
                        $hours_remaining = $this->convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
        
                        if($minutes_planned > 0)
                        {
                            if($minutes_attended > $minutes_planned)
                                $attended_percentage = 100;
                            else
                                $attended_percentage = ($minutes_attended / $minutes_planned) * 100;
                            $remaining_percentage = 100 - ($minutes_attended / $minutes_planned * 100);
                        }
                        else
                        {
                            $attended_percentage = 0;
                            $remaining_percentage = 0;
                        }
        
                        if($minutes_remaining < 0)
                            $minutes_remaining = 0;
        
                        if($remaining_percentage < 0)
                            $remaining_percentage = 0;

                        echo '<td>' . $hours_attended . '</td>';    
                        echo '<td>' . $hours_remaining . '</td>';    
                        echo '<td>' . round($attended_percentage, 2) . '%</td>';    
                        echo '<td>' . round($remaining_percentage, 2) . '%</td>';    
                    } 
                    elseif ($column == 'ldoc') 
                    {
                        if ($row['max_tracking_date'] != '' && $row['max_support_date'] != '') 
                        {
                            $max_tracking_date = new Date($row['max_tracking_date']);
                            $max_support_date = new Date($row['max_support_date']);
                            if ($max_tracking_date->after($max_support_date)) 
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
                        elseif ($row['max_tracking_date'] == '') 
                        {
                            echo '<td class="' . self::getLdocColor($link, $row['max_support_date'], $row['tr_id']) . '">' . Date::toShort($row['max_support_date']) . '</td>';
                        } 
                        elseif ($row['max_support_date'] == '') 
                        {
                            echo '<td class="' . self::getLdocColor($link, $row['max_tracking_date'], $row['tr_id']) . '">' . Date::toShort($row['max_tracking_date']) . '</td>';
                        } 
                        else 
                        {
                            echo '<td></td>';
                        }
                    }
                    elseif($column == 'fs_english_level_1')
                    {
                        echo '<td>' . $fs_registrations['eng_l1']['title'] . '</td>';
                    }
                    elseif($column == 'fs_english_level_1_reg_number')
                    {
                        echo '<td>' . $fs_registrations['eng_l1']['reg'] . '</td>';
                    }
                    elseif($column == 'fs_english_level_1_result')
                    {
                        echo '<td>' . $fs_registrations['eng_l1']['result'] . '</td>';
                    }
                    elseif($column == 'fs_english_level_2')
                    {
                        echo '<td>' . $fs_registrations['eng_l2']['title'] . '</td>';
                    }
                    elseif($column == 'fs_english_level_2_reg_number')
                    {
                        echo '<td>' . $fs_registrations['eng_l2']['reg'] . '</td>';
                    }
                    elseif($column == 'fs_english_level_2_result')
                    {
                        echo '<td>' . $fs_registrations['eng_l2']['result'] . '</td>';
                    }
                    elseif($column == 'fs_maths_level_1')
                    {
                        echo '<td>' . $fs_registrations['math_l1']['title'] . '</td>';
                    }
                    elseif($column == 'fs_maths_level_1_reg_number')
                    {
                        echo '<td>' . $fs_registrations['math_l1']['reg'] . '</td>';
                    }
                    elseif($column == 'fs_maths_level_1_result')
                    {
                        echo '<td>' . $fs_registrations['math_l1']['result'] . '</td>';
                    }
                    elseif($column == 'fs_maths_level_2')
                    {
                        echo '<td>' . $fs_registrations['math_l2']['title'] . '</td>';
                    }
                    elseif($column == 'fs_maths_level_2_reg_number')
                    {
                        echo '<td>' . $fs_registrations['math_l2']['reg'] . '</td>';
                    }
                    elseif($column == 'fs_maths_level_2_result')
                    {
                        echo '<td>' . $fs_registrations['math_l2']['result'] . '</td>';
                    }
                    elseif($column == 'progress_percentage')
                    {
                        if(!in_array($row['course_id'], $courses_templates))
                        {
                            $course = Course::loadFromDatabase($link, $row['course_id']);
                            $courses_templates[$course->id] = $course->getKSBTemplate($link);
                        }

                        $tracking_template = $courses_templates[$row['course_id']];

                        $total_done = 0;
                        $total_total = 0;
                        foreach($tracking_template->sections AS $section)
                        {
                            $evidence_ids = array_map(function($evidence){
                                return $evidence->evidence_id;
                            }, $section->evidences);
                            $implode_evidence_ids = implode(',', $evidence_ids);
                            $section_evidences = count($evidence_ids);
                            if($section_evidences == 0)
                                $learner_evidence_count = 0;
                            else
                                $learner_evidence_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_tracking WHERE tr_id = '{$row['tr_id']}' AND tracking_id IN ({$implode_evidence_ids})");
                            $total_done += $learner_evidence_count;    
                            $total_total += $section_evidences;    
                        }
                        if($total_total != 0)
                        {
                            echo '<td>' . round(($total_done/$total_total)*100, 2) . '%</td>';
                        }
                        else
                        {
                            echo '<td></td>';
                        }
                    }
                    elseif($column == 'fs_math_result')
                    {
                        if($fs_registrations['math']['exempt'])
                        {
                            echo "<td>EXEMPT</td>";
                        }
                        else
                        {
                            $l2_exam = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 2%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                            if(isset($l2_exam->id))
                            {
                                if(strtolower($l2_exam->exam_result) == 'fail')
                                {
                                    echo '<i class="fa fa-remove"></i> ';
                                }
                                else
                                {
                                    echo $l2_exam->attempt_no == '1' ? '<i class="fa fa-check text-green"></i>' : '<i class="fa fa-check text-red"></i>';
                                }
                            }
                        }
                    }
                    else
                    {
                        echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    }
                }
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo $view->getViewNavigator();
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1)
        {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
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

    private $excludeColumns = [
        'tr_id',
        'max_tracking_date',
        'max_support_date',
        'course_id',
    ];
}