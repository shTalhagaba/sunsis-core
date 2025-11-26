<?php
/**
 * File for class LogicMelonStructGetApplicationsWithFiltersPaged
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetApplicationsWithFiltersPaged originally named GetApplicationsWithFiltersPaged
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetApplicationsWithFiltersPaged extends LogicMelonWsdlClass
{
    /**
     * The ApplicationStartDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $ApplicationStartDateTime;
    /**
     * The ApplicationEndDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $ApplicationEndDateTime;
    /**
     * The bIncludeEmailBody
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $bIncludeEmailBody;
    /**
     * The bIncludeAttachment
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $bIncludeAttachment;
    /**
     * The bIncludeParsed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $bIncludeParsed;
    /**
     * The bIncludeEmail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $bIncludeEmail;
    /**
     * The CurrentPage
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $CurrentPage;
    /**
     * The RowsPerPage
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $RowsPerPage;
    /**
     * The sCultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCultureID;
    /**
     * The sAPIKey
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAPIKey;
    /**
     * The sUsername
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sUsername;
    /**
     * The sUserIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sUserIdentifier;
    /**
     * The sStartOrganisation
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sStartOrganisation;
    /**
     * The sAdvertIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertIdentifier;
    /**
     * The sAdvertReference
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertReference;
    /**
     * The sAdvertID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertID;
    /**
     * The Destinations
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $Destinations;
    /**
     * The Ranking
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $Ranking;
    /**
     * The ProgressID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $ProgressID;
    /**
     * The AdvertCandidateID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $AdvertCandidateID;
    /**
     * The Filters
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfNameValue
     */
    public $Filters;
    /**
     * The OrderBy
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $OrderBy;
    /**
     * Constructor method for GetApplicationsWithFiltersPaged
     * @see parent::__construct()
     * @param dateTime $_applicationStartDateTime
     * @param dateTime $_applicationEndDateTime
     * @param boolean $_bIncludeEmailBody
     * @param boolean $_bIncludeAttachment
     * @param boolean $_bIncludeParsed
     * @param boolean $_bIncludeEmail
     * @param int $_currentPage
     * @param int $_rowsPerPage
     * @param string $_sCultureID
     * @param string $_sAPIKey
     * @param string $_sUsername
     * @param string $_sUserIdentifier
     * @param string $_sStartOrganisation
     * @param string $_sAdvertIdentifier
     * @param string $_sAdvertReference
     * @param string $_sAdvertID
     * @param LogicMelonStructArrayOfString $_destinations
     * @param LogicMelonStructArrayOfString $_ranking
     * @param LogicMelonStructArrayOfString $_progressID
     * @param LogicMelonStructArrayOfString $_advertCandidateID
     * @param LogicMelonStructArrayOfNameValue $_filters
     * @param string $_orderBy
     * @return LogicMelonStructGetApplicationsWithFiltersPaged
     */
    public function __construct($_applicationStartDateTime,$_applicationEndDateTime,$_bIncludeEmailBody,$_bIncludeAttachment,$_bIncludeParsed,$_bIncludeEmail,$_currentPage,$_rowsPerPage,$_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sUserIdentifier = NULL,$_sStartOrganisation = NULL,$_sAdvertIdentifier = NULL,$_sAdvertReference = NULL,$_sAdvertID = NULL,$_destinations = NULL,$_ranking = NULL,$_progressID = NULL,$_advertCandidateID = NULL,$_filters = NULL,$_orderBy = NULL)
    {
        parent::__construct(array('ApplicationStartDateTime'=>$_applicationStartDateTime,'ApplicationEndDateTime'=>$_applicationEndDateTime,'bIncludeEmailBody'=>$_bIncludeEmailBody,'bIncludeAttachment'=>$_bIncludeAttachment,'bIncludeParsed'=>$_bIncludeParsed,'bIncludeEmail'=>$_bIncludeEmail,'CurrentPage'=>$_currentPage,'RowsPerPage'=>$_rowsPerPage,'sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sUserIdentifier'=>$_sUserIdentifier,'sStartOrganisation'=>$_sStartOrganisation,'sAdvertIdentifier'=>$_sAdvertIdentifier,'sAdvertReference'=>$_sAdvertReference,'sAdvertID'=>$_sAdvertID,'Destinations'=>($_destinations instanceof LogicMelonStructArrayOfString)?$_destinations:new LogicMelonStructArrayOfString($_destinations),'Ranking'=>($_ranking instanceof LogicMelonStructArrayOfString)?$_ranking:new LogicMelonStructArrayOfString($_ranking),'ProgressID'=>($_progressID instanceof LogicMelonStructArrayOfString)?$_progressID:new LogicMelonStructArrayOfString($_progressID),'AdvertCandidateID'=>($_advertCandidateID instanceof LogicMelonStructArrayOfString)?$_advertCandidateID:new LogicMelonStructArrayOfString($_advertCandidateID),'Filters'=>($_filters instanceof LogicMelonStructArrayOfNameValue)?$_filters:new LogicMelonStructArrayOfNameValue($_filters),'OrderBy'=>$_orderBy),false);
    }
    /**
     * Get ApplicationStartDateTime value
     * @return dateTime
     */
    public function getApplicationStartDateTime()
    {
        return $this->ApplicationStartDateTime;
    }
    /**
     * Set ApplicationStartDateTime value
     * @param dateTime $_applicationStartDateTime the ApplicationStartDateTime
     * @return dateTime
     */
    public function setApplicationStartDateTime($_applicationStartDateTime)
    {
        return ($this->ApplicationStartDateTime = $_applicationStartDateTime);
    }
    /**
     * Get ApplicationEndDateTime value
     * @return dateTime
     */
    public function getApplicationEndDateTime()
    {
        return $this->ApplicationEndDateTime;
    }
    /**
     * Set ApplicationEndDateTime value
     * @param dateTime $_applicationEndDateTime the ApplicationEndDateTime
     * @return dateTime
     */
    public function setApplicationEndDateTime($_applicationEndDateTime)
    {
        return ($this->ApplicationEndDateTime = $_applicationEndDateTime);
    }
    /**
     * Get bIncludeEmailBody value
     * @return boolean
     */
    public function getBIncludeEmailBody()
    {
        return $this->bIncludeEmailBody;
    }
    /**
     * Set bIncludeEmailBody value
     * @param boolean $_bIncludeEmailBody the bIncludeEmailBody
     * @return boolean
     */
    public function setBIncludeEmailBody($_bIncludeEmailBody)
    {
        return ($this->bIncludeEmailBody = $_bIncludeEmailBody);
    }
    /**
     * Get bIncludeAttachment value
     * @return boolean
     */
    public function getBIncludeAttachment()
    {
        return $this->bIncludeAttachment;
    }
    /**
     * Set bIncludeAttachment value
     * @param boolean $_bIncludeAttachment the bIncludeAttachment
     * @return boolean
     */
    public function setBIncludeAttachment($_bIncludeAttachment)
    {
        return ($this->bIncludeAttachment = $_bIncludeAttachment);
    }
    /**
     * Get bIncludeParsed value
     * @return boolean
     */
    public function getBIncludeParsed()
    {
        return $this->bIncludeParsed;
    }
    /**
     * Set bIncludeParsed value
     * @param boolean $_bIncludeParsed the bIncludeParsed
     * @return boolean
     */
    public function setBIncludeParsed($_bIncludeParsed)
    {
        return ($this->bIncludeParsed = $_bIncludeParsed);
    }
    /**
     * Get bIncludeEmail value
     * @return boolean
     */
    public function getBIncludeEmail()
    {
        return $this->bIncludeEmail;
    }
    /**
     * Set bIncludeEmail value
     * @param boolean $_bIncludeEmail the bIncludeEmail
     * @return boolean
     */
    public function setBIncludeEmail($_bIncludeEmail)
    {
        return ($this->bIncludeEmail = $_bIncludeEmail);
    }
    /**
     * Get CurrentPage value
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->CurrentPage;
    }
    /**
     * Set CurrentPage value
     * @param int $_currentPage the CurrentPage
     * @return int
     */
    public function setCurrentPage($_currentPage)
    {
        return ($this->CurrentPage = $_currentPage);
    }
    /**
     * Get RowsPerPage value
     * @return int
     */
    public function getRowsPerPage()
    {
        return $this->RowsPerPage;
    }
    /**
     * Set RowsPerPage value
     * @param int $_rowsPerPage the RowsPerPage
     * @return int
     */
    public function setRowsPerPage($_rowsPerPage)
    {
        return ($this->RowsPerPage = $_rowsPerPage);
    }
    /**
     * Get sCultureID value
     * @return string|null
     */
    public function getSCultureID()
    {
        return $this->sCultureID;
    }
    /**
     * Set sCultureID value
     * @param string $_sCultureID the sCultureID
     * @return string
     */
    public function setSCultureID($_sCultureID)
    {
        return ($this->sCultureID = $_sCultureID);
    }
    /**
     * Get sAPIKey value
     * @return string|null
     */
    public function getSAPIKey()
    {
        return $this->sAPIKey;
    }
    /**
     * Set sAPIKey value
     * @param string $_sAPIKey the sAPIKey
     * @return string
     */
    public function setSAPIKey($_sAPIKey)
    {
        return ($this->sAPIKey = $_sAPIKey);
    }
    /**
     * Get sUsername value
     * @return string|null
     */
    public function getSUsername()
    {
        return $this->sUsername;
    }
    /**
     * Set sUsername value
     * @param string $_sUsername the sUsername
     * @return string
     */
    public function setSUsername($_sUsername)
    {
        return ($this->sUsername = $_sUsername);
    }
    /**
     * Get sUserIdentifier value
     * @return string|null
     */
    public function getSUserIdentifier()
    {
        return $this->sUserIdentifier;
    }
    /**
     * Set sUserIdentifier value
     * @param string $_sUserIdentifier the sUserIdentifier
     * @return string
     */
    public function setSUserIdentifier($_sUserIdentifier)
    {
        return ($this->sUserIdentifier = $_sUserIdentifier);
    }
    /**
     * Get sStartOrganisation value
     * @return string|null
     */
    public function getSStartOrganisation()
    {
        return $this->sStartOrganisation;
    }
    /**
     * Set sStartOrganisation value
     * @param string $_sStartOrganisation the sStartOrganisation
     * @return string
     */
    public function setSStartOrganisation($_sStartOrganisation)
    {
        return ($this->sStartOrganisation = $_sStartOrganisation);
    }
    /**
     * Get sAdvertIdentifier value
     * @return string|null
     */
    public function getSAdvertIdentifier()
    {
        return $this->sAdvertIdentifier;
    }
    /**
     * Set sAdvertIdentifier value
     * @param string $_sAdvertIdentifier the sAdvertIdentifier
     * @return string
     */
    public function setSAdvertIdentifier($_sAdvertIdentifier)
    {
        return ($this->sAdvertIdentifier = $_sAdvertIdentifier);
    }
    /**
     * Get sAdvertReference value
     * @return string|null
     */
    public function getSAdvertReference()
    {
        return $this->sAdvertReference;
    }
    /**
     * Set sAdvertReference value
     * @param string $_sAdvertReference the sAdvertReference
     * @return string
     */
    public function setSAdvertReference($_sAdvertReference)
    {
        return ($this->sAdvertReference = $_sAdvertReference);
    }
    /**
     * Get sAdvertID value
     * @return string|null
     */
    public function getSAdvertID()
    {
        return $this->sAdvertID;
    }
    /**
     * Set sAdvertID value
     * @param string $_sAdvertID the sAdvertID
     * @return string
     */
    public function setSAdvertID($_sAdvertID)
    {
        return ($this->sAdvertID = $_sAdvertID);
    }
    /**
     * Get Destinations value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getDestinations()
    {
        return $this->Destinations;
    }
    /**
     * Set Destinations value
     * @param LogicMelonStructArrayOfString $_destinations the Destinations
     * @return LogicMelonStructArrayOfString
     */
    public function setDestinations($_destinations)
    {
        return ($this->Destinations = $_destinations);
    }
    /**
     * Get Ranking value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getRanking()
    {
        return $this->Ranking;
    }
    /**
     * Set Ranking value
     * @param LogicMelonStructArrayOfString $_ranking the Ranking
     * @return LogicMelonStructArrayOfString
     */
    public function setRanking($_ranking)
    {
        return ($this->Ranking = $_ranking);
    }
    /**
     * Get ProgressID value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getProgressID()
    {
        return $this->ProgressID;
    }
    /**
     * Set ProgressID value
     * @param LogicMelonStructArrayOfString $_progressID the ProgressID
     * @return LogicMelonStructArrayOfString
     */
    public function setProgressID($_progressID)
    {
        return ($this->ProgressID = $_progressID);
    }
    /**
     * Get AdvertCandidateID value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getAdvertCandidateID()
    {
        return $this->AdvertCandidateID;
    }
    /**
     * Set AdvertCandidateID value
     * @param LogicMelonStructArrayOfString $_advertCandidateID the AdvertCandidateID
     * @return LogicMelonStructArrayOfString
     */
    public function setAdvertCandidateID($_advertCandidateID)
    {
        return ($this->AdvertCandidateID = $_advertCandidateID);
    }
    /**
     * Get Filters value
     * @return LogicMelonStructArrayOfNameValue|null
     */
    public function getFilters()
    {
        return $this->Filters;
    }
    /**
     * Set Filters value
     * @param LogicMelonStructArrayOfNameValue $_filters the Filters
     * @return LogicMelonStructArrayOfNameValue
     */
    public function setFilters($_filters)
    {
        return ($this->Filters = $_filters);
    }
    /**
     * Get OrderBy value
     * @return string|null
     */
    public function getOrderBy()
    {
        return $this->OrderBy;
    }
    /**
     * Set OrderBy value
     * @param string $_orderBy the OrderBy
     * @return string
     */
    public function setOrderBy($_orderBy)
    {
        return ($this->OrderBy = $_orderBy);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetApplicationsWithFiltersPaged
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
