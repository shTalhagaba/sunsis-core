<?php
class correct_ilrs implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
        //$st = $link->query("SELECT * FROM ilr WHERE submission = 'W12' AND contract_id IN (SELECT id FROM contracts WHERE contract_year=2014) AND tr_id IN (SELECT id FROM tr WHERE TIMESTAMPDIFF(YEAR,dob,start_date)>=19)");
        $st = $link->query("SELECT * FROM ilr WHERE submission = 'W13' AND contract_id IN (SELECT id FROM contracts WHERE contract_year=2016) and tr_id not in (select tr_id from ilr where contract_id in (select id from contracts where contract_year = 2017))");
        $shouldMigrate = Array();
        $shouldNotMigrate = Array();
        $contracts = Array();
        while($row = $st->fetch())
        {
            $tr_id = $row['tr_id'];
            $contract_id = $row['contract_id'];
            $l03 = $row['L03'];

            $answer = Ilr2016::Migrate($link,$tr_id,$contract_id,$l03);
            if($answer==true)
            {
                $shouldMigrate[] = $l03;
                $contracts[] = DAO::getSingleValue($link, "select title from contracts where id = '$contract_id'");
            }
            else
                $shouldNotMigrate[] = $l03;

        }
        pre("Should Migrate \n" . implode("\n",$shouldMigrate) . " \n and contrats \n" . implode("\n",$contracts) . " \n and should not migrate \n ". implode("\n",$shouldNotMigrate));
    }
}

/*        $st = $link->query("SELECT * FROM temp limit 1600,100");
        while($row = $st->fetch())
        {
            // if in 2013
            $project = $row['project'];
            $start_date = new Date($row['start_date']);
            $uln = $row['uln'];
            $pre = false;
            $st2 = $link->query("SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE extractvalue(ilr,'/ilr/learner/L45|/Learner/ULN')='$uln';");
            while($row2 = $st2->fetch())
            {
                $contract_id = $row2['contract_id'];
                $submission = $row2['submission'];
                $tr_id = $row2['tr_id'];
                $l03 = $row2['L03'];
                $ilr = $row2['ilr'];
                $contract_year = $row2['contract_year'];
                $eligible = false;
                // Check the eligibility first
                if($contract_year<2012)
                {
                    $pageDom = new DomDocument();
                    $pageDom->loadXML($ilr);
                    $e = $pageDom->getElementsByTagName('programmeaim');
                    foreach($e as $node)
                    {
                        $st_date = new Date($node->getElementsByTagName('A27')->item(0)->nodeValue);
                        if($start_date->getDate() == $st_date->getDate())
                            $eligible = true;
                    }
                    $e = $pageDom->getElementsByTagName('main');
                    foreach($e as $node)
                    {
                        $st_date = new Date($node->getElementsByTagName('A27')->item(0)->nodeValue);
                        if($start_date->getDate() == $st_date->getDate())
                            $eligible = true;
                    }
                    $e = $pageDom->getElementsByTagName('subaim');
                    foreach($e as $node)
                    {
                        $st_date = new Date($node->getElementsByTagName('A27')->item(0)->nodeValue);
                        if($start_date->getDate() == $st_date->getDate())
                            $eligible = true;
                    }
                }
                else
                {
                    $pageDom = new DomDocument();
                    $pageDom->loadXML($ilr);
                    $e = $pageDom->getElementsByTagName('LearningDelivery');
                    foreach($e as $node)
                    {
                        $st_date = new Date($node->getElementsByTagName('LearnStartDate')->item(0)->nodeValue);
                        if($start_date->getDate() == $st_date->getDate())
                            $eligible = true;
                    }
                }

                if($eligible)
                {
                    if($contract_year<2012)
                    {
                        $pageDom = new DomDocument();
                        $pageDom->loadXML($ilr);
                        $e = $pageDom->getElementsByTagName('learner');
                        foreach($e as $node)
                        {
                            $node->getElementsByTagName('L42b')->item(0)->nodeValue = $project;
                        }
                        $e = $pageDom->getElementsByTagName('programmeaim');
                        foreach($e as $node)
                        {
                            @$node->getElementsByTagName('A48b')->item(0)->nodeValue = $project;
                        }
                        $e = $pageDom->getElementsByTagName('main');
                        foreach($e as $node)
                        {
                            @$node->getElementsByTagName('A48b')->item(0)->nodeValue = $project;
                        }
                        $e = $pageDom->getElementsByTagName('subaim');
                        foreach($e as $node)
                        {
                            $node->getElementsByTagName('A48b')->item(0)->nodeValue = $project;
                        }
                        $ilr = $pageDom->saveXML();
                        $ilr=substr($ilr,21);
                        $ilr = str_replace("'", "&apos;" , $ilr);

                        $sql3 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and L03 = '$l03'";
                        $st3 = $link->query($sql3);
                        if(!$st3)
                            throw new Exception(implode($link->errorInfo()).'..........', $link->errorCode());

                    }
                    else
                    {
                        $pageDom = new DomDocument();
                        $pageDom->loadXML($ilr);
                        $e = $pageDom->getElementsByTagName('ProviderSpecLearnerMonitoring');
                        foreach($e as $node)
                        {
                            if($node->getElementsByTagName('ProvSpecLearnMonOccur')->item(0)->nodeValue=="B")
                                $node->getElementsByTagName('ProvSpecLearnMon')->item(0)->nodeValue = $project;
                        }

                        $e = $pageDom->getElementsByTagName('LearningDelivery');
                        foreach($e as $node)
                        {
                            if ($node->getElementsByTagName("ProviderSpecDeliveryMonitoring")->length == 0)
                            {
                                $newElement = $pageDom->createElement('ProviderSpecDeliveryMonitoring');
                                $node->appendChild($newElement);
                                $sub = $node->getElementsByTagName('ProviderSpecDeliveryMonitoring');
                                foreach($sub as $s)
                                {
                                    $newsub = $pageDom->createElement('ProvSpecDelMonOccur',"A");
                                    $newElement->appendChild($newsub);
                                    $newsub = $pageDom->createElement('ProvSpecDelMon');
                                    $newElement->appendChild($newsub);
                                }
                            }
                            else
                            {
                                {
                                    if(!isset($node->getElementsByTagName('ProvSpecDelMonOccur')->item(0)->nodeValue))
                                        pre("oops" . $l03);
                                    if($node->getElementsByTagName('ProvSpecDelMonOccur')->item(0)->nodeValue=="B")
                                        $node->getElementsByTagName('ProvSpecDelMon')->item(0)->nodeValue = $project;
                                }
                            }
                        }
                        $ilr = $pageDom->saveXML();
                        $ilr=substr($ilr,21);
                        $ilr = str_replace("'", "&apos;" , $ilr);

                        $sql3 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and L03 = '$l03'";
                        $st3 = $link->query($sql3);
                        if(!$st3)
                            throw new Exception(implode($link->errorInfo()).'..........', $link->errorCode());
                        if($pre)
                            pre($l03);
                    }
                }
            }
        }

*/



		// Auto create courses
/*		$sql = "select * from frameworks";
		$fst = $link->query($sql);
		if($fst)
		{
			$flag =0;
			while($frow = $fst->fetch())
			{

				$flag++;
				$vo = new Course();
				$vo->id = NULL;
				$vo->organisations_id = 840;
				$vo->title = $frow['title'];
				$vo->description = '';
				$vo->course_start_date = "01/01/2009";
				$vo->course_end_date = "31/12/2020";
				$vo->framework_id = $frow['id'];
				if($frow['framework_type']=='99')
					$vo->programme_type = 1;
				else
					$vo->programme_type = 2;
				$vo->active = 1;
				try
				{
					DAO::transaction_start($link);
					if($vo->id!='')
					{
						$query  = "delete from course_qualifications_dates where course_id='$vo->id'";
						DAO::execute($link, $query);
					}
					$vo->save($link);

					// Add default qualification dates for this course to course_qualification_dates
					if(!empty($vo->framework_id) && is_numeric($vo->framework_id))
					{
						$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $vo->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $vo->id where framework_qualifications.framework_id = $vo->framework_id);";
						DAO::execute($link, $query);
					}

					DAO::transaction_commit($link);
				}
				catch(Exception $e)
				{
					DAO::transaction_rollback($link, $e);
					throw new WrappedException($e);
				}

			}
		}

		die($flag);
*/


/*
		// This script auto enrol learners to courses;
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';


		// For Apps
		$sql = "SELECT
		ilr.tr_id
		, courses.id
		, courses.framework_id
		FROM ilr
		LEFT JOIN frameworks ON frameworks.`framework_code` = extractvalue(ilr,'/ilr/main/A26') AND frameworks.`framework_type` = extractvalue(ilr,'/ilr/main/A15')
		LEFT JOIN courses ON courses.`framework_id` = frameworks.id
		WHERE extractvalue(ilr,'/ilr/main/A15')!='99' and courses.id is not null";

		// For Non-Apps
//		$sql = "SELECT
//		ilr.tr_id
//		, courses.id
//		, courses.framework_id
//		FROM ilr
//		LEFT JOIN framework_qualifications ON REPLACE(framework_qualifications.id,'/','') = extractvalue(ilr,'/ilr/main/A09')
//		LEFT JOIN frameworks ON frameworks.id = framework_qualifications.`framework_id`
//		LEFT JOIN courses ON courses.`framework_id` = frameworks.id
//		WHERE extractvalue(ilr,'/ilr/main/A15')='99' AND frameworks.`framework_type` = '99' and courses.id is not null;";


		$fst = $link->query($sql);
		if($fst)
		{
			while($frow = $fst->fetch())
			{

			$tr_id = $frow['tr_id'];
			$course_id = $frow['id'];
			$framework_id = $frow['framework_id'];

		$link->beginTransaction();
		try
		{

			$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
			$sd = Date::toMySQL($tr->start_date);
			$ed = Date::toMySQL($tr->target_date);


			//		$tr->save($link);
			$exists = DAO::getSingleValue($link, "select tr_id from courses_tr where tr_id = $tr_id");
if(!$exists)
{
			// enroling on a course
			$query = <<<HEREDOC
insert into
	courses_tr
values($course_id, $tr_id, '0', $framework_id);
HEREDOC;
			DAO::execute($link, $query);

}

			if(!$exists)
			{
					// Importing framework
					$query = <<<HEREDOC
insert into
	student_frameworks
select title, id, '$tr_id', framework_code, comments, duration_in_months
from frameworks
	where id = '$framework_id';
HEREDOC;
					DAO::execute($link, $query);
			}

			if(!$exists)
			{
					// importing qualification from framework
					$query = <<<HEREDOC
insert into
	student_qualifications
select
id,
'$framework_id',
'$tr_id',
framework_qualifications.internaltitle,
lsc_learning_aim,
awarding_body,
title,
description,
assessment_method,
structure,
level,
qualification_type,
accreditation_start_date,
operational_centre_start_date,
accreditation_end_date,
certification_end_date,
dfes_approval_start_date,
dfes_approval_end_date,
evidences,
units,
'0',
'0',
'0',
'0',
'0',
units_required,
proportion,
0,
0,
0,
0,
0,
0,
0,
'$sd',
'$ed',
NULL,
NULL,
units_required,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
'',
''
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and
course_qualifications_dates.framework_id = framework_qualifications.framework_id and
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id';
HEREDOC;
					DAO::execute($link, $query);
			}
			$link->commit();

		}
		catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}
			}

		}

	}
}
*/
/*
			$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		  $st = $link->query($sql);
		  if($st)
		  {
			  while($row = $st->fetch())
			  {
				  $tr_id = $row['tr_id'];
				  $xml = $row['ilr'];
				  $pageDom = new DomDocument();
				  @$pageDom->loadXML($xml);

				  $e = $pageDom->getElementsByTagName('main');
				  foreach($e as $node)
				  {
					  $a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					  $a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					  $a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					  $a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					  $a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					  $a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					  $a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					  $a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					  $a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					  $a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					  if($a31!='' && $a31!='00000000')
						  $a31 = "'" . Date::toMySQL($a31) . "'";
					  else
						  $a31 = 'NULL';

					  if($a40!='' && $a40!='00000000')
						  $a40 = "'" . Date::toMySQL($a40) . "'";
					  else
						  $a40 = 'NULL';

					  $s = "update student_qualifications set actual_end_date = $a31, a14 = $a14,  a18 = '$a18', a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";
					  $st2 = $link->query($s);
				  //	if(!$st2);
				  //		throw new Exception(implode($link->errorInfo()). $s);

					  $s = "update tr set closure_date = $a31, status_code = '$a34' where tr_id = $tr_id;";
					  $st2 = $link->query($s);


				  }
				  $e = $pageDom->getElementsByTagName('subaim');
				  foreach($e as $node)
				  {
					  $a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					  $a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					  $a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					  $a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					  $a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					  $a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					  $a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					  $a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					  $a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					  $a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					  if($a31!='' && $a31!='00000000')
						  $a31 = "'" . Date::toMySQL($a31) . "'";
					  else
						  $a31 = 'NULL';

					  if($a40!='' && $a40!='00000000')
						  $a40 = "'" . Date::toMySQL($a40) . "'";
					  else
						  $a40 = 'NULL';

					  $s = "update student_qualifications set actual_end_date = $a31, a14 = $a14, a18 = '$a18', a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";
					  $st2 = $link->query($s);
				  //	if(!$st2);
				  //		throw new Exception(implode($link->errorInfo()). $s);

				  }
			  }
		  }


		/*		$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		  $st = $link->query($sql);
		  if($st)
		  {
			  while($row = $st->fetch())
			  {
				  $tr_id = $row['tr_id'];
				  $xml = $row['ilr'];
				  $pageDom = new DomDocument();
				  @$pageDom->loadXML($xml);

				  $e = $pageDom->getElementsByTagName('main');
				  foreach($e as $node)
				  {
					  $a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					  $a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					  $a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					  $a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					  $a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					  $a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					  $a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					  $a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					  $a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					  $a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					  if($a31!='' && $a31!='00000000')
						  $a31 = "'" . Date::toMySQL($a31) . "'";
					  else
						  $a31 = 'NULL';

					  if($a40!='' && $a40!='00000000')
						  $a40 = "'" . Date::toMySQL($a40) . "'";
					  else
						  $a40 = 'NULL';

					  $s = "update tr set closure_date = $a31, status_code = '$a34' where id = $tr_id;";
					  $st2 = $link->query($s);
				  //	if(!$st2);
				  //		throw new Exception(implode($link->errorInfo()). $s);
				  }
			  }
		  }



	  }
  }


  /*
		  $sql = "SELECT * FROM tr WHERE id IN (SELECT tr_id FROM courses_tr);";
		  $st = $link->query($sql);
		  if($st)
		  {
			  while($row = $st->fetch())
			  {
				  $tr_id = $row['id'];
				  $contract_id = $row['contract_id'];
				  $contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");
				  $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = '$tr_id'");
				  $fid = DAO::getSingleValue($link, "select framework_id from courses where id = '$course_id'");
				  $xml = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' order by contract_id desc, submission desc limit 0,1");
				  $pageDom = new DomDocument();
				  $pageDom->loadXML($xml);
				  $aims = $pageDom->getElementsByTagName('A09');
				  foreach($aims as $aim)
				  {
					  $a09 = $aim->nodeValue;
					  $a27 = "'" . Date::toMySQL($aim->parentNode->getElementsByTagName('A27')->item(0)->nodeValue) . "'";
					  $a28 = "'" . Date::toMySQL($aim->parentNode->getElementsByTagName('A28')->item(0)->nodeValue) . "'";

					  if($contract_year<2011)
						  $a14 = @(int)$aim->parentNode->getElementsByTagName('A14')->item(0)->nodeValue;
					  else
						  $a14 = @(int)$aim->parentNode->getElementsByTagName('A71')->item(0)->nodeValue;

					  $a18 = @(int)$aim->parentNode->getElementsByTagName('A18')->item(0)->nodeValue;
					  $a51a = @(int)$aim->parentNode->getElementsByTagName('A51a')->item(0)->nodeValue;
					  $a16 = @(int)$aim->parentNode->getElementsByTagName('A16')->item(0)->nodeValue;

					  $a31 = $aim->parentNode->getElementsByTagName('A31')->item(0)->nodeValue;
					  if($a31!='' && $a31!='00000000' && $a31!='00/00/0000' && $a31!='dd/mm/yyyy')
						  $a31 = "'" . Date::toMySQL($a31) . "'";
					  else
						  $a31 = "NULL";

					  $a40 = @$aim->parentNode->getElementsByTagName('A40')->item(0)->nodeValue;
					  if($a40!='' && $a40!='00000000' && $a40!='00/00/0000' && $a40!='dd/mm/yyyy')
						  $a40 = "'" . Date::toMySQL($a40) . "'";
					  else
						  $a40 = "NULL";


  $query = <<<HEREDOC
  insert into
	  student_qualifications
  select
  id,
  '$fid',
  '$tr_id',
  internaltitle,
  lsc_learning_aim,
  awarding_body,
  title,
  description,
  assessment_method,
  structure,
  LEVEL,
  qualification_type,
  regulation_start_date,
  operational_start_date,
  operational_end_date,
  certification_end_date,
  dfes_approval_start_date,
  dfes_approval_end_date,
  evidences,
  units,
  '0',
  '0',
  '0',
  '0',
  '0',
  units_required,
  20,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  $a27,
  $a28,
  $a31,
  $a40,
  units_required,
  NULL,
  NULL,
  NULL,
  '$a14',
  '$a18',
  '$a51a',
  '$a16'
  from qualifications
  where replace(id,'/','') = '$a09' and id not in (select id from student_qualifications where replace(id,'/','') = '$a09' and tr_id = $tr_id);
  HEREDOC;


			  $st2 = $link->query($query);
			  if(!$st2)
				  throw new Exception(implode($link->errorInfo()).'..........'. $query . $link->errorCode());


				  }
			  }
		  }
	  }
  }

  */
/*	
 *  this will remove all elements and evidences from trees.
	
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			
			$total = 0;
			$ns = 0;
			$comp = 0;
			$behind = 0;
			
			$domElemsToRemove = array(); 
			$pageDom = new DomDocument();
			@$pageDom->loadXML($row['evidences']);

			// Recalculate percentage
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			foreach($units as $unit)
			{
				$elements = $pageDom->getElementsByTagName('element');
				foreach($elements as $element)
				{
					$element->parentNode->removeChild($element);
				}
			}
			
				
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);

			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set unitsUnderAssessment = '$total_unit_percentage', units = '$total', unitsCompleted = '$comp', unitsNotStarted = '$ns', unitsBehind = '$behind', evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());

		}
	}
}	
*/		
/*		// Update student qualifications and tr based on ILR;
		$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$xml = $row['ilr'];
				$pageDom = new DomDocument();
				@$pageDom->loadXML($xml);
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
					$s = "update student_qualifications set actual_end_date = $a31, a14 = $a14, a16 = $a16, a18 = $a18, a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";	
					$st2 = $link->query($s);
//					if(!$st2);
//						throw new Exception(implode($link->errorInfo()). $s);

					$s = "update tr set closure_date = $a31, status_code = '$a34' where id = $tr_id;";	
					$st2 = $link->query($s);
//					if(!$st2);
//						throw new Exception(implode($link->errorInfo()). $s . $link->errorCode());
					
					
				}
				$e = $pageDom->getElementsByTagName('subaim');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
					$s = "update student_qualifications set actual_end_date = $a31, a14 = $a14, a16 = $a16, a18 = $a18, a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";	
					$st2 = $link->query($s);
//					if(!$st2);
//						throw new Exception(implode($link->errorInfo()). $s);
				
				}
				
			}
		}
	}
}		
		/*		$handle = fopen("lewisham.csv","r");
		$st = fgets($handle);
		
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			if($arr[0]=='END')
				throw new Exception($gc);
			
			$firstname = trim($arr[4]);
			$surname = trim($arr[5]);
			$job_role = trim($arr[8]);
			 			
			$type = DAO::getSingleValue($link, "select type from users where type != 5 and firstnames = '$firstname' and surname = '$surname'");

			// If user exists
			if($type != '')
			{
				if($type == 3)
					$cat = "Assessor";
				elseif($type == 7)
					$cat = "Salesman";
				elseif($type == 9)
					$cat = "Supervisor";
				elseif($type == 12)
					$cat = "Viewer";
				else 
					$cat = "";
				
				// If job role was provided
				if($job_role!='')
				{
					$desc = DAO::getSingleValue($link, "select description from lookup_job_roles where description = '$job_role' and cat = '$cat'");
					if($desc!='')
					{
						$link->query("update users set job_role = '$desc' where type != 5 and firstnames = '$firstname' and surname = '$surname'");
					}
					else
					{
						$max = DAO::getSingleValue($link, "select max(id) from lookup_job_roles");
						$max++;
						DAO::execute($link, "insert into lookup_job_roles values('$max','$job_role','$cat')");
						DAO::execute($link, "update users set job_role = '$job_role' where type != 5 and firstnames = '$firstname' and surname = '$surname'");
					}
				}
			}
			
		}
	}
}
*/		
/*		$handle = fopen("nordic.csv","r");
		$st = fgets($handle);
		$user = new User();
		
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			if($arr[0]=='END')
				throw new Exception($gc);
			
			$uln = trim($arr[0]);
			$l39 = trim($arr[28]);
			$status = trim($arr[38]);
			$a31 = trim($arr[39]);
			$a09 = trim($arr[41]);
			$a34 = trim($arr[52]);
			$a35 = trim($arr[53]);
			$a40 = trim($arr[55]);
			$a50 = trim($arr[60]);
			$a53 = trim($arr[62]);

			$sql = "SELECT * FROM ilr where locate('$uln', ilr)>0 order by submission desc limit 0,1";
			$st = $link->query($sql);
			if(!$st)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql. $link->errorCode());
			while($row = $st->fetch())
			{
				$pageDom = new DomDocument();
				@$pageDom->loadXML($row['ilr']);
	
				$evidences = $pageDom->getElementsByTagName('learner');
				foreach($evidences as $evidence)
				{
					$evidence->getElementsByTagName('L39')->item(0)->nodeValue = $l39;
				}
				
				$evidences = $pageDom->getElementsByTagName('programmeaim');
				foreach($evidences as $evidence)
				{
					if($evidence->getElementsByTagName('A09')->item(0)->nodeValue==$a09)
					{
						$evidence->getElementsByTagName('A31')->item(0)->nodeValue = $a31;
						$evidence->getElementsByTagName('A34')->item(0)->nodeValue = $a34;
						$evidence->getElementsByTagName('A35')->item(0)->nodeValue = $a35;
						$evidence->getElementsByTagName('A40')->item(0)->nodeValue = $a40;
						$evidence->getElementsByTagName('A50')->item(0)->nodeValue = $a50;
						$evidence->getElementsByTagName('A53')->item(0)->nodeValue = $a53;
					}	
				}
				
				$evidences = $pageDom->getElementsByTagName('main');
				foreach($evidences as $evidence)
				{
					if($evidence->getElementsByTagName('A09')->item(0)->nodeValue==$a09)
					{
						$evidence->getElementsByTagName('A31')->item(0)->nodeValue = $a31;
						$evidence->getElementsByTagName('A34')->item(0)->nodeValue = $a34;
						$evidence->getElementsByTagName('A35')->item(0)->nodeValue = $a35;
						$evidence->getElementsByTagName('A40')->item(0)->nodeValue = $a40;
						$evidence->getElementsByTagName('A50')->item(0)->nodeValue = $a50;
						$evidence->getElementsByTagName('A53')->item(0)->nodeValue = $a53;
					}	
				}

				$evidences = $pageDom->getElementsByTagName('subaim');
				foreach($evidences as $evidence)
				{
					if($evidence->getElementsByTagName('A09')->item(0)->nodeValue==$a09)
					{
						$evidence->getElementsByTagName('A31')->item(0)->nodeValue = $a31;
						$evidence->getElementsByTagName('A34')->item(0)->nodeValue = $a34;
						$evidence->getElementsByTagName('A35')->item(0)->nodeValue = $a35;
						$evidence->getElementsByTagName('A40')->item(0)->nodeValue = $a40;
						$evidence->getElementsByTagName('A50')->item(0)->nodeValue = $a50;
						$evidence->getElementsByTagName('A53')->item(0)->nodeValue = $a53;
					}	
				}
				
				$qual = $pageDom->saveXML();
				$qual=substr($qual,21);
				
				$qual= str_replace("'","apos;",$qual);
				
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
				$tr_id = $row['tr_id'];
				
				$sql2 = "update ilr set ilr = '$qual' where tr_id=$tr_id and submission = '$submission' and contract_id = '$contract_id'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
	
			}
		}
*/
/*		$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$xml = $row['ilr'];
				$pageDom = new DomDocument();
				@$pageDom->loadXML($xml);
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
					$s = "update student_qualifications set actual_end_date = $a31, a14 = $a14,  a18 = '$a18', a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";	
					$st2 = $link->query($s);
				//	if(!$st2);
				//		throw new Exception(implode($link->errorInfo()). $s);

					$s = "update tr set closure_date = $a31, status_code = '$a34' where tr_id = $tr_id;";	
					$st2 = $link->query($s);
					
					
				}
				$e = $pageDom->getElementsByTagName('subaim');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
					$s = "update student_qualifications set actual_end_date = $a31, a14 = $a14, a18 = '$a18', a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";	
					$st2 = $link->query($s);
				//	if(!$st2);
				//		throw new Exception(implode($link->errorInfo()). $s);
				
				}
				
			}
		}
*/		
/*		$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$xml = $row['ilr'];
				$pageDom = new DomDocument();
				@$pageDom->loadXML($xml);
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
					$s = "update tr set closure_date = $a31, status_code = '$a34' where id = $tr_id;";	
					$st2 = $link->query($s);
				//	if(!$st2);
				//		throw new Exception(implode($link->errorInfo()). $s);
				}
			}
		}
	}
}
		
*/
/*		$handle = fopen("lewisham.csv","r");
		$st = fgets($handle);
		$user = new User();
		
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			if($arr[0]=='END')
				throw new Exception($gc);
			
			$legal_name = trim($arr[2]);
			$edrs = trim($arr[3]);
			$sector = trim($arr[4]);
			$account_manager = trim($arr[5]);
			$contact_name = trim($arr[6]);
			$contact_telephone = trim($arr[7]);
			$contact_mobile = trim($arr[8]);
			$contact_email = trim($arr[9]);
			$location_name = trim($arr[11]);
			$description = trim($arr[13]);
			$street = trim($arr[14]);
			$locality = trim($arr[15]);
			$town = trim($arr[16]);
			$county = trim($arr[17]);
			$postcode = trim($arr[18]);
			$telephone = trim($arr[19]);
			$fax = trim($arr[20]);
			$size = trim($arr[21]);
			
			$o = new Employer($link);
			$o->legal_name = $legal_name;
			$o->edrs = $edrs;
			$o->sector = $sector;
			$o->active = 1;
			$o->save($link);
			
			$l = new Location($link);
			$l->creator = DAO::getSingleValue($link, "select username from users where CONCAT(firstnames,' ',surname) = '$account_manager'");
			$l->contact_name = $contact_name;
			$l->contact_telephone = $contact_telephone;
			$l->contact_mobile = $contact_mobile;
			$l->contact_email = $contact_email;
			$l->full_name = $location_name;
			$l->street_description = $description;
			$l->street = $street;
			$l->locality = $locality;
			$l->town = $town;
			$l->county = $county;
			$l->postcode = $postcode;
			$l->telephone = $telephone;
			$l->fax = $fax;
			$l->organisations_id = $o->id;
			$l->save($link);
			
		}			
		fclose($handle); 
	}
}
*/
	
