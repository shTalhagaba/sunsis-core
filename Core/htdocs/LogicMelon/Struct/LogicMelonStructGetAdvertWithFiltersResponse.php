<?php
/**
 * File for class LogicMelonStructGetAdvertWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertWithFiltersResponse originally named GetAdvertWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The GetAdvertWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvert
     */
    public $GetAdvertWithFiltersResult;
    /**
     * Constructor method for GetAdvertWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIAdvert $_getAdvertWithFiltersResult
     * @return LogicMelonStructGetAdvertWithFiltersResponse
     */
    public function __construct($_getAdvertWithFiltersResult = NULL)
    {
        parent::__construct(array('GetAdvertWithFiltersResult'=>($_getAdvertWithFiltersResult instanceof LogicMelonStructArrayOfAPIAdvert)?$_getAdvertWithFiltersResult:new LogicMelonStructArrayOfAPIAdvert($_getAdvertWithFiltersResult)),false);
    }
    /**
     * Get GetAdvertWithFiltersResult value
     * @return LogicMelonStructArrayOfAPIAdvert|null
     */
    public function getGetAdvertWithFiltersResult()
    {
        return $this->GetAdvertWithFiltersResult;
    }
    /**
     * Set GetAdvertWithFiltersResult value
     * @param LogicMelonStructArrayOfAPIAdvert $_getAdvertWithFiltersResult the GetAdvertWithFiltersResult
     * @return LogicMelonStructArrayOfAPIAdvert
     */
    public function setGetAdvertWithFiltersResult($_getAdvertWithFiltersResult)
    {
        return ($this->GetAdvertWithFiltersResult = $_getAdvertWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertWithFiltersResponse
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
