<?php
/**
 * File for class LRSServiceList
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceList originally named List
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceList extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named ListSavedRtaQueries
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructListSavedRtaQueries $_lRSStructListSavedRtaQueries
     * @return LRSStructListSavedRtaQueriesResponse
     */
    public function ListSavedRtaQueries(LRSStructListSavedRtaQueries $_lRSStructListSavedRtaQueries)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ListSavedRtaQueries($_lRSStructListSavedRtaQueries));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named ListDataChallenge
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructListDataChallenge $_lRSStructListDataChallenge
     * @return LRSStructListDataChallengeResponse
     */
    public function ListDataChallenge(LRSStructListDataChallenge $_lRSStructListDataChallenge)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ListDataChallenge($_lRSStructListDataChallenge));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named ListNotificationStatus
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructListNotificationStatus $_lRSStructListNotificationStatus
     * @return LRSStructListNotificationStatusResponse
     */
    public function ListNotificationStatus(LRSStructListNotificationStatus $_lRSStructListNotificationStatus)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ListNotificationStatus($_lRSStructListNotificationStatus));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named ListSnapshots
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructListSnapshots $_lRSStructListSnapshots
     * @return LRSStructListSnapshotsResponse
     */
    public function ListSnapshots(LRSStructListSnapshots $_lRSStructListSnapshots)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ListSnapshots($_lRSStructListSnapshots));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructListDataChallengeResponse|LRSStructListNotificationStatusResponse|LRSStructListSavedRtaQueriesResponse|LRSStructListSnapshotsResponse
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
