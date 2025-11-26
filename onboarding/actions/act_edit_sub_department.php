<?php
class edit_sub_department implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $sub_id = isset($_GET['id']) ? $_GET['id'] : '';
        $organisations_id = isset($_GET['organisations_id']) ? $_GET['organisations_id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_sub_department&id={$sub_id}&organisations_id={$organisations_id}", "Add/ Edit Sub Department");

        if( ($organisations_id == '') && ($sub_id == '') )
        {
            throw new Exception("Querystring argument id or organisations_id  must be specified");
        }

        $organisation = Organisation::loadFromDatabase($link, $organisations_id);

        if($sub_id == '')
        {
            // New record
            $sub = new stdClass();
            $sub->id = null;
            $sub->linked_dept_id = $organisation->id;
            $sub->dept_code = null;
            $sub->dept_name = null;
            $sub->pm_name = null;
            $sub->pm_telephone = null;
            $sub->pm_email = null;
        }
        else
        {
            $sub = DAO::getObject($link, "SELECT * FROM sub_departments WHERE id = '{$sub_id}'");
        }

        include('tpl_edit_sub_department.php');
    }

    private function renderOtherDepartments(PDO $link, $org_id)
    {
        $records = DAO::getResultset($link, "SELECT * FROM sub_departments WHERE linked_dept_id = '{$org_id}' ORDER BY id DESC", DAO::FETCH_ASSOC);
        if(count($records) == 0)
        {
            echo '<i class="fa fa-info-circle"></i> No other sub departments.';
        }
        else
        {
            foreach($records AS $sub)
            {
                echo $sub['dept_code'] != '' ? $sub['dept_code'] . '<br>' : '';
                echo $sub['dept_name'] != '' ? $sub['dept_name'] . '<br>' : '';
                echo $sub['pm_name'] != '' ? $sub['pm_name'] . '<br>' : '';
                echo $sub['pm_telephone'] != '' ? $sub['pm_telephone'] . '<br>' : '';
                echo $sub['pm_email'] != '' ? $sub['pm_email'] . '<br>' : '';
                echo '<hr> ';
            }
        }
    }
}
?>