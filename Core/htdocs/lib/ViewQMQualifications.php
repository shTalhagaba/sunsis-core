<?php
class ViewQMQualifications extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{

		if(SystemConfig::getEntityValue($link, "manager") && $_SESSION['user']->type==8)
		{
			$org_id = $_SESSION['user']->employer_id;
			$db = DB_NAME;
			$sql = <<<HEREDOC
SELECT
	id as qan,
	lsc_learning_aim,
	awarding_body,
	title,
	description,
	assessment_method,
	structure,
	level,
	qualification_type as type,
	regulation_start_date,
	operational_start_date,
	operational_end_date,
	certification_end_date,
	#evidences,
	units,
	total_proportion,
	unitswithevidence,
	elements_without_evidence,
	units_required,
	mandatory_units,
	clients,
	mainarea,
	subarea,
	qual_status,
	guided_learning_hours,
	concat(mandatory_units,'/',(units-mandatory_units)) as total_units,
	qualifications.internaltitle as internal_title
FROM
	qualifications 
INNER JOIN $db.provider_qualifications on $db.provider_qualifications.qualification_id = qualifications.id and $db.provider_qualifications.internaltitle = qualifications.internaltitle	
	where $db.provider_qualifications.org_id = $org_id;
HEREDOC;
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	id as qan,
	lsc_learning_aim,
	awarding_body,
	title,
	description,
	assessment_method,
	structure,
	level,
	qualification_type as type,
	regulation_start_date,
	operational_start_date,
	operational_end_date,
	certification_end_date,
	#evidences,
	units,
	total_proportion,
	unitswithevidence,
	elements_without_evidence,
	units_required,
	mandatory_units,
	clients,
	mainarea,
	subarea,
	qual_status,
	guided_learning_hours,
	concat(mandatory_units,'/',(units-mandatory_units)) as total_units,
	internaltitle as internal_title
FROM
	qualifications;
HEREDOC;
		}

		if(DB_NAME=='am_edexcel')
		{
			$username = $_SESSION['user']->username;
			$sql = <<<HEREDOC
SELECT
	id as qan,
	lsc_learning_aim,
	awarding_body,
	title,
	description,
	assessment_method,
	structure,
	level,
	qualification_type as type,
	regulation_start_date,
	operational_start_date,
	operational_end_date,
	certification_end_date,
	#evidences,
	units,
	total_proportion,
	unitswithevidence,
	elements_without_evidence,
	units_required,
	mandatory_units,
	clients,
	mainarea,
	subarea,
	qual_status,
	guided_learning_hours,
	concat(mandatory_units,'/',(units-mandatory_units)) as total_units,
	internaltitle as internal_title
FROM
	qualifications
	where  clients not in('demo1','demo2','demo3','jatin','demo','demo4','pearsonwbl','admin')
HEREDOC;
		}
		
		// Create new view object

			$view = $_SESSION[$key] = new ViewQMQualifications();
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
			
