<?php
/**
 * File for class LRSStructArrayOfPlrSnapshotEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfPlrSnapshotEvent originally named ArrayOfPlrSnapshotEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfPlrSnapshotEvent extends LRSWsdlClass
{
    /**
     * The PlrSnapshotEvent
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructPlrSnapshotEvent
     */
    public $PlrSnapshotEvent;
    /**
     * Constructor method for ArrayOfPlrSnapshotEvent
     * @see parent::__construct()
     * @param LRSStructPlrSnapshotEvent $_plrSnapshotEvent
     * @return LRSStructArrayOfPlrSnapshotEvent
     */
    public function __construct($_plrSnapshotEvent = NULL)
    {
        parent::__construct(array('PlrSnapshotEvent'=>$_plrSnapshotEvent),false);
    }
    /**
     * Get PlrSnapshotEvent value
     * @return LRSStructPlrSnapshotEvent|null
     */
    public function getPlrSnapshotEvent()
    {
        return $this->PlrSnapshotEvent;
    }
    /**
     * Set PlrSnapshotEvent value
     * @param LRSStructPlrSnapshotEvent $_plrSnapshotEvent the PlrSnapshotEvent
     * @return LRSStructPlrSnapshotEvent
     */
    public function setPlrSnapshotEvent($_plrSnapshotEvent)
    {
        return ($this->PlrSnapshotEvent = $_plrSnapshotEvent);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructPlrSnapshotEvent
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructPlrSnapshotEvent
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructPlrSnapshotEvent
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructPlrSnapshotEvent
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructPlrSnapshotEvent
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string PlrSnapshotEvent
     */
    public function getAttributeName()
    {
        return 'PlrSnapshotEvent';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfPlrSnapshotEvent
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
