<?php
/**
 * File for class LRSStructSetNotification
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructSetNotification originally named SetNotification
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructSetNotification extends LRSWsdlClass
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
     * The gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $gender;
    /**
     * The emailAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $emailAddress;
    /**
     * The smsPhoneNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $smsPhoneNumber;
    /**
     * The notificationPreference
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $notificationPreference;
    /**
     * Constructor method for SetNotification
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_uln
     * @param string $_givenName
     * @param string $_familyName
     * @param dateTime $_dateOfBirth
     * @param string $_gender
     * @param string $_emailAddress
     * @param string $_smsPhoneNumber
     * @param string $_notificationPreference
     * @return LRSStructSetNotification
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_uln,$_givenName,$_familyName,$_dateOfBirth,$_gender,$_emailAddress,$_smsPhoneNumber,$_notificationPreference)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'uln'=>$_uln,'givenName'=>$_givenName,'familyName'=>$_familyName,'dateOfBirth'=>$_dateOfBirth,'gender'=>$_gender,'emailAddress'=>$_emailAddress,'smsPhoneNumber'=>$_smsPhoneNumber,'notificationPreference'=>$_notificationPreference),false);
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
     * Get emailAddress value
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    /**
     * Set emailAddress value
     * @param string $_emailAddress the emailAddress
     * @return string
     */
    public function setEmailAddress($_emailAddress)
    {
        return ($this->emailAddress = $_emailAddress);
    }
    /**
     * Get smsPhoneNumber value
     * @return string
     */
    public function getSmsPhoneNumber()
    {
        return $this->smsPhoneNumber;
    }
    /**
     * Set smsPhoneNumber value
     * @param string $_smsPhoneNumber the smsPhoneNumber
     * @return string
     */
    public function setSmsPhoneNumber($_smsPhoneNumber)
    {
        return ($this->smsPhoneNumber = $_smsPhoneNumber);
    }
    /**
     * Get notificationPreference value
     * @return string
     */
    public function getNotificationPreference()
    {
        return $this->notificationPreference;
    }
    /**
     * Set notificationPreference value
     * @param string $_notificationPreference the notificationPreference
     * @return string
     */
    public function setNotificationPreference($_notificationPreference)
    {
        return ($this->notificationPreference = $_notificationPreference);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructSetNotification
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
