<?php
class ViewCandidateCRMNotesReport extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';

			$sql = <<<SQL


SELECT
	crm_notes_candidates.`name_of_person` AS `name_of_person_contacted`,
	(SELECT description FROM lookup_crm_contact_type WHERE id = crm_notes_candidates.`type_of_contact`) AS type_of_contact,
	(SELECT description FROM lookup_crm_subject WHERE id = crm_notes_candidates.`subject`) AS `subject`,
	DATE_FORMAT(crm_notes_candidates.`date`, '%d/%m/%Y') AS `date`,
	crm_notes_candidates.`agreed_action`,
	crm_notes_candidates.`by_whom`,
	crm_notes_candidates.`whom_position`,
	DATE_FORMAT(crm_notes_candidates.`next_action_date`, '%d/%m/%Y') AS `next_action_date`,
	crm_notes_candidates.`other_notes`

FROM
	crm_notes_candidates

$where

SQL;

// Create new view object

			$view = $_SESSION[$key] = new ViewCandidateCRMNotesReport();
			$view->setSQL($sql);

			// type of contact Filter
			$options = "SELECT id, description, null, CONCAT('WHERE crm_notes_candidates.type_of_contact=',id) FROM lookup_crm_contact_type GROUP BY lookup_crm_contact_type.id ORDER BY description";
			$f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
			$f->setDescriptionFormat("Type of Contact: %s");
			$view->addFilter($f);

			// by_whom
			$options = "SELECT t1.by_whom, t1.by_whom, null, CONCAT('WHERE crm_notes_candidates.by_whom=\"',t1.by_whom,'\"') FROM crm_notes_candidates t1 GROUP BY t1.by_whom ORDER BY t1.by_whom";
			$f = new DropDownViewFilter('filter_by_whom', $options, null, true);
			$f->setDescriptionFormat("Contacted By: %s");
			$view->addFilter($f);

			// subject
			$options = "SELECT id, description, null, CONCAT('WHERE crm_notes_candidates.subject=',id) FROM lookup_crm_subject WHERE candidate = 1 GROUP BY  lookup_crm_subject .id ORDER BY description";
			$f = new DropDownViewFilter('filter_subject', $options, null, true);
			$f->setDescriptionFormat("Contacted By: %s");
			$view->addFilter($f);

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Date Filter
			$format = "WHERE crm_notes_candidates.date >= '%s'";
			$f = new DateViewFilter('filter_from_date', $format, '');
			$f->setDescriptionFormat("From Date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE crm_notes_candidates.date <= '%s'";
			$f = new DateViewFilter('filter_to_date', $format, '');
			$f->setDescriptionFormat("To Date Created: %s");
			$view->addFilter($f);

			// Next Action Date Filter
			$format = "WHERE crm_notes_candidates.next_action_date >= '%s'";
			$f = new DateViewFilter('filter_from_next_action_date', $format, '');
			$f->setDescriptionFormat("From Next Action Date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE crm_notes_candidates.next_action_date <= '%s'";
			$f = new DateViewFilter('filter_to_next_action_date', $format, '');
			$f->setDescriptionFormat("To Next Action Date Created: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Date Created (DESC)', null, 'ORDER BY crm_notes_candidates.date_created DESC'),
				1=>array(2, 'Date Created (DESC), Name of Person Contacted (ASC)', null, 'ORDER BY crm_notes_candidates.date_created DESC, crm_notes_candidates.name_of_person ASC'),
				2=>array(3, 'Date Contacted (DESC), Name of Person Contacted (ASC)', null, 'ORDER BY crm_notes_candidates.date DESC, crm_notes_candidates.name_of_person ASC'),
				3=>array(4, 'By Whom (ASC)', null, 'ORDER BY crm_notes_candidates.by_whom'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
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
				echo '<tr>';
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