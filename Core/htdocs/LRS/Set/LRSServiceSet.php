<?php
/**
 * File for class LRSServiceSet
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSServiceSet originally named Set
 * @package LRS
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSServiceSet extends LRSWsdlClass
{
    /**
     * Method to call the operation originally named SetNotification
     * @uses LRSWsdlClass::getSoapClient()
     * @uses LRSWsdlClass::setResult()
     * @uses LRSWsdlClass::saveLastError()
     * @param LRSStructSetNotification $_lRSStructSetNotification
     * @return LRSStructSetNotificationResponse
     */
    public function SetNotification(LRSStructSetNotification $_lRSStructSetNotification)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->SetNotification($_lRSStructSetNotification));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LRSWsdlClass::getResult()
     * @return LRSStructSetNotificationResponse
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
