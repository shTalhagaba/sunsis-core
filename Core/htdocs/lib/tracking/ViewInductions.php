<?php
class ViewInductions extends View
{
	public static function getInstance(PDO $link, $viewNameSuffix)
	{
		$key = 'view_'.__CLASS__.'_'.$viewNameSuffix;

		if(!isset($_SESSION[$key]))
		{

			$sql = new SQLStatement("SELECT
  employers.legal_name AS company,
  CASE induction.`miap`
	  WHEN 'C' THEN 'Checking'
	  WHEN 'I' THEN 'Ineligible'
	  WHEN 'N' THEN 'No record'
	  WHEN 'P' THEN 'Please select'
	  WHEN 'Y' THEN 'Yes'
  END AS miap_check,
  DATE_FORMAT(inductees.created, '%d/%m/%Y') AS date_submitted,
  induction.resourcer AS recruiter,
  induction.lead_gen,
  induction.brm,
  (SELECT description FROM lookup_delivery_locations WHERE id = inductees.location_area) AS delivery_location,
  '' AS Programme,
  induction.`train_fee_contr` AS training_fee_contribution,
  inductees.age_group,
  DATE_FORMAT(dob, '%d/%m/%Y') AS dob,
  ((DATE_FORMAT(inductees.created,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(inductees.created,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) AS age,
  ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) AS age_at_induction,
  inductees.surname,
  inductees.firstnames,
  inductees.`work_email`,
  inductees.`home_mobile`,
  inductees.`ni` AS NINO,
  CASE induction.`sunesis_account`
      WHEN 'N' THEN 'No'
      WHEN 'Y' THEN 'Yes'
  END AS sunesis,
   CASE induction.`headset_issued`
      WHEN 'S' THEN 'Sent'
      WHEN 'NR' THEN 'Not required'
      WHEN 'SF' THEN 'Signed for'
  END AS headset_received,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  DATE_FORMAT(induction.`join_date`, '%d/%m/%Y') AS join_date,
  DATE_FORMAT(induction.`join_date`, '%m (%M)') AS join_month,
  DATE_FORMAT(induction.`join_date`, '%Y') AS join_year,
  emp_dec_returned AS employer_declaration,
  dec_returned AS learner_declaration,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.induction_assessor) AS induction_assessor,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.assigned_assessor) AS assigned_assessor,
  '' AS employment_start_date,
   CASE induction.`moredle_account`
      WHEN 'N' THEN 'No'
      WHEN 'Y' THEN 'Yes'
      WHEN 'NA' THEN 'N/A'
  END AS moredle_account_created,
  CONCAT(COALESCE(inductees.`home_address_line_1`), ' (',COALESCE(inductees.`home_address_line_2`,''),',',COALESCE(inductees.`home_postcode`,''), ')') AS address,
  inductees.`home_telephone`,
  inductees.`home_email`,

  inductees.id AS inductee_id,

  CASE induction.`induction_status`
	  WHEN 'TBA' THEN 'To Be Arranged'
	  WHEN 'S' THEN 'Scheduled'
	  WHEN 'C' THEN 'Completed'
	  WHEN 'H' THEN 'Holding Contract'
	  WHEN 'L' THEN 'Leaver'
	  WHEN 'W' THEN 'Withdrawn'
	  ELSE ''
  END AS induction_status_desc,
  induction.`induction_status`



FROM
  inductees
  LEFT JOIN induction
    ON inductees.id = induction.inductee_id
  LEFT JOIN organisations AS employers
    ON employers.id = inductees.employer_id
  LEFT JOIN locations AS emp_locations
    ON emp_locations.organisations_id = employers.id
;
	");

			if($_SESSION['user']->isAdmin())
			{
				// nothing
			}
			else
			{
				//TODO
			}

			// Create new view object

			$view = $_SESSION[$key] = new ViewInductions($link, $key);
			$view->setSQL($sql->__toString());

			$f = new TextboxViewFilter('filter_firstnames', "WHERE inductees.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("Firstnames: %s");
			$view->addFilter($f);

			$format = "WHERE induction.induction_date >= '%s'";
			$f = new DateViewFilter('filter_from_induction_date', $format, '');
			$f->setDescriptionFormat("From induction date: %s");
			$view->addFilter($f);

			$format = "WHERE induction.induction_date <= '%s'";
			$f = new DateViewFilter('filter_to_induction_date', $format, '');
			$f->setDescriptionFormat("To induction date: %s");
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
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$columns = array('company', 'miap_check', 'date_submitted', 'recruiter', 'lead_gen', 'brm', 'delivery_location', 'Programme', 'training_fee_contribution', 'age_group', 'dob', 'age', 'age_at_induction', 'surname', 'firstnames', 'work_email', 'home_mobile', 'NINO', 'sunesis', 'headset_received', 'induction_date', 'join_date', 'join_month', 'join_year', 'employer_declaration', 'learner_declaration', 'induction_assessor', 'assigned_assessor', 'employment_start_date', 'moredle_account_created', 'address', 'home_telephone', 'home_email', 'induction_status');
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo '<div style="width:100%; overflow-x:scroll ; overflow-y: hidden; padding-bottom:10px;"><table id="tblInductions" class="table table-striped table-bordered">';
			echo '<thead>';
			echo '<tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				/*foreach($row AS $key => $value)
					if(ctype_alpha(str_replace('_', '',$key)))
						echo '\''.($key).'\', ';*/

				$record_id = $row['inductee_id'];
				$open_url = 'do.php?_action=view_inductee&id='.$record_id;
				$edit_url = 'do.php?_action=edit_inductee&id='.$record_id;
				$td = <<<HTML

<div class="btn-group">
	<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
		<span class="sr-only">Toggle Dropdown</span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li><a href="#" onclick="window.location.href='$open_url';"><span class="fa fa-folder-open"></span>Open</a></li>
		<li><a href="#" onclick="window.location.href='$edit_url';"><span class="fa fa-edit"></span>Edit</a></li>
	</ul>
</div>

HTML;
				echo '<tr onclick="test(\''.$record_id.'\');"><td>' . $td . '</td>';
				foreach($columns as $column)
				{
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
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