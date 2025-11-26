<?php
/**
 * File for class LogicMelonServiceUnarchive
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceUnarchive originally named Unarchive
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceUnarchive extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named UnarchiveAdvert
     * Documentation : <a name='UnarchiveAdvert'></a><p>Provides a mechanism to restore (make visible) an advert on the system.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructUnarchiveAdvert $_logicMelonStructUnarchiveAdvert
     * @return LogicMelonStructUnarchiveAdvertResponse
     */
    public function UnarchiveAdvert(LogicMelonStructUnarchiveAdvert $_logicMelonStructUnarchiveAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UnarchiveAdvert($_logicMelonStructUnarchiveAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named UnarchiveAdvertWithFilters
     * Documentation : <a name='UnarchiveAdvertWithFilters'></a><p>Provides a mechanism to restore (make visible) an advert on the system.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructUnarchiveAdvertWithFilters $_logicMelonStructUnarchiveAdvertWithFilters
     * @return LogicMelonStructUnarchiveAdvertWithFiltersResponse
     */
    public function UnarchiveAdvertWithFilters(LogicMelonStructUnarchiveAdvertWithFilters $_logicMelonStructUnarchiveAdvertWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->UnarchiveAdvertWithFilters($_logicMelonStructUnarchiveAdvertWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructUnarchiveAdvertResponse|LogicMelonStructUnarchiveAdvertWithFiltersResponse
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
