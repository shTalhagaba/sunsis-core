<?php
class view_qualification_tabular  implements IAction
{
    public function execute(PDO $link)
    {
        $auto_id = isset($_REQUEST['auto_id'])?$_REQUEST['auto_id']:'';
        $qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
        $clients = isset($_REQUEST['clients'])?$_REQUEST['clients']:'';

        $_SESSION['bc']->add($link, "do.php?_action=view_qualification_tabular&id=" . $qualification_id . "&internaltitle=" . $internaltitle . "&clients=" . $clients, "View Qualification (Tabular)");

        if($qualification_id == '')
        {
            throw new Exception("Missing argument \$qualification_id");
        }

        $vo = Qualification::loadFromDatabase($link, $qualification_id, $internaltitle, $clients);

        if($auto_id != '')
        {
            $vo = Qualification::loadFromDatabaseByAutoId($link, $auto_id);
        }

        if(is_null($vo))
        {
            throw new Exception("Couldn't find qualification");
        }

        $evidence_methods = DAO::getLookupTable($link, 'select id, type from lookup_evidence_type');
        $evidence_cats = DAO::getLookupTable($link,"select id, category from lookup_evidence_categories");
        $evidence_types = DAO::getLookupTable($link,"select id, content from lookup_evidence_content");

        $evidence = DAO::getResultSet($link,"select id, type from lookup_evidence_type");
        $evidence2 = DAO::getResultSet($link,"select id, content from lookup_evidence_content");
        $evidence3 = DAO::getResultSet($link,"select id, category from lookup_evidence_categories");


        $qual_evidences = XML::loadSimpleXML($vo->evidences);

        require_once('tpl_view_qualification_tabular.php');
    }
}
?>