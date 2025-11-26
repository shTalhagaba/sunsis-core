<?php
class ViewContracts extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__.'V2';

		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
SELECT
	contracts.*, organisations.legal_name AS contract_holder,
	lookup_contract_locations.description AS contract_location,
	lookup_contract_types.contract_type AS funded,
	(SELECT COUNT(DISTINCT id) FROM tr WHERE contract_id = contracts.id) AS training_records,
	(SELECT COUNT(DISTINCT id) FROM tr WHERE contract_id = contracts.id AND tr.status_code = 1) AS continuing,
	(SELECT COUNT(DISTINCT id) FROM tr WHERE contract_id = contracts.id AND tr.status_code = 2) AS completed,
	(SELECT COUNT(DISTINCT id) FROM tr WHERE contract_id = contracts.id AND tr.status_code = 3) AS withdrawn,
	IF(contracts.funding_type = 1,'Included','Excluded') AS success_rates
FROM
	contracts
	LEFT JOIN organisations ON contracts.contract_holder = organisations.id
	LEFT JOIN lookup_contract_types ON lookup_contract_types.id = contracts.funded
	LEFT JOIN lookup_contract_locations ON lookup_contract_locations.id = contracts.contract_location
ORDER BY
	contracts.title
			");

			if(($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER) && DB_NAME != "am_lead")
				$sql->setClause("WHERE contracts.title LIKE '%{$_SESSION['user']->org->legal_name}%' ");

			$view = $_SESSION[$key] = new ViewContracts();
			$view->setSQL($sql->__toString());

			$f = new TextboxViewFilter('filter_title', "WHERE contracts.title LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Title: %s");
			$view->addFilter($f);

			$options = 'SELECT id, legal_name, null, CONCAT("WHERE contracts.contract_holder=",id) FROM organisations WHERE organisation_type = ' . Organisation::TYPE_CONTRACT_HOLDER . ' ORDER BY legal_name';
			$f = new DropDownViewFilter('filter_contract_holder', $options, null, true);
			$f->setDescriptionFormat("Contract Holder: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, date('Y'), true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All Contracts', null, null),
				1=>array(2, 'Active Contracts', null, 'WHERE  contracts.active = 1'),
				2=>array(3, 'Inactive Contracts', null, 'WHERE contracts.active <> 1'));
			$f = new DropDownViewFilter('filter_active', $options, 2, false);
			$f->setDescriptionFormat("Active: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All Contracts', null, null),
				1=>array(2, 'Funded Contracts', null, 'WHERE  contracts.funded = 1'),
				2=>array(3, 'Unfunded Contracts', null, 'WHERE contracts.funded <> 1'));
			$f = new DropDownViewFilter('filter_funded', $options, 1, false);
			$f->setDescriptionFormat("Funded: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Contract Year (descending), Title', null, 'ORDER BY contract_year DESC, title'),
				1=>array(2, 'Contract Year (ascending), Title', null, 'ORDER BY contract_year, title'));
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


	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator() . '<br>';

			echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr><th class="topRow" colspan="7">Contract</th><th colspan="5">Training Records</th></tr>
					<tr>
						<th class="bottomRow">Title</th>
						<th class="bottomRow">Holder</th>
						<th class="bottomRow">Proportion</th>
						<th class="bottomRow">Year</th>
						<th class="bottomRow">Location</th>
						<th class="bottomRow">Funded</th>
						<th class="bottomRow">Success Rates</th>
						<th class="bottomRow">Total</th>
						<th class="bottomRow">Continuing</th>
						<th class="bottomRow">Completed</th>
						<th class="bottomRow">Withdrawn</th>
					</tr>
					</thead>
HEREDOC;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo $_SESSION['user']->isAdmin() ? HTML::viewrow_opening_tag('do.php?_action=read_contract&id=' . $row['id']) : '<tr>';
				echo '<td>' . HTML::cell($row['title']) . '</td>';
				echo '<td>' . HTML::cell($row['contract_holder']) . '</td>';
				echo '<td class="text-center">' . HTML::cell($row['proportion']) . '</td>';
				$year = $row['contract_year'].'-'.str_pad((substr($row['contract_year'],2,2)+1),2,'0',0);
				echo '<td>' . HTML::cell($year)  . '<p><i class="text-muted">'.Date::toShort($row['start_date']).'</i> - <i class="text-muted">' . Date::toShort($row['end_date']) . '</i></p></td>';
				echo '<td>' . HTML::cell($row['contract_location']) . '</td>';
				echo $row['funded'] == 'Funded'?'<td class="bg-green text-center">' . HTML::cell($row['funded']) . '</td>':'<td class="bg-info text-center">' . HTML::cell($row['funded']) . '</td>';
				if($row['funding_type'] == '1')
					echo '<td style="color: green">' . HTML::cell('Included') . '</td>';
				else
					echo '<td style="color: red">' . HTML::cell('Excluded') . '</td>';
				if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
					$link_tr = "do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_contract%5B%5D={$row['id']}&ViewTrainingRecordsV2_filter_record_status%5B%5D=";
				else
					$link_tr = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_contract={$row['id']}&ViewTrainingRecords_filter_record_status%5B%5D=";
				echo '<td align="center"'.($row['training_records'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'SHOW_ALL">'.$row['training_records'].'</a></td>';
				echo '<td align="center"'.($row['continuing'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'1">'.$row['continuing'].'</td>';
				echo '<td align="center"'.($row['completed'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'2">'.$row['completed'].'</td>';
				echo '<td align="center"'.($row['withdrawn'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'3">'.$row['withdrawn'].'</td>';

				echo '</tr>';
			}

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