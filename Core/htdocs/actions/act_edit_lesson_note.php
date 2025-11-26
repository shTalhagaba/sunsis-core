<?php
class edit_lesson_note implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: id");
		}

		$vo = LessonNote::loadFromDatabase($link, $id);
		if(is_null($vo))
		{
			throw new Exception("No note with id #$id found");
		}

		// Check permissions
/*		if( ($_SESSION['role'] != 'admin') && ($vo->username != $_SESSION['username']) )
		{
			throw new Exception("Only administrators and the report author may edit a report");
		}
*/		
		if($vo->is_audit_note)
		{
			throw new Exception("Audit log notes may not be edited");
		}
		
		$readers_dropdown = $this->getVisibilityDropdown($link, $vo->lessons_id);
		
		require_once('tpl_edit_lesson_note.php');
	}

	
	private function getVisibilityDropdown(PDO $link, $lessons_id)
	{
		$sql = <<<HEREDOC
(SELECT DISTINCT
	schools.id AS `value`,
	schools.short_name AS `label`,
	schools.legal_name AS `tooltip`,
	lookup_org_type.org_type AS `type`
FROM
	organisations AS schools
	INNER JOIN lookup_org_type ON lookup_org_type.id = schools.organisation_type
	INNER JOIN tr ON tr.provider_id = schools.id
	INNER JOIN group_members ON group_members.tr_id = tr.id
	INNER JOIN lessons ON group_members.groups_id = lessons.groups_id
WHERE
	lessons.id = {$lessons_id}
	AND schools.id != '{$_SESSION['user']->employer_id}')

UNION DISTINCT

(SELECT DISTINCT
	providers.id AS `value`,
	providers.short_name AS `label`,
	providers.legal_name AS `tooltip`,
	lookup_org_type.org_type AS `type`
FROM
	lessons
	INNER JOIN groups ON lessons.groups_id = groups.id
	INNER JOIN courses ON groups.courses_id = courses.id
	INNER JOIN organisations AS providers ON courses.organisations_id = providers.id
	INNER JOIN lookup_org_type ON lookup_org_type.id = providers.organisation_type
WHERE
	lessons.id = {$lessons_id}
	AND providers.id != '{$_SESSION['user']->employer_id}')
	
ORDER BY
	`type` DESC, label ASC
HEREDOC;

		return DAO::getResultset($link, $sql);
	}	
	
}
?>