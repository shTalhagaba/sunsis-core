<?php

// need to sort this part to build into existing framework
// ------
//require_once ("../lib/config.php");
require_once ("../lib/DAO.php");
require_once ("../lib/Entity.php");
require_once ("../lib/User.php");
require_once ("../lib/Organisation.php");
require_once ("../lib/Location.php");
require_once ("../lib/ACL.php");
// ------

ini_set('default_charset', 'iso-8859-1');
ini_set("soap.wsdl_cache_enabled", "0");

session_start();

$soap_systems = array (
	'am_siemens_demo' => 'wsdl/siemens.wsdl'
);

// re:
// #TODO: need to build wsdl dynamically based on system as opposed to creating a new wsdl per system.
// allow for the dynamic use of wsdl
$default_wsdl = 'wsdl/siemens.wsdl';
$server = new SoapServer($default_wsdl);


$server->setClass('accept_siemens_file');
$server->handle();

class accept_siemens_file {

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

	public function acceptCSVFile($username, $password, $api_key, $fileName, $fileVersion, $xmlFile) {

		// ensure the system is setup to allow soap connections
		$configuration_sql = "SELECT value FROM configuration WHERE entity = 'module_soap'";
		if( (int)(DAO::getSingleValue($this->connection, $configuration_sql)) !== 1 ) {
			return 'WSEC001. SOAP request is not enabled for your site, operation aborted.';
		}

		$api_key_sql = "SELECT value FROM configuration WHERE entity = 'module_soap_sunesis_key'";
		if( DAO::getSingleValue($this->connection, $api_key_sql) !== $api_key ) {
			return 'WSEC002. Authentication Failed [Incorrect API Key]';
		}
		if ( !$this->validateConnection($username, $password) ) {
			return 'WSEC003. Authentication Failed [Incorrect Username/Password]';
		}

		if( trim($fileName) == '' || strtolower(trim($fileName)) == '.csv' ) {
			return 'WSEC004. File name missing, operation aborted. ';
		}

		if( trim($fileVersion) == '' ) {
			return 'WSEC005. File version missing, operation aborted. ';
		}

		if( trim($xmlFile) == '' ) {
			return 'WSEC006. File is empty, operation aborted. ';
		}
		// basic file verifications
		$this->files['CSV'] = $fileName;
		$this->validateFiles();
		if (( $this->files['CSV'] != $fileName )) {
			return 'WSEC007. File type not recognized: [CSV: '.$this->files['CSV'].']';
		}
		$xml_response = $this->saveCSVSiemens('CSV', $fileName, $xmlFile);

		return "[CSV]:".$xml_response;
	}

	/**
	 * Authentication of request
	 * -------
	 * 2. check the login details provide are valid
	 */
	private function validateConnection($username, $password) {

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

	private function saveCSVSiemens($type, $fileName, $csvFile) {

		$return_message = '';

		if ( !preg_match('/^.*\.(csv)$/i', $fileName ) ) {
			$return_message = 'File type not recognized: '.$fileName;
			return $return_message;
		}
		try
		{
			$data_root = "";
			// Data directory
			if (isset($_SERVER['PERSPECTIVE_DATA_ROOT'])) {
				$data_root = rtrim($_SERVER['PERSPECTIVE_DATA_ROOT'], '\\/');
			} else if(PHP_OS == "WINNT") {
				$data_root = "C:/Apps/sunesis-data";
			} else {
				$data_root = "/srv/www/am_common_data";
			}

			if(!file_exists($data_root."/uploads/".$this->db_name."/soap/"))
			{
				mkdir($data_root."/uploads/".$this->db_name."/soap/", 0777, true);
			}

			$myFile = $data_root."/uploads/".$this->db_name."/soap/".time().'_'.$fileName;

			$fh = fopen($myFile, 'w');
			if ( !$fh ) {
				$return_message = 'Unable to store the '.$type.' file at ' . $myFile;
				return $return_message;
			}
			fwrite($fh, $csvFile);
			fclose($fh);

			$return_message = 'WSRC001. File has been accepted and stored. ';
			return $return_message;
		}
		catch(Exception $e)
		{
			$return_message = 'WSEC099. ' . $type.' Data has not been recognised';
			return $return_message;
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
				$this->files['PDF'] = '4.1 invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
			}
			else if ( 'XML' == $type && $file_details[1] != 'xml' ) {
				$this->files['XML'] = '4.1 invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
			}
			else if ( 'CSV' == $type && $file_details[1] != 'csv' ) {
				$this->files['CSV'] = '4.1 invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
			}
			else {
				$this->files['RND'] = '4.1 invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
			}
		}
	}

	private $employer_id = NULL;
	private $employer_location = NULL;
	private $files = array();

}

?>
