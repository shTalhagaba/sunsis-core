<?php
/**
 * File for class LRSServiceSave
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceSave originally named Save
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceSave extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named SaveRtaQuery
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructSaveRtaQuery $_lRSStructSaveRtaQuery
     * @return LRSStructSaveRtaQueryResponse
     */
    public function SaveRtaQuery(LRSStructSaveRtaQuery $_lRSStructSaveRtaQuery)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->SaveRtaQuery($_lRSStructSaveRtaQuery));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructSaveRtaQueryResponse
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
