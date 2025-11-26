<?php 
class save_evidences_repository implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$xml= isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		
		//$xmlreviews = new SimpleXMLElement($xml);
		$xmlreviews = XML::loadSimpleXML($xml);
		$values = '';
		foreach($xmlreviews->evidence as $review)
		{	
			$et = ($review->type=='')?0:$review->type;
			$co = ($review->content=='')?0:$review->content;
			$cat = ($review->category=='')?0:$review->category;
			$values .= '(' . "'" . $qualification_id . "'" . ',' . "'" . $internaltitle . "'" . ','  . "'" . $review->title . "'"  . ',' . "'" . $review->reference . "'" . ',' . "'" . $review->portfolio . "'" . ',' . $et . ',' . $co . ',' . $cat . '),';
		}
		
		$values = substr($values, 0, -1);  

if($values!='')
{
// Delete existing reviews
		$sql2 = <<<HEREDOC
delete from
	evidence
where qualification_id = '$qualification_id' and internaltitle = '$internaltitle'
HEREDOC;
		DAO::execute($link, $sql2);
				
// Store reviews		
		$sql2 = <<<HEREDOC
insert into 
	evidence (qualification_id, internaltitle, title, reference, portfolio, type, content, category)
values
	$values;	
HEREDOC;
		DAO::execute($link, $sql2);
}				
	
http_redirect('do.php?_action=read_qualification&id='.$qualification_id.'&internaltitle='.$internaltitle);
	
}
}
?>
