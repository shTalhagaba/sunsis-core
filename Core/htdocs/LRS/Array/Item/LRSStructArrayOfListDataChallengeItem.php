<?php
/**
 * File for class LRSStructArrayOfListDataChallengeItem
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfListDataChallengeItem originally named ArrayOfListDataChallengeItem
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfListDataChallengeItem extends LRSWsdlClass
{
    /**
     * The ListDataChallengeItem
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructListDataChallengeItem
     */
    public $ListDataChallengeItem;
    /**
     * Constructor method for ArrayOfListDataChallengeItem
     * @see parent::__construct()
     * @param LRSStructListDataChallengeItem $_listDataChallengeItem
     * @return LRSStructArrayOfListDataChallengeItem
     */
    public function __construct($_listDataChallengeItem = NULL)
    {
        parent::__construct(array('ListDataChallengeItem'=>$_listDataChallengeItem),false);
    }
    /**
     * Get ListDataChallengeItem value
     * @return LRSStructListDataChallengeItem|null
     */
    public function getListDataChallengeItem()
    {
        return $this->ListDataChallengeItem;
    }
    /**
     * Set ListDataChallengeItem value
     * @param LRSStructListDataChallengeItem $_listDataChallengeItem the ListDataChallengeItem
     * @return LRSStructListDataChallengeItem
     */
    public function setListDataChallengeItem($_listDataChallengeItem)
    {
        return ($this->ListDataChallengeItem = $_listDataChallengeItem);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructListDataChallengeItem
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructListDataChallengeItem
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructListDataChallengeItem
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructListDataChallengeItem
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructListDataChallengeItem
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string ListDataChallengeItem
     */
    public function getAttributeName()
    {
        return 'ListDataChallengeItem';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfListDataChallengeItem
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
