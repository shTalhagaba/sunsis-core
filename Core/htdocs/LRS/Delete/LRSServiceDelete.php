<?php
/**
 * File for class LRSServiceDelete
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceDelete originally named Delete
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceDelete extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named DeleteSavedRtaQuery
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructDeleteSavedRtaQuery $_lRSStructDeleteSavedRtaQuery
     * @return LRSStructDeleteSavedRtaQueryResponse
     */
    public function DeleteSavedRtaQuery(LRSStructDeleteSavedRtaQuery $_lRSStructDeleteSavedRtaQuery)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->DeleteSavedRtaQuery($_lRSStructDeleteSavedRtaQuery));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named DeleteDataChallenge
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructDeleteDataChallenge $_lRSStructDeleteDataChallenge
     * @return LRSStructDeleteDataChallengeResponse
     */
    public function DeleteDataChallenge(LRSStructDeleteDataChallenge $_lRSStructDeleteDataChallenge)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->DeleteDataChallenge($_lRSStructDeleteDataChallenge));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named DeleteSnapshot
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructDeleteSnapshot $_lRSStructDeleteSnapshot
     * @return LRSStructDeleteSnapshotResponse
     */
    public function DeleteSnapshot(LRSStructDeleteSnapshot $_lRSStructDeleteSnapshot)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->DeleteSnapshot($_lRSStructDeleteSnapshot));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructDeleteDataChallengeResponse|LRSStructDeleteSavedRtaQueryResponse|LRSStructDeleteSnapshotResponse
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
