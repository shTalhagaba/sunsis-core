<?php
/**
 * File for class LogicMelonStructGetAdvertWithValuesResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertWithValuesResponse originally named GetAdvertWithValuesResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertWithValuesResponse extends LogicMelonWsdlClass
{
    /**
     * The GetAdvertWithValuesResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvertWithValues
     */
    public $GetAdvertWithValuesResult;
    /**
     * Constructor method for GetAdvertWithValuesResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIAdvertWithValues $_getAdvertWithValuesResult
     * @return LogicMelonStructGetAdvertWithValuesResponse
     */
    public function __construct($_getAdvertWithValuesResult = NULL)
    {
        parent::__construct(array('GetAdvertWithValuesResult'=>($_getAdvertWithValuesResult instanceof LogicMelonStructArrayOfAPIAdvertWithValues)?$_getAdvertWithValuesResult:new LogicMelonStructArrayOfAPIAdvertWithValues($_getAdvertWithValuesResult)),false);
    }
    /**
     * Get GetAdvertWithValuesResult value
     * @return LogicMelonStructArrayOfAPIAdvertWithValues|null
     */
    public function getGetAdvertWithValuesResult()
    {
        return $this->GetAdvertWithValuesResult;
    }
    /**
     * Set GetAdvertWithValuesResult value
     * @param LogicMelonStructArrayOfAPIAdvertWithValues $_getAdvertWithValuesResult the GetAdvertWithValuesResult
     * @return LogicMelonStructArrayOfAPIAdvertWithValues
     */
    public function setGetAdvertWithValuesResult($_getAdvertWithValuesResult)
    {
        return ($this->GetAdvertWithValuesResult = $_getAdvertWithValuesResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertWithValuesResponse
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
