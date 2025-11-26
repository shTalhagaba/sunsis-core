<?php
/**
 * File for class MIAPServiceVerify
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceVerify originally named Verify
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceVerify extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named verifyLearner
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructVerifyLearnerRqst $_mIAPStructVerifyLearnerRqst
     * @return MIAPStructVerifyLearnerResp
     */
    public function verifyLearner(MIAPStructVerifyLearnerRqst $_mIAPStructVerifyLearnerRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->verifyLearner($_mIAPStructVerifyLearnerRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructVerifyLearnerResp
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
