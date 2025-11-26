<?php
/**
 * File for class LRSStructArrayOfSectorSubjectArea
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfSectorSubjectArea originally named ArrayOfSectorSubjectArea
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfSectorSubjectArea extends LRSWsdlClass
{
    /**
     * The SectorSubjectArea
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructSectorSubjectArea
     */
    public $SectorSubjectArea;
    /**
     * Constructor method for ArrayOfSectorSubjectArea
     * @see parent::__construct()
     * @param LRSStructSectorSubjectArea $_sectorSubjectArea
     * @return LRSStructArrayOfSectorSubjectArea
     */
    public function __construct($_sectorSubjectArea = NULL)
    {
        parent::__construct(array('SectorSubjectArea'=>$_sectorSubjectArea),false);
    }
    /**
     * Get SectorSubjectArea value
     * @return LRSStructSectorSubjectArea|null
     */
    public function getSectorSubjectArea()
    {
        return $this->SectorSubjectArea;
    }
    /**
     * Set SectorSubjectArea value
     * @param LRSStructSectorSubjectArea $_sectorSubjectArea the SectorSubjectArea
     * @return LRSStructSectorSubjectArea
     */
    public function setSectorSubjectArea($_sectorSubjectArea)
    {
        return ($this->SectorSubjectArea = $_sectorSubjectArea);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructSectorSubjectArea
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructSectorSubjectArea
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructSectorSubjectArea
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructSectorSubjectArea
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructSectorSubjectArea
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string SectorSubjectArea
     */
    public function getAttributeName()
    {
        return 'SectorSubjectArea';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfSectorSubjectArea
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
