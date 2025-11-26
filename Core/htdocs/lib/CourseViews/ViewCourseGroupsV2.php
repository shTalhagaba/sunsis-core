<?php
class ViewCourseGroupsV2 extends View
{
	public static function getInstance($link, $course_id)
	{
		$key = 'view_'.__CLASS__.$course_id;

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<SQL
SELECT
	groups.id AS group_id,
	groups.courses_id AS course_id,
	groups.title,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = groups.tutor) AS tutor,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = groups.assessor) AS assessor,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = groups.verifier) AS verifier,
	#(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.tr_id = tr.id WHERE group_members.groups_id = groups.id) AS members,
	(SELECT COUNT(*) FROM training_groups WHERE training_groups.group_id = groups.id) AS tgs
FROM
	groups

ORDER BY
	groups.title
;
SQL;

			$sql = new SQLStatement($sql);

			$sql->setClause("WHERE groups.courses_id = '{$course_id}'");

			$view = $_SESSION[$key] = new ViewCourseGroupsV2();
			$view->setSQL($sql->__toString());

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$caseload = '';
		if($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";

		$st = DAO::query($link, $this->getSQL());
		if($st)
		{
			echo '<div class="well well-sm text-center text-bold" style="padding: 1px;">' . $st->rowCount() . ' records</div>';
			echo '<div class="table-responsive"><table id="tblCourseGroups" class="table table-bordered table-hover">';
			echo '<thead><tr><th>&nbsp;</th>';
			echo '<th>Title</th><th>Tutor</th><th>Assessor</th><th>Verifier</th><th>Learners Count</th><th title="training groups count">Training Groups Count</th>';
			echo '</thead><tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td>';
				echo '<span class="btn btn-xs btn-info" title="View detail about this cohort" onclick="window.location.href=\'do.php?_action=read_course_v2&subview=group_view&id=' . $row['course_id'].'&group_id='.$row['group_id'] . '\'"><i class="fa fa-folder"></i></span> &nbsp; ';
				if($_SESSION['user']->isAdmin())
				{
					echo '<span class="btn btn-xs btn-primary" title="Edit this cohort" onclick="window.location.href=\'do.php?_action=read_course_v2&subview=add_edit_group&id=' . $row['course_id'].'&group_id='.$row['group_id'] . '&from_view=groups' . '\'"><i class="fa fa-edit"></i></span> &nbsp; ';
					echo '<span class="btn btn-xs btn-primary" title="Create training groups for this cohort" onclick="window.location.href=\'do.php?_action=read_course_v2&subview=add_training_group_multiple&id=' . $row['course_id'].'&group_id='.$row['group_id'] . '&from_view=groups' . '\'"><i class="fa fa-sitemap"></i></span> &nbsp; ';
					echo '<span class="btn btn-xs btn-danger" title="Delete this cohort" onclick="delete_cohort('.$row['group_id'].');"><i class="fa fa-trash"></i></span> &nbsp; ';
				}
				echo '</td>';
				echo '<td>' . HTML::cell($row['title']) . '</td>';
				echo '<td>' . HTML::cell($row['tutor']) . '</td>';
				echo '<td>' . HTML::cell($row['assessor']) . '</td>';
				echo '<td>' . HTML::cell($row['verifier']) . '</td>';
				$members_sql = "SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.tr_id = tr.id WHERE group_members.groups_id = '{$row['group_id']}' {$caseload} ";
				echo '<td>' . HTML::cell(DAO::getSingleValue($link, $members_sql)) . '</td>';
				echo '<td>' . HTML::cell($row['tgs']) . '</td>';
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo '<div class="well well-sm text-center text-bold" style="padding: 1px;">' . $st->rowCount() . ' records</div>';
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>