<?php
class ajax_helper implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if ($subaction != '' && $subaction == 'update_lead_hwc') {
            $this->update_lead_hwc($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'preview_email_template') {
            $this->preview_email_template($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'view_sent_email') {
            $this->view_sent_email($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'add_learners_into_date') {
            $this->add_learners_into_date($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'remove_learner_from_training') {
            $this->remove_learner_from_training($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'fetch_employer_learners') {
            $this->fetch_employer_learners($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'add_training_schedule') {
            $this->add_training_schedule($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_training_schedule') {
            $this->delete_training_schedule($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'get_location_details') {
            echo $this->get_location_details($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'get_crm_company_details') {
            echo $this->get_crm_company_details($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_training_schedule') {
            $this->update_training_schedule($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'save_crm_product') {
            $this->save_crm_product($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'load_products') {
            $this->load_products($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'load_contacts') {
            $this->load_contacts($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_opportunity_hwc') {
            $this->update_opportunity_hwc($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_lead_status') {
            $this->update_lead_status($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_opportunity_status') {
            $this->update_opportunity_status($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_audits_tab') {
            $this->update_audits_tab($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_opportunities_audits_tab') {
            $this->update_opportunities_audits_tab($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_enquiry_status') {
            $this->update_enquiry_status($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_enquiry_audits_tab') {
            $this->update_enquiry_audits_tab($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'convert_enquiry_to_lead') {
            $this->convert_enquiry_to_lead($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'convert_lead_to_opportunity') {
            $this->convert_lead_to_opportunity($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'convert_opportunity') {
            $this->convert_opportunity($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'save_entity_comment') {
            $this->save_entity_comment($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'get_company_details') {
            echo $this->get_company_details($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'loadActivities') {
            if (isset($_REQUEST['activity_type']) && $_REQUEST['activity_type'] == 'email')
                $this->loadEmails($link);
            else
                $this->loadActivities($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'get_activity_detail') {
            $this->get_activity_detail($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'save_crm_activity') {
            $this->save_crm_activity($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_enquiry') {
            echo $this->delete_enquiry($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_lead') {
            echo $this->delete_lead($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_opportunity') {
            echo $this->delete_opportunity($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_crm_entity_comment') {
            echo $this->delete_crm_entity_comment($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_crm_entity_file') {
            echo $this->delete_crm_entity_file($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_owner') {
            echo $this->update_owner($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'prepareTimeSpentModal') {
            echo $this->prepareTimeSpentModal();
            exit;
        }
        if ($subaction != '' && $subaction == 'save_activity_time_spent') {
            $this->save_activity_time_spent($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'load_pool_locations') {
            $this->load_pool_locations($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'load_employer_locations') {
            $this->load_employer_locations($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'populate_other_info') {
            $this->populate_other_info($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'delete_crm_contact') {
            $this->delete_crm_contact($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'update_duplex_training_status') {
            $this->update_duplex_training_status($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'set_crm_note_as_actioned') {
            $this->set_crm_note_as_actioned($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'getCrmActivitiesCombinedView') {
            $this->getCrmActivitiesCombinedView($link);
            exit;
        }
        if ($subaction != '' && $subaction == 'toggleActivityCompletion') {
            $this->toggleActivityCompletion($link);
            exit;
        }
    }

    private function update_lead_hwc(PDO $link)
    {
        $lead_id = isset($_REQUEST['lead_id']) ? $_REQUEST['lead_id'] : '';
        $hwc = isset($_REQUEST['hwc']) ? $_REQUEST['hwc'] : '';
        if ($lead_id == '')
            throw new Exception('Missing querystring argument: hwc, lead_id');

        $lead = Lead::loadFromDatabase($link, $lead_id);
        $lead->hwc = $hwc;

        $existing_record = Lead::loadFromDatabase($link, $lead_id);
        $log_string = $existing_record->buildAuditLogString($link, $lead);
        if ($log_string != '') {
            $note = new Note();
            $note->subject = "Lead Updated";
            $note->note = $log_string;
            $note->is_audit_note = true;
            $note->parent_table = 'leads';
            $note->parent_id = $lead->id;
        }

        DAO::transaction_start($link);
        try {
            $lead->save($link);
            if (isset($note))
                $note->save($link);
            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        echo "Lead status updated";

        return 1;
    }

    private function update_lead_status(PDO $link)
    {
        $lead_id = isset($_REQUEST['lead_id']) ? $_REQUEST['lead_id'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        if ($lead_id == '')
            throw new Exception('Missing querystring argument: status, lead_id');

        $lead = Lead::loadFromDatabase($link, $lead_id);
        $lead->status = $status;

        $note = new Note();
        $note->subject = "Lead Updated";
        $note->note = $lead->status == 3 ? 'Lead is Won' : 'Lead is Lost';
        $note->is_audit_note = true;
        $note->parent_table = 'crm_leads';
        $note->parent_id = $lead->id;

        DAO::transaction_start($link);
        try {
            $lead->save($link);
            $note->save($link);
            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        echo "Lead status updated";

        return 1;
    }

    private function update_opportunity_status(PDO $link)
    {
        $opportunity_id = isset($_REQUEST['opportunity_id']) ? $_REQUEST['opportunity_id'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        if ($opportunity_id == '')
            throw new Exception('Missing querystring argument: status, opportunity_id');

        $opportunity = Opportunity::loadFromDatabase($link, $opportunity_id);
        $opportunity->status = $status;

        $note = new Note();
        $note->subject = "Opportunity Updated";
        $note->note = $opportunity->status == 3 ? 'Opportunity is Qualified' : 'Opportunity is Unqualified';
        $note->is_audit_note = true;
        $note->parent_table = 'crm_opportunities';
        $note->parent_id = $opportunity->id;

        DAO::transaction_start($link);
        try {
            $opportunity->save($link);
            $note->save($link);
            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        echo "Opportunity status updated";

        return 1;
    }

    private function update_opportunity_hwc(PDO $link)
    {
        $opportunity_id = isset($_REQUEST['opportunity_id']) ? $_REQUEST['opportunity_id'] : '';
        $hwc = isset($_REQUEST['hwc']) ? $_REQUEST['hwc'] : '';
        if ($opportunity_id == '')
            throw new Exception('Missing querystring argument: hwc, tr_id');

        $opportunity = Opportunity::loadFromDatabase($link, $opportunity_id);
        $opportunity->hwc = $hwc;

        $existing_record = Opportunity::loadFromDatabase($link, $opportunity_id);
        $log_string = $existing_record->buildAuditLogString($link, $opportunity);
        if ($log_string != '') {
            $note = new Note();
            $note->subject = "Opportunity Updated";
            $note->note = $log_string;
            $note->is_audit_note = true;
            $note->parent_table = 'opportunities';
            $note->parent_id = $opportunity->id;
        }

        DAO::transaction_start($link);
        try {
            $opportunity->save($link);
            if (isset($note))
                $note->save($link);
            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        echo "Lead status updated";

        return 1;
    }

    private function update_enquiry_status(PDO $link)
    {
        $enquiry_id = isset($_REQUEST['enquiry_id']) ? $_REQUEST['enquiry_id'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        if ($enquiry_id == '')
            throw new Exception('Missing querystring argument: enquiry_id');

        $enquiry = Enquiry::loadFromDatabase($link, $enquiry_id);
        $enquiry->status = $status;

        $existing_record = Enquiry::loadFromDatabase($link, $enquiry_id);
        $log_string = $existing_record->buildAuditLogString($link, $enquiry);
        if ($log_string != '') {
            $note = new Note();
            $note->subject = "Enquiry Updated";
            $note->note = $log_string;
            $note->is_audit_note = true;
            $note->parent_table = 'enquiries';
            $note->parent_id = $enquiry->id;
            $note->note = $log_string;
        }

        DAO::transaction_start($link);
        try {
            $enquiry->save($link);
            if (isset($note))
                $note->save($link);
            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        echo "Enquiry status updated";

        return 1;
    }

    private function update_owner(PDO $link)
    {
        $entity_id = isset($_POST['entity_id']) ? $_POST['entity_id'] : '';
        $entity_type = isset($_POST['entity_type']) ? $_POST['entity_type'] : '';
        if ($entity_id == '' || $entity_type == '')
            throw new Exception('Missing querystring arguments');

        $entity = $entity_type::loadFromDatabase($link, $entity_id);
        $entity->created_by = $_POST['owner'];

        $existing_record = $entity_type::loadFromDatabase($link, $entity_id);
        $log_string = $existing_record->buildAuditLogString($link, $entity);
        if ($log_string != '') {
            $note = new Note();
            $note->subject = $entity_type . " Updated";
            $note->note = $log_string;
            $note->is_audit_note = true;
            $note->parent_table = $entity->getTableName();
            $note->parent_id = $entity->id;
            $note->note = $log_string;
        }

        DAO::transaction_start($link);
        try {
            $entity->save($link);
            if (isset($note))
                $note->save($link);
            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        return 'Owner name is updated successfully.';
    }

    private function update_audits_tab(PDO $link)
    {
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
        $entity = $entity_type::loadFromDatabase($link, $entity_id);
        $entity->renderNotes($link);
    }

    private function update_opportunities_audits_tab(PDO $link)
    {
        $opportunity_id = isset($_REQUEST['opportunity_id']) ? $_REQUEST['opportunity_id'] : '';
        if ($opportunity_id == '')
            throw new Exception('Missing querystring argument:opportunity_id');

        $opportunity = Opportunity::loadFromDatabase($link, $opportunity_id);
        $opportunity->renderNotes($link);
    }

    private function update_enquiry_audits_tab(PDO $link)
    {
        $enquiry_id = isset($_REQUEST['enquiry_id']) ? $_REQUEST['enquiry_id'] : '';
        if ($enquiry_id == '')
            throw new Exception('Missing querystring argument:enquiry_id');

        $enquiry = Enquiry::loadFromDatabase($link, $enquiry_id);
        $enquiry->renderNotes($link);
    }

    private function convert_enquiry_to_lead(PDO $link)
    {
        $enquiry_id = isset($_REQUEST['enquiry_id']) ? $_REQUEST['enquiry_id'] : '';
        if ($enquiry_id == '')
            throw new Exception('Missing querystring argument:enquiry_id');

        $enquiry = Enquiry::loadFromDatabase($link, $enquiry_id);

        DAO::transaction_start($link);
        try {
            $lead = new Lead();
            $lead->populate($enquiry);
            $lead->id = null;
            $lead->enquiry_id = $enquiry->id;
            $lead->lead_title = $enquiry->enquiry_title;
	    $lead->lead_type = $enquiry->enquiry_type;
            $lead->status = 2;
            $lead->created_by = $_SESSION['user']->id;
	    $lead->industry = $enquiry->industry;
            $lead->save($link);

            $note = new Note();
            $note->subject = "Lead Created.";
            $note->is_audit_note = true;
            $note->parent_table = 'leads';
            $note->parent_id = $lead->id;
            $note->save($link);

            $enquiry->converted = 1;
            $enquiry->save($link);

            // also copy the enquiry activities to the lead
            $enquiry_activities = DAO::getResultset($link, "SELECT * FROM crm_activities WHERE entity_id = '{$enquiry->id}' AND entity_type = 'enquiry'", DAO::FETCH_ASSOC);
            foreach ($enquiry_activities as $_activity) {
                $_activity = (object)$_activity;
                $lead_activity = new CRMActivity();
                $lead_activity->populate($_activity);
                $lead_activity->id = null;
                $lead_activity->entity_id = $lead->id;
                $lead_activity->entity_type = 'lead';
                $lead_activity->parent_entity_id = $_activity->id;
                $lead_activity->parent_entity_type = $_activity->entity_type;
                $lead_activity->save($link);
            }

            // copy the enquiry contacts to the lead
            $enquiry_crm_contacts = DAO::getResultset($link, "SELECT * FROM crm_contacts WHERE entity_id = '{$enquiry->id}' AND entity_type = 'enquiry'", DAO::FETCH_ASSOC);
            foreach ($enquiry_crm_contacts as $_contact) {
                $_contact = (object)$_contact;
                $_contact->id = null;
                $_contact->entity_type = 'lead';
                $_contact->entity_id = $lead->id;
                DAO::saveObjectToTable($link, 'crm_contacts', $_contact);
            }

            // copy the enquiry comments to the lead
            $enquiry_crm_comments = DAO::getResultset($link, "SELECT * FROM crm_entities_comments WHERE entity_id = '{$enquiry->id}' AND entity_type = 'enquiry'", DAO::FETCH_ASSOC);
            foreach ($enquiry_crm_comments as $_comment) {
                $_comment = (object)$_comment;
                $_comment->id = null;
                $_comment->entity_type = 'lead';
                $_comment->entity_id = $lead->id;
                DAO::saveObjectToTable($link, 'crm_entities_comments', $_comment);
            }

            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect("do.php?_action=edit_lead&id={$lead->id}&org_id={$lead->company_id}&org_type={$lead->company_type}");
    }

    private function convert_lead_to_opportunity(PDO $link)
    {
        $lead_id = isset($_REQUEST['lead_id']) ? $_REQUEST['lead_id'] : '';
        if ($lead_id == '')
            throw new Exception('Missing querystring argument:lead_id');

        $lead = Lead::loadFromDatabase($link, $lead_id);

        DAO::transaction_start($link);
        try {
            $opportunity = new Opportunity();
            $opportunity->populate($lead);
            $opportunity->id = null;
            $opportunity->status = 2;
            $opportunity->created_by = $_SESSION['user']->id;
            $opportunity->lead_id = $lead->id;
            $opportunity->opportunity_title = $lead->lead_title;
	    $opportunity->industry = $lead->industry;
            $opportunity->save($link);

            $note = new Note();
            $note->subject = "Opportunity Created.";
            $note->is_audit_note = true;
            $note->parent_table = 'opportunities';
            $note->parent_id = $opportunity->id;
            $note->save($link);

            $lead->converted = 1;
            $lead->save($link);
            $note = new Note();
            $note->subject = "Lead Converted.";
            $note->is_audit_note = true;
            $note->parent_table = 'crm_leads';
            $note->parent_id = $lead->id;
            $note->save($link);

            // also copy the lead activities to the opportunities
            $enquiry_activities = DAO::getResultset($link, "SELECT * FROM crm_activities WHERE entity_id = '{$lead->id}' AND entity_type = 'lead'", DAO::FETCH_ASSOC);
            foreach ($enquiry_activities as $_activity) {
                $_activity = (object)$_activity;
                $opportunity_activity = new CRMActivity();
                $opportunity_activity->populate($_activity);
                $opportunity_activity->id = null;
                $opportunity_activity->entity_id = $opportunity->id;
                $opportunity_activity->entity_type = 'opportunity';
                $opportunity_activity->parent_entity_id = $_activity->id;
                $opportunity_activity->parent_entity_type = $_activity->entity_type;
                $opportunity_activity->save($link);
            }

            // copy the lead comments to the opportunity
            $lead_crm_comments = DAO::getResultset($link, "SELECT * FROM crm_entities_comments WHERE entity_id = '{$lead->id}' AND entity_type = 'lead'", DAO::FETCH_ASSOC);
            foreach ($lead_crm_comments as $_comment) {
                $_comment = (object)$_comment;
                $_comment->id = null;
                $_comment->entity_type = 'opportunity';
                $_comment->entity_id = $opportunity->id;
                DAO::saveObjectToTable($link, 'crm_entities_comments', $_comment);
            }

            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect('do.php?_action=edit_opportunity&id=' . $opportunity->id . '&org_id=' . $opportunity->company_id . '&org_type=' . $opportunity->company_type);
    }

    private function convert_opportunity(PDO $link)
    {
        $opportunity_id = isset($_REQUEST['opportunity_id']) ? $_REQUEST['opportunity_id'] : '';
        if ($opportunity_id == '')
            throw new Exception('Missing querystring argument:opportunity_id');

        $opportunity = Opportunity::loadFromDatabase($link, $opportunity_id);

        DAO::transaction_start($link);
        try {

            if($opportunity->company_type == 'pool')
            {
                $company = EmployerPool::loadFromDatabase($link, $opportunity->company_id);
                $company_location = DAO::getObject($link, "SELECT * FROM pool_locations WHERE pool_id = '{$opportunity->company_location_id}'");
    
                $employer = new Organisation();
                $employer->organisation_type = Organisation::TYPE_EMPLOYER;
                $employer->legal_name = $company->legal_name;
                $employer->trading_name = $company->trading_name;
                $employer->short_name = substr($company->legal_name, 0, 11);
                $employer->sgb = $opportunity->company_rating;
                $employer->active = 1;
                $employer->save($link);
    
                $location = new Location();
                $location->organisations_id = $employer->id;
                $location->is_legal_address = 1;
                $location->short_name = 'Main Site';
                $location->address_line_1 = $company_location->address_line_1;
                $location->address_line_2 = $company_location->address_line_2;
                $location->address_line_3 = $company_location->address_line_3;
                $location->address_line_4 = $company_location->address_line_4;
                $location->postcode = $company_location->postcode;
                $main_contact = DAO::getObject($link, "SELECT * FROM pool_contact WHERE contact_id = '{$opportunity->main_contact_id}'");
                if(isset($main_contact->id))
                {
                    $location->contact_name = $main_contact->contact_name;
                    $location->contact_email = $main_contact->contact_email;
                    $location->contact_telephone = $main_contact->contact_telephone;
                    $location->contact_mobile = $main_contact->contact_mobile;
                    $location->save($link);       
                }

		$company->employer_id = $employer->id;
                $company->save($link);
            }
    
    
            $opportunity->converted = 1;
            $opportunity->employer_id = isset($employer) ? $employer->id : $opportunity->company_id;
            $opportunity->save($link);

            $note = new Note();
            $note->subject = "Opportunity converted.";
            $note->is_audit_note = true;
            $note->parent_table = 'opportunities';
            $note->parent_id = $opportunity->id;
            $note->save($link);

            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        //http_redirect('do.php?_action=rec_edit_vacancy&employer_id='.$opportunity->employer_id.'&id=');
        http_redirect('do.php?_action=read_employer&id=' . $opportunity->employer_id);
    }

    public function save_entity_comment(PDO $link)
    {
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';

        if ($entity_id == '' || $entity_type == '')
            throw new Exception('Missing querystring argument: entity_id, entity_type');

        $comment = new stdClass();
        $comment->entity_id = $entity_id;
        $comment->entity_type = $entity_type;
        $comment->subject = isset($_REQUEST['frmCommentModalSubject']) ? $_REQUEST['frmCommentModalSubject'] : null;
        $comment->comments = isset($_REQUEST['frmCommentModalComments']) ? $_REQUEST['frmCommentModalComments'] : null;
        $comment->created_by = $_SESSION['user']->id;

        DAO::saveObjectToTable($link, 'crm_entities_comments', $comment);

        http_redirect($_SESSION['bc']->getCurrent());
    }

    public function get_company_details(PDO $link)
    {
        $org_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($org_id == '')
            return;

        $sql = <<<SQL
SELECT
  organisations.legal_name AS company,
  locations.`address_line_1` AS p_addr,
  locations.`address_line_2` AS p_addr_city,
  locations.`address_line_4` AS p_addr_region,
  locations.`postcode` AS p_addr_postcode,
  locations.`telephone` AS p_addr_phone,
  organisations.sgb
FROM
  organisations
  INNER JOIN locations
    ON (
      organisations.id = locations.`organisations_id`
      AND locations.`is_legal_address` = 1
    )
WHERE organisations.id = '$org_id'
 ;

SQL;


        return json_encode(DAO::getObject($link, $sql));
    }

    private function loadEmails(PDO $link)
    {
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
        $activity_type = isset($_REQUEST['activity_type']) ? $_REQUEST['activity_type'] : '';
        if ($entity_id == '' || $entity_type == '' || $activity_type == '')
            throw new Exception('Missing querystring arguments.');

        $sql = <<<SQL
SELECT
	crm_activities.*,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_activities.created_by) AS name_created_by
FROM
	crm_activities
WHERE
	crm_activities.entity_id = '$entity_id' AND crm_activities.entity_type = '$entity_type' AND crm_activities.activity_type = '$activity_type'
ORDER BY
	crm_activities.created_at DESC
SQL;
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        if (count($result) == 0) {
            echo '<span style="margin-left: 50%;" class="strong"><i class="fa fa-info-circle"></i> No activity (' . $activity_type . ') found.</span>';
            return;
        }

        foreach ($result as $row) {
            $subject = $row['subject'];
            $created_by = $row['name_created_by'];
            $created = Date::to($row['created_at'], DAte::DATETIME);
            $detail = '';
            $detail_section = '';
            if ($activity_type == 'email') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($activity_type == 'email') {
                    $detail_section .= '<span class="text-bold">To: </span>' . $detail->To->__toString() . ' | ';
                    $detail_section .= '<span class="text-bold">Subject: </span>' . $detail->Subject->__toString() . '<hr>';
                    $detail_section .= '<small>' . $detail->Message->__toString() . '</small>';
                }
            }

            $duration_btn = "<span onclick=\"prepareTimeSpentModal('{$row['id']}');\"  class=\"btn btn-xs btn-primary\">{$row['hours']}hour(s) {$row['minutes']}minutes</span></td>";
            if (is_null($row['hours']) && is_null($row['minutes']))
                $duration_btn = "<span onclick=\"prepareTimeSpentModal('{$row['id']}');\"  class=\"btn btn-xs btn-primary\">Set time</span>";


            echo <<<HTML
<div class="well well-sm">
	<div class="box box-info">
		<div class="box-header">
			<i class="fa fa-sticky-note-o"></i>
			<span class="box-title">$subject</span>
		</div>
		<div class="box-body">
			<span class="text-bold">Sent By: </span>$created_by
			<br>
			$detail_section
			<span class="pull-right"><i class="fa fa-clock-o"></i> $created</span>
		</div>
		<div class="box-footer">
			$duration_btn
		</div>
	</div>
</div>
HTML;
        }
    }

    private function loadActivities(PDO $link)
    {
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
        $activity_type = isset($_REQUEST['activity_type']) ? $_REQUEST['activity_type'] : '';
        if ($entity_id == '' || $entity_type == '' || $activity_type == '')
            throw new Exception('Missing querystring arguments.');

        if($activity_type == 'combined')    
        {
            return $this->getCrmActivitiesCombinedView($link);
        }

        $sql = <<<SQL
SELECT
	crm_activities.*,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_activities.created_by) AS name_created_by
FROM
	crm_activities
WHERE
	crm_activities.entity_id = '$entity_id' AND crm_activities.entity_type = '$entity_type' AND crm_activities.activity_type = '$activity_type'
ORDER BY
	crm_activities.created_at DESC
SQL;
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        if (count($result) == 0) {
            echo '<span style="margin-left: 50%;" class="strong"><i class="fa fa-info-circle"></i> No activity (' . $activity_type . ') found.</span>';
            return;
        }

        if($entity_type == 'enquiry')
        {
            $company_type = DAO::getSingleValue($link, "SELECT company_type FROM crm_enquiries WHERE id = '{$entity_id}'");
        }
        if($entity_type == 'lead')
        {
            $company_type = DAO::getSingleValue($link, "SELECT company_type FROM crm_leads WHERE id = '{$entity_id}'");
        }
        if($entity_type == 'opportunity')
        {
            $company_type = DAO::getSingleValue($link, "SELECT company_type FROM crm_opportunities WHERE id = '{$entity_id}'");
        }
        $contacts_table = $company_type == 'pool' ? 'pool_contact' : 'organisation_contact';
        $contacts_sql = "SELECT CONCAT( COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' ') FROM {$contacts_table} WHERE contact_id = '__CONTACT_ID__'";

        foreach ($result as $row) {
            $activity_id = $row['id'];
            $subject = $row['subject'];
            $date = Date::toShort($row['date']);
            $due_date = Date::toShort($row['due_date']);
            $next_action_date = Date::toShort($row['next_action_date']);
            $created_by = $row['name_created_by'];
            $created = Date::to($row['created_at'], DAte::DATETIME);
            $detail = '';
            $detail_section = '';
            if ($activity_type == 'task') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($activity_type == 'task') {
                    $detail_section .= $detail->Status->__toString() != '' ? '<span class="text-bold">Status: </span>' . Lead::getListLeadTaskStatus($detail->Status->__toString()) . ' | ' : '';
                    $detail_section .= $detail->Priority->__toString() != '' ? '<span class="text-bold">Priority: </span>' . Lead::getListLeadTaskPriority($detail->Priority->__toString()) . ' | ' : '';
                    $detail_section .= $detail->PersonContacted->__toString() != '' ? '<span class="text-bold">Person Contacted: </span>' . DAO::getSingleValue($link, str_replace("__CONTACT_ID__", $detail->PersonContacted->__toString(), $contacts_sql)) . '<br>' : '<br>';
                    //$detail_section .= '<span class="text-bold">Job Title: </span>' . $detail->JobTitle->__toString() . '<br>';
                    $detail_section .= nl2br($detail->Comments->__toString());
                }
            }
            if ($activity_type == 'phone') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($activity_type == 'phone') {
                    $detail_section .= ($detail->Status1->__toString() != '' && $detail->Status2->__toString() != '') ? '<span class="text-bold">Status: </span>' . Lead::getListLeadCallStatus1($detail->Status1->__toString()) . ' - ' . Lead::getListLeadCallStatus2($detail->Status2->__toString()) . ' | ' : '';
                    $detail_section .= $detail->PersonContacted->__toString() != '' ? '<span class="text-bold">Person Contacted: </span>' . DAO::getSingleValue($link, str_replace("__CONTACT_ID__", $detail->PersonContacted->__toString(), $contacts_sql)) . '<br>' : '<br>';
                    //$detail_section .= '<span class="text-bold">Job Title: </span>' . $detail->JobTitle->__toString() . '<br>';
                    $detail_section .= nl2br($detail->Comments->__toString());
                }
            }
            if ($activity_type == 'meeting') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($activity_type == 'meeting') {
                    $types = ['1' => 'Meeting', '2' => 'Training', '3' => 'Other', '' => ''];
                    $detail_section .= '<span class="text-bold">Type: </span>' . $types[$detail->Type->__toString()] . ' | ';
                    $detail_section .= $detail->Status->__toString() != '' ? '<span class="text-bold">Status: </span>' . Lead::getListLeadMeetingStatus($detail->Status->__toString()) . ' | ' : '';
                    $detail_section .= $detail->Location->__toString() != '' ? '<span class="text-bold">Location: </span>' . $detail->Location->__toString() . ' | ' : '';
                    $detail_section .= $detail->Time->__toString() != '' ? '<span class="text-bold">Time: </span>' . $detail->Time->__toString() . ' | ' : '';
                    $detail_section .= $detail->Duration->__toString() != '' ? '<span class="text-bold">Duration: </span>' . $detail->Duration->__toString() . ' | ' : '';
                    $detail_section .= $detail->PersonContacted->__toString() != '' ? '<span class="text-bold">Person Contacted: </span>' . DAO::getSingleValue($link, str_replace("__CONTACT_ID__", $detail->PersonContacted->__toString(), $contacts_sql)) . '<br>' : '<br>';
                    //$detail_section .= '<span class="text-bold">Job Title: </span>' . $detail->JobTitle->__toString() . '<br>';
                    $detail_section .= nl2br($detail->Comments->__toString());
                }
            }

            $duration_btn = "&nbsp; <span onclick=\"prepareTimeSpentModal('{$row['id']}');\"  class=\"btn btn-xs btn-primary\">{$row['hours']}hour(s) {$row['minutes']}minutes</span></td>";
            if (is_null($row['hours']) && is_null($row['minutes']))
                $duration_btn = "&nbsp; <span onclick=\"prepareTimeSpentModal('{$row['id']}');\"  class=\"btn btn-xs btn-primary\">Set time</span>";

            $mark_complete_btn = $row['complete'] == 0 ? 
                '&nbsp; <span class="btn btn-xs btn-default" onclick="toggleActivityCompletion(\''.$row['id'].'\', \''.$row['activity_type'].'\', \'0\');"><i class="fa fa-check-circle"></i> Mark as Complete</span>' : 
                '&nbsp; <span class="btn btn-xs btn-success" onclick="toggleActivityCompletion(\''.$row['id'].'\', \''.$row['activity_type'].'\', \'0\');"><i class="fa fa-hourglass"></i> Mark as Open</span>';         

            echo <<<HTML
<div class="well well-sm">
	<div class="box box-info">
		<div class="box-header with-border">
			<i class="fa fa-sticky-note-o"></i>
			<span class="box-title">$subject</span>
            <div class="pull-right">
                <span class="btn btn-xs btn-primary" onclick="editActivity('$activity_id', '$activity_type');"><i class="fa fa-edit"></i> Edit</span>
                $duration_btn
                $mark_complete_btn
            </div>
		</div>
		<div class="box-body">
			<span class="text-bold">Date: </span>$date |
			<span class="text-bold">Due Date: </span>$due_date |
			<span class="text-bold">Next Action Date: </span>$next_action_date |
			<span class="text-bold">Created By: </span>$created_by
			<br>
			<span class="text-bold">Detail: </span><br>
			$detail_section
			<span class="pull-right"><i class="fa fa-clock-o"></i> $created</span>
		</div>
	</div>
</div>
HTML;
        }
    }

    function get_activity_detail(PDO $link)
    {
        $activity = CRMActivity::loadFromDatabase($link, $_REQUEST['activity_id']);
        $entity_id = $activity->activity_type . '_entity_id';
        $entity_type = $activity->activity_type . '_entity_type';
        $subject = $activity->activity_type . '_subject';
        $date = $activity->activity_type . '_date';
        $due_date = $activity->activity_type . '_due_date';
        $next_action_date = $activity->activity_type . '_next_action_date';

        $obj = new stdClass();
        $obj->id = $activity->id;
        $obj->$entity_id = $activity->entity_id;
        $obj->$entity_type = $activity->entity_type;
        $obj->activity_type = $activity->activity_type;
        $obj->$subject = $activity->subject;
        $obj->$date = Date::toShort($activity->date);
        $obj->$due_date = Date::toShort($activity->due_date);
        $obj->$next_action_date = Date::toShort($activity->next_action_date);

        if ($activity->activity_type == 'task') {
            $detail = XML::loadSimpleXML($activity->detail);
            $obj->task_status = isset($detail->Status) ? $detail->Status->__toString() : '';
            $obj->task_priority = isset($detail->Priority) ? $detail->Priority->__toString() : '';
            $obj->task_name_of_person = isset($detail->PersonContacted) ? $detail->PersonContacted->__toString() : '';
            $obj->task_job_title = isset($detail->JobTitle) ? $detail->JobTitle->__toString() : '';
            $obj->task_comments = isset($detail->Comments) ? $detail->Comments->__toString() : '';
        }

        if ($activity->activity_type == 'phone') {
            $detail = XML::loadSimpleXML($activity->detail);
            $obj->phone_call_status1 = isset($detail->Status1) ? $detail->Status1->__toString() : '';
            $obj->phone_call_status2 = isset($detail->Status2) ? $detail->Status2->__toString() : '';
            $obj->phone_name_of_person = isset($detail->PersonContacted) ? $detail->PersonContacted->__toString() : '';
            $obj->phone_job_title = isset($detail->JobTitle) ? $detail->JobTitle->__toString() : '';
            $obj->phone_comments = isset($detail->Comments) ? $detail->Comments->__toString() : '';
        }

        if ($activity->activity_type == 'meeting') {
            $detail = XML::loadSimpleXML($activity->detail);
            $obj->meeting_type = isset($detail->Type) ? $detail->Type->__toString() : '';
            $obj->meeting_status = isset($detail->Status) ? $detail->Status->__toString() : '';
            $obj->meeting_location = isset($detail->Location) ? $detail->Location->__toString() : '';
            $obj->meeting_name_of_person = isset($detail->PersonContacted) ? $detail->PersonContacted->__toString() : '';
            $obj->meeting_job_title = isset($detail->JobTitle) ? $detail->JobTitle->__toString() : '';
            $obj->meeting_time = isset($detail->Time) ? $detail->Time->__toString() : '';
            $obj->meeting_duration = isset($detail->Duration) ? $detail->Duration->__toString() : '';
            $obj->meeting_comments = isset($detail->Comments) ? $detail->Comments->__toString() : '';
        }

        echo json_encode($obj);
    }

    function save_crm_activity(PDO $link)
    {
        $activity_type = isset($_REQUEST['activity_type']) ? $_REQUEST['activity_type'] : '';
        if ($activity_type == '')
            throw new Exception('Missing querystring argument: activity_type');

        $entity_type = $_REQUEST[$activity_type . '_entity_type']; // e.g. enquiry, lead, opportunity etc.
        $entity_id = $_REQUEST[$activity_type . '_entity_id']; // id of enquiry, lead, opportunity etc.

        $activity_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        $activity = $activity_id == '' ? new CRMActivity() : CRMActivity::loadFromDatabase($link, $activity_id);
        $activity->entity_type = $entity_type;
        $activity->entity_id = $entity_id;
        $activity->activity_type = $activity_type;
        $activity->subject = isset($_REQUEST[$activity_type . '_subject']) ? $_REQUEST[$activity_type . '_subject'] : '';
        $activity->date = isset($_REQUEST[$activity_type . '_date']) ? $_REQUEST[$activity_type . '_date'] : '';
        $activity->due_date = isset($_REQUEST[$activity_type . '_due_date']) ? $_REQUEST[$activity_type . '_due_date'] : '';
        $activity->next_action_date = isset($_REQUEST[$activity_type . '_next_action_date']) ? $_REQUEST[$activity_type . '_next_action_date'] : '';

        $detail = $activity_id == '' ? '<Details></Details>' : $activity->detail;
        $detail = XML::loadSimpleXML($detail);

        switch ($activity_type) {
            case 'task':
                if ($activity_id == '') {
                    $activity->detail = XML::loadSimpleXML('<Details></Details>');
                }
                $detail->Status = isset($_REQUEST['task_status']) ? $_REQUEST['task_status'] : '';
                $detail->Priority = isset($_REQUEST['task_priority']) ? $_REQUEST['task_priority'] : '';
                $detail->PersonContacted = isset($_REQUEST['task_name_of_person']) ? Text::utf8_to_latin1($_REQUEST['task_name_of_person']) : '';
                $detail->JobTitle = isset($_REQUEST['task_job_title']) ? Text::utf8_to_latin1($_REQUEST['task_job_title']) : '';
                $detail->Comments = isset($_REQUEST['task_comments']) ? Text::utf8_to_latin1($_REQUEST['task_comments']) : '';
                break;
            case 'phone':
                if ($activity_id == '') {
                    $activity->detail = XML::loadSimpleXML('<Details></Details>');
                }
                $detail->Status1 = isset($_REQUEST['phone_call_status1']) ? $_REQUEST['phone_call_status1'] : '';
                $detail->Status2 = isset($_REQUEST['phone_call_status2']) ? $_REQUEST['phone_call_status2'] : '';
                $detail->PersonContacted = isset($_REQUEST['phone_name_of_person']) ? Text::utf8_to_latin1($_REQUEST['phone_name_of_person']) : '';
                $detail->JobTitle = isset($_REQUEST['phone_job_title']) ? Text::utf8_to_latin1($_REQUEST['phone_job_title']) : '';
                $detail->Comments = isset($_REQUEST['phone_comments']) ? Text::utf8_to_latin1($_REQUEST['phone_comments']) : '';
                break;
            case 'meeting':
                if ($activity_id == '') {
                    $activity->detail = XML::loadSimpleXML('<Details></Details>');
                }
                $detail->Type = isset($_REQUEST['meeting_type']) ? $_REQUEST['meeting_type'] : '';
                $detail->Status = isset($_REQUEST['meeting_status']) ? $_REQUEST['meeting_status'] : '';
                $detail->Location = isset($_REQUEST['meeting_location']) ? $_REQUEST['meeting_location'] : '';
                $detail->Time = isset($_REQUEST['meeting_time']) ? $_REQUEST['meeting_time'] : '';
                $detail->Duration = isset($_REQUEST['meeting_duration']) ? $_REQUEST['meeting_duration'] : '';
                $detail->PersonContacted = isset($_REQUEST['meeting_name_of_person']) ? Text::utf8_to_latin1($_REQUEST['meeting_name_of_person']) : '';
                $detail->JobTitle = isset($_REQUEST['meeting_job_title']) ? Text::utf8_to_latin1($_REQUEST['meeting_job_title']) : '';
                $detail->Comments = isset($_REQUEST['meeting_comments']) ? Text::utf8_to_latin1($_REQUEST['meeting_comments']) : '';
                break;
            case 'email':
                if ($activity_id == '') {
                    $activity->detail = XML::loadSimpleXML('<Details></Details>');
                }
                $detail->To = isset($_REQUEST['email_to']) ? $_REQUEST['email_to'] : '';
                $detail->Subject = isset($_REQUEST['email_subject']) ? Text::utf8_to_latin1($_REQUEST['email_subject']) : '';
                $detail->Message = isset($_REQUEST['email_message']) ? Text::utf8_to_latin1($_REQUEST['email_message']) : '';
                if (!SOURCE_LOCAL)
                    Emailer::html_mail($_REQUEST['email_to'], '', $_REQUEST['email_subject'], '', $_REQUEST['email_message']);
                break;
        }

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadXML($detail->saveXML());
        $dom->formatOutput = TRUE;
        $modified_xml = $dom->saveXml();
        $modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

        $activity->detail = $modified_xml;

        $existing_record = CRMActivity::loadFromDatabase($link, $activity_id);

        if ($activity_id == '') {
            $note = new Note();
            $note->subject = "Activity Created";
        } else {
            $log_string = $existing_record->buildAuditLogString($link, $activity);
            if ($log_string != '') {
                $note = new Note();
                $note->subject = "Activity Updated";
                $note->note = $log_string . PHP_EOL;
                $note->note .= 'Activity type: ' . $activity->activity_type . ', Subject: ' . $activity->subject . ', Creation Date: ' . $activity->created_at;
            }
        }

        $activity->save($link);
        if (isset($note) && !is_null($note)) {
            $note->is_audit_note = true;
            $note->parent_table = 'crm_activities';
            $note->parent_id = $activity->id;
            if (is_null($note->note))
                $note->note = 'Activity type: ' . $activity->activity_type . ', Subject: ' . $activity->subject . ', Creation Date: ' . $activity->created_at;
            $note->save($link);
        }



        http_redirect($_SESSION['bc']->getCurrent());
    }

    public function delete_enquiry(PDO $link)
    {
        $enquiry_id = isset($_POST['enquiry_id_to_delete']) ? $_POST['enquiry_id_to_delete'] : '';
        if ($enquiry_id == '')
            throw new Exception('Missing querystring argument: enquiry_id');

        $enquiry = Enquiry::loadFromDatabase($link, $enquiry_id);
        if (!$enquiry->isSafeToDelete($link))
            throw new Exception('This record is not safe to delete.');

        $enquiry->delete($link);

        return 'The record has been deleted successfully.';
    }

    public function delete_lead(PDO $link)
    {
        $lead_id = isset($_POST['lead_id_to_delete']) ? $_POST['lead_id_to_delete'] : '';
        if ($lead_id == '')
            throw new Exception('Missing querystring argument: lead_id');

        $lead = Lead::loadFromDatabase($link, $lead_id);
        if (!$lead->isSafeToDelete($link))
            throw new Exception('This record is not safe to delete.');

        $lead->delete($link);

        return 'The record has been deleted successfully.';
    }

    public function delete_opportunity(PDO $link)
    {
        $opportunity_id = isset($_POST['opportunity_id_to_delete']) ? $_POST['opportunity_id_to_delete'] : '';
        if ($opportunity_id == '')
            throw new Exception('Missing querystring argument: opportunity_id');

        $opportunity = Opportunity::loadFromDatabase($link, $opportunity_id);
        if (!$opportunity->isSafeToDelete($link))
            throw new Exception('This record is not safe to delete.');

        $opportunity->delete($link);

        return 'The record has been deleted successfully.';
    }

    public function delete_crm_entity_comment(PDO $link)
    {
        $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : '';
        if ($comment_id == '')
            throw new Exception('Missing querystring argument: comment_id');

        $comment = EntityComment::loadFromDatabase($link, $comment_id);
        if (!$comment->isSafeToDelete($link))
            throw new Exception('This record is not safe to delete.');

        $comment->delete($link);

        return 'The record has been deleted successfully.';
    }

    public function delete_crm_entity_file(PDO $link)
    {
        $entity_id = isset($_POST['entity_id']) ? $_POST['entity_id'] : '';
        $entity_type = isset($_POST['entity_type']) ? strtolower($_POST['entity_type']) : '';
        $file_path = isset($_POST['file_path']) ? $_POST['file_path'] : '';
        if ($entity_id == '')
            throw new Exception('Missing querystring argument: entity_id');

        unlink($file_path);

        DAO::execute($link, "DELETE FROM crm_entities_files WHERE entity_id = '{$entity_id}' AND entity_type = '{$entity_type}' AND file_path = '{$file_path}'");

        $note = new Note();
        $note->subject = "File Deleted";
        $note->note = 'File Name: ' . basename($file_path);
        $note->is_audit_note = true;
        if ($entity_type == 'enquiry')
            $note->parent_table = 'crm_enquiries';
        elseif ($entity_type == 'lead')
            $note->parent_table = 'crm_leads';
        elseif ($entity_type == 'opportunity')
            $note->parent_table = 'crm_opportunities';
        $note->parent_id = $entity_id;
        $note->save($link);

        return 'The file has been deleted successfully.';
    }

    public function prepareTimeSpentModal()
    {
        $tab_combined = isset($_REQUEST['tab_combined']) ? $_REQUEST['tab_combined'] : 0;
        return CRMActivity::renderTimeSpentModalHTML($_REQUEST['activity_id'], $tab_combined);
    }

    public function save_activity_time_spent(PDO $link)
    {
        $activity_id = isset($_POST['id']) ? $_POST['id'] : '';
        $tab_combined = isset($_POST['tab_combined']) ? $_POST['tab_combined'] : 0;

        $activity = CRMActivity::loadFromDatabase($link, $activity_id);
        $activity->hours = $_REQUEST['hours'];
        $activity->minutes = $_REQUEST['minutes'];
        $activity->save($link);
        $note = new Note();
        $note->subject = "Activity Updated";
        $note->note = 'Time spent updated.' . PHP_EOL;
        $note->note .= 'Activity type: ' . $activity->activity_type . ', Subject: ' . $activity->subject . ', Creation Date: ' . $activity->created_at;
        $note->is_audit_note = true;
        if ($activity->entity_type == 'enquiry')
            $note->parent_table = 'crm_enquiries';
        elseif ($activity->entity_type == 'lead')
            $note->parent_table = 'crm_leads';
        elseif ($activity->entity_type == 'opportunity')
            $note->parent_table = 'crm_opportunities';
        $note->parent_id = $activity->entity_id;
        $note->save($link);
        $_at = !$tab_combined ? $activity->activity_type : 'combined';
        //throw new Exception(json_encode($activity->activity_type));
        echo $_at;
    }

    public function load_pool_locations(PDO $link)
    {
        $pool_id = isset($_REQUEST['pool_id']) ? $_REQUEST['pool_id'] : '';
        if ($pool_id == '')
            return;
        if (DB_NAME == "am_ligauk" && in_array($_SESSION['user']->id, [4394, 4393])) {
            if ($_SESSION['user']->id == 4393)
                $sql = <<<SQL
SELECT
	id, CONCAT(COALESCE(`full_name`,''), COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,'')), null
FROM
	pool_locations
WHERE
	pool_id = '{$pool_id}'
	AND pool_locations.address_line_4 IN ('Hampshire', 'Surrey', 'Dorset')
;
SQL;
            else
                $sql = <<<SQL
SELECT
	id, CONCAT(COALESCE(`full_name`,''), COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,'')), null
FROM
	pool_locations
WHERE
	pool_id = '{$pool_id}'
	AND pool_locations.address_line_4 IN ('Berkshire', 'Oxfordshire', 'Greater London' )
;
SQL;
        } else {
            $sql = "SELECT id, CONCAT(COALESCE(`full_name`,''), COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,'')), null FROM pool_locations WHERE pool_id = '{$pool_id}'";
        }

        $st = $link->query($sql);
        if ($st) {
            echo "<option value=\"\"></option>";
            if ($st->rowCount() == 0) {
                echo '<option value="">No pool locations found</option>';
            } else {
                while ($row = $st->fetch()) {
                    echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>";
                }
            }
        } else {
            throw new DatabaseException($link, $sql);
        }
    }

    public function load_employer_locations(PDO $link)
    {
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
        if ($employer_id == '')
            throw new Exception('Missing querystring argument: employer_id');

        $sql = "SELECT id, CONCAT(COALESCE(`full_name`,''), COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,'')), null FROM locations WHERE organisations_id = '{$employer_id}'";
        $st = $link->query($sql);
        if ($st) {
            echo "<option value=\"\"></option>";
            if ($st->rowCount() == 0) {
                echo '<option value="">No employer locations found</option>';
            } else {
                while ($row = $st->fetch()) {
                    echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>";
                }
            }
        } else {
            throw new DatabaseException($link, $sql);
        }
    }

    public function populate_other_info(PDO $link)
    {
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        if ($entity_id == '')
            throw new Exception('Missing querystring argument: entity_id');

        if ($entity_type == 'pool')
            $sql = "SELECT pool.*, pool_locations.telephone FROM pool INNER JOIN pool_locations ON pool.id = pool_locations.`pool_id` WHERE pool.id = '{$entity_id}'";
        if ($entity_type == 'employer')
            $sql = "SELECT organisations.*, locations.telephone FROM organisations INNER JOIN locations ON organisations.id = locations.`organisations_id` WHERE organisations.id = '{$entity_id}'";

        $pool = DAO::getObject($link, $sql);

        echo json_encode($pool);
    }

    public function save_crm_product(PDO $link)
    {
        $product_name = isset($_REQUEST['product_name']) ? $_REQUEST['product_name'] : '';
        if ($product_name == '')
            return;

        $obj = (object)[
            'id' => null,
            'description' => substr($product_name, 0, 254),
        ];

        DAO::saveObjectToTable($link, "lookup_crm_products", $obj);
    }
    
    function load_products(PDO $link)
    {
        header('Content-Type: text/xml');
        $sql = "SELECT id, description, null FROM lookup_crm_products ORDER BY description";
        $st = $link->query($sql);
        if ($st) {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";

            echo "<option value=\"\"></option>\r\n";
            while ($row = $st->fetch()) {
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }
            echo '</select>';
        } else {
            throw new DatabaseException($link, $sql);
        }
    }

    function load_contacts(PDO $link)
    {
        $org_id = isset($_REQUEST['org_id']) ? $_REQUEST['org_id'] : '';
        $org_type = isset($_REQUEST['org_type']) ? $_REQUEST['org_type'] : '';
        if($org_id == '' || $org_type == '')
            return;

        header('Content-Type: text/xml');
        if($org_type == 'pool')
        {
            $sql = "SELECT `contact_id`, CONCAT( COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' '),NULL FROM pool_contact WHERE pool_id = '{$org_id}' ORDER BY contact_name;";
        }
        if($org_type == 'employer')
        {
            $sql = "SELECT `contact_id`, CONCAT( COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' '),NULL FROM organisation_contact WHERE org_id = '{$org_id}' ORDER BY contact_name;";
        }
        $st = $link->query($sql);
        if ($st) {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";

            echo "<option value=\"\"></option>\r\n";
            while ($row = $st->fetch()) {
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }
            echo '</select>';
        } else {
            throw new DatabaseException($link, $sql);
        }
    }

    public function delete_crm_contact(PDO $link)
    {
        $contact_id = isset($_GET['id']) ? $_GET['id'] : '';
        $org_id = isset($_GET['org_id']) ? $_GET['org_id'] : '';
        $org_type = isset($_GET['org_type']) ? $_GET['org_type'] : '';
        if($org_type == 'pool')
            DAO::execute($link, "DELETE FROM pool_contact WHERE contact_id = '{$contact_id}'");
        if($org_type == 'employer')
            DAO::execute($link, "DELETE FROM organisation_contact WHERE contact_id = '{$contact_id}'");
        http_redirect($_SESSION['bc']->getPrevious());
    }

    public function add_training_schedule(PDO $link)
    {
        $entry = new stdClass();
        $entry->level = isset($_REQUEST['level']) ? $_REQUEST['level'] : '';
        $entry->training_date = isset($_REQUEST['training_date']) ? $_REQUEST['training_date'] : '';
        $entry->duration = isset($_REQUEST['duration']) ? $_REQUEST['duration'] : '';
        $entry->training_end_date = isset($_REQUEST['training_end_date']) ? $_REQUEST['training_end_date'] : '';
        $entry->capacity = isset($_REQUEST['capacity']) ? $_REQUEST['capacity'] : '';
        $entry->trainer = isset($_REQUEST['trainer']) ? $_REQUEST['trainer'] : '';
        $entry->venue = isset($_REQUEST['venue']) ? $_REQUEST['venue'] : '';
	$entry->start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
        $entry->end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : '';

        if (!empty($entry->training_date) && !empty($entry->level) && !empty($entry->capacity) && !empty($entry->venue)) {
            DAO::saveObjectToTable($link, "crm_training_schedule", $entry);
        }
    }

    public function update_training_schedule(PDO $link)
    {
        $entry = new stdClass();
        $entry->id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $entry->level = isset($_REQUEST['level']) ? $_REQUEST['level'] : '';
        $entry->training_date = isset($_REQUEST['training_date']) ? $_REQUEST['training_date'] : '';
        $entry->duration = isset($_REQUEST['duration']) ? $_REQUEST['duration'] : '';
        $entry->training_end_date = isset($_REQUEST['training_end_date']) ? $_REQUEST['training_end_date'] : '';
	$entry->start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
        $entry->end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : '';
        $entry->capacity = isset($_REQUEST['capacity']) ? $_REQUEST['capacity'] : '';
        $entry->trainer = isset($_REQUEST['trainer']) ? $_REQUEST['trainer'] : '';
        $entry->venue = isset($_REQUEST['venue']) ? $_REQUEST['venue'] : '';

        if (!empty($entry->training_date) && !empty($entry->level) && !empty($entry->capacity) && !empty($entry->venue)) {
            DAO::saveObjectToTable($link, "crm_training_schedule", $entry);
        }
    }

    public function delete_training_schedule(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if (!empty($id)) {
            $learner_ids = DAO::getSingleValue($link, "SELECT learner_ids FROM crm_training_schedule WHERE id = '{$id}'");
            if (!is_null($learner_ids)) {
                echo 'Error: There are learners added to this training date.';
                return;
            }

            DAO::execute($link, "DELETE FROM crm_training_schedule WHERE id = '{$id}'");
        }
    }

    public function get_location_details(PDO $link)
    {
        $location_id = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : '';
        if ($location_id != '') {
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
            $sql = '';
            if ($type == 'pool')
                $sql = "SELECT * FROM pool_locations WHERE id = '{$location_id}'";
            elseif ($type == 'employer')
                $sql = "SELECT * FROM locations WHERE id = '{$location_id}'";
            $location = DAO::getObject($link, $sql);
            if (isset($location->id))
                return json_encode($location);
            else
                return null;
        }
    }

    public function get_crm_company_details(PDO $link)
    {
        $company_id = isset($_REQUEST['company_id']) ? $_REQUEST['company_id'] : '';
        if ($company_id != '') {
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
            $sql = '';
            if ($type == 'pool')
                $sql = "SELECT * FROM pool WHERE id = '{$company_id}'";
            elseif ($type == 'employer')
                $sql = "SELECT * FROM organisations WHERE id = '{$company_id}'";
            $company = DAO::getObject($link, $sql);
            if (isset($company->id))
                return json_encode($company);
            else
                return null;
        }
    }

    function fetch_employer_learners(PDO $link)
    {
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';

        $level = DAO::getSingleValue($link, "SELECT level FROM crm_training_schedule WHERE id = '{$schedule_id}'");

        // already booked for the same level dates
        $sql = <<<SQL
SELECT DISTINCT learner_id 
FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
WHERE crm_training_schedule.`level` = '{$level}' AND training.`status` = '1';
SQL;
        $saved_learner_ids = DAO::getSingleColumn($link, $sql);
        //echo $sql; return;
        $records = DAO::getResultset($link, "SELECT * FROM users WHERE type = 5 AND users.employer_id = '{$employer_id}'", DAO::FETCH_ASSOC);
	if($_SESSION['user']->employer_id == 3278)
        {
            $learners_sql->setClause(
                "WHERE 
                    (users.who_created IN (SELECT username FROM users WHERE users.type != 5 AND users.employer_id = 3278)) OR 
                    (users.id IN (SELECT learner_id FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.venue = 'Peterborough Skills Academy')) "
            );
        }
        echo '<table class="table table-bordered">';
        echo '<tr><th>Select</th><th>Employer</th><th>Firstnames</th><th>Surname</th><th>Home Address</th></tr>';
        if (count($records) == 0) {
            echo '<tr><td colspan="5"><i>No records found.</i></td> </tr>';
        } else {
            foreach ($records as $row) {
                if (in_array($row['id'], $saved_learner_ids))
                    continue;
                echo '<tr>';
                echo '<td><input type="checkbox" name="learners[]" value="' . $row['id'] . '" /></td>';
                echo '<td>' . DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$row['employer_id']}'") . '</td>';
                echo '<td>' . $row['firstnames'] . '</td>';
                echo '<td>' . $row['surname'] . '</td>';
                echo '<td class="small">' . $row['home_address_line_1'] . ' ' . $row['home_address_line_2'] . ' ' . $row['home_address_line_3'] . ' ' . $row['home_address_line_4'] . ' ' . $row['home_postcode'] . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }

    public function add_learners_into_date(PDO $link)
    {
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';
        $learners = isset($_REQUEST['learners']) ? explode(",", $_REQUEST['learners']) : '';

        if ($schedule_id != '' && is_array($learners)) {
            foreach ($learners as $learner_id) {
                if ($learner_id == '')
                    continue;

                $training = (object)[
                    'schedule_id' => $schedule_id,
                    'learner_id' => $learner_id,
                    'booked_date' => date('Y-m-d'),
                ];
                DAO::saveObjectToTable($link, 'training', $training);
            }
        }
    }

    public function remove_learner_from_training(PDO $link)
    {
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';
        $learner_id = isset($_REQUEST['learner_id']) ? $_REQUEST['learner_id'] : '';

        if ($schedule_id != '' && $learner_id != '')
            DAO::execute($link, "DELETE FROM training WHERE training.schedule_id = '{$schedule_id}' AND training.learner_id = '{$learner_id}'");
    }

    public function preview_email_template(PDO $link)
    {
        echo DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE id = '{$_REQUEST['template_id']}'");
    }

    public function view_sent_email(PDO $link)
    {
        echo DAO::getSingleValue($link, "SELECT email_body FROM emails WHERE id = '{$_REQUEST['id']}'");
    }

    function update_duplex_training_status(PDO $link)
    {
        $training_id = isset($_REQUEST['training_id']) ? $_REQUEST['training_id'] : '';
        $training_status = isset($_REQUEST['training_status']) ? $_REQUEST['training_status'] : '';
	$vocanto_progress = isset($_REQUEST['vocanto_progress']) ? $_REQUEST['vocanto_progress'] : '';
        if ($training_id == '' || $training_status == '')
            return;

	$vocanto_progress = $vocanto_progress > 100 ? 100 : $vocanto_progress;

        DAO::execute($link, "UPDATE training SET training.status = '{$training_status}', training.vocanto_progress = '{$vocanto_progress}' WHERE training.id = '{$training_id}'");
    }

    public function set_crm_note_as_actioned(PDO $link)
    {
        $note_id = isset($_REQUEST['note_id']) ? $_REQUEST['note_id'] : '';
        if ($note_id == '')
            return;

        $note = DAO::getObject($link, "SELECT * FROM crm_notes_orgs WHERE id = '{$note_id}'");
        if (isset($note->id)) {
            $note->actioned = "Y";
            DAO::saveObjectToTable($link, "crm_notes_orgs", $note);
            echo "1";
            return;
        }
        echo "0";
    }

    public function getCrmActivitiesCombinedView(PDO $link)
    {
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';

        $records = DAO::getResultset($link, "SELECT * FROM crm_activities WHERE entity_type = '{$entity_type}' AND entity_id = '{$entity_id}' ORDER BY id DESC", DAO::FETCH_ASSOC);
        if(count($records) == 0)
        {
            echo '<i class="text-muted">No activities found.</i>';
            return;
        }

        $icon = 'fa fa-comment';
        if($entity_type == 'enquiry')
        {
            $company_type = DAO::getSingleValue($link, "SELECT company_type FROM crm_enquiries WHERE id = '{$entity_id}'");
        }
        if($entity_type == 'lead')
        {
            $company_type = DAO::getSingleValue($link, "SELECT company_type FROM crm_leads WHERE id = '{$entity_id}'");
        }
        if($entity_type == 'opportunity')
        {
            $company_type = DAO::getSingleValue($link, "SELECT company_type FROM crm_opportunities WHERE id = '{$entity_id}'");
        }
        $contacts_table = $company_type == 'pool' ? 'pool_contact' : 'organisation_contact';
        $contacts_sql = "SELECT CONCAT( COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' ') FROM {$contacts_table} WHERE contact_id = '__CONTACT_ID__'";


        echo '<div class="tab-pane" id="timeline"><ul class="timeline">';
        foreach($records AS $row)
        {
            if($row['activity_type'] == 'phone')
                $icon = 'fa fa-phone';
            elseif($row['activity_type'] == 'task')
                $icon = 'fa fa-tasks';
            elseif($row['activity_type'] == 'meeting')
                $icon = 'fa fa-users';

            echo '<li class="time-label"><span class="bg-green">' . Date::toShort($row['created_at']) . '<br><i class="fa fa-clock-o"></i> ' . Date::to($row['created_at'], 'H:i:s').'</span></li>';
            echo '<li><i class="fa ' . $icon . ' bg-aqua"></i>';
            echo '<div class="timeline-item well">';
            if($row['activity_type'] != 'email')
            {
                echo '<span class="time">';
                echo '<span class="btn btn-xs btn-primary" onclick="editActivity(\''.$row['id'].'\', \''.$row['activity_type'].'\');"><i class="fa fa-edit"></i> Edit</span>';
                if (is_null($row['hours']) && is_null($row['minutes']))
                    echo "&nbsp; <span onclick=\"prepareTimeSpentModal('{$row['id']}', 1);\"  class=\"btn btn-xs btn-primary\"><i class=\"fa fa-clock-o\"></i> Set time</span>";
                else
                    echo "&nbsp; <span onclick=\"prepareTimeSpentModal('{$row['id']}', 1);\"  class=\"btn btn-xs btn-primary\"><i class=\"fa fa-clock-o\"></i> {$row['hours']}hour(s) {$row['minutes']}minutes</span></td>";
                
                echo $row['complete'] == 0 ? 
                    '&nbsp; <span class="btn btn-xs btn-default" onclick="toggleActivityCompletion(\''.$row['id'].'\', \''.$row['activity_type'].'\', \'1\');"><i class="fa fa-check-circle"></i> Mark as Complete</span>' : 
                    '&nbsp; <span class="btn btn-xs btn-success" onclick="toggleActivityCompletion(\''.$row['id'].'\', \''.$row['activity_type'].'\', \'1\');"><i class="fa fa-hourglass"></i> Mark as Open</span>';     
                echo '</span>';
            }
            echo '<strong class="timeline-header"><span class="text-info">' . ucfirst($row['activity_type']) . ': </span>' . $row['subject'] . '</strong>';
            $by_whom = '<span class="fa fa-user" title="By whom"></span> '.DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname, ' (', username, ')') FROM users WHERE users.id = '{$row['created_by']}'");
            echo '<div class="timeline-body">';
            $detail = '';
            $detail_section = '';
            if ($row['activity_type'] == 'task') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($row['activity_type'] == 'task') {
                    $detail_section .= $detail->Status->__toString() != '' ? '<span class="text-bold">Status: </span>' . Lead::getListLeadTaskStatus($detail->Status->__toString()) . ' | ' : '';
                    $detail_section .= $detail->Priority->__toString() != '' ? '<span class="text-bold">Priority: </span>' . Lead::getListLeadTaskPriority($detail->Priority->__toString()) . ' | ' : '';
                    $detail_section .= $detail->PersonContacted->__toString() != '' ? '<span class="text-bold">Person Contacted: </span>' . DAO::getSingleValue($link, str_replace("__CONTACT_ID__", $detail->PersonContacted->__toString(), $contacts_sql)) . '<br>' : '<br>';
                    //$detail_section .= '<span class="text-bold">Job Title: </span>' . $detail->JobTitle->__toString() . '<br>';
                    $detail_section .= nl2br($detail->Comments->__toString());
                }
            }
            if ($row['activity_type'] == 'phone') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($row['activity_type'] == 'phone') {
                    $detail_section .= ($detail->Status1->__toString() != '' && $detail->Status2->__toString() != '') ? '<span class="text-bold">Status: </span>' . Lead::getListLeadCallStatus1($detail->Status1->__toString()) . ' - ' . Lead::getListLeadCallStatus2($detail->Status2->__toString()) . ' | ' : '';
                    $detail_section .= $detail->PersonContacted->__toString() != '' ? '<span class="text-bold">Person Contacted: </span>' . DAO::getSingleValue($link, str_replace("__CONTACT_ID__", $detail->PersonContacted->__toString(), $contacts_sql)) . '<br>' : '<br>';
                    //$detail_section .= '<span class="text-bold">Job Title: </span>' . $detail->JobTitle->__toString() . '<br>';
                    $detail_section .= nl2br($detail->Comments->__toString());
                }
            }
            if ($row['activity_type'] == 'meeting') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($row['activity_type'] == 'meeting') {
                    $types = ['1' => 'Meeting', '2' => 'Training', '3' => 'Other', '' => ''];
                    $detail_section .= '<span class="text-bold">Type: </span>' . $types[$detail->Type->__toString()] . ' | ';
                    $detail_section .= $detail->Status->__toString() != '' ? '<span class="text-bold">Status: </span>' . Lead::getListLeadMeetingStatus($detail->Status->__toString()) . ' | ' : '';
                    $detail_section .= $detail->Location->__toString() != '' ? '<span class="text-bold">Location: </span>' . $detail->Location->__toString() . ' | ' : '';
                    $detail_section .= $detail->Time->__toString() != '' ? '<span class="text-bold">Time: </span>' . $detail->Time->__toString() . ' | ' : '';
                    $detail_section .= $detail->Duration->__toString() != '' ? '<span class="text-bold">Duration: </span>' . $detail->Duration->__toString() . ' | ' : '';
                    $detail_section .= $detail->PersonContacted->__toString() != '' ? '<span class="text-bold">Person Contacted: </span>' . DAO::getSingleValue($link, str_replace("__CONTACT_ID__", $detail->PersonContacted->__toString(), $contacts_sql)) . '<br>' : '<br>';
                    //$detail_section .= '<span class="text-bold">Job Title: </span>' . $detail->JobTitle->__toString() . '<br>';
                    $detail_section .= nl2br($detail->Comments->__toString());
                }
            }
            if ($row['activity_type'] == 'email') {
                $detail = XML::loadSimpleXML($row['detail']);
                if ($row['activity_type'] == 'email') {
                    $detail_section .= '<span class="text-bold">To: </span>' . $detail->To->__toString() . ' | ';
                    $detail_section .= '<span class="text-bold">Subject: </span>' . $detail->Subject->__toString() . '<hr>';
                    $detail_section .= '<small>' . strip_tags($detail->Message->__toString()) . '</small>';
                }
            }
            echo nl2br($detail_section);
            echo '<hr><i class="text-bold">' . $by_whom . '</i>';
	    echo '<span class="pull-right">Last modified: ' . Date::to($row['updated_at'], Date::DATETIME) . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul></div>';
    }

    public function toggleActivityCompletion(PDO $link)
    {
        $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
        $activity_type = isset($_REQUEST['activity_type']) ? $_REQUEST['activity_type'] : '';
        $tab_combined = isset($_REQUEST['tab_combined']) ? $_REQUEST['tab_combined'] : '';

        DAO::execute($link, "UPDATE crm_activities SET crm_activities.complete = (crm_activities.complete-1)*-1 WHERE crm_activities.id = '{$activity_id}'");
        echo !$tab_combined ? $activity_type : 'combined';
    }
}
