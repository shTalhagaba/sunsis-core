<?php
class provider_view_sign_skills_scan implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        $ob_learner = $tr->getObLearnerRecord($link);

        $_SESSION['bc']->add($link, "do.php?_action=provider_view_sign_skills_scan&id=" . $id, "View/Sign Skills Scan");

        $qualLevelsList = DAO::getLookupTable($link, "SELECT id, description FROM lookup_ob_qual_levels");
        $priorAttainList = DAO::getLookupTable($link,"SELECT DISTINCT code, CONCAT(description) FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");
        $qualGradesList = DAO::getLookupTable($link,"SELECT id, description FROM lookup_gcse_grades ORDER BY id;");

        $employer = Employer::loadFromDatabase($link, $ob_learner->employer_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $ss_stats = $tr->getKsbStats($link);

        $scroll_logic = 0;

        $skills_analysis = $tr->getSkillsAnalysis($link);

        include_once('tpl_provider_view_sign_skills_scan.php');
    }
}