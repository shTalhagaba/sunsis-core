<?php
/**
 * File for class MIAPServiceGet
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceGet originally named Get
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceGet extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named getBatchRegistrationOutput
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructBatchOutputRqst $_mIAPStructBatchOutputRqst
     * @return MIAPStructBatchOutputResp
     */
    public function getBatchRegistrationOutput(MIAPStructBatchOutputRqst $_mIAPStructBatchOutputRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->getBatchRegistrationOutput($_mIAPStructBatchOutputRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named getVerifyBatchOutput
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructVerifyBatchOutputRqst $_mIAPStructVerifyBatchOutputRqst
     * @return MIAPStructVerifyBatchOutputResp
     */
    public function getVerifyBatchOutput(MIAPStructVerifyBatchOutputRqst $_mIAPStructVerifyBatchOutputRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->getVerifyBatchOutput($_mIAPStructVerifyBatchOutputRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named getMergedUnmergedULNsReport
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructULNReportRqst $_mIAPStructULNReportRqst
     * @return MIAPStructULNReportResp
     */
    public function getMergedUnmergedULNsReport(MIAPStructULNReportRqst $_mIAPStructULNReportRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->getMergedUnmergedULNsReport($_mIAPStructULNReportRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named getDeletedULNsReport
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructULNReportRqst $_mIAPStructULNReportRqst
     * @return MIAPStructULNReportResp
     */
    public function getDeletedULNsReport(MIAPStructULNReportRqst $_mIAPStructULNReportRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->getDeletedULNsReport($_mIAPStructULNReportRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named getLearnerRecord
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructLearnerRecordRqst $_mIAPStructLearnerRecordRqst
     * @return MIAPStructLearnerRecordResp
     */
    public function getLearnerRecord(MIAPStructLearnerRecordRqst $_mIAPStructLearnerRecordRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->getLearnerRecord($_mIAPStructLearnerRecordRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructBatchOutputResp|MIAPStructLearnerRecordResp|MIAPStructULNReportResp|MIAPStructVerifyBatchOutputResp
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
