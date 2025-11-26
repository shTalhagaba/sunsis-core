<?php
/**
 * File for class LogicMelonServiceDeliver
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceDeliver originally named Deliver
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceDeliver extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named DeliverAdvert
     * Documentation : <a name='DeliverAdvert'></a><p>Validate an existing advert against a supplied set of destinations and mark for delivery if valid.</p><ul><li><strong>Destinations</strong> Required list of job board destinations as either numeric id's or string identifiers.</li><li><strong>FuturePostDateTimeInUtc</strong> An optional Date and Time components in UTC of when to deliver the advert (default is ASAP).</li><li><strong>sAdvertID, sAdvertReference, sAdvertIdentifier</strong> Locate the existing advert data already stored in the database.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructDeliverAdvert $_logicMelonStructDeliverAdvert
     * @return LogicMelonStructDeliverAdvertResponse
     */
    public function DeliverAdvert(LogicMelonStructDeliverAdvert $_logicMelonStructDeliverAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->DeliverAdvert($_logicMelonStructDeliverAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructDeliverAdvertResponse
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
