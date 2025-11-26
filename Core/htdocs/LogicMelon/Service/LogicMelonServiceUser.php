<?php
/**
 * File for class LogicMelonServiceUser
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceUser originally named User
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceUser extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named UserFeedsAndQuota
     * Documentation : <a name='UserFeedsAndQuota'></a><p>Return a set of feeds and quota as configured in the database.</p><ul><li><strong>sUsername, sPassword</strong> Credentials to find a users feed and quota details.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructUserFeedsAndQuota $_logicMelonStructUserFeedsAndQuota
     * @return LogicMelonStructUserFeedsAndQuotaResponse
     */
    public function UserFeedsAndQuota(LogicMelonStructUserFeedsAndQuota $_logicMelonStructUserFeedsAndQuota)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UserFeedsAndQuota($_logicMelonStructUserFeedsAndQuota));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named UserFeedsAndQuotaWithDestinations
     * Documentation : <a name='UserFeedsAndQuotaWithDestinations'></a><p>Return a set of feeds and quota as configured in the database.</p><ul><li><strong>sUsername, sPassword</strong> Credentials to find a users feed and quota details.</li><li><strong>Destinations</strong> Optional list of job board destinations to filter down to as either numeric id's or string identifiers.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructUserFeedsAndQuotaWithDestinations $_logicMelonStructUserFeedsAndQuotaWithDestinations
     * @return LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse
     */
    public function UserFeedsAndQuotaWithDestinations(LogicMelonStructUserFeedsAndQuotaWithDestinations $_logicMelonStructUserFeedsAndQuotaWithDestinations)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UserFeedsAndQuotaWithDestinations($_logicMelonStructUserFeedsAndQuotaWithDestinations));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructUserFeedsAndQuotaResponse|LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse
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
