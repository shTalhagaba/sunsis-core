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
$server = new SoapServer("wsdl/exg.wsdl");
$server->setClass('exg_accept_destiny');
$server->handle();

class exg_accept_destiny {

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
     * Accept the fwsolutions ilr pdf
     */
    public function acceptILRDATA($username, $password, $fileName, $pdfFile) {

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
     * Authentication of request
     * -------
     * 1. checks the system is enabled to allow soap requests
     * 2. check the login details provide are valid
     */
    private function validateConnection($username, $password) {

        // ensure the system is setup to allow soap connections
     //   $configuration_sql = "SELECT value FROM configuration WHERE entity = 'module_soap'";
      //  if( (int)(DAO::getSingleValue($this->connection, $configuration_sql)) !== 1 ) {
       ///     return false;
       // }

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
     * Saving of the pdf file
     */
    private function savePDF($type, $fileName, $xmlFile, $userni = '' ) {

        $return_message = '';

        if ( !preg_match('/^.*\.(csv)$/i', $fileName ) ) {
            $return_message = 'File type not recognized: '.$fileName;
        }

        // ---
        // re - rename the file to allow 
        //      standardisation for the 
        //	    subsequent presentation
        if ( preg_match('/.*-EXG-.*\.(csv)$/i', $fileName ) ) {
            // $fileName  = 'destiny-ILRV2.pdf';
        }
        elseif ( preg_match('/.*-LA-.*\.(csv)$/i', $fileName ) ) {
            $fileName  = 'exg-LA.csv';
        }
        // ---

        $myFile = DATA_ROOT."/uploads/".$fileName;

        if ( '' != $userni && preg_match('/^[A-Za-z]{2}[0-9]{6}[A-Za-z]{1}$/', $userni) ) {
            $this_username = DAO::getSingleValue($this->connection, "SELECT username FROM users WHERE ni = '".$userni."'");
            if ( preg_match('/.*EXG.*\.(csv)$/i', $fileName ) ) {
                $fileName  = 'exg-EXG.csv';
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
        $return_message = $type.' ILR Data has been accepted and stored for import ['.$fileName.']['.$myFile.']';

        return $return_message;
    }


    /**
     * initial stub / basic file validation checks prior to
     * trying to do any of the magic
     */
    private function validateFiles() {
        foreach ( $this->files as $type => $name ) {
            $file_details = preg_split('/\./', $name);
            if ( 'CSV' == $type && $file_details[1] != 'csv' ) {
                $this->files['CSV'] = 'invalid file '.$type.' '.$name.' ['.$file_details[1].']['.$file_details[0].']';
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
