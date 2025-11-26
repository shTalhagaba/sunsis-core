<?php
/**
 * File for class MIAPStructMIAPLearningEvent
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPLearningEvent originally named MIAPLearningEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerrecord.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPLearningEvent extends MIAPWsdlClass
{
    /**
     * The AchievementProviderUKPRN
     * Meta informations extracted from the WSDL
     * - pattern : [0-9a-zA-Z]{8}
     * @var string
     */
    public $AchievementProviderUKPRN;
    /**
     * The AchievementProviderName
     * @var string
     */
    public $AchievementProviderName;
    /**
     * The AwardingOrganisationCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var string
     */
    public $AwardingOrganisationCode;
    /**
     * The AwardingOrganisationName
     * @var string
     */
    public $AwardingOrganisationName;
    /**
     * The FrameworkCode
     * @var string
     */
    public $FrameworkCode;
    /**
     * The FrameworkDesc
     * @var string
     */
    public $FrameworkDesc;
    /**
     * The ProgrammeCode
     * @var string
     */
    public $ProgrammeCode;
    /**
     * The ProgrammeDesc
     * @var string
     */
    public $ProgrammeDesc;
    /**
     * The QualificationTypeCode
     * @var string
     */
    public $QualificationTypeCode;
    /**
     * The QualificationTypeTitle
     * @var string
     */
    public $QualificationTypeTitle;
    /**
     * The SubjectCode
     * @var string
     */
    public $SubjectCode;
    /**
     * The SubjectTitle
     * @var string
     */
    public $SubjectTitle;
    /**
     * The QualificationTitle
     * @var string
     */
    public $QualificationTitle;
    /**
     * The LearningOutcomeGradeCode
     * @var string
     */
    public $LearningOutcomeGradeCode;
    /**
     * The LearningOutcomeGradeDesc
     * @var string
     */
    public $LearningOutcomeGradeDesc;
    /**
     * The RetakeIndicator
     * @var string
     */
    public $RetakeIndicator;
    /**
     * The SectorSubjectAreaCode
     * @var string
     */
    public $SectorSubjectAreaCode;
    /**
     * The SectorSubjectAreaTitle
     * @var string
     */
    public $SectorSubjectAreaTitle;
    /**
     * The AcademicYear
     * @var string
     */
    public $AcademicYear;
    /**
     * The DateLoaded
     * Meta informations extracted from the WSDL
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $DateLoaded;
    /**
     * The CollectionType
     * @var string
     */
    public $CollectionType;
    /**
     * The ReturnNumber
     * @var string
     */
    public $ReturnNumber;
    /**
     * The IncrementNumber
     * @var long
     */
    public $IncrementNumber;
    /**
     * The DataChallengeIndicator
     * @var string
     */
    public $DataChallengeIndicator;
    /**
     * The AchievementAwardDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $AchievementAwardDate;
    /**
     * The AssessmentDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $AssessmentDate;
    /**
     * The CreditsAchieved
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var long
     */
    public $CreditsAchieved;
    /**
     * The ParticipationStartDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $ParticipationStartDate;
    /**
     * The ParticipationEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $ParticipationEndDate;
    /**
     * The LastUpdatedDateTime
     * Meta informations extracted from the WSDL
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29) ([0-1][0-9]|[2][0-3]):[0-5][0-9]:[0-5][0-9]
     * @var string
     */
    public $LastUpdatedDateTime;
    /**
     * The BinCode
     * @var string
     */
    public $BinCode;
    /**
     * The RestrictedUseIndicator
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var string
     */
    public $RestrictedUseIndicator;
    /**
     * The RestrictionFlag
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var string
     */
    public $RestrictionFlag;
    /**
     * The Restriction
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var string
     */
    public $Restriction;
    /**
     * Constructor method for MIAPLearningEvent
     * @see parent::__construct()
     * @param string $_achievementProviderUKPRN
     * @param string $_achievementProviderName
     * @param string $_awardingOrganisationCode
     * @param string $_awardingOrganisationName
     * @param string $_frameworkCode
     * @param string $_frameworkDesc
     * @param string $_programmeCode
     * @param string $_programmeDesc
     * @param string $_qualificationTypeCode
     * @param string $_qualificationTypeTitle
     * @param string $_subjectCode
     * @param string $_subjectTitle
     * @param string $_qualificationTitle
     * @param string $_learningOutcomeGradeCode
     * @param string $_learningOutcomeGradeDesc
     * @param string $_retakeIndicator
     * @param string $_sectorSubjectAreaCode
     * @param string $_sectorSubjectAreaTitle
     * @param string $_academicYear
     * @param string $_dateLoaded
     * @param string $_collectionType
     * @param string $_returnNumber
     * @param long $_incrementNumber
     * @param string $_dataChallengeIndicator
     * @param string $_achievementAwardDate
     * @param string $_assessmentDate
     * @param long $_creditsAchieved
     * @param string $_participationStartDate
     * @param string $_participationEndDate
     * @param string $_lastUpdatedDateTime
     * @param string $_binCode
     * @param string $_restrictedUseIndicator
     * @param string $_restrictionFlag
     * @param string $_restriction
     * @return MIAPStructMIAPLearningEvent
     */
    public function __construct($_achievementProviderUKPRN = NULL,$_achievementProviderName = NULL,$_awardingOrganisationCode = NULL,$_awardingOrganisationName = NULL,$_frameworkCode = NULL,$_frameworkDesc = NULL,$_programmeCode = NULL,$_programmeDesc = NULL,$_qualificationTypeCode = NULL,$_qualificationTypeTitle = NULL,$_subjectCode = NULL,$_subjectTitle = NULL,$_qualificationTitle = NULL,$_learningOutcomeGradeCode = NULL,$_learningOutcomeGradeDesc = NULL,$_retakeIndicator = NULL,$_sectorSubjectAreaCode = NULL,$_sectorSubjectAreaTitle = NULL,$_academicYear = NULL,$_dateLoaded = NULL,$_collectionType = NULL,$_returnNumber = NULL,$_incrementNumber = NULL,$_dataChallengeIndicator = NULL,$_achievementAwardDate = NULL,$_assessmentDate = NULL,$_creditsAchieved = NULL,$_participationStartDate = NULL,$_participationEndDate = NULL,$_lastUpdatedDateTime = NULL,$_binCode = NULL,$_restrictedUseIndicator = NULL,$_restrictionFlag = NULL,$_restriction = NULL)
    {
        parent::__construct(array('AchievementProviderUKPRN'=>$_achievementProviderUKPRN,'AchievementProviderName'=>$_achievementProviderName,'AwardingOrganisationCode'=>$_awardingOrganisationCode,'AwardingOrganisationName'=>$_awardingOrganisationName,'FrameworkCode'=>$_frameworkCode,'FrameworkDesc'=>$_frameworkDesc,'ProgrammeCode'=>$_programmeCode,'ProgrammeDesc'=>$_programmeDesc,'QualificationTypeCode'=>$_qualificationTypeCode,'QualificationTypeTitle'=>$_qualificationTypeTitle,'SubjectCode'=>$_subjectCode,'SubjectTitle'=>$_subjectTitle,'QualificationTitle'=>$_qualificationTitle,'LearningOutcomeGradeCode'=>$_learningOutcomeGradeCode,'LearningOutcomeGradeDesc'=>$_learningOutcomeGradeDesc,'RetakeIndicator'=>$_retakeIndicator,'SectorSubjectAreaCode'=>$_sectorSubjectAreaCode,'SectorSubjectAreaTitle'=>$_sectorSubjectAreaTitle,'AcademicYear'=>$_academicYear,'DateLoaded'=>$_dateLoaded,'CollectionType'=>$_collectionType,'ReturnNumber'=>$_returnNumber,'IncrementNumber'=>$_incrementNumber,'DataChallengeIndicator'=>$_dataChallengeIndicator,'AchievementAwardDate'=>$_achievementAwardDate,'AssessmentDate'=>$_assessmentDate,'CreditsAchieved'=>$_creditsAchieved,'ParticipationStartDate'=>$_participationStartDate,'ParticipationEndDate'=>$_participationEndDate,'LastUpdatedDateTime'=>$_lastUpdatedDateTime,'BinCode'=>$_binCode,'RestrictedUseIndicator'=>$_restrictedUseIndicator,'RestrictionFlag'=>$_restrictionFlag,'Restriction'=>$_restriction),false);
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
     * Get ProgrammeCode value
     * @return string|null
     */
    public function getProgrammeCode()
    {
        return $this->ProgrammeCode;
    }
    /**
     * Set ProgrammeCode value
     * @param string $_programmeCode the ProgrammeCode
     * @return string
     */
    public function setProgrammeCode($_programmeCode)
    {
        return ($this->ProgrammeCode = $_programmeCode);
    }
    /**
     * Get ProgrammeDesc value
     * @return string|null
     */
    public function getProgrammeDesc()
    {
        return $this->ProgrammeDesc;
    }
    /**
     * Set ProgrammeDesc value
     * @param string $_programmeDesc the ProgrammeDesc
     * @return string
     */
    public function setProgrammeDesc($_programmeDesc)
    {
        return ($this->ProgrammeDesc = $_programmeDesc);
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
     * Get DateLoaded value
     * @return string|null
     */
    public function getDateLoaded()
    {
        return $this->DateLoaded;
    }
    /**
     * Set DateLoaded value
     * @param string $_dateLoaded the DateLoaded
     * @return string
     */
    public function setDateLoaded($_dateLoaded)
    {
        return ($this->DateLoaded = $_dateLoaded);
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
     * Get AchievementAwardDate value
     * @return string|null
     */
    public function getAchievementAwardDate()
    {
        return $this->AchievementAwardDate;
    }
    /**
     * Set AchievementAwardDate value
     * @param string $_achievementAwardDate the AchievementAwardDate
     * @return string
     */
    public function setAchievementAwardDate($_achievementAwardDate)
    {
        return ($this->AchievementAwardDate = $_achievementAwardDate);
    }
    /**
     * Get AssessmentDate value
     * @return string|null
     */
    public function getAssessmentDate()
    {
        return $this->AssessmentDate;
    }
    /**
     * Set AssessmentDate value
     * @param string $_assessmentDate the AssessmentDate
     * @return string
     */
    public function setAssessmentDate($_assessmentDate)
    {
        return ($this->AssessmentDate = $_assessmentDate);
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
     * Get ParticipationStartDate value
     * @return string|null
     */
    public function getParticipationStartDate()
    {
        return $this->ParticipationStartDate;
    }
    /**
     * Set ParticipationStartDate value
     * @param string $_participationStartDate the ParticipationStartDate
     * @return string
     */
    public function setParticipationStartDate($_participationStartDate)
    {
        return ($this->ParticipationStartDate = $_participationStartDate);
    }
    /**
     * Get ParticipationEndDate value
     * @return string|null
     */
    public function getParticipationEndDate()
    {
        return $this->ParticipationEndDate;
    }
    /**
     * Set ParticipationEndDate value
     * @param string $_participationEndDate the ParticipationEndDate
     * @return string
     */
    public function setParticipationEndDate($_participationEndDate)
    {
        return ($this->ParticipationEndDate = $_participationEndDate);
    }
    /**
     * Get LastUpdatedDateTime value
     * @return string|null
     */
    public function getLastUpdatedDateTime()
    {
        return $this->LastUpdatedDateTime;
    }
    /**
     * Set LastUpdatedDateTime value
     * @param string $_lastUpdatedDateTime the LastUpdatedDateTime
     * @return string
     */
    public function setLastUpdatedDateTime($_lastUpdatedDateTime)
    {
        return ($this->LastUpdatedDateTime = $_lastUpdatedDateTime);
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPLearningEvent
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
