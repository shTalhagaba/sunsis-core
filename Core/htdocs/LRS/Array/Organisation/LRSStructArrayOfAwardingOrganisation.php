<?php
/**
 * File for class LRSStructArrayOfAwardingOrganisation
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfAwardingOrganisation originally named ArrayOfAwardingOrganisation
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfAwardingOrganisation extends LRSWsdlClass
{
    /**
     * The AwardingOrganisation
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructAwardingOrganisation
     */
    public $AwardingOrganisation;
    /**
     * Constructor method for ArrayOfAwardingOrganisation
     * @see parent::__construct()
     * @param LRSStructAwardingOrganisation $_awardingOrganisation
     * @return LRSStructArrayOfAwardingOrganisation
     */
    public function __construct($_awardingOrganisation = NULL)
    {
        parent::__construct(array('AwardingOrganisation'=>$_awardingOrganisation),false);
    }
    /**
     * Get AwardingOrganisation value
     * @return LRSStructAwardingOrganisation|null
     */
    public function getAwardingOrganisation()
    {
        return $this->AwardingOrganisation;
    }
    /**
     * Set AwardingOrganisation value
     * @param LRSStructAwardingOrganisation $_awardingOrganisation the AwardingOrganisation
     * @return LRSStructAwardingOrganisation
     */
    public function setAwardingOrganisation($_awardingOrganisation)
    {
        return ($this->AwardingOrganisation = $_awardingOrganisation);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructAwardingOrganisation
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructAwardingOrganisation
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructAwardingOrganisation
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructAwardingOrganisation
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructAwardingOrganisation
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string AwardingOrganisation
     */
    public function getAttributeName()
    {
        return 'AwardingOrganisation';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfAwardingOrganisation
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
