<?php
/**
 * File for class LRSStructCreateOrModifySnapshot
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructCreateOrModifySnapshot originally named CreateOrModifySnapshot
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructCreateOrModifySnapshot extends LRSWsdlClass
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
     * The vendorId
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $vendorId;
    /**
     * The userType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $userType;
    /**
     * The language
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $language;
    /**
     * The learnerUln
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $learnerUln;
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
     * The gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $gender;
    /**
     * The dateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $dateOfBirth;
    /**
     * The events
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructArrayOfPlrSnapshotEvent
     */
    public $events;
    /**
     * The includeAllEvents
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $includeAllEvents;
    /**
     * The targetEmail
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $targetEmail;
    /**
     * The pinCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $pinCode;
    /**
     * The userReference
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $userReference;
    /**
     * The guid
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $guid;
    /**
     * Constructor method for CreateOrModifySnapshot
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param int $_vendorId
     * @param string $_userType
     * @param string $_language
     * @param string $_learnerUln
     * @param string $_givenName
     * @param string $_familyName
     * @param int $_gender
     * @param dateTime $_dateOfBirth
     * @param LRSStructArrayOfPlrSnapshotEvent $_events
     * @param boolean $_includeAllEvents
     * @param string $_targetEmail
     * @param string $_pinCode
     * @param string $_userReference
     * @param string $_guid
     * @return LRSStructCreateOrModifySnapshot
     */
    public function __construct($_invokingOrganisation,$_vendorId,$_userType,$_language,$_learnerUln,$_givenName,$_familyName,$_gender,$_dateOfBirth,$_events,$_includeAllEvents,$_targetEmail,$_pinCode,$_userReference,$_guid)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'vendorId'=>$_vendorId,'userType'=>$_userType,'language'=>$_language,'learnerUln'=>$_learnerUln,'givenName'=>$_givenName,'familyName'=>$_familyName,'gender'=>$_gender,'dateOfBirth'=>$_dateOfBirth,'events'=>($_events instanceof LRSStructArrayOfPlrSnapshotEvent)?$_events:new LRSStructArrayOfPlrSnapshotEvent($_events),'includeAllEvents'=>$_includeAllEvents,'targetEmail'=>$_targetEmail,'pinCode'=>$_pinCode,'userReference'=>$_userReference,'guid'=>$_guid),false);
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
     * Get learnerUln value
     * @return string
     */
    public function getLearnerUln()
    {
        return $this->learnerUln;
    }
    /**
     * Set learnerUln value
     * @param string $_learnerUln the learnerUln
     * @return string
     */
    public function setLearnerUln($_learnerUln)
    {
        return ($this->learnerUln = $_learnerUln);
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
     * Get gender value
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * Set gender value
     * @param int $_gender the gender
     * @return int
     */
    public function setGender($_gender)
    {
        return ($this->gender = $_gender);
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
     * Get events value
     * @return LRSStructArrayOfPlrSnapshotEvent
     */
    public function getEvents()
    {
        return $this->events;
    }
    /**
     * Set events value
     * @param LRSStructArrayOfPlrSnapshotEvent $_events the events
     * @return LRSStructArrayOfPlrSnapshotEvent
     */
    public function setEvents($_events)
    {
        return ($this->events = $_events);
    }
    /**
     * Get includeAllEvents value
     * @return boolean
     */
    public function getIncludeAllEvents()
    {
        return $this->includeAllEvents;
    }
    /**
     * Set includeAllEvents value
     * @param boolean $_includeAllEvents the includeAllEvents
     * @return boolean
     */
    public function setIncludeAllEvents($_includeAllEvents)
    {
        return ($this->includeAllEvents = $_includeAllEvents);
    }
    /**
     * Get targetEmail value
     * @return string
     */
    public function getTargetEmail()
    {
        return $this->targetEmail;
    }
    /**
     * Set targetEmail value
     * @param string $_targetEmail the targetEmail
     * @return string
     */
    public function setTargetEmail($_targetEmail)
    {
        return ($this->targetEmail = $_targetEmail);
    }
    /**
     * Get pinCode value
     * @return string
     */
    public function getPinCode()
    {
        return $this->pinCode;
    }
    /**
     * Set pinCode value
     * @param string $_pinCode the pinCode
     * @return string
     */
    public function setPinCode($_pinCode)
    {
        return ($this->pinCode = $_pinCode);
    }
    /**
     * Get userReference value
     * @return string
     */
    public function getUserReference()
    {
        return $this->userReference;
    }
    /**
     * Set userReference value
     * @param string $_userReference the userReference
     * @return string
     */
    public function setUserReference($_userReference)
    {
        return ($this->userReference = $_userReference);
    }
    /**
     * Get guid value
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }
    /**
     * Set guid value
     * @param string $_guid the guid
     * @return string
     */
    public function setGuid($_guid)
    {
        return ($this->guid = $_guid);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructCreateOrModifySnapshot
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
