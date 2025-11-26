<?php
/**
 * File for class LogicMelonStructValidateAdvertValuesResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructValidateAdvertValuesResponse originally named ValidateAdvertValuesResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructValidateAdvertValuesResponse extends LogicMelonWsdlClass
{
    /**
     * The ValidateAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var LogicMelonStructValidateAdvertResult
     */
    public $ValidateAdvertResult;
    /**
     * Constructor method for ValidateAdvertValuesResponse
     * @see parent::__construct()
     * @param LogicMelonStructValidateAdvertResult $_validateAdvertResult
     * @return LogicMelonStructValidateAdvertValuesResponse
     */
    public function __construct($_validateAdvertResult)
    {
        parent::__construct(array('ValidateAdvertResult'=>$_validateAdvertResult),false);
    }
    /**
     * Get ValidateAdvertResult value
     * @return LogicMelonStructValidateAdvertResult
     */
    public function getValidateAdvertResult()
    {
        return $this->ValidateAdvertResult;
    }
    /**
     * Set ValidateAdvertResult value
     * @param LogicMelonStructValidateAdvertResult $_validateAdvertResult the ValidateAdvertResult
     * @return LogicMelonStructValidateAdvertResult
     */
    public function setValidateAdvertResult($_validateAdvertResult)
    {
        return ($this->ValidateAdvertResult = $_validateAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructValidateAdvertValuesResponse
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
