<?php
/**
 * File for class LogicMelonStructGetApplicationsPaged
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetApplicationsPaged originally named GetApplicationsPaged
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetApplicationsPaged extends LogicMelonWsdlClass
{
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
     * The DestinationsAsCSV
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $DestinationsAsCSV;
    /**
     * The sApplicationStartDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sApplicationStartDateTime;
    /**
     * The sApplicationEndDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sApplicationEndDateTime;
    /**
     * The RankingAsCSV
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $RankingAsCSV;
    /**
     * The ProgressIDAsCSV
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $ProgressIDAsCSV;
    /**
     * The AdvertCandidateIDAsCSV
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertCandidateIDAsCSV;
    /**
     * The boolIncludeEmailBody
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $boolIncludeEmailBody;
    /**
     * The boolIncludeAttachment
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $boolIncludeAttachment;
    /**
     * The boolIncludeParsed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $boolIncludeParsed;
    /**
     * The boolIncludeEmail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $boolIncludeEmail;
    /**
     * The OrderBy
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $OrderBy;
    /**
     * Constructor method for GetApplicationsPaged
     * @see parent::__construct()
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
     * @param string $_destinationsAsCSV
     * @param string $_sApplicationStartDateTime
     * @param string $_sApplicationEndDateTime
     * @param string $_rankingAsCSV
     * @param string $_progressIDAsCSV
     * @param string $_advertCandidateIDAsCSV
     * @param string $_boolIncludeEmailBody
     * @param string $_boolIncludeAttachment
     * @param string $_boolIncludeParsed
     * @param string $_boolIncludeEmail
     * @param string $_orderBy
     * @return LogicMelonStructGetApplicationsPaged
     */
    public function __construct($_currentPage,$_rowsPerPage,$_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sUserIdentifier = NULL,$_sStartOrganisation = NULL,$_sAdvertIdentifier = NULL,$_sAdvertReference = NULL,$_sAdvertID = NULL,$_destinationsAsCSV = NULL,$_sApplicationStartDateTime = NULL,$_sApplicationEndDateTime = NULL,$_rankingAsCSV = NULL,$_progressIDAsCSV = NULL,$_advertCandidateIDAsCSV = NULL,$_boolIncludeEmailBody = NULL,$_boolIncludeAttachment = NULL,$_boolIncludeParsed = NULL,$_boolIncludeEmail = NULL,$_orderBy = NULL)
    {
        parent::__construct(array('CurrentPage'=>$_currentPage,'RowsPerPage'=>$_rowsPerPage,'sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sUserIdentifier'=>$_sUserIdentifier,'sStartOrganisation'=>$_sStartOrganisation,'sAdvertIdentifier'=>$_sAdvertIdentifier,'sAdvertReference'=>$_sAdvertReference,'sAdvertID'=>$_sAdvertID,'DestinationsAsCSV'=>$_destinationsAsCSV,'sApplicationStartDateTime'=>$_sApplicationStartDateTime,'sApplicationEndDateTime'=>$_sApplicationEndDateTime,'RankingAsCSV'=>$_rankingAsCSV,'ProgressIDAsCSV'=>$_progressIDAsCSV,'AdvertCandidateIDAsCSV'=>$_advertCandidateIDAsCSV,'boolIncludeEmailBody'=>$_boolIncludeEmailBody,'boolIncludeAttachment'=>$_boolIncludeAttachment,'boolIncludeParsed'=>$_boolIncludeParsed,'boolIncludeEmail'=>$_boolIncludeEmail,'OrderBy'=>$_orderBy),false);
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
     * Get DestinationsAsCSV value
     * @return string|null
     */
    public function getDestinationsAsCSV()
    {
        return $this->DestinationsAsCSV;
    }
    /**
     * Set DestinationsAsCSV value
     * @param string $_destinationsAsCSV the DestinationsAsCSV
     * @return string
     */
    public function setDestinationsAsCSV($_destinationsAsCSV)
    {
        return ($this->DestinationsAsCSV = $_destinationsAsCSV);
    }
    /**
     * Get sApplicationStartDateTime value
     * @return string|null
     */
    public function getSApplicationStartDateTime()
    {
        return $this->sApplicationStartDateTime;
    }
    /**
     * Set sApplicationStartDateTime value
     * @param string $_sApplicationStartDateTime the sApplicationStartDateTime
     * @return string
     */
    public function setSApplicationStartDateTime($_sApplicationStartDateTime)
    {
        return ($this->sApplicationStartDateTime = $_sApplicationStartDateTime);
    }
    /**
     * Get sApplicationEndDateTime value
     * @return string|null
     */
    public function getSApplicationEndDateTime()
    {
        return $this->sApplicationEndDateTime;
    }
    /**
     * Set sApplicationEndDateTime value
     * @param string $_sApplicationEndDateTime the sApplicationEndDateTime
     * @return string
     */
    public function setSApplicationEndDateTime($_sApplicationEndDateTime)
    {
        return ($this->sApplicationEndDateTime = $_sApplicationEndDateTime);
    }
    /**
     * Get RankingAsCSV value
     * @return string|null
     */
    public function getRankingAsCSV()
    {
        return $this->RankingAsCSV;
    }
    /**
     * Set RankingAsCSV value
     * @param string $_rankingAsCSV the RankingAsCSV
     * @return string
     */
    public function setRankingAsCSV($_rankingAsCSV)
    {
        return ($this->RankingAsCSV = $_rankingAsCSV);
    }
    /**
     * Get ProgressIDAsCSV value
     * @return string|null
     */
    public function getProgressIDAsCSV()
    {
        return $this->ProgressIDAsCSV;
    }
    /**
     * Set ProgressIDAsCSV value
     * @param string $_progressIDAsCSV the ProgressIDAsCSV
     * @return string
     */
    public function setProgressIDAsCSV($_progressIDAsCSV)
    {
        return ($this->ProgressIDAsCSV = $_progressIDAsCSV);
    }
    /**
     * Get AdvertCandidateIDAsCSV value
     * @return string|null
     */
    public function getAdvertCandidateIDAsCSV()
    {
        return $this->AdvertCandidateIDAsCSV;
    }
    /**
     * Set AdvertCandidateIDAsCSV value
     * @param string $_advertCandidateIDAsCSV the AdvertCandidateIDAsCSV
     * @return string
     */
    public function setAdvertCandidateIDAsCSV($_advertCandidateIDAsCSV)
    {
        return ($this->AdvertCandidateIDAsCSV = $_advertCandidateIDAsCSV);
    }
    /**
     * Get boolIncludeEmailBody value
     * @return string|null
     */
    public function getBoolIncludeEmailBody()
    {
        return $this->boolIncludeEmailBody;
    }
    /**
     * Set boolIncludeEmailBody value
     * @param string $_boolIncludeEmailBody the boolIncludeEmailBody
     * @return string
     */
    public function setBoolIncludeEmailBody($_boolIncludeEmailBody)
    {
        return ($this->boolIncludeEmailBody = $_boolIncludeEmailBody);
    }
    /**
     * Get boolIncludeAttachment value
     * @return string|null
     */
    public function getBoolIncludeAttachment()
    {
        return $this->boolIncludeAttachment;
    }
    /**
     * Set boolIncludeAttachment value
     * @param string $_boolIncludeAttachment the boolIncludeAttachment
     * @return string
     */
    public function setBoolIncludeAttachment($_boolIncludeAttachment)
    {
        return ($this->boolIncludeAttachment = $_boolIncludeAttachment);
    }
    /**
     * Get boolIncludeParsed value
     * @return string|null
     */
    public function getBoolIncludeParsed()
    {
        return $this->boolIncludeParsed;
    }
    /**
     * Set boolIncludeParsed value
     * @param string $_boolIncludeParsed the boolIncludeParsed
     * @return string
     */
    public function setBoolIncludeParsed($_boolIncludeParsed)
    {
        return ($this->boolIncludeParsed = $_boolIncludeParsed);
    }
    /**
     * Get boolIncludeEmail value
     * @return string|null
     */
    public function getBoolIncludeEmail()
    {
        return $this->boolIncludeEmail;
    }
    /**
     * Set boolIncludeEmail value
     * @param string $_boolIncludeEmail the boolIncludeEmail
     * @return string
     */
    public function setBoolIncludeEmail($_boolIncludeEmail)
    {
        return ($this->boolIncludeEmail = $_boolIncludeEmail);
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
     * @return LogicMelonStructGetApplicationsPaged
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
