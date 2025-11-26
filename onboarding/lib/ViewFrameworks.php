<?php
class ViewFrameworks extends View
{
	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
SELECT
	frameworks.id,
	frameworks.title AS title,
	(SELECT ProgTypeDesc FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType=frameworks.`framework_type`) AS `type`,
	IF(
		frameworks.`framework_type` = 25, 
		(SELECT LEFT(CONCAT(StandardCode, ' ' , StandardName),40) FROM lars201718.Core_LARS_Standard WHERE lars201718.Core_LARS_Standard.`StandardCode` = frameworks.`StandardCode` LIMIT 1), 
		(SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = frameworks.framework_code LIMIT 1)
	) AS `code`,
	frameworks.duration_in_months AS duration,
	(SELECT COUNT(*) FROM framework_qualifications WHERE framework_qualifications.`framework_id` = frameworks.id) AS no_of_qualifications,
	frameworks.epa_price
	
FROM
	frameworks
			");

			$sql->setClause("GROUP BY frameworks.id");

			if($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)
			{
				$sql->setClause("WHERE frameworks.parent_org = '{$_SESSION['user']->employer_id}'");
			}

			$view = $_SESSION[$key] = new ViewFrameworks();
			$view->setSQL($sql->__toString());

			$f = new TextboxViewFilter('filter_title', "WHERE frameworks.title LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Framework Title: %s");
			$view->addFilter($f);

			$options = "SELECT ProgType, LEFT(CONCAT(ProgType, ' ' , ProgTypeDesc),40), NULL, CONCAT('WHERE frameworks.framework_type=',ProgType) FROM lars201718.CoreReference_LARS_ProgType_Lookup;";
			$f = new DropDownViewFilter('filter_framework_type', $options, null, true);
			$f->setDescriptionFormat("Framework Type: %s");
			$view->addFilter($f);

			$options = array(
				0 => array(1, 'All', null, null),
				1 => array(2, 'Active', null, 'WHERE  frameworks.active=1'),
				2 => array(3, 'Inactive', null, 'WHERE frameworks.active<>1'));
			$f = new DropDownViewFilter('by_active', $options, 2, false);
			$f->setDescriptionFormat("Active: %s");
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

			$options = array(
				0=>array(1, 'Framework Title (asc)', null, 'ORDER BY title'),
				1=>array(2, 'Framework Title (desc)', null, 'ORDER BY title DESC'));
			$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = DAO::query($link, $this->getSQL());
		if ($st) {
			echo $this->getViewNavigator();
			echo '';
			echo '';
			echo <<<HEREDOC
			<div align="center" class="table-responsive">
				<table class="table resultset">
					<thead>
					<tr>
						<th class="bottomRow"></th>
						<th class="bottomRow">Title</th>
						<th class="bottomRow">Type</th>
						<th class="bottomRow">Duration</th>
						<th class="bottomRow">No Of Qualifications</th>
						<th class="bottomRow">EPA Price (TNP2)</th>
					</tr>
					</thead>
HEREDOC;

			echo '<tbody>';

			while ($row = $st->fetch())
			{

				echo HTML::viewrow_opening_tag('do.php?_action=view_framework_qualifications&id=' . rawurlencode($row['id']));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['duration'] . " months") . "</td>";
				echo '<td align="center">' . HTML::cell($row['no_of_qualifications']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['epa_price']) . "</td>";
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