<?php
class save_sub_department implements IAction
{
    public function execute(PDO $link)
    {
        $sub = new stdClass();
        $sub->id = $_POST['id'];
        $sub->linked_dept_id = $_POST['linked_dept_id'];
        $sub->dept_code = substr(strtoupper($_POST['dept_code'] ?? ''), 0, 7);
        $sub->dept_name = substr(strtoupper($_POST['dept_name'] ?? ''), 0, 99);
        $sub->pm_name = substr($_POST['pm_name'], 0, 79);
        $sub->pm_telephone = substr($_POST['pm_telephone'], 0, 49);
        $sub->pm_email = substr($_POST['pm_email'], 0, 99);

        DAO::saveObjectToTable($link, 'sub_departments', $sub);

        http_redirect($_SESSION['bc']->getPrevious());

    }
}
?>