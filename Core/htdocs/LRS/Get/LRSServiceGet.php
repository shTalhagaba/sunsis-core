<?php
/**
 * File for class LRSServiceGet
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceGet originally named Get
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceGet extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named GetLearnerLearningEvents
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructGetLearnerLearningEvents $_lRSStructGetLearnerLearningEvents
     * @return LRSStructGetLearnerLearningEventsResponse
     */
    public function GetLearnerLearningEvents(LRSStructGetLearnerLearningEvents $_lRSStructGetLearnerLearningEvents)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetLearnerLearningEvents($_lRSStructGetLearnerLearningEvents));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetLearnerRecord
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructGetLearnerRecord $_lRSStructGetLearnerRecord
     * @return LRSStructGetLearnerRecordResponse
     */
    public function GetLearnerRecord(LRSStructGetLearnerRecord $_lRSStructGetLearnerRecord)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetLearnerRecord($_lRSStructGetLearnerRecord));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetMyLearningEvents
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructGetMyLearningEvents $_lRSStructGetMyLearningEvents
     * @return LRSStructGetMyLearningEventsResponse
     */
    public function GetMyLearningEvents(LRSStructGetMyLearningEvents $_lRSStructGetMyLearningEvents)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetMyLearningEvents($_lRSStructGetMyLearningEvents));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetOrganisation
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructGetOrganisation $_lRSStructGetOrganisation
     * @return LRSStructGetOrganisationResponse
     */
    public function GetOrganisation(LRSStructGetOrganisation $_lRSStructGetOrganisation)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetOrganisation($_lRSStructGetOrganisation));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructGetLearnerLearningEventsResponse|LRSStructGetLearnerRecordResponse|LRSStructGetMyLearningEventsResponse|LRSStructGetOrganisationResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
    /**
     * Method returning the class name
     * @return string __CLASS__
     */
    public function __toString()
    {
        return __CLASS__;
    }
}
