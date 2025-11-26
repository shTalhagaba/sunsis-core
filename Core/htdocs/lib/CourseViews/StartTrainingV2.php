<?php
class StartTrainingV2 extends View
{
	public static function getInstance($course_id)
	{
		$key = 'view'.__CLASS__.$course_id;

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<SQL
SELECT
	users.surname,
	users.firstnames,
	users.username,
	users.employer_id,
	organisations.legal_name,
	DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(users.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(users.dob, '00-%m-%d')) AS age,
	locations.full_name,
	users.work_telephone,
	COUNT(tr.id) AS trs,
	users.gender,
	users.home_postcode, users.job_role
FROM
	users
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN tr ON tr.username = users.username
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE users.type = '5'
GROUP BY users.username
;
SQL;

			$sql = new SQLStatement($sql);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$sql->setClause("WHERE organisations.parent_org = '{$_SESSION['user']->employer_id}'");

			$view = $_SESSION[$key] = new StartTrainingV2();
			$view->setSQL($sql->__toString());

			$options = array(
				0=>array(1, 'Surname (asc), Firstname (asc)', null, 'ORDER BY surname, firstnames'),
				1=>array(2, 'Surname (desc), Firstname (desc)', null, 'ORDER BY surname DESC, firstnames DESC'),
				2=>array(3, 'Firstname (asc), Surname (asc)', null, 'ORDER BY firstnames, surname'),
				3=>array(4, 'Firstname (desc), Surname (desc)', null, 'ORDER BY firstnames DESC, surname DESC'),
				4=>array(5, 'Employer', null, 'ORDER BY legal_name'));
			$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%" order by legal_name';
			$f = new DropDownViewFilter('filter_organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE users.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname Starts With: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All learners', null, null),
				1=>array(2, 'Learners in training', null, ' where (select count(*) from tr where username = users.username and status_code=1) > 0'),
				2=>array(3, 'Never started training', null, ' where users.username not in (select username from tr)'),
				3=>array(4, 'Achievers', null, ' where (select count(*) from tr where username = users.username and status_code=2) > 0'),
				4=>array(5, 'Not in training', null, ' where (select count(*) from tr where username = users.username and status_code=1) = 0'));
			$f = new DropDownViewFilter('filter_learners_type', $options, 3, false);
			$f->setDescriptionFormat("Learners: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_learner_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$st = DAO::query($link, $this->getSQL());
		if($st)
		{
			echo '<div class="table-responsive"><table id="tblCourseGroups" class="table table-bordered table-hover">';
			echo '<thead><tr><th>&nbsp;</th>';
			echo '<th>Gender</th><th>Surname</th><th>Firstname</th><th>Age</th><th>Job Role</th><th>Organisation</th><th>Work Telephone</th><th>Home <br> Postcode</th><th>Training <br>Records</th>';
			echo '</thead><tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td align="center"><input class="chkEnrolLearnersSelection" type="checkbox" name="learnersToEnrol" onclick="learnersToEnrol_onclick(this);" value="' . $row['username'] . '" /></td>';
				if($row['gender'] == 'M')
					echo '<td align = center><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td align = center><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';

				echo '<td>' . HTML::cell($row['surname']) . "</td>";
				echo '<td>' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td>' . HTML::Cell($row['age']) . "</td>";
				echo '<td>' . HTML::cell($row['job_role']) . "</td>";
				echo '<td>' . HTML::cell($row['legal_name']) . '<br> &nbsp; <span class="small"><i class="fa fa-map-marker"></i> ' . HTML::cell($row['full_name']) . '</span>' . '</td>';
				echo '<td>' . HTML::cell($row['work_telephone']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['home_postcode']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['trs']) . '</td>';
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