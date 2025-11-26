<?php
/**
 * File for class LRSStructLearnerEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLearnerEvent originally named LearnerEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLearnerEvent extends LRSStructBusinessObject
{
    /**
     * The AcademicYear
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AcademicYear;
    /**
     * The AchievementProviderName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AchievementProviderName;
    /**
     * The AchievementProviderUKPRN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AchievementProviderUKPRN;
    /**
     * The AssessmentDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $AssessmentDate;
    /**
     * The AwardDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $AwardDate;
    /**
     * The AwardingOrganisationCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AwardingOrganisationCode;
    /**
     * The AwardingOrganisationName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AwardingOrganisationName;
    /**
     * The BinCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $BinCode;
    /**
     * The CollectionType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CollectionType;
    /**
     * The CreditsAchieved
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var long
     */
    public $CreditsAchieved;
    /**
     * The DataChallengeIndicator
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DataChallengeIndicator;
    /**
     * The DataChallengeRef
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DataChallengeRef;
    /**
     * The DateLoaded
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $DateLoaded;
    /**
     * The FrameworkCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FrameworkCode;
    /**
     * The FrameworkDesc
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FrameworkDesc;
    /**
     * The Grade
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Grade;
    /**
     * The GradingType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $GradingType;
    /**
     * The ID
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $ID;
    /**
     * The IncrementNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var long
     */
    public $IncrementNumber;
    /**
     * The LanguageForAssessment
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LanguageForAssessment;
    /**
     * The LastUpdatedDateTime
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $LastUpdatedDateTime;
    /**
     * The LearningOutcomeGradeCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LearningOutcomeGradeCode;
    /**
     * The LearningOutcomeGradeDesc
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LearningOutcomeGradeDesc;
    /**
     * The Level
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Level;
    /**
     * The MinimumGuidedLearningHours
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $MinimumGuidedLearningHours;
    /**
     * The OwningOrganisationName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OwningOrganisationName;
    /**
     * The ParticipationEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $ParticipationEndDate;
    /**
     * The ParticipationStartDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $ParticipationStartDate;
    /**
     * The ProgramCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ProgramCode;
    /**
     * The ProgramDesc
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ProgramDesc;
    /**
     * The QualificationTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationTitle;
    /**
     * The QualificationTypeCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationTypeCode;
    /**
     * The QualificationTypeTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationTypeTitle;
    /**
     * The RecordedBy
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $RecordedBy;
    /**
     * The RestrictedUseIndicator
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $RestrictedUseIndicator;
    /**
     * The Restriction
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Restriction;
    /**
     * The RestrictionFlag
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $RestrictionFlag;
    /**
     * The RetakeIndicator
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $RetakeIndicator;
    /**
     * The ReturnNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReturnNumber;
    /**
     * The SectorSubjectAreaCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SectorSubjectAreaCode;
    /**
     * The SectorSubjectAreaTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SectorSubjectAreaTitle;
    /**
     * The Selected
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $Selected;
    /**
     * The Sensitive
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $Sensitive;
    /**
     * The SourceQualificationCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SourceQualificationCode;
    /**
     * The Status
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var LRSEnumAchievementStatus
     */
    public $Status;
    /**
     * The SubjectCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SubjectCode;
    /**
     * The SubjectTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SubjectTitle;
    /**
     * The UpdatedBy
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $UpdatedBy;
    /**
     * Constructor method for LearnerEvent
     * @see parent::__construct()
     * @param string $_academicYear
     * @param string $_achievementProviderName
     * @param string $_achievementProviderUKPRN
     * @param dateTime $_assessmentDate
     * @param dateTime $_awardDate
     * @param string $_awardingOrganisationCode
     * @param string $_awardingOrganisationName
     * @param string $_binCode
     * @param string $_collectionType
     * @param long $_creditsAchieved
     * @param string $_dataChallengeIndicator
     * @param string $_dataChallengeRef
     * @param dateTime $_dateLoaded
     * @param string $_frameworkCode
     * @param string $_frameworkDesc
     * @param string $_grade
     * @param string $_gradingType
     * @param int $_iD
     * @param long $_incrementNumber
     * @param string $_languageForAssessment
     * @param dateTime $_lastUpdatedDateTime
     * @param string $_learningOutcomeGradeCode
     * @param string $_learningOutcomeGradeDesc
     * @param string $_level
     * @param int $_minimumGuidedLearningHours
     * @param string $_owningOrganisationName
     * @param dateTime $_participationEndDate
     * @param dateTime $_participationStartDate
     * @param string $_programCode
     * @param string $_programDesc
     * @param string $_qualificationTitle
     * @param string $_qualificationTypeCode
     * @param string $_qualificationTypeTitle
     * @param string $_recordedBy
     * @param string $_restrictedUseIndicator
     * @param string $_restriction
     * @param string $_restrictionFlag
     * @param string $_retakeIndicator
     * @param string $_returnNumber
     * @param string $_sectorSubjectAreaCode
     * @param string $_sectorSubjectAreaTitle
     * @param boolean $_selected
     * @param boolean $_sensitive
     * @param string $_sourceQualificationCode
     * @param LRSEnumAchievementStatus $_status
     * @param string $_subjectCode
     * @param string $_subjectTitle
     * @param string $_updatedBy
     * @return LRSStructLearnerEvent
     */
    public function __construct($_academicYear = NULL,$_achievementProviderName = NULL,$_achievementProviderUKPRN = NULL,$_assessmentDate = NULL,$_awardDate = NULL,$_awardingOrganisationCode = NULL,$_awardingOrganisationName = NULL,$_binCode = NULL,$_collectionType = NULL,$_creditsAchieved = NULL,$_dataChallengeIndicator = NULL,$_dataChallengeRef = NULL,$_dateLoaded = NULL,$_frameworkCode = NULL,$_frameworkDesc = NULL,$_grade = NULL,$_gradingType = NULL,$_iD = NULL,$_incrementNumber = NULL,$_languageForAssessment = NULL,$_lastUpdatedDateTime = NULL,$_learningOutcomeGradeCode = NULL,$_learningOutcomeGradeDesc = NULL,$_level = NULL,$_minimumGuidedLearningHours = NULL,$_owningOrganisationName = NULL,$_participationEndDate = NULL,$_participationStartDate = NULL,$_programCode = NULL,$_programDesc = NULL,$_qualificationTitle = NULL,$_qualificationTypeCode = NULL,$_qualificationTypeTitle = NULL,$_recordedBy = NULL,$_restrictedUseIndicator = NULL,$_restriction = NULL,$_restrictionFlag = NULL,$_retakeIndicator = NULL,$_returnNumber = NULL,$_sectorSubjectAreaCode = NULL,$_sectorSubjectAreaTitle = NULL,$_selected = NULL,$_sensitive = NULL,$_sourceQualificationCode = NULL,$_status = NULL,$_subjectCode = NULL,$_subjectTitle = NULL,$_updatedBy = NULL)
    {
        LRSWsdlClass::__construct(array('AcademicYear'=>$_academicYear,'AchievementProviderName'=>$_achievementProviderName,'AchievementProviderUKPRN'=>$_achievementProviderUKPRN,'AssessmentDate'=>$_assessmentDate,'AwardDate'=>$_awardDate,'AwardingOrganisationCode'=>$_awardingOrganisationCode,'AwardingOrganisationName'=>$_awardingOrganisationName,'BinCode'=>$_binCode,'CollectionType'=>$_collectionType,'CreditsAchieved'=>$_creditsAchieved,'DataChallengeIndicator'=>$_dataChallengeIndicator,'DataChallengeRef'=>$_dataChallengeRef,'DateLoaded'=>$_dateLoaded,'FrameworkCode'=>$_frameworkCode,'FrameworkDesc'=>$_frameworkDesc,'Grade'=>$_grade,'GradingType'=>$_gradingType,'ID'=>$_iD,'IncrementNumber'=>$_incrementNumber,'LanguageForAssessment'=>$_languageForAssessment,'LastUpdatedDateTime'=>$_lastUpdatedDateTime,'LearningOutcomeGradeCode'=>$_learningOutcomeGradeCode,'LearningOutcomeGradeDesc'=>$_learningOutcomeGradeDesc,'Level'=>$_level,'MinimumGuidedLearningHours'=>$_minimumGuidedLearningHours,'OwningOrganisationName'=>$_owningOrganisationName,'ParticipationEndDate'=>$_participationEndDate,'ParticipationStartDate'=>$_participationStartDate,'ProgramCode'=>$_programCode,'ProgramDesc'=>$_programDesc,'QualificationTitle'=>$_qualificationTitle,'QualificationTypeCode'=>$_qualificationTypeCode,'QualificationTypeTitle'=>$_qualificationTypeTitle,'RecordedBy'=>$_recordedBy,'RestrictedUseIndicator'=>$_restrictedUseIndicator,'Restriction'=>$_restriction,'RestrictionFlag'=>$_restrictionFlag,'RetakeIndicator'=>$_retakeIndicator,'ReturnNumber'=>$_returnNumber,'SectorSubjectAreaCode'=>$_sectorSubjectAreaCode,'SectorSubjectAreaTitle'=>$_sectorSubjectAreaTitle,'Selected'=>$_selected,'Sensitive'=>$_sensitive,'SourceQualificationCode'=>$_sourceQualificationCode,'Status'=>$_status,'SubjectCode'=>$_subjectCode,'SubjectTitle'=>$_subjectTitle,'UpdatedBy'=>$_updatedBy),false);
    }
    /**
     * Get AcademicYear value
     * @return string|null
     */
    public function getAcademicYear()
    {
        return $this->AcademicYear;
    }
    /**
     * Set AcademicYear value
     * @param string $_academicYear the AcademicYear
     * @return string
     */
    public function setAcademicYear($_academicYear)
    {
        return ($this->AcademicYear = $_academicYear);
    }
    /**
     * Get AchievementProviderName value
     * @return string|null
     */
    public function getAchievementProviderName()
    {
        return $this->AchievementProviderName;
    }
    /**
     * Set AchievementProviderName value
     * @param string $_achievementProviderName the AchievementProviderName
     * @return string
     */
    public function setAchievementProviderName($_achievementProviderName)
    {
        return ($this->AchievementProviderName = $_achievementProviderName);
    }
    /**
     * Get AchievementProviderUKPRN value
     * @return string|null
     */
    public function getAchievementProviderUKPRN()
    {
        return $this->AchievementProviderUKPRN;
    }
    /**
     * Set AchievementProviderUKPRN value
     * @param string $_achievementProviderUKPRN the AchievementProviderUKPRN
     * @return string
     */
    public function setAchievementProviderUKPRN($_achievementProviderUKPRN)
    {
        return ($this->AchievementProviderUKPRN = $_achievementProviderUKPRN);
    }
    /**
     * Get AssessmentDate value
     * @return dateTime|null
     */
    public function getAssessmentDate()
    {
        return $this->AssessmentDate;
    }
    /**
     * Set AssessmentDate value
     * @param dateTime $_assessmentDate the AssessmentDate
     * @return dateTime
     */
    public function setAssessmentDate($_assessmentDate)
    {
        return ($this->AssessmentDate = $_assessmentDate);
    }
    /**
     * Get AwardDate value
     * @return dateTime|null
     */
    public function getAwardDate()
    {
        return $this->AwardDate;
    }
    /**
     * Set AwardDate value
     * @param dateTime $_awardDate the AwardDate
     * @return dateTime
     */
    public function setAwardDate($_awardDate)
    {
        return ($this->AwardDate = $_awardDate);
    }
    /**
     * Get AwardingOrganisationCode value
     * @return string|null
     */
    public function getAwardingOrganisationCode()
    {
        return $this->AwardingOrganisationCode;
    }
    /**
     * Set AwardingOrganisationCode value
     * @param string $_awardingOrganisationCode the AwardingOrganisationCode
     * @return string
     */
    public function setAwardingOrganisationCode($_awardingOrganisationCode)
    {
        return ($this->AwardingOrganisationCode = $_awardingOrganisationCode);
    }
    /**
     * Get AwardingOrganisationName value
     * @return string|null
     */
    public function getAwardingOrganisationName()
    {
        return $this->AwardingOrganisationName;
    }
    /**
     * Set AwardingOrganisationName value
     * @param string $_awardingOrganisationName the AwardingOrganisationName
     * @return string
     */
    public function setAwardingOrganisationName($_awardingOrganisationName)
    {
        return ($this->AwardingOrganisationName = $_awardingOrganisationName);
    }
    /**
     * Get BinCode value
     * @return string|null
     */
    public function getBinCode()
    {
        return $this->BinCode;
    }
    /**
     * Set BinCode value
     * @param string $_binCode the BinCode
     * @return string
     */
    public function setBinCode($_binCode)
    {
        return ($this->BinCode = $_binCode);
    }
    /**
     * Get CollectionType value
     * @return string|null
     */
    public function getCollectionType()
    {
        return $this->CollectionType;
    }
    /**
     * Set CollectionType value
     * @param string $_collectionType the CollectionType
     * @return string
     */
    public function setCollectionType($_collectionType)
    {
        return ($this->CollectionType = $_collectionType);
    }
    /**
     * Get CreditsAchieved value
     * @return long|null
     */
    public function getCreditsAchieved()
    {
        return $this->CreditsAchieved;
    }
    /**
     * Set CreditsAchieved value
     * @param long $_creditsAchieved the CreditsAchieved
     * @return long
     */
    public function setCreditsAchieved($_creditsAchieved)
    {
        return ($this->CreditsAchieved = $_creditsAchieved);
    }
    /**
     * Get DataChallengeIndicator value
     * @return string|null
     */
    public function getDataChallengeIndicator()
    {
        return $this->DataChallengeIndicator;
    }
    /**
     * Set DataChallengeIndicator value
     * @param string $_dataChallengeIndicator the DataChallengeIndicator
     * @return string
     */
    public function setDataChallengeIndicator($_dataChallengeIndicator)
    {
        return ($this->DataChallengeIndicator = $_dataChallengeIndicator);
    }
    /**
     * Get DataChallengeRef value
     * @return string|null
     */
    public function getDataChallengeRef()
    {
        return $this->DataChallengeRef;
    }
    /**
     * Set DataChallengeRef value
     * @param string $_dataChallengeRef the DataChallengeRef
     * @return string
     */
    public function setDataChallengeRef($_dataChallengeRef)
    {
        return ($this->DataChallengeRef = $_dataChallengeRef);
    }
    /**
     * Get DateLoaded value
     * @return dateTime|null
     */
    public function getDateLoaded()
    {
        return $this->DateLoaded;
    }
    /**
     * Set DateLoaded value
     * @param dateTime $_dateLoaded the DateLoaded
     * @return dateTime
     */
    public function setDateLoaded($_dateLoaded)
    {
        return ($this->DateLoaded = $_dateLoaded);
    }
    /**
     * Get FrameworkCode value
     * @return string|null
     */
    public function getFrameworkCode()
    {
        return $this->FrameworkCode;
    }
    /**
     * Set FrameworkCode value
     * @param string $_frameworkCode the FrameworkCode
     * @return string
     */
    public function setFrameworkCode($_frameworkCode)
    {
        return ($this->FrameworkCode = $_frameworkCode);
    }
    /**
     * Get FrameworkDesc value
     * @return string|null
     */
    public function getFrameworkDesc()
    {
        return $this->FrameworkDesc;
    }
    /**
     * Set FrameworkDesc value
     * @param string $_frameworkDesc the FrameworkDesc
     * @return string
     */
    public function setFrameworkDesc($_frameworkDesc)
    {
        return ($this->FrameworkDesc = $_frameworkDesc);
    }
    /**
     * Get Grade value
     * @return string|null
     */
    public function getGrade()
    {
        return $this->Grade;
    }
    /**
     * Set Grade value
     * @param string $_grade the Grade
     * @return string
     */
    public function setGrade($_grade)
    {
        return ($this->Grade = $_grade);
    }
    /**
     * Get GradingType value
     * @return string|null
     */
    public function getGradingType()
    {
        return $this->GradingType;
    }
    /**
     * Set GradingType value
     * @param string $_gradingType the GradingType
     * @return string
     */
    public function setGradingType($_gradingType)
    {
        return ($this->GradingType = $_gradingType);
    }
    /**
     * Get ID value
     * @return int|null
     */
    public function getID()
    {
        return $this->ID;
    }
    /**
     * Set ID value
     * @param int $_iD the ID
     * @return int
     */
    public function setID($_iD)
    {
        return ($this->ID = $_iD);
    }
    /**
     * Get IncrementNumber value
     * @return long|null
     */
    public function getIncrementNumber()
    {
        return $this->IncrementNumber;
    }
    /**
     * Set IncrementNumber value
     * @param long $_incrementNumber the IncrementNumber
     * @return long
     */
    public function setIncrementNumber($_incrementNumber)
    {
        return ($this->IncrementNumber = $_incrementNumber);
    }
    /**
     * Get LanguageForAssessment value
     * @return string|null
     */
    public function getLanguageForAssessment()
    {
        return $this->LanguageForAssessment;
    }
    /**
     * Set LanguageForAssessment value
     * @param string $_languageForAssessment the LanguageForAssessment
     * @return string
     */
    public function setLanguageForAssessment($_languageForAssessment)
    {
        return ($this->LanguageForAssessment = $_languageForAssessment);
    }
    /**
     * Get LastUpdatedDateTime value
     * @return dateTime|null
     */
    public function getLastUpdatedDateTime()
    {
        return $this->LastUpdatedDateTime;
    }
    /**
     * Set LastUpdatedDateTime value
     * @param dateTime $_lastUpdatedDateTime the LastUpdatedDateTime
     * @return dateTime
     */
    public function setLastUpdatedDateTime($_lastUpdatedDateTime)
    {
        return ($this->LastUpdatedDateTime = $_lastUpdatedDateTime);
    }
    /**
     * Get LearningOutcomeGradeCode value
     * @return string|null
     */
    public function getLearningOutcomeGradeCode()
    {
        return $this->LearningOutcomeGradeCode;
    }
    /**
     * Set LearningOutcomeGradeCode value
     * @param string $_learningOutcomeGradeCode the LearningOutcomeGradeCode
     * @return string
     */
    public function setLearningOutcomeGradeCode($_learningOutcomeGradeCode)
    {
        return ($this->LearningOutcomeGradeCode = $_learningOutcomeGradeCode);
    }
    /**
     * Get LearningOutcomeGradeDesc value
     * @return string|null
     */
    public function getLearningOutcomeGradeDesc()
    {
        return $this->LearningOutcomeGradeDesc;
    }
    /**
     * Set LearningOutcomeGradeDesc value
     * @param string $_learningOutcomeGradeDesc the LearningOutcomeGradeDesc
     * @return string
     */
    public function setLearningOutcomeGradeDesc($_learningOutcomeGradeDesc)
    {
        return ($this->LearningOutcomeGradeDesc = $_learningOutcomeGradeDesc);
    }
    /**
     * Get Level value
     * @return string|null
     */
    public function getLevel()
    {
        return $this->Level;
    }
    /**
     * Set Level value
     * @param string $_level the Level
     * @return string
     */
    public function setLevel($_level)
    {
        return ($this->Level = $_level);
    }
    /**
     * Get MinimumGuidedLearningHours value
     * @return int|null
     */
    public function getMinimumGuidedLearningHours()
    {
        return $this->MinimumGuidedLearningHours;
    }
    /**
     * Set MinimumGuidedLearningHours value
     * @param int $_minimumGuidedLearningHours the MinimumGuidedLearningHours
     * @return int
     */
    public function setMinimumGuidedLearningHours($_minimumGuidedLearningHours)
    {
        return ($this->MinimumGuidedLearningHours = $_minimumGuidedLearningHours);
    }
    /**
     * Get OwningOrganisationName value
     * @return string|null
     */
    public function getOwningOrganisationName()
    {
        return $this->OwningOrganisationName;
    }
    /**
     * Set OwningOrganisationName value
     * @param string $_owningOrganisationName the OwningOrganisationName
     * @return string
     */
    public function setOwningOrganisationName($_owningOrganisationName)
    {
        return ($this->OwningOrganisationName = $_owningOrganisationName);
    }
    /**
     * Get ParticipationEndDate value
     * @return dateTime|null
     */
    public function getParticipationEndDate()
    {
        return $this->ParticipationEndDate;
    }
    /**
     * Set ParticipationEndDate value
     * @param dateTime $_participationEndDate the ParticipationEndDate
     * @return dateTime
     */
    public function setParticipationEndDate($_participationEndDate)
    {
        return ($this->ParticipationEndDate = $_participationEndDate);
    }
    /**
     * Get ParticipationStartDate value
     * @return dateTime|null
     */
    public function getParticipationStartDate()
    {
        return $this->ParticipationStartDate;
    }
    /**
     * Set ParticipationStartDate value
     * @param dateTime $_participationStartDate the ParticipationStartDate
     * @return dateTime
     */
    public function setParticipationStartDate($_participationStartDate)
    {
        return ($this->ParticipationStartDate = $_participationStartDate);
    }
    /**
     * Get ProgramCode value
     * @return string|null
     */
    public function getProgramCode()
    {
        return $this->ProgramCode;
    }
    /**
     * Set ProgramCode value
     * @param string $_programCode the ProgramCode
     * @return string
     */
    public function setProgramCode($_programCode)
    {
        return ($this->ProgramCode = $_programCode);
    }
    /**
     * Get ProgramDesc value
     * @return string|null
     */
    public function getProgramDesc()
    {
        return $this->ProgramDesc;
    }
    /**
     * Set ProgramDesc value
     * @param string $_programDesc the ProgramDesc
     * @return string
     */
    public function setProgramDesc($_programDesc)
    {
        return ($this->ProgramDesc = $_programDesc);
    }
    /**
     * Get QualificationTitle value
     * @return string|null
     */
    public function getQualificationTitle()
    {
        return $this->QualificationTitle;
    }
    /**
     * Set QualificationTitle value
     * @param string $_qualificationTitle the QualificationTitle
     * @return string
     */
    public function setQualificationTitle($_qualificationTitle)
    {
        return ($this->QualificationTitle = $_qualificationTitle);
    }
    /**
     * Get QualificationTypeCode value
     * @return string|null
     */
    public function getQualificationTypeCode()
    {
        return $this->QualificationTypeCode;
    }
    /**
     * Set QualificationTypeCode value
     * @param string $_qualificationTypeCode the QualificationTypeCode
     * @return string
     */
    public function setQualificationTypeCode($_qualificationTypeCode)
    {
        return ($this->QualificationTypeCode = $_qualificationTypeCode);
    }
    /**
     * Get QualificationTypeTitle value
     * @return string|null
     */
    public function getQualificationTypeTitle()
    {
        return $this->QualificationTypeTitle;
    }
    /**
     * Set QualificationTypeTitle value
     * @param string $_qualificationTypeTitle the QualificationTypeTitle
     * @return string
     */
    public function setQualificationTypeTitle($_qualificationTypeTitle)
    {
        return ($this->QualificationTypeTitle = $_qualificationTypeTitle);
    }
    /**
     * Get RecordedBy value
     * @return string|null
     */
    public function getRecordedBy()
    {
        return $this->RecordedBy;
    }
    /**
     * Set RecordedBy value
     * @param string $_recordedBy the RecordedBy
     * @return string
     */
    public function setRecordedBy($_recordedBy)
    {
        return ($this->RecordedBy = $_recordedBy);
    }
    /**
     * Get RestrictedUseIndicator value
     * @return string|null
     */
    public function getRestrictedUseIndicator()
    {
        return $this->RestrictedUseIndicator;
    }
    /**
     * Set RestrictedUseIndicator value
     * @param string $_restrictedUseIndicator the RestrictedUseIndicator
     * @return string
     */
    public function setRestrictedUseIndicator($_restrictedUseIndicator)
    {
        return ($this->RestrictedUseIndicator = $_restrictedUseIndicator);
    }
    /**
     * Get Restriction value
     * @return string|null
     */
    public function getRestriction()
    {
        return $this->Restriction;
    }
    /**
     * Set Restriction value
     * @param string $_restriction the Restriction
     * @return string
     */
    public function setRestriction($_restriction)
    {
        return ($this->Restriction = $_restriction);
    }
    /**
     * Get RestrictionFlag value
     * @return string|null
     */
    public function getRestrictionFlag()
    {
        return $this->RestrictionFlag;
    }
    /**
     * Set RestrictionFlag value
     * @param string $_restrictionFlag the RestrictionFlag
     * @return string
     */
    public function setRestrictionFlag($_restrictionFlag)
    {
        return ($this->RestrictionFlag = $_restrictionFlag);
    }
    /**
     * Get RetakeIndicator value
     * @return string|null
     */
    public function getRetakeIndicator()
    {
        return $this->RetakeIndicator;
    }
    /**
     * Set RetakeIndicator value
     * @param string $_retakeIndicator the RetakeIndicator
     * @return string
     */
    public function setRetakeIndicator($_retakeIndicator)
    {
        return ($this->RetakeIndicator = $_retakeIndicator);
    }
    /**
     * Get ReturnNumber value
     * @return string|null
     */
    public function getReturnNumber()
    {
        return $this->ReturnNumber;
    }
    /**
     * Set ReturnNumber value
     * @param string $_returnNumber the ReturnNumber
     * @return string
     */
    public function setReturnNumber($_returnNumber)
    {
        return ($this->ReturnNumber = $_returnNumber);
    }
    /**
     * Get SectorSubjectAreaCode value
     * @return string|null
     */
    public function getSectorSubjectAreaCode()
    {
        return $this->SectorSubjectAreaCode;
    }
    /**
     * Set SectorSubjectAreaCode value
     * @param string $_sectorSubjectAreaCode the SectorSubjectAreaCode
     * @return string
     */
    public function setSectorSubjectAreaCode($_sectorSubjectAreaCode)
    {
        return ($this->SectorSubjectAreaCode = $_sectorSubjectAreaCode);
    }
    /**
     * Get SectorSubjectAreaTitle value
     * @return string|null
     */
    public function getSectorSubjectAreaTitle()
    {
        return $this->SectorSubjectAreaTitle;
    }
    /**
     * Set SectorSubjectAreaTitle value
     * @param string $_sectorSubjectAreaTitle the SectorSubjectAreaTitle
     * @return string
     */
    public function setSectorSubjectAreaTitle($_sectorSubjectAreaTitle)
    {
        return ($this->SectorSubjectAreaTitle = $_sectorSubjectAreaTitle);
    }
    /**
     * Get Selected value
     * @return boolean|null
     */
    public function getSelected()
    {
        return $this->Selected;
    }
    /**
     * Set Selected value
     * @param boolean $_selected the Selected
     * @return boolean
     */
    public function setSelected($_selected)
    {
        return ($this->Selected = $_selected);
    }
    /**
     * Get Sensitive value
     * @return boolean|null
     */
    public function getSensitive()
    {
        return $this->Sensitive;
    }
    /**
     * Set Sensitive value
     * @param boolean $_sensitive the Sensitive
     * @return boolean
     */
    public function setSensitive($_sensitive)
    {
        return ($this->Sensitive = $_sensitive);
    }
    /**
     * Get SourceQualificationCode value
     * @return string|null
     */
    public function getSourceQualificationCode()
    {
        return $this->SourceQualificationCode;
    }
    /**
     * Set SourceQualificationCode value
     * @param string $_sourceQualificationCode the SourceQualificationCode
     * @return string
     */
    public function setSourceQualificationCode($_sourceQualificationCode)
    {
        return ($this->SourceQualificationCode = $_sourceQualificationCode);
    }
    /**
     * Get Status value
     * @return LRSEnumAchievementStatus|null
     */
    public function getStatus()
    {
        return $this->Status;
    }
    /**
     * Set Status value
     * @uses LRSEnumAchievementStatus::valueIsValid()
     * @param LRSEnumAchievementStatus $_status the Status
     * @return LRSEnumAchievementStatus
     */
    public function setStatus($_status)
    {
        if(!LRSEnumAchievementStatus::valueIsValid($_status))
        {
            return false;
        }
        return ($this->Status = $_status);
    }
    /**
     * Get SubjectCode value
     * @return string|null
     */
    public function getSubjectCode()
    {
        return $this->SubjectCode;
    }
    /**
     * Set SubjectCode value
     * @param string $_subjectCode the SubjectCode
     * @return string
     */
    public function setSubjectCode($_subjectCode)
    {
        return ($this->SubjectCode = $_subjectCode);
    }
    /**
     * Get SubjectTitle value
     * @return string|null
     */
    public function getSubjectTitle()
    {
        return $this->SubjectTitle;
    }
    /**
     * Set SubjectTitle value
     * @param string $_subjectTitle the SubjectTitle
     * @return string
     */
    public function setSubjectTitle($_subjectTitle)
    {
        return ($this->SubjectTitle = $_subjectTitle);
    }
    /**
     * Get UpdatedBy value
     * @return string|null
     */
    public function getUpdatedBy()
    {
        return $this->UpdatedBy;
    }
    /**
     * Set UpdatedBy value
     * @param string $_updatedBy the UpdatedBy
     * @return string
     */
    public function setUpdatedBy($_updatedBy)
    {
        return ($this->UpdatedBy = $_updatedBy);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLearnerEvent
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
