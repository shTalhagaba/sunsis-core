<?php
/**
 * File for class LogicMelonStructArrayOfInt
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfInt originally named ArrayOfInt
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfInt extends LogicMelonWsdlClass
{
    /**
     * The int
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * @var int
     */
    public $int;
    /**
     * Constructor method for ArrayOfInt
     * @see parent::__construct()
     * @param int $_int
     * @return LogicMelonStructArrayOfInt
     */
    public function __construct($_int = NULL)
    {
        parent::__construct(array('int'=>$_int),false);
    }
    /**
     * Get int value
     * @return int|null
     */
    public function getInt()
    {
        return $this->int;
    }
    /**
     * Set int value
     * @param int $_int the int
     * @return int
     */
    public function setInt($_int)
    {
        return ($this->int = $_int);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return int
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return int
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return int
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return int
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return int
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string int
     */
    public function getAttributeName()
    {
        return 'int';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfInt
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
