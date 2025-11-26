<?php
class ajax_get_previous_signature implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $user = isset($_REQUEST['user'])?$_REQUEST['user']: '';
        $review_id = isset($_REQUEST['review_id'])?$_REQUEST['review_id']: '';
        $type = isset($_REQUEST['type'])?$_REQUEST['type']: '';

        if($user==1 && isset($_SESSION['user']))
        {
            $assessor_name = $_SESSION['user']->firstnames . " " . $_SESSION['user']->surname;
            $username = $_SESSION['user']->username;
            if($type==1)
                $signature = DAO::getSingleValue($link, "select signature_assessor_font from assessor_review_forms where learner_assessor = '$assessor_name' order by review_id desc limit 0,1");
            else
                $signature = DAO::getSingleValue($link, "select signature from users where username='$username'");
            echo $signature;
        }
    }
}