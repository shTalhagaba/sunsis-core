<?php 

// need to sort this part to build into existing framework
// ------
require_once ("../lib/DAO.php");
require_once ("../lib/Entity.php");
require_once ("../lib/Date.php");
require_once ("../lib/User.php");
require_once ("../lib/Organisation.php");
require_once ("../lib/Location.php");
require_once ("../lib/ACL.php");
require_once ("../lib/Note.php");
// ------

ini_set('default_charset', 'iso-8859-1');
ini_set("soap.wsdl_cache_enabled", "0"); 

session_start();

$server = new SoapServer("wsdl/fwsolutions.wsdl"); 
$server->setClass('fw_accept_destiny');
$server->handle(); 

class fw_accept_destiny {

	private $db_name = '';
	private $db_user = '';
	private $db_password = '';
	private $db_host = '';
	private $db_port = '';

	private $connection = NULL;

	public function __construct() {

		$this->db_name = isset($_SERVER['PERSPECTIVE_DB_NAME'])?$_SERVER['PERSPECTIVE_DB_NAME']:'';
		$this->db_user = isset($_SERVER['PERSPECTIVE_DB_USER'])?$_SERVER['PERSPECTIVE_DB_USER']:ini_get('mysqli.default_user');
		$this->db_password = isset($_SERVER['PERSPECTIVE_DB_PASSWORD'])?$_SERVER['PERSPECTIVE_DB_PASSWORD']:ini_get('mysqli.default_pw');
		$this->db_host = isset($_SERVER['PERSPECTIVE_DB_HOST'])?$_SERVER['PERSPECTIVE_DB_HOST']:ini_get('mysqli.default_host');
		$this->db_port = isset($_SERVER['PERSPECTIVE_DB_PORT'])?$_SERVER['PERSPECTIVE_DB_PORT']:ini_get('mysqli.default_port');
		
		$this->connection = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name.";port=".$this->db_port, $this->db_user, $this->db_password);
	}

	/**
    * Accept the ilr xml
    */
	public function acceptILRXML($username, $password, $fileName, $xmlFile) { 

		if ( !$this->validateConnection($username, $password) ) {
			return 'Authentication Failed';
		}
		return $this->saveXML('ILR', $fileName, $xmlFile);
	}
	
	/**
    * Accept the ilr pdf
    */
	public function acceptILRPDF($username, $password, $fileName, $xmlFile) { 

		if ( !$this->validateConnection($username, $password) ) {
			return 'Authentication Failed';
		}
		return $this->savePDF('ILR', $fileName, $xmlFile);
	}
	
	/**
	* Accept the learner agreement xml
	*/
	public function acceptLearnerAgreementXML($username, $password, $fileName, $xmlFile) {

		if ( !$this->validateConnection($username, $password) ) {
            return 'Authentication Failed';
        }
		return $this->saveXML('Learner Agreement', $fileName, $xmlFile);
	} 

	/**
	* Accept the learner agreement PDF
	*/
	public function acceptLearnerAgreementPDF($username, $password, $fileName, $xmlFile) {

		if ( !$this->validateConnection($username, $password) ) {
            return 'Authentication Failed';
        }
		return $this->savePDF('Learner Agreement', $fileName, $xmlFile);
	} 	
	
	/**
	* Authentication of request
	* -------
	* 1. checks the system is enabled to allow soap requests
	* 2. check the login details provide are valid
	*/
	private function validateConnection($username, $password) {

		// ensure the system is setup to allow soap connections
		$configuration_sql = "SELECT value FROM configuration WHERE entity = 'module_soap'";
		if( DAO::getSingleValue($this->connection, $configuration_sql) !== 1 ) {
			return false;
		}

		// load the requestor login details
		$system_user = User::loadFromDatabase($this->connection, $username);

		// check 1. there is a user, 2. they have web access, 3. the password supplied matches users.
        if( ( !is_null($system_user) ) && ( $system_user->password != '' ) && ( $system_user->web_access == 1 ) && ( $system_user->password == $password ) ) {
        	$_SESSION['user'] = $system_user;
			return true;
        }
        return false;
	}

	/**
	* Saving of the xml file
	*/
	private function saveXML($type, $fileName, $xmlFile) {

		$return_message = '';

        if ( !preg_match('/^.*\.(xml)$/i', $fileName ) ) {
            $return_message = 'File type not recognized: '.$fileName;
        }

        try
        {
        	$pageDom = XML::loadXmlDom(mb_convert_encoding($xmlFile,'UTF-8'));
            $myFile = DATA_ROOT."/uploads/".$fileName;
            $fh = fopen($myFile, 'w');
            if ( !$fh ) {
                $return_message = 'Unable to store the '.$type.' Data';
            }
            fwrite($fh, $xmlFile);
            fclose($fh);
            $return_message = $type.'Data has been accepted and stored';       	
        }
        catch(XMLException $e)
        {
        	$return_message = $type.'Data has not been recognised';
        }
        
        /*
        $pageDom = new DOMDocument();
        if ( $pageDom->loadXML(utf8_encode($xmlFile)) ) {
            $myFile = DATA_ROOT."/uploads/".$fileName;
            $fh = fopen($myFile, 'w');
            if ( !$fh ) {
                $return_message = 'Unable to store the '.$type.' Data';
            }
            fwrite($fh, $xmlFile);
            fclose($fh);
            $return_message = $type.'Data has been accepted and stored';
        }
        else {
            $return_message = $type.'Data has not been recognised';
        }
		*/
        
  		$destiny_data = $pageDom->getElementsByTagName( "PenSurname" );
 		$destiny_pensurname = $destiny_data->item(0)->nodeValue;

		$destiny_data = $pageDom->getElementsByTagName( "Header" );
		$ilr_data = $pageDom->getElementsByTagName( "ILR" );

		$full_destiny_data = array();

		$this->get_xml_content($destiny_data->item(0));
		$full_destiny_data['Destiny'] = $this->xml_content;
		unset($this->xml_content);
		$this->get_xml_content($ilr_data->item(0));
		$full_destiny_data['ILR'] = $this->xml_content;

		$user = new User();

		// this is hardcoded to a destiny employer
		// need to adjust this to be dynamic
		$user->employer_id              = 682;
        $user->employer_location_id     = 106;

		$user->username 				= $full_destiny_data['ILR']['FirstName'].$full_destiny_data['ILR']['LastName'];
		$user->password 				= "password";
		$user->upn 						= $full_destiny_data['ILR']['UKProviderNumber'];
		$user->uln  					= $full_destiny_data['ILR']['UniqueLearnerNumber'];
		$user->surname 					= $full_destiny_data['ILR']['LastName'];
        $user->firstnames 				= $full_destiny_data['ILR']['FirstName'];
		$user->ni 						= $full_destiny_data['ILR']['NINumber'];
		$user->dob 						= $full_destiny_data['ILR']['DOB'];	
		$user->home_address_line_1  	= $full_destiny_data['ILR']['Address1'];
		$user->home_address_line_2 	    = $full_destiny_data['ILR']['Address2'];
		$user->home_address_line_3 		= $full_destiny_data['ILR']['PostTown'];
		$user->home_address_line_4 		= $full_destiny_data['ILR']['County'];
		$user->gender 					= $full_destiny_data['ILR']['Gender'];
		$user->ethnicity 				= $full_destiny_data['ILR']['EthnicGroup'];
		$user->home_postcode 			= $full_destiny_data['ILR']['PostCode2'];
		$user->home_telephone 			= $full_destiny_data['ILR']['Telephone'];	
		$user->l14 						= $full_destiny_data['ILR']['Disabled'];
		$user->l15 						= $full_destiny_data['ILR']['DisabilityType'];
		$user->l16 						= $full_destiny_data['ILR']['LearningDif'];
		$user->l34a 					= $full_destiny_data['ILR']['LearningSupportReason_1'];
		$user->l34b 					= $full_destiny_data['ILR']['LearningSupportReason_2'];
		$user->l34c 					= $full_destiny_data['ILR']['LearningSupportReason_3'];
        $user->l34d 					= $full_destiny_data['ILR']['LearningSupportReason_4'];
		$user->l35 						= $full_destiny_data['ILR']['PriorAttainLevel'];
		$user->l36 						= $full_destiny_data['ILR']['StatusDayPrior'];
		$user->l37 						= $full_destiny_data['ILR']['StatusFirstDay'];
		$user->l47 						= $full_destiny_data['ILR']['CurrentStatus'];
		$user->l48 						= $full_destiny_data['ILR']['StatusChangeDate'];
		$user->l39 						= $full_destiny_data['ILR']['Destination'];
		$user->record_status 			= 1;
		$user->type 					= 5;
		if ( $full_destiny_data['ILR']['Box_A'] == 1 ) {
			$user->save($this->connection, false);
			$return_message = 'User has been updated for '.$user->firstnames.' '.$user->surname;
		}
		else {
			$user->save($this->connection, true);
			$return_message = 'User has been imported for '.$user->firstnames.' '.$user->surname;
		}

		return $return_message;		
	}

	/**
	* Saving of the pdf file
	*/
	private function savePDF($type, $fileName, $xmlFile) {

		$return_message = '';

        if ( !preg_match('/^.*\.(pdf)$/i', $fileName ) ) {
            $return_message = 'File type not recognized: '.$fileName;
        }

        $myFile = DATA_ROOT."/uploads/".$fileName;
        $fh = fopen($myFile, 'w');
        if ( !$fh ) {
        	$return_message = 'Unable to store the '.$type.' Data';
        }
        fwrite($fh, $xmlFile);
        fclose($fh);
        $return_message = $type.'Data has been accepted and stored for import ['.$fileName.']';

        return $return_message;
	}

	/**
	*  convert the xml file to an array
	*/
	private function get_xml_content($node_item, $current_name_value="") {

    	$node_list=$node_item->childNodes;

        for( $j=0 ;  $j < $node_list->length; $j++ ) {
            $node = $node_list->item($j);
            $node_name = $node->nodeName;
            $node_value = $node->nodeValue;
            if( $node->nodeType == XML_TEXT_NODE ) {
                if ( preg_match("/[A-Z0-9]/i", $node_value) ) {
                    $this->xml_content[$current_name_value] = $node_value;
                }
            }
            else {
                $current_name_value = $node_name;
                $this->xml_content[$node_name] = '';
                $this->get_xml_content($node, $current_name_value);
            }
        }
    }

	private $xml_content = array();

}

?>
