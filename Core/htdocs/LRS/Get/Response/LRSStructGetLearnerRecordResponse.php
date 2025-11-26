<?php
/**
 * File for class LRSStructGetLearnerRecordResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructGetLearnerRecordResponse originally named GetLearnerRecordResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructGetLearnerRecordResponse extends LRSStructServiceResponseR9
{
    /**
     * The AbilityToShare
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AbilityToShare;
    /**
     * The DateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $DateOfBirth;
    /**
     * The EmailAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $EmailAddress;
    /**
     * The FamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FamilyName;
    /**
     * The FamilyNameAt16
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FamilyNameAt16;
    /**
     * The Gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $Gender;
    /**
     * The GivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $GivenName;
    /**
     * The IncomingDateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $IncomingDateOfBirth;
    /**
     * The IncomingFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $IncomingFamilyName;
    /**
     * The IncomingGender
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $IncomingGender;
    /**
     * The IncomingGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $IncomingGivenName;
    /**
     * The IncomingLastKnownPostCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $IncomingLastKnownPostCode;
    /**
     * The IncomingULN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $IncomingULN;
    /**
     * The LastKnownAddressCountyOrCity
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressCountyOrCity;
    /**
     * The LastKnownAddressLine1
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressLine1;
    /**
     * The LastKnownAddressLine2
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressLine2;
    /**
     * The LastKnownAddressTown
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressTown;
    /**
     * The LastKnownPostCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownPostCode;
    /**
     * The LearnerRecord
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfLearnerEvent
     */
    public $LearnerRecord;
    /**
     * The MiddleOtherName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $MiddleOtherName;
    /**
     * The Nationality
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Nationality;
    /**
     * The OtherVerificationDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OtherVerificationDescription;
    /**
     * The PlaceOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PlaceOfBirth;
    /**
     * The PreferredGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PreferredGivenName;
    /**
     * The PreviousFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PreviousFamilyName;
    /**
     * The SchoolAtAge16
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SchoolAtAge16;
    /**
     * The ScottishCandidateNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ScottishCandidateNumber;
    /**
     * The Title
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Title;
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ULN;
    /**
     * The VerificationType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $VerificationType;
    /**
     * The GetLearnerRecordResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $GetLearnerRecordResult;
    /**
     * Constructor method for GetLearnerRecordResponse
     * @see parent::__construct()
     * @param string $_abilityToShare
     * @param dateTime $_dateOfBirth
     * @param string $_emailAddress
     * @param string $_familyName
     * @param string $_familyNameAt16
     * @param int $_gender
     * @param string $_givenName
     * @param dateTime $_incomingDateOfBirth
     * @param string $_incomingFamilyName
     * @param int $_incomingGender
     * @param string $_incomingGivenName
     * @param string $_incomingLastKnownPostCode
     * @param string $_incomingULN
     * @param string $_lastKnownAddressCountyOrCity
     * @param string $_lastKnownAddressLine1
     * @param string $_lastKnownAddressLine2
     * @param string $_lastKnownAddressTown
     * @param string $_lastKnownPostCode
     * @param LRSStructArrayOfLearnerEvent $_learnerRecord
     * @param string $_middleOtherName
     * @param string $_nationality
     * @param string $_otherVerificationDescription
     * @param string $_placeOfBirth
     * @param string $_preferredGivenName
     * @param string $_previousFamilyName
     * @param string $_schoolAtAge16
     * @param string $_scottishCandidateNumber
     * @param string $_title
     * @param string $_uLN
     * @param string $_verificationType
     * @param ServiceResponseR9 $_getLearnerRecordResult
     * @return LRSStructGetLearnerRecordResponse
     */
    public function __construct($_abilityToShare = NULL,$_dateOfBirth = NULL,$_emailAddress = NULL,$_familyName = NULL,$_familyNameAt16 = NULL,$_gender = NULL,$_givenName = NULL,$_incomingDateOfBirth = NULL,$_incomingFamilyName = NULL,$_incomingGender = NULL,$_incomingGivenName = NULL,$_incomingLastKnownPostCode = NULL,$_incomingULN = NULL,$_lastKnownAddressCountyOrCity = NULL,$_lastKnownAddressLine1 = NULL,$_lastKnownAddressLine2 = NULL,$_lastKnownAddressTown = NULL,$_lastKnownPostCode = NULL,$_learnerRecord = NULL,$_middleOtherName = NULL,$_nationality = NULL,$_otherVerificationDescription = NULL,$_placeOfBirth = NULL,$_preferredGivenName = NULL,$_previousFamilyName = NULL,$_schoolAtAge16 = NULL,$_scottishCandidateNumber = NULL,$_title = NULL,$_uLN = NULL,$_verificationType = NULL,$_getLearnerRecordResult = NULL)
    {
        LRSWsdlClass::__construct(array('AbilityToShare'=>$_abilityToShare,'DateOfBirth'=>$_dateOfBirth,'EmailAddress'=>$_emailAddress,'FamilyName'=>$_familyName,'FamilyNameAt16'=>$_familyNameAt16,'Gender'=>$_gender,'GivenName'=>$_givenName,'IncomingDateOfBirth'=>$_incomingDateOfBirth,'IncomingFamilyName'=>$_incomingFamilyName,'IncomingGender'=>$_incomingGender,'IncomingGivenName'=>$_incomingGivenName,'IncomingLastKnownPostCode'=>$_incomingLastKnownPostCode,'IncomingULN'=>$_incomingULN,'LastKnownAddressCountyOrCity'=>$_lastKnownAddressCountyOrCity,'LastKnownAddressLine1'=>$_lastKnownAddressLine1,'LastKnownAddressLine2'=>$_lastKnownAddressLine2,'LastKnownAddressTown'=>$_lastKnownAddressTown,'LastKnownPostCode'=>$_lastKnownPostCode,'LearnerRecord'=>($_learnerRecord instanceof LRSStructArrayOfLearnerEvent)?$_learnerRecord:new LRSStructArrayOfLearnerEvent($_learnerRecord),'MiddleOtherName'=>$_middleOtherName,'Nationality'=>$_nationality,'OtherVerificationDescription'=>$_otherVerificationDescription,'PlaceOfBirth'=>$_placeOfBirth,'PreferredGivenName'=>$_preferredGivenName,'PreviousFamilyName'=>$_previousFamilyName,'SchoolAtAge16'=>$_schoolAtAge16,'ScottishCandidateNumber'=>$_scottishCandidateNumber,'Title'=>$_title,'ULN'=>$_uLN,'VerificationType'=>$_verificationType,'GetLearnerRecordResult'=>$_getLearnerRecordResult),false);
    }
    /**
     * Get AbilityToShare value
     * @return string|null
     */
    public function getAbilityToShare()
    {
        return $this->AbilityToShare;
    }
    /**
     * Set AbilityToShare value
     * @param string $_abilityToShare the AbilityToShare
     * @return string
     */
    public function setAbilityToShare($_abilityToShare)
    {
        return ($this->AbilityToShare = $_abilityToShare);
    }
    /**
     * Get DateOfBirth value
     * @return dateTime|null
     */
    public function getDateOfBirth()
    {
        return $this->DateOfBirth;
    }
    /**
     * Set DateOfBirth value
     * @param dateTime $_dateOfBirth the DateOfBirth
     * @return dateTime
     */
    public function setDateOfBirth($_dateOfBirth)
    {
        return ($this->DateOfBirth = $_dateOfBirth);
    }
    /**
     * Get EmailAddress value
     * @return string|null
     */
    public function getEmailAddress()
    {
        return $this->EmailAddress;
    }
    /**
     * Set EmailAddress value
     * @param string $_emailAddress the EmailAddress
     * @return string
     */
    public function setEmailAddress($_emailAddress)
    {
        return ($this->EmailAddress = $_emailAddress);
    }
    /**
     * Get FamilyName value
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->FamilyName;
    }
    /**
     * Set FamilyName value
     * @param string $_familyName the FamilyName
     * @return string
     */
    public function setFamilyName($_familyName)
    {
        return ($this->FamilyName = $_familyName);
    }
    /**
     * Get FamilyNameAt16 value
     * @return string|null
     */
    public function getFamilyNameAt16()
    {
        return $this->FamilyNameAt16;
    }
    /**
     * Set FamilyNameAt16 value
     * @param string $_familyNameAt16 the FamilyNameAt16
     * @return string
     */
    public function setFamilyNameAt16($_familyNameAt16)
    {
        return ($this->FamilyNameAt16 = $_familyNameAt16);
    }
    /**
     * Get Gender value
     * @return int|null
     */
    public function getGender()
    {
        return $this->Gender;
    }
    /**
     * Set Gender value
     * @param int $_gender the Gender
     * @return int
     */
    public function setGender($_gender)
    {
        return ($this->Gender = $_gender);
    }
    /**
     * Get GivenName value
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->GivenName;
    }
    /**
     * Set GivenName value
     * @param string $_givenName the GivenName
     * @return string
     */
    public function setGivenName($_givenName)
    {
        return ($this->GivenName = $_givenName);
    }
    /**
     * Get IncomingDateOfBirth value
     * @return dateTime|null
     */
    public function getIncomingDateOfBirth()
    {
        return $this->IncomingDateOfBirth;
    }
    /**
     * Set IncomingDateOfBirth value
     * @param dateTime $_incomingDateOfBirth the IncomingDateOfBirth
     * @return dateTime
     */
    public function setIncomingDateOfBirth($_incomingDateOfBirth)
    {
        return ($this->IncomingDateOfBirth = $_incomingDateOfBirth);
    }
    /**
     * Get IncomingFamilyName value
     * @return string|null
     */
    public function getIncomingFamilyName()
    {
        return $this->IncomingFamilyName;
    }
    /**
     * Set IncomingFamilyName value
     * @param string $_incomingFamilyName the IncomingFamilyName
     * @return string
     */
    public function setIncomingFamilyName($_incomingFamilyName)
    {
        return ($this->IncomingFamilyName = $_incomingFamilyName);
    }
    /**
     * Get IncomingGender value
     * @return int|null
     */
    public function getIncomingGender()
    {
        return $this->IncomingGender;
    }
    /**
     * Set IncomingGender value
     * @param int $_incomingGender the IncomingGender
     * @return int
     */
    public function setIncomingGender($_incomingGender)
    {
        return ($this->IncomingGender = $_incomingGender);
    }
    /**
     * Get IncomingGivenName value
     * @return string|null
     */
    public function getIncomingGivenName()
    {
        return $this->IncomingGivenName;
    }
    /**
     * Set IncomingGivenName value
     * @param string $_incomingGivenName the IncomingGivenName
     * @return string
     */
    public function setIncomingGivenName($_incomingGivenName)
    {
        return ($this->IncomingGivenName = $_incomingGivenName);
    }
    /**
     * Get IncomingLastKnownPostCode value
     * @return string|null
     */
    public function getIncomingLastKnownPostCode()
    {
        return $this->IncomingLastKnownPostCode;
    }
    /**
     * Set IncomingLastKnownPostCode value
     * @param string $_incomingLastKnownPostCode the IncomingLastKnownPostCode
     * @return string
     */
    public function setIncomingLastKnownPostCode($_incomingLastKnownPostCode)
    {
        return ($this->IncomingLastKnownPostCode = $_incomingLastKnownPostCode);
    }
    /**
     * Get IncomingULN value
     * @return string|null
     */
    public function getIncomingULN()
    {
        return $this->IncomingULN;
    }
    /**
     * Set IncomingULN value
     * @param string $_incomingULN the IncomingULN
     * @return string
     */
    public function setIncomingULN($_incomingULN)
    {
        return ($this->IncomingULN = $_incomingULN);
    }
    /**
     * Get LastKnownAddressCountyOrCity value
     * @return string|null
     */
    public function getLastKnownAddressCountyOrCity()
    {
        return $this->LastKnownAddressCountyOrCity;
    }
    /**
     * Set LastKnownAddressCountyOrCity value
     * @param string $_lastKnownAddressCountyOrCity the LastKnownAddressCountyOrCity
     * @return string
     */
    public function setLastKnownAddressCountyOrCity($_lastKnownAddressCountyOrCity)
    {
        return ($this->LastKnownAddressCountyOrCity = $_lastKnownAddressCountyOrCity);
    }
    /**
     * Get LastKnownAddressLine1 value
     * @return string|null
     */
    public function getLastKnownAddressLine1()
    {
        return $this->LastKnownAddressLine1;
    }
    /**
     * Set LastKnownAddressLine1 value
     * @param string $_lastKnownAddressLine1 the LastKnownAddressLine1
     * @return string
     */
    public function setLastKnownAddressLine1($_lastKnownAddressLine1)
    {
        return ($this->LastKnownAddressLine1 = $_lastKnownAddressLine1);
    }
    /**
     * Get LastKnownAddressLine2 value
     * @return string|null
     */
    public function getLastKnownAddressLine2()
    {
        return $this->LastKnownAddressLine2;
    }
    /**
     * Set LastKnownAddressLine2 value
     * @param string $_lastKnownAddressLine2 the LastKnownAddressLine2
     * @return string
     */
    public function setLastKnownAddressLine2($_lastKnownAddressLine2)
    {
        return ($this->LastKnownAddressLine2 = $_lastKnownAddressLine2);
    }
    /**
     * Get LastKnownAddressTown value
     * @return string|null
     */
    public function getLastKnownAddressTown()
    {
        return $this->LastKnownAddressTown;
    }
    /**
     * Set LastKnownAddressTown value
     * @param string $_lastKnownAddressTown the LastKnownAddressTown
     * @return string
     */
    public function setLastKnownAddressTown($_lastKnownAddressTown)
    {
        return ($this->LastKnownAddressTown = $_lastKnownAddressTown);
    }
    /**
     * Get LastKnownPostCode value
     * @return string|null
     */
    public function getLastKnownPostCode()
    {
        return $this->LastKnownPostCode;
    }
    /**
     * Set LastKnownPostCode value
     * @param string $_lastKnownPostCode the LastKnownPostCode
     * @return string
     */
    public function setLastKnownPostCode($_lastKnownPostCode)
    {
        return ($this->LastKnownPostCode = $_lastKnownPostCode);
    }
    /**
     * Get LearnerRecord value
     * @return LRSStructArrayOfLearnerEvent|null
     */
    public function getLearnerRecord()
    {
        return $this->LearnerRecord;
    }
    /**
     * Set LearnerRecord value
     * @param LRSStructArrayOfLearnerEvent $_learnerRecord the LearnerRecord
     * @return LRSStructArrayOfLearnerEvent
     */
    public function setLearnerRecord($_learnerRecord)
    {
        return ($this->LearnerRecord = $_learnerRecord);
    }
    /**
     * Get MiddleOtherName value
     * @return string|null
     */
    public function getMiddleOtherName()
    {
        return $this->MiddleOtherName;
    }
    /**
     * Set MiddleOtherName value
     * @param string $_middleOtherName the MiddleOtherName
     * @return string
     */
    public function setMiddleOtherName($_middleOtherName)
    {
        return ($this->MiddleOtherName = $_middleOtherName);
    }
    /**
     * Get Nationality value
     * @return string|null
     */
    public function getNationality()
    {
        return $this->Nationality;
    }
    /**
     * Set Nationality value
     * @param string $_nationality the Nationality
     * @return string
     */
    public function setNationality($_nationality)
    {
        return ($this->Nationality = $_nationality);
    }
    /**
     * Get OtherVerificationDescription value
     * @return string|null
     */
    public function getOtherVerificationDescription()
    {
        return $this->OtherVerificationDescription;
    }
    /**
     * Set OtherVerificationDescription value
     * @param string $_otherVerificationDescription the OtherVerificationDescription
     * @return string
     */
    public function setOtherVerificationDescription($_otherVerificationDescription)
    {
        return ($this->OtherVerificationDescription = $_otherVerificationDescription);
    }
    /**
     * Get PlaceOfBirth value
     * @return string|null
     */
    public function getPlaceOfBirth()
    {
        return $this->PlaceOfBirth;
    }
    /**
     * Set PlaceOfBirth value
     * @param string $_placeOfBirth the PlaceOfBirth
     * @return string
     */
    public function setPlaceOfBirth($_placeOfBirth)
    {
        return ($this->PlaceOfBirth = $_placeOfBirth);
    }
    /**
     * Get PreferredGivenName value
     * @return string|null
     */
    public function getPreferredGivenName()
    {
        return $this->PreferredGivenName;
    }
    /**
     * Set PreferredGivenName value
     * @param string $_preferredGivenName the PreferredGivenName
     * @return string
     */
    public function setPreferredGivenName($_preferredGivenName)
    {
        return ($this->PreferredGivenName = $_preferredGivenName);
    }
    /**
     * Get PreviousFamilyName value
     * @return string|null
     */
    public function getPreviousFamilyName()
    {
        return $this->PreviousFamilyName;
    }
    /**
     * Set PreviousFamilyName value
     * @param string $_previousFamilyName the PreviousFamilyName
     * @return string
     */
    public function setPreviousFamilyName($_previousFamilyName)
    {
        return ($this->PreviousFamilyName = $_previousFamilyName);
    }
    /**
     * Get SchoolAtAge16 value
     * @return string|null
     */
    public function getSchoolAtAge16()
    {
        return $this->SchoolAtAge16;
    }
    /**
     * Set SchoolAtAge16 value
     * @param string $_schoolAtAge16 the SchoolAtAge16
     * @return string
     */
    public function setSchoolAtAge16($_schoolAtAge16)
    {
        return ($this->SchoolAtAge16 = $_schoolAtAge16);
    }
    /**
     * Get ScottishCandidateNumber value
     * @return string|null
     */
    public function getScottishCandidateNumber()
    {
        return $this->ScottishCandidateNumber;
    }
    /**
     * Set ScottishCandidateNumber value
     * @param string $_scottishCandidateNumber the ScottishCandidateNumber
     * @return string
     */
    public function setScottishCandidateNumber($_scottishCandidateNumber)
    {
        return ($this->ScottishCandidateNumber = $_scottishCandidateNumber);
    }
    /**
     * Get Title value
     * @return string|null
     */
    public function getTitle()
    {
        return $this->Title;
    }
    /**
     * Set Title value
     * @param string $_title the Title
     * @return string
     */
    public function setTitle($_title)
    {
        return ($this->Title = $_title);
    }
    /**
     * Get ULN value
     * @return string|null
     */
    public function getULN()
    {
        return $this->ULN;
    }
    /**
     * Set ULN value
     * @param string $_uLN the ULN
     * @return string
     */
    public function setULN($_uLN)
    {
        return ($this->ULN = $_uLN);
    }
    /**
     * Get VerificationType value
     * @return string|null
     */
    public function getVerificationType()
    {
        return $this->VerificationType;
    }
    /**
     * Set VerificationType value
     * @param string $_verificationType the VerificationType
     * @return string
     */
    public function setVerificationType($_verificationType)
    {
        return ($this->VerificationType = $_verificationType);
    }
    /**
     * Get GetLearnerRecordResult value
     * @return ServiceResponseR9|null
     */
    public function getGetLearnerRecordResult()
    {
        return $this->GetLearnerRecordResult;
    }
    /**
     * Set GetLearnerRecordResult value
     * @param ServiceResponseR9 $_getLearnerRecordResult the GetLearnerRecordResult
     * @return ServiceResponseR9
     */
    public function setGetLearnerRecordResult($_getLearnerRecordResult)
    {
        return ($this->GetLearnerRecordResult = $_getLearnerRecordResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructGetLearnerRecordResponse
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
