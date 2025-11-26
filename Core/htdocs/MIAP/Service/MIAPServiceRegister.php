<?php
/**
 * File for class MIAPServiceRegister
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceRegister originally named Register
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceRegister extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named registerSingleLearner
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructRegisterSingleLearnerRqst $_mIAPStructRegisterSingleLearnerRqst
     * @return MIAPStructRegisterSingleLearnerResp
     */
    public function registerSingleLearner(MIAPStructRegisterSingleLearnerRqst $_mIAPStructRegisterSingleLearnerRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->registerSingleLearner($_mIAPStructRegisterSingleLearnerRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructRegisterSingleLearnerResp
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
