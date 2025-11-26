<?php
class ViewRecActivityReport2 extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';

			$sql = <<<SQL


SELECT
	crm_notes.by_whom AS team_member,
	crm_notes.`whom_position` AS job_role,
	SUM(IF(crm_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Phone Call'), 1, 0)) AS `number_of_phone_calls`,
	SUM(IF(crm_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Email'), 1, 0)) AS `number_of_emails`,
	SUM(IF(crm_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Meeting'), 1, 0)) AS `number_of_meetings`,
	SUM(IF(crm_notes.`type_of_contact` = (SELECT id FROM lookup_crm_contact_type WHERE description = 'Letter'), 1, 0)) AS `number_of_letters`,
	COUNT(*) AS `total_number_of_contacts`

FROM
	crm_notes

GROUP BY
	crm_notes.by_whom
ORDER BY
	crm_notes.by_whom;

SQL;

			// Create new view object
			$view = $_SESSION[$key] = new ViewRecActivityReport2();
			$view->setSQL($sql);

			// Job role filter
			$options = "SELECT whom_position, whom_position, null, CONCAT('WHERE crm_notes.whom_position=\"',whom_position,'\"') FROM crm_notes GROUP BY whom_position ORDER BY whom_position";
			$f = new DropDownViewFilter('filter_job_role', $options, null, true);
			$f->setDescriptionFormat("Job Role: %s");
			$view->addFilter($f);

			// by_whom
			$options = "SELECT t1.by_whom, t1.by_whom, null, CONCAT('HAVING team_member=\"',t1.by_whom,'\"') FROM crm_notes t1 GROUP BY t1.by_whom ORDER BY t1.by_whom";
			$f = new DropDownViewFilter('filter_by_whom', $options, null, true);
			$f->setDescriptionFormat("Team Member: %s");
			$view->addFilter($f);

			// type of contact Filter
			$options = "SELECT id, description, null, CONCAT('WHERE crm_notes.type_of_contact=',id) FROM lookup_crm_contact_type GROUP BY lookup_crm_contact_type.id ORDER BY description";
			$f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
			$f->setDescriptionFormat("Type of Contact: %s");
			$view->addFilter($f);

			// subject
			$options = "SELECT id, description, null, CONCAT('WHERE crm_notes.subject=',id) FROM lookup_crm_subject WHERE employer = 1  ORDER BY description";
			$f = new DropDownViewFilter('filter_subject', $options, null, true);
			$f->setDescriptionFormat("CRM Subject: %s");
			$view->addFilter($f);

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Date Filter
			$format = "WHERE crm_notes.date >= '%s'";
			$f = new DateViewFilter('filter_from_date', $format, '');
			$f->setDescriptionFormat("From Date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE crm_notes.date <= '%s'";
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
				echo HTML::viewrow_opening_tag('/do.php?_action=view_organisation_crm&ViewOrganisationCRM_filter_by_whom=' . $row['team_member'] . '&ViewOrganisationCRM_filter_type_of_contact=' . $type_of_contact . '&ViewOrganisationCRM_filter_subject=' . $subject);
				foreach($columns as $column)
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