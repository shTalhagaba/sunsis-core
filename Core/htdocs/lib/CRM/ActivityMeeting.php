<?php
class ActivityMeeting
{
	public function __construct()
	{
		$this->activity_type = 'meeting';
	}

	public static function renderActivityModalHTML($entity_id, $entity_type, PDO $link)
	{
		$meeting_type = HTML::selectChosen('meeting_type', [['1', 'Face-to-Face'], ['2', 'Online'], ['2', 'Other']], '', false, true);
		$meeting_status = HTML::selectChosen('meeting_status', Lead::getDDLLeadMeetingStatus(), '', false, true);
		$meeting_time = HTML::timebox('meeting_time', '', false);

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
			$contacts_ddl_html = HTML::selectChosen('meeting_name_of_person', $contacts_ddl, null, true, false);	

		return <<<HTML
<div class="modal fade" id="meetingModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title text-bold">Meeting Details</h5>
			</div>
			<div class="modal-body">
				<form autocomplete="off" class="form-horizontal" method="post" name="meetingForm" id="meetingForm" method="post" action="do.php">
					<input type="hidden" name="_action" value="ajax_helper" />
					<input type="hidden" name="subaction" value="save_crm_activity" />
					<input type="hidden" name="meeting_entity_id" value="$entity_id" />
					<input type="hidden" name="meeting_entity_type" value="$entity_type" />
					<input type="hidden" name="activity_type" value="meeting" />
					<input type="hidden" name="id" value="" />

					<div class="row">
						<div class="col-sm-6">
							<div class="control-group"><label class="control-label" for ="meeting_type">Meeting Type </label>$meeting_type</div>
						</div>
						<div class="col-sm-6">
							<div class="control-group"><label class="control-label" for ="meeting_location">Location:</label>
							<input type="text" name="meeting_location" id="meeting_location" class="form-control"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="control-group"><label class="control-label" for ="meeting_subject">Subject:</label>
							<input type="text" name="meeting_subject" id="meeting_subject" class="form-control compulsory"></div>
						</div>						
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="control-group">
								<label class="control-label" for="meeting_name_of_person">Person contacted:</label>
								$contacts_ddl_html
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="control-group">
								<label class="control-label" for ="meeting_due_date">Due Date:</label>
								<input type="text" class="form-control datepicker" id="meeting_due_date" name="meeting_due_date" value="" placeholder="dd/mm/yyyy">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="control-group"><label class="control-label" for ="meeting_time">Time:</label>$meeting_time</div>
						</div>
						<div class="col-sm-4">
							<div class="control-group">
								<label class="control-label" for ="meeting_duration">Duration:</label> &nbsp;
								<input type="text" name="meeting_duration" id="meeting_duration" class="form-control">
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for ="meeting_comments">Comments:</label>
						<textarea class="form-control" name="meeting_comments" id="meeting_comments" rows="5" style="width: 100%;"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#meetingModal').modal('hide');">Cancel</button>
				<button type="button" id="btnMeetingModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
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

$("button#btnMeetingModalSave").click(function(){
	if(validateForm(document.forms['meetingForm']) == false)
	{
		return;
	}
	$("#meetingForm").submit();
});

</script>
JS;

	}
}
