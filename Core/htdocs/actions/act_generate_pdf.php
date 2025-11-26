<?php
class generate_pdf extends ActionController
{

    public function indexAction(PDO $link)
    {

    }

    public function employerAgreementAction(PDO $link)
    {

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $agreement = EmployerAgreement::loadFromDatabase($link, $id);

        EmployerAgreement::generatePdf($link, $agreement);

    }

}