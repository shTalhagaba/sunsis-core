<?php
class edit_contract implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_contract&id=" . $id, "Add/ Edit Contract");
		
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$acl = ACL::loadFromDatabase($link, 'contract', $id); /* @var $acl ACL */
		
		
		if($id == '')
		{
			// New record
			$vo = new Contract();
			$vo->sync_learners_smart_assessor = 1;
			
			// Add author's organisation as a default reader
			if(!$_SESSION['user']->isAdmin() && ($_SESSION['user']->employer_id != '') )
			{
				$acl->setIdentities('read', '*/'.$_SESSION['user']->org_short_name);
			}
			
			// Add author as default writer
			if(!$_SESSION['user']->isAdmin())
			{
				$acl->setIdentities('write', $_SESSION['user']->getFullyQualifiedName());
			}
			
		}
		else
		{
			$vo = Contract::loadFromDatabase($link, $id);
		/*	$acl = ACL::loadFromDatabase($link, 'contract', $id);
			if(!$acl->isAuthorised($_SESSION['user'],'write'))
			{
				throw new UnauthorizedException();
			}
		*/	
		}
		
		$sql = "SELECT id, legal_name, null FROM organisations WHERE organisation_type LIKE '%4%' ORDER BY legal_name;";
		$providers = DAO::getResultSet($link, $sql);

        if(DB_NAME=='am_crackerjack' or DB_NAME=='am_baltic_demo')
        {
            $sql = "SELECT id, title, null FROM allocations";
            $allocations = DAO::getResultSet($link, $sql);
        }

		$sql = "SELECT DISTINCT id, contract_type, null FROM lookup_contract_types;";
		$funding_bodies = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, description, null FROM lookup_contract_locations;";
		$contract_locations = DAO::getResultSet($link, $sql);
		
        $sql = "SELECT DISTINCT id, contract_type, null FROM lookup_contract_types";
        $contract_types = DAO::getResultSet($link, $sql);
		
		$contract_year = Array();
		for($i = 2025; $i >= 2007; $i--)
		{
			$year = str_pad(($i + 1) - 2000, 2, '0', STR_PAD_LEFT );
			$contract_year[] = Array($i, $i . '-' . $year);
		}

		$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis201011;port=".DB_PORT, DB_USER, DB_PASSWORD);
				
		$L25_dropdown = "SELECT CONCAT(Code,Satellite_Office), LEFT(CONCAT(Code,Satellite_Office,' ', Name),50),null from LSC order by Code;";
		$L25_dropdown = DAO::getResultset($linklis,$L25_dropdown);

		$linklis='';
		
		$L01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown);
		
		$L46_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);

		$dropdown_funding_type = "SELECT id, description, null FROM lookup_funding_type";
		$funding_type_dropdown = DAO::getResultset($link, $dropdown_funding_type);
		
		$ContOrg_dropdown = DAO::getResultset($link,"SELECT distinct ContOrgCode, LEFT(CONCAT(ContOrgCode, ' ', ContOrgCode_Desc),50), null from lis201415.ilr_contorgcode order by ContOrgCode;", DAO::FETCH_NUM, "ILR2014 ContOrg dropdown");

        include('tpl_edit_contract.php');
	}
}
?>