<?php
/**
 * File for class LogicMelonServiceQuery
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceQuery originally named Query
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceQuery extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named QueryLocations
     * Documentation : <a name='QueryLocations'></a><p>Perform a location query. For a specific location specify LocationValue, or to search specify q (minimum 2 characters) along with a priority or prefix</p><ul><li><strong>q</strong> free text query (e.g. Cam)</li><li><strong>prefix</strong> Results only in a specific country (e.g. US) or county (e.g. GB.EEEC for Cambridgeshire).</li><li><strong>priority</strong> Give higher scores by country (e.g. US) or county (e.g. GB.EEEC for Cambridgeshire).</li><li><em>LocationValue</em> Return the data for a specific known location e.g. GB.EEEC.0460 for Cambridge in Cambridgeshire.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructQueryLocations $_logicMelonStructQueryLocations
     * @return LogicMelonStructQueryLocationsResponse
     */
    public function QueryLocations(LogicMelonStructQueryLocations $_logicMelonStructQueryLocations)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->QueryLocations($_logicMelonStructQueryLocations));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named QueryJobTitle
     * Documentation : <a name='QueryJobTitle'></a><p>Perform a job title query. For a specific location specify LocationValue, or to search specify q (minimum 2 characters) along with a priority or prefix</p><ul><li><strong>q</strong> free text query (e.g. Project)</li><li><em>Industry</em> Return job titles in a specific industry.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructQueryJobTitle $_logicMelonStructQueryJobTitle
     * @return LogicMelonStructQueryJobTitleResponse
     */
    public function QueryJobTitle(LogicMelonStructQueryJobTitle $_logicMelonStructQueryJobTitle)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->QueryJobTitle($_logicMelonStructQueryJobTitle));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructQueryJobTitleResponse|LogicMelonStructQueryLocationsResponse
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
