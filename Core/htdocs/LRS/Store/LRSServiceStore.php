<?php
/**
 * File for class LRSServiceStore
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceStore originally named Store
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceStore extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named StoreLearnerKey
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructStoreLearnerKey $_lRSStructStoreLearnerKey
     * @return LRSStructStoreLearnerKeyResponse
     */
    public function StoreLearnerKey(LRSStructStoreLearnerKey $_lRSStructStoreLearnerKey)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->StoreLearnerKey($_lRSStructStoreLearnerKey));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructStoreLearnerKeyResponse
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
