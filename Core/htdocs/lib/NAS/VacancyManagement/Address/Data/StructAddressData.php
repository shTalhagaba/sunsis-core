<?php
/**
 * File for class StructAddressData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructAddressData originally named AddressData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructAddressData extends WsdlClass
{
    /**
     * The AddressLine1
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $AddressLine1;
    /**
     * The AddressLine2
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AddressLine2;
    /**
     * The AddressLine3
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AddressLine3;
    /**
     * The AddressLine4
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AddressLine4;
    /**
     * The AddressLine5
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AddressLine5;
    /**
     * The County
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $County;
    /**
     * The GridEastM
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $GridEastM;
    /**
     * The GridNorthM
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $GridNorthM;
    /**
     * The Latitude
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var decimal
     */
    public $Latitude;
    /**
     * The Longitude
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var decimal
     */
    public $Longitude;
    /**
     * The PostCode
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $PostCode;
    /**
     * The Town
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $Town;
    /**
     * The LocalAuthority
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LocalAuthority;
    /**
     * Constructor method for AddressData
     * @see parent::__construct()
     * @param string $_addressLine1
     * @param string $_addressLine2
     * @param string $_addressLine3
     * @param string $_addressLine4
     * @param string $_addressLine5
     * @param string $_county
     * @param int $_gridEastM
     * @param int $_gridNorthM
     * @param decimal $_latitude
     * @param decimal $_longitude
     * @param string $_postCode
     * @param string $_town
     * @param string $_localAuthority
     * @return StructAddressData
     */
    public function __construct($_addressLine1 = NULL,$_addressLine2 = NULL,$_addressLine3 = NULL,$_addressLine4 = NULL,$_addressLine5 = NULL,$_county = NULL,$_gridEastM = NULL,$_gridNorthM = NULL,$_latitude = NULL,$_longitude = NULL,$_postCode = NULL,$_town = NULL,$_localAuthority = NULL)
    {
        parent::__construct(array('AddressLine1'=>$_addressLine1,'AddressLine2'=>$_addressLine2,'AddressLine3'=>$_addressLine3,'AddressLine4'=>$_addressLine4,'AddressLine5'=>$_addressLine5,'County'=>$_county,'GridEastM'=>$_gridEastM,'GridNorthM'=>$_gridNorthM,'Latitude'=>$_latitude,'Longitude'=>$_longitude,'PostCode'=>$_postCode,'Town'=>$_town,'LocalAuthority'=>$_localAuthority),false);
    }
    /**
     * Get AddressLine1 value
     * @return string|null
     */
    public function getAddressLine1()
    {
        return $this->AddressLine1;
    }
    /**
     * Set AddressLine1 value
     * @param string $_addressLine1 the AddressLine1
     * @return string
     */
    public function setAddressLine1($_addressLine1)
    {
        return ($this->AddressLine1 = $_addressLine1);
    }
    /**
     * Get AddressLine2 value
     * @return string|null
     */
    public function getAddressLine2()
    {
        return $this->AddressLine2;
    }
    /**
     * Set AddressLine2 value
     * @param string $_addressLine2 the AddressLine2
     * @return string
     */
    public function setAddressLine2($_addressLine2)
    {
        return ($this->AddressLine2 = $_addressLine2);
    }
    /**
     * Get AddressLine3 value
     * @return string|null
     */
    public function getAddressLine3()
    {
        return $this->AddressLine3;
    }
    /**
     * Set AddressLine3 value
     * @param string $_addressLine3 the AddressLine3
     * @return string
     */
    public function setAddressLine3($_addressLine3)
    {
        return ($this->AddressLine3 = $_addressLine3);
    }
    /**
     * Get AddressLine4 value
     * @return string|null
     */
    public function getAddressLine4()
    {
        return $this->AddressLine4;
    }
    /**
     * Set AddressLine4 value
     * @param string $_addressLine4 the AddressLine4
     * @return string
     */
    public function setAddressLine4($_addressLine4)
    {
        return ($this->AddressLine4 = $_addressLine4);
    }
    /**
     * Get AddressLine5 value
     * @return string|null
     */
    public function getAddressLine5()
    {
        return $this->AddressLine5;
    }
    /**
     * Set AddressLine5 value
     * @param string $_addressLine5 the AddressLine5
     * @return string
     */
    public function setAddressLine5($_addressLine5)
    {
        return ($this->AddressLine5 = $_addressLine5);
    }
    /**
     * Get County value
     * @return string|null
     */
    public function getCounty()
    {
        return $this->County;
    }
    /**
     * Set County value
     * @param string $_county the County
     * @return string
     */
    public function setCounty($_county)
    {
        return ($this->County = $_county);
    }
    /**
     * Get GridEastM value
     * @return int|null
     */
    public function getGridEastM()
    {
        return $this->GridEastM;
    }
    /**
     * Set GridEastM value
     * @param int $_gridEastM the GridEastM
     * @return int
     */
    public function setGridEastM($_gridEastM)
    {
        return ($this->GridEastM = $_gridEastM);
    }
    /**
     * Get GridNorthM value
     * @return int|null
     */
    public function getGridNorthM()
    {
        return $this->GridNorthM;
    }
    /**
     * Set GridNorthM value
     * @param int $_gridNorthM the GridNorthM
     * @return int
     */
    public function setGridNorthM($_gridNorthM)
    {
        return ($this->GridNorthM = $_gridNorthM);
    }
    /**
     * Get Latitude value
     * @return decimal|null
     */
    public function getLatitude()
    {
        return $this->Latitude;
    }
    /**
     * Set Latitude value
     * @param decimal $_latitude the Latitude
     * @return decimal
     */
    public function setLatitude($_latitude)
    {
        return ($this->Latitude = $_latitude);
    }
    /**
     * Get Longitude value
     * @return decimal|null
     */
    public function getLongitude()
    {
        return $this->Longitude;
    }
    /**
     * Set Longitude value
     * @param decimal $_longitude the Longitude
     * @return decimal
     */
    public function setLongitude($_longitude)
    {
        return ($this->Longitude = $_longitude);
    }
    /**
     * Get PostCode value
     * @return string|null
     */
    public function getPostCode()
    {
        return $this->PostCode;
    }
    /**
     * Set PostCode value
     * @param string $_postCode the PostCode
     * @return string
     */
    public function setPostCode($_postCode)
    {
        return ($this->PostCode = $_postCode);
    }
    /**
     * Get Town value
     * @return string|null
     */
    public function getTown()
    {
        return $this->Town;
    }
    /**
     * Set Town value
     * @param string $_town the Town
     * @return string
     */
    public function setTown($_town)
    {
        return ($this->Town = $_town);
    }
    /**
     * Get LocalAuthority value
     * @return string|null
     */
    public function getLocalAuthority()
    {
        return $this->LocalAuthority;
    }
    /**
     * Set LocalAuthority value
     * @param string $_localAuthority the LocalAuthority
     * @return string
     */
    public function setLocalAuthority($_localAuthority)
    {
        return ($this->LocalAuthority = $_localAuthority);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructAddressData
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
