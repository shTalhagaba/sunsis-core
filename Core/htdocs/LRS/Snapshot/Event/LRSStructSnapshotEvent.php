<?php
/**
 * File for class LRSStructSnapshotEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructSnapshotEvent originally named SnapshotEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructSnapshotEvent extends LRSWsdlClass
{
    /**
     * The AchievementAwardDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $AchievementAwardDate;
    /**
     * The DateLoaded
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $DateLoaded;
    /**
     * The LastUpdatedDateTime
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $LastUpdatedDateTime;
    /**
     * The LearningEventId
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $LearningEventId;
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
     * The QualificationCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationCode;
    /**
     * Constructor method for SnapshotEvent
     * @see parent::__construct()
     * @param dateTime $_achievementAwardDate
     * @param dateTime $_dateLoaded
     * @param dateTime $_lastUpdatedDateTime
     * @param int $_learningEventId
     * @param dateTime $_participationEndDate
     * @param dateTime $_participationStartDate
     * @param string $_qualificationCode
     * @return LRSStructSnapshotEvent
     */
    public function __construct($_achievementAwardDate = NULL,$_dateLoaded = NULL,$_lastUpdatedDateTime = NULL,$_learningEventId = NULL,$_participationEndDate = NULL,$_participationStartDate = NULL,$_qualificationCode = NULL)
    {
        parent::__construct(array('AchievementAwardDate'=>$_achievementAwardDate,'DateLoaded'=>$_dateLoaded,'LastUpdatedDateTime'=>$_lastUpdatedDateTime,'LearningEventId'=>$_learningEventId,'ParticipationEndDate'=>$_participationEndDate,'ParticipationStartDate'=>$_participationStartDate,'QualificationCode'=>$_qualificationCode),false);
    }
    /**
     * Get AchievementAwardDate value
     * @return dateTime|null
     */
    public function getAchievementAwardDate()
    {
        return $this->AchievementAwardDate;
    }
    /**
     * Set AchievementAwardDate value
     * @param dateTime $_achievementAwardDate the AchievementAwardDate
     * @return dateTime
     */
    public function setAchievementAwardDate($_achievementAwardDate)
    {
        return ($this->AchievementAwardDate = $_achievementAwardDate);
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
     * Get LearningEventId value
     * @return int|null
     */
    public function getLearningEventId()
    {
        return $this->LearningEventId;
    }
    /**
     * Set LearningEventId value
     * @param int $_learningEventId the LearningEventId
     * @return int
     */
    public function setLearningEventId($_learningEventId)
    {
        return ($this->LearningEventId = $_learningEventId);
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
     * Get QualificationCode value
     * @return string|null
     */
    public function getQualificationCode()
    {
        return $this->QualificationCode;
    }
    /**
     * Set QualificationCode value
     * @param string $_qualificationCode the QualificationCode
     * @return string
     */
    public function setQualificationCode($_qualificationCode)
    {
        return ($this->QualificationCode = $_qualificationCode);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructSnapshotEvent
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
