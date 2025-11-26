<?php
class qualification_export implements IAction
{
	public function execute(PDO $link)
	{

		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
	
		$filename = "qualification";
		
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

// Internet Explorer requires two extra headers when downloading files over HTTPS
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
{
	header('Pragma: public');
	header('Cache-Control: max-age=0');
}			

		if(DB_NAME=='am_edexcel')
			$username = $_SESSION['user']->username;
		else
			$username = DB_NAME;
		
		$sql = "select * from qualifications where id = '$id' and clients = '$username'";

		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				
				//$pageDom = new DomDocument();
				//$pageDom->loadXML(utf8_encode($row['evidences']));
				$pageDom = XML::loadXmlDom(mb_convert_encoding($row['evidences'],'UTF-8'));
				$e = $pageDom->getElementsByTagName('unit');
				foreach($e as $node)
				{
					echo "Unit Title";
					echo ',"' . $node->getAttribute('title') . '"' . "\r\n";
					echo "Unit Reference, " . $node->getAttribute('reference') . "\r\n";
					echo "Unit Owner Reference, "  . $node->getAttribute('owner_reference')  . "\r\n";
					echo "Credits, "  . $node->getAttribute('credits')  . "\r\n";
					echo "Guided Learning Hours, "  . $node->getAttribute('glh')  . "\r\n";
					$e2 = $node->getElementsByTagName('element');
					foreach($e2 as $node2)
					{
						echo "Element"; 
						echo ',"' . $node2->getAttribute('title') . '"' . "\r\n";
						$e3 = $node2->getElementsByTagName('evidence');
						foreach($e3 as $node3)
						{
							echo "Evidence";
							echo ',"' . $node3->getAttribute('title') . '"' . "\r\n";
						}				
					}				
				}
			}
		}		
	}
}
?>