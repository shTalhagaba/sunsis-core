<?php
class edit_register implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';
		$attendance_module = isset($_GET['attendance_module']) ? $_GET['attendance_module'] : SystemConfig::getEntityValue($link, 'attendance_module_v2');
		$attendance_module_id = isset($_GET['attendance_module_id']) ? $_GET['attendance_module_id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_register&lesson_id=" . $lesson_id . "&attendance_module=" . $attendance_module, "Mark Register");
		
		if($lesson_id == '' || !is_numeric($lesson_id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}
		
		// Load register from database
		if($attendance_module)
			$reg = new Register($lesson_id, $link, true);
		else
			$reg = new Register($lesson_id, $link);
		$reg->load($link);

		// make address presentation helpers
		$campus_address = new Address($reg->location);
		$tutor_address = new Address($reg->tutor);

		$readers_dropdown = $this->getVisibilityDropdown($link, $reg);
		$readers_preselect = array();

		$sort_dropdown = array(
			array(0, "Learner", null),
			array(1, "Provider", null));

		if($attendance_module)
		{
			if($attendance_module_id == '')
				$attendance_module_id = DAO::getSingleValue($link, "SELECT module_id FROM attendance_module_groups WHERE attendance_module_groups.id = (SELECT lessons.groups_id FROM lessons WHERE lessons.id = " . $lesson_id . ")");

			$sql = <<<SQL
SELECT
	re.id AS id,
	extra_learners.`lesson_id` AS lessons_id,
	extra_learners.`attendee_id`,
	re.entry AS entry,
	re.created AS created,
	re.lesson_contribution,
	l.date AS `lesson_date`,
	lookup.description AS entry_description,
	attendees.firstnames AS student_firstnames,
	attendees.surname AS student_surname,
	l.`tutor`
FROM
	lessons AS l INNER JOIN lesson_extra_attendees AS extra_learners INNER JOIN attendees
	ON (l.id = extra_learners.lesson_id AND attendees.id = extra_learners.`attendee_id` )
	LEFT OUTER JOIN register_extra_attendees_entries AS re ON (re.`attendee_id` = attendees.id AND re.lessons_id = l.id)
	LEFT OUTER JOIN lookup_register_entry_codes AS lookup ON lookup.code = re.entry
WHERE
	l.id = $lesson_id ;
SQL;
			$extra_attendees_entries = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		}
		// Presentation
		include('tpl_edit_register.php');
	}
	
	
	private function checkPermissions(PDO $link, Register $reg)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			// School users can edit a register if the register lists one or
			// more of their school's students and the register has been previously completed
			// by a training provider or administrator
			$num_pupils_in_lesson = <<<HEREDOC
SELECT
	COUNT(pot.school_id)
FROM
	lessons INNER JOIN group_members INNER JOIN pot
	ON(lessons.groups_id = group_members.groups_id AND group_members.pot_id = pot.id)
WHERE
	lessons.id = {$reg->lesson->id} AND pot.school_id = {$_SESSION['org']->id};
HEREDOC;
			$num_pupils_in_lesson = DAO::getSingleValue($link, $num_pupils_in_lesson);
			
			return ($num_pupils_in_lesson > 0) && ($reg->lesson->num_entries > 0);
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $reg->course->id);
			$is_employee = $_SESSION['org']->id == $reg->course->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && ($is_local_admin || $listed_in_course_acl);
		}
		else
		{
			return false;
		}
	}	

	
	private function getVisibilityDropdown(PDO $link, Register $reg)
	{
		$sql = <<<HEREDOC
(SELECT DISTINCT
	schools.id AS `value`,
	schools.short_name AS `label`,
	schools.legal_name AS `tooltip`,
	lookup_org_type.org_type AS `type`
FROM
	organisations AS schools
	INNER JOIN lookup_org_type ON schools.organisation_type like '%lookup_org_type.id%'
	INNER JOIN tr ON tr.employer_id = schools.id
	INNER JOIN group_members ON group_members.tr_id = tr.id
	INNER JOIN lessons ON group_members.groups_id = lessons.groups_id
WHERE
	lessons.id = {$reg->lesson->id})

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
	INNER JOIN lookup_org_type ON providers.organisation_type like '%lookup_org_type.id%'
WHERE
	lessons.id = {$reg->lesson->id})
	
ORDER BY
	`type` DESC, label ASC
HEREDOC;

		return DAO::getResultset($link, $sql);
		
		// 	AND schools.id != '{$_SESSION['org']->id}' It was there in where clause to restrict to the organisations's own records only
	}	
	
	
	private function renderRegisterNotes(PDO $link, Register $reg)
	{
			$sql = <<<HEREDOC
SELECT
	lesson_notes.*,
	users.email
FROM
	lesson_notes LEFT OUTER JOIN users ON lesson_notes.username = users.username
WHERE
	lessons_id = {$reg->lesson->id}		
HEREDOC;
	
			
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo '<div class="note">';
				echo '<div class="header">' . htmlspecialchars((string)$row['subject']) . '</div>';
				
				if($row['email'] != '')
				{
					echo "<div class=\"author\"><a href=\"mailto:{$row['email']}\">{$row['firstnames']} {$row['surname']}</a> @ {$row['organisation_name']}";
				}
				else
				{
					echo "<div class=\"author\">{$row['firstnames']} {$row['surname']} @ {$row['organisation_name']}";
				}
				
				echo ' (' . date('D, d M Y H:i:s T', strtotime($row['created'])) . ')</div>';
				echo HTML::nl2p(htmlspecialchars((string)$row['note']));
				echo '</div>';
			}
				
		}
	}
}
?>