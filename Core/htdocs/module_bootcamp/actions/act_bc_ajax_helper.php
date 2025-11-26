<?php
class bc_ajax_helper extends ActionController
{
    public function indexAction( PDO $link )
    {
        
    }

    public function updateLearnerFromBcRegistraionAction(PDO $link)
    {

        $registrationId = isset($_REQUEST['registrationId']) ? $_REQUEST['registrationId'] : '';

        if($registrationId == '')
        {
            throw new Exception("Missing required information.");
        }

        $registration = Registration::loadFromDatabase($link, $registrationId);

        if($registration->is_finished != 'Y')
        {
            throw new Exception("The registration is not yet completed and signed.");
        }

        if($registration->is_synced)
        {
            throw new Exception("The learner record is already updated from this registration record.");
        }

        $learner = User::loadFromDatabaseById($link, $registration->entity_id);

        foreach([
            'firstnames',
            'surname',
            'gender',
            'dob',
            'ni',
            'home_address_line_1',
            'home_address_line_2',
            'home_address_line_3',
            'home_address_line_4',
            'home_postcode',
            'home_mobile',
            'hhs',
            'primary_lldd',
        ] AS $field)
        {
            $learner->$field = $registration->$field;
        }

        $learner->lldd_cat = $registration->llddcat;

        if($registration->employment_status == '10' && $registration->SEI == '1')
        {
            $learner->duplex_emp_status = 'Self Employed';
        }
        elseif($registration->employment_status == '10' && $registration->SEI == '0')
        {
            $learner->duplex_emp_status = 'Employed';
        }
        elseif($registration->employment_status == '11' && $registration->employment_status == '12')
        {
            $learner->duplex_emp_status = 'Unemployed';
        }

        DAO::transaction_start($link);
        try
        {
            $learner->save($link);

            $registration->is_synced = 1;
            $registration->save($link);

            DAO::transaction_commit($link);
        }
        catch(DatabaseException $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        echo "This registration record is successfully synced with the learner record.";
    }

    public function createLearnerFromBcRegistraionAction(PDO $link)
    {

        $registrationId = isset($_REQUEST['registrationId']) ? $_REQUEST['registrationId'] : '';

        if($registrationId == '')
        {
            throw new Exception("Missing required information.");
        }

        $registration = Registration::loadFromDatabase($link, $registrationId);

        if($registration->is_finished != 'Y')
        {
            throw new Exception("The registration is not yet completed and signed.");
        }

        if($registration->is_synced)
        {
            throw new Exception("The learner record is already updated from this registration record.");
        }

        $learner = new User;
        $learner->populate([
            'firstnames' => $registration->firstnames,
            'surname' => $registration->surname,
            'gender' => $registration->gender,
            'dob' => $registration->dob,
            'ni' => $registration->ni,
            'home_address_line_1' => $registration->home_address_line_1,
            'home_address_line_2' => $registration->home_address_line_2,
            'home_address_line_3' => $registration->home_address_line_3,
            'home_address_line_4' => $registration->home_address_line_4,
            'home_postcode' => $registration->home_postcode,
            'home_email' => $registration->home_email,
            'home_telephone' => $registration->home_telephone,
            'home_mobile' => $registration->home_mobile,
            'hhs' => $registration->hhs,
            'primary_lldd' => $registration->primary_lldd,
            'ethnicity' => $registration->ethnicity,
            'job_role' => $registration->current_job_title,
            'rui' => $registration->RUI,
            'pmc' => $registration->PMC,
            'type' => User::TYPE_LEARNER,
        ]);
        $learner->lldd_cat = $registration->llddcat;
        if($registration->LLDD == "Y")
        {
            $learner->l14 = 1;
        }
        elseif($registration->LLDD == "N")
        {
            $learner->l14 = 2;
        }
        elseif($registration->LLDD == "P")
        {
            $learner->l14 = 3;
        }

        if($registration->employment_status == '10' && $registration->SEI == '1')
        {
            $learner->duplex_emp_status = 'Self Employed';
        }
        elseif($registration->employment_status == '10' && $registration->SEI == '0')
        {
            $learner->duplex_emp_status = 'Employed';
        }
        elseif($registration->employment_status == '11' && $registration->employment_status == '12')
        {
            $learner->duplex_emp_status = 'Unemployed';
        }


        DAO::transaction_start($link);
        try
        {
            if($registration->employment_status == '10')
            {
                $employerLocation = $this->createEmployer($link, $registration);
            }
            else
            {
                $employerLocation = $this->getUnemployed($link);
            }

            $learner->username = $this->getUniqueUsername($link, $learner->firstnames, $learner->surname);
            $learner->password = PasswordUtilities::generateDatePassword();
            $learner->pwd_sha1 = sha1($learner->password);
            $learner->employer_id = $employerLocation->organisations_id;
            $learner->employer_location_id = $employerLocation->id;
            $learner->work_postcode = $employerLocation->postcode;
            $learner->work_telephone = $employerLocation->telephone;
            $learner->save($link);

            $registration->entity_id = $learner->id;
            $registration->entity_type = get_class($learner);
            $registration->is_synced = 1;
            $registration->save($link);

            DAO::transaction_commit($link);
        }
        catch(DatabaseException $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        echo "This registration record is successfully synced with the learner record.";
    }

    private function getUniqueUsername(PDO $link, $firstnames, $surname)
	{
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

    private function createEmployer(PDO $link, Registration $registration)
    {
        $employer = new Organisation();
        $employer->populate([
            'organisation_type' => Organisation::TYPE_EMPLOYER,
            'legal_name' => $registration->emp_status_employer,
            'trading_name' => $registration->emp_status_employer,
            'short_name' => $this->formatShortName($registration->emp_status_employer),
            'active' => '1',
        ]);
        $employer->save($link);

        $location = new Location();
        $location->populate([
            'organisations_id' => $employer->id,
            'is_legal_address' => 1,
            'full_name' => 'Main Site',
            'postcode' => $registration->workplace_postcode,
            'telephone' => $registration->emp_status_employer_tel,
            'contact_name' => $registration->employer_contact_name,
            'contact_email' => $registration->employer_contact_email,
        ]);
        $location->save($link);

        return $location;
    }
    
    private function getUnemployed(PDO $link)
    {
        $id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE legal_name LIKE '%Unemployed%' OR legal_name LIKE '%(Not attached to Employer)%'");
        if($id != '')
        {
            $employer = Organisation::loadFromDatabase($link, $id);
            return $employer->getMainLocation($link);
        }

        $employer = new Organisation();
        $employer->populate([
            'organisation_type' => Organisation::TYPE_EMPLOYER,
            'legal_name' => '(Not attached to Employer)',
            'trading_name' => '(Not attached to Employer)',
            'short_name' => 'no employer',
            'active' => '1',
        ]);
        $employer->save($link);

        $location = new Location();
        $location->populate([
            'organisations_id' => $employer->id,
            'is_legal_address' => 1,
            'full_name' => 'No Employer',
            'postcode' => 'NN1 1NN',
        ]);
        $location->save($link);

        return $location;
    }

    public function submitComplianceInfoAction(PDO $link)
    {
        $registraionId = isset($_REQUEST['registration_id']) ? $_REQUEST['registration_id'] : '';
        $registration = Registration::loadFromDatabase($link, $registraionId);
        $compliance = new stdClass();
        $compliance->registration_id = $registration->id;
        $compliance->compliance_status = isset($_REQUEST['compliance_status']) ? $_REQUEST['compliance_status'] : '';
        $compliance->comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : '';
        $compliance->extra_details = [
            'uk_residence_check' => isset($_REQUEST['uk_residence_check']) ? $_REQUEST['uk_residence_check'] : '',
            'age_check' => isset($_REQUEST['age_check']) ? $_REQUEST['age_check'] : '',
        ];
        $compliance->extra_details = json_encode($compliance->extra_details);
        $compliance->created_by = $_SESSION['user']->id;
        $compliance->created_at = date('Y-m-d');

        DAO::saveObjectToTable($link, 'registration_compliance', $compliance);

        $registration->is_compliant = $compliance->compliance_status;
        $registration->save($link);

        http_redirect('do.php?_action=read_bc_registration=&id=' . $registraionId);
    }

    private function formatShortName($input)
    {
        $output = str_replace(["'", "&", " "], ["", "", ""], $input);
        $output = substr($output, 0, 10);
        return $output;
    }
}