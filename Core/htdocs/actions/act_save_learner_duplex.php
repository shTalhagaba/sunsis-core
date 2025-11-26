<?php
class save_learner_duplex implements IAction
{
    public function execute(PDO $link)
    {

        $vo = new User();
        $vo->populate($_POST);
        $vo->username = $vo->id == '' ? $this->getUniqueUsername($link, $vo->firstnames, $vo->surname) : $vo->username;
        $vo->home_postcode = strtoupper($vo->home_postcode);

        if($vo->id == '')
        {
            do
            {
                $pwd = PasswordUtilities::generateDatePassword();
                $pwd = PasswordUtilities::randomCapitalisation($pwd, 1);
                $pwd = PasswordUtilities::replaceSpacesWithNumbers($pwd);
                $validationResults = PasswordUtilities::checkPasswordStrength($pwd, PasswordUtilities::getIllegalWords());
            } while($validationResults['code'] == 0);
            $vo->password = $pwd;
            $vo->pwd_sha1 = sha1($pwd);
            $vo->web_access = 0;
    
            $vo->created = date('Y-m-d H:i:s');
        }

        $vo->type = User::TYPE_LEARNER;

        $location = Location::loadFromDatabase($link, $vo->employer_location_id);
        $vo->work_address_line_1 = $location->address_line_1;
        $vo->work_address_line_2 = $location->address_line_2;
        $vo->work_address_line_3 = $location->address_line_3;
        $vo->work_address_line_4 = $location->address_line_4;
        $vo->work_postcode = $location->postcode;
        $vo->work_telephone = $location->telephone;
        $vo->work_fax = $location->fax;

	if(!isset($_POST['l24']))
        {
            $vo->l24 = '';
        }
	if(!isset($_POST['l41a']))
        {
            $vo->l41a = '';
        }


        DAO::transaction_start($link);
        try
        {
	    $vo->who_created = $_SESSION['user']->username;

            if($vo->id == '')
                $vo->save($link, true);
            else
                $vo->save($link, false);

            $level_ids = [
				'L1' => 'level1_date',
				'L2' => 'level2_date',
                'L3' => 'level3_date',
                'L4' => 'level4_date',
                'ML3' => 'ML3_date',
                'FG' => 'FG_date',
            ];
            foreach($level_ids AS $key => $value)
            {
                if($_REQUEST[$value] != '')
                {
                    $training = DAO::getObject($link, "SELECT id FROM training WHERE training.schedule_id = '{$_REQUEST[$value]}' AND training.learner_id = '{$vo->id}'");
                    if(isset($training->id))
                    {
                        $training->schedule_id = $_REQUEST[$value];
                    }
                    else
                    {
                        $training = new stdClass();
                        $training->schedule_id = $_REQUEST[$value];
                        $training->learner_id = $vo->id;
			$training->booked_date = date('Y-m-d');
                    }
                    DAO::saveObjectToTable($link, "training", $training);
                }
            }
            DAO::execute($link, "DELETE FROM training WHERE 
                training.learner_id = '{$vo->id}' AND 
				training.schedule_id != '{$_REQUEST['level1_date']}' AND 
                training.schedule_id != '{$_REQUEST['level2_date']}' AND 
                training.schedule_id != '{$_REQUEST['level3_date']}' AND 
                training.schedule_id != '{$_REQUEST['level4_date']}' AND
                training.schedule_id != '{$_REQUEST['ML3_date']}' AND 
                training.schedule_id != '{$_REQUEST['FG_date']}'
                ");

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect("do.php?_action=read_learner&username={$vo->username}&id={$vo->id}");
    }

    private function getUniqueUsername(PDO $link, $firstnames, $surname)
    {
        $firstnames = str_replace("'", "", $firstnames);
        $firstnames = str_replace("-", "", $firstnames);
        $surname = str_replace("'", "", $surname);
        $surname = str_replace("-", "", $surname);

        $number_of_attempts = 0;
        $i = 1;
        do
        {
            $number_of_attempts++;
            if($number_of_attempts > 29)
                return null;
            $username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 20));
            $username = str_replace(' ', '', $username);
            $username = str_replace("'", '', $username);
            $username = str_replace('"', '', $username);
            $username = $username . $i;
            $i++;
        }while((int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE username = '$username'") > 0);
        if($username == '' || is_null($username))
            $username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 15)) . date('is');
        return strtolower($username);
    }
}
?>
