<?php
class add_training implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';

		if (!$tr_id) {
			throw new Exception("Missing or empty argument: tr_id");
		}
		if (!$course_id) {
			throw new Exception("Missing or empty argument: course_id");
		}

		$link->beginTransaction();
		try
		{
			$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
			if (!$tr) {
				throw new Exception("Could not find training record with id #" . $tr_id);
			}

			$sd = Date::toMySQL($tr->start_date);
			if (!$sd) {
				throw new Exception("This training record (#$tr_id) does not have a training start-date. Please correct this before adding new qualifications.");
			}
			$ed = Date::toMySQL($tr->target_date);
			if (!$ed) {
				throw new Exception("This training record (#$tr_id) does not have a training planned-end-date. Please correct this before adding new qualifications.");
			}

			$course = Course::loadFromDatabase($link, $course_id);
			if (!$course) {
				throw new Exception("Could not find course with id #" . $course_id);
			}

			$que = "select id from locations where organisations_id='$course->organisations_id'";
			$location_id = trim(DAO::getSingleValue($link, $que));
			$provider = Location::loadFromDatabase($link, $location_id);
			if(empty($provider)) {
				throw new Exception("Cannot find location with id #$location_id for organisation #{$course->organisations_id}");
			}

            DAO::execute($link, "update tr set provider_id = '$course->organisations_id', provider_location_id = '$location_id' where id = $tr->id");

/*			DAO::execute($link, "update tr set provider_id = '$course->organisations_id', provider_location_id = '$location_id',
			provider_saon_start_number = '$provider->saon_start_number',
			provider_saon_start_suffix = '$provider->saon_start_suffix',
			provider_saon_end_number = '$provider->saon_end_number',
			provider_saon_end_suffix = '$provider->saon_end_suffix',
			provider_saon_description = '$provider->saon_description',
			provider_paon_start_number = '$provider->paon_start_number',
			provider_paon_start_suffix = '$provider->paon_start_suffix',
			provider_paon_end_number = '$provider->paon_end_number',
			provider_paon_end_suffix = '$provider->paon_end_suffix',
			provider_paon_description = '$provider->paon_description',
			provider_street_description = '$provider->street_description',
			provider_locality = '$provider->locality',
			provider_town = '$provider->town',
			provider_county = '$provider->county',
			provider_postcode = '$provider->postcode',
			provider_telephone = '$provider->telephone'
			where id = $tr->id");*/
			$data = array();
			$data['id'] = $tr->id;
			$data['provider_address_line_1'] = $provider->address_line_1;
			$data['provider_address_line_2'] = $provider->address_line_2;
			$data['provider_address_line_3'] = $provider->address_line_3;
			$data['provider_address_line_4'] = $provider->address_line_4;
			$data['provider_postcode'] = $provider->postcode;
			DAO::saveObjectToTable($link, 'tr', $data);

			// Enrol on a course and put in a  group
			$framework_id = '0';
			$qualification_id = '0';


		
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
	courses_tr (course_id, tr_id, qualification_id, framework_id)
values($course_id, $tr_id, '$qualification_id', $framework_id);
HEREDOC;
			DAO::execute($link, $query);


		
		
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
select title, id, '$tr_id', framework_code, comments, duration_in_months
from frameworks
	where id = '$fid';
HEREDOC;
					DAO::execute($link, $query);

					// importing qualification from framework
					$query = <<<HEREDOC
insert into
	student_qualifications(id,
framework_id,
tr_id,
internaltitle,
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
unitsCompleted,
unitsNotStarted,
unitsBehind,
unitsOnTrack,
unitsUnderAssessment,
unitsPercentage,
proportion,
aptitude,
attitude,
comments,
modified,
username,
trading_name,
auto_id,
start_date,
end_date,
actual_end_date,
achievement_date,
units_required,
awarding_body_reg,
awarding_body_date,
awarding_body_batch,
a14,
a18,
a51a,
a16,
certificate_applied,
certificate_received,
smart_assessor_id
)
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
NULL,
'',
'',
''
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and 
course_qualifications_dates.framework_id = framework_qualifications.framework_id and 
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id';
HEREDOC;
					DAO::execute($link, $query);
		
					// Creating milestones
					$sql = "SELECT *, timestampdiff(MONTH, start_date, end_date) as months FROM student_qualifications where tr_id = $tr_id";
					$st = $link->query($sql);
					$unit=0;
					while($row = $st->fetch())
					{
						$xml = mb_convert_encoding($row['evidences'],'UTF-8');


						//$pageDom = new DomDocument();
						//$pageDom->loadXML($xml);
						$pageDom = XML::loadXmlDom($xml);

						$evidences = $pageDom->getElementsByTagName('unit');
						foreach($evidences as $evidence)
						{
							$unit_id = $evidence->getAttribute('owner_reference');
							$tr_id = $row['tr_id'];
							$framework_id = $row['framework_id'];
							$qualification_id = $row['id'];
							$internaltitle = $row['internaltitle'];
							$internaltitle = addslashes((string)$row['internaltitle']);

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

							$unit_id = str_replace(" ","",$unit_id);

							DAO::execute($link, "delete from student_milestones where tr_id = '$tr_id' and qualification_id not in (select id from student_qualifications where tr_id = '$tr_id')");
							DAO::execute($link, "insert into student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8, month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen) values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
						}
					}
				}
			}

			$link->commit();
			http_redirect("do.php?_action=read_training_record&id=$tr_id");
		}
		catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}
	}


	public static function xmlspecialchars($text) 
	{
   		return str_replace('&#039;', '&apos;', htmlspecialchars((string)$text, ENT_QUOTES));
	}
}
?>