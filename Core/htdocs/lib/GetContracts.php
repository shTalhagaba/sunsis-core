<?php
class GetContracts extends View
{

	public static function getInstance()
	{
		$key = 'view'.__CLASS__;
		
		if(true)
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	CASE contracts.funded WHEN '1' THEN 'Yes' WHEN '2' THEN 'No' ELSE 'Not Set' END AS 'funded_contract',
	contracts.*, organisations.legal_name,
	(select count(distinct tr_id) from ilr where ilr.contract_id = contracts.id) as ilrs
FROM
	contracts
	LEFT JOIN organisations on organisations.id = contracts.contract_holder
WHERE
    contracts.active = 1
HEREDOC;

			if($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)
			{
				$emp_id = $_SESSION['user']->employer_id;

				$sql = <<<HEREDOC

SELECT DISTINCT
	CASE contracts.funded WHEN '1' THEN 'Yes' WHEN '2' THEN 'No' ELSE 'Not Set' END AS 'funded_contract',
	contracts.*, organisations.legal_name,
	(select count(distinct tr_id) from ilr where ilr.contract_id = contracts.id) as ilrs
FROM
	contracts
	LEFT JOIN organisations on organisations.id = contracts.contract_holder
	LEFT JOIN tr ON contracts.id = tr.`contract_id`
WHERE
    contracts.active = 1
    AND tr.provider_id = '$emp_id'

HEREDOC;

			}
			
			$view = $_SESSION[$key] = new GetContracts();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Contract Year (desc), title (asc)', null, 'ORDER BY contract_year desc, title asc'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_type DESC, level DESC'));
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
			echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr>
						<th class="bottomRow text-center"><input id="global" type="checkbox" title = "top" onclick="checkAll(this);" /></a></th>
						<th class="bottomRow">Contract Title</th>
						<th class="bottomRow">Contact Holder</th>
						<th class="bottomRow">Contract Year</th>
						<th class="bottomRow">ILRs</th>
						<th class="bottomRow">Funded Contract</th>
					</tr>
					</thead>
HEREDOC;
			echo '<tbody>';
			$counter = 1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr title="' . $row['contract_year'] .  '">';
				echo '<td class="text-center"><input id="button'.$counter++.'" type="checkbox"  onclick="evidenceradio_onclick(this)" title="' . $row['contract_year'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				echo '<td>' . $row['title'] . '</td>';
				echo '<td>' . $row['legal_name'] . '</td>';
				echo '<td align=center>' . $row['contract_year'] . '</td>';
				echo '<td align=center>' . $row['ilrs'] . '</td>';
				if($row['funded_contract'] == 'Yes')
				{
					echo '<td class="bg-green text-center">' . $row['funded_contract'] . '</td>';
				}
				else
				{
					echo '<td class="bg-red text-center">' . $row['funded_contract'] . '</td>';
				}
				echo '</tr>';

			}
			echo '</tbody></table></div>';

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>