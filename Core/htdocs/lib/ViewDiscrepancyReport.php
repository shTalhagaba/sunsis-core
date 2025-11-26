<?php
class ViewDiscrepancyReport extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT DISTINCT tr_id FROM ilr WHERE contract_id IN (SELECT DISTINCT id FROM contracts WHERE funding_body = 2 and contract_year = 2011) 
AND tr_id IN (SELECT DISTINCT tr_id FROM ilr WHERE contract_id IN (SELECT DISTINCT id FROM contracts WHERE contract_year = 2010));
HEREDOC;
			$view = $_SESSION[$key] = new ViewDiscrepancyReport();
			$view->setSQL($sql);
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			//echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>L01</th>
		<th>A09</th>
		<th>2010 Contract</th>
		<th>2011 Contract</th>
		<th>2010 A34</th>
		<th>2011 A34</th>
		<th>2010 A35</th>
		<th>2011 A35</th>
		<th>2010 A31</th>
		<th>2011 A31</th>
		<th>2010 A40</th>
		<th>2011 A40</th>
	</tr>
	</thead>
HEREDOC;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$ilr2011 = DAO::getSingleValue($link, "SELECT ilr FROM ilr WHERE tr_id = $tr_id AND contract_id IN (SELECT id FROM contracts WHERE contract_year = 2011) ORDER BY submission DESC LIMIT 0,1;");
				//$pageDom2011 = new DomDocument();
				//@$pageDom2011->loadXML($ilr2011);
				$pageDom2011 = XML::loadXmlDom($ilr2011);

				$ilr2010 = DAO::getSingleValue($link, "SELECT ilr FROM ilr WHERE tr_id = $tr_id AND contract_id IN (SELECT id FROM contracts WHERE contract_year = 2010) ORDER BY submission DESC LIMIT 0,1;");
				//$pageDom2010 = new DomDocument();
				//@$pageDom2010->loadXML($ilr2010);
				$pageDom2010 = XML::loadXmlDom($ilr2010);
				
				$l03 = DAO::getSingleValue($link, "select l03 from ilr where tr_id = $tr_id limit 0,1");
				$contract2010 = DAO::getSingleValue($link, "select title from contracts where contract_year = 2010 and id in (select contract_id from ilr where tr_id = '$tr_id')");
				$contract2011 = DAO::getSingleValue($link, "select title from contracts where contract_year = 2011 and id in (select contract_id from ilr where tr_id = '$tr_id')");
				
				$evidences2011 = $pageDom2011->getElementsByTagName('main');
				foreach($evidences2011 as $evidence2011)
				{
					$a092011 = "" . $evidence2011->getElementsByTagName('A09')->item(0)->nodeValue;
					$a342011 = "" . $evidence2011->getElementsByTagName('A34')->item(0)->nodeValue;
					$a352011 = "" . $evidence2011->getElementsByTagName('A35')->item(0)->nodeValue;
					$a312011 = "" . $evidence2011->getElementsByTagName('A31')->item(0)->nodeValue;
					$a402011 = "" . @$evidence2011->getElementsByTagName('A40')->item(0)->nodeValue;
				}	

				$evidences2010 = $pageDom2010->getElementsByTagName('main');
				foreach($evidences2010 as $evidence2010)
				{
					$a092010 = "" . $evidence2010->getElementsByTagName('A09')->item(0)->nodeValue;
					$a342010 = "" . $evidence2010->getElementsByTagName('A34')->item(0)->nodeValue;
					$a352010 = "" . $evidence2010->getElementsByTagName('A35')->item(0)->nodeValue;
					$a312010 = "" . $evidence2010->getElementsByTagName('A31')->item(0)->nodeValue;
					$a402010 = "" . @$evidence2010->getElementsByTagName('A40')->item(0)->nodeValue;
				}

				if($a092011 == $a092010 && ($a342011 != $a342010 || $a352011 != $a352010 || $a312011 != $a312010 || $a402011 != $a402010) && $a312011=='00000000')
				{
					echo '<tr><td>' . $l03 . '</td><td>' . $a092011 . '</td><td>' . $contract2010 . '</td><td>' . $contract2011 . '</td><td>' . $a342010 . '</td><td>' . $a342011 . '</td><td>' . $a352010 . '</td><td>' . $a352011 . '</td><td>' . $a312010 . '</td><td>' . $a312011 . '</td><td>' . $a402010 . '</td><td>' . $a402011 . '</td></tr>';
				}	
							
				$evidences2011 = $pageDom2011->getElementsByTagName('subaim');
				foreach($evidences2011 as $evidence2011)
				{
					$a092011 = "" . $evidence2011->getElementsByTagName('A09')->item(0)->nodeValue;
					$a342011 = "" . $evidence2011->getElementsByTagName('A34')->item(0)->nodeValue;
					$a352011 = "" . $evidence2011->getElementsByTagName('A35')->item(0)->nodeValue;
					$a312011 = "" . $evidence2011->getElementsByTagName('A31')->item(0)->nodeValue;
					$a402011 = "" . @$evidence2011->getElementsByTagName('A40')->item(0)->nodeValue;

					$evidences2010 = $pageDom2010->getElementsByTagName('subaim');
					foreach($evidences2010 as $evidence2010)
					{
						$a092011 = "" . $evidence2010->getElementsByTagName('A09')->item(0)->nodeValue;
						$a342011 = "" . $evidence2010->getElementsByTagName('A34')->item(0)->nodeValue;
						$a352011 = "" . $evidence2010->getElementsByTagName('A35')->item(0)->nodeValue;
						$a312011 = "" . $evidence2010->getElementsByTagName('A31')->item(0)->nodeValue;
						$a402011 = "" . @$evidence2010->getElementsByTagName('A40')->item(0)->nodeValue;

						if($a092011 == $a092010 && ($a342011 != $a342010 || $a352011 != $a352010 || $a312011 != $a312010 || $a402011 != $a402010) && $a312011=='00000000')
						{
							echo '<tr><td>' . $l03 . '</td><td>' . $a092011 . '</td><td>' . $contract2010 . '</td><td>' . $contract2011 . '</td><td>' . $a342010 . '</td><td>' . $a342011 . '</td><td>' . $a352010 . '</td><td>' . $a352011 . '</td><td>' . $a312010 . '</td><td>' . $a312011 . '</td><td>' . $a402010 . '</td><td>' . $a402011 . '</td></tr>';
						}	
					}
					
				}
			}
		}
	}
}
?>