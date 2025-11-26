<?php
class add_remove_framework_quals implements IAction
{
    public function execute(PDO $link)
    {
        $fid = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
        if(trim($fid) == '')
        {
            throw new Exception('Missing querystring argument: framework id');
        }


        DAO::transaction_start($link);
        try
        {
            // deleting all the qualifications from this framework
            DAO::execute($link, "DELETE FROM framework_qualifications WHERE framework_id = '$fid' ");
            DAO::execute($link, "DELETE FROM milestones WHERE framework_id = '$fid' ");

            // add qualifications to this framework
            $fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM framework_qualifications");
            if( isset($_POST['selectedQuals']) && is_array($_POST['selectedQuals']) && count($_POST['selectedQuals']) > 0 )
            {
                foreach($_POST['selectedQuals'] AS $qual_auto_id)
                {
                    $qualification = DAO::getObject($link, "SELECT * FROM qualifications WHERE auto_id = '{$qual_auto_id}'");
                    $framework_qual = new stdClass();
                    foreach($fields AS $field)
                    {
                        $framework_qual->$field = isset($qualification->$field) ? $qualification->$field : null;
                    }
                    $framework_qual->framework_id = $fid;
		            $framework_qual->sequence = isset($_POST["sequence{$qual_auto_id}"]) ? $_POST["sequence{$qual_auto_id}"] : null;
                    $framework_qual->proportion = isset($_POST["proportion{$qual_auto_id}"]) ? $_POST["proportion{$qual_auto_id}"] : null;
                    $framework_qual->duration_in_months = isset($_POST["duration{$qual_auto_id}"]) ? $_POST["duration{$qual_auto_id}"] : null;
		            $framework_qual->offset_months = isset($_POST["offset_months{$qual_auto_id}"]) ? $_POST["offset_months{$qual_auto_id}"] : 0;
                    $framework_qual->main_aim = $qual_auto_id == $_POST["main_aim_radio"] ? 1 : 0;
                    DAO::saveObjectToTable($link, "framework_qualifications", $framework_qual);
                }
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect("do.php?_action=read_framework&id={$fid}");
    }
}
?>