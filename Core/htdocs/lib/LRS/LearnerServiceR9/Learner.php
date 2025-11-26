<?php

class Learner extends BusinessObject
{

    /**
     * @var int $AbilityToShare
     */
    protected $AbilityToShare = null;

    /**
     * @var char $AtsLearnerPreference
     */
    protected $AtsLearnerPreference = null;

    /**
     * @var int $BirthDateVerification
     */
    protected $BirthDateVerification = null;

    /**
     * @var string $CountryOfAddress
     */
    protected $CountryOfAddress = null;

    /**
     * @var string $CountryOfBirth
     */
    protected $CountryOfBirth = null;

    /**
     * @var string $CountryOfDomicle
     */
    protected $CountryOfDomicle = null;

    /**
     * @var string $CountryOfLastKnownAddress
     */
    protected $CountryOfLastKnownAddress = null;

    /**
     * @var \DateTime $CreatedDate
     */
    protected $CreatedDate = null;

    /**
     * @var int $CreatedViaChannel
     */
    protected $CreatedViaChannel = null;

    /**
     * @var \DateTime $DateOfAddressCapture
     */
    protected $DateOfAddressCapture = null;

    /**
     * @var \DateTime $DateOfBirth
     */
    protected $DateOfBirth = null;

    /**
     * @var \DateTime $DateOfDeath
     */
    protected $DateOfDeath = null;

    /**
     * @var int $DeathDateVerification
     */
    protected $DeathDateVerification = null;

    /**
     * @var int $Deceased
     */
    protected $Deceased = null;

    /**
     * @var \DateTime $DeceasedDate
     */
    protected $DeceasedDate = null;

    /**
     * @var int $DisabilityCode
     */
    protected $DisabilityCode = null;

    /**
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    /**
     * @var string $EthnicityCode
     */
    protected $EthnicityCode = null;

    /**
     * @var int $EthnicityVerification
     */
    protected $EthnicityVerification = null;

    /**
     * @var int $EtlLoadId
     */
    protected $EtlLoadId = null;

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

    /**
     * @var string $FamilyNameAt16
     */
    protected $FamilyNameAt16 = null;

    /**
     * @var boolean $FamilyNameFirst
     */
    protected $FamilyNameFirst = null;

    /**
     * @var int $FirstEstablismentId
     */
    protected $FirstEstablismentId = null;

    /**
     * @var int $Gender
     */
    protected $Gender = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var int $InReceiptOfDsa
     */
    protected $InReceiptOfDsa = null;

    /**
     * @var string $Initials
     */
    protected $Initials = null;

    /**
     * @var \DateTime $LastCompiledLearnerPlanReport
     */
    protected $LastCompiledLearnerPlanReport = null;

    /**
     * @var \DateTime $LastCompiledLearnerRecordReport
     */
    protected $LastCompiledLearnerRecordReport = null;

    /**
     * @var boolean $LastKnownAddressChanged
     */
    protected $LastKnownAddressChanged = null;

    /**
     * @var string $LastKnownAddressCountyOrCity
     */
    protected $LastKnownAddressCountyOrCity = null;

    /**
     * @var string $LastKnownAddressLine1
     */
    protected $LastKnownAddressLine1 = null;

    /**
     * @var string $LastKnownAddressLine2
     */
    protected $LastKnownAddressLine2 = null;

    /**
     * @var int $LastKnownAddressQualififer
     */
    protected $LastKnownAddressQualififer = null;

    /**
     * @var string $LastKnownAddressTown
     */
    protected $LastKnownAddressTown = null;

    /**
     * @var int $LastKnownGender
     */
    protected $LastKnownGender = null;

    /**
     * @var string $LastKnownPostCode
     */
    protected $LastKnownPostCode = null;

    /**
     * @var string $LastUpdatedAction
     */
    protected $LastUpdatedAction = null;

    /**
     * @var \DateTime $LastUpdatedDate
     */
    protected $LastUpdatedDate = null;

