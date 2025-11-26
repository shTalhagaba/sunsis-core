<?php
class save_employer_agreement implements IAction
{
    public function execute(PDO $link)
    {
        $agreement = new EmployerAgreement();
        $agreement->populate($_POST);

        //copy bank details from employer to agreement
        $employer = Employer::loadFromDatabase($link, $agreement->employer_id);
        if($agreement->id == '')
        {
            $agreement->bank_name = $employer->bank_name;
            $agreement->account_name = $employer->account_name;
            $agreement->sort_code = $employer->sort_code;
            $agreement->account_number = $employer->account_number;
        }

        $save_employer = false;
        if(
            $agreement->employer_type != $employer->employer_type ||
            $agreement->funding_type != $employer->funding_type ||
            $agreement->expiry_date != $employer->agreement_expiry
        )
        {
            if($agreement->employer_type != $employer->employer_type)
                $employer->employer_type = $agreement->employer_type;
            if($agreement->funding_type != $employer->funding_type)
                $employer->funding_type = $agreement->funding_type;
            if($agreement->expiry_date != $employer->agreement_expiry)
                $employer->agreement_expiry = $agreement->expiry_date;
            $save_employer = true;
        }

        DAO::transaction_start($link);
        try{

            // check if file was previously uploaded and now user is uploading the new file
            if($_POST['id'] != '' && isset($_FILES['agreement_file']) && $_FILES['agreement_file']['name'] != '')
            {
                $dir_name = Repository::getRoot() . "/employers/{$employer->id}/agreements/{$agreement->id}";
                if(is_dir($dir_name))
                {
                    $files = Repository::readDirectory($dir_name);
                    foreach($files AS $f)
                        unlink($f->getAbsolutePath());
                }
            }

            if($save_employer)
                $employer->save($link);

            if(isset($_REQUEST['finance_contact']) && $_REQUEST['finance_contact'] != '')
            {
                $finance_contact = OrganisationContact::loadFromDatabase($link, $_REQUEST['finance_contact']);
                $agreement->finance_contact_name = $finance_contact->contact_name;
                $agreement->finance_contact_email = $finance_contact->contact_email;
                $agreement->finance_contact_telephone = $finance_contact->contact_telephone;
            }
            if(isset($_REQUEST['levy_contact']) && $_REQUEST['levy_contact'] != '')
            {
                $levy_contact = OrganisationContact::loadFromDatabase($link, $_REQUEST['levy_contact']);
                $agreement->levy_contact_name = $levy_contact->contact_name;
                $agreement->levy_contact_email = $levy_contact->contact_email;
                $agreement->levy_contact_telephone = $levy_contact->contact_telephone;
            }
            
            $agreement->save($link);

            if(isset($_FILES['agreement_file']) && $_FILES['agreement_file']['name'] != '')
            {
                $valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');
                $r = Repository::processFileUploads('agreement_file', "/employers/{$employer->id}/agreements/{$agreement->id}", $valid_extensions);
                if(!isset($r[0]))
                {
                    throw new Exception("Error uploading file, please try again.");
                }

                $agreement->file_upload = "Y";
                $agreement->status = EmployerAgreement::TYPE_COMPLETED;
                $agreement->save($link);
            }

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }




        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>