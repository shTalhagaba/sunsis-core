<?php
/**
 * File for class MIAPServiceUpdate
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceUpdate originally named Update
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceUpdate extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named updateSingleLearner
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructUpdateLearnerRqst $_mIAPStructUpdateLearnerRqst
     * @return MIAPStructUpdateLearnerResp
     */
    public function updateSingleLearner(MIAPStructUpdateLearnerRqst $_mIAPStructUpdateLearnerRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->updateSingleLearner($_mIAPStructUpdateLearnerRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructUpdateLearnerResp
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
