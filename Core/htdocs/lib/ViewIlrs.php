<?php
class ViewIlrs extends View
{
	public static function getInstance($link, $id)
	{

		//$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$contract_id = substr($id,3);
		$submission = substr($id,0,3);
		
		$key = 'view_'.__CLASS__.$contract_id.$submission;

		if(!isset($_SESSION[$key]))
		{
			$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");
		if($contract_year<2012)
		{

			$sql = <<<HEREDOC
SELECT
	ilr.*, tr.uln, tr.surname as surname, contracts.contract_year,contracts.funding_body
FROM
	ilr 
	LEFT JOIN tr ON tr.id = ilr.tr_id
	LEFT JOIN contracts on contracts.id = ilr.contract_id 
where ilr.submission = '$submission' and ilr.contract_id ='$contract_id';
HEREDOC;
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	ilr.*, extractvalue(ilr.ilr,'/Learner/FamilyName') as surname, extractvalue(ilr.ilr,'/Learner/GivenNames') as firstnames, extractvalue(ilr.ilr,'/Learner/ULN') as uln, contracts.contract_year,contracts.funding_body
FROM
	ilr
	LEFT JOIN tr ON tr.id = ilr.tr_id
	LEFT JOIN contracts on contracts.id = ilr.contract_id
where ilr.submission = '$submission' and ilr.contract_id ='$contract_id';
HEREDOC;

		}
			
			$view = $_SESSION[$key] = new ViewIlrs();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Surname (asc), Ref (asc)', null, "ORDER BY substr(ilr,locate('<FamilyName>',ilr)+5,(locate('</FamilyName>',ilr)-locate('<FamilyName>',ilr)-5))"),
				1=>array(2, 'Surname (desc), Ref (desc)', null, "ORDER BY substr(ilr,locate('<FamilyName>',ilr)+5,(locate('</FamilyName>',ilr)-locate('<FamilyName>',ilr)-5)) DESC"));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'Valid', null, ' where is_valid=1'),
				2=>array(3, 'Invalid', null, ' where is_valid <> 1'));
			$f = new DropDownViewFilter('filter_valid', $options, 1, false);
			$f->setDescriptionFormat("Validity: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'Approved', null, ' where is_approved=1'),
				2=>array(3, 'Not Approved', null, ' where is_approved <> 1'));
			$f = new DropDownViewFilter('filter_approved', $options, 1, false);
			$f->setDescriptionFormat("Approved: %s");
			$view->addFilter($f);
		
			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'Active', null, ' where is_active=1'),
				2=>array(3, 'Not Active', null, ' where is_active <> 1'));
			$f = new DropDownViewFilter('filter_active', $options, 1, false);
			$f->setDescriptionFormat("Active: %s");
			$view->addFilter($f);
			
			$f = new TextboxViewFilter('l03', "WHERE ilr.l03 LIKE '%s'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{

		$sql = $this->getSQL();
		$st = $link->query($sql);
		if($st) 
		{
			$is_valid='';
			$is_active='';
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="table table-bordered table-hover">';
			echo '<thead><tr class="bg-gray"><th>&nbsp;</th> <th> Is Valid </th> <th> Is Active </th> <th>Learner Reference</th><th>ULN</th><th>First Name</th><th>Surname</th><th>Delivery Partner</th><th>Training Provider</th></tr></thead>';
		
			echo '<tbody>';
			
			while($row = $st->fetch())
			{
				$year = (string)str_pad(substr($row['contract_year'],2,2),2,"0",0) . (string)str_pad(substr($row['contract_year'],2,2)+1,2,"0",0);

				if($row['contract_year']>=2008)
					if($row['funding_body']==1)
						echo HTML::viewrow_opening_tag('do.php?_action=edit_lr_ilr'.$row['contract_year'].'&submission='.rawurlencode($row['submission']).'&contract_id='.$row['contract_id'].'&L03='.$row['L03'].'&tr_id='.$row['tr_id']);
					else
						echo HTML::viewrow_opening_tag('do.php?_action=edit_ilr'.$row['contract_year'].'&submission='.rawurlencode($row['submission']).'&contract_id='.$row['contract_id'].'&L03='.$row['L03'].'&tr_id='.$row['tr_id']);

				echo '<td><img src="/images/rosette.gif" /></td>';
				echo $row['is_valid'] == '1' ? '<td align="center"><i class="fa fa-check"></i> </td>' : '<td align="center"><i class="fa fa-times"></i> </td>';
				echo $row['is_active'] == '1' ? '<td align="center"><i class="fa fa-check"></i> </td>' : '<td align="center"><i class="fa fa-times"></i> </td>';

				echo '<td align="left">' . HTML::cell($row['L03']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['uln']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				$employer_id = '';
				if(isset($row['tr_id']) && $row['tr_id'] != '')
					$employer_id = DAO::getSingleValue($link, "SELECT employer_id FROM tr WHERE id = " . $row['tr_id']);
				if($employer_id != '')
					echo '<td align="left">' . HTML::cell(DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $employer_id)) . "</td>";
				else
					echo '<td align="left"></td>';
				$provider_id = '';
				if(isset($row['tr_id']) && $row['tr_id'] != '')
					$provider_id = DAO::getSingleValue($link, "SELECT provider_id FROM tr WHERE id = " . $row['tr_id']);
				if($provider_id != '')
					echo '<td align="left">' . HTML::cell(DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $provider_id)) . "</td>";
				else
					echo '<td align="left"></td>';


				echo '</tr>';
				$_REQUEST['L01'] = $row['L01'];
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>