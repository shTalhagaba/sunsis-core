<?php
class
add_learner_from_ecordia implements IAction
{
    public function execute(PDO $link)
    {
        $firstname = isset($_REQUEST['firstname'])?$_REQUEST['firstname']:'';
        $middlenames = isset($_REQUEST['middlenames'])?$_REQUEST['middlenames']:'';
        $lastname = isset($_REQUEST['lastname'])?$_REQUEST['lastname']:'';
        $email = isset($_REQUEST['email'])?$_REQUEST['email']:'';
        $phonenumber = isset($_REQUEST['phonenumber'])?$_REQUEST['phonenumber']:'';
        $dateofbirth = isset($_REQUEST['dateofbirth'])?$_REQUEST['dateofbirth']:'';
        $uln = isset($_REQUEST['uln'])?$_REQUEST['uln']:'';
        $learnrefnumber = isset($_REQUEST['learnrefnumber'])?$_REQUEST['learnrefnumber']:'';
        $workplacename = isset($_REQUEST['workplacename'])?$_REQUEST['workplacename']:'';
        $workplacepostcode = isset($_REQUEST['workplacepostcode'])?$_REQUEST['workplacepostcode']:'';

        $vo = new User();
        $vo->firstnames = $firstname . " " . $middlenames;
        $vo->surname = $lastname;
        $vo->home_email = $email;
        $vo->home_telephone = $phonenumber;
        $vo->dob = Date::toMySQL($dateofbirth);
        $vo->l45 = $uln;
        $vo->uln = $uln;
        $vo->employer_id = DAO::getSingleValue($link, "select id from organisations where organisation_type = 2 limit 0,1");
        $vo->employer_location_id = DAO::getSingleValue($link,"SELECT id FROM locations WHERE organisations_id = '$vo->employer_id'");
        $vo->type = 5;
        $vo->username = trim($lastname) . trim($firstname) . trim("ecor");
        $vo->save($link, 1);

        http_redirect('do.php?_action=ecordia_learner_sync');
    }
}
?>
