<?php

class LearningEvent
{

    /**
     * @var string $AchievementAwardDate
     */
    protected $AchievementAwardDate = null;

    /**
     * @var string $AchievementProviderName
     */
    protected $AchievementProviderName = null;

    /**
     * @var string $AchievementProviderUkprn
     */
    protected $AchievementProviderUkprn = null;

    /**
     * @var string $AwardingOrganisationName
     */
    protected $AwardingOrganisationName = null;

    /**
     * @var string $AwardingOrganisationUkprn
     */
    protected $AwardingOrganisationUkprn = null;

    /**
     * @var string $CollectionType
     */
    protected $CollectionType = null;

    /**
     * @var int $Credits
     */
    protected $Credits = null;

    /**
     * @var string $DateLoaded
     */
    protected $DateLoaded = null;

    /**
     * @var string $Grade
     */
    protected $Grade = null;

    /**
     * @var int $ID
     */
    protected $ID = null;

    /**
     * @var string $LanguageForAssessment
     */
    protected $LanguageForAssessment = null;

    /**
     * @var string $Level
     */
    protected $Level = null;

    /**
     * @var string $ParticipationEndDate
     */
    protected $ParticipationEndDate = null;

    /**
     * @var string $ParticipationStartDate
     */
    protected $ParticipationStartDate = null;

    /**
     * @var string $QualificationType
     */
    protected $QualificationType = null;

    /**
     * @var string $Restriction
     */
    protected $Restriction = null;

    /**
     * @var string $ReturnNumber
     */
    protected $ReturnNumber = null;

    /**
     * @var string $Source
     */
    protected $Source = null;

    /**
     * @var string $Status
     */
    protected $Status = null;

    /**
     * @var string $Subject
     */
    protected $Subject = null;

    /**
     * @var string $SubjectCode
     */
    protected $SubjectCode = null;

    /**
     * @var string $UnderDataChallenge
     */
    protected $UnderDataChallenge = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getAchievementAwardDate()
    {
      return $this->AchievementAwardDate;
    }

    /**
     * @param string $AchievementAwardDate
     * @return LearningEvent
     */
    public function setAchievementAwardDate($AchievementAwardDate)
    {
      $this->AchievementAwardDate = $AchievementAwardDate;
      return $this;
    }

    /**
     * @return string
     */
    public function getAchievementProviderName()
    {
      return $this->AchievementProviderName;
    }

    /**
     * @param string $AchievementProviderName
     * @return LearningEvent
     */
    public function setAchievementProviderName($AchievementProviderName)
    {
      $this->AchievementProviderName = $AchievementProviderName;
      return $this;
    }

    /**
     * @return string
     */
    public function getAchievementProviderUkprn()
    {
      return $this->AchievementProviderUkprn;
    }

    /**
     * @param string $AchievementProviderUkprn
     * @return LearningEvent
     */
    public function setAchievementProviderUkprn($AchievementProviderUkprn)
    {
      $this->AchievementProviderUkprn = $AchievementProviderUkprn;
      return $this;
    }

    /**
     * @return string
     */
    public function getAwardingOrganisationName()
    {
      return $this->AwardingOrganisationName;
    }

    /**
     * @param string $AwardingOrganisationName
     * @return LearningEvent
     */
    public function setAwardingOrganisationName($AwardingOrganisationName)
    {
      $this->AwardingOrganisationName = $AwardingOrganisationName;
      return $this;
    }

    /**
     * @return string
     */
    public function getAwardingOrganisationUkprn()
    {
      return $this->AwardingOrganisationUkprn;
    }

    /**
     * @param string $AwardingOrganisationUkprn
     * @return LearningEvent
     */
    public function setAwardingOrganisationUkprn($AwardingOrganisationUkprn)
    {
      $this->AwardingOrganisationUkprn = $AwardingOrganisationUkprn;
      return $this;
    }

    /**
     * @return string
     */
    public function getCollectionType()
    {
      return $this->CollectionType;
    }

    /**
     * @param string $CollectionType
     * @return LearningEvent
     */
    public function setCollectionType($CollectionType)
    {
      $this->CollectionType = $CollectionType;
      return $this;
    }

    /**
     * @return int
     */
    public function getCredits()
    {
      return $this->Credits;
    }

