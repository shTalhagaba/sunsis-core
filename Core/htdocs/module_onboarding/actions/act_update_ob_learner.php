<?php
class update_ob_learner implements IAction
{
    public function execute(PDO $link)
    {
        $learner_info = [
            'id',
            'firstnames',
            'surname',
            'dob',
            'gender',
            'home_postcode',
            'home_email',
            'employer_id',
            'employer_location_id',
            'ks_assessment',
            'coach',
            'contract_id',
        ];

        $vo = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$_POST['id']}'");
        foreach($learner_info AS $field)
        {
            $vo->$field = isset($_REQUEST[$field])?$_REQUEST[$field]:'';
        }


        // clean some fields
        $vo->firstnames = ucfirst($vo->firstnames);
        $vo->surname = ucfirst($vo->surname);
        $vo->home_postcode = strtoupper($vo->home_postcode);
        $vo->home_email = strtolower($vo->home_email);

        DAO::saveObjectToTable($link, 'ob_learners', $vo);

        $log = new OnboardingLogger();
        $log->subject = 'RECORD UPDATED';
        $log->note = 'Learner record is updated';
        $log->ob_learner_id = $vo->id;
        $log->by_whom = $_SESSION['user']->id;
        $log->save($link);
        unset($log);

        http_redirect($_SESSION['bc']->getPrevious());
    }

    private function getUniqueUsername(PDO $link, $table, $column, $firstnames, $surname)
    {
        $number_of_attempts = 0;
        $i = 1;
        do
        {
            $number_of_attempts++;
            if($number_of_attempts > 20)
                return null;
            $username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 20));
            $username = str_replace(' ', '', $username);
            $username = str_replace("'", '', $username);
            $username = str_replace('"', '', $username);
            $username = $username . $i;
            $i++;
        }while((int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM {$table} WHERE {$column} = '{$username}'") > 0);
        if($username == '' || is_null($username))
            $username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 15)) . date('is');
        return strtolower($username);
    }

}