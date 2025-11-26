<?php
class ViewInternalValidationReport extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		$where = "";

		//if(!isset($_SESSION[$key]))
		{
			$sql = <<<SQL

			SELECT
	internal_validation.id AS iv_id,
	internal_validation.tr_id,
	CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name,
	(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = internal_validation.iv_user_id) AS iv_name,
	internal_validation.iv_date,
	internal_validation.iv_action_date,
	(IF(internal_validation.iv_type = 1, 'Interim', IF(internal_validation.iv_type = 2, 'Summative', ''))) AS iv_type,
	internal_validation.iv_qualification_id,
	(SELECT internaltitle FROM student_qualifications WHERE REPLACE(student_qualifications.id, '/', '') = internal_validation.iv_qualification_id AND student_qualifications.tr_id = internal_validation.tr_id LIMIT 1) AS qualification_title,
	internal_validation.comments

FROM
	internal_validation
	LEFT JOIN tr ON internal_validation.`tr_id` = tr.id

$where

ORDER BY
	internal_validation.iv_date DESC
;

SQL;

			// Create new view object

			$view = $_SESSION[$key] = new ViewInternalValidationReport();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

			//L03 filter
			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Ref: %s");
			$view->addFilter($f);

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			// SurnameFilter
			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 3 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 2 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			// Qual Title Filter
			$options = "SELECT iv_qualification_id, student_qualifications.`internaltitle`, NULL, CONCAT('WHERE internal_validation.iv_qualification_id=',CHAR(39),iv_qualification_id,CHAR(39)) FROM internal_validation
INNER JOIN student_qualifications ON REPLACE(internal_validation.`iv_qualification_id`, '/','') = REPLACE(student_qualifications.`id`, '/', '')
AND internal_validation.`tr_id` = student_qualifications.`tr_id` GROUP BY iv_qualification_id;";
			$f = new DropDownViewFilter('filter_qualification_title', $options, null, true);
			$f->setDescriptionFormat("Qualification Title: %s");
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

			$options = array(
				0=>array(1, 'Learner', null, 'ORDER BY tr.firstnames ASC'),
				1=>array(2, 'Qualification Title', null, 'ORDER BY internal_validation.iv_qualification_id ASC'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<tr>';
			echo '<th  class="topRow"></th>
				 <th  class="topRow">Learner Name</th>
				 <th  class="topRow">IV Name</th>
				 <th  class="topRow">IV Date</th>
				 <th  class="topRow">IV Type</th>
				 <th  class="topRow">IV Action Date</th>
				 <th  class="topRow">QAN</th>
				 <th  class="topRow">Qualification Title</th>
				 <th  class="topRow">Units</th>
				 <th  class="topRow">Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];

				echo HTML::viewrow_opening_tag('/do.php?_action=read_training_record&internal_validation_tab=1&id=' . $tr_id);
				echo "<td title='" . $row['iv_id'] . "' align='center' style='border-right-style: solid;'> <img height='50px;' src=\"/images/iv.png\" border=\"0\" alt=\"\" /></td>";
				echo '<td align="left">' . HTML::cell($row['learner_name']) . '</td>';
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
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}



}
?>