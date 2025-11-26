<?php
class save_provider_sign_employer_agreement implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : '';

        $agreement = EmployerAgreement::loadFromDatabase($link, $id);
        $agreement->status = EmployerAgreement::TYPE_COMPLETED;
        $agreement->provider_sign = isset($_POST['provider_sign']) ? $_POST['provider_sign'] : '';
        $agreement->provider_sign_date = date('Y-m-d');
        $agreement->provider_user = $_SESSION['user']->id;
        $agreement->provider_sign_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;

        $agreement->save($link);

        //check if provider user has signature otherwise save it
        if($_SESSION['user']->signature == '')
        {
            $_SESSION['user']->signature = $_POST['provider_sign'];
            $_SESSION['user']->save($link);
        }

        if(!is_null($agreement->provider_sign))
        {
            $emp_directory = Repository::getRoot() . '/employers/' . $agreement->employer_id . '/signatures/';
            if(!is_dir($emp_directory))
            {
                mkdir("$emp_directory", 0777, true);
            }
            $tp_signature_file = $emp_directory . '/tp_sign_image.png';
            if(!is_file($tp_signature_file))
            {
                $signature_parts = explode('&', $agreement->provider_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $f_size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $f_size[1]);
                    imagepng($signature, $tp_signature_file, 0, NULL);
                }
            }
        }

        //EmployerAgreement::generatePdf($link, $agreement);

        http_redirect($_SESSION['bc']->getPrevious());
    }

}
?>