<?php
class ViewLearnerCrmNotes extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;

		if(!isset($_SESSION[$key]))
		{
			if(DB_NAME=="ams" || DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
			{
				if($_SESSION['user']->isAdmin())
				{
					$where = " where tr_id = $id ";
				}
				else
				{
					$provider_id = $_SESSION['user']->employer_id;
//					$where = " where tr_id = $id and tr.provider_id = '$provider_id' " ;
					$where = " where tr_id = $id ";

				}
			}
			else
			{
				$where = " where tr_id = $id ";
			}

			// Create new view object
			$sql = <<<HEREDOC
SELECT
	crm_notes_learner.id,	
	tr_id,
	name_of_person,
	position,
	agreed_action,
	DATE_FORMAT(date, '%d/%m/%Y') AS date,
	lookup_crm_contact_type.description as type_of_contact,
	lookup_crm_subject.description as subject,
	by_whom,
	whom_position,
	next_action_date
FROM
	crm_notes_learner
	left join tr on crm_notes_learner.tr_id = tr.id
	left join lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes_learner.type_of_contact
	left join lookup_crm_subject on lookup_crm_subject.id = crm_notes_learner.subject

$where

ORDER BY crm_notes_learner.date desc
HEREDOC;

			$view = $_SESSION[$key] = new ViewLearnerCrmNotes();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Date</th><th>Person contacted</th><th> Position </th><th>Type of Contact</th><th>Subject</th><th>By whom</th><th> Position </th><th> Next Action Date </th><th>Agreed Action</th></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
					echo '<tr>';
				else
					echo HTML::viewrow_opening_tag('do.php?_action=edit_learner_crm_note&id=' . rawurlencode($row['id']) . '&tr_id=' . rawurlencode($row['tr_id']));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['name_of_person']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['position']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['by_whom']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['whom_position']) . "</td>";
				if($row['next_action_date'] != '')
				{
					$row['next_action_date'] = strtotime($row['next_action_date']);
					$row['next_action_date'] = date('d/m/Y',$row['next_action_date']);
				}
				echo '<td align="left">' . HTML::cell($row['next_action_date']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['agreed_action']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator('left');

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>