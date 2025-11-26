<?php
class save_crm_note implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $mode = isset($_POST['mode']) ? $_POST['mode'] : '';

        $organisation_id = isset($_POST['organisation_id']) ? $_POST['organisation_id'] : '';
        $pool_id = isset($_POST['pool_id']) ? $_POST['pool_id'] : '';

        $redirect_link = 'do.php?_action=read_employer&id='.$organisation_id;
		if(DB_NAME == "am_presentation")
			$redirect_link = 'do.php?_action=rec_read_employer&id='.$organisation_id;
        if($organisation_id != '')
        {
            $organisation_type = DAO::getSingleValue($link, "SELECT organisation_type FROM organisations WHERE organisations.id = " . $organisation_id);
            if($organisation_type == 3)
                $redirect_link = 'do.php?_action=read_trainingprovider&id='.$organisation_id;
            elseif($organisation_type == 7)
                $redirect_link = 'do.php?_action=read_college&id='.$organisation_id;
			elseif($organisation_type == 9)
				$redirect_link = 'do.php?_action=read_hotel&id='.$organisation_id;
        }

        $vo = NULL;

        $db_update_table = 'crm_notes';

        $org_type = 1;
        if ( isset($_POST['pool_id']) )
        {
            $vo = new PoolCrmNote();
            $redirect_link = 'do.php?_action=read_employers_pool_emp&auto_id='.$_POST['pool_id'];
            // set the orgnaisation status type to 2 for an employer pool org.
            $org_type = 2;
            $db_update_table = 'employerpool_notes';
        }
        else
        {
            $vo = new CrmNote();
        }

        $vo->populate($_POST);

        if($pool_id != '')
            $vo->organisation_id = $pool_id;

        if ( !isset($vo->outcome) && isset($_POST['outcomeplus']) )
        {
            $vo->outcome = $_POST['outcomeplus'];
        }

		if(!isset($_REQUEST['prevention_alert']) || $_REQUEST['prevention_alert'] == '')
			$vo->prevention_alert = 'N';

        $vo->save($link);

        // save the priority flag and next_action_date across all records
        $sql_next_action = "update ".$db_update_table." set ";
        $nad_update = 0;
        $pri_update = 0;
        if ( isset($_REQUEST['next_action_date']) ) {
            $date = explode('/', $vo->next_action_date);
            $new_date = $date[2]."-".$date[1]."-".$date[0];
            $sql_next_action .= "next_action_date = '".$new_date."' ";
            $nad_update = 1;
        }
        if ( isset($_REQUEST['priority']) ) {
            if ($nad_update == 1 ) {
                $sql_next_action .= ", ";
            }
            $sql_next_action .= "priority = '1' ";
            $pri_update = 1;
        }
        else {
            if ($nad_update == 1 ) {
                $sql_next_action .= ", ";
            }
            $sql_next_action .= "priority = '0' ";
            $pri_update = 1;
        }

        if($id != '')
        {
            if($pool_id != '')
                $sql_next_action .= "where organisation_id = ".$pool_id . " AND id = " . $id;
            else
                $sql_next_action .= "where organisation_id = ".$organisation_id . " AND id = " . $id;

            if ( $nad_update == 1 || $pri_update == 1 ){
                DAO::execute($link, $sql_next_action);
            }
        }
        else
        {
            if($pool_id != '')
                $sql_next_action .= "where organisation_id = ".$pool_id;
            else
                $sql_next_action .= "where organisation_id = ".$organisation_id;

            if ( $nad_update == 1 || $pri_update == 1 ){
                DAO::execute($link, $sql_next_action);
            }
        }

        // save the next_action against the organsiation
        if($mode == 'new')
        {
            if ( isset($_REQUEST['next_action']) AND $organisation_id != '' )
            {
                $status_desc = DAO::getSingleValue($link, "SELECT description FROM lookup_crm_regarding WHERE id = ".$_REQUEST['next_action']);
                $sql_org_status = "insert into organisations_status ( org_id, org_status, org_status_comment, org_type, note_id ) values (".$organisation_id.",".$_REQUEST['next_action'].",'".$status_desc."',".$org_type.",".$vo->id.");";
                DAO::execute($link, $sql_org_status);
            }
            elseif ( isset($_REQUEST['next_action']) AND $pool_id != '' )
            {
                $status_desc = DAO::getSingleValue($link, "SELECT description FROM lookup_crm_regarding WHERE id = ".$_REQUEST['next_action']);
                $sql_org_status = "insert into organisations_status ( org_id, org_status, org_status_comment, org_type, note_id ) values (".$pool_id.",".$_REQUEST['next_action'].",'".$status_desc."',".$org_type.",".$vo->id.");";
                DAO::execute($link, $sql_org_status);
            }
        }
        elseif($mode == 'edit')
        {
            if ( isset($_REQUEST['next_action']) AND $organisation_id != '' )
            {
                $status_desc = DAO::getSingleValue($link, "SELECT description FROM lookup_crm_regarding WHERE id = ".$_REQUEST['next_action']);
                $sql_org_status = "UPDATE organisations_status SET org_status = " . $_REQUEST['next_action'] . ", org_status_comment = '" . $status_desc . "' WHERE note_id = " . $vo->id . " AND org_id = " . $organisation_id;
                DAO::execute($link, $sql_org_status);
            }
            elseif ( isset($_REQUEST['next_action']) AND $pool_id != '' )
            {
                $status_desc = DAO::getSingleValue($link, "SELECT description FROM lookup_crm_regarding WHERE id = ".$_REQUEST['next_action']);
                $sql_org_status = "UPDATE organisations_status SET org_status = " . $_REQUEST['next_action'] . ", org_status_comment = '" . $status_desc . "' WHERE note_id = " . $vo->id . " AND org_id = " . $pool_id;
                DAO::execute($link, $sql_org_status);
            }
        }
        //http_redirect($_SESSION['bc']->getPrevious());
        http_redirect($redirect_link);
    }
}
?>