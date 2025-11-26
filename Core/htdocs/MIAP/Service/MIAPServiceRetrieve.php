<?php
/**
 * File for class MIAPServiceRetrieve
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPServiceRetrieve originally named Retrieve
 * @package MIAP
 * @subpackage Services
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPServiceRetrieve extends MIAPWsdlClass
{
    /**
     * Method to call the operation originally named retrieveULNs
     * @uses MIAPWsdlClass::getSoapClient()
     * @uses MIAPWsdlClass::setResult()
     * @uses MIAPWsdlClass::saveLastError()
     * @param MIAPStructRetrieveULNsRqst $_mIAPStructRetrieveULNsRqst
     * @return MIAPStructRetrieveULNsResp
     */
    public function retrieveULNs(MIAPStructRetrieveULNsRqst $_mIAPStructRetrieveULNsRqst)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->retrieveULNs($_mIAPStructRetrieveULNsRqst));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see MIAPWsdlClass::getResult()
     * @return MIAPStructRetrieveULNsResp
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