			$options = array(
				0=>array(1, 'Centre No (asc), Title (asc)', null, 'ORDER BY clients,internal_title'),
				1=>array(2, 'Title (asc), Type (asc)', null, 'ORDER BY internal_title, type'),
				2=>array(3, 'Type (asc), Level (asc)', null, 'ORDER BY type, level'),
				3=>array(4, 'Type (desc), Level (desc)', null, 'ORDER BY type DESC, level DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT awarding_body, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM qualifications order by awarding_body';
			$f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
			$f->setDescriptionFormat("Awarding Body: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT level, level, null, CONCAT(" WHERE level=",char(39),level,char(39)) FROM qualifications order by level';
			$f = new DropDownViewFilter('filter_level', $options, null, true);
			$f->setDescriptionFormat("Level: %s");
			$view->addFilter($f);
			
			$options = 'SELECT DISTINCT qualification_type, qualification_type, null, CONCAT(" WHERE qualification_type=",char(39),qualification_type,char(39)) FROM qualifications order by qualification_type';
			$f = new DropDownViewFilter('filter_qualification_type', $options, null, true);
			$f->setDescriptionFormat("Qualification Type: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT mainarea, mainarea, null, CONCAT(" WHERE mainarea=",char(39),mainarea,char(39)) FROM qualifications order by mainarea';
			$f = new DropDownViewFilter('filter_qualification_mainarea', $options, null, true);
			$f->setDescriptionFormat("Qualification Sector Subject Area: %s");
			$view->addFilter($f);
			
			$options = 'SELECT DISTINCT subarea, subarea, null, CONCAT(" WHERE subarea=",char(39),subarea,char(39)) FROM qualifications order by subarea';
			$f = new DropDownViewFilter('filter_qualification_subarea', $options, null, true);
			$f->setDescriptionFormat("Qualification Sector Subject Sub-area: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT username, username, null, CONCAT(" WHERE clients=",char(39),username,char(39)) FROM users where type = 1 order by username';
			$f = new DropDownViewFilter('filter_centres', $options, null, true);
			$f->setDescriptionFormat("Centre: %s");
			$view->addFilter($f);
			
			
			if(DB_NAME!='am_edexcel')
			{
				$options = array(
					0=>array(1, 'Accessible', null, ' where clients like "%' . DB_NAME .'%"'),
					1=>array(2, 'Not Accessible', null, ' where clients not like "%' . DB_NAME . '%"'));
				$f = new DropDownViewFilter('filter_accessibility', $options, 1, false);
				$f->setDescriptionFormat("Access: %s");
				$view->addFilter($f);
			}

			$options = array(
				0=>array(1, 'All Qualifications', null, null),
				1=>array(2, 'Fully built', null, ' where qual_status = 1'),
				2=>array(3, 'Unit Level only', null, ' where qual_status = 0'));
			$f = new DropDownViewFilter('filter_status', $options, 1, false);
			$f->setDescriptionFormat("Status: %s");
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
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Centre No</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>LAD</th><th>Total Units</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{

				if(DB_NAME!='am_edexcel')
				{
					$pos = strpos($row['clients'],DB_NAME);
					
					if($pos === false) 
					{
						echo HTML::viewrow_opening_tag('do.php?_action=read_qualification&id=' . rawurlencode($row['id']) . '&internaltitle=' . rawurlencode($row['internaltitle']) . '&clients=' . rawurlencode($row['clients']));
					}
					else
					{
						echo HTML::viewrow_opening_tag('do.php?_action=read_qualification&id=' . rawurlencode($row['qan']) . '&internaltitle=' . rawurlencode($row['internal_title']));
					}
				}
				else
						echo HTML::viewrow_opening_tag('do.php?_action=read_qualification&id=' . rawurlencode($row['qan']) . '&internaltitle=' . rawurlencode($row['internal_title']) . '&clients=' . rawurlencode($row['clients']));
				
			
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="center">' . HTML::cell($row['clients']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['internal_title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['level']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['qan']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";

				//echo '<td align="center">' . htmlspecialchars((string)$row['mandatory_units']) . " / " . htmlspecialchars((string)$row['units']-$row['mandatory_units']) .  "</td>";

				$err='';
				$counter = 0;
				
				// The sum of proportion of mandatory units must be equal to 100 if there are no optional units.
				if((int)$row['total_proportion']!=100 && (int)$row['units']==(int)$row['mandatory_units'])
					$err .= "\n\n" . ++$counter . ". The sum of proportion of all mandatory units must be equal to 100 since there is no optional unit in the qualification"; 
			
				// The sum of proportion of mandatory units must be less than 100 if there are optional units
				if((int)$row['total_proportion']>=100 && (int)$row['units']>(int)$row['mandatory_units'])
					$err .= "\n\n" . ++$counter . ". The sum of proportion of all mandatory units must be less than 100 since there are optional units in the qualification"; 
				
				// All units must have at least one evidence requirement
				if((int)$row['unitswithevidence']<(int)$row['units'])
					$err .= "\n\n" . ++$counter . ". All units must have at least one evidence requirement"; 
			
				// All elements must have at least one evidence requirement
				if((int)$row['elements_without_evidence']>0)
					$err .= "\n\n" . ++$counter . ". All elements must have at least one evidence requirement"; 


				if($err=='')
					echo '<td align="center">' . htmlspecialchars((string)$row['mandatory_units']) . " / " . htmlspecialchars((string)$row['units']-$row['mandatory_units']) .  "</td>";
				else
				{
					$err .= "\n\n Please contact support desk for further assistance.";
					echo '<td align="center" title="' . $err .  '" style="background-color: #FF6666">' . htmlspecialchars((string)$row['mandatory_units']) . " / " . htmlspecialchars((string)$row['units']-$row['mandatory_units']) .  "</td>";
				}
				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>