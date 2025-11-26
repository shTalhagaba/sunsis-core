<?php
/**
 * File for class LRSServiceUpdate
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceUpdate originally named Update
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceUpdate extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named UpdateLearnerSubsetFields
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructUpdateLearnerSubsetFields $_lRSStructUpdateLearnerSubsetFields
     * @return LRSStructUpdateLearnerSubsetFieldsResponse
     */
    public function UpdateLearnerSubsetFields(LRSStructUpdateLearnerSubsetFields $_lRSStructUpdateLearnerSubsetFields)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UpdateLearnerSubsetFields($_lRSStructUpdateLearnerSubsetFields));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named UpdateLearner
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructUpdateLearner $_lRSStructUpdateLearner
     * @return LRSStructUpdateLearnerResponse
     */
    public function UpdateLearner(LRSStructUpdateLearner $_lRSStructUpdateLearner)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UpdateLearner($_lRSStructUpdateLearner));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named UpdateLearnerByUlnKey
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructUpdateLearnerByUlnKey $_lRSStructUpdateLearnerByUlnKey
     * @return LRSStructUpdateLearnerByUlnKeyResponse
     */
    public function UpdateLearnerByUlnKey(LRSStructUpdateLearnerByUlnKey $_lRSStructUpdateLearnerByUlnKey)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UpdateLearnerByUlnKey($_lRSStructUpdateLearnerByUlnKey));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructUpdateLearnerByUlnKeyResponse|LRSStructUpdateLearnerResponse|LRSStructUpdateLearnerSubsetFieldsResponse
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
