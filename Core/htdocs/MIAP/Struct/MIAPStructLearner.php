<?php
/**
 * File for class MIAPStructLearner
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructLearner originally named Learner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructLearner extends MIAPWsdlClass
{
    /**
     * The CreatedDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $CreatedDate;
    /**
     * The LastUpdatedDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $LastUpdatedDate;
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $ULN;
    /**
     * The GivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $GivenName;
    /**
     * The FamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $FamilyName;
    /**
     * The LastKnownPostCode
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 9
     * - pattern : [bB][fF][pP][oO] ?[0-9]{1,4} ? ? ? ?
     * @var string
     */
    public $LastKnownPostCode;
    /**
     * The DateOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $DateOfBirth;
    /**
     * The Gender
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 1
     * - pattern : 0|1|2|9
     * @var string
     */
    public $Gender;
    /**
     * The AbilityToShare
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 1
     * - minLength : 1
     * - pattern : 0|1|2
     * @var string
     */
    public $AbilityToShare;
    /**
     * The LearnerStatus
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 1
     * - minLength : 1
     * - pattern : 0|1|2
     * @var string
     */
    public $LearnerStatus;
    /**
     * The VersionNumber
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $VersionNumber;
    /**
     * The MasterSubstituted
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $MasterSubstituted;
    /**
     * The Title
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * @var string
     */
    public $Title;
    /**
     * The MiddleOtherName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * @var string
     */
    public $MiddleOtherName;
    /**
     * The PreferredGivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * @var string
     */
    public $PreferredGivenName;
    /**
     * The PreviousFamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * @var string
     */
    public $PreviousFamilyName;
    /**
     * The FamilyNameAtAge16
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * @var string
     */
    public $FamilyNameAtAge16;
    /**
     * The SchoolAtAge16
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 254
     * @var string
     */
    public $SchoolAtAge16;
    /**
     * The LastKnownAddressLine1
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 50
     * @var string
     */
    public $LastKnownAddressLine1;
    /**
     * The LastKnownAddressLine2
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 50
     * @var string
     */
    public $LastKnownAddressLine2;
    /**
     * The LastKnownAddressTown
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 50
     * @var string
     */
    public $LastKnownAddressTown;
    /**
     * The LastKnownAddressCountyOrCity
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 50
     * @var string
     */
    public $LastKnownAddressCountyOrCity;
    /**
     * The DateOfAddressCapture
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $DateOfAddressCapture;
    /**
     * The PlaceOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * @var string
     */
    public $PlaceOfBirth;
    /**
     * The EmailAddress
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 254
     * - pattern : [a-zA-Z0-9!#$%'\*\+\-/=\?\^_`\{\|\}~]+(\.[a-zA-Z0-9!#$%'\*\+\-/=\?\^_`\{\|\}~]+)*@[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9](\.[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])*\.[a-zA-Z]{2,6}
     * @var string
     */
    public $EmailAddress;
    /**
     * The Nationality
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 3
     * @var string
     */
    public $Nationality;
    /**
     * The ScottishCandidateNumber
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 9
     * - pattern : [0-9]{9}
     * @var string
     */
    public $ScottishCandidateNumber;
    /**
     * The VerificationType
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 3
     * - pattern : 0|1|2|3|4|5|6|7|999
     * @var string
     */
    public $VerificationType;
    /**
     * The OtherVerificationDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 255
     * @var string
     */
    public $OtherVerificationDescription;
    /**
     * The TierLevel
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 1
     * - minLength : 1
     * - pattern : 0|1|2
     * @var string
     */
    public $TierLevel;
    /**
     * The LinkedULNs
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var MIAPStructMIAPLinkedULN
     */
    public $LinkedULNs;
    /**
     * The Notes
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 4000
     * @var string
     */
    public $Notes;
    /**
     * Constructor method for Learner
     * @see parent::__construct()
     * @param string $_createdDate
     * @param string $_lastUpdatedDate
     * @param string $_uLN
     * @param string $_givenName
     * @param string $_familyName
     * @param string $_lastKnownPostCode
     * @param string $_dateOfBirth
     * @param string $_gender
     * @param string $_abilityToShare
     * @param string $_learnerStatus
     * @param int $_versionNumber
     * @param string $_masterSubstituted
     * @param string $_title
     * @param string $_middleOtherName
     * @param string $_preferredGivenName
     * @param string $_previousFamilyName
     * @param string $_familyNameAtAge16
     * @param string $_schoolAtAge16
     * @param string $_lastKnownAddressLine1
     * @param string $_lastKnownAddressLine2
     * @param string $_lastKnownAddressTown
     * @param string $_lastKnownAddressCountyOrCity
     * @param string $_dateOfAddressCapture
     * @param string $_placeOfBirth
     * @param string $_emailAddress
     * @param string $_nationality
     * @param string $_scottishCandidateNumber
     * @param string $_verificationType
     * @param string $_otherVerificationDescription
     * @param string $_tierLevel
     * @param MIAPStructMIAPLinkedULN $_linkedULNs
     * @param string $_notes
     * @return MIAPStructLearner
     */
    public function __construct($_createdDate,$_lastUpdatedDate,$_uLN,$_givenName,$_familyName,$_lastKnownPostCode,$_dateOfBirth,$_gender,$_abilityToShare,$_learnerStatus,$_versionNumber,$_masterSubstituted = NULL,$_title = NULL,$_middleOtherName = NULL,$_preferredGivenName = NULL,$_previousFamilyName = NULL,$_familyNameAtAge16 = NULL,$_schoolAtAge16 = NULL,$_lastKnownAddressLine1 = NULL,$_lastKnownAddressLine2 = NULL,$_lastKnownAddressTown = NULL,$_lastKnownAddressCountyOrCity = NULL,$_dateOfAddressCapture = NULL,$_placeOfBirth = NULL,$_emailAddress = NULL,$_nationality = NULL,$_scottishCandidateNumber = NULL,$_verificationType = NULL,$_otherVerificationDescription = NULL,$_tierLevel = NULL,$_linkedULNs = NULL,$_notes = NULL)
    {
        parent::__construct(array('CreatedDate'=>$_createdDate,'LastUpdatedDate'=>$_lastUpdatedDate,'ULN'=>$_uLN,'GivenName'=>$_givenName,'FamilyName'=>$_familyName,'LastKnownPostCode'=>$_lastKnownPostCode,'DateOfBirth'=>$_dateOfBirth,'Gender'=>$_gender,'AbilityToShare'=>$_abilityToShare,'LearnerStatus'=>$_learnerStatus,'VersionNumber'=>$_versionNumber,'MasterSubstituted'=>$_masterSubstituted,'Title'=>$_title,'MiddleOtherName'=>$_middleOtherName,'PreferredGivenName'=>$_preferredGivenName,'PreviousFamilyName'=>$_previousFamilyName,'FamilyNameAtAge16'=>$_familyNameAtAge16,'SchoolAtAge16'=>$_schoolAtAge16,'LastKnownAddressLine1'=>$_lastKnownAddressLine1,'LastKnownAddressLine2'=>$_lastKnownAddressLine2,'LastKnownAddressTown'=>$_lastKnownAddressTown,'LastKnownAddressCountyOrCity'=>$_lastKnownAddressCountyOrCity,'DateOfAddressCapture'=>$_dateOfAddressCapture,'PlaceOfBirth'=>$_placeOfBirth,'EmailAddress'=>$_emailAddress,'Nationality'=>$_nationality,'ScottishCandidateNumber'=>$_scottishCandidateNumber,'VerificationType'=>$_verificationType,'OtherVerificationDescription'=>$_otherVerificationDescription,'TierLevel'=>$_tierLevel,'LinkedULNs'=>$_linkedULNs,'Notes'=>$_notes),false);
    }
    /**
     * Get CreatedDate value
     * @return string
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
     * Get LastUpdatedDate value
     * @return string
     */
    public function getLastUpdatedDate()
    {
        return $this->LastUpdatedDate;
    }
    /**
     * Set LastUpdatedDate value
     * @param string $_lastUpdatedDate the LastUpdatedDate
     * @return string
     */
    public function setLastUpdatedDate($_lastUpdatedDate)
    {
        return ($this->LastUpdatedDate = $_lastUpdatedDate);
    }
    /**
     * Get ULN value
     * @return string
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
     * Get GivenName value
     * @return string
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
     * Get FamilyName value
     * @return string
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
     * Get LastKnownPostCode value
     * @return string
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
     * Get DateOfBirth value
     * @return string
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
     * Get Gender value
     * @return string
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
     * Get AbilityToShare value
     * @return string
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
     * Get LearnerStatus value
     * @return string
     */
    public function getLearnerStatus()
    {
        return $this->LearnerStatus;
    }
    /**
     * Set LearnerStatus value
     * @param string $_learnerStatus the LearnerStatus
     * @return string
     */
    public function setLearnerStatus($_learnerStatus)
    {
        return ($this->LearnerStatus = $_learnerStatus);
    }
    /**
     * Get VersionNumber value
     * @return int
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
     * Get MasterSubstituted value
     * @return string|null
     */
    public function getMasterSubstituted()
    {
        return $this->MasterSubstituted;
    }
    /**
     * Set MasterSubstituted value
     * @param string $_masterSubstituted the MasterSubstituted
     * @return string
     */
    public function setMasterSubstituted($_masterSubstituted)
    {
        return ($this->MasterSubstituted = $_masterSubstituted);
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
     * Get FamilyNameAtAge16 value
     * @return string|null
     */
    public function getFamilyNameAtAge16()
    {
        return $this->FamilyNameAtAge16;
    }
    /**
     * Set FamilyNameAtAge16 value
     * @param string $_familyNameAtAge16 the FamilyNameAtAge16
     * @return string
     */
    public function setFamilyNameAtAge16($_familyNameAtAge16)
    {
        return ($this->FamilyNameAtAge16 = $_familyNameAtAge16);
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
     * Get TierLevel value
     * @return string|null
     */
    public function getTierLevel()
    {
        return $this->TierLevel;
    }
    /**
     * Set TierLevel value
     * @param string $_tierLevel the TierLevel
     * @return string
     */
    public function setTierLevel($_tierLevel)
    {
        return ($this->TierLevel = $_tierLevel);
    }
    /**
     * Get LinkedULNs value
     * @return MIAPStructMIAPLinkedULN|null
     */
    public function getLinkedULNs()
    {
        return $this->LinkedULNs;
    }
    /**
     * Set LinkedULNs value
     * @param MIAPStructMIAPLinkedULN $_linkedULNs the LinkedULNs
     * @return MIAPStructMIAPLinkedULN
     */
    public function setLinkedULNs($_linkedULNs)
    {
        return ($this->LinkedULNs = $_linkedULNs);
    }
    /**
     * Get Notes value
     * @return string|null
     */
    public function getNotes()
    {
        return $this->Notes;
    }
    /**
     * Set Notes value
     * @param string $_notes the Notes
     * @return string
     */
    public function setNotes($_notes)
    {
        return ($this->Notes = $_notes);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructLearner
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
