<?php
/**
 * File for class LRSServiceRemove
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceRemove originally named Remove
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceRemove extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named RemoveLearnerKey
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructRemoveLearnerKey $_lRSStructRemoveLearnerKey
     * @return LRSStructRemoveLearnerKeyResponse
     */
    public function RemoveLearnerKey(LRSStructRemoveLearnerKey $_lRSStructRemoveLearnerKey)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->RemoveLearnerKey($_lRSStructRemoveLearnerKey));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructRemoveLearnerKeyResponse
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
