<?php
/**
 * File for class LogicMelonStructArrayOfAPIPosting
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfAPIPosting originally named ArrayOfAPIPosting
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfAPIPosting extends LogicMelonWsdlClass
{
    /**
     * The APIPosting
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructAPIPosting
     */
    public $APIPosting;
    /**
     * Constructor method for ArrayOfAPIPosting
     * @see parent::__construct()
     * @param LogicMelonStructAPIPosting $_aPIPosting
     * @return LogicMelonStructArrayOfAPIPosting
     */
    public function __construct($_aPIPosting = NULL)
    {
        parent::__construct(array('APIPosting'=>$_aPIPosting),false);
    }
    /**
     * Get APIPosting value
     * @return LogicMelonStructAPIPosting|null
     */
    public function getAPIPosting()
    {
        return $this->APIPosting;
    }
    /**
     * Set APIPosting value
     * @param LogicMelonStructAPIPosting $_aPIPosting the APIPosting
     * @return LogicMelonStructAPIPosting
     */
    public function setAPIPosting($_aPIPosting)
    {
        return ($this->APIPosting = $_aPIPosting);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructAPIPosting
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructAPIPosting
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructAPIPosting
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructAPIPosting
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructAPIPosting
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string APIPosting
     */
    public function getAttributeName()
    {
        return 'APIPosting';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfAPIPosting
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
