<?php
class Framework extends Entity
{
    public static function loadFromDatabase(PDO $link, $framework_id)
    {

        if($framework_id == '')
        {
            return null;
        }

        $key = addslashes($framework_id);
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
        if($this->fund_model != self::FUNDING_STREAM_99)
        {
            $this->fund_model_extra = null;
        }
        return DAO::saveObjectToTable($link, 'frameworks', $this);
    }

    public function getMainAim(PDO $link)
    {
        return DAO::getObject($link, "SELECT * FROM framework_qualifications WHERE framework_id = '{$this->id}' AND main_aim = 1");
    }

    public function getProgrammeTypeDesc(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT CONCAT(ProgType, ' ' , ProgTypeDesc) FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$this->framework_type}'");
    }

    public function getStandardCodeDesc(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT CONCAT(StandardCode, ' ' , StandardName) FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$this->StandardCode}'");
    }

    public function getFrameworkCodeDesc(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT CONCAT(StandardCode, ' ' , StandardName) FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$this->StandardCode}'");
    }

    public function getFundingBandMax(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT ROUND(MaxEmployerLevyCap) FROM lars201718.`Core_LARS_ApprenticeshipFunding` WHERE ApprenticeshipType = 'STD' AND ApprenticeshipCode = '{$this->StandardCode}' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
    }

    public function getRecommendedDuration(PDO $link, $otjPerWeekCol = '')
    {
        if($otjPerWeekCol != '')
        {
            $sql = "
                SELECT {$otjPerWeekCol} 
                FROM central.lookup_otj_durations INNER JOIN central.lookup_app_otj_requirements ON lookup_otj_durations.otj_hours = lookup_app_otj_requirements.otj_hours 
                WHERE standard_code = '{$this->standard_ref_no}'";
            return DAO::getSingleValue($link, $sql);
        }
        
        return $this->duration_in_months != '' ?
            $this->duration_in_months :
            DAO::getSingleValue($link, "SELECT ROUND(Duration) FROM lars201718.`Core_LARS_ApprenticeshipFunding` WHERE ApprenticeshipType = 'STD' AND ApprenticeshipCode = '{$this->StandardCode}' ORDER BY EffectiveFrom DESC LIMIT 0,1; ");
    }

    public function getApprenticeshipLink(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT UrlLink FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$this->StandardCode}'; ");
    }

    public function delete(PDO $link)
    {

        $qan = addslashes($this->id);

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

    public function getRplPercentages()
    {
        if($this->rpl_percentages != '')
        {
            return $this->rpl_percentages;
        }

        $rpl_percentages = [];

        foreach(SkillsAnalysis::getScoreAndPercentageList() AS $key => $value)
        {
            $rpl_percentages["score_{$key}"] = $value;
        }

        return json_encode($rpl_percentages);
    }

    public function calculatedOtj(PDO $link)
    {
        return is_null($this->standard_ref_no) ? 0 :
            DAO::getSingleValue($link, "SELECT otj_hours FROM central.lookup_app_otj_requirements WHERE standard_code = '{$this->standard_ref_no}'");
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
    public $PwayCode = NULL;
    public $track = NULL;
    public $otj_hours = NULL;
    public $epa_org_id = NULL;
    public $epa_org_assessor_id = NULL;
    public $min_days = NULL;
    public $standard_ref_no = NULL;
    public $programme_code = NULL;
    public $programme_parent = NULL;
    public $epa_price = NULL;
    public $sub_dept_id = NULL;
    public $training_by_provider = NULL;
    public $provider_equipment = NULL;
    public $training_by_employer = NULL;
    public $employer_equipment = NULL;
    public $training_by_subcontractor = NULL;
    public $items_not_eligible_for_funding1 = NULL;
    public $cost_of_items_not_eligible_for_funding1 = NULL;
    public $items_not_eligible_for_funding2 = NULL;
    public $cost_of_items_not_eligible_for_funding2 = NULL;
    public $program_manager = NULL;
    public $epa_duration = NULL;
    public $tnp1 = NULL;
    public $additional_prices = NULL;
    public $rpl_percentages = NULL;
    public $fdil_page_content = NULL;
    public $first_review = NULL;
    public $review_frequency = NULL;
    public $writing_assessment_text = NULL;
    public $writing_assessment_chars = NULL;
    public $fund_model = NULL;
    public $fund_model_extra = NULL;

    protected $audit_fields = [
        'title' => 'Title',
        'duration_in_months' => 'Duration in Months',
        'epa_duration' => 'EPA Duration',
        'tnp1' => 'TNP1',
        'epa_price' => 'TNP2',
    ];

    const FUNDING_STREAM_SFA = 1;
    const FUNDING_STREAM_SCOTTISH = 2;
    const FUNDING_STREAM_APP = 36;
    const FUNDING_STREAM_BOOTCAMP = 37;
    const FUNDING_STREAM_ASF = 38;
    const FUNDING_STREAM_LEARNER_LOAN = 991;
    const FUNDING_STREAM_COMMERCIAL = 992;
    const FUNDING_STREAM_99 = 99;
}
?>