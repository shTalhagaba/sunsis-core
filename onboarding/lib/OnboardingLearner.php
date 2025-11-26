<?php


class OnboardingLearner extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes($id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	ob_learners
WHERE
	id='$key'
LIMIT 1;
HEREDOC;
        $st = $link->query($query);

        $ob_learner = null;
        if($st)
        {
            $row = $st->fetch();
            if($row)
            {
                $ob_learner = new OnboardingLearner();
                $ob_learner->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find onboarding learner. " . '----' . $query . '----' . $st->errorCode());
        }

        return $ob_learner;
    }

    public function save(PDO $link)
    {
        $this->created_by = $this->id == '' ? $_SESSION['user']->id : $this->created_by;
        $this->created = $this->id == '' ? date('Y-m-d H:i:s') : $this->created;
        $this->updated = date('Y-m-d H:i:s');

        $this->home_postcode = strtoupper($this->home_postcode);

        return DAO::saveObjectToTable($link, 'ob_learners', $this);
    }

    public function delete(PDO $link)
    {
        $sql = <<<HEREDOC
DELETE FROM
	ob_learners, tr
USING
	ob_learners 
	LEFT OUTER JOIN tr ON ob_learners.id = tr.ob_learner_id
WHERE
	ob_learners.id={$this->id}
HEREDOC;

        DAO::execute($link, $sql);
    }

    public function getCreatorName(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = '{$this->created_by}'");
    }

    public function downloadBksbAssessments(PDO $link)
    {
        $_need_saving = false;
        $bksb_access_key = SystemConfig::getEntityValue($link, "bksb_access_key");
        $bksb_secret = SystemConfig::getEntityValue($link, "bksb_secret");

        $valid_username = Bksb::isUsernameExists($bksb_access_key, $bksb_secret, $this->bksb_username);

        if($valid_username == 'true')
        {
            // get user id from username
            $bksb_userid = Bksb::getUserId($bksb_access_key, $bksb_secret, $this->bksb_username);

            if($bksb_userid != "A user with username '{$this->bksb_username}' not found.")
            {
                $_need_saving = true;

                $this->bksb_userid = $bksb_userid;

                // now get the initial assessment from user id
                $bksb_i_assessment = Bksb::initialAssessment($bksb_access_key, $bksb_secret, $this->bksb_userid);
                $this->bksb_i_assessment = $bksb_i_assessment;

                // now get the diagnostic assessment from user id
                $bksb_d_assessment = Bksb::diagnosticAssessment($bksb_access_key, $bksb_secret, $this->bksb_userid);
                $this->bksb_d_assessment = $bksb_d_assessment;

                $this->bksb_username = $this->bksb_username;

            }
        }

        if($_need_saving)
            $this->save($link);

        if(IS_AJAX)
        {
            if(!$valid_username || $valid_username == 'false')
                return ("Invalid BKSB username.");

            if($_need_saving)
                return ("BKSB assessments are downloaded successfully.");
        }
    }

    public function downloadBksbLogin(PDO $link)
    {
        if($this->bksb_userid == '')
            return;

        $_need_saving = false;
        $bksb_access_key = SystemConfig::getEntityValue($link, "bksb_access_key");
        $bksb_secret = SystemConfig::getEntityValue($link, "bksb_secret");

        $valid_username = Bksb::isUsernameExists($bksb_access_key, $bksb_secret, $this->bksb_username);

        if($valid_username == 'true')
        {
            $bksb_login = Bksb::getAutoLoginLink($bksb_access_key, $bksb_secret, $this->bksb_userid);
            $this->bksb_login = $bksb_login;

            $_need_saving = true;
        }

        if($_need_saving)
            $this->save($link);
    }

    public function getAssessmentSessions(PDO $link)
    {

        $_need_saving = false;
        $bksb_access_key = SystemConfig::getEntityValue($link, "bksb_access_key");
        $bksb_secret = SystemConfig::getEntityValue($link, "bksb_secret");

        $valid_username = Bksb::isUsernameExists($bksb_access_key, $bksb_secret, $this->bksb_username);

        $course_subjects = [
            'ENG_NEW',
            'MATHS_NEW',
        ];

        if($valid_username == 'true')
        {
            // get user id from username
            $bksb_userid = Bksb::getUserId($bksb_access_key, $bksb_secret, $this->bksb_username);

            if($bksb_userid != "A user with username '{$this->bksb_username}' not found.")
            {
                $_need_saving = true;

                $this->bksb_userid = $bksb_userid;

                $this->bksb_username = $this->bksb_username;

                DAO::execute($link, "DELETE FROM bksb_assessment_sessions WHERE ob_learner_id = '{$this->id}'");

                foreach($course_subjects AS $subject)
                {
                    // get assessmentSessions
                    $assessment_sessions = Bksb::getAssessmentSessionsForCourseV5($bksb_access_key, $bksb_secret, $this->bksb_userid, $subject);
                    $assessment_sessions_decoded = json_decode($assessment_sessions);
                    if(count($assessment_sessions_decoded) > 0)
                    {
                        foreach($assessment_sessions_decoded AS $session_detail)
                        {
                            if(!isset($session_detail->SessionId))
                                continue;

                            if(isset($session_detail->DateCompleted) && $session_detail->DateCompleted == "0001-01-01T00:00:00")
                                continue;

                            $session = new stdClass();
                            $session->ob_learner_id = $this->id;
                            $session->course_subject = $subject;
                            foreach($session_detail AS $key => $value)
                            {
                                if(in_array($key, ["DateStarted", "DateCompleted"]))
                                {
                                    $d = new Date ($value);
                                    $session->$key = $d->format('Y-m-d H:i:s');
                                }
                                $session->$key = $value;
                            }

                            DAO::saveObjectToTable($link, "bksb_assessment_sessions", $session);
                        }
                    }
                }

            }
        }

        if($_need_saving)
            $this->save($link);

        return "success";
    }

    public function getAssessmentEOAData(PDO $link)
    {
        $this->getAssessmentSessions($link);

        $bksb_access_key = SystemConfig::getEntityValue($link, "bksb_access_key");
        $bksb_secret = SystemConfig::getEntityValue($link, "bksb_secret");

        $result = DAO::getResultset($link, "SELECT * FROM bksb_assessment_sessions WHERE ob_learner_id = '{$this->id}'", DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $session_report = Bksb::GetEOAReportDataForAssessmentSessionV5($bksb_access_key, $bksb_secret, $this->bksb_userid, $row['SessionId']);

            $_obj = (object)$row;
            $_obj->AssessmentEOAData = $session_report;

            DAO::saveObjectToTable($link, "bksb_assessment_sessions", $_obj);
        }

        if(IS_AJAX)
        {
            return ("BKSB assessments are downloaded successfully..");
        }
    }

    public function isNonApp(PDO $link)
    {
        return in_array($this->funding_stream, [Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_ASF]);
    }

    public $id = NULL;
    public $learner_title = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $gender = NULL;
    public $dob = NULL;
    public $home_address_line_1 = NULL;
    public $home_address_line_2 = NULL;
    public $home_address_line_3 = NULL;
    public $home_address_line_4 = NULL;
    public $home_postcode = NULL;
    public $home_mobile = NULL;
    public $home_email = NULL;
    public $home_telephone = NULL;
    public $work_email = NULL;
    public $ethnicity = NULL;
    public $ni = NULL;
    public $uln = NULL;
    public $archive = "N";
    public $created_by = NULL;
    public $created = NULL;
    public $updated = NULL;
    public $sunesis_learner_id = NULL;
    public $employer_id = NULL;
    public $employer_location_id = NULL;
    public $bksb_username = NULL;
    public $bksb_userid = NULL;
    public $bksb_i_assessment = NULL;
    public $bksb_d_assessment = NULL;
    public $bksb_learner_info = NULL;
    public $das_admin = NULL;
    public $das_cohort_no = NULL;
    public $line_manager_id = NULL;
    public $caseload_org_id = NULL;
    public $borough = NULL;
    public $funding_stream = NULL;

    protected $audit_fields = [
        'learner_title' => 'Learner title',
        'firstnames' => 'Firstnames',
        'surname' => 'Surname',
        'gender' => 'Gender',
        'dob' => 'DOB',
        'home_address_line_1' => 'Home address line 1',
        'home_address_line_2' => 'Home address line 2',
        'home_address_line_3' => 'Home address line 3',
        'home_address_line_4' => 'Home address line 4',
        'home_postcode' => 'Home postcode',
        'home_mobile' => 'Home mobile',
        'home_telephone' => 'Home telephone',
        'home_email' => 'Home email',
        'work_email' => 'Work email',
        'wthnicity' => 'Ethnicity',
        'ni' => 'National insurance',
        'uln' => 'ULN',
        'archive' => 'Archive',
    ];

    const TYPE_CREATED = 1;
    const TYPE_SS_EMAILS_ENT = 2;
    const TYPE_SS_SIGNED_BY_LEARNER = 3;
    const TYPE_SS_SIGNED_BY_EMPLOYER = 4;
    const TYPE_SS_SIGNED_BY_PROVIDER = 5;

    const CASELOAD_FRONTLINE = 1;
    const CASELOAD_LINKS_TRAINING = 2;
    const CASELOAD_NEW_ACCESS = 3;
    const CASELOAD_INTERNAL_ELA = 4;
    const CASELOAD_ADMIN_SALES = 5;


}