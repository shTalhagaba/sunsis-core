<?php
/**
 * File for class LRSServiceExecute
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceExecute originally named Execute
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceExecute extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named ExecuteRtaQuery
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructExecuteRtaQuery $_lRSStructExecuteRtaQuery
     * @return LRSStructExecuteRtaQueryResponse
     */
    public function ExecuteRtaQuery(LRSStructExecuteRtaQuery $_lRSStructExecuteRtaQuery)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ExecuteRtaQuery($_lRSStructExecuteRtaQuery));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named ExecuteRoCQuery
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructExecuteRoCQuery $_lRSStructExecuteRoCQuery
     * @return LRSStructExecuteRoCQueryResponse
     */
    public function ExecuteRoCQuery(LRSStructExecuteRoCQuery $_lRSStructExecuteRoCQuery)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ExecuteRoCQuery($_lRSStructExecuteRoCQuery));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructExecuteRoCQueryResponse|LRSStructExecuteRtaQueryResponse
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
