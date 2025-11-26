<?php
/**
 * File for class LogicMelonStructGetCurrencyResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetCurrencyResponse originally named GetCurrencyResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetCurrencyResponse extends LogicMelonWsdlClass
{
    /**
     * The GetCurrencyResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfGetValue
     */
    public $GetCurrencyResult;
    /**
     * Constructor method for GetCurrencyResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfGetValue $_getCurrencyResult
     * @return LogicMelonStructGetCurrencyResponse
     */
    public function __construct($_getCurrencyResult = NULL)
    {
        parent::__construct(array('GetCurrencyResult'=>($_getCurrencyResult instanceof LogicMelonStructArrayOfGetValue)?$_getCurrencyResult:new LogicMelonStructArrayOfGetValue($_getCurrencyResult)),false);
    }
    /**
     * Get GetCurrencyResult value
     * @return LogicMelonStructArrayOfGetValue|null
     */
    public function getGetCurrencyResult()
    {
        return $this->GetCurrencyResult;
    }
    /**
     * Set GetCurrencyResult value
     * @param LogicMelonStructArrayOfGetValue $_getCurrencyResult the GetCurrencyResult
     * @return LogicMelonStructArrayOfGetValue
     */
    public function setGetCurrencyResult($_getCurrencyResult)
    {
        return ($this->GetCurrencyResult = $_getCurrencyResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetCurrencyResponse
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
