<?php
/**
 * File for class LogicMelonServiceExpress
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceExpress originally named Express
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceExpress extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named ExpressPostAdvert
     * Documentation : <a name='ExpressPostAdvert'></a><p>Provides a mechanism to search for an advert and request for it to be delivered unaltered to job boards previously posted to.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p><ul><li><strong>Destinations</strong> Optionally a comma separated list of job board destinations as either numeric id's or string identifiers. <strong>If not specified requests removals on all support destinations.</strong></li><li><strong>sArchive</strong> Optionally indicate if the advert should be archived on the local system as well (defaults to false)</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructExpressPostAdvert $_logicMelonStructExpressPostAdvert
     * @return LogicMelonStructExpressPostAdvertResponse
     */
    public function ExpressPostAdvert(LogicMelonStructExpressPostAdvert $_logicMelonStructExpressPostAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ExpressPostAdvert($_logicMelonStructExpressPostAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named ExpressPostAdvertWithFilters
     * Documentation : <a name='ExpressPostAdvertWithFilters'></a><p>Provides a mechanism to search for an advert and request for it to be delivered unaltered to job boards previously posted to. Allows extra search Filters to be included</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p><ul><li><strong>Destinations</strong> Optionally a list of job board destinations as either numeric id's or string identifiers. <strong>If not specified requests removals on all support destinations.</strong></li><li><strong>sArchive</strong> Optionally indicate if the advert should be archived on the local system as well (defaults to false)</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructExpressPostAdvertWithFilters $_logicMelonStructExpressPostAdvertWithFilters
     * @return LogicMelonStructExpressPostAdvertWithFiltersResponse
     */
    public function ExpressPostAdvertWithFilters(LogicMelonStructExpressPostAdvertWithFilters $_logicMelonStructExpressPostAdvertWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ExpressPostAdvertWithFilters($_logicMelonStructExpressPostAdvertWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructExpressPostAdvertResponse|LogicMelonStructExpressPostAdvertWithFiltersResponse
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
