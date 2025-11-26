<?php
/**
 * Test with LogicMelon for 'http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL'
 * @package LogicMelon
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/LogicMelonAutoload.php';
/**
 * Wsdl instanciation infos. By default, nothing has to be set.
 * If you wish to override the SoapClient's options, please refer to the sample below.
 * 
 * This is an associative array as:
 * - the key must be a LogicMelonWsdlClass constant beginning with WSDL_
 * - the value must be the corresponding key value
 * Each option matches the {@link http://www.php.net/manual/en/soapclient.soapclient.php} options
 * 
 * Here is below an example of how you can set the array:
 * $wsdl = array();
 * $wsdl[LogicMelonWsdlClass::WSDL_URL] = 'http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL';
 * $wsdl[LogicMelonWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
 * $wsdl[LogicMelonWsdlClass::WSDL_TRACE] = true;
 * $wsdl[LogicMelonWsdlClass::WSDL_LOGIN] = 'myLogin';
 * $wsdl[LogicMelonWsdlClass::WSDL_PASSWD] = '**********';
 * etc....
 * Then instantiate the Service class as: 
 * - $wsdlObject = new LogicMelonWsdlClass($wsdl);
 */
/**
 * Examples
 */


/**********************************
 * Example for LogicMelonServiceGet
 */
$logicMelonServiceGet = new LogicMelonServiceGet();
// sample call for LogicMelonServiceGet::GetApplicationsPaged()
if($logicMelonServiceGet->GetApplicationsPaged(new LogicMelonStructGetApplicationsPaged(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetApplications()
if($logicMelonServiceGet->GetApplications(new LogicMelonStructGetApplications(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetApplicationsWithFiltersPaged()
if($logicMelonServiceGet->GetApplicationsWithFiltersPaged(new LogicMelonStructGetApplicationsWithFiltersPaged(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetApplicationsWithFilters()
if($logicMelonServiceGet->GetApplicationsWithFilters(new LogicMelonStructGetApplicationsWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertPaged()
if($logicMelonServiceGet->GetAdvertPaged(new LogicMelonStructGetAdvertPaged(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvert()
if($logicMelonServiceGet->GetAdvert(new LogicMelonStructGetAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertWithFiltersPaged()
if($logicMelonServiceGet->GetAdvertWithFiltersPaged(new LogicMelonStructGetAdvertWithFiltersPaged(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertWithFilters()
if($logicMelonServiceGet->GetAdvertWithFilters(new LogicMelonStructGetAdvertWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertWithValues()
if($logicMelonServiceGet->GetAdvertWithValues(new LogicMelonStructGetAdvertWithValues(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertWithValuesPaged()
if($logicMelonServiceGet->GetAdvertWithValuesPaged(new LogicMelonStructGetAdvertWithValuesPaged(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertWithValuesWithFilters()
if($logicMelonServiceGet->GetAdvertWithValuesWithFilters(new LogicMelonStructGetAdvertWithValuesWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetAdvertWithValuesWithFiltersPaged()
if($logicMelonServiceGet->GetAdvertWithValuesWithFiltersPaged(new LogicMelonStructGetAdvertWithValuesWithFiltersPaged(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetValues()
if($logicMelonServiceGet->GetValues(new LogicMelonStructGetValues(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());
// sample call for LogicMelonServiceGet::GetCurrency()
if($logicMelonServiceGet->GetCurrency(new LogicMelonStructGetCurrency(/*** update parameters list ***/)))
    print_r($logicMelonServiceGet->getResult());
else
    print_r($logicMelonServiceGet->getLastError());

/************************************
 * Example for LogicMelonServiceTrack
 */
$logicMelonServiceTrack = new LogicMelonServiceTrack();
// sample call for LogicMelonServiceTrack::TrackAdvert()
if($logicMelonServiceTrack->TrackAdvert(new LogicMelonStructTrackAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceTrack->getResult());
else
    print_r($logicMelonServiceTrack->getLastError());
// sample call for LogicMelonServiceTrack::TrackAdvertWithFilters()
if($logicMelonServiceTrack->TrackAdvertWithFilters(new LogicMelonStructTrackAdvertWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceTrack->getResult());
else
    print_r($logicMelonServiceTrack->getLastError());

/************************************
 * Example for LogicMelonServiceClose
 */
$logicMelonServiceClose = new LogicMelonServiceClose();
// sample call for LogicMelonServiceClose::CloseAdvert()
if($logicMelonServiceClose->CloseAdvert(new LogicMelonStructCloseAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceClose->getResult());
else
    print_r($logicMelonServiceClose->getLastError());
// sample call for LogicMelonServiceClose::CloseAdvertWithFilters()
if($logicMelonServiceClose->CloseAdvertWithFilters(new LogicMelonStructCloseAdvertWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceClose->getResult());
else
    print_r($logicMelonServiceClose->getLastError());

/**************************************
 * Example for LogicMelonServiceArchive
 */
$logicMelonServiceArchive = new LogicMelonServiceArchive();
// sample call for LogicMelonServiceArchive::ArchiveAdvert()
if($logicMelonServiceArchive->ArchiveAdvert(new LogicMelonStructArchiveAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceArchive->getResult());
else
    print_r($logicMelonServiceArchive->getLastError());
// sample call for LogicMelonServiceArchive::ArchiveAdvertWithFilters()
if($logicMelonServiceArchive->ArchiveAdvertWithFilters(new LogicMelonStructArchiveAdvertWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceArchive->getResult());
else
    print_r($logicMelonServiceArchive->getLastError());

/****************************************
 * Example for LogicMelonServiceUnarchive
 */
$logicMelonServiceUnarchive = new LogicMelonServiceUnarchive();
// sample call for LogicMelonServiceUnarchive::UnarchiveAdvert()
if($logicMelonServiceUnarchive->UnarchiveAdvert(new LogicMelonStructUnarchiveAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceUnarchive->getResult());
else
    print_r($logicMelonServiceUnarchive->getLastError());
// sample call for LogicMelonServiceUnarchive::UnarchiveAdvertWithFilters()
if($logicMelonServiceUnarchive->UnarchiveAdvertWithFilters(new LogicMelonStructUnarchiveAdvertWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceUnarchive->getResult());
else
    print_r($logicMelonServiceUnarchive->getLastError());

/**********************************
 * Example for LogicMelonServiceAdd
 */
$logicMelonServiceAdd = new LogicMelonServiceAdd();
// sample call for LogicMelonServiceAdd::AddAdvert()
if($logicMelonServiceAdd->AddAdvert(new LogicMelonStructAddAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceAdd->getResult());
else
    print_r($logicMelonServiceAdd->getLastError());
// sample call for LogicMelonServiceAdd::AddAdvertWithValues()
if($logicMelonServiceAdd->AddAdvertWithValues(new LogicMelonStructAddAdvertWithValues(/*** update parameters list ***/)))
    print_r($logicMelonServiceAdd->getResult());
else
    print_r($logicMelonServiceAdd->getLastError());
// sample call for LogicMelonServiceAdd::AddAdvertValues()
if($logicMelonServiceAdd->AddAdvertValues(new LogicMelonStructAddAdvertValues(/*** update parameters list ***/)))
    print_r($logicMelonServiceAdd->getResult());
else
    print_r($logicMelonServiceAdd->getLastError());
// sample call for LogicMelonServiceAdd::AddAdvertCandidate()
if($logicMelonServiceAdd->AddAdvertCandidate(new LogicMelonStructAddAdvertCandidate(/*** update parameters list ***/)))
    print_r($logicMelonServiceAdd->getResult());
else
    print_r($logicMelonServiceAdd->getLastError());
// sample call for LogicMelonServiceAdd::AddAdvertCandidateWithFilters()
if($logicMelonServiceAdd->AddAdvertCandidateWithFilters(new LogicMelonStructAddAdvertCandidateWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceAdd->getResult());
else
    print_r($logicMelonServiceAdd->getLastError());

/***************************************
 * Example for LogicMelonServiceValidate
 */
$logicMelonServiceValidate = new LogicMelonServiceValidate();
// sample call for LogicMelonServiceValidate::ValidateAdvertValues()
if($logicMelonServiceValidate->ValidateAdvertValues(new LogicMelonStructValidateAdvertValues(/*** update parameters list ***/)))
    print_r($logicMelonServiceValidate->getResult());
else
    print_r($logicMelonServiceValidate->getLastError());

/**************************************
 * Example for LogicMelonServiceDeliver
 */
$logicMelonServiceDeliver = new LogicMelonServiceDeliver();
// sample call for LogicMelonServiceDeliver::DeliverAdvert()
if($logicMelonServiceDeliver->DeliverAdvert(new LogicMelonStructDeliverAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceDeliver->getResult());
else
    print_r($logicMelonServiceDeliver->getLastError());

/***********************************
 * Example for LogicMelonServiceUser
 */
$logicMelonServiceUser = new LogicMelonServiceUser();
// sample call for LogicMelonServiceUser::UserFeedsAndQuota()
if($logicMelonServiceUser->UserFeedsAndQuota(new LogicMelonStructUserFeedsAndQuota(/*** update parameters list ***/)))
    print_r($logicMelonServiceUser->getResult());
else
    print_r($logicMelonServiceUser->getLastError());
// sample call for LogicMelonServiceUser::UserFeedsAndQuotaWithDestinations()
if($logicMelonServiceUser->UserFeedsAndQuotaWithDestinations(new LogicMelonStructUserFeedsAndQuotaWithDestinations(/*** update parameters list ***/)))
    print_r($logicMelonServiceUser->getResult());
else
    print_r($logicMelonServiceUser->getLastError());

/**************************************
 * Example for LogicMelonServiceExpress
 */
$logicMelonServiceExpress = new LogicMelonServiceExpress();
// sample call for LogicMelonServiceExpress::ExpressPostAdvert()
if($logicMelonServiceExpress->ExpressPostAdvert(new LogicMelonStructExpressPostAdvert(/*** update parameters list ***/)))
    print_r($logicMelonServiceExpress->getResult());
else
    print_r($logicMelonServiceExpress->getLastError());
// sample call for LogicMelonServiceExpress::ExpressPostAdvertWithFilters()
if($logicMelonServiceExpress->ExpressPostAdvertWithFilters(new LogicMelonStructExpressPostAdvertWithFilters(/*** update parameters list ***/)))
    print_r($logicMelonServiceExpress->getResult());
else
    print_r($logicMelonServiceExpress->getLastError());

/************************************
 * Example for LogicMelonServiceQuery
 */
$logicMelonServiceQuery = new LogicMelonServiceQuery();
// sample call for LogicMelonServiceQuery::QueryLocations()
if($logicMelonServiceQuery->QueryLocations(new LogicMelonStructQueryLocations(/*** update parameters list ***/)))
    print_r($logicMelonServiceQuery->getResult());
else
    print_r($logicMelonServiceQuery->getLastError());
// sample call for LogicMelonServiceQuery::QueryJobTitle()
if($logicMelonServiceQuery->QueryJobTitle(new LogicMelonStructQueryJobTitle(/*** update parameters list ***/)))
    print_r($logicMelonServiceQuery->getResult());
else
    print_r($logicMelonServiceQuery->getLastError());
