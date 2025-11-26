<?php
/**
 * File for class MIAPServiceSubmit
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceSubmit originally named Submit
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceSubmit extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named submitBatchRegistration
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructBatchRegistrationRqst $_mIAPStructBatchRegistrationRqst
     * @return MIAPStructBatchRegistrationResp
     */
    public function submitBatchRegistration(MIAPStructBatchRegistrationRqst $_mIAPStructBatchRegistrationRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->submitBatchRegistration($_mIAPStructBatchRegistrationRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named submitVerifyBatch
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructVerifyBatchRqst $_mIAPStructVerifyBatchRqst
     * @return MIAPStructVerifyBatchResp
     */
    public function submitVerifyBatch(MIAPStructVerifyBatchRqst $_mIAPStructVerifyBatchRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->submitVerifyBatch($_mIAPStructVerifyBatchRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructBatchRegistrationResp|MIAPStructVerifyBatchResp
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
