<?php
/**
 * File for class LogicMelonStructArrayOfCPostFeed
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfCPostFeed originally named ArrayOfCPostFeed
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfCPostFeed extends LogicMelonWsdlClass
{
    /**
     * The CPostFeed
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructCPostFeed
     */
    public $CPostFeed;
    /**
     * Constructor method for ArrayOfCPostFeed
     * @see parent::__construct()
     * @param LogicMelonStructCPostFeed $_cPostFeed
     * @return LogicMelonStructArrayOfCPostFeed
     */
    public function __construct($_cPostFeed = NULL)
    {
        parent::__construct(array('CPostFeed'=>$_cPostFeed),false);
    }
    /**
     * Get CPostFeed value
     * @return LogicMelonStructCPostFeed|null
     */
    public function getCPostFeed()
    {
        return $this->CPostFeed;
    }
    /**
     * Set CPostFeed value
     * @param LogicMelonStructCPostFeed $_cPostFeed the CPostFeed
     * @return LogicMelonStructCPostFeed
     */
    public function setCPostFeed($_cPostFeed)
    {
        return ($this->CPostFeed = $_cPostFeed);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructCPostFeed
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructCPostFeed
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructCPostFeed
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructCPostFeed
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructCPostFeed
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string CPostFeed
     */
    public function getAttributeName()
    {
        return 'CPostFeed';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfCPostFeed
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
