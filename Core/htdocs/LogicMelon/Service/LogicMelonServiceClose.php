<?php
/**
 * File for class LogicMelonServiceClose
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceClose originally named Close
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceClose extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named CloseAdvert
     * Documentation : <a name='CloseAdvert'></a><p>Provides a mechanism to search for an advert and request closure of the advert on job boards that support this feature.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p><ul><li><strong>Destinations</strong> Optionally a comma separated list of job board destinations as either numeric id's or string identifiers. <strong>If not specified requests removals on all support destinations.</strong></li><li><strong>sArchive</strong> Optionally indicate if the advert should be archived on the local system as well (defaults to false)</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructCloseAdvert $_logicMelonStructCloseAdvert
     * @return LogicMelonStructCloseAdvertResponse
     */
    public function CloseAdvert(LogicMelonStructCloseAdvert $_logicMelonStructCloseAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->CloseAdvert($_logicMelonStructCloseAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named CloseAdvertWithFilters
     * Documentation : <a name='CloseAdvertWithFilters'></a><p>Provides a mechanism to search for an advert and request closure of the advert on job boards that support this feature. Allows extra search Filters to be included</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p><ul><li><strong>Destinations</strong> Optionally a list of job board destinations as either numeric id's or string identifiers. <strong>If not specified requests removals on all support destinations and closes the advert.</strong></li><li><strong>sArchive</strong> Optionally, when destinations are specified, indicate if the advert should be archived on the local system as well (defaults to false)</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructCloseAdvertWithFilters $_logicMelonStructCloseAdvertWithFilters
     * @return LogicMelonStructCloseAdvertWithFiltersResponse
     */
    public function CloseAdvertWithFilters(LogicMelonStructCloseAdvertWithFilters $_logicMelonStructCloseAdvertWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->CloseAdvertWithFilters($_logicMelonStructCloseAdvertWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructCloseAdvertResponse|LogicMelonStructCloseAdvertWithFiltersResponse
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
