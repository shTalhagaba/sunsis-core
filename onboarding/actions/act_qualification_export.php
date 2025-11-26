<?php
class qualification_export implements IAction
{
    public function execute(PDO $link)
    {
        $auto_id = isset($_REQUEST['auto_id'])?$_REQUEST['auto_id']:'';

        $vo = Qualification::loadFromDatabaseByAutoId($link, $auto_id);

        if(is_null($vo))
        {
            throw new Exception("Couldn't find qualification");
        }

        $filename = "QualificationTabularView";

        $evidence_assessment_methods = DAO::getLookupTable($link,"select id, type from lookup_evidence_type");
        $evidence_types = DAO::getLookupTable($link,"select id, content from lookup_evidence_content");
        $evidence_cats = DAO::getLookupTable($link,"select id, category from lookup_evidence_categories");

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

        // Internet Explorer requires two extra headers when downloading files over HTTPS
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }

        $pageDom = XML::loadXmlDom(utf8_encode($vo->evidences));
        $e = $pageDom->getElementsByTagName('unit');
        echo "Unit Title,Unit Reference,Unit Owner Reference,Unit Credits,Unit GLH,Unit Status,";
        echo "Element Title,Evidence Title,Evidence Reference,Evidence Delivery Hours,Evidence Assessment Method,Evidence Type,Evidence Category\r\n";
        foreach($e as $_unit)
        {
            $e2 = $_unit->getElementsByTagName('element');
            foreach($e2 as $_element)
            {
                $e3 = $_element->getElementsByTagName('evidence');
                foreach($e3 as $_evidence)
                {
                    // unit columns
                    echo HTML::csvSafe($_unit->getAttribute('title')) . ",";
                    echo HTML::csvSafe($_unit->getAttribute('reference')) . ",";
                    echo HTML::csvSafe($_unit->getAttribute('owner_reference')) . ",";
                    echo HTML::csvSafe($_unit->getAttribute('credits')) . ",";
                    echo HTML::csvSafe($_unit->getAttribute('glh')) . ",";
                    if( $_unit->getAttribute('mandatory') == true || $_unit->getAttribute('mandatory') == 'true' )
                        echo "Mandatory,";
                    else
                        echo "Optional,";

                    // element columns
                    echo HTML::csvSafe($_element->getAttribute('title')) . ",";

                    // evidence columns
                    echo HTML::csvSafe($_evidence->getAttribute('title')) . ",";
                    echo HTML::csvSafe($_evidence->getAttribute('reference')) . ",";
                    echo HTML::csvSafe($_evidence->getAttribute('delhours')) . ",";
                    echo isset($evidence_assessment_methods[$_evidence->getAttribute('method')]) ? HTML::csvSafe($evidence_assessment_methods[$_evidence->getAttribute('method')]) . "," : ",";
                    echo isset($evidence_types[$_evidence->getAttribute('etype')]) ? HTML::csvSafe($evidence_types[$_evidence->getAttribute('etype')]) . "," : ",";
                    echo isset($evidence_cats[$_evidence->getAttribute('cat')]) ? HTML::csvSafe($evidence_cats[$_evidence->getAttribute('cat')]) . "," : ",";
                    echo "\r\n";
                }
            }
        }
    }
}
?>