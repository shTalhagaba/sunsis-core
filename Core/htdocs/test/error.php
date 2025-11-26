<?php
require('config.php');
	
$message = <<<HEREDOC
Test error - please ignore
HEREDOC;

$sql = <<<HEREDOC
SELECT DISTINCT
	pot.firstnames,
	pot.surname,
	pot.id,
	HEX(sif_student_ref_ids.sif_ref_id) AS sif_ref_id
FROM
	register_entries INNER JOIN lessons INNER JOIN groups INNER JOIN group_members INNER JOIN pot INNER JOIN students
	INNER JOIN organisations AS partnerships INNER JOIN sif_student_ref_ids
		ON register_entries.lessons_id = lessons.id
		AND lessons.groups_id = groups.id
		AND groups.id = group_members.groups_id
		AND register_entries.pot_id = pot.id
		AND pot.students_id = students.id
		AND pot.partnership_id = partnerships.id
		AND students.id = sif_student_ref_ids.students_id
		AND students.school_id = sif_student_ref_ids.organisations_id
	INNER JOIN sif_session_mappings AS `map`
		ON pot.school_id = map.school_id
		AND groups.courses_id = map.course_id
		AND groups.id = map.group_id
		AND lessons.lesson_provider = map.provider_id
		AND lessons.location = map.location_id
		AND WEEKDAY(lessons.date) = map.weekday
		AND lessons.start_time = map.start
		AND lessons.end_time = map.end
WHERE
	lessons.date = '2011-10-13'
	AND pot.school_id = 497
	AND pot.partnership_id = 494
	AND partnerships.sif_enabled = 1
	AND sif_student_ref_ids.sif_ref_id IS NOT NULL
	AND map.periods IS NOT NULL;
	
SELECT DISTINCT
	pot.firstnames,
	pot.surname,
	pot.id,
	HEX(sif_student_ref_ids.sif_ref_id) AS sif_ref_id
FROM
	register_entries INNER JOIN lessons INNER JOIN groups INNER JOIN group_members INNER JOIN pot INNER JOIN students
	INNER JOIN organisations AS partnerships INNER JOIN sif_student_ref_ids
		ON register_entries.lessons_id = lessons.id
		AND lessons.groups_id = groups.id
		AND groups.id = group_members.groups_id
		AND register_entries.pot_id = pot.id
		AND pot.students_id = students.id
		AND pot.partnership_id = partnerships.id
		AND students.id = sif_student_ref_ids.students_id
		AND students.school_id = sif_student_ref_ids.organisations_id
	INNER JOIN sif_session_mappings AS `map`
		ON pot.school_id = map.school_id
		AND groups.courses_id = map.course_id
		AND groups.id = map.group_id
		AND lessons.lesson_provider = map.provider_id
		AND lessons.location = map.location_id
		AND WEEKDAY(lessons.date) = map.weekday
		AND lessons.start_time = map.start
		AND lessons.end_time = map.end
WHERE
	lessons.date = '2011-10-13'
	AND pot.school_id = 497
	AND pot.partnership_id = 494
	AND partnerships.sif_enabled = 1
	AND sif_student_ref_ids.sif_ref_id IS NOT NULL
	AND map.periods IS NOT NULL;
	
SELECT DISTINCT
	pot.firstnames,
	pot.surname,
	pot.id,
	HEX(sif_student_ref_ids.sif_ref_id) AS sif_ref_id
FROM
	register_entries INNER JOIN lessons INNER JOIN groups INNER JOIN group_members INNER JOIN pot INNER JOIN students
	INNER JOIN organisations AS partnerships INNER JOIN sif_student_ref_ids
		ON register_entries.lessons_id = lessons.id
		AND lessons.groups_id = groups.id
		AND groups.id = group_members.groups_id
		AND register_entries.pot_id = pot.id
		AND pot.students_id = students.id
		AND pot.partnership_id = partnerships.id
		AND students.id = sif_student_ref_ids.students_id
		AND students.school_id = sif_student_ref_ids.organisations_id
	INNER JOIN sif_session_mappings AS `map`
		ON pot.school_id = map.school_id
		AND groups.courses_id = map.course_id
		AND groups.id = map.group_id
		AND lessons.lesson_provider = map.provider_id
		AND lessons.location = map.location_id
		AND WEEKDAY(lessons.date) = map.weekday
		AND lessons.start_time = map.start
		AND lessons.end_time = map.end
WHERE
	lessons.date = '2011-10-13'
	AND pot.school_id = 497
	AND pot.partnership_id = 494
	AND partnerships.sif_enabled = 1
	AND sif_student_ref_ids.sif_ref_id IS NOT NULL
	AND map.periods IS NOT NULL;
	
HEREDOC;

throw new SQLException($message, 1, $sql);

?>