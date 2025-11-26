<?php
class repair_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		

		// Getting evidences from table
			$sql = <<<HEREDOC
SELECT
	*
FROM
	qualifications
WHERE
	id = '$qualification_id' and internaltitle='$internaltitle';
HEREDOC;
		
		$st = $link->query($sql);	
		if($st) 
		{
			$data = Array();	
			while($row = $st->fetch())
			{
				$xml = $row['evidences'];
				$id = $row['id'];
				$internaltitle = $row['internaltitle'];

				//$pageDom = new DomDocument();
				//$pageDom->loadXML($xml);
				$pageDom = XML::loadXmlDom($xml);

				$e = $pageDom->getElementsByTagName('unit');
				foreach($e as $node)
				{
					$t = $node->getAttribute("reference");
					$node->setAttribute("owner_reference", $t);
				}

				$blob = substr($pageDom->saveXML(),22);

				$sql2 = "update qualifications set evidences = '$blob' where id = '$id' and internaltitle='$internaltitle'";
				DAO::execute($link, $sql2);
				
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
	}
}
?>