/*		
		$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$xml = $row['ilr'];
				$pageDom = new DomDocument();
				@$pageDom->loadXML($xml);
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
	//				$s = "update student_qualifications set actual_end_date = $a31, a14 = $a14, a16 = $a16, a18 = $a18, a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";	
	//				$st2 = $link->query($s);
					//if(!$st2);
					//	throw new Exception(implode($link->errorInfo()). $s);

					$s = "update tr set closure_date = $a31, status_code = '$a34' where tr_id = $tr_id;";	
					$st2 = $link->query($s);
					
					
				}
				$e = $pageDom->getElementsByTagName('subaim');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
					$a31 = $node->getElementsByTagName('A31')->item(0)->nodeValue;
					$a14 = $node->getElementsByTagName('A14')->item(0)->nodeValue;
					$a16 = $node->getElementsByTagName('A16')->item(0)->nodeValue;
					$a18 = $node->getElementsByTagName('A18')->item(0)->nodeValue;
					$a51a = $node->getElementsByTagName('A51a')->item(0)->nodeValue;
					$a27 = Date::toMySQL($node->getElementsByTagName('A27')->item(0)->nodeValue);
					$a28 = Date::toMySQL($node->getElementsByTagName('A28')->item(0)->nodeValue);
					$a40 = $node->getElementsByTagName('A40')->item(0)->nodeValue;
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;

					if($a31!='' && $a31!='00000000')
						$a31 = "'" . Date::toMySQL($a31) . "'";
					else
						$a31 = 'NULL';				
	
					if($a40!='' && $a40!='00000000')
						$a40 = "'" . Date::toMySQL($a40) . "'";
					else
						$a40 = 'NULL';				
						
//					$s = "update student_qualifications set actual_end_date = $a31, a14 = $a14, a16 = $a16, a18 = $a18, a51a = $a51a, start_date = '$a27', end_date = '$a28', achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";	
//					$st2 = $link->query($s);
				//	if(!$st2);
				//		throw new Exception(implode($link->errorInfo()). $s);
				
				}
				
			}
		}
	}
}		
*/		
/*		
		$sql = "SELECT * FROM ilr where contract_id = 7";
		$st = $link->query($sql);
		if(!$st)
			throw new Exception(implode($link->errorInfo()).'..........'.$sql. $link->errorCode());
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			@$pageDom->loadXML($row['ilr']);

			$evidences = $pageDom->getElementsByTagName('subaim');
			foreach($evidences as $evidence)
			{
				$evidence->parentNode->removeChild($evidence);
			}
			
			
			
			$evidences = $pageDom->getElementsByTagName('main');
			foreach($evidences as $evidence)
			{
				$evidence->getElementsByTagName('A09')->item(0)->nodeValue = '5006633X';
			}

			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$contract_id = $row['contract_id'];
			$submission = $row['submission'];
			$tr_id = $row['tr_id'];
			
			$sql2 = "update ilr set ilr = '$qual' where tr_id=$tr_id and submission = '$submission' and contract_id = '$contract_id'";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());

		}		
	}
}
			
		
		
/*		// Recalclate percentage and units status
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			
			$total = 0;
			$ns = 0;
			$comp = 0;
			$behind = 0;
			
			$domElemsToRemove = array(); 
			$pageDom = new DomDocument();
			@$pageDom->loadXML($row['evidences']);

			// Recalculate percentage
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			foreach($units as $unit)
			{
				
				$unitPercentage = $unit->getAttribute("percentage");
				$unitProportion = $unit->getAttribute('proportion');
				$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
				
				$total++;
				
				if($unitPercentage == 100)
					$comp++;
				elseif($unitPercentage > 0)
					$behind++;
				else
					$ns++;
			}
			
			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);

				
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);

			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set unitsUnderAssessment = '$total_unit_percentage', units = '$total', unitsCompleted = '$comp', unitsNotStarted = '$ns', unitsBehind = '$behind', evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());

		}
		
		*/

		
		
/*		// to set tr status looking at the ILR
		$sql = "SELECT * from tr LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id)";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$xml = $row['ilr'];
				$pageDom = new DomDocument();
				$pageDom->loadXML($xml);
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					$a34 = $node->getElementsByTagName('A34')->item(0)->nodeValue;
				}
				
				$link->query("update tr set status_code = $a34 where id='$tr_id'");
			}
		}
	}
}
*/
/*		
		$sql = "SELECT * from central.qualifications where id not in ('100/1718/5','100/1721/5','100/1958/3','100/1959/5','100/2974/6','100/4289/1','100/4906/X',
		'100/4907/1','500/1498/5','500/1552/7','500/1807/3','500/1809/7','500/2363/9','500/2662/8','500/2986/1','500/3885/0','500/3891/6','500/3935/0','500/4164/2','500/8299/1',
		'500/8300/4')";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$qid = $row['id'];
			DAO::execute($link, "insert into test values('$qid','Ok')");
			
			$evidences = $row['evidences'];	
			$xml = "<logbook xmls=\"\">\n";
			$xml .= "  <MainAttributes>\n";
			$xml .=  "    <Sector>".$row['subarea']."</Sector>\n";
			$xml .=  "    <QualFramework>".$row['qualification_type']."</QualFramework>\n";
			$xml .=  "    <MainHeader>".$row['internaltitle']."</MainHeader>\n";
			$xml .=  "    <SecondHeader />\n";
			$xml .=  "    <LogbookDate></LogbookDate>\n";
			$xml .=  "    <GenericFreeText/>\n";
			$xml .=  "  </MainAttributes>\n";
			$xml .=  "  <Qualification>\n";
			$xml .=  "    <Levels>\n";
			$xml .=  "      <Level>\n";
			$xml .=  "        <LevelName>Level ".$row['level']."</LevelName>\n";
			$xml .=  "        <Description>".$row['internaltitle']."</Description>\n";
			$xml .=  "        <QualificationsSize>\n";
			$xml .=  "          <QualificationSize>\n";
			$xml .=  "            <QualificationID></QualificationID>\n";
			$xml .=  "            <QualSizeName />\n";
			$xml .=  "            <QualificationTitle>".$row['title']."</QualificationTitle>\n";
			$xml .=  "            <QAN>".$row['id']."</QAN>\n";
			$xml .=  "            <RulesOfCombination>\n";
			$xml .=  "              <RulesOfCombDescription />\n";
			$xml .=  "              <QualificationCreditValue />\n";
			$xml .=  "              <MinimumCredit />\n";
			$xml .=  "              <MandatoryUnitCredit />\n";
			$xml .=  "              <OptionalUnitCredit />\n";
			$xml .=  "              <OthersUnitCredit />\n";
			$xml .=  "            </RulesOfCombination>\n";
			$xml .=  "            <QualStructure>\n";
			$xml .=  "              <QualStructureDescription>".$row['description']."</QualStructureDescription>\n";
			$xml .=  "              <QualUnits>\n";
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($row['evidences']));
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node) 
			{
				$xml .=  "                <QualUnit>\n";
				$xml .=  "                  <UnitStatusType>".$node->parentNode->getAttribute('title')."</UnitStatusType>\n";
				$xml .=  "                  <UnitNumber>".$node->getAttribute('reference')."</UnitNumber>\n";
				$xml .=  "                  <UnitTitle>".$node->getAttribute('title')."</UnitTitle>\n";
				$xml .=  "                  <UnitGroup />\n";
				$xml .=  "                  <UnitCode />\n";
				$xml .=  "                </QualUnit>\n";
			}
			$xml .=  "              </QualUnits>\n";
			$xml .=  "              <QualStrAdditionalInfo />\n";
			$xml .=  "            </QualStructure>\n";
			$xml .=  "            <LogbookInfo>\n";
			$xml .=  "              <text></text>\n";
			$xml .=  "              <hyperlink></hyperlink>\n";
			$xml .=  "            </LogbookInfo>\n";
			$xml .=  "          </QualificationSize>\n";
			$xml .=  "        </QualificationsSize>\n";
			$xml .=  "      </Level>\n";
			$xml .=  "    </Levels>\n";
			$xml .=  "    <GenericFreeText />\n";
			$xml .=  "  </Qualification>\n";
			$xml .=  "  <Units>\n";
			foreach($e as $node) 
			{ 
				$xml .=  "    <Unit>\n";
				$xml .=  "      <UnitID></UnitID>\n";
				$xml .=  "      <KnowledgeAtUnitLevel></KnowledgeAtUnitLevel>\n";
				$xml .=  "      <RangeAtUnitLevel></RangeAtUnitLevel>\n";
				$xml .=  "      <UnitNumber>".$node->getAttribute('reference')."</UnitNumber>\n";
				$xml .=  "      <UnitTitle>".$node->getAttribute('title')."</UnitTitle>\n";
				$xml .=  "      <UnitCode />\n";
				$xml .=  "      <UnitLevel></UnitLevel>\n";
				$xml .=  "      <CreditValue>".$node->getAttribute('credits')."</CreditValue>\n";
                $xml .=  "      <GuidedLearningHours>".$node->getAttribute('glh')."</GuidedLearningHours>\n";
                $xml .=  "      <UnitSummary>\n";
                $xml .=  "        <Unit_Aim>\n";
                $xml .=  "          <Title></Title>\n";
                $xml .=  "          <Description></Description>\n";
                $xml .=  "        </Unit_Aim>\n";
                $xml .=  "        <Unit_Introduction>\n";
                $xml .=  "          <Title></Title>\n";
                $xml .=  "          <Description />\n";
                $xml .=  "        </Unit_Introduction>\n";
                $xml .=  "      </UnitSummary>\n";
                $xml .=  "      <LearningOutcomes>\n";           
                $xml .=  "        <Title></Title>\n";
                $xml .=  "        <GenericDescription />\n";
                $assessment_requirements = '';
                $e2 = $node->getElementsByTagName('element');
				foreach($e2 as $node2) 
				{
                	$xml .=  "        <LearningOutcome>\n";
                	$xml .=  "          <Code></Code>\n";
                	$xml .=  "          <MainHeader></MainHeader>\n";
                	$xml .=  "          <AssessmentCriterias>\n";
                	$xml .=  "            <Title>".$node2->getAttribute('title')."</Title>\n";
                	$xml .=  "            <AssessmentCriteria>\n";
                	$xml .=  "              <Description>".$node2->getAttribute('description')."</Description>\n";
                	$xml .=  "              <SubItems>\n";
                	$xml .=  "                <SubItem>\n";
                	$xml .=  "                  <Description />\n";
                	$xml .=  "                </SubItem>\n";
                	$xml .=  "              </SubItems>\n";  
                	$xml .=  "              <CriteriaRequiredNumber></CriteriaRequiredNumber>\n";                
                	$xml .=  "            </AssessmentCriteria>\n";
                	$xml .=  "          </AssessmentCriterias>\n";
                	$xml .=  "          <Ranges>\n";
                	$xml .=  "            <Title></Title>\n";
                	$xml .=  "            <Range>\n";
                	$xml .=  "              <Description></Description>\n";
                	$xml .=  "              <SubItems>\n";
                	$xml .=  "                <SubItem>\n";
                	$xml .=  "                  <Description />\n";
                	$xml .=  "                </SubItem>\n";
                	$xml .=  "              </SubItems>\n";  
                	$xml .=  "            </Range>\n";
                	$xml .=  "          </Ranges>\n";
                	$xml .=  "        </LearningOutcome>\n";
                		
					$e3 = $node2->getElementsByTagName('evidence');
					$displayindex = 0;
					foreach($e3 as $node3) 
					{
              			$assessment_requirements .= "        <AssessmentRequirement>\n";
                		$assessment_requirements .= "          <Description />\n";
                		$assessment_requirements .= "          <Ranges>\n";
                		$assessment_requirements .= "            <Title>".$node3->getAttribute('title')."</Title>\n";
                		$assessment_requirements .= "            <Range>\n";
                		$assessment_requirements .= "              <Description />\n";
                		$assessment_requirements .= "              <SubItems>\n";
                		$assessment_requirements .= "                <SubItem>\n";
                		$assessment_requirements .= "                  <Description />\n";
                		$assessment_requirements .= "                </SubItem>\n";
                		$assessment_requirements .= "              </SubItems>\n";
                		$assessment_requirements .= "            </Range>\n";
                		$assessment_requirements .= "          </Ranges>\n";
                		$assessment_requirements .= "        </AssessmentRequirement>\n";
					}
				}	                
                $xml .=  "      </LearningOutcomes>\n";
                $xml .=  "      <AssessmentRequirements>\n";
                $xml .=  "        <Title>Assessment Requirements / Evidence Requirements</Title>\n";
                if ( '' != $assessment_requirements ) 
                { 
					$xml .=  $assessment_requirements; 
			}
            $xml .=  "      </AssessmentRequirements>\n";
           	$xml .=  "      <UnitEssentialGuidance />\n";
			$xml .=  "    </Unit>\n";
		}
		$xml .=  "  </Units>\n";
		$xml .=  "</logbook>\n";
		
		
		
		try
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($xml));
		}
		catch (Exception $e)
		{
			throw new Exception($xml);
		}
		
		}
	
	}
}
		
*/		
/*		
		$sql = "SELECT distinct id, internaltitle FROM central.qualifications where description = ''";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{

			$regulation_start_date = '';
			$operational_start_date = '';
			$guided_learning_hours = '';
			$credits = '';
			$subarea = '';
			$operational_end_date = '';
			$certification_end_date = '';
			$description = '';	
			$assessment_method = '';	
			
			
			$q = new RITS();
			$qual = $q->getQualification($row['id'], false);
			
			$pageDom = new DomDocument();
			@$pageDom->loadXML(utf8_encode($qual));
			$e = $pageDom->getElementsByTagName('qualification');
			foreach($e as $node)
			{
				$regulation_start_date = $node->getAttribute('regulation_start_date');
				$operational_start_date = $node->getAttribute('operational_start_date');
				$guided_learning_hours = $node->getAttribute('guided_learning_hours');
				$credits = $node->getAttribute('credits');
				$subarea = $node->getAttribute('ssa');
				$operational_end_date = $node->getAttribute('operational_end_date');
				$certification_end_date = $node->getAttribute('certification_end_date');
				$description = $node->getElementsByTagNae('description')->item(0)->nodeValue;	
				$assessment_method = $node->getElementsByTagName('assessment_method')->item(0)->nodeValue;	
			}

			$description = str_replace("'","\'",$description);

			$regulation_start_date = ($regulation_start_date=='')?'0000-00-00':$regulation_start_date;
			$operational_start_date = ($operational_start_date=='')?'0000-00-00':$operational_start_date;
			$operational_end_date = ($operational_end_date=='')?'0000-00-00':$operational_end_date;
			$certification_end_date = ($certification_end_date=='')?'0000-00-00':$certification_end_date;
			
						
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			
			$sql2 = "update central.qualifications set regulation_start_date = '$regulation_start_date', operational_start_date='$operational_start_date', guided_learning_hours = '$guided_learning_hours', subarea = '$subarea', operational_end_date = '$operational_end_date', certification_end_date = '$certification_end_date', description = '$description', assessment_method = '$assessment_method'  where id = '$id' and internaltitle = '$internaltitle'";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
		
		pre("process complete");
	}
}
		


*/		
		
/*		
		$handle = fopen("quals.csv","r");
		$st = fgets($handle);
		while(!feof($handle))
		{
			$qan = fgets($handle);
			$ndaq = new NDAQ2();
			$xml = $ndaq->getQualification($qan, 2);
			$pageDom = new DomDocument();
			@$pageDom->loadXML(utf8_encode($xml));
			$e = $pageDom->getElementsByTagName('qualification');
			foreach($e as $node)
			{
				$internaltitle = addslashes((string)$node->getAttribute('title'));
				$title = addslashes((string)$internaltitle);
				$qualification_type = addslashes((string)$node->getAttribute('type'));
				$id = $node->getAttribute('reference');
				$level = addslashes((string)$node->getAttribute('level'));
				$awarding_body = addslashes((string)$node->getAttribute('awarding_body'));
				$accreditation_start_date = $node->getAttribute('accreditation_start_date');
				$operational_centre_start_date = ($node->getAttribute('operational_centre_start_date')!='')?'"'.$node->getAttribute('operational_centre_start_date').'"':"NULL";
				$accreditation_end_date = $node->getAttribute('accreditation_end_date');
				$certification_end_date = $node->getAttribute('certification_end_date');
				$dfes_approval_start_date = ($node->getAttribute('dfes_approval_start_date')!='')? '"' . $node->getAttribute('dfes_approval_start_date') . '"':"NULL";
				$dfes_approval_end_date = ($node->getAttribute('dfes_approval_end_date')!='')?'"' . $node->getAttribute('dfes_approval_end_date') . '"':"NULL";
				$mainarea = $node->getAttribute('mainarea');
				$subarea = $node->getAttribute('subarea');
				$description = addslashes((string)$node->getElementsByTagName('description')->item(0)->nodeValue);
				$assessment_method = addslashes((string)$node->getElementsByTagName('assessment_method')->item(0)->nodeValue);
				$structure = addslashes((string)$node->getElementsByTagName('structure')->item(0)->nodeValue);
			}
			$evidences = substr($xml,strpos($xml,"<root>"),(strpos($xml,"</root>")-strpos($xml,"<root>"))) . "</root>";
			
			// Build tree 
			$total_units = 0;
			if($evidences=='</root>')
			{
				$xml = '<root percentage="0"></root>';
			}
			else
			{
				$pageDom->loadXML(utf8_encode($evidences));
				$xml='<root percentage="0">';	
				$unitgroups = $pageDom->getElementsByTagName('units');
				foreach($unitgroups as $unitgroup)
				{
					$xml .= '<units title="' . $unitgroup->getAttribute('title') . '">';			
					$units = $unitgroup->getElementsByTagName('unit');
					foreach($units as $unit)
					{
						$total_units++;
						$xml .= '<unit reference="' . $unit->getAttribute('reference');
						$xml .= '" proportion="0';
						$xml .= '" percentage="0';
						$xml .= '" mandatory="null';
						$xml .= '" chosen="true';
						$xml .= '" title="' . addslashes((string)$unit->getAttribute('title'));
						$xml .= '" owner_reference="' . $unit->getAttribute('owner_reference');
						$xml .= '" credits="' . $unit->getAttribute('credits') . '">';
						$xml .= '<element title="' . addslashes((string)$unit->getAttribute('title')) . '"><description>no description</description><evidence title="' . addslashes((string)$unit->getAttribute('title')) . '"></evidence></element>';
						$xml .= '\n</unit>';
					}
					$xml .= '</units>';
				}
				$xml .= '</root>';
			}

			$sql2 = "insert into qualifications values('$id','','$awarding_body','$title','$description','$assessment_method','$structure','$level','$qualification_type','$accreditation_start_date',$operational_centre_start_date,'$accreditation_end_date','$certification_end_date',$dfes_approval_start_date,$dfes_approval_end_date,'$xml','$total_units','$internaltitle','0','$total_units','0','0','0','am_demo2','$mainarea','$subarea','1','0')";
			$st = $link->query($sql2);
			if(!$st)
			//	throw new Exception($sql2);
				throw new Exception($sql2 . implode($link->errorInfo()), $link->errorCode());
		}
	}
}	
*/		
/*		
		$sql = "SELECT * FROM student_qualifications where id='100/0798/2'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($row['evidences']));
			$e = $pageDom->getElementsByTagName('evidence');
			foreach($e as $node)
			{

				
				if($node->getAttribute('title')=="A.1.1 Know how to Discuss")
				{	
					throw new Exception($node->getAttribute('title'));
					$node->setAttribute('title',"A.1.1 Know how to Interpret Information");
				}
				if($node->getAttribute('title')=="A.1.2 Know how to Read and Obtain Information")
				{	
					$node->setAttribute('title',"A.1.2 Know how to carry out Calculations");
				}
				if($node->getAttribute('title')=="A.1.3 Know how to Write Documents")
				{	
					$node->setAttribute('title',"A.1.3 Know how to interpret results and present your findings");
				}
			}	
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
		
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
*/	
		// Global update on a field of ILR
/*
		$sql = "select * from ilr where contract_id in (7) and submission = 'W01'";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{	
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$L03 = $row['L03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
		
				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					//if($node->getElementsByTagName('A10')->item(0)->nodeValue=='45')
						$node->getElementsByTagName('A70')->item(0)->nodeValue = "SFWM";
				}

				$e = $pageDom->getElementsByTagName('subaim');
				foreach($e as $node)
				{
					$node->getElementsByTagName('A70')->item(0)->nodeValue = "SFWM";
				}
				
				$ilr = $pageDom->saveXML();
		
				$ilr=substr($ilr,21);
				$ilr = str_replace("'", "&apos;" , $ilr);
				
				//throw new Exception($ilr);
				
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
				
			}
		}
	}
}
				
*/

