<?php
/**
 * File for class LRSStructUpdateLearner
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUpdateLearner originally named UpdateLearner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUpdateLearner extends LRSWsdlClass
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
     * The versionNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $versionNumber;
    /**
     * The title
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $title;
    /**
     * The givenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $givenName;
    /**
     * The middleOtherName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $middleOtherName;
    /**
     * The familyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $familyName;
    /**
     * The preferredGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $preferredGivenName;
    /**
     * The previousFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $previousFamilyName;
    /**
     * The familyNameAtSixteen
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $familyNameAtSixteen;
    /**
     * The schoolAtAge16
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $schoolAtAge16;
    /**
     * The lastKnownAddressLineOne
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownAddressLineOne;
    /**
     * The lastKnownAddressLineTwo
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownAddressLineTwo;
    /**
     * The lastKnownAddressTown
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownAddressTown;
    /**
     * The lastKnownAddressCountyOrCity
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownAddressCountyOrCity;
    /**
     * The lastKnownPostCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownPostCode;
    /**
     * The dateOfAddressCapture
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $dateOfAddressCapture;
    /**
     * The dateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var dateTime
     */
    public $dateOfBirth;
    /**
     * The placeOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $placeOfBirth;
    /**
     * The emailAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $emailAddress;
    /**
     * The gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $gender;
    /**
     * The nationality
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $nationality;
    /**
     * The scottishCandidateNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $scottishCandidateNumber;
    /**
     * The verificationType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $verificationType;
    /**
     * The otherVerificationDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $otherVerificationDescription;
    /**
     * The abilityToShare
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var LRSEnumAbilityToShare
     */
    public $abilityToShare;
    /**
     * The notes
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $notes;
    /**
     * The atsLearnerPreference
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $atsLearnerPreference;
    /**
     * The lastUpdatedSearchKey
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $lastUpdatedSearchKey;
    /**
     * Constructor method for UpdateLearner
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_uln
     * @param int $_versionNumber
     * @param string $_title
     * @param string $_givenName
     * @param string $_middleOtherName
     * @param string $_familyName
     * @param string $_preferredGivenName
     * @param string $_previousFamilyName
     * @param string $_familyNameAtSixteen
     * @param string $_schoolAtAge16
     * @param string $_lastKnownAddressLineOne
     * @param string $_lastKnownAddressLineTwo
     * @param string $_lastKnownAddressTown
     * @param string $_lastKnownAddressCountyOrCity
     * @param string $_lastKnownPostCode
     * @param dateTime $_dateOfAddressCapture
     * @param dateTime $_dateOfBirth
     * @param string $_placeOfBirth
     * @param string $_emailAddress
     * @param int $_gender
     * @param string $_nationality
     * @param string $_scottishCandidateNumber
     * @param int $_verificationType
     * @param string $_otherVerificationDescription
     * @param LRSEnumAbilityToShare $_abilityToShare
     * @param string $_notes
     * @param int $_atsLearnerPreference
     * @param int $_lastUpdatedSearchKey
     * @return LRSStructUpdateLearner
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_uln,$_versionNumber,$_title,$_givenName,$_middleOtherName,$_familyName,$_preferredGivenName,$_previousFamilyName,$_familyNameAtSixteen,$_schoolAtAge16,$_lastKnownAddressLineOne,$_lastKnownAddressLineTwo,$_lastKnownAddressTown,$_lastKnownAddressCountyOrCity,$_lastKnownPostCode,$_dateOfAddressCapture,$_dateOfBirth,$_placeOfBirth,$_emailAddress,$_gender,$_nationality,$_scottishCandidateNumber,$_verificationType,$_otherVerificationDescription,$_abilityToShare,$_notes,$_atsLearnerPreference,$_lastUpdatedSearchKey)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'uln'=>$_uln,'versionNumber'=>$_versionNumber,'title'=>$_title,'givenName'=>$_givenName,'middleOtherName'=>$_middleOtherName,'familyName'=>$_familyName,'preferredGivenName'=>$_preferredGivenName,'previousFamilyName'=>$_previousFamilyName,'familyNameAtSixteen'=>$_familyNameAtSixteen,'schoolAtAge16'=>$_schoolAtAge16,'lastKnownAddressLineOne'=>$_lastKnownAddressLineOne,'lastKnownAddressLineTwo'=>$_lastKnownAddressLineTwo,'lastKnownAddressTown'=>$_lastKnownAddressTown,'lastKnownAddressCountyOrCity'=>$_lastKnownAddressCountyOrCity,'lastKnownPostCode'=>$_lastKnownPostCode,'dateOfAddressCapture'=>$_dateOfAddressCapture,'dateOfBirth'=>$_dateOfBirth,'placeOfBirth'=>$_placeOfBirth,'emailAddress'=>$_emailAddress,'gender'=>$_gender,'nationality'=>$_nationality,'scottishCandidateNumber'=>$_scottishCandidateNumber,'verificationType'=>$_verificationType,'otherVerificationDescription'=>$_otherVerificationDescription,'abilityToShare'=>$_abilityToShare,'notes'=>$_notes,'atsLearnerPreference'=>$_atsLearnerPreference,'lastUpdatedSearchKey'=>$_lastUpdatedSearchKey),false);
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
     * Get versionNumber value
     * @return int
     */
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }
    /**
     * Set versionNumber value
     * @param int $_versionNumber the versionNumber
     * @return int
     */
    public function setVersionNumber($_versionNumber)
    {
        return ($this->versionNumber = $_versionNumber);
    }
    /**
     * Get title value
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Set title value
     * @param string $_title the title
     * @return string
     */
    public function setTitle($_title)
    {
        return ($this->title = $_title);
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
     * Get middleOtherName value
     * @return string
     */
    public function getMiddleOtherName()
    {
        return $this->middleOtherName;
    }
    /**
     * Set middleOtherName value
     * @param string $_middleOtherName the middleOtherName
     * @return string
     */
    public function setMiddleOtherName($_middleOtherName)
    {
        return ($this->middleOtherName = $_middleOtherName);
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
     * Get preferredGivenName value
     * @return string
     */
    public function getPreferredGivenName()
    {
        return $this->preferredGivenName;
    }
    /**
     * Set preferredGivenName value
     * @param string $_preferredGivenName the preferredGivenName
     * @return string
     */
    public function setPreferredGivenName($_preferredGivenName)
    {
        return ($this->preferredGivenName = $_preferredGivenName);
    }
    /**
     * Get previousFamilyName value
     * @return string
     */
    public function getPreviousFamilyName()
    {
        return $this->previousFamilyName;
    }
    /**
     * Set previousFamilyName value
     * @param string $_previousFamilyName the previousFamilyName
     * @return string
     */
    public function setPreviousFamilyName($_previousFamilyName)
    {
        return ($this->previousFamilyName = $_previousFamilyName);
    }
    /**
     * Get familyNameAtSixteen value
     * @return string
     */
    public function getFamilyNameAtSixteen()
    {
        return $this->familyNameAtSixteen;
    }
    /**
     * Set familyNameAtSixteen value
     * @param string $_familyNameAtSixteen the familyNameAtSixteen
     * @return string
     */
    public function setFamilyNameAtSixteen($_familyNameAtSixteen)
    {
        return ($this->familyNameAtSixteen = $_familyNameAtSixteen);
    }
    /**
     * Get schoolAtAge16 value
     * @return string
     */
    public function getSchoolAtAge16()
    {
        return $this->schoolAtAge16;
    }
    /**
     * Set schoolAtAge16 value
     * @param string $_schoolAtAge16 the schoolAtAge16
     * @return string
     */
    public function setSchoolAtAge16($_schoolAtAge16)
    {
        return ($this->schoolAtAge16 = $_schoolAtAge16);
    }
    /**
     * Get lastKnownAddressLineOne value
     * @return string
     */
    public function getLastKnownAddressLineOne()
    {
        return $this->lastKnownAddressLineOne;
    }
    /**
     * Set lastKnownAddressLineOne value
     * @param string $_lastKnownAddressLineOne the lastKnownAddressLineOne
     * @return string
     */
    public function setLastKnownAddressLineOne($_lastKnownAddressLineOne)
    {
        return ($this->lastKnownAddressLineOne = $_lastKnownAddressLineOne);
    }
    /**
     * Get lastKnownAddressLineTwo value
     * @return string
     */
    public function getLastKnownAddressLineTwo()
    {
        return $this->lastKnownAddressLineTwo;
    }
    /**
     * Set lastKnownAddressLineTwo value
     * @param string $_lastKnownAddressLineTwo the lastKnownAddressLineTwo
     * @return string
     */
    public function setLastKnownAddressLineTwo($_lastKnownAddressLineTwo)
    {
        return ($this->lastKnownAddressLineTwo = $_lastKnownAddressLineTwo);
    }
    /**
     * Get lastKnownAddressTown value
     * @return string
     */
    public function getLastKnownAddressTown()
    {
        return $this->lastKnownAddressTown;
    }
    /**
     * Set lastKnownAddressTown value
     * @param string $_lastKnownAddressTown the lastKnownAddressTown
     * @return string
     */
    public function setLastKnownAddressTown($_lastKnownAddressTown)
    {
        return ($this->lastKnownAddressTown = $_lastKnownAddressTown);
    }
    /**
     * Get lastKnownAddressCountyOrCity value
     * @return string
     */
    public function getLastKnownAddressCountyOrCity()
    {
        return $this->lastKnownAddressCountyOrCity;
    }
    /**
     * Set lastKnownAddressCountyOrCity value
     * @param string $_lastKnownAddressCountyOrCity the lastKnownAddressCountyOrCity
     * @return string
     */
    public function setLastKnownAddressCountyOrCity($_lastKnownAddressCountyOrCity)
    {
        return ($this->lastKnownAddressCountyOrCity = $_lastKnownAddressCountyOrCity);
    }
    /**
     * Get lastKnownPostCode value
     * @return string
     */
    public function getLastKnownPostCode()
    {
        return $this->lastKnownPostCode;
    }
    /**
     * Set lastKnownPostCode value
     * @param string $_lastKnownPostCode the lastKnownPostCode
     * @return string
     */
    public function setLastKnownPostCode($_lastKnownPostCode)
    {
        return ($this->lastKnownPostCode = $_lastKnownPostCode);
    }
    /**
     * Get dateOfAddressCapture value
     * @return dateTime
     */
    public function getDateOfAddressCapture()
    {
        return $this->dateOfAddressCapture;
    }
    /**
     * Set dateOfAddressCapture value
     * @param dateTime $_dateOfAddressCapture the dateOfAddressCapture
     * @return dateTime
     */
    public function setDateOfAddressCapture($_dateOfAddressCapture)
    {
        return ($this->dateOfAddressCapture = $_dateOfAddressCapture);
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
     * Get placeOfBirth value
     * @return string
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }
    /**
     * Set placeOfBirth value
     * @param string $_placeOfBirth the placeOfBirth
     * @return string
     */
    public function setPlaceOfBirth($_placeOfBirth)
    {
        return ($this->placeOfBirth = $_placeOfBirth);
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
     * Get nationality value
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }
    /**
     * Set nationality value
     * @param string $_nationality the nationality
     * @return string
     */
    public function setNationality($_nationality)
    {
        return ($this->nationality = $_nationality);
    }
    /**
     * Get scottishCandidateNumber value
     * @return string
     */
    public function getScottishCandidateNumber()
    {
        return $this->scottishCandidateNumber;
    }
    /**
     * Set scottishCandidateNumber value
     * @param string $_scottishCandidateNumber the scottishCandidateNumber
     * @return string
     */
    public function setScottishCandidateNumber($_scottishCandidateNumber)
    {
        return ($this->scottishCandidateNumber = $_scottishCandidateNumber);
    }
    /**
     * Get verificationType value
     * @return int
     */
    public function getVerificationType()
    {
        return $this->verificationType;
    }
    /**
     * Set verificationType value
     * @param int $_verificationType the verificationType
     * @return int
     */
    public function setVerificationType($_verificationType)
    {
        return ($this->verificationType = $_verificationType);
    }
    /**
     * Get otherVerificationDescription value
     * @return string
     */
    public function getOtherVerificationDescription()
    {
        return $this->otherVerificationDescription;
    }
    /**
     * Set otherVerificationDescription value
     * @param string $_otherVerificationDescription the otherVerificationDescription
     * @return string
     */
    public function setOtherVerificationDescription($_otherVerificationDescription)
    {
        return ($this->otherVerificationDescription = $_otherVerificationDescription);
    }
    /**
     * Get abilityToShare value
     * @return LRSEnumAbilityToShare
     */
    public function getAbilityToShare()
    {
        return $this->abilityToShare;
    }
    /**
     * Set abilityToShare value
     * @uses LRSEnumAbilityToShare::valueIsValid()
     * @param LRSEnumAbilityToShare $_abilityToShare the abilityToShare
     * @return LRSEnumAbilityToShare
     */
    public function setAbilityToShare($_abilityToShare)
    {
        if(!LRSEnumAbilityToShare::valueIsValid($_abilityToShare))
        {
            return false;
        }
        return ($this->abilityToShare = $_abilityToShare);
    }
    /**
     * Get notes value
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }
    /**
     * Set notes value
     * @param string $_notes the notes
     * @return string
     */
    public function setNotes($_notes)
    {
        return ($this->notes = $_notes);
    }
    /**
     * Get atsLearnerPreference value
     * @return int
     */
    public function getAtsLearnerPreference()
    {
        return $this->atsLearnerPreference;
    }
    /**
     * Set atsLearnerPreference value
     * @param int $_atsLearnerPreference the atsLearnerPreference
     * @return int
     */
    public function setAtsLearnerPreference($_atsLearnerPreference)
    {
        return ($this->atsLearnerPreference = $_atsLearnerPreference);
    }
    /**
     * Get lastUpdatedSearchKey value
     * @return int
     */
    public function getLastUpdatedSearchKey()
    {
        return $this->lastUpdatedSearchKey;
    }
    /**
     * Set lastUpdatedSearchKey value
     * @param int $_lastUpdatedSearchKey the lastUpdatedSearchKey
     * @return int
     */
    public function setLastUpdatedSearchKey($_lastUpdatedSearchKey)
    {
        return ($this->lastUpdatedSearchKey = $_lastUpdatedSearchKey);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUpdateLearner
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
