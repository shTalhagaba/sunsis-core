<?php
/**
 * File for the class which returns the class map definition
 * @package MIAP
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * Class which returns the class map definition by the static method MIAPClassMap::classMap()
 * @package MIAP
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPClassMap
{
    /**
     * This method returns the array containing the mapping between WSDL structs and generated classes
     * This array is sent to the SoapClient when calling the WS
     * @return array
     */
    final public static function classMap()
    {
        return array (
  'BatchLearner' => 'MIAPStructBatchLearner',
  'BatchOutputResp' => 'MIAPStructBatchOutputResp',
  'BatchOutputRqst' => 'MIAPStructBatchOutputRqst',
  'BatchRegistrationResp' => 'MIAPStructBatchRegistrationResp',
  'BatchRegistrationRqst' => 'MIAPStructBatchRegistrationRqst',
  'FindLearnerResp' => 'MIAPStructFindLearnerResp',
  'Learner' => 'MIAPStructLearner',
  'LearnerByDemographicsRqst' => 'MIAPStructLearnerByDemographicsRqst',
  'LearnerByULNRqst' => 'MIAPStructLearnerByULNRqst',
  'LearnerRecordResp' => 'MIAPStructLearnerRecordResp',
  'LearnerRecordRqst' => 'MIAPStructLearnerRecordRqst',
  'LearnerToRegister' => 'MIAPStructLearnerToRegister',
  'LearnerToUpdate' => 'MIAPStructLearnerToUpdate',
  'MIAPAPIException' => 'MIAPStructMIAPAPIException',
  'MIAPBatchLearnerToVerify' => 'MIAPStructMIAPBatchLearnerToVerify',
  'MIAPDeletedLearner' => 'MIAPStructMIAPDeletedLearner',
  'MIAPDeletedLearners' => 'MIAPStructMIAPDeletedLearners',
  'MIAPLearnerRecord' => 'MIAPStructMIAPLearnerRecord',
  'MIAPLearnerToVerify' => 'MIAPStructMIAPLearnerToVerify',
  'MIAPLearningEvent' => 'MIAPStructMIAPLearningEvent',
  'MIAPLearningEvents' => 'MIAPStructMIAPLearningEvents',
  'MIAPLinkedULN' => 'MIAPStructMIAPLinkedULN',
  'MIAPMergedLearner' => 'MIAPStructMIAPMergedLearner',
  'MIAPMergedLearners' => 'MIAPStructMIAPMergedLearners',
  'MIAPRetrievedULN' => 'MIAPStructMIAPRetrievedULN',
  'MIAPRetrievedULNs' => 'MIAPStructMIAPRetrievedULNs',
  'MIAPULNReport' => 'MIAPStructMIAPULNReport',
  'MIAPULNsToRetrieve' => 'MIAPStructMIAPULNsToRetrieve',
  'MIAPUnmergedLearner' => 'MIAPStructMIAPUnmergedLearner',
  'MIAPUnmergedLearners' => 'MIAPStructMIAPUnmergedLearners',
  'MIAPVerifiedBatchLearner' => 'MIAPStructMIAPVerifiedBatchLearner',
  'MIAPVerifiedLearner' => 'MIAPStructMIAPVerifiedLearner',
  'OutputBatchLearner' => 'MIAPStructOutputBatchLearner',
  'RegisterSingleLearnerResp' => 'MIAPStructRegisterSingleLearnerResp',
  'RegisterSingleLearnerRqst' => 'MIAPStructRegisterSingleLearnerRqst',
  'RetrieveULNsResp' => 'MIAPStructRetrieveULNsResp',
  'RetrieveULNsRqst' => 'MIAPStructRetrieveULNsRqst',
  'ULNReportResp' => 'MIAPStructULNReportResp',
  'ULNReportRqst' => 'MIAPStructULNReportRqst',
  'UpdateLearnerResp' => 'MIAPStructUpdateLearnerResp',
  'UpdateLearnerRqst' => 'MIAPStructUpdateLearnerRqst',
  'VerifyBatchOutputResp' => 'MIAPStructVerifyBatchOutputResp',
  'VerifyBatchOutputRqst' => 'MIAPStructVerifyBatchOutputRqst',
  'VerifyBatchResp' => 'MIAPStructVerifyBatchResp',
  'VerifyBatchRqst' => 'MIAPStructVerifyBatchRqst',
  'VerifyLearnerResp' => 'MIAPStructVerifyLearnerResp',
  'VerifyLearnerRqst' => 'MIAPStructVerifyLearnerRqst',
);
    }
}
