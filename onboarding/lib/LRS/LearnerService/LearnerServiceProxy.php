<?php

class LearnerServiceProxy extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
  'LearnerByULNRqst' => '\\LearnerByULNRqst',
  'BaseFindLearnerServiceRqst' => '\\BaseFindLearnerServiceRqst',
  'BaseLearnerServiceRequestPart' => '\\BaseLearnerServiceRequestPart',
  'FindLearnerResp' => '\\FindLearnerResp',
  'LearnerServiceWrappedResponse' => '\\LearnerServiceWrappedResponse',
  'LearnerByDemographicsRqst' => '\\LearnerByDemographicsRqst',
  'Learner' => '\\Learner',
  'ArrayOfString' => '\\ArrayOfString',
  'LearnerToRegister' => '\\LearnerToRegister',
  'LearnerToUpdate' => '\\LearnerToUpdate',
  'BatchLearner' => '\\BatchLearner',
  'OutputBatchLearner' => '\\OutputBatchLearner',
  'MIAPBatchLearnerToVerify' => '\\MIAPBatchLearnerToVerify',
  'MIAPVerifiedBatchLearner' => '\\MIAPVerifiedBatchLearner',
  'MIAPRetrievedULN' => '\\MIAPRetrievedULN',
  'MIAPLearnerToVerify' => '\\MIAPLearnerToVerify',
  'MIAPVerifiedLearner' => '\\MIAPVerifiedLearner',
  'MIAPAPIException' => '\\MIAPAPIException',
  'RegisterSingleLearnerRqst' => '\\RegisterSingleLearnerRqst',
  'BaseLearnerServiceRqst' => '\\BaseLearnerServiceRqst',
  'RegisterSingleLearnerResp' => '\\RegisterSingleLearnerResp',
  'UpdateLearnerRqst' => '\\UpdateLearnerRqst',
  'UpdateLearnerResp' => '\\UpdateLearnerResp',
  'BatchRegistrationRqst' => '\\BatchRegistrationRqst',
  'BatchRegistrationResp' => '\\BatchRegistrationResp',
  'BatchOutputRqst' => '\\BatchOutputRqst',
  'BatchOutputResp' => '\\BatchOutputResp',
  'VerifyBatchRqst' => '\\VerifyBatchRqst',
  'VerifyBatchResp' => '\\VerifyBatchResp',
  'VerifyBatchOutputRqst' => '\\VerifyBatchOutputRqst',
  'VerifyBatchOutputResp' => '\\VerifyBatchOutputResp',
  'RetrieveULNsRqst' => '\\RetrieveULNsRqst',
  'RetrieveULNsResp' => '\\RetrieveULNsResp',
  'ArrayOfMIAPRetrievedULN' => '\\ArrayOfMIAPRetrievedULN',
  'VerifyLearnerRqst' => '\\VerifyLearnerRqst',
  'VerifyLearnerResp' => '\\VerifyLearnerResp',
);

    /**
     * @param string $wsdl The wsdl file to use
     * @param array $options A array of config values
     */
  public function __construct(array $options = array(), $wsdl = null)
  {

    foreach (self::$classmap as $key => $value) {
      if (!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    $options = array_merge(array(
      'features' => 1,
    ), $options);
    
    if (!$wsdl) {
      $wsdl = 'LearnerService.wsdl';
    }
    parent::__construct($wsdl, $options);
  }

    /**
     * @param LearnerByULNRqst $FindLearnerByULN
     * @return FindLearnerResp
     */
    public function learnerByULN(LearnerByULNRqst $FindLearnerByULN)
    {
      return $this->__soapCall('learnerByULN', array($FindLearnerByULN));
    }

    /**
     * @param LearnerByDemographicsRqst $FindLearnerByDemographics
     * @return FindLearnerResp
     */
    public function learnerByDemographics(LearnerByDemographicsRqst $FindLearnerByDemographics)
    {
      return $this->__soapCall('learnerByDemographics', array($FindLearnerByDemographics));
    }

    /**
     * @param RegisterSingleLearnerRqst $RegisterLearner
     * @return RegisterSingleLearnerResp
     */
    public function registerSingleLearner(RegisterSingleLearnerRqst $RegisterLearner)
    {
      return $this->__soapCall('registerSingleLearner', array($RegisterLearner));
    }

    /**
     * @param UpdateLearnerRqst $UpdateLearner
     * @return UpdateLearnerResp
     */
    public function updateSingleLearner(UpdateLearnerRqst $UpdateLearner)
    {
      return $this->__soapCall('updateSingleLearner', array($UpdateLearner));
    }

    /**
     * @param BatchRegistrationRqst $SubmitBatch
     * @return BatchRegistrationResp
     */
    public function submitBatchRegistration(BatchRegistrationRqst $SubmitBatch)
    {
      return $this->__soapCall('submitBatchRegistration', array($SubmitBatch));
    }

    /**
     * @param BatchOutputRqst $GetSubmitBatchOutput
     * @return BatchOutputResp
     */
    public function getBatchRegistrationOutput(BatchOutputRqst $GetSubmitBatchOutput)
    {
      return $this->__soapCall('getBatchRegistrationOutput', array($GetSubmitBatchOutput));
    }

    /**
     * @param VerifyBatchRqst $SubmitVerifyBatch
     * @return VerifyBatchResp
     */
    public function submitVerifyBatch(VerifyBatchRqst $SubmitVerifyBatch)
    {
      return $this->__soapCall('submitVerifyBatch', array($SubmitVerifyBatch));
    }

    /**
     * @param VerifyBatchOutputRqst $GetVerifyBatchOutput
     * @return VerifyBatchOutputResp
     */
    public function getVerifyBatchOutput(VerifyBatchOutputRqst $GetVerifyBatchOutput)
    {
      return $this->__soapCall('getVerifyBatchOutput', array($GetVerifyBatchOutput));
    }

    /**
     * @param RetrieveULNsRqst $RetrieveULNs
     * @return RetrieveULNsResp
     */
    public function retrieveULNs(RetrieveULNsRqst $RetrieveULNs)
    {
      return $this->__soapCall('retrieveULNs', array($RetrieveULNs));
    }

    /**
     * @param VerifyLearnerRqst $VerifyLearner
     * @return VerifyLearnerResp
     */
    public function verifyLearner(VerifyLearnerRqst $VerifyLearner)
    {
      return $this->__soapCall('verifyLearner', array($VerifyLearner));
    }

}
