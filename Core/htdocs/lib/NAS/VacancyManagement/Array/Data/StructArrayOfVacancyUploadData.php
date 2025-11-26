<?php
/**
 * File for class StructArrayOfVacancyUploadData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructArrayOfVacancyUploadData originally named ArrayOfVacancyUploadData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructArrayOfVacancyUploadData extends WsdlClass
{
    /**
     * The VacancyUploadData
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var StructVacancyUploadData
     */
    public $VacancyUploadData;
    /**
     * Constructor method for ArrayOfVacancyUploadData
     * @see parent::__construct()
     * @param StructVacancyUploadData $_vacancyUploadData
     * @return StructArrayOfVacancyUploadData
     */
    public function __construct($_vacancyUploadData = NULL)
    {
        parent::__construct(array('VacancyUploadData'=>$_vacancyUploadData),false);
    }
    /**
     * Get VacancyUploadData value
     * @return StructVacancyUploadData|null
     */
    public function getVacancyUploadData()
    {
        return $this->VacancyUploadData;
    }
    /**
     * Set VacancyUploadData value
     * @param StructVacancyUploadData $_vacancyUploadData the VacancyUploadData
     * @return StructVacancyUploadData
     */
    public function setVacancyUploadData($_vacancyUploadData)
    {
        return ($this->VacancyUploadData = $_vacancyUploadData);
    }
    /**
     * Returns the current element
     * @see WsdlClass::current()
     * @return StructVacancyUploadData
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see WsdlClass::item()
     * @param int $_index
     * @return StructVacancyUploadData
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see WsdlClass::first()
     * @return StructVacancyUploadData
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see WsdlClass::last()
     * @return StructVacancyUploadData
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see WsdlClass::last()
     * @param int $_offset
     * @return StructVacancyUploadData
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see WsdlClass::getAttributeName()
     * @return string VacancyUploadData
     */
    public function getAttributeName()
    {
        return 'VacancyUploadData';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructArrayOfVacancyUploadData
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
