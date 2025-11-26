<?php
class ViewProspectCRMNotesReport extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';

			$sql = <<<SQL


SELECT
	employerpool_notes.`name_of_person` AS `name_of_person_contacted`,
	(SELECT description FROM lookup_crm_contact_type WHERE id = employerpool_notes.`type_of_contact`) AS type_of_contact,
	(SELECT description FROM lookup_crm_subject WHERE id = employerpool_notes.`subject`) AS `subject`,
	DATE_FORMAT(employerpool_notes.`date`, '%d/%m/%Y') AS `date`,
	employerpool_notes.`agreed_action`,
	employerpool_notes.`by_whom`,
	employerpool_notes.`whom_position`,
	DATE_FORMAT(employerpool_notes.`next_action_date`, '%d/%m/%Y') AS `next_action_date`,
	(SELECT description FROM lookup_crm_regarding WHERE id = employerpool_notes.`next_action`) AS next_action,
	(SELECT company FROM central.`emp_pool` WHERE auto_id = employerpool_notes.`organisation_id`) AS company,
	employerpool_notes.audit_info

FROM
	employerpool_notes


$where

SQL;

// Create new view object

			$view = $_SESSION[$key] = new ViewProspectCRMNotesReport();
			$view->setSQL($sql);

			// company Filter
			$options = "SELECT auto_id, company, null, CONCAT('WHERE employerpool_notes.organisation_id=',auto_id) FROM central.emp_pool ORDER BY company";
			$f = new DropDownViewFilter('filter_company', $options, null, true);
			$f->setDescriptionFormat("Type of Contact: %s");
			$view->addFilter($f);

			// type of contact Filter
			$options = "SELECT id, description, null, CONCAT('WHERE employerpool_notes.type_of_contact=',id) FROM lookup_crm_contact_type GROUP BY lookup_crm_contact_type.id ORDER BY description";
			$f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
			$f->setDescriptionFormat("Type of Contact: %s");
			$view->addFilter($f);

			// by_whom
			$options = "SELECT t1.by_whom, t1.by_whom, null, CONCAT('WHERE employerpool_notes.by_whom=\"',t1.by_whom,'\"') FROM employerpool_notes t1 GROUP BY t1.by_whom ORDER BY t1.by_whom";
			$f = new DropDownViewFilter('filter_by_whom', $options, null, true);
			$f->setDescriptionFormat("Contacted By: %s");
			$view->addFilter($f);

			// subject
			$options = "SELECT id, description, null, CONCAT('WHERE employerpool_notes.subject=',id) FROM lookup_crm_subject WHERE pool = 1  GROUP BY  lookup_crm_subject .id ORDER BY description";
			$f = new DropDownViewFilter('filter_subject', $options, null, true);
			$f->setDescriptionFormat("Contacted By: %s");
			$view->addFilter($f);

			// next action
			$options = "SELECT id, description, null, CONCAT('WHERE employerpool_notes.next_action=',id) FROM lookup_crm_regarding  WHERE pool = 1 ORDER BY description";
			$f = new DropDownViewFilter('filter_next_action', $options, null, true);
			$f->setDescriptionFormat("Next Action: %s");
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

			// Next Action Date Filter
			$format = "WHERE employerpool_notes.next_action_date >= '%s'";
			$f = new DateViewFilter('filter_from_next_action_date', $format, '');
			$f->setDescriptionFormat("From Next Action Date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE employerpool_notes.next_action_date <= '%s'";
			$f = new DateViewFilter('filter_to_next_action_date', $format, '');
			$f->setDescriptionFormat("To Next Action Date Created: %s");
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
			echo '<div align="center" style="display:block;float:clear;" ><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
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