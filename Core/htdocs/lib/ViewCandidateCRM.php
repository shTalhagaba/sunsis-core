<?php
class ViewCandidateCRM extends View
{

	public static function getInstance($link, $candidate_id)
	{
		$key = 'view_'.__CLASS__;

//		if(!isset($_SESSION[$key]))
//		{
			$sql = <<<HEREDOC
SELECT
		concat(candidate.firstnames,' ',candidate.surname) as candidate,
		crm_notes_candidates.name_of_person,
		crm_notes_candidates.position,
		lookup_crm_contact_type.description AS type_of_contact,
		lookup_crm_subject.description as subject,
		crm_notes_candidates.date,
		crm_notes_candidates.by_whom,
		crm_notes_candidates.whom_position,
		crm_notes_candidates.agreed_action,
		crm_notes_candidates.id,
		crm_notes_candidates.candidate_id,
		crm_notes_candidates.next_action_date,
		crm_notes_candidates.crm_type,
		crm_notes_candidates.other_notes

FROM
		crm_notes_candidates
		INNER JOIN candidate on candidate.id = crm_notes_candidates.candidate_id
		LEFT JOIN lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes_candidates.type_of_contact
		LEFT JOIN lookup_crm_subject on lookup_crm_subject.id = crm_notes_candidates.subject
WHERE crm_notes_candidates.candidate_id = $candidate_id
HEREDOC;

// 			$view = $_SESSION[$key] = new ViewCandidateCRM();
 			$view = new ViewCandidateCRM();
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

//		}

//		return $_SESSION[$key];
		return $view;
	}


	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		//$st=$link->query("call view_training_providers();");
		if($st)
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="5">';
			echo '<thead height="40px"><tr><th>&nbsp;</th><th class="topRow">Candidate</th><th class="topRow">Name of Person</th><th class="topRow">Position</th><th class="topRow">Type of Contact</th><th class="topRow">Subject</th><th class="topRow">Date</th><th class="topRow">By Whom</th><th class="topRow">Position</th><th class="topRow">Next Action Date</th><th class="topRow">Agreed Action</th><th class="topRow">Other Notes</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($row['crm_type'] == 'crm_note' && $_SESSION['user']->isAdmin()) // only super users can edit the records
				{
					echo HTML::viewrow_opening_tag('/do.php?_action=edit_candidate_crm&id=' . $row['id'] . '&candidate_id=' . $row['candidate_id']);
					echo '<td><img src="/images/text-left.png" border="0" /></td>';
				}
				else
				{
					echo '<td><img src="/images/text-left.png" border="0" width="35" height="35" /></td>';
				}
				echo '<td align="left">' . HTML::cell($row["candidate"]) . '</td>';
				echo '<td align="left">' . HTML::cell($row['name_of_person']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['position']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['type_of_contact']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['subject']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['by_whom']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['whom_position']) . '</td>';
				if($row['next_action_date'] != '')
				{
					$row['next_action_date'] = strtotime($row['next_action_date']);
					$row['next_action_date'] = date('d/m/Y',$row['next_action_date']);
				}
				echo '<td align="left">' . HTML::cell($row['next_action_date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['agreed_action']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['other_notes']) . '</td>';

				echo '</tr>';
			}

			echo '</tbody></table></div align="left">';
			echo $this->getViewNavigator('left');

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>