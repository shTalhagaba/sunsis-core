<?php
/**
 * File for class StructArrayOfElementErrorData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructArrayOfElementErrorData originally named ArrayOfElementErrorData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructArrayOfElementErrorData extends WsdlClass
{
    /**
     * The ElementErrorData
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var StructElementErrorData
     */
    public $ElementErrorData;
    /**
     * Constructor method for ArrayOfElementErrorData
     * @see parent::__construct()
     * @param StructElementErrorData $_elementErrorData
     * @return StructArrayOfElementErrorData
     */
    public function __construct($_elementErrorData = NULL)
    {
        parent::__construct(array('ElementErrorData'=>$_elementErrorData),false);
    }
    /**
     * Get ElementErrorData value
     * @return StructElementErrorData|null
     */
    public function getElementErrorData()
    {
        return $this->ElementErrorData;
    }
    /**
     * Set ElementErrorData value
     * @param StructElementErrorData $_elementErrorData the ElementErrorData
     * @return StructElementErrorData
     */
    public function setElementErrorData($_elementErrorData)
    {
        return ($this->ElementErrorData = $_elementErrorData);
    }
    /**
     * Returns the current element
     * @see WsdlClass::current()
     * @return StructElementErrorData
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see WsdlClass::item()
     * @param int $_index
     * @return StructElementErrorData
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see WsdlClass::first()
     * @return StructElementErrorData
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see WsdlClass::last()
     * @return StructElementErrorData
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see WsdlClass::last()
     * @param int $_offset
     * @return StructElementErrorData
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see WsdlClass::getAttributeName()
     * @return string ElementErrorData
     */
    public function getAttributeName()
    {
        return 'ElementErrorData';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructArrayOfElementErrorData
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
