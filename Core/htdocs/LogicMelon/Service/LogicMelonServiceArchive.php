<?php
/**
 * File for class LogicMelonServiceArchive
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceArchive originally named Archive
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceArchive extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named ArchiveAdvert
     * Documentation : <a name='ArchiveAdvert'></a><p>Provides a mechanism to archive an advert and abort future postings. If you want to close an advert on portals and other media that support this please use the CloseAdvert method.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructArchiveAdvert $_logicMelonStructArchiveAdvert
     * @return LogicMelonStructArchiveAdvertResponse
     */
    public function ArchiveAdvert(LogicMelonStructArchiveAdvert $_logicMelonStructArchiveAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ArchiveAdvert($_logicMelonStructArchiveAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named ArchiveAdvertWithFilters
     * Documentation : <a name='ArchiveAdvertWithFilters'></a><p>Provides a mechanism to archive an advert and abort future postings. If you want to close an advert on portals and other media that support this please use the CloseAdvert method.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructArchiveAdvertWithFilters $_logicMelonStructArchiveAdvertWithFilters
     * @return LogicMelonStructArchiveAdvertWithFiltersResponse
     */
    public function ArchiveAdvertWithFilters(LogicMelonStructArchiveAdvertWithFilters $_logicMelonStructArchiveAdvertWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ArchiveAdvertWithFilters($_logicMelonStructArchiveAdvertWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructArchiveAdvertResponse|LogicMelonStructArchiveAdvertWithFiltersResponse
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
