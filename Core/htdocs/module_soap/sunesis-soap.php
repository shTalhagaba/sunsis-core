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

// amendments: change this to refer to either default ( sunesis.wsdl ) or system specific wsdl....
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
	* Accept generic destiny ilr
	*/
	public function acceptDestinyILR($username, $password, $fileName, $xmlFile, $pdfFileName, $pdfFile) {

		if ( !$this->validateConnection($username, $password) ) {
            return '7. Authentication Failed';
        }

	// basic file verifications
        $this->files['XML'] = $fileName;
        $this->files['PDF'] = $pdfFileName;
        $this->validateFiles();
        if (( $this->files['XML'] != $fileName )||( $this->files['PDF'] != $pdfFileName ) ) {
            return 'File types not recognized: [XML: '.$fileName.'] [PDF: '.$pdfFileName.']';
        }

        $xml_response = $this->saveDestinyXML('Destiny ILR', $fileName, $xmlFile);
        $pdf_response = $this->savePDF('Destiny ILR', $pdfFileName, $pdfFile);

        return "[XML]:".$xml_response." [PDF]:".$pdf_response;

	}

	/**
    	* Accept the fwsolutions ilr xml
    	*/
	public function acceptILRXML($username, $password, $fileName, $xmlFile) { 

		if ( !$this->validateConnection($username, $password) ) {
			return '1. Authentication Failed';
		}

		// basic file verifications
		$this->files['XML'] = $fileName;
		$this->validateFiles();	
		if ( $this->files['XML'] != $fileName ) {
			return 'File type not recognized: '.$fileName;
		} 
		return $this->saveILRXML('ILR', $fileName, $xmlFile);
	}

	/**
    	* Accept the fwsolutions ilr pdf
    	*/
	public function acceptILRPDF($username, $password, $fileName, $pdfFile) { 

		if ( !$this->validateConnection($username, $password) ) {
			return '2. Authentication Failed';
		}
		 // basic file verifications
        $this->files['PDF'] = $fileName;
        $this->validateFiles();
        if ( $this->files['PDF'] != $fileName ) {
            return 'File type not recognized: '.$fileName;
        }
		return $this->savePDF('ILR', $fileName, $pdfFile);
	}

	/**
	* Accept both fwsolutions ilr pdf & xml
	*/
	public function acceptILR($username, $password, $fileName, $xmlFile, $pdfFileName, $pdfFile) {

		if ( !$this->validateConnection($username, $password) ) {
            return '3. Authentication Failed ['.$username.']['.$password.']';
        }
		 // basic file verifications
        $this->files['XML'] = $fileName;
		$this->files['PDF'] = $pdfFileName;
        $this->validateFiles();
        if (( $this->files['XML'] != $fileName )||( $this->files['PDF'] != $pdfFileName ) ) {
            return 'File types not recognized: [XML: '.$fileName.'] [PDF: '.$pdfFileName.']';
        }
		$xml_response = $this->saveILRXML('ILR', $fileName, $xmlFile);
		
		// get the NI number to associate the PDF file to the learner
		preg_match('/^\[(.*)\](.*)/', $xml_response, $xml_ni);

		$learnerni = '';
		if ( isset($xml_ni[1]) && preg_match('/^[A-Za-z]{2}[0-9]{6}[A-Za-z]{1}$/', $xml_ni[1]) ) {
			$learnerni = $xml_ni[1];
		}
		// ---
			
		$pdf_response = $this->savePDF('ILR', $pdfFileName, $pdfFile, $learnerni);

		return "[XML]:".$xml_response." [PDF]:".$pdf_response;	
	}
	
	/**
	* Accept the fwsolutions learner agreement xml
	*/
	public function acceptLearnerAgreementXML($username, $password, $fileName, $xmlFile) {

		if ( !$this->validateConnection($username, $password) ) {
            		return '4. Authentication Failed';
        	}
		// basic file verifications
        	$this->files['XML'] = $fileName;
        	$this->validateFiles();
        	if ( $this->files['XML'] != $fileName ) {
            		return 'File type not recognized: '.$fileName;
        	}
		return $this->saveXML('Learner Agreement', $fileName, $xmlFile);
	} 

	/**
	* Accept the fwsolutions learner agreement PDF
	*/
	public function acceptLearnerAgreementPDF($username, $password, $fileName, $xmlFile) {

		if ( !$this->validateConnection($username, $password) ) {
            return '5. Authentication Failed';
        }
		// basic file verifications
        $this->files['PDF'] = $fileName;
        $this->validateFiles();
        if ( $this->files['PDF'] != $fileName ) {
            return 'File type not recognized: '.$fileName;
        }
		return $this->savePDF('Learner Agreement', $fileName, $xmlFile);
	} 	

	/**
    * Accept both fwsolutions learner agreement pdf & xml
    */
    public function acceptLearnerAgreement($username, $password, $fileName, $xmlFile, $pdfFileName, $pdfFile) {
        if ( !$this->validateConnection($username, $password) ) {
            return '6. Authentication Failed';
        }
		 // basic file verifications
        $this->files['XML'] = $fileName;
        $this->files['PDF'] = $pdfFileName;
        $this->validateFiles();
        if (( $this->files['XML'] != $fileName )||( $this->files['PDF'] != $pdfFileName ) ) {
            return 'File types not recognized: [XML: '.$fileName.'] [PDF: '.$pdfFileName.']';
        }

        $xml_response = $this->saveXML('Learner Agreement', $fileName, $xmlFile);

		// get the NI number to associate the PDF file to the learner
		preg_match('/^\[(.*)\](.*)/', $xml_response, $xml_ni);

		$learnerni = '';
		if ( isset($xml_ni[1]) && preg_match('/^[A-Za-z]{2}[0-9]{6}[A-Za-z]{1}$/', $xml_ni[1]) ) {
			$learnerni = $xml_ni[1];
		}
		
        $pdf_response = $this->savePDF('Learner Agreement', $pdfFileName, $pdfFile, $learnerni);

        return "[XML]:".$xml_response." [PDF]:".$pdf_response;
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
		if( (int)(DAO::getSingleValue($this->connection, $configuration_sql)) !== 1 ) {
			return false;
		}

		// load the requestor login details
		$system_user = User::loadFromDatabase($this->connection, $username);

		$this->employer_id = $system_user->employer_id;
		$this->employer_location = $system_user->employer_location_id;

		// check 1. there is a user, 2. they have web access, 3. the password supplied matches users.
        if( ( !is_null($system_user) ) && ( $system_user->password != '' ) && ( $system_user->web_access == 1 ) && ( $system_user->password == $password ) ) {
        	$_SESSION['user'] = $system_user;
			return true;
        }
        return false;
	}

	/**
	* Saving of the Learner Agreement XML file	
	*/
	private function saveXML($type, $fileName, $xmlFile) {
		$return_message = '';

       	if ( !preg_match('/^.*\.(xml)$/i', $fileName ) ) {
       		$return_message = 'File type not recognized: '.$fileName;
       	}
       	
       	/*
        $pageDom = new DomDocument();
        if ( $pageDom->loadXML(utf8_encode($xmlFile)) ) {
        	$myFile = DATA_ROOT."/uploads/".$this->db_name."/".$fileName;
         	$fh = fopen($myFile, 'w');
         	if ( !$fh ) {
           		$return_message = 'Unable to store the '.$type.' Data';
         	}
        	fwrite($fh, $xmlFile);
        	fclose($fh);
        	$return_message = $type.' XML Data has been accepted and stored';
       	}
       	else {
       		$return_message = $type.' ++ Data has not been recognised';
       	}
       	*/
       	
       	try
       	{
       		$pageDom = XML::loadXmlDom(mb_convert_encoding($xmlFile,'UTF-8'));
      	 	$myFile = DATA_ROOT."/uploads/".$this->db_name."/".$fileName;
         	$fh = fopen($myFile, 'w');
         	if ( !$fh ) {
           		$return_message = 'Unable to store the '.$type.' Data';
         	}
        	fwrite($fh, $xmlFile);
        	fclose($fh);
        	$return_message = $type.' XML Data has been accepted and stored';       		
       	}
       	catch(XMLException $e)
       	{
       		$return_message = $type.' ++ Data has not been recognised';
       	}
       	
		$destiny_data = $pageDom->getElementsByTagName( "Surname" );
 		$destiny_pensurname = $destiny_data->item(0)->nodeValue;

		$destiny_data = $pageDom->getElementsByTagName( "Header" );
		$ilr_data = $pageDom->getElementsByTagName( "LearnerAgreement" );

		$full_destiny_data = array();

		$this->get_xml_content($destiny_data->item(0));
		$full_destiny_data['Destiny'] = $this->xml_content;
		unset($this->xml_content);
		$this->get_xml_content($ilr_data->item(0));
		$full_destiny_data['LearnerAgreement'] = $this->xml_content;

		$username_sql = "SELECT username FROM users WHERE ni = '".$full_destiny_data['LearnerAgreement']['NINumber']."'";
		$existing_username = DAO::getSingleValue($this->connection, $username_sql);
                if( $existing_username != "" ) {
			$user = User::loadFromDatabase($this->connection, $existing_username);	
                }
		else {
			$user = new User();	
		}	

		// ---
		// this is hardcoded to a destiny employer
		// need to adjust this to be dynamic to the 
		// company passed in via the learner agreement
		// - using the destiny pen as a default
		// ---
		$user->employer_id = $this->employer_id;
        	$user->employer_location_id = $this->employer_location;

		$employer_setup_feedback = ' - no employer match - using destiny - ';

		// ---
		// EMPLOYER ORGANISATION RETREIVAL
		$companyname = $full_destiny_data['LearnerAgreement']['CompanyName'];
		$locationname = $full_destiny_data['LearnerAgreement']['SiteName'];
		
		$employer_sql = "SELECT organisations.id as org_id, locations.id as loc_id, locations.full_name, locations.is_legal_address FROM organisations, locations WHERE LCASE(organisations.legal_name) = LCASE('".$companyname."') AND organisations.id = locations.organisations_id AND organisation_type = 2";

		$employer_details = DAO::getResultset($this->connection, $employer_sql, DAO::FETCH_ASSOC);

		foreach( $employer_details as $org_array_id => $org_details ) {
			if ( isset($org_details['org_id']) && isset($org_details['loc_id']) ) {
				// hit pay dirt - matched the org & its location 
				// from the destiny form
				if ( strtolower($org_details['full_name']) == strtolower($locationname) ) {
					$user->employer_id = $org_details['org_id'];
            				$user->employer_location_id = $org_details['loc_id'];		
					$employer_setup_feedback = ' - using LA employer and location - ';
					continue;
				}
				
				// set the main legal address as the location in lieu of any others
				if ( $org_details['is_legal_address'] == 1 ) {
					$user->employer_id = $org_details['org_id'];
                    			$user->employer_location_id = $org_details['loc_id'];
					$employer_setup_feedback = ' - using LA employer and default main location - ';
				}
			}
		} 
		// ---

		$user->username	= strtolower(substr($full_destiny_data['LearnerAgreement']['FirstName'],0,1)).$full_destiny_data['LearnerAgreement']['Surname'];
		// $user->upn = $full_destiny_data['LearnerAgreement']['UKProviderNumber'];
		// $user->uln = $full_destiny_data['LearnerAgreement']['UniqueLearnerNumber'];
		$user->surname 					= $full_destiny_data['LearnerAgreement']['Surname'];
        	$user->firstnames 				= $full_destiny_data['LearnerAgreement']['FirstName'];
		$user->ni 						= $full_destiny_data['LearnerAgreement']['NINumber'];
		$user->dob 						= $full_destiny_data['LearnerAgreement']['DOB'];	

		// $user->home_saon_description 	= $full_destiny_data['LearnerAgreement']['HomeAddress_1'];

		// $user->home_street_description  = $full_destiny_data['LearnerAgreement']['HomeAddress_1'];
		// $user->home_locality 			= $full_destiny_data['LearnerAgreement']['HomeAddress_2'];

		// $user->home_town 				= $full_destiny_data['LearnerAgreement']['HomeAddress_3'];
		// $user->home_county 				= $full_destiny_data['LearnerAgreement']['HomeAddress_4'];

		// do some guessing on gender based on form values
		$user->gender = 'U';
		if( $full_destiny_data['LearnerAgreement']['Title'] == 'Mr' ) {
			 $user->gender = 'M';
		}
		elseif( $full_destiny_data['LearnerAgreement']['Title'] == 'Mrs' 
				|| $full_destiny_data['LearnerAgreement']['Title'] == 'Miss' 
				|| $full_destiny_data['LearnerAgreement']['Title'] == 'Ms' ) {
			$user->gender = 'F';
		}
		

		// $user->ethnicity 				= $full_destiny_data['LearnerAgreement']['EthnicGroup'];

		$user->home_postcode 			= $full_destiny_data['LearnerAgreement']['HomePostCode'];
		$user->home_telephone 			= $full_destiny_data['LearnerAgreement']['HomeTelNumber'];	

		// $user->l14 						= $full_destiny_data['LearnerAgreement']['Disabled'];
		// $user->l15 						= $full_destiny_data['LearnerAgreement']['DisabilityType'];
		// $user->l16 						= $full_destiny_data['LearnerAgreement']['LearningDif'];
		// $user->l34a 					= $full_destiny_data['LearnerAgreement']['LearningSupportReason_1'];
		// $user->l34b 					= $full_destiny_data['LearnerAgreement']['LearningSupportReason_2'];
		// $user->l34c 					= $full_destiny_data['LearnerAgreement']['LearningSupportReason_3'];
        // $user->l34d 					= $full_destiny_data['LearnerAgreement']['LearningSupportReason_4'];
		// $user->l35 						= $full_destiny_data['LearnerAgreement']['PriorAttainLevel'];
		// $user->l36 						= $full_destiny_data['LearnerAgreement']['StatusDayPrior'];
		// $user->l37 						= $full_destiny_data['LearnerAgreement']['StatusFirstDay'];
		// $user->l47 						= $full_destiny_data['LearnerAgreement']['CurrentStatus'];
		// $user->l48 						= $full_destiny_data['LearnerAgreement']['StatusChangeDate'];
		// $user->l39 						= $full_destiny_data['LearnerAgreement']['Destination'];

		// $user->record_status 			= 1;
		// $user->type 					= 5;

		// validation checking on user
		$username_sql = "SELECT count(username) FROM users WHERE ni = '".$user->ni."'";
        	if( DAO::getSingleValue($this->connection, $username_sql) == 1 ) {
			$user->save($this->connection, false);	
			$return_message = '['.$user->ni.'] User '.$user->firstnames.' '.$user->surname.' is already in the system and has been updated';	
     		} 
		else {	
			$username_increment = 1;
			$original_username = $user->username;
			while( DAO::getSingleValue($this->connection, "SELECT count(username) FROM users WHERE username = '".$user->username."'") >= 1 ) {
				$user->username = $original_username."_".$username_increment;	
				$username_increment++;
			}
			$user->password = "password";
			$user->save($this->connection, true);
			$return_message = '['.$user->ni.'] User has been imported for '.$user->firstnames.' '.$user->surname.' '.$employer_setup_feedback;
		}

		return $return_message;
	}		

	/**
	* Saving of the ilr xml file
	*/
	private function saveILRXML($type, $fileName, $xmlFile)
	{
		$return_message = '';

        if ( !preg_match('/^.*\.(xml)$/i', $fileName ) ) {
     		$return_message = 'File type not recognized: '.$fileName;
			return $return_message;
        }

        /*
        $pageDom = new DomDocument();
        if ( $pageDom->loadXML(utf8_encode($xmlFile)) ) {
		// re : updated to write to right location
        	$myFile = DATA_ROOT."/uploads/".$this->db_name."/".$fileName;
            	$fh = fopen($myFile, 'w');
            	if ( !$fh ) {
                	$return_message = 'Unable to store the '.$type.' Data';
			return $return_message;
            	}
            	fwrite($fh, $xmlFile);
            	fclose($fh);
            	$return_message = $type.' XML Data has been accepted and stored';
        }
        else {
            	$return_message = $type.' Data has not been recognised as XML';
		return $return_message;
        }
        */
        
        try
        {
        	$pageDom = XML::loadXmlDom(mb_convert_encoding($xmlFile,'UTF-8'));
       		$myFile = DATA_ROOT."/uploads/".$this->db_name."/".$fileName;
            $fh = fopen($myFile, 'w');
            if ( !$fh ) {
               	$return_message = 'Unable to store the '.$type.' Data';
				return $return_message;
            }
           	fwrite($fh, $xmlFile);
            fclose($fh);
            $return_message = $type.' XML Data has been accepted and stored';        	
        }
        catch(XMLException $e)
        {
            $return_message = $type.' Data has not been recognised as XML';
			return $return_message;        	
        }

	  	$destiny_data = $pageDom->getElementsByTagName("PenSurname");
	 	$destiny_pensurname = $destiny_data->item(0)->nodeValue;
		$destiny_data = $pageDom->getElementsByTagName("Header");
	
	
		$ilr_data = '';
		$ilr_data = $pageDom->getElementsByTagName("ILR");
	
		// re - 2011/12 
		// if ( empty($ilr_data) ) {
			$ilr_data = $pageDom->getElementsByTagName("ILRv2");
		// }
	
		$full_destiny_data = array();
	
		$this->get_xml_content($destiny_data->item(0));
		$full_destiny_data['Destiny'] = $this->xml_content;
		unset($this->xml_content);
		$this->get_xml_content($ilr_data->item(0));
		$full_destiny_data['ILR'] = $this->xml_content;

		// rudimentary check that this is an ILR xml file.
		if ( !isset($full_destiny_data['ILR']['Box_A']) ) {
			$return_message = ' ILR XML Data has not been recognised.';
			return $return_message;
		}		

		$username_sql = "SELECT username FROM users WHERE ni = '".$full_destiny_data['ILR']['NINumber']."'";
                $existing_username = DAO::getSingleValue($this->connection, $username_sql);
                if( $existing_username != "" ) {
                        $user = User::loadFromDatabase($this->connection, $existing_username);
                }
                else {
                        $user = new User();
                }
        	// ---
		// this is hardcoded to a destiny employer
		// need to adjust this to be dynamic to the 
		// company passed in via the learner agreement
		// - using the destiny pen as a default
		// ---
		$user->employer_id = $this->employer_id;
        	$user->employer_location_id = $this->employer_location;

		$employer_setup_feedback = ' - no employer match - using destiny - ';

		// ---
		// EMPLOYER ORGANISATION RETREIVAL
		$company_ukprn = $full_destiny_data['ILR']['UKProviderNumber'];
		$company_upin = $full_destiny_data['ILR']['UPIN'];
		
		$employer_sql = "SELECT organisations.id as org_id, locations.id as loc_id, locations.full_name, locations.is_legal_address FROM organisations, locations WHERE organisations.ukprn = '".$company_ukprn."' AND organisations.upin = '".$company_upin."' AND organisations.id = locations.organisations_id";

		$employer_details = DAO::getResultset($this->connection, $employer_sql, DAO::FETCH_ASSOC);

		foreach( $employer_details as $org_array_id => $org_details ) {
			if ( isset($org_details['org_id']) && isset($org_details['loc_id']) ) {			
				// set the main legal address as the location in lieu of any others
				if ( $org_details['is_legal_address'] == 1 ) {
					$user->employer_id = $org_details['org_id'];
                    $user->employer_location_id = $org_details['loc_id'];
					$employer_setup_feedback = ' - using ILR employer and default main location - ';
				}
			}
		} 
		// ---
        
		$user->username = strtolower(substr($full_destiny_data['ILR']['FirstName'],0,1).$full_destiny_data['ILR']['LastName']);
		$user->upn = $full_destiny_data['ILR']['UKProviderNumber'];

		// ?? doubling up of uln data
		// ---
		$user->uln = $full_destiny_data['ILR']['UniqueLearnerNumber'];
		$user->l45 = $full_destiny_data['ILR']['UniqueLearnerNumber'];
		// --- 
		$user->surname = $full_destiny_data['ILR']['LastName'];
        	$user->firstnames = $full_destiny_data['ILR']['FirstName'];
		$user->ni = $full_destiny_data['ILR']['NINumber'];
		$user->dob = $full_destiny_data['ILR']['DOB'];	

		// $user->home_saon_description 	= $full_destiny_data['ILR']['Address1'];

		$user->home_address_line_1  = $full_destiny_data['ILR']['Address1'];
        $user->home_address_line_2 = $full_destiny_data['ILR']['Address2'];
		$user->home_address_line_3 = $full_destiny_data['ILR']['PostTown'];
		$user->home_address_line_4 = $full_destiny_data['ILR']['County'];
		$user->gender = $full_destiny_data['ILR']['Gender'];
		$user->ethnicity = $full_destiny_data['ILR']['EthnicGroup'];
		$user->home_postcode = $full_destiny_data['ILR']['PostCode'];
		$user->l17 = $full_destiny_data['ILR']['PostCode2'];
		$user->home_telephone = $full_destiny_data['ILR']['Telephone'];	
		$user->home_email = $full_destiny_data['ILR']['EMailAddress'];
		// ---
		// disabled is a Y/N flag in the new ILR
		$user->l14 = 9;
		if ( $full_destiny_data['ILR']['Disabled'] == 'Y' ) {
			$user->l14 = 1;
		}
		else if ( $full_destiny_data['ILR']['Disabled'] == 'N' ) {
			$user->l14 = 2;
		}	
		$user->l15 = $full_destiny_data['ILR']['LLDDHealth_1'];
		$user->l16 = $full_destiny_data['ILR']['LLDDHealth_2'];

		$user->l34a = $full_destiny_data['ILR']['LearnerFAM_1'];
		$user->l34b = $full_destiny_data['ILR']['LearnerFAM_2'];
		$user->l34c = $full_destiny_data['ILR']['LearnerFAM_3'];
        	$user->l34d = $full_destiny_data['ILR']['LearnerFAM_4'];
		$user->l40a = $full_destiny_data['ILR']['LearnerFAM_5'];
        	$user->l40b = $full_destiny_data['ILR']['LearnerFAM_6'];
		$user->l35 = $full_destiny_data['ILR']['PriorAttainment'];
		$user->l36 = $full_destiny_data['ILR']['StatusDayPrior'];
		$user->l37 = $full_destiny_data['ILR']['StatusFirstDay'];
		$user->l47 = $full_destiny_data['ILR']['CurrentStatus'];
		$user->l48 = $full_destiny_data['ILR']['StatusChangeDate'];
		$user->l39 = $full_destiny_data['ILR']['Destination'];
		$user->record_status = 1;
		$user->type = 5;

		// re - country of domicile
		// going to do a lookup here
		if ( $full_destiny_data['ILR']['CountryOfDomicile'] != '' ) {
			$domicile_sql = "SELECT Domicile_Code FROM lis201112.ilr_l24_domiciles WHERE Domicile_Desc = '".$full_destiny_data['ILR']['CountryOfDomicile']."'";
			$domicile_code = DAO::getSingleValue($this->connection, $domicile_sql);
			if ( '' != $domicile_code ) {
				$user->l24 = $domicile_code;
			}
		}

		$weasel = '';
        foreach ( $full_destiny_data['ILR'] as $uName => $uValue ) {
            $weasel .= '-- ['.$uName.'] = ['.$uValue."] --<br/>";
        }
		foreach ( $user as $uName => $uValue ) {
			$weasel .= '['.$uName.'] = ['.$uValue."]<br/>";
		}

		if ( $full_destiny_data['ILR']['Box_A'] == 1 ) {
			$user->save($this->connection, false);
			$return_message = '['.$user->ni.'] User has been updated for '.$user->firstnames.' '.$user->surname.' '.$employer_setup_feedback;
		}
		else {
			// validation checking on user
			$username_sql = "SELECT count(username) FROM users WHERE ni = '".$user->ni."'";
           		if( DAO::getSingleValue($this->connection, $username_sql) >= 1 ) {
				$user->save($this->connection, false);
				$return_message = '['.$user->ni.'] User '.$user->firstnames.' '.$user->surname.' is already in the system ( NI number matched ) and has been updated!';	
           		}
			else {	
				$username_increment = 1;
				$original_username = $user->username;
				while( DAO::getSingleValue($this->connection, "SELECT count(username) FROM users WHERE username = '".$user->username."'") >= 1 ) {
					$user->username = $original_username."_".$username_increment;	
					$username_increment++;
				}
				$user->password = "password";

				$user->save($this->connection, true);
				$return_message = '['.$user->ni.'] User has been imported for '.$user->firstnames.' '.$user->surname.' '.$employer_setup_feedback;
			}
		}

		$myFile = DATA_ROOT."/uploads/".$this->db_name."/.".$user->ni.".dat";
                $fh = fopen($myFile, 'w');
                if ( !$fh ) {
                        $return_message .= ' Unable to store the ILR Addtional Data';
                        return $return_message;
                }
                fwrite($fh, '<L17>'.$full_destiny_data['ILR']['PostCode2'].'</L17>');
		$contact_type = '';
		if ( $full_destiny_data['ILR']['ByPost'] == 1 ) {
			$contact_type = '+';
		}
		else { $contact_type = '-'; }	
		if ( $full_destiny_data['ILR']['ByEmail'] == 1 ) {
                        $contact_type .= '+';
                }
                else { $contact_type .= '-'; }
		if ( $full_destiny_data['ILR']['ByPhone'] == 1 ) {
                        $contact_type .= '+';
                }
                else { $contact_type .= '-'; } 

		switch($contact_type)
                {
                        case '+++':
				fwrite($fh, '<L52>7</L52>');
                                break;
			case '+--':
                                fwrite($fh, '<L52>1</L52>');
                                break;
			case '-+-':
                                fwrite($fh, '<L52>2</L52>');
                                break;
			case '--+':
                                fwrite($fh, '<L52>3</L52>');
                                break;
			case '++-':
                                fwrite($fh, '<L52>4</L52>');
                                break;
			case '+-+':
                                fwrite($fh, '<L52>5</L52>');
                                break;
			case '-++':
                                fwrite($fh, '<L52>6</L52>');
                                break;
                        default:
                                fwrite($fh, '<L52>9</L52>');
                                break;
                }	

		fwrite($fh, '<L27a>'.$full_destiny_data['ILR']['Courses'].'</L27a>');
		fwrite($fh, '<L27b>'.$full_destiny_data['ILR']['Surveys'].'</L27b>');
                fclose($fh);	

		return $return_message;		
	}

	/**
	* import of standard destiny xml 
	*/
	private function saveDestinyXML($type, $fileName, $xmlFile)
	{
		//$pageDom = new DomDocument();
		//$pageDom->loadXML($xmlFile);
		$pageDom = XML::loadXmlDom($xmlFile);

		$dob_array = array();
				
		$e = $pageDom->getElementsByTagName('Field');
		foreach( $e as $node ) {
			if( $node->getElementsByTagName('Name')->item(0)->nodeValue=="L09" ) {
				$surname = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
			}
			if( $node->getElementsByTagName('Name')->item(0)->nodeValue=="L10" ) {
				$firstname = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
			}
			if( $node->getElementsByTagName('Name')->item(0)->nodeValue=="L13" ) {
				$gender = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
			}
			if( $node->getElementsByTagName('Name')->item(0)->nodeValue=="L11DD" ) {
				$dob_array['DD'] = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
			}	
			if( $node->getElementsByTagName('Name')->item(0)->nodeValue=="L11MM" ) {
				$dob_array['MM'] = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
			}
			if( $node->getElementsByTagName('Name')->item(0)->nodeValue=="L11YY" ) {
				$dob_array['YY'] = '19'.$node->getElementsByTagName('valStr')->item(0)->nodeValue;
			}
		}

		$user->dob = '0000-00-00';

		$dob = $dob_array['YY'].'-'.$dob_array['MM'].'-'.$dob_array['DD'];

		if ( preg_match('/(\d+){4}-(\d+){2}-(\d+){2}/', $dob) ) {
			$user->dob = $dob;
		}

		$user = new User();

		$user->dob = '0000-00-00';

        $dob = $dob_array['YY'].'-'.$dob_array['MM'].'-'.$dob_array['DD'];

        if ( preg_match('/(\d+){4}-(\d+){2}-(\d+){2}/', $dob) ) {
            $user->dob = $dob;
        }

		$user->username = $firstname.$surname;
		$user->password = "password";
		$user->record_status = 1;
		$user->type = 5;
		$user->gender = "U";
		$user->surname = $surname;
		$user->firstnames = $firstname;
		$user->employer_id = $this->employer_id;
		$user->employer_location_id = $this->employer_location;

		// validation checking on user
		// - make this generic
        $username_sql = "SELECT count(username) FROM users WHERE ni = '".$user->ni."'";
        if( DAO::getSingleValue($this->connection, $username_sql) >= 1 ) {
        	$return_message = 'User '.$firstname.' '.$surname.' is already in the system ( NI number duplication )';
        }
        else {
        	$username_increment = 1;
           	$original_username = $user->username;
           	while( DAO::getSingleValue($this->connection, "SELECT count(username) FROM users WHERE username = '".$user->username."'") >= 1 ) {
           		$user->username = $original_username."_".$username_increment;
               	$username_increment++;
            }
            $user->save($this->connection, true);
            $return_message = 'User has been imported for '.$firstname.' '.$surname;
        }
		return $return_message;
	
	}

	/**
	* Saving of the pdf file
	*/
	private function savePDF($type, $fileName, $xmlFile, $userni = '' ) {

		$return_message = '';

        	if ( !preg_match('/^.*\.(pdf)$/i', $fileName ) ) {
            		$return_message = 'File type not recognized: '.$fileName;
        	}
        
        // ---
        // re - rename the file to allow 
        //      standardisation for the 
        //	    subsequent presentation
	if ( preg_match('/.*-ILRV2-.*\.(pdf)$/i', $fileName ) ) {
            // $fileName  = 'destiny-ILRV2.pdf';
        }
        elseif ( preg_match('/.*-LA-.*\.(pdf)$/i', $fileName ) ) {
        	$fileName  = 'destiny-LA.pdf';	
        }
        // ---

        $myFile = DATA_ROOT."/uploads/".$fileName;

	if ( '' != $userni && preg_match('/^[A-Za-z]{2}[0-9]{6}[A-Za-z]{1}$/', $userni) ) {
 		$this_username = DAO::getSingleValue($this->connection, "SELECT username FROM users WHERE ni = '".$userni."'");
		if ( preg_match('/.*ILRV2.*\.(pdf)$/i', $fileName ) ) {
            		$fileName  = 'destiny-ILRV2.pdf';
        	}
		$myFile = DATA_ROOT."/uploads/".$this->db_name."/".$this_username."/".$fileName;

		$full_path = realpath(DATA_ROOT."/uploads/");
		if ( !file_exists( DATA_ROOT."/uploads/".$this->db_name) ) {
			mkdir($full_path."/".$this->db_name);
		}
		if ( !file_exists( DATA_ROOT."/uploads/".$this->db_name."/".$this_username) ) {
                	mkdir($full_path."/".$this->db_name."/".$this_username);
       		}
        }

        $fh = fopen($myFile, 'w');
        if ( !$fh ) {
        	$return_message = 'Unable to store the '.$type.' Data';
        }
        fwrite($fh, $xmlFile);
        fclose($fh);
        $return_message = $type.' PDF Data has been accepted and stored for import ['.$fileName.']['.$myFile.']';

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

	/**
	* initial stub / basic file validation checks prior to
	* trying to do any of the magic	
	*/
	private function validateFiles() {
		foreach ( $this->files as $type => $name ) {
			$file_details = preg_split('/\./', $name);
			if ( 'PDF' == $type && $file_details[1] != 'pdf' ) {
				$this->files['PDF'] = 'invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';	
			}
			else if ( 'XML' == $type && $file_details[1] != 'xml' ) {
				$this->files['XML'] = 'invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
			}
			else {
				$this->files['RND'] = 'invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
			}
		}
	}

	private $xml_content = array();
	private $employer_id = NULL;
	private $employer_location = NULL;
	private $files = array();

}

?>
