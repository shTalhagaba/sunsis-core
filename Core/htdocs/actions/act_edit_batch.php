<?php
class edit_batch implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$qualification_id = isset($_GET['qualification_id']) ? $_GET['qualification_id'] : '';
		$internaltitle= isset($_GET['internaltitle']) ? $_GET['internaltitle'] : '';
		$framework_id = isset($_GET['framework_id']) ? $_GET['framework_id'] : '';
		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		$achieved = isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';
		$batchmembers = isset($_REQUEST['batchmembers'])?$_REQUEST['batchmembers']:'';
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_tr_matrix&qualification_id="  . $qualification_id . "&framework_id=" . $framework_id . "&tr_id=" . $tr_id . "&internaltitle=" . $internaltitle . "&achieved=" . $achieved, "Learner Qualification Matrix");

		
		$view = ViewBatchTrainingRecords::getInstance($link, $course_id, $batchmembers);
		$view->refresh($link, $_REQUEST);
		
		
		
			$sql = <<<HEREDOC
SELECT
*
FROM
	framework_qualifications
WHERE 
	id = '$qualification_id' and internaltitle = '$internaltitle' and framework_id='$framework_id'; 
HEREDOC;

			
		$progress = array();
		$st = $link->query($sql);	
		if($st) 
		{
            $milestones = array();

			while($row = $st->fetch())
			{
				// Calculating current month since framework start date
/*				$que = "select DATE_FORMAT(start_date,'%m') from tr where id={$row['tr_id']}";
				$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
				$que = "select DATE_FORMAT(start_date,'%Y') from tr where id={$row['tr_id']}";
				$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
				$current_year = (int)date("Y");
				$current_month = (int)date("m");
				$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;
		
				if($current_month > $study_start_month)
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				else
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				
				$month = "month_" . ($current_month_since_study_start_date-1);	
		
				
				if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
				{
					// Calculating target month and target
					$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1 and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id={$row['tr_id']}";
					$target = trim(DAO::getSingleValue($link, $que));
					
					// Preparing milestones array for unit level progress tracking
					$sql2 = "select * from student_milestones where framework_id = '$framework_id' and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
					$milestones = array();
					$st2 = $link->query($sql2);	
					if($st2) 
					{
						$index = 0;
						while($row2 = $st2->fetch())
						{
							$milestones[] = array('tr_id' => $row2['tr_id'], 'unit_id' => $row2['unit_id'], 'target' => $row2[$month]);
							$index++;
						}
					}
					
				}
				else
	*/			
				{	
					$target='';
					$milestones = array();	
				}
				
				if($target=='')
					$target=0;
				
				$progress[]= array('evidences' => $row['evidences'], 'qualification_id' => $row['id'], 'framework_id' => $row['framework_id'],  'internaltitle' => $row['internaltitle'],  'target' => $target);
				$actual_end_date = '';
				$achievement_date = '';	
			}
		
		}		
		else
		{
			throw new DatabaseException($link, $sql);
		}

		
		
		
		$evidence_dropdown = "SELECT title, CONCAT(title, ' - ', DATE_FORMAT(date,'%d-%m-%Y') , ' - ' , assessor), null FROM evidence_template where tr_id='$tr_id' and qualification_id = '$qualification_id' ORDER BY id;";
		$evidence_dropdown = DAO::getResultset($link, $evidence_dropdown);
		
		$assessment_method_dropdown = "SELECT id, type, null FROM lookup_evidence_type ORDER BY id;";
		$assessment_method_dropdown = DAO::getResultset($link, $assessment_method_dropdown);
		
		$evidence_type_dropdown = "SELECT id, content, null FROM lookup_evidence_content ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);

		$category_dropdown = "SELECT id, category, null FROM lookup_evidence_categories ORDER BY id;";
		$category_dropdown = DAO::getResultset($link, $category_dropdown);
		
		$status = array(
		array('1', 'Achieved', ''),
		array('0', 'Outstanding', ''),
		array('3', 'Reset', ''));
		
		$evidence = DAO::getResultSet($link,"select id, type from lookup_evidence_type");
		$evidence2 = DAO::getResultSet($link,"select id, content from lookup_evidence_content");
		$evidence3 = DAO::getResultSet($link,"select id, category from lookup_evidence_categories");
		
		include('tpl_edit_batch.php');
	}
}

?>
