<?php
class lewisham_step1 implements IAction
{
	public function execute(PDO $link)
	{

	//	$link->query("delete from aim where A10 Not in (45,46)");
	//	$link->query("delete from learner where L03 not in (select A03 from aim)");

		// Create Employers		
		$sql = "select distinct A44, A45 from aim where trim(a44)!='000000000' and a44 not in (select edrs from organisations where edrs is not null);";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				// Employer Does not Exists so create one
				$a44 = trim($row['A44']);
				$a23 = $row['A45'];
				$o = new Employer($link);
				$o->legal_name = $a44;
				$o->edrs = $a44;
				$o->organisation_type = 2;
				$o->active = 1;
				$o->save($link);

				$l = new Location($link);
				$l->full_name = $a44;
				$l->postcode = $a23;
				$l->organisations_id = $o->id;
				$l->save($link);
			}
		}
		pre("Complete");
	}
}
?>
