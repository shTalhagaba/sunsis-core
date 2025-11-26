<?php
class otj_planner_tr implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        if($subaction == 'save')
        {
            $sections = DAO::getSingleColumn($link, "SELECT section_id FROM otj_tr_template_sections WHERE tr_id = '{$tr_id}'");
            foreach($sections AS $section_id)
            {
                $save_section = new stdClass();
                $save_section->section_id = $section_id;
                for($i = 2; $i <= OnboardingHelper::colsOfStandard($link, $tr->framework_id)+1; $i++)
                {
                    $col = "col_{$i}_otj";
                    $save_section->$col = isset($_POST["txt_section_{$section_id}_col_{$i}"]) ? $_POST["txt_section_{$section_id}_col_{$i}"] : '';
                }
                DAO::saveObjectToTable($link, "otj_tr_template_sections", $save_section);
            }

            $activities = [];
            foreach($_REQUEST AS $key => $value)
            {
                if( substr($key, 0, 9) == 'activity_' )
                {
                    $activity_id = str_replace('activity_', '', $key);
                    // $_a = new stdClass();
                    // $_a->activity_id = $activity_id;
                    // $_a->activity_desc = $value;
                    // DAO::saveObjectToTable($link, 'otj_tr_template_activities', $_a);
                    $activities[] = [
                        'activity_id' => $activity_id,
                        'activity_desc' => $value,
                    ];
                }
            }
            if(count($activities) > 0)
            {
                DAO::multipleRowInsert($link, 'otj_tr_template_activities', $activities);
            }

            if(isset($_REQUEST['provider_sign']) && $_REQUEST['provider_sign'] != '')
            {
                $otj_signatures = DAO::getObject($link, "SELECT * FROM otj_planner_signatures WHERE tr_id = '{$tr->id}'");
                if(!isset($otj_signatures->tr_id))
                {
                    $otj_signatures = new stdClass();
                    $otj_signatures->tr_id = $tr->id;
                }
                $otj_signatures->provider_sign = $_REQUEST['provider_sign'];
                $otj_signatures->provider_sign_id = DAO::getSingleValue($link, "SELECT id FROM users WHERE users.signature = '{$_REQUEST['provider_sign']}'");
                $otj_signatures->provider_sign_date = date('Y-m-d');
                DAO::saveObjectToTable($link, "otj_planner_signatures", $otj_signatures);
            }

            http_redirect($_SESSION['bc']->getPrevious());
        }

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $ob_learner = $tr->getObLearnerRecord($link);

        $otj_signatures = DAO::getObject($link, "SELECT * FROM otj_planner_signatures WHERE tr_id = '{$tr->id}'");

        $_SESSION['bc']->add($link, "do.php?_action=otj_planner_tr&tr_id={$tr->id}", "OTJ Planner");

        include_once('tpl_otj_planner_tr.php');
    }
}