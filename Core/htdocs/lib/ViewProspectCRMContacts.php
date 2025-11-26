<?php
class ViewProspectCRMContacts extends View
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
		prospect_contact
	WHERE org_id = '$org_id';
HEREDOC;

			$view = $_SESSION[$key] = new ViewProspectCRMContacts();
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
		if($st)
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';
			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=edit_crm_contact&org_type=prospect&contact_id=' . $row['contact_id']);
				echo '<td><img src="/images/blue-building.png" border="0" /></td>';
				echo '<td align="left">' . HTML::cell($row['contact_title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_department']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_telephone']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_mobile']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_email']) . '</td>';
				echo '</tr>';
			}

			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator('left');

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>