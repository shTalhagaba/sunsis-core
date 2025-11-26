<?php
class ViewRecActivityReport1 extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';

			$sql = <<<SQL


SELECT
	employerpool_notes.by_whom AS team_member,
	employerpool_notes.`whom_position` AS job_role,
	SUM(IF(employerpool_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Phone Call'), 1, 0)) AS `number_of_phone_calls`,
	SUM(IF(employerpool_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Email'), 1, 0)) AS `number_of_emails`,
	SUM(IF(employerpool_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Meeting'), 1, 0)) AS `number_of_meetings`,
	SUM(IF(employerpool_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Letter'), 1, 0)) AS `number_of_letters`,
	COUNT(*) AS `total_number_of_contacts`

FROM
	employerpool_notes

GROUP BY
	employerpool_notes.by_whom
ORDER BY
	employerpool_notes.by_whom;

SQL;

			// Create new view object
			$view = $_SESSION[$key] = new ViewRecActivityReport1();
			$view->setSQL($sql);

			// Job role filter
			$options = "SELECT whom_position, whom_position, null, CONCAT('WHERE employerpool_notes.whom_position=\"',whom_position,'\"') FROM employerpool_notes GROUP BY whom_position ORDER BY whom_position";
			$f = new DropDownViewFilter('filter_job_role', $options, null, true);
			$f->setDescriptionFormat("Job Role: %s");
			$view->addFilter($f);


			// type of contact Filter
			$options = "SELECT id, description, null, CONCAT('WHERE employerpool_notes.type_of_contact=',id) FROM lookup_crm_contact_type GROUP BY lookup_crm_contact_type.id ORDER BY description";
			$f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
			$f->setDescriptionFormat("Type of Contact: %s");
			$view->addFilter($f);

			// by_whom
			$options = "SELECT t1.by_whom, t1.by_whom, null, CONCAT('HAVING team_member=\"',t1.by_whom,'\"') FROM employerpool_notes t1 GROUP BY t1.by_whom ORDER BY t1.by_whom";
			$f = new DropDownViewFilter('filter_by_whom', $options, null, true);
			$f->setDescriptionFormat("Team Member: %s");
			$view->addFilter($f);

			// subject
			$options = "SELECT id, description, null, CONCAT('WHERE employerpool_notes.subject=',id) FROM lookup_crm_subject  WHERE pool = 1 ORDER BY description";
			$f = new DropDownViewFilter('filter_subject', $options, null, true);
			$f->setDescriptionFormat("CRM Subject: %s");
			$view->addFilter($f);

			// next action
			$options = "SELECT id, description, null, CONCAT('WHERE employerpool_notes.next_action=',id) FROM lookup_crm_regarding WHERE pool = 1 ORDER BY description";
			$f = new DropDownViewFilter('filter_next_action', $options, null, true);
			$f->setDescriptionFormat("CRM Subject: %s");
			$view->addFilter($f);

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Date Filter
			$format = "WHERE employerpool_notes.date >= '%s'";
			$f = new DateViewFilter('filter_from_date', $format, '');
			$f->setDescriptionFormat("From Date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE employerpool_notes.date <= '%s'";
			$f = new DateViewFilter('filter_to_date', $format, '');
			$f->setDescriptionFormat("To Date Created: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			foreach($columns as $column)
			{
				echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
			}

			echo '</tr></thead>';

			echo '<tbody>';


			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$type_of_contact = $this->getFilterValue('filter_type_of_contact');
				$subject = $this->getFilterValue('filter_subject');
				$next_action = $this->getFilterValue('filter_next_action');
				echo HTML::viewrow_opening_tag('/do.php?_action=baltic_view_prospect_crm_notes_report&ViewProspectCRMNotesReport_filter_by_whom=' . $row['team_member'] . '&ViewProspectCRMNotesReport_filter_type_of_contact=' . $type_of_contact . '&ViewProspectCRMNotesReport_filter_subject=' . $subject . '&ViewProspectCRMNotesReport_filter_next_action=' . $next_action);				foreach($columns as $column)
				{
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				echo '</tr>';
			}//end while
			echo '</tbody></table>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}


}
?>