<?php
/**
 * Test with MIAP for 'http://localhost/miap.wsdl'
 * @package MIAP
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/MIAPAutoload.php';

	/**
 * Wsdl instanciation infos. By default, nothing has to be set.
 * If you wish to override the SoapClient's options, please refer to the sample below.
 * 
 * This is an associative array as:
 * - the key must be a MIAPWsdlClass constant beginning with WSDL_
 * - the value must be the corresponding key value
 * Each option matches the {@link http://www.php.net/manual/en/soapclient.soapclient.php} options
 * 
 * Here is below an example of how you can set the array:
 * $wsdl = array();
 * $wsdl[MIAPWsdlClass::WSDL_URL] = 'http://localhost/miap.wsdl';
 * $wsdl[MIAPWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
 * $wsdl[MIAPWsdlClass::WSDL_TRACE] = true;
 * $wsdl[MIAPWsdlClass::WSDL_LOGIN] = 'myLogin';
 * $wsdl[MIAPWsdlClass::WSDL_PASSWD] = '**********';
 * etc....
 * Then instantiate the Service class as: 
 * - $wsdlObject = new MIAPWsdlClass($wsdl);
 */
/**
 * Examples
 */


/********************************
 * Example for MIAPServiceLearner
 */

$mIAPServiceLearner = new MIAPServiceLearner();

// sample call for MIAPServiceLearner::learnerByULN()
$learner = new MIAPStructLearnerByULNRqst('CHK', 'P3rsp3ctiv358303', 'TEST05', '1026893096','Tucker', 'Darcie', 'TEST05');
//pre($learner);exit;
if($mIAPServiceLearner->learnerByULN($learner))
    pre($mIAPServiceLearner->getResult());
else
    pre($mIAPServiceLearner->getLastRequest());


// sample call for MIAPServiceLearner::learnerByDemographics()

$learner = new MIAPStructLearnerByDemographicsRqst('FUL', 'SamplePassword', 'SampleUserName', 'Slavin', 'Catherine', '1994-03-03', '2', 'B35 6LR');


if($mIAPServiceLearner->learnerByDemographics($learner))
{
	pre($mIAPServiceLearner->getResult());
}
else
{
	pre($mIAPServiceLearner->getLastError());
}

