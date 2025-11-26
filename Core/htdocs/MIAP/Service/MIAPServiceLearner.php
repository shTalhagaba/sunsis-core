<?php
/**
 * File for class MIAPServiceLearner
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceLearner originally named Learner
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceLearner extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named learnerByULN
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructLearnerByULNRqst $_mIAPStructLearnerByULNRqst
     * @return MIAPStructFindLearnerResp
     */
    public function  learnerByULN(MIAPStructLearnerByULNRqst $_mIAPStructLearnerByULNRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->learnerByULN($_mIAPStructLearnerByULNRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named learnerByDemographics
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructLearnerByDemographicsRqst $_mIAPStructLearnerByDemographicsRqst
     * @return MIAPStructFindLearnerResp
     */
    public function learnerByDemographics(MIAPStructLearnerByDemographicsRqst $_mIAPStructLearnerByDemographicsRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->learnerByDemographics($_mIAPStructLearnerByDemographicsRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructFindLearnerResp
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
