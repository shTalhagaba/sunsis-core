<?php
class employer_schedule_completed implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $k = isset($_REQUEST['k']) ? $_REQUEST['k'] : '';
        if($k == '')
            http_redirect('do.php?_action=error_page');

        $valid = DAO::getSingleValue($link, "SELECT ob_tr.id FROM ob_tr WHERE MD5(CONCAT('sunesis_form_completed_for_',ob_tr.id)) = '{$k}'");
        if($valid == '')
            http_redirect('do.php?_action=error_page');

        OnboardingHelper::generateCompletionPage($link, $valid);

    }
}