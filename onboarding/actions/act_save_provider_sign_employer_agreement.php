<?php
class save_provider_sign_employer_agreement implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : '';

        $agreement = EmployerAgreement::loadFromDatabase($link, $id);

        // copy the changes made by employer to other related entities
        $employer_rep = OrganisationContact::loadFromDatabase($link, DAO::getSingleValue($link, "SELECT employer_rep FROM employer_agreements WHERE id = '{$_POST['id']}'"));
        $employer_rep->job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
        $employer_rep->contact_telephone = isset($_POST['contact_telephone']) ? $_POST['contact_telephone'] : '';

        DAO::transaction_start($link);
        try {

            if ($agreement->finance_contact_name != '') {
                $finance_contact_name = addslashes(trim($agreement->finance_contact_name));
                $finance_contact_id = DAO::getSingleValue($link, "SELECT contact_id FROM organisation_contacts WHERE TRIM(contact_name) = '{$finance_contact_name}' AND job_role = '4'");
                if ($finance_contact_id == '') {
                    $finance_contact = new OrganisationContact($employer_id);
                    $finance_contact->job_role = 4;
                } else {
                    $finance_contact = OrganisationContact::loadFromDatabase($link, $finance_contact_id);
                    if ($finance_contact->job_role != 4) {
                        $finance_contact->job_role = 4;
                        $finance_contact->id = null;
                    }
                }
                $finance_contact->contact_name = $agreement->finance_contact_name;
                $finance_contact->contact_email = $agreement->finance_contact_email;
                $finance_contact->contact_telephone = $agreement->finance_contact_telephone;
                $finance_contact->save($link);
                $agreement->finance_contact = $finance_contact->contact_id;
            }

            if ($agreement->levy_contact_name != '') {
                $levy_contact_name = addslashes(trim($agreement->levy_contact_name));
                $levy_contact_id = DAO::getSingleValue($link, "SELECT contact_id FROM organisation_contacts WHERE TRIM(contact_name) = '{$levy_contact_name}' AND job_role = '5'");
                if ($levy_contact_id == '') {
                    $levy_contact = new OrganisationContact($employer_id);
                    $levy_contact->job_role = 5;
                } else {
                    $levy_contact = OrganisationContact::loadFromDatabase($link, $levy_contact_id);
                    if ($levy_contact->job_role != 5) {
                        $levy_contact->job_role = 5;
                        $levy_contact->id = null;
                    }
                }
                $levy_contact->contact_name = $agreement->levy_contact_name;
                $levy_contact->contact_email = $agreement->levy_contact_email;
                $levy_contact->contact_telephone = $agreement->levy_contact_telephone;
                $levy_contact->save($link);
                $agreement->levy_contact = $levy_contact->contact_id;
            }

            $employer = Employer::loadFromDatabase($link, $employer_id);
            $employer->bank_name = $agreement->bank_name;
            $employer->account_name = $agreement->account_name;
            $employer->account_number = $agreement->account_number;
            $employer->sort_code = $agreement->sort_code;
            if( !in_array(DB_NAME, ["am_ela", "am_superdrug"]) )
            {
                $employer->site_employees = $agreement->avg_no_of_employees;
            }
            $employer->company_number = $agreement->company_number;

            $employer->save($link);

            if($_POST['provider_sign'] != '')
            {
                $agreement->status = EmployerAgreement::TYPE_COMPLETED;
                $agreement->provider_sign = $_POST['provider_sign'];
                $agreement->provider_sign_date = date('Y-m-d H:i:s');
                $agreement->provider_sign_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
                $agreement->provider_sign_id = $_SESSION['user']->id;

                if ($_SESSION['user']->signature == '') 
                {
                    $_SESSION['user']->signature = $_POST['provider_sign'];
                    $_SESSION['user']->save($link);
                }
    
            }

            $agreement->save($link);

            // save provider signatures
            if($_POST['provider_sign'] != '')
            {

                $provider_signatures_log = (object)[
                    'entity_id' => $agreement->id,
                    'entity_type' => 'employer_agreements',
                    'user_sign' => $_POST['provider_sign'],
                    'user_sign_date' => date('Y-m-d H:i:s'),
                    'user_sign_name' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname,
                    'user_type' => 'PROVIDER',
                ];

                DAO::saveObjectToTable($link, "documents_signatures", $provider_signatures_log);
            }

            DAO::transaction_commit($link);
        } catch (Exception $ex) {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        

        //EmployerAgreement::generatePdf($link, $agreement);

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
