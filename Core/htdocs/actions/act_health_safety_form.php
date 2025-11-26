<?php
class health_safety_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $location_id = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : '';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '1';
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $output = isset($_REQUEST['output']) ? $_REQUEST['output'] : '';

        $form_arf = HealthSafetyForm::loadFromDatabase($link, $id, $location_id);

        include('tpl_health_safety_form.php');
    }
}
?>