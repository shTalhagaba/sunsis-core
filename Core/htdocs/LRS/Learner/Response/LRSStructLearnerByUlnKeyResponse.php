<?php
/**
 * File for class LRSStructLearnerByUlnKeyResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLearnerByUlnKeyResponse originally named LearnerByUlnKeyResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLearnerByUlnKeyResponse extends LRSStructServiceResponseR9
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
     * The CreatedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CreatedDate;
    /**
     * The DateOfAddressCapture
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DateOfAddressCapture;
    /**
     * The DateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
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
     * The FamilyNameAtSixteen
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FamilyNameAtSixteen;
    /**
     * The FoundULN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FoundULN;
    /**
     * The Gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
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
     * The LastKnownAddressLineOne
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressLineOne;
    /**
     * The LastKnownAddressLineTwo
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressLineTwo;
    /**
     * The LastKnownAddressTown
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressTown;
    /**
     * The LastKnownPostcode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownPostcode;
    /**
     * The LastUpdateDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastUpdateDate;
    /**
     * The LinkedULNs
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $LinkedULNs;
    /**
     * The MasterSubstituted
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var boolean
     */
    public $MasterSubstituted;
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
     * The SchoolAtAgeSixteen
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SchoolAtAgeSixteen;
    /**
     * The ScottishCandidateNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ScottishCandidateNumber;
    /**
     * The Status
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Status;
    /**
     * The Title
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Title;
    /**
     * The VerificationType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $VerificationType;
    /**
     * The VersionNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $VersionNumber;
    /**
     * Constructor method for LearnerByUlnKeyResponse
     * @see parent::__construct()
     * @param string $_abilityToShare
     * @param string $_createdDate
     * @param string $_dateOfAddressCapture
     * @param string $_dateOfBirth
     * @param string $_emailAddress
     * @param string $_familyName
     * @param string $_familyNameAtSixteen
     * @param string $_foundULN
     * @param string $_gender
     * @param string $_givenName
     * @param string $_incomingULN
     * @param string $_lastKnownAddressCountyOrCity
     * @param string $_lastKnownAddressLineOne
     * @param string $_lastKnownAddressLineTwo
     * @param string $_lastKnownAddressTown
     * @param string $_lastKnownPostcode
     * @param string $_lastUpdateDate
     * @param LRSStructArrayOfstring $_linkedULNs
     * @param boolean $_masterSubstituted
     * @param string $_middleOtherName
     * @param string $_nationality
     * @param string $_otherVerificationDescription
     * @param string $_placeOfBirth
     * @param string $_preferredGivenName
     * @param string $_previousFamilyName
     * @param string $_schoolAtAgeSixteen
     * @param string $_scottishCandidateNumber
     * @param string $_status
     * @param string $_title
     * @param string $_verificationType
     * @param int $_versionNumber
     * @return LRSStructLearnerByUlnKeyResponse
     */
    public function __construct($_abilityToShare = NULL,$_createdDate = NULL,$_dateOfAddressCapture = NULL,$_dateOfBirth = NULL,$_emailAddress = NULL,$_familyName = NULL,$_familyNameAtSixteen = NULL,$_foundULN = NULL,$_gender = NULL,$_givenName = NULL,$_incomingULN = NULL,$_lastKnownAddressCountyOrCity = NULL,$_lastKnownAddressLineOne = NULL,$_lastKnownAddressLineTwo = NULL,$_lastKnownAddressTown = NULL,$_lastKnownPostcode = NULL,$_lastUpdateDate = NULL,$_linkedULNs = NULL,$_masterSubstituted = NULL,$_middleOtherName = NULL,$_nationality = NULL,$_otherVerificationDescription = NULL,$_placeOfBirth = NULL,$_preferredGivenName = NULL,$_previousFamilyName = NULL,$_schoolAtAgeSixteen = NULL,$_scottishCandidateNumber = NULL,$_status = NULL,$_title = NULL,$_verificationType = NULL,$_versionNumber = NULL)
    {
        LRSWsdlClass::__construct(array('AbilityToShare'=>$_abilityToShare,'CreatedDate'=>$_createdDate,'DateOfAddressCapture'=>$_dateOfAddressCapture,'DateOfBirth'=>$_dateOfBirth,'EmailAddress'=>$_emailAddress,'FamilyName'=>$_familyName,'FamilyNameAtSixteen'=>$_familyNameAtSixteen,'FoundULN'=>$_foundULN,'Gender'=>$_gender,'GivenName'=>$_givenName,'IncomingULN'=>$_incomingULN,'LastKnownAddressCountyOrCity'=>$_lastKnownAddressCountyOrCity,'LastKnownAddressLineOne'=>$_lastKnownAddressLineOne,'LastKnownAddressLineTwo'=>$_lastKnownAddressLineTwo,'LastKnownAddressTown'=>$_lastKnownAddressTown,'LastKnownPostcode'=>$_lastKnownPostcode,'LastUpdateDate'=>$_lastUpdateDate,'LinkedULNs'=>($_linkedULNs instanceof LRSStructArrayOfstring)?$_linkedULNs:new LRSStructArrayOfstring($_linkedULNs),'MasterSubstituted'=>$_masterSubstituted,'MiddleOtherName'=>$_middleOtherName,'Nationality'=>$_nationality,'OtherVerificationDescription'=>$_otherVerificationDescription,'PlaceOfBirth'=>$_placeOfBirth,'PreferredGivenName'=>$_preferredGivenName,'PreviousFamilyName'=>$_previousFamilyName,'SchoolAtAgeSixteen'=>$_schoolAtAgeSixteen,'ScottishCandidateNumber'=>$_scottishCandidateNumber,'Status'=>$_status,'Title'=>$_title,'VerificationType'=>$_verificationType,'VersionNumber'=>$_versionNumber),false);
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
     * Get CreatedDate value
     * @return string|null
     */
    public function getCreatedDate()
    {
        return $this->CreatedDate;
    }
    /**
     * Set CreatedDate value
     * @param string $_createdDate the CreatedDate
     * @return string
     */
    public function setCreatedDate($_createdDate)
    {
        return ($this->CreatedDate = $_createdDate);
    }
    /**
     * Get DateOfAddressCapture value
     * @return string|null
     */
    public function getDateOfAddressCapture()
    {
        return $this->DateOfAddressCapture;
    }
    /**
     * Set DateOfAddressCapture value
     * @param string $_dateOfAddressCapture the DateOfAddressCapture
     * @return string
     */
    public function setDateOfAddressCapture($_dateOfAddressCapture)
    {
        return ($this->DateOfAddressCapture = $_dateOfAddressCapture);
    }
    /**
     * Get DateOfBirth value
     * @return string|null
     */
    public function getDateOfBirth()
    {
        return $this->DateOfBirth;
    }
    /**
     * Set DateOfBirth value
     * @param string $_dateOfBirth the DateOfBirth
     * @return string
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
     * Get FamilyNameAtSixteen value
     * @return string|null
     */
    public function getFamilyNameAtSixteen()
    {
        return $this->FamilyNameAtSixteen;
    }
    /**
     * Set FamilyNameAtSixteen value
     * @param string $_familyNameAtSixteen the FamilyNameAtSixteen
     * @return string
     */
    public function setFamilyNameAtSixteen($_familyNameAtSixteen)
    {
        return ($this->FamilyNameAtSixteen = $_familyNameAtSixteen);
    }
    /**
     * Get FoundULN value
     * @return string|null
     */
    public function getFoundULN()
    {
        return $this->FoundULN;
    }
    /**
     * Set FoundULN value
     * @param string $_foundULN the FoundULN
     * @return string
     */
    public function setFoundULN($_foundULN)
    {
        return ($this->FoundULN = $_foundULN);
    }
    /**
     * Get Gender value
     * @return string|null
     */
    public function getGender()
    {
        return $this->Gender;
    }
    /**
     * Set Gender value
     * @param string $_gender the Gender
     * @return string
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
     * Get LastKnownAddressLineOne value
     * @return string|null
     */
    public function getLastKnownAddressLineOne()
    {
        return $this->LastKnownAddressLineOne;
    }
    /**
     * Set LastKnownAddressLineOne value
     * @param string $_lastKnownAddressLineOne the LastKnownAddressLineOne
     * @return string
     */
    public function setLastKnownAddressLineOne($_lastKnownAddressLineOne)
    {
        return ($this->LastKnownAddressLineOne = $_lastKnownAddressLineOne);
    }
    /**
     * Get LastKnownAddressLineTwo value
     * @return string|null
     */
    public function getLastKnownAddressLineTwo()
    {
        return $this->LastKnownAddressLineTwo;
    }
    /**
     * Set LastKnownAddressLineTwo value
     * @param string $_lastKnownAddressLineTwo the LastKnownAddressLineTwo
     * @return string
     */
    public function setLastKnownAddressLineTwo($_lastKnownAddressLineTwo)
    {
        return ($this->LastKnownAddressLineTwo = $_lastKnownAddressLineTwo);
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
     * Get LastKnownPostcode value
     * @return string|null
     */
    public function getLastKnownPostcode()
    {
        return $this->LastKnownPostcode;
    }
    /**
     * Set LastKnownPostcode value
     * @param string $_lastKnownPostcode the LastKnownPostcode
     * @return string
     */
    public function setLastKnownPostcode($_lastKnownPostcode)
    {
        return ($this->LastKnownPostcode = $_lastKnownPostcode);
    }
    /**
     * Get LastUpdateDate value
     * @return string|null
     */
    public function getLastUpdateDate()
    {
        return $this->LastUpdateDate;
    }
    /**
     * Set LastUpdateDate value
     * @param string $_lastUpdateDate the LastUpdateDate
     * @return string
     */
    public function setLastUpdateDate($_lastUpdateDate)
    {
        return ($this->LastUpdateDate = $_lastUpdateDate);
    }
    /**
     * Get LinkedULNs value
     * @return LRSStructArrayOfstring|null
     */
    public function getLinkedULNs()
    {
        return $this->LinkedULNs;
    }
    /**
     * Set LinkedULNs value
     * @param LRSStructArrayOfstring $_linkedULNs the LinkedULNs
     * @return LRSStructArrayOfstring
     */
    public function setLinkedULNs($_linkedULNs)
    {
        return ($this->LinkedULNs = $_linkedULNs);
    }
    /**
     * Get MasterSubstituted value
     * @return boolean|null
     */
    public function getMasterSubstituted()
    {
        return $this->MasterSubstituted;
    }
    /**
     * Set MasterSubstituted value
     * @param boolean $_masterSubstituted the MasterSubstituted
     * @return boolean
     */
    public function setMasterSubstituted($_masterSubstituted)
    {
        return ($this->MasterSubstituted = $_masterSubstituted);
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
     * Get SchoolAtAgeSixteen value
     * @return string|null
     */
    public function getSchoolAtAgeSixteen()
    {
        return $this->SchoolAtAgeSixteen;
    }
    /**
     * Set SchoolAtAgeSixteen value
     * @param string $_schoolAtAgeSixteen the SchoolAtAgeSixteen
     * @return string
     */
    public function setSchoolAtAgeSixteen($_schoolAtAgeSixteen)
    {
        return ($this->SchoolAtAgeSixteen = $_schoolAtAgeSixteen);
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
     * Get Status value
     * @return string|null
     */
    public function getStatus()
    {
        return $this->Status;
    }
    /**
     * Set Status value
     * @param string $_status the Status
     * @return string
     */
    public function setStatus($_status)
    {
        return ($this->Status = $_status);
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
     * Get VersionNumber value
     * @return int|null
     */
    public function getVersionNumber()
    {
        return $this->VersionNumber;
    }
    /**
     * Set VersionNumber value
     * @param int $_versionNumber the VersionNumber
     * @return int
     */
    public function setVersionNumber($_versionNumber)
    {
        return ($this->VersionNumber = $_versionNumber);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLearnerByUlnKeyResponse
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
