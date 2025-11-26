<?php
/**
 * File to load generated classes once at once time
 * @package MIAP
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * Includes for all generated classes files
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
require_once dirname(__FILE__) . '/MIAPWsdlClass.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearnerToRegister.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructBatchLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructUpdateLearnerResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructRegisterSingleLearnerResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructUpdateLearnerRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructRegisterSingleLearnerRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructBatchRegistrationRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructBatchOutputRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructVerifyBatchRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructBatchOutputResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructBatchRegistrationResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPRetrievedULNs.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructFindLearnerResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPBatchLearnerToVerify.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPVerifiedLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPVerifiedBatchLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructVerifyBatchOutputRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearnerByDemographicsRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearnerByULNRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPLearnerToVerify.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructVerifyBatchResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearnerRecordResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearnerRecordRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPLearnerRecord.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPAPIException.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPLearningEvents.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPLearningEvent.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructRetrieveULNsRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructULNReportResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructULNReportRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructVerifyBatchOutputResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructRetrieveULNsResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructVerifyLearnerRqst.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructVerifyLearnerResp.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPRetrievedULN.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPULNReport.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPUnmergedLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPMergedLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructOutputBatchLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearnerToUpdate.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPDeletedLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPMergedLearners.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPDeletedLearners.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPUnmergedLearners.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructLearner.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPLinkedULN.php';
require_once dirname(__FILE__) . '/Struct/MIAPStructMIAPULNsToRetrieve.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceLearner.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceRegister.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceUpdate.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceSubmit.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceGet.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceRetrieve.php';
require_once dirname(__FILE__) . '/Service/MIAPServiceVerify.php';
require_once dirname(__FILE__) . '/MIAPClassMap.php';
//define('WEBROOT', dirname(__DIR__) . '/');
require_once(WEBROOT . 'lib/config.php');
