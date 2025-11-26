<?php
class ActivityEmail
{
	public function __construct()
	{
		$this->activity_type = 'email';
	}

	public static function renderActivityModalHTML($entity_id, $entity_type)
	{
		return <<<HTML
<div class="modal fade" id="emailModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title text-bold">Email</h5>
			</div>
			<div class="modal-body">
				<form autocomplete="off" class="form-horizontal" method="post" name="emailForm" id="emailForm" method="post" action="do.php">
					<input type="hidden" name="_action" value="ajax_helper" />
					<input type="hidden" name="subaction" value="save_crm_activity" />
					<input type="hidden" name="email_entity_id" value="$entity_id" />
					<input type="hidden" name="email_entity_type" value="$entity_type" />
					<input type="hidden" name="activity_type" value="email" />
					<input type="hidden" name="id" value="" />

					<div class="row">
						<div class="col-sm-12">
							<div class="control-group"><label class="control-label" for ="email_to">To:</label>
							<input type="text" name="email_to" id="email_to" class="form-control compulsory"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="control-group"><label class="control-label" for ="email_subject">Subject:</label>
							<input type="text" name="email_subject" id="email_subject" class="form-control compulsory"></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for ="email_message">Message:</label>
						<textarea name="email_message" id="email_message" class="form-control">
						</textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#emailModal').modal('hide');">Cancel</button>
				<button type="button" id="btnEmailModalSave" class="btn btn-primary btn-md"><i class="fa fa-send"></i> Send</button>
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

$("button#btnEmailModalSave").click(function(){
	if(validateForm(document.forms['emailForm']) == false)
	{
		return;
	}
	$("#emailForm").submit();
});

</script>
JS;

	}
}
