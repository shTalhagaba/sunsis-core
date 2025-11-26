<?php
class add_lesson_note implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new LessonNote();
		$vo->populate($_REQUEST);
		
		
		// Make sure that if no reading restrictions exist, the field is wiped
		// in the database
		if(is_null($vo->readers))
		{
			$vo->readers = '';
		}
		
		// Check permissions
		//$this->checkPermissions($link, $vo);
		
		$vo->save($link);
		
		// Instruct client on next action
		if(IS_AJAX)
		{
			header('Content-Type: text/plain; charset=ISO-8859-1');
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=read_register&lesson_id=' . $vo->lessons_id);
		}
	}
	
	
	private function checkPermissions(mysqli $link, LessonNote $vo)
	{
		switch($_SESSION['role'])
		{
			case 'admin':
				break;
				
			case 'user':
				switch($_SESSION['org']->org_type_id)
				{
					case ORG_SCHOOL:
						$num_pupils = <<<HEREDOC
SELECT
	COUNT(pot.school_id) AS school_id
FROM
	lessons INNER JOIN group_members INNER JOIN pot
	ON(lessons.groups_id = group_members.groups_id AND group_members.pot_id = pot.id)
WHERE
	lessons.id = {$vo->lessons_id} AND school_id = {$_SESSION['org']->id};
HEREDOC;
						$num_pupils = DAO::getSingleValue($link, $num_pupils);
						
						if($num_pupils === 0)
						{
							throw new UnauthorizedException("You may only add notes to a register that lists one or more of your pupils");
						}			
						break;
					
					case ORG_PROVIDER:
						$lesson_provider_id = <<<HEREDOC
SELECT
	courses.organisations_id
FROM
	lessons INNER JOIN groups INNER JOIN courses
	ON lessons.groups_id = groups.id AND groups.courses_id = courses.id
WHERE
	lessons.id = {$vo->lessons_id};
HEREDOC;
						$lesson_provider_id = DAO::getSingleValue($link, $lesson_provider_id);
						
						if($lesson_provider_id != $_SESSION['org']->id)
						{
							throw new UnauthorizedException("You may only add notes to a register belonging to your institution");
						}
						break;
						
					case ORG_EMPLOYER:
						throw new UnauthorizedException("Employers may not add notes to registers");
						break;
						
					default:
						throw new Exception("Unknown organisation type");
						break;
				}
				break;
			
			default:
				throw new Exception("Unknown security role");
				break;
		}		
	}
}
?>