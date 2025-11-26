<?php

class BatchLearner extends BaseLearnerServiceRequestPart
{

    /**
     * @var string $ULN
     */
    protected $ULN = null;

    /**
     * @var string $MISIdentifier
     */
    protected $MISIdentifier = null;

    /**
     * @var string $Title
     */
    protected $Title = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var string $PreferredGivenName
     */
    protected $PreferredGivenName = null;

    /**
     * @var string $MiddleOtherName
     */
    protected $MiddleOtherName = null;

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

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
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    /**
     * @var string $Gender
     */
    protected $Gender = null;

    /**
     * @var string $Nationality
     */
    protected $Nationality = null;

    /**
     * @var string $ScottishCandidateNumber
     */
    protected $ScottishCandidateNumber = null;

    /**
     * @var string $AbilityToShare
     */
    protected $AbilityToShare = null;

    /**
     * @var string $VerificationType
     */
    protected $VerificationType = null;

    /**
     * @var string $OtherVerificationDescription
     */
    protected $OtherVerificationDescription = null;

    /**
     * @var string $Notes
     */
    protected $Notes = null;

    
    public function __construct()
    {
    
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
     * @return BatchLearner
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
      return $this;
    }

    /**
     * @return string
     */
    public function getMISIdentifier()
    {
      return $this->MISIdentifier;
    }

    /**
     * @param string $MISIdentifier
     * @return BatchLearner
     */
    public function setMISIdentifier($MISIdentifier)
    {
      $this->MISIdentifier = $MISIdentifier;
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
     * @return BatchLearner
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
     * @return BatchLearner
     */
    public function setGivenName($GivenName)
    {
      $this->GivenName = $GivenName;
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
     * @return BatchLearner
     */
    public function setPreferredGivenName($PreferredGivenName)
    {
      $this->PreferredGivenName = $PreferredGivenName;
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
     * @return BatchLearner
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
     * @return BatchLearner
     */
    public function setFamilyName($FamilyName)
    {
      $this->FamilyName = $FamilyName;
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
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
     * @return BatchLearner
     */
    public function setPlaceOfBirth($PlaceOfBirth)
    {
      $this->PlaceOfBirth = $PlaceOfBirth;
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
     * @return BatchLearner
     */
    public function setEmailAddress($EmailAddress)
    {
      $this->EmailAddress = $EmailAddress;
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
     * @return BatchLearner
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
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
     * @return BatchLearner
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
     * @return BatchLearner
     */
    public function setScottishCandidateNumber($ScottishCandidateNumber)
    {
      $this->ScottishCandidateNumber = $ScottishCandidateNumber;
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
     * @return BatchLearner
     */
    public function setAbilityToShare($AbilityToShare)
    {
      $this->AbilityToShare = $AbilityToShare;
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
     * @return BatchLearner
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
     * @return BatchLearner
     */
    public function setOtherVerificationDescription($OtherVerificationDescription)
    {
      $this->OtherVerificationDescription = $OtherVerificationDescription;
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
     * @return BatchLearner
     */
    public function setNotes($Notes)
    {
      $this->Notes = $Notes;
      return $this;
    }

}
