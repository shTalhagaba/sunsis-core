<?php
class read_register implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';
		$export = isset($_GET['export']) ? $_GET['export'] : '';
		$attendance_module = isset($_GET['attendance_module']) ? $_GET['attendance_module'] : SystemConfig::getEntityValue($link, 'attendance_module_v2');
		$attendance_module_id = isset($_GET['attendance_module_id']) ? $_GET['attendance_module_id'] : '';

		if($lesson_id == '' || !is_numeric($lesson_id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$_SESSION['bc']->add($link, "do.php?_action=read_register&lesson_id=" . $lesson_id . "&attendance_module=" . $attendance_module, "View Register");

		if($attendance_module)
			$reg = new Register($lesson_id, $link, true);
		else
			$reg = new Register($lesson_id, $link);
		$reg->load($link);

		// Check authorisation
		if($this->checkPermissions($link, $reg) == false)
		{
			throw new UnauthorizedException();
		}

		$campus_address = new Address($reg->location);
		$tutor_address = new Address($reg->tutor);

		$readers_dropdown = $this->getVisibilityDropdown($link, $reg);
		$readers_preselect = array();

		if(!$attendance_module)
			$readers_preselect[] = $reg->course->organisations_id;
		else
			$readers_preselect[] = $reg->attendance_module->provider_id;
		if($export=='pdf')
		{
			$this->exportToPDF($link, $reg);
		}

		if($attendance_module)
		{
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

		$future_register = 0;
		$date_of_register = new Date($reg->lesson->date);
		$today_date = new Date(date('Y-m-d'));
		if($date_of_register->after($today_date))
			$future_register = 1;

		//pre($reg);	

		// Presentation
		include('tpl_read_register.php');
	}

	private function exportToPDF(PDO $link, Register $reg)
	{

		include("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','','','',15,15,47,16,9,9);
		// LOAD a stylesheet
		$stylesheet = file_get_contents('common.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

		$mpdf->SetImportUse();

		if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
			$mpdf->SetDocTemplate('RegisterPrintTemplate1.pdf',1);	// 1|0 to continue after end of document or not - used on matching page numbers
		else
			$mpdf->SetDocTemplate('RegisterPrintTemplate.pdf',1);	// 1|0 to continue after end of document or not - used on matching page numbers

		$learnersCount = count($reg->entries);
		$page_no = '{PAGENO}';
		$print_off_date = '{DATE j-m-Y H:m}';

		$lesson_id = $reg->lesson->id;
		$reg_dates = htmlspecialchars(Date::toLong($reg->lesson->date).' ('.$reg->lesson->start_time.' - '.$reg->lesson->end_time.')');
		$reg_provider = htmlspecialchars((string)$reg->provider->legal_name);
		$reg_course = htmlspecialchars((string)$reg->course->title.' ('.Date::toShort($reg->course->course_start_date).')');
		$reg_group = htmlspecialchars((string)$reg->group->title);
		$reg_qualification = htmlspecialchars((string)$reg->lesson->qualification);

		if($reg->lesson->module != '')
			$module_title = htmlspecialchars((string) DAO::getSingleValue($link, "select title from modules where id = {$reg->lesson->module}"));
		else
			$module_title = '&nbsp;';

		if($reg->lesson->module != '')
			$glh_in_lesson = htmlspecialchars((string) DAO::getSingleValue($link, "select learning_hours from modules where id = {$reg->lesson->module}"));
		else
			$glh_in_lesson = '&nbsp;';

		$rowNum = 0;

		$data = new ArrayIterator($reg->entries);

		$counter = 0;
		if(count($reg->entries) % 8 == 0)
			$limit = 7;
		else
			$limit = 8;
		$offset = 0;

		$total_pages = ceil(count($reg->entries) / $limit);

		for($i = 0; $i <= count($reg->entries); $i = $i + $limit)
		{
			$rowNum++;
			$html = <<<HEREDOC
			<h3>Lesson</h3>
<table border="0" style="margin-left:10px" cellspacing="4" cellpadding="4" width="590">
	<col width="110"/>
	<tr>
		<td class="fieldLabel">Date &amp; Time:</td>
		<td class="fieldValue"> $reg_dates </td>
	</tr>
	<tr>
		<td class="fieldLabel">Provider:</td>
		<td class="fieldValue"> $reg_provider </td>	</tr>
	<tr>
		<td class="fieldLabel">Group:</td>
		<td class="fieldValue">$reg_group </td>
	</tr>
	<tr>
		<td class="fieldLabel">Qualification:</td>
		<td class="fieldValue">$reg_qualification</td>
	</tr>

	<tr>
		<td class="fieldLabel">Module:</td>
		<td class="fieldValue">$module_title </td>
	</tr>
	<tr>
		<td class="fieldLabel">GLH in Lesson:</td>
		<td class="fieldValue">$glh_in_lesson </td>
	</tr>
</table>
<h3>Learner Attendance</h3>
<table border="1" style="margin-left:10px;" cellspacing="0" cellpadding="6" width="580" style="border-collapse:collapse;">
	<tr width="100%">
		<th width="10%">Learner<br>Ref No</th>
		<th width="23%">Learner First Name</th>
		<th width="23%">Learner Surname</th>
		<th width="10%">Full GLH in<br>Lesson Achieved<br>(Yes or No)</th>
		<th width="10%">If "No"<br>Then - Enter<br>Actual GLH Achieved</th>
		<th width='23%'>Learner Signature</th>
	</tr>

HEREDOC;
			foreach(new LimitIterator($data, $offset, $limit) as $entry)
			{

				$html .= '<tr>';

				// Remaining cells
				if($entry->late_starter)
				{
					$text_style = 'color:blue';
				}
				elseif($entry->pot_closed)
				{
					$text_style = 'color: silver; text-decoration: line-through';
				}
				else
				{
					$text_style = '';
				}

				$html .=  "<td  height='35' style=\"$text_style\" >" . HTML::cell($entry->l03) . '</td>';
				$html .=  "<td  height='35' style=\"$text_style\" >" . HTML::cell($entry->student_firstnames) . '</td>';
				$html .=  "<td  height='35' style=\"$text_style; font-style: italic; text-transform: uppercase\" >" . HTML::cell($entry->student_surname) . '</td>';


				$html .=  "<td>&nbsp;</td>";
				$html .=  "<td>&nbsp;</td>";
				$html .=  "<td>&nbsp;</td>";


				$html .=  '</tr>';

			}
			$html .= "</table>";
			$mpdf->AddPage();
			$mpdf->WriteHTML($html);

			$footer = <<<HEREDOC
		<div>
			<table border = "1"  width = "100%" style="border-collapse:collapse;">
				<tr>
					<td colspan="4">
						I confirm that each signatory above has completed the duration of this lesson.<br>
						I confirm that I will update the Sunesis database attendance records in line with this register within one working day.
					</td>
				</tr>
				<tr>
					<td width = "33%" bgcolor="#B9BCCA">
						<strong>Trainer Name</strong>
					</td>
					<td colspan="3" width = "67%">

					</td>
				</tr>
				<tr>
					<td width = "10%" bgcolor="#B9BCCA">
						<strong>Trainer Signature</strong>
					</td>
					<td width = "30%">
					</td>
					<td width = "35%" bgcolor="#B9BCCA">
						<strong>Trainer Signature Sign off Date</strong>
					</td>
					<td width = "25%">
					</td>
				</tr>
			</table>
		<div>
		<br>
		<div>
			<table border = "1"  width = "100%" style="border-collapse:collapse;">
				<tr>
					<td width = "33%" bgcolor="#B9BCCA">
						<strong>Lesson ID</strong>
					</td>
					<td width = "33.5%">
						$lesson_id
					</td>
					<td width = "33.5%">
					</td>
				</tr>
				<tr>
					<td width = "33%" bgcolor="#B9BCCA">
						<strong>Print off date</strong>
					</td>
					<td width = "33%">
						$print_off_date
					</td>
					<td width = "33%" align="right">
						Page $page_no of $total_pages
					</td>
				</tr>
			</table>
		</div>
HEREDOC;
			if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
				$footer .= '<div><img src="images/ReedPrintRegisterLogo.png" /><div>';
			$mpdf->SetHTMLFooter($footer);

			$offset = $offset + $limit;
		}







		$mpdf->Output();

		exit;
	}

	private function checkPermissions(PDO $link, Register $reg)
	{
		//if($_SESSION['role'] == 'admin')
		if(true) // Khushnood
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			// School users can read a register if the register
			// lists one or more of their school's pupils
			$num_pupils_in_lesson = <<<HEREDOC
SELECT
	COUNT(tr.employer_id)
FROM
	lessons INNER JOIN group_members INNER JOIN tr
	ON(lessons.groups_id = group_members.groups_id AND group_members.pot_id = tr.id)
WHERE
	lessons.id = {$reg->lesson->id} AND tr.employer_id = {$_SESSION['org']->id};
HEREDOC;
			$num_pupils_in_lesson = DAO::getSingleValue($link, $num_pupils_in_lesson);

			return ($num_pupils_in_lesson > 0);
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
	organisations.id AS `value`,
	organisations.short_name AS `label`,
	organisations.legal_name AS `tooltip`,
	lookup_org_type.org_type AS `type`
FROM
	organisations 
	INNER JOIN lookup_org_type ON lookup_org_type.id like '%organisations.organisation_type%'
	INNER JOIN tr ON tr.employer_id = organisations.id
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
	INNER JOIN lookup_org_type ON lookup_org_type.id like '%providers.org_type_id%'
WHERE
	lessons.id = {$reg->lesson->id})
	
ORDER BY
	`type` DESC, label ASC
HEREDOC;


		// The line below was removed from where clause  
		//AND providers.id != '{$_SESSION['org']->id}' */ )


		return DAO::getResultset($link, $sql);
	}


	private function renderRegisterNotes(PDO $link, Register $reg)
	{
		$sql = <<<HEREDOC
SELECT
	lesson_notes.*,
	users.work_email
FROM
	lesson_notes LEFT OUTER JOIN users ON lesson_notes.username = users.username
WHERE
	lessons_id = {$reg->lesson->id}		
HEREDOC;

		/*		if($_SESSION['role'] == 'user')
		  {
			  $sql .= <<<HEREDOC
  # Show own notes, public notes and those notes with the user's org ID in the readers field
  AND (lesson_notes.organisations_id = {$_SESSION['org']->id} OR lesson_notes.`public` = 1 OR FIND_IN_SET({$_SESSION['org']->id}, lesson_notes.readers) )
  HEREDOC;
		  }
  */
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo '<div class="note">';
				//echo '<div class="header">' . htmlspecialchars((string)$row['subject']) . '</div>';
				echo '<div class="header">';
				echo '<table width="100%"><tr><td align="left">'.htmlspecialchars((string)$row['subject']).'</td>';
				echo '<td align="right">';
				if( (($_SESSION['user']->isAdmin()) || ($_SESSION['user']->username == $row['username']))
					&& $row['is_audit_note'] == '0' )
				{
					echo <<<HEREDOC
<span class="button" onclick="editLessonNote({$row['id']})">Edit</span>
<span class="button" onclick="deleteLessonNote({$row['id']})">Delete</span></td>
HEREDOC;
				}
				echo '</td></tr></table></div>';

				if($row['work_email'] != '')
				{
					echo "<div class=\"author\"><a href=\"mailto:{$row['work_email']}\">{$row['firstnames']} {$row['surname']}</a> @ {$row['organisation_name']}";
				}
				else
				{
					echo "<div class=\"author\">{$row['firstnames']} {$row['surname']} @ {$row['organisation_name']}";
				}
				echo ' (' . date('D, d M Y H:i:s T', strtotime($row['created'] ?: '')) . ')</div>';
				echo HTML::nl2p(htmlspecialchars((string)$row['note']));
				echo '</div>';
			}

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

}
?>