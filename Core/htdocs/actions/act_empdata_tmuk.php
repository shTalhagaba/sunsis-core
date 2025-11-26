<?php
class empdata_tmuk implements IAction
{
	public function execute(PDO $link)
	{

		
		$handle = fopen("sml.csv","r");
		$st = fgets($handle);
		//Display header
		print $st;
		$delimiter=",";
		
		while (!feof($handle)) 
		{
			$st = fgets($handle);
			
			$record = explode($delimiter, $st);
			//0-Mbr no, 1-Last name, 2-name, 3-Group ( Employer ) ,4-Dept (location) , 5-title ( sector)
			$s=strpos($record[5],'(');
			$e=strpos($record[5],')');
			
			$sector_code=strtolower(substr($record[5],($s+1),3));
			
			print "<br/>";
			//Check if employer exist
		
			
$query = <<<HEREDOC
select count(legal_name), id, legal_name from organisations where legal_name = '$record[4]';
HEREDOC;
			$st2 = $link->query($query);
			if($st2 == false)
			{
				throw new DatabaseException($link, $query);
			}
			$sqlres = $st2->fetch();
			
			if ( $sqlres[0] == 0 ) // New organisation
			{
				// Add Employer 
$query = <<<HEREDOC
insert into organisations set legal_name = '$record[4]', organisation_type = 2 , trading_name= '$record[4]', sector = 1;
HEREDOC;
				check($link,$st2 = $link->query($query));				
			
			
			}
			else
			{
			if ( 0 ) {
$query = <<<HEREDOC
update organisations set legal_name = '$record[4]', organisation_type = 2 , trading_name= '$record[4]', sector = (select id from lookup_sector_types where LOWER(description) = '$sector_code' );
HEREDOC;
						
				check($link,$st2 = $link->query($query));				
			}
			
			// Add location for current company.

//$query = <<<HEREDOC
//update organisations set legal_name = '$record[4]', organisation_type = 2 , trading_name= '$record[4]', sector = (select id from lookup_sector_types where LOWER(description) = '$sector_code' );
//HEREDOC;

$query = <<<HEREDOC
select id from organisations where legal_name = '$record[4]';
HEREDOC;

				check($link,$st2 = $link->query($query));					
				$sqlres = $st2->fetch();
				
$query = <<<HEREDOC
insert into locations set organisations_id = $sqlres[0], full_name = '$record[3]';
HEREDOC;
				check($link,$st2 = $link->query($query));

				//die("OK");
			
			}
			
			
			
			//print print_r($st2->fetch()) . " " .$record[4] . "<br/>";
		
		}
		
			
		fclose($handle);
		
		print "Imported data";

	}
}


function check($link,$qry)
{
	if($qry == false)
	{
		throw new Exception("Could not execute qry" . implode($link->errorInfo()));
	}


}
?>