<?php
/**
 * File for class LRSServiceCreate
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceCreate originally named Create
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceCreate extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named CreateDataChallenge
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructCreateDataChallenge $_lRSStructCreateDataChallenge
     * @return LRSStructCreateDataChallengeResponse
     */
    public function CreateDataChallenge(LRSStructCreateDataChallenge $_lRSStructCreateDataChallenge)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->CreateDataChallenge($_lRSStructCreateDataChallenge));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named CreateOrModifySnapshot
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructCreateOrModifySnapshot $_lRSStructCreateOrModifySnapshot
     * @return LRSStructCreateOrModifySnapshotResponse
     */
    public function CreateOrModifySnapshot(LRSStructCreateOrModifySnapshot $_lRSStructCreateOrModifySnapshot)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->CreateOrModifySnapshot($_lRSStructCreateOrModifySnapshot));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructCreateDataChallengeResponse|LRSStructCreateOrModifySnapshotResponse
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
