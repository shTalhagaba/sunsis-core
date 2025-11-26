<?php
/**
 * File for class LogicMelonStructArrayOfAPIAdvertWithPostings
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfAPIAdvertWithPostings originally named ArrayOfAPIAdvertWithPostings
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfAPIAdvertWithPostings extends LogicMelonWsdlClass
{
    /**
     * The APIAdvertWithPostings
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $APIAdvertWithPostings;
    /**
     * Constructor method for ArrayOfAPIAdvertWithPostings
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_aPIAdvertWithPostings
     * @return LogicMelonStructArrayOfAPIAdvertWithPostings
     */
    public function __construct($_aPIAdvertWithPostings = NULL)
    {
        parent::__construct(array('APIAdvertWithPostings'=>$_aPIAdvertWithPostings),false);
    }
    /**
     * Get APIAdvertWithPostings value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getAPIAdvertWithPostings()
    {
        return $this->APIAdvertWithPostings;
    }
    /**
     * Set APIAdvertWithPostings value
     * @param LogicMelonStructAPIAdvertWithPostings $_aPIAdvertWithPostings the APIAdvertWithPostings
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setAPIAdvertWithPostings($_aPIAdvertWithPostings)
    {
        return ($this->APIAdvertWithPostings = $_aPIAdvertWithPostings);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string APIAdvertWithPostings
     */
    public function getAttributeName()
    {
        return 'APIAdvertWithPostings';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfAPIAdvertWithPostings
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
