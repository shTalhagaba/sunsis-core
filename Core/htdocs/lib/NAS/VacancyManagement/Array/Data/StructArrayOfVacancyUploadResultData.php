<?php
/**
 * File for class StructArrayOfVacancyUploadResultData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructArrayOfVacancyUploadResultData originally named ArrayOfVacancyUploadResultData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructArrayOfVacancyUploadResultData extends WsdlClass
{
    /**
     * The VacancyUploadResultData
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var StructVacancyUploadResultData
     */
    public $VacancyUploadResultData;
    /**
     * Constructor method for ArrayOfVacancyUploadResultData
     * @see parent::__construct()
     * @param StructVacancyUploadResultData $_vacancyUploadResultData
     * @return StructArrayOfVacancyUploadResultData
     */
    public function __construct($_vacancyUploadResultData = NULL)
    {
        parent::__construct(array('VacancyUploadResultData'=>$_vacancyUploadResultData),false);
    }
    /**
     * Get VacancyUploadResultData value
     * @return StructVacancyUploadResultData|null
     */
    public function getVacancyUploadResultData()
    {
        return $this->VacancyUploadResultData;
    }
    /**
     * Set VacancyUploadResultData value
     * @param StructVacancyUploadResultData $_vacancyUploadResultData the VacancyUploadResultData
     * @return StructVacancyUploadResultData
     */
    public function setVacancyUploadResultData($_vacancyUploadResultData)
    {
        return ($this->VacancyUploadResultData = $_vacancyUploadResultData);
    }
    /**
     * Returns the current element
     * @see WsdlClass::current()
     * @return StructVacancyUploadResultData
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see WsdlClass::item()
     * @param int $_index
     * @return StructVacancyUploadResultData
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see WsdlClass::first()
     * @return StructVacancyUploadResultData
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see WsdlClass::last()
     * @return StructVacancyUploadResultData
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see WsdlClass::last()
     * @param int $_offset
     * @return StructVacancyUploadResultData
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see WsdlClass::getAttributeName()
     * @return string VacancyUploadResultData
     */
    public function getAttributeName()
    {
        return 'VacancyUploadResultData';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructArrayOfVacancyUploadResultData
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
