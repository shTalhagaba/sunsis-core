<?php
class sa_crontab_log extends ActionController
{

	public function indexAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_LOCAL && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		$view = $this->buildView($link);
		$view->refresh($link, $_REQUEST);

		include('smartassessor/crontab/tpl_log.php');
	}


	public function buildView(PDO $link)
	{
		$key = "view_sa_crontab_log";
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		$sql = <<<SQL
SELECT
	crontab_log.*,
	crontab.task
FROM
	crontab_log INNER JOIN crontab
		ON crontab_log.crontab_id = crontab.id
WHERE
	crontab.task IN ('SynchroniseLearners', 'SynchroniseEmployers', 'SynchroniseAssessors', 'SynchroniseReviews','SynchroniseProgresstrack','SynchroniseLearnerQualification','SynchroniseLearnerAssessors','SynchroniseLearnerIV','SynchroniseLearnersSurveyLink');
SQL;

		$view = new View();
		$view->setSQL($sql);

		// Add view filters
		$d = new DateTime("now");
		$f = new DateRangeViewFilter("filter_date", "crontab_log.timestamp", $d->format("d/m/Y"), $d->format("d/m/Y"), true);
		$f->setDescriptionFormat("Date: %s");
		$view->addFilter($f);

		$options = array(
			array('0', 'EMERG', null, 'WHERE crontab_log.priority <= 0'),
			array('1', 'ALERT', null, 'WHERE crontab_log.priority <= 1'),
			array('2', 'CRIT', null, 'WHERE crontab_log.priority <= 2'),
			array('3', 'ERR', null, 'WHERE crontab_log.priority <= 3'),
			array('4', 'WARN', null, 'WHERE crontab_log.priority <= 4'),
			array('5', 'NOTICE', null, 'WHERE crontab_log.priority <= 5'),
			array('6', 'INFO', null, 'WHERE crontab_log.priority <= 6'),
			array('7', 'DEBUG', null, 'WHERE crontab_log.priority <= 7'),
		);
		$f = new DropDownViewFilter('filter_priority', $options, 6, true);
		$f->setDescriptionFormat("Priority: %s");
		$view->addFilter($f);

		$options = array(
			array(50,50,null,null),
			array(100,100,null,null),
			array(0,'No limit',null,null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 50, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		$_SESSION[$key] = $view;
		return $view;
	}

	private function _renderView(PDO $link, View $view)
	{
		$sql = $view->getSQL();
		$st = DAO::query($link, $sql);

		echo '<table class="resultset" cellpadding="4" cellspacing="0" style="width:800px; table-layout: fixed; word-wrap: break-word">';
		echo '<col width="150"/>';
		echo '<col width="150"/>';
		echo '<col width="80"/>';
		echo '<col width=""/>';

		echo '<tr><th>Timestamp</th><th>Task</th><th>Priority</th><th>Message</th></tr>';

		while($row = $st->fetch(PDO::FETCH_ASSOC)) {
			echo '<tr class="' . $row['priority_name'] . '">';
			echo '<td valign="top">', Date::to($row['timestamp'], Date::DATETIME), '</td>';
			echo '<td valign="top">', htmlspecialchars((string)$row['task']), '</td>';
			echo '<td valign="top">', htmlspecialchars((string)$row['priority_name']), '</td>';
			echo '<td valing="top">', htmlspecialchars(substr($row['message'], 0, 300)), '</td>';
			echo '</tr>';
		}
	}
}