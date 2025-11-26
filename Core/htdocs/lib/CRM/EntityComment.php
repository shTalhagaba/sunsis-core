<?php
class EntityComment extends ValueObject
{
	public $id = NULL;
	public $entity_id = NULL;
	public $entity_type = NULL;
	public $subject = NULL;
	public $comments = NULL;
	public $created = NULL;
	public $created_by = NULL;

	public function save(PDO $link)
	{
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
		$this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;


		$result = DAO::saveObjectToTable($link, 'crm_entities_comments', $this);

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
	crm_entities_comments
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$comment = null;
		if($st)
		{
			$comment = null;
			$row = $st->fetch();
			if($row)
			{
				$comment = new EntityComment();
				$comment->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find comment record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $comment;
	}

	public static function renderComments(PDO $link, $entity_type, $entity_id, $order_by_column = " created ", $order_by_type = " ASC ")
	{
		$result = DAO::getResultset($link, "SELECT * FROM crm_entities_comments WHERE entity_type = '{$entity_type}' AND entity_id = '{$entity_id}' ORDER BY {$order_by_column} {$order_by_type}", DAO::FETCH_ASSOC);

		$html = "<table class='table table-bordered'>";
		$html .= "<thead><tr><th>Creation Details</th><th>Event</th><th>Comments</th></tr></thead>";
		$html .= "<tbody>";
		if(count($result) == 0)
			$html .= "<tr><td colspan='3'>No records found.</td></tr>";
		else
		{
			foreach($result AS $row)
			{
				$html .= "<tr>";
				$html .= "<td>";
				$html .= "By:&nbsp;" . str_replace(' ', '&nbsp;', self::getCreatedByName($link, $row['created_by'])) . "<br>";
				$html .= "Date:&nbsp;" . Date::toShort($row['created']) . "<br>";
				$html .= "Time:&nbsp;" . Date::to($row['created'], 'H:i:s') . "<br>";
				$html .= "<form action='do.php?_action=ajax_helper&subaction=delete_crm_entity_comment' method='post'>";
				$html .= "<input type='hidden' name='comment_id' value='{$row['id']}'>";
				$html .= "<span class='btn btn-xs btn-danger btn-block btnDelEntityComment'><i class='fa fa-trash'></i> </span>";
				$html .= "</form>";
				$html .= "</td>";
				$html .= "<td>{$row['subject']}</td>";
				$html .= "<td>{$row['comments']}</td>";
				$html .= "</td>";
				$html .= "</tr>";
			}
		}
		$html .= "</tbody>";
		$html .= "</table>";

		return $html;
	}

	public static function renderModalOpenButton()
	{
		return '<span class="btn btn-xs btn-primary" onclick="$(\'#commentModal\').modal(\'show\');"><i class="fa fa-comment"></i> Add Comment</span> &nbsp;';
	}

	public static function renderModalHTML($entity_id, $entity_type)
	{
		return <<<HTML
<div class="modal fade" id="commentModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title text-bold">Use this feature to record any comments</h5>
			</div>
			<div class="modal-body">
				<form autocomplete="off" class="form-horizontal" method="post" name="frmCommentModal" id="frmCommentModal" method="post" action="do.php?_action=ajax_helper&subaction=save_entity_comment">
					<input type="hidden" name="entity_id" value="$entity_id" />
					<input type="hidden" name="entity_type" value="$entity_type" />

					<div class="control-group">
						<label class="control-label" for ="subject">Subject/Event:</label>
						<input type="text" name="frmCommentModalSubject" id="frmCommentModalSubject" class="form-control">
					</div>
					<div class="control-group">
						<label class="control-label" for ="frmCommentModalComments">Comments:</label>
						<textarea class="form-control" name="frmCommentModalComments" id="frmCommentModalComments" rows="5" style="width: 100%;"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#commentModal').modal('hide');">Cancel</button>
				<button type="button" id="btnCommentModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
			</div>
		</div>
	</div>
</div>



HTML;

	}

	public static function renderModalJS()
	{
		return <<<JS

<script type="text/javascript">

$("button#btnCommentModalSave").click(function(){
	if(validateForm(document.forms['frmCommentModal']) == false)
	{
		return;
	}
	$("#frmCommentModal").submit();
});

$('.btnDelEntityComment').on('click', function(){
	if(!confirm('This action is irreversible, are you sure you want to continue?'))
	{ return false;}
	var form = this.closest('form');

	$.ajax({
		url: form.action,
		type: form.method,
		data: $(form).serialize()
	}).done(function(response, textStatus) {
		toastr.success(response.responseText);
		window.location.reload();
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(textStatus + ': ' + errorThrown);
	});
});

</script>

JS;

	}

	public function isSafeToDelete()
	{
		return true;
	}

	public function delete(PDO $link)
	{
		if($this->isSafeToDelete($link))
		{
			return DAO::execute($link, "DELETE FROM crm_entities_comments WHERE id = '{$this->id}'");
		}
		else
		{
			throw new Exception('This record is not safe to delete.');
		}

	}
}
