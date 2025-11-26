<?php
class add_learners_tracking implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        $course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
        $tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id'] : '';

        $_SESSION['bc']->index = 0;
        if($course_id != '')
        {
            $course_title = DAO::getSingleValue($link, "SELECT title FROM courses WHERE id = '{$course_id}'");
            $_SESSION['bc']->add($link, "do.php?_action=view_courses2", "View Courses");
            $_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview=overview&id={$course_id}", $course_title);
        }
        $_SESSION['bc']->add($link, "do.php?_action=add_learners_tracking&course_id={$course_id}&group_id={$group_id}&tg_id={$tg_id}", 'Record Tracking');
        if($subaction == 'renderStudentsTrackingTab')
        {
            $this->renderStudentsTrackingTab($link);
            exit;
        }
        if($subaction == 'save_tracking')
        {
            $this->save_tracking($link);
        }
        if($subaction == 'update_tracking_date')
        {
            $this->update_tracking_date($link);
            exit;
        }

        $courses_select = DAO::getResultset($link, "SELECT courses.id, courses.title FROM courses WHERE courses.active = 1 ORDER BY courses.title");
        $groups_select = $course_id == '' ? [] : DAO::getResultset($link, "SELECT groups.id, groups.title FROM groups WHERE groups.courses_id = '{$course_id}' ORDER BY groups.title");
        $tgs_select = $group_id == '' ? [] : DAO::getResultset($link, "SELECT id, title FROM training_groups WHERE group_id = '{$group_id}' ORDER BY training_groups.title");


        include_once('tpl_add_learners_tracking.php');
    }

    public function renderStudentsTrackingTab(PDO $link)
    {
        $tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id'] : '';
        $section_id = isset($_REQUEST['section_id']) ? $_REQUEST['section_id'] : '';

        $section_row = DAO::getObject($link, "SELECT * FROM tracking_template WHERE id = '{$section_id}' ");

        $html = '<p class="lead text-bold text-center text-blue">' . $section_row->title . '</p>';

        $html .= '<div class="">';

        $html .= '<table class="table table-bordered">';
        $html .= '<thead>';
        $section_elements = DAO::getResultset($link, "SELECT * FROM tracking_template WHERE section_id = '{$section_id}'", DAO::FETCH_ASSOC);
        $html .= '<tr>';
        $html .= '<th class="bg-green"></th>';
        foreach($section_elements AS $element)
        {
            $element_evidences_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tracking_template WHERE element_id = '{$element['id']}'");
            $html .= '<th colspan="' . $element_evidences_count . '" class="text-center text-orange bg-black">';
            $html .= str_replace(' ', '&nbsp;', strtoupper($element['title']));
            $html .= '</th>';
        }
        $html .= '</tr>';
        $html .= '<tr class="secondRow">';
        $html .= '<th class="bg-green">Learner&nbsp;Name</th>';
        foreach($section_elements AS $element)
        {
            $element_evidences = DAO::getResultset($link, "SELECT id, title FROM tracking_template WHERE element_id = '{$element['id']}'", DAO::FETCH_ASSOC);
            foreach($element_evidences AS $evidence)
            {
                $html .= '<th class="text-center text-black bg-light-blue-gradient">';
                $html .= '<p>' . str_replace(' ', '&nbsp;', $evidence['title']).'</p>';
                $html .= '<input class="chkTrackerEvidence" type="checkbox" name="evidence_'.$evidence['id'].'" id="evidence_'.$evidence['id'].'" />';
                $html .= '</th>';
            }
        }
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody class="small">';
        $caseload = '';
        if($_SESSION['caseload_learners_only'] == 1)
            $caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
        $learners = DAO::getResultset($link, "SELECT tr.id, CONCAT(firstnames, ' ', surname) AS learner_name FROM tr WHERE tr.tg_id = '{$tg_id}' {$caseload} ORDER BY tr.firstnames", DAO::FETCH_ASSOC);
        foreach($learners AS $learner)
        {
            $html .= '<tr>';
            $html .= '<td>' . str_replace(' ', '&nbsp;', $learner['learner_name']) . '</td>';
            foreach($section_elements AS $element)
            {
//				$element_evidences_ids = DAO::getSingleColumn($link, "SELECT id FROM tracking_template WHERE element_id = '{$element['id']}'");
                $element_evidences = DAO::getResultset($link, "SELECT id, title FROM tracking_template WHERE element_id = '{$element['id']}'", DAO::FETCH_ASSOC);
                foreach($element_evidences AS $element_evidence)
                {
                    $tracking = DAO::getObject($link, "SELECT * FROM tr_tracking WHERE tr_id = '{$learner['id']}' AND tracking_id = '{$element_evidence['id']}'");
                    if(!isset($tracking->tr_id))
                    {
                        $html .= '<td align="center" title="Learner: ' . $learner['learner_name'] . '&#10;Col: ' . $element_evidence['title'] . '">';
                        $html .= '<input class="chkTrackerEvidence" type="checkbox" name="evid_'.$element_evidence['id'].'_tid_'.$learner['id'].'" id="evid_'.$element_evidence['id'].'_tid_'.$learner['id'].'" value="1" />';
                        $html .= '</td>';
                    }
                    else
                    {
                        $html .= '<td class="text-center text-green" title="Learner: ' . $learner['learner_name'] . '&#10;Col: ' . $element_evidence['title'] . '">';
                        $html .= '<i class="fa fa-check fa-lg"></i>';
                        $html .= '<br><span class="cellDate" id="cellDate_'.$tracking->tr_id.'_'.$tracking->tracking_id.'">' . Date::toShort($tracking->date) . '</span>';
                        $html .= '<br><span class="btn btn-info btn-xs pull-right" title="Edit the date in this cell." onclick="updateTrackingDate(this, \''.$tracking->tr_id.'\', \''.$tracking->tracking_id.'\');"><i class="fa fa-edit"></i></span>';
                        $html .= '</td>';
                    }
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        $html .= '</table> ';

        $html .= '</div> ';

        echo  $html;
    }

    private function save_tracking(PDO $link)
    {
        foreach($_POST AS $key => $value)
        {
            if($value == '' || substr($key, 0, 5) != 'evid_')
                continue;

            $key_parts = explode('_', $key);

            $vo = new stdClass();
            $vo->tracking_id = $key_parts[1];
            $vo->tr_id = $key_parts[3];
            $vo->status = $value;
            $vo->date = isset($_POST['ksb_tracker_date']) ? $_POST['ksb_tracker_date'] : date('Y-m-d');
            DAO::saveObjectToTable($link, 'tr_tracking', $vo);

            // create / update activity
            if(true)
            {
                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$vo->tr_id}'");
                $course = Course::loadFromDatabase($link, $course_id);
                $tracking_template = $course->getKSBTemplate($link);

                $date = Date::toMySQL($vo->date);
                $review_id = DAO::getSingleValue($link, "SELECT id FROM review_forms WHERE tr_id = '{$vo->tr_id}' AND date_of_activity = '{$date}'");
                $review = $review_id == '' ? new LeapReviewForm() : LeapReviewForm::loadFromDatabase($link, $review_id);
                if($review->coach_sign == '')
                {
                    $review->tr_id = $vo->tr_id;
                    $review->date_of_activity = $vo->date;
                    if($review->record_of_work_completed == '')
                    {
                        $record_of_work_completed = DAO::getSingleValue($link, "SELECT title FROM tracking_template WHERE id = '{$vo->tracking_id}'");
                    }
                    else
                    {
                        $record_of_work_completed = explode(",", $review->record_of_work_completed);
                        $record_of_work_completed[] = DAO::getSingleValue($link, "SELECT title FROM tracking_template WHERE id = '{$vo->tracking_id}'");
                    }
                    $review->record_of_work_completed = $record_of_work_completed;


                    foreach($tracking_template->sections AS $section)
                    {
                        if(!in_array($section->section_title, ["Knowledge", "Skills", "Behaviours"]))
                            continue;

                        $evidence_ids = array_map(function($evidence){
                            return $evidence->evidence_id;
                        }, $section->evidences);
                        $implode_evidence_ids = implode(',', $evidence_ids);
                        $section_evidences = count($evidence_ids);
                        if($section_evidences == 0)
                            $learner_evidence_count = 0;
                        else
                            $learner_evidence_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_tracking WHERE tr_id = '{$vo->tr_id}' AND tracking_id IN ({$implode_evidence_ids})");
                        $percentage = $section_evidences > 0 ? round(($learner_evidence_count/$section_evidences)*100, 2) : 0;
                        $section_title = $section->section_title;
                        $review->$section_title = "{$learner_evidence_count} / {$section_evidences} = {$percentage}";

                    }

                    $review->save($link);
                }
            }
        }

        if($_POST['stay_on'] == 0)
            http_redirect("do.php?_action=add_learners_tracking&course_id={$_POST['course_id']}&group_id={$_POST['group_id']}&tg_id={$_POST['tg_id']}&stay_on_section={$_POST['stay_on_section']}");
        else
            http_redirect("do.php?_action=read_course_v2&id={$_POST['course_id']}&subview=tracking_view");
    }

    private function update_tracking_date(PDO $link)
    {
        $vo = new stdClass();
        $vo->tracking_id = $_POST['tracking_id'];
        $vo->tr_id = $_POST['tr_id'];
        $vo->date = $_POST['tracking_date'];
        if(is_null($vo->date) || $vo->date == '')
            DAO::execute($link, "DELETE FROM tr_tracking WHERE tracking_id = '{$vo->tracking_id}' AND tr_id = '{$vo->tr_id}'");
        else
            DAO::saveObjectToTable($link, 'tr_tracking', $vo);

        if($_POST['stay_on_section'] != '' && $vo->date == '')
            echo "do.php?_action=add_learners_tracking&course_id={$_POST['course_id']}&group_id={$_POST['group_id']}&tg_id={$_POST['tg_id']}&stay_on_section={$_POST['stay_on_section']}";
        else
            echo $vo->date;
    }
}
