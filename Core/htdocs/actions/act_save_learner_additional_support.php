<?php
class save_learner_additional_support implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new AdditionalSupport();
        $vo->populate($_POST);

        if(isset($_POST['manager_attendance']))
            $vo->manager_attendance = "true";
        else
            $vo->manager_attendance = "";


        $vo->save($link);

        $subject_areas = [
            0 => "Assessment Plans",
            1 => "Reflective Hours",
            2 => "Functional Skills",
            3 => "Others"
        ];

        if(SOURCE_LOCAL || in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$vo->tr_id}'");
            $course = Course::loadFromDatabase($link, $course_id);
            $tracking_template = $course->getKSBTemplate($link);

            $date = Date::toMySQL($vo->actual_date);
            $review_id = DAO::getSingleValue($link, "SELECT id FROM review_forms WHERE tr_id = '{$vo->tr_id}' AND date_of_activity = '{$date}'");

            if($review_id == '')
            {
                $review = new LeapReviewForm();

                $review->tr_id = $vo->tr_id;
                $review->date_of_activity = $date;
                $review->record_of_work_completed = $vo->comments;

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

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $vo->id;
        }
        else
        {
            http_redirect('do.php?_action=read_training_record&webinars_tab=1&id=' . $vo->tr_id);
        }
    }
}
?>