<?php

class LearnerServiceR9Proxy extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
  'LearnerByUln' => '\\LearnerByUln',
  'LearnerByUlnResponse' => '\\LearnerByUlnResponse',
  'GetLearnerLearningEvents' => '\\GetLearnerLearningEvents',
  'GetLearnerLearningEventsResponse' => '\\GetLearnerLearningEventsResponse',
  'UpdateLearnerSubsetFields' => '\\UpdateLearnerSubsetFields',
  'UpdateLearnerSubsetFieldsResponse' => '\\UpdateLearnerSubsetFieldsResponse',
  'ViewAudit' => '\\ViewAudit',
  'ViewAuditResponse' => '\\ViewAuditResponse',
  'GetOrganisation' => '\\GetOrganisation',
  'GetOrganisationResponse' => '\\GetOrganisationResponse',
  'InvokingOrganisation' => '\\InvokingOrganisation',
  'ServiceResponseR9' => '\\ServiceResponseR9',
  'ArrayOfLearningEvent' => '\\ArrayOfLearningEvent',
  'LearningEvent' => '\\LearningEvent',
  'PagedListOfPlrAccessEntryResponseN4dIIFC_S' => '\\PagedListOfPlrAccessEntryResponseN4dIIFC_S',
  'ArrayOfPlrAccessEntryResponse' => '\\ArrayOfPlrAccessEntryResponse',
  'PlrAccessEntryResponse' => '\\PlrAccessEntryResponse',
  'Learner' => '\\Learner',
  'BusinessObject' => '\\BusinessObject',
  'ArrayOfstring' => '\\ArrayOfstring',
  'DomainFault' => '\\DomainFault',
  'InvokingOrganisationR10' => '\\InvokingOrganisationR10',
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
        $wsdl = 'LearnerServiceR9.wsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param LearnerByUln $parameters
     * @return LearnerByUlnResponse
     */
    public function LearnerByUln(LearnerByUln $parameters)
    {
      return $this->__soapCall('LearnerByUln', array($parameters));
    }

    /**
     * @param GetLearnerLearningEvents $parameters
     * @return GetLearnerLearningEventsResponse
     */
    public function GetLearnerLearningEvents(GetLearnerLearningEvents $parameters)
    {
      return $this->__soapCall('GetLearnerLearningEvents', array($parameters));
    }

    /**
     * @param UpdateLearnerSubsetFields $parameters
     * @return UpdateLearnerSubsetFieldsResponse
     */
    public function UpdateLearnerSubsetFields(UpdateLearnerSubsetFields $parameters)
    {
      return $this->__soapCall('UpdateLearnerSubsetFields', array($parameters));
    }

    /**
     * @param ViewAudit $parameters
     * @return ViewAuditResponse
     */
    public function ViewAudit(ViewAudit $parameters)
    {
      return $this->__soapCall('ViewAudit', array($parameters));
    }

    /**
     * @param GetOrganisation $parameters
     * @return GetOrganisationResponse
     */
    public function GetOrganisation(GetOrganisation $parameters)
    {
      return $this->__soapCall('GetOrganisation', array($parameters));
    }

}
