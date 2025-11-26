<?php
class ajax_bksb extends ActionController
{
    private $access_key = null;
    private $secret = null;

    public function indexAction(PDO $link)
    {
        $this->access_key = SystemConfig::getEntityValue($link, "bksb_access_key");
        $this->secret = SystemConfig::getEntityValue($link, "bksb_secret");


    }

    public function createLearnerInBksbAction(PDO $link)
    {
        $this->indexAction($link);

        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : ''; // here username is ebs id
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';

        if($username == '' || $ob_learner_id == '')
            throw new Exception("missing querystring argument: username");

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);

        // check username (ebs id) exists already in the BKSB
        $valid_username = Bksb::isUsernameExists($this->access_key, $this->secret, $username);

        if(!$valid_username || $valid_username == 'false')
        {
            $learner_dob = new Date($ob_learner->dob);

            $dob = [
                "Year" => $learner_dob->getYear(),
                "Month" => $learner_dob->getMonth(),
                "Day" => $learner_dob->getDay(),
                "Hour" => $learner_dob->getHour(),
                "Minute" => $learner_dob->getMinute(),
                "Second" => $learner_dob->getSecond(),
                "Millisecond" => 0
            ];
            $learner_push = [
                "Username" => $username,
                "Firstname" => $ob_learner->firstnames,
                "Lastname" => $ob_learner->surname,
                "Password" => "Pa55word",
                "Email" => $ob_learner->home_email,
                "DateOfBirth" => $dob,
                "AutoEnrolCourses" => true
            ];

            $learner_push = http_build_query($learner_push);

            $bksb_learner_info = Bksb::createUser($this->access_key, $this->secret, $learner_push);

            $bksb_learner_info = json_decode($bksb_learner_info);
            if(isset($bksb_learner_info->UserId) && $bksb_learner_info->UserId != '')
            {
                $ob_learner->bksb_learner_info = json_encode($bksb_learner_info);
                $ob_learner->bksb_username = $bksb_learner_info->Username;
                $ob_learner->bksb_userid = $bksb_learner_info->UserId;
                $ob_learner->save($link);
                echo 'Record is successfully created in BKSB';
            }
            else
            {
                echo $bksb_learner_info;
            }
        }
        else
        {
            echo "The username '{$username}' already exists in BKSB system.";
        }
    }

    public function downloadInitialAssessmentAction(PDO $link)
    {
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';

        if($username == '' || $ob_learner_id == '')
            throw new Exception("missing querystring argument: username");

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);

        echo $ob_learner->downloadBksbAssessments($link);
    }

    public function downloadInitialAssessmentV2Action(PDO $link)
    {
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';

        if($username == '' || $ob_learner_id == '')
            throw new Exception("missing querystring argument: username");

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);

        echo $ob_learner->getAssessmentEOAData($link);
    }

}