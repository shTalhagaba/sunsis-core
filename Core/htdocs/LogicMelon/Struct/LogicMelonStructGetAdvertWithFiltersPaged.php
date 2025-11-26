<?php
/**
 * File for class LogicMelonStructGetAdvertWithFiltersPaged
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertWithFiltersPaged originally named GetAdvertWithFiltersPaged
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertWithFiltersPaged extends LogicMelonWsdlClass
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
     * The sSearchDays
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSearchDays;
    /**
     * The Filters
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfNameValue
     */
    public $Filters;
    /**
     * The sAdvertStatusIDs
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertStatusIDs;
    /**
     * The sHideFutureJobs
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sHideFutureJobs;
    /**
     * The OrderBy
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $OrderBy;
    /**
     * Constructor method for GetAdvertWithFiltersPaged
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
     * @param string $_sSearchDays
     * @param LogicMelonStructArrayOfNameValue $_filters
     * @param string $_sAdvertStatusIDs
     * @param string $_sHideFutureJobs
     * @param string $_orderBy
     * @return LogicMelonStructGetAdvertWithFiltersPaged
     */
    public function __construct($_currentPage,$_rowsPerPage,$_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sUserIdentifier = NULL,$_sStartOrganisation = NULL,$_sAdvertIdentifier = NULL,$_sAdvertReference = NULL,$_sAdvertID = NULL,$_sSearchDays = NULL,$_filters = NULL,$_sAdvertStatusIDs = NULL,$_sHideFutureJobs = NULL,$_orderBy = NULL)
    {
        parent::__construct(array('CurrentPage'=>$_currentPage,'RowsPerPage'=>$_rowsPerPage,'sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sUserIdentifier'=>$_sUserIdentifier,'sStartOrganisation'=>$_sStartOrganisation,'sAdvertIdentifier'=>$_sAdvertIdentifier,'sAdvertReference'=>$_sAdvertReference,'sAdvertID'=>$_sAdvertID,'sSearchDays'=>$_sSearchDays,'Filters'=>($_filters instanceof LogicMelonStructArrayOfNameValue)?$_filters:new LogicMelonStructArrayOfNameValue($_filters),'sAdvertStatusIDs'=>$_sAdvertStatusIDs,'sHideFutureJobs'=>$_sHideFutureJobs,'OrderBy'=>$_orderBy),false);
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
     * Get sSearchDays value
     * @return string|null
     */
    public function getSSearchDays()
    {
        return $this->sSearchDays;
    }
    /**
     * Set sSearchDays value
     * @param string $_sSearchDays the sSearchDays
     * @return string
     */
    public function setSSearchDays($_sSearchDays)
    {
        return ($this->sSearchDays = $_sSearchDays);
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
     * Get sAdvertStatusIDs value
     * @return string|null
     */
    public function getSAdvertStatusIDs()
    {
        return $this->sAdvertStatusIDs;
    }
    /**
     * Set sAdvertStatusIDs value
     * @param string $_sAdvertStatusIDs the sAdvertStatusIDs
     * @return string
     */
    public function setSAdvertStatusIDs($_sAdvertStatusIDs)
    {
        return ($this->sAdvertStatusIDs = $_sAdvertStatusIDs);
    }
    /**
     * Get sHideFutureJobs value
     * @return string|null
     */
    public function getSHideFutureJobs()
    {
        return $this->sHideFutureJobs;
    }
    /**
     * Set sHideFutureJobs value
     * @param string $_sHideFutureJobs the sHideFutureJobs
     * @return string
     */
    public function setSHideFutureJobs($_sHideFutureJobs)
    {
        return ($this->sHideFutureJobs = $_sHideFutureJobs);
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
     * @return LogicMelonStructGetAdvertWithFiltersPaged
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
