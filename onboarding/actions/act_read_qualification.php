<?php
class read_qualification  implements IAction
{
    public $main_tree = [];
    public function execute(PDO $link)
    {
        $auto_id = isset($_REQUEST['auto_id'])?$_REQUEST['auto_id']:'';
        $qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
        $clients = isset($_REQUEST['clients'])?$_REQUEST['clients']:'';

        $_SESSION['bc']->add($link, "do.php?_action=read_qualification&id=" . $qualification_id . "&clients" . $clients . "&internaltitle=" . rawurlencode($internaltitle) . '&auto_id=' . $auto_id, "View Qualification");

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

        $evidence = DAO::getResultSet($link,"select id, type from lookup_evidence_type");
        $evidence2 = DAO::getResultSet($link,"select id, content from lookup_evidence_content");
        $evidence3 = DAO::getResultSet($link,"select id, category from lookup_evidence_categories");

        $qan = str_replace("/","",$qualification_id);
        $lad = DAO::getSingleValue($link, "select LEARNING_AIM_ID from lad201112.learning_aim where LEARNING_AIM_REF = '$qan' ");

        $vo->evidences = str_replace('?&pound;', '£', $vo->evidences);
        $qual_evidences = XML::loadSimpleXML($vo->evidences);

        $this->main_tree = $this->buildTree($qual_evidences);

        if(count($this->main_tree) == 0)
            $this->processUnit($qual_evidences, $this->main_tree);

        require_once('tpl_read_qualification.php');
    }

    public function buildTree($qual_evidences)
    {
        if(!isset($qual_evidences->units))
        {
            return $this->processUnit($qual_evidences);
        }
        $tree = [];
        foreach($qual_evidences->units AS $ev_unit_group)
        {
            $ug_attributes = (array)$ev_unit_group->attributes();
            $ug_attributes = $ug_attributes['@attributes'];
            if(!isset($ug_attributes['title'])) continue;

            $tree[] = (object)[
                'text' => html_entity_decode($ug_attributes['title']),
                'icon' => "fa fa-bookmark",
                //'backColor' => "#50C878",
                'tags' => ['Unit Group'],
                'nodes' => $this->buildTree($ev_unit_group),
            ];
        }
        return $tree;
    }

    public function processUnit($qual_evidences)
    {
        $tree = [];
        foreach($qual_evidences->unit AS $ev_unit)
        {
            $elements = [];
            $unit = new stdClass();
            $u_attributes = (array)$ev_unit->attributes();
            $u_attributes = $u_attributes['@attributes'];
            if(!isset($u_attributes['title'])) continue;
            $unit->text = html_entity_decode($u_attributes['title']);
            $unit->icon = "fa fa-book";
            //$unit->backColor = "#00FA9A";

            foreach($ev_unit->element AS $ev_element)
            {
                $evidences = [];
                $element = new stdClass();
                $e_attributes = (array)$ev_element->attributes();
                $e_attributes = $e_attributes['@attributes'];
                if(!isset($e_attributes['title'])) continue;
                $element->text = html_entity_decode($e_attributes['title']);
                //$element->backColor = "#ECFFDC";

                foreach($ev_element->evidence AS $ev_evidence)
                {
                    $evidence = new stdClass();
                    $ev_attributes = (array)$ev_evidence->attributes();
                    $ev_attributes = $ev_attributes['@attributes'];
                    if(!isset($ev_attributes['title'])) continue;
                    $evidence->text = html_entity_decode($ev_attributes['title']);
                    $evidence->tags = isset($ev_attributes['delhours']) ? [$ev_attributes['delhours'] . ' delivery hours'] : [];

                    $evidences[] = $evidence;
                }

                $element->nodes = $evidences;
                $elements[] = $element;
            }

            if(count($elements) > 1)
                $unit->tags = [count($elements) . ' elements', 'Unit'];
            else
                $unit->tags = [count($elements) . ' element', 'Unit'];
            $unit->nodes = $elements;
            $tree[] = $unit;
        }
        return $tree;
    }
}
?>
