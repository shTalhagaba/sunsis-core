<?php

class Learner
{

    /**
     * @var string $CreatedDate
     */
    protected $CreatedDate = null;

    /**
     * @var string $LastUpdatedDate
     */
    protected $LastUpdatedDate = null;

    /**
     * @var string $ULN
     */
    protected $ULN = null;

    /**
     * @var string $MasterSubstituted
     */
    protected $MasterSubstituted = null;

    /**
     * @var string $Title
     */
    protected $Title = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var string $MiddleOtherName
     */
    protected $MiddleOtherName = null;

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

    /**
     * @var string $PreferredGivenName
     */
    protected $PreferredGivenName = null;

    /**
     * @var string $PreviousFamilyName
     */
    protected $PreviousFamilyName = null;

    /**
     * @var string $FamilyNameAtAge16
     */
    protected $FamilyNameAtAge16 = null;

    /**
     * @var string $SchoolAtAge16
     */
    protected $SchoolAtAge16 = null;

    /**
     * @var string $LastKnownAddressLine1
     */
    protected $LastKnownAddressLine1 = null;

    /**
     * @var string $LastKnownAddressLine2
     */
    protected $LastKnownAddressLine2 = null;

    /**
     * @var string $LastKnownAddressTown
     */
    protected $LastKnownAddressTown = null;

    /**
     * @var string $LastKnownAddressCountyOrCity
     */
    protected $LastKnownAddressCountyOrCity = null;

    /**
     * @var string $LastKnownPostCode
     */
    protected $LastKnownPostCode = null;

    /**
     * @var string $DateOfAddressCapture
     */
    protected $DateOfAddressCapture = null;

    /**
     * @var string $DateOfBirth
     */
    protected $DateOfBirth = null;

    /**
     * @var string $PlaceOfBirth
     */
    protected $PlaceOfBirth = null;

    /**
     * @var string $Gender
     */
    protected $Gender = null;

    /**
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    /**
     * @var string $Nationality
     */
    protected $Nationality = null;

    /**
     * @var string $ScottishCandidateNumber
     */
    protected $ScottishCandidateNumber = null;

    /**
     * @var string $VerificationType
     */
    protected $VerificationType = null;

    /**
     * @var string $OtherVerificationDescription
     */
    protected $OtherVerificationDescription = null;

    /**
     * @var string $TierLevel
     */
    protected $TierLevel = null;

    /**
     * @var string $AbilityToShare
     */
    protected $AbilityToShare = null;

    /**
     * @var string $LearnerStatus
     */
    protected $LearnerStatus = null;

    /**
     * @var ArrayOfString $LinkedULNs
     */
    public $LinkedULNs = null;

    /**
     * @var string $Notes
     */
    protected $Notes = null;

    /**
     * @var int $VersionNumber
     */
    protected $VersionNumber = null;

    /**
     * @param int $VersionNumber
     */
    public function __construct($VersionNumber)
    {
      $this->VersionNumber = $VersionNumber;
    }

    /**
     * @return string
     */
    public function getCreatedDate()
    {
      return $this->CreatedDate;
    }

