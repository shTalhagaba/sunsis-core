<?php
class ajax_load_organisation_address implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$org_id = array_key_exists('org_id', $_REQUEST)?$_REQUEST['org_id']:'';
		$loc_id = array_key_exists('loc_id', $_REQUEST)?$_REQUEST['loc_id']:'';
		
		if( ($org_id == '') && ($loc_id == '') )
		{
			throw new Exception("At least one of 'org_id' or 'loc_id' arguments must be present in the querystring");
		}
		
		// If only the organisation ID has been specified, use the main location by default
		if(($org_id != '') && ($loc_id == ''))
		{
			$sql = "SELECT id FROM locations WHERE organisations_id=".addslashes((string)$org_id)." AND is_legal_address=1;";
			$loc_id = DAO::getSingleValue($link, $sql);
		}
		 
		$loc = Location::loadFromDatabase($link, $loc_id);
		$addr = new Address($loc);

		$addr_xml = $addr->toXML();
		$addr_xml = str_replace('</address>', '', $addr_xml);

		$sql = "SELECT contact_mobile, contact_telephone, contact_email, fax FROM locations WHERE id = " . $loc_id;
		$result = $link->query($sql);
		if($result)
		{
			while($row = $result->fetch())
			{
				$addr_xml .= "<telephone>" . $row['contact_telephone'] . "</telephone>";
				$addr_xml .= "<mobile>" . $row['contact_mobile'] . "</mobile>";
				$addr_xml .= "<fax>" . $row['fax'] . "</fax>";
				$addr_xml .= "<email>" . $row['contact_email'] . "</email>";
			}
		}
		$addr_xml .= "</address>";

		echo $addr_xml;

	}
}
?>