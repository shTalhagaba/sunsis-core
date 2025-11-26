<?php
class ViewUsers extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

//        if
//        (
//            !$_SESSION['user']->isAdmin() &&
//            !$_SESSION['user']->isOrgAdmin() &&
//            $_SESSION['user']->type != User::TYPE_MANAGER &&
//            $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER &&
//            $_SESSION['user']->type != User::TYPE_SCHOOL_VIEWER &&
//            $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER
//        )
//        {
//            throw new UnauthenticatedException();
//        }

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("
SELECT DISTINCT
	users.id,
	users.username,
	users.surname,
	users.firstnames,
	users.job_role,
	lookup_user_types.description AS system_user_type,
	users.employer_id,
	organisations.legal_name AS organisation,
	lookup_org_type.`org_type` AS organisation_type,
	users.work_email,
	users.work_telephone,
	users.work_mobile,
	departments.`company_number` AS department_code,
	IF(users.`web_access` = 1, 'Enabled', 'Disabled') AS web_access

FROM
	users
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN lookup_user_types ON lookup_user_types.id = users.type
	LEFT JOIN lookup_org_type ON organisations.`organisation_type` = lookup_org_type.`id`
	LEFT JOIN locations AS departments_locations ON users.`department` = departments_locations.`id`
	LEFT JOIN organisations AS departments ON departments_locations.`organisations_id` = departments.`id`

			");

            $sql->setClause("WHERE users.type != '" . User::TYPE_LEARNER . "'");

            $employer_id = $_SESSION['user']->employer_id;

            if(
                ($_SESSION['user']->isOrgAdmin() ||
                    $_SESSION['user']->type == User::TYPE_MANAGER ||
                    $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER ||
                    $_SESSION['user']->type == User::TYPE_SCHOOL_VIEWER)
            )
                $sql->setClause("WHERE users.employer_id = '{$employer_id}' ");

		if(DB_NAME == "am_ela")
            {
                if($_SESSION['user']->learners_caseload == 0)
                {
                    // do nothing
                }
                elseif(in_array($_SESSION['user']->learners_caseload, [1, 2, 3]))
                {
                  $sql->setClause("WHERE users.learners_caseload = '{$_SESSION['user']->learners_caseload}' ");
                }
            }    

            $view = $_SESSION[$key] = new ViewUsers();
            $view->setSQL($sql->__toString());

            if($_SESSION['user']->type == User::TYPE_MANAGER)
                $options = <<<SQL
SELECT DISTINCT
  organisations.id,
  legal_name,
  (SELECT lookup_org_type.`org_type` FROM lookup_org_type WHERE id = organisations.`organisation_type`) AS org_type,
  CONCAT(
    "WHERE users.employer_id=",
    organisations.id
  )
FROM
  organisations
  INNER JOIN users
    ON organisations.id = users.employer_id
WHERE
  organisations.parent_org = '{$employer_id}'
  AND users.`type` != 5
ORDER BY legal_name
;
SQL;
            else
                $options = 'SELECT DISTINCT
  organisations.id,
  legal_name,
  (SELECT lookup_org_type.`org_type` FROM lookup_org_type WHERE id = organisations.`organisation_type`) AS org_type,
  CONCAT(
    "WHERE users.employer_id=",
    organisations.id
  )
FROM
  organisations
  INNER JOIN users
    ON organisations.id = users.employer_id
WHERE users.`type` != 5
ORDER BY legal_name';
            $f = new DropDownViewFilter('organisation', $options, null, true);
            $f->setDescriptionFormat("Organisation: %s");
            $view->addFilter($f);

            $learner_type = User::TYPE_LEARNER;
            $options = <<<OPTIONS
SELECT DISTINCT
  lookup_user_types.id,
  lookup_user_types.description,
  NULL,
  CONCAT(
    "WHERE users.type=",
    lookup_user_types.id
  )
FROM
  lookup_user_types
  INNER JOIN users ON lookup_user_types.`id` = users.`type`
WHERE lookup_user_types.id != '$learner_type'
ORDER BY lookup_user_types.description;
OPTIONS;
            $f = new DropDownViewFilter('filter_user_type', $options, null, true);
            $f->setDescriptionFormat("System User Type: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'User (asc)', null, 'ORDER BY users.surname, users.firstnames'),
                1=>array(2, 'User (desc)', null, 'ORDER BY users.surname DESC, users.firstnames DESC'),
                2=>array(3, 'Organisation (asc), User (asc)', null, 'ORDER BY organisations.legal_name, users.surname, users.firstnames'),
                3=>array(4, 'Organisation (desc), User (desc)', null, 'ORDER BY organisations.legal_name DESC, users.surname DESC, users.firstnames DESC'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE users.surname LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Surname contains: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Disabled', null, 'WHERE users.web_access = 0'),
                1=>array(1, 'Enabled', null, 'WHERE users.web_access = 1')
            );
            $f = new DropDownViewFilter('filter_web_access', $options, null, true);
            $f->setDescriptionFormat("Web Access: %s");
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
					<tr>
						<th class="bottomRow">Username</th>
						<th class="bottomRow">Surname</th>
						<th class="bottomRow">Firstnames</th>
						<th class="bottomRow">Job Role</th>
						<th class="bottomRow">System User Type</th>
						<th class="bottomRow">Organisation</th>
						<th class="bottomRow">Organisation Type</th>
						<th class="bottomRow">Work Email</th>
						<th class="bottomRow">Work Telephone</th>
						<th class="bottomRow">Work Mobile</th>
						<th class="bottomRow">Department Code</th>
						<th class="bottomRow">Web Access</th>
					</tr>
					</thead>
HEREDOC;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_user&id=' . $row['id']);
                echo '<td><code>' . HTML::cell($row['username']) . '</code></td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['job_role']) . '</td>';
                echo '<td>' . HTML::cell($row['system_user_type']) . '</td>';
                echo '<td>' . HTML::cell($row['organisation']) . '</td>';
                echo '<td>' . HTML::cell($row['organisation_type']) . '</td>';
                echo '<td>' . HTML::cell($row['work_email']) . '</td>';
                echo '<td>' . HTML::cell($row['work_telephone']) . '</td>';
                echo '<td>' . HTML::cell($row['work_mobile']) . '</td>';
                echo '<td>' . HTML::cell($row['department_code']) . '</td>';
                echo '<td>' . HTML::cell($row['web_access']) . '</td>';

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