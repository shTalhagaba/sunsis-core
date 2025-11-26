<?php
/**
 * File for class LogicMelonStructGetAdvertResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertResponse originally named GetAdvertResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertResponse extends LogicMelonWsdlClass
{
    /**
     * The GetAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvert
     */
    public $GetAdvertResult;
    /**
     * Constructor method for GetAdvertResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIAdvert $_getAdvertResult
     * @return LogicMelonStructGetAdvertResponse
     */
    public function __construct($_getAdvertResult = NULL)
    {
        parent::__construct(array('GetAdvertResult'=>($_getAdvertResult instanceof LogicMelonStructArrayOfAPIAdvert)?$_getAdvertResult:new LogicMelonStructArrayOfAPIAdvert($_getAdvertResult)),false);
    }
    /**
     * Get GetAdvertResult value
     * @return LogicMelonStructArrayOfAPIAdvert|null
     */
    public function getGetAdvertResult()
    {
        return $this->GetAdvertResult;
    }
    /**
     * Set GetAdvertResult value
     * @param LogicMelonStructArrayOfAPIAdvert $_getAdvertResult the GetAdvertResult
     * @return LogicMelonStructArrayOfAPIAdvert
     */
    public function setGetAdvertResult($_getAdvertResult)
    {
        return ($this->GetAdvertResult = $_getAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertResponse
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
