<?php
/**
 * File for class StructArrayOfSiteVacancyData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructArrayOfSiteVacancyData originally named ArrayOfSiteVacancyData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructArrayOfSiteVacancyData extends WsdlClass
{
    /**
     * The SiteVacancyData
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var StructSiteVacancyData
     */
    public $SiteVacancyData;
    /**
     * Constructor method for ArrayOfSiteVacancyData
     * @see parent::__construct()
     * @param StructSiteVacancyData $_siteVacancyData
     * @return StructArrayOfSiteVacancyData
     */
    public function __construct($_siteVacancyData = NULL)
    {
        parent::__construct(array('SiteVacancyData'=>$_siteVacancyData),false);
    }
    /**
     * Get SiteVacancyData value
     * @return StructSiteVacancyData|null
     */
    public function getSiteVacancyData()
    {
        return $this->SiteVacancyData;
    }
    /**
     * Set SiteVacancyData value
     * @param StructSiteVacancyData $_siteVacancyData the SiteVacancyData
     * @return StructSiteVacancyData
     */
    public function setSiteVacancyData($_siteVacancyData)
    {
        return ($this->SiteVacancyData = $_siteVacancyData);
    }
    /**
     * Returns the current element
     * @see WsdlClass::current()
     * @return StructSiteVacancyData
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see WsdlClass::item()
     * @param int $_index
     * @return StructSiteVacancyData
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see WsdlClass::first()
     * @return StructSiteVacancyData
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see WsdlClass::last()
     * @return StructSiteVacancyData
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see WsdlClass::last()
     * @param int $_offset
     * @return StructSiteVacancyData
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see WsdlClass::getAttributeName()
     * @return string SiteVacancyData
     */
    public function getAttributeName()
    {
        return 'SiteVacancyData';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructArrayOfSiteVacancyData
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
