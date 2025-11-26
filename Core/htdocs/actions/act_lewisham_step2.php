<?php
class lewisham_step2 implements IAction
{
	public function execute(PDO $link)
	{

//		$link->query("delete from aim where A10 Not in (45,46)");
//		$link->query("delete from learner where L03 not in (select A03 from aim)");

		// Create new Learners and Update existing learners
		$sql = "select * from learner where trim(L03) not in (select trim(username) from users where type = 5);";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				// Learner does not exist so create one
				$l03 = trim($row['L03']);
				$edrs = DAO::getSingleValue($link, "select A44 from aim where A03 = '$l03' and A44!='000000000'");						
				if($edrs=='')
					$edrs='000000000';
				$employer_id = DAO::getSingleValue($link, "select id from organisations where edrs = '$edrs'");
				if($employer_id == '')
					pre("Learner " . $l03 . " and Employer " . $edrs . " does not exist please check step 1");
				$employer_location_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$employer_id'");
				if($employer_location_id=='')
					pre("Location of " . $edrs . " does not exists please check step 2");
										
				$user = new User();
				$user->username = trim($row['L03']);
				$user->surname = trim($row['L09']);
				$user->firstnames = trim($row['L10']);
				$user->enrollment_no = trim($row['L03']);;
				$user->password = "password";
				$user->home_postcode = trim($row['L17']);
				$user->home_address_line_1 = trim($row['L18']);
				$user->home_address_line_2 = trim($row['L19']);
				$user->home_address_line_3 = trim($row['L20']);
				$user->home_address_line_4 = trim($row['L21']);
				$user->telephone = trim($row['L23']);
				$user->email = trim($row['L51']);
				$user->ni = trim($row['L26']);
				$user->type = 5;
				$user->l24 = trim($row['L24']);
				$user->l15 = trim($row['L15']);
				$user->l16 = trim($row['L16']);
				$user->l14 = trim($row['L14']);
				$user->l35 = trim($row['L35']);
				$user->uln = trim($row['L45']);
				$user->dob = Date::toMySQL($row['L11']);
				$user->ethnicity = trim($row['L12']);
				$user->gender = trim($row['L13']);
				
				$user->employer_id = $employer_id;
				$user->employer_location_id = $employer_location_id;
				
				$user->save($link, true);
			}	
		}			
		// update learner information from learner table
		$sql = <<<SQL
UPDATE
  users
  LEFT JOIN learner
    ON learner.L03 = users.username
SET
  users.surname = learner.L09,
  users.firstnames = learner.L10,
  users.enrollment_no = learner.L03,
  users.home_postcode = learner.L17,
  users.home_address_line_1 = learner.L18,
  users.home_address_line_2 = learner.L19,
  users.home_address_line_3 = learner.L20,
  users.home_address_line_4 = learner.L21,
  users.home_telephone = learner.L23,
  users.home_email = learner.L51,
  users.ni = learner.L26,
  users.l24 = learner.L24,
  users.l15 = learner.L15,
  users.l16 = learner.L16,
  users.l14 = learner.L14,
  users.l35 = learner.L35,
  users.uln = learner.L45,
  users.dob = learner.L11,
  users.ethnicity = learner.L12,
  users.gender = learner.L13
WHERE
  users.username = learner.l03
SQL;
		DAO::execute($link, $sql);
		//DAO::execute($link, "UPDATE users LEFT JOIN learner ON learner.L03 = users.username SET users.surname = learner.L09, users.firstnames = learner.L10, users.enrollment_no = learner.L03, users.home_postcode = learner.L17, users.home_street_description = learner.L18, users.home_locality = learner.L19, users.home_town = learner.L20, users.home_county = learner.L21, users.home_telephone = learner.L23, users.home_email = learner.L51, users.ni = learner.L26, users.l24 = learner.L24, users.l15 = learner.L15, users.l16 = learner.L16, users.l14 = learner.L14, users.l35 = learner.L35, users.uln = learner.L45, users.dob = learner.L11, users.ethnicity = learner.L12, users.gender = learner.L13 WHERE users.username = learner.l03; ");

		// Relink learners to the employers if has been changed
		$sql2 = "SELECT DISTINCT A03, A44, organisations.id FROM aim LEFT JOIN organisations ON organisations.edrs = aim.a44 WHERE a44 != '999999999' AND a44!='000000000';";
		$st2 = $link->query($sql2);
		if($st2) 
		{
			while($row2 = $st2->fetch())
			{
				$username = $row2['A03'];
				$edrs = $row2['A44'];
				$org_id = $row2['id'];
				if($org_id!='')
					DAO::execute($link, "update users set employer_id = $org_id where username = '$username'");
			}
		}
		// Relink locations
		DAO::execute($link, "update users left join organisations on organisations.id = users.employer_id left join locations on locations.organisations_id = organisations.id set users.employer_location_id = locations.id");
		
		pre("Complete");
	}
}
?>