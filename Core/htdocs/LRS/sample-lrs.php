<?php
/**
 * Test with LRS for 'http://compact-soft.com/projects/tempuri.org.wsdl'
 * @package LRS
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/LRSAutoload.php';
/**
 * Wsdl instanciation infos. By default, nothing has to be set.
 * If you wish to override the SoapClient's options, please refer to the sample below.
 * 
 * This is an associative array as:
 * - the key must be a LRSWsdlClass constant beginning with WSDL_
 * - the value must be the corresponding key value
 * Each option matches the {@link http://www.php.net/manual/en/soapclient.soapclient.php} options
 * 
 * Here is below an example of how you can set the array:
 * $wsdl = array();
 * $wsdl[LRSWsdlClass::WSDL_URL] = 'http://compact-soft.com/projects/tempuri.org.wsdl';
 * $wsdl[LRSWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
 * $wsdl[LRSWsdlClass::WSDL_TRACE] = true;
 * $wsdl[LRSWsdlClass::WSDL_LOGIN] = 'myLogin';
 * $wsdl[LRSWsdlClass::WSDL_PASSWD] = '**********';
 * etc....
 * Then instantiate the Service class as: 
 * - $wsdlObject = new LRSWsdlClass($wsdl);
 */
/**
 * Examples
 */


/*******************************
 * Example for LRSServiceExecute
 */
$lRSServiceExecute = new LRSServiceExecute();
// sample call for LRSServiceExecute::ExecuteRtaQuery()
if($lRSServiceExecute->ExecuteRtaQuery(new LRSStructExecuteRtaQuery(/*** update parameters list ***/)))
    print_r($lRSServiceExecute->getResult());
else
    print_r($lRSServiceExecute->getLastError());
// sample call for LRSServiceExecute::ExecuteRoCQuery()
if($lRSServiceExecute->ExecuteRoCQuery(new LRSStructExecuteRoCQuery(/*** update parameters list ***/)))
    print_r($lRSServiceExecute->getResult());
else
    print_r($lRSServiceExecute->getLastError());

/****************************
 * Example for LRSServiceSave
 */
$lRSServiceSave = new LRSServiceSave();
// sample call for LRSServiceSave::SaveRtaQuery()
if($lRSServiceSave->SaveRtaQuery(new LRSStructSaveRtaQuery(/*** update parameters list ***/)))
    print_r($lRSServiceSave->getResult());
else
    print_r($lRSServiceSave->getLastError());

/****************************
 * Example for LRSServiceList
 */
$lRSServiceList = new LRSServiceList();
// sample call for LRSServiceList::ListSavedRtaQueries()
if($lRSServiceList->ListSavedRtaQueries(new LRSStructListSavedRtaQueries(/*** update parameters list ***/)))
    print_r($lRSServiceList->getResult());
else
    print_r($lRSServiceList->getLastError());
// sample call for LRSServiceList::ListDataChallenge()
if($lRSServiceList->ListDataChallenge(new LRSStructListDataChallenge(/*** update parameters list ***/)))
    print_r($lRSServiceList->getResult());
else
    print_r($lRSServiceList->getLastError());
// sample call for LRSServiceList::ListNotificationStatus()
if($lRSServiceList->ListNotificationStatus(new LRSStructListNotificationStatus(/*** update parameters list ***/)))
    print_r($lRSServiceList->getResult());
else
    print_r($lRSServiceList->getLastError());
// sample call for LRSServiceList::ListSnapshots()
if($lRSServiceList->ListSnapshots(new LRSStructListSnapshots(/*** update parameters list ***/)))
    print_r($lRSServiceList->getResult());
else
    print_r($lRSServiceList->getLastError());

/******************************
 * Example for LRSServiceDelete
 */
$lRSServiceDelete = new LRSServiceDelete();
// sample call for LRSServiceDelete::DeleteSavedRtaQuery()
if($lRSServiceDelete->DeleteSavedRtaQuery(new LRSStructDeleteSavedRtaQuery(/*** update parameters list ***/)))
    print_r($lRSServiceDelete->getResult());
else
    print_r($lRSServiceDelete->getLastError());
// sample call for LRSServiceDelete::DeleteDataChallenge()
if($lRSServiceDelete->DeleteDataChallenge(new LRSStructDeleteDataChallenge(/*** update parameters list ***/)))
    print_r($lRSServiceDelete->getResult());
else
    print_r($lRSServiceDelete->getLastError());
// sample call for LRSServiceDelete::DeleteSnapshot()
if($lRSServiceDelete->DeleteSnapshot(new LRSStructDeleteSnapshot(/*** update parameters list ***/)))
    print_r($lRSServiceDelete->getResult());
else
    print_r($lRSServiceDelete->getLastError());

/*******************************
 * Example for LRSServiceLearner
 */
$lRSServiceLearner = new LRSServiceLearner();
// sample call for LRSServiceLearner::LearnerByUln()
if($lRSServiceLearner->LearnerByUln(new LRSStructLearnerByUln(/*** update parameters list ***/)))
    print_r($lRSServiceLearner->getResult());
else
    print_r($lRSServiceLearner->getLastError());

/****************************
 * Example for LRSServiceFind
 */
$lRSServiceFind = new LRSServiceFind();
// sample call for LRSServiceFind::FindLearnerByUlnKey()
if($lRSServiceFind->FindLearnerByUlnKey(new LRSStructFindLearnerByUlnKey(/*** update parameters list ***/)))
    print_r($lRSServiceFind->getResult());
else
    print_r($lRSServiceFind->getLastError());

/***************************
 * Example for LRSServiceGet
 */
$lRSServiceGet = new LRSServiceGet();
// sample call for LRSServiceGet::GetLearnerLearningEvents()
if($lRSServiceGet->GetLearnerLearningEvents(new LRSStructGetLearnerLearningEvents(/*** update parameters list ***/)))
    print_r($lRSServiceGet->getResult());
else
    print_r($lRSServiceGet->getLastError());
// sample call for LRSServiceGet::GetLearnerRecord()
if($lRSServiceGet->GetLearnerRecord(new LRSStructGetLearnerRecord(/*** update parameters list ***/)))
    print_r($lRSServiceGet->getResult());
else
    print_r($lRSServiceGet->getLastError());
// sample call for LRSServiceGet::GetMyLearningEvents()
if($lRSServiceGet->GetMyLearningEvents(new LRSStructGetMyLearningEvents(/*** update parameters list ***/)))
    print_r($lRSServiceGet->getResult());
else
    print_r($lRSServiceGet->getLastError());
// sample call for LRSServiceGet::GetOrganisation()
if($lRSServiceGet->GetOrganisation(new LRSStructGetOrganisation(/*** update parameters list ***/)))
    print_r($lRSServiceGet->getResult());
else
    print_r($lRSServiceGet->getLastError());

/******************************
 * Example for LRSServiceUpdate
 */
$lRSServiceUpdate = new LRSServiceUpdate();
// sample call for LRSServiceUpdate::UpdateLearnerSubsetFields()
if($lRSServiceUpdate->UpdateLearnerSubsetFields(new LRSStructUpdateLearnerSubsetFields(/*** update parameters list ***/)))
    print_r($lRSServiceUpdate->getResult());
else
    print_r($lRSServiceUpdate->getLastError());
// sample call for LRSServiceUpdate::UpdateLearner()
if($lRSServiceUpdate->UpdateLearner(new LRSStructUpdateLearner(/*** update parameters list ***/)))
    print_r($lRSServiceUpdate->getResult());
else
    print_r($lRSServiceUpdate->getLastError());
// sample call for LRSServiceUpdate::UpdateLearnerByUlnKey()
if($lRSServiceUpdate->UpdateLearnerByUlnKey(new LRSStructUpdateLearnerByUlnKey(/*** update parameters list ***/)))
    print_r($lRSServiceUpdate->getResult());
else
    print_r($lRSServiceUpdate->getLastError());

/******************************
 * Example for LRSServiceCreate
 */
$lRSServiceCreate = new LRSServiceCreate();
// sample call for LRSServiceCreate::CreateDataChallenge()
if($lRSServiceCreate->CreateDataChallenge(new LRSStructCreateDataChallenge(/*** update parameters list ***/)))
    print_r($lRSServiceCreate->getResult());
else
    print_r($lRSServiceCreate->getLastError());
// sample call for LRSServiceCreate::CreateOrModifySnapshot()
if($lRSServiceCreate->CreateOrModifySnapshot(new LRSStructCreateOrModifySnapshot(/*** update parameters list ***/)))
    print_r($lRSServiceCreate->getResult());
else
    print_r($lRSServiceCreate->getLastError());

/****************************
 * Example for LRSServiceView
 */
$lRSServiceView = new LRSServiceView();
// sample call for LRSServiceView::ViewAudit()
if($lRSServiceView->ViewAudit(new LRSStructViewAudit(/*** update parameters list ***/)))
    print_r($lRSServiceView->getResult());
else
    print_r($lRSServiceView->getLastError());

/*****************************
 * Example for LRSServiceStore
 */
$lRSServiceStore = new LRSServiceStore();
// sample call for LRSServiceStore::StoreLearnerKey()
if($lRSServiceStore->StoreLearnerKey(new LRSStructStoreLearnerKey(/*** update parameters list ***/)))
    print_r($lRSServiceStore->getResult());
else
    print_r($lRSServiceStore->getLastError());

/******************************
 * Example for LRSServiceRemove
 */
$lRSServiceRemove = new LRSServiceRemove();
// sample call for LRSServiceRemove::RemoveLearnerKey()
if($lRSServiceRemove->RemoveLearnerKey(new LRSStructRemoveLearnerKey(/*** update parameters list ***/)))
    print_r($lRSServiceRemove->getResult());
else
    print_r($lRSServiceRemove->getLastError());

/***************************
 * Example for LRSServiceSet
 */
$lRSServiceSet = new LRSServiceSet();
// sample call for LRSServiceSet::SetNotification()
if($lRSServiceSet->SetNotification(new LRSStructSetNotification(/*** update parameters list ***/)))
    print_r($lRSServiceSet->getResult());
else
    print_r($lRSServiceSet->getLastError());
