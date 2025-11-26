<?php
/**
 * File for class LogicMelonStructArrayOfValidateFieldResult
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfValidateFieldResult originally named ArrayOfValidateFieldResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfValidateFieldResult extends LogicMelonWsdlClass
{
    /**
     * The ValidateFieldResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructValidateFieldResult
     */
    public $ValidateFieldResult;
    /**
     * Constructor method for ArrayOfValidateFieldResult
     * @see parent::__construct()
     * @param LogicMelonStructValidateFieldResult $_validateFieldResult
     * @return LogicMelonStructArrayOfValidateFieldResult
     */
    public function __construct($_validateFieldResult = NULL)
    {
        parent::__construct(array('ValidateFieldResult'=>$_validateFieldResult),false);
    }
    /**
     * Get ValidateFieldResult value
     * @return LogicMelonStructValidateFieldResult|null
     */
    public function getValidateFieldResult()
    {
        return $this->ValidateFieldResult;
    }
    /**
     * Set ValidateFieldResult value
     * @param LogicMelonStructValidateFieldResult $_validateFieldResult the ValidateFieldResult
     * @return LogicMelonStructValidateFieldResult
     */
    public function setValidateFieldResult($_validateFieldResult)
    {
        return ($this->ValidateFieldResult = $_validateFieldResult);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructValidateFieldResult
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructValidateFieldResult
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructValidateFieldResult
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructValidateFieldResult
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructValidateFieldResult
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string ValidateFieldResult
     */
    public function getAttributeName()
    {
        return 'ValidateFieldResult';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfValidateFieldResult
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
