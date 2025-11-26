<?php
class survey_form implements IAction {

    // Survey link should be like - http://www.rob.com/do.php?_action=survey_form&username=1091ap198734 || 8631583404
	public function execute(PDO $link) {
        $_REQUEST['header'] = 1;

    	// Validate data
		$uln = isset($_GET['uln']) ? $_GET['uln'] : '';
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        $uid = isset($_GET['uid']) ? $_GET['uid'] : '';
        if(!$uln && !$username &&!$uid)
		{
			throw new Exception("Missing or empty querystring argument");
		}

        // Create Value Object
        if ($uid) {
			$vo = User::loadFromDatabaseById($link, $uid);
			if (is_null($vo)) {
				throw new Exception("No user with username '$username'");
			}
            $addparam = '&uid='.$uid;
		} elseif ($username) {
			$vo = User::loadFromDatabase($link, $username);
			if (is_null($vo)) {
				throw new Exception("No user with username '$username'");
			}
            $addparam = '&username='.$username;
		} else {
			$vo = User::loadFromDatabase($link, $uln);
			if (is_null($vo)) {
				throw new Exception("No user with uln '$uln'");
			}
            $addparam = '&uln='.$uln;
		}

        $sent = false;
		if( isset($_REQUEST['subaction']) && $_REQUEST['subaction']== "send" ) {

            $surveyObj = new Survey();
            $surveyObj->userid = $vo->id;
            $surveyObj->details = $_POST['details'];
            $surveyObj->created = date('Y-m-d H:i:s');
            $surveyObj->save($link);
            $survey_id = $surveyObj->id;

            header("Location: do.php?_action=survey_form&subaction=sent&survey_id=".$survey_id.$addparam);exit;
		}

        if( isset($_REQUEST['subaction']) && $_REQUEST['subaction']== "sent" ) {
              $sent = true;
              $survey_id = $_GET['survey_id'];
        }

		include "tpl_survey_form.php";

	}

}
