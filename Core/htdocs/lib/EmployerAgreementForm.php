<?php
class EmployerAgreementForm extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes((string)$id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	employer_agreement
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = new EmployerAgreementForm();
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

        return $form;
    }

    public function save(PDO $link)
    {
        DAO::saveObjectToTable($link, 'employer_agreement', $this);
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
    public $employer_id = NULL;
    public $meeting_date = NULL;
    public $paye_bill = NULL;
    public $company_size = NULL;
    public $employer = NULL;
    public $company_number = NULL;
    public $employer_address = NULL;
    public $employer_name = NULL;
    public $employer_title = NULL;
    public $employer_email = NULL;
    public $employer_telephone = NULL;
    public $employer_postal_address = NULL;
    public $provider = NULL;
    public $provider_company_number = NULL;
    public $provider_address = NULL;
    public $ukprn = NULL;
    public $vat = NULL;
    public $provider_name = NULL;
    public $provider_title = NULL;
    public $provider_email = NULL;
    public $provider_telephone = NULL;
    public $provider_postal_address = NULL;
    public $fixed_multiple = NULL;
    public $administration_service = NULL;
    public $complaints = NULL;
    public $schedule1 = NULL;
    public $schedule2 = NULL;
    public $schedule3 = NULL;
    public $contact_name = NULL;
    public $contact_telephone = NULL;
    public $contact_email = NULL;
    public $invoice_address = NULL;
    public $invoice_contact = NULL;
    public $bank_name = NULL;
    public $bank_address = NULL;
    public $account_name = NULL;
    public $account_number = NULL;
    public $sort_code = NULL;


    public $signature_assessor_font = NULL;
    public $signature_assessor_name = NULL;
    public $signature_assessor_date = NULL;

    public $signature_employer_font = NULL;
    public $signature_employer_name = NULL;
    public $signature_employer_date = NULL;




}
?>