/*		
		// Garys dates sync script
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$sql2 = "SELECT * FROM ilr where tr_id = {$row['id']}";
				$st2 = $link->query($sql2);
				if($st2) 
				{
					while($row2 = $st2->fetch())
					{

						$submission = $row2['submission'];
						$contract_id = $row2['contract_id'];
						$tr_id = $row2['tr_id'];
						$l03 = $row2['L03'];
						$xml = $row2['ilr'];

						
						
						$pageDom = new DomDocument();
						$pageDom->loadXML($xml);
						
						$e = $pageDom->getElementsByTagName('programmeaim');
						foreach($e as $node)
						{
							$node->getElementsByTagName('A27')->item(0)->nodeValue = Date::toShort($row['start_date']);
							$node->getElementsByTagName('A28')->item(0)->nodeValue = Date::toShort($row['target_date']);
							$node->getElementsByTagName('A31')->item(0)->nodeValue = Date::toShort($row['closure_date']);
							$node->getElementsByTagName('A40')->item(0)->nodeValue = Date::toShort($row['closure_date']);
						}
		
						$e = $pageDom->getElementsByTagName('main');
						foreach($e as $node)
						{
								
							$node->getElementsByTagName('A27')->item(0)->nodeValue = Date::toShort($row['start_date']);
							$node->getElementsByTagName('A28')->item(0)->nodeValue = Date::toShort($row['target_date']);
							$node->getElementsByTagName('A31')->item(0)->nodeValue = Date::toShort($row['closure_date']);
							$node->getElementsByTagName('A40')->item(0)->nodeValue = Date::toShort($row['closure_date']);
						}
						
						$e = $pageDom->getElementsByTagName('subaim');
						foreach($e as $node)
						{
								
							$node->getElementsByTagName('A27')->item(0)->nodeValue = Date::toShort($row['start_date']);
							$node->getElementsByTagName('A28')->item(0)->nodeValue = Date::toShort($row['target_date']);
							$node->getElementsByTagName('A31')->item(0)->nodeValue = Date::toShort($row['closure_date']);
							$node->getElementsByTagName('A40')->item(0)->nodeValue = Date::toShort($row['closure_date']);
						}
						
						$ilr = $pageDom->saveXML();
				
						$ilr=substr($ilr,21);
						$ilr = str_replace("'", "&apos;" , $ilr);
						
						$sql3 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and L03 = '$l03'";
						$st3 = $link->query($sql3);			
						if(!$st3)
							throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
						
					}
				}
			}
		}
	}
}
		
*/		
		
		
/*		
		// Fareham enrolment out of batch file

		$edrs = '';
		$handle = fopen("fareham","r");
		$st = fgets($handle);
		$ccc = 0;
		$aims = Array();
		$tr_id=0;
		while(!feof($handle))
		{
			$st = fgets($handle);
 
			if(trim(substr($st,20,2))=='10')
			{

				$surname = addslashes(trim(substr($st,27,20)));
				$firstname = addslashes(trim(substr($st,47,40)));
				$contract = trim(substr($st,256,3));
				$l03 = substr($st,8,12);
				$st = fgets($handle);
				if(trim(substr($st,20,2))=='35')
					$st = fgets($handle);
				$course_title = trim(substr($st,193,24));
				$start_date = substr($st,80,2) . "/" . substr($st,82,2) . "/" . substr($st,84,4);
				$end_date = substr($st,88,2) . "/" . substr($st,90,2) . "/" . substr($st,92,4);

				
		$username = DAO::getSingleValue($link, "select username from users where firstnames = '$firstname' and surname = '$surname'");
		$course_id = DAO::getSingleValue($link, "select id from courses where title = '$course_title'");
		$framework_id = DAO::getSingleValue($link, "select framework_id from courses where title = '$course_title'");
		$group_id = '';
		if($contract=='270')
			$contract_id = 1;
		else
			$contract_id = 2;

		if($username!='' && $course_id!='')
		{	
			
			
		$sd = Date::toMySQL($start_date);
		$ed = Date::toMySQL($end_date);
		
		$user = User::loadFromDatabase($link, $username);
		$course = Course::loadFromDatabase($link, $course_id);

		$que = "select id from locations where organisations_id='$course->organisations_id'";
		$location_id = trim(DAO::getSingleValue($link, $que));
		
		$provider = Location::loadFromDatabase($link, $location_id);

//		$l03 = DAO::getSingleValue($link, "select l03 from tr where username = '$username'");
//		if($count>0)
//			throw new Exception("The Learner "  . " " . $user->surname . " has already been enrolled and active");

		$link->beginTransaction();
		try
		{
			
			
		// Create training record 
		$tr = new TrainingRecord();
		$tr->populate($user, true);
		$tr->contract_id = $contract_id;
		$tr->start_date = $start_date;
		$tr->target_date = $end_date;
		$tr->status_code = 1;
		$tr->provider_id = $course->organisations_id;
		$tr->provider_location_id = $location_id;
		$tr->provider_saon_start_number = $provider->saon_start_number; 
		$tr->provider_saon_start_suffix = $provider->saon_start_suffix; 
		$tr->provider_saon_end_number = $provider->saon_end_number;
		$tr->provider_saon_end_suffix = $provider->saon_end_suffix; 
		$tr->provider_saon_description = $provider->saon_description;
		$tr->provider_paon_start_number = $provider->paon_start_number; 
		$tr->provider_paon_start_suffix = $provider->paon_start_suffix; 
		$tr->provider_paon_end_number = $provider->paon_end_number;
		$tr->provider_paon_end_suffix = $provider->paon_end_suffix;
		$tr->provider_paon_description = $provider->paon_description; 
		$tr->provider_street_description = $provider->street_description;
		$tr->provider_locality = $provider->locality;
		$tr->provider_town = $provider->town;
		$tr->provider_county = $provider->county;
		$tr->provider_postcode = $provider->postcode; 
		$tr->provider_telephone = $provider->telephone;
		$tr->ethnicity = $user->ethnicity;
		$tr->work_experience = 1;

		if($l03=='')
		{		
			$l03 = (int)DAO::getSingleValue($link, "select max(cast(l03 as unsigned)) from tr");
			$l03 += 1;
		}
		
		$tr->l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
		$tr->save($link); 	

		$tr_id = $tr->id;
		$identity = $user->getFullyQualifiedName();
		
		// Enrol on a course and put in a  group
		
		$framework_id = '0';
		$qualification_id = '0';
		
		if($tr_id=='' || $course_id=='')	
		{
			throw new Exception("Could not enrol on a course! insufficient information given");	
		}
		
		$que = "select main_qualification_id from courses where id='$course_id'";
		$qualification_id = DAO::getSingleValue($link, $que);
		
		if($qualification_id=='')
		{
			$qualification_id  = '0';
			$que = "select framework_id from courses where id='$course_id'";
			$framework_id = DAO::getSingleValue($link, $que);
			$fid = $framework_id;
		}
		
// enroling on a course
$query = <<<HEREDOC
insert into
	courses_tr
values($course_id, $tr_id, '$qualification_id', $framework_id);
HEREDOC;
		$st = $link->query($query);
		if($st== false)
		{
			throw new Exception("Could not enrol on this course " . implode($link->errorInfo()));
		}


		
		
if($group_id!='')		
{		
// 	attaching to a group
$query = <<<HEREDOC
insert into
	group_members
values($group_id, $tr_id, 0);
HEREDOC;
		$st = $link->query($query);
		if($st == false)
		{
			throw new Exception("Could not put on this group" . implode($link->errorInfo()));
		}
}
		
		
// Check if this course has a framework attached to it and get framework id
$que = "select framework_id from courses where id='$course_id'";
$fid = DAO::getSingleValue($link, $que);

$que = "select id from student_frameworks where tr_id='$tr_id'";
$tr_framework_id = DAO::getSingleValue($link, $que);

if($fid!='')
{
	if($tr_framework_id=='')
	{
		
// Importing framework
$query = <<<HEREDOC
insert into
	student_frameworks
select title, id, '$tr_id', sector, comments, duration_in_months
from frameworks
	where id = '$fid';
HEREDOC;
		$st = $link->query($query);
		if($st == false)
		{
			throw new Exception("Could not copy framework " . implode($link->errorInfo()));
		}

// importing qualification from framework		
$query = <<<HEREDOC
insert into
	student_qualifications
select 
id, 
'$fid', 
'$tr_id', 
framework_qualifications.internaltitle, 
lsc_learning_aim, 
awarding_body, 
title, 
description, 
assessment_method, 
structure, 
level, 
qualification_type, 
accreditation_start_date, 
operational_centre_start_date, 
accreditation_end_date, 
certification_end_date, 
dfes_approval_start_date, 
dfes_approval_end_date, 
evidences, 
units,
'0',
'0',
'0',
'0',
'0',
units_required, 
proportion, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
'$sd', 
'$ed', 
NULL, 
NULL, 
units_required,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL 
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and 
course_qualifications_dates.framework_id = framework_qualifications.framework_id and 
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id';
HEREDOC;

		$st = $link->query($query);
		if($st== false)
		{
			throw new Exception("Could not copy framework qualifications " . implode($link->errorInfo()));
		}
		
		// Creating milestones
		$sql = "SELECT *, PERIOD_DIFF(CONCAT(LEFT(end_date,4),MID(end_date,6,2)),CONCAT(LEFT(start_date,4),MID(start_date,6,2))) as months FROM student_qualifications where tr_id = $tr_id";
		$st = $link->query($sql);
		$unit=0;
		while($row = $st->fetch())
		{
			$xml = utf8_encode($row['evidences']);
			
			$pageDom = new DomDocument();
			$pageDom->loadXML($xml);

			
			$evidences = $pageDom->getElementsByTagName('unit');
			foreach($evidences as $evidence)
			{
				$unit_id = $evidence->getAttribute('owner_reference');
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				$qualification_id = $row['id'];
				$internaltitle = $row['internaltitle'];

				$m = Array();
				for($a = 1; $a<=$row['months']; $a++)
				{
					if($a==$row['months'])
						$m[] = 100;
					else
						$m[] = sprintf("%.1f", 100 / $row['months'] * $a);
				}
				for($a = $row['months']+1; $a<=36; $a++)
				{
					$m[] = 100;
				}
				
				DAO::execute($link, "insert into student_milestones values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
			}
		}
	}
}


			// Creating ILR
			$sql = "SELECT users.ni, users.l24, users.l14, users.l15, users.l16, users.l35, users.l48, users.l42a, users.l42b, contract_holder.upin, users.uln, l03, tr.l28a, tr.l28b, tr.l34a, tr.l34b, tr.l34c, tr.l34d, tr.l36, tr.l37, tr.l39, tr.l40a, tr.l40b, tr.l41a, tr.l41b, tr.l47, tr.id, tr.surname, tr.firstnames, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date, tr.ethnicity, tr.gender, learning_difficulties, disability,learning_difficulty, tr.home_postcode, TRIM(CONCAT(IF(tr.home_paon_start_number IS NOT NULL,TRIM(tr.home_paon_start_number),''),IF(tr.home_paon_start_suffix IS NOT NULL,TRIM(tr.home_paon_start_suffix),''),' ', IF(tr.home_paon_end_number IS NOT NULL,TRIM(tr.home_paon_end_number),''),IF(tr.home_paon_end_suffix IS NOT NULL,TRIM(tr.home_paon_end_suffix),''),' ' , IF(tr.home_paon_description IS NOT NULL,TRIM(tr.home_paon_description),''),' ',IF(tr.home_street_description IS NOT NULL,TRIM(tr.home_street_description),''))) AS L18, tr.home_locality, tr.home_town,tr.home_county, current_postcode, tr.home_telephone, country_of_domicile, tr.ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date, status_code,provider_location_id, tr.employer_id, provider_location.postcode AS lpcode, organisations.edrs AS edrs, organisations.legal_name AS employer_name,employer_location.postcode AS epcode FROM tr LEFT JOIN locations AS provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations AS employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN organisations AS contract_holder ON contract_holder.id = contract_holder LEFT JOIN users ON users.username = tr.username WHERE contract_id = '$contract_id' AND tr.id = '$tr_id';";

			$contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id='$contract_id'");

			$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' order by last_submission_date LIMIT 1;");
			
			$ilrtemplate = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");
			
			if($ilrtemplate!='')
			{
				$ilrtemplate = Ilr2009::loadFromXML($ilrtemplate);
			}
			
			$st = $link->query($sql);
			if($st)
			{

				while($row = $st->fetch())
				{	
					// here to create ilrs for the first time from training records.					
					$xml = '<ilr>';
					$xml .= "<learner>";
					$xml .= "<L01>" . $row['upin'] . "</L01>";
					$xml .= "<L02>" . "99" . "</L02>";
					$xml .= "<L03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</L03>";
					$xml .= "<L04>" . "10" . "</L04>";

					// No of learning aim data sets
					$sql ="select COUNT(*) from student_qualifications where tr_id ={$row['id']}";
					$learning_aims = DAO::getResultset($link,$sql);
					
					$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
					$xml .= "<L06>" . "00" . "</L06>";
					$xml .= "<L07>" . "00" . "</L07>";
					if($row['status_code']==4 || $row['status_code']=='4')
						$xml .= "<L08>" . "Y" . "</L08>";
					else
						$xml .= "<L08>" . "N" . "</L08>";
					$xml .= "<L09>" . $row['surname'] . 				"</L09>";
					$xml .= "<L10>" . $row['firstnames'] . 				"</L10>";
					$xml .= "<L11>" . $row['date_of_birth'] . 			"</L11>";
					$xml .= "<L12>" . $row['ethnicity'] . 				"</L12>";
					$xml .= "<L13>" . $row['gender'] . 					"</L13>";

					if(isset($ilrtemplate->learnerinformation->L14))
						$xml .= "<L14>" . $ilrtemplate->learnerinformation->L14 . "</L14>";
					else
						$xml .= "<L14>" . $row['l14'] .	"</L14>";

					if(isset($ilrtemplate->learnerinformation->L15))
						$xml .= "<L15>" . $ilrtemplate->learnerinformation->L15 . "</L15>";
					else
						$xml .= "<L15>" . $row['l15'] . 				"</L15>";

					if(isset($ilrtemplate->learnerinformation->L16))
						$xml .= "<L16>" . $ilrtemplate->learnerinformation->L16 . "</L16>";
					else
						$xml .= "<L16>" . $row['l16'] .		"</L16>";

					$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";
					$xml .= "<L18>" . $row['L18'] . "</L18>";
					$xml .= "<L19>" . $row['home_locality'] . 			"</L19>";
					$xml .= "<L20>" . $row['home_town'] . 				"</L20>";
					$xml .= "<L21>" . $row['home_county'] . 			"</L21>";
					$xml .= "<L22>" . $row['home_postcode'] .		"</L22>";
					$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";

					if(isset($ilrtemplate->learnerinformation->L24))
						$xml .= "<L24>" . $ilrtemplate->learnerinformation->L24 . "</L24>";
					else
						$xml .= "<L24>" . $row['l24'] .		"</L24>";
										
					$xml .= "<L25>" . "</L25>";
					$xml .= "<L26>" . $row['ni'] . 						"</L26>";
					$xml .= "<L27>" . "1" . "</L27>";

					if(isset($ilrtemplate->learnerinformation->L28a))
						$xml .= "<L28a>" . $ilrtemplate->learnerinformation->L28a . "</L28a>";
					else
						$xml .= "<L28a>" . $row['l28a'] . "</L28a>";

					if(isset($ilrtemplate->learnerinformation->L28b))
						$xml .= "<L28b>" . $ilrtemplate->learnerinformation->L28b . "</L28b>";
					else
						$xml .= "<L28b>" . $row['l28b'] . "</L28b>";

					$xml .= "<L29>" . "00" . "</L29>";
					$xml .= "<L31>" . "000000" . "</L31>";
					$xml .= "<L32>" . "00" . "</L32>";
					$xml .= "<L33>" . "0.0000" . "</L33>";

					if(isset($ilrtemplate->learnerinformation->L34a))
						$xml .= "<L34a>" . $ilrtemplate->learnerinformation->L34a . "</L34a>";
					else
						$xml .= "<L34a>" . $row['l34a'] . "</L34a>";

					if(isset($ilrtemplate->learnerinformation->L34b))
						$xml .= "<L34b>" . $ilrtemplate->learnerinformation->L34b . "</L34b>";
					else
						$xml .= "<L34b>" . $row['l34b'] . "</L34b>";

					if(isset($ilrtemplate->learnerinformation->L34c))
						$xml .= "<L34c>" . $ilrtemplate->learnerinformation->L34c . "</L34c>";
					else
						$xml .= "<L34c>" . $row['l34c'] . "</L34c>";
					
					if(isset($ilrtemplate->learnerinformation->L34d))
						$xml .= "<L34d>" . $ilrtemplate->learnerinformation->L34d . "</L34d>";
					else
						$xml .= "<L34d>" . $row['l34d'] . "</L34d>";
					
					$xml .= "<L35>" . $row['l35'] .	"</L35>";
					$xml .= "<L36>" . $row['l36'] . "</L36>";
					
					if(isset($ilrtemplate->learnerinformation->L37))
						$xml .= "<L37>" . $ilrtemplate->learnerinformation->L37 . "</L37>";
					else
						$xml .= "<L37>" . $row['l37'] . "</L37>";
					
					$xml .= "<L38>" . "00" . "</L38>";

					if(isset($ilrtemplate->learnerinformation->L39))
						$xml .= "<L39>" . $ilrtemplate->learnerinformation->L39 . "</L39>";
					else
						$xml .= "<L39>" . $row['l39'] . "</L39>";

					if(isset($ilrtemplate->learnerinformation->L40a))
						$xml .= "<L40a>" . $ilrtemplate->learnerinformation->L40a . "</L40a>";
					else
						$xml .= "<L40a>" . $row['l40a'] . "</L40a>";
					
					if(isset($ilrtemplate->learnerinformation->L40b))
						$xml .= "<L40b>" . $ilrtemplate->learnerinformation->L40b . "</L40b>";
					else
						$xml .= "<L40b>" . $row['l40b'] . "</L40b>";

					$xml .= "<L41a>" . $row['l41a'] . "</L41a>";	
					$xml .= "<L41b>" . $row['l41b'] . "</L41b>";	
					$xml .= "<L42a>" . $row['l42a'] . "</L42a>";	
					$xml .= "<L42b>" . $row['l42b'] . "</L42b>";	
					$xml .= "<L44>" . "</L44>";
//					$xml .= "<L45>" . $row['uln'] . "</L45>";	
					$xml .= "<L45>9999999999</L45>";	
					$xml .= "<L46>" . "</L46>";
					
					if(isset($ilrtemplate->learnerinformation->L47))
						$xml .= "<L47>" . $ilrtemplate->learnerinformation->L47 . "</L47>";
					else
						$xml .= "<L47>" . $row['l47'] . "</L47>";
					
					$xml .= "<L48>" .  "</L48>";
					$xml .= "<L49a>00</L49a>";
					$xml .= "<L49b>00</L49b>";
					$xml .= "<L49c>00</L49c>";
					$xml .= "<L49d>00</L49d>";
										
					// Getting no. of sub aims
					$sql ="select count(*) from student_qualifications where tr_id ={$row['id']} and qualification_type!='NVQ'";
					$sub_aims = DAO::getSingleValue($link,$sql);
					
					$xml .= "<subaims>" . $sub_aims . "</subaims>";
					$xml .= "</learner>";
					$xml .= "<subaims>" . $sub_aims . "</subaims>";

					// Creating Programme aim
					$xml .= "<programmeaim>";

					if(isset($ilrtemplate->programmeaim->A02))
						$xml .= "<A02>" . $ilrtemplate->programmeaim->A02 . "</A02>";
					else
						$xml .= "<A02>" . "99" . "</A02>";
					
					if(isset($ilrtemplate->programmeaim->A04))
						$xml .= "<A04>" . $ilrtemplate->programmeaim->A04 . "</A04>";
					else
						$xml .= "<A04>" . "35" . "</A04>";
					
					if(isset($ilrtemplate->programmeaim->A09))
						$xml .= "<A09>" . $ilrtemplate->programmeaim->A09 . "</A09>";
					else
						$xml .= "<A09>" . "ZPROG001" . "</A09>";
					
					if(isset($ilrtemplate->programmeaim->A10))
						$xml .= "<A10>" . $ilrtemplate->programmeaim->A10 . "</A10>";
					else
						$xml .= "<A10>" . "45" . "</A10>";
					
					if(isset($ilrtemplate->programmeaim->A15))
						$xml .= "<A15>" . $ilrtemplate->programmeaim->A15 ."</A15>";
					else
						$xml .= "<A15>" . "</A15>";
					
					if(isset($ilrtemplate->programmeaim->A16))
						$xml .= "<A16>" . $ilrtemplate->programmeaim->A16 . "</A16>";
					else
						$xml .= "<A16>" . "</A16>";
					
					if(isset($ilrtemplate->programmeaim->A26))
						$xml .= "<A26>" . $ilrtemplate->programmeaim->A26 . "</A26>";
					else
						$xml .= "<A26>" . "</A26>";
					
					$xml .= "<A27>" . $start_date . "</A27>";
					$xml .= "<A28>" . $end_date . "</A28>";
					$xml .= "<A23>" . $tr->work_postcode . "</A23>";
					$xml .= "<A51a>100</A51a>";

					if(isset($ilrtemplate->programmeaim->A14))
						$xml .= "<A14>" . $ilrtemplate->programmeaim->A14 ."</A14>";
					else
						$xml .= "<A14>" . "</A14>";
					
					if(isset($ilrtemplate->programmeaim->A46a))
						 $xml .= "<A46a>" . "</A46a>";
					else
						 $xml .= "<A46a>" . "</A46a>";
					
					if(isset($ilrtemplate->programmeaim->A46b))
						 $xml .= "<A46b>" . $ilrtemplate->programmeaim->A46b . "</A46b>";
					else
						 $xml .= "<A46b>" . "</A46b>";
					
					if(isset($ilrtemplate->programmeaim->A31))
						 $xml .= "<A31>" . $ilrtemplate->programmeaim->A31 . "</A31>";
					else
						 $xml .= "<A31>" . "</A31>";
					
					if(isset($ilrtemplate->programmeaim->A40))
						 $xml .= "<A40>" . $ilrtemplate->programmeaim->A40 . "</A40>";
					else
						 $xml .= "<A40>" . "</A40>";
					
					if(isset($ilrtemplate->programmeaim->A34))
						 $xml .= "<A34>" . $ilrtemplate->programmeaim->A34 . "</A34>";
					else
						 $xml .= "<A34>" . "</A34>";
					
					if(isset($ilrtemplate->programmeaim->A35))
						 $xml .= "<A35>" . $ilrtemplate->programmeaim->A35 . "</A35>";
					else
						 $xml .= "<A35>" . "</A35>";
					
					if(isset($ilrtemplate->programmeaim->A50))
						 $xml .= "<A50>" . $ilrtemplate->programmeaim->A50 . "</A50>";
					else
						 $xml .= "<A50>" . "</A50>";
					
					$xml .= "</programmeaim>";
					
					
					// Creating main aim					
					$sql_main = "select student_qualifications.*, tr.start_date as lsd,tr.work_postcode, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']} and framework_qualifications.main_aim=1";
					$st2 = $link->query($sql_main);
					if($st2)
					{
						while($row_main = $st2->fetch())
						{	
							$xml .= "<main>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";

							if(isset($ilrtemplate->aims[0]->A02))
								$xml .= "<A02>" . $ilrtemplate->aims[0]->A02 . "</A02>";
							else
								$xml .= "<A02>99</A02>";
							
							$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</A03>";

							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . "01" . "</A05>";
							$xml .= "<A06>" . "00" . "</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_main['id']) . "</A09>";

							if(isset($ilrtemplate->aims[0]->A10))
								$xml .= "<A10>" . $ilrtemplate->aims[0]->A10 . "</A10>";
							else
								$xml .= "<A10>" . "</A10>";
							
							$xml .= "<A11a>" . "000" . "</A11a>";
							$xml .= "<A11b>" . "000" . "</A11b>";
							$xml .= "<A12a>" . "000" . "</A12a>";
							$xml .= "<A12b>" . "000" . "</A12b>";
							$xml .= "<A13>" . "00000" . "</A13>";
							
							if(isset($ilrtemplate->aims[0]->A14))
								$xml .= "<A14>" . $ilrtemplate->aims[0]->A14 . "</A14>";
							else
								$xml .= "<A14>" . "00" . "</A14>";
							
							if(isset($ilrtemplate->aims[0]->A15))
								$xml .= "<A15>" . $ilrtemplate->aims[0]->A15 . "</A15>";
							else
								$xml .= "<A15>" . "</A15>";
							
							if(isset($ilrtemplate->aims[0]->A16))
								$xml .= "<A16>" . $ilrtemplate->aims[0]->A16 . "</A16>";
							else
								$xml .= "<A16>" . "</A16>";
							
							$xml .= "<A17>" . "0" . "</A17>";

							if(isset($ilrtemplate->aims[0]->A18))
								$xml .= "<A18>" . $ilrtemplate->aims[0]->A18 . "</A18>";
							else
								$xml .= "<A18>" . "</A18>";
							
							$xml .= "<A19>" . "0" . "</A19>";
							$xml .= "<A20>" . "0" . "</A20>";
							
							if(isset($ilrtemplate->aims[0]->A21))
								$xml .= "<A21>" . $ilrtemplate->aims[0]->A21 . "</A21>";
							else
								$xml .= "<A21>" . "00" . "</A21>";
							
							if(isset($ilrtemplate->aims[0]->A22))
								$xml .= "<A22>" . $ilrtemplate->aims[0]->A22 . "</A22>";
							else
								$xml .= "<A22>" . "      " . "</A22>";
							
							$xml .= "<A23>" . $row_main['work_postcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";

							if(isset($ilrtemplate->aims[0]->A26))
								$xml .= "<A26>" . $ilrtemplate->aims[0]->A26 . "</A26>";
							else
								$xml .= "<A26>" . "</A26>";
							
								$xml .= "<A27>" . substr($row_main['lsd'],8,2) . '/' . substr($row_main['lsd'],5,2) . '/' . substr($row_main['lsd'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_main['led'],8,2) . '/' . substr($row_main['led'],5,2) . '/' . substr($row_main['led'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_main['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";

							if(isset($ilrtemplate->aims[0]->A34))
								$xml .= "<A34>" . $ilrtemplate->aims[0]->A34 . "</A34>";
							else
								$xml .= "<A34>" . "</A34>";
							
							if(isset($ilrtemplate->aims[0]->A35))
								$xml .= "<A35>" . $ilrtemplate->aims[0]->A35 . "</A35>";
							else
								$xml .= "<A35>" . "</A35>";
							
							if(isset($ilrtemplate->aims[0]->A36))
								$xml .= "<A36>" . $ilrtemplate->aims[0]->A36 . "</A36>";
							else
								$xml .= "<A36>" . "   " . "</A36>";
							
							$xml .= "<A37>" . $row_main['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_main['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_main['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";

							if($row['edrs']!='')
								$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
							else
								$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
							
							$xml .= "<A45>" . $row['epcode'] . "</A45>";

							if(isset($ilrtemplate->aims[0]->A46a))
								$xml .= "<A46a>" . $ilrtemplate->aims[0]->A46a . "</A46a>";
							else
								$xml .= "<A46a>" . "</A46a>";
							
							if(isset($ilrtemplate->aims[0]->A46b))
								$xml .= "<A46b>" . $ilrtemplate->aims[0]->A46b . "</A46b>";
							else
								$xml .= "<A46b>" . "</A46b>";
							
							if(isset($ilrtemplate->aims[0]->A47a))
								$xml .= "<A47a>" . $ilrtemplate->aims[0]->A47a . "</A47a>";
							else
								$xml .= "<A47a>" . "</A47a>";
							
							if(isset($ilrtemplate->aims[0]->A47b))
								$xml .= "<A47b>" . $ilrtemplate->aims[0]->A47b . "</A47b>";
							else
								$xml .= "<A47b>" . "</A47b>";
							
							if(isset($ilrtemplate->aims[0]->A48a))
								$xml .= "<A48a>"  . $ilrtemplate->aims[0]->A48a . "</A48a>";
							else
								$xml .= "<A48a>" . "</A48a>";
							
							if(isset($ilrtemplate->aims[0]->A48b))
								$xml .= "<A48b>" . $ilrtemplate->aims[0]->A48b . "</A48b>";
							else
								$xml .= "<A48b>" . "</A48b>";
							
							if(isset($ilrtemplate->aims[0]->A49))
								$xml .= "<A49>" . $ilrtemplate->aims[0]->A49 . "</A49>";
							else
								$xml .= "<A49>" . "     " . "</A49>";
							
							if(isset($ilrtemplate->aims[0]->A50))
								$xml .= "<A50>" . $ilrtemplate->aims[0]->A50 .  "</A50>";
							else
								$xml .= "<A50>" . "</A50>";
							
							$xml .= "<A51a>100</A51a>";
							$xml .= "<A52>" . "0.000" . "</A52>";
							if(isset($ilrtemplate->aims[0]->A53))
								$xml .= "<A53>" . $ilrtemplate->aims[0]->A53 . "</A53>";
							else
								$xml .= "<A53>" . "</A53>";
							
							if(isset($ilrtemplate->aims[0]->A54))
								$xml .= "<A54>" . $ilrtemplate->aims[0]->A54 . "</A54>";
							else
								$xml .= "<A54>" . "</A54>";
							
							$xml .= "<A55>9999999999</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
							$xml .= "<A58>" . "</A58>";

							if(isset($ilrtemplate->aims[0]->A59))
								$xml .= "<A59>" . $ilrtemplate->aims[0]->A59 . "</A59>";
							else
								$xml .= "<A59>" . "</A59>";
								
							if(isset($ilrtemplate->aims[0]->A60))
								$xml .= "<A60>" . $ilrtemplate->aims[0]->A60 . "</A60>";
							else
								$xml .= "<A60>" . "</A60>";
								
							if(isset($ilrtemplate->aims[0]->A61))
								$xml .= "<A61>" . $ilrtemplate->aims[0]->A61 . "</A61>";
							else
								$xml .= "<A61>" . "</A61>";
							
							if(isset($ilrtemplate->aims[0]->A62))
								$xml .= "<A62>" . $ilrtemplate->aims[0]->A62 . "</A62>";
							else
								$xml .= "<A62>" . "</A62>";
							
							if(isset($ilrtemplate->aims[0]->A63))
								$xml .= "<A63>" . $ilrtemplate->aims[0]->A63 . "</A63>";
							else
								$xml .= "<A63>" . "</A63>";
							
							if(isset($ilrtemplate->aims[0]->A64))
								$xml .= "<A64>" . $ilrtemplate->aims[0]->A64 . "</A64>";
							else
								$xml .= "<A64>" . "</A64>";

							if(isset($ilrtemplate->aims[0]->A65))
								$xml .= "<A65>" . $ilrtemplate->aims[0]->A65 . "</A65>";
							else
								$xml .= "<A65>" . "</A65>";

							if(isset($ilrtemplate->aims[0]->A66))
								$xml .= "<A66>" . $ilrtemplate->aims[0]->A66 . "</A66>";
							else
								$xml .= "<A66>" . "</A66>";
							
							if(isset($ilrtemplate->aims[0]->A67))
								$xml .= "<A67>" . $ilrtemplate->aims[0]->A67 . "</A67>";
							else
								$xml .= "<A67>" . "</A67>";
								
							if(isset($ilrtemplate->aims[0]->A68))
								$xml .= "<A68>" . $ilrtemplate->aims[0]->A68 . "</A68>";
							else
								$xml .= "<A68>" . "</A68>";
							
							$xml .= "</main>";
						}
					}
					
					
					// Creating Sub Aims out of framework
					$sql_main = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']} and framework_qualifications.main_aim<>1";
					$st3 = $link->query($sql_main);
					if($st3)
					{
						$learningaim=2;	
						while($row_sub = $st3->fetch())
						{	
							$xml .= "<subaim>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>99</A02>";
							$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) .  "</A03>";
							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
							$learningaim++;
							$xml .= "<A06>00</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
							
							if(isset($ilrtemplate->aims[1]->A10))
								$xml .= "<A10>" . $ilrtemplate->aims[1]->A10 . "</A10>";
							else
								$xml .= "<A10>" . "</A10>";
							
							$xml .= "<A11a>" . "000" . "</A11a>";
							$xml .= "<A11b>" . "000" . "</A11b>";
							$xml .= "<A12a>" . "000" . "</A12a>";
							$xml .= "<A12b>" . "000" . "</A12b>";
							$xml .= "<A13>" . "00000" . "</A13>";

							if(isset($ilrtemplate->aims[1]->A14))
								$xml .= "<A14>" . $ilrtemplate->aims[1]->A14 . "</A14>";
							else
								$xml .= "<A14>" . "00" . "</A14>";
							
							if(isset($ilrtemplate->aims[1]->A15))
								$xml .= "<A15>" . $ilrtemplate->aims[1]->A15 . "</A15>";
							else
								$xml .= "<A15>" . "</A15>";
							
							if(isset($ilrtemplate->aims[1]->A16))
								$xml .= "<A16>"  . $ilrtemplate->aims[1]->A16 . "</A16>";
							else
								$xml .= "<A16>" . "</A16>";
							
							$xml .= "<A17>" . "0" . "</A17>";
							$xml .= "<A18>" . "</A18>";
							$xml .= "<A19>" . "0" . "</A19>";
							$xml .= "<A20>" . "0" . "</A20>";

							if(isset($ilrtemplate->aims[1]->A21))
								$xml .= "<A21>" . $ilrtemplate->aims[1]->A21 . "</A21>";
							else
								$xml .= "<A21>" . "00" . "</A21>";
							
							if(isset($ilrtemplate->aims[1]->A22))
								$xml .= "<A22>" . $ilrtemplate->aims[1]->A22 . "</A22>";
							else
								$xml .= "<A22>" . "      " . "</A22>";
							
							$xml .= "<A23>" . $row_sub['work_postcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";

							if(isset($ilrtemplate->aims[1]->A26))
								$xml .= "<A26>"  . $ilrtemplate->aims[1]->A26 . "</A26>";
							else
								$xml .= "<A26>" . "</A26>";
							
							$xml .= "<A27>" . substr($row_sub['lsd'],8,2) . '/' . substr($row_sub['lsd'],5,2) . '/' . substr($row_sub['lsd'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_sub['led'],8,2) . '/' . substr($row_sub['led'],5,2) . '/' . substr($row_sub['led'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";
							
							if(isset($ilrtemplate->aims[1]->A34))
								$xml .= "<A34>"  . $ilrtemplate->aims[1]->A34 . "</A34>";
							else
								$xml .= "<A34>" . "</A34>";
							
							if(isset($ilrtemplate->aims[1]->A35))
								$xml .= "<A35>"  . $ilrtemplate->aims[1]->A35 . "</A35>";
							else
								$xml .= "<A35>" . "</A35>";
							
							if(isset($ilrtemplate->aims[1]->A36))
								$xml .= "<A36>" . $ilrtemplate->aims[1]->A36 . "</A36>";
							else
								$xml .= "<A36>" . "   " . "</A36>";
							
							$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_sub['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";

							if($row['edrs']!='')
								$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
							else
								$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
							
							$xml .= "<A45>" . "</A45>";
							
							if(isset($ilrtemplate->aims[1]->A46a))
								$xml .= "<A46a>"  . $ilrtemplate->aims[1]->A46a . "</A46a>";
							else
								$xml .= "<A46a>" . "</A46a>";
							
							if(isset($ilrtemplate->aims[1]->A46b))
								$xml .= "<A46b>"  . $ilrtemplate->aims[1]->A46b . "</A46b>";
							else
								$xml .= "<A46b>" . "</A46b>";
							
							if(isset($ilrtemplate->aims[1]->A47a))
								$xml .= "<A47a>"  . $ilrtemplate->aims[1]->A47a . "</A47a>";
							else
								$xml .= "<A47a>" . "</A47a>";
							
							if(isset($ilrtemplate->aims[1]->A47b))
								$xml .= "<A47b>" . $ilrtemplate->aims[1]->A47b . "</A47b>";
							else
								$xml .= "<A47b>" . "</A47b>";
							
							if(isset($ilrtemplate->aims[1]->A48a))
								$xml .= "<A48a>" . $ilrtemplate->aims[1]->A48a . "</A48a>";
							else
								$xml .= "<A48a>" . "</A48a>";
							
							if(isset($ilrtemplate->aims[1]->A48b))
								$xml .= "<A48b>"  . $ilrtemplate->aims[1]->A48b . "</A48b>";
							else
								$xml .= "<A48b>" . "</A48b>";
							
							if(isset($ilrtemplate->aims[1]->A49))
								$xml .= "<A49>" . $ilrtemplate->aims[1]->A49 . "</A49>";
							else
								$xml .= "<A49>" . "     " . "</A49>";
							
							if(isset($ilrtemplate->aims[1]->A50))
								$xml .= "<A50>"  . $ilrtemplate->aims[1]->A50 . "</A50>";
							else
								$xml .= "<A50>" . "</A50>";
							
							$xml .= "<A51a>100</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							
							if(isset($ilrtemplate->aims[1]->A53))
								$xml .= "<A53>"  . $ilrtemplate->aims[1]->A53 . "</A53>";
							else
								$xml .= "<A53>" . "</A53>";
							
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>9999999999</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";

							if(isset($ilrtemplate->aims[1]->A59))
								$xml .= "<A59>"  . $ilrtemplate->aims[1]->A59 . "</A59>";
							else
								$xml .= "<A59>" . "</A59>";

							if(isset($ilrtemplate->aims[1]->A60))
								$xml .= "<A60>"  . $ilrtemplate->aims[1]->A60 . "</A60>";
							else
								$xml .= "<A60>" . "</A60>";
							
							if(isset($ilrtemplate->aims[1]->A61))
								$xml .= "<A61>"  . $ilrtemplate->aims[1]->A61 . "</A61>";
							else
								$xml .= "<A61>" . "</A61>";
							
							if(isset($ilrtemplate->aims[1]->A62))
								$xml .= "<A62>"  . $ilrtemplate->aims[1]->A62 . "</A62>";
							else
								$xml .= "<A62>" . "</A62>";
							
							if(isset($ilrtemplate->aims[1]->A63))
								$xml .= "<A63>"  . $ilrtemplate->aims[1]->A63 . "</A63>";
							else
								$xml .= "<A63>" . "</A63>";
								
							if(isset($ilrtemplate->aims[1]->A66))
								$xml .= "<A66>"  . $ilrtemplate->aims[1]->A66 . "</A66>";
							else
								$xml .= "<A66>" . "</A66>";
							
							if(isset($ilrtemplate->aims[1]->A67))
								$xml .= "<A67>"  . $ilrtemplate->aims[1]->A67 . "</A67>";
							else
								$xml .= "<A67>" . "</A67>";
							
							$xml .= "</subaim>";
						}
					}

					// Creating Sub Aims out of additional qualifications
					$sql_sub = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id where tr_id = {$row['id']} and framework_id=0";
					
					$st4 = $link->query($sql_sub);	
					if($st4)
					{
						$learningaim=2;	
						while($row_sub = $st4->fetch())
						{	
							$xml .= "<subaim>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>99</A02>";
							$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</A03>";
							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
							$learningaim++;
							$xml .= "<A06>00</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
							$xml .= "<A10>" . "</A10>";
							$xml .= "<A11a>" . "000" . "</A11a>";
							$xml .= "<A11b>" . "000" . "</A11b>";
							$xml .= "<A12a>" . "000" . "</A12a>";
							$xml .= "<A12b>" . "000" . "</A12b>";
							$xml .= "<A13>" . "00000" . "</A13>";
							$xml .= "<A14>" . "00" . "</A14>";
							$xml .= "<A15>" . "</A15>";
							$xml .= "<A16>" . "</A16>";
							$xml .= "<A17>" . "0" . "</A17>";
							$xml .= "<A18>" . "</A18>";
							$xml .= "<A19>" . "0" . "</A19>";
							$xml .= "<A20>" . "0" . "</A20>";
							$xml .= "<A21>" . "00" . "</A21>";
							$xml .= "<A22>" . "      " . "</A22>";
							$xml .= "<A23>" . $row_sub['work_postcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";
							$xml .= "<A26>" . "</A26>";
							$xml .= "<A27>" . substr($row_sub['lsd'],8,2) . '/' . substr($row_sub['lsd'],5,2) . '/' . substr($row_sub['lsd'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_sub['led'],8,2) . '/' . substr($row_sub['led'],5,2) . '/' . substr($row_sub['led'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";
							$xml .= "<A34>" . "</A34>";
							$xml .= "<A35>" . "</A35>";
							$xml .= "<A36>" . "   " . "</A36>";
							$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_sub['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";

							if($row['edrs']!='')
								$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
							else
								$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";

							$xml .= "<A45>" . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>100</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>9999999999</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
							$xml .= "</subaim>";
						}
					}
					
					
					
					$xml .= "</ilr>";
					$xml = str_replace("&", "&amp;", $xml);
					$xml = str_replace("'", "&apos;", $xml);
					// getting contract type 
				
					$sql = "Select contract_type from contracts where id ='$contract_id'";
					$contract_type = DAO::getResultset($link, $sql);
					$contract_type = $contract_type[0][0];					
					
					// $xml = addslashes((string)$xml);
					$contract = addslashes((string)$contract_id);
					$contract_type=addslashes((string)$contract_type);
					
					$upin = $row['upin'];
					//$l03 = $row['l03'];
					
					$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin','$l03','0','$xml','$submission','$contract_type','$tr_id','0','0','0','1','$contract');";
					$st5 = $link->query($sql);			
					if($st5 == false)
						throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());
											
				}
			}
						$link->commit();
			}
			catch(Exception $e)
			{
				$link->rollback();
				throw new WrappedException($e);
			}
				
			}	
			}
		}
	}
}
		
*/		
		
		
/*		
		// Recalclate percentage and units status
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			
			$total = 0;
			$ns = 0;
			$comp = 0;
			$behind = 0;
			
			$domElemsToRemove = array(); 
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);

			// Recalculate percentage
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			foreach($units as $unit)
			{
				$no_of_elements = 0;
				$total_element_percentage = 0;
				$elements = $unit->getElementsByTagName('element');
				foreach($elements as $element)
				{
					$no_of_elements++;
					
					$evidences = $element->getElementsByTagName('evidence');
					$achieved_evidences=0;
					$no_of_evidences = 0;
					foreach($evidences as $evidence)
					{
						$no_of_evidences++;
						if($evidence->getAttribute('status')=='a')
							$achieved_evidences++;
					}
					
					$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
					$total_element_percentage += $elementPercentage;
					$element->setAttribute("percentage",$elementPercentage);
				}
				
				if($no_of_elements!=0)
					$unitPercentage = $total_element_percentage / $no_of_elements;
				else
					$unitPercentage = $total_element_percentage;
				
				$unitProportion = $unit->getAttribute('proportion');
				$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
				$unit->setAttribute("percentage",$unitPercentage);
				
				$total++;
				
				if($unitPercentage == 100)
					$comp++;
				elseif($unitPercentage > 0)
					$behind++;
				else
					$ns++;
			}
			
			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);

				
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);

			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set unitsUnderAssessment = '$total_unit_percentage', units = '$total', unitsCompleted = '$comp', unitsNotStarted = '$ns', unitsBehind = '$behind', evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());

		}
	}
}		

*/

