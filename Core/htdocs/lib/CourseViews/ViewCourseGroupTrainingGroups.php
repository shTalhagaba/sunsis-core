<?php
class ViewCourseGroupTrainingGroups extends View
{
	public static function getInstance($link, $course_id)
	{
		$key = 'view_'.__CLASS__.$course_id;

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<SQL
SELECT
	groups.id AS group_id,
	training_groups.id AS tg_id,
	groups.courses_id AS course_id,
	groups.title AS group_title,
	training_groups.title AS tg_title
	#,(SELECT COUNT(*) FROM tr WHERE tr.tg_id = training_groups.id) AS members
FROM
	training_groups
	INNER JOIN groups ON training_groups.group_id = groups.id
ORDER BY
	groups.title,
	training_groups.title
;
SQL;

			$sql = new SQLStatement($sql);

			$sql->setClause("WHERE groups.courses_id = '{$course_id}'");

			$view = $_SESSION[$key] = new ViewCourseGroupTrainingGroups();
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
			echo '<div class="table-responsive"><table id="tblCourseGroupsTGs" class="table table-bordered table-hover">';
			echo '<thead><tr><th>&nbsp;</th>';
			echo '<th>Cohort Title</th><th>Training Group Title</th><th>Learners Count</th>';
			echo '</thead><tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td>';
				echo '<span class="btn btn-xs btn-info" title="View detail about this training group" onclick="window.location.href=\'do.php?_action=read_course_v2&subview=training_group_view&id=' . $row['course_id'].'&group_id='.$row['group_id'].'&tg_id='.$row['tg_id'] . '&from_view=course_training_groups' . '\'"><i class="fa fa-folder"></i></span> &nbsp; ';
				if($_SESSION['user']->isAdmin())
				{
					echo '<span class="btn btn-xs btn-primary" title="Edit this training group" onclick="window.location.href=\'do.php?_action=read_course_v2&subview=add_edit_training_group&id=' . $row['course_id'].'&group_id='.$row['group_id'].'&tg_id='.$row['tg_id'] . '&from_view=course_training_groups' . '\'"><i class="fa fa-edit"></i></span> &nbsp; ';
					echo '<span class="btn btn-xs btn-danger" title="Delete this training group" onclick="delete_training_group('.$row['tg_id'].');"><i class="fa fa-trash"></i></span> &nbsp; ';
				}
				echo '<div class="btn-group">';
                echo '<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown">';
                echo '<span class="caret"></span>';
                echo '</button>';
                echo '<ul class="dropdown-menu">';
                echo '<li><a href="do.php?_action=add_exam_results_multiple&subaction=show_learners&course_id='.$row['course_id'].'&group_id='.$row['group_id'].'&tg_id='.$row['tg_id'].'">Add Exam Results</a></li>';
                echo '<li><a href="do.php?_action=add_learners_tracking&subaction=show_learners&course_id='.$row['course_id'].'&group_id='.$row['group_id'].'&tg_id='.$row['tg_id'].'">Record Tracking</a></li>';
                echo '</ul>';
                echo '</div>';
				echo '</td>';
				echo '<td>' . HTML::cell($row['group_title']) . '</td>';
				echo '<td>' . HTML::cell($row['tg_title']) . '</td>';
				$members_sql = "SELECT COUNT(*) FROM tr WHERE tr.tg_id = '{$row['tg_id']}' {$caseload} ";
				echo '<td>' . HTML::cell(DAO::getSingleValue($link, $members_sql)) . '</td>';
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

	public static function learnerBadges(PDO $link, $tr_id)
	{
		$labels = [];
		$first_time_maths_l1 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exam_results WHERE tr_id = '{$tr_id}' AND LOWER(qualification_title) LIKE '%math%' AND LOWER(unit_title) LIKE '%level 1%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass';");
		if($first_time_maths_l1 > 0)
			$labels['maths_l1_first_time'] = '<label class="label label-success">Maths L1 Pass 1st Time</label>';
		$first_time_maths_l2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exam_results WHERE tr_id = '{$tr_id}' AND LOWER(qualification_title) LIKE '%math%' AND LOWER(unit_title) LIKE '%level 2%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass';");
		if($first_time_maths_l2 > 0)
			$labels['maths_l2_first_time'] = '<label class="label label-success">Maths L2 Pass 1st Time</label>';
		return $labels;
	}
}
?>