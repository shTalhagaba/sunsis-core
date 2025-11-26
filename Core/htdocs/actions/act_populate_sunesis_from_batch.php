<?php
class populate_sunesis_from_batch implements IAction
{
	public function execute(PDO $link)
	{

		$learners;
		$filename = $_FILES['uploadedfile']['tmp_name'];
		$content = file_get_contents($filename);
		$handle = fopen($filename,"r");

		DAO::execute($link, "truncate learner");
		DAO::execute($link, "truncate aim");
		DAO::execute($link, "TRUNCATE tr;");
		DAO::execute($link, "TRUNCATE courses_tr;");
		DAO::execute($link, "DELETE FROM organisations WHERE organisation_type = 2;");
		DAO::execute($link, "DELETE FROM users WHERE TYPE = 5;");
		DAO::execute($link, "DELETE FROM ilr;");
		DAO::execute($link, "TRUNCATE student_qualifications;");
		DAO::execute($link, "TRUNCATE assessor_review;");
		DAO::execute($link, "TRUNCATE group_members;");
		
		$st = fgets($handle);
		$upin = substr($st,0,6);
		$L25 = substr($st,22,3);
		DAO::execute($link, "update contracts set upin = '$upin' where id = 4");
		DAO::execute($link, "update contracts set L25 = '$L25' where id = 4");
		$employers = Array();
		$learners = Array();
		while(!feof($handle))
		{
			$st = fgets($handle);
			$st = str_replace("'"," ",$st);
			$a04 = substr($st,20,2);
			if($a04=='10')
			{
				$l01 = substr($st,0,6);
				$l02 = substr($st,6,2);
				$l03 = substr($st,8,12);
				$l04 = substr($st,20,2);
				$l05 = substr($st,22,2);
				$l07 = substr($st,24,2);
				$l08 = substr($st,26,1);
				$l09 = substr($st,27,20);
				$l10 = substr($st,47,40);
				$l11 = substr($st,87,2) . "/" . substr($st,89,2) . "/" . substr($st,91,4);
				$l11 = Date::toMySQL($l11);
				$l12 = substr($st,95,2);
				$l13 = substr($st,97,1);
				$l14 = substr($st,98,1);
				$l15 = substr($st,99,2);
				$l16 = substr($st,101,2);
				$l17 = substr($st,103,8);
				$l18 = substr($st,111,30);
				$l19 = substr($st,141,30);
				$l20 = substr($st,171,30);
				$l21 = substr($st,201,30);
				$l22 = substr($st,231,8);
				$l23 = substr($st,239,15);
				$l24 = substr($st,254,2);
				$l25 = substr($st,256,3);
				$l26 = substr($st,259,9);
				$l27 = substr($st,268,1);
				$l28a = substr($st,269,2);
				$l28b = substr($st,271,2);
				$l29 = substr($st,273,2);
				$l31 = substr($st,275,6);
				$l32 = substr($st,281,2);
				$l33 = substr($st,283,6);
				$l34a = substr($st,289,2);
				$l34b = substr($st,291,2);
				$l34c = substr($st,293,2);
				$l34d = substr($st,295,2);
				$l35 = substr($st,297,2);
				$l36 = substr($st,299,2);
				$l37 = substr($st,301,2);
				$l39 = substr($st,303,2);
				$l40a = substr($st,305,2);
				$l40b = substr($st,307,2);
				$l41a = substr($st,309,12);
				$l41b = substr($st,321,12);
				$l42a = substr($st,333,12);
				$l42b = substr($st,345,12);
				$l44 = substr($st,357,3);
				$l45 = substr($st,360,10);
				$l46 = substr($st,370,8);
				$l47 = substr($st,378,2);
				$l48 = substr($st,380,2) . "/" . substr($st,382,2) . "/" . substr($st,384,4);
				if($l48!='00/00/0000')
					$l48 = "'" . Date::toMySQL($l48) . "'";
				else
					$l48 = 'NULL';					
				$l49a = substr($st,388,2);
				$l49b = substr($st,390,2);
				$l49c = substr($st,392,2);
				$l49d = substr($st,394,2);
				$sql = "insert into learner (ProcessId, L01, L02, L03, L04, L05, L07, L08, L09, L10, L11, L12, L13, L14, L15, L16, L17, L18, L19, L20, L21, L22, L23, L24, L25, L26, L27, L28a, L28b, L29, L31, L32, L33, L34a, L34b, L34c, L34d, L35, L36, L37, L39, L40a, L40b, L41a, L41b, L42a, L42b, L44, L45, L46, L47, L48, L49a, L49b, L49c, L49d, FullyValid, FundingValid, ErrorLevel) values('0','$l01','$l02','$l03','$l04','$l05','$l07','$l08','$l09','$l10','$l11','$l12','$l13','$l14','$l15','$l16','$l17','$l18','$l19','$l20','$l21','$l22','$l23','$l24','$l25','$l26','$l27','$l28a','$l28b','$l29','$l31','$l32','$l33','$l34a','$l34b','$l34c','$l34d','$l35','$l36','$l37','$l39','$l40a','$l40b','$l41a','$l41b','$l42a','$l42b','$l44','$l45','$l46','$l47',$l48,'$l49a','$l49b','$l49c','$l49d',0,0,0);";
				DAO::execute($link, $sql);
			}
			elseif($a04=='35' || $a04=='30')
			{
				$a01 = substr($st,0,6);
				$a02 = substr($st,6,2);
				$a03 = substr($st,8,12);
				$a04 = substr($st,20,2);
				$a05 = substr($st,22,2);
				$a07 = substr($st,24,2);
				$a08 = substr($st,26,1);
				$a09 = substr($st,27,8);
				$a10 = substr($st,35,2);
				$a11a = substr($st,37,3);
				$a11b = substr($st,40,3);
				$a13 = substr($st,43,5);
				$a14 = substr($st,48,2);
				$a15 = substr($st,50,2);
				$a16 = substr($st,52,2);
				$a17 = substr($st,54,1);
				$a18 = substr($st,55,2);
				$a19 = substr($st,57,1);
				$a20 = substr($st,58,1);
				$a21 = substr($st,59,2);
				$a22 = substr($st,61,8);
				$a23 = substr($st,69,8);
				$a26 = substr($st,77,3);
				$a27 = substr($st,80,2) . "/" . substr($st,82,2) . "/" . substr($st,84,4);
				$a27 = Date::toMySQL($a27);
				$a28 = substr($st,88,2) . "/" . substr($st,90,2) . "/" . substr($st,92,4);
				$a28 = Date::toMySQL($a28);
				$a31 = substr($st,96,2) . "/" . substr($st,98,2) . "/" . substr($st,100,4);
				if($a31!='00/00/0000')
					$a31 = "'" . Date::toMySQL($a31) . "'";
				else
					$a31 = 'NULL';					
				$a32 = substr($st,104,5);
				$a34 = substr($st,109,1);
				$a35 = substr($st,110,1);
				$a36 = substr($st,111,6);
				$a40 = substr($st,117,2) . "/" . substr($st,119,2) . "/" . substr($st,121,4);
				if($a40!='00/00/0000')
					$a40 = "'" . Date::toMySQL($a40) . "'";
				else
					$a40 = 'NULL';					
				$a44 = substr($st,125,30);
				$a45 = substr($st,155,8);
				$a46a = substr($st,163,3);
				$a46b = substr($st,166,3);
				$a47a = substr($st,169,12);
				$a47b = substr($st,181,12);
				$a48a = substr($st,193,12);
				$a48b = substr($st,205,12);
				$a49 = substr($st,217,5);
				$a50 = substr($st,222,2);
				$a51a = substr($st,224,3);
				$a52 = substr($st,227,5);
				$a53 = substr($st,232,2);
				$a54 = substr($st,234,10);
				$a55 = substr($st,244,10);
				$a56 = substr($st,254,8);
				$a57 = substr($st,262,2);
				$a58 = substr($st,264,2);
				$a59 = substr($st,266,3);
				$a60 = substr($st,269,3);
				$a61 = substr($st,272,9);
				$a62 = substr($st,281,3);
				$a63 = substr($st,284,2);
				$a64 = substr($st,286,5);
				$a65 = substr($st,291,5);
				$a66 = substr($st,296,2);
				$a67 = substr($st,298,2);
				$a68 = substr($st,300,2);			

				$sql = "insert into aim (ProcessId, A01, A02, A03, A04, A05, A07, A08, A09, A10, A11a, A11b, A13, A14, A15, A16, A17, A18, A19, A20, A21, A22, A23, A26, A27, A28, A31, A32, A34, A35, A36, A40, A44, A45, A46a, A46b, A47a, A47b, A48a, A48b, A49, A50, A51a, A52, A53, A54, A55, A56, A57, A58, A59, A60, A61, A62, A63, A64, A65, A66, A67, A68, A69, A70) values('0','$a01','$a02','$a03','$a04','$a05','$a07','$a08','$a09','$a10','$a11a','$a11b','$a13','$a14','$a15','$a16','$a17','$a18','$a19','$a20','$a21','$a22','$a23','$a26','$a27','$a28',$a31,'$a32','$a34','$a35','$a36',$a40,'$a44','$a45','$a46a','$a46b','$a47a','$a47b','$a48a','$a48b','$a49','$a50','$a51a','$a52','$a53','$a54','$a55','$a56','$a57','$a58','$a59','$a60','$a61','$a62','$a63','$a64','$a65','$a66','$a67','$a68',0,'');";
				DAO::execute($link, $sql);
			}
				
			if(substr($st,10,10)=='ZZZZZZZZZZ')
				break;
		}
		
		// Delete existing employers and locations
		DAO::execute($link, "DELETE FROM organisations, locations USING organisations LEFT OUTER JOIN locations ON locations.organisations_id = organisations.id WHERE organisations.organisation_type = 2;");

			$sql = <<<HEREDOC
		SELECT DISTINCT a44,a45 FROM aim WHERE (a15='99') OR (a15!='99' AND a10='46');
HEREDOC;
		
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$a44 = $row['a44'];
				$a444 = DAO::getSingleValue($link, "select id from organisations where edrs = '$a44'");
	
				// Create an employer
				if($a444=='')
				{
					$e = new Employer();
					$e->legal_name = trim($row['a44']);
					$e->edrs = trim($row['a44']);
					$e->trading_name = trim($row['a44']);
					$e->active = 1;
					$e->save($link);
				}
				else
					$e = Employer::loadFromDatabase($link, $a444);
									
				$loc = new Location();
				$loc->short_name = "Main Site";
				$loc->full_name = "Main Site";
				$loc->organisations_id = $e->id;
				$loc->postcode = trim($row['a45']);
				$loc->save($link);
			}
		}
		
		
		// Create Learners
		DAO::execute($link, "DELETE FROM users where type = 5;");
		DAO::execute($link, "truncate tr");

			$sql = <<<HEREDOC
