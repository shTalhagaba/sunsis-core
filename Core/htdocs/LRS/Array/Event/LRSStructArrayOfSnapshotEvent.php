<?php
/**
 * File for class LRSStructArrayOfSnapshotEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfSnapshotEvent originally named ArrayOfSnapshotEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfSnapshotEvent extends LRSWsdlClass
{
    /**
     * The SnapshotEvent
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructSnapshotEvent
     */
    public $SnapshotEvent;
    /**
     * Constructor method for ArrayOfSnapshotEvent
     * @see parent::__construct()
     * @param LRSStructSnapshotEvent $_snapshotEvent
     * @return LRSStructArrayOfSnapshotEvent
     */
    public function __construct($_snapshotEvent = NULL)
    {
        parent::__construct(array('SnapshotEvent'=>$_snapshotEvent),false);
    }
    /**
     * Get SnapshotEvent value
     * @return LRSStructSnapshotEvent|null
     */
    public function getSnapshotEvent()
    {
        return $this->SnapshotEvent;
    }
    /**
     * Set SnapshotEvent value
     * @param LRSStructSnapshotEvent $_snapshotEvent the SnapshotEvent
     * @return LRSStructSnapshotEvent
     */
    public function setSnapshotEvent($_snapshotEvent)
    {
        return ($this->SnapshotEvent = $_snapshotEvent);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructSnapshotEvent
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructSnapshotEvent
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructSnapshotEvent
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructSnapshotEvent
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructSnapshotEvent
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string SnapshotEvent
     */
    public function getAttributeName()
    {
        return 'SnapshotEvent';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfSnapshotEvent
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
