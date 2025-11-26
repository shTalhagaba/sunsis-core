<?php
/**
 * File for class LRSStructStoreLearnerKey
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructStoreLearnerKey originally named StoreLearnerKey
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructStoreLearnerKey extends LRSWsdlClass
{
    /**
     * The invokingOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructInvokingOrganisationR10
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
     * The vendorID
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $vendorID;
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
     * @var string
     */
    public $dateOfBirth;
    /**
     * The gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $gender;
    /**
     * The keyType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $keyType;
    /**
     * The keyValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $keyValue;
    /**
     * Constructor method for StoreLearnerKey
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisationR10 $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorID
     * @param string $_language
     * @param string $_uln
     * @param string $_givenName
     * @param string $_familyName
     * @param string $_dateOfBirth
     * @param string $_gender
     * @param string $_keyType
     * @param string $_keyValue
     * @return LRSStructStoreLearnerKey
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorID,$_language,$_uln,$_givenName,$_familyName,$_dateOfBirth,$_gender,$_keyType,$_keyValue)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorID'=>$_vendorID,'language'=>$_language,'uln'=>$_uln,'givenName'=>$_givenName,'familyName'=>$_familyName,'dateOfBirth'=>$_dateOfBirth,'gender'=>$_gender,'keyType'=>$_keyType,'keyValue'=>$_keyValue),false);
    }
    /**
     * Get invokingOrganisation value
     * @return LRSStructInvokingOrganisationR10
     */
    public function getInvokingOrganisation()
    {
        return $this->invokingOrganisation;
    }
    /**
     * Set invokingOrganisation value
     * @param LRSStructInvokingOrganisationR10 $_invokingOrganisation the invokingOrganisation
     * @return LRSStructInvokingOrganisationR10
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
     * Get vendorID value
     * @return int
     */
    public function getVendorID()
    {
        return $this->vendorID;
    }
    /**
     * Set vendorID value
     * @param int $_vendorID the vendorID
     * @return int
     */
    public function setVendorID($_vendorID)
    {
        return ($this->vendorID = $_vendorID);
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
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    /**
     * Set dateOfBirth value
     * @param string $_dateOfBirth the dateOfBirth
     * @return string
     */
    public function setDateOfBirth($_dateOfBirth)
    {
        return ($this->dateOfBirth = $_dateOfBirth);
    }
    /**
     * Get gender value
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * Set gender value
     * @param string $_gender the gender
     * @return string
     */
    public function setGender($_gender)
    {
        return ($this->gender = $_gender);
    }
    /**
     * Get keyType value
     * @return string
     */
    public function getKeyType()
    {
        return $this->keyType;
    }
    /**
     * Set keyType value
     * @param string $_keyType the keyType
     * @return string
     */
    public function setKeyType($_keyType)
    {
        return ($this->keyType = $_keyType);
    }
    /**
     * Get keyValue value
     * @return string
     */
    public function getKeyValue()
    {
        return $this->keyValue;
    }
    /**
     * Set keyValue value
     * @param string $_keyValue the keyValue
     * @return string
     */
    public function setKeyValue($_keyValue)
    {
        return ($this->keyValue = $_keyValue);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructStoreLearnerKey
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
