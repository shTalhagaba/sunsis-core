<?php
/**
 * File for class LogicMelonStructAddAdvertCandidateWithFilters
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAddAdvertCandidateWithFilters originally named AddAdvertCandidateWithFilters
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAddAdvertCandidateWithFilters extends LogicMelonWsdlClass
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
     * The sFeedID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sFeedID;
    /**
     * The sFeedIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sFeedIdentifier;
    /**
     * The sCandidateEmail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCandidateEmail;
    /**
     * The sCandidateFirstName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCandidateFirstName;
    /**
     * The sCandidateLastName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCandidateLastName;
    /**
     * The sCandidateHomePhone
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCandidateHomePhone;
    /**
     * The sCandidateWorkPhone
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCandidateWorkPhone;
    /**
     * The sCandidateMobilePhone
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCandidateMobilePhone;
    /**
     * The Filters
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfNameValue
     */
    public $Filters;
    /**
     * The CandidateDetail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfNameValue
     */
    public $CandidateDetail;
    /**
     * Constructor method for AddAdvertCandidateWithFilters
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
     * @param string $_sFeedID
     * @param string $_sFeedIdentifier
     * @param string $_sCandidateEmail
     * @param string $_sCandidateFirstName
     * @param string $_sCandidateLastName
     * @param string $_sCandidateHomePhone
     * @param string $_sCandidateWorkPhone
     * @param string $_sCandidateMobilePhone
     * @param LogicMelonStructArrayOfNameValue $_filters
     * @param LogicMelonStructArrayOfNameValue $_candidateDetail
     * @return LogicMelonStructAddAdvertCandidateWithFilters
     */
    public function __construct($_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sUserIdentifier = NULL,$_sStartOrganisation = NULL,$_sAdvertIdentifier = NULL,$_sAdvertReference = NULL,$_sAdvertID = NULL,$_sSearchDays = NULL,$_sFeedID = NULL,$_sFeedIdentifier = NULL,$_sCandidateEmail = NULL,$_sCandidateFirstName = NULL,$_sCandidateLastName = NULL,$_sCandidateHomePhone = NULL,$_sCandidateWorkPhone = NULL,$_sCandidateMobilePhone = NULL,$_filters = NULL,$_candidateDetail = NULL)
    {
        parent::__construct(array('sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sUserIdentifier'=>$_sUserIdentifier,'sStartOrganisation'=>$_sStartOrganisation,'sAdvertIdentifier'=>$_sAdvertIdentifier,'sAdvertReference'=>$_sAdvertReference,'sAdvertID'=>$_sAdvertID,'sSearchDays'=>$_sSearchDays,'sFeedID'=>$_sFeedID,'sFeedIdentifier'=>$_sFeedIdentifier,'sCandidateEmail'=>$_sCandidateEmail,'sCandidateFirstName'=>$_sCandidateFirstName,'sCandidateLastName'=>$_sCandidateLastName,'sCandidateHomePhone'=>$_sCandidateHomePhone,'sCandidateWorkPhone'=>$_sCandidateWorkPhone,'sCandidateMobilePhone'=>$_sCandidateMobilePhone,'Filters'=>($_filters instanceof LogicMelonStructArrayOfNameValue)?$_filters:new LogicMelonStructArrayOfNameValue($_filters),'CandidateDetail'=>($_candidateDetail instanceof LogicMelonStructArrayOfNameValue)?$_candidateDetail:new LogicMelonStructArrayOfNameValue($_candidateDetail)),false);
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
     * Get sFeedID value
     * @return string|null
     */
    public function getSFeedID()
    {
        return $this->sFeedID;
    }
    /**
     * Set sFeedID value
     * @param string $_sFeedID the sFeedID
     * @return string
     */
    public function setSFeedID($_sFeedID)
    {
        return ($this->sFeedID = $_sFeedID);
    }
    /**
     * Get sFeedIdentifier value
     * @return string|null
     */
    public function getSFeedIdentifier()
    {
        return $this->sFeedIdentifier;
    }
    /**
     * Set sFeedIdentifier value
     * @param string $_sFeedIdentifier the sFeedIdentifier
     * @return string
     */
    public function setSFeedIdentifier($_sFeedIdentifier)
    {
        return ($this->sFeedIdentifier = $_sFeedIdentifier);
    }
    /**
     * Get sCandidateEmail value
     * @return string|null
     */
    public function getSCandidateEmail()
    {
        return $this->sCandidateEmail;
    }
    /**
     * Set sCandidateEmail value
     * @param string $_sCandidateEmail the sCandidateEmail
     * @return string
     */
    public function setSCandidateEmail($_sCandidateEmail)
    {
        return ($this->sCandidateEmail = $_sCandidateEmail);
    }
    /**
     * Get sCandidateFirstName value
     * @return string|null
     */
    public function getSCandidateFirstName()
    {
        return $this->sCandidateFirstName;
    }
    /**
     * Set sCandidateFirstName value
     * @param string $_sCandidateFirstName the sCandidateFirstName
     * @return string
     */
    public function setSCandidateFirstName($_sCandidateFirstName)
    {
        return ($this->sCandidateFirstName = $_sCandidateFirstName);
    }
    /**
     * Get sCandidateLastName value
     * @return string|null
     */
    public function getSCandidateLastName()
    {
        return $this->sCandidateLastName;
    }
    /**
     * Set sCandidateLastName value
     * @param string $_sCandidateLastName the sCandidateLastName
     * @return string
     */
    public function setSCandidateLastName($_sCandidateLastName)
    {
        return ($this->sCandidateLastName = $_sCandidateLastName);
    }
    /**
     * Get sCandidateHomePhone value
     * @return string|null
     */
    public function getSCandidateHomePhone()
    {
        return $this->sCandidateHomePhone;
    }
    /**
     * Set sCandidateHomePhone value
     * @param string $_sCandidateHomePhone the sCandidateHomePhone
     * @return string
     */
    public function setSCandidateHomePhone($_sCandidateHomePhone)
    {
        return ($this->sCandidateHomePhone = $_sCandidateHomePhone);
    }
    /**
     * Get sCandidateWorkPhone value
     * @return string|null
     */
    public function getSCandidateWorkPhone()
    {
        return $this->sCandidateWorkPhone;
    }
    /**
     * Set sCandidateWorkPhone value
     * @param string $_sCandidateWorkPhone the sCandidateWorkPhone
     * @return string
     */
    public function setSCandidateWorkPhone($_sCandidateWorkPhone)
    {
        return ($this->sCandidateWorkPhone = $_sCandidateWorkPhone);
    }
    /**
     * Get sCandidateMobilePhone value
     * @return string|null
     */
    public function getSCandidateMobilePhone()
    {
        return $this->sCandidateMobilePhone;
    }
    /**
     * Set sCandidateMobilePhone value
     * @param string $_sCandidateMobilePhone the sCandidateMobilePhone
     * @return string
     */
    public function setSCandidateMobilePhone($_sCandidateMobilePhone)
    {
        return ($this->sCandidateMobilePhone = $_sCandidateMobilePhone);
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
     * Get CandidateDetail value
     * @return LogicMelonStructArrayOfNameValue|null
     */
    public function getCandidateDetail()
    {
        return $this->CandidateDetail;
    }
    /**
     * Set CandidateDetail value
     * @param LogicMelonStructArrayOfNameValue $_candidateDetail the CandidateDetail
     * @return LogicMelonStructArrayOfNameValue
     */
    public function setCandidateDetail($_candidateDetail)
    {
        return ($this->CandidateDetail = $_candidateDetail);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAddAdvertCandidateWithFilters
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
