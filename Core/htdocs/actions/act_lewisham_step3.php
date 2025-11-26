<?php
class lewisham_step3 implements IAction
{
	public function execute(PDO $link)
	{
//		$link->query("delete from aim where A10 Not in (45,46)");
//		$link->query("delete from learner where L03 not in (select A03 from aim)");
		
		// Check if all qualifications exists
		$sql = "select distinct A09 from aim where A09 not in ('ZPROG001','')";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$a09 = $row['A09'];
				$found = DAO::getSingleValue($link, "select count(*) from qualifications where replace(id,'/','') = '$a09'");
				if(!$found)
					pre("Qualification " . $a09 . " need to be downloaded");
			}
		}
		
		// Create Frameworks
		$sql = "select distinct A09, A15, A26 from aim where A09 not in ('ZPROG001','') ORDER BY A26, A15, A09";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$a09 = $row['A09'];
				$a15 = $row['A15'];
				$a26 = $row['A26'];
				$internaltitle = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','')='$a09'");

				// If TtG Framework
				if($a15=='99' && ($a26=='0' || $a26==''))
				{
					// Check if framework already exists
					$fid = DAO::getSingleValue($link, "select id from frameworks where framework_type = '99' and title = '$internaltitle'");
					if(!$fid)
					{
						$f = new Framework($link);
						$f->title = $internaltitle;
						$f->framework_code = 0;
						$f->id = NULL;
						$f->duration_in_months = 12;
						$f->parent_org = 1;
						$f->active = 1;
						$f->clients = '';
						$f->framework_type = 99;
						$f->save($link);
						// Add qualification too
						DAO::execute($link, "insert into framework_qualifications select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, NULL, NULL, '$f->id',evidences, units, internaltitle, '100', '12', units_required, mandatory_units, 1 from qualifications where replace(id,'/','') = '$a09'");


						// and create course too 
						$course = new Course($link);	
						$course->id = NULL;
						$course->organisations_id = 1361;
						$course->title = $internaltitle;
						$course->framework_id = $f->id;
						$course->programme_type = 1;
						$course->active = 1;
						$course->course_start_date = '2009-01-01';
						$course->course_end_date = '2010-12-31';
						$course->save($link);
						
						// Add qualification to the course too
						$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course->id where framework_qualifications.framework_id = $f->id);";
						DAO::execute($link, $query);
					}
					else
					{
						// Framework found but qualification(s) need to be added
						$qual = DAO::getSingleValue($link, "select count(*) from framework_qualifications where framework_id = $fid");
						if($qual!=1)
						{
							DAO::execute($link, "insert into framework_qualifications select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, NULL, NULL, '$f->id',evidences, units, internaltitle, '100', '12', units_required, mandatory_units, 1 from qualifications where replace(id,'/','') = '$a09'");
						}	
						
						// Framework was found but course need to be created
						$course_id = DAO::getSingleValue($link, "select id from courses where framework_id = $fid");
						if($course_id=='')
						{
							$course = new Course($link);	
							$course->id = NULL;
							$course->organisations_id = 1361;
							$course->title = $internaltitle;
							$course->framework_id = $fid;
							$course->programme_type = 1;
							$course->active = 1;
							$course->course_start_date = '2009-01-01';
							$course->course_end_date = '2010-12-31';
							$course->save($link);

							// Add qualification to the course too
							$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course->id where framework_qualifications.framework_id = $fid);";
							DAO::execute($link, $query);
							
						}
					}					
				}
				elseif($a15!='99' && ($a26!='0' && $a26!=''))
				{
					$fid = DAO::getSingleValue($link, "select id from frameworks where framework_code = '$a26' and framework_type = '$a15'");
					if(!$fid)
					{
						$f = new Framework($link);
						$f->title = DAO::getSingleValue($link, "select CONCAT(FRAMEWORK_CODE, ' - ',FRAMEWORK_DESC) from lad201112.FRAMEWORKS where FRAMEWORK_CODE='$a26' and FRAMEWORK_TYPE_CODE='$a15'");
						$f->framework_code = $a26;
						$f->id = NULL;
						$f->duration_in_months = 12;
						$f->parent_org = 1;
						$f->active = 1;
						$f->clients = '';
						$f->framework_type = $a15;
						$f->save($link);
						// Add qualification too
						DAO::execute($link, "insert into framework_qualifications select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, NULL, NULL, '$f->id',evidences, units, internaltitle, '20', '12', units_required, mandatory_units, 1 from qualifications where replace(id,'/','') in (select A09 from aim where A15='$a15' and A26='$a26')");

						// and create course too 
						$course = new Course($link);	
						$course->id = NULL;
						$course->organisations_id = 1361;
						$course->title = DAO::getSingleValue($link, "select CONCAT(FRAMEWORK_CODE, ' - ',FRAMEWORK_DESC) from lad201112.frameworks where FRAMEWORK_CODE='$a26' and FRAMEWORK_TYPE_CODE='$a15'");
						$course->framework_id = $f->id;
						$course->programme_type = 2;
						$course->active = 1;
						$course->course_start_date = '2009-01-01';
						$course->course_end_date = '2010-12-31';
						$course->save($link);
						
						// Add qualification to the course too
						$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course->id where framework_qualifications.framework_id = $f->id);";
						DAO::execute($link, $query);
					}
				}
			}
		}
		pre("Complete");
	}
}
?>