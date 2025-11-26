<?php
class ViewContractHolders extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.*, locations.address_line_2, locations.address_line_3
	FROM
		organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	WHERE organisations.organisation_type LIKE '%4%';
HEREDOC;

			$view = $_SESSION[$key] = new ViewContractHolders();
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

			$options = array(
				0=>array(1, 'Company name (asc)', null, 'ORDER BY legal_name'),
				1=>array(2, 'Company name (desc)', null, 'ORDER BY legal_name DESC'),
				2=>array(3, 'Location (asc), Provider name (asc)', null, 'ORDER BY address_line_3, address_line_2, legal_name'),
				3=>array(4, 'Location (desc), Provider name (desc)', null, 'ORDER BY address_line_3 DESC, address_line_2 DESC, legal_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
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
			echo $this->getViewNavigator();
			echo '<div class="table-responsive"><table id="tblContractHolders" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th><th>Legal Name</th><th>Town</th><th>Locality</th><th>Current Active Contracts</th></tr></thead>';
			$current_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=read_contractholder&id=' . $row['id']);
				echo '<td><span class="fa fa-bank"></span> </td>';
				echo '<td>' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td>' . HTML::cell($row['address_line_3']) . '</td>';
				echo '<td>' . HTML::cell($row['address_line_2']) . '</td>';
				$list = DAO::getSingleColumn($link, "SELECT contracts.title FROM contracts WHERE contracts.contract_holder = '{$row['id']}' AND active = '1' AND contract_year = '{$current_year}'");
				echo '<td>';
				foreach($list AS $l)
					echo $l . '<br>';
				echo '</td>';
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>