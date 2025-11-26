<?php
/**
 * File for class LRSStructExecuteRoCQuery
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructExecuteRoCQuery originally named ExecuteRoCQuery
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructExecuteRoCQuery extends LRSWsdlClass
{
    /**
     * The invokingOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructInvokingOrganisation
     */
    public $invokingOrganisation;
    /**
     * The userType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $userType;
    /**
     * The vendorId
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $vendorId;
    /**
     * The language
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $language;
    /**
     * The uln
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $uln;
    /**
     * The accreditationNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $accreditationNumber;
    /**
     * The givenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $givenName;
    /**
     * The familyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $familyName;
    /**
     * The dateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $dateOfBirth;
    /**
     * The lastKnownPostcode
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownPostcode;
    /**
     * The extraUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $extraUnits;
    /**
     * The includeLearnerUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $includeLearnerUnits;
    /**
     * Constructor method for ExecuteRoCQuery
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_uln
     * @param string $_accreditationNumber
     * @param string $_givenName
     * @param string $_familyName
     * @param dateTime $_dateOfBirth
     * @param string $_lastKnownPostcode
     * @param LRSStructArrayOfstring $_extraUnits
     * @param boolean $_includeLearnerUnits
     * @return LRSStructExecuteRoCQuery
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_uln,$_accreditationNumber,$_givenName,$_familyName,$_dateOfBirth,$_lastKnownPostcode,$_extraUnits,$_includeLearnerUnits)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'uln'=>$_uln,'accreditationNumber'=>$_accreditationNumber,'givenName'=>$_givenName,'familyName'=>$_familyName,'dateOfBirth'=>$_dateOfBirth,'lastKnownPostcode'=>$_lastKnownPostcode,'extraUnits'=>($_extraUnits instanceof LRSStructArrayOfstring)?$_extraUnits:new LRSStructArrayOfstring($_extraUnits),'includeLearnerUnits'=>$_includeLearnerUnits),false);
    }
    /**
     * Get invokingOrganisation value
     * @return LRSStructInvokingOrganisation
     */
    public function getInvokingOrganisation()
    {
        return $this->invokingOrganisation;
    }
    /**
     * Set invokingOrganisation value
     * @param LRSStructInvokingOrganisation $_invokingOrganisation the invokingOrganisation
     * @return LRSStructInvokingOrganisation
     */
    public function setInvokingOrganisation($_invokingOrganisation)
    {
        return ($this->invokingOrganisation = $_invokingOrganisation);
    }
    /**
     * Get userType value
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }
    /**
     * Set userType value
     * @param string $_userType the userType
     * @return string
     */
    public function setUserType($_userType)
    {
        return ($this->userType = $_userType);
    }
    /**
     * Get vendorId value
     * @return int
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
    /**
     * Set vendorId value
     * @param int $_vendorId the vendorId
     * @return int
     */
    public function setVendorId($_vendorId)
    {
        return ($this->vendorId = $_vendorId);
    }
    /**
     * Get language value
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * Set language value
     * @param string $_language the language
     * @return string
     */
    public function setLanguage($_language)
    {
        return ($this->language = $_language);
    }
    /**
     * Get uln value
     * @return string
     */
    public function getUln()
    {
        return $this->uln;
    }
    /**
     * Set uln value
     * @param string $_uln the uln
     * @return string
     */
    public function setUln($_uln)
    {
        return ($this->uln = $_uln);
    }
    /**
     * Get accreditationNumber value
     * @return string
     */
    public function getAccreditationNumber()
    {
        return $this->accreditationNumber;
    }
    /**
     * Set accreditationNumber value
     * @param string $_accreditationNumber the accreditationNumber
     * @return string
     */
    public function setAccreditationNumber($_accreditationNumber)
    {
        return ($this->accreditationNumber = $_accreditationNumber);
    }
    /**
     * Get givenName value
     * @return string
     */
    public function getGivenName()
    {
        return $this->givenName;
    }
    /**
     * Set givenName value
     * @param string $_givenName the givenName
     * @return string
     */
    public function setGivenName($_givenName)
    {
        return ($this->givenName = $_givenName);
    }
    /**
     * Get familyName value
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }
    /**
     * Set familyName value
     * @param string $_familyName the familyName
     * @return string
     */
    public function setFamilyName($_familyName)
    {
        return ($this->familyName = $_familyName);
    }
    /**
     * Get dateOfBirth value
     * @return dateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    /**
     * Set dateOfBirth value
     * @param dateTime $_dateOfBirth the dateOfBirth
     * @return dateTime
     */
    public function setDateOfBirth($_dateOfBirth)
    {
        return ($this->dateOfBirth = $_dateOfBirth);
    }
    /**
     * Get lastKnownPostcode value
     * @return string
     */
    public function getLastKnownPostcode()
    {
        return $this->lastKnownPostcode;
    }
    /**
     * Set lastKnownPostcode value
     * @param string $_lastKnownPostcode the lastKnownPostcode
     * @return string
     */
    public function setLastKnownPostcode($_lastKnownPostcode)
    {
        return ($this->lastKnownPostcode = $_lastKnownPostcode);
    }
    /**
     * Get extraUnits value
     * @return LRSStructArrayOfstring
     */
    public function getExtraUnits()
    {
        return $this->extraUnits;
    }
    /**
     * Set extraUnits value
     * @param LRSStructArrayOfstring $_extraUnits the extraUnits
     * @return LRSStructArrayOfstring
     */
    public function setExtraUnits($_extraUnits)
    {
        return ($this->extraUnits = $_extraUnits);
    }
    /**
     * Get includeLearnerUnits value
     * @return boolean
     */
    public function getIncludeLearnerUnits()
    {
        return $this->includeLearnerUnits;
    }
    /**
     * Set includeLearnerUnits value
     * @param boolean $_includeLearnerUnits the includeLearnerUnits
     * @return boolean
     */
    public function setIncludeLearnerUnits($_includeLearnerUnits)
    {
        return ($this->includeLearnerUnits = $_includeLearnerUnits);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructExecuteRoCQuery
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
