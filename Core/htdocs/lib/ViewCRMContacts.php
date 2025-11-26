<?php
class ViewCRMContacts extends View
{

	public static function getInstance(PDO $link, $org_id)
	{
		$key = 'view_'.__CLASS__;

		//if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		*
	FROM
		organisation_contact
	WHERE org_id = '$org_id';
HEREDOC;

			$view = $_SESSION[$key] = new ViewCRMContacts();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
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
		if(SystemConfig::getEntityValue($link, "module_tracking"))
		{
			if($st)
			{
				echo $this->getViewNavigator('left');
				echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
				echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th><th>Actions</th></tr></thead>';

				echo '<tbody>';
				while($row = $st->fetch())
				{
					echo '<tr>';
					echo '<td><img src="/images/blue-person.gif" /></td>';
					echo '<td align="left">' . HTML::cell($row['contact_title']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_name']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_department']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_telephone']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_mobile']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_email']) . '</td>';
					echo '<td align="center">';
					echo '<p><span class="button" onclick="window.location.href=\'do.php?_action=edit_crm_contact&org_type=employer&contact_id=' . $row['contact_id'].'\';">&nbsp;&nbsp;Edit&nbsp;&nbsp;</span></p>';
                    echo '<p><span class="button" onclick="window.location.href=\'do.php?_action=edit_crm_holidays&org_type=employer&contact_id=' . $row['contact_id'].'\';">&nbsp;&nbsp;Holidays&nbsp;&nbsp;</span></p>';
					$linked_trs = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.crm_contact_id = '" . $row['contact_id'] . "'");
					echo $linked_trs > 0 ? '' : '<p><span class="button" onclick="deleteOrganisationCRMContact(\'' . $row['contact_id'] . '\');">Delete</span></p>';
					echo '</td>';
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
		else
		{
			if($st)
			{
				echo $this->getViewNavigator('left');
				echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
				echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr></thead>';

				echo '<tbody>';
				while($row = $st->fetch())
				{
					if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
					{
						$linked_trs = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.crm_contact_id = '" . $row['contact_id'] . "'");
						if($linked_trs > 0)
						{
							echo '<tr><td rowspan = "2">Review forms attached</td></tr>';
						}
						else
						{
							echo '<tr><td rowspan = "2" align="center"><span class="button" onclick="deleteOrganisationCRMContact(\'' . $row['contact_id'] . '\');">Delete</span></td></tr>';
						}
					}

					echo HTML::viewrow_opening_tag('/do.php?_action=edit_crm_contact&org_type=employer&contact_id=' . $row['contact_id']);
					if(DB_NAME != "am_baltic" && DB_NAME != "am_baltic_demo")
						echo '<td><img src="/images/blue-person.gif" /></td>';
					echo '<td align="left">' . HTML::cell($row['contact_title']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_name']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_department']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_telephone']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_mobile']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contact_email']) . '</td>';
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
}
?>