select * from learner
HEREDOC;
		
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				// Create a learner
				$user = new User();
				$user->firstnames = trim($row['L10']);
				$user->surname = trim($row['L09']);
				$user->username = $row['L03'];
				$user->dob = $row['L11'];
				$user->password = "password";
				$user->record_status = 1;
				$user->ni = $row['L26'];
				$user->gender = $row['L13'];
				$user->ethnicity = $row['L12'];
				$user->home_address_line_1 = $row['L18'];
				$user->home_address_line_2 = $row['L19'];
				$user->home_address_line_3 = $row['L20'];
				$user->home_address_line_4 = $row['L21'];
				$user->home_postcode = $row['L17'];
				$user->home_telephone = $row['L23'];
				$user->uln = $row['L45'];
				$user->l24 = $row['L24'];
				$user->l14 = $row['L14'];
				$user->l15 = $row['L15'];
				$user->l16 = $row['L16'];
				$user->l34a = $row['L34a'];
				$user->l34b = $row['L34b'];
				$user->l34c = $row['L34c'];
				$user->l34d = $row['L34d'];
				$user->l35 = $row['L35'];
				$user->l36 = $row['L36'];
				$user->l37 = $row['L37'];
				$user->l28a = $row['L28a'];
				$user->l28b = $row['L28b'];
				$user->l39 = $row['L39'];
				$user->l40a = $row['L40a'];
				$user->l40b = $row['L40b'];
				$user->l41a = $row['L41a'];
				$user->l41b = $row['L41b'];
				$user->l47 = $row['L47'];
				$user->l48 = $row['L48'];
				$user->l45 = $row['L45'];

				$L03 = $row['L03'];
				
				$a44 = DAO::getSingleValue($link, "SELECT a44 FROM aim WHERE A03 = '$L03' ORDER BY a44 DESC ,a45 DESC LIMIT 0,1;");
				$a45 = DAO::getSingleValue($link, "SELECT a45 FROM aim WHERE A03 = '$L03' ORDER BY a44 DESC ,a45 DESC LIMIT 0,1;");
				$a4445 = $a44.$a45;

//				$emp_id = DAO::getSingleValue($link, "SELECT organisations.id FROM organisations INNER JOIN locations ON locations.organisations_id = organisations.id WHERE CONCAT(organisations.legal_name,locations.postcode) = '$a4445'");
				$emp_id = DAO::getSingleValue($link, "SELECT organisations.id FROM organisations INNER JOIN locations ON locations.organisations_id = organisations.id WHERE organisations.legal_name = '$a44'");
				
				$location_id = DAO::getSingleValue($link, "SELECT locations.id FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$emp_id';");
				
				$user->employer_id = $emp_id;
				$user->employer_location_id = $location_id;
				$user->type = 5;
				$user->save($link, true);				
			}
		}
		$ttg = 0;

		// Create training records and ILRs;
			$sql = <<<HEREDOC
		SELECT DISTINCT L03,A15,A26 FROM learner INNER JOIN aim ON aim.A03 = learner.L03
HEREDOC;
		
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$l03 = $row['L03'];
				$a15 = $row['A15'];
				$a26 = $row['A26'];
				
				$sql2 = "SELECT learner.*,aim.* FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26';"; 
				$st2 = $link->query($sql2);
				if($st2) 
				{
					$subaim = '';
					$programmeaim = '';
					$mainaim = '';
					$L05 = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26';");	
					$a10flag = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26' and aim.a10='46';");	
					while($row2 = $st2->fetch())
					{
						// Look for Train to Gain first
						$a15 = $row2['A15'];
						$a10 = $row2['A10'];
						if($a15=='99' && $a10!='70' || ($a15=='99' && $a10=='70' && $row2['L05']=='01'))
						{
							$ttg++;
							$ilr = "<ilr><learner>";
							$ilr .= "<L01>" . $row2['L01'] . "</L01>"; 	
							$ilr .= "<L02>" . $row2['L02'] . "</L02>";	//	Contract/ Allocation type
							$ilr .= "<L03>" . $row2['L03'] . "</L03>";	//	Learner Reference Number 
							$ilr .= "<L04>" . $row2['L04'] . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
							//$ilr .= "<L05>" . $row2['L05'] . "</L05>"; 	// 	How many learning aims data sets inner loop
							$ilr .= "<L05>2</L05>"; 	// 	How many learning aims data sets inner loop
							$ilr .= "<L07>" . $row2['L07'] . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
							$ilr .= "<L08>" . $row2['L08'] . "</L08>";	//	Deletion Flag
							$ilr .= "<L09>" . $row2['L09'] . "</L09>";	
							$ilr .= "<L10>" . $row2['L10'] . "</L10>";	//	Forenames
							$ilr .= "<L11>" . Date::toShort($row2['L11']) . "</L11>"; // Date of Birth
							$ilr .= "<L12>" . $row2['L12'] . "</L12>";	//	Ethnicity
							$ilr .= "<L13>" . $row2['L13'] . "</L13>";	//	Sex
							$ilr .= "<L14>" . $row2['L14'] . "</L14>";	//	Learning difficulties/ disabilities/ health problems
							$ilr .= "<L15>" . $row2['L15'] . "</L15>";	//	Disability			
							$ilr .= "<L16>" . $row2['L16'] . "</L16>";	//	Learning difficulty
							$ilr .= "<L17>" . $row2['L17'] . "</L17>";	//	Home postcode
							$ilr .= "<L18>" . $row2['L18'] . "</L18>";	//	Address line 1
							$ilr .= "<L19>" . $row2['L19'] . "</L19>";	//	Address line 2
							$ilr .= "<L20>" . $row2['L20'] . "</L20>";	//	Address line 3
							$ilr .= "<L21>" . $row2['L21'] . "</L21>";	//	Address line 4
							$ilr .= "<L22>" . $row2['L22'] . "</L22>";		//	Current postcode
							$ilr .= "<L23>" . $row2['L23'] . "</L23>";	//	Home telephone
							$ilr .= "<L24>" . $row2['L24'] . "</L24>";	//	Country of domicile
							$ilr .= "<L25>" . $row2['L25'] . "</L25>";	//	LSC Number of funding LSC
							$ilr .= "<L26>" . $row2['L26'] . "</L26>";	//	National insurance number
							$ilr .= "<L27>" . $row2['L27'] . "</L27>";	//	Restricted use indicator
							$ilr .= "<L28a>" . $row2['L28a'] . "</L28a>";	//	Eligibility for enhanced funding
							$ilr .= "<L28b>" . $row2['L28b'] . "</L28b>";	//	Eligibility for enhanced funding
							$ilr .= "<L29>" . $row2['L29'] . "</L29>";	//	Additional support
							$ilr .= "<L31>" . $row2['L31'] . "</L31>";	//	Additional support cost 
							$ilr .= "<L32>" . $row2['L32'] . "</L32>";	//	Eligibility for disadvatnage uplift
							$ilr .= "<L33>" . $row2['L33'] . "</L33>";	//	Disadvatnage uplift factor
							$ilr .= "<L34a>" . $row2['L34a'] . "</L34a>";	//	Learner support reason
							$ilr .= "<L34b>" . $row2['L34b'] . "</L34b>";	//	Learner support reason
							$ilr .= "<L34c>" . $row2['L34c'] . "</L34c>";	//	Learner support reason
							$ilr .= "<L34d>" . $row2['L34d'] . "</L34d>";	//	Learner support reason
							$ilr .= "<L35>" . $row2['L35'] . "</L35>";	//	Prior attainment level
							$ilr .= "<L36>" . $row2['L36'] . "</L36>";	//	Learner status on last working day
							$ilr .= "<L37>" . $row2['L37'] . "</L37>";	//	Employment status on first day of learning
							$ilr .= "<L39>" . $row2['L39'] . "</L39>";	//	Destination
							$ilr .= "<L40a>" . $row2['L40a'] . "</L40a>";	//	National learner monitoring
							$ilr .= "<L40b>" . $row2['L40b'] . "</L40b>";	//	National learner monitoring
							$ilr .= "<L41a>" . $row2['L41a'] . "</L41a>";	//	Local learner monitoring
							$ilr .= "<L41b>" . $row2['L41b'] . "</L41b>";	//	Local learner monitoring
							$ilr .= "<L42a>" . $row2['L42a'] . "</L42a>";	//	Provider specified learner data
							$ilr .= "<L42b>" . $row2['L42b'] . "</L42b>";	//	Provider specified learner data
							$ilr .= "<L44>" . $row2['L44'] . "</L44>";	//	NES delivery LSC number
							$ilr .= "<L45>" . $row2['L45'] . "</L45>";	//	Unique learner number
							$ilr .= "<L46>" . $row2['L46'] . "</L46>";	
							$ilr .= "<L47>" . $row2['L47'] . "</L47>";	//	Current employment status
							$ilr .= "<L48>" . Date::toShort($row2['L48']) . "</L48>"; // Date employment status changed
							$ilr .= "<L49a>" . $row2['L49a'] . "</L49a>";	//	Current employment status
							$ilr .= "<L49b>" . $row2['L49b'] . "</L49b>";	//	Current employment status
							$ilr .= "<L49c>" . $row2['L49c'] . "</L49c>";	//	Current employment status
							$ilr .= "<L49d>" . $row2['L49d'] . "</L49d>";	//	Current employment status
							$ilr .= "</learner>";
							$ilr .= "<subaims>" . 0 . "</subaims>";	//	Subaims
							$ilr .= "<programmeaim>";
							$ilr .= $this->createAim($row2);
							$ilr .= "</programmeaim>";					
							$ilr .= "<main>";
							$ilr .= $this->createAim($row2);
							$ilr .= "</main>";					
							$ilr .= "</ilr>";

							$user = User::loadFromDatabase($link, $row2['L03']);
							$tr = new TrainingRecord();
							$tr->populate($user, true);
							$tr->contract_id = 1;
							$tr->start_date = $row2['A27'];
							$tr->target_date = $row2['A28'];
							//$tr->closure_date = $row['A31'];
							$tr->status_code = 1;
							$tr->ethnicity = $user->ethnicity;
							$tr->work_experience = 1;
							$tr->l03 = $row2['L03'];
							$tr->save($link); 	
							
							$L01 = $row2['L01'];
							$L03 = $row2['L03'];
							$A09m = $row2['A09'];
							$submission = 'W12';
							$contract_type = 1;
							$tr_id = $tr->id;
							$is_complete = 0;
							$is_valid = 0;
							$is_approved = 0;
							$is_active = 1;
							$contract_id = 1;
																												
							$query = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');";
							DAO::execute($link, $query);
						}
						else
						{
							if($row2['A04']=='35' || ($row2['A09']=='ZESF0001'))
							{
								$ilr1 = "<ilr><learner>";
								$ilr1 .= "<L01>" . $row2['L01'] . "</L01>"; 	
								$ilr1 .= "<L02>" . $row2['L02'] . "</L02>";	//	Contract/ Allocation type
								$ilr1 .= "<L03>" . $row2['L03'] . "</L03>";	//	Learner Reference Number 
								$ilr1 .= "<L04>" . $row2['L04'] . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.

								$ilr2 = "<L07>" . $row2['L07'] . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
								$ilr2 .= "<L08>" . $row2['L08'] . "</L08>";	//	Deletion Flag
								$ilr2 .= "<L09>" . $row2['L09'] . "</L09>";	
								$ilr2 .= "<L10>" . $row2['L10'] . "</L10>";	//	Forenames
								$ilr2 .= "<L11>" . Date::toShort($row2['L11']) . "</L11>"; // Date of Birth
								$ilr2 .= "<L12>" . $row2['L12'] . "</L12>";	//	Ethnicity
								$ilr2 .= "<L13>" . $row2['L13'] . "</L13>";	//	Sex
								$ilr2 .= "<L14>" . $row2['L14'] . "</L14>";	//	Learning difficulties/ disabilities/ health problems
								$ilr2 .= "<L15>" . $row2['L15'] . "</L15>";	//	Disability			
								$ilr2 .= "<L16>" . $row2['L16'] . "</L16>";	//	Learning difficulty
								$ilr2 .= "<L17>" . $row2['L17'] . "</L17>";	//	Home postcode
								$ilr2 .= "<L18>" . $row2['L18'] . "</L18>";	//	Address line 1
								$ilr2 .= "<L19>" . $row2['L19'] . "</L19>";	//	Address line 2
								$ilr2 .= "<L20>" . $row2['L20'] . "</L20>";	//	Address line 3
								$ilr2 .= "<L21>" . $row2['L21'] . "</L21>";	//	Address line 4
								$ilr2 .= "<L22>" . $row2['L22'] . "</L22>";		//	Current postcode
								$ilr2 .= "<L23>" . $row2['L23'] . "</L23>";	//	Home telephone
								$ilr2 .= "<L24>" . $row2['L24'] . "</L24>";	//	Country of domicile
								$ilr2 .= "<L25>" . $row2['L25'] . "</L25>";	//	LSC Number of funding LSC
								$ilr2 .= "<L26>" . $row2['L26'] . "</L26>";	//	National insurance number
								$ilr2 .= "<L27>" . $row2['L27'] . "</L27>";	//	Restricted use indicator
								$ilr2 .= "<L28a>" . $row2['L28a'] . "</L28a>";	//	Eligibility for enhanced funding
								$ilr2 .= "<L28b>" . $row2['L28b'] . "</L28b>";	//	Eligibility for enhanced funding
								$ilr2 .= "<L29>" . $row2['L29'] . "</L29>";	//	Additional support
								$ilr2 .= "<L31>" . $row2['L31'] . "</L31>";	//	Additional support cost 
								$ilr2 .= "<L32>" . $row2['L32'] . "</L32>";	//	Eligibility for disadvatnage uplift
								$ilr2 .= "<L33>" . $row2['L33'] . "</L33>";	//	Disadvatnage uplift factor
								$ilr2 .= "<L34a>" . $row2['L34a'] . "</L34a>";	//	Learner support reason
								$ilr2 .= "<L34b>" . $row2['L34b'] . "</L34b>";	//	Learner support reason
								$ilr2 .= "<L34c>" . $row2['L34c'] . "</L34c>";	//	Learner support reason
								$ilr2 .= "<L34d>" . $row2['L34d'] . "</L34d>";	//	Learner support reason
								$ilr2 .= "<L35>" . $row2['L35'] . "</L35>";	//	Prior attainment level
								$ilr2 .= "<L36>" . $row2['L36'] . "</L36>";	//	Learner status on last working day
								$ilr2 .= "<L37>" . $row2['L37'] . "</L37>";	//	Employment status on first day of learning
								$ilr2 .= "<L39>" . $row2['L39'] . "</L39>";	//	Destination
								$ilr2 .= "<L40a>" . $row2['L40a'] . "</L40a>";	//	National learner monitoring
								$ilr2 .= "<L40b>" . $row2['L40b'] . "</L40b>";	//	National learner monitoring
								$ilr2 .= "<L41a>" . $row2['L41a'] . "</L41a>";	//	Local learner monitoring
								$ilr2 .= "<L41b>" . $row2['L41b'] . "</L41b>";	//	Local learner monitoring
								$ilr2 .= "<L42a>" . $row2['L42a'] . "</L42a>";	//	Provider specified learner data
								$ilr2 .= "<L42b>" . $row2['L42b'] . "</L42b>";	//	Provider specified learner data
								$ilr2 .= "<L44>" . $row2['L44'] . "</L44>";	//	NES delivery LSC number
								$ilr2 .= "<L45>" . $row2['L45'] . "</L45>";	//	Unique learner number
								$ilr2 .= "<L46>" . $row2['L46'] . "</L46>";	
								$ilr2 .= "<L47>" . $row2['L47'] . "</L47>";	//	Current employment status
								$ilr2 .= "<L48>" . Date::toShort($row2['L48']) . "</L48>"; // Date employment status changed
								$ilr2 .= "<L49a>" . $row2['L49a'] . "</L49a>";	//	Current employment status
								$ilr2 .= "<L49b>" . $row2['L49b'] . "</L49b>";	//	Current employment status
								$ilr2 .= "<L49c>" . $row2['L49c'] . "</L49c>";	//	Current employment status
								$ilr2 .= "<L49d>" . $row2['L49d'] . "</L49d>";	//	Current employment status
								$ilr2 .= "</learner>";
								
								$programmeaim = "<programmeaim>" . $this->createAim($row2) . "</programmeaim>";
								$user = User::loadFromDatabase($link, $row2['L03']);
								$tr = new TrainingRecord();
								$tr->populate($user, true);
								$tr->contract_id = 1;
								$tr->start_date = $row2['A27'];
								$tr->target_date = $row2['A28'];
								//$tr->closure_date = $row['A31'];
								$tr->status_code = 1;
								$tr->ethnicity = $user->ethnicity;
								$tr->work_experience = 1;
								$tr->l03 = $row2['L03'];
								$tr->save($link); 	
								$L01 = $row2['L01'];
								$L03 = $row2['L03'];
								$A09m = $row2['A09'];
								$submission = 'W12';
								$contract_type = 1;
								$tr_id = $tr->id;
								$is_complete = 0;
								$is_valid = 0;
								$is_approved = 0;
								$is_active = 1;
								$contract_id = 1;
							}
							
							if($row2['A10']=='46' || ($row2['A10']=='70' && $row2['A09']!='ZESF0001'))
							{
								$mainaim = "<main>" . $this->createAim($row2) . "</main>";
								$A09m = $row2['A09'];
							}
							
							if($row2['A04']=='30' && $row2['A09']!='ZESF0001' && ($row2['A10']=='45' || $row2['A10']=='99'))
							{
								$subaim .=  "<subaim>" . $this->createAim($row2) . "</subaim>";								
							}							
						}
					}
					if($programmeaim!='')
					{
						
						$ilr = $ilr1 . "<L05>" . str_pad($L05,2,'0',STR_PAD_LEFT) . "</L05>" . $ilr2 . $programmeaim . $mainaim . $subaim . "</ilr>";	
						
						$query = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');";
						DAO::execute($link, $query);
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
		return $ilr;
	}
}
?>