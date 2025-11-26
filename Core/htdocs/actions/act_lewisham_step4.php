<?php
class lewisham_step4 implements IAction
{
	public function execute(PDO $link)
	{
//		$link->query("delete from aim where A10 Not in (45,46)");
//		$link->query("delete from learner where L03 not in (select A03 from aim)");

		// Check TtG Training Records and Create if dont exists
		$sql = "SELECT * FROM aim LEFT JOIN learner ON learner.L03 = aim.A03 WHERE A15 = 99 AND A26 = 0 AND A09 != 'ZSPE0001' AND A09!='ZSPE1022';";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$l03 = $row['A03'];
				$a09 = $row['A09'];
				$sd = $row['A27'];
				$ed = $row['A28'];
				$count = DAO::getSingleValue($link, "SELECT tr.id FROM tr LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id LEFT JOIN courses ON courses.id = courses_tr.course_id  LEFT JOIN frameworks ON frameworks.id = courses.framework_id LEFT JOIN framework_qualifications ON framework_qualifications.framework_id = frameworks.id WHERE frameworks.framework_type = 99 AND REPLACE(framework_qualifications.id,'/','')='$a09' AND tr.l03 = '$l03'");
				if($count=='')
				{
					$link->beginTransaction();
					try
					{

					$start_date = $row['A27'];
					$end_date = $row['A28'];
					$username = $row['A03'];
					$user = User::loadFromDatabase($link, $username);
					$tr = new TrainingRecord();
					$tr->populate($user, true);
					$tr->contract_id = DAO::getSingleValue($link, "select id from contracts where contract_year = 2011 limit 0,1");
					$tr->start_date = $start_date;
					$tr->target_date = $end_date;
					$tr->status_code = 1;
					$a26 = $row['A26'];
					$a15 = $row['A15'];
					$course_id = DAO::getSingleValue($link, "SELECT courses.id FROM courses LEFT JOIN frameworks ON frameworks.id = courses.framework_id LEFT JOIN framework_qualifications ON framework_qualifications.framework_id = frameworks.id WHERE frameworks.framework_type=99 AND REPLACE(framework_qualifications.id,'/','') = '$a09'");
					$course = new Course($link, $course_id);
					$provider = new Organisation($link, $course->provider_id);
					$tr->provider_id = $course->organisations_id;
					$provider_location = new Location($link, DAO::getSingleValue($link, "select id from locations where organisations_id = '$course->organisations_id'"));	
					$tr->provider_location_id = $provider->id;
/*					$tr->provider_saon_start_number = $provider_location->saon_start_number;
					$tr->provider_saon_start_suffix = $provider_location->saon_start_suffix;
					$tr->provider_saon_end_number = $provider_location->saon_end_number;
					$tr->provider_saon_end_suffix = $provider_location->saon_end_suffix;
					$tr->provider_saon_description = $provider_location->saon_description;
					$tr->provider_paon_start_number = $provider_location->paon_start_number;
					$tr->provider_paon_start_suffix = $provider_location->paon_start_suffix;
					$tr->provider_paon_end_number = $provider_location->paon_end_number;
					$tr->provider_paon_end_suffix = $provider_location->paon_end_suffix;
					$tr->provider_paon_description = $provider_location->paon_description;
					$tr->provider_street_description = $provider_location->street_description;
					$tr->provider_locality = $provider_location->locality;
					$tr->provider_town = $provider_location->town;
					$tr->provider_county = $provider_location->county;*/
					$tr->provider_address_line_1 = $provider_location->address_line_1;
					$tr->provider_address_line_2 = $provider_location->address_line_2;
					$tr->provider_address_line_3 = $provider_location->address_line_3;
					$tr->provider_address_line_4 = $provider_location->address_line_4;
					$tr->provider_postcode = $provider_location->postcode; 
					$tr->provider_telephone = $provider_location->telephone;
					$tr->ethnicity = $user->ethnicity;
					$tr->work_experience = 0;
					$tr->l03 = $row['A03'];
					$tr->id = NULL;
					$tr->save($link); 	

					$ilr = "<ilr><learner>";
					$ilr .= "<L01>" . $row['L01'] . "</L01>";
					$ilr .= "<L02>" . $row['L02'] . "</L02>";
					$ilr .= "<L03>" . $row['L03'] . "</L03>";
					$ilr .= "<L04>" . $row['L04'] . "</L04>";
					$ilr .= "<L05>" . $row['L05'] . "</L05>";
					$ilr .= "<L07>" . $row['L07'] . "</L07>";
					$ilr .= "<L08>" . $row['L08'] . "</L08>";
					$ilr .= "<L09>" . $row['L09'] . "</L09>";
					$ilr .= "<L10>" . $row['L10'] . "</L10>";
					$ilr .= "<L11>" . Date::toShort($row['L11']) . "</L11>";
					$ilr .= "<L12>" . $row['L12'] . "</L12>";
					$ilr .= "<L13>" . $row['L13'] . "</L13>";
					$ilr .= "<L14>" . $row['L14'] . "</L14>";
					$ilr .= "<L15>" . $row['L15'] . "</L15>";
					$ilr .= "<L16>" . $row['L16'] . "</L16>";
					$ilr .= "<L17>" . addslashes((string)$row['L17']) . "</L17>";
					$ilr .= "<L18>" . addslashes((string)$row['L18']) . "</L18>";
					$ilr .= "<L19>" . addslashes((string)$row['L19']) . "</L19>";
					$ilr .= "<L20>" . addslashes((string)$row['L20']) . "</L20>";
					$ilr .= "<L21>" . addslashes((string)$row['L21']) . "</L21>";
					$ilr .= "<L22>" . addslashes((string)$row['L22']) . "</L22>";
					$ilr .= "<L23>" . $row['L23'] . "</L23>";
					$ilr .= "<L24>" . $row['L24'] . "</L24>";
					$ilr .= "<L25>" . $row['L25'] . "</L25>";
					$ilr .= "<L26>" . $row['L26'] . "</L26>";
					$ilr .= "<L27>" . $row['L27'] . "</L27>";
					$ilr .= "<L28a>" . $row['L28a'] . "</L28a>";
					$ilr .= "<L28b>" . $row['L28b'] . "</L28b>";
					$ilr .= "<L29>" . $row['L29'] . "</L29>";
					$ilr .= "<L31>" . $row['L31'] . "</L31>";
					$ilr .= "<L32>" . $row['L32'] . "</L32>";
					$ilr .= "<L33>" . $row['L33'] . "</L33>";
					$ilr .= "<L34a>" . $row['L34a'] . "</L34a>";
					$ilr .= "<L34b>" . $row['L34b'] . "</L34b>";
					$ilr .= "<L34c>" . $row['L34c'] . "</L34c>";
					$ilr .= "<L34d>" . $row['L34d'] . "</L34d>";
					$ilr .= "<L35>" . $row['L35'] . "</L35>";
					$ilr .= "<L36>" . $row['L36'] . "</L36>";
					$ilr .= "<L37>" . $row['L37'] . "</L37>";
					$ilr .= "<L39>" . $row['L39'] . "</L39>";
					$ilr .= "<L40a>" . $row['L40a'] . "</L40a>";
					$ilr .= "<L40b>" . $row['L40b'] . "</L40b>";
					$ilr .= "<L41a>" . $row['L41a'] . "</L41a>";
					$ilr .= "<L41b>" . $row['L41b'] . "</L41b>";
					$ilr .= "<L42a>" . $row['L42a'] . "</L42a>";
					$ilr .= "<L42b>" . $row['L42b'] . "</L42b>";
					$ilr .= "<L44>" . $row['L44'] . "</L44>";
					$ilr .= "<L45>" . $row['L45'] . "</L45>";
					$ilr .= "<L46>" . $row['L46'] . "</L46>";
					$ilr .= "<L47>" . $row['L47'] . "</L47>";
					$ilr .= "<L48>" . $row['L48'] . "</L48>";
					$ilr .= "<L49a>" . $row['L49a'] . "</L49a>";
					$ilr .= "<L49b>" . $row['L49b'] . "</L49b>";
					$ilr .= "<L49c>" . $row['L49c'] . "</L49c>";
					$ilr .= "<L49d>" . $row['L49d'] . "</L49d>";
					$ilr .= "<L51>" . $row['L51'] . "</L51>";
					$ilr .= "<L52>" . $row['L52'] . "</L52>";
					$ilr .= "</learner><subaims>0</subaims><programmeaim><A01></A01><A02>0</A02><A03></A03><A04></A04><A05>1</A05><A07></A07><A08></A08><A09></A09><A10></A10><A11a></A11a><A11b></A11b><A13></A13><A14></A14><A15></A15><A16></A16><A17></A17><A18></A18><A19></A19><A20></A20><A21></A21><A22></A22><A23></A23><A26></A26><A27></A27><A28></A28><A31/><A32></A32><A34></A34><A35></A35><A36>      </A36><A40/><A44></A44><A45></A45><A46a></A46a><A46b></A46b><A47a></A47a><A47b></A47b><A48a></A48a><A48b></A48b><A49></A49><A50></A50><A51a></A51a><A52></A52><A53></A53><A54></A54><A55></A55><A56></A56><A57></A57><A58></A58><A59></A59><A60></A60><A61></A61><A62></A62><A63></A63><A64></A64><A65></A65><A66></A66><A67></A67><A68></A68><A69></A69><A70></A70><A71></A71><A72a></A72a><A72b></A72b></programmeaim><main>";
					$ilr .= $this->createAim($row);
					$ilr .= "</main></ilr>";
					$a03 = $row['A03'];
					$a09 = $row['A09'];
					//DAO::execute($link, "insert into ilr values('$provider->upin','$a03', '$a09', '$ilr', 'W03', 'ER', '$tr->id', '1', '1', '1', '1', $tr->contract_id)");

					// enroling on a course
					$course_id = DAO::getSingleValue($link, "SELECT courses.id FROM courses LEFT JOIN frameworks ON frameworks.id = courses.framework_id LEFT JOIN framework_qualifications ON framework_qualifications.framework_id = frameworks.id WHERE frameworks.framework_type=99 AND REPLACE(framework_qualifications.id,'/','') = '$a09'");
					$framework_id = DAO::getSingleValue($link, "SELECT frameworks.id FROM courses LEFT JOIN frameworks ON frameworks.id = courses.framework_id LEFT JOIN framework_qualifications ON framework_qualifications.framework_id = frameworks.id WHERE frameworks.framework_type=99 AND REPLACE(framework_qualifications.id,'/','') = '$a09'");
					$tr_id = $tr->id;
					if($course_id=='')
						pre($l03);
					$query_courses_tr = "insert into 	courses_tr (course_id, tr_id, qualification_id, framework_id) values($course_id, $tr_id, '0', $framework_id); ";
					DAO::execute($link, $query_courses_tr);

					
					// Importing framework
					$query_student_frameworks = " insert into 	student_frameworks select title, id, '$tr_id', framework_code, comments, duration_in_months from frameworks where id = '$framework_id';";
					DAO::execute($link, $query_student_frameworks);
					
					
// importing qualification from framework		
$query_student_qualifications = <<<HEREDOC
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
'100',
NULL,
'',
''
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and 
course_qualifications_dates.framework_id = framework_qualifications.framework_id and 
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id';
HEREDOC;
		DAO::execute($link, $query_student_qualifications);
					
		
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
	function createAim($row2)
	{
		$ilr = "<A01>" . $row2['A01'] . "</A01>";
		$ilr .= "<A02>" . $row2['A02'] . "</A02>";	//	Contract/ Allocation Type
		$ilr .= "<A03>" . $row2['A03'] . "</A03>";	//	Learner reference number
		$ilr .= "<A04>" . $row2['A04'] . "</A04>";	//	Data set identifier code
		$ilr .= "<A05>" . $row2['A05'] . "</A05>";	//	Learning aim data set sequence
		$ilr .= "<A07>" . $row2['A07'] . "</A07>";	//	HE data sets
		$ilr .= "<A08>" . $row2['A08'] . "</A08>";	//	Data set format
		$ilr .= "<A09>" . $row2['A09'] . "</A09>";	//	Learning aim reference
		$ilr .= "<A10>" . $row2['A10'] . "</A10>";	//	LSC funding stream
		$ilr .= "<A11a>" . $row2['A11a'] . "</A11a>";	//	Source of funding
		$ilr .= "<A11b>" . $row2['A11b'] . "</A11b>";	//	Source of funding
		$ilr .= "<A13>" . $row2['A13'] . "</A13>";	//	Tuition fee received for year
		$ilr .= "<A14>" . $row2['A14'] . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
		$ilr .= "<A15>" . $row2['A15'] . "</A15>";	//	Programme type
		$ilr .= "<A16>" . $row2['A16'] . "</A16>";	//	Programme entry route
		$ilr .= "<A17>" . $row2['A17'] . "</A17>";	//	Delivery mode
		$ilr .= "<A18>" . $row2['A18'] . "</A18>";	//	Main delivery method
		$ilr .= "<A19>" . $row2['A19'] . "</A19>";	//	Employer role
		$ilr .= "<A20>" . $row2['A20'] . "</A20>";	//	Resit
		$ilr .= "<A21>" . $row2['A21'] . "</A21>";	//	Franchised out and partnership arrangement
		$ilr .= "<A22>" . $row2['A22'] . "</A22>";	//	Franchised out and partnership delivery provider number
		$ilr .= "<A23>" . $row2['A23'] . "</A23>";	//	Delivery location postcode
		$ilr .= "<A26>" . $row2['A26'] . "</A26>";	//	Sector framework of learning 
		$ilr .= "<A27>" . Date::toShort($row2['A27']) . "</A27>"; // Learning start date
		$ilr .= "<A28>" . Date::toShort($row2['A28']) . "</A28>"; // Learning planned end date
		$ilr .= "<A31>" . Date::toShort($row2['A31']) . "</A31>"; // Learning actual end date
		$ilr .= "<A32>" . $row2['A32'] . "</A32>";	//	Guided learning hours
		$ilr .= "<A34>" . $row2['A34'] . "</A34>";	//	Completion status
		$ilr .= "<A35>" . $row2['A35'] . "</A35>";	//	Learning outcome
		$ilr .= "<A36>" . $row2['A36'] . "</A36>";	//	Learning outcome grade
		$ilr .= "<A40>" . Date::toShort($row2['A40']) . "</A40>"; // Achivement date
		$ilr .= "<A44>" . $row2['A44'] . "</A44>";	//	Employer identifier
		$ilr .= "<A45>" . $row2['A45'] . "</A45>";	//	Workplace location postcode
		$ilr .= "<A46a>" . $row2['A46a'] . "</A46a>";	//	National learning aim monitoring
		$ilr .= "<A46b>" . $row2['A46b'] . "</A46b>";	//	National learning aim monitoring
		$ilr .= "<A47a>" . $row2['A47a'] . "</A47a>";	//	Local learning aim monitoring
		$ilr .= "<A47b>" . $row2['A47b'] . "</A47b>";	//	Local learning aim monitoring
		$ilr .= "<A48a>" . $row2['A48a'] . "</A48a>";	//	Provider specified learning aim data
		$ilr .= "<A48b>" . $row2['A48b'] . "</A48b>";	//	Provider specified learning aim data
		$ilr .= "<A49>" . $row2['A49'] . "</A49>";	//	Special projects and pilots
		$ilr .= "<A50>" . $row2['A50'] . "</A50>";	//	Reason learning ended
		$ilr .= "<A51a>" . $row2['A51a'] . "</A51a>";	//	Proportion of funding remaining
		$ilr .= "<A52>" . $row2['A52'] . "</A52>";	//	Distance learning funding
		$ilr .= "<A53>" . $row2['A53'] . "</A53>";	//	Additional learning needs
		$ilr .= "<A54>" . $row2['A54'] . "</A54>";	//	Broker contract number
		$ilr .= "<A55>" . $row2['A55'] . "</A55>";	//	Unique learner number
		$ilr .= "<A56>" . $row2['A56'] . "</A56>";	//	UK Provider reference number
		$ilr .= "<A57>" . $row2['A57'] . "</A57>";	//	Source of tuition fees
		$ilr .= "<A58>" . $row2['A58'] . "</A58>";	//	Source of tuition fees
		$ilr .= "<A59>" . $row2['A59'] . "</A59>";	//	Source of tuition fees
		$ilr .= "<A60>" . $row2['A60'] . "</A60>";	//	Source of tuition fees
		$ilr .= "<A61>" . $row2['A61'] . "</A61>";	//	Source of tuition fees
		$ilr .= "<A62>" . $row2['A62'] . "</A62>";	//	Source of tuition fees
		$ilr .= "<A63>" . $row2['A63'] . "</A63>";	//	Source of tuition fees
		$ilr .= "<A64>" . $row2['A64'] . "</A64>";	//	Source of tuition fees
		$ilr .= "<A65>" . $row2['A65'] . "</A65>";	//	Source of tuition fees
		$ilr .= "<A66>" . $row2['A66'] . "</A66>";	//	Source of tuition fees
		$ilr .= "<A67>" . $row2['A67'] . "</A67>";	//	Source of tuition fees
		$ilr .= "<A68>" . $row2['A68'] . "</A68>";	//	Source of tuition fees
		$ilr .= "<A69>" . $row2['A69'] . "</A69>";	//	Source of tuition fees
		$ilr .= "<A70>" . $row2['A70'] . "</A70>";	//	Source of tuition fees
		$ilr .= "<A71>" . $row2['A71'] . "</A71>";	//	Source of tuition fees
		$ilr .= "<A72a>" . $row2['A72a'] . "</A72a>";	//	Source of tuition fees
		$ilr .= "<A72b>" . $row2['A72b'] . "</A72b>";	//	Source of tuition fees
		return $ilr;
	}
	
}
?>