/*		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$firstname = $row['firstnames'];
				$surname = $row['surname'];
				

				$sql2 = "SELECT * FROM ilr where tr_id = {$row['id']}";
				$st2 = $link->query($sql2);
				if($st2) 
				{
					while($row2 = $st2->fetch())
					{

						$submission = $row2['submission'];
						$contract_id = $row2['contract_id'];
						$tr_id = $row2['tr_id'];
						$l03 = $row2['L03'];
						$xml = $row2['ilr'];

						
						
						$pageDom = new DomDocument();
						$pageDom->loadXML($xml);
						
						$e = $pageDom->getElementsByTagName('learner');
						foreach($e as $node)
						{
								
							$node->getElementsByTagName('L09')->item(0)->nodeValue = $surname;
							$node->getElementsByTagName('L10')->item(0)->nodeValue = $firstname;
						}
		
					
						$ilr = $pageDom->saveXML();
				
						$ilr=substr($ilr,21);
						$ilr = str_replace("'", "&apos;" , $ilr);
						
						$sql3 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and L03 = '$l03'";
						$st3 = $link->query($sql3);			
						if(!$st3)
							throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
						
					}
				}
			}
		}
	}
}
		
*/		
/*
		// Migrating awarding body data
		$handle = fopen("awarding.csv","r");
		$st = fgets($handle);
		$nf = Array();
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			if($arr[0]=='END')
				break;
			
			$qan = $arr[2];
			$l03 = $arr[3];				
			$abrn = $arr[6];
			$abrd = Date::toMySQL(trim($arr[7]));

			$sql = "UPDATE student_qualifications LEFT JOIN tr ON student_qualifications.tr_id = tr.id SET awarding_body_reg = '$abrn', awarding_body_date = '$abrd' WHERE REPLACE(student_qualifications.id,'/','') = '$qan' AND tr.l03 = '$l03'";
			$link->query($sql);

			$c = DAO::getSingleValue($link, "select count(*) from student_qualifications LEFT JOIN tr ON student_qualifications.tr_id = tr.id  WHERE REPLACE(student_qualifications.id,'/','') = '$qan' AND tr.l03 = '$l03'");
			if($c<1)
				$nf[] = $l03 . " " . $qan;			
			
		}
			
			pre($nf);
	}
}
		
*/		
		
/*		
		// BIT
		$handle = fopen("tmuk.csv","r");
		$st = fgets($handle);
		
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);
			
			$member = trim($arr[0]);	
			if(trim($arr[11])=='')
				$start_date = 'NULL';
			else
				$start_date = "'" . Date::toMySQL(trim($arr[11])) . "'";

			$cip = trim($arr[12]);
			$environment = trim($arr[13]);
			$kaizenfull = trim($arr[14]);
			$mss = trim($arr[15]);
			$cost = trim($arr[16]);
			$kaizen = trim($arr[17]);
			$kaizenreporting = trim($arr[18]);
			$qcmember = trim($arr[19]);
			$pdcacourse = trim($arr[20]);
			$pdcaro = trim($arr[21]);
			$safetyeye = trim($arr[22]);
			$safetyleadergl = trim($arr[23]);
			$coaching = trim($arr[24]);
			$qcadvisor = trim($arr[25]);
			$quality = trim($arr[26]);
			$cost2 = trim($arr[27]);
			$delivery = trim($arr[28]);
			
//			if(trim($arr[7])=='')
//				$end_date = 'NULL';
//			else
//				$end_date = "'" . Date::toMySQL(trim($arr[7])) . "'";
			
			$username = DAO::getSingleValue($link, "select username from users where CAST(enrollment_no AS SIGNED) = '$member' ");

			$sql = "SELECT * FROM student_qualifications where username = '$username' and id = '500/2154/0'";
			$st = $link->query($sql);
			while($row = $st->fetch())
			{
		
				$pageDom = new DomDocument();
				$pageDom->loadXML($row['evidences']);
				$e = $pageDom->getElementsByTagName('evidence');
				foreach($e as $node)
				{
		
					// Remove all the tracking
					if($node->getAttribute('date')=='')
						$node->setAttribute('status','');
					
					if($node->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');
						
					if($node->parentNode->nodeName == 'units')
						$group = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');

					if($group=='')
						pre("pagal");	
						
					if($unitreference == "BIT Pilot TM CIP 0.5 day" && $node->getAttribute('title') == "The learner has completed this unit")
					{
						if($cip!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$cip);				
						}
					}
					elseif($unitreference == "BIT Pilot Environment 0.5 day" && $node->getAttribute('title')=="The learner has completed this unit.")
					{
						if($environment!='')	
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$environment);				
						}
					}
					elseif($unitreference == "TM Kaizen 2 day" && $node->getAttribute('title')=="1.1  The learner has completed the two day Kaizen course.")
					{
						if($kaizenfull!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$kaizenfull);				
						}
					}
					elseif($unitreference == "BIT Pilot MSS 0.5 day" && $node->getAttribute('title')=="The learner has completed this unit")
					{
						if($mss!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$mss);				
						}
					}
					elseif($unitreference == "BIT Pilot Cost 0.5 day" && $node->getAttribute('title')=="The learner has completed this unit")
					{
						if($cost!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$cost);				
						}
					}
					elseif($unitreference == "TM Kaizen 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($kaizen!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$kaizen);				
						}
					}
					elseif($unitreference == "TM Kaizen Reporting 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($kaizenreporting!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$kaizenreporting);				
						}
					}
					elseif($unitreference == "TM QC Member 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($qcmember!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$qcmember);				
						}
					}
					elseif($unitreference == "TL PDCA Course 1.0 day" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($pdcacourse!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$pdcacourse);				
						}
					}
					elseif($unitreference == "TL PDCA RO 0.5 day" && $node->getAttribute('title')=="1.1  The learner has completed this unit.")
					{
						if($pdcaro!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$pdcaro);				
						}
					}
					elseif($unitreference == "TL Safety Eye 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($safetyeye!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$safetyeye);				
						}
					}
					elseif($unitreference == "GL/GM Safety Leader 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($safetyleadergl!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$safetyleadergl);				
						}
					}
					elseif($unitreference == "GL/GM Coaching 1 day" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($coaching!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$coaching);				
						}
					}
					elseif($unitreference == "GL/GM QC Advisor 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($qcadvisor!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$qcadvisor);				
						}
					}
					elseif($node->getAttribute('title')=="201.1 Assessment  - Quality" || $node->getAttribute('title')=="202.1 Assessment - Quality" || $node->getAttribute('title')=="204.1 Assessment  - Quality" || $node->getAttribute('title')=="205.1 Assessment - Quality" || $node->getAttribute('title')=="209.1 Assessment  - Quality" || $node->getAttribute('title')=="213.1 Assessment - Quality")
					{
						if($quality!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$quality);				
						}
					}
					elseif($node->getAttribute('title')=="201.2 Assessment - Cost" || $node->getAttribute('title')=="202.2 Assessment  - Cost" || $node->getAttribute('title')=="204.2 Assessment  - Cost" || $node->getAttribute('title')=="205.2  Assessment - Cost" || $node->getAttribute('title')=="209.2 Assessment - Cost" || $node->getAttribute('title')=="213.2 Assessment - Cost")
					{
						if($cost2!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$cost2);				
						}
					}
					elseif($node->getAttribute('title')=="201.3  Assessment  - Delivery" || $node->getAttribute('title')=="202.3 Assessment  -  Delivery" || $node->getAttribute('title')=="204.3 Assessment - Delivery" || $node->getAttribute('title')=="205.3 Assessment - Delivery" || $node->getAttribute('title')=="209.3 Assessment - Delivery" || $node->getAttribute('title')=="213.3 Assessment - Delivery")
					{
						if($delivery!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$delivery);				
						}
					}
				}
		
				// Recalculating percentage
			
				$units = $pageDom->getElementsByTagName('unit');
				$total_unit_percentage = 0;
				foreach($units as $unit)
				{
					$no_of_elements = 0;
					$total_element_percentage = 0;
					$elements = $unit->getElementsByTagName('element');
					foreach($elements as $element)
					{
						$no_of_elements++;
						
						$evidences = $element->getElementsByTagName('evidence');
						$achieved_evidences=0;
						$no_of_evidences = 0;
						foreach($evidences as $evidence)
						{
							$no_of_evidences++;
							if($evidence->getAttribute('status')=='a')
								$achieved_evidences++;
						}
						
						$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
						$total_element_percentage += $elementPercentage;
						$element->setAttribute("percentage",$elementPercentage);
					}
					
					if($no_of_elements!=0)
						$unitPercentage = $total_element_percentage / $no_of_elements;
					else
						$unitPercentage = $total_element_percentage;
					
					$unitProportion = $unit->getAttribute('proportion');
					$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
					$unit->setAttribute("percentage",$unitPercentage);
				}
				
				$roots = $pageDom->getElementsByTagName('root');
				foreach($roots as $root)
					$root->setAttribute("percentage", $total_unit_percentage);
				
		
				$qual = $pageDom->saveXML();
				$qual=substr($qual,21);
				
				$qual= str_replace("'","apos;",$qual);
				
				$id = $row['id'];
				$internaltitle = $row['internaltitle'];
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				
				$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, start_date = $start_date, evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
			}
		}
	}
}


*/		
/*		
		// PMO 
		$handle = fopen("tmuk.csv","r");
		$st = fgets($handle);
		$notfound = '';
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);
			
			$member = trim($arr[0]);	
			if(trim($arr[1])=='')
				$start_date = 'NULL';
			else
				$start_date = "'" . Date::toMySQL(trim($arr[1])) . "'";

			$fundamental = trim($arr[2]);
			$upk = trim($arr[3]);
			$assessment1 = trim($arr[4]);
			$assessment2 = trim($arr[5]);
			$assessment3 = trim($arr[6]);
			
			if(trim($arr[7])=='')
				$end_date = 'NULL';
			else
				$end_date = "'" . Date::toMySQL(trim($arr[7])) . "'";
			
			$username = DAO::getSingleValue($link, "select username from users where CAST(enrollment_no AS SIGNED) = '$member' ");

			$count = DAO::getSingleValue($link,"SELECT count(*) FROM student_qualifications where username = '$username' and id = '100/3955/7'");
			if($count==0)
				$notfound .= $member . ",";
			$sql = "SELECT * FROM student_qualifications where username = '$username' and id = '100/3955/7'";
			$st = $link->query($sql);
			while($row = $st->fetch())
			{
		
				$pageDom = new DomDocument();
				$pageDom->loadXML($row['evidences']);
				$e = $pageDom->getElementsByTagName('evidence');
				foreach($e as $node)
				{
		
					// Remove all the tracking
					if($node->getAttribute('date')=='' ||$node->getAttribute('date')=='null')
						$node->setAttribute('status','');
					
					if($node->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');
						
					if($node->parentNode->nodeName == 'units')
						$group = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');

					if($group=='')
						pre("pagal");	
						
					if($unitreference == "Fundamental Skills  7.5 hours" && $node->getAttribute('title') == "Assessment 1")
					{
						if($fundamental!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$fundamental);				
						}
					}
					elseif($unitreference == "UPK Underpinning Knowledge  7.5 hours" && $node->getAttribute('title')=="Assessment 1")
					{
						if($upk!='')	
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$upk);				
						}
					}
					elseif(($group=="Production" || $group=="A - Mandatory units") && $node->getAttribute('title')=="Assessment 1")
					{
						if($assessment1!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$assessment1);				
						}
					}
					elseif(($group=="Production" || $group=="A - Mandatory units") && $node->getAttribute('title')=="Assessment 2")
					{
						if($assessment2!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$assessment2);				
						}
					}
					elseif(($group=="Production" || $group=="A - Mandatory units") && $node->getAttribute('title')=="Assessment 3")
					{
						if($assessment3!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$assessment3);				
						}
					} 
				}
		
				// Recalculating percentage
			
				$units = $pageDom->getElementsByTagName('unit');
				$total_unit_percentage = 0;
				foreach($units as $unit)
				{
					$no_of_elements = 0;
					$total_element_percentage = 0;
					$elements = $unit->getElementsByTagName('element');
					foreach($elements as $element)
					{
						$no_of_elements++;
						
						$evidences = $element->getElementsByTagName('evidence');
						$achieved_evidences=0;
						$no_of_evidences = 0;
						foreach($evidences as $evidence)
						{
							$no_of_evidences++;
							if($evidence->getAttribute('status')=='a')
								$achieved_evidences++;
						}
						
						$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
						$total_element_percentage += $elementPercentage;
						$element->setAttribute("percentage",$elementPercentage);
					}
					
					if($no_of_elements!=0)
						$unitPercentage = $total_element_percentage / $no_of_elements;
					else
						$unitPercentage = $total_element_percentage;
					
					$unitProportion = $unit->getAttribute('proportion');
					$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
					$unit->setAttribute("percentage",$unitPercentage);
				}
				
				$roots = $pageDom->getElementsByTagName('root');
				foreach($roots as $root)
					$root->setAttribute("percentage", $total_unit_percentage);
				
		
				$qual = $pageDom->saveXML();
				$qual=substr($qual,21);
				
				$qual= str_replace("'","apos;",$qual);
				
				$id = $row['id'];
				$internaltitle = $row['internaltitle'];
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				
				$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, start_date = $start_date, end_date = $end_date ,evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
			}
		}
		pre($notfound);
	}
}

*/		
		
		
/*		
		$sql = "select * from ilr where contract_id = 5 and submission = 'W10'";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{	
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$L03 = $row['L03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
		
				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);
				
				$e = $pageDom->getElementsByTagName('programmeaim');
				$count = 0;
				foreach($e as $node)
				{
					$node->getElementsByTagName('A31')->item(0)->nodeValue = $node->getElementsByTagName('A27')->item(0)->nodeValue;
					$node->getElementsByTagName('A40')->item(0)->nodeValue = $node->getElementsByTagName('A27')->item(0)->nodeValue;
					$node->getElementsByTagName('A34')->item(0)->nodeValue = "2";
					$node->getElementsByTagName('A35')->item(0)->nodeValue = "1";
					$node->getElementsByTagName('A50')->item(0)->nodeValue = "97";
					//$node->getElementsByTagName('A14')->item(0)->nodeValue = "28";
				}

			
				$ilr = $pageDom->saveXML();
		
				$ilr=substr($ilr,21);
				$ilr = str_replace("'", "&apos;" , $ilr);
				
				//throw new Exception($ilr);
				
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
				
			}
		}
	}
}
		
		
*/		
/*	
		$temps = Array();
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
	//		pre(3);
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);
			$e = $pageDom->getElementsByTagName('evidence');
			foreach($e as $node)
			{
				if($node->getAttribute('status')=='' && $node->getAttribute('comments')!='')
				{
					if(!in_array($row['tr_id'],$temps))
						$temps[] = $row['tr_id'];		
				}
			}
		}
		
		$html = '';
		foreach($temps as $temp)
		{
			$username = DAO::getSingleValue($link, "select username from tr where id = $temp");
			$html .=	DAO::getSingleValue($link, "select enrollment_no from users where username = '$username'");
			$html .=	" " .DAO::getSingleValue($link, "select firstnames from tr where id = $temp");
			$html .=	" " . DAO::getSingleValue($link, "select surname from tr where id = $temp");
			$html .=	" " . DAO::getSingleValue($link, "select internaltitle from student_qualifications where tr_id = $temp");
			$html .= "<br>";			
		}
		echo $html;
	}
}	
*/
/*		
		// Update ilrs via template
		$sql = "SELECT * FROM contracts";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$template = $row['template'];
			$template = Ilr2009::loadFromXML($template);
			$sql2 = "SELECT * FROM ilr where contract_id = {$row['id']};";
			$st2 = $link->query($sql2);
		//	pre(1);
			while($row2 = $st2->fetch())
			{
				$ilr = $row2['ilr'];
				$tr_id = $row2['tr_id'];
				$submission = $row2['submission'];
				$contract_id = $row2['contract_id'];
				//$ilr = Ilr2009::loadFromXML($ilr);

				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);
				
				$e = $pageDom->getElementsByTagName('learner');
				foreach($e as $node)
				{
					if(isset($template->learnerinformation->L14))
						$node->getElementsByTagName('L14')->item(0)->nodeValue = $template->learnerinformation->L14;

					if(isset($template->learnerinformation->L15))
						$node->getElementsByTagName('L15')->item(0)->nodeValue = $template->learnerinformation->L15;

					if(isset($template->learnerinformation->L16))
						$node->getElementsByTagName('L16')->item(0)->nodeValue = $template->learnerinformation->L16;
						
					if(isset($template->learnerinformation->L24))
						$node->getElementsByTagName('L24')->item(0)->nodeValue = $template->learnerinformation->L24;
						
					if(isset($template->learnerinformation->L28a))
						$node->getElementsByTagName('L28a')->item(0)->nodeValue = $template->learnerinformation->L28a;
				
					if(isset($template->learnerinformation->L28b))
						$node->getElementsByTagName('L28b')->item(0)->nodeValue = $template->learnerinformation->L28b;
						
					if(isset($template->learnerinformation->L34a))
						$node->getElementsByTagName('L34a')->item(0)->nodeValue = $template->learnerinformation->L34a;

					if(isset($template->learnerinformation->L34b))
						$node->getElementsByTagName('L34b')->item(0)->nodeValue = $template->learnerinformation->L34b;

					if(isset($template->learnerinformation->L34c))
						$node->getElementsByTagName('L34c')->item(0)->nodeValue = $template->learnerinformation->L34c;
						
					if(isset($template->learnerinformation->L34d))
						$node->getElementsByTagName('L34d')->item(0)->nodeValue = $template->learnerinformation->L34d;
						
					if(isset($template->learnerinformation->L37))
						$node->getElementsByTagName('L37')->item(0)->nodeValue = $template->learnerinformation->L37;

					if(isset($template->learnerinformation->L39))
						$node->getElementsByTagName('L39')->item(0)->nodeValue = $template->learnerinformation->L39;
						
					if(isset($template->learnerinformation->L40a))
						$node->getElementsByTagName('L40a')->item(0)->nodeValue = $template->learnerinformation->L40a;
						
					if(isset($template->learnerinformation->L40b))
						$node->getElementsByTagName('L40b')->item(0)->nodeValue = $template->learnerinformation->L40b;

					if(isset($template->learnerinformation->L47))
						$node->getElementsByTagName('L47')->item(0)->nodeValue = $template->learnerinformation->L47;

				}
				
				$e = $pageDom->getElementsByTagName('programmeaim');
				foreach($e as $node)
				{
					if(isset($template->programmeaim->A02))
						$node->getElementsByTagName('A02')->item(0)->nodeValue = $template->programmeaim->A02;
					
					if(isset($template->programmeaim->A04))
						$node->getElementsByTagName('A04')->item(0)->nodeValue = $template->programmeaim->A04;

					if(isset($template->programmeaim->A09))
						$node->getElementsByTagName('A09')->item(0)->nodeValue = $template->programmeaim->A09;
						
					if(isset($template->programmeaim->A10))
						$node->getElementsByTagName('A10')->item(0)->nodeValue = $template->programmeaim->A10;

					if(isset($template->programmeaim->A15))
						$node->getElementsByTagName('A15')->item(0)->nodeValue = $template->programmeaim->A15;
						
					if(isset($template->programmeaim->A16))
						$node->getElementsByTagName('A16')->item(0)->nodeValue = $template->programmeaim->A16;
						
					if(isset($template->programmeaim->A26))
						$node->getElementsByTagName('A26')->item(0)->nodeValue = $template->programmeaim->A26;

					if(isset($template->programmeaim->A14))
						$node->getElementsByTagName('A14')->item(0)->nodeValue = $template->programmeaim->A14;
						
					if(isset($template->programmeaim->A46a))
						$node->getElementsByTagName('A46a')->item(0)->nodeValue = $template->programmeaim->A46a;

					if(isset($template->programmeaim->A46b))
						$node->getElementsByTagName('A46b')->item(0)->nodeValue = $template->programmeaim->A46b;

					if(isset($template->programmeaim->A40))
						$node->getElementsByTagName('A40')->item(0)->nodeValue = $template->programmeaim->A40;
						
					if(isset($template->programmeaim->A31))
						$node->getElementsByTagName('A31')->item(0)->nodeValue = $template->programmeaim->A31;
						
					if(isset($template->programmeaim->A34))
						$node->getElementsByTagName('A34')->item(0)->nodeValue = $template->programmeaim->A34;

					if(isset($template->programmeaim->A35))
						$node->getElementsByTagName('A35')->item(0)->nodeValue = $template->programmeaim->A35;

					if(isset($template->programmeaim->A50))
						$node->getElementsByTagName('A50')->item(0)->nodeValue = $template->programmeaim->A50;
				}
				
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					if(isset($template->aims[0]->A02))
						$node->getElementsByTagName('A02')->item(0)->nodeValue = $template->aims[0]->A02;

					if(isset($template->aims[0]->A10))
						$node->getElementsByTagName('A10')->item(0)->nodeValue = $template->aims[0]->A10;
						
					if(isset($template->aims[0]->A14))
						$node->getElementsByTagName('A14')->item(0)->nodeValue = $template->aims[0]->A14;
				
					if(isset($template->aims[0]->A15))
						$node->getElementsByTagName('A15')->item(0)->nodeValue = $template->aims[0]->A15;
						
					if(isset($template->aims[0]->A16))
						$node->getElementsByTagName('A16')->item(0)->nodeValue = $template->aims[0]->A16;

					if(isset($template->aims[0]->A18))
						$node->getElementsByTagName('A18')->item(0)->nodeValue = $template->aims[0]->A18;
						
					if(isset($template->aims[0]->A21))
						$node->getElementsByTagName('A21')->item(0)->nodeValue = $template->aims[0]->A21;
						
					if(isset($template->aims[0]->A22))
						$node->getElementsByTagName('A22')->item(0)->nodeValue = $template->aims[0]->A22;
						
					if(isset($template->aims[0]->A26))
						$node->getElementsByTagName('A26')->item(0)->nodeValue = $template->aims[0]->A26;

					if(isset($template->aims[0]->A34))
						$node->getElementsByTagName('A34')->item(0)->nodeValue = $template->aims[0]->A34;
						
					if(isset($template->aims[0]->A35))
						$node->getElementsByTagName('A35')->item(0)->nodeValue = $template->aims[0]->A35;

					if(isset($template->aims[0]->A36))
						$node->getElementsByTagName('A36')->item(0)->nodeValue = $template->aims[0]->A36;

					if(isset($template->aims[0]->A46a))
						$node->getElementsByTagName('A46a')->item(0)->nodeValue = $template->aims[0]->A46a;
						
					if(isset($template->aims[0]->A46b))
						$node->getElementsByTagName('A46b')->item(0)->nodeValue = $template->aims[0]->A46b;
					
					if(isset($template->aims[0]->A47a))
						$node->getElementsByTagName('A47a')->item(0)->nodeValue = $template->aims[0]->A47a;
						
					if(isset($template->aims[0]->A47b))
						$node->getElementsByTagName('A47b')->item(0)->nodeValue = $template->aims[0]->A47b;
						
					if(isset($template->aims[0]->A48a))
						$node->getElementsByTagName('A48a')->item(0)->nodeValue = $template->aims[0]->A48a;
						
					if(isset($template->aims[0]->A48b))
						$node->getElementsByTagName('A48b')->item(0)->nodeValue = $template->aims[0]->A48b;
				
					if(isset($template->aims[0]->A49))
						$node->getElementsByTagName('A49')->item(0)->nodeValue = $template->aims[0]->A49;
						
					if(isset($template->aims[0]->A50))
						$node->getElementsByTagName('A50')->item(0)->nodeValue = $template->aims[0]->A50;
	
					if(isset($template->aims[0]->A53))
						$node->getElementsByTagName('A53')->item(0)->nodeValue = $template->aims[0]->A53;
						
					if(isset($template->aims[0]->A54))
						$node->getElementsByTagName('A54')->item(0)->nodeValue = $template->aims[0]->A54;
	
					if(isset($template->aims[0]->A59))
						$node->getElementsByTagName('A59')->item(0)->nodeValue = $template->aims[0]->A59;
				
					if(isset($template->aims[0]->A60))
						$node->getElementsByTagName('A60')->item(0)->nodeValue = $template->aims[0]->A60;
						
					if(isset($template->aims[0]->A61))
						$node->getElementsByTagName('A61')->item(0)->nodeValue = $template->aims[0]->A61;
				
					if(isset($template->aims[0]->A62))
						$node->getElementsByTagName('A62')->item(0)->nodeValue = $template->aims[0]->A62;
						
					if(isset($template->aims[0]->A63))
						$node->getElementsByTagName('A63')->item(0)->nodeValue = $template->aims[0]->A63;
						
					if(isset($template->aims[0]->A64))
						$node->getElementsByTagName('A64')->item(0)->nodeValue = $template->aims[0]->A64;
						
					if(isset($template->aims[0]->A65))
						$node->getElementsByTagName('A65')->item(0)->nodeValue = $template->aims[0]->A65;
						
					if(isset($template->aims[0]->A66))
						$node->getElementsByTagName('A66')->item(0)->nodeValue = $template->aims[0]->A66;
						
					if(isset($template->aims[0]->A67))
						$node->getElementsByTagName('A67')->item(0)->nodeValue = $template->aims[0]->A67;
						
					if(isset($template->aims[0]->A68))
						$node->getElementsByTagName('A68')->item(0)->nodeValue = $template->aims[0]->A68;
				}

				$e = $pageDom->getElementsByTagName('subaim');
				foreach($e as $node)
				{
					if(isset($template->aims[1]->A10))
						$node->getElementsByTagName('A10')->item(0)->nodeValue = $template->aims[1]->A10;

					if(isset($template->aims[1]->A14))
						$node->getElementsByTagName('A14')->item(0)->nodeValue = $template->aims[1]->A14;
				
					if(isset($template->aims[1]->A15))
						$node->getElementsByTagName('A15')->item(0)->nodeValue = $template->aims[1]->A15;
						
					if(isset($template->aims[1]->A16))
						$node->getElementsByTagName('A16')->item(0)->nodeValue = $template->aims[1]->A16;
				
					if(isset($template->aims[1]->A26))
						$node->getElementsByTagName('A26')->item(0)->nodeValue = $template->aims[1]->A26;
						
					if(isset($template->aims[1]->A34))
						$node->getElementsByTagName('A34')->item(0)->nodeValue = $template->aims[1]->A34;
						
					if(isset($template->aims[1]->A35))
						$node->getElementsByTagName('A35')->item(0)->nodeValue = $template->aims[1]->A35;
						
					if(isset($template->aims[1]->A36))
						$node->getElementsByTagName('A36')->item(0)->nodeValue = $template->aims[1]->A36;
						
					if(isset($template->aims[1]->A46a))
						$node->getElementsByTagName('A46a')->item(0)->nodeValue = $template->aims[1]->A46a;
						
					if(isset($template->aims[1]->A46b))
						$node->getElementsByTagName('A46b')->item(0)->nodeValue = $template->aims[1]->A46b;
				
					if(isset($template->aims[1]->A47a))
						$node->getElementsByTagName('A47a')->item(0)->nodeValue = $template->aims[1]->A47a;
						
					if(isset($template->aims[1]->A47b))
						$node->getElementsByTagName('A47b')->item(0)->nodeValue = $template->aims[1]->A47b;
						
					if(isset($template->aims[1]->A48a))
						$node->getElementsByTagName('A48a')->item(0)->nodeValue = $template->aims[1]->A48a;
						
					if(isset($template->aims[1]->A48b))
						$node->getElementsByTagName('A48b')->item(0)->nodeValue = $template->aims[1]->A48b;
						
					if(isset($template->aims[1]->A49))
						$node->getElementsByTagName('A49')->item(0)->nodeValue = $template->aims[1]->A49;
						
					if(isset($template->aims[1]->A50))
						$node->getElementsByTagName('A50')->item(0)->nodeValue = $template->aims[1]->A50;
						
					if(isset($template->aims[1]->A53))
						$node->getElementsByTagName('A53')->item(0)->nodeValue = $template->aims[1]->A53;
				
					if(isset($template->aims[1]->A59))
						$node->getElementsByTagName('A59')->item(0)->nodeValue = $template->aims[1]->A59;
						
					if(isset($template->aims[1]->A60))
						$node->getElementsByTagName('A60')->item(0)->nodeValue = $template->aims[1]->A60;
				
					if(isset($template->aims[1]->A61))
						$node->getElementsByTagName('A61')->item(0)->nodeValue = $template->aims[1]->A61;
						
					if(isset($template->aims[1]->A62))
						$node->getElementsByTagName('A62')->item(0)->nodeValue = $template->aims[1]->A62;

					if(isset($template->aims[1]->A63))
						$node->getElementsByTagName('A63')->item(0)->nodeValue = $template->aims[1]->A63;
						
					if(isset($template->aims[1]->A66))
						$node->getElementsByTagName('A66')->item(0)->nodeValue = $template->aims[1]->A66;
						
					if(isset($template->aims[1]->A67))
						$node->getElementsByTagName('A67')->item(0)->nodeValue = $template->aims[1]->A67;
				}

				$ilr = $pageDom->saveXML();
				$ilr = substr($ilr,21);
				$ilr = str_replace("'", "&apos;" , $ilr);
			
				$sql3 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission'";
				$st3 = $link->query($sql3);			
				if(!$st3)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql3, $link->errorCode());	
			
			}
		}
	}
}
*/
/*		
		chdir(DATA_ROOT."/uploads/am_demo"); 
		
		// create object
		$zip = new ZipArchive();
		// open archive 
		if ($zip->open('wtr.docx', ZIPARCHIVE::CREATE) !== TRUE) {
		    die ("Could not open archive");
		}

		chdir("../../htdocs/test/wtr"); 
		
		// list of files to add
		$fileList = array(
		    '[Content_Types].xml',
		    '_rels/.rels',
		    'docProps/app.xml',
		    'docProps/core.xml',
			'word/endnotes.xml',
			'word/footer1.xml',
			'word/header1.xml',
			'word/styles.xml',
			'word/document.xml',
			'word/fontTable.xml',
			'word/footnotes.xml',
			'word/settings.xml',
			'word/webSettings.xml',
			'word/_rels/document.xml.rels',
			'word/_rels/footer1.xml.rels',
			'word/_rels/header1.xml.rels',
			'word/media/image1.jpeg',
			'word/media/image2.png',
			'word/theme/theme1.xml'
		);


		
		// add files
		foreach ($fileList as $f) {
		    $zip->addFile($f) or die ("ERROR: Could not add file: $f");   
		}
		
		// close and save archive
		$zip->close();
		
		http_redirect("do.php?_action=downloader&path=%2Fam_demo%2F&f=wtr.docx");
		
	}
}		
*/
/*
			$sql = "SELECT * FROM student_qualifications where id = '500/2154/0'";
			$st = $link->query($sql);
			while($row = $st->fetch())
			{
				
				
				$pageDom = new DomDocument();
				$pageDom->loadXML($row['evidences']);
				$e = $pageDom->getElementsByTagName('evidence');
				foreach($e as $node)
				{
		
					if($node->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');
						
					if($node->parentNode->nodeName == 'units')
						$group = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');

					$username = $row['username'];
					$tr_id = $row['tr_id'];					
					$team_member = DAO::getSingleValue($link, "select count(*) from student_qualifications where id = '100/3955/7' and username = '$username'");	

							
					if($unitreference == "TL Safety Eye 0.5 days" && $node->getAttribute('title')=="1.1  The learner has completed this unit")
					{
						if($team_member)
						{	
							if($node->getAttribute('status')=='a')
							{
								$mark = true;
								$node->setAttribute('status','');
								$node->setAttribute('date','');
								$date = $node->getAttribute('date');		
							}
							else
								$mark = false;
						}
						else
							$mark = false;

					}
				}	
					
				if($mark)
				{
					$e = $pageDom->getElementsByTagName('evidence');
					foreach($e as $node)
					{
			
						// Remove all the tracking
						if($node->parentNode->nodeName == 'unit')
							$unitreference = $node->parentNode->getAttribute('owner_reference');
						elseif($node->parentNode->parentNode->nodeName == 'unit')
							$unitreference = $node->parentNode->parentNode->getAttribute('owner_reference');
						elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
							$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('owner_reference');
						elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
							$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('owner_reference');
							
						$unitreference = str_replace(" ","", $unitreference);	
							
						if($unitreference == "TMSafety")
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$date);
						}
					}
				}
		
				// Recalculating percentage
			
				$units = $pageDom->getElementsByTagName('unit');
				$total_unit_percentage = 0;
				foreach($units as $unit)
				{
					$no_of_elements = 0;
					$total_element_percentage = 0;
					$elements = $unit->getElementsByTagName('element');
					foreach($elements as $element)
					{
						$no_of_elements++;
						
						$evidences = $element->getElementsByTagName('evidence');
						$achieved_evidences=0;
						$no_of_evidences = 0;
						foreach($evidences as $evidence)
						{
							$no_of_evidences++;
							if($evidence->getAttribute('status')=='a')
								$achieved_evidences++;
						}
						
						$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
						$total_element_percentage += $elementPercentage;
						$element->setAttribute("percentage",$elementPercentage);
					}
					
					if($no_of_elements!=0)
						$unitPercentage = $total_element_percentage / $no_of_elements;
					else
						$unitPercentage = $total_element_percentage;
					
					$unitProportion = $unit->getAttribute('proportion');
					$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
					$unit->setAttribute("percentage",$unitPercentage);
				}
				
				$roots = $pageDom->getElementsByTagName('root');
				foreach($roots as $root)
					$root->setAttribute("percentage", $total_unit_percentage);
				
		
				$qual = $pageDom->saveXML();
				$qual=substr($qual,21);
				
				$qual= str_replace("'","apos;",$qual);
				
				$id = $row['id'];
				$internaltitle = $row['internaltitle'];
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				
				$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
		
*/		
		
/*		
		// BIT
		// Change of propoprtion in units
		$sql = "SELECT * FROM student_qualifications where id = '500/2154/0'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
	
//			$tr_id = $row['tr_id'];
//			$count = DAO::getSingleValue($link, "select count(*) from student_qualifications where tr_id = $tr_id");
			
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{

				$owner_reference = str_replace(" ","",$node->getAttribute('owner_reference'));
				
				if($owner_reference=="TMKaizen" || $owner_reference=="TMKaizen1" || $owner_reference=="TMQCMem" || $owner_reference=="TMKaiRptg" || $owner_reference=="TMSafety")
				{
					$node->setAttribute('proportion',"10");				
				}
			}
	
			// Recalculating percentage
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			foreach($units as $unit)
			{
				$no_of_elements = 0;
				$total_element_percentage = 0;
				$elements = $unit->getElementsByTagName('element');
				foreach($elements as $element)
				{
					$no_of_elements++;
					
					$evidences = $element->getElementsByTagName('evidence');
					$achieved_evidences=0;
					$no_of_evidences = 0;
					foreach($evidences as $evidence)
					{
						$no_of_evidences++;
						if($evidence->getAttribute('status')=='a')
							$achieved_evidences++;
					}
					
					$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
					$total_element_percentage += $elementPercentage;
					$element->setAttribute("percentage",$elementPercentage);
				}
				
				if($no_of_elements!=0)
					$unitPercentage = $total_element_percentage / $no_of_elements;
				else
					$unitPercentage = $total_element_percentage;
				
				$unitProportion = $unit->getAttribute('proportion');
				$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
				$unit->setAttribute("percentage",$unitPercentage);
			}
			
			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);
			
	
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
		
		
*/		
		
/*		
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				$achieved = true;
				$a35 = 0;
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = (select max(submission) from ilr where tr_id = $tr_id)");

				$vo = Ilr2009::loadFromXML($ilr);

				if($vo->aims[0]->A31=='' || $vo->aims[0]->A31=='00000000' || $vo->aims[0]->A31=='dd/mm/yyyy')
					$achieved = false; 
				else
				{
					$a31 = $vo->aims[0]->A31;
					$a35 = $vo->aims[0]->A35;
				}
				

				for($a=1; $a<=$vo->subaims; $a++)
				{
					if($vo->aims[$a]->A31=='' || $vo->aims[$a]->A31=='00000000' || $vo->aims[$a]->A31=='dd/mm/yyyy')
						$achieved = false; 
					else
					{
						$a31 = $vo->aims[$a]->A31;
						$a35 = $vo->aims[$a]->A35;
					}
				}
				
				if($achieved)
				{
					if($a35==1)
						$sc = 2;
					else
						$sc = 3;
					$a31 = Date::toMySQL($a31);
					$sql = "update tr set status_code = '$sc', closure_date = '$a31' where id = $tr_id";
					$st2 = $link->query($sql);
				}
			}
		}
	}
}
/*		
		$sql = "select * from ilr where contract_id = 5 and submission = 'W08'";
		$st2 = $link->query($sql);
		if($st2)
		{
			while($row = $st2->fetch())
			{	
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$L03 = $row['L03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
		
				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);
				
				$e = $pageDom->getElementsByTagName('programmeaim');
				$count = 0;
				foreach($e as $node)
				{
					$node->getElementsByTagName('A10')->item(0)->nodeValue = "70";
				}

			
				$ilr = $pageDom->saveXML();
		
				$ilr=substr($ilr,21);
				$ilr = str_replace("'", "&apos;" , $ilr);
				
				//throw new Exception($ilr);
				
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
				
			}
		}
	}
}
		
*/		
/*		
		// Updating existing ILRs using the batch file
		$handle = fopen("A11735800999091000101.W06","r");
		$st = fgets($handle);
		$tr_id = 0;		
		while(!feof($handle))
		{
			$st = fgets($handle);

			if(trim(substr($st,27,8))=='ZESF0001')
			{
				$l03 = trim(substr($st,8,12));
				$sql = "select * from ilr where L03 = '$l03'";
				$st2 = $link->query($sql);
				if($st2)
				{
					while($row = $st2->fetch())
					{	
						$ilr = $row['ilr'];
						$tr_id = $row['tr_id'];
						$L03 = $row['L03'];
						$contract_id = $row['contract_id'];
						$submission = $row['submission'];
				
						$pageDom = new DomDocument();
						$pageDom->loadXML($ilr);
		
						
						$e = $pageDom->getElementsByTagName('programmeaim');
						$count = 0;
						foreach($e as $node)
						{
							$node->getElementsByTagName('A01')->item(0)->nodeValue = trim(substr($st,0,6));
							$node->getElementsByTagName('A02')->item(0)->nodeValue = trim(substr($st,6,2));
							$node->getElementsByTagName('A03')->item(0)->nodeValue = trim(substr($st,8,12));
							$node->getElementsByTagName('A04')->item(0)->nodeValue = trim(substr($st,20,2));
							$node->getElementsByTagName('A05')->item(0)->nodeValue = trim(substr($st,22,2));
							$node->getElementsByTagName('A07')->item(0)->nodeValue = trim(substr($st,24,2));
							$node->getElementsByTagName('A08')->item(0)->nodeValue = trim(substr($st,26,1));
							$node->getElementsByTagName('A09')->item(0)->nodeValue = trim(substr($st,27,8));
							$node->getElementsByTagName('A10')->item(0)->nodeValue = trim(substr($st,35,2));
							$node->getElementsByTagName('A11a')->item(0)->nodeValue = trim(substr($st,37,3));
							$node->getElementsByTagName('A11b')->item(0)->nodeValue = trim(substr($st,40,3));
							$node->getElementsByTagName('A13')->item(0)->nodeValue = trim(substr($st,44,5));
							$node->getElementsByTagName('A14')->item(0)->nodeValue = (int)trim(substr($st,48,2));
							$node->getElementsByTagName('A15')->item(0)->nodeValue = (int)trim(substr($st,50,2));
							$node->getElementsByTagName('A16')->item(0)->nodeValue = (int)trim(substr($st,52,2));
							$node->getElementsByTagName('A17')->item(0)->nodeValue = trim(substr($st,54,1));
							$node->getElementsByTagName('A18')->item(0)->nodeValue = (int)trim(substr($st,55,2));
							$node->getElementsByTagName('A19')->item(0)->nodeValue = trim(substr($st,57,1));
							$node->getElementsByTagName('A20')->item(0)->nodeValue = trim(substr($st,58,1));
							$node->getElementsByTagName('A21')->item(0)->nodeValue = (int)trim(substr($st,59,2));
							$node->getElementsByTagName('A22')->item(0)->nodeValue = trim(substr($st,61,8));
							$node->getElementsByTagName('A23')->item(0)->nodeValue = trim(substr($st,69,8));
							$node->getElementsByTagName('A26')->item(0)->nodeValue = trim(substr($st,77,3));
							$node->getElementsByTagName('A27')->item(0)->nodeValue = trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4);
							$node->getElementsByTagName('A28')->item(0)->nodeValue = trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4);
							$node->getElementsByTagName('A31')->item(0)->nodeValue = trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4);
							$node->getElementsByTagName('A32')->item(0)->nodeValue = trim(substr($st,104,5));
							$node->getElementsByTagName('A34')->item(0)->nodeValue = trim(substr($st,109,1));
							$node->getElementsByTagName('A35')->item(0)->nodeValue = trim(substr($st,110,1));
							$node->getElementsByTagName('A36')->item(0)->nodeValue = trim(substr($st,111,6));
							$node->getElementsByTagName('A40')->item(0)->nodeValue = trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4);
							$node->getElementsByTagName('A44')->item(0)->nodeValue = trim(substr($st,125,30));
							$node->getElementsByTagName('A45')->item(0)->nodeValue = trim(substr($st,155,8));
							$node->getElementsByTagName('A46a')->item(0)->nodeValue = trim(substr($st,163,3));
							$node->getElementsByTagName('A46b')->item(0)->nodeValue = trim(substr($st,166,3));
							$node->getElementsByTagName('A47a')->item(0)->nodeValue = trim(substr($st,169,12));
							$node->getElementsByTagName('A47b')->item(0)->nodeValue = trim(substr($st,181,12));
							$node->getElementsByTagName('A48a')->item(0)->nodeValue = trim(substr($st,193,12));
							$node->getElementsByTagName('A48b')->item(0)->nodeValue = trim(substr($st,205,12));
							$node->getElementsByTagName('A49')->item(0)->nodeValue = trim(substr($st,217,5));
							$node->getElementsByTagName('A50')->item(0)->nodeValue = (int)trim(substr($st,222,2));
							$node->getElementsByTagName('A51a')->item(0)->nodeValue = trim(substr($st,224,3));
							$node->getElementsByTagName('A53')->item(0)->nodeValue = trim(substr($st,232,2));
							$node->getElementsByTagName('A54')->item(0)->nodeValue = trim(substr($st,234,10));
							$node->getElementsByTagName('A55')->item(0)->nodeValue = trim(substr($st,244,10));
							$node->getElementsByTagName('A56')->item(0)->nodeValue = trim(substr($st,254,8));
							$node->getElementsByTagName('A57')->item(0)->nodeValue = trim(substr($st,262,2));
							$node->getElementsByTagName('A58')->item(0)->nodeValue = trim(substr($st,264,2));
							$node->getElementsByTagName('A59')->item(0)->nodeValue = trim(substr($st,266,3));
							$node->getElementsByTagName('A60')->item(0)->nodeValue = trim(substr($st,269,3));

							@$node->removeChild($node->getElementsByTagName('A61')->item(0));
							@$node->removeChild($node->getElementsByTagName('A62')->item(0));
							@$node->removeChild($node->getElementsByTagName('A63')->item(0));
							@$node->removeChild($node->getElementsByTagName('A64')->item(0));
							@$node->removeChild($node->getElementsByTagName('A65')->item(0));
							@$node->removeChild($node->getElementsByTagName('A66')->item(0));
							@$node->removeChild($node->getElementsByTagName('A67')->item(0));
							@$node->removeChild($node->getElementsByTagName('A68')->item(0));

							$a61 = $pageDom->createElement("A61");
							$a62 = $pageDom->createElement("A62");
							$a63 = $pageDom->createElement("A63");
							$a64 = $pageDom->createElement("A64");
							$a65 = $pageDom->createElement("A65");
							$a66 = $pageDom->createElement("A66");
							$a67 = $pageDom->createElement("A67");
							$a68 = $pageDom->createElement("A68");
							
    						$node->appendChild($a61);
							$node->appendChild($a62);
							$node->appendChild($a63);
							$node->appendChild($a64);
							$node->appendChild($a65);
							$node->appendChild($a66);
							$node->appendChild($a67);
							$node->appendChild($a68);
																												
							$node->getElementsByTagName('A61')->item(0)->nodeValue = trim(substr($st,272,9));
							$node->getElementsByTagName('A62')->item(0)->nodeValue = trim(substr($st,281,3));
							$node->getElementsByTagName('A63')->item(0)->nodeValue = (int)trim(substr($st,284,2));
							$node->getElementsByTagName('A64')->item(0)->nodeValue = trim(substr($st,286,5));
							$node->getElementsByTagName('A65')->item(0)->nodeValue = trim(substr($st,291,5));
							$node->getElementsByTagName('A66')->item(0)->nodeValue = (int)trim(substr($st,296,2));
							$node->getElementsByTagName('A67')->item(0)->nodeValue = (int)trim(substr($st,298,2));
							$node->getElementsByTagName('A68')->item(0)->nodeValue = (int)trim(substr($st,300,2));
						}

					
						$ilr = $pageDom->saveXML();
				
						$ilr=substr($ilr,21);
						$ilr = str_replace("'", "&apos;" , $ilr);
						//throw new Exception($count);
						$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'";
						$st2 = $link->query($sql2);			
						if(!$st2)
							throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
						
					}
				}
			}
		}
	}
}
	
		
*/		
		
/*		
			// A routines to create ILRs against the training records with missing ILRs
			$sql1000 = "select * from tr where id not in (select tr_id from ilr)";
			$st1000 = $link->query($sql1000);
			if($st1000)
			{

				while($row1000 = $st1000->fetch())
				{	

					$tr_id = $row1000['id'];
					$contract_id = $row1000['contract_id'];
			
					$l03 = DAO::getSingleValue($link, "select l03 from tr where id = '$tr_id'");
					$start_date = DAO::getSingleValue($link, "select start_date from tr where id = '$tr_id'");
					$end_date = DAO::getSingleValue($link, "select target_date from tr where id = '$tr_id'");
					$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
					
					// Creating ILR
					$sql = "SELECT users.ni, users.l24, users.l14, users.l15, users.l16, users.l35, users.l48, users.l42a, users.l42b, contract_holder.upin, users.uln, l03, tr.l28a, tr.l28b, tr.l34a, tr.l34b, tr.l34c, tr.l34d, tr.l36, tr.l37, tr.l39, tr.l40a, tr.l40b, tr.l41a, tr.l41b, tr.l47, tr.id, tr.surname, tr.firstnames, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date, tr.ethnicity, tr.gender, learning_difficulties, disability,learning_difficulty, tr.home_postcode, TRIM(CONCAT(IF(tr.home_paon_start_number IS NOT NULL,TRIM(tr.home_paon_start_number),''),IF(tr.home_paon_start_suffix IS NOT NULL,TRIM(tr.home_paon_start_suffix),''),' ', IF(tr.home_paon_end_number IS NOT NULL,TRIM(tr.home_paon_end_number),''),IF(tr.home_paon_end_suffix IS NOT NULL,TRIM(tr.home_paon_end_suffix),''),' ' , IF(tr.home_paon_description IS NOT NULL,TRIM(tr.home_paon_description),''),' ',IF(tr.home_street_description IS NOT NULL,TRIM(tr.home_street_description),''))) AS L18, tr.home_locality, tr.home_town,tr.home_county, current_postcode, tr.home_telephone, country_of_domicile, tr.ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date, status_code,provider_location_id, tr.employer_id, provider_location.postcode AS lpcode, organisations.edrs AS edrs, organisations.legal_name AS employer_name,employer_location.postcode AS epcode FROM tr LEFT JOIN locations AS provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations AS employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN organisations AS contract_holder ON contract_holder.id = contract_holder LEFT JOIN users ON users.username = tr.username WHERE contract_id = $contract_id AND tr.id = $tr_id;";
		
					$contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id=$contract_id");
		
					$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' order by last_submission_date LIMIT 1;");
					
					$st = $link->query($sql);
					if($st)
					{
		
						while($row = $st->fetch())
						{	
							// here to create ilrs for the first time from training records.					
							$xml = '<ilr>';
							$xml .= "<learner>";
							$xml .= "<L01>" . $row['upin'] . "</L01>";
							$xml .= "<L02>" . "99" . "</L02>";
							$xml .= "<L03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</L03>";
							$xml .= "<L04>" . "10" . "</L04>";
		
							// No of learning aim data sets
							$sql ="select COUNT(*) from student_qualifications where tr_id ={$row['id']}";
							$learning_aims = DAO::getResultset($link,$sql);
							
							$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
							$xml .= "<L06>" . "00" . "</L06>";
							$xml .= "<L07>" . "00" . "</L07>";
							if($row['status_code']==4 || $row['status_code']=='4')
								$xml .= "<L08>" . "Y" . "</L08>";
							else
								$xml .= "<L08>" . "N" . "</L08>";
							$xml .= "<L09>" . $row['surname'] . 				"</L09>";
							$xml .= "<L10>" . $row['firstnames'] . 				"</L10>";
							$xml .= "<L11>" . $row['date_of_birth'] . 			"</L11>";
							$xml .= "<L12>" . $row['ethnicity'] . 				"</L12>";
							$xml .= "<L13>" . $row['gender'] . 					"</L13>";
							$xml .= "<L14>" . $row['l14'] .	"</L14>";
							$xml .= "<L15>" . $row['l15'] . 				"</L15>";
							$xml .= "<L16>" . $row['l16'] .		"</L16>";
							$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";
							$xml .= "<L18>" . $row['L18'] . "</L18>";
							$xml .= "<L19>" . $row['home_locality'] . 			"</L19>";
							$xml .= "<L20>" . $row['home_town'] . 				"</L20>";
							$xml .= "<L21>" . $row['home_county'] . 			"</L21>";
							$xml .= "<L22>" . $row['home_postcode'] .		"</L22>";
							$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";
							$xml .= "<L24>" . $row['l24'] .		"</L24>";
							$xml .= "<L25>" . "</L25>";
							$xml .= "<L26>" . $row['ni'] . 						"</L26>";
							$xml .= "<L27>" . "1" . "</L27>";
							$xml .= "<L28a>" . $row['l28a'] . "</L28a>";
							$xml .= "<L28b>" . $row['l28b'] . "</L28b>";
							$xml .= "<L29>" . "00" . "</L29>";
							$xml .= "<L31>" . "000000" . "</L31>";
							$xml .= "<L32>" . "00" . "</L32>";
							$xml .= "<L33>" . "0.0000" . "</L33>";
							$xml .= "<L34a>" . $row['l34a'] . "</L34a>";
							$xml .= "<L34b>" . $row['l34b'] . "</L34b>";
							$xml .= "<L34c>" . $row['l34c'] . "</L34c>";
							$xml .= "<L34d>" . $row['l34d'] . "</L34d>";
							$xml .= "<L35>" . $row['l35'] .	"</L35>";
							$xml .= "<L36>" . $row['l36'] . "</L36>";
							$xml .= "<L37>" . $row['l37'] . "</L37>";
							$xml .= "<L38>" . "00" . "</L38>";
							$xml .= "<L39>" . $row['l39'] . "</L39>";
							$xml .= "<L40a>" . $row['l40a'] . "</L40a>";
							$xml .= "<L40b>" . $row['l40b'] . "</L40b>";
							$xml .= "<L41a>" . $row['l41a'] . "</L41a>";	
							$xml .= "<L41b>" . $row['l41b'] . "</L41b>";	
							$xml .= "<L42a>" . $row['l42a'] . "</L42a>";	
							$xml .= "<L42b>" . $row['l42b'] . "</L42b>";	
							$xml .= "<L44>" . "</L44>";
		//					$xml .= "<L45>" . $row['uln'] . "</L45>";	
							$xml .= "<L45>9999999999</L45>";	
							$xml .= "<L46>" . "</L46>";
							$xml .= "<L47>" . $row['l47'] . "</L47>";
							$xml .= "<L48>" .  "</L48>";
							$xml .= "<L49a>00</L49a>";
							$xml .= "<L49b>00</L49b>";
							$xml .= "<L49c>00</L49c>";
							$xml .= "<L49d>00</L49d>";
												
							// Getting no. of sub aims
							$sql ="select count(*) from student_qualifications where tr_id ={$row['id']} and qualification_type!='NVQ'";
							$sub_aims = DAO::getSingleValue($link,$sql);
							
							$xml .= "<subaims>" . $sub_aims . "</subaims>";
							$xml .= "</learner>";
							$xml .= "<subaims>" . $sub_aims . "</subaims>";
		
							// Creating Programme aim
							$xml .= "<programmeaim>";
							$xml .= "<A02>" . "99" . "</A02>";
							$xml .= "<A04>" . "35" . "</A04>";
							$xml .= "<A09>" . "ZPROG001" . "</A09>";
							$xml .= "<A10>" . "45" . "</A10>";
							$xml .= "<A15>" . "</A15>";
							$xml .= "<A16>" . "</A16>";
							$xml .= "<A26>" . "</A26>";
							$xml .= "<A27>" . $start_date . "</A27>";
							$xml .= "<A28>" . $end_date . "</A28>";
							$xml .= "<A23>" . $tr->work_postcode . "</A23>";
							$xml .= "<A51a>100</A51a>";
							$xml .= "<A14>" . "</A14>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A02>" . "</A02>";
							$xml .= "<A31>" . "</A31>";
							$xml .= "<A40>" . "</A40>";
							$xml .= "<A34>" . "</A34>";
							$xml .= "<A35>" . "</A35>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "</programmeaim>";
							
							
							// Creating main aim					
							$sql_main = "select student_qualifications.*, tr.start_date as lsd,tr.work_postcode, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']} and framework_qualifications.main_aim=1";
							$st2 = $link->query($sql_main);
							if($st2)
							{
								while($row_main = $st2->fetch())
								{	
									$xml .= "<main>";
									$xml .= "<A01>" . $row['upin'] . "</A01>";
									$xml .= "<A02>99</A02>";
									$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</A03>";
									$xml .= "<A04>" . "30" . "</A04>";
									$xml .= "<A05>" . "01" . "</A05>";
									$xml .= "<A06>" . "00" . "</A06>";
									$xml .= "<A07>" . "00" . "</A07>";
									$xml .= "<A08>" . "2" . "</A08>";
									$xml .= "<A09>" . str_replace("/" , "", $row_main['id']) . "</A09>";
									$xml .= "<A10>" . "</A10>";
									$xml .= "<A11a>" . "000" . "</A11a>";
									$xml .= "<A11b>" . "000" . "</A11b>";
									$xml .= "<A12a>" . "000" . "</A12a>";
									$xml .= "<A12b>" . "000" . "</A12b>";
									$xml .= "<A13>" . "00000" . "</A13>";
									$xml .= "<A14>" . "00" . "</A14>";
									$xml .= "<A15>" . "</A15>";
									$xml .= "<A16>" . "</A16>";
									$xml .= "<A17>" . "0" . "</A17>";
									$xml .= "<A18>" . "</A18>";
									$xml .= "<A19>" . "0" . "</A19>";
									$xml .= "<A20>" . "0" . "</A20>";
									$xml .= "<A21>" . "00" . "</A21>";
									$xml .= "<A22>" . "      " . "</A22>";
									$xml .= "<A23>" . $row_main['work_postcode'] . "</A23>";
									$xml .= "<A24>" . "</A24>";
									$xml .= "<A26>" . "</A26>";
									$xml .= "<A27>" . substr($row_main['lsd'],8,2) . '/' . substr($row_main['lsd'],5,2) . '/' . substr($row_main['lsd'],0,4) . "</A27>";
									$xml .= "<A28>" . substr($row_main['led'],8,2) . '/' . substr($row_main['led'],5,2) . '/' . substr($row_main['led'],0,4) . "</A28>";
									$xml .= "<A31>" . $row_main['actual_end_date'] . "</A31>";
									$xml .= "<A32>" . "</A32>";
									$xml .= "<A33>" . "     " . "</A33>";
									$xml .= "<A34>" . "</A34>";
									$xml .= "<A35>" . "</A35>";
									$xml .= "<A36>" . "   " . "</A36>";
									$xml .= "<A37>" . $row_main['unitsCompleted'] . "</A37>";
									$xml .= "<A38>" . $row_main['units'] . "</A38>";
									$xml .= "<A39>" . "0" . "</A39>";
									$xml .= "<A40>" . $row_main['achievement_date'] . "</A40>";
									$xml .= "<A43>" . $row['closure_date'] . "</A43>";
		
									if($row['edrs']!='')
										$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
									else
										$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
									
									$xml .= "<A45>" . $row['epcode'] . "</A45>";
									$xml .= "<A46a>" . "</A46a>";
									$xml .= "<A46b>" . "</A46b>";
									$xml .= "<A47a>" . "</A47a>";
									$xml .= "<A47b>" . "</A47b>";
									$xml .= "<A48a>" . "</A48a>";
									$xml .= "<A48b>" . "</A48b>";
									$xml .= "<A49>" . "     " . "</A49>";
									$xml .= "<A50>" . "</A50>";
									$xml .= "<A51a>100</A51a>";
									$xml .= "<A52>" . "00000" . "</A52>";
									$xml .= "<A53>" . "</A53>";
									$xml .= "<A54>" . "</A54>";
									$xml .= "<A55>9999999999</A55>";
									$xml .= "<A56>" . "</A56>";
									$xml .= "<A57>" . "00" . "</A57>";
									$xml .= "</main>";
								}
							}
							
							
							// Creating Sub Aims out of framework
							$sql_main = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']} and framework_qualifications.main_aim<>1";
							$st3 = $link->query($sql_main);
							if($st3)
							{
								$learningaim=2;	
								while($row_sub = $st3->fetch())
								{	
									$xml .= "<subaim>";
									$xml .= "<A01>" . $row['upin'] . "</A01>";
									$xml .= "<A02>99</A02>";
									$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) .  "</A03>";
									$xml .= "<A04>" . "30" . "</A04>";
									$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
									$learningaim++;
									$xml .= "<A06>00</A06>";
									$xml .= "<A07>" . "00" . "</A07>";
									$xml .= "<A08>" . "2" . "</A08>";
									$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
									$xml .= "<A10>" . "</A10>";
									$xml .= "<A11a>" . "000" . "</A11a>";
									$xml .= "<A11b>" . "000" . "</A11b>";
									$xml .= "<A12a>" . "000" . "</A12a>";
									$xml .= "<A12b>" . "000" . "</A12b>";
									$xml .= "<A13>" . "00000" . "</A13>";
									$xml .= "<A14>" . "00" . "</A14>";
									$xml .= "<A15>" . "</A15>";
									$xml .= "<A16>" . "</A16>";
									$xml .= "<A17>" . "0" . "</A17>";
									$xml .= "<A18>" . "</A18>";
									$xml .= "<A19>" . "0" . "</A19>";
									$xml .= "<A20>" . "0" . "</A20>";
									$xml .= "<A21>" . "00" . "</A21>";
									$xml .= "<A22>" . "      " . "</A22>";
									$xml .= "<A23>" . $row_sub['work_postcode'] . "</A23>";
									$xml .= "<A24>" . "</A24>";
									$xml .= "<A26>" . "</A26>";
									$xml .= "<A27>" . substr($row_sub['lsd'],8,2) . '/' . substr($row_sub['lsd'],5,2) . '/' . substr($row_sub['lsd'],0,4) . "</A27>";
									$xml .= "<A28>" . substr($row_sub['led'],8,2) . '/' . substr($row_sub['led'],5,2) . '/' . substr($row_sub['led'],0,4) . "</A28>";
									$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
									$xml .= "<A32>" . "</A32>";
									$xml .= "<A33>" . "     " . "</A33>";
									$xml .= "<A34>" . "</A34>";
									$xml .= "<A35>" . "</A35>";
									$xml .= "<A36>" . "   " . "</A36>";
									$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
									$xml .= "<A38>" . $row_sub['units'] . "</A38>";
									$xml .= "<A39>" . "0" . "</A39>";
									$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
									$xml .= "<A43>" . $row['closure_date'] . "</A43>";
		
									if($row['edrs']!='')
										$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
									else
										$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
									
									$xml .= "<A45>" . "</A45>";
									$xml .= "<A46a>" . "</A46a>";
									$xml .= "<A46b>" . "</A46b>";
									$xml .= "<A47a>" . "</A47a>";
									$xml .= "<A47b>" . "</A47b>";
									$xml .= "<A48a>" . "</A48a>";
									$xml .= "<A48b>" . "</A48b>";
									$xml .= "<A49>" . "     " . "</A49>";
									$xml .= "<A50>" . "</A50>";
									$xml .= "<A51a>100</A51a>";
									$xml .= "<A52>" . "00000" . "</A52>";
									$xml .= "<A53>" . "</A53>";
									$xml .= "<A54>" . "</A54>";
									$xml .= "<A55>9999999999</A55>";
									$xml .= "<A56>" . "</A56>";
									$xml .= "<A57>" . "00" . "</A57>";
									$xml .= "</subaim>";
								}
							}
		
							// Creating Sub Aims out of additional qualifications
							$sql_sub = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id where tr_id = {$row['id']} and framework_id=0";
							
							$st4 = $link->query($sql_sub);	
							if($st4)
							{
								$learningaim=2;	
								while($row_sub = $st4->fetch())
								{	
									$xml .= "<subaim>";
									$xml .= "<A01>" . $row['upin'] . "</A01>";
									$xml .= "<A02>99</A02>";
									$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</A03>";
									$xml .= "<A04>" . "30" . "</A04>";
									$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
									$learningaim++;
									$xml .= "<A06>00</A06>";
									$xml .= "<A07>" . "00" . "</A07>";
									$xml .= "<A08>" . "2" . "</A08>";
									$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
									$xml .= "<A10>" . "</A10>";
									$xml .= "<A11a>" . "000" . "</A11a>";
									$xml .= "<A11b>" . "000" . "</A11b>";
									$xml .= "<A12a>" . "000" . "</A12a>";
									$xml .= "<A12b>" . "000" . "</A12b>";
									$xml .= "<A13>" . "00000" . "</A13>";
									$xml .= "<A14>" . "00" . "</A14>";
									$xml .= "<A15>" . "</A15>";
									$xml .= "<A16>" . "</A16>";
									$xml .= "<A17>" . "0" . "</A17>";
									$xml .= "<A18>" . "</A18>";
									$xml .= "<A19>" . "0" . "</A19>";
									$xml .= "<A20>" . "0" . "</A20>";
									$xml .= "<A21>" . "00" . "</A21>";
									$xml .= "<A22>" . "      " . "</A22>";
									$xml .= "<A23>" . $row_sub['work_postcode'] . "</A23>";
									$xml .= "<A24>" . "</A24>";
									$xml .= "<A26>" . "</A26>";
									$xml .= "<A27>" . substr($row_sub['lsd'],8,2) . '/' . substr($row_sub['lsd'],5,2) . '/' . substr($row_sub['lsd'],0,4) . "</A27>";
									$xml .= "<A28>" . substr($row_sub['led'],8,2) . '/' . substr($row_sub['led'],5,2) . '/' . substr($row_sub['led'],0,4) . "</A28>";
									$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
									$xml .= "<A32>" . "</A32>";
									$xml .= "<A33>" . "     " . "</A33>";
									$xml .= "<A34>" . "</A34>";
									$xml .= "<A35>" . "</A35>";
									$xml .= "<A36>" . "   " . "</A36>";
									$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
									$xml .= "<A38>" . $row_sub['units'] . "</A38>";
									$xml .= "<A39>" . "0" . "</A39>";
									$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
									$xml .= "<A43>" . $row['closure_date'] . "</A43>";
		
									if($row['edrs']!='')
										$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
									else
										$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
		
									$xml .= "<A45>" . "</A45>";
									$xml .= "<A46a>" . "</A46a>";
									$xml .= "<A46b>" . "</A46b>";
									$xml .= "<A47a>" . "</A47a>";
									$xml .= "<A47b>" . "</A47b>";
									$xml .= "<A48a>" . "</A48a>";
									$xml .= "<A48b>" . "</A48b>";
									$xml .= "<A49>" . "     " . "</A49>";
									$xml .= "<A50>" . "</A50>";
									$xml .= "<A51a>100</A51a>";
									$xml .= "<A52>" . "00000" . "</A52>";
									$xml .= "<A53>" . "</A53>";
									$xml .= "<A54>" . "</A54>";
									$xml .= "<A55>9999999999</A55>";
									$xml .= "<A56>" . "</A56>";
									$xml .= "<A57>" . "00" . "</A57>";
									$xml .= "</subaim>";
								}
							}
							
							
							
							$xml .= "</ilr>";
							$xml = str_replace("&", "&amp;", $xml);
							$xml = str_replace("'", "&apos;", $xml);
							// getting contract type 
						
							$sql = "Select contract_type from contracts where id ='$contract_id'";
							$contract_type = DAO::getResultset($link, $sql);
							$contract_type = $contract_type[0][0];					
							
							// $xml = addslashes((string)$xml);
							$contract = addslashes((string)$contract_id);
							$contract_type=addslashes((string)$contract_type);
							
							$upin = $row['upin'];
							//$l03 = $row['l03'];
							
							$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin','$l03','0','$xml','$submission','$contract_type','$tr_id','0','0','0','1','$contract');";
							$st5 = $link->query($sql);			
							if($st5 == false)
								throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());
													
						}
					}
				}
			}
	}
}	
*/		
/*		
		// It will create new milestones throughout the system
		$sql = "SELECT *, PERIOD_DIFF(CONCAT(LEFT(end_date,4),MID(end_date,6,2)),CONCAT(LEFT(start_date,4),MID(start_date,6,2))) as months FROM student_qualifications";
		$st = $link->query($sql);
		$unit=0;
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($row['evidences']));
			
			$evidences = $pageDom->getElementsByTagName('unit');
			foreach($evidences as $evidence)
			{
				$unit_id = $evidence->getAttribute('owner_reference');
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				$qualification_id = $row['id'];
				$internaltitle = $row['internaltitle'];

				$m = Array();
				for($a = 1; $a<=$row['months']; $a++)
				{
					if($a==$row['months'])
						$m[] = 100;
					else
						$m[] = sprintf("%.1f", 100 / $row['months'] * $a);
				}
				for($a = $row['months']+1; $a<=36; $a++)
				{
					$m[] = 100;
				}
				
				DAO::execute($link, "insert into student_milestones values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
			}
		}
	}
}
		
*/		
/*		
		// Finding any unit with missing owner_reference in qualification database
		$sql = "SELECT * FROM qualifications where id='500/2181/3'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($row['evidences']));
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				if($node->getAttribute('title')=='Reading and Writing - Level 1')
				{	
					$node->setAttribute('owner_reference','RW');
				}
				if($node->getAttribute('title')=='Speaking and Listening - Level 1')
				{	
					$node->setAttribute('owner_reference','SL');
				}
			}	
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
		
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];

			$sql2 = "update qualifications set evidences = '$qual' where id = '$id' and internaltitle = '$internaltitle'";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}

		// Finding any unit with missing owner_reference in framework qualification database
		$sql = "SELECT * FROM framework_qualifications where id='500/2181/3'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($row['evidences']));
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				if($node->getAttribute('title')=='Reading and Writing - Level 1')
				{	
					$node->setAttribute('owner_reference','RW');
				}
				if($node->getAttribute('title')=='Speaking and Listening - Level 1')
				{	
					$node->setAttribute('owner_reference','SL');
				}
			}	
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
		
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update framework_qualifications set evidences = '$qual' where id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
		// Finding any unit with missing owner_reference across all learners
		$sql = "SELECT * FROM student_qualifications where id='500/2181/3'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML(utf8_encode($row['evidences']));
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				if($node->getAttribute('title')=='Reading and Writing - Level 1')
				{	
					$node->setAttribute('owner_reference','RW');
				}
				if($node->getAttribute('title')=='Speaking and Listening - Level 1')
				{	
					$node->setAttribute('owner_reference','SL');
				}
			}	
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
		
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
		
*/				
/*
		$sunesis = Array();
		$sql = "SELECT * FROM ilr";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$vo = Ilr2008::loadFromDatabase($link, $row['submission'], $row['contract_id'], $row['tr_id'], $row['L03']);
			if(!in_array($vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09,$sunesis))
				$sunesis[] = $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09;
		}
		
		$handle = fopen("A11735800999091000101.W06","r");
		$csv = Array();
		$st = fgets($handle);
		while(!feof($handle))
		{
			$st = fgets($handle);
			
			if(trim(substr($st,20,2))=='10')
			{
				$firstnames = trim(substr($st,47,40));
				$surname = trim(substr($st,27,20));
			}
			else
			{
				$l03 = trim(substr($st,8,12));
				$a09 = trim(substr($st,27,8));
				$a27 = trim(substr($st,80,2)) . "/" . trim(substr($st,82,2)) . "/" . trim(substr($st,84,4));
				$a28 = trim(substr($st,88,2)) . "/" . trim(substr($st,90,2)) . "/" . trim(substr($st,92,4));
				$courses = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(course_id) FROM course_qualifications_dates  where replace(qualification_id,'/','') = '$a09' GROUP BY qualification_id");

				if(!in_array($firstnames . ' ' . $surname,$sunesis))
					if(!in_array($firstnames . ' ' . $surname,$csv))
						$csv[] = $firstnames . ' ' . $surname . ' ' . $a09 . ' ' . $l03 . ' ' . $a27 . ' ' . $a28 . ',' . $courses;
				
			}			
				
		//	if(trim(substr($st,20,2))=='10')
		//	{	
		//		$st = fgets($handle);
		//		if(trim(substr($st,27,8))=='ZESF0001')
		//			$st = fgets($handle);
				
		//		$a40 = trim(substr($st,96,8));
				
		//	}	
		}

		pre($csv);
	}
}
		
		
*/		
		
		
				
/*		$handle = fopen("A11735800999091000101.W06","r");
		$csv = '';
		$st = fgets($handle);
		while(!feof($handle))
		{
			$st = fgets($handle);
			
			if(trim(substr($st,20,2))=='10')
			{
				$firstnames = trim(substr($st,47,40));
				$surname = trim(substr($st,27,20));
			}
			else
			{
				$l03 = trim(substr($st,8,12));
				$a09 = trim(substr($st,27,8));
				$a27 = trim(substr($st,80,2)) . "/" . trim(substr($st,82,2)) . "/" . trim(substr($st,84,4));
				$a28 = trim(substr($st,88,2)) . "/" . trim(substr($st,90,2)) . "/" . trim(substr($st,92,4));
				$courses = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(course_id) FROM course_qualifications_dates  where replace(qualification_id,'/','') = '$a09' GROUP BY qualification_id");
				
				$csv .= $l03 . "," . $firstnames . "," . $surname . "," . $a09 . "," . $a27 . "," . $a28 . "," . $courses . "\n";
				
			}
		}

		pre($csv);
	}
}

*/		
/*		
		// Choosing or not choosing units based on two rules
		// 1. If a learner is doing only BIT then switch off all the units under "Team Member Associated Units"
		// 2. If a learner is doing PMO and BIT both, then all the units under unit group "Team Leader Associated Training Units"
		//    and "Group Leader/ Section Manager Associated Training Units" should be switched off
		$sql = "SELECT * FROM student_qualifications where id = '500/2154/0'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$username = $row['username'];
			$count = DAO::getSingleValue($link, "select count(*) from student_qualifications where username = '$username'");
			if($count==1) 
			{
				$pageDom = new DomDocument();
				$pageDom->loadXML($row['evidences']);
				
				$evidences = $pageDom->getElementsByTagName('unit');
				foreach($evidences as $evidence)
				{
					$or = $evidence->getAttribute('owner_reference');
					if($or=='TMKaizen1' || $or=='TMQCMem' || $or=='TMKaiRptg' || $or=='TMSafety')
						$evidence->setAttribute("chosen", "false");						
				}
			}		
			else
			{
				$pageDom = new DomDocument();
				$pageDom->loadXML($row['evidences']);
				
				$evidences = $pageDom->getElementsByTagName('unit');
				foreach($evidences as $evidence)
				{
					$or = $evidence->getAttribute('owner_reference');
					if($or=='TLPDCARO' || $or=='TLSafeEye' || $or=='TLPDCA' || $or=='GL/GMCoach' || $or=='GL/GMSafeLdr' || $or=='GL/GMQCAdvisor')
						$evidence->setAttribute("chosen", "false");						
				}
			}
			
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
			
		}
	}
}		
		
*/
				
/*		
		// It will create new milestones throughout the system
		$sql = "SELECT *, PERIOD_DIFF(CONCAT(LEFT(end_date,4),MID(end_date,6,2)),CONCAT(LEFT(start_date,4),MID(start_date,6,2))) as months FROM student_qualifications";
		$st = $link->query($sql);
		$unit=0;
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);
			
			$evidences = $pageDom->getElementsByTagName('unit');
			foreach($evidences as $evidence)
			{
				$unit_id = $evidence->getAttribute('owner_reference');
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				$qualification_id = $row['id'];
				$internaltitle = $row['internaltitle'];

				$m = Array();
				for($a = 1; $a<=$row['months']; $a++)
				{
					if($a==$row['months'])
						$m[] = 100;
					else
						$m[] = sprintf("%.1f", 100 / $row['months'] * $a);
				}
				for($a = $row['months']+1; $a<=36; $a++)
				{
					$m[] = 100;
				}
				
				DAO::execute($link, "insert into student_milestones values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
			}
		}
	}
}
		
*/		
		
		
		
/*		
		$sql = "SELECT * FROM student_qualifications where id = '100/3955/7'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);
			
			$evidences = $pageDom->getElementsByTagName('evidence');
			foreach($evidences as $evidence)
			{
				if($evidence->getAttribute('title')=='Controlling manufacturing operations')
					$evidence->parentNode->removeChild($evidence);
			}
			
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
	
*/	
	
/*		
		// to display how many learner have unit sign off marker
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		$report= Array();
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			try
			{
				@$pageDom->loadXML(utf8_decode($row['evidences']));
			}
			catch(Exception $e)
			{
				throw new Exception($row['id']);
			}
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				// Remove all the tracking
				$or = $node->getAttribute('fc');
				if($or=='true')
					if(!in_array($row['tr_id'],$report))
						$report[] = $row['tr_id'];
			}							
		}
		pre($report);
	}
}
		
*/		
		
/*		
		// to remove unit sign off marker from the units have got evidence(S) marked too
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);
			
			$units = $pageDom->getElementsByTagName('unit');
			foreach($units as $unit)
			{
				$removemarker = false;
				$elements = $unit->getElementsByTagName('element');
				foreach($elements as $element)
				{
					$evidences = $element->getElementsByTagName('evidence');
					foreach($evidences as $evidence)
					{
						if($evidence->getAttribute('status')=='a')
							$removemarker = true;
					}
				}
				if($removemarker)
					$unit->setAttribute("fc", "false");
			}
			
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
	
		
*/		
		
		
		
/*
		$sql = "SELECT * FROM users where type = 5";
		$st = $link->query($sql);
		$report='';
		while($row = $st->fetch())
		{
			$username = $row['username'];		
			$tr_id = DAO::getSingleValue($link, "select id from tr where username = '$username' order by id DESC LIMIT 1");
			if($tr_id!='')
			{
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id desc, submission desc limit 1");
			}
		}
	}
}	
*/
				
/*		
		$sql = "SELECT * FROM student_qualifications";
		$st = $link->query($sql);
		$report='';
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			try
			{
				@$pageDom->loadXML(utf8_decode($row['evidences']));
			}
			catch(Exception $e)
			{
				throw new Exception($row['id']);
			}
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				// Remove all the tracking
				$or = $node->getAttribute('proportion');
				if((int)$or==0)
					$report .= $row['internaltitle'] . "\n";
			}							
		}
		pre($report);
	}
}
*/		
/*		
		$sql = "SELECT * FROM student_qualifications where id = '500/2154/0'";
		$st = $link->query($sql);
		while($row = $st->fetch())
		{
			$pageDom = new DomDocument();
			$pageDom->loadXML($row['evidences']);
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				// Remove all the tracking
				$or = $node->getAttribute('owner_reference');
				if($or=='TMKaizen1' || $or=='TMQCMem' || $or=='TMKaiRptg' || $or=='TMSafety')
					$node->setAttribute('proportion','10');
			}							
	
			// Recalculating percentage
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			foreach($units as $unit)
			{
				$no_of_elements = 0;
				$total_element_percentage = 0;
				$elements = $unit->getElementsByTagName('element');
				foreach($elements as $element)
				{
					$no_of_elements++;
					
					$evidences = $element->getElementsByTagName('evidence');
					$achieved_evidences=0;
					$no_of_evidences = 0;
					foreach($evidences as $evidence)
					{
						$no_of_evidences++;
						if($evidence->getAttribute('status')=='a')
							$achieved_evidences++;
					}
					
					$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
					$total_element_percentage += $elementPercentage;
					$element->setAttribute("percentage",$elementPercentage);
				}
				
				if($no_of_elements!=0)
					$unitPercentage = $total_element_percentage / $no_of_elements;
				else
					$unitPercentage = $total_element_percentage;
				
				$unitProportion = $unit->getAttribute('proportion');
				$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
				$unit->setAttribute("percentage",$unitPercentage);
			}
			
			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);
			
	
			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$id = $row['id'];
			$internaltitle = $row['internaltitle'];
			$tr_id = $row['tr_id'];
			$framework_id = $row['framework_id'];
			
			$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$st2 = $link->query($sql2);			
			if(!$st2)
				throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
		}
	}
}
		
*/		
/*		
		$handle = fopen("exg.W05","r");
		$csv = '';
		$st = fgets($handle);
		while(!feof($handle))
		{
			$st = fgets($handle);
			
			if(trim(substr($st,20,2))=='10')
			{
				$firstnames = trim(substr($st,47,40));
				$surname = trim(substr($st,27,20));
			}
			else
			{
				$l03 = trim(substr($st,8,12));
				$a09 = trim(substr($st,27,8));
				$a27 = trim(substr($st,80,2)) . "/" . trim(substr($st,82,2)) . "/" . trim(substr($st,84,4));
				$a28 = trim(substr($st,88,2)) . "/" . trim(substr($st,90,2)) . "/" . trim(substr($st,92,4));
				$csv .= $l03 . "," . $firstnames . "," . $surname . "," . $a09 . "," . $a27 . "," . $a28 . "\n";
			}
		}

		pre($csv);
	}
}
*/		
		
		

/*		
		// PMO 
		$handle = fopen("tmuk.csv","r");
		$st = fgets($handle);
		
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);
			
			$member = trim($arr[0]);	
			if(trim($arr[1])=='')
				$start_date = 'NULL';
			else
				$start_date = "'" . Date::toMySQL(trim($arr[1])) . "'";

			$fundamental = trim($arr[2]);
			$upk = trim($arr[3]);
			$assessment1 = trim($arr[4]);
			$assessment2 = trim($arr[5]);
			$assessment3 = trim($arr[6]);
			
			if(trim($arr[7])=='')
				$end_date = 'NULL';
			else
				$end_date = "'" . Date::toMySQL(trim($arr[7])) . "'";
			
			$username = DAO::getSingleValue($link, "select username from users where CAST(enrollment_no AS SIGNED) = '$member' ");

			$sql = "SELECT * FROM student_qualifications where username = '$username' and id = '100/3955/7'";
			$st = $link->query($sql);
			while($row = $st->fetch())
			{
		
				$pageDom = new DomDocument();
				$pageDom->loadXML($row['evidences']);
				$e = $pageDom->getElementsByTagName('evidence');
				foreach($e as $node)
				{
		
					// Remove all the tracking
					$node->setAttribute('status','');
					
					if($node->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
						$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');
						
					if($node->parentNode->nodeName == 'units')
						$group = $node->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->getAttribute('title');
					elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'units')
						$group = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('title');

					if($group=='')
						pre("pagal");	
						
					if($unitreference == "Fundamental Skills  7.5 hours" && $node->getAttribute('title') == "Assessment 1")
					{
						if($fundamental!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$fundamental);				
						}
					}
					elseif($unitreference == "UPK Underpinning Knowledge  7.5 hours" && $node->getAttribute('title')=="Assessment 1")
					{
						if($upk!='')	
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$upk);				
						}
					}
					elseif(($group=="Production" || $group=="A - Mandatory units") && $node->getAttribute('title')=="Assessment 1")
					{
						if($assessment1!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$assessment1);				
						}
					}
					elseif(($group=="Production" || $group=="A - Mandatory units") && $node->getAttribute('title')=="Assessment 2")
					{
						if($assessment2!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$assessment2);				
						}
					}
					elseif(($group=="Production" || $group=="A - Mandatory units") && $node->getAttribute('title')=="Assessment 3")
					{
						if($assessment3!='')
						{
							$node->setAttribute('status','a');
							$node->setAttribute('date',$assessment3);				
						}
					} 
				}
		
				// Recalculating percentage
			
				$units = $pageDom->getElementsByTagName('unit');
				$total_unit_percentage = 0;
				foreach($units as $unit)
				{
					$no_of_elements = 0;
					$total_element_percentage = 0;
					$elements = $unit->getElementsByTagName('element');
					foreach($elements as $element)
					{
						$no_of_elements++;
						
						$evidences = $element->getElementsByTagName('evidence');
						$achieved_evidences=0;
						$no_of_evidences = 0;
						foreach($evidences as $evidence)
						{
							$no_of_evidences++;
							if($evidence->getAttribute('status')=='a')
								$achieved_evidences++;
						}
						
						$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
						$total_element_percentage += $elementPercentage;
						$element->setAttribute("percentage",$elementPercentage);
					}
					
					if($no_of_elements!=0)
						$unitPercentage = $total_element_percentage / $no_of_elements;
					else
						$unitPercentage = $total_element_percentage;
					
					$unitProportion = $unit->getAttribute('proportion');
					$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
					$unit->setAttribute("percentage",$unitPercentage);
				}
				
				$roots = $pageDom->getElementsByTagName('root');
				foreach($roots as $root)
					$root->setAttribute("percentage", $total_unit_percentage);
				
		
				$qual = $pageDom->saveXML();
				$qual=substr($qual,21);
				
				$qual= str_replace("'","apos;",$qual);
				
				$id = $row['id'];
				$internaltitle = $row['internaltitle'];
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				
				$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, start_date = $start_date, end_date = $end_date ,evidences = '$qual' where tr_id=$tr_id and id = '$id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql2. $link->errorCode());
			}
		}
	}
}
		
*/		
		
// Update all the tracking side according to ilr 		
/* 		
$dates = 0;
		
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id ORDER BY contract_id DESC, submission DESC LIMIT 1");
				
				if($ilr!='')
				{
					$ilrxml = new SimpleXMLElement($ilr);
					foreach ($ilrxml->main as $item) 
					{
						$a14 = ($item->A14=='')?'null':$item->A14;
						$a18 = ($item->A18=='')?'null':$item->A18;
						$a51a = ($item->A51a=='')?'null':$item->A51a;
						$a16 = ($item->A16=='')?'null':$item->A16;
						$id = $item->A09;
						$a34 = ($item->A34=='')?'null':$item->A34;
						$a35 = ($item->A35=='')?'null':$item->A35;
						
						
						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy')
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = "00-00-0000";

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = "00-00-0000";
							

						$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
						$st2 = $link->query($sql2);
					}
					foreach ($ilrxml->subaim as $item) 
					{
						$a14 = ($item->A14!='')?$item->A14:"00";
						$a18 = $item->A18;
						$a51a = ($item->A51a=='')?'null':$item->A51a;
						$a16 = ($item->A16!='')?$item->A16:"00";
						$id = $item->A09;
						$a34 = $item->A34;
						$a35 = $item->A35;

						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);

						
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy' && $item->A31!='' )
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = '00-00-0000';

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy' && $item->A40!='')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = '00-00-0000';
												
						$sql3 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
						if(!$st3 = $link->query($sql3))
						{	
							throw new Exception($item->A40 . $sql3."]".implode($link->errorInfo()));
						}
					} 
				}
			}
			
			//throw new Exception($dates);
		}
		
		
		$link->query("update student_qualifications set achievement_date = NULL where achievement_date = '0000-00-00'");
		$link->query("update student_qualifications set actual_end_date = NULL where actual_end_date = '0000-00-00'");
		
	}
}
		
		
		
		
		
		
		
		
/*		
		$sql = "SELECT * FROM ilr LEFT JOIN tr on ilr.tr_id = tr.id where submission = 'W04' ";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$count = 0;
			$valid = 0;
			$dates = Array();
			while($row = $st->fetch())
			{
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$l03 = $row['L03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
				$surname = $row['surname'];
				$firstnames = $row['firstnames'];
				
		//		throw new Exception($firstnames . $surname . $start_date->formatShort() . $target_date->formatShort());

				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);

				
				$e2 = $pageDom->getElementsByTagName('A18');
				$count++;
				foreach($e2 as $node2)
				{
					$dates[$node2->parentNode->getElementsByTagName('A09')->item(0)->nodeValue] = $node2->nodeValue;
					if($node2->nodeValue!='22' && $node2->nodeValue!='23' && $node2->parentNode->getElementsByTagName('A09')->item(0)->nodeValue!='ZPROG001')
						$dates[$l03] = $firstnames . " " . $surname ;
				}
			}
		}
		pre($dates);
	}
}	
		
		
		
/*
$dates = 0;
		
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id ORDER BY contract_id DESC, submission DESC LIMIT 1");
				
				if($ilr!='')
				{
					$ilrxml = new SimpleXMLElement($ilr);
					foreach ($ilrxml->main as $item) 
					{
						$a14 = ($item->A14=='')?'null':$item->A14;
						$a18 = ($item->A18=='')?'null':$item->A18;
						$a51a = ($item->A51a=='')?'null':$item->A51a;
						$a16 = ($item->A16=='')?'null':$item->A16;
						$id = $item->A09;
						$a34 = ($item->A34=='')?'null':$item->A34;
						$a35 = ($item->A35=='')?'null':$item->A35;
						
						
						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy')
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = "00-00-0000";

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = "00-00-0000";
							
				//		$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16 , a34 = $a34, a35 = $a35 where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";

						$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
							
					//	if($tr_id == 112)
					//	throw new Exception($sql);
						
						$st2 = $link->query($sql2);

						//$st3 = $link->query("update test set id = '$tr_id'");
					}
					foreach ($ilrxml->subaim as $item) 
					{
						$a14 = ($item->A14!='')?$item->A14:"00";
						$a18 = $item->A18;
						$a51a = ($item->A51a!='')?$item->A51a:"100";
						$a16 = ($item->A16!='')?$item->A16:"00";
						$id = $item->A09;
						$a34 = $item->A34;
						$a35 = $item->A35;

						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);

						
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy' && $item->A31!='' )
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = '00-00-0000';

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy' && $item->A40!='')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = '00-00-0000';
												
//						$sql3 = "update student_qualifications set a14 = '$a14', a18 = '$a18', a51a = '$a51a', a16 = '$a16', a34 = '$a34', a35 = '$a35' where REPLACE(id,'/','') = '$id'";
						$sql3 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
						if(!$st3 = $link->query($sql3))
						{	
							//throw new Exception($a40);
							throw new Exception($item->A40 . $sql3."]".implode($link->errorInfo()));
						}
					} 
				}
			}
			
			throw new Exception($dates);
		}
	}
}
		
		
/*

		$sql = "SELECT * FROM ilr where submission = 'W04'";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$l03 = $row['L03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
				
		//		throw new Exception($firstnames . $surname . $start_date->formatShort() . $target_date->formatShort());

				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);

				
				$ilr2 = DAO::getSingleValue($link, "select ilr from ilr where l03 = '$l03' and tr_id = '$tr_id' and submission = 'W13'");

				if($ilr2!='')
				{				
					$pageDom2 = new DomDocument();
					$pageDom2->loadXML($ilr2);

					$dates = Array();
					$e2 = $pageDom2->getElementsByTagName('A28');
					$count = 0;
					foreach($e2 as $node2)
					{
						$dates[$node2->parentNode->getElementsByTagName('A09')->item(0)->nodeValue] = $node2->nodeValue;
					}
			

					$pageDom = new DomDocument();
					$pageDom->loadXML($ilr);

					$e = $pageDom->getElementsByTagName('A28');
					$count = 0;
					foreach($e as $node)
					{
						$a09 = $node->parentNode->getElementsByTagName('A09')->item(0)->nodeValue;
						$node->nodeValue = $dates[$a09];
					}
					
					
					
					$ilr = $pageDom->saveXML();
					
					$ilr=substr($ilr,21);
					$ilr = str_replace("'", "&apos;" , $ilr);
					//throw new Exception($count);
					$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = 'W04' and l03 = '$l03'";
					$st2 = $link->query($sql2);			
					if(!$st2)
						throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
				}				
			}
		}
	}
}	
		
		
/*		
		$edrs = '';
		$handle = fopen("tmuk.csv","r");
		$st = fgets($handle);
		
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);
			
			$member = trim($arr[0]);	
			
			if($member!='')
			{
				$found = DAO::getSingleValue($link, "select count(*) from users where CAST(enrollment_no AS SIGNED) = $member ");
				
				if(!$found)
					$edrs .= ($member . ",");
			
	//		try
	//		{
	//			$dob = Date::toMySQL($dob);
	//		}
	//		catch(Exception $e)
	//		{
	//			throw new Exception($dob);
	//		}
			
			//$mn = $arr[0];
			//$mn = str_pad($mn, 5, "0", STR_PAD_LEFT);  
			
			//$st = $link->query("update users set enrollment_no = '$mn' where dob = '$dob'");

			//throw new Exception("update users set enrollment_no = $mn where dob = $dob");
			}			
		}
		
		throw new Exception($edrs);
	}
}		
		
/*		
		$sql = "SELECT * FROM ilr left join tr on tr.id = ilr.tr_id";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$firstnames = $row['firstnames'];
				$surname = $row['surname'];
				$start_date = new Date($row['start_date']);
				$target_date = new Date($row['target_date']);
				$L03 = $row['l03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
				
		//		throw new Exception($firstnames . $surname . $start_date->formatShort() . $target_date->formatShort());

				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);

				$e = $pageDom->getElementsByTagName('L09');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $surname;
				}
		
				$e = $pageDom->getElementsByTagName('L10');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $firstnames;
				}
				
				$e = $pageDom->getElementsByTagName('A27');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $start_date->formatShort();
				}
				
				$e = $pageDom->getElementsByTagName('A28');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $target_date->formatShort();
				}
				
				
				$ilr = $pageDom->saveXML();
				
				$ilr=substr($ilr,21);
				$ilr = str_replace("'", "&apos;" , $ilr);
				//throw new Exception($count);
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
				
			}
		}
	}
}	
		
		
		
*/		
		
/*		
		$edrs = '';
		$handle = fopen("tmuk.csv","r");
		//$st = fgets($handle);
		
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);

			$l03 = trim($arr[0]);
			$a09 = trim($arr[1]);
			$start_date = $arr[2];
			$end_date = $arr[3];			
			
			try
			{
				$start_date = Date::toMySQL(trim($start_date));
				$end_date = Date::toMySQL(trim($end_date));
			}
			catch(Exception $e)
			{
				throw new Exception($e . $end_date);
			}
			
			$st = $link->query("update student_qualifications INNER JOIN tr on tr.id = student_qualifications.tr_id set student_qualifications.start_date = '$start_date', student_qualifications.end_date = '$end_date' where tr.l03 = '$l03' and REPLACE(student_qualifications.id,'/','') = '$a09'");
			if(!$st)
				throw new Exception(implode($link->errorInfo()));
			
		}
	}
}		
		
*/		
/*		
		$sql = "SELECT * FROM ilr left join tr on tr.id = ilr.tr_id";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$ilr = $row['ilr'];
				$tr_id = $row['tr_id'];
				$firstnames = $row['firstnames'];
				$surname = $row['surname'];
				$start_date = new Date($row['start_date']);
				$target_date = new Date($row['target_date']);
				$L03 = $row['l03'];
				$contract_id = $row['contract_id'];
				$submission = $row['submission'];
				
		//		throw new Exception($firstnames . $surname . $start_date->formatShort() . $target_date->formatShort());

				$pageDom = new DomDocument();
				$pageDom->loadXML($ilr);

				$e = $pageDom->getElementsByTagName('L09');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $surname;
				}
		
				$e = $pageDom->getElementsByTagName('L10');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $firstnames;
				}
				
				$e = $pageDom->getElementsByTagName('A27');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $start_date->formatShort();
				}
				
				$e = $pageDom->getElementsByTagName('A28');
				$count = 0;
				foreach($e as $node)
				{
					$node->nodeValue = $target_date->formatShort();
				}
				
				
				$ilr = $pageDom->saveXML();
				
				$ilr=substr($ilr,21);
				$ilr = str_replace("'", "&apos;" , $ilr);
				//throw new Exception($count);
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());	
				
			}
		}
	}
}	
		
*/	
/*		
		
$dates = 0;
		
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id ORDER BY contract_id DESC, submission DESC LIMIT 1");
				
				if($ilr!='')
				{
					$ilrxml = new SimpleXMLElement($ilr);
					foreach ($ilrxml->main as $item) 
					{
						$a14 = ($item->A14=='')?'null':$item->A14;
						$a18 = ($item->A18=='')?'null':$item->A18;
						$a51a = ($item->A51a=='')?'null':$item->A51a;
						$a16 = ($item->A16=='')?'null':$item->A16;
						$id = $item->A09;
						$a34 = ($item->A34=='')?'null':$item->A34;
						$a35 = ($item->A35=='')?'null':$item->A35;
						
						
						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy')
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = "00-00-0000";

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = "00-00-0000";
							
				//		$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16 , a34 = $a34, a35 = $a35 where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";

						$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
							
					//	if($tr_id == 112)
					//	throw new Exception($sql);
						
						$st2 = $link->query($sql2);

						//$st3 = $link->query("update test set id = '$tr_id'");
					}
					foreach ($ilrxml->subaim as $item) 
					{
						$a14 = ($item->A14!='')?$item->A14:"00";
						$a18 = $item->A18;
						$a51a = ($item->A51a!='')?$item->A51a:"100";
						$a16 = ($item->A16!='')?$item->A16:"00";
						$id = $item->A09;
						$a34 = $item->A34;
						$a35 = $item->A35;

						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);

						
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy' && $item->A31!='' )
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = '00-00-0000';

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy' && $item->A40!='')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = '00-00-0000';
												
//						$sql3 = "update student_qualifications set a14 = '$a14', a18 = '$a18', a51a = '$a51a', a16 = '$a16', a34 = '$a34', a35 = '$a35' where REPLACE(id,'/','') = '$id'";
						$sql3 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
						if(!$st3 = $link->query($sql3))
						{	
							//throw new Exception($a40);
							throw new Exception($item->A40 . $sql3."]".implode($link->errorInfo()));
						}
					} 
				}
			}
			
			throw new Exception($dates);
		}
	}
}
*/		
		
/*		$sql = "SELECT * FROM student_qualifications where id = '500/2154/0'";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$id = $row['id'];
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				$internaltitle = $row['internaltitle'];

				$xml = $row['evidences'];

				$pageDom = new DomDocument();
				$pageDom->loadXML($xml);
				$e = $pageDom->getElementsByTagName('unit');

				$count = 0;
				foreach($e as $node)
				{
					$o = $node->getAttribute('owner_reference');
					if($o == "TMKaizen1" || $o == "TMQCMem" || $o == "TMKaiRptg" || $o == "TMSafety")
					{	
						$node->setAttribute("proportion", "10");
						$count++;
					}
				}
		
				$ilr = $pageDom->saveXML();
				
				$ilr=substr($ilr,21);
				throw new Exception($count);
				$sql2 = "update ilr set evidences = '$ilr' where id='$id' and tr_id = '$tr_id' and framework_id = '$framework_id' and internaltitle = '$internaltitle'";
				//$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception("Error");	
				
			}
		}
	}
}	

*/		
		
/*		$dates = 0;
		
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id ORDER BY contract_id DESC, submission DESC LIMIT 1");
				
				if($ilr!='')
				{
					$ilrxml = new SimpleXMLElement($ilr);
					foreach ($ilrxml->main as $item) 
					{
						$a14 = ($item->A14=='')?'null':$item->A14;
						$a18 = ($item->A18=='')?'null':$item->A18;
						$a51a = ($item->A51a=='')?'null':$item->A51a;
						$a16 = ($item->A16=='')?'null':$item->A16;
						$id = $item->A09;
						$a34 = ($item->A34=='')?'null':$item->A34;
						$a35 = ($item->A35=='')?'null':$item->A35;
						
						
						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy')
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = "00-00-0000";

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = "00-00-0000";
							
				//		$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16 , a34 = $a34, a35 = $a35 where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";

						$sql2 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
							
					//	if($tr_id == 112)
					//	throw new Exception($sql);
						
						$st2 = $link->query($sql2);

						//$st3 = $link->query("update test set id = '$tr_id'");
					}
					foreach ($ilrxml->subaim as $item) 
					{
						$a14 = $item->A14;
						$a18 = $item->A18;
						$a51a = $item->A51a;
						$a16 = $item->A16;
						$id = $item->A09;
						$a34 = $item->A34;
						$a35 = $item->A35;

						$a27 = Date::toMySQL($item->A27);
						$a28 = Date::toMySQL($item->A28);

						
						if($item->A31!='00000000' && $item->A31!='dd/mm/yyyy')
						{
							$a31 = Date::toMySQL($item->A31);
							$dates++;
						}
						else
							$a31 = '00-00-0000';

						if($item->A40!='00000000' && $item->A40!='dd/mm/yyyy')
							$a40 = Date::toMySQL($item->A40);
						else
							$a40 = '00-00-0000';
												
//						$sql3 = "update student_qualifications set a14 = '$a14', a18 = '$a18', a51a = '$a51a', a16 = '$a16', a34 = '$a34', a35 = '$a35' where REPLACE(id,'/','') = '$id'";
						$sql3 = "update student_qualifications set achievement_date = '$a40', actual_end_date = '$a31', end_date = '$a28', start_date = '$a27', a14 = $a14, a18 = $a18, a51a = $a51a, a16 = $a16  where REPLACE(id,'/','') = '$id' and tr_id = '$tr_id'";
						if(!$st3 = $link->query($sql3))
						{	
							//throw new Exception($a40);
							throw new Exception($tr_id."]".implode($link->errorInfo()));
						}
					} 
				}
			}
			
			throw new Exception($dates);
		}
	}
}
		
*/		
/*		
		$edrs = '';
		$handle = fopen("ml.csv","r");
		$st = fgets($handle);
		
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);

			$dob = trim($arr[3]);	
			try
			{
				$dob = Date::toMySQL($dob);
			}
			catch(Exception $e)
			{
				throw new Exception($dob);
			}
			
			$mn = $arr[0];
			$mn = str_pad($mn, 5, "0", STR_PAD_LEFT);  
			
			$st = $link->query("update users set enrollment_no = '$mn' where dob = '$dob'");

			//throw new Exception("update users set enrollment_no = $mn where dob = $dob");
			
		}
	}
}		
*/		
		
/*		
		$trs = 0;
		$sql = "SELECT * FROM ilr where contract_id = 11";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$xml = $row['ilr'];
				$submission = $row['submission'];
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];

				$trs += 1;
				
				$pageDom = new DomDocument();
				$pageDom->loadXML($xml);
				$e = $pageDom->getElementsByTagName('A51a');
				$a = 1;
				$evidences = Array();
				$data='';
				foreach($e as $node)
				{
					$node->nodeValue = "100";
				}
		
				$ilr = $pageDom->saveXML();
				
				$ilr=substr($ilr,21);
				
				$sql2 = "update ilr set ilr = '$ilr' where submission='$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception("Error");	
			}
			
			throw new Exception($trs);
		}		
	}
}		
		
*/		
		
		
/*		
 		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = (select max(submission) from ilr where tr_id = $tr_id)");

				$vo = Ilr2008::loadFromXML($ilr);
				
				$disability = $vo->learnerinformation->L15;
				$learning_difficulty = $vo->learnerinformation->L16;
				
				$sql = "update tr set disability = '$disability', learning_difficulty = '$learning_difficulty' where id = $tr_id";
				$st2 = $link->query($sql);
			}
		}
	}
}
*/	
	
	
/*		$sql = "SELECT * FROM ilr";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$submission = $row['submission'];
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];
				$l03 = $row['L03'];
				$ilr = $row['ilr'];
				
				$ilrxml = new SimpleXMLElement($ilr);
				foreach ($ilrxml->main as $item) 
				{
				
					$a01 = ($item->A01=='')?0:$item->A01;
					$a02 = ($item->A02=='')?0:$item->A02;
					$a03 = $item->A03;
					$a04 = ($item->A04=='')?0:$item->A04;
					$a05 = ($item->A05=='')?0:$item->A05;
					$a06 = ($item->A06=='')?0:$item->A06;
					$a07 = ($item->A07=='')?0:$item->A07;
					$a08 = ($item->A08=='')?0:$item->A08;
					$a09 = $item->A09;
					$a10 = ($item->A10=='')?0:$item->A10;
					$a11a = ($item->A11a=='')?0:$item->A11a;
					$a11b = ($item->A11b=='')?0:$item->A11b;
					$a12 = 0;
					$a13 = ($item->A13=='')?0:$item->A13;
					$a14 = ($item->A14=='')?0:$item->A14;
					$a15 = ($item->A15=='')?0:$item->A15;
					$a16 = ($item->A16=='')?0:$item->A16;
					$a17 = ($item->A17=='')?0:$item->A17;
					$a18 = ($item->A18=='')?0:$item->A18;
					$a19 = ($item->A19=='')?0:$item->A19;
					$a20 = ($item->A20=='')?0:$item->A20;
					$a21 = ($item->A21=='')?0:$item->A21;
					$a22 = $item->A22;
					$a23 = $item->A23;
					$a24 = ($item->A24=='')?0:$item->A24;
					$a26 = ($item->A26=='')?0:$item->A26;
					$a27 = $item->A27;
					$a28 = $item->A28;
					$a31 = ($item->A31=='')?'null':"'" . $item->A31 . "'";
					$a32 = ($item->A32=='')?0:$item->A32;
					$a33 = $item->A33;
					$a34 = ($item->A34=='')?0:$item->A34;
					$a35 = ($item->A35=='')?0:$item->A35;
					$a36 = $item->A36;
					$a37 = ($item->A37=='')?0:$item->A37;
					$a38 = ($item->A38=='')?0:$item->A38;
					$a39 = ($item->A39=='')?0:$item->A39;
					$a40 = ($item->A40=='')?'null':"'" . $item->A40 . "'";
					$a43 = ($item->A43=='')?0:$item->A43;
					$a44 = $item->A44;
					$a45 = $item->A45;
					$a46a = ($item->A46a=='')?0:$item->A46a;
					$a46b = ($item->A46b=='')?0:$item->A46b;
					$a47a = ($item->A47a=='')?0:$item->A47a;
					$a47b = ($item->A47b=='')?0:$item->A47b;
					$a48a = $item->A48a;
					$a48b = $item->A48b;
					$a49 = $item->A49;
					$a50 = ($item->A50=='')?0:$item->A50;
					$a51a = ($item->A51a=='')?0:$item->A51a;
					$a52 = ($item->A52=='')?0:$item->A52;
					$a53 = ($item->A53=='')?0:$item->A53;
					$a54 = $item->A54;
					$a55 = ($item->A55=='')?0:$item->A55;
					$a56 = ($item->A56=='')?0:$item->A56;
					$a57 = ($item->A57=='')?0:$item->A57;
					$a58 = ($item->A58=='')?0:$item->A58;
					$a59 = ($item->A59=='')?0:$item->A59;
					$a60 = ($item->A60=='')?0:$item->A60;
					$a61 = $item->A61;
					$a62 = ($item->A62=='')?0:$item->A62;
					$a63 = ($item->A63=='')?0:$item->A63;
					$a64 = ($item->A64=='')?0:$item->A64;
					$a65 = ($item->A65=='')?0:$item->A65;
					$a66 = ($item->A66=='')?0:$item->A66;
					$a67 = ($item->A67=='')?0:$item->A67;
					$a68 = ($item->A68=='')?0:$item->A68;
					
			$sql2 = <<<HEREDOC
insert into learning_aims
values ($contract_id, $tr_id, $a01, $a02, '$a03', $a04, $a05, 0, $a07, $a08, '$a09', $a10, $a11a, $a11b, 
$a12, $a13, $a14, $a15, $a16, $a17, $a18, $a19, $a20, $a21, '$a22', '$a23', $a24, $a26, '$a27', '$a28', 
$a31, $a32, '$a33', $a34, $a35, '$a36', $a37, $a38, $a39, $a40, $a43, '$a44', '$a45', $a46a, $a46b, 
$a47a, $a47b, '$a48a', '$a48b', '$a49', '$a50', '$a51a', $a52, $a53, '$a54', $a55, $a56, $a57, $a58, $a59, 
$a60, '$a61', $a62, $a63, $a64, $a65, $a66, $a67, $a68);
HEREDOC;
	
					$st2 = $link->query($sql2);
					if(!$st2)
						//throw new Exception($sql2);
						throw new Exception(implode($link->errorInfo()));
					
				}
			}
		}
	}
}
	
	
	
	
/*
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = (select max(submission) from ilr where tr_id = $tr_id)");

				$vo = Ilr2008::loadFromXML($ilr);
				
				$status = $vo->aims[0]->A34;

				$sql = "update tr set status_code = '$status' where id = $tr_id";
				$st2 = $link->query($sql);
			}
		}
*/	
	
	
	
	/*	$sql = "SELECT * FROM ilr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$xml = $row['ilr'];
				$pos = strpos($xml,"<main>");
				if($pos === false)
				{
					$ilrs += 1;
					$xml = str_replace("<subaims>Array</subaims>","<subaims>0</subaims>", $xml);
					$xml = str_replace("<subaim>","<main>", $xml);
					$xml = str_replace("</subaim>","</main>", $xml);
					
					$submission = $row['submission'];
					$tr_id = $row['tr_id'];
					$contract_id = $row['contract_id'];
					
					$sql = "update ilr set ilr = '$xml' where submission='$submission' and tr_id = $tr_id and contract_id = $contract_id";
					$st2 = $link->query($sql);
				
				}
			}
		}
	*/
?>