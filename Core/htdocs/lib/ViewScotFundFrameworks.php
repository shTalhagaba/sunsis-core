<?php
class ViewScotFundFrameworks extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_' . __CLASS__;

		$funding_stream = Framework::FUNDING_STREAM_SCOTTISH;

		if (!isset($_SESSION[$key])) {
			$sql = <<<SQL

			SELECT DISTINCT
				frameworks.*
			FROM
				frameworks
			WHERE
				frameworks.funding_stream = $funding_stream

SQL;

			// Create new view object

			$view = $_SESSION[$key] = new ViewScotFundFrameworks();
			$view->setSQL($sql);

			// Title Filter
			$f = new TextboxViewFilter('filter_title', "WHERE frameworks.title LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			// Add view filters
			$options = array(
				0 => array(20, 20, null, null),
				1 => array(50, 50, null, null),
				2 => array(100, 100, null, null),
				3 => array(0, 'No limit', null, null)
			);
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0 => array(1, 'Framework Title (asc)', null, 'ORDER BY title'),
				1 => array(2, 'Framework Title (desc)', null, 'ORDER BY title DESC')
			);
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0 => array(1, 'All Frameworks', null, null),
				1 => array(2, 'Active Frameworks', null, 'where  active=1'),
				2 => array(3, 'Inactive Frameworks', null, 'where active<>1')
			);
			$f = new DropDownViewFilter('by_active', $options, 2, false);
			$f->setDescriptionFormat("Active: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$framework_with_max_milestones = 0;
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		$link->exec("SET NAMES utf8mb4");
		if ($st) {
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

			$rows = $st->fetchAll();
			foreach ($rows as $header_row) {
				if ($header_row['milestones'] > $framework_with_max_milestones)
					$framework_with_max_milestones = $header_row['milestones'];
			}

			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Code</th><th>Duration</th><th>Milestones</th><th>Age Category</th><th>Start Payment</th>';
			for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
				echo '<th>Milestone ' . $i . '</th>';
			}
			echo '<th>Outcome Payment</th><th>Total</th></tr></thead>';

			echo '<tbody>';
			foreach ($rows as $row) {
				$row_total = 0;
				$saved_records = array();
				echo '<tr>';
				echo '<td rowspan="3"><img src="/images/rosette.gif" /></td>';
				echo '<td rowspan="3" align="left"><a href="do.php?_action=view_framework_qualifications&id=' . rawurlencode($row['id']) . '">' . HTML::cell($row['title']) . '</a></td>';
				echo '<td rowspan="3" align="center">' . HTML::cell($row['framework_code']) . "</td>";
				echo '<td rowspan="3" align="center">' . HTML::cell($row['duration_in_months'] . " months") . "</td>";
				echo '<td rowspan="3" align="center">' . HTML::cell(htmlspecialchars((string)$row['milestones'])) . "</td>";

				$result_set = DAO::getResultset($link, "SELECT description, amount FROM fwrk_scottish_funding WHERE fwrk_id = " . $row['id'], DAO::FETCH_ASSOC);
				foreach ($result_set as $record) {
					$saved_records[$record['description']] = $record['amount'];
				}

				$var_sp = isset($saved_records['16_19_SP']) ? $saved_records['16_19_SP'] : ' - ';
				echo '<td align="center">' . HTML::cell('16 - 19') . "</td>";
				echo '<td align="center">' . HTML::cell($var_sp) . "</td>";

				$row_total += is_numeric($var_sp) ? (float)$var_sp : 0;
				for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
					$var_mp = isset($saved_records['16_19_MP_' . $i]) ? $saved_records['16_19_MP_' . $i] : ' - ';
					echo '<td align="center">' . HTML::cell($var_mp) . "</td>";
					$row_total += $var_mp;
				}
				$var_op = isset($saved_records['16_19_OP']) ? $saved_records['16_19_OP'] : ' - ';
				echo '<td align="center">' . HTML::cell($var_op) . "</td>";
				$row_total += $var_op;
				echo '<td align="center"><strong>' . HTML::cell('£ ' . number_format($row_total, '2', '.', '')) . "</strong></td>";
				echo '</tr>';

				$row_total = 0;
				echo '<tr bgcolor="#f0f8ff">';

				$var_sp = isset($saved_records['20_24_SP']) ? $saved_records['20_24_SP'] : ' - ';
				echo '<td align="center">' . HTML::cell('20 - 24') . "</td>";
				echo '<td align="center">' . HTML::cell($var_sp) . "</td>";
				$row_total += is_numeric($var_sp) ? (float)$var_sp : 0;

				for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
					$var_mp = isset($saved_records['20_24_MP_' . $i]) ? $saved_records['20_24_MP_' . $i] : ' - ';
					echo '<td align="center">' . HTML::cell($var_mp) . "</td>";
					$row_total += $var_mp;
				}
				$var_op = isset($saved_records['20_24_OP']) ? $saved_records['20_24_OP'] : ' - ';
				echo '<td align="center">' . HTML::cell($var_op) . "</td>";
				$row_total += $var_op;
				echo '<td align="center"><strong>' . HTML::cell('£ ' . number_format($row_total, '2', '.', '')) . "</strong></td>";
				echo '</tr>';

				$row_total = 0;
				echo '<tr bgcolor="#dcdcdc">';

				$var_sp = isset($saved_records['25_Plus_SP']) ? $saved_records['25_Plus_SP'] : ' - ';
				echo '<td align="center">' . HTML::cell('25 Plus') . "</td>";
				echo '<td align="center">' . HTML::cell($var_sp) . "</td>";

				$row_total += is_numeric($var_sp) ? (float)$var_sp : 0;

				for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
					$var_mp = isset($saved_records['25_Plus_MP_' . $i]) ? $saved_records['25_Plus_MP_' . $i] : ' - ';
					echo '<td align="center">' . HTML::cell($var_mp) . "</td>";
					$row_total += $var_mp;
				}
				$var_op = isset($saved_records['25_Plus_OP']) ? $saved_records['25_Plus_OP'] : ' - ';
				echo '<td align="center">' . HTML::cell($var_op) . "</td>";
				$row_total += $var_op;
				echo '<td align="center"><strong>' . HTML::cell('£ ' . number_format($row_total, '2', '.', '')) . "</strong></td>";

				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
		} else {
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	public function exportToCSV(PDO $link, $columns = '', $extra = '', $key = '', $where = '')
	{
		$line = '';
		$framework_with_max_milestones = 0;
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if ($st) {
			$rows = $st->fetchAll();
			foreach ($rows as $header_row) {
				if ($header_row['milestones'] > $framework_with_max_milestones)
					$framework_with_max_milestones = $header_row['milestones'];
			}

			$line .= 'Title,Code,Duration,Milestones,Age Category,Start Payment,';
			for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
				$line .= 'Milestone ' . $i . ',';
			}
			$line .= 'Outcome Payment,Total';
			echo $line . "\r\n";


			foreach ($rows as $row) {
				$line = "";
				$row_total = 0;
				$saved_records = array();
				$line .= '"' .   ($row['title']) . '",';
				$line .= '"' .    ($row['framework_code']) . '",';
				$line .= '"' .    ($row['duration_in_months'] . " months") . '",';
				$line .= '"' .    (htmlspecialchars((string)$row['milestones'])) . '",';

				$result_set = DAO::getResultset($link, "SELECT description, amount FROM fwrk_scottish_funding WHERE fwrk_id = " . $row['id'], DAO::FETCH_ASSOC);
				foreach ($result_set as $record) {
					$saved_records[$record['description']] = $record['amount'];
				}

				$var_sp = isset($saved_records['16_19_SP']) ? $saved_records['16_19_SP'] : ' - ';
				$line .= '"' .    ('16 - 19') . '",';
				$line .= '"' .    ($var_sp) . '",';

				$row_total += is_numeric($var_sp) ? (float)$var_sp : 0;
				for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
					$var_mp = isset($saved_records['16_19_MP_' . $i]) ? $saved_records['16_19_MP_' . $i] : ' - ';
					$line .= '"' .    ($var_mp) . '",';
					$row_total += $var_mp;
				}
				$var_op = isset($saved_records['16_19_OP']) ? $saved_records['16_19_OP'] : ' - ';
				$line .= '"' .    ($var_op) . '",';
				$row_total += $var_op;
				$line .= '"' .    ('£ ' . number_format($row_total, '2', '.', '')) . '",';
				echo $line . "\r\n";
				$line = '';

				$row_total = 0;

				$line .= '"' .    ($row['title']) . '",';
				$line .= '"' .    ($row['framework_code']) . '",';
				$line .= '"' .    ($row['duration_in_months'] . " months") . '",';
				$line .= '"' .    (htmlspecialchars((string)$row['milestones'])) . '",';

				$var_sp = isset($saved_records['20_24_SP']) ? $saved_records['20_24_SP'] : ' - ';
				$line .= '"' .    ('20 - 24') . '",';
				$line .= '"' .    ($var_sp) . '",';
				$row_total += is_numeric($var_sp) ? (float)$var_sp : 0;

				for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
					$var_mp = isset($saved_records['20_24_MP_' . $i]) ? $saved_records['20_24_MP_' . $i] : ' - ';
					$line .= '"' .    ($var_mp) . '",';
					$row_total += $var_mp;
				}
				$var_op = isset($saved_records['20_24_OP']) ? $saved_records['20_24_OP'] : ' - ';
				$line .= '"' .    ($var_op) . '",';
				$row_total += $var_op;
				$line .= '"' .   ('£ ' . number_format($row_total, '2', '.', '')) . '",';
				echo $line . "\r\n";
				$line = '';


				$row_total = 0;

				$line .= '"' .    ($row['title']) . '",';
				$line .= '"' .    ($row['framework_code']) . '",';
				$line .= '"' .    ($row['duration_in_months'] . " months") . '",';
				$line .= '"' .    (htmlspecialchars((string)$row['milestones'])) . '",';

				$var_sp = isset($saved_records['25_Plus_SP']) ? $saved_records['25_Plus_SP'] : ' - ';
				$line .= '"' .    ('25 Plus') . '",';
				$line .= '"' .    ($var_sp) . '",';
				$row_total += is_numeric($var_sp) ? (float)$var_sp : 0;

				for ($i = 1; $i <= $framework_with_max_milestones; $i++) {
					$var_mp = isset($saved_records['25_Plus_MP_' . $i]) ? $saved_records['25_Plus_MP_' . $i] : ' - ';
					$line .= '"' .    ($var_mp) . '",';
					$row_total += $var_mp;
				}
				$var_op = isset($saved_records['25_Plus_OP']) ? $saved_records['25_Plus_OP'] : ' - ';
				$line .= '"' .    ($var_op) . '",';
				$row_total += $var_op;
				$line .= '"' .   ('£ ' . number_format($row_total, '2', '.', '')) . '",';

				echo $line . "\r\n";
			}
		} else {
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}