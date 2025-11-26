<?php
class validate_ilr2025 implements IAction
{
    public function execute(PDO $link)
    {

        // Check arguments
        $qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
        $xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
        $submission_date = isset($_REQUEST['submission_date'])?$_REQUEST['submission_date']:'';
        $L01 = isset($_REQUEST['L01'])?$_REQUEST['L01']:'';
        $A09 = isset($_REQUEST['A09'])?$_REQUEST['A09']:'';
        $sub = isset($_REQUEST['sub'])?$_REQUEST['sub']:'';
        $contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

        $ilr = Ilr2025::loadFromXML($xml);
        $validator = new ValidateILR2025();
        try
        {
            $report = $validator->validate($link, $ilr);
        }
        catch(Exception $e)
        {
            echo('in action file' . $e->getMessage());
        }

        $xml_escaped = addslashes($xml);
        $qan_escaped = addslashes($qan);
        $submission_escaped = addslashes($submission_date);
        $L01_escaped = addslashes($L01);
        $A09_escaped = addslashes($A09);
        $sub_escaped = addslashes($sub);


        if($report != 'No Error')
        {

            $sql = "update ilr set is_valid = 0 where submission='$sub' and tr_id=$tr_id and L03='$qan' and contract_id=$contract_id";
            DAO::execute($link, $sql);
            $sql_update_tr = "UPDATE tr SET ilr_status = 0 WHERE tr.id = " . $tr_id . " AND contract_id = " . $contract_id . " AND l03 = '" . $qan . "'";
            DAO::execute($link, $sql_update_tr);
            header('Content-Type: text/xml');
            echo $report;
        }
        else
        {
            $sql = "update ilr set is_valid = 1 where submission='$sub' and tr_id=$tr_id and contract_id=$contract_id";
            DAO::execute($link, $sql);
            $sql_update_tr = "UPDATE tr SET ilr_status = 1 WHERE tr.id = " . $tr_id . " AND contract_id = " . $contract_id;
            DAO::execute($link, $sql_update_tr);
            header('Content-Type: application/xml; charset=utf-8');
            echo '<?xml version="1.0"?><report><success l03="' . htmlspecialchars($ilr->LearnRefNumber) . '" /></report>';

        }
    }


    private function checkPermissions(PDO $link, Course $c_vo)
    {
        if($_SESSION['role'] == 'admin')
        {
            return true;
        }
        elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
        {
            $acl = CourseACL::loadFromDatabase($link, $c_vo->id);
            $is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
            $is_local_admin = in_array('ladmin', $_SESSION['privileges']);
            $listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);

            return $is_employee && $is_local_admin;
        }
        elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
        {
            return false;
        }
        else
        {
            return false;
        }
    }
}
?>