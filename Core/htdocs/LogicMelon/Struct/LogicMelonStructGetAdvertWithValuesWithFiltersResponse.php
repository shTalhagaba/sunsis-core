<?php
/**
 * File for class LogicMelonStructGetAdvertWithValuesWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertWithValuesWithFiltersResponse originally named GetAdvertWithValuesWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertWithValuesWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The GetAdvertWithValuesWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvertWithValues
     */
    public $GetAdvertWithValuesWithFiltersResult;
    /**
     * Constructor method for GetAdvertWithValuesWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIAdvertWithValues $_getAdvertWithValuesWithFiltersResult
     * @return LogicMelonStructGetAdvertWithValuesWithFiltersResponse
     */
    public function __construct($_getAdvertWithValuesWithFiltersResult = NULL)
    {
        parent::__construct(array('GetAdvertWithValuesWithFiltersResult'=>($_getAdvertWithValuesWithFiltersResult instanceof LogicMelonStructArrayOfAPIAdvertWithValues)?$_getAdvertWithValuesWithFiltersResult:new LogicMelonStructArrayOfAPIAdvertWithValues($_getAdvertWithValuesWithFiltersResult)),false);
    }
    /**
     * Get GetAdvertWithValuesWithFiltersResult value
     * @return LogicMelonStructArrayOfAPIAdvertWithValues|null
     */
    public function getGetAdvertWithValuesWithFiltersResult()
    {
        return $this->GetAdvertWithValuesWithFiltersResult;
    }
    /**
     * Set GetAdvertWithValuesWithFiltersResult value
     * @param LogicMelonStructArrayOfAPIAdvertWithValues $_getAdvertWithValuesWithFiltersResult the GetAdvertWithValuesWithFiltersResult
     * @return LogicMelonStructArrayOfAPIAdvertWithValues
     */
    public function setGetAdvertWithValuesWithFiltersResult($_getAdvertWithValuesWithFiltersResult)
    {
        return ($this->GetAdvertWithValuesWithFiltersResult = $_getAdvertWithValuesWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertWithValuesWithFiltersResponse
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
