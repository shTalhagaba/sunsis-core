<?php
class ViewOrganisationCrmNotes extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		
		if(!isset($_SESSION[$key.'a']))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	crm_notes_orgs.id,	
	crm_notes_orgs.organisation_id,
	organisation_contact.contact_name as name_of_person,
	organisation_contact.job_title,
	crm_notes_orgs.agreed_action,
	DATE_FORMAT(crm_notes_orgs.`contact_date`, '%d/%m/%Y') AS contact_date,
	lookup_crm_contact_type.description as type_of_contact,
	lookup_crm_subject.description as subject,
	crm_notes_orgs.by_whom,
	crm_notes_orgs.by_whom_position,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_notes_orgs.`created_by`) AS created_by,
	crm_notes_orgs.created_at,
	crm_notes_orgs.actioned
FROM
	crm_notes_orgs
	left join lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes_orgs.type_of_contact
	left join lookup_crm_subject on lookup_crm_subject.id = crm_notes_orgs.subject
	left join organisation_contact on organisation_contact.contact_id = crm_notes_orgs.org_contact_id
	LEFT JOIN organisations ON organisations.id = crm_notes_orgs.organisation_id
	
where 
	organisation_id = $id
	AND organisations.id = $id
	AND  organisations.id = organisation_id
ORDER BY crm_notes_orgs.contact_date desc
HEREDOC;

			$view = $_SESSION[$key] = new ViewOrganisationCrmNotes();
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
	
	
	public function render(PDO $link, $type)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Date</th><th>Person contacted</th><th>Type of Contact</th><th>Subject</th><th>By whom</th><th> Position </th><th>Agreed Action</th><th>Audit Info</th></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//if(DB_NAME=="am_baltic" || DB_NAME=="ams")
				//	echo '<tr>';
				//else
					echo HTML::viewrow_opening_tag('do.php?_action=edit_crm_note&mode=edit&id=' . rawurlencode($row['id']) . '&organisations_id=' . rawurlencode($row['organisation_id']) . '&organisation_type=' . $type . '&person_contacted=' . rawurlencode($row['name_of_person']));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['contact_date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['name_of_person']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['by_whom']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['by_whom_position']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['agreed_action']) . "</td>";
                echo '<td align="left"><i>created by: ' . $row['created_by'] . ' on ' . Date::toShort($row['created_at']) . ' at ' . Date::to($row['created_at'], 'H:i:s') . "</td>";
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
	
	/**
	 * 
	 * Display the CRM notes in a more compact manner for Employer Engagement Section (RE)
	 * @param PDO $link
	 * @param unknown_type $type
	 * @throws Exception
	 */
	public function render_mini(PDO $link, $type)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			$row_style = '';
			$row_count = 0;
			while($row = $st->fetch())
			{
				if ( $row_count % 2 == 0 ) {
					$row_style = '';
				}
				else {
					$row_style = 'background-color: #E0EAD0;';
				}
				
				if ( $row_count == 0 ) {
					echo '<h3>Existing CRM notes for '.$row['legal_name'].'</h3>';
					echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="0"  >';
					echo '<tbody>';
				}
				
				echo '<tr style="'.$row_style.'" >';
				echo '<td style="border-top: 1px solid #999;" align="left"><strong>Date of contact:</strong><br/>'.HTML::cell($row['date'])."</td>";
				echo '<td style="border-top: 1px solid #999;" align="left"><strong>Contact Type:</strong><br/>'.HTML::cell($row['type_of_contact'])."</td>";				
				echo '<td style="border-top: 1px solid #999;" align="left"><strong>Contact By:</strong><br/>'.HTML::cell($row['by_whom'])."</td>";
				//echo '<td style="border-top: 1px solid #999; text-align:right;"><a class="submit" href="do.php?_action=edit_crm_note&person_contacted='.rawurldecode($row['name_of_person']).'&mode=edit&id='.rawurlencode($row['id']).'&organisations_id='.rawurlencode($row['organisation_id']).'&organisation_type='.$type.'">edit</a>&nbsp;</td>';
				if(DB_NAME == "am_baltic")
				{
					if($_SESSION['user']->isAdmin())
						echo '<td style="border-top: 1px solid #999; text-align:right;"><a class="submit" href="do.php?_action=edit_crm_note&person_contacted='.rawurldecode($row['name_of_person']).'&mode=edit&id='.rawurlencode($row['id']).'&organisations_id='.rawurlencode($row['organisation_id']).'&organisation_type='.$type.'">edit</a>&nbsp;</td>';
					else
						echo '<td></td>';
				}
				else
					echo '<td style="border-top: 1px solid #999; text-align:right;"><a class="submit" href="do.php?_action=edit_crm_note&person_contacted='.rawurldecode($row['name_of_person']).'&mode=edit&id='.rawurlencode($row['id']).'&organisations_id='.rawurlencode($row['organisation_id']).'&organisation_type='.$type.'">edit</a>&nbsp;</td>';
				echo '</tr><tr style="'.$row_style.'" >';
				echo '<td align="left"><strong>Contacted:</strong><br/>'.HTML::cell($row['name_of_person'])."</td>";
				echo '<td align="left"><strong>Contact Position:</strong><br/>'.HTML::cell($row['position'])."</td>";
				echo '<td align="left" colspan="2"><strong>Subject:</strong><br/>'.HTML::cell($row['subject'])."</td>";
				echo '</tr>';
				echo '<tr style="'.$row_style.'" >';
				echo '<td colspan="4"><strong>Comments/Actions:</strong><br/>';
				echo htmlspecialchars((string)$row['agreed_action']) . "</td>";
				echo '</tr>';
				$row_count++;
			}
			echo '</tbody></table></div align="left">';			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>