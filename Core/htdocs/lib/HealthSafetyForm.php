<?php
class HealthSafetyForm extends Entity
{
    public static function loadFromDatabase(PDO $link, $id, $location_id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes((string)$id);
        $query = <<<HEREDOC
SELECT
* from
health_safety_form
where id ='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new HealthSafetyForm();
            $row = $st->fetch();
            if($row)
            {
                $form->populate($row);
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
        }

        $location = Location::loadFromDatabase($link, $location_id);
        $employer = Employer::loadFromDatabase($link, $location->organisations_id);
        if($form->company_name=='')
            $form->company_name = $employer->legal_name;
        if($form->address_line_1=='')
            $form->address_line_1 = $location->address_line_1;
        if($form->address_line_2=='')
            $form->address_line_2 = $location->address_line_2;
        if($form->address_line_3=='')
            $form->address_line_3 = $location->address_line_3;
        if($form->address_line_4=='')
            $form->address_line_4 = $location->address_line_4;
        if($form->postcode=='')
            $form->postcode = $location->postcode;
        if($form->telephone=='')
            $form->telephone = $location->telephone;
        if($form->email=='')
            $form->email = $location->contact_email;
        if($form->employees=='')
            $form->employees = $employer->site_employees;

        if($form->contact_name == '')
            $form->contact_name = $location->contact_name;
        if($form->contact_phone == '')
            $form->contact_phone = $location->contact_telephone;
        if($form->contact_mobile == '')
            $form->contact_mobile = $location->contact_mobile;
        if($form->contact_email == '')
            $form->contact_email = $location->contact_email;

        return $form;
    }

    public function save(PDO $link)
    {
        $this->created_by = isset($_SESSION['user']->username) ? $_SESSION['user']->username : '';
        DAO::saveObjectToTable($link, 'health_safety_form', $this);
    }

    public function delete(PDO $link)
    {
        // Placeholder
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public $id = null;
    public $location_id = NULL;
    public $company_name = NULL;
    public $address_line_1 = NULL;
    public $address_line_2 = NULL;
    public $address_line_3 = NULL;
    public $address_line_4 = NULL;
    public $postcode = NULL;
    public $telephone = NULL;
    public $email = NULL;
    public $employees = NULL;

    public $contact_name = NULL;
    public $job_role = NULL;
    public $contact_phone = NULL;
    public $contact_mobile = NULL;
    public $contact_email = NULL;

    public $enforcement_actions = NULL;

    public $insurer_name = NULL;
    public $policy_number = NULL;
    public $expiry_date = NULL;

    public $policy_in_place = NULL;
    public $policy_in_place_comments = NULL;
    public $general_approach = NULL;
    public $manage_health_safety = NULL;
    public $who_does_what = NULL;
    public $health_safety_comments = NULL;


    public $comments1 = NULL;
    public $comments2 = NULL;
    public $comments3 = NULL;
    public $comments4 = NULL;
    public $comments5 = NULL;
    public $comments6 = NULL;
    public $comments7 = NULL;
    public $comments8 = NULL;
    public $comments9 = NULL;
    public $comments10 = NULL;
    public $comments11 = NULL;
    public $comments12 = NULL;
    public $comments13 = NULL;
    public $comments14 = NULL;
    public $comments15 = NULL;
    public $comments16 = NULL;
    public $comments17 = NULL;
    public $comments18 = NULL;

    public $assessment1 = NULL;
    public $assessment2 = NULL;
    public $assessment3 = NULL;
    public $assessment4 = NULL;
    public $assessment5 = NULL;
    public $assessment6 = NULL;
    public $assessment7 = NULL;
    public $assessment8 = NULL;
    public $assessment9 = NULL;
    public $assessment10 = NULL;
    public $assessment11 = NULL;
    public $assessment12 = NULL;
    public $assessment13 = NULL;
    public $assessment14 = NULL;
    public $assessment15 = NULL;
    public $assessment16 = NULL;
    public $assessment17 = NULL;
    public $assessment18 = NULL;
    public $assessment19 = NULL;
    public $nature_of_work = NULL;

    public $signature_assessor_font = NULL;
    public $signature_assessor_name = NULL;
    public $signature_assessor_date = NULL;

    public $signature_employer_font = NULL;
    public $signature_employer_name = NULL;
    public $signature_employer_date = NULL;
    public $employer_job_title = NULL;

    public $autosave = NULL;
    public $created_by = NULL;
    public $manager_attendance = NULL;

}
?>