<?php
/**
 * File for class LogicMelonStructGetAdvertWithValuesWithFilters
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertWithValuesWithFilters originally named GetAdvertWithValuesWithFilters
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertWithValuesWithFilters extends LogicMelonWsdlClass
{
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
     * The FieldIDs
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $FieldIDs;
    /**
     * The Destinations
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $Destinations;
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
     * Constructor method for GetAdvertWithValuesWithFilters
     * @see parent::__construct()
     * @param string $_sCultureID
     * @param string $_sAPIKey
     * @param string $_sUsername
     * @param string $_sUserIdentifier
     * @param string $_sStartOrganisation
     * @param string $_sAdvertIdentifier
     * @param string $_sAdvertReference
     * @param string $_sAdvertID
     * @param string $_sSearchDays
     * @param LogicMelonStructArrayOfString $_fieldIDs
     * @param LogicMelonStructArrayOfString $_destinations
     * @param LogicMelonStructArrayOfNameValue $_filters
     * @param string $_sAdvertStatusIDs
     * @param string $_sHideFutureJobs
     * @return LogicMelonStructGetAdvertWithValuesWithFilters
     */
    public function __construct($_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sUserIdentifier = NULL,$_sStartOrganisation = NULL,$_sAdvertIdentifier = NULL,$_sAdvertReference = NULL,$_sAdvertID = NULL,$_sSearchDays = NULL,$_fieldIDs = NULL,$_destinations = NULL,$_filters = NULL,$_sAdvertStatusIDs = NULL,$_sHideFutureJobs = NULL)
    {
        parent::__construct(array('sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sUserIdentifier'=>$_sUserIdentifier,'sStartOrganisation'=>$_sStartOrganisation,'sAdvertIdentifier'=>$_sAdvertIdentifier,'sAdvertReference'=>$_sAdvertReference,'sAdvertID'=>$_sAdvertID,'sSearchDays'=>$_sSearchDays,'FieldIDs'=>($_fieldIDs instanceof LogicMelonStructArrayOfString)?$_fieldIDs:new LogicMelonStructArrayOfString($_fieldIDs),'Destinations'=>($_destinations instanceof LogicMelonStructArrayOfString)?$_destinations:new LogicMelonStructArrayOfString($_destinations),'Filters'=>($_filters instanceof LogicMelonStructArrayOfNameValue)?$_filters:new LogicMelonStructArrayOfNameValue($_filters),'sAdvertStatusIDs'=>$_sAdvertStatusIDs,'sHideFutureJobs'=>$_sHideFutureJobs),false);
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
     * Get FieldIDs value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getFieldIDs()
    {
        return $this->FieldIDs;
    }
    /**
     * Set FieldIDs value
     * @param LogicMelonStructArrayOfString $_fieldIDs the FieldIDs
     * @return LogicMelonStructArrayOfString
     */
    public function setFieldIDs($_fieldIDs)
    {
        return ($this->FieldIDs = $_fieldIDs);
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertWithValuesWithFilters
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
