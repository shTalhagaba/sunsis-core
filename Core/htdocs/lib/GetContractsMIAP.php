<?php
class GetContractsMIAP extends View
{

	public static function getInstance()
	{
		$key = 'view'.__CLASS__;
		
		if(true)
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	contracts.*, organisations.legal_name,
	(select count(distinct L03) from ilr where ilr.contract_id = contracts.id) as ilrs 
FROM
	contracts
	LEFT JOIN organisations on organisations.id = contracts.contract_holder order by contract_year desc, title
	where contracts.active = 1;
HEREDOC;

			if($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)
			{
				$emp_id = $_SESSION['user']->employer_id;

				$sql = <<<HEREDOC

SELECT DISTINCT
	contracts.*, organisations.legal_name,
	(select count(distinct L03) from ilr where ilr.contract_id = contracts.id) as ilrs
FROM
	contracts
	LEFT JOIN organisations on organisations.id = contracts.contract_holder
	LEFT JOIN tr ON contracts.id = tr.`contract_id`
WHERE
    contracts.active = 1
    AND tr.provider_id = '$emp_id'

HEREDOC;

			}
			
			$view = $_SESSION[$key] = new GetContractsMIAP();
			$view->setSQL($sql);
			
/*			$options = 'SELECT username, CONCAT(firstnames," ", surname), null, CONCAT("WHERE assessors.username=",CHAR(39),username,CHAR(39)) FROM users where type=3';
			$f = new DropDownViewFilter('assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
			$f = new DropDownViewFilter('employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE courses.id=',id) FROM courses";
			$f = new DropDownViewFilter('course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);
*/			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th><input id="global" type="checkbox" onclick="checkAll(this);" /></th><th>Title</th><th>Contract Holder</th><th>Year</th><th>ILRs</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr><th>&nbsp;</td>';
				echo '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				echo '<td>' . $row['title'] . '</td>';
				echo '<td>' . $row['legal_name'] . '</td>';
				echo '<td align=center>' . $row['contract_year'] . '</td>';
				echo '<td align=center>' . $row['ilrs'] . '</td>';
				echo '</tr>';
					
				$qid = $row['id'];
				
			}
			echo '</tbody></table></div align="left">';
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>