<?php
class save_organisation_repository implements IAction
{
    public function execute(PDO $link)
    {
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id']:'';
        $section = isset($_REQUEST['section']) ? $_REQUEST['section']:'';
        if(!$organisation_id)
        {
            throw new Exception("Missing querystring argument, organisation_id");
        }

        $organisation_type = DAO::getSingleValue($link, "SELECT organisations.`organisation_type` FROM organisations WHERE organisations.id = '{$organisation_id}'");
        switch($organisation_type)
        {
            case Organisation::TYPE_CLIENT:
                $target_directory = "/systemowner/" . $organisation_id;
                break;
            case Organisation::TYPE_EMPLOYER:
                $target_directory = "/employers/" . $organisation_id;
                break;
            case Organisation::TYPE_TRAINING_PROVIDER:
                $target_directory = "/trainingproviders/" . $organisation_id;
                break;
            case Organisation::TYPE_CONTRACT_HOLDER:
                $target_directory = "/contractholders/" . $organisation_id;
                break;
            case Organisation::TYPE_SUB_CONTRACTOR:
                $target_directory = "/subcontractors/" . $organisation_id;
                break;
            case Organisation::TYPE_DEPARTMENT:
                $target_directory = "/departments/" . $organisation_id;
                break;
            default:
                $target_directory = "/clients/" . $organisation_id;
                break;
        }

        if($section != '')
            $target_directory .= "/" . $section;

        $valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

        Repository::processFileUploads('uploaded_organisation_file', $target_directory, $valid_extensions);

        http_redirect($_SESSION['bc']->getCurrent().'&section='.$section);
    }

}
?>