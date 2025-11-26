<?php
class ajax_get_evidence_types implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml; charset=iso-8859-1');
		header("Cache-Control: public, must-revalidate, max-age=600"); // Valid for ten minutes
		header("Expires: ");
		header("Pragma: public");
		
		$table = DAO::getLookupTable($link, "SELECT id, `type` FROM lookup_evidence_type", "lookup_evidence_type");
		
		echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
		echo "<evidencetypes>";
		foreach($table as $key=>$value)
		{
			echo "<type>", htmlspecialchars($value), "</type>";
		}
		echo "</evidencetypes>";
		 
		/*$sql = "SELECT * FROM lookup_evidence_type";
		$xml = '';
		$st = $link->query($sql);
		if($st)
		{
			$xml .= "<evidencetypes>";
			
			while($row = $st->fetch())
			{
				$xml .= "<type>" . $row['type'] . "</type>";
			}
			
			$xml .= '</evidencetypes>';
			
		}
	
		echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>" . $xml;*/

	}
}
?>