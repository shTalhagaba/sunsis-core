<?php
/**
 * File for class LRSServiceFind
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceFind originally named Find
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceFind extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named FindLearnerByUlnKey
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructFindLearnerByUlnKey $_lRSStructFindLearnerByUlnKey
     * @return LRSStructFindLearnerByUlnKeyResponse
     */
    public function FindLearnerByUlnKey(LRSStructFindLearnerByUlnKey $_lRSStructFindLearnerByUlnKey)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->FindLearnerByUlnKey($_lRSStructFindLearnerByUlnKey));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructFindLearnerByUlnKeyResponse
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
