<?php
class otj_planner implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($subaction == 'save')
        {
            $sections = DAO::getSingleColumn($link, "SELECT section_id FROM otj_prog_template_sections");
            foreach($sections AS $section_id)
            {
                $save_section = new stdClass();
                $save_section->section_id = $section_id;
                for($i = 2; $i <= 8; $i++)
                {
                    $col = "col_{$i}_otj";
                    $save_section->$col = isset($_POST["txt_section_{$section_id}_col_{$i}"]) ? $_POST["txt_section_{$section_id}_col_{$i}"] : '';
                }
                DAO::saveObjectToTable($link, "otj_prog_template_sections", $save_section);
            }
        }

        include_once('tpl_otj_planner.php');
    }
}