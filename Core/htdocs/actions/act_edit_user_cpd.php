<?php
class edit_user_cpd implements IAction
{
    public function execute(PDO $link)
    {
        if(isset($_POST['cpd']))
        {
            $this->saveCpdEntry($link, $_POST);
        }

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_user_cpd&id=" . $id, "Add/ Edit CPD Entry");

        if($id == '')
        {
            $cpd = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM users_cpd");
            foreach($records AS $key => $value)
                $cpd->$value = null;
        }
        else
        {
            $cpd = DAO::getObject($link, "SELECT * FROM users_cpd WHERE id = '{$id}'");
        }

        $routeways_ddl = [
            ['Data', 'Data'],
            ['DM', 'DM'],
            ['IT', 'IT'],
            ['SWD', 'SWD'],
        ];

        $types_ddl = [
            ['BU', 'Business Understanding'],
            ['Of', 'Ofsted'],
            ['Ot', 'Other'],
            ['P', 'Professional'],
            ['T', 'Technical'],
        ];

        include('tpl_edit_user_cpd.php');
    }

    public function saveCpdEntry(PDO $link, $input)
    {
        $entry = new stdClass();
        $entry->id = $input['id'];
        $entry->user_id = $input['user_id'];
        $entry->routeway = $input['routeway'];
	$entry->start_date = $input['start_date'];
        $entry->start_time = $input['start_time'];
	$entry->end_date = $input['end_date'];
        $entry->end_time = $input['end_time'];
        $entry->type = $input['type'];
        $entry->comments = substr($input['comments'], 0, 799);

        DAO::saveObjectToTable($link, "users_cpd", $entry);

        $_SESSION['alert-success'] = 'CPD entry is saved successfully.';

        http_redirect("do.php?_action=view_users_cpd");

    }
}
?>