<?php
/**
 * File for class LRSServiceLearner
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceLearner originally named Learner
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceLearner extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named LearnerByUln
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructLearnerByUln $_lRSStructLearnerByUln
     * @return LRSStructLearnerByUlnResponse
     */
    public function LearnerByUln(LRSStructLearnerByUln $_lRSStructLearnerByUln)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->LearnerByUln($_lRSStructLearnerByUln));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructLearnerByUlnResponse
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
