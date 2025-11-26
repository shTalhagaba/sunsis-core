<?php
class ViewLearningAims extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$emp=$_SESSION['user']->employer_id;

			$where = '';
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
			{
				$where = '';
			}
			elseif($_SESSION['user']->type==1 || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
			{
				$where = ' and (tr.provider_id= '. $emp . ' or tr.employer_id=' . $emp . ')';
			}
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				$where = " and tr.assessor='" . $id . "')";
			}
			elseif($_SESSION['user']->type==2)
			{
				$username = $_SESSION['user']->username;
				$where = ' and tr.tutor="' . $username . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' and tr.verifier="' . $id . '")';
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' and tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==20)
			{
				$username = $_SESSION['user']->username;
				$where = " and (tr.programme='" . $username . "')";
			}
			elseif($_SESSION['user']->type==21)
			{
				$username = $_SESSION['user']->username;
				//$where = " and (courses.director='" . $username . "')";
				$where = ' and find_in_set("' . $username . '", courses.director) ';
			}
			$sql = <<<HEREDOC
select * from dm;
HEREDOC;


			$view = $_SESSION[$key] = new ViewLearningAims();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			// Add view filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE status_code=1'),
				2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE status_code=2'),
				3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE status_code=3'),
				4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE status_code = 4'),
				5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE status_code = 5'),
				6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE status_code = 6'),
				7=>array(7, '7. Delete from ILR', null, 'WHERE status_code = 7'));
			$f = new DropDownViewFilter('filter_record_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			// Surname Sort
			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Qualification Title (asc)', null, 'ORDER BY title'),
				2=>array(3, 'Training Record ID (asc) Learn Aim Ref (desc)', null, ' ORDER BY tr_id ASC, l03, learning_aim_reference DESC '));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			/*			$options = array(
				   0=>array(1, 'All qualifications', null, null),
				   1=>array(2, 'Exempted', null, ' where aptitude = 1'),
				   2=>array(3, 'Not-exempted', null, 'where aptitude != 1'));
			   $f = new DropDownViewFilter('filter_exemption', $options, 1, false);
			   $f->setDescriptionFormat("Exemption: %s");
			   $view->addFilter($f);
   */
			/*
			   $options = 'SELECT distinct level, level, null, CONCAT("WHERE student_qualifications.level=",level) FROM student_qualifications order by level';
			   $f = new DropDownViewFilter('level', $options, null, true);
			   $f->setDescriptionFormat("Level: %s");
			   $view->addFilter($f);
   */
			$options = 'SELECT distinct qualification_type, qualification_type, null, CONCAT("WHERE dm.qualification_type=",char(39),qualification_type,char(39)) FROM student_qualifications order by qualification_type';
			$f = new DropDownViewFilter('type', $options, null, true);
			$f->setDescriptionFormat("Type: %s");
			$view->addFilter($f);

			$options = 'SELECT distinct contracts.id, contracts.title, null, CONCAT("WHERE dm.contract_id=",contracts.id) FROM contracts where active = 1 order by contracts.id desc';
			$f = new DropDownViewFilter('contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			$options = 'SELECT distinct id, legal_name, null, CONCAT("WHERE employer_id=",organisations.id) FROM organisations where organisation_type = 2 ORDER BY legal_name';
			$f = new DropDownViewFilter('employer', $options, null, true);
			$f->setDescriptionFormat("Employers: %s");
			$view->addFilter($f);

			// Provider Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  provider_id=',organisations.id) FROM organisations WHERE id = $parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  provider_id=',organisations.id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE sector=",lookup_sector_types.id) FROM lookup_sector_types WHERE lookup_sector_types.id = 17 OR lookup_sector_types.id > 21';
			$f = new DropDownViewFilter('filter_sector', $options, null, true);
			$f->setDescriptionFormat("Sector: %s");
			$view->addFilter($f);

			// Start Date Filter
			$format = "WHERE dm.start_date >= '%s'";
			$f = new DateViewFilter('start_date_start', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			$format = "WHERE dm.start_date <= '%s'";
			$f = new DateViewFilter('start_date_end', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			// Planned end date Filter
			$format = "WHERE dm.planned_end_date >= '%s'";
			$f = new DateViewFilter('end_date_start', $format, '');
			$f->setDescriptionFormat("From end date: %s");
			$view->addFilter($f);

			$format = "WHERE dm.planned_end_date <= '%s'";
			$f = new DateViewFilter('end_date_end', $format, '');
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);

			// Actual end date Filter
			$format = "WHERE dm.actual_end_date >= '%s'";
			$f = new DateViewFilter('actual_end_date_start', $format, '');
			$f->setDescriptionFormat("From actual end date: %s");
			$view->addFilter($f);

			$format = "WHERE dm.actual_end_date <= '%s'";
			$f = new DateViewFilter('actual_end_date_end', $format, '');
			$f->setDescriptionFormat("To actual end date: %s");
			$view->addFilter($f);

			// Achievement end date Filter
			$format = "WHERE dm.achievement_date >= '%s'";
			$f = new DateViewFilter('achievement_date_start', $format, '');
			$f->setDescriptionFormat("From achievement date: %s");
			$view->addFilter($f);

			$format = "WHERE dm.achievement_date <= '%s'";
			$f = new DateViewFilter('achievement_date_end', $format, '');
			$f->setDescriptionFormat("To achievement date: %s");
			$view->addFilter($f);
			/*
			   // Assessor Filter
			   if($_SESSION['user']->type == User::TYPE_MANAGER)
			   {
				   $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),username,char(39)) FROM users WHERE type=3 and employer_id = ".$emp;
			   }
			   else
			   {
				   $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),username,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
			   }
			   $f = new DropDownViewFilter('filter_assessor', $options, null, true);
			   $f->setDescriptionFormat("Assessor: %s");
			   $view->addFilter($f);
   */
			// Programme Type 
			// ---
			/*
			 * re: Updated to use lookup_programme_type table #21814
			 */
			$options = "SELECT code, description, null, CONCAT('WHERE dm.programme_type=',code) FROM lookup_programme_type order by description asc";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE dm.framework_id=',id) FROM frameworks";
			$f = new DropDownViewFilter('filter_framework', $options, null, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);

			// Add Qualification Status filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The qualification is continuing', null, 'WHERE actual_end_date IS NULL'),
				2=>array(2, '2. The qualification is completed', null, 'WHERE actual_end_date IS NOT NULL'));
			$f = new DropDownViewFilter('filter_qualification_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			$options = 'SELECT id, title, null, CONCAT("where dm.manufacturer=",id) FROM brands';
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Manufacturer: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		/* @var $result pdo_result */
		$link->query("UPDATE student_qualifications LEFT JOIN lad201314.all_annual_values ON all_annual_values.LEARNING_AIM_REF = REPLACE(student_qualifications.id,'/','') LEFT JOIN lad201314.ssa_tier1_codes ON ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE SET lsc_learning_aim = CONCAT(lad201314.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201314.ssa_tier1_codes.SSA_TIER1_DESC); ");
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{
//				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
				echo '<td>';
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				$textStyle = '';
				switch($row['status_code'])
				{
					case 1:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
						break;

					case 2:
						echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
						break;

					case 3:
						echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
						break;

					case 4:
					case 5:
					case 6:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
						$textStyle = 'text-decoration:line-through;color:gray';
						break;

					default:
						echo '?';
						break;
				}
				echo '</td>';

//				if($row['gender']=='M')
//					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
//				else
//					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';

				// Shove programme type
				$tr_id = $row['tr_id'];
				$LearnAimRef = str_replace("/","",$row['learning_aim_reference']);
				if($row['contract_year']<2012)
				{
					$x = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A34|/ilr/main[A09='$LearnAimRef']/A34|/ilr/subaim[A09='$LearnAimRef']/A34" . '"';
					$y = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A35|/ilr/main[A09='$LearnAimRef']/A35|/ilr/subaim[A09='$LearnAimRef']/A35" . '"';
					$z = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A31|/ilr/main[A09='$LearnAimRef']/A31|/ilr/subaim[A09='$LearnAimRef']/A31" . '"';
					$destination = '"' . "/ilr/learner/L39" . '"';
					$health_problems = '"' . "/ilr/learner/L14" . '"';
					$disability = '"' . "/ilr/learner/L15" . '"';
					$learning_difficulty = '"' . "/ilr/learner/L16" . '"';
					$ethnicity = '"' . "/ilr/learner/L12" . '"';
					$provspec = '"' . "/ilr/learner/L42b" . '"';
					$partnerUKPRN = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A22|/ilr/main[A09='$LearnAimRef']/A22|/ilr/subaim[A09='$LearnAimRef']/A22" . '"';
					$LearnerPostcode = '"' . "/ilr/learner/L17" . '"';
					$DelLocPostCode = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A23|/ilr/main[A09='$LearnAimRef']/A23|/ilr/subaim[A09='$LearnAimRef']/A23" . '"';
					$aln = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A53|/ilr/main[A09='$LearnAimRef']/A53|/ilr/subaim[A09='$LearnAimRef']/A53" . '"';
					$res_code = '"' . "/" . '"';
					$provspec_a = '"' . "/ilr/learner/L42a" . '"';
				}
				else
				{
					$x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
					$y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
					$z = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearnActEndDate" . '"';
					$destination = '"' . "/Learner/Dest" . '"';
					$health_problems = '"' . "/Learner/LLDDHealthProb" . '"';
					$disability = '"' . "/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode" . '"';
					$learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode" . '"';
					$ethnicity = '"' . "/Learner/Ethnicity" . '"';
					$provspec = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
					$partnerUKPRN = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PartnerUKPRN" . '"';
					$LearnerPostcode = '"' . "/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode" . '"';
					$DelLocPostCode = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/DelLocPostCode" . '"';
					$aln = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']" . '"';
					if($row['contract_year']>2012)
						$aln = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='LSF']/LearnDelFAMCode" . '"';
					$res_code = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
					$provspec_a = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';

				}
				$res = DAO::getResultset($link, "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z),extractvalue(ilr,$destination),extractvalue(ilr,$health_problems),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$ethnicity),extractvalue(ilr,$provspec),extractvalue(ilr,$partnerUKPRN),extractvalue(ilr,$LearnerPostcode),extractvalue(ilr,$DelLocPostCode),extractvalue(ilr,$aln),extractvalue(ilr,$res_code),extractvalue(ilr,$provspec_a) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = '$tr_id'  order by contract_year DESC, submission DESC LIMIT 1");
				$row['qualification_status'] = @$res[0][0];
				$row['outcome'] = @$res[0][1];
				$row['ilr_actual_end_date'] = @$res[0][2];
				if($row['learning_aim_reference']=='ZPROG001')
					$row['actual_end_date'] = @$res[0][2];
				$row['ilr_destination_code'] = @$res[0][3];
				$row['health_problems'] = @$res[0][4];
				$row['disability'] = @$res[0][5];
				$row['learning_difficulty'] = @$res[0][6];
				$row['ethnicity'] = @$res[0][7];
				if(!strpos(@$res[0][8], '-'))
					$row['project'] = substr(@$res[0][8],0,5);
				else
					$row['project'] = substr(@$res[0][8],0,8);
				$row['ukprn'] = (@$res[0][9]=='undefined')?'':@$res[0][9];
				$row['learner_postcode'] = @$res[0][10];
				$row['delivery_postcode'] = @$res[0][11];
				$row['additional_learning_need'] = @$res[0][12];
				$row['res_code'] = @$res[0][13];
				$row['project_a'] = @$res[0][14];

				foreach($columns as $column)
				{
					if($column=='name')
					{
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
					else if($column == 'last_login')
					{
						if(empty($row["$column"]))
						{
							echo '<td align="left">n/a</td>';
						}
						else
						{
							echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
						}
					}
					else
					{
						if($column == 'start_date' OR $column == 'creation_date' OR $column == 'closed_date' OR $column == 'planned_end_date' OR $column == 'actual_end_date' OR $column == 'last_review_date' OR $column == 'achievement_date')
						{
							if(isset($row[$column]) AND $row[$column]!='' AND $row[$column]!=0 AND !is_null($row[$column]))
							{
								$row[$column] = isset($row[$column])? date("d/m/Y", strtotime($row[$column])): '&nbsp';
								if($row[$column] == '01/01/1970')
									$row[$column] = '&nbsp';
							}
							else
								$row[$column] = '&nbsp';
						}
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
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
