<?php
class edit_qualification_structure implements IAction
{
    public function execute(PDO $link)
    {
        $auto_id = isset($_REQUEST['auto_id']) ? $_REQUEST['auto_id'] : '128';

        if($auto_id != '')
        {
            $vo = Qualification::loadFromDatabaseByAutoId($link, $auto_id);
            if(!$vo)
            {
                throw new Exception("No qualification found with id: ".$auto_id);
            }
        }
        else
        {
            $vo = new Qualification();
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_qualification_details&auto_id={$auto_id}", "Add/Edit Qualification");

        $type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
        $type_dropdown = DAO::getResultset($link, $type_dropdown);
        $assess_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
        $assess_dropdown = DAO::getResultset($link, $assess_dropdown);
        $level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
        $level_checkboxes = DAO::getResultset($link, $level_checkboxes);

        $assessment_method_dropdown = "SELECT id, type, null FROM lookup_evidence_type ORDER BY id;";
        $assessment_method_dropdown = DAO::getResultset($link, $assessment_method_dropdown);

        $evidence_type_dropdown = "SELECT id, content, null FROM lookup_evidence_content ORDER BY id;";
        $evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);

        $category_dropdown = "SELECT id, category, null FROM lookup_evidence_categories ORDER BY category;";
        $category_dropdown = DAO::getResultset($link, $category_dropdown);

        $status = array(
            array('1', 'Achieved', ''),
            array('0', 'Outstanding', ''));

        $qual_status = array(
            array('1', 'Full Qualification', ''),
            array('0', 'Unit Level', ''));


        require_once('tpl_edit_qualification_structure.php');
    }

}
?>