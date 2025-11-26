<?php
class ActivityPhone
{
	public function __construct()
	{
		$this->activity_type = 'phone';
	}

	public static function renderActivityModalHTML($entity_id, $entity_type, PDO $link)
	{
		$status1 = HTML::selectChosen('phone_call_status1', Lead::getDDLLeadCallStatus1(), '', false, true);
		$status2 = HTML::selectChosen('phone_call_status2', Lead::getDDLLeadCallStatus2(), '', false, true);
		$priority = HTML::selectChosen('phone_priority', Lead::getDDLLeadTaskPriority(), '', false, true);

		$entity = ucfirst($entity_type);
		$entity = $entity::loadFromDatabase($link, $entity_id);

		if($entity->company_type == 'pool')
		{
			$contacts_sql = <<<SQL
SELECT `contact_id`, 
CONCAT( 
	COALESCE(contact_title, ''), ' ',
	COALESCE(contact_name, ''), ', ',
	COALESCE(job_title, ''), ' '
 ) AS detail,
 NULL
FROM pool_contact
WHERE pool_id = '{$entity->company_id}'
ORDER BY contact_name
;
SQL;
		}
		elseif($entity->company_type == 'employer')
		{
			$contacts_sql = <<<SQL
SELECT `contact_id`, 
CONCAT( 
	COALESCE(contact_title, ''), ' ',
	COALESCE(contact_name, ''), ', ',
	COALESCE(job_title, ''), ' '
 ) AS detail,
 NULL
FROM organisation_contact
WHERE org_id = '{$entity->company_id}'
ORDER BY contact_name
;
SQL;

		}
			$contacts_ddl = DAO::getResultset($link, $contacts_sql);
			$contacts_ddl_html = HTML::selectChosen('phone_name_of_person', $contacts_ddl, null, true, false);	

		return <<<HTML
<div class="modal fade" id="phoneModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title text-bold">Phone Call Details</h5>
			</div>
			<div class="modal-body">
				<form autocomplete="off" class="form-horizontal" method="post" name="phoneForm" id="phoneForm" method="post" action="do.php">
					<input type="hidden" name="_action" value="ajax_helper" />
					<input type="hidden" name="subaction" value="save_crm_activity" />
					<input type="hidden" name="phone_entity_id" value="$entity_id" />
					<input type="hidden" name="phone_entity_type" value="$entity_type" />
					<input type="hidden" name="activity_type" value="phone" />
					<input type="hidden" name="id" value="" />

					<div class="row">
						<div class="col-sm-12">
							<div class="control-group"><label class="control-label" for ="phone_subject">Title:</label>
							<input type="text" name="phone_subject" id="phone_subject" class="form-control compulsory"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="control-group">
								<label class="control-label" for="phone_name_of_person">Person contacted:</label>
								$contacts_ddl_html
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="control-group">
								<label class="control-label" for ="phone_due_date">Due Date:</label>
								<input type="text" class="form-control datepicker" id="phone_due_date" name="phone_due_date" value="" placeholder="dd/mm/yyyy">
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for ="phone_comments">Comments:</label>
						<textarea class="form-control" name="phone_comments" id="phone_comments" rows="5" style="width: 100%;"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#phoneModal').modal('hide');">Cancel</button>
				<button type="button" id="btnPhoneModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
			</div>
		</div>
	</div>
</div>
HTML;

	}

	public static function renderActivityModalJS()
	{
		return <<<JS
<script type="text/javascript">

$("button#btnPhoneModalSave").click(function(){
	if(validateForm(document.forms['phoneForm']) == false)
	{
		return;
	}
	$("#phoneForm").submit();
});

</script>
JS;

	}
}
