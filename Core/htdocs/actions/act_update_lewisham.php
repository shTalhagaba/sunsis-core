<?php
class update_lewisham implements IAction
{
	public function execute(PDO $link)
	{

		DAO::execute($link, "delete from aim where A10 Not in (45,46)");
		DAO::execute($link, "delete from learner where L03 not in (select A03 from aim)");

		$st = $link->query("SELECT * FROM aim LEFT JOIN learner ON learner.L03 = aim.A03 WHERE A15 = 99 AND A26 = 0;");
		if($st) 
		{
			while($row = $st->fetch())
			{
				$a44 = trim($row['A44']);
				$employer_id = DAO::getSingleValue($link, "select id from organisations where edrs = '$a44'");
				if($employer_id=='')
				{
					// Employer deoes not exists so create one
					$a44 = $row['A44'];
					$a45 = $row['A45'];
					$o = new Employer($link);
					$o->legal_name = $a44;
					$o->edrs = $a44;
					$o->organisation_type = 2;
					$o->active = 1;
					$o->save($link);
	
					$l = new Location($link);
					$l->full_name = $a44;
					$l->postcode = $a45;
					$l->organisations_id = $o->id;
					$l->save($link);
					$employer_id = $l->id;	

					// Employers exists but location does not exists so create one
					$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = $found");				
					if(!$loc_id)
					{
						$o = Employer::loadFromDatabase($link, $found);
						$l = new Location($link);
						$l->full_name = $a44;
						$l->postcode = $postcode;
						$l->organisations_id = $o->id;
						$l->save($link);
						$location_id = $l->id;
					}
				}
				else
				{
					$location_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$employer_id'");										
				}
				// Create Learner if does not exists
				$a03 = $row['A03'];
				$username = DAO::getSingleValue($link, "select username from users where username = '$a03'");
				if($username == '')
				{
					
				}				
				
			}
		}
		
		pre("Completed");
	}
}
?>