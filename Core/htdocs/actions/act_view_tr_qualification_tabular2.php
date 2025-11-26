<?php
class view_tr_qualification_tabular2  implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=view_tr_qualification_tabular&qualification_id=" . $qualification_id . "&internaltitle=" . $internaltitle . "&framework_id=" . $framework_id . "&tr_id=" . $tr_id, "View Learner Qualification (Tabular)");

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		
		if($qualification_id == '')
		{
			throw new Exception("Missing argument \$qualification_id");
		}
		
		$vo = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $tr_id, $internaltitle);

		if(is_null($vo))
		{
			throw new Exception("Couldn't find qualification");
		}
		
		
	
		$evidence = DAO::getResultSet($link,"select id, type from lookup_evidence_type");
		$evidence2 = DAO::getResultSet($link,"select id, content from lookup_evidence_content");
		$evidence3 = DAO::getResultSet($link,"select id, category from lookup_evidence_categories");
		

		// File Repository data preparation
		$html2='';
		if(DB_NAME=='am_demo'|| DB_NAME=='am_midkent' || DB_NAME=='am_portsmouth')
		{
			$db = DB_NAME;
			$user = $tr->username;	
				
			$urls2 = Array();
			if(file_exists(DATA_ROOT."/uploads/$db/$user"))
			{		
				$TrackDir=opendir(DATA_ROOT."/uploads/$db/$user");
				$n2 = 0;
				$html2 = '<table>';
				while ($file = readdir($TrackDir)) 
				{ 
					if ($file != "." && $file != ".." && $file!='admin') 
					{	
						$n2++;
						if(isset($urls2[$n2]))
						{	
							$urls2[$no] .=  '<a href="do.php?_action=downloader&path=' . $user . '/'. "&f=" . rawurlencode($file) . '"><br>' . $n2 . ". " .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=" . $user . "&f=" . rawurlencode($file) . "&tr_id=" . $tr->id . rawurlencode('do.php?_action=read_training_record&id='.$tr->id.'&repo=1');;
							$html2 .= "<tr><td><input type=checkbox name=attach /></td><td>" . $urls2[$n2] . "</td></tr>";
							die($urls2[$n2]);
						}
						else
						{	 
							$urls2[$n2] = '<a href="do.php?_action=downloader&path=' . $user . '/'. "&f=" . rawurlencode($file) . '">' . $n2 . ". " .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=" . $user . "&f=" . rawurlencode($file) . "&tr_id=" . $tr->id . rawurlencode('do.php?_action=read_training_record&id='.$tr->id.'&repo=1');;
							$html2 .= "<tr><td><input type=checkbox name=attach /></td><td>" . $urls2[$n2] . "</td></tr>";
						}
					}
				}
				$html2 .= '</table>'; 
				closedir($TrackDir); 
			}	
		}
		
		
		$urls = Array();
		//$pageDom = new DomDocument();
		//$pageDom->loadXML(utf8_encode($vo->evidences));
		$pageDom = XML::loadXmlDom(mb_convert_encoding($vo->evidences,'UTF-8'));
		$e = $pageDom->getElementsByTagName('evidence');
		$no = 0;
		foreach($e as $node)
		{
			$no++;
		
			$qid = str_replace("/","",$qualification_id);
			if(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid."/".$no))
			{		
				$TrackDir=opendir(DATA_ROOT."/uploads/" .DB_NAME. "/" . $tr_id."/".$qid."/".$no);
				$n2 = 0;
				while ($file = readdir($TrackDir)) 
				{ 
					if ($file != "." && $file != "..") 
					{	
						$n2++;
						if(isset($urls[$no]))
						{	
							$urls[$no] .= '<a href="do.php?_action=downloader&path=/' . $tr_id."/".$qid."/".$no .'/'. "&f=" . rawurlencode($file) . '"><br>' . $n2 . ". " .$file . '</a>';
							die($urls[$no]);
						}
						else
						{	 
							$urls[$no] = '<a href="do.php?_action=downloader&path=/' . $tr_id."/".$qid."/".$no .'/'. "&f=" . rawurlencode($file) . '">' . $n2 . ". " .$file . '</a>';
						}
					}
				} 
				closedir($TrackDir); 
			}	
		}
		
		require_once('tpl_view_tr_qualification_tabular2.php');
	}
}
?>
