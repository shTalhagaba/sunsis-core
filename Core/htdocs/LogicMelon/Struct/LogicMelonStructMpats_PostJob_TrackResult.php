<?php
/**
 * File for class LogicMelonStructMpats_PostJob_TrackResult
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructMpats_PostJob_TrackResult originally named mpats_PostJob_TrackResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructMpats_PostJob_TrackResult extends LogicMelonWsdlClass
{
    /**
     * The PostingID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $PostingID;
    /**
     * The AdvertID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $AdvertID;
    /**
     * The FeedID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $FeedID;
    /**
     * The PostDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var dateTime
     */
    public $PostDate;
    /**
     * The PostingStatusID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var short
     */
    public $PostingStatusID;
    /**
     * The ResponseClassID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var short
     */
    public $ResponseClassID;
    /**
     * The CorrectDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $CorrectDate;
    /**
     * The CorrectStatusID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var short
     */
    public $CorrectStatusID;
    /**
     * The CorrectClassID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var short
     */
    public $CorrectClassID;
    /**
     * The EstimatedExpireDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $EstimatedExpireDate;
    /**
     * The ApplicationsCache
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $ApplicationsCache;
    /**
     * The SuitableCache
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $SuitableCache;
    /**
     * The MaybeSuitableCache
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $MaybeSuitableCache;
    /**
     * The UnsuitableCache
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $UnsuitableCache;
    /**
     * The SchemaIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SchemaIdentifier;
    /**
     * The FeedIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FeedIdentifier;
    /**
     * The FeedName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FeedName;
    /**
     * The ResponseSummary
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $ResponseSummary;
    /**
     * The PostingStatus
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $PostingStatus;
    /**
     * The PostingClass
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $PostingClass;
    /**
     * The PostingStatusDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $PostingStatusDescription;
    /**
     * The PostingClassDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $PostingClassDescription;
    /**
     * The CorrectSummary
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CorrectSummary;
    /**
     * The CorrectStatus
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CorrectStatus;
    /**
     * The CorrectClass
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CorrectClass;
    /**
     * The CorrectStatusDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CorrectStatusDescription;
    /**
     * The JobBoardURL
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $JobBoardURL;
    /**
     * The JobBoardIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $JobBoardIdentifier;
    /**
     * Constructor method for mpats_PostJob_TrackResult
     * @see parent::__construct()
     * @param int $_postingID
     * @param int $_advertID
     * @param int $_feedID
     * @param dateTime $_postDate
     * @param short $_postingStatusID
     * @param short $_responseClassID
     * @param dateTime $_correctDate
     * @param short $_correctStatusID
     * @param short $_correctClassID
     * @param dateTime $_estimatedExpireDate
     * @param int $_applicationsCache
     * @param int $_suitableCache
     * @param int $_maybeSuitableCache
     * @param int $_unsuitableCache
     * @param string $_schemaIdentifier
     * @param string $_feedIdentifier
     * @param string $_feedName
     * @param string $_responseSummary
     * @param string $_postingStatus
     * @param string $_postingClass
     * @param string $_postingStatusDescription
     * @param string $_postingClassDescription
     * @param string $_correctSummary
     * @param string $_correctStatus
     * @param string $_correctClass
     * @param string $_correctStatusDescription
     * @param string $_jobBoardURL
     * @param string $_jobBoardIdentifier
     * @return LogicMelonStructMpats_PostJob_TrackResult
     */
    public function __construct($_postingID,$_advertID,$_feedID,$_postDate,$_postingStatusID,$_responseClassID,$_correctDate,$_correctStatusID,$_correctClassID,$_estimatedExpireDate,$_applicationsCache,$_suitableCache,$_maybeSuitableCache,$_unsuitableCache,$_schemaIdentifier = NULL,$_feedIdentifier = NULL,$_feedName = NULL,$_responseSummary = NULL,$_postingStatus = NULL,$_postingClass = NULL,$_postingStatusDescription = NULL,$_postingClassDescription = NULL,$_correctSummary = NULL,$_correctStatus = NULL,$_correctClass = NULL,$_correctStatusDescription = NULL,$_jobBoardURL = NULL,$_jobBoardIdentifier = NULL)
    {
        parent::__construct(array('PostingID'=>$_postingID,'AdvertID'=>$_advertID,'FeedID'=>$_feedID,'PostDate'=>$_postDate,'PostingStatusID'=>$_postingStatusID,'ResponseClassID'=>$_responseClassID,'CorrectDate'=>$_correctDate,'CorrectStatusID'=>$_correctStatusID,'CorrectClassID'=>$_correctClassID,'EstimatedExpireDate'=>$_estimatedExpireDate,'ApplicationsCache'=>$_applicationsCache,'SuitableCache'=>$_suitableCache,'MaybeSuitableCache'=>$_maybeSuitableCache,'UnsuitableCache'=>$_unsuitableCache,'SchemaIdentifier'=>$_schemaIdentifier,'FeedIdentifier'=>$_feedIdentifier,'FeedName'=>$_feedName,'ResponseSummary'=>$_responseSummary,'PostingStatus'=>$_postingStatus,'PostingClass'=>$_postingClass,'PostingStatusDescription'=>$_postingStatusDescription,'PostingClassDescription'=>$_postingClassDescription,'CorrectSummary'=>$_correctSummary,'CorrectStatus'=>$_correctStatus,'CorrectClass'=>$_correctClass,'CorrectStatusDescription'=>$_correctStatusDescription,'JobBoardURL'=>$_jobBoardURL,'JobBoardIdentifier'=>$_jobBoardIdentifier),false);
    }
    /**
     * Get PostingID value
     * @return int
     */
    public function getPostingID()
    {
        return $this->PostingID;
    }
    /**
     * Set PostingID value
     * @param int $_postingID the PostingID
     * @return int
     */
    public function setPostingID($_postingID)
    {
        return ($this->PostingID = $_postingID);
    }
    /**
     * Get AdvertID value
     * @return int
     */
    public function getAdvertID()
    {
        return $this->AdvertID;
    }
    /**
     * Set AdvertID value
     * @param int $_advertID the AdvertID
     * @return int
     */
    public function setAdvertID($_advertID)
    {
        return ($this->AdvertID = $_advertID);
    }
    /**
     * Get FeedID value
     * @return int
     */
    public function getFeedID()
    {
        return $this->FeedID;
    }
    /**
     * Set FeedID value
     * @param int $_feedID the FeedID
     * @return int
     */
    public function setFeedID($_feedID)
    {
        return ($this->FeedID = $_feedID);
    }
    /**
     * Get PostDate value
     * @return dateTime
     */
    public function getPostDate()
    {
        return $this->PostDate;
    }
    /**
     * Set PostDate value
     * @param dateTime $_postDate the PostDate
     * @return dateTime
     */
    public function setPostDate($_postDate)
    {
        return ($this->PostDate = $_postDate);
    }
    /**
     * Get PostingStatusID value
     * @return short
     */
    public function getPostingStatusID()
    {
        return $this->PostingStatusID;
    }
    /**
     * Set PostingStatusID value
     * @param short $_postingStatusID the PostingStatusID
     * @return short
     */
    public function setPostingStatusID($_postingStatusID)
    {
        return ($this->PostingStatusID = $_postingStatusID);
    }
    /**
     * Get ResponseClassID value
     * @return short
     */
    public function getResponseClassID()
    {
        return $this->ResponseClassID;
    }
    /**
     * Set ResponseClassID value
     * @param short $_responseClassID the ResponseClassID
     * @return short
     */
    public function setResponseClassID($_responseClassID)
    {
        return ($this->ResponseClassID = $_responseClassID);
    }
    /**
     * Get CorrectDate value
     * @return dateTime
     */
    public function getCorrectDate()
    {
        return $this->CorrectDate;
    }
    /**
     * Set CorrectDate value
     * @param dateTime $_correctDate the CorrectDate
     * @return dateTime
     */
    public function setCorrectDate($_correctDate)
    {
        return ($this->CorrectDate = $_correctDate);
    }
    /**
     * Get CorrectStatusID value
     * @return short
     */
    public function getCorrectStatusID()
    {
        return $this->CorrectStatusID;
    }
    /**
     * Set CorrectStatusID value
     * @param short $_correctStatusID the CorrectStatusID
     * @return short
     */
    public function setCorrectStatusID($_correctStatusID)
    {
        return ($this->CorrectStatusID = $_correctStatusID);
    }
    /**
     * Get CorrectClassID value
     * @return short
     */
    public function getCorrectClassID()
    {
        return $this->CorrectClassID;
    }
    /**
     * Set CorrectClassID value
     * @param short $_correctClassID the CorrectClassID
     * @return short
     */
    public function setCorrectClassID($_correctClassID)
    {
        return ($this->CorrectClassID = $_correctClassID);
    }
    /**
     * Get EstimatedExpireDate value
     * @return dateTime
     */
    public function getEstimatedExpireDate()
    {
        return $this->EstimatedExpireDate;
    }
    /**
     * Set EstimatedExpireDate value
     * @param dateTime $_estimatedExpireDate the EstimatedExpireDate
     * @return dateTime
     */
    public function setEstimatedExpireDate($_estimatedExpireDate)
    {
        return ($this->EstimatedExpireDate = $_estimatedExpireDate);
    }
    /**
     * Get ApplicationsCache value
     * @return int
     */
    public function getApplicationsCache()
    {
        return $this->ApplicationsCache;
    }
    /**
     * Set ApplicationsCache value
     * @param int $_applicationsCache the ApplicationsCache
     * @return int
     */
    public function setApplicationsCache($_applicationsCache)
    {
        return ($this->ApplicationsCache = $_applicationsCache);
    }
    /**
     * Get SuitableCache value
     * @return int
     */
    public function getSuitableCache()
    {
        return $this->SuitableCache;
    }
    /**
     * Set SuitableCache value
     * @param int $_suitableCache the SuitableCache
     * @return int
     */
    public function setSuitableCache($_suitableCache)
    {
        return ($this->SuitableCache = $_suitableCache);
    }
    /**
     * Get MaybeSuitableCache value
     * @return int
     */
    public function getMaybeSuitableCache()
    {
        return $this->MaybeSuitableCache;
    }
    /**
     * Set MaybeSuitableCache value
     * @param int $_maybeSuitableCache the MaybeSuitableCache
     * @return int
     */
    public function setMaybeSuitableCache($_maybeSuitableCache)
    {
        return ($this->MaybeSuitableCache = $_maybeSuitableCache);
    }
    /**
     * Get UnsuitableCache value
     * @return int
     */
    public function getUnsuitableCache()
    {
        return $this->UnsuitableCache;
    }
    /**
     * Set UnsuitableCache value
     * @param int $_unsuitableCache the UnsuitableCache
     * @return int
     */
    public function setUnsuitableCache($_unsuitableCache)
    {
        return ($this->UnsuitableCache = $_unsuitableCache);
    }
    /**
     * Get SchemaIdentifier value
     * @return string|null
     */
    public function getSchemaIdentifier()
    {
        return $this->SchemaIdentifier;
    }
    /**
     * Set SchemaIdentifier value
     * @param string $_schemaIdentifier the SchemaIdentifier
     * @return string
     */
    public function setSchemaIdentifier($_schemaIdentifier)
    {
        return ($this->SchemaIdentifier = $_schemaIdentifier);
    }
    /**
     * Get FeedIdentifier value
     * @return string|null
     */
    public function getFeedIdentifier()
    {
        return $this->FeedIdentifier;
    }
    /**
     * Set FeedIdentifier value
     * @param string $_feedIdentifier the FeedIdentifier
     * @return string
     */
    public function setFeedIdentifier($_feedIdentifier)
    {
        return ($this->FeedIdentifier = $_feedIdentifier);
    }
    /**
     * Get FeedName value
     * @return string|null
     */
    public function getFeedName()
    {
        return $this->FeedName;
    }
    /**
     * Set FeedName value
     * @param string $_feedName the FeedName
     * @return string
     */
    public function setFeedName($_feedName)
    {
        return ($this->FeedName = $_feedName);
    }
    /**
     * Get ResponseSummary value
     * @return string|null
     */
    public function getResponseSummary()
    {
        return $this->ResponseSummary;
    }
    /**
     * Set ResponseSummary value
     * @param string $_responseSummary the ResponseSummary
     * @return string
     */
    public function setResponseSummary($_responseSummary)
    {
        return ($this->ResponseSummary = $_responseSummary);
    }
    /**
     * Get PostingStatus value
     * @return string|null
     */
    public function getPostingStatus()
    {
        return $this->PostingStatus;
    }
    /**
     * Set PostingStatus value
     * @param string $_postingStatus the PostingStatus
     * @return string
     */
    public function setPostingStatus($_postingStatus)
    {
        return ($this->PostingStatus = $_postingStatus);
    }
    /**
     * Get PostingClass value
     * @return string|null
     */
    public function getPostingClass()
    {
        return $this->PostingClass;
    }
    /**
     * Set PostingClass value
     * @param string $_postingClass the PostingClass
     * @return string
     */
    public function setPostingClass($_postingClass)
    {
        return ($this->PostingClass = $_postingClass);
    }
    /**
     * Get PostingStatusDescription value
     * @return string|null
     */
    public function getPostingStatusDescription()
    {
        return $this->PostingStatusDescription;
    }
    /**
     * Set PostingStatusDescription value
     * @param string $_postingStatusDescription the PostingStatusDescription
     * @return string
     */
    public function setPostingStatusDescription($_postingStatusDescription)
    {
        return ($this->PostingStatusDescription = $_postingStatusDescription);
    }
    /**
     * Get PostingClassDescription value
     * @return string|null
     */
    public function getPostingClassDescription()
    {
        return $this->PostingClassDescription;
    }
    /**
     * Set PostingClassDescription value
     * @param string $_postingClassDescription the PostingClassDescription
     * @return string
     */
    public function setPostingClassDescription($_postingClassDescription)
    {
        return ($this->PostingClassDescription = $_postingClassDescription);
    }
    /**
     * Get CorrectSummary value
     * @return string|null
     */
    public function getCorrectSummary()
    {
        return $this->CorrectSummary;
    }
    /**
     * Set CorrectSummary value
     * @param string $_correctSummary the CorrectSummary
     * @return string
     */
    public function setCorrectSummary($_correctSummary)
    {
        return ($this->CorrectSummary = $_correctSummary);
    }
    /**
     * Get CorrectStatus value
     * @return string|null
     */
    public function getCorrectStatus()
    {
        return $this->CorrectStatus;
    }
    /**
     * Set CorrectStatus value
     * @param string $_correctStatus the CorrectStatus
     * @return string
     */
    public function setCorrectStatus($_correctStatus)
    {
        return ($this->CorrectStatus = $_correctStatus);
    }
    /**
     * Get CorrectClass value
     * @return string|null
     */
    public function getCorrectClass()
    {
        return $this->CorrectClass;
    }
    /**
     * Set CorrectClass value
     * @param string $_correctClass the CorrectClass
     * @return string
     */
    public function setCorrectClass($_correctClass)
    {
        return ($this->CorrectClass = $_correctClass);
    }
    /**
     * Get CorrectStatusDescription value
     * @return string|null
     */
    public function getCorrectStatusDescription()
    {
        return $this->CorrectStatusDescription;
    }
    /**
     * Set CorrectStatusDescription value
     * @param string $_correctStatusDescription the CorrectStatusDescription
     * @return string
     */
    public function setCorrectStatusDescription($_correctStatusDescription)
    {
        return ($this->CorrectStatusDescription = $_correctStatusDescription);
    }
    /**
     * Get JobBoardURL value
     * @return string|null
     */
    public function getJobBoardURL()
    {
        return $this->JobBoardURL;
    }
    /**
     * Set JobBoardURL value
     * @param string $_jobBoardURL the JobBoardURL
     * @return string
     */
    public function setJobBoardURL($_jobBoardURL)
    {
        return ($this->JobBoardURL = $_jobBoardURL);
    }
    /**
     * Get JobBoardIdentifier value
     * @return string|null
     */
    public function getJobBoardIdentifier()
    {
        return $this->JobBoardIdentifier;
    }
    /**
     * Set JobBoardIdentifier value
     * @param string $_jobBoardIdentifier the JobBoardIdentifier
     * @return string
     */
    public function setJobBoardIdentifier($_jobBoardIdentifier)
    {
        return ($this->JobBoardIdentifier = $_jobBoardIdentifier);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructMpats_PostJob_TrackResult
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
