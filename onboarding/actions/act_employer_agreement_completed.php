<?php
class employer_agreement_completed implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $k = isset($_REQUEST['k']) ? $_REQUEST['k'] : '';
        if($k == '')
            http_redirect('do.php?_action=error_page');

        $valid = DAO::getSingleValue($link, "SELECT employer_agreements.id FROM employer_agreements WHERE MD5(CONCAT('sunesis_employer_agreement_form_completed_for_',employer_agreements.id)) = '{$k}'");
        if($valid == '')
            http_redirect('do.php?_action=error_page');

        EmployerAgreement::generateCompletionPage($link, $valid);

    }
}