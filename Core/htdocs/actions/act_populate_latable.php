<?php
class populate_latable implements IAction
{
	public function execute(PDO $link)
	{

			$sql = <<<HEREDOC
SELECT
*
FROM
	ilr
	where submission = 'W12';
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo $row['tr_id'] . '<br/>' ;
					//$xml = $row['ilr'];
					//$ilr = new SimpleXMLElement($xml);
					//
					//foreach($ilr->learner as $learner)
					//{



					//}
				
			
			}
		
		}		
		else 
		{ 
			echo "no records"; 
			
		}
	
	}
	
	

}
?>