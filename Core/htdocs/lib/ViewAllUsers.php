<?php
class ViewAllUsers extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{
				$where = '';
			}
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==2)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.tutor = '. '"' . $id . '" or groups.old_tutor="' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.verifier = '. '"' . $id . '" or tr.verifier="' . $id . '")';
			}
			elseif($_SESSION['user']->type==1)
			{
				$provider_id = $_SESSION['user']->employer_id;
				$where = ' where (tr.provider_id = '. '"' . $provider_id . '")';
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$org_id = $_SESSION['user']->employer_id;
				$username = $_SESSION['user']->username;
				$where = " where (tr.provider_id = '$org_id' or users.employer_id='$org_id' or users.who_created = '$username' or users.who_created in (select username from users where type = 8 and employer_id = '$org_id'))" ;
			}
			elseif($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (tr.programme="' . $id . '")';
			}
			else
				throw new Exception("Not authorized");

			$sql = <<<HEREDOC
SELECT DISTINCT
	users.surname,
	users.firstnames,
	users.username,
	users.job_role,
	lookup_people_type.people_type AS user_type,
	users.employer_id,
	organisations.legal_name AS organisation,
	locations.full_name AS location,
	users.work_telephone,
	users.work_email,
	users.gender,
	users.enrollment_no,
	users.dob,
	users.home_address_line_1 AS address_line_1,
	users.home_address_line_2 AS address_line_2,
	users.home_address_line_3 AS address_line_3,
	users.home_address_line_4 AS address_line_4,
	users.home_postcode,
	IF(users.`web_access` = 1, 'Enabled', 'Disabled') AS web_access,
	providers.legal_name AS provider

FROM
	users
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN lookup_people_type on lookup_people_type.id = users.type
	LEFT JOIN tr on tr.username = users.username
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN groups on groups.courses_id = courses.id and group_members.groups_id = groups.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
ORDER BY users.surname
$where
HEREDOC;

			$view = $_SESSION[$key] = new ViewAllUsers();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);


			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT DISTINCT organisations.id, legal_name, null, CONCAT("WHERE users.employer_id=",organisations.id) FROM organisations INNER JOIN users ON organisations.id = users.employer_id WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = 'SELECT DISTINCT organisations.id, legal_name, null, CONCAT("WHERE users.employer_id=",organisations.id) FROM organisations INNER JOIN users ON organisations.id = users.employer_id WHERE organisation_type LIKE "%2%" OR organisation_type LIKE "%6%" OR organisation_type LIKE "%7%" ORDER BY legal_name';
			$f = new DropDownViewFilter('organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			//user type filter
			$options = 'SELECT DISTINCT lookup_user_types.id, lookup_user_types.description, null, CONCAT("WHERE users.type=",lookup_user_types.id) FROM lookup_user_types INNER JOIN users ON lookup_user_types.id = users.type ORDER BY description ';
			$f = new DropDownViewFilter('filter_user_type', $options, null, true);
			$f->setDescriptionFormat("System User Type: %s");
			$view->addFilter($f);

			//not user type filter
			$options = 'SELECT id, description, null, CONCAT("WHERE users.type!=",id) FROM lookup_user_types ORDER BY description ';
			$f = new DropDownViewFilter('filter_not_user_type', $options, null, true);
			$f->setDescriptionFormat("System User Type Not In: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'User (asc)', null, 'ORDER BY users.surname, users.firstnames'),
				1=>array(2, 'User (desc)', null, 'ORDER BY users.surname DESC, users.firstnames DESC'),
				2=>array(3, 'Employer (asc), User (asc)', null, 'ORDER BY organisations.legal_name, users.surname, users.firstnames'),
				3=>array(4, 'Employer (desc), User (desc)', null, 'ORDER BY organisations.legal_name DESC, users.surname DESC, users.firstnames DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			// Dealer Name Filter
			$f = new TextboxViewFilter('filter_surname', "WHERE users.surname LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			// Dealer Name Filter
			$f = new TextboxViewFilter('filter_surname2', "WHERE users.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname Starts With: %s");
			$view->addFilter($f);

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstname', "WHERE users.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_member', "WHERE users.enrollment_no LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Memeber No: %s");
			$view->addFilter($f);

			// Date of Birth Filter
			$format = "WHERE users.dob = '%s'";
			$f = new DateViewFilter('filter_dob', $format, '');
			$f->setDescriptionFormat("Date Of Birth: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE users.l45 LIKE '%s%%'", null);
			$f->setDescriptionFormat("ULN: %s");
			$view->addFilter($f);

			// National Insurance Filter
			$f = new TextboxViewFilter('filter_nationalinsurance', "WHERE users.ni LIKE '%s%%'", null);
			$f->setDescriptionFormat("National Insurance: %s");
			$view->addFilter($f);

			// L03 Filter
			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			// we access filter
			$options = array(
				0=>array(0, 'Disabled', null, 'WHERE users.web_access = 0'),
				1=>array(1, 'Enabled', null, 'WHERE users.web_access = 1')
			);
			$f = new DropDownViewFilter('filter_web_access', $options, null, true);
			$f->setDescriptionFormat("Web Access: %s");
			$view->addFilter($f);

			$parent_org = $_SESSION['user']->employer_id;
			// Provider Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE id = $parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);

			$options = <<<OPTIONS
SELECT
  organisations.id, CONCAT(lookup_org_type.`org_type`, ' - ', legal_name), lookup_org_type.`org_type`, CONCAT("WHERE users.employer_id=",organisations.id)
FROM
  organisations
  INNER JOIN lookup_org_type
    ON organisations.`organisation_type` = lookup_org_type.id
ORDER BY lookup_org_type.org_type,
  legal_name
;
OPTIONS;
			$f = new DropDownViewFilter('filter_all_organisations', $options, null, true);
			$f->setDescriptionFormat("All Organisations: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div class="table-responsive"> <table id="tblAllUsers" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
			}
			echo '</tr></thead><tbody>';
			while($row = $st->fetch())
			{
				$class = $row['web_access'] == 'Disabled' ? 'text-muted' : '';
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username'], $class);
				if($row['gender']=='M')
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				foreach($columns as $column)
				{
					if($column == 'username')
						echo '<td><code>' . htmlspecialchars((string)$row['username']) . '</code></td>';
					else
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
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