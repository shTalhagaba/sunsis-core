<?php
/**
 * File for class LRSStructArrayOfPlrAccessEntryResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfPlrAccessEntryResponse originally named ArrayOfPlrAccessEntryResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfPlrAccessEntryResponse extends LRSWsdlClass
{
    /**
     * The PlrAccessEntryResponse
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructPlrAccessEntryResponse
     */
    public $PlrAccessEntryResponse;
    /**
     * Constructor method for ArrayOfPlrAccessEntryResponse
     * @see parent::__construct()
     * @param LRSStructPlrAccessEntryResponse $_plrAccessEntryResponse
     * @return LRSStructArrayOfPlrAccessEntryResponse
     */
    public function __construct($_plrAccessEntryResponse = NULL)
    {
        parent::__construct(array('PlrAccessEntryResponse'=>$_plrAccessEntryResponse),false);
    }
    /**
     * Get PlrAccessEntryResponse value
     * @return LRSStructPlrAccessEntryResponse|null
     */
    public function getPlrAccessEntryResponse()
    {
        return $this->PlrAccessEntryResponse;
    }
    /**
     * Set PlrAccessEntryResponse value
     * @param LRSStructPlrAccessEntryResponse $_plrAccessEntryResponse the PlrAccessEntryResponse
     * @return LRSStructPlrAccessEntryResponse
     */
    public function setPlrAccessEntryResponse($_plrAccessEntryResponse)
    {
        return ($this->PlrAccessEntryResponse = $_plrAccessEntryResponse);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructPlrAccessEntryResponse
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructPlrAccessEntryResponse
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructPlrAccessEntryResponse
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructPlrAccessEntryResponse
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructPlrAccessEntryResponse
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string PlrAccessEntryResponse
     */
    public function getAttributeName()
    {
        return 'PlrAccessEntryResponse';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfPlrAccessEntryResponse
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