    /**
     * @param int $Credits
     * @return LearningEvent
     */
    public function setCredits($Credits)
    {
      $this->Credits = $Credits;
      return $this;
    }

    /**
     * @return string
     */
    public function getDateLoaded()
    {
      return $this->DateLoaded;
    }

    /**
     * @param string $DateLoaded
     * @return LearningEvent
     */
    public function setDateLoaded($DateLoaded)
    {
      $this->DateLoaded = $DateLoaded;
      return $this;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
      return $this->Grade;
    }

    /**
     * @param string $Grade
     * @return LearningEvent
     */
    public function setGrade($Grade)
    {
      $this->Grade = $Grade;
      return $this;
    }

    /**
     * @return int
     */
    public function getID()
    {
      return $this->ID;
    }

    /**
     * @param int $ID
     * @return LearningEvent
     */
    public function setID($ID)
    {
      $this->ID = $ID;
      return $this;
    }

    /**
     * @return string
     */
    public function getLanguageForAssessment()
    {
      return $this->LanguageForAssessment;
    }

    /**
     * @param string $LanguageForAssessment
     * @return LearningEvent
     */
    public function setLanguageForAssessment($LanguageForAssessment)
    {
      $this->LanguageForAssessment = $LanguageForAssessment;
      return $this;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
      return $this->Level;
    }

    /**
     * @param string $Level
     * @return LearningEvent
     */
    public function setLevel($Level)
    {
      $this->Level = $Level;
      return $this;
    }

    /**
     * @return string
     */
    public function getParticipationEndDate()
    {
      return $this->ParticipationEndDate;
    }

    /**
     * @param string $ParticipationEndDate
     * @return LearningEvent
     */
    public function setParticipationEndDate($ParticipationEndDate)
    {
      $this->ParticipationEndDate = $ParticipationEndDate;
      return $this;
    }

    /**
     * @return string
     */
    public function getParticipationStartDate()
    {
      return $this->ParticipationStartDate;
    }

    /**
     * @param string $ParticipationStartDate
     * @return LearningEvent
     */
    public function setParticipationStartDate($ParticipationStartDate)
    {
      $this->ParticipationStartDate = $ParticipationStartDate;
      return $this;
    }

    /**
     * @return string
     */
    public function getQualificationType()
    {
      return $this->QualificationType;
    }

    /**
     * @param string $QualificationType
     * @return LearningEvent
     */
    public function setQualificationType($QualificationType)
    {
      $this->QualificationType = $QualificationType;
      return $this;
    }

    /**
     * @return string
     */
    public function getRestriction()
    {
      return $this->Restriction;
    }

    /**
     * @param string $Restriction
     * @return LearningEvent
     */
    public function setRestriction($Restriction)
    {
      $this->Restriction = $Restriction;
      return $this;
    }

    /**
     * @return string
     */
    public function getReturnNumber()
    {
      return $this->ReturnNumber;
    }

    /**
     * @param string $ReturnNumber
     * @return LearningEvent
     */
    public function setReturnNumber($ReturnNumber)
    {
      $this->ReturnNumber = $ReturnNumber;
      return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
      return $this->Source;
    }

    /**
     * @param string $Source
     * @return LearningEvent
     */
    public function setSource($Source)
    {
      $this->Source = $Source;
      return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
      return $this->Status;
    }

    /**
     * @param string $Status
     * @return LearningEvent
     */
    public function setStatus($Status)
    {
      $this->Status = $Status;
      return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
      return $this->Subject;
    }

    /**
     * @param string $Subject
     * @return LearningEvent
     */
    public function setSubject($Subject)
    {
      $this->Subject = $Subject;
      return $this;
    }

    /**
     * @return string
     */
    public function getSubjectCode()
    {
      return $this->SubjectCode;
    }

    /**
     * @param string $SubjectCode
     * @return LearningEvent
     */
    public function setSubjectCode($SubjectCode)
    {
      $this->SubjectCode = $SubjectCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getUnderDataChallenge()
    {
      return $this->UnderDataChallenge;
    }

    /**
     * @param string $UnderDataChallenge
     * @return LearningEvent
     */
    public function setUnderDataChallenge($UnderDataChallenge)
    {
      $this->UnderDataChallenge = $UnderDataChallenge;
      return $this;
    }

}
