<?php
class view_ob_document implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
        $doc = isset($_REQUEST['doc']) ? $_REQUEST['doc'] : '';
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';

        $company_name = "Lead";

		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		$header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

        
        include_once('tpl_view_ob_document.php');
    }
}