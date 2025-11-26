<?php

class ExternalServiceProxy extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
  'SubmitAchievementBatchJob' => '\\SubmitAchievementBatchJob',
  'ArrayOfAchievement' => '\\ArrayOfAchievement',
  'Achievement' => '\\Achievement',
  'SubmitAchievementBatchJobResponse' => '\\SubmitAchievementBatchJobResponse',
  'GetAchievementBatchJob' => '\\GetAchievementBatchJob',
  'GetAchievementBatchJobResponse' => '\\GetAchievementBatchJobResponse',
  'User' => '\\User',
  'BusinessObject' => '\\BusinessObject',
  'ServiceResponseR9' => '\\ServiceResponseR9',
  'AchievementBatchJobResponse' => '\\AchievementBatchJobResponse',
  'DomainFault' => '\\DomainFault',
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
      $options = array_merge(array (
  'features' => 1,
), $options);
      if (!$wsdl) {
        $wsdl = 'ExternalService.wsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param SubmitAchievementBatchJob $parameters
     * @return SubmitAchievementBatchJobResponse
     */
    public function SubmitAchievementBatchJob(SubmitAchievementBatchJob $parameters)
    {
      return $this->__soapCall('SubmitAchievementBatchJob', array($parameters));
    }

    /**
     * @param GetAchievementBatchJob $parameters
     * @return GetAchievementBatchJobResponse
     */
    public function GetAchievementBatchJob(GetAchievementBatchJob $parameters)
    {
      return $this->__soapCall('GetAchievementBatchJob', array($parameters));
    }

}
