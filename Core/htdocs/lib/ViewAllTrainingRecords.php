<?php
class ViewAllTrainingRecords extends View
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
				$where = ' where (groups.tutor = '. '"' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.verifier = '. '"' . $id . '" or tr.verifier="' . $id . '")';
			}
			elseif($_SESSION['user']->isOrgAdmin())
			{
				$provider_id = $_SESSION['user']->employer_id;
				$where = ' where tr.provider_id = ' . $provider_id;
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$username = $_SESSION['user']->username;
				$emp = $_SESSION['user']->employer_id;
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp' or users.who_created = '$username' or users.who_created in (select username from users where type = 8 and employer_id = '$emp'))" ;
			}
			elseif($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (tr.programme="' . $id . '")';
			}
			elseif($_SESSION['user']->type==21)
			{
				$username = $_SESSION['user']->username;
				//$where = ' where (courses.director="' . $username . '")';
				$where = ' where find_in_set("' . $username . '", courses.director) ';
			}
			else
				throw new Exception("Not authorized");

			$sql = <<<HEREDOC
SELECT DISTINCT
	tr.username,
	tr.surname, tr.firstnames, 
	tr.l03,
	users.enrollment_no AS member_no,
	tr.contract_id,
	CONCAT(tr.firstnames, ' ', tr.surname) AS NAME,
	users.job_role,
	users.enrollment_no,
	lookup_people_type.people_type AS user_type,
	tr.l36 AS percentage_completed,
	employers.legal_name,
	employers.sector,
	locations.full_name,
	users.work_telephone,
	assessor_review.comments AS assessment_status,
	tr.gender,
	tr.uln,
	tr.id AS tr_id,
	tr.status_code,
	employers.legal_name AS employer,
	providers.legal_name AS provider,
	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS projected_end_date,
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS actual_end_date,
	DATEDIFF(CURRENT_DATE(),tr.start_date) AS days_passed,

	courses.title AS course,
	courses.id AS course_id,
	#student_frameworks.title as framework,
	#ilr.is_valid,
	courses.title AS c_title,

	users.job_role AS job_role,
	#student_frameworks.id as fid,
	#group_members.groups_id,

	#groups.assessor,
	#groups.title as group_title,
	#groups.id as group_id,

	tr.`attendances`,

	#IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,

	tr.work_experience,
	contracts.title AS contract,
	employers.manufacturer,
	employers.id AS employer_id,
	locations.full_name AS location,
	employers.legal_name

FROM
	tr
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	#LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id
	LEFT JOIN users AS assessors ON groups.assessor = assessors.id
	#LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id
	#LEFT JOIN ilr on ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND assessor_review.id = (SELECT MAX(id) FROM assessor_review WHERE tr_id = tr.id)
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	#LEFT JOIN student_qualifications as nvqlevel on nvqlevel.tr_id = tr.id and nvqlevel.qualification_type = 'NVQ'
	#LEFT JOIN student_qualifications on student_qualifications.tr_id = tr.id
	LEFT JOIN locations ON locations.id = tr.employer_location_id
	LEFT JOIN lookup_people_type ON lookup_people_type.id = users.type

			$where
HEREDOC;

			$view = $_SESSION[$key] = new ViewAllTrainingRecords();
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


			$options = 'SELECT DISTINCT organisations.id, legal_name, null, CONCAT("WHERE tr.employer_id=",organisations.id) FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE organisation_type LIKE "%2%" ORDER BY legal_name';
			$f = new DropDownViewFilter('organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type like "%3%" order by legal_name';
			$f = new DropDownViewFilter('provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			if(DB_NAME == 'am_pera')
				$options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE employers.sector=",lookup_sector_types.id) FROM lookup_sector_types WHERE lookup_sector_types.id = 17 OR lookup_sector_types.id > 21';
			else
				$options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE employers.sector=",lookup_sector_types.id) FROM lookup_sector_types';
			$f = new DropDownViewFilter('filter_sector', $options, null, true);
			$f->setDescriptionFormat("Sector: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'User (asc)', null, 'ORDER BY surname, firstnames'),
				1=>array(2, 'User (desc)', null, 'ORDER BY surname DESC, firstnames DESC'),
				2=>array(3, 'Employer (asc), User (asc)', null, 'ORDER BY employers.legal_name, surname, firstnames'),
				3=>array(4, 'Employer (desc), User (desc)', null, 'ORDER BY employers.legal_name DESC, surname DESC, firstnames DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			// Dealer Name Filter 	
			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			// Dealer Name Filter 	
			$f = new TextboxViewFilter('filter_surname2', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname Starts With: %s");
			$view->addFilter($f);

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			// L03 Filter
			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("ULN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_member', "WHERE users.enrollment_no LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Enrolment No: %s");
			$view->addFilter($f);

			// Date of Birth Filter
			$format = "WHERE tr.dob = '%s'";
			$f = new DateViewFilter('filter_dob', $format, '');
			$f->setDescriptionFormat("Date Of Birth: %s");
			$view->addFilter($f);

			// National Insurance Filter
			$f = new TextboxViewFilter('filter_nationalinsurance', "WHERE users.ni LIKE '%s%%'", null);
			$f->setDescriptionFormat("National Insurance: %s");
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
			echo '<div class="table-responsive"><table id="tblAllTrainingRecords" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Enrolment No</th><th>Course</th><th>Job Role</th><th>Username</th><th>Organisation</th><th>Location</th><th>Work Telephone</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);

				echo '<td>';
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				switch($row['status_code'])
				{
					case 1:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
						break;

					case 2:
						echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
						break;

					case 3:
					case 6:
						echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
						break;

					case 4:
					case 5:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
						$textStyle = 'text-decoration:line-through;color:gray';
						break;

					default:
						echo '?';
						break;
				}
				echo '</td>';
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['enrollment_no']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['c_title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
				echo '<td align="left"><code>' . htmlspecialchars((string)$row['username']) . "</code></td>";
				if($row['legal_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				}
				if($row['full_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				}
				echo '<td align="left">' . HTML::cell($row['work_telephone']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div> ';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>