<?php
/**
 * File for class LogicMelonServiceTrack
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceTrack originally named Track
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceTrack extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named TrackAdvert
     * Documentation : <a name='TrackAdvert'></a><p>Provides a mechanism to search for an advert and getting tracking information on the delivery.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p><ul><li><strong>Destinations</strong> Optionally filter the tracking results by a comma separated list of job board destinations as either numeric id's or string identifiers.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructTrackAdvert $_logicMelonStructTrackAdvert
     * @return LogicMelonStructTrackAdvertResponse
     */
    public function TrackAdvert(LogicMelonStructTrackAdvert $_logicMelonStructTrackAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->TrackAdvert($_logicMelonStructTrackAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named TrackAdvertWithFilters
     * Documentation : <a name='TrackAdvertWithFilters'></a><p>Provides a mechanism to search for an advert and getting tracking information on the delivery. Allows extra search Filters to be included</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p><ul><li><strong>Destinations</strong> Optionally filter the tracking results by a list of job board destinations as either numeric id's or string identifiers.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructTrackAdvertWithFilters $_logicMelonStructTrackAdvertWithFilters
     * @return LogicMelonStructTrackAdvertWithFiltersResponse
     */
    public function TrackAdvertWithFilters(LogicMelonStructTrackAdvertWithFilters $_logicMelonStructTrackAdvertWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->TrackAdvertWithFilters($_logicMelonStructTrackAdvertWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructTrackAdvertResponse|LogicMelonStructTrackAdvertWithFiltersResponse
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
