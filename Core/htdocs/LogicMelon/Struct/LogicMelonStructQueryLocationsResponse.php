<?php
/**
 * File for class LogicMelonStructQueryLocationsResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructQueryLocationsResponse originally named QueryLocationsResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructQueryLocationsResponse extends LogicMelonWsdlClass
{
    /**
     * The QueryLocationsResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfCQueryLocation
     */
    public $QueryLocationsResult;
    /**
     * Constructor method for QueryLocationsResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfCQueryLocation $_queryLocationsResult
     * @return LogicMelonStructQueryLocationsResponse
     */
    public function __construct($_queryLocationsResult = NULL)
    {
        parent::__construct(array('QueryLocationsResult'=>($_queryLocationsResult instanceof LogicMelonStructArrayOfCQueryLocation)?$_queryLocationsResult:new LogicMelonStructArrayOfCQueryLocation($_queryLocationsResult)),false);
    }
    /**
     * Get QueryLocationsResult value
     * @return LogicMelonStructArrayOfCQueryLocation|null
     */
    public function getQueryLocationsResult()
    {
        return $this->QueryLocationsResult;
    }
    /**
     * Set QueryLocationsResult value
     * @param LogicMelonStructArrayOfCQueryLocation $_queryLocationsResult the QueryLocationsResult
     * @return LogicMelonStructArrayOfCQueryLocation
     */
    public function setQueryLocationsResult($_queryLocationsResult)
    {
        return ($this->QueryLocationsResult = $_queryLocationsResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructQueryLocationsResponse
     */
    public static function __set_state(array $_array,$_className = __CLASS__)
    {
        return parent::__set_state($_array,$_className);
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
