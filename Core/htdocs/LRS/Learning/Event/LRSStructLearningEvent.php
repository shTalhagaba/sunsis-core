<?php
/**
 * File for class LRSStructLearningEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLearningEvent originally named LearningEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLearningEvent extends LRSStructBusinessObject
{
    /**
     * The AchievementAwardDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AchievementAwardDate;
    /**
     * The AchievementProviderName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AchievementProviderName;
    /**
     * The AchievementProviderUkprn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AchievementProviderUkprn;
    /**
     * The AwardingOrganisationName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AwardingOrganisationName;
    /**
     * The AwardingOrganisationUkprn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AwardingOrganisationUkprn;
    /**
     * The CollectionType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CollectionType;
    /**
     * The Credits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var long
     */
    public $Credits;
    /**
     * The DateLoaded
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DateLoaded;
    /**
     * The Grade
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Grade;
    /**
     * The ID
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $ID;
    /**
     * The LanguageForAssessment
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LanguageForAssessment;
    /**
     * The Level
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Level;
    /**
     * The ParticipationEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ParticipationEndDate;
    /**
     * The ParticipationStartDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ParticipationStartDate;
    /**
     * The QualificationType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationType;
    /**
     * The Restriction
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Restriction;
    /**
     * The ReturnNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReturnNumber;
    /**
     * The Source
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Source;
    /**
     * The Status
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Status;
    /**
     * The Subject
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Subject;
    /**
     * The SubjectCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SubjectCode;
    /**
     * The UnderDataChallenge
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $UnderDataChallenge;
    /**
     * Constructor method for LearningEvent
     * @see parent::__construct()
     * @param string $_achievementAwardDate
     * @param string $_achievementProviderName
     * @param string $_achievementProviderUkprn
     * @param string $_awardingOrganisationName
     * @param string $_awardingOrganisationUkprn
     * @param string $_collectionType
     * @param long $_credits
     * @param string $_dateLoaded
     * @param string $_grade
     * @param int $_iD
     * @param string $_languageForAssessment
     * @param string $_level
     * @param string $_participationEndDate
     * @param string $_participationStartDate
     * @param string $_qualificationType
     * @param string $_restriction
     * @param string $_returnNumber
     * @param string $_source
     * @param string $_status
     * @param string $_subject
     * @param string $_subjectCode
     * @param string $_underDataChallenge
     * @return LRSStructLearningEvent
     */
    public function __construct($_achievementAwardDate = NULL,$_achievementProviderName = NULL,$_achievementProviderUkprn = NULL,$_awardingOrganisationName = NULL,$_awardingOrganisationUkprn = NULL,$_collectionType = NULL,$_credits = NULL,$_dateLoaded = NULL,$_grade = NULL,$_iD = NULL,$_languageForAssessment = NULL,$_level = NULL,$_participationEndDate = NULL,$_participationStartDate = NULL,$_qualificationType = NULL,$_restriction = NULL,$_returnNumber = NULL,$_source = NULL,$_status = NULL,$_subject = NULL,$_subjectCode = NULL,$_underDataChallenge = NULL)
    {
        LRSWsdlClass::__construct(array('AchievementAwardDate'=>$_achievementAwardDate,'AchievementProviderName'=>$_achievementProviderName,'AchievementProviderUkprn'=>$_achievementProviderUkprn,'AwardingOrganisationName'=>$_awardingOrganisationName,'AwardingOrganisationUkprn'=>$_awardingOrganisationUkprn,'CollectionType'=>$_collectionType,'Credits'=>$_credits,'DateLoaded'=>$_dateLoaded,'Grade'=>$_grade,'ID'=>$_iD,'LanguageForAssessment'=>$_languageForAssessment,'Level'=>$_level,'ParticipationEndDate'=>$_participationEndDate,'ParticipationStartDate'=>$_participationStartDate,'QualificationType'=>$_qualificationType,'Restriction'=>$_restriction,'ReturnNumber'=>$_returnNumber,'Source'=>$_source,'Status'=>$_status,'Subject'=>$_subject,'SubjectCode'=>$_subjectCode,'UnderDataChallenge'=>$_underDataChallenge),false);
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
     * Get AchievementProviderUkprn value
     * @return string|null
     */
    public function getAchievementProviderUkprn()
    {
        return $this->AchievementProviderUkprn;
    }
    /**
     * Set AchievementProviderUkprn value
     * @param string $_achievementProviderUkprn the AchievementProviderUkprn
     * @return string
     */
    public function setAchievementProviderUkprn($_achievementProviderUkprn)
    {
        return ($this->AchievementProviderUkprn = $_achievementProviderUkprn);
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
     * Get AwardingOrganisationUkprn value
     * @return string|null
     */
    public function getAwardingOrganisationUkprn()
    {
        return $this->AwardingOrganisationUkprn;
    }
    /**
     * Set AwardingOrganisationUkprn value
     * @param string $_awardingOrganisationUkprn the AwardingOrganisationUkprn
     * @return string
     */
    public function setAwardingOrganisationUkprn($_awardingOrganisationUkprn)
    {
        return ($this->AwardingOrganisationUkprn = $_awardingOrganisationUkprn);
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
     * Get Credits value
     * @return long|null
     */
    public function getCredits()
    {
        return $this->Credits;
    }
    /**
     * Set Credits value
     * @param long $_credits the Credits
     * @return long
     */
    public function setCredits($_credits)
    {
        return ($this->Credits = $_credits);
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
     * Get QualificationType value
     * @return string|null
     */
    public function getQualificationType()
    {
        return $this->QualificationType;
    }
    /**
     * Set QualificationType value
     * @param string $_qualificationType the QualificationType
     * @return string
     */
    public function setQualificationType($_qualificationType)
    {
        return ($this->QualificationType = $_qualificationType);
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
     * Get Source value
     * @return string|null
     */
    public function getSource()
    {
        return $this->Source;
    }
    /**
     * Set Source value
     * @param string $_source the Source
     * @return string
     */
    public function setSource($_source)
    {
        return ($this->Source = $_source);
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
     * Get Subject value
     * @return string|null
     */
    public function getSubject()
    {
        return $this->Subject;
    }
    /**
     * Set Subject value
     * @param string $_subject the Subject
     * @return string
     */
    public function setSubject($_subject)
    {
        return ($this->Subject = $_subject);
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
     * Get UnderDataChallenge value
     * @return string|null
     */
    public function getUnderDataChallenge()
    {
        return $this->UnderDataChallenge;
    }
    /**
     * Set UnderDataChallenge value
     * @param string $_underDataChallenge the UnderDataChallenge
     * @return string
     */
    public function setUnderDataChallenge($_underDataChallenge)
    {
        return ($this->UnderDataChallenge = $_underDataChallenge);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLearningEvent
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
