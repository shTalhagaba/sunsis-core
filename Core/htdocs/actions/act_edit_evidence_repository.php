<?php
class edit_evidence_repository implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		
		$que = "select evidences from qualifications where id='$qualification_id' and internaltitle = '$internaltitle'";
		$xml = trim(DAO::getSingleValue($link, $que));
		
/*		$no_of_evidences = substr_count($xml, '<evidence');
		$start = 0;
		$end = 0;
		$length = 0;
		$evidences = Array();
		for($a = 1; $a<=$no_of_evidences; $a++)
		{
			$start = strpos($xml, "<evidence", $start);
			$end = strpos($xml, ">", $start);
			$text = substr($xml, $start, $end-$start+1); // Take full evidence
			$text = substr($text, strpos($text,'"')+1);	// Strip from left
			$text = substr($text, 0, strpos($text,'"')); // Strip from right
			$start++;
			$evidences[$a] = $text;				
		}
*/


		//$pageDom = new DomDocument();
		//$pageDom->loadXML($xml);
		$pageDom = XML::loadXmlDom($xml);
		$e = $pageDom->getElementsByTagName('evidence');
		$a = 1;
		$evidences = Array();
		foreach($e as $node)
		{
			$data[$a++]['title'] = $node->getAttribute("title");
		}
		$no_of_evidences = $a-1;

		
		
		// Dropdowns
		$dropdown_type = "SELECT id, CONCAT(id, ' - ', type), null FROM lookup_evidence_type ORDER BY id;";
		$dropdown_type = DAO::getResultset($link, $dropdown_type);
		
		$dropdown_content = "SELECT id, CONCAT(id, ' - ', content), null FROM lookup_evidence_content ORDER BY id;";
		$dropdown_content = DAO::getResultset($link, $dropdown_content);
		
		$dropdown_category = "SELECT id, CONCAT(id, ' - ', category), null FROM lookup_evidence_categories ORDER BY id;";
		$dropdown_category = DAO::getResultset($link, $dropdown_category);

		// Getting evidences from table
			$sql = <<<HEREDOC
SELECT
	*
FROM
	evidence
where 
	qualification_id = '$qualification_id' and internaltitle = '$internaltitle'
HEREDOC;
		
		$st = $link->query($sql);	
		if($st) 
		{
			$index=1;
			while($row = $st->fetch())
			{
				
				$data[$index]['title'] 		= $row['title'];
				$data[$index]['reference'] 	= $row['reference'];
				$data[$index]['portfolio'] 	= $row['portfolio'];
				$data[$index]['type'] 		= $row['type'];
				$data[$index]['content'] 	= $row['content'];
				$data[$index]['category'] 	= $row['category'];
				$index++;
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		
		include('tpl_edit_evidence_repository.php');
	}
}
?>