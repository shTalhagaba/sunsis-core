<?php
class CRMActivity extends Entity
{
	public function save(PDO $link)
	{
		$this->created_at = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created_at;
		$this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

		$result = DAO::saveObjectToTable($link, 'crm_activities', $this);

		return $result;
	}

	public static function getCreatedByName(PDO $link, $created_by)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$created_by}'");
	}

	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	crm_activities
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$activity = null;
		if($st)
		{
			$activity = null;
			$row = $st->fetch();
			if($row)
			{
				$activity = new CRMActivity();
				$activity->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find activity record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $activity;
	}

	public static function getListActivityType($individual = '')
	{
		$a = array(
			'1' => 'Task',
			'2' => 'Phone Call',
			'3' => 'Meeting',
			'4' => 'Email'
		);
		asort($a);
		return $individual == '' ? $a : $a[$individual];
	}

	public static function getDDLEnquiryActivityType()
	{
		$a = array(
			array('1', 'Task'),
			array('2', 'Phone Call'),
			array('3', 'Meeting'),
			array('4', 'Email')
		);
		asort($a);
		return $a;
	}

	public static function renderActivityModalHTML($entity_id, $entity_type, $activity_type, PDO $link)
	{
		if($activity_type == 'task')
		{
			return ActivityTask::renderActivityModalHTML($entity_id, $entity_type, $link);
		}
		elseif($activity_type == 'phone')
		{
			return ActivityPhone::renderActivityModalHTML($entity_id, $entity_type, $link);
		}
		elseif($activity_type == 'meeting')
		{
			return ActivityMeeting::renderActivityModalHTML($entity_id, $entity_type, $link);
		}
		elseif($activity_type == 'email')
		{
			return ActivityEmail::renderActivityModalHTML($entity_id, $entity_type, $link);
		}
	}

	public static function renderActivityModalJS($activity_type)
	{
		if($activity_type == 'task')
		{
			return ActivityTask::renderActivityModalJS();
		}
		elseif($activity_type == 'phone')
		{
			return ActivityPhone::renderActivityModalJS();
		}
		elseif($activity_type == 'meeting')
		{
			return ActivityMeeting::renderActivityModalJS();
		}
		elseif($activity_type == 'email')
		{
			return ActivityEmail::renderActivityModalJS();
		}
	}

	public static function renderCompletionToggleJs()
	{
		return <<<JS
function toggleActivityCompletion(activity_id, activity_type, tab_combined)
{
	$.ajax({
		url: 'do.php?_action=ajax_helper&subaction=toggleActivityCompletion',
		type: 'get',
		data: {activity_id: activity_id, activity_type: activity_type, tab_combined: tab_combined}
		}).done(function(response, textStatus) {
			toastr.success('Activity status updated successfully.');
			loadActivities(response);
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(textStatus + ': ' + errorThrown);
	});
}		
JS;

	}

	public static function renderTimeSpentModalHTML($activity_id, $tab_combined = 0)
	{
		$options = [];
		for ($i = 0; $i <= 100; $i++)
			$options[] = [$i, $i];
		$hours = HTML::selectChosen('hours', $options, '', false, false);
		$minutes = HTML::selectChosen('minutes', $options, '', false, false);
		return <<<HTML

	<div class="modal-dialog">
		<form autocomplete="off" class="form-horizontal" method="post" name="frmActivityTime" id="frmActivityTime" method="post" action="do.php?_action=ajax_helper">
			<input type="hidden" name="id" value="$activity_id"/>
			<input type="hidden" name="subaction" value="save_activity_time_spent"/>
			<input type="hidden" name="tab_combined" value="$tab_combined"/>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title text-bold">Record time spent on this activity</h5>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="control-group"><label class="control-label" for="subject">Hours:</label>$hours</div>
						</div>
						<div class="col-sm-6">
							<div class="control-group"><label class="control-label" for="task">Minutes:</label>$minutes</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#timeSpentModal').modal('hide');">Cancel</button>
					<button type="button" id="btnTimeSpentModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
				</div>
			</div>
		</form>
	</div>

<script type="text/javascript">

$("button#btnTimeSpentModalSave").click(function(){
	var form = this.closest('form');
	$.ajax({
			url: form.action,
			type: form.method,
			data: $(form).serialize()
		}).done(function(response, textStatus) {
			toastr.success('Time updated successfully.');
			loadActivities(response);
			$('#timeSpentModal').modal('hide');
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(textStatus + ': ' + errorThrown);
		});
});

</script>
HTML;

	}

	public $id = NULL;
	public $entity_id = NULL;
	public $entity_type = NULL;
	public $activity_type = NULL;
	public $subject = NULL;
	public $date = NULL;
	public $due_date = NULL;
	public $next_action_date = NULL;
	public $detail = NULL;
	public $created_by = NULL;
	public $created_at = NULL;
	public $updated_at = NULL;
	public $hours = NULL;
	public $minutes = NULL;
	public $parent_entity_id = NULL;
	public $parent_entity_type = NULL;

	protected $audit_fields = array(
		'subject'=>'Subject',
		'date'=>'Date',
		'due_date'=>'Due Date',
		'next_action_date' => 'Next Action Date',
		'hours' => 'Hours',
		'minutes' => 'Minutes'
	);
}