    /**
     * @var string $LastUpdatedLrbUsername
     */
    protected $LastUpdatedLrbUsername = null;

    /**
     * @var string $LastUpdatedLrsUsername
     */
    protected $LastUpdatedLrsUsername = null;

    /**
     * @var int $LastUpdatedSearchKey
     */
    protected $LastUpdatedSearchKey = null;

    /**
     * @var int $LastUpdatedViaChannel
     */
    protected $LastUpdatedViaChannel = null;

    /**
     * @var int $LearningDifficultyCode
     */
    protected $LearningDifficultyCode = null;

    /**
     * @var ArrayOfstring $LinkedUlns
     */
    protected $LinkedUlns = null;

    /**
     * @var boolean $ManualUpdate
     */
    protected $ManualUpdate = null;

    /**
     * @var int $MaritalStatus
     */
    protected $MaritalStatus = null;

    /**
     * @var int $MaritalStatusVerification
     */
    protected $MaritalStatusVerification = null;

    /**
     * @var string $MasterUln
     */
    protected $MasterUln = null;

    /**
     * @var string $MiddleOtherName
     */
    protected $MiddleOtherName = null;

    /**
     * @var string $NameSuffix
     */
    protected $NameSuffix = null;

    /**
     * @var string $NationalInsuranceNumber
     */
    protected $NationalInsuranceNumber = null;

    /**
     * @var string $Nationality
     */
    protected $Nationality = null;

    /**
     * @var int $NextDataChallengeNo
     */
    protected $NextDataChallengeNo = null;

    /**
     * @var string $NormalisedFamilyName
     */
    protected $NormalisedFamilyName = null;

    /**
     * @var string $NormalisedGivenName
     */
    protected $NormalisedGivenName = null;

    /**
     * @var string $NormalisedOtherNames
     */
    protected $NormalisedOtherNames = null;

    /**
     * @var string $NormalisedPerferredGivenName
     */
    protected $NormalisedPerferredGivenName = null;

    /**
     * @var string $NormalisedPreviousFamilyName
     */
    protected $NormalisedPreviousFamilyName = null;

    /**
     * @var string $Notes
     */
    protected $Notes = null;

    /**
     * @var string $OtherVerificationDescription
     */
    protected $OtherVerificationDescription = null;

    /**
     * @var string $PlaceOfBirth
     */
    protected $PlaceOfBirth = null;

    /**
     * @var int $PotentialDuplicateSearchKey
     */
    protected $PotentialDuplicateSearchKey = null;

    /**
     * @var boolean $PreferredFamilyFirstNameFirst
     */
    protected $PreferredFamilyFirstNameFirst = null;

    /**
     * @var string $PreferredFamilyName
     */
    protected $PreferredFamilyName = null;

    /**
     * @var string $PreferredGivenName
     */
    protected $PreferredGivenName = null;

    /**
     * @var string $PreviousFamilyName
     */
    protected $PreviousFamilyName = null;

    /**
     * @var string $ReasonForDeletion
     */
    protected $ReasonForDeletion = null;

    /**
     * @var int $RecordStatus
     */
    protected $RecordStatus = null;

    /**
     * @var string $ReferenceNumber
     */
    protected $ReferenceNumber = null;

    /**
     * @var string $RegisteredByOrganisationRef
     */
    protected $RegisteredByOrganisationRef = null;

    /**
     * @var boolean $RestrictedUse
     */
    protected $RestrictedUse = null;

    /**
     * @var string $SchoolAtAge16
     */
    protected $SchoolAtAge16 = null;

    /**
     * @var string $ScottishCandidateNumber
     */
    protected $ScottishCandidateNumber = null;

    /**
     * @var int $SecretQuestionId
     */
    protected $SecretQuestionId = null;

    /**
     * @var string $SecurityAnswer
     */
    protected $SecurityAnswer = null;

    /**
     * @var int $SenType
     */
    protected $SenType = null;

    /**
     * @var int $SequentialVersionNumber
     */
    protected $SequentialVersionNumber = null;

    /**
     * @var string $TelephoneNumber
     */
    protected $TelephoneNumber = null;

    /**
     * @var int $TierLevel
     */
    protected $TierLevel = null;

    /**
     * @var string $Title
     */
    protected $Title = null;

    /**
     * @var string $Uln
     */
    protected $Uln = null;

    /**
     * @var string $UniqueCandidateIdentifier
     */
    protected $UniqueCandidateIdentifier = null;

    /**
     * @var string $UniquePupilNumber
     */
    protected $UniquePupilNumber = null;

    /**
     * @var int $VerificationType
     */
    protected $VerificationType = null;

    /**
     * @var int $VersionNumber
     */
    protected $VersionNumber = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return int
     */
    public function getAbilityToShare()
    {
      return $this->AbilityToShare;
    }

    /**
     * @param int $AbilityToShare
     * @return Learner
     */
    public function setAbilityToShare($AbilityToShare)
    {
      $this->AbilityToShare = $AbilityToShare;
      return $this;
    }

    /**
     * @return char
     */
    public function getAtsLearnerPreference()
    {
      return $this->AtsLearnerPreference;
    }

    /**
     * @param char $AtsLearnerPreference
     * @return Learner
     */
    public function setAtsLearnerPreference($AtsLearnerPreference)
    {
      $this->AtsLearnerPreference = $AtsLearnerPreference;
      return $this;
    }

    /**
     * @return int
     */
    public function getBirthDateVerification()
    {
      return $this->BirthDateVerification;
    }

    /**
     * @param int $BirthDateVerification
     * @return Learner
     */
    public function setBirthDateVerification($BirthDateVerification)
    {
      $this->BirthDateVerification = $BirthDateVerification;
      return $this;
    }

    /**
     * @return string
     */
    public function getCountryOfAddress()
    {
      return $this->CountryOfAddress;
    }

    /**
     * @param string $CountryOfAddress
     * @return Learner
     */
    public function setCountryOfAddress($CountryOfAddress)
    {
      $this->CountryOfAddress = $CountryOfAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getCountryOfBirth()
    {
      return $this->CountryOfBirth;
    }

    /**
     * @param string $CountryOfBirth
     * @return Learner
     */
    public function setCountryOfBirth($CountryOfBirth)
    {
      $this->CountryOfBirth = $CountryOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getCountryOfDomicle()
    {
      return $this->CountryOfDomicle;
    }

    /**
     * @param string $CountryOfDomicle
     * @return Learner
     */
    public function setCountryOfDomicle($CountryOfDomicle)
    {
      $this->CountryOfDomicle = $CountryOfDomicle;
      return $this;
    }

    /**
     * @return string
     */
    public function getCountryOfLastKnownAddress()
    {
      return $this->CountryOfLastKnownAddress;
    }

    /**
     * @param string $CountryOfLastKnownAddress
     * @return Learner
     */
    public function setCountryOfLastKnownAddress($CountryOfLastKnownAddress)
    {
      $this->CountryOfLastKnownAddress = $CountryOfLastKnownAddress;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
      if ($this->CreatedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->CreatedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $CreatedDate
     * @return Learner
     */
    public function setCreatedDate(\DateTime $CreatedDate = null)
    {
      if ($CreatedDate == null) {
       $this->CreatedDate = null;
      } else {
        $this->CreatedDate = $CreatedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return int
     */
    public function getCreatedViaChannel()
    {
      return $this->CreatedViaChannel;
    }

    /**
     * @param int $CreatedViaChannel
     * @return Learner
     */
    public function setCreatedViaChannel($CreatedViaChannel)
    {
      $this->CreatedViaChannel = $CreatedViaChannel;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfAddressCapture()
    {
      if ($this->DateOfAddressCapture == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->DateOfAddressCapture);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $DateOfAddressCapture
     * @return Learner
     */
    public function setDateOfAddressCapture(\DateTime $DateOfAddressCapture = null)
    {
      if ($DateOfAddressCapture == null) {
       $this->DateOfAddressCapture = null;
      } else {
        $this->DateOfAddressCapture = $DateOfAddressCapture->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
      if ($this->DateOfBirth == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->DateOfBirth);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $DateOfBirth
     * @return Learner
     */
    public function setDateOfBirth(\DateTime $DateOfBirth = null)
    {
      if ($DateOfBirth == null) {
       $this->DateOfBirth = null;
      } else {
        $this->DateOfBirth = $DateOfBirth->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfDeath()
    {
      if ($this->DateOfDeath == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->DateOfDeath);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $DateOfDeath
     * @return Learner
     */
    public function setDateOfDeath(\DateTime $DateOfDeath = null)
    {
      if ($DateOfDeath == null) {
       $this->DateOfDeath = null;
      } else {
        $this->DateOfDeath = $DateOfDeath->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return int
     */
    public function getDeathDateVerification()
    {
      return $this->DeathDateVerification;
    }

    /**
     * @param int $DeathDateVerification
     * @return Learner
     */
    public function setDeathDateVerification($DeathDateVerification)
    {
      $this->DeathDateVerification = $DeathDateVerification;
      return $this;
    }

    /**
     * @return int
     */
    public function getDeceased()
    {
      return $this->Deceased;
    }

    /**
     * @param int $Deceased
     * @return Learner
     */
    public function setDeceased($Deceased)
    {
      $this->Deceased = $Deceased;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeceasedDate()
    {
      if ($this->DeceasedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->DeceasedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $DeceasedDate
     * @return Learner
     */
    public function setDeceasedDate(\DateTime $DeceasedDate = null)
    {
      if ($DeceasedDate == null) {
       $this->DeceasedDate = null;
      } else {
        $this->DeceasedDate = $DeceasedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return int
     */
    public function getDisabilityCode()
    {
      return $this->DisabilityCode;
    }

    /**
     * @param int $DisabilityCode
     * @return Learner
     */
    public function setDisabilityCode($DisabilityCode)
    {
      $this->DisabilityCode = $DisabilityCode;
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
    public function getEthnicityCode()
    {
      return $this->EthnicityCode;
    }

    /**
     * @param string $EthnicityCode
     * @return Learner
     */
    public function setEthnicityCode($EthnicityCode)
    {
      $this->EthnicityCode = $EthnicityCode;
      return $this;
    }

    /**
     * @return int
     */
    public function getEthnicityVerification()
    {
      return $this->EthnicityVerification;
    }

    /**
     * @param int $EthnicityVerification
     * @return Learner
     */
    public function setEthnicityVerification($EthnicityVerification)
    {
      $this->EthnicityVerification = $EthnicityVerification;
      return $this;
    }

    /**
     * @return int
     */
    public function getEtlLoadId()
    {
      return $this->EtlLoadId;
    }

    /**
     * @param int $EtlLoadId
     * @return Learner
     */
    public function setEtlLoadId($EtlLoadId)
    {
      $this->EtlLoadId = $EtlLoadId;
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
    public function getFamilyNameAt16()
    {
      return $this->FamilyNameAt16;
    }

    /**
     * @param string $FamilyNameAt16
     * @return Learner
     */
    public function setFamilyNameAt16($FamilyNameAt16)
    {
      $this->FamilyNameAt16 = $FamilyNameAt16;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getFamilyNameFirst()
    {
      return $this->FamilyNameFirst;
    }

    /**
     * @param boolean $FamilyNameFirst
     * @return Learner
     */
    public function setFamilyNameFirst($FamilyNameFirst)
    {
      $this->FamilyNameFirst = $FamilyNameFirst;
      return $this;
    }

    /**
     * @return int
     */
    public function getFirstEstablismentId()
    {
      return $this->FirstEstablismentId;
    }

    /**
     * @param int $FirstEstablismentId
     * @return Learner
     */
    public function setFirstEstablismentId($FirstEstablismentId)
    {
      $this->FirstEstablismentId = $FirstEstablismentId;
      return $this;
    }

    /**
     * @return int
     */
    public function getGender()
    {
      return $this->Gender;
    }

    /**
     * @param int $Gender
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
     * @return int
     */
    public function getInReceiptOfDsa()
    {
      return $this->InReceiptOfDsa;
    }

    /**
     * @param int $InReceiptOfDsa
     * @return Learner
     */
    public function setInReceiptOfDsa($InReceiptOfDsa)
    {
      $this->InReceiptOfDsa = $InReceiptOfDsa;
      return $this;
    }

    /**
     * @return string
     */
    public function getInitials()
    {
      return $this->Initials;
    }

    /**
     * @param string $Initials
     * @return Learner
     */
    public function setInitials($Initials)
    {
      $this->Initials = $Initials;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastCompiledLearnerPlanReport()
    {
      if ($this->LastCompiledLearnerPlanReport == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastCompiledLearnerPlanReport);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastCompiledLearnerPlanReport
     * @return Learner
     */
    public function setLastCompiledLearnerPlanReport(\DateTime $LastCompiledLearnerPlanReport = null)
    {
      if ($LastCompiledLearnerPlanReport == null) {
       $this->LastCompiledLearnerPlanReport = null;
      } else {
        $this->LastCompiledLearnerPlanReport = $LastCompiledLearnerPlanReport->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastCompiledLearnerRecordReport()
    {
      if ($this->LastCompiledLearnerRecordReport == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastCompiledLearnerRecordReport);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastCompiledLearnerRecordReport
     * @return Learner
     */
    public function setLastCompiledLearnerRecordReport(\DateTime $LastCompiledLearnerRecordReport = null)
    {
      if ($LastCompiledLearnerRecordReport == null) {
       $this->LastCompiledLearnerRecordReport = null;
      } else {
        $this->LastCompiledLearnerRecordReport = $LastCompiledLearnerRecordReport->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLastKnownAddressChanged()
    {
      return $this->LastKnownAddressChanged;
    }

    /**
     * @param boolean $LastKnownAddressChanged
     * @return Learner
     */
    public function setLastKnownAddressChanged($LastKnownAddressChanged)
    {
      $this->LastKnownAddressChanged = $LastKnownAddressChanged;
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
     * @return int
     */
    public function getLastKnownAddressQualififer()
    {
      return $this->LastKnownAddressQualififer;
    }

    /**
     * @param int $LastKnownAddressQualififer
     * @return Learner
     */
    public function setLastKnownAddressQualififer($LastKnownAddressQualififer)
    {
      $this->LastKnownAddressQualififer = $LastKnownAddressQualififer;
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
     * @return int
     */
    public function getLastKnownGender()
    {
      return $this->LastKnownGender;
    }

    /**
     * @param int $LastKnownGender
     * @return Learner
     */
    public function setLastKnownGender($LastKnownGender)
    {
      $this->LastKnownGender = $LastKnownGender;
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
    public function getLastUpdatedAction()
    {
      return $this->LastUpdatedAction;
    }

    /**
     * @param string $LastUpdatedAction
     * @return Learner
     */
    public function setLastUpdatedAction($LastUpdatedAction)
    {
      $this->LastUpdatedAction = $LastUpdatedAction;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdatedDate()
    {
      if ($this->LastUpdatedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastUpdatedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastUpdatedDate
     * @return Learner
     */
    public function setLastUpdatedDate(\DateTime $LastUpdatedDate = null)
    {
      if ($LastUpdatedDate == null) {
       $this->LastUpdatedDate = null;
      } else {
        $this->LastUpdatedDate = $LastUpdatedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return string
     */
    public function getLastUpdatedLrbUsername()
    {
      return $this->LastUpdatedLrbUsername;
    }

    /**
     * @param string $LastUpdatedLrbUsername
     * @return Learner
     */
    public function setLastUpdatedLrbUsername($LastUpdatedLrbUsername)
    {
      $this->LastUpdatedLrbUsername = $LastUpdatedLrbUsername;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastUpdatedLrsUsername()
    {
      return $this->LastUpdatedLrsUsername;
    }

    /**
     * @param string $LastUpdatedLrsUsername
     * @return Learner
     */
    public function setLastUpdatedLrsUsername($LastUpdatedLrsUsername)
    {
      $this->LastUpdatedLrsUsername = $LastUpdatedLrsUsername;
      return $this;
    }

    /**
     * @return int
     */
    public function getLastUpdatedSearchKey()
    {
      return $this->LastUpdatedSearchKey;
    }

    /**
     * @param int $LastUpdatedSearchKey
     * @return Learner
     */
    public function setLastUpdatedSearchKey($LastUpdatedSearchKey)
    {
      $this->LastUpdatedSearchKey = $LastUpdatedSearchKey;
      return $this;
    }

    /**
     * @return int
     */
    public function getLastUpdatedViaChannel()
    {
      return $this->LastUpdatedViaChannel;
    }

    /**
     * @param int $LastUpdatedViaChannel
     * @return Learner
     */
    public function setLastUpdatedViaChannel($LastUpdatedViaChannel)
    {
      $this->LastUpdatedViaChannel = $LastUpdatedViaChannel;
      return $this;
    }

    /**
     * @return int
     */
    public function getLearningDifficultyCode()
    {
      return $this->LearningDifficultyCode;
    }

    /**
     * @param int $LearningDifficultyCode
     * @return Learner
     */
    public function setLearningDifficultyCode($LearningDifficultyCode)
    {
      $this->LearningDifficultyCode = $LearningDifficultyCode;
      return $this;
    }

    /**
     * @return ArrayOfstring
     */
    public function getLinkedUlns()
    {
      return $this->LinkedUlns;
    }

    /**
     * @param ArrayOfstring $LinkedUlns
     * @return Learner
     */
    public function setLinkedUlns($LinkedUlns)
    {
      $this->LinkedUlns = $LinkedUlns;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getManualUpdate()
    {
      return $this->ManualUpdate;
    }

    /**
     * @param boolean $ManualUpdate
     * @return Learner
     */
    public function setManualUpdate($ManualUpdate)
    {
      $this->ManualUpdate = $ManualUpdate;
      return $this;
    }

    /**
     * @return int
     */
    public function getMaritalStatus()
    {
      return $this->MaritalStatus;
    }

    /**
     * @param int $MaritalStatus
     * @return Learner
     */
    public function setMaritalStatus($MaritalStatus)
    {
      $this->MaritalStatus = $MaritalStatus;
      return $this;
    }

    /**
     * @return int
     */
    public function getMaritalStatusVerification()
    {
      return $this->MaritalStatusVerification;
    }

    /**
     * @param int $MaritalStatusVerification
     * @return Learner
     */
    public function setMaritalStatusVerification($MaritalStatusVerification)
    {
      $this->MaritalStatusVerification = $MaritalStatusVerification;
      return $this;
    }

    /**
     * @return string
     */
    public function getMasterUln()
    {
      return $this->MasterUln;
    }

    /**
     * @param string $MasterUln
     * @return Learner
     */
    public function setMasterUln($MasterUln)
    {
      $this->MasterUln = $MasterUln;
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
    public function getNameSuffix()
    {
      return $this->NameSuffix;
    }

    /**
     * @param string $NameSuffix
     * @return Learner
     */
    public function setNameSuffix($NameSuffix)
    {
      $this->NameSuffix = $NameSuffix;
      return $this;
    }

    /**
     * @return string
     */
    public function getNationalInsuranceNumber()
    {
      return $this->NationalInsuranceNumber;
    }

    /**
     * @param string $NationalInsuranceNumber
     * @return Learner
     */
    public function setNationalInsuranceNumber($NationalInsuranceNumber)
    {
      $this->NationalInsuranceNumber = $NationalInsuranceNumber;
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
     * @return int
     */
    public function getNextDataChallengeNo()
    {
      return $this->NextDataChallengeNo;
    }

    /**
     * @param int $NextDataChallengeNo
     * @return Learner
     */
    public function setNextDataChallengeNo($NextDataChallengeNo)
    {
      $this->NextDataChallengeNo = $NextDataChallengeNo;
      return $this;
    }

    /**
     * @return string
     */
    public function getNormalisedFamilyName()
    {
      return $this->NormalisedFamilyName;
    }

    /**
     * @param string $NormalisedFamilyName
     * @return Learner
     */
    public function setNormalisedFamilyName($NormalisedFamilyName)
    {
      $this->NormalisedFamilyName = $NormalisedFamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getNormalisedGivenName()
    {
      return $this->NormalisedGivenName;
    }

    /**
     * @param string $NormalisedGivenName
     * @return Learner
     */
    public function setNormalisedGivenName($NormalisedGivenName)
    {
      $this->NormalisedGivenName = $NormalisedGivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getNormalisedOtherNames()
    {
      return $this->NormalisedOtherNames;
    }

    /**
     * @param string $NormalisedOtherNames
     * @return Learner
     */
    public function setNormalisedOtherNames($NormalisedOtherNames)
    {
      $this->NormalisedOtherNames = $NormalisedOtherNames;
      return $this;
    }

    /**
     * @return string
     */
    public function getNormalisedPerferredGivenName()
    {
      return $this->NormalisedPerferredGivenName;
    }

    /**
     * @param string $NormalisedPerferredGivenName
     * @return Learner
     */
    public function setNormalisedPerferredGivenName($NormalisedPerferredGivenName)
    {
      $this->NormalisedPerferredGivenName = $NormalisedPerferredGivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getNormalisedPreviousFamilyName()
    {
      return $this->NormalisedPreviousFamilyName;
    }

    /**
     * @param string $NormalisedPreviousFamilyName
     * @return Learner
     */
    public function setNormalisedPreviousFamilyName($NormalisedPreviousFamilyName)
    {
      $this->NormalisedPreviousFamilyName = $NormalisedPreviousFamilyName;
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
     * @return int
     */
    public function getPotentialDuplicateSearchKey()
    {
      return $this->PotentialDuplicateSearchKey;
    }

    /**
     * @param int $PotentialDuplicateSearchKey
     * @return Learner
     */
    public function setPotentialDuplicateSearchKey($PotentialDuplicateSearchKey)
    {
      $this->PotentialDuplicateSearchKey = $PotentialDuplicateSearchKey;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getPreferredFamilyFirstNameFirst()
    {
      return $this->PreferredFamilyFirstNameFirst;
    }

    /**
     * @param boolean $PreferredFamilyFirstNameFirst
     * @return Learner
     */
    public function setPreferredFamilyFirstNameFirst($PreferredFamilyFirstNameFirst)
    {
      $this->PreferredFamilyFirstNameFirst = $PreferredFamilyFirstNameFirst;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreferredFamilyName()
    {
      return $this->PreferredFamilyName;
    }

    /**
     * @param string $PreferredFamilyName
     * @return Learner
     */
    public function setPreferredFamilyName($PreferredFamilyName)
    {
      $this->PreferredFamilyName = $PreferredFamilyName;
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
    public function getReasonForDeletion()
    {
      return $this->ReasonForDeletion;
    }

    /**
     * @param string $ReasonForDeletion
     * @return Learner
     */
    public function setReasonForDeletion($ReasonForDeletion)
    {
      $this->ReasonForDeletion = $ReasonForDeletion;
      return $this;
    }

    /**
     * @return int
     */
    public function getRecordStatus()
    {
      return $this->RecordStatus;
    }

    /**
     * @param int $RecordStatus
     * @return Learner
     */
    public function setRecordStatus($RecordStatus)
    {
      $this->RecordStatus = $RecordStatus;
      return $this;
    }

    /**
     * @return string
     */
    public function getReferenceNumber()
    {
      return $this->ReferenceNumber;
    }

    /**
     * @param string $ReferenceNumber
     * @return Learner
     */
    public function setReferenceNumber($ReferenceNumber)
    {
      $this->ReferenceNumber = $ReferenceNumber;
      return $this;
    }

    /**
     * @return string
     */
    public function getRegisteredByOrganisationRef()
    {
      return $this->RegisteredByOrganisationRef;
    }

    /**
     * @param string $RegisteredByOrganisationRef
     * @return Learner
     */
    public function setRegisteredByOrganisationRef($RegisteredByOrganisationRef)
    {
      $this->RegisteredByOrganisationRef = $RegisteredByOrganisationRef;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getRestrictedUse()
    {
      return $this->RestrictedUse;
    }

    /**
     * @param boolean $RestrictedUse
     * @return Learner
     */
    public function setRestrictedUse($RestrictedUse)
    {
      $this->RestrictedUse = $RestrictedUse;
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
     * @return int
     */
    public function getSecretQuestionId()
    {
      return $this->SecretQuestionId;
    }

    /**
     * @param int $SecretQuestionId
     * @return Learner
     */
    public function setSecretQuestionId($SecretQuestionId)
    {
      $this->SecretQuestionId = $SecretQuestionId;
      return $this;
    }

    /**
     * @return string
     */
    public function getSecurityAnswer()
    {
      return $this->SecurityAnswer;
    }

    /**
     * @param string $SecurityAnswer
     * @return Learner
     */
    public function setSecurityAnswer($SecurityAnswer)
    {
      $this->SecurityAnswer = $SecurityAnswer;
      return $this;
    }

    /**
     * @return int
     */
    public function getSenType()
    {
      return $this->SenType;
    }

    /**
     * @param int $SenType
     * @return Learner
     */
    public function setSenType($SenType)
    {
      $this->SenType = $SenType;
      return $this;
    }

    /**
     * @return int
     */
    public function getSequentialVersionNumber()
    {
      return $this->SequentialVersionNumber;
    }

    /**
     * @param int $SequentialVersionNumber
     * @return Learner
     */
    public function setSequentialVersionNumber($SequentialVersionNumber)
    {
      $this->SequentialVersionNumber = $SequentialVersionNumber;
      return $this;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber()
    {
      return $this->TelephoneNumber;
    }

    /**
     * @param string $TelephoneNumber
     * @return Learner
     */
    public function setTelephoneNumber($TelephoneNumber)
    {
      $this->TelephoneNumber = $TelephoneNumber;
      return $this;
    }

    /**
     * @return int
     */
    public function getTierLevel()
    {
      return $this->TierLevel;
    }

    /**
     * @param int $TierLevel
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
    public function getUln()
    {
      return $this->Uln;
    }

    /**
     * @param string $Uln
     * @return Learner
     */
    public function setUln($Uln)
    {
      $this->Uln = $Uln;
      return $this;
    }

    /**
     * @return string
     */
    public function getUniqueCandidateIdentifier()
    {
      return $this->UniqueCandidateIdentifier;
    }

    /**
     * @param string $UniqueCandidateIdentifier
     * @return Learner
     */
    public function setUniqueCandidateIdentifier($UniqueCandidateIdentifier)
    {
      $this->UniqueCandidateIdentifier = $UniqueCandidateIdentifier;
      return $this;
    }

    /**
     * @return string
     */
    public function getUniquePupilNumber()
    {
      return $this->UniquePupilNumber;
    }

    /**
     * @param string $UniquePupilNumber
     * @return Learner
     */
    public function setUniquePupilNumber($UniquePupilNumber)
    {
      $this->UniquePupilNumber = $UniquePupilNumber;
      return $this;
    }

    /**
     * @return int
     */
    public function getVerificationType()
    {
      return $this->VerificationType;
    }

    /**
     * @param int $VerificationType
     * @return Learner
     */
    public function setVerificationType($VerificationType)
    {
      $this->VerificationType = $VerificationType;
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
