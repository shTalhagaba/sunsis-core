<?php
class ViewLearnerInternalValidation extends View
{

	public static function getInstance(PDO $link, $id)
	{
		$where = '';

		// Create new view object
		$sql = <<<HEREDOC
SELECT
	internal_validation.id AS iv_id,
	internal_validation.tr_id,
	(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = internal_validation.iv_user_id) AS iv_name,
	internal_validation.iv_date,
	internal_validation.iv_action_date,
	(IF(internal_validation.iv_type = 1, 'Interim', IF(internal_validation.iv_type = 2, 'Summative', ''))) AS iv_type,
	internal_validation.iv_qualification_id,
	(SELECT internaltitle FROM student_qualifications WHERE REPLACE(student_qualifications.id, '/', '') = internal_validation.iv_qualification_id AND student_qualifications.tr_id = internal_validation.tr_id LIMIT 1) AS qualification_title,
	internal_validation.comments

FROM
	internal_validation
WHERE
	tr_id='$id' $where
ORDER BY
	internal_validation.iv_date DESC
;
HEREDOC;

		$view = new ViewLearnerInternalValidation();
		$view->setSQL($sql);


		return $view;
	}


	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';

			echo '<thead><tr>';
			echo '<th  class="topRow"></th><th  class="topRow">IV Name</th><th  class="topRow">IV Date</th><th  class="topRow">IV Type</th><th  class="topRow">IV Action Date</th><th  class="topRow">QAN</th><th  class="topRow">Qualification Title</th><th  class="topRow">Units</th><th  class="topRow">Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_internal_validation&iv_id=' . $row['iv_id'] . '&tr_id=' . $row['tr_id']);
				echo "<td align='center' style='border-right-style: solid;'> <img height='50px;' src=\"/images/iv.png\" border=\"0\" alt=\"\" /></td>";
				echo '<td align="left">' . HTML::cell($row['iv_name']) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['iv_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell($row['iv_type']) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['iv_action_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell($row['iv_qualification_id']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['qualification_title']) . '</td>';
				$attached_units = DAO::getSingleColumn($link, "SELECT unit_reference FROM internal_validation_unit_details WHERE internal_validation_id = " . $row['iv_id']);
				$qual_id = $row['iv_qualification_id'];
				$tr_id = $row['tr_id'];
				$units = "";
				foreach($attached_units AS $unit)
				{
					$query = <<<QUERY
SELECT extractvalue(evidences, '//unit[@reference="$unit"]/@title') AS title FROM student_qualifications WHERE REPLACE(id,'/','') = '$qual_id' AND tr_id = $tr_id
QUERY;
					$unit_title = DAO::getSingleValue($link, $query);
					$units .= $unit . ' - ' . $unit_title . PHP_EOL;
				}
				echo '<td align="left" style="font-size:90%;">' . HTML::cell($units) . '</td>';
				echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';
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