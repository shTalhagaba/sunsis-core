<?php
class edit_announcement implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$subaction = isset($_REQUEST['subaction'])?strtolower($_REQUEST['subaction']):'';
		$author = array(array('Sunesis Support Team','Sunesis Support Team',''), array('Sunesis Release Team','Sunesis Release Team',''),array('Sunesis Development Team','Sunesis Development Team',''), array('Sunesis Marketing Team','Sunesis Marketing Team',''));

		
		
		switch(strtolower($subaction))
		{
			case "getpartnerships":
				$orgs = $this->getPartnerships($link);
			header("Content-Type: application/json");
				echo Text::json_encode_latin1($orgs);
				return;	
			/*
			case "getschools":
				$orgs = $this->getSchoolsAndProviders($link, ORG_SCHOOL);
				header("Content-Type: application/json");
				echo Text::json_encode_latin1($orgs);
				return;	
				
			case "getproviders":
				$orgs = $this->getSchoolsAndProviders($link, ORG_PROVIDER);
				header("Content-Type: application/json");
				echo Text::json_encode_latin1($orgs);
				return;
				*/
			default:
			break;
		}

		if($id != '' && !is_numeric($id)){
			throw new Exception("Non-numeric querystring value 'id'");
		}

		if($id)
		{
			$vo = Announcement::loadFromDatabase($link, $id);
			if(is_null($vo))
			{
				throw new Exception("No announcement with id #$id found");
			}
			
		}
		else
		{
			$vo = new Announcement();
			
			$vo->organisations_id = $_SESSION['user']->employer_id;
			$vo->organisations_legal_name = $_SESSION['user']->org->legal_name;
			$vo->users_id = $_SESSION['user']->id;
			$vo->user_firstnames = $_SESSION['user']->firstnames;
			$vo->user_surname = $_SESSION['user']->surname;
			//$vo->all_partnerships = 1;
			$vo->author = 'Sunesis Support Team';
			//$vo->all_schools = 1;
			//$vo->all_providers = 1;
			$vo->publication_date=Date('d-m-Y'); 
				}

		if($id)
		{
			$js_cancel = "window.location.replace('do.php?_action=read_announcement&id=".$id."')";
		}
		else
		{
			$js_cancel = "window.history.back();";
		}
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_announcement", "Edit Announcements");
		
		require('tpl_edit_announcement.php');
	}
	
	/**
	 * Returns the possible partnerships the user can choose from
	 * @param PDO $link
	 */
	/*private function getPartnerships(PDO $link)
	{
		$user = $_SESSION['user'];
		$org = $_SESSION['org'];
		$dao = new OrganisationDAO($link);
		
		switch($org->org_type_id)
		{
			case null:
				$sql = "SELECT id, legal_name FROM organisations WHERE org_type_id=4 ORDER BY legal_name";
				break;
				
			case ORG_PARTNERSHIP:
				$sub_partnerships = $dao->getSubPartnerships($org->id);
				$parent_partnerships = $dao->getParentPartnerships($org->id);
				$partnerships = array_merge($sub_partnerships, $parent_partnerships);
				$partnerships[] = $org->id;
				$sql = "SELECT id, legal_name FROM organisations WHERE id IN(".DAO::mysqli_implode($partnerships).")  ORDER BY legal_name";
				break;

			// We probably won't let schools and providers hide announcements from parent partnerships, so this
			// section is probably going to remain unused.
			case ORG_SCHOOL:
			case ORG_PROVIDER:
				$ids = $dao->getParentPartnerships($org->id);
				$keys = DAO::mysqli_implode($ids);
				$sql = "SELECT id, legal_name FROM organisations WHERE id IN(".$keys.") ORDER BY legal_name";
				break;
				
			default:
				throw new Exception("Unknown organisation type");
		}
		
		return DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
	}
	
	
	private function getSchoolsAndProviders(mysqli $link, $org_type_id)
	{
		$user = $_SESSION['user'];
		$org = $_SESSION['org'];
		$dao = new OrganisationDAO($link);
		
		$partnerships = isset($_REQUEST['partnerships']) ? $_REQUEST['partnerships']:array();

		switch($org->org_type_id)
		{
			case ORG_SYSADMIN:
				if(count($partnerships))
				{
					$keys = DAO::mysqli_implode($partnerships);
					$sql = <<<HEREDOC
SELECT DISTINCT
	organisations.id,
	CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`
FROM
	partnership_org_lookup INNER JOIN organisations
		ON partnership_org_lookup.org_id = organisations.id
WHERE
	partnership_org_lookup.partnership_id IN ($keys)
	AND organisations.org_type_id = $org_type_id
ORDER BY
	legal_name
HEREDOC;
				}
				else
				{
					$sql = "SELECT id, CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name` FROM organisations WHERE org_type_id=$org_type_id ORDER BY legal_name";
				}
				break;
				
			case ORG_PARTNERSHIP:
				$sub_partnerships = $dao->getSubPartnerships($org->id);
				$parent_partnerships = $dao->getParentPartnerships($org->id);
				$possible_partnerships = array_merge($sub_partnerships, $parent_partnerships);
				$possible_partnerships[] = $org->id;
				if(count($partnerships))
				{
					// Partnership(s) specified -- whittle down possible partnerships to the selected partnerships
					$partnerships = array_intersect($possible_partnerships, $partnerships);
					if(!count($partnerships)){
						return array();
					}
				}
				else
				{
					$partnerships = $possible_partnerships;
				}
				$keys = DAO::mysqli_implode($partnerships);
				
				$sql = <<<HEREDOC
SELECT DISTINCT
	organisations.id,
	CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`
FROM
	partnership_org_lookup INNER JOIN organisations
		ON partnership_org_lookup.org_id = organisations.id
WHERE
	partnership_org_lookup.partnership_id IN ($keys)
	AND organisations.org_type_id = $org_type_id
ORDER BY
	legal_name
HEREDOC;
				break;
				
			case ORG_SCHOOL:
			case ORG_PROVIDER:
				$ids = $dao->getOrgSiblings($org->id);
				$ids[] = $org->id;
				$keys = DAO::mysqli_implode($ids);
				$sql = "SELECT id, CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name` FROM organisations WHERE id IN(".$keys.") ORDER BY legal_name";
				$sql = <<<HEREDOC
SELECT
	id,
	CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`
FROM
	organisations
WHERE
	id IN($keys)
	AND organisations.org_type_id = $org_type_id
ORDER BY
	legal_name			
HEREDOC;
				break;
				
			default:
				throw new Exception("Unknown organisation type");
		}
		
		return DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);		
	}
	
	*/
	private function renderJavaScript(PDO $link, Announcement $vo)
	{
		$user = $_SESSION['user'];
		$org = $_SESSION['user']->org;
		
		
		
		if($org->organisation_type)
		{
			echo "window.userOrgTypeId = ".$org->organisation_type.";\r\n";
		}
		else
		{
			echo "window.userOrgTypeId = null;\r\n";
		}
		
		if(!$vo->id){
		//	echo "window.selectedPartnerships = [];\r\n";
		//	echo "window.selectedSchools = [];\r\n";
		//	echo "window.selectedProviders = [];\r\n";
			return;
		}
/*
		$sql = <<<HEREDOC
		

SELECT
	organisations.id, organisations.legal_name
FROM
	announcement_acl INNER JOIN organisations
		ON announcement_acl.org_id = organisations.id
WHERE
	announcement_acl.announcements_id = {$vo->id}
	AND organisations.org_type_id = 4
ORDER BY
	organisations.legal_name
HEREDOC;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo "window.selectedPartnerships=".Text::json_encode_latin1($rows).";\r\n";

		$sql = <<<HEREDOC
SELECT
	organisations.id, CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`
FROM
	announcement_acl INNER JOIN organisations
		ON announcement_acl.org_id = organisations.id
WHERE
	announcement_acl.announcements_id = {$vo->id}
	AND organisations.org_type_id = 1
ORDER BY
	organisations.legal_name
HEREDOC;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo "window.selectedSchools=".Text::json_encode_latin1($rows).";\r\n";
		
		$sql = <<<HEREDOC
SELECT
	organisations.id, CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`
FROM
	announcement_acl INNER JOIN organisations
		ON announcement_acl.org_id = organisations.id
WHERE
	announcement_acl.announcements_id = {$vo->id}
	AND organisations.org_type_id = 2
ORDER BY
	organisations.legal_name
HEREDOC;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo "window.selectedProviders=".Text::json_encode_latin1($rows).";\r\n";
		
*/		
	}
	
	/**
	 * 
	 * @param string $fieldname
	 * @param string $value
	 * @param boolean $checked
	 */
	private function renderCheckbox($fieldname, $value, $checked)
	{
		echo '<input type="checkbox" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'"'.($checked?' checked="checked"':'')." />";
	}

/*	
	private function renderPartnershipSelectionOptions(Announcement $vo)
	{
		$label = $vo->organisations_id ? "all related":"all";
		$class = $vo->all_partnerships ? "AllOrganisationsLabelSelected":"AllOrganisationsLabelUnselected";
		echo '<input type="checkbox" name="all_partnerships" id="all_partnerships" value="1"'.($vo->all_partnerships?' checked="checked"':'')." />";
		echo '<span class="AllOrganisationsLabel">'.$label.'</span>';
		echo '<input type="button" id="btnSelectPartnerships" value="choose" onclick="selectPartnerships(); return false" '.($vo->all_partnerships?' disabled="disabled"':'').'/>';
	}
	
	private function renderSchoolSelectionOptions(Announcement $vo)
	{
		$label = $vo->organisations_id ? "all related":"all";
		$class = $vo->all_schools ? "AllOrganisationsLabelSelected":"AllOrganisationsLabelUnselected";
		echo '<input type="checkbox" name="all_schools" id="all_schools" value="1"'.($vo->all_schools?' checked="checked"':'')." />";
		echo '<span class="AllOrganisationsLabel">'.$label.'</span>';
		echo '<input type="button" id="btnSelectSchools" value="choose" onclick="selectSchools(); return false" '.($vo->all_schools?' disabled="disabled"':'').'/>';
	}
	
	private function renderProviderSelectionOptions(Announcement $vo)
	{
		$label = $vo->organisations_id ? "all related":"all";
		$class = $vo->all_providers ? "AllOrganisationsLabelSelected":"AllOrganisationsLabelUnselected";
		echo '<input type="checkbox" name="all_providers" id="all_providers" value="1"'.($vo->all_providers?' checked="checked"':'')." />";
		echo '<span class="AllOrganisationsLabel">'.$label.'</span>';
		echo '<input type="button" id="btnSelectProviders" value="choose" onclick="selectProviders(); return false" '.($vo->all_providers?' disabled="disabled"':'').'/>';
*/	
}
	
//}
?>