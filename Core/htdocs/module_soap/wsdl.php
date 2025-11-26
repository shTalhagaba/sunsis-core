<?php

/**
* @author: relmes
* function to return a wsdl based on the 
* requested system.
*/

ini_set('default_charset', 'iso-8859-1');
ini_set("soap.wsdl_cache_enabled", "0");

$soap_systems = array (
	'am_fwsolutions' => 'wsdl/fwsolutions.wsdl',
	'am_demo' => 'wsdl/demo.wsdl',
	'am_destiny' => 'wsdl/destiny.wsdl',
    'am_exg' => 'wsdl/exg.wsdl',
	'am_sunesis' => 'wsdl/relmes.wsdl'   // development wsdl
);

$default_wsdl = isset($_SERVER['PERSPECTIVE_DB_NAME'])?$soap_systems{$_SERVER['PERSPECTIVE_DB_NAME']}:'wsdl/demo.wsdl';

echo file_get_contents($default_wsdl);
exit;

?>