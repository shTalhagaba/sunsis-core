<?php
class Framework extends Entity
{
    public static function loadFromDatabase(PDO $link, $framework_id)
    {

        if($framework_id == '')
        {
            return null;
        }

        $key = addslashes((string)$framework_id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	frameworks
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $framework = null;
        if($st)
        {
            $framework = null;
            $row = $st->fetch();
            if($row)
            {
                $framework = new Framework();
                $framework->populate($row);
                $framework->id = $framework_id;
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
        }

        return $framework;
    }

    public function save(PDO $link)
    {
        if(!isset($this->active))
            $this->active=0;
        return DAO::saveObjectToTable($link, 'frameworks', $this);
    }

    public function delete(PDO $link)
    {

        $qan = addslashes((string)$this->id);

        // Delete the qualification's structure and the qualification
        $sql = <<<HEREDOC
DELETE FROM
	frameworks,
	framework_qualifications,
	courses,
	course_qualifications_dates,
	groups,
	group_members,
	lessons,
	lesson_notes,
	register_entries,
	register_entry_notes,
	attendance_reports,
	tr
USING
	frameworks
	LEFT OUTER JOIN	courses on courses.framework_id = frameworks.id
	LEFT OUTER JOIN	course_qualifications_dates ON courses.id = course_qualifications_dates.course_id
	LEFT OUTER JOIN groups ON groups.courses_id = courses.id
	LEFT OUTER JOIN group_members ON groups.id = group_members.groups_id
	LEFT OUTER JOIN tr ON group_members.tr_id = tr.id
	LEFT OUTER JOIN lessons ON lessons.groups_id = groups.id
	LEFT OUTER JOIN lesson_notes ON lessons.id = lesson_notes.lessons_id
	LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT OUTER JOIN register_entry_notes ON register_entries.id = register_entry_notes.register_entries_id
	LEFT OUTER JOIN attendance_reports ON register_entries.lessons_id = attendance_reports.lesson_id
	LEFT OUTER JOIN framework_qualifications on framework_qualifications.framework_id = frameworks.id
WHERE
	frameworks.id='$qan';
HEREDOC;
        DAO::execute($link, $sql);
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }


    public $id = null;
    public $title = NULL;
    //public $start_date = NULL;
    //public $end_date = NULL;
    public $framework_code = NULL;
    public $comments = NULL;
    public $targets = NULL;
    public $duration_in_months = NULL;
    public $parent_org = NULL;
    public $active = 1;
    public $clients = NULL;
    public $framework_type = NULL;
    public $milestones = NULL;
    public $start_payment = NULL;
    public $milestone_payment = NULL;
    public $achievement_payment = NULL;
    public $funding_stream = NULL;
    public $StandardCode = NULL;
    public $standard_ref_no = NULL;
    public $PwayCode = NULL;
    public $track = NULL;
    public $otj_hours = NULL;
    public $epa_org_id = NULL;
    public $epa_org_assessor_id = NULL;
    public $min_days = NULL;
    public $gateway_forecast = NULL;
    public $epa_forecast = NULL;
    public $onefile_organisation_id = NULL;
    public $onefile_fwk_tpl_id = NULL;
    public $first_review = NULL;
    public $review_frequency = NULL;
    public $epa_duration = NULL;

    const FUNDING_STREAM_SFA = 1;
    const FUNDING_STREAM_SCOTTISH = 2;
    const FUNDING_STREAM_COMMERCIAL = 3;
}
?>