    /**
     * @param string $CreatedDate
     * @return Learner
     */
    public function setCreatedDate($CreatedDate)
    {
      $this->CreatedDate = $CreatedDate;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastUpdatedDate()
    {
      return $this->LastUpdatedDate;
    }

    /**
     * @param string $LastUpdatedDate
     * @return Learner
     */
    public function setLastUpdatedDate($LastUpdatedDate)
    {
      $this->LastUpdatedDate = $LastUpdatedDate;
      return $this;
    }

    /**
     * @return string
     */
    public function getULN()
    {
      return $this->ULN;
    }

    /**
     * @param string $ULN
     * @return Learner
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
      return $this;
    }

    /**
     * @return string
     */
    public function getMasterSubstituted()
    {
      return $this->MasterSubstituted;
    }

    /**
     * @param string $MasterSubstituted
     * @return Learner
     */
    public function setMasterSubstituted($MasterSubstituted)
    {
      $this->MasterSubstituted = $MasterSubstituted;
      return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
      return $this->Title;
    }

    /**
     * @param string $Title
     * @return Learner
     */
    public function setTitle($Title)
    {
      $this->Title = $Title;
      return $this;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
      return $this->GivenName;
    }

    /**
     * @param string $GivenName
     * @return Learner
     */
    public function setGivenName($GivenName)
    {
      $this->GivenName = $GivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getMiddleOtherName()
    {
      return $this->MiddleOtherName;
    }

    /**
     * @param string $MiddleOtherName
     * @return Learner
     */
    public function setMiddleOtherName($MiddleOtherName)
    {
      $this->MiddleOtherName = $MiddleOtherName;
      return $this;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
      return $this->FamilyName;
    }

    /**
     * @param string $FamilyName
     * @return Learner
     */
    public function setFamilyName($FamilyName)
    {
      $this->FamilyName = $FamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreferredGivenName()
    {
      return $this->PreferredGivenName;
    }

    /**
     * @param string $PreferredGivenName
     * @return Learner
     */
    public function setPreferredGivenName($PreferredGivenName)
    {
      $this->PreferredGivenName = $PreferredGivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreviousFamilyName()
    {
      return $this->PreviousFamilyName;
    }

    /**
     * @param string $PreviousFamilyName
     * @return Learner
     */
    public function setPreviousFamilyName($PreviousFamilyName)
    {
      $this->PreviousFamilyName = $PreviousFamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getFamilyNameAtAge16()
    {
      return $this->FamilyNameAtAge16;
    }

    /**
     * @param string $FamilyNameAtAge16
     * @return Learner
     */
    public function setFamilyNameAtAge16($FamilyNameAtAge16)
    {
      $this->FamilyNameAtAge16 = $FamilyNameAtAge16;
      return $this;
    }

    /**
     * @return string
     */
    public function getSchoolAtAge16()
    {
      return $this->SchoolAtAge16;
    }

    /**
     * @param string $SchoolAtAge16
     * @return Learner
     */
    public function setSchoolAtAge16($SchoolAtAge16)
    {
      $this->SchoolAtAge16 = $SchoolAtAge16;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownAddressLine1()
    {
      return $this->LastKnownAddressLine1;
    }

    /**
     * @param string $LastKnownAddressLine1
     * @return Learner
     */
    public function setLastKnownAddressLine1($LastKnownAddressLine1)
    {
      $this->LastKnownAddressLine1 = $LastKnownAddressLine1;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownAddressLine2()
    {
      return $this->LastKnownAddressLine2;
    }

    /**
     * @param string $LastKnownAddressLine2
     * @return Learner
     */
    public function setLastKnownAddressLine2($LastKnownAddressLine2)
    {
      $this->LastKnownAddressLine2 = $LastKnownAddressLine2;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownAddressTown()
    {
      return $this->LastKnownAddressTown;
    }

    /**
     * @param string $LastKnownAddressTown
     * @return Learner
     */
    public function setLastKnownAddressTown($LastKnownAddressTown)
    {
      $this->LastKnownAddressTown = $LastKnownAddressTown;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownAddressCountyOrCity()
    {
      return $this->LastKnownAddressCountyOrCity;
    }

    /**
     * @param string $LastKnownAddressCountyOrCity
     * @return Learner
     */
    public function setLastKnownAddressCountyOrCity($LastKnownAddressCountyOrCity)
    {
      $this->LastKnownAddressCountyOrCity = $LastKnownAddressCountyOrCity;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownPostCode()
    {
      return $this->LastKnownPostCode;
    }

    /**
     * @param string $LastKnownPostCode
     * @return Learner
     */
    public function setLastKnownPostCode($LastKnownPostCode)
    {
      $this->LastKnownPostCode = $LastKnownPostCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getDateOfAddressCapture()
    {
      return $this->DateOfAddressCapture;
    }

    /**
     * @param string $DateOfAddressCapture
     * @return Learner
     */
    public function setDateOfAddressCapture($DateOfAddressCapture)
    {
      $this->DateOfAddressCapture = $DateOfAddressCapture;
      return $this;
    }

    /**
     * @return string
     */
    public function getDateOfBirth()
    {
      return $this->DateOfBirth;
    }

    /**
     * @param string $DateOfBirth
     * @return Learner
     */
    public function setDateOfBirth($DateOfBirth)
    {
      $this->DateOfBirth = $DateOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlaceOfBirth()
    {
      return $this->PlaceOfBirth;
    }

    /**
     * @param string $PlaceOfBirth
     * @return Learner
     */
    public function setPlaceOfBirth($PlaceOfBirth)
    {
      $this->PlaceOfBirth = $PlaceOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
      return $this->Gender;
    }

    /**
     * @param string $Gender
     * @return Learner
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
      return $this->EmailAddress;
    }

    /**
     * @param string $EmailAddress
     * @return Learner
     */
    public function setEmailAddress($EmailAddress)
    {
      $this->EmailAddress = $EmailAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
      return $this->Nationality;
    }

    /**
     * @param string $Nationality
     * @return Learner
     */
    public function setNationality($Nationality)
    {
      $this->Nationality = $Nationality;
      return $this;
    }

    /**
     * @return string
     */
    public function getScottishCandidateNumber()
    {
      return $this->ScottishCandidateNumber;
    }

    /**
     * @param string $ScottishCandidateNumber
     * @return Learner
     */
    public function setScottishCandidateNumber($ScottishCandidateNumber)
    {
      $this->ScottishCandidateNumber = $ScottishCandidateNumber;
      return $this;
    }

    /**
     * @return string
     */
    public function getVerificationType()
    {
      return $this->VerificationType;
    }

    /**
     * @param string $VerificationType
     * @return Learner
     */
    public function setVerificationType($VerificationType)
    {
      $this->VerificationType = $VerificationType;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherVerificationDescription()
    {
      return $this->OtherVerificationDescription;
    }

    /**
     * @param string $OtherVerificationDescription
     * @return Learner
     */
    public function setOtherVerificationDescription($OtherVerificationDescription)
    {
      $this->OtherVerificationDescription = $OtherVerificationDescription;
      return $this;
    }

    /**
     * @return string
     */
    public function getTierLevel()
    {
      return $this->TierLevel;
    }

    /**
     * @param string $TierLevel
     * @return Learner
     */
    public function setTierLevel($TierLevel)
    {
      $this->TierLevel = $TierLevel;
      return $this;
    }

    /**
     * @return string
     */
    public function getAbilityToShare()
    {
      return $this->AbilityToShare;
    }

    /**
     * @param string $AbilityToShare
     * @return Learner
     */
    public function setAbilityToShare($AbilityToShare)
    {
      $this->AbilityToShare = $AbilityToShare;
      return $this;
    }

    /**
     * @return string
     */
    public function getLearnerStatus()
    {
      return $this->LearnerStatus;
    }

    /**
     * @param string $LearnerStatus
     * @return Learner
     */
    public function setLearnerStatus($LearnerStatus)
    {
      $this->LearnerStatus = $LearnerStatus;
      return $this;
    }

    /**
     * @return ArrayOfString
     */
    public function getLinkedULNs()
    {
      return $this->LinkedULNs;
    }

    /**
     * @param ArrayOfString $LinkedULNs
     * @return Learner
     */
    public function setLinkedULNs($LinkedULNs)
    {
      $this->LinkedULNs = $LinkedULNs;
      return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
      return $this->Notes;
    }

    /**
     * @param string $Notes
     * @return Learner
     */
    public function setNotes($Notes)
    {
      $this->Notes = $Notes;
      return $this;
    }

    /**
     * @return int
     */
    public function getVersionNumber()
    {
      return $this->VersionNumber;
    }

    /**
     * @param int $VersionNumber
     * @return Learner
     */
    public function setVersionNumber($VersionNumber)
    {
      $this->VersionNumber = $VersionNumber;
      return $this;
